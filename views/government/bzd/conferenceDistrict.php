<?php  
$role = $this->memberRole['readonly']; 
// $role = '2';
?>
    <div class="col-md-12 v1 government_<?php echo $this->uniqId ?> p-1" data-process-id="<?php echo $this->uniqId ?>" data-bp-uniq-id="<?php echo $this->uniqId ?>">
        <form action=""  method="post" id="gov_form_<?php echo $this->uniqId; ?>">
            <div class="d-flex align-items-start flex-column flex-md-row">
                <div class="w-100 order-2 order-md-1">
                    <div class="card border-none">
                        <div class="page-header-content header-elements-md-inline px-0">
                            <div class="page-title d-flex align-items-center p-2">
                                <div class="badge general_number_conf badge-primary d-flex flex-column p-0">
                                    <img class="parliament_logo" src="<?php echo Config::getFromCache('conference_header_logo'); ?>" onerror="onUserImgError(this);">
                                </div> 
                                <div class="d-flex flex-column">
                                    <h4 style="line-height: normal; font-size: 14px;"
                                        class="membername font-weight-bold text-uppercase line-height-normal d-flex align-items-center huraldaan-color">
                                        <?php echo (isset($this->selectedRow['namecode']) && $this->selectedRow['namecode']) ? $this->selectedRow['namecode'] : ''; ?></h4>
                                    <p class="posname2 d-flex align-items-center huraldaan-color"><i class="icon-calendar mr-2"></i> 
                                        <span class="type text-uppercase"></span><?php echo $this->selectedRow['startdate']; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="<?php echo ($role == '2') ? (($this->memberRole['showbutton'] == '1') ? '' : 'd-none') : 'd-none'; ?>">
                                <div class="workflow-buttons-<?php echo $this->id; ?>">
                                    
                                </div>
                            </div>

                            <div class="header-elements <?php echo ($role == '2') ? 'hidden  d-none' : ''; ?>">
                                <div class="d-flex justify-content-center review">
                                    <h1 class="d-none">00:00:00</h1>

                                    <input type="hidden" name="startTime" value="<?php echo (isset($this->selectedRow['starttime']) && $this->selectedRow['starttime']) ? $this->selectedRow['starttime'] : '' ?>"/>
                                    <input type="hidden" name="entTime" value=""/>
                                    <input type="hidden" name="durationTime" value=""/>
                                    <input type="hidden" name="bookid" value="<?php echo (isset($this->selectedRow['id'])) ?>"/>
                                    <input type="hidden" name="breakTime" value=""/>
                                    <input type="hidden" name="percent" value=""/>

                                    <div class="timer-window align-items-center text-left mr-2">
                                        <span class="timestart timer-start ">Эхэлсэн</span>
                                        <h5 class="line-height-normal text-blue_ huraldaan-color" id ="digital-clock"><?php echo (isset($this->selectedRow['starttime']) && $this->selectedRow['starttime']) ? $this->selectedRow['starttime'] : '00:00' ?></h5>
                                    </div>
                                    <div class="timer-window  align-items-center text-left mr-2 endTime <?php echo ($role == '2') ? 'hidden  dblock' : ''; ?>">
                                        <span class="timestart timer-end ">Дууссан</span>
                                        <h5 class="line-height-normal text-red_ huraldaan-color endTime_<?php echo $this->uniqId; ?>"><?php echo (isset($this->selectedRow['endtime']) && $this->selectedRow['endtime']) ? $this->selectedRow['endtime'] : '00:00' ?></h5>
                                    </div>
                                    <div class="timer-window  align-items-center text-left mr-2 durationTimer durationTimer_<?php echo $this->uniqId; ?> <?php echo ($role == '2') ? 'hidden  dblock' : ''; ?>">
                                        <span class="timestart">Үргэлжилсэн</span>
                                        <h5 class="line-height-normal text-green_ huraldaan-color"><?php echo (isset($this->selectedRow['duration']) && $this->selectedRow['duration']) ? $this->selectedRow['duration'] : '00:00:00' ?></h5>
                                    </div>
                                    <div class="timer-window  align-items-center text-left mr-2 breakTimer breakTimer_<?php echo $this->uniqId; ?>">
                                        <span class="timestart">Завсарласан</span>
                                        <h5 class="line-height-normal text-orange_ huraldaan-color huraldaan-color"><?php echo (isset($this->selectedRow['totalbreaktime']) && $this->selectedRow['totalbreaktime']) ? $this->selectedRow['totalbreaktime'] : '00:00:00' ?></h5>
                                    </div>
                                    <div class="timer-window  align-items-center text-left mr-2">
                                        <span class="timestart">Ирц</span>
                                        <h5 class="line-height-normal percentOfAttendance"> <?php echo (isset($this->selectedRow['percentofattendance']) && $this->selectedRow['percentofattendance']) ? $this->selectedRow['percentofattendance'] : '0%' ?></h5>
                                    </div>
                                    <div class="text-left mr-3 <?php echo ($role == '1') ? 'd-none' : ''; ?>" >
                                        <button 
                                            <?php echo ($this->conferenceData['action'] === '3') ? 'disabled="disabled"' : ''; ?>
                                            type="button" class="btn clock huraldaan-btn font-weight-bold timer-start <?php echo ($this->conferenceData['action'] === '3') ? 'disabled' : ''; ?> conference-action-<?php echo $this->uniqId ?>"  
                                            data-click= "8" onclick="timerAction_<?php echo $this->uniqId ?>(this, '<?php echo $this->selectedRow['id']?>')" 
                                            title="<?php echo ($this->conferenceData['action'] === '2') ? 'Үргэлжлүүлэх' : (($this->conferenceData['action'] === '1') ? 'Завсарлах' : 'Эхлүүлэх'); ?>" data-type="<?php echo ($this->conferenceData['action'] === '2') ? 'play' : (($this->conferenceData['action'] === '1') ? 'pause' : 'start'); ?>" style="width:60px;" >
                                            <i class="<?php echo ($this->conferenceData['action'] === '1') ? 'fa fa-pause' : 'fa fa-play' ?>"></i>
                                        </button>
                                    </div>
                                    <div class="text-left mr-3">
                                        <button type="button" 
                                            <?php echo ($this->conferenceData['action'] == '3' || $this->conferenceData['action'] === '0') ? 'disabled="disabled"' : ''; ?>
                                            class="btn clock huraldaan-btn font-weight-bold conferencestopbtn<?php echo $this->uniqId ?> <?php echo ($this->conferenceData['action'] === '3') ? 'disabled' : ''; ?> startbtn <?php echo ($role == '1') ? 'd-none' : ''; ?>" 
                                            onclick="timerAction_<?php echo $this->uniqId ?>(this, '<?php echo $this->selectedRow['id']?>')" title="Дуусгах" data-type="stop" 
                                            style="width:60px;">
                                            <i class="icon-stop2" style="padding-right: 3px;"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row  ">
                        <?php 
                        $colclass = 'col ';
                        if($role == '1'){
                            $colclass .= 'col-9';
                        }elseif($role == '2'){
                            $colclass .= 'col w-100';
                        }else{
                            $colclass = 'col-8';
                        }
                         ?>
                        <div class="<?php echo $colclass; ?> ">
                            <div class="card" style="display: none !important; ">
                                <div class="page-header-content header-elements-md-inline px-0 border-none" style="min-height: 100px;  ">
                                    <div class="page-title d-flex align-items-center pt-3 pb-3" id="selected-conference-<?php echo $this->uniqId ?>" style="display: none !important">
                                        <div class="badge general_number badge-primary d-flex flex-column ph28">
                                            <span class="numb">#<span id="conferencing-issue-number"></span></span>
                                            <span class="type text-uppercase">Асуудал</span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h4 style="line-height: normal;"
                                                class="mb-1 font-weight-bold confasname text-justify mr-3">
                                                <p class="font-weight-bold mb-0" id="conferencing-issue-name"></p>
                                            </h4>
                                            <div class="d-flex align-items-center mr-3 mb-3 mb-md-0 header_icon_box ">
                                                <div class="d-flex flex-row mr-3">
                                                    <span class="text-gray mr-1 font-weight-bold">Эхэлсэн:</span>
                                                    <h5 class="text-gray font-weight-bold mb-0" id="conferencing-issue-starttime"></h5>
                                                </div>
                                                <div class="d-flex flex-row">
                                                    <span class="text-gray mr-1 font-weight-bold">Дууссан:</span>
                                                    <h5 class="text-gray font-weight-bold mb-0" id="conferencing-issue-endtime"></h5>
                                                </div>
                                            </div>
                                            <div class="d-flex mt-n20"> 
                                                <div class="ml-auto"  id="profilemember"> 
                                                    <a class="profile-img" href="javascript:;">
                                                        <img src="assets/core/global/img/user.png" onerror="onUserImgError(this);" class="rounded-circle" alt="" width="34" height="34" >  
                                                    </a>
                                                    <div class="media-body float-right ml-1 hidden">
                                                        <div id="said" class="media-title font-weight-semibold ">{name}</div>
                                                        <span id="position2" class="text-muted">{position}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row pb-2 <?php echo ($role == '2') ? 'd-none' : ''; ?>">
                                <div class="col-6"><button type="button" class="btn bg-huraldaan w-100" onclick="addissue_<?php  echo $this->uniqId; ?>(this, '<?php echo $this->selectedRow['id'];?>' )"><i class="icon-plus"></i >Шинэ асуудал нэмэх</button></div>
                                <div class="col-6"><button type="button" class="btn bg-white-huraldaan w-100" onclick="addcustomissue_<?php  echo $this->uniqId; ?>(this, '<?php echo $this->selectedRow['id']; ?>' )"><i class="icon-plus"></i >Үүссэн асуудлаас оруулах</button></div>
                            </div>
                            <!-- NOT TAB START -->
                            <div class="card mb0 br-10">
                                <div class="gov_issui_<?php echo $this->uniqId; ?>" style="overflow: auto; border-radius: 10px;">
                                    <div class="not-tab">
                                        <ul class="d-flex justify-content-end mb-0 mt-1 pr-3">
                                            <li class="d-flex align-items-center mr-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" viewBox="0 0 8 8" fill="none">
                                                    <circle cx="4" cy="4" r="4" fill="#31BA96"/>
                                                </svg>
                                                <span class="ml-1 crm-title">Хэлэлцэж дууссан</span>
                                            </li>
                                            <li class="d-flex align-items-center mr-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" viewBox="0 0 8 8" fill="none">
                                                    <circle cx="4" cy="4" r="4" fill="#FDC144"/>
                                                </svg>
                                                <span class="ml-1 crm-title">Хэлэлцэж байгаа</span>
                                            </li>
                                            <li class="d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" viewBox="0 0 8 8" fill="none">
                                                    <circle cx="4" cy="4" r="4" fill="#FD6F61"/>
                                                </svg>
                                                <span class="ml-1 crm-title">Хэлэлцэх асуудал</span>
                                            </li>
                                        </ul>
                                        <a href="#highlighted-justified-tab1_<?php echo $this->uniqId; ?>"
                                            class="nav-link active show text-uppercase font-weight-bold pt-0 huraldaan-color"
                                            data-typeid="<?php echo $this->kheleltsekhTypeId ?>"
                                            data-toggle="tab">
                                            Хэлэлцэх
                                        </a>
                                        
                                        <div id="highlighted-justified-tab1_<?php echo $this->uniqId; ?>">
                                            <ul class="media-list conferencing-issue-list">
                                                
                                                <?php
                                                $i = 1;
                                                foreach ($this->issuelist as $row => $issuelist) {
                                                    $rowJson = htmlentities(json_encode($issuelist), ENT_QUOTES, 'UTF-8');
                                                    $isFinished = ($issuelist['endtime'] && $issuelist['starttime']) ? '1' : '0';
                                                    $class = ($issuelist['endtime'] && $issuelist['starttime']) ? 'issue-stop' : (($issuelist['starttime']) ? 'issue-start' : '');
                                                    
                                                    $issuelist['starttime'] = $starttime = Date::formatter($issuelist['starttime'], 'H:i:s');
                                                    $issuelist['endtime'] = $endtime = Date::formatter($issuelist['endtime'], 'H:i:s'); 

                                                    $tempRow = $issuelist;
                                                    $tempRowJson = htmlentities(json_encode($tempRow), ENT_QUOTES, 'UTF-8');
                                                    $addBackground = ($class === 'issue-start') ? 'background: #F4FAFF;' : '';

                                                    $more =  ' onclick="gridDrillDownLink(this, \'CMS_HELELTSEH_LIST\', \'bookmark\', \'1\', \'cmsSubjectWeblink\', \'1564710579741\', \'subjectname\', \'1564385570445960\', \'id='. $issuelist['id'] .'\', true, true)"';
                                                ?>
                                                <li data-id="<?php echo $issuelist['id']; ?>" 
                                                    data-row ="<?php echo $tempRowJson; ?>"
                                                    id="subject_<?php echo $issuelist['id']; ?>" data-ordernum="<?php echo $issuelist['ordernum']; ?>"  
                                                    style="<?php echo $addBackground; ?>"
                                                    class="c-issue-list media isitem d-flex justify-content-center align-items-center <?php echo ($role === '1' || $role === '0') ? 'tiktok-'.$this->uniqId . ' ' : ''; echo $class ?> media align-items-stretch">
                                                    <div class="p-1">
                                                        <p style="height:100%; border:3px solid <?php echo $issuelist['rowcolor']; ?>"></p>
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="font-weight-bold number" row-number="<?php echo $issuelist['ordernum']; ?>">
                                                            <p class="line-height-normal mb-0 conf-issuelist-name w-75">
                                                                <!-- <a style="color: #000;" href="javascript:;" data-row="<?php //echo $tempRowJson; ?>" <?php //echo ($role !='1') ? $more : ''; ?>> -->
                                                                <?php echo $issuelist['ordernum']; ?>. <?php echo $issuelist['subjectname']; ?>
                                                                <!-- </a> -->
                                                            </p>
                                                        </div>
                                                       
                                                        <ul class="media-title list-inline list-inline-dotted">
                                                            <li class="list-inline-item mt-1 w-75">
                                                                <span class="memberposition font-weight-bold"><?php echo issetParam($issuelist['saidname']) !== '' ? $issuelist['saidname'] . ' - ' : '' ; ?></span>
                                                                <span class="memberposition1 font-weight-bold"><?php echo $issuelist['departmentname']; ?></span>
                                                                <span class="memberposition2 font-weight-bold"><?php echo issetParam($issuelist['referentname']) !== '' ? '-' . $issuelist['referentname'] : ''; ?></span>
                                                                <span class="memberpic hidden"><?php echo $issuelist['saidphoto']; ?></span>
                                                            </li>
                                                        </ul>
                                                        <p class="font-weight-bold line-height-normal mb-0 conf-issuelist-start timestart conf-issuelist-timer "  style="text-align: right; <?php echo ($isFinished) ? '' : 'display:none;' ?>">
                                                            <span class="timestart timer-start"></span>
                                                            <?php echo $starttime . ' - ' . $endtime; ?>
                                                            <span class="icon-p"  data-toggle="tooltip" data-placement="bottom" title="Товлосон цагт засвар хийх" onclick="changeConferenceTimer_<?php echo $this->uniqId ?>(this)"> 
                                                                <i class="icon-alarm"></i>
                                                            </span>
                                                        </p>
                                                        <div class="w-100 participants align-self-center d-flex mt-1">
                                                            <span> Ажиглагч нар: <?php echo ($issuelist['subjectparticipantcount']) ? $issuelist['subjectparticipantcount'] : '0'; ?></span>
                                                            <button type="button" class="btn btn-outline-primary border-none ml-auto px-1 py-0" onclick="totalSum(this,<?php echo $issuelist['id'] ?>)">
                                                                <span class="huraldaan-total"><?php echo ($issuelist['total']) ? $issuelist['total'] : ''; ?></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="fissue align-self-center ml-3">
                                                        <?php if ($isFinished) { ?>
                                                            <span class="badge badge-success"><?php echo ($issuelist['count']) ? $issuelist['count'] : ''; ?></span>
                                                            <button type="button" class="btn font-weight-bold finishadd" onclick="finishByDescription_<?php echo $this->uniqId; ?>(this, '<?php echo $issuelist['id'] ?>')"><i class="fa fa-gavel"></i></button>
                                                        <?php } elseif ($class === 'issue-start') { ?>
                                                            <button type="button" class="btn font-weight-bold finishadd" onclick="setProtocol_<?php echo $this->uniqId; ?>(this, '<?php echo $issuelist['id'] ?>', '<?php echo $this->selectedRow['id'] ?>')">
                                                                <i class="fa fa-microphone"></i>
                                                            </button>
                                                            <button type="button" class="btn font-weight-bold finishFeedback" data-row ="<?php echo $tempRowJson; ?>" onclick="totalProtocol<?php echo $this->uniqId; ?>(this, '<?php echo $issuelist['id'] ?>', '<?php echo $issuelist['mapid']; ?>', '<?php echo $issuelist['meetingbookid']; ?>')">
                                                                <span>Санал хураалт</span>
                                                            </button>
                                                        <?php } ?>
                                                    </div>
                                                </li>
                                                <?php 
                                                    $i++;
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <a href="#highlighted-justified-tab2_<?php echo $this->uniqId; ?>"
                                            data-typeid="<?php echo $this->taniltsakhTypeId ?>"
                                            class="nav-link text-uppercase font-weight-bold huraldaan-color" data-toggle="tab">
                                            Тогтоол
                                        </a>
                                        <div id="highlighted-justified-tab2_<?php echo $this->uniqId; ?>">

                                            <ul class="media-list conferencing-issue-list">
                                                <?php
                                                foreach ($this->reviewgov as $row => $issuelist) {
                                                    $rowJson = htmlentities(json_encode($issuelist), ENT_QUOTES, 'UTF-8');
                                                    $isFinished = ($issuelist['endtime'] && $issuelist['starttime']) ? '1' : '0';
                                                    $class = ($issuelist['endtime'] && $issuelist['starttime']) ? 'issue-stop' : (($issuelist['starttime']) ? 'issue-start' : '');

                                                    $issuelist['starttime'] = $starttime = Date::formatter($issuelist['starttime'], 'H:i:s');
                                                    $issuelist['endtime'] = $endtime = Date::formatter($issuelist['endtime'], 'H:i:s'); 

                                                    $tempRow = $issuelist;
                                                    $tempRowJson = htmlentities(json_encode($tempRow), ENT_QUOTES, 'UTF-8');
                                                    $addBackground = ($class === 'issue-start') ? 'background: #F4FAFF;' : '';
                                                    $more =  ' onclick="gridDrillDownLink(this, \'CMS_HELELTSEH_LIST\', \'bookmark\', \'1\', \'cmsSubjectWeblink\', \'1564710579741\', \'subjectname\', \'1564385570445960\', \'id='. $issuelist['id'] .'\', true, true)"';
                                                ?>
                                                <li data-id="<?php echo $issuelist['id']; ?>" 
                                                    data-row ="<?php echo $tempRowJson; ?>"
                                                    id="subject_<?php echo $issuelist['id']; ?>" data-ordernum="<?php echo $issuelist['ordernum']; ?>"  
                                                    style="<?php echo $addBackground; ?>"
                                                    class="c-issue-list media isitem d-flex justify-content-center align-items-center <?php echo ($role != '1') ? 'tiktok-'.$this->uniqId . ' ' : ''; echo $class ?>  media align-items-stretch">
                                                    <div class="p-1">
                                                        <p style="height:100%; border:3px solid <?php echo $issuelist['rowcolor']; ?>"></p>
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="font-weight-bold number" row-number="<?php echo $issuelist['ordernum']; ?>">
                                                            <p class="line-height-normal mb-0 conf-issuelist-name w-75">
                                                                <!-- <a style="color: #000;" href="javascript:;" data-row="<?php //echo $tempRowJson; ?>" <?php //echo ($role !='1') ? $more : ''; ?>> -->
                                                                <?php echo $issuelist['ordernum']; ?>. <?php echo $issuelist['subjectname']; ?>
                                                                <!-- </a> -->
                                                            </p>
                                                        </div>
                                                        <ul class="media-title list-inline list-inline-dotted">
                                                            <li class="list-inline-item mt-1 w-75">
                                                                <span class="memberposition font-weight-bold"><?php echo issetParam($issuelist['saidname']) !== '' ? $issuelist['saidname'] . ' - ' : '' ; ?></span>
                                                                <span class="memberposition1 font-weight-bold"><?php echo $issuelist['departmentname']; ?> </span>
                                                                <span class="memberposition2 font-weight-bold"><?php echo issetParam($issuelist['referentname']) !== '' ? '-' . $issuelist['referentname'] : ''; ?></span>
                                                                <span class="memberpic hidden"><?php echo $issuelist['saidphoto']; ?></span>
                                                            </li>
                                                        </ul>
                                                        <p class="font-weight-bold line-height-normal mb-0 conf-issuelist-start timestart conf-issuelist-timer"  style="text-align: right; <?php echo ($isFinished) ? '' : 'display:none;' ?>">
                                                            <span class="timestart timer-start"></span>
                                                            <?php echo $starttime . ' - ' . $endtime; ?>
                                                            <span class="icon-p"  data-toggle="tooltip" data-placement="bottom" title="Товлосон цагт засвар хийх" onclick="changeConferenceTimer_<?php echo $this->uniqId ?>(this)"> 
                                                                <i class="icon-alarm"></i>
                                                            </span>
                                                        </p>
                                                        <div class="participants align-self-center d-flex mt-1">
                                                            <span> Ажиглагч нар: <?php echo ($issuelist['subjectparticipantcount']) ? $issuelist['subjectparticipantcount'] : '0'; ?></span>
                                                            <button type="button" class="btn btn-outline-primary border-none ml-auto px-1 py-0" onclick="totalSum(this,<?php echo $issuelist['id'] ?>)">
                                                                <span class="huraldaan-total ml-auto"><?php echo ($issuelist['total']) ? $issuelist['total'] : ''; ?></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="fissue align-self-center ml-3">
                                                        <?php if ($isFinished) { ?>
                                                            <span class="badge badge-success"><?php echo ($issuelist['count']) ? $issuelist['count'] : ''; ?></span>
                                                            <button type="button" class="btn font-weight-bold finishadd" onclick="finishByDescription_<?php echo $this->uniqId; ?>(this, '<?php echo $issuelist['id'] ?>')"><i class="fa fa-gavel"></i></button>
                                                        <?php } elseif ($class === 'issue-start') { ?>
                                                            <button type="button" class="btn font-weight-bold finishadd" onclick="setProtocol_<?php echo $this->uniqId; ?>(this, '<?php echo $issuelist['id'] ?>', '<?php echo $this->selectedRow['id']; ?>')">
                                                                <i class="fa fa-microphone"></i>
                                                            </button>
                                                            <button type="button" class="btn font-weight-bold finishFeedback" data-row ="<?php echo $tempRowJson; ?>" onclick="totalProtocol<?php echo $this->uniqId; ?>(this, '<?php echo $issuelist['id'] ?>', '<?php echo $issuelist['mapid']; ?>', '<?php echo $issuelist['meetingbookid']; ?>')">
                                                                <span>Санал хураалт</span>
                                                            </button>
                                                        <?php } ?>
                                                    </div>
                                                </li>
                                                <?php 
                                                    $i++; 
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- NOT TAB END -->
                        </div> 
                        <div class="col-<?php echo ($role === '1') ? '3' : '4' ?> <?php echo ($role == '2') ? 'd-none' : ''; ?>">
                            <div class="member-list-conference">
                                <div class="sidebar-content">
                                    <div class="card mainmember<?php echo $this->uniqId ?>">
                                        <div class="card-header bg-transparent header-elements-inline">
                                            <span class="text-uppercase font-weight-bold">Хуралдаанд оролцогчид</span>
                                            <div class="header-elements">
                                                <div class="list-icons">
                                                    <a class="list-icons-item" data-action="collapse"></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-body pt-0">
                                            <ul class="media-list">
                                                <?php
                                                foreach($this->member as $row => $member) { 
                                                    $rowJson = htmlentities(json_encode($member), ENT_QUOTES, 'UTF-8'); 
                                                    ?>
                                                <li class="media media_<?php echo $member['id']; ?>" data-status="<?php echo ($member['wfmstatusid'] === "1506391592724323" || $member['wfmstatusid'] === "1560435540488" || $member['wfmstatusid'] === "1506391623676392" || $member['wfmstatusid'] === "1560435540486"|| $member['wfmstatusid'] === "1562133250548") ? '1' : '0'; ?>" data-row="<?php echo $rowJson; ?>" id="media_<?php echo $member['id']; ?>">

                                                    <input type="hidden" name="employeekeyid[]" value="<?php echo $member['employeekeyid']?>">
                                                    <input type="hidden" name="wfmstatusid[]" value="<?php echo $member['wfmstatusid']?>">
                                                    <input type="hidden" name="userid[]" value="<?php echo $member['id']?>">    
                                                    
                                                    <a href="javascript:;" class="mr-2 position-relative">
                                                        <div class="data-tooltip">
                                                            <img src="<?php echo $member['picture']; ?>" class="rounded-circle" onerror="onUserImgError(this);" width="34" height="34">
                                                            <div class="tooltipright">
                                                                <h5><?php echo $member['positionname']; ?></h5>
                                                                <label class="label<?php echo $member['id']; ?>"><?php echo (isset($member['wfmdescription']) && $member['wfmdescription']) ? 'Тайлбар : ' . $member['wfmdescription'] : ''; ?></label>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    <div class="media-body" style="width:30%">
                                                        <div class="membername font-weight-bold text-uppercase line-height-normal d-flex align-items-center">
                                                            <span title="<?php echo $member['employeename']; ?>"><?php echo $member['employeename']; ?></span>
                                                            <!--<span class="badgecus log<?php echo $member['id'];?>" onclick="memberLog(this, '<?php echo $member['id'];?>')"><i class="icon-pencil"></i></span>-->
                                                        </div>
                                                        <span class="memberposition"><?php echo $member['positionname']; ?></span>
                                                    </div>

                                                    <div class="ml-3 align-self-center" style="margin-top: -3px;">
                                                        <a href="javascript:;" class="position-relative">
                                                            <button
                                                                    type="button"  
                                                                    <?php echo ($role == '1') ? 'disabled="disabled"' : ""; ?>  
                                                                    id="mem<?php echo $member['id'];?>" 
                                                                    class="btn startbtn small" 
                                                                    data-num="<?php echo $member['isarrived'];?>"  
                                                                    onclick="member_list_<?php echo $this->uniqId; ?>(this, '<?php echo $member['id']; ?>', '<?php echo $member['bookid']; ?>', '<?php echo $member['employeekeyid']; ?>', '<?php echo $member['isarrived'];?>')" 
                                                                    data-status="<?php echo $member['wfmstatusid'];?>"
                                                                    title="<?php // echo $member['time1']; ?>">
                                                                <?php echo ($member['time1']) ? $member['time1'] : 'Ирсэн'; ?>
                                                                <?php // echo ($member['isarrived'] == '1') ? "<i class='icon-pause memberactive".$member['id']."'></i>" : "<i class='btn-icon" . $member['id'] . " icon-play4'></i>"; ?>
                                                            </button>
                                                        </a>
                                                    </div>
                                                    <div class="ml-1 align-self-center" style="margin-top: -3px;">
                                                        <a href="javascript:void(0);" class="position-relative">
                                                            <button 
                                                                type="button" 
                                                                <?php echo ($role == '1') ? 'disabled="disabled"' : ""; ?>  
                                                                class="btn startbtn btnstatus   small "
                                                                id="btnstatus_<?php echo $member['id']; ?>" 
                                                                onclick = "member_status_<?php echo $this->uniqId; ?>(this, '<?php echo $member['id']; ?>', '<?php echo $member['bookid']; ?>' , '<?php echo $member['employeekeyid']; ?>', '<?php echo $member['isarrived'];?>')" 
                                                                data-status = "<?php echo $member['wfmstatusid'];?>">
                                                                <i class='<?php echo $member['wfmicon'] ?>'></i>
                                                            </button>
                                                        </a>
                                                    </div>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card othermember<?php echo $this->uniqId ?>">
                                        <div class="card-header bg-transparent header-elements-inline">
                                            <span class="text-uppercase font-weight-bold">Бусад оролцогчид</span>
                                        </div>
                                        <div class="card-body pt-0">
                                            <ul class="media-list">
                                                <?php foreach($this->othermember as $row => $othermember) { 
                                                    $rowJson = htmlentities(json_encode($othermember), ENT_QUOTES, 'UTF-8');
                                                    if ($othermember['id']) { ?>
                                                    <li class="media media_<?php echo $othermember['id']; ?>" data-status="<?php echo ($othermember['time1']) ? '1' : '0'; ?>" data-row="<?php echo $rowJson; ?>" id="media_<?php echo $othermember['id']; ?>">
                                                        <a href="javascript:;" class="mr-2 position-relative">
                                                            <div class="data-tooltip">
                                                                <img src="<?php echo $othermember['picture']; ?>" class="rounded-circle" onerror="onUserImgError(this);" width="34" height="34">
                                                                <div class="tooltipright">
                                                                    <h5><?php echo $othermember['positionname']; ?></h5>
                                                                    <label class="label<?php echo $othermember['id']; ?>"><?php echo (isset($othermember['wfmdescription']) && $othermember['wfmdescription']) ? 'Тайлбар : ' . $othermember['wfmdescription'] : ''; ?></label>
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <div class="media-body">
                                                            <div class="membername font-weight-bold text-uppercase line-height-normal d-flex align-items-center">
                                                                <span><?php echo $othermember['employeename']; ?></span>
                                                            </div>
                                                            <span class="memberposition"><?php echo $othermember['positionname']; ?></span>
                                                        </div>
                                                        <div class="ml-3 align-self-center" style="margin-top: -3px;">
                                                            <a href="javascript:;" class="position-relative">
                                                                <button type="button" 
                                                                    <?php echo ($role == '1') ? 'disabled="disabled"' : ""; ?>
                                                                    id="mem<?php echo $othermember['id'];?>" 
                                                                    class="btn startbtn small" 
                                                                    data-num="1<?php echo $othermember['isarrived'];?>"  
                                                                    onclick="member_list_<?php echo $this->uniqId; ?>(this, '<?php echo $othermember['id']; ?>', '<?php echo $othermember['bookid']; ?>', '<?php echo $othermember['employeekeyid']; ?>', '<?php echo $othermember['isarrived'];?>')">
                                                                    <?php echo ($othermember['time1']) ? $othermember['time1'] : 'Ирсэн'; ?>
                                                                    <?php echo ''; //($othermember['isarrived'] == '1') ? "<i class='icon-pause memberactive".$othermember['id']."'></i>" : "<i class='btn-icon" . $othermember['id'] . " icon-play4'></i>"; ?>
                                                                </button>
                                                            </a>
                                                        </div>
                                                        <div class="ml-1 align-self-center " style="margin-top: -3px;">
                                                            <a href="javascript:void(0);" class="position-relative">
                                                                <button 
                                                                    type="button" 
                                                                    <?php echo ($role == '1') ? 'disabled="disabled"' : ""; ?> 
                                                                    class="btn startbtn btnstatus  small"  
                                                                    id="btnstatus_<?php echo $othermember['id']; ?>" 
                                                                    onclick="member_status_<?php echo $this->uniqId; ?>(this, '<?php echo $othermember['id']; ?>', '<?php echo $othermember['bookid']; ?>', '<?php echo $othermember['employeekeyid']; ?>', '<?php echo $othermember['isarrived'];?>')" 
                                                                    data-status="<?php echo $othermember['wfmstatusid'];?>">
                                                                    <i class='<?php echo $othermember['wfmicon'] ?>'></i>
                                                                </button>
                                                            </a>
                                                        </div>
                                                    </li>
                                                <?php } }?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="<?php echo ($role === '1') ? 'd-none' : (($role == '2') ? 'd-none' : '') ?> sidebar-right2 wmin-250 border-0 shadow-0 order-1 order-md-2 sidebar-expand-md"> 
                    <?php if ($role !== '1') { ?>
                        <div class="card mb-3" id="protocol-list-<?php echo $this->uniqId ?>" style="border-radius: 10px;">
                            <div class="card-header header-elements-inline ">
                                <h6 class="card-title font-weight-bold text-uppercase protocol-title">Асуудлын тэмдэглэл <span style="color:#A0A0A0"></span></h6>
                                <div id="issue<?php echo $this->uniqId ?>"></div>
                            </div>
                            <div class="card-body">
                                <textarea name="enter-protocol" data-subject-id="" class="form-control" placeholder="Тэмдэглэлээ бичээд ENTER дарна уу..." style="height: 50px">@</textarea>
                                <ul class="media-list media-chat-scrollable mt10 conferencing-protocol-list" id="conferencing-protocol-list-<?php echo $this->uniqId ?>"></ul>
                            </div>
                        </div>
                    <?php } ?>    

                    <div class="card" id="protocol-list-<?php echo $this->uniqId ?>" style="border-radius: 10px;">
                        <div class="card-header header-elements-inline">
                            <h6 class="card-title font-weight-bold text-uppercase protocol-title huraldaan-color">Ажиглагч нар<br><span style="color:#A0A0A0"></span></h6>
                        </div>
                        <div class="card-body">
                            <div id="govaddmember<?php echo $this->uniqId ?>"></div>
                            <ul class="media-list other-member-list" id="other-member-list-<?php echo $this->uniqId ?>">
                            </ul>
                        </div>
                    </div> 
                </div>
            </div>
            <?php foreach($this->allmember as $row => $allmember) { ?>
                <input type="hidden" name="employeekey[]" value="<?php echo $allmember['employeekeyid'];?>">
                <input type="hidden" name="users[]" value="<?php echo $allmember['id'];?>">
                <input type="hidden" name="bookid" value="<?php echo $this->selectedRow['id'];?>">
            <?php } ?>

        </form>
    </div>
    <div class="clearfix w-100"></div>

<?php echo isset($this->defaultCss) ? $this->defaultCss : ''; ?>
<?php echo isset($this->defaultJs) ? $this->defaultJs : ''; ?>