<div id="window-mobi-template-connection_<?php echo $this->uniqId ?>">
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'mobi-template-connection', 'method' => 'post')); ?>
        <div class="row">
            <div class="col-md-12 center-sidebar">
                <div class="col-md-12 xs-form">
                    <table class="table table-sm table-no-bordered bp-header-param">
                        <tbody>
                            <tr>                                            
                                <td class="text-right middle" data-cell-path="sourceAddress" style="width: 25%">
                                    <?php echo Form::label(array('text' => $this->lang->line('source_address'), 'for' => 'sourceAddress',  'class' => 'col-form-label')); ?>                            
                                </td>
                                <td class="middle" data-cell-path="sourceAddress" style="width: 25%" colspan="">
                                    <div data-section-path="sourceAddress">
                                        <?php echo Form::text(array('name' => 'sourceAddress', 'class' => 'form-control form-control-sm stringInit viewDisableMode', 'value' => isset($this->installation['SOURCE_ADDRESS']) ? $this->installation['SOURCE_ADDRESS'] : '')); ?>
                                    </div>
                                </td>
                                <td class="text-right middle" data-cell-path="installationUserName" style="width: 25%">
                                    <?php echo Form::label(array('text' => $this->lang->line('installation_user_id'), 'for' => 'installationUserName', 'class' => 'col-form-label')); ?>                                 
                                </td>
                                <td class="middle" data-cell-path="installationUserName" style="width: 25%" colspan="">
                                    <div data-section-path="installationUserName">
                                        <?php echo Form::text(array('name' => 'installationUserName', 'class' => 'form-control form-control-sm stringInit', 'value' => isset($this->installation['INSTALLATION_USER']) ? $this->installation['INSTALLATION_USER'] : (isset($this->installationUserName) ? $this->installationUserName : ''), 'disabled' => 'disabled')); ?>
                                    </div>
                                </td>

                            </tr>
                            <tr>                                            
                                <td class="text-right middle" data-cell-path="destinationAddress" style="width: 25%">
                                    <?php echo Form::label(array('text' => $this->lang->line('destination_address'), 'for' => 'destinationAddress', 'class' => 'col-form-label')); ?>
                                </td>
                                <td class="middle" data-cell-path="destinationAddress" style="width: 25%" colspan="">
                                    <div data-section-path="destinationAddress">
                                        <?php echo Form::text(array('name' => 'destinationAddress', 'class' => 'form-control form-control-sm stringInit viewDisableMode', 'value' => isset($this->installation['DESTINATION_ADDRESS']) ? $this->installation['DESTINATION_ADDRESS'] : '')); ?>
                                    </div>
                                </td>
                                <td class="text-right middle" data-cell-path="installationDate" style="width: 25%">
                                    <?php echo Form::label(array('text' => $this->lang->line('installation_date'), 'for' => 'installationDate', 'class' => 'col-form-label')); ?>
                                </td>
                                <td class="middle" data-cell-path="installationDate" style="width: 25%" colspan="">
                                    <div data-section-path="installationDate">
                                        <div class="dateElement input-group">
                                            <?php echo Form::text(array('name' => 'installationDate', 'class' => 'form-control form-control-sm dateInit', 'value' => isset($this->installationDate) ? $this->installationDate : '', 'disabled' => 'disabled')); ?>
                                            <span class="input-group-btn"><button onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button></span>
                                        </div>
                                </td>
                            </tr>
                            <tr>                                            
                                <td class="text-right middle" data-cell-path="circuitId" style="width: 25%">
                                    <?php echo Form::label(array('text' => $this->lang->line('circuit_id'), 'for' => 'circuitId', 'class' => 'col-form-label')); ?>
                                </td>
                                <td class="middle" data-cell-path="circuitId" style="width: 25%" colspan="">
                                    <div data-section-path="circuitId">
                                        <?php echo Form::text(array('name' => 'circuitId', 'class' => 'form-control form-control-sm stringInit viewDisableMode', 'value' => isset($this->installation['CIRCUIT_ID']) ? $this->installation['CIRCUIT_ID'] : '')); ?>
                                    </div>
                                </td>
                         <!--       <td class="text-right middle" data-cell-path="taskId" style="width: 25%">
                                <?php echo Form::label(array('text' => $this->lang->line('taskId'), 'for' => 'taskId', 'class' => 'col-form-label')); ?>
                                </td>-->
                                <td class="middle" data-cell-path="taskId" style="width: 25%" colspan="">
                                    <div data-section-path="taskId">
                                        <?php echo Form::hidden(array('name' => 'taskId', 'class' => 'form-control form-control-sm stringInit', 'value' => isset($this->installation['TASK_ID']) ? $this->installation['TASK_ID'] : $this->taskId)); ?>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="tabbable-line tabbable-tabdrop bp-tabs">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a href="#tab_<?php echo $this->assetId . '_' . $this->uniqId ?>" class="nav-link active" data-toggle="tab"><?php echo $this->lang->line('connection_port_002') ?></a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_<?php echo $this->assetId . '_' . $this->uniqId ?>">
                                <div class="row mb10" data-section-path="MOB_ASSET_PORT" data-isclear="0">
                                    <div class="col-md-12" data-bp-detail-container="1">
                                        <div class="table-toolbar ">
                                            <div class="row">
                                                <div class="col-md-6 text-left">
                                                    <a href="javascript:;" class="btn blue plus-btn btn-xs addConnectionBtn editMode"  onclick="addRow('#connection_port_<?php echo $this->uniqId ?>')" title="add_btn"><i class="icon-plus3 font-size-12"></i></a>
                                                    <a href="javascript:;" class="btn blue plus-btn btn-xs viewOnMap"  onclick="viewMap()" title="add_btn"><i class="fa fa-map"></i></a>
                                                </div>
                                                <div class="col-md-6 text-right dv-right-tools-btn">
                                                    <button type="button" class="btn btn-secondary btn-sm btn-circle default ml4 bp-detail-fullscreen editMode" title="Fullscreen" onclick="bpDetailFullScreen(this);" data-action-path="MOB_ASSET_PORT" data-old-height="62" data-old-max-height="450px"><i class="fa fa-expand"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div data-parent-path="MOB_ASSET_PORT" class="bp-overflow-xy-auto no-border" style="max-height: 450px; overflow: auto;">
                                            <table class="table " id="connection_port_<?php echo $this->uniqId ?>">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 48%;"><?php echo $this->lang->line('connection_port_001') ?></th>
                                                        <th style="width: 58%;"><?php echo $this->lang->line('connection_port_002') ?></th>
                                                        <th style="width: 4%;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr style="display: none;">
                                                        <td>
                                                            <div class="col-md-12 editMode">
                                                                <input id="srclocationid" type="hidden" name="srclocationid[0][]" value="">
                                                                <input id="srcPortInfo" type="hidden" name="srcPortInfo[0][]" value="">
                                                                <input id="srcrecordid" name="srcrecordid[0][]" type="hidden" value="">
                                                                <input id="srcportid" name="srcportid[0][]" type="hidden" value="">
                                                                <input id="orderNum" name="orderNum[0][]" type="hidden" value="0" class="ordernumberindex">
                                                                <button type="button" id="searchCalcTypeButton" style="float:left; margin-right:6px !important; font-size: 9px;" class="btn default btn-bordered form-control-sm mr0 searchCalcTypeButton" onclick="dataViewCustomSelectableGrid('mobSiteEquipmentDropList', 'single', 'chooseSrcMobiBpMeta', 'id=<?php echo $this->srcRecordId ?>', this);"><i class="fa fa-search"></i></button>
                                                                <?php
                                                                echo Form::select(
                                                                        array(
                                                                            'name' => 'srcPortType[0][]',
                                                                            'id' => 'srcPortType',
                                                                            'class' => 'form-control form-control-sm initRequired',
                                                                            'data' => '',
                                                                            'op_value' => 'ID',
                                                                            'op_text' => 'NAME',
                                                                            'onchange' => 'changeSrcPortNumber(this)',
                                                                            'style' => 'float:left; width:120px; margin-right:6px !important'
                                                                        )
                                                                );
                                                                ?>
                                                                <?php
                                                                echo Form::select(
                                                                        array(
                                                                            'name' => 'srcPortNumber[0][]',
                                                                            'id' => 'srcPortNumber',
                                                                            'class' => 'form-control form-control-sm initRequired',
                                                                            'data' => '',
                                                                            'op_value' => 'ID',
                                                                            'op_text' => 'NAME',
                                                                            'onchange' => 'getSrcPortNumber(this)',
                                                                            'style' => 'float:left; width:120px; margin-right:6px !important'
                                                                        )
                                                                );
                                                                ?>
                                                            </div>
                                                            <div class="col-md-12 pull-left">
                                                                <span  class="main-info" data-path="src-connection-path">
                                                                    <span></span>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-12 editMode">
                                                                <input id="trgcrecordid" name="trgcrecordid[0][]" type="hidden" value="">
                                                                <input id="trgclocationid" name="trgclocationid[0][]" type="hidden" value="">
                                                                <input id="trgportid" name="trgportid[0][]" type="hidden" value="">
                                                                <input id="trgcPortInfo" type="hidden" name="trgcPortInfo[0][]" value="">
                                                                <button type="button" id="searchCalcTypeButton" style="float:left; margin-right:6px !important; font-size: 9px;" class="btn default btn-bordered form-control-sm mr0 searchCalcTypeButton" onclick="dataViewCustomSelectableGrid('mobSiteEquipmentDropList', 'single', 'chooseMobiBpMeta', 'id=<?php echo $this->srcRecordId ?>', this);"><i class="fa fa-search"></i></button>
                                                                <?php
                                                                echo Form::select(
                                                                        array(
                                                                            'name' => 'trgPortType[0][]',
                                                                            'id' => 'trgPortType',
                                                                            'class' => 'form-control form-control-sm initRequired',
                                                                            'data' => '',
                                                                            'op_value' => 'ID',
                                                                            'op_text' => 'NAME',
                                                                            'onchange' => 'changePortNumber(this)',
                                                                            'style' => 'float:left; width:120px; margin-right:6px !important'
                                                                        )
                                                                );
                                                                ?>
                                                                <?php
                                                                echo Form::select(
                                                                        array(
                                                                            'name' => 'trgPortNumber[0][]',
                                                                            'id' => 'trgPortNumber',
                                                                            'class' => 'form-control form-control-sm initRequired',
                                                                            'data' => '',
                                                                            'op_value' => 'ID',
                                                                            'op_text' => 'NAME',
                                                                            'onchange' => 'getPortNumber(this)',
                                                                            'style' => 'float:left; width:120px; margin-right:6px !important'
                                                                        )
                                                                );
                                                                ?>
                                                                <span  class="main-info" data-path="connection-path">
                                                                    <span></span>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="text-center stretchInput middle editMode"><a href="javascript:;" class="btn red btn-xs bp-remove-row" onclick="removeRow('#connection_port_<?php echo $this->uniqId ?>', this)" title="delete_btn"><i class="fa fa-trash"></i></a></td>
                                                    </tr>
                                                    <?php
                                                    if (!empty($this->conData)) {
                                                        foreach ($this->conData as $key => $row) { ?>
                                                            <tr>
                                                                <td>
                                                                    <div class="col-md-12 editMode">
                                                                        <input id="srclocationid" type="hidden" name="srclocationid[0][]" value="<?php echo $row['SRC_LOCATION_ID']; ?>">
                                                                        <input id="srcPortInfo" type="hidden" name="srcPortInfo[0][]" value="<?php echo $row['SRC_PORT_INFO']; ?>">
                                                                        <input id="srcrecordid" name="srcrecordid[0][]" type="hidden" value="<?php echo $row['SRC_RECORD_ID']; ?>">
                                                                        <input id="srcportid" name="srcportid[0][]" type="hidden" value="<?php echo $row['SRC_PORT_ID']; ?>">
                                                                        <?php if (($key == 0)) { ?>
                                                                            <input id="srcPortType" name="srcPortType[0][]" type="hidden" value="<?php echo $row['SRC_PORT_TYPE_ID']; ?>">
                                                                            <input id="srcPortNumber" name="srcPortNumber[0][]" type="hidden" value="<?php echo $row['SRC_PORT']; ?>">
                                                                        <?php } ?>
                                                                        <input id="orderNum" name="orderNum[0][]" type="hidden" value="<?php echo $row['ORDER_NUM']; ?>" class="ordernumberindex">
                                                                        <button type="button" id="searchCalcTypeButton" style="float:left; margin-right:6px !important; font-size: 9px;" class="btn default <?php echo ($key == 0) ? 'disabled' : ''; ?> btn-bordered form-control-sm mr0 searchCalcTypeButton" onclick="dataViewCustomSelectableGrid('mobSiteEquipmentDropList', 'single', 'chooseSrcMobiBpMeta', 'id=<?php echo $row['SRC_RECORD_ID'] ?>', this);"><i class="fa fa-search"></i></button>
                                                                        <?php
                                                                        echo Form::select(
                                                                                array(
                                                                                    'name' => 'srcPortType[0][]',
                                                                                    'id' => 'srcPortType',
                                                                                    'class' => 'form-control form-control-sm',
                                                                                    'data' => $row['srcPortTypeList'],
                                                                                    'op_value' => 'PORT_TYPE_ID',
                                                                                    'op_text' => 'PORT_TYPE_NAME',
                                                                                    'onchange' => 'changeSrcPortNumber(this)',
                                                                                    'value' => $row['SRC_PORT_TYPE_ID'],
                                                                                    'required' => 'required',
                                                                                    'style' => 'float:left; width:120px; margin-right:6px !important',
                                                                                    ($key == 0) ? 'disabled' : '' => ($key == 0) ? 'disabled' : ''
                                                                                )
                                                                        );
                                                                        ?>
                                                                        <?php
                                                                        echo Form::select(
                                                                                array(
                                                                                    'name' => 'srcPortNumber[0][]',
                                                                                    'id' => 'srcPortNumber',
                                                                                    'class' => 'form-control form-control-sm',
                                                                                    'data' => ($key == 0) ? array(0 => array('ID' => intval($row['SRC_PORT']), 'NAME' => $row['SRC_PORT'])) : !empty($row['SRC_RECORD_ID']) ? (new Mdasset())->getPortListArray($row['SRC_RECORD_ID'], $row['SRC_PORT_TYPE_ID'], $row['SRC_PORT'], $row['SRC_PORT_ID']) : array(),
                                                                                    'op_value' => 'ID',
                                                                                    'op_text' => 'NAME',
                                                                                    'onchange' => 'getSrcPortNumber(this)',
                                                                                    'value' => intval($row['SRC_PORT']),
                                                                                    'required' => 'required',
                                                                                    'style' => 'float:left; width:120px; margin-right:6px !important',
                                                                                    ($key == 0) ? 'disabled' : '' => ($key == 0) ? 'disabled' : ''
                                                                                )
                                                                        );
                                                                        ?>
                                                                    </div>
                                                                    <div class="col-md-12 pull-left">
                                                                        <span  class="main-info" data-path="src-connection-path">
                                                                            <?php echo '<span ' . (($this->connectionId == $row['CONNECTION_ID']) ? 'style="color: #4b8df8"' : '') . '>' . $row['SRC_PORT_INF'] . '</span>' ?>
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="col-md-12 editMode">
                                                                        <input id="trgcrecordid" name="trgcrecordid[0][]" type="hidden" value="<?php echo $row['TRG_RECORD_ID']; ?>">
                                                                        <input id="trgclocationid" name="trgclocationid[0][]" type="hidden" value="<?php echo $row['TRG_LOCATION_ID']; ?>">
                                                                        <input id="trgcPortInfo" type="hidden" name="trgcPortInfo[0][]" value="<?php echo $row['TRG_PORT_INFO']; ?>">
                                                                        <input id="trgportid" name="trgportid[0][]" type="hidden" value="<?php echo $row['TRG_PORT_ID']; ?>">
                                                                        <button type="button" id="searchCalcTypeButton" style="float:left; margin-right:6px !important; font-size: 9px;" class="btn default btn-bordered form-control-sm mr0 searchCalcTypeButton" onclick="dataViewCustomSelectableGrid('mobSiteEquipmentDropList', 'single', 'chooseMobiBpMeta', 'id=<?php echo $row['TRG_RECORD_ID'] ?>', this);"><i class="fa fa-search"></i></button>
                                                                        <?php
                                                                        echo Form::select(
                                                                                array(
                                                                                    'name' => 'trgPortType[0][]',
                                                                                    'id' => 'trgPortType',
                                                                                    'class' => 'form-control form-control-sm',
                                                                                    'data' => $row['trgPortTypeList'],
                                                                                    'op_value' => 'PORT_TYPE_ID',
                                                                                    'op_text' => 'PORT_TYPE_NAME',
                                                                                    'onchange' => 'changePortNumber(this)',
                                                                                    'value' => empty($row['TRG_DESCRIPTION']) ? $row['TRG_PORT_TYPE_ID'] : null,
                                                                                    'required' => 'required',
                                                                                    'style' => 'float:left; width:120px; margin-right:6px !important;' . (empty($row['TRG_DESCRIPTION']) ? '' : 'display: none'),
                                                                                    empty($row['TRG_DESCRIPTION']) ? '' : 'disabled' => 'disabled'
                                                                                )
                                                                        );
                                                                        ?>
                                                                        <?php
                                                                        echo Form::select(
                                                                                array(
                                                                                    'name' => 'trgPortNumber[0][]',
                                                                                    'id' => 'trgPortNumber',
                                                                                    'class' => 'form-control form-control-sm',
                                                                                    'data' => empty($row['TRG_DESCRIPTION']) && !empty($row['TRG_RECORD_ID']) ? (new Mdasset())->getPortListArray($row['TRG_RECORD_ID'], $row['TRG_PORT_TYPE_ID'], $row['TRG_PORT'], $row['TRG_PORT_ID']) : null,
                                                                                    'op_value' => 'ID',
                                                                                    'op_text' => 'NAME',
                                                                                    'onchange' => 'getPortNumber(this)',
                                                                                    'value' => empty($row['TRG_DESCRIPTION']) ? intval($row['TRG_PORT']) : null,
                                                                                    'required' => 'required',
                                                                                    'style' => 'float:left; width:120px; margin-right:6px !important;' . (empty($row['TRG_DESCRIPTION']) ? '' : 'display: none'),
                                                                                    empty($row['TRG_DESCRIPTION']) ? '' : 'disabled' => 'disabled'
                                                                                )
                                                                        );
                                                                        ?>
                                                                        <?php
                                                                        if (!empty($row['TRG_DESCRIPTION'])) {
                                                                            echo Form::text(array('name' => 'trgPortInfoExternal', 'class' => 'form-control form-control-sm stringInit', 'style' => 'float: left; width: 400px; margin-right: 6px !important;', 'value' => $row['TRG_DESCRIPTION']));
                                                                            echo '<script> $(".addConnectionBtn").hide(); </script>';
                                                                        }
                                                                        ?>
                                                                        <span  class="main-info" data-path="connection-path">
                                                                        </span>
                                                                    </div>
                                                                    <div class="col-md-12 pull-left">
                                                                        <span  class="main-info" data-path="src-connection-path">
                                                                            <?php echo '<span ' . (($this->connectionId == $row['CONNECTION_ID']) ? 'style="color: #4b8df8"' : '') . '>' . $row['TRG_PORT_INF'] . '</span>' ?>
                                                                        </span>
                                                                    </div>                    
                                                                </td>
                                                                <td class="text-center stretchInput middle editMode">
                                                                    <?php
                                                                    if ($key == 0) {
                                                                        echo '';
                                                                    } else {
                                                                        ?><a href="javascript:;" class="btn red btn-xs bp-remove-row" onclick="removeRow('#connection_port_<?php echo $this->uniqId ?>', this)" title="delete_btn"><i class="fa fa-trash"></i></a><?php } ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <div class="col-md-12 editMode">
                                                                    <input id="srclocationid" type="hidden" name="srclocationid[0][]" value="<?php echo $this->locationId; ?>">
                                                                    <input id="srcPortInfo" type="hidden" name="srcPortInfo[0][]" value="<?php echo $this->directorypath; ?>">
                                                                    <input id="srcrecordid" name="srcrecordid[0][]" type="hidden" value="<?php echo $this->checkkeyid; ?>">
                                                                    <input id="srcPortType" name="srcPortType[0][]" type="hidden" value="<?php echo $this->dataRow['PORT_TYPE_ID']; ?>">
                                                                    <input id="srcPortNumber" name="srcPortNumber[0][]" type="hidden" value="<?php echo $this->dataRow['PORT_ORDER']; ?>">
                                                                    <input id="srcportid" name="srcportid[0][]" type="hidden" value="<?php echo $this->dataRow['ASSET_PORT_ID']; ?>">
                                                                    <input id="orderNum" name="orderNum[0][]" type="hidden" value="1" class="ordernumberindex">
                                                                    <button type="button" id="searchCalcTypeButton" style="float:left; margin-right:6px !important; font-size: 9px;" class="btn default btn-bordered form-control-sm mr0 searchCalcTypeButton disabled" onclick="dataViewCustomSelectableGrid('mobSiteEquipmentDropList', 'single', 'chooseSrcMobiBpMeta', 'id=<?php echo $this->srcRecordId ?>', this);"><i class="fa fa-search"></i></button>
                                                                    <?php
                                                                    echo Form::select(
                                                                            array(
                                                                                'name' => 'srcPortType[0][]',
                                                                                'id' => 'srcPortType',
                                                                                'class' => 'form-control form-control-sm',
                                                                                'data' => $this->defaultSelectData,
                                                                                'op_value' => 'PORT_TYPE_ID',
                                                                                'op_text' => 'PORT_TYPE_NAME',
                                                                                'style' => 'float:left; width:100px; margin-right:6px !important',
                                                                                'value' => $this->dataRow['PORT_TYPE_ID'],
                                                                                'required' => 'required',
                                                                                'disabled' => 'disabled'
                                                                            )
                                                                    );
                                                                    ?>
                                                                    <?php
                                                                    echo Form::select(
                                                                            array(
                                                                                'name' => 'srcPortNumber[0][]',
                                                                                'id' => 'srcPortNumber',
                                                                                'class' => 'form-control form-control-sm',
                                                                                'data' => array(0 => array('ID' => $this->dataRow['PORT_ORDER'], 'NAME' => $this->dataRow['PORT_ORDER'])),
                                                                                'op_value' => 'ID',
                                                                                'op_text' => 'NAME',
                                                                                'style' => 'float:left; width:60px; margin-right:6px !important',
                                                                                'value' => $this->dataRow['PORT_ORDER'],
                                                                                'required' => 'required',
                                                                                'disabled' => 'disabled'
                                                                            )
                                                                    );
                                                                    ?>
                                                                </div>
                                                                <div class="col-md-12  pull-left">
                                                                    <span class="main-info" data-path="src-connection-path"><?php echo $this->directorypathFull ?></span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12 editMode">
                                                                    <input id="trgcrecordid" name="trgcrecordid[0][]" type="hidden" value="<?php echo isset($this->conTrgData['TRG_RECORD_ID']) ? $this->conTrgData['TRG_RECORD_ID'] : ''; ?>">
                                                                    <input id="trgclocationid" name="trgclocationid[0][]" type="hidden" value="<?php echo (isset($this->conTrgData['TRG_LOCATION_ID'])) ? $this->conTrgData['TRG_LOCATION_ID'] : '' ?>">
                                                                    <input id="trgportid" name="trgportid[0][]" type="hidden" value="">
                                                                    <input id="trgcPortInfo" name="trgcPortInfo[0][]" type="hidden" value="">
                                                                    <button type="button" id="searchCalcTypeButton" style="float:left; margin-right:6px !important; font-size: 9px;" class="btn default btn-bordered form-control-sm mr0 searchCalcTypeButton" onclick="dataViewCustomSelectableGrid('mobSiteEquipmentDropList', 'single', 'chooseMobiBpMeta', 'id=<?php echo $this->srcRecordId ?>', this);"><i class="fa fa-search"></i></button>
                                                                    <?php
                                                                    echo Form::select(
                                                                            array(
                                                                                'name' => 'trgPortType[0][]',
                                                                                'id' => 'trgPortType',
                                                                                'class' => 'form-control form-control-sm',
                                                                                'data' => '',
                                                                                'op_value' => 'ID',
                                                                                'op_text' => 'NAME',
                                                                                'onchange' => 'changePortNumber(this)',
                                                                                'required' => 'required',
                                                                                'style' => 'float:left; width:120px; margin-right:6px !important'
                                                                            )
                                                                    );
                                                                    ?>
                                                                    <?php
                                                                    echo Form::select(
                                                                            array(
                                                                                'name' => 'trgPortNumber[0][]',
                                                                                'id' => 'trgPortNumber',
                                                                                'class' => 'form-control form-control-sm',
                                                                                'data' => '',
                                                                                'op_value' => 'ID',
                                                                                'op_text' => 'NAME',
                                                                                'onchange' => 'getPortNumber(this)',
                                                                                'required' => 'required',
                                                                                'style' => 'float:left; width:120px; margin-right:6px !important'
                                                                            )
                                                                    );
                                                                    ?>
                                                                    <span  class="main-info" data-path="connection-path">
                                                                        <?php echo isset($this->conTrgData['TRG_PORT_INF']) ? $this->conTrgData['TRG_PORT_INF'] : ''; ?>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            <td class="text-center stretchInput middle editMode"></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <input type="hidden" name="checkkeyid" value="<?php echo $this->checkkeyid ?>">
                                            <input type="hidden" name="assetPortId" value="<?php echo $this->dataRow['ASSET_PORT_ID'] ?>">
                                            <input type="hidden" name="locationId" value="<?php echo $this->locationId ?>">
                                            <input type="hidden" name="portNumber" value="<?php echo $this->dataRow['PORT_ORDER'] ?>">
                                            <input type="hidden" name="portTypeId" value="<?php echo $this->dataRow['PORT_TYPE_ID'] ?>">
                                            <input type="hidden" name="connectionId" value="<?php echo $this->connectionId ?>">
                                            <input type="hidden" name="installationId" value="<?php echo $this->installationId ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php echo Form::close(); ?>
</div>

<style type="text/css"> 
    
    #window-mobi-template-connection_<?php echo $this->uniqId ?> .labelDiv {
        border-bottom: 1px #0576c5 solid;
        font-size: 18px !important;

        width: 100%;
        text-align: left;
        text-transform: capitalize;
        padding: 2px 6px;
        color: #0576c5;
        float: left;
        margin-bottom: 10px;
        padding-bottom: 10px;
    }

    #window-mobi-template-connection_<?php echo $this->uniqId ?> .main-info {
        font-size: 12px; 
        line-height: 33px; 
        text-transform: uppercase;
        font-weight: bold;

        color: gray;
    }
