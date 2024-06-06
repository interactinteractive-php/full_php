<div class="col-md-12 mainSocialView pr0 hidden">
    <div class="col-md-10 content-left-<?php echo $this->metaDataId ?>">
        <div class="mainWindow-<?php echo $this->metaDataId ?>">
            <div class="col-md-3" style="width: 250px">
                <div class="row notary-logo-<?php echo $this->metaDataId ?>">
                    <img src="assets/core/global/img/logo-notary-header-black.png">
                </div>
                <div class="leftSocialViewDv-<?php echo $this->metaDataId ?>" ></div>
            </div>
            <div class="col-md-7" style="width: 600px">
                <div class="row">
                    <div class="cover-pic-<?php echo $this->metaDataId ?>"></div>
                    <div class="centerTopSocialViewDv-<?php echo $this->metaDataId ?>"></div>
                    <div class="postData-<?php echo $this->metaDataId ?>">
                        <form id="commentInputForm_<?php echo $this->metaDataId ?>" class="" method="post" enctype="multipart/form-data">
                            <div class="media form-group row fom-row">
                                <div class="row" style="margin-top: 10px; margin-left: 0px; margin-right: 0px;">
                                    <div class="col-md-12">
                                        <textarea name="commentText" required="required" class="form-control form-control-sm descriptionInit description_autoInit" rows="2" placeholder="Коммент үлдээх"></textarea>
                                        <input type="hidden" name="methodId" value="1503233875444">
                                        <input type="hidden" name="processSubType" value="internal">
                                        <input type="hidden" name="create" value="1">
                                        <input type="hidden" name="inputMetaDataId" value="1478851914832">
                                        <input type="hidden" name="isSystemProcess" value="true">
                                        <input type="hidden" name="processSubType" value="internal">
                                        <input type="hidden" name="responseType" value="">
                                        <input type="hidden" name="refStructureId" value="">
                                        <input type="hidden" name="recordId" value="">
                                    </div>
                                </div>

                                <div class="float-left" style="margin-left: 15px;">
                                    <label class="cv-file-button">
                                        <a href="javascript:;" id="socialview-fileupload" class="cv-upload-btn  btn btn-secondary btn-circle btn-sm mt5"> <i class="fa fa-file-image-o"></i> Зураг</a>
                                        <input style="padding-left:7px;" type="file" id="attachFile" name="physicalPath[]" class="col-md-12" >
                                        <span class="after-file-change hide">
                                            <span id="cv-name" class="mr-15 cv-name"></span>
                                            <i class="fa fa-check"></i>
                                        </span>
                                    </label>


                                </div>
                                <div class="float-right" style="margin-right: 15px; margin-bottom: 5px;">
                                    <a href="javascript:;" id="postComment" onclick="appendBpComment_<?php echo $this->metaDataId; ?>();" class="btn btn-secondary btn-circle btn-sm mt5">Нийтлэх</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="mainSocialViewDv-<?php echo $this->metaDataId ?>"></div>
            </div>
            <div class="col-md-2" style="width: 250px; padding-left: 0; padding-right: 0;">
                <div class="rightTopSocialViewDv-<?php echo $this->metaDataId ?>"></div>
                <div class="rightWidgetViewDv-<?php echo $this->metaDataId ?>">
                    <div class="createProcessTop-<?php echo $this->metaDataId ?>">
                        <div class="socialview-title">
                            Шинэ груп үүсгэх
                        </div>
                        <div class="socialview-content">
                            <a href="javascript:;" onclick="transferProcessAction('', '1510645111646', '1510645115828', '200101010000011', 'toolbar', this, {callerType: 'NTR_GROUP_DV'}, undefined, undefined, undefined, undefined, '');" type="button" class="btn btn-primary btn-xs">Үүсгэх</a>
                        </div>
                    </div>
                    <div class="createProcessCenter-<?php echo $this->metaDataId ?>">
                        <div class="socialview-title">
                            Гишүүн нэмэх
                        </div>
                        <div class="socialview-content">
                            <a href="javascript:;" type="button" class="btn btn-primary btn-xs"><?php echo $this->lang->line('META_00103'); ?></a>
                        </div>
                    </div>
                    <div class="createProcessBottom-<?php echo $this->metaDataId ?>"></div>
                </div>
                <div class="rightTopSocialViewDv-<?php echo $this->metaDataId ?>"></div>
            </div>
        </div>
    </div>
    <div class="col-md-2 p-0 content-right-<?php echo $this->metaDataId ?>">
        <div class="rightSocialViewDv-<?php echo $this->metaDataId ?>">
            <div class="rightBottomSocialViewDv-<?php echo $this->metaDataId ?>"></div>
            <div class="rightCenterSocialViewDv-<?php echo $this->metaDataId ?>"></div>
        </div>
    </div>
