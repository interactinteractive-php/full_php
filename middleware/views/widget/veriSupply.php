<div class="row veri-supply-<?php echo $this->uniqId; ?>">
    <div class="collateralview crdstyle mt-10 mb-70">
        <div class="container">

            <div class="col-md-9 pr-20">
                <div class="tabstyle3">
                    <ul class="fltr-control">
                        <li class="active" href="#protab1" data-toggle="tab" aria-expanded="false">
                            Нүүр
                        </li>
                        <li class="" href="#protab2" data-toggle="tab" aria-expanded="false">
                            Миний худалдан авалт
                        </li>
                        <li class="" href="#protab3" data-toggle="tab" aria-expanded="false">
                            Миний гишүүнчлэл
                        </li>
                        <li class="" href="#protab4" data-toggle="tab" aria-expanded="false">
                            Таны сонголтонд
                        </li>
                    </ul>
                </div>

                <div class="tab-content bgNone ph-0 pt-0">     
                    <div class="tab-pane fade active in" id="protab1">

                        <div class="about">

                            <div class="cartStatic">

                                <div class="item ">
                                    <div class="icon">
                                        <img src="http://supply.veritech.mn/assets/emarket/img/icon/data.png">
                                    </div>
                                    <div class="name">Нийт худалдан авалт</div>
                                    <div class="value mt-5"><?php echo Number::formatMoney(5000000); ?></div>
                                </div>

                                <div class="item pink">
                                    <div class="icon">
                                        <img src="http://supply.veritech.mn/assets/emarket/img/icon/upcolumn.png">
                                    </div>
                                    <div class="name">Нийт хэмнэлт</div>
                                    <div class="value mt-5"><?php echo Number::formatMoney(250000); ?></div>
                                </div>

                                <div class="item purple">
                                    <div class="icon">
                                        <img src="http://supply.veritech.mn/assets/emarket/img/icon/calendar.png">
                                    </div>
                                    <div class="name">Нийт бонус</div>
                                    <div class="value mt-5"><?php echo Number::formatMoney(120000); ?><span></div>
                                </div>

                                <div class="clearfix w-100"></div>
                            </div>
                        </div>

                        <div class="homeChart">
                            <div class="item bgWhite">
                                <div class="lxtitle ta-c mt-5">1500</div>
                                <div class="smtitle ta-c mv-15">Нийт бараа</div>
                                <div class="ta-c">
                                    <div class="rateit humanIcon mt-5 mb-15"  
                                         data-rateit-readonly="true"
                                         data-rateit-value="3"
                                         data-rateit-starwidth="14"
                                         data-rateit-starheight="22"
                                         data-rateit-max="5">
                                    </div>
                                </div>

                                <div class="xsprocessbar mb-10">
                                    <div class="descrip">
                                        Хүнс
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar dcblue" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="xsprocessbar">
                                    <div class="descrip">
                                        Бусад
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar dcred" style="width: 25%" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="item bgWhite">
                                <div class="lxtitle ta-c mt-5">204</div>
                                <div class="smtitle ta-c mv-15">Энэ сард</div>
                                <div class="ta-c">
                                    <div class="rateit boxIcon mt-5 mb-15"  
                                         data-rateit-readonly="true"
                                         data-rateit-value="4"
                                         data-rateit-starwidth="20"
                                         data-rateit-starheight="22"
                                         data-rateit-max="5">
                                    </div>
                                </div>

                                <div class="xsprocessbar mb-10">
                                    <div class="descrip">Хүнс (1153)</div>
                                    <div class="progress">
                                        <div class="progress-bar dcgreen" style="width: 80%" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="xsprocessbar">
                                    <div class="descrip">Бусад (347)</div>
                                    <div class="progress">
                                        <div class="progress-bar dcorange" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="item bgWhite">
                                <div class="lxtitle ta-c mt-5">150</div>
                                <div class="smtitle ta-c mv-15">Хөнгөлөлттөй</div>
                                <div class="ta-c">
                                    <div class="rateit locationIcon mt-5 mb-15"  
                                         data-rateit-readonly="true"
                                         data-rateit-value="2"
                                         data-rateit-starwidth="18"
                                         data-rateit-starheight="22"
                                         data-rateit-max="5">
                                    </div>
                                </div>

                                <div class="xsprocessbar mb-10">
                                    <div class="descrip">Хүнс  (172)</div>
                                    <div class="progress">
                                        <div class="progress-bar dcpurple" style="width: 40%" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="xsprocessbar">
                                    <div class="descrip">Бусад (32)</div>
                                    <div class="progress">
                                        <div class="progress-bar dcpink" style="width: 60%" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="item itemStyle1 bgWhite">
                                <div class="mdtitle mb-5">
                                    <!-- Дундаж худалдан авалт -->
                                    Нийт худалдан авалт
                                </div>
                                <div class="smtitle normalcase mb-20">
                                    Сарын бүрийн дундаж дүн
                                </div>
                                <div class="totalResult">
                                    <?php echo Number::formatMoney(1500000) . '₮'; ?>
                                    <span class="green">+1.4%</span>
                                    <!-- <span class="red">-1.4% багассан</span> -->
                                </div>

                                <div class="hmCartBg" id="homeChart1"></div>

                                <div class="smtitle normalcase ta-c">
                                    Сүүлийн сард: <b><?php echo Number::formatMoney(150000) . '₮'; ?></b>
                                </div>
                            </div>

                            <div class="clearfix w-100"></div>

                            <div class="row mh-n-5 mt-20">
                                <div class="col-xs-6 ph-5">
                                    <div class="item itemStyle1 block height200 bgWhite">
                                        <div class="rt-desc" style="">
                                            <div class="mdtitle mb-10">Худалдан авалт үзүүлэлт</div>
                                            <div class="smtitle normalcase">Тухайн сард худалдан нийт бараа</div>
                                        </div>
                                        <div class="mh-n-15">
                                            <div class="hmCartBg2" id="homeChart2"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-3 ph-5">
                                    <div class="item itemStyle1 block height200 bgWhite">
                                        <div class="lgtitle mt-10 ta-c mb-15">
                                            <?php echo Number::formatMoney(15860); ?>
                                        </div>
                                        <div class="smtitle black ta-c mb-20">
                                            Сард цуглуулсан бонусын дүн
                                        </div>
                                        <div class="smtitle normalcase ta-c">Тухайн сард цуглуулсан</div>
                                        <div class="mh-n-15">
                                            <div class="hmCartBg3" id="homeChart3"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-3 ph-5">
                                    <div class="item itemStyle1 block height200 bgWhite">
                                        <div class="smtitle black ta-c mb-20">
                                            Карт бүрийн бонусын дүн
                                        </div>
                                        <div class="mh-n-15">
                                            <div class="hmCartBg4" id="homeChart5"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="about mt-20">
                            <div class="title2 mb-25">Худалдан авалтын түүх</div>
                            <div class=""><!-- tabstyle3 -->
                                <ul class="fltr-control">
                                    <li class="active" href="#bhistory1" data-toggle="tab" aria-expanded="false">Сүүлийн</li>
                                    <li class="" href="#bhistory2" data-toggle="tab" aria-expanded="false">Энэ 7 хоногт</li>
                                    <li class="" href="#bhistory3" data-toggle="tab" aria-expanded="false">Энэ сар</li>
                                </ul>
                            </div>
                            <div class="tab-content bgNone ph-0 pt-25">     
                                <div class="tab-pane fade active in" id="bhistory1">
                                    <!-- ------------------------------------ -->
                                    <?php
                                    $classView = 'list_view';
