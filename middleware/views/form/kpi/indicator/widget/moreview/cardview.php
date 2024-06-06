<style type="text/css">
.mv-cardview-more-wrapper {
    background-color: #f3f4f6;
    margin-top: -10px;
    margin-left: -15px;
    margin-right: -15px;
    margin-bottom: -4px;
    padding-top: 10px;
    min-height: 92vh;
}
.mv-cardview-more {
    padding: 20px; 
    margin-left: auto;
    margin-right: auto;
    max-width: 1000px;
    background-color: #fff;
    border-radius: 10px;
}
.mv-cardview-more .mv-cardview-more-title {
    display: block;
    margin-bottom: 20px;
    text-transform: uppercase;
    font-weight: bold;
}
.mv-cardview-more .mv-cardview-more-description {
    font-size: 13px;
}
.mv-cardview-more .mv-cardview-more-image {
    margin-bottom: 20px;
}
.mv-cardview-more .mv-cardview-more-image img {
    max-width: 100%;
    max-height: 200px;
}
.mv-cardview-more .mv-cardview-more-apply-btn {
    margin-top: 20px;
}
</style>
<div class="mv-cardview-more-wrapper" data-widget-parent="tag" id="<?php echo $this->uniqId; ?>">
    <div class="mv-cardview-more">
        <h5 class="mv-cardview-more-title">
            <?php echo $this->moreData['RELATED_INDICATOR_ID_DESC']; ?>
        </h5>
        <div class="mv-cardview-more-image">
            <img src="<?php echo checkFileDefaultVal($this->moreData['PICTURE'], 'assets/custom/addon/img/noimage.png'); ?>">
        </div>
        <div class="mv-cardview-more-description">
            <?php echo Str::cleanOut($this->moreData['DESCRIPTION']); ?>
        </div>
        <div class="mv-cardview-more-apply-btn text-right">
            <button type="button" class="btn btn-outline rounded-round bg-teal-400 text-teal-400 border-teal-400 border-1" onclick="mvCustomCardMoreViewRunAction(this, '<?php echo $this->moreData['RELATED_INDICATOR_ID']; ?>');">
                <i class="far fa-check-circle mr-2"></i> Apply
            </button>
        </div>
    </div>
</div>

<script type="text/javascript">
function mvCustomCardMoreViewRunAction(elem, indicatorId) {
    manageKpiIndicatorValue(elem, '', indicatorId, false);
}    
</script>