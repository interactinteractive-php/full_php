<?php
    (Array) $pos_5Arr = array();
    if (issetParam($this->layoutPositionArr['pos_5'][0]['cardtitle'])) {
        $pos_5Arr = Arr::groupByArrayOnlyRows($this->layoutPositionArr['pos_5'], 'cardtitle');
    }
    
    (Array) $pos_8Arr = array();
    if (issetParam($this->layoutPositionArr['pos_8'][0]['categorytypeid'])) {
        $pos_8Arr = Arr::groupByArray($this->layoutPositionArr['pos_8'], 'categorytypeid');
    }
    $colorData = array('#ff7e79','#ff8e51','#fec244','#39e0d0','#48c6f3','#6373ed');
?>
<style type="text/css">
    /* .header-tab, */
    #app_tab_governmentdashboardv1 .approve-requests,
    #app_tab_governmentdashboardv1 .employee-unitdashboard {
        display: none;
    }
    #chartdiv_pie2 {
        /*width: 50%;*/
        /*height: 300px;*/
    }
</style>
<link rel="stylesheet" href="assets/custom/addon/plugins/owl-carousel/owl.carousel.css">
<script src="assets/custom/addon/plugins/owl-carousel/owl.carousel.min.js"></script>
<script src="assets/custom/addon/plugins/amcharts/amcharts/amcharts.js"></script>
<script src="views/government/meeting/include/feather.min.js"></script>
<script type="text/javascript" src="assets/custom/addon/plugins/amcharts/amcharts/amChartMinify.js"></script>

