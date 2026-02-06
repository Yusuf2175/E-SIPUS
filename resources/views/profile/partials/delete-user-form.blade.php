<section class="space-y-6">
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex">
            <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Peringatan!</h3>
                <div class="mt-2 text-sm text-red-700">
                    <p>Setelah akun Anda dihapus, semua data dan informasi akan dihapus secara permanen. Pastikan Anda telah mengunduh data penting sebelum menghapus akun.</p>
                </div>
            </div>
        </div>
    </div>

    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" 
            class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200">
        Hapus Akun
    </button>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ show: @js($errors->userDeletion->isNotEmpty()) }" 
         x-show="show" 
         x-on:open-modal.window="$event.detail == 'confirm-user-deletion' ? show = true : null"
         x-on:close.stop="show = false"
         x-on:keydown.escape.window="show = false"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
         style="display: none;">
        
        <div x-show="show" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="bg-white rounded-lg max-w-md w-full">
            
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                @csrf
                @method('delete')

                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <h2 class="ml-3 text-lg font-semibold text-gray-900">Konfirmasi Hapus Akun</h2>
                </div>

                <p class="text-sm text-gray-600 mb-6">
                    Apakah Anda yakin ingin menghapus akun? Semua data akan dihapus secara permanen dan tidak dapat dikembalikan. Masukkan password untuk konfirmasi.
                </p>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input id="password" name="password" type="password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent" 
                           placeholder="Masukkan password Anda">
                    @error('password', 'userDeletion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" x-on:click="show = false" 
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                        Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
