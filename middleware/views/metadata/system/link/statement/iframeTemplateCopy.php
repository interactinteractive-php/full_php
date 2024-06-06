<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
echo Form::create(array('class' => 'form-horizontal', 'id' => 'report-template-copy', 'method' => 'post', 'autocomplete' => 'off')); ?>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Загварын нэр', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-9">
            <?php echo Form::text(array('name' => 'templateName', 'class' => 'form-control form-control-sm', 'required' => 'required')); ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Statement meta', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-9">
            <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$statementMetaTypeId; ?>">
                <div class="input-group double-between-input">
                    <input type="hidden" id="statementId" name="statementId">
                    <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" required="required">
                    <span class="input-group-btn">
                        <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid('single', '', this);"><i class="fa fa-search"></i></button>
                    </span>     
                    <span class="input-group-btn not-group-btn">
                        <div class="btn-group pf-meta-manage-dropdown">
                            <button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>
                            <ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>
                        </div>
                    </span>  
                    <span class="input-group-btn flex-col-group-btn">
                        <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" required="required">      
                    </span>     
                </div>
            </div>  
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'DataView meta', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-9">
            <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                <div class="input-group double-between-input">
                    <input type="hidden" id="dataViewId" name="dataViewId">
                    <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" required="required">
                    <span class="input-group-btn">
                        <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid('single', '', this);"><i class="fa fa-search"></i></button>
                    </span>     
                    <span class="input-group-btn not-group-btn">
                        <div class="btn-group pf-meta-manage-dropdown">
                            <button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>
                            <ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>
                        </div>
                    </span>  
                    <span class="input-group-btn flex-col-group-btn">
                        <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" required="required">      
                    </span>     
                </div>
            </div>  
        </div>
    </div>
</div>
<?php echo Form::close(); ?>