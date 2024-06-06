var isGovAddonScript = true;

function previewIntranet(paramData) {
    
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'mdintranet/intranet1',
        data: {paramData: paramData},
        beforeSend: function() {
            
            Core.blockUI({
                boxed: true,
                message: 'Түр хүлээнэ үү...'
            });
        },
        success: function(data) {
            if (typeof data.status !== 'undefined') {
                new PNotify({
                    title: data.status,
                    text: data.text,
                    type: data.status,
                    sticker: false
                });  
            } else {
                appMultiTabByContent({ weburl: 'mdintranet/intranet1', metaDataId: getUniqueId(1), title: data.Title, type: 'selfurl', content: data.Html }, function () {
                    $("head").append('<link rel="stylesheet" type="text/css" href="'+ URL_APP +'middleware/assets/css/intranet/style.css"/>')
                });
            }
            Core.unblockUI();
        }
    });
}

function privateGroup (processCodew, groupId = null) {    
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'government/groups',
        beforeSend: function() {
            $("head").append('<link rel="stylesheet" type="text/css" href="'+ URL_APP +'middleware/assets/css/intranet/style.css"/>')
            Core.blockUI({
                boxed: true,
                message: 'Түр хүлээнэ үү...'
            });
        },
        success: function(data) {
            var $dialogName = 'dialog-popup-'+data.uniqId;
            
            $('<div class="modal fade modal-after-save-close private-group" id="'+ $dialogName +'"  tabindex="-1">' +
                    '<div class="modal-dialog modal-lg">' +
                        '<div class="modal-content">' +
                            '<div class="modal-header">' +
                                '<h5 class="modal-title">' + data.Title + '</h5>' +
                                '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
                            '</div>' +
                            '<div class="modal-body" style="min-height: 350px;">'+ data.Html + '</div>' +
                            '<div class="modal-footer">' +
                                '<button type="button" class="btn btn-primary" data-dismiss="modal">'+ data.close_btn + '</button>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>').appendTo('body');
        
            var $dialog   = $('#' + $dialogName);
            
            $dialog.modal();
            
            $dialog.on('shown.bs.modal', function () {
                setTimeout(function(){
                    Core.unblockUI();
                }, 10);    
                disableScrolling();
            });   
            
            $dialog.draggable({
                handle: ".modal-header"
            });

            $dialog.on('hidden.bs.modal', function () {
                $dialog.remove();
                enableScrolling();
            });   
            if(groupId != null){
                $('.box' + groupId).click();
            }
            
            Core.unblockUI();
        }
    });
}

function members(id) {
    var $element = $('a[data-id="'+ id +'"]');
    var dataRow = JSON.parse($element.attr('data-rowdata'));
    $element.closest('div#groups').find('div.box').removeClass('group_active');
    $element.closest('div.box').addClass('group_active');
    
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'government/members',
        data: {
            id: id,
            dataRow: dataRow
        } ,
        beforeSend: function() {
            blockContentGovernment(".member-body"); 
        },
        success: function(data) {
            var html = '';
            
            if(data) {
                $.each(data, function(key, value) {
                html += '<div class="box">' +
                            '<div class="mr-2">' +
                                '<img src="assets/custom/img/user_new.png" class="rounded-circle" width="40" height="40">' +
                            '</div>' +
                            '<div class="d-flex flex-column">' +
                                '<a href="javascript:void(0);" class="text-black media-title mb-0 line-height-normal word-break-all">'+value.personname+'</a>' +
                                '<span class="text-muted font-size-sm mr-3 line-height-normal text-uppercase font-size-10">'+value.positionname+'</span>' +
                            '</div>' +
                            '<div class="ml-auto d-flex align-items-center">' +
                                '<div class="action-btn" style="display:none;">' +
                                    '<a href="javascript:void(0);" onclick="deleteMember('+value.id+','+value.groupid+')">' +
                                        '<i class="fa fa-trash-o mr-2"></i>' +
                                    '</a>' +
                                '</div>' +
                            '</div>' +
                        '</div>';
                });
            } else {
                html += '<p class="text-grey text-center">Энэ бүлэг хоосон байна</p>';
            }
            
            var addhtml = '';
            if (typeof dataRow['id'] !== 'undefined') {
                addhtml += '<input type="hidden" name="groupId" value="'+ dataRow['id'] +'">';
                addhtml += '<input type="hidden" name="group_name" value="'+ dataRow['groupname'] +'">';
                addhtml += '<input type="hidden" name="group_position" value="'+ dataRow['positionid'] +'">';
                addhtml += '<input type="hidden" name="userid" value="'+ dataRow['userid'] +'">';
                addhtml += '<input type="hidden" name="createddate" value="'+ dataRow['createddate'] +'">';
            }
            
            $(".member-body").empty().append(html);
            $('#memberForm').find('.addinParams').remove();
            $('#memberForm').append('<div class="addinParams">' + addhtml + '</div>');
            Core.unblockUI('.member-body');
        }
    });
}

function addGroup() {
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'government/addGroups',
        data: $('#groupForm').serialize(),
        success: function(data) {
            PNotify.removeAll();
            new PNotify({
                title: data.status,
                text: data.text,
                type: data.status,
                sticker: false
            });
            clearField("#groupForm");
            reloadGroup();
            $(".group-add-box").toggle();
        }
    });
}

function reloadGroup(){
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'government/reloadGroups',
        success: function(data) {
            var html = "";
            $.each(data, function(key,value){
                var rowJson = htmlentities(JSON.stringify(value), 'ENT_QUOTES', 'UTF-8');
                html += '<div class="box" onclick="members('+value.id+');">' +
                            '<div class="mr-2">' +
                                '<img src="assets/custom/img/org.png" class="rounded-circle" width="40" height="40">' +
                            '</div>' +
                            '<div class="d-flex flex-column">' +
                                '<a href="javascript:void(0);" data-id="'+value.id+'" onclick="members('+value.id+');" data-rowdata="'+rowJson+'" class="text-blue media-title mb-0 line-height-normal word-break-all">'+value.groupname+'</a>' +
                            '</div>' +
                            '<div class="ml-auto d-flex align-items-center">' +
                                '<div class="action-btn" style="display:none;">' +
                                    '<a href="javascript:void(0);" onclick="renderEditGroupForm('+value.id+')">' +
                                        '<i class="fa fa-cog mr-1"></i>' +
                                    '</a>' +
                                    '<a href="javascript:void(0);" onclick="deleteGroup('+value.id+')">' +
                                        '<i class="fa fa-trash-o mr-2"></i>' +
                                    '</a>' +
                                '</div>' +
                                '<a href="javascript:void(0);">' +
                                    '<span class="text-gray font-size-12">('+value.membercount+')</span>' +
                                '</a>' +
                            '</div>' +
                        '</div>';
            });
            $("#groups").empty().append(html);
        }
    });
}

function deleteGroup(id){
    if(id) {
        runIsOneBusinessProcess(1572258776188891, 1572007014775, true, {id: id}, function () {
            $(".member-body").empty().append("<p class='text-grey text-center'>Бүлэг сонгоно уу...</p>");
            reloadGroup();
        });
    }
}

function renderEditGroupForm(id) {
    var $element = $('a[data-id="'+ id +'"]');
    
    $(".group-edit-box").show();
    $('.modal').stop().animate({scrollTop: 0}, 1000);
    
    var dataRow = JSON.parse($element.attr('data-rowdata'));

    var html = '<div class="form-group row d-flex align-items-center">' + 
                    '<label class="col-form-label col-lg-3 text-right">Бүлгийн нэр</label>'+ 
                    '<div class="col-lg-9">'+
                        '<input type="text" name="group_name" value="'+dataRow['groupname']+'" class="form-control form-control-sm">'+
                    '</div>'+
                '</div>'+
                '<div class="form-group row d-flex align-items-center">'+
                    '<label class="col-form-label col-lg-3 text-right">Байрлал</label>'+
                    '<div class="col-lg-9">'+
                        '<select name="group_position" class="form-control form-control-sm dropdownInput select2 data-combo-set select2-offscreen">';
                        $.each($optionGroupIntra, function(i,data) {
                            if(data.id === dataRow['positionid']) {
                                html += '<option value="'+data.id+'" selected>'+data.name+'</options>';
                            } else {
                                html += '<option value="'+data.id+'">'+data.name+'</options>';
                            }
                        });
                    html += '</select>'+
                    '</div>'+
                '</div>';
                html += '<input type="hidden" name="id" value="'+dataRow['id']+'"><input type="hidden" name="user_id" value="'+dataRow['userid']+'"><input type="hidden" name="created_date" value="'+dataRow['createddate']+'">' +
                '<div class="d-flex justify-content-end">'+
                    '<button type="button" onclick="editGroup()" class="btn btn-success"><i class="icon-checkmark3 mr-1 font-size-18"></i> Засах</button>'+
                '</div>';
    $("#editGroupForm").empty().append(html).promise().done(function () {
        $("#editGroupForm").find('input[name="group_name"]').focus();
    });
    $(".group-add-box").hide();
    Core.initAjax($("#editGroupForm"));
}

