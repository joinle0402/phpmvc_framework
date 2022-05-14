@extends('layouts.admin')

@section('content')
    <div class="content">
        <form method="post" action="{{ url('/admin/examinations/update') }}" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $examinationId }}">

            <div class="mt-3">
                <label class="form-label">Subject Name:</label>
                <select class="form-select" name="subjectId">
                    @foreach ($subjects as $subject)
                        @if (!empty($subject['id']) && $subject['id'] === $examination['subjectId'])
                            <option value="{{ $subject['id'] }}" selected>{{ $subject['name'] }}</option>
                        @else
                            <option value="{{ $subject['id'] }}">{{ $subject['name'] }}</option>
                        @endif
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
                    class="form-control">
            </div>

            <div class="mt-3">
                <label class="form-label">Total Question:</label>
                <select class="form-select" name="totalQuestion">
                    @foreach ($totalQuestions as $totalQuestion)
                        @if (!empty($examination['totalQuestion']) && $examination['totalQuestion'] == $totalQuestion)
                            <option value="{{ $totalQuestion }}" selected>{{ $totalQuestion }} Câu</option>
                        @else
                            <option value="{{ $totalQuestion }}">{{ $totalQuestion }} Câu</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="mt-3">
                <label class="form-label">Total time:</label>
                <select class="form-select" name="totalTime">
                    @foreach ($totalTimes as $totalTime)
                        @if (!empty($examination['totalTime']) && $examination['totalTime'] == $totalTime)
                            <option value="{{ $totalTime }}" selected>{{ $totalTime }} Phút</option>
                        @else
                            <option value="{{ $totalTime }}">{{ $totalTime }} Phút</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Submit</button>
            <a href="{{ url('/admin/examinations') }}" class="btn btn-primary mt-3">Back</a>
        </form>
    </div>
@endsection
