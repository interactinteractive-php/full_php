<!-- Start User Comment Widget -->
<div id="widgetUserComment_<?php echo $this->uniqId; ?>">
    <style type="text/css">
    </style>
    
    <div class="mb-40 ta-c">
        <h3 class="section-title mb-10">
            Харилцагчид юу гэж хэлэв ?
        </h3>
        <div>
            <span class="section-desc">DIGITALCREDIT - н талаархи тэдний сэтгэгдлүүдийг хуваалцъя</span>
        </div>
    </div>
    <div class="container ph-40">    
        <?php if($this->getRows) { ?>
        <div class="carousel-row">
            <div class="carousel slide" id="emarket-comment-slide" data-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    foreach ($this->getRows as $key => $comment) {
                        $tmpClass = $key == 0 ? 'active' : '';
                        ?>
                        <div class="item <?php echo $tmpClass; ?>">
                            <div class="col-md-12 ta-c">

                                <img src="<?php echo array_key_exists($this->layoutParamConfig['photo'], $comment) ? IMG_BASE_URL . $comment[$this->layoutParamConfig['photo']] : ''; ?>" onerror="onItemImgError(this);"  alt="" class="mb-15 avatar-img"/>

                                <div class="cc-name mb-10"><?php echo array_key_exists($this->layoutParamConfig['name'], $comment) ? $comment[$this->layoutParamConfig['name']] : ''; ?></div>

                                <p class="ph-100">
                                    <?php echo array_key_exists($this->layoutParamConfig['description'], $comment) ? $comment[$this->layoutParamConfig['description']] : ''; ?>
                                </p>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <a class="left carousel-control" href="#emarket-comment-slide" role="button" data-slide="prev">
                    <img src="assets/emarket/img/service-left.png" alt="" />
                </a>
                <a class="right carousel-control" href="#emarket-comment-slide" role="button" data-slide="next">
                    <img src="assets/emarket/img/service-right.png" alt="" />
                </a>
            </div>
        </div>    
        <?php } else {
            echo "Өгөгдөл олдсонгүй.";
        } ?>
    </div>
    
    <script type="text/javascript">
    </script>

</div>
<!-- End User Comment Widget -->

