<div class="wfm-buttons-preview pb10">
<?php
foreach ($this->statusList as $row) {
    
    $icon          = ($row['wfmstatusicon'] ? $row['wfmstatusicon'] : 'fa-cog');
    $wfmStatusCode = issetParam($row['wfmstatuscode']); 
    $wfmIsNeedSign = $row['wfmisneedsign'];
    $wfmStatusName = $row['wfmstatusname'];
    
    if (!$row['wfmstatusprocessid']) {
        
        if ($wfmIsNeedSign == '1') {
            
            $clickFnc = "beforeSignChangeWfmStatusId(this, '".$row['wfmstatusid']."', '".$this->metaDataId."', '".$this->refStructureId."', '".$row['wfmstatuscolor']."', '".$row['wfmstatusname']."');";
            $wfmStatusName .= ' <i class="fa fa-key"></i>';
            
        } elseif ($wfmIsNeedSign == '2') {
            
            $clickFnc = "beforeHardSignChangeWfmStatusId(this, '".$row['wfmstatusid']."', '".$this->metaDataId."', '".$this->refStructureId."', '".$row['wfmstatuscolor']."', '".$row['wfmstatusname']."');";
            $wfmStatusName .= ' <i class="fa fa-key"></i>';
            
        } elseif ($wfmIsNeedSign == '3') {
            
            $clickFnc = "cloudSignChangeWfmStatusId(this, '".$row['wfmstatusid']."', '".$this->metaDataId."', '".$this->refStructureId."', '".$row['wfmstatuscolor']."', '".$row['wfmstatusname']."');";
            $wfmStatusName .= ' <i class="fa fa-key"></i>';
            
        } elseif ($wfmIsNeedSign == '4') {
            
            $clickFnc = "pinCodeChangeWfmStatusId(this, '".$row['wfmstatusid']."', '".$this->metaDataId."', '".$this->refStructureId."', '".$row['wfmstatuscolor']."', '".$row['wfmstatusname']."');";
            $wfmStatusName .= ' <i class="fa fa-key"></i>';
            
        } elseif ($wfmIsNeedSign == '6') {
            
            $clickFnc = "otpChangeWfmStatusId(this, '".$row['wfmstatusid']."', '".$this->metaDataId."', '".$this->refStructureId."', '".$row['wfmstatuscolor']."', '".$row['wfmstatusname']."');";
            $wfmStatusName .= ' <i class="fa fa-key"></i>';
            
        } else {
            $clickFnc = "changeWfmStatusId(this, '".$row['wfmstatusid']."', '".$this->metaDataId."', '".$this->refStructureId."', '".$row['wfmstatuscolor']."', '".$row['wfmstatusname']."', {cyphertext: '', plainText: ''});";
        } 
 
    } else {
        
        if ($wfmIsNeedSign == '4') {
            $clickFnc = "transferProcessAction('pinCode', '".$this->metaDataId."', '".$row['wfmstatusprocessid']."', '".Mdmetadata::$businessProcessMetaTypeId."', 'toolbar', this, {callerType: 'filePreview', isWorkFlow: true, wfmStatusId: '".$row['wfmstatusid']."', wfmStatusCode: '".$wfmStatusCode."'}, 'dataViewId=".$this->metaDataId."&refStructureId=".$this->refStructureId."&statusId=".$row['wfmstatusid']."&statusName=".$row['wfmstatusname']."&statusColor=".$row['wfmstatuscolor']."&rowId=".$this->rowId."');";
            $wfmStatusName .= ' <i class="fa fa-key"></i>';
        } elseif ($wfmIsNeedSign == '6') {
            $clickFnc = "transferProcessAction('otp', '".$this->metaDataId."', '".$row['wfmstatusprocessid']."', '".Mdmetadata::$businessProcessMetaTypeId."', 'toolbar', this, {callerType: 'filePreview', isWorkFlow: true, wfmStatusId: '".$row['wfmstatusid']."', wfmStatusCode: '".$wfmStatusCode."'}, 'dataViewId=".$this->metaDataId."&refStructureId=".$this->refStructureId."&statusId=".$row['wfmstatusid']."&statusName=".$row['wfmstatusname']."&statusColor=".$row['wfmstatuscolor']."&rowId=".$this->rowId."');";
            $wfmStatusName .= ' <i class="fa fa-key"></i>';
        } elseif ($wfmIsNeedSign == '7') {
            $clickFnc = "transferProcessAction('watermark', '".$this->metaDataId."', '".$row['wfmstatusprocessid']."', '".Mdmetadata::$businessProcessMetaTypeId."', 'toolbar', this, {callerType: 'filePreview', isWorkFlow: true, wfmStatusId: '".$row['wfmstatusid']."', wfmStatusCode: '".$wfmStatusCode."'}, 'dataViewId=".$this->metaDataId."&refStructureId=".$this->refStructureId."&statusId=".$row['wfmstatusid']."&statusName=".$row['wfmstatusname']."&statusColor=".$row['wfmstatuscolor']."&rowId=".$this->rowId."');";
            $wfmStatusName .= ' <i class="fa fa-key"></i>';
        } else {
            $clickFnc = "transferProcessAction('', '".$this->metaDataId."', '".$row['wfmstatusprocessid']."', '".Mdmetadata::$businessProcessMetaTypeId."', 'toolbar', this, {callerType: 'filePreview', isWorkFlow: true, wfmStatusId: '".$row['wfmstatusid']."', wfmStatusCode: '".$wfmStatusCode."'}, 'dataViewId=".$this->metaDataId."&refStructureId=".$this->refStructureId."&statusId=".$row['wfmstatusid']."&statusName=".$row['wfmstatusname']."&statusColor=".$row['wfmstatuscolor']."&rowId=".$this->rowId."');";
        }
    }
    
    echo '<a href="javascript:;" class="btn btn-circle btn-sm mr5" style="background-color: '.$row['wfmstatuscolor'].'; color: #fff;" onclick="'.$clickFnc.'"><i class="fa '.$icon.'"></i> '.$wfmStatusName.'</a>';
} 
?>
</div>