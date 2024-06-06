<div class="col-md-12">
    <?php 
    if ($this->metaTypeId === Mdmetadata::$businessProcessMetaTypeId) {
        echo Form::button(
            array(
                'class' => 'btn btn-xs green-meadow', 
                'value' => '<i class="icon-plus3 font-size-12"></i> '.$this->lang->line('META_00103'), 
                'onclick' => 'createProcessDefaultValue(\''.$this->metaDataId.'\', \'list\');'
            )
        ); 
    } 
    ?>
    <div class="table-scrollable overflowYauto" style="max-height: 400px;">
        <table class="table table-bordered table-advance table-hover" id="valueMetaList">
            <thead>
                <tr>
                    <th style="width: 150px;"><?php echo $this->lang->line('META_00075'); ?></th>
                    <th style="width: 60%"><?php echo $this->lang->line('META_00125'); ?></th>
                    <th style="width: 136px;">Үүсгэсэн огноо</th>
                    <th></th>
                </tr>    
            </thead> 
            <tbody>
                <?php
                if ($this->dataList) {
                    foreach ($this->dataList as $row) {
                ?>
                <tr>
                    <td><?php echo $row['PACKAGE_CODE']; ?></td>
                    <td><?php echo $row['PACKAGE_NAME']; ?></td>
                    <td><?php echo $row['CREATED_DATE']; ?></td>
                    <td>
                        <a href="javascript:;" class="btn red btn-xs" onclick="deleteProcessDefaultValue('<?php echo $row['PACKAGE_ID']; ?>', this);"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>  
    </div>    
</div>

<script type="text/javascript">
function deleteProcessDefaultValue(packageId, elem) {
    var $dialogName = 'dialog-confirm';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }

    $.ajax({
        type: 'post',
        url: 'mdcommon/deleteConfirm',
        dataType: "json",
        beforeSend: function() {
            Core.blockUI({
                animate: true
            });
        },
        success: function(data) {
            $("#" + $dialogName).empty().html(data.Html);
            $("#" + $dialogName).dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 330,
                height: "auto",
                modal: true,
                close: function() {
                    $("#" + $dialogName).empty().dialog('close');
                },
                buttons: [
                    {text: data.yes_btn, class: 'btn green-meadow btn-sm', click: function() {
                        if (packageId !== "") {
                            $.ajax({
                                type: 'post',
                                url: 'mdmeta/deleteProcessDefaultValue',
                                data: {packageId: packageId},
                                dataType: "json",
                                beforeSend: function() {
                                    Core.blockUI({
                                        animate: true
                                    });
                                },
                                success: function(dataSub) {
                                    PNotify.removeAll();
                                    if (dataSub.status === 'success') {
                                        new PNotify({
                                            title: 'Success',
                                            text: dataSub.message,
                                            type: 'success',
                                            sticker: false
                                        });
                                        $(elem).closest("tr").remove();
                                    } else {
                                        new PNotify({
                                            title: 'Error',
                                            text: dataSub.message,
                                            type: 'error',
                                            sticker: false
                                        });
                                    }
                                    $("#" + $dialogName).dialog('close');
                                    Core.unblockUI();
                                },
                                error: function() {
                                    alert("Error");
                                }
                            });
                        }
                    }},
                    {text: data.no_btn, class: 'btn blue-madison btn-sm', click: function() {
                        $("#" + $dialogName).dialog('close');
                    }}
                ]
            });
            $("#" + $dialogName).dialog('open');
            Core.unblockUI();
        },
        error: function() {
            alert("Error");
        }
    }).done(function() {
        Core.initAjax($("#" + $dialogName));
    });
}    
</script>