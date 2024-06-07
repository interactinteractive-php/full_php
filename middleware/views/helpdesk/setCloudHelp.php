<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<form method="post" autocomplete="off">
    <div class="col-md-12 xs-form">
        <div class="form-group row">
            <div class="col-md-12">
                <div class="alert alert-info">
                    Жишээ нь: 
                    <a href="https://help.veritech.mn/lessons/content?filterid=16615113575348&lparentid=16615115669708" target="_blank">
                        https://help.veritech.mn/lessons/content?filterid=16615113575348&lparentid=16615115669708
                    </a>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <?php echo Form::label(['text' => 'help.veritech.mn URL', 'for' => 'helpUrl', 'class' => 'col-form-label text-right pt-1 col-md-3', 'required' => 'required']); ?>
            <div class="col-md-9">
                <?php 
                echo Form::text([
                    'name' => 'helpUrl', 
                    'id' => 'helpUrl', 
                    'class' => 'form-control form-control-sm', 
                    'required' => 'required', 
                    'autocomplete' => 'off'
                ]); 
                ?>
            </div>
        </div>
    </div>
</form>