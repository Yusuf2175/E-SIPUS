<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - e-SIPUS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/Logo_E-Sipus.webp') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Background -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('assets/background.png') }}" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 backdrop-blur-sm bg-black/20"></div>
    </div>

    <div class="w-full max-w-5xl relative z-10 my-6">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="flex flex-col md:grid md:grid-cols-2">

                <!-- Left Side - Form -->
                <div class="p-6 sm:p-8 md:p-10 order-1">
                    <div class="mb-5">
                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-1">Create your account</h1>
                        <p class="text-sm text-slate-500">to start your library journey</p>
                    </div>

                    {{--
                        Form hanya berisi CSRF + hidden inputs.
                        Field visible berada DI LUAR form sehingga browser
                        tidak mengenalinya sebagai form register dan tidak autofill.
                    --}}
                    <form id="registerForm" method="POST" action="{{ route('register') }}">
                        @csrf
                        <input type="hidden" id="real_name"     name="name">
                        <input type="hidden" id="real_email"    name="email">
                        <input type="hidden" id="real_address"  name="address">
                        <input type="hidden" id="real_province" name="province">
                        <input type="hidden" id="real_city"     name="city">
                        <input type="hidden" id="real_password" name="password">
                        <input type="hidden" id="real_password_confirmation" name="password_confirmation">
                    </form>

                    <div class="space-y-3.5">

                        <!-- Name -->
                        <div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <input id="vis_name" type="text"
                                       autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                                       class="w-full pl-12 pr-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-slate-900 placeholder-slate-400 text-sm"
                                       placeholder="Full name">
                            </div>
                            @error('name')
                                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                    </svg>
                                </div>
                                <input id="vis_email" type="text"
                                       autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                                       class="w-full pl-12 pr-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-slate-900 placeholder-slate-400 text-sm"
                                       placeholder="Email address">
                            </div>
                            @error('email')
                                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <div class="relative">
                                <div class="absolute top-3 left-0 pl-4 flex items-start pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <textarea id="vis_address" rows="2"
                                          autocomplete="off"
                                          class="w-full pl-12 pr-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-slate-900 placeholder-slate-400 resize-none text-sm"
                                          placeholder="Your address"></textarea>
                            </div>
                            @error('address')
                                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Provinsi -->
                        <div>
                            <select id="vis_province"
                                    class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-slate-900 text-sm">
                                <option value="">-- Pilih Provinsi --</option>
                            </select>
                            @error('province')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kabupaten / Kota -->
                        <div>
                            <select id="vis_city" disabled
                                    class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-slate-900 text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                <option value="">-- Pilih Provinsi dulu --</option>
                            </select>
                            @error('city')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input id="vis_password" type="text"
                                       autocomplete="off"
                                       style="-webkit-text-security:disc"
                                       class="w-full pl-12 pr-12 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-slate-900 placeholder-slate-400 text-sm"
                                       placeholder="Password">
                                <button type="button" id="togglePwd"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition">
                                    <svg id="eyeOn" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg id="eyeOff" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <input id="vis_password_confirmation" type="text"
                                       autocomplete="off"
                                       style="-webkit-text-security:disc"
                                       class="w-full pl-12 pr-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-slate-900 placeholder-slate-400 text-sm"
                                       placeholder="Confirm password">
                            </div>
                        </div>

                        <!-- Terms -->
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="terms"
                                   class="w-4 h-4 rounded border-slate-300 text-purple-600 focus:ring-purple-500 focus:ring-offset-0 transition shrink-0">
                            <label for="terms" class="text-sm text-slate-600">I agree to the terms of service</label>
                        </div>

                        <!-- Submit -->
                        <button type="button" id="submitBtn"
                                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white py-3 px-4 rounded-xl font-semibold text-sm shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-0.5">
                            Create Account
                        </button>
                    </div>

                    <!-- Footer Links -->
                    <div class="mt-5 text-center">
                        <p class="text-sm text-slate-600">
                            Already have an account?
                            <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-700 font-semibold transition">Sign in</a>
                        </p>
                        <div class="mt-3">
                            <a href="{{ route('landingPage') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 transition group">
                                <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Back to Home
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Illustration -->
                <div class="hidden md:flex bg-gradient-to-br from-indigo-50 to-purple-50 p-10 items-center justify-center order-2">
                    <img src="{{ asset('assets/ilustrasi.png') }}" alt="Library Illustration" class="w-full max-w-sm object-contain">
                </div>

            </div>
        </div>
    </div>

