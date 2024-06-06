<div class="table-scrollable table-scrollable-borderless" style="margin-top: 0 !important; overflow: auto">
    <!-- <div class="mv10">
        <a href="javascript:;" style="float:right" class="but">Hide/show</a>
    </div> -->
    <table class="table table-hover table-light tab-comment-content">
        <tbody>
            <?php
            if ($this->recordList) {
                $i = 0;
                // if($i < 10)
                foreach ($this->recordList as $row) {
                    $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
            ?>
                <tr>
                    <?php if (isset($this->photoField) && $this->photoField) { ?>
                        <td style="width: 125px;">
                            <img style="width:100px; height: auto; border-radius: 3px" onerror="onUserImgErrorDocDoc(this)" src="<?php echo $row[$this->photoField]; ?>" style="width: 40px;">
                        </td>
                    <?php } ?>
                    <td class="text-left mt5 mb5">
                        <h3 class="mt20"><a href="javascript:;" class="selected-row-link folder-link" data-row-data="<?php echo $rowJson; ?>" onclick="clickItem_<?php echo $this->dataViewId; ?>(this);"><div style="color: #000;font-size: 12px;"><?php echo $row[$this->name1]; ?></div></a></h3>
                        <span> <?php echo $this->name2 ? $row[$this->name2] : ''; ?></span>
                        <div class="d-flex justify-content-end">
                            <a href="javascript:;" onclick="commentAddBoardProcess(this, ' <?php echo $this->name5 ? $row[$this->name5] : '1578069565628'; ?>', '<?php echo $row['id']?>','<?php echo $row['contentid']?>')" class="btn btn-success btn-circle btn-sm" style="float:left" class="but">Сэтгэгдэл</a>
                            <a href="javascript:;" onclick="commentBoardProcess(this, '<?php echo $this->name4 ? $row[$this->name4] : ''; ?>', '<?php echo $row['id']; ?>', '<?php echo $row['contentid']?>')" class="btn btn-danger btn-circle btn-sm">Устгах</a>
                            <a href="javascript:;" onclick="commentUpdateBoardProcess(this, '<?php echo $this->name3 ? $row[$this->name3] : ''; ?>', '<?php echo $row['id']; ?>', '<?php echo $row['contentid']?>')" class="btn btn-success btn-circle btn-sm">Засах</a>
                        </div>
                    </td>
                </tr>
            <?php
            $i ++;
            }
            }
            ?>
        </tbody>
    </table>
</div>
<style>
    #crm_dialog {
        text-align: center;
    }

    #crm_dialog img{
        max-width: 100%;
    }
