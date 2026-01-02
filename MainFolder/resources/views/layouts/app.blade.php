<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduRate - Faculty Evaluation System</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .bg-maroon { background-color: #800000; }
        .text-maroon { color: #800000; }
        .bg-gold { background-color: #FFB800; }
        .hero-overlay { background: rgba(128, 0, 0, 0.75); }
    </style>
</head>
<body class="font-sans antialiased text-gray-900">
    @yield('content')
</body>
</html>