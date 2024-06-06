<p><?php echo $this->lang->line('metadata_code'); ?>: <strong><?php echo $this->metaRow['META_DATA_CODE']; ?></strong></p>
<p><?php echo $this->lang->line('metadata_name'); ?>: <strong><?php echo $this->metaRow['META_DATA_NAME']; ?></strong></p>

<?php 
echo Form::button(array(
    'class' => 'btn btn-xs green-meadow mb-2',
    'value' => '<i class="icon-plus3 font-size-12"></i> Нөөц үүсгэх',
    'onclick' => 'createConfigBackUp(\'' . $this->metaRow['META_DATA_ID'] . '\');'
)); 
?>

<table class="table table-bordered table-advance table-hover" id="group-backup-list">
    <thead>
        <tr>
            <th style="width: 10px">№</th>
            <th style="width: 72%"><?php echo $this->lang->line('META_00007'); ?></th>
            <th style="width: 23%;"><?php echo $this->lang->line('date'); ?></th>
            <th style="width: 102px; min-width: 102px;"></th>
        </tr>    
    </thead> 
    <tbody>
        <?php
        if ($this->backupList) {
            foreach ($this->backupList as $k => $row) {
        ?>
        <tr>
            <td class="text-center"><?php echo (++$k); ?></td>
            <td class="middle"><?php echo $row['DESCRIPTION']; ?></td>
            <td class="middle"><?php echo $row['CREATED_DATE']; ?></td>
            <td class="text-center middle">
                <a href="javascript:;" class="btn btn-primary btn-xs" onclick="restoreConfigBackUp('<?php echo $row['ID']; ?>');">
                    <i class="far fa-undo"></i> <?php echo $this->lang->line('META_00168'); ?>
                </a>
            </td>
        </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>      

<script type="text/javascript">    
function createConfigBackUp(metaDataId) {
    $.ajax({
        type: 'post',
        url: 'mdmeta/addConfigBackup',
        data: {metaDataId: metaDataId},
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            var $dialogName = 'dialog-create-configbackup-'+metaDataId;
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $("#" + $dialogName);
            
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 500,
                height: 'auto',
                modal: true,
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {
                        $("#create-configbackup-form", "#" + $dialogName).validate({errorPlacement: function () {}});
                        if ($("#create-configbackup-form", "#" + $dialogName).valid()) {
                            $.ajax({
                                type: 'post',
                                url: 'mdmeta/createConfigBackup',
                                data: $("#create-configbackup-form").serialize(),
                                dataType: "json",
                                beforeSend: function () {
                                    Core.blockUI({message: 'Loading...', boxed: true});
                                },
                                success: function (data) {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false
                                    });

                                    if (data.status === 'success') {
                                        groupConfigBackup(metaDataId);
                                    } 

                                    $dialog.dialog('close');
                                    Core.unblockUI();
                                },
                                error: function () { alert("Error"); }
                            });
                        }
                    }}, 
                    {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            
            Core.unblockUI();
        },
        error: function () { alert("Error"); }
    });
}    
function restoreConfigBackUp(id) {
    $.ajax({
        type: 'post',
        url: 'mdmeta/restoreConfigBackUp',
        data: {id: id},
        dataType: "json",
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            PNotify.removeAll();
            new PNotify({
                title: data.status,
                text: data.message,
                type: data.status,
                sticker: false
            });
            Core.unblockUI();
        },
        error: function () { alert("Error"); }
    });
}
</script>