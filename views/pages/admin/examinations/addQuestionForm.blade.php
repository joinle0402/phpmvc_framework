@extends('layouts.admin')

@section('content')
    <div class="content">
        @php
            $addQuestionLink = "/admin/examinations/$examinationId/addQuestion";
        @endphp
        <form method="post" action="{{ url($addQuestionLink) }}">
            <div class="mt-3">
                <label class="form-label">Question content:</label>
                <textarea type="text" id="content" name="content" style="resize: none;"
                    value="{{ isset($examination['name']) ? $examination['name'] : false }}"
                    class="form-control{{ !empty($errors['name']) ? ' is-invalid' : '' }}">
                </textarea>
                @if (!empty($errors['name']))
                    <div class="invalid-feedback">
                        {{ $errors['name'] }}
                    </div>
                @endif
            </div>

            <div class="mt-3">
                <label class="form-label">Option Title 1:</label>
                <input type="checkbox" class="form-check-input" name="isCorrect[]" value="1" id="">
                <textarea type="text" id="option1" name="options[]" style="resize: none;"
                    value="{{ isset($examination['name']) ? $examination['name'] : false }}"
                    class="form-control{{ !empty($errors['name']) ? ' is-invalid' : '' }}">
                </textarea>
            </div>

            <div class="mt-3">
                <label class="form-label">Option Title 2:</label>
                <input type="checkbox" class="form-check-input" name="isCorrect[]" value="2" id="">
                <textarea type="text" id="option2" name="options[]" style="resize: none;"
                    value="{{ isset($examination['name']) ? $examination['name'] : false }}"
                    class="form-control{{ !empty($errors['name']) ? ' is-invalid' : '' }}">
                </textarea>
            </div>

            <div class="mt-3">
                <label class="form-label">Option Title 3:</label>
                <input type="checkbox" class="form-check-input" name="isCorrect[]" value="3" id="">
                <textarea type="text" id="option3" name="options[]" style="resize: none;"
                    value="{{ isset($examination['name']) ? $examination['name'] : false }}"
                    class="form-control{{ !empty($errors['name']) ? ' is-invalid' : '' }}">
                </textarea>
            </div>

            <div class="mt-3">
                <input type="checkbox" class="form-check-input" name="isCorrect[]" value="4" id="">
                <label class="form-label">Option Title 4:</label>
                <textarea id="option4" type="text" name="options[]" style="resize: none;"
                    value="{{ isset($examination['name']) ? $examination['name'] : false }}"
                    class="form-control{{ !empty($errors['name']) ? ' is-invalid' : '' }}">
                </textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Submit</button>
            <a href="{{ url('/admin/examinations') }}" class="btn btn-primary mt-3">Back</a>
        </form>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        CKEDITOR.plugins.addExternal('ckeditor_wiris',
            '../../../public/js/node_modules/@wiris/mathtype-ckeditor4/plugin.js');

        const mathElements = [
            'math',
            'maction',
            'maligngroup',
            'malignmark',
            'menclose',
            'merror',
            'mfenced',
            'mfrac',
            'mglyph',
            'mi',
            'mlabeledtr',
            'mlongdiv',
            'mmultiscripts',
            'mn',
            'mo',
            'mover',
            'mpadded',
            'mphantom',
            'mroot',
            'mrow',
            'ms',
            'mscarries',
            'mscarry',
            'msgroup',
            'msline',
            'mspace',
            'msqrt',
            'msrow',
            'mstack',
            'mstyle',
            'msub',
            'msup',
            'msubsup',
            'mtable',
            'mtd',
            'mtext',
            'mtr',
            'munder',
            'munderover',
            'semantics',
            'annotation',
            'annotation-xml',
            'mprescripts',
            'none'
        ];

        const ckeditorConfigs = {
            extraPlugins: 'ckeditor_wiris',
            height: 320,
            width: '100%',
            extraAllowedContent: mathElements.join(' ') +
                '(*)[*]{*};img[data-mathml,data-custom-editor,role](Wirisformula)',
            removePlugins: 'resize',

            filebrowserBrowseUrl: 'http://localhost/phpmvc_framework/public/js/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl: 'http://localhost/phpmvc_framework/public/js/ckfinder/ckfinder.html?Type=Images',
            filebrowserUploadUrl: 'http://localhost/phpmvc_framework/public/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl: 'http://localhost/phpmvc_framework/public/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        }

        const contentCkeditor = CKEDITOR.replace('content', ckeditorConfigs);
        const option1Ckeditor = CKEDITOR.replace('option1', ckeditorConfigs);
        const option2Ckeditor = CKEDITOR.replace('option2', ckeditorConfigs);
        const option3Ckeditor = CKEDITOR.replace('option3', ckeditorConfigs);
        const option4Ckeditor = CKEDITOR.replace('option4', ckeditorConfigs);
    </script>
@endsection
