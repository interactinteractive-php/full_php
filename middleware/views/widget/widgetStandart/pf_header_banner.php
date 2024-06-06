<?php $renderAtom = new Mdwidget(); ?>


    <?php $uid = getUID(); ?>
    <?php if($this->jsonAttr["renderType"] !="img") { ?>
        <div class="slick-carousel2<?php echo $uid; ?> slickwidget">
            <?php 
            if ($this->datasrc && array_key_exists(0, $this->datasrc)) {
                foreach($this->datasrc as $row) { ?> 
                    <div class="relative w-full h-full rounded-xl">
                        <img data-tposition="position1" data-tpath="<?php echo $renderAtom->renderAtomPath("position1", $this->positionConfig); ?>" src="<?php echo $renderAtom->renderAtom($row, "position1", $this->positionConfig); ?>" alt="Banner image" class="w-100 object-center object-fill block rounded-xl">
                    </div>
            <?php }
            } ?>      
        </div>
    <?php }else{ ?> 
        <div class="  overflow-hidden relative  box  rounded-xl slickwidget">
            <div class="slick-carousel3<?php echo $uid; ?> ">
           
                <?php 
                if ($this->datasrc && array_key_exists(0, $this->datasrc)) {
                    foreach($this->datasrc as $row) { ?> 
                        <div class="relative rounded-xl bg-image" style="height:300px; width:100%; background-image:url(<?php echo $renderAtom->renderAtom($row, "position1", $this->positionConfig); ?>);">
                        </div>
                <?php }
                } ?>      
            </div>
        </div>
       
    <script>
        $('.slick-carousel3<?php echo $uid; ?>').slick({
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            // variableWidth: true,
            autoplay: true,
            dots: true,
            prevArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer" class=""><i class="far fa-angle-left" style="font-size:22px;margin: 9px;"></i></div>',
            nextArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer" class=""><i class="far fa-angle-right" style="font-size:22px;margin: 9px;"></i></div>'       
        });    
        setTimeout(function() {
            // $(".slick-carousel3<?php echo $uid; ?>").css("width", $(window).width()-280);
        }, 8);
    </script>
    <?php }  ?> 

<style>
    .box{
        box-sizing: border-box 
    }
    .slick-carousel2<?php echo $uid; ?> {
        display: flex;
        align-items: center;
    }
    .slick-carousel2<?php echo $uid; ?> .slick-slide {
        width: 310px;
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
        display: block;
        width: 100%;
        padding: 0;
        margin: 0;
        margin-top: 0px;
        list-style: none;
        list-style-type: none;
        text-align: center;
        bottom: 16px;
    }    

    .slick-carousel3<?php echo $uid; ?> .slick-dots{
        margin: auto;
        display: flex;
        grid-gap: 5px;
        text-align: center;
        justify-content: center;
    }
   .slickwidget .slick-dots li {
        width: 6px;
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
</style>

<script>
    $('.slick-carousel2<?php echo $uid; ?>').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        variableWidth: false,
        fade: true,
        autoplay: true,
        dots: true,
        prevArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer" class=""><i class="far fa-angle-left" style="font-size:22px;margin: 9px;"></i></div>',
        nextArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer" class=""><i class="far fa-angle-right" style="font-size:22px;margin: 9px;"></i></div>'       
    });    
    setTimeout(function() {
        $(".slick-carousel2<?php echo $uid; ?>").css("width", $(window).width() -580);
    }, 10);
</script>