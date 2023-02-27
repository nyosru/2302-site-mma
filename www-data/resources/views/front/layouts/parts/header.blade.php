<header>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-6 col-md-1 pr-0">
                <a href="{{route('front.home')}}/" class="logo">
                    <img src="{{ url('/images/logo.svg') }}" alt="">
                </a>
            </div>
            <div class="col-6 col-md-11 pl-0">
                <nav class="navbar navbar-expand-md">
                    <button class="navbar-toggler" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
                        <img src="{{ url('/images/menu.svg') }}" alt="">
                        <img src="{{ url('/images/close.svg') }}" alt="" style="display:none;">
                    </button>
                    <div class="collapse navbar-collapse" id="navbar">
                            <li class="nav-item">
                                <a class="nav-link" href="#">Example</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>
