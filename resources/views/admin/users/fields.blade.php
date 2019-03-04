<div class="form-group {{ $errors->first('username', 'has-error') }}">
    {!! Form::label('username', Lang::get('users/title.username'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4">
        @if($isMyUser)
            <h3 style="margin:0px">{!! Form::label('username', $user->username, ['class' => 'label label-info']) !!}</h3>
            {!! Form::hidden('username', (isset($user) ? $user->username : "")) !!}
        @else
            {!! Form::text('username', null, ['class' => 'form-control required']) !!}
            {!! $errors->first('username', '<span class="help-block">:message</span>') !!}
        @endif
    </div>
</div>

<div class="form-group {{ $errors->first('first_name', 'has-error') }}">
    {!! Form::label('first_name', Lang::get('users/title.first_name'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('first_name', null, ['class' => 'form-control required']) !!}
        {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('last_name', 'has-error') }}">
    {!! Form::label('last_name', Lang::get('users/title.last_name'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        {!! Form::text('last_name', null, ['class' => 'form-control required']) !!}
        {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('email', 'has-error') }}">
    {!! Form::label('email', Lang::get('users/title.email'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('email', null, ['class' => 'form-control required']) !!}
        {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('password', 'has-error') }}">
    {!! Form::label('password', Lang::get('users/title.password'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        {!! Form::password('password', array('class'=>'form-control required' )) !!}
        {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->first('password_confirm', 'has-error') }}">
    {!! Form::label('password_confirm', Lang::get('users/title.confirm_password'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3">
        {!! Form::password('password_confirm', array('class'=>'form-control required' )) !!}
        {!! $errors->first('password_confirm', '<span class="help-block">:message</span>') !!}
    </div>
</div>

@if($isMyUser)
    {!! Form::hidden('group_id', (isset($group_id) ? $group_id : "")) !!}
    {!! Form::hidden('clinic_id', (isset($user) ? $user->clinic_id : "")) !!}
    {!! Form::hidden('site_id', (isset($user) ? $user->site_id : "")) !!}
    {!! Form::hidden('is_my_user', "1") !!}
@else

    <div class="form-group {{ $errors->first('group_id', 'has-error') }}">
        <label for="group_id" class="col-md-2 control-label">@lang('users/title.role')</label>

        <div class="col-md-3">
            {!! Form::select('group_id', $groups, isset($group_id) ? $group_id : null, array('id' => 'group_id', 'class'=>'form-control select2' )) !!}
            {!! $errors->first('group_id', '<span class="help-block">:message</span>') !!}
        </div>
    </div>

    <div id="pnlLimitSite" class="form-group {{ $errors->first('site_id', 'has-error') }}">
        <label for="chkLimitSiteId" class="col-md-2 control-label">@lang('users/title.limit_site')</label>

        <div class="col-md-3">
            {!! Form::hidden('site_id', (isset($user) ? $user->site_id : ""), array('id' => 'site_id')) !!}
            <input type="checkbox" class="form-check-input" id="chkLimitSiteId" style="margin-top: 8px"
                    {!! isset($user) && $user->site_id != null ? "checked" : "" !!}>
            {!! Form::select('cbo_site_id', $sites,  isset($user) ? $user->site_id : null, array('id' => 'cbo_site_id', 'class'=>'form-control select2' )) !!}
            {!! $errors->first('site_id', '<span class="help-block">:message</span>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->first('clinic_id', 'has-error') }} clinics_single" style="display: none;">
        <label for="clinic_id" class="col-md-2 control-label">@lang('users/title.clinic_id')</label>

        <div class="col-md-3">
            {!! Form::select('clinic_id', $clinics,  isset($user) ? $user->clinic_id : null, array('id' => 'clinic_id', 'class'=>'form-control select2' )) !!}
            {!! $errors->first('clinic_id', '<span class="help-block">:message</span>') !!}
        </div>
    </div>

    {{--<div class="form-group {{ $errors->first('clinics_ids', 'has-error') }} clinics_multi" style="display: none;">--}}
    {{--<label for="clinics_ids" class="col-md-2 control-label">@lang('users/title.clinics_ids')</label>--}}
    {{--<div class="col-md-4">--}}
    {{--{!! Form::select('clinics_ids', $clinics, null, array('id' => 'clinics_ids', 'name' => 'clinics_ids[]','class'=>'form-control select2', 'multiple'=>"multiple" )) !!}--}}
    {{--{!! $errors->first('clinics_ids', '<span class="help-block">:message</span>') !!}--}}
    {{--</div>--}}
    {{--</div>--}}

    <div class="row form-group clinics_multi" style="display: none;">
        <input type="hidden" id="clinics_ids" name="clinics_ids"
               value="{!! isset($user_clinics) ? join(",", $user_clinics) : null !!}">
        <label for="clinics_ids" class="col-md-2 control-label">@lang('users/title.clinics_ids')</label>
        <div class="col-md-8" style="padding-left: 6px;">
            <div>
                <table id="tblClinics" class="table-striped table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th style="text-align:center; min-width: 50px">
                            <input type="checkbox" id="select-all" name="select-all">
                        </th>
                        <th>@lang('users/title.clinic_id')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clinics as $clinicId => $clinicName)
                        @php
                            $clinicSelected = isset($user_clinics) && in_array($clinicId, $user_clinics);
                        @endphp

                        <tr>
                            <td style="text-align:center">
                                <input type="checkbox" id="chk-{{ $clinicId }}"
                                       name="chk-{{ $clinicId }}" value="{{ $clinicId }}"
                                        {{ $clinicSelected == true ? "checked" : "" }}>
                            </td>
                            <td style="min-width: 90px">
                                <label for="chk-{{ $clinicId }}"
                                       style="font-weight:normal">{{ $clinicName }}</label>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

<div class="form-group">
    <div class="col-md-offset-2 col-md-10">
        {!! Form::submit(Lang::get('button.save'), ['class' => 'btn btn-success', 'style' => 'width: 160px;']) !!}
        <a href="{!! route('admin.users.index') !!}" class="btn btn-default"
           style="width: 140px; margin-left: 10px">@lang('button.cancel')</a>
    </div>
</div>


@if(!$isMyUser)

    <script>
        $(function () {

            $('#select-all').on('change', function () {
                $("#tblClinics")
                    .find("tbody :checkbox")
                    .prop("checked", $('#select-all').is(":checked"));
            });

            var checkControlsVisibility = function () {
                var isSingleClinics = ($("#group_id").val() == {!! \App\Http\Common\ORAConsts::ROLE_USER_SINGLE_ID !!});
                var isMultiClinics = ($("#group_id").val() == {!! \App\Http\Common\ORAConsts::ROLE_USER_MULTI_ID !!});
                var isRoleLimitedAdmin = ($("#group_id").val() == {!! \App\Http\Common\ORAConsts::ROLE_LIMITED_ADMIN_ID !!});
                var isRoleStongAdmin = ($("#group_id").val() == {!! \App\Http\Common\ORAConsts::ROLE_STRONG_ADMIN_ID !!});

                $(".form-group.clinics_single").css("display", isSingleClinics ? "" : "none");
                $(".form-group.clinics_multi").css("display", isMultiClinics ? "" : "none");

                if(isRoleLimitedAdmin || isRoleStongAdmin){
                    $("#pnlLimitSite").css("display", "");
                } else {
                    $("#chkLimitSiteId").prop("checked", false);
                    $("#site_id").val('');
                    $("#pnlLimitSite").css("display", "none");
                }
            };
            checkControlsVisibility();

            var checkSiteVisibility = function () {
                $isSiteLimited = $("#chkLimitSiteId").prop("checked");
                $("#cbo_site_id").css("display", $isSiteLimited ? "block" : "none");
            };
            checkSiteVisibility();

            var checkSite = function () {
                $isSiteLimited = $("#chkLimitSiteId").prop("checked");
                if ($isSiteLimited) {
                    $("#site_id").val($("#cbo_site_id").val());
                } else {
                    $("#site_id").val('');
                }
            };

            var checkControls = function () {
                if ($("#group_id").val() != {!! \App\Http\Common\ORAConsts::ROLE_USER_SINGLE_ID !!}) {
                    $("#clinic_id")
                        .val(null)
                        .trigger("change");
                }

                if ($("#group_id").val() != {!! \App\Http\Common\ORAConsts::ROLE_USER_MULTI_ID !!}) {
                    $("#clinics_ids")
                        .val(null)
                        .trigger("change");
                } else {
                    //Load clinics ids
                    var clinics = [];
                    $("#tblClinics input[type='checkbox']:checked").not("#select-all").each(function () {
                        clinics.push($(this).val());
                    });
                    $('#clinics_ids').val(clinics.join(','));
                }
            };

            $("#chkLimitSiteId")
                .on("change", function () {
                    checkSiteVisibility();
                });

            $("#group_id")
                .on("change", function () {
                    checkControlsVisibility();
                    checkControls();
                });

            $("#group_id, #clinic_id") //, #clinics_ids
                .select2({
                    theme: "bootstrap",
                    width: "100%",
                    placeholder: "@lang('app/general.select')"
                });

            //Set initial selection
            {{--$('#clinics_ids')--}}
                {{--.val({!! isset($user_clinics) ? json_encode($user_clinics) : null !!}).trigger('change');--}}

            $("form")
                .submit(function (e) {
                    checkControls();
                    checkSite();
                    return true;
                });

        });

    </script>

@endif