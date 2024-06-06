<div class="content-body-right height-scroll" style="height: calc(100vh - 161px);">
    <div class="mb-3">
        <h6 class="card-title">Мэдээлэл</h6>
        <div class="media-list-reviews" md-dataviewid="<?php echo $this->layoutPositionArr['pos_7_dvid'] ?>">
            <?php
            if (isset($this->layoutPositionArr['pos_7'])) {
                foreach ($this->layoutPositionArr['pos_7'] as $row) {
                    $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                    $fileview = array();
                    $extentions = explode(', ', $row['fileextension']);
                    $physicalpaths = explode(', ', $row['physicalpath']);

                    foreach ($extentions as $key => $extention) {
                        switch ($extention) {
                            case 'png':
                            case 'gif':
                            case 'jpeg':
                            case 'pjpeg':
                            case 'jpg':
                            case 'x-png':
                            case 'bmp':
                                $icon[$key] = "icon-file-picture text-danger-400";
                                $fileview[$key] = '<img src="'. $row['physicalpath'] .'" class="w-100">';
                                break;
                            case 'zip':
                            case 'rar':
                                $icon[$key] = "icon-file-zip text-danger-400";
                                break;
                            case 'pdf':
                                $icon[$key] = "icon-file-pdf text-danger-400";
                                $fileview[$key] = '<iframe src="'.URL.'api/pdf/web/viewer.html?file='.$row['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px;" id="iframe-detail-'.$this->uniqId.'"></iframe>';
                                break;
                            case 'mp3':
                                $icon[$key] = "icon-file-music text-danger-400";
                                break;
                            case 'mp4':
                                $icon[$key] = "icon-file-video text-danger-400";
                                break;
                            case 'doc':
                            case 'docx':
                                $icon[$key] = "icon-file-word text-blue-400";
                                $fileview[$key] = '<iframe id="viewFileMain" src="'.CONFIG_FILE_VIEWER_ADDRESS.'DocEdit.aspx?showRb=0&url='.URL . $row['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>';
                                break;
                            case 'ppt':
                            case 'pptx':
                                $icon[$key] = "icon-file-presentation text-danger-400";
                                $fileview[$key] = '<iframe id="file_viewer_'. $row['id'] .'" src="'. CONFIG_FILE_VIEWER_ADDRESS .'DocEdit.aspx?showRb=0&url='. URL . $row['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px;"></iframe>';
                                break;
                            case 'xls':
                            case 'xlsx':
                                $icon[$key] = "icon-file-excel text-green-400";
                                $fileview[$key] = '<iframe id="viewFileMain" src="'.CONFIG_FILE_VIEWER_ADDRESS.'SheetEdit.aspx?showRb=0&url='.URL . $row['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>';
                                break;
                            default:
                                $icon[$key] = "icon-file-empty text-danger-400";
                                break;
                        }
                    }
                    ?>
                    <div class="media mt-1">
                        <div class="media-body pb-1 border-bottom-1 border-gray">
                            <div class="d-flex flex-row justify-content-between">
                                <a href="javascript:;" class="line-height-normal text-two-line" data-rowdata="<?php echo $rowJson; ?>" onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)" title="<?php echo $row['description'] ?>"><?php echo $row['description'] ?></a>
                                <div>
                                    <?php
                                    if ($fileview) {
                                        foreach ($fileview as $key => $file) { ?>
                                            <a href="javascript:;" onclick="dataViewFileViewer(this, '1', '<?php echo isset($extentions[$key]) ? $extentions[$key] : '' ?>', '<?php echo isset($physicalpaths[$key]) ? $physicalpaths[$key] : '' ?>',  '<?php echo URL . (isset($physicalpaths[$key]) ? $physicalpaths[$key] : '') ?>', 'undefined');">
                                                <i class="<?php echo $icon[$key]; ?>"></i>
                                            </a>
                                        <?php
                                        }
                                    } ?>
                                </div>
                            </div>
                            <p class="mb-0 font-size-12"><?php echo $row['name'] ?></p>
                            <small class="text-gray mr-2"><?php echo $row['tsag'] ?></small>
                            <small class="text-gray">Шинээр нэмэгдсэн </small>
                        </div>
                    </div>
                <?php }
            }
            ?>
        </div>
    </div>
    <div class="mb-3">
        <ul class="nav nav-tabs nav-tabs-highlight nav-justified">
            <li class="nav-item"><a href="#filelib_<?php echo $this->uniqId ?>" class="nav-link pt-2 pb-2 pl-0 pr-0 active" data-toggle="tab">Файлын сан (<?php echo isset($this->layoutPositionArr['pos_9'][0]) ? sizeOf($this->layoutPositionArr['pos_9'][0]) : '0' ?>)</a></li>
            <li class="nav-item"><a href="#photogallery_<?php echo $this->uniqId ?>" class="nav-link pt-2 pb-2 pl-0 pr-0" data-toggle="tab">Зураг (<?php echo isset($this->layoutPositionArr['pos_9'][1]) ? sizeOf($this->layoutPositionArr['pos_9'][1]) : '0' ?>)</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="filelib_<?php echo $this->uniqId ?>">
                <div class="media-list-activity" md-dataviewid="<?php echo $this->layoutPositionArr['pos_9_0_dvid'] ?>">
                    <?php
                    $index = 1;
                    if (isset($this->layoutPositionArr['pos_9'][0])) {
                        foreach ($this->layoutPositionArr['pos_9'][0] as $row) { 
                            $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');

                            $fileview = array();
                            $extentions = explode(', ', $row['fileextension']);
                            $physicalpaths = explode(', ', $row['physicalpath']);

                            foreach ($extentions as $key => $extention) {
                                switch ($extention) {
                                    case 'png':
                                    case 'gif':
                                    case 'jpeg':
                                    case 'pjpeg':
                                    case 'jpg':
                                    case 'x-png':
                                    case 'bmp':
                                        $icon[$key] = "icon-file-picture text-danger-400";
                                        $fileview[$key] = '<img src="'. $row['physicalpath'] .'" class="w-100">';
                                        break;
                                    case 'zip':
                                    case 'rar':
                                        $icon[$key] = "icon-file-zip text-danger-400";
                                        break;
                                    case 'pdf':
                                        $icon[$key] = "icon-file-pdf text-danger-400";
                                        $fileview[$key] = '<iframe src="'.URL.'api/pdf/web/viewer.html?file='.$row['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px;" id="iframe-detail-'.$this->uniqId.'"></iframe>';
                                        break;
                                    case 'mp3':
                                        $icon[$key] = "icon-file-music text-danger-400";
                                        break;
                                    case 'mp4':
                                        $icon[$key] = "icon-file-video text-danger-400";
                                        break;
                                    case 'doc':
                                    case 'docx':
                                        $icon[$key] = "icon-file-word text-blue-400";
                                        $fileview[$key] = '<iframe id="viewFileMain" src="'.CONFIG_FILE_VIEWER_ADDRESS.'DocEdit.aspx?showRb=0&url='.URL . $row['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>';
                                        break;
                                    case 'ppt':
                                    case 'pptx':
                                        $icon[$key] = "icon-file-presentation text-danger-400";
                                        $fileview[$key] = '<iframe id="file_viewer_'. $row['id'] .'" src="'. CONFIG_FILE_VIEWER_ADDRESS .'DocEdit.aspx?showRb=0&url='. URL . $row['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px;"></iframe>';
                                        break;
                                    case 'xls':
                                    case 'xlsx':
                                        $icon[$key] = "icon-file-excel text-green-400";
                                        $fileview[$key] = '<iframe id="viewFileMain" src="'.CONFIG_FILE_VIEWER_ADDRESS.'SheetEdit.aspx?showRb=0&url='.URL . $row['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>';
                                        break;
                                    default:
                                        $icon[$key] = "icon-file-empty text-danger-400";
                                        break;
                                }
                            }

                            ?>
                            <div class="media mt-1">
                                <div class="media-body pb-1 border-bottom-1 border-gray">
                                    <div class="d-flex flex-row justify-content-between">
                                        <a href="javascript:;" class="line-height-normal text-two-line" data-rowdata="<?php echo $rowJson; ?>" onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)" title="<?php echo $row['description'] ?>"><?php echo $row['description'] ?></a>
                                        <div>
                                            <?php
                                            if ($fileview) {
                                                foreach ($fileview as $key => $file) { ?>
                                                    <a href="javascript:;" onclick="dataViewFileViewer(this, '1', '<?php echo isset($extentions[$key]) ? $extentions[$key] : '' ?>', '<?php echo isset($physicalpaths[$key]) ? $physicalpaths[$key] : '' ?>',  '<?php echo URL . (isset($physicalpaths[$key]) ? $physicalpaths[$key] : '') ?>', 'undefined');">
                                                        <i class="<?php echo $icon[$key]; ?>"></i>
                                                    </a>
                                                <?php
                                                }
                                            } ?>
                                        </div>
                                    </div>
                                    <!--<p class="mb-0 font-size-12"><?php echo $row['name'] ?></p>-->
                                    <small class="text-gray mr-2"><?php echo $row['tsag'] ?></small>
                                    <small class="text-gray">Шинээр нэмэгдсэн </small>
                                </div>
                            </div>
                        <?php
                        $index++;
                        }
                    } ?>
                </div>
            </div>
            <div class="tab-pane fade" id="photogallery_<?php echo $this->uniqId ?>">
                <div class="media-list-activity" md-dataviewid="<?php echo $this->layoutPositionArr['pos_9_1_dvid'] ?>">
                    <?php if (isset($this->layoutPositionArr['pos_9'][1])) {
                        foreach ($this->layoutPositionArr['pos_9'][1] as $row) {
                            $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');

                            $fileview = array();
                            $extentions = explode(', ', $row['fileextension']);
                            $physicalpaths = explode(', ', $row['physicalpath']);

                            foreach ($extentions as $key => $extention) {
                                switch ($extention) {
                                    case 'png':
                                    case 'gif':
                                    case 'jpeg':
                                    case 'pjpeg':
                                    case 'jpg':
                                    case 'x-png':
                                    case 'bmp':
                                        $icon[$key] = "icon-file-picture text-danger-400";
                                        $fileview[$key] = '<img src="'. $row['physicalpath'] .'" class="w-100">';
                                        break;
                                    case 'zip':
                                    case 'rar':
                                        $icon[$key] = "icon-file-zip text-danger-400";
                                        break;
                                    case 'pdf':
                                        $icon[$key] = "icon-file-pdf text-danger-400";
                                        $fileview[$key] = '<iframe src="'.URL.'api/pdf/web/viewer.html?file='.$row['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px;" id="iframe-detail-'.$this->uniqId.'"></iframe>';
                                        break;
                                    case 'mp3':
                                        $icon[$key] = "icon-file-music text-danger-400";
                                        break;
                                    case 'mp4':
                                        $icon[$key] = "icon-file-video text-danger-400";
                                        break;
                                    case 'doc':
                                    case 'docx':
                                        $icon[$key] = "icon-file-word text-blue-400";
                                        $fileview[$key] = '<iframe id="viewFileMain" src="'.CONFIG_FILE_VIEWER_ADDRESS.'DocEdit.aspx?showRb=0&url='.URL . $row['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>';
                                        break;
                                    case 'ppt':
                                    case 'pptx':
                                        $icon[$key] = "icon-file-presentation text-danger-400";
                                        $fileview[$key] = '<iframe id="file_viewer_'. $row['id'] .'" src="'. CONFIG_FILE_VIEWER_ADDRESS .'DocEdit.aspx?showRb=0&url='. URL . $row['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px;"></iframe>';
                                        break;
                                    case 'xls':
                                    case 'xlsx':
                                        $icon[$key] = "icon-file-excel text-green-400";
                                        $fileview[$key] = '<iframe id="viewFileMain" src="'.CONFIG_FILE_VIEWER_ADDRESS.'SheetEdit.aspx?showRb=0&url='.URL . $row['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>';
                                        break;
                                    default:
                                        $icon[$key] = "icon-file-empty text-danger-400";
                                        break;
                                }
                            }

                            ?>
                            <div class="media mt-1">
                                <div class="media-body pb-1 border-bottom-1 border-gray">
                                    <div class="d-flex flex-row justify-content-between">
                                        <a href="javascript:;" class="line-height-normal text-two-line" data-rowdata="<?php echo $rowJson; ?>" onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)" title="<?php echo $row['description'] ?>"><?php echo $row['description'] ?></a>
                                        <div>
                                            <?php
                                            if ($fileview) {
                                                foreach ($fileview as $key => $file) { ?>
                                                    <a href="javascript:;" onclick="dataViewFileViewer(this, '1', '<?php echo isset($extentions[$key]) ? $extentions[$key] : '' ?>', '<?php echo isset($physicalpaths[$key]) ? $physicalpaths[$key] : '' ?>',  '<?php echo URL . (isset($physicalpaths[$key]) ? $physicalpaths[$key] : '') ?>', 'undefined');">
                                                        <i class="<?php echo $icon[$key]; ?>"></i>
                                                    </a>
                                                <?php
                                                }
                                            } ?>
                                        </div>
                                    </div>
                                    <!--<p class="mb-0 font-size-12"><?php echo $row['name'] ?></p>-->
                                    <small class="text-gray mr-2"><?php echo $row['tsag'] ?></small>
                                    <small class="text-gray">Шинээр нэмэгдсэн </small>
                                </div>
                            </div>
                    <?php
                        $index++;
                        }
                    } ?>
                </div>
            </div>
        </div>
    </div>
    <div style="display: none;" class="mb-3">
        <ul class="nav nav-tabs nav-tabs-highlight nav-justified">
            <li class="nav-item"><a href="#forum_<?php echo $this->uniqId ?>" class="nav-link pt-2 pb-2 pl-0 pr-0 active" data-toggle="tab">Санал асуулга (<?php echo isset($this->layoutPositionArr['pos_8'][0]) ? sizeOf($this->layoutPositionArr['pos_8'][0]) : '0' ?>)</a></li>
            <li class="nav-item"><a href="#poll_<?php echo $this->uniqId ?>" class="nav-link pt-2 pb-2 pl-0 pr-0" data-toggle="tab">Хэлэлцүүлэг (<?php echo isset($this->layoutPositionArr['pos_8'][1]) ? sizeOf($this->layoutPositionArr['pos_8'][1]) : '0' ?>)</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="forum_<?php echo $this->uniqId ?>">
                <div class="media-list-reviews" md-dataviewid="<?php echo $this->layoutPositionArr['pos_8_0_dvid'] ?>">
                    <?php if (isset($this->layoutPositionArr['pos_8'][0])) {
                        foreach ($this->layoutPositionArr['pos_8'][0] as $row) {
                            $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                            ?>
                            <div class="media mt-1">
                                <div class="media-body pb-1 border-bottom-1 border-gray">
                                    <div class="d-flex flex-row justify-content-between">
                                        <a href="javascript:;" class="line-height-normal text-two-line" data-rowdata="<?php echo $rowJson; ?>" onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)" title="<?php echo $row['description'] ?>"><?php echo $row['description'] ?></a>
                                    </div>
                                    <p class="mb-0 font-size-12"><?php echo $row['name'] ?></p>
                                    <small class="text-gray mr-2"><?php echo $row['tsag'] ?></small>
                                    <small class="text-gray">Шинээр нэмэгдсэн </small>
                                </div>
                            </div>
                    <?php }
                    } ?>
                </div>
            </div>
            <div class="tab-pane fade" id="poll_<?php echo $this->uniqId ?>">
                <div class="media-list-reviews">
                    <?php if (isset($this->layoutPositionArr['pos_8'][1])) {
                        foreach ($this->layoutPositionArr['pos_8'][1] as $row) {
                            $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>
                            <div class="media mt-1" md-dataviewid="<?php echo $this->layoutPositionArr['pos_8_1_dvid'] ?>">
                                <div class="media-body pb-1 border-bottom-1 border-gray">
                                    <div class="d-flex flex-row justify-content-between">
                                        <a href="javascript:;" class="line-height-normal text-two-line" data-rowdata="<?php echo $rowJson; ?>" onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)" title="<?php echo $row['description'] ?>"><?php echo $row['description'] ?></a>
                                    </div>
                                    <p class="mb-0 font-size-12"><?php echo $row['name'] ?></p>
                                    <small class="text-gray mr-2"><?php echo $row['tsag'] ?></small>
                                    <small class="text-gray">Шинээр нэмэгдсэн </small>
                                </div>
                            </div>
                    <?php }
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    function drilldownLink_intranet_<?php echo $this->uniqId ?> (element) {
        var _this = $(element),
            selectedRow = JSON.parse(_this.attr('data-rowdata'));
        if (typeof _this.attr('data-showtype') !== 'undefined' && _this.attr('data-showtype') === 'dialog') {
            
            var $dialogName = 'dialog-confirm-' + getUniqueId(1);
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }

            $("#" + $dialogName).empty().append(selectedRow['body']);
            $("#" + $dialogName).dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: selectedRow['description'],
                width: 450,
                height: "auto",
                modal: true,
                close: function() {
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                },
                buttons: [
                    {
                        text: plang.get('close_btn'),
                        class: 'btn blue-madison btn-sm',
                        click: function() {
                            $("#" + $dialogName).dialog('close');
                        }
                    }
                ]
            }).dialogExtend({
                "closable": false,
                "maximizable": false,
                "minimizable": false,
                "collapsable": false,
                "dblclick": "maximize",
                "minimizeLocation": "left",
                "icons": {
                    "close": "ui-icon-circle-close",
                    "maximize": "ui-icon-extlink",
                    "minimize": "ui-icon-minus",
                    "collapse": "ui-icon-triangle-1-s",
                    "restore": "ui-icon-newwin"
                }
            });

            $("#" + $dialogName).dialog('open');
        } else {
            appMultiTab({weburl: 'government/intranet', metaDataId: 'government/intranet', dataViewId: selectedRow.id, title: 'Олон нийт', type: 'selfurl', recordId: selectedRow.id, selectedRow: selectedRow, tabReload: true});
        }
    }
    
</script>