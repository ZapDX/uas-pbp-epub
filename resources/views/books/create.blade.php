<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Upload Buku Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Form Upload --}}
                    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        {{-- Judul --}}
                        <div>
                            <label for="title" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Judul Buku</label>
                            <input type="text" name="title" id="title" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        </div>

                        {{-- Penulis --}}
                        <div>
                            <label for="author" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Penulis</label>
                            <input type="text" name="author" id="author" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        </div>

                        {{-- Kategori (Dropdown dari Database) --}}
                        <div>
                            <label for="category_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Kategori / Prodi</label>
                            <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label for="description" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Deskripsi Singkat</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                        </div>

                        {{-- File ePub --}}
                        <div>
                            <label for="file" class="block font-medium text-sm text-gray-700 dark:text-gray-300">File ePub (.epub)</label>
                            <input type="file" name="file" id="file" accept=".epub" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                            <p class="mt-1 text-sm text-gray-500">Wajib format .epub (Max 10MB)</p>
                        </div>

                         {{-- Cover Image --}}
                         <div>
                            <label for="cover" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Cover Buku (Opsional)</label>
                            <input type="file" name="cover" id="cover" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Upload Buku
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>