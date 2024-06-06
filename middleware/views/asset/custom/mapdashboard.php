<?php
if (!$this->isAjax) {
?>
<div class="col-md-12 airs_dashboard">
    <div class="card light shadow card-multi-tab">
        <div class="card-header header-elements-inline tabbable-line d-none">
            <ul class="nav nav-tabs card-multi-tab-navtabs">
                <li data-type="layout">
                    <a href="#app_tab_mdassetcovid_dashboard" class="active" data-toggle="tab">
                        <i class="fa fa-caret-right"></i> <?php echo $this->title; ?><span>
                        <i class="fa fa-times-circle"></i></span></a>
                </li>
            </ul>
        </div>
        <div class="card-body pt0">
            <div class="tab-content card-multi-tab-content">
                <div class="tab-pane active" id="app_tab_mdassetcovid_dashboard">
                    <?php } ?>
                    <div class="content covid19 dashboard3" style="height: auto;">
                        <div class="container-fluid p-0">
                            <a class="list-icons-item d-none" id="fullscreen" data-action="fullscreen"></a>
                            <div class="col p-0">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card-header  d-none __d-flex justify-content-between">
                                            <h6 class="lh-5"><?php echo $this->lang->line('air_dashboard3_chart2'); ?></h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" id="mapSection">
                                                <div class="col-md-6 col-sm-6">
                                                    <select name="mapFilter" class="form-control" title="" tabindex="-1" style="width: 319px;margin-top: 10px;margin-left: 10px;float: left;color: #FFF;background: none;">
                                                        <option value="1599478648184">Томуу, амьсгалын замын өвчний амбулаторийн үзлэг</option>
                                                        <option value="1599545178676">Амьсгалын аппарат</option>
                                                    </select>
                                                    <div id="layoutMap" style="height: 600px;" ></div>
                                                    <div class="other_dtl w-100" style="color: #FFF;"></div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 right-dash" style="padding-left: 40px;">
                                                    <div class="layout-location-right pull-left mr-1" style="overflow: auto; max-height: 690px; ">
                                                        <div class="custom-title pb-5 parent__"><?php echo Lang::line('CV_MONGOL_001') ?></div>
                                                        <div class="card-ind brown parent__">
                                                            <div class="first">
                                                                <span class="title"><?php echo Lang::line('CV_ZUUWURLUGSUN_TOO') ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <span id="totalLicense">
                                                                    <?php echo Number::formatMoney(issetParamZero($this->covidDataFromMn['transportcasenim'])) ?>
                                                                </span>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind yellow parent__">
                                                            <div class="first">
                                                                <span class="title "><?php echo Lang::line('CV_EDGERELT') ?></span>
                                                            </div>
                                                            <div class="second yellow">
                                                                <a href="javascript:;" class="">
                                                                    <span id="totalCompany">
                                                                        <?php echo Number::formatMoney(issetParamZero($this->covidDataFromMn['healingnum'])) ?>
                                                                    </span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <ul class="list-unstyled list-inline license-ind parent__">
                                                            <li>
                                                                <i class="fa fa-certificate"></i>
                                                                <span class="title-lis"><?php echo Lang::line('CV_UNUUDUR_SHINJILGEE') . ': <span style="color: #FFF" class="longInit">' . issetParam($this->tdData[0]['value2']) . '</span>' .  ' /'. Date::currentDate('Y-m-d') . '/' ?></span>
                                                                <div class="cus-col-md first">
                                                                    <span class="title-lis child"><?php echo Lang::line('CV_TANDALT_SORITS') ?></span>
                                                                    <p class="amount-lis" id="totalLicenseArea">
                                                                        <?php echo Number::formatMoney(issetParam($this->tdData[0]['value'])) ?>
                                                                    </p>
                                                                </div>
                                                                <div class="cus-col-md">
                                                                    <span class="title-lis child"><?php echo Lang::line('CV_EMCH_AJILCHID') ?></span>
                                                                    <p class="amount-lis" id="totalLicenseOilArea">
                                                                        <?php echo Number::formatMoney(issetParam($this->tdData[0]['value3'])) ?>
                                                                    </p>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <i class="fa fa-map"></i>
                                                                <span class="title-lis"><?php echo Lang::line('CV_TUSGAALALTAND_BAIGAA') ?></span>
                                                                <p class="amount-lis ml-20" id="totalArea">
                                                                    <?php echo Number::formatMoney(issetParamZero($this->covidDataFromMn['novadaysnum'])) ?>
                                                                </p>
                                                            </li>
                                                            <li>
                                                                <i class="fa fa-certificate"></i>
                                                                <span class="title-lis"><?php echo Lang::line('CV_IRSEN_URGUDUL_GOMDOL') ?>: <?php echo Number::formatMoney(issetParamZero($this->covidDataFromMn['ehorondoobustah'])) ?></span>
                                                            </li>
                                                            <li class="ta-c">
                                                                <span class="triangle-chart"></span>
                                                                <div id="licenseAreaOfArea" class="pieLicenseAreaOfArea" style="overflow: hidden; text-align: left;">
                                                                </div>
                                                            </li>
                                                            <li class="">
                                                                <p class="link-o">
                                                                    <?php echo Lang::line('CV_SHINECHLESEN_OGNOO') ?>: 
                                                                    <span class="resourse"><?php echo Date::currentDate(); ?></span>
                                                                </p>
                                                            </li>
                                                        </ul>
                                                        <div class="custom-title pb-5 child__" style="display: none; font-size: 14px !important; padding-bottom: 15px !important; border-bottom: 1px solid #CCC; margin-bottom: 10px; ">Хүлээн авах, яаралтай тусламжийн тасгаар үйлчлүүлсэн иргэдийн тоо</div>
                                                        <div class="custom-title pb-5 child__" style="display: none; font-size: 14px !important; padding-bottom: 10px !important; "><?php echo 'Насанд хүрэгчид' ?></div>
                                                        <div class="card-ind brown child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo 'Яаралтай мэс засал' ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="1value">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind yellow child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo 'Зүрх судас, мэдрэл эмгэг' ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="1value1">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind brown child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo 'Архи' ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="1value2">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind yellow child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo 'Хоол' ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="1value3">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind brown child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo 'Авто осол' ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="1value4">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind yellow child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo 'Түлэгдэлт' ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="1value5">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind brown child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo 'Бусад гэмтэл' ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="1value6">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind yellow child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo 'Дотрын өвчний шалтгаант' ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="1value8">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind brown child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo 'Буцаасан' ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="1value9">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind yellow child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo 'Насанд хүрэгчдийн нас баралт' ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="1value10">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="custom-title pb-5 child__" style="display: none; font-size: 14px !important; padding-bottom: 10px !important;"><?php echo 'Хүүхэд' ?></div>
                                                        <div class="card-ind brown child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo 'Яаралтай мэс засал' ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="2value">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind yellow child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo 'Томуу, Томуу тоөс өвчин' ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="2value1">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind brown child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo 'Хордлого' ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="2value2">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind yellow child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo 'Авто осол' ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="2value3">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind brown child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo 'Түлэгдэлт' ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="2value4">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind yellow child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo 'Бусад гэмтэл' ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="2value5">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind brown child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo 'Хүүхдийн нас баралт' ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="2value6">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>
                                                    <div class="layout-location-right pull-left">
                                                        <div class="custom-title pb-5" id="currentLocationName"><?php echo Lang::line('CV_DELKHII_DAHIND') ?></div>
                                                        <div class="card-ind brown parent__">
                                                            <div class="first">
                                                                <span class="title"><?php echo Lang::line('CV_BATLAGDSAN_TOO') ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <span id="totalLicense">
                                                                    <?php echo Number::formatMoney(issetParam($this->covidData['total_cases'])) ?>
                                                                </span>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind yellow parent__">
                                                            <div class="first">
                                                                <span class="title "><?php echo Lang::line('CV_EDGERELT') ?></span>
                                                            </div>
                                                            <div class="second yellow">
                                                                <a href="javascript:;" class="">
                                                                    <span id="totalCompany">
                                                                        <?php echo Number::formatMoney(issetParam($this->covidData['total_recovered'])) ?>
                                                                    </span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind brown parent__">
                                                            <div class="first">
                                                                <span class="title "><?php echo Lang::line('CV_NAS_BARALT') ?></span>
                                                            </div>
                                                            <div class="second yellow">
                                                                <a href="javascript:;" class="">
                                                                    <span id="totalCompany">
                                                                        <?php echo Number::formatMoney(issetParam($this->covidData['total_deaths'])) ?>
                                                                    </span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind yellow parent__" style="background: #fb895d;">
                                                            <div class="first">
                                                                <span class="title "><?php echo Lang::line('CV_TOTAL_COUNTRIES') ?></span>
                                                            </div>
                                                            <div class="second yellow">
                                                                <a href="javascript:;" class="">
                                                                    <span id="totalCompany">
                                                                        <?php echo Number::formatMoney(issetParam($this->covidData['total_affected_countries'])) ?>
                                                                    </span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind yellow parent__">
                                                            <div class="first">
                                                                <span class="title "><?php echo Lang::line('CV_UNUUDUR_BURTGEGDSEN') ?></span>
                                                            </div>
                                                            <div class="second yellow">
                                                                <a href="javascript:;" class="">
                                                                    <span id="totalCompany">
                                                                        <?php echo Number::formatMoney(issetParam($this->covidData['total_new_cases_today'])) ?>
                                                                    </span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind yellow child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo Lang::line('CV_HUN_AMIIN_TOO') ?></span>
                                                            </div>
                                                            <div class="second yellow">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="value5">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind brown child__" style="display: none;" style="background: #fb895d;">
                                                            <div class="first">
                                                                <span class="title "><?php echo Lang::line('CV_EMCH') ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="value4">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind yellow child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title"><?php echo Lang::line('CV_TOMUU') ?></span>
                                                            </div>
                                                            <div class="second yellow">
                                                                <span class="numberInit" id="value">0</span>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind brown child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo Lang::line('CV_SUWILAGCH') ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="value3">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind yellow child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo Lang::line('CV_ERCHIMT_EMCH') ?></span>
                                                            </div>
                                                            <div class="second yellow">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="value6">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind brown child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo Lang::line('CV_ERCHIMT_SUWILAGCH') ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="value7">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind yellow child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo Lang::line('CV_1000_EMCH') ?></span>
                                                            </div>
                                                            <div class="second yellow">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="value8">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="card-ind brown child__" style="display: none;">
                                                            <div class="first">
                                                                <span class="title "><?php echo Lang::line('CV_1000_SUWILAGCH') ?></span>
                                                            </div>
                                                            <div class="second brown">
                                                                <a href="javascript:;" class="">
                                                                    <span class="numberInit" id="value9">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col p-0 mt30">
                                <div class="layout-section-title">
                                    <div class="layout-title-trapezoid outer">
                                        <div class="layout-title-trapezoid inner">
                                            <h3 class="layout-title titleTmpChart"><?php echo Lang::line('CV_HALDWARIIN_NUHTSUL_BAIDAL') ?></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="chartdiv"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col p-0 mt30">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="layout-section-title">
                                            <div class="layout-title-trapezoid outer">
                                                <div class="layout-title-trapezoid inner">
                                                    <h3 class="layout-title titleTmpChart"><?php echo Lang::line('CV_HILIIN_NEWTREH') ?> /<?php echo Date::currentDate('Y-m-d') ?>/</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="chartdiv2" style="width: 100%; height: 500px; "></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="layout-section-title">
                                            <div class="layout-title-trapezoid outer">
                                                <div class="layout-title-trapezoid inner">
                                                    <h3 class="layout-title titleTmpChart"><?php echo Lang::line('CV_ERUUL_MENDIIN_NUUTS') ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5"></div>
                                            <div class="col-md-4">
                                                <select name="stockType" id="stockType" class="form-control" title="" tabindex="-1" >
                                                    <option value="1599545157049">Эмийн нөөц</option>
                                                    <option value="1599545157569">Эмнэлгийн хэрэгслийн нөөц</option>
                                                    <option value="1599545158590">Хувийн хамгаалах хэрэгслийн нөөц</option>
                                                    <!--<option value="">Бүгд</option>-->
                                                    <?php /* if (isset($this->stockType)) {
                                                        echo '<option value="">Бүгд</option>';
                                                        foreach ($this->stockType as $key => $row) {
                                                            echo '<option value="'. $row['id'] .'">'. $row['name'] .'</option>';
                                                        }
                                                    } */?>
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <div id="chartdiv1" style="width: 100%; height: 440px; "></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col p-0 mt30">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="layout-section-title">
                                            <div class="layout-title-trapezoid outer">
                                                <div class="layout-title-trapezoid inner">
                                                    <h3 class="layout-title titleTmpChart"><?php echo Lang::line('CV_119_DUUDLAGE') ?> /<?php echo Date::currentDate('Y-m-d') ?>/</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5"></div>
                                            <div class="col-md-7 dataview" data-viewid="1599378870179767" data-tag="chartdiv3">
                                                <select name="filteryear" class="form-control" title="" tabindex="-1" style="width: 119px; float: left; ">
                                                    <option value="2019">2019 он</option>
                                                    <option selected="selected"  value="2020">2020 он</option>
                                                    <option value="2021">2021 он</option>
                                                    <option value="2022">2022 он</option>
                                                    <option value="2023">2023 он</option>
                                                </select>
                                                <select name="filtermonth" class="form-control" title="" tabindex="-1" style="width: 119px; margin-left: 10px; float: left; ">
                                                    <option value="<?php echo '1' ?>">1-р сар</option>
                                                    <option value="<?php echo '2' ?>">2-р сар</option>
                                                    <option value="<?php echo '3' ?>">3-р сар</option>
                                                    <option value="<?php echo '4' ?>">4-р сар</option>
                                                    <option value="<?php echo '5' ?>">5-р сар</option>
                                                    <option value="<?php echo '6' ?>">6-р сар</option>
                                                    <option value="<?php echo '7' ?>">7-р сар</option>
                                                    <option value="<?php echo '8' ?>">8-р сар</option>
                                                    <option selected="selected" value="<?php echo '9' ?>">9-р сар</option>
                                                    <option value="<?php echo '10' ?>">10-р сар</option>
                                                    <option value="<?php echo '11' ?>">11-р сар</option>
                                                    <option value="<?php echo '12' ?>">12-р сар</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <div id="chartdiv3" style="width: 100%; height: 500px; "></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="layout-section-title">
                                            <div class="layout-title-trapezoid outer">
                                                <div class="layout-title-trapezoid inner">
                                                    <h3 class="layout-title titleTmpChart"><?php echo Lang::line('CV_HULEEN_AWAH_UDRUUR') ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5"></div>
                                            <div class="col-md-7 dataview" data-viewid="1599474553144712" data-tag="chartdiv4">
                                                <select name="filteryear" class="form-control" title="" tabindex="-1" style="width: 119px; float: left; ">
                                                    <option value="2019">2019 он</option>
                                                    <option selected="selected"  value="2020">2020 он</option>
                                                    <option value="2021">2021 он</option>
                                                    <option value="2022">2022 он</option>
                                                    <option value="2023">2023 он</option>
                                                </select>
                                                <select name="filtermonth" class="form-control" title="" tabindex="-1" style="width: 119px; margin-left: 10px; float: left; ">
                                                    <option value="<?php echo '1' ?>">1-р сар</option>
                                                    <option value="<?php echo '2' ?>">2-р сар</option>
                                                    <option value="<?php echo '3' ?>">3-р сар</option>
                                                    <option value="<?php echo '4' ?>">4-р сар</option>
                                                    <option value="<?php echo '5' ?>">5-р сар</option>
                                                    <option value="<?php echo '6' ?>">6-р сар</option>
                                                    <option value="<?php echo '7' ?>">7-р сар</option>
                                                    <option value="<?php echo '8' ?>">8-р сар</option>
                                                    <option selected="selected" value="<?php echo '9' ?>">9-р сар</option>
                                                    <option value="<?php echo '10' ?>">10-р сар</option>
                                                    <option value="<?php echo '11' ?>">11-р сар</option>
                                                    <option value="<?php echo '12' ?>">12-р сар</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <div id="chartdiv4" style="width: 100%; height: 220px; "></div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="layout-section-title">
                                                    <div class="layout-title-trapezoid outer">
                                                        <div class="layout-title-trapezoid inner">
                                                            <h3 class="layout-title titleTmpChart"><?php echo Lang::line('CV_TOMUU_UDUR') ?></h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="chartdiv5" style="width: 100%; height: 220px; "></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col p-0 mt30">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="layout-section-title">
                                            <div class="layout-title-trapezoid outer">
                                                <div class="layout-title-trapezoid inner">
                                                    <h3 class="layout-title titleTmpChart"><?php echo Lang::line('CV_KHALAMJ') ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="chartdiv6" style="width: 100%; height: 500px; "></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="layout-section-title">
                                            <div class="layout-title-trapezoid outer">
                                                <div class="layout-title-trapezoid inner">
                                                    <h3 class="layout-title titleTmpChart"><?php echo Lang::line('CV_NEGDSEN') ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="chartdiv7" style="width: 100%; height: 500px; "></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col p-0 mt30">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="layout-section-title">
                                            <div class="layout-title-trapezoid outer">
                                                <div class="layout-title-trapezoid inner">
                                                    <h3 class="layout-title titleTmpChart"><?php echo Lang::line('Эрчимт эмчилгээ/Өдөр/') ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5"></div>
                                            <div class="col-md-7 dataview" data-viewid="1599543579041" data-tag="chartdiv8">
                                                <select name="filteryear" class="form-control" title="" tabindex="-1" style="width: 119px; float: left; ">
                                                    <option value="2019">2019 он</option>
                                                    <option selected="selected"  value="2020">2020 он</option>
                                                    <option value="2021">2021 он</option>
                                                    <option value="2022">2022 он</option>
                                                    <option value="2023">2023 он</option>
                                                </select>
                                                <select name="filtermonth" class="form-control" title="" tabindex="-1" style="width: 119px; margin-left: 10px; float: left; ">
                                                    <option value="<?php echo '1' ?>">1-р сар</option>
                                                    <option value="<?php echo '2' ?>">2-р сар</option>
                                                    <option value="<?php echo '3' ?>">3-р сар</option>
                                                    <option value="<?php echo '4' ?>">4-р сар</option>
                                                    <option value="<?php echo '5' ?>">5-р сар</option>
                                                    <option value="<?php echo '6' ?>">6-р сар</option>
                                                    <option value="<?php echo '7' ?>">7-р сар</option>
                                                    <option value="<?php echo '8' ?>">8-р сар</option>
                                                    <option selected="selected" value="<?php echo '9' ?>">9-р сар</option>
                                                    <option value="<?php echo '10' ?>">10-р сар</option>
                                                    <option value="<?php echo '11' ?>">11-р сар</option>
                                                    <option value="<?php echo '12' ?>">12-р сар</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <div id="chartdiv8" style="width: 100%; height: 500px; "></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="layout-section-title">
                                            <div class="layout-title-trapezoid outer">
                                                <div class="layout-title-trapezoid inner">
                                                    <h3 class="layout-title titleTmpChart"><?php echo Lang::line('Түргэн тусламжийн дуудлага/Өдөр/') ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5"></div>
                                            <div class="col-md-7 dataview" data-viewid="1599543579821" data-tag="chartdiv9">
                                                <select name="filteryear" class="form-control" title="" tabindex="-1" style="width: 119px; float: left; ">
                                                    <option value="2019">2019 он</option>
                                                    <option selected="selected"  value="2020">2020 он</option>
                                                    <option value="2021">2021 он</option>
                                                    <option value="2022">2022 он</option>
                                                    <option value="2023">2023 он</option>
                                                </select>
                                                <select name="filtermonth" class="form-control" title="" tabindex="-1" style="width: 119px; margin-left: 10px; float: left; ">
                                                    <option value="<?php echo '1' ?>">1-р сар</option>
                                                    <option value="<?php echo '2' ?>">2-р сар</option>
                                                    <option value="<?php echo '3' ?>">3-р сар</option>
                                                    <option value="<?php echo '4' ?>">4-р сар</option>
                                                    <option value="<?php echo '5' ?>">5-р сар</option>
                                                    <option value="<?php echo '6' ?>">6-р сар</option>
                                                    <option value="<?php echo '7' ?>">7-р сар</option>
                                                    <option value="<?php echo '8' ?>">8-р сар</option>
                                                    <option selected="selected" value="<?php echo '9' ?>">9-р сар</option>
                                                    <option value="<?php echo '10' ?>">10-р сар</option>
                                                    <option value="<?php echo '11' ?>">11-р сар</option>
                                                    <option value="<?php echo '12' ?>">12-р сар</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <div id="chartdiv9" style="width: 100%; height: 500px; "></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col p-0 mt30">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="layout-section-title">
                                            <div class="layout-title-trapezoid outer">
                                                <div class="layout-title-trapezoid inner">
                                                    <h3 class="layout-title titleTmpChart"><?php echo Lang::line('CV_INTERNET_HAILT') ?> /<?php echo Date::currentDate('Y-m-d') ?>/</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="TIMESERIES" style="width: 100%;"></div>
                                                <script type="text/javascript" src="https://ssl.gstatic.com/trends_nrtr/2213_RC01/embed_loader.js"></script> 
                                                <script type="text/javascript"> trends.embed.renderExploreWidget("TIMESERIES", {"comparisonItem":[{"keyword":"коронавирус","geo":"MN","time":"today 12-m"},{"keyword":"эрүүл мэндийн яам","geo":"MN","time":"today 12-m"},{"keyword":"УОК","geo":"MN","time":"today 12-m"},{"keyword":"халдвар","geo":"MN","time":"today 12-m"}],"category":0,"property":""}, {"exploreQuery":"geo=MN&q=%D0%BA%D0%BE%D1%80%D0%BE%D0%BD%D0%B0%D0%B2%D0%B8%D1%80%D1%83%D1%81,%D1%8D%D1%80%D2%AF%D2%AF%D0%BB%20%D0%BC%D1%8D%D0%BD%D0%B4%D0%B8%D0%B9%D0%BD%20%D1%8F%D0%B0%D0%BC,%D0%A3%D0%9E%D0%9A,%D1%85%D0%B0%D0%BB%D0%B4%D0%B2%D0%B0%D1%80&date=today 12-m,today 12-m,today 12-m,today 12-m","guestPath":"https://trends.google.com:443/trends/embed/"}); </script> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                if (!$this->isAjax) {
                ?>
            </div>
        </div>
    </div>
    </div>
</div>
<div>
    <a href="javascript:;" class="btn search-companies-btn search-company-bn" data-hasqtip="3" aria-describedby="qtip-3">
        <i class="fa fa-building-o"></i>
    </a>
    <div class="col-xs-12 col-sm-10 col-md-10 panelCompaniesList" id="panelCompaniesList">
        <a href="javascript:;" class="btn close-companies-panel">
            <i class="fa fa-close"></i>
        </a>
        <div class="portlet shadow">
            <div class="portlet-body form " id="tab-light-123"></div>
        </div>
    </div>
    
    <a href="javascript:;" class="btn search-companies-btn search-company-bn1" data-hasqtip="3" aria-describedby="qtip-3" style="margin-top: 182px !important">
        <i class="fa fa-building-o"></i>
    </a>
    <div class="col-xs-12 col-sm-10 col-md-10 panelCompaniesList" id="panelCompaniesList1">
        <a href="javascript:;" class="btn close-companies-panel2">
            <i class="fa fa-close"></i>
        </a>
        <div class="portlet shadow">
            <div class="portlet-body form " id="tab-light-1231"></div>
        </div>
    </div>
    
    <a href="javascript:;" class="btn search-companies-btn search-company-bn2" data-hasqtip="3" aria-describedby="qtip-3" style="margin-top: 259px !important">
        <i class="fa fa-building-o"></i>
    </a>
    <div class="col-xs-12 col-sm-10 col-md-10 panelCompaniesList" id="panelCompaniesList2">
        <a href="javascript:;" class="btn close-companies-panel3">
            <i class="fa fa-close"></i>
        </a>
        <div class="portlet shadow">
            <div class="portlet-body form " id="tab-light-1232"></div>
        </div>
    </div>
    
    <a href="javascript:;" class="btn search-companies-btn search-company-bn4" data-hasqtip="3" aria-describedby="qtip-3" style="margin-top: 297px !important">
        <i class="fa fa-building-o"></i>
    </a>
    <div class="col-xs-12 col-sm-10 col-md-10 panelCompaniesList" id="panelCompaniesList4">
        <a href="javascript:;" class="btn close-companies-panel4">
            <i class="fa fa-close"></i>
        </a>
        <div class="portlet shadow">
            <div class="portlet-body form " id="tab-light-1233"></div>
        </div>
    </div>
</div>
<?php
}
?>
<?php echo issetParam($this->covidcss) ?>
<?php echo issetParam($this->covidjs) ?>