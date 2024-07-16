<?php 

    $uid = getUID();
?>
<div class="mv-cloudcard-<?php echo $uid ?>">
    <div class="appmenu-table appmenu-newdesign-1 p-0 mx-0">
        <div class="appmenu-table-row">          
            <div id="mixgrid" class="pt0 appmenu-table-cell-right mix-grid appmenu-card-html p-0"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    function itemCardGroupInit<?php echo $uid ?>(id, elem) {
      $.ajax({
        type: "post",
        url: "mdwidget/cardHtml",
        data: { 
            parentId: id, 
            uid: '<?php echo $uid ?>' ,
            indicatorId: '<?php echo $this->indicatorId ?>' ,
            relationViewConfig: '<?php echo json_encode(issetParamArray($this->relationViewConfig)) ?>' 
        },
        dataType: "json",
        beforeSend: function () {
          Core.blockUI({
            message: "Loading...",
            boxed: true
          });
        },
        success: function (data) {
            if (data.html == '') {
                alert('Хоосон байна.');
                Core.unblockUI();
                return;
            }

            var htmlStr = '';
            if (id != '') {
                
                htmlStr += '<h2 class="ml-2 pt10 pb10" style="display: flex;width: 100%;text-transform: uppercase;font-family: Arial;font-size: 16px;"><a href="javascript:;" class="back-item-btn d-block" data-parentid="'+ data.parentid +'"><i class="icon-arrow-left8" style="color:#000"></i></a><span class="pt11 pl12">'+$(elem).data('modulename')+'</span></h2>';
            }
            htmlStr += data.html;

            var $mainContent = $(".appmenu-card-html");
            $mainContent.empty().append(htmlStr);
            Core.unblockUI();
            $('html, body').scrollTop(0);          
        },
        error: function () {
            Core.unblockUI();
        },
      });
    }
    
    $(document).on('click', '.back-item-btn', function(e){
        var $parentId = $(this).data('parentid');
        itemCardGroupInit<?php echo $uid ?>($parentId);
    });

    $(function () {
        itemCardGroupInit<?php echo $uid ?>('');
    });
    
    function mvProductAppmenuCardRender<?php echo $uid ?>(indicatorId, dataFolderId, processFolderId) {
        $.ajax({
            type: 'post',
            url: 'mdform/mvProductRender',
            data: {
                indicatorId: indicatorId,
                dataFolderId: dataFolderId,
                processFolderId: processFolderId,
                appMenuCard: 1
            }, 
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {

                if (data.status == 'success') {

                    if (data.renderType == 'paper_main_window') {
                        window.location.href = URL_APP + 'appmenu/mvmodule/' + indicatorId;
                    } else {
                        var $dialogName = 'dialog-valuemap-'+indicatorId;
                        if (!$("#" + $dialogName).length) {
                            $('<div id="' + $dialogName + '"></div>').appendTo('body');
                        }
                        var $dialog = $('#' + $dialogName);

                        $dialog.dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: '',
                            width: 1000,
                            height: 'auto',
                            modal: true,
                            closeOnEscape: false,
                            open: function() {
                                $dialog.append(data.html);
                                $dialog.parent().find(">.ui-dialog-buttonpane").remove();
                                $dialog.parent().find(">.ui-dialog-titlebar").remove();
                                var dh = $dialog.parent().find(">.ui-dialog-content").height() + 110;
                                $dialog.parent().find(">.ui-dialog-content").css("height", dh+"px");
                            },
                            beforeClose: function() {

                                if ($dialog.data('can-close')) {
                                    $dialog.removeData('can-close');
                                    return true;
                                }

                                var dialogNameConfirm = '#dialog-mvproduct-confirm';
                                if (!$(dialogNameConfirm).length) {
                                    $('<div id="' + dialogNameConfirm.replace('#', '') + '"></div>').appendTo('body');
                                }
                                var $dialogConfirm = $(dialogNameConfirm);

                                $dialogConfirm.html(plang.get('Та гарахдаа итгэлтэй байна уу?'));
                                $dialogConfirm.dialog({
                                    cache: false,
                                    resizable: true,
                                    bgiframe: true,
                                    autoOpen: false,
                                    title: plang.get('msg_title_confirm'), 
                                    width: 300,
                                    height: 'auto',
                                    modal: true,
                                    buttons: [
                                        {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function() {
                                            $dialogConfirm.dialog('close');
                                            $dialog.data('can-close', true);
                                            $dialog.dialog('close');
                                        }},
                                        {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                            $dialogConfirm.dialog('close');
                                        }}
                                    ]
                                });
                                $dialogConfirm.dialog('open');

                                return false;
                            },
                            close: function() {
                                removeHtmlEditorByElement($dialog);
                                $dialog.empty().dialog('destroy').remove();
                            },
                            buttons: [
                                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki bp-btn-close', click: function () {
                                    $dialog.dialog('close');
                                }}
                            ]
                        }).dialogExtend({
                            "closable": true,
                            "maximizable": true,
                            "minimizable": true,
                            "collapsable": true,
                            "dblclick": "maximize",
                            "minimizeLocation": "left",
                            "icons": {
                                "close": "ui-icon-circle-close",
                                "maximize": "ui-icon-extlink",
                                "minimize": "ui-icon-minus",
                                "collapse": "ui-icon-triangle-1-s",
                                "restore": "ui-icon-newwin"
                            }
                        });

                        $dialog.dialogExtend('maximize');
                        $dialog.dialog('open');
                    }                                   

                } else if (data.status == 'info') {

                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false, 
                        addclass: 'pnotify-center'
                    });

                }

                Core.unblockUI();
            }
        });
    }    
    