//                                    if ($this->curView === 'list') {
//                                        $classView = 'list_view';
//                                    }
                                    ?>
                                    <div class="col-md-11 pr-60">
                                        <div class="row product-list product-list-view">
                                            <?php
                                            foreach ($this->itemList as $key => $item) {
                                                if (is_numeric($key)) {
                                                    ?>
                                                    <div class="col-md-4 product_list_wrap ph-8 <?php echo $classView; ?>"> <!-- col-md-3 col-sm-6 col-xs-12 ph-8 mb-16 -->
                                                        <div class="single-product shop">

                                                            <?php if ($item['endqty'] == '0'): ?>
                                                                <span class="sold"><span>Зарагдаж дууссан</span></span>
                                                            <?php endif; ?>

                                                            <div class="img-section list-col">
                                                                <a href="category/item/<?php echo $item['id']; ?>">
                                                                    <?php if (Input::getCheck('menu') && Input::get('menu') == '1515665385357'): ?>

                                                                        <img src="assets/emarket/img/document.png" onerror="onItemImgError(this);" class="img-fluid img-product" alt="<?php echo $item['name']; ?>">

                                                                    <?php else: ?>

                                                                        <img src="<?php echo IMG_BASE_URL . $item['photo']; ?>" onerror="onItemImgError(this);" class="img-fluid img-product" alt="<?php echo $item['name']; ?>">

                                                                    <?php endif; ?>
                                                                </a>
                                                            </div>

                                                            <div class="single-more">
                                                                <div class="mb10">
                                                                    <div class="col-md-12 col-sm-12 col-xs-12 ph-0 tc more-actions">
                                                                        <?php if ($item['endqty'] != '0'): ?>
                                                                            <div class="action"><span class="add-to-cart-btn" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Сагсанд хийх"><i class="fa fa-opencart"></i> </span></div>
                                                                        <?php endif; ?>
                                                                        <div class="action"><a href="category/item/<?php echo $item['id']; ?>"><span class="view-item-btn" title="Дэлгэрэнгүй"><i class="fa fa-eye"></i></span></a></div>
                                                                        <div class="action"><span class="add-to-wish-btn" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Хүсэлтийн жагсаалт"><i class="fa fa-heart"></i> </span></div>
                                                                    </div>
                                                                    <div class="clearfix w-100"></div>
                                                                </div>
                                                                <div class="prod-price">
                                                                    <div class="col-md-6 col-sm-6 col-xs-12 ph-0">
                                                                        <div class="p-first"><?php echo Number::formatMoney($item['price'], true); ?>₮</div>
                                                                    </div>
                                                                    <div class="col-md-6 col-sm-6 col-xs-12 ph-0">
                                                                        <div id="BVInlineRatings">
                                                                            <div class="starRate">
                                                                                <div class="starShow" style="width:10%"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="clearfix w-100"></div>
                                                                </div>

                                                                <a href="category/item/<?php echo $item['id']; ?>">
                                                                    <h4 class="det-one-prod" title="<?php echo $item['name']; ?>"><?php echo $item['name']; ?></h4>
                                                                </a>
                                                            </div>


                                                            <div class="list-col title_wrap" style="display: none;">
                                                                <a href="category/item/<?php echo $item['id']; ?>" class="title"><?php echo $item['name']; ?></a>
                                                                <div class="mt-7">
                                                                    <div id="BVInlineRatings" class="ta-l">
                                                                        <div class="starRate ml-0">
                                                                            <div class="starShow" style="width:10%"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="list-col infos" style="display: none;">
                                                                <span class="price"><?php echo Number::formatMoney($item['price'], true); ?>₮</span>
                                                            </div>
                                                            <div class="list-col infos" style="display: none;">
                                                                <select name="colorpicker-picker-longlist">
                                                                    <option value="#7bd148">Green</option>
                                                                    <option value="#5484ed">Bold blue</option>
                                                                    <option value="#a4bdfc">Blue</option>
                                                                </select>
                                                            </div>
                                                            <div class="list-col infos" style="display: none;">
                                                                <span class="vl fl cop itemqty_select" style="width: 95px;">
                                                                    <input type="text" class="form-control form-control-sm numberInit quantity-input product-quantity-list" data-bts-max="<?php echo $item['endqty']; ?>">
                                                                </span>
                                                            </div>
                                                            <div class="list-col infos" style="display: none;">
                                                                <?php ///if ( $item['endqty'] != '0'): ?>
                                                                <button class="button-style-sp1 add-to-cart-btn" type="button" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Сагсанд хийх" <?php if ($item['endqty'] == '0') echo "disabled"; ?>>
                                                                    <i class="fa fa-opencart"></i>
                                                                </button>
                                                                <?php //endif; ?>
                                                            </div>

                                                            <div class="clearfix w-100"></div>
                                                        </div>
                                                    </div> <!-- -->
                                                    <?php
                                                }
                                            }
                                            ?>    
                                        </div>
                                    </div>
                                    <div class="clearfix w-100"></div>
                                    <!-- ------------------------------------ -->
                                </div>
                                <div class="tab-pane fade" id="bhistory2">
                                    <div class="col-md-11  pr-60">
                                        <div class="row product-list product-list-view">
                                            <?php
                                            foreach ($this->itemList2 as $key => $item) {
                                                if (is_numeric($key)) {
                                                    ?>
                                                    <div class="col-md-4 product_list_wrap ph-8 <?php echo $classView; ?>"> <!-- col-md-3 col-sm-6 col-xs-12 ph-8 mb-16 -->
                                                        <div class="single-product shop">

                                                            <?php if ($item['endqty'] == '0'): ?>
                                                                <span class="sold"><span>Зарагдаж дууссан</span></span>
                                                            <?php endif; ?>

                                                            <div class="img-section list-col">
                                                                <a href="category/item/<?php echo $item['id']; ?>">
                                                                    <?php if (Input::getCheck('menu') && Input::get('menu') == '1515665385357'): ?>

                                                                        <img src="assets/emarket/img/document.png" onerror="onItemImgError(this);" class="img-fluid img-product" alt="<?php echo $item['name']; ?>">

                                                                    <?php else: ?>

                                                                        <img src="<?php echo IMG_BASE_URL . $item['photo']; ?>" onerror="onItemImgError(this);" class="img-fluid img-product" alt="<?php echo $item['name']; ?>">

                                                                    <?php endif; ?>
                                                                </a>
                                                            </div>

                                                            <div class="single-more">
                                                                <div class="mb10">
                                                                    <div class="col-md-12 col-sm-12 col-xs-12 ph-0 tc more-actions">
                                                                        <?php if ($item['endqty'] != '0'): ?>
                                                                            <div class="action"><span class="add-to-cart-btn" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Сагсанд хийх"><i class="fa fa-opencart"></i> </span></div>
                                                                        <?php endif; ?>
                                                                        <div class="action"><a href="category/item/<?php echo $item['id']; ?>"><span class="view-item-btn" title="Дэлгэрэнгүй"><i class="fa fa-eye"></i></span></a></div>
                                                                        <div class="action"><span class="add-to-wish-btn" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Хүсэлтийн жагсаалт"><i class="fa fa-heart"></i> </span></div>
                                                                    </div>
                                                                    <div class="clearfix w-100"></div>
                                                                </div>
                                                                <div class="prod-price">
                                                                    <div class="col-md-6 col-sm-6 col-xs-12 ph-0">
                                                                        <div class="p-first"><?php echo Number::formatMoney($item['price'], true); ?>₮</div>
                                                                    </div>
                                                                    <div class="col-md-6 col-sm-6 col-xs-12 ph-0">
                                                                        <div id="BVInlineRatings">
                                                                            <div class="starRate">
                                                                                <div class="starShow" style="width:10%"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="clearfix w-100"></div>
                                                                </div>

                                                                <a href="category/item/<?php echo $item['id']; ?>">
                                                                    <h4 class="det-one-prod" title="<?php echo $item['name']; ?>"><?php echo $item['name']; ?></h4>
                                                                </a>
                                                            </div>


                                                            <div class="list-col title_wrap" style="display: none;">
                                                                <a href="category/item/<?php echo $item['id']; ?>" class="title"><?php echo $item['name']; ?></a>
                                                                <div class="mt-7">
                                                                    <div id="BVInlineRatings" class="ta-l">
                                                                        <div class="starRate ml-0">
                                                                            <div class="starShow" style="width:10%"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="list-col infos" style="display: none;">
                                                                <span class="price"><?php echo Number::formatMoney($item['price'], true); ?>₮</span>
                                                            </div>
                                                            <div class="list-col infos" style="display: none;">
                                                                <select name="colorpicker-picker-longlist">
                                                                    <option value="#7bd148">Green</option>
                                                                    <option value="#5484ed">Bold blue</option>
                                                                    <option value="#a4bdfc">Blue</option>
                                                                </select>
                                                            </div>
                                                            <div class="list-col infos" style="display: none;">
                                                                <span class="vl fl cop itemqty_select" style="width: 95px;">
                                                                    <input type="text" class="form-control form-control-sm numberInit quantity-input product-quantity-list" data-bts-max="<?php echo $item['endqty']; ?>">
                                                                </span>
                                                            </div>
                                                            <div class="list-col infos" style="display: none;">
                                                                <?php ///if ( $item['endqty'] != '0'): ?>
                                                                <button class="button-style-sp1 add-to-cart-btn" type="button" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Сагсанд хийх" <?php if ($item['endqty'] == '0') echo "disabled"; ?>>
                                                                    <i class="fa fa-opencart"></i>
                                                                </button>
                                                                <?php //endif; ?>
                                                            </div>

                                                            <div class="clearfix w-100"></div>
                                                        </div>
                                                    </div> <!-- -->
                                                    <?php
                                                }
                                            }
                                            ?>    
                                        </div>
                                    </div>
                                    <div class="clearfix w-100"></div>
                                </div>
                                <div class="tab-pane fade" id="bhistory3">
                                    <div class="col-md-11  pr-60">
                                        <div class="row product-list product-list-view">
                                            <?php
                                            foreach ($this->itemList3 as $key => $item) {
                                                if (is_numeric($key)) {
                                                    ?>
                                                    <div class="col-md-4 product_list_wrap ph-8 <?php echo $classView; ?>"> <!-- col-md-3 col-sm-6 col-xs-12 ph-8 mb-16 -->
                                                        <div class="single-product shop">

                                                            <?php if ($item['endqty'] == '0'): ?>
                                                                <span class="sold"><span>Зарагдаж дууссан</span></span>
                                                            <?php endif; ?>

                                                            <div class="img-section list-col">
                                                                <a href="category/item/<?php echo $item['id']; ?>">
                                                                    <?php if (Input::getCheck('menu') && Input::get('menu') == '1515665385357'): ?>

                                                                        <img src="assets/emarket/img/document.png" onerror="onItemImgError(this);" class="img-fluid img-product" alt="<?php echo $item['name']; ?>">

                                                                    <?php else: ?>

                                                                        <img src="<?php echo IMG_BASE_URL . $item['photo']; ?>" onerror="onItemImgError(this);" class="img-fluid img-product" alt="<?php echo $item['name']; ?>">

                                                                    <?php endif; ?>
                                                                </a>
                                                            </div>

                                                            <div class="single-more">
                                                                <div class="mb10">
                                                                    <div class="col-md-12 col-sm-12 col-xs-12 ph-0 tc more-actions">
                                                                        <?php if ($item['endqty'] != '0'): ?>
                                                                            <div class="action"><span class="add-to-cart-btn" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Сагсанд хийх"><i class="fa fa-opencart"></i> </span></div>
                                                                        <?php endif; ?>
                                                                        <div class="action"><a href="category/item/<?php echo $item['id']; ?>"><span class="view-item-btn" title="Дэлгэрэнгүй"><i class="fa fa-eye"></i></span></a></div>
                                                                        <div class="action"><span class="add-to-wish-btn" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Хүсэлтийн жагсаалт"><i class="fa fa-heart"></i> </span></div>
                                                                    </div>
                                                                    <div class="clearfix w-100"></div>
                                                                </div>
                                                                <div class="prod-price">
                                                                    <div class="col-md-6 col-sm-6 col-xs-12 ph-0">
                                                                        <div class="p-first"><?php echo Number::formatMoney($item['price'], true); ?>₮</div>
                                                                    </div>
                                                                    <div class="col-md-6 col-sm-6 col-xs-12 ph-0">
                                                                        <div id="BVInlineRatings">
                                                                            <div class="starRate">
                                                                                <div class="starShow" style="width:10%"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="clearfix w-100"></div>
                                                                </div>

                                                                <a href="category/item/<?php echo $item['id']; ?>">
                                                                    <h4 class="det-one-prod" title="<?php echo $item['name']; ?>"><?php echo $item['name']; ?></h4>
                                                                </a>
                                                            </div>


                                                            <div class="list-col title_wrap" style="display: none;">
                                                                <a href="category/item/<?php echo $item['id']; ?>" class="title"><?php echo $item['name']; ?></a>
                                                                <div class="mt-7">
                                                                    <div id="BVInlineRatings" class="ta-l">
                                                                        <div class="starRate ml-0">
                                                                            <div class="starShow" style="width:10%"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="list-col infos" style="display: none;">
                                                                <span class="price"><?php echo Number::formatMoney($item['price'], true); ?>₮</span>
                                                            </div>
                                                            <div class="list-col infos" style="display: none;">
                                                                <select name="colorpicker-picker-longlist">
                                                                    <option value="#7bd148">Green</option>
                                                                    <option value="#5484ed">Bold blue</option>
                                                                    <option value="#a4bdfc">Blue</option>
                                                                </select>
                                                            </div>
                                                            <div class="list-col infos" style="display: none;">
                                                                <span class="vl fl cop itemqty_select" style="width: 95px;">
                                                                    <input type="text" class="form-control form-control-sm numberInit quantity-input product-quantity-list" data-bts-max="<?php echo $item['endqty']; ?>">
                                                                </span>
                                                            </div>
                                                            <div class="list-col infos" style="display: none;">
                                                                <?php ///if ( $item['endqty'] != '0'): ?>
                                                                <button class="button-style-sp1 add-to-cart-btn" type="button" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Сагсанд хийх" <?php if ($item['endqty'] == '0') echo "disabled"; ?>>
                                                                    <i class="fa fa-opencart"></i>
                                                                </button>
                                                                <?php //endif; ?>
                                                            </div>

                                                            <div class="clearfix w-100"></div>
                                                        </div>
                                                    </div> <!-- -->
                                                    <?php
                                                }
                                            }
                                            ?>    
                                        </div>
                                    </div>
                                    <div class="clearfix w-100"></div>
                                </div>
                            </div>

                        </div>

                        <div class="about">
                            <div class="title2 mb-25">Хамгийн эрэлттэй гишүүнчлэл</div>
                            <div class="col-md-11 pr-60">
                                <div class="mycardlist">
                                    <div class="row">
                                        <?php
                                        foreach ($this->carts as $cart):
                                            $card_img = 'assets/emarket/img/banner/cart-def.jpg';
                                            if ($cart['physicalpath'])
                                                $card_img = IMG_BASE_URL . $cart['physicalpath'];
                                            ?>
                                            <div class="col-md-4 ph-10 mb-20">
                                                <div class="item">
                                                    <div class="wimg">
                                                        <img src="<?php echo $card_img; ?>" class="img-fluid">
                                                    </div>
                                                    <div class="ph-0">
                                                        <div class="action mv-8">
                                                            <span class="action_but">
                                                                <i class="fa fa-opencart"></i>
                                                            </span>
                                                            <span class="action_but">
                                                                <i class="fa fa-eye"></i>
                                                            </span>
                                                            <span class="action_but">
                                                                <i class="fa fa-heart"></i>
                                                            </span>
                                                        </div>
                                                        <h3 class="title mt-5 mb-15"><?php echo Str::moreMB($cart['name'], 15); ?></h3>
                                                        <div class="ta-c mb-0">

                                                            <div class="mb-10">
                                                                <?php
                                                                loadBarCodeImageData();
                                                                $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                                                                echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($cart['cardnumber'], $generator::TYPE_CODE_128, 2, 50)) . '" border="0" style="width: 100%;height: auto;">';
                                                                ?>
                                                            </div>

                                                            <div class="infos">
                                                                <div class="name">Нийт худалдан авалт</div>
                                                                <div class="value"><?php echo Number::formatMoney($cart['total']); ?>₮</div>
                                                            </div>

                                                            <div class="infos">
                                                                <div class="name">Бонус үлдэгдэл</div>
                                                                <div class="value"><?php echo Number::formatMoney($cart['discount']); ?>₮</div>
                                                            </div>

                                                            <div style="display: inline-block;" class="shop">
                                                                <div id="BVInlineRatings" class="ta-c">
                                                                    <div class="starRate ml-0">
                                                                        <div class="starShow" style="width:37%"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
<?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix w-100"></div>

                        </div>

                        <div class="about">
                            <div class="title2 mb-25">Үнэлгээ</div>
                            <div class="title3 mb-15">
                                78 Үнэлгээ
                                <div class="rateit mdIcon ml-20"  
                                     data-rateit-readonly="true"
                                     data-rateit-value="2.5"
                                     data-rateit-starwidth="22"
                                     data-rateit-starheight="22">
                                </div>
                            </div>

                            <div class="row">
