@props(['book'])

<div class="group relative bg-[#1F1F1F] rounded-2xl p-3 hover:bg-[#2A2A2A] transition-colors duration-300 border border-gray-800 hover:border-gray-700 shadow-sm flex flex-col h-full">
    
    <div class="relative w-full h-[250px] rounded-xl overflow-hidden mb-3 shadow-lg bg-gray-800 flex-shrink-0">
        @if($book->cover_path)
            <img src="{{ asset('storage/' . $book->cover_path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
        @else
            <div class="w-full h-full flex flex-col items-center justify-center text-gray-500">
                <span class="text-3xl">📕</span>
                <span class="text-xs mt-2">No Cover</span>
            </div>
        @endif

        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-[2px]">
            <a href="{{ route('books.show', $book->id) }}" class="bg-indigo-600 text-white px-6 py-2 rounded-full font-bold text-sm shadow-xl hover:bg-indigo-500 transform scale-95 group-hover:scale-100 transition">
                Baca
            </a>
        </div>
    </div>

    <div class="mt-auto">
        <h3 class="text-white font-bold text-sm leading-tight mb-1 truncate" title="{{ $book->title }}">
            {{ $book->title }}
        </h3>
        <p class="text-gray-400 text-xs truncate uppercase tracking-wider mb-2">
            {{ $book->author }}
        </p>
        
        @if($book->category)
        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-medium bg-gray-800 text-gray-300 border border-gray-700">
            {{ $book->category->name }}
        </span>
        @endif
    </div>
</div>