</script>
<style type="text/css">
    .mv-cloudcard-<?php echo $uid ?> {
    .appmenu-newdesign-1 .white-card-menu .vr-menu-tile, .appmenu-newdesign-1 .appmenu-table-cell-right .vr-menu-tile {
        overflow: hidden !important;
        padding: 15px;
        background: var(--root-color02);
        text-decoration: none;
        width: 180px;
        height: 98px;
        margin: 0 13px 18px 6px;
        border: 0;
        border-radius: 20px 20px 20px 20px;
        display: block;
        float: left;
        position: static;
    }
    .appmenu-newdesign-1 .vr-menu-tile > div {
        display: block !important;
    }
    .appmenu-newdesign-1 .white-card-menu .vr-menu-title, .appmenu-newdesign-1 .appmenu-table-cell-right .vr-menu-title {
        margin-top: 30px;
    }
    .appmenu-newdesign-1 .appmenu-table-cell-right .vr-menu-title .vr-menu-row .vr-menu-name {
        font-size: 16px !important;
        font-weight: 500 !important;
        color: #fff;
        font-family: 'Rubik';
    }
    .new-vlogo-link-selector {
        padding-top: 12px !important;
        padding-bottom: 12px !important
    }
    .new-vlogo-link-selector .header-logo img {
        max-height: 33px !important;
    }    
    .appmenu-newdesign-1 .appmenu-table-cell-right {
        /*background-color: #fff;*/
    }
    .back-item-btn {
        background: #FFFFFF;
        border: 1px solid #E6E6E6;
        box-sizing: border-box;
        border-radius: 10px;
        width: 40px;
        height: 40px;
        text-align: center;
        padding-top: 9px;
    }      
    .item-card-toptitle {
        color: #3C3C3C;
        font-size: 18px;
        font-family: 'Rubik';
        text-transform: uppercase;
        font-weight: 600;        
    }
    .appmenu-newdesign-1 .white-card-menu .vr-menu-title .vr-menu-row .vr-menu-name,     
    .appmenu-newdesign-1 .appmenu-table-cell-right .vr-menu-title .vr-menu-row .vr-menu-name {        
        color: #fff;
    }
    .appmenu-newdesign-1 .appmenu-table-cell-right .vr-menu-title .vr-menu-row i {
        color: #fff;
    }
    .appmenu-newdesign-1 .white-card-menu .vr-menu-tile, .appmenu-newdesign-1 .appmenu-table-cell-right .vr-menu-tile {
        height: 98px;
    }    
    .appmenu-newdesign-1 .appmenu-table-cell-right .vr-menu-tile:hover {
        background-color: #596fff !important;
    }    
    .appmenu-newdesign-1 .appmenu-table-cell-right .vr-menu-tile:hover .vr-menu-row .vr-menu-name,
    .appmenu-newdesign-1 .appmenu-table-cell-right .vr-menu-tile:hover .vr-menu-row i {
        color: #fff !important;
    }
    .appmenu-newdesign-1 .white-card-menu .vr-menu-title, .appmenu-newdesign-1 .appmenu-table-cell-right .vr-menu-title {
        margin-top: 30px;
    }
    .card-ischild-0 .acard-is-child-div {
        display: none;
    }
    .acard-is-child-div i {
        color: #ffffff9e;
    }
    .vr-menu-tile:hover .acard-is-child-div i {
        color: #fff;
    }
    .appmenu-newdesign-1 .white-card-menu .vr-menu-tile.random-border-radius3, 
    .appmenu-newdesign-1 .appmenu-table-cell-right .vr-menu-tile.random-border-radius3 {
        border-radius: 15px;
        box-shadow: 0px 4px 6px 0px rgba(0, 0, 0, 0.06);
    }
    .page-content {
        background-color: #F3F4F6;
    }    
    .appmenu-newdesign-1 .white-card-menu .vr-menu-title .vr-menu-row .vr-menu-name, 
    .appmenu-newdesign-1 .appmenu-table-cell-right .vr-menu-title .vr-menu-row .vr-menu-name {
        max-height: 38px;
    }    
    }
</style>