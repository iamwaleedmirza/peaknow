<header class="nav-header">
    <nav class="navbar navbar-expand-lg nav-container navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                <div class="nav-logo logo-light"></div>
            </a>

            <div class="d-flex">
                <div class="theme-toggle">
                    <i id="theme-toggle" class="fas fa-sun change-theme btn-theme"></i>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-bars ham-menu"></i>
                </button>
            </div>

            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item text-center">
                        <a class="nav-link {{ Route::current()->getName() == 'about-us' ? 'active' : '' }}"
                           href="{{ route('about-us') }}">About Us</a>
                    </li>
                    <li class="nav-item text-center">
                        <a class="nav-link {{ Route::current()->getName() == 'plans' ? 'active' : '' }}"
                           href="{{ route('plans') }}">Plans</a>
                    </li>
                    <li class="nav-item text-center">
                        <a class="nav-link {{ Route::current()->getName() == 'faq' ? 'active' : '' }}"
                           href="{{ route('faq') }}">FAQ</a>
                    </li>
                    @if(!auth()->user())
                        <li class="nav-item text-center">
                            <a class="nav-link {{ Route::current()->getName() == 'login.user' ? 'active' : '' }}"
                               href="{{ route('login.user') }}">Log in</a>
                        </li>
                    @endif
                    {{--<li class="nav-item text-center">--}}
                    {{--<a class="nav-link {{ Route::current()->getName() == 'blogs' ? 'active' : '' }}"--}}
                    {{--    href="{{ route('blogs') }}">Blogs</a>--}}
                    {{--</li>--}}
                    <li class="nav-item text-center">
                        <a class="nav-link {{ Route::current()->getName() == 'contact-us' ? 'active' : '' }}"
                           href="{{ route('contact-us') }}">Contact Us</a>
                    </li>
                </ul>

                <div class="d-none d-lg-flex px-5">
                    @if(auth()->user())
                        <a href="{{ route('account-home') }}">
                            <button class="btn btn-peaks text-uppercase">Go to Dashboard</button>
                        </a>
                    @else
                        <a href="#plans">
                            <button class="btn btn-peaks text-uppercase">Get Started</button>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>
</header>
