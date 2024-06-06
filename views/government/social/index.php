<?php $newuniqId = getUID(); ?>
<?php $commentdate = Date::currentDate(); ?>

<div class="<?php echo (isset($this->ajax) && $this->ajax) ? 'socialIntranet'. $this->uniqId .' sclIntranet_'. $newuniqId .' pl15 pr15 pt15' : 'container'; ?>">
    <div class="row">
        <main class="col <?php echo (isset($this->ajax) && $this->ajax) ? 'col-xl-9' : 'col-xl-6'; ?>  order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12 mainpost">
            <div class="row socialPin<?php echo $this->uniqId ?>">
                <?php if (isset($this->pinpostData['result'])) {
                    foreach ($this->pinpostData['result'] as $key => $row) { ?>
                        <div class="col">
                            <div class="ui-block">			
                                <div class="friend-item">
                                    <div class="friend-header-thumb">
                                        <img src="<?php echo isset($row['picture']) ? $row['picture'] : '' ?>" onerror="onNCUserImgError(this);">
                                    </div>
                                    <div class="friend-item-content"><?php echo $row['description'] ?></div>
                                </div>			
                            </div>
                        </div>
                    <?php }
                } ?>
            </div>
            
            <?php echo $this->createPost; ?>

            <div class="timeline-posts">
                <?php echo $this->posts; ?>
            </div>
            <?php if ($this->postsData) { ?>
                <a class="infinite-more-link" href="social/posts/2"></a>
                <div class="loadmore<?php echo $this->uniqId ?>" style="padding: 2px 10px;text-align: center;background: #FFF;">
                    <span class="d-block w-100" style="text-align: center; font-style: italic; padding-bottom: 8px; padding-top: 8px;">Showing recent results...</span>
                    <a class="w-100" href="javascript:;" ssonclick="panelscrolling<?php echo $this->uniqId ?>()" style="text-decoration: underline;">More</a>
                </div>
            <?php } else { ?>
                <div class="" style="padding: 2px 10px;text-align: center;background: #FFF;">
                    <span class="d-block w-100" style="text-align: center; font-style: italic; padding-bottom: 8px; padding-top: 8px;">no post</span>
                </div>
            <?php } ?>
        </main>

        <?php
            echo $this->mainLeft;
            echo $this->mainRight;
        ?>

    </div>
    <span class="d-none tmp<?php echo $this->uniqId ?>"></span>
</div>

