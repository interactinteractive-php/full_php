<link href="<?php echo autoVersion('middleware/assets/theme/layout/hranket/css/style.css'); ?>" rel="stylesheet"/>

<div class="hranket-bp w-100" id="membership-template-<?php echo $this->uniqId; ?>" style="margin-left: 15px">
    <div class="bg-white mt-3">
<!--        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold"></div>
        </div>-->
        <div class="card-body">
            
            <table class="hranket-table">
                <tbody>
                    <tr>
                        <td style="width: 55px">
                            <img src="middleware/assets/theme/layout/hranket/img/general-blue.png">
                        </td>
                        <td>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    Овог
                                    <span class="hranket-general-value">
                                        <?php echo $this->fillParamData['lastname']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Нэр
                                    <span class="hranket-general-value">
                                        <?php echo $this->fillParamData['firstname']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Регистрийн дугаар
                                    <span class="hranket-general-value">
                                        <?php echo $this->fillParamData['stateregnumber']; ?>
                                    </span>
                                </div>                                
                            </div>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    Төрсөн огноо
                                    <span class="hranket-general-value">
                                        <?php echo $this->fillParamData['dateofbirth']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Нас
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['age']); ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Хүйс
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['gender']); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    Төрсөн улс
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['birthofcountry']); ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Төрсөн аймаг, хот
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['birthofcity']); ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Төрсөн сум, дүүрэг
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['birthofdistrict']); ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php if(isset($this->fillParamData['maritalstatusname'])) { ?>
                        <tr>
                            <td>
                                <img src="middleware/assets/theme/layout/hranket/img/general-blue3.png">
                            </td>
                            <td>
                                <div class="row mb15">
                                    <div class="col-md-3">
                                        Гэрлэлтийн байдал
                                        <span class="hranket-general-value">
                                            <?php echo $this->fillParamData['maritalstatusname']; ?>
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        Ам бүлийн тоо
                                        <span class="hranket-general-value">
                                            <?php echo $this->fillParamData['nooffamilymember']; ?>
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        Хүүхдийн тоо
                                        <span class="hranket-general-value">
                                            <?php echo $this->fillParamData['noofchildren']; ?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if(isset($this->fillParamData['employeephone'])) { ?>
                    <tr>
                        <td>
                            <img src="middleware/assets/theme/layout/hranket/img/general-blue4.png">
                        </td>
                        <td>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    Ажлын утас
                                    <span class="hranket-general-value">
                                        <?php echo $this->fillParamData['employeephone']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Гар утас
                                    <span class="hranket-general-value">
                                        <?php echo $this->fillParamData['employeemobile']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    И-мейл
                                    <span class="hranket-general-value">
                                        <?php echo $this->fillParamData['employeeemail']; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    Шуудангийн хаяг
                                    <span class="hranket-general-value">
                                        <?php echo $this->fillParamData['postaddress']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Яаралтай холбоо барих хүн
                                    <span class="hranket-general-value">
                                        <?php echo $this->fillParamData['relationshipfirstname']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Утасны дугаар
                                    <span class="hranket-general-value">
                                        <?php echo $this->fillParamData['mobile']; ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>    
    </div>
    
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold">Гишүүнчлэл</div>
        </div>
        <div class="card-body">
            
            <?php
            if (isset($this->fillParamData['memloyaltyprogramlist'])) {
                foreach ($this->fillParamData['memloyaltyprogramlist'] as $homeAddress) {
            ?>
            <div class="hranket-address">
                <div style="width: 50px;float: left;">
                    <img src="middleware/assets/theme/layout/hranket/img/member.png">
                </div>
                <div class="hranket-address-info">
                    <strong><?php echo $homeAddress['loyaltyprogramname']; ?></strong><br />
                    <?php //echo $homeAddress['cityname']; ?><br />
                    <?php //echo $homeAddress['districtname']; ?><br />
                    <?php //echo $homeAddress['streetname']; ?>
                </div>
            </div>
            <?php
                }
            }
            ?>
            <div class="clearfix w-100"></div>
        </div>
    </div>  
    
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold">Яаралтай үед холбоо барих</div>
        </div>
        <div class="card-body">
            
            <table class="hranket-table">
                <tbody>
                <?php
                if (isset($this->fillParamData['meminformlist'])) {
                    foreach ($this->fillParamData['meminformlist'] as $row) {
                ?>                    
                    <tr>
                        <td style="width: 55px">
                            <img src="middleware/assets/theme/layout/hranket/img/general-blue4.png">
                        </td>
                        <td>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    Таны юу болох
                                    <span class="hranket-general-value">
                                        <?php echo $row['relationshipname']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Овог
                                    <span class="hranket-general-value">
                                        <?php echo $row['lastname']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Нэр
                                    <span class="hranket-general-value">
                                        <?php echo $row['firstname']; ?>
                                    </span>
                                </div>                                
                            </div>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    Төрсөн огноо
                                    <span class="hranket-general-value">
                                        <?php echo $row['birthdate']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Утас
                                    <span class="hranket-general-value">
                                        <?php echo $row['mobilenumber']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Утас2
                                    <span class="hranket-general-value">
                                        <?php echo $row['phonenumber']; ?>
                                    </span>
                                </div>                                
                            </div>
                        </td>
                    </tr>
                <?php
                    }
                }
                ?>                    
                </tbody>
            </table>

        </div>    
    </div>    
    
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold">Гэрийн хаяг</div>
        </div>
        <div class="card-body">
            
            <?php
            if (isset($this->fillParamData['memcustomeraddressget_dv'])) {
                foreach ($this->fillParamData['memcustomeraddressget_dv'] as $homeAddress) {
            ?>
            <div class="hranket-address">
                <div style="width: 50px;float: left;">
                    <img src="middleware/assets/theme/layout/hranket/img/address.png">
                </div>
                <div class="hranket-address-info">
                    <strong><?php echo $homeAddress['cityname']; ?></strong><br />
                    <?php echo $homeAddress['addresstype']; ?><br />
                </div>
            </div>
            <?php
                }
            }
            ?>
            <div class="clearfix w-100"></div>
        </div>
    </div>  
    
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold">Гэр бүлийн байдал</div>
        </div>
        <div class="card-body">
            
            <table class="hranket-table">
                <tbody>
                <?php
                if (isset($this->fillParamData['memfamilylist'])) {
                    foreach ($this->fillParamData['memfamilylist'] as $row) {
                ?>                    
                    <tr>
                        <td style="width: 55px">
                            <img src="middleware/assets/theme/layout/hranket/img/general-blue.png">
                        </td>
                        <td>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    Таны юу болох
                                    <span class="hranket-general-value">
                                        <?php echo $row['relationshipname']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Овог
                                    <span class="hranket-general-value">
                                        <?php echo $row['lastname']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Нэр
                                    <span class="hranket-general-value">
                                        <?php echo $row['firstname']; ?>
                                    </span>
                                </div>                                
                            </div>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    Регистр
                                    <span class="hranket-general-value">
                                        <?php echo $row['stateregnumber']; ?>
                                    </span>
                                </div>                                
                                <div class="col-md-3">
                                    Эрхэлдэг ажил
                                    <span class="hranket-general-value">
                                        <?php echo $row['typename']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Утас
                                    <span class="hranket-general-value">
                                        <?php echo $row['phonenumber']; ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php
                    }
                }
                ?>                    
                </tbody>
            </table>

        </div>    
    </div>
    
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold">Сошиал мэдээлэл</div>
        </div>
        <div class="card-body">
            
            <table class="hranket-table">
                <tbody>
                <?php
                if (isset($this->fillParamData['memsociallist'])) { ?>
                    <tr>
                        <td style="width: 55px">
                        </td>
                        <td>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    Төрөл
                                </div>
                                <div class="col-md-3">
                                    Хаяг
                                </div>
                                <div class="col-md-3">
                                    Тайлбар
                                </div>                                
                            </div>
                        </td>
                    </tr>                    
                <?php
                    foreach ($this->fillParamData['memsociallist'] as $row) {
                        
                        $socialIcon = "middleware/assets/theme/layout/hranket/img/internet.png";
                        if (strtolower($row['contactsubtypename']) == 'facebook') {
                            $socialIcon = "middleware/assets/theme/layout/hranket/img/facebook.png";
                        } elseif (strtolower($row['contactsubtypename']) == 'google') {
                            $socialIcon = "middleware/assets/theme/layout/hranket/img/google.png";
                        } elseif (strtolower($row['contactsubtypename']) == 'twitter') {
                            $socialIcon = "middleware/assets/theme/layout/hranket/img/twitter.png";
                        }
                ?>                    
                    <tr>
                        <td style="width: 55px" class="pt5">
                            <img src="<?php echo $socialIcon; ?>">
                        </td>
                        <td>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    <span class="hranket-general-value">
                                        <?php echo $row['contactsubtypename']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <span class="hranket-general-value">
                                        <?php echo $row['contactname']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <span class="hranket-general-value">
                                        <?php echo $row['contactdescription']; ?>
                                    </span>
                                </div>                                
                            </div>
                        </td>
                    </tr>
                <?php
                    }
                }
                ?>                    
                </tbody>
            </table>

        </div>    
    </div>
    
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold">Боловсрол</div>
        </div>
        <div class="card-body">
            
            <table class="hranket-table">
                <tbody>                    
                <?php
                if (isset($this->fillParamData['memeducation_list'])) { ?>
                    <tr>
                        <td style="width: 55px">
                        </td>
                        <td>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    Боловсролын зэрэг / Он
                                </div>
                                <div class="col-md-2">
                                    Хамгаалсан улс
                                </div>
                                <div class="col-md-2">
                                    Зэрэг
                                </div>                                
                                <div class="col-md-2">
                                    Сургууль
                                </div>                                
                                <div class="col-md-2">
                                    Гэрчилгээний дугаар
                                </div>                                              
                            </div>
                        </td>
                    </tr>
                <?php
                    foreach ($this->fillParamData['memeducation_list'] as $row) {
                ?>                    
                    <tr>
                        <td style="width: 55px" class="pt5">
                            <img src="middleware/assets/theme/layout/hranket/img/education.png">
                        </td>
                        <td>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    <span class="hranket-general-value">
                                        <p><?php echo $row['date']; ?></p>
                                        <p><?php echo $row['levelname']; ?></p>
                                    </span>
                                </div>
                                <div class="col-md-2">
                                    <span class="hranket-general-value">
                                        <?php echo $row['countryname']; ?>
                                    </span>
                                </div>
                                <div class="col-md-2">
                                    <span class="hranket-general-value">
                                        <?php echo $row['degreename']; ?>
                                    </span>
                                </div>                                
                                <div class="col-md-2">
                                    <span class="hranket-general-value">
                                        <?php echo $row['schoolname']; ?>
                                    </span>
                                </div>     
                                <div class="col-md-2">
                                    <span class="hranket-general-value">
                                        <?php echo $row['certificatenumber']; ?>
                                    </span>
                                </div>                                                                         
                            </div>
                        </td>
                    </tr>
                <?php
                    }
                }
                ?>                    
                </tbody>
            </table>

        </div>    
    </div>
    
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold">Гадаад хэлний мэдлэг</div>
        </div>
        <div class="card-body">
            
            <?php            
            if (isset($this->fillParamData['memlanguage_list'])) { ?>
                <table class="">
                    <thead>
                        <tr>
                            <th style="width: 260px; font-weight: normal; text-align: center">Хэл</th>
                            <th style="width: 200px;font-weight: normal; text-align: center">Ерөнхий түвшин</th>
                            <th style="width:200px;font-weight: normal; text-align: center">Унших</th>
                            <th style="width: 200px;font-weight: normal; text-align: center">Бичих</th>
                            <th style="width: 200px;font-weight: normal; text-align: center">Сонсох</th>
                            <th style="width: 200px;font-weight: normal; text-align: center">Ярих</th>
                        </tr>
                    </thead>           
                    <tbody>    
                <?php foreach ($this->fillParamData['memlanguage_list'] as $familyPeople2) { ?>
                <tr>
                    <td>
                        <strong><?php echo $familyPeople2['languagename']; ?></strong>
                    </td>
                    <td>
                    <div class="">
                        <?php
                        $color1 = $color2 = $color3 = $color4 = $color5 = 'color: #ccc;';
                        $star1 = $star2 = $star3 = $star4 = $star5 = 'icon-star-empty3';
                        if ($familyPeople2['fluencyvalue'] == 1) {
                            $color1 = 'color: orange;';
                            $star1 = 'icon-star-full2';
                        } elseif ($familyPeople2['fluencyvalue'] == 2) {
                            $color1 = $color2 = 'color: orange;';
                            $star1 = $star2 = 'icon-star-full2';
                        } elseif ($familyPeople2['fluencyvalue'] == 3) {
                            $color1 = $color2 = $color3 = 'color: orange;';
                            $star1 = $star2 = $star3 = 'icon-star-full2';
                        } elseif ($familyPeople2['fluencyvalue'] == 4) {
                            $color1 = $color2 = $color3 = $color4 = 'color: orange;';
                            $star1 = $star2 = $star3 = $star4 = 'icon-star-full2';
                        } else {
                            $color1 = $color2 = $color3 = $color4 = $color5 = 'color: orange;';
                            $star1 = $star2 = $star3 = $star4 = $star5 = 'icon-star-full2';
                        }
                        ?>
                        <div style="text-align:center;height:auto;" class="datagrid-cell datagrid-cell-c1-scorepoint"><ul class="nav navbar-nav dv-star-rating d-flex flex-row"><li data-id="1" title="1"><i class="<?php echo $star1; ?>" style="<?php echo $color1; ?> cursor: pointer;"></i></li><li data-id="2" title="2"><i class="<?php echo $star2; ?>" style="<?php echo $color2; ?> cursor: pointer;"></i></li><li data-id="3" title="3"><i class="<?php echo $star3; ?>" style="<?php echo $color3; ?> cursor: pointer;"></i></li><li data-id="4" title="4"><i class="<?php echo $star4; ?>" style="<?php echo $color4; ?> cursor: pointer;"></i></li><li data-id="5" title="5"><i class="<?php echo $star5; ?>" style="<?php echo $color5; ?> cursor: pointer;"></i></li></ul></div>
                    </div>
                    </td>
                    <td>
                    <div class="">
                        <?php
                        $color1 = $color2 = $color3 = $color4 = $color5 = 'color: #ccc;';
                        $star1 = $star2 = $star3 = $star4 = $star5 = 'icon-star-empty3';
                        if ($familyPeople2['readvalue'] == 1) {
                            $color1 = 'color: orange;';
                            $star1 = 'icon-star-full2';
                        } elseif ($familyPeople2['readvalue'] == 2) {
                            $color1 = $color2 = 'color: orange;';
                            $star1 = $star2 = 'icon-star-full2';
                        } elseif ($familyPeople2['readvalue'] == 3) {
                            $color1 = $color2 = $color3 = 'color: orange;';
                            $star1 = $star2 = $star3 = 'icon-star-full2';
                        } elseif ($familyPeople2['readvalue'] == 4) {
                            $color1 = $color2 = $color3 = $color4 = 'color: orange;';
                            $star1 = $star2 = $star3 = $star4 = 'icon-star-full2';
                        } else {
                            $color1 = $color2 = $color3 = $color4 = $color5 = 'color: orange;';
                            $star1 = $star2 = $star3 = $star4 = $star5 = 'icon-star-full2';
                        }
                        ?>
                        <div style="text-align:center;height:auto;" class="datagrid-cell datagrid-cell-c1-scorepoint"><ul class="nav navbar-nav dv-star-rating d-flex flex-row"><li data-id="1" title="1"><i class="<?php echo $star1; ?>" style="<?php echo $color1; ?> cursor: pointer;"></i></li><li data-id="2" title="2"><i class="<?php echo $star2; ?>" style="<?php echo $color2; ?> cursor: pointer;"></i></li><li data-id="3" title="3"><i class="<?php echo $star3; ?>" style="<?php echo $color3; ?> cursor: pointer;"></i></li><li data-id="4" title="4"><i class="<?php echo $star4; ?>" style="<?php echo $color4; ?> cursor: pointer;"></i></li><li data-id="5" title="5"><i class="<?php echo $star5; ?>" style="<?php echo $color5; ?> cursor: pointer;"></i></li></ul></div>
                    </div>
                    </td>
                    <td>
                    <div class="">
                        <?php
                        $color1 = $color2 = $color3 = $color4 = $color5 = 'color: #ccc;';
                        $star1 = $star2 = $star3 = $star4 = $star5 = 'icon-star-empty3';
                        if ($familyPeople2['writevalue'] == 1) {
                            $color1 = 'color: orange;';
                            $star1 = 'icon-star-full2';
                        } elseif ($familyPeople2['writevalue'] == 2) {
                            $color1 = $color2 = 'color: orange;';
                            $star1 = $star2 = 'icon-star-full2';
                        } elseif ($familyPeople2['writevalue'] == 3) {
                            $color1 = $color2 = $color3 = 'color: orange;';
                            $star1 = $star2 = $star3 = 'icon-star-full2';
                        } elseif ($familyPeople2['writevalue'] == 4) {
                            $color1 = $color2 = $color3 = $color4 = 'color: orange;';
                            $star1 = $star2 = $star3 = $star4 = 'icon-star-full2';
                        } else {
                            $color1 = $color2 = $color3 = $color4 = $color5 = 'color: orange;';
                            $star1 = $star2 = $star3 = $star4 = $star5 = 'icon-star-full2';
                        }
                        ?>
                        <div style="text-align:center;height:auto;" class="datagrid-cell datagrid-cell-c1-scorepoint"><ul class="nav navbar-nav dv-star-rating d-flex flex-row"><li data-id="1" title="1"><i class="<?php echo $star1; ?>" style="<?php echo $color1; ?> cursor: pointer;"></i></li><li data-id="2" title="2"><i class="<?php echo $star2; ?>" style="<?php echo $color2; ?> cursor: pointer;"></i></li><li data-id="3" title="3"><i class="<?php echo $star3; ?>" style="<?php echo $color3; ?> cursor: pointer;"></i></li><li data-id="4" title="4"><i class="<?php echo $star4; ?>" style="<?php echo $color4; ?> cursor: pointer;"></i></li><li data-id="5" title="5"><i class="<?php echo $star5; ?>" style="<?php echo $color5; ?> cursor: pointer;"></i></li></ul></div>
                    </div>
                    </td>
                    <td>
                    <div class="">
                        <?php
                        $color1 = $color2 = $color3 = $color4 = $color5 = 'color: #ccc;';
                        $star1 = $star2 = $star3 = $star4 = $star5 = 'icon-star-empty3';
                        if ($familyPeople2['listenvalue'] == 1) {
                            $color1 = 'color: orange;';
                            $star1 = 'icon-star-full2';
                        } elseif ($familyPeople2['listenvalue'] == 2) {
                            $color1 = $color2 = 'color: orange;';
                            $star1 = $star2 = 'icon-star-full2';
                        } elseif ($familyPeople2['listenvalue'] == 3) {
                            $color1 = $color2 = $color3 = 'color: orange;';
                            $star1 = $star2 = $star3 = 'icon-star-full2';
                        } elseif ($familyPeople2['listenvalue'] == 4) {
                            $color1 = $color2 = $color3 = $color4 = 'color: orange;';
                            $star1 = $star2 = $star3 = $star4 = 'icon-star-full2';
                        } else {
                            $color1 = $color2 = $color3 = $color4 = $color5 = 'color: orange;';
                            $star1 = $star2 = $star3 = $star4 = $star5 = 'icon-star-full2';
                        }
                        ?>
                        <div style="text-align:center;height:auto;" class="datagrid-cell datagrid-cell-c1-scorepoint"><ul class="nav navbar-nav dv-star-rating d-flex flex-row"><li data-id="1" title="1"><i class="<?php echo $star1; ?>" style="<?php echo $color1; ?> cursor: pointer;"></i></li><li data-id="2" title="2"><i class="<?php echo $star2; ?>" style="<?php echo $color2; ?> cursor: pointer;"></i></li><li data-id="3" title="3"><i class="<?php echo $star3; ?>" style="<?php echo $color3; ?> cursor: pointer;"></i></li><li data-id="4" title="4"><i class="<?php echo $star4; ?>" style="<?php echo $color4; ?> cursor: pointer;"></i></li><li data-id="5" title="5"><i class="<?php echo $star5; ?>" style="<?php echo $color5; ?> cursor: pointer;"></i></li></ul></div>
                    </div>
                    </td>
                    <td>
                    <div class="">
                        <?php
                        $color1 = $color2 = $color3 = $color4 = $color5 = 'color: #ccc;';
                        $star1 = $star2 = $star3 = $star4 = $star5 = 'icon-star-empty3';
                        if ($familyPeople2['speakvalue'] == 1) {
                            $color1 = 'color: orange;';
                            $star1 = 'icon-star-full2';
                        } elseif ($familyPeople2['speakvalue'] == 2) {
                            $color1 = $color2 = 'color: orange;';
                            $star1 = $star2 = 'icon-star-full2';
                        } elseif ($familyPeople2['speakvalue'] == 3) {
                            $color1 = $color2 = $color3 = 'color: orange;';
                            $star1 = $star2 = $star3 = 'icon-star-full2';
                        } elseif ($familyPeople2['speakvalue'] == 4) {
                            $color1 = $color2 = $color3 = $color4 = 'color: orange;';
                            $star1 = $star2 = $star3 = $star4 = 'icon-star-full2';
                        } else {
                            $color1 = $color2 = $color3 = $color4 = $color5 = 'color: orange;';
                            $star1 = $star2 = $star3 = $star4 = $star5 = 'icon-star-full2';
                        }
                        ?>
                        <div style="text-align:center;height:auto;" class="datagrid-cell datagrid-cell-c1-scorepoint"><ul class="nav navbar-nav dv-star-rating d-flex flex-row"><li data-id="1" title="1"><i class="<?php echo $star1; ?>" style="<?php echo $color1; ?> cursor: pointer;"></i></li><li data-id="2" title="2"><i class="<?php echo $star2; ?>" style="<?php echo $color2; ?> cursor: pointer;"></i></li><li data-id="3" title="3"><i class="<?php echo $star3; ?>" style="<?php echo $color3; ?> cursor: pointer;"></i></li><li data-id="4" title="4"><i class="<?php echo $star4; ?>" style="<?php echo $color4; ?> cursor: pointer;"></i></li><li data-id="5" title="5"><i class="<?php echo $star5; ?>" style="<?php echo $color5; ?> cursor: pointer;"></i></li></ul></div>
                    </div>
                    </td>
                </tr>
            <?php
                } ?>
                </tbody>
                </table>
            <?php }
            ?>
            <div class="clearfix w-100"></div>
        </div>
    </div> 
    
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold">Ажлын туршлага</div>
        </div>
        <div class="card-body">
            
            <table class="hranket-table">
                <tbody>
                <?php
                if (isset($this->fillParamData['memworkexpriencelist'])) {
                    foreach ($this->fillParamData['memworkexpriencelist'] as $row) {
                ?>                    
                    <tr>
                        <td style="width: 55px">
                            <img src="middleware/assets/theme/layout/hranket/img/general-blue.png">
                        </td>
                        <td>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    Эрхэлдэг ажил
                                    <span class="hranket-general-value">
                                        <?php echo $row['typecode']; ?>
                                    </span>
                                </div>                                
                                <div class="col-md-3">
                                    Байгууллагын нэр
                                    <span class="hranket-general-value">
                                        <?php echo $row['companyname']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Албан тушаал
                                    <span class="hranket-general-value">
                                        <?php echo $row['positionname']; ?>
                                    </span>
                                </div>                                
                                <div class="col-md-3">
                                    Ажилласан жил
                                    <span class="hranket-general-value">
                                        <?php echo $row['workingyear']; ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php
                    }
                }
                ?>                    
                </tbody>
            </table>

        </div>    
    </div>    
    
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold">Шагнал</div>
        </div>
        <div class="card-body">
            
            <table class="hranket-table">
                <tbody>
                <?php
                if (isset($this->fillParamData['memrewardlist'])) {
                    foreach ($this->fillParamData['memrewardlist'] as $row) {
                ?>                    
                    <tr>
                        <td style="width: 55px">
                            <img src="middleware/assets/theme/layout/hranket/img/general-blue.png">
                        </td>
                        <td>
                            <div class="row mb15">
                                <div class="col-md-4">
                                    Шагнуулсан он
                                    <span class="hranket-general-value">
                                        <?php echo $row['rewarddate']; ?>
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    Шагналын нэр
                                    <span class="hranket-general-value">
                                        <?php echo $row['rewardtype']; ?>
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    Хаанаас
                                    <span class="hranket-general-value">
                                        <?php echo $row['organizationname']; ?>
                                    </span>
                                </div>                                
                            </div>
                        </td>
                    </tr>
                <?php
                    }
                }
                ?>                    
                </tbody>
            </table>

        </div>    
    </div>    
    
    <div class="card light shadow bg-white mb0 pb0">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold">Шилжилт хөдөлгөөний түүх</div><br><br>
            <div class="card-title font-weight-bold">Байрлал солигдсон</div>
        </div>
        <div class="card-body">
            
            <table class="hranket-table">
                <tbody>
                <?php
                if (isset($this->fillParamData['membertransposition_dv'])) {
                    foreach ($this->fillParamData['membertransposition_dv'] as $row) {
                ?>                    
                    <tr>
                        <td style="width: 55px">
                            <img src="middleware/assets/theme/layout/hranket/img/general-blue2.png">
                        </td>
                        <td>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    Бүртгэлийн огноо
                                    <span class="hranket-general-value">
                                        <?php echo $row['createddate']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Хуучин харъяалал
                                    <span class="hranket-general-value">
                                        <p class="mb0"><?php echo $row['cityname']; ?></p>
                                        <p class="mb0"><?php echo $row['districtname']; ?></p>
                                        <p class="mb0"><?php echo $row['text1']; ?></p>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Шинэ харъяалал
                                    <span class="hranket-general-value">
                                        <p class="mb0"><?php echo $row['newcityname']; ?></p>
                                        <p class="mb0"><?php echo $row['newdistrictname']; ?></p>
                                        <p class="mb0"><?php echo $row['text2']; ?></p>
                                    </span>
                                </div>                                
                                <div class="col-md-3">
                                    Дэлгэрэнгүй хаяг
                                    <span class="hranket-general-value">
                                        <p><?php echo $row['booktypename']; ?></p>
                                    </span>
                                </div>                                
                            </div>
                        </td>
                    </tr>
                <?php
                    }
                }
                ?>                    
                </tbody>
            </table>

        </div>    
    </div>       
    
    <div class="card light shadow bg-white mb0 pb0">
            <div class="card-header card-header-no-padding header-elements-inline">
                <div class="card-title font-weight-bold">Төрийн албан шилжсэн</div>
            </div>
            <div class="card-body">
                <table class="hranket-table">
                    <tbody>
                        <?php
                        if (isset($this->fillParamData['civiltransposition_dv'])) {
                            foreach ($this->fillParamData['civiltransposition_dv'] as $row) {
                        ?>                    
                        <tr>
                            <td style="width: 55px">
                                <img src="middleware/assets/theme/layout/hranket/img/general-blue2.png">
                            </td>
                            <td>
                                <div class="row mb15">
                                    <div class="col-md-3">
                                        Шилжсэн огноо
                                        <span class="hranket-general-value">
                                            <?php echo $row['bookdate']; ?>
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        Харъяа нэгж
                                        <span class="hranket-general-value">
                                            <p class="mb0"><?php echo $row['cityname']; ?></p>
                                            <p class="mb0"><?php echo $row['districtname']; ?></p>
                                            <p class="mb0"><?php echo $row['departmentname']; ?></p>
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        Байгууллага
                                        <span class="hranket-general-value">
                                            <p class="mb0"><?php echo $row['text6']; ?></p>    
                                            <p class="mb0"><?php echo $row['text7']; ?></p>    
                                        </span>
                                    </div>                                
                                    <div class="col-md-3">
                                        Албан тушаал
                                        <span class="hranket-general-value">
                                            <p><?php echo $row['civilbooktype']; ?></p>    
                                        </span>
                                    </div>                                
                                </div>
                            </td>
                        </tr>          
                        <?php
                            }
                        }
                        ?>                  
                    </tbody>
                </table>
            </div>  
    </div>       
    
    <div class="bg-white mt-3">
            <div class="card-header card-header-no-padding header-elements-inline">
                <div class="card-title font-weight-bold">Ёс зүйн зөрчил</div>
            </div>
            <div class="card-body">
                <table class="hranket-table">
                    <tbody>
                        <?php
                        if (isset($this->fillParamData['ethictransposition_dv'])) {
                            foreach ($this->fillParamData['ethictransposition_dv'] as $row) {
                        ?>                    
                        <tr>
                            <td style="width: 55px">
                                <img src="middleware/assets/theme/layout/hranket/img/general-blue2.png">
                            </td>
                            <td>
                                <div class="row mb15">
                                    <div class="col-md-3">
                                        Бүртгэсэн огноо
                                        <span class="hranket-general-value" style="font-weight: normal">
                                            <?php echo $row['text10']; ?>
                                            <p><strong>Төлөв:</strong> <?php echo $row['text9']; ?></p>                                            
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        Ёс зүйн төрөл
                                        <span class="hranket-general-value">
                                            <p class="mb0"><?php echo $row['ethiccityname']; ?></p>
                                            <p class="mb0"><?php echo $row['ethicdistrictname']; ?></p>
                                            <p class="mb0"><?php echo $row['ethicdepartmentname']; ?></p>
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        Эх сурвалж
                                        <span class="hranket-general-value">
                                            <p class="mb0"><?php echo $row['text8']; ?></p>                                                    
                                        </span>
                                    </div>                                
                                    <div class="col-md-3">
                                        Ёс зүйн асуудал
                                        <span class="hranket-general-value">
                                            <p><?php echo $row['ethicbooktype']; ?></p>    
                                        </span>
                                    </div>                                
                                </div>
                            </td>
                        </tr>          
                        <?php
                            }
                        }
                        ?>                  
                    </tbody>
                </table>
            </div>  
    </div>       

    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold">Харъяа нэгж</div>
        </div>
        <div class="card-body">
            
            <table class="hranket-table">
                <tbody>
                <?php
                if (isset($this->fillParamData['memdepartmentbyget'])) {
                    foreach ($this->fillParamData['memdepartmentbyget'] as $row) {
                ?>                    
                    <tr>
                        <td style="width: 55px">
                            <img src="middleware/assets/theme/layout/hranket/img/general-blue.png">
                        </td>
                        <td>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    Харъяа нэгж
                                    <span class="hranket-general-value">
                                        <?php echo $row['departmentname']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Эхэлсэн огноо
                                    <span class="hranket-general-value">
                                        <?php echo $row['workstartdate']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Дууссан огноо
                                    <span class="hranket-general-value">
                                        <?php echo $row['workenddate']; ?>
                                    </span>
                                </div>   
                                <div class="col-md-3">
                                    Үндсэн эсэх
                                    <span class="hranket-general-value">
                                        <?php echo $row['active']; ?>
                                    </span>
                                </div>                             
                            </div>
                        </td>
                    </tr>
                <?php
                    }
                }
                ?>                    
                </tbody>
            </table>

        </div>    
    </div>        

    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold">Харъяа бүлэг</div>
        </div>
        <div class="card-body">
            
            <table class="hranket-table">
                <tbody>
                <?php
                if (isset($this->fillParamData['memSegmentationByGet'])) {
                    foreach ($this->fillParamData['memSegmentationByGet'] as $row) {
                ?>                    
                    <tr>
                        <td style="width: 55px">
                            <img src="middleware/assets/theme/layout/hranket/img/general-blue.png">
                        </td>
                        <td>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    Харъяа бүлэг
                                    <span class="hranket-general-value">
                                        <?php echo $row['segmentationname']; ?>
                                    </span>
                                </div>                     
                            </div>
                        </td>
                    </tr>
                <?php
                    }
                }
                ?>                    
                </tbody>
            </table>

        </div>    
    </div>        
    
</div>    

<script>
    $(function(){
        $("#membership-template-<?php echo $this->uniqId; ?>").closest(".package-div").css("background-color", "inherit");
    });
</script>