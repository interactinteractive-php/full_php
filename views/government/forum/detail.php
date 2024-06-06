<div class="forum-<?php echo $this->uniqId ?> ">
    <div class="row">
        <?php echo (issetParamArray($this->data['lislawdiscussionquestiondv'])) ? '' : '<div class="col-md-3"></div>'; ?>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="product not-hover">
                        <div class="product-body w-100 pull-left">
                            <div class="product-cat">
                                <label><?php echo (issetParam($this->data['departmentname']) ? $this->data['departmentname'] . ' > ' : '') . $this->data['discussiontypename'] ?></label>
                            </div>
                            <section class="pull-right w-50"><a href="javascript:;" class="btn btn-xs btn-danger pull-right pt-0" onclick="follow<?php echo $this->uniqId ?>(this, '<?php echo issetParamZero($this->data['followed']); ?>')"><i class="fa fa-user-plus"></i> <?php echo Lang::line('follow_btn') ?></a></section>
                            <h3 class="product-title"><label><?php echo $this->data['subject'] ?></label></h3>
                            <p><?php echo issetParam($this->data['description']); ?></p>
                            <?php if (issetParam($this->data['physicalpath'])) { 
                                $fileextention = $this->data['fileextension'];
                                $fileExtension = ($fileextention) ? $fileextention : strtolower(substr($fileextention, strrpos($fileextention, '.') + 1));
                                ?>
                                <div class="w-100 pull-left align-center" style="text-align: center;">
                                    <a class="btn-product btn-product-s btn-cart" target="_blank"  href="<?php echo URL . $this->data['physicalpath'] ?>" d-onclick="dataViewFileViewer(this, '1', '<?php echo $fileExtension ?>', '<?php echo $this->data['physicalpath'] ?>', '<?php echo URL . $this->data['physicalpath'] ?>', '');" ><i class="fa fa-download"></i> <span>Төслийн файлыг татах</span></a>
                                </div>
                            <?php } ?>
                            <label class="list-title">Төслийн хавсралтууд</label>
                            <hr style="margin-top: 0;">
                            <?php 
                            if (issetParamArray($this->data['lislawdiscussionreferencedv'])) {
                                $referenceArr = Arr::groupByArrayOnlyRows($this->data['lislawdiscussionreferencedv'], 'contenttypename');
                                $index = 1;
                                foreach ($referenceArr as $key => $reference) { ?>
                                    <div class="card">
                                        <div class="card-header bg-slate">
                                            <h6 class="card-title">
                                                <a data-toggle="collapse" class="text-white" href="#collapsible-styled-group<?php echo $index ?>" aria-expanded="true"><?php echo $key ?></a>
                                            </h6>
                                        </div>
                                        <div id="collapsible-styled-group<?php echo $index ?>" class="collapse " style="">
                                            <div class="card-body  mb-2 p-3">
                                                <?php if ($reference) {
                                                    foreach ($reference as $skey => $row) {
                                                        $fileextention = $this->data['fileextension'];
                                                        $fileExtension = ($fileextention) ? $fileextention : strtolower(substr($fileextention, strrpos($fileextention, '.') + 1));
                                                        ?>
                                                        <a target="_blank" href="<?php echo URL . $row['attachfile'] ?>" class="btn-product btn-cart" d-onclick="dataViewFileViewer(this, '1', '<?php echo $fileExtension ?>', '<?php echo $row['attachfile'] ?>', '<?php echo URL . $row['attachfile'] ?>', '');" style="text-align: left; padding: 1rem; margin-top: 10px; text-transform: none;"><span class="w-100 pull-left"><?php echo ($row['attachfilename']) ? $row['attachfilename'] : 'Файл' ?></span></a>
                                                    <?php }
                                                } ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php 
                                    $index++;
                                }
                            }
                            ?>
                            <div class="clearfix"></div>
                            <section class="row mt-1 mb-2 w-100 pull-left" style="margin-bottom: 3rem !important;">
                                <span class="col"><?php echo 'Нийтэлсэн : '.$this->data['createdusername'] ?></span>
                                <span class="col"><i class="fa fa-calendar"></i> <?php echo $this->data['createddate'] ?></span></span>
                                <span class="col"><i class="fa fa-eye"></i> <?php echo issetParamZero($this->data['viewcount']) ?> </span>
                                <span class="col"><i class="fa fa-comment"></i> <?php echo issetParamZero($this->data['reviewcount']) ?></span>
                                <span class="col"><i class="fa fa-user-plus"></i> <?php echo issetParamZero($this->data['followcount']) ?></span>
                            </section>
                            
                            <label class="list-title">Санал</label>
                            <hr style="margin-top: 0;">
                            <div class="card-body all-comments">
                                <div class="mb-4">
                                    <?php 
                                    if (issetParamArray($this->data['lislawdiscussioncommentdv'])) {
                                        foreach ($this->data['lislawdiscussioncommentdv'] as $key => $row) { ?>
                                            <div class="media flex-column flex-md-row">
                                                <div class="mr-md-3 mb-2 mb-md-0">
                                                    <a href="javascript:void(0);"><img src="storage/uploads/process/file_1537353877188322_14930215614883211.png" class="rounded-circle" width="36" height="36" alt=""></a>
                                                </div>
                                                <div class="media-body">
                                                    <div class="media-title">
                                                        <a href="javascript:void(0);" class="font-weight-bold"><?php echo $row['createdusername'] ?></a>
                                                        <span class="font-size-sm text-muted ml-sm-2 mb-2 mb-sm-0 d-block d-sm-inline-block"><?php echo $row['datediff'] . $row['datedesc'] ?></span>
                                                    </div>
                                                    <p><?php echo $row['commenttext'] ?></p>
                                                    <ul class="list-inline font-size-sm mb-0">
                                                        <li class="list-inline-item"><a href="javascript:void(0);" class="bgbtn">Хариулах</a></li>
                                                        <li class="list-inline-item mr-2"><a href="javascript:void(0);"><i class="icon-thumbs-up2" style="top:-2px;"></i></a></li>
                                                        <li class="list-inline-item"><a href="javascript:void(0);"><i class="icon-thumbs-down2"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        <?php }
                                    } ?>
                                </div>
                                <div class="mb-3">
                                    <textarea rows="3" cols="3" class="form-control write-comment" placeholder="Саналаа бичээд ENTER дарна уу..."></textarea>
                                </div>
                            </div>
                        </div> 
                   </div>
                </div>
            </div>
        </div>
        <?php if (issetParamArray($this->data['lislawdiscussionquestiondv'])) { ?>
            <div class="col-md-6">
                <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'saveForumPoll_' . $this->uniqId, 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
                <?php foreach ($this->data['lislawdiscussionquestiondv'] as $key => $row) { ?>
                    <div class="questiondv questiondv_<?php echo $row['templateid'] ?>" data-limitcount="0">
                        <div class="question-index-<?php echo $row['templateid'] ?>">
                            <div class="d-flex flex-row align-items-center justify-content-between" style="border-bottom: 1px solid #dddddd !important">
                                <div class="w-100 mb-2">
                                    <input type="hidden" name="id" value="<?php echo $this->data['id'] ?>">
                                    <label class="col-form-label text-left  w-100 border-gray font-size-16 text-blue"> <span class="required"></span><?php echo $row['indicatorname'] ?></label>                                                </div>
                                <div class="" style="width: 150px; color: #00bcd4; font-weight: bold; text-align: right; text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="main-selector">
                                <?php if (issetParamArray($row['lislawdiscussionanswerdv'])) {
                                    foreach ($row['lislawdiscussionanswerdv'] as $skey => $srow) {
                                        ?>
                                        <div class="checkboxes checkbox-option">
                                            <div class="form-group pt-0 mb-0 data-selectoption">
                                                <div class="form-check">
                                                    <label class="form-check-label d-flex justify-content-between align-items-center">
                                                        <input type="<?php echo $srow['showtype'] ?>" class="form-check-input-styled" data-ans-id="" id="answerData_<?php echo $key; ?>_<?php echo $skey; ?>" name="checkvalue[<?php echo $key ?>]" value="<?php echo $skey . '_' . $srow['templatedtlfactid'] ?>"  data-fouc>
                                                        <input type="hidden" name="templatedtlfactid[<?php echo $srow['templatedtlfactid']; ?>][<?php echo $skey ?>]" value="<?php echo $srow['templatedtlfactid'] ?>">
                                                        <input type="hidden" name="scoreid[<?php echo $srow['templatedtlfactid']; ?>][<?php echo $skey ?>]" value="<?php echo $srow['scoreid'] ?>">
                                                        <input type="hidden" name="indicatorid[<?php echo $srow['templatedtlfactid']; ?>][<?php echo $skey ?>]" value="<?php echo $row['indicatorid'] ?>">
                                                        <input type="hidden" name="showtype[<?php echo $srow['templatedtlfactid']; ?>][<?php echo $skey ?>]" value="<?php echo $srow['showtype'] ?>">
                                                        <input type="hidden" name="parampath[<?php echo $srow['templatedtlfactid']; ?>][<?php echo $skey ?>]" value="<?php echo $srow['parampath'] ?>">
                                                        <input type="hidden" name="scorename[<?php echo $srow['templatedtlfactid']; ?>][<?php echo $skey ?>]" value="<?php echo $srow['scorename'] ?>">
                                                        <input type="hidden" name="templateid[<?php echo $srow['templatedtlfactid']; ?>][<?php echo $skey ?>]" value="<?php echo $srow['templateid'] ?>">
                                                        <input type="hidden" name="templatedtlid[<?php echo $srow['templatedtlfactid']; ?>][<?php echo $skey ?>]" value="<?php echo $srow['templatedtlid'] ?>">
                                                        <label for="answerData_<?php echo $key; ?>_<?php echo $skey; ?>" class="text-left w-100 input-text-style2 ml-1 " style="padding:10px; ">
                                                            <span class="pull-left"><?php echo $srow['scorename'] ?></span>
                                                        </label>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="d-flex justify-content-between align-items-center" style="padding: 1rem; background: #cccccc47;">
                        <a href="javascript:;" class="btn btn-light btn-icon"><i class="icon-help"></i></a>
                        <div class="d-inline-flex">
                            <a type="button" href="javascript:;" class="btn bg-blue ml-3" onclick="saveForumPoll_<?php echo $this->uniqId ?>(this)"><?php echo Lang::line('send_btn') ?> <i class="icon-paperplane ml-2"></i></a>
                        </div>
                    </div>
                <?php echo Form::close(); ?>
            </div>
        <?php } else {
            echo '<div class="col-md-3"></div>';
        } ?>
    </div>
</div>
<?php

//var_dump($this->data['lislawdiscussionquestiondv']);
?>
<?php echo issetParam($this->dcss); ?>
<script type="text/javascript"> 
    
    $('body').find('.forum-<?php echo $this->uniqId ?> textarea.write-comment').keypress(function(e) {
        if (e.keyCode == $.ui.keyCode.ENTER && !e.shiftKey) {
            createcomment_<?php echo $this->uniqId ?> (this);
        }
    });

    $('body').find('.forum-<?php echo $this->uniqId ?> textarea.write-comment').keyup(function(e) {
        if (e.keyCode == 27) {
            $(this).val('');
        }
    });
    
    function saveForumPoll_<?php echo $this->uniqId ?>(element) {
        var $this = $(element);
        
        var $processForm = $('#saveForumPoll_<?php echo $this->uniqId ?>');
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
            url: 'government/saveForumPoll',
            dataType: 'json',
            beforeSend: function () {
                blockContentGovernment ('#saveForumPoll_<?php echo $this->uniqId ?>'); 
            },
            success: function (responseData) {
                PNotify.removeAll();
                new PNotify({
                    title: responseData.status,
                    text: responseData.text,
                    type: responseData.status, 
                    sticker: false
                });

                Core.unblockUI('#saveForumPoll_<?php echo $this->uniqId ?>');
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
                Core.unblockUI('#saveForumPoll_<?php echo $this->uniqId ?>');
            }
        });
    }
    
    function createcomment_<?php echo $this->uniqId ?> (element) {
    
        var $this = $(element); 
        var text = $this.val();
        
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
            url: 'government/saveCommentLaw',
            dataType: 'JSON',
            type: 'POST',
            data: {
                recordId: '<?php echo $this->data['id'] ?>',
                commentType: 'comment',
                commentText: text,
                uniqId: '<?php echo $this->uniqId ?>'
            },
            beforeSend: function () {
                Core.blockUI({
                    message: 'Уншиж байна түр хүлээнэ үү...',
                    boxed: true
                });
            },
            success: function (response) {
                
                if (typeof response.html !== 'undefined' && response.html !== '') {
                    var $parent = $this.closest('.all-comments');
                    $parent.prepend(response.html).promise().done(function () {
                        $this.val('');
                    });
                }
                
                Core.unblockUI();
            },
            error: function (jqXHR, exception) {
                Core.showErrorMessage(jqXHR, exception);
                Core.unblockUI();
            }
        });
    } 
    
    function follow<?php echo $this->uniqId ?>(element, type) {
        
        $.ajax({
            url: 'government/lawfollow',
            dataType: 'JSON',
            type: 'POST',
            data: {
                recordId: '<?php echo $this->data['id'] ?>',
                uniqId: '<?php echo $this->uniqId ?>',
                type: type
            },
            beforeSend: function () {
                Core.blockUI({
                    message: 'Уншиж байна түр хүлээнэ үү...',
                    boxed: true
                });
            },
            success: function (response) {
                
                PNotify.removeAll();
                new PNotify({
                    title: 'Success',
                    text: 'Амжилттай',
                    type: 'success',
                    sticker: false
                });
                
                Core.unblockUI();
            },
            error: function (jqXHR, exception) {
                Core.showErrorMessage(jqXHR, exception);
                Core.unblockUI();
            }
        });
    }
    
</script>
<style type="text/css">
    
    .forum-<?php echo $this->uniqId ?> .product {
        font-family: Roboto,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji" !important;
    }
    
    .forum-<?php echo $this->uniqId ?> .spinner {
        width: initial !important;
        height: initial !important;
    }
    
    .not-hover .btn-product-s {
        display: inline-block;
        padding: 1.2rem 0.7rem;
        flex-wrap: unset;
        font-size: 18px;
        font-weight: 700;
        text-transform: none;
        margin-bottom: 20px;
    }
    
    .not-hover .product-title {
        display: block;
    }
    
    .not-hover .product-title label {
        float: left;
        width: 100%;
        text-align: center;
        font-weight: bold;
        color: #0e3452;
    }
    
    .not-hover .product-cat label {
        color: #176fac;
    }
    
    .forum-<?php echo $this->uniqId ?> .list-title {
        text-transform: uppercase;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: -0.1px;
    }
    
</style>