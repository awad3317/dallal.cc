<?php

use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\WelcomeMail;
// use App\Mail\NewMessagesNotification;


// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';
// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = User::where('id','=',3)->get();

Mail::to($users->email)->send(new WelcomeMail($users));
echo "تم إرسال رسالة ترحيبية إلى: {$user->email}\n";
