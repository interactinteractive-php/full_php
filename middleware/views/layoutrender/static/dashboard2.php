<?php
    (Array) $pos_5Arr = array();
    if (isset($this->layoutPositionArr['pos_5'][0]['cardtitle'])) {
        $pos_5Arr = Arr::groupByArrayOnlyRows($this->layoutPositionArr['pos_5'], 'cardtitle');
    }
    $i = 1;
    
    /*
    $this->layoutPositionArr['pos_1_dvid']
    $this->layoutPositionArr['pos_2_dvid']
    $this->layoutPositionArr['pos_3_dvid']
    $this->layoutPositionArr['pos_4_dvid']
    $this->layoutPositionArr['pos_5_dvid']
    $this->layoutPositionArr['pos_6_0_dvid']
    $this->layoutPositionArr['pos_6_1_dvid']
    $this->layoutPositionArr['pos_7_dvid']
    $this->layoutPositionArr['pos_8_0_dvid']
    $this->layoutPositionArr['pos_8_1_dvid']
    $this->layoutPositionArr['pos_9_0_dvid']
    $this->layoutPositionArr['pos_9_1_dvid']
     * 
     */
?>
<div class="2 content-dashboard-ten content-dashboard-ten-<?php echo $this->uniqId ?>">
    <div class="content-body">
        <div class="content-body-left height-scroll" style="height: calc(100vh - 104px);">
            <div class="row">
                <div class="col">
                    <div class="card-body card-dashboard-five" style="background: #2f5eaf;border-left:3px solid rgba(0,0,0,0.3);">
                        <h5 class="text-white font-size-16 pb5 mb5" style="border-bottom: 1px solid rgba(255,255,255,0.2);"><?php echo Lang::line('TITLE_POS_001') ?></h5>
                        <div class="row">
                            <?php
                                if (isset($this->layoutPositionArr['pos_1'][0]) && $this->layoutPositionArr['pos_1'][0]) { 
                                    foreach ($this->layoutPositionArr['pos_1'][0] as $key => $row) {
                                ?>
                                <div class="col-4" md-dataviewid="<?php echo $this->layoutPositionArr['pos_1_dvid'] ?>">
                                    <div class="mb-0">
                                        <label class="card-label text-white"><?php echo Lang::line($key) ?></label>
                                        <h6 class="card-value text-white"><?php echo $row; ?></h6>
                                    </div>
                                </div>
                            <?php 
                                    } 
                                }
                                if (isset($this->layoutPositionArr['pos_2'][0]) && $this->layoutPositionArr['pos_2'][0]) { 
                                    foreach ($this->layoutPositionArr['pos_2'][0] as $key => $row) {
                                ?>
                                <div class="col-4" md-dataviewid="<?php echo $this->layoutPositionArr['pos_2_dvid'] ?>">
                                    <div class="mb-0">
                                        <label class="card-label text-white"><?php echo Lang::line($key) ?></label>
                                        <h6 class="card-value text-white"><?php echo $row; ?></h6>
                                    </div>
                                </div>
                            <?php 
                                    } 
                                }
                                if (isset($this->layoutPositionArr['pos_3'][0]) && $this->layoutPositionArr['pos_3'][0]) { 
                                    foreach ($this->layoutPositionArr['pos_3'][0] as $key => $row) {
                                ?>
                                <div class="col-4" md-dataviewid="<?php echo $this->layoutPositionArr['pos_3_dvid'] ?>">
                                    <div class="mb-0">
                                        <label class="card-label text-white"><?php echo Lang::line($key) ?></label>
                                        <h6 class="card-value text-white"><?php echo $row; ?></h6>
                                    </div>
                                </div>
                            <?php 
                                    } 
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                if (isset($this->layoutPositionArr['pos_4']) && $this->layoutPositionArr['pos_4']) { ?>
                <div class="col" md-dataviewid="<?php echo $this->layoutPositionArr['pos_4_dvid'] ?>">
                    <div class="card-body card-dashboard-five" style="background: #698fd1;border-left:3px solid rgba(0,0,0,0.3);">
                        <h5 class="text-white font-size-16 pb5 mb5" style="border-bottom: 1px solid rgba(255,255,255,0.2);"><?php echo Lang::line('TITLE_POS_002') ?></h5>
                        <div class="row">
                            <?php
                                foreach ($this->layoutPositionArr['pos_4'] as $key => $row) { 
                                        ?>
                                        <div class="col">
                                            <div class="mb-0">
                                                <label class="card-label text-white"><?php echo $row['cardname'] ?></label>
                                                <h6 class="card-value text-white"><?php echo $row['cardvalue'] ?></h6>
                                            </div>
                                        </div>
                                    <?php 
                                    } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <h5 class="card-title font-size-16 ml3 pb-0 mb3"><?php echo Lang::line('TITLE_POS_003') ?></h5>
            <div class="row" md-dataviewid="<?php echo $this->layoutPositionArr['pos_5_dvid'] ?>" style="padding-left: 0.675rem !important; padding-right: 0.675rem !important;">
                <?php if ($pos_5Arr) {
                    foreach ($pos_5Arr as $key => $arData) { ?>
                        <div class="col-4 p-0">
                            <?php 
                            $color = "";
                            if($key == 'Өргөдөл гомдол') {
                                $color = "#4bcdca";
                            } else if ($key == 'Төлөвлөж буй бичиг') {
                                $color = "#5ea4e8";
                            } else if ($key == 'ирсэн бичиг') {
                                $color = "#b671cb";
                            } ?>
                            <div class="card card-body card-dashboard-five" style="background: <?php echo $color; ?>">
                                <h6 class="card-body-title3 font-weight-bold text-other"><?php echo Lang::line($key) ?></h6>
                                <div class="row">
                                    <?php if ($arData) {
                                        foreach ($arData as $row) {
                                            $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>
                                            <div class="col-6 col-sm-4 col-lg">
                                                <label class="card-label text-other"><?php echo $row['cardname'] ?></label>
                                                <h6 class="card-value text-other"><a href="javascript:;" data-row='<?php echo $rowJson ?>' onclick="drilldownLinkCustome3_<?php echo $this->uniqId ?>(this)"><?php echo $row['cardvalue'] ?></a></h6>
                                            </div>
                                        <?php }
                                    } ?>
                                </div>
                            </div>
                        </div>
                    <?php 
                }
            } ?>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card p-2 card-dashboard-four" style="height: 410px;">
                        <div class="card-header pt-2 pb-2">
                            <h6 class="card-title text-uppercase mr-3">Ажил үүрэг</h6>
                            <ul class="nav nav-tabs nav-tabs-bottom border-bottom-0 mb-0">
                                <li class="nav-item"><a href="#highlighted-tab1_<?php echo $this->uniqId ?>" class="nav-link pt-1 pb-2 active show" data-toggle="tab">Шинэ ажил (<?php echo (isset($this->layoutPositionArr['pos_6'][0]) ? sizeof($this->layoutPositionArr['pos_6'][0]) : 0); ?>)</a></li>
                                <li class="nav-item"><a href="#highlighted-tab2_<?php echo $this->uniqId ?>" class="nav-link pt-1 pb-2" data-toggle="tab">Хугацаа хэтэрсэн ажил (<?php echo (isset($this->layoutPositionArr['pos_6'][1]) ? sizeof($this->layoutPositionArr['pos_6'][1]) : 0) ?>)</a></li>
                            </ul>
                            <div class="list-icons ml-auto">
                                <!--<a href="javascript:void(0);" class="btn btn-primary btn-sm"></a>-->
                            </div>
                        </div>
                        <div class="card-body p-0 pb-3 table">
                            <div class="tab-content">
                                <div class="tab-pane fade active show" id="highlighted-tab1_<?php echo $this->uniqId ?>">
                                    <div>
                                        <table class="not-datagrid table table-striped table-dashboard-two mg-b-0" md-dataviewid="<?php echo $this->layoutPositionArr['pos_6_0_dvid'] ?>">
                                            <thead>
                                                <tr>
                                                    <th style="width:50px;"></th>
                                                    <th>Ажлын нэр</th>
                                                    <th class="text-right">Дуусах огноо</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                if (isset($this->layoutPositionArr['pos_6'][0])) {
                                                    if (isset($this->layoutPositionArr['pos_6'][0][0]['departmentname'])) {
                                                        $pos_6Arr = Arr::groupByArrayOnlyRows($this->layoutPositionArr['pos_6'][0], 'departmentname');
                                                        foreach ($pos_6Arr as $key => $pos_6) { ?> 
                                                            <tr>
                                                                <td colspan='3' class="text-blue letter-icon-title">
                                                                    <a class="text-default collapsed" data-toggle="collapse" href="#collapsible-<?php echo $this->uniqId ?><?php echo $i ?>" aria-expanded="true"><?php echo $key; ?> </a>
                                                                </td>
                                                            </tr>
                                                            <?php foreach ($pos_6 as $row) {
                                                            $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>
                                                            <tr class="selected-row d-flex justify-content-between align-items-center" id="collapsible-<?php echo $this->uniqId ?><?php echo $i ?>" class="collapse show" >
                                                                <td style="width:50px;">
                                                                    <img src="<?php echo ($row['picture']) ? $row['picture'] : '' ?>" class="rounded-circle" onerror="onUserImageError(this);" width="38" height="38" title="<?php echo $row['employeename'] ?>&#x0a<?php echo $row['positionname'] ?>">
                                                                </td>
                                                                <td>
                                                                    <div class="font-size-12">
                                                                        <a data-row-data="<?php echo $rowJson; ?>" href="javascript:;" onclick="drillDownTransferProcessAction1('transferProcessAction', '1', '', '', '1568018390310', '1477069622497', 'toolbar', '', this, {callerType: 'taskListByNew', isDrillDown: true}, '');">
                                                                            <?php echo $row['taskname'] ?>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                                <td class="ml-auto">
                                                                    <div class="font-size-12"><?php echo Date::format('Y-m-d', $row['enddate']); ?></div>
                                                                </td>
                                                            </tr>        
                                                        <?php }
                                                            $i++;
                                                        }
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="highlighted-tab2_<?php echo $this->uniqId ?>">
                                    <div>
                                        <table class="table table-striped table-dashboard-two mg-b-0" md-dataviewid="<?php echo $this->layoutPositionArr['pos_6_1_dvid'] ?>">
                                            <thead>
                                                <tr>
                                                    <th style="width:50px;"></th>
                                                    <th>Ажлын нэр</th>
                                                    <th class="text-right">Хэтэрсэн өдөр</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                if (isset($this->layoutPositionArr['pos_6'][1])) {
                                                    if (isset($this->layoutPositionArr['pos_6'][1][0]['departmentname'])) {
                                                    $pos_6Arr = Arr::groupByArrayOnlyRows($this->layoutPositionArr['pos_6'][1], 'departmentname');
                                                        foreach ($pos_6Arr as $key => $pos_6) { ?>
                                                            <tr>
                                                                <td colspan='3' class="text-blue letter-icon-title">
                                                                    <a class="text-default collapsed" data-toggle="collapse" href="#collapsible-<?php echo $this->uniqId ?><?php echo $i ?>" aria-expanded="true"><?php echo $key; ?> </a>
                                                                </td>
                                                            </tr>
                                                            <?php foreach ($pos_6 as $row) {
                                                            $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>
                                                            <tr class="selected-row d-flex justify-content-between align-items-center" id="collapsible-<?php echo $this->uniqId ?><?php echo $i ?>" class="collapse show" >
                                                                <td style="width:50px;">
                                                                    <img src="<?php echo ($row['picture']) ? $row['picture'] : '' ?>" class="rounded-circle" onerror="onUserImageError(this);" width="38" height="38" title="<?php echo $row['employeename'] ?>&#x0a<?php echo $row['positionname'] ?>">
                                                                </td>
                                                                <td>
                                                                    <div class="font-size-12">
                                                                        <a data-row-data="<?php echo $rowJson; ?>" href="javascript:;" onclick="drillDownTransferProcessAction1('transferProcessAction', '1', '', '', '1568018390310', '1477069622497', 'toolbar', '', this, {callerType: 'taskListByNew', isDrillDown: true}, '');">
                                                                            <?php echo $row['taskname'] ?>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                                <td class="ml-auto">
                                                                    <div class="font-size-12"><?php echo $row['daycnt'] ?></div>
                                                                </td>
                                                            </tr>        
                                                        <?php }
                                                            $i++;
                                                        }
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-7">
                    <div class="card p-2 card-dashboard-four" id="layout-1568354619456" data-layout-param-map-id="1571919579563191" style="height: 410px; padding-top: 20px;">

                    </div>
                </div>
            </div>
        </div>
        <div class="content-body-right height-scroll" style="height: calc(100vh - 104px);">
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
                                    <div class="d-flex">
                                        <a href="javascript:;" class="line-height-normal text-two-line" data-rowdata="<?php echo $rowJson; ?>" onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)" title="<?php echo $row['description'] ?>"><?php echo $row['description'] ?></a>
                                    </div>
                                    <div class="d-flex flex-row justify-content-between">
                                        <div>
                                            <p class="mb-0 font-size-12"><?php echo $row['name'] ?></p>
                                        </div>
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
                                    <div class="d-flex justify-content-between">
                                        <small class="text-gray mr-2"><?php echo $row['tsag'] ?></small>
                                        <small class="text-gray">Шинээр нэмэгдсэн</small>
                                    </div>
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
                                    <div class="media mt-1" data-rowdata="<?php echo $rowJson; ?>">
                                        <div class="media-body pb-1 border-bottom-1 border-gray">
                                            <div class="d-flex">
                                                <a href="javascript:;" class="line-height-normal text-two-line" data-rowdata="<?php echo $rowJson; ?>" onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)" title="<?php echo $row['description'] ?>"><?php echo $row['description'] ?></a>
                                            </div>
                                            <div class="d-flex flex-row justify-content-between">
                                                <div>
                                                    <p class="mb-0 font-size-12"><?php echo $row['name'] ?></p>
                                                </div>
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
                                            <div class="d-flex justify-content-between">
                                                <small class="text-gray mr-2"><?php echo $row['tsag'] ?></small>
                                                <small class="text-gray">Шинээр нэмэгдсэн</small>
                                            </div>
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
                                    <div class="media mt-1" data-rowdata="<?php echo $rowJson; ?>">
                                        <div class="media-body pb-1 border-bottom-1 border-gray">
                                            <div class="d-flex">
                                                <a href="javascript:;" class="line-height-normal text-two-line" data-rowdata="<?php echo $rowJson; ?>" onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)" title="<?php echo $row['description'] ?>"><?php echo $row['description'] ?></a>
                                            </div>
                                            <div class="d-flex flex-row justify-content-between">
                                                <div>
                                                    <p class="mb-0 font-size-12"><?php echo $row['name'] ?></p>
                                                </div>
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
                                            <div class="d-flex justify-content-between">
                                                <small class="text-gray mr-2"><?php echo $row['tsag'] ?></small>
                                                <small class="text-gray">Шинээр нэмэгдсэн</small>
                                            </div>
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
            <div class="mb-3">
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
                                            <div class="d-flex justify-content-between">
                                                <small class="text-gray mr-2"><?php echo $row['tsag'] ?></small>
                                                <small class="text-gray">Шинээр нэмэгдсэн </small>
                                            </div>
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
                                    $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                                    ?>
                                    <div class="media mt-1" md-dataviewid="<?php echo $this->layoutPositionArr['pos_8_1_dvid'] ?>">
                                        <div class="media-body pb-1 border-bottom-1 border-gray">
                                            <div class="d-flex flex-row justify-content-between">
                                                <a href="javascript:;" class="line-height-normal text-two-line" data-rowdata="<?php echo $rowJson; ?>" onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)" title="<?php echo $row['description'] ?>"><?php echo $row['description'] ?></a>
                                            </div>
                                            <p class="mb-0 font-size-12"><?php echo $row['name'] ?></p>
                                            <div class="d-flex justify-content-between">
                                                <small class="text-gray mr-2"><?php echo $row['tsag'] ?></small>
                                                <small class="text-gray">Шинээр нэмэгдсэн </small>
                                            </div>
                                        </div>
                                    </div>
                            <?php }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    
    .content-dashboard-ten-<?php echo $this->uniqId ?> .data-tooltip {
        display:inline-block;
        position:relative;
        text-align:left;
    }

    .content-dashboard-ten-<?php echo $this->uniqId ?> .data-tooltip h5 {
        padding: 10px;
        border-bottom: 1px dashed #FFF;
    }

    .content-dashboard-ten-<?php echo $this->uniqId ?> .data-tooltip .tooltipright {
        min-width:293px;
        max-width:293px;
        top:50%;
        left:100%;
        margin-left:20px;
        transform:translate(0, -50%);
        padding:0;
        color:#EEEEEE;
        background-color:#444444;
        font-weight:normal;
        font-size:13px;
        border-radius:8px;
        position:absolute;
        z-index:99999999;
        box-sizing:border-box;
        box-shadow:0 1px 8px rgba(0,0,0,0.5);
        visibility:hidden; opacity:0; transition:opacity 0.8s;
    }

    .content-dashboard-ten-<?php echo $this->uniqId ?> .data-tooltip:hover .tooltipright {
        visibility:visible; opacity:1;
    }

    .content-dashboard-ten-<?php echo $this->uniqId ?> .data-tooltip .tooltipright img {
        width:400px;
        border-radius:8px 8px 0 0;
    }
    
    .content-dashboard-ten-<?php echo $this->uniqId ?> .data-tooltip .text-content {
        padding:10px 20px;
    }

    .content-dashboard-ten-<?php echo $this->uniqId ?> .data-tooltip .tooltipright i {
        position:absolute;
        top:50%;
        right:100%;
        margin-top:-12px;
        width:12px;
        height:24px;
        overflow:hidden;
    }
    .content-dashboard-ten-<?php echo $this->uniqId ?> .data-tooltip .tooltipright i::after {
        content:'';
        position:absolute;
        width:12px;
        height:12px;
        left:0;
        top:50%;
        transform:translate(50%,-50%) rotate(-45deg);
        background-color:#444444;
        box-shadow:0 1px 8px rgba(0,0,0,0.5);
    }
    
    .content-dashboard-ten-<?php echo $this->uniqId ?> .card.card-body.card-dashboard-five {
        border: 1px solid #e5e5e5 !important;
        background: #FFF !important;
        border: 0;
        box-shadow: none !important;
    }
    .content-dashboard-ten-<?php echo $this->uniqId ?> {
        font-family: 'Arial', sans-serif !important;
    }
    .content-dashboard-ten-<?php echo $this->uniqId ?> .card-body .card-body-title3 {
        letter-spacing: 0 !important;
        border-bottom-color: #22b9ff!important;
    }
    
    .content-dashboard-ten-<?php echo $this->uniqId ?> .card-dashboard-four .card-title {
        letter-spacing: -0.5px !important;
    }
    .content-dashboard-ten-<?php echo $this->uniqId ?> .card-value a,
    .text-other {
        color: #74788d;
    }
</style>
<script type="text/javascript">

    $(function () {
        
        layoutCallWidget('1568354619456', 'widgethrmtimesheetlogv3', '1571919579563191');
        
    });
    
    function layoutCallWidget(metaDataId, metaDataCode, paramMapId) {
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'mdwidget/runWidget',
            data: {
                widgetCode: metaDataCode,
                metaDataId: metaDataId,
                paramMapId: paramMapId,
                linkMetaDataId: <?php echo $this->metaDataId; ?>,
                uniqId: '<?php echo getUID(); ?>'
            },
            dataType: "json",
            beforeSend: function () {},
            success: function (data) {
                $("div#layout-" + metaDataId).empty().append(data.html);
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initAjax($("div#layout-" + metaDataId));
        });
    }
    
    function drillDownTransferProcessAction1 (functionName, clinkMetaDataId, criteria, passPath, mainMetaDataId, processMetaDataId, metaTypeId, whereFrom, elem, params, wfmStatusParams, drillDownType) {
        var mainRow = JSON.parse($(elem).attr('data-row-data'));
        drillDownTransferProcessAction(functionName, clinkMetaDataId, criteria, passPath, mainMetaDataId, processMetaDataId, metaTypeId, whereFrom, elem, params, wfmStatusParams, drillDownType, mainRow);
    }
    
    function drilldownLinkCustome3_<?php echo $this->uniqId ?> (element) {
        var row = JSON.parse($(element).attr('data-row'));
        var criteria = 'defaultmetaid=1578488805200&isnew1='+ row.isnew1+'&typeid='+ row.typeid +',typeid='+ row.typeid +'&directionid=1&isnew1='+ row.isnew1+'&defaultmetaid=1554813741229,isnew1='+ row.isnew1+'&typeid='+ row.typeid +'&defaultmetaid=1578488805780,isnew1='+ row.isnew1+'&typeid='+ row.typeid +'&defaultmetaid=1578488807023';
        gridDrillDownLink(element, 'DOC_DASHBOARD_CARDS_01', 'package,package,package,package', '4', 'cardid==1,1==0,cardid==2,cardid==4', '1571474482320', 'cardvalue', '1580889642841862,1580889642841862,1580889642841862,1580889642841862', criteria, 'newrender', '',  '',  '');
    }
    
    function drilldownLink_intranet_<?php echo $this->uniqId ?> (element) {
        var selectedRow = JSON.parse($(element).attr('data-rowdata'));
        
        appMultiTab({weburl: 'government/intranet', metaDataId: 'government/intranet', dataViewId: selectedRow.id, title: 'Олон нийт', type: 'selfurl', recordId: selectedRow.id, selectedRow: selectedRow, tabReload: true});
    }
    
</script>