@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
    @lang('reservations/res.page_title')
    @parent
@stop
{{-- page level styles --}}
@section('header_styles')

    <link href="{{ asset('assets/vendors/x-editable/css/bootstrap-editable.css') }}" type="text/css" rel="stylesheet"/>
    <link href="{{ asset('assets/css/bootstrap-toggle.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet"/>

    <script src="{{ asset('assets/js/jquery.stickytableheaders.min.js') }}" type="text/javascript"></script>

    <style>

        .table-reservations-container {
            height: 74vh;
            overflow: auto;
        }

        .table-reservations thead > tr {
            background-color: #337AB7;
            color: #fff;
        }

        .table-reservations tr > td {
            border-bottom: 1px solid #fff;
        }

        .table-reservations tr > td:first-of-type {
            white-space: nowrap;
        }

        .no-appointments {
            color: #333;
            font-size: 120%;
        }

        .date-row {
            /* border-bottom: 2px solid #555; */
            /* border-top: 2px solid #555; */
        }

        .date-row-normal {
            background: #ddd !important;
            color: #666;
        }

        .date-row-holiday {
            background: #E7AE85 !important;;
        }

        .popover.confirmation {
            max-width: 600px;
            width: auto;
        }

        .form-controls input[type=text] {
            display: inline;
            font-size: 20px;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25em;
            background-color: #5bc0de;
            border-color: #46b8da;
            width: 140px !important;
        }

        .form-controls button:disabled {
            background-color: #5bc0de;
            opacity: 1;
            cursor: default;
        }

        form span.select2-container {
            z-index: inherit;
        }

        .action-btn {
            width: 80px !important;
            padding: 6px 1px !important;
            font-size: 12px;
        }

        .action-label {
            padding: 0px 1px !important;
            margin: 0px !important;
            display: inline-block;
        }

        .action-label .label {
            width: 78px !important;
            font-size: 12px;
            font-weight: normal !important;
            padding: 10px 0px !important;
            display: inline-block;
        }
    </style>
