<!DOCTYPE html>
<html>
<head>
    <title>Visit Confirmation</title>
</head>
<body>
    <h2>Hello {{ $visitor->name }},</h2>
    <p>Your visit has been successfully registered with the following details:</p>

    @php
        $latestHistory = $visitor->histories->sortByDesc('created_at')->first();
    @endphp

    @if($latestHistory)
        <ul>
            <li><strong>Visitor Number:</strong> {{ $visitor->visitor_number }}</li>
            <li><strong>Name:</strong> {{ $visitor->name }}</li>
            <li><strong>Phone:</strong> {{ $visitor->phone }}</li>
            <li><strong>Purpose:</strong> {{ $latestHistory->purpose }}</li>
            <li><strong>Check-in Time:</strong> {{ \Carbon\Carbon::parse($latestHistory->check_in)->format('F j, Y, g:i A') }}</li>
        </ul>
    @else
        <p>No visit history was found for your profile.</p>
    @endif

    <p>Thank you for visiting!</p>
</body>
</html>
