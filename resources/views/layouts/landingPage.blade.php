<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>E-SIPUS</title>
    <link rel="icon" href="{{ asset('assets/Logo_E-Sipus.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Modal Animation */
        #loginModal, #registerModal {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        #loginModal > div, #registerModal > div {
            animation: slideUp 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 flex flex-col antialiased">

    @include('components.navbar')

    <main class="flex-1">
        @yield('content')
    </main>

</body>
</html>