<?php for ($i = 0; $i < 2; $i++) { ?>
                                    <div class="col-xs-6">
                                        <ul class="rateStat">
                                            <li>
                                                <span>Accury</span>
                                                <div class="rateit"  
                                                     data-rateit-readonly="true"
                                                     data-rateit-value="2.5">
                                                </div>
                                            </li>
                                            <li>
                                                <span>Communication</span>
                                                <div class="rateit"  
                                                     data-rateit-readonly="true"
                                                     data-rateit-value="2.5">
                                                </div>
                                            </li>
                                            <li>
                                                <span>Cleanliness</span>
                                                <div class="rateit"  
                                                     data-rateit-readonly="true"
                                                     data-rateit-value="2.5">
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
<?php } ?>
                            </div>
                        </div>
                        <div class="about">
                            <div class="title2 mb-25">Сэтгэгдлүүд</div>
                            <div class="commets">
<?php for ($i = 0; $i < 3; $i++) { ?>
                                    <div class="item">
                                        <div class="userhead">
                                            <div class="img">
                                                <img src="https://dev.veritech.mn/storage/uploads/process/file_1529031097927492_1521442811086.jpg" onerror="onItemImgError(this);">
                                            </div>
                                            <div class="username">Золбаяр</div>
                                            <div class="date"><?php echo Date::formatter('2015-05-23', 'Y.m.d'); ?></div>
                                        </div>
                                        <div class="comment">
                                            Хүнсний үйлдвэр барих Үндсэн хөрөнгөд 2018.06.03
                                        </div>
                                    </div>
<?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="protab2">
                        <div class="about mt-0">
                            <div class="title2 mb-25">Худалдан авалтын түүх</div>

                            <div class=""><!-- tabstyle3 -->
                                <ul class="fltr-control">
                                    <li class="active" href="#bhistory11" data-toggle="tab" aria-expanded="false">
                                        Талоноор
                                    </li>
                                    <li class="" href="#bhistory22" data-toggle="tab" aria-expanded="false">
                                        Бараагаар
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-content bgNone ph-0 pt-25"> 
                                <div class="tab-pane fade active in" id="bhistory11">
                                    <table class="table table-striped table-borderless">
                                        <thead>
                                            <tr>
                                                <th>Огноо</th>
                                                <th width="20%">Нийлүүлэгч</th>
                                                <th width="20%">Дэлгүүр</th>
                                                <th width="13%">Үнийн дүн</th>
                                                <th width="13%">Бонус</th>
                                                <th width="13%">НӨАТ</th>
                                                <th width="10%">Талон</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $tTotal = 0;
                                            $tSale = 0;
                                            $tNoat = 0;

                                            foreach ($this->purchase_list as $key => $value):
                                                ?>
                                                <?php
                                                if (is_numeric($key)):

                                                    $tTotal += $value['total'];
                                                    $tSale += $value['discount'];
                                                    $tNoat += $value['vat'];
                                                    ?>

                                                    <tr>
                                                        <td><?php echo $value['invoicedate']; ?></td>
                                                        <td><?php echo $value['departmentname']; ?></td>
                                                        <td><?php echo $value['storename']; ?></td>
                                                        <td class="ta-r"><?php echo number_format($value['total']); ?></td>
                                                        <td class="ta-r"><?php echo number_format($value['discount']); ?></td>
                                                        <td class="ta-r"><?php echo number_format($value['vat']); ?></td>
                                                        <td class="ta-c">
        <?php if ($value['attach']): ?>
                                                                <a href="#" class="showTalon" data-id="#talon_<?php echo $value['id']; ?>" data-toggle="modal" data-target="#talon_<?php echo $value['id']; ?>"><i class="fa fa-sticky-note"></i></a>                               

                                                                <div id="talon_<?php echo $value['id']; ?>" class="modal fade" role="dialog">
                                                                    <div class="modal-dialog">  <!-- modal-lg -->
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                <h4 class="modal-title ta-c">НӨАТ талон.</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <img src="<?php echo 'data:image/png;base64,' . $value['attach']; ?>" class="img-fluid">
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>


        <?php endif ?>
                                                        </td>
                                                    </tr>


    <?php endif; ?>
<?php endforeach; ?>

                                        </tbody>

                                        <thead>
                                            <tr>
                                                <th colspan="2">Нийт</th>
                                                <th class="ta-r"><?php echo number_format($tTotal); ?></th>
                                                <th class="ta-r"><?php echo number_format($tSale); ?></th>
                                                <th class="ta-r"><?php echo number_format($tNoat); ?></th>
                                                <th class="ta-c"></th>
                                            </tr>
                                        </thead>

                                    </table>
                                </div>
                                <div class="tab-pane fade" id="bhistory22">
                                    <div class="col-md-11  pr-60">
                                        <div class="row product-list product-list-view">
                                            <?php
                                            foreach ($this->itemList2 as $key => $item) {
                                                if (is_numeric($key)) {
                                                    ?>
                                                    <div class="col-md-4 product_list_wrap ph-8 <?php echo $classView; ?>"> <!-- col-md-3 col-sm-6 col-xs-12 ph-8 mb-16 -->
                                                        <div class="single-product shop">

        <?php if ($item['endqty'] == '0'): ?>
                                                                <span class="sold"><span>Зарагдаж дууссан</span></span>
                                                                    <?php endif; ?>

                                                            <div class="img-section list-col">
                                                                <a href="category/item/<?php echo $item['id']; ?>">
                                                                    <?php if (Input::getCheck('menu') && Input::get('menu') == '1515665385357'): ?>

                                                                        <img src="assets/emarket/img/document.png" onerror="onItemImgError(this);" class="img-fluid img-product" alt="<?php echo $item['name']; ?>">

                                                                    <?php else: ?>

                                                                        <img src="<?php echo IMG_BASE_URL . $item['photo']; ?>" onerror="onItemImgError(this);" class="img-fluid img-product" alt="<?php echo $item['name']; ?>">

        <?php endif; ?>
                                                                </a>
                                                            </div>

                                                            <div class="single-more">
                                                                <div class="mb10">
                                                                    <div class="col-md-12 col-sm-12 col-xs-12 ph-0 tc more-actions">
        <?php if ($item['endqty'] != '0'): ?>
                                                                            <div class="action"><span class="add-to-cart-btn" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Сагсанд хийх"><i class="fa fa-opencart"></i> </span></div>
        <?php endif; ?>
                                                                        <div class="action"><a href="category/item/<?php echo $item['id']; ?>"><span class="view-item-btn" title="Дэлгэрэнгүй"><i class="fa fa-eye"></i></span></a></div>
                                                                        <div class="action"><span class="add-to-wish-btn" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Хүсэлтийн жагсаалт"><i class="fa fa-heart"></i> </span></div>
                                                                    </div>
                                                                    <div class="clearfix w-100"></div>
                                                                </div>
                                                                <div class="prod-price">
                                                                    <div class="col-md-6 col-sm-6 col-xs-12 ph-0">
                                                                        <div class="p-first"><?php echo Number::formatMoney($item['price'], true); ?>₮</div>
                                                                    </div>
                                                                    <div class="col-md-6 col-sm-6 col-xs-12 ph-0">
                                                                        <div id="BVInlineRatings">
                                                                            <div class="starRate">
                                                                                <div class="starShow" style="width:10%"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="clearfix w-100"></div>
                                                                </div>

                                                                <a href="category/item/<?php echo $item['id']; ?>">
                                                                    <h4 class="det-one-prod" title="<?php echo $item['name']; ?>"><?php echo $item['name']; ?></h4>
                                                                </a>
                                                            </div>


                                                            <div class="list-col title_wrap" style="display: none;">
                                                                <a href="category/item/<?php echo $item['id']; ?>" class="title"><?php echo $item['name']; ?></a>
                                                                <div class="mt-7">
                                                                    <div id="BVInlineRatings" class="ta-l">
                                                                        <div class="starRate ml-0">
                                                                            <div class="starShow" style="width:10%"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="list-col infos" style="display: none;">
                                                                <span class="price"><?php echo Number::formatMoney($item['price'], true); ?>₮</span>
                                                            </div>
                                                            <div class="list-col infos" style="display: none;">
                                                                <select name="colorpicker-picker-longlist">
                                                                    <option value="#7bd148">Green</option>
                                                                    <option value="#5484ed">Bold blue</option>
                                                                    <option value="#a4bdfc">Blue</option>
                                                                </select>
                                                            </div>
                                                            <div class="list-col infos" style="display: none;">
                                                                <span class="vl fl cop itemqty_select" style="width: 95px;">
                                                                    <input type="text" class="form-control form-control-sm numberInit quantity-input product-quantity-list" data-bts-max="<?php echo $item['endqty']; ?>">
                                                                </span>
                                                            </div>
                                                            <div class="list-col infos" style="display: none;">
                                                                <?php ///if ( $item['endqty'] != '0'):  ?>
                                                                <button class="button-style-sp1 add-to-cart-btn" type="button" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Сагсанд хийх" <?php if ($item['endqty'] == '0') echo "disabled"; ?>>
                                                                    <i class="fa fa-opencart"></i>
                                                                </button>
        <?php //endif;  ?>
                                                            </div>

                                                            <div class="clearfix w-100"></div>
                                                        </div>
                                                    </div> <!-- -->
                                                    <?php
                                                }
                                            }
                                            ?>    
                                        </div>
                                    </div>
                                    <div class="clearfix w-100"></div>
                                </div>
                            </div>

                            <?php /* ?>

                              <div class="tab-content bgNone ph-0 pt-25">
                              <div class="tab-pane fade active in" id="bhistory11">
                              <!-- ------------------------------------ -->
                              <?php
                              $classView = '';
                              if ($this->curView === 'list') {
                              $classView = 'list_view';
                              }
                              ?>
                              <div class="col-md-11 pr-60">
                              <div class="row product-list product-list-view">
                              <?php
                              foreach ($this->itemList as $key => $item) {
                              if (is_numeric($key)) {
                              ?>
                              <div class="col-md-4 product_list_wrap ph-8 <?php echo $classView; ?>"> <!-- col-md-3 col-sm-6 col-xs-12 ph-8 mb-16 -->
                              <div class="single-product shop">

                              <?php if ($item['endqty'] == '0'): ?>
                              <span class="sold"><span>Зарагдаж дууссан</span></span>
                              <?php endif; ?>

                              <div class="img-section list-col">
                              <a href="category/item/<?php echo $item['id']; ?>">
                              <?php if (Input::getCheck('menu') && Input::get('menu') == '1515665385357'): ?>

                              <img src="assets/emarket/img/document.png" onerror="onItemImgError(this);" class="img-fluid img-product" alt="<?php echo $item['name']; ?>">

                              <?php else: ?>

                              <img src="<?php echo IMG_BASE_URL . $item['photo']; ?>" onerror="onItemImgError(this);" class="img-fluid img-product" alt="<?php echo $item['name']; ?>">

                              <?php endif; ?>
                              </a>
                              </div>

                              <div class="single-more">
                              <div class="mb10">
                              <div class="col-md-12 col-sm-12 col-xs-12 ph-0 tc more-actions">
                              <?php if ($item['endqty'] != '0'): ?>
                              <div class="action"><span class="add-to-cart-btn" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Сагсанд хийх"><i class="fa fa-opencart"></i> </span></div>
                              <?php endif; ?>
                              <div class="action"><a href="category/item/<?php echo $item['id']; ?>"><span class="view-item-btn" title="Дэлгэрэнгүй"><i class="fa fa-eye"></i></span></a></div>
                              <div class="action"><span class="add-to-wish-btn" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Хүсэлтийн жагсаалт"><i class="fa fa-heart"></i> </span></div>
                              </div>
                              <div class="clearfix w-100"></div>
                              </div>
                              <div class="prod-price">
                              <div class="col-md-6 col-sm-6 col-xs-12 ph-0">
                              <div class="p-first"><?php echo Number::formatMoney($item['price'], true); ?>₮</div>
                              </div>
                              <div class="col-md-6 col-sm-6 col-xs-12 ph-0">
                              <div id="BVInlineRatings">
                              <div class="starRate">
                              <div class="starShow" style="width:10%"></div>
                              </div>
                              </div>
                              </div>
                              <div class="clearfix w-100"></div>
                              </div>

                              <a href="category/item/<?php echo $item['id']; ?>">
                              <h4 class="det-one-prod" title="<?php echo $item['name']; ?>"><?php echo $item['name']; ?></h4>
                              </a>
                              </div>


                              <div class="list-col title_wrap" style="display: none;">
                              <a href="category/item/<?php echo $item['id']; ?>" class="title"><?php echo $item['name']; ?></a>
                              <div class="mt-7">
                              <div id="BVInlineRatings" class="ta-l">
                              <div class="starRate ml-0">
                              <div class="starShow" style="width:10%"></div>
                              </div>
                              </div>
                              </div>
                              </div>
                              <div class="list-col infos" style="display: none;">
                              <span class="price"><?php echo Number::formatMoney($item['price'], true); ?>₮</span>
                              </div>
                              <div class="list-col infos" style="display: none;">
                              <select name="colorpicker-picker-longlist">
                              <option value="#7bd148">Green</option>
                              <option value="#5484ed">Bold blue</option>
                              <option value="#a4bdfc">Blue</option>
                              </select>
                              </div>
                              <div class="list-col infos" style="display: none;">
                              <span class="vl fl cop itemqty_select" style="width: 95px;">
                              <input type="text" class="form-control form-control-sm numberInit quantity-input product-quantity-list" data-bts-max="<?php echo $item['endqty']; ?>">
                              </span>
                              </div>
                              <div class="list-col infos" style="display: none;">
                              <?php ///if ( $item['endqty'] != '0'): ?>
                              <button class="button-style-sp1 add-to-cart-btn" type="button" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Сагсанд хийх" <?php if ($item['endqty'] == '0') echo "disabled"; ?>>
                              <i class="fa fa-opencart"></i>
                              </button>
                              <?php //endif; ?>
                              </div>

                              <div class="clearfix w-100"></div>
                              </div>
                              </div> <!-- -->
                              <?php
                              }
                              }
                              ?>
                              </div>
                              </div>
                              <div class="clearfix w-100"></div>
                              <!-- ------------------------------------ -->
                              </div>
                              <div class="tab-pane fade" id="bhistory22">
                              <div class="col-md-11  pr-60">
                              <div class="row product-list product-list-view">
                              <?php
                              foreach ($this->itemList2 as $key => $item) {
                              if (is_numeric($key)) {
                              ?>
                              <div class="col-md-4 product_list_wrap ph-8 <?php echo $classView; ?>"> <!-- col-md-3 col-sm-6 col-xs-12 ph-8 mb-16 -->
                              <div class="single-product shop">

                              <?php if ($item['endqty'] == '0'): ?>
                              <span class="sold"><span>Зарагдаж дууссан</span></span>
                              <?php endif; ?>

                              <div class="img-section list-col">
                              <a href="category/item/<?php echo $item['id']; ?>">
                              <?php if (Input::getCheck('menu') && Input::get('menu') == '1515665385357'): ?>

                              <img src="assets/emarket/img/document.png" onerror="onItemImgError(this);" class="img-fluid img-product" alt="<?php echo $item['name']; ?>">

                              <?php else: ?>

                              <img src="<?php echo IMG_BASE_URL . $item['photo']; ?>" onerror="onItemImgError(this);" class="img-fluid img-product" alt="<?php echo $item['name']; ?>">

                              <?php endif; ?>
                              </a>
                              </div>

                              <div class="single-more">
                              <div class="mb10">
                              <div class="col-md-12 col-sm-12 col-xs-12 ph-0 tc more-actions">
                              <?php if ($item['endqty'] != '0'): ?>
                              <div class="action"><span class="add-to-cart-btn" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Сагсанд хийх"><i class="fa fa-opencart"></i> </span></div>
                              <?php endif; ?>
                              <div class="action"><a href="category/item/<?php echo $item['id']; ?>"><span class="view-item-btn" title="Дэлгэрэнгүй"><i class="fa fa-eye"></i></span></a></div>
                              <div class="action"><span class="add-to-wish-btn" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Хүсэлтийн жагсаалт"><i class="fa fa-heart"></i> </span></div>
                              </div>
                              <div class="clearfix w-100"></div>
                              </div>
                              <div class="prod-price">
                              <div class="col-md-6 col-sm-6 col-xs-12 ph-0">
                              <div class="p-first"><?php echo Number::formatMoney($item['price'], true); ?>₮</div>
                              </div>
                              <div class="col-md-6 col-sm-6 col-xs-12 ph-0">
                              <div id="BVInlineRatings">
                              <div class="starRate">
                              <div class="starShow" style="width:10%"></div>
                              </div>
                              </div>
                              </div>
                              <div class="clearfix w-100"></div>
                              </div>

                              <a href="category/item/<?php echo $item['id']; ?>">
                              <h4 class="det-one-prod" title="<?php echo $item['name']; ?>"><?php echo $item['name']; ?></h4>
                              </a>
                              </div>


                              <div class="list-col title_wrap" style="display: none;">
                              <a href="category/item/<?php echo $item['id']; ?>" class="title"><?php echo $item['name']; ?></a>
                              <div class="mt-7">
                              <div id="BVInlineRatings" class="ta-l">
                              <div class="starRate ml-0">
                              <div class="starShow" style="width:10%"></div>
                              </div>
                              </div>
                              </div>
                              </div>
                              <div class="list-col infos" style="display: none;">
                              <span class="price"><?php echo Number::formatMoney($item['price'], true); ?>₮</span>
                              </div>
                              <div class="list-col infos" style="display: none;">
                              <select name="colorpicker-picker-longlist">
                              <option value="#7bd148">Green</option>
                              <option value="#5484ed">Bold blue</option>
                              <option value="#a4bdfc">Blue</option>
                              </select>
                              </div>
                              <div class="list-col infos" style="display: none;">
                              <span class="vl fl cop itemqty_select" style="width: 95px;">
                              <input type="text" class="form-control form-control-sm numberInit quantity-input product-quantity-list" data-bts-max="<?php echo $item['endqty']; ?>">
                              </span>
                              </div>
                              <div class="list-col infos" style="display: none;">
                              <?php ///if ( $item['endqty'] != '0'): ?>
                              <button class="button-style-sp1 add-to-cart-btn" type="button" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Сагсанд хийх" <?php if ($item['endqty'] == '0') echo "disabled"; ?>>
                              <i class="fa fa-opencart"></i>
                              </button>
                              <?php //endif; ?>
                              </div>

                              <div class="clearfix w-100"></div>
                              </div>
                              </div> <!-- -->
                              <?php
                              }
                              }
                              ?>
                              </div>
                              </div>
                              <div class="clearfix w-100"></div>
                              </div>
                              <div class="tab-pane fade" id="bhistory33">
                              <div class="col-md-11  pr-60">
                              <div class="row product-list product-list-view">
                              <?php
                              foreach ($this->itemList3 as $key => $item) {
                              if (is_numeric($key)) {
                              ?>
                              <div class="col-md-4 product_list_wrap ph-8 <?php echo $classView; ?>"> <!-- col-md-3 col-sm-6 col-xs-12 ph-8 mb-16 -->
                              <div class="single-product shop">

                              <?php if ($item['endqty'] == '0'): ?>
                              <span class="sold"><span>Зарагдаж дууссан</span></span>
                              <?php endif; ?>

                              <div class="img-section list-col">
                              <a href="category/item/<?php echo $item['id']; ?>">
                              <?php if (Input::getCheck('menu') && Input::get('menu') == '1515665385357'): ?>

                              <img src="assets/emarket/img/document.png" onerror="onItemImgError(this);" class="img-fluid img-product" alt="<?php echo $item['name']; ?>">

                              <?php else: ?>

                              <img src="<?php echo IMG_BASE_URL . $item['photo']; ?>" onerror="onItemImgError(this);" class="img-fluid img-product" alt="<?php echo $item['name']; ?>">

                              <?php endif; ?>
                              </a>
                              </div>

                              <div class="single-more">
                              <div class="mb10">
                              <div class="col-md-12 col-sm-12 col-xs-12 ph-0 tc more-actions">
                              <?php if ($item['endqty'] != '0'): ?>
                              <div class="action"><span class="add-to-cart-btn" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Сагсанд хийх"><i class="fa fa-opencart"></i> </span></div>
                              <?php endif; ?>
                              <div class="action"><a href="category/item/<?php echo $item['id']; ?>"><span class="view-item-btn" title="Дэлгэрэнгүй"><i class="fa fa-eye"></i></span></a></div>
                              <div class="action"><span class="add-to-wish-btn" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Хүсэлтийн жагсаалт"><i class="fa fa-heart"></i> </span></div>
                              </div>
                              <div class="clearfix w-100"></div>
                              </div>
                              <div class="prod-price">
                              <div class="col-md-6 col-sm-6 col-xs-12 ph-0">
                              <div class="p-first"><?php echo Number::formatMoney($item['price'], true); ?>₮</div>
                              </div>
                              <div class="col-md-6 col-sm-6 col-xs-12 ph-0">
                              <div id="BVInlineRatings">
                              <div class="starRate">
                              <div class="starShow" style="width:10%"></div>
                              </div>
                              </div>
                              </div>
                              <div class="clearfix w-100"></div>
                              </div>

                              <a href="category/item/<?php echo $item['id']; ?>">
                              <h4 class="det-one-prod" title="<?php echo $item['name']; ?>"><?php echo $item['name']; ?></h4>
                              </a>
                              </div>


                              <div class="list-col title_wrap" style="display: none;">
                              <a href="category/item/<?php echo $item['id']; ?>" class="title"><?php echo $item['name']; ?></a>
                              <div class="mt-7">
                              <div id="BVInlineRatings" class="ta-l">
                              <div class="starRate ml-0">
                              <div class="starShow" style="width:10%"></div>
                              </div>
                              </div>
                              </div>
                              </div>
                              <div class="list-col infos" style="display: none;">
                              <span class="price"><?php echo Number::formatMoney($item['price'], true); ?>₮</span>
                              </div>
                              <div class="list-col infos" style="display: none;">
                              <select name="colorpicker-picker-longlist">
                              <option value="#7bd148">Green</option>
                              <option value="#5484ed">Bold blue</option>
                              <option value="#a4bdfc">Blue</option>
                              </select>
                              </div>
                              <div class="list-col infos" style="display: none;">
                              <span class="vl fl cop itemqty_select" style="width: 95px;">
                              <input type="text" class="form-control form-control-sm numberInit quantity-input product-quantity-list" data-bts-max="<?php echo $item['endqty']; ?>">
                              </span>
                              </div>
                              <div class="list-col infos" style="display: none;">
                              <?php ///if ( $item['endqty'] != '0'): ?>
                              <button class="button-style-sp1 add-to-cart-btn" type="button" data-prod-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" title="Сагсанд хийх" <?php if ($item['endqty'] == '0') echo "disabled"; ?>>
                              <i class="fa fa-opencart"></i>
                              </button>
                              <?php //endif; ?>
                              </div>

                              <div class="clearfix w-100"></div>
                              </div>
                              </div> <!-- -->
                              <?php
                              }
                              }
                              ?>
                              </div>
                              </div>
                              <div class="clearfix w-100"></div>
                              </div>
                              </div>
                             */ ?>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="protab3">

                        <div class="about">
                            <div class="title2 mb-25">Гишүүнчлэлийн мэдээлэл</div>

                            <div class="col-md-11 pr-60">
                                <div class="mycardlist">
                                    <div class="row">
                                        <?php
                                        foreach ($this->carts as $cart):
                                            $card_img = 'assets/emarket/img/banner/cart-def.jpg';
                                            if ($cart['physicalpath'])
                                                $card_img = IMG_BASE_URL . $cart['physicalpath'];
                                            ?>
                                            <div class="col-md-4 ph-10 mb-20">
                                                <div class="item">
                                                    <div class="wimg">
                                                        <img src="<?php echo $card_img; ?>" class="img-fluid">
                                                    </div>
                                                    <div class="ph-0">
                                                        <div class="action mv-8">
                                                            <span class="action_but">
                                                                <i class="fa fa-opencart"></i>
                                                            </span>
                                                            <span class="action_but">
                                                                <i class="fa fa-eye"></i>
                                                            </span>
                                                            <span class="action_but">
                                                                <i class="fa fa-heart"></i>
                                                            </span>
                                                        </div>
                                                        <h3 class="title mt-5 mb-15">
                                                                <?php // echo Str::moreMB($cart['name'],15); ?>
                                                                <?php echo 'Хөнгөлөлтийн хувь: <b>' . $cart['cardpercent'] . '%</b>'; ?>
                                                        </h3>
                                                        <div class="ta-c mb-0">
                                                            <div class="mb-10">
    <?php
    loadBarCodeImageData();
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($cart['cardnumber'], $generator::TYPE_CODE_128, 2, 50)) . '" border="0" style="width: 100%;height: auto;">';
    ?>
                                                            </div>

                                                            <div class="infos">
                                                                <div class="name">Нийт худалдан авалт</div>
                                                                <div class="value"><?php echo Number::formatMoney($cart['total']); ?>₮</div>
                                                            </div>

                                                            <div class="infos">
                                                                <div class="name">Бонус үлдэгдэл</div>
                                                                <div class="value"><?php echo Number::formatMoney($cart['discount']); ?>₮</div>
                                                            </div>

                                                            <div style="display: inline-block;" class="shop">
                                                                <div id="BVInlineRatings" class="ta-c">
                                                                    <div class="starRate ml-0">
                                                                        <div class="starShow" style="width:37%"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
