<div id="width-carousel-container-<?php echo $this->dataViewId; ?>"></div>

<?php
if (!isset($this->recordList['status'])) {
?>
<div class="pf-carousel-marquee-inline-inline" id="pf-carousel-container-<?php echo $this->dataViewId; ?>">
    <div class="owl-carousel" id="carousel-container-<?php echo $this->dataViewId; ?>">
        <ul class='carousel-marquee-inline-list'>
             <marquee  direction="left" loop height="50" onmouseover="this.stop();" onmouseout="this.start();" scrollamount="10">
            <?php
            $defaultImage = 'assets/core/global/img/images.jpg';
            $countRecord = count($this->recordList) - 1;

            foreach ($this->recordList as $key => $row) {
                $imgSrc = $this->photo ? $row[$this->photo] : $defaultImage;
                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
            ?>
            <li data-row-data="<?php echo $rowJson; ?>">
                <span class="carousel-marquee-inline-list-link">
                    <?php if($this->photo) { ?><img src="<?php echo $imgSrc; ?>" height="56" width="56" onerror="onDataViewImgError(this);"><?php } ?>
                    <div class="right-widget-detail">
                    <h2 style="font-weight:600">
                        <span class="name actor-name"><?php echo $row[$this->name1]; ?></span>
                    </h2>
                    <?php if ($this->name2) { ?>
                        <p class=""><?php echo $row[$this->name2]; ?></p>
                    <?php } ?>
                    </div>
                </span>
            </li> 
            <?php
                if ($countRecord !== $key) {
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;****&nbsp;&nbsp&nbsp;'; 
                }
            }
            ?>
             </marquee>
        </ul>
    </div>
</div>
<?php
} else {
    echo html_tag('div', array('class' => 'alert alert-info'), isset($this->recordList['message']) ? $this->recordList['message'] : 'No data!');
}
?>

<style>
    #pf-carousel-container-<?php echo $this->dataViewId; ?> .carousel-marquee-inline-list {
        margin-top: 16px;
        list-style: none inside;
    }
    #pf-carousel-container-<?php echo $this->dataViewId; ?> .carousel-marquee-inline-list li {
        display: inline-block;
    }
    #pf-carousel-container-<?php echo $this->dataViewId; ?> .carousel-marquee-inline-list img {
        width: 56px;
        height: 56px;
        border: 1px solid transparent;
        border-radius: 49.9%;
        flex: 0 0 auto;
    }
    #pf-carousel-container-<?php echo $this->dataViewId; ?> .right-widget-detail {
        padding: 0 0 0 8px;
        flex: 1 0 0;
        margin-top: -18px;
    }
    #pf-carousel-container-<?php echo $this->dataViewId; ?> .right-widget-detail h3 {
        line-height: 20px;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }
    #pf-carousel-container-<?php echo $this->dataViewId; ?> .right-widget-detail h3:hover {
        color: #0084bf;
    }
    #pf-carousel-container-<?php echo $this->dataViewId; ?> .right-widget-detail p {
        line-height: 20px;
        font-weight: 400;
        color: rgba(0,0,0,.55);
        font-size: 12px;
        margin-top: -5px;
    }
    #pf-carousel-container-<?php echo $this->dataViewId; ?> .carousel-marquee-inline-list-link {
        display: flex !important;
    }
    #pf-carousel-container-<?php echo $this->dataViewId; ?> .carousel-marquee-inline-list-link:hover {
        text-decoration: none;    
    }    
</style>

<script type="text/javascript">
$(function(){
    
    /*$.when(
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
    });*/
    
    /*$('#carousel-container-<?php echo $this->dataViewId; ?>').on('click', '.product-item', function(){
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
    });*/
    
});    
</script>