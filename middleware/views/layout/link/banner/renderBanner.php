<div class="card light shadow">
    <div class="card-body">
    <?php
    if ($this->bannerList) {
        $bannerItem = "";
        $bannerNav = "";
        foreach ($this->bannerList as $k=>$row) {
            $active = "";
            if ($k == 0) {
                $active = "active";
            }
            $bannerItem .= '<div class="'.$active.' item">
                            <img src="'.$row['ATTACH'].'" class="img-fluid">';
            if ($row['ATTACH_NAME'] != "") {
                $bannerItem .= '<div class="carousel-caption"><p>'.$row['ATTACH_NAME'].'</p></div>';
            }
            $bannerItem .= '</div>';
            $bannerNav .= '<li data-target="#meta-carousel-'.$this->metaDataId.'" data-slide-to="'.$k.'" class="'.$active.'"></li>';
        }
    ?>
    <div id="meta-carousel-<?php echo $this->metaDataId; ?>" class="carousel image-carousel slide meta-carousel-<?php echo $this->metaDataId; ?>">
        <div class="carousel-inner">
            <?php echo $bannerItem; ?>
        </div>
        <a class="carousel-control left" href="#meta-carousel-<?php echo $this->metaDataId; ?>" data-slide="prev">
            <i class="m-icon-big-swapleft m-icon-white"></i>
        </a>
        <a class="carousel-control right" href="#meta-carousel-<?php echo $this->metaDataId; ?>" data-slide="next">
            <i class="m-icon-big-swapright m-icon-white"></i>
        </a>
        <ol class="carousel-indicators">
            <?php echo $bannerNav; ?>
        </ol>
    </div>

    <?php
    if ($this->cellAttr['height'] != "") {
    ?>
    <style type="text/css">
    .meta-carousel-<?php echo $this->metaDataId; ?> .item{
        height: <?php echo $this->cellAttr['height']; ?> !important;
    }
    .meta-carousel-<?php echo $this->metaDataId; ?> .item img {
        min-height: <?php echo $this->cellAttr['height']; ?> !important;
    }
    </style>
    <?php
    }
    ?>
    <?php
    } else {
        echo '<center>Зураг оруулна уу!</center>';
    }
    ?>
    </div>
</div>