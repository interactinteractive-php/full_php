<div id="window-id-<?php echo $this->uniqId; ?>">
    <div class="pl-3">
        <a href="javascript:;" class="appmarket-back-btn"><i class="fas fa-arrow-left font-size-18" title="Буцах"></i></a>
    </div>
    <div class="d-flex p-3">
        <div class="rounded-circle module-img" style="background-color:#FF7E79;width:150px;height: 150px">
            <img style="padding: 30px;width:150px;height: 150px" src="https://process.veritech.mn/<?php echo (isset($this->getModuleInfo['logo']) ? $this->getModuleInfo['logo'] : 'assets/core/global/img/veritech-erp.png') ?>" class="module-img mr-1 img-fluid my-auto" alt="img">
        </div>
        <div class="pl-3">
            <h3><?php echo $this->getModuleInfo['productname'] ?></h3>
            <p style="color:#67748E"><?php echo $this->getModuleInfo['description'] ?></p>
            <div class="d-flex" style="gap:20px">
                <div class="d-inline-block p-1 px-2 mt-1" style="background-color:#377CD626;border-radius:10px;height: 30px;"><a href="javascript:;" data-basketprice="<?php echo $this->getModuleInfo['basketamount'] ?>" data-price="<?php echo $this->getModuleInfo['unitprice'] ?>" data-basketbookid="<?php echo $this->getModuleInfo['basketbookid'] ?>" onclick="appMarketItemToBasket_<?php echo $this->uniqId; ?>('<?php echo $this->getModuleInfo['itemid'] ?>', this)" style="color:#468CE2;font-weight: bold;"><?php echo $this->getModuleInfo['isbasket'] ? 'Сагсанд хийсэн' : 'Сагсанд хийх'; ?></a></div>
                <div style="background-color: #A0A0A0;width: 1px;"></div>
                <div style="text-align: center;">
                    <div class="am-fs-13" style="color:#67748E;">Үнэлгээ</div>
                    <div class="line-height-0 mt-1">
                        <i class="icon-star-full2 font-size-12" style="color:<?php echo $this->getModuleInfo['rating'] >= 1 ? '#FFBB00' : '#C4C4C4' ?>"></i>
                        <i class="icon-star-full2 font-size-12" style="color:<?php echo $this->getModuleInfo['rating'] >= 2 ? '#FFBB00' : '#C4C4C4' ?>"></i>
                        <i class="icon-star-full2 font-size-12" style="color:<?php echo $this->getModuleInfo['rating'] >= 3 ? '#FFBB00' : '#C4C4C4' ?>"></i>
                        <i class="icon-star-full2 font-size-12" style="color:<?php echo $this->getModuleInfo['rating'] >= 4 ? '#FFBB00' : '#C4C4C4' ?>"></i>
                        <i class="icon-star-full2 font-size-12" style="color:<?php echo $this->getModuleInfo['rating'] >= 5 ? '#FFBB00' : '#C4C4C4' ?>"></i>
                    </div>                        
                </div>                        
                <div style="background-color: #A0A0A0;width: 1px;"></div>
                <div style="text-align: center;">
                    <div class="am-fs-13" style="color:#67748E;">Хэрэглэгч</div>
                    <div style="font-size: 15px;font-weight: bold;"><?php echo $this->getModuleInfo['usercount'] ?></div>                       
                </div>                        
                <div style="background-color: #A0A0A0;width: 1px;"></div>
                <div style="text-align: center;">
                    <div class="am-fs-13" style="color:#67748E;">Ангилал</div>
                    <div style="font-size: 15px;font-weight: bold;"><?php echo $this->getModuleInfo['categoryname'] ?></div>                       
                </div>                        
            </div>
        </div>
    </div>
    <div class="pt-3">
        <div class="content-wrapper">
            <div class="content  justify-content-center align-items-center">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="nav px-3 pt-3 pb-0" style="width:fit-content">
                            <li class="nav-item">
                                <a class="nav-link title-color fs-16 active" id="link-menu-detail-active1" data-toggle="tab" href="#menu-active-detail1" aria-current="page" aria-controls="menu-active-detail1" role="tab" aria-selected="true">Ерөнхий</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link title-color fs-16 " id="link-menu-detail-active2"  data-toggle="tab" href="#menu-active-detail2" aria-current="page" aria-controls="menu-active-detail2" role="tab" aria-selected="false">Системийн боломж, онцлогууд</a>
                            </li>
                        </ul>
                        <div class="tab-content px-1 pt-1">
                            <div class="tab-pane active" id="menu-active-detail1" aria-labelledby="link-menu-detail-active1" role="tabpanel">
                                <div class="row" style="gap:20px;padding:1.5rem!important">
                                    <?php 
                                    if ($this->getModuleInfo['general']) {
                                        foreach ($this->getModuleInfo['general'] as $row) {
                                            echo '<p>'.$row['description'].'</p>';
                                        }
                                    } ?>
                                </div>
                            </div>
                            <div class="tab-pane" id="menu-active-detail2" aria-labelledby="link-menu-detail-active2" role="tabpanel">
                                <div class="row" style="padding:1.5rem !important; padding-left:1rem !important">
                                    <div class="col-md-4">
                                        <div id="treeContainer">
                                            <div id="dataViewStructureTreeView_<?php echo $this->uniqId; ?>" class="tree-demo"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 appmarket-module-feature-description">
                                        <h5 style="color:#a7a7a7">Системийн боломж, онцлогуудыг сонгож дэлгэрэнгүй тайлбар харна уу</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <div>
        <div class="overflow-hidden relative  box  rounded-xl slickwidget py-4" style="background-color:#F0F0F0">
            <div class="appmarket-slick-carousel3">
                <?php 
                if ($this->getModuleInfo['picture']) {
                    foreach ($this->getModuleInfo['picture'] as $row) {
                        echo '<div class="relative rounded-xl bg-image" style="height:330px; width:100%; background-image:url(https://process.veritech.mn/'.$row['picture'].');"></div>';
                    }
                } ?>                        
            </div>
        </div>
    </div>            