<script type="text/javascript">
    
    var $socialIntranet_<?php echo $this->uniqId ?> = $('.sclIntranet_<?php echo $newuniqId ?>');
    
    jQuery(document).ready(function () {
        
        try {
            rtc.on('api_send_all_user', function (data) {
                
                switch (data.type) {
                    case 'postIntranetAllUser<?php echo issetParam($this->groupId) ? '_' . $this->groupId : ''; ?>':
                        $socialIntranet_<?php echo $this->uniqId ?>.find('.timeline-posts').prepend(data.Html).promise().done(function () {
                            $(this).find('.remove-actions').remove();
                            $socialIntranet_<?php echo $this->uniqId ?>.find('textarea.post-comment').keypress(function(e) {
                                if (e.keyCode == $.ui.keyCode.ENTER && !e.shiftKey) {
                                    createcomment_<?php echo $this->uniqId ?> (this);
                                }
                            });

                            $socialIntranet_<?php echo $this->uniqId ?>.find('textarea.post-comment').keyup(function(e) {
                                if (e.keyCode == 27) {
                                    $(this).val('');
                                }
                            });

                        });
                        break;
                        
                    case 'postAddLikeCnt<?php echo issetParam($this->groupId) ? '_' . $this->groupId : ''; ?>':
                        $socialIntranet_<?php echo $this->uniqId ?>.find('.footer-list').find('a[data-post-id="'+ data.postId + '"]').find('.like-count').empty().append(data.Html);
                        break;
                        
                    case 'commentAddLikeCnt<?php echo issetParam($this->groupId) ? '_' . $this->groupId : ''; ?>':
                        $socialIntranet_<?php echo $this->uniqId ?>.find('a.like-people[data-comment-id="'+ data.commentId +'"]').find('.like-count').empty().append(data.Html);
                        break;

                    case 'postAddComment<?php echo issetParam($this->groupId) ? '_' . $this->groupId : ''; ?>':
                        
                        $socialIntranet_<?php echo $this->uniqId ?>.find('div[id="post'+ data.postId +'"]').find('.post-comments-list').empty().append(data.Html).promise().done(function () {
                            $(this).find('.remove-actions').remove();
                        });
                        
                        break;
                        
                    case 'postDelete':
                        
                        $socialIntranet_<?php echo $this->uniqId ?>.find('div[id="post'+ data.postId +'"]').fadeOut();
                        
                        break;
                        
                    case 'postDeleteComment':
                        
                        $socialIntranet_<?php echo $this->uniqId ?>.find('ul[id="comment'+ data.commentId +'"]').fadeOut();
                        
                        break;
                        
                    default:

                        break;
                }
            });
        } catch (e) {
            console.log(e);
        }
        
        if (typeof socialJs === 'undefined') {
            return;
            $.getScript(URL_APP + 'assets/custom/css/social/js/social.js', function () {
                
            });
        }
        
        $socialIntranet_<?php echo $this->uniqId ?>.find('.scl-fancybox:not([data-fancybox])').each(function () {
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
        
    });
    
    $socialIntranet_<?php echo $this->uniqId ?>.find('textarea.post-comment').keypress(function(e) {
        if (e.keyCode == $.ui.keyCode.ENTER && !e.shiftKey) {
            createcomment_<?php echo $this->uniqId ?> (this)
        }
    });
    
    $socialIntranet_<?php echo $this->uniqId ?>.find('textarea.post-comment').keyup(function(e) {
        if (e.keyCode == 27) {
            $(this).val('');
        }
    });
    
    $socialIntranet_<?php echo $this->uniqId ?>.find('.sc-post-button').on('click', function () {
        var $parent = $(this).closest('.create-post-form');
        var $this = $parent.find('textarea[name="description"]');
        simplePost<?php echo $this->uniqId ?>($this.val(), $this);
    });
    
    $socialIntranet_<?php echo $this->uniqId ?>.find('textarea[name="description"]').keypress(function(e) {
        if (e.keyCode == 13 && !e.shiftKey) {
            e.preventDefault();
            var $this = $(this);
            
            var text = $this.val();
            
            if (text === '') {
                PNotify.removeAll();
                new PNotify({
                    title: 'Анхаар',
                    text: 'Нийтлэл бичих хэсэгт утга оруулна уу',
                    type: 'error',
                    sticker: false
                });
                return;
            }
            
            simplePost<?php echo $this->uniqId ?>(text, $this);
        }
    });
    
    $socialIntranet_<?php echo $this->uniqId ?>.find('.imageUpload').on('click', function () {
        
    });
    
    $(".socialIntranet<?php echo $this->uniqId ?>").find('[data-userid]').each(function (index, row) {
        $(row).qtip({
            content: {
                text: function(event1, api1) {
                    var response = $.ajax({
                        url: 'government/getUserData',
                        type: 'post',
                        data: {
                            userId: $(row).attr('data-userid')
                        },
                        dataType: 'JSON',
                        beforeSend: function () {},
                        success: function (data) {
                            var $data = (typeof data.result !== 'undefined') ? data.result : [];
                            var $html = '<div class="media-body d-flex align-items-center justify-content-center" style="padding: 8px;">';
                                    $html += '<div class="col-4 text-center border-right-1 border-gray mr-2">';
                                        $html += '<img src="'+ ((typeof $data.picture !== 'undefined' && $data.picture) ? $data.picture : 'assets/custom/addon/admin/layout4/img/user.png') +'" onerror="onUserImageError(this);" class="rounded-circle" style="width:50px;height:50px;">';
                                        $html += '<h6 class="text-blue font-weight-bold mt-1 mb-1 line-height-normal">'+ ((typeof $data.name !== 'undefined' && $data.name) ? $data.name : '') +'</h6>';
                                        $html += '<p class="text-blu mb-0 font-size-12 line-height-normal">'+ ((typeof $data.position !== 'undefined' && $data.position) ? $data.position : '') +'</p>';
                                    $html += '</div>';
                                    $html += '<div class="col-8">';
                                        $html += '<ul class="media-list font-size-13">';
                                            $html += '<li class="d-flex flex-row align-items-center">';
                                                $html += '<i class="icon-mail5 text-blue mr-1"></i>';
                                                $html += '<a href="mailto:">'+ ((typeof $data.employeeemail !== 'undefined' && $data.employeeemail) ? $data.employeeemail : '') +'</a>';
                                            $html += '</li>';
                                            $html += '<li class="d-flex flex-row align-items-center mt10">';
                                                $html += '<i class="icon-mobile text-blue mr-1"></i>';
                                                $html += '<span>'+ ((typeof $data.employeemobile !== 'undefined' && $data.employeemobile) ? $data.employeemobile : '') +'</span>';
                                            $html += '</li>';
                                            $html += '<li class="d-flex flex-row align-items-center mt10">';
                                                $html += '<i class="icon-location3 text-blue mr-1"></i>';
                                                $html += '<span class="line-height-normal">'+ ((typeof $data.departmentname !== 'undefined' && $data.departmentname) ? $data.departmentname: '') +'</span>';
                                            $html += '</li>';
                                        $html += '</ul>';
                                    $html += '</div>';
                                $html += '</div>';
                            $('body').find('#' + api1['_id']).find('.qtip-content').html($html);
                        }
                    });
                }
            },
            position:{
                effect:!1,
                at:"top left",
                my: "right top",
            },
            show:{effect:!1,delay:500},
            hide:{effect:!1,fixed:!0,delay:70},
            style:{classes:"qtip-bootstrap",width:450,tip:{width:12,height:7}}
        });
    });

    $socialIntranet_<?php echo $this->uniqId ?>.find('#videoUpload').on('click', function (e) {
        e.preventDefault();
        $('.video-addon').slideToggle('fast');
    });

    $socialIntranet_<?php echo $this->uniqId ?>.find('#fileUpload').on('click', function (e) {
        e.preventDefault();
        $('.file-addon').slideToggle('fast');
    });
    
    $socialIntranet_<?php echo $this->uniqId ?>.find('#imageUpload').on('click', function (e) {
        e.preventDefault();
        $('.post-images-upload').trigger('click');
    });

    $socialIntranet_<?php echo $this->uniqId ?>.find(document.body).on('change', '.post-images-upload', function (e) {
        e.preventDefault();

        var files = !!this.files ? this.files : [];
        $('.post-images-selected').find('span').text(files.length);
        $('.post-images-selected').show('slow');

        if (!files.length || !window.FileReader)
            return;

        var countFiles = $(this)[0].files.length;
        var imgPath = $(this)[0].value;
        var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
        var image_holder = $("#post-image-holder");
        image_holder.empty();

        if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
            if (typeof (FileReader) != "undefined") {
                validFiles = [];

                $.each(files, function (key, val) {
                    validFiles.push(files[key]);

                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var file = e.target;
                        $("<span class=\"pip\">" +
                                "<img class=\"thumb-image\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
                                "<a data-id=" + (key) + " class='remove-thumb'><i class='fa fa-times'></i></a>" +
                                "</span>").appendTo(image_holder);
                    }
                    image_holder.show();
                    reader.readAsDataURL(files[key]);
                });
            } else {
                alert("This browser does not support FileReader.");
            }
        } else {
            alert("Please select only images");
        }
    });

    $socialIntranet_<?php echo $this->uniqId ?>.find(document.body).on('change', '.post-video-upload', function (e) {
        e.preventDefault();
        var files = !!this.files ? this.files : [];

        if ((files[0].size / 1024) / 1024 < 100) {

            $('.post-video-selected').find('span').text(files[0]['name']);
            $('.post-video-selected').show('slow');
            if (!files.length || !window.FileReader)
                return;

        } else {
            $('.post-video-upload').val("");
            alert('file size is more than 100 MB');
        }
    });
    
    $socialIntranet_<?php echo $this->uniqId ?>.on('click', '.like-post', function () {
        var $this = $(this);
        var postId = $this.attr('data-post-id');
        var likeId = $this.attr('data-islike');

        if (likeId != '') {
            $this.html('<i class="ci-icon-like"></i> Like');
            $this.attr('data-like-id', '');
            
            $.ajax({
                type: 'post',
                url: 'government/saveLike/1',
                data: {
                    postId: postId, 
                    likeId: likeId, 
                    likeTypeId: '1', 
                    targetType: 'post'
                },
                dataType: 'json',
                success: function (data) {
                    $this.closest('ul').find('.like-count').text(data.count);
                    $this.attr('data-islike', '');
                    $this.attr('data-like-id', '');
                    
                    try {
                        var postType = 'postAddLikeCnt<?php echo issetParam($this->groupId) ? '_' . $this->groupId : ''; ?>';
                        rtc.apiSendAllUser({type: postType, Html: data.count, postId: postId});
                    } catch (e) {
                        console.log(e);
                    }
                }
            });

        } else {
            $this.html('<img src="assets/custom/img/ico/like.png" width="24" height="24"/> Unlike');

            $.ajax({
                type: 'post',
                url: 'government/saveLike/1',
                data: {
                    postId: postId, 
                    likeTypeId: '1', 
                    targetType: 'post'
                },
                dataType: 'json',
                success: function (data) {
                    $this.attr('data-like-id', data.likeId);
                    $this.attr('data-islike', '1');
                    $this.closest('ul').find('.like-count').text(data.count);
                    
                    try {
                        var postType = 'postAddLikeCnt<?php echo issetParam($this->groupId) ? '_' . $this->groupId : ''; ?>';
                        rtc.apiSendAllUser({type: postType, Html: data.count, postId: postId});
                    } catch (e) {
                        console.log(e);
                    }
                    
                }
            });
        }
    });
    
    $socialIntranet_<?php echo $this->uniqId ?>.on('click', '.comment-like', function () {
        likecomment_<?php echo $this->uniqId ?>(this);
    });

    $socialIntranet_<?php echo $this->uniqId ?>.on('click', '.like-people', function () {
        var $this = $(this);
        var postId = $this.attr('data-post-id');
        var commentId = $this.attr('data-comment-id');

        var $dialogName = 'dialog-likepeople';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);

        $dialog.dialog({
            dialogClass: '',
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
                    url: 'government/likePeople',
                    data: {postId: postId, commentId: commentId},
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

    $socialIntranet_<?php echo $this->uniqId ?>.on('click', '.comment-reply', function () {
        var $this = $(this), $parent = $this.closest('ul.list-inline');
        var postId = $parent.attr('data-post-id'),
            commentId = $parent.attr('data-comment-id');
            
        if ($parent.find('.comment-rr').length > 0) {
            return;
        }
        
        var $html = '';
        $html += '<div class="to-comment reply-comment comment-rr">';
            $html += '<div class="commenter-avatar">';
                $html += '<a href="javascript:;">';
                    $html += '<img src="<?php echo issetParam($this->userInfo['result']['userpicture']); ?>" data-userid="<?php echo issetParam($this->userInfo['result']['userid']); ?>" class="rounded-circle" width="40" height="40" onerror="onNCUserImgError(this);">';
                $html += '</a>';
            $html += '</div>';
            $html += '<div class="comment-textfield">';
                $html += '<div class="comment-holder">';
                    $html += '<textarea class="form-control reply-comment" autocomplete="off" data-post-id="'+ postId +'" data-comment-id="'+ commentId +'" name="post_comment" placeholder="Write a comment... Press Enter to Post" required="required" style="resize: none; border-radius: 10px;"></textarea>';
                    $html += '<input type="file" class="comment-images-upload hidden" accept="image/jpeg,image/png,image/gif" name="comment_images_upload">';
                    $html += '<ul class="list-inline meme-reply hidden">';
                        $html += '<li>';
                            $html += '<a href="javascript:;" id="imageComment">';
                                $html += '<i class="fa fa-camera" aria-hidden="true"></i>';
                            $html += '</a>';
                        $html += '</li>';
                    $html += '</ul>';
                $html += '</div>';
                    $html += '<div id="comment-image-holder"></div>';
            $html += '</div>';
            $html += '<div class="clearfix"></div>';
        $html += '</div>';
        
        $parent.append($html).promise().done(function () {
            
            $parent.find('textarea.reply-comment').keypress(function(e) {
                if (e.keyCode == $.ui.keyCode.ENTER && !e.shiftKey) {
                    var $this1 = $(this);
                    var text = $this1.val();
                    var postId = $this1.attr("data-post-id");

                    if (text === '') {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Анхаар',
                            text: 'Сэтгэгдэл бичих хэсэгт утга оруулна уу',
                            type: 'error',
                            sticker: false
                        });
                        return;
                    }

                    $.ajax({
                        url: 'government/saveIntanetComment',
                        dataType: 'JSON',
                        type: 'POST',
                        data: {
                            postId: postId,
                            commentType: 'reply',
                            commentText: text,
                            commentId: commentId,
                            uniqId: '<?php echo $this->uniqId ?>'
                        },
                        beforeSend: function () {
                            Core.blockUI({
                                message: 'Уншиж байна түр хүлээнэ үү...',
                                boxed: true
                            });
                        },
                        success: function (result) {
                            $parent.find('.reply-comment').remove();
                            
                            if (typeof result.html !== 'undefined' && result.html !== '') {
                                var $parent1 = $this.closest('.post-comments-list');
                                $parent1.empty().append(result.html).promise().done(function () {
                                    /*
                                    $socialIntranet_<?php echo $this->uniqId ?>.on('click', '.comment-like', function () {
                                        likecomment_<?php echo $this->uniqId ?>(this);
                                    });*/
                                });
                            } 
                            
                            try {
                                var postType = 'postAddComment<?php echo issetParam($this->groupId) ? '_' . $this->groupId : ''; ?>';
                                rtc.apiSendAllUser({type: postType, Html: result.html, postId: postId});
                            } catch (e) {
                                console.log(e);
                            }
                            
                            Core.unblockUI();
                            return;
                        }
                    });
                }
            });

            $parent.find('textarea.reply-comment').keyup(function(e) {
                if (e.keyCode == 27) {
                    $parent.find('.reply-comment').remove();
                    Core.unblockUI();
                    return;

                }
            });
            
        });
        
    });

    $socialIntranet_<?php echo $this->uniqId ?>.find('#isCommentLike').on('click', function (e) {
        e.preventDefault();
        $('.commentlike-addon').slideToggle('fast');
    });

    function simplePost<?php echo $this->uniqId ?>(text, $this) {
        $this.closest('form.create-post-form').ajaxSubmit({
            url: 'government/saveSinglePost',
            dataType: 'JSON',
            type: 'POST',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Уншиж байна түр хүлээнэ үү...',
                    boxed: true
                });
            },
            success: function (response) {
                if (response) {
                    $this.closest('form.create-post-form').find('input:not(.ignore)').val('');
                    $this.closest('form.create-post-form').find('.post-addon').hide();
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Амжилттай',
                        text: 'Амжилттай хадгалагдлаа',
                        type: 'success',
                        sticker: false
                    });

                    $this.val('');
                    
                    try {
                        var postType = 'postIntranetAllUser<?php echo issetParam($this->groupId) ? '_' . $this->groupId : ''; ?>';
                        rtc.apiSendAllUser({type: postType, data: response.result, Html: response.Html});
                    } catch (e) {
                        console.log(e);
                    }
                    
                    $socialIntranet_<?php echo $this->uniqId ?>.find('.timeline-posts').prepend(response.Html).promise().done(function () {

                        $socialIntranet_<?php echo $this->uniqId ?>.find('textarea.post-comment').keypress(function(e) {
                            if (e.keyCode == $.ui.keyCode.ENTER && !e.shiftKey) {
                                createcomment_<?php echo $this->uniqId ?> (this);
                            }
                        });

                        $socialIntranet_<?php echo $this->uniqId ?>.find('textarea.post-comment').keyup(function(e) {
                            if (e.keyCode == 27) {
                                $(this).val('');
                            }
                        });
                        
                    });
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
    
    function parseHTML<?php echo $this->uniqId ?>(text) {
        $socialIntranet_<?php echo $this->uniqId ?>.find('.tmp<?php echo $this->uniqId ?>').empty().append(text);
        return $socialIntranet_<?php echo $this->uniqId ?>.find('.tmp<?php echo $this->uniqId ?>')[0].innerText;
    }

    function editComment_<?php echo $this->uniqId ?>(element) {
        var $this = $(element);
        var $parent = $this.closest('.commenter');
        var $rowData = $parent.data('rowdata');
        
        if (typeof $rowData !== 'object') {
            $rowData = JSON.parse($rowData);
        }
        
        var commentId = $rowData.id;
        var comment = $parent.find('span.comment-description').html();
        
        var $html ='<textarea class="form-control post-comment" autocomplete="off" data-commentid="'+ commentId +'" data-post-id="'+ $rowData.postid +'" name="post_comment" placeholder="Write a comment... Press Enter to Post" required="required" style="resize: none; border-radius: 10px;">'+ comment +'</textarea>';
        
        $parent.empty().append($html).promise().done(function () {
            
            $parent.find('textarea.post-comment').keypress(function(e) {
                if (e.keyCode == $.ui.keyCode.ENTER && !e.shiftKey) {
                    createcomment_<?php echo $this->uniqId ?>(this);
                }
            });

            $parent.find('textarea.post-comment').keyup(function(e) {
                if (e.keyCode == 27) {
                    $(this).val('');
                }
            });
            
        });
    }
    
    function deleteComment_<?php echo $this->uniqId ?>(element) {
        var $this = $(element);
        var $commentId = $this.attr('data-comment-id');
        $.ajax({
            url: 'government/deleteComment',
            dataType: 'JSON',
            type: 'POST',
            data: {
                commentId: $commentId,
            },
            beforeSend: function () {
                Core.blockUI({
                    message: 'Уншиж байна түр хүлээнэ үү...',
                    boxed: true
                });
            },
            success: function (response) {
                if (response.status) {

                    PNotify.removeAll();
                    new PNotify({
                        title: 'Амжилттай',
                        text: 'Амжилттай устгагдлаа',
                        type: 'success',
                        sticker: false
                    });

                    $this.closest('ul[id="comment'+ $commentId +'"]').fadeOut();
                    
                    try {
                        rtc.apiSendAllUser({type: 'postDeleteComment', commentId: $commentId});
                    } catch (e) {
                        console.log(e);
                    }
                    
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
    
    function editSocialPost_<?php echo $this->uniqId ?>(element) {
        var $this = $(element);
        
        var $modalId_<?php echo $this->uniqId ?> = 'modal-intranet<?php echo $this->uniqId ?>';

        $.ajax({
            url: 'government/editPost',
            type: 'post',
            dataType: 'JSON',
            data: {
                postId: $this.attr('data-post-id'),
                issingle: '1',
                selectedRow: {id: $this.attr('data-post-id'), windowtypeid: '5', posttypeid: '5'},
                dataRow: {id: $this.attr('data-post-id'), windowtypeid: '5', posttypeid: '5'},
                uniqId: '<?php echo $this->uniqId ?>'
            },
            success: function (data) {
                var title = plang.get('edit_post_001');

                $('<div id="' + $modalId_<?php echo $this->uniqId ?> + '" class="modal intranet_sent_email fade" tabindex="-1" role="dialog">' +
                        '<div class="modal-dialog modal-lg">' +
                        '<div class="modal-content modalcontent<?php echo $this->uniqId ?>">' +
                        '<div class="modal-header">' +
                        '<h6 class="modal-title">' + title + '</h6>' +
                        '<button type="button" class="close" onclick="close_<?php echo $this->uniqId ?>()" style="filter: inherit;">&times;</button>' +
                        '</div>' +
                        '<div class="modal-body">' +
                        data.Html +
                        '</div>' +
                        '<div class="modal-footer">' +
                        '<button type="button" class="btn send-btn-<?php echo $this->uniqId ?>" onclick="save_<?php echo $this->uniqId ?>(\'.modalcontent<?php echo $this->uniqId ?>\')">Хадгалах</button>' +
                        '<button type="button" class="btn btn-link" onclick="close_<?php echo $this->uniqId ?>()">Хаах</button>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>').appendTo('body');

                var $dialog = $('#' + $modalId_<?php echo $this->uniqId ?>);

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

                $dialog.on('hidden.bs.modal', function () {
                    $dialog.remove();
                    enableScrolling();
                });
                
                $dialog.modal('show');
                 
                Core.initAjax($dialog);
                $dialog.find('.modal-backdrop').remove();
                
                Core.unblockUI('.intranet-<?php echo $this->uniqId ?>');
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
                if (typeof element !== 'undefined') {
                    Core.unblockUI(element);
                } else {
                    Core.unblockUI();
                }
            }
        });
    }
    
    function deleteSocialPost_<?php echo $this->uniqId ?>(element) {
        var $this = $(element);
        var $postId = $this.attr('data-post-id');
        
        $.ajax({
            url: 'government/deletePost',
            dataType: 'JSON',
            type: 'POST',
            data: {
                dataRow: {id: $postId},
            },
            beforeSend: function () {
                Core.blockUI({
                    message: 'Уншиж байна түр хүлээнэ үү...',
                    boxed: true
                });
            },
            success: function (response) {
                if (response) {

                    PNotify.removeAll();
                    new PNotify({
                        title: 'Амжилттай',
                        text: 'Амжилттай устгагдлаа',
                        type: 'success',
                        sticker: false
                    });

                    $socialIntranet_<?php echo $this->uniqId ?>.find('.timeline-posts div[id="post'+ $this.attr('data-post-id') +'"]').fadeOut();
                    
                    try {
                        rtc.apiSendAllUser({type: 'postDelete', postId: $postId});
                    } catch (e) {
                        console.log(e);
                    }
                    
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
    
    function likecomment_<?php echo $this->uniqId ?>(element) {
        var $this = $(element), $parent = $this.closest('ul.list-inline');
        var postId = $parent.attr('data-post-id'),
            commentId = $parent.attr('data-comment-id'),
            likeId = $this.attr('data-comment-islike');
            
        if (likeId != '' && likeId != '0') {
            
            $.ajax({
                type: 'post',
                url: 'government/saveLike/1',
                data: {
                    postId: postId, 
                    likeId: likeId, 
                    commentId: commentId, 
                    likeTypeId: '1', 
                    targetType: 'comment'
                },
                dataType: 'json',
                success: function (data) {
                    //$this.closest('ul').find('.like-count').text(data.count);
                    $this.html('<i class="ci-icon-like"></i> Like');
                    $this.attr('data-comment-islike', '');
                    $socialIntranet_<?php echo $this->uniqId ?>.find('a.like-people[data-comment-id="'+ commentId +'"]').find('.like-count').empty().append(data.count);
                    
                    try {
                        var postType = 'commentAddLikeCnt<?php echo issetParam($this->groupId) ? '_' . $this->groupId : ''; ?>';
                        rtc.apiSendAllUser({type: postType, Html: data.count, commentId: commentId});
                    } catch (e) {
                        console.log(e);
                    }
                    
                }
            });
        } else {

            $.ajax({
                type: 'post',
                url: 'government/saveLike/1',
                data: {
                    postId: postId, 
                    commentId: commentId, 
                    likeTypeId: '1', 
                    targetType: 'comment'
                },
                dataType: 'json',
                success: function (data) {
                    //$this.closest('ul').find('.like-count').text(data.count);
                    $this.html('<img src="assets/custom/img/ico/like.png" width="24" height="24"/> Unlike');
                    $this.attr('data-comment-islike', '1');
                    $socialIntranet_<?php echo $this->uniqId ?>.find('a.like-people[data-comment-id="'+ commentId +'"]').find('.like-count').empty().append(data.count);
                    
                    try {
                        var postType = 'commentAddLikeCnt<?php echo issetParam($this->groupId) ? '_' . $this->groupId : ''; ?>';
                        rtc.apiSendAllUser({type: postType, Html: data.count, commentId: commentId});
                    } catch (e) {
                        console.log(e);
                    }
                }
            });
        }
    }
    
    function createcomment_<?php echo $this->uniqId ?> (element) {
    
        var $this = $(element); 
        var text = $this.val();
        var postId = $this.attr("data-post-id");
        
        if (text === '') {
            PNotify.removeAll();
            new PNotify({
                title: 'Анхаар',
                text: 'Сэтгэгдэл бичих хэсэгт утга оруулна уу',
                type: 'error',
                sticker: false
            });
            return;
        }

        $.ajax({
            url: 'government/saveIntanetComment',
            dataType: 'JSON',
            type: 'POST',
            data: {
                postId: postId,
                commentType: 'comment',
                commentText: text,
                commentId: typeof $this.attr('data-commentid') !== 'undefined' ? $this.attr('data-commentid') : '0',
                uniqId: '<?php echo $this->uniqId ?>'
            },
            beforeSend: function () {
                Core.blockUI({
                    message: 'Уншиж байна түр хүлээнэ үү...',
                    boxed: true
                });
            },
            success: function (result) {
                
                if (typeof result.html !== 'undefined' && result.html !== '') {
                    var $parent = $this.closest('.all_comments').find('.post-comments-list');
                    $parent.empty().append(result.html).promise().done(function () {
                        $this.val('');
                        /*
                        $socialIntranet_<?php echo $this->uniqId ?>.on('click', '.comment-like', function () {
                            likecomment_<?php echo $this->uniqId ?>(this);
                        });*/
                    });
                    
                    try {
                        var postType = 'postAddComment<?php echo issetParam($this->groupId) ? '_' . $this->groupId : ''; ?>';
                        rtc.apiSendAllUser({type: postType, Html: result.html, postId: postId});
                    } catch (e) {
                        console.log(e);
                    }
                    
                } 
                
                Core.unblockUI();
            }
        });
    } 
    
</script>