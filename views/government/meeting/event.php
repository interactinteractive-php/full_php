<?php
    (Array) $pos_6Arr = array();
    $pos6Arr = issetParamArray($this->layoutPositionArr['pos_6']);
    if (issetParam($this->layoutPositionArr['pos_6'][0]['timetense'])) {
        $pos_6Arr = Arr::groupByArrayOnlyRows($this->layoutPositionArr['pos_6'], 'timetense');
    }

?>
<div class="card-header bg-transparent with-border-bottom">
    <h6 class="card-title mg-b-0"><?php echo Lang::lineDefault('gov_dashboard_title_5', 'Цаг үеийн үйл явдал') ?></h6>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card card-hover card-project-three border-0 pt-2 pl-3 mb-0 box-shadow-none work<?php echo $this->uniqId ?>">
            <ul class="nav nav-tabs nav-justified border-bottom-0 mb-3 calendar-sub-tab">
                <?php if(!Config::getFromCache('isNotaryServer') && !Config::getFromCache('hideJobTab')){ ?>
                <li class="nav-item"><a href="#app-dashboard-work-tab<?php echo $this->uniqId ?>" class="nav-link active p-2 border-radius-100 text-nowrap" data-toggle="tab"><?php echo Lang::lineDefault('gov_dashboard_title_6', 'Миний ажил') ?></a></li>
                <?php } ?> 
                <li class="nav-item"><a href="#app-dashboard-event-tab<?php echo $this->uniqId ?>" class="nav-link p-2 border-radius-100 text-nowrap <?php echo Config::getFromCache('isNotaryServer') || Config::getFromCache('hideJobTab') ? 'active' : '' ?>" data-toggle="tab"><?php echo Config::getFromCache('isNotaryServer') ? 'Миний төлөвлөгөө' : 'Цагийн хүсэлт' ?>  (<span class="timereqCounter<?php echo $this->uniqId ?>"><?php echo issetParam($this->layoutPositionArr['pos_14']) ? sizeof($this->layoutPositionArr['pos_14']) : '0' ?></span>)</a></li>
                <li class="nav-item approve-requests"><a href="#app-dashboard-req-4<?php echo $this->uniqId ?>" class="nav-link p-2 border-radius-100 text-nowrap" data-toggle="tab"><?php echo Config::getFromCache('isNotaryServer') ? 'Нийт төлөвлөгөө' : 'Миний батлах хүсэлтүүд' ?>  (<span class="approvedReq<?php echo $this->uniqId ?>"><?php echo issetParam($this->layoutPositionArr['pos_15']) ? sizeof($this->layoutPositionArr['pos_15']) : '0' ?></span>)</a></li>
            </ul>
            <div class="tab-content">
                <?php if(!Config::getFromCache('isNotaryServer') && !Config::getFromCache('hideJobTab')) { ?>
                <div class="tab-pane fade active show" id="app-dashboard-work-tab<?php echo $this->uniqId ?>">
                    <ul class="nav nav-tabs nav-tabs-highlight calendar-event mb20">
                        <li class="nav-item"><a href="#app-dashboard-work-cat-tab-1<?php echo $this->uniqId ?>" class="nav-link active px-3" data-toggle="tab"><?php echo Lang::lineDefault('gov_dashboard_title_7', 'Өнөөдөр') ?> (<span class="timeatoday<?php echo $this->uniqId ?>"><?php echo issetParam($pos_6Arr['today']) ? sizeof($pos_6Arr['today']) : '0' ?></span>)</a></li>
                        <li class="nav-item"><a href="#app-dashboard-work-cat-tab-3<?php echo $this->uniqId ?>" class="nav-link px-3" data-toggle="tab"><?php echo Lang::lineDefault('gov_dashboard_title_8', 'Хугацаа хэтэрсэн') ?> (<span class="timepast<?php echo $this->uniqId ?>"><?php echo issetParam($pos_6Arr['past']) ? sizeof($pos_6Arr['past']) : '0' ?></span>)</a></li>
                        <li class="nav-item"><a href="#app-dashboard-work-cat-tab-4<?php echo $this->uniqId ?>" class="nav-link px-3" data-toggle="tab"><?php echo Lang::lineDefault('gov_dashboard_title_9', 'Бүгд') ?> (<span class="timeall<?php echo $this->uniqId ?>"><?php echo issetParam($pos6Arr) ? sizeof($pos6Arr) : '0' ?></span>)</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade <?php echo !Config::getFromCache('isNotaryServer') && !Config::getFromCache('hideJobTab') ? 'active show' : '' ?>" id="app-dashboard-work-cat-tab-1<?php echo $this->uniqId ?>">
                            <div class="event-tab-content">
                                <div class="w-100 pull-left removeTag">
                                    <div class="mb-3 skeleton-row">
                                        <div class="skeleton"></div>&nbsp;&nbsp;&nbsp;<div class="skeleton"></div>
                                    </div>
                                    <div class="mb-3 skeleton1"></div>
                                    <div class="mb-3 skeleton2"></div>
                                    <div class="mb-3 skeleton-row">
                                        <div class="skeleton3"></div>&nbsp;&nbsp;&nbsp;<div class="skeleton4"></div>
                                    </div>
                                    <div class="mb-3 skeleton-row">
                                        <div class="skeleton"></div>&nbsp;&nbsp;&nbsp;<div class="skeleton"></div>
                                    </div>
                                    <div class="mb-3 skeleton1"></div>
                                    <div class="mb-3 skeleton2"></div>
                                    <div class="mb-3 skeleton-row">
                                        <div class="skeleton3"></div>&nbsp;&nbsp;&nbsp;<div class="skeleton4"></div>
                                    </div>
                                </div>
                                <?php if (issetParam($pos_6Arr['today'])) {
                                    foreach ($pos_6Arr['today'] as $key => $row) {
                                        $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                                    ?>
                                    <div class="event-box <?php echo Config::getFromCache('isNotaryServer') ? 'eventtt' : '' ?>" data-id="<?php echo $row['id'] ?>" data-taskdata="<?php echo $rowJson ?>">
                                        <div style="min-width:90px;float:left;">
                                            <div class="calendar-date project-data-group">
                                                <div class="d-flex flex-column">
                                                    <h3 class="text-black d-flex align-items-center">
                                                        <i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>
                                                        <span><?php echo Date::formatter($row['startdate']) ?></span>
                                                    </h3>
                                                    <h3 class="text-black d-flex align-items-center">
                                                        <i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>
                                                        <span class="text-danger"><?php echo Date::formatter($row['enddate']) ?></span>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="project-data-group">
                                                <div style="width:31px;">
                                                    <div class="d-flex flex-column">
                                                        <h3><?php echo issetParam($row['starttime']) ? Date::formatter($row['starttime'], 'H:i') : '' ?></h3>
                                                        <h3><?php echo issetParam($row['endtime']) ? Date::formatter($row['endtime'], 'H:i') : '' ?></h3>
                                                    </div>
                                                </div>
                                                <div class="taskdesc">
                                                    <div><?php echo $row['tasktypename'] ?></div>
                                                    <div class="text-muted line-height-normal"><?php echo $row['taskname'] ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                                }
                                ?>
                            </div>
                            <?php if(!Config::getFromCache('isNotaryServer')) { ?>
                            <div class="event-tab-footer">
                                <div class="d-flex align-items-center">
                                    <div class="timesec mr-3">
                                        <span>Ирсэн:</span>
                                        <span class="timesec1 intime"></span>
                                    </div>
                                    <div class="timesec">
                                        <span>Явсан:</span>
                                        <span class="timesec1 outtime"></span>
                                    </div>
                                    <div class="ml-auto">
                                        <div class="conflict">
                                            <span class="mr-3">
                                                <span>Нийт ажилласан цаг:</span>
                                                <span class="timesec1 cleantime"></span>
                                            </span>
                                            <a href="javascript:;" class="timesec icon-menu-bg mr-2" onclick="appMultiTab({metaDataId: '1533876130306', title: 'Цаг бүртгэл', type: 'dataview', proxyId: ''}, this);">
                                                <i class="icon-menu"></i>
                                            </a>
                                            <a href="javascript:;" class="timesec icon-cross-bg d-none">
                                                <i class="icon-cross2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="tab-pane fade" id="app-dashboard-work-cat-tab-3<?php echo $this->uniqId ?>">
                            <div class="event-tab-content">
                                <div class="event-box d-none">
                                    <div style="min-width:90px;float:left;">
                                        <div class="calendar-date project-data-group">
                                            <div class="d-flex flex-column">
                                                <h3 class="text-black d-flex align-items-center">
                                                    <i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>
                                                    <span>2020-04-26</span>
                                                </h3>
                                                <h3 class="text-black d-flex align-items-center">
                                                    <i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>
                                                    <span class="text-danger">2020-04-26</span>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="project-data-group">
                                            <div style="width:31px;">
                                                <div class="d-flex flex-column">
                                                    <h3>08:30</h3>
                                                    <h3 class="text-danger">08:30</h3>
                                                </div>
                                            </div>
                                            <div class="taskdesc">
                                                <div>Гадуур ажиллах хүсэлт</div>
                                                <div class="text-muted">Кат таб тайлбар байршина.</div>
                                            </div>
                                        </div>
                                        <div class="project-data-group">
                                            <div style="width:31px;">
                                                <div class="d-flex flex-column">
                                                    <h3>08:30</h3>
                                                    <h3 class="text-danger">08:30</h3>
                                                </div>
                                            </div>
                                            <div class="vertical-seperator bg-teal"></div>
                                            <div>
                                                <div>Гадуур ажиллах хүсэлт</div>
                                                <div class="text-muted">Кат таб тайлбар байршина.</div>
                                            </div>
                                        </div>
                                        <div class="project-data-group">
                                            <div style="width:31px;">
                                                <div class="d-flex flex-column">
                                                    <h3>09:00</h3>
                                                    <h3 class="text-danger">09:30</h3>
                                                </div>
                                            </div>
                                            <div class="vertical-seperator bg-teal"></div>
                                            <div>
                                                <div>Гадуур ажиллах хүсэлт</div>
                                                <div class="text-muted">Кат таб тайлбар байршина.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                if (issetParam($pos_6Arr['past'])) {
                                    foreach ($pos_6Arr['past'] as $key => $row) { 
                                        $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                                    ?>
                                    <div class="event-box" data-id="<?php echo $row['id'] ?>" data-taskdata="<?php echo $rowJson ?>" data-date="<?php echo Date::formatter($row['enddate']); ?>">
                                        <div style="min-width:90px;float:left;">
                                            <div class="calendar-date project-data-group">
                                                <div class="d-flex flex-column">
                                                    <h3 class="text-black d-flex align-items-center">
                                                        <i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>
                                                        <span><?php echo Date::formatter($row['startdate']) ?></span>
                                                    </h3>
                                                    <h3 class="text-black d-flex align-items-center">
                                                        <i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>
                                                        <span class="text-danger"><?php echo Date::formatter($row['enddate']) ?></span>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="project-data-group">
                                                <div style="width:31px;">
                                                    <div class="d-flex flex-column">
                                                        <h3><?php echo issetParam($row['starttime']) ? Date::formatter($row['starttime'], 'H:i') : '' ?></h3>
                                                        <h3><?php echo issetParam($row['endtime']) ? Date::formatter($row['endtime'], 'H:i') : '' ?></h3>
                                                    </div>
                                                </div>
                                                <div class="taskdesc">
                                                    <div><?php echo $row['tasktypename'] ?></div>
                                                    <div class="text-muted line-height-normal"><?php echo $row['taskname'] ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                                } ?>
                            </div>
                            <?php if(!Config::getFromCache('isNotaryServer')) { ?>
                            <div class="event-tab-footer">
                                <div class="d-flex align-items-center">
                                    <div class="timesec mr-3">
                                        <span>Ирсэн:</span>
                                        <span class="timesec1 intime"></span>
                                    </div>
                                    <div class="timesec">
                                        <span>Явсан:</span>
                                        <span class="timesec1 outtime"></span>
                                    </div>
                                    <div class="ml-auto">
                                        <div class="conflict">
                                            <span class="mr-3">
                                                <span>Нийт ажилласан цаг:</span>
                                                <span class="timesec1 cleantime"></span>
                                            </span>
                                            <a href="javascript:;" class="timesec icon-menu-bg mr-2" onclick="appMultiTab({metaDataId: '1533876130306', title: 'Цаг бүртгэл', type: 'dataview', proxyId: ''}, this);">
                                                <i class="icon-menu"></i>
                                            </a>
                                            <a href="javascript:;" class="timesec icon-cross-bg d-none">
                                                <i class="icon-cross2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="tab-pane fade" id="app-dashboard-work-cat-tab-4<?php echo $this->uniqId ?>">
                            <div class="event-tab-content">
                                <?php if (issetParam($this->layoutPositionArr['pos_6'])) {
                                    foreach ($this->layoutPositionArr['pos_6'] as $key => $row) { 
                                        $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                                        if (issetParam($row['id'])) { ?>
                                            <div class="event-box" data-id="<?php echo issetParam($row['id']) ? $row['id'] : '' ?>" data-taskdata="<?php echo $rowJson ?>">
                                                <div style="min-width:90px;float:left;">
                                                    <div class="calendar-date project-data-group">
                                                        <div class="d-flex flex-column">
                                                            <h3 class="text-black d-flex align-items-center">
                                                                <i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>
                                                                <span><?php echo Date::formatter($row['startdate']) ?></span>
                                                            </h3>
                                                            <h3 class="text-black d-flex align-items-center">
                                                                <i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>
                                                                <span class="text-danger"><?php echo Date::formatter($row['enddate']) ?></span>
                                                            </h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="project-data-group">
                                                        <div style="width:31px;">
                                                            <div class="d-flex flex-column">
                                                                <h3><?php echo issetParam($row['starttime']) ? Date::formatter($row['starttime'], 'H:i') : '' ?></h3>
                                                                <h3><?php echo issetParam($row['endtime']) ? Date::formatter($row['endtime'], 'H:i') : '' ?></h3>
                                                            </div>
                                                        </div>
                                                        <div class="taskdesc">
                                                            <div><?php //echo $row['tasktypename'] ?></div>
                                                            <div class="text-muted line-height-normal"><?php echo $row['taskname'] ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }
                                        }
                                    }
                                ?>
                            </div>
                            <div class="event-tab-footer">
                                <div class="d-flex align-items-center">
                                    <div class="timesec mr-3">
                                        <span>Ирсэн:</span>
                                        <span class="timesec1 intime"></span>
                                    </div>
                                    <div class="timesec">
                                        <span>Явсан:</span>
                                        <span class="timesec1 outtime"></span>
                                    </div>
                                    <div class="ml-auto">
                                        <div class="conflict">
                                            <span class="mr-3">
                                                <span>Нийт ажилласан цаг:</span>
                                                <span class="timesec1 cleantime"></span>
                                            </span>
                                            <a href="javascript:;" class="timesec icon-menu-bg mr-2" onclick="appMultiTab({metaDataId: '1533876130306', title: 'Цаг бүртгэл', type: 'dataview', proxyId: ''}, this);">
                                                <i class="icon-menu"></i>
                                            </a>
                                            <a href="javascript:;" class="timesec icon-cross-bg d-none">
                                                <i class="icon-cross2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="tab-pane fade <?php echo Config::getFromCache('isNotaryServer') || Config::getFromCache('hideJobTab') ? 'active show' : '' ?>" id="app-dashboard-event-tab<?php echo $this->uniqId ?>">
                    <div class="event-tab-content without-three-tab">
                        <?php if (issetParam($this->layoutPositionArr['pos_14'])) {
                            foreach ($this->layoutPositionArr['pos_14'] as $key => $row) {
                                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                                ?>
                                <div class="event-box event-box<?php echo $this->uniqId ?>" data-row="<?php echo $rowJson ?>" data-id="<?php echo $row['id'] ?>" data-startdate="<?php echo $row['startdate'] ?>" data-wfmstatuscode="<?php echo $row['wfmstatuscode'] ?>">
                                    <div style="min-width:90px;float:left;">
                                        <div class="calendar-date project-data-group">
                                            <div class="d-flex flex-column">
                                                <h3 class="text-black d-flex align-items-center">
                                                    <i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>
                                                    <span><?php echo Date::formatter($row['startdate']) ?></span>
                                                </h3>
                                                <h3 class="text-black d-flex align-items-center">
                                                    <i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>
                                                    <span class="text-danger"><?php echo Date::formatter($row['enddate']) ?></span>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="d-flex align-items-center justify-content-between w-100">
                                            <div class="d-flex project-data-group">
                                                <div style="width:32px;">
                                                    <div class="d-flex flex-column">
                                                        <h3><?php echo Date::formatter($row['starttime'], 'H:i') ?></h3>
                                                        <h3 class="text-danger"><?php echo Date::formatter($row['endtime'], 'H:i') ?></h3>
                                                    </div>
                                                </div>
                                                <div class="taskdesc">
                                                    <div><?php echo $row['booktypename'] ?></div>
                                                    <div class="text-muted"><?php echo $row['description'] ?></div>
                                                </div>
                                            </div>
                                            <div class="ml-auto" style="min-width: 70px;">
                                                <div class="d-flex flex-column">
                                                    <h3 class="text-danger mb-0"><button class="btn btn-sm btn-primary btn-rounded text-white" style="background-color: <?php //echo $row['wfmstatuscolor'] ? $row['wfmstatuscolor'] : '#3F51B5' ?>"><?php echo $row['wfmstatusname'] ?></button></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        <?php }
                        } ?>
                        
                    </div>
                    <?php if(!Config::getFromCache('isNotaryServer')) { ?>
                    <div class="event-tab-footer">
                        <div class="d-flex align-items-center">
                            <div class="timesec mr-3">
                                <span>Ирсэн:</span>
                                <span class="timesec1 intime"></span>
                            </div>
                            <div class="timesec">
                                <span>Явсан:</span>
                                <span class="timesec1 outtime"></span>
                            </div>
                            <div class="ml-auto">
                                <div class="conflict">
                                    <span class="mr-3">
                                        <span>Нийт ажилласан цаг:</span>
                                        <span class="timesec1 cleantime"></span>
                                    </span>
                                    <a href="javascript:;" class="timesec icon-menu-bg mr-2" onclick="appMultiTab({metaDataId: '1533876130306', title: 'Цаг бүртгэл', type: 'dataview', proxyId: ''}, this);">
                                        <i class="icon-menu"></i>
                                    </a>
                                    <a href="javascript:;" class="timesec icon-cross-bg d-none">
                                        <i class="icon-cross2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="tab-pane fade" id="app-dashboard-req-4<?php echo $this->uniqId ?>">
                    <div class="event-tab-content without-three-tab">
                        <?php 
                            if ($this->layoutPositionArr['pos_15']) {
                                foreach ($this->layoutPositionArr['pos_15'] as $key => $row) {
                                    $class = '';
                                    if (issetParam($row['createduserid']) == Ue::sessionUserKeyId()) {
                                        $class = 'eventtt';
                                    }
                                    
                                    $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>
                                <div class="event-box <?php echo Config::getFromCache('isNotaryServer') ? $class : '' ?>" data-rowdata="<?php echo $rowJson; ?>" data-id="<?php echo $row['id'] ?>">
                                    <div style="min-width:90px;float:left;">
                                        <div class="calendar-date project-data-group">
                                            <div class="d-flex flex-column">
                                                <h3 class="text-black d-flex align-items-center">
                                                    <i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>
                                                    <span><?php echo issetParam($row['startdate']) ? Date::formatter($row['startdate']) : '0000-00-00'; ?></span>
                                                </h3>
                                                <h3 class="text-black d-flex align-items-center">
                                                    <i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>
                                                    <span class="text-danger"><?php echo issetParam($row['enddate']) ? Date::formatter($row['enddate']) : '0000-00-00' ?></span>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="project-data-group">
                                            <div style="width:35px;">
                                                <div class="d-flex flex-column">
                                                    <h3><?php echo issetParam($row['startdate']) ? Date::formatter($row['starttime'], 'H:i') : '--:--' ?></h3>
                                                    <h3 class="text-danger"><?php echo issetParam($row['enddate']) ? Date::formatter($row['endtime'], 'H:i') : '--:--' ?></h3>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between w-100 taskdesc app-req">
                                                <div class="d-flex">
                                                    <?php if(!Config::getFromCache('isNotaryServer')){ ?>
                                                    <div class="avatar mr-2">
                                                        <img src="<?php echo $row['picture']; ?>" class="rounded-circle" alt="" onerror="onUserImageError(this);">
                                                    </div>
                                                    <div class="mr-3" style="width:120px;">
                                                        <div><?php echo $row['employeename']; ?></div>
                                                        <div class="text-muted line-height-normal"><?php echo $row['positionname']; ?></div>
                                                    </div>
                                                    <?php } ?>
                                                    <div style="width:250px;">
                                                        <div><?php echo $row['booktypename']; ?></div>
                                                        <div class="text-muted line-height-normal"><?php echo $row['description']; ?> </div>
                                                        
                                                    </div>
                                                </div>
                                                <?php if(!Config::getFromCache('isNotaryServer')){ ?>
                                                <div class="ml-auto" style="min-width: 110px;">
                                                    <button type="button" class="btn btn-sm btn-light btn-rounded"  data-row='<?php echo $rowJson ?>' onclick="drilldownLinkCustome2_<?php echo $this->uniqId ?>(this)" >Төлөв өөрчлөх</button>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                            }
                        } ?>
                    </div>
                    <?php if(!Config::getFromCache('isNotaryServer')) { ?>
                    <div class="event-tab-footer">
                        <div class="d-flex align-items-center">
                            <div class="timesec mr-3">
                                <span>Ирсэн:</span>
                                <span class="timesec1 intime"></span>
                            </div>
                            <div class="timesec">
                                <span>Явсан:</span>
                                <span class="timesec1 outtime"></span>
                            </div>
                            <div class="ml-auto">
                                <div class="conflict">
                                    <span class="mr-3">
                                        <span>Нийт ажилласан цаг:</span>
                                        <span class="timesec1 cleantime"></span>
                                    </span>
                                    <a href="javascript:;" class="timesec icon-menu-bg mr-2" onclick="appMultiTab({metaDataId: '1533876130306', title: 'Цаг бүртгэл', type: 'dataview', proxyId: ''}, this);">
                                        <i class="icon-menu"></i>
                                    </a>
                                    <a href="javascript:;" class="timesec icon-cross-bg d-none">
                                        <i class="icon-cross2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="p-3" style="background: #ffffff;height: 661px; border-left: 1px solid #f7f8ff;">
            <div class="calendar calendar-<?php echo $this->uniqId ?>">
                <div class="col p-0" id="calendar-<?php echo $this->uniqId ?>"></div>
            </div>
        <?php if(!Config::getFromCache('isNotaryServer')) { ?>
        <div class="mt-2">
            <ul class="emoji font-size-12" style="list-style: none; font-weight: 100;">
                <div class="row">
                    <div class="col-6">
                        <li><i class="icon-primitive-dot text-success"></i><span class="cat-t first ml-2"><?php echo Lang::line('request_') ?></span></li>
                        <li><i class="icon-primitive-dot text-danger"></i><span class="cat-t first ml-2"><?php echo Lang::line('absent_') ?></span></li>
                        <li><i class="icon-primitive-dot text-warning"></i><span class="cat-t first ml-2"><?php echo Lang::line('late_') ?></span></li>
                    </div>
                    <div class="col-6">
                        <li><i class="icon-briefcase3 text-danger"></i><span class="cat-t first ml-2"><?php echo Lang::line('task_gone') ?></span></li>
                        <li><i class="icon-info22 text-primary"></i><span class="cat-t first ml-2"><?php echo Lang::line('holiday_') ?></span></li>
                    </div>
                </div>
            </ul>
        </div>
        <?php } ?>
        </div>
    </div>
</div>