<?php echo Form::create(array('id' => 'testCaseForm', 'method' => 'post', 'class' => 'form-horizontal')); ?>
    <div class="form-group row mb8">
        <label class="col-form-label col-md-3" for="testCaseSystemId"><span class="required">*</span>Систем:</label>
        <div class="col-md-9">
            <?php
            echo Form::select(
                array(
                    'required' => 'required',
                    'name'     => 'testCaseSystemId',
                    'id'       => 'testCaseSystemId',
                    'class'    => 'form-control select2 form-control-sm input-xxlarge',
                    'data'     => $this->systemList,
                    'op_value' => 'id',
                    'op_text'  => 'systemname'
                )
            );
            ?>
        </div>
    </div>
    <hr class="mt3 mb-2">
    <div class="form-group row mb8">
        <label class="col-form-label col-md-3" for="testCaseName"><span class="required">*</span>Тест кэйсийн нэр:</label>
        <div class="col-md-9">
            <input type="text" id="testCaseName" name="testCaseName" class="form-control form-control-sm" required="required" />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-form-label col-md-3" for="testCaseModeId"><span class="required">*</span>Тест кэйсийн төрөл:</label>
        <div class="col-md-5">
            <?php
            echo Form::select(
                array(
                    'required' => 'required',
                    'name'     => 'testCaseModeId',
                    'id'       => 'testCaseModeId',
                    'class'    => 'form-control select2 form-control-sm input-xxlarge',
                    'data'     => $this->typeList,
                    'op_id'    => 'id',
                    'op_value' => 'id',
                    'op_param' => 'id',
                    'op_text'  => 'name'
                )
            );
            ?>
        </div>
    </div>   
<?php echo Form::close(); ?>