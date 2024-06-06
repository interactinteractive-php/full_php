<form class="form-horizontal">
    <div class="form-body xs-form">
        <div class="row warehouse-header-row">
            <div class="col-md-9 warehouse-header-content">
                <div class="col-md-2">
                    <a href="javascript:;" class="float-left thumb-lg avatar border">
                        <img src="storage/uploads/ERP-150x150.jpg" class="img-custom-circle">                                
                    </a>
                </div>
                <div class="col-md-5">
                    <?php
                    if ($row = Mdobject::getFeatureCellIndex($this->headerRow, '1')) {
                    ?>
                    <div class="form-group row fom-row">
                        <?php echo Form::label(array('text' => $row['labelName'], 'class' => 'col-form-label col-md-4')); ?>
                        <div class="col-md-8">
                            <p class="form-control-plaintext font-weight-bold"><?php echo $row['value']; ?></p>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                    <?php
                    if ($row = Mdobject::getFeatureCellIndex($this->headerRow, '2')) {
                    ?>
                    <div class="form-group row fom-row">
                        <?php echo Form::label(array('text' => $row['labelName'], 'class' => 'col-form-label col-md-4')); ?>
                        <div class="col-md-8">
                            <p class="form-control-plaintext font-weight-bold"><?php echo $row['value']; ?></p>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                    <?php
                    if ($row = Mdobject::getFeatureCellIndex($this->headerRow, '3')) {
                    ?>
                    <div class="form-group row fom-row">
                        <?php echo Form::label(array('text' => $row['labelName'], 'class' => 'col-form-label col-md-4')); ?>
                        <div class="col-md-8">
                            <p class="form-control-plaintext font-weight-bold"><?php echo $row['value']; ?></p>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="col-md-5">
                    <?php
                    if ($row = Mdobject::getFeatureCellIndex($this->headerRow, '4')) {
                    ?>
                    <div class="form-group row fom-row">
                        <?php echo Form::label(array('text' => $row['labelName'], 'class' => 'col-form-label col-md-4')); ?>
                        <div class="col-md-8">
                            <p class="form-control-plaintext font-weight-bold"><?php echo $row['value']; ?></p>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                    <?php
                    if ($row = Mdobject::getFeatureCellIndex($this->headerRow, '5')) {
                    ?>
                    <div class="form-group row fom-row">
                        <?php echo Form::label(array('text' => $row['labelName'], 'class' => 'col-form-label col-md-4')); ?>
                        <div class="col-md-8">
                            <p class="form-control-plaintext font-weight-bold"><?php echo $row['value']; ?></p>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                    <?php
                    if ($row = Mdobject::getFeatureCellIndex($this->headerRow, '6')) {
                    ?>
                    <div class="form-group row fom-row">
                        <?php echo Form::label(array('text' => $row['labelName'], 'class' => 'col-form-label col-md-4')); ?>
                        <div class="col-md-8">
                            <p class="form-control-plaintext font-weight-bold"><?php echo $row['value']; ?></p>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div> 
            </div>
            <div class="col-md-3 warehouse-header-content warehouse-header-sum-price">
                <div class="warehouse-header-sum-price">
                    <?php
                    if ($row = Mdobject::getFeatureCellIndex($this->headerRow, '7')) {
                    ?>
                    <p><?php echo $row['labelName']; ?></p>
                    <div class="clearfix w-100"></div>
                    <span class="float-right"><?php echo $row['value']; ?></span>
                    <?php
                    }
                    ?>
                    <div class="clearfix w-100"></div>
                    <div class="header-action-button mt10">
                        <?php 
                        echo Form::button(
                            array(
                                'class' => 'btn btn-circle btn-sm btn-success', 
                                'value' => 'Дэлгэрэнгүй'
                            )
                        ); 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>