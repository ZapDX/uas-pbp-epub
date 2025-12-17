<!DOCTYPE html>
<html lang="id" class="light"> <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyPerpusKu - Cari & Baca Buku</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class', 
            theme: {
                fontFamily: { sans: ['Inter', 'sans-serif'] },
                extend: {
                    colors: { background: '#0f172a', surface: '#1e293b' },
                    animation: { 'bounce-slow': 'bounce 2s infinite' }
                }
            }
        }
    </script>
    <style>
        /* Sembunyikan Scrollbar */
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
        html { scroll-behavior: smooth; }

        /* === VARIABLE TRACKING MOUSE === */
        :root { --mouse-x: 50%; --mouse-y: 50%; }
        
        /* === BACKGROUND CONFIG (PERBAIKAN DISINI) === */
        /* Default Light Mode */
        body { 
            background-color: #f8fafc; 
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Dark Mode: Selector harus 'html.dark body' */
        html.dark body { 
            background-color: #0f172a; 
            position: relative; 
        }
        
        /* Layer Glow (Hanya muncul jika html punya class .dark) */
        html.dark body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100vh;
            z-index: -1; pointer-events: none;
            background: radial-gradient(
                800px circle at var(--mouse-x) var(--mouse-y), 
                rgba(99, 102, 241, 0.15), 
                transparent 50%
            );
            filter: blur(80px); opacity: 1;
        }
    </style>
</head>

