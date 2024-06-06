<script type="text/javascript">
    
    <?php if(isset($this->drillId)) { ?>
        getNewsContent(<?php echo $this->drillId ?>);
    <?php } ?>
    
    var panelDv_<?php echo $this->uniqId; ?> = $('body').find('.intranet-<?php echo $this->uniqId ?>');
    var firstList_<?php echo $this->uniqId; ?> = $('body').find('.leftsidebar-<?php echo $this->uniqId ?>');
    var firstcontentclicked = 0;
    
    jQuery(document).ready(function () {
        $('ul.leftsidebar-<?php echo $this->uniqId ?>  > li:eq(0) > a').trigger('click');
        $('#tooltip-demo').tooltip();
        Core.initFancybox($(".galleryfancy"));
        
        getRightSide();
    });
    
    $(function () {
        $.contextMenu({
            selector: "li.contextmenu-li-tag-<?php echo $this->uniqId ?>",
            callback: function(key, opt) {
                var $this = $(opt['$trigger'][0]);
                if (key === 'edit') {
                    var id = $this.data('id');
//                    var $data = $this.children().data('rowdata');
                    editPost_<?php echo $this->uniqId ?>(id);
                }
                if (key === 'delete') {
                    var id = $this.data('id');
                    var element = $this.closest('li');
                    deletePost_<?php echo $this->uniqId ?>(id, element);
                }
            },
            items: {
                "edit": {name: "Засах", icon: "pencil"},
                "delete": {name: "Устгах", icon: "trash"}
            }
        });
    });

    //post add action
    panelDv_<?php echo $this->uniqId; ?>.on('click', 'a[data-secondlistaddprocessid]', function () {
        var elem = this;
        var processId = $(elem).data('secondlistaddprocessid');
        if (processId) {

            var $dialogName = 'dialog-businessprocess-' + processId;
            if (!$('#' + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);

            var fillDataParams = '',
                rowData = firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').attr('rowdata');
                
            if (typeof rowData !== 'object') {
                rowData = JSON.parse(rowData);
                if(rowData.parentid === '-1'){
                    fillDataParams = 'typeId=' + rowData.id; 
                } else {
                    fillDataParams = 'typeId=' + rowData.typeid + '&categoryId=' + rowData.categoryid;
                }
            }
            
            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: processId,
                    isDialog: true,
                    isHeaderName: false,
                    isBackBtnIgnore: 1,
                    callerType: 'dv',
                    openParams: '{"callerType":"dv","afterSaveNoAction":true,"afterSaveNoActionFnc":"panelDvRefreshSecondList(<?php echo $this->uniqId; ?>)"}',
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

                    var $processForm = $('#wsForm', '#' + $dialogName),
                        processUniqId = $processForm.parent().attr('data-bp-uniq-id');

                    var buttons = [
                        {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                                if (window['processBeforeSave_' + processUniqId]($(e.target))) {

                                    $processForm.validate({
                                        ignore: '',
                                        highlight: function (element) {
                                            $(element).addClass('error');
                                            $(element).parent().addClass('error');
                                            if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                                $processForm.find("div.tab-pane:hidden:has(.error)").each(function (index, tab) {
                                                    var tabId = $(tab).attr('id');
                                                    $processForm.find('a[href="#' + tabId + '"]').tab('show');
                                                });
                                            }
                                        },
                                        unhighlight: function (element) {
                                            $(element).removeClass('error');
                                            $(element).parent().removeClass('error');
                                        },
                                        errorPlacement: function () {
                                        }
                                    });

                                    var isValidPattern = initBusinessProcessMaskEvent($processForm);

                                    if ($processForm.valid() && isValidPattern.length === 0) {
                                        $processForm.ajaxSubmit({
                                            type: 'post',
                                            url: 'mdwebservice/runProcess',
                                            dataType: 'json',
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
                                                    getMenuId(rowData.id, rowData.name, rowData.categoryid, rowData, firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected'));
                                                    $dialog.dialog('close');
                                                }
                                                Core.unblockUI();
                                            },
                                            error: function () {
                                                Core.unblockUI();
                                                alert("Error");
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
                        $dialog.dialogExtend("maximize");
                    }

                    setTimeout(function () {
                        $dialog.dialog('open');
                        Core.unblockUI();
                    }, 1);
                },
                error: function () {
                    alert('Error');
                }
            });
        }

    });

    //post edit action
    panelDv_<?php echo $this->uniqId; ?>.on('click', 'button[data-editactionbtn]', function (e) {
    alert('sda');
        var elem = this;
        var id = $(elem).data('editactionbtn');
        var processId = 1568017428765;
        if (processId) {

            var $dialogName = 'dialog-businessprocess-' + processId;
            if (!$('#' + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);

            var contentData = $("#edit" + id).data('rowdata');
//            console.log('content-data = ' + contentData.longdescr);
            var fillDataParams = 'typeId=' + contentData.typeid + '&categoryId=' + contentData.categoryid 
                            + '&description=' + contentData.description + '&imgUrl=' + contentData.imgurl
                            + '&longDescr=' + html_entity_decode(contentData.longdescr, "ENT_QUOTES") + '&id=' + contentData.id + '&isLongDescr=' + contentData.ispinpost;
            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: processId,
                    isDialog: true,
                    isHeaderName: false,
                    isBackBtnIgnore: 1,
                    callerType: 'dv',
                    openParams: '{"callerType":"dv","afterSaveNoAction":true,"afterSaveNoActionFnc":"panelDvRefreshSecondList(<?php echo $this->uniqId; ?>)"}',
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

                    var $processForm = $('#wsForm', '#' + $dialogName),
                        processUniqId = $processForm.parent().attr('data-bp-uniq-id');

                    var buttons = [
                        {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                                if (window['processBeforeSave_' + processUniqId]($(e.target))) {

                                    $processForm.validate({
                                        ignore: '',
                                        highlight: function (element) {
                                            $(element).addClass('error');
                                            $(element).parent().addClass('error');
                                            if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                                $processForm.find("div.tab-pane:hidden:has(.error)").each(function (index, tab) {
                                                    var tabId = $(tab).attr('id');
                                                    $processForm.find('a[href="#' + tabId + '"]').tab('show');
                                                });
                                            }
                                        },
                                        unhighlight: function (element) {
                                            $(element).removeClass('error');
                                            $(element).parent().removeClass('error');
                                        },
                                        errorPlacement: function () {
                                        }
                                    });

                                    var isValidPattern = initBusinessProcessMaskEvent($processForm);

                                    if ($processForm.valid() && isValidPattern.length === 0) {
                                        $processForm.ajaxSubmit({
                                            type: 'post',
                                            url: 'mdwebservice/runProcess',
                                            dataType: 'json',
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
                                                    getMenuId(contentData.typeid, contentData.typename, contentData.categoryid, contentData, firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected'));
                                                    getNewsContent(id);
                                                    $dialog.dialog('close');
                                                }
                                                Core.unblockUI();
                                            },
                                            error: function () {
                                                alert("Error");
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
                        $dialog.dialogExtend("maximize");
                    }

                    setTimeout(function () {
                        $dialog.dialog('open');
                        Core.unblockUI();
                    }, 1);
                },
                error: function () {
                    alert('Error');
                }
            });
        }

    });

    //post delete action
    panelDv_<?php echo $this->uniqId; ?>.on('click', 'button[data-deleteactionbtn]', function (e) {
        var $this = $(this);
        var id = $this.data('deleteactionbtn');
        if (id) {
            runIsOneBusinessProcess(1564662242403286, 1568017432968, true, {id: id}, function () {
                $this.closest('li').fadeOut(1000, function () {
                    $(this).remove();
                });
            });
            e.preventDefault();
            e.stopPropagation();
        }
    });
    
    function getPostAttachments() {
        var attachArray = [];
        $.ajax({
            url: 'mdintranet/getPostAttachments',
            type: 'post',
            data: {id: 1571993537393},
            dataType: 'JSON',
            success: function (result) {
                console.log(result);
                $.each(result, function (i, data) {
//                    console.log(data.ecmcontentmap);
                    attachArray.push({"&id": data.id, "&fileName": data.filename, "&fileSize": data.filesize, "&fileExtension": data.fileextension, "&physicalPath" : data.physicalpath, "&relatedId":data.relatedid});
                    
                });
                
            }
        });
        console.log(attachArray);
        return attachArray;
    }   
    
    function editPost_<?php echo $this->uniqId ?>(id) {
        var elem = this;
        var id = id;
        var processId = 1568017428765;
        if (processId) {
            var $dialogName = 'dialog-businessprocess-' + processId;
            if (!$('#' + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);
            var contentData = $("#edit" + id).data('rowdata');
            var fillDataParams = 'typeId=' + contentData.typeid + '&categoryId=' + contentData.categoryid 
                            + '&description=' + contentData.description + '&imgUrl=' + contentData.imgurl
                            + '&longDescr=' + html_entity_decode(contentData.longdescr, "ENT_QUOTES") 
                            + '&id=' + contentData.id + '&isLongDescr=' + contentData.ispinpost + "&isLike=" + contentData.islike
                            + '&isComment=' + contentData.iscomment; 
//             $.each(rowData, function (index, row) {
//                fillDataParams += '&'+ index +'=' + row;
//            });       
//            fillDataParams += '&testRow[';
//            for(var i = 1; i < 10 + 1; i++) {
//                fillDataParams += '&testRow.id[1]=123';
//            } 
//            fillDataParams += ']';
                    
//            fillDataParams += getPostAttachments;
//                        console.log(fillDataParams);

            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: processId,
                    isDialog: true,
                    isHeaderName: false,
                    isBackBtnIgnore: 1,
                    callerType: 'dv',
                    openParams: '{"callerType":"dv","afterSaveNoAction":true,"afterSaveNoActionFnc":"panelDvRefreshSecondList(<?php echo $this->uniqId; ?>)"}',
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

                    var $processForm = $('#wsForm', '#' + $dialogName),
                        processUniqId = $processForm.parent().attr('data-bp-uniq-id');

                    var buttons = [
                        {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                                if (window['processBeforeSave_' + processUniqId]($(e.target))) {

                                    $processForm.validate({
                                        ignore: '',
                                        highlight: function (element) {
                                            $(element).addClass('error');
                                            $(element).parent().addClass('error');
                                            if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                                $processForm.find("div.tab-pane:hidden:has(.error)").each(function (index, tab) {
                                                    var tabId = $(tab).attr('id');
                                                    $processForm.find('a[href="#' + tabId + '"]').tab('show');
                                                });
                                            }
                                        },
                                        unhighlight: function (element) {
                                            $(element).removeClass('error');
                                            $(element).parent().removeClass('error');
                                        },
                                        errorPlacement: function () {
                                        }
                                    });

                                    var isValidPattern = initBusinessProcessMaskEvent($processForm);

                                    if ($processForm.valid() && isValidPattern.length === 0) {
                                        $processForm.ajaxSubmit({
                                            type: 'post',
                                            url: 'mdwebservice/runProcess',
                                            dataType: 'json',
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
                                                    getMenuId(contentData.typeid, contentData.typename, contentData.categoryid, contentData, firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected'));
                                                    getNewsContent(id);
                                                    $dialog.dialog('close');
                                                }
                                                Core.unblockUI();
                                            },
                                            error: function () {
                                                alert("Error");
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
                        $dialog.dialogExtend("maximize");
                    }

                    setTimeout(function () {
                        $dialog.dialog('open');
                        Core.unblockUI();
                    }, 1);
                },
                error: function () {
                    alert('Error');
                }
            });
        }
    }
    
    function deletePost_<?php echo $this->uniqId ?>(id, element) {
        if (id) {
            runIsOneBusinessProcess(1564662242403286, 1568017432968, true, {id: id}, function () {
                element.fadeOut(1000, function () {
                    $(this).remove();
                });
            });
        }
    }

    function getSubMenuIntranet_<?php echo $this->uniqId ?>(element, id, level, metadataid) {
        
        var $element = $(element);
        var $parent = $element.parent();
        var isSubItem = $element.hasClass('v2');
        var $openMenu = $('body').find('.leftsidebar-<?php echo $this->uniqId ?>').find('.nav-item-open');
        var openMenuCount = $openMenu.length;
        
        if (!isSubItem && openMenuCount) {
            $openMenu.not($parent).removeClass('nav-item-open');
            $('body').find('.leftsidebar-<?php echo $this->uniqId ?>').not($parent).find('.nav-group-sub').hide();
        }
        
        var $mainSelectorLi = $element.closest('li');
        $('body').find('.leftsidebar-<?php echo $this->uniqId ?>').find('.dv-twocol-f-selected').removeClass('dv-twocol-f-selected');
        $mainSelectorLi.addClass('dv-twocol-f-selected');
        
        if ($parent.hasClass('nav-item-open')) {
            $parent.removeClass('nav-item-open');
            $parent.find('.nav-group-sub').hide();
            return;
        }

        if ($parent.find('.nav-group-sub').length == 0) {
            var $dataRow = JSON.parse($element.attr('data-row'));
            $.ajax({
                url: 'mdintranet/getIntranetSubMenuRender/',
                data: {'id': id, 'subLevel': level, uniqId: '<?php echo $this->uniqId ?>', dataRow: $dataRow},
                type: 'post',
                dataType: 'JSON',
                success: function (result) {

                    if (typeof result.menu !== 'undefined' && result.menu == '1') {
                        $parent.append('<ul class="nav nav-group-sub" style="display: block;">'+result.Html+'</ul>');
                        $parent.addClass('nav-item-open');
                    }

                    if (level === '1') {
                        getMenuId(id, $dataRow['name'],undefined, $element);
                    } else {
                        getMenuId(undefined, $dataRow['name'], id, $element);
                    }

                }
            });
            
        } else {
            $parent.addClass('nav-item-open');
            $parent.find('.nav-group-sub:eq(0)').show();
            
            var $dataRow = JSON.parse($element.attr('data-row'));
            getMenuId($dataRow['id'], $dataRow['name'],undefined, $element);
        }
        
    }
    
    function getMenuId(id, menuName, categoryId, $elementRow, $elemet) {
        var id = (typeof id !== 'undefined') ? id : '0';
        var menuName = (typeof menuName !== 'undefined') ? menuName : '0';
        var categoryId = (typeof categoryId !== 'undefined') ? categoryId : '0';
        
        if (typeof $elemet !== 'undefined') {
            firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').removeClass('dv-twocol-f-selected');
            $($elemet).closest('li').addClass('dv-twocol-f-selected');
        }

        $.ajax({
            url: 'mdintranet/getSideBarContent',
            type: 'post',
            data: {id: id, categoryId: categoryId},
            dataType: 'JSON',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Уншиж байна түр хүлээнэ үү...',
                    boxed: true
                });
            },
            success: function (result) {
                var number = 1;
                var table_value = '';
                var read_class = '';
                var page = 0;
                var offset = 0;
                var sessionuserid = '<?php echo Ue::sessionUserId() ?>';
                var contextmenu = '';
                var iscreateduser = '';
                var collapsenum = 0;
                $.each(result, function (index1, results) {
                    
                    var poststar = '';
                    if (index1 !== 'paging') {
                        table_value += '<div class="date-filter" data-toggle="collapse" href="#collapse'+collapsenum+'" aria-expanded="true">' + index1 +
                                            '<i class="icon-arrow-down5"></i>'+
                                        '</div><div id="collapse'+collapsenum+'" class="collapse show">';
                        $.each(results, function (index, row) {
                            if(!isNaN(row.id)){
                                if(row.read === null || row.read === '0'){
                                    read_class = 'font-weight-bold';
                                } else {
                                    read_class = '';
                                }
                                
                                if(row.userid === sessionuserid){
                                    contextmenu = 'contextmenu-li-tag-<?php echo $this->uniqId ?>';
                                    iscreateduser = '<i class="icon-circle-small text-green"></i>&nbsp;';
                                } else {
                                    contextmenu = '';
                                    iscreateduser = '';
                                }

                                if(row.ispinpost === '1') {
                                   poststar = 'icon-star-full2 text-warning-300';
                                } else {
                                   poststar = 'icon-star-empty3 text-gray'; 
                                }

                                table_value += '<li class="dv-twocol-remove-li '+ contextmenu +'" data-id="'+ row.id +'">'
                                    + '<a href="javascript:void(0);" '
                                    + ' onclick="getNewsContent(' + row.id + ')"'
                                    + ' data-second-id="' + row.id + '" '
                                    + ' data-secondprocessid="1560141744604" '
                                    + 'data-secondtypecode="process" '
                                    + 'class="media d-flex align-items-center justify-content-center" id="edit' + row.id + '" '
                                    + 'data-rowdata="' + htmlentities(JSON.stringify(results[index]), 'ENT_QUOTES', 'UTF-8') + '">'
                                    + '<span class="line-height-0"></span><span class="mr-2"><i class="'+poststar+'"></i></span>'
                                    + '<div class="media-body">'
                                    + '<div id="removal'+ row.id +'" class="media-title d-flex flex-row '+ read_class +' mb-0" style="line-height: normal;font-size: 12px;">'
                                        + '<div>' + row.description + '</div>'
                                        + '<span class="ml-auto">'
                                            + iscreateduser + '<i class="icon-attachment text-gray"></i>'
                                        + '</span>'
                                    + '</div>'
                                    + '<span class="text-muted font-size-sm">' + row.tsag + '</span>'
                                    + '</div>' 
                                    + '</a></li>';
                            }
                        });
                        table_value += '</div>';
                    } else {
                        page = Math.ceil(results.totalcount / results.pagesize);
                        offset = results.offset;
                    }
                    collapsenum++;     
                    console.log(collapsenum);
                });
                
                if(page !== 0) {
                    var pagenationLi = '';
                    var pagenationActive = '';
                    var pagenationArrowLeftDisable = '';
                    var pagenationArrowRightDisable = '';
                    for(var i = 1; i < page + 1; i++) {
                        if(parseInt(offset) === i) {
                            pagenationActive = 'active';
                        } else {
                            pagenationActive = '';
                        }

                        pagenationLi += '<li class="page-item '+pagenationActive+'">'+
                                    '<a href="javascript:void(0);" onclick="changePage('+ offset +','+ i +','+id+','+categoryId+',\''+menuName+'\',\''+$elementRow+'\')" class="page-link">' + i + '</a>' +
                                  '</li>';
                    }

                    if(parseInt(offset) === 1) {
                        pagenationArrowLeftDisable = 'disabled';
                    } else {
                        pagenationArrowLeftDisable = '';
                    }
                    
                    if(parseInt(offset) === page || parseInt(offset) > page){
                        pagenationArrowRightDisable = 'disabled';
                    } else {
                        pagenationArrowRightDisable = '';
                    }

                    table_value += '<div class="pagination-bottom">' + 
                                        '<ul class="justify-content-center twbs-default pagination">' + 
                                            '<li class="page-item prev '+pagenationArrowLeftDisable+'">'+
                                                '<a href="javascript:void(0);" onclick="changePage('+ offset +','+ (parseInt(offset)-1) +','+id+','+categoryId+',\''+menuName+'\',\''+$elementRow+'\')" class="page-link">←</a>' +
                                            '</li>' + pagenationLi +
                                            '<li class="page-item next '+pagenationArrowRightDisable+'">' +
                                                '<a href="javascript:void(0);" onclick="changePage('+ offset +','+ (parseInt(offset)+1) +','+id+','+categoryId+',\''+menuName+'\',\''+$elementRow+'\')" class="page-link">→</a>' +
                                            '</li>' +
                                        '</ul>' +
                                    '</div>';
                }
                
                $("#all-content").empty().append(table_value).promise().done(function () {
//                    console.log($(this));
                    if (!firstcontentclicked) {
                        firstcontentclicked = 1;
                    }
//                    console.log($(this).find('li.dv-twocol-remove-li'));
                    $(this).find('li.dv-twocol-remove-li:eq(0) > a').trigger('click')
//                    ss.shift().trigger('click');
                });
                
                if (table_value === '') {
                    $("#all-content").append('<center class="text-grey mt-3">Тохирох үр дүн олдсонгүй</center>');
                }

                if (menuName !== null) {
                    $("#category_title").text(menuName);
                    $("#category_title").attr('data-row', $($elementRow).attr('data-row'));
                }
                Core.unblockUI();
            }
        });
    }

    function changePage(offset, newOffset, typeId, categoryId, menuName, $elementRow) {
        $.ajax({
            url: 'mdintranet/getSideBarContent',
            type: 'post',
            data: {id: typeId, categoryId: categoryId, offset: newOffset},
            dataType: 'JSON',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Уншиж байна түр хүлээнэ үү...',
                    boxed: true
                });
            },
            success: function (result) {
                var number = 1;
                var table_value = '';
                var read_class = '';
                var page = 0;
                var offset = 0;
                var sessionuserid = '<?php echo Ue::sessionUserId() ?>';
                var actionbuttons = '';
                var actioncheck = '';
                var contextmenu = '';
                var iscreateduser = '';
                $.each(result, function (index, row) {
                    if(!isNaN(row.id)){
                        if(row.read === null || row.read === '0'){
                            read_class = 'font-weight-bold';
                        } else {
                            read_class = '';
                        }
                    
                        if(row.userid === sessionuserid){
                            contextmenu = 'contextmenu-li-tag-<?php echo $this->uniqId ?>';
                            iscreateduser = '<i class="icon-cog3 text-gray"></i>&nbsp;';
                            actionbuttons = '<div class="mr-1" style="width:25px;height:26px;">'
                                    + '<button type="button" class="btn btn-sm btn-outline-info btn-icon trash-btn-hide pt-0 pb-0"  data-editactionbtn="' + row.id + '"><i class="fa fa-edit"></i></button></div>'
                                    + '<div class="ml-1" style="width:25px;height:26px;">'
                                    + '<button type="button" class="btn btn-sm btn-outline-danger btn-icon trash-btn-hide pt-0 pb-0" data-deleteactionbtn="' + row.id + '"><i class="fa fa-trash"></i></button></div>';
                            actioncheck = '';
                        } else {
                            contextmenu = '';
                            iscreateduser = '';
                            actioncheck = 'disabled';
                            actionbuttons = '';
                        }
                        
                        if(row.ispinpost === '1') {
                            table_value += '<li class="dv-twocol-remove-li '+ contextmenu +'" data-id="'+ row.id +'">'
                                + '<a href="javascript:void(0);" '
                                + ' onclick="getNewsContent(' + row.id + ')"'
                                + ' data-second-id="' + row.id + '" '
                                + ' data-secondprocessid="1560141744604" '
                                + 'data-secondtypecode="process" '
                                + 'class="media d-flex align-items-center justify-content-center" id="edit' + row.id + '" '
                                + 'data-rowdata="' + htmlentities(JSON.stringify(result[index]), 'ENT_QUOTES', 'UTF-8') + '">'
                                + '<span class="line-height-0"><!--<input class="check-box" onclick="ischeckedFnc(this)"  type="checkbox" '+actioncheck+'>--></span><span class="mr-2"><i class="icon-star-full2 text-warning-300"></i></span>'
                                + '<div class="media-body">'
                                + '<div id="removal'+ row.id +'" class="media-title d-flex flex-row '+ read_class +' mb-0" style="line-height: normal;font-size: 12px;">'
                                    + '<div>' + row.description + '</div>'
                                    + '<span class="ml-auto">'
                                        + iscreateduser + '<i class="icon-attachment text-gray"></i>'
                                    + '</span>'
                                + '</div>'
                                + '<span class="text-muted font-weight-bold font-size-sm">' + row.tsag + '</span>'
                                + '</div>' 
                                + '</a></li>';
                        } else {
                            if (number < 10) {
                                number = '0' + number;
                            }
                            table_value += '<li class="dv-twocol-remove-li '+ contextmenu +'" data-id="'+ row.id +'">'
                                    + '<a href="javascript:void(0);" '
                                    + ' onclick="getNewsContent(' + row.id + ')"'
                                    + ' data-second-id="' + row.id + '" '
                                    + ' data-secondprocessid="1560141744604" '
                                    + 'data-secondtypecode="process" '
                                    + 'class="media d-flex align-items-center justify-content-center" id="edit' + row.id + '" '
                                    + 'data-rowdata="' + htmlentities(JSON.stringify(result[index]), 'ENT_QUOTES', 'UTF-8') + '">'
                                    + '<span class="line-height-0"><!--<input class="check-box" type="checkbox" onclick="ischeckedFnc(this)" value="'+ row.id +'" '+actioncheck+'>--></span><span class="mr-2"><i class="icon-star-empty3 text-gray"></i></span>'
                                    + '<div class="media-body">'
                                    + '<div id="removal'+ row.id +'" class="media-title d-flex flex-row '+ read_class +' mb-0" style="line-height: normal;font-size: 12px;">'
                                        + '<div>' + row.description + '</div>'
                                        + '<span class="ml-auto">'
                                            + iscreateduser + '<i class="icon-attachment text-gray"></i>'
                                        + '</span>'
                                    + '</div>'
                                    + '<span class="text-muted font-weight-bold font-size-sm">' + row.tsag + '</span>'
                                    + '</div>' 
                                    + '</a></li>';
                            number++;
                        }
                    }
                    page = Math.ceil(row.totalcount / row.pagesize);
                    offset = row.offset;
                });
                
                if(page !== 0) {
                    var pagenationLi = '';
                    var pagenationActive = '';
                    var pagenationArrowLeftDisable = '';
                    var pagenationArrowRightDisable = '';
                    for(var i = 1; i < page + 1; i++) {
                        if(parseInt(offset) === i) {
                            pagenationActive = 'active';
                        } else {
                            pagenationActive = '';
                        }
                        pagenationLi += '<li class="page-item '+pagenationActive+'">'+
                                    '<a href="javascript:void(0);" onclick="changePage('+ offset +','+ i +','+typeId+','+categoryId+')" class="page-link">' + i + '</a>' +
                                  '</li>';
                    }

                    if(parseInt(offset) === 1) {
                        pagenationArrowLeftDisable = 'disabled';
                    } else {
                        pagenationArrowLeftDisable = '';
                    }

                    if(parseInt(offset) === page || parseInt(offset) > page){
                        pagenationArrowRightDisable = 'disabled';
                    } else {
                        pagenationArrowRightDisable = '';
                    }

                    table_value += '<div class="pagination-bottom">' + 
                                        '<ul class="justify-content-center twbs-default pagination">' + 
                                            '<li class="page-item prev '+pagenationArrowLeftDisable+'">'+
                                                '<a href="javascript:void(0);" onclick="changePage('+ offset +','+ (parseInt(offset)-1) +','+typeId+','+categoryId+',\''+menuName+'\',\''+$elementRow+'\')" class="page-link">←</a>' +
                                            '</li>' + pagenationLi +
                                            '<li class="page-item next '+pagenationArrowRightDisable+'">' +
                                                '<a href="javascript:void(0);" onclick="changePage('+ offset +','+ (parseInt(offset)+1) +','+typeId+','+categoryId+',\''+menuName+'\',\''+$elementRow+'\')" class="page-link">→</a>' +
                                            '</li>' +
                                        '</ul>' +
                                    '</div>';
                }
                $("#all-content").empty().append(table_value);
                
                if (table_value === '') {
                    $("#all-content").append('<center>Тохирох үр дүн олдсонгүй</center>');
                }

                if (menuName !== null) {
                   // $("#category_title").text(menuName);
                 //   $("#category_title").attr('data-row', $($elementRow).attr('data-row'));
                }
                Core.unblockUI();
            }
        });
    }

    function getNewsContent(id) {
        $.ajax({
            url: 'mdintranet/getSideBarContentDtl/' + id,
            type: 'post',
            dataType: 'JSON',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Уншиж байна түр хүлээнэ үү...',
                    boxed: true
                });
            },
            success: function (result) {
                console.log(result);
                $("#main-content > .page-header").show();
                $("#content_title").text(result.description);
                $("#created-user").text(result.name);
                $("#view-count").text(result.seenpercent + '%');
                $("#like-count").text(result.likecount);
                $("#dislike-count").text(result.dislikecount); 
                $("#printLink").attr("href", "mdintranet/printContentNewWindow/"+result.id);
                
                if(result.longdescr) {
                    var encodedStr = result.longdescr;
                    var parser = new DOMParser;
                    var dom = parser.parseFromString('<!doctype html><body>' + encodedStr,'text/html');
                    var decodedString = dom.body.textContent;
                    $("#body").empty().html(decodedString);
                } else {
                    $("#body").empty();
                }
                
                $("#total-comment").text(result.commentcount);
                $("#created-date").text(result.createddate);
                $("#attach_file_section").empty();
                $("#likebutton").attr("onclick", "like(" + result.id + ",'post',1)");
                $("#dislikebutton").attr("onclick", "like(" + result.id + ",'post',2)");
                $("#poll_title").text(result.description);
                $("#viewslist").empty();
                $("#post_id").val(result.id);
                
                if (!result.likecount) {
                    $("#like-count").text(0);
                }
                if (!result.dislikecount) {
                    $("#dislike-count").text(0);
                }

                //set post attritutes
                $('.communication-<?php echo $this->uniqId ?>').attr("data-post-id", result.id);

                //post attach file
                if (result.fileattach_multifile !== null) {
                    $.each(result.fileattach_multifile, function (index, row) {
                        var file_icon = '';
                        var file_color = '';
                        var file_view_type = '';
                        
                        switch (row.fileextension) {
                            case 'jpg' :
                            case 'jpeg' :
                            case 'png' :
                                file_icon = 'file-picture';
                                file_color = '#26a69a';
                                file_view_type = '<img src="' + row.physicalpath + '" style="width: 100%;">';
                                break;
                            case 'xls' :
                            case 'xlsx' :
                                file_icon = 'file-excel';
                                file_color = '#26a69a';
                                file_view_type = '<iframe src="https://view.officeapps.live.com/op/view.aspx?src=' + row.physicalpath + '" width="100%" height="550px" frameborder="0"></iframe>';
                                break;
                            case 'doc' :
                            case 'docx' :
                                file_icon = 'file-word';
                                file_color = '#26a69a';
                                file_view_type = '<iframe src="https://view.officeapps.live.com/op/view.aspx?src=' + row.physicalpath + '" width="100%" height="550px" frameborder="0"></iframe>';
                                break;
                            case 'pdf':
                                file_icon = 'file-pdf';
                                file_color = '#26a69a';
                                file_view_type = '<iframe id="file_viewer_<?php echo $this->uniqId ?>" src="<?php echo URL ?>api/pdf/web/viewer.html?file=<?php echo URL ?>' + row.physicalpath + '" frameborder="0" style="width: 100%; height: 551px;"></iframe>';
                                break;
                            default :
                                file_icon = 'stack';
                                file_color = '#f44336';
                        }

                        var html = '<div class="col-lg-4">' +
                                '<div class="card card-body">' +
                                '<div class="d-flex align-items-center">' +
                                '<i class="icon-' + file_icon + ' text-success-400 icon-2x mr-2" style="color: ' + file_color + ';"></i>' +
                                '<a href="javascript:void(0);" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;" data-toggle="modal" data-target="#modal_attach'+row.id+'">' + row.filename + '</a>' +
                                '<div id="modal_attach'+row.id+'" class="modal fade" tabindex="-1">' +
                                '<div class="modal-dialog">' +
                                '<div class="modal-content">' +
                                '<div class="modal-header">' +
                                '<h5 class="modal-title">' + row.filename + '</h5>' +
                                '<button type="button" class="close" data-dismiss="modal">×</button>' +
                                '</div>' +
                                '<div class="modal-body">' + file_view_type + '</div>' +
                                '<div class="modal-footer">' +
                                '<button type="button" class="btn btn-link closebtn" data-dismiss="modal">Хаах</button>' +
                                '<a href="' + row.physicalpath + '" class="btn btn-sm btn-primary">Татаж авах</a>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        $("#attach_file_section").empty().append(html);
                    });
                } else {
                    //$("#attach_file_section").hide();
                }

                //post view
                var viewusershtml = '';
                viewusershtml+= '<div class="alert bg-teal text-white alert-styled-left alert-styled-custom alert-dismissible text-center mb-0 p-0 pt-2 pb-2">'+
				'<span class="font-weight-semibold">Үзээгүй хэрэглэгчийн тоо : <b>'+result.unseencount+'</b></span>'+
				'</div><hr>';
                if (result.scl_posts_view_dv !== null) {
                    $.each(result.scl_posts_view_dv, function (index, row) {
                        viewusershtml += '<li class="media">' +
                                            '<div class="mr-3">' +
                                                '<img src="'+ row.picture +'" width="36" height="36" class="rounded-circle" alt="' + row.createdusername + '" onerror="onUserImgError(this);">' +
                                            '</div>' +
                                            '<div class="media-body">' +
                                                '<a href="javascript:void(0);" class="media-title font-weight-semibold">' + row.createdusername + '</a>' +
                                                '<span class="d-block text-muted font-size-sm">' + row.createddate + '</span>' +
                                            '</div>' +
                                        '</li>';
                    });
                    
                    $("#viewslist").append(viewusershtml).promise().done(function () {
                        //Core.initAjax();
                    });
                    
                }


                $("#modal_post_show_dislike").find('.modal-body').empty();
                $("#modal_post_show_like").find('.modal-body').empty();
                //post like, dislike
                var likeusershtml = '';
                if (result.scl_post_like_list !== null) {
//                    $("#modal_post_show_dislike").find('.modal-body').empty();
//                    $("#modal_post_show_like").find('.modal-body').empty();
                    $.each(result.scl_post_like_list, function (index, row) {
                        var a = '';
                        if (row.liketype === 'Like') {
                            a = 'up2 text-success';
                            likeusershtml += '<li class="media">' +
                                    '<div class="mr-3">' +
                                    '<img src="" width="36" height="36" class="rounded-circle" alt="" onerror="onUserImgError(this);">' +
                                    '</div>' +
                                    '<div class="media-body">' +
                                    '<a href="javascript:void(0);" class="media-title font-weight-semibold">' + row.name + '</a>' +
                                    '<span class="d-block text-muted font-size-sm">' + row.createddate + '</span>' +
                                    '</div>' +
                                    '<div class="ml-3 align-self-center"><i class="icon-thumbs-' + a + ' mr-1"></i></div>' +
                                    '</li>';
                            $("#modal_post_show_like").find('.modal-body').append(likeusershtml);
                        } else {
                            a = 'down2 text-danger';
                            likeusershtml += '<li class="media">' +
                                    '<div class="mr-3">' +
                                    '<img src="" width="36" height="36" class="rounded-circle" alt="" onerror="onUserImgError(this);">' +
                                    '</div>' +
                                    '<div class="media-body">' +
                                    '<a href="javascript:void(0);" class="media-title font-weight-semibold">' + row.name + '</a>' +
                                    '<span class="d-block text-muted font-size-sm">' + row.createddate + '</span>' +
                                    '</div>' +
                                    '<div class="ml-3 align-self-center"><i class="icon-thumbs-' + a + ' mr-1"></i></div>' +
                                    '</li>';
                            $("#modal_post_show_dislike").find('.modal-body').append(likeusershtml);
                        }
                        likeusershtml = '';
                    });
                }

                //зар мэдээ
                //хэлэлцүүлэг
                if (result.typeid === "2" || result.iscomment === "1") {
                    getComments(result.id);
                    $("#forum").show();
                    $("#forum").css("border", "0");
                    $("#commentsection").show();
                } else {
                    $("#forum").hide();
                    $("#commentsection").hide();
                }

                if (result.islike === "1") {
                    $("#likesection").show();
                    $("#dislikesection").show();
                } else {
                    console.log("avahgui");
                    $("#likesection").hide();
                    $("#dislikesection").hide();
                }

                //санал асуулга
                if (result.typeid === "3") {
                    getPollAttept(id);
                    $("#votingsection").show();
                    $(".intrahr").hide();
                    $("#body").hide();
                } else {
                    $("#votingsection").hide();
                    $(".intrahr").show();
                }

                //Файлын сан
                if (result.typeid === "4") {
                    getFileLibrary(result.id);
                    $("#filelibrary").show();
                    
                } else {
                    $("#filelibrary").hide();
                    
                }

                //Зургийн цомог
                if (result.typeid === "5") {
                    getPhotoLibrary(result.id);
                    $("#photolibrary").show();
                } else {
                    $("#photolibrary").hide();
                }
                
                
                var $mainClickedContent = $('body').find('a[data-second-id="'+ id +'"]');
                
                if (typeof $mainClickedContent.attr('content-view') === 'undefined' && $mainClickedContent.find('#removal' + id).hasClass('font-weight-bold')) {
                    $mainClickedContent.attr('content-view', '1');
                    var $textTmp = $('.dv-twocol-f-selected').find('span.badge').html();
                    $('.dv-twocol-f-selected').find('span.badge').html(parseInt($textTmp)-1);
                }
                
                $("#removal"+result.id).removeClass('font-weight-bold');
                Core.unblockUI();
            }
        });
       
        readContentChecker(id);
    }

    function getComments(postId) {
        $.ajax({
            url: 'mdintranet/getIntranetComments',
            type: 'post',
            data: {postId: postId},
            dataType: 'JSON',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Уншиж байна түр хүлээнэ үү...',
                    boxed: true
                });
            },
            success: function (result) {
                var $html = '';
                var cr_html = '';
                var noimage = "'assets/core/global/img/user.png'";

                $.each(result, function (index, row) {
                    $.each(row.scl_posts_comment_reply_dv, function (ii, rrow) {
                        if (row.id === rrow.commentid) {
                            cr_html += '<div class="media flex-column flex-md-row mt-1">' +
                                            '<div class="mr-md-3 mb-2 mb-md-0">' +
                                                '<a href="javascript:void(0);"><img src="' + rrow.picture + '" class="rounded-circle" width="36" height="36" alt="" onerror="onUserImgError(this);"></a>' +
                                            '</div>' +
                                            '<div class="media-body">' +
                                                '<div class="media-title d-flex flex-row align-items-center">' +
                                                    '<a href="javascript:void(0);" class="font-weight-bold">' + rrow.createdusername + '</a>' +
                                                    '<span class="font-size-sm text-muted ml-sm-2 mb-2 mb-sm-0 d-block d-sm-inline-block mr-3">' + rrow.createddate + '</span>' +
                                                    '<ul id="ul' + rrow.id + '" data-comment-id="' + rrow.id + '" class="list-inline font-size-sm mb-0" data-comment-user="' + rrow.createduserid + '">' +         
                                                        '<li class="list-inline-item mr-2">' +
                                                            '<a href="javascript:void(0);" onclick="like(' + rrow.id + ', 3, 1)"  ><i class="icon-thumbs-up2" style="top:-2px;"></i></a>' +
                                                            '<a href="javascript:void(0);" onclick="getLike(' + rrow.id + ',' + rrow.countreplylike + ', 1, 3)" data-toggle="modal" data-target="#modal_default_show_like"><span class="badge badge-flat badge-pill text-black" style="color: black; margin-left: 2px;">' + rrow.countreplylike + '</span></a>' +
                                                        '</li>' +
                                                        '<li class="list-inline-item mr-1">'+
                                                            '<a href="javascript:void(0);" onclick="like(' + rrow.id + ',3,2)"><i class="icon-thumbs-down2"></i></a>' +
                                                            '<a href="javascript:void(0);" onclick="getLike(' + rrow.id + ',' + rrow.countreplydislike + ', 2, 3)" data-toggle="modal" data-target="#modal_default_show_dislike"><span class="badge badge-flat badge-pill text-black" style="color: black; margin-left: 2px;">' + rrow.countreplydislike + '</span></a>' +
                                                        '</li>' +
                                                        '<!--<li class="list-inline-item"><a href="javascript:void(0);" class="bgbtn" id="commentnested' + rrow.commentid + '" data-comment-id="' + rrow.commentid + '" data-comment-user="' + rrow.createduserid + '" onclick="replyComment_<?php echo $this->uniqId ?>(' + rrow.commentid + ', 1, this)"><i class="icon-undo2 mr-1"></i>Хариулах</a></li>-->' +
                                                    '</ul>' +
                                                '</div>' +
                                                '<p>' + rrow.replytext + '</p>' +
                                            '</div>' +
                                        '</div>';
                        }
                    });
                    
                    $html += '<div class="media flex-column flex-md-row mt-1">' +
                                '<div class="mr-md-3 mb-2 mb-md-0">' +
                                    '<a href="javascript:void(0);"><img src="' + row.picture + '" class="rounded-circle" width="36" height="36" alt="" onerror="onUserImgError(this);"></a>' +
                                '</div>' +
                                '<div class="media-body">' +
                                    '<div class="media-title d-flex flex-row align-items-center">' +
                                        '<a href="javascript:void(0);" class="font-weight-bold">' + row.name + '</a>' +
                                        '<span class="font-size-sm text-muted ml-sm-2 mb-2 mb-sm-0 d-block d-sm-inline-block mr-3">' + row.createddate + '</span>' +
                                        '<ul id="ul' + row.id + '" data-comment-id="' + row.id + '" data-comment-user="' + row.userid + '" class="list-inline font-size-sm mb-0">' +
                                            '<li class="list-inline-item mr-2">'+
                                                '<a href="javascript:void(0);" onclick="like(' + row.id + ',2,1)" ><i class="icon-thumbs-up2" style="top:-2px;"></i></a>' +
                                                '<a href="javascript:void(0);" onclick="getLike(' + row.id + ',' + row.countcommentlike + ',1,2)" data-toggle="modal" data-target="#modal_default_show_like">'+
                                                    '<span class="badge badge-flat badge-pill text-black" style="color: black; margin-left: 2px;">' + row.countcommentlike + '</span>'+
                                                '</a>' +
                                            '</li>' +
                                            '<li class="list-inline-item mr-1">'+
                                                '<a href="javascript:void(0);" onclick="like(' + row.id + ',2,2)"><i class="icon-thumbs-down2"></i></a>' +
                                                '<a href="javascript:void(0);" onclick="getLike(' + row.id + ',' + row.countcommentdislike + ',2,2)" data-toggle="modal" data-target="#modal_default_show_dislike">'+
                                                    '<span class="badge badge-flat badge-pill text-black" style="color: black; margin-left: 2px;">' + row.countcommentdislike + '</span>'+
                                                '</a>' +
                                            '</li>' +
                                            '<li class="list-inline-item"><a href="javascript:void(0);" id="comment' + row.id + '" data-reply-type="reply" data-comment-id="' + row.id + '" data-comment-user="' + row.userid + '" onclick="replyComment_<?php echo $this->uniqId ?>(' + row.id + ', 0, this)" class="bgbtn"><i class="icon-undo2 mr-1"></i>Хариулах</a></li>' +   
                                        '</ul>' +
                                    '</div>' +
                                    '<p>' + row.commenttxt + '</p>'
                                    + cr_html +
                                    '<div id="gg'+row.id+'" class="replycomment-<?php echo $this->uniqId ?>"></div>' +
                                '</div>' +
                            '</div>';
                    cr_html = '';
                });
                $("#commentbody-<?php echo $this->uniqId ?>").empty().append($html);
            }
        });
    }

    function replyComment_<?php echo $this->uniqId ?>(id, type, element) {
       
        var $mainSelector = $(element).closest('ul');
        var comment_id = $mainSelector.attr('data-comment-id');
        var user_id = $mainSelector.attr('data-comment-user');
        
        $('body').find('.intranet-<?php echo $this->uniqId ?>').find('.replycomment-<?php echo $this->uniqId ?>').empty();
        
        var $subHtml = '<div class="subaddcomment-<?php echo $this->uniqId ?> mb-2 mt-2">' +
                            '<textarea data-comment-id="' + comment_id + '" data-comment-user="' + user_id + '" rows="3" cols="3" class="form-control" placeholder="Саналаа бичээд ENTER дарна уу..." style="margin-top: 0px; margin-bottom: 0px; height: 76px;" required></textarea>' +
                        '</div>' +
                        '<button type="button" id="save_comment" onclick="saveComment(this, \'reply\')" class="btn bg-pink mb-3">Санал бичих</button>';
        console.log('com' + comment_id);
        console.log('userid' + user_id);
        $("#gg" + id).empty().append($subHtml);
//        $("#save_comment").html('Хариу бичих');
    }

    function saveComment(element, type) {
        
        var commentId = '0';
        var userId = '0';
        var text = $(element).closest('div.card-body').find('textarea').val();
        var postId = $('.communication-<?php echo $this->uniqId ?>').attr("data-post-id");
        
        if (type === 'reply') {
            var $mainSelector = $(element).closest('.media-body');
            console.log($mainSelector);
            commentId = $mainSelector.attr('data-comment-id');
            userId = $mainSelector.attr('data-comment-user');
            text = $(element).closest('div.replycomment-<?php echo $this->uniqId ?>').find('textarea').val();
            commentId= $(element).closest('div.replycomment-<?php echo $this->uniqId ?>').find('textarea').attr('data-comment-id');
            userId= $(element).closest('div.replycomment-<?php echo $this->uniqId ?>').find('textarea').attr('data-comment-user');
            console.log('replyy = ' + text);
            console.log('commentId' + commentId);
            console.log('userid' + userId);
        }
        
//        return ;
        
        if (text === '') {
            PNotify.removeAll();
            new PNotify({
                title: 'Анхаар',
                text: 'Сэтгэгдэл бичих хэсэгт утга оруулна уу',
                type: 'error',
                sticker: false
            });
        } else {
            $.ajax({
                url: 'mdintranet/saveIntanetComment',
                type: 'post',
                data: {postId: postId, commentType: type, commentText: text, commentId: commentId, userId: userId},
                dataType: 'JSON',
                beforeSend: function () {
                    Core.blockUI({
                        message: 'Уншиж байна түр хүлээнэ үү...',
                        boxed: true
                    });
                },
                success: function (result) {
                    if (result) {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Амжилттай',
                            text: 'Амжилттай хадгалагдлаа',
                            type: 'success',
                            sticker: false
                        });
                        $("#comment_writing").val('');
                        $("#comment_writing1").val('');
                        $('body').find('.intranet-<?php echo $this->uniqId ?>').find('.replycomment-<?php echo $this->uniqId ?>').empty();
                        getNewsContent(postId);
                    } else {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Алдаа',
                            text: 'Алдаа',
                            type: 'error',
                            sticker: false
                        });
                    }
                    Core.unblockUI();
                }
            });
        }
    }

    function like(id, post, liketype) {
        $.ajax({
            url: 'mdintranet/saveIntranetLike',
            type: 'post',
            data: {postId: id, likeTypeId: liketype, targetType: post},
            dataType: 'JSON',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Уншиж байна түр хүлээнэ үү...',
                    boxed: true
                });
            },
            success: function (result) {
                var postid = $("#post_id").val();
                getNewsContent(postid);
                if (result) {
                    
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Алдаа',
                        text: 'Алдаа',
                        type:'danger',
                        sticker: false
                    });
                }
                Core.unblockUI();
            }
        });
    }

    function getLike(id, count, type, post) {
        $("#modal_default_show_dislike").find('.modal-body').empty();
        $("#modal_default_show_like").find('.modal-body').empty();
        //type 1 like ,type 2 dislike
        var liketype;
        if (type === 1) {
            liketype = 'Like';
        } else if (type === 2) {
            liketype = 'Dislike';
        }

        if (count !== 0) {
            $.ajax({
                url: 'mdintranet/getLikeInformation',
                type: 'post',
                data: {commentId: id, targetType: post},
                dataType: 'JSON',
                success: function (result) {
                    var likehtml = '';
                    var dislikehtml = '';
                    var noimage = "'assets/core/global/img/user.png'";
                    if (post === 2) {
                        $.each(result.scl_comment_like_list, function (i, data) {
                            var a = '';
                            if (data.liketype === 'Like') {
                                a = 'up2 text-success';
                                likehtml += '<li class="media">' +
                                        '<div class="mr-3">' +
                                        '<img src="" width="36" height="36" class="rounded-circle" alt="" onerror="onUserImgError(this);">' +
                                        '</div>' +
                                        '<div class="media-body">' +
                                        '<a href="javascript:void(0);" class="media-title font-weight-semibold">' + data.name + '</a>' +
                                        '<span class="d-block text-muted font-size-sm">' + data.createddate + '</span>' +
                                        '</div>' +
                                        '<div class="ml-3 align-self-center"><i class="icon-thumbs-' + a + ' mr-1"></i></div>' +
                                        '</li>';
                            } else {
                                a = 'down2 text-danger';
                                dislikehtml += '<li class="media">' +
                                        '<div class="mr-3">' +
                                        '<img src="" width="36" height="36" class="rounded-circle" alt="" onerror="onUserImgError(this);">' +
                                        '</div>' +
                                        '<div class="media-body">' +
                                        '<a href="javascript:void(0);" class="media-title font-weight-semibold">' + data.name + '</a>' +
                                        '<span class="d-block text-muted font-size-sm">' + data.createddate + '</span>' +
                                        '</div>' +
                                        '<div class="ml-3 align-self-center"><i class="icon-thumbs-' + a + ' mr-1"></i></div>' +
                                        '</li>';
                            }
                        });
                    } else {
                        $.each(result.scl_comment_reply_like_list, function (i, data) {
                            var a = '';
                            if (data.liketype === 'Like') {
                                a = 'up2 text-success';
                                likehtml += '<li class="media">' +
                                        '<div class="mr-3">' +
                                        '<img src="" width="36" height="36" class="rounded-circle" alt="">' +
                                        '</div>' +
                                        '<div class="media-body">' +
                                        '<a href="javascript:void(0);" class="media-title font-weight-semibold">' + data.name + '</a>' +
                                        '<span class="d-block text-muted font-size-sm">' + data.createddate + '</span>' +
                                        '</div>' +
                                        '<div class="ml-3 align-self-center"><i class="icon-thumbs-' + a + ' mr-1"></i></div>' +
                                        '</li>';
                            } else {
                                a = 'down2 text-danger';
                                dislikehtml += '<li class="media">' +
                                        '<div class="mr-3">' +
                                        '<img src="" width="36" height="36" class="rounded-circle" alt="">' +
                                        '</div>' +
                                        '<div class="media-body">' +
                                        '<a href="javascript:void(0);" class="media-title font-weight-semibold">' + data.name + '</a>' +
                                        '<span class="d-block text-muted font-size-sm">' + data.createddate + '</span>' +
                                        '</div>' +
                                        '<div class="ml-3 align-self-center"><i class="icon-thumbs-' + a + ' mr-1"></i></div>' +
                                        '</li>';
                            }
                        });
                    }
                    $("#modal_default_show_like").find('.modal-body').empty().append(likehtml);
                    $("#modal_default_show_dislike").find('.modal-body').empty().append(dislikehtml);
                }
            });
        }
    }

    function readContentChecker(id) {
        $.ajax({
            url: 'mdintranet/saveReadingPostInformation',
            type: 'post',
            data: {postId: id},
            dataType: 'JSON',
            success: function (result) {

            }
        });
    }

    function getPhotoLibrary(id) {
        $("#photolibrarybody").empty();
        var noimage = "'assets/core/global/img/noimage.png'";
        $.ajax({
            url: 'mdintranet/getIntranetPhotoLibrary',
            type: 'post',
            data: {postId: id},
            dataType: 'JSON',
            success: function (result) {
                var html = '';
                $.each(result.fileattach_multifile, function (i, data) {
                    html += '<div class="col-xl-3 col-sm-6 mb-3">' +
                            '<div class="galleryfancy">' +
                            '<a data-fancybox="gallery" data-caption="' + data.filename + '" href="' + data.physicalpath + '"><img class="card-img" width="96" height="160" src="' + data.physicalpath + '" onerror="onUserImgError(this);"></a>' +
                            '<span class="card-img-actions-overlay card-img">' +
                            '<i class="icon-plus3 icon-2x"></i>' +
                            '</span>' +
                            '</a>' +
                            '</div>' +
                            '</div>';
                    $("#photolibrarybody").empty().append(html);
                });
            }
        });
    }

    function getFileLibrary(id) {
        $("#filelibrarybody").empty();
        $.ajax({
            url: 'mdintranet/getIntranetFileLibrary',
            type: 'post',
            data: {postId: id},
            dataType: 'JSON',
            success: function (result) {
                var html = '';
                var modal = '';
                var i = 0;
                $.each(result.fileattach_multifile, function (i, data) {
                    var file_icon = '';
                    var file_color = '';
                    var file_view_type = '';
                    switch (data.fileextension) {
                        case 'jpg' :
                        case 'jpeg' :
                        case 'png' :
                            file_icon = 'file-picture';
                            file_color = '#26a69a';
                            file_view_type = '<img src="' + data.physicalpath + '" style="width: 100%; height: 80vh;">';
                            break;
                        case 'xls' :
                        case 'xlsx' :
                            file_icon = 'file-excel';
                            file_color = '#26a69a';
                            file_view_type = '<iframe src="https://view.officeapps.live.com/op/view.aspx?src=' + data.physicalpath + '" width="100%" height="550px" frameborder="0"></iframe>';
                            break;
                        case 'doc' :
                        case 'docx' :
                            file_icon = 'file-word';
                            file_color = '#26a69a';
                            file_view_type = '<iframe src="https://view.officeapps.live.com/op/view.aspx?src=' + data.physicalpath + '" width="100%" height="550px" frameborder="0"></iframe>';
                            break;
                        case 'pdf':
                            file_icon = 'file-pdf';
                            file_color = '#26a69a';
                            file_view_type = '<iframe id="file_viewer_<?php echo $this->uniqId ?>" src="<?php echo URL ?>api/pdf/web/viewer.html?file=<?php echo URL ?>' + data.physicalpath + '" frameborder="0" style="width: 100%; height: 551px;"></iframe>';
                            
                            break;
                        default :
                            file_icon = 'stack';
                            file_color = '#f44336';
                    }
                    
                    
                    html += '<div class="col-lg-4">' +
                            '<div class="card card-body">' +
                            '<div class="d-flex align-items-center">' +
                            '<i class="icon-' + file_icon + ' text-success-400 icon-3x mr-2" style="color: ' + file_color + ';"></i>' +
                            '<div class="d-flex flex-column">' +
                            '<a href="javascript:void(0);" onclick="dataViewFileViewer(this, \'1\', \''+data.fileextension+'\', \'' + data.physicalpath + '\', \'<?php echo URL ?>'+ data.physicalpath + '\', \'undefined\');" class="text-default font-weight-bold media-title font-weight-semibold mb-0 line-height-normal font-size-13 text-justify word-break-all">' + data.filename + '</a>' +
                            '<span class="text-muted font-weight-bold font-size-sm mr-3 mt5"><i class="icon-file-text mr-1"></i> ' + data.filesize + ' KB</span>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>';

                    $("#filelibrarybody").empty().append(html);
                });
            }
        });
    }

    function getVoting(id) {
        $.ajax({
            url: 'mdintranet/getIntranetVoting',
            type: 'post',
            data: {postId: id},
            dataType: 'JSON',
            success: function (result) {
                var question_html = '';
                var answer_html = '';
                $.each(result.scl_posts_sanal_asuult_list, function (i, question) {
                    console.log(question);
                    console.log(question.answertypename);
                    if(question.answertypename === 'Combo') {
                        answer_html += '<select class="form-control" name="'+question.id+'">' +
                                       '<option value="" disabled selected>Сонгох</option>';
                            $.each(question.scl_posts_sanal_hariult_list, function (j, answer) {
                                answer_html += '<option value="' + answer.id + '">'+answer.answertxt+'</option>';
                            });
                        answer_html += '</select>';
                    }
                    
                    if(question.anwertypename === 'Radio'){
                        $.each(question.scl_posts_sanal_hariult_list, function (j, answer) {
                            answer_html += '<tr>' +
                                '<td style="width: 50px"><div class="uniform-checker">' +
                                '<a href="javascript:;" onclick="save(' + answer.id + ');"><span id="answercheck' + answer.id + '" class="">' +
                                '<input type="checkbox" id="answer' + answer.id + '" name="' + answer.pollquestionid + '" value=' + answer.id + ' class="form-input-styled"></span></a></div></td>' +
                                '<td>' +
                                '<a href="javascript:void(0);" onclick="save();" class="text-default">' +
                                '<div>' +
                                answer.answertxt +
//                                '<span class="text-muted ml-3">optional text байна</span>' +
                                '</div>' +
                                '</a>' +
                                '</td>' +
                                '</tr>';
                        });
                    }

                    question_html += '<div class="card mb-2 p-3">' +
                            '<div class="card-header">' +
                            ' <h6 class="card-title">' +
                            '<a class="text-default" data-toggle="collapse" href="#question' + question.id + '" aria-expanded="true">' + question.questiontxt + '</a>' +
                            '</h6>' +
                            '</div>' +
                            '<div id="question' + question.id + '" class="collapse show" style="">' +
                            '<div class="table-responsive">' +
                            '<table class="table">' +
                            '<tbody>' + answer_html +
                            '</tbody>' +
                            '</table>' +
                            '</div>' +
                            '</div>' +
                            '</div>';
                    answer_html = '';
                    $("#questionbody").empty().append(question_html);
                });
            }
        });
    }

    function save(answer) {
        $("#answercheck" + answer).toggleClass('checked');
        $("#answer" + answer).prop('checked', true);
    }

    function savePoll() {
        var test = document.getElementById("main_form").elements;
        var postId = $("#post_id").val();
        var polldata = [];
        for (i = 0; i < test.length; i++) {
            if ((test[i].nodeName === "INPUT" && test[i].checked === true) || test[i].nodeName === "SELECT") {
                polldata.push({post_id: postId, question_id: test[i].name, answer_id: test[i].value});
            }
        }
        
        $.ajax({
            url: 'mdintranet/saveIntranetPoll',
            type: 'post',
            data: {polldata: polldata},
            success: function (result) {
                var res = JSON.parse(result);
                if (res.status === 'success') {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Амжилттай',
                        text: 'Амжилттай хадгалагдлаа',
                        type: 'success',
                        sticker: false
                    });
                    $("#questionbody").empty().append('<center><h2 class="text-success"> Амжилттай! Таны өгсөн саналыг хүлээн авлаа </h2></center>');
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Анхаар',
                        text: 'Алдаа гарлаа',
                        type: 'error',
                        sticker: false
                    });
                }
            }
        });
    }
    
    function ischeckedFnc(element) {
        if (element.checked) {
            $(element).closest('li').addClass('checked-data');
            $("#rowsDeleteButton").show();
        } else {
            $(element).closest('li').removeClass('checked-data');
        }
        
        var mainSelector = $("#all-content").find('.checked-data');
        console.log(mainSelector.length);
        if(mainSelector.length === 0 || mainSelector.length <= 1){
            $("#rowsDeleteButton").hide();
        }
    }
    
    function postSelect(){
        var test = document.getElementById("all-content-form").elements;
        var polldata = [];
        var mainSelector = $("#all-content").find('.checked-data');
        
        mainSelector.each(function (index, row) {
            var rowVal = $(row).attr('data-id');
            polldata.push({'id':rowVal});
        });
        
        if (polldata) {
            runIsMultiBusinessProcess(1564662242403286, 1568017432968, true, polldata, function () {
                mainSelector.each(function (index, row) {
                    $(row).fadeOut(1000, function () {
                        $(this).remove();
                    });
                });
            });
        }
    }

    function getPollAttept(id) {
        $.ajax({
            url: 'mdintranet/getIntranetPollAttept',
            type: 'post',
            data: {postId: id},
            dataType: 'JSON',
            success: function (result) {
                if (result) {
                    $.ajax({
                        url: 'mdintranet/getIntranetPollResult',
                        type: 'post',
                        data: {postId: id},
                        dataType: 'JSON',
                        success: function (result) {
                            console.log(id);
                            console.log(result);
                            $("#questionbody").empty().append('<center><h4 class="text-primary">  Та энэ санал асуулгыг өмнө нь өгсөн байна. Доорх үр дүнтэй танилцана уу </h4></center>');
                            
                            var html = '<center><div class="col-6">' + 
                                        '<div class="table-responsive mb-4">' +
                                            '<table class="table table-striped table-borderless">' +
                                                '<tbody>' +
                                                    '<tr>' +
                                                        '<td class="text-right text-gray">Нийт санал:</td>' +
                                                        '<td class="font-weight-bold">'+result.votedcount+' санал</td>' +
                                                    '</tr>' +
                                                    '<tr>' +
                                                        '<td class="text-right text-gray">Нууцлалын төрөл:</td>' +
                                                        '<td class="font-weight-bold">'+result.privacytype+'</td>' +
                                                    '</tr>' +
                                                    '<tr>' +
                                                        '<td class="text-right text-gray">Хариултын төрөл:</td>' +
                                                        '<td class="font-weight-bold">'+result.answertypename+'</td>' +
                                                    '</tr>' +
                                                    '<tr>' +
                                                        '<td class="text-right text-gray">Дуусах хугацаа:</td>' +
                                                        '<td class="font-weight-bold">'+result.enddate+'</td>' +
                                                    '</tr>' +
                                                    '<tr>' +
                                                        '<td class="text-right text-gray">Үлдсэн хоног:</td>' +
                                                        '<td class="font-weight-bold">'+result.leftdays+'</td>' +
                                                    '</tr>' +
                                                '</tbody>' +
                                            '</table>' +
                                        '</div>';    
                                
                            
                                
                            var tableHtml = '';    
                            var num = 0;
                            var modalHtml = '';
                            $.each(result.scl_answered_count_list, function (index, row) {
                                num++;
                                
                                tableHtml += '<div class="poll_result">' + 
                                        '<div class="poll-box">' +  
                                            '<div class="d-flex">' + 
                                                '<div class="mr-1">' + 
                                                    '<h5 class="mb-0 font-weight-bold">'+row.answertxt+'</h5>' + 
                                                '</div>' + 
                                                '<div class="">' + 
                                                    '<h5 class="pt6 mb-0 text-gray text-uppercase font-size-12 font-weight-bold">('+row.countedfor+' саналтай)&nbsp;&nbsp;<a href="javascript:;" data-toggle="modal" data-target="#modal_poll_people'+row.answerid+'">Оролцогчид</a></h5>' + 
                                                '</div>' + 
                                                '<div class="ml-auto">' + 
                                                    '<h5 class="mb-0 font-weight-bold">'+row.countforpercent+'%</h5>' + 
                                                '</div>' + 
                                            '</div>' + 
                                            '<div class="progress mb-3" style="height: 0.625rem;">' + 
                                                '<div class="progress-bar progress-bar-striped bg-warning" style="width: '+row.countforpercent+'%">' + 
                                                    '<span class="sr-only">'+row.countforpercent+'% Complete</span>' + 
                                                '</div>' + 
                                            '</div>' + 
                                        '</div>' + 
                                    '</div>';      
                                    
                                
                                var userHtml = '';
                                $.each(row.scl_answered_name_get_list, function(j, drow) {
                                    userHtml += '<li class="media">' +
                                            '<div class="mr-3">' +
                                                '<img src="'+drow.picture+'" width="36" height="36" class="rounded-circle" alt="1" onerror="onUserImgError(this);">' +
                                            '</div>' +
                                            '<div class="media-body">' +
                                                '<a href="javascript:void(0);" class="media-title font-weight-semibold">' + drow.name + '</a>' +
                                                '<span class="d-block text-muted font-size-sm"></span>' +
                                            '</div>' +
                                        '</li>';
                                });
                                
                                modalHtml = '<div id="modal_poll_people'+row.answerid+'" class="modal fade" tabindex="-1">' +
                                                    '<div class="modal-dialog mini-dialog">' +
                                                        '<div class="modal-content">' +
                                                            '<div class="modal-header">' +
                                                                '<h5 class="modal-title">Санал өгсөн хүмүүс</h5>' +
                                                                '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
                                                            '</div>' +
                                                            '<div class="modal-body">' + 
                                                                    userHtml+
                                                            '</div>' +
                                                            '<div class="modal-footer">' +
                                                                '<button type="button" class="btn btn-primary" data-dismiss="modal">Хаах</button>' +
                                                            '</div>' +
                                                        '</div>' +
                                                    '</div>' +
                                                '</div>';
                                
                                $("#pollmodal").append(modalHtml);
                            });
                            
                            html += tableHtml + '</div></center>'; 
                            
                            $("#questionbody").append(html);    
                            
                        }
                    });
                } else {
                    getVoting(id);
                }
            }
        });
    }

    function addProcess() {
        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: {
                metaDataId: 1567154435267,
                isDialog: false,
                isHeaderName: false,
                isBackBtnIgnore: 1,
                callerType: 'dv',
                openParams: '{"callerType":"dv","afterSaveNoAction":true,"afterSaveNoActionFnc":"panelDvRefreshSecondList(1567578460751687)"}'
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {
                console.log(data.Html);
                $("#body").empty().append(data.Html);
                Core.unblockUI();
//                    viewProcess_1567578460751687.empty().append(data.Html).promise().done(function () {
//                        panelDv_1567578460751687.find('#dv-twocol-view-title').text('Нэмэх');
//                        viewProcess_1567578460751687.find('.bp-btn-back, .bpTestCaseSaveButton').remove();
//                        Core.initBPAjax(viewProcess_1567578460751687);
//                        Core.unblockUI();
//                    });
            },
            error: function () {
                alert('Error');
            }
        });
    }
    
    function addFolder(element, typeId) {
        
        var processId = 1567584412244;
        if (processId) {

            var $dialogName = 'dialog-businessprocess-' + processId;
            if (!$('#' + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);

            var fillDataParams = '',
                fillDataParams = 'typeId=' + typeId;
        
            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: processId,
                    isDialog: true,
                    isHeaderName: false,
                    isBackBtnIgnore: 1,
                    callerType: 'dv',
                    openParams: '{"callerType":"dv","afterSaveNoAction":true,"afterSaveNoActionFnc":"panelDvRefreshSecondList(<?php echo $this->uniqId; ?>)"}',
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

                    var $processForm = $('#wsForm', '#' + $dialogName),
                            processUniqId = $processForm.parent().attr('data-bp-uniq-id');

                    var buttons = [
                        {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                            if (window['processBeforeSave_' + processUniqId]($(e.target))) {

                                $processForm.validate({
                                    ignore: '',
                                    highlight: function (element) {
                                        $(element).addClass('error');
                                        $(element).parent().addClass('error');
                                        if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                            $processForm.find("div.tab-pane:hidden:has(.error)").each(function (index, tab) {
                                                var tabId = $(tab).attr('id');
                                                $processForm.find('a[href="#' + tabId + '"]').tab('show');
                                            });
                                        }
                                    },
                                    unhighlight: function (element) {
                                        $(element).removeClass('error');
                                        $(element).parent().removeClass('error');
                                    },
                                    errorPlacement: function () {
                                    }
                                });

                                var isValidPattern = initBusinessProcessMaskEvent($processForm);

                                if ($processForm.valid() && isValidPattern.length === 0) {
                                    $processForm.ajaxSubmit({
                                        type: 'post',
                                        url: 'mdwebservice/runProcess',
                                        dataType: 'json',
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
                                                $dialog.dialog('close');
                                                console.log(responseData.resultData);
                                                
                                                var $row = responseData.resultData;
                                                var $rowJson = htmlentities(JSON.stringify($row), 'ENT_QUOTES', 'UTF-8');
                                                
                                                var $appendTag = '<li class="nav-item subcat" rowdata="' + $rowJson + '">';
                                                        $appendTag += '<a href="javascript:;" ';
                                                            $appendTag += 'data-row="' + $rowJson + '"';
                                                            $appendTag += 'onclick="getSubMenuIntranet_<?php echo $this->uniqId ?>(this, '+ $row['id'] +' , \'2\', \'\')" ';
                                                            $appendTag += 'class="nav-link v2 media d-flex align-items-center justify-content-center">';
                                                            $appendTag += '<div class="media-body">';
                                                                $appendTag += $row['name'];
                                                            $appendTag += '</div>';
                                                            $appendTag += '<div class="icon-trash-fix">';
                                                                $appendTag += '<button type="button" onclick="deleteFolder('+ $row['id'] +', this)" class="btn btn-sm text-red btn-icon trash-btn-hide pt-0 pb-0"><i class="fa fa-trash del"></i></button>';
                                                            $appendTag += '</div>';
                                                        $appendTag += '</a>';
                                                    $appendTag += '</li>';
                                                    
                                                    $($appendTag).insertBefore(element);
                                            }
                                            Core.unblockUI();
                                        },
                                        error: function () {
                                            alert("Error");
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
                        $dialog.dialogExtend("maximize");
                    }

                    setTimeout(function () {
                        $dialog.dialog('open');
                        Core.unblockUI();
                    }, 1);
                },
                error: function () {
                    alert('Error');
                }
            });
        }
    }
    
    function deleteFolder(categoryId, element) {
        if (categoryId) {
            runIsOneBusinessProcess(1567592452382272, 1567584413074, true, {id: categoryId}, function () {
                $(element).closest('li').fadeOut(1000, function () {
                    $(this).remove();
                });
            });
        }
    }
    
    function reloadSidebar_<?php echo $this->uniqId ?>() {
        $.ajax({
            url: 'mdintranet/reloadSidebar', 
            type: 'post',
            dataType: 'json',
            data: {
                bookId: '1',
                rowdata: JSON.parse($('.dv-twocol-f-selected').attr('rowdata')),
                uniqId: '<?php echo $this->uniqId ?>'
            },
//            beforeSend: function () {
//                Core.blockUI({
//                    textOnly: true,
//                    message: '<i class="icon-spinner4 spinner" style=" width: inherit; height: inherit; "></i>',
//                    target: '.side'
//                });
//            },
            success: function (data) {
                $('.side').empty().append(data).promise().done(function () {
//                    console.log(data);
//                    Core.unblockUI('.side');
                });
            }
        });
    }
    
    function search() {
        var title = $("#title").val();
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var user = $("#user").val();
        $.ajax({
            url: 'mdintranet/searchContent',
            type: 'post',
            dateType: 'JSON',
            beforeSend: function () {
                    Core.blockUI({
                        message: 'Хайж байна...',
                        boxed: true
                    });
                },
            data: {title: title, startDate : start_date, endDate : end_date, createdUser : user},
            success: function (result) {
                var result1 = JSON.parse(result);
                var number = 1;
                var table_value = '';
                var read_class = '';
                var sessionuserid = '<?php echo Ue::sessionUserId() ?>';
                var actionbuttons = '';
                var actioncheck = '';
                $.each(result1, function (index, row) {
                    if (number < 10) {
                        number = '0' + number;
                    }
                    if(row.read === null || row.read === '0'){
                        read_class = 'font-weight-bold';
                    } else {
                        read_class = '';
                    }
                    
                    if(row.userid === sessionuserid){
                            actionbuttons = '<div class="mr-1" style="width:25px;height:26px;">'
                                    + '<button type="button" class="btn btn-sm btn-outline-info btn-icon trash-btn-hide pt-0 pb-0"  data-editactionbtn="' + row.id + '"><i class="fa fa-edit"></i></button></div>'
                                    + '<div class="ml-1" style="width:25px;height:26px;">'
                                    + '<button type="button" class="btn btn-sm btn-outline-danger btn-icon trash-btn-hide pt-0 pb-0" data-deleteactionbtn="' + row.id + '"><i class="fa fa-trash"></i></button></div>';
                            actioncheck = '';
                        } else {
                            actioncheck = 'disabled';
                            actionbuttons = '';
                        }
                    
                    if(row.ispinpost === '1') {
                            table_value += '<li class="dv-twocol-remove-li" data-id="'+ row.id +'">'
                                + '<a href="javascript:void(0);" '
                                + ' onclick="getNewsContent(' + row.id + ')"'
                                + ' data-second-id="' + row.id + '" '
                                + ' data-secondprocessid="1560141744604" '
                                + 'data-secondtypecode="process" '
                                + 'class="media d-flex align-items-center justify-content-center" id="edit' + row.id + '" '
                                + 'data-rowdata="' + htmlentities(JSON.stringify(result[index]), 'ENT_QUOTES', 'UTF-8') + '">'
                                + '<span class="line-height-0"><input class="check-box" onclick="ischeckedFnc(this)"  type="checkbox" '+actioncheck+'></span><span class="mr-2"><i class="icon-star-full2 text-warning-300"></i></span>'
                                + '<div class="media-body">'
                                + '<div id="removal'+ row.id +'" class="media-title '+ read_class +' mb-0" style="line-height: normal;font-size: 12px;">' + row.description + '</div>'
                                + '<span class="text-muted font-weight-bold font-size-sm">' + row.tsag + '</span>'
                                + '</div>' + actionbuttons 
                                + '</a></li>';
                        } else {
                            if (number < 10) {
                                number = '0' + number;
                            }
                            table_value += '<li class="dv-twocol-remove-li" data-id="'+ row.id +'">'
                                    + '<a href="javascript:void(0);" '
                                    + ' onclick="getNewsContent(' + row.id + ')"'
                                    + ' data-second-id="' + row.id + '" '
                                    + ' data-secondprocessid="1560141744604" '
                                    + 'data-secondtypecode="process" '
                                    + 'class="media d-flex align-items-center justify-content-center" id="edit' + row.id + '" '
                                    + 'data-rowdata="' + htmlentities(JSON.stringify(result[index]), 'ENT_QUOTES', 'UTF-8') + '">'
                                    + '<span class="line-height-0"><input class="check-box" type="checkbox" onclick="ischeckedFnc(this)" value="'+ row.id +'" '+actioncheck+'></span><!--<span class="badge badge-primary mr-2">' + number + '</span>-->'
                                    + '<div class="media-body">'
                                    + '<div id="removal'+ row.id +'" class="media-title '+ read_class +' mb-0" style="line-height: normal;font-size: 12px;">' + row.description + '</div>'
                                    + '<span class="text-muted font-weight-bold font-size-sm">' + row.tsag + '</span>'
                                    + '</div>' + actionbuttons
                                    + '</a></li>';
                            number++;
                        }    

                });

                $("#all-content").empty().append(table_value);

                if (table_value === '') {
                    $("#all-content").append('<center class="text-grey mt-3">Тохирох үр дүн олдсонгүй</center>');
                }
                $("#category_title").text('Бүх мэдээ');
                Core.unblockUI();
            }
        });
    }  
    
    function getRightSide() {
        $.ajax({
            url: 'mdintranet/rightSidebarContent', 
            type: 'post',
            dataType: 'json',
            data: {
            },
            beforeSend: function () {
                Core.blockUI({
                    textOnly: true,
                    message: '<i class="icon-spinner4 spinner" style=" width: inherit; height: inherit; "></i>',
                    target: '#rightsidebar'
                });
            },
            success: function (data) {
                var html = '';
                
                $.each(data, function (index, row) {
                    html += '<div class="media border-bottom-1 border-gray mt-0 p-2">' +
                                '<div class="media-body">' +
                                    '<div class="d-flex flex-row align-items-center justify-content-between">' +
                                        '<a href="javascript:;" onclick="getNewsContent('+row.id+')" class="mb-0 font-weight-semibold line-height-normal font-size-13">'+row.name+'</a>' +
                                        '<div id="intranet-right">' +
                                            gridFileOnlyIconField (row.attachfile, row, '1') +
                                        '</div>' + 
                                    '</div>' +
                                    '<p class="mb-0 line-height-normal font-size-13 text-justify">'+row.description+'</p>' +
                                    '<small class="mr-2 text-gray">'+row.tsag+' </small>' +
                                    '<small class="text-gray">Шинээр нэмэгдсэн </small>' +
                                '</div>' +
                            '</div>';
                });
                            
                $("#rightsidebar").empty().append(html);            
            }
        });
    }
</script>