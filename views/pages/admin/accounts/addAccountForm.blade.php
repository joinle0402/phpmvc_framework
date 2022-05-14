@extends('layouts.admin')

@section('content')
    <div class="content">
        <form method="post" action="{{ url('/admin/accounts/add') }}">
            <div class="row mb-3">
                <div class="col-6">
                    <label class="form-label">First Name:</label>
                    <input type="text" name="firstName"
                        value="{{ isset($account['firstName']) ? $account['firstName'] : false }}"
                        class="form-control{{ !empty($errors['firstName']) ? ' is-invalid' : '' }}">
                    @if (!empty($errors['firstName']))
                        <div class="invalid-feedback">
                            {{ $errors['firstName'] }}
                        </div>
                    @endif
                </div>

                <div class="col-6">
                    <label class="form-label">Last Name:</label>
                    <input type="text" name="lastName"
                        value="{{ isset($account['lastName']) ? $account['lastName'] : false }}"
                        class="form-control{{ !empty($errors['lastName']) ? ' is-invalid' : '' }}">
                    @if (!empty($errors['lastName']))
                        <div class="invalid-feedback">
                            {{ $errors['lastName'] }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="text" name="email" value="{{ !empty($account['email']) ? $account['email'] : false }}"
                    class="form-control{{ isset($errors['email']) ? ' is-invalid' : '' }}">
                @if (!empty($errors['email']))
                    <div class="invalid-feedback">
                        {{ $errors['email'] }}
                    </div>
                @endif
            </div>
            <div class="mb-3">
                <label class="form-label">Username:</label>
                <input type="text" name="username"
                    value="{{ !empty($account['username']) ? $account['username'] : false }}"
                    class="form-control{{ !empty($errors['username']) ? ' is-invalid' : '' }}">
                @if (!empty($errors['username']))
                    <div class="invalid-feedback">
                        {{ $errors['username'] }}
                    </div>
                @endif
            </div>
            <div class="mb-3">
                <label class="form-label">Password:</label>
                <input type="password" name="password"
                    value="{{ !empty($account['password']) ? $account['password'] : false }}"
                    class="form-control{{ !empty($errors['password']) ? ' is-invalid' : '' }}">
                @if (!empty($errors['password']))
                    <div class="invalid-feedback">
                        {{ $errors['password'] }}
                    </div>
                @endif
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password:</label>
                <input type="password" name="confirmPassword"
                    value="{{ !empty($account['password']) ? $account['password'] : false }}"
                    class="form-control{{ !empty($errors['password']) ? ' is-invalid' : '' }}">
                @if (!empty($errors['password']))
                    <div class="invalid-feedback">
                        {{ $errors['password'] }}
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">User Role:</label>
                <select class="form-select" name="role">
                    <option value="USER"
                        {{ !empty($account['role']) ? ($account['role'] === 'USER' ? 'selected' : '') : false }}>User
                    </option>
                    <option value="ADMINISTRATOR"
                        {{ !empty($account['role']) ? ($account['role'] === 'ADMINISTRATOR' ? 'selected' : '') : false }}>
                        Administrator
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
