<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'cat-lock-form', 'method' => 'post')); ?>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Харъяалагдах категори', 'for' => 'categoryId', 'class' => 'col-form-label col-md-4')); ?>
        <div class="col-md-8">
            <?php 
            echo Form::select(
                array(
                    'name' => 'categoryId', 
                    'id' => 'categoryId', 
                    'class' => 'form-control form-control-sm select2', 
                    'data' => $this->categoryList, 
                    'op_value' => 'ID', 
                    'op_text' => 'CAT_NAME', 
                    'value' => $this->row['PARENT_ID'] 
                )
            ); 
            ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Нэр', 'for' => 'categoryName', 'class' => 'col-form-label col-md-4', 'required' => 'required')); ?>
        <div class="col-md-8">
            <?php 
            echo Form::text(
                array(
                    'name' => 'categoryName', 
                    'id' => 'categoryName', 
                    'class' => 'form-control form-control-sm', 
                    'required' => 'required', 
                    'value' => $this->row['NAME']
                )
            ); 
            ?>
        </div>
    </div>
</div>
<?php 
echo Form::hidden(array('name' => 'id', 'value' => $this->categoryId));
echo Form::close(); 
?>