<?php $renderAtom = new Mdwidget(); ?>
<div class="w-full flex justify-start flex-col pl-1 pr-3 py-3">
    <div style="color:#585858;font-size:20px"><?php echo Lang::lineDefault(issetParam($this->jsonAttr['title']), 'Тайлан'); ?></div>
    <ul class="mt-3">
        <?php 
        if ($this->datasrc) {
            foreach($this->datasrc as $row) { ?>               
                <li style="margin-bottom: 10px;" data-processid="<?php echo $renderAtom->renderAtom($row, "position4", $this->positionConfig) ?>" data-metatypeid="<?php echo $renderAtom->renderAtom($row, "position5", $this->positionConfig) ?>" data-row="{}" class="cloud-call-report flex w-full justify-between text-gray-800  leading-none cursor-pointer items-center relative text-sso">
                    <div class="flex">
                        <div class="p-4 rounded-3xl flex items-center justify-center" style="height: 40px;width: 40px;aspect-ratio: auto 1 / 1; color: rgb(118, 51, 107);background-color:<?php echo $renderAtom->renderAtom($row, "position6", $this->positionConfig, "#E1EBFD") ?>">
                            <i style="color:<?php echo $renderAtom->renderAtom($row, "position7", $this->positionConfig, "#699BF7") ?>" class="far <?php echo $renderAtom->renderAtom($row, "position1", $this->positionConfig, "fa-smile") ?> text-xl false hover:text-sso "></i>
                        </div>
                        <div class="ml-2 self-center">
                            <div style="color:#585858;font-size:14px"><?php echo $renderAtom->renderAtom($row, "position2", $this->positionConfig) ?></div>
                            <div style="color:#9FA2B4;font-size:12px;" class="mt-1"><?php echo $renderAtom->renderAtom($row, "position3", $this->positionConfig) ?></div>
                        </div>
                    </div>
                </li>               
        <?php }
        } ?>              
    </ul>
    <div style="color:#9FA2B4;font-size:14px;" class="hidden">Бүгдийг харах</div>
</div>

<script>
    $(".cloud-call-report").click(function(){
    
        var metaDataId = $(this).data("processid");

        if (!metaDataId) {
            return;
        }

        var getCustomerItems = $.ajax({
            type: "post",
            url: "mdmetadata/getMetaDataDrill/"+metaDataId,
            dataType: "json",
            async: false,
            success: function (data) {
                Core.unblockUI();
                return data.result;
            }
        });
        
        if (getCustomerItems.responseJSON.META_TYPE_CODE.toLowerCase() === 'statement') {
            $.ajax({
                type: 'post',
                url: 'mdstatement/index/' + $(this).data("processid"),
                beforeSend: function() {
                    Core.blockUI({animate: true});
                },
                success: function(dataHtml) {
                    var $dialogName = "dialog-pos-cloudreport";
                    if (!$("#" + $dialogName).length) {
                        $('<div id="' + $dialogName + '"></div>').appendTo("body");
                    }                
                    var $dialog = $("#" + $dialogName);

                    $dialog.empty().append(dataHtml);
                    $dialog.dialog({
                        cache: false,
                        resizable: false,
                        bgiframe: true,
                        autoOpen: false,
                        title: "Тайлан",
                        width: 1000,
                        minWidth: 1000,
                        height: "auto",
                        modal: true,
                        closeOnEscape: isCloseOnEscape,
                        position: { my: "top", at: "top" },
                        open: function () {
                            disableScrolling();
                        },
                        close: function () {
                            enableScrolling();
                        },
                    }).dialogExtend({
                        closable: true,
                        maximizable: true,
                        minimizable: true,
                        collapsable: true,
                        dblclick: "maximize",
                        minimizeLocation: "left",
                        icons: {
                        close: "ui-icon-circle-close",
                        maximize: "ui-icon-extlink",
                        minimize: "ui-icon-minus",
                        collapse: "ui-icon-triangle-1-s",
                        restore: "ui-icon-newwin",
                        },
                    });
                    $dialog.dialogExtend("maximize");
                    $dialog.dialog("open");
                    Core.unblockUI();
                },
                error: function() {
                    alert('Error');
                }
            });            
        } else {      
            gridDrillDownLink($(this), getCustomerItems.responseJSON.META_DATA_CODE, getCustomerItems.responseJSON.META_TYPE_CODE.toLowerCase(), '1', '',  '', '',metaDataId, '', false, true, $(window).width() - 20, $(window).height() - 10); 
        }
    });
</script>