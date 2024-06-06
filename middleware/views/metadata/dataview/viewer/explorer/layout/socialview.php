<div id="socialview-<?php echo $this->dataViewId; ?>" class="socialview">
    <div id="socialview-board" class="u-fancy-scrollbar js-no-higher-edits js-list-sortable">
        <!--<form id="commentInputForm" class="" method="post" enctype="multipart/form-data">
          <div class="media form-group row fom-row">
            <div class="row">
              <div class="col-md-12">
                <textarea name="param[description]" required="required" class="col-md-10 form-control" rows="2" style="border-radius: 0px !important;" placeholder="<?php echo $this->lang->line('social_book_comment_input') ?>"></textarea>
                <input type="hidden" name="methodId" value="1478851914875">
                <input type="hidden" name="processSubType" value="internal">
                <input type="hidden" name="create" value="1">
                <input type="hidden" name="inputMetaDataId" value="1478851914832">
                <input type="hidden" name="isSystemProcess" value="true">
                <input type="hidden" name="processSubType" value="internal">
                <input type="hidden" name="responseType" value="">
    
                <input type="hidden" name="parentId" value="">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12" id="file-upload-area">
    
              </div>
            </div>
            <div class="float-left">
              <div class="col-md-6">
                <a id="socialview-fileupload"><i class="fa fa-upload mt20" aria-hidden="true"></i></a>
              </div>
            </div>
            <div class="float-right">
              <div class="col-md-6">
                <a href="javascript:;" id="postComment" class="btn btn-success btn-circle btn-sm mt20"><?php echo $this->lang->line('social_book_post') ?></a>
              </div>
            </div>
          </div>
        </form>
        -->
        <div class="comment-list-section">
            <?php
