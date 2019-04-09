<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left">
                    <i class="livicon" data-name="list" data-size="16"
                       data-loop="true" data-c="#fff" data-hc="white"></i>
                        @lang('questions.results')
                </h4>

                <div class="pull-right">
                    <a href="{{ route('admin.question.result') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{ __('questions.create') }}</a>
                </div>
            </div>
            <div class="panel-body">
                <!--main content-->
                <div class="row">
                    <div class="col-md-7 col-md-offset-2">
                        <!--main content-->
                        @if($results->count())
                            <ul class="list-group">
                                @foreach($results as $key => $result)
                                    <li class="list-group-item">
                                        <span class="pull-right">
                                            <a href="{{ route('admin.question.result.edit', $result) }}" class="badge">{{ __('questions.edit') }}</a>
                                            <a onclick="deleteQuestion('{{ route('admin.question.result.delete', $result) }}')" href="#" class="badge">{{ __('questions.delete') }}</a>
                                        </span>
                                        <span class="font-sr">{{ $result->title }}</span>
                                        <br>
                                        {{ $result->titleEn }}
                                        <br>
                                        {{ $result->from }} -
                                        {{ $result->to }}
                                    </li>
                                @endforeach
                            </ul>
                        @else

                        <p>There are no any result yet
                            <a href="{{ route('admin.question.result') }}">Create new</a>
                        </p>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>