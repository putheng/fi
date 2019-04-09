<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left">
                <i class="livicon" data-name="list" data-size="16"
                   data-loop="true" data-c="#fff" data-hc="white"></i>
                    @lang('page.recommend')
            </h4>

        </div>
            <div class="panel-body">
                <!--main content-->
                <div class="row">
                    <div class="col-md-7 col-md-offset-2">
                        <!--main content-->
                        @if($recommends->count())
                            <ul class="list-group">
                                @foreach($recommends as $key => $recommend)
                                    <li class="list-group-item">
                                        <span class="pull-right">
                                            <a href="{{ route('admin.re.update', $recommend) }}" class="badge">{{ __('questions.edit') }}</a>
                                        </span>
                                        <span class="font-sr">{{ $recommend->title }}</span>
                                        <br>
                                        {{ $recommend->title_en }}
                                        <br>
                                    </li>
                                @endforeach
                            </ul>
                        @else

                        <p class="text-center">Empty
                            <a href="{{ route('admin.re.create') }}">Create new</a>
                        </p>

                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left">
                    <i class="livicon" data-name="list" data-size="16"
                       data-loop="true" data-c="#fff" data-hc="white"></i>
                        @lang('questions.free')
                </h4>

                <div class="pull-right">
                    <a href="{{ route('admin.question.info.create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{ __('questions.create') }}</a>
                </div>
            </div>
            <div class="panel-body">
                <!--main content-->
                <div class="row">
                    <div class="col-md-7 col-md-offset-2">
                        <!--main content-->
                        @if($infos->count())
                            <ul class="list-group">
                                @foreach($infos as $key => $info)
                                    <li class="list-group-item">
                                        <span class="pull-right">
                                            <a href="{{ route('admin.question.info.edit', $info) }}" class="badge">{{ __('questions.edit') }}</a>
                                            <a href="{{ route('admin.question.info.answer.create', $info) }}" class="badge">{{ __('questions.answer') }}</a>
                                            <a href="{{ route('admin.question.info.delete', $info) }}" class="badge">{{ __('questions.delete') }}</a>
                                        </span>
                                        <span class="font-sr">{{ $info->title }}</span>
                                        <br>
                                        {{ implode($info->answers()->pluck('title')->toArray(), ' - ') }}
                                        
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