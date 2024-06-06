<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'form-new-notify', 'method' => 'post')); ?>
    <div class="col-md-12 xs-form">
        <?php
        if (!$this->isManual) {
        ?>
        <div class="form-group row">
            <?php echo Form::label(array('text' => 'Is manual', 'class' => 'col-form-label col-md-4')); ?>
            <div class="col-md-8">
                <div class="radio-list">
                    <label class="radio-inline pt0">
                        <input type="radio" name="isManual" value="1"> Тийм
                    </label>
                    <label class="radio-inline pt0">
                        <input type="radio" name="isManual" value="0" checked="checked"> Үгүй
                    </label>
                </div>
            </div>    
        </div>
        <div class="form-group row" id="notify-block-message" style="display: none;">
            <?php echo Form::label(array('text' => 'Блок мэдэгдэл', 'for' => 'blockDescription', 'class' => 'col-form-label col-md-4')); ?>
            <div class="col-md-8">
                <?php 
                echo Form::textArea(
                    array(
                        'name' => 'blockDescription', 
                        'id' => 'blockDescription', 
                        'class' => 'form-control', 
                        'rows' => 4, 
                        'value' => $this->blockMsg
                    )
                ); 
                ?>
            </div>
        </div>
        <div class="form-group row">
            <?php echo Form::label(array('text' => 'Урьдчилсан мэдэгдэл', 'for' => 'description', 'class' => 'col-form-label col-md-4', 'required' => 'required')); ?>
            <div class="col-md-8">
                <?php 
                echo Form::textArea(
                    array(
                        'name' => 'description', 
                        'id' => 'description', 
                        'class' => 'form-control', 
                        'required' => 'required', 
                        'rows' => 4, 
                        'value' => $this->alertMsg
                    )
                ); 
                ?>
            </div>
        </div>
        <div class="form-group row">
            <?php echo Form::label(array('text' => 'Эхлэх хугацаа', 'for' => 'startDate', 'class' => 'col-form-label col-md-4', 'required' => 'required')); ?>
            <div class="col-md-3">
                <?php 
                echo Form::text(
                    array(
                        'name' => 'startDate', 
                        'id' => 'startDate', 
                        'class' => 'form-control input-sm dateMinuteMaskInit', 
                        'required' => 'required', 
                        'value' => Date::currentDate('Y-m-d H:i')
                    )
                ); 
                ?>
            </div>
        </div>
        <div class="form-group row">
            <?php echo Form::label(array('text' => 'Урьдчилж эхлэх хугацаа', 'for' => 'prevTime', 'class' => 'col-form-label col-md-4', 'required' => 'required')); ?>
            <div class="col-md-3">
                <?php 
                echo Form::text(
                    array(
                        'name' => 'prevTime', 
                        'id' => 'prevTime', 
                        'class' => 'form-control input-sm longInit', 
                        'required' => 'required', 
                        'value' => 10
                    )
                ); 
                ?>
            </div>
            <div class="col-md-3">
                минутаар
            </div>
        </div>
        <?php 
            echo Form::hidden(array('name' => 'isActive', 'value' => 1));
        } else {
        ?>
        <div class="form-group row">
            <?php echo Form::label(array('text' => 'Нээх эсэх', 'class' => 'col-form-label col-md-4')); ?>
            <div class="col-md-8">
                <div class="radio-list">
                    <label class="radio-inline pt0">
                        <input type="radio" name="isOpen" value="1" checked="checked"> Тийм
                    </label>
                    <label class="radio-inline pt0">
                        <input type="radio" name="isOpen" value="0"> Үгүй
                    </label>
                </div>
            </div>    
        </div>
        <?php
        }
        ?>
    </div>
<?php echo Form::close(); ?>

<script type="text/javascript">
$(function(){
    $('#form-new-notify').on('click', 'input[name="isManual"]', function(){
        var isManual = $('#form-new-notify input[name="isManual"]:checked').val();
        
        if (isManual === '1') {
            $('#notify-block-message').show();
        } else {
            $('#notify-block-message').hide();
        }
    });
});
</script>