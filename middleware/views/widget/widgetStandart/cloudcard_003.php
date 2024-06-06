<?php $renderAtom = new Mdwidget(); ?>
<section data-sectioncode="3" class="mb-5 col-span-12">
    <div style="font-size:18px;color:#585858;margin-bottom:15px;margin-top:10px" class="font-bold"><?php echo Lang::line(issetParam($this->jsonAttr['title'])) ?></div>          
    <div class="w-full h-full false" style="grid-gap:2%">
    <div class=" ">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 w-full gap-2 " style="gap:1.3rem;overflow: hidden">
        <?php 
        if ($this->datasrc) {            
            foreach($this->datasrc as $index => $row) {
            if ($index <= 5) { ?>                       
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 cloudcard-003-card-row cloud-call-process" style="gap: 1.1rem;" data-processid="<?php echo $renderAtom->renderAtom($row, "positiondrillmeta", $this->positionConfig) ?>">
                <div class="flex items-center justify-between w-full sm:w-full;" style="overflow: hidden;">
                    <div class="flex flex-column">
                        <div class="">
                            <i data-tposition="position1" data-tpath="<?php echo $renderAtom->renderAtomPath("position1", $this->positionConfig); ?>" style="color:<?php echo $renderAtom->renderAtom($row, "position5", $this->positionConfig, "#3975B5") ?>" class="<?php echo $renderAtom->renderAtom($row, "position1", $this->positionConfig, "far fa-smile") ?> text-xl false hover:text-sso "></i>
                        </div>
                        <div class="mt-3">
                            <span class="" data-tposition="position2" data-tpath="<?php echo $renderAtom->renderAtomPath("position2", $this->positionConfig); ?>" style="white-space: nowrap;font-size:14px; color:#252F4A;font-weight: 700;"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position2", $this->positionConfig)) ?></span>
                            <span class="false  text-sm text-gray-600 block" style="color:#67748E">
                            <span data-tposition="position3" data-tpath="<?php echo $renderAtom->renderAtomPath("position3", $this->positionConfig); ?>" class="line-clamp-0"><?php echo $renderAtom->renderAtom($row, "position3", $this->positionConfig) ?></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php }}
        } ?>                  
        </div>
        <?php
            echo '<div class="mt-2 cursor-pointer" style="text-align: right;">
            <span style="color:#5BA6FF;font-size: 11px" data-tposition="position13" data-tpath="'.$renderAtom->renderAtomPath("position13", $this->positionConfig).'" onclick="dataViewAll(this)" data-row={} data-dataviewid="'.issetParam($this->jsonAttr['viewAll']).'">'.Lang::line($renderAtom->renderAtom($row, "position13", $this->positionConfig, 'Бүгдийг харах')).'</span>
        </div>';        
        ?>
    </div>
    </div>
</section>
<style>
    .cloudcard-003-card-row:hover {
        background-color:#5BA6FF !important;
        cursor: pointer;
    }
    .cloudcard-003-card-row:hover span, .cloudcard-003-card-row:hover i {
        color:#fff !important;
    }
</style>
<script>
    $(".cloud-call-process").click(function(){
        Core.blockUI({
          message: "Loading...",
          boxed: true,
        });        
        var metaDataId = $(this).data("processid");
        if (metaDataId) {
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
                appMultiTab({weburl: getCustomerItems.responseJSON.BOOKMARK_URL, metaDataId: getCustomerItems.responseJSON.BOOKMARK_URL+'223999663325', title: getCustomerItems.responseJSON.META_DATA_NAME, type: 'selfurl'});
            } else {
                gridDrillDownLink(this, getCustomerItems.responseJSON.META_DATA_CODE, getCustomerItems.responseJSON.META_TYPE_CODE.toLowerCase(), '1', '',  '', '',metaDataId, '', true, true)
            }
        }
    });
</script>