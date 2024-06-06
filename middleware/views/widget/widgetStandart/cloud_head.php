<?php 
    $data = issetParamArray($this->datasrc[0]); 
    $renderAtom = new Mdwidget(); 
    $uid = getUID(); ?>
    <?php if($this->jsonAttr["renderType"] !="img") { ?>
        <div class="slick-carousel2<?php echo $uid; ?> slickwidget">
            <div class="relative w-full h-full rounded-xl">
                <img data-tposition="position1" data-tpath="" src="middleware/assets/theme/layout/widget/air-plane.jpg" alt="Banner image" class="w-100 object-center object-fill block rounded-xl">
            </div>    
            <div class="relative w-full h-full rounded-xl">
                <img data-tposition="position1" data-tpath="" src="middleware/assets/theme/layout/widget/development.jpg" alt="Banner image" class="w-100 object-center object-fill block rounded-xl">
            </div>    
            <div class="relative w-full h-full rounded-xl">
                <img data-tposition="position1" data-tpath="" src="middleware/assets/theme/layout/widget/console.jpg" alt="Banner image" class="w-100 object-center object-fill block rounded-xl">
            </div>    
        </div>
    <?php } else { ?> 
        <div class="  overflow-hidden relative  box  rounded-xl slickwidget">
            <div class="slick-carousel3<?php echo $uid; ?> ">
                <?php 
                if ($this->datasrc && array_key_exists(0, $this->datasrc)) {
                    foreach($this->datasrc as $row) { ?> 
                        <div class="relative rounded-xl bg-image" style="height:300px; width:100%; background-image:url(<?php echo $renderAtom->renderAtom($row, "position0", $this->positionConfig); ?>);"></div>
                <?php }
                } ?>      
            </div>
        </div>
        <script type="text/javascript">
            $('.slick-carousel3<?php echo $uid; ?>').slick({
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                // arrows: true,
                // dots: true,
                autoplay: true,
                // prevArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer" class=""><i class="far fa-angle-left" style="font-size:22px;margin: 9px;"></i></div>',
                // nextArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer" class=""><i class="far fa-angle-right" style="font-size:22px;margin: 9px;"></i></div>'       
            });    
            setTimeout(function() {
                // $(".slick-carousel3<?php echo $uid; ?>").css("width", $(window).width()-280);
            }, 8);
        </script>
    <?php }  ?> 
    
    <div class="row position-absolute chead<?php echo $uid; ?>" >
        <div class="col-md-6">
           <p>
                <span class="leftside-head">
                    <?php echo $renderAtom->renderAtom($data, "position1", $this->positionConfig) ?> 
                </span>
            </p>
           <p class="leftside-foot">
                <span><?php echo $renderAtom->renderAtom($data, "position2", $this->positionConfig) ?> </span> 
                <i class="icon-arrow-right8"></i> 
                <span class="text-danger" --style="color: #FFAE00; font-size: 16px; text-align: left; font-weight: 400"><?php echo $renderAtom->renderAtom($data, "position3", $this->positionConfig) ?></span>
            </p>

        </div>
        <div class="col-md-6 align-self-center">
            <div class="row">
                <div class="col-md-3">
                    <p class="rightside-head"><?php echo $renderAtom->renderAtom($data, "position4", $this->positionConfig) ?></p>
                    <p class="rightside-foot"><?php echo $renderAtom->renderAtom($data, "position5", $this->positionConfig) ?></p>
                </div>
                <div class="col-md-3">
                    <p class="rightside-head"><?php echo $renderAtom->renderAtom($data, "position6", $this->positionConfig) ?></p>
                    <p class="rightside-foot"><?php echo $renderAtom->renderAtom($data, "position7", $this->positionConfig) ?></p>
                </div>
                <div class="col-md-3">
                    <p class="rightside-head"><?php echo $renderAtom->renderAtom($data, "position8", $this->positionConfig) ?></p>
                    <p class="rightside-foot"><?php echo $renderAtom->renderAtom($data, "position9", $this->positionConfig) ?></p>
                </div>
                <div class="col-md-3">
                    <p class="rightside-head"><?php echo $renderAtom->renderAtom($data, "position10", $this->positionConfig) ?></p>
                    <p class="rightside-foot"><?php echo $renderAtom->renderAtom($data, "position11", $this->positionConfig) ?></p>
                </div>
            </div>
        </div>
    </div>

