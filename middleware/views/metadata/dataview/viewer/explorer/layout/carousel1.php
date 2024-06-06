<div id="width-carousel-container-<?php echo $this->dataViewId; ?>"></div>

<?php
if ($this->recordList) {
?>
<div class="pf-carousel-1" id="pf-carousel-container-<?php echo $this->dataViewId; ?>">
    <div class="owl-carousel" id="carousel-container-<?php echo $this->dataViewId; ?>">
        <?php
        $defaultImage = 'assets/custom/img/build1.jpg';
        
        foreach ($this->recordList as $row) {
            $imgSrc = $this->photo ? $row[$this->photo] : $defaultImage;
            $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
        ?>
        <div>
            <div class="product-item" data-row-data="<?php echo $rowJson; ?>">
                <div class="pi-img-wrapper">
                    <img src="<?php echo $imgSrc; ?>" class="img-fluid" data-default-image="<?php echo $defaultImage; ?>" onerror="onDataViewImgError(this);">
                </div>
                <div class="pi-yellow-bg-title">
                    <div class="pi-yellow-bg-title-name1">
                        <?php echo $row[$this->name1]; ?>
                    </div>
                    <div class="pi-yellow-bg-title-name2">
                        <?php echo $row[$this->name2]; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
</div>
<?php
} else {
    echo html_tag('div', array('class' => 'alert alert-info'), 'No data!');
}
?>

<script type="text/javascript">
$(function(){
    
    $.when(
        $.getStylesheet(URL_APP+'assets/custom/addon/plugins/carousel-owl-carousel/owl-carousel/owl.carousel.css'), 
        $.getScript(URL_APP+'assets/custom/addon/plugins/carousel-owl-carousel/owl-carousel/owl.carousel.min.js') 
    ).then(function () {
        
        var getWidth = $('#width-carousel-container-<?php echo $this->dataViewId; ?>').innerWidth();
        
        $('#pf-carousel-container-<?php echo $this->dataViewId; ?>').width(getWidth);
        
        $("#carousel-container-<?php echo $this->dataViewId; ?>").owlCarousel({
            pagination: false,
            navigation: true,
            addClassActive: true, 
            itemWidth: 161
        });
        
    }, function () {
        console.log('an error occurred somewhere');
    });
    
    $('#carousel-container-<?php echo $this->dataViewId; ?>').on('click', '.product-item', function(){
        var elem = this;
        var _this = $(elem);
        var _parent = _this.closest('.owl-carousel');
        _parent.find('.selected-row').removeClass('selected-row');
        _this.addClass('selected-row');

        <?php //echo $this->clickRowFunction; ?>
    });
    
    $('#carousel-container-<?php echo $this->dataViewId; ?>').on('contextmenu', '.product-item', function(e) {
        e.preventDefault();
        var elem = this;
        var _this = $(elem);
        var _parent = _this.closest('.owl-carousel');
        _parent.find('.selected-row').removeClass('selected-row');
        _this.addClass('selected-row');
    });
    
});    
</script>