function editGroup() {
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'government/editGroups',
        data: $('#editGroupForm').serialize(),
        success: function(data) {
            PNotify.removeAll();
            new PNotify({
                title: data.status,
                text: data.text,
                type: data.status,
                sticker: false
            });
            clearField("#editGroupForm");
            reloadGroup();
            $(".group-edit-box").toggle();
        }
    });
}

function deleteMember(id,groupId) {
    $.ajax({
        type: 'post',
        dataType: 'json',
        data: {id : id},
        url: 'government/deleteMember',
        success: function (data) {
            if(data.status === 'success') {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: 'Амжилттай устгагдлаа',
                    type: data.status,
                    sticker: false
                });
                reloadGroup();
                members(groupId);
            } else {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.text,
                    type: data.status,
                    sticker: false
                });
            }
        }
    });
}

function addMember(){
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'government/addMember',
        data: $('#memberForm').serialize(),
        success: function(data) {
            PNotify.removeAll();
            new PNotify({
                title: data.status,
                text: data.text,
                type: data.status,
                sticker: false
            });
            
            if(typeof data.groupId !== 'undefined') { 
                members(data.groupId);
            }
            
            clearField('#memberForm');
            reloadGroup();
            $(".member-add-box").toggle();
        }
    });
}

function clearField(form) {
    $(form).find('input').val('');
    $(form).find('select').select2('val', '');
    $(form).find('button[data-lookupid="1565070936581248"]').html('..');
}

function callomsconferenceAddForm (paramData, date, isedit, id, callback, param,templateId,stime,etime) {
    $.ajax({
        type: 'post',
        url: 'government/omsconferenceAddForm',
        data: {paramData: paramData, isedit: (typeof isedit !== 'undefined') ? '1' : '0' , id: id},
        dataType: 'json',
        beforeSend: function () {
            if (!$("link[href='assets/custom/gov/goverment.css']").length) {
                $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/gov/goverment.css"/>');
            }
            if (!$("link[href='assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css']").length) {
                $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
            }
            
            Core.blockUI({
                message: 'Түр хүлээнэ үү...',
                boxed: true
            });
        },
        success: function (data) {
            var $dialogName = 'dialog-oms-' + data.uniqId;
            $('<div class="modal pl0 fade modal-after-save-close dialogoms" id="'+ $dialogName +'" tabindex="-1" role="dialog" aria-hidden="true">'+
                    '<div class="modal-dialog" style="width: 450px !important; margin-top: 10px;">'+
                        '<div class="modal-content modalcontent'+ data.uniqId +'">'+
                            '<div class="modal-header">'+
                                '<h4 class="modal-title">' + data.Title + '</h4>'+
                                '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
                            '</div>'+
                            '<div class="modal-body">'+
                                data.Html+
                            '</div>'+
                            '<div class="modal-footer">'+
                                '<button type="button" class="btn btn-sm btn-primary" id="savebutton'+data.uniqId+'" onclick="saveOmsConference(\'.modalcontent'+ data.uniqId +'\', '+ data.uniqId +')">' + data.save_btn + '</button>'+
                                '<button type="button" data-dismiss="modal" class="btn btn-sm btn-danger">' + data.close_btn + '</button>'+
                            '</div>' +
                        '</div>'+
                    '</div>'+
                '</div>').appendTo('body');
        
            var $dialog   = $('#' + $dialogName);
            if (typeof isedit === 'undefined') {
                $("#startDate"  + data.uniqId).val(date);
                $("#templateId"  + data.uniqId).val(templateId);
                $("#startTime"  + data.uniqId).val(stime);
                $("#endTime"  + data.uniqId).val(etime);
            }
            
            $dialog.modal({
                show: false,
                keyboard: false,
                backdrop: 'static'
            });
            
            //$dialog.modal();
            
            $dialog.on('shown.bs.modal', function () {
                setTimeout(function() {
                    Core.initAjax($dialog);
                    Core.unblockUI();
                }, 10);    
//                disableScrolling();
            });   
            
            $dialog.draggable({
                handle: ".modal-header"
            });

            $dialog.on('hidden.bs.modal', function () {
                if (typeof callback !== 'undefined') {
                    window[callback](param);
                } else {
                    $('.conf-room [data-one="1"]').remove();
                    reload('refetchEvents');
                    dataViewReload(data.dataviewId); 
                    dataViewFirstColumnFocus(data.dataviewId);
                    $dialog.remove();
                    enableScrolling();
                }
            });            

            $dialog.modal('show');
            
        }
    });
}

function saveOmsConference (element, uniqId) {
    var $processForm = $('#saveform_' + uniqId);
    $processForm.validate({errorPlacement: function () {}});
    
    if (!$processForm.valid()) {
        PNotify.removeAll();
        new PNotify({
            title: 'Анхааруулга',
            text: 'Заавал бөглөх талбарыг бөглөнө үү',
            type: 'warning',
            sticker: false
        });
        return;
    }
    
    $processForm.ajaxSubmit({
        type: 'post',
        url: 'government/saveOmsConference',
        dataType: 'json',
        beforeSend: function () {
            blockContentGovernment (element); 
        },
        success: function (responseData) {
            PNotify.removeAll();
            new PNotify({
                title: responseData.status,
                text: responseData.text,
                type: responseData.status, 
                sticker: false
            });
            
            Core.unblockUI(element);
            $('#dialog-oms-' + uniqId).modal('hide');
        },
        error: function(jqXHR, exception) {
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }

            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: msg,
                type: 'error',
                sticker: false
            });
            Core.unblockUI(element);
        }
    });
}

