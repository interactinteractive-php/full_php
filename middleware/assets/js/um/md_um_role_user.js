/* global go, Core, msg_saving_block, PNotify */
var mdUmRoleObj;

var MdUmRoleUser = function (roleId, userId, uniqId) {
    mdUmRoleObj = this;
    this.roleId = roleId;
    this.userId = userId;
    this.uniqId = uniqId;
    this.dataGridId = '#usersdatagrid_' + uniqId;
    this.periodId = null;
    this.headerConfig = {};
    if (typeof this.roleId === "number") {
        this.columns = [[
                {field: 'LAST_NAME', title: 'Овог', align: 'left', sortable: false, fit: true, width: '140'},
                {field: 'FIRST_NAME', title: 'Нэр', align: 'left', sortable: false, fit: true, width: '140'},
                {field: 'USERNAME', title: 'Хэрэглэгчийн нэр', align: 'left', sortable: false, width: '140'},
                {field: 'DEPARTMENT_CODE', title: 'Салбар нэгжийн код', align: 'left', sortable: false, width: '150'},
                {field: 'DEPARTMENT_NAME', title: 'Салбар нэгж', align: 'left', sortable: false, width: '210'},
                {field: 'PARENT_DEPARTMENT', title: 'Харъяа салбар нэгж', align: 'left', sortable: false, width: '250'},
                {field: 'POSITION_NAME', title: 'Албан тушаал', align: 'left', sortable: false, width: '210'},
                {field: 'CREATED_DATE', title: plang.get('createdDate'), align: 'left', sortable: false, width: '130'},
                {field: 'CREATED_USER_NAME', title: plang.get('createdUserName'), align: 'left', sortable: false, width: '150'},
                {field: 'IS_ACTIVE_TXT', title: 'Идэвхитэй эсэх', align: 'center', sortable: false, formatter: isActiveTxt, width: '100'},
                {field: 'IS_ACTIVE', title: 'Үйлдэл', sortable: false, align: 'center', width: '80', formatter: activeButton}
            ]];
    } else if (typeof this.userId === "number") {
        this.columns = [[
                {field: 'ROLE_CODE', title: 'Код', align: 'left', sortable: false, fit: true, width: '20%'
                },
                {field: 'ROLE_NAME', title: 'Нэр', align: 'left', sortable: false, fit: true, width: '40%'
                },
                {field: 'IS_ACTIVE_TXT', title: 'Идэвхитэй эсэх', align: 'left', sortable: false,
                    formatter: isActiveTxt,
                    width: '15%'},
                {field: 'IS_ACTIVE', title: 'Үйлдэл', sortable: false, align: 'center', width: '10%',
                    formatter: activeButton}
            ]];
    }

};

MdUmRoleUser.prototype.initEventListener = function () {
    $('#addRoleToUser').click(function () {

        var $this = this;

        if (mdUmRoleObj.roleId) {
            $($this).attr('data-criteria', 'roleId=' + mdUmRoleObj.roleId);
        }

        dataViewSelectableGrid('nullmeta', '0', '1524482191989', 'multi', 'nullmeta', $this, 'toUserRoleSaveUm');

        /*if($("#selectedUserId").val() !== ''){
         Core.blockUI({
         message: 'Боловсруулж байна түр хүлээнэ үү...',
         boxed: true
         });
         $.ajax({
         type: 'post',
         url: 'mdum/saveRoleUser',
         dataType: "json",
         data: {userId: $("#selectedUserId").val(),
         roleId: mdUmRoleObj.roleId},
         success: function(data){
         if(data.status === 'success'){
         $(mdUmRoleObj.dataGridId).datagrid("reload");
         PNotify.removeAll();
         new PNotify({
         title: data.status,
         text: 'Үйлдэл амжилттай боллоо',
         type: 'success',
         sticker: false
         });
         } else {
         PNotify.removeAll();
         new PNotify({
         title: data.status,
         text: 'Үйлдэл амжилтгүй боллоо.',
         type: 'warning',
         sticker: false
         });
         }
         },
         error: function(jqXHR, exception){
         Core.unblockUI();
         }
         }).complete(function(){
         Core.unblockUI();
         });
         } else {
         PNotify.removeAll();
         new PNotify({
         title: '',
         text: 'Хэрэглэгч сонгоно уу.',
         type: 'warning',
         sticker: false
         });
         }*/
    });
};

