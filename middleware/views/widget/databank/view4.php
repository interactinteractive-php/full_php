<?php $type = (isset($this->data['typeid']) && $this->data['typeid']) ? $this->data['typeid'] : '1'; ?>

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
                        <?php if (isset($this->data['phonenumber']) && $this->data['phonenumber']) { ?>
                        <tr>                                            
                            <td class="text-left middle"  style="width: 28%;">
                                <span><?php echo $this->lang->line('Merchant phone') ?>:</span>                                            
                            </td>
                            <td class="middle" style="width: 60%" colspan="" data-path="address"><?php echo isset($this->data['phonenumber']) ? $this->data['phonenumber'] : '' ?></td>                                         
                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['phonenumber']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                        </tr>
                        <?php } ?>
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
                        <?php if ($type === '3' && isset($this->data['owneremail'])) { ?>
                            <tr>                                            
                                <td class="text-left middle"  style="width: 28%;">
                                    <span><?php echo $this->lang->line('Owner email') ?>:</span>                                            
                                </td>
                                <td class="middle" style="width: 60%" colspan="" data-path="owneremail"><?php echo isset($this->data['owneremail']) ? $this->data['owneremail'] : '' ?></td>                                         
                                <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['owneremail']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                            </tr>
                        <?php } ?>
                        <?php if ($type === '3' && isset($this->data['priorityname'])) { ?>
                        <tr>                                            
                            <td class="text-left middle"  style="width: 28%;">
                                <span><?php echo $this->lang->line('Priority name') ?>:</span>                                            
                            </td>
                            <td class="middle" style="width: 60%" colspan="" data-path="priorityname"><?php echo isset($this->data['priorityname']) ? $this->data['priorityname'] : '' ?></td>                                         
                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['priorityname']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                        </tr>
                        <?php } ?>
                        <?php if ($type === '3' && isset($this->data['priorityname'])) { ?>
                        <tr>                                            
                            <td class="text-left middle"  style="width: 28%;">
                                <span><?php echo $this->lang->line('New priority name') ?>:</span>                                            
                            </td>
                            <td class="middle" style="width: 60%" colspan="" data-path="newpriorityname"><?php echo isset($this->data['newpriorityname']) ? $this->data['newpriorityname'] : '' ?></td>                                         
                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['newpriorityname']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                        </tr>
                        <?php } ?>
                        <?php if ($type === '3' && isset($this->data['phonenumber']) && $this->data['phonenumber']) { ?>
                        <tr>                                            
                            <td class="text-left middle"  style="width: 28%;">
                                <span><?php echo $this->lang->line('Phone number') ?>:</span>                                            
                            </td>
                            <td class="middle" style="width: 60%" colspan="" data-path="phonenumber"><?php echo isset($this->data['phonenumber']) ? $this->data['phonenumber'] : '' ?></td>                                         
                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['phonenumber']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                        </tr>
                        <?php } ?>
                        <?php if ($type === '3') { ?>
                            <?php if (
                                isset($this->data['newcityname']) && 
                                isset($this->data['newdistrictname']) && 
                                isset($this->data['newstreetname']) && 
                                isset($this->data['newapartmentdoor']) && 
                                $this->data['newcityname'] &&
                                $this->data['newdistrictname'] &&
                                $this->data['newstreetname'] &&
                                $this->data['newapartmentdoor']
                                    ) { ?>
                                <tr>
                                    <td colspan="3"><strong style="font-size: 15px;"><?php echo Lang::line('New Address'); ?></strong></td>
                                </tr>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('City') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan="" data-path="newcityname"><?php echo isset($this->data['newcityname']) ? $this->data['newcityname'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['newcityname']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                                </tr>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('District') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan="" data-path="newdistrictname"><?php echo isset($this->data['newdistrictname']) ? $this->data['districtname'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['newdistrictname']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                                </tr>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('Street') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan="" data-path="newstreetname"><?php echo isset($this->data['newstreetname']) ? $this->data['newstreetname'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['newstreetname']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                                </tr>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('House') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan="" data-path="newapartmentdoor"><?php echo isset($this->data['newapartmentdoor']) ? $this->data['newapartmentdoor'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['newapartmentdoor']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                                </tr>
                            <?php } ?>
                            <?php if (isset($this->data['newowneremail'])  && $this->data['newowneremail']) { ?>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('New owner email') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan="" data-path="newowneremail"><?php echo isset($this->data['newowneremail']) ? $this->data['newowneremail'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['newowneremail']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                                </tr>
                            <?php } ?>
                                
                            <?php if (isset($this->data['newpriorityname']) && $this->data['newpriorityname']) { ?>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('New priority name') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan="" data-path="newpriorityname"><?php echo isset($this->data['newpriorityname']) ? $this->data['newpriorityname'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['newpriorityname']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                                </tr>
                            <?php } ?>
                            
                            <?php if (isset($this->data['newphonenumber']) && $this->data['newphonenumber']) { ?>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('New phone number') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan="" data-path="newphonenumber"><?php echo isset($this->data['newphonenumber']) ? $this->data['newphonenumber'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['newphonenumber']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php

$index = 1;
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
                                    
                                <?php if (isset($row['industrycode'])) { ?>
                                
                                    <tr>                                            
                                        <td class="text-left middle"  style="width: 28%;">
                                            <span><?php echo $this->lang->line('Merchant category code') ?>:</span>                                            
                                        </td>
                                        <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['industrycode']) ? $row['industrycode'] : '' ?></td>                                         
                                        <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['industrycode']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                    </tr>
                                    
                                <?php } ?>
                                    
                                <?php if (isset($row['industryname'])) { ?>
                                
                                    <tr>                                            
                                        <td class="text-left middle"  style="width: 28%;">
                                            <span><?php echo $this->lang->line('Merchant category name') ?>:</span>                                            
                                        </td>
                                        <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['industryname']) ? $row['industryname'] : '' ?></td>                                         
                                        <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['industryname']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                    </tr>
                                    
                                <?php } ?>
                                    
                                <?php if (isset($row['agentcode'])) { ?>
                                
                                    <tr>                                            
                                        <td class="text-left middle"  style="width: 28%;">
                                            <span><?php echo $this->lang->line('Agent code') ?>:</span>                                            
                                        </td>
                                        <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['agentcode']) ? $row['agentcode'] : '' ?></td>                                         
                                        <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['agentcode']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                    </tr>
                                    
                                <?php } ?>
                                    
                                <?php if (isset($row['agentname'])) { ?>
                                
                                    <tr>                                            
                                        <td class="text-left middle"  style="width: 28%;">
                                            <span><?php echo $this->lang->line('Agent name') ?>:</span>                                            
                                        </td>
                                        <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['agentname']) ? $row['agentname'] : '' ?></td>                                         
                                        <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['agentname']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                    </tr>
                                    
                                <?php } ?>
                                    
                                <?php if (isset($row['departmentcode'])) { ?>
                                
                                    <tr>                                            
                                        <td class="text-left middle"  style="width: 28%;">
                                            <span><?php echo $this->lang->line('Agent code') ?>:</span>                                            
                                        </td>
                                        <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['departmentcode']) ? $row['departmentcode'] : '' ?></td>                                         
                                        <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['departmentcode']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                    </tr>
                                    
                                <?php } ?>
                                    
                                <?php if (isset($row['departmentname'])) { ?>
                                
                                    <tr>                                            
                                        <td class="text-left middle"  style="width: 28%;">
                                            <span><?php echo $this->lang->line('Agent name') ?>:</span>                                            
                                        </td>
                                        <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['departmentname']) ? $row['departmentname'] : '' ?></td>                                         
                                        <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['departmentname']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                    </tr>
                                    
                                <?php } ?>
                                    
                                <?php if (isset($row['address']) && $row['address']) { ?>
                                
                                    <tr>                                            
                                        <td class="text-left middle"  style="width: 28%;">
                                            <span><?php echo $this->lang->line('Merchant full address') ?>:</span>                                            
                                        </td>
                                        <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['address']) ? $row['address'] : '' ?></td>                                         
                                        <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['address']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                    </tr>
                                    
                                <?php } ?>
                                
                                <tr>
                                    <td colspan="3"><strong style="font-size: 15px;"><?php echo ($type === '1') ? 'Address' : 'Information'; ?></strong></td>
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
                                <?php if (isset($row['contactposition'])) { ?>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('Contact name') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['contactname']) ? $row['contactname'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['contactname']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                </tr>
                                <?php } ?>
                                <?php if (isset($row['contactposition'])) { ?>
                                    <tr>                                            
                                        <td class="text-left middle"  style="width: 28%;">
                                            <span><?php echo $this->lang->line('Contact position') ?>:</span>                                            
                                        </td>
                                        <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['contactposition']) ? $row['contactposition'] : '' ?></td>                                         
                                        <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['contactposition']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
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
                                
                                <?php if (isset($row['cashback']) && $row['cashback']) { ?>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('Cashback') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['cashback']) ? $row['cashback'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['cashback']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                </tr>
                                <?php } ?>
                                
                                <?php if (isset($row['season']) && $row['season']) { ?>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('Season') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['season']) ? $row['season'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['season']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                </tr>
                                <?php } ?>
                                
                                <?php if (isset($row['merchantposcount']) && $row['merchantposcount']) { ?>
                                
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('Merchant pos count') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['merchantposcount']) ? $row['merchantposcount'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['merchantposcount']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                </tr>
                                
                                <?php } ?>
                                
                                <?php if (isset($row['what3words']) && $row['what3words']) { ?>
                                <tr>                                            
                                    <td class="text-left middle"  style="width: 28%;">
                                        <span><?php echo $this->lang->line('What 3 words') ?>:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['what3words']) ? $row['what3words'] : '' ?></td>                                         
                                    <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['what3words']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                </tr>
                                <?php } ?>
                                
                                <?php if ($type === '3') { ?>
                                    
                                    <tr>
                                        <td colspan="3"><strong style="font-size: 20px;">New merchant 1_<?php echo $index ?></strong></td>
                                    </tr>
                                    <?php if (isset($row['newmerchantnumber']) && $row['newmerchantnumber']) { ?>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Merchant number') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['newmerchantnumber']) ? $row['newmerchantnumber'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['newmerchantnumber']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($row['newmerchantname']) && $row['newmerchantname']) { ?>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Merchant name') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan="" data-path="newmerchantname"><?php echo isset($row['newmerchantname']) ? $row['newmerchantname'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['newmerchantname']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($row['newindustrycode'])) { ?>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Merchant category code') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan="" data-path="newindustrycode"><?php echo isset($row['newindustrycode']) ? $row['newindustrycode'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['newindustrycode']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($row['newindustryname'])) { ?>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Merchant category name') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan="" data-path="newindustryname"><?php echo isset($row['newindustryname']) ? $row['newindustryname'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['newindustryname']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($row['newagentcode'])) { ?>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Agent code') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan="" data-path="newagentcode"><?php echo isset($row['newagentcode']) ? $row['newagentcode'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['newagentcode']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($row['newwhat3words'])) { ?>
                                        
                                    <?php } ?>
                                    <?php if (isset($row['newagentname']) && $row['newagentname']) { ?>
                                            
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('New agent name') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan="" data-path="newagentname"><?php echo isset($row['newagentname']) ? $row['newagentname'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['newagentname']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($row['newaddress'])) { ?>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Merchant full address') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['newaddress']) ? $row['newaddress'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['newaddress']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="3"><strong style="font-size: 15px;"><?php echo 'Information'; ?></strong></td>
                                    </tr>
                                    <?php if (isset($row['newcontactname'])) { ?>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Contact name') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['newcontactname']) ? $row['newcontactname'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['newcontactname']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                    <?php } ?>

                                    <?php if (isset($row['newcontactposition'])) { ?>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Contact position') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['newcontactposition']) ? $row['newcontactposition'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['newcontactposition']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($row['newcontactphone'])) { ?>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Contact phone') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['newcontactphone']) ? $row['newcontactphone'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['newcontactphone']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($row['newaccountnumber'])) { ?>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('Account number') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan="" data-path="newaccountnumber"><?php echo isset($row['newaccountnumber']) ? $row['newaccountnumber'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['newaccountnumber']) ? '<a href="javascript:;" class="copy-btn-databank"> Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($row['newwhat3words'])) { ?>
                                        <tr>                                            
                                            <td class="text-left middle"  style="width: 28%;">
                                                <span><?php echo $this->lang->line('What 3 words') ?>:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($row['newwhat3words']) ? $row['newwhat3words'] : '' ?></td>                                         
                                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($row['newwhat3words']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
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