<?php $renderAtom = new Mdwidget(); ?>

<div class="bg-white p-4">
    <div style="font-size:20px;color:#585858;margin-bottom:20px">Кассын нярав</div>
    <div class="slick-carousel">
        <?php 
        if ($this->datasrc) {
            foreach($this->datasrc as $row) { ?>                
                <div class="rounded-xl" style="background-color:<?php echo $renderAtom->renderAtom($row, "position11", $this->positionConfig, "#496ABA") ?>;height: 200px;">
                    <div class="flex justify-between w-full h-full">
                        <div class="p-3">
                            <div class="flex">
                                <div class="p-4 rounded-3xl flex items-center justify-center" style="height: 50px;width: 50px;background-size: cover; color: rgb(118, 51, 107);background-color:#C0DCFF;background-image:url(<?php echo $renderAtom->renderAtom($row, "position1", $this->positionConfig) ?>);background-position: center;border: 2px solid #fff;">
                                </div>
                                <div class="ml-3">
                                    <span class="text-sm lg:text-base text-base text-gray-700 block font-bold" style="color:#fff;font-size:14px;text-overflow: ellipsis;overflow: hidden;white-space: nowrap;width: 112px;"><?php echo $renderAtom->renderAtom($row, "position2", $this->positionConfig) ?></span>
                                    <span class="" style="color:#fff;">
                                        <span class="line-clamp-0 d-block" style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap;width: 112px;"><?php echo $renderAtom->renderAtom($row, "position3", $this->positionConfig) ?></span>
                                    </span>
                                </div>
                            </div>
                            <div class="text-white" style="margin-top: 75px;">
                                <div>
                                <?php echo $renderAtom->renderAtom($row, "position8", $this->positionConfig) ?>
                                </div>
                                <div>
                                <?php echo $renderAtom->renderAtom($row, "position9", $this->positionConfig) ?>
                                </div>
                                <div>
                                <?php echo $renderAtom->renderAtom($row, "position10", $this->positionConfig) ?>
                                </div>
                            </div>
                        </div>
                        <div style="background-color: <?php echo $renderAtom->renderAtom($row, "position12", $this->positionConfig, "#637FC2") ?>;width: 110px;border-top-left-radius: 0;border-bottom-left-radius: 0;" class="h-full p-3 rounded-xl">
                            <div style="text-align: right;font-size: 10px;font-weight: bold;color: #fff;text-transform: uppercase;"><?php echo $renderAtom->renderAtom($row, "position4", $this->positionConfig) ?></div>
                            <div class="font-bold p-1 mt-5" style="background-color: #fff;opacity: .6;border-radius: 50px;text-align: center;">
                                Орлого
                            </div>
                            <div class="font-bold p-1 mt-2" style="background-color: #fff;opacity: .6;border-radius: 50px;text-align: center;">
                                Зарлага
                            </div>
                            <div class="font-bold p-1 mt-2" style="background-color: #fff;opacity: .6;border-radius: 50px;text-align: center;">
                                ...
                            </div>
                            <div class="absolute" style="
                                text-align: center;
                                margin-top: -95px;
                                margin-left: -100px;
                                opacity: .06;
                                color: #fff;
                                border: 10px solid #fff;
                                padding: 10px;
                                border-radius: 55px;
                                height: 115px;
                                width: 115px;">
                                <i class="far fa-dollar-sign" style="font-size:80px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
        <?php }
        } ?>      
    </div>
</div>

<style>
    .slick-carousel {
        display: flex;
        align-items: center;
    }
    .slick-carousel .slick-slide {
        width: 310px;
        height: 200px;
        margin: 0 20px;
    }
    .slick-carousel .slick-list {
        margin-left: 20px;
        margin-right: 20px;
    }
</style>

<script>
    $('.slick-carousel').slick({
        // autoplay: true,
        // autoplaySpeed: 1500,
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: true,
        variableWidth: true,
        dots: false,
        prevArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer" class=""><i class="far fa-angle-left" style="font-size:22px;margin: 9px;"></i></div>',
        nextArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer" class=""><i class="far fa-angle-right" style="font-size:22px;margin: 9px;"></i></div>'       
    });    
    setTimeout(function() {
        $(".slick-carousel").css("width", $(window).width() -500);
    }, 10);
</script>