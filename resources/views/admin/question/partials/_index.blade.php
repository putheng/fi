<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left">
                <i class="livicon" data-name="list" data-size="16"
                   data-loop="true" data-c="#fff" data-hc="white"></i>
                    @lang('questions.question_title')
            </h4>

            <div class="pull-right">
                <a href="{{ route('admin.question.create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{ __('questions.create') }}</a>
            </div>
        </div>
            <div class="panel-body">
                <!--main content-->
                <div class="row">
                    <div class="col-md-7 col-md-offset-2">
                        <!--main content-->
                        <ul class="list-group">
                            @foreach($questions as $question)
                                <li class="list-group-item">
                                    <span class="pull-right">
                                        <a class="badge" href="{{ route('admin.question.answer', $question) }}">
                                            {{ __('questions.add_answer') }}
                                        </a>
                                        <a href="{{ route('admin.question.edit', $question) }}" class="badge">{{ __('questions.edit') }}</a>
                                        <a onclick="deleteQuestion('{{ route('admin.question.delete', $question) }}')" href="#" class="badge">{{ __('questions.delete') }}</a>
                                    </span>
                                    <span class="font-sr">{{ $question->titleKh }}</span>
                                    <br>
                                    {{ $question->titleEn }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>