function blockContentGovernment(mainSelector) {
        $(mainSelector).block({
            message: '<i class="icon-spinner4 spinner"></i>',
            centerX: 0,
            centerY: 0,
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                width: 16,
                top: '15px',
                left: '',
                right: '15px',
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
}
    
function govTrainingAction (selectedRow, urlLower, dataviewId) {
    
    var $dataRow = selectedRow;
    $dataRow = {'windowtypeid': '5', 'posttypeid': '12', 'postcategoryid': '12', 'id': (typeof selectedRow !== 'undefined' && typeof selectedRow['id'] !== 'undefined' ? selectedRow['id'] : '')};
    var $uniqId = Core.getUniqueID('icon_');
    var $modalId = 'modal-intranet' + $uniqId;
    
    switch (urlLower) {
        case "addtrainingweblink":
            $.ajax({
                url: 'government/addForm',
                type: 'post',
                dataType: 'json',
                data: {
                    dataRow: $dataRow,
                    uniqId: $uniqId
                },
                beforeSend: function () {
                    Core.blockUI({
                        boxed: true,
                        message: 'Түр хүлээнэ үү...'
                    });
                },
                success: function (data) {
                    var title = 'Сургалт нэмэх';
                    var isconfirmed = '0';
                    
                    $('<div id="' + $modalId + '" class="modal surgaltiinmodal fade" tabindex="-1" role="dialog">' +
                            '<div class="modal-dialog modal-lg">' +
                            '<div class="modal-content modalcontent'+ $uniqId +'">' +
                            '<div class="modal-header">' +
                            '<h6 class="modal-title">' + title + '</h6>' +
                            '<button type="button" class="close" onclick="close_15648941613216846('+ isconfirmed +', \''+ dataviewId +'\', \''+ $uniqId +'\')" style="filter: inherit;">&times;</button>' +
                            '</div>' +
                            '<div class="modal-body">' +
                            data.Html +
                            '</div>' +
                            '<div class="modal-footer">' +
                            '<button type="button" class="btn send-btn-'+ $uniqId +'" onclick="save_15648941613216846(\'.modalcontent'+ $uniqId +'\', \''+ dataviewId +'\', \''+ $uniqId +'\')">Хадгалах</button>' +
                            '<button type="button" class="btn btn-link" onclick="close_15648941613216846('+ isconfirmed +', \''+ dataviewId +'\', \''+ $uniqId +'\')">Хаах</button>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div><style>.surgaltiinmodal .spinner { width: initial !important;         height: initial !important; }</style>').appendTo('body');

                    var $dialog = $('#' + $modalId);

                    $dialog.modal({
                        show: false,
                        keyboard: false,
                        backdrop: 'static'
                    });

                    $dialog.on('shown.bs.modal', function () {
                        setTimeout(function () {

                        }, 10);
                        disableScrolling();
                    });

                    $dialog.draggable({
                        handle: ".modal-header"
                    });

                    $dialog.on('hidden.bs.modal', function () {
                        $dialog.remove();
                        enableScrolling();
                    });

                    if (typeof tinymce === 'undefined') {
                        $.getScript(URL_APP + 'assets/custom/addon/plugins/tinymce/tinymce.min.js', function () {
                            $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/autocomplete.css"/>');
                            $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/rte-content.css"/>');
                            $.getScript(URL_APP + 'assets/custom/addon/plugins/tinymce/plugins/mention/plugin.min.js').done(function (script, textStatus) {
                                initTinyMceEditor_123('#body_' + $uniqId, $uniqId);
                                $dialog.modal('show');
                            });
                        });
                    } else {
                        initTinyMceEditor_123('#body_' + $uniqId, $uniqId);
                        $dialog.modal('show');
                    }

                    Core.initAjax($dialog);
                    $dialog.find('.modal-backdrop').remove();

                    Core.unblockUI();
                }
            });
            break;
            
        case "edittrainingweblink":
            $.ajax({
                url: 'government/editPost',
                type: 'post',
                dataType: 'json',
                data: {
                    dataRow: $dataRow,
                    selectedRow: $dataRow,
                    uniqId: $uniqId
                },
                beforeSend: function () {
                    Core.blockUI({
                        boxed: true,
                        message: 'Түр хүлээнэ үү...'
                    });
                },
                success: function (data) {
                    var title = 'Сургалт нэмэх';
                    var isconfirmed = '0';
                    
                    $('<div id="' + $modalId + '" class="modal surgaltiinmodal fade" tabindex="-1" role="dialog">' +
                            '<div class="modal-dialog modal-lg">' +
                            '<div class="modal-content modalcontent'+ $uniqId +'">' +
                            '<div class="modal-header">' +
                            '<h6 class="modal-title">' + title + '</h6>' +
                            '<button type="button" class="close" onclick="close_15648941613216846('+ isconfirmed +', \''+ dataviewId +'\', \''+ $uniqId +'\')" style="filter: inherit;">&times;</button>' +
                            '</div>' +
                            '<div class="modal-body">' +
                            data.Html +
                            '</div>' +
                            '<div class="modal-footer">' +
                            '<button type="button" class="btn send-btn-'+ $uniqId +'" onclick="save_15648941613216846(\'.modalcontent'+ $uniqId +'\', \''+ dataviewId +'\', \''+ $uniqId +'\')">Хадгалах</button>' +
                            '<button type="button" class="btn btn-link" onclick="close_15648941613216846('+ isconfirmed +', \''+ dataviewId +'\', \''+ $uniqId +'\')">Хаах</button>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div><style>.surgaltiinmodal .spinner { width: initial !important;         height: initial !important; }</style>').appendTo('body');

                    var $dialog = $('#' + $modalId);

                    $dialog.modal({
                        show: false,
                        keyboard: false,
                        backdrop: 'static'
                    });

                    $dialog.on('shown.bs.modal', function () {
                        setTimeout(function () {

                        }, 10);
                        disableScrolling();
                    });

                    $dialog.draggable({
                        handle: ".modal-header"
                    });

                    $dialog.on('hidden.bs.modal', function () {
                        $dialog.remove();
                        enableScrolling();
                    });

                    if (typeof tinymce === 'undefined') {
                        $.getScript(URL_APP + 'assets/custom/addon/plugins/tinymce/tinymce.min.js', function () {
                            $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/autocomplete.css"/>');
                            $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/rte-content.css"/>');
                            $.getScript(URL_APP + 'assets/custom/addon/plugins/tinymce/plugins/mention/plugin.min.js').done(function (script, textStatus) {
                                initTinyMceEditor_123('#body_' + $uniqId, $uniqId);
                                $dialog.modal('show');
                            });
                        });
                    } else {
                        initTinyMceEditor_123('#body_' + $uniqId, $uniqId);
                        $dialog.modal('show');
                    }

                    Core.initAjax($dialog);
                    $dialog.find('.modal-backdrop').remove();

                    Core.unblockUI();
                }
            });
            break;
    }
}

function govPollAction (selectedRow, urlLower, dataviewId, paramData) {
    
    var $dataRow = selectedRow;
    var _saveProcessCode = typeof paramData['saveProcessCode'] !== 'undefined' && paramData['saveProcessCode'] ? paramData['saveProcessCode'] : 'LIS_POLL_DV_001'; 
    $dataRow = {'windowtypeid': '2', 'posttypeid': '3', 'postcategoryid': '0', 'createprocesscode': _saveProcessCode, 'id': (typeof selectedRow !== 'undefined' && typeof selectedRow['id'] !== 'undefined' ? selectedRow['id'] : '')};
    var $uniqId = '15648941613216846';// Core.getUniqueID('icon_');
    var $modalId = 'modal-intranet' + $uniqId;
    $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/css/intranet/style.v'+ getUniqueId(1) +'.css"/>');
    switch (urlLower) {
        case "addformpoll":
            $.ajax({
                url: 'government/addForm',
                type: 'post',
                dataType: 'json',
                data: {
                    dataRow: $dataRow,
                    uniqId: $uniqId
                },
                beforeSend: function () {
                    Core.blockUI({
                        boxed: true,
                        message: 'Түр хүлээнэ үү...'
                    });
                },
                success: function (data) {
                    var title = 'Санал асуулга нэмэх';
                    var isconfirmed = '0';
                    
                    $('<div id="' + $modalId + '" class="modal surgaltiinmodal fade" tabindex="-1" role="dialog">' +
                            '<div class="modal-dialog modal-lg">' +
                                '<div class="modal-content modalcontent'+ $uniqId +'">' +
                                    '<div class="modal-header">' +
                                        '<h6 class="modal-title">' + title + '</h6>' +
                                        '<button type="button" class="close" onclick="close_15648941613216846('+ isconfirmed +', \''+ dataviewId +'\', \''+ $uniqId +'\')" style="filter: inherit;">&times;</button>' +
                                    '</div>' +
                                    '<div class="modal-body">' +
                                        data.Html +
                                    '</div>' +
                                    '<div class="modal-footer">' +
                                        '<button type="button" class="btn send-btn-'+ $uniqId +'" onclick="save_15648941613216846(\'.modalcontent'+ $uniqId +'\', \''+ dataviewId +'\', \''+ $uniqId +'\', \'1\')">Хадгалах</button>' +
                                        '<button type="button" class="btn btn-link" onclick="close_15648941613216846('+ isconfirmed +', \''+ dataviewId +'\', \''+ $uniqId +'\')">Хаах</button>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '</div><style>.surgaltiinmodal .spinner { width: initial !important;         height: initial !important; } .modal {     z-index: 1000 !important; } .modal-backdrop {     z-index: 999 !important; } .ui-dialog {z-index: 1052 !important; } </style>').appendTo('body');

                    var $dialog = $('#' + $modalId);

                    $dialog.modal({
                        show: false,
                        keyboard: false,
                        backdrop: 'static'
                    });

                    $dialog.on('shown.bs.modal', function () {
                        setTimeout(function () {

                        }, 10);
                        disableScrolling();
                    });

                    $dialog.draggable({
                        handle: ".modal-header"
                    });

                    $dialog.on('hidden.bs.modal', function () {
                        $dialog.remove();
                        enableScrolling();
                    });

                    $dialog.modal('show');

                    Core.initAjax($dialog);
                    $dialog.find('.modal-backdrop').remove();

                    Core.unblockUI();
                }
            });
            break;
            
        case "editformpoll":
            if (!$dataRow['id']) {
                alert(plang.get('msg_pls_list_select'));
                return false;
            }
            
            $.ajax({
                url: 'government/editPost',
                type: 'post',
                dataType: 'json',
                data: {
                    dataRow: $dataRow,
                    selectedRow: $dataRow,
                    uniqId: $uniqId
                },
                beforeSend: function () {
                    Core.blockUI({
                        boxed: true,
                        message: 'Түр хүлээнэ үү...'
                    });
                },
                success: function (data) {
                    var title = 'Санал асуулга нэмэх';
                    var isconfirmed = '0';
                    
                    $('<div id="' + $modalId + '" class="modal surgaltiinmodal fade" tabindex="-1" role="dialog">' +
                            '<div class="modal-dialog modal-lg">' +
                                '<div class="modal-content modalcontent'+ $uniqId +'">' +
                                    '<div class="modal-header">' +
                                        '<h6 class="modal-title">' + title + '</h6>' +
                                        '<button type="button" class="close" onclick="close_15648941613216846('+ isconfirmed +', \''+ dataviewId +'\', \''+ $uniqId +'\')" style="filter: inherit;">&times;</button>' +
                                    '</div>' +
                                    '<div class="modal-body">' +
                                        data.Html +
                                    '</div>' +
                                    '<div class="modal-footer">' +
                                        '<button type="button" class="btn send-btn-'+ $uniqId +'" onclick="save_15648941613216846(\'.modalcontent'+ $uniqId +'\', \''+ dataviewId +'\', \''+ $uniqId +'\', \'1\')">Хадгалах</button>' +
                                        '<button type="button" class="btn btn-link" onclick="close_15648941613216846('+ isconfirmed +', \''+ dataviewId +'\', \''+ $uniqId +'\')">Хаах</button>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div><style>.surgaltiinmodal .spinner { width: initial !important;         height: initial !important; } .modal {     z-index: 1000 !important; } .modal-backdrop {     z-index: 999 !important; } .ui-dialog {z-index: 1052 !important; }</style>').appendTo('body');

                    var $dialog = $('#' + $modalId);

                    $dialog.modal({
                        show: false,
                        keyboard: false,
                        backdrop: 'static'
                    });

                    $dialog.on('shown.bs.modal', function () {
                        disableScrolling();
                    });

                    $dialog.draggable({
                        handle: ".modal-header"
                    });

                    $dialog.on('hidden.bs.modal', function () {
                        $dialog.remove();
                        enableScrolling();
                    });
                    
                    $dialog.modal('show');
                    Core.initAjax($dialog);
                    $dialog.find('.modal-backdrop').remove();

                    Core.unblockUI();
                }
            });
            break;
    }
}

function initTinyMceEditor_123($elementSelector, $uniqId) {

    tinymce.dom.Event.domLoaded = true;
    tinymce.baseURL = URL_APP + 'assets/custom/addon/plugins/tinymce';
    tinymce.suffix = ".min";
    tinymce.init({
        selector: $elementSelector,
//            height: '180px',
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'autoresize',
            'emoticons template paste textcolor colorpicker textpattern imagetools moxiemanager mention lineheight fullpage'
        ],
        toolbar1: 'fontselect fontsizeselect | forecolor backcolor | bold italic underline | alignleft aligncenter alignright alignjustify | customInsertButton currentdate',
        fontsize_formats: '8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 36px 8pt 9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 17pt 18pt 19pt 20pt 21pt 22pt 23pt 24pt 25pt 36pt',
        lineheight_formats: '8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 1.0 1.15 1.5 2.0 2.5 3.0',
        image_advtab: true, 
        setup: function(editor) {
            function insertImageCustom_123() {
                $('#upload_' + $uniqId).trigger('click');
                $('#upload_' + $uniqId).on('change', function() {
                    var file = this.files[0];
                    if (file) {
                        $(this).val('');
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            editor.insertContent('<img src="'+e.target.result+'" />');
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            editor.on('change', function() {
                tinymce.triggerSave();
            });

            editor.addButton('currentdate', {
                icon: 'image',
              //   text: 'Image',
                tooltip: "Insert image",
                onclick: insertImageCustom_123
            });

            editor.on('SetContent',function(e){
                const content = e.content;

                var newHeight = String(content.length/2);
                editor.theme.resizeTo("100%",newHeight);          
           });  
        },
        force_br_newlines: true,
        force_p_newlines: false,
        forced_root_block: '',
        paste_data_images: true,
        menubar: false,
        statusbar: true,
        resize: true,
        theme_advanced_toolbar_location: "bottom",
        theme_modern_toolbar_location: "bottom",
        paste_word_valid_elements: "b,p,br,strong,i,em,h1,h2,h3,h4,ul,li,ol,table,span,div,font",
        mentions: {
            delimiter: '#'
        },
        document_base_url: URL_APP,
        content_css: URL_APP + 'assets/custom/css/print/tinymce.css',
    });
}

function save_15648941613216846(element, dataviewId, $uniqId, pollurl) {
    
    var urlApp = (typeof pollurl !== 'undefined' ) ? "legal/savePoll/" : "government/savePost/";
    $("#saveform_" + $uniqId).validate({ errorPlacement: function() {} });
    if ($("#saveform_" + $uniqId).valid()) {
        $("#saveform_" + $uniqId).ajaxSubmit({
            type: 'post',
            url: urlApp,
            dataType: 'json',
            beforeSend: function () {
                if (typeof element !== 'undefined') {
                    blockContent_15648941613216846(element);
                } else {
                    Core.blockUI({
                        message: 'Түр хүлээнэ үү',
                        boxed: true
                    });
                }
            },
            success: function (response) {

                PNotify.removeAll();
                new PNotify({
                    title: response.status,
                    text: response.text,
                    type: response.status,
                    sticker: false
                });

                if (response.status === 'success') {
                    $("#modal-intranet" + $uniqId).modal('hide');
                    dataViewReload(dataviewId);
                }
                
                if (typeof element !== 'undefined') {
                    Core.unblockUI(element);
                } else {
                    Core.unblockUI();
                }

            },
            error: function (jqXHR, exception) {
                Core.showErrorMessage(jqXHR, exception);
                if (typeof element !== 'undefined') {
                    Core.unblockUI(element);
                } else {
                    Core.unblockUI();
                }
            }
        });
    }
    
}

function blockContent_15648941613216846(mainSelector) {
    $(mainSelector).block({
        message: '<i class="icon-spinner4 spinner"></i>',
        centerX: 0,
        centerY: 0,
        overlayCSS: {
            backgroundColor: '#fff',
            opacity: 0.8,
            cursor: 'wait'
        },
        css: {
            width: 16,
            top: '15px',
            left: '',
            right: '15px',
            border: 0,
            padding: 0,
            backgroundColor: 'transparent'
        }
    });
}

function close_15648941613216846(isconfirmed, dataviewId, $uniqId) {
    var $html = 'Хаахдаа итгэлтэй байна уу?';
    var $dialogConfirm = 'dialog-confirm-15648941613216846';
    if (!$("#" + $dialogConfirm).length) {
        $('<div id="' + $dialogConfirm + '"></div><style>.ui-widget[aria-describedby="dialog-confirm-15648941613216846"]{z-index: 1052 !important;  }</style>').appendTo('body');
    }
    
    var $dialog = $("#" + $dialogConfirm);

    $dialog.empty().append($html);
    $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: 'Баталгаажуулалт',
        width: 400,
        height: "auto",
        modal: true,
        close: function () {
            $dialog.empty().dialog('close');
        },
        buttons: [
            {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                $dialog.empty().dialog('close');
                $("#modal-intranet" + $uniqId).modal('hide');
                dataViewReload(dataviewId);
            }},
            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
                dataViewReload(dataviewId);
            }}
        ]
    });
    $dialog.dialog('open');
    return; 
}

function changepic(element, tag, createddate) {
    var $this = $(element),
        $parent = $this.closest('.main-compare');
    
    $parent.find('img.' +tag).attr('src', $this.attr('src'));
    $parent.find('span.' +tag).empty().append(plang.get('created_date') + ': ' + createddate);
}

function ntrBillPrint (data, dataViewId) {
    
    var $dialogName = 'dialog-confirm-' + getUniqueId(1);
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    
    var $uniqId = getUniqueId(1);
    
    var $html = '';
    $html += '<div class="input-group input-group-criteria mb-2 mt-2" id="bp-window-'+ $uniqId +'" style="float: left;">';
        $html += '<input type="text" class="form-control form-control-sm stringInit" data-path="temp-mail" value="" placeholder="Цахим шуудан оруулах">';
        $html += '<span class="input-group-btn ">';
            $html += '<button type="button" class="btn btn-sm dropdown-toggle criteria-condition-btn  dropdown-none-arrow btn-info" data-toggle="dropdown" aria-expanded="true" tabindex="-1" onclick="sendMailBill(this, \''+ data.servicebookid + '\', \''+ dataViewId +'\', \''+ $dialogName +'\')" style="padding: 0px 5px 0px 5px;border-bottom-left-radius: 0; border-top-left-radius: 0; font-size: 13.5px;">Илгээх</button>';
        $html += '</span> ';
        $html += '<button type="button" class="btn green-meadow btn-sm checktype-text-ntr w-100 mt-2" onclick="printBillCom(this, \''+ data.servicebookid + '\', \''+ dataViewId +'\', \''+ $dialogName +'\')">Хэвлэх</button>'
    $html += '</div>';
    
    $("#" + $dialogName).empty().append($html);
    $("#" + $dialogName).dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: 'Үйлдэл',
        width: 260,
        height: "auto",
        modal: true,
        closeOnEscape: false,
        close: function() {
            $("#" + $dialogName).empty().dialog('destroy').remove();
        },
        buttons: [/*{
                text: 'Хэвлэх',
                class: 'btn green-meadow btn-sm checktype-text-ntr',
                click: function() {
                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: "contentui/ntrBillPrint",
                        data: {data: data, email: $("#" + $dialogName).find('input').val()},
                        beforeSend: function() {
                            Core.blockUI();
                        },
                        success: function(response) {
                            dataViewReload(dataViewId);
                            $("#" + $dialogName).dialog('close');
                            
                            var $dialogName = 'testbillPrint';
                            if (typeof response.status !== 'undefined' && response.status === 'error') {
                                Core.unblockUI();
                                PNotify.removeAll();
                                new PNotify({
                                    title: response.status,
                                    text: response.text,
                                    type: response.status,
                                    sticker: false
                                });
                                return;
                            }

                            if (!$("#" + $dialogName).length) {
                                $('<div id="' + $dialogName + '" class="d-none"></div>').appendTo('body');
                            }

                            $("div#" + $dialogName).empty().append(response.html).promise().done(function() {
                                $("div#" + $dialogName).printThis({
                                    debug: false,
                                    importCSS: false,
                                    printContainer: false,
                                    dataCSS: response.css,
                                    removeInline: false
                                });
                            });

                            Core.unblockUI();
                        },
                        error: function (jqXHR, exception) {
                            Core.unblockUI();
                            Core.showErrorMessage(jqXHR, exception);
                        }
                    });
                }
            },
            {
                text: 'Хаах',
                class: 'btn blue-madison btn-sm',
                click: function() {
                    $("#" + $dialogName).dialog('close');
                }
            }*/
        ]
    }).dialogExtend({
        "closable": false,
        "maximizable": false,
        "minimizable": false,
        "collapsable": false,
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
    
    $("#" + $dialogName).dialog('open');
    
    Core.initAjax($("#" + $dialogName));
}

function printBillCom (element, id, dataViewId, $dialogName1) {
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: "contentui/ntrBillPrint",
        data: {servicebookid: id},
        beforeSend: function() {
            Core.blockUI();
            $("#" + $dialogName1).dialog('close');
        },
        success: function(response) {
            dataViewReload(dataViewId);

            var $dialogName = 'testbillPrint';
            
            if (typeof response.status !== 'undefined' && response.status === 'error') {
                Core.unblockUI();
                PNotify.removeAll();
                new PNotify({
                    title: response.status,
                    text: response.text,
                    type: response.status,
                    sticker: false
                });
                return;
            }

            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="d-none"></div>').appendTo('body');
            }

            $("div#" + $dialogName).empty().append(response.html).promise().done(function() {
                $("div#" + $dialogName).printThis({
                    debug: false,
                    importCSS: false,
                    printContainer: false,
                    dataCSS: response.css,
                    removeInline: false
                });
            });

            Core.unblockUI();
        },
        error: function (jqXHR, exception) {
            Core.unblockUI();
            Core.showErrorMessage(jqXHR, exception);
        }
    });
}

