@extends('layouts.admin')

@section('content')
    <div class="content">
        <form method="post" action="{{ url('/admin/examinations/add') }}" enctype="multipart/form-data">

            <div class="mt-3">
                <label class="form-label">Subject Name:</label>
                <select class="form-select" name="subjectId">
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject['id'] }}">{{ $subject['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-3">
                <label class="form-label">Name:</label>
                <input type="text" name="name" value="{{ isset($examination['name']) ? $examination['name'] : false }}"
                    class="form-control{{ !empty($errors['name']) ? ' is-invalid' : '' }}">
                @if (!empty($errors['name']))
                    <div class="invalid-feedback">
                        {{ $errors['name'] }}
                    </div>
                @endif
            </div>

            <div class="mt-3">
                <label class="form-label">Description:</label>
                <input type="text" name="description"
                    value="{{ isset($examination['description']) ? $examination['description'] : false }}"
                    class="form-control"
                >
            </div>

            <div class="mt-3">
                <label class="form-label">Total Question:</label>
                <select class="form-select" name="totalQuestion">
                    @foreach ($totalQuestions as $totalQuestion)
                        <option value="{{ $totalQuestion }}"
                            {{ $totalQuestion === $defaultTotalQuestion ? ' selected' : '' }}>{{ $totalQuestion }} Câu
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mt-3">
                <label class="form-label">Total time:</label>
                <select class="form-select" name="totalTime">
                    @foreach ($totalTimes as $totalTime)
                        <option value="{{ $totalTime }}" {{ $totalTime === $defaultTotalTime ? ' selected' : '' }}>
                            {{ $totalTime }} Phút
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Submit</button>
            <a href="{{ url('/admin/examinations') }}" class="btn btn-primary mt-3">Back</a>
        </form>
    </div>
@endsection
