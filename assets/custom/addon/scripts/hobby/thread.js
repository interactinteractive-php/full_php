var thread = function () {
    var threadForm = '#thread-form',
            createThreadFormID = '#thread-create-form-id',
            editThreadFormID = '#thread-edit-form-id',
            groupCommentAddFormID = '#groupCommentAddForm',
            TMP_HTML = '';
    var initEvent = function () {
        $(createThreadFormID).validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: ".ignore",
            rules: {
                thread_name: {required: true},
                thread_description: {required: true}
            },
            messages: {
                thread_name: " ",
                thread_description: " "
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
        $(editThreadFormID).validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: ".ignore",
            rules: {
                thread_name: {required: true},
                thread_description: {required: true}
            },
            messages: {
                thread_name: " ",
                thread_description: " "
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
        $(groupCommentAddFormID).validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: ".ignore",
            rules: {
                add_comment: {required: true}
            },
            messages: {
                add_comment: " "
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
            var GROUP_ID = $('#group_id').val();
            $(".add-thread-btn").click(function () {
                Core.blockUI({
                    animate: true
                });
                $.ajax({
                    url: "school/addThread/",
                    dataType: "html",
                    type: "POST",
                    data: {GROUP_ID: GROUP_ID},
                    success: function (data) {
                        var dialogname = $('#thread-add-dialog');
                        var $dialogname = 'thread-add-dialog';
                        dialogname.empty().html(data);
                        dialogname.dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: 'Нэмэх',
                            width: 900,
                            height: 350,
                            modal: true,
                            close: function () {
                                dialogname.empty().dialog('close');
                            },
                            buttons: [
                                {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm', click: function () {
                                        $(createThreadFormID).validate({errorPlacement: function () {
                                            }});
                                        if ($(createThreadFormID).valid()) {
                                            $(createThreadFormID).ajaxSubmit({
                                                dataType: 'json',
                                                beforeSend: function () {
                                                    Core.blockUI({animate: true});
                                                },
                                                success: function (data) {
                                                    Core.unblockUI();
                                                    dialogname.dialog('close');
                                                    drawThreas($('#group_id').val(), data.newsId);
                                                },
                                                error: function () {
                                                    Core.unblockUI();
                                                    alert("Error");
                                                }
                                            });
                                        }
//                                        $(formAddGroupFormId)[0].reset();
                                    }},
                                {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
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
            $('#add-Comment-Btn').click(function () {
                $(groupCommentAddFormID).validate({errorPlacement: function () {
                    }});
                if ($(groupCommentAddFormID).valid()) {
                    $(groupCommentAddFormID).ajaxSubmit({
                        dataType: 'json',
                        beforeSend: function () {
                            Core.blockUI({animate: true});
                        },
                        success: function (data) {
                            Core.unblockUI();
                            getCommentAction($('#thread_id').val(), 0);
                        },
                        error: function () {
                            Core.unblockUI();
                            alert("Error");
                        }
                    });
                }
            });
            $("#searchGroupMember").keyup(function () {
                autoComplete('exist', this, $("#searchGroupMember").val());
            });
            $("#SearchNewMember").keyup(function () {
                if ($(this).val().length > 0) {
                    autoComplete('new', this, $("#SearchNewMember").val());
                }
            });
            $('#btn-back').click(function () {
//                location.reload();
            });
        });
    };
    var drawThreas = function (groupId, threadId) {

        Core.blockUI({animate: true});
        $.post('school/newThreadData', {GROUP_ID: groupId, THREAD_ID: threadId}, function (res) {
            if (res != '') {
                TMP_HTML = '<div id="' + threadId + '"><div class="row">';
                TMP_HTML += '<div class="col-md-4 blog-img blog-tag-data">';
                if (res.PICTURE != '') {
                    TMP_HTML += '<img src="' + UPLOADPATH + res.PICTURE + '" alt="" class="img-responsive"  width= "500px">';
                } else {
                    TMP_HTML += '<img src="' + UPLOADPATH + 'assets/core/global/img/hobby/no-image.png" alt="" class="img-responsive"  width= "500px">';
                }
                TMP_HTML += '<ul class="list-inline">'
                        + '<li><i class="fa fa-calendar"></i>'
                        + res.CREATED_DATE
                        + '</li>'
                        + '<li>'
                        + '<i class="fa fa-comments"></i>'
                        + '<a href="#">'
                        + ' 0 ' + thread_comment + ' </a>'
                        + '</li>'
                        + '</ul>'
                        + '<ul class="list-inline blog-tags">'
                        + '<li>'
                        + '<i class="fa fa-eye"></i> 0 '
                        + thread_view
                        + '</li> '
                        + '<li>'
                        + '<i class="fa fa-tags"></i>'
                        + '<a href="#" title="' + thread_publisher + '">'
                        + res.USERNAME
                        + '</a>'
                        + '</li>'
                        + '</ul>'
                        + '</div>'
                        + '<div class="col-md-8 blog-article">'
                        + '<div class="btn-group pull-right">'
                        + '<button class="btn btn-circle green-haze btn-sm dropdown-toggle" type="button" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">'
                        + action + ' <i class="fa fa-angle-down"></i>'
                        + '</button>'
                        + '<ul class="dropdown-menu pull-right" role="menu">'
                        + '<li><a href="javascript:;" onclick="actionThread(' + res.NEWS_ID + ', 1, ' + res.GROUP_ID + ')">' + thread_change_logo + '</a></li>'
                        + '<li role="separator" class="divider"></li>'
                        + '<li><a href="javascript:;" onclick="actionThread(' + res.NEWS_ID + ', 0, ' + res.GROUP_ID + ')">' + thread_delete + '</a></li>'
                        + '</ul>'
                        + '</div>'
                        + '<h3>'
                        + '<a href="">'
                        + res.HEADLINE + '</a>'
                        + '</h3>'
                        + '<p>'
                        + res.BODY_PLAIN
                        + '</p>'
                        + '<a class="btn blue btn-sm" href="javascript:;" onclick="actionGetComment(' + res.NEWS_ID + ', 1, \'' + res.HEADLINE + '\')">'
                        + thread_more
                        + ' <i class="m-icon-swapright m-icon-white"></i>'
                        + '</a>'
                        + '</div>';
                TMP_HTML += '</div></div><hr>';
            }
            $('#thread-data-list').prepend(TMP_HTML).fadeIn(3000);
        }, 'json');
        Core.unblockUI();
        return TMP_HTML;
    };
    var threadAction = function (element, type, groupId) {
        if (parseInt(type) === 1) {
            $.ajax({
                url: "school/editThread/",
                dataType: "html",
                type: "POST",
                data: {THREAD_ID: element, GROUP_ID: groupId},
                success: function (data) {

                    var dialogname = $('#thread-add-dialog');
                    var $dialogname = 'thread-add-dialog';
                    dialogname.empty().html(data);
                    dialogname.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: thread_edit,
                        width: 900,
                        height: 350,
                        modal: true,
                        close: function () {
                            dialogname.empty().dialog('close');
                        },
                        buttons: [
                            {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm', click: function () {
                                    $(editThreadFormID).validate({errorPlacement: function () {
                                        }});
                                    if ($(editThreadFormID).valid()) {
                                        $(editThreadFormID).ajaxSubmit({
                                            dataType: 'json',
                                            beforeSend: function () {
                                                Core.blockUI({animate: true});
                                            },
                                            success: function (data) {
                                                Core.unblockUI();
                                                dialogname.dialog('close');
                                                $('#' + element).find('img').attr('src', data.picture);
//                                                location.reload();

                                            },
                                            error: function () {
                                                Core.unblockUI();
                                                alert("Error");
                                            }
                                        });
                                    }
                                }},
                            {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
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
                width: 350,
                height: 'auto',
                modal: true,
                buttons: [
                    {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                            $.ajax({
                                data: {THREAD_ID: element},
                                url: "school/deleteThread",
                                dataType: "json",
                                type: "POST",
                                success: function (data) {
                                    if (data.status == 'success') {
                                        $('#' + element).hide();
//                                        console.log(element, type, 'deleted ' + data.status);
//                                        location.reload();
                                    } else {
                                        //-todo?
                                    }
                                },
                                error: function (jqXHR, exception) {
                                }
                            });
                            $("#dialog-confirm").dialog('close');
                        }},
                    {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $("#dialog-confirm").dialog('close');
                        }}
                ]
            });
            $("#dialog-confirm").html(plang.get('msg_delete_confirm')).dialog('open');
        }
    };

    var requestAction = function (element, type) {
        if (parseInt(type) === 1) {
            $.ajax({
                data: {GROUP_MEMBER_ID: element},
                url: "school/confirmRequest",
                dataType: "json",
                type: "POST",
                success: function (data) {
                    if (data.status == 'success') {
                        $('#searchGroupMemberResult').children().append($('li#' + element + '.media').parent().html());
                        $('li#' + element + '.media').find('.request-btn-sm').remove();
                        $('#requestGroupList').find('li#' + element + '.media').hide();
                    }
                },
                error: function (jqXHR, exception) {
                }
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
                buttons: [
                    {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                            $.ajax({
                                data: {GROUP_MEMBER_ID: element},
                                url: "school/deleteRequest",
                                dataType: "json",
                                type: "POST",
                                success: function (data) {
                                    if (data.status == 'success') {
                                        console.log(element, type, 'deleted ' + data.status);
                                        $('li#' + element + '.media').hide();
                                    } else {
                                        //todo?
                                    }
                                },
                                error: function (jqXHR, exception) {
                                }
                            });
                            $("#dialog-confirm").dialog('close');
                        }},
                    {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $("#dialog-confirm").dialog('close');
                        }}
                ]
            });
            $("#dialog-confirm").html(plang.get('msg_delete_confirm')).dialog('open');
        }
    };
    var getCommentAction = function (element, type) {
        if (parseInt(type) == 0) {
            $.ajax({
                data: {THREAD_ID: element},
                url: "school/groupComment",
                dataType: "html",
                type: "POST",
                success: function (data) {
                    $('#contentData').empty();
                    $('#contentData').html(data);
                },
                error: function (jqXHR, exception) {
                }
            });
        } else if (parseInt(type) == 1) {
            $.ajax({
                data: {THREAD_ID: element},
                url: "school/viewThread",
                dataType: "json",
                type: "POST",
                success: function (data) {
                    $.ajax({
                        data: {THREAD_ID: element},
                        url: "school/groupComment",
                        dataType: "html",
                        type: "POST",
                        success: function (data) {
                            $('#contentData').empty();
                            $('#contentData').html(data);
                        },
                        error: function (jqXHR, exception) {
                        }
                    });
                },
                error: function (jqXHR, exception) {
                }
            });
        }
    };
    var commentAction = function (element, type) {
        if (parseInt(type) === 1) {
            $.ajax({
                url: "school/editComment/",
                dataType: "html",
                type: "POST",
                data: {THREAD_REPLY_ID: element},
                success: function (data) {
                    alert(data);
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
                buttons: [
                    {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                            $.ajax({
                                data: {THREAD_REPLY_ID: element},
                                url: "group/deleteComment",
                                dataType: "json",
                                type: "POST",
                                success: function (data) {
                                    if (data.status == 'success') {
                                        console.log(element, type, 'deleted ' + data.status);
                                        getCommentAction($('#thread_id').val(), 0);
                                    } else {
                                        console.log(element, type, 'deleted ' + data.status);
                                        getCommentAction($('#thread_id').val(), 0);
                                    }
                                },
                                error: function (jqXHR, exception) {
                                    getCommentAction($('#thread_id').val());
                                }
                            });
                            $("#dialog-confirm").dialog('close');
                        }},
                    {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $("#dialog-confirm").dialog('close');
                        }}
                ]
            });
            $("#dialog-confirm").html(plang.get('msg_delete_confirm')).dialog('open');
        }
    };
    var autoComplete = function (type, el, thisVal) {
        var html = '', id = 0;
        if (type === 'exist') {
            $.ajax({
                type: 'post',
                url: 'school/searchMember/',
                dataType: 'json',
                data: {term: thisVal, GROUP_ID: $('#group_id').val()},
                success: function (data) {
                    html = '<ul class="media-list">';
                    $.each(data, function (i, item) {
                        console.log(item);
                        html += '<li class="media">'
                                + '<div class="col-md-9"><a class="pull-left" href="#">'
                                + '<img  class="group-memberpic img-circle dataview-list-icon" src="assets/core/admin/layout4/img/avatar.png" onerror="onUserImgError(this);" width="27px" height="27px"  style = "margin-right: 5px;"></a>'
                                + '<div><span>';
                        if (item.ROLE_NAME != 'STUDENT') {
                            id = item.USER_ID;
                            html += item.USERNAME + ' ( ' + item.ROLE_NAME + ' )';
                        } else {
                            id = item.MEMBER_USER_ID;
                            html += item.USERNAME + ' ( ' + student + ' - ' + item.MEMBER_USER_ID + ' )';
                        }
                        html += '</span></div><p> ' + humanized_time_span(item.CREATED_DATE, $.now()) + '</p></div>';
                        html += '<div class="col-md-3">'
                                + '<a href="javascript:;" onclick="actionRequest(' + id + ',0)" class="btn btn-icon-only btn-circle red request-btn-sm">'
                                + '<i class="fa fa-times"></i>'
                                + '</a></div></li>';
                    });
                    html += '</ul>';
                    $('#searchGroupMemberResult').html(html);
                }
            });
        } else if (type === 'new') {
            $.ajax({
                type: 'post',
                url: 'school/searchNewMember/',
                dataType: 'json',
                data: {term: thisVal, GROUP_ID: $('#group_id').val()},
                success: function (data) {
                    html = '<ul class="media-list mt5" style="padding-left: 20px;">';
                    $.each(data, function (i, item) {
                        html += '<li class="">'
                                + '<img  class="group-memberpic img-circle dataview-list-icon" src="assets/core/admin/layout4/img/avatar.png" onerror="onUserImgError(this);" width="27px" height="27px"  style = "margin-right: 5px;">'
                                + ' <a href="javascript:;" onclick="actionNewMember(' + item.USER_ID + ',' + $('#group_id').val() + ')">';
                        if (item.ROLE_NAME != 'STUDENT') {
                            html += item.USERNAME + ' ( ' + item.ROLE_NAME + ' )';
                        } else {
                            html += item.USERNAME + ' ( ' + student + ' - ' + item.USER_ID + ' )';
                        }
                        html += '</a></li>';
                    });
                    html += '</ul>';
                    $('#newUsers').html(html);
                }
            });
        }
    };
    var newMemberAction = function (id, groupId) {
        var $dialogname = 'dialog-confirm';
        $("#dialog-confirm").dialog({
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: 'Баталгаажуулах',
            width: 400,
            height: 'auto',
            modal: true,
            buttons: [
                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                        $.ajax({
                            data: {MEMBER_ID: id, GROUP_ID: groupId},
                            url: "school/addNewMember",
                            dataType: "json",
                            type: "POST",
                            beforeSend: function () {
                                Core.blockUI({animate: true});
                            },
                            success: function (data) {
                                Core.unblockUI();
                                var text = '<li id=' + data.result.USER_ID + ' class="media">' +
                                        '<div class="col-md-9" style="padding-left: 0px"><a class="pull-left" href="#">' +
                                        '<img class="group-memberpic img-circle dataview-list-icon" src="portal/assets/core/global/img/avatar.png" onerror="onUserImgError(this);" width="30px" height="35px" style = "margin-right: 5px;">' +
                                        '</a>' +
                                        '<span>' + data.result.USERNAME + ' (' + data.result.ROLE_NAME + ')' +
                                        '</span> <p>' +
                                        ' </div><p>' +
                                        ' </p></div><div class="col-md-3">' +
//                                        '<a href="javascript:;" onclick="actionRequest(' + data.result.USER_ID + ',0)" class="btn btn-icon-only btn-circle red request-btn-sm">' +
//                                        '<i class="fa fa-times"></i></a>' +
                                        '</div></li>';
                                $('#searchGroupMemberResult').children().append(text);
                            },
                            error: function (jqXHR, exception) {
                            }
                        });
                        $("#dialog-confirm").dialog('close');
                    }},
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                        $("#dialog-confirm").dialog('close');
                    }}
            ]
        });
        $("#dialog-confirm").html(plang.get('msg_new_member_add')).dialog('open');

    };
    var memberAction = function (element) {
        var $dialogname = 'dialog-confirm';
        $("#dialog-confirm").dialog({
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: plang.get('msg_title_confirm'),
            width: 400,
            height: 'auto',
            modal: true,
            buttons: [
                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                        $.ajax({
                            data: {GROUP_MEMBER_ID: element},
                            url: "school/deleteMember",
                            dataType: "json",
                            type: "POST",
                            success: function (data) {
                                $('li#' + element + '.media').hide();
                            },
                            error: function (jqXHR, exception) {
//                                todo?
                            }
                        });
                        $("#dialog-confirm").dialog('close');
                    }},
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                        $("#dialog-confirm").dialog('close');
                    }}
            ]
        });
        $("#dialog-confirm").html(plang.get('msg_delete_confirm')).dialog('open');
    };
    function humanized_time_span(date, ref_date, date_formats, time_units) {
        //Date Formats must be be ordered smallest -> largest and must end in a format with ceiling of null
        date_formats = date_formats || {
            past: [
                {ceiling: 60, text: "$seconds seconds ago"},
                {ceiling: 3600, text: "$minutes minutes ago"},
                {ceiling: 86400, text: "$hours hours ago"},
                {ceiling: 2629744, text: "$days days ago"},
                {ceiling: 31556926, text: "$months months ago"},
                {ceiling: null, text: "$years years ago"}
            ]
        };
//Time units must be be ordered largest -> smallest
        time_units = time_units || [
            [31556926, 'years'],
            [2629744, 'months'],
            [86400, 'days'],
            [3600, 'hours'],
            [60, 'minutes'],
            [1, 'seconds']
        ];

        date = new Date(date);
        ref_date = ref_date ? new Date(ref_date) : new Date();
        var seconds_difference = (ref_date - date) / 1000;

        var tense = 'past';

        function get_format() {
            for (var i = 0; i < date_formats[tense].length; i++) {
                if (date_formats[tense][i].ceiling == null || seconds_difference <= date_formats[tense][i].ceiling) {
                    return date_formats[tense][i];
                }
            }
            return null;
        }

        function get_time_breakdown() {
            var seconds = seconds_difference;
            var breakdown = {};
            for (var i = 0; i < time_units.length; i++) {
                var occurences_of_unit = Math.floor(seconds / time_units[i][0]);
                seconds = seconds - (time_units[i][0] * occurences_of_unit);
                breakdown[time_units[i][1]] = occurences_of_unit;
            }
            return breakdown;
        }

        function render_date(date_format) {
            var breakdown = get_time_breakdown();
            var time_ago_text = date_format.text.replace(/\$(\w+)/g, function () {
                return breakdown[arguments[1]];
            });
            return depluralize_time_ago_text(time_ago_text, breakdown);
        }

        function depluralize_time_ago_text(time_ago_text, breakdown) {
            for (var i in breakdown) {
                if (breakdown[i] == 1) {
                    var regexp = new RegExp("\\b" + i + "\\b");
                    time_ago_text = time_ago_text.replace(regexp, function () {
                        return arguments[0].replace(/s\b/g, '');
                    });
                }
            }
            return time_ago_text;
        }

        return render_date(get_format());
    }
    return {
        init: function () {
            initEvent();
        },
        fillindexList: function () {
            fillindexList();
        },
        threadAction: function (element, type, groupId) {
            threadAction(element, type, groupId);
        },
        requestAction: function (element, type) {
            requestAction(element, type);
        },
        getCommentAction: function (element, type) {
            getCommentAction(element, type);
        },
        commentAction: function (element, type) {
            commentAction(element, type);
        },
        newMemberAction: function (id, groupId) {
            newMemberAction(id, groupId);
        },
        memberAction: function (element) {
            memberAction(element);
        }
    };
}();