function sendMailBill(element, id, dataViewId, $dialogName1) {
    var $mail = $(element).closest('.input-group').find('input[data-path="temp-mail"]').val();
    
    if (!$mail) {
        PNotify.removeAll();
        new PNotify({
            title: 'Анхааруулга',
            text: 'Илгээх имэйл хаягаа оруулна уу?',
            type: 'warning',
            sticker: false
        });
        return;
    }
    
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: "contentui/ntrBillPrint",
        data: {servicebookid: id, type: 'sendmail', email: $mail},
        beforeSend: function() {
            Core.blockUI();
            $("#" + $dialogName1).dialog('close');
        },
        success: function (response) {
            dataViewReload(dataViewId);
            
            Core.unblockUI();
            PNotify.removeAll();
            new PNotify({
                title: 'Success',
                text: 'Амжилттай илгээгдлээ, <strong>'+ $mail +'</strong> имэйл хаягаа шалгана уу?',
                type: 'success',
                sticker: false
            });
            return;
        },
        error: function (jqXHR, exception) {
            Core.unblockUI();
            Core.showErrorMessage(jqXHR, exception);
        }
    });
}

function ntrReturnBill(elem, processMetaDataId, dataViewId, selectedRow) {
    if (typeof selectedRow === 'undefined') {
        alert('Мөрөө сонгоно уу!');
        return;
    }  
    
    if (typeof selectedRow['billid'] === 'undefined') {
        PNotify.removeAll();
        new PNotify({
            title: 'warning',
            text: 'Сонгосон мөрөнд буцаалт хийхэд шаардлагатай мэдээлэл байхгүй байна.',
            type: 'warning',
            sticker: false
        });
        return;
    }  
    
    if (!selectedRow['billid']) {
        PNotify.removeAll();
        new PNotify({
            title: 'warning',
            text: 'Сонгосон мөрөнд буцаалт хийхэд шаардлагатай мэдээлэл байхгүй байна.',
            type: 'warning',
            sticker: false
        });
        return;
    }  

    var $dialogConfirm = 'dialog-confirm-addressinfo-'+ getUniqueId(1);
    if (!$("#" + $dialogConfirm).length) {
        $('<div id="' + $dialogConfirm + '"></div>').appendTo('body');
    }

    var $dialogC = $("#" + $dialogConfirm);
    var $html = 'Баримт буцаалт хийхдээ итгэлтэй байна уу?';

    $dialogC.empty().append($html);
    $dialogC.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: 'Баталгаажуулалт',
        width: 400,
        height: "auto",
        modal: true,
        close: function () {
            $dialogC.empty().dialog('destroy').remove();
        },
        buttons: [
            {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: "contentui/ntrReturnBill",
                    data: {selectedRow: selectedRow},
                    beforeSend: function() {
                        Core.blockUI();
                    },
                    success: function(response) {
                        Core.unblockUI();
                        PNotify.removeAll();
                        new PNotify({
                            title: response.status,
                            text: response.text,
                            type: response.status,
                            sticker: false
                        });
                        dataViewReload(dataViewId);
                    },
                    error: function (jqXHR, exception) {
                        Core.unblockUI();
                        Core.showErrorMessage(jqXHR, exception);
                    }
                });
                $dialogC.dialog('close');
            }},
            {text: plang.get('no_btn') , class: 'btn blue-madison btn-sm', click: function () {
                $dialogC.dialog('close');
            }}
        ]
    });
    
    $dialogC.dialog('open');
}

