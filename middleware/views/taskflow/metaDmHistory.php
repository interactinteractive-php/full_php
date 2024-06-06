<div class="col-md-12" id="bp-history-list">
    <div class="tabbable-line">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#task-flow-log" class="nav-link active" data-toggle="tab" aria-expanded="false">Log</a>
            </li>
            <li class="nav-item">
                <a href="#task-flow-statistic" data-toggle="tab" aria-expanded="false" class="nav-link">Statistic</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="task-flow-log">
                <table id="bp-history-grid"></table>
            </div>
            <div class="tab-pane" id="task-flow-statistic">
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    var historyWindowId = "#bp-history-list";
    $(function () {
        var historyDataGrid = $('#bp-history-grid');
        historyDataGrid.datagrid({
            url: 'mdtaskflow/getProcessHistoryList',
            queryParams: {lifeCycleDtlId: '<?php echo $this->lifeCycleDtlId; ?>', sourceId: '<?php echo $this->sourceId; ?>'},
            rownumbers: true,
            singleSelect: true,
            ctrlSelect: true,
            pagination: true,
            pageSize: 10,
            width: 600,
            height: 300,
            fitColumn: true,
            nowrap: false,
            striped: true,
            columns: [[
                    {field: 'COMMAND_NAME', title: 'Команд', sortable: true, halign: 'center', align: 'left'},
                    {field: 'USER_NAME', title: 'Хэрэглэгчийн нэр', sortable: true, halign: 'center', align: 'left'},
                    {field: 'IP_ADDRESS', title: 'IP хаяг', sortable: true, halign: 'center', align: 'left', width: 150},
                    {field: 'ACTION_DATE', title: 'Огноо', sortable: true, halign: 'center', align: 'left'}
                ]],
            onLoadSuccess: function () {
                showGridMessage($(this));
            },
            onRowContextMenu: function (e, index, row) {
                e.preventDefault();
                $(this).datagrid('selectRow', index);
                var rowData = $(this).datagrid('getSelected');
                $.contextMenu({
                    selector: historyWindowId + " .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
                    callback: function (key, opt) {
                        if (key === 'input') {
                            inputOutputParameter(rowData, 'input');
                        }
                        if (key === 'output') {
                            inputOutputParameter(rowData, 'output');
                        }
                    },
                    items: {
                        "input": {name: "Оролтын параметр", icon: "download"},
                        "output": {name: "Гаралтын параметр", icon: "upload"}
                    }
                });
            }
        });
    });

    function inputOutputParameter(elem, type) {
        var $dialogName = 'dialog-confirm';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }

        $.ajax({
            type: 'post',
            url: 'mdtaskflow/inputOutputHistory',
            data: {id: elem.ID, type: type},
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $("#" + $dialogName).empty().html(data.Html);
                $("#" + $dialogName).dialog({
                    close: function () {
                        $("#" + $dialogName).empty().dialog('destroy');
                    },
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 800,
                    height: 600,
                    modal: true,
                    close: function () {
                        $("#" + $dialogName).empty().dialog('close');
                    },
                            buttons: [
                                {text: data.close_btn, class: 'btn grey-cascade btn-sm', click: function () {
                                        $("#" + $dialogName).dialog('close');
                                    }}
                            ]
                });
                $("#" + $dialogName).dialog('open');
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initAjax();
        });


    }
</script>