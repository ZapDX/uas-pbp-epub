<!DOCTYPE html>
<html lang="id" class="dark"> <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Perpustakaan</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                },
            }
        }
    </script>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        #sidebar {
            transition: width 0.3s ease-in-out, transform 0.3s ease-in-out;
        }
        
        /* Transisi warna halus saat ganti mode */
        body, div, nav, aside, header, input, h2, h3, p, span, a, button {
            transition-property: background-color, border-color, color;
            transition-duration: 300ms;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-[#121212] text-gray-900 dark:text-white font-sans antialiased overflow-hidden transition-colors duration-300">

    <div class="flex h-screen w-full relative">

        <aside id="sidebar" class="w-64 bg-white dark:bg-[#1F1F1F] flex flex-col border-r border-gray-200 dark:border-gray-800 hidden md:flex h-full flex-shrink-0 z-50 overflow-hidden whitespace-nowrap shadow-lg md:shadow-none">
            
            <div class="p-8 flex flex-col items-center border-b border-gray-200 dark:border-gray-800 transition-opacity duration-200" id="userProfile">
                
                @if(Auth::user()->avatar)
                    <div class="w-16 h-16 rounded-full border-2 border-indigo-500 p-0.5 mb-3 shadow-lg overflow-hidden">
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="User Avatar" class="w-full h-full object-cover rounded-full">
                    </div>
                @else
                    <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-2xl font-bold text-white mb-3 shadow-lg">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                @endif

                <h3 class="font-bold text-lg text-gray-800 dark:text-white">{{ Auth::user()->name }}</h3>
                
                <a href="{{ route('profile.edit') }}" class="text-xs text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300 mt-1 flex items-center gap-1 transition">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    Edit Profile
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 {{ !request('category') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }} rounded-xl transition-all group">
                    <span class="mr-3">📚</span> All Books
                </a>

                <a href="{{ route('bookmarks.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('bookmarks.index') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }} rounded-xl transition-all group">
                    <span class="mr-3">⭐</span> Tersimpan
                </a>

                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Program Studi</p>
                </div>

                @foreach($categories as $category)
                    <a href="{{ route('dashboard', ['category' => $category->id]) }}" 
                       class="flex items-center px-4 py-3 rounded-xl transition-all {{ request('category') == $category->id ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                        <span class="mr-3">🎓</span> {{ $category->name }}
                    </a>
                @endforeach

                <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-800">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full flex items-center px-4 py-3 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all">
                            <span class="mr-3">🚪</span> Logout
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50 dark:bg-[#121212] relative w-full transition-all duration-300">
            
            <header class="h-20 flex items-center justify-between px-4 md:px-8 border-b border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-[#121212]/95 backdrop-blur z-20 flex-shrink-0">
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <button id="toggleSidebarBtn" class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white focus:outline-none bg-gray-100 dark:bg-[#1F1F1F] rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition shadow-sm">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="relative w-full md:w-96">
                        <form action="{{ route('dashboard') }}" method="GET">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search books..." 
                                   class="w-full bg-gray-100 dark:bg-[#1F1F1F] text-gray-800 dark:text-white border-none rounded-full py-2.5 pl-6 pr-4 focus:ring-2 focus:ring-indigo-500 text-sm focus:outline-none placeholder-gray-500 transition-colors">
                        </form>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button id="theme-toggle" class="p-2.5 rounded-full bg-gray-100 dark:bg-[#1F1F1F] text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition shadow-sm">
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    </button>

                    <a href="{{ route('books.create') }}" class="flex items-center gap-2 bg-indigo-600 text-white px-4 md:px-6 py-2.5 rounded-full font-bold text-sm hover:bg-indigo-700 transition transform hover:scale-105 shadow-lg whitespace-nowrap ml-2">
                        <span class="hidden md:inline">+ Add New Book</span>
                        <span class="md:hidden">+</span>
                    </a>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-4 md:p-8 no-scrollbar relative">
                
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Your Collection</h2>

                @if(session('success'))
                    <div id="flashMessage" class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-500/50 text-green-700 dark:text-green-400 p-4 rounded-xl flex justify-between items-center">
                        <span>{{ session('success') }}</span>
                        <button onclick="document.getElementById('flashMessage').style.display='none'" class="text-green-700 dark:text-green-400 hover:text-green-900 dark:hover:text-white">&times;</button>
                    </div>
                @endif

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 pb-20">
                    
                    @forelse($books as $book)
                        <div class="group relative bg-white dark:bg-[#1F1F1F] rounded-2xl p-3 hover:shadow-xl dark:hover:bg-[#2A2A2A] transition-all duration-300 border border-gray-200 dark:border-gray-800 hover:border-indigo-500 dark:hover:border-gray-700 shadow-sm flex flex-col">
                            
                            <div class="relative w-full h-[250px] rounded-xl overflow-hidden mb-3 shadow-lg bg-gray-100 dark:bg-gray-800 flex-shrink-0">
                                @if($book->cover_path)
                                    <img src="{{ asset('storage/' . $book->cover_path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <span class="text-3xl">📕</span>
                                        <span class="text-xs mt-2">No Cover</span>
                                    </div>
                                @endif

                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-[2px]">
                                    <a href="{{ route('books.show', $book->id) }}" class="bg-indigo-600 text-white px-6 py-2 rounded-full font-bold text-sm shadow-xl hover:bg-indigo-500 transform scale-95 group-hover:scale-100 transition">Read</a>
                                </div>
                            </div>

                            <div class="flex justify-between items-start mt-auto">
                                <div class="overflow-hidden pr-2">
                                    <h3 class="text-gray-800 dark:text-white font-bold text-sm leading-tight mb-1 truncate" title="{{ $book->title }}">
                                        {{ $book->title }}
                                    </h3>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs truncate uppercase tracking-wider">
                                        {{ $book->author }}
                                    </p>
                                </div>
                                
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <a href="{{ route('books.edit', $book->id) }}" class="text-gray-400 hover:text-yellow-500 p-1 transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>

                                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus buku ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 p-1 transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>

                                    <form action="{{ route('books.bookmark', $book->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-1 transition {{ Auth::user()->bookmarks->contains($book->id) ? 'text-yellow-500 hover:text-yellow-600' : 'text-gray-400 hover:text-gray-600' }}" title="Bookmark">
                                            @if(Auth::user()->bookmarks->contains($book->id))
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="col-span-full py-20 text-center text-gray-500">
                            <p>Belum ada buku. Silakan upload buku pertama Anda.</p>
                        </div>
                    @endforelse

                </div>
            </div>
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebarBtn');

        // Sidebar Toggle
        toggleBtn.addEventListener('click', () => {
            const isMobile = window.innerWidth < 768;
            if (isMobile) {
                sidebar.classList.toggle('hidden');
                if (!sidebar.classList.contains('hidden')) {
                    sidebar.classList.remove('hidden');
                    sidebar.classList.add('fixed', 'inset-y-0', 'left-0', 'z-50', 'w-64', 'shadow-2xl');
                } else {
                    sidebar.classList.remove('fixed', 'inset-y-0', 'left-0', 'z-50', 'w-64', 'shadow-2xl');
                    sidebar.classList.add('hidden');
                }
            } else {
                if (sidebar.classList.contains('w-64')) {
                    sidebar.classList.remove('w-64', 'p-0');
                    sidebar.classList.add('w-0', 'border-none'); 
                } else {
                    sidebar.classList.add('w-64');
                    sidebar.classList.remove('w-0', 'border-none');
                }
            }
        });

        document.addEventListener('click', (e) => {
            const isMobile = window.innerWidth < 768;
            if (isMobile && !sidebar.contains(e.target) && !toggleBtn.contains(e.target) && !sidebar.classList.contains('hidden')) {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('fixed', 'inset-y-0', 'left-0', 'z-50', 'w-64', 'shadow-2xl');
            }
        });

        // === DARK MODE LOGIC ===
        const themeToggleBtn = document.getElementById('theme-toggle');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');
        const html = document.documentElement;

        function updateIcons() {
            if (html.classList.contains('dark')) {
                lightIcon.classList.remove('hidden');
                darkIcon.classList.add('hidden');
            } else {
                lightIcon.classList.add('hidden');
                darkIcon.classList.remove('hidden');
            }
        }

        // Cek LocalStorage saat load
        if (localStorage.getItem('color-theme') === 'dark' || 
           (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }
        updateIcons();

        // Event Switcher
        themeToggleBtn.addEventListener('click', () => {
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
            updateIcons();
        });
    </script>
</body>
</html>