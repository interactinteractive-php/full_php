<div class="panel panel-default bg-inverse">
    <table class="table sheetTable">
        <tbody>
            <tr>
                <td style="width: 170px" class="left-padding">
                    <label for="layoutId">
                        Layout:
                    </label>
                </td>
                <td class="middle">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'layoutId',
                            'id' => 'layoutId',
                            'data' => (new Mdlayout())->getLayoutList(),
                            'op_value' => 'LAYOUT_ID',
                            'op_text' => 'LAYOUT_CODE| |-| |LAYOUT_NAME',
                            'class' => 'form-control select2', 
                            'value' => $this->ltRow['LAYOUT_ID'], 
                            'style' => 'width: 85%'
                        )
                    );
                    ?>
                    <?php 
                    echo Form::button(
                        array(
                            'class' => 'btn btn-sm purple-plum float-right meta-content-btn mt3 mr0', 
                            'value' => '...', 
                            'onclick' => 'contentCellLink(this);', 
                            'disabled' => 'disabled'
                        )
                    ); 
                    ?>
                    <div id="dialog-celllinks" style="display: none"><?php echo $this->cellLinks; ?></div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script type="text/javascript">
$(function(){
    if ($("#layoutId").val() !== "") {
        $(".meta-content-btn").removeAttr("disabled");
    }
    $("#layoutId").on("change", function(){
        var layoutId = $(this).val();
        if (layoutId !== "") {
            $(".meta-content-btn").removeAttr("disabled");
        } else {
            $(".meta-content-btn").attr("disabled", "disabled");
        }
    });
});   

function contentCellLink(elem){
    var layoutId = $("select#layoutId").val();
    if (layoutId !== "") {
        var $dialogName = 'dialog-celllinks';
        
//        if ($("#"+$dialogName).children().length > 0) {
//            $("#"+$dialogName).dialog({
//                appendTo: "form#editMetaSystemForm",
//                cache: false,
//                resizable: true,
//                bgiframe: true,
//                autoOpen: false,
//                title: 'Cell Links',
//                width: 700,
//                minWidth: 700,
//                height: "auto",
//                modal: true,      
//                buttons: [
//                    {text: plang.get('save_btn'), class:'btn btn-sm green-meadow', click: function(){
//                        $("#"+$dialogName).dialog('close');
//                    }},
//                    {text: plang.get('close_btn'), class:'btn btn-sm blue-hoki', click: function(){
//                        $("#"+$dialogName).dialog('close');
//                    }}, 
//                    {text: "<?php echo $this->lang->line('META_00002'); ?>", class:'btn btn-sm red-sunglo', click: function(){
//                        $("#"+$dialogName).empty().dialog('close');
//                    }}
//                ]        
//            });
//            $("#"+$dialogName).dialog('open');
//        } else {
            $.ajax({
                type: 'post',
                url: 'mdlayout/setCellLink',
                data: {layoutId: layoutId, contentMetaDataId: '<?php echo $this->metaDataId; ?>'},
                dataType: "json",
                beforeSend:function(){
                    Core.blockUI({
                        animate: true
                    });
                },
                success:function(data){
                    $("#"+$dialogName).empty().html(data.Html);  
                    $("#"+$dialogName).dialog({
                        appendTo: "form#editMetaSystemForm",
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: 700,
                        minWidth: 700,
                        height: "auto",
                        modal: true,      
                        buttons: [
                            {text: data.save_btn, class:'btn btn-sm green-meadow bp-btn-subsave', click: function(){
                                $("#"+$dialogName).dialog('close');
                            }},
                            {text: data.close_btn, class:'btn btn-sm blue-hoki', click: function(){
                                $("#"+$dialogName).dialog('close');
                            }}
                        ]        
                    });
                    $("#"+$dialogName).dialog('open');
                    Core.unblockUI();
                },
                error:function(){
                    alert("Error");
                }
            }).done(function(){
                Core.initAjax();
            });
//        }
    }
}    
</script>