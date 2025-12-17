<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $book->title }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/jszip@3.10.1/dist/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/epubjs@0.3.93/dist/epub.min.js"></script>

    <style>
        body { font-family: sans-serif; background-color: #121212; }
        
        #viewer { 
            height: 100vh;
            width: 100%;
            overflow-y: auto !important; 
            scroll-behavior: smooth;
            padding-top: 60px;
            padding-bottom: 80px;
        }

        /* Scrollbar Halus */
        ::-webkit-scrollbar { width: 6px; background: #121212; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 3px; }

        .ui-bar { transition: transform 0.3s ease-in-out; }
        .hide-top { transform: translateY(-100%); }
        .hide-bottom { transform: translateY(100%); }
    </style>
</head>
<body class="text-white h-screen overflow-hidden relative">

    <header id="top-bar" class="ui-bar fixed top-0 left-0 w-full h-16 bg-[#1F1F1F]/95 backdrop-blur border-b border-gray-800 flex items-center justify-between px-4 z-50 shadow-lg">
        
        @auth
            <a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-white flex items-center gap-2 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span class="hidden md:inline">Dashboard</span>
            </a>
        @else
            <a href="{{ route('welcome') }}" class="text-gray-300 hover:text-white flex items-center gap-2 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span class="hidden md:inline">Beranda</span>
            </a>
        @endauth
        
        <div class="text-center overflow-hidden px-4">
            <h1 class="font-bold text-sm truncate max-w-[200px] md:max-w-md text-white">{{ $book->title }}</h1>
            <p class="text-[10px] text-gray-400 truncate uppercase tracking-wider">{{ $book->author }}</p>
        </div>
        <div class="w-6"></div> 
    </header>

    <main class="w-full h-full bg-[#121212]">
        <div id="viewer" class="bg-[#121212]"></div>

        <div id="status-overlay" class="absolute inset-0 flex flex-col items-center justify-center bg-[#121212] z-40">
            <div id="spinner" class="text-center">
                <svg class="animate-spin h-10 w-10 text-indigo-500 mb-4 mx-auto" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-400 text-sm">Memuat buku...</p>
            </div>
            <div id="error-message" class="hidden text-center px-6">
                <p class="text-red-500 text-4xl mb-2">⚠️</p>
                <p id="error-text" class="text-gray-300 text-sm bg-gray-800 p-2 rounded">Error</p>
            </div>
        </div>
    </main>

    <footer id="bottom-bar" class="ui-bar fixed bottom-0 left-0 w-full h-16 bg-[#1F1F1F]/95 backdrop-blur border-t border-gray-800 flex items-center justify-between px-4 z-50 shadow-[0_-5px_15px_rgba(0,0,0,0.3)]">
        <button id="prev" class="p-2 rounded-full hover:bg-gray-700 text-gray-300 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>

        <div class="flex-1 mx-4">
            <select id="toc-select" class="w-full bg-gray-800 text-white text-xs md:text-sm border-none rounded-lg py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none truncate">
                <option value="" disabled selected>Memuat Daftar Isi...</option>
            </select>
        </div>

        <button id="next" class="p-2 rounded-full hover:bg-gray-700 text-gray-300 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
    </footer>

    <script>
        const topBar = document.getElementById('top-bar');
        const bottomBar = document.getElementById('bottom-bar');
        const tocSelect = document.getElementById('toc-select');
        let isUiVisible = true;

        var bookUrl = "{{ asset('storage/' . $book->file_path) }}";
        var book = ePub(bookUrl, { openAs: "epub" });
        
        // --- PERBAIKAN DI SINI ---
        var rendition = book.renderTo("viewer", {
            width: "100%",
            height: "100%", 
            flow: "scrolled", 
            manager: "continuous",
            allowScriptedContent: true // <--- INI KUNCI AGAR TIDAK DIBLOKIR BRAVE/EDGE
        });

        // CSS Injection (Gaya Visual)
        rendition.themes.default({
            "body": { 
                "background-color": "#121212 !important", 
                "color": "#e5e7eb !important", 
                "padding": "0 15px !important",
                "font-family": "sans-serif !important"
            },
            "p, span, h1, h2, h3, h4, h5, h6, li, div, blockquote": { 
                "color": "#e5e7eb !important",
                "background-color": "transparent !important"
            },
            "a": { "color": "#818cf8 !important", "text-decoration": "none !important" },
            "img": { 
                "max-width": "100% !important", 
                "height": "auto !important", 
                "margin": "10px auto", 
                "display": "block",
                "border-radius": "4px"
            }
        });

        var displayed = rendition.display();

        displayed.then(function(){
            document.getElementById('status-overlay').style.display = 'none';
        }).catch(err => {
            // Jika masih error, kemungkinan browser memblokir total
            showError("Browser memblokir script. Matikan Shield/Protection browser Anda untuk situs ini.");
        });

        // Load TOC
        book.loaded.navigation.then(function(toc){
            tocSelect.innerHTML = '<option value="" disabled selected>Pilih Bab / Chapter</option>';
            toc.forEach(function(chapter){
                let option = document.createElement("option");
                option.textContent = chapter.label.trim();
                option.value = chapter.href;
                tocSelect.appendChild(option);
            });
            tocSelect.addEventListener("change", function(){
                rendition.display(this.value);
            });
        });

        // UI Toggle
        rendition.on('click', function() { toggleUI(); });
        document.getElementById('viewer').addEventListener('click', (e) => {
            if(e.target.tagName !== 'A' && e.target.tagName !== 'SELECT' && e.target.tagName !== 'OPTION') toggleUI();
        });

        function toggleUI() {
            if (isUiVisible) {
                topBar.classList.add('hide-top');
                bottomBar.classList.add('hide-bottom');
            } else {
                topBar.classList.remove('hide-top');
                bottomBar.classList.remove('hide-bottom');
            }
            isUiVisible = !isUiVisible;
        }

        document.getElementById("next").addEventListener("click", () => rendition.next());
        document.getElementById("prev").addEventListener("click", () => rendition.prev());

        function showError(msg) {
            document.getElementById('spinner').style.display = 'none';
            document.getElementById('error-message').classList.remove('hidden');
            document.getElementById('error-text').innerText = msg;
            console.error(msg);
        }
    </script>
</body>
</html>