<?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix w-100"></div>

                        </div>

                    </div>

                    <div class="tab-pane fade" id="protab4">

                        <div class="row">
                            <div class="col-md-11 pr-60">
                                <?php
                                $tmpDiscount = array(
                                    array(
                                        'title' => 'E Mart',
                                        'title2' => 'Урамшуулалтай худалдаа',
                                        'img' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrTHRGvWz6Rt9Ogl74ueuCP0im-9BteHbUjakHJmpntwFf2cpx',
                                        'date' => '2018-08-05',
                                        'date2' => '2018-09-05',
                                        'comment' => rand(5, 15),
                                        'preview' => rand(5, 15),
                                        'desc' => 'Хөнгөлөлтийн картын эзэмшигч бүр уг урамшуулалд хамрагдана'
                                    ),
                                    array(
                                        'title' => 'Номин карт',
                                        'title2' => 'Урамшуулалтай худалдаа',
                                        'img' => 'http://v2.flyercenter.com/resources/images/public/1508/55c3be4d5e8bd.jpg',
                                        'date' => '2018-08-05',
                                        'date2' => '2018-09-05',
                                        'comment' => rand(5, 15),
                                        'preview' => rand(5, 15),
                                        'desc' => 'Хөнгөлөлтийн картын эзэмшигч бүр уг урамшуулалд хамрагдана'
                                    ),
                                    array(
                                        'title' => 'Тэс карт',
                                        'title2' => 'Урамшуулалтай худалдаа',
                                        'img' => 'https://www.retaildetail.eu/sites/default/files/styles/news/public/news/shutterstock_248016445.jpg?itok=5gx8GxYq',
                                        'date' => '2018-08-05',
                                        'date2' => '2018-09-05',
                                        'comment' => rand(5, 15),
                                        'preview' => rand(5, 15),
                                        'desc' => 'Хөнгөлөлтийн картын эзэмшигч бүр уг урамшуулалд хамрагдана'
                                    )
                                );
                                ?>

                                <div class="row mh-n-10 cartContent">

