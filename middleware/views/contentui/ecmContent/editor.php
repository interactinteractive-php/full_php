<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'ecmcontent-form', 'method' => 'post')); ?>
<div class="col-md-12 xs-form">
    <textarea name="ecmContentBody" id="ecmContentBody" rows="15"><?php echo $this->contentBody; ?></textarea>
</div>
<?php 
echo Form::hidden(array('name' => 'recordId', 'value' => $this->recordId));  
echo Form::close(); 
?>