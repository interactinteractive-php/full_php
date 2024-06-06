<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'copybp-template-form', 'method' => 'post')); ?>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Код', 'for' => 'templateCode', 'class' => 'col-form-label col-md-3', 'required'=>'required')); ?>
        <div class="col-md-8">
            <?php echo Form::text(array('name' => 'templateCode', 'id' => 'templateCode', 'value' => $this->row['TEMPLATE_CODE'], 'class'=>'form-control form-control-sm', 'required'=>'required')); ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Нэр', 'for' => 'templateName', 'class' => 'col-form-label col-md-3', 'required'=>'required')); ?>
        <div class="col-md-8">
            <?php echo Form::text(array('name' => 'templateName', 'id' => 'templateName', 'value' => $this->row['TEMPLATE_NAME'], 'class'=>'form-control form-control-sm', 'required'=>'required')); ?>
        </div>
    </div>
</div>
<?php 
echo Form::hidden(array('name' => 'id', 'value' => $this->row['ID'])); 
echo Form::close(); 
?>