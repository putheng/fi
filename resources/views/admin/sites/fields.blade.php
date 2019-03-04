<div class="form-group {{ $errors->first('name', 'has-error') }}">
    {!! Form::label('code', Lang::get('sites/title.code'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('code', null, ['class' => 'form-control required','maxlength' => 20]) !!}
        {!! $errors->first('code', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('name', 'has-error') }}">
    {!! Form::label('name', Lang::get('sites/title.name_lang1'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('name', null, ['class' => 'form-control required','maxlength' => 255]) !!}
        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('name_lang2', 'has-error') }}">
    {!! Form::label('name_lang2', Lang::get('sites/title.name_lang2'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('name_lang2', null, ['class' => 'form-control required','maxlength' => 255]) !!}
        {!! $errors->first('name_lang2', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('gps_lat', 'has-error') }}">
    {!! Form::label('gps_lat', Lang::get('sites/title.gps_lat'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('gps_lat', null, ['class' => 'form-control required','maxlength' => 20]) !!}
        {!! $errors->first('gps_lat', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('gps_long', 'has-error') }}">
    {!! Form::label('gps_long', Lang::get('sites/title.gps_long'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('gps_long', null, ['class' => 'form-control required','maxlength' => 20]) !!}
        {!! $errors->first('gps_long', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('sort_index', 'has-error') }}">
    {!! Form::label('sort_index', Lang::get('sites/title.sort_index'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('sort_index', null, ['class' => 'form-control required','maxlength' => 7]) !!}
        {!! $errors->first('sort_index', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-2 col-md-10">
        {!! Form::submit(Lang::get('button.save'), ['class' => 'btn btn-success', 'style' => 'width: 160px;']) !!}
        <a href="{!! route('admin.sites.index') !!}" class="btn btn-default"
           style="width: 140px; margin-left: 10px">@lang('button.cancel')</a>
    </div>
</div>



