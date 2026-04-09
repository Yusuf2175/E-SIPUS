@extends('layouts.dashboard')

@section('page-title', 'Update Book Record')

@section('content')

    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('books.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Bibliographic Record Maintenance</h1>
                <p class="text-slate-600">Update and maintain accurate catalog information</p>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl shadow-sm p-8 max-w-4xl">
        <form method="POST" action="{{ route('books.update', $book) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Title --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Book Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $book->title) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black @error('title') border-red-500 @enderror"
                           placeholder="Enter book title">
                    @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Author --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Author <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="author" value="{{ old('author', $book->author) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black @error('author') border-red-500 @enderror"
                           placeholder="Enter author name">
                    @error('author')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- ISBN --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        ISBN <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black @error('isbn') border-red-500 @enderror"
                           placeholder="e.g., 978-0-7432-7356-5">
                    @error('isbn')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Category --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select name="category" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black @error('category') border-red-500 @enderror">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->name }}" {{ old('category', $book->category) == $category->name ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Publisher --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Publisher</label>
                    <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black"
                           placeholder="Enter publisher name">
                    @error('publisher')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Region / Wilayah --}}
                @if($lockedRegion)
                    {{-- Petugas: region dikunci --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Wilayah Buku</label>
                        <div class="flex items-center gap-3 px-4 py-3 bg-purple-50 border border-purple-200 rounded-lg">
                            <svg class="w-5 h-5 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-purple-800">
                                    {{ $lockedCity ? $lockedCity . ', ' : '' }}{{ $lockedRegion }}
                                </p>
                                <p class="text-xs text-purple-600">Wilayah otomatis sesuai lokasi akun Anda</p>
                            </div>
                            <svg class="w-4 h-4 text-purple-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="hidden" name="region" value="{{ $lockedRegion }}">
                        <input type="hidden" name="city" value="{{ $lockedCity }}">
                    </div>
                @else
                    {{-- Admin: bebas ubah region --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                        <select id="province_select"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black">
                            <option value="">-- Pilih Provinsi --</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten / Kota</label>
                        <select id="city_select" name="city" disabled
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black disabled:bg-gray-100 disabled:cursor-not-allowed @error('city') border-red-500 @enderror">
                            <option value="">-- Pilih Provinsi dulu --</option>
                        </select>
                        <input type="hidden" name="region" id="region_hidden" value="{{ old('region', $book->region) }}">
                        @error('city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                @endif

                {{-- Published Year --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Published Year</label>
                    <input type="number" name="published_year" value="{{ old('published_year', $book->published_year) }}"
                           min="1000" max="{{ date('Y') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black"
                           placeholder="e.g., 2024">
                    @error('published_year')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Total Copies --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Total Copies <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="total_copies" value="{{ old('total_copies', $book->total_copies) }}"
                           required min="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black @error('total_copies') border-red-500 @enderror"
                           placeholder="Enter number of copies">
                    <p class="mt-1 text-xs text-gray-500">Available copies: {{ $book->available_copies }}</p>
                    @error('total_copies')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Cover Image --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cover Image</label>
                    <div class="flex items-start gap-4">
                        @if($book->cover_image)
                            <div class="flex-shrink-0">
                                <p class="text-xs text-gray-500 mb-2">Current Image:</p>
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}"
                                     class="h-32 w-24 object-cover rounded-lg border-2 border-gray-300">
                            </div>
                        @endif
                        <div class="flex-1">
                            <label class="flex flex-col items-center px-4 py-6 bg-white border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <span class="mt-2 text-sm text-gray-500">Click to upload new cover image</span>
                                <span class="mt-1 text-xs text-gray-400">PNG, JPG, GIF up to 2MB</span>
                                <input type="file" name="cover_image" accept="image/*" class="hidden" onchange="previewImage(event)">
                            </label>
                            <div id="imagePreview" class="hidden mt-4">
                                <p class="text-xs text-gray-500 mb-2">New Image Preview:</p>
                                <img id="preview" class="h-32 w-24 object-cover rounded-lg border-2 border-purple-300" alt="Preview">
                            </div>
                        </div>
                    </div>
                    @error('cover_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="5"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black"
                              placeholder="Enter book description or synopsis">{{ old('description', $book->description) }}</textarea>
                    @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('books.index') }}"
                   class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Book
                </button>
            </div>
        </form>
    </div>

    <x-book-alert />

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        // Wilayah cascading — hanya untuk admin
        @if(!$lockedRegion)
        function toTitleCase(str) {
            return str.toLowerCase().replace(/\b\w/g, c => c.toUpperCase());
        }

        const provinceSelect = document.getElementById('province_select');
        const citySelect     = document.getElementById('city_select');
        const regionHidden   = document.getElementById('region_hidden');

        const savedProvince = @json(old('region', $book->region ?? ''));
        const savedCity     = @json(old('city', $book->city ?? ''));

        async function loadProvinces() {
            try {
                const res  = await fetch('{{ route('wilayah.provinces') }}');
                const data = await res.json();
                data.forEach(p => {
                    const opt = new Option(toTitleCase(p.name), p.id);
                    opt.dataset.name = toTitleCase(p.name);
                    provinceSelect.add(opt);
                });
                if (savedProvince) {
                    const match = [...provinceSelect.options].find(o => o.dataset.name === savedProvince);
                    if (match) {
                        provinceSelect.value = match.value;
                        await loadRegencies(match.value, savedCity);
                    }
                }
            } catch (e) {
                console.error('Gagal memuat provinsi', e);
            }
        }

        async function loadRegencies(provinceId, restoreCity = '') {
            citySelect.disabled = true;
            citySelect.innerHTML = '<option value="">Memuat...</option>';
            try {
                const res  = await fetch(`{{ url('api/wilayah/regencies') }}/${provinceId}`);
                const data = await res.json();
                citySelect.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
                data.forEach(r => {
                    const opt = new Option(toTitleCase(r.name), toTitleCase(r.name));
                    citySelect.add(opt);
                });
                citySelect.disabled = false;
                if (restoreCity) citySelect.value = restoreCity;
                updateRegionHidden();
            } catch (e) {
                citySelect.innerHTML = '<option value="">Gagal memuat data</option>';
            }
        }

        function updateRegionHidden() {
            const provOpt = provinceSelect.options[provinceSelect.selectedIndex];
            regionHidden.value = provOpt && provOpt.dataset.name ? provOpt.dataset.name : '';
        }

        provinceSelect.addEventListener('change', function () {
            if (this.value) {
                loadRegencies(this.value);
            } else {
                citySelect.disabled = true;
                citySelect.innerHTML = '<option value="">-- Pilih Provinsi dulu --</option>';
                regionHidden.value = '';
            }
        });

        citySelect.addEventListener('change', updateRegionHidden);

        loadProvinces();
        @endif
    </script>

@endsection
