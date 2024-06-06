<div class="col-md-12 xs-form" id="bp-window-<?php echo $this->methodId; ?>" data-meta-type="process">
    <?php
    if (isset($this->isDialog) && $this->isDialog == false) {
    ?>
        <div class="meta-toolbar">
            <?php
            echo html_tag('a', 
                array(
                    'href' => 'javascript:;', 
                    'class' => 'btn btn-circle btn-secondary card-subject-btn-border bp-btn-back', 
                    'onclick' => 'backFormMeta();'
                ), 
                '<i class="icon-arrow-left7"></i>', 
                true
            );
            ?> 
            <div class="clearfix w-100"></div>
        </div>
    <?php
    }
    ?>
    <div class="alert alert-warning"><?php echo isset($this->message) ? $this->message : 'Ажиллах боломжгүй!!!'; ?></div>
</div>    