MdUmRoleUser.prototype.loadDataGrid = function () {
    var dataGridEl = $(this.dataGridId);
    var gridHeight = elemHeight(dataGridEl, 250, 0);

    dataGridEl.datagrid({
        url: 'mdum/getRoleUsers',
        queryParams: {
            roleId: this.roleId,
            userId: this.userId
        },
        rownumbers: true,
        singleSelect: true,
        pagination: true,
        pageSize: 20,
        height: gridHeight,
        width: '100%',
        striped: false,
        remoteSort: true,
        remoteFilter: true,
        filterDelay: 10000000000,
        //fitColumns: true,
        columns: this.columns,
        onBeforeLoad: function (p) {
            Core.blockUI({
                animate: true
            });
        },
        onLoadSuccess: function () {
            showGridMessage(dataGridEl);
            Core.unblockUI();
        }
    });
    dataGridEl.datagrid('enableFilter', [
        {field: 'IS_ACTIVE', type: 'label'},
        {
            field: 'IS_ACTIVE_TXT',
            type: 'combobox',
            options: {
                panelHeight: 'auto',
                data: [{value: '', text: 'All'}, {value: '1', text: 'Тийм'}, {value: '0', text: 'Үгүй'}],
                onChange: function (value) {
                    if (value == '') {
                        dataGridEl.datagrid('removeFilterRule', 'IS_ACTIVE_TXT');
                    } else {
                        dataGridEl.datagrid('addFilterRule', {
                            field: 'IS_ACTIVE_TXT',
                            op: 'equal',
                            value: value
                        });
                    }
                    dataGridEl.datagrid('doFilter');
                }
            }
        }
    ]);
};

MdUmRoleUser.prototype.initUserAutocomplete = function () {
    var umRole = this;

    var getDisplayTxt = function (item) {
        return "(" + (item.LAST_NAME == null ? "" :
                item.LAST_NAME) + " " + (item.FIRST_NAME == null ? "" :
                item.FIRST_NAME) + ") " + (item.DEPARTMENT_NAME == null ? "" :
                item.DEPARTMENT_NAME);

    };

    $("#role_user_list_tab_" + mdUmRoleObj.uniqId).on("focus, keydown", 'input#usernameAc:not(disabled, readonly)', function (e) {
        var _this = $(this);
        var isHoverSelect = false;

        _this.autocomplete({
            minLength: 1,
            maxShowItems: 30,
            delay: 500,
            highlightClass: "lookup-ac-highlight",
            appendTo: "body",
            position: {my: "left top", at: "left bottom", collision: "flip flip"},
            autoFocus: false,
            source: function (request, response) {
                $.ajax({
                    type: 'post',
                    url: 'mdum/getUsers',
                    dataType: "json",
                    data: {q: request.term,
                        roleId: umRole.roleId},
                    success: function (data) {
                        response($.map(data, function (item) {
                            return {
                                label: item.USERNAME,
                                name: getDisplayTxt(item),
                                data: item
                            };
                        }));
                    }
                });
            },
            focus: function (event, ui) {
                if (typeof event.keyCode === 'undefined' || event.keyCode == 0) {
                    isHoverSelect = false;
                } else {
                    if (event.keyCode == 38 || event.keyCode == 40) {
                        isHoverSelect = true;
                    }
                }
                return false;
            },
            open: function () {
                $(this).autocomplete('widget').zIndex(99999999999999);
                return false;
            },
            close: function (event, ui) {
                $(this).autocomplete("option", "appendTo", "body");
            },
            select: function (event, ui) {
                _this.val(getDisplayTxt(ui.item.data));
                toUserRoleSaveUmSingle(mdUmRoleObj.roleId, ui.item.data.USER_ID);
                return false;
            }
        }).autocomplete("instance")._renderItem = function (ul, item) {
            ul.addClass('lookup-ac-render');

            var re = new RegExp("(" + this.term + ")", "gi"),
                    cls = this.options.highlightClass,
                    template = "<span class='" + cls + "'>$1</span>",
                    label = item.label.replace(re, template);

            return $('<li>').append('<div class="lookup-ac-render-code">' + label +
                    '</div><div class="lookup-ac-render-name">' + item.name + '</div>').appendTo(
                    ul);
        };
    });
};

var activeButton = function (val, row) {

    if (row.IS_ACTIVE == 1) {
        var icon = 'icon-minus3';
        var title = plang.get('MET_332424');
    } else {
        var icon = 'icon-checkmark';
        var title = plang.get('MET_332425');
    }

    var removeBtn = '<button type="button" onclick="umRoleUserRemove(this, ' + row.ID + ');" class="btn btn-xs btn-danger ml5" title="' + plang.get('delete_btn') + '"><i class="icon-trash"></i></button>';

    return '<a onclick="changeIsActive(this, ' + row.ID + ', ' + row.IS_ACTIVE + ');" title="' + title + '" class="btn btn-xs green" href="javascript:;"><i class="' + icon + '"></i></a>' + removeBtn;
};

