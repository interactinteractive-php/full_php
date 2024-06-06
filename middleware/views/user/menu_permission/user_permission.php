<div id="user_permission_section">
    <div class="tabbable-line">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#userPermision" class="nav-link active" data-toggle="tab">Эрхтэй хэрэглэгчийн жагсаалт</a>
            </li>
        </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane active in" id="userPermision">
            <br/>
            <div class="table-toolbar">
                <div class="row">
                    <div class="col-md-9">
                        <div class="btn-group btn-group-devided">
                            <a class="btn btn-secondary btn-circle btn-sm"  href="javascript:;" onclick="addPermission('<?php echo $this->metaDataId ?>');"><i class="icon-plus3 font-size-12"></i> Нэмэх</a>
                            <a class="btn btn-secondary btn-circle btn-sm"  href="javascript:;" onclick="editPermission('<?php echo $this->metaDataId ?>');"><i class="fa fa-edit"></i> Засах</a>
                            <a class="btn btn-secondary btn-circle btn-sm"  href="javascript:;" onclick="removePermission('<?php echo $this->metaDataId ?>');"><i class="fa fa-trash-o"></i> Устгах</a>
                        </div>                            
                    </div>
                    <div class="col-md-3 text-right">
                        <div class="btn-group btn-group-circle">
                            <?php
                            echo Html::anchor(
                                'javascript:;', '<i class="fa  fa-filter"></i>', array(
                                'class' => 'btn btn-secondary btn-sm active permissionListFilter',
                                'title' => 'Filter column'
                                    ), true
                            );
                            echo Html::anchor(
                                'javascript:;', '<i class="fa fa-columns"></i>', array(
                                'class' => 'btn btn-secondary btn-sm permissionListMerge',
                                'title' => 'Merge cell'
                                    ), true
                            );
                            ?>
                        </div>
                    </div>
                </div>
            </div>    
            <div class="row">
                <div class="jeasyuiTheme3">
                    <table id="userPermissionList"></table>
                </div>   
            </div>
        </div>
    </div>     
