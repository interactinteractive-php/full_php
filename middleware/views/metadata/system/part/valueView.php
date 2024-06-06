<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal xs-form', 'id' => 'metaValue-form', 'method' => 'post')); ?>
<div class="row-fluid">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>$this->lang->line('metadata_name'), 'for'=>'META_DATA_NAME', 'class'=>'col-form-label col-md-3')); ?>
        <div class="col-md-9">
            <p class="form-control-plaintext font-weight-bold"><?php echo $this->row['META_DATA_NAME']; ?></p>
        </div>
    </div>
</div>  
<div class="table-scrollable rowMetaValues-scroll">
    <table class="table table-bordered table-advance table-hover table-striped" id="valueList">
        <thead>
            <tr>
                <th class="text-center">№</th>
                <th><?php echo $this->lang->line('metadata_value_code'); ?></th>
                <th><?php echo $this->lang->line('metadata_value_name'); ?></th>
                <th class="filter-false"><?php echo $this->lang->line('metadata_value_parent'); ?> /Child/</th>
                <th class="filter-false"><?php echo $this->lang->line('metadata_value_parented'); ?> /Parent/</th>
            </tr>    
        </thead> 
        <tbody>
            <?php
            if ($this->valueList) {
                foreach ($this->valueList as $val) {
            ?>
            <tr id="<?php echo $val['META_VALUE_ID']; ?>">
                <td class="text-center valueRelate"></td>
                <td class="valueRelate"><?php echo $val['META_VALUE_CODE']; ?></td>
                <td class="valueRelate"><?php echo $val['META_VALUE_NAME']; ?></td>
                <td class="valueRelateChild"><?php echo Mdmetadata_Model::relateValues($val['META_VALUE_ID']); ?></td>
                <td class="valueRelateParent"><?php echo Mdmetadata_Model::relatedValues($val['META_VALUE_ID']); ?></td>
            </tr> 
            <?php
                }
            }
            ?>
        </tbody> 
    </table>
</div>    
<?php echo Form::close(); ?>

<style type="text/css">  
.rowMetaValues-scroll {
    position: relative;
    overflow-y: auto;
    height: 450px;
}
</style>