function covid19Book (paramData, selectedRow, dataViewId) {
    console.log(paramData, selectedRow, dataViewId);
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: "contentui/covid19Book",
        data: {data: paramData, selectedRow: selectedRow},
        beforeSend: function() {
            Core.blockUI();
        },
        success: function(response) {
            var $dialogConfirm = 'dialog-book-' + response.uniqId;

            if (!$("#" + $dialogConfirm).length) {
                $('<div id="' + $dialogConfirm + '" class="display-none"></div>').appendTo('body');
            }

            var $dialog = $("#" + $dialogConfirm);
            $dialog.empty().append(response.Html).promise().done(function() {
                Core.initAjax($('.xsform' + response.uniqId));
            });
            
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: response.Title,
                width: response.Width,
                height: "auto",
                modal: true,
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                            
                        $('.xsform' + response.uniqId).ajaxSubmit({
                            type: 'post',
                            url: 'contentui/saveDataBookCvd19',
                            dataType: 'json',
                            beforeSend: function () {
                                Core.blockUI();
                            },
                            success: function (responseData) {
                                PNotify.removeAll();
                                new PNotify({
                                    title: responseData.status,
                                    text: responseData.text,
                                    type: responseData.status, 
                                    sticker: false
                                });

                                Core.unblockUI();
                            },
                            error: function(jqXHR, exception) {
                                Core.unblockUI();
                                Core.showErrorMessage(jqXHR, exception);
                            }
                        });
                    }},
                    {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });

            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function (jqXHR, exception) {
            Core.unblockUI();
            Core.showErrorMessage(jqXHR, exception);
        }
    });
    
}

function editNtrProcess (elem, processMetaDataId, dataViewId, selectedRow) {
    appMultiTab({ weburl: 'contentui/editNotaryProcess/' + selectedRow['id'], metaDataId: 'edit_' + dataViewId, dataViewId: dataViewId, id: selectedRow['id'], title: plang.get('edit_ntr'), type: 'selfurl', tabReload: true, rowId: selectedRow['id']}, this, function(elem) {});
}