</div>
<?php echo $this->socialViewStyle ?>

<script type="text/javascript">
    var $centerDataviewId_<?php echo $this->metaDataId ?> = '1509071537823';
    var $centerTopDataviewId_<?php echo $this->metaDataId ?> = '1510645112803';
    
    var $rightDataviewId_<?php echo $this->metaDataId ?> = '1510718490581';
    var $rightCenterDataviewId_<?php echo $this->metaDataId ?> = '1510717623258728';
    
    var $leftDataviewId_<?php echo $this->metaDataId ?> = '1510715202277321';
    
    var $jobseekerRegForm = $('#commentInputForm_<?php echo $this->metaDataId ?>');
    
    var topMenuCfg = <?php echo (defined('CONFIG_TOP_MENU') ? json_encode(CONFIG_TOP_MENU) : 'false'); ?>;
    
    $(function () {
        dataViewByMeta_<?php echo $this->metaDataId; ?>($leftDataviewId_<?php echo $this->metaDataId ?>, 'left');
        
        dataViewByMeta_<?php echo $this->metaDataId; ?>($centerTopDataviewId_<?php echo $this->metaDataId ?>, 'centerTop');
        /*dataViewByMeta_<?php echo $this->metaDataId; ?>($centerDataviewId_<?php echo $this->metaDataId ?>, 'center', $('.postData-<?php echo $this->metaDataId ?>').find('input[name="refStructureId"]').val());*/
        
        
        dataViewByMeta_<?php echo $this->metaDataId; ?>($rightDataviewId_<?php echo $this->metaDataId ?>, 'right');
        dataViewByMeta_<?php echo $this->metaDataId; ?>($rightCenterDataviewId_<?php echo $this->metaDataId ?>, 'rightCenter');
        
        $jobseekerRegForm.find('#attachFile').change(function() {
           var filename=$(this).val().replace(/C:\\fakepath\\/i, '');
           if (filename !== ''){
               $jobseekerRegForm.find('.cv-name').html(filename);
               $jobseekerRegForm.find('.before-file-change').hide();
               $jobseekerRegForm.find('.after-file-change').show();
           }
       });
        
        $('body').on('click', '.leftSocialViewDv-<?php echo $this->metaDataId ?> .media', function() {
            var $this = $(this);
            var $customerId = $this.attr('data-id');
            $('.leftSocialViewDv-<?php echo $this->metaDataId ?>').find('.active').removeClass('active');
            $this.addClass('active');
            $('.postData-<?php echo $this->metaDataId ?>').find('input[name="recordId"]').val($customerId);
            
            dataViewByMeta_<?php echo $this->metaDataId; ?>($centerDataviewId_<?php echo $this->metaDataId ?>, 'center', $customerId);
        }); 
    });
    
    function dataViewByMeta_<?php echo $this->metaDataId; ?>(metaDataId, positionType, criteriaCustomerId) {
        $.ajax({
            type: 'post',
            dataType: 'json',
            async: false,
            data: {drillDownDefaultCriteria: 'groupId=' + criteriaCustomerId},
            url: 'mdobject/dataview/' + metaDataId + '/' + 'false'+ '/json/false' ,
            beforeSend: function () {},
            success: function (data) {
                Core.blockUI({
                    target: '.mainSocialView',
                    animate: true
                });
                
                switch (positionType) {
                    case 'left':
                        $('.leftSocialViewDv-<?php echo $this->metaDataId ?>').empty().append(data.Html).promise().done(function () {
                            if (topMenuCfg == true) {
                                $('.leftSocialViewDv-<?php echo $this->metaDataId ?>').find('.col-md-12:first').remove();
                            }
                        });
                        break;
                    case 'center':
                        $('.mainSocialViewDv-<?php echo $this->metaDataId ?>').empty().append(data.Html).promise().done(function () {
                            if (topMenuCfg == true) {
                                $('.mainSocialViewDv-<?php echo $this->metaDataId ?>').find('.col-md-12:first').remove();
                            }
                        });
                        break;
                    case 'centerTop':
                        $('.centerTopSocialViewDv-<?php echo $this->metaDataId ?>').empty().append(data.Html).promise().done(function () {
                            if (topMenuCfg == true) {
                                $('.centerTopSocialViewDv-<?php echo $this->metaDataId ?>').find('.col-md-12:first').remove();
                            }
                        });
                        break;
                    case 'right':
                        $('.rightTopSocialViewDv-<?php echo $this->metaDataId ?>').empty().append(data.Html).promise().done(function () {
                            if (topMenuCfg == true) {
                                $('.rightTopSocialViewDv-<?php echo $this->metaDataId ?>').find('.col-md-12:first').remove();
                            }
                        });
                        break;
                    case 'rightCenter':
                        $('.rightCenterSocialViewDv-<?php echo $this->metaDataId ?>').empty().append(data.Html).promise().done(function () {
                            if (topMenuCfg == true) {
                                $('.rightCenterSocialViewDv-<?php echo $this->metaDataId ?>').find('.col-md-12:first').remove();
                            }

                            $('.mainSocialView').removeClass('hidden');

                            setTimeout(function () {
                                $('.leftSocialViewDv-<?php echo $this->metaDataId ?>').find('.media').trigger('click');
                            }, 900);
                        });
                        
                        break;
                }
            },
            error: function(){
                alert("Error");
            }
        }).done(function() {
            Core.initAjax($(".mainSocialView"));
            Core.unblockUI('.mainSocialView');
        });
    }
    
    function appendBpComment_<?php echo $this->metaDataId; ?>() {
        
        $('#commentInputForm_<?php echo $this->metaDataId ?>').ajaxSubmit({
            type: 'post',
            url: 'mddoc/runProcessSocialView',
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    boxed: true, 
                    message: 'Түр хүлээнэ үү'
                });
            },
            success: function (responseData) {
                if (responseData.status === 'success') {
                    $('#commentInputForm_<?php echo $this->metaDataId ?>').find("input[type=file], input[type=text], textarea").val("");
                    dataViewByMeta_<?php echo $this->metaDataId; ?>($centerDataviewId_<?php echo $this->metaDataId ?>, 'center', $('.postData-<?php echo $this->metaDataId ?>').find('input[name="refStructureId"]').val());
                    $jobseekerRegForm.find('.after-file-change').hide();
                } else {
                    new PNotify({
                        title: 'Error',
                        text: responseData.text,
                        type: 'error',
                        sticker: false
                    });
                }

                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    }
    
    function commentReply(element) {
        var $this = $(element);
        var $closestForm = $this.closest('form');
        $closestForm.ajaxSubmit({
            type: 'post',
            url: 'mddoc/runProcessSocialView',
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    boxed: true, 
                    message: 'Түр хүлээнэ үү'
                });
            },
            success: function (responseData) {
                if (responseData.status === 'success') {
                    dataViewByMeta_<?php echo $this->metaDataId; ?>($centerDataviewId_<?php echo $this->metaDataId ?>, 'center');
                } else {
                    new PNotify({
                        title: 'Error',
                        text: responseData.text,
                        type: 'error',
                        sticker: false
                    });
                }

                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    }
    
</script>