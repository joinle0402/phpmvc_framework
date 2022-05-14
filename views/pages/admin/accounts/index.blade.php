@extends('layouts.admin')

@section('content')
    <div class="content">
        <div class="d-flex justify-content-end">
            <a href="{{ url('/admin/accounts/addAccountForm') }}" class="btn btn-primary">Add Accounts</a>
        </div>

        <table class="table mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accounts as $key => $account)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $account['firstName'] }}</td>
                        <td>{{ $account['lastName'] }}</td>
                        <td>{{ $account['username'] }}</td>
                        <td>{{ $account['email'] }}</td>
                        <td>{{ $account['role'] }}</td>
                        <td>
                            <div class="d-grid gap-2 d-md-block">
                                <a href="{{ url('/admin/accounts/delete/' . $account['id']) }}"
                                    class="btn btn-danger">Delete</a>
                                <a href="{{ url('/admin/accounts/update/' . $account['id']) }}"
                                    class="btn btn-warning">Update</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
