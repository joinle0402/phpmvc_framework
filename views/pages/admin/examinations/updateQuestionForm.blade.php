@extends('layouts.admin')

@section('content')
    <div class="content">
        @php
            $updateQuestionLink = "/admin/examinations/$examinationId/questions/update/" . $question['id'];
        @endphp
        <form method="post" action="{{ url($updateQuestionLink) }}">

            <div class="mt-3">
                <label class="form-label">Question content:</label>
                <input type="text" name="content" value="{{ isset($question['content']) ? $question['content'] : false }}"
                    class="form-control{{ !empty($errors['name']) ? ' is-invalid' : '' }}">
                @if (!empty($errors['name']))
                    <div class="invalid-feedback">
                        {{ $errors['name'] }}
                    </div>
                @endif
            </div>

            @foreach ($question['options'] as $index => $option)
                <div class="mt-3">
                    <label class="form-label">Option Title {{ $index + 1 }}:</label>
                    <div class="form-group d-flex align-items-center gap-3">
                        <input type="checkbox" class="form-check-input" name="isCorrect[]" value="{{ $index + 1 }}"
                            {{ $option['isCorrect'] == 1 ? 'checked' : '' }}>
                        <input type="text" name="options[]"
                            value="{{ isset($option['content']) ? $option['content'] : false }}"
                            class="form-control{{ !empty($errors['name']) ? ' is-invalid' : '' }}">
                        @if (!empty($errors['name']))
                            <div class="invalid-feedback">
                                {{ $errors['name'] }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach


            <button type="submit" class="btn btn-primary mt-3">Submit</button>
            <a href="{{ url('/admin/examinations') }}" class="btn btn-primary mt-3">Back</a>
        </form>
    </div>
@endsection
