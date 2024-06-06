<div class="col-md-12">
    <div class="card light shadow card-multi-tab">
        <div class="card-header header-elements-inline tabbable-line">
            <ul class="nav nav-tabs card-multi-tab-navtabs">
                <li data-type="layout">
                    <a href="#appmarket_tab" class="nav-link active" data-title="App market" data-toggle="tab"><i class="fa fa-caret-right"></i> App market<span><i class="fa fa-times-circle"></i></span></a>
                </li>
            </ul>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content card-multi-tab-content">
                <div class="tab-pane active" id="appmarket_tab">
                    <div class="layout-fullscreen-btn">
                        <!--<button type="button" class="btn btn-sm btn-icon mr-1" title="Fullscreen" onclick="layoutFullScreen(this);">
                            <i class="fa fa-expand"></i>
                        </button>-->
                        <button type="button" class="btn btn-sm btn-icon layout-manual-refresh-btn mr-1 d-none" title="Refresh" style="height: 22px;width: 22px;padding: 0;top: 28px;right: -27px;">
                            <i class="fa fa-refresh"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-icon mr-1 layout-print-btn d-none" title="<?php echo $this->lang->line('print_btn'); ?>" style="height: 22px;width: 22px;padding: 0;">
                            <i class="fa fa-print"></i>
                        </button>
                    </div>

                    <div class="appmarket-wrapper">
                        <div id="window-id-<?php echo $this->uniqId; ?>">
                            <div class="appmarket d-flex">

                                <?php include_once 'leftMenu.php'; ?>

                                <div class="appmarket-body">
                                    <div class="appmarket-basket-page">
                                        <?php if ($this->getBasket && !$this->getBasket['smbasketdtl']) {
                                            echo '<div class="px-3 pt-3 pb-0"><h3 style="color:#a2a2a2">Таны сагс хоосон байна</h3></div>';
                                        } else { ?>
                                        <div class="full-header-info">
                                            <div class="px-3 pt-3 pb-0">
                                                <h3>Сагс</h3>
                                            </div>
                                            <div class="">
                                                <form id="appMarketBasketForm" method="post">
                                                    <div class="content-wrapper">
                                                        <div class="content  justify-content-center align-items-center">
                                                            <div class="row">
                                                                <div class="col-md-12 px-3 pb-0">
                                                                    <table class="table" id="appmarket-basket-tablelist">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col" class="w-50 am-fs-13">Бүтээгдэхүүн</th>
                                                                                <th scope="col" class="am-fs-13">Хэрэглэгчийн тоо</th>
                                                                                <th scope="col" class="am-fs-13">Хугацаа /сараар/</th>
                                                                                <th scope="col" class="am-fs-13" style="width: 150px;text-align: right;padding-right: 20px;">Дүн</th>
                                                                                <th style="width:20px"></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php if ($this->getBasket && $this->getBasket['smbasketdtl']) {
                                                                                foreach ($this->getBasket['smbasketdtl'] as $row) { ?>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <input type="hidden" name="itemId[]" value="<?php echo $row['itemid'] ?>">
                                                                                            <input type="hidden" name="dtlId[]" value="<?php echo $row['id'] ?>">
                                                                                            <input type="hidden" name="basketBookId[]" value="<?php echo $row['basketbookid'] ?>">
                                                                                            <input type="hidden" name="amount[]" value="<?php echo $row['unitprice'] ?>">
                                                                                            <input type="hidden" name="lineTotalAmount[]" value="<?php echo $row['linetotalprice'] ?>">
                                                                                            <div class="d-flex p-3" style="padding-left: 0px !important;">
                                                                                                <div class="rounded-circle module-img" style="background-color:#FF7E79;width:70px;height: 70px">
                                                                                                    <img style="padding: 20px;width:70px;height: 70px" src="https://process.veritech.mn/<?php echo (isset($row['profilephoto']) ? $row['profilephoto'] : 'assets/core/global/img/veritech-erp.png') ?>" class="module-img mr-1 img-fluid my-auto" alt="img">
                                                                                                </div>
                                                                                                <div class="pl-2">
                                                                                                    <h4><?php echo $row['itemname'] ?></h4>
                                                                                                    <p style="color:#67748E"><?php echo $row['description'] ?></p>
                                                                                                    <div class="d-inline-block pt-1 pl-0 mt-1"><a href="javascript:;" style="color:#468CE2;font-weight: bold;font-size: 15px;"><?php echo Number::formatMoney($row['unitprice']) ?>₮</a></div>
                                                                                                </div>
                                                                                            </div>    
                                                                                        </td>
                                                                                        <td>
                                                                                            <ul class="list-group list-group-horizontal market_basket_plus" style="background: transparent;height: 45px;">
                                                                                                <li class="list-group-item px-2 py-1 border-right-0 minus appmarket-basket-minus" style="border-top-left-radius: 20px;border-bottom-left-radius: 20px;" index="0"><i class="fa fa-minus"></i></li>
                                                                                                <li class="list-group-item px-2 py-1 border-x-0" id="return0" style="font-weight: bold;"><input type="text" name="basketUserQty[]" value="<?php echo $row['usernumber'] ? $row['usernumber'] : 1 ?>"></li>
                                                                                                <li class="list-group-item px-2 py-1 border-left-0 plus appmarket-basket-plus" index="0" style="border-top-right-radius: 20px;border-bottom-right-radius: 20px;"><i class="fa fa-plus"></i></li>
                                                                                            </ul>
                                                                                        </td>
                                                                                        <td>
                                                                                            <ul class="list-group list-group-horizontal market_basket_plus" style="background: transparent;height: 45px;">
                                                                                                <li class="list-group-item px-2 py-1 border-right-0 minusMonth appmarket-basket-minus-month" index="00" style="border-top-left-radius: 20px;border-bottom-left-radius: 20px;"><i class="fa fa-minus"></i></li>
                                                                                                <li class="list-group-item px-1 py-1 border-x-0" id="return00" style="font-weight: bold;"><input type="text" name="basketQty[]" value="<?php echo $row['monthnumber'] ? $row['monthnumber'] : 1 ?>"></li>
                                                                                                <li class="list-group-item px-2 py-1 border-left-0 plusMonth appmarket-basket-plus-month" index="00" style="border-top-right-radius: 20px;border-bottom-right-radius: 20px;"><i class="fa fa-plus"></i></li>
                                                                                            </ul>
                                                                                        </td>
                                                                                        <td> 
                                                                                            <div class="d-flex justify-content-end">
                                                                                                <div class="d-inline-block p-1 px-2 mt-1">
                                                                                                    <a href="javascript:;" style="font-weight: bold; color:#585858;font-size: 15px;" class="basket-linetotal-amount"><?php echo Number::formatMoney($row['linetotalprice']) ?>₮</a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td style="text-align: center">
                                                                                            <a href= "javascript:;" title="" onclick="removeBasket_<?php echo $this->uniqId; ?>(this, '<?php echo $row['id'] ?>')" class="btn">
                                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 25" fill="none">
                                                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M16.0002 2.25012V3.50012H21.2502C21.6645 3.50012 22.0002 3.83591 22.0002 4.25012C22.0002 4.66434 21.6645 5.00012 21.2502 5.00012H2.75024C2.33603 5.00012 2.00024 4.66434 2.00024 4.25012C2.00024 3.83591 2.33603 3.50012 2.75024 3.50012H8.00024V2.25012C8.00024 1.28362 8.78375 0.500122 9.75024 0.500122H14.2502C15.2167 0.500122 16.0002 1.28362 16.0002 2.25012ZM9.50024 2.25012C9.50024 2.11205 9.61217 2.00012 9.75024 2.00012H14.2502C14.3883 2.00012 14.5002 2.11205 14.5002 2.25012V3.50012H9.50024V2.25012Z" fill="#F43F5E"/>
                                                                                                <path d="M4.99681 6.67787C4.95691 6.26558 4.59034 5.9637 4.17805 6.0036C3.76577 6.0435 3.46389 6.41007 3.50378 6.82236L4.91633 21.4187C5.00314 22.3157 5.75699 23.0001 6.65819 23.0001H17.3424C18.2436 23.0001 18.9975 22.3157 19.0843 21.4187L20.4968 6.82236C20.5367 6.41007 20.2348 6.0435 19.8225 6.0036C19.4103 5.9637 19.0437 6.26558 19.0038 6.67787L17.5912 21.2742C17.5788 21.4023 17.4711 21.5001 17.3424 21.5001H6.65819C6.52945 21.5001 6.42176 21.4023 6.40936 21.2742L4.99681 6.67787Z" fill="#F43F5E"/>
                                                                                                <path d="M9.20623 8.00141C9.61972 7.97709 9.97465 8.29257 9.99897 8.70607L10.499 17.2061C10.5233 17.6196 10.2078 17.9745 9.79431 17.9988C9.38081 18.0231 9.02588 17.7077 9.00156 17.2942L8.50156 8.79416C8.47724 8.38066 8.79273 8.02573 9.20623 8.00141Z" fill="#F43F5E"/>
                                                                                                <path d="M15.499 8.79416C15.5233 8.38066 15.2078 8.02573 14.7943 8.00141C14.3808 7.97709 14.0259 8.29257 14.0016 8.70607L13.5016 17.2061C13.4772 17.6196 13.7927 17.9745 14.2062 17.9988C14.6197 18.0231 14.9746 17.7077 14.999 17.2942L15.499 8.79416Z" fill="#F43F5E"/>
                                                                                                </svg>
                                                                                            </a>
                                                                                        </td>
                                                                                    </tr>
                                                                            <?php }} ?>
                                                                        </tbody>
                                                                    </table>
                                                                    <hr>
                                                                    <div class="d-flex p-2">
                                                                        <div class="w-75">
                                                                            <h3>Description</h3>
                                                                            <p class="text-justify mr-5">
                                                                            “Veritech CMS” Гэрээний удирдлагын систем нь байгууллагын гэрээ контракт болон түүнтэй холбоотой аливаа асуудлыг програм хангамжийн
                                                                            шийдлээр механик ажиллагааг хөнгөвчлөх, цаасан хэлбэрээс татгалзаж захиалагч, гүйцэтгэгч гэх мэт бүх харилцагчийг өөрийн эрх үүргийн 
                                                                            хэмжээнд ашиглах боломжоор хангах, бүх төрлийн гэрээ контрактыг нэгдсэн удирдлагаар хянах боломжоор хангахад чиглэнэ.

                                                                            Гэрээнүүдээ зөв удирдасанаар бүхнийг алдаагүй гүйцэтгэх боломжтой. Танай байгууллага шүүх хяналтын байгууллагын өмнө ч асуудалгүй байж чадна.
                                                                            </p>
                                                                        </div>
                                                                        <div class="w-25">
                                                                            <ul class="list-group border-none" style="background: transparent;">
                                                                                <li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center rounded p-1" style="color: #3C3C3C;border-radius: 5px !important;">Гишүүний хөнгөлөлтийн эрх эдлэх
                                                                                    <div class="btn btn-light d-flex justify-content-between align-items-center rounded ml-1" style="border-radius: 5px !important;border:none">
                                                                                        <span class="mr-1">
                                                                                        -10
                                                                                        </span>
                                                                                        <div>
                                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="none">
                                                                                                <g clip-path="url(#clip0_1926_3873)">
                                                                                                <path d="M7.22979 4.99011C7.28467 5.28323 7.57545 5.5144 7.91846 5.45026C8.25151 5.38073 8.44092 5.06139 8.37862 4.7612C8.30545 4.41214 7.96069 4.21768 7.63405 4.31511C7.34229 4.40218 7.16971 4.70347 7.22979 4.99011Z" fill="#585858"/>
                                                                                                <path d="M19.8531 13.1745C16.6648 3.89351 16.8841 4.52939 16.8705 4.49778C16.5386 3.72968 15.8943 3.13255 15.1029 2.85946C14.9895 2.82036 8.57195 1.31914 8.38648 1.25492C7.81767 1.05793 7.02742 1.16836 6.47264 1.67861L0.972917 6.73925C0.354619 7.30794 0 8.11749 0 8.96041V17.922C0 18.9781 0.854594 19.8373 1.90505 19.8373H13.6712C14.7217 19.8373 15.5762 18.9781 15.5762 17.922V17.0558L18.8558 15.6522C19.8093 15.2396 20.2529 14.1322 19.8531 13.1745ZM13.6712 18.6654H1.90505C1.50078 18.6654 1.17191 18.3319 1.17191 17.922V8.96041C1.17191 8.44442 1.38855 7.94917 1.76634 7.6017C6.47818 3.26611 3.56576 5.9704 7.28481 2.54066C7.47876 2.36191 7.74654 2.29011 8.01213 2.37019C8.24952 2.44109 7.83064 2.09991 13.81 7.60178C14.1877 7.94921 14.4043 8.44442 14.4043 8.96041V17.922C14.4043 18.3319 14.0754 18.6654 13.6712 18.6654ZM18.3926 14.5758L15.5762 15.7811V8.96041C15.5762 8.11749 15.2216 7.30794 14.6034 6.73933L10.5076 2.97059L14.7461 3.97633C15.2088 4.14379 15.5859 4.49435 15.7863 4.94342C18.9685 14.2067 18.7539 13.5849 18.7678 13.617C18.9278 13.9869 18.7585 14.4174 18.3926 14.5758Z" fill="#585858"/>
                                                                                                <path d="M9.45184 8.40224C9.15351 8.277 8.81002 8.41747 8.68479 8.71584L5.83225 15.5145C5.66951 15.9023 5.95698 16.3273 6.3723 16.3273C6.60125 16.3273 6.81876 16.1923 6.9129 15.9679L9.76544 9.16929C9.89064 8.87085 9.75024 8.52744 9.45184 8.40224Z" fill="#585858"/>
                                                                                                <path d="M6.70578 10.091C6.70578 8.9601 5.87752 8.04004 4.8594 8.04004C3.84129 8.04004 3.01306 8.9601 3.01306 10.091C3.01306 11.2219 3.84133 12.1419 4.85944 12.1419C5.87756 12.1419 6.70578 11.2219 6.70578 10.091ZM4.85944 10.97C4.49385 10.97 4.18497 10.5675 4.18497 10.091C4.18497 9.6145 4.49381 9.21195 4.85944 9.21195C5.22508 9.21195 5.53391 9.6145 5.53391 10.091C5.53387 10.5675 5.22504 10.97 4.85944 10.97Z" fill="#585858"/>
                                                                                                <path d="M10.7718 12.6885C9.75368 12.6885 8.92542 13.6085 8.92542 14.7394C8.92542 15.8703 9.75368 16.7904 10.7718 16.7904C11.7899 16.7904 12.6182 15.8703 12.6182 14.7394C12.6182 13.6085 11.7899 12.6885 10.7718 12.6885ZM10.7718 15.6185C10.4062 15.6185 10.0973 15.2159 10.0973 14.7394C10.0973 14.2629 10.4062 13.8604 10.7718 13.8604C11.1374 13.8604 11.4463 14.2629 11.4463 14.7394C11.4463 15.2159 11.1374 15.6185 10.7718 15.6185Z" fill="#585858"/>
                                                                                                </g>
                                                                                                <defs>
                                                                                                <clipPath id="clip0_1926_3873">
                                                                                                <rect width="20" height="20" fill="white" transform="translate(0 0.5)"/>
                                                                                                </clipPath>
                                                                                                </defs>
                                                                                            </svg>
                                                                                        </div>
                                                                                    </div>
                                                                                </li>
                                                                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">Хөнглөлт
                                                                                    <span>
                                                                                    -3%
                                                                                    </span>
                                                                                </li>
                                                                                <li class="list-group-item d-flex justify-content-between align-items-center p-0">
                                                                                    <input type="hidden" value="<?php echo issetParam($this->getBasket['total']) ?>" name="totalAmount"/>
                                                                                    <input type="hidden" value="" name="qpayInvoiceId"/>
                                                                                    <h3 class="font-weight-bold" style="font-size: 12px;">Нийт дүн</h3>
                                                                                    <h3 class="font-weight-bold total"><?php echo Number::formatMoney(issetParam($this->getBasket['total'])) ?>₮</h3>
                                                                                </li>
                                                                                <li onclick="qpayPayment_<?php echo $this->uniqId; ?>(this)" class="list-group-item list-group-sm d-flex justify-content-center p-0 br-30 align-items-center" style="background:#468CE2; border-radius:30px;cursor: pointer">
                                                                                    <h1 class="font-weight-bold text-white pt-2 text-uppercase am-fs-13">Худалдан авалт хийх</h1>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>  
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div> 
                                        </div>     
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>                    

<link href="<?php echo autoVersion('assets/custom/css/appmarket.css'); ?>" rel="stylesheet"/>
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
    var suto = 0;
    var posQpayQrCheckInterval_<?php echo $this->uniqId; ?>;
    
    $('.appmarket_menu_wrapper').on('click', '.nav-link', function() {
        $('.appmarket_menu_wrapper').find('.nav-item-selected').removeClass('nav-item-selected');
        $(this).addClass('nav-item-selected');
        window.location.href = URL_APP+'appmarket/index/'+$(this).data('id')+'&mmid=1701920318761819&mid=1701920318761819';
    });      
    
    function basketSumTotal_<?php echo $this->uniqId; ?>() {
        suto = 0;
        $('#appmarket-basket-tablelist tbody tr', windowId_<?php echo $this->uniqId; ?>).each(function(){
            suto += Number($(this).find('input[name="lineTotalAmount[]"]').val());
        });
        $('.total', windowId_<?php echo $this->uniqId; ?>).text(pureNumberFormat(suto)+'₮');
        $('input[name="totalAmount"]', windowId_<?php echo $this->uniqId; ?>).val(suto);
    }
    
    function removeBasket_<?php echo $this->uniqId; ?>(elem, id) {
        var $dialogName = "dialog-appmarket-confirm";
        if (!$("#" + $dialogName).length) {
          $('<div id="' + $dialogName + '"></div>').appendTo("body");
        }
        var $dialog = $("#" + $dialogName);

        $dialog.empty().append('Та устгахдаа итгэлтэй байна уу?');
        $dialog.dialog({
          cache: false,
          resizable: false,
          bgiframe: true,
          autoOpen: false,
          title: "Confirm",
          width: 300,
          height: "auto",
          modal: true,
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: "Тийм",
              class: "btn green-meadow btn-sm",
              click: function () {
                $.ajax({
                    type: "post",
                    url: "api/callProcess",
                    data: {
                      processCode: "appMarketBasketSingleProductRemove",
                      paramData: { 
                          id: id
                      }
                    },
                    beforeSend: function () {
                      Core.blockUI({
                        message: "Loading...",
                        boxed: true
                      });
                    },            
                    dataType: "json",
                    success: function (data) {
                        PNotify.removeAll();
                        if (data.status === "success") {
                            new PNotify({
                              title: "Success",
                              text: "Сагснааас амжилттай устгагдлаа",
                              type: "success",
                              sticker: false
                            });                               
                            $(elem).closest('tr').remove();
                            basketSumTotal_<?php echo $this->uniqId; ?>();
                        } else {
                            new PNotify({
                              title: "Warning",
                              text: data.text,
                              type: "warning",
                              sticker: false
                            });                    
                        }
                        Core.unblockUI();
                        $dialog.dialog("close");
                    }
                });                                      
              },
            },
            {
              text: "Үгүй",
              class: "btn blue-madison btn-sm",
              click: function () {
                $dialog.dialog("close");
              },
            },
          ],
        });

        $dialog.dialog("open");
    }
    
    function saveBasketRow_<?php echo $this->uniqId; ?>($self) {
        var $tr = $self.closest('tr');
        var dtlId = $tr.find('input[name="dtlId[]"]').val();
        var basketBookId = $tr.find('input[name="basketBookId[]"]').val();
        var itemId = $tr.find('input[name="itemId[]"]').val();
        var userNumber = $tr.find('input[name="basketUserQty[]"]').val();
        var monthNumber = $tr.find('input[name="basketQty[]"]').val();
        var lineTotalPrice = $tr.find('input[name="lineTotalAmount[]"]').val();
        
        basketSumTotal_<?php echo $this->uniqId; ?>();
        
        $.ajax({
            type: "post",
            url: "api/callProcess",
            data: {
              processCode: "smBasketDtl_002",
              paramData: { 
                  id: '<?php echo $this->getBasket['id'] ?>',
                  total: suto,
                  basketBookDtl: {
                    id: dtlId,
                    basketBookId: basketBookId,
                    itemId: itemId,
                    userNumber: userNumber,
                    monthNumber: monthNumber,
                    lineTotalPrice: lineTotalPrice
                }
              }
            },
            beforeSend: function () {
              Core.blockUI({
                message: "Loading...",
                boxed: true
              });
            },            
            dataType: "json",
            success: function (data) {
                PNotify.removeAll();
                if (data.status === "success" && data.result) {
                } else {
                    new PNotify({
                      title: "Warning",
                      text: data.text,
                      type: "warning",
                      sticker: false
                    });                    
                }
                Core.unblockUI();
            }
        });          
    }
    
    function qpayPayment_<?php echo $this->uniqId; ?>(elem) {
        var toAm = $('input[name="totalAmount"]', windowId_<?php echo $this->uniqId; ?>).val();
        $.ajax({
          type: "post",
          url: "appmarket/qpayGenerateQrCode",
          data: { amount: toAm },
          dataType: "json",
          beforeSend: function () {
            Core.blockUI({
              message: "Qpay QR loading...",
              boxed: true
            });
          },
          success: function (dataQrCode) {
            Core.unblockUI();

            if (dataQrCode.status == "success") {
              var candyQrUuid = dataQrCode.traceNo;
              qpayQRCodeShow_<?php echo $this->uniqId; ?>(dataQrCode);

              posQpayQrCheckInterval_<?php echo $this->uniqId; ?> = setInterval(function () {
                qpayCheckQrCode_<?php echo $this->uniqId; ?>(candyQrUuid, toAm);
              }, 3000);
            } else {
              new PNotify({
                title: dataQrCode.status,
                text: dataQrCode.message,
                type: dataQrCode.status,
                sticker: false,
                addclass: "pnotify-center"
              });
            }
          },
        });        
    }
    
    function save_<?php echo $this->uniqId; ?>() {
        $.ajax({
            type: "post",
            url: "api/callProcess",
            data: {
              processCode: "appMarketLicenseCreate",
              paramData: { 
                  filterUserId: '<?php echo Ue::sessionUserKeyId() ?>'
              }
            },
            beforeSend: function () {
              Core.blockUI({
                message: "Борлуулалт үүсгэж байна...",
                boxed: true
              });
            },            
            dataType: "json",
            success: function (data) {
                PNotify.removeAll();
                if (data.status === "success") {
                    new PNotify({
                        title: 'Success',
                        text: plang.get('msg_save_success'),
                        type: 'success',
                        sticker: false
                    });                                              
                } else {
                    new PNotify({
                      title: "Warning",
                      text: data.text,
                      type: "warning",
                      sticker: false
                    });                    
                }
                Core.unblockUI();
            }
        });            
    }
    
    function qpayQRCodeShow_<?php echo $this->uniqId; ?>(data) {
      var $dialogName = "dialog-pos-qpayqr";
      $('<div id="' + $dialogName + '"></div>').appendTo("body");
      var $dialog = $("#" + $dialogName);

      $dialog.empty().append(data.html);

      $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: data.title,
        width: 420,
        minWidth: 420,
        height: "auto",
        modal: true,
        dialogClass: "pos-payment-dialog",
        closeOnEscape: isCloseOnEscape,
        close: function () {
          clearInterval(posQpayQrCheckInterval_<?php echo $this->uniqId; ?>);
          $dialog.empty().dialog("destroy").remove();
        },
        buttons: [{
            text: data.close_btn,
            class: "btn btn-sm blue-hoki",
            click: function () {
              $dialog.dialog("close");
              clearInterval(posQpayQrCheckInterval_<?php echo $this->uniqId; ?>);
            },
        }],
      });
      $dialog.dialog("open");
    }    
    
    function qpayCheckQrCode_<?php echo $this->uniqId; ?>(uuid, amount) {
      $.ajax({
        type: "post",
        url: "appmarket/qpayCheckQrCode",
        data: { uuid: uuid },
        dataType: "json",
        success: function (dataQrCode) {
          Core.unblockUI();
          if (dataQrCode.status == "success") {
            clearInterval(posQpayQrCheckInterval_<?php echo $this->uniqId; ?>);
            $("#dialog-pos-qpayqr").html('<div class="payment-success-icon"><i class="icon-check"></i></div><h3 style="color: green;padding: 20px 20px 20px 0px;text-align: center;">Төлбөр амжилттай төлөгдлөө.</h3>');
            $('input[name="qpayInvoiceId"]', windowId_<?php echo $this->uniqId; ?>).val(uuid);
            save_<?php echo $this->uniqId; ?>();
          }
        },
      });
    }    
    
    $('input[name="basketQty[]"]', windowId_<?php echo $this->uniqId; ?>).on('change', function(){
        var $self = $(this);
        var qty = Number($self.val());
        $self.closest('tr').find('input[name="lineTotalAmount[]"]').val(qty * Number($self.closest('tr').find('input[name="amount[]"]').val()));
        $self.closest('tr').find('.basket-linetotal-amount').text(pureNumberFormat(qty * Number($self.closest('tr').find('input[name="amount[]"]').val()))+'₮');
        saveBasketRow_<?php echo $this->uniqId; ?>($self);
    });
    
    $('.appmarket-basket-plus-month', windowId_<?php echo $this->uniqId; ?>).on('click', function(){
        var $self = $(this);
        var qty = Number($self.closest('ul').find('input[name="basketQty[]"]').val()) + 1;
        $self.closest('ul').find('input[name="basketQty[]"]').val(qty);
        $self.closest('tr').find('input[name="lineTotalAmount[]"]').val(qty * Number($self.closest('tr').find('input[name="amount[]"]').val()));
        $self.closest('tr').find('.basket-linetotal-amount').text(pureNumberFormat(qty * Number($self.closest('tr').find('input[name="amount[]"]').val()))+'₮');
        saveBasketRow_<?php echo $this->uniqId; ?>($self);
    });
    
    $('.appmarket-basket-minus-month', windowId_<?php echo $this->uniqId; ?>).on('click', function(){
        var $self = $(this);
        var qty = Number($self.closest('ul').find('input[name="basketQty[]"]').val()) - 1;
        if (qty > 0) {
            $self.closest('ul').find('input[name="basketQty[]"]').val(qty);
            $self.closest('tr').find('input[name="lineTotalAmount[]"]').val(qty * Number($self.closest('tr').find('input[name="amount[]"]').val()));
            $self.closest('tr').find('.basket-linetotal-amount').text(pureNumberFormat(qty * Number($self.closest('tr').find('input[name="amount[]"]').val()))+'₮');            
            saveBasketRow_<?php echo $this->uniqId; ?>($self);
        }
    });
    
    $('input[name="basketUserQty[]"]', windowId_<?php echo $this->uniqId; ?>).on('change', function(){
        var $self = $(this);
        saveBasketRow_<?php echo $this->uniqId; ?>($self);
    });    
    
    $('.appmarket-basket-plus', windowId_<?php echo $this->uniqId; ?>).on('click', function(){
        var $self = $(this);
        var qty = Number($(this).closest('ul').find('input[name="basketUserQty[]"]').val()) + 1;
        $(this).closest('ul').find('input[name="basketUserQty[]"]').val(qty);
        saveBasketRow_<?php echo $this->uniqId; ?>($self);
    });
    
    $('.appmarket-basket-minus', windowId_<?php echo $this->uniqId; ?>).on('click', function(){
        var $self = $(this);
        var qty = Number($(this).closest('ul').find('input[name="basketUserQty[]"]').val()) - 1;
        if (qty > 0) {
            $(this).closest('ul').find('input[name="basketUserQty[]"]').val(qty);
            saveBasketRow_<?php echo $this->uniqId; ?>($self);
        }
    });
</script>
    