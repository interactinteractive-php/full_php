<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'viewMetaSystemForm', 'method' => 'post')); ?>
<div class="row">
    <div class="col-md-7">
        <span class="text-muted float-left"><i class="fa fa-tag"></i> <?php echo $this->metaRow['META_DATA_CODE']; ?></span> 
        <span class="text-muted float-right"><i class="fa fa-clock-o"></i> <?php echo Date::formatter($this->metaRow['CREATED_DATE'], 'Y-m-d H:i'); ?></span> 
        <div class="clearfix w-100"></div>
        <h3 class="bold"><?php echo $this->metaRow['META_DATA_NAME']; ?></h3>
        <hr />
        <p><?php echo Str::nlTobr($this->metaRow['DESCRIPTION']); ?></p>
        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#metatab_2" class="nav-link active" aria-expanded="false" data-toggle="tab"><?php echo $this->lang->line('META_00149'); ?></a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" id="metatab_2">
                    <?php echo $this->metaFiles; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <?php echo $this->sidebar; ?>
    </div> 
</div>

<div class="row form-actions mt20">
    <div class="col-lg-8 ml-lg-auto">
        <?php
        echo Form::button(
            array(
                'class' => 'btn grey-cascade meta-btn-back',
                'value' => $this->lang->line('back_btn'),
                'onclick' => 'backFormMeta();'
            )
        );
        echo Form::button(
            array(
                'class' => 'btn green-meadow ml10',
                'value' => $this->lang->line('edit_btn'),
                'onclick' => 'editFormMeta(\'' . $this->metaRow['META_DATA_ID'] . '\', \'' . $this->folderId . '\', this);'
            ), true
        );
        ?>
    </div>
</div>
<?php echo Form::close(); ?>   