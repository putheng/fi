<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left">
                <i class="livicon" data-name="list" data-size="16"
                   data-loop="true" data-c="#fff" data-hc="white"></i>
                    @lang('questions.welcome')
            </h4>
        </div>
            <div class="panel-body">
                <!--main content-->
                <div class="row">
                    <div class="col-md-7 col-md-offset-2">
                        <!--main content-->
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <span class="pull-right">
                                        <a href="{{ route('admin.term.edit') }}" class="badge">{{ __('questions.edit') }}</a>
                                        
                                    </span>
                                    <span class="font-sr">{{ $term->title }}</span>
                                    <br>
                                    {{ $term->title_en }}
                                    <br>
                                </li>
                            </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>