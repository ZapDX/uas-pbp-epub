<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Buku') }}: {{ $book->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                
                <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 max-w-xl">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="title" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Judul Buku</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required>
                    </div>

                    <div>
                        <label for="author" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Penulis</label>
                        <input type="text" name="author" id="author" value="{{ old('author', $book->author) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required>
                    </div>

                    <div>
                        <label for="category_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Kategori</label>
                        <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $book->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="description" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Deskripsi</label>
                        <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">{{ old('description', $book->description) }}</textarea>
                    </div>

                    <div>
                        <label for="cover" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Ganti Cover (Opsional)</label>
                        <input type="file" name="cover" id="cover" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Biarkan kosong jika tidak ingin mengubah cover.</p>
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md shadow transition">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white underline text-sm">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>