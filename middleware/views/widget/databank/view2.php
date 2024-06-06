<div class="" style="background: #f1f4f7; ">
    <div class="" style="position: fixed; left: -1000px;">
        <textarea class="js-copytextarea-<?php echo $this->uniqId ?>"></textarea>
    </div>
    <div class="xs-form main-action-meta bp-banner-container " id="bp-window-1529564333371" data-meta-type="process" data-process-id="1529564333371" data-bp-uniq-id="1529976174445972">
        <div class="col-md-12 center-sidebar">
            <div class="table-scrollable table-scrollable-borderless bp-header-param">
                <table class="table table-sm table-bordered bp-header-param customerInfo-table-<?php echo $this->uniqId ?>">
                    <tbody>
                        <tr>
                            <td colspan="3"><strong style="font-size: 20px;">Merchant 1</strong></td>
                        </tr>
                        <tr>                                            
                            <td class="text-left middle"  style="width: 28%;">
                                <span><?php echo $this->lang->line('Merchant name') ?>:</span>                                            
                            </td>
                            <td class="middle" style="width: 60%" colspan="" data-path="lastname"><?php echo isset($this->data['lastname']) ? $this->data['lastname'].' ' : '' ?><?php echo isset($this->data['firstname']) ? $this->data['firstname'] : '' ?></td>                                         
                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['firstname']) || isset($this->data['lastname']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                        </tr>
                        <tr>                                            
                            <td class="text-left middle"  style="width: 28%;">
                                <span><?php echo $this->lang->line('Register no') ?>:</span>                                            
                            </td>
                            <td class="middle" style="width: 60%" colspan="" data-path="registernumber"><?php echo isset($this->data['registernumber']) ? $this->data['registernumber'] : '' ?></td>                                         
                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['registernumber']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                        </tr>
                        <tr>                                            
                            <td class="text-left middle"  style="width: 28%;">
                                <span><?php echo $this->lang->line('Nationality') ?>:</span>                                            
                            </td>
                            <td class="middle" style="width: 60%" colspan="" data-path="nationality"><?php echo isset($this->data['nationality']) ? $this->data['nationality'] : '' ?></td>                                         
                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['nationality']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                        </tr>
                        <tr>                                            
                            <td class="text-left middle"  style="width: 28%;">
                                <span><?php echo $this->lang->line('Merchant phone') ?>:</span>                                            
                            </td>
                            <td class="middle" style="width: 60%" colspan="" data-path="address"><?php echo isset($this->data['phonenumber']) ? $this->data['phonenumber'] : '' ?></td>                                         
                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['phonenumber']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                        </tr>
                        <tr>
                            <td colspan="3"><strong style="font-size: 15px;">Address</strong></td>
                        </tr>
                        <tr>                                            
                            <td class="text-left middle"  style="width: 28%;">
                                <span><?php echo $this->lang->line('City') ?>:</span>                                            
                            </td>
                            <td class="middle" style="width: 60%" colspan="" data-path="cityname"><?php echo isset($this->data['cityname']) ? $this->data['cityname'] : '' ?></td>                                         
                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['cityname']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                        </tr>
                        <tr>                                            
                            <td class="text-left middle"  style="width: 28%;">
                                <span><?php echo $this->lang->line('District') ?>:</span>                                            
                            </td>
                            <td class="middle" style="width: 60%" colspan="" data-path="districtname"><?php echo isset($this->data['districtname']) ? $this->data['districtname'] : '' ?></td>                                         
                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['districtname']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                        </tr>
                        <tr>                                            
                            <td class="text-left middle"  style="width: 28%;">
                                <span><?php echo $this->lang->line('Street') ?>:</span>                                            
                            </td>
                            <td class="middle" style="width: 60%" colspan="" data-path="streetname"><?php echo isset($this->data['streetname']) ? $this->data['streetname'] : '' ?></td>                                         
                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['streetname']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                        </tr>
                        <tr>                                            
                            <td class="text-left middle"  style="width: 28%;">
                                <span><?php echo $this->lang->line('House') ?>:</span>                                            
                            </td>
                            <td class="middle" style="width: 60%" colspan="" data-path="apartmentdoor"><?php echo isset($this->data['apartmentdoor']) ? $this->data['apartmentdoor'] : '' ?></td>                                         
                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['apartmentdoor']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php

$index = 1;

if (isset($this->data['kubaccountterminalcount']) && !empty($this->data['kubaccountterminalcount'])) { 
    
    foreach ($this->data['kubaccountterminalcount'] as $row) { ?>
        <div class="" style="background: #0096886e; margin-top: 15px; ">
            <div class="xs-form main-action-meta bp-banner-container " id="bp-window-1529564333371" data-meta-type="process" data-process-id="1529564333371" data-bp-uniq-id="1529976174445972">
                <div class="col-md-12 center-sidebar">  
                    <div class="table-scrollable table-scrollable-borderless bp-header-param">
                        <table class="table table-sm table-bordered bp-header-param customerInfo-table-<?php echo $this->uniqId ?>">
                            <tbody>
                                <tr>
                                    <td colspan="3"><strong style="font-size: 20px;">Merchant 1_<?php echo $index ?></strong></td>
                                </tr>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('Merchant name') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['customername']) ? $row['customername'].' ' : '' ?><?php echo isset($row['firstname']) ? $row['firstname'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['customername']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                </tr>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('Merchant accountnumber') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['accountnumber']) ? $row['accountnumber'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['accountnumber']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                </tr>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('Merchant terminal count') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['terminalcount']) ? $row['terminalcount'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['terminalcount']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php 
        $index++;
    }
    
}

if (isset($this->data['posgetmerchantrequestlist']) && !empty($this->data['posgetmerchantrequestlist'])) {
    
    foreach ($this->data['posgetmerchantrequestlist'] as $key => $row) { ?>

        <div class="" style="background: #ff98004d; margin-top: 15px; ">
            <div class="xs-form main-action-meta bp-banner-container " id="bp-window-1529564333371" data-meta-type="process" data-process-id="1529564333371" data-bp-uniq-id="1529976174445972">
                <div class="col-md-12 center-sidebar">  
                    <div class="table-scrollable table-scrollable-borderless bp-header-param">
                        <table class="table table-sm table-bordered bp-header-param customerInfo-table-<?php echo $this->uniqId ?>">
                            <tbody>
                                <tr>
                                    <td colspan="3"><strong style="font-size: 20px;">Merchant 1_<?php echo $index ?></strong></td>
                                </tr>
                                <?php if (isset($row['merchantnumber'])) { ?>
                                
                                    <tr>                                            
                                        <td class="text-left middle"  style="width: 28%;">
                                            <span><?php echo $this->lang->line('Merchant number') ?>:</span>                                            
                                        </td>
                                        <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['merchantnumber']) ? $row['merchantnumber'] : '' ?></td>                                         
                                        <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['merchantnumber']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                    </tr>
                                    
                                <?php } ?>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('Merchant name') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['merchantname']) ? $row['merchantname'].' ' : '' ?><?php echo isset($row['firstname']) ? $row['firstname'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['merchantname']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                </tr>
                                    
                                <?php if (isset($row['standart']) && !$row['standart']) { ?>
                                
                                    <tr>                                            
                                        <td class="text-left middle"  style="width: 28%;">
                                            <span><?php echo $this->lang->line('Merchant full address') ?>:</span>                                            
                                        </td>
                                        <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['address']) ? $row['address'] : '' ?></td>                                         
                                        <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['address']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                    </tr>
                                    
                                <?php } ?>
                                <tr>
                                    <td colspan="3"><strong style="font-size: 15px;"><?php echo (isset($row['standart']) && !$row['standart']) ? 'Address' : 'Information' ?></strong></td>
                                </tr>
                                
                                <?php if (isset($row['cityname']) && $row['cityname']) { ?>

                                    <tr>                                            
                                        <td class="text-left middle"  style="width: 28%;">
                                            <span><?php echo $this->lang->line('City') ?>:</span>                                            
                                        </td>
                                        <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['cityname']) ? $row['cityname'] : '' ?></td>                                         
                                        <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['cityname']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                    </tr>
                                    
                                <?php } ?>
                                
                                <?php if (isset($row['districtname']) && $row['districtname']) { ?>

                                    <tr>                                            
                                        <td class="text-left middle"  style="width: 28%;">
                                            <span><?php echo $this->lang->line('District') ?>:</span>                                            
                                        </td>
                                        <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['districtname']) ? $row['districtname'] : '' ?></td>                                         
                                        <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['districtname']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                    </tr>
                                    
                                <?php } ?>
                                
                                <?php if (isset($row['streetname']) && $row['streetname']) { ?>

                                    <tr>                                            
                                        <td class="text-left middle"  style="width: 28%;">
                                            <span><?php echo $this->lang->line('Street') ?>:</span>                                            
                                        </td>
                                        <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['streetname']) ? $row['streetname'] : '' ?></td>                                         
                                        <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['streetname']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                    </tr>
                                    
                                <?php } ?>
                                    
                                <?php if (isset($row['apartmentdoor']) && $row['apartmentdoor']) { ?>

                                    <tr>                                            
                                        <td class="text-left middle"  style="width: 28%;">
                                            <span><?php echo $this->lang->line('House') ?>:</span>                                            
                                        </td>
                                        <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['apartmentdoor']) ? $row['apartmentdoor'] : '' ?></td>                                         
                                        <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['apartmentdoor']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                    </tr>
                                    
                                <?php } ?>
                                    
                                
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('Contact phone') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['contactphone']) ? $row['contactphone'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['contactphone']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                </tr>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('Account number') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['accountnumber']) ? $row['accountnumber'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['accountnumber']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                </tr>
                                
                                <?php if (isset($row['standart'])) { ?>

                                    <tr>                                            
                                        <td class="text-left middle"  style="width: 28%;">
                                            <span><?php echo $this->lang->line('Standart') ?>:</span>                                            
                                        </td>
                                        <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['standart']) ? $row['standart'] : '' ?></td>                                         
                                        <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['standart']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                    </tr>
                                    
                                <?php } ?>
                                
                                <?php if (isset($row['bank'])) { ?>

                                    <tr>                                            
                                        <td class="text-left middle"  style="width: 28%;">
                                            <span><?php echo $this->lang->line('Bank') ?>:</span>                                            
                                        </td>
                                        <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['bank']) ? $row['bank'] : '' ?></td>                                         
                                        <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['bank']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                    </tr>
                                    
                                <?php } ?>
                                
                                <?php if (isset($row['kub'])) { ?>

                                    <tr>                                            
                                        <td class="text-left middle"  style="width: 28%;">
                                            <span><?php echo $this->lang->line('Kub') ?>:</span>                                            
                                        </td>
                                        <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['kub']) ? $row['kub'] : '' ?></td>                                         
                                        <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['kub']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                    </tr>
                                    
                                <?php } ?>
                                    
                                <?php if (isset($row['terminalnumber']) && $row['terminalnumber']) { ?>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('Terminal number') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['terminalnumber']) ? $row['terminalnumber'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['terminalnumber']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                </tr>
                                <?php } ?>
                                
                                <?php if (isset($row['newmerchantname']) && $row['newmerchantname']) { ?>
                                
                                <tr>
                                    <td colspan="3"><strong style="font-size: 20px;">New Merchant 1_<?php echo $index ?></strong></td>
                                </tr>
                                
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('New Merchant name') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['newmerchantname']) ? $row['newmerchantname'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['terminalnumber']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                </tr>
                                <tr>
                                    <td colspan="3"><strong style="font-size: 15px;"><?php echo (isset($row['standart']) && !$row['standart']) ? 'Address' : 'New Information' ?></strong></td>
                                </tr>
                                <?php } ?>
                                
                                <?php if (isset($row['newcityname']) && $row['newcityname']) { ?>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('New City') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['newcityname']) ? $row['newcityname'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['newcityname']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                </tr>
                                <?php } ?>
                                
                                <?php if (isset($row['newdistrictname']) && $row['newdistrictname']) { ?>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('New District') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['newdistrictname']) ? $row['newdistrictname'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['newdistrictname']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                </tr>
                                <?php } ?>
                                
                                <?php if (isset($row['newstreetname']) && $row['newstreetname']) { ?>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('New Street') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['newstreetname']) ? $row['newstreetname'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['newstreetname']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                </tr>
                                <?php } ?>
                                
                                <?php if (isset($row['newapartmentdoor']) && $row['newapartmentdoor']) { ?>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('New House') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['newapartmentdoor']) ? $row['newapartmentdoor'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['newapartmentdoor']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php 
        if (isset($row['posgetterminalrequestlist']) && !empty($row['posgetterminalrequestlist'])) {
            
            $subIndex = 1;
            
            foreach ($row['posgetterminalrequestlist'] as $key => $subRow) { ?>
                <div class="" style="background: #8bc34a75; margin-top: 15px; ">
                    <div class="xs-form main-action-meta bp-banner-container " id="bp-window-1529564333371" data-meta-type="process" data-process-id="1529564333371" data-bp-uniq-id="1529976174445972">
                        <div class="col-md-12 center-sidebar">  
                            <div class="table-scrollable table-scrollable-borderless bp-header-param">
                                <table class="table table-sm table-bordered bp-header-param customerInfo-table-<?php echo $this->uniqId ?>">
                                    <tbody>
                                        <tr>
                                            <td colspan="3"><strong style="font-size: 20px;">Terminal 1_<?php echo $index.'_'.$subIndex ?></strong></td>
                                        </tr>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Pos name') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($subRow['posname']) ? $subRow['posname'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($subRow['posname']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Merchant category code') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($subRow['industrycode']) ? $subRow['industrycode'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($subRow['industrycode']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Merchant category name') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($subRow['industryname']) ? $subRow['industryname'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($subRow['industryname']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Agent code') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($subRow['departmentcode']) ? $subRow['departmentcode'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($subRow['departmentcode']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Agent') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($subRow['departmentname']) ? $subRow['departmentname'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($subRow['departmentname']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Is Season') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan=""><?php echo (isset($subRow['isseason']) && $subRow['isseason'] === '1') ? 'TIIM' : 'UGUI' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($subRow['isseason']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Is Cashback') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan=""><?php echo (isset($subRow['iscashback']) && $subRow['iscashback'] === '1') ? 'TIIM' : 'UGUI' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($subRow['iscashback']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            
            <?php 
            
            $subIndex++;
            }
        }
        
        if (isset($row['changeinfoterminallist']) && !empty($row['changeinfoterminallist'])) {
            
            $subIndex1 = 1;
            
            foreach ($row['changeinfoterminallist'] as $key => $subRow) { ?>
                <div class="" style="background: #8bc34a75; margin-top: 15px; ">
                    <div class="xs-form main-action-meta bp-banner-container " id="bp-window-1529564333371" data-meta-type="process" data-process-id="1529564333371" data-bp-uniq-id="1529976174445972">
                        <div class="col-md-12 center-sidebar">  
                            <div class="table-scrollable table-scrollable-borderless bp-header-param">
                                <table class="table table-sm table-bordered bp-header-param customerInfo-table-<?php echo $this->uniqId ?>">
                                    <tbody>
                                        <tr>
                                            <td colspan="3"><strong style="font-size: 20px;">Terminal 1_<?php echo $index.'_'.$subIndex1 ?></strong></td>
                                        </tr>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Pos name') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($subRow['posname']) ? $subRow['posname'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($subRow['posname']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Terminal number') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($subRow['terminalnumber']) ? $subRow['terminalnumber'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($subRow['terminalnumber']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            
            <?php 
            $subIndex1++;
            }
        }
        
        if (isset($row['cashback']) && !empty($row['cashback'])) {
            
            $subIndex1 = 1;
            
            foreach ($row['cashback'] as $key => $subRow) { ?>
                <div class="" style="background: #8bc34a75; margin-top: 15px; ">
                    <div class="xs-form main-action-meta bp-banner-container " id="bp-window-1529564333371" data-meta-type="process" data-process-id="1529564333371" data-bp-uniq-id="1529976174445972">
                        <div class="col-md-12 center-sidebar">  
                            <div class="table-scrollable table-scrollable-borderless bp-header-param">
                                <table class="table table-sm table-bordered bp-header-param customerInfo-table-<?php echo $this->uniqId ?>">
                                    <tbody>
                                        <tr>
                                            <td colspan="3"><strong style="font-size: 20px;">Terminal 1_<?php echo $index.'_'.$subIndex1 ?></strong></td>
                                        </tr>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Terminal number') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($subRow['terminalnumber']) ? $subRow['terminalnumber'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($subRow['terminalnumber']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Одоо байгаа эсэх') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($subRow['isdefault']) ? $subRow['isdefault'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($subRow['isdefault']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Cashback авах эсэх') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($subRow['iscashback']) ? $subRow['iscashback'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($subRow['iscashback']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            
            <?php 
            $subIndex1++;
            }
        }
        
        $index++;
    }
    
} 

if (isset($this->data['getmiddleposwithowneruppercaseen']) && !empty($this->data['getmiddleposwithowneruppercaseen'])) {
    $middleOwner = $this->data['getmiddleposwithowneruppercaseen']; ?>
    <div class="" style="background: #0096886e; margin-top: 15px; ">
        <div class="xs-form main-action-meta bp-banner-container " id="bp-window-1529564333371" data-meta-type="process" data-process-id="1529564333371" data-bp-uniq-id="1529976174445972">
            <div class="col-md-12 center-sidebar">  
                <div class="table-scrollable table-scrollable-borderless bp-header-param">
                    <table class="table table-sm table-bordered bp-header-param customerInfo-table-<?php echo $this->uniqId ?>">
                        <tbody>
                            <tr>
                                <td colspan="3"><strong style="font-size: 20px;">Merchant <?php echo $index ?></strong></td>
                            </tr>
                            <tr>                                            
                                <td class="text-left middle"  style="width: 28%;">
                                    <span><?php echo $this->lang->line('Merchant name') ?>:</span>                                            
                                </td>
                                <td class="middle" style="width: 60%" colspan="" data-path="merchantname"><?php echo isset($middleOwner['merchantname']) ? $middleOwner['merchantname'].' ' : '' ?><?php echo isset($middleOwner['firstname']) ? $middleOwner['firstname'] : '' ?></td>                                         
                                <td class="middle" style="width: 12%" colspan=""><?php echo isset($middleOwner['merchantname']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                            </tr>
                            <tr>                                            
                                <td class="text-left middle"  style="width: 28%;">
                                    <span><?php echo $this->lang->line('Register no') ?>:</span>                                            
                                </td>
                                <td class="middle" style="width: 60%" colspan="" data-path="registernumber"><?php echo isset($middleOwner['registernumber']) ? $middleOwner['registernumber'] : '' ?></td>                                         
                                <td class="middle" style="width: 12%" colspan=""><?php echo isset($middleOwner['registernumber']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                            </tr>
                            <tr>                                            
                                <td class="text-left middle"  style="width: 28%;">
                                    <span><?php echo $this->lang->line('Account number') ?>:</span>                                            
                                </td>
                                <td class="middle" style="width: 60%" colspan="" data-path="accountnumber"><?php echo isset($middleOwner['accountnumber']) ? $middleOwner['accountnumber'] : '' ?></td>                                         
                                <td class="middle" style="width: 12%" colspan=""><?php echo isset($middleOwner['accountnumber']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                            </tr>
                            <tr>                                            
                                <td class="text-left middle"  style="width: 28%;">
                                    <span><?php echo $this->lang->line('Merchant phone') ?>:</span>                                            
                                </td>
                                <td class="middle" style="width: 60%" colspan="" data-path="address"><?php echo isset($middleOwner['contactphone']) ? $middleOwner['contactphone'] : '' ?></td>                                         
                                <td class="middle" style="width: 12%" colspan=""><?php echo isset($middleOwner['contactphone']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                            </tr>
                            <tr>
                                <td colspan="3"><strong style="font-size: 15px;">Address</strong></td>
                            </tr>
                            <tr>                                            
                                <td class="text-left middle"  style="width: 28%;">
                                    <span><?php echo $this->lang->line('City') ?>:</span>                                            
                                </td>
                                <td class="middle" style="width: 60%" colspan="" data-path="cityname"><?php echo isset($middleOwner['cityname']) ? $middleOwner['cityname'] : '' ?></td>                                         
                                <td class="middle" style="width: 12%" colspan=""><?php echo isset($middleOwner['cityname']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                            </tr>
                            <tr>                                            
                                <td class="text-left middle"  style="width: 28%;">
                                    <span><?php echo $this->lang->line('District') ?>:</span>                                            
                                </td>
                                <td class="middle" style="width: 60%" colspan="" data-path="districtname"><?php echo isset($middleOwner['districtname']) ? $middleOwner['districtname'] : '' ?></td>                                         
                                <td class="middle" style="width: 12%" colspan=""><?php echo isset($middleOwner['districtname']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                            </tr>
                            <tr>                                            
                                <td class="text-left middle"  style="width: 28%;">
                                    <span><?php echo $this->lang->line('Street') ?>:</span>                                            
                                </td>
                                <td class="middle" style="width: 60%" colspan="" data-path="streetname"><?php echo isset($middleOwner['streetname']) ? $middleOwner['streetname'] : '' ?></td>                                         
                                <td class="middle" style="width: 12%" colspan=""><?php echo isset($middleOwner['streetname']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                            </tr>
                            <tr>                                            
                                <td class="text-left middle"  style="width: 28%;">
                                    <span><?php echo $this->lang->line('House') ?>:</span>                                            
                                </td>
                                <td class="middle" style="width: 60%" colspan="" data-path="apartmentdoor"><?php echo isset($middleOwner['apartmentdoor']) ? $middleOwner['apartmentdoor'] : '' ?></td>                                         
                                <td class="middle" style="width: 12%" colspan=""><?php echo isset($middleOwner['apartmentdoor']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php  } 

?>

<style type="text/css">
    
    .customerInfo-table-<?php echo $this->uniqId ?> > tbody > tr {
        height: 34px;
    }
    
    .copy-btn-databank {
        padding: 7px 15px;
        background: #CCC;
        color: #FFF;
        text-decoration: none;
    }
    
    .copy-btn-databank:hover {
        text-decoration: none;
    }
    
</style>

<script type="text/javascript">

    $('body').on('click', '.copy-btn-databank', function (e) {
        
        $('.js-copytextarea-<?php echo $this->uniqId ?>').val($(this).closest('td').prev('td').text());
        $('.js-copytextarea-<?php echo $this->uniqId ?>').focus().select();

        try {
            var successful = document.execCommand('copy');
            var msg = successful ? 'successful' : 'unsuccessful';
            console.log('Copying text command was ' + msg);
        } catch (err) {
            console.log('Oops, unable to copy');
        }
        
    });

</script>