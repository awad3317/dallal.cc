<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق الخاص بك</title>
    <style>
        * {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
        }
        body {
            direction: rtl;
            unicode-bidi: embed;
            line-height: 1.8;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        .logo {
            width: 50px;
            height: 50px;
        }
        h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 24px;
        }
        .otp-code {
            font-size: 28px;
            font-weight: bold;
            color: #e74c3c;
            text-align: center;
            margin: 25px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
            border: 1px dashed #ddd;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #7f8c8d;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        p {
            margin: 15px 0;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://dallal.cc/dalal-logo.svg" alt="شعار منصة دلال" class="logo" width="50" height="50">
            <h1>رمز التحقق</h1>
        </div>
        
        <p>مرحباً بك،</p>
        <p>لإتمام عملية التحقق، يرجى استخدام رمز OTP التالي:</p>
        
        <div class="otp-code">{{ $otp }}</div>
        
        <p>ملاحظة: هذا الرمز صالح لمدة <strong>10 دقائق</strong> فقط ولا تشاركه مع أي شخص.</p>
        
        <div class="footer">
            <p>شكراً لاستخدامك خدماتنا!</p>
            <p>فريق منصة دلال</p>
        </div>
    </div>
</body>
</html>