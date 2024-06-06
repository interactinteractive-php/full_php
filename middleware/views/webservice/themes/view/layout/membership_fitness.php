<link href="<?php echo autoVersion('middleware/assets/theme/layout/membership_fitness/css/style.css'); ?>" rel="stylesheet"/>

<div class="hranket-bp w-100" id="membership-template-<?php echo $this->uniqId; ?>" style="margin-left: 15px">
    <div class="bg-white">
<!--        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title"></div>
        </div>-->
        <div class="card-body">
            
            <table class="hranket-table">
                <tbody>
                    <tr>
                        <td style="width: 55px">
                            <img src="middleware/assets/theme/layout/membership_fitness/img/general-blue.png">
                        </td>
                        <td>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    Код
                                    <span class="hranket-general-value">
                                        <?php echo $this->fillParamData['code']; ?>
                                    </span>
                                </div>
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
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>    
    </div>
    
    <div class="bg-white">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title">Гэрээ</div>
        </div>
        <div class="card-body">
            
            <table class="hranket-table">
                <tbody>
                <?php
                if (isset($this->fillParamData['fitcontractview_dv'])) {
                    foreach ($this->fillParamData['fitcontractview_dv'] as $row) {
                ?>                    
                    <tr>
                        <td style="width: 55px">
                            <img src="middleware/assets/theme/layout/membership_fitness/img/education.png">
                        </td>
                        <td>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    Гэрээний нэр
                                    <span class="hranket-general-value">
                                        <?php echo $row['contractname']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Гэрээний дугаар
                                    <span class="hranket-general-value">
                                        <?php echo $row['contractcode']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Эхлэх огноо
                                    <span class="hranket-general-value">
                                        <?php echo $row['startdate']; ?>
                                    </span>
                                </div>                                
                                <div class="col-md-3">
                                    Дуусах огноо
                                    <span class="hranket-general-value">
                                        <?php echo $row['enddate']; ?>
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
    
    <div class="bg-white">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title">Гэрийн хаяг</div>
        </div>
        <div class="card-body">
            
            <table class="hranket-table">
                <tbody>
                <?php
                if (isset($this->fillParamData['fitcustomeraddressview_dv'])) {
                ?>                    
                    <tr>
                        <td style="width: 55px">
                            <img src="middleware/assets/theme/layout/hranket/img/general-blue4.png">
                        </td>
                        <td>
                            <div class="row mb15">
                                <div class="col-md-3">
                                    Хот
                                    <span class="hranket-general-value">
                                        <?php echo $this->fillParamData['fitcustomeraddressview_dv']['cityname']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Дүүрэг
                                    <span class="hranket-general-value">
                                        <?php echo $this->fillParamData['fitcustomeraddressview_dv']['districtname']; ?>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    Гудамж
                                    <span class="hranket-general-value">
                                        <?php echo $this->fillParamData['fitcustomeraddressview_dv']['streetname']; ?>
                                    </span>
                                </div>                                
                                <div class="col-md-3">
                                    Хотхон
                                    <span class="hranket-general-value">
                                        <?php echo $this->fillParamData['fitcustomeraddressview_dv']['buildingname']; ?>
                                    </span>
                                </div>                                
                            </div>
                        </td>
                    </tr>
                <?php
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
        $("#membership-template-<?php echo $this->uniqId; ?>").closest(".vr-workspace-theme20").find(".right-side").css("margin-top", "-62px");
        $("#membership-template-<?php echo $this->uniqId; ?>").closest(".vr-workspace-theme20").find(".right-side").find("a.btn-icon-only").css("margin-top", "-40px");
    });
</script>