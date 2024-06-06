<?php if(isset($this->firstLoadDv) && $this->firstLoadDv) { ?>
    <?php foreach($this->firstLoadDv as  $content) { ?>
        <li>
            <a href="javascript:void(0);" data-rowdata="<?php echo htmlentities(json_encode($content), ENT_QUOTES, 'UTF-8'); ?>" onclick="getContentDetail<?php echo $this->uniqId ?>(<?php echo $content['id'] ?>,'<?php echo $content['physicalpath'] ?>', '<?php echo $content['videopath'] ?>');" class="media p-2">
                <div class="media-body">
                    <div class="d-flex flex-row align-items-center justify-content-between">
                        <div class="media-title mb-0 line-height-normal"><?php echo $content['materialtitle'] ?></div>
                        <div class="ml-3">
                            <span class="date-time text-right"><?php echo $content['createddate'] ?></span>
                        </div>
                    </div>
                    <!--<span class="title"><?php echo $content['description'] ?></span>-->
                </div>
            </a>
        </li>
    <?php } ?>
<?php } ?>
<?php 
/*
if(isset($this->firstLoadDv) && $this->firstLoadDv) {
    $index = 0;
    foreach($this->firstLoadDv as $key => $group) { ?>
        <div class="date-filter" data-toggle="collapse" href="#collapse_<?php echo $this->uniqId . '_' . $index ?>">
            <?php echo $key ?>
            <i class="icon-arrow-down5"></i>
        </div>
        <div class="collapse show" id="collapse_<?php echo $this->uniqId . '_' . $index ?>">
            <?php foreach($group as  $content) { ?>
                    <li>
                        <a href="javascript:void(0);" data-rowdata="<?php echo htmlentities(json_encode($content), ENT_QUOTES, 'UTF-8'); ?>" onclick="getContentDetail<?php echo $this->uniqId ?>('<?php echo $content['physicalpath'] ?>');" class="media">
                            <div class="media-body">
                                <div class="d-flex flex-row align-items-center">
                                    <div class="media-title mb-0 line-height-normal"><?php echo $content['materialtitle'] ?></div>
                                    <div class="ml-auto">
                                        <span class="date-time text-right"><?php echo $content['createddate'] ?></span>
                                    </div>
                                </div>
                                <!--<span class="title"><?php echo $content['description'] ?></span>-->
                            </div>
                        </a>
                    </li>
            <?php }
            $index++;  ?>
        </div>
<?php } 
} */ ?>