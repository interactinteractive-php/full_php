var group = function () {

    var groupForm = '#group-form',
            groupAddFormID = '#group-add-form',
            createGroupFormID = '#group-create-form-id',
            GROUP_MEMBER_ID = 0,
            YES = '',
            editGroupFormID = '#group-edit-form-id';

    var initEvent = function () {
        $('#group_admin_id').select2();
        $('#group_admin_id').change(function () {
            if ($(this).val() == createdUserId) {
                $('#is_enroll_group').attr('disabled', true);
            } else {
                $('#is_enroll_group').removeAttr('disabled');
            }
        });
        if (typeof $('#group_admin_id_hidden').val() !== "undefined") {
            if (createdUserId == $('#group_admin_id_hidden').val()) {
                if ($('#group_admin_id').hasAttr('disabled')) {
                    $(this).removeAttr('disabled');
                }
            } else {
                if ($('#group_admin_id').hasAttr('disabled')) {
                } else {
                    $('#group_admin_id').attr('disabled', true);
                }
            }
        }
        $(createGroupFormID).validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: ".ignore",
            rules: {
                group_name: {required: true},
                group_type_id: {required: true},
                group_description: {required: true}
            },
            messages: {
                group_name: " ",
                group_type_id: " ",
                group_description: " "
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.help-block').removeClass('ok'); // display OK icon
                $(element).closest('.form-group').removeClass('success').addClass(
                        'has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change dony by hightlight
                $(element).closest('.form-group').removeClass(
                        'has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
            }
        });
        $(editGroupFormID).validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: ".ignore",
            rules: {
                group_name: {required: true},
                group_type_id: {required: true},
                group_description: {required: true}
            },
            messages: {
                group_name: " ",
                group_type_id: " ",
                group_description: " "
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.help-block').removeClass('ok'); // display OK icon
                $(element).closest('.form-group').removeClass('success').addClass(
                        'has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change dony by hightlight
                $(element).closest('.form-group').removeClass(
                        'has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
            }
        });
    };
    var fillindexList = function () {
        $(function () {
            var member = 0;
            if ($('#srch_group_member').is(":checked"))
                member = 1;
            var searchParams = {
                GROUP_NAME: $('#srch_group_name').val(),
                GROUP_TYPE: $('#srch_group_type_id').val(),
                WFM_STATUS_ID: $('#srch_an_wfm_status_id').val(),
                GROUP_IS_MEMBER: member
            };
            $('.returnGroupDataGrid').datagrid({
                url: 'school/groupList',
                fit: false,
                fitColumns: true,
                rownumbers: true,
                pagination: true,
                pageSize: pageCount,
                queryParams: searchParams,
                nowrap: false,
                singleSelect: true,
                columns: [[
                        {field: 'GROUP_NAME', title: cam_group_name, sortable: true, align: 'left', width: 200,
                            formatter: groupDetail},
                        {field: 'GROUP_TYPE_NAME', title: cam_group_type, sortable: true, align: 'left',
                            width: 50},
                        {field: 'GROUP_MEMBER', title: cam_group_members, sortable: true, align: 'left',
                            width: 40, formatter: showGroupMember},
                        {field: 'TOPIC_COUNT', title: cam_topic_count, sortable: true, align: 'left',
                            width: 40},
                        {field: 'IS_MEMBER', title: cam_is_group_members, sortable: true, align: 'left',
                            width: 40, formatter: isMember},
                        {field: 'WFM_STATUS_NAME', title: cam_group_status, sortable: true, align: 'left',
                            width: 40},
                        {field: 'GROUP_ID', title: action, sortable: true, align: 'left', width: 60,
                            formatter: buttonsTerm}
                    ]],
                onLoadSuccess: function (data) {
                    showGridMessage($('.returnGroupDataGrid'));
                }
            });
            $(".add-group-btn").click(function () {

                Core.blockUI({animate: true});
                $.ajax({
                    url: "school/add/",
                    dataType: "html",
                    type: "POST",
                    success: function (data) {

                        var dialogname = $('#group-add-dialog');
                        var $dialogname = 'group-add-dialog';
                        dialogname.empty().html(data);
                        dialogname.dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: cam_group_add,
                            width: 900,
                            height: 380,
                            modal: true,
                            open: function () {
                                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group pull-right");
                                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-xs blue mr0');
                                $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn btn-xs green');
                            },
                            close: function () {
                                dialogname.empty().dialog('close');
                            },
                            buttons: [
                                {text: plang.get('save_btn'), click: function () {
                                        $(createGroupFormID).validate({errorPlacement: function () {
                                            }});
                                        var member = 0;
                                        if ($('#is_enroll_group').is(":checked"))
                                            member = 1;

                                        if ($(createGroupFormID).valid()) {
                                            $(createGroupFormID).ajaxSubmit({
                                                dataType: 'json',
                                                data: {ENROLL: member},
                                                beforeSend: function () {
                                                    show_msg_saving_block();
                                                },
                                                success: function (data) {
                                                    $.unblockUI();
                                                    dialogname.dialog('close');
                                                    $('.returnGroupDataGrid').datagrid('load', {});

                                                },
                                                error: function () {
                                                    $.unblockUI();
                                                    alert("Error");
                                                }
                                            });
                                        }
//                                        $(formAddGroupFormId)[0].reset();
                                    }},
                                {text: plang.get('close_btn'), click: function () {
                                        dialogname.dialog('close');
                                    }}
                            ]
                        });
                        dialogname.dialog('open');

                    },
                    error: function (jqXHR, exception) {
                        Core.unblockUI();
                    }
                }).complete(function () {
                    Core.unblockUI();
                });
            });
            $(document).keyup(function (e) {
                if (e.which === 13)
                    $('#searchGroupList').trigger("click");
                if (e.which === 27)
                    $('.groupclear').trigger("click");
            });
        });
        $('#searchGroupList').click(function () {
            var member = 0;
            if ($('#srch_group_member').is(":checked"))
                member = 1;

            $('.returnGroupDataGrid').datagrid('load', {
                GROUP_NAME: $('#srch_group_name').val(),
                GROUP_TYPE: $('#srch_group_type_id').val(),
                WFM_STATUS_ID: $('#srch_an_wfm_status_id').val(),
                GROUP_IS_MEMBER: member
            });
        });
        $('.groupclear').click(function () {
            var member = 0;
            if ($('#srch_group_member').is(":checked"))
                member = 0;

            $('#srch_group_name').val('');
            $('#srch_group_type_id').select2('val', '');
            $('#srch_an_wfm_status_id').select2('val', '');
            $("#srch_group_member").prop("checked", false);
            $('#search-group-form').find('.checker').find('span').removeClass('checked');

            $('.returnGroupDataGrid').datagrid('load', {
                GROUP_NAME: $('#srch_group_name').val(),
                GROUP_TYPE: $('#srch_group_type_id').val(),
                WFM_STATUS_ID: $('#srch_an_wfm_status_id').val(),
                GROUP_IS_MEMBER: member
            });
        });
        $(".pdfGroup").live("click", function () {
            $.blockUI(".block-ui");
            $.download('group/exportPdfGroupList', 'form', $("#search-group-form").serialize(), 'post',
                    '');
            $.unblockUI(".block-ui");
        });
        $(".excelGroup").live("click", function () {
            $.blockUI(".block-ui");
            $.download('group/exportExcelGroupList', 'form', $("#search-group-form").serialize(), 'post',
                    '');
            $.unblockUI(".block-ui");
        });
    };

    var buttonsTerm = function (val, row) {
        var html = '<div class="btn-group">';
        if (isedit == true && row.IS_MEMBER == 1) {
            if (row.STUDENT_KEY_ID === null || row.CREATED_USER_ID == createdUserId) {
                html += '<a class="btn btn-xs blue" href="javascript:;" onclick="actionGroup(' +
                        row.GROUP_ID + ', 1)" title="' + plang.get('edit_btn') + '"><i class="fa fa-edit"></i></a>';
            }
        } else if (isedit == true && row.CREATED_USER_ID == createdUserId) {
            html += '<a class="btn btn-xs blue" href="javascript:;" onclick="actionGroup(' +
                    row.GROUP_ID + ', 1)" title="' + plang.get('edit_btn') + '"><i class="fa fa-edit"></i></a>';
        }
        if (isdelete == true && row.IS_FOUNDER == 1) {
            if (row.STUDENT_KEY_ID === null || row.CREATED_USER_ID == createdUserId) {
                html += '<a class="btn btn-xs red" href="javascript:;" onclick="actionGroup(' +
                        row.GROUP_ID + ', 0)" title="' + plang.get('delete_btn') + '"><i class="fa fa-trash"></i></a>';
            }
        } else if (isdelete == true && row.STUDENT_KEY_ID !== null && row.CREATED_USER_ID ==
                createdUserId) {
            html += '<a class="btn btn-xs red" href="javascript:;" onclick="actionGroup(' +
                    row.GROUP_ID + ', 0)" title="' + plang.get('delete_btn') + '"><i class="fa fa-trash"></i></a>';
        }
        if (isrequest == true && row.IS_MEMBER == 0 && row.IS_REQUEST == 1 && row.IS_APPROVED == 1 &&
                row.WFM_STATUS_ID != 21556)
            html += '<a class="btn btn-xs green" href="javascript:;" onclick="actionGroup(' +
                    row.GROUP_ID + ', 2)" title="' + plang.get('request_send') +
                    '"><i class="fa fa-envelope"></i></a>';
        if (row.IS_APPROVED == 0)
            html += '<span class="btn btn-xs yellow" title="' + plang.get('request_sent') +
                    '"><i class="fa fa-send-o"></i></span>';

        if (row.IS_MEMBER == 1 || row.IS_PUBLIC == 1 && row.WFM_STATUS_ID != 21556) {
            html += '<a class="btn btn-xs yellow" href="javascript:;" onclick="actionGroupDetail(' +
                    row.GROUP_ID + ')" title="' + plang.get('view_group') + '"><i class="fa fa-sign-in"></i></a>';
        }

        html += '</div>';
        return html;
    };
    var isMember = function (val, row) {
        var html = no;
        if (row.IS_MEMBER == 1)
            html = yes;

        return html;
    };
