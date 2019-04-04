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
                                        @lang('reservations/res.date')
                                    </th>
                                    <th style="width: 20%">
                                        @lang('reservations/res.name')
                                    </th>
                                    <th style="width: 10%">
                                        @lang('reservations/res.code')
                                    </th>
                                    <th style="width: 10%">
                                        @lang('reservations/res.phone')
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
        $("#clinic_id")
                .select2({
                    theme: "bootstrap",
                    width: "100%",
                    placeholder: "@lang('app/general.select')"
                });
    </script>

@stop