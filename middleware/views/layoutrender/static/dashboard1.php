<?php
    (Array) $pos_5Arr = array();
    if (isset($this->layoutPositionArr['pos_5'][0]['cardtitle'])) {
        $pos_5Arr = Arr::groupByArrayOnlyRows($this->layoutPositionArr['pos_5'], 'cardtitle');
    }
    
    $i = 1;
//    var_dump($this->layoutPositionArr['pos_6']);
?>
<div class="1 content-dashboard-ten content-dashboard-ten-<?php echo $this->uniqId ?>">
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
                        <h5 class="text-other font-size-16 pb5 mb5" style="border-bottom: 1px solid rgba(255,255,255,0.2);"><?php echo Lang::line('TITLE_POS_002') ?></h5>
                        <div class="row">
                            <?php
                                foreach ($this->layoutPositionArr['pos_4'] as $key => $row) { 
                                        ?>
                                        <div class="col">
                                            <div class="mb-0">
                                                <label class="card-label text-other"><?php echo $row['cardname'] ?></label>
                                                <h6 class="card-value text-other"><?php echo $row['cardvalue'] ?></h6>
                                            </div>
                                        </div>
                                <?php 
                                } 
                            ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <h5 class="card-title font-size-16 ml3 pb-0 mb3"><?php echo Lang::line('TITLE_POS_003') ?></h5>
            <div class="row" md-dataviewid="<?php echo $this->layoutPositionArr['pos_5_dvid'] ?>" style="padding-left: 0.675rem !important; padding-right: 0.675rem !important;">
                    <?php if ($pos_5Arr) {
                        foreach ($pos_5Arr as $key => $arData) { ?>
                            <div class="col-md-4 p-0 ">
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
                        <?php }
                    } ?>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="card pl-3 pr-3 card-dashboard-four" style="height: 410px;">
                        <div class="card-header pt-2 pb-2">
                            <h6 class="card-title text-uppercase">Ажилтнуудын ирц</h6>
                            <!-- <div class="list-icons">
                                <a href="javascript:void(0);" class="btn bg-blue btn-sm"><?php echo (isset($this->layoutPositionArr['pos_6'][0]) ? sizeof($this->layoutPositionArr['pos_6'][0]) : 0)  ?></a>
                            </div> -->
                        </div>
                        <div class="card-body p-0 pb-3 table">
                            <div>
                                <table class="table table-striped table-dashboard-two mg-b-0" md-dataviewid="<?php echo $this->layoutPositionArr['pos_6_0_dvid'] ?>">
                                    <?php
                                        $currentDate = Date::currentDate();
                                        $dd = Date::format('D', $currentDate);
                                        switch ($dd) {
                                            case 'Sun':
                                                $currentDate = date('Y-m-d', strtotime($currentDate . ' - 2 days')); Date::lastDay('Y-m-d', $currentDate, '2');
                                                break;
                                            case 'Sat':
                                                $currentDate = date('Y-m-d', strtotime($currentDate . ' - 1 days')); Date::lastDay('Y-m-d', $currentDate, '1');
                                                break;
                                            default:
                                                break;
                                        }
                                        
                                        $a1 = Date::addWorkingDays('Y-m-d', '-', $currentDate, 4);
                                        $a2 = Date::addWorkingDays('Y-m-d', '-', $currentDate, 3);
                                        $a3 = Date::addWorkingDays('Y-m-d', '-', $currentDate, 2);
                                        $a4 = Date::addWorkingDays('Y-m-d', '-', $currentDate, 1);
                                        $a5 = Date::addWorkingDays('Y-m-d', '-', $currentDate, 0);
                                    ?>
                                    <thead>
                                        <tr>
                                            <th style="width:200px;">Ажилтан</th>
                                            <th><?php echo Date::format('m/d', $a1) . ' ('. Date::format('D', $a1) .')' ?></th>
                                            <th><?php echo Date::format('m/d', $a2) . ' ('. Date::format('D', $a2) .')' ?></th>
                                            <th><?php echo Date::format('m/d', $a3) . ' ('. Date::format('D', $a3) .')' ?></th>
                                            <th><?php echo Date::format('m/d', $a4) . ' ('. Date::format('D', $a4) .')' ?></th>
                                            <th><?php echo Date::format('m/d', $a5) . ' ('. Date::format('D', $a5) .')' ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if (isset($this->layoutPositionArr['pos_6'][0])) {
                                                if (isset($this->layoutPositionArr['pos_6'][0][0]['departmentname'])) {
                                                    $pos_6Arr = Arr::groupByArrayOnlyRows($this->layoutPositionArr['pos_6'][0], 'departmentname');
                                                    foreach ($pos_6Arr as $key => $pos_6) { ?> 
                                                        <tr>
                                                            <td colspan='6' class="text-blue letter-icon-title "> 
                                                                <a class="text-default collapsed" data-toggle="collapse" href="#collapsible-<?php echo $this->uniqId ?><?php echo $i ?>" aria-expanded="true"><?php echo $key; ?> </a>
                                                            </td>
                                                        </tr>
                                                        <?php foreach ($pos_6 as $row) {
                                                        $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>
                                                    <tr id="collapsible-<?php echo $this->uniqId ?><?php echo $i ?>" class="collapse show">
                                                        <td style="width:200px;">
                                                            <div class="d-flex align-items-center">
                                                                <div class="mt3 mr-2 position-relative">
                                                                    <img src="<?php echo ($row['picture']) ? $row['picture'] : '' ?>" class="rounded-circle" onerror="onUserImageError(this);" width="38" height="38" alt="">
                                                                </div>
                                                                <div>
                                                                    <div><a href='javascript:;' data-row='<?php echo $rowJson ?>' onclick="drilldownLinkCustome1_<?php echo $this->uniqId ?>(this)" class='text-blue letter-icon-title'><?php echo $row['employeename'] ?></a></div>
                                                                    <div class="text-muted font-size-10"><?php echo $row['positionname'] ?></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="tx-medium tx-inverse"><div class="font-size-10"><?php echo $row[Str::lower(Date::format('l', $a1)) . 'out'] ?></div></td>
                                                        <td class="tx-medium tx-inverse"><div class="font-size-10"><?php echo $row[Str::lower(Date::format('l', $a2)) . 'out'] ?></div></td>
                                                        <td class="tx-medium tx-inverse"><div class="font-size-10"><?php echo $row[Str::lower(Date::format('l', $a3)) . 'out'] ?></div></td>
                                                        <td class="tx-medium tx-inverse"><div class="font-size-10"><?php echo $row[Str::lower(Date::format('l', $a4)) . 'out'] ?></div></td>
                                                        <td class="tx-medium tx-inverse"><div class="font-size-10"><?php echo $row[Str::lower(Date::format('l', $a5)) . 'out'] ?></div></td>
                                                    </tr>
                                            <?php   } 
                                                $i++;
                                                }
                                            }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card pl-3 pr-3 card-dashboard-four" style="height: 410px;">
                        <div class="card-header pt-2 pb-2">
                            <h6 class="card-title text-uppercase">Ажлын гүйцэтгэл</h6>
                            <!-- <div class="list-icons">
                                <a href="javascript:void(0);" class="btn bg-blue btn-sm"><?php echo (isset($this->layoutPositionArr['pos_6'][1]) ? sizeof($this->layoutPositionArr['pos_6'][1]) : 0)  ?></a>
                            </div> -->
                        </div>
                        <div class="card-body p-0 pb-3 table">
                            <div>
                                <table class="table table-striped table-dashboard-two mg-b-0" md-dataviewid="<?php echo $this->layoutPositionArr['pos_6_1_dvid'] ?>">
                                    <thead>
                                        <tr>
                                            <th style="width:300px;">Ажилтан</th>
                                            <th style="width:60px;">Шинэ</th>
                                            <th style="width:60px;">Хийгдэж<br>байгаа</th>
                                            <th style="width:60px;">Хийгдсэн</th>
                                            <th style="width:60px;">Нийт</th>
                                            <th style="width:60px;">/Хувиар/</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if (isset($this->layoutPositionArr['pos_6'][1])) {
                                            $pos_6Arr = Arr::groupByArrayOnlyRows($this->layoutPositionArr['pos_6'][1], 'departmentname');
                                            foreach ($pos_6Arr as $key => $pos_6) { ?> 
                                                <tr>
                                                    <td colspan='6' class="text-black letter-icon-title "> 
                                                        <a class="text-default collapsed" data-toggle="collapse" href="#collapsible-<?php echo $this->uniqId ?><?php echo $i ?>" aria-expanded="true"><?php echo $key; ?> </a>
                                                    </td>
                                                </tr>
                                                <?php foreach ($pos_6 as $row) {
                                                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>
                                                <tr id="collapsible-<?php echo $this->uniqId ?><?php echo $i ?>" class="collapse show">
                                                    <td style="width:300px;">
                                                        <div class="d-flex align-items-center">
                                                            <div class="mt3 mr-2 position-relative">
                                                                <img src="<?php echo ($row['picture']) ? $row['picture'] : '' ?>" class="rounded-circle" onerror="onUserImageError(this);" width="38" height="38" alt="">
                                                            </div>
                                                            <div>
                                                                <a href="javascript:void(0);" class="text-black letter-icon-title"><?php echo $row['employeename'] ?></a>
                                                                <div class="text-muted font-size-12"><?php echo $row['status'] ?></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="width:60px;">
                                                        <div class="d-flex align-items-center"><?php echo $row['new'] ?></div>
                                                    </td>
                                                    <td style="width:60px;">
                                                        <div class="d-flex align-items-center"><?php echo $row['doing'] ?></div>
                                                    </td>
                                                    <td style="width:60px;">
                                                        <div class="d-flex align-items-center"><?php echo $row['done'] ?></div>
                                                    </td>
                                                    <td style="width:60px;">
                                                        <div class="d-flex align-items-center"><?php echo $row['alltasks'] ?></div>
                                                    </td>
                                                    <td style="width:60px;">
                                                        <div class="d-flex align-items-center"><?php echo $row['progress'] ?></div>
                                                    </td>
                                                </tr>        
                                            <?php }} $i++; } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="card pl-3 pr-3 card-dashboard-four" style="height: 410px;">
                        <div class="card-header pt-2 pb-2">
                            <h6 class="card-title text-uppercase">Хүсэлтүүд</h6>
                            <div class="list-icons">
                                <a href="javascript:void(0);" class="btn bg-blue btn-sm"><?php echo (isset($this->layoutPositionArr['pos_6'][2]) ? sizeof($this->layoutPositionArr['pos_6'][2]) : 0) ?></a>
                            </div>
                        </div>
                        <div class="card-body p-0 pb-3 table">
                            <div>
                                <table class="table table-striped table-dashboard-two mg-b-0" md-dataviewid="<?php echo $this->layoutPositionArr['pos_6_2_dvid'] ?>">
                                    <thead>
                                        <tr>
                                            <th style="width:200px;"></th>
                                            <th style="width:200px;">Хүсэлтийн төрөл</th>
                                            <th style="width:100px;">Огноо</th>
                                            <th>Цаг</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if (isset($this->layoutPositionArr['pos_6'][2])) {
                                            foreach ($this->layoutPositionArr['pos_6'][2] as $key => $row) {
                                                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                                                ?>
                                                <tr data-rowdata="<?php echo $rowJson; ?>">
                                                    <td style="width:200px;">
                                                        <div class="d-flex align-items-center">
                                                            <div class="mt3 mr-2 position-relative">
                                                                <img src="<?php echo ($row['picture']) ? $row['picture'] : '' ?>" class="rounded-circle" onerror="onUserImageError(this);" width="38" height="38" alt="">
                                                            </div>
                                                            <div>
                                                                <a href="javascript:void(0);" class="text-black letter-icon-title"><?php echo $row['employeename'] ?></a>
                                                                <div class="text-muted font-size-12"><?php echo $row['positionname'] ?></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="width:200px;">
                                                        <div class="d-flex align-items-center"><div>
                                                                <div><a href='javascript:;' data-row='<?php echo $rowJson ?>' onclick="drilldownLinkCustome2_<?php echo $this->uniqId ?>(this)"><?php echo $row['booktypename'] ?></a></div>
                                                                <div class="text-muted font-size-12"><?php echo $row['description'] ?></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="width:100px;">
                                                        <div>
                                                            <div class="font-size-12"><?php echo $row['startdate'] ?></div>
                                                            <div class="font-size-12"><?php echo $row['enddate'] ?></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <div class="font-size-12"><?php echo $row['starttime'] ?></div>
                                                            <div class="font-size-12"><?php echo $row['endtime'] ?></div>
                                                        </div>
                                                    </td>
                                                </tr>        
                                            <?php }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card pl-3 pr-3 card-dashboard-four" style="height: 410px;">
                        <div class="card-header pt-2 pb-2">
                            <h6 class="card-title text-uppercase">Баримт бичиг шийдвэрлэлт</h6>
                            <div class="list-icons">
                                <a href="javascript:void(0);" class="btn bg-blue btn-sm"><?php echo (isset($this->layoutPositionArr['pos_6'][3]) ? sizeof($this->layoutPositionArr['pos_6'][3]) : 0)  ?></a>
                            </div>
                        </div>
                        <div class="card-body p-0 pb-3 table">
                            <div>
                                <table class="table table-striped table-dashboard-two mg-b-0" md-dataviewid="<?php echo $this->layoutPositionArr['pos_6_3_dvid'] ?>">
                                    <thead>
                                        <tr>
                                            <th style="width:200px;">Ажилтан</th>
                                            <th>Нийт</th>
                                            <th>Хянаж буй</th>
                                            <th>Шийдэж буй</th>
                                            <th>Хайж буй</th>
                                            <th>%</th>
                                            <th>Хоцорсон</th>
                                            <th>Хугацаа дөхсөн</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if (isset($this->layoutPositionArr['pos_6'][3])) {
                                                if (isset($this->layoutPositionArr['pos_6'][3][0]['departmentname'])) {
                                                    $pos_6Arr = Arr::groupByArrayOnlyRows($this->layoutPositionArr['pos_6'][3], 'departmentname');
                                                    foreach ($pos_6Arr as $key => $pos_6) { ?> 
                                                        <tr>
                                                            <td colspan='8' class="text-black letter-icon-title "> 
                                                                <a class="text-default collapsed" data-toggle="collapse" href="#collapsible-<?php echo $this->uniqId ?><?php echo $i ?>" aria-expanded="true"><?php echo $key; ?> </a>
                                                            </td>
                                                        </tr>
                                                        <?php foreach ($pos_6 as $row) {
                                                        $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>
                                                            <tr id="collapsible-<?php echo $this->uniqId ?><?php echo $i ?>" class="collapse show">
                                                                <td style="width:200px;">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="mt3 mr-2 position-relative">
                                                                            <img src="<?php echo ($row['picture']) ? $row['picture'] : '' ?>" class="rounded-circle" onerror="onUserImageError(this);" width="38" height="38" alt="">
                                                                        </div>
                                                                        <div>
                                                                            <a href="javascript:void(0);" class="text-black letter-icon-title"><?php echo $row['userfullname'] ?></a>
                                                                            <div class="text-muted font-size-12"><?php echo $row['positionname'] ?></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td><div class="font-size-12"><?php echo $row['totaldocument'] ?></div></td>
                                                                <td><div class="font-size-12"><?php echo $row['approvalcnt'] ?></div></td>
                                                                <td><div class="font-size-12"><?php echo $row['plancnt'] ?></div></td>
                                                                <td><div class="font-size-12"><?php echo $row['closingcnt'] ?></div></td>
                                                                <td><div class="font-size-12"><?php echo $row['totalpercent'] ?></div></td>
                                                                <td><div class="font-size-12"><?php echo $row['latecnt'] ?></div></td>
                                                                <td><div class="font-size-12"><?php echo $row['hurrycnt'] ?></div></td>

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
                                foreach ($this->layoutPositionArr['pos_8'][0] as $row) { ?>
                                    <div class="media mt-1">
                                        <div class="media-body pb-1 border-bottom-1 border-gray">
                                            <div class="d-flex flex-row justify-content-between">
                                                <a href="javascript:;" class="line-height-normal text-two-line" data-rowdata="<?php echo $rowJson; ?>" onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)" title="<?php echo $row['description'] ?>"><?php echo $row['description'] ?></a>
                                            </div>
                                            <p class="mb-0 font-size-12"><?php echo $row['name'] ?></p>
                                            <div class="d-flex justify-content-between">
                                                <small class="text-gray mr-2"><?php echo $row['tsag'] ?></small>
                                                <small class="text-gray">Шинээр нэмэгдсэн</small>
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
                                foreach ($this->layoutPositionArr['pos_8'][1] as $row) { ?>
                                    <div class="media mt-1" md-dataviewid="<?php echo $this->layoutPositionArr['pos_8_1_dvid'] ?>">
                                        <div class="media-body pb-1 border-bottom-1 border-gray">
                                            <div class="d-flex flex-row justify-content-between">
                                                <a href="javascript:;" class="line-height-normal text-two-line" data-rowdata="<?php echo $rowJson; ?>" onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)" title="<?php echo $row['description'] ?>"><?php echo $row['description'] ?></a>
                                            </div>
                                            <p class="mb-0 font-size-12"><?php echo $row['name'] ?></p>
                                            <div class="d-flex justify-content-between">
                                                <small class="text-gray mr-2"><?php echo $row['tsag'] ?></small>
                                                <small class="text-gray">Шинээр нэмэгдсэн</small>
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
<style type='text/css'>
    td > a.collapsed:before {
        content: "-";
    }

    a:before {
        right: 30px;
    }

    td > a:before {
        content: "+";
        font-family: icomoon;
        position: absolute;
        margin-top: -.5rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
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
    
    function drilldownLinkCustome2_<?php echo $this->uniqId ?> (element) {
        var row = JSON.parse($(element).attr('data-row'));
        
        gridDrillDownLink(element, 'feedbackListByEmployee', 'process,process,process,process,process,process,process,process,process,process,process,process,process,process,process', 15, '(booktypeid==9029 || booktypeid==9030) && isMulti == 1,booktypeid==9008 && isMulti ==1,booktypeid==9009 && isMulti == 1,(booktypeid==9043 || booktypeid ==9042) && isMulti == 1,(booktypeid==9032 || booktypeid==9033) && isMulti == 1,(booktypeid==9062 || booktypeid==9063 || booktypeid==9035 || booktypeid==9036 || booktypeid==9037) && isMulti == 1,booktypeid==9009 && isMulti !=1,(booktypeid==9043 || booktypeid==9042)&& isMulti !=1,(booktypeid==9051 || booktypeid==9027 || booktypeid==9049 || booktypeid==9046 || booktypeid==9045 || booktypeid==9061 || booktypeid==9024 || booktypeid==9025 || booktypeid==9022 || booktypeid==9048 || booktypeid==9086 || booktypeid==9023 ) && isMulti !=1,(booktypeid==9029 || booktypeid==9030) && isMulti != 1,(booktypeid==9062 || booktypeid==9063 || booktypeid==9035 || booktypeid==9036 || booktypeid==9037) && isMulti != 1,booktypeid==9008 && isMulti !=1,(booktypeid==9032 || booktypeid==9033) && isMulti != 1,(booktypeid==9045 || booktypeid==9046) && isMulti ==1,booktypeid==9059', '1568362804484', 'id', '1488543422698,1486037585030,1487042384774,1490149675416,1492489015885,1491375778581,1571475544133,1571475513504,1571475567150,1571475558829,1571998694279,1571998700423,1571998703494,1529564437946,1490789761271', 'id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +'', false, true, ',,,,,,,,,,,,,,,', ',,,,,,,,,,,,,,,'); 

//        gridDrillDownLink(element, 'feedbackListByEmployee', 'process,process,process,process,process,process,process,process,process,process,process,process,process,process,process', 15, '(booktypeid==9029 ||booktypeid==9030||booktypeid==9073||booktypeid==9074 || booktypeid==9075 || booktypeid==9076) && isMulti == 1,booktypeid==9008 && isMulti == 1,booktypeid==9009 && isMulti == 1,booktypeid==9043 && isMulti == 1,(booktypeid==9032 || booktypeid==9033) && isMulti == 1,(booktypeid==9062 || booktypeid==9063 || booktypeid==9035 || booktypeid==9036 || booktypeid==9037) && isMulti == 1,booktypeid==9059,booktypeid==9009,booktypeid==9043 && isMulti == 0,(booktypeid==9051 || booktypeid==9027 || booktypeid==9049 || booktypeid==9046 || booktypeid==9045 || booktypeid==9061 || booktypeid==9024 || booktypeid==9025 || booktypeid==9022 || booktypeid==9048 || booktypeid==9086) && isMulti !=1,(booktypeid==9029 || booktypeid==9030) && isMulti != 1,(booktypeid==9062 || booktypeid==9063 || booktypeid==9035 || booktypeid==9036 || booktypeid==9037) && isMulti == 0,booktypeid==9008 && isMulti == 0,(booktypeid==9032 || booktypeid==9033) && isMulti == 0,booktypeid==9051 || booktypeid==9027 || booktypeid==9049 || booktypeid==9046|| booktypeid==9045 || booktypeid==9061 || booktypeid==9024 || booktypeid==9025 || booktypeid==9022 || booktypeid==9048 || booktypeid==9086', '1568362804484', 'id', '1488543422698,1486037585030,1487042384774,1490149675416,1492489015885,1491375778581,1490941662449,1571475544133,1571475513504,1571475567150,1571475558829,1571998694279,1571998700423,1571998703494,1489236229167', 'id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +',id='+ row.id +'', false, true, ',,,,,,,,,,,,,,,', ',,,,,,,,,,,,,,,'); 
    }
    
    function drilldownLinkCustome1_<?php echo $this->uniqId ?> (element) {
        var row = JSON.parse($(element).attr('data-row'));
        gridDrillDownLink(element, 'tnaReportList18', 'metagroup', 1, '', '1567152804488', 'id', '1533787071544', 'filterstartdate='+ row.filterstartdate +'&filterenddate='+ row.filterenddate +'&employeeid='+ row.employeeid +'', true, true, ',', ','); 
    }
    
    function drilldownLinkCustome3_<?php echo $this->uniqId ?> (element) {
        var row = JSON.parse($(element).attr('data-row'));
        //gridDrillDownLink(element, 'DOC_DASHBOARD_CARDS_01', 'metagroup', '1', '', '1571474482320', 'cardvalue', '1554813741229', 'typeid='+ row.typeid + '&directionid='+ row.cardid + '&isnew1='+ row.isnew1, 'newrender', undefined,  '',  '')
        var criteria = 'defaultmetaid=1578488805200&isnew1='+ row.isnew1+'&typeid='+ row.typeid +',typeid='+ row.typeid +'&directionid=1&isnew1='+ row.isnew1+'&defaultmetaid=1554813741229,isnew1='+ row.isnew1+'&typeid='+ row.typeid +'&defaultmetaid=1578488805780,isnew1='+ row.isnew1+'&typeid='+ row.typeid +'&defaultmetaid=1578488807023';
        gridDrillDownLink(element, 'DOC_DASHBOARD_CARDS_01', 'package,package,package,package', '4', 'cardid==1,1==0,cardid==2,cardid==4', '1571474482320', 'cardvalue', '1580889642841862,1580889642841862,1580889642841862,1580889642841862', criteria, 'newrender', '',  '',  '');
    }
    
    function drilldownLink_intranet_<?php echo $this->uniqId ?> (element) {
        var selectedRow = JSON.parse($(element).attr('data-rowdata'));
        
        appMultiTab({weburl: 'government/intranet', metaDataId: 'government/intranet', dataViewId: selectedRow.id, title: 'Олон нийт', type: 'selfurl', recordId: selectedRow.id, selectedRow: selectedRow, tabReload: true});
    }
</script>