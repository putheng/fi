<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            @lang('clinics/title.services_title')
        </h3>
    </div>
    <div class="panel-body">

        <!--main content-->
        <div class="row">
            <div id="grdServices"></div>
            <input type="hidden" id="servicesData" name="servicesData" />
        </div>
    </div>
</div>

<script>

    $(function () {
        $("#frmMain")
            .on('submit', function() {
                var data = $("#grdServices").jsGrid("option", "data");
                $("#servicesData")
                    .val(JSON.stringify(data));
            });

        var services = {!! $services !!};

        $("#grdServices").jsGrid({
            height: "350px",
            width: "100%",

            filtering: false,
            paging: false,
            sorting: false,
            autoload: true,
            confirmDeleting: false,
            inserting: true,
            editing: true,
            data: services,
            invalidNotify: function (args) {
            },
            fields: [
                {
                    name: "service_desc_lang1",
                    title: "@lang('app/general.lang1')",
                    type: "text", 
                    width: "50%",
                    validate: {
                        validator: "required",
                        message: function (value, item) {
                            return "@lang('clinics/title.grid_required')";
                        }
                    }
                },
                {
                    name: "service_desc_lang2",
                    title: "@lang('app/general.lang2')",
                    type: "text", 
                    width: "50%",
                    validate: {
                        validator: "required",
                        message: function (value, item) {
                            return "@lang('clinics/title.grid_required')";
                        }
                    }
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