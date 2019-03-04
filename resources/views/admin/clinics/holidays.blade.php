<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">
            @lang('clinics/title.holidays_title')
        </h3>
    </div>
    <div class="panel-body">
        {{--style="height: 350px; overflow-y: auto"--}}
        <!--main content-->
        <div class="row">
            <div id="grdHolidays"></div>

            {{--<table class="table table-striped table-hover">--}}
            {{--<thead>--}}
            {{--<tr>--}}
            {{--<th style="width: 40%;">@lang('clinics/title.holidays_name')</th>--}}
            {{--<th style="width: 20%;">@lang('clinics/title.holidays_date')</th>--}}
            {{--<th style="width: 10%; text-align: center">@lang('clinics/title.holidays_recurring')</th>--}}
            {{--<th style="width: 10%; text-align: center">@lang('clinics/title.holidays_actions')</th>--}}
            {{--</tr>--}}
            {{--</thead>--}}
            {{--<tbody>--}}

            {{--@foreach($holidays as $holiday)--}}
            {{--<tr>--}}
            {{--<td>{{ $holiday->holiday_name }}</td>--}}
            {{--<td>{{ DateTime::createFromFormat('Y-m-d', $holiday->holiday_date)->format($holiday->is_recurring == 1 ? ('d/m/' . date("Y")) : 'd/m/Y') }}</td>--}}
            {{--<td align="center">--}}
            {{--@if($holiday->is_recurring == 1)--}}
            {{--<img src="{{  asset('assets/img/chk_true32.png') }}" height='16' >--}}
            {{--@endif--}}
            {{--</td>--}}
            {{--<td align="center">--}}
            {{--<a href="#" class="btn btn-default btn-xs red-stripe action-btn">--}}
            {{--<i class="fa fa-edit" style="margin-right: 4px"></i>--}}
            {{--@lang('app/general.delete')--}}
            {{--</a>--}}
            {{--</td>--}}
            {{--</tr>--}}
            {{--@endforeach--}}

            {{--</tbody>--}}
            {{--</table>--}}

            <input type="hidden" id="holidaysData" name="holidaysData" />
        </div>
    </div>
</div>

<script>

    $(function () {
        $("#frmMain").on('submit', function() {
            $("#holidaysData").val(JSON.stringify($("#grdHolidays").jsGrid("option", "data" )));
        });

        var holidays = {!! $holidays !!};

        var HolidayDateField = function (config) {
            jsGrid.Field.call(this, config);
        };

        var holidayDate;
        HolidayDateField.prototype = new jsGrid.Field({
            css: "date-field",            // redefine general property 'css'
            align: "center",              // redefine general property 'align'
            sorter: function (date1, date2) {
                return new Date(date1) - new Date(date2);
            },
            itemTemplate: function (value) {
                return $.datepicker.formatDate('dd-mm-yy', new Date(value));
            },
            insertTemplate: function (value) {
                return this._insertPicker = $("<input>").daterangepicker({
                    singleDatePicker: true,
                    locale: {
                        format: 'DD/MM/YYYY'
                    }
                }, function (start, end) {
                    holidayDate = start;
                });
            },
            editTemplate: function (value) {
                return this._editPicker = $("<input>").daterangepicker({
                    startDate: new Date(value),
                    singleDatePicker: true,
                    locale: {
                        format: 'DD/MM/YYYY'
                    }
                }, function (start, end) {
                    holidayDate = start;
                });
            },
            insertValue: function () {
                var picker = this._insertPicker.data('daterangepicker');
                return picker.startDate.format('YYYY-MM-DD');
            },
            editValue: function () {
                var picker = this._editPicker.data('daterangepicker');
                return picker.startDate.format('YYYY-MM-DD');
            }
        });
        jsGrid.fields.holiday_date = HolidayDateField;


        $("#grdHolidays").jsGrid({
            height: "350px",
            width: "100%",

            filtering: false,
            paging: false,
            sorting: false,
            autoload: true,
            confirmDeleting: false,
            inserting: true,
            editing: true,
            data: holidays,
            invalidNotify: function (args) {
            },
            fields: [
                {
                    name: "holiday_name", title: "@lang('clinics/title.holidays_name')", type: "text", width: "50%",
                    validate: {
                        validator: "required",
                        message: function (value, item) {
                            return "@lang('clinics/title.grid_required')";
                        }
                    }
                },
                {
                    name: "holiday_date",
                    title: "@lang('clinics/title.holidays_date')",
                    type: "holiday_date",
                    width: "30%",
                    validate: {
                        validator: "required",
                        message: function (value, item) {
                            return "@lang('clinics/title.grid_required')";
                        }
                    }
                },
                {
                    name: "is_recurring",
                    title: "@lang('clinics/title.holidays_recurring')",
                    type: "checkbox",
                    width: "20%"
                },
                {
                    type: "control",
                    editButton: false,
                    deleteButton: true,
                    clearFilterButton: false,
                    modeSwitchButton: false,
                    width: "70px",
                    deleteButtonTooltip: "@lang('clinics/title.grid_delete')",
                    updateButtonTooltip: "@lang('clinics/title.grid_update')",
                    cancelEditButtonTooltip: "@lang('clinics/title.grid_cancel')",
                    insertButtonTooltip: "@lang('clinics/title.grid_insert')",
                }
            ]
        });
    });
</script>