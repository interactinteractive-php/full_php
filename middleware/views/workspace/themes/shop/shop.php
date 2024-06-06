<div class="vr-workspace-shop">
    <div class="main container">
        <div class="inner-container">
            <div class="preface"></div>
            <div class="col-main">
                <div class="grid12-4">
                    <div class="product-image-detial">
                        <div class='dopelessrotate zoom'>
                            <img src='middleware/assets/theme/shop/img/4_3.jpg'>
                        </div>
                        <div class="owl-btn img-prev"><i class="fa fa-angle-left"></i></div>
                        <div class="owl-btn img-next"><i class="fa fa-angle-right"></i></div>
                        <div class="owl-image-detail owl-carousel owl-theme">
                            <div class="item fadein">
                                <div data-img="middleware/assets/theme/shop/img/4_3.jpg" class="image">
                                    <img src="middleware/assets/theme/shop/img/4_3_1.jpg">
                                </div>
                            </div>
                            <div class="item fadein">
                                <div data-img="middleware/assets/theme/shop/img/271669-0054_1.jpg" class="image">
                                    <img src="middleware/assets/theme/shop/img/271669-0054_1.jpg">
                                </div>
                            </div>
                            <div class="item fadein">
                                <div data-img="middleware/assets/theme/shop/img/677080-0132_1_2.jpg" class="image">
                                    <img src="middleware/assets/theme/shop/img/677080-0132_1_2.jpg">
                                </div>
                            </div>
                            <div class="item fadein">
                                <div data-img="middleware/assets/theme/shop/img/677188-0067_1.jpg" class="image">
                                    <img src="middleware/assets/theme/shop/img/677188-0067_1.jpg">
                                </div>
                            </div>
                            <div class="item fadein">
                                <div data-img="middleware/assets/theme/shop/img/734194-0030_1.jpg" class="image">
                                    <img src="middleware/assets/theme/shop/img/734194-0030_1.jpg">
                                </div>
                            </div>
                            <div class="item fadein">
                                <div data-img="middleware/assets/theme/shop/img/996093-0100_1.jpg" class="image">
                                    <img src="middleware/assets/theme/shop/img/996093-0100_1.jpg">
                                </div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(function () {
                                $('.dopelessrotate').zoom();
                                $(".product-image-detial .owl-image-detail .item").on("click", function(){
                                    var _this = $(this);
                                    var selectImg = _this.find(".image").attr("data-img");
                                    $(".product-image-detial .owl-image-detail .item").removeClass("active");
                                    _this.addClass("active");
                                    console.log(selectImg);
                                    $(".dopelessrotate img").attr("src", selectImg);
                                    $('.dopelessrotate').zoom();
                                });
                            });
                        </script>
                    </div>
                </div>

                <div class="grid12-5">
                    <div class="product-detail">
                        <h1>Барааны нэр төрөл</h1>

                        <div class="ratings">
                            <div class="rating-box">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                            <p class="rating-links">
                                <a id="goto-reviews" href="#customer-reviews">2 Сэтгэгдэл</a>
                                <span class="separator">|</span>
                                <a id="goto-reviews-form" href="#review-form">Сэтгэгдэл нэмэх</a>
                            </p>
                        </div>

                        <div class="short-description">Гэрэл зурагчин Б.Баяр 2006 оноос хойш уран бүтээлээ туурвиж байгаа юм. Тэрбээр эдийн засагч мэргэжилтэй хэдий ч өөрийн хүсэл, сонирхлоор гэрэл зураг авдаг болсон байна.</div>

                        <div class="accessories mb10">
                            <span class="left">
                                <span class="badge" title="Only 17 left">Only <strong>17</strong> left</span>
                            </span>
                        </div>
                        <div class="clearfix w-100"></div>
                        <div class="accessories mb10">
                            <div class="left price">$80.00</div>
                            <p class="right">Availability: <span>In stock</span></p>
                        </div>
                        <div class="clearfix w-100"></div>

                        <div class="form-group row fom-row">
                            <label>Select Size</label>
                            <select name="options[31]" id="select_31" class="form-control select2"><option value="">-- Please Select --</option><option value="80" price="0">M </option><option value="81" price="3">L +$3.00</option><option value="82" price="4.99">XL +$4.99</option></select>
                        </div>
                        <div class="form-group row fom-row">
                            <label>Select Color</label>
                            <select name="options[31]" id="select_31" class="form-control select2"><option value="">-- Please Select --</option><option value="80" price="0">M </option><option value="81" price="3">L +$3.00</option><option value="82" price="4.99">XL +$4.99</option></select>
                        </div>

                        <button type="button" class="btn red"><i class="fa fa-shopping-cart"></i> Сагсанд нэмэх</button>

                    </div>

                    <div class="action-box clearer">
                        <ul class="add-to-links">
                            <li>
                                <div class="feature indent">
                                    <span class="ic fadein"><i class="fa fa-star"></i></span>
                                    <span class="badge links">Add to Compare</span>
                                </div>
                            </li>
                            <li>
                                <div class="feature indent">
                                    <span class="ic fadein"><i class="fa fa-star"></i></span>
                                    <span class="badge links">Add to Compare</span>
                                </div>
                            </li>
                        </ul>				
                    </div>

                </div>
            </div>


            <div class="grid12-3 custom-sidebar-right">
                <div class="inner">

                    <div class="feature-wrapper bottom-border">			
                        <div class="box-brand">
                            <a class="fade-on-hover" href="#" title="Click to see more products from BlueLogo"><img src="middleware/assets/theme/shop/img/bluelogo.png" alt="BlueLogo"></a>
                        </div>

                    </div>

                    <div>
                        <div class="feature indent">
                            <span class="ic fadein"><i class="fa fa-car"></i></span>
                            <p class="m-0 ">We will send this product in 2 days. <a href="#">Read more...</a></p>
                        </div>
                        <div class="feature indent">
                            <span class="ic fadein"><i class="fa fa-phone"></i></span>
                            <p class="m-0 ">Call us now for more info about our products.</p>
                        </div>
                        <div class="feature indent">
                            <span class="ic fadein"><i class="fa fa-bank"></i></span>
                            <p class="m-0 ">Return purchased items and get all your money back.</p>
                        </div>
                        <div class="feature indent">
                            <span class="ic fadein"><i class="fa fa-star"></i></span>
                            <p class="m-0 ">Buy this product and earn 10 special loyalty points!</p>
                        </div>

                        <br>
                    </div>
                    <div class="box-collateral box-related">
                        <div class="customNavigation owl-vertical-btn">
                            <div class="title">Холбоотой бараа</div>
                            <div class="owl-btn vr-btn-prev fadein"><i class="fa fa-angle-left"></i></div>
                            <div class="owl-btn vr-btn-next fadein"><i class="fa fa-angle-right"></i></div>
                        </div>
                        <div class="owl-vertical owl-carousel owl-theme">
                            <div class="item">
                                <ul class="block-related-thumbnails">
                                    <li class="product">
                                        <a class="product-image" href="#">
                                            <img src="middleware/assets/theme/shop/img/428374-0023_1_1.jpg">
                                        </a>
                                        <div class="product-details">
                                            <h3 class="product-name"><a href="#">Every Day Heel</a></h3>
                                            <div class="price-box">
                                                <span class="regular-price" id="product-price-62-related">
                                                    <span class="price">$95.30</span>                                    
                                                </span>
                                            </div>
                                            <div class="related-add-to-wishlist">Жагсаалтад нэмэх</div>
                                        </div> <!-- end: product-details -->
                                        <div class="clearfix w-100"></div>
                                    </li>
                                    <li class="product">
                                        <a class="product-image" href="#">
                                            <img src="middleware/assets/theme/shop/img/928172-0484_1.jpg">
                                        </a>
                                        <div class="product-details">
                                            <h3 class="product-name"><a href="#">Crimson Wave Heel</a></h3>
                                            <div class="price-box">
                                                <span class="regular-price" id="product-price-67-related">
                                                    <span class="price">$85.50</span>                                    
                                                </span>
                                            </div>
                                            <div class="related-add-to-wishlist">Жагсаалтад нэмэх</div>
                                        </div> <!-- end: product-details -->
                                        <div class="clearfix w-100"></div>
                                    </li>
                                    <li class="product">
                                        <a class="product-image" href="#">
                                            <img src="middleware/assets/theme/shop/img/428446-0067_1_1_1_1_1.jpg" alt="Metropolis High Heels">
                                        </a>
                                        <div class="product-details">
                                            <h3 class="product-name"><a href="#">Metropolis High Heels</a></h3>
                                            <div class="price-box">
                                                <span class="regular-price" id="product-price-65-related">
                                                    <span class="price">$140.00</span>                                    
                                                </span>
                                            </div>
                                            <div class="related-add-to-wishlist">Жагсаалтад нэмэх</div>
                                        </div> <!-- end: product-details -->
                                        <div class="clearfix w-100"></div>
                                    </li>
                                    <li class="product">
                                        <a class="product-image" href="#">
                                            <img src="middleware/assets/theme/shop/img/428357-0001_1_1_1.jpg">
                                        </a>
                                        <div class="product-details">
                                            <h3 class="product-name"><a href="#">Prestige Lite Heels</a></h3>
                                            <div class="price-box">
                                                <span class="regular-price" id="product-price-63-related">
                                                    <span class="price">$215.00</span>                                    
                                                </span>
                                            </div>
                                            <div class="related-add-to-wishlist">Жагсаалтад нэмэх</div>
                                        </div> <!-- end: product-details -->
                                        <div class="clearfix w-100"></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="item">
                                <ul class="block-related-thumbnails">
                                    <li class="product">
                                        <a class="product-image" href="#">
                                            <img src="middleware/assets/theme/shop/img/428374-0023_1_1.jpg">
                                        </a>
                                        <div class="product-details">
                                            <h3 class="product-name"><a href="#">Every Day Heel</a></h3>
                                            <div class="price-box">
                                                <span class="regular-price" id="product-price-62-related">
                                                    <span class="price">$95.30</span>                                    
                                                </span>
                                            </div>
                                            <div class="related-add-to-wishlist">Жагсаалтад нэмэх</div>
                                        </div> <!-- end: product-details -->
                                        <div class="clearfix w-100"></div>
                                    </li>
                                    <li class="product">
                                        <a class="product-image" href="#">
                                            <img src="middleware/assets/theme/shop/img/928172-0484_1.jpg">
                                        </a>
                                        <div class="product-details">
                                            <h3 class="product-name"><a href="#">Crimson Wave Heel</a></h3>
                                            <div class="price-box">
                                                <span class="regular-price" id="product-price-67-related">
                                                    <span class="price">$85.50</span>                                    
                                                </span>
                                            </div>
                                            <div class="related-add-to-wishlist">Жагсаалтад нэмэх</div>
                                        </div> <!-- end: product-details -->
                                        <div class="clearfix w-100"></div>
                                    </li>
                                    <li class="product">
                                        <a class="product-image" href="#">
                                            <img src="middleware/assets/theme/shop/img/428446-0067_1_1_1_1_1.jpg" alt="Metropolis High Heels">
                                        </a>
                                        <div class="product-details">
                                            <h3 class="product-name"><a href="#">Metropolis High Heels</a></h3>
                                            <div class="price-box">
                                                <span class="regular-price" id="product-price-65-related">
                                                    <span class="price">$140.00</span>                                    
                                                </span>
                                            </div>
                                            <div class="related-add-to-wishlist">Жагсаалтад нэмэх</div>
                                        </div> <!-- end: product-details -->
                                        <div class="clearfix w-100"></div>
                                    </li>
                                    <li class="product">
                                        <a class="product-image" href="#">
                                            <img src="middleware/assets/theme/shop/img/428357-0001_1_1_1.jpg">
                                        </a>
                                        <div class="product-details">
                                            <h3 class="product-name"><a href="#">Prestige Lite Heels</a></h3>
                                            <div class="price-box">
                                                <span class="regular-price" id="product-price-63-related">
                                                    <span class="price">$215.00</span>                                    
                                                </span>
                                            </div>
                                            <div class="related-add-to-wishlist">Жагсаалтад нэмэх</div>
                                        </div> <!-- end: product-details -->
                                        <div class="clearfix w-100"></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="item">
                                <ul class="block-related-thumbnails">
                                    <li class="product">
                                        <a class="product-image" href="#">
                                            <img src="middleware/assets/theme/shop/img/428374-0023_1_1.jpg">
                                        </a>
                                        <div class="product-details">
                                            <h3 class="product-name"><a href="#">Every Day Heel</a></h3>
                                            <div class="price-box">
                                                <span class="regular-price" id="product-price-62-related">
                                                    <span class="price">$95.30</span>                                    
                                                </span>
                                            </div>
                                            <div class="related-add-to-wishlist">Жагсаалтад нэмэх</div>
                                        </div> <!-- end: product-details -->
                                        <div class="clearfix w-100"></div>
                                    </li>
                                    <li class="product">
                                        <a class="product-image" href="#">
                                            <img src="middleware/assets/theme/shop/img/928172-0484_1.jpg">
                                        </a>
                                        <div class="product-details">
                                            <h3 class="product-name"><a href="#">Crimson Wave Heel</a></h3>
                                            <div class="price-box">
                                                <span class="regular-price" id="product-price-67-related">
                                                    <span class="price">$85.50</span>                                    
                                                </span>
                                            </div>
                                            <div class="related-add-to-wishlist">Жагсаалтад нэмэх</div>
                                        </div> <!-- end: product-details -->
                                        <div class="clearfix w-100"></div>
                                    </li>
                                    <li class="product">
                                        <a class="product-image" href="#">
                                            <img src="middleware/assets/theme/shop/img/428446-0067_1_1_1_1_1.jpg" alt="Metropolis High Heels">
                                        </a>
                                        <div class="product-details">
                                            <h3 class="product-name"><a href="#">Metropolis High Heels</a></h3>
                                            <div class="price-box">
                                                <span class="regular-price" id="product-price-65-related">
                                                    <span class="price">$140.00</span>                                    
                                                </span>
                                            </div>
                                            <div class="related-add-to-wishlist">Жагсаалтад нэмэх</div>
                                        </div> <!-- end: product-details -->
                                        <div class="clearfix w-100"></div>
                                    </li>
                                    <li class="product">
                                        <a class="product-image" href="#">
                                            <img src="middleware/assets/theme/shop/img/428357-0001_1_1_1.jpg">
                                        </a>
                                        <div class="product-details">
                                            <h3 class="product-name"><a href="#">Prestige Lite Heels</a></h3>
                                            <div class="price-box">
                                                <span class="regular-price" id="product-price-63-related">
                                                    <span class="price">$215.00</span>                                    
                                                </span>
                                            </div>
                                            <div class="related-add-to-wishlist">Жагсаалтад нэмэх</div>
                                        </div> <!-- end: product-details -->
                                        <div class="clearfix w-100"></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="item">
                                <ul class="block-related-thumbnails">
                                    <li class="product">
                                        <a class="product-image" href="#">
                                            <img src="middleware/assets/theme/shop/img/428374-0023_1_1.jpg">
                                        </a>
                                        <div class="product-details">
                                            <h3 class="product-name"><a href="#">Every Day Heel</a></h3>
                                            <div class="price-box">
                                                <span class="regular-price" id="product-price-62-related">
                                                    <span class="price">$95.30</span>                                    
                                                </span>
                                            </div>
                                            <div class="related-add-to-wishlist">Жагсаалтад нэмэх</div>
                                        </div> <!-- end: product-details -->
                                        <div class="clearfix w-100"></div>
                                    </li>
                                    <li class="product">
                                        <a class="product-image" href="#">
                                            <img src="middleware/assets/theme/shop/img/928172-0484_1.jpg">
                                        </a>
                                        <div class="product-details">
                                            <h3 class="product-name"><a href="#">Crimson Wave Heel</a></h3>
                                            <div class="price-box">
                                                <span class="regular-price" id="product-price-67-related">
                                                    <span class="price">$85.50</span>                                    
                                                </span>
                                            </div>
                                            <div class="related-add-to-wishlist">Жагсаалтад нэмэх</div>
                                        </div> <!-- end: product-details -->
                                        <div class="clearfix w-100"></div>
                                    </li>
                                    <li class="product">
                                        <a class="product-image" href="#">
                                            <img src="middleware/assets/theme/shop/img/428446-0067_1_1_1_1_1.jpg" alt="Metropolis High Heels">
                                        </a>
                                        <div class="product-details">
                                            <h3 class="product-name"><a href="#">Metropolis High Heels</a></h3>
                                            <div class="price-box">
                                                <span class="regular-price" id="product-price-65-related">
                                                    <span class="price">$140.00</span>                                    
                                                </span>
                                            </div>
                                            <div class="related-add-to-wishlist">Жагсаалтад нэмэх</div>
                                        </div> <!-- end: product-details -->
                                        <div class="clearfix w-100"></div>
                                    </li>
                                    <li class="product">
                                        <a class="product-image" href="#">
                                            <img src="middleware/assets/theme/shop/img/428357-0001_1_1_1.jpg">
                                        </a>
                                        <div class="product-details">
                                            <h3 class="product-name"><a href="#">Prestige Lite Heels</a></h3>
                                            <div class="price-box">
                                                <span class="regular-price" id="product-price-63-related">
                                                    <span class="price">$215.00</span>                                    
                                                </span>
                                            </div>
                                            <div class="related-add-to-wishlist">Жагсаалтад нэмэх</div>
                                        </div> <!-- end: product-details -->
                                        <div class="clearfix w-100"></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end: product-secondary-column -->

            <div class="box-additional grid12-9">
                <div class="mb20">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a href="#tab_5_1" data-toggle="tab" class="nav-link active" aria-expanded="true">Эхлэл</a>
                        </li>
                        <li class="nav-item">
                            <a href="#tab_5_2" data-toggle="tab" aria-expanded="false" class="nav-link">Дугаарлалт</a>
                        </li>
                        <li class="nav-item">
                            <a href="#tab_5_3" data-toggle="tab" aria-expanded="false" class="nav-link">Шинэ сонин содон</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_5_1">
                            <p>
                                Нийслэлийн хэмжээнд Цэргийн үүрэгтэн, бэлтгэл офицеруудын бүртгэл 01 сарын 06-нд эхэлсэн бөгөөд 01 сарын 24-ний өдөр хүртэл үргэлжлэх юм.

                                Цэргийн бүртгэлд 18-50 насны эрэгтэйчүүд, 55 хүртэл насны дунд офицер, ахлагч, 60 хүртэл насны дээд, ахлах офицерууд хамрагдана.
                            </p>
                        </div>
                        <div class="tab-pane" id="tab_5_2">
                            <p>
                                Бүртгүүлэхдээ Иргэний үнэмлэх, Бэлтгэл офицерын үнэмлэх, Цэргийн үүрэгтний үнэмлэх /байхгүй бол 2 хувь цээж зураг /, ажлын газрын болон оюутны үнэмлэхтэйгээ харьяа хорооны Засаг даргын ажлын байранд ажлын өдрүүдэд 08:30-19:00 цаг хүртэл, амралтын өдрүүдэд 10:00-18:00 цагийн хооронд очиж бүртгүүлнэ.
                            </p>
                            <p>
                                <a class="btn green" href="ui_tabs_accordions_navs.html#tab_5_2" target="_blank">
                                    Activate this tab via URL </a>
                            </p>
                        </div>
                        <div class="tab-pane" id="tab_5_3">

                            <p>
                                Тогтоосон хугацаанд цэргийн бүртгэлдээ хамрагдаж иргэний цэргийн үүргээ биелүүлнэ үү гэж Нийслэлийн ЗДТГ-ын Хэвлэл мэдээлэл, олон нийттэй харилцах хэлтсээс мэдээллээ.
                            </p>
                            <p>
                                <a class="btn yellow" href="ui_tabs_accordions_navs.html#tab_5_3" target="_blank">
                                    Activate this tab via URL </a>
                            </p>
                        </div>
                    </div>
                </div>		
            </div> <!-- end: box-tabs -->

            <div class="grid12-9">
                <div class="customNavigation">
                    <div class="title">Сүүлд нэмэгдсэн бараа</div>
                    <div class="owl-btn btn-prev"><i class="fa fa-angle-left"></i></div>
                    <div class="owl-btn btn-next"><i class="fa fa-angle-right"></i></div>
                </div>
                <div class="owl-horizontal owl-carousel owl-theme">
                    <div class="item">
                        <a href="#" class="image">
                            <img src="middleware/assets/theme/shop/img/216840-0129_1.jpg">
                        </a>
                        <h3 class="title"><a href="#">Гэрэл зурагчин Б.Баярын 2015 оны ШИЛДЭГ зургууд</a></h3>
                        <div class="accessories-box">
                            <span class="unit-item left">
                                <span class="price">$105.00</span>
                            </span>
                            <span class="unit-item right">
                                <span class="comment"><i class="fa fa-comment"></i> 10</span>

                            </span>
                        </div>
                    </div>
                    <div class="item">
                        <a href="#" class="image">
                            <img src="middleware/assets/theme/shop/img/271669-0054_1.jpg">
                        </a>
                        <h3 class="title"><a href="#">Гэрэл зурагчин Б.Баярын 2015 оны ШИЛДЭГ зургууд</a></h3>
                        <div class="accessories-box">
                            <span class="unit-item left">
                                <span class="price">$105.00</span>
                            </span>
                            <span class="unit-item right">
                                <span class="comment"><i class="fa fa-comment"></i> 10</span>

                            </span>
                        </div>
                    </div>
                    <div class="item">
                        <a href="#" class="image">
                            <img src="middleware/assets/theme/shop/img/677080-0132_1_2.jpg">
                        </a>
                        <h3 class="title"><a href="#">Гэрэл зурагчин Б.Баярын 2015 оны ШИЛДЭГ зургууд</a></h3>
                        <div class="accessories-box">
                            <span class="unit-item left">
                                <span class="price">$105.00</span>
                            </span>
                            <span class="unit-item right">
                                <span class="comment"><i class="fa fa-comment"></i> 10</span>

                            </span>
                        </div>
                    </div>
                    <div class="item">
                        <a href="#" class="image">
                            <img src="middleware/assets/theme/shop/img/677188-0067_1.jpg">
                        </a>
                        <h3 class="title"><a href="#">Гэрэл зурагчин Б.Баярын 2015 оны ШИЛДЭГ зургууд</a></h3>
                        <div class="accessories-box">
                            <span class="unit-item left">
                                <span class="price">$105.00</span>
                            </span>
                            <span class="unit-item right">
                                <span class="comment"><i class="fa fa-comment"></i> 10</span>

                            </span>
                        </div>
                    </div>
                    <div class="item">
                        <a href="#" class="image">
                            <img src="middleware/assets/theme/shop/img/734194-0030_1.jpg">
                        </a>
                        <h3 class="title"><a href="#">Гэрэл зурагчин Б.Баярын 2015 оны ШИЛДЭГ зургууд</a></h3>
                        <div class="accessories-box">
                            <span class="unit-item left">
                                <span class="price">$105.00</span>
                            </span>
                            <span class="unit-item right">
                                <span class="comment"><i class="fa fa-comment"></i> 10</span>

                            </span>
                        </div>
                    </div>
                    <div class="item">
                        <a href="#" class="image">
                            <img src="middleware/assets/theme/shop/img/996093-0100_1.jpg">
                        </a>
                        <h3 class="title"><a href="#">Өндөр настанг дээрэмдэж, гэмтээсэн этгээдүүд баривчлагджээ</a></h3>
                        <div class="accessories-box">
                            <span class="unit-item left">
                                <span class="price">$105.00</span>
                            </span>
                            <span class="unit-item right">
                                <span class="comment"><i class="fa fa-comment"></i> 10</span>

                            </span>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {


        var owlImageDetial = $(".owl-image-detail");
        owlImageDetial.owlCarousel({
            items: 4, //10 items above 1000px browser width
            itemsDesktop: [1000, 4], //5 items between 1000px and 901px
            itemsDesktopSmall: [900, 3], // betweem 900px and 601px
            itemsTablet: [600, 2], //2 items between 600 and 0
            itemsMobile: false // itemsMobile disabled - inherit from itemsTablet option
        });
        $(".img-next").click(function () {
            owlImageDetial.trigger('owl.next');
        });
        $(".img-prev").click(function () {
            owlImageDetial.trigger('owl.prev');
        });


        var owlHorizontal = $(".owl-horizontal");
        owlHorizontal.owlCarousel({
            items: 5, //10 items above 1000px browser width
            itemsDesktop: [1000, 5], //5 items between 1000px and 901px
            itemsDesktopSmall: [900, 3], // betweem 900px and 601px
            itemsTablet: [600, 2], //2 items between 600 and 0
            itemsMobile: false // itemsMobile disabled - inherit from itemsTablet option
        });
        $(".btn-next").click(function () {
            owlHorizontal.trigger('owl.next');
        });
        $(".btn-prev").click(function () {
            owlHorizontal.trigger('owl.prev');
        });
        var owlVertical = $(".owl-vertical");
        owlVertical.owlCarousel({
            items: 1, //10 items above 1000px browser width
            itemsDesktop: [1000, 1], //5 items between 1000px and 901px
            itemsDesktopSmall: [900, 1], // betweem 900px and 601px
            itemsTablet: [600, 1], //2 items between 600 and 0
            itemsMobile: false // itemsMobile disabled - inherit from itemsTablet option
        });
        // Custom Navigation Events
        $(".vr-btn-next").click(function () {
            owlVertical.trigger('owl.next');
        });
        $(".vr-btn-prev").click(function () {
            owlVertical.trigger('owl.prev');
        });
    });
</script>