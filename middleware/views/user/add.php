<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="xs-form">
<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'addUser-form', 'method' => 'post')); ?>
<div class="col-md-12">
    <fieldset class="collapsible">
        <legend>Хэрэглэгчийн мэдээлэл</legend>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text'=>'Хэрэглэгчийн нэр', 'for'=>'USERNAME', 'class'=>'col-form-label col-md-3', 'required'=>'required')); ?>
            <div class="col-md-3">
                <?php echo Form::text(array('name'=>'USERNAME', 'id'=>'USERNAME', 'class'=>'form-control text-right', 'required'=>'required')); ?>
            </div>
            <div class="col-md-6">
                <div class="form-group row fom-row mb0">
                    <?php echo Form::label(array('text'=>'Нууц үг', 'for'=>'PASSWORD_HASH', 'class'=>'col-form-label col-md-5')); ?>
                    <div class="col-md-6">
                        <?php echo Form::text(array('name'=>'PASSWORD_HASH', 'id'=>'PASSWORD_HASH', 'class'=>'form-control text-right')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text'=>'', 'class'=>'col-form-label col-md-3','no_colon'=>'no_colon')); ?>
            <div class="col-md-4">
                <div class="checkbox-list">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="IS_VAT" id="IS_VAT" value="1"> НӨАТ-тэй эсэх
                    </label>
                </div>    
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text'=>'НӨАТ-гүй үнэ', 'for'=>'NO_VAT_PRICE', 'class'=>'col-form-label col-md-3')); ?>
            <div class="col-md-3">
                <?php echo Form::text(array('name'=>'NO_VAT_PRICE', 'id'=>'NO_VAT_PRICE', 'class'=>'form-control text-right', 'readonly'=>'readonly')); ?>
            </div>
            <div class="col-md-6">
                <div class="form-group row fom-row mb0">
                    <?php echo Form::label(array('text'=>'НӨАТ-тэй үнэ', 'for'=>'VAT_PRICE', 'class'=>'col-form-label col-md-5')); ?>
                    <div class="col-md-6">
                        <?php echo Form::text(array('name'=>'VAT_PRICE', 'id'=>'VAT_PRICE', 'class'=>'form-control text-right', 'readonly'=>'readonly')); ?>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</div>
<?php echo Form::close(); ?>
</div>    

<script type="text/javascript">
$(function() {
    $.ui.dialog.prototype._allowInteraction = function(e) {
         return !!$(e.target).closest('.ui-dialog, .ui-datepicker, .select2-dropdown').length;
    };
});
</script>    