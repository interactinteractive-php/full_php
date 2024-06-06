<div class="" style="background: #ff98004d; ">
    <div class="" style="position: absolute; left: -1000px; top: -1000px; ">
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
                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($this->data['merchantname']) ? $this->data['merchantname'].' ' : '' ?><?php echo isset($row['firstname']) ? $row['firstname'] : '' ?></td>                                         
                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['merchantname']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                        </tr>
                        <tr>                                            
                            <td class="text-left middle"  style="width: 28%;">
                                <span><?php echo $this->lang->line('Merchant full address') ?>:</span>                                            
                            </td>
                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($this->data['address']) ? $this->data['address'] : '' ?></td>                                         
                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['address']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                        </tr>
                        <tr>
                            <td colspan="3"><strong style="font-size: 15px;">Address</strong></td>
                        </tr>
                        <tr>                                            
                            <td class="text-left middle"  style="width: 28%;">
                                <span><?php echo $this->lang->line('City') ?>:</span>                                            
                            </td>
                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($this->data['cityname']) ? $this->data['cityname'] : '' ?></td>                                         
                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['cityname']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                        </tr>
                        <tr>                                            
                            <td class="text-left middle"  style="width: 28%;">
                                <span><?php echo $this->lang->line('District') ?>:</span>                                            
                            </td>
                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($this->data['districtname']) ? $this->data['districtname'] : '' ?></td>                                         
                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['districtname']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                        </tr>
                        <tr>                                            
                            <td class="text-left middle"  style="width: 28%;">
                                <span><?php echo $this->lang->line('Street') ?>:</span>                                            
                            </td>
                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($this->data['streetname']) ? $this->data['streetname'] : '' ?></td>                                         
                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['streetname']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                        </tr>
                        <tr>                                            
                            <td class="text-left middle"  style="width: 28%;">
                                <span><?php echo $this->lang->line('House') ?>:</span>                                            
                            </td>
                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($this->data['apartmentdoor']) ? $this->data['apartmentdoor'] : '' ?></td>                                         
                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['apartmentdoor']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                        </tr>
                        <tr>                                            
                            <td class="text-left middle"  style="width: 28%;">
                                <span><?php echo $this->lang->line('Contact phone') ?>:</span>                                            
                            </td>
                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($this->data['contactphone']) ? $this->data['contactphone'] : '' ?></td>                                         
                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['contactphone']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                        </tr>
                        <tr>                                            
                            <td class="text-left middle"  style="width: 28%;">
                                <span><?php echo $this->lang->line('Account number') ?>:</span>                                            
                            </td>
                            <td class="middle" style="width: 60%" colspan=""><?php echo isset($this->data['accountnumber']) ? $this->data['accountnumber'] : '' ?></td>                                         
                            <td class="middle" style="width: 12%" colspan=""><?php echo isset($this->data['accountnumber']) ? '<a href="javascript:;" class="copy-btn-databank" > Хуулах</a>' : '' ?></td>                                         
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php if (isset($this->data['posgetterminalrequestlist']) && !empty($this->data['posgetterminalrequestlist'])) {
    $subIndex = 1;
    foreach ($this->data['posgetterminalrequestlist'] as $key => $subRow) { ?>
        <div class="" style="background: #8bc34a75; margin-top: 15px; ">
            <div class="xs-form main-action-meta bp-banner-container " id="bp-window-1529564333371" data-meta-type="process" data-process-id="1529564333371" data-bp-uniq-id="1529976174445972">
                <div class="col-md-12 center-sidebar">  
                    <div class="table-scrollable table-scrollable-borderless bp-header-param">
                        <table class="table table-sm table-bordered bp-header-param customerInfo-table-<?php echo $this->uniqId ?>">
                            <tbody>
                                <tr>
                                    <td colspan="3"><strong style="font-size: 20px;">Merchant 1_<?php echo $subIndex ?></strong></td>
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
} ?>
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