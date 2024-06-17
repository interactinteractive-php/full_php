<div class="row wfm-log-form-save">
    <?php
    if ($this->isForm) { 
    ?>
    <form id="changeWfmStatusForm_<?php echo $this->metaDataId ?>" class="form-horizontal w-100" method="post" enctype="multipart/form-data">
    <?php 
    }
    
    $suserid = Ue::sessionUserKeyId();
    $cuserid = issetParam($this->dataRow['deletefilewfmcreateduserid']);
    echo Form::hidden(array('name' => 'refStructureId', 'value' => $this->refStructureId)); 
    echo Form::hidden(array('name' => 'metaDataId', 'value' => $this->metaDataId)); 
    echo Form::hidden(array('name' => 'recordId', 'value' => $this->recordId)); 
    echo Form::hidden(array('name' => 'wfmStatusId', 'value' => $this->wfmStatusId)); 
    echo Form::hidden(array('name' => 'isIndicator', 'value' => issetParam($this->dataRow['iskpiindicator']))); 

    $wfmRuleId = isset($this->getWfmStatus['DEFAULT_RULE_ID']) ? $this->getWfmStatus['DEFAULT_RULE_ID'] : '1';
    ?>
        <div class="col-md-12">
            <?php 
            if ($this->isSee === 'false') { 
            ?>
                <div class="form-group row">
                    <?php echo Form::label(array('text'=>$this->lang->lineDefault('selected_status', 'Сонгосон төлөв'), 'class'=>'col-form-label d-flex align-items-center justify-content-end col-md-4')); ?>
                    <div class="col-md-auto pl0 ml10">
                        <p class="form-control-plaintext">
                            <span class="badge font-size-12" style="background-color: <?php echo $this->newWfmStatusColor; ?>">
                                <?php echo $this->newWfmStatusName; ?>
                            </span>
                        </p>
                    </div>
                </div>
                <?php 
                $textAreaAttr = array('name' => 'description', 'id' => 'newWfmDescription', 'placeholder' => $this->lang->line('META_00007'), 'id' => 'description', 'class' => 'form-control mb15', 'style' => 'height: 65px');
                if (!empty($this->getWfmStatus['IS_DESC_REQUIRED'])) {
                    $textAreaAttr['required'] = 'required';
                }
                echo Form::textArea($textAreaAttr); 
                                
                if ($this->getWfmStatus['IS_HIDE_FILE'] != '1') {
                ?>
                <div class="form-group fom-row mb0">
                    <div>
                        <div class="fileSidebarRows col-md-12 p-0">
                            <span class="btn btn-xs bg-slate fileinput-button mb5">
                                <span><?php echo $this->lang->line('select_file_btn'); ?></span>
                                <input type="file" name="workflowFiles[]" onchange="changeWorkflowContentDvName(this)" style="width:100%">
                            </span>
                            <span data-path="physicalPath" class="float-left ml5" style="margin-left: 2px;"></span>
                            <a href="javascript:;" class="btn btn-xs green-meadow float-left ml5" title="<?php echo $this->lang->line('META_00057'); ?>" onclick="addFileWorkflow_<?php echo $this->metaDataId; ?>(this);">
                                <i class="icon-plus3 font-size-12"></i>
                            </a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            <?php 
                }
            } 
            echo "<div class='row'>";
            
                echo isset($this->wfmStatusNext) && $this->wfmStatusNext && $this->getWfmStatus['IS_HIDE_NEXT_USER'] != '1' ? "<div class='col-md-9'>" : "<div class='col-md-12'>";
                
                if (isset($this->wfmStatusLog)) {
                    
                    $isShowAssignmentRemove = Config::getFromCache('IS_SHOW_ASSIGNMENT_REMOVE');
                    $isWfmNotAssignReturn = Config::getFromCache('IS_WFM_NOT_ASSIGN_RETURN');
                    $isWfmNotAssignRemove = Config::getFromCache('IS_WFM_NOT_ASSIGN_DELETE');
                ?>
                    <div class="card-body" style="border-radius: 10px;background: white;padding: 8px;">
                        <div class="mt-element-list">
                            <div class="mt-list-container list-todo" id="accordion1" role="tablist" aria-multiselectable="true">
                                <div class="list-todo-line"></div>
                                <ul>
                                    <?php 
                                    if (isset($this->wfmStatusLog['data']['log']) && $this->wfmStatusLog['data']['log']) {
                                        
                                        $logData = $this->wfmStatusLog['data']['log'];
                                        $sizeOfData = count($logData);
                                        $indexKey = 1;
                                        $ticket = false;
                                        $isNextUserSendMail = (!isset($this->isSentShowWorkflowLog) || (isset($this->isSentShowWorkflowLog) && ($this->isSentShowWorkflowLog == '' || $this->isSentShowWorkflowLog == '1')));
                                        ?>
                                        <li class="mt-list-item">
                                            <div class="list-todo-item grey">
                                                <div class="list-toggle done">
                                                    <div class="list-toggle-title font-weight-bold">
                                                        <table class="table mb0 wfm-header-table-<?php echo $this->metaDataId ?>">
                                                            <?php
                                                            foreach ($logData as $pkey => $wfmLog) {
                                                                if ($indexKey === 1) { $tstyle = ''; } else { $tstyle = 'display:none;'; } 
                                                            ?>
                                                            <thead>
                                                                <tr>
                                                                    <th style="<?php echo $tstyle; ?>">
                                                                        <label class="font-weight-bold" style="color:#ADADAD;"><?php echo Lang::line('wf_user'); ?></label>
                                                                    </th>
                                                                    <th style="<?php echo $tstyle; ?>">
                                                                        <label class="font-weight-bold" style="color:#ADADAD;"><?php echo Lang::line('wfm_status_'); ?></label>
                                                                    </th>
                                                                    <th style="<?php echo $tstyle; ?>">
                                                                        <label class="font-weight-bold" style="color:#ADADAD;"><?php echo $this->lang->line('date'); ?></label>
                                                                    </th>
                                                                    <?php
                                                                    if (isset($this->isShowTimeSpent) && $this->isShowTimeSpent) {
                                                                    ?>
                                                                    <th style="width: 120px;<?php echo $tstyle; ?>">
                                                                        <label class="font-weight-bold" style="color:#ADADAD;"><?php echo $this->lang->line('wf_duration'); ?></label>
                                                                    </th>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    <th style="width:350px;<?php echo $tstyle; ?>">
                                                                        <label class="font-weight-bold" style="color:#ADADAD;"><?php echo Lang::line('wf_description'); ?></label>
                                                                    </th>
                                                                    <th style="<?php echo $tstyle; ?>">
                                                                        <label class="font-weight-bold" style="color:#ADADAD;"><?php echo Lang::line('wfm_assign') ?></label>
                                                                    </th>
                                                                    <?php
                                                                    if (!$this->isIgnoreRuleCodeWorkflowLog) {
                                                                    ?>
                                                                    <th style="<?php echo $tstyle; ?>">
                                                                        <label class="font-weight-bold" style="color:#ADADAD;"><?php echo $this->lang->line('wfm_rule_code'); ?></label>
                                                                    </th>     
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    <th style="<?php echo $tstyle; ?>">
                                                                        <label class="font-weight-bold" style="color:#ADADAD;"><?php echo Lang::line('wfm_file') ?></label>
                                                                    </th>
                                                                </tr>
                                                            </thead>                                                   
                                                            <tbody>
                                                                <tr data-wfm-log-id="<?php echo $wfmLog['wfmlogid'] ?>">
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="mr-2">
                                                                                <img class="rounded-circle" style="object-fit:contain" src="<?php echo isset($wfmLog['picture']) ? 'api/image_thumbnail?width=40&src='.$wfmLog['picture'] : ''; ?>" onerror="onUserLogoError(this);" width="40" height="40">
                                                                            </div> 
                                                                            <div class="line-height-normal">
                                                                                <label class="text-default font-weight-bold"><?php echo issetParam($wfmLog['username']); ?></label>
                                                                                <?php if (isset($wfmLog['aliasusername'])) { ?>
                                                                                    <label class="text-default font-weight-bold"><?php echo '('.$wfmLog['aliasusername'].')'; ?></label>
                                                                                <?php } ?>
                                                                                <div title="<?php echo issetParam($wfmLog['departmentname']); ?>">
                                                                                    <label class="text-muted font-size-11"><?php echo isset($wfmLog['departmentname']) ? mb_substr($wfmLog['departmentname'], 0, 30). ', ' : ''; ?></label>
                                                                                    <span title="<?php echo issetParam($wfmLog['positionname']); ?>">
                                                                                        <label class="text-muted font-size-11"><?php echo mb_substr(issetParam($wfmLog['positionname']), 0, 30); ?></label>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <span class="badge font-size-12" title="<?php echo issetParam($wfmLog['wfmstatusname']); ?>" style="border-radius: 15px;white-space: inherit;background-color: <?php echo issetParam($wfmLog['wfmstatuscolor']); ?>;"><?php echo ((isset($wfmLog['isneedsign']) && $wfmLog['isneedsign'] === '1') ? '<i class="fa fa-key"></i>' : ''); ?> <?php echo issetParam($wfmLog['wfmstatusname']); ?></span>
                                                                    </td>
                                                                    <td class="font-weight-normal">
                                                                        <span title="<?php echo $wfmLog['createddate']; ?>"><strong><?php echo isset($wfmLog['createddate']) ? Date::formatter($wfmLog['createddate'], 'Y.m.d') . '</strong> <span style="color:#ADADAD">'.Date::formatter($wfmLog['createddate'], 'H:i').'</span>' : '<i class="fas fa-horizontal-rule"></i>'; ?></span>
                                                                    </td>
                                                                    <?php
                                                                    if (isset($this->isShowTimeSpent) && $this->isShowTimeSpent) {
                                                                        
                                                                        $progressColor = '';
                                                                        
                                                                        if (!array_key_exists('timespent', $wfmLog)) {
                                                                            
                                                                            $wfmLog['timespent'] = '';
                                                                            $wfmLog['timespentpercent'] = '';
                                                                            
                                                                        } elseif ($wfmLog['timespentpercent']) {
                                                                            
                                                                            if ($wfmLog['timespentpercent'] <= 49) {
                                                                                $progressColor = '#4caf50'; //green
                                                                            } elseif ($wfmLog['timespentpercent'] <= 100) {
                                                                                $progressColor = '#2196f3'; //blue
                                                                            } else {
                                                                                $progressColor = '#f44336'; //red
                                                                            }
                                                                        }
                                                                    ?>
                                                                    <td>
                                                                        <span style="color: <?php echo $progressColor; ?>"><?php echo Date::minutesToHumanReadable($wfmLog['timespent']); ?></span>
                                                                        <div class="progress" style="height: 5px;">
                                                                            <div class="progress-bar" style="width: <?php echo $wfmLog['timespentpercent']; ?>%; background-color: <?php echo $progressColor; ?>"></div>
                                                                            <?php echo $wfmLog['timespentpercent'] ? '<div style="position: absolute;left: 0;right: 0;margin-left: auto;margin-right: auto;width: 20px;margin-top: 4px;text-align: center;">'.$wfmLog['timespentpercent'].'%</div>' : ''; ?>
                                                                        </div>
                                                                    </td>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    <td style="width:350px;text-transform: none;" class="font-weight-normal">
                                                                        <span style="word-break: break-word;">
                                                                            <strong><?php echo isset($wfmLog['wfmdescription']) ? html_entity_decode($wfmLog['wfmdescription'], ENT_QUOTES) : '<i class="fas fa-horizontal-rule"></i>'; ?></strong>
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        if ($sizeOfData > 1 && $sizeOfData > ($pkey + 1) && $assignmentsCount = issetCount($wfmLog['assignments'])) {
                                                                        ?>
                                                                        <div class="badge badge-secondary font-weight-bold cursor-pointer font-size-12" title="Нийт" style="background-color: #000;" onclick="wmfDrillFromDataview_<?php echo $this->metaDataId ?>(this, '<?php echo $wfmLog['wfmlogid']; ?>');"><?php echo $assignmentsCount; ?></div>
                                                                        <script type="text/template" data-template="assignments-json"><?php echo json_encode($wfmLog['assignments'], JSON_UNESCAPED_UNICODE); ?></script>
                                                                        <?php
                                                                        }
                                                                        
                                                                        if ($sizeOfData != $indexKey) {
                                                                            echo '<i class="fas fa-horizontal-rule"></i>';
                                                                        }
                                                                        
                                                                        if ($isNextUserSendMail) {
                                                                        ?>
                                                                        <div class="badge badge-secondary font-weight-bold <?php echo ($sizeOfData === $indexKey) ? '' : 'hidden' ?>" title="Имэйл илгээх" onclick="sendMailBySelectionUser_<?php echo $this->metaDataId ?>(this, '<?php echo $wfmLog['wfmlogid']; ?>');" style="background-color: #89C4F4;"><i class="fa fa-send" style="margin-top: -1px;"></i></div>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <?php
                                                                    if (!$this->isIgnoreRuleCodeWorkflowLog) {
                                                                    ?>
                                                                    <td class="font-weight-normal">
                                                                        <span><?php echo isset($wfmLog['rulecode']) ? $wfmLog['rulecode'] : '<i class="fas fa-horizontal-rule"></i>'; ?></span>
                                                                    </td>  
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    <td>
                                                                        <?php                                                                                                                                                
                                                                        if (isset($wfmLog['attachedfiles']) && is_array($wfmLog['attachedfiles'])) {
                                                                            
                                                                            if (count($wfmLog['attachedfiles']) > 1) {
                                                                                
                                                                                echo '<div class="btn-group">';
                                                                                echo '<button class="btn green-meadow btn-circle btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-download"></i> Файлууд</button>';
                                                                                echo '<ul class="dropdown-menu" role="menu">';
                                                                                
                                                                                foreach ($wfmLog['attachedfiles'] as $fkey => $frow) {
                                                                                    
                                                                                    $physicalpath  = $frow['physicalpath'];
                                                                                    $fileName      = $frow['filename'];
                                                                                    $fileExtension = strtolower(substr($physicalpath, strrpos($physicalpath, '.') + 1));
                                                                                    
                                                                                    $deleteFileBtn = '';
                                                                                    if ($suserid == issetParam($wfmLog['createduserid']) || $cuserid == $suserid) {
                                                                                        $deleteFileBtn = '<a href="javascript:;" onclick="removeMeFileWfmLog(this, \''.$frow['contentid'].'\', \''.$frow['wfmlogid'].'\');" title="Файл устгах"><i class="icon-cross font-size-14" style="color: red;"></i></a>';
                                                                                    }                                                                                    

                                                                                    if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif')) === true) {

                                                                                        $button = '<a href="'.$physicalpath.'" class="fancybox-img" data-rel="fancybox-button">';

                                                                                    } elseif (in_array($fileExtension, array('pdf', 'xls', 'xlsx', 'doc', 'docx')) === true) {

                                                                                        $button = '<a href="javascript:;" onclick="dataViewFileViewer(this, \'\', \''.$fileExtension.'\', \''.$fileName.'\', \''.URL.$physicalpath.'\');">';

                                                                                    } else {

                                                                                        $button = '<a href="mdobject/downloadFile?file=' . $frow['physicalpath'] . '" target="_blank" title="Файл татах">';
                                                                                    }

                                                                                    echo '<li>';
                                                                                    echo $button;
                                                                                    echo '<i class="icon-file-eye2"></i> ' . $fileName;
                                                                                    echo '</a>';
                                                                                    echo $deleteFileBtn;
                                                                                    echo '</li>';
                                                                                } 
                                                                                
                                                                                echo '</ul>';
                                                                                echo '</div>';
                                                                                
                                                                            } else {
                                                                                
                                                                                $deleteFileBtn = '';
                                                                                if ($suserid == issetParam($wfmLog['createduserid'])  || $cuserid == $suserid) {
                                                                                    $deleteFileBtn = '<a href="javascript:;" onclick="removeMeFileWfmLog(this, \''.$wfmLog['attachedfiles'][0]['contentid'].'\', \''.$wfmLog['wfmlogid'].'\');" title="Файл устгах"><i class="icon-cross font-size-14" style="color: red;"></i></a>';
                                                                                }
                                                                                $physicalpath = $wfmLog['attachedfiles'][0]['physicalpath'];
                                                                                $fileName = $wfmLog['attachedfiles'][0]['filename'];
                                                                                $fileExtension = strtolower(substr($physicalpath, strrpos($physicalpath, '.') + 1));
                                                                                
                                                                                if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif', 'bmp')) === true) {

                                                                                    $button = '<a href="'.$physicalpath.'" class="fancybox-img mr3" data-rel="fancybox-button">';

                                                                                } elseif (in_array($fileExtension, array('pdf', 'xls', 'xlsx', 'doc', 'docx')) === true) {

                                                                                    $button = '<a class="mr3" href="javascript:;" onclick="dataViewFileViewer(this, \'\', \''.$fileExtension.'\', \''.$fileName.'\', \''.URL.$physicalpath.'\');">';

                                                                                } else {
                                                                                    
                                                                                    $button = '<a class="mr3" href="mdobject/downloadFile?file=' . $wfmLog['attachedfiles'][0]['physicalpath'] . '" target="_blank" title="Файл татах">';
                                                                                }                                                                        
                                                                                
                                                                                echo $button;
                                                                                echo '<i class="icon-file-eye2 font-size-14"></i> ' . $fileName;
                                                                                echo '</a>';                                     
                                                                                echo $deleteFileBtn;
                                                                            }
                                                                        } else {
                                                                            echo '<i class="fas fa-horizontal-rule"></i>';
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        <?php 
                                                        $indexKey++; 
                                                        } 
                                                        ?>
                                                        </table>
                                                    </div>
                                                </div>
                                                <?php $indexKey = $sizeOfData; ?>
                                                <div class="task-list panel-collapse collapse <?php echo ($indexKey == $sizeOfData) ? 'in' : '' ?>" id="task-<?php echo $this->metaDataId.'_'.$indexKey ?>" aria-expanded="false">
                                                    <?php if ($indexKey === $sizeOfData) { ?>
                                                        <div class="rule-<?php echo $pkey. '_'. $this->metaDataId ?>" style="border-radius: 0;"></div>
                                                    <?php } ?>
                                                    <ul>
                                                        <?php
                                                        if ($indexKey === $sizeOfData) {
                                                            $ticket = true;
                                                        }
                                                        
                                                        if ((isset($wfmLog['assignments']) && $wfmLog['assignments']) || $ticket) { 
                                                            $sizeAssig = issetCount($wfmLog['assignments']);
                                                            $assigInKey = 1;
                                                            
                                                            if (isset($wfmLog['assignments']) && $wfmLog['assignments']) {
                                                                $assTicket = ($sizeAssig == $assigInKey) ? true : false;
                                                        ?>
                                                            <li class="task-list-item done">
                                                                <div class="task-content pl0 pr0 wfm-header-dtl-<?php echo $this->metaDataId ?>">
                                                                    <div class="font-weight-bold overflow-auto wfm-header-dtltable-<?php echo $this->metaDataId ?>">
                                                                        <table class="table mb0" data-table-key="<?php echo $pkey; ?>" id="<?php echo ($ticket && $assTicket) ? 'wfm-header-assoign-dtltable-last-' : 'wfm-header-assoign-dtltable-'.$pkey.'_'.$this->metaDataId; ?>">
                                                                            <?php 
                                                                            $key2 = 0;
                                                                            foreach ($wfmLog['assignments'] as $key => $wfmLogAssig) {
                                                                                if (issetParam($wfmLogAssig['isactive']) == '0') {
                                                                                    echo '<tbody></tbody>';
                                                                                    continue;
                                                                                }
                                                                                
                                                                                $sclass = ($key2 === 0) ? '' : 'hidden';  
                                                                                $btn = ($key2 === 0) ? true : false;
                                                                            ?>
                                                                                <div class="task-status" style="<?php echo ($btn) ? 'margin-top: 20px;' : ''; ?>">
                                                                                    <?php 
                                                                                    if (isset($this->isUseAssign) && $this->isUseAssign === '1') { 
                                                                                        echo Html::anchor(
                                                                                            'javascript:;', '<i class="fa fa-arrow-right"></i>', array(
                                                                                                'class' => 'done ',
                                                                                                'title' => 'Шилжүүлэх',
                                                                                                'data-key' => $pkey,
                                                                                                'data-assignmentid' => $wfmLogAssig['id'],
                                                                                                'data-isneedsign' => ((isset($wfmLog['isneedsign']) && $wfmLog['isneedsign'] === '1') ? '1' : '0'),
                                                                                                'data-isuserdefassign' => ((isset($wfmLog['isuserdefassign']) && $wfmLog['isuserdefassign'] === '1') ? '1' : '0'),
                                                                                                'onclick' => 'addMultiWfmStatusAssigmentClick(this);',
                                                                                            ), ((isset($wfmLog['isuserdefassign']) && $wfmLog['isuserdefassign'] == '1' && $wfmLogAssig['userid'] == $this->userKeyId && $ticket) ? true : false)
                                                                                        ); 
                                                                                    } 
                                                                                    ?>
                                                                                </div>
                                                                                <?php if ($key2 === 0) { ?>                                                                        
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>
                                                                                            <label class="font-weight-bold <?php echo $sclass ?>">Хэнээс</label>
                                                                                        </th>
                                                                                        <th>
                                                                                            <label class="font-weight-bold <?php echo $sclass ?>">Шилжүүлсэн огноо</label>
                                                                                        </th>
                                                                                        <th>
                                                                                            <label class="font-weight-bold <?php echo $sclass ?>">Шийдвэрлэх огноо</label>
                                                                                        </th>
                                                                                        <th>
                                                                                            <label class="font-weight-bold <?php echo $sclass ?>">Хэнд</label>
                                                                                        </th>
                                                                                        <th>
                                                                                            <label class="font-weight-bold <?php echo $sclass ?>">Төлөв</label>
                                                                                        </th>
                                                                                        <th>
                                                                                            <label class="font-weight-bold <?php echo $sclass ?>">Шийдвэрлэсэн огноо</label>
                                                                                        </th>
                                                                                        <th></th>
                                                                                        <th>
                                                                                            <label class="font-weight-bold <?php echo $sclass ?>"><?php echo Lang::line('wfm_weight'); ?></label>
                                                                                        </th>
                                                                                        <th></th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <?php } ?>
                                                                                <tbody>
                                                                                    <tr id="<?php echo $wfmLogAssig['id'] ?>" assignId="<?php echo $wfmLogAssig['id'] ?>" style="background-color: <?php echo ($wfmLogAssig['istransferred']) ? 'rgba(154, 148, 148, 0.37)' : '#FFF' ?>">
                                                                                        <td>
                                                                                            <div class="d-flex align-items-center">
                                                                                                <div class="mr-2">
                                                                                                    <img class="rounded-circle" src="api/image_thumbnail?width=40&src=<?php echo $wfmLogAssig['assignpicture']; ?>" onerror="onUserLogoError(this);" width="40" height="40">
                                                                                                </div> 
                                                                                                <div class="line-height-normal">
                                                                                                    <label class="font-weight-bold"><?php echo $wfmLogAssig['assignedemployeename']; ?></label>
                                                                                                    <div>
                                                                                                        <label class="text-muted font-size-10" title="<?php echo issetParam($wfmLogAssig['assigndepartmentname']); ?>"><?php echo issetParam($wfmLogAssig['assigndepartmentname']); ?>
                                                                                                        </label>
                                                                                                        <span title="<?php echo issetParam($wfmLogAssig['assignpositionname']); ?>">
                                                                                                            <label class="text-muted font-size-10"><?php echo issetParam($wfmLogAssig['assignpositionname']); ?>
                                                                                                            </label>
                                                                                                        </span>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>                
                                                                                        </td>
                                                                                        <td>
                                                                                            <label>
                                                                                                <?php echo Date::formatter($wfmLogAssig['assigneddate']. ' '. $wfmLogAssig['assignedtime'], 'Y.m.d H:i'); ?>
                                                                                            </label>
                                                                                        </td>
                                                                                        <td>
                                                                                            <label>
                                                                                                <?php echo $wfmLogAssig['duedate']. ' '. $wfmLogAssig['dueday']. ' '. Date::formatter($wfmLogAssig['duetime'], 'H:i'); ?>
                                                                                            </label>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="d-flex align-items-center">
                                                                                                <div class="mr-2">
                                                                                                    <img class="rounded-circle" src="api/image_thumbnail?width=40&src=<?php echo $wfmLogAssig['picture'] ?>" onerror="onUserLogoError(this);" width="40" height="40"/>
                                                                                                </div> 
                                                                                                <div class="line-height-normal">
                                                                                                    <label class="font-weight-bold"><?php echo $wfmLogAssig['employeename']; ?></label>
                                                                                                    <div>
                                                                                                        <label class="text-muted font-size-10" title="<?php echo issetParam($wfmLogAssig['departmentname']); ?>"><?php echo issetParam($wfmLogAssig['departmentname']); ?>
                                                                                                        </label>
                                                                                                        <span title="<?php echo issetParam($wfmLogAssig['positionname']); ?>">
                                                                                                            <label class="text-muted font-size-10"><?php echo issetParam($wfmLogAssig['positionname']); ?></label>
                                                                                                        </span>
                                                                                                    </div>                                                                                                
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td data-field="USER_STATUS_ID">
                                                                                            <span class="badge font-size-12" style="background-color: <?php echo $wfmLogAssig['wfmstatuscolor'] ?>">
                                                                                                <?php echo $wfmLogAssig['wfmstatusname']; ?>
                                                                                            </span>
                                                                                        </td>
                                                                                        <td data-field="USER_STATUS_DATE">
                                                                                            <label>
                                                                                                <?php echo $wfmLogAssig['userstatusdate']. ' '. $wfmLogAssig['userstatustime']. ' '. $wfmLogAssig['userstatusday']; ?>
                                                                                            </label>
                                                                                        </td>
                                                                                        <td>
                                                                                            <p><?php echo $wfmLogAssig['description']; ?></p>
                                                                                        </td>
                                                                                        <td>
                                                                                            <p><?php echo $wfmLogAssig['weight']; ?></p>
                                                                                        </td>
                                                                                        <?php
                                                                                        $isAssignCopy = (($this->userKeyId == $this->createdUserId) ? true : false);
                                                                                        ?>
                                                                                        <td<?php echo ($isAssignCopy ? ' style="width: 82px;"' : ''); ?>>
                                                                                            <?php 
                                                                                            if (!$isWfmNotAssignReturn && $isAssignCopy) {
                                                                                                echo Html::anchor(
                                                                                                    'javascript:;', '<i class="fa fa-undo"></i>', array(
                                                                                                        'class' => 'btn btn-xs btn-primary mr-1',
                                                                                                        'title' => 'Төлөв буцаах',
                                                                                                        'onclick' => 'copyAssignment_'. $this->metaDataId .'(this, \''. $wfmLogAssig['id'] .'\');',
                                                                                                    ), true 
                                                                                                ); 
                                                                                            }
                                                                                            
                                                                                            echo Html::anchor(
                                                                                                'javascript:;', '<i class="fa fa-trash"></i>', array(
                                                                                                    'class' => 'pending btn btn-xs red mr0',
                                                                                                    'title' => $this->lang->line('META_00002'),
                                                                                                    'data-key' => $pkey,
                                                                                                    'data-isneedsign' => (issetParam($wfmLog['isneedsign']) === '1') ? '1' : '0',
                                                                                                    'data-isuserdefassign' => (issetParam($wfmLog['isuserdefassign']) === '1') ? '1' : '0', 
                                                                                                    'onclick' => 'deleteAssignment_'. $this->metaDataId .'(this, \''. $wfmLogAssig['id'] .'\');',
                                                                                                ), ((!$isWfmNotAssignRemove && ($wfmLogAssig['assigneduserid'] == $this->userKeyId || $isShowAssignmentRemove)) ? true : false) /*2022-08-30 Akma helev av gej isset($wfmLog['isuserdefassign']) && $wfmLog['isuserdefassign'] == '1' && */
                                                                                            );    
                                                                                            ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            <?php 
                                                                            $assigInKey++; 
                                                                            $key2++;
                                                                            } 
                                                                            ?>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <?php } elseif ($ticket) { ?>
                                                            <li class="task-list-item done" style='padding:0px;'>
                                                                <div class="task-status"></div>
                                                                <div class="task-content pl0 pr0 wfm-header-dtl-<?php echo $this->metaDataId ?>">
                                                                    <div class="uppercase font-weight-bold wfm-header-dtltable-<?php echo $this->metaDataId ?> ">
                                                                        <table class="table mb0 " id="wfm-header-assoign-dtltable-last-<?php echo $this->metaDataId ?>"><tbody></tbody></table>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        <?php }} ?>
                                                    </ul>
                                                    <?php 
                                                    if ($ticket && isset($this->isUseAssign) && $this->isUseAssign == '1') { 
                                                    ?>
                                                    <div class="task-footer">
                                                        <div class="d-flex justify-content-center">
                                                            <div class="col-xs-12">
                                                                <?php 
                                                                echo Html::anchor(
                                                                    'javascript:;', 'Шилжүүлэх', array(
                                                                    'tabindex' => '-1',     
                                                                    'class' => 'task-add btn btn-sm btn-primary',
                                                                    'title' => 'Шилжүүлэх',
                                                                    'data-key' => $pkey,
                                                                    'data-isneedsign' => ((isset($wfmLog['isneedsign']) && $wfmLog['isneedsign'] === '1') ? '1' : '0'),
                                                                    'data-isuserdefassign' => ((isset($wfmLog['isuserdefassign']) && $wfmLog['isuserdefassign'] === '1') ? '1' : '0'),
                                                                    'onclick' => 'addMultiWfmStatusAssigmentClick(this);',
                                                                ), ((isset($wfmLog['isuserdefassign']) && $wfmLog['isuserdefassign'] === '1' && $ticket) ? true : false)
                                                                ); 
                                                                ?>
                                                                <button type="button" class="btn btn-sm green-meadow float-right wfm-log-assign-save d-none ml6" onclick="wfmLogAssignSave(this);"><i class="fa fa-save"></i> <?php echo $this->lang->line('META_00060'); ?></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </li>
                                    <?php 
                                    } elseif (isset($this->wfmStatusLog['message'])) {
                                        echo html_tag('div', array('class' => 'alert alert-info'), $this->wfmStatusLog['message'], true);
                                    } 
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php 
                } 
                echo "</div>";
                if (isset($this->wfmStatusNext) && $this->wfmStatusNext && $this->getWfmStatus['IS_HIDE_NEXT_USER'] != '1') { 
                    echo "<div class='col-md-3'>";
                ?>
                    <div class="card-body" style="border-top: none;background: white;padding-left: 8px;padding-right: 8px;border-radius: 10px;">
                        <div class="mt-element-list">
                            <div class="mt-list-container list-todo" id="accordion1" role="tablist" aria-multiselectable="true">
                                <div class="list-todo-line"></div>
                                <ul>
                                    <?php 
                                    if (isset($this->wfmStatusNext) && $this->wfmStatusNext) {
                                        $indexNext = 1;
                                        $ticket = false;                                     
                                    ?>
                                        <li class="mt-list-item">
                                            <div class="list-todo-item grey">
                                                <div class="task-list panel-collapse collapse in" id="task-<?php echo $this->metaDataId.'_'.$indexNext ?>" aria-expanded="false">
                                                    <ul>
                                                        <li class="task-list-item done">
                                                            <div class="task-content pl0 pr0 wfm-header-dtl-<?php echo $this->metaDataId ?>" style="padding:0 !important;">
                                                                <div class="font-weight-bold wfm-header-dtltable-<?php echo $this->metaDataId ?> ">
                                                                    <table class="table mb0" data-table-key="" id="wfm-header-assoign-dtltable-<?php echo $this->metaDataId ?>">
                                                                        <thead>
                                                                            <?php if ($indexNext === 1) { ?>
                                                                                <th style="border-bottom: 1px solid white;padding-bottom: 15px;text-transform: none;"><label class="font-weight-bold" style="font-size:12px;padding-top:5px"><?php echo Lang::line('wfm_next_assign_user') ?></label></th>
                                                                            <?php } ?>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php 
                                                                            $userAssignAction = 'display:none';
                                                                            foreach ($this->wfmStatusNext as $nkey => $wfmLogNext) { 
                                                                                if ($indexNext <= 5) {
                                                                                ?>
                                                                                    <tr>
                                                                                        <td style="border-top:none">
                                                                                            <div class="d-flex align-items-center">
                                                                                                <div class="mr-2">
                                                                                                    <img class="rounded-circle" style="object-fit:contain" src="api/image_thumbnail?width=40&src=<?php echo $wfmLogNext['picture']; ?>" onerror="onUserLogoError(this);" width="40" height="40" title="<?php echo $wfmLogNext['username']; ?> <?php echo (isset($wfmLogNext['departmentname']) ? '('.$wfmLogNext['departmentname'].')' : ''); ?>"/>
                                                                                                </div> 
                                                                                                <div class="line-height-normal" style="-ms-flex: 1;flex: 1;">
                                                                                                    
                                                                                                    <?php if (isset($wfmLogNext['aliasusername'])) { ?>
                                                                                                        <div style="border-bottom: 1px #eee solid;padding-bottom: 5px;margin-bottom: 5px;">
                                                                                                            Орлон ажиллах: 
                                                                                                            <label class="text-default font-weight-bold">
                                                                                                                <?php echo $wfmLogNext['aliasusername']; ?>
                                                                                                            </label>
                                                                                                        </div>
                                                                                                    <?php } ?>

                                                                                                    <div>
                                                                                                        <label class="text-default font-weight-bold"><?php echo $wfmLogNext['username']; ?></label>
                                                                                                    </div>
                                                                                                    <span title="<?php echo issetParam($wfmLogNext['departmentname']); ?>">
                                                                                                        <label class="text-muted font-size-11"><?php echo isset($wfmLogNext['departmentname']) ? ($wfmLogNext['departmentname']) ? $wfmLogNext['departmentname'].', ' : '-' : '-'; ?>
                                                                                                        </label>
                                                                                                    </span>
                                                                                                    <span title="<?php echo issetParam($wfmLogNext['positionname']); ?>">
                                                                                                        <label class="text-muted font-size-11"><?php echo isset($wfmLogNext['positionname']) ? ($wfmLogNext['positionname']) ? $wfmLogNext['positionname'] : '-' : '-'; ?>
                                                                                                        </label>
                                                                                                    </span>
                                                                                                </div>
                                                                                                
                                                                                                <?php
                                                                                                if ($nextWfmRows = issetParam($wfmLogNext['rows'])) { 
                                                                                                ?>
                                                                                                <div class="ml-3 align-self-center">
                                                                                                    <div class="list-icons">
                                                                                                        <div class="list-icons-item dropdown">
                                                                                                            <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown" title="Дараагийн төлөв"><i class="fas fa-ellipsis-v"></i></a>
                                                                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                                                                <?php
                                                                                                                foreach ($nextWfmRows as $nextWfmRow) {
                                                                                                                ?>
                                                                                                                <div class="dropdown-item" style="cursor: default;"><?php echo $nextWfmRow['wfmstatusname']; ?></div>
                                                                                                                <?php
                                                                                                                }
                                                                                                                ?>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <?php
                                                                                                }
                                                                                                ?>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                            <?php 
                                                                                }
                                                                                $indexNext++; 
                                                                            } 
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                    <?php
                                                                    if (count($this->wfmStatusNext) > 5) {
                                                                    ?>
                                                                        <div style="text-align: right;margin-top: 10px;">
                                                                            <a href="javascript:;" onclick="expandNextAssignUser(this)" style="color:#f5b300;text-transform: none;">Бүгдийг харах</a>
                                                                        </div>
                                                                    <?php } ?>                                                                    
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    <?php  
                                    } 
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php 
                    echo "</div>";
                } 
            echo "</div>";

            if (isset($this->getWfmStatus['IS_FILE_PREVIEW']) 
                && $this->getWfmStatus['IS_FILE_PREVIEW'] == '1' 
                && $this->isSee === 'false' 
                && isset($this->isFilePreview) 
                && $this->isFilePreview == '1' 
                && isset($this->dataRow['attachfile']) 
                && !empty($this->dataRow['attachfile']) 
                && file_exists($this->dataRow['attachfile'])) {
            ?>
                <div class="fileViewerGeneric" style="height: 75vh">
                    <?php
                    $path_parts = pathinfo($this->dataRow['attachfile']);
                    switch ($path_parts['extension']) {
                        case 'pdf':
                        echo '<iframe src="'.URL.'api/pdf/web/viewer.html?file='.URL.$this->dataRow['attachfile'].'" frameborder="0" style="width: 100%;height: 760px;" id="iframe-detail-'.$this->uniqid.'"></iframe>';
                            break;
                        case 'doc':
                        case 'docx':
                            echo '<iframe id="iframe-detail-'. $this->recordId ."\" src=" . URL . "mddoc/office?filename=" . $this->dataRow['attachfile'] . "&review=1&edit=1&fDownload=1&docname=" .$path_parts['filename']. "&docId=".
                                  $this->recordId .' frameborder="0" style="width: 100%; height: 100%;"></iframe>';
                            break;
                        case 'xls': 
                        case 'xlsx': 
                            echo '<iframe id="viewFileMain" src="'.CONFIG_FILE_VIEWER_ADDRESS.'SheetEdit.aspx?showRb=0&url='.URL.$this->dataRow['attachfile'].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>';
                            break;
                        default:
                            echo '<img id="viewImageMain" style="width:auto" src="'.$this->dataRow['attachfile'].'"  class="img-fluid mar-auto d-flex justify-content-center">';
                            break;
                    }
                    ?>
                </div>
            <?php 
            } 
            ?>
        </div>
    <?php 
    if ($this->isForm) { 
    ?>
    </form>
    <?php 
    } 
    ?>
    <div class="clearfix w-100"></div>
</div>

<?php if (isset($this->wfmStatusLog)) { ?>

<script type="text/javascript">   
var nextUserWfm = JSON.parse('<?php echo json_encode($this->wfmStatusNext ? $this->wfmStatusNext : '', JSON_UNESCAPED_UNICODE); ?>');
        
$(function() {
    
    $(document.body).on('click', '.assign-isedit', function() {
        var $this = $(this);
        var $row = $this.closest('tr');
        var isEdit = $this.val();
        
        if (isEdit == '2') {
            $row.find('input[name="weight[]"]').prop('readonly', false);
        } else {
            $row.find('input[name="weight[]"]').prop('readonly', true);
        }
    });
});

function addMultiWfmStatusAssigmentClick(elem) {
    var params = '', rows = getDataViewSelectedRows('<?php echo $this->metaDataId; ?>');
    
    if (rows.length && rows[0].hasOwnProperty('filterwfmdepartmentid') && rows[0]['filterwfmdepartmentid']) {
        params = 'param[filterWfmDepartmentId]=' + rows[0]['filterwfmdepartmentid'];
    }
    
    wfmUserMetaDataGrid('multi', '', 'param[wfmRuleId]=<?php echo $wfmRuleId; ?>', 'addWfmStatusAssigment', elem);
}

function addWfmStatusAssigment(metaDataCode, chooseType, elem, rows) {

    var mandatoryCriteria = $('#wfmuser-metadata-search-form'), 
        $elem = $(elem), $wfmRuleId = mandatoryCriteria.find('select[name="param[wfmRuleId]"]'), 
        $wfmWaitTime = mandatoryCriteria.find('input[name="waitTime"]'),
        $wfmWaitStatusId = mandatoryCriteria.find('select[name="waitStatusId"]'),
        selectedRule, append = true, joinRows = [], dKey = $elem.attr('data-key'), ruleHtml = '', 
        view_btn = plang.get('view_btn'), edit_btn = plang.get('edit_btn'), length = 0;

    $('#dialog-wfmusermetadata').dialog('close');

    if ($wfmRuleId.length && $wfmRuleId.val()) {
        selectedRule = {'ID': $wfmRuleId.val(), 'NAME': $wfmRuleId.find('option:selected').text()};
    }

    if (typeof $elem.attr('data-assignmentid') !== 'undefined' && $elem.attr('data-assignmentid') !== '') {
        append = false;
    }

    if (selectedRule) {

        ruleHtml = '<div class="alert alert-info" style="padding:5px; border-radius: 0; margin-bottom:0;">' + 
            selectedRule['NAME'] + '<input type="hidden" name="ruleId" value="'+ selectedRule['ID'] +'"/>' + 
        '<span class="pull-right"><a href="javascript:;" onclick="changeWfmRule(this)"><i class="fa fa-pencil"></i> '+plang.get('edit_btn')+'</a></span></div>';
    }

    ruleHtml += '<input type="hidden" name="waitTime" value="'+ $wfmWaitTime.val() +'"/>';
    ruleHtml += '<input type="hidden" name="waitStatusId" value="'+ $wfmWaitStatusId.val() +'"/>';

    $('.rule-'+ dKey +'_<?php echo $this->metaDataId ?>').html(ruleHtml);

    for (var i = 0; i < rows.length; i++) {

        var row = rows[i], isAddRow = true, weight = '';

        $('#tbl-userdefassignwfmstatus > tbody > tr').each(function() {
            if ($(this).attr('data-id') == row.id) {
                isAddRow = false;
            }
        });

        if (isAddRow) {
            
            if (row.hasOwnProperty('weight') && row.weight) {
                weight = row.weight;
            }

            joinRows.push('<tr data-id="'+row.id+'">');

                joinRows.push('<td>');
                
                    if (!append) {
                        joinRows.push('<input type="hidden" name="wfmAssingmentId[]" value="'+ $elem.attr('data-assignmentid') +'">');
                    }
                    
                    joinRows.push('<input type="hidden" name="assigmentUserId[]" value="'+row.id+'">');
                    joinRows.push('<div class="d-flex align-items-center">');
                        joinRows.push('<div class="mr-2">');
                            joinRows.push('<img class="rounded-circle" src="api/image_thumbnail?width=40&src='+row.picture+'" onerror="onUserLogoError(this);" width="40" height="40">');
                        joinRows.push('</div>'); 
                        joinRows.push('<div>');
                            joinRows.push('<label class="text-default font-weight-bold">'+row.userfullname+'</label>');
                            joinRows.push('<div>');
                                joinRows.push('<label class="text-muted font-size-11 mt-1">'+dvFieldValueShow(row.departmentname)+'</label>');
                                joinRows.push('<label class="text-muted font-size-11 ml-1">'+dvFieldValueShow(row.positionname)+'</label>');
                            joinRows.push('</div>');
                        joinRows.push('</div>');
                    joinRows.push('</div>');
                joinRows.push('</td>');

                joinRows.push('<td>');
                    joinRows.push('<input type="text" name="order[]" class="form-control longInit" value="'+(++length)+'">');
                joinRows.push('</td>');

                joinRows.push('<td>');

                    joinRows.push('<div class="form-check form-check-inline mr10">');
                        joinRows.push('<label class="form-check-label" title="'+view_btn+'">');
                            joinRows.push('<input type="radio" name="isEdit['+row.id+']" class="form-check-input assign-isedit" value="1">');
                            joinRows.push('<i class="icon-eye ml5 mt2"></i>');
                        joinRows.push('</label>');
                    joinRows.push('</div>');

                    joinRows.push('<div class="form-check form-check-inline">');
                        joinRows.push('<label class="form-check-label" title="'+edit_btn+'">');
                            joinRows.push('<input type="radio" name="isEdit['+row.id+']" class="form-check-input assign-isedit" value="2" checked>');
                            joinRows.push('<i class="icon-pencil6 ml5 mt2"></i>');
                        joinRows.push('</label>');
                    joinRows.push('</div>');

                joinRows.push('</td>');

                joinRows.push('<td>');
                    joinRows.push('<input type="text" name="weight[]" class="form-control bigdecimalInit" data-v-max="100" data-mdec="2" value="'+weight+'">');
                joinRows.push('</td>');

                joinRows.push('<td>');
                    joinRows.push('<textarea class="form-control" name="descriptionAssign[]" rows="2"></textarea>');
                joinRows.push('</td>');

                joinRows.push('<td>');
                    joinRows.push('<div class="dateElement input-group" style="max-width: 175px !important;">');
                    joinRows.push('<input type="text" name="dueDate[]" class="form-control form-control-sm dateInit" style="width: 52px !important;">');
                    joinRows.push('<span class="input-group-btn"><button tabindex="-1" class="btn" onclick="return false;"><i class="fa fa-calendar"></i></button></span>');
                    joinRows.push('<input type="text" name="dueTime[]" class="form-control form-control-sm ml10 timeInit" value="00:00">');
                    joinRows.push('</div>');
                joinRows.push('</td>');

                joinRows.push('<td class="pr0">');
                joinRows.push('<button type="button" class="btn btn-xs red mr0" onclick="removeWfmStatusAssigment(this);"><i class="fa fa-trash"></i></button>');
                joinRows.push('</td>');

            joinRows.push('</tr>');
        }
    }

    var $parent = $elem.closest('.task-footer');

    if ($parent.length) {

        var $tableResp = $parent.prev('.table-responsive');

        if ($tableResp.length) {

            var $assignTbl = $tableResp.find('#tbl-userdefassignwfmstatus > tbody');
            $assignTbl.append(joinRows.join(''));

        } else {

            var assignmentTbl = '<div class="table-responsive">'+
                '<table class="table table-hover" id="tbl-userdefassignwfmstatus">'+
                    '<thead>'+
                        '<tr>'+
                            '<th class="font-weight-bold" style="width: 300px;">Хэрэглэгч</th>'+
                            '<th class="font-weight-bold" style="width: 75px;">Дараалал</th>'+
                            '<th class="font-weight-bold text-center" style="width: 110px;">Харах / Засах</th>'+
                            '<th class="font-weight-bold" style="width: 90px;">Батлах хувь</th>'+
                            '<th class="font-weight-bold" style="min-width: 200px;">Тайлбар</th>'+
                            '<th class="font-weight-bold" style="width: 190px;">Огноо</th>'+
                            '<th style="width: 46px;"></th>'+
                        '</tr>'+
                    '</thead>'+
                    '<tbody>'+joinRows.join('')+'</tbody>'+
                '</table>'+
            '</div>';

            $parent.before(assignmentTbl);

            var $assignTbl = $parent.prev('.table-responsive').find('#tbl-userdefassignwfmstatus > tbody');
        }

        Core.initNumberInput($assignTbl);
        Core.initDateInput($assignTbl);
        Core.initTimeInput($assignTbl);
        Core.initUniform($assignTbl);

        $('.wfm-log-assign-save').removeClass('d-none');
    }
    
    var $statusDialog = $elem.closest('.ui-dialog-content');
    $statusDialog.dialog('option', 'position', {my: 'center', at: 'center', of: window});
}

function removeWfmStatusAssigment(elem) {
    $(elem).closest('tr').remove();
}

function deleteAssigmentUserId(elem) {
    $(elem).closest('tr').remove();
    var el = $("#wfm-status-assigment-tbl > tbody > tr");
    var len = el.length, i = 0;
    for (i; i < len; i++) { 
        $(el[i]).find("td:first").text((i + 1) + '.');
    }
}

function deleteAssignment_<?php echo $this->metaDataId ?>(element, assigmentId) {
    var $dialogName = 'dialog-wfm-assign-confirm';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    $dialog.empty().append('Та устгахдаа итгэлтэй байна уу?');
    $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: 'Confirm',
        width: 360,
        height: 'auto',
        modal: true,
        close: function() {
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [{
            text: plang.get('yes_btn'),
            class: 'btn green-meadow btn-sm',
            click: function() {   
                $.ajax({
                    type: 'post',                
                    url: 'mdworkflow/deleteAssignment',
                    dataType: 'json',
                    data: {assigmentId: assigmentId, metaDataId: '<?php echo $this->metaDataId ?>'},
                    beforeSend:function() {
                        Core.blockUI({animate: true});
                    },
                    success: function (data) {

                        if (data.status == 'success') {
                            $(element).remove();
                            $('tr[id="'+ assigmentId +'"]').remove();
                        }

                        Core.unblockUI();
                    }
                });
                $dialog.dialog('close');
            }
        },{
            text: plang.get('no_btn'),
            class: 'btn blue-madison btn-sm',
            click: function() {
                $dialog.dialog('close');
            }
        }]
    });

    $dialog.dialog('open');            
}
function copyAssignment_<?php echo $this->metaDataId ?>(element, assigmentId) {
    $.ajax({
        type: 'post',
        url: 'mdworkflow/copyAssignment', 
        data: {assigmentId: assigmentId}, 
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({boxed : true, message: 'Loading...'});
        },
        success: function(data) {
            PNotify.removeAll();
            new PNotify({
                title: data.status,
                text: data.message,
                type: data.status,
                sticker: false, 
                addclass: 'pnotify-center'
            });
            
            if (data.status == 'success') {
                var $this = $(element), $row = $this.closest('tr');
                $row.find('td[data-field="USER_STATUS_ID"], td[data-field="USER_STATUS_DATE"]').empty();
                $this.remove();
            }
            Core.unblockUI();
        }
    });
}

function sendMailBySelectionUser_<?php echo $this->metaDataId ?>(element, wfmlogId) {
    
    var postData = {wfmlogId: wfmlogId, metaDataId: '<?php echo $this->metaDataId ?>', selectedRow: '<?php echo isset($this->dataRow) ? Arr::encode($this->dataRow) : ''; ?>'};
    
    if (nextUserWfm && Object.keys(nextUserWfm).length > 0) {
        var nextUserIds = [];
        for (var n in nextUserWfm) {
            nextUserIds.push(nextUserWfm[n]['userid']);
        }
        postData.nextUserIds = nextUserIds;
    }
    
    $.ajax({
        type: 'post',
        url: 'mddatamodel/sendMailBySelectionUser',
        dataType: 'json',
        data: postData, 
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            PNotify.removeAll();
            new PNotify({
                title: data.status,
                text: data.message,
                type: data.status,
                sticker: false
            });
            Core.unblockUI();
        }
    });
} 
function wmfDrillFromDataview_<?php echo $this->metaDataId ?>(elem, logId) {
    
    var $this = $(elem);
    var $row = $this.closest('tr');
    var $nextRow = $row.next('tr[data-assignments]');
    
    if ($nextRow.length) {
        
        if ($nextRow.hasClass('d-none')) {
            $nextRow.removeClass('d-none');
        } else {
            $nextRow.addClass('d-none');
        }
        
    } else {
        
        var assignments = JSON.parse($this.next('script[data-template="assignments-json"]').text());
        var htmlTbl = [];
        
        htmlTbl.push('<tr data-assignments="1">');
            htmlTbl.push('<td colspan="7">');
                htmlTbl.push('<table class="table mb0 border-1">');
                    htmlTbl.push('<thead>');
                        htmlTbl.push('<tr>');
                            htmlTbl.push('<th style="width: 300px;"><label class="font-weight-bold">Хэнээс</label></th>');
                            htmlTbl.push('<th><label class="font-weight-bold">Шилжүүлсэн огноо</label></th>');
                            htmlTbl.push('<th><label class="font-weight-bold">Шийдвэрлэх огноо</label></th>');
                            htmlTbl.push('<th style="width: 300px;"><label class="font-weight-bold">Хэнд</label></th>');
                            htmlTbl.push('<th><label class="font-weight-bold">Төлөв</label></th>');
                            htmlTbl.push('<th><label class="font-weight-bold">Шийдвэрлэсэн огноо</label></th>');
                            htmlTbl.push('<th><label class="font-weight-bold">Тайлбар</label></th>');
                            htmlTbl.push('<th><label class="font-weight-bold">'+plang.get('wfm_weight')+'</label></th>');
                        htmlTbl.push('</tr>');
                    htmlTbl.push('</thead>');
                    htmlTbl.push('<tbody>');
                    
                    for (var k in assignments) {
                        
                        htmlTbl.push('<tr>');
                        
                            htmlTbl.push('<td>');
                            htmlTbl.push('<div class="d-flex align-items-center">');
                                htmlTbl.push('<div class="mr-2">');
                                    htmlTbl.push('<img class="rounded-circle" src="api/image_thumbnail?width=40&src='+assignments[k]['assignpicture']+'" onerror="onUserLogoError(this);" width="40" height="40">');
                                htmlTbl.push('</div>');
                                htmlTbl.push('<div class="line-height-normal">');
                                    htmlTbl.push('<label class="font-weight-bold">'+assignments[k]['assignedemployeename']+'</label>');
                                    htmlTbl.push('<div>');
                                        htmlTbl.push('<label class="text-muted font-size-10" title="'+assignments[k]['assigndepartmentname']+'">'+assignments[k]['assigndepartmentname']+'</label>');
                                        htmlTbl.push('<span title="'+assignments[k]['assignpositionname']+'"><label class="text-muted font-size-10">'+assignments[k]['assignpositionname']+'</label></span>');
                                    htmlTbl.push('</div>');
                                htmlTbl.push('</div>');
                            htmlTbl.push('</div>');
                            htmlTbl.push('</td>');
                            
                            htmlTbl.push('<td><label>'+date('Y.m.d H:i', strtotime(assignments[k]['assigneddate']+' '+assignments[k]['assignedtime']))+'</label></td>');
                            htmlTbl.push('<td><label>'+dvFieldValueShow(assignments[k]['duedate'])+' '+dvFieldValueShow(assignments[k]['dueday'])+' '+((assignments[k]['duetime'] == '' || assignments[k]['duetime'] == null) ? '' : date('H:i', strtotime(assignments[k]['duetime'])))+'</label></td>');
                            htmlTbl.push('<td>');
                            
                            htmlTbl.push('<div class="d-flex align-items-center">');
                                htmlTbl.push('<div class="mr-2">');
                                    htmlTbl.push('<img class="rounded-circle" src="api/image_thumbnail?width=40&src='+assignments[k]['picture']+'" onerror="onUserLogoError(this);" width="40" height="40"/>');
                                htmlTbl.push('</div>');
                                htmlTbl.push('<div class="line-height-normal">');
                                    htmlTbl.push('<label class="font-weight-bold">'+assignments[k]['employeename']+'</label>');
                                    htmlTbl.push('<div>');
                                        htmlTbl.push('<label class="text-muted font-size-10" title="'+assignments[k]['departmentname']+'">'+assignments[k]['departmentname']+'</label>');
                                        htmlTbl.push('<span title="'+assignments[k]['positionname']+'">');
                                            htmlTbl.push('<label class="text-muted font-size-10">'+assignments[k]['positionname']+'</label>');
                                        htmlTbl.push('</span>');
                                    htmlTbl.push('</div>');       
                                htmlTbl.push('</div>');
                            htmlTbl.push('</div>');
        
                            htmlTbl.push('</td>');
                            htmlTbl.push('<td>');
                            htmlTbl.push('<span class="badge font-size-12" style="background-color: '+assignments[k]['wfmstatuscolor']+'">'+assignments[k]['wfmstatusname']+'</span>');
                            htmlTbl.push('</td>');
                            htmlTbl.push('<td><label>'+dvFieldValueShow(assignments[k]['userstatusdate'])+' '+dvFieldValueShow(assignments[k]['userstatustime'])+' '+dvFieldValueShow(assignments[k]['userstatusday'])+'</label></td>');
                            htmlTbl.push('<td><p>'+dvFieldValueShow(assignments[k]['description'])+'</p></td>');
                            htmlTbl.push('<td><p>'+dvFieldValueShow(assignments[k]['weight'])+'</p></td>');
                        htmlTbl.push('</tr>');
                    }
                    
                    htmlTbl.push('</tbody>');
                htmlTbl.push('</table>');
            htmlTbl.push('</td>');
        htmlTbl.push('</tr>');
        
        $row.after(htmlTbl.join(''));
    }
    
    var $statusDialog = $this.closest('.ui-dialog-content');
    $statusDialog.dialog('option', 'position', {my: 'center', at: 'center', of: window});
}

function wfmLogAssignSave(elem) {
    var $thisForm = $(elem).closest('.wfm-log-form-save'), 
        $wfmLogWrap = $(elem).closest('.mt-list-item'), 
        wfmLogId = typeof $wfmLogWrap.attr('data-wfm-log-id') !== 'undefined' ? $wfmLogWrap.attr('data-wfm-log-id') : '';

    $.ajax({
        type: 'post',
        url: 'mdobject/setRowWfmStatus', 
        data: $thisForm.find('input, textarea, select').serialize() + '&wfmLogId=' + wfmLogId, 
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({boxed : true, message: 'Loading...'});  
        },
        success: function (data) {
            PNotify.removeAll();
            new PNotify({
                title: data.status,
                text: data.message,
                type: data.status,
                sticker: false
            });

            Core.unblockUI();
        },
        error: function () { alert("Error"); }
    });

    var $dialog = $('#' + $(elem).closest('.ui-dialog-content').attr('id'));
    $dialog.empty().dialog('destroy').remove();
}        

<?php if (isset($this->serializeData) && !empty($this->serializeData)) { ?>
var serializeData = <?php echo json_encode($this->serializeData); ?>, row, isNeedSign = '0';

for (var i = 0; i < serializeData.length; i++) {
    row = JSON.parse(decodeURIComponent(serializeData[i]));

    var appendHtml = '<tr style="background-color: rgba(255, 106, 0, 0.16);">' 
        + '<td class="middle" style="width: 150px; border: 0 !important">'
            + '<div class="col-md-4 pl5 pr0" style="max-width: 45px !important">'
                + '<img src="<?php echo URL ?>'+ row.picture +'" onerror="onUserLogoError(this);" height="53"/>'
            + '</div> '
            + '<div class="col-md-8 pl0 pr0">'
                + '<div class="word-wrap-overflow">'
                    + '<label>'+ row.employeename+ '</label>'
                    + '<input type="hidden" name="assigmentUserId[]" value="'+row.id+'">'
                + '</div>'
            + '</div>'
            + '<div class="col-md-8 pl0 pr0"><div class="word-wrap-overflow" title="'+ row.departmentname +'"><label>'+ row.departmentname +'</label></div></div>'
            + '<div class="col-md-8 pl0 pr0"><div class="word-wrap-overflow" title="'+ row.positionname +'"><label>'+ row.positionname +'</label></div></div>'
        + '</td>'                
        + '<td class="middle" style="width: 10px; border: 0 !important; width:80px !important;">'
            + '<input type="text" name="order[]" class="form-control form-control-sm" style="width:30px; font-size: 12px; float:left" class="order" value="0">'
        + '</td>'
        + '<td class="text-left middle" style="width: 200px; border: 0 !important" title="Төлөв өөрчлөх огноо">'
            + '<input type="text" name="dueDate[]" style="width:80px; font-size: 12px; float:left" class="form-control dateInit form-control-sm">'
            + '<input type="text" name="dueTime[]" style="width:50px; font-size: 12px; float:left" class="form-control timeInit form-control-sm" value="00:00">'
        + '</td>'
        + '<td class="text-left middle" style="width: 30px; border: 0 !important" title="LOCK">'
            + '<input type="hidden" name="lock[]" value="0" class="'+ row.id +'">';

    appendHtml += '</td>'
        + '<td class="text-left middle" style="width: 90px; border: 0 !important"></td>'
        + '<td class="text-left middle" style="width: 30px; border: 0 !important">'
            + '<a href="javascript:;" onclick="deleteAssigmentUserId(this);" class="btn red btn-xs"><i class="fa fa-trash"></i></a>'
        + '</td>'
    + '</tr>';

    $('#wfm-header-assoign-dtltable-last-<?php echo $this->metaDataId ?> > tbody').append(appendHtml);
    $('.wfm-log-assign-save').removeClass('d-none');        
}    
<?php } ?>
</script>    

<style type="text/css">
    .mt-element-list .list-todo.mt-list-container {
        border-left: none;
        border-right: none;
        border-color: #e7ecf1;
        position: relative; }
    .mt-element-list .list-todo.mt-list-container ul {
        margin-bottom: 0;
        padding: 0;
        position: relative;
        z-index: 5; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item {
        list-style: none;
        border-bottom: 1px solid;
        border-bottom-style: solid;
        border-color: #e7ecf1;
        padding: 10px 4px 10px 0px;
        border-color: #fff;
        position: relative; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item:last-child {
        border: none; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-icon {
        display: inline-block;
        margin-top: 0.7em;
        padding: 0.7em 0;
        vertical-align: top; 
        width: 18px;
        text-align: right;
    }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item > .list-toggle-container:hover, .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item > .list-toggle-container:focus, .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item > .list-toggle-container:active {
        text-decoration: none; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item > .list-toggle-container .list-toggle {
        padding: 0px; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item > .list-toggle-container .list-toggle > .list-toggle-title {
        display: inline-block; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item .task-list {
        border: none;
        border-color: #e7ecf1;
        padding: 0;
        margin: 0;
        position: relative;
        border-top: none;
        border-bottom: none; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item .task-list .task-list-item {
        list-style: none;
        padding: 0px;
        border-bottom: 1px solid;
        border-color: #e7ecf1; }
    /*.mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item .task-list .task-list-item a {
        color: #2f353b; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item .task-list .task-list-item a:hover {
        text-decoration: none;
        color: #e43a45; }*/
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item .task-list .task-list-item:last-child {
        border-bottom: none; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item .task-list .task-list-item > .task-icon {
        float: left; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item .task-list .task-list-item > .task-content {
        padding: 0 45px 0 35px; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item .task-list .task-list-item > .task-content > h4 {
        margin-top: 0;
        font-size: 14px; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item .task-list .task-list-item > .task-content > p {
        font-size: 12px;
        margin: 0; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item .task-list .task-list-item > .task-status {
        float: right; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item .task-list .task-list-item > .task-status a {
        color: #e5e5e5; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item .task-list .task-list-item > .task-status .done:hover {
        color: #26C281; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item .task-list .task-list-item > .task-status .pending:hover {
        color: #e43a45; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item .task-list .task-list-item.done > .task-status .done {
        color: #26C281; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item .task-list .task-footer {
        padding: 15px;
        padding-bottom: 0;
        text-align: center; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item .task-list .task-footer a {
        color: #fff; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item .task-list .task-footer a:hover {
        text-decoration: none; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item .task-list .task-footer a.task-trash:hover {
        color: #e43a45; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-todo-item .task-list .task-footer a.task-add:hover {
        color: #ececec; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-icon-container {
        font-size: 20px;
        position: absolute;
        right: 5px;
        top: 50%;
        margin-top: -10px; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-icon-container a {
        color: #2f353b; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-icon-container a:hover {
        color: #32c5d2; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-item-content {
        padding: 0 25px 0 0; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-item-content > h3 {
        margin: 0;
        font-size: 18px;
        margin-bottom: 10px; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-item-content > h3 > a {
        color: #34495e; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-item-content > h3 > a:hover {
        color: #32c5d2;
        text-decoration: none; }
    .mt-element-list .list-todo.mt-list-container ul > .mt-list-item > .list-datetime {
        margin-bottom: 10px; }
    .mt-element-list .list-todo .list-todo-item.grey .list-toggle {
  background: #fff;
  color: #333333 !important; }
  .mt-element-list .list-todo .list-todo-item.grey .list-toggle > .badge {
    color: #E5E5E5;
    background: #333333; }               
</style>
<?php } ?>

<script type="text/javascript">
                
    $(function () {
        $('.wfm-header-table-1525923067784').on('click', '.dropdown-toggle', function (event) {
            var self = $(this);
            var selfHeight = $(this).parent().height();
            var selfWidth = $(this).parent().width();
            var selfOffset = $(self).offset();
            var selfOffsetRigth = $(document).width() - selfOffset.left - selfWidth;
            var dropDown = self.parent().find('ul');
            $(dropDown).css({position: 'fixed', top: selfOffset.top + selfHeight - 6, left: 'auto', right: selfOffsetRigth, overflow: 'hidden'});
        });
    });
    
    function workflowCamera_<?php echo $this->metaDataId; ?>(elem) {
        $.getScript(URL_APP+"assets/custom/addon/plugins/swfobject/swfobject.js").done(function() {
            $.getScript(URL_APP+'assets/custom/addon/plugins/webcam/scriptcam/scriptcam.js').done(function() {

                var dialogName = '#dialog-person-photo-webcam';
                if (!$(dialogName).length) {
                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                }

                $.ajax({
                    type: 'post',
                    url: 'mdprocess/bpTmpAddPhotoFromWebcam',
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function(data) {
                        $(dialogName).empty().append(data.html);
                        $(dialogName).dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: data.title,
                            width: 800,
                            height: 550,
                            modal: true, 
                            close: function () { 
                                $(dialogName).empty().dialog('destroy').remove(); 
                            },
                            buttons: [
                                {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {

                                    var savedImg = $('form#bpWebcam-form').find("input[name='base64Photo']").val();
                                    $(elem).closest('.fileSidebarRows').find('span[data-path="physicalPath"]').empty().append(savedImg);
                                    $(elem).closest('.fileSidebarRows').find('input[data-path="NTR_SERVICE_CONTENT_DV.NTR_CONTENT_DV.physicalPath"]').val(savedImg);

                                    $('.person-photo-wrap').empty().append(img).promise().done(function() {
                                        Core.initFancybox($('.person-photo-wrap'));
                                    });

                                    $(dialogName).dialog('close');
                                }},
                                {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                                    $(dialogName).dialog('close');
                                }}
                            ]
                        });
                        $(dialogName).dialog('open');    

                        Core.unblockUI();
                    },
                    error: function() {
                        alert("Error");
                    }
                });
            });
        });
    }        

    function addFileWorkflow_<?php echo $this->metaDataId; ?>(elem) {
        var getDiv = $(elem).parent().parent();
        $(getDiv).append(
                '<div class="mt5 fileSidebarRows col-md-12 p-0">' +
                    '<span class="btn btn-xs bg-slate fileinput-button mb5">' +
                        '<span>'+plang.get('select_file_btn')+'</span>' + 
                        '<input type="file" onchange="changeWorkflowContentDvName(this)" name="workflowFiles[]" style="width:100%">' + 
                    '</span>' +
                    '<span data-path="physicalPath" class="float-left ml5" style="margin-left: 2px;"></span>' + 
                    '<a href="javascript:;" class="btn btn-xs btn-danger float-left ml5" title="<?php echo $this->lang->line('META_00002'); ?>" onclick="removeWorkflowFile(this);"><i class="icon-cross2 font-size-12"></i></a>' +
                '</div>'+
                '<div class="clearfix"></div>'
        );
    }

    function removeWorkflowFile(element) {
        $(element).parent().remove();
    }    

    function changeWorkflowContentDvName (element) {
        if (!$(element).hasExtension(["png", "gif", "jpeg", "pjpeg", "jpg", "x-png", "bmp", "doc", "docx", "xls", "xlsx", "pdf", "ppt", "pptx"])) {
            alert(plang.get('valid_file_info')+' '+plang.get('tm_please_select'));
            $(element).val('');
            return;
        }
        $(element).closest('.fileSidebarRows').find('span[data-path="physicalPath"]').empty().append($(element)[0].files[0].name);
    }
    
    $(document.body).on('keydown', 'form#changeWfmStatusForm_<?php echo $this->metaDataId ?> textarea', 'Ctrl+s', function(e) {
        
        if ($('body').find('button.wfmstatus-btn-save').length > 0 && $('body').find('button.wfmstatus-btn-save').is(':visible')) {
            var $buttonElement = $('body').find('button.wfmstatus-btn-save:visible:last');
            if (!$buttonElement.is(':disabled')) {
                $buttonElement.click();
            }
        }
        
        e.preventDefault();
        return false;
    });

    function wfmUserMetaDataGrid(chooseType, elem, params, funcName, _this) {
        var funcName = typeof funcName === 'undefined' ? 'selectableCommonMetaDataGrid' : funcName;
        var _this = typeof _this === 'undefined' ? '' : _this;
        var $dialogName = 'dialog-wfmusermetadata';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $("#" + $dialogName);
        var rows = getDataViewSelectedRows('<?php echo $this->metaDataId; ?>');

        $.ajax({
            type: 'post',
            url: 'mdmetadata/wfmUserDataSelectableGrid',
            data: { 
                chooseType: chooseType, 
                params: params,
                selectedRow: rows[0],
                dataViewId: '<?php echo $this->metaDataId; ?>',
                refStructureId: '<?php echo $this->refStructureId; ?>'
            },
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {
                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1100,
                    height: 'auto',
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                        if ($("div[id*=dialog-multiple-filter_wfm_]").length) {
                            $("div[id*=dialog-multiple-filter_wfm_]").each(function(){
                                $('#'+$(this).attr('id')).empty().dialog('destroy').remove();
                            });
                        }                                    
                    },
                    buttons: [{
                            text: data.addbasket_btn,
                            class: 'btn green-meadow btn-sm float-left',
                            click: function() {
                                basketWfmUserMetaDataGrid();
                            }
                        },
                        {
                            text: data.choose_btn,
                            class: 'btn blue btn-sm datagrid-common-choose-btn',
                            click: function() {
                                if (typeof(window[funcName]) === 'function') {
                                    var countBasketList = $('#wfmUserBasketMetaDataGrid').datagrid('getData').total;
                                    if (countBasketList > 0) {
                                        var rows = $('#wfmUserBasketMetaDataGrid').datagrid('getRows');
                                        window[funcName](chooseType, params, _this, rows);
                                    }                                                                
                                } else {
                                    alert('Function undefined error: ' + funcName);
                                }
                            }
                        },
                        {
                            text: data.close_btn,
                            class: 'btn blue-hoki btn-sm',
                            click: function() {
                                $dialog.dialog('close');
                            }
                        }
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            }
        }).done(function() {
            Core.initAjax($dialog);
        });
    }    

    function changeWfmRule(elem) {
        var $dialogName = 'dialog-wfmruleid-change';
        var $getRuleWrap = $(elem).parent().parent().parent();
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var rows = getDataViewSelectedRows('<?php echo $this->metaDataId; ?>');
        var $dialog = $("#" + $dialogName);

        $.ajax({
            type: 'post',
            url: 'mdmetadata/changeWfmRule',
            data: { 
                ruleId: $getRuleWrap.find('input[name="ruleId"]').val(), 
                waitTime: $getRuleWrap.find('input[name="waitTime"]').val(),
                waitTimeStatusId: $getRuleWrap.find('input[name="waitStatusId"]').val(),
                selectedRow: rows[0],
                dataViewId: '<?php echo $this->metaDataId; ?>',
                refStructureId: '<?php echo $this->refStructureId; ?>'                
            },
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {
                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 700,
                    height: 'auto',
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('close');
                    },
                    buttons: [{
                            text: data.save_btn,
                            class: 'btn green-meadow btn-sm',
                            click: function() {                                
                                $getRuleWrap.find('input[name="ruleId"]').val($dialog.find('select[name="param[wfmRuleId]"]').val());
                                $getRuleWrap.find('input[name="waitTime"]').val($dialog.find('input[name="waitTime"]').val());
                                $getRuleWrap.find('input[name="waitStatusId"]').val($dialog.find('select[name="waitStatusId"]').val());
                                $dialog.dialog('close');
                            }
                        },
                        {
                            text: data.close_btn,
                            class: 'btn blue-hoki btn-sm',
                            click: function() {
                                $dialog.dialog('close');
                            }
                        }
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            }
        }).done(function() {
            Core.initAjax($dialog);
        });
    }
    
    function removeMeFileWfmLog(elem, id, wfmlogid) {
        var $dialogName = "dialog-wfmlog-filedelete-confirm";
        if (!$("#" + $dialogName).length) {
          $('<div id="' + $dialogName + '"></div>').appendTo("body");
        }
        var $dialog = $("#" + $dialogName);

        $dialog.empty().append('Та файл устгахдаа итгэлтэй байна уу?');
        $dialog.dialog({
          cache: false,
          resizable: false,
          bgiframe: true,
          autoOpen: false,
          title: "Санамж",
          width: 400,
          height: "auto",
          modal: true,
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: "Тийм",
              class: "btn green-meadow btn-sm",
              click: function () {
                $.ajax({
                  type: "post",
                  url: "mdwebservice/renderBpTabDeleteFile",
                  data: {
                    metaDataId: '<?php echo $this->refStructureId ?>',
                    metaValueId: wfmlogid,
                    attachId: id
                  },
                  dataType: "json",
                  beforeSend: function () {
                    Core.blockUI({ message: "Loading...", boxed: true });
                  },
                  success: function (dataSub) {
                    if ($(elem).closest('td').find('ul').length) {
                        $(elem).closest('li').remove();
                    } else {
                        $(elem).closest('td').empty();
                    }
                    new PNotify({
                      title: dataSub.status,
                      text: dataSub.message,
                      type: dataSub.status,
                      sticker: false,
                    });
                    Core.unblockUI();
                  },
                });
                $dialog.dialog("close");
              },
            },
            {
              text: "Үгүй",
              class: "btn blue-madison btn-sm",
              click: function () {
                $dialog.dialog("close");
              },
            },
          ],
        });

        $dialog.dialog("open");    
    }

    function expandNextAssignUser(elem) {
        var $dialogName = 'dialog-expand-next-user';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $("#" + $dialogName), $rowHtml = '';
        
        if (nextUserWfm) {
            $.each(nextUserWfm, function(key, row){
                
                $rowHtml += '<tr>'+
                    '<td style="border-top:none">'+
                        '<div class="d-flex align-items-center">'+
                            '<div class="mr-2">'+
                                '<img class="rounded-circle" src="api/image_thumbnail?width=40&src='+row.picture+'" onerror="onUserLogoError(this);" width="40" height="40">'+
                            '</div>'+ 
                            '<div class="line-height-normal" style="-ms-flex: 1;flex: 1;">';
                    
                    if (row.hasOwnProperty('aliasusername') && row.aliasusername) {
                        
                        $rowHtml += '<div style="border-bottom: 1px #eee solid;padding-bottom: 5px;margin-bottom: 5px;">'+
                            'Орлон ажиллах: <label class="text-default font-weight-bold">' + row.aliasusername + '</label>'+
                        '</div>';
                    }   
                    
                    $rowHtml += '<div>'+
                                    '<label class="text-default font-weight-bold">'+row.username+'</label>'+
                                '</div>'+ 
                                '<span title="'+(row.departmentname ? row.departmentname : '')+'">'+
                                    '<label class="text-muted font-size-10">'+(row.departmentname ? row.departmentname : '')+'</label>'+
                                '</span>'+
                                '<span title="'+(row.positionname ? row.positionname : '')+'">'+
                                    '<label class="text-muted font-size-10">'+(row.positionname ? row.positionname : '')+'</label>'+
                                '</span>'+
                            '</div>';
                    
                    if (row.hasOwnProperty('rows') && row.rows) {
                        var nextWfmRows = row.rows;
                        
                        $rowHtml += '<div class="ml-3 align-self-center">'+
                            '<div class="list-icons">'+
                                '<div class="list-icons-item dropdown">'+
                                    '<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown" title="Дараагийн төлөв"><i class="fas fa-ellipsis-v"></i></a>'+
                                    '<div class="dropdown-menu dropdown-menu-right">';
                        
                        for (var n in nextWfmRows) {
                            $rowHtml += '<div class="dropdown-item" style="cursor: default;">'+nextWfmRows[n]['wfmstatusname']+'</div>';
                        }
                                    
                        $rowHtml += '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
                    }
                    
                $rowHtml += '</div>'+
                    '</td>'+
                '</tr>';
            });
        }

        $dialog.empty().append('<table class="table mb0 uppercase"><tbody>'+$rowHtml+'</tbody></table>');
        $dialog.dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: 'Дараагийн төлөвт шилжүүлэх хэрэглэгч',
            width: 550,
            height: 'auto',
            maxHeight: ($(window).height() - 60),
            modal: true,
            position: {my: 'top', at: 'top+50'},
            open: function () {
                $dialog.find('table tr').show();
            },             
            close: function() {
                $dialog.empty().dialog('close');
            },
            buttons: [
                {
                    text: plang.get('close_btn'),
                    class: 'btn blue-hoki btn-sm',
                    click: function() {
                        $dialog.dialog('close');
                    }
                }
            ]
        });
        $dialog.dialog('open');       
    }
</script>

<style type="text/css">
.fileinput-button input {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    opacity: 0;
    -ms-filter: 'alpha(opacity=0)';
    font-size: 200px;
    direction: ltr;
    cursor: pointer;
}
.fileinput-button {
    float: left;
    position: relative;
    overflow: hidden;
}
@media screen\9 {
    .fileinput-button input {
        filter: alpha(opacity=0);
        font-size: 100%;
        height: 100%;
    }
}
</style>