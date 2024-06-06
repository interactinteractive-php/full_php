<div id="socialview-<?php echo $this->dataViewId; ?>" class="socialview <?php echo ($this->layoutTheme == 'sv_education') ? '' : 'row'?>">
    <div id="socialview-board" class="u-fancy-scrollbar js-no-higher-edits js-list-sortable">
        <?php 
        switch ($this->layoutTheme) {
            case 'sv_post': 
                $recordListArr = Arr::groupByArrayOnlyRows($this->recordList, $this->parentField);
                foreach ($this->recordList as $recordRow) {
                    if (!$recordRow[$this->parentField]) { ?>
                        <div class="media shadow" data-id="<?php echo isset($recordRow[$this->layoutPath['id']]) ? $recordRow[$this->layoutPath['id']] : '' ?>">
                            <div class="media-body">
                                <div class="media-heading">
                                    <div class="media-title float-left col-md-12 pr0 pl0">
                                        <?php  if (isset($this->layoutPath['userpicture'])) { ?>
                                            <a href="javascript:;" class="float-left mr10">
                                                <img alt="" src="<?php echo $recordRow[$this->layoutPath['userpicture']]; ?>" onerror="onUserImgError(this);" class="rounded-circle media-object" style="width: 50px; height: 50px;">
                                            </a>
                                        <?php } ?>
                                        <?php  if (isset($this->layoutPath['title'])) { ?>
                                            <div class="caption padding-10">
                                                <span class="caption-subject font-weight-bold card-subject-customed card-subject-customed-name">
                                                    <?php echo isset($recordRow[$this->layoutPath['title']]) ? $recordRow[$this->layoutPath['title']] : ''; ?>
                                                </span>
                                                <?php  if (isset($this->layoutPath['date'])) { ?>
                                                <div>
                                                    <i class="fa fa-calendar" aria-hidden="true"></i><span class="comment-date"><?php echo $recordRow[$this->layoutPath['date']]; ?></span>
                                                </div>
                                                <?php  } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="media-content float-left col-md-12 pr0 pl0">
                                        <?php 
                                        if (isset($this->layoutPath['body'])) {
                                            echo '<p>' . $recordRow[$this->layoutPath['body']] . '</p>'; 
                                        } 
                                        if (isset($this->layoutPath['photo']) && isset($recordRow[$this->layoutPath['photo']]) && $recordRow[$this->layoutPath['photo']]) {
                                            echo '<p><img alt="" src="'. $recordRow[$this->layoutPath['photo']] .'" onerror="onUserImgError(this);" class="media-object" style="max-width: 500px; max-height: 500px; border-radius: 0;"></p>'; 
                                        } 
                                        if (isset($this->layoutPath['attachment']) && !is_null($recordRow[$this->layoutPath['attachment']])) { ?>
                                            <img class="img-thumbnail media-object" style="margin-bottom: 10px; width:30%;height:auto" src = "<?php echo $recordRow[$this->layoutPath['attachment']] ?>" data-full="<?php echo $recordRow[$this->layoutPath['attachment']] ?>">
                                        <?php }
                                        if (isset($this->layoutPath['like']) || isset($this->layoutPath['view']) || isset($this->layoutPath['comment'])) { ?>
                                        <div class="form-group row fom-row">
                                            <div class="float-left">
                                                <?php if (isset($this->layoutPath['comment'])) { ?>
                                                    <a href="javascript:;">
                                                        <i class="fa fa-comment-o" aria-hidden="true"></i>
                                                    </a>
                                                    <a href="javascript:;" style="color: #939391;" class="counts"> <?php echo $this->lang->line('META_00150'); ?> <?php echo $recordRow[$this->layoutPath['comment']] ?></a>
                                                <?php } ?>
                                            </div>
                                            <div class="float-right">
                                                <?php if (isset($this->layoutPath['like'])) { ?>
                                                    <a href="javascript:;" style="color: #A49FFD;">
                                                        <i class="icon-like" aria-hidden="true"></i>
                                                    </a>
                                                    <a href="javascript:;" style="color: #939391;" class="counts"> Таалагдсан
                                                        <?php echo $recordRow[$this->layoutPath['like']] ?>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="media-footer">
                            <?php
                            if (isset($recordListArr[$recordRow[$this->layoutPath['id']]])) {
                                foreach ($recordListArr[$recordRow[$this->layoutPath['id']]] as $subRecordRow) {  ?>
                                    <div class="comment-detail">
                                        <div class="media-heading">
                                            <div class="card-title">
                                                <?php  if (isset($this->layoutPath['userpicture'])) { ?>
                                                    <a href="javascript:;" class="float-left">
                                                        <img alt="" src="<?php echo $subRecordRow[$this->layoutPath['userpicture']]; ?>" onerror="onUserImgError(this);" class="rounded-circle media-object" style="width: 50px; height: 50px;">
                                                    </a>
                                                <?php } ?>
                                            </div>
                                            <?php 
                                            if (isset($this->layoutPath['body'])) {
                                                echo '<p style="    padding-top: 4px; padding-left: 70px;">' . $subRecordRow[$this->layoutPath['body']] . '</p>'; 
                                            } 
                                            if (isset($subRecordRow[$this->layoutPath['photo']]) && $subRecordRow[$this->layoutPath['photo']]) {
                                                echo '<p><img alt="" src="'. $subRecordRow[$this->layoutPath['photo']] .'" onerror="onUserImgError(this);" class="media-object" style="max-width: 500px; max-height: 500px; border-radius: 0;"></p>'; 
                                            }  ?>
                                        </div>
                                    </div>
                                    <hr class="comment-hr">

                                <?php }
                            } 
                            if (isset($this->layoutPath['comment'])) { ?>
                                <form class="" method="post" enctype="multipart/form-data" style="background-color: #FFF; margin-right: 12px;  ">
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
                                                <input type="hidden" name="recordId" value="<?php echo isset($recordRow[$this->layoutPath['id']]) ? $recordRow[$this->layoutPath['id']] : '' ?>">
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
                                            <a href="javascript:;" onclick="commentReply(this)" data-srcid="<?php echo isset($recordRow[$this->layoutPath['id']]) ? $recordRow[$this->layoutPath['id']] : '' ?>" class="btn btn-secondary btn-circle btn-sm mt5"> Хариулах</a>
                                        </div>
                                    </div>
                                </form>   
                            <?php } ?>
                        </div>
                    <?php }
                }
                break;
            case 'sv_explorer': ?>
                <div class="comment-list-section">
                    <?php 
                        foreach ($this->recordList as $recordRow) { ?>
                        <div class="media shadow"  data-id="<?php echo (isset($this->layoutPath['id']) && isset($recordRow[$this->layoutPath['id']])) ? $recordRow[$this->layoutPath['id']] : '' ?>">
                            <?php  if (isset($this->layoutPath['userpicture'])) { ?>
                                <a href="javascript:;" class="float-left">
                                    <img alt="" src="<?php echo $recordRow[$this->layoutPath['userpicture']]; ?>" onerror="onUserImgError(this);" class="rounded-circle media-object" style="width: 25px; height: 25px;">
                                </a>
                            <?php } ?>
                            <div class="media-body">
                                <div class="media-heading">
                                    <?php  if (isset($this->layoutPath['title'])) { ?>
                                        <div class="card-title">
                                            <span class="caption-subject font-weight-bold card-subject-customed card-subject-customed-name">
                                                <?php echo isset($recordRow[$this->layoutPath['title']]) ? $recordRow[$this->layoutPath['title']] : ''; ?>
                                            </span>
                                            <?php  if (isset($this->layoutPath['date'])) { ?>
                                            <div >
                                                <i class="fa fa-calendar" aria-hidden="true"></i><span class="comment-date"><?php echo $recordRow[$this->layoutPath['date']]; ?></span>
                                            </div>
                                            <?php  } ?>
                                        </div>
                                    <?php }
                                    if (isset($this->layoutPath['body'])) {
                                        echo '<p>' . $recordRow[$this->layoutPath['body']] . '</p>'; 
                                    } 
                                    if (isset($this->layoutPath['photo']) && isset($recordRow[$this->layoutPath['photo']]) && $recordRow[$this->layoutPath['photo']]) {
                                        echo '<p><img alt="" src="'. $recordRow[$this->layoutPath['photo']] .'" onerror="onUserImgError(this);" class="media-object" style="max-width: 500px; max-height: 500px; border-radius: 0;"></p>'; 
                                    } 
                                    if (isset($this->layoutPath['attachment']) && !is_null($recordRow[$this->layoutPath['attachment']])) { ?>
                                        <img class="img-thumbnail media-object" style="margin-bottom: 10px; width:30%;height:auto" src = "<?php echo $recordRow[$this->layoutPath['attachment']] ?>" data-full="<?php echo $recordRow[$this->layoutPath['attachment']] ?>">
                                    <?php }
                                    if (isset($this->layoutPath['like']) || isset($this->layoutPath['view']) || isset($this->layoutPath['comment'])) { ?>
                                    <div class="form-group row fom-row">
                                        <div class="float-right">
                                            <?php if (isset($this->layoutPath['like'])) { ?>
                                                <a href="javascript:;" style="color: #A49FFD;">
                                                    <i class="icon-like" aria-hidden="true"></i>
                                                </a>
                                                <a href="javascript:;" style="color: #939391;" class="counts"> Таалагдсан
                                                    <?php echo $recordRow[$this->layoutPath['like']] ?>
                                                </a>
                                            <?php } ?>
                                            <?php if (isset($this->layoutPath['comment'])) { ?>
                                                <a href="javascript:;">
                                                    <i class="fa fa-comment-o" aria-hidden="true"></i>
                                                </a>
                                                <a href="javascript:;" style="color: #939391;" class="counts"> <?php echo $this->lang->line('META_00150'); ?> <?php echo $recordRow[$this->layoutPath['comment']] ?></a>
                                            <?php } ?>
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
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="media-footer">
                            <?php if (isset($this->layoutPath['comment'])) { ?>
                                <form class="" method="post" enctype="multipart/form-data" style="background-color: #FFF; margin-right: 12px;  ">
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
                                                <input type="hidden" name="recordId" value="<?php echo isset($recordRow[$this->layoutPath['id']]) ? $recordRow[$this->layoutPath['id']] : '' ?>">
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
                                            <a href="javascript:;" onclick="commentReply(this)" data-srcid="<?php echo isset($recordRow[$this->layoutPath['id']]) ? $recordRow[$this->layoutPath['id']] : '' ?>" class="btn btn-secondary btn-circle btn-sm mt5"> Хариулах</a>
                                        </div>
                                    </div>
                                </form>   
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php
                break;
            case 'sv_online': 
                foreach ($this->recordList as $recordRow) { ?>
                    <div class="media shadow" data-id="<?php echo (isset($this->layoutPath['id']) && isset($recordRow[$this->layoutPath['id']])) ? $recordRow[$this->layoutPath['id']] : '' ?>">
                        <?php  if (isset($this->layoutPath['userpicture'])) { ?>
                            <a href="javascript:;" class="float-left">
                                <img alt="" src="<?php echo $recordRow[$this->layoutPath['userpicture']]; ?>" onerror="onUserImgError(this);" class="rounded-circle media-object" style="width: 25px;height: 25px;;">
                            </a>
                        <?php } ?>
                        <div class="media-body">
                            <div class="media-heading">
                                <?php  if (isset($this->layoutPath['username'])) { ?>
                                    <div class="card-title">
                                        <span class="caption-subject font-weight-bold card-subject-customed card-subject-customed-name" style="width: 100%; float: left;">
                                            <?php echo isset($recordRow[$this->layoutPath['username']]) ? $recordRow[$this->layoutPath['username']] : ''; ?>
                                        </span>
                                        <span class="caption-online-status"></span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php 
                }
                break;
            case 'sv_widget': 
                foreach ($this->recordList as $recordRow) {?>
                    <div class="media shadow" data-id="<?php echo isset($recordRow[$this->layoutPath['id']]) ? $recordRow[$this->layoutPath['id']] : '' ?>">
                        <div class="media-body">
                            <div class="media-heading">
                                <div class="media-title float-left col-md-12 pr0 pl0">
                                    <?php  if (isset($this->layoutPath['userpicture'])) { ?>
                                        <a href="javascript:;" class="float-left mr10">
                                            <img alt="" src="<?php echo $recordRow[$this->layoutPath['userpicture']]; ?>" onerror="onUserImgError(this);" class="rounded-circle media-object" style="width: 25px; height: 25px;">
                                        </a>
                                    <?php } ?>
                                    <?php  if (isset($this->layoutPath['title'])) { ?>
                                        <div class="caption caption padding-5 mb5">
                                            <span class="caption-subject font-weight-bold card-subject-customed card-subject-customed-name">
                                                <?php echo isset($recordRow[$this->layoutPath['title']]) ? $recordRow[$this->layoutPath['title']] : ''; ?>
                                            </span>
                                            <?php  if (isset($this->layoutPath['date'])) { ?>
                                            <div>
                                                <i class="fa fa-calendar" aria-hidden="true"></i><span class="comment-date"><?php echo $recordRow[$this->layoutPath['date']]; ?></span>
                                            </div>
                                            <?php  } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="media-content float-left col-md-12 pr0 pl0">
                                    <?php 
                                    if (isset($this->layoutPath['body'])) {
                                        echo '<p>' . $recordRow[$this->layoutPath['body']] . '</p>'; 
                                    }  ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php  } 
                break;
            case 'sv_tab': ?>
                <div class="tabbable-line" style="margin: 10px 0 0 20px;">
                    <ul class="nav nav-tabs nav-tabs-header">
                        <?php 
                        foreach ($this->recordList as $key => $recordRow) { 
                            if (isset($this->layoutPath['title'])) { 
                        ?>
                            <li data-id="<?php echo (isset($this->layoutPath['id']) && isset($recordRow[$this->layoutPath['id']])) ? $recordRow[$this->layoutPath['id']] : '' ?>">
                                <a href="#" data-toggle="tab" class="<?php echo ($key == 0) ? 'active' : '' ?>" aria-expanded="true"><?php echo isset($recordRow[$this->layoutPath['title']]) ? $recordRow[$this->layoutPath['title']] : ''; ?></a>
                            </li>
                        <?php    
                            } 
                        } 
                        ?>
                    </ul>
                </div>
            <?php
            break; 
            case 'sv_education': 
                
                foreach ($this->recordList as $recordRow) {
                $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
                ?>
                    <div class="media shadow sv_education selected-row-link folder-link" data-row-data="<?php echo $rowJson; ?>" data-id="<?php echo (isset($this->layoutPath['id']) && isset($recordRow[$this->layoutPath['id']])) ? $recordRow[$this->layoutPath['id']] : '' ?>">
                        <div class="media-body">
                            <div class="media-heading">
                                <a href="javascript:;"></a>
                                <div class="media-title float-left col-md-12 pr0 pl0">
                                    <a href="javascript:;" class="float-left mr10">
                                        <i class="fa <?php echo (isset($this->gridLayout['DEFAULT_IMAGE']) && $this->gridLayout['DEFAULT_IMAGE'] && preg_match('/fa-/', $this->gridLayout['DEFAULT_IMAGE'])) ? 'fa '. $this->gridLayout['DEFAULT_IMAGE'] : 'fa fa-bank' ?>"></i>
                                    </a>
                                    <?php if (isset($this->layoutPath['title'])) { ?>
                                        <div class="caption caption mediacaption-title">
                                            <span class="caption-subject font-weight-bold card-subject-customed card-subject-customed-name">
                                                <?php echo isset($recordRow[$this->layoutPath['title']]) ? $recordRow[$this->layoutPath['title']] : ''; ?>
                                            </span>
                                            <?php  if (isset($this->layoutPath['date'])) { ?>
                                            <div>
                                                <i class="fa fa-calendar education-date" aria-hidden="true"></i><span class="education-date"><?php echo $recordRow[$this->layoutPath['date']]; ?></span>
                                            </div>
                                            <?php  } ?>
                                            <?php  if (isset($this->layoutPath['code'])) { ?>
                                            <div>
                                                <span class="education-code"><?php echo $recordRow[$this->layoutPath['code']]; ?></span>
                                            </div>
                                            <?php  } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="media-content float-left col-md-12 pr0 pl0">
                                    <?php 
                                    if (isset($this->layoutPath['body'])) {
                                        echo '<p>' . $recordRow[$this->layoutPath['body']] . '</p>'; 
                                    }  ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php  }  ?>
        
                    <style type="text/css">
                        
                        .selected-row-link:hover {
                            background: #5cb1da47;
                            cursor: pointer;
                        }
                        
                        <?php if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) { ?>
                            .sv_education {
                                margin-left: 15px;
                                margin-right: 15px;
                            }
                        <?php } ?>
                        
                        .sv_education .media-body p {
                            text-align: justify;
                        }
                        .sv_education .media-body .mediacaption-title {
                            line-height: 21px;
                        }
                        .sv_education .media-title a {
                            background-color: #CCC;
                            padding: 15px;
                        }
                        .sv_education .media-title a i{
                            font-size: 35px;
                            line-height: 30px;
                            color: #FFF;
                        }
                        .sv_education .education-code, .sv_education .education-date{
                            color: #848282;
                            padding-right: 10px;
                        }
                    </style>
                <?php 
                break;
            case 'sv_address': 
                
                foreach ($this->recordList as $recordRow) {
                $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
                ?>
                    <div class="media shadow sv_address selected-row-link folder-link" data-row-data="<?php echo $rowJson; ?>" data-id="<?php echo (isset($this->layoutPath['id']) && isset($recordRow[$this->layoutPath['id']])) ? $recordRow[$this->layoutPath['id']] : '' ?>">
                        <div class="media-body">
                            <div class="media-heading">
                                <a href="javascript:;"></a>
                                <div class="media-title float-left col-md-5 pr0 pl0">
                                    <a href="javascript:;" class="float-left mr10">
                                        <i class="fa <?php echo (isset($this->gridLayout['DEFAULT_IMAGE']) && $this->gridLayout['DEFAULT_IMAGE'] && preg_match('/fa-/', $this->gridLayout['DEFAULT_IMAGE'])) ? 'fa '. $this->gridLayout['DEFAULT_IMAGE'] : 'fa fa-home' ?>"></i>
                                    </a>
                                    <?php  if (isset($this->layoutPath['title'])) { ?>
                                        <div class="caption caption mediacaption-title">
                                            <span class="caption-subject font-weight-bold card-subject-customed card-subject-customed-name">
                                                <?php echo isset($recordRow[$this->layoutPath['title']]) ? $recordRow[$this->layoutPath['title']] : ''; ?>
                                            </span>
                                            <?php  if (isset($this->layoutPath['name1'])) { ?>
                                            <div>
                                                <span class="education-date"><?php echo $recordRow[$this->layoutPath['name1']]; ?></span>
                                            </div>
                                            <?php  } ?>
                                            <?php  if (isset($this->layoutPath['name2'])) { ?>
                                            <div>
                                                <span class="education-code"><?php echo $recordRow[$this->layoutPath['name2']]; ?></span>
                                            </div>
                                            <?php  } ?>
                                            <?php  if (isset($this->layoutPath['name3'])) { ?>
                                            <div>
                                                <span class="education-code" style="margin-left: 72px;"><?php echo $recordRow[$this->layoutPath['name3']]; ?></span>
                                            </div>
                                            <?php  } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="media-title float-left col-md-7 pr0 pl0">
                                    <?php  if (isset($this->layoutPath['title'])) { ?>
                                        <div class="caption caption mediacaption-title">
                                            <?php  if (isset($this->layoutPath['name4'])) { ?>
                                            <div>
                                                <i class="fa fa-phone sv_address_phone"></i> <span class="education-date"><?php echo $recordRow[$this->layoutPath['name4']]; ?></span>
                                            </div>
                                            <?php  } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="media-content float-left col-md-12 pr0 pl0">
                                    <?php 
                                    if (isset($this->layoutPath['body'])) {
                                        echo '<p>' . $recordRow[$this->layoutPath['body']] . '</p>'; 
                                    }  ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php  }  ?>
        
                    <style type="text/css">
                        
                        .selected-row-link:hover {
                            background: #5cb1da47;
                            cursor: pointer;
                        }
                        
                        <?php if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) { ?>
                            .sv_address {
                                margin-left: 30px;
                                margin-right: 15px;
                            }
                        <?php } ?>
                        
                        .sv_address .media-body p {
                            text-align: justify;
                        }
                        .sv_address .media-body .mediacaption-title {
                            line-height: 21px;
                        }
                        .sv_address .media-title a {
                            background-color: #59cea1;
                            padding: 15px;
                        }
                        .sv_address .media-title a i{
                            font-size: 35px;
                            line-height: 30px;
                            color: #FFF;
                        }
                        .sv_address .education-code, .sv_address .education-date{
                            color: #848282;
                            padding-right: 10px;
                        }
                        .sv_address .sv_address_phone {
                            background-color: #00abe5;
                            color: #fff;
                            padding: 2px 3px 0px 3px;
                            font-size: 11px;
                        }
                    </style>
                <?php 
                break;
            default:
                break;
        } 
        ?>
    </div>
    <div class="lightbox">
        <figure></figure>
    </div>
</div>


