var IS_LOAD_GL_SCRIPT = true;
var cashHandBank = ['CASH_IN_TRANSIT', 'CASH_ON_BANK', 'CASH_ON_HAND'];

//<editor-fold defaultstate="collapsed" desc="old version functions of general ledger">
function addImageGl(elem) {
    var getTable = $(elem).closest("table");
    $("tbody", getTable).append(
        '<tr>'+
            '<td style="width: 210px"><input type="file" name="gl_photo[]" class="col-md-12" onchange="hasPhotoExtension(this);"></td>'+
            '<td><input type="text" name="gl_photo_name[]" class="form-control col-md-12" placeholder="Тайлбар"/></td>'+
            '<td>'+
                '<a href="javascript:;" class="btn btn-xs btn-danger" onclick="removeMetaPhoto(this);"><i class="icon-cross2 font-size-12"></i></a>' + 
            '</td>'+
        '</tr>');  
}
function addFileGl(elem) {
    var getTable = $(elem).closest("table");
    $("tbody", getTable).append(
        '<tr>'+
            '<td style="width: 210px"><input type="file" name="gl_file[]" class="col-md-12" onchange="hasFileExtension(this);"></td>'+
            '<td><input type="text" name="gl_file_name[]" class="form-control col-md-12" placeholder="Тайлбар"/></td>'+
            '<td>'+
                '<a href="javascript:;" class="btn btn-xs btn-danger" onclick="removeMetaPhoto(this);"><i class="icon-cross2 font-size-12"></i></a>' + 
            '</td>'+
        '</tr>');  
}
function removeMetaPhoto(element) {
    $(element).parent().parent().remove();
}
function appendBpComment(elem) {
    var _this = $(elem);
    var _thisVal = _this.val();
    if ($.trim(_thisVal) != "") {
        var _parent = _this.closest("div.bp-comment-wrap");
        var _chats = _parent.find("ul.chats");
        $.ajax({
            type: 'post',
            url: 'mdwebservice/bpCommentTempSave',
            data: {comment: _thisVal},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                _chats.append(data);
                _this.val("");
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    }
} 
function updateGlTabPhoto(elem) {
    var dialogName = '#update-form-dialog';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    var _this = $(elem);
    var li = _this.parents('li.shadow');
    $.ajax({
        type: 'post',
        url: 'mdwebservice/renderBpTabUpdatePhotoForm',
        data: {metaDataId: '<?php echo Mdgl::$glBookGroupId;?>', metaValueId: '<?php echo $this->row["id"];?>', attachId: li.attr('data-attach-id')},
        dataType: "json",
        beforeSend: function() {
            $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
            Core.blockUI({
                animate: true
            });
        },
        success: function(data) {
            $(dialogName).html(data.Html);
            $(dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: '800',
                height: 'auto',
                modal: true,
                buttons: [
                    {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function() {
                            $('form#glEntryForm').ajaxSubmit({
                                type: 'post',
                                url: 'mdwebservice/renderBpTabUpdatePhoto',
                                dataType: 'json',
                                beforeSend: function() {
                                    Core.blockUI({
                                        animate: true
                                    });
                                },
                                success: function(data) {
                                    PNotify.removeAll();
                                    if (data.status === 'success') {
                                        new PNotify({
                                            title: 'Success',
                                            text: data.message,
                                            type: 'success',
                                            sticker: false
                                        });
                                        var _this = $('li[data-attach-id="' + data.attachId + '"]', 'body');
                                        if (data.photoFileName != '') {
                                            _this.find('a').attr('href', 'storage/uploads/metavalue/photo_original/' + data.photoFileName);
                                            _this.find('a img').attr('src', 'storage/uploads/metavalue/photo_thumb/' + data.photoFileName);
                                        }
                                        if (data.photoName != '' || data.photoName != 'undefined') {
                                            _this.find('.title-photo').html(data.photoName);
                                            _this.find('a.main').attr('title', data.photoName);
                                        }
                                        $(dialogName).dialog('close');
                                    } else {
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });
                                    }
                                    Core.unblockUI();
                                }
                            });
                        }},
                    {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function() {
                            $(dialogName).dialog('close');
                        }}
                ]
            });
            $(dialogName).dialog('open');
            Core.unblockUI();
        },
        error: function() {
            alert("Error update form");
        }
    });
}
function deleteGlTabPhoto(elem) {
    var dialogName = '#deleteConfirm';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    $(dialogName).html('Та устгахдаа итгэлтэй байна уу?');
    $(dialogName).dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Сануулах',
        width: '350',
        height: 'auto',
        modal: true,
        buttons: [
            {text: 'Тийм', class: 'btn green-meadow btn-sm', click: function() {
                    var _this = $(elem);
                    var li = _this.parents('li.shadow');
                    $.ajax({
                        type: 'post',
                        url: 'mdwebservice/renderBpTabDeletePhoto',
                        data: {metaDataId: '<?php echo Mdgl::$glBookGroupId;?>', metaValueId: '<?php echo $this->row["id"];?>', attachId: li.attr('data-attach-id')},
                        dataType: "json",
                        success: function(data) {
                            if (data.status === 'success') {
                                new PNotify({
                                    title: 'Success',
                                    text: 'Амжилттай устгагдлаа.',
                                    type: 'success',
                                    sticker: false
                                });
                                li.remove();
                            } else {
                                new PNotify({
                                    title: 'Error',
                                    text: 'Алдаа гарлаа',
                                    type: 'error',
                                    sticker: false
                                });
                            }
                        },
                        error: function() {
                            alert("Error delete photo");
                        }
                    });
                    $(dialogName).dialog('close');
                }},
            {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function() {
                    $(dialogName).dialog('close');
                }}
        ]
    });
    $(dialogName).dialog('open');
}
function onChangeAttachFIle(input) {
    if ($(input).hasExtension(["png", "gif", "jpeg", "pjpeg", "jpg", "x-png", "bmp", "doc", "docx", "xls", "xlsx", "pdf", "ppt", "pptx", "zip", "rar"])) {
        $('form#glEntryForm').ajaxSubmit({
            type: 'post',
            url: 'mdwebservice/renderBpTabUploadFile',
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({
                    animate: true
                });
            },
            success: function(data) {
                PNotify.removeAll();
                if (data.status === 'success') {
                    new PNotify({
                        title: 'Success',
                        text: data.message,
                        type: 'success',
                        sticker: false
                    });
                    var li = '<li class="shadow" data-attach-id="' + data.attachId + '">';
                    if (data.attachFile != '') {
                        if (data.extension == 'png' ||
                                data.extension == 'gif' ||
                                data.extension == 'jpeg' ||
                                data.extension == 'pjpeg' ||
                                data.extension == 'jpg' ||
                                data.extension == 'x-png' ||
                                data.extension == 'bmp') {
                            li += '<a href="storage/uploads/metavalue/file/' + data.attachFile + '" class="fancybox-button main" data-rel="fancybox-button" title="">';
                            li += '<img src="storage/uploads/metavalue/file/' + data.attachFile + '"/>';
                            li += '</a>';
                        } else {
                            li += '<a href="javascript:;" title="' + data.fileName + '" class="fancybox-button">';
                            li += '<img src="assets/core/global/img/filetype/64/' + data.fileExtension + '.png"/>';
                            li += '</a>';
                        }
                    }

                    li += '<div class="btn-group float-right padding-5">';
                    li += '<button aria-expanded="false" class="btn default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">';
                    li += '</button>';
                    li += '<ul class="dropdown-menu float-left" role="menu">';
                    li += '<li>';
                    li += '<a href="javascript:;" onclick="updateGlTabFile(this)"><i class="fa fa-edit"></i> <?php echo Lang::line("edit_btn"); ?></a>';
                    li += '</li>';
                    li += '<li>';
                    li += '<a href="javascript:;" onclick="deleteGlTabFile(this)"><i class="fa fa-trash"></i> <?php echo Lang::line("delete_btn"); ?></a>';
                    li += '</li>';
                    li += '</ul>';
                    li += '</div>';
                    li += '<div class="title-photo"></div>';
                    li += '</li>';
                    $('.list-view-file').append(li);
                } else {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                }
                Core.unblockUI();
            }
        });
    }
    else {
        alert('Файл сонгоно уу.');
        $(input).val('');
    }
}
function updateGlTabFile(elem) {
    var dialogName = '#update-form-dialog';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    var _this = $(elem);
    var li = _this.parents('li.shadow');
    $.ajax({
        type: 'post',
        url: 'mdwebservice/renderBpTabUpdateFileForm',
        data: {metaDataId: '<?php echo Mdgl::$glBookGroupId;?>', metaValueId: '<?php echo $this->row["id"];?>', attachId: li.attr('data-attach-id')},
        dataType: "json",
        beforeSend: function(){
            $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
            Core.blockUI({
                animate: true
            });
        },
        success: function(data) {
            $(dialogName).html(data.Html);
            $(dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: '800',
                height: 'auto',
                modal: true,
                buttons: [
                    {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {
                        $('form#glEntryForm').ajaxSubmit({
                            type: 'post',
                            url: 'mdwebservice/renderBpTabUpdateFile',
                            dataType: 'json',
                            beforeSend: function () {
                                Core.blockUI({
                                    animate: true
                                });
                            },
                            success: function (data) {
                                PNotify.removeAll();
                                if (data.status === 'success') {
                                    new PNotify({
                                        title: 'Success',
                                        text: data.message,
                                        type: 'success',
                                        sticker: false
                                    });
                                    var _this = $('li[data-attach-id="'+data.attachId+'"]', 'body');
                                    if (data.attachFile != '') {
                                        if (data.extension == 'png' || 
                                            data.extension == 'gif' || 
                                            data.extension == 'jpeg' || 
                                            data.extension == 'pjpeg' || 
                                            data.extension == 'jpg' || 
                                            data.extension == 'x-png' || 
                                            data.extension == 'bmp') {
                                            _this.find('a.main').attr('href', 'storage/uploads/metavalue/file/'+data.attachFile);
                                            _this.find('a.main img').attr('src', 'storage/uploads/metavalue/file/'+data.attachFile);
                                        } else {
                                            _this.find('a.main').attr('href', 'storage/uploads/metavalue/file/'+data.attachFile);
                                            _this.find('a.main img').attr('src', 'assets/core/global/img/filetype/64/'+data.extension+'.png');
                                        }
                                    }

                                    if (data.attachName != '' || data.attachName != 'undefined') {
                                        _this.find('.title-photo').html(data.attachName);
                                        _this.find('a.main').attr('title', data.attachName);
                                    }
                                    $(dialogName).dialog('close');
                                } else {
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false
                                    });
                                }
                                Core.unblockUI();
                            }
                        });
                    }},
                    {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                        $(dialogName).dialog('close');
                    }}
                ]
            });
            $(dialogName).dialog('open');                
            Core.unblockUI();
        },
        error: function() {
            alert("Error update form");
        }
    });
}
function deleteBpTabFile(elem) {
    var dialogName = '#deleteConfirm';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    $(dialogName).html('Та устгахдаа итгэлтэй байна уу?');
    $(dialogName).dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Сануулах',
        width: '350',
        height: 'auto',
        modal: true,
        buttons: [
            {text: 'Тийм', class: 'btn green-meadow btn-sm', click: function () {
                var _this = $(elem);
                var li = _this.parents('li.shadow');
                $.ajax({
                    type: 'post',
                    url: 'mdwebservice/renderBpTabDeleteFile',
                    data: {metaDataId: '<?php echo Mdgl::$glBookGroupId;?>', metaValueId: '<?php echo $this->row["id"];?>', attachId: li.attr('data-attach-id')},
                    dataType: "json",
                    success: function(data) {
                        if (data.status === 'success') {
                            new PNotify({
                                title: 'Success',
                                text: data.message,
                                type: 'success',
                                sticker: false
                            });
                            li.remove();
                        } else {
                            new PNotify({
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
                $(dialogName).dialog('close');
            }},
            {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function () {
                    $(dialogName).dialog('close');
            }}
        ]
    });
    $(dialogName).dialog('open');
}
function addRowToTable(row, defaultValue, vidNumber) {
    var chooseBookBtn = '', debitCreditReadonly = '';
    var bookDate = $("#glbookDate").val();
    var debitValue = "", creditValue = "", debitBaseValue = "", creditBaseValue = "";
    var accountBookId = "", description = $("#gldescription").val();
    var bookNumberHide = "", bagts = "", rateAmount = '', accountBookType = '';
    var vatColumn = "<input type=\"text\" name=\"tax_meta[]\" readonly=\"true\" class=\"form-control sidebar_input\"></input>";
    var cashColumn = "<input type=\"text\" name=\"cash_meta[]\" readonly=\"true\" class=\"form-control sidebar_input\"></input>";
    var params = [];
    if (row != '') {
        if (jQuery.inArray(row.ACCOUNT_TYPE_ID, vatTypes) != -1) {
            var type = 1;
            if (row.ACCOUNT_TYPE_ID == 'VALUE_ADDED_TAX_PAYABLE') {
                type = 0;
            }
            vatColumn = "<select name=\"tax_meta[]\" class=\"form-control form-control-sm input-xxlarge select2\" data-glrequired=\"required\"><option value=\"\">- Сонгох -</option>";
            $.ajax({
                type: 'post',
                url: 'mdaccount/getTaxMetaValues/' + type,
                dataType: "json",
                async: false,
                success: function(data) {
                    $.each(data, function(key, value) {
                        vatColumn += '<option value=\"' + value['VAT_ATTR_SUB_CATEGORY_ID'] + '\">' + value['CODE'] + '-' + value['NAME'] + '</option>';
                    });
                }
            });
            vatColumn += "</select>";
        }
        $.ajax({
            type: 'post',
            url: 'mdgl/getAccountRateAndType',
            data: {accountId: row.ACCOUNT_ID, date: bookDate},
            dataType: "json",
            async: false,
            success: function(data) {
                accountBookType = data.accountType;
                if (defaultValue != undefined) {
                    bagts = defaultValue.subid;
                    description = defaultValue.description;
                    debitValue = defaultValue.debitamount;
                    creditValue = defaultValue.creditamount;
                    debitBaseValue = 'value=\"' + defaultValue.debitamountbase + '\"';
                    creditBaseValue = 'value=\"' + defaultValue.creditamountbase + '\"';
                    rateAmount = defaultValue.rate;
                    if (defaultValue.mapcode != undefined && defaultValue.mapcode != 'IGNORE') {
                        accountBookId = 'vid' + vidNumber;
                        vidNumber++;
                    }
                } else {
                    bagts = changeBagts(row);
                    rateAmount = data.result.rate;
                    debitValue = '', creditValue = '';
                    debitBaseValue = 'value=\"\"', creditBaseValue = 'value=\"\"';
                    if (row.USE_DETAIL_BOOK == '1') {
                        chooseBookBtn = '<div class="btn btn-sm purple-plum " onclick="bookListByAccountType(\'' + row.ACCOUNT_TYPE_ID + '\', \'' + row.ACCOUNT_ID + '\', this, \'multi\', \'gl\', \'' + glEntryWindowId + '\', \'#glEntryAccountDtlGrid\');" title="Баримт сонгох"><i class="fa fa-download"></i></div>';
                        debitCreditReadonly = 'readonly=\"readonly\"';
                        rateAmount = 1;
                    } else {
                        bookNumberHide = 'id=\"hideFromSideBar\"';
                        var values = copyEqualizeAmount(row);
                        debitValue = (values[0].cmDebitAmount == 0 && values[0].cmCreditAmount == 0) ? '' : values[0].cmDebitAmount;
                        creditValue = (values[0].cmDebitAmount == 0 && values[0].cmCreditAmount == 0) ? '' : values[0].cmCreditAmount;
                        debitBaseValue = 'value=\"' + (values[0].cmDebitAmount * data.result) + '\"';
                        creditBaseValue = 'value=\"' + (values[0].cmCreditAmount * data.result) + '\"';
                        bagts = values[0].bagts;
                    }
                }
            },
            error: function(data) {
                new PNotify({
                    title: 'Error',
                    text: 'Дансны ханш татахад алдаа гарлаа',
                    type: 'error',
                    sticker: false
                });
            }
        });
    } else {
        bagts = defaultValue.subid;
        description = defaultValue.description;
        debitValue = defaultValue.debitamount;
        creditValue = defaultValue.creditamount;
        debitBaseValue = 'value=\"' + defaultValue.debitamountbase + '\"';
        creditBaseValue = 'value=\"' + defaultValue.creditamountbase + '\"';
        rateAmount = defaultValue.rate;
        if (defaultValue.mapcode != undefined && defaultValue.mapcode != 'IGNORE') {
            accountBookId = 'vid' + vidNumber;
            vidNumber++;
        }
    }
    params.push({bagts: bagts, description: description, debit: debitValue, credit: creditValue, debitBase: debitBaseValue, creditBase: creditBaseValue,
        rate: rateAmount, accountBookId: accountBookId, cashColumn: cashColumn, vatColumn: vatColumn, readOnly: debitCreditReadonly,
        bookNumberHide: bookNumberHide, accountBookType: accountBookType, row: row, chooseBookBtn: chooseBookBtn});
    appendToTable(params);
}
function copyEqualizeAmount(row) {
    var creditCount = 0, debitCount = 0, sumCredit = 0, sumDebit = 0;
    var cmType = '';
    var cmDebitAmount = 0, cmCreditAmount = 0;
    var bagts = row.SUB_ID;
    var arr = [];
    $('#glEntryAccountDtlGrid tbody').find("tr").each(function() {
        if ($(this).find("input[name='bagts[]']").val() == row.SUB_ID && $(this).find("input[name='gl_accounId[]']").val() != row.ACCOUNT_ID) {
            if ($(this).find("input[name='debit[]']").val() != '' && $(this).find("input[name='credit[]']").val() != '') {
                if (getRowNumericValue($(this), "input[name='debit[]']", 'int') > 0 && getRowNumericValue($(this), "input[name='credit[]']", 'int') == 0) {
                    debitCount++;
                    if (!$(this).hasClass('removed-tr')) {
                        sumDebit += getRowNumericValue($(this), "input[name='debit[]']", 'int');
                    }
                }
                if (getRowNumericValue($(this), "input[name='credit[]']", 'int') > 0 && getRowNumericValue($(this), "input[name='debit[]']", 'int') == 0) {
                    creditCount++;
                    if (!$(this).hasClass('removed-tr')) {
                        sumCredit += getRowNumericValue($(this), "input[name='credit[]']", 'int');
                    }
                }
            }
        }
    });
    if (debitCount > creditCount) {
        if (creditCount == 0) {
            cmType = 'credit';
            cmCreditAmount = sumDebit;
            cmDebitAmount = 0;
        } else {
            bagts = changeBagts(row);
        }
    } else if (debitCount > creditCount) {
        if (debitCount == 0) {
            cmType = 'debit';
            cmDebitAmount = sumCredit;
            cmCreditAmount = 0;
        } else {
            bagts = changeBagts(row);
        }
    } else if (debitCount == creditCount) {
        if (debitCount != 0 && debitCount != 1) {
            bagts = changeBagts(row);
        }
    }
    arr.push({cmType: cmType, cmCreditAmount: cmCreditAmount, cmDebitAmount: cmDebitAmount, bagts: bagts});
    return arr;
}
function changeBagts(row) {
    var bagtsud = [];
    var maxBagts;
    $('#glEntryAccountDtlGrid tbody').find("tr").each(function() {
        if ($(this).find("input[name='bagts[]']").val() != undefined && $(this).find("input[name='gl_accounId[]']").val() != row.ACCOUNT_ID) {
            bagtsud.push($(this).find("input[name='bagts[]']").val());
        }
    });
    if (jQuery.isEmptyObject(bagtsud)) {
        maxBagts = 1;
    } else {
        maxBagts = Math.max.apply(Math, bagtsud);
    }
    var isEnterToLastBagts = checkBagtsProportion(maxBagts, row.ACCOUNT_ID);
    if (!isEnterToLastBagts) {
        maxBagts++;
    }
    return maxBagts;
}
function checkBagtsProportion(bagts, account) {
    var creditCount = 0;
    var debitCount = 0;
    $('#glEntryAccountDtlGrid tbody').find("tr").each(function() {
        if ($(this).find("input[name='bagts[]']").val() == bagts && $(this).find("input[name='gl_accounId[]']").val() != account) {
            if (getRowNumericValue($(this), "input[name='debit[]']", 'int') > 0 && getRowNumericValue($(this), "input[name='credit[]']", 'int') == 0) {
                debitCount++;
            }
            if (getRowNumericValue($(this), "input[name='credit[]']", 'int') > 0 && getRowNumericValue($(this), "input[name='debit[]']", 'int') == 0) {
                creditCount++;
            }
        }
    });
    if (debitCount > creditCount) {
        if (creditCount == 0) {
            return true;
        }
    }
    else if (creditCount > debitCount) {
        if (debitCount == 0) {
            return true;
        }
    }
    else if (debitCount == creditCount) {
        if (debitCount == 0 || debitCount == 1) {
            return true;
        }
    }
    return false;
}
function getCashMetaValues(bagts, accountId, row, isDebit) {
    if (row == '') {
        $('#glEntryAccountDtlGrid tbody').find("tr").each(function() {
            if ($(this).find("input[name='bagts[]']").val() == bagts && $(this).find("input[name='gl_accounId[]']").val() != accountId) {
                if (jQuery.inArray($(this).find("input[name='accountTypeId[]']").val(), cashHandBank) == -1) {
                    if (!isDebit) {
                        if (getRowNumericValue($(this), "input[name='debit[]']", 'int') > 0 && getRowNumericValue($(this), "input[name='credit[]']", 'int') == 0) {
                            cashColumn = '<select name="cash_meta[]" class="form-control input-xs input-xxlarge select2" data-glrequired="required"><option value="">- Сонгох -</option>';
                            $.ajax({
                                type: 'post',
                                url: 'mdaccount/getCashMetaValues/' + 1,
                                dataType: "json",
                                async: false,
                                success: function(data) {
                                    $.each(data, function(key, value) {
                                        cashColumn += '<option value="' + value['CASH_FLOW_SUB_CATEGORY_ID'] + '">' + value['CODE'] + '-' + value['NAME'] + '</option>';
                                    });
                                }
                            });
                            cashColumn += '</select>';
                            $(this).children("td:eq(5)").find("#cm").html(cashColumn);
                        }
                    } else {
                        if (getRowNumericValue($(this), "input[name='credit[]']", 'int') > 0 && getRowNumericValue($(this), "input[name='debit[]']", 'int') == 0) {
                            cashColumn = '<select name="cash_meta[]" class="form-control input-xs input-xxlarge select2" data-glrequired="required"><option value="">- Сонгох -</option>';
                            $.ajax({
                                type: 'post',
                                url: 'mdaccount/getCashMetaValues/' + 0,
                                dataType: "json",
                                async: false,
                                success: function(data) {
                                    $.each(data, function(key, value) {
                                        cashColumn += '<option value="' + value['CASH_FLOW_SUB_CATEGORY_ID'] + '">' + value['CODE'] + '-' + value['NAME'] + '</option>';
                                    });
                                }
                            });
                            cashColumn += '</select>';
                            $(this).children("td:eq(5)").find("#cm").html(cashColumn);
                        }
                    }
                }
            }
        });
    } else {
        var cashColumn = "<input type=\"text\" name=\"cash_meta[]\" readonly=\"true\" class=\"form-control sidebar_input\"></input>";
        $('#glEntryAccountDtlGrid tbody').find("tr").each(function() {
            if ($(this).find("input[name='bagts[]']").val() == bagts && $(this).find("input[name='gl_accounId[]']").val() != accountId) {
                if (jQuery.inArray($(this).find("input[name='accountTypeId[]']").val(), cashHandBank) != -1) {
                    if (getRowNumericValue($(this), "input[name='debit[]']", 'int') > 0 && getRowNumericValue($(this), "input[name='credit[]']", 'int') == 0) {
                        cashColumn = '<select name="cash_meta[]" class="form-control input-xs input-xxlarge select2" data-glrequired="required"><option value="">- Сонгох -</option>';
                        $.ajax({
                            type: 'post',
                            url: 'mdaccount/getCashMetaValues/' + 1,
                            dataType: "json",
                            async: false,
                            success: function(data) {
                                $.each(data, function(key, value) {
                                    cashColumn += '<option value="' + value['CASH_FLOW_SUB_CATEGORY_ID'] + '">' + value['CODE'] + '-' + value['NAME'] + '</option>';
                                });
                            }
                        });
                        cashColumn += '</select>';
                        row.children("td:eq(5)").find("#cm").html(cashColumn);
                    } else if (getRowNumericValue($(this), "input[name='credit[]']", 'int') > 0 && getRowNumericValue($(this), "input[name='debit[]']", 'int') == 0) {
                        row.children("td:eq(5)").find("#cm").html('');
                        cashColumn = '<select name="cash_meta[]" class="form-control input-xs input-xxlarge select2" data-glrequired="required"><option value="">- Сонгох -</option>';
                        $.ajax({
                            type: 'post',
                            url: 'mdaccount/getCashMetaValues/' + 0,
                            dataType: "json",
                            async: false,
                            success: function(data) {
                                $.each(data, function(key, value) {
                                    cashColumn += '<option value="' + value['CASH_FLOW_SUB_CATEGORY_ID'] + '">' + value['CODE'] + '-' + value['NAME'] + '</option>';
                                });
                            }
                        });
                        cashColumn += '</select>';
                        row.children("td:eq(5)").find("#cm").html(cashColumn);
                    }
                }
            }
        });
    }

}
function viewBookDetail(bookId, moduleId) {
    var dialogName = '#bookDialog';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    $.ajax({
        type: 'post',
        url: 'mdgl/popupBook',
        data: {bookId: bookId, moduleId: moduleId},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                animate: true
            });
        },
        success: function(data) {
            $(dialogName).empty().html(data.Html);
            $(dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 1400,
                minWidth: 1400,
                height: "auto",
                modal: true,
                close: function() {
                    $(dialogName).empty().dialog('destroy').remove();
                },
                buttons: [
                    {
                        text: data.close_btn,
                        class: 'btn btn-sm blue-hoki',
                        click: function() {
                            $(dialogName).empty().dialog('destroy').remove();
                        }
                    }
                ]
            });
            $(dialogName).dialog('open');
            Core.unblockUI();
        },
        error: function() {
            alert("Error");
        }
    }).done(function() {
        Core.initAjax();
    });
}
function prevNext(type, target) {
    var selectedTR = $(glEntryWindowId).find('#glEntryAccountDtlGrid tbody').find('tr.selected');
    var trLength = $(glEntryWindowId).find('#glEntryAccountDtlGrid tbody').find('tr').length - 1;
    if (type === 'prev') {
        selectedTR.prev("tr").trigger("click");
        if (selectedTR.index() - 1 <= 0)
            $(target).prop('disabled', true);
        if (selectedTR.index() + 1 <= trLength + 1)
            $(target).parent().find('button:last').prop('disabled', false);
    } else {
        selectedTR.next("tr").trigger("click");
        if (selectedTR.index() - 1 >= -1)
            $(target).parent().find('button:first').prop('disabled', false);
        if (selectedTR.index() + 1 >= trLength)
            $(target).prop('disabled', true);
    }
}
function defaultAdd() {
    var rowIndex = glEntryDtlTable.fnAddData([
        "<input type=\"text\" id=\"bagts\" name=\"bagts[]\" class=\"form-control\" required=\"required\" value=\"\">",
        "",
        "",
        "<input type=\"text\" id=\"debit\" name=\"debit[]\" class=\"form-control DEBIT numberInit\" value=\"\" data-m-dec=\"2\">",
        "<input type=\"text\" id=\"credit\" name=\"credit[]\" class=\"form-control CREDIT numberInit\" value=\"\" data-m-dec=\"2\">",
        "<input type=\"hidden\" name=\"accountName[]\" value=\"\"><div class=\"text-center actionDiv\"><a href=\"javascript:;\" class=\"btn red btn-xs\" onclick=\"defaultRemove(this);\" title=\"Устгах\"><i class=\"fa fa-trash\"></i></a>\n\</div>"
    ]);
    glEntryDtlTable.find('tbody').find('tr:eq(' + rowIndex + ')').trigger("click");
    if (rowIndex > 0) {
        $("#trPrevNextAction", glEntryWindowId).find("button:first").prop("disabled", false);
    }
    focusOnAccountCode();
    glEntryDtlTable.fnAdjustColumnSizing();
    addClassGlEntryGridInputs();
}
function defaultRemove(elem) {
    var target_row = $(elem).closest("tr");
    var aPos = glEntryDtlTable.fnGetPosition(target_row.get(0));
    glEntryDtlTable.fnDeleteRow(aPos);
    glEntryDtlTable.fnAdjustColumnSizing();
    gridGlEntryItemSum();
}
function selectableBookListByAccountTypeDataGrid(chooseType, elem, partCode) {
    var sidebarContent = $(".grid-row-content", glEntryWindowId).find("table tbody");
    if (partCode === 'gl') {
        var tr = $(glEntryWindowId).find('#glEntryAccountDtlGrid tbody').find('tr.selected');
        var index = tr.index();
        var accountId = tr.find("input[name='gl_accounId[]']").val();
        var accountTypeId = tr.find("input[name='accountTypeId[]']").val();
        var accountBookType = tr.find("input[name='accountBookType[]']").val();
        var accountName = tr.find("td:last").find("input[name='accountName[]']").val();
        var bagts = tr.find("input[name='bagts[]']").val();
        var accountCode = tr.find("td:eq(1)").text();
        var bookDtlBasketNum = $('#basket-book-list-grid').datagrid('getData').total;
        var ids = "", book = "", hideFromSideBar = "";
        var isDebit = false, beforeValue = false;
        var beforeAdverseValue = 0;
        var vatColumn = tr.find("td:last").find("#vat").find(".input_html").html();
        var cashColumn = tr.find("td:last").find("#cash").find(".input_html").html();
        var params = [];
        var chooseBookBtn = '<div class=\"btn btn-sm purple-plum\" onclick=\"bookListByAccountType(\'" + accountTypeId + "\', \'" + accountId + "\', this, \'multi\', \'gl\', \'" + glEntryWindowId + "\', \'#glEntryAccountDtlGrid\');\" title=\"Баримт сонгох\"><i class=\"fa fa-download\"></i></div></span></div>';
        if (bookDtlBasketNum > 0) {
            var rows = $('#basket-book-list-grid').datagrid('getRows');
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                var unrepeated = true;
                $('#glEntryAccountDtlGrid tbody').find("tr").each(function() {
                    if ($(this).find("input[name='accountBookIds[]']").val() == row.BOOK_ID && $(this).find("input[name='gl_accounId[]']").val() == accountId) {
                        unrepeated = false;
                    }
                });
                if (unrepeated) {
                    if (row.DT_TOTAL > 0) {
                        isDebit = true;
                    }
                    if (row.RATE == '1' || row.RATE == '0') {
                        hideFromSideBar = 'id =\"hideFromSideBar\"';
                    }
                    if (i > 0) {
                        var trRow = [];
                        var debitCreditReadonly = 'readonly=\"readonly\"';
                        trRow.push({ACCOUNT_ID: accountId, ACCOUNT_CODE: accountCode, ACCOUNT_NAME: accountName, USE_DETAIL_BOOK: 1});
                        params.push({bagts: bagts, description: row.DESCRIPTION, debit: '', credit: '', debitBase: '', creditBase: '',
                            rate: row.RATE, accountBookId: row.BOOK_ID, cashColumn: cashColumn, vatColumn: vatColumn, readOnly: debitCreditReadonly,
                            bookNumberHide: '', acountBookType: accountBookType, row: trRow[0], chooseBookBtn: chooseBookBtn});
                        var rowIndex = appendToTable(params);
                        tr = $('#glEntryAccountDtlGrid tbody').find("tr:eq(" + rowIndex + ")");
                    }
                    tr.find("td:last").find("input[name='glrate[]']").val(pureNumberFormat(row.RATE));
                    sidebarContent.find("input[name='glrate[]']").val(pureNumberFormat(row.RATE));
                    if (hideFromSideBar != "") {
                        tr.find("td:last-child").find("input[name='glrate[]']").parent().parent().attr('id', "hideFromSidebar");
                        tr.find("td:last-child").find("input[name='base_debit[]']").parent().parent().attr('id', "hideFromSidebar");
                        tr.find("td:last-child").find("input[name='base_credit[]']").parent().parent().attr('id', "hideFromSidebar");
                    }
                    tr.find("td:eq(2)").text(row.DESCRIPTION);
                    tr.find("td:last-child").find("input[name='glrow_description[]']").val(row.DESCRIPTION);
                    if (isDebit) {
                        tr.find("input[name='debit[]']").val(row.DT_TOTAL);
                        tr.find("input[name='credit[]']").val(0);
                        tr.find("td:last-child").find("input[name='base_debit[]']").val(pureNumberFormat(parseFloat(row.DT_TOTAL) * parseFloat(row.RATE)));
                        tr.find("td:last-child").find("input[name='base_credit[]']").val(pureNumberFormat(0));
                        sidebarContent.find("input[name='base_debit[]']").val(pureNumberFormat(parseFloat(row.DT_TOTAL) * parseFloat(row.RATE)));
                        sidebarContent.find("input[name='base_credit[]']").val(pureNumberFormat(0));
                        $('#glEntryAccountDtlGrid tbody').find("tr").each(function() {
                            if ($(this).find("input[name='bagts[]']").val() == bagts && $(this).find("input[name='gl_accounId[]']").val() != accountId) {
                                if (getRowNumericValue($(this), "input[name='debit[]']", 'int') > 0 && getRowNumericValue($(this), "input[name='credit[]']", 'int') == 0) {
                                    beforeValue = true;
                                }
                                if (getRowNumericValue($(this), "input[name='credit[]']", 'int') > 0 && getRowNumericValue($(this), "input[name='debit[]']", 'int') == 0) {
                                    beforeAdverseValue++;
                                }
                            }
                        });
                    } else {
                        tr.find("input[name='credit[]']").val(row.KT_TOTAL);
                        tr.find("input[name='debit[]']").val(0);
                        tr.find("td:last").find("input[name='base_debit[]']").val(pureNumberFormat(0));
                        tr.find("td:last").find("input[name='base_credit[]']").val(pureNumberFormat(parseFloat(row.KT_TOTAL) * parseFloat(row.RATE)));
                        sidebarContent.find("input[name='base_debit[]']").val(pureNumberFormat(0));
                        sidebarContent.find("input[name='base_credit[]']").val(pureNumberFormat(parseFloat(row.KT_TOTAL) * parseFloat(row.RATE)));
                        $('#glEntryAccountDtlGrid tbody').find("tr").each(function() {
                            if ($(this).find("input[name='bagts[]']").val() == bagts && $(this).find("input[name='gl_accounId[]']").val() != accountId) {
                                if (getRowNumericValue($(this), "input[name='credit[]']", 'int') > 0 && getRowNumericValue($(this), "input[name='debit[]']", 'int') == 0) {
                                    beforeValue = true;
                                }
                                if (getRowNumericValue($(this), "input[name='debit[]']", 'int') > 0 && getRowNumericValue($(this), "input[name='credit[]']", 'int') == 0) {
                                    beforeAdverseValue++;
                                }
                            }
                        });
                    }
                    if (jQuery.inArray(accountTypeId, cashHandBank) == -1) {
                        getCashMetaValues(bagts, accountId, tr);
                    } else {
                        getCashMetaValues(bagts, accountId, '', isDebit);
                    }
                    if (beforeValue && beforeAdverseValue > 1) {
                        var bagts = changeBagts(tr);
                        tr.find("input[name='bagts[]']").val(bagts);
                    }
                    ids = row.BOOK_ID;
                    tr.find("input[name='accountBookIds[]']").val(ids);
                    book = '<a onclick="viewBookDetail(\'' + row.BOOK_ID + '\', \'' + row.BOOK_MODULE_TYPE + '\');return false;">' + row.BOOK_NUMBER + '</a>';
                    tr.find("td:last-child").find("#books").find('#bookNumber').html(book);
                    tr.find("td:last-child").find("#books").find('#bookNumber').attr("onmouseover", "showBookNumberDtl(this);");
                    var target_row = $(tr).closest("tr");
                    target_row.find("td:last").find('span').find("#books").find('i')
                            .parent().removeClass('purple-plum')
                            .addClass('yellow-lemon');
                    Core.initInputType();
                    addClassGlEntryGridInputs();
                    glEntryDtlTable.fnAdjustColumnSizing();
                    gridGlEntryItemSum();
                }
                $('#glEntryAccountDtlGrid tbody tr:eq(' + index + ')').trigger('click');
            }
        } else {
            alert("Please choose item!");
        }
    }
}
function equalizeDebitCredit(sub_id) {
    var debitSum = 0;
    var creditSum = 0;
    var undefinedValueRowCount = 0;
    var undefinedValueRow;
    $.each($(".DEBIT"), function(i, val) {
        var tr = $(this).closest("tr");
        var bagts = tr.children("td:eq(0)").children("input[type=text]").val();
        var debit = new Number($(this).autoNumeric('get'));
        if (bagts == sub_id) {
            if (!$(this).hasClass('removed-tr')) {
                debitSum = debitSum + debit;
            }
        }
    });
    $.each($(".CREDIT"), function(i, val) {
        var tr = $(this).closest("tr");
        var bagts = tr.children("td:eq(0)").children("input[type=text]").val();
        var credit = new Number($(this).autoNumeric('get'));
        if (bagts == sub_id) {
            if (!$(this).hasClass('removed-tr')) {
                creditSum = creditSum + credit;
            }
        }
    });
    $("#glEntryAccountDtlGrid tbody tr").each(function() {
        if ($(this).find("input[name='bagts[]']").val() == sub_id) {
            var thisDebit = $(this).find("input[name='debit[]']").val();
            var thisCredit = $(this).find("input[name='credit[]']").val();
            var useBook = $(this).find("input[name='accountUseDetailBook[]']").val();
            if (thisDebit == 0 && thisCredit == 0) {
                if (useBook == 0) {
                    undefinedValueRow = this;
                    undefinedValueRowCount++;
                }
            }
        }
    });
    if (undefinedValueRowCount == 1) {
        if (debitSum !== creditSum) {
            var max = Math.max(creditSum, debitSum);
            var min = Math.min(creditSum, debitSum);
            var rest = max - min;
            var rate = getRowNumericValue($(undefinedValueRow), "input[name='glrate[]']", 'int');
            var accountTypeId = $(undefinedValueRow).find("input[name='accountTypeId[]']").val();
            var accountId = $(undefinedValueRow).find("input[name='gl_accounId[]']").val();
            if (debitSum > creditSum) {
                $(undefinedValueRow).find("input[name='credit[]']").val(pureNumberFormat(rest));
                $(undefinedValueRow).find("input[name='debit[]']").val(pureNumberFormat(0));
                $(undefinedValueRow).find("input[name='base_credit[]']").val(pureNumberFormat(rest * rate));
                $(undefinedValueRow).find("input[name='base_debit[]']").val(pureNumberFormat(0));
            }
            else {
                $(undefinedValueRow).find("input[name='credit[]']").val(pureNumberFormat(0));
                $(undefinedValueRow).find("input[name='debit[]']").val(pureNumberFormat(rest));
                $(undefinedValueRow).find("input[name='base_credit[]']").val(pureNumberFormat(0));
                $(undefinedValueRow).find("input[name='base_debit[]']").val(pureNumberFormat(rest * rate));
            }
            if ($(undefinedValueRow).find("td:last-child").find("#cm").find('select').length == 0) {
                if (jQuery.inArray(accountTypeId, cashHandBank) == -1) {
                    getCashMetaValues(sub_id, accountId, $(undefinedValueRow));
                }
            }
        }
    }
}
function adjustCashMeta(accountTypeId, bagts, debit, credit) {
    var cashColumn = '<input type="text" name="cash_meta[]" readonly="true" class="form-control sidebar_input"></input>';
    if (jQuery.inArray(accountTypeId, cashHandBank) != -1) {
        $("#glEntryAccountDtlGrid tbody tr").each(function() {
            if (typeof $(this).find("input[name='debit[]']").val() !== 'undefined') {
                var this_bagts = $(this).find("input[name='bagts[]']").val();
                var this_debit = $(this).find("input[name='debit[]']").autoNumeric('get');
                var this_credit = $(this).find("input[name='credit[]']").autoNumeric('get');
                if (debit > 0) {
                    if (bagts == this_bagts && this_credit > 0) {
                        $(this).children("td:eq(5)").find("#cm").html(cashColumn);
                    }
                } else if (credit > 0) {
                    if (bagts == this_bagts && this_debit > 0) {
                        $(this).children("td:eq(5)").find("#cm").html(cashColumn);
                    }
                }
            }
        });
    }
}
function updateRowToTable(row, index, oldId) {
    console.log(oldId);
    if (oldId !== '') {
        var trOldRow = $('#glEntryAccountDtlGrid tbody tr:eq(' + index + ')');
        removeItemDtl(trOldRow);
        addRowToTable(row);
        glEntryDtlTable.fnAdjustColumnSizing();
        Core.initInputType();
        addClassGlEntryGridInputs();
        gridGlEntryItemSum();
    } else {
        var chooseBookBtn = '';
        var debitCreditReadonly = '';
        var debitValue = "";
        var creditValue = "";
        var debitBaseValue = "";
        var creditBaseValue = "";
        var bookDate = $("#glbookDate").val();
        var description = $("#gldescription").val();
        var bagts = row.SUB_ID;
        var rateColumn = "";
        var bookNumberHide = "";
        var vatColumn = "<input type=\"text\" name=\"tax_meta[]\" readonly=\"true\" class=\"form-control sidebar_input\"></input>";
        var cashColumn = "<input type=\"text\" name=\"cash_meta[]\" readonly=\"true\" class=\"form-control sidebar_input\"></input>";
        var rate = "<input type=\"text\" id=\"glrate" + row.ACCOUNT_ID + "\" name=\"glrate[]\" readonly=\"true\" class=\"form-control numberInit sidebar_input\" data-m-dec=\"2\" required=\"required\" value=\"\">";
        if (jQuery.inArray(row.ACCOUNT_TYPE_ID, vatTypes) != -1) {
            var type = 1;
            if (row.ACCOUNT_TYPE_ID == 'VALUE_ADDED_TAX_PAYABLE') {
                type = 0;
            }
            vatColumn = "<select name=\"tax_meta[]\" class=\"form-control form-control-sm input-xxlarge select2\"  data-glrequired=\"required\"><option value=\"\">- Сонгох -</option>";
            $.ajax({
                type: 'post',
                url: 'mdaccount/getTaxMetaValues/' + type,
                dataType: "json",
                async: false,
                success: function(data) {
                    $.each(data, function(key, value) {
                        vatColumn += '<option value=\"' + value['CODE'] + '\">' + value['NAME'] + '</option>';
                    });
                }
            });
            vatColumn += "</select>";
        }
        $.ajax({
            type: 'post',
            url: 'mdgl/getAccountRateAndType',
            data: {accountId: row.ACCOUNT_ID, date: bookDate},
            dataType: "json",
            async: false,
            success: function(data) {
                if (row.USE_DETAIL_BOOK == '1') {
                    chooseBookBtn = '<div class="btn btn-sm purple-plum" onclick="bookListByAccountType(\'' + row.ACCOUNT_TYPE_ID + '\', \'' + row.ACCOUNT_ID + '\', this, \'multi\', \'gl\', \'' + glEntryWindowId + '\', \'#glEntryAccountDtlGrid\');" title="Баримт сонгох"><i class="fa fa-download"></i></div>';
                    debitCreditReadonly = 'readonly=\"readonly\"';
                } else {
                    bookNumberHide = 'id=\"hideFromSideBar\"';
                    rate = "<input type=\"text\" id=\"glrate" + row.ACCOUNT_ID + "\" name=\"glrate[]\" readonly=\"true\" class=\"form-control numberInit sidebar_input\" data-m-dec=\"2\" required=\"required\" value=\"" + data.result.rate + "\">";
                    var values = copyEqualizeAmount(row);
                    debitValue = (values[0].cmDebitAmount == 0 && values[0].cmCreditAmount == 0) ? '' : values[0].cmDebitAmount;
                    creditValue = (values[0].cmDebitAmount == 0 && values[0].cmCreditAmount == 0) ? '' : values[0].cmCreditAmount;
                    debitBaseValue = 'value=\"' + (values[0].cmDebitAmount * data.result) + '\"';
                    creditBaseValue = 'value=\"' + (values[0].cmCreditAmount * data.result) + '\"';
                }
                if (data.result.rate != 1) {
                    rateColumn = "<span class=\"hide fa_asset_sidebar_content\"><i class=\"input_label_txt\">Кредит валют</i><i class=\"input_html\"><input type=\"text\" name=\"base_credit[]\" " + debitCreditReadonly + " class=\"form-control sum numberInit sidebar_input\" data-m-dec=\"2\" " + creditBaseValue + "></i></span>\n\
                    <span class=\"hide fa_asset_sidebar_content\"><i class=\"input_label_txt\">Дебит валют</i><i class=\"input_html\"><input type=\"text\" name=\"base_debit[]\" " + debitCreditReadonly + " class=\"form-control sum numberInit sidebar_input\" data-m-dec=\"2\" " + debitBaseValue + "></i></span>\n\
                    <span class=\"hide fa_asset_sidebar_content\"><i class=\"input_label_txt\">Ханш</i><i class=\"input_html\">" + rate + "</i></span>";
                } else {
                    rateColumn = "<span class=\"hide fa_asset_sidebar_content\" id =\"hideFromSideBar\"><i class=\"input_label_txt\">Кредит валют</i><i class=\"input_html\"><input type=\"text\" name=\"base_credit[]\" " + debitCreditReadonly + " class=\"form-control sum numberInit sidebar_input\" data-m-dec=\"2\" " + creditBaseValue + "></i></span>\n\
                    <span class=\"hide fa_asset_sidebar_content\" id =\"hideFromSideBar\"><i class=\"input_label_txt\">Дебит валют</i><i class=\"input_html\"><input type=\"text\" name=\"base_debit[]\" " + debitCreditReadonly + " class=\"form-control sum numberInit sidebar_input\" data-m-dec=\"2\" " + debitBaseValue + "></i></span>\n\
                    <span class=\"hide fa_asset_sidebar_content\" id =\"hideFromSideBar\"><i class=\"input_label_txt\">Ханш</i><i class=\"input_html\">" + rate + "</i></span>";
                }
                glEntryDtlTable.fnUpdate([
                    "<input type=\"text\" id=\"bagts" + row.ACCOUNT_ID + "\" name=\"bagts[]\" class=\"form-control\" required=\"required\" value=\"" + bagts + "\">",
                    row.ACCOUNT_CODE,
                    description,
                    "<input type=\"text\" id=\"debit" + row.ACCOUNT_ID + "\" name=\"debit[]\" " + debitCreditReadonly + " class=\"form-control DEBIT numberInit\" data-m-dec=\"2\" value=\"" + debitValue + "\">",
                    "<input type=\"text\" id=\"credit" + row.ACCOUNT_ID + "\" name=\"credit[]\" " + debitCreditReadonly + " class=\"form-control CREDIT numberInit\" data-m-dec=\"2\" value=\"" + creditValue + "\">",
                    "<div class=\"text-center actionDiv\"><a href=\"javascript:;\" class=\"btn red btn-xs\" onclick=\"removeItemDtl(this);\" title=\"Устгах\"><i class=\"fa fa-trash\"></i></a>\n\</div>\n\
                <input type=\"hidden\" id=\"gl_accounId" + row.ACCOUNT_ID + "\" name=\"gl_accounId[]\" value=\"" + row.ACCOUNT_ID + "\">\n\
                <span class=\"hide fa_asset_sidebar_content\" id=\"vat\"><i class=\"input_label_txt\">НӨАТ-н үзүүлэлт</i><i class=\"input_html\" id=\"vt\">" + vatColumn + "</i></span>\n\
                <span class=\"hide fa_asset_sidebar_content\" id=\"cash\"><i class=\"input_label_txt\">Мөнгөн үзүүлэлт</i><i class=\"input_html\" id=\"cm\">" + cashColumn + "</i></span>\n\
                <span class=\"hide fa_asset_sidebar_content\" id=\"desc\"><i class=\"input_label_txt\">Гүйлгээний утга</i><i class=\"input_html\"><input type=\"text\" name=\"glrow_description[]\" class=\"form-control sidebar_input\" value=\"" + description + "\"></i></span>" + rateColumn + "\n\
                <span class=\"hide fa_asset_sidebar_content\" " + bookNumberHide + "><i class =\"input_label_txt\">Баримт</i><i class=\"input_html\" id=\"books\"><div class=\"input-group\"><span id=\"bookNumber\" class=\"form-control\" style=\"border:none !important; line-height:200% !important; overflow:hidden; height:30px; text-overflow:hidden;\"></span>\n\
                <span class=\"input-group-btn\">" + chooseBookBtn + "</span></div></i></span>\n\
                <input type=\"hidden\" name=\"accountName[]\" value=\"" + row.ACCOUNT_NAME + "\">\n\
                <input type=\"hidden\" id=\"accountTypeId" + row.ACCOUNT_ID + "\" name=\"accountTypeId[]\" value=\"" + row.ACCOUNT_TYPE_ID + "\">\n\
                <input type=\"hidden\" id=\"accountUseDetailBook" + row.ACCOUNT_ID + "\" name=\"accountUseDetailBook[]\" value=\"" + row.USE_DETAIL_BOOK + "\">\n\
                <input type=\"hidden\" name=\"accountBookIds[]\" value=\"\">\n\
                <input type=\"hidden\" name=\"dtlId[]\" value=\"\">\n\
                <input type=\"hidden\" name=\"oldBookIds[]\" value=\"\">\n\
                <input type = \"hidden\" name=\"oldVatIds[]\" value=\"\">\n\
                <input type=\"hidden\" name=\"oldCashIds[]\" value=\"\">\n\
                <input type=\"hidden\" name=\"customerId[]\" value=\"\">\n\
                <input type=\"hidden\" name=\"referenceNumber[]\" value=\"\">\n\
                <input type=\"hidden\" name=\"accountBookType[]\" value=\"" + data.accountType + "\">\n\
                <input type=\"hidden\" name=\"rowState[]\" value=\"ADDED\">\n\
                "
                ], $('#glEntryAccountDtlGrid tbody tr:eq(' + index + ')')[0]);
                $('#glEntryAccountDtlGrid tbody tr:eq(' + index + ')').find("td:eq(1)").addClass('bold');
            },
            error: function(data) {
                new PNotify({
                    title: 'Error',
                    text: 'Дансны ханш татахад алдаа гарлаа',
                    type: 'error',
                    sticker: false
                });
            }
        });
        $('#glEntryAccountDtlGrid tbody tr:eq(' + index + ')').find("td:eq(5)").find(".actionDiv").find('i:first')
                .parent().removeClass('btn-warning')
                .addClass('btn-success');
        var updatedRow = $('#glEntryAccountDtlGrid tbody tr:eq(' + index + ')');
        if (jQuery.inArray(row.ACCOUNT_TYPE_ID, cashHandBank) == -1) {
            getCashMetaValues(bagts, row.ACCOUNT_ID, updatedRow);
        }
        $('#glEntryAccountDtlGrid tbody tr:eq(' + index + ')').trigger('click');
        focusOnAccountCode();
    }
}
function validateGlTable(glWindowId) {
    var table = $('#glEntryAccountDtlGrid_wrapper', glWindowId).find('#glEntryAccountDtlGrid tbody');
    var tr = $('#glEntryAccountDtlGrid_wrapper', glWindowId).find('.DTFC_Cloned tbody');
    table.find("tr").removeClass("validation-error-tr");
    fixedCols.find("tr").removeClass("validation-error-tr");
    var mainErrorStatus = false;
    var array = [];
    var errorList = "";
    if ($(glWindowId).find("gldescription").val() == '') {
        mainErrorStatus = true;
        errorList = "<dt>Журнал бичилтийн утга хоосон байна</dt>";
    }
    $(glWindowId).find('#glEntryAccountDtlGrid > tbody > tr').each(function(i) {
        var errorStatus = false;
        var childErrorList = "";
        var _this = $(this);
        if (_this.find("input[name='gl_accounId[]']").val() == '') {
            errorStatus = true;
            childErrorList += "<dd>Данс сонгоогүй байна</dd>";
        }
        if ($(this).find("input[name='debit[]']").autoNumeric('get') == 0 && $(this).find("input[name='credit[]']").autoNumeric('get') == 0) {
            errorStatus = true;
            childErrorList += "<dd>Гүйлгээний ДТ/КТ дүн хоосон байна</dd>";
        }
        if ($(this).find("input[name='bagts[]']").val() == '') {
            errorStatus = true;
            childErrorList += "<dd>Багцын дугаар хоосон байна</dd>";
        }
        if ($(this).find("input[name='accountUseDetailBook[]']").val() == '1') {
            if ($(this).find("input[name='accountBookIds[]']").val() == '') {
                errorStatus = true;
                childErrorList += "<dd>Баримт сонгоогүй байна</dd>";
            }
        }
//        if (_this.find("td:last-child").find("select[name='cash_meta[]']").hasAttr('data-glrequired')) {
//            if (_this.find("td:last-child").find("select[name='cash_meta[]']").val() == '') {
//                errorStatus = true;
//                childErrorList += "<dd>Мөнгөн үзүүлэлт сонгоогүй байна</dd>";
//            }
//        }
//        if (_this.find("td:last-child").find("select[name='tax_meta[]']").hasAttr('data-glrequired')) {
//            if (_this.find("td:last-child").find("select[name='tax_meta[]']").val() == '') {
//                errorStatus = true;
//                childErrorList += "<dd>НӨАТ үзүүлэлт сонгоогүй байна</dd>";
//            }
//        }
        if ($(this).find("input[name='glrow_description[]']").val() == '') {
            errorStatus = true;
            childErrorList += "<dd>Гүйлгээний утга хоосон байна</dd>";
        }
        if (errorStatus) {
            mainErrorStatus = true;
            errorList += "<dt>" + (i + 1) + "-р мөрөн дээр дараах алдаа байна</dt>";
            errorList += childErrorList;
            table.find("tr:eq(" + i + ")").addClass("validation-error-tr");
            fixedCols.find("tr:eq(" + i + ")").addClass("validation-error-tr");
        }
    });
    if (mainErrorStatus) {
        array.push({
            status: 'error',
            text: errorList
        });
    } else {
        array.push({
            status: 'success',
            text: 'success'
        });
    }
    return array;
}
function extractTemplate(temp) {
    var tempValues = JSON.parse(temp);
    var vidNumber = 1;
    var glDescription = "";
    jQuery.each(tempValues, function(i, val) {
        glDescription = val.description;
        if (val.accountid != undefined) {
            $.ajax({
                type: 'post',
                async: false,
                url: 'mdgl/getAccountRowById/' + val.accountid,
                dataType: 'json',
                success: function(row) {
                    if (row != null) {
                        addRowToTable(row, val, vidNumber);
                    }
                }
            });
            vidNumber++;
        } else {
            addRowToTable('', val, vidNumber);
        }
        glEntryDtlTable.fnAdjustColumnSizing();
        Core.initInputType();
        addClassGlEntryGridInputs();
        gridGlEntryItemSum();
    });
    $("#gldescription").val(glDescription);
}
function appendToTable(values) {
    values = values[0];
    var row = values.row;
    var rateColumnHide = '';
    if (values.rate == 1 || values.rate == 0) {
        rateColumnHide = 'id =\"hideFromSideBar\"';
    }
    var rowIndex = glEntryDtlTable.fnAddData([
        "<input type=\"text\" id=\"bagts\" name=\"bagts[]\" class=\"form-control\" data-m-dec=\"2\" required=\"required\" value=\"" + values.bagts + "\">",
        (row !== '' ? row.ACCOUNT_CODE : ''),
        values.description,
        "<input type=\"text\" id=\"debit\" name=\"debit[]\" " + values.readOnly + " class=\"form-control DEBIT numberInit\" value=\"" + values.debit + "\" data-m-dec=\"2\">",
        "<input type=\"text\" id=\"credit\" name=\"credit[]\" " + values.readOnly + " class=\"form-control CREDIT numberInit\" value=\"" + values.credit + "\" data-m-dec=\"2\">",
        "<div class=\"text-center actionDiv\"><a href=\"javascript:;\" class=\"btn red btn-xs\" onclick=\"removeItemDtl(this);\" title=\"Устгах\"><i class=\"fa fa-trash\"></i></a>\n\</div>\n\
            <input type=\"hidden\" id=\"gl_accounId\" name=\"gl_accounId[]\" value=\"" + (row !== '' ? row.ACCOUNT_ID : '') + "\">\n\
            <input type=\"hidden\" id=\"accountTypeId\" name=\"accountTypeId[]\" value=\"" + (row !== '' ? row.ACCOUNT_TYPE_ID : '') + "\">\n\
            <input type=\"hidden\" name=\"accountName[]\" value=\"" + (row !== '' ? row.ACCOUNT_NAME : '') + "\">\n\
            <input type=\"hidden\" id=\"accountUseDetailBook\" name=\"accountUseDetailBook[]\" value=\"" + (row !== '' ? row.USE_DETAIL_BOOK : '') + "\">\n\
            <input type=\"hidden\" name=\"accountBookIds[]\" value=\"" + values.accountBookId + "\">\n\
            <input type=\"hidden\" name=\"accountBookType[]\" value=\"" + values.accountBookType + "\">\n\
            <input type=\"hidden\" name=\"rowState[]\" value=\"ADDED\">\n\
            <input type=\"hidden\" name=\"dtlId[]\" value=\"\">\n\
            <input type=\"hidden\" name=\"oldBookIds[]\" value=\"\">\n\
            <input type=\"hidden\" name=\"oldVatIds[]\" value=\"\">\n\
            <input type=\"hidden\" name=\"oldCashIds[]\" value=\"\">\n\
            <input type=\"hidden\" name=\"customerId[]\" value=\"\">\n\
            <input type=\"hidden\" name=\"referenceNumber[]\" value=\"\">\n\
            <span class=\"hide fa_asset_sidebar_content\" id=\"desc\"><i class=\"input_label_txt\">Гүйлгээний утга</i><i class=\"input_html\"><input type=\"text\" name=\"glrow_description[]\" class=\"form-control sidebar_input\" value=\"" + values.description + "\"></i></span>\n\
            <span class=\"hide fa_asset_sidebar_content\" " + rateColumnHide + "><i class=\"input_label_txt\">Кредит валют</i><i class=\"input_html\"><input type=\"text\" name=\"base_credit[]\" " + values.readOnly + " class=\"form-control sum numberInit sidebar_input\" data-m-dec=\"2\" " + values.creditBase + "></i></span>\n\
            <span class=\"hide fa_asset_sidebar_content\" " + rateColumnHide + "><i class=\"input_label_txt\">Дебит валют</i><i class=\"input_html\"><input type=\"text\" name=\"base_debit[]\" " + values.readOnly + " class=\"form-control sum numberInit sidebar_input\" data-m-dec=\"2\" " + values.debitBase + "></i></span>\n\
            <span class=\"hide fa_asset_sidebar_content\" " + rateColumnHide + "><i class=\"input_label_txt\">Ханш</i><i class=\"input_html\"><input type=\"text\" id=\"glrate\" name=\"glrate[]\" readonly=\"true\" class=\"form-control numberInit sidebar_input\" data-m-dec=\"2\" required=\"required\" value=\"" + values.rate + "\"></i></span>\n\
            <span class=\"hide fa_asset_sidebar_content\" " + values.bookNumberHide + "><i class =\"input_label_txt\">Баримт</i><i class=\"input_html\" id=\"books\"><div class=\"input-group\"><span id=\"bookNumber\" class=\"form-control\" style=\"border:none !important; line-height:200% !important; overflow:hidden; height:30px; text-overflow:hidden;\"></span>\n\
            <span class=\"input-group-btn\">" + values.chooseBookBtn + "</span></div></i></span>\n\
        "
    ]);
    var newRow = $('#glEntryAccountDtlGrid tbody tr:eq(' + rowIndex + ')');
    if (jQuery.inArray(row.ACCOUNT_TYPE_ID, cashHandBank) == -1) {
        getCashMetaValues(values.bagts, row.ACCOUNT_ID, newRow);
    }
    glEntryDtlTable.find('tbody').find('tr:eq(' + rowIndex + ')').trigger("click");
    if (rowIndex > 0) {
        $("#trPrevNextAction", glEntryWindowId).find("button:first").prop("disabled", false);
    }
    return rowIndex;
}
function focusOnAccountCode() {
    var rightsidebar = $(".right-sidebar", glEntryWindowId);
    var rightsidebarstatus = rightsidebar.attr("data-status");
    if (rightsidebarstatus === "closed") {
        $(".stoggler", glEntryWindowId).trigger('click');
    }
    var tab = rightsidebar.find("div.right-sidebar-content").find(".tabbable-line");
    if (!tab.find(".tab-content").find("#sidebar-book-dtl").hasClass("active")) {
        tab.find(".tab-content").find("#sidebar-book-dtl").addClass("active");
        tab.find(".tab-content").find("#sidebar-book-hdr").removeClass("active");
    }
    var table = tab.find(".tab-content").find("#sidebar-book-dtl").find("#generalInfo").find('table');
    var input = table.find("tbody").find("tr:eq(0)").find("td:eq(1)").find("input");
    input.focus();
}
function selectBook() {
    var rightsidebar = $(".right-sidebar", glEntryWindowId);
    var rightsidebarstatus = rightsidebar.attr("data-status");
    if (rightsidebarstatus === "closed") {
        $(".stoggler", glEntryWindowId).trigger('click');
    }
    var tab = rightsidebar.find("div.right-sidebar-content").find(".tabbable-line");
    if (!tab.find(".tab-content").find("#sidebar-book-dtl").hasClass("active")) {
        tab.find(".tab-content").find("#sidebar-book-dtl").addClass("active");
        tab.find(".tab-content").find("#sidebar-book-hdr").removeClass("active");
    }
    var table = tab.find(".tab-content").find("#sidebar-book-dtl").find("#generalInfo").find('table');
    var button = table.find("tbody").find("tr:eq(3)").find("td:eq(1)").find("button");
    if (button != undefined) {
        table.find("tbody").find("tr:eq(3)").find("td:eq(1)").find("div.input-group").find("span:last").find("div").trigger("click");
    }
}
function addClassGlEntryGridInputs() {
    $('#glEntryAccountDtlGrid > tbody  > tr').each(function() {
        $(this).find("td:eq(1)").addClass('bold');
    });
    $(glEntryWindowId)
            .find(".dataTables_scroll")
            .find(".dataTables_scrollBody")
            .find("table.dataTable tbody")
            .find("input[type=text]").parent("td").addClass("stretchInput");
    $(glEntryWindowId)
            .find(".dataTables_scroll")
            .find(".dataTables_scrollBody")
            .find("table.dataTable tbody")
            .find("div.actionDiv").parent("td").addClass("stretchInput");
}
function selectableAccountDataGrid(metaCode, chooseType, elem, params) {
    if (elem === 'message') {
        var rows = $('#commonSelectableBasketDataGrid').datagrid('getRows');
        var row = rows[0];
        $("input#gl_description_code", glEntryWindowId).val(row.MESSAGE_CODE);
        $("#gldescription", glEntryWindowId).val(row.MESSAGE_DESC_L);
        $("#glEntryAccountDtlGrid tbody tr").each(function() {
            if ($(this).find('td:eq(2)').text() == "") {
                $(this).find('td:eq(2)').text(row.MESSAGE_DESC_L);
                $(this).find("td:last-child").find("span#desc").find(".input_html").html('<input type="text" name="glrow_description[]" class="form-control sidebar_input" value="' + row.MESSAGE_DESC_L + '">');
            }
        });
    }
    if (elem === 'accountDtl') {
        var itemDtlBasketNum = $('#commonSelectableBasketDataGrid').datagrid('getData').total;
        if (itemDtlBasketNum > 0) {
            var rows = $('#commonSelectableBasketDataGrid').datagrid('getRows');
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                addRowToTable(row);
            }
            glEntryDtlTable.fnAdjustColumnSizing();
            Core.initInputType();
            addClassGlEntryGridInputs();
            //gridGlAccountNumbering();
            gridGlEntryItemSum();
        } else {
            alert("Please choose account!");
        }
    }
}
function selectableUpdateAccountDataGrid(metaCode, chooseType, elem, params) {
    var index = $("#searchClickedTR").val();
    var trRow = $('#glEntryAccountDtlGrid tbody tr:eq(' + index + ')');
    var oldId = trRow.find('td:last').find("input[name='dtlId[]']").val();
    if (elem === 'accountDtl') {
        var itemDtlBasketNum = $('#commonSelectableBasketDataGrid').datagrid('getData').total;
        if (itemDtlBasketNum > 0) {
            var rows = $('#commonSelectableBasketDataGrid').datagrid('getRows');
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                updateRowToTable(row, index, oldId);
            }
            glEntryDtlTable.fnAdjustColumnSizing();
            Core.initInputType();
            addClassGlEntryGridInputs();
            //gridGlAccountNumbering();
            gridGlEntryItemSum();
        } else {
            alert("Please choose account!");
        }
    }
}
function gridGlEntryItemFormula(elem, sidebar) {
    var side = typeof sidebar;
    var sidebarContent = $(".grid-row-content", glEntryWindowId).find("table tbody");
    if (side === 'undefined') {
        var _thisName = elem.attr("name").replace(/[[]]/g, '');
        var _thisRow = elem.parents("tr");
    } else {
        var _thisName = sidebar.replace(/[[]]/g, '');
        var _thisRow = elem;
    }
    var index = _thisRow.index();
    var bagts = $(_thisRow.find("input[name='bagts[]']")).val();
    var accountId = $(_thisRow.find("input[name='gl_accounId[]']")).val();
    var accountTypeId = $(_thisRow.find("input[name='accountTypeId[]']")).val();
    var glrate = getRowNumericValue(_thisRow, "input[name='glrate[]']", 'int');
    var debit = getRowNumericValue(_thisRow, "input[name='debit[]']", 'int');
    var credit = getRowNumericValue(_thisRow, "input[name='credit[]']", 'int');
    var base_debit = getRowNumericValue(_thisRow, "input[name='base_debit[]']", 'int');
    var base_credit = getRowNumericValue(_thisRow, "input[name='base_credit[]']", 'int');
    var beforeValue = false;
    var beforeAdverseValue = 0;
    if (_thisName === 'debit') {
        $(_thisRow.find("input[name='credit[]']")).val(pureNumberFormat(0));
        _thisRow.find("input[name='base_debit[]']").val(pureNumberFormat(debit * glrate));
        _thisRow.find("input[name='base_credit[]']").val(pureNumberFormat(0));
        sidebarContent.find("input[name='base_debit[]']").val(pureNumberFormat(debit * glrate));
        sidebarContent.find("input[name='base_credit[]']").val(pureNumberFormat(0));
        if (_thisRow.find("td:last-child").find("#cm").find('select').length == 0) {
            if (jQuery.inArray(accountTypeId, cashHandBank) == -1) {
                getCashMetaValues(bagts, accountId, _thisRow);
            }
        }
    }
    if (_thisName === 'credit') {
        $(_thisRow.find("input[name='debit[]']")).val(pureNumberFormat(0));
        _thisRow.find("input[name='base_credit[]']").val(pureNumberFormat(credit * glrate));
        _thisRow.find("input[name='base_debit[]']").val(pureNumberFormat(0));
        sidebarContent.find("input[name='base_credit[]']").val(pureNumberFormat(credit * glrate));
        sidebarContent.find("input[name='base_debit[]']").val(pureNumberFormat(0));
        if (_thisRow.find("td:last-child").find("#cm").find('select').length == 0) {
            if (jQuery.inArray(accountTypeId, cashHandBank) == -1) {
                getCashMetaValues(bagts, accountId, _thisRow);
            }
        }
    }
    if (_thisName === 'base_debit') {
        base_debit = side === 'undefined' ? base_debit : getRowNumericValue(sidebarContent, "input[name='base_debit[]']", 'int');
        $(_thisRow.find("input[name='debit[]']")).autoNumeric('set', base_debit / glrate);
        $(_thisRow.find("input[name='credit[]']")).autoNumeric('set', 0);
        _thisRow.find("input[name='base_credit[]']").val(pureNumberFormat(0));
        sidebarContent.find("input[name='base_credit[]']").val(pureNumberFormat(0));
        if (_thisRow.find("td:last-child").find("#cm").find('select').length == 0) {
            if (jQuery.inArray(accountTypeId, cashHandBank) == -1) {
                getCashMetaValues(bagts, accountId, _thisRow);
            }
        }
    }
    if (_thisName === 'base_credit') {
        base_credit = side === 'undefined' ? base_credit : getRowNumericValue(sidebarContent, "input[name='base_credit[]']", 'int');
        $(_thisRow.find("input[name='credit[]']")).autoNumeric('set', base_credit / glrate);
        $(_thisRow.find("input[name='debit[]']")).autoNumeric('set', 0);
        _thisRow.find("input[name='base_debit[]']").val(pureNumberFormat(0));
        sidebarContent.find("input[name='base_debit[]']").val(pureNumberFormat(0));
        if (_thisRow.find("td:last-child").find("#cm").find('select').length == 0) {
            if (jQuery.inArray(accountTypeId, cashHandBank) == -1) {
                getCashMetaValues(bagts, accountId, _thisRow);
            }
        }
    }
//    if (_thisName === 'glrow_description') {
//        var row_desc = side === 'undefined' ? '' : sidebarContent.find("input[name='glrow_description[]']").val();
//        $(_thisRow.find("td:last-child").find("input[name='glrow_description[]']")).val(row_desc);
//        $(_thisRow).find("td:eq(2)").text(row_desc);
//    }
    if (_thisName === 'bagts' || _thisName === 'sub') {
        var sub = side === 'undefined' ? bagts : sidebarContent.find("input[name='sub[]']").val();
        var checkedBagts = checkBagtsProportion(sub, accountId);
        if (checkedBagts) {
            if (side === 'undefined') {
                sidebarContent.find("input[name='sub']").val(sub);
            } else {
                $(_thisRow.find("input[name='bagts[]']")).val(sub);
            }
        } else {
            new PNotify({
                title: 'Error',
                text: 'Тухайн дансны багцыг ' + sub + ' багцаар солих боломжгүй байна',
                type: 'error',
                sticker: false
            });
        }
    }
    if (debit > 0 && credit == 0) {
        $('#glEntryAccountDtlGrid tbody').find("tr").each(function() {
            if ($(this).find("input[name='bagts[]']").val() == bagts && $(this).find("input[name='gl_accounId[]']").val() != accountId) {
                if (getRowNumericValue($(this), "input[name='debit[]']", 'int') > 0 && getRowNumericValue($(this), "input[name='credit[]']", 'int') == 0) {
                    beforeValue = true;
                }
                if (getRowNumericValue($(this), "input[name='credit[]']", 'int') > 0 && getRowNumericValue($(this), "input[name='debit[]']", 'int') == 0) {
                    beforeAdverseValue++;
                }
            }
        });
    } else if (credit > 0 && debit == 0) {
        $('#glEntryAccountDtlGrid tbody').find("tr").each(function() {
            if ($(this).find("input[name='bagts[]']").val() == bagts && $(this).find("input[name='gl_accounId[]']").val() != accountId) {
                if (getRowNumericValue($(this), "input[name='credit[]']", 'int') > 0 && getRowNumericValue($(this), "input[name='debit[]']", 'int') == 0) {
                    beforeValue = true;
                }
                if (getRowNumericValue($(this), "input[name='debit[]']", 'int') > 0 && getRowNumericValue($(this), "input[name='credit[]']", 'int') == 0) {
                    beforeAdverseValue++;
                }
            }
        });
    }
    if (beforeValue && beforeAdverseValue > 1) {
        var newBagts = changeBagts(_thisRow);
        bagts = newBagts;
        $(_thisRow.find("input[name='bagts[]']")).val(newBagts);
        sidebarContent.find("input[name='bagts[]']").val(newBagts);
        $('#glEntryAccountDtlGrid tbody tr:eq(' + index + ')').trigger('click');
    }
}
function renderSidebar(windowId, row) {
    var selectedTR = $(windowId).find('#glEntryAccountDtlGrid tbody').find('tr.selected');
    if (row.find("td").length > 1) {
        var sidebarContent = $(".grid-row-content", glEntryWindowId).find("table tbody");
        sidebarContent.empty();
        if (!$("#sum_div", glEntryWindowId).length) {
            $(glEntryWindowId).append("<div id='sum_div'></div>");
        }
        var rightsidebar = $(".right-sidebar", glEntryWindowId);
        var parentWidth = rightsidebar.find('#property_sum').find('table tr:eq(1)').find('td:eq(1)').width();
        var oldParentWidth = rightsidebar.width() - 152;
        var width = (parentWidth === 0) ? oldParentWidth : parentWidth;
        row.find('td:last-child').find('span.fa_asset_sidebar_content').not('#hideFromSideBar').each(function() {
            var _ths = $(this);
            if (_ths.find('#cm').find("div").length > 1) {
                _ths.find('#cm').find("div").first().remove();
            }
            if (_ths.find('#vt').find("div").length > 1) {
                _ths.find('#vt').find("div").first().remove();
            }
            var inputs = _ths.find('.input_html').html().replace(/[,]/g, '');
            if (_ths.find('#books').length > 0) {
                inputs = _ths.find('.input_html').html().replace("border", "width:" + 70 * width / 100 + "px; border");
            }
            sidebarContent.prepend(
                    "<tr>" +
                    "<td style='width: 150px;' class='left-padding'>" + _ths.find('.input_label_txt').text() + ":</td>" +
                    "<td><div id='right-sidebar-container-values'>" + inputs + "</div></td>" +
                    "</tr>"
                    );
            if (_ths.find('.input_html').find('input').hasClass('sum') === true) {
                var inputName = _ths.find('input').attr('name');
                if ($("#sum_div", glEntryWindowId).find('input[id="SUM_' + inputName + '"]').length === 0) {
                    $("#sum_div", glEntryWindowId).append("<input type='hidden' id='SUM_" + inputName + "'>");
                    $("#property_sum").find("table tbody").append(
                            "<tr>" +
                            "<td style='150px;' class='left-padding'>" + _ths.find('.input_label_txt').text() + ":</td>" +
                            "<td class='text-right'><p class=\"property_page_sum_padding form-control-plaintext numberInit\" id='INPUT_SUM_" + inputName.replace(/[[]]/g, '') + "'>0</p></td>" +
                            "</tr>"
                            );
                }
            }
        });
        sidebarContent.find('tr').find("#right-sidebar-container-values").attr('style', 'width:' + width + 'px;');
        sidebarContent.prepend(
                "<tr>" +
                "<td style='width: 150px;' class='left-padding'>Дансны код:</td>" +
                "<td><div class='input-group'><div class=\"input-icon\">" +
                "<input type=\"text\" name=\"codeSidebar\" id=\"codeSidebar\" class=\"form-control\" value=\"" + row.find('td:eq(1)').text() + "\"></div>" +
                "<span class=\"input-group-btn\"><div class=\"btn btn-sm green-meadow\" id=\"selectAccount\"><i class=\"fa fa-search\"></i></div></span>" +
                "</div></td>" +
                "</tr>" +
                "<tr>" +
                "<td style='width: 150px;' class='left-padding'>Дансны нэр:</td>" +
                "<td><p class=\"form-control-plaintext\" onmouseover=\"showAccountNameDtl(this);\" style=\"height:30px; overflow:hidden; line-height:200%;\">" + row.find('td:eq(5)').find("input[name='accountName[]']").val() + "</p></td>" +
                "</tr>" +
                "<tr>" +
                "<td style='width: 150px;' class='left-padding'>Багц:</td>" +
                "<td><input type=\"text\" name=\"sub\" class=\"form-control\" value=\"" + row.find('td:eq(0)').find("input[name='bagts[]']").val() + "\"></td>" +
                "</tr>"
                );
        $('.numberInit').autoNumeric('init', {aPad: false, mDec: 2, vMin: '-99999999999.99'});
        var accountId = row.find("input[name='gl_accounId[]']").val();
        var accountTypeId = row.find("input[name='accountTypeId[]']").val();
        var metaCheck = selectedTR.find('td:last-child').find('.gl-meta-content-sidebar').length;
        if (accountId !== '' && typeof accountId !== 'undefined' && metaCheck === 0) {
//            $.ajax({
//                type: 'post',
//                url: 'mdgl/accountMetaData',
//                data: {accountId: accountId, accountTypeId: accountTypeId, accessDataNameIndex: selectedTR.index()}, beforeSend: function() {
//                },
//                success: function(data) {
//                    if (data != '') {
//                        if ($(".metaSidebar", windowId).hasClass("hide")) {
//                            $(".metaSidebar", windowId).removeClass("hide");
//                        }
//                        if (selectedTR.hasClass('edit-gl-meta')) {
//                            var accountId = selectedTR.find('td:last-child').find("input[name='ACCOUNT_ID[]']").val();
//                            $.post('mdgl/getAccountMeta', {accountId: accountId}, function(res) {
//                                if ($.trim(res) !== '') {
//                                    $(".metaSidebar span", windowId).empty().append(data);
//                                    $.each(res, function() {
//                                        $(".metaSidebar span", windowId).find('table tr').find('td').find('input[name="accountMeta[' + selectedTR.index() + '][' + itemKeyGroupId + '][' + this.META_DATA_ID + '][metaValueId][]"]').val(this.META_VALUE);
//                                        $(".metaSidebar span", windowId).find('table tr').find('td').find('select[name="accountMeta[' + selectedTR.index() + '][' + itemKeyGroupId + '][' + this.META_DATA_ID + '][metaValueId][]"] option')
//                                                .filter('[value="' + this.META_VALUE_ID + '"]').attr("selected", "selected");
//                                    });
//                                    $('<div class="gl-meta-content-sidebar hide">' + $(".metaSidebar span", windowId).html() + '</div>').appendTo(selectedTR.find("td:last-child"));
//                                } else {
//                                    $(".metaSidebar span", windowId).empty().append(data);
//                                    $('<div class="gl-meta-content-sidebar hide">' + data + '</div>').appendTo(selectedTR.find("td:last-child"));
//                                }
//                            });
//                        } else {
//                            $(".metaSidebar span", windowId).empty().append(data);
//                            $('<div class="gl-meta-content-sidebar hide">' + data + '</div>').appendTo(selectedTR.find("td:last-child"));
//                        }
//                    } else {
//                        if (!$(".metaSidebar", windowId).hasClass("hide")) {
//                            $(".metaSidebar", windowId).addClass("hide");
//                        }
//                    }
//                },
//                error: function() {
//                    alert("Error");
//                }
//            }).done(function() {
//                $("div.gl-meta-content-sidebar", glEntryWindowId).find(".select2").select2("destroy");
//                Core.initSelect2();
//            });
            $.ajax({
                type: 'post',
                url: 'mdgl/getMetaByAccountType',
                data: {accountId: accountId, accountTypeId: accountTypeId, accessDataNameIndex: selectedTR.index()}, beforeSend: function() {
                },
                success: function(data) {
                    if (data != '') {
                        if ($(".metaSidebar", windowId).hasClass("hide")) {
                            $(".metaSidebar", windowId).removeClass("hide");
                        }
                        if (selectedTR.hasClass('edit-gl-meta')) {
                            var dtlId = selectedTR.find('td:last-child').find("input[name='dtlId[]']").val();
                            $(".metaSidebar span", windowId).empty().append(data);
                            $('<div class="gl-meta-content-sidebar hide">' + data + '</div>').appendTo(selectedTR.find("td:last-child"));
                            $.post('mdgl/getGlDtlMeta', {dtlId: dtlId}, function(res) {
                                if ($.trim(res) !== '') {
                                    $.each(res, function() {
                                        var appendItem = $(windowId).find(".metaSidebar span").find('table tr').find('td');
                                        var itemName = '"accountMeta[' + selectedTR.index() + '][' + this.FIELD_PATH + '][' + this.META_DATA_ID + ']"';
                                        appendItem.find("input[name=" + itemName + "]").val(this.META_VALUE);
                                        appendItem.find("select[name=" + itemName + "]").attr('data-edit-value', this.META_VALUE_ID);
                                        appendItem.find("select[name=" + itemName + "]").find('option:selected').removeAttr("selected");
                                        appendItem.find("select[name=" + itemName + "]").find('option').filter('[value="' + this.META_VALUE_ID + '"]').attr("selected", "selected");
                                        appendItem.find("select[name=" + itemName + "]").trigger("change");
                                    });
                                    $('<div class="gl-meta-content-sidebar hide">' + $(".metaSidebar span", windowId).html() + '</div>').appendTo(selectedTR.find("td:last-child"));
                                }
                            });
                        } else {
                            $(".metaSidebar span", windowId).empty().append(data);
                            $('<div class="gl-meta-content-sidebar hide">' + data + '</div>').appendTo(selectedTR.find("td:last-child"));
                        }
                    } else {
                        if (!$(".metaSidebar", windowId).hasClass("hide")) {
                            $(".metaSidebar", windowId).addClass("hide");
                        }
                    }
                },
                error: function() {
                    alert("Error");
                }
            }).done(function() {
                $("div.gl-meta-content-sidebar", glEntryWindowId).find(".select2").select2("destroy");
                Core.initSelect2();
                var fitWidth = rightsidebar.find('#generalInfo').find('table tr:eq(1)').find('td:eq(1)').width();
                $(".metaSidebar").find("table > tbody > tr").each(function() {
                    $(this).find("td:last").find('div').attr("style", "width:" + fitWidth + "px;");
                });
            });
        } else if (metaCheck > 0) {
            if ($(".metaSidebar", windowId).hasClass("hide")) {
                $(".metaSidebar", windowId).removeClass("hide");
            }
            $(".metaSidebar span", windowId).empty().append(selectedTR.find("td:last-child").find(".gl-meta-content-sidebar").html());
        } else {
            $(".metaSidebar", windowId).addClass("hide");
            $(".metaSidebar span", windowId).empty();
        }
        var _thisRow = $('#glEntryAccountDtlGrid tbody', glEntryWindowId)
                .find('tr:eq(' + $("#searchClickedTR", glEntryWindowId).val() + ')');
        $(".sidebar_input", glEntryWindowId).keyup(function() {
            gridGlEntryItemFormula(_thisRow, this.name);
            gridGlEntryItemSum();
        });
        Core.initInputType();
        $(".generalInfo span", windowId).find('table tbody').find("tr").each(function() {
            if ($(this).find('td:eq(1)').find('.select2-container').length > 1) {
                $(this).find('td:eq(1)').find('.select2-container').first().remove();
            }
        });
        $(".metaSidebar span", windowId).find('table tbody').find("tr").each(function() {
            if ($(this).find('td:eq(1)').find('.select2-container').length > 1) {
                $(this).find('td:eq(1)').find('.select2-container').first().remove();
            }
        });
    }
}
function showAccountNameDtl(elem) {
    var pop_options = {placement: 'left', trigger: 'click', html: true, title: 'Дансны нэр', container: 'body'};
    var html = $(elem).closest('p').text();
    $(elem).data('content', html).popover(pop_options);
    $(elem).click();
}
function showBookNumberDtl(elem) {
    var pop_options = {placement: 'left', trigger: 'click', html: true, title: 'Баримтын дугаар', container: 'body'};
    var html = $(elem).find("a").html();
    $(elem).data('content', html).popover(pop_options);
    $(elem).click();
}
function getBookNumber(glEntryWindowId){
    $.ajax({
        type: 'post',
        url: 'mdcommon/getAutoNumber',
        data: {
            bookTypeId: 2,
            objectId: 40002
        },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                animate: true
            });
        },
        success: function(data) {
            if (data.status === 'success') {
                $("#glbookNumber", glEntryWindowId).val(data.result);
            } else {
                new PNotify({
                    title: 'Error',
                    text: data.message,
                    type: 'error',
                    sticker: false
                });
            }
            Core.unblockUI();
        }
    });
}
//</editor-fold>