<style type="text/css">
    .box{
        box-sizing: border-box 
    }
    .slick-carousel2<?php echo $uid; ?> {
        display: flex;
        align-items: center;
    }
    .slick-carousel2<?php echo $uid; ?> .slick-slide {
        min-width: 310px;
        margin: 0;
        /* margin-right: 5px; */
    }
    /* .slick-carousel2<?php echo $uid; ?> .slick-slide > div {
        margin-top: 20px;
    } */
    .slick-carousel2<?php echo $uid; ?> .slick-list {
        margin-left:-3px;
        margin-right:-3px;
        margin-top: 12px;
        border-radius: 0.75rem;
    }
    .slick-carousel2<?php echo $uid; ?> .slick-dots ,  .slick-carousel3<?php echo $uid; ?> .slick-dots{
        position: absolute;
        display: flex;
        width: 100%;
        padding: 0;
        margin: 0;
        margin-top: 0px;
        list-style: none;
        list-style-type: none;
        text-align: center;
        bottom: 16px;
        justify-content: center;
    }    

    .slick-carousel3<?php echo $uid; ?> .slick-dots{
        margin: auto;
        display: flex;
        grid-gap: 5px;
        text-align: center;
        justify-content: center;
    }

    .slick-prev,
    .slick-next
     {
        display: none !important;
    }
   .slickwidget .slick-dots li {
        width: 6px;
        padding: 10px;
    }    
   .slickwidget .slick-dots li.slick-active button {
        background-color: #fff;
    }
   .slickwidget .slick-dots li button {
        display: block;
        width: .5rem;
        height: .5rem;
        padding: 0;
        border: none;
        border-radius: 100%;
        background-color: #ffffff87;			
        text-indent: -9999px;
        outline: none;
    }    

    .bg-image {
        background-position: center; /* Center the image */
        background-repeat: no-repeat; /* Do not repeat the image */
        background-size: cover;
    }
    .chead<?php echo $uid; ?> {
        bottom: 0px;
        margin: 0;
        width: 99.4%;
        padding: 20px;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0) 24%, rgba(0, 0, 0, 0.75) 100%);
        border-radius: 10px;
        left: 4px;

        .leftside-head {
            font-size: 30px;
            font-weight: 700;
            line-height: 34px;
            letter-spacing: 0em;
            text-align: left;
            color: #FFF;
        }

        .rightside-head {
            font-size: 30px;
            font-weight: 700;
            line-height: 34px;
            letter-spacing: 0em;
            text-align: left;
            color: #FFF;
        }

        .leftside-foot {
            font-size: 16px; 
            font-weight: 400;
        }

        .text-danger {
            color: #FFAE00 !important;
        }

        .rightside-head {
            font-size: 20px;
            font-weight: 700;
            line-height: 22px;
            letter-spacing: 0em;
            text-align: center;

        }
        
        .rightside-foot {
            font-size: 14px;
            font-weight: 400;
            line-height: 18px;
            letter-spacing: 0em;
            text-align: center;

        }
    }
</style>

<script type="text/javascript">
    $('.slick-carousel2<?php echo $uid; ?>').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        variableWidth: false,
        fade: true,
        autoplay: true,
        dots: true,
        // prevArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer" class=""><i class="far fa-angle-left" style="font-size:22px;margin: 9px;"></i></div>',
        // nextArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer" class=""><i class="far fa-angle-right" style="font-size:22px;margin: 9px;"></i></div>'       
    });    
    // setTimeout(function() {
    //     $(".slick-carousel2<?php echo $uid; ?>").css("width", $(window).width() - 165);
    // }, 10);
</script>