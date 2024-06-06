<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="row-fluid">
    <div class="span12">
        <div class="clearfix w-100 mb10 hidden-print">
            <div class="btn-group float-right view-controller" data-toggle="buttons-radio">
                <?php echo Form::button(array('class'=>'btn mini active','onclick'=>'folderView(\'listView\');','value'=>'<i class="icon-reorder"></i> '.$this->lang->line("list_view"))); ?>
                <?php echo Form::button(array('class'=>'btn mini','onclick'=>'folderView(\'schemaView\');','value'=>'<i class="icon-sitemap"></i> '.$this->lang->line("card_view"))); ?>
            </div>
        </div>
        
        <div class="table-scrollable no-border folderRender"></div>
        
    </div>
</div>

<script type="text/javascript">
function folderView(type)
{
    $.ajax({
        type: 'post',
        url: 'mdfolder/'+type,
        beforeSend: function(){
          App.blockUI(".folderRender");  
        },
        success: function(data){ 
            $(".folderRender").empty().append(data);  
            App.unblockUI(".folderRender");
        }
    });
}
    folderView('schemaView');

    $(".addFolder").live("click", function(){
         var $dialogName = 'dialog-folder';
         var element = ".table-scrollable";
         $.ajax({
            type: 'post',
            url: 'mdfolder/addFolder',
            beforeSend:function(){
                App.blockUI(element);
            }, 
            success:function(data){
                $("#"+$dialogName).empty().html(data);  
                $("#"+$dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: "<?php echo $this->lang->line('add_folder'); ?>",
                    width: 600,
                    height: "auto",
                    modal: true,
                    open: function(){
                        $('div[aria-describedby='+$dialogName+'] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn green');
                        $('div[aria-describedby='+$dialogName+'] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn black');
                    },        
                    close:function(){
                        $("#"+$dialogName).empty().dialog('destroy');
                    }, 
                    buttons: [
                        {text: plang.get('save_btn'), click: function(){
                            $("#addFolder-form").validate({ 
                                ignore: "", 
                                highlight: function(label) {
                                    $(label).closest('.control-group').addClass('error');
                         
                                },
                                unhighlight: function(label) {
                                    $(label).closest('.control-group').removeClass('error');
                                },
                                errorPlacement: function(){} 
                            });
        
                            if ($("#addFolder-form").valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: 'mdfolder/createFolder',
                                    data: $("#addFolder-form").serialize(),
                                    dataType: "json",
                                    beforeSend: function(){
                                        //show_msg_saving_block();
                                    },
                                    success: function(data) {
                                        $.unblockUI();
                                        if (data.status === 'success') {
                                            $.pnotify({
                                                title: 'Success',
                                                text: data.message,
                                                type: 'success',
                                                sticker: false
                                            });
                                            $("#"+$dialogName).dialog('close');
                                            $('#folderTreeGrid').treegrid('reload'); 
                                        } else {
                                            $.pnotify({
                                                title: 'Error',
                                                text: data.message,
                                                type: 'error',
                                                sticker: false
                                            });
                                        }
                                    },
                                    error: function() {
                                        alert("Error");
                                    }
                                });
                            }
                        }},
                        {text: plang.get('close_btn'), click: function(){
                            $("#"+$dialogName).dialog('close');
                        }}
                    ]        
                });
                $("#"+$dialogName).dialog('open');
                App.unblockUI(element);
            },
            error:function(){
                alert("Error");
            }
        });
    });
    
    $(".editFolder").live("click", function(){
        var row = $('#folderTreeGrid').treegrid('getSelected');
        if (row) {
        var errorMsg = '<?php echo $this->lang->line('unavailable_for_edit');?>';
        $.ajax({
        type: 'post',
        url: 'mdfolder/checkDynamic',
        data:{ folderId: row.id},
        success: function(res) {
            if (res === '{"status":"error"}') {
                $.pnotify({
                    title: 'Error',
                    text: errorMsg,
                    type: 'error',
                    sticker: false
                });
            } else {
            var $dialogName = 'dialog-folder';
            var element = $(this);
            $.ajax({
                type: 'post',
                url: 'mdfolder/editFolder',
                data:{ folderId: row.id },
                success:function(data){
                    $("#"+$dialogName).empty().html(data);  
                    $("#"+$dialogName).dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: "<?php echo $this->lang->line('edit_folder'); ?>",
                        width: 600,
                        height: "auto",
                        modal: true,
                        open: function(){
                            $('div[aria-describedby='+$dialogName+'] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn green');
                            $('div[aria-describedby='+$dialogName+'] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn black');
                        },        
                        close:function(){
                            $("#"+$dialogName).empty().dialog('destroy');
                        }, 
                        buttons: [
                            {text: plang.get('save_btn'), click: function(){
                                $("#editFolder-form").validate({ 
                                    ignore: "", 
                                    highlight: function(label) {
                                        $(label).closest('.control-group').addClass('error');
                                    },
                                    unhighlight: function(label) {
                                        $(label).closest('.control-group').removeClass('error');
                                    },
                                    errorPlacement: function(){} 
                                });

                                if ($("#editFolder-form").valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdfolder/updateFolder',
                                        data: $("#editFolder-form").serialize(),
                                        dataType: "json",
                                        beforeSend: function(){
                                            show_msg_saving_block();
                                        },
                                        success: function(data) {
                                            $.unblockUI();
                                            if (data.status === 'success') {
                                                $.pnotify({
                                                    title: 'Success',
                                                    text: data.message,
                                                    type: 'success',
                                                    sticker: false
                                                });
                                                $("#"+$dialogName).dialog('close');
                                                $('#folderTreeGrid').treegrid('reload'); 
                                            } else {
                                                $.pnotify({
                                                    title: 'Error',
                                                    text: data.message,
                                                    type: 'error',
                                                    sticker: false
                                                });
                                            }
                                        },
                                        error: function() {
                                            alert("Error");
                                        }
                                    });
                                }
                            }},
                            {text: plang.get('close_btn'), click: function(){
                                $("#"+$dialogName).dialog('close');
                            }}
                        ]        
                    });
                    $("#"+$dialogName).dialog('open');
                    App.unblockUI(element);
                },
                error:function(){
                    alert("Error");
                }
            });              
          }
        },
        error: function() {
            alert("Error");
        }            
        });                   
        } else {
            alert("<?php echo $this->lang->line('msg_pls_list_select'); ?>");
        }
    } );
    
    $(".checkFolder").live("click", function(){
        var row = $('#folderTreeGrid').treegrid('getSelected');   
        if (row) {
            var $dialogName = 'dialog-check';
            var element = $(this);
            $.ajax({
                type: 'post',
                url: 'mdfolder/checkFolder',
                data:{ folderId: row.id },
                dataType: 'json',
                beforeSend:function(){
                    App.blockUI(element);
                },
                success:function(res){
                    App.unblockUI(element);
                    if (res.status === 'success') {
                        deleteFolder();
                    } else {
                        App.unblockUI(element);
                        $("#"+$dialogName).dialog({
                        cache: false,
                        resizable: true,
                        autoOpen: false,
                        width: 900,   
                        height: "auto",
                        data : res.usetables,
                        open: function(){
                            $('div[aria-describedby='+$dialogName+'] .ui-dialog-content').text(res.usetables);
                            $('div[aria-describedby='+$dialogName+'] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn black');
                        },        
                        close:function(){
                            $("#"+$dialogName).empty().dialog('destroy');
                        }, 
                        buttons: [
                            {text: plang.get('close_btn'), click: function(){
                                $("#"+$dialogName).dialog('close');
                            }}
                        ]        
                    });
                    $("#"+$dialogName).dialog('open');                  
                    }

                },
                error:function(){
                    alert("Error");
                }
            });
        
        } else {
            alert("<?php echo $this->lang->line('msg_pls_list_select'); ?>");
        }
    } );  
    
    function deleteFolder(){  
        var row = $('#folderTreeGrid').treegrid('getSelected');
        if (row) {  
            $("#dialog-confirm").dialog({
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: plang.get('msg_title_confirm'),
                width: 400,
                height: 'auto',
                modal: true,   
                position: 'center',
                open: function() {
                    $('.ui-dialog-buttonset').find('button:eq(0)').addClass('btn blue');
                    $('.ui-dialog-buttonset').find('button:eq(1)').addClass('btn');
                },
                buttons: [
                    {text: plang.get('yes_btn'), click: function(){
                        $.ajax({
                            type: 'post',
                            url: 'mdfolder/deleteFolder',
                            data:{folderId:row.id},
                            dataType: "json",
                            beforeSend: function(){
                                App.blockUI(".table-scrollable");
                            },
                            success: function(data) {
                                $.unblockUI();
                                if (data.status === 'success') {
                                    $.pnotify({
                                        title: 'Success',
                                        text: data.message,
                                        type: 'success',
                                        sticker: false
                                    }); 
                                 $('#folderTreeGrid').treegrid('reload'); 
                                } else {
                                    $.pnotify({
                                        title: 'Error',
                                        text: data.message,
                                        type: 'error',
                                        sticker: false
                                    });
                                }
                                App.unblockUI(".table-scrollable");
                            },
                            error: function() {
                                alert("Error");
                            }
                        });
                        $("#dialog-confirm").dialog('close');
                    }},
                    {text: plang.get('no_btn'), click: function(){
                        $("#dialog-confirm").dialog('close');
                    }}    
                ]    
            });
            $("#dialog-confirm").html(plang.get('msg_delete_confirm')).dialog('open');
        } else {
            alert("<?php echo $this->lang->line('msg_pls_list_select'); ?>");
        }
    }   
</script>    

