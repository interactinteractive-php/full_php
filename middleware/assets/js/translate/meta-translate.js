var IS_LOAD_TRANSLATE_SCRIPT =  true;

function metaTranslatorInit(elem, metaDataId) {
    
    PNotify.removeAll();
    
    $.ajax({
        type: 'post',
        url: 'mdlanguage/getMetaDictionary',
        data: {metaDataId: metaDataId},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function(data) {
            
            if (data.status == 'success') {
                
                var $dialogName = 'dialog-meta-translate-' + metaDataId;
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName), 
                    langList = data.langList,   
                    colspan = langList.length + 2, 
                    list = data.list, 
                    groupList = {}, 
                    windowHeight = $(window).height(), 
                    n = 1;
            
                var htmlTable = '<table class="table table-bordered table-hover">'+
                        '<thead>'+
                            '<tr>'+
                                '<th style="width: 25px;">№</th>'+
                                '<th style="width: 250px;">Код</th>';
                        
                    for (var l in langList) {    
                        htmlTable += '<th>'+langList[l].LANGUAGE_NAME+'</th>';
                    }
                                
                    htmlTable += '</tr></thead><tbody>';
                
                for (var k in list) {
                    
                    if (!groupList[list[k].GROUP_NAME]) {
                        
                        htmlTable += '<tr class="trnslt-groupname">'+
                            '<td colspan="'+colspan+'">' + translateCodeToName(list[k].GROUP_NAME) + '</td>'+
                        '</tr>';
                
                        groupList[list[k].GROUP_NAME] = 1;
                        n = 1;
                    }

                    htmlTable += '<tr>'+
                        '<td class="row-number">'+n+'</td>'+
                        '<td class="label-name">'+ 
                            '<input type="hidden" name="param['+list[k].GROUP_NAME+']['+list[k].PATH_NAME+'][globeCode][]" value="'+dvFieldValueShow(list[k].GLOBE_CODE)+'">'+ 
                            '<input type="hidden" name="param['+list[k].GROUP_NAME+']['+list[k].PATH_NAME+'][labelName][]" value="'+dvFieldValueShow(list[k].LABEL_NAME)+'">'+ 
                            list[k].PATH_NAME+ 
                        '</td>';
                    
                    for (var l in langList) {
                        htmlTable += '<td><input type="text" name="param['+list[k].GROUP_NAME+']['+list[k].PATH_NAME+']['+langList[l].LANGUAGE_CODE+'][]" class="form-control" value="' + dvFieldValueShow(list[k][langList[l].LANGUAGE_CODE]) + '"></td>';
                    }
                        
                    htmlTable += '</tr>';
                    
                    n++;
                }
                            
                htmlTable += '</tbody></table>';
                
                $dialog.empty().append('<form method="post" autocomplete="off" id="meta-translate-form-'+metaDataId+'"><div class="freeze-overflow-xy-auto" style="height: '+(windowHeight - 100)+'px;">'+htmlTable+'</div></form>');
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    dialogClass: 'translate-dialog', 
                    title: 'Translate', 
                    width: 1200,
                    height: windowHeight,
                    modal: true,
                    open: function () {
                        setTimeout(function() {
                            $dialog.find('.freeze-overflow-xy-auto > table').tableHeadFixer({'head': true}); 
                        }, 1);
                    }, 
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow bp-btn-subsave', click: function () {

                            PNotify.removeAll();
                            var $form = $('#meta-translate-form-' + metaDataId);

                            $form.validate({errorPlacement: function () {}});

                            if ($form.valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: 'mdlanguage/saveMetaTranslation', 
                                    data: 'metaDataId='+metaDataId+'&metaTypeId='+data.metaTypeId+'&metaDataCode='+data.metaDataCode+'&'+$form.serialize(),
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({
                                            message: 'Saving...',
                                            boxed: true
                                        });
                                    },
                                    success: function(dataSub) {
                                        new PNotify({
                                            title: dataSub.status,
                                            text: dataSub.message, 
                                            type: dataSub.status, 
                                            sticker: false
                                        });
                                        if (dataSub.status == 'success') {
                                            $dialog.dialog('close');
                                        } 
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }}, 
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-madison', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
                
                $dialog.on('keydown', 'input[type="text"]', function(e) {
                    
                    var keyCode = (e.keyCode ? e.keyCode : e.which);
                    
                    if (keyCode == 38) { /*up*/
                        
                        var $this = $(this);
                        var $row = $this.closest('tr');
                        var $cell = $this.closest('td');
                        var colIndex = $cell.index();
                        var $prevRow = $row.prevAll('tr:not(.trnslt-groupname):first');
                        
                        if ($prevRow.length) {
                            $prevRow.find('td:eq('+colIndex+') > input').focus().select();
                            return e.preventDefault();
                        }
                    } else if (keyCode == 40) { /*down*/
                        
                        var $this = $(this);
                        var $row = $this.closest('tr');
                        var $cell = $this.closest('td');
                        var colIndex = $cell.index();
                        var $nextRow = $row.nextAll('tr:not(.trnslt-groupname):first');
                        
                        if ($nextRow.length) {
                            $nextRow.find('td:eq('+colIndex+') > input').focus().select();
                            return e.preventDefault();
                        }
                    }
                });
            }
            
            Core.unblockUI();
        }
    });
}
function translateCodeToName(groupName) {
    var name = '';

    if (groupName == 'processName') {
        name = 'Процессийн нэр';
    } else if (groupName == 'tabName') {
        name = 'Табын нэр';
    } else if (groupName == 'sidebarName') {
        name = 'Sidebar нэр';
    } else if (groupName == 'parameter') {
        name = 'Параметр';
    } else if (groupName == 'listName') {
        name = 'Жагсаалтын нэр';
    } else if (groupName == 'listMenuName') {
        name = 'Жагсаалтын дэд нэр';
    } else if (groupName == 'mergeName') {
        name = 'Баганын бүлэглэсэн нэр';
    } else if (groupName == 'searchGroupName') {
        name = 'Шүүлтийг бүлэглэсэн нэр';
    } else if (groupName == 'batchName') {
        name = 'Товчийг бүлэглэсэн нэр';
    } else if (groupName == 'listProcessName') {
        name = 'Процессийн товчны нэр';
    } else if (groupName == 'columns') {
        name = 'Баганын нэр';
    }

    if (name) {
        name += ' <a href="javascript:;" title="Жишээ зураг" onclick="translateHelpPhotos(\''+groupName+'\', this);"><i class="icon-images2"></i></a>';
    }

    return name;
}
function translateHelpPhotos(groupName, elem) {
    
    if (groupName == 'processName') {
        var photosSrc = [
            {
                src  : 'middleware/assets/img/translate-help/process-name.png',
                opts : {
                    thumb: 'middleware/assets/img/translate-help/process-name.png'
                }
            }
        ];
    } else if (groupName == 'tabName') {
        var photosSrc = [
            {
                src  : 'middleware/assets/img/translate-help/tab-name.png',
                opts : {
                    thumb: 'middleware/assets/img/translate-help/tab-name.png'
                }
            }
        ];
    } else if (groupName == 'parameter') {
        var photosSrc = [
            {
                src  : 'middleware/assets/img/translate-help/process-parameter.png',
                opts : {
                    thumb: 'middleware/assets/img/translate-help/process-parameter.png'
                }
            }
        ];
    } else if (groupName == 'listName') {
        var photosSrc = [
            {
                src  : 'middleware/assets/img/translate-help/list-name.png',
                opts : {
                    thumb: 'middleware/assets/img/translate-help/list-name.png'
                }
            }
        ];
    } else if (groupName == 'searchGroupName') {
        var photosSrc = [
            {
                src  : 'middleware/assets/img/translate-help/search-group-name.png',
                opts : {
                    thumb: 'middleware/assets/img/translate-help/search-group-name.png'
                }
            }
        ];
    } else if (groupName == 'listProcessName') {
        var photosSrc = [
            {
                src  : 'middleware/assets/img/translate-help/list-process-name.png',
                opts : {
                    thumb: 'middleware/assets/img/translate-help/list-process-name.png'
                }
            }
        ];
    } else if (groupName == 'listMenuName') {
        var photosSrc = [
            {
                src  : 'middleware/assets/img/translate-help/list-menu-name.png',
                opts : {
                    thumb: 'middleware/assets/img/translate-help/list-menu-name.png'
                }
            }
        ];
    } else if (groupName == 'batchName') {
        var photosSrc = [
            {
                src  : 'middleware/assets/img/translate-help/batch-name.png',
                opts : {
                    thumb: 'middleware/assets/img/translate-help/batch-name.png'
                }
            }
        ];
    } else if (groupName == 'mergeName') {
        var photosSrc = [
            {
                src  : 'middleware/assets/img/translate-help/column-merge-name.png',
                opts : {
                    thumb: 'middleware/assets/img/translate-help/column-merge-name.png'
                }
            }
        ];
    } else if (groupName == 'columns') {
        var photosSrc = [
            {
                src  : 'middleware/assets/img/translate-help/column-name.png',
                opts : {
                    thumb: 'middleware/assets/img/translate-help/column-name.png'
                }
            }
        ];
    }
    
    var $transDialog = $(elem).closest('.ui-dialog-content');
    
    $.fancybox.open(photosSrc, {
        loop : false, 
        thumbs : {
            autoStart : true
        }, 
        beforeLoad : function() {
            $transDialog.dialog('option', 'closeOnEscape', false);
        }, 
        beforeClose : function() {
            $transDialog.dialog('option', 'closeOnEscape', true);
        }
    });
}
function menuMetaTranslatorInit(elem, metaDataId) {
    
    PNotify.removeAll();
    
    $.ajax({
        type: 'post',
        url: 'mdlanguage/getMenuMetaDictionary',
        data: {metaDataId: metaDataId},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function(data) {
            
            if (data.status == 'success') {
                
                var $dialogName = 'dialog-meta-translate-' + metaDataId;
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName), 
                    langList = data.langList,   
                    list = data.list, 
                    windowHeight = $(window).height(), 
                    n = 1;
            
                var htmlTable = '<table class="table table-bordered table-hover">'+
                        '<thead>'+
                            '<tr>'+
                                '<th style="width: 25px;">№</th>'+
                                '<th style="width: 330px;">Цэс</th>';
                        
                    for (var l in langList) {    
                        htmlTable += '<th>'+langList[l].LANGUAGE_NAME+'</th>';
                    }
                                
                    htmlTable += '</tr></thead><tbody>';
                
                for (var k in list) {

                    htmlTable += '<tr>'+
                        '<td class="row-number">'+n+'</td>'+
                        '<td class="label-name tnslt-level-'+list[k].ORDER_LEVEL+'">'+ 
                            '<input type="hidden" name="param['+list[k].TRG_META_DATA_ID+'][globeCode]" value="'+dvFieldValueShow(list[k].GLOBE_CODE)+'">'+ 
                            list[k].MONGOLIAN+ 
                        '</td>';
                    
                    for (var l in langList) {
                        htmlTable += '<td><input type="text" name="param['+list[k].TRG_META_DATA_ID+']['+langList[l].LANGUAGE_CODE+']" class="form-control" value="' + dvFieldValueShow(list[k][langList[l].LANGUAGE_CODE]) + '"></td>';
                    }
                        
                    htmlTable += '</tr>';
                    
                    n++;
                }
                            
                htmlTable += '</tbody></table>';
                
                $dialog.empty().append('<form method="post" autocomplete="off" id="meta-translate-form-'+metaDataId+'"><div class="freeze-overflow-xy-auto" style="height: '+(windowHeight - 100)+'px;">'+htmlTable+'</div></form>');
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    dialogClass: 'translate-dialog', 
                    title: 'Translate', 
                    width: 1200,
                    height: windowHeight,
                    modal: true,
                    open: function () {
                        setTimeout(function() {
                            $dialog.find('.freeze-overflow-xy-auto > table').tableHeadFixer({'head': true}); 
                        }, 1);
                    }, 
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow bp-btn-subsave', click: function () {

                            PNotify.removeAll();
                            var $form = $('#meta-translate-form-' + metaDataId);

                            $form.validate({errorPlacement: function () {}});

                            if ($form.valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: 'mdlanguage/saveMenuMetaTranslation', 
                                    data: 'moduleId='+metaDataId+'&'+$form.serialize(),
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({
                                            message: 'Saving...',
                                            boxed: true
                                        });
                                    },
                                    success: function(dataSub) {
                                        new PNotify({
                                            title: dataSub.status,
                                            text: dataSub.message, 
                                            type: dataSub.status, 
                                            sticker: false
                                        });
                                        if (dataSub.status == 'success') {
                                            $dialog.dialog('close');
                                        } 
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }}, 
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-madison', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
                
                $dialog.on('keydown', 'input[type="text"]', function(e) {
                    
                    var keyCode = (e.keyCode ? e.keyCode : e.which);
                    
                    if (keyCode == 38) { /*up*/
                        
                        var $this = $(this);
                        var $row = $this.closest('tr');
                        var $cell = $this.closest('td');
                        var colIndex = $cell.index();
                        var $prevRow = $row.prev('tr');
                        
                        if ($prevRow.length) {
                            $prevRow.find('td:eq('+colIndex+') > input').focus().select();
                            return e.preventDefault();
                        }
                    } else if (keyCode == 40) { /*down*/
                        
                        var $this = $(this);
                        var $row = $this.closest('tr');
                        var $cell = $this.closest('td');
                        var colIndex = $cell.index();
                        var $nextRow = $row.next('tr');
                        
                        if ($nextRow.length) {
                            $nextRow.find('td:eq('+colIndex+') > input').focus().select();
                            return e.preventDefault();
                        }
                    }
                });
            }
            
            Core.unblockUI();
        }
    });
}