<!DOCTYPE html>
<html>
<head>
    <title>Visit Confirmation</title>
</head>
<body>
    <h2>Hello {{ $visitor->name }},</h2>
    <p>Your visit has been successfully registered with the following details:</p>
    <ul>
        <li><strong>Visitor Number:</strong> {{ $visitor->visitor_number }}</li>
        <li><strong>Name:</strong> {{ $visitor->name }}</li>
        <li><strong>Phone:</strong> {{ $visitor->phone }}</li>
        <li><strong>Purpose:</strong> {{ $visitor->histories->purpose }}</li>
        <li><strong>Check-in Time:</strong> {{ $visitor->histories->check_in }}</li>
    </ul>
    <p>Thank you for visiting!</p>
</body>
</html>
