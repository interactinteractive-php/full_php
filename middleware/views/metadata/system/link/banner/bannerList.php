<div class="col-md-12" id="banner-manager">
    <div class="table-toolbar">
        <div class="row">
            <div class="col-md-6">
                <div class="btn-group">
                    <?php echo Form::button(array('class' => 'btn btn-xs green-meadow', 'value' => '<i class="icon-plus3 font-size-12"></i> '.$this->lang->line('META_00103'), 'onclick' => 'addBanner();')); ?>
                </div>
            </div>
        </div>
    </div>
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'process-content-form', 'method' => 'post')); ?>
    <?php echo Form::hidden(array('name' => 'metaDataId', 'value' => $this->metaDataId)); ?>
    <div class="table-scrollable">
        <table class="table table-hover" id="banner-manager-list">
            <thead>
                <tr>
                    <th style="width: 30px;">#</th>
                    <th style="width: 100px;"><?php echo $this->lang->line('META_00149'); ?></th>
                    <th style="width: 250px;"><?php echo $this->lang->line('META_00125'); ?></th>
                    <th style="width: 50px;"><?php echo $this->lang->line('META_00015'); ?></th>
                    <th style="width: 30px;"><?php echo $this->lang->line('META_00080'); ?></th>
                    <th style="width: 250px;"><?php echo $this->lang->line('META_00190'); ?></th>
                    <th style="width: 170px;"><?php echo $this->lang->line('META_00191'); ?></th>
                    <th style="width: 30px;"></th>
                </tr>
            </thead>
            <tbody><?php echo $this->initProcessContent; ?></tbody>
        </table>
    </div>
    <?php echo Form::close(); ?>
</div>
<script type="text/javascript">
    function addBanner() {
        var $dialogName = 'dialog-banner-manager';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }

        $.ajax({
            type: 'post',
            url: 'mdmeta/addProcessContentFrom',
            dataType: "json",
            data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $("#" + $dialogName).empty().html(data.html);
                $("#" + $dialogName).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 850,
                    height: 505,
                    modal: true,
                    close: function () {
                        $("#" + $dialogName).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.add_btn, class: 'btn green-meadow btn-sm', click: function () {
                                var rowNum = parseInt($("table#banner-manager-list tbody tr").length) + 1;
                                var html = '';
                                html += '<tr>';
                                html += '<td>';
                                    html += rowNum;
                                    html += '<input type="hidden" name="contentId[]" value="' + $("#contentId").val() + '">';
                                    html += '<input type="hidden" name="rowId[]" value="' + $("#rowId").val() + '">';
                                html += '</td>';
                                html += '<td>';
                                    html += '<a href="'+ $("#contentFilePath").val() + '" data-fancybox="images"><img src="'+ $("#contentFilePath").val() + '" class="d-block w-auto" alt="img name" style="max-height:45px;"/></a>    ';
                                html += '</td>';
                                html += '<td>' + $("#contentName").val() + '</td>';
                                html += '<td>';
                                html += '<select name="positionType[]" class="form-control select2">';
                                    html += '<option value="">- Сонгох -</option>';
                                    html += '<option value="top"><?php echo $this->lang->line('META_00131') ?></option>';
                                    html += '<option value="right"><?php echo $this->lang->line('META_00055') ?></option>';
                                    html += '<option value="bottom"><?php echo $this->lang->line('META_00054') ?></option>';
                                    html += '<option value="left"><?php echo $this->lang->line('META_00082') ?></option>';
                                html += '</select>';
                                html += '</td>';
                                html += '<td><input type="text" name="orderNum[]" class="form-control longInit"></td>';
                                html += '<td><input type="text" name="webUrl[]" class="form-control"></td>';
                                html += '<td>';
                                html += '<select name="urlTarget[]" class="form-control select2">';
                                html += '<option value="">- Сонгох -</option>';
                                html += '<option value="_blank"><?php echo $this->lang->line('META_00167') ?></option>';
                                html += '<option value="_blank" selected="selected"><?php echo $this->lang->line('META_00016') ?></option>';
                                html += '</select>';
                                html += '</td>';
                                html += '<td>';
                                html += '<a href="javascript:;" class="btn red btn-xs" onclick="deleteProcessContent(this)"><i class="fa fa-trash"></i></a>';
                                html += '</td>';
                                html += '</tr>';
                                $("table#banner-manager-list tbody").append(html).promise().done(function () {
                                    Core.initAjax();
                                    $("#" + $dialogName).dialog('close');
                                });
                            }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
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
    function deleteProcessContent(elem) {
        var _this = $(elem);
        var row = _this.parents('tr');
        var rowId = row.find('input[name="rowId[]"]').val();
        var dialogName = '#deleteConfirm';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        $(dialogName).html('Та устгахдаа итгэлтэй байна уу?');
        $(dialogName).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Сануулах',
            width: '350',
            height: 'auto',
            modal: true,
            buttons: [
                {text: 'Тийм', class: 'btn green-meadow btn-sm', click: function () {
                        $.ajax({
                            type: 'post',
                            url: 'mdmeta/deleteProcessContent',
                            dataType: "json",
                            data: {rowId: rowId},
                            beforeSend: function () {
                                Core.blockUI({
                                    animate: true
                                });
                            },
                            success: function (data) {
                                if (data.status === 'success') {
                                    new PNotify({
                                        title: 'Success',
                                        text: data.message,
                                        type: 'success',
                                        sticker: false
                                    });
                                    row.hide();
                                } else {
                                    new PNotify({
                                        title: 'Error',
                                        text: data.message,
                                        type: 'error',
                                        sticker: false
                                    });
                                }
                                $(dialogName).dialog('close');
                                Core.unblockUI();
                            },
                            error: function () {
                                alert("Error");
                            }
                        }).done(function () {
                            Core.initAjax();
                        });
                        $(dialogName).dialog('close');
                    }},
                {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function () {
                        $(dialogName).dialog('close');
                    }}
            ]
        });
        $(dialogName).dialog('open');

    }
</script>