</style>
<script type="text/javascript">

    var $trglocationName = '';
    var $srclocationName = '';
    var $connectionData = <?php echo ($this->conData) ? json_encode($this->conData) : '\'\'' ?>;
    var isEdit = '<?php echo isset($this->isEdit) ? $this->isEdit : "false"; ?>';
    var $installationId = '<?php echo $this->installationId ?>';
    var map;

    $(function () {
        $('input[name="srcPortInfo"]').val('<?php echo $this->directorypath ?>');

        Core.initAjax($('#window-mobi-template-connection_<?php echo $this->uniqId ?>'));
        if (isEdit === 'false') {
            $('.editMode').hide();
            $('.viewDisableMode').prop('disabled', true);
        }
    });

    //<editor-fold defaultstate="collapsed" desc="Target Port">

    function chooseMobiBpMeta(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        if (!row.locationid) {
            PNotify.removeAll();
            new PNotify({
                title: 'Анхааруулга',
                text: 'Холбогдох төхөөрөмжийн байршил үүсээгүй байна',
                type: 'warning',
                sticker: false
            });
            return;
        }

        if (row.typeid === '9') {
            $('.addConnectionBtn').hide();
            var _parent = $(elem).closest('tr');

            _parent.find('#trgcrecordid').val(row.checkkeyid);
            _parent.find('#trgclocationid').val(row.locationid);
            _parent.find('#metaDataId_displayField').val(row.code);
            _parent.find('#metaDataId_nameField').val(row.name);

            $trglocationName = row.directorypath;
            if (typeof _parent.find('input[name="trgPortInfoExternal"]').val() !== 'undefined') {
                var val = _parent.find('input[name="trgPortInfoExternal"]').val();
                _parent.find('input[name="trgPortInfoExternal"]').remove();
                _parent.find('td:eq(1) .editMode').append('<input type="text" name="trgPortInfoExternal" class="form-control form-control-sm stringInit" style="float: left; width: 400px; margin-right: 6px !important;" required="required" value="' + val + '">');
            } else {
                _parent.find('td:eq(1) .editMode').append('<input type="text" name="trgPortInfoExternal" class="form-control form-control-sm stringInit" style="float: left; width: 400px; margin-right: 6px !important;" required="required" value="">');
            }
            _parent.find('span[data-path="connection-path"]').html($trglocationName);
            _parent.find('input[name="trgcPortInfo[0][]"]').val($trglocationName);
            _parent.find('input[name="trgportid[0][]"]').val('');

            var $elementTabId = '#window-mobi-template-connection_<?php echo $this->uniqId ?>';
            var external = true;
            changePortTypeDataV1(row.assetid, _parent, external);
        } else {
            $('.addConnectionBtn').show();
            var _parent = $(elem).closest('tr');

            _parent.find('#trgcrecordid').val(row.checkkeyid);
            _parent.find('#trgclocationid').val(row.locationid);
            _parent.find('#metaDataId_displayField').val(row.code);
            _parent.find('#metaDataId_nameField').val(row.name);

            $trglocationName = row.directorypath;
            _parent.find('span[data-path="connection-path"]').html($trglocationName);
            _parent.find('input[name="trgcPortInfo[0][]"]').val($trglocationName);
            if (typeof _parent.find('input[name="trgPortInfoExternal"]') !== 'undefined') {
                _parent.find('input[name="trgPortInfoExternal"]').remove();
                _parent.find("select#trgPortType").attr('disabled', false).show();
                _parent.find("select#trgPortNumber").attr('disabled', false).show();
            }

            var $elementTabId = '#window-mobi-template-connection_<?php echo $this->uniqId ?>';
            changePortTypeDataV1(row.assetid, _parent);
        }
    }
    function changePortTypeDataV1(assetid, _parent, external = false) {
        if (!external) {
            $.ajax({
                type: 'post',
                url: 'mdasset/getConnectionIdData',
                dataType: 'json',
                data: {
                    assetId: assetid,
                },
                beforeSend: function () {

                    _parent.find("select#trgPortType option:gt(0)").remove();
                    _parent.find("select#trgPortNumber option:gt(0)").remove();

                    Core.blockUI({
                        target: '#window-mobi-template-connection_<?php echo $this->uniqId ?>',
                        animate: true
                    });
                },
                success: function (data) {
                    _parent.find("select#trgPortType option:gt(0)").remove();
                    $.each(data, function () {
                        _parent.find("select#trgPortType").append($("<option />").val(this.PORT_TYPE_ID).text(this.PORT_TYPE_NAME).attr('data-port-qty', this.PORT_QTY).attr('data-asset-port-id', this.ASSET_PORT_ID));
                    });

                    Core.initSelect2();
                    Core.unblockUI('#window-mobi-template-connection_<?php echo $this->uniqId ?>');
                },
                error: function (data) {
                    alert('Error');
                    Core.unblockUI('#window-mobi-template-connection_<?php echo $this->uniqId ?>');
                }
            }).done(function () {
                if (typeof trgPortTypeId !== 'undefined') {
                    _parent.find("select#trgPortType").val(trgPortTypeId);
                    var $qty = _parent.find("select#trgPortType").find('option[value="' + trgPortTypeId + '"]').attr('data-port-qty');
                    var $assetPortId = _parent.find("select#trgPortType").find('option[value="' + trgPortTypeId + '"]').attr('data-asset-port-id');
                    changePortNumberValue($qty, trgPort, _parent, '', $assetPortId);
                }
            });
        } else {
            _parent.find('input[name="trgcPortInfo[0][]"]').val($trglocationName);
            _parent.find("select#trgPortType").attr('disabled', true).hide();
            _parent.find("select#trgPortNumber").attr('disabled', true).hide();
    }
    }

    function changePortNumber(element) {
        console.log($trglocationName);

        var $this = $(element);
        var $thisvalue = $this.val();
        var _parent = $(element).closest('tr');

        _parent.find("select#trgPortNumber option:gt(0)").remove();
        _parent.find("select#trgPortNumber").trigger("change");

        var $qty = $this.find('option[value="' + $thisvalue + '"]').attr('data-port-qty');
        var $assetPortId = '';

        if ($thisvalue.length > 0) {
            _parent.find('span[data-path="connection-path"]').html($trglocationName + ' ' + _parent.find('select#trgPortType option:selected').text());
            _parent.find('input[name="trgcPortInfo[0][]"]').val($trglocationName);
            $assetPortId = _parent.find('select#trgPortType option:selected').attr('data-asset-port-id');
        } else {
            _parent.find('span[data-path="connection-path"]').html($trglocationName);
            _parent.find('input[name="trgcPortInfo[0][]"]').val($trglocationName);
        }

        changePortNumberValue($qty, '', _parent, $thisvalue, $assetPortId);
    }

    function changePortNumberValue($qty, $value, _parent, $thisvalue, $assetPortId) {
        var _selfPort = '';
        if ((_parent.find('#trgcrecordid').val() == <?php echo $this->checkkeyid; ?>) && ($thisvalue == <?php echo $this->dataRow['PORT_TYPE_ID']; ?>)) {
            _selfPort = <?php echo $this->dataRow['PORT_ORDER']; ?>
        }
        $.ajax({
            type: 'post',
            url: 'mdasset/getPortListSelectData',
            dataType: 'json',
            data: {
                checkKeyId: _parent.find('#trgcrecordid').val(),
                portType: $thisvalue,
                srcPort: _selfPort,
                assetPortId: $assetPortId
            },
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {
                $.each(data, function () {
                    _parent.find("select#trgPortNumber").append($("<option />").val(this.ID).text(this.NAME));
                    _parent.find('input[name="trgportid[0][]"]').val(this.ASSET_PORT_ID);
                });

                Core.initSelect2();
                Core.unblockUI();
            },
            error: function (data) {
                alert('Error');
                Core.unblockUI();
            }
        });

        setTimeout(function () {
            _parent.find("select#trgPortNumber").val($value);
        }, 10);
    }

    function getPortNumber(element) {
        var $this = $(element);
        var $thisvalue = $this.val();
        var _parent = $(element).closest('tr');
        if ($thisvalue.length > 0) {
            _parent.find('span[data-path="connection-path"]').html($trglocationName + ' ' + _parent.find('select#trgPortType option:selected').text() + '#' + $thisvalue);
            _parent.find('input[name="trgcPortInfo[0][]"]').val($trglocationName);
        } else {
            _parent.find('span[data-path="connection-path"]').html($trglocationName);
            _parent.find('input[name="trgcPortInfo[0][]"]').val($trglocationName);
        }
    }

    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Target Port">

    function chooseSrcMobiBpMeta(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        var _parent = $(elem).closest('tr');
        if (row.typeid === '9') {
            PNotify.removeAll();
            new PNotify({
                title: 'Холболт хийх боломжгүй',
                text: 'Холболт хийх боломжгүй. Өөр төхөөрөмж сонгоно уу',
                type: 'warning',
                sticker: false
            });
            return;
        } else {
            _parent.find('#srcrecordid').val(row.checkkeyid);
            _parent.find('#srclocationid').val(row.locationid);
            _parent.find('#metaDataId_displayField').val(row.code);
            _parent.find('#metaDataId_nameField').val(row.name);

            $srclocationName = row.directorypath;
            _parent.find('span[data-path="src-connection-path"]').html($srclocationName);
            _parent.find('input[name="srcPortInfo[0][]"]').val($srclocationName);

            var $elementTabId = '#window-mobi-template-connection_<?php echo $this->uniqId ?>';
            changePortTypeDataV2(row.assetid, _parent);
        }
    }


    function changePortTypeDataV2(assetid, _parent) {
        $.ajax({
            type: 'post',
            url: 'mdasset/getConnectionIdData',
            dataType: 'json',
            data: {
                assetId: assetid,
            },
            beforeSend: function () {

                _parent.find("select#srcPortType option:gt(0)").remove();
                _parent.find("select#srcPortNumber option:gt(0)").remove();

                Core.blockUI({
                    target: '#window-mobi-template-connection_<?php echo $this->uniqId ?>',
                    animate: true
                });
            },
            success: function (data) {
                _parent.find("select#srcPortType option:gt(0)").remove();
                $.each(data, function () {
                    _parent.find("select#srcPortType").append($("<option />").val(this.PORT_TYPE_ID).text(this.PORT_TYPE_NAME).attr('data-port-qty', this.PORT_QTY).attr('data-asset-port-id', this.ASSET_PORT_ID));
                });

                Core.initSelect2();
                Core.unblockUI('#window-mobi-template-connection_<?php echo $this->uniqId ?>');
            },
            error: function (data) {
                alert('Error');
                Core.unblockUI('#window-mobi-template-connection_<?php echo $this->uniqId ?>');
            }
        }).done(function () {
            if (typeof srcPortTypeId !== 'undefined') {
                _parent.find("select#srcPortType").val(srcPortTypeId);
                var $qty = _parent.find("select#srcPortType").find('option[value="' + srcPortTypeId + '"]').attr('data-port-qty');
                var $assetPortId = _parent.find("select#srcPortType").find('option[value="' + srcPortTypeId + '"]').attr('data-asset-port-id');
                changeSrcPortNumberValue($qty, srcPort, _parent, '', $assetPortId);
            }
        });
    }

    function changeSrcPortNumber(element) {
        var $this = $(element);
        var $thisvalue = $this.val();
        var _parent = $(element).closest('tr');

        _parent.find("select#srcPortNumber option:gt(0)").remove();
        _parent.find("select#srcPortNumber").trigger("change");

        var $qty = $this.find('option[value="' + $thisvalue + '"]').attr('data-port-qty');
        var $assetPortId = '';

        if ($thisvalue.length > 0) {
            _parent.find('span[data-path="src-connection-path"]').html($srclocationName + ' ' + _parent.find('select#srcPortType option:selected').text());
            _parent.find('input[name="srcPortInfo[0][]"]').val($srclocationName);
            $assetPortId = _parent.find('select#srcPortType option:selected').attr('data-asset-port-id');
        } else {
            _parent.find('span[data-path="src-connection-path"]').html($srclocationName);
            _parent.find('input[name="srcPortInfo[0][]"]').val($srclocationName);
        }

        changeSrcPortNumberValue($qty, '', _parent, $thisvalue, $assetPortId);
    }

    function changeSrcPortNumberValue($qty, $value, _parent, $thisvalue, $assetPortId) {
        var _selfPort = '';
        if ((_parent.find('#srcrecordid').val() == <?php echo $this->checkkeyid; ?>) && ($thisvalue == <?php echo $this->dataRow['PORT_TYPE_ID']; ?>)) {
            _selfPort = <?php echo $this->dataRow['PORT_ORDER']; ?>
        }
        $.ajax({
            type: 'post',
            url: 'mdasset/getPortListSelectData',
            dataType: 'json',
            data: {
                checkKeyId: _parent.find('#srcrecordid').val(),
                portType: $thisvalue,
                srcPort: _selfPort,
                assetPortId: $assetPortId
            },
            beforeSend: function () {

                Core.blockUI({
                    target: '#window-mobi-template-connection_<?php echo $this->uniqId ?>',
                    animate: true
                });
            },
            success: function (data) {
                $.each(data, function () {
                    _parent.find("select#srcPortNumber").append($("<option />").val(this.ID).text(this.NAME));
                    _parent.find('input[name="srcportid[0][]"]').val(this.ASSET_PORT_ID);
                });

                Core.initSelect2();
                Core.unblockUI('#window-mobi-template-connection_<?php echo $this->uniqId ?>');
            },
            error: function (data) {
                alert('Error');
                Core.unblockUI('#window-mobi-template-connection_<?php echo $this->uniqId ?>');
            }
        });

        setTimeout(function () {
            _parent.find("select#srcPortNumber").val($value);
        }, 10);
    }

    function getSrcPortNumber(element) {
        var $this = $(element);
        var $thisvalue = $this.val();
        var _parent = $(element).closest('tr');

        if ($thisvalue.length > 0) {
            _parent.find('span[data-path="src-connection-path"]').html($srclocationName + ' ' + _parent.find('select#srcPortType option:selected').text() + '#' + $thisvalue);
            _parent.find('input[name="srcPortInfo[0][]"]').val($srclocationName);
        } else {
            _parent.find('span[data-path="src-connection-path"]').html($srclocationName);
            _parent.find('input[name="srcPortInfo[0][]"]').val($srclocationName);
        }
    }

    //</editor-fold>


    function addRow(docId) {
        //       Core.blockUI(docId);
        var firstChild = $(docId + ' > tbody > tr:first').html();
        $(docId + ' > tbody:last-child').append('<tr>' + firstChild + '</tr>');
        $(docId + '  > tbody > tr:last .initRequired').each(function (i, elem) {
            $(elem).attr('required', true);
        });
        $('.ordernumberindex').each(function (i, elem) {
            $(elem).val(i);
        });
        Core.unblockUI(docId);
    }
    function removeRow(docId, element) {
        $(element).closest('tr').remove();
        $('.ordernumberindex').each(function (i, elem) {
            $(elem).val(i);
        });
        Core.unblockUI(docId);
    }
    function viewMap(element) {
        $.ajax({
            type: 'post',
            url: 'mdobject/googleMapDataGrid',
            dataType: 'json',
            data: {
                metaDataId: "1529727324487237",
                defaultCriteriaData: "",
                viewType: "gmap",
                isDialog: true,
//                urlParams: '{"installationid":"'+ $installationId +'"}'
                filterRules: JSON.stringify([{"field": "installationid", "op": "=", "value": $installationId}])
            },
            beforeSend: function () {

                Core.blockUI({
                    target: '#window-mobi-template-connection_<?php echo $this->uniqId ?>',
                    animate: true
                });
            },
            success: function (data) {
                var $dialogName = 'dialog-connection-port-map';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);
                $dialog.empty().append('<div id="md-map-connection-canvas-' + data.metaDataId + '" style="width:100%; height:100%; overflow:auto; position: relative; margin: 0; padding: 0;"></div>').promise().done(function () {
                    if (window.google && google.maps) {
                        googleMapConnectionViewLoad(data);
                    } else {
                        $.getScript("https://maps.google.com/maps/api/js?sensor=true&key=" + gmapApiKey + "&language=mn").done(function () {
                            googleMapConnectionViewLoad(data);
                        });
                    }
                });
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1000,
                    height: 'auto',
                    modal: true,
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: "Хаах", class: 'btn blue-madison btn-sm', click: function () {
                                $dialog.dialog('close');
                            }
                        }
                    ]
                }).dialogExtend({
                    "closable": true,
                    "maximizable": true,
                    "minimizable": true,
                    "collapsable": true,
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

                $dialog.dialog('open');
                $dialog.dialogExtend('maximize');
                Core.unblockUI('#window-mobi-template-connection_<?php echo $this->uniqId ?>');
            },
            error: function (data) {
                alert('Error');
                Core.unblockUI('#window-mobi-template-connection_<?php echo $this->uniqId ?>');
            }
        });
    }
    function googleMapConnectionViewLoad(data) {
        var defaultDrawDataList = data;
        var gotoLocation;
        var strokeOpacity = 0.8;
        var strokeWeight = 2;
        var fillOpacity = 0.5;
        var shapeOptions = {
            strokeColor: '#1e90ff',
            strokeOpacity: strokeOpacity,
            strokeWeight: strokeWeight,
            fillColor: '#1e90ff',
            fillOpacity: fillOpacity,
            editable: true
        };

        $("link[href='assets/custom/addon/admin/pages/css/todo.css']").remove();
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/admin/pages/css/todo.css"/>');
        $("link[href='assets/custom/addon/plugins/google-map/googleMap.css']").remove();
        $('head').append('<link rel="stylesheet" href="assets/custom/addon/plugins/google-map/googleMap.css" type="text/css" />');
        //$("#md-map-connection-canvas-" + defaultDrawDataList.metaDataId).parent().append('<div id="md-map-connection-legend-' + defaultDrawDataList.metaDataId + '" style="background: #fff;padding: 10px;margin: 10px;border: 2px solid #000; font-size:13px; display:none"></div>');

        var mapProp = {
            center: new google.maps.LatLng(47.919128, 106.917609),
            zoom: 6,
            mapTypeControl: true,
            disableDefaultUI: true,
            mapTypeControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER
            },
            panControl: !0,
            panControlOptions: {
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            zoomControl: !0,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.LARGE,
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            streetViewControl: !0,
            streetViewControlOptions: {
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            scaleControl: !0,
            scaleControlOptions: {
                position: google.maps.ControlPosition.RIGHT_BOTTOM
            }
        };
        map = new google.maps.Map(document.getElementById("md-map-connection-canvas-" + defaultDrawDataList.metaDataId), mapProp);
        var bounds = new google.maps.LatLngBounds();

        if (typeof defaultDrawDataList.coordinate != 'undefined') {

            $.each(defaultDrawDataList.coordinate, function (index, row) {
                var _this = row;
                if (_this.IS_DYNAMIC == '0') {
                    if (_this.DRAW_TYPE == 'MARKER' || _this.DRAW_TYPE == 'SERVICE') {

                        var infowindow = new google.maps.InfoWindow();

                        $.each(_this.GMAPDATA, function (key, value) {
                            var row = this;
                            var rowData = JSON.parse(row.rowData);

                            if ('nextpolyline' in Object(rowData) && rowData.nextpolyline) {
                                var coord = rowData.nextpolyline.split('|');
                                var path = [];

                                path.push({lat: Number(row.lat), lng: Number(row.lng)});
                                path.push({lat: Number(coord[1]), lng: Number(coord[0])});

                                var pcolor = ['#00802b', '#00802b', '#00802b'];

                                var polylinePath = new google.maps.Polyline({
                                    path: path,
                                    geodesic: true,
                                    strokeOpacity: 1,
                                    strokeColor: '#00802b',
                                    strokeWeight: 1,
                                    metaDataId: _this.META_DATA_ID,
                                    metaGoogleMapLinkId: row.metaGoogleMapLinkId,
                                    actionMetaDataId: row.actionMetaDataId,
                                    actionMetaTypeId: row.actionMetaTypeId,
                                    metaValueId: row.META_VALUE_ID,
                                    rowData: row.rowData
                                });

                                polylinePath.setMap(map);

                                google.maps.event.addListener(polylinePath, 'mouseover', function () {
                                    polylinePath.setOptions({strokeColor: '#00FFAA', strokeWeight: 7});
                                });

                                google.maps.event.addListener(polylinePath, 'mouseout', function () {
                                    polylinePath.setOptions({strokeColor: '#00802b', strokeWeight: 1});
                                });

                                google.maps.event.addListener(polylinePath, 'click', function (event) {
                                    var rowData = JSON.parse(row.rowData);

                                    if ('lineactionmetatypeid' in Object(rowData) && rowData.lineactionmetatypeid == 'popupDataview') {
                                        $.ajax({
                                            type: 'post',
                                            url: 'mddatamodel/renderGmapInfoWindowByDv',
                                            data: {dvId: rowData.lineactionmetadataid, rowData: rowData},
                                            dataType: 'json',
                                            beforeSend: function () {
                                                Core.blockUI({
                                                    message: 'Loading...',
                                                    boxed: true
                                                });
                                            },
                                            success: function (data) {
                                                infowindow.setContent(data.html);
                                                infowindow.setPosition(event.latLng);
                                                infowindow.open(map);
                                                Core.unblockUI();
                                            }
                                        });
                                    }
                                });
                            }

                        });

                        google.maps.event.addListener(map, 'click', function () {
                            infowindow.close();
                        });

                        $.each(_this.GMAPDATA, function () {

                            var row = this, contentString = '';

                            if (typeof row.META_VALUE_CODE != 'undefined') {
                                contentString = '<strong>' + row.META_VALUE_CODE + '</strong><br />' + row.META_VALUE_NAME;
                            }

                            var rowData = JSON.parse(row.rowData);
                            contentString = '<div style="width: 300px;"><img width="80px" src="' + rowData.profile +
                                    '" align="left" style="margin-right:5px"><div style="padding-left: 85px;"><b style="text-transform:uppercase;">' +
                                    (row.META_VALUE_NAME ? row.META_VALUE_NAME : '') + '</b><br /><br />' + (rowData.description ? rowData.description : '') + '<br><a href="javascript:;" onClick="googleMapMarkerMoreLink(' + _this.META_DATA_ID + ', \'' + encodeURIComponent(row.rowData) + '\')">Дэлгэрэнгүй...</a></div></div>';

                            var marker = new google.maps.Marker({
                                position: new google.maps.LatLng(row.lat, row.lng),
                                icon: {
                                    url: rowData.markerphoto,
                                    scaledSize: new google.maps.Size(42, 42)
                                },
                                animation: google.maps.Animation.DROP,
                                map: map,
                                title: (rowData.name ? rowData.name : ''),
                                metaDataId: _this.META_DATA_ID,
                                metaGoogleMapLinkId: row.metaGoogleMapLinkId,
                                actionMetaDataId: row.actionMetaDataId,
                                actionMetaTypeId: row.actionMetaTypeId,
                                metaValueId: row.META_VALUE_ID,
                                rowData: row.rowData
                            });

                            gotoLocation = new google.maps.LatLng(row.lat, row.lng);
                            bounds.extend(gotoLocation);

                            marker.addListener('click', (function (marker, contentString) {
                                return function () {
                                    infowindow.setContent(contentString);
                                    infowindow.open(map, marker);
                                };
                            })(marker, contentString));

                        });

                    }

                }
            });
        }

        map.setCenter(map.getCenter());
        map.setZoom(7);
        //map.fitBounds(bounds);
//        map.panToBounds(bounds);
    }
</script>