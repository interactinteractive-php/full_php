<!-- Start News Widget -->
<div id="widgetNews_<?php echo $this->uniqId; ?>">
    <style type="text/css">
    </style>
    
    <div class="col-md-12 col-sm-12 col-xs-12">
        <h2 class="ta-c specific-title2 mb-20 ml-15">Мэдээ мэдээлэл</h2>
        <div class="multi-news" id="news-section-01">
            <div class="row">
                
                <?php
                foreach ($this->getRows as $key => $comment) {
                    ?>
                    <div class="col-md-3 col-sm-6 col-xs-12 news-post">
                        <div class="news-post-cont">
                            <div class="content-img-div">
                                <a href="content/show/<?php echo array_key_exists($this->layoutParamConfig['id'], $comment) ? $comment[$this->layoutParamConfig['id']] : ''; ?>">
                                    <img src="<?php echo array_key_exists($this->layoutParamConfig['photo'], $comment) ? IMG_BASE_URL . $comment[$this->layoutParamConfig['photo']] : ''; ?>" onerror="onItemImgError(this);"  alt="" class="img-fluid content-img"/>
                                </a>    
                            </div>                                
                            <div class="ph-15">
                                <div class="date mt-10"><?php echo array_key_exists($this->layoutParamConfig['createddate'], $comment) ? $comment[$this->layoutParamConfig['createddate']] : ''; ?></div>
                                <h2>
                                    <a href="content/show/<?php echo array_key_exists($this->layoutParamConfig['id'], $comment) ? $comment[$this->layoutParamConfig['id']] : ''; ?>"><?php echo array_key_exists($this->layoutParamConfig['title'], $comment) ? $comment[$this->layoutParamConfig['title']] : ''; ?></a>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>                

            </div>
        </div>
    </div>
    
    <script type="text/javascript">
    </script>

</div>
<!-- End News Widget -->

