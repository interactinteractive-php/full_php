<div id="width-carousel-container-<?php echo $this->dataViewId; ?>"></div>

<?php
if ($this->recordList) {
?>
<div class="pf-carousel-marquee" id="pf-carousel-container-<?php echo $this->dataViewId; ?>">
    <div class="owl-carousel" id="carousel-container-<?php echo $this->dataViewId; ?>">
        <ul class='carousel-marquee-list'>
             <marquee  direction="up" loop height="450" onmouseover="this.stop();" onmouseout="this.start();" scrollamount="4">
            <?php
            $defaultImage = 'assets/core/global/img/images.jpg';

            foreach ($this->recordList as $row) {
                $imgSrc = $this->photo ? $row[$this->photo] : $defaultImage;
                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
            ?>
            <li data-row-data="<?php echo $rowJson; ?>">
                <span class="carousel-marquee-list-link">
                    <?php if($this->photo) { ?><img src="<?php echo $imgSrc; ?>" height="56" width="56" onerror="onDataViewImgError(this);"><?php } ?>
                    <div class="right-widget-detail">
                    <h3>
                        <span class="name actor-name"><?php echo $row[$this->name1]; ?></span>
                    </h3>
                    <p class=""><?php echo $row[$this->name2]; ?></p>
                    </div>
                </span>
            </li>
            <?php
            }
            ?>
             </marquee>
        </ul>
    </div>
</div>
<?php
} else {
    echo html_tag('div', array('class' => 'alert alert-info'), 'No data!');
}
?>

<style>
    #pf-carousel-container-<?php echo $this->dataViewId; ?> .carousel-marquee-list {
        margin-top: 16px;
        list-style: none inside;
    }
    #pf-carousel-container-<?php echo $this->dataViewId; ?> .carousel-marquee-list li {
        margin-top: 16px;
    }
    #pf-carousel-container-<?php echo $this->dataViewId; ?> .carousel-marquee-list img {
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
    #pf-carousel-container-<?php echo $this->dataViewId; ?> .carousel-marquee-list-link {
        display: flex !important;
    }
    #pf-carousel-container-<?php echo $this->dataViewId; ?> .carousel-marquee-list-link:hover {
        text-decoration: none;    
    }    
</style>