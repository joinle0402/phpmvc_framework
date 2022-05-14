<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <title>Hello, world!</title>
</head>

<body>
    @include('includes.sidebar')

    @include('includes.sidebar')

    @yield('content')

    <script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">
    <script src="{{ asset('/js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('/js/ckfinder/ckfinder.js') }}"></script>
</head>

<body>
    @include('includes.admin.sidebar')

    @include('includes.admin.header')

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <script src="{{ asset('/js/script.js') }}"></script>
    @yield('scripts')
</body>

</html>
