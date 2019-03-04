<div class="form-group {{ $errors->first('token_num', 'has-error') }}">
    {!! Form::label('first_name', Lang::get('tokens/title.token_num'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4">
        @if(isset($edit))
            <h3 style="margin:0px">{!! Form::label('token_num', $token->token_num, ['class' => 'label label-info']) !!}</h3>
            {!! Form::hidden('token_num', null) !!}
        @else
            {!! Form::text('token_num', null, ['class' => 'form-control required','maxlength' => 9,'style="width:160px"']) !!}
            {!! $errors->first('token_num', '<span class="help-block">:message</span>') !!}
        @endif
    </div>
</div>

<div class="form-group {{ $errors->first('title', 'has-error') }}">
    {!! Form::label('first_name', Lang::get('tokens/title.title'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('title', null, ['class' => 'form-control required','maxlength' => 100]) !!}
        {!! $errors->first('title', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('purpose_desc', 'has-error') }}">
    {!! Form::label('first_name', Lang::get('tokens/title.purpose_desc'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('purpose_desc', null, ['class' => 'form-control required','maxlength' => 500]) !!}
        {!! $errors->first('purpose_desc', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('fe_default_lang', 'has-error') }}">
    {!! Form::label('fe_default_lang', Lang::get('tokens/title.fe_default_lang'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4">
        {!! Form::radio('fe_default_lang', \App\Http\Common\ORAConsts::LANGUAGE1, !isset($token) || $token->fe_default_lang == \App\Http\Common\ORAConsts::LANGUAGE1, ['class' => '', 'style'=> 'margin-top:10px;']) !!}
        @lang("lang.en")
        {!! Form::radio('fe_default_lang', \App\Http\Common\ORAConsts::LANGUAGE2, isset($token) && $token->fe_default_lang == \App\Http\Common\ORAConsts::LANGUAGE2, ['class' => '', 'style'=> 'margin-top:10px;margin-left:20px']) !!}
        @lang("lang.hi")
        {!! $errors->first('fe_default_lang', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('fe_color_1', 'has-error') }}">
    {!! Form::label('fe_color_1', Lang::get('tokens/title.fe_color_1'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4">
        <div id="cp1" class="input-group colorpicker-component">
            {!! Form::text('fe_color_1', isset($token->fe_color_1) ? null: '#7f2c7c', ['class' => 'form-control required','maxlength' => 20]) !!}
            <span class="input-group-addon"><i></i></span>
        </div>
        {!! $errors->first('fe_color_1', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('fe_color_2', 'has-error') }}">
    {!! Form::label('fe_color_2', Lang::get('tokens/title.fe_color_2'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4">
        <div id="cp2" class="input-group colorpicker-component">
            {!! Form::text('fe_color_2', isset($token->fe_color_2) ? null: '#d71249', ['class' => 'form-control required','maxlength' => 20]) !!}
            <span class="input-group-addon"><i></i></span>
        </div>
        {!! $errors->first('fe_color_2', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('fe_color_3', 'has-error') }}">
    {!! Form::label('fe_color_3', Lang::get('tokens/title.fe_color_3'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4">
        <div id="cp3" class="input-group colorpicker-component">
            {!! Form::text('fe_color_3', isset($token->fe_color_3) ? null: '#ffffff', ['class' => 'form-control required','maxlength' => 20]) !!}
            <span class="input-group-addon"><i></i></span>
        </div>
        {!! $errors->first('fe_color_3', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('fe_color_4', 'has-error') }}">
    {!! Form::label('fe_color_4', Lang::get('tokens/title.fe_color_4'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4">
        <div id="cp4" class="input-group colorpicker-component">
            {!! Form::text('fe_color_4', isset($token->fe_color_4) ? null: '#000000', ['class' => 'form-control required','maxlength' => 20]) !!}
            <span class="input-group-addon"><i></i></span>
        </div>
        {!! $errors->first('fe_color_4', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('fe_image_url', 'has-error') }}">
    {!! Form::label('fe_image_url', Lang::get('tokens/title.fe_image_url'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-sm-10">
        <div class="fileinput fileinput-new" data-provides="fileinput">
            <div class="fileinput-new thumbnail" >
                @if(isset($token) && $token->fe_image_url)
                    <img class="token-img" src="{!! $token->fe_image_url . "?x=" . time() !!}" alt="@lang('tokens/title.fe_image_url')">
                @elseif(isset($default_image_url) && $default_image_url)
                    <img class="token-img" src="{!! $default_image_url . "?x=" . time() !!}" alt="@lang('tokens/title.fe_image_url')">
                @else
                    <img class="token-img" src="{{ asset('assets/img/upload_placeholder.png') }}" alt="@lang('tokens/title.fe_image_url')">
                @endif
            </div>
            <div class="fileinput-preview fileinput-exists thumbnail token-img"></div>
            <div>
                <span class="btn btn-default btn-file">
                    <span class="fileinput-new">@lang('button.select_image')</span>
                    <span class="fileinput-exists">@lang('button.change')</span>
                    <input id="fe_image_url" name="fe_image" type="file" class="form-control"/>
                </span>
                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">@lang('button.remove')</a>
            </div>
        </div>
        {!! $errors->first('fe_image_url', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('fe_welcome_text', 'has-error') }}">
    {!! Form::label('fe_welcome_text', Lang::get('tokens/title.fe_welcome_text'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('fe_welcome_text', isset($token->fe_welcome_text) ? null : Lang::get('tokens/title.fe_welcome_text_default'), ['class' => 'form-control','maxlength' => 2000,'rows' => 5]) !!}
        {!! $errors->first('fe_welcome_text', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('fe_welcome_text_lang2', 'has-error') }}">
    {!! Form::label('fe_welcome_text_lang2', Lang::get('tokens/title.fe_welcome_text_lang2'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('fe_welcome_text_lang2', isset($token->fe_welcome_text) ? null : Lang::get('tokens/title.fe_welcome_text_lang2_default'), ['class' => 'form-control','maxlength' => 2000,'rows' => 5]) !!}
        {!! $errors->first('fe_welcome_text_lang2', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('fe_welcome_text_size', 'has-error') }}">
    {!! Form::label('fe_welcome_text_size', Lang::get('tokens/title.fe_welcome_text_size'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-1">
        {!! Form::text('fe_welcome_text_size', isset($token->fe_welcome_text_size) ? null: '20', ['class' => 'form-control','maxlength' => 2]) !!}
        {!! $errors->first('fe_welcome_text_size', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('fe_snippet_text', 'has-error') }}">
    {!! Form::label('fe_snippet_text', Lang::get('tokens/title.fe_snippet_text'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('fe_snippet_text', null, ['class' => 'form-control','maxlength' => 4000,'rows' => 8]) !!}
        {!! $errors->first('fe_snippet_text', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('fe_snippet_text_lang2', 'has-error') }}">
    {!! Form::label('fe_snippet_text_lang2', Lang::get('tokens/title.fe_snippet_text_lang2'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('fe_snippet_text_lang2', null, ['class' => 'form-control','maxlength' => 4000,'rows' => 8]) !!}
        {!! $errors->first('fe_snippet_text_lang2', '<span class="help-block">:message</span>') !!}
    </div>
</div>


<div class="form-group {{ $errors->first('return_url', 'has-error') }}">
    {!! Form::label('return_url', Lang::get('tokens/title.return_url'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('return_url', null, ['class' => 'form-control','maxlength' => 255]) !!}
        {!! $errors->first('return_url', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('is_incentive', 'has-error') }}">
    {!! Form::label('is_incentive', Lang::get('tokens/title.is_incentive'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-6">
        {!! Form::checkbox('is_incentive', 1, isset($token) && $token->is_incentive == 1, ['class' => '', 'style'=> 'margin-top:10px']) !!}
        {!! $errors->first('is_incentive', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('skip_risk_assessment', 'has-error') }}">
    {!! Form::label('skip_risk_assessment', Lang::get('tokens/title.skip_risk_assessment'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-6">
        {!! Form::checkbox('skip_risk_assessment', 1, isset($token) && $token->skip_risk_assessment == 1, ['class' => '', 'style'=> 'margin-top:10px']) !!}
        {!! $errors->first('skip_risk_assessment', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-2 col-md-10">
        {!! Form::submit(Lang::get('button.save'), ['class' => 'btn btn-success', 'style' => 'width: 160px;']) !!}
        <a href="{!! route('admin.tokens.index') !!}" class="btn btn-default"
           style="width: 140px; margin-left: 10px">@lang('button.cancel')</a>
    </div>
</div>



<script> $(function () {
        $('#cp1').colorpicker({
            format: 'hex'
        });
        $('#cp2').colorpicker({
            format: 'hex'
        });
        $('#cp3').colorpicker({
            format: 'hex'
        });
        $('#cp4').colorpicker({
            format: 'hex'
        });
    }); </script>
