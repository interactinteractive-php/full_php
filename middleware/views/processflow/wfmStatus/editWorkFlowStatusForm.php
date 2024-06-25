<div class="row">
<div class="col-md-12">
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'updateWfmStatus-from', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
    <span style="display:block;height:22px;color:#FF5722;font-weight:700;">Ерөнхий</span>
    <div class="panel panel-default bg-inverse" style="margin-bottom: 10px !important">
        <table class="table sheetTable" style="table-layout: fixed">
            <tbody>
                <tr>
                    <td style="width: 140px; height: 30px;" class="left-padding">Төлвийн ID:</td>
                    <td class="pl5"><?php echo $this->metaWfmStatusId; ?></td>
                </tr>   
                <tr>
                    <td style="height: 30px;" class="left-padding">Төлвийн код:</td>
                    <td>
                        <input type="text" id="wfmStatusCode" name="wfmStatusCode" placeholder="Төлвийн код" class="form-control form-control-sm" required="required" value="<?php echo $this->metaWfmStatus['WFM_STATUS_CODE'] ?>">
                    </td>
                </tr> 
                <tr>
                    <td style="height: 30px;" class="left-padding">Төлвийн нэр:</td>
                    <td>
                        <?php 
                        $wfmStatusNameAttr = array(
                            'id' => 'wfmStatusName', 
                            'name' => 'wfmStatusName', 
                            'placeholder' => 'Төлвийн нэр', 
                            'class' => 'form-control form-control-sm', 
                            'required' => 'required', 
                            'data-path' => 'wfmStatusName', 
                            'value' => $this->metaWfmStatus['WFM_STATUS_NAME'] 
                        );
                        
                        if (Lang::isUseMultiLang()) {
                            
                            if ($this->metaWfmStatus['TRANSLATION_VALUE']) {
                                $pfTranslationValArr = json_decode($this->metaWfmStatus['TRANSLATION_VALUE'], true);
                                $pfTranslationValArr = $pfTranslationValArr['value'];
                            }
                            
                            $wfmStatusNameAttr['data-c-name'] = 'WFM_STATUS_NAME';
                            $wfmStatusNameAttr['data-dl-value'] = $this->metaWfmStatus['WFM_STATUS_NAME'];
                            
                            if (Lang::getCode() != Lang::getDefaultLangCode()) {
                                
                                if (isset($pfTranslationValArr['WFM_STATUS_NAME'][Lang::getCode()])) {
                                    $wfmStatusNameAttr['value'] = $pfTranslationValArr['WFM_STATUS_NAME'][Lang::getCode()];
                                }
                            }
                            
                            echo Form::textArea(array(
                                'style' => 'display: none', 
                                'name' => 'pfTranslationValue', 
                                'value' => $this->metaWfmStatus['TRANSLATION_VALUE'], 
                                'data-path' => 'pfTranslationValue'
                            ));
                            
                            echo '<div class="input-group">
                                '.Form::text($wfmStatusNameAttr).'
                                <span class="input-group-append"><button class="btn btn-primary" type="button" onclick="bpFieldTranslate(this);" title="Орчуулга"><i class="far fa-language"></i></button></span> 
                            </div>';
                            
                        } else {
                            echo Form::text($wfmStatusNameAttr);
                        }
                        ?>
                    </td>
                </tr> 
                <tr>
                    <td style="height: 30px;" class="left-padding">Төлвийн өнгө:</td>
                    <td>
                        <div class="input-group color colorpicker-default" data-color="<?php echo $this->metaWfmStatus['WFM_STATUS_COLOR']; ?>" data-color-format="rgba">
                            <input type="text" name="wfmStatusColor" id="wfmStatusColor" class="form-control" value="<?php echo $this->metaWfmStatus['WFM_STATUS_COLOR']; ?>"  required="required">
                            <span class="input-group-btn">
                                <button class="btn default colorpicker-input-addon px-1" type="button"><i style="background-color: <?php echo $this->metaWfmStatus['WFM_STATUS_COLOR']; ?>;"></i>&nbsp;</button>
                            </span>
                        </div>
                    </td>
                </tr> 
                <tr>
                    <td style="height: 30px;" class="left-padding">
                        <a href="javascript:;" class="pf-wfm-config-toggle pf-wfm-config-expand" data-class="pf-wfm-config-general">Бусад <i class="far fa-plus-square"></i></a>
                    </td>
                    <td></td>
                </tr> 
                <tr class="pf-wfm-config-general d-none">
                    <td style="height: 30px;" class="left-padding">Дараалал:</td>
                    <td>
                        <input type="text" id="orderNum" name="orderNum" placeholder="Дараалал" class="form-control form-control-sm longInit" value="<?php echo $this->metaWfmStatus['ORDER_NUMBER'] ?>">
                    </td>
                </tr> 
                <tr class="pf-wfm-config-general d-none">
                    <td style="height: 30px;" class="left-padding"><label for="wfmIsIgnoreSorting">Is Ingore Sorting:</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'wfmIsIgnoreSorting', 'id' => 'wfmIsIgnoreSorting', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['IS_IGNORE_SORTING'])); ?>
                    </td>
                </tr>                
                <tr class="pf-wfm-config-general d-none">
                    <td style="height: 30px;" class="left-padding">Дүрс:</td>
                    <td>
                        <button type="button" class="btn btn-secondary btn-sm wfmstatus-icon" data-search-text="Хайх" data-placement="bottom" data-iconset="fontawesome5" data-cols="5" data-icon="<?php echo $this->metaWfmStatus['WFM_STATUS_ICON']; ?>" role="iconpicker"></button>
                        <?php echo Form::hidden(array('name' => 'wfmStatusIcon', 'value' => $this->metaWfmStatus['WFM_STATUS_ICON'])); ?>
                    </td>
                </tr> 
                <tr class="pf-wfm-config-general d-none">
                    <td style="height: 30px;" class="left-padding">Эхлэлийн төлөв болгох:</td>
                    <td>
                        <button type="button" class="btn btn-sm green-meadow mr0" onclick="startWfmPointer('<?php echo $this->metaWfmStatusId; ?>')"><i class="fa fa-circle-o"></i></button>
                    </td>
                </tr> 
            </tbody>    
        </table>    
    </div>        
    <span style="display:block;height:22px;color:#FF5722;font-weight:700;">Ажиллагаа</span>
    <div class="panel panel-default bg-inverse" style="margin-bottom: 10px !important">
        <table class="table sheetTable" style="table-layout: fixed">
            <tbody>
                <tr>
                    <td style="width: 140px; height: 30px;" class="left-padding">Процесс сонгох:</td>
                    <td>
                        <div class="input-group">
                            <input type="hidden" id="wfmProcessId" name="wfmProcessId" value="<?php echo $this->metaWfmStatus['PROCESS_META_DATA_ID']; ?>">
                            <input type="text" id="wfmProcessCodeName" title="<?php echo $this->metaWfmStatus['PROCESS_CODENAME'] ?>" disabled name="wfmProcessCodeName" value="<?php echo $this->metaWfmStatus['PROCESS_CODENAME']; ?>" class="form-control form-control-sm" placeholder="Процесс сонгох">                                    
                            <span class="input-group-btn">
                                <button type="button" class="btn green-meadow mr0" onclick="dataViewCustomSelectableGrid('sysMetaProcessListWFM', 'single', 'proccessSelectabledGridForWfm', '', this, '', '1', 'refstructureid=<?php echo $this->metaDataId ?>');"><i class="icon-plus3 font-size-12"></i></button>
                                <button type="button" class="btn red-meadow" title="Сонгосон процессыг цэвэрлэх" onclick="deleteWfmProcess(this);"><i class="fa fa-trash"></i></button>
                            </span>
                        </div>  
                    </td>
                </tr>  
                <tr>
                    <td style="height: 30px;" class="left-padding">Процесс сонгох /Mobile/:</td>
                    <td>
                        <div class="input-group">
                            <input type="hidden" id="wfmMobileProcessId" name="wfmMobileProcessId" value="<?php echo $this->metaWfmStatus['MOBILE_PROCESS_META_DATA_ID']; ?>">
                            <input type="text" id="wfmProcessCodeName" title="<?php echo $this->metaWfmStatus['MOBILE_PROCESS_CODENAME'] ?>" disabled name="wfmProcessCodeName" value="<?php echo $this->metaWfmStatus['MOBILE_PROCESS_CODENAME']; ?>" class="form-control form-control-sm" placeholder="Процесс сонгох /Mobile/">                                    
                            <span class="input-group-btn">
                                <button type="button" class="btn green-meadow mr0" onclick="dataViewCustomSelectableGrid('sysMetaProcessListWFM', 'single', 'proccessSelectabledGridForWfm', '', this, '', '1', 'refstructureid=<?php echo $this->metaDataId ?>');"><i class="icon-plus3 font-size-12"></i></button>
                                <button type="button" class="btn red-meadow" title="Сонгосон процессыг цэвэрлэх" onclick="deleteWfmProcess(this);"><i class="fa fa-trash"></i></button>
                            </span>
                        </div>  
                    </td>
                </tr>
                <?php
                if ($this->fromType == 'metaverse') {
                ?>
                <tr>
                    <td style="height: 30px;" class="left-padding">Metaverse процесс:</td>
                    <td>
                        <input type="text" id="indicatorId" name="indicatorId" placeholder="Metaverse процесс" class="form-control form-control-sm" value="<?php echo $this->metaWfmStatus['INDICATOR_ID']; ?>">
                    </td>
                </tr> 
                <?php
                }
                ?>
                <tr>
                    <td style="height: 30px;" class="left-padding">Процессийн нэр:</td>
                    <td>
                        <?php 
                        $wfmProcessNameAttr = array(
                            'id' => 'wfmProcessName', 
                            'name' => 'wfmProcessName', 
                            'placeholder' => 'Процессийн нэр', 
                            'class' => 'form-control form-control-sm', 
                            'required' => 'required', 
                            'data-path' => 'wfmProcessName', 
                            'value' => $this->metaWfmStatus['PROCESS_NAME'] 
                        );
                        
                        if (Lang::isUseMultiLang()) {
                            
                            $wfmProcessNameAttr['data-c-name'] = 'PROCESS_NAME';
                            $wfmProcessNameAttr['data-dl-value'] = $this->metaWfmStatus['PROCESS_NAME'];
                            
                            if (Lang::getCode() != Lang::getDefaultLangCode()) {
                                
                                if (isset($pfTranslationValArr['PROCESS_NAME'][Lang::getCode()])) {
                                    $wfmProcessNameAttr['value'] = $pfTranslationValArr['PROCESS_NAME'][Lang::getCode()];
                                }
                            }
                            
                            echo '<div class="input-group">
                                '.Form::text($wfmProcessNameAttr).'
                                <span class="input-group-append"><button class="btn btn-primary" type="button" onclick="bpFieldTranslate(this);" title="Орчуулга"><i class="far fa-language"></i></button></span> 
                            </div>';
                            
                        } else {
                            echo Form::text($wfmProcessNameAttr);
                        }
                        ?>
                    </td>
                </tr> 
                <tr>
                    <td style="height: 30px;" class="left-padding"><?php echo $this->lang->line('wfm_alias_name') ?>:</td>
                    <td>
                        <?php 
                        $statusAliasNameAttr = array(
                            'id' => 'statusAliasName', 
                            'name' => 'statusAliasName', 
                            'placeholder' => $this->lang->line('wfm_alias_name'), 
                            'class' => 'form-control form-control-sm', 
                            'required' => 'required', 
                            'data-path' => 'statusAliasName', 
                            'value' => $this->metaWfmStatus['ALIAS_NAME'] 
                        );
                        
                        if (Lang::isUseMultiLang()) {
                            
                            $statusAliasNameAttr['data-c-name'] = 'ALIAS_NAME';
                            $statusAliasNameAttr['data-dl-value'] = $this->metaWfmStatus['ALIAS_NAME'];
                            
                            if (Lang::getCode() != Lang::getDefaultLangCode()) {
                                
                                if (isset($pfTranslationValArr['ALIAS_NAME'][Lang::getCode()])) {
                                    $statusAliasNameAttr['value'] = $pfTranslationValArr['ALIAS_NAME'][Lang::getCode()];
                                }
                            }
                            
                            echo '<div class="input-group">
                                '.Form::text($statusAliasNameAttr).'
                                <span class="input-group-append"><button class="btn btn-primary" type="button" onclick="bpFieldTranslate(this);" title="Орчуулга"><i class="far fa-language"></i></button></span> 
                            </div>';
                            
                        } else {
                            echo Form::text($statusAliasNameAttr);
                        }
                        ?>
                    </td>
                </tr> 
                <tr>
                    <td style="height: 30px;" class="left-padding">
                        <a href="javascript:;" class="pf-wfm-config-toggle pf-wfm-config-expand" data-class="pf-wfm-config-process">Бусад <i class="far fa-plus-square"></i></a>
                    </td>
                    <td></td>
                </tr> 
                <tr class="pf-wfm-config-process d-none">
                    <td style="height: 30px;" class="left-padding"><label for="wfmIsSign">Гарын үсэгтэй эсэх:</label></td>
                    <td>
                        <?php 
                        $signChooseData = array(
                            array(
                                'id' => '0',
                                'name' => '- '.Lang::line('select_btn').' -'
                            ),
                            array(
                                'id' => '1',
                                'name' => 'Desktop Client'
                            ),
                            array(
                                'id' => '2',
                                'name' => 'Monpass Client Position'
                            ),
                            array(
                                'id' => '3',
                                'name' => 'Cloud'
                            ),
                            array(
                                'id' => '4',
                                'name' => 'Pin code'
                            ), 
                            array(
                                'id' => '5',
                                'name' => 'Digital signature'
                            ), 
                            array(
                                'id' => '6',
                                'name' => 'OTP'
                            ), 
                            array(
                                'id' => '7',
                                'name' => 'Pdf watermark'
                            ),
                        );
                        echo Form::select(array('name' => 'wfmIsSign', 'text' => 'notext', 'id' => 'wfmIsSign', 'data' => $signChooseData, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control', 'value' => $this->metaWfmStatus['IS_NEED_SIGN'])) 
                        ?>
                    </td>
                </tr> 
                <tr class="pf-wfm-config-process d-none">
                    <td style="height: 30px;" class="left-padding"><label for="isDescRequired">Тайлбар заавал оруулах эсэх:</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'isDescRequired', 'id' => 'isDescRequired', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['IS_DESC_REQUIRED'])); ?>
                    </td>
                </tr> 
                <tr class="pf-wfm-config-process d-none">
                    <td style="height: 30px;" class="left-padding"><label for="usedescriptionWindow"><?php echo $this->lang->line('wfm_use_description_window') ?>:</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'usedescriptionWindow', 'id' => 'usedescriptionWindow', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['USE_DESCRIPTION_WINDOW'])); ?>
                    </td>
                </tr> 
                <tr class="pf-wfm-config-process d-none">
                    <td style="height: 30px;" class="left-padding"><label for="useprocessWindow"><?php echo $this->lang->line('wfm_use_process_window') ?>:</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'useprocessWindow', 'id' => 'useprocessWindow', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['USE_PROCESS_WINDOW'])); ?>
                    </td>
                </tr>
                <tr class="pf-wfm-config-process d-none">
                    <td style="height: 30px;" class="left-padding"><label for="useprocessFormSubmit">Form submit хийхгүй:</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'useprocessFormSubmit', 'id' => 'useprocessFormSubmit', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['IS_FORM_NOTSUBMIT'])); ?>
                    </td>
                </tr>
                <tr class="pf-wfm-config-process d-none">
                    <td style="height: 30px;" class="left-padding"><label for="filterUsersByDepartment">Department-р шүүх эсэх (ntf ба дараагийн төлөвт шилжүүлэх user-үүдийг):</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'filterUsersByDepartment', 'id' => 'filterUsersByDepartment', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['IS_FILTER_USERS_BY_DEPARTMENT'])); ?>
                    </td>
                </tr>
                <tr class="pf-wfm-config-process d-none">
                    <td style="height: 30px;" class="left-padding"><label for="wfmIsHideNextUser">Дараагын хэрэглэгч харуулахгүй эсэх:</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'wfmIsHideNextUser', 'id' => 'wfmIsHideNextUser', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['IS_HIDE_NEXT_USER'])); ?>
                    </td>
                </tr>
                <tr class="pf-wfm-config-process d-none">
                    <td style="height: 30px;" class="left-padding"><label for="wfmIsIgnoreRow">Is Ingore Row:</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'wfmIsIgnoreRow', 'id' => 'wfmIsIgnoreRow', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['IS_IGNORE_ROW'])); ?>
                    </td>
                </tr>
                <tr class="pf-wfm-config-process d-none">
                    <td style="height: 30px;" class="left-padding"><label for="wfmIsCheckAssignCriteria">Is Check Assign Criteria:</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'wfmIsCheckAssignCriteria', 'id' => 'wfmIsCheckAssignCriteria', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['IS_CHECK_ASSIGN_CRITERIA'])); ?>
                    </td>
                </tr>
                <tr class="pf-wfm-config-process d-none">
                    <td style="height: 30px;" class="left-padding"><label for="wfmIsFilterLog">Is Filter Log:</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'wfmIsFilterLog', 'id' => 'wfmIsFilterLog', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['IS_FILTER_LOG'])); ?>
                    </td>
                </tr>
                <tr class="pf-wfm-config-process d-none">
                    <td style="height: 30px;" class="left-padding"><label for="wfmIsDirect">Is Direct:</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'wfmIsDirect', 'id' => 'wfmIsDirect', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['IS_DIRECT'])); ?>
                    </td>
                </tr>
                <tr class="pf-wfm-config-process d-none">
                    <td style="height: 30px;" class="left-padding"><label for="wfmIsHideFile">Файл сонгохгүй эсэх:</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'wfmIsHideFile', 'id' => 'wfmIsHideFile', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['IS_HIDE_FILE'])); ?>
                    </td>
                </tr>
                <tr class="pf-wfm-config-process d-none">
                    <td style="height: 30px;" class="left-padding"><label for="wfmIsFilePreview">Файл харуулах эсэх:</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'wfmIsFilePreview', 'id' => 'wfmIsFilePreview', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['IS_FILE_PREVIEW'])); ?>
                    </td>
                </tr>
                <tr class="pf-wfm-config-process d-none">
                    <td style="height: 30px;" class="left-padding"><label for="wfmIsNotConfirm"><?php echo $this->lang->line('wfm_not_confirm'); ?>:</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'wfmIsNotConfirm', 'id' => 'wfmIsNotConfirm', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['IS_NOT_CONFIRM'])); ?>
                    </td>
                </tr>
                <tr class="pf-wfm-config-process d-none">
                    <td style="height: 30px;" class="left-padding"><label for="wfmIsIgnoreMultirowRunBp"><?php echo $this->lang->line('wfm_isignoremultirowrunbp'); ?>:</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'wfmIsIgnoreMultirowRunBp', 'id' => 'wfmIsIgnoreMultirowRunBp', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['IS_IGNORE_MULTIROW_RUN_BP'])); ?>
                    </td>
                </tr>
                <tr class="pf-wfm-config-process d-none">
                    <td style="width: 140px; height: 30px;" class="left-padding">
                        <label for="transitionTime">Үргэлжлэх хугацаа:</label>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-md-6">
                                <?php 
                                echo Form::select(array(
                                    'name' => 'timeTypeId', 
                                    'id' => 'timeTypeId', 
                                    'class' => 'form-control', 
                                    'data' => Info::getRefTimeTypeList(), 
                                    'op_value' => 'TIME_TYPE_ID', 
                                    'op_text' => 'TIME_TYPE_NAME', 
                                    'value' => $this->metaWfmStatus['TIME_TYPE_ID']
                                )); 
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                echo Form::text(array(
                                    'name' => 'transitionTime', 
                                    'id' => 'transitionTime', 
                                    'class' => 'form-control integerInit', 
                                    'style' => 'border-left: 1px #ddd solid; border-top-left-radius: 0; border-bottom-left-radius: 0;',
                                    'placeholder' => 'Хугацаа',
                                    'value' => $this->metaWfmStatus['TRANSITION_TIME']
                                )); 
                                ?>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>    
        </table>    
    </div>  
    
    <a href="javascript:;" style="display:block;height:22px;color:#FF5722;font-weight:700;" class="pf-wfm-config-toggle pf-wfm-config-expand" data-class="pf-wfm-config-ntf">Notification <i class="far fa-plus-square"></i></a>
    
    <div class="panel panel-default bg-inverse pf-wfm-config-ntf d-none" style="margin-bottom: 10px !important">
        <table class="table sheetTable" style="table-layout: fixed">
            <tbody>  
                <tr>
                    <td style="width: 140px; height: 30px;" class="left-padding"><label for="isMailAction"><?php echo $this->lang->line('IS_MAIL_ACTION') ?>:</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'isMailAction', 'id' => 'isMailAction', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['IS_MAIL_ACTION'])); ?>
                    </td>
                </tr> 
                <tr>
                    <td style="width: 140px; height: 30px;" class="left-padding"><label for="isSendMail">Мэйл илгээх эсэх:</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'isSendMail', 'id' => 'isSendMail', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['IS_SEND_MAIL'])); ?>
                    </td>
                </tr> 
                <tr>
                    <td style="width: 140px; height: 30px;" class="left-padding">
                        <label for="isSendNotifWithEmail">Имейлээр илгээсэн нотификешнг web дээр давхар харуулах:</label>
                    </td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'isSendNotifWithEmail', 'id' => 'isSendNotifWithEmail', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['IS_SEND_NOTIF_WITH_EMAIL'])); ?>
                    </td>
                </tr>  
                <tr>
                    <td style="width: 140px; height: 30px;" class="left-padding"><label for="isSendSms">SMS илгээх эсэх:</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'isSendSms', 'id' => 'isSendSms', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['IS_SEND_SMS'])); ?>
                    </td>
                </tr> 
                <tr>
                    <td style="height: 30px;" class="left-padding">Хүсэлтийн эзэнд очих:</td>
                    <td>
                        <div class="input-group">
                            <input type="hidden" id="createdUserNotificationId" name="createdUserNotificationId" placeholder="" value="<?php echo $this->metaWfmStatus['CREATED_USER_NOTIFICATION_ID']; ?>" class="form-control form-control-sm">
                            <input type="text" id="createdUserNotificationName" title="<?php echo $this->metaWfmStatus['CREATEDUSER_NOTIFICATION_NAME'] ?>" disabled name="createdUserNotificationName" value="<?php echo $this->metaWfmStatus['CREATEDUSER_NOTIFICATION_NAME'] ?>" class="form-control form-control-sm" placeholder="Хэнээс">                                    
                            <span class="input-group-btn">
                                <button type="button" class="btn green-meadow mr0" onclick="dataViewCustomSelectableGrid('NTF_NOTIFICATION_LIST', 'single', 'createdUserNotifationSelectabledGridForWfm', '<?php echo $this->metaWfmStatus['CREATED_USER_NOTIFICATION_ID']; ?>', this);"><i class="icon-plus3 font-size-12"></i></button>
                                <button type="button" class="btn red-meadow" title="Устгах" onclick="deleteWfmNotification(this, 'createdUserNotificationId', 'createdUserNotificationName');"><i class="fa fa-trash"></i></button>
                            </span>
                        </div>
                    </td>
                </tr> 
                <tr>
                    <td style="height: 30px;" class="left-padding">Өмнөх төлвийн эзэнд очих:</td>
                    <td>
                        <div class="input-group">
                            <input type="hidden" id="fromNotificationId" name="fromNotificationId" placeholder="" value="<?php echo $this->metaWfmStatus['FROM_NOTIFICATION_ID']; ?>" class="form-control form-control-sm">
                            <input type="text" id="fromNotificationName" title="<?php echo $this->metaWfmStatus['FROMNOTICATION_NAME'] ?>" disabled name="fromNotificationName" value="<?php echo $this->metaWfmStatus['FROMNOTICATION_NAME'] ?>" class="form-control form-control-sm" placeholder="Хэнээс">                                    
                            <span class="input-group-btn">
                                <button type="button" class="btn green-meadow mr0" onclick="dataViewCustomSelectableGrid('NTF_NOTIFICATION_LIST', 'single', 'fromNotifationSelectabledGridForWfm', '<?php echo $this->metaWfmStatus['FROM_NOTIFICATION_ID']; ?>', this);"><i class="icon-plus3 font-size-12"></i></button>
                                <button type="button" class="btn red-meadow" title="Устгах" onclick="deleteWfmNotification(this, 'fromNotificationId', 'fromNotificationName');"><i class="fa fa-trash"></i></button>
                            </span>
                        </div> 
                    </td>
                </tr> 
                <tr>
                    <td style="height: 30px;" class="left-padding">Дараах төлвийн эзэнд очих:</td>
                    <td>
                        <div class="input-group">
                            <input type="hidden" id="toNotificationId" name="toNotificationId" placeholder="" value="<?php echo $this->metaWfmStatus['TO_NOTIFICATION_ID']; ?>" class="form-control form-control-sm">
                            <input type="text" id="toNotificationName" title="<?php echo $this->metaWfmStatus['TONOTICATION_NAME'] ?>" disabled name="toNotificationName" value="<?php echo $this->metaWfmStatus['TONOTICATION_NAME'] ?>" class="form-control form-control-sm" placeholder="Хэнд">                                    
                            <span class="input-group-btn">
                                <button type="button" class="btn green-meadow mr0" onclick="dataViewCustomSelectableGrid('NTF_NOTIFICATION_LIST', 'single', 'toNotifationSelectabledGridForWfm', '<?php echo $this->metaWfmStatus['TO_NOTIFICATION_ID']; ?>', this);"><i class="icon-plus3 font-size-12"></i></button>
                                <button type="button" class="btn red-meadow" title="Устгах" onclick="deleteWfmNotification(this, 'toNotificationId', 'toNotificationName');"><i class="fa fa-trash"></i></button>
                            </span>
                        </div>    
                    </td>
                </tr> 
            </tbody>    
        </table>    
    </div>    
    
    <a href="javascript:;" style="display:block;height:22px;color:#FF5722;font-weight:700;" class="pf-wfm-config-toggle pf-wfm-config-expand" data-class="pf-wfm-config-assign">Assignment <i class="far fa-plus-square"></i></a>
    
    <div class="panel panel-default bg-inverse pf-wfm-config-assign d-none" style="margin-bottom: 10px !important">
        <table class="table sheetTable" style="table-layout: fixed">
            <tbody>  
                <tr>
                    <td style="width: 140px; height: 30px;" class="left-padding"><label for="isUserDefAssign"><?php echo $this->lang->line('wfm_is_userdef_assign') ?>:</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'isUserDefAssign', 'id' => 'isUserDefAssign', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['IS_USERDEF_ASSIGN'])); ?>
                    </td>
                </tr> 
                <tr>
                    <td style="height: 30px;" class="left-padding"><label for="isUserdefRule"><?php echo $this->lang->line('wfm_is_userdef_rule') ?>:</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'isUserdefRule', 'id' => 'isUserdefRule', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['IS_USERDEF_RULE'])); ?>
                    </td>
                </tr> 
                <tr>
                    <td style="height: 30px;" class="left-padding"><?php echo $this->lang->line('wfm_default_rule_id') ?>:</td>
                    <td>
                        <?php echo Form::select(array('name' => 'defaultRuleId', 'id' => 'defaultRuleId', 'class' => 'form-control form-control-sm select2me', 'data' => $this->wfmStatusDefaultRuleList, 'op_value' => 'ID', 'op_text' => 'NAME', 'value' => $this->metaWfmStatus['DEFAULT_RULE_ID'], 'text' => 'notext')); ?>
                    </td>
                </tr> 
                <tr>
                    <td style="height: 30px;" class="left-padding"><label for="isInheritAssign">Is inherit assign:</label></td>
                    <td>
                        <?php echo Form::checkbox(array('name' => 'isInheritAssign', 'id' => 'isInheritAssign', 'class' => 'form-control', 'value' => '1', 'saved_val' => $this->metaWfmStatus['IS_INHERIT_ASSIGN'])); ?>
                    </td>
                </tr>                 
                <tr>
                    <td style="height: 30px;" class="left-padding"><label for="fromStatusName">From status name:</label></td>
                    <td>
                        <?php echo Form::text(array('name' => 'fromStatusName', 'id' => 'fromStatusName', 'class' => 'form-control', 'value' => $this->metaWfmStatus['FROM_STATUS_NAME'])); ?>
                    </td>
                </tr>
                <tr>
                    <td style="height: 30px;" class="left-padding"><label for="toStatusName">To status name:</label></td>
                    <td>
                        <?php echo Form::text(array('name' => 'toStatusName', 'id' => 'toStatusName', 'class' => 'form-control', 'value' => $this->metaWfmStatus['TO_STATUS_NAME'])); ?>
                    </td>
                </tr>
                <tr>
                    <td style="height: 30px;" class="left-padding"><label for="assignDataviewId">Assignment DV-р ажиллах бол:</label></td>
                    <td>
                        <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                            <div class="input-group double-between-input">
                                <input id="assignDataviewId" name="assignDataviewId" type="hidden" value="<?php echo Arr::get($this->metaWfmStatus, 'ASSIGN_DATAVIEW_ID'); ?>">
                                <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" value="<?php echo Arr::get($this->metaWfmStatus, 'ASSIGN_DATAVIEW_CODE'); ?>">
                                <span class="input-group-btn">
                                    <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid('single', '', this);"><i class="fa fa-search"></i></button>
                                </span> 
                                <span class="input-group-btn flex-col-group-btn">
                                    <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" value="<?php echo Arr::get($this->metaWfmStatus, 'ASSIGN_DATAVIEW_NAME'); ?>">      
                                </span>     
                            </div>
                        </div>   
                    </td>
                </tr>
            </tbody>    
        </table>    
    </div> 
  
    <?php 
    echo Form::hidden(array('name' => 'metaWfmStatusId', 'value' => $this->metaWfmStatusId));
    echo Form::hidden(array('name' => 'metaDataId', 'value' => $this->metaDataId));
    echo Form::close(); 
    ?>