<?php foreach ($tmpDiscount as $key => $value): ?>
                                        <div class="col-md-4 ph-10">
                                            <div class="item">
                                                <div class="imgwrap">
                                                    <img src="<?php echo $value['img']; ?>">
                                                </div>

                                                <div class="ph-12 pv-15">
                                                    <div class="dateinfo">
                                                        <i class="fa fa-calendar"></i>
                                                        <?php echo Date::formatter($value['date'], 'Y.m.d') . ' - ' . Date::formatter($value['date2'], 'Y.m.d'); ?>
                                                    </div>
                                                    <div class="title"><?php echo $value['title']; ?></div>
                                                    <div class="title2"><?php echo $value['title2']; ?></div>
                                                    <div class="desc">
                                                            <?php echo $value['desc']; ?>
                                                    </div>
                                                    <div class="infoswrap">
                                                        <div class="">
                                                            <i class="fa fa-comment"></i>
    <?php echo $value['comment']; ?>
                                                        </div>
                                                        <div class="">
                                                            <i class="fa fa-eye"></i>
    <?php echo $value['preview']; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
<?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3 bgWhite mt-36"> <!-- pl-20  -->
                <div class="userinfo pt-20">
                    <div id="useraccordion">
                        <div class="user_detail noborder pt-0">

                            <a data-toggle="collapse" data-parent="#useraccordion" href="#useraccordion1" class="button-style-sp2 block">Миний харах</a> <!-- target="_blank" -->

                            <div id="useraccordion1" class="panel-collapse collapse in">
                                <div class="userImg mt-20">
                                    <img src="http://supply.veritech.mn/storage/uploads/process/file_1519800898652224_1484720782410325.png" onerror="onItemImgError(this);">
                                </div>

                                <div class="username">
                                    <?php //echo Session::get(SESSION_PREFIX . 'customerName'); ?>
                                     Гангамөрөн
                                    <?php //var_dump($_SESSION);  ?>
                                </div>

                                <ul class="detailInfo">
                                    <?php //if ( isset($this->companyDetail['dc_department_dtl']['officephone']) && !empty($this->companyDetail['dc_department_dtl']['officephone']) ):  ?>
                                    <li><span>Утас :</span> 99885533</li>
                                    <?php //endif;  ?>

                                    <?php //if ( isset($this->companyDetail['registrationnumber']) && !empty($this->companyDetail['registrationnumber']) ):  ?>
                                    <li><span>И-майл :</span> boldoo22@yahoo.com</li>
                                    <?php //endif;  ?>

                                    <?php //if ( isset($this->companyDetail['industryid']['name']) ): ?>
                                    <li><span>Факс :</span>333-444-5555</li>
<?php //endif;  ?>

<?php /* if ( isset($this->companyDetail['dc_department_dtl']['email']) && !empty($this->companyDetail['dc_department_dtl']['email']) ): ?>
  <li><span>И-мэйл :</span><a href="mailto:<?php echo $this->companyDetail['dc_department_dtl']['email']; ?>"><?php echo $this->companyDetail['dc_department_dtl']['email']; ?></a></li>
  <?php endif; */ ?>
                                </ul>

                                <ul class="socials mv-15">
                                    <li>
                                        <a class="facebook" href="javascript:;"><i class="fa fa-facebook"></i></a>
                                    </li>
                                    <li>
                                        <a class="twitter" href="javascript:;"><i class="fa fa-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a class="google" href="javascript:;"><i class="fa fa-google"></i></a>
                                    </li>
                                    <li>
                                        <a class="linkedin" href="javascript:;"><i class="fa fa-linkedin"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix w-100"></div>
                            </div>

                            <a data-toggle="collapse" data-parent="#useraccordion" href="#useraccordion2" class="button-style-sp2 block mt-10 mb-20" target="_blank">Миний эрхүүд</a>
                            <!-- <div class="username mt-35 mb-20">Бизнесийн тухай</div> -->
                            <div id="useraccordion2" class="panel-collapse collapse">
                                <div class="row mh-n-4">
                                    <div class="col-sm-6 ph-4">
                                        <div class="box-info">
                                            <div class="value">16</div>
                                            <div class="titletxt">Бэлэг</div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 ph-4">
                                        <div class="box-info">
                                            <div class="value">
                                                8
                                            </div>
                                            <div class="titletxt">Гишүүнчлэл</div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 ph-4">
                                        <div class="box-info mt-8">
                                            <div class="value">
                                                7
                                            </div>
                                            <div class="titletxt">Эрх</div>
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
</div>

