<?php echo Form::create(array('id'=>'metaProcessExportForm', 'name'=>'metaProcessExportForm', 'method'=>'post', 'action'=>'javascript:;', 'class'=>'form-horizontal', 'enctype'=>'multipart/form-data'));?>
<div class="col-md-12">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Мета', 'for' => 'isOverride', 'class' => 'col-form-label col-md-2')); ?>
        <div class="col-md-10 text-align-left">
            <label class="checkbox-list"><input type="checkbox" name="isOverride" id="isOverride" class="isOverride" value="0"> Дагалдсан метаг экспортлох</label>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>

<script type="text/javascript">
$(function(){
    $('#isOverride').on("click", function(){
        var $this = $(this);
        if ($this.attr('checked')) {
            $this.val(1);
        } else {
            $this.val(0);
        }
    });
});
</script>

