<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book - E-SIPUS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50">
    <div class="flex min-h-screen">
        @if(auth()->user()->isAdmin())
            <x-sidebar-admin />
        @else
            <x-sidebar-petugas />
        @endif

        <div class="flex-1 ml-64">
            <div class="p-8">
                <!-- Header Section -->
                <div class="mb-8">
                    <div class="flex items-center gap-4 mb-4">
                        <a href="{{ route('books.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-slate-800">Edit Book</h1>
                            <p class="text-slate-600">Update book information</p>
                        </div>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="bg-white rounded-2xl shadow-sm p-8 max-w-4xl">
                    <form method="POST" action="{{ route('books.update', $book) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Title -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Book Title <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="title" 
                                    value="{{ old('title', $book->title) }}"
                                    required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black @error('title') border-red-500 @enderror"
                                    placeholder="Enter book title">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Author -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Author <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="author" 
                                    value="{{ old('author', $book->author) }}"
                                    required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black @error('author') border-red-500 @enderror"
                                    placeholder="Enter author name">
                                @error('author')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- ISBN -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    ISBN <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="isbn" 
                                    value="{{ old('isbn', $book->isbn) }}"
                                    required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black @error('isbn') border-red-500 @enderror"
                                    placeholder="e.g., 978-0-7432-7356-5">
                                @error('isbn')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    name="category" 
                                    required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black @error('category') border-red-500 @enderror">
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->name }}" {{ old('category', $book->category) == $category->name ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Publisher -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Publisher
                                </label>
                                <input 
                                    type="text" 
                                    name="publisher" 
                                    value="{{ old('publisher', $book->publisher) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black @error('publisher') border-red-500 @enderror"
                                    placeholder="Enter publisher name">
                                @error('publisher')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Published Year -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Published Year
                                </label>
                                <input 
                                    type="number" 
                                    name="published_year" 
                                    value="{{ old('published_year', $book->published_year) }}"
                                    min="1000" 
                                    max="{{ date('Y') }}" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black @error('published_year') border-red-500 @enderror"
                                    placeholder="e.g., 2024">
                                @error('published_year')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Total Copies -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Total Copies <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    name="total_copies" 
                                    value="{{ old('total_copies', $book->total_copies) }}"
                                    required 
                                    min="1" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black @error('total_copies') border-red-500 @enderror"
                                    placeholder="Enter number of copies">
                                @error('total_copies')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Available copies: {{ $book->available_copies }}</p>
                            </div>

                            <!-- Cover Image -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Cover Image
                                </label>
                                <div class="flex items-start gap-4">
                                    <!-- Current Image -->
                                    @if($book->cover_image)
                                        <div class="flex-shrink-0">
                                            <p class="text-xs text-gray-500 mb-2">Current Image:</p>
                                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="h-32 w-24 object-cover rounded-lg border-2 border-gray-300">
                                        </div>
                                    @endif
                                    
                                    <!-- Upload New Image -->
                                    <div class="flex-1">
                                        <label class="flex flex-col items-center px-4 py-6 bg-white border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            <span class="mt-2 text-sm text-gray-500">Click to upload new cover image</span>
                                            <span class="mt-1 text-xs text-gray-400">PNG, JPG, GIF up to 2MB</span>
                                            <input 
                                                type="file" 
                                                name="cover_image" 
                                                accept="image/*" 
                                                class="hidden"
                                                onchange="previewImage(event)">
                                        </label>
                                        <div id="imagePreview" class="hidden mt-4">
                                            <p class="text-xs text-gray-500 mb-2">New Image Preview:</p>
                                            <img id="preview" class="h-32 w-24 object-cover rounded-lg border-2 border-purple-300" alt="Preview">
                                        </div>
                                    </div>
                                </div>
                                @error('cover_image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea 
                                    name="description" 
                                    rows="5" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black @error('description') border-red-500 @enderror"
                                    placeholder="Enter book description or synopsis">{{ old('description', $book->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('books.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Update Book
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