@stop

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <h1>{{ $clinic->name }}
            <small>- @lang('reservations/res.title')</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.reservations') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    @lang('app/general.home')
                </a>
            </li>

            <li class="active">@lang('reservations/res.title')
            </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content paddingleft_right15">
        <div class="row">
            {{--<div class="panel ">--}}
            {{--panel-primary
            <div class="panel-heading">--}}
            {{--<h4 class="panel-title">--}}
            {{--<i class="livicon" data-name="list" data-size="16"--}}
            {{--data-loop="true" data-c="#fff" data-hc="white"></i>--}}
            {{--{{ $clinic->name }}--}}
            {{--</h4>--}}
            {{--</div>--}}
            {{--<br/>--}}

            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        {!! Form::open(['id'=> 'formFilters', 'url' => URL::to('admin/reservations'), 'class' => 'form-inline']) !!}

                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                        @if(!ORAHelper::isSingleClinicUser())
                            <div class="form-group" style="width: 240px; margin-right: 12px">
                                {!! Form::select('clinic_id', $clinics, $clinic->id, array('id' => 'clinic_id', 'class'=>'form-control' )) !!}
                            </div>
                        @endif

                        <div class="form-group form-controls" style="width: 700px">
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button id="btnDatePrev" name="btnDatePrev" type="button" class="btn btn-info"
                                            title="@lang('reservations/res.filter_date_prev')">
                                        <span class="glyphicon glyphicon-arrow-left"></span>
                                    </button>
                                    <button type="button" class="btn btn-info" disabled="disabled">
                                        {{ __('reservations/res.filter_date_from') }}:
                                    </button>
                                </div>
                                <input type="text" class="form-control" value="{{ $dateStartDisplay  }}"
                                       readonly="reaonly">
                            </div>

                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-info" disabled="disabled">
                                        @lang('reservations/res.filter_date_to'):
                                    </button>
                                </div>
                                <input type="text" class="form-control" value="{{ $dateEndDisplay  }}"
                                       readonly="reaonly">
                                <div class="input-group-btn">
                                    <button id="btnDateNext" name="btnDateNext" type="button" class="btn btn-info"
                                            title="@lang('reservations/res.filter_date_next')">
                                        <span class="glyphicon glyphicon-arrow-right"></span>
                                    </button>
                                </div>
                            </div>

                            <input type="hidden" id="dateAction" name="dateAction" value=""/>
                            <input type="hidden" id="dateStart" name="dateStart"
                                   value="{{ $dateStart->format('Y-m-d') }}"/>
                            <input type="hidden" id="dateEnd" name="dateEnd" value="{{ $dateEnd->format('Y-m-d') }}"/>
                        </div>

                        <div class="form-group pull-right">
                            <a href="{{ URL::to('admin/export/index' ) . '/' . $clinic->id }}"
                               class="btn btn-success" style="min-width: 160px">
                                @lang('reservations/res.export')
                            </a>
                        </div>

                        {!! Form::close() !!}

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="margin-top: 10px">
                        <div class="table-responsive table-reservations-container">
                            <table class="table table-striped table-hover table-reservations">
                                <thead>
                                <tr>
                                    <th style="width: 5%;">
                                        @lang('reservations/res.reservation')
                                    </th>
                                    <th style="width: 20%">
                                        @lang('reservations/res.name')
                                    </th>
                                    <th style="width: 10%">
                                        @lang('reservations/res.token')
                                        /<br/>
                                        @lang('reservations/res.code')
                                    </th>
                                    <th style="width: 10%">
                                        @lang('reservations/res.phone')
                                        /<br/>
                                        @lang('reservations/res.gender')
                                    </th>
                                    <th style="width: 5%">
                                        @lang('reservations/res.risk')
                                    </th>
                                    {{--<th style="width: 10%">--}}
                                    {{--@lang('reservations/res.res_option')--}}
                                    {{--/<br />--}}
                                    {{--@lang('reservations/res.line_id')--}}
                                    {{--</th>--}}
                                    <th style="width: 15%; min-width: 220px;">
                                        @lang('reservations/res.actions')
                                    </th>
                                    <th style="width: 10%;">
                                        @lang('reservations/res.status')
                                    </th>
                                    <th style="text-align: center;">
                                        @lang('reservations/res.link')
                                    </th>
                                    <th style="text-align: center;">
                                        @lang('reservations/res.qr_code')
                                    </th>
                                    <th style="width: 10%; min-width: 100px;">
                                        @lang('reservations/res.clinic_code')
                                    </th>
                                    <th style="width: 15%; min-width: 100px;">
                                        @lang('reservations/res.clinic_notes')
                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                @php
                                    function truncate($text, $length) {
                                        $length = abs((int)$length);
                                        $substr = $text;
                                        if(mb_strlen($text, "utf-8") > $length) {
                                            $substr = mb_substr($text, 0, $length, "utf-8");
                                            $substr = htmlspecialchars($substr, ENT_IGNORE, 'UTF-8');
                                            $substr = "<span title='" . $text . "'>" . $substr . "...</span>";
                                        }
                                        return $substr;
                                    }
                                @endphp

                                @foreach($dates as $date)
                                    @php
                                        $reservations = $date["reservations"];
                                    @endphp

                                    {{--Print the date row--}}
                                    <tr class="date-row {{ $date["is_holiday"] == 0 ? "date-row-normal" : "date-row-holiday" }}">
                                        <td>
                                            <strong>{{ $date["display_date"] }}</strong>
                                            {{--@if(count($reservations) == 0)--}}
                                            {{--: --}}
                                            {{--@endif--}}
                                        </td>
                                        <td colspan="10">
                                            @if(count($reservations) == 0)
                                                <span class="no-appointments">
                                                    <span class="glyphicon glyphicon-exclamation-sign"></span>
                                                    @lang("reservations/res.date_no_reservations")
                                                </span>
                                            @endif
                                        </td>
                                    </tr>

                                    @if(count($reservations) > 0)
                                        @foreach($reservations as $res)
                                            {{--Print the reservations--}}
                                            <tr>
                                                <td>
                                                    {{ date("H:i", strtotime($res->res_date)) }}
                                                    &nbsp;
                                                    <button type="button" class="btn btn-sm btn-default"
                                                            data-toggle="modal" data-target="#changeDateModal"
                                                            data-date="{{ $res->res_date }}" data-phone="{{ $res->client_phone_num }}" data-pk="{{ $res->id }}">
                                                        <span class='glyphicon glyphicon-pencil'></span>
                                                    </button>
                                                </td>
                                                <td>
                                                    {!! truncate($res->client_name, 20) !!}
                                                </td>
                                                <td>
                                                    {{ $res->token_num }}
                                                    /<br/>
                                                    {{ $res->res_code_long }}
                                                </td>
                                                <td>
                                                    {!! truncate($res->client_phone_num, 13) !!}
                                                    <br/>
                                                    {{ $res->gender }}
                                                </td>
                                                <td style="text-align:left">
                                                    {{ $res->res_risk_result }}
                                                </td>
                                                <td class="action-col" style="min-width: 350px;">
                                                    @if($reservationActionsDisabled)
                                                        <h3 class="action-label"><span
                                                                    class="label label-{{ $res->is_arrived_btn_class }}">
                                                            {{ $res->is_arrived_btn_label }}
                                                        </span></h3>

                                                        @if(!$reservationStatusHidden)
                                                            <h3 class="action-label"><span
                                                                        class="label label-{{ $res->sti_btn_class }}">
                                                                {{ $res->sti_btn_label }}
                                                            </span></h3>
                                                            <h3 class="action-label"><span
                                                                        class="label label-{{ $res->screened_btn_class }}">
                                                                {{ $res->screened_btn_label }}
                                                            </span></h3>
                                                            <h3 class="action-label"><span
                                                                        class="label label-{{ $res->confirmed_btn_class }}">
                                                                {{ $res->confirmed_btn_label }}
                                                            </span></h3>
                                                        @endif
                                                    @else
                                                        <button data-name="is_arrived"
                                                                data-pk="{{ $res->id }}"
                                                                data-value="{{ $res->is_arrived }}"
                                                                type="button"
                                                                class="action-btn btn btn-{{ $res->is_arrived_btn_class }} btn-md"
                                                                {{ $res->is_arrived_btn_disabled == 1 ? 'disabled' : '' }} >
                                                            {{ $res->is_arrived_btn_label }}
                                                        </button>

                                                        @if(!$changeStatusLimited)
                                                            <button data-name="sti_status"
                                                                    data-pk="{{ $res->id }}"
                                                                    data-value="{{ $res->sti_status }}"
                                                                    type="button"
                                                                    class="action-btn btn btn-{{ $res->sti_btn_class }} btn-md"
                                                                    {{ $res->sti_btn_disabled == 1 ? 'disabled' : '' }} >
                                                                {{ $res->sti_btn_label }}
                                                            </button>

                                                            <button data-name="screened_status"
                                                                    data-pk="{{ $res->id }}"
                                                                    data-value="{{ $res->screened_status }}"
                                                                    type="button"
                                                                    class="action-btn btn btn-{{ $res->screened_btn_class }} btn-md"
                                                                    {{ $res->screened_btn_disabled == 1 ? 'disabled' : '' }} >
                                                                {{ $res->screened_btn_label }}
                                                            </button>

                                                            <button data-name="confirmed_status"
                                                                    data-pk="{{ $res->id }}"
                                                                    data-value="{{ $res->confirmed_status }}"
                                                                    type="button"
                                                                    class="action-btn btn btn-{{ $res->confirmed_btn_class }} btn-md"
                                                                    {{ $res->confirmed_btn_disabled == 1 ? 'disabled' : '' }} >
                                                                {{ $res->confirmed_btn_label }}
                                                            </button>
                                                        @else
                                                            <h3 class="action-label"><span
                                                                        class="label label-{{ $res->sti_btn_class }}">
                                                                {{ $res->sti_btn_label }}
                                                            </span></h3>
                                                            <h3 class="action-label"><span
                                                                        class="label label-{{ $res->screened_btn_class }}">
                                                                {{ $res->screened_btn_label }}
                                                            </span></h3>
                                                            <h3 class="action-label"><span
                                                                        class="label label-{{ $res->confirmed_btn_class }}">
                                                                {{ $res->confirmed_btn_label }}
                                                            </span></h3>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td style="min-width: 120px;">
                                                    @if(!$reservationStatusHidden)
                                                        <span style="color: {!! $res->status_color !!}; font-weight: {!! $res->status_weight !!}"
                                                              id="status_desc_{{ $res->id }}">
                                                        {!! $res->status_desc !!}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td style="min-width: 75px;">
                                                    <a href="#" data-toggle="popover-link" class="btn btn-sm btn-success"
                                                       data-url="{{ \App\Http\Common\ORAHelper::getIndexUrl($res->res_code_long) }}"
                                                       data-placement="left" style="width: 28px; height: 28px; padding: 4px;">
                                                        <span class="fa fa-link fa-lg" aria-hidden="true"></span>
                                                    </a>
                                                    <a href="#" data-toggle="popover-send" class="btn btn-sm btn-primary" data-trigger="focus"
                                                       data-res-id="{{ $res->id }}"
                                                       data-placement="left" style="width: 28px; height: 28px; padding: 4px;">
                                                        <span class="fa fa-share-square fa-lg" aria-hidden="true"></span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="#" data-toggle="popover" data-trigger="focus"
                                                       data-code="{{ $res->res_code_long }}"
                                                       data-placement="left">
                                                        <img src="{{ asset('assets/img/btn_qr.png') }}" width="28"
                                                             height="28" alt="QR"
                                                             style="margin-left: 10px;margin-right: 10px">
                                                    </a>
                                                </td>
                                                <td style="min-width: 100px;">
                                                    <a href="#"
                                                       data-type="text"
                                                       data-pk="{{ $res->id }}"
                                                       data-name="clinic_internal_code"
                                                       data-title="@lang('reservations/res.internal_code_desc')"
                                                       class="clinic-internal-code editable editable-click"
                                                    >{{ $res->clinic_internal_code }}</a>
                                                </td>
                                                <td style="min-width: 100px;">
                                                    <a href="#"
                                                       data-type="textarea" data-rows="2"
                                                       data-pk="{{ $res->id }}"
                                                       data-name="clinic_notes"
                                                       data-title="@lang('reservations/res.comments_desc')"
                                                       class="clinic-notes editable editable-click"
                                                    >{{ $res->clinic_notes }}</a>

                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{--</div>--}}
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="changeDateModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">@lang('reservations/res.popup_title')</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="slotDate">@lang('reservations/res.popup_selectdate'):</label>
                        <input class="form-control" id="slotDate">
                    </div>
                    <div class="form-group">
                        <label for="slotTime">@lang('reservations/res.popup_selectslot'):</label>
                        <select class="form-control" id="slotTime"></select>
                    </div>
                    <div class="form-group">
                        <label for="phoneNumber">@lang('reservations/res.popup_phone'):</label>
                        <input type="number" class="form-control" id="phoneNumber"></input>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript: void(0);" class="text-danger pull-left" onclick="deleteReservation();"
                       style="position: relative; top: 6px;">@lang('reservations/res.popup_delete')</a>
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">@lang('reservations/res.popup_close')</button>
                    <button type="button" class="btn btn-primary"
                            onclick="updateReservationSlot();">@lang('reservations/res.popup_save')</button>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(function () {
            $('#slotDate')
                .daterangepicker({
                    locale: {
                        format: 'DD-MM-YYYY'
                    },
                    singleDatePicker: true,
                    showDropdowns: true
                });
        });

        var createSlots = function (date, currentSlot) {
            @if(!ORAHelper::isSingleClinicUser())
            {!! 'var clinic = $("#clinic_id").val();' !!}
            @else
            {!! 'var clinic = ' . Sentinel::getUser()->clinic_id . ';' !!}
            @endif
            var dateFormat = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
            $.get(
                '{{ route("admin.reservations.slots", ["clinicId" => null, "date" => null, "currentSlot" => null]) }}/' + clinic + "/" + dateFormat + "/" + currentSlot,
                function (response) {
                    // Get the structure
                    var data = JSON.parse(response);

                    // Get the select and fill it
                    var $select = $("#slotTime");
                    $select
                        .attr("disabled", true)
                        .empty();
                    $.each(data, function (index, item) {
                        var time = item.date.split(" ")[1].split(".")[0];
                        var parts = time.split(":");
                        var timeStr = parts[0] + ":" + parts[1];

                        $select
                            .append($('<option>', {
                                value: item.date,
                                text: timeStr
                            }));
                        $select.attr("disabled", false);
                    });
                }
            )
        };

        $('#changeDateModal')
            .on('show.bs.modal', function (e) {
                var $source = $(e.relatedTarget);
                var currentSlot = $source.data('date');
                var date = new Date(currentSlot);
                var phoneNum = $source.data('phone');

                // Set the PK
                $("#slotTime").data("pk", $source.data("pk"));

                //Phone number
                $("#phoneNumber").val(phoneNum);

                // Date picker
                var picker = $('#slotDate').data('daterangepicker');
                picker.setStartDate(date);
                picker.setEndDate(date);

                $('#slotDate')
                    .on("apply.daterangepicker", function () {
                        $("#clinic_id").data("pk", $source.data("pk"));
                        createSlots(picker.startDate.toDate(), '-1');
                    });

                // Create the slots select
                createSlots(date, currentSlot);
            });

        var updateReservationSlot = function () {
            $("#changeDateModal .button-primary")
                .attr("disabled", true);

            $.ajax({
                type: "POST",
                url: '{{ route("admin.reservations.updateslot") }}',
                data: {
                    res_date: $("#slotTime").val(),
                    res_phone: $("#phoneNumber").val(),
                    res_id: $("#slotTime").data("pk")
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function () {
                    $("#changeDateModal").modal("toggle");
                    submitForm();
                },
                error: function (response) {
                    $("#changeDateModal .button-primary")
                        .attr("disabled", false);
                    slotUpdateError(response);
                }
            });
        };

        var deleteReservation = function () {
            function reload() {
                $("#changeDateModal").modal("toggle");
                submitForm();
            };

            function submit() {
                $.ajax({
                    type: "GET",
                    url: '{{ route("admin.reservations.delete", null) }}/' + $("#slotTime").data("pk"),
                    success: function () {
                        $("#changeDateModal").modal("toggle");
                        submitForm();
                    },
                    error: function () {
                        $("#changeDateModal .button-primary")
                            .attr("disabled", false);
                        editableSaveError();
                    }
                });
            }

            eModal.alert({
                title: "@lang('reservations/res.popup_warningtitle')",
                message: "@lang('reservations/res.popup_warningmessage')",
                buttons: [
                    {text: "@lang('reservations/res.popup_yes')", style: 'danger', close: true, click: submit},
                    {text: "@lang('reservations/res.popup_no')", close: true}
                ],
            });
        }

    </script>

@stop

{{-- page level scripts --}}
@section('footer_scripts')

    <script src="{{ asset('assets/vendors/x-editable/js/bootstrap-editable.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-toggle.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/jquery.blockUI.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-confirmation.min.js') }}"></script>

    <script>

        $(function () {
            $('.editable').editable({
                container: 'body',
                url: '{{ route("admin.reservations.update") }}',
                ajaxOptions: {
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                },
                emptytext: "@lang('reservations/res.editable_ph')",
                success: editableSaveSuccess,
                error: editableSaveError
            });

            $("#clinic_id")
                .select2({
                    theme: "bootstrap",
                    width: "100%",
                    placeholder: "@lang('app/general.select')"
                });

            $("#clinic_id").on("change", submitForm);
            $("#btnDatePrev").on("click", function () {
                $("#dateAction").val("P");
                submitForm();
            });
            $("#btnDateNext").on("click", function () {
                $("#dateAction").val("N");
                submitForm();
            });
        });

        submitForm = function () {
            var msg = "<h4>" + "@lang('app/general.loading')" + "</h4>";
            $.blockUI({message: msg, baseZ: 9999});
            $("#formFilters").submit();
        };
        editableSaveSuccess = function (response, newValue) {
            toastr.options.timeOut = 900;
            toastr.options.showDuration = 200;
            toastr.options.hideDuration = 200;
            toastr.success('@lang("app/general.msg_save_success")')
        };
        editableSaveWarning = function (warning) {
            toastr.options.timeOut = 6000;
            toastr.options.showDuration = 200;
            toastr.options.hideDuration = 200;
            toastr.warning('@lang("reservations/res.msg_save_sms_warning")' + '\n' + warning, '@lang("app/general.warning_title")')
        };
        editableSaveError = function (errors) {
            toastr.options.timeOut = 4000;
            toastr.options.showDuration = 200;
            toastr.options.hideDuration = 200;
            toastr.error('@lang("app/general.err_generic")', '@lang("app/general.err_title")')
        };
        slotUpdateError = function (response) {
            toastr.options.timeOut = 4000;
            toastr.options.showDuration = 200;
            toastr.options.hideDuration = 200;
            toastr.error(response.responseJSON.message);
        };



        reservationSaveSuccess = function ($res) {

            if ($res.sms_error == 1) {
                editableSaveWarning($res.sms_error_message);
            } else {
                //Display toast
                editableSaveSuccess();
            }

            @if(!$reservationActionsDisabled)

            //Update the row's buttons according to the new status
            //Set enabled/disabled
            $is_arrived = $('[data-name=is_arrived][data-pk=' + $res.id + ']');
            $sti_status = $('[data-name=sti_status][data-pk=' + $res.id + ']');
            $screened_status = $('[data-name=screened_status][data-pk=' + $res.id + ']');
            $confirmed_status = $('[data-name=confirmed_status][data-pk=' + $res.id + ']');

            $is_arrived.prop('disabled', $res.is_arrived_btn_disabled);
            $sti_status.prop('disabled', $res.sti_btn_disabled);
            $screened_status.prop('disabled', $res.screened_btn_disabled);
            $confirmed_status.prop('disabled', $res.confirmed_btn_disabled);

            //Set color, label and value
            $is_arrived.removeClass('btn-success btn-danger');
            $is_arrived.addClass('btn-' + $res.is_arrived_btn_class);
            $is_arrived.html($res.is_arrived_btn_label);
            $is_arrived.data('value', $res.is_arrived);

            $sti_status.removeClass('btn-success btn-danger');
            $sti_status.addClass('btn-' + $res.sti_btn_class);
            $sti_status.html($res.sti_btn_label);
            $sti_status.data('value', $res.sti_status);

            $screened_status.removeClass('btn-success btn-danger');
            $screened_status.addClass('btn-' + $res.screened_btn_class);
            $screened_status.html($res.screened_btn_label);
            $screened_status.data('value', $res.screened_status);

            $confirmed_status.removeClass('btn-success btn-danger');
            $confirmed_status.addClass('btn-' + $res.confirmed_btn_class);
            $confirmed_status.html($res.confirmed_btn_label);
            $confirmed_status.data('value', $res.confirmed_status);

            $('#status_desc_' + $res.id)
                .html($res.status_desc)
                .css('color', $res.status_color)
                .css('font-weight', $res.status_weight);

            @endif
        };

        var updateReservation = function (name, value, pk) {
            $.ajax({
                type: "POST",
                url: '{{ route("admin.reservations.update") }}',
                data: {
                    name: name,
                    value: value,
                    pk: pk
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: reservationSaveSuccess,
                error: editableSaveError
            });
        };

        $(function () {

            // Activate the popover
            $('[data-toggle=popover]')
                .popover({
                    html: true,
                    content: function () {
                        return "<div style='width:140px'>" + "@lang('app/general.loading')" + "</div>";
                    }
                })
                .on("show.bs.popover", function () {

                    //Close all other popovers
                    $('.popover').popover('hide');

                    // Get the code
                    var code = $(this).data("code");
                    var url = "{{ route('admin.qrcode', null) }}/" + code;

                    $(this).data("bs.popover").tip().css( {
                            "width": "200px",
                            "height": "200px",
                            "text-align": "center",
                            "font-weight": "700"
                    });

                    //Call ajax
                    $.get(url, function (response) {
                        // inject inside the popover
                        $('.popover.in .popover-content')
                            .empty()
                            .append(
                                $("<div>")
                                    .append($("<div>").html(response))
                                    .append($("<div>").html(code))
                            );
                    });
                });

            // Activate the popover
            $('[data-toggle=popover-link]')
                .popover({
                    html: true,
                    title: "@lang('reservations/res.link-title')",
                    content: function () {
                        var url = $(this).data("url");
                        return "<div class='input-group'>" +
                            "<input id='txtIndexUrl' style='width:220px;' class='form-control' readonly value='" + url + "' />" +
                            " <span class='input-group-btn' >" +
                            "  <button id='btnCopyIndexUrl' class='btn btn-sm btn-primary' style='margin-left: 0px; width: 30px; height: 34px; padding: 4px;' onclick='copyTextContents();'>" +
                            "   <span class='fa fa-copy fa-lg' aria-hidden='true'></span>" +
                            "  </button>" +
                            " </span>" +
                            "</div>" ;
                    }
                })
                .on("show.bs.popover", function () {
                    //Close all other popovers
                    $('.popover').popover('hide');
                });

            // Activate the popover
            $('[data-toggle=popover-send]')
                .popover({
                    html: true,
                    title: "@lang('reservations/res.send-link-title')",
                    content: function () {
                        var resId = encodeURIComponent($(this).data("res-id"));
                        return "<div class='input-group'>" +
                            "<button id='btnSendLink' class='btn btn-danger' style='min-width: 120px' onclick='sendLinkSMS(\"" + resId + "\");'>" +
                            "@lang('reservations/res.send-link-button')" +
                            "</button>" +
                            "</div>" ;
                    }
                })
                .on("show.bs.popover", function () {
                    //Close all other popovers
                    $('.popover').popover('hide');
                });

            $('.table-reservations')
                .stickyTableHeaders({scrollableArea: $('.table-reservations-container')});

            @if(!$reservationActionsDisabled)

                $('[data-name=is_arrived]').on("click", function () {
                name = $(this).data("name");
                pk = $(this).data("pk");
                value = $(this).data("value") == "1" ? 0 : 1;

                updateReservation(name, value, pk);
            });


            var buttons = [];
            @for($i = 0; $i < \App\Http\Common\ORAConsts::RES_STI_STATUS_COUNT; $i++)
                buttons.push(
                    {
                        class: 'btn btn-sm btn-primary',
                        value: "{!! $i !!}",
                        label: "@lang("reservations/res.sti_status_$i")"
                    }
                );
            @endfor

            $('[data-name=sti_status]').confirmation({
                rootSelector: '[data-name=sti_status]',
                popout: true, singleton: true, html: true,
                title: '@lang("reservations/res.sti_option")',
                buttons: buttons,
                onConfirm: function(value) {
                    pk = $(this).data("pk");
                    updateReservation("sti_status", value, pk);
                }
            }).on('show.bs.confirmation', function (e) {
                value = $(this).data("value");
                pk = $(this).data("pk");

                //When not empty, simply revert to empty status, no need to show confirmaton options
                if (value != '{{ ORAConsts::RES_STI_STATUS_EMPTY }}') {
                    updateReservation("sti_status", '{{ ORAConsts::RES_STI_STATUS_EMPTY }}', pk);
                    return false;
                }
            });

            $('[data-name=screened_status]').confirmation({
                rootSelector: '[data-name=screened_status]',
                popout: true, singleton: true, html: true,
                title: '@lang("reservations/res.screened_option")',
                buttons: [
                    {
                        label: '@lang("reservations/res.screened_status_negative")',
                        class: 'btn btn-md btn-danger',
                        icon: 'glyphicon glyphicon-remove',
                        onClick: function () {
                            pk = $(this).data("pk");
                            value = '{{ ORAConsts::RES_SCREENED_STATUS_NEGATIVE }}';
                            updateReservation("screened_status", value, pk);
                        }
                    },
                    {
                        label: '@lang("reservations/res.screened_status_positive")',
                        class: 'btn btn-md btn-success',
                        icon: 'glyphicon glyphicon-ok',
                        onClick: function () {
                            pk = $(this).data("pk");
                            value = '{{ ORAConsts::RES_SCREENED_STATUS_POSITIVE }}';
                            updateReservation("screened_status", value, pk);
                        }
                    }
                ]
            }).on('show.bs.confirmation', function (e) {
                value = $(this).data("value");
                pk = $(this).data("pk");

                //When not empty, simply revert to empty status, no need to show confirmaton options
                if (value != '{{ ORAConsts::RES_SCREENED_STATUS_EMPTY }}') {
                    updateReservation("screened_status", '{{ ORAConsts::RES_SCREENED_STATUS_EMPTY }}', pk);
                    return false;
                }
            });

            $('[data-name=confirmed_status]').confirmation({
                rootSelector: '[data-name=confirmed_status]',
                popout: true, singleton: true, html: true,
                title: '@lang("reservations/res.confirmed_option")',
                buttons: [
                    {
                        label: '@lang("reservations/res.confirmed_status_negative")',
                        class: 'btn btn-md btn-danger',
                        icon: 'glyphicon glyphicon-remove',
                        onClick: function () {
                            pk = $(this).data("pk");
                            value = '{{ ORAConsts::RES_CONFIRMED_STATUS_NEGATIVE }}';
                            updateReservation("confirmed_status", value, pk);
                        }
                    },
                    {
                        label: '@lang("reservations/res.confirmed_status_positive")',
                        class: 'btn btn-md btn-success',
                        icon: 'glyphicon glyphicon-ok',
                        onClick: function () {
                            pk = $(this).data("pk");
                            value = '{{ ORAConsts::RES_CONFIRMED_STATUS_POSITIVE }}';
                            updateReservation("confirmed_status", value, pk);
                        }
                    },
                    {
                        label: '@lang("reservations/res.confirmed_status_other")',
                        class: 'btn btn-md btn-warning',
                        icon: 'glyphicon glyphicon-alert',
                        onClick: function () {
                            pk = $(this).data("pk");
                            value = '{{ ORAConsts::RES_CONFIRMED_STATUS_OTHER }}';
                            updateReservation("confirmed_status", value, pk);
                        }
                    }
                ]
            }).on('show.bs.confirmation', function (e) {
                value = $(this).data("value");
                pk = $(this).data("pk");

                //When not empty, simply revert to empty status, no need to show confirmaton options
                if (value != '{{ ORAConsts::RES_CONFIRMED_STATUS_EMPTY}}') {
                    updateReservation("confirmed_status", '{{ ORAConsts::RES_CONFIRMED_STATUS_EMPTY }}', pk);
                    return false;
                }
            });

            @endif
        });

        function copyTextContents() {
            $("#txtIndexUrl").select();
            document.execCommand("copy");
            $("#btnCopyIndexUrl").removeClass('btn-primary').addClass('btn-success');
        }

        function sendLinkSMS(resId) {

            $.ajax({
                type: "POST",
                url: '{{ route("admin.reservations.sendlink") }}',
                data: {
                    resId: resId,
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function () {
                    toastr.options.timeOut = 2000;
                    toastr.options.showDuration = 200;
                    toastr.options.hideDuration = 200;
                    toastr.success('@lang("reservations/res.send-link-success")');
                },
                error: function () {
                    toastr.options.timeOut = 4000;
                    toastr.options.showDuration = 200;
                    toastr.options.hideDuration = 200;
                    toastr.error('@lang("reservations/res.send-link-error")');
                }
            });
        }

    </script>

@stop