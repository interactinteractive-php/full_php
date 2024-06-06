var socialJs = true;
$(function () {

    $('time.timeago').timeago();

    $('.scl-fancybox:not([data-fancybox])').each(function () {
        var $this = $(this);

        $this.fancybox({
            href: $this.attr('data-src'),
            prevEffect: 'none',
            nextEffect: 'none',
            titlePosition: 'over',
            closeBtn: true,
            helpers: {
                overlay: {
                    locked: false
                }
            }
        });

        $this.attr('data-fancybox', '1');
    });

    $(window).scroll(function () {

        var lastPage = Number($('.load-more').attr('data-last-page'));
        
        if (($(window).scrollTop() == $(document).height() - $(window).height()) && (lastPage != 0)) {

            if ($('.scl-group-header-cover').length) {
                var postData = {page: lastPage + 1, groupId: $('.scl-group-header-cover').attr('data-group-id')};
            } else {
                var postData = {page: lastPage + 1};
            }

            $.ajax({
                type: 'post',
                url: 'social/posts',
                data: postData,
                beforeSend: function () {
                    $('.load-more').show();
                },
                success: function (html) {

                    var $posts = $('.timeline-posts');

                    $('.load-more').remove();

                    $posts.append(html);
                    $posts.find('time.timeago').timeago();

                    $posts.find('.scl-fancybox:not([data-fancybox])').each(function () {
                        var $this = $(this);

                        $this.fancybox({
                            href: $this.attr('data-src'),
                            prevEffect: 'none',
                            nextEffect: 'none',
                            titlePosition: 'over',
                            closeBtn: true,
                            helpers: {
                                overlay: {
                                    locked: false
                                }
                            }
                        });

                        $this.attr('data-fancybox', '1');
                    });
                }
            });
        }
    });

    $(document.body).on('click', '.post-button', function () {

        var $form = $('.create-post-form');

        $form.validate({errorPlacement: function () {}});

        if ($form.valid()) {
            $form.ajaxSubmit({
                type: 'post',
                url: 'social/createPost',
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function (data) {

                    PNotify.removeAll();

                    if (data.status == 'success') {

                        $('textarea#createPost, #youtubeText').val('');

                        var $posts = $('.timeline-posts');

                        $posts.prepend(data.post);
                        $posts.find('.panel-post:eq(0)').find('time.timeago').timeago();

                        $posts.find('.panel-post:eq(0)').find('.scl-fancybox:not([data-fancybox])').each(function () {
                            var $this = $(this);

                            $this.fancybox({
                                href: $this.attr('data-src'),
                                prevEffect: 'none',
                                nextEffect: 'none',
                                titlePosition: 'over',
                                closeBtn: true,
                                helpers: {
                                    overlay: {
                                        locked: false
                                    }
                                }
                            });

                            $this.attr('data-fancybox', '1');
                        });

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
    });

    $(document.body).on('keydown', 'input.post-comment:not(disabled, readonly)', function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code === 13) {
            var $this = $(this);
            var $form = $this.closest('form');

            $form.validate({errorPlacement: function () {}});

            if ($form.valid()) {
                $form.ajaxSubmit({
                    type: 'post',
                    url: 'social/postComment',
                    dataType: 'json',
                    beforeSubmit: function (formData, jqForm, options) {
                        formData.push(
                                {name: 'postId', value: $this.attr('data-post-id')}
                        );
                    },
                    beforeSend: function () {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function (data) {

                        PNotify.removeAll();

                        if (data.status == 'success') {

                            var $panel = $this.closest('.panel-post');
                            var $commentWrap = $panel.find('.post-comments-list');

                            $panel.find('.comment-count').text(data.commentCount);
                            $commentWrap.empty().append(data.comments);
                            $commentWrap.find('time.timeago').timeago();

                            $this.val('');
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
        }
    });

    $(document.body).on('click', '.show-comments', function () {
        var $this = $(this);
        var $panel = $this.closest('.panel-post');
        $panel.find('.post-comment').focus();
    });

    $(document.body).on('click', '.delete_comment', function () {
        var $this = $(this);
        var commentId = $this.attr('data-comment-id');
        var postId = $this.attr('data-post-id');
        var $dialogName = 'dialog-deleteeconfirm';

        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);

        $.ajax({
            type: 'post',
            url: 'social/deleteConfirm',
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 350,
                    height: 'auto',
                    modal: true,
                    buttons: [
                        {text: data.yes_btn, class: 'btn green-meadow btn-sm', click: function () {

                                $.ajax({
                                    type: 'post',
                                    url: 'social/deleteComment',
                                    data: {postId: postId, commentId: commentId},
                                    dataType: 'json',
                                    beforeSend: function () {
                                        Core.blockUI({
                                            message: 'Loading...',
                                            boxed: true
                                        });
                                    },
                                    success: function (dataSub) {

                                        if (dataSub.status == 'success') {

                                            var $panel = $this.closest('.panel-post');
                                            var $commentWrap = $panel.find('.post-comments-list');

                                            $panel.find('.comment-count').text(dataSub.commentCount);
                                            $commentWrap.empty().append(dataSub.comments);
                                            $commentWrap.find('time.timeago').timeago();

                                            $dialog.dialog('close');

                                        } else {
                                            PNotify.removeAll();
                                            new PNotify({
                                                title: dataSub.status,
                                                text: dataSub.message,
                                                type: dataSub.status,
                                                sticker: false
                                            });
                                        }
                                        Core.unblockUI();
                                    },
                                    error: function () {
                                        alert("Error");
                                    }
                                });
                            }},
                        {text: data.no_btn, class: 'btn blue-madison btn-sm', click: function () {
                                $dialog.dialog('close');
                            }}
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    });

    $(document.body).on('click', '.post_actions', function () {
        var $this = $(this);
        var postId = $this.attr('data-post-id');
        var $parent = $this.closest('.dropdown').find('.dropdown-menu');
        $parent.empty().append('<li class="main-link text-center"><i class="fa fa-circle-o-notch fa-spin"></i></li>');

        setTimeout(function () {
            $.ajax({
                type: 'post',
                url: 'social/postActions',
                data: {postId: postId},
                dataType: 'json',
                success: function (data) {

                    if (data.status == 'success') {
                        $parent.empty().append(data.html);
                    }

                    Core.unblockUI();
                },
                error: function () {
                    alert("Error");
                }
            });
        }, 40);
    });

    $(document.body).on('click', '.delete-post', function () {
        var $this = $(this);
        var postId = $this.attr('data-post-id');
        var $dialogName = 'dialog-deleteconfirm';

        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);

        $.ajax({
            type: 'post',
            url: 'social/deleteConfirm',
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 350,
                    height: 'auto',
                    modal: true,
                    buttons: [
                        {text: data.yes_btn, class: 'btn green-meadow btn-sm', click: function () {

                                $.ajax({
                                    type: 'post',
                                    url: 'social/deletePost',
                                    data: {postId: postId},
                                    dataType: 'json',
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

                                            $this.closest('.panel-post').remove();
                                            $dialog.dialog('close');
                                        }

                                        Core.unblockUI();
                                    },
                                    error: function () {
                                        alert("Error");
                                    }
                                });
                            }},
                        {text: data.no_btn, class: 'btn blue-madison btn-sm', click: function () {
                                $dialog.dialog('close');
                            }}
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    });

    $(document.body).on('click', '.like-post', function () {
        var $this = $(this);
        var postId = $this.attr('data-post-id');
        var likeId = $this.attr('data-like-id');

        if (likeId != '') {
            $this.html('<i class="fa fa-thumbs-o-up"></i> Like');
            $this.attr('data-like-id', '');

            $.ajax({
                type: 'post',
                url: 'social/saveLike',
                data: {postId: postId, likeId: likeId},
                dataType: 'json',
                success: function (data) {
                    $this.closest('ul').find('.like-count').text(data.count);
                }
            });

        } else {
            $this.html('<i class="fa fa-thumbs-o-down"></i> Unlike');

            $.ajax({
                type: 'post',
                url: 'social/saveLike',
                data: {postId: postId},
                dataType: 'json',
                success: function (data) {
                    $this.attr('data-like-id', data.likeId);
                    $this.closest('ul').find('.like-count').text(data.count);
                }
            });
        }
    });

    $(document.body).on('click', '.like-people', function () {
        var $this = $(this);
        var postId = $this.attr('data-post-id');

        var $dialogName = 'dialog-likepeople';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);

        $dialog.dialog({
            dialogClass: 'altn-custom-dialog',
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            width: 420,
            height: 'auto',
            maxHeight: 600,
            modal: true,
            open: function () {
                $.ajax({
                    type: 'post',
                    url: 'social/likePeople',
                    data: {postId: postId},
                    dataType: 'json',
                    beforeSend: function () {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function (data) {
                        $dialog.dialog('option', 'title', data.count + ' like');
                        $dialog.empty().append(data.html);
                        $dialog.dialog(
                                "option",
                                "position",
                                {my: "center", at: "center", of: window}
                        );
                        $dialog.find('.timeago').timeago();
                    },
                    error: function () {
                        alert("Error");
                    }
                });
            },
            close: function () {
                $dialog.empty().dialog('destroy').remove();
            }
        });
        $dialog.dialog('open');
        Core.unblockUI();
    });

    $('#isCommentLike').on('click', function (e) {
        e.preventDefault();
        $('.commentlike-addon').slideToggle('fast');
    });

    $('.create-post-form').ajaxForm({
        url: 'social/createPost',
        beforeSubmit: function validate(formData, jqForm, options) {
            var form = jqForm[0];
            var hasFile = false;

            for (var i = 0; i <= validFiles.length; i++) {
                if (validFiles[i] != null) {
                    hasFile = true
                    var file = new File([validFiles[i]], validFiles[i].name, {type: validFiles[i].type});
                    formData.push({name: 'post_images_upload_modified[]', value: file})
                }
            }

            validFiles = [];

            if (!hasFile && !$('.post-video-upload').val() && !form.description.value && !form.youtube_video_id.value && !form.location.value) {
                alert("Your post cannot be empty!")
                return false;
            }
        },
        beforeSend: function () {
            var $create_post_form = $('.create-post-form');
            var $create_post_button = $create_post_form.find('.btn-submit');
            $create_post_button.attr('disabled', true).append('<i class="fa fa-spinner fa-pulse"></i>');
            $create_post_form.find('.post-message').fadeOut('fast');
        },
        success: function (responseText) {
            $create_post_button.attr('disabled', false).find('.fa-spinner').addClass('hidden');

            if (responseText.status == 201) {
                notify(responseText.message, 'warning');
            }

            if (responseText.status == 200) {
                $('.timeline-posts').prepend(responseText.data.original);
                jQuery("time.timeago").timeago();
                $('.no-posts').hide();
                $('.video-addon').hide();
                create_post_form.find("input[type=text], textarea, input[type=file]").val("");
                create_post_form.find('.youtube-iframe').empty();
                create_post_form.find('#post-image-holder').empty();
                create_post_form.find('.post-images-selected').hide();
                create_post_form.find('#post-video-holder').empty();
                create_post_form.find('.post-videos-selected').hide();
                $('[name="youtube_video_id"]').val('');
                $('[name="youtube_title"]').val('');
                $('.post-description').linkify();
                $('[name="description"]').focus();
                notify('Your post has been successfully published');

            } else {
                $('.login-errors').html(responseText.message);
            }
        }
    });

    $(document.body).on('click', '.remove-thumb', function (e) {
        e.preventDefault()
        var count = 0, $this = $(this), key = $this.data('id');
        validFiles[key] = null;
        $this.parent('.pip').remove();

        $.each(validFiles, function (key, val) {
            if (val != null) {
                count++;
            }
        });

        $('.post-images-selected').find('span').text(count);
    });

    $('#youtubeText').bind('input propertychange', function () {
        var $this = $(this);
        var $video_addon = $this.closest('.video-addon');

        /*$video_addon.find('.fa-film').addClass('fa-spinner fa-spin');
        $this.closest('.video-addon').find('.fa-film').addClass('fa-spinner fa-spin');
        
        $.post('social/getYoutubeVideo', {youtube_source: $('#youtubeText').val()}).done(function (data) {
            if (data.status == 200) {
                $('.youtube-iframe').html(data.message.iframe);
                $('[name="youtube_video_id"]').val(data.message.id);
                $('[name="youtube_title"]').val(data.message.title);
                $video_addon.find('.fa-film').removeClass('fa-spinner fa-spin');
            }
        });*/
    });

    $(document.body).on('click', '.post-save-item', function () {
        var $this = $(this);
        var postId = $this.attr('data-post-id');

        $.ajax({
            type: 'post',
            url: 'social/saveItem',
            data: {postId: postId},
            dataType: 'json',
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

                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    });

    $(document.body).on('click', '.post-unsave-item', function () {
        var $this = $(this);
        var postId = $this.attr('data-post-id');

        $.ajax({
            type: 'post',
            url: 'social/unSaveItem',
            data: {postId: postId},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {

                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });

                if (data.status == 'success' && $this.hasAttr('data-mode') && $this.attr('data-mode') == 'remove') {
                    $this.closest('.scl-save-item').remove();
                }

                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    });

    $(document.body).on('click', '.scl-group-edit', function () {
        var $this = $(this);
        var groupId = $this.attr('data-group-id');
        var $dialogName = 'dialog-editgroup';

        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);

        $.ajax({
            type: 'post',
            url: 'social/editGroupForm',
            data: {groupId: groupId},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {

                if (data.status == 'success') {

                    $dialog.empty().append(data.html);
                    $dialog.dialog({
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.title,
                        width: 700,
                        height: 'auto',
                        modal: true,
                        position: {my: 'top', at: 'top+70'},
                        buttons: [
                            {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {

                                    var $form = $('#scl-edit-group');

                                    $form.validate({errorPlacement: function () {}});

                                    if ($form.valid()) {
                                        $form.submit();
                                    }
                                }},
                            {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                                    $dialog.dialog('close');
                                }}
                        ]
                    });
                    $dialog.dialog('open');
                }
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    });

    $(document.body).on('click', '.scl-group-addmember', function () {
        var $this = $(this);
        var groupId = $this.attr('data-group-id');
        var $dialogName = 'dialog-addmember';

        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);

        $.ajax({
            type: 'post',
            url: 'social/addMemberForm',
            data: {groupId: groupId},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {

                if (data.status == 'success') {

                    $dialog.empty().append(data.html);
                    $dialog.dialog({
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.title,
                        width: 500,
                        height: 'auto',
                        modal: true,
                        buttons: [
                            {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {

                                    var $form = $('#scl-add-member');

                                    if ($form.find('input[type="hidden"]').length) {
                                        $form.submit();
                                    } else {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: 'Info',
                                            text: 'Гишүүн сонгоно уу.',
                                            type: 'info',
                                            sticker: false
                                        });
                                    }
                                }},
                            {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                                    $dialog.dialog('close');
                                }}
                        ]
                    });
                    $dialog.dialog('open');
                }
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    });

    $(document.body).on('click', '.scl-group-delete', function () {

        var $this = $(this);
        var groupId = $this.attr('data-group-id');
        var $dialogName = 'dialog-deleteconfirm';

        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);

        $.ajax({
            type: 'post',
            url: 'social/deleteConfirm',
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 350,
                    height: 'auto',
                    modal: true,
                    buttons: [
                        {text: data.yes_btn, class: 'btn btn-danger btn-sm', click: function () {
                                window.location = 'social/deleteGroup/' + groupId;
                            }},
                        {text: data.no_btn, class: 'btn blue-madison btn-sm', click: function () {
                                $dialog.dialog('close');
                            }}
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            }
        });
    });

    $(document.body).on('click', '.scl-group-exit-member', function () {

        var $this = $(this);
        var groupId = $this.attr('data-group-id');
        var userId = $this.attr('data-user-id');
        var $dialogName = 'dialog-deleteconfirm';

        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);

        $.ajax({
            type: 'post',
            url: 'social/deleteConfirm',
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 350,
                    height: 'auto',
                    modal: true,
                    buttons: [
                        {text: data.yes_btn, class: 'btn btn-danger btn-sm', click: function () {

                                $.ajax({
                                    type: 'post',
                                    url: 'social/removeFromGroup',
                                    data: {groupId: groupId, userId: userId},
                                    dataType: 'json',
                                    beforeSend: function () {
                                        Core.blockUI({
                                            message: 'Loading...',
                                            boxed: true
                                        });
                                    },
                                    success: function (data) {

                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });

                                        if (data.status == 'success') {
                                            $this.closest('.inline-items').remove();
                                            $dialog.dialog('close');
                                        }

                                        Core.unblockUI();
                                    }
                                });
                            }},
                        {text: data.no_btn, class: 'btn blue-madison btn-sm', click: function () {
                                $dialog.dialog('close');
                            }}
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            }
        });
    });

});

function createGroup(elem) {
    var $dialogName = 'dialog-creategroup';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    $.ajax({
        type: 'post',
        url: 'social/createGroupForm',
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function (data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 700,
                height: 'auto',
                modal: true,
                position: {my: 'top', at: 'top+70'},
                buttons: [
                    {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {

                            var $form = $('#scl-create-group');

                            $form.validate({errorPlacement: function () {}});

                            if ($form.valid()) {
                                $form.submit();
                            }
                        }},
                    {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    });
}