function fillDanData(danData, $processId) {
    try {
        
        if (typeof $processId !== 'undefined') {
            var $mainSelector = $('body').find('div[id="bp-window-'+ $processId +'"]');
        } else {
            var $mainSelector = $('body').find('div[id="bp-window-16124218420162"]');
        }
    
        var $uniqId = $mainSelector.attr('data-bp-uniq-id');
        if (typeof danData['1'] !== 'undefined' &&
            typeof danData['1']['services'] !== 'undefined' &&
            typeof danData['1']['services']['WS100101_getCitizenIDCardInfo'] !== 'undefined' &&
            typeof danData['1']['services']['WS100101_getCitizenIDCardInfo']['resultCode'] !== 'undefined') {
            var $cardinfoSms = danData['1']['services']['WS100101_getCitizenIDCardInfo']['resultMessage'];
            var $cardCode = danData['1']['services']['WS100101_getCitizenIDCardInfo']['resultCode'];
    
            if ($cardCode == 0) {
                /* new PNotify({title: 'success', text: 'Иргэний мэдээлэл дуудахад <br><strong>'+ $cardinfoSms +'</strong>.', type: 'success', sticker: false, addclass: pnotifyPosition});*/
            } else {
                new PNotify({ title: 'warning', text: 'Иргэний мэдээлэл дуудахад <br><strong>' + $cardinfoSms + '</strong>.', type: 'warning', sticker: false, addclass: pnotifyPosition });
            }
        }
        if (typeof danData['1'] !== 'undefined' &&
            typeof danData['1']['services'] !== 'undefined' &&
            typeof danData['1']['services']['WS100155_citizenGraduateSchoolList'] !== 'undefined' &&
            typeof danData['1']['services']['WS100155_citizenGraduateSchoolList']['resultCode'] !== 'undefined') {
            var $schoolinfoSms = danData['1']['services']['WS100155_citizenGraduateSchoolList']['resultMessage'];
            var $schCode = danData['1']['services']['WS100155_citizenGraduateSchoolList']['resultCode'];
    
            if ($schCode == 0) {
                /*new PNotify({title: 'success', text: 'Сургуулийн мэдээлэл дуудахад <br><strong>'+ $schoolinfoSms +'</strong>.', type: 'success', sticker: false, addclass: pnotifyPosition});*/
            } else {
                new PNotify({ title: 'warning', text: 'Сургуулийн мэдээлэл дуудахад <br><strong>' + $schoolinfoSms + '</strong>.', type: 'warning', sticker: false, addclass: pnotifyPosition });
            }
        }
        if (typeof danData['2'] !== 'undefined' &&
            typeof danData['2']['services'] !== 'undefined' &&
            typeof danData['2']['services']['WS100501_getCitizenSalaryInfo'] !== 'undefined' &&
            typeof danData['2']['services']['WS100501_getCitizenSalaryInfo']['resultCode'] !== 'undefined') {
            var $salainfoSms = danData['2']['services']['WS100501_getCitizenSalaryInfo']['resultMessage'];
            var $salCode = danData['2']['services']['WS100501_getCitizenSalaryInfo']['resultCode'];
    
            if ($salCode == 0) {
                /*new PNotify({title: 'success', text: 'Нийгмийн даатгалын мэдээлэл дуудахад <br><strong>'+ $salainfoSms +'</strong>.', type: 'success', sticker: false, addclass: pnotifyPosition});*/
            } else {
                new PNotify({ title: 'warning', text: 'Нийгмийн даатгалын мэдээлэл дуудахад <br><strong>' + $salainfoSms + '</strong>.', type: 'warning', sticker: false, addclass: pnotifyPosition });
            }
    
        }
        if (typeof danData['1'] !== 'undefined') {
            console.log(danData);
            if (typeof danData['1'] !== 'undefined' &&
                typeof danData['1']['services'] !== 'undefined' &&
                typeof danData['1']['services']['WS100101_getCitizenIDCardInfo'] !== 'undefined' &&
                typeof danData['1']['services']['WS100101_getCitizenIDCardInfo']['response'] !== 'undefined') {
                var $citizenCardInfo = danData['1']['services']['WS100101_getCitizenIDCardInfo']['response'];
                console.log($citizenCardInfo);
                $mainSelector.find('input[data-path="stateRegNumber"]:eq(0)').val($citizenCardInfo['regnum']);
                $mainSelector.find('input[data-path="lastName"]:eq(0)').val($citizenCardInfo['lastname']);
                $mainSelector.find('input[data-path="firstName"]:eq(0)').val($citizenCardInfo['firstname']);
                $mainSelector.find('input[data-path="urag"]:eq(0)').val($citizenCardInfo['surname']);
                $mainSelector.find('input[data-path="dateOfBirth"]:eq(0)').val($citizenCardInfo['birthDateAsText']);
                $mainSelector.find('input[data-path="stateRegNumber"]:eq(0)').val($citizenCardInfo['regnum']);
    
                var _gender = $citizenCardInfo['gender'];
                var _g = strtolower(_gender);
    
                if (_g == 'эрэгтэй' || _g == 'эр') {
                    $mainSelector.find('select[data-path="gender"]:eq(0)').trigger("select2-opening", [true]);
                    $mainSelector.find('select[data-path="gender"]:eq(0)').select2('val', '1');
                } else if (_g == 'эмэгтэй' || _g == 'эм') {
                    $mainSelector.find('select[data-path="gender"]:eq(0)').trigger("select2-opening", [true]);
                    $mainSelector.find('select[data-path="gender"]:eq(0)').select2('val', '2');
                }
    
                $mainSelector.find('input[data-path="picture"]:eq(0)').val($citizenCardInfo['image']);
                $mainSelector.find('input[data-path="orts"]:eq(0)').val($citizenCardInfo['addressApartmentName']);
                $mainSelector.find('input[data-path="door"]:eq(0)').val($citizenCardInfo['addressDetail']);
                $mainSelector.find('textarea[data-path="postAddress"]:eq(0)').val($citizenCardInfo['passportAddress']);
                $mainSelector.find('input[data-path="city"]:eq(0)').val($citizenCardInfo['aimagCityCode']);
                $mainSelector.find('input[data-path="district"]:eq(0)').val($citizenCardInfo['soumDistrictCode']);
                $mainSelector.find('input[data-path="street"]:eq(0)').val($citizenCardInfo['bagKhorooCode']);
    
            }
    
            if (typeof danData['1'] !== 'undefined' &&
                typeof danData['1']['services'] !== 'undefined' &&
                typeof danData['1']['services']['WS100155_citizenGraduateSchoolList'] !== 'undefined' &&
                typeof danData['1']['services']['WS100155_citizenGraduateSchoolList']['response'] !== 'undefined'
            ) {
                var $dataRow = [];
                var $danSchoolInfo_1234 = danData['1']['services']['WS100155_citizenGraduateSchoolList']['response'];
                if ($danSchoolInfo_1234 && typeof $danSchoolInfo_1234['listData'] !== 'undefined') {
                    console.log($danSchoolInfo_1234['listData']);
                    $.each($danSchoolInfo_1234['listData'], function (index, row) {
                        var $dd = ConvertKeysToLowerCase(row);
                        $dataRow.push($dd);
                    });
                    console.log($dataRow);
                    window['setDanSchool_' + $uniqId]($dataRow);
                }
            }
        }
    
        if (typeof danData['2'] !== 'undefined' &&
            typeof danData['2']['services'] !== 'undefined' &&
            typeof danData['2']['services']['WS100501_getCitizenSalaryInfo'] !== 'undefined' &&
            typeof danData['2']['services']['WS100501_getCitizenSalaryInfo']['response'] !== 'undefined'
        ) {
            var $danSalary_1234 = danData['2']['services']['WS100501_getCitizenSalaryInfo']['response'];
            if ($danSalary_1234 && typeof $danSalary_1234['list'] !== 'undefined') {
                var $dataRow = [];
                console.log($danSalary_1234['list']);
                $.each($danSalary_1234['list'], function (index, row) {
                    var $dd = ConvertKeysToLowerCase(row);
                    $dataRow.push($dd);
                });
                console.log($dataRow);
                window['setDanSalaryInfo_' + $uniqId]($dataRow);
                /* setDanSalaryInfo($dataRow); */
            }
        }
    
        if (
            typeof danData['1'] !== 'undefined' &&
            typeof danData['1']['services'] !== 'undefined' &&
            typeof danData['1']['services']['WS100101_getCitizenIDCardInfo'] !== 'undefined' &&
            typeof danData['1']['services']['WS100101_getCitizenIDCardInfo']['response'] !== 'undefined' &&
            typeof danData['1']['services']['WS100101_getCitizenIDCardInfo']['response']['aimagCityCode'] !== 'undefined' &&
            typeof danData['1']['services']['WS100101_getCitizenIDCardInfo']['response']['soumDistrictCode'] !== 'undefined' &&
            typeof danData['1']['services']['WS100101_getCitizenIDCardInfo']['response']['bagKhorooCode'] !== 'undefined'
        ) {
            var city = danData['1']['services']['WS100101_getCitizenIDCardInfo']['response']['aimagCityCode'];
            var district = danData['1']['services']['WS100101_getCitizenIDCardInfo']['response']['soumDistrictCode'];
            var street = danData['1']['services']['WS100101_getCitizenIDCardInfo']['response']['bagKhorooCode'];
            window['setAddress_' + $uniqId](city, district, street);
        }
        /*danState = localStorage.getItem('dan_state');*/
        /*localStorage.removeItem(danState);*/
    } catch (error) {
        console.log(error);
    }

}

function ConvertKeysToLowerCase(obj) {
    var output = {};
    for (i in obj) {
        if (Object.prototype.toString.apply(obj[i]) === '[object Object]') {
            output[i.toLowerCase()] = ConvertKeysToLowerCase(obj[i]);
        } else if (Object.prototype.toString.apply(obj[i]) === '[object Array]') {
            output[i.toLowerCase()] = [];
            output[i.toLowerCase()].push(ConvertKeysToLowerCase(obj[i][0]));
        } else {
            output[i.toLowerCase()] = obj[i];
        }
    }
    return output;
};

function step1Exam(elem, dataViewId, selectedRow) {
    var $html = '';
    var dlname = 'modal_iconified';
    startExam(selectedRow.id, selectedRow.examname, dataViewId, undefined, selectedRow);
}

