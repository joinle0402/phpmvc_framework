@extends('layouts.admin')


@section('content')
    <div class="content">
        <div class="d-flex justify-content-end">
            @php
                $addQuestionFormLink = "/admin/examinations/$examinationId";
            @endphp
            <a href="{{ url($addQuestionFormLink . '/addQuestionForm') }}" class="btn btn-primary">New Question</a>
        </div>
        @foreach ($questions as $index => $question)
            <div class="quizz-item mt-3 ">
                <div class="d-flex justify-content-end gap-2 mb-2">
                    <a href="{{ url($addQuestionFormLink . '/questions/update/' . $question['id']) }}"
                        class="btn btn-warning"><i class='bx bx-edit-alt'></i></a>
                    <a href="{{ url($addQuestionFormLink . '/questions/delete/' . $question['id']) }}"
                        class="btn btn-danger"><i class='bx bx-trash'></i></a>
                </div>
                <div class="quizz-item-question">
                    <h2 class="quizz-item-question-title">Câu {{ $index + 1 }}: { <p>{{ $question['content'] }}</p> }</h2>
                </div>
                <div class="quizz-item-answers">
                    @foreach ($question['options'] as $option)
                        <div class="quizz-item-answer {{ $option['isCorrect'] ? 'quizz-item-answer--isCorrect' : '' }}">
                            <input class="quizz-item-answer-radio" type="radio" name="answer" id="answer"
                                value="{{ $option['id'] }}" />
                            <label class="quizz-item-answer-label" for="answer">{! $option['content'] !}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection
