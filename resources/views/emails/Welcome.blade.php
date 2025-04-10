<!DOCTYPE html>
<html>
<head>
    <title>إشعار رسائل غير مقروءة</title>
</head>
<body>
    <h1>مرحباً {{ $user->name }}!</h1>
    
    <p>لديك {{$unread_count }} محادثة تحتوي على رسائل غير مقروءة.</p>
    
    <p>
        <a href="{{ url('/messages') }}">اضغط هنا لعرض المحادثات</a>
    </p>
</body>
</html>