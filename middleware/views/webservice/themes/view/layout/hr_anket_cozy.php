<link href="<?php echo autoVersion('middleware/assets/theme/layout/hranket/css/style.css'); ?>" rel="stylesheet"/>

<div class="hranket-bp w-100">
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold">Ерөнхий мэдээлэл</div>
        </div>
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
                                <?php echo Lang::lineDefault('BPG_0001', 'Ургийн овог'); ?>
                                <span class="hranket-general-value">
                                    <?php echo $this->fillParamData['familyname']; ?>
                                </span>
                            </div>
                            <div class="col-md-3">                                    
                                <?php echo Lang::lineDefault('BPG_0002', 'Төрсөн огноо'); ?>
                                <span class="hranket-general-value">
                                    <?php echo $this->fillParamData['dateofbirth']; ?>
                                </span>
                            </div>
                            <div class="col-md-3">
                                <?php echo Lang::lineDefault('BPG_0003', 'Хүйс'); ?>
                                <span class="hranket-general-value">
                                    <?php echo $this->fillParamData['gendername']; ?>
                                </span>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-md-3">                                    
                                <?php echo Lang::lineDefault('BPG_0004', 'Регистрийн дугаар'); ?>
                                <span class="hranket-general-value">
                                    <?php echo $this->fillParamData['stateregnumber']; ?>
                                </span>
                            </div>
                            <div class="col-md-3">
                                <?php echo Lang::lineDefault('BPG_0005', 'Утасны дугаар'); ?>
                                <span class="hranket-general-value">
                                    <?php echo $this->fillParamData['firstphone']; ?>
                                </span>
                            </div>
                            <div class="col-md-3">
                                <?php echo Lang::lineDefault('BPG_0006', 'И-мейл хаяг'); ?>
                                <span class="hranket-general-value">
                                    <?php echo $this->fillParamData['firstemail']; ?>
                                </span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <img src="middleware/assets/theme/layout/hranket/img/general-blue2.png">
                    </td>
                    <td>
                        <div class="row mb15">
                            <div class="col-md-3">
                                <?php echo Lang::lineDefault('BPG_0007', 'Иргэншил'); ?>
                                <span class="hranket-general-value">
                                    <?php echo $this->fillParamData['countryname']; ?>
                                </span>
                            </div>
                            <div class="col-md-3">
                                <?php echo Lang::lineDefault('BPG_0008', 'Төрсөн улс'); ?>
                                <span class="hranket-general-value">
                                    <?php echo $this->fillParamData['countryname']; ?>
                                </span>
                            </div>
                            <div class="col-md-3">
                                <?php echo Lang::lineDefault('BPG_0009', 'Яс үндэс'); ?>
                                <span class="hranket-general-value">
                                    <?php echo $this->fillParamData['originname']; ?>
                                </span>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-md-3">
                                <?php echo Lang::lineDefault('BPG_0010', 'Төрсөн хот/аймаг'); ?>
                                <span class="hranket-general-value">
                                    <?php echo $this->fillParamData['cityname']; ?>
                                </span>
                            </div>
                            <div class="col-md-3">                                    
                                <?php echo Lang::lineDefault('BPG_0011', 'Төрсөн сум/дүүрэг'); ?>
                                <span class="hranket-general-value">
                                    <?php echo $this->fillParamData['districtname']; ?>
                                </span>
                            </div>
                            <div class="col-md-3">                                    
                                <?php echo Lang::lineDefault('BPG_0012', 'Төрсөн баг/хороо'); ?>
                                <span class="hranket-general-value">
                                    <?php echo $this->fillParamData['streetname']; ?>
                                </span>
                            </div>                              
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>         
        </div>    
    </div>            
    
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold"><?php echo Lang::lineDefault('BPG_0013', 'Гэрийн хаяг'); ?></div>
        </div>
        <div class="card-body">
            
            <?php
            if (isset($this->fillParamData['homeaddressoftheperson'])) {
                foreach ($this->fillParamData['homeaddressoftheperson'] as $homeAddress) {
            ?>
            <div class="hranket-address">
                <div class="hranket-address-icon">
                    <img src="middleware/assets/theme/layout/hranket/img/address-blue.png">
                </div>
                <div class="hranket-address-info">
                    <strong><?php echo $homeAddress['address']; ?></strong><br />
                    <?php echo $homeAddress['addresstypename']; ?><br />
                    <?php echo $homeAddress['cityname']; ?><br />
                    <?php echo $homeAddress['districtname']; ?><br />
                    <?php echo $homeAddress['streetname']; ?>
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
            <div class="card-title font-weight-bold"><?php echo Lang::lineDefault('BPG_0014', 'Гэр бүлийн байдал'); ?></div>
        </div>
        <div class="card-body">
            
            <?php
            if (isset($this->fillParamData['familymembersoftheperson'])) {
                foreach ($this->fillParamData['familymembersoftheperson'] as $familyPeople) {
            ?>
            <div class="hranket-address">
                <div class="hranket-address-icon">
                    <img src="middleware/assets/theme/layout/hranket/img/person-orange.png">
                </div>
                <div class="hranket-address-info">
                    <strong>
                        <?php echo $familyPeople['lastname']; ?> овогтой <?php echo $familyPeople['firstname']; ?>
                    </strong> (<?php echo $familyPeople['relationshipname']; ?>)<br />
                    <?php echo $familyPeople['yearname']; ?><br />
                    <?php echo $familyPeople['mobile']; ?><br />
                    <?php echo $familyPeople['positionname']; ?>
                </div>
            </div>
            <?php
                }
            }
            ?>
            
            <div class="clearfix w-100"></div>
        </div>
    </div> 
    
    <?php if (isset($this->fillParamData['relativesoftheperson'])) { ?>
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold"><?php echo Lang::line('Гэр бүл'); ?></div>
        </div>
        <div class="card-body">
            
            <?php
                foreach ($this->fillParamData['relativesoftheperson'] as $familyPeople2) {
            ?>
            <div class="hranket-address">
                <div class="hranket-address-icon">
                    <img src="middleware/assets/theme/layout/hranket/img/person-blue.png">
                </div>
                <div class="hranket-address-info">
                    <strong>
                        <?php echo $familyPeople2['lastname']; ?> овогтой <?php echo $familyPeople2['firstname']; ?>
                    </strong> (<?php echo $familyPeople2['relationshipname']; ?>)<br />
                    <?php echo $familyPeople2['yearname']; ?><br />
                    <?php echo $familyPeople2['mobile']; ?><br />
                    <?php echo $familyPeople2['positionname']; ?>
                </div>
            </div>
            <?php
                }
            ?>
            
            <div class="clearfix w-100"></div>
        </div>
    </div> 
    <?php } ?>    
    
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold"><?php echo Lang::lineDefault('BPG_0015', 'Боловсрол'); ?></div>
        </div>
        <div class="card-body">
            
            <?php
            if (isset($this->fillParamData['postsecondaryeducation'])) {
            ?>
            <table class="hranket-education-table">
                <thead>
                    <tr>
                        <th style="width: 45px"></th>
                        <th style="width: 100px">Он</th>
                        <th style="width: 350px;">Сургуулийн нэр</th>
                        <th style="width: 160px;">Эзэмшсэн мэргэжил</th>
                        <th>Хаана сурсан</th>
                        <th>Дүн</th>
                    </tr>
                </thead>
                <tbody>
            <?php
                foreach ($this->fillParamData['postsecondaryeducation'] as $educationGeneral) {
//                    if ($educationGeneral['typecode'] == '2') {
//                        echo '<tr><td colspan="5">Их сургууль</td></tr>';
//                    }
            ?>
                <tr>
                    <td><img src="middleware/assets/theme/layout/hranket/img/dot-green.png"></td>
                    <td><?php echo $educationGeneral['startyear'].' - '.$educationGeneral['endyear']; ?></td>
                    <td><strong><?php echo $educationGeneral['schoolname']; ?></strong></td>
                    <td class="bold"><?php echo $educationGeneral['educationname']; ?></td>
                    <td><strong><?php echo $educationGeneral['countryname']; ?></strong></td>
                    <td class="bold"><?php echo $educationGeneral['grade']; ?></td>
                </tr>
            <?php
                }
            ?>
                </tbody>
            </table>
            <?php
            }
            ?>
            
            <div class="clearfix w-100"></div>
        </div>
    </div> 

    <?php
    if (isset($this->fillParamData['educationLevel'])) {
    ?>
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold"><?php echo Lang::lineDefault('BPG_0016', 'Мэргэжлийн зэрэг'); ?></div>
        </div>
        <div class="card-body">
            
            <table class="hranket-education-table">
                <thead>
                    <tr>
                        <th style="width: 45px"></th>
                        <th style="width: 100px">Огноо</th>
                        <th style="width: 350px;">Хаана</th>
                        <th style="width: 160px;">Чиглэл</th>
                    </tr>
                </thead>
                <tbody>
            <?php
                foreach ($this->fillParamData['educationLevel'] as $educationGeneral) {
            ?>
                <tr>
                    <td><img src="middleware/assets/theme/layout/hranket/img/dot-green.png"></td>
                    <td><?php echo $educationGeneral['startdate'].' - '.$educationGeneral['enddate']; ?></td>
                    <td><strong><?php echo $educationGeneral['schoolname']; ?></strong></td>
                    <td class="bold"><?php echo $educationGeneral['certificatenumber']; ?></td>
                </tr>
            <?php
                }
            ?>
                </tbody>
            </table>
            
            <div class="clearfix w-100"></div>
        </div>
    </div> 
    <?php
    }
    ?>
    
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold"><?php echo $this->lang->line('Хөдөлмөр эрхлэлт'); ?></div>
        </div>
        <div class="card-body">
            
            <?php
            if (isset($this->fillParamData['workexperienceoftheperson'])) {
            ?>
            <table class="hranket-education-table">
                <thead>
                    <tr>
                        <th style="width: 45px"></th>
                        <th style="width: 160px">Он</th>
                        <th style="width: 350px">Байгууллага /Салбар нэгж/</th>
                        <th style="width: 180px;">Албан тушаал</th>
                        <th style="width: 180px;">Гарсан шалтгаан</th>
                        <th style="width: 100px;">Ажилласан жил</th>
                    </tr>
                </thead>
                <tbody>
            <?php
                foreach ($this->fillParamData['workexperienceoftheperson'] as $workExperience) {
            ?>
                <tr>
                    <td><img src="middleware/assets/theme/layout/hranket/img/dot-green.png"></td>
                    <td><?php echo Date::formatter($workExperience['startdate'], 'Y/m/d').' - '.Date::formatter($workExperience['enddate'], 'Y/m/d'); ?></td>
                    <td class="bold"><?php echo $workExperience['organizationname'].' '.$workExperience['departmentname']; ?></td>
                    <td class="bold"><?php echo $workExperience['positionname']; ?></td>
                    <td class="bold"><?php echo issetParam($workExperience['resignreason']); ?></td>
                    <td class="bold"><?php echo issetParam($workExperience['']); ?></td>
                </tr>
            <?php
                }
            ?>
                </tbody>
            </table>
            <?php
            }
            ?>
            
            <div class="clearfix w-100"></div>
        </div>
    </div>
    
    <?php if (isset($this->fillParamData['rewardOfthePerson'])) { ?>
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold"><?php echo Lang::lineDefault('BPG_0017', 'Шагнал'); ?></div>
        </div>
        <div class="card-body">
            
            <table class="hranket-education-table">
                <thead>
                    <tr>
                        <th style="width: 45px"></th>
                        <th style="width: 100px">Он</th>
                        <th style="width: 380px;">Шагналын нэр</th>
                        <th style="width: 130px;">Хаанаас</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($this->fillParamData['rewardOfthePerson'] as $reward) {
                ?>
                    <tr>
                        <td><img src="middleware/assets/theme/layout/hranket/img/dot-green.png"></td>
                        <td><?php echo $reward['']; ?></td>
                        <td class="bold"><?php echo $reward['rewardtypename'].' '.$reward['rewardname']; ?></td>
                        <td class="bold"><?php echo $reward['organizationname']; ?></td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>    
            
            <div class="clearfix w-100"></div>
        </div>
    </div> 
    <?php } ?>
     
    <?php if (isset($this->fillParamData['languageOfThePerson']) && $this->fillParamData['languageOfThePerson']) { ?>
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold"><?php echo Lang::lineDefault('BPG_0018', 'Хэлний мэдлэг'); ?></div>
        </div>
        <div class="card-body">
            
            <?php
            if (isset($this->fillParamData['languageOfThePerson'])) {
                foreach ($this->fillParamData['languageOfThePerson'] as $language) {
            ?>
            <div class="hranket-progress-parent"> 
                <div class="hranket-progress-name">
                    <?php echo $language['languagename']; ?>
                </div>
                <div class="hranket-progress-circle p<?php echo $language['speakvalue']; ?>">
                    <span class="span2"><?php echo (int) $language['speakvalue'] * 100 / 5; ?>%</span>
                    <span><?php echo Lang::line('Ярих'); ?></span>
                    <div class="left-half-clipper">
                        <div class="first50-bar"></div>
                        <div class="value-bar"></div>
                    </div>
                </div>
                <div class="hranket-progress-circle p<?php echo $language['writevalue']; ?>">
                    <span class="span2"><?php echo (int) $language['writevalue'] * 100 / 5; ?>%</span>
                    <span><?php echo Lang::line('Бичих'); ?></span>
                    <div class="left-half-clipper">
                        <div class="first50-bar"></div>
                        <div class="value-bar"></div>
                    </div>
                </div>
                <div class="hranket-progress-circle p<?php echo $language['readvalue']; ?>">
                    <span class="span2"><?php echo (int) $language['readvalue'] * 100 / 5; ?>%</span>
                    <span><?php echo Lang::line('Унших'); ?></span>
                    <div class="left-half-clipper">
                        <div class="first50-bar"></div>
                        <div class="value-bar"></div>
                    </div>
                </div>
                <div class="hranket-progress-circle p<?php echo $language['listenvalue']; ?>">
                    <span class="span2"><?php echo (int) $language['listenvalue'] * 100 / 5; ?>%</span>
                    <span><?php echo Lang::line('Сонсох'); ?></span>
                    <div class="left-half-clipper">
                        <div class="first50-bar"></div>
                        <div class="value-bar"></div>
                    </div>
                </div>
            </div>
            <?php
                }
            }
            ?>
            
            <div class="clearfix w-100"></div>
        </div>
    </div>
    <?php } ?>
    
    <?php if (isset($this->fillParamData['hobbies'])) { ?>
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold"><?php echo Lang::lineDefault('BPG_0019', 'Сонирхол'); ?></div>
        </div>
        <div class="card-body">
            
            <?php
                foreach ($this->fillParamData['hobbies'] as $hobby) {
            ?>
            <div class="hranket-hobby w-100"> 
                <div class="hranket-hobby-name w-100 text-left">
                    <?php echo $hobby['hobbyname']; ?>
                </div>
            </div>
            <?php
                }
            ?>
            
            <div class="clearfix w-100"></div>
        </div>
    </div>
    <?php } ?>

    <?php if (isset($this->fillParamData['talentOfThePerson'])) { ?>
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold"><?php echo Lang::lineDefault('BPG_0020', 'Авьяас чадвар'); ?></div>
        </div>
        <div class="card-body">
            
            <?php
                foreach ($this->fillParamData['talentOfThePerson'] as $talent) {
            ?>
            <div class="hranket-talent">
                <div class="hranket-talent-name">
                    <?php echo $talent['talentname']; ?>
                </div>
            </div>
            <?php
                }
            ?>
            
            <div class="clearfix w-100"></div>
        </div>
    </div>    
    <?php } ?>
    
</div>    