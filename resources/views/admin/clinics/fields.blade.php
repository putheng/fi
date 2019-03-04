<div class="form-group {{ $errors->first('code', 'has-error') }}">
    {!! Form::label('code', Lang::get('clinics/title.code'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        @if(isset($edit))
            <h3 style="margin:0px">{!! Form::label('code', $clinic->code, ['class' => 'label label-info']) !!}</h3>
            {!! Form::hidden('code', null) !!}
        @else
            {!! Form::text('code', null, ['class' => 'form-control required','maxlength' => 15,'style="width:160px"']) !!}
            {!! $errors->first('code', '<span class="help-block">:message</span>') !!}
        @endif
    </div>
</div>

@if($isMyClinic)
    {!! Form::hidden('sort_index', (isset($clinic) ? $clinic->site_id : "")) !!}
    {!! Form::hidden('chain_id', (isset($clinic) ? $clinic->chain_id : "")) !!}
@else

    <div class="form-group {{ $errors->first('site_id', 'has-error') }}">
        {!! Form::label('site_id', Lang::get('clinics/title.site_id'), ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-4">
            <select class="form-control select2" title="@lang('clinics/title.res_time_slot_length')" name="site_id"
                    id="site_id" required>
                @foreach($sites as $site)
                    <option value="{{ $site->id }}"
                            @if(isset($clinic) && $clinic->site_id == $site->id) selected="selected" @endif
                    >{{ $site->name }}</option>
                @endforeach
            </select>
            {!! $errors->first('site_id', '<span class="help-block">:message</span>') !!}
        </div>
    </div>


    <div class="form-group {{ $errors->first('chain_id', 'has-error') }}">
        {!! Form::label('chain_id', Lang::get('clinics/title.chain_id'), ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-4">
            <select class="form-control select2" title="@lang('clinics/title.res_time_slot_length')" name="chain_id"
                    id="chain_id" required>
                @foreach($chains as $chain)
                    <option value="{{ $chain->id }}"
                            @if(isset($clinic) && $clinic->chain_id == $chain->id) selected="selected" @endif
                    >{{ $chain->name }}</option>
                @endforeach
            </select>
            {!! $errors->first('chain_id', '<span class="help-block">:message</span>') !!}
        </div>
    </div>
@endif

<div class="form-group {{ $errors->first('name', 'has-error') }}">
    {!! Form::label('name', Lang::get('clinics/title.name'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, ['class' => 'form-control required','maxlength' => 100]) !!}
        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('name_lang2', 'has-error') }}">
    {!! Form::label('name_lang2', Lang::get('clinics/title.name_lang2'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        {!! Form::text('name_lang2', null, ['class' => 'form-control required','maxlength' => 100]) !!}
        {!! $errors->first('name_lang2', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('location_desc', 'has-error') }}">
    {!! Form::label('location_desc', Lang::get('clinics/title.location_desc'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        {!! Form::text('location_desc', null, ['class' => 'form-control required','maxlength' => 500]) !!}
        {!! $errors->first('location_desc', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('location_desc_lang2', 'has-error') }}">
    {!! Form::label('location_desc_lang2', Lang::get('clinics/title.location_desc_lang2'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        {!! Form::text('location_desc_lang2', null, ['class' => 'form-control required','maxlength' => 500]) !!}
        {!! $errors->first('location_desc_lang2', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('address_desc', 'has-error') }}">
    {!! Form::label('address_desc', Lang::get('clinics/title.address_desc'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        {!! Form::textarea('address_desc', null, ['class' => 'form-control','maxlength' => 500,'rows' => 3]) !!}
        {!! $errors->first('address_desc', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('address_desc_lang2', 'has-error') }}">
    {!! Form::label('address_desc_lang2', Lang::get('clinics/title.address_desc_lang2'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        {!! Form::textarea('address_desc_lang2', null, ['class' => 'form-control','maxlength' => 500,'rows' => 3]) !!}
        {!! $errors->first('address_desc_lang2', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('gps_lat', 'has-error') }} {{ $errors->first('gps_long', 'has-error') }}">
    {!! Form::label('gps_lat', Lang::get('clinics/title.gps_lat'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('gps_lat', null, ['class' => 'form-control required','maxlength' => 500]) !!}
        {!! $errors->first('gps_lat', '<span class="help-block">:message</span>') !!}
    </div>
    {!! Form::label('gps_long', Lang::get('clinics/title.gps_long'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('gps_long', null, ['class' => 'form-control required','maxlength' => 500]) !!}
        {!! $errors->first('gps_long', '<span class="help-block">:message</span>') !!}
    </div>
</div>


<div class="form-group {{ $errors->first('email', 'has-error') }}">
    {!! Form::label('email', Lang::get('clinics/title.email'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        {!! Form::text('email', null, ['class' => 'form-control required','maxlength' => 500]) !!}
        {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('phone_num', 'has-error') }}">
    {!! Form::label('phone_num', Lang::get('clinics/title.phone_num'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('phone_num', null, ['class' => 'form-control required','maxlength' => 500]) !!}
        {!! $errors->first('phone_num', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('website_url', 'has-error') }}">
    {!! Form::label('website_url', Lang::get('clinics/title.website_url'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        {!! Form::text('website_url', null, ['class' => 'form-control required','maxlength' => 500]) !!}
        {!! $errors->first('website_url', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('video_url', 'has-error') }}" style="display: none">
    {!! Form::label('video_url', Lang::get('clinics/title.video_url'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        {!! Form::text('video_url', null, ['class' => 'form-control required','maxlength' => 1000]) !!}
        {!! $errors->first('video_url', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('forward_url', 'has-error') }}">
    {!! Form::label('forward_url', Lang::get('clinics/title.forward_url'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        {!! Form::text('forward_url', null, ['class' => 'form-control','maxlength' => 1000]) !!}
        {!! $errors->first('forward_url', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('directions_desc', 'has-error') }}">
    {!! Form::label('directions_desc', Lang::get('clinics/title.directions_desc'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        {!! Form::textarea('directions_desc', null, ['class' => 'form-control','maxlength' => 750,'rows' => 3]) !!}
        {!! $errors->first('directions_desc', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('directions_desc_lang2', 'has-error') }}">
    {!! Form::label('directions_desc_lang2', Lang::get('clinics/title.directions_desc_lang2'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        {!! Form::textarea('directions_desc_lang2', null, ['class' => 'form-control','maxlength' => 750,'rows' => 3]) !!}
        {!! $errors->first('directions_desc_lang2', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('logo_url', 'has-error') }}">
    {!! Form::label('logo_url', Lang::get('clinics/title.logo_url'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        <div class="fileinput fileinput-new" data-provides="fileinput">
            <div class="fileinput-new thumbnail">
                @if(isset($clinic) && $clinic->logo_url)
                    <img class="clinic-img" src="{!! $clinic->logo_url . "?x=" . time() !!}"
                         alt="@lang('clinics/title.logo_url')">
                @else
                    <img class="clinic-img" src="{{ asset('assets/img/upload_placeholder.png') }}"
                         alt="@lang('clinics/title.logo_url')">
                @endif
            </div>
            <div class="fileinput-preview fileinput-exists thumbnail clinic-img"></div>
            <div>
                <span class="btn btn-default btn-file">
                    <span class="fileinput-new">@lang('button.select_image')</span>
                    <span class="fileinput-exists">@lang('button.change')</span>
                    <input id="logo_url" name="logo_file" type="file" class="form-control"/>
                </span>
                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">@lang('button.remove')</a>
            </div>
        </div>
        {!! $errors->first('logo_url', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('res_time_slot_length', 'has-error') }}">
    {!! Form::label('first_name', Lang::get('clinics/title.res_time_slot_length'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4" style="width: 140px">
        <select class="form-control select2" title="@lang('clinics/title.res_time_slot_length')"
                name="res_time_slot_length" id="res_time_slot_length" required>
            @foreach($slot_lengths as $slot_length)
                <option value="{{ $slot_length }}"
                        @if((isset($clinic) && $clinic->res_time_slot_length == $slot_length) ||
                            (!isset($clinic) && $slot_length == 15)) selected="selected" @endif
                >{{ $slot_length }}</option>
            @endforeach
        </select>
        {!! $errors->first('res_time_slot_length', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('res_notes', 'has-error') }}">
    {!! Form::label('res_notes', Lang::get('clinics/title.res_notes'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        {!! Form::textarea('res_notes', null, ['class' => 'form-control','maxlength' => 4000,'rows' => 5]) !!}
        {!! $errors->first('res_notes', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('res_notes_lang2', 'has-error') }}">
    {!! Form::label('res_notes_lang2', Lang::get('clinics/title.res_notes_lang2'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        {!! Form::textarea('res_notes_lang2', null, ['class' => 'form-control','maxlength' => 4000,'rows' => 5]) !!}
        {!! $errors->first('res_notes_lang2', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('res_options', 'has-error') }}">
    {!! Form::label('res_options', Lang::get('clinics/title.res_options'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::checkbox('res_options', 1, isset($clinic) && $clinic->res_options == 1, ['class' => '', 'style'=> 'margin-top:10px']) !!}
        {!! $errors->first('res_options', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div id="resOptionsTextContainer"
     class="form-group {{ $errors->first('res_options_text', 'has-error') || $errors->first('res_options_text_lang2', 'has-error') ? 'has-error': '' }}">
    <label class="col-md-3 control-label">{!! Lang::get('clinics/title.res_options_text') !!}</label>
    <div class="col-md-9">
        {!! Form::text('res_options_text', null, ['class' => 'form-control required', 'maxlength' => 255, 'placeholder' => Lang::get('app/general.lang1')]) !!}
        {!! $errors->first('res_options_text', '<span class="help-block">:message</span>') !!}
        {!! Form::text('res_options_text_lang2', null, ['class' => 'form-control required', 'maxlength' => 255, 'placeholder' => Lang::get('app/general.lang2')]) !!}
        {!! $errors->first('res_options_text_lang2', '<span class="help-block">:message</span>') !!}
    </div>
</div>

@if($isMyClinic)
    {!! Form::hidden('is_enabled', (isset($clinic) && $clinic->is_enabled == 1) ? "1" : "0") !!}
    {!! Form::hidden('days_visible', (isset($clinic) ? $clinic->days_visible : "9")) !!}
    {!! Form::hidden('sort_index', (isset($clinic) ? $clinic->sort_index : "0")) !!}
@else
    <div class="form-group {{ $errors->first('is_enabled', 'has-error') }}">
        {!! Form::label('first_name', Lang::get('clinics/title.is_enabled'), ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-4">
            {!! Form::checkbox('is_enabled', 1, isset($clinic) && $clinic->is_enabled == 1, ['class' => '', 'style'=> 'margin-top:10px']) !!}
            {!! $errors->first('is_enabled', '<span class="help-block">:message</span>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->first('days_visible', 'has-error') }}">
        {!! Form::label('days_visible', Lang::get('clinics/title.days_visible'), ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-3">
            {!! Form::text('days_visible', isset($clinic->days_visible) ? null : '10', ['class' => 'form-control required','maxlength' => 7]) !!}
            {!! $errors->first('days_visible', '<span class="help-block">:message</span>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->first('sort_index', 'has-error') }}" style="display: none">
        {!! Form::label('sort_index', Lang::get('clinics/title.sort_index'), ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-3">
            {!! Form::text('sort_index', '0', ['class' => 'form-control required','maxlength' => 7]) !!}
            {!! $errors->first('sort_index', '<span class="help-block">:message</span>') !!}
        </div>
    </div>
@endif

<div class="form-group">
    <div class="col-md-offset-3 col-md-10">
        {!! Form::submit(Lang::get('button.save'), ['class' => 'btn btn-success', 'style' => 'width: 160px;']) !!}
        <a href="{!! route('admin.clinics.index') !!}" class="btn btn-default"
           style="width: 140px; margin-left: 10px">@lang('button.cancel')</a>
    </div>
</div>

<script>

    // Check the reservation texts container
    var reservationTextsContainerCheck = function () {
        var checked = $("#res_options").is(":checked");
        $("#resOptionsTextContainer")
            .css("display", checked ? "" : "none");
    };

    $(function () {

        reservationTextsContainerCheck();
        $("#res_options")
            .on("change", function () {
                reservationTextsContainerCheck();
            });

    });

</script>
