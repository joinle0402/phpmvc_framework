<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">Examination App</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse d-flex justify-content-end" id="navbarNav">
            @if (empty($role))
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ url('/register') }}">Đăng ký</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ url('/login') }}">Đăng nhập</a>
                    </li>
                </ul>
            @else
                <ul class="navbar-nav">
                    @if ($role === 'ADMINISTRATOR')
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ url('/admin') }}">Quản trị</a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ url('/logout') }}">Đăng Xuất</a>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</nav>
