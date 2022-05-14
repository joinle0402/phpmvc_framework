@extends('layouts.admin')

@section('content')
    <div class="content">
        <div class="d-flex justify-content-end">
            <a href="{{ url('/admin/subjects/addSubjectForm') }}" class="btn btn-primary">Add Subject</a>
        </div>

        <table class="table mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Image</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subjects as $key => $subject)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $subject['name'] }}</td>
                        <td>
                            <img src="{{ url('/uploads/images/' . $subject['image']) }}" class="table-image" alt="">
                        </td>
                        <td>
                            <div class="d-grid gap-2 d-md-block">
                                <a href="{{ url('/admin/subjects/delete/' . $subject['id']) }}"
                                    class="btn btn-danger">Delete</a>
                                <a href="{{ url('/admin/subjects/update/' . $subject['id']) }}"
                                    class="btn btn-warning">Update</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