</div>

<style>
    .appmarket-slick-carousel3 .slick-dots{
        position: absolute;
        display: block;
        width: 100%;
        padding: 0;
        margin: 0;
        margin-top: 0px;
        list-style: none;
        list-style-type: none;
        text-align: center;
        bottom: -20px;
    }    
    .appmarket-slick-carousel3 .slick-dots{
        margin: auto;
        display: flex;
        grid-gap: 5px;
        text-align: center;
        justify-content: center;
    }
    .appmarket-slick-carousel3 .slick-slide{
        margin-left: 10px;
        border-radius: 10px;
    }
   .slickwidget .slick-dots li {
        width: 6px;
    }    
   .slickwidget .slick-dots li.slick-active button {
        background-color: #377CD6;
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

    .card-style{
        height:260px; 
        width:224px;
        border-radius: 10px;
        background: #FFF;
    }
</style>

<script type="text/javascript">
    var windowId_<?php echo $this->uniqId; ?> = 'div#window-id-<?php echo $this->uniqId; ?>';
    
    if ($("#treeContainer", windowId_<?php echo $this->uniqId; ?>).length) {
        $("#treeContainer", windowId_<?php echo $this->uniqId; ?>).css({
            'max-height': $(window).height() - $("#treeContainer", windowId_<?php echo $this->uniqId; ?>).offset().top - 370,
            'overflow-y': 'auto'
        });
    }    

    $('.appmarket-slick-carousel3').slick({
        infinite: true,
        slidesToShow: 2,
        slidesToScroll: 1,
        arrows: true,
        variableWidth: false,
        autoplay: true,
        dots: true,
        prevArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer;position: absolute;z-index: 10;margin-top: 150px;" class=""><i class="far fa-angle-left" style="font-size:22px;margin: 9px;"></i></div>',
        nextArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer;position: absolute;margin-top: -180px;right: 0;z-index: 10;" class=""><i class="far fa-angle-right" style="font-size:22px;margin: 9px;"></i></div>'       
    });   
    setTimeout(function() {
        $(".appmarket-slick-carousel3").css("width", $(window).width() - 470);
    }, 10);    
    
    function drawTree_<?php echo $this->uniqId; ?>() {
        var dataViewStructureTreeView_<?php echo $this->uniqId; ?> = $('div#dataViewStructureTreeView_<?php echo $this->uniqId; ?>');
        
        dataViewStructureTreeView_<?php echo $this->uniqId; ?>.jstree({
            "core": {
                "themes": {
                    "responsive": true
                },
                "check_callback": true,
                "data": {
                    "url": function (node) {
                        return 'appmarket/getAjaxTree';
                    },
                    "data": function (node) {
                        return {'parent': node.id, 'dataViewId' : '1700798394268919', 'structureMetaDataId': '1700806562995652', criteria:{path:'filterProductId', value:'<?php echo $this->getModuleInfo['id'] ?>'}};
                    }
                }
            },
            "types": {
                "default": {
                    "icon": "icon-folder2 text-orange-300"
                }
            },
            "plugins": ["types", "cookies"]
        }).bind("select_node.jstree", function (e, data) {
            var nid = data.node.id === 'null' ? '' : data.node.id;
            if (data.node.original.rowdata.description) {
                $('.appmarket-module-feature-description').html('<p>'+data.node.original.rowdata.description+'</p>');
            } else {
                $('.appmarket-module-feature-description').html('<h5 style="color:#a7a7a7">Уучлаарай, тайлбар оруулаагүй байна</h5>');
            }
        }).bind('loaded.jstree', function (e, data) {
            if (!data.instance._cnt) {
                $('.appmarket-module-feature-description').html('');
            }
        });
    }    
    drawTree_<?php echo $this->uniqId; ?>();
    
    function appMarketItemToBasket_<?php echo $this->uniqId; ?>(id, elem) {
        if ($(elem).text() === 'Сагсанд хийсэн') {
            PNotify.removeAll();
            new PNotify({
              title: "Warning",
              text: "Сагсанд нэмсэн байна",
              type: "warning",
              sticker: false
            });               
            return;
        }
        if (id === '') {
            PNotify.removeAll();
            new PNotify({
              title: "Warning",
              text: "Барааны мэдээлэлээ оруулна уу!",
              type: "warning",
              sticker: false
            });               
            return;
        }
//        var response = $.ajax({
//          type: "post",
//          url: "api/callProcess",
//          data: {
//                processCode: "basketCheck_004",
//                paramData: { 
//                    filterUserId:  "<?php echo $this->sessionUserKeyId; ?>"
//                }
//          },
//          dataType: "json",
//          async: false
//        });
//        var responseParam = response.responseJSON;        
        var basketTotalAmount = $(elem).data('basketprice');
        var price = $(elem).data('price');
        var basketbookid = $(elem).data('basketbookid');
        var headerId = basketbookid;
              
//        if (responseParam.result) {
//            headerId = responseParam.result.basketbookid;
//            basketTotalAmount = responseParam.result.basketprice;
//        }
        
        $.ajax({
            type: "post",
            url: "appmarket/saveToBasket",
            data: {
                id: headerId,
                itemId: id,
                basketTotalAmount: basketTotalAmount,
                price: price
            },
            beforeSend: function () {
              Core.blockUI({
                message: "Loading...",
                boxed: true
              });
            },            
            dataType: "json",
            async: false,
            success: function (data) {
                PNotify.removeAll();
                if (data.status === "success" && data.result) {
                    $(elem).text('Сагсанд хийсэн');
                    new PNotify({
                      title: "Success",
                      text: "Сагсанд амжилттай нэмэгдлээ",
                      type: "success",
                      sticker: false
                    });
                } else {
                    new PNotify({
                      title: "Warning",
                      text: "Сагсанд нэмэхэд алдаа гарлаа! " + data.text,
                      type: "warning",
                      sticker: false
                    });                    
                }
                Core.unblockUI();
            }
        });        
    }
</script>
    