<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'request-accept-form', 'method' => 'post')); ?>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Хүсэлт илгээсэн', 'class' => 'col-form-label col-md-4')); ?>
        <div class="col-md-8">
            <?php echo $this->row['USERNAME']; ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Дуусах хугацаа', 'for' => 'endTime', 'class' => 'col-form-label col-md-4', 'required' => 'required')); ?>
        <div class="col-md-5">
            <?php
            echo Form::text(
                array(
                    'name' => 'endTime',
                    'id' => 'endTime',
                    'class' => 'form-control form-control-sm datetimeInit', 
                    'required' => 'required', 
                    'value' => $this->row['END_TIME']
                )
            );
            ?>
        </div>
    </div>
</div>
<?php 
echo Form::hidden(array('name'=>'id','value'=>$this->id)); 
echo Form::hidden(array('name'=>'metaDataId','value'=>$this->row['META_DATA_ID']));
echo Form::hidden(array('name'=>'userId','value'=>$this->row['CREATED_USER_ID']));
echo Form::hidden(array('name'=>'licenserUserId','value'=>$this->row['LICENSER_USER_ID']));
echo Form::close(); 
?>