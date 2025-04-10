<?php
use App\Models\User;
use App\Mail\WelcomeMail;
use App\Models\Conversation;
use Illuminate\Support\Facades\Mail;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// الحصول على المستخدم ككائن فردي (ليس مجموعة)
// $user = User::where('id', 3)->first(['id', 'name', 'email']);
$user = User::find(3);
if (!$user) {
    echo "لم يتم العثور على المستخدم\n";
    exit;
}
if (!$user->receive_email_notifications) {
    echo "المستخدم غير مفعل لاستقبال الإشعارات البريدية\n";
    exit;
}
$unreadCount = Conversation::where(function($query) use ($user) {
    $query->where('sender_id', $user->id)
          ->orWhere('receiver_id', $user->id);
})
->whereHas('messages', function($query) use ($user) {
    $query->where('receiver_id', $user->id)
          ->where('is_read', false);
})
->count();
if ($unreadCount > 0) {
    try {
        Mail::to($user->email)->send(new WelcomeMail( $user, $unreadCount));
        
        echo "تم إرسال إشعار إلى: {$user->email} - عدد المحادثات غير المقروءة: {$unreadCount}\n";
    } catch (\Exception $e) {
        echo "خطأ في الإرسال: " . $e->getMessage() . "\n";
    }
} else {
    echo "لا توجد محادثات غير مقروءة\n";
}

// if ($user) {
//     try {
//         Mail::to($user->email)->send(new WelcomeMail($user));
//         echo "تم إرسال رسالة ترحيبية إلى: {$user->email}\n";
//     } catch (\Exception $e) {
//         echo "حدث خطأ أثناء الإرسال: " . $e->getMessage() . "\n";
//     }
// } else {
//     echo "لم يتم العثور على المستخدم ذو ID 3\n";
// }