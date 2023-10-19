<div class="navigation">
    <div class="container">
        <div class="phone-devices">
            <a class="navbar-brand-narrow" href="{{ route('home') }}">
                <img src="{{ asset('logo/Logo-white.svg') }}"
                    alt="Website Logo">
            </a>
        </div>
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="dev-cont-logo">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('logo/Logo-white.svg') }}" alt="Website Logo">
                </a>
            </div>
            <div class="dev-cont">
                <img id="theme-toggle-mb" src="{{ asset('assets/sun.svg') }}" class="modes-btn" alt="dark light mode icons">
                @if (auth()->user())
                    <a href="{{ route('account-home') }}" class="dss-btn nav-btn navbar-btn">Go to Dashboard</a>
                @else
                    <a href="{{ route('plans') }}" class="dss-btn nav-btn navbar-btn">Choose Your Plan</a>
                @endif
            </div>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about-us') }}">About Us</a>
                    </li>
                    @if(!auth()->user())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('plans') }}">Plans</a>
                    </li>
                    @elseif (auth()->user() && !auth()->user()->getActiveOrder())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('plans') }}">Plans</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact-us') }}">Contact Us</a>
                    </li>
                    @if (!auth()->user())
                        <li class="nav-item ">
                            <a class="nav-link {{ Route::current()->getName() == 'login.user' ? 'active' : '' }}"
                                href="{{ route('login.user') }}">Log in</a>
                        </li>
                    @endif
                </ul>
                <div class="modes-cyp">
                    <img id="theme-toggle" src="{{ asset('assets/sun.svg') }}" class="modes-btn for-dt" alt="dark light mode icons">
                    @if (auth()->user())
                        <a href="{{ route('account-home') }}" class="dss-btn nav-btn for-dt navbar-btn">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('plans') }}" class="dss-btn nav-btn for-dt navbar-btn">Choose Your Plan</a>
                    @endif

                </div>
            </div>
        </nav>
    </div>
</div>
