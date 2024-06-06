<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'temp-folder-form', 'method' => 'post')); ?>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Нэр', 'for' => 'name', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-9">
            <?php 
            echo Form::text(
                array(
                    'name' => 'name', 
                    'id' => 'name', 
                    'class' => 'form-control form-control-sm', 
                    'required' => 'required', 
                    'value' => $this->row['NAME']
                )
            ); 
            ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('item_parent_category'), 'for' => 'parentId', 'class' => 'col-form-label col-md-3')); ?>
        <div class="col-md-9">
            <?php 
            echo Form::select(
                array(
                    'name' => 'parentId', 
                    'id' => 'parentId', 
                    'class' => 'form-control form-control-sm select2', 
                    'data' => $this->folderList, 
                    'op_value' => 'ID', 
                    'op_text' => 'NAME', 
                    'value' => $this->row['PARENT_ID'] 
                )
            ); 
            ?>
        </div>
    </div>

    <div class="tabbable-line">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#edit-rt-permission" class="nav-link active" data-toggle="tab" aria-expanded="true">Засах эрхтэй хэрэглэгчид</a>
            </li>
        </ul>
        <div class="tab-content pb0">
            <div class="tab-pane active" id="edit-rt-permission">
                <?php echo Form::button(array('class' => 'btn btn-sm green-meadow', 'value' => '<i class="fa fa-plus"></i> Хэрэглэгч нэмэх', 'onclick' => 'mdUserSelection();')); ?>
                <div class="mt8">Child бүх загварууд дээр өөрчлөгдөхийг анхаарна уу!</div>
                <table class="table table-bordered table-advance table-hover mt5 mb0" id="rt-user-list">
                    <thead>
                        <tr>
                            <th>Загварын нэр</th>
                            <th>Овог Нэр</th>
                            <th>Хэрэглэгчийн нэр</th>
                            <th style="width: 20px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($this->userList) {
                            foreach ($this->userList as $user) {
                        ?>
                        <tr>
                            <td><input type="hidden" name="rtUserId[]" data-userid="<?php echo $user['USER_ID']; ?>" value="<?php echo $user['USER_ID']; ?>"><?php echo $user['META_DATA_NAME']; ?></td>
                            <td><?php echo $user['LAST_NAME'].' '.$user['FIRST_NAME']; ?></td>
                            <td><?php echo $user['USERNAME']; ?></td>
                            <td class="text-center"><button type="button" class="btn btn-sm red" title="Устгах" onclick="rtUserRemove(this);"><i class="fa fa-trash"></i></button></td>
                        </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
</div>
<?php 
echo Form::hidden(array('name' => 'metaDataId', 'value' => $this->metaDataId));
echo Form::hidden(array('name' => 'folderId', 'value' => $this->folderId));
echo Form::close(); 
?>

<script type="text/javascript">
function mdUserSelection() {
    $.ajax({
        type: 'post',
        url: 'mduser/chooseUserV3',
        data: {chooseMode: 'multi'},
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            var $dialogName = 'dialog-user-list';
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
                width: 920,
                height: 'auto',
                modal: true,
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.addbasket_btn, class: 'btn green-meadow btn-sm float-left', click: function () {
                        basketMdUserV3();
                    }},
                    {text: data.choose_btn, class: 'btn blue btn-sm', click: function () {
                        var rows = $('#mdUserBasketDataGrid').datagrid('getRows');
                        if (rows.length > 0) {
                            
                            var $userTbl = $('#rt-user-list > tbody');
                            
                            for (var i = 0; i < rows.length; i++) {
                                var row = rows[i];
                                
                                if ($userTbl.find('input[data-userid="'+row.USER_ID+'"]').length == 0) {
                                    $userTbl.append('<tr>'+
                                        '<td><input type="hidden" name="rtUserId[]" data-userid="'+row.USER_ID+'" value="'+row.USER_ID+'">'+row.LAST_NAME+'</td>'+
                                        '<td>'+row.FIRST_NAME+'</td>'+
                                        '<td>'+row.USERNAME+'</td>'+
                                        '<td class="text-center"><button type="button" class="btn btn-sm red" title="Устгах" onclick="rtUserRemove(this);"><i class="fa fa-trash"></i></button></td>'+
                                    '</tr>'); 
                                }
                            }
                            
                            $dialog.dialog('close');
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
        error: function () {
            alert('Error');
        }
    });
}
function rtUserRemove(elem) {
    $(elem).closest('tr').remove();
}
</script>