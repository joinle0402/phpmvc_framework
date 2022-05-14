@extends('layouts.admin')

@section('content')
    <div class="content">
        <div class="d-flex justify-content-end">
            <a href="{{ url('/admin/examinations/addExaminationForm') }}" class="btn btn-primary">Add Examination</a>
        </div>

        <table class="table mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">TotalQuestion</th>
                    <th scope="col">TotalTime</th>
                    <th scope="col">CreatedDate</th>
                    <th scope="col">Subject</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($examinations as $key => $examination)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>
                            <a href="{{ url('/admin/examinations/' . $examination['id']) }}">
                                {{ $examination['name'] }}
                            </a>
                        </td>
                        <td>{{ $examination['totalQuestion'] }} Câu </td>
                        <td>{{ $examination['totalTime'] }} Phút </td>
                        <td>{{ $examination['createdDate'] }}</td>
                        <td>{{ $examination['subject'] }}</td>
                        <td>
                            <div class="d-grid gap-2 d-md-block">
                                <a href="{{ url('/admin/examinations/delete/' . $examination['id']) }}"
                                    class="btn btn-danger">Delete</a>
                                <a href="{{ url('/admin/examinations/update/' . $examination['id']) }}"
                                    class="btn btn-warning">Update</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