//fa-sign-in
    var groupDetail = function (val, row) {
        var html = '';
        html = '<a href="javascript:;" onclick="groupDetailShow(' +
                row.GROUP_ID + ')">' + row.GROUP_NAME + '</a>';
        return html;
    };
    var groupDetailShow = function (id) {
        var dialogname = $('#get-member-dialog');
        var $dialogname = 'get-member-dialog';
        $.ajax({
            url: "school/getGroupDef/",
            dataType: "json",
            data: {GROUP_ID: id},
            type: "POST",
            success: function (data) {
                dialogname.empty().html(data);
                dialogname.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: cam_group_description,
                    width: 600,
                    modal: true,
                    open: function () {
                        $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group pull-right");
                        $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-xs blue mr0');
//            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn btn-xs green');
                    },
                    close: function () {
                        dialogname.dialog('close');
                    },
                    buttons: [
                        {text: plang.get('close_btn'), click: function () {
                                dialogname.dialog('close');
                            }}
                    ]
                });
                dialogname.dialog('open');

            },
            error: function (jqXHR, exception) {
                Core.unblockUI();
            }
        }).complete(function () {
            Core.unblockUI();
        });
    };

    var showGroupMember = function (val, row) {
        var html = '';
        //row.GROUP_TYPE_ID == CLOSED_TYPE && 
        if (row.WFM_STATUS_ID != 21556) {
            html = '<a href="javascript:;" onclick="actionGroupMember(' +
                    row.GROUP_ID + ')">' + row.GROUP_MEMBER + '</a>';
        } else {
            html = row.GROUP_MEMBER;
        }
        return html;
    };

    var groupDetailAction = function (id) {
        window.location = URL + 'group/groupInfo/' + id;
    };
    var groupAction = function (element, type) {
        if (parseInt(type) === 1) {
            $.ajax({
                url: "school/edit/",
                dataType: "html",
                type: "POST",
                data: {GROUP_ID: element},
                success: function (data) {

                    var dialogname = $('#group-add-dialog');
                    var $dialogname = 'group-add-dialog';
                    dialogname.empty().html(data);
                    dialogname.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: cam_group_edit,
                        width: 900,
                        height: 380,
                        modal: true,
                        open: function () {
                            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group pull-right");
                            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-xs blue mr0');
                            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn btn-xs green');
                        },
                        close: function () {
                            dialogname.empty().dialog('close');
                        },
                        buttons: [
                            {text: plang.get('save_btn'), click: function () {
                                    $(editGroupFormID).validate({errorPlacement: function () {
                                        }});
                                    if ($(editGroupFormID).valid()) {
                                        $(editGroupFormID).ajaxSubmit({
                                            dataType: 'json',
                                            beforeSend: function () {
                                                show_msg_saving_block();
                                            },
                                            success: function (data) {
                                                $.unblockUI();
                                                dialogname.dialog('close');
                                                $('.returnGroupDataGrid').datagrid('load', {});

                                            },
                                            error: function () {
                                                $.unblockUI();
                                                alert("Error");
                                            }
                                        });
                                    }
                                }},
                            {text: plang.get('close_btn'), click: function () {
                                    dialogname.dialog('close');
                                }}
                        ]
                    });
                    dialogname.dialog('open');

                },
                error: function (jqXHR, exception) {
                    Core.unblockUI();
                }
            }).complete(function () {
                Core.unblockUI();
            });
        } else if (parseInt(type) === 0) {
            var $dialogname = 'dialog-confirm';
            $("#dialog-confirm").dialog({
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: plang.get('msg_title_confirm'),
                width: 400,
                height: 'auto',
                modal: true,
                open: function () {
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group pull-right");
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-xs blue mr0');
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn btn-xs green');
                },
                buttons: [
                    {text: plang.get('yes_btn'), click: function () {
                            $.ajax({
                                data: {GROUP_ID: element},
                                url: "school/deleteGroup",
                                dataType: "json",
                                type: "POST",
                                success: function (data) {
                                    if (data.status == 'success') {
                                        console.log(element, type, 'deleted ' + data.status);
                                        $('.returnGroupDataGrid').datagrid('reload');
                                    } else {
                                        console.log(element, type, 'deleted ' + data.status);
                                        $('.returnGroupDataGrid').datagrid('reload');
                                    }
                                },
                                error: function (jqXHR, exception) {
                                    $('.returnGroupDataGrid').datagrid('reload');
                                }
                            });
                            $("#dialog-confirm").dialog('close');
                        }},
                    {text: plang.get('no_btn'), click: function () {
                            $("#dialog-confirm").dialog('close');
                        }}
                ]
            });
            $("#dialog-confirm").html(plang.get('msg_delete_confirm')).dialog('open');
        } else if (parseInt(type) === 2) {
            var $dialogname = 'dialog-confirm';
            $("#dialog-confirm").dialog({
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: plang.get('msg_title_confirm'),
                width: 400,
                height: 'auto',
                modal: true,
                open: function () {
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group pull-right");
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-xs blue mr0');
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn btn-xs green');
                },
                buttons: [
                    {text: plang.get('yes_btn'), click: function () {
                            $.ajax({
                                data: {GROUP_ID: element},
                                url: "school/sendRequestGroup",
                                dataType: "json",
                                type: "POST",
                                success: function (data) {
                                    if (data.status == 'success') {
                                        console.log(element, type, 'sent ' + data.status);
                                        $('.returnGroupDataGrid').datagrid('reload');
                                    } else {
                                        console.log(element, type, 'sent ' + data.status);
                                        $('.returnGroupDataGrid').datagrid('reload');
                                    }
                                },
                                error: function (jqXHR, exception) {
                                    $('.returnGroupDataGrid').datagrid('reload');
                                }
                            });
                            $("#dialog-confirm").dialog('close');
                        }},
                    {text: plang.get('no_btn'), click: function () {
                            $("#dialog-confirm").dialog('close');
                        }}
                ]
            });
            $("#dialog-confirm").html(plang.get('msg_request_confirm')).dialog('open');
        }
    };

    var groupMemberAction = function (element) {
        var dialogname = $('#get-member-dialog');
        var $dialogname = 'get-member-dialog';
        $.ajax({
            url: "school/getMemberTable/",
            dataType: "json",
            data: {GROUP_ID: element},
            type: "POST",
            success: function (data) {
                dialogname.empty().html(data);
                dialogname.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: cam_group_members,
                    width: 600,
                    modal: true,
                    open: function () {
                        $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group pull-right");
                        $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-xs blue mr0');
//          $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn btn-xs green');
                    },
                    close: function () {
                        dialogname.dialog('close');
                    },
                    buttons: [
                        {text: plang.get('close_btn'), click: function () {
                                dialogname.dialog('close');
                            }}
                    ]
                });
                dialogname.dialog('open');

            },
            error: function (jqXHR, exception) {
                Core.unblockUI();
            }
        }).complete(function () {
            Core.unblockUI();
        });


    };

    return {
        init: function () {
            initEvent();
        },
        fillindexList: function () {
            fillindexList();
        },
        groupAction: function (element, type) {
            groupAction(element, type);
        },
        groupMemberAction: function (element) {
            groupMemberAction(element);
        },
        groupDetailShow: function (element) {
            groupDetailShow(element);
        },
        groupDetailAction: function (id) {
            groupDetailAction(id);
        }
    };

}();