</style>
<script>

    $(".but").click (function(){ 
        $(".tab-comment-content").stop().slideUp(300); 
        $(this).next(".tab-comment-content").stop().slideToggle(300);
    });
    
    function commentAddBoardProcess(elem, pid, recordid, contentid) {
    
        var $dialogName = 'dialog-taskboard-bp';
        if (!$('#' + $dialogName).length) {
            $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName), fillDataParams = '', 
            saveUrl = 'mdwebservice/runProcess';
            fillDataParams = 'contentid='+contentid+'&recordid='+contentid+'&defaultGetPf=1';

        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: {
                metaDataId: pid, 
                isDialog: true, 
                isSystemMeta: false,
                fillDataParams: fillDataParams
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...', 
                    boxed: true
                });
            },
            success: function (data) {

                $dialog.empty().append(data.Html);

                var $processForm = $dialog.find('#wsForm'), processUniqId = $processForm.parent().attr('data-bp-uniq-id');
                var buttons = [
                    {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                        if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                            $processForm.validate({ 
                                ignore: '', 
                                highlight: function(element) {
                                    $(element).addClass('error');
                                    $(element).parent().addClass('error');
                                    if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                        $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                            var tabId = $(tab).attr('id');
                                            $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                        });
                                    }
                                },
                                unhighlight: function(element) {
                                    $(element).removeClass('error');
                                    $(element).parent().removeClass('error');
                                },
                                errorPlacement: function(){} 
                            });

                            var isValidPattern = initBusinessProcessMaskEvent($processForm);

                            if ($processForm.valid() && isValidPattern.length === 0) {
                                $processForm.ajaxSubmit({
                                    type: 'post',
                                    url: saveUrl,
                                    dataType: 'json',
                                    beforeSubmit: function (formData, jqForm, options) {
                                    },
                                    beforeSend: function () {
                                        Core.blockUI({
                                            boxed: true, 
                                            message: 'Түр хүлээнэ үү'
                                        });
                                    },
                                    success: function (responseData) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: responseData.status,
                                            text: responseData.message,
                                            type: responseData.status, 
                                            sticker: false
                                        });

                                        if (responseData.status === 'success') {
                                            var defaultCriteria = {};
                                            defaultCriteria.defaultCriteriaData = $("div#dv-search-<?php echo $this->dataViewId; ?> form#default-criteria-form").serialize();
                                            explorerRefresh_<?php echo $this->dataViewId; ?>(elem, defaultCriteria);
                                            /*if (isMulti) {
                                                dataGrid.datagrid('reload');
                                            } else {
                                                $(elem).closest('div.datagrid-view').children('table').datagrid('reload');
                                            }*/
                                            $dialog.dialog('close');
                                        } 
                                        Core.unblockUI();
                                    },
                                    error: function () {
                                        alert("Error");
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }    
                    }},
                    {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ];

                var dialogWidth = data.dialogWidth, dialogHeight = data.dialogHeight;

                if (data.isDialogSize === 'auto') {
                    dialogWidth = 1200;
                    dialogHeight = 'auto';
                }

                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: dialogWidth,
                    height: dialogHeight,
                    modal: true,
                    closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape), 
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: buttons
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
                if (data.dialogSize === 'fullscreen') {
                    $dialog.dialogExtend('maximize');
                }
                $dialog.dialog('open');
            },
            error: function () {
                alert('Error');
                Core.unblockUI();
            }
        }).done(function () {
            Core.initBPAjax($dialog);
            Core.unblockUI();
        });
    }

    function commentBoardProcess(elem, pid, recordid, contentid) {
    
        var $dialogName = 'dialog-taskboard-bp';
        if (!$('#' + $dialogName).length) {
            $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName), fillDataParams = '', 
            saveUrl = 'mdwebservice/runProcess';
            fillDataParams = 'contentid='+contentid;

        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: {
                metaDataId: pid, 
                isDialog: true, 
                isSystemMeta: false,
                fillDataParams: fillDataParams
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...', 
                    boxed: true
                });
            },
            success: function (data) {

                $dialog.empty().append(data.Html);

                var $processForm = $dialog.find('#wsForm'), processUniqId = $processForm.parent().attr('data-bp-uniq-id');
                var buttons = [
                    {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                        if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                            $processForm.validate({ 
                                ignore: '', 
                                highlight: function(element) {
                                    $(element).addClass('error');
                                    $(element).parent().addClass('error');
                                    if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                        $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                            var tabId = $(tab).attr('id');
                                            $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                        });
                                    }
                                },
                                unhighlight: function(element) {
                                    $(element).removeClass('error');
                                    $(element).parent().removeClass('error');
                                },
                                errorPlacement: function(){} 
                            });

                            var isValidPattern = initBusinessProcessMaskEvent($processForm);

                            if ($processForm.valid() && isValidPattern.length === 0) {
                                $processForm.ajaxSubmit({
                                    type: 'post',
                                    url: saveUrl,
                                    dataType: 'json',
                                    beforeSubmit: function (formData, jqForm, options) {
                                    },
                                    beforeSend: function () {
                                        Core.blockUI({
                                            boxed: true, 
                                            message: 'Түр хүлээнэ үү'
                                        });
                                    },
                                    success: function (responseData) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: responseData.status,
                                            text: responseData.message,
                                            type: responseData.status, 
                                            sticker: false
                                        });

                                        if (responseData.status === 'success') {
                                            var defaultCriteria = {};
                                            defaultCriteria.defaultCriteriaData = $("div#dv-search-<?php echo $this->dataViewId; ?> form#default-criteria-form").serialize();
                                            explorerRefresh_<?php echo $this->dataViewId; ?>(elem, defaultCriteria);
                                            /*if (isMulti) {
                                                dataGrid.datagrid('reload');
                                            } else {
                                                $(elem).closest('div.datagrid-view').children('table').datagrid('reload');
                                            }*/
                                            $dialog.dialog('close');
                                        } 
                                        Core.unblockUI();
                                    },
                                    error: function () {
                                        alert("Error");
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }    
                    }},
                    {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ];

                var dialogWidth = data.dialogWidth, dialogHeight = data.dialogHeight;

                if (data.isDialogSize === 'auto') {
                    dialogWidth = 1200;
                    dialogHeight = 'auto';
                }

                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: dialogWidth,
                    height: dialogHeight,
                    modal: true,
                    closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape), 
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: buttons
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
                if (data.dialogSize === 'fullscreen') {
                    $dialog.dialogExtend('maximize');
                }
                $dialog.dialog('open');
            },
            error: function () {
                alert('Error');
                Core.unblockUI();
            }
        }).done(function () {
            Core.initBPAjax($dialog);
            Core.unblockUI();
        });
    }

    function commentUpdateBoardProcess(elem, pid, recordid ,contentid) {
    
        var $dialogName = 'dialog-taskboard-bp';
        if (!$('#' + $dialogName).length) {
            $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName), fillDataParams = '', 
            saveUrl = 'mdwebservice/runProcess';
            fillDataParams = 'contentid='+ contentid;

        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: {
                metaDataId: pid, 
                isDialog: true, 
                isSystemMeta: false,
                fillDataParams: fillDataParams
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...', 
                    boxed: true
                });
            },
            success: function (data) {

                $dialog.empty().append(data.Html);

                var $processForm = $dialog.find('#wsForm'), processUniqId = $processForm.parent().attr('data-bp-uniq-id');
                var buttons = [
                    {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                        if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                            $processForm.validate({ 
                                ignore: '', 
                                highlight: function(element) {
                                    $(element).addClass('error');
                                    $(element).parent().addClass('error');
                                    if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                        $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                            var tabId = $(tab).attr('id');
                                            $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                        });
                                    }
                                },
                                unhighlight: function(element) {
                                    $(element).removeClass('error');
                                    $(element).parent().removeClass('error');
                                },
                                errorPlacement: function(){} 
                            });

                            var isValidPattern = initBusinessProcessMaskEvent($processForm);

                            if ($processForm.valid() && isValidPattern.length === 0) {
                                $processForm.ajaxSubmit({
                                    type: 'post',
                                    url: saveUrl,
                                    dataType: 'json',
                                    beforeSubmit: function (formData, jqForm, options) {
                                    },
                                    beforeSend: function () {
                                        Core.blockUI({
                                            boxed: true, 
                                            message: 'Түр хүлээнэ үү'
                                        });
                                    },
                                    success: function (responseData) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: responseData.status,
                                            text: responseData.message,
                                            type: responseData.status, 
                                            sticker: false
                                        });

                                        if (responseData.status === 'success') {
                                            var defaultCriteria = {};
                                            defaultCriteria.defaultCriteriaData = $("div#dv-search-<?php echo $this->dataViewId; ?> form#default-criteria-form").serialize();
                                            explorerRefresh_<?php echo $this->dataViewId; ?>(elem, defaultCriteria);
                                            /*if (isMulti) {
                                                dataGrid.datagrid('reload');
                                            } else {
                                                $(elem).closest('div.datagrid-view').children('table').datagrid('reload');
                                            }*/
                                            $dialog.dialog('close');
                                        } 
                                        Core.unblockUI();
                                    },
                                    error: function () {
                                        alert("Error");
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }    
                    }},
                    {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ];

                var dialogWidth = data.dialogWidth, dialogHeight = data.dialogHeight;

                if (data.isDialogSize === 'auto') {
                    dialogWidth = 1200;
                    dialogHeight = 'auto';
                }

                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: dialogWidth,
                    height: dialogHeight,
                    modal: true,
                    closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape), 
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: buttons
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
                if (data.dialogSize === 'fullscreen') {
                    $dialog.dialogExtend('maximize');
                }
                $dialog.dialog('open');
            },
            error: function () {
                alert('Error');
                Core.unblockUI();
            }
        }).done(function () {
            Core.initBPAjax($dialog);
            Core.unblockUI();
        });
    }
</script>