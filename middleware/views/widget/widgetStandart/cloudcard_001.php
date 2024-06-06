<?php $renderAtom = new Mdwidget(); ?>
<?php $uid = getUID(); ?>
<div class="p-4" style="padding-bottom:10px !important">
    <div style="font-size:20px;color:#585858;" class="mb-2"><?php echo Lang::line(issetParam($this->jsonAttr['title'])) ?></div>
    <div class="slick-carousel2<?php echo $uid; ?>">
        <?php 
        if ($this->datasrc) {
            foreach($this->datasrc as $row) { ?>               
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 cursor-pointer cloud-call-process" data-row="[]" data-processid="<?php echo $renderAtom->renderAtom($row, "position4", $this->positionConfig) ?>">
                    <div class="flex items-center justify-between w-full sm:w-full">
                        <div class="flex items-center">
                            <div class="p-4 rounded-3xl flex items-center justify-center" style="height: 50px;width: 50px;aspect-ratio: auto 1 / 1; color: rgb(118, 51, 107);background-color:#E1EBFD">
                                <i style="color:#699BF7" class="far <?php echo $renderAtom->renderAtom($row, "position1", $this->positionConfig, "fa-smile") ?> text-xl false hover:text-sso "></i>
                            </div>
                            <div class="ml-2">
                                <span class="text-sm lg:text-base text-base text-gray-700 block" style="color:#67748E;font-size:12px"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position2", $this->positionConfig)) ?></span>
                                <span class="false  text-sm text-gray-600 font-bold block">
                                    <span class="line-clamp-0"><?php echo $renderAtom->renderAtom($row, "position3", $this->positionConfig) ?></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>      
        <?php }
        } ?>      
    </div>
</div>

<style>
    .slick-carousel2<?php echo $uid; ?> {
        display: flex;
        align-items: center;
    }
    .slick-carousel2<?php echo $uid; ?> .slick-slide {
        width: 310px;
        margin: 0 12px;
    }
    /* .slick-carousel2<?php echo $uid; ?> .slick-slide > div {
        margin-top: 20px;
    } */
    .slick-carousel2<?php echo $uid; ?> .slick-list {
        margin-left: 20px;
        margin-right: 20px;
    }
</style>

<script>
    $('.slick-carousel2<?php echo $uid; ?>').slick({
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
        $(".slick-carousel2<?php echo $uid; ?>").css("width", $(".slick-carousel2<?php echo $uid; ?>").parent().parent().width() - 40);
    }, 10);
    $(".cloud-call-process").click(function(){
        Core.blockUI({
          message: "Loading...",
          boxed: true,
        });        
        var metaDataId = $(this).data("processid");
        var getCustomerItems = $.ajax({
            type: "post",
            url: "mdmetadata/getMetaDataDrill/"+metaDataId,
            dataType: "json",
            async: false,
            success: function (data) {
                Core.unblockUI();
                return data.result;
            },
        });
        if (getCustomerItems.responseJSON.META_TYPE_CODE == 'BOOKMARK') {
            appMultiTab({weburl: getCustomerItems.responseJSON.BOOKMARK_URL, metaDataId: getCustomerItems.responseJSON.BOOKMARK_URL+'223kdlfoeor666', title: getCustomerItems.responseJSON.META_DATA_NAME, type: 'selfurl'});
        } else {
            gridDrillDownLink(this, getCustomerItems.responseJSON.META_DATA_CODE, getCustomerItems.responseJSON.META_TYPE_CODE.toLowerCase(), '1', '',  '', '',metaDataId, '', true, true)
        }
    });
</script>