<script>
(function () {
    // Visible inputs
    const visName     = document.getElementById('vis_name');
    const visEmail    = document.getElementById('vis_email');
    const visAddress  = document.getElementById('vis_address');
    const visProvince = document.getElementById('vis_province');
    const visCity     = document.getElementById('vis_city');
    const visPwd      = document.getElementById('vis_password');
    const visPwdConf  = document.getElementById('vis_password_confirmation');
    const terms       = document.getElementById('terms');

    // Hidden inputs (inside form)
    const realName     = document.getElementById('real_name');
    const realEmail    = document.getElementById('real_email');
    const realAddress  = document.getElementById('real_address');
    const realProvince = document.getElementById('real_province');
    const realCity     = document.getElementById('real_city');
    const realPwd      = document.getElementById('real_password');
    const realPwdConf  = document.getElementById('real_password_confirmation');

    const submitBtn = document.getElementById('submitBtn');
    const toggleBtn = document.getElementById('togglePwd');
    const eyeOn     = document.getElementById('eyeOn');
    const eyeOff    = document.getElementById('eyeOff');
    let   showPwd   = false;

    // Kosongkan saat load — cegah browser restore
    window.addEventListener('pageshow', function () {
        visName.value    = '';
        visEmail.value   = '';
        visAddress.value = '';
        visPwd.value     = '';
        visPwdConf.value = '';
    });

    // Toggle show/hide password
    toggleBtn.addEventListener('click', function () {
        showPwd = !showPwd;
        const sec = showPwd ? 'none' : 'disc';
        visPwd.style.webkitTextSecurity     = sec;
        visPwdConf.style.webkitTextSecurity = sec;
        eyeOn.classList.toggle('hidden', showPwd);
        eyeOff.classList.toggle('hidden', !showPwd);
        visPwd.focus();
    });

    // Submit: salin nilai visible ke hidden, lalu submit form
    submitBtn.addEventListener('click', function () {
        realName.value    = visName.value.trim();
        realEmail.value   = visEmail.value.trim();
        realAddress.value = visAddress.value.trim();
        realProvince.value= visProvince.options[visProvince.selectedIndex]?.dataset.name ?? '';
        realCity.value    = visCity.value;
        realPwd.value     = visPwd.value;
        realPwdConf.value = visPwdConf.value;
        document.getElementById('registerForm').submit();
    });

    // Enter key support
    [visName, visEmail, visPwd, visPwdConf].forEach(function (el) {
        el.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') submitBtn.click();
        });
    });

    // ── Wilayah ──────────────────────────────────────────────────────────────
    const oldProvince = @json(old('province', ''));
    const oldCity     = @json(old('city', ''));

    function toTitle(str) {
        return str.toLowerCase().replace(/\b\w/g, c => c.toUpperCase());
    }

    async function loadProvinces() {
        try {
            const res  = await fetch('{{ route('wilayah.provinces') }}');
            const data = await res.json();
            data.forEach(p => {
                const opt = new Option(toTitle(p.name), p.id);
                opt.dataset.name = toTitle(p.name);
                visProvince.add(opt);
            });
            if (oldProvince) {
                const match = [...visProvince.options].find(o => o.dataset.name === oldProvince);
                if (match) {
                    visProvince.value = match.value;
                    await loadCities(match.value, oldCity);
                }
            }
        } catch (e) { console.error(e); }
    }

    async function loadCities(provinceId, restore = '') {
        visCity.disabled = true;
        visCity.innerHTML = '<option value="">Memuat...</option>';
        try {
            const res  = await fetch(`{{ url('api/wilayah/regencies') }}/${provinceId}`);
            const data = await res.json();
            visCity.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
            data.forEach(r => {
                const name = toTitle(r.name);
                visCity.add(new Option(name, name));
            });
            visCity.disabled = false;
            if (restore) visCity.value = restore;
        } catch (e) {
            visCity.innerHTML = '<option value="">Gagal memuat</option>';
        }
    }

    visProvince.addEventListener('change', function () {
        if (this.value) {
            loadCities(this.value);
        } else {
            visCity.disabled = true;
            visCity.innerHTML = '<option value="">-- Pilih Provinsi dulu --</option>';
        }
    });

    loadProvinces();
    visName.focus();
})();
</script>

</body>
</html>