function startExam(examBookId, examName, dataviewId, dlname, selectedRow) {
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'contentui/startExam',
        data: { examBookId: examBookId, selectedRow: selectedRow },
        beforeSend: function () {
            Core.blockUI({
                boxed: true,
                message: 'Түр хүлээнэ үү...'
            });
        },
        success: function (data) {
            if (typeof data.status !== 'undefined' && data.status === 'success') {
                appMultiTabByContent({ weburl: 'contentui/startExam', metaDataId: examBookId, title: examName, type: 'selfurl', content: data.Html }, function () {
                    if (typeof dlname !== 'undefined') {
                        $('#' + dlname).modal('hide');
                    }
                });
            } else {
                new PNotify({
                    title: data.status,
                    text: data.text,
                    type: data.status,
                    sticker: false
                });
            }
            Core.unblockUI();
        }
    });
}

function step2Exam($this, dmMetaDataId, rowData, renderTag, titleTag, dvTwoWindowHeight, addonProcess) {
    
    if (typeof rowData['showerrortext'] !== 'undefined' && rowData['showerrortext']) {
        
        new PNotify({
            title: 'Анхааруулга',
            text: rowData['showerrortext'],
            type: 'error',
            sticker: false
        });
        return;
    }

    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'contentui/startExam',
        data: { examBookId: rowData.id, type: '1', selectedRow: rowData },
        beforeSend: function () {
            Core.blockUI({
                boxed: true,
                message: 'Түр хүлээнэ үү...'
            });
        },
        success: function (data) {
            if (typeof data.status !== 'undefined' && data.status === 'success') {
                titleTag.find('#dv-twocol-view-title').html('Шалгалт');
                renderTag.empty().append(data.Html).promise().done(function () {
                    if (rowData.hasOwnProperty('ishideprocess') && rowData.ishideprocess == '1') {
                        addonProcess = '';
                    }

                    if (rowData.hasOwnProperty('ishidetitle') && rowData.ishidetitle == '1') {
                        titleTag.find('#dv-twocol-view-title').html(addonProcess);
                    } else {

                        if (typeof isProcessDropDown !== 'undefined' && isProcessDropDown) {

                            var rowTitle = $this.find('span:last').text();
                            var processTitle = '<div style="float: left;width: calc(100% - 45px);height: 30px;display: -webkit-box;display: -ms-flexbox;display: flex;-webkit-box-align: center;-ms-flex-align: center;align-items: center;-webkit-box-pack: center;-ms-flex-pack: center;justify-content: center;" title="' + rowTitle + '"><div style="display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;line-height: 16px;font-size: 11px;">' + rowTitle + '</div></div>';

                            titleTag.find('#dv-twocol-view-title').html(addonProcess + processTitle);
                        } else {
                            titleTag.find('#dv-twocol-view-title').html(addonProcess + $this.find('span:last').text());
                        }
                    }

                    var $metaToolbar = renderTag.find('.meta-toolbar:eq(0)');
                    var $firstRow = renderTag.find('> .row > .xs-form > form > .row:eq(0)');

                    $metaToolbar.addClass('float-right');
                    renderTag.find('.bp-btn-back, .bpTestCaseSaveButton, #boot-fileinput-error-wrap').remove();

                    if ($firstRow.length) {
                        $firstRow.css({
                            'overflow': 'auto',
                            'max-height': dvTwoWindowHeight - $metaToolbar.offset().top - 80,
                            'margin-left': '-20px',
                            'margin-right': '-20px'
                        });
                    } else {
                        var $firstTabContent = renderTag.find('> .row > .xs-form > form > .tabbable-line:eq(0) > .tab-content');
                        if ($firstTabContent.length) {
                            $firstTabContent.css({
                                'overflow-x': 'hidden',
                                'overflow-y': 'auto',
                                'max-height': dvTwoWindowHeight - $metaToolbar.offset().top - 128
                            });
                            $metaToolbar.css('margin-bottom', '8px');
                        }
                    }

                });
            } else {
                new PNotify({
                    title: data.status,
                    text: data.text,
                    type: data.status,
                    sticker: false
                });
            }
            Core.unblockUI();
        }
    });
}

function step3Exam($this, dmMetaDataId, rowData, renderTag, titleTag, dvTwoWindowHeight) {
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'contentui/examResult',
        data: { examBookId: rowData.exambookid, examStudentId: rowData.id, rowData: rowData},
        beforeSend: function () {
            Core.blockUI({
                boxed: true,
                message: 'Түр хүлээнэ үү...'
            });
        },
        success: function (data) {
            if (typeof data.status !== 'undefined' && data.status === 'success') {
                appMultiTabByContent({ weburl: 'contentui/examResult', metaDataId: rowData.exambookid, title: rowData.fullname, type: 'selfurl', content: data.Html }, function () {
                    if (typeof dlname !== 'undefined') {
                        $('#' + dlname).modal('hide');
                    }
                });
            } else {
                new PNotify({
                    title: data.status,
                    text: data.text,
                    type: data.status,
                    sticker: false
                });
            }
            Core.unblockUI();
        }
    });
}

function view1Exam($this, dmMetaDataId, rowData, renderTag, titleTag, dvTwoWindowHeight, addonProcess) {
    
    if (rowData.hasOwnProperty('topmetadataid') && rowData.topmetadataid) {
        var processId = rowData.topmetadataid;

        $.ajax({
            type: 'post',
            url: 'contentui/permission',
            dataType: 'json',
            data: { topicId: rowData.parentid, selectedRow: rowData },
            beforeSend: function () {
                Core.blockUI({ animate: true });
            },
            success: function (cresponse) {
                if (typeof cresponse.Html !== 'undefined') {
                    renderTag.empty().append(cresponse.Html).promise().done(function () {

                        Core.unblockUI();
                    });
                    return;
                } else {
                    $.ajax({
                        type: 'post',
                        url: 'mdwebservice/callMethodByMeta',
                        data: {
                            metaDataId: processId,
                            dmMetaDataId: dmMetaDataId,
                            isDialog: false,
                            isHeaderName: false,
                            isBackBtnIgnore: 1,
                            oneSelectedRow: rowData,
                            callerType: 'dv',
                            openParams: '{"callerType":"dv","afterSaveNoAction":true,"afterSaveNoActionFnc":"panelRowClickDvRefreshSecondList(<?php echo $this->uniqId; ?>, \'1\', true)"}'
                        },
                        dataType: 'json',
                        beforeSend: function () {
                            Core.blockUI({ message: 'Түр хүлээнэ үү...', boxed: true });
                        },
                        success: function (data) {
                            var $uniqId = data.uniqId;
                            renderTag.empty().append(data.Html).promise().done(function () {
                                if (rowData.hasOwnProperty('ishideprocess') && rowData.ishideprocess == '1') {
                                    addonProcess = '';
                                }
    
                                if (rowData.hasOwnProperty('ishidetitle') && rowData.ishidetitle == '1') {
                                    titleTag.find('#dv-twocol-view-title').html(addonProcess);
                                } else {
    
                                    if (typeof isProcessDropDown !== 'undefined' && isProcessDropDown) {
    
                                        var rowTitle = $this.find('span:last').text();
                                        var processTitle = '<div style="float: left;width: calc(100% - 45px);height: 30px;display: -webkit-box;display: -ms-flexbox;display: flex;-webkit-box-align: center;-ms-flex-align: center;align-items: center;-webkit-box-pack: center;-ms-flex-pack: center;justify-content: center;" title="' + rowTitle + '"><div style="display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;line-height: 16px;font-size: 11px;">' + rowTitle + '</div></div>';
    
                                        titleTag.find('#dv-twocol-view-title').html(addonProcess + processTitle);
                                    } else {
                                        titleTag.find('#dv-twocol-view-title').html(addonProcess + $this.find('span:last').text());
                                    }
                                }
    
                                var $metaToolbar = renderTag.find('.meta-toolbar:eq(0)');
                                var $firstRow = renderTag.find('> .row > .xs-form > form > .row:eq(0)');
    
                                $metaToolbar.addClass('float-right');
                                renderTag.find('.bp-btn-back, .bpTestCaseSaveButton, #boot-fileinput-error-wrap').remove();
    
                                if ($firstRow.length) {
                                    $firstRow.css({
                                        'overflow': 'auto',
                                        'max-height': dvTwoWindowHeight - $metaToolbar.offset().top - 80,
                                        'margin-left': '-20px',
                                        'margin-right': '-20px'
                                    });
                                } else {
                                    var $firstTabContent = renderTag.find('> .row > .xs-form > form > .tabbable-line:eq(0) > .tab-content');
                                    if ($firstTabContent.length) {
                                        $firstTabContent.css({
                                            'overflow-x': 'hidden',
                                            'overflow-y': 'auto',
                                            'max-height': dvTwoWindowHeight - $metaToolbar.offset().top - 128
                                        });
                                        $metaToolbar.css('margin-bottom', '8px');
                                    }
                                }
                                
                                var $callbackFnc = 'saveCamAttendance';
                                if (typeof rowData.iscustomer !== 'undefined' && rowData.iscustomer == '1') {
                                    $callbackFnc = 'saveCamAttendanceCustomer';
                                }

                                $.ajax({
                                    type: 'post',
                                    url: 'mdwebservice/renderEditModeBpFileTab',
                                    data: { uniqId: $uniqId, refStructureId: rowData.refstrucureid, sourceId: rowData.id, actionType: 'view', callbackFnc: $callbackFnc },
                                    beforeSend: function () {
                                        if (!$("link[href='assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css']").length) {
                                            $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
                                        }
                                        Core.blockUI({ animate: true });
                                    },
                                    success: function (data) {
                                        var $html1 = '<fieldset class="collapsible"> <legend>' + plang.get('file') + '</legend>' + data + '</fieldset>';
                                        renderTag.append($html1).promise().done(function () {
                                            Core.initFancybox(renderTag);
                                            Core.unblockUI();
    
                                            $.ajax({
                                                type: 'post',
                                                url: 'mdwebservice/renderEditModeBpCommentTab',
                                                data: { uniqId: $uniqId, refStructureId: rowData.refstrucureid, sourceId: rowData.id },
                                                beforeSend: function () {
                                                    Core.blockUI({ animate: true });
                                                },
                                                success: function (data) {
                                                    var $html2 = '<fieldset class="collapsible"> <legend>' + plang.get('comment') + '</legend>' + data + '</fieldset>';
                                                    renderTag.append($html2);
                                                    Core.unblockUI();
                                                },
                                                error: function () {
                                                    alert("Error comment loading...");
                                                }
                                            });
                                        });
                                    },
                                    error: function () {
                                        alert("Error file loading...");
                                    }
                                });
    
                                Core.initBPAjax(renderTag);
                                Core.unblockUI();
                            });
                        },
                        error: function () { alert('Error'); Core.unblockUI(); }
                    });
                }
            },
            error: function () {}
        });
    }
}

