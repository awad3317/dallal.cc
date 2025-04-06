<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق الخاص بك</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            text-align: right;
        }
        .header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        .logo {
            width: 50px;
            height: 50px;
        }
        h1 {
            color: #2c3e50;
        }
        .otp-code {
            font-size: 24px;
            font-weight: bold;
            color: #e74c3c;
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('dalal-logo.svg') }}" alt="شعار منصة دلال" class="logo" width="50" height="50">
        <h1>رمز التحقق </h1>
    </div>
    
    <p>مرحباً بك،</p>
    <p>لإتمام عملية التحقق، يرجى استخدام رمز OTP التالي:</p>
    
    <div class="otp-code">{{ $otp }}</div>
    
    <p>ملاحظة: هذا الرمز صالح لمدة <strong>10 دقائق</strong> فقط ولا تشاركه مع أي شخص.</p>
    
    <div class="footer">
        <p>شكراً لاستخدامك خدماتنا!</p>
        <p>فريق المنصة</p>
    </div>
</body>
</html>