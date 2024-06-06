<?php echo Form::create(array('class'=>'form-horizontal', 'method'=>'post')); ?>
<div class="col-md-7">
    <?php 
    if (!empty($this->metaValueRow['META_VALUE_CODE'])) {
        echo html_tag(
            'span', 
            array(
                'class' => 'text-muted float-left'
            ), 
            '<i class="fa fa-tag"></i> '.$this->metaValueRow['META_VALUE_CODE']
        ); 
    }
    if (!empty($this->metaValueRow['CREATED_DATE'])) {
        echo html_tag(
            'span', 
            array(
                'class' => 'text-muted float-right'
            ), 
            '<i class="fa fa-clock-o"></i> '.Date::format('Y-m-d H:i', $this->metaValueRow['CREATED_DATE'])
        );
    }
    echo html_tag(
        'h3', 
        array(
            'class' => 'bold'
        ), 
        $this->metaValueRow['META_VALUE_NAME']
    );
    ?>
    <hr />
    <div class="tabbable-line">
        <ul class="nav nav-tabs">
            <?php
            if ($this->mainGroupId != null) {
                echo '<li class="nav-item">
                        <a aria-expanded="false" class="nav-link active" href="#metatab_5" data-toggle="tab">'.$this->mainGroupName.'</a>
                    </li>';
            }
            ?>
            <li>
                <a aria-expanded="false" href="#metatab_1" <?php echo (($this->mainGroupId != null) ? '' : ' class="active"'); ?> data-toggle="tab"><?php echo $this->lang->line('META_00007'); ?></a>
            </li>
            <li>
                <a aria-expanded="false" href="#metatab_2" data-toggle="tab"><?php echo $this->lang->line('META_00072'); ?></a>
            </li>
            <li>
                <a aria-expanded="false" href="#metatab_3" data-toggle="tab"><?php echo $this->lang->line('META_00149'); ?></a>
            </li>
            <li>
                <a aria-expanded="false" href="#metatab_4" data-toggle="tab"><?php echo $this->lang->line('META_00150'); ?></a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane<?php echo (($this->mainGroupId != null) ? "" : ' active'); ?>" id="metatab_1">
                <?php
                echo html_tag(
                    'p', 
                    array(), 
                    Str::nlTobr($this->metaValueRow['DESCRIPTION'])
                );
                ?>
            </div>
            <div class="tab-pane" id="metatab_2">
                <?php echo $this->metaValuePhotos; ?>
            </div>
            <div class="tab-pane" id="metatab_3">
                <?php echo $this->metaValueFiles; ?>
            </div>
            <div class="tab-pane" id="metatab_4">
                <?php echo $this->metaValueComments; ?>
            </div>
        </div>
    </div>
</div>
<div class="col-md-5">
    <?php echo $this->sidebar; ?>
</div> 
<div class="clearfix w-100"></div>
<?php echo Form::close(); ?>   