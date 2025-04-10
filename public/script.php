<?php
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\WelcomeMail;

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
        Mail::to($user->email)->send(new WelcomeMail($user));
        echo "تم إرسال رسالة ترحيبية إلى: {$user->email}\n";
    } catch (\Exception $e) {
        echo "حدث خطأ أثناء الإرسال إلى {$user->email}: " . $e->getMessage() . "\n";
    }
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