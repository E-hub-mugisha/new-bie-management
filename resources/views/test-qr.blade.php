<!DOCTYPE html>
<html>

<head>
    <title>QR Code Test</title>
</head>

<body>
    <h1>QR Code for {{ $visitor_number }}</h1>
    <img src="{{ $qrUrl }}" alt="QR Code">

    <p><a href="{{ $qrUrl }}" target="_blank">Open QR Image</a></p>
</body>

</html>