//    var_dump($this->recordList);
//    die;
            foreach ($this->recordList as $recordRow) {
//            $photoField = isset($recordRow[$this->photoField]) ? $recordRow[$this->photoField] : '';
//                $rowJson    = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
                ?>
                <?php
                if (isset($recordRow['parentid']) && is_null($recordRow['parentid'])) {
                    ?>
                    <div class="media shadow">
                        <a href="javascript:;" class="float-left">
                            <img alt="" src="<?php echo $recordRow['picture']; ?>" class="rounded-circle media-object" style="width:35px;height:35px">
                        </a>
                        <div class="media-body">
                            <div class="media-heading">
                                <div class="card-title">
                                    <span class="caption-subject font-weight-bold card-subject-customed card-subject-customed-name">
                                        <?php echo $recordRow['firstname']; ?>
                                    </span>
                                    <span class="caption-subject card-subject-customed" style="padding-left: 2px">нийтлэв.</span>
                                    <i style="margin-left: 10px" class="fa fa-calendar" aria-hidden="true"></i><span class="comment-date"><?php echo $recordRow['createddate']; ?></span>
                                </div>
                                <p>
                                    <?php echo $recordRow['description']; ?>
                                </p>
                                <?php
                                if (isset($recordRow['attachment']) && !is_null($recordRow['attachment'])) {
                                    ?>
                                    <img class="img-thumbnail media-object" style="margin-bottom: 10px; width:30%;height:auto" src = "<?php echo $recordRow['attachment'] ?>" data-full="<?php echo $recordRow['attachment'] ?>">
                                <?php }
                                ?>
                                <?php
                                $hasChild = false;
                                $cnt = count($this->recordList);
                                for ($i = 0; $i < $cnt; $i++) {
                                    if (isset($this->recordList[$i]['parentid']) && isset($recordRow['id']) && $this->recordList[$i]['parentid'] == $recordRow['id']) {
                                        $hasChild = true;
                                        break;
                                    }
                                }

                                if ($hasChild) {
                                    ?>
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <?php
                                            for ($i = 0; $i < $cnt; $i++) {
                                                if ($this->recordList[$i]['parentid'] == $recordRow['id']) {
                                                    ?>
                                                    <a href="javascript:;" class="float-left">
                                                        <img alt="" src="<?php echo $this->recordList[$i]['picture']; ?>" class="rounded-circle media-object" style="width:35px;height:35px; margin-right: 10px;">
                                                    </a>
                                                    <div class="comment-detail">
                                                        <div class="media-heading">
                                                            <div class="card-title">
                                                                <span class="caption-subject font-weight-bold card-subject-customed card-subject-customed-name">
                                                                    <?php echo $this->recordList[$i]['firstname']; ?>
                                                                </span>
                                                                <span class="caption-subject card-subject-customed" style="padding-left: 2px"><?php
                                                                    echo $this->recordList[$i]['actiontype'] == 1 ? "сэтгэгдэл үлдээв" : "хуваалцав";
                                                                    ?></span>
                                                                <i style="margin-left: 10px" class="fa fa-calendar" aria-hidden="true"></i><span class="comment-date"><?php echo $this->recordList[$i]['createddate']; ?></span>
                                                            </div>
                                                            <p> <?php echo $this->recordList[$i]['description']; ?></p>
                                                            <?php
                                                            if (!is_null($this->recordList[$i]['attachment'])) {
                                                                ?>
                                                                <img class="img-thumbnail media-object" style="width:30%;height:auto" src = "<?php echo $this->recordList[$i]['attachment'] ?>"  data-full="<?php echo $this->recordList[$i]['attachment'] ?>">
                                                            <?php }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <hr class="comment-hr">
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="form-group row fom-row">
                                    <div class="float-left">
                                        <a href="javascript:;" style="color: #A49FFD;">
                                            <i class="fa fa-heart" aria-hidden="true"></i>
                                        </a>
                                        <a href="javascript:;" style="color: #939391;" class="counts">
                                            100
                                        </a>
                                        <a href="javascript:;" style="color: #63B5AB;">
                                            <i class="fa fa-share-alt" aria-hidden="true"></i>
                                        </a>
                                        <a href="javascript:;" style="color: #939391;" class="counts">
                                            100
                                        </a>
                                        <!--                    <a href="javascript:;" style="color: #DDDD37;">
                                                              <i class="fa fa-comments" aria-hidden="true"></i>
                                                            </a>
                                                            <a href="javascript:;" style="color: #939391;" class="counts">
                                                              100
                                                            </a>-->
                                    </div>
                                    <div class="float-right mt20">
                                        <a href="javascript:;">
                                            <i class="fa fa-reply" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="form-group row fom-row sub-comment" style="display: none;">
                                    <textarea class="col-md-10 form-control" style="margin-top: 20px; border-radius: 0px !important;" rows="2"></textarea>
                                    <div class="float-right">
                                        <div class="col-md-12">
                                            <button class="btn btn-success btn-circle btn-sm mt20"><?php echo $this->lang->line('social_book_post') ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="media shadow">
                        <a href="javascript:;" class="float-left">
                            <img alt="" src="<?php echo $recordRow['picture']; ?>" class="rounded-circle media-object" style="width:35px;height:35px">
                        </a>
                        <div class="media-body">
                            <div class="media-heading">
                                <div class="card-title">
                                    <span class="caption-subject font-weight-bold card-subject-customed card-subject-customed-name">
                                        <?php echo $recordRow['firstname']; ?>
                                    </span>
                                    <span class="caption-subject card-subject-customed" style="padding-left: 2px">нийтлэв.</span>
                                    <i style="margin-left: 10px" class="fa fa-calendar" aria-hidden="true"></i><span class="comment-date"><?php echo $recordRow['createddate']; ?></span>
                                </div>
                                <p>
                                    <?php echo $recordRow['description']; ?>
                                </p>
                                <?php
                                if (isset($recordRow['attachment']) && !is_null($recordRow['attachment'])) {
                                    ?>
                                    <img class="img-thumbnail media-object" style="margin-bottom: 10px; width:30%;height:auto" src = "<?php echo $recordRow['attachment'] ?>" data-full="<?php echo $recordRow['attachment'] ?>">
                                <?php }
                                ?>
                                <?php
                                $hasChild = false;
                                $cnt = count($this->recordList);
                                for ($i = 0; $i < $cnt; $i++) {
                                    if (isset($this->recordList[$i]['parentid']) && isset($recordRow['id']) && $this->recordList[$i]['parentid'] == $recordRow['id']) {
                                        $hasChild = true;
                                        break;
                                    }
                                }

                                if ($hasChild) {
                                    ?>
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <?php
                                            for ($i = 0; $i < $cnt; $i++) {
                                                if ($this->recordList[$i]['parentid'] == $recordRow['id']) {
                                                    ?>
                                                    <a href="javascript:;" class="float-left">
                                                        <img alt="" src="<?php echo $this->recordList[$i]['picture']; ?>" class="rounded-circle media-object" style="width:35px;height:35px; margin-right: 10px;">
                                                    </a>
                                                    <div class="comment-detail">
                                                        <div class="media-heading">
                                                            <div class="card-title">
                                                                <span class="caption-subject font-weight-bold card-subject-customed card-subject-customed-name">
                                                                    <?php echo $this->recordList[$i]['firstname']; ?>
                                                                </span>
                                                                <span class="caption-subject card-subject-customed" style="padding-left: 2px"><?php
                                                                    echo $this->recordList[$i]['actiontype'] == 1 ? "сэтгэгдэл үлдээв" : "хуваалцав";
                                                                    ?></span>
                                                                <i style="margin-left: 10px" class="fa fa-calendar" aria-hidden="true"></i><span class="comment-date"><?php echo $this->recordList[$i]['createddate']; ?></span>
                                                            </div>
                                                            <p> <?php echo $this->recordList[$i]['description']; ?></p>
                                                            <?php
                                                            if (isset($this->recordList[$i]['attachment']) && !is_null($this->recordList[$i]['attachment'])) {
                                                                ?>
                                                                <img class="img-thumbnail media-object" style="width:30%;height:auto" src = "<?php echo $this->recordList[$i]['attachment'] ?>"  data-full="<?php echo $this->recordList[$i]['attachment'] ?>">
                                                            <?php }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <hr class="comment-hr">
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php } 
                                
                                ?>
                                    
                                <?php if (isset($recordRow['likecount']) || isset($recordRow['clickcount'])) { ?>
                                    <div class="form-group row fom-row">
                                        <div class="float-left">
                                            <?php if (isset($recordRow['likecount'])) { ?>
                                                <a href="javascript:;" style="color: #A49FFD;">
                                                    <i class="fa fa-heart" aria-hidden="true"></i>
                                                </a>
                                                <a href="javascript:;" style="color: #939391;" class="counts">
                                                    <?php echo $recordRow['likecount']; ?>
                                                </a>
                                            <?php } ?>
                                            <?php if (isset($recordRow['clickcount'])) { ?>
                                                <a href="javascript:;" style="color: #63B5AB;">
                                                    <i class="fa fa-share-alt" aria-hidden="true"></i>
                                                </a>
                                                <a href="javascript:;" style="color: #939391;" class="counts">
                                                    <?php echo $recordRow['clickcount']; ?>
                                                </a>
                                            <?php } ?>
                                            <!--                    <a href="javascript:;" style="color: #DDDD37;">
                                                                  <i class="fa fa-comments" aria-hidden="true"></i>
                                                                </a>
                                                                <a href="javascript:;" style="color: #939391;" class="counts">
                                                                  100
                                                                </a>-->
                                        </div>
                                        <div class="float-right mt20">
                                            <a href="javascript:;">
                                                <i class="fa fa-reply" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="form-group row fom-row sub-comment" style="display: none;">
                                    <textarea class="col-md-10 form-control" style="margin-top: 20px; border-radius: 0px !important;" rows="2"></textarea>
                                    <div class="float-right">
                                        <div class="col-md-12">
                                            <button class="btn btn-success btn-circle btn-sm mt20"><?php echo $this->lang->line('social_book_post') ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            <?php } ?>
        </div>
    </div>
    <div class="lightbox" >
        <figure></figure>
    </div>
</div>

<script>
    $(function () {
        $(".socialview .fa-reply").click(function () {
            var commentTxt = $(this).parents(".media-body").find(".sub-comment");
            if (commentTxt.is(":visible")) {
                commentTxt.hide();
            } else {
                commentTxt.show();
            }
        });

        $("#socialview-fileupload").click(function () {
            $.ajax({
                type: 'post',
                url: 'mdwebservice/renderAddModeBpFileTab',
                beforeSend: function () {
                    if (!$(
                            "link[href='assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css']").length) {
                        $("head").prepend(
                                '<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
                    }
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (data) {
                    $("#file-upload-area").html(data);
                    Core.unblockUI();
                },
                error: function () {
                    Core.unblockUI();
                }
            });
        });

        $("#postComment").click(function () {
            var commentForm = $("#commentInputForm");
            commentForm.validate({
                ignore: "",
                highlight: function (element) {
                    $(element).addClass('error');
                    $(element).parent().addClass('error');
                },
                unhighlight: function (element) {
                    $(element).removeClass('error');
                    $(element).parent().removeClass('error');
                },
                errorPlacement: function () {}
            });
            if (commentForm.valid()) {
                commentForm.ajaxSubmit({
                    type: 'post',
                    url: 'mdwebservice/runProcess',
                    dataType: 'json',
                    beforeSend: function () {
                        if (!$(
                                "link[href='assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css']").length) {
                            $("head").prepend(
                                    '<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
                        }
                        Core.blockUI({
                            boxed: true,
                            message: 'Түр хүлээнэ үү'
                        });
                    },
                    success: function (responseData) {
                        Core.unblockUI();
                        location.reload();
//              if(responseData.status === 'success'){
//                new PNotify({
//                  title: 'Success',
//                  text: responseData.message,
//                  type: 'success',
//                  sticker: false
//                });
//              } else {
//                new PNotify({
//                  title: 'Error',
//                  text: responseData.message,
//                  type: 'error',
//                  sticker: false
//                });
//              }
                    },
                    error: function () {
                        Core.unblockUI();
                    }
                });
            }
        });

        var $galleryDv = $('.socialview'),
                $lightbox = $galleryDv.find('.lightbox'),
                $figure = $lightbox.find('figure');

        $galleryDv.find('.img-thumbnail').on('click', function () {
            var full = $(this).attr('data-full');
            toggleLightbox(full);
        });

        function toggleLightbox(url) {

            if ($lightbox.is('.open')) {
                $lightbox.removeClass('open').fadeOut(200);
            } else {
                $figure.css('background-image', 'url(' + url + ')');
                $lightbox.addClass('open').fadeIn(200);
            }
        }

        $lightbox.on('click', toggleLightbox);

    });
</script>

<style>
    .socialview .lightbox {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.7);
        padding: 75px;
        text-align: center;
        display: none;
        cursor: -webkit-zoom-out;
        cursor: -moz-zoom-out;
    }
    .socialview .lightbox figure {
        display: block;
        position: relative;
        width: 100%;
        height: 100%;
        white-space: no-wrap;
        background-repeat: no-repeat;
        background-position: center;
        -moz-background-size: contain;
        -o-background-size: contain;
        -webkit-background-size: contain;
        background-size: contain;
    }

    .socialview .comment-list-section .fa {
        font-size: 1.2em;
    }
    .socialview .comment-list-section .caption .fa {
        font-size: 0.7em;
        color: black;
    }
    .socialview .comment-list-section{
        background-color: #ecf2f6;
        padding-left: 5px;
        padding-right: 5px;
    }
    .socialview .media{
        background-color: #fff;
        margin-top: 10px;
        padding-left: 10px;
        padding-right: 10px;
        padding-top: 10px;
        border-radius: 0px;
        position: relative;
    }
    .socialview .comment-date{
        font-size: 0.7em;
        color: #5D5F5F;
        margin-left: 10px;
        /*    margin-bottom: 1px;
            margin-top: 1px;*/
    }
    .socialview .counts {
        font-size: 0.7em;
        padding-right: 20px;
    }

    .socialview .comment-detail {
        margin-left: 40px;
    }
    .socialview .media > .float-left {
        padding-right: 10px;
    }
    .socialview .media img {
        /*height: 54px;*/
        position: relative;
        top: 3px;
        /*width: 54px;*/
    }
    .socialview img.img-thumbnail {
        cursor:zoom-in;
    }
    .socialview{
        bottom: 0;
        content: "";
        display: block;
        top: 0;
        margin-left: -18px;
        margin-right: -18px;
    }
    .socialview .comment-hr{
        border-color: #929595 -moz-use-text-color -moz-use-text-color;
    }
    .socialview .panel{
        padding-left: 18px;
        /*margin-top: 20px;*/
        padding-top: 10px;
        padding-right: 20px;
        padding-bottom: 0px;
        border-radius: 0px !important;
        border-style: solid;
        border-width: medium;
        position: relative;
        background-color: #1111;
    }
    .socialview .panel-body{
        padding-top: 0px !important;
        padding-left: 0px !important;
        padding-right: 15px !important;
        padding-bottom: 15px !important;
    }
    .socialview .media-body p{
        font-size: 18px;
        color: #7a7e7e !important;
        margin-top: 10px;
        margin-bottom: 15px;
    }
    .socialview .media-heading{
        margin-top: 5px;
        margin-bottom: 0px;
    }
    .socialview .card-subject-customed{
        color: #555757 !important;
        font-size: 12px;
        margin-top: 3px;
    }
    .socialview .card-subject-customed-name{
        color: #4e86c1 !important;
    }
    .panel-body hr:last-child{
        display: none;
    }
</style>