<style type="text/css">
    .veri-supply-<?php echo $this->uniqId; ?> .tabstyle2 {
        border-bottom: 1px solid #bfbfbf;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .tabstyle2 li {
        font-size: 15px;
        color: #000;
        border: none;
        font-family: "Roboto" !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .tabstyle2 li:hover {
        color: #b20838;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .tabstyle2 li.active {
        color: #b20838;
        font-family: "Roboto Medium" !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .tabstyle3 {
        /*border-bottom: 1px solid #bfbfbf;*/
    }
    .veri-supply-<?php echo $this->uniqId; ?> .tabstyle3 li {
        font-size: 14px;
        color: #000;
        border: none;
        font-family: "Roboto" !important;
        padding-bottom: 10px;
        border-bottom: 2px solid transparent;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .tabstyle3 li:hover {
        color: #3ec3d6;
        border-color: #3ec3d6;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .tabstyle3 li.active {
        color: #3ec3d6;
        border-color: #3ec3d6;
        font-family: "Roboto bold" !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .homeChart {
        position: relative;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .homeChart .item {
        position: relative;
        border: 1px solid #dcdcdc;
        border-right: none;
        padding: 15px;
        width: 22%;
        min-height: 250px;
        float: left;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .homeChart .item.itemStyle1 {
        border-right: 1px solid #dcdcdc;
        width: 34%;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .homeChart .item.block {
        width: 100%;
        display: block;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .homeChart .item.height200 {
        min-height: 200px;
        height: 200px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .homeChart .item .lxtitle {
        font-size: 55px;
        font-family: "Roboto Medium";
        color: #343a40;
        margin: 0;
        line-height: 1;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .homeChart .item .lgtitle {
        font-size: 38px;
        font-family: "Roboto Light";
        color: #33393f;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .homeChart .item .mdtitle {
        font-size: 22px;
        line-height: 22px;
        color: #343a40;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .homeChart .item .smtitle {
        font-size: 12px;
        font-family: "Roboto Light";
        line-height: 13px;
        color: #8b90a5;
        text-transform: uppercase;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .homeChart .item .smtitle b {
        color: #000;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .homeChart .item .smtitle.normalcase {
        text-transform: none;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .homeChart .item .smtitle.black {
        color: #33393f;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .homeChart .item .totalResult {
        font-size: 32px;
        line-height: 32px;
        color: #333;
        font-family: "Roboto";
    }
    .veri-supply-<?php echo $this->uniqId; ?> .homeChart .item .totalResult span {
        font-size: 12px;
        line-height: 32px;
        vertical-align: middle;
        margin-left: 10px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .homeChart .item .totalResult span.green {
        color: #22bf07;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .homeChart .item .totalResult span.red {
        color: #dc3545;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .homeChart .item .rt-desc {
        position: absolute;
        display: block;
        width: 50%;
        top: 15px;
        left: 50%;
        margin-left: -15px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .hmCartBg {
        width: 100%;
        overflow: hidden;
        height: 95px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .hmCartBg2 {
        width: 100%;
        overflow: hidden;
        height: 100px;
        margin-top: 68px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .hmCartBg3 {
        position: absolute;
        display: block;
        width: 100%;
        left: 0;
        bottom: 0;
        height: 30px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .hmCartBg4 {
        position: absolute;
        display: block;
        width: 100%;
        left: 0;
        bottom: 0;
        height: 155px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .xsprocessbar .progress {
        height: 5px;
        margin-bottom: 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .xsprocessbar .descrip {
        font-size: 14px;
        font-family: "Roboto Light";
        color: #9095b4;
        margin-bottom: 5px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .progress-bar.dcblue {
        background-color: #1b84e7;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .progress-bar.dcred {
        background-color: #dc3545;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .progress-bar.dcgreen {
        background-color: #22bf07;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .progress-bar.dcorange {
        background-color: #f49917;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .progress-bar.dcpurple {
        background-color: #6f42c1;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .progress-bar.dcpink {
        background-color: #e83e8c;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo {
        text-align: center;
        font-size: 12px;
        font-family: "Roboto";
        color: #000;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo .userImg {
        width: 130px;
        height: 130px;
        border-radius: 50% !important;
        overflow: hidden;
        display: inline-block;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo .userImg img {
        width: 130px;
        height: 130px;
        object-fit: cover;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo .username {
        font-size: 18px;
        margin-bottom: 25px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo .detailInfo {
        list-style: none;
        margin: 0;
        padding: 0;
        font-family: "Roboto Light";
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo .detailInfo li {
        margin-bottom: 10px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo .detailInfo li span {
        color: #808080;
        margin-right: 5px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo .user_detail {
        padding-top: 25px;
        padding-bottom: 10px;
        border-left: 1px solid #ececec;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo .user_detail.noborder {
        border: none;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo .user_detail .box-info {
        border: 1px solid #dcdcdc;
        font-family: "Roboto Light";
        font-size: 11px;
        color: #000;
        text-align: center;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo .user_detail .box-info .value {
        font-family: "Roboto Medium";
        font-size: 26px;
        color: #f6af05;
        margin-top: 20px;
        margin-bottom: 10px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo .user_detail .box-info .value .fa {
        font-size: 25px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo .user_detail .box-info .titletxt {
        height: 24px;
        line-height: 12px;
        margin-bottom: 13px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo .user_detail .socials {
        list-style: none;
        padding: 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo .user_detail .socials li {
        display: inline-block;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo .user_detail .socials li a {
        border-radius: 50% !important;
        color: #fff;
        width: 22px;
        height: 22px;
        display: block;
        background: #ccc;
        text-align: center;
        line-height: 20px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo .user_detail .socials li a.facebook {
        background: #527ac3;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo .user_detail .socials li a.twitter {
        background: #64cff8;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo .user_detail .socials li a.linkedin {
        background: #88cde0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo .user_detail .socials li a.google {
        background: #f26264;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .userinfo .user_detail .socials li a .fa {
        font-size: 12px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .title {
        font-family: "Roboto";
        font-size: 30px;
        color: #000;
        line-height: 1;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .title2 {
        font-family: "Roboto";
        font-size: 20px;
        color: #000;
        line-height: 1;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .title3 {
        font-family: "Roboto";
        font-size: 24px;
        color: #000;
        line-height: 1;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview ul.headInfo {
        list-style: none;
        padding: 0;
        float: left;
        width: 100%;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview ul.headInfo li {
        /*display: inline-block;*/
        display: block;
        float: left;
        margin-right: 10px;
        border: 1px solid #dcdcdc;
        width: 100px;
        height: 100px;
        text-align: center;
        padding-top: 22px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview ul.headInfo li .name {
        font-family: "Roboto Light";
        font-size: 12px;
        color: #000;
        line-height: 1;
        display: block;
        /*white-space: nowrap;*/
        /*margin-bottom: 10px;*/
        margin-top: 17px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview ul.headInfo li .icon {
        margin-right: 10px;
        display: inline-block;
        background: url("../img/icon/collateral_icons.png");
        background-repeat: no-repeat;
        height: 24px;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        overflow: hidden;
        border: none !important;
        padding: 0 !important;
        vertical-align: top;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview ul.headInfo li .icon.door {
        width: 18px;
        background-position: 0 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview ul.headInfo li .icon.calendar {
        width: 24px;
        background-position: 0 -24px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview ul.headInfo li .icon.size {
        width: 24px;
        background-position: 0 -48px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview ul.headInfo li .icon.graj {
        width: 24px;
        background-position: 0 -72px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview ul.headInfo li .text {
        font-family: "Roboto";
        font-size: 12px;
        color: #000;
        /*line-height: 1;*/
        display: inline-block;
        height: 24px;
        line-height: 24px;
        margin: 0;
        vertical-align: top;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about {
        margin-top: 30px;
        margin-bottom: 30px;
        padding-top: 30px;
        border-top: 1px solid #bfbfbf;
        /*padding-bottom: 30px;*/
        /*border-bottom: 1px solid #bfbfbf;*/
        font-size: 12px;
        font-family: "Roboto";
        line-height: 20px;
        color: #000;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about ul.dotted,
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about ul.col2,
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about ul.rateStat,
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about ul.detailDesc {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about ul.dotted li {
        padding-left: 15px;
        position: relative;
        margin-bottom: 20px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about ul.dotted li:before {
        display: block;
        content: "";
        position: absolute;
        width: 6px;
        height: 6px;
        background: #b20838;
        border-radius: 50% !important;
        left: 0;
        top: 50%;
        margin-top: -3px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about ul.col2 {
        float: left;
        width: 100%;
        position: relative;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about ul.col2:before,
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about ul.col2:after {
        clear: both;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about ul.col2 li {
        width: 45%;
        padding-right: 5%;
        display: block;
        float: left;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about ul.detailDesc li {
        margin-bottom: 10px;
        color: #b20838;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about ul.detailDesc li span {
        color: #000;
        width: 270px;
        display: inline-block;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about ul.rateStat {
        border-top: 1px solid #dbdbdb;
        padding: 15px 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about ul.rateStat li {
        font-size: 14px;
        font-family: "Roboto Light";
        margin-bottom: 15px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about ul.rateStat li span {
        display: inline-block;
        width: 175px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about .commets .item {
        padding-bottom: 25px;
        margin-bottom: 25px;
        border-bottom: 1px solid #dbdbdb;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about .commets .item:last-child {
        border-bottom: none;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about .commets .userhead {
        margin-bottom: 15px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about .commets .userhead .img {
        border-radius: 50% !important;
        width: 45px;
        height: 45px;
        overflow: hidden;
        float: left;
        margin-right: 15px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about .commets .userhead .img img {
        width: 45px;
        height: 45px;
        object-fit: cover;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about .commets .userhead .username {
        font-family: "Roboto Medium";
        font-size: 14px;
        margin-bottom: 3px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about .commets .userhead .date {
        font-family: "Roboto Light";
        font-size: 14px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .about .commets .comment {
        font-family: "Roboto Light";
        font-size: 14px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .cartStatic {
        display: block;
        position: relative;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .cartStatic .item {
        display: inline-block;
        background: #70a7fd;
        margin-right: 20px;
        color: #fff;
        padding: 15px 20px;
        min-width: 190px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .cartStatic .item.purple {
        background: #b088d8;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .cartStatic .item.pink {
        background: #f7769c;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .cartStatic .item .icon {
        position: absolute;
        display: block;
        width: 40px;
        height: 40px;
        /*background: red;
        left: 0;
        top: 0;*/
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .cartStatic .item .icon {
        height: 40px;
        width: auto;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .cartStatic .item .name {
        text-transform: uppercase;
        font-size: 12px;
        font-family: "Roboto Light";
        text-align: right;
        min-height: 40px;
        line-height: 40px;
        padding-left: 45px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .cartStatic .item .value {
        white-space: nowrap;
        font-size: 32px;
        font-family: "Roboto Light";
        padding-left: 20px;
        text-align: right;
        height: 25px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview .cartStatic .item .value span {
        font-size: 18px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .collateralview.crdstyle .about {
        background: #fff;
        padding: 20px;
        border: none;
        margin-bottom: 20px;
        margin-top: 0px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item {
        background: #fff;
        border: 1px solid #dcdcdc;
        padding: 25px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item .action {
        display: block;
        text-align: center;
        min-height: 30px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item .action .action_but {
        width: 30px;
        height: 30px;
        background: #ebebeb;
        position: relative;
        border-radius: 100% !important;
        line-height: 30px;
        text-align: center;
        display: inline-block;
        margin: 0 2px;
        cursor: pointer;
        color: #3c3c3c;
        display: none;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item .action .action_but:hover {
        background: #c61932;
        color: #fff;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item .wimg {
        width: 100%;
        height: 90px;
        /*154px*/
        overflow: hidden;
        text-align: center;
        object-fit: cover;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item img {
        /*width: 100%;
        height: auto;*/
        object-fit: cover;
        height: 90px;
        width: 100%;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item .title {
        /*font-family: 'Roboto Bold';
        font-size: 17px;*/
        font-family: "Roboto";
        font-size: 12px;
        text-transform: uppercase;
        text-align: center;
        color: #000;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item .title b {
        font-family: "Roboto Medium";
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item .desc {
        font-size: 14px;
        line-height: 16px;
        font-family: "Roboto";
        color: #000;
        min-height: 35px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item .type_button {
        background: transparent;
        border-radius: 20px !important;
        border: 1px solid #000;
        width: 145px;
        height: 32px;
        color: #000;
        font-size: 12px;
        /*text-transform: uppercase;*/
        font-family: "Roboto";
        display: inline-block;
        line-height: 30px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item .type_button:hover {
        background: #0cc3ce;
        border-color: #0cc3ce;
        color: #fff !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item .type_button .deactxt {
        display: block;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item .type_button .actxt {
        display: none;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item .type_button.active {
        background: #41b1e3;
        border-color: #41b1e3;
        color: #fff;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item .type_button.active .fa {
        margin-left: 5px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item .type_button.active .deactxt {
        display: none;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item .type_button.active .actxt {
        display: block;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item .moreInf {
        font-size: 14px;
        font-family: "Roboto";
        color: #2994ca;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item:hover .action .action_but {
        display: inline-block;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item .infos {
        text-align: center;
        margin-bottom: 10px;
        font-size: 12px;
        line-height: 15px;
        font-family: "Roboto Light";
        color: #000;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .mycardlist .item .infos .value {
        color: #b20838;
        font-family: "Roboto";
        font-size: 12px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .cartContent {
        position: relative;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .cartContent .item {
        background: #fff;
        border: 1px solid #dcdcdc;
        font-size: 11px;
        line-height: 13px;
        font-family: "Roboto";
        color: #646464;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .cartContent .item .imgwrap {
        width: 100%;
        height: 165px;
        overflow: hidden;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .cartContent .item .imgwrap img {
        width: 100%;
        height: 165px;
        object-fit: cover;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .cartContent .item .dateinfo {
        color: #646464;
        font-size: 11px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .cartContent .item .dateinfo .fa {
        margin-right: 5px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .cartContent .item .title {
        color: #000;
        font-size: 14px;
        font-family: "Roboto Medium";
        text-align: center;
        margin: 10px 0;
        text-transform: uppercase;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .cartContent .item .title2 {
        font-size: 12px;
        font-family: "Roboto Medium";
        text-align: center;
        color: #000;
        margin-bottom: 5px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .cartContent .item .infoswrap {
        margin-top: 13px;
        text-align: right;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .cartContent .item .infoswrap div {
        display: inline-block;
        margin-left: 10px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .cartContent .item .infoswrap div .fa {
        margin-right: 5px;
    }    
    .veri-supply-<?php echo $this->uniqId; ?> html, .veri-supply-<?php echo $this->uniqId; ?> body {
      height: 100%;
    }
    .veri-supply-<?php echo $this->uniqId; ?> #containerwithheigth {
      min-height: 100%;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .ui-state-default {
      background: #fff;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .breadcrumb {
      font: 14px "Roboto", sans-serif;
      float: left;
      width: 100%;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .page-slider {
      margin: 0 !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .sidebar {
      font: 15px "Roboto", sans-serif;
      color: #282c3f;
      background: #fff;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .sidebar .list-group-item {
      background: transparent;
      /* #ebebeb */
      padding: 5px 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .sidebar .list-group-item div.checker {
      margin: 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .sidebar-filter {
      /*background: #ebebeb;*/
      padding: 0 0 15px;
      margin: 1px 0 15px;
      border-bottom: 1px solid #dcdcdc;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .sidebar-filter:last-child {
      border-bottom: none;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .sidebar-filter .sidebar-menu {
      /*max-height: 300px;
      min-height: 70px;
      overflow-y: auto;
      overflow-x: hidden;*/
    }
    .veri-supply-<?php echo $this->uniqId; ?> .sidebar-filter h2 {
      font-size: 18px;
      margin: 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .sidebar-filter h3 {
      font-family: "Roboto Bold", sans-serif;
      font-size: 12px;
      padding: 0;
      margin: 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .sidebar-filter a {
      font: 300 12px "Roboto", sans-serif;
      padding: 0;
      margin: 0;
      color: #787878;
      /*height: 18px;*/
    }
    .veri-supply-<?php echo $this->uniqId; ?> .sidebar-filter a.checked {
      color: #ff9800;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .sidebar-filter label {
      text-transform: none;
      font: 400 13px "Roboto", sans-serif;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .sidebar-filter #amount {
      background: transparent;
      /*#ebebeb*/
      font-weight: normal !important;
      color: #767F88 !important;
      /* display: none;*/
    }
    .veri-supply-<?php echo $this->uniqId; ?> .sidebar-filter .ui-widget-content {
      border: none;
      background: #e5e5e5;
      border-radius: 0;
      height: 4px !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .ui-slider-horizontal .ui-slider-handle {
      top: -7px !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .sidebar-filter .ui-widget-header {
      background: #00a9e4;
      /*ffb848*/
    }
    .veri-supply-<?php echo $this->uniqId; ?> .ui-slider-horizontal .ui-slider-handle {
      border-radius: 50% !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .color-filter .color-box {
      /*width: 30px;
      height: 20px;*/
      margin-right: 7px;
      margin-bottom: 7px;
      float: left;
      width: 27px;
      height: 27px;
      border: 2px solid #dcdcdc;
      padding: 1px;
      background-color: #fff;
      border-radius: 50% !important;
      overflow: hidden;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .color-filter .color-box div {
      width: 100%;
      height: 100%;
      border-radius: 50% !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .color-filter .color-box.selected,
    .veri-supply-<?php echo $this->uniqId; ?> .color-filter .color-box:hover {
      /*border: 2px solid #1cc3c9;*/
      border-color: #ff9800;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .product-main-image,
    .veri-supply-<?php echo $this->uniqId; ?> .product-main-image_new {
      margin-bottom: 6px;
      position: relative;
      overflow: hidden;
      background: #fff;
      /*f5f3f4*/
      border: 1px #dedede solid;
      text-align: center;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .product-main-image img,
    .veri-supply-<?php echo $this->uniqId; ?> .product-main-image_new img {
      /*width: 100%;*/
      max-width: 100%;
      width: auto;
      max-height: 100%;
      height: auto;
      display: inline-block;
      object-fit: cover;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .product-other-images {
      text-align: left;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .product-other-images img {
      width: 58px;
      height: auto;
      margin: 0 12px 12px 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .product-other-images a:hover img,
    .veri-supply-<?php echo $this->uniqId; ?> .product-other-images a.active img {
      box-shadow: 0 0 0 2px #c7ced5;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .product-quantity,
    .veri-supply-<?php echo $this->uniqId; ?> .product-quantity .input-group {
      width: 70px;
      float: left;
      margin-right: 20px;
      position: relative;
    }
    .veri-supply-<?php echo $this->uniqId; ?> table .product-quantity,
    .veri-supply-<?php echo $this->uniqId; ?> table .product-quantity .input-group {
      margin-right: 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .product-page-cart .btn {
      padding: 7px 20px;
      font-size: 12px;
      height: 38px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .product-quantity input.form-control {
      border: none;
      background: #edeff1 !important;
      font: 300 14px "Roboto", sans-serif;
      color: #647484;
      height: 32px;
      width: 50px;
      text-align: center;
      padding: 5px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .product-quantity input.form-control:focus {
      border: none;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .product-quantity .input-group-btn {
      position: static;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .product-quantity .btn {
      text-align: center;
      height: 18px !important;
      width: 18px;
      padding: 0 2px 0 1px !important;
      text-align: center;
      background: #edeff1;
      border-radius: 0 !important;
      font-size: 18px !important;
      line-height: 1 !important;
      color: #616b76;
      margin: 0 !important;
      position: absolute;
      right: 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .product-quantity .quantity-up {
      top: 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .product-quantity .quantity-down {
      bottom: 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .product-quantity .btn i {
      position: relative;
      top: -2px;
      left: 1px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .list-view-sorting {
      margin-bottom: 20px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .list-view-sorting .pull-right {
      margin-left: 30px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .list-view-sorting label {
      font-weight: normal;
      font-size: 12px;
      color: #8e9ca8;
      font-family: "Roboto Light", sans-serif;
      float: left;
      margin-right: 10px;
      position: relative;
      top: 6px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .list-view-sorting select {
      float: left;
      width: auto;
      height: 26px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .list-view-sorting a {
      background: #fff;
      color: #1cc3c9;
      display: inline-block;
      padding: 4px 6px;
      line-height: 1;
      margin-right: -3px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .product-page-content {
      width: 100%;
      overflow: hidden;
      padding-top: 60px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .product-page-content .tab-content {
      padding: 20px 15px;
      background: #fff;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .product-detail .catname {
      color: #00abe1;
      font-family: "Roboto Bold", sans-serif;
      font-size: 14px;
      margin: 0;
      padding: 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .bgc-white {
      background-color: #fff;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .company-header-bg {
      position: relative;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .profile-header {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      padding-bottom: 20px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .profile-header .img-wrap {
      background: #fff;
      border: 7px solid #fff;
      height: 200px;
      width: 200px;
      overflow: hidden;
      text-align: center;
      background-size: cover;
      border-radius: 50% !important;
      margin-bottom: 20px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .profile-header .descrip {
      text-transform: uppercase;
      color: #ffffff;
      font-family: "Roboto", sans-serif;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .profile-header .descrip .username {
      font-family: "Roboto", sans-serif;
      font-size: 30px;
      /*40px*/
      text-transform: none;
      color: #ff9800;
      margin: 0;
      padding: 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .profile-header .descrip .position {
      font-family: "Roboto", sans-serif;
      font-size: 17px;
      margin: 0;
      padding: 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .profile-header .descrip .desc {
      font-size: 14px;
      margin: 0;
      padding: 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .profile-header .descrip .desc.tt-n {
      text-transform: none !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .profile-header .descrip .desc strong {
      font-family: "Roboto Bold", sans-serif;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .profile_tab h1 {
      font-family: "Roboto", sans-serif;
      font-size: 20px;
      line-height: 20px;
      color: #00a9e4;
      /*ff9800*/
      text-transform: uppercase;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .profile_tab p {
      font-family: "Roboto", sans-serif;
      font-size: 14px;
      color: #3c3c3c;
      line-height: 22px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .profile_tab ul.list {
      padding: 0;
      list-style: none;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .profile_tab ul.list li {
      font-family: "Roboto", sans-serif;
      font-size: 14px;
      margin-bottom: 7px;
      color: #646464;
      text-transform: uppercase;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .profile_tab ul.list li .fa {
      font-size: 24px;
      line-height: 24px;
      color: #00a9e4;
      /*ff9800*/
      margin-right: 20px;
      width: 20px;
      text-align: center;
      vertical-align: middle;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .profile_tab ul li {
      font-family: "Roboto", sans-serif;
      font-size: 14px;
      margin-bottom: 10px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .fltr-control {
      padding: 0;
      list-style: none;
      margin: 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .fltr-control li {
      font-family: "Roboto Medium", sans-serif;
      font-size: 12px;
      display: inline-block;
      padding: 6px 0px;
      background: transparent;
      color: #000;
      text-transform: none;
      margin-right: 35px;
      cursor: pointer;
      border-bottom: 2px solid transparent;
      /*&
      &.active{
          border-color: $activeColor;
          color: $activeColor;
      }
      &:last-child{
          margin-right: 0;
      }*/
    }
    .veri-supply-<?php echo $this->uniqId; ?> .fltr-control li:hover,
    .veri-supply-<?php echo $this->uniqId; ?> .fltr-control li.active {
      border-color: #00a9e4;
      color: #00a9e4;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .fltr-control li:last-child {
      margin-right: 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .veriBottom1 {
      font-family: "Roboto Medium", sans-serif;
      font-size: 12px;
      text-transform: none;
      color: #fff;
      padding: 5px 15px;
      line-height: 13px;
      background: #ff9800;
      border-radius: 12px !important;
      display: inline-block;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .veriBottom1:hover,
    .veri-supply-<?php echo $this->uniqId; ?> .veriBottom1:active,
    .veri-supply-<?php echo $this->uniqId; ?> .veriBottom1:focus {
      color: #fff !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .veriBottom1.lg {
      font-size: 15px;
      padding: 15px 25px;
      border-radius: 22px !important;
      text-transform: uppercase;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .float-right {
      position: absolute;
      bottom: 20px;
      right: 30px;
      float: right;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .treeview-nodot li {
      background-image: none !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .treeview-nodot .hitarea,
    .veri-supply-<?php echo $this->uniqId; ?> .treeview-nodot li.lastCollapsable,
    .veri-supply-<?php echo $this->uniqId; ?> .treeview-nodot li.lastExpandable {
      background-image: url(../img/treeview/treeview-noline.gif) !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .itemqty_select {
      background: #fff;
      border: 1px solid #dbdbdb;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .itemqty_select input.form-control {
      border: none !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .itemqty_select .input-group-btn .btn {
      line-height: 0 !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .zoomContainer {
      z-index: 50;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .custom.nav-tabs {
      border-color: #fff;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .custom.nav-tabs > li.active > a, .veri-supply-<?php echo $this->uniqId; ?> .custom.nav-tabs > li.active > a:hover, .veri-supply-<?php echo $this->uniqId; ?> .custom.nav-tabs > li.active > a:focus {
      background: #fff;
      color: #00a9e4;
      font-family: "Roboto Bold", sans-serif;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .custom.nav-tabs > li.active {
      border-bottom: 2px solid #00a9e4;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .custom.nav-tabs > li > a, .veri-supply-<?php echo $this->uniqId; ?> .custom.nav-tabs > li > a:hover, .veri-supply-<?php echo $this->uniqId; ?> .custom.nav-tabs > li > a:focus {
      background: #fff;
      color: #000;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .btn-white {
      margin-top: 5px;
      background: #fff;
      color: #000;
      padding: 6px 5px !important;
      border: 1px solid #c8c8c8;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .btn-white:hover {
      background: #00a9e4;
      color: #fff;
      border: 1px solid #00a9e4;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .custom-title {
      font-family: "Roboto Regular", sans-serif;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .specific-title6 {
      color: #000;
      text-transform: uppercase;
      font-family: "Roboto Regular", sans-serif !important;
      font-size: 20px !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .specific-title7 {
      font-family: "Roboto Medium", sans-serif !important;
      color: #000;
      font-size: 14px !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .tt-title2 {
      color: #000;
      text-transform: uppercase;
      font-family: "Roboto Regular", sans-serif !important;
      font-size: 20px !important;
      text-align: center;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop {
      background: #fff;
      border: 1px solid #dcdcdc;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .img-section {
      /*background: #f9f9f9;*/
      background: #fff;
      width: 100%;
      text-align: center;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .img-section img {
      display: inline-block;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .prod-price .p-first {
      color: #000;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .starShow {
      background: url(/assets/emarket/img/starshow/orange_stars.png) no-repeat;
      height: 14px;
      width: 73px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop #BVInlineRatings .starShow {
      background: url(/assets/emarket/img/starshow/blue_stars.png) no-repeat;
      width: 90px;
      height: 14px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop #BVInlineRatings {
      padding: 0 0 5px;
      text-align: center;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop #BVInlineRatings .starRate {
      background: url(/assets/emarket/img/starshow/blue_empty_stars.png) no-repeat;
      width: 90px;
      height: 14px;
      padding: 0;
      margin: 0 auto;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .itemmore-group {
      font-family: "Roboto Light", sans-serif;
      font-size: 14px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .itemmore-group-label {
      color: #3c3c3c;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .det-one-prod {
      font-family: "Roboto Regular", sans-serif !important;
      font-size: 12px !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .more-actions {
      text-align: center;
      vertical-align: middle;
      position: relative;
      visibility: hidden;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .more-actions .action {
      width: 30px;
      height: 30px;
      background: #ebebeb;
      position: relative;
      left: 0;
      top: 0;
      border-radius: 100% !important;
      text-align: center;
      display: inline-block;
      margin: 2px 5px 0px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .more-actions .action span {
      color: #646464;
      font-size: 18px;
      line-height: 28px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .p-ribbon {
      position: absolute;
      z-index: 30;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .p-ribbon.right {
      right: -62px;
      top: -23px;
      -webkit-transform: rotate(45deg) translateY(100%);
      -moz-transform: rotate(45deg) translateY(100%);
      -ms-transform: rotate(45deg) translateY(100%);
      -o-transform: rotate(45deg) translateY(100%);
    }
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .p-ribbon.left {
      left: -62px;
      top: -23px;
      -webkit-transform: rotate(-45deg) translateY(100%);
      -moz-transform: rotate(-45deg) translateY(100%);
      -ms-transform: rotate(-45deg) translateY(100%);
      -o-transform: rotate(-45deg) translateY(100%);
    }
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .p-ribbon .product-badge {
      text-align: center;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .p-ribbon .product-badge span {
      font-family: "Roboto Light", sans-serif !important;
      color: #feffff;
      display: block;
      font-size: 12px;
      padding: 5px 13px;
      width: 120px;
      height: 38px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .p-ribbon .product-badge.new span {
      background: #30954b;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .p-ribbon .product-badge.discounted span {
      background: #00a9e4;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .p-ribbon .product-badge.finished span {
      background: #a50f0f;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .product_list_wrap.list_view .shop .title_wrap .title {
      font-family: "Roboto Medium", sans-serif;
      font-size: 14px;
      line-height: 16px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop .btn-secondary > i {
      color: #5a5a5a;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop input.form-control {
      color: #000;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop .button-style-sp1, .veri-supply-<?php echo $this->uniqId; ?> .shop .item-take-btn {
      background-color: #3c3c3c !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop .item-take-btn {
      padding: 6px 91px !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop .item-addwishlist-btn {
      background-color: #fff;
      border: 1px solid #3c3c3c;
      padding: 6px 35px;
      font-family: "Roboto Medium", sans-serif !important;
      font-size: 14px !important;
      color: #000;
      text-transform: none;
      border-radius: 25px !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop#item-detail-section, .veri-supply-<?php echo $this->uniqId; ?> .shop#item-detail-section #itemTabContent, .veri-supply-<?php echo $this->uniqId; ?> .shop#item-detail-section #itemTab li a {
      background: #fff !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop#item-detail-section #itemTab li a {
      color: #000 !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop#item-detail-section #itemTab li.active:active, .veri-supply-<?php echo $this->uniqId; ?> .shop#item-detail-section #itemTab li.active:hover, .veri-supply-<?php echo $this->uniqId; ?> .shop#item-detail-section #itemTab li.active:focus, .veri-supply-<?php echo $this->uniqId; ?> .shop#item-detail-section #itemTab li.active.active, .veri-supply-<?php echo $this->uniqId; ?> .shop#item-detail-section #itemTab li.active.selected {
      border-color: #000 !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop#item-detail-section #itemTab li a:active, .veri-supply-<?php echo $this->uniqId; ?> .shop#item-detail-section #itemTab li a:hover, .veri-supply-<?php echo $this->uniqId; ?> .shop#item-detail-section #itemTab li a:focus, .veri-supply-<?php echo $this->uniqId; ?> .shop#item-detail-section #itemTab li a.active, .veri-supply-<?php echo $this->uniqId; ?> .shop#item-detail-section #itemTab li a.selected {
      color: #333 !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop#item-detail-section #itemTab li.active {
      border-bottom: 2px solid #000 !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop#item-detail-section .item-code {
      color: #000;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop#item-detail-section .item-code .amount-value {
      font-family: "Roboto Bold", sans-serif;
      font-size: 15px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop#item-detail-section .label-value {
      font-size: 26px !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop#item-detail-section #itemTab {
      border-bottom: 1px solid #dcdcdc;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .product-detail .catname {
      color: #000;
      font-family: "Roboto Regular", sans-serif;
      font-size: 14px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .title-similar-item2 {
      font-family: "Roboto Medium", sans-serif !important;
      font-size: 14px !important;
      color: #000;
      margin: 0;
      text-transform: uppercase;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .footer {
      background: repeating-linear-gradient(44deg, #0f0f0f, #1a1a1a 1px, #272727 1px, #272727 1px);
      min-height: 240px;
      position: relative;
      z-index: 1;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .det-one-prod:active, .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .det-one-prod:hover,
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .det-one-prod:focus, .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .det-one-prod.active,
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .det-one-prod.selected {
      color: #000;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop:active .more-actions,
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop:focus .more-actions,
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop:hover .more-actions {
      visibility: visible;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .more-actions .action:hover {
      background: #c61932;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .single-product.shop .more-actions .action:hover span {
      color: #ffffff;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .poweredby {
      padding: 10px 0px;
      font-size: 12px;
      font-family: "Roboto Light", sans-serif;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .sectionTitle {
      color: #fff;
      text-transform: uppercase;
      font-family: "Roboto Regular", sans-serif;
      font-size: 12px;
      margin-bottom: 15px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> ul.section-list {
      list-style: none;
      margin: 0;
      padding: 0;
      font-family: "Roboto Light", sans-serif;
    }
    .veri-supply-<?php echo $this->uniqId; ?> ul.section-list li {
      font-size: 12px;
      padding: 0 0 5px 0px;
      margin: 0 0 5px 0;
    }
    .veri-supply-<?php echo $this->uniqId; ?> ul.section-list li a {
      text-decoration: none;
      color: #808080;
      font-family: "Roboto Light", sans-serif;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .copyright {
      color: #fff;
      font-family: "Roboto Regular", sans-serif;
      font-size: 12px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .copyright span {
      text-transform: uppercase;
    }
    .veri-supply-<?php echo $this->uniqId; ?> #news-section {
      background-color: #fff !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .news-post-cont {
      border-bottom: 1px solid #ddd;
      /*    box-shadow: inset 1px 5px 13px -6px;*/
      -webkit-box-shadow: 10px 0px 11px 0px rgba(0, 0, 0, 0.11);
      -moz-box-shadow: 10px 0px 11px 0px rgba(0, 0, 0, 0.11);
      box-shadow: 10px 0px 11px 0px rgba(0, 0, 0, 0.11);
    }
    .veri-supply-<?php echo $this->uniqId; ?> .socail_round .item {
      width: 38px;
      height: 38px;
      display: inline-block;
      border: 1px solid #958ca7;
      background-color: transparent;
      border-radius: 50% !important;
      text-align: center;
      line-height: 36px;
      color: #958ca7;
      margin-right: 3px;
      margin-bottom: 5px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .socail_round .item:active, .veri-supply-<?php echo $this->uniqId; ?> .socail_round .item:hover,
    .veri-supply-<?php echo $this->uniqId; ?> .socail_round .item:focus {
      background-color: #958ca7;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .socail_round .item:active i, .veri-supply-<?php echo $this->uniqId; ?> .socail_round .item:hover i,
    .veri-supply-<?php echo $this->uniqId; ?> .socail_round .item:focus i {
      color: #fff;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop#emarket-cart {
      background-color: #fff;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop .cart-step li.active {
      border-color: #00a9e4;
      color: #00a9e4;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop .cart-step li.active span {
      color: #00a9e4;
      font-size: 12px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop .cart-step li:after {
      width: 105px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop .single-cart {
      border: 1px solid #dcdcdc;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .shop#emarket-cart .order-e-btn.activeColor {
      background: #3c3c3c !important;
      border-color: #3c3c3c !important;
    }    
    .veri-supply-<?php echo $this->uniqId; ?> .rateit .rateit-range {
      background: url(../img/star_2.gif) !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit .rateit-hover {
      background: url(../img/star_2.gif) left -32px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit .rateit-selected {
      background: url(../img/star_2.gif) left -16px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit .rateit-selected-rtl {
      background-position: right -16px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit .rateit-preset {
      background: url(../img/star_2.gif) left -48px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit .rateit-preset-rtl {
      background: url(../img/star_2.gif) left -48px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.mdIcon .rateit-range {
      background: url(../img/star_md.gif) !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.mdIcon .rateit-hover {
      background: url(../img/star_md.gif) left -44px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.mdIcon .rateit-selected {
      background: url(../img/star_md.gif) left -22px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.mdIcon .rateit-selected-rtl {
      background-position: right -16px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.mdIcon .rateit-preset {
      background: url(../img/star_md.gif) left -66px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.mdIcon .rateit-preset-rtl {
      background: url(../img/star_md.gif) left -66px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.humanIcon .rateit-range {
      background: url(../img/human.gif) !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.humanIcon .rateit-hover {
      background: url(../img/human.gif) left -44px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.humanIcon .rateit-selected {
      background: url(../img/human.gif) left -22px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.humanIcon .rateit-selected-rtl {
      background-position: right -16px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.humanIcon .rateit-preset {
      background: url(../img/human.gif) left -66px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.humanIcon .rateit-preset-rtl {
      background: url(../img/human.gif) left -66px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.boxIcon .rateit-range {
      background: url(../img/ratebox.gif) !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.boxIcon .rateit-hover {
      background: url(../img/ratebox.gif) left -44px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.boxIcon .rateit-selected {
      background: url(../img/ratebox.gif) left -22px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.boxIcon .rateit-selected-rtl {
      background-position: right -16px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.boxIcon .rateit-preset {
      background: url(../img/ratebox.gif) left -66px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.boxIcon .rateit-preset-rtl {
      background: url(../img/ratebox.gif) left -66px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.locationIcon .rateit-range {
      background: url(../img/ratelocation.gif) !important;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.locationIcon .rateit-hover {
      background: url(../img/ratelocation.gif) left -44px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.locationIcon .rateit-selected {
      background: url(../img/ratelocation.gif) left -22px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.locationIcon .rateit-selected-rtl {
      background-position: right -16px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.locationIcon .rateit-preset {
      background: url(../img/ratelocation.gif) left -66px;
    }
    .veri-supply-<?php echo $this->uniqId; ?> .rateit.locationIcon .rateit-preset-rtl {
      background: url(../img/ratelocation.gif) left -66px;
    }    
</style>
<link href="assets/custom/addon/plugins/rateit/src/rateit.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
    $.getScript('assets/custom/addon/plugins/rateit/src/jquery.rateit.min.js', function() {
        $('.rateit').rateit();
    });    
    amChartMinify.init();

    AmCharts.makeChart("homeChart1", {
        "type": "serial",
        "dataProvider": generateChartData(),
        "categoryField": "date",
        "autoMargins": false,
        "marginLeft": 0,
        "marginRight": 0,
        "marginTop": 10,
        "marginBottom": 10,
        "startDuration": 1,
        "graphs": [{
                "bullet": "round",
                "bulletColor": "rgb(17, 128, 230)",
                "bulletBorderThickness": "0",
                'bulletSize': '2',
                "valueField": "price",
                "type": "smoothedLine",
                "fillAlphas": 0,
                "lineThickness": 1,
                "showBalloon": true,
                "lineColor": "rgb(17, 128, 230)",
                "balloonText": "[[category]] -р сар: <b>[[value]]₮</b>"
            }],
        "valueAxes": [{
                "gridAlpha": 0,
                "axisAlpha": 0
            }],
        "categoryAxis": {
            "startOnAxis": true,
            "gridAlpha": 0,
            "axisAlpha": 0
        }
    });

    AmCharts.makeChart("homeChart2", {
        "type": "serial",
        "dataProvider": generateChartData(5, 2),
        "categoryField": "date",
        "autoMargins": false,
        "marginLeft": 0,
        "marginRight": 0,
        "marginTop": 0,
        "marginBottom": 0,
        "columnWidth": 0.9,
        "columnSpacing": 0,
        "graphs": [{
                "valueField": "price",
                "type": "column",
                "fillAlphas": 1,
                "showBalloon": true,
                "lineColor": "rgb(138, 198, 232)",
                "balloonText": "[[category]] сард Баталсан: <b>[[value]]</b>"
            }, {
                "valueField": "price2",
                "type": "column",
                "fillAlphas": 1,
                "showBalloon": true,
                "lineColor": "rgb(27, 132, 231)",
                "balloonText": "[[category]] сард Буцаасан: <b>[[value]]</b>"
            }],
        "valueAxes": [{
                "gridAlpha": 0,
                "axisAlpha": 0
            }],
        "categoryAxis": {
            "gridAlpha": 0,
            "axisAlpha": 0
        }
    });

    AmCharts.makeChart("homeChart3", {
        "type": "serial",
        "dataProvider": generateChartData(),
        "categoryField": "date",
        "autoMargins": false,
        "marginLeft": 0,
        "marginRight": 0,
        "marginTop": 0,
        "marginBottom": 0,
        "columnWidth": 0.9,
        "columnSpacing": 0,
        "graphs": [{
                "bullet": "round",
                "bulletColor": "rgb(31, 146, 211)",
                "bulletBorderThickness": "0",
                'bulletSize': '2',
                "valueField": "price",
                "fillAlphas": 0.2,
                "type": "line",
                "showBalloon": true,
                "lineColor": "rgb(31, 146, 211)",
                "balloonText": "[[category]] сард Баталсан: <b>[[value]]</b>"
            }],
        "valueAxes": [{
                "gridAlpha": 0,
                "axisAlpha": 0
            }],
        "categoryAxis": {
            "startOnAxis": true,
            "gridAlpha": 0,
            "axisAlpha": 0
        }
    });

    AmCharts.makeChart("homeChart5", {
        "type": "pie",
        "adjustPrecision": true,
        "balloonText": "[[title]]-р сард<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
        "gradientType": "linear",
        "innerRadius": "60%",
        "labelText": "",
        "accessibleLabel": "",
        "pullOutRadius": "5%",
        "colors": [
            "#337AB7",
            "#DC3545",
            "#22BF07",
            "#F49917",
            "#6F42C1",
            "#E83E8C"
        ],
        "groupedAlpha": 0,
        "marginBottom": 0,
        "marginTop": 0,
        "startEffect": "elastic",
        "titleField": "date",
        "valueField": "price",
        "handDrawScatter": 0,
        "handDrawThickness": 0,
        "percentPrecision": 0,
        "precision": 0,
        "allLabels": [],
        "balloon": {
            "animationDuration": 0,
            "fadeOutDuration": 0,
            "offsetX": 0,
            "offsetY": 1
        },
        "legend": {
            "enabled": true,
            "autoMargins": false,
            "bottom": 0,
            "equalWidths": false,
            "marginLeft": 5,
            "marginRight": 0,
            "markerLabelGap": 5,
            "markerSize": 5,
            "markerType": "triangleLeft",
            "maxColumns": -2,
            "position": "right",
            "rollOverColor": "#231C1C",
            "spacing": 0,
            "tabIndex": -2,
            "top": 0,
            "valueText": "",
            "verticalGap": 5,
        },
        // "titles": [],
        "dataProvider": generateChartData(4)
    });

    function generateChartData(count = 5, dcount = 1) {
        var chartData = [];
        for (var i = 1; i <= count; i++) {

            if (dcount == 1) {
                chartData.push({
                    date: i,
                    price: Math.floor(Math.random() * 10)
                });
            } else if (dcount == 2) {
                chartData.push({
                    date: i,
                    price: Math.floor(Math.random() * 10),
                    price2: Math.floor(Math.random() * 10),
                });
            }


        }
        return chartData;
    }
</script>