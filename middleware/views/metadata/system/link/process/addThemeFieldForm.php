<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'add-process-theme-form', 'method' => 'post')); ?>
<?php 
    echo Form::hidden(array('id'=>'metaDataId', 'name'=>'metaDataId', 'value'=>$this->metaDataId));
?>
<div class="col-md-12">
    <div class="form-group row fom-row">
        <label class="col-md-4 col-form-label">Theme position: </label>
        <div class="col-md-8">
            <?php
            $themeFieldArray = array();
            for ($i = 1; $i<=20; $i++) {
                array_push($themeFieldArray, array('ID' => 'header-position-' . $i, 'NAME' => 'Header position #' . $i));
            }
            array_push($themeFieldArray, array('ID' => 'group-position', 'NAME' => 'Group position'));
            echo Form::select(
                array(
                    'name' => 'themeField',
                    'id' => 'themeField',
                    'class' => 'form-control form-control-sm select2',
                    'data' => $themeFieldArray,
                    'op_value' => 'ID',
                    'op_text' => 'NAME'
                )
            );
            ?>
        </div>
        <div class="clearfix w-100"></div>
    </div>
    <div class="form-group row fom-row">
        <label class="col-md-4 col-form-label">Process field: </label>
        <div class="col-md-8">
            <?php
            echo Form::select(
                array(
                    'name' => 'processField',
                    'id' => 'processField',
                    'class' => 'form-control form-control-sm select2',
                    'data' => $this->getProcessFieldList,
                    'op_value' => 'PARAM_REAL_PATH',
                    'op_text' => 'PARAM_REAL_PATH| |-| |META_DATA_NAME'
                )
            );
            ?>
        </div>
        <div class="clearfix w-100"></div>
    </div>
    <div class="form-group row fom-row">
        <label class="col-md-4 col-form-label">Tab name: </label>
        <div class="col-md-8">
            <?php
            echo Form::text(
                array(
                    'name' => 'tabName',
                    'id' => 'tabName',
                    'class' => 'form-control form-control-sm'
                )
            );
            ?>
        </div>
        <div class="clearfix w-100"></div>
    </div>
    <div class="form-group row fom-row">
        <label class="col-md-4 col-form-label"><?php echo $this->lang->line('META_00080'); ?> </label>
        <div class="col-md-8">
            <?php
            echo Form::text(
                array(
                    'name' => 'orderNum',
                    'id' => 'orderNum',
                    'class' => 'form-control form-control-sm bigdecimalInit',
                    'style' => 'width: 50px;'
                )
            );
            ?>
        </div>
        <div class="clearfix w-100"></div>
    </div>
    <div class="form-group row fom-row">
        <label class="col-md-4 col-form-label">Is label: </label>
        <div class="col-md-8">
            <?php
            echo Form::checkbox(
                array(
                    'name' => 'isLabel',
                    'id' => 'isLabel',
                    'class' => 'form-control',
                    'value' => '0',
                    'onclick' => 'changeLabelValue(this)'
                )
            );
            ?>
        </div>
        <div class="clearfix w-100"></div>
    </div>
    
</div>
<?php echo Form::close(); ?>

<script type="text/javascript">
    function changeLabelValue(elem) {
        if ($(elem).is(':checked')) {
            $(elem).val('1');
        }else {
            $(elem).val('0');
        }
    }
</script>