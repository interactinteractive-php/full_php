<?php $renderAtom = new Mdwidget(); ?>
<?php $uid = getUID(); ?>
<section data-sectioncode="3" class="mb-5 col-span-12">
    <div style="font-size:18px;color:#585858;margin-bottom:15px;margin-top:10px" class="font-bold"><?php echo Lang::line(issetParam($this->jsonAttr['title'])) ?></div>          
    <div class="w-full h-full false" style="grid-gap:2%">
    <div class=" ">
        <div class="d-flex" style="gap: 30px;">
            <div class="rounded-xl p-4" style="background: #F1416C;flex: 1 1 auto;">
                <div class="mt-3" style="">
                    <i class="far fa-draw-circle" style="font-size: 32px;"></i>
                </div>
                <div class="my-5 d-flex">
                    <h2 style="font-size: 50px;">9.2M</h2>
                    <div class="ml30 mt25" style="font-size: 15px;">Миний орлого</div>
                </div>
                <div>
                    <div class="mt-3" style="">
                        <span class="" data-tposition="position2" data-tpath="<?php echo $renderAtom->renderAtomPath("position2", $this->positionConfig); ?>" style="font-size: 20px;color:#fff;font-weight: 700;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;">178</span>
                    </div>            
                    <div class="my-3 mt6" style=";color: #fff;font-size: 12px;">
                        <span style="display: -webkit-box;-webkit-line-clamp: 4;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;">Нийт хөгжүүлсэн процесс</span>
                    </div>
                </div>
            </div>               
            <div class="rounded-xl p-4" style="background: #7239EA;flex: 1 1 auto;">
                <div class="mt-3" style="">
                    <i class="far fa-clock" style="font-size: 32px;"></i>
                </div>
                <div class="my-5 d-flex">
                    <h2 style="font-size: 50px;">427</h2>
                    <div class="ml30 mt25" style="font-size: 15px;">Хөгжүүлсэн цаг</div>
                </div>                
                <div>                
                    <div class="mt-3" style="">
                        <span class="" data-tposition="position2" data-tpath="<?php echo $renderAtom->renderAtomPath("position2", $this->positionConfig); ?>" style="font-size: 20px;color:#fff;font-weight: 700;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;">15</span>
                    </div>            
                    <div class="my-3 mt6" style=";color: #fff;font-size: 12px;">
                        <span style="display: -webkit-box;-webkit-line-clamp: 4;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;">Нийт авсан захиалга</span>
                    </div>
                </div>
            </div>               
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