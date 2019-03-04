<div class="form-group {{ $errors->first('name', 'has-error') }}">
    {!! Form::label('code', Lang::get('chains/title.code'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('code', null, ['class' => 'form-control required','maxlength' => 20]) !!}
        {!! $errors->first('code', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('name', 'has-error') }}">
    {!! Form::label('name', Lang::get('chains/title.name_lang1'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('name', null, ['class' => 'form-control required','maxlength' => 255]) !!}
        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('name_lang2', 'has-error') }}">
    {!! Form::label('name_lang2', Lang::get('chains/title.name_lang2'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('name_lang2', null, ['class' => 'form-control','maxlength' => 255]) !!}
        {!! $errors->first('name_lang2', '<span class="help-block">:message</span>') !!}
    </div>
</div>


<div class="form-group {{ $errors->first('billing_code_info', 'has-error') }}">
    {!! Form::label('billing_code_info', Lang::get('chains/title.billing_code_info'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        {!! Form::textarea('billing_code_info', null, ['class' => 'form-control','maxlength' => 2000, 'rows' => 3]) !!}
        {!! $errors->first('billing_code_info', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('billing_code_info_lang2', 'has-error') }}">
    {!! Form::label('billing_code_info_lang2', Lang::get('chains/title.billing_code_info_lang2'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        {!! Form::textarea('billing_code_info_lang2', null, ['class' => 'form-control','maxlength' => 2000, 'rows' => 3]) !!}
        {!! $errors->first('billing_code_info_lang2', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('promo_text', 'has-error') }}">
    {!! Form::label('promo_text', Lang::get('chains/title.promo_text'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        {!! Form::textarea('promo_text', null, ['class' => 'form-control','maxlength' => 2000, 'rows' => 3]) !!}
        {!! $errors->first('promo_text', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('promo_text_lang2', 'has-error') }}">
    {!! Form::label('promo_text_lang2', Lang::get('chains/title.promo_text_lang2'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        {!! Form::textarea('promo_text_lang2', null, ['class' => 'form-control','maxlength' => 2000, 'rows' => 3]) !!}
        {!! $errors->first('promo_text_lang2', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-2 col-md-10">
        {!! Form::submit(Lang::get('button.save'), ['class' => 'btn btn-success', 'style' => 'width: 160px;']) !!}
        <a href="{!! route('admin.chains.index') !!}" class="btn btn-default"
           style="width: 140px; margin-left: 10px">@lang('button.cancel')</a>
    </div>
</div>



