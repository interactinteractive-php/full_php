<?php $renderAtom = new Mdwidget(); ?>
<?php $uid = getUID(); ?>
<section data-sectioncode="3" class="mb-5 col-span-12 cloudcard_010<?php echo $uid; ?>">
    <div style="font-size:18px;color:#585858;margin-bottom:15px;margin-top:10px" class="font-bold"><?php echo Lang::line(issetParam($this->jsonAttr['title'])) ?></div>          
    <div class="w-full h-full false" style="grid-gap:2%">
        <div class="">
            <div class="d-flex mb20 hidden" style="gap: 20px;">
                <div style="width: 500px">
                    <div class="cloud-call-process" style="width: 500px;gap: 1.1rem;background: transparent" data-processid="">
                        <div class="d-flex justify-content-center mt5" style="overflow: hidden;">
                            <div class="">
                                <div class="">
                                    <img style="width: 500px" src="https://cloudnew.veritech.mn/app/storage/uploads/files/image(2305).png" class="rounded-xl" alt="img">
                                </div>
                            </div>
                        </div>
                    </div>              
                </div>        
                <div>        
                    <div class="p-2 pb10 pt0" style="background: #fff;border-bottom-left-radius: 0.75rem;border-bottom-right-radius: 0.75rem;">
                        <span class="" data-tposition="position2" data-tpath="name" style="font-size: 21px;color:#585858;font-weight: 700;">Метаверс ажлын урсгал</span>
                    </div>
                    <div class="bg-white p-2 pt0 pb0">
                        <span style="color: #a4a4a4;font-size: 15px;">
                        <div class="append-textstyle" style="font-size: 11pt;">
                        <p><strong><span style="font-size: 11pt;">Ажлын урсгал үүсгэхдээ</span></strong></p>

                        <p>&nbsp;</p>

                        <ol>
                                <li>Dataset-н&nbsp;<strong>"Бүтэц"</strong> таб руу орж Засах&nbsp;дарах&nbsp;</li>
                                <li>Дата моделийн тохиргоо хийх цонхны <strong>"Нэмэлт тохиргоо"</strong> хэсгээс Ажлын урсгал ашиглах эсэхийг чеклэх</li>
                                <li>Үзүүлэлтийн <strong>"Ажлын урсгал"</strong> таб руу орох</li>
                                <li><strong>"Төлөв нэмэх"</strong> товч дарж өөрт хэрэгтэй төлвүүдийг нэмэх</li>
                                <li><strong>"Ажлын урсгал&nbsp;нэмэх"</strong> товч даран Ажлын урсгалын нэр өгөөд, эхлэлийн төлөв сонгоод хадгалах</li>
                                <li>Эхлэлийн төлвөөс эхлэн хоорондын логик уялдаа холбоог сумаар зааж өгөх / Шинэ -- Илгээсэн -- Хүлээн авсан -- Хянаж байгаа -- Баталсан/Цуцалсан exc./</li>
                                <li><strong>"Дата"</strong> хэсэг рүү орж төлвийн тохиргоог шалгах</li>
                        </ol>
                        </div>                            
                        </span>
                    </div>          
                    <div class="mt15 p-2">
                        <a class="btn blue btn-circle btn-sm" target="_blank" style="border-radius: 108px;" href="https://help.veritech.mn/lessons/content?filterid=169994323708632&lparentid=169752726502732">Дэлгэрэнгүй</a>                        
                    </div>        
                </div>        
            </div>        

            <div class="d-flex flex-wrap" style="gap:1.3rem">
            <?php 
            $colorSet = [
                '#f9ce27',
                '#2888f9',
                '#1ce984',
                '#f46464',
                '#f46464',
            ];
            $titleSet = [
                'Дата март дээр оролтын утга харуулах',
                'Config, 360 extension ашиглах',
                'Хугацаа гүйлгэх expression',
                'Data quality management',
                '#f46464',
            ];
            $imageSet = [
                'https://astengineering.ca/wp-content/uploads/2020/03/systems-engineering-300x180.jpg',
                'https://cleopatraenterprise.com/wp-content/uploads/2019/08/img-importance-of-metrics-300x180.jpg',
                'https://coinswitch.co/switch/wp-content/uploads/2023/02/How-will-DeFi-Reshape-the-Future-of-Digital-Finance-300x180.jpg',
                'https://www.lockupservices.ca/wp-content/uploads/2022/11/Difference-Between-Access-Control-Systems-And-Biometric-Access-Systems-300x180.jpg',
                'https://astengineering.ca/wp-content/uploads/2020/03/systems-engineering-300x180.jpg',
            ];
            if ($this->datasrc) {            
                foreach($this->datasrc as $index => $row) {
                if ($index <= 3) { ?>                       
                <div style="width: 300px">
                    <div class="cloud-call-process" style="height: 150px;width: 300px;gap: 1.1rem;background: transparent" data-processid="<?php echo $renderAtom->renderAtom($row, "positiondrillmeta", $this->positionConfig) ?>">
                        <div class="d-flex justify-content-center mt15" style="overflow: hidden;">
                            <div class="">
                                <div class="">
                                    <img style="border-top-left-radius: 0.75rem;border-top-right-radius: 0.75rem;height: 150px;width: 300px" src="<?php echo $imageSet[$index] ?>" class="" alt="img">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-2" style="background: #F3F4F6">
                        <span style="color: #a4a4a4;font-size: 13px;">2024.02.24</span>
                    </div>
                    <div class="p-2 pb10 pt0" style="background: #F3F4F6;">
                        <span class="" data-tposition="position2" data-tpath="<?php echo $renderAtom->renderAtomPath("position2", $this->positionConfig); ?>" style="font-size: 14px;white-space: nowrap;color:#585858;font-weight: 700;"><?php echo $titleSet[$index] ?></span>
                    </div>
                    <div class="p-2 pt0 pb20" style="background: #F3F4F6;border-bottom-left-radius: 0.75rem;border-bottom-right-radius: 0.75rem;">
                        <span style="color: #a4a4a4;font-size: 13px;">Дата март нь тохируулсан step query -ийн тусламжтайгаар утга дүүргэх, түүнийг ашиглан аливаа тооцоолол болон тайлан боловсруулах зориулалт бүхий бүтэц юм.</span>
                    </div>                
                </div>
                <?php }}
            } ?>                  
            </div>
        </div>
    </div>
</section>
<style>
    .cloudcard_010<?php echo $uid; ?> .nav-item >.nav-link.active {
        color: #468CE2;
        border-bottom: 2px solid#457B9D;
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