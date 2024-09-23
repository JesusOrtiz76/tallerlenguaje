<!-- Navigation-->
@php
    $classContainer = 'container';
    $buttonHtml = '';

    if (!in_array(request()->path(), ['login', 'register', 'password/reset', 'password/email', 'password/reset/*', 'email/verify'])) {
        $buttonHtml = '<button type="button" id="sidebarCollapse" class="btn btn-white">
                            <i class="fa fa-bars me-1"></i>
                            <span class="sr-only">Toggle Sidebar</span>
                        </button>';
        $classContainer = 'container-fluid';
    }
@endphp

<nav class="navbar navbar-expand-lg sticky-top border-bottom" id="mainNav">
    <div class="{{ $classContainer }}">

        {!! $buttonHtml !!}

        <a class="navbar-brand" href="{{ url('/') }}"></a>

        <button class="btn btn-white d-inline-block d-lg-none ml-auto"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarResponsive"
                aria-controls="navbarResponsive"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <i class="fa fa-ellipsis-v ms-1"></i>
            <span class="sr-only">Toggle Menu</span>
        </button>

        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">{{ __('Home') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">{{ __('Go to workshop') }}</a>
                </li>
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link  text-uppercase" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link  text-uppercase" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown"
                           class="nav-link dropdown-toggle  text-uppercase"
                           href="#" role="button"
                           data-bs-toggle="dropdown"
                           aria-haspopup="true"
                           aria-expanded="false"
                           v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form class="form"
                                  id="logout-form"
                                  action="{{ route('logout') }}"
                                  method="POST"
                                  class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
