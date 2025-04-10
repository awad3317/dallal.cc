<?php
use App\Models\User;
use App\Mail\OtpMail;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// الحصول على المستخدم ككائن فردي (ليس مجموعة)
$user = User::where('id', 3)->first(['id', 'name', 'email']);

if ($user) {
    try {
        $fields['email']=$user->email;
        $otp=1221;
        Mail::to($fields['email'])->send(new OtpMail($otp));
        // Mail::to($user->email)->send(new WelcomeMail($user));
        echo "تم إرسال رسالة ترحيبية إلى: {$user->email}\n";
    } catch (\Exception $e) {
        echo "حدث خطأ أثناء الإرسال: " . $e->getMessage() . "\n";
    }
} else {
    echo "لم يتم العثور على المستخدم ذو ID 3\n";
}