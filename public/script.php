<?php
use App\Models\User;
use App\Mail\WelcomeMail;
use App\Models\Conversation;
use Illuminate\Support\Facades\Mail;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = User::where('receive_email_notifications', '=',true)->get();
if ($users->isEmpty()) {
    echo "لا يوجد مستخدمون مفعلون لاستقبال الإشعارات البريدية\n";
    exit;
}
foreach ($users as $user) {
    try {
        
        $unreadConversations = Conversation::with(['sender', 'receiver', 'messages' => function($q) use ($user) {
                $q->where('receiver_id', $user->id)
                  ->where('is_read', false);
            }])
            ->where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->get()
            ->filter(function($conversation) use ($user) {
                
                $conversation->other_user = $conversation->sender_id == $user->id 
                    ? $conversation->receiver 
                    : $conversation->sender;
                
                $conversation->unread_count = $conversation->messages->count();
                
                return $conversation->unread_count > 0;
            });

        if ($unreadConversations->isNotEmpty()) {
            $senders = $unreadConversations->map(function($conv) {
                return [
                    'name' => $conv->other_user->name,
                    'unread_count' => $conv->unread_count
                ];
            });

            
            Mail::to($user->email)->send(new WelcomeMail([
                'user' => $user,
                'senders' => $senders,
                'total_unread' => $unreadConversations->sum('unread_count')
            ]));
            
            echo "تم إرسال إشعار إلى: {$user->email} - عدد المرسلين: {$senders->count()}\n";
        }
    } catch (\Exception $e) {
        echo "حدث خطأ أثناء الإرسال إلى {$user->email}: " . $e->getMessage() . "\n";
    }
}