<div class="app_dashboard dashboard<?php echo $this->uniqId ?> w-100">
    <div class="row">
        <div class="col pr-0">
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-hover card-prompt">
                        <div class="card-header bg-transparent">
                            <h6 class="card-title mg-b-0 text-danger" style="color: #CC0000 !important;">
                            <?php if(Config::getFromCache('isNotaryServer')) { ?>     
                                Мэдээ, мэдээлэл
                            <?php } else { ?>
                                <?php echo Lang::lineDefault('gov_dashboard_title_1', 'Шуурхай мэдээ') ?>
                            <?php } ?> 
                            </h6>
                            <nav class="nav nav-card-icon">
                                <a href="javascript:void(0);" class="link-gray-500"><i data-feather="refresh-ccw" class="svg-16"></i></a>
                            </nav>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled media-list mg-b-0 yellownews<?php echo $this->uniqId ?>">
                                <div class="w-100 pull-left removeTag">
                                    <div class="mb-3 skeleton-row">
                                        <div class="skeleton"></div>
                                        &nbsp;&nbsp;&nbsp;
                                        <div class="skeleton"></div>
                                    </div>
                                    <div class="mb-3 skeleton1"></div>
                                    <div class="mb-3 skeleton2"></div>
                                    <div class="mb-3 skeleton-row">
                                        <div class="skeleton3"></div>
                                        &nbsp;&nbsp;&nbsp;
                                        <div class="skeleton4"></div>
                                    </div>
                                </div>
                                <?php if (issetParam($this->layoutPositionArr['pos_0'])) {
                                foreach ($this->layoutPositionArr['pos_0'] as $key => $row) {
                                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                                $bgcolorData = array('event-panel-pink','event-panel-green','event-panel-primary','event-panel-orange');
                                ?>
                                    <li class="media" data-id="<?php echo $row['id'] ?>" style="cursor:pointer">
                                        <div class="media-left">
                                            <label><?php echo $row['monday'] ?></label>
                                            <p><?php echo $row['digitday'] ?></p>
                                        </div>
                                        <div class="media-body <?php echo $bgcolorData[rand(0, 3)]; ?> mt2">
                                            <?php if (Config::getFromCache('hideDtlinfo') == '1') { ?>
                                                <span class="event-time text-two-line" data-rowdata="<?php echo $rowJson; ?>" onclick="drilldownLink_hrbp_<?php echo $this->uniqId ?>(this)"><?php echo $row['description'] ?></span>
                                            <?php } else { ?>
                                                <span class="event-time text-two-line" data-showtype="dialog" data-rowdata="<?php echo $rowJson; ?>" onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)"><?php echo $row['description'] ?></span>
                                            <?php } ?>
                                        </div>
                                    </li>
                                <?php }} ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 px-md-0 slideText<?php echo $this->uniqId ?>">
                        <div id="carouselSlide2_<?php echo $this->uniqId ?>" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <?php if (issetParam($this->layoutPositionArr['pos_12'])) {
                                    foreach ($this->layoutPositionArr['pos_12'] as $key => $row) { ?>
                                        <li data-target="#carouselSlide2_<?php echo $this->uniqId ?>" data-slide-to="<?php echo $key; ?>" class="<?php echo $key == '0' ? 'active' : ''; ?>" data-id="<?php echo $row['id'] ?>"></li>
                                <?php }} ?>
                            </ol>
                            <div class="carousel-inner">
                                <?php 
                                    foreach (issetParamArray($this->layoutPositionArr['pos_12']) as $key => $row) { 
                                        $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                                        ?>
                                    <div class="carousel-item <?php echo $key == '0' ? 'active' : ''; ?>" data-id="<?php echo $row['id'] ?>">
                                        <div class="card card-hover card-blog-one">
                                            <a href="javascript:void(0);" onclick="wordstrue<?php echo $this->uniqId ?>(this);" data-rowdata="<?php echo $rowJson ?>">
                                                <div class="marker t-10 l-10 text-black text-one-line font-family-Oswald">
                                                    <?php echo $row['description'] ?>
                                                </div>
                                                <div class="card-body" style="display:flex;">
                                                    <div class="col-4 mt-3">
                                                        <img src="<?php echo issetParam($row['thumbphysicalpath']) ?>" class="card-img" onerror="onUserImageError(this);">
                                                    </div>
                                                    <div class="col-8 mt-3">
                                                        <p class="card-desc text-ten-line"><?php echo issetParam($row['body']) ? Str::htmltotext($row['body']) : '' ?></p>
                                                    </div>
                                                    
                                                </div>
                                            </a>
                                            <div class="card-footer">
                                                <a href="javascript:void(0);" class="mr-3" onclick="like_<?php echo $this->uniqId ?>('<?php echo $row['id']; ?>', 'post', '1', this)"><i ssdata-feather="heart" class="ssssvg-14 font-size-13 mr5 fa fa-heart<?php echo $row['isliked'] == '1' ? '' : '-o' ?> "></i> <span class="likecc"><?php echo $row['likecount'] ?></span></a>
                                                <span class="tx-gray-500 ml-auto d-flex"><i data-feather="calendar" class="svg-14"></i> <span><?php echo $row['tsag'] ?></span></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <a class="carousel-control-prev" href="#carouselSlide2_<?php echo $this->uniqId ?>" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"><i class="icon-arrow-left32"></i></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselSlide2_<?php echo $this->uniqId ?>" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"><i class="icon-arrow-right32"></i></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                </div>
                <div class="col-md-4">
                    <?php if(!Config::getFromCache('isNotaryServer')) { ?>
                    <div class="card card-hover card-campaign-one">
                        <div class="card-header bg-transparent">
                            <h6 class="card-title mg-b-0"><?php echo Lang::lineDefault('gov_dashboard_title_2 ', 'Явцын мэдээлэл') ?></h6>
                            <nav class="nav nav-card-icon">
                                <a href="javascript:void(0);"><i data-feather="refresh-ccw"></i></a>
                            </nav>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center drill-chart">
                            <div class="row w-100">
                                <div class="col-4">
                                    <div class="chart1<?php echo $this->uniqId ?>" data-percent="<?php echo issetParamZero($this->layoutPositionArr['pos_1'][0]['percent']) ?>">
                                        <span class="percent"></span>
                                    </div>
                                    <p class="chart-label text-center" onclick="appMultiTab({metaDataId: '1590027122788', title: '<?php echo Lang::line('dashboard_doc_title_001') ?>', type: 'dataview'}, this);"><?php echo Lang::line('dashboard_doc_title_001') ?></p>
                                </div>
                                <div class="col-4">
                                    <div class="chart2<?php echo $this->uniqId ?>" data-percent="<?php echo issetParamZero($this->layoutPositionArr['pos_2'][0]['totalpercent']) ?>">
                                        <span class="percent"></span>
                                    </div>
                                    <p class="chart-label text-center" onclick="appMultiTab({metaDataId: '1590046031611016', title: 'Ажил үүрэг', type: 'dataview'}, this);"><?php echo Lang::lineDefault('gov_dashboard_title_3', 'Ажил<br>шийдвэрлэлт') ?></p>
                                </div>
                                <div class="col-4">
                                    <div class="chart3<?php echo $this->uniqId ?>" data-percent="<?php echo issetParamZero($this->layoutPositionArr['pos_3'][0]['workedtimepercent']) ?>">
                                        <span class="percent"></span>
                                    </div>
                                    <p class="chart-label text-center" onclick="appMultiTab({metaDataId: '1533876130306', title: 'Цаг бүртгэл', type: 'dataview', proxyId: ''}, this);"><?php echo Lang::lineDefault('gov_dashboard_title_4', 'Цаг<br>ашиглалт') ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="card card-hover card-prompt">
                        <div class="card-header bg-transparent">
                            <h6 class="card-title mg-b-0">Хууль эрх зүй</h6>
                            <nav class="nav nav-card-icon">
                                <a href="javascript:void(0);"><i data-feather="refresh-ccw"></i></a>
                            </nav>
                        </div>
                       
                        <div class="card-body">
                            <ul class="list-unstyled media-list mg-b-0 yellownews<?php echo $this->uniqId ?>">
                                <?php if (issetParam($this->legalList)) {
                                    foreach ($this->legalList as $key => $row) {
                                    $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                                    $bgcolorData = array('event-panel-pink','event-panel-green','event-panel-primary','event-panel-orange');
                                    ?>
                                        <li class="media" data-id="<?php echo $row['id'] ?>">
                                            <div class="media-left">
                                                <label><?php echo $row['monday'] ?></label>
                                                <p><?php echo $row['digitday'] ?></p>
                                            </div>
                                            <div class="media-body <?php echo $bgcolorData[rand(0, 3)]; ?> mt2">
                                                <span class="event-time text-two-line" data-rowdata="<?php echo $rowJson; ?>" onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)"><?php echo $row['description'] ?></span>
                                            </div>
                                        </li>
                                    <?php }
                                } ?>
                            </ul>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-12">
                    <div class="card card-hover card-profile-sidebar card-three-col">
                        <?php if (!Config::getFromCache('isNotaryServer')) { ?>
                        <div class="card-header bg-transparent with-border-bottom">
                            <h6 class="card-title mg-b-0"><?php echo Lang::line('dashboard_doc_title_002') ?></h6>
                        </div>
                        <?php } ?>
                        <div class="row documentrender documentrender<?php echo $this->uniqId ?>">
                            <div class="p-3 w-100 pull-left min-height-150">
                                <div class="mb-3 skeleton-row">
                                    <div class="skeleton"></div>
                                    &nbsp;&nbsp;&nbsp;
                                    <div class="skeleton"></div>
                                </div>
                                <div class="mb-3 skeleton1"></div>
                                <div class="mb-3 skeleton2"></div>
                                <div class="mb-3 skeleton-row">
                                    <div class="skeleton3"></div>
                                    &nbsp;&nbsp;&nbsp;
                                    <div class="skeleton4"></div>
                                </div>
                            </div>
                            <?php if ($pos_5Arr) { foreach ($pos_5Arr as $key => $row) { ?>
                                <div class="<?php echo Config::getFromCache('isNotaryServer') ? 'col' : 'col-4' ?>">
                                    <div class="card-header bg-transparent">
                                        <div class="media align-items-center">
                                            <div class="media-body">
                                            <h6 class="tx-gray mb-0"><?php echo $key; ?></h6>
                                            </div>
                                        </div>
                                        <div class="profile-info">
                                            <?php foreach ($row as $key2 => $subRow) { $rowJson = htmlentities(json_encode($subRow), ENT_QUOTES, 'UTF-8'); ?>
                                                <div class="<?php echo Config::getFromCache('isNotaryServer') ? 'col' : 'col-md-4' ?> three-card-col">
                                                    <h5 class="tx-primary font-size-18 mb-1">
                                                        <?php if (Config::getFromCache('hideDtlinfo') == '1') { ?>
                                                            <a href="javascript:;" data-row='<?php echo $rowJson ?>' onclick="drilldownLink_hr2_<?php echo $this->uniqId ?>(this)"><?php echo $subRow['cardvalue'] ?></a>
                                                        <?php } else { ?>                                                                                                                    
                                                            <a href="javascript:;" data-row='<?php echo $rowJson ?>' onclick="drilldownLinkCustome3_<?php echo $this->uniqId ?>(this)"><?php echo $subRow['cardvalue'] ?></a>
                                                        <?php } ?>
                                                    </h5>
                                                    <p><?php echo $subRow['cardname'] ?></p>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php }} ?>
                        </div>
                    </div>
                </div>
                <?php if (Config::getFromCache('isNotaryServer')) { ?>
                <div class="col-md-4">
                    <div class="card card-hover card-campaign-one" style="height: 400px;">
                         <div class="card-header bg-transparent">
                            <h6 class="card-title mg-b-0">Хүйсээр</h6>
                            <nav class="nav nav-card-icon">
                                <a href="javascript:void(0);"><i data-feather="refresh-ccw"></i></a>
                            </nav>
                        </div>   
                        <div id="chartdiv_pie2" style="height: 300px;"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-hover card-campaign-one" style="height: 400px;">
                     <div class="card-header bg-transparent">
                        <h6 class="card-title mg-b-0">Насны ангилал</h6>
                        <nav class="nav nav-card-icon">
                            <a href="javascript:void(0);"><i data-feather="refresh-ccw"></i></a>
                        </nav>
                    </div>   
                    <div id="chartdiv" style="height: 350px;"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-hover card-campaign-one" style="height: 400px;">
                     <div class="card-header bg-transparent">
                        <h6 class="card-title mg-b-0">Ажилчдын төлөв</h6>
                        <nav class="nav nav-card-icon">
                            <a href="javascript:void(0);"><i data-feather="refresh-ccw"></i></a>
                        </nav>
                    </div>   
                        <div class="card card-body border-top-primary">
                        <?php // var_dump($this->notariasStatus) ?>
                    <!--<div id="notariasstatus" style="height: 370px;"></div>-->
                        <?php if(isset($this->notariasStatusP)) { ?>
                            <?php foreach($this->notariasStatusP as $key => $status) {?>
                            <span class="text-left font-weight-bold"><?php echo $status['currrentstatusname'] ?></span>
                            <div class="progress mb-3">
                                <span class="text-white" style="position:absolute; padding: 2px;"><?php echo $status['percent'] ?>%</span>
                                    <div class="progress-bar" style="width: <?php echo $status['percent'] ?>%; background: <?php echo $status['color']; ?> !important; border-radius: 5px;">
                                            <span class="sr-only"><?php echo $status['currrentstatusname'] ?></span>
                                    </div>
                            </div>
                            <?php } ?>
                        <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php if(!Config::getFromCache('isNotaryServer') && Config::getFromCache('hideemployeeInfo') != '1') { ?>    
                <div class="col-md-12 employee-unitdashboard job<?php echo $this->uniqId ?>">
                    <div class="card card-hover card-profile-sidebar">
                        <div class="card-header bg-transparent with-border-bottom">
                            <h6 class="card-title mg-b-0"><?php echo Lang::line('Ажилтны явцын хяналт') ?></h6>
                        </div>
                        <div class="row search-form d-none">
                            <div class="d-flex flex-row" style="width:70%;">
                                <div class="col-3 form-group">
                                    <label>Хайлт</label>
                                    <input type="text" class="form-control border-radius-100 filterName<?php echo $this->uniqId ?>" name="filterName" name="format-date" placeholder="Хайлт">
                                </div>
                                <div class="ml-auto d-flex flex-row">
                                    <div class="col form-group">
                                        <label>Эхлэх огноо</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control dateInit" style="border-radius: 100px 0 0 100px;" placeholder="Эхлэх огноо">
                                            <span class="input-group-prepend input-group-btn">
                                                <button class="input-group-text btn" tabindex="-1" name="filteStartDate" style="border-radius: 0 100px 100px 0;border-left: 0;" onclick="return false;"><i class="icon-calendar"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col form-group">
                                        <label>Дуусах огноо</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control dateInit" name="filteEndDate" style="border-radius: 100px 0 0 100px;" placeholder="Дуусах огноо">
                                            <span class="input-group-prepend input-group-btn">
                                                <button class="input-group-text btn" tabindex="-1" style="border-radius: 0 100px 100px 0;border-left: 0;" onclick="return false;"><i class="icon-calendar"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-auto mt-3">
                                <button type="button" class="btn bg-orange rounded-round pt6 pb6 px-5 mr-2">Хайх</button>
                            </div>
                        </div>
                        <div class="table-responsive" style="max-height: 450px; height: 450px; ">
                            <?php if (issetParam($this->layoutPositionArr['pos_11'])) {
                                $currentDate = Date::currentDate();
                                $dd = Date::format('D', $currentDate);
                                if (Config::getFromCache('addWorkin7Days') === '1') {
                                    $a7 = Date::weekdayAfter('Y-m-d', $currentDate, ' -6 days'); //Date::addWorkingDays('Y-m-d', '-', $currentDate, 6, false);
                                    $a6 = Date::weekdayAfter('Y-m-d', $currentDate, ' -5 days'); //Date::addWorkingDays('Y-m-d', '-', $currentDate, 5, false);
                                    $a1 = Date::weekdayAfter('Y-m-d', $currentDate, ' -4 days'); //Date::addWorkingDays('Y-m-d', '-', $currentDate, 4, false);
                                    $a2 = Date::weekdayAfter('Y-m-d', $currentDate, ' -3 days'); //Date::addWorkingDays('Y-m-d', '-', $currentDate, 3, false);
                                    $a3 = Date::weekdayAfter('Y-m-d', $currentDate, ' -2 days'); //Date::addWorkingDays('Y-m-d', '-', $currentDate, 2, false);
                                    $a4 = Date::weekdayAfter('Y-m-d', $currentDate, ' -1 days'); //Date::addWorkingDays('Y-m-d', '-', $currentDate, 1, false);
                                    $a5 = Date::weekdayAfter('Y-m-d', $currentDate, ' -0 days'); //Date::addWorkingDays('Y-m-d', '-', $currentDate, 0, false);
                                    ?>
                                    <table class="table task-table task-<?php echo $this->uniqId; ?>" id="example<?php echo $this->uniqId ?>">
                                        <thead style="background-color: #f9f9fd;">
                                            <tr>
                                                <th rowspan="2" style="vertical-align:bottom;">Нэр</th>
                                                <th class="text-center border-0" colspan="7" style="vertical-align:bottom;"></th>
                                                <th rowspan="2" style="vertical-align:bottom;width: 150px;">Ажлын гүйцэтгэл</th>
                                                <th class="text-right" rowspan="2" style="vertical-align:bottom;width: 150px;">Баримт бичиг шийдвэрлэлт</th>
                                                <!-- <th class="text-center" style="width: 20px;"><i class="icon-arrow-down12"></i></th> -->
                                            </tr>
                                            <tr>
                                                <th style="border-top: 0;width: 80px;" class="<?php echo Date::format('D', $a7); ?>"><?php echo Date::format('m/d', $a7) . ' ('. Lang::line(Date::format('D', $a7)) .')' ?></th>
                                                <th style="border-top: 0;width: 80px;" class="<?php echo Date::format('D', $a6); ?>"><?php echo Date::format('m/d', $a6) . ' ('. Lang::line(Date::format('D', $a6)) .')' ?></th>
                                                <th style="border-top: 0;width: 80px;" class="<?php echo Date::format('D', $a1); ?>"><?php echo Date::format('m/d', $a1) . ' ('. Lang::line(Date::format('D', $a1)) .')' ?></th>
                                                <th style="border-top: 0;width: 80px;" class="<?php echo Date::format('D', $a2); ?>"><?php echo Date::format('m/d', $a2) . ' ('. Lang::line(Date::format('D', $a2)) .')' ?></th>
                                                <th style="border-top: 0;width: 80px;" class="<?php echo Date::format('D', $a3); ?>"><?php echo Date::format('m/d', $a3) . ' ('. Lang::line(Date::format('D', $a3)) .')' ?></th>
                                                <th style="border-top: 0;width: 80px;" class="<?php echo Date::format('D', $a4); ?>"><?php echo Date::format('m/d', $a4) . ' ('. Lang::line(Date::format('D', $a4)) .')' ?></th>
                                                <th style="border-top: 0;width: 80px;" class="<?php echo Date::format('D', $a5); ?>"><?php echo Date::format('m/d', $a5) . ' ('. Lang::line(Date::format('D', $a5)) .')' ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                foreach ($this->layoutPositionArr['pos_11'] as $key => $row) { ?>
                                                    <tr data-id="<?php echo $row['userid'] ?>">
                                                        <td style="width: 350px;">
                                                            <div class="d-flex align-items-center">
                                                                <div class="mr-2">
                                                                    <a href="javascript:void(0);">
                                                                        <img src="<?php echo $row['picture'] ?>" class="rounded-circle" width="34" height="34" alt="" onerror="onUserImageError(this);" data-userid="<?php echo issetParam($row['userid']) ? $row['userid'] : '' ?>">
                                                                    </a>
                                                                </div>
                                                                <div>
                                                                    <a href="javascript:void(0);" class="text-blue font-weight-bold" onclick="appMultiTab({metaDataId: '1533876130306', title: 'Цаг бүртгэл', type: 'dataview', proxyId: ''}, this);"><?php echo $row['employeename'] ?></a>
                                                                    <div class="text-muted font-size-sm">
                                                                        <div class="line-height-normal font-size-11"><?php echo $row['positionname'] ?></div>
                                                                        <span class="line-height-normal font-size-11"><?php echo $row['departmentname'] ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="<?php echo Date::format('D', $a7); ?>"><div class="text-black" style="width: 80px;"><?php echo $row[Str::lower(Date::format('l', $a7)) . 'outtime'] ?></div></td>
                                                        <td class="<?php echo Date::format('D', $a6); ?>"><div class="text-black" style="width: 80px;"><?php echo $row[Str::lower(Date::format('l', $a6)) . 'outtime'] ?></div></td>
                                                        <td class="<?php echo Date::format('D', $a1); ?>"><div class="text-black" style="width: 80px;"><?php echo $row[Str::lower(Date::format('l', $a1)) . 'outtime'] ?></div></td>
                                                        <td class="<?php echo Date::format('D', $a2); ?>"><div class="text-black" style="width: 80px;"><?php echo $row[Str::lower(Date::format('l', $a2)) . 'outtime'] ?></div></td>
                                                        <td class="<?php echo Date::format('D', $a3); ?>"><div class="text-black" style="width: 80px;"><?php echo $row[Str::lower(Date::format('l', $a3)) . 'outtime'] ?></div></td>
                                                        <td class="<?php echo Date::format('D', $a4); ?>"><div class="text-black" style="width: 80px;"><?php echo $row[Str::lower(Date::format('l', $a4)) . 'outtime'] ?></div></td>
                                                        <td class="<?php echo Date::format('D', $a5); ?>"><div class="text-black" style="width: 80px;"><?php echo $row[Str::lower(Date::format('l', $a5)) . 'outtime'] ?></div></td>
                                                        <td style="width: 150px;">
                                                            <?php echo $row['taskpercent'] ?>
                                                            <div class="progress mt-1" style="height: 0.375rem;">
                                                                <div class="progress-bar bg-success" style="width: <?php echo $row['taskpercent2'] ?>%">
                                                                    <span class="sr-only"><?php echo $row['taskpercent2'] ?>% Complete</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 150px;">
                                                            <div class="d-flex align-items-center justify-content-end">
                                                                <span class="text-black font-weight-bold mr-2"><?php echo ($row['documentcc'] == '0/0') ? '' : $row['documentcc'] ?></span>
                                                                <span class="badge border-radius-100 <?php echo (int) $row['totalpercent'] > '50' ? 'bg-success' : 'bg-danger';  ?> "><?php echo ($row['documentcc'] == '0/0') ? '' : $row['totalpercent'] ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <?php 
                                } else {
                                    
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
                                    $a5 = Date::addWorkingDays('Y-m-d', '-', $currentDate, 0); ?>
                                
                                <table class="table task-table task-<?php echo $this->uniqId; ?>" id="example<?php echo $this->uniqId ?>">
                                    <thead style="background-color: #f9f9fd;">
                                        <tr>
                                            <th rowspan="2" style="vertical-align:bottom;">Нэр</th>
                                            <th class="text-center border-0" colspan="5" style="vertical-align:bottom;"></th>
                                            <th rowspan="2" style="vertical-align:bottom;width: 150px;">Ажлын гүйцэтгэл</th>
                                            <th class="text-right" rowspan="2" style="vertical-align:bottom;width: 150px;">Баримт бичиг шийдвэрлэлт</th>
                                            <!-- <th class="text-center" style="width: 20px;"><i class="icon-arrow-down12"></i></th> -->
                                        </tr>
                                        <tr>
                                            <th style="border-top: 0;width: 80px;"><?php echo Date::format('m/d', $a1) . ' ('. Lang::line(Date::format('D', $a1)) .')' ?></th>
                                            <th style="border-top: 0;width: 80px;"><?php echo Date::format('m/d', $a2) . ' ('. Lang::line(Date::format('D', $a2)) .')' ?></th>
                                            <th style="border-top: 0;width: 80px;"><?php echo Date::format('m/d', $a3) . ' ('. Lang::line(Date::format('D', $a3)) .')' ?></th>
                                            <th style="border-top: 0;width: 80px;"><?php echo Date::format('m/d', $a4) . ' ('. Lang::line(Date::format('D', $a4)) .')' ?></th>
                                            <th style="border-top: 0;width: 80px;"><?php echo Date::format('m/d', $a5) . ' ('. Lang::line(Date::format('D', $a5)) .')' ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            foreach ($this->layoutPositionArr['pos_11'] as $key => $row) {
                                                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                                                ?>
                                                <tr data-id="<?php echo $row['userid'] ?>" data-row="<?php echo $rowJson; ?>">
                                                    <td style="width: 350px;">
                                                        <div class="d-flex align-items-center">
                                                            <div class="mr-2">
                                                                <a href="javascript:void(0);">
                                                                    <img src="<?php echo $row['picture'] ?>" class="rounded-circle" width="34" height="34" alt="" onerror="onUserImageError(this);" data-userid="<?php echo issetParam($row['userid']) ? $row['userid'] : '' ?>">
                                                                </a>
                                                            </div>
                                                            <div>
                                                                <a href="javascript:void(0);" class="text-blue font-weight-bold" onclick="gridDrillDownLink(this, 'GOV_NEW_DASHBOARD_LIST', 'metagroup', '1', '', '1587095552879524', 'firstname', '1533787071544', 'employeeid=<?php echo $row['employeeid'] ?>', true, undefined,  '',  '')"><?php echo $row['employeename'] ?></a>
                                                                <div class="text-muted font-size-sm">
                                                                    <div class="line-height-normal font-size-11"><?php echo $row['positionname'] ?></div>
                                                                    <span class="line-height-normal font-size-11"><?php echo $row['departmentname'] ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><div class="text-black" style="width: 80px;"><?php echo $row[Str::lower(Date::format('l', $a1)) . 'outtime'] ?></div></td>
                                                    <td><div class="text-black" style="width: 80px;"><?php echo $row[Str::lower(Date::format('l', $a2)) . 'outtime'] ?></div></td>
                                                    <td><div class="text-black" style="width: 80px;"><?php echo $row[Str::lower(Date::format('l', $a3)) . 'outtime'] ?></div></td>
                                                    <td><div class="text-black" style="width: 80px;"><?php echo $row[Str::lower(Date::format('l', $a4)) . 'outtime'] ?></div></td>
                                                    <td><div class="text-black" style="width: 80px;"><?php echo $row[Str::lower(Date::format('l', $a5)) . 'outtime'] ?></div></td>
                                                    <td style="width: 150px;">
                                                        <?php echo $row['taskpercent'] ?>
                                                        <div class="progress mt-1" style="height: 0.375rem;">
                                                            <div class="progress-bar bg-success" style="width: <?php echo $row['taskpercent2'] ?>%">
                                                                <span class="sr-only"><?php echo $row['taskpercent2'] ?>% Complete</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="width: 150px;">
                                                        <div class="d-flex align-items-center justify-content-end">
                                                            <span class="text-black font-weight-bold mr-2"><?php echo ($row['documentcc'] == '0/0') ? '' : $row['documentcc'] ?></span>
                                                            <span class="badge border-radius-100 <?php echo (int) $row['totalpercent'] > '50' ? 'bg-success' : 'bg-danger';  ?> "><?php echo ($row['documentcc'] == '0/0') ? '' : $row['totalpercent'] ?></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            <?php } ?>
                            <?php  } else { ?>
                            <div class="p-3 w-100 pull-left removeTag">
                                <div class="mb-3 skeleton-row">
                                    <div class="skeleton"></div>
                                    &nbsp;&nbsp;&nbsp;
                                    <div class="skeleton"></div>
                                </div>
                                <div class="mb-3 skeleton1"></div>
                                <div class="mb-3 skeleton2"></div>
                                <div class="mb-3 skeleton-row">
                                    <div class="skeleton3"></div>
                                    &nbsp;&nbsp;&nbsp;
                                    <div class="skeleton4"></div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div> 
                <?php } ?>
                <div class="col-md-12">
                    <div class="card card-hover card-profile-sidebar card-event-calendar">
                        <?php echo issetParam($this->eventBox) ? $this->eventBox : '' ?>
                    </div>
                </div>
                <?php if (Config::getFromCache('isNotaryServer')) { ?>
                <div class="col-md-12 employee-unitdashboard job<?php echo $this->uniqId ?> d-none">
                    <div class="card card-hover card-profile-sidebar">
                        <div class="card-header bg-transparent with-border-bottom">
                            <h6 class="card-title mg-b-0">Нотариатчдын мэдээлэл</h6>
                        </div>
                        <div class="row mt-1 mb-1 pl-5 pr-5 d-flex justify-content-center">
                            <div class="col-4">
                                <div style="display: inline-flex; align-items: center;">
                                    <label class="mr-3 mb-0">Байршил сонгох</label>
                                    <select class="form-control text-uppercase" id="departmentid" style="line-height: 1; font-size: 11px !important; height: 34px; padding: 2px 6px; background: #f5f5f5;width: 180px; font-weight: 700;" onchange="list(1)">
                                        <option value="" selected="">Бүгд</option>
                                        <?php foreach ($this->locationLookup as $location) { ?>
                                            <option value="<?php echo $location['id'] ?>"><?php echo $location['name'] ?></option> 
                                        <?php } ?>
                                </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div style="display: inline-flex; align-items: center;">
                                    <label class="mr-3 mb-0">Ажиллах өдөр сонгох</label>
                                    <select class="form-control text-uppercase" id="scheduleid" style="line-height: 1; font-size: 11px !important; height: 34px; padding: 2px 6px; background: #f5f5f5;width: 103px; font-weight: 700;" onchange="list(1)">
                                        <option value="" selected="">Бүгд</option>
                                        <?php foreach ($this->timeTableLookup as $timetable) { ?>
                                            <option value="<?php echo $timetable['id'] ?>"><?php echo $timetable['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row search-form d-none">
                            <div class="d-flex flex-row" style="width:70%;">
                                <div class="col-3 form-group">
                                    <label>Хайлт</label>
                                    <input type="text" class="form-control border-radius-100 filterName<?php echo $this->uniqId ?>" name="filterName" name="format-date" placeholder="Хайлт">
                                </div>
                                <div class="ml-auto d-flex flex-row">
                                    <div class="col form-group">
                                        <label>Эхлэх огноо</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control dateInit" style="border-radius: 100px 0 0 100px;" placeholder="Эхлэх огноо">
                                            <span class="input-group-prepend input-group-btn">
                                                <button class="input-group-text btn" tabindex="-1" name="filteStartDate" style="border-radius: 0 100px 100px 0;border-left: 0;" onclick="return false;"><i class="icon-calendar"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col form-group">
                                        <label>Дуусах огноо</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control dateInit" name="filteEndDate" style="border-radius: 100px 0 0 100px;" placeholder="Дуусах огноо">
                                            <span class="input-group-prepend input-group-btn">
                                                <button class="input-group-text btn" tabindex="-1" style="border-radius: 0 100px 100px 0;border-left: 0;" onclick="return false;"><i class="icon-calendar"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-auto mt-3">
                                <button type="button" class="btn bg-orange rounded-round pt6 pb6 px-5 mr-2">Хайх</button>
                            </div>
                        </div>
                        <div class="table-responsive" style="max-height: 450px; height: 450px; ">
                            <?php if (issetParam($this->notariesList)) { ?> 
                                <table class="table task-table task-<?php echo $this->uniqId; ?>" id="example<?php echo $this->uniqId ?>">
                                    <thead style="background-color: #f9f9fd;">
                                        <tr>
                                            <th rowspan="2" style="vertical-align:bottom;">Нэр</th>
                                            <th class="text-center border-0" colspan="5" style="vertical-align:bottom;"></th>
<!--                                            <th rowspan="2" style="vertical-align:bottom;width: 150px;">Ажлын гүйцэтгэл</th>
                                            <th class="text-right" rowspan="2" style="vertical-align:bottom;width: 150px;">Баримт бичиг шийдвэрлэлт</th>-->
                                            <!-- <th class="text-center" style="width: 20px;"><i class="icon-arrow-down12"></i></th> -->
                                        </tr>
                                        <tr>
                                            <th style="border-top: 0;width: 80px;">Код</th>
                                            <th style="border-top: 0;width: 200px;">Байршил</th>
                                            <th style="border-top: 0;width: 80px;">Утас</th>
                                            <th style="border-top: 0;width: 80px;">Цагийн хуваарь</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            foreach ($this->notariesList as $key => $row) { ?>
                                                <tr data-id="<?php echo $row['personid'] ?>">
                                                    <td style="width: 350px;">
                                                        <div class="d-flex align-items-center">
                                                            <div class="mr-2">
                                                                <a href="javascript:void(0);">
                                                                    <img src="<?php echo $row['officeimage'] ?>" class="rounded-circle" width="34" height="34" alt="" onerror="onUserImageError(this);" data-userid="<?php echo issetParam($row['personid']) ? $row['personid'] : '' ?>">
                                                                </a>
                                                            </div>
                                                            <div>
                                                                <a href="javascript:void(0);" class="text-blue font-weight-bold" onclick="appMultiTab({metaDataId: '1533876130306', title: 'Цаг бүртгэл', type: 'dataview', proxyId: ''}, this);"><?php echo $row['fullname'] ?></a>
                                                                <div class="text-muted font-size-sm">
                                                                    <div class="line-height-normal font-size-11"><?php echo $row['positionname'] ?></div>
                                                                    <span class="line-height-normal font-size-11"><?php echo $row['departmentname'] ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><div class="text-black" style="width: 80px;"><?php echo $row['employeecode'] ?></div></td>
                                                    <td><div class="text-black" style="width: 200px;"><?php echo $row['employeeaddress'] ?></div></td>
                                                    <td><div class="text-black" style="width: 80px;"><?php echo $row['employeephone'] ?></div></td>
                                                    <td><div class="text-black" style="width: 80px;"><?php echo $row['schedule'] ?></div></td>
<!--                                                    <td style="width: 150px;">
                                                        <?php echo $row['fullname'] ?>
                                                        <div class="progress mt-1" style="height: 0.375rem;">
                                                            <div class="progress-bar bg-success" style="width: <?php echo $row['fullname'] ?>%">
                                                                <span class="sr-only"><?php echo $row['fullname'] ?>% Complete</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="width: 150px;">
                                                        <div class="d-flex align-items-center justify-content-end">
                                                            <span class="text-black font-weight-bold mr-2"></span>
                                                            <span class="badge border-radius-100 "></span>
                                                        </div>
                                                    </td>-->
                                                </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            <?php  } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php if(!Config::getFromCache('isNotaryServer')) { ?>
                <div class="col-md-12 mb10<?php echo Config::getFromCache('hideDtlinfo') == '1' ? ' d-none' : '' ?>">
                    <div id="carouselSlideNew_<?php echo $this->uniqId ?>" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <?php if (issetParam($this->layoutPositionArr['pos_13'])) {
                                foreach ($this->layoutPositionArr['pos_13'] as $key => $row) { ?>
                                    <li data-target="#carouselSlideNew_<?php echo $this->uniqId ?>" data-slide-to="<?php echo $key; ?>" class="<?php echo $key == '0' ? 'active' : ''; ?>"></li>
                            <?php }
                            } ?>
                        </ol>
                        <div class="carousel-inner">
                            <?php if ($this->layoutPositionArr['pos_13']) {
                                foreach ($this->layoutPositionArr['pos_13'] as $key => $row) { ?>
                                    <div class="carousel-item <?php echo $key == '0' ? 'active' : ''; ?>">
                                        <a href="<?php echo ($row['imgurl']) ? $row['imgurl'] : 'javascript:;' ?>" <?php echo ($row['imgurl']) ? 'target="_blank"' : '' ?> class="marker t-10 l-10 bg-success tx-white">
                                            <?php echo $row['description'] ?>
                                        </a>
                                        <img class="d-block w-100" src="<?php echo $row['physicalpath'] ?>" alt="<?php echo $row['filename'] ?>" height="335">
                                    </div>
                                <?php }
                            } else { ?>
                                <div class="p-3 w-100 pull-left removeTag">
                                    <div class="mb-3 skeleton-row">
                                        <div class="skeleton"></div>
                                        &nbsp;&nbsp;&nbsp;
                                        <div class="skeleton"></div>
                                    </div>
                                    <div class="mb-3 skeleton1"></div>
                                    <div class="mb-3 skeleton2"></div>
                                    <div class="mb-3 skeleton-row">
                                        <div class="skeleton3"></div>
                                        &nbsp;&nbsp;&nbsp;
                                        <div class="skeleton4"></div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <a class="carousel-control-prev" href="#carouselSlideNew_<?php echo $this->uniqId ?>" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"><i class="icon-arrow-left32"></i></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselSlideNew_<?php echo $this->uniqId ?>" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"><i class="icon-arrow-right32"></i></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                
                <div class="col-md-8<?php echo Config::getFromCache('hideDtlinfo') == '1' ? ' d-none' : '' ?>">
                    <div class="card card-hover card-transactions card-forum">
                        <div class="card-header bg-transparent">
                            <h6 class="card-title mg-b-0"><?php echo Lang::line('Хэлэлцүүлэг') ?></h6>
                            <nav class="nav nav-card-icon">
                                <a href="javascript:void(0);"><i data-feather="refresh-ccw"></i></a>
                            </nav>
                        </div>
                        <ul class="list-group list-group-flush heleltsuuleg<?php echo $this->uniqId ?>">
                            <div class="p-3 w-100 pull-left removeTag">
                                <div class="mb-3 skeleton-row">
                                    <div class="skeleton"></div>
                                    &nbsp;&nbsp;&nbsp;
                                    <div class="skeleton"></div>
                                </div>
                                <div class="mb-3 skeleton1"></div>
                                <div class="mb-3 skeleton2"></div>
                                <div class="mb-3 skeleton-row">
                                    <div class="skeleton3"></div>
                                    &nbsp;&nbsp;&nbsp;
                                    <div class="skeleton4"></div>
                                </div>
                            </div>
                            <?php if (issetParam($this->layoutPositionArr['pos_9'])) {
                                foreach ($this->layoutPositionArr['pos_9'] as $key => $row) {
                                    $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                                ?>
                                    <li class="list-group-item align-items-center" data-id="<?php echo $row['id'] ?>">
                                        <a href="javascript:;" data-rowdata="<?php echo $rowJson; ?>" onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)">
                                            <div class="avatar mr-2">
                                                <img src="<?php echo $row['physicalpath'] ?>" class="rounded-circle" alt="" onerror="onUserImageError(this);" data-userid="<?php echo $row['userid'] ?>">
                                            </div>
                                        </a>
                                        <div class="mr-3">
                                            <a href="javascript:;" data-rowdata="<?php echo $rowJson; ?>" onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)" class="text-black">
                                                <h6><?php echo $row['description'] ?></h6>
                                            </a>
                                            <small><?php echo $row['positionname'] ?> <?php echo $row['name'] ?></small>
                                            <p class="text-justify mt-1 mb-0 forum-body-text" style="text-transform: unset;"><?php echo issetParam($row['body']) ? Str::htmltotext($row['body']) : '' ?></p>
                                        </div>
                                        <div class="ml-auto tx-right">
                                            <?php
                                            $personname = explode(',', $row['commentpersonname']);
                                            $personpic = explode(',', $row['commentpicture']);
                                            if ($row['commentpersonname'] && $personname) { ?>
                                                <div class="avatar-group justify-content-end">
                                                    <?php foreach ($personname as $key => $pname) { if ($key < 4) { ?>
                                                        <a href="javascript:;">
                                                            <div class="avatar avatar-xs">
                                                                <img src="<?php issetParam($personpic[$key]) ? $personpic[$key] : '' ?>" onerror="onUserImageError(this);" class="rounded-circle" alt="">
                                                            </div>
                                                        </a>
                                                    <?php }} ?>
                                                </div>
                                                <a href="javascript:void(0);" class="text-nowrap"><small class="mt-1"><?php echo count($personname); ?> сэтгэгдэл</small></a>
                                            <?php } ?>
                                        </div>
                                    </li>
                                <?php }
                            } ?>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4 pl-0<?php echo Config::getFromCache('hideDtlinfo') == '1' ? ' d-none' : '' ?>">
                    <div class="card card-hover card-project-green card-poll poll<?php echo $this->uniqId ?>">
                        <?php echo issetParam($this->pollBox) ? $this->pollBox : '' ?>
                    </div>
                </div>
                <?php } ?>
                <div class="col-md-12<?php echo Config::getFromCache('hideDtlinfo') == '1' ? ' d-none' : '' ?>">
                    <div class="card card-hover card-profile-sidebar">
                        <div class="card-header bg-transparent with-border-bottom">
                            <h6 class="card-title mg-b-0 float-left">Зургийн сан</h6>
                        </div>
                        <div class="gallery p-3">
                            <?php if (issetParam($this->layoutPositionArr['pos_10'])) {
                            foreach ($this->layoutPositionArr['pos_10'] as $key => $row) {
                                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                                ?>
                                <a href="<?php echo $row['physicalpath']; ?>" data-rowdata="<?php echo $rowJson; ?>"  class="with-caption image-link" title="<?php echo $row['description'] ?>">
                                    <img src="<?php echo issetParam($row['thumbphysicalpath']) ? $row['thumbphysicalpath'] : $row['physicalpath']; ?>"/>
                                </a>
                            <?php }} else { ?>
                                <div class="removeTag w-100 pull-left">
                                    <div class="mb-3 skeleton-row">
                                        <div class="skeleton"></div>
                                        &nbsp;&nbsp;&nbsp;
                                        <div class="skeleton"></div>
                                    </div>
                                    <div class="mb-3 skeleton1"></div>
                                    <div class="mb-3 skeleton2"></div>
                                    <div class="mb-3 skeleton-row">
                                        <div class="skeleton3"></div>
                                        &nbsp;&nbsp;&nbsp;
                                        <div class="skeleton4"></div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <script>
                            $('.gallery').each(function() {
                                $(this).magnificPopup({
                                    delegate: 'a',
                                    type: 'image',
                                    gallery: {
                                     enabled:true
                                    }
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
        <div style="width: 250px;" class="px-2">
            <div class="card card-hover card-profile-sidebar card-weather">
                <div class="card-header bg-transparent">
                    <h6 class="card-title mg-b-0">Цаг агаар</h6>
                    <nav class="nav nav-card-icon">
                        
                        <a href="javascript:void(0);"><i data-feather="refresh-ccw"></i></a>
                    </nav>
                </div>
                <div class="p-3">
                    <?php if ($this->weatherData) { ?>
                        <div class="d-flex align-items-center justify-content-between row">
                            <div class="col">
                                <h2 class="tx-primary mb-0 font-family-Oswald"><?php echo issetDefaultVal($this->weatherData[0]['temperatureDay'], '0') ?>°C</h2>
                                <p class="mb-0">Өнөөдөр<br><?php echo issetParam($this->weatherData[0]['date']) ? $this->weatherData[0]['date'] : ''  ?></p>
                            </div>
                            <div class="col">
                                <img src="<?php echo issetParam($this->weatherData[0]['filepath']) ? $this->weatherData[0]['filepath'] : 'assets/custom/img/app_dashboard/weather.png'; ?>" class="img-fluid" onerror="onUserImageError(this);">
                            </div>
                        </div>
                        <div class="profile-info mt-3 row">
                            <div class="col-4">
                            <h5 class="tx-primary"><?php echo issetParam($this->weatherData[1]['temperatureDay']) ? $this->weatherData[1]['temperatureDay'] : ''  ?>°C</h5>
                            <p class="text-nowrap"><?php echo issetParam($this->weatherData[1]['date']) ? Date::format('l', $this->weatherData[1]['date'], true) : ''  ?></p>
                            </div>
                            <div class="col-4">
                            <h5 class="tx-primary"><?php echo issetParam($this->weatherData[2]['temperatureDay']) ? $this->weatherData[2]['temperatureDay'] : ''  ?>°C</h5>
                            <p class="text-nowrap"><?php echo issetParam($this->weatherData[2]['date']) ? Date::format('l', $this->weatherData[2]['date'], true) : ''  ?></p>
                            </div>
                            <div class="col-4">
                            <h5 class="tx-primary"><?php echo issetParam($this->weatherData[3]['temperatureDay']) ? $this->weatherData[2]['temperatureDay'] : ''  ?>°C</h5>
                            <p class="text-nowrap"><?php echo issetParam($this->weatherData[3]['date']) ? Date::format('l', $this->weatherData[3]['date'], true) : ''  ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="card card-hover card-connection-one">
                <?php if(Config::getFromCache('isNotaryServer')) { ?>
                    <div class="card-header bg-transparent">
                        <h6 class="card-title mg-b-0">Ханшийн мэдээ</h6>
                        <nav class="nav nav-card-icon">

                            <a href="javascript:void(0);"><i data-feather="refresh-ccw"></i></a>
                        </nav>
                    </div>
                
                    <div id="table-scroll-ex" class="table-scroll-ex">
                    <div class="table-wrap">
                      <table class="main-table">
                        <thead>
                          <tr>
                            <th class="fixed-side" scope="col">&nbsp;</th>
                            <th scope="col">Тэмдэгт</th>
                            <th scope="col">Авах</th>
                            <th scope="col">Зарах</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php if (issetParamArray($this->exchangeData)) { ?>
                            <?php foreach($this->exchangeData as $exchange) {  
                                $flag = '';
                                if (issetParam($exchange['code'])) {
                                    switch ($exchange['code']) {
                                        case 'USD':
                                            $flag = 'us.png';
                                            break;
                                        case 'EUR':
                                            $flag = 'europeanunion.png';
                                            break;
                                        case 'JPY':
                                            $flag = 'jp.png';
                                            break;
                                        case 'GBP':
                                            $flag = 'en.png';
                                            break;
                                        case 'RUB':
                                            $flag = 'ru.png';
                                            break;
                                        case 'CNY':
                                            $flag = 'cn.png';
                                            break;
                                        case 'KRW':
                                            $flag = 'kr.png';
                                            break;
                                        case 'CHF':
                                            $flag = 'ch.png';
                                            break;
                                        case 'HKD':
                                            $flag = 'hk.png';
                                            break;
                                        case 'AUD':
                                            $flag = 'au.png';
                                            break;
                                        case 'CAD':
                                            $flag = 'ca.png';
                                            break;
                                        case 'SGD':
                                            $flag = 'sg.png';
                                            break;
                                        case 'NZD':
                                            $flag = 'nz.png';
                                            break;
                                        case 'XAU':
                                            $flag = 'mn.png';
                                            break;
                                        case 'XAG':
                                            $flag = 'mn.png';
                                            break;
                                    }
                                }
                                ?>
                          <tr>
                            <th class="fixed-side">
                                <img src="assets/core/global/img/flags/<?php echo $flag ?>"/>
                            </th>
                            <td><?php echo issetParam($exchange['code']) ?></td>
                            <td><?php echo Number::formatMoney(issetParamZero($exchange['buy']), true)   ?></td>
                            <td><?php echo Number::formatMoney(issetParamZero($exchange['sell']), true) ?></td>
                          </tr>
                            <?php } } ?>
                        </tbody>
                      </table>
                    </div>
                </div>
                
                    <table class="table table-bordered d-none">
                        <?php if (1 == 0 && isset($this->exchangeData)) { ?>
                        <tbody>
                            <?php foreach($this->exchangeData as $exchange) { 
                                $flag = '';
                                if($exchange['code'] == 'USD') {
                                    $flag = 'us.png';
                                } else if($exchange['code'] == 'EUR') {
                                    $flag = 'europeanunion.png';
                                } else if($exchange['code'] == 'JPY') {
                                    $flag = 'jp.png';
                                } else if($exchange['code'] == 'GBP') {
                                    $flag = 'en.png';
                                } else if($exchange['code'] == 'RUB') {
                                    $flag = 'ru.png';
                                } else if($exchange['code'] == 'CNY') {
                                    $flag = 'cn.png';
                                } else if($exchange['code'] == 'KRW') {
                                    $flag = 'kr.png';
                                }
                                
                                
                                ?>
                                <tr>
                                    <td>                                        
                                        <li class="list-group-item border-none" style="padding:0;">
                                            <div class="list-group-icon">
                                                <img src="assets/core/global/img/flags/<?php echo $flag ?>">
                                            </div>
                                            <div class="list-body">
                                                <p class="person-name mb-0">
                                                <?php echo $exchange['name'] ?>
                                                </p>
                                            </div>
                                        </li>
                                    </td>
                                        <td><?php echo $exchange['code'] ?></td>
                                        <td><?php echo $exchange['rate'] ?>₮</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <?php } ?>
                </table>
                
                <?php } else { ?>
                    <div class="card-header bg-transparent">
                        <h6 class="card-title mg-b-0">Мэдээ мэдээлэл</h6>
                        <nav class="nav nav-card-icon">

                            <a href="javascript:void(0);"><i data-feather="refresh-ccw"></i></a>
                        </nav>
                    </div>
                    <ul class="list-group list-group-flush newslist<?php echo $this->uniqId ?>">
                        <div class="p-3 w-100 pull-left removeTag">
                            <div class="mb-3 skeleton-row">
                                <div class="skeleton"></div>
                                &nbsp;&nbsp;&nbsp;
                                <div class="skeleton"></div>
                            </div>
                            <div class="mb-3 skeleton1"></div>
                            <div class="mb-3 skeleton2"></div>
                            <div class="mb-3 skeleton-row">
                                <div class="skeleton3"></div>
                                &nbsp;&nbsp;&nbsp;
                                <div class="skeleton4"></div>
                            </div>
                        </div>
                        <?php if (issetParam($this->layoutPositionArr['pos_7'])) {
                            foreach ($this->layoutPositionArr['pos_7'] as $key => $row) { 
                            $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                        ?>
                            <li class="list-group-item align-items-center" data-id="<?php echo $row['id'] ?>">
                                <div class="avatar">
                                    <?php if (!empty($row['physicalpath']) && file_exists($row['physicalpath'])) {   ?>
                                        <img src="<?php echo $row['physicalpath'] ?>" class="rounded-circle" onerror="onUserImageError(this);" alt="">
                                        <?php } else {
                                            $colorData = array('#5556fd','#22d273','#6f42c1','#dc3545','#38c4fa','#fd7e14');
                                        ?>
                                        <span class="avatar-initial rounded-circle" style="background: <?php echo $colorData[rand(0, 5)]; ?>;">
                                            <?php echo Str::utf8_substr(Str::sanitize($row['description']), 0, 1); ?>
                                        </span>
                                    <?php } ?>
                                </div>
                                <div class="list-body">
                                    <?php if (Config::getFromCache('hideDtlinfo') == '1') { ?>
                                        <p class="person-name mb-0"><a href="javascript:void(0);" onclick="drilldownLink_hrbp_<?php echo $this->uniqId ?>(this)"  data-rowdata="<?php echo $rowJson; ?>"><?php echo $row['description'] ?></a></p>
                                    <?php } else { ?>
                                        <p class="person-name mb-0"><a href="javascript:void(0);" onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)"  data-rowdata="<?php echo $rowJson; ?>"><?php echo $row['description'] ?></a></p>
                                    <?php } ?>
                                    <p class="person-location"><?php echo $row['name'] ?></p>
                                    <p class="person-location"><?php echo $row['createddate'] ?></p>
                                </div>
                                <?php if ($row['attachfile']) { ?>
                                    <a href="javascript:void(0);" onclick="dataViewFileViewer(this, '1', '<?php echo $row['fileextension'] ?>', '<?php echo $row['physicalpath'] ?>',  '<?php echo URL . $row['physicalpath'] ?>', 'undefined');" class="person-more">
                                        <i data-feather="paperclip" class="svg-16"></i>
                                    </a>
                                <?php } ?>
                            </li>
                        <?php }} ?>
                    </ul>
                
                <?php  } ?>
            </div>
            <div class="card card-hover card-connection-one<?php echo Config::getFromCache('hideDtlinfo') == '1' ? ' d-none' : '' ?>">
                <div class="card-header bg-transparent">
                    <h6 class="card-title mg-b-0">Файлын сан</h6>
                    <nav class="nav nav-card-icon">
                        
                        <a href="javascript:void(0);"><i data-feather="refresh-ccw"></i></a>
                    </nav>
                </div>
                <ul class="nav nav-line px-3" id="myTab5" role="tablist">
                    <?php if ($pos_8Arr) { $index = 1; foreach ($pos_8Arr as $key => $row) { ?>
                        <li class="nav-item"><a href="#app-dashboard-file-tab-<?php echo $key . '_' .$this->uniqId ?>" class="nav-link <?php echo $index == sizeof($pos_8Arr) ? 'active' : '' ?>" data-toggle="tab"><?php echo $row['row']['categoryname'] ?></a></li>
                    <?php  $index++; }} ?>
                </ul>
                <div class="tab-content filelibrary<?php echo $this->uniqId ?>" id="myTabContent5">
                
                    <div class="p-3 w-100 pull-left removeTag">
                        <div class="mb-3 skeleton-row">
                            <div class="skeleton"></div>
                            &nbsp;&nbsp;&nbsp;
                            <div class="skeleton"></div>
                        </div>
                        <div class="mb-3 skeleton1"></div>
                        <div class="mb-3 skeleton2"></div>
                        <div class="mb-3 skeleton-row">
                            <div class="skeleton3"></div>
                            &nbsp;&nbsp;&nbsp;
                            <div class="skeleton4"></div>
                        </div>
                    </div>
                    <?php if ($pos_8Arr) { $index = 1; foreach ($pos_8Arr as $key => $prow) { ?>
                        <div class="tab-pane fade <?php echo $index == sizeof($pos_8Arr) ? 'active show' : '' ?>" id="app-dashboard-file-tab-<?php echo $key . '_' .$this->uniqId ?>">
                            <ul class="list-group list-group-flush card-analytics-two wh38">
                                <?php
                                if ($prow['rows']) {
                                    foreach ($prow['rows'] as $key => $row) {
                                        $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');

                                        $fileview = $icon = $color = '';
                                        switch ($row['fileextension']) {
                                            case 'png':
                                            case 'gif':
                                            case 'jpeg':
                                            case 'pjpeg':
                                            case 'jpg':
                                            case 'x-png':
                                            case 'bmp':
                                                $icon = "icon-file-picture";
                                                $color = "bg-blue";
                                                break;
                                            case 'zip':
                                            case 'rar':
                                                $icon = "icon-file-zip";
                                                $color = "bg-pink";
                                                break;
                                            case 'pdf':
                                                $icon = "icon-file-pdf";
                                                $color = "bg-danger";
                                            break;
                                            case 'mp3':
                                                $icon = "icon-file-music";
                                                $color = "bg-purple";
                                                break;
                                            case 'mp4':
                                                $icon = "icon-file-video";
                                                $color = "bg-purple";
                                                break;
                                            case 'doc':
                                            case 'docx':
                                                $icon = "icon-file-word";
                                                $color = "bg-primary";
                                            break;
                                            case 'ppt':
                                            case 'pptx':
                                                $icon = "icon-file-ppt";
                                                $color = "bg-danger";
                                            break;
                                            case 'xls':
                                            case 'xlsx':
                                                $icon = "icon-file-xls";
                                                $color = "bg-green";
                                            break;
                                            default:
                                                $icon = "icon-file-jpg";
                                                $color = "bg-primary";
                                            break;
                                    } ?>
                                    <li class="list-group-item" data-id="<?php echo $row['id'] ?>">
                                        <div onclick="dataViewFileViewer(this, '1', '<?php echo $row['fileextension'] ?>', '<?php echo $row['physicalpath'] ?>',  '<?php echo URL . $row['physicalpath'] ?>', 'undefined');">
                                            <div class="list-group-icon <?php echo $color; ?> tx-white">
                                                <i class="<?php echo $icon; ?>"></i>
                                            </div>
                                        </div>
                                        <div class="list-body">
                                            <p class="person-name mb-0">
                                                <a href="javascript:void(0);" onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)"  data-rowdata="<?php echo $rowJson; ?>">
                                                    <?php echo $row['description']; ?>
                                                </a>
                                            </p>
                                            <p class="person-location"><?php echo $row['createddate'] ?></p>
                                        </div>
                                        <a href="javascript:void(0);" class="person-more font-family-Oswald"><?php echo issetParam($row['filesize']) ? formatSizeUnits($row['filesize'], 0) : '' ?></a>
                                    </li>
                                    <?php }
                                    } ?>
                            </ul>
                        </div>
                    <?php $index++; }} ?>
                </div>
            </div>
            <div class="card card-hover card-connection-one">
                <div class="card-header bg-transparent">
                    <h6 class="card-title mg-b-0">Төрсөн өдөр <i class="fa fa-birthday-cake ml-1 text-pink"></i></h6>
                    <nav class="nav nav-card-icon">
                        <a href="javascript:void(0);"><i data-feather="refresh-ccw"></i></a>
                    </nav>
                </div>
                <ul class="list-group list-group-flush">
                    <?php if (issetParam($this->layoutPositionArr['pos_4'])) {
                    foreach ($this->layoutPositionArr['pos_4'] as $key => $row) { ?>
                        <li class="list-group-item align-items-center">
                            <div class="avatar">
                                <img src="<?php echo issetParam($row['picture']) ? $row['picture'] : ''  ?>" class="rounded-circle" onerror="onUserImageError(this);" alt="" data-birthuserid="<?php echo issetParam($row['userid']) ? $row['userid'] : '' ?>">
                            </div>
                            <div class="list-body">
                                <p class="person-name mb-0"><a href="javascript:void(0);"><?php echo issetParam($row['employeename']) ? $row['employeename'] : ''  ?></a></p>
                                <p class="person-location"><?php echo $row['positionname']  ?></p>
                            </div>
                            <a href="javascript:void(0);" class="person-more" title="Мэндчилгээ илгээх" onclick="apiChatSendMessagev1<?php echo $this->uniqId ?>('<?php echo issetParam($row['userid'])  ? $row['userid'] : '' ?>', 'Төрсөн өдрийн баярын мэнд хүргэе.', false);">
                                <i data-feather="gift" class="svg-16 gifticon"></i>
                            </a>
                        </li>
                    <?php }} else { ?>
                        <div class="p-3 w-100 pull-left removeTag">
                            <div class="mb-3 skeleton-row">
                                <div class="skeleton"></div>
                                &nbsp;&nbsp;&nbsp;
                                <div class="skeleton"></div>
                            </div>
                            <div class="mb-3 skeleton1"></div>
                            <div class="mb-3 skeleton2"></div>
                            <div class="mb-3 skeleton-row">
                                <div class="skeleton3"></div>
                                &nbsp;&nbsp;&nbsp;
                                <div class="skeleton4"></div>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- <li class="list-group-item align-items-center">
                        <div class="avatar"><img src="views/government/meeting/include/img/img9.jpg" class="rounded-circle" alt=""></div>
                        <div class="list-body">
                            <p class="person-name mb-0"><a href="javascript:void(0);">Ж.Тамир</a></p>
                            <p class="person-location">Мэдээллийн технологийн хэлтсийн дарга</p>
                        </div>
                        <a href="javascript:void(0);" class="person-more"><i data-feather="gift" class="svg-16"></i></a>
                    </li>
                    <li class="list-group-item align-items-center">
                        <div class="avatar"><img src="views/government/meeting/include/img/img5.jpg" class="rounded-circle" alt=""></div>
                        <div class="list-body">
                            <p class="person-name mb-0"><a href="javascript:void(0);">Б.Энхзаяа</a></p>
                            <p class="person-location">Ахлах референт</p>
                        </div>
                        <a href="javascript:void(0);" class="person-more"><i data-feather="gift" class="svg-16"></i></a>
                    </li>
                    <li class="list-group-item align-items-center">
                        <div class="avatar"><span class="avatar-initial rounded-circle bg-primary">Х</span></div>
                        <div class="list-body">
                            <p class="person-name mb-0"><a href="javascript:void(0);">У.Хүрэлсүх</a></p>
                            <p class="person-location">Монгол улсын ерөнхий сайд</p>
                        </div>
                        <a href="javascript:void(0);" class="person-more"><i data-feather="gift" class="svg-16"></i></a>
                    </li>
                    <li class="list-group-item align-items-center">
                        <div class="avatar"><span class="avatar-initial rounded-circle bg-teal">О</span></div>
                        <div class="list-body">
                            <p class="person-name mb-0"><a href="javascript:void(0);">Л.Оюун-Эрдэнэ</a></p>
                            <p class="person-location">Хэрэг эрхлэх газрын дарга</p>
                        </div>
                        <a href="javascript:void(0);" class="person-more"><i data-feather="gift" class="svg-16"></i></a>
                    </li> -->
                </ul>
            </div>
        </div>
    </div>
</div>

<?php 
    echo issetParam($this->app_js) ? $this->app_js : '';
    echo issetParam($this->app_css) ? $this->app_css : '';
?>

<style type="text/css">
</style>