<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'cat-edit-form', 'method' => 'post')); ?>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Категори', 'for' => 'categoryId', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-9">
            <?php 
            echo Form::select(
                array(
                    'name' => 'categoryId', 
                    'id' => 'categoryId', 
                    'class' => 'form-control form-control-sm select2', 
                    'data' => $this->categoryList, 
                    'op_value' => 'ID', 
                    'op_text' => 'CAT_NAME', 
                    'required' => 'required'
                )
            ); 
            ?>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>