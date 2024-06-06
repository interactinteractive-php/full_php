<div class="form-group row fom-row mt10">
    <?php echo Form::label(array('text'=>$this->lang->line('Баталгаажуулах дүрэм'),'class' => 'col-md-4','for'=>'param[wfmRuleId]')); ?>
    <div class="col-md-8">
    <?php 
    echo Form::select(
        array(
            'name' => 'param[wfmRuleId]',
            'id' => 'param[wfmRuleId]',
            'class' => 'form-control form-control-sm select2',
            'data' => $this->rulesData,
            'op_value' => $this->mainMetaDataValue, 
            'op_text' => $this->mainMetaDataName,
            'value' => $this->wfmRuleId
        )
    ); 
    ?>
    </div>
</div>
<div class="form-group row fom-row">
    <?php echo Form::label(array('text'=>'Хүлээх хугацаа /цаг/','class' => 'col-md-4', 'for'=>'waitTime')); ?> 
    <div class="col-md-8">
        <?php echo Form::text(array('name'=>'waitTime','id'=>'waitTime','class'=>'form-control form-control-sm longInit', 'style'=>'width:80px', 'value' => Input::post('waitTime'))); ?>
    </div>    
</div>
<div class="form-group row fom-row">
    <?php echo Form::label(array('text'=>$this->lang->line('Шилжүүлэх төлөв'),'class'=>'col-md-4', 'for'=>'waitStatusId')); ?>
    <div class="col-md-8">
    <?php 
    echo Form::select(
        array(
            'name' => 'waitStatusId',
            'id' => 'waitStatusId',
            'class' => 'form-control form-control-sm select2',
            'data' => $this->wfmStatusButtons,
            'op_value' => 'wfmstatusid', 
            'op_text' => 'wfmstatusname',
            'value' => Input::post('waitTimeStatusId')
        )
    ); 
    ?>
    </div>
</div>