function budgetConnectGeneralLedger(elem, rowStr, obj) {
    
    var postData = {rowStr: rowStr};
    
    if (typeof obj !== 'undefined') {
        postData = obj;
        postData['paramData'] = paramDataToObject(obj.paramData);
    }
    
    $.ajax({
        type: 'post',
        url: 'mdgl/budgetConnectGL',
        data: postData, 
        dataType: 'json',
        beforeSend: function(){
            Core.blockUI({
                message: 'Loading...', 
                boxed: true 
            });
        },
        success: function(data) {
            
            if (data.status == 'success') {
                
                var dialogName = '#dialog-budget-gl';
                if (!$(dialogName).length) {
                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                }
                var $dialog = $(dialogName);
                var isDvReload = false;
                
                if (data.hasOwnProperty('isDvReload') && data.isDvReload) {
                    isDvReload = data.isDvReload;
                }
                
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 1180,
                    height: 'auto',
                    modal: true,
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function () {
                            
                            var saveUrl = 'mdgl/createGlEntry';
                            if (data.isEdit == true) {
                                saveUrl = 'mdgl/updateGlEntry';
                            }    
                            
                            $('form#glEntryForm', dialogName).ajaxSubmit({
                                type: 'post',
                                url: saveUrl, 
                                dataType: 'json',
                                beforeSubmit: function (formData, jqForm, options) {
                                    formData.push({name: 'isFromBudget', value: 1});
                                },
                                beforeSend: function () {
                                    Core.blockUI({
                                        message: 'Loading...', 
                                        boxed: true 
                                    });
                                },
                                success: function (dataSub) {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: dataSub.status,
                                        text: dataSub.message,
                                        type: dataSub.status,
                                        sticker: false
                                    });
                                    
                                    if (dataSub.status == 'success') {
                                        
                                        $dialog.dialog('close');
                                        
                                        if (isDvReload) {
                                            dataViewReload(postData.dataViewId);
                                        } else {
                                            if (data.isEdit == false) {
                                            
                                                var budgetIframe = document.getElementById('sheet_budget_iframe');
                                                var sendIframeSetValue = function(msg) {
                                                    budgetIframe.contentWindow.postMessage(msg, '*');
                                                };

                                                sendIframeSetValue('glBookId='+dataSub.id);
                                            }
                                        }
                                    } 
                                    
                                    Core.unblockUI();
                                }
                            });
                        }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
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
                $dialog.dialog('open');
                
            } else {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    addclass: pnotifyPosition,
                    sticker: false 
                });
            }
             
            Core.unblockUI();
        },
        error: function() {
            alert('Error');
            Core.unblockUI();
        }
    });
}

