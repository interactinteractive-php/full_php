<form id="isLockForm">
    <div class="form-group row fom-row mt15">
        <div class="row">
            <?php echo Form::label(array('required'=>true, 'text' => 'Тайлагдах огноо', 'class' => 'col-md-5 col-form-label'));?>
            <div class="col-md-7">
                <div class="dateElement input-group">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'lockEndDate',
                            'id' => 'lockEndDate',
                            'class' => 'form-control form-control-sm dateInit',
                            'required' => true)
                    );
                    ?>
                    <span class="input-group-btn">
                        <button onclick="return false;" class="btn" style="padding: 1px 8px;"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>

            </div>
        </div>
    </div>
    <!--<div class="form-group row fom-row">
        <div class="row">
            <?php echo Form::label(array('required'=>true, 'text' => 'Түгжих эсэх', 'class' => 'col-md-5 col-form-label'));?>
            <div class="col-md-7">
                <div class="radio-list">
                    <?php
                    echo Form::radioMulti(array(
                        array('label' => $this->lang->line('yes_btn'), 'name' => 'isLock', 'value' => 1, 'labelclass' => 'radio-inline'),
                        array('label' => $this->lang->line('no_btn'), 'name' => 'isLock', 'value' => 0, 'labelclass' => 'radio-inline')),1);
                    ?>
                </div>
            </div>
        </div>
    </div>-->
</form>