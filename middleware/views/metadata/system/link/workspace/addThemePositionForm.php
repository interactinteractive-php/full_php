<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php 
echo Form::create(array('class' => 'form-horizontal', 'id' => 'add-theme-position-form', 'method' => 'post')); 

    echo Form::hidden(array('id'=>'metaDataId', 'name' => 'metaDataId', 'value' => $this->metaDataId));
    echo Form::hidden(array('id'=>'targetMetaId', 'name' => 'targetMetaId', 'value' => $this->targetMetaId));
?>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row">
        <label class="col-md-4 col-form-label">Theme position:</label>
        <div class="col-md-8">
            <?php
            echo Form::select(
                array(
                    'name' => 'paramPath',
                    'id' => 'paramPath',
                    'class' => 'form-control form-control-sm select2',
                    'data' => array(
                        array('ID' => 'header-position-1', 'NAME' => 'Header position #1'),
                        array('ID' => 'header-position-2', 'NAME' => 'Header position #2'),
                        array('ID' => 'header-position-3', 'NAME' => 'Header position #3'),
                        array('ID' => 'header-position-4', 'NAME' => 'Header position #4'),
                        array('ID' => 'header-position-5', 'NAME' => 'Header position #5'),
                        array('ID' => 'header-position-6', 'NAME' => 'Header position #6'),
                        array('ID' => 'header-position-7', 'NAME' => 'Header position #7'),
                        array('ID' => 'header-position-8', 'NAME' => 'Header position #8'),
                        array('ID' => 'header-position-9', 'NAME' => 'Header position #9'),
                        array('ID' => 'header-position-10', 'NAME' => 'Header position #10'),
                        array('ID' => 'header-position-11', 'NAME' => 'Header position #11'),
                        array('ID' => 'header-position-12', 'NAME' => 'Header position #12'),
                        array('ID' => 'header-position-13', 'NAME' => 'Header position #13'),
                        array('ID' => 'header-position-14', 'NAME' => 'Header position #14'),
                        array('ID' => 'header-position-15', 'NAME' => 'Header position #15'),
                        array('ID' => 'header-position-16', 'NAME' => 'Header position #16'),
                        array('ID' => 'header-position-17', 'NAME' => 'Header position #17'),
                        array('ID' => 'header-position-18', 'NAME' => 'Header position #18'),
                        array('ID' => 'header-position-19', 'NAME' => 'Header position #19'),
                        array('ID' => 'header-position-20', 'NAME' => 'Header position #20'),
                        array('ID' => 'header-position-21', 'NAME' => 'Header position #21'),
                        array('ID' => 'header-position-22', 'NAME' => 'Header position #22'),
                        array('ID' => 'header-position-23', 'NAME' => 'Header position #23'),
                        array('ID' => 'header-position-24', 'NAME' => 'Header position #24'),
                        array('ID' => 'header-position-25', 'NAME' => 'Header position #25'),
                        array('ID' => 'header-position-26', 'NAME' => 'Header position #26'),
                        array('ID' => 'header-position-27', 'NAME' => 'Header position #27'),
                        array('ID' => 'header-position-28', 'NAME' => 'Header position #28'),
                        array('ID' => 'header-position-29', 'NAME' => 'Header position #29'),
                        array('ID' => 'header-position-30', 'NAME' => 'Header position #30'),
                        array('ID' => 'header-position-31', 'NAME' => 'Header position #31'),
                        array('ID' => 'header-position-32', 'NAME' => 'Header position #32'),
                        array('ID' => 'header-position-33', 'NAME' => 'Header position #33'),
                        array('ID' => 'header-position-34', 'NAME' => 'Header position #34'),
                        array('ID' => 'header-position-35', 'NAME' => 'Header position #35'),
                        array('ID' => 'header-position-36', 'NAME' => 'Header position #36'),
                        array('ID' => 'header-position-37', 'NAME' => 'Header position #37'),
                        array('ID' => 'header-position-38', 'NAME' => 'Header position #38'),
                        array('ID' => 'header-position-39', 'NAME' => 'Header position #39'),
                        array('ID' => 'header-position-40', 'NAME' => 'Header position #40'),
                        array('ID' => 'header-position-41', 'NAME' => 'Header position #41'),
                        array('ID' => 'header-position-42', 'NAME' => 'Header position #42'),
                        array('ID' => 'header-position-43', 'NAME' => 'Header position #43'),
                        array('ID' => 'header-position-44', 'NAME' => 'Header position #44'),
                        array('ID' => 'header-position-45', 'NAME' => 'Header position #45'),
                        array('ID' => 'header-position-46', 'NAME' => 'Header position #46'),
                        array('ID' => 'cover', 'NAME' => 'Cover image'),
                    ),
                    'op_value' => 'ID',
                    'op_text' => 'NAME'
                )
            );
            ?>
        </div>
        <div class="clearfix w-100"></div>
    </div>
    <div class="form-group row fom-row">
        <label class="col-md-4 col-form-label">DV parameter:</label>
        <div class="col-md-8">
            <?php
            echo Form::select(
                array(
                    'name' => 'fieldPath',
                    'id' => 'fieldPath',
                    'class' => 'form-control form-control-sm select2',
                    'data' => $this->getDVParameterList,
                    'op_value' => 'FIELD_PATH',
                    'op_text' => 'META_DATA_NAME', 
                    'translationText' => true
                )
            );
            ?>
        </div>
        <div class="clearfix w-100"></div>
    </div>
    <div class="form-group row fom-row">
        <label class="col-md-4 col-form-label">Label name:</label>
        <div class="col-md-8">
            <?php
            echo Form::text(
                array(
                    'name' => 'labelName',
                    'id' => 'labelName',
                    'class' => 'form-control form-control-sm globeCodeInput'
                )
            );
            ?>
        </div>
        <div class="clearfix w-100"></div>
    </div>
</div>
<?php echo Form::close(); ?>