<body class="text-gray-900 dark:text-gray-100 min-h-screen flex flex-col font-sans selection:bg-indigo-500 selection:text-white overflow-x-hidden">

    <nav class="absolute top-0 left-0 w-full p-6 flex justify-between items-center z-50">
        <div class="flex items-center gap-2">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <span class="text-2xl">📚</span>
            </div>
            <a href="/" class="font-bold text-xl tracking-tight drop-shadow-md">
                My<span class="text-indigo-600 dark:text-indigo-400">PerpusKu</span>
            </a>
        </div>

        <div class="flex items-center gap-3 md:gap-4">
            
            <button id="theme-toggle" class="p-2.5 rounded-full bg-white dark:bg-white/10 border border-gray-200 dark:border-white/10 text-gray-500 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/20 transition focus:outline-none shadow-sm backdrop-blur-md">
                <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
            </button>

            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-white transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hidden md:block font-bold text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-white transition">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-indigo-600 text-white dark:bg-white/10 dark:text-white dark:border dark:border-white/20 font-bold rounded-full hover:bg-indigo-700 dark:hover:bg-white/20 transition shadow-lg backdrop-blur-md">
                            Daftar
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <header class="h-screen w-full flex flex-col items-center justify-center text-center relative px-4">
        <div>
        <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight leading-tight drop-shadow-sm text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400">
            MyPerpusKu <br>
        </h1>
        <p class="bg-clip-text bg-gradient-to-r text-gray-900 dark:text-white transition-colors duration-300 text-sm mt-0 mb-6">Perpustakaan digital, baca sepuasnya tanpa harus login.</p>
        </div>
        
        <div class="w-full max-w-2xl relative z-20">
            <form action="{{ route('welcome') }}" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full py-5 pl-14 pr-6 rounded-full text-lg outline-none transition-all duration-300 shadow-xl
                              bg-white border border-gray-200 text-gray-900 placeholder-gray-500
                              dark:bg-white/5 dark:backdrop-blur-xl dark:border-white/10 dark:text-white dark:placeholder-gray-400
                              focus:ring-4 focus:ring-indigo-500/20 dark:focus:ring-indigo-500/40 focus:border-indigo-500" 
                       placeholder="Cari judul buku, skripsi, atau nama penulis...">
                
                <div class="absolute left-6 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </form>
        </div>

        <div class="absolute bottom-10 animate-bounce-slow cursor-pointer opacity-60 hover:opacity-100 transition z-20" onclick="document.getElementById('content-area').scrollIntoView()">
            <span class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2 block">Mulai Jelajah</span>
            <svg class="w-6 h-6 mx-auto text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
        </div>
    </header>

    <main id="content-area" class="flex-1 w-full relative z-10 bg-white dark:bg-[#0f172a]/80 dark:backdrop-blur-lg rounded-t-[2.5rem] shadow-[0_-10px_40px_rgba(0,0,0,0.05)] dark:shadow-none border-t border-gray-100 dark:border-white/5 pt-12 pb-20 transition-colors duration-300">
        
        <div class="max-w-[95%] mx-auto space-y-16">
            
            @if(request('search'))
                <section id="search-results">
                    <div class="flex items-center justify-between mb-6 border-b border-gray-200 dark:border-white/10 pb-4">
                        <h2 class="text-2xl font-bold flex items-center gap-2">
                            🔍 Hasil Pencarian: <span class="text-indigo-600 dark:text-indigo-400">"{{ request('search') }}"</span>
                        </h2>
                        <a href="/" class="text-sm text-red-500 hover:underline">Reset</a>
                    </div>

                    @if(isset($searchResults) && $searchResults->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                            @foreach($searchResults as $book)
                                <div class="h-full">
                                    <x-book-card :book="$book" />
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 dark:bg-white/5 rounded-2xl p-20 text-center border border-dashed border-gray-300 dark:border-white/10">
                            <p class="text-gray-500 dark:text-gray-400 text-2xl">Buku tidak ditemukan.</p>
                        </div>
                    @endif
                </section>
            @endif

            <section id="recommendations" class="relative">
                <div class="flex items-center justify-between mb-6 px-1">
                    <div>
                        <h2 class="text-2xl font-bold flex items-center gap-2">
                            ✨ Rekomendasi Terkini
                        </h2>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Pilihan acak untuk menemani waktu luangmu.</p>
                    </div>
                    
                    <div class="flex gap-2">
                        <button id="prevBtn" class="bg-white dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 p-2 rounded-full transition shadow border border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <button id="nextBtn" class="bg-white dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 p-2 rounded-full transition shadow border border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-2xl py-6">
                    <div id="sliderTrack" class="flex transition-transform duration-700 ease-out gap-5">
                        @forelse($recommendations as $book)
                            <div class="w-[85%] md:w-[40%] lg:w-[19%] flex-shrink-0">
                                 <x-book-card :book="$book" />
                            </div>
                        @empty
                            <div class="w-full py-10 text-center text-gray-500">Belum ada data.</div>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer class="py-8 text-center text-sm text-gray-500 dark:text-gray-600 bg-gray-50 dark:bg-[#0b1120] border-t border-gray-200 dark:border-white/5 relative z-20">
        &copy; {{ date('Y') }} E-Lib UIN Jakarta.
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            // 1. DARK MODE LOGIC (DIPERBAIKI)
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
            const themeToggleBtn = document.getElementById('theme-toggle');
            const html = document.documentElement;

            function applyTheme(theme) {
                if (theme === 'dark') {
                    html.classList.add('dark');
                    themeToggleLightIcon.classList.remove('hidden');
                    themeToggleDarkIcon.classList.add('hidden');
                } else {
                    html.classList.remove('dark');
                    themeToggleLightIcon.classList.add('hidden');
                    themeToggleDarkIcon.classList.remove('hidden');
                }
            }

            // Cek preferensi awal
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                applyTheme('dark');
            } else {
                applyTheme('light');
            }

            themeToggleBtn.addEventListener('click', function() {
                if (html.classList.contains('dark')) {
                    applyTheme('light');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    applyTheme('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            });

            // 2. MOUSE TRACKING (Hanya update variable, CSS yg kerjakan sisanya)
            document.addEventListener('mousemove', (e) => {
                document.body.style.setProperty('--mouse-x', e.clientX + 'px');
                document.body.style.setProperty('--mouse-y', e.clientY + 'px');
            });

            // 3. AUTO SCROLL SEARCH
            const urlParams = new URLSearchParams(window.location.search);
            if(urlParams.has('search')) {
                const resultsSection = document.getElementById('search-results');
                if(resultsSection) {
                    setTimeout(() => resultsSection.scrollIntoView({ behavior: 'smooth', block: 'start' }), 500);
                }
            }

            // 4. SLIDER LOGIC
            const track = document.getElementById('sliderTrack');
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            if(!track || track.children.length === 0) return;

            const items = track.children;
            const totalItems = items.length;
            let currentIndex = 0;

            function getItemsPerView() {
                if(window.innerWidth >= 1024) return 5;
                if(window.innerWidth >= 768) return 2;
                return 1;
            }

            function updateSlider() {
                const itemsPerView = getItemsPerView();
                const itemWidth = items[0].getBoundingClientRect().width;
                const gap = 20; 
                const translateX = currentIndex * (itemWidth + gap);
                track.style.transform = `translateX(-${translateX}px)`;
            }

            function nextSlide() {
                const itemsPerView = getItemsPerView();
                if (currentIndex >= totalItems - itemsPerView) currentIndex = 0;
                else currentIndex++;
                updateSlider();
            }

            function prevSlide() {
                const itemsPerView = getItemsPerView();
                if (currentIndex <= 0) currentIndex = totalItems - itemsPerView < 0 ? 0 : totalItems - itemsPerView;
                else currentIndex--;
                updateSlider();
            }

            nextBtn.addEventListener('click', () => { nextSlide(); resetTimer(); });
            prevBtn.addEventListener('click', () => { prevSlide(); resetTimer(); });

            let slideTimer = setInterval(nextSlide, 4000);
            function resetTimer() { clearInterval(slideTimer); slideTimer = setInterval(nextSlide, 4000); }
            window.addEventListener('resize', updateSlider);
        });
    </script>
</body>
</html>

@props(['book'])
<?php
if(!function_exists('renderBookCard')) {
    function renderBookCard($book) {
        ?>
        <div class="group relative flex flex-col h-full
                    bg-white dark:bg-white/5 dark:backdrop-blur-md 
                    rounded-2xl p-3 
                    border border-gray-200 dark:border-white/10
                    transition-all duration-300 ease-out
                    hover:-translate-y-2 hover:shadow-xl hover:shadow-indigo-500/10 dark:hover:shadow-indigo-500/20
                    dark:hover:border-indigo-500/50 hover:border-indigo-400/50">

            <div class="relative w-full h-[250px] rounded-xl overflow-hidden mb-3 shadow-md bg-gray-100 dark:bg-gray-800 flex-shrink-0">
                @if($book->cover_path)
                    <img src="{{ asset('storage/' . $book->cover_path) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 dark:text-gray-600">
                        <span class="text-3xl grayscale group-hover:grayscale-0 transition">📕</span>
                        <span class="text-xs mt-2">No Cover</span>
                    </div>
                @endif
                
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-[1px]">
                    <a href="{{ route('books.show', $book->id) }}" class="bg-indigo-600 text-white px-6 py-2 rounded-full font-bold text-sm shadow-xl hover:bg-indigo-500 transform scale-95 group-hover:scale-100 transition ring-2 ring-white/20">
                        Baca
                    </a>
                </div>
            </div>

            <div class="mt-auto">
                <h3 class="font-bold text-sm leading-tight mb-1 truncate transition-colors text-gray-800 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-white" title="{{ $book->title }}">
                    {{ $book->title }}
                </h3>
                <p class="text-xs truncate uppercase tracking-wider mb-3 transition-colors text-gray-500 dark:text-gray-400">
                    {{ $book->author }}
                </p>
                
                @if($book->category)
                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-medium transition-colors
                             bg-gray-100 text-gray-600 border border-gray-200
                             dark:bg-white/10 dark:text-gray-300 dark:border-white/5
                             group-hover:bg-indigo-50 group-hover:text-indigo-600 group-hover:border-indigo-200
                             dark:group-hover:bg-indigo-900/30 dark:group-hover:text-indigo-300 dark:group-hover:border-indigo-500/30">
                    {{ $book->category->name }}
                </span>
                @endif
            </div>
        </div>
        <?php
    }
}
?>