<?php 
$sessionUserId = Ue::sessionUserId();
$nuniqId = getUID();

if ($this->data) {
    foreach ($this->data as $key => $data) { ?>
        <li class="commentbody media">
            <div class="d-flex align-items-center p-2 w-100">
                <table class="table ">
                    <tbody>
                        <tr>
                            <td class="pl-0 pr-2 py-1" style="width: 150px;">
                                <div class="data-tooltip">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2">
                                            <img src="<?php echo $data['picture'] ?>" data-comment-dd="<?php echo $data['userid'] ?>" class="rounded-circle" width="40" height="40" onerror="onNCUserImgError(this);" >
                                        </div>
                                        <div style="width: 100px;">
                                            <a href="javascript:void(0);" id="created-user" class="text-default font-weight-bold letter-icon-title"><?php echo $data['name'] ?></a>
                                            <div id="positionname" class="desc text-blue" style="float: left; width: 100%; word-wrap: break-word; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo $data['positionname'] ?></div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="pl-2 pr-2" style="border-right: 1px dashed #e0e0e0; width: 100px;">
                                <div class="desc" id="created-date"><?php echo Date::format('Y-m-d', $data['createddate']) ?></div>
                                <span class="desc"><?php echo $data['posttime'] ?></span>
                            </td>
                            <td class="pl-4 pr-2">
                                <div class="media-chat-item line-height-normal" data-comment='<?php echo $data['commenttext'] ?>'><?php echo $data['commenttxt'] ?></div>
                                <div class="font-size-sm text-muted mt-1 d-flex flex-row align-items-center">
                                    <ul id="ul<?php echo $data['id'] ?>" data-comment-id="<?php echo $data['id'] ?>" data-comment-user="<?php echo $data['userid'] ?>" class="list-inline font-size-sm mb-0">
                                        <li class="list-inline-item mr-2">
                                            <a href="javascript:;" onclick="like_<?php echo $this->uniqId ?>('<?php echo $data['id'] ?>', '<?php echo $this->postId ?>', 1)" ><i class="icon-thumbs-up2" ></i></a>
                                            <a href="javascript:;" onclick="getLike_<?php echo $this->uniqId ?>('<?php echo $data['id'] ?>', '<?php echo $data['countcommentlike'] ?>', 1, 2, '#modal_default_show_like')">
                                                <span class="badge badge-flat badge-pill text-black"><?php echo $data['countcommentlike'] ?></span>
                                            </a>
                                        </li>
                                        <li class="list-inline-item mr-1">
                                            <a href="javascript:;" onclick="like_<?php echo $this->uniqId ?>('<?php echo $data['id'] ?>', '<?php echo $this->postId ?>', 2)"><i class="icon-thumbs-down2"></i></a>
                                            <a href="javascript:;" onclick="getLike_<?php echo $this->uniqId ?>('<?php echo $data['id'] ?>', '<?php echo $data['countcommentdislike'] ?>', 2, 2, '#modal_default_show_dislike')">
                                                <span class="badge badge-flat badge-pill text-black"><?php echo $data['countcommentdislike'] ?></span>
                                            </a>
                                        </li>
                                        <li class="list-inline-item"><a href="javascript:;" id="comment<?php echo $data['id'] ?>" data-reply-type="reply" data-comment-id="<?php echo $data['id'] ?>" data-comment-user="<?php echo $data['userid'] ?>" onclick="replyComment_<?php echo $this->uniqId ?>('<?php echo $data['id'] ?>', '0', this)"><i class="icon-undo2 mr-1"></i></a></li>   
                                    </ul>
                                </div>
                            </td>
                            <?php if ($data['userid'] == $sessionUserId) { ?>
                                <td class="pl-2 pr-2" style="width: 100px;">
                                    <ul class="list-inline m-0 pull-right">
                                        <li class="dropdown">
                                            <a href="javascript:;" class="dropdown-togle" data-toggle="dropdown" role="button" aria-expanded="false" data-post-id="1588317692770"><i class="fa fa-ellipsis-h"></i></a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="javascript:;" class="dropdown-item" onclick="editComment<?php echo $nuniqId ?>(this, '<?php echo $data['id'] ?>')"><i class="fa fa-edit"></i> <?php echo Lang::line('edit_btn') ?></a>
                                                </li>
                                                <li>
                                                    <a href="javascript:;" class="dropdown-item" onclick="deleteComment<?php echo $nuniqId ?>(this, '<?php echo $data['id'] ?>')"><i class="fa fa-trash"></i> <?php echo Lang::line('delete_btn') ?></a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </td>
                            <?php } ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </li>
        <div id="gg<?php echo $data['id'] ?>" class="replycomment-<?php echo $this->uniqId ?> ml60"></div>
        <?php if (isset($data['child'])) {
            foreach ($data['child'] as $rkey => $row) { ?>
            <li class="commentbody reply media">
                <div class="d-flex align-items-center p-2 w-100">
                    <table class="table ">
                        <tbody>
                            <tr>
                                <td class="pl-0 pr-2 py-1" style="width: 150px;">
                                    <div class="data-tooltip">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-2">
                                                <img src="<?php echo $row['picture'] ?>" data-comment-dd="<?php echo $row['userid'] ?>" class="rounded-circle" width="40" height="40" onerror="onNCUserImgError(this);" >
                                            </div>
                                            <div style="width: 100px;">
                                                <a href="javascript:void(0);" id="created-user" class="text-default font-weight-bold letter-icon-title"><?php echo $row['name'] ?></a>
                                                <div id="positionname" class="desc text-blue" style="float: left; width: 100%; word-wrap: break-word; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo $row['positionname'] ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="pl-2 pr-2" style="border-right: 1px dashed #e0e0e0; width: 100px;">
                                    <div class="desc" id="created-date"><?php echo Date::format('Y-m-d', $row['createddate']) ?></div>
                                    <span class="desc"><?php echo $row['posttime'] ?></span>
                                </td>
                                <td class="pl-4 pr-2">
                                    <div class="media-chat-item line-height-normal" data-comment='<?php echo $row['commenttext'] ?>'><?php echo $row['commenttxt'] ?></div>
                                    <div class="font-size-sm text-muted mt-1 d-flex flex-row align-items-center">
                                        <ul id="ul<?php echo $row['id'] ?>" data-comment-id="<?php echo $row['id'] ?>" data-comment-user="<?php echo $row['userid'] ?>" class="list-inline font-size-sm mb-0">
                                            <li class="list-inline-item mr-2">
                                                <a href="javascript:;" onclick="like_<?php echo $this->uniqId ?>('<?php echo $row['id'] ?>', '<?php echo $this->postId ?>', 1)" ><i class="icon-thumbs-up2" ></i></a>
                                                <a href="javascript:;" onclick="getLike_<?php echo $this->uniqId ?>('<?php echo $row['id'] ?>', '<?php echo $row['countcommentlike'] ?>', 1, 2, '#modal_default_show_like')">
                                                    <span class="badge badge-flat badge-pill text-black"><?php echo $row['countcommentlike'] ?></span>
                                                </a>
                                            </li>
                                            <li class="list-inline-item mr-1">
                                                <a href="javascript:;" onclick="like_<?php echo $this->uniqId ?>('<?php echo $row['id'] ?>', '<?php echo $this->postId ?>', 2)"><i class="icon-thumbs-down2"></i></a>
                                                <a href="javascript:;" onclick="getLike_<?php echo $this->uniqId ?>('<?php echo $row['id'] ?>', '<?php echo $row['countcommentdislike'] ?>', 2, 2, '#modal_default_show_dislike')">
                                                    <span class="badge badge-flat badge-pill text-black"><?php echo $row['countcommentdislike'] ?></span>
                                                </a>
                                            </li>
                                            <li class="list-inline-item"><a href="javascript:;" id="comment<?php echo $row['id'] ?>" data-reply-type="reply" data-comment-id="<?php echo $row['id'] ?>" data-comment-user="<?php echo $row['userid'] ?>" onclick="replyComment_<?php echo $this->uniqId ?>('<?php echo $row['id'] ?>', '0', this)"><i class="icon-undo2 mr-1"></i></a></li>   
                                        </ul>
                                    </div>
                                </td>
                                <?php if ($row['userid'] == $sessionUserId) { ?>
                                    <td class="pl-2 pr-2" style="width: 100px;">
                                        <ul class="list-inline m-0 pull-right">
                                            <li class="dropdown">
                                                <a href="javascript:;" class="dropdown-togle" data-toggle="dropdown" role="button" aria-expanded="false" data-post-id="<?php $data['id'] ?>"><i class="fa fa-ellipsis-h"></i></a>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="javascript:;" class="dropdown-item" onclick="editComment<?php echo $nuniqId ?>(this, '<?php echo $row['id'] ?>')"><i class="fa fa-edit"></i> <?php echo Lang::line('edit_btn') ?></a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;" class="dropdown-item" onclick="deleteComment<?php echo $nuniqId ?>(this, '<?php echo $row['id'] ?>')"><i class="fa fa-trash"></i> <?php echo Lang::line('delete_btn') ?></a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </td>
                                <?php } ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </li>
            <div id="gg<?php echo $row['id'] ?>" class="replycomment-<?php echo $this->uniqId ?> ml90"></div>
                <?php if (isset($row['child'])) {
                foreach ($row['child'] as $rrkey => $rowc) { ?>
                <li class="commentbody reply media ml60">
                    <div class="d-flex align-items-center p-2 w-100">
                        <table class="table ">
                            <tbody>
                                <tr>
                                    <td class="pl-0 pr-2 py-1" style="width: 150px;">
                                        <div class="data-tooltip">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-2">
                                                    <img src="<?php echo $rowc['picture'] ?>" data-comment-dd="<?php echo $rowc['userid'] ?>" class="rounded-circle" width="40" height="40" onerror="onNCUserImgError(this);" >
                                                </div>
                                                <div style="width: 100px;">
                                                    <a href="javascript:void(0);" id="created-user" class="text-default font-weight-bold letter-icon-title"><?php echo $rowc['name'] ?></a>
                                                    <div id="positionname" class="desc text-blue" style="float: left; width: 100%; word-wrap: break-word; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo $rowc['positionname'] ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl-2 pr-2" style="border-right: 1px dashed #e0e0e0; width: 100px;">
                                        <div class="desc" id="created-date"><?php echo Date::format('Y-m-d', $rowc['createddate']) ?></div>
                                        <span class="desc"><?php echo $rowc['posttime'] ?></span>
                                    </td>
                                    <td class="pl-4 pr-2">
                                        <div class="media-chat-item line-height-normal" data-comment='<?php echo $rowc['commenttext'] ?>'><?php echo $rowc['commenttxt'] ?></div>
                                        <div class="font-size-sm text-muted mt-1 d-flex flex-row align-items-center">
                                            <ul id="ul<?php echo $rowc['id'] ?>" data-comment-id="<?php echo $rowc['id'] ?>" data-comment-user="<?php echo $rowc['userid'] ?>" class="list-inline font-size-sm mb-0">
                                                <li class="list-inline-item mr-2">
                                                    <a href="javascript:;" onclick="like_<?php echo $this->uniqId ?>('<?php echo $rowc['id'] ?>', '<?php echo $this->postId ?>', 1)" ><i class="icon-thumbs-up2" ></i></a>
                                                    <a href="javascript:;" onclick="getLike_<?php echo $this->uniqId ?>('<?php echo $rowc['id'] ?>', '<?php echo $rowc['countcommentlike'] ?>', 1, 2, '#modal_default_show_like')">
                                                        <span class="badge badge-flat badge-pill text-black"><?php echo $rowc['countcommentlike'] ?></span>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item mr-1">
                                                    <a href="javascript:;" onclick="like_<?php echo $this->uniqId ?>('<?php echo $rowc['id'] ?>', '<?php echo $this->postId ?>', 2)"><i class="icon-thumbs-down2"></i></a>
                                                    <a href="javascript:;" onclick="getLike_<?php echo $this->uniqId ?>('<?php echo $rowc['id'] ?>', '<?php echo $rowc['countcommentdislike'] ?>', 2, 2, '#modal_default_show_dislike')">
                                                        <span class="badge badge-flat badge-pill text-black"><?php echo $rowc['countcommentdislike'] ?></span>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item"><a href="javascript:;" id="comment<?php echo $rowc['id'] ?>" data-reply-type="reply" data-comment-id="<?php echo $row['id'] ?>" data-comment-user="<?php echo $rowc['userid'] ?>" onclick="replyComment_<?php echo $this->uniqId ?>('<?php echo $rowc['id'] ?>', '0', this)"><i class="icon-undo2 mr-1"></i></a></li>   
                                            </ul>
                                        </div>
                                    </td>
                                    <?php if ($rowc['userid'] == $sessionUserId) { ?>
                                        <td class="pl-2 pr-2" style="width: 100px;">
                                            <ul class="list-inline m-0 pull-right">
                                                <li class="dropdown">
                                                    <a href="javascript:;" class="dropdown-togle" data-toggle="dropdown" role="button" aria-expanded="false" data-post-id="1588317692770"><i class="fa fa-ellipsis-h"></i></a>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="javascript:;" class="dropdown-item" onclick="editComment<?php echo $nuniqId ?>(this, '<?php echo $rowc['id'] ?>')"><i class="fa fa-edit"></i> <?php echo Lang::line('edit_btn') ?></a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:;" class="dropdown-item" onclick="deleteComment<?php echo $nuniqId ?>(this, '<?php echo $rowc['id'] ?>')"><i class="fa fa-trash"></i> <?php echo Lang::line('delete_btn') ?></a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </td>
                                    <?php } ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </li>
                <div id="gg<?php echo $rowc['id'] ?>" class="replycomment-<?php echo $this->uniqId ?>" style="margin-left: 120px;"></div>
            <?php }
            } ?>
        <?php }
        } ?>
        
<?php }
} ?>
                
