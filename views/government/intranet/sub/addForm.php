<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'saveform_' . $this->uniqId, 'method' => 'post', 'enctype' => 'multipart/form-data')); 
    echo Form::hidden(array('name' => 'typeId', 'data-path' => 'typeId', 'value' => (issetParam($this->issingle) == '1' ? '9999' : $this->selectedRow['posttypeid']))); 
    echo Form::hidden(array('name' => 'saveProcessCode', 'value' => $this->saveProcessCode)); 
    $sessionUserId = Ue::sessionUserId();
    $currentDate = Date::currentDate('Y-m-d');
    
    if ($this->isEdit) {
        includeLib('Compress/Compression');
        $decompressContent = Compression::encode_string_array($this->mainData);
        
        echo Form::hidden(array('name' => 'isedit', 'value' => '1'));
        echo Form::hidden(array('name' => 'groupId', 'value' => issetParam($this->mainData['groupid'])));
        echo Form::hidden(array('name' => 'id', 'value' => $this->mainData['id']));
        echo Form::hidden(array('name' => 'mainData', 'value' => $decompressContent));
    }
    
    $dnone = ($this->selectedRow['windowtypeid'] == '1' || $this->selectedRow['windowtypeid'] == '4'  || $this->selectedRow['windowtypeid'] == '5') ? '' : 'd-none'; 
    $dnone_answer = ($this->selectedRow['windowtypeid'] == '2') ? '' : 'd-none'; 
    
    if (isset($this->selectedRow['postcategoryid']) && $this->selectedRow['postcategoryid']) {
        echo Form::hidden(array('name' => 'categoryId', 'data-path' => 'categoryId', 'value' => $this->selectedRow['postcategoryid']));
    }
    else if (issetParam($this->saveProcessCode) == '') {
        if ($this->groupOption) { ?>
            <div class="d-flex flex-row align-items-center mb-2">
                <div class="col-1 p-0">
                        <span class="mr-2">Бүлэг</span>
                    </div>
                <div class="col-11 p-0">
                    <?php
                    echo Form::select(
                            array(
                                'name' => 'categoryId',
                                'id' => 'categoryId',
                                'class' => 'form-control select2',
                                'data' => $this->groupOption,
                                'op_value' => 'id',
                                'op_text' => 'name',
                                'value' => (issetParam($this->selectedRow['postcategoryid']) !== '') ? $this->selectedRow['postcategoryid'] : (issetParam($this->paramData['categoryid']) ? $this->paramData['categoryid'] : '')
                            )
                        );

                    ?>
                </div>
            </div>
        <?php
        }
    } ?>
    
    <div class="<?php echo issetParam($this->issingle) == '1' ? 'd-none' : 'd-flex' ?> flex-row align-items-center mb-2">
        <div class="<?php echo (issetParam($this->saveProcessCode) == '') ? 'col-1' : 'col-2' ?> p-0">
            <span class="mr-2"><?php echo (issetParam($this->saveProcessCode) == '') ? 'Гарчиг' : 'Бүлгийн нэр' ?> <span class="text-red">*</span></span>
        </div>
        <div class="<?php echo (issetParam($this->saveProcessCode) == '') ? 'col-11' : 'col-10' ?> p-0">
            <input type="text" name="<?php echo issetParam($this->issingle) == '1' ? 'subject__' : 'subject' ?>" placeholder="" class="form-control" value="<?php echo isset($this->mainData['description']) ? $this->mainData['description'] : '' ?>">
        </div>
    </div>

    <div class="<?php echo issetParam($this->issingle) == '1' ? '' : 'd-none' ?> flex-row align-items-center mb-2">
        <div class="col-2 p-0">
            <span class="mr-2"><?php echo 'YoutubeId'; ?></span>
        </div>
        <div class="<?php echo (issetParam($this->saveProcessCode) == '') ? 'col-11' : 'col-10' ?> p-0">
            <input type="text" name="youtubeid" class="form-control" value="<?php echo isset($this->mainData['youtubeid']) ? 'www.youtube.com/embed/'.$this->mainData['youtubeid'] : '' ?>">
        </div>
    </div>

    <textarea rows="12" class="form-control <?php echo issetParam($this->issingle) == '1' ? '' : $dnone; ?>" id="body_<?php echo $this->uniqId ?>" name="<?php echo issetParam($this->issingle) == '1' ? 'subject' : 'body' ?>" placeholder="Тайлбар"><?php echo isset($this->mainData['longdescr']) ? $this->mainData['longdescr'] : '' ?></textarea>

    <?php if ($this->selectedRow['windowtypeid'] == '2') {  ?>
        <div class="questiondv_<?php echo $this->otherUniqId ?> ">
            <?php echo isset($this->addinDiv) ? $this->addinDiv : '' ?>
        </div>
    <?php } ?>
    <?php if (issetParam($this->selectedRow['createprocesscode']) !== 'LIS_POLL_DV_001') { ?>
        
    <div class="<?php echo issetParam($this->issingle) == '1' ? 'd-none' : '' ?> org-choice">
        <div class="form-group mb-0">
            <div class="form-check form-check-inline">
                <label class="form-check-label align-items-center">
                    <input type="radio" name="mainTypeId" value="5" <?php echo ($this->isEdit) ? (isset($this->mainData['privacytype']) && $this->mainData['privacytype'] == 'public') ? 'checked="checked"' : '' : 'checked="checked"'; ?>>
                    <?php echo Lang::line('TYPE_PUBLIC_001') ?>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label align-items-center">
                    <input type="radio" name="mainTypeId" value="4" <?php echo ($this->isEdit) ? (isset($this->mainData['privacytype']) && ($this->mainData['privacytype'] == 'private' || $this->mainData['privacytype'] == 'closed')) ? 'checked="checked"' : '' : ''; ?>>
                    <?php echo Lang::line('TYPE_PRIVATE_002') ?>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label align-items-center  <?php echo (issetParam($this->saveProcessCode) == '') ? '' : 'd-none' ?> ">
                    <input type="radio" name="mainTypeId" value="1" <?php echo ($this->isEdit) ? (isset($this->mainData['privacytype']) && $this->mainData['privacytype'] == 'user') ? 'checked="checked"' : '' : ''; ?>>
                    <?php echo Lang::line('TYPE_USER_003') ?>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label align-items-center  <?php echo (issetParam($this->saveProcessCode) == '') ? '' : 'd-none' ?> ">
                    <input type="radio" name="mainTypeId" value="2" <?php echo ($this->isEdit) ? (isset($this->mainData['privacytype']) && $this->mainData['privacytype'] == 'department') ? 'checked="checked"' : '' : ''; ?>>
                    <?php echo Lang::line('TYPE_DEPARTMENT_004') ?>
                </label>
            </div>
            <div class="form-check form-check-inline d-none">
                <label class="form-check-label align-items-center">
                    <input type="radio" name="mainTypeId" value="3" <?php echo ($this->isEdit) ? (isset($this->mainData['privacytype']) && $this->mainData['privacytype'] == 'userDepartment') ? 'checked="checked"' : '' : ''; ?>>
                    <?php echo Lang::line('TYPE_USERDEPARTMENT_005') ?>
                </label>
            </div>
        </div>
    </div>
    <div class="<?php echo ($this->isEdit && issetParam($this->mainData['int_post_groups_dv']) || issetParam($this->selectedRow['posttypeid']) == '9988') ? 'd-flex' : 'd-none' ?> flex-row mb10 userIds<?php echo $this->uniqId ?>">
        <div class="w-100">
            <div class="input-group">
                <select id="param[userIds]" name="param[userIds][]" class="form-control form-control-sm dropdownInput select2 bp-field-with-popup-combo select2-offscreen" data-path="userIds" data-field-name="userIds" data-row-data="{&quot;META_DATA_ID&quot;:&quot;1565070936581248&quot;,&quot;ATTRIBUTE_ID_COLUMN&quot;:&quot;id&quot;,&quot;ATTRIBUTE_CODE_COLUMN&quot;:null,&quot;ATTRIBUTE_NAME_COLUMN&quot;:&quot;name&quot;,&quot;PARAM_REAL_PATH&quot;:&quot;userIds&quot;,&quot;PROCESS_META_DATA_ID&quot;:&quot;1567154435267&quot;,&quot;CHOOSE_TYPE&quot;:&quot;multicomma&quot;}" multiple="multiple" data-isclear="0" data-placeholder="- Хэрэглэгч -" tabindex="-1" style="">
                    <?php 
                    $countUser = 0;
                    if ($this->isEdit && issetParam($this->mainData['int_post_groups_dv'])) {
                        foreach ($this->mainData['int_post_groups_dv'] as $row) {
                            $drowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                            if ($row['userid'] !== $sessionUserId) {
                                $countUser++;
                                echo '<option selected="selected" value="'. $row['userid'] .'" data-row-data="'. $drowJson .'">'. $row['personname'] .'</option>';
                            }
                        }
                    } ?>
                </select> 
                <span class="input-group-append"> 
                    <button class="btn btn-primary mr0" type="button" data-lookupid="1565070936581248" data-paramcode="userIds" data-processid="1567154435267" data-choosetype="multicomma" data-idfield="id" data-namefield="name" onclick="bpBasketDvWithPopupCombo(this);"><?php echo ($countUser) ? $countUser : '..' ?></button>
                    <button class="btn btn-danger mr0 removebtn" type="button" data-lookupid="1565070936581248" onclick="bpRemoveAllBasketWithPopupCombo(this);" style="display: none;"><i class="fa fa-trash"></i></button>
                </span>
            </div>
        </div>
    </div>
    <div class="<?php echo ($this->isEdit && issetParam($this->mainData['int_post_groups_department_dv'])) ? 'd-flex' : 'd-none' ?> flex-row mb10 departmentIds<?php echo $this->uniqId ?>">
        <div class="w-100">
            <div class="input-group">
                <select id="param[departmentIds]" name="param[departmentIds][]" class="form-control form-control-sm dropdownInput select2 bp-field-with-popup-combo select2-offscreen" data-path="departmentIds" data-field-name="departmentIds" data-row-data="{&quot;META_DATA_ID&quot;:&quot;1565070690138433&quot;,&quot;ATTRIBUTE_ID_COLUMN&quot;:&quot;id&quot;,&quot;ATTRIBUTE_CODE_COLUMN&quot;:null,&quot;ATTRIBUTE_NAME_COLUMN&quot;:&quot;departmentName&quot;,&quot;PARAM_REAL_PATH&quot;:&quot;departmentIds&quot;,&quot;PROCESS_META_DATA_ID&quot;:&quot;1567154435267&quot;,&quot;CHOOSE_TYPE&quot;:&quot;multicomma&quot;}" multiple="multiple" data-isclear="0" data-placeholder="- Хэлтэс нэгж -" tabindex="-1">
                    <?php 
                    $countDep = 0;
                    if ($this->isEdit && issetParam($this->mainData['int_post_groups_department_dv'])) {
                        foreach ($this->mainData['int_post_groups_department_dv'] as $row) {
                            $countDep++;
                            $drowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                            echo '<option selected="selected" value="'. $row['departmentid'] .'" data-row-data="'. $drowJson .'">'. $row['departmentname'] .'</option>';
                        }
                    } ?>
                </select> 
                <span class="input-group-append"> 
                    <button class="btn btn-primary mr0" type="button" data-lookupid="1565070690138433" data-paramcode="departmentIds" data-processid="1567154435267" data-choosetype="multicomma" data-idfield="id" data-namefield="departmentName" onclick="bpBasketDvWithPopupCombo(this);"><?php echo ($countDep) ? $countDep : '..' ?></button>
                    <button class="btn btn-danger mr0 removebtn" type="button" data-lookupid="1565070936581248" onclick="bpRemoveAllBasketWithPopupCombo(this);" style="display: none;"><i class="fa fa-trash"></i></button>
                </span>
            </div>
        </div>
    </div>
    <div class="<?php echo issetParam($this->issingle) == '1' ? 'd-none' : ((issetParam($this->saveProcessCode) == '') ? 'd-flex' : 'd-none') ?> flex-row">
        <?php if (isset($this->selectedRow['posttypeid']) && $this->selectedRow['posttypeid'] == '2') {
            echo Form::hidden(array('name' => 'isComment', 'value' => '1'));
        } else { ?>
            <div class="advanced-control mr-3">
                <div class="d-flex">
                    <div class="checkbox-list mr-1">
                        <input type="checkbox" name="isComment" id="isComment" value="1" <?php echo ($this->isEdit) ? (isset($this->mainData['iscomment']) && $this->mainData['iscomment'] == '1') ? 'checked="checked"' : '' : 'checked="checked"'; ?>>
                    </div>
                    <label for="isComment">
                        <?php echo Lang::line('Comment хүлээж авах эсэх'); ?>
                    </label>
                </div>
            </div>
        <?php } ?>
        <div class="advanced-control mr-3">
            <div class="d-flex">
                <div class="checkbox-list mr-1">
                    <input type="checkbox" name="isLike" id="isLike" value="1" <?php echo ($this->isEdit) ? (isset($this->mainData['islike']) && $this->mainData['islike'] == '1') ? 'checked="checked"' : '' : 'checked="checked"'; ?>>
                </div>
                <label for="isLike">
                    <?php echo Lang::line('Like хүлээж авах эсэх'); ?>
                </label>
            </div>
        </div>
        <?php if (isset($this->selectedRow['windowtypeid']) && $this->selectedRow['windowtypeid'] != '4' && $this->selectedRow['windowtypeid'] != '5') { ?>
            <div class="advanced-control mr-3">
                <div class="d-flex">
                    <div class="checkbox-list mr-1">
                        <input type="checkbox" name="isUrgent" id="isUrgent" value="1" <?php echo ($this->isEdit) ? (isset($this->mainData['isurgent']) && $this->mainData['isurgent'] == '1') ? 'checked="checked"' : '' : ''; ?>>
                    </div>
                    <label for="isUrgent">
                        <?php echo Lang::line('Шуурхай эсэх'); ?>
                    </label>
                </div>
            </div>
        <?php } ?>
        <div class="advanced-control mr-3">
            <div class="d-flex">
                <div class="checkbox-list mr-1">
                    <input type="checkbox" name="isPin" id="isPin" onclick="isPinCheckedFnc_<?php echo $this->uniqId ?>(this)" value="1" <?php echo ($this->isEdit) ? (isset($this->mainData['ispin']) && $this->mainData['ispin'] == '1') ? 'checked="checked"' : '' : ''; ?>>
                </div>
                <label for="isPin">
                    <?php echo Lang::line('Онцлох эсэх'); ?>
                </label>
            </div>
        </div>
        <div class="advanced-control mr-3 ">
            <div class="dateElement input-group enddate_<?php echo $this->uniqId ?> <?php echo ($this->isEdit && isset($this->mainData['ispin']) && $this->mainData['ispin'] == '1') ? 'd-flex' : 'd-none' ?>" style="top: -5px;">
                <input type="text" name="pindate" placeholder="Онцлох д/огноо" class="form-control dateInit" value="<?php echo ($this->isEdit && issetParam($this->mainData['pindate'])) ? $this->mainData['pindate'] : '' ?>">
                <span class="input-group-btn input-group-append"><button tabindex="-1" onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button></span>
            </div>
        </div>
    </div>
    <div class="row mt-2 mb-2">
        <div class="col-md-12 d-flex">
            <label for="bp_imgurl" class=" mr-2">
                <?php echo Lang::line('Зураг сонгох'); ?>
            </label>
            <input type="file" name="bp_imgurl" id="bp_imgurl" class="" />
        </div>
    </div>
    <?php } ?>
    <div class="row">
        <div class="<?php echo (issetParam($this->saveProcessCode) == '') ? 'col-2' : 'col-3' ?> ">
            <div class="mt-2 mb10">
                <a href="javascript:;" class="btn btn-sm btn-outline bg-primary border-primary text-primary-800 fileinput-button" title="Файл нэмэх">
                    <i class="icon-attachment mr-1"></i> 
                    <?php if($this->selectedRow['posttypeid'] == '5') { ?>
                        Зураг нэмэх
                    <?php } else { ?>
                        <?php echo (issetParam($this->saveProcessCode) == '') ? 'Файл нэмэх' : 'Бүлгэмийн зураг' ?> 
                    <?php } ?>
                    <input type="file" name="bp_file[]" class="" <?php echo (issetParam($this->saveProcessCode) == '') ? 'multiple' : '' ?> onchange="onChangeAttachFIleAddMode<?php echo $this->uniqId ?>(this, '<?php echo (issetParam($this->saveProcessCode) == '') ? '0' : '1' ?>')" />
                </a>
            </div>
        </div>
        <div class="<?php echo (issetParam($this->saveProcessCode) == '') ? 'col-10' : 'col-9' ?> ">
            <div class="<?php echo ($this->selectedRow['windowtypeid'] == '2') ? 'd-flex' : 'd-none' ?> flex-row align-items-center mt-2 mb-2">
                <span class="mr-2">Огноо</span>
                <div class="dateElement input-group" style="max-width: 190px !important;">
                    <input type="text" name="startdate" placeholder="Эхлэх огноо" class="form-control datetimeInit" tabindex value="<?php echo ($this->isEdit && issetParam($this->mainData['startdate'])) ? $this->mainData['startdate'] : '' ?>"  <?php echo ($this->selectedRow['windowtypeid'] == '2') ? 'required="required"' : '' ?>>
                    <span class="input-group-btn input-group-append"><button tabindex="-1" onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button></span>
                </div>
                <div class="dateElement input-group ml-1" style="max-width: 190px !important;">
                    <input type="text" name="enddate" placeholder="Дуусах огноо" class="form-control datetimeInit" tabindex value="<?php echo ($this->isEdit && issetParam($this->mainData['enddate'])) ? $this->mainData['enddate'] : '' ?>"  <?php echo ($this->selectedRow['windowtypeid'] == '2') ? 'required="required"' : '' ?>>
                    <span class="input-group-btn input-group-append"><button tabindex="-1" onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button></span>
                </div>
                <?php if (issetParam($this->selectedRow['createprocesscode']) === 'LIS_POLL_DV_001') { ?>
                    <div class="d-flex flex-row">
                        <div class="advanced-control ml-3">
                            <div class="d-flex">
                                <div class="checkbox-list mr-1">
                                    <input type="checkbox" name="isPublish" id="isPublish" value="1" <?php echo ($this->isEdit) ? (isset($this->mainData['ispublished']) && $this->mainData['ispublished'] == '1') ? 'checked="checked"' : '' : ''; ?>>
                                </div>
                                <label for="isPublish">
                                    <?php echo Lang::line('Нийтлэх эсэх'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="card-body">
        <ul class="row list-inline mb-0 list-view-file-new filelist-<?php echo $this->uniqId ?>">
            <?php if (isset($this->mainData['int_content_map_dv'])) {
                foreach ($this->mainData['int_content_map_dv'] as $key => $content) { 
                    $fileview = '';
                    switch ($content['int_content_dv']['fileextension']) {
                        case 'png':
                        case 'gif':
                        case 'jpeg':
                        case 'pjpeg':
                        case 'jpg':
                        case 'x-png':
                        case 'bmp':
                            $icon = "icon-file-picture text-danger-400";
                            $fileview = '<img src="'. $content['int_content_dv']['physicalpath'] .'" class="w-100">';
                            break;
                        case 'zip':
                        case 'rar':
                            $icon = "icon-file-zip text-danger-400";
                            break;
                        case 'pdf':
                            $icon = "icon-file-pdf text-danger-400";
                            $fileview = '<iframe src="'.URL.'api/pdf/web/viewer.html?file='.$content['int_content_dv']['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px;" id="iframe-detail-'.$this->uniqId.'"></iframe>';
                            break;
                        case 'mp3':
                            $icon = "icon-file-music text-danger-400";
                            break;
                        case 'mp4':
                            $icon = "icon-file-video text-danger-400";
                            break;
                        case 'doc':
                        case 'docx':
                            $icon = "icon-file-word text-blue-400";
                            $fileview = '<iframe id="viewFileMain" src="'.CONFIG_FILE_VIEWER_ADDRESS.'DocEdit.aspx?showRb=0&url='.URL . $content['int_content_dv']['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>';
                            break;
                        case 'ppt':
                            $icon = "icon-file-presentation text-danger-400";
                            $fileview = '<iframe id="file_viewer_<?php echo $this->rowId; ?>" src="'. CONFIG_FILE_VIEWER_ADDRESS .'documentviewer.aspx?showRb=0&url='. URL . $content['int_content_dv']['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px;"></iframe>';
                        case 'pptx':
                            $icon = "icon-file-presentation text-danger-400";
                            $fileview = '<iframe id="file_viewer_<?php echo $this->rowId; ?>" src="'. CONFIG_FILE_VIEWER_ADDRESS .'documentviewer.aspx?showRb=0&url='. URL . $content['int_content_dv']['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px;"></iframe>';
                            break;
                        case 'xls':
                        case 'xlsx':
                            $icon = "icon-file-excel text-green-400";
                            $fileview = '<iframe id="viewFileMain" src="'.CONFIG_FILE_VIEWER_ADDRESS.'SheetEdit.aspx?showRb=0&url='.URL . $content['int_content_dv']['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>';
                            break;
                        default:
                            $icon = "icon-file-empty text-danger-400";
                            break;
                    }
                    ?>
                    <div class="col-4 pl-0">
                        <li class="list-inline-item w-100">
                            <div class="card bg-light py-1 px-2 mt-2 mb-0 filecontent-tag">
                                <input type="hidden" name="contentId[]" value="<?php echo $content['contentid'] ?>" />
                                <div class="media my-1">
                                    <div class="mr-2 align-self-center"><i class="<?php echo $icon; ?> icon-2x top-0"></i></div>
                                    <div class="media-body">
                                        <div class="font-weight-semibold"><?php echo $content['int_content_dv']['filename'] ?></div>
                                        <ul class="list-inline list-inline-condensed mb-0">
                                            <li class="list-inline-item text-muted"><?php echo formatSizeUnits($content['int_content_dv']['filesize']) ?></li>
                                            <li class="list-inline-item"><a href="javascript:void(0);" onclick="dataViewFileViewer(this, '1', '<?php echo $content['int_content_dv']['fileextension'] ?>', '<?php echo $content['int_content_dv']['physicalpath'] ?>',  '<?php echo URL . $content['int_content_dv']['physicalpath'] ?>', 'undefined');" >Харах</a></li>
                                            <li class="list-inline-item"><a href="javascript:void(0);" onclick="removecontent_<?php echo $this->uniqId ?>(this)">Устгах</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </div>
                <?php }
            } ?>
        </ul>
    </div>
<input name="image" type="file" id="upload_<?php echo $this->uniqId ?>" class="d-none" onchange="">
<input name="ischanged" type="hidden" value="<?php echo issetParam($this->issingle) == '1' ? '1' : '0' ?>">
<?php echo Form::close(); ?>
    
<script type="text/javascript">
    
    $(function () {
        
        <?php if ($this->isEdit && issetParam($this->selectedRow['posttypeid']) != '9988') { ?>
            
            var mainSelector = $('#saveform_<?php echo $this->uniqId ?>');
            switch ($('#saveform_<?php echo $this->uniqId ?>').find('input[name="mainTypeId"][checked="checked"]').val()) {
                case '1':
                    mainSelector.find('.userIds<?php echo $this->uniqId ?>').addClass('d-flex').removeClass('d-none');
                    mainSelector.find('.departmentIds<?php echo $this->uniqId ?>').removeClass('d-flex').addClass('d-none');
                    break;
                case '2':
                    mainSelector.find('.userIds<?php echo $this->uniqId ?>').removeClass('d-flex').addClass('d-none');
                    mainSelector.find('.departmentIds<?php echo $this->uniqId ?>').addClass('d-flex').addClass('d-none');
                    break;
                case '3':
                    mainSelector.find('.userIds<?php echo $this->uniqId ?>').addClass('d-flex').removeClass('d-none');
                    mainSelector.find('.departmentIds<?php echo $this->uniqId ?>').addClass('d-flex').addClass('d-none');
                    break;
                default: 
                    mainSelector.find('.userIds<?php echo $this->uniqId ?>').addClass('d-none').removeClass('d-flex');
                    mainSelector.find('.departmentIds<?php echo $this->uniqId ?>').addClass('d-none').removeClass('d-flex');
                    break;
            }
        <?php } ?>
        
        $('#saveform_<?php echo $this->uniqId ?>').find('input.dateInit').on('changeDate', function(){
            var $thisval = new Date($(this).val());
            var $thisEndDateVal = new Date('<?php echo $currentDate ?>');
            
            if ($thisval.getTime() < $thisEndDateVal.getTime()) {

                PNotify.removeAll();
                new PNotify({
                    title: '<?php echo Lang::line('warning') ?>',
                    text: '<?php echo Lang::line('WRONG_DATE_GOVERMENT_POST') ?>',
                    type: 'warning',
                    sticker: false
                });
                
                $(this).datepicker('update', '<?php echo $currentDate ?>');
            }
        });
    });
    
    
    $('body').on('change', '#saveform_<?php echo $this->uniqId ?> select[id="categoryId"], #saveform_<?php echo $this->uniqId ?> input[name="isUrgent"], #saveform_<?php echo $this->uniqId ?> input[id="isComment"], #saveform_<?php echo $this->uniqId ?> input[id="isLike"], #saveform_<?php echo $this->uniqId ?> input[id="isPin"]', function () {
        $('#saveform_<?php echo $this->uniqId ?>').find('input[name="ischanged"]').val('1');
    });
    
    $('body').on('click', 'input[name="mainTypeId"]', function () {
        var $this = $(this);
        var $id = $this.val();
        
        var mainSelector = $('#saveform_<?php echo $this->uniqId ?>');
        
        $this.closest('.create_reg_btn').find('button.active').removeClass('active');
        $this.addClass('active');
        <?php if ($this->isEdit && issetParam($this->selectedRow['posttypeid']) != '9988') { ?>
            $('select[data-path="departmentIds"]').select2('val', '');
            $('select[data-path="userIds"]').select2('val', '');
        <?php } ?>
        mainSelector.find('.departmentIds<?php echo $this->uniqId ?>').find('button[data-paramcode="departmentIds"]').html('..');
        mainSelector.find('.userIds<?php echo $this->uniqId ?>').find('button[data-paramcode="userIds"]').html('..');
        mainSelector.find('.userIds<?php echo $this->uniqId ?>').find('button.removebtn').hide();
        mainSelector.find('.departmentIds<?php echo $this->uniqId ?>').find('button.removebtn').hide();
        switch ($id) {
            case '1':
                mainSelector.find('.userIds<?php echo $this->uniqId ?>').addClass('d-flex').removeClass('d-none');
                mainSelector.find('.departmentIds<?php echo $this->uniqId ?>').removeClass('d-flex').addClass('d-none');
                break;
            case '2':
                mainSelector.find('.userIds<?php echo $this->uniqId ?>').removeClass('d-flex').addClass('d-none');
                mainSelector.find('.departmentIds<?php echo $this->uniqId ?>').addClass('d-flex').addClass('d-none');
                break;
            case '3':
                mainSelector.find('.userIds<?php echo $this->uniqId ?>').addClass('d-flex').removeClass('d-none');
                mainSelector.find('.departmentIds<?php echo $this->uniqId ?>').addClass('d-flex').addClass('d-none');
                break;
            default: 
            <?php if($this->saveProcessCode !== 'NTR_SOCIAL_GROUP_DV_001') { ?>
                mainSelector.find('.userIds<?php echo $this->uniqId ?>').addClass('d-none').removeClass('d-flex');
                mainSelector.find('.departmentIds<?php echo $this->uniqId ?>').addClass('d-none').removeClass('d-flex');
            <?php } ?>
                break;
        }
    });

    var mainSelector = $('#saveform_<?php echo $this->uniqId ?>');
    <?php // if($this->saveProcessCode == 'NTR_SOCIAL_GROUP_DV_001') { echo "mainSelector.find('.userIds". $this->uniqId ."').addClass('d-flex').removeClass('d-none');"; } ?>
    
    $('body').on('change', '#saveform_<?php echo $this->uniqId ?> input#advanced', function () {
        $('#saveform_<?php echo $this->uniqId ?> .advanced-control').addClass('d-none').removeClass('d-flex');
        
        if (this.checked) {
            $('#saveform_<?php echo $this->uniqId ?> .advanced-control').removeClass('d-none').addClass('d-flex');
        }
        
    });
    
    function removeAnswer_<?php echo $this->otherUniqId ?> (element) {
        var $this = $(element);
        var $mainSelector = $this.closest('.main-selector').find('.checkbox-option');
        
        if ($mainSelector.find('input[data-path="answerTxt"]').length == 1) {
            return;
        }
        
        $this.closest('.data-selectoption').remove();
        
        var index = 1;
        $mainSelector.find('input[data-path="answerTxt"]').each(function (i, row) {
            $(row).attr('placeholder', 'Хариулт ' + index);
            index++;
        });
        
    }
    
    function addAnswer_<?php echo $this->otherUniqId ?> (element, uniqId) {
        var $mainSelector = $(element).closest('.main-selector').find('.checkbox-option');
        
        var $mainHtml = '<div class="form-group pt-2 mb-0 data-selectoption">' +
                            '<div class="form-check">' +
                                '<label class="form-check-label d-flex justify-content-between align-items-center pl15">' +
                                    '<input type="text" style="border-bottom: 1px solid #2196f3 !important" name="answerTxt['+ $(element).attr('data-order') +'][]" placeholder="Хариулт '+  ($mainSelector.children().length+1) +'" data-path="answerTxt" class="input-text-style2 imp ml-1 focuss">' +
                                    '<a href="javascript:;" onclick="removeAnswer_<?php echo $this->otherUniqId ?>(this)">' +
                                        '<i class="icon-cross2 text-gray"></i>' +
                                    '</a>' +
                                '</label>' +
                            '</div>' +
                        '</div>';

        $mainSelector.find('.focuss').css("border-color", "");
               
        $mainSelector.append($mainHtml);
        $mainSelector.find('.focuss').focus();
    }
    
    function isPinCheckedFnc_<?php echo $this->uniqId ?> (element) {
        $('.enddate_<?php echo $this->uniqId ?>').find('input').val('');
        if (element.checked) {
            $('.enddate_<?php echo $this->uniqId ?>').removeClass('d-none').addClass('d-flex')
        } else {
            $('.enddate_<?php echo $this->uniqId ?>').removeClass('d-flex').addClass('d-none')
        }
    }
    
    function select2style_<?php echo $this->otherUniqId ?>(element) {
    
        /************************************ SELECT OPTION STYLE START **********************************/
        
        var x = document.getElementsByClassName(element);
        for (i = 0; i < x.length; i++) {

            var selElmnt = x[i].getElementsByTagName("select")[0];
            a = document.createElement("DIV");
            a.setAttribute("class", "select-selected");
            a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
            x[i].appendChild(a);
            b = document.createElement("DIV");
            b.setAttribute("class", "select-items select-hide");

            for (j = 1; j < selElmnt.length; j++) {
                c = document.createElement("DIV");
                c.innerHTML = selElmnt.options[j].innerHTML;
                c.addEventListener("click", function(e) {
                    var y, i, k, s, h;
                    s = this.parentNode.parentNode.getElementsByTagName("select")[0];
                    h = this.parentNode.previousSibling;
                    for (i = 0; i < s.length; i++) {
                        if (s.options[i].innerHTML == this.innerHTML) {
                            s.selectedIndex = i;
                            h.innerHTML = this.innerHTML;
                            y = this.parentNode.getElementsByClassName("same-as-selected");
                            for (k = 0; k < y.length; k++) {
                                y[k].removeAttribute("class");
                            }
                            this.setAttribute("class", "same-as-selected");
                            break;
                        }
                    }
                    h.click();
                });
                b.appendChild(c);
            }
            
            x[i].appendChild(b);
            a.addEventListener("click", function(e) {
                e.stopPropagation();
                closeAllSelect_<?php echo $this->otherUniqId ?>(this);
                this.nextSibling.classList.toggle("select-hide");
                this.classList.toggle("select-arrow-active");
                
                var $parentSelector = $(this).closest('.questiondv');
                var $mainLimitCount = $parentSelector.find('input[data-path="limitCount"]');
                
                $parentSelector.find('select[data-path="questionType"]').find('option').removeAttr('selected');
                $parentSelector.find('select[data-path="questionType"]').find('option[value="1"]:eq(0)').attr('selected', 'selected');
                $parentSelector.find('input[data-path="qtype"]').val('1');
                
                //$parentSelector.find('.form-check input[type="text"]').val('');
                $parentSelector.find('.form-check').show();
                $parentSelector.find('.isother').show();
                
                $mainLimitCount.hide();
                $mainLimitCount.val('');
                
                if ($(this).hasClass('select-selected') && $(this).html() == 'Олон сонголттой') {
                    $mainLimitCount.show();
                    $parentSelector.find('input[data-path="qtype"]').val('2');
                    $parentSelector.find('select[data-path="questionType"]').find('option').removeAttr('selected');
                    $parentSelector.find('select[data-path="questionType"]').find('option[value="2"]').attr('selected', 'selected');
                    $mainLimitCount.val('0');
                }
                
                if ($(this).hasClass('select-selected') && $(this).html() == 'Нээлттэй асуулт') {
                    $parentSelector.find('.form-check input[type="text"]').val('');
                    $parentSelector.find('.form-check').hide();
                    $parentSelector.find('input[data-path="qtype"]').val('3');
                    $parentSelector.find('.isother').attr('style', 'display: none !important');
                    $parentSelector.find('select[data-path="questionType"]').find('option').removeAttr('selected');
                    $parentSelector.find('select[data-path="questionType"]').find('option[value="3"]').attr('selected', 'selected');
                    
                }
                
            });

        }
        /************************************* SELECT OPTION STYLE 2 END ***********************************/
    }

    function closeAllSelect_<?php echo $this->otherUniqId ?>(elmnt) {
        var x, y, i, arrNo = [];
        x = document.getElementsByClassName("select-items");
        y = document.getElementsByClassName("select-selected");
        for (i = 0; i < y.length; i++) {
            if (elmnt == y[i]) {
                arrNo.push(i)
            } else {
                y[i].classList.remove("select-arrow-active");
            }
        }

        for (i = 0; i < x.length; i++) {
            if (arrNo.indexOf(i)) {
                x[i].classList.add("select-hide");
            }
        }
    }

    document.addEventListener("click", closeAllSelect_<?php echo $this->otherUniqId ?>);
    
    $('body').on('click', '.questiondv-copy-btn-<?php echo $this->otherUniqId ?>', function () {
        
        $.ajax({
            url: 'government/addinDv', 
            type: 'post',
            dataType: 'json',
            beforeSend: function () {
                blockContent_<?php echo $this->uniqId ?> ('.questiondv_<?php echo $this->otherUniqId ?>'); 
            },
            data: {
                order: $('.question-index-<?php echo $this->otherUniqId ?>').length,
                uniqId: '<?php echo $this->uniqId ?>',
                otherUniqId: '<?php echo $this->otherUniqId ?>',
            },
            success: function (data) {
                $('.questiondv_<?php echo $this->otherUniqId ?>').append(data.Html).promise().done(function () {
                    Core.initComponentSwitchery('.questiondv_<?php echo $this->otherUniqId ?>');
                });
                
                Core.unblockUI('.questiondv_<?php echo $this->otherUniqId ?>');
            },
            error: function () {
                Core.unblockUI('.questiondv_<?php echo $this->otherUniqId ?>');
            }
        });
        
    });
    
    $('body').on('click', '.questiondv-delete-btn-<?php echo $this->otherUniqId ?>', function () {
    
        if ($('.questiondv_<?php echo $this->otherUniqId ?>').find('.question-index-<?php echo $this->otherUniqId ?>').length == 1) {
            return;
        }
        
        $(this).closest('.question-index-<?php echo $this->otherUniqId ?>').remove();
        
        $('.questiondv_<?php echo $this->otherUniqId ?>').find('.question-index-<?php echo $this->otherUniqId ?>').each(function (index, row) {
            
            console.log('index = ' + index);
            console.log(row);
            var $name = index + 1;
            
            $(row).find('input[data-path="questionTxt"]').attr('name', 'questionTxt['+ index +']');
            $(row).find('input[data-path="isrequired"]').attr('name', 'isrequired['+ index +']');
            $(row).find('input[data-path="answerTxt"]').attr('name', 'answerTxt['+ index +'][]');
            $(row).find('input[data-path="questionTxt"]').attr('placeholder', 'Асуулт '+ $name);
            $(row).find('span.addanswerbtn').attr('data-order', index);
            
        });
        
    });
    
    function closebtn_content_<?php echo $this->uniqId ?>($contentModal) {
        $($contentModal).modal('hide');
        $('#modal-intranet<?php echo $this->uniqId ?>').modal('show');
    }
    
    function callViewFile_<?php echo $this->uniqId ?>($showmodal) {
        // $('#modal-intranet<?php echo $this->uniqId ?>').modal('hide');
        $($showmodal).modal('show');
    }
</script>