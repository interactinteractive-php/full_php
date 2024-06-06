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
                                    <?php echo Lang::lineDefault('hranketvalue1', 'Ажилтны код'); ?>
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['employeecode']); ?>
                                    </span>
                                </div>
                                <div class="col-md-3">                                    
                                    <?php echo Lang::lineDefault('hranketvalue2', 'Ургийн овог'); ?>
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['urag']); ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <?php echo Lang::lineDefault('hranketvalue3', 'Төрсөн огноо'); ?>
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['dateofbirth']); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="row mb15">
                                <div class="col-md-3">                                    
                                    <?php echo Lang::lineDefault('hranketvalue4', 'Регистрийн дугаар'); ?>
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['stateregnumber']); ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <?php echo Lang::lineDefault('hranketgender', 'Хүйс'); ?>
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['gender']); ?>
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
                                    <?php echo Lang::lineDefault('hranketvalue5', 'Иргэншил'); ?>
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['countryname']); ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <?php echo Lang::lineDefault('hranketvalue6', 'Төрсөн улс'); ?>
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['birthcountryname']); ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <?php echo Lang::lineDefault('hranketvalue7', 'Яс үндэс'); ?>
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['originname']); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    <?php echo Lang::lineDefault('hranketvalue8', 'Төрсөн хот/аймаг'); ?>
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['cityname']); ?>
                                    </span>
                                </div>
                                <div class="col-md-3">                                    
                                    <?php echo Lang::lineDefault('hranketvalue9', 'Төрсөн сум/дүүрэг'); ?>
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['districtname']); ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <?php if ($this->fillParamData['socialorigin']) { ?>                                    
                                    <?php echo Lang::lineDefault('hranketvalue10', 'Нийгмийн гарал'); ?>
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['socialorigin']); ?>
                                    </span>
                                    <?php } ?>
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
                                        <?php echo Lang::lineDefault('hranketvalue11', 'Гэрлэлтийн байдал'); ?>
                                        <span class="hranket-general-value">
                                            <?php echo issetParam($this->fillParamData['maritalstatusname']); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <?php echo Lang::lineDefault('hranketvalue12', 'Ам бүлийн тоо'); ?>
                                        <span class="hranket-general-value">
                                            <?php echo issetParam($this->fillParamData['nooffamilymember']); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <?php echo Lang::lineDefault('hranketvalue13', 'Хүүхдийн тоо'); ?>
                                        <span class="hranket-general-value">
                                            <?php echo issetParam($this->fillParamData['noofchildren']); ?>
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
                                    <?php echo Lang::line('HR_ANKET_01'); ?>
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['employeemobile']) ? $this->fillParamData['employeemobile'] : issetParam($this->fillParamData['employeephone']); ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <?php echo Lang::line('HR_ANKET_02'); ?>
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['workphone']); ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <?php echo Lang::line('HR_ANKET_03'); ?>
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['employeeemail']); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    <?php echo Lang::line('HR_ANKET_04'); ?>
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['postaddress']); ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <?php echo Lang::line('HR_ANKET_05'); ?>
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['relationshipfirstname']); ?>
                                    </span>
                                </div>
                                <?php if ($this->fillParamData['mobile']) { ?>
                                <div class="col-md-3">
                                    <?php echo Lang::line('HR_ANKET_06'); ?>
                                    <span class="hranket-general-value">
                                        <?php echo issetParam($this->fillParamData['mobile']); ?>
                                    </span>
                                </div>
                                <?php } ?>
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
            <div class="card-title font-weight-bold"><?php echo Lang::lineDefault('hranketgroup1', 'Гэрийн хаяг'); ?></div>
        </div>
        <div class="card-body">
            
            <?php
            if (issetParamArray($this->fillParamData['hrmemphomeaddress'])) {
                foreach ($this->fillParamData['hrmemphomeaddress'] as $homeAddress) {
            ?>
            <div class="hranket-address">
                <div class="hranket-address-icon">
                    <img src="middleware/assets/theme/layout/hranket/img/address-blue.png">
                </div>
                <div class="hranket-address-info">
                    <strong><?php echo issetParam($homeAddress['address']); ?></strong><br />
                    <?php echo issetParam($homeAddress['addresstypename']); ?><br />
                    <?php echo issetParam($homeAddress['cityname']); ?><br />
                    <?php echo issetParam($homeAddress['districtname']); ?><br />
                    <?php echo issetParam($homeAddress['streetname']); ?>
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
            <div class="card-title font-weight-bold"><?php echo Lang::lineDefault('hranketgroup2', 'Гэр бүлийн байдал'); ?></div>
        </div>
        <div class="card-body">
            
            <?php
            if (issetParamArray($this->fillParamData['hrmempfamilypeople'])) {
                foreach ($this->fillParamData['hrmempfamilypeople'] as $familyPeople) {
            ?>
            <div class="hranket-address">
                <div class="hranket-address-icon">
                    <img src="middleware/assets/theme/layout/hranket/img/person-orange.png">
                </div>
                <div class="hranket-address-info">
                    <strong>
                        <?php echo $familyPeople['lastname']; ?> овогтой <?php echo $familyPeople['firstname']; ?>
                    </strong> (<?php echo issetParam($familyPeople['relationshipname']); ?>)<br />
                    <?php echo issetParam($familyPeople['yearname']); ?><br />
                    <?php echo issetParam($familyPeople['mobile']); ?><br />
                    <?php echo issetParam($familyPeople['positionname']); ?>
                </div>
            </div>
            <?php
                }
            }
            ?>
            
            <div class="clearfix w-100"></div>
        </div>
    </div> 
    
    <?php if (issetParamArray($this->fillParamData['hrmempfamilypeople2'])) { ?>
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold"><?php echo Lang::line('HR_ANKET_07'); ?></div>
        </div>
        <div class="card-body">
            
            <?php
                foreach ($this->fillParamData['hrmempfamilypeople2'] as $familyPeople2) {
            ?>
            <div class="hranket-address">
                <div class="hranket-address-icon">
                    <img src="middleware/assets/theme/layout/hranket/img/person-blue.png">
                </div>
                <div class="hranket-address-info">
                    <strong>
                        <?php echo issetParam($familyPeople2['lastname']); ?> овогтой <?php echo issetParam($familyPeople2['firstname']); ?>
                    </strong> (<?php echo issetParam($familyPeople2['relationshipname']); ?>)<br />
                    <?php echo issetParam($familyPeople2['yearname']); ?><br />
                    <?php echo issetParam($familyPeople2['mobile']); ?><br />
                    <?php echo issetParam($familyPeople2['positionname']); ?>
                </div>
            </div>
            <?php
                }
            ?>
            
            <div class="clearfix w-100"></div>
        </div>
    </div> 
    <?php } ?>
    
    <?php if (issetParamArray($this->fillParamData['hrmempfamilypeople3'])) { ?>
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold"><?php echo Lang::line('globe_employee_relative_family'); ?></div>
        </div>
        <div class="card-body">
            
            <?php
                foreach ($this->fillParamData['hrmempfamilypeople3'] as $familyPeople2) {
            ?>
            <div class="hranket-address">
                <div class="hranket-address-icon">
                    <img src="middleware/assets/theme/layout/hranket/img/person-blue.png">
                </div>
                <div class="hranket-address-info">
                    <strong>
                        <?php echo issetParam($familyPeople2['lastname']); ?> овогтой <?php echo issetParam($familyPeople2['firstname']); ?>
                    </strong> (<?php echo issetParam($familyPeople2['relationshipname']); ?>)<br />
                    <?php echo issetParam($familyPeople2['yearname']); ?><br />
                    <?php echo issetParam($familyPeople2['mobile']); ?><br />
                    <?php echo issetParam($familyPeople2['positionname']); ?><br />
                    <?php echo issetParam($familyPeople2['workname']); ?>
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
            <div class="card-title font-weight-bold"><?php echo Lang::lineDefault('hranketgroup3', 'Боловсрол'); ?></div>
        </div>
        <div class="card-body">
            
            <?php
            if (issetParamArray($this->fillParamData['hrmempeducation_general'])) {
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
                foreach ($this->fillParamData['hrmempeducation_general'] as $educationGeneral) {
//                    if ($educationGeneral['typecode'] == '1') {
//                        echo '<tr><td colspan="5">Ерөнхий боловсрол</td></tr>';
//                    }
            ?>
                <tr>
                    <td><img src="middleware/assets/theme/layout/hranket/img/dot-green.png"></td>
                    <td><?php echo issetParam($educationGeneral['startyear']).' - '.issetParam($educationGeneral['endyear']); ?></td>
                    <td><strong><?php echo issetParam($educationGeneral['schoolname']); ?></strong></td>
                    <td class="bold"><?php echo issetParam($educationGeneral['certificatenumber']); ?></td>
                    <td><strong><?php echo issetParam($educationGeneral['countryname']); ?></strong></td>
                    <td class="bold"><?php echo issetParam($educationGeneral['grade']); ?></td>
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
    if (issetParamArray($this->fillParamData['hrmempeducation_training'])) {
    ?>
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold"><?php echo Lang::lineDefault('hranketgroup11', 'Мэргэшил'); ?></div>
        </div>
        <div class="card-body">
            
            <table class="hranket-education-table">
                <thead>
                    <tr>
                        <th style="width: 45px"></th>
                        <th style="width: 100px">Он</th>
                        <th style="width: 350px;">Хаана</th>
                        <th style="width: 160px;">Чиглэл</th>
                    </tr>
                </thead>
                <tbody>
            <?php
                foreach ($this->fillParamData['hrmempeducation_training'] as $educationGeneral) {
            ?>
                <tr>
                    <td><img src="middleware/assets/theme/layout/hranket/img/dot-green.png"></td>
                    <td><?php echo issetParam($educationGeneral['startyear']).' - '.issetParam($educationGeneral['endyear']); ?></td>
                    <td><strong><?php echo issetParam($educationGeneral['schoolname']); ?></strong></td>
                    <td class="bold"><?php echo issetParam($educationGeneral['certificatenumber']); ?></td>
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
            <div class="card-title font-weight-bold"><?php echo $this->lang->line('MET_99990700'); ?></div>
        </div>
        <div class="card-body">
            
            <?php
            if (issetParamArray($this->fillParamData['hrmempworkexperience'])) {
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
                foreach ($this->fillParamData['hrmempworkexperience'] as $workExperience) {
            ?>
                <tr>
                    <td><img src="middleware/assets/theme/layout/hranket/img/dot-green.png"></td>
                    <td><?php 
                        echo (issetParam($workExperience['startdate']) ? Date::formatter($workExperience['startdate'], 'Y/m/d') .' - ' : '' ) ;
                        echo (issetParam($workExperience['enddate']) ? Date::formatter($workExperience['enddate'], 'Y/m/d') : ''); 
                    ?></td>
                    <td class="bold"><?php echo issetParam($workExperience['organizationname']) . ' '.issetParam($workExperience['departmentname']); ?></td>
                    <td class="bold"><?php echo issetParam($workExperience['positionname']); ?></td>
                    <td class="bold"><?php echo issetParam($workExperience['resignreason']); ?></td>
                    <td class="bold"><?php echo issetParam($workExperience['workedyear']); ?></td>
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
    
    <?php if (issetParamArray($this->fillParamData['hrmempreward'])) { ?>
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold"><?php echo Lang::lineDefault('hranketgroup4', 'Шагнал'); ?></div>
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
                foreach ($this->fillParamData['hrmempreward'] as $reward) {
                ?>
                    <tr>
                        <td><img src="middleware/assets/theme/layout/hranket/img/dot-green.png"></td>
                        <td><?php echo issetParam($reward['yearcode']); ?></td>
                        <td class="bold"><?php echo issetParam($reward['rewardtypename']).' '.issetParam($reward['rewardname']); ?></td>
                        <td class="bold"><?php echo issetParam($reward['organizationname']); ?></td>
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
    
    <?php if (issetParamArray($this->fillParamData['hrmemppunishment'])) { ?>
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold"><?php echo Lang::lineDefault('hranketgroup5', 'Сахилга'); ?></div>
        </div>
        <div class="card-body">
            
            <table class="hranket-punishment-table">
                <thead>
                    <tr>
                        <th style="width: 45px"></th>
                        <th style="width: 100px">Он</th>
                        <th style="width: 200px">Сахилгын төрөл</th>
                        <th style="width: 250px;">Зөрчлийн шалтгаан</th>
                        <th style="width: 130px;">Тушаалын дугаар</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($this->fillParamData['hrmemppunishment'] as $punishment) {
                    ?>
                    <tr>
                        <td><img src="middleware/assets/theme/layout/hranket/img/dot-red.png"></td>
                        <td><?php echo (issetParam($punishment['punishmentdate']) ? Date::formatter($punishment['punishmentdate'], 'Y/m/d') : ''); ?></td>
                        <td class="bold"><?php echo issetParam($punishment['punishmenttypename']); ?></td>
                        <td class="bold"><?php echo issetParam($punishment['punishment']); ?></td>
                        <td class="bold"><?php echo issetParam($punishment['rectorshipnumber']); ?></td>
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
    
    <?php if (issetParamArray($this->fillParamData['hrm_emp_language']) || issetParamArray($this->fillParamData['hrm_emp_language2'])) { ?>
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold"><?php echo Lang::lineDefault('hranketgroup6', 'Хэлний мэдлэг'); ?></div>
        </div>
        <div class="card-body">
            
            <?php
            if (issetParamArray($this->fillParamData['hrm_emp_language'])) {
                foreach ($this->fillParamData['hrm_emp_language'] as $language) {
            ?>
            <div class="hranket-progress-parent"> 
                <div class="hranket-progress-name">
                    <?php echo issetParamZero($language['languagename']); ?>
                </div>
                <div class="hranket-progress-circle p<?php echo issetParamZero($language['speakvalue']); ?>">
                    <span class="span2"><?php echo (int) issetParamZero($language['speakvalue']) * 100 / 5; ?>%</span>
                    <span><?php echo Lang::line('HR_ANKET_09'); ?></span>
                    <div class="left-half-clipper">
                        <div class="first50-bar"></div>
                        <div class="value-bar"></div>
                    </div>
                </div>
                <div class="hranket-progress-circle p<?php echo issetParamZero($language['writevalue']); ?>">
                    <span class="span2"><?php echo (int) issetParamZero($language['writevalue']) * 100 / 5; ?>%</span>
                    <span><?php echo Lang::line('HR_ANKET_10'); ?></span>
                    <div class="left-half-clipper">
                        <div class="first50-bar"></div>
                        <div class="value-bar"></div>
                    </div>
                </div>
                <div class="hranket-progress-circle p<?php echo issetParamZero($language['readvalue']); ?>">
                    <span class="span2"><?php echo (int) issetParamZero($language['readvalue']) * 100 / 5; ?>%</span>
                    <span><?php echo Lang::line('HR_ANKET_11'); ?></span>
                    <div class="left-half-clipper">
                        <div class="first50-bar"></div>
                        <div class="value-bar"></div>
                    </div>
                </div>
                <div class="hranket-progress-circle p<?php echo issetParamZero($language['listenvalue']); ?>">
                    <span class="span2"><?php echo (int) issetParamZero($language['listenvalue']) * 100 / 5; ?>%</span>
                    <span><?php echo Lang::line('HR_ANKET_12'); ?></span>
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
            
            <?php
            if (issetParamArray($this->fillParamData['hrm_emp_language2'])) {
                foreach ($this->fillParamData['hrm_emp_language2'] as $language) {
            ?>
            <div class="hranket-progress-parent"> 
                <div class="hranket-progress-name">
                    <?php echo issetParam($language['languagename']); ?>
                </div>
                <div class="hranket-progress-circle">
                    <span class="span2"><?php echo issetParam($language['fluencyname']); ?></span>
                    <span>Ерөнхий түвшин</span>
                    <div class="left-half-clipper">
                        <div class="first50-bar"></div>
                        <div class="value-bar"></div>
                    </div>
                </div>
                <div class="hranket-progress-circle">
                    <span class="span2"><?php echo issetParam($language['examname']); ?></span>
                    <span>Шалгалтын төрөл</span>
                    <div class="left-half-clipper">
                        <div class="first50-bar"></div>
                        <div class="value-bar"></div>
                    </div>
                </div>
                <div class="hranket-progress-circle">
                    <span class="span2"><?php echo issetParam($language['readvalue']); ?></span>
                    <span>Оноо</span>
                    <span>/<?php echo issetParam($language['stardate']); ?> - <?php echo issetParam($language['enddate']); ?>/</span>
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
    
    <?php if (issetParamArray($this->fillParamData['hrmemphobby'])) { ?>
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold"><?php echo Lang::lineDefault('hranketgroup7', 'Сонирхол'); ?></div>
        </div>
        <div class="card-body">
            
            <?php
                foreach ($this->fillParamData['hrmemphobby'] as $hobby) {
            ?>
            <div class="hranket-hobby w-100"> 
                <div class="hranket-hobby-name w-100 text-left">
                    <?php echo issetParamZero($hobby['hobbyname']); ?>
                </div>
            </div>
            <?php
                }
            ?>
            
            <div class="clearfix w-100"></div>
        </div>
    </div>
    <?php } ?>

    <?php if (issetParamArray($this->fillParamData['hrmemptalent'])) { ?>
    <div class="bg-white mt-3">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title font-weight-bold"><?php echo Lang::lineDefault('hranketgroup8', 'Авьяас чадвар'); ?></div>
        </div>
        <div class="card-body">
            
            <?php
                foreach ($this->fillParamData['hrmemptalent'] as $hobby) {
            ?>
            <div class="hranket-hobby">
                <div class="hranket-hobby-name">
                    <?php echo issetParamZero($hobby['talentname']); ?>
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