<?php 
    $renderAtom = new Mdwidget(); 
    $uid = getUID(); 
?>
<section data-sectioncode="3" class="mb-5 col-span-12">
    <div style="font-size:18px;color:#585858;margin-bottom:15px;margin-top:10px" class="font-bold"><?php echo Lang::line(issetParam($this->jsonAttr['title'])) ?></div>          
    <div class="w-full h-full false" style="grid-gap:2%">
        <div >
            <?php 
            $colorSet = [
                '#f9ce27',
                '#2888f9',
                '#1ce984',
                '#f46464',
                '#f46464',
            ];

            if ($this->datasrc) { ?>
            <div class="d-flex" style="gap:1.3rem;overflow: hidden">
            <?php
                foreach($this->datasrc as $index => $row) {
                    if ($index <= 3) { ?>                       
                        <div class="rounded-xl p-3" style="width: 250px;background: #F3F4F6;">
                            <div class="mt-3">
                                <i class="far fa-draw-circle" style="font-size: 26px;"></i>
                            </div>
                            <div class="mt-3 d-flex">
                                <span class="text-one-line" data-tposition="position2" data-tpath="<?php echo $renderAtom->renderAtomPath("position2", $this->positionConfig); ?>" style="font-size: 14px;color:#585858;font-weight: 700;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position2", $this->positionConfig)) ?></span>
                                <div class="ml-auto w-auto align-self-center" style="padding:0 0.75rem 0 0.75rem;background: #F3F4F6">
                                    <div style="font-size: 11px;">
                                        <span><i class="fas solid fa-star" style="color:#ffaf3c"></i></span>
                                        <span><i class="fas solid fa-star" style="color:#ffaf3c"></i></span>
                                        <span><i class="fas solid fa-star" style="color:#ffaf3c"></i></span>
                                        <span><i class="fas solid fa-star" style="color:#ffaf3c"></i></span>
                                        <span><i class="fas solid fa-star" style="color:#ccc"></i></span>
                                    </div>
                                </div>
                            </div>            
                            <div class="my-3" style=";color: #a4a4a4;font-size: 12px;">
                                <span class="text-three-line" style="display: -webkit-box;-webkit-line-clamp: 4;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;">Сегментийн датаг жагсаалтын дээр картаар харах боломж нэмэгдлээ. Энэ нь үндсэн жагсаалтаас гадна тухайн жагсаалтын датаг сегментэд холбосон бол сегментүүдийг жагсаалтын дээр таб хэлбэрээр харах боломжтой болсон. </span>
                            </div>
                        </div>
                    <?php }
                } ?>
                <a href="javascript:;" class="rounded-xl p-3" style="width: 250px;background: #F3F4F6;" onclick="redirectFunction(this, 'mdhelpdesk/ssoLogin')">
                    <div class="mt75" style="text-align: center">
                        <i class="far fa-arrow-right" style="font-size: 48px;color: #c1c1c1;"></i>
                    </div>
                </a>    
                <a href="javascript:;" class="newtab d-none " target="_blank"></a>
            </div>
            <div class="mt-2 cursor-pointer " style="text-align: right;">
                <?php echo '<span style="color:#A0A0A0;font-size: 11px" data-tposition="position4" >'.Lang::line('Бүгдийг харах').'</span>'; ?>
            </div>    
            <?php } else { ?>
                <div class="row">
                    <img src="middleware/assets/img/icon/no-data.png" alt="no-data" class="w-auto mx-auto"/>              
                </div>
            <?php } ?>   
        </div>
    </div>
</section>
<style type="text/css">
    .cloudcard-003-card-row:hover {
        background-color:#5BA6FF !important;
        cursor: pointer;
    }
    .cloudcard-003-card-row:hover span, .cloudcard-003-card-row:hover i {
        color:#fff !important;
    }
</style>
<script type="text/javascript">
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