<script type="text/javascript">
$(function(){
    
    $.tablesorter.addWidget({
       id: "numbering",
       format: function(table) {
           $("tr", table.tBodies[0]).each(function(i) {
               $(this).find("td").eq(0).text(i + 1 + ".");
           });
       }
    }); 
    $("#valueList").tablesorter({
        theme: "bootstrap",
        cssInfoBlock: "tablesorter-no-sort",
        widthFixed: true,
        sortMultiSortKey: 'altKey',
        headerTemplate: "{content} {icon}",
        widgets: ["uitheme", "filter", "numbering", "stickyHeaders"],
        widgetOptions: {
           filter_reset : ".reset", 
           stickyHeaders : '',
           stickyHeaders_offset : 0,
           stickyHeaders_cloneId : '-sticky',
           stickyHeaders_addResizeEvent : true,
           stickyHeaders_zIndex : 2,
           stickyHeaders_attachTo : '.rowMetaValues-scroll'
        },
        headers: {
            0: { sorter: false, filter: false }
        }
    }); 
    
    $.contextMenu({
        selector: 'table#valueList tbody td.valueRelate', 
        callback: function(key, opt) { 
            if (key === 'delete') {
                
                $("#dialog-confirm").dialog({
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: plang.get('msg_title_confirm'),
                    width: 400,
                    height: 'auto',
                    modal: true,   
                    position: 'center',
                    buttons: [
                        {text: plang.get('yes_btn'), class: "btn blue", click: function(){
                            $.ajax({
                                type: 'post',
                                url: 'metadata/deleteValueDependancy',
                                data: {FOLDER_ID: opt.$trigger.attr("id")},
                                dataType: "json",
                                beforeSend: function(){
                                    App.blockUI("table#valueList");
                                },
                                success: function(data) {
                                    if (data.status === 'success') {
                                        $.pnotify({
                                            title: 'Success',
                                            text: data.message,
                                            type: 'success',
                                            sticker: false
                                        }); 
                                        $("#valueList").trigger("update");
                                    } else {
                                        $.pnotify({
                                            title: 'Error',
                                            text: data.message,
                                            type: 'error',
                                            sticker: false
                                        });
                                    }
                                    App.unblockUI("table#valueList");
                                },
                                error: function() {
                                    alert("Error");
                                }
                            });
                            $("#dialog-confirm").dialog('close');
                        }},
                        {text: plang.get('no_btn'), class: "btn", click: function(){
                            $("#dialog-confirm").dialog('close');
                        }}    
                    ]    
                });
                $("#dialog-confirm").html(plang.get('msg_delete_confirm')).dialog('open');
                
            } else if (key === 'attribution') {
            
                var valueId = opt.$trigger.parent().attr("id");
                var $dialogName = 'dialog-metarelate';
                $.ajax({
                    type: 'post',
                    url: 'metadata/valueRelateValue/'+valueId,
                    beforeSend:function(){
                        App.blockUI("#valueList");
                    },
                    success:function(data){
                        $("#"+$dialogName).empty().html(data);  
                        $("#"+$dialogName).dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: "<?php echo $this->lang->line('metadata_value_attribute'); ?>",
                            width: 700,
                            height: "auto",
                            modal: false,      
                            close:function(){
                                $("#"+$dialogName).empty().dialog('close');
                            }, 
                            buttons: [
                                {text: plang.get('save_btn'), class: "btn green", click: function(){
                                    $("#valueRelateValue-form").validate({ errorPlacement: function(){} });
                                    if ($("#valueRelateValue-form").valid()) {
                                        $.ajax({
                                            type: 'post',
                                            url: 'metadata/saveValueRelateValue',
                                            data: $("#valueRelateValue-form").serialize(),
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
                                                    metaValueView(data.metaDataId);
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
                                {text: plang.get('close_btn'), class: "btn black", click: function(){
                                    $("#"+$dialogName).dialog('close');
                                }}
                            ]        
                        });
                        $("#"+$dialogName).dialog('open');
                        App.unblockUI("#valueList");
                    },
                    error:function(){
                        alert("Error");
                    }
                });
            }
        },
        items: {
            "attribution": {name: "<?php echo $this->lang->line('metadata_value_attribute'); ?>", icon: "sitemap"},
            //"delete": {name: "<?php echo $this->lang->line('metadata_value_attribute_delete'); ?>", icon: "trash"}
        }
    });
    
    $.contextMenu({
        selector: 'table#valueList tbody td.valueRelateChild', 
        callback: function(key, opt) { 
            if (key === 'attribution') {
            
                var $dialogName = 'dialog-metarelate';
                var valueId = opt.$trigger.parent().attr("id");
                $.ajax({
                    type: 'post',
                    url: 'metadata/valueRelateChild',
                    data: {valueId: valueId},
                    beforeSend:function(){
                        App.blockUI("#valueList");
                    },
                    success:function(data){
                        $("#"+$dialogName).empty().html(data);  
                        $("#"+$dialogName).dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: "<?php echo $this->lang->line('metadata_value_attribute'); ?>",
                            width: 700,
                            height: "auto",
                            modal: false,      
                            close:function(){
                                $("#"+$dialogName).empty().dialog('close');
                            }, 
                            buttons: [
                                {text: plang.get('save_btn'), class: "btn green", click: function(){
                                    $("#childValueRelateValue-form").validate({ errorPlacement: function(){} });
                                    if ($("#childValueRelateValue-form").valid()) {
                                        $.ajax({
                                            type: 'post',
                                            url: 'metadata/saveChildRelateValue',
                                            data: $("#childValueRelateValue-form").serialize(),
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
                                                    metaValueView(data.metaDataId);
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
                                {text: plang.get('close_btn'), class: "btn black", click: function(){
                                    $("#"+$dialogName).dialog('close');
                                }}
                            ]        
                        });
                        $("#"+$dialogName).dialog('open');
                        App.unblockUI("#valueList");
                    },
                    error:function(){
                        alert("Error");
                    }
                });
            }
        },
        items: {
            "attribution": {name: "<?php echo $this->lang->line('metadata_value_attribute'); ?>", icon: "sitemap"}
        }
    });
    
    $.contextMenu({
        selector: 'table#valueList tbody td.valueRelateParent', 
        callback: function(key, opt) { 
            if (key === 'attribution') {
            
                var $dialogName = 'dialog-metarelate';
                var valueId = opt.$trigger.parent().attr("id");
                $.ajax({
                    type: 'post',
                    url: 'metadata/valueRelateParent',
                    data: {valueId: valueId},
                    beforeSend:function(){
                        App.blockUI("#valueList");
                    },
                    success:function(data){
                        $("#"+$dialogName).empty().html(data);  
                        $("#"+$dialogName).dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: "<?php echo $this->lang->line('metadata_value_attribute'); ?>",
                            width: 700,
                            height: "auto",
                            modal: false,      
                            close:function(){
                                $("#"+$dialogName).empty().dialog('close');
                            }, 
                            buttons: [
                                {text: plang.get('save_btn'), class: "btn green", click: function(){
                                    $("#parentValueRelateValue-form").validate({ errorPlacement: function(){} });
                                    if ($("#parentValueRelateValue-form").valid()) {
                                        $.ajax({
                                            type: 'post',
                                            url: 'metadata/saveParentRelateValue',
                                            data: $("#parentValueRelateValue-form").serialize(),
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
                                                    metaValueView(data.metaDataId);
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
                                {text: plang.get('close_btn'), class: "btn black", click: function(){
                                    $("#"+$dialogName).dialog('close');
                                }}
                            ]        
                        });
                        $("#"+$dialogName).dialog('open');
                        App.unblockUI("#valueList");
                    },
                    error:function(){
                        alert("Error");
                    }
                });
            }
        },
        items: {
            "attribution": {name: "<?php echo $this->lang->line('metadata_value_attribute'); ?>", icon: "sitemap"}
        }
    });
   
});    
</script>    