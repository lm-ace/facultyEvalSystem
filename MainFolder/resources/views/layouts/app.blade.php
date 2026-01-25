<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>@yield('title', 'EduRate - Faculty Evaluation System')</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        .bg-maroon { background-color: #800000; }
        .text-maroon { color: #800000; }
        .bg-gold { background-color: #FFB800; }
        .hero-overlay { background: rgba(128, 0, 0, 0.75); }
        
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #800000; }
        ::-webkit-scrollbar-thumb:hover { background: #660000; }
    </style>
</head>
<body class="font-sans antialiased text-gray-900">
    
    @yield('content')

</body>
</html>