<div class="row xs-form">
    <div class="col-md-12 form-horizontal">
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'Банк', 'class' => 'col-form-label col-md-3')); ?>
            <div class="col-md-9">
                <?php echo $this->bankName; ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => $this->lang->line('amount'), 'for'=>'statementAmount', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
            <div class="col-md-4">
                <?php 
                echo Form::text(
                    array(
                        'id' => 'statementAmount', 
                        'class' => 'form-control bigdecimalInit pos-account-statement-input', 
                        'placeholder' => $this->lang->line('amount'), 
                        'required' => 'required', 
                        'style' => 'height: 26px; padding: 6px 7px; font-size: 15px!important;'
                    )
                ); 
                ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => $this->lang->line('POS_0186'), 'for'=>'statementDescr', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
            <div class="col-md-9">
                <?php 
                echo Form::text(
                    array(
                        'id' => 'statementDescr', 
                        'class' => 'form-control pos-account-statement-input', 
                        'placeholder' => $this->lang->line('POS_0186'), 
                        'style' => 'height: 26px; padding: 6px 7px; font-size: 15px!important;'
                    )
                ); 
                ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => $this->lang->line('MET_330492'), 'for'=>'statementId', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
            <div class="col-md-4">
                <?php 
                echo Form::text(
                    array(
                        'id' => 'statementId', 
                        'class' => 'form-control pos-account-statement-input', 
                        'placeholder' => $this->lang->line('MET_330492'), 
                        'style' => 'height: 26px; padding: 6px 7px; font-size: 15px!important;'
                    )
                ); 
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-circle btn-sm blue" onclick="filterAccountStatement('<?php echo $this->bankId; ?>');"><i class="fa fa-search"></i> <?php echo $this->lang->line('search_btn'); ?></button>
    </div>
    <div class="col-md-12 mt10">
        <table class="table table-hover table-advance table-bordered" id="account-statement-list">
            <thead>
                <tr>
                    <th style="width: 10px;">№</th>
                    <th style="width: 10px;"><i class="fa fa-check-circle"></i></th>
                    <th><?php echo $this->lang->line('MET_330492'); ?></th>
                    <th><?php echo $this->lang->line('date'); ?></th>
                    <th><?php echo $this->lang->line('amount'); ?></th>
                    <th><?php echo $this->lang->line('POS_0186'); ?></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>