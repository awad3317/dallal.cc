<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إشعار رسائل جديدة</title>
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
        .messages-summary {
            font-size: 18px;
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid rgb(245 202 88);
        }
        .sender-list {
            margin: 25px 0;
            border: 1px solid #eee;
            border-radius: 8px;
            overflow: hidden;
        }
        .sender-item {
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s;
        }
        .sender-item:last-child {
            border-bottom: none;
        }
        .sender-item:hover {
            background-color: #f9f9f9;
        }
        .sender-name {
            font-weight: 600;
            color: #2c3e50;
        }
        .message-count {
            background-color:rgb(245 202 88);
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 14px;
        }
        .action-btn {
            display: inline-block;
            margin-top: 20px;
            background-color: rgb(245 202 88);
            color: rgb(255, 255, 255);
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
        }
        .action-btn:hover {
            background-color:#f5c651;
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
            <img src="{{ config('app.url') }}/dalal-logo.svg" alt="شعار منصة دلال" class="logo" width="50" height="50">
            <h1>رسائل جديدة</h1>
        </div>
        
        <p>مرحباً {{ $mailData['user']->name }}،</p>
        
        <div class="messages-summary">
            لديك <strong>{{ $mailData['total_unread'] }} رسالة جديدة</strong> من 
            <strong>{{ count($mailData['senders']) }} أشخاص</strong>
        </div>
        
        <div class="sender-list">
            @foreach($mailData['senders'] as $sender)
            <div class="sender-item">
                <span class="sender-name">{{ $sender['name'] }}</span>
                <span class="message-count">{{ $sender['unread_count'] }} رسالة</span>
            </div>
            @endforeach
        </div>
        
        <div style="text-align: center;">
            <a href="https://dalal-front-end.vercel.app/messages" class="action-btn">عرض جميع المحادثات</a>
        </div>
        
        <div class="footer">
            <p>شكراً لاستخدامك خدماتنا!</p>
            <p>فريق منصة دلال</p>
        </div>
    </div>
</body>
<script>
    document.querySelector('.action-btn').addEventListener('click', (e) => {
    e.preventDefault();
    window.location.href = 'https://dallal.cc/messages';
});
</script>
</html>