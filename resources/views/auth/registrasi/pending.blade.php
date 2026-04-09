<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Pending - e-SIPUS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/Logo_E-Sipus.webp') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <div class="absolute inset-0 z-0">
        <img src="{{ asset('assets/background.png') }}" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 backdrop-blur-sm bg-black/20"></div>
    </div>

    <div class="w-full max-w-lg relative z-10">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden p-10 text-center">

            {{-- Icon --}}
            <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-slate-900 mb-2">Registration Submitted!</h1>
            <p class="text-slate-500 text-sm mb-6">
                Your account is currently <span class="font-semibold text-yellow-600">pending approval</span>
                from our admin or staff. You will be able to log in once your account has been reviewed.
            </p>

            @if(session('pending_email'))
                <div class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 mb-6 text-sm text-slate-600">
                    Registered email: <span class="font-semibold text-slate-800">{{ session('pending_email') }}</span>
                </div>
            @endif

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-8 text-left">
                <h3 class="text-sm font-semibold text-blue-800 mb-2">What happens next?</h3>
                <ul class="text-sm text-blue-700 space-y-1.5">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                        </svg>
                        Admin or staff will review your registration
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                        </svg>
                        Once approved, you can log in with your credentials
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                        </svg>
                        If rejected, you will see the reason when trying to log in
                    </li>
                </ul>
            </div>

            <div class="flex flex-col gap-3">
                <a href="{{ route('login') }}"
                   class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white py-3 px-4 rounded-xl font-semibold text-sm transition-all">
                    Go to Login
                </a>
                <a href="{{ route('landingPage') }}"
                   class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 py-3 px-4 rounded-xl font-semibold text-sm transition-all">
                    Back to Home
                </a>
            </div>

            <p class="mt-6 text-xs text-slate-400">e-SIPUS &copy; {{ date('Y') }} — Digital Library System</p>
        </div>
    </div>

</body>
</html>
