<div class="row">
    <div class="col-md-6">
        <div class="p-2" style="background: #ffffff; border-left: 1px solid #f7f8ff;">
            <h3>Цагийн хүсэлт</h3>
            <ul class="nav nav-tabs nav-justified border-bottom-0 mb-3 calendar-sub-tab">
                <li class="nav-item"><a href="#app-dashboard-work-tab<?php echo $this->uniqId ?>" class="nav-link active p-2 border-radius-100 text-nowrap" data-toggle="tab">Шинэ</a></li>
                <li class="nav-item"><a href="#app-dashboard-event-tab<?php echo $this->uniqId ?>" class="nav-link p-2 border-radius-100 text-nowrap" data-toggle="tab">Нийт </a></li>
            </ul>
            <div class="tab-content timerequist">
                <div class="tab-pane fade active show" id="app-dashboard-work-tab<?php echo $this->uniqId ?>">
               
                <?php if (issetParam($this->layoutPositionArr['pos_8'])) {
                    foreach ($this->layoutPositionArr['pos_8'] as $key => $row) {
                        $rowJson = '';
                        ?>
                        <div class="event-box event-box<?php echo $this->uniqId ?>" data-row="<?php echo $rowJson ?>" data-id="<?php echo $row['id'] ?>" data-startdate="<?php echo $row['startdate'] ?>">
                            <div style="min-width:70px;float:left;">
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
                                <div class="row  d-flex align-items-center justify-content-between w-100">
                                    <div class="col-md-9 d-flex project-data-group">
                                        <div class="taskdesc">
                                            <div><?php echo $row['positionname'] ?></div>
                                            <div class="text-muted"><?php echo $row['departmentname'] ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex flex-column">
                                            <h3 style="font-size:13px" class="text-danger mb-0"><?php echo $row['wfmstatusname'] ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php }
                } ?>

                </div>
                <div class="tab-pane fade" id="app-dashboard-event-tab<?php echo $this->uniqId ?>">
                <?php if (issetParam($this->layoutPositionArr['pos_9'])) {
                    foreach ($this->layoutPositionArr['pos_9'] as $key => $row) {
                        $rowJson = '';
                        ?>
                        <div class="event-box event-box<?php echo $this->uniqId ?>" data-row="<?php echo $rowJson ?>" data-id="<?php echo $row['id'] ?>" data-startdate="<?php echo $row['startdate'] ?>">
                            <div style="min-width:70px;float:left;">
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
                                        <div class="taskdesc">
                                            <div><?php echo $row['positionname'] ?></div>
                                            <div class="text-muted"><?php echo $row['departmentname'] ?></div>
                                        </div>
                                    </div>
                                    <div class="ml-auto" style="min-width: 180px;">
                                        <div class="d-flex flex-column">
                                            <h3 style="font-size:13px" class="text-danger mb-0"><?php echo $row['wfmstatusname'] ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php }
                } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-hover card-project-three border-0 pt20 box-shadow-none work<?php echo $this->uniqId ?>">
            <h3>Үйл ажиллагаа</h3>
            <?php if (issetParam($this->layoutPositionArr['pos_0'])) {
                foreach ($this->layoutPositionArr['pos_0'] as $key => $row) {
                    $rowJson = '';                    
                    ?>
                    <div class="event-box khantask event-box<?php echo $this->uniqId ?>" data-row="<?php echo $rowJson ?>" data-id="<?php echo $row['id'] ?>" data-startdate="<?php echo $row['startdate'] ?>" data-wfmstatuscode="<?php echo $row['rowcolor'] ?>">
                        <div class="mr15" style="min-width:70px;float:left;">
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
                            <div class="row d-flex align-items-center justify-content-between w-100">
                                <div class="mdata w-100">
                                    <div class="taskdesc">
                                        <div><?php echo $row['location'] ?></div>
                                        <div class="text-muted"><?php echo $row['activityname'] ?></div>
                                    </div>
                                    <div class="d-flex justify-content-end ">
                                        <h3 class="text-danger mb-0"><button onclick="updateEvent(this ,'<?php echo $row['id'];?>')"  data-row="<?php echo $rowJson ?>" class="btn btn-sm btn-primary btn-rounded text-white" style="background-color: <?php //echo $row['wfmstatuscolor'] ? $row['wfmstatuscolor'] : '#3F51B5' ?>"><?php echo $row['purpose'] ?></button></h3>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>

            <?php }
            } ?>
            <div style="display: inline-block">
                <div id="calendar2-<?php echo $this->uniqId; ?>" class="hrmtimelog-calendar2"></div>
            </div>
        </div>
    </div>
</div>