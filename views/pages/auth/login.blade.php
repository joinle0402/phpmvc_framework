@extends('layouts.client')

@section('content')
    <div class="container border shadow p-4 mt-3" style="width: 600px">
        <h2 class="text-center mt-3">Đăng nhập</h2>

        @if (!empty($message))
            <div class="alert alert-success mt-3 text-center" role="alert">
                {{ $message }}
            </div>
        @endif

        @if (!empty($error))
            <div class="alert alert-danger mt-3 text-center" role="alert">
                {{ $error }}
            </div>
        @endif

        <div class="mt-4">
            <form method="post" action="">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" value="{{ $rememberUsername }}" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" value="{{ $rememberPassword }}" class="form-control">
                </div>

                <div class="row mb-4 d-flex justify-content-between">
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember"
                                {{ $remember ? 'checked' : '' }} />
                            <label class="form-check-label" for="remember"> Remember me </label>
                        </div>
                    </div>

                    <div class="col d-flex justify-content-end">
                        <a href="#!">Forgot password?</a>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
@endsection
