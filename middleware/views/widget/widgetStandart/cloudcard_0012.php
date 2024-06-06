<?php $renderAtom = new Mdwidget(); ?>
<?php $uid = getUID(); ?>
<section data-sectioncode="3" class="mb-5 col-span-12">
    <div style="font-size:18px;color:#585858;margin-bottom:15px;margin-top:10px" class="font-bold"><?php echo Lang::line(issetParam($this->jsonAttr['title'])) ?></div>          
    <div class="w-full h-full false" style="grid-gap:2%">
    <div class=" ">
        <div class="d-flex" style="gap:1.3rem;overflow: hidden">
        <?php 
        $colorSet = [
            '#f9ce27',
            '#2888f9',
            '#1ce984',
            '#f46464',
            '#f46464',
        ];
        if ($this->datasrc) {            
            foreach($this->datasrc as $index => $row) {
            if ($index <= 10) { ?>                       
            <div style="width: 130px;">
                <div class="rounded-xl p-4 cloudcard-003-card-row cloud-call-process" style="height: 130px;width: 130px;gap: 1.1rem;background: <?php echo $colorSet[array_rand($colorSet)] ?>" data-processid="<?php echo $renderAtom->renderAtom($row, "positiondrillmeta", $this->positionConfig) ?>">
                    <div class="d-flex justify-content-center mt15" style="overflow: hidden;">
                        <div class="">
                            <div class="">
                                <img style="width: 45px;" src="https://process.veritech.mn/assets/core/global/img/veritech-erp.png" class="" alt="img">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3" style="text-align: center">
                    <span class="" data-tposition="position2" data-tpath="<?php echo $renderAtom->renderAtomPath("position2", $this->positionConfig); ?>" style="font-size: 14px;white-space: nowrap;color:#585858;font-weight: 700;"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position2", $this->positionConfig)) ?></span>
                </div>            
                <div class="mt3" style="text-align: center;color: #a4a4a4;font-size: 11px;">
                    <span>1 сарын 28 2024</span>
                </div>
            </div>
            <?php }}
        } ?>                  
        </div>
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