<form class="form-horizontal" method="post" enctype="multipart/form-data" action="social/updateGroup" id="scl-edit-group">
    <div class="form-body">
        <div class="form-group row fom-row">
            <label class="col-md-12 font-weight-bold">* Бүлгийн нэр</label>
            <div class="col-md-12">
                <input type="text" name="name" class="form-control" required="required" value="<?php echo $this->row['GROUP_NAME']; ?>">
            </div>
        </div>
        <div class="form-group row fom-row">
            <label class="col-md-12 font-weight-bold">* Бүлгийн тухай</label>
            <div class="col-md-12">
                <textarea name="descr" class="form-control" rows="3" required="required"><?php echo $this->row['DESCRIPTION']; ?></textarea>
            </div>
        </div>
        <div class="form-group row fom-row">
            <label class="col-md-12 font-weight-bold">* Бүлгийн нууцлал</label>
            <div class="col-md-6">
                <?php
                echo Form::select(
                    array(
                        'name' => 'privacyType', 
                        'class' => 'form-control', 
                        'required' => 'required', 
                        'data' => array(
                            array(
                                'id' => 'public', 
                                'name' => 'Нээлттэй'
                            ), 
                            array(
                                'id' => 'closed', 
                                'name' => 'Хаалттай'
                            )
                        ), 
                        'op_value' => 'id', 
                        'op_text' => 'name', 
                        'value' => $this->row['PRIVACY_TYPE']
                    )
                );
                ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <label class="col-md-12 font-weight-bold">Бүлгийн зураг</label>
            <div class="col-md-12">
                <input type="file" name="cover" class="form-control" accept="image/jpeg,image/png,image/gif">
            </div>
        </div>
    </div>
    <input type="hidden" name="groupId" value="<?php echo $this->groupId; ?>">
</form>