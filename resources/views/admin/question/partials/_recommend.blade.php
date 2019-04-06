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