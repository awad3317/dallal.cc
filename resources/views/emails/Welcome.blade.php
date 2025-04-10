<!DOCTYPE html>
<html>
<head>
    <title>إشعار رسائل جديدة</title>
    <style>
        .sender-list { margin: 20px 0; }
        .sender-item { 
            padding: 10px; 
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <h1>مرحباً {{ $mailData['user']->name }}!</h1>
    
    <p>لديك {{ $mailData['total_unread'] }} رسالة جديدة من {{ count($mailData['senders']) }} أشخاص:</p>
    
    <div class="sender-list">
        @foreach($mailData['senders'] as $sender)
        <div class="sender-item">
            <span>{{ $sender['name'] }}</span>
            <span>{{ $sender['unread_count'] }} رسالة</span>
        </div>
        @endforeach
    </div>
    
    <p>
        <a href="{{ url('/messages') }}" style="background: #4CAF50; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
            عرض المحادثات
        </a>
    </p>
</body>
</html>