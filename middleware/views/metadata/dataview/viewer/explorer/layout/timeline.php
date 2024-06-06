<?php
if ($this->recordList && !isset($this->recordList['status'])) { ?>
<div class="timeline" id="timeline-1-<?php echo $this->dataViewId; ?>">
    <?php
    $fields = $this->row['dataViewLayoutTypes']['explorer']['fields'];

    $titleField = strtolower($fields['title']);
    $dateField = strtolower($fields['date']);
    $descrField = strtolower($fields['descr']);
    $photoField = strtolower($fields['photo']);
    $colorField = strtolower($fields['color']);

    $firstRow = $this->recordList[0];

    $title = $date = $descr = ' echo "";';
    $photo = ' echo "fa-photo";';
    $color = ' echo "#596679";';

    if (isset($firstRow[$titleField])) {
        $title = 'echo $row[\''.$titleField.'\'];';
    }

    if (isset($firstRow[$dateField])) {
        $date = 'echo $row[\''.$dateField.'\'];';
    }

    if (isset($firstRow[$descrField])) {
        $descr = 'echo $row[\''.$descrField.'\'];';
    }

    if (isset($firstRow[$photoField])) {
        $photo = 'echo $row[\''.$photoField.'\'];';
    }
    
    if (isset($firstRow[$colorField])) {
        $color = 'echo $row[\''.$colorField.'\'];';
    }

    $onClick = 'echo "clickItem_'.$this->dataViewId.'(this);";';

    foreach ($this->recordList as $row) {
        $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
    ?>
    <div class="timeline-item" data-row-data="<?php echo $rowJson; ?>">
        <div class="timeline-badge">
            <div class="timeline-icon" style="padding-top: 0; padding-left: 0;">
                <?php 
                if (strpos($row[$photoField], 'img') != false) {
                    echo eval($photo) ;
                } else {
                    echo '<i class="fa '. eval($photo) .'" style="color: '. eval($color) .'"></i>';
                }?>
            </div>
        </div>
        <div class="timeline-body">
            <div class="timeline-body-arrow">
            </div>
            <div class="timeline-body-head">
                <div class="timeline-body-head-caption">
                    <span class="timeline-body-alerttitle font-green-haze">
                        <a href="javascript:;" data-row-data="<?php echo $rowJson ?>"
                           onclick="<?php echo eval($onClick); ?>">
                            <?php eval($title); ?>
                        </a>
                    </span>
                    <span class="timeline-body-time font-grey-cascade"><?php eval($date); ?></span>
                </div>
            </div>
            <div class="timeline-body-content">
                <?php eval($descr); ?>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
</div>

<script type="text/javascript">
$(function(){
    if (!$("link[href='<?php echo autoVersion('assets/custom/addon/admin/pages/css/timeline.css'); ?>']").length) {
        $("head").append('<link rel="stylesheet" type="text/css" href="<?php echo autoVersion('assets/custom/addon/admin/pages/css/timeline.css'); ?>"/>');
    }
    
    $('#timeline-1-<?php echo $this->dataViewId; ?>').on('click', '.timeline-item', function(){
        var $this = $(this);
        var $parent = $this.closest('.timeline');
        $parent.find('.selected-row').removeClass('selected-row');
        $this.addClass('selected-row');
    });
    
    $('#timeline-1-<?php echo $this->dataViewId; ?>').on('contextmenu', '.timeline-item', function(e){
        e.preventDefault();
        var $this = $(this);
        var $parent = $this.closest('.timeline');
        $parent.find('.selected-row').removeClass('selected-row');
        $this.addClass('selected-row');
    });
});
</script>

<?php
} else {
    echo html_tag('div', array('class' => 'alert alert-info'), (isset($this->recordList['message']) ? $this->recordList['message'] : 'No data!') );
}
?>