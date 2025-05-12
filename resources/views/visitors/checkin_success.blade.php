<!DOCTYPE html>
<html>
<head>
    <title>Check-in Success</title>
</head>
<body>
    <h2>Welcome, {{ $visitor->name }}</h2>
    <p>You have successfully checked in. Your visit is now registered.</p>
    <p><strong>Visitor Number:</strong> {{ $visitor->visitor_number }}</p>
    <p><strong>Purpose:</strong> {{ $visitor->history->purpose }}</p>
    <p><strong>Check-in Time:</strong> {{ $visitor->history->check_in }}</p>
</body>
</html>
