<aside class="col col-xl-3 order-xl-3 col-lg-6 order-lg-3 col-md-6 col-sm-12 col-12 rigthside<?php echo $this->uniqId ?>">
    <?php if (empty($this->groupData)) { ?>
        <div class="ui-block">
            <div class="friend-item">
                <div class="friend-header-thumb">
                    <img src="<?php echo issetParam($this->userInfo['result']['backgroundpic']); ?>" onerror="onFiUserCovError(this);" style="width: 184px !important; height: 184px !important; margin: auto !important; display: block !important; text-align: center;">
                </div>

                <div class="friend-item-content">

                    <div class="friend-avatar">
                        <div class="author-thumb">
                            <img src="<?php echo issetParam($this->userInfo['result']['userpicture']) ?>" onerror="onNCUserImgError(this);">
                        </div>
                        <div class="author-content">
                            <a href="profile" class="h5 author-name"><?php echo Ue::getSessionPersonWithLastName(); ?></a>
                        </div>
                    </div>
                    <div class="friend-count">
                        <a href="javascript:;" class="friend-count-item">
                            <div class="h6"><?php echo issetParamZero($this->userInfo['result']['allposts']); ?></div>
                            <div class="title"><?php echo Lang::line('LCODE_POST'); ?></div>
                        </a>
                        <a href="javascript:;" class="friend-count-item">
                            <div class="h6"><?php echo issetParamZero($this->userInfo['result']['imageposts']); ?></div>
                            <div class="title"><?php echo Lang::line('LCODE_PHOTOS'); ?></div>
                        </a>
                        <a href="javascript:;" class="friend-count-item">
                            <div class="h6"><?php echo issetParamZero($this->userInfo['result']['videoposts']); ?></div>
                            <div class="title"><?php echo Lang::line('LCODE_VIDEOS'); ?></div>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    <?php }
    if (isset($this->groupData['result']) && issetParam($this->mainRow['isapproved']) === '1' && issetParam($this->mainRow['createduserid']) !== Ue::sessionUserKeyId()) { ?>
        <div class="ui-block">
            <a class="ui-block-title text-muted" type="button" href="javascript:;" onclick="requestActionGroup(this, '<?php echo $this->groupData['result']['id']; ?>', '2');" >
                <h6 class="title pr-1"><?php echo Lang::line('exit_request_group') ?></h6>
                <i class="fa fa-send" title="<?php echo Lang::line('exit_request_group') ?>"></i>
            </a>
        </div>
    <?php }
    if (isset($this->groupData['result']) && issetParam($this->mainRow['isapproved']) !== '1') { ?>
        <div class="ui-block">
            <a class="ui-block-title text-muted" type="button" href="javascript:;" onclick="sendRequestGroup('<?php echo $this->groupData['result']['id']; ?>');" >
                <h6 class="title pr-1"><?php echo Lang::line('send_request_group') ?></h6>
                <i class="fa fa-send" title="<?php echo Lang::line('send_request_group') ?>"></i>
            </a>
        </div>
    <?php }
    else { 
        if (issetParamArray($this->requestUserList['result'])) { ?>
            <div class="ui-block">
                <div class="ui-block-title">
                    <h6 class="title"><?php echo Lang::line('GROUP_REQ_00001') ?></h6>
                    <?php if (isset($this->groupData['result']) && issetParam($this->mainRow['createduserid']) === Ue::sessionUserKeyId()) { ?>
                        <i onclick="privateGroup(<?php echo Config::getFromCache('SCL_GROUP_USER') . ', ' . $this->groupData['result']['id']; ?>);" class="fa fa-edit"></i>
                    <?php } ?>
                </div>
                <div class="scroller" style="height: 500px" data-handle-color="#999">
                    <ul class="widget w-friend-pages-added notification-list friend-requests">
                        <?php
                            foreach ($this->requestUserList['result'] as $row) {
                        ?>
                                <li class="inline-items pr-0">
                                    <div class="author-thumb">
                                        <img src="<?php echo issetParam($row['picture']) ?>" class="rounded-circle" width="40" height="40" alt="" onerror="onNCUserImgError(this);" data-userid="<?php echo $row['userid'] ?>">
                                    </div>
                                    <div class="notification-event">
                                        <a href="javascript:;" class="h6 notification-friend"><?php echo $row['firstname']; ?></a>
                                        <span class="chat-message-item"><?php echo $row['lastname']; ?></span>
                                    </div>
                                    <div class="author-thumb btn-group-circle btn-group-sm pull-right">
                                        <a href="javascript:;" class="btn btn-light btn-circle bg-primary border-0 p-1 pl-2 pr-2 gaction-btns" onclick="requestActionGroup(this, '<?php echo $row['id'] ?>', '1');" title="<?php echo Lang::line('approve_btn') ?>"><i class="fa fa-check-circle"></i></a>
                                        <a href="javascript:;" class="btn btn-light btn-circle bg-danger border-0 p-1 pl-2 pr-2 gaction-btns" onclick="requestActionGroup(this, '<?php echo $row['id'] ?>', '3');" title="<?php echo Lang::line('block_btn') ?>"><i class="fa fa-times"></i></a>
                                    </div>
                                </li>
                        <?php
                            }

                        ?>
                    </ul>
                </div>
            </div>
       <?php  } ?>
        <div class="ui-block">
            <div class="ui-block-title">
                <h6 class="title">Идэвхитэй гишүүд</h6>
                <?php if (isset($this->groupData['result']) && issetParam($this->mainRow['createduserid']) === Ue::sessionUserKeyId()) { ?>
                    <i onclick="privateGroup(<?php echo Config::getFromCache('SCL_GROUP_USER') . ', ' . $this->groupData['result']['id']; ?>);" class="fa fa-edit"></i>
                <?php } ?>
            </div>
            <div class="scroller" style="height: 500px" data-handle-color="#999">
                <ul class="widget w-friend-pages-added notification-list friend-requests">
                    <?php
                    if (issetParam($this->activeUserList['result'])) {
                        foreach ($this->activeUserList['result'] as $row) {
                    ?>
                            <li class="inline-items">
                                <div class="author-thumb">
                                    <img src="<?php echo issetParam($row['picture']) ?>" class="rounded-circle" width="40" height="40" alt="" onerror="onNCUserImgError(this);" data-userid="<?php echo $row['userid'] ?>">
                                </div>
                                <div class="notification-event">
                                    <a href="javascript:;" class="h6 notification-friend"><?php echo $row['firstname']; ?></a>
                                    <span class="chat-message-item"><?php echo $row['lastname']; ?></span>
                                </div>
                                <?php if (isset($this->groupData['result']) && issetParam($this->mainRow['createduserid']) === Ue::sessionUserKeyId()) { ?>
                                    <div class="author-thumb btn-group-circle btn-group-sm pull-right">
                                        <a href="javascript:;" class="btn btn-light btn-circle bg-danger border-0 p-1 pl-2 pr-2 pull-right gaction-btns" onclick="requestActionGroup(this, '<?php echo $row['id'] ?>', '3');" title="<?php echo Lang::line('block_btn') ?>"><i class="fa fa-times"></i></a>
                                    </div>
                                <?php } ?>
                            </li>
                    <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    <?php } ?>
</aside>
<script type="text/javascript"> 
    function sendRequestGroup (groupId) {
        $.ajax({
            type: 'post',
            url: 'government/sendRequestGroup',
            data: {
                groupId: groupId, 
            },
            dataType: 'json',
            success: function (data) {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.text,
                    type: data.status,
                    sticker: false
                });
            },
            error: function (jqXHR, exception) {
                Core.showErrorMessage(jqXHR, exception);
                Core.unblockUI();
            }
        });
    }
    
    function requestActionGroup (element, mainId, type) {
        var $dialogConfirm = 'dialog-confirm-<?php echo $this->uniqId ?>';
        var $html = '';
        switch (type) {
            case '0':
                $html = 'Татгалзахдаа итгэлтэй байна уу?'
                break;
            case '1':
                $html = 'Зөвшөөрөхдөө итгэлтэй байна уу?'
                break;
            case '2':
                $html = 'Гарахдаа итгэлтэй байна уу?'
                break;

            default:

                break;
        }
        
        if (!$("#" + $dialogConfirm).length) {
            $('<div id="' + $dialogConfirm + '"></div>').appendTo('.intranet-<?php echo $this->uniqId ?>');
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
        
                    $.ajax({
                        type: 'post',
                        url: 'government/requestActionGroup',
                        data: {
                            mainId: mainId, 
                            type: type, 
                        },
                        dataType: 'json',
                        success: function (data) {
                            PNotify.removeAll();
                            new PNotify({
                                title: data.status,
                                text: data.text,
                                type: data.status,
                                sticker: false
                            });

                            if (type !== '2') {
                                $(element).closest('li').fadeOut();
                            } else {
                                if (typeof firstList_<?php echo $this->uniqId; ?> !== 'undefined') {
                                    reloadmenu_<?php echo $this->uniqId ?>(firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').parent().index());
                                }
                            }
                        },
                        error: function (jqXHR, exception) {
                            Core.showErrorMessage(jqXHR, exception);
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
</script>