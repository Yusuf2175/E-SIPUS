<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - e-SIPUS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/Logo_E-Sipus.webp') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <div class="absolute inset-0 z-0">
        <img src="{{ asset('assets/background.png') }}" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 backdrop-blur-sm bg-black/20"></div>
    </div>

    <div class="w-full max-w-5xl relative z-10">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="grid md:grid-cols-2">

                <!-- Left Side - Form -->
                <div class="p-12">
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-slate-900 mb-2">Sign in to e-SIPUS</h1>
                        <p class="text-slate-600">to continue to your library</p>
                    </div>

                    {{--
                        Form asli hanya berisi CSRF + hidden inputs.
                        Field yang terlihat berada DI LUAR form (pakai form="loginForm")
                        sehingga Chrome tidak mengenalinya sebagai form login
                        dan tidak akan autofill.
                    --}}
                    <form id="loginForm" method="POST" action="{{ route('login') }}">
                        @csrf
                        <input type="hidden" id="real_email"    name="email">
                        <input type="hidden" id="real_password" name="password">
                        <input type="hidden" id="real_remember" name="remember" value="">
                    </form>

                    <div class="space-y-5">

                        <!-- Email visible (di luar form) -->
                        <div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <input id="vis_email" type="text"
                                       autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                                       class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-slate-900 placeholder-slate-400"
                                       placeholder="Username or email address">
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password visible (di luar form) -->
                        <div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input id="vis_password" type="text"
                                       autocomplete="off"
                                       style="-webkit-text-security:disc"
                                       class="w-full pl-12 pr-12 py-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-slate-900 placeholder-slate-400"
                                       placeholder="Password">
                                <button type="button" id="togglePwd"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition">
                                    <svg id="eyeOn" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg id="eyeOff" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <input type="checkbox" id="vis_remember"
                                   class="w-4 h-4 rounded border-slate-300 text-purple-600 focus:ring-purple-500 focus:ring-offset-0 transition">
                            <label for="vis_remember" class="ml-2 text-sm text-slate-600">I agree to the terms of service</label>
                        </div>

                        <!-- Submit -->
                        <button type="button" id="submitBtn"
                                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white py-3.5 px-4 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                            Sign In
                        </button>
                    </div>

                    <!-- Footer Links -->
                    <div class="mt-8 text-center">
                        <p class="text-sm text-slate-600">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="text-purple-600 hover:text-purple-700 font-semibold transition">Create account</a>
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('landingPage') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 transition group">
                                <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Back to Home
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Side -->
                <div class="hidden md:flex bg-gradient-to-br from-indigo-50 to-purple-50 p-12 items-center justify-center relative overflow-hidden">
                    <div class="relative z-10 w-full h-full flex items-center justify-center">
                        <img src="{{ asset('assets/ilustrasi.png') }}" alt="Library Illustration" class="w-full h-full object-contain max-w-lg">
                    </div>
                </div>

            </div>
        </div>
    </div>

<script>
(function () {
    const visEmail    = document.getElementById('vis_email');
    const visPwd      = document.getElementById('vis_password');
    const visRemember = document.getElementById('vis_remember');
    const realEmail   = document.getElementById('real_email');
    const realPwd     = document.getElementById('real_password');
    const realRemember= document.getElementById('real_remember');
    const submitBtn   = document.getElementById('submitBtn');
    const toggleBtn   = document.getElementById('togglePwd');
    const eyeOn       = document.getElementById('eyeOn');
    const eyeOff      = document.getElementById('eyeOff');
    let   showPwd     = false;

    // Kosongkan saat load — cegah browser restore
    window.addEventListener('pageshow', function () {
        visEmail.value = '';
        visPwd.value   = '';
    });

    // Toggle show/hide password
    toggleBtn.addEventListener('click', function () {
        showPwd = !showPwd;
        visPwd.style.webkitTextSecurity = showPwd ? 'none' : 'disc';
        eyeOn.classList.toggle('hidden', showPwd);
        eyeOff.classList.toggle('hidden', !showPwd);
        visEmail.focus(); visEmail.blur(); // refocus trick
        visPwd.focus();
    });

    // Submit: salin nilai visible ke hidden, lalu submit form
    submitBtn.addEventListener('click', function () {
        realEmail.value    = visEmail.value.trim();
        realPwd.value      = visPwd.value;
        realRemember.value = visRemember.checked ? '1' : '';
        document.getElementById('loginForm').submit();
    });

    // Enter key support
    [visEmail, visPwd].forEach(function (el) {
        el.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') submitBtn.click();
        });
    });

    // Autofocus email
    visEmail.focus();
})();
</script>

</body>
</html>
