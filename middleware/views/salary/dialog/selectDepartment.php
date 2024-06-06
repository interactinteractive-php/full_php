<div class="row">
    <div class="col-md-12">
        <br/>
        <center><label>Ажилчидын мэдээллийг аль хэлтэсийн цалин бодолтод оноохыг зааж өгнө үү?</label></center>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <label class="customLabel col-md-4" for="departmentId" style="text-align: left !important;">Алба хэлтэс:</label>
        <div class="col-md-6">
                <?php
                echo Form::select(
                        array(
                            'name' => 'departmentId',
                            'id' => 'departmentId_'.$this->uniqId,
                            'class' => 'form-control select2 form-control-sm input-xxlarge',
                            'data' => $this->selectDepartments,
                            'op_value' => 'DEPARTMENT_ID',
                            'op_text' => 'DEPARTMENT_NAME',
                            'required' => 'required'
                        )
                );
                ?>
        </div>
    </div>
</div>

