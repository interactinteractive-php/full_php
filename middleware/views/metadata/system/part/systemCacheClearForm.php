<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal ', 'id' => 'form-cache-clear', 'method' => 'post', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-md-12">

        <div class="form-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" name="isSystem" value="1"> SYSTEM CONFIG
                </label>
            </div>
            <?php
            if (!isset($this->isUrlAuthenticate) || (isset($this->isUrlAuthenticate) && !$this->isUrlAuthenticate)) {
            ?>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" name="isMaster" value="1"> REFERENCE
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" name="isMenu" value="1"> MENU
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" name="isKpi" value="1"> KPI
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" name="isDv" value="1"> DATAVIEW
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" name="isProcessConfig" value="1"> PROCESS CONFIG
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" name="isProcessExpression" value="1"> PROCESS EXPRESSION
                </label>
            </div>
            <?php
            }
            ?>
        </div>

    </div>
</div>
<?php echo Form::close(); ?>