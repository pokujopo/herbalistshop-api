    <div class="container-fluid">
        <div class="row bg-black  py-1 px-xl-5">
            <div class="col-lg-6 d-none d-lg-block">
                <div class="d-inline-flex align-items-center h-100">
                    <a class="text-body mr-3" href="">About</a>
                      @if (Route::has('login'))
                             @auth
                            <a href="{{ route('logout') }}" class="text-body mr-3" type="button">SignOut</a>

                            @else
                            <a href="{{ route('login') }}" class="text-body mr-3" type="button">SignIn</a>

                            @endauth

                        @endif    
                        
                    
                    <a class="text-body mr-3" href="">Help</a>
                    <a class="text-body mr-3" href="">FAQs</a>
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown">My Account</button>
                        <div class="dropdown-menu dropdown-menu-right">
                        @if (Route::has('login'))
                             @auth
                            <a href="{{ route('logout') }}" class="dropdown-item" type="button">SignOut</a>

                            @else
                            <a href="{{ route('login') }}" class="dropdown-item" type="button">SignIn</a>

                            @endauth

                        @endif    
                        

                        </div>
                    </div>
                    <div class="btn-group mx-2">
                        <!-- Currency (TSH active by default) -->
                        <div class="btn-group mr-2 hidden" role="group">
                            <button id="currencyBtn" hidden type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span id="currencyLabel">TSH</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="currencyBtn">
                                <button class="dropdown-item currency-option" data-currency="TSH">TSH</button>
                                <button class="dropdown-item currency-option" data-currency="USD">USD</button>
                                <button class="dropdown-item currency-option" data-currency="EUR">EUR</button>
                            </div>
                        </div>

                        <!-- Language -->
                        <div class="btn-group mr-2 hidden" role="group">
                            <button id="langBtn" hidden type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span id="langLabel">EN</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="langBtn">
                                <button class="dropdown-item lang-option" data-lang="EN">English</button>
                                <button class="dropdown-item lang-option" data-lang="SW">Swahili</button>
                            </div>
                        </div>

                        <!-- Dark mode toggle -->
                        <button id="darkModeToggle" type="button" class="btn btn-sm btn-light" title="Toggle dark mode" aria-pressed="false">
                            <i id="darkModeIcon" class="fas fa-moon"></i>
                        </button>

                        <style>
                        /* Make text readable in dark mode: force light text color, but allow exceptions if needed */
                        body.dark-mode * {
                          color: #e9ecef !important;
                        }

                        /* Keep brand/primary elements visible (adjust as needed) */
                        body.dark-mode .text-primary,
                        body.dark-mode .bg-primary,
                        body.dark-mode .btn-primary,
                        body.dark-mode .badge,
                        body.dark-mode .badge-primary {
                          color: #ffffff !important;
                        }

                        /* Inputs, placeholders and form controls */
                        body.dark-mode .form-control,
                        body.dark-mode input,
                        body.dark-mode textarea {
                          background: #1f1f1f !important;
                          color: #e9ecef !important;
                          border-color: #444 !important;
                        }
                        body.dark-mode ::placeholder { color: #9aa0a6 !important; }
                        </style>    <style>
                        /* Basic dark-mode styles — adjust colors to taste */
                        body.dark-mode { background-color: #121212; color: #e9ecef; }
                        body.dark-mode .bg-light { background-color: #1f1f1f !important; color: #e9ecef !important; }
                        body.dark-mode .bg-dark { background-color: #0b0b0b !important; color: #e9ecef !important; }
                        body.dark-mode .navbar, body.dark-mode .navbar .nav-link, body.dark-mode .text-dark { color: #e9ecef !important; }
                        body.dark-mode .btn-light { background-color: #2b2b2b; color: #e9ecef; border-color: #444; }
                        body.dark-mode .dropdown-menu { background: #222; color: #e9ecef; }
                        body.dark-mode .form-control { background: #1f1f1f; color: #e9ecef; border-color: #444; }
                        </style>    </div>

                    <script>
                    (function(){
                        const currencyLabel = document.getElementById('currencyLabel');
                        const langLabel = document.getElementById('langLabel');
                        const darkToggle = document.getElementById('darkModeToggle');
                        const darkIcon = document.getElementById('darkModeIcon');

                        // Restore saved preferences or defaults
                        const savedCurrency = localStorage.getItem('currency') || 'TSH';
                        const savedLang = localStorage.getItem('language') || 'EN';
                        const savedDark = localStorage.getItem('darkMode') === 'true';

                        currencyLabel.textContent = savedCurrency;
                        langLabel.textContent = savedLang;
                        applyDarkMode(savedDark);

                        // Currency selection
                        document.querySelectorAll('.currency-option').forEach(btn =>
                            btn.addEventListener('click', e => {
                                const c = e.currentTarget.getAttribute('data-currency');
                                currencyLabel.textContent = c;
                                localStorage.setItem('currency', c);
                                // If you need server-side changes, trigger a request or reload:
                                // location.reload();
                            })
                        );

                        // Language selection
                        document.querySelectorAll('.lang-option').forEach(btn =>
                            btn.addEventListener('click', e => {
                                const l = e.currentTarget.getAttribute('data-lang');
                                langLabel.textContent = l;
                                localStorage.setItem('language', l);
                                // If you use server-side localization, trigger a request or reload:
                                // location.reload();
                            })
                        );

                        // Dark mode toggle
                        darkToggle.addEventListener('click', () => {
                            const isDark = document.body.classList.toggle('dark-mode');
                            localStorage.setItem('darkMode', isDark);
                            applyDarkMode(isDark);
                        });

                        function applyDarkMode(on) {
                            if (on) {
                                document.body.classList.add('dark-mode');
                                darkIcon.classList.remove('fa-moon');
                                darkIcon.classList.add('fa-sun');
                            } else {
                                document.body.classList.remove('dark-mode');
                                darkIcon.classList.remove('fa-sun');
                                darkIcon.classList.add('fa-moon');
                            }
                        }
                    })();
                    </script>    <div class="btn-group">
                      
                    </div>
                </div>
               
            </div>
        </div>
        <div class="row align-items-center bg-light py-3 px-xl-5 d-none d-lg-flex">
            <div class="col-lg-4">
                <a href="" class="text-decoration-none">
                    <span class="h1 text-uppercase text-primary bg-dark px-2">Adam</span>
                    <span class="h1 text-uppercase text-dark bg-primary px-2 ml-n1">Herbal</span>
                </a>
            </div>
            <div class="col-lg-4 col-6 text-left" style="position:relative;">
                <div class="input-group">
                    <input
                        id="search-input"
                        type="text"
                        class="form-control"
                        autocomplete="off"
                        placeholder="Search for products"
                        aria-autocomplete="list"
                        aria-controls="searchResults"
                        aria-expanded="false"
                    >
                    <div class="input-group-append">
                        <span class="input-group-text bg-transparent text-primary">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                </div>

                <!-- Floating results (styled like Google autocomplete) -->
                <div
                    id="searchResults"
                    class="list-group p-4 position-absolute w-100 mt-1 d-none bg-white"
                    role="listbox"
                    style="top:100%; left:0; z-index:1050; max-height:320px; overflow:auto; box-shadow:0 6px 18px rgba(33,0,0,.15); border-radius:.25rem; background-color:#fff;"
                >
                    <!-- JS will inject <a class="list-group-item list-group-item-action"> items here -->
                </div>

                <style>
                /* Keep white background in light mode and provide dark-mode override */
                body.dark-mode #searchResults {
                  background: #222 !important;
                  color: #e9ecef !important;
                }
                body.dark-mode #searchResults .list-group-item {
                  background: transparent !important;
                  color: #e9ecef !important;
                  border-color: #444 !important;
                }
                </style>    <script>
                (function(){
                    const input = document.getElementById('search-input');
                    const results = document.getElementById('searchResults');
                    if (!input || !results) return;

                    // hide when clicking outside
                    document.addEventListener('click', (e) => {
                        if (!input.contains(e.target) && !results.contains(e.target)) {
                            results.classList.add('d-none');
                            input.setAttribute('aria-expanded', 'false');
                        }
                    });

                    // keyboard navigation (ArrowUp, ArrowDown, Enter, Escape)
                    input.addEventListener('keydown', (e) => {
                        const items = Array.from(results.querySelectorAll('.list-group-item'));
                        const activeIndex = items.findIndex(i => i.classList.contains('active'));

                        if (e.key === 'ArrowDown') {
                            e.preventDefault();
                            const next = items[activeIndex + 1] || items[0];
                            items.forEach(i => i.classList.remove('active'));
                            if (next) next.classList.add('active');
                        } else if (e.key === 'ArrowUp') {
                            e.preventDefault();
                            const prev = items[activeIndex - 1] || items[items.length - 1];
                            items.forEach(i => i.classList.remove('active'));
                            if (prev) prev.classList.add('active');
                        } else if (e.key === 'Enter') {
                            const active = items[activeIndex];
                            if (active) {
                                // simulate click on the active suggestion
                                active.click();
                            }
                        } else if (e.key === 'Escape') {
                            results.classList.add('d-none');
                            input.setAttribute('aria-expanded', 'false');
                        }
                    });

                    // when results are populated by your fetch logic, ensure aria-expanded is updated there.
                    // Example helper to render results in the expected format:
                    window.__renderSearchResults = function(data){
                        if (!Array.isArray(data) || data.length === 0) {
                            results.innerHTML = '<div class="list-group-item text-muted">No results</div>';
                            results.classList.remove('d-none');
                            input.setAttribute('aria-expanded', 'true');
                            return;
                        }
                        results.innerHTML = data.map(p => {
                            // escape product text minimally
                            const text = String(p.product || p.name || '').replace(/[&<>"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
                            return `<a href="${(p.url||'#')}" class="list-group-item list-group-item-action" role="option">${text}</a>`;
                        }).join('');
                        results.classList.remove('d-none');
                        input.setAttribute('aria-expanded', 'true');
                    };
                })();
                </script>
            </div>    <div class="col-lg-4 col-6 text-right">
                <p class="m-0">Customer Service</p>
                <h5 class="m-0">+012 345 6789</h5>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid bg-dark mb-30">
        <div class="row px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a class="btn d-flex align-items-center justify-content-between bg-primary w-100" data-toggle="collapse" href="#navbar-vertical" style="height: 65px; padding: 0 30px;">
                    <h6 class="text-dark m-0"><i class="fa fa-bars mr-2"></i>Categories</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 bg-light" id="navbar-vertical" style="width: calc(100% - 30px); z-index: 999;">
                    <div class="navbar-nav w-100">
    
                        <a href="{{url ('shop_category', 'virutubisho')}}" class="nav-item nav-link">virutubisho</a>
                        <a href="{{url ('shop_category', 'nguvu')}}" class="nav-item nav-link">nguvu</a>
                       
                    </div>
                </nav>
            </div>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3 py-lg-0 px-0">
                    <a href="" class="text-decoration-none d-block d-lg-none">
                        <span class="h1 text-uppercase text-dark bg-light px-2">Adam</span>
                        <span class="h1 text-uppercase text-light bg-primary px-2 ml-n1">Herbal</span>
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">
                            <a href="{{ url('/')}}" class="nav-item nav-link ">Home</a>
                            <a href="{{url('shop')}}" class="nav-item nav-link">Shop</a>
                            
                            
                               
                                
                           
                            <a href="{{url('/contact')}}" class="nav-item nav-link">Contact</a>
                        </div>
                        <div class="navbar-nav ml-auto py-0 d-none d-lg-block">
                            
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>

<script>
(function(){
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('searchResults');
    console.log(searchInput);

    if (!searchInput || !searchResults) return;

    // Use 'input' so changes are detected immediately
    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.trim();
        console.log(query);
        if (query.length > 0){
            fetch(`/search?query=${encodeURIComponent(query)}`)
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    let result = '';
                    if (Array.isArray(data) && data.length) {
                        data.forEach(product => result += `<p>${product.product}</p>`);
                    } else {
                        result = '<p class="text-muted m-2">No results</p>';
                    }
                    searchResults.innerHTML = result;
                    searchResults.classList.remove('hidden', 'd-none');
                })
                .catch(err => {
                    console.error(err);
                    searchResults.innerHTML = '<p class="text-danger m-2">Error loading results</p>';
                });
        } else{
            searchResults.innerHTML = '';
            searchResults.classList.add('hidden', 'd-none');
        }
    });
})();
</script>