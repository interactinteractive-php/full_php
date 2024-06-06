<div class="pf-widget1" id="pf-widget1-container-<?php echo $this->dataViewId; ?>" style="display: none">
    <?php
    if ($this->recordList) {
    ?>
    <div class="pf-widget1-table">
        <?php
        $defaultImage = 'assets/core/global/img/user.png';

        foreach ($this->recordList as $row) {
            $imgSrc = isset($row[$this->photoField]) ? $row[$this->photoField] : $defaultImage;
            $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
        ?>
        <div class="pf-widget1-table-row" data-row-data="<?php echo $rowJson; ?>">
            <div class="pf-widget1-table-cell-photo">
                <div class="pf-widget1-photo-circle">
                    <img src="<?php echo $imgSrc; ?>" class="img-fluid" data-default-image="<?php echo $defaultImage; ?>" onerror="onDataViewImgError(this);">
                </div>
            </div>
            <div class="pf-widget1-table-cell-title">
                <div class="pf-widget1-name1">
                    <?php echo $row[$this->name1]; ?>
                </div>
                <div class="pf-widget1-name2">
                    <?php echo $row[$this->name2]; ?>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
<?php
} else {
    echo html_tag('div', array('class' => 'alert alert-info'), 'No data!');
}
?>
</div>

<script type="text/javascript">
$(function(){
    $('.table-toolbar').hide();
    $.when(
        $.getStylesheet(URL_APP+'middleware/assets/css/gridlayout/widget1.css')  
    ).then(function () {
        /*$('#pf-widget1-container-<?php echo $this->dataViewId; ?>').show();*/
    }, function () {
        console.log('an error occurred somewhere');
    });
    
    $('#pf-widget1-container-<?php echo $this->dataViewId; ?>').on('click', '.pf-widget1-table-row', function(){
        var elem = this;
        var _this = $(elem);
        var _parent = _this.closest('.pf-widget1-table');
        _parent.find('.selected-row').removeClass('selected-row');
        _this.addClass('selected-row');
    });
    
    $('#pf-widget1-container-<?php echo $this->dataViewId; ?>').on('contextmenu', '.pf-widget1-table-row', function(e){
        e.preventDefault();
        var elem = this;
        var _this = $(elem);
        var _parent = _this.closest('.pf-widget1-table');
        _parent.find('.selected-row').removeClass('selected-row');
        _this.addClass('selected-row');
    });
    
});    
</script>