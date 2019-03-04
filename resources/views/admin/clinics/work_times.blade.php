<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">
            @lang('clinics/title.work_times_title')
        </h3>
    </div>
    <div class="panel-body">
        <!--main content-->
        <div class="row">

            <div id="grdWorkTimes"></div>

            {{--<table class="table table-striped table-hover">--}}
            {{--<thead>--}}
            {{--<tr>--}}
            {{--<th style="width: 20%;">@lang('clinics/title.work_times_day')</th>--}}
            {{--<th style="width: 20%;">@lang('clinics/title.work_times_start1')</th>--}}
            {{--<th style="width: 20%">@lang('clinics/title.work_times_end1')</th>--}}
            {{--<th style="width: 20%;">@lang('clinics/title.work_times_start2')</th>--}}
            {{--<th style="width: 20%">@lang('clinics/title.work_times_end2')</th>--}}
            {{--</tr>--}}
            {{--</thead>--}}
            {{--<tbody>--}}

            {{--@foreach($workTimes as $workTime)--}}
            {{--<tr>--}}
            {{--<td>@lang("dates.d" . $workTime->day_num)</td>--}}
            {{--<td>{{ $workTime->time_start_1 }}</td>--}}
            {{--<td>{{ $workTime->time_end_1 }}</td>--}}
            {{--<td>{{ $workTime->time_start_2 }}</td>--}}
            {{--<td>{{ $workTime->time_end_2 }}</td>--}}
            {{--</tr>--}}
            {{--@endforeach--}}

            {{--</tbody>--}}
            {{--</table>--}}

            <input type="hidden" id="workTimesData" name="workTimesData" />
        </div>
    </div>
</div>

<script>

    $(function () {
        $("#frmMain").on('submit', function() {
            $("#workTimesData").val(JSON.stringify($("#grdWorkTimes").jsGrid("option", "data" )));
        });

        var workTimes = {!! $workTimes !!};

        var WorkTimeField = function (config) {
            jsGrid.Field.call(this, config);
        };

        WorkTimeField.prototype = new jsGrid.Field({
            css: "date-field",
            align: "center",
            editTemplate: function (value) {
                this._editPicker = $("<input class='form-control' data-mask='99:99'>");
                this._editPicker.val(value);
                return this._editPicker;
            },
            editValue: function () {
                var value = this._editPicker.val();
                if(value ==  "__:__") return "";
                return value;
            }
        });
        jsGrid.fields.work_time = WorkTimeField;

        var DayNumField = function (config) {
            jsGrid.Field.call(this, config);
        };
        DayNumField.prototype = new jsGrid.Field({
            itemTemplate: function (value) {
                return days[value];
            },
            editTemplate: function (value) {
                return days[value];
            }
        });
        jsGrid.fields.day_num = DayNumField;

        var days = ['@lang("dates.d0")', '@lang("dates.d1")', '@lang("dates.d2")', '@lang("dates.d3")', '@lang("dates.d4")', '@lang("dates.d5")', '@lang("dates.d6")'];

        var timeValidation = function (value, item) {
            if(value.length == 0) return true;
            return /^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/.test(value);
        };

        $("#grdWorkTimes").jsGrid({
            height: "auto",
            width: "100%",

            filtering: false,
            paging: false,
            sorting: false,
            autoload: true,
            inserting: false,
            deleting: false,
            editing: true,
            data: workTimes,
            invalidNotify: function (args) {
            },
            fields: [
                {
                    name: "day_num",
                    title: "@lang('clinics/title.work_times_day')",
                    type: "day_num",
                    align: "center",
                    readOnly: true,
                    width: "30%"
                },
                {
                    name: "time_start_1", 
                    title: "@lang('clinics/title.work_times_start1')",
                    type: "work_time", 
                    width: "18%", 
                    validate: [ {
                        validator: "required",
                        message: function (value, item) {
                            return "@lang('clinics/title.grid_required')";
                        }
                    },
                    {
                        validator: timeValidation,
                        message: function (value, item) {
                            return "@lang('clinics/title.grid_required')";
                        }
                    } ]
                },
                {
                    name: "time_end_1", 
                    title: "@lang('clinics/title.work_times_end1')",
                    type: "work_time", 
                    width: "18%", 
                    validate: [ {
                        validator: "required",
                        message: function (value, item) {
                            return "@lang('clinics/title.grid_required')";
                        }
                    },
                    {
                        validator: timeValidation,
                        message: function (value, item) {
                            return "@lang('clinics/title.grid_required')";
                        }
                    } ]
                },
                {
                    name: "time_start_2", title: "@lang('clinics/title.work_times_start2')",
                    type: "work_time", 
                    width: "17%", 
                    validate: {
                        validator: timeValidation,
                        message: function (value, item) {
                            debugger;
                            return "@lang('clinics/title.grid_required')";
                        }
                    }
                },
                {
                    name: "time_end_2", 
                    title: "@lang('clinics/title.work_times_end2')",
                    type: "work_time", 
                    width: "17%", 
                    validate: {
                        validator: timeValidation,
                        message: function (value, item) {
                            return "@lang('clinics/title.grid_required')";
                        }
                    }
                },
                {
                    type: "control",
                    editButton: false,
                    deleteButton: false,
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