<?php $renderAtom = new Mdwidget(); ?>
<?php $uid = getUID(); ?>
<section data-sectioncode="3" class="mb-5 col-span-12 cloudcard_010<?php echo $uid; ?>">
    <div style="font-size:18px;color:#585858;margin-bottom:15px;margin-top:10px" class="font-bold"><?php echo Lang::line(issetParam($this->jsonAttr['title'])) ?></div>          
    <ul class="nav pb-0" style="width:fit-content">
        <li class="nav-item">
            <a class="nav-link title-color active" id="link-menu-active1" data-toggle="tab" href="#menu-active1" aria-current="page" aria-controls="menu-active1" role="tab" aria-selected="true">Эрэлттэй</a>
        </li>
        <li class="nav-item">
            <a class="nav-link title-color" id="link-menu-active2" data-toggle="tab" href="#menu-active2" aria-current="page" aria-controls="menu-active2" role="tab" aria-selected="false">Шинээр нэмэгдсэн</a>
        </li>
    </ul>   
    <div class="tab-content pt-1">
        <div class="tab-pane active" id="menu-active1" aria-labelledby="link-menu-active1" role="tabpanel">
            <div class="w-full h-full false" style="grid-gap:2%">
            <div class=" ">
                <div class="d-flex flex-wrap" style="gap:1.3rem">
                <?php 
                $colorSet = [
                    '#f9ce27',
                    '#2888f9',
                    '#1ce984',
                    '#f46464',
                    '#f46464',
                ];
                $imageSet = [
                    'https://www.kerlink.com/wp-content/uploads/2021/03/Factory-300x180.jpg',
                    'https://cleopatraenterprise.com/wp-content/uploads/2019/08/img-importance-of-metrics-300x180.jpg',
                    'https://coinswitch.co/switch/wp-content/uploads/2023/02/How-will-DeFi-Reshape-the-Future-of-Digital-Finance-300x180.jpg',
                    'https://www.lockupservices.ca/wp-content/uploads/2022/11/Difference-Between-Access-Control-Systems-And-Biometric-Access-Systems-300x180.jpg',
                    'https://astengineering.ca/wp-content/uploads/2020/03/systems-engineering-300x180.jpg',
                ];
                if ($this->datasrc) {            
                    foreach($this->datasrc as $index => $row) {
                    if ($index <= 3) { ?>                       
                    <div style="width: 300px">
                        <div class="cloud-call-process" style="height: 180px;width: 300px;gap: 1.1rem;background: transparent" data-processid="<?php echo $renderAtom->renderAtom($row, "positiondrillmeta", $this->positionConfig) ?>">
                            <div class="d-flex justify-content-center mt15" style="overflow: hidden;">
                                <div class="">
                                    <div class="">
                                        <img style="border-top-left-radius: 0.75rem;border-top-right-radius: 0.75rem;height: 180px;width: 300px" src="<?php echo $imageSet[$index] ?>" class="" alt="img">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-2" style="background: #F3F4F6">
                            <span style="color: #a4a4a4;font-size: 13px;">Хөгжүүлсэн: Veritech</span>
                        </div>
                        <div class="" style="padding:0 0.75rem 0 0.75rem;background: #F3F4F6">
                            <div style="font-size: 11px;">
                                <span><i class="fas solid fa-star" style="color:#ffaf3c"></i></span>
                                <span><i class="fas solid fa-star" style="color:#ffaf3c"></i></span>
                                <span><i class="fas solid fa-star" style="color:#ffaf3c"></i></span>
                                <span><i class="fas solid fa-star" style="color:#ffaf3c"></i></span>
                                <span><i class="fas solid fa-star" style="color:#ccc"></i></span>
                                <span style="color: #a4a4a4;font-size: 11px;">(4.1) 150</span>
                            </div>
                        </div>
                        <div class="p-2 pb15" style="background: #F3F4F6;border-bottom-left-radius: 0.75rem;border-bottom-right-radius: 0.75rem;">
                            <span class="" data-tposition="position2" data-tpath="<?php echo $renderAtom->renderAtomPath("position2", $this->positionConfig); ?>" style="font-size: 14px;white-space: nowrap;color:#585858;font-weight: 700;"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position2", $this->positionConfig)) ?></span>
                        </div>
                    </div>
                    <?php }}
                } ?>                  
                </div>
            </div>
            </div>
        </div>
        <div class="tab-pane" id="menu-active2" aria-labelledby="link-menu-active1" role="tabpanel">
            <div class="w-full h-full false" style="grid-gap:2%">
            <div class=" ">
                <div class="d-flex flex-wrap" style="gap:1.3rem">
                <?php 
                $colorSet = [
                    '#f9ce27',
                    '#2888f9',
                    '#1ce984',
                    '#f46464',
                    '#f46464',
                ];
                $imageSet = [
                    'https://www.kerlink.com/wp-content/uploads/2021/03/Factory-300x180.jpg',
                    'https://cleopatraenterprise.com/wp-content/uploads/2019/08/img-importance-of-metrics-300x180.jpg',
                    'https://coinswitch.co/switch/wp-content/uploads/2023/02/How-will-DeFi-Reshape-the-Future-of-Digital-Finance-300x180.jpg',
                    'https://www.lockupservices.ca/wp-content/uploads/2022/11/Difference-Between-Access-Control-Systems-And-Biometric-Access-Systems-300x180.jpg',
                    'https://astengineering.ca/wp-content/uploads/2020/03/systems-engineering-300x180.jpg',
                ];
                if ($this->datasrc) {  
                    foreach($this->datasrc as $index => $row) {
                    if ($index <= 3) { ?>                       
                    <div style="width: 300px">
                        <div class="cloud-call-process" style="height: 180px;width: 300px;gap: 1.1rem;background: transparent" data-processid="<?php echo $renderAtom->renderAtom($row, "positiondrillmeta", $this->positionConfig) ?>">
                            <div class="d-flex justify-content-center mt15" style="overflow: hidden;">
                                <div class="">
                                    <div class="">
                                        <img style="border-top-left-radius: 0.75rem;border-top-right-radius: 0.75rem;height: 180px;width: 300px" src="<?php echo $imageSet[array_rand($imageSet)] ?>" class="" alt="img">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-2" style="background: #F3F4F6">
                            <span style="color: #a4a4a4;font-size: 13px;">Хөгжүүлсэн: Veritech</span>
                        </div>
                        <div class="" style="padding:0 0.75rem 0 0.75rem;background: #F3F4F6">
                            <div style="font-size: 11px;">
                                <span><i class="fas solid fa-star" style="color:#ffaf3c"></i></span>
                                <span><i class="fas solid fa-star" style="color:#ffaf3c"></i></span>
                                <span><i class="fas solid fa-star" style="color:#ffaf3c"></i></span>
                                <span><i class="fas solid fa-star" style="color:#ffaf3c"></i></span>
                                <span><i class="fas solid fa-star" style="color:#ccc"></i></span>
                                <span style="color: #a4a4a4;font-size: 11px;">(4.1) 150</span>
                            </div>
                        </div>
                        <div class="p-2 pb15" style="background: #F3F4F6;border-bottom-left-radius: 0.75rem;border-bottom-right-radius: 0.75rem;">
                            <span class="" data-tposition="position2" data-tpath="<?php echo $renderAtom->renderAtomPath("position2", $this->positionConfig); ?>" style="font-size: 14px;white-space: nowrap;color:#585858;font-weight: 700;"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position2", $this->positionConfig)) ?></span>
                        </div>
                    </div>
                    <?php }}
                } ?>                  
                </div>
            </div>
            </div>
        </div>
    </div>
</section>
<style>
    .cloudcard_010<?php echo $uid; ?> .nav-item >.nav-link.active {
        color: #468CE2;
        border-bottom: 2px solid#457B9D;
    }
    .cloudcard_010<?php echo $uid; ?> .nav-item >.nav-link {
        font-size: 14px;
        color:#585858;
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