var isActiveTxt = function (val, row) {
    if (row.IS_ACTIVE == 1) {
        var actionname = plang.get('yes_btn');
    } else {
        var actionname = plang.get('no_btn');
    }
    return actionname;
};

var changeIsActive = function (thisEl, roleUserId, isActive) {
    Core.blockUI({
        message: 'Боловсруулж байна түр хүлээнэ үү...',
        boxed: true
    });
    $.ajax({
        type: 'post',
        url: 'mdum/changeIsActive',
        dataType: "json",
        data: {roleUserId: roleUserId, isActive: isActive},
        success: function (data) {
            PNotify.removeAll();
            if (data.status === 'success') {
                $(mdUmRoleObj.dataGridId).datagrid("reload");
                new PNotify({
                    title: data.status,
                    text: 'Үйлдэл амжилттай боллоо',
                    type: 'success',
                    sticker: false
                });
            } else {
                new PNotify({
                    title: data.status,
                    text: 'Үйлдэл амжилтгүй боллоо.',
                    type: 'warning',
                    sticker: false
                });
            }
        },
        error: function (jqXHR, exception) {
            Core.unblockUI();
        }
    }).complete(function () {
        Core.unblockUI();
    });
};

var umRoleUserRemove = function (thisEl, roleUserId) {
    PNotify.removeAll();

    var $dialogName = 'dialog-roleuser-removeconfirm';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    $dialog.html(plang.get('msg_delete_confirm'));
    $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: plang.get('msg_title_confirm'),
        width: 400,
        height: 'auto',
        modal: true,
        buttons: [
            {text: plang.get('yes_btn'), class: 'btn btn-sm green', click: function () {
                    PNotify.removeAll();
                    $.ajax({
                        type: 'post',
                        url: 'mdum/roleUserRemove',
                        data: {id: roleUserId},
                        dataType: 'json',
                        success: function (data) {
                            new PNotify({
                                title: data.status,
                                text: data.message,
                                type: data.status,
                                sticker: false,
                                addclass: pnotifyPosition
                            });
                            if (data.status == 'success') {
                                $dialog.dialog('close');
                                $(mdUmRoleObj.dataGridId).datagrid('reload');
                            }
                        }
                    });
                }},
            {text: plang.get('no_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                    $dialog.dialog('close');
                }}
        ]
    });
    $dialog.dialog('open');
};

function toUserRoleSaveUm(metaDataCode, processMetaDataId, chooseType, elem, rows, paramRealPath, lookupMetaDataId, isMetaGroup) {
    Core.blockUI({
        message: 'Боловсруулж байна түр хүлээнэ үү...',
        boxed: true
    });
    $.ajax({
        type: 'post',
        url: 'mdum/saveRoleUserMulti',
        data: {roleId: mdUmRoleObj.roleId, rows: rows},
        dataType: 'json',
        success: function (data) {
            PNotify.removeAll();

            if (data.status === 'success') {
                $(mdUmRoleObj.dataGridId).datagrid('reload');
                new PNotify({
                    title: data.status,
                    text: 'Үйлдэл амжилттай боллоо',
                    type: 'success',
                    sticker: false
                });
            } else {
                new PNotify({
                    title: data.status,
                    text: 'Үйлдэл амжилтгүй боллоо.',
                    type: 'warning',
                    sticker: false
                });
            }
        },
        error: function (jqXHR, exception) {
            Core.unblockUI();
        }
    }).complete(function () {
        Core.unblockUI();
    });
}
function toUserRoleSaveUmSingle(roleId, userId) {
    $.ajax({
        type: 'post',
        url: 'mdum/saveRoleUser',
        data: {userId: userId, roleId: roleId},
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function (data) {

            PNotify.removeAll();

            if (data.status === 'success') {
                new PNotify({
                    title: data.status,
                    text: 'Үйлдэл амжилттай боллоо',
                    type: 'success',
                    sticker: false
                });
                $(mdUmRoleObj.dataGridId).datagrid('reload');
                $("#role_user_list_tab_" + mdUmRoleObj.uniqId).find('input#usernameAc').val('');
            } else {
                new PNotify({
                    title: data.status,
                    text: 'Үйлдэл амжилтгүй боллоо.',
                    type: 'warning',
                    sticker: false
                });
            }
        },
        error: function (jqXHR, exception) {
            Core.unblockUI();
        }
    }).complete(function () {
        Core.unblockUI();
    });
}