<style type="text/css">
    .custom-style-text {
        border-radius: 0;
        border: 1px solid #e5e5e5;
        padding: 12px;
        width: 100%;
    }
    
    .custom-parent {
        border-radius: 0;
        background: none !important;
        padding: 0;
        width: 90%;
    }
    
    input.custom-style-text:focus {
        outline-offset: 0px;
        border-radius: 0;
    }
</style>
<script type="text/javascript">
    
    function deleteComment<?php echo $nuniqId ?> (element, commentId) {
        var $dialogConfirm = 'dialog-confirm-<?php echo $nuniqId ?>';
        if (!$("#" + $dialogConfirm).length) {
            $('<div id="' + $dialogConfirm + '"></div>').appendTo('body');
        }
        var $dialog = $("#" + $dialogConfirm);

        $dialog.empty().append('Устгахдаа итгэлтэй байна уу');
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
                    $.ajax({
                        url: 'government/deleteComment',
                        type: 'post',
                        data: {
                            commentId: commentId
                        },
                        dataType: 'JSON',
                        beforeSend: function () {
                            Core.blockUI({
                                message: 'Уншиж байна түр хүлээнэ үү...',
                                boxed: true
                            });
                        },
                        success: function (result) {
                            if (typeof result.status !== 'undefined' && result.status === 'success') {
                                $(element).closest('li.commentbody').remove();
                            }

                            Core.unblockUI();
                        },
                        error: function (jqXHR, exception) {
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

                            Core.unblockUI();
                        }
                    });
                    
                    $dialog.empty().dialog('close');
                }},
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');
        
    }
    
    function editComment<?php echo $nuniqId ?>(element, commentId) {
        var $this = $(element);
        $this.closest('ul').addClass('d-none');
        
        var $parent = $this.closest('table.table');
        var $html = $parent.find('.media-chat-item').attr('data-comment');
        $parent.find('.media-chat-item').html('<textarea name="commenttext" data-id="'+ commentId +'" class="form input-sm custom-style-text" >'+ unescape($html) +'</textarea>');
        $parent.find('.media-chat-item').addClass('custom-parent');
        
        $parent.find('textarea[data-id="'+ commentId +'"').keypress(function(e) {
            console.log(e, e.which);
            if (e.keyCode == 13 && !e.shiftKey) {
                var $this = $(this);
                var $dialogConfirm = 'dialog-confirm-<?php echo $nuniqId ?>';
                if (!$("#" + $dialogConfirm).length) {
                    $('<div id="' + $dialogConfirm + '"></div>').appendTo('body');
                }
                var $dialog = $("#" + $dialogConfirm);

                $dialog.empty().append('Хадгалахдаа итгэлтэй байна уу');
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
                            $.ajax({
                                url: 'government/saveComment',
                                type: 'post',
                                data: {
                                    commentId: $this.attr('data-id'),
                                    commentTxt: $this.val()
                                },
                                dataType: 'JSON',
                                beforeSend: function () {
                                    Core.blockUI({
                                        message: 'Уншиж байна түр хүлээнэ үү...',
                                        boxed: true
                                    });
                                },
                                success: function (result) {
                                    if (typeof result.status !== 'undefined' && result.status === 'success') {
                                        getComments_<?php echo $this->uniqId ?>('<?php echo $this->postId ?>');
                                    }

                                    Core.unblockUI();
                                },
                                error: function (jqXHR, exception) {
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

                                    Core.unblockUI();
                                }
                            });

                            $dialog.empty().dialog('close');
                        }},
                        {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
            }
            
        });
        
        $parent.find('textarea[data-id="'+ commentId +'"').keyup(function(e) {
            if (e.which == 27) {
                getComments_<?php echo $this->uniqId ?>('<?php echo $this->postId ?>');
            }
        });
    }
    
    
</script>