function saveCamAttendance(elem, tabName, rowId, fileExtension, fileName, fullPath, contentId, dvId, refStructureId) {
    $.ajax({
        type: 'post',
        url: 'contentui/saveCamAttendance',
        data: { rowId: rowId},
        beforeSend: function () {},
        success: function () {},
        error: function () {}
    }).done(function () {
        var opts = { tabName: tabName, rowId: rowId, fileExtension: fileExtension, fileName: fileName, fullPath: fullPath, contentId: contentId, dvId: dvId, refStructureId: refStructureId };
        initFileViewer(elem, opts);
    });
}

function saveCamAttendanceCustomer(elem, tabName, rowId, fileExtension, fileName, fullPath, contentId, dvId, refStructureId) {
    $.ajax({
        type: 'post',
        url: 'contentui/saveCamAttendanceCustomer',
        data: { rowId: rowId},
        beforeSend: function () {},
        success: function () {},
        error: function () {}
    }).done(function () {
        var opts = { tabName: tabName, rowId: rowId, fileExtension: fileExtension, fileName: fileName, fullPath: fullPath, contentId: contentId, dvId: dvId, refStructureId: refStructureId };
        initFileViewer(elem, opts);
    });
}

function evisamore (element, datavieId, selectedRow) {
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'contentui/visamore',
        data: {
            id: selectedRow.id,
            selectedRow: selectedRow,
            datavieId: datavieId,
        },
        beforeSend: function() {
            Core.blockUI({
                boxed: true,
                message: 'Түр хүлээнэ үү...'
            });
        },
        success: function(data) {
            if (data.status !== 'success') {
                new PNotify({
                    title: data.status,
                    text: data.text,
                    type: data.status,
                    sticker: false
                });
                Core.unblockUI();
                return;
            }
            
            var $dialogName = 'dialog-preview-more';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            
            $("#" + $dialogName).empty().append(data.Html);
            $("#" + $dialogName).dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 1000,
                height: "auto",
                modal: true,
                close: function() {
                    $("#" + $dialogName).empty().dialog('close');
                },
                buttons: [
                    {
                        text: plang.get('close_btn'),
                        class: 'btn blue-madison btn-sm',
                        click: function() {
                            $("#" + $dialogName).dialog('close');
                        }
                    }
                ]
            });
            $("#" + $dialogName).dialog('open');

            Core.unblockUI();
        },
        error: function (jqXHR, exception) {
            /* Core.showErrorMessage(jqXHR, exception); */
            new PNotify({
                title: 'Error',
                text: plang.get('water_mark_error_001'),
                type: 'error',
                sticker: false
            });
            Core.unblockUI();
        }
    });
}

function declineSentEmail(responseData, type) {
    if (typeof type !== 'undefined' && type === 'refere') {
        if (typeof responseData.rowId !== 'undefined' && responseData.rowId) {
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: 'contentui/mailDeclineDtl/',
                data: {id: responseData.rowId},
                beforeSend: function () {
                    Core.blockUI({
                        boxed: true,
                        message: 'Түр хүлээнэ үү...'
                    });
                },
                success: function (data) {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    Core.unblockUI();
                }
            });
        }
    } else {
        if (
            typeof responseData.resultData !== 'undefined' && responseData.resultData &&
            typeof responseData.resultData.applicationnumber !== 'undefined' && responseData.resultData.applicationnumber
        ) {
            var $applicationNumber = responseData.resultData.applicationnumber;
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: 'contentui/mailDecline/' + $applicationNumber,
                data: {
                    params: type,
                    responseData: responseData,
                },
                beforeSend: function () {
                    Core.blockUI({
                        boxed: true,
                        message: 'Түр хүлээнэ үү...'
                    });
                },
                success: function (data) {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    Core.unblockUI();
                }
            });
        }
    }
}

function confirmSentEmail(responseData, type) {
    
    if (typeof type !== 'undefined' && type === 'refere') {
        if (typeof responseData.rowId !== 'undefined' && responseData.rowId) {
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: 'contentui/mailConfirmDtl/',
                data: {id: responseData.rowId},
                beforeSend: function () {
                    Core.blockUI({
                        boxed: true,
                        message: 'Түр хүлээнэ үү...'
                    });
                },
                success: function (data) {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    Core.unblockUI();
                }
            });
        }
    } else {
        if (
            typeof responseData.resultData !== 'undefined' && responseData.resultData &&
            typeof responseData.resultData.applicationnumber !== 'undefined' && responseData.resultData.applicationnumber
        ) {
            var $applicationNumber = responseData.resultData.applicationnumber;
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: 'contentui/mailConfirm/' + $applicationNumber,
                beforeSend: function () {
                    Core.blockUI({
                        boxed: true,
                        message: 'Түр хүлээнэ үү...'
                    });
                },
                success: function (data) {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    Core.unblockUI();
                }
            });
        }
    }
}

function watermarkPdf (rowId, getDataViewId) {
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'contentui/watermarkPdf/',
        data: { rowId: rowId, dataViewId: getDataViewId},
        beforeSend: function () {
            Core.blockUI({
                boxed: true,
                message: 'Түр хүлээнэ үү...'
            });
        },
        success: function (data) {
            new PNotify({
                title: data.status,
                text: data.text,
                type: data.status,
                sticker: false
            });
            Core.unblockUI();
        },
        error: function (jqXHR, exception) {
            /* Core.showErrorMessage(jqXHR, exception); */
            new PNotify({
                title: 'Error',
                text: plang.get('water_mark_error_001'),
                type: 'error',
                sticker: false
            });
            Core.unblockUI();
        }
    });
}

function docToPdfConfirm ($elementy, $serviceBookId, dataViewId, $confirmType, $parentSelector, $cDialogName, parentMethods, ntrWfmStatusId, ntrWfmDescription) {
    $.ajax({
        type: 'post',
        url: 'mddoc/confirmNtrServicePdf',
        data: {
            id: $serviceBookId,
            dataViewId: dataViewId,
            confirmType: $confirmType,
            bookDateCustome: $($cDialogName).find('input[name="bookDateCust"]').val()
        },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Түр хүлээнэ үү...', boxed: true});
        },
        success: function(data) {
            var selectedRow = {'physicalpath': data.filepath, 'id': data.recordId, qrcode: data.qrcode, ntrwfmstatusid: ntrWfmStatusId, ntrwfmdescription: ntrWfmDescription};
            var _processId = (typeof $parentSelector !== 'undefined' && typeof $parentSelector.attr('data-process-id') !== 'undefined') ? $parentSelector.attr('data-process-id') : '';
            docToPdfUpload($elementy, _processId, dataViewId, selectedRow, {}, $confirmType, 'mddoc/docToPdfUploadConfirm', function() { if (typeof parentMethods !== 'undefined') { docToPdfConfirm(parentMethods['elementy'], parentMethods['parentId'], parentMethods['dataViewId'], parentMethods['confirmType'], parentMethods['parentSelector'], undefined, parentMethods['pprocessId']); } Core.unblockUI(); if (typeof $cDialogName !== 'undefined') {  $($cDialogName).empty().dialog('destroy').remove(); } backFirstContent($elementy); return true; });
        },
        error: function() {
            alert('Error');
        }
    });
}