</div>
<script type="text/javascript">
    var metaTypeName = '<?php echo $this->metaType['META_TYPE_NAME']?>';
    $(function() {
        $("#userPermissionList").datagrid({
            view: horizonscrollview,
            url: 'mduser/userPermissionDataGridOnMain/' + '<?php echo $this->metaDataId ?>',
            rownumbers: true,
            singleSelect: true,
            pagination: true,
            pageSize: 10,
            striped: true,
            showFooter: true,
            remoteFilter: true,
            filterDelay: 10000000000,           
            columns: [[
                    {field: 'META_DATA_CODE', title: metaTypeName +' код', sortable: true, halign: 'center', align: 'left', width: 150},
                    {field: 'META_DATA_NAME', title: metaTypeName +' нэр', sortable: true, halign: 'center', align: 'left', width: 200},
                    {field: 'USERNAME', title: 'Хэрэглэгч', sortable: true, halign: 'center', align: 'left', width: 100},
                    {field: 'ROLE_CODE', title: 'Роле код', sortable: true, halign: 'center', align: 'left', width: 100},
                    {field: 'ROLE_NAME', title: 'Роле нэр', sortable: true, halign: 'center', align: 'center', width: 200},
                    {field: 'GROUP_CODE', title: 'Групп код', sortable: true, halign: 'center', align: 'left', width: 100},
                    {field: 'GROUP_NAME', title: 'Групп нэр', sortable: true, halign: 'center', align: 'center', width: 200},
                    {field: 'ACTION_CODE', title: 'Action код', sortable: true, halign: 'center', align: 'right', width: 100},
                    {field: 'ACTION_NAME', title: 'Action нэр', sortable: true, halign: 'center', align: 'right', width: 100},
                    {field: 'FIELD_CRITERIA', title: '', sortable: true, align: 'center', width: 100, formatter: function(v, r, i) {
                            var fieldCriteria = r.FIELD_CRITERIA === null ? '' : r.FIELD_CRITERIA ;
                            var recordCriteria = r.RECORD_CRITERIA === null ? '' : r.RECORD_CRITERIA ;
                        return '<button class="btn btn-sm purple-plum" title="view criteria" onclick="viewCriteria(\'' + fieldCriteria + '\', \'' + recordCriteria + '\');">...</button>';
                    }}
                ]],
            onLoadSuccess: function() {
                showGridMessage($("#userPermissionList"));
            }
        });
        $("#userPermissionList").datagrid('enableFilter');
        $(".permissionListFilter").on("click", function() {
            var filterBtn = $(this);
            if (filterBtn.hasClass("active")) {
                $("#userPermissionList").datagrid('disableFilter');
                filterBtn.removeClass("active");
            } else {
                $("#userPermissionList").datagrid('enableFilter');
                filterBtn.addClass("active");
            }
        });
        $(".permissionListMerge").on("click", function() {
            var mergeBtn = $(this);
            if (mergeBtn.hasClass("active")) {
                $("#userPermissionList").datagrid('reload');
                mergeBtn.removeClass("active");
            } else {
                $("#userPermissionList").datagrid("autoMergeCells");
                mergeBtn.addClass("active");
            }
        });
    });

    function viewCriteria(fieldCriteria, recordCriteria){
      var $dialogName = 'dialog-viewCriteria';
        if (!$($dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: 'mduser/viewCriteria',
            data: {fieldCriteria: fieldCriteria, recordCriteria: recordCriteria},
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {
                $("#" + $dialogName).empty().html(data.Html);
                var dialog = $("#" + $dialogName).dialog({
                    appendTo: "body",
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 900,
                    minWidth: 900,
                    height: 500,
                    modal: false,
                    buttons: [
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                                dialog.empty().dialog('close');
                                dialog.dialog('destroy').remove();
                            }}
                    ]
                });
                dialog.dialog('open');
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
            }
        }).done(function() {
            Core.initAjax();
        });  
    }
    function addPermission(id) {
        var $dialogName = 'dialog-addPermission';
        if (!$($dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: 'mduser/addPermission',
            data: {metaDataId: id},
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {
                $("#" + $dialogName).empty().html(data.Html);
                var dialog = $("#" + $dialogName).dialog({
                    appendTo: "body",
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 900,
                    minWidth: 900,
                    height: 650,
                    modal: false,
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm blue-hoki', click: function() {
                                $("#addPermission-form").validate({
                                    ignore: "",
                                    highlight: function(label) {   
                                        $(label).closest('.control-group').addClass('error');

                                    },
                                    unhighlight: function(label) {
                                        $(label).closest('.control-group').removeClass('error');
                                    },
                                    errorPlacement: function() {
                                    }
                                });
                                if ($("#addPermission-form").valid()) {
                                    addfieldCriteriaEditor.save();
                                    addrecordCriteriaEditor.save();
                                    $.ajax({
                                        type: 'post',
                                        url: 'mduser/createPermission',
                                        data: $("#addPermission-form").serialize(),
                                        dataType: "json",
                                        beforeSend: function() {
                                            Core.blockUI({
                                                animate: true
                                            });
                                        },
                                        success: function(data) {
                                            Core.unblockUI();
                                            if(data.status === 'success'){
                                                new PNotify({
                                                    title: 'Success',
                                                    text: data.result,
                                                    type: 'success',
                                                    sticker: false
                                                });
                                            }else{
                                                new PNotify({
                                                    title: 'Error',
                                                    text: data.text,
                                                    type: 'error',
                                                    sticker: false
                                                });
                                            }
                                            $('#userPermissionList').datagrid('reload'); 
                                        },
                                        error: function() {
                                            alert("Error");
                                        }
                                    });
                                }
                                dialog.empty().dialog('close');
                                dialog.dialog('destroy').remove();
                            }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                                dialog.empty().dialog('close');
                                dialog.dialog('destroy').remove();
                            }}
                    ]
                });
                dialog.dialog('open');
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
            }
        }).done(function() {
            Core.initAjax();
        });
    }
    function editPermission(id){
        var row = $('#userPermissionList').datagrid('getSelected');   
        if (row) {
        var $dialogName = 'dialog-editPermission';
        if (!$($dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: 'mduser/editPermission',
            data: {metaDataId: id, permissionId : row.PERMISSION_ID},
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {
                $("#" + $dialogName).empty().html(data.Html);
                var dialog = $("#" + $dialogName).dialog({
                    appendTo: "body",
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 900,
                    minWidth: 900,
                    height: 650,
                    modal: false,
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm blue-hoki', click: function() {
                                $("#editPermission-form").validate({
                                    ignore: "",
                                    highlight: function(label) {   
                                        $(label).closest('.control-group').addClass('error');

                                    },
                                    unhighlight: function(label) {
                                        $(label).closest('.control-group').removeClass('error');
                                    },
                                    errorPlacement: function() {
                                    }
                                });
                                if ($("#editPermission-form").valid()) {
                                    editfieldCriteriaEditor.save();
                                    editrecordCriteriaEditor.save();
                                    $.ajax({
                                        type: 'post',
                                        url: 'mduser/updatePermission',
                                        data: $("#editPermission-form").serialize(),
                                        dataType: "json",
                                        beforeSend: function() {
                                                    Core.blockUI({
                                                        animate: true
                                                    });
                                                },
                                                success: function(data) {
                                                    Core.unblockUI();
                                                    if (data.status === 'success') {
                                                        new PNotify({
                                                            title: 'Success',
                                                            text: data.result,
                                                            type: 'success',
                                                            sticker: false
                                                        });
                                                    } else {
                                                        new PNotify({
                                                            title: 'Error',
                                                            text: data.text,
                                                            type: 'error',
                                                            sticker: false
                                                        });
                                                    }
                                                    $('#userPermissionList').datagrid('reload');
                                                },
                                                error: function() {
                                                    alert("Error");
                                                }
                                            });
                                        }
                                        dialog.empty().dialog('close');
                                        dialog.dialog('destroy').remove();
                                    }},
                                {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                                        dialog.empty().dialog('close');
                                        dialog.dialog('destroy').remove();
                                    }}
                            ]
                        });
                        dialog.dialog('open');
                        Core.unblockUI();
                    },
                    error: function() {
                        alert("Error");
                    }
                }).done(function() {
                    Core.initAjax();
                });
        } else {
            alert("please select row");
        }
    }
    function removePermission(id){
        var dialogName = '#removeDialog';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        var row = $('#userPermissionList').datagrid('getSelected');  
        if (row) {
            $(dialogName).html('<?php echo $this->lang->line('msg_delete_confirm'); ?>').dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                width: 'auto',
                height: 'auto',
                modal: true,
                buttons: [
                    {text: '<?php echo $this->lang->line('yes_btn'); ?>', class: 'btn', click: function() {
                            $.ajax({
                                type: 'post',
                                url: 'mduser/deletePermission',
                                data: {permissionId: row.PERMISSION_ID, metaDataId : id},
                                dataType: "json",
                                success: function(data) {
                                    if (data.status === 'success') {
                                        new PNotify({
                                            title: 'Success',
                                            text: 'Амжилттай устгагдлаа',
                                            type: 'success',
                                            sticker: false
                                        });
                                        $('#userPermissionList').datagrid('reload'); 
                                    } else {
                                        new PNotify({
                                            title: 'Error',
                                            text: 'Амжилтгүй боллоо',
                                            type: 'error',
                                            sticker: false
                                        });
                                    }
                                },
                                error: function() {
                                    alert("Error");
                                }
                            });
                            $(dialogName).dialog('close');
                        }},
                    {text: '<?php echo $this->lang->line('no_btn'); ?>', class: 'btn', click: function() {
                            $(dialogName).dialog('close');
                        }}]
            }).dialog('open');
        } else {
                alert("<?php echo $this->lang->line('msg_pls_list_select'); ?>");
        }
    }
</script>    
