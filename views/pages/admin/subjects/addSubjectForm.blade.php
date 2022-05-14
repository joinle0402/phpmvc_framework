@extends('layouts.admin')

@section('content')
    <div class="content">
        <form method="post" action="{{ url('/admin/subjects/add') }}" enctype="multipart/form-data">
            <div class="mt-3">
                <label class="form-label">Subject Name:</label>
                <input type="text" name="name" value="{{ isset($subject['name']) ? $subject['name'] : false }}"
                    class="form-control{{ !empty($errors['name']) ? ' is-invalid' : '' }}">
                @if (!empty($errors['name']))
                    <div class="invalid-feedback">
                        {{ $errors['name'] }}
                    </div>
                @endif
            </div>

            <div class="mt-3">
                <label for="image" class="form-label">Subject Image:</label>
                <input class="form-control" type="file" id="fileUploader" name="subjectImages[]"
                    value="{{ isset($subject['image']) ? $subject['image'] : false }}" multiple>
                <div class="image-container" id="preview">
                    @if (isset($subject['image']))
                        <img src="{{ url('/uploads/images/' . $subject['image']) }}" class="table-image" alt="">
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Submit</button>
            <button type="submit" class="btn btn-primary mt-3">Back</button>
        </form>
    </div>
@endsection