</div>
</div>

<style type="text/css">
.ui-dialog .xs-form .sheetTable input.form-control {
    border: 1px transparent solid;
}
.ui-dialog .xs-form .sheetTable input.form-control:focus {
    border: 1px #666 solid !important;
}
</style>

<script type="text/javascript">
    $(function() {
        $('.colorpicker-default').colorpicker({
            format: 'hex'
        });
        $('button[role="iconpicker"]').iconpicker({
            arrowPrevIconClass: 'fa fa-arrow-left',
            arrowNextIconClass: 'fa fa-arrow-right'
        });
        
        $('.wfmstatus-icon').on('change', function(e) {
            var $parentCell = $(this).closest('td');
            if (e.icon === 'empty' || e.icon === 'fa-empty') {
                $parentCell.find("input[name='wfmStatusIcon']").val('');
            } else {
                $parentCell.find("input[name='wfmStatusIcon']").val(e.icon);
            }
        });
        
        $('.pf-wfm-config-toggle').on('click', function() {
            var $this = $(this), className = $this.attr('data-class');
            
            if ($this.hasClass('pf-wfm-config-expand')) {
                $this.removeClass('pf-wfm-config-expand').addClass('pf-wfm-config-collase');
                $this.find('i').removeClass('fa-plus-square').addClass('fa-minus-square');
                $('.' + className).removeClass('d-none');
            } else {
                $this.removeClass('pf-wfm-config-collase').addClass('pf-wfm-config-expand');
                $this.find('i').removeClass('fa-minus-square').addClass('fa-plus-square');
                $('.' + className).addClass('d-none');
            }
        });
    });
    
    function proccessSelectabledGridForWfm(metaDataCode, chooseType, elem, rows) {
        var currTarget = $(elem).closest('.input-group');
        currTarget.find('input[type="hidden"]').val(rows[0].id);
        currTarget.find('input[type="text"]').val(rows[0].metadatacode + ' | ' + rows[0].metadataname);
    }    
    
    function toNotifationSelectabledGridForWfm(metaDataCode, chooseType, elem, rows) {
        var currTarget = $(elem).closest('.input-group');
        currTarget.find('#toNotificationId').val(rows[0].id);
        currTarget.find('#toNotificationName').val(rows[0].notificationtypename + ' | ' + rows[0].message);
    }
    
    function fromNotifationSelectabledGridForWfm(metaDataCode, chooseType, elem, rows) {
        var currTarget = $(elem).closest('.input-group');
        currTarget.find('#fromNotificationId').val(rows[0].id);
        currTarget.find('#fromNotificationName').val(rows[0].notificationtypename + ' | ' + rows[0].message);
    }
    
    function createdUserNotifationSelectabledGridForWfm(metaDataCode, chooseType, elem, rows) {
        var currTarget = $(elem).closest('.input-group');
        currTarget.find('#createdUserNotificationId').val(rows[0].id);
        currTarget.find('#createdUserNotificationName').val(rows[0].notificationtypename + ' | ' + rows[0].message);
    }
    
    function toAssignedNotifationSelectabledGridForWfm(metaDataCode, chooseType, elem, rows) {
        var currTarget = $(elem).closest('.input-group');
        currTarget.find('#assignedToNotifId').val(rows[0].id);
        currTarget.find('#assignedToNotifName').val(rows[0].notificationtypename + ' | ' + rows[0].message);
    }
    
    function fromAssignedNotifationSelectabledGridForWfm(metaDataCode, chooseType, elem, rows) {
        var currTarget = $(elem).closest('.input-group');
        currTarget.find('#assignedFromNotifId').val(rows[0].id);
        currTarget.find('#assignedFromNotifName').val(rows[0].notificationtypename + ' | ' + rows[0].message);
    }
    
    function deleteWfmProcess (element) {
        var $currTarget = $(element).closest('.input-group');
        $currTarget.find('#wfmProcessId, #wfmMobileProcessId, #wfmProcessCodeName').val('');
    }
    
    function deleteWfmNotification (element, id, name) {
        var currTarget = $(element).closest('.input-group');
        currTarget.find('#'+id).val('');
        currTarget.find('#'+name).val('');
    }
    
    function configFromNotificationProcessWfm(element) {
        var currTarget = $(element).closest('.input-group');
        var notificationId = currTarget.find('#fromNotificationId').val();
        if (notificationId.length === 0) {
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: 'Процессоо сонгоно уу?',
                type: 'warning', 
                sticker: false
            });
            return;
        }
        $.ajax({
            type: 'post',
            url: 'mdnotification/findParams',
            data: {'notificationId' : notificationId},
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                console.log(data);
                return;
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.unblockUI();
        });
    }
    
    function configToNotificationProcessWfm(element) {
        var currTarget = $(element).closest('.input-group');
        var notificationId = currTarget.find('#toNotificationId').val();
        if (notificationId.length === 0) {
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: 'Процессоо сонгоно уу?',
                type: 'warning', 
                sticker: false
            });
            return;
        }
        $.ajax({
            type: 'post',
            url: 'mdnotification/findParams',
            data: {'notificationId' : notificationId},
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                console.log(data);
                return;  
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.unblockUI();
        });
    }

    function startWfmPointer(wfmStatusId) {
        var $dialogName = 'dialog-meta-start-wfm-status';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $("#" + $dialogName);        

        $.ajax({
            type: 'post',
            url: 'mdprocessflow/startWfmCriteria',
            data: {wfmStatusId: wfmStatusId, metaDataId: '<?php echo $this->metaDataId ?>'},
            dataType: 'json',
            beforeSend: function() {
                if (!$("link[href='middleware/assets/css/salary/expression.css']").length){
                    $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/css/salary/expression.css"/>');
                }                
                Core.blockUI({animate: true});
            },
            success: function(data) {
                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1100,
                    minWidth: 1100,
                    height: 'auto',
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('close');
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm green', click: function() {
                            $("#brcriteria-form", "#" + $dialogName).validate({ errorPlacement: function() {} });
                            if ($("#brcriteria-form", "#" + $dialogName).valid()) {
                                PNotify.removeAll();    
                                var expArea = $("#brcriteria-form", "#"+$dialogName).find('.p-exp-area');
                                var expAreaContent = $.trim(expArea.html());
                                $("#brcriteria-form", "#"+$dialogName).find('input[name="bpCriteria"]').val(expAreaContent);

                                $.ajax({
                                    type: 'post',
                                    url: 'mdprocessflow/saveStartWfmCriteria',
                                    data: $("#brcriteria-form", "#"+$dialogName).serialize() + '&wfmStatusId=' + wfmStatusId + '&workflowId=<?php echo $this->metaWfmStatus['WFM_WORKFLOW_ID']; ?>',
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({animate: true});
                                    },
                                    success: function(data) {
                                        if (data.status === 'success') {
                                            viewVisualHtmlMetaData('<?php echo $this->metaDataId ?>');
                                            $dialog.dialog('close');
                                        } else {
                                            new PNotify({
                                                title: data.status,
                                                text: data.message,
                                                type: data.status,
                                                sticker: false
                                            });
                                        }
                                        Core.unblockUI();
                                    },
                                    error: function() { alert("Error"); }
                                });
                            }
                        }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
                $dialog.find('input[name="transitionDescription"]').focus();
                Core.unblockUI();
            },
            error: function() {alert("Error");}
        }).done(function() {
            Core.initAjax($dialog);
        });        
    }
</script>