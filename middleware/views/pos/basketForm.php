<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'pos-basket-form', 'method' => 'post')); ?>
<div class="row xs-form">
    <div class="col-md-12" id="pos-payment-accordion-delivery">
        <div class="form-group row fom-row">
            
            <?php
            if ($this->keyField == 'customer' || $this->keyField == '4') {
                if (Session::get(SESSION_PREFIX.'posTypeCode') === '4') {
                    echo Form::label(array('text' => $this->lang->line('POS_0126'), 'for' => 'customerId_displayField', 'class' => 'col-form-label col-md-2')); 
                } else {
                    echo Form::label(array('text' => $this->lang->line('POS_0126'), 'for' => 'customerId_displayField', 'class' => 'col-form-label col-md-2', 'required'=>'required')); 
                }
            ?>
            <?php if (Session::get(SESSION_PREFIX.'posTypeCode') === '4') { ?>
                <div class="col-md-7">
                    <div class="meta-autocomplete-wrap" data-section-path="customerId">
                        <div class="input-group double-between-input">
                            <input type="hidden" name="customerId" id="customerId_valueField" data-path="serviceCustomerId" class="popupInit" value="<?php echo $this->customerId; ?>">
                            <input type="text" name="customerId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="customerId" id="customerId_displayField" data-processid="1454315883636" data-lookupid="1535356377337" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off" value="<?php echo $this->customerCode; ?>" title="<?php echo $this->customerCode; ?>">
                            <span class="input-group-btn">
                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('customerId', '1454315883636', '1535356377337', 'single', 'customerId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                            </span>  
                            <span class="input-group-btn">
                                <input type="text" name="customerId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="customerId" id="customerId_nameField" data-processid="1454315883636" data-lookupid="1535356377337" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off" value="<?php echo $this->customerName; ?>" title="<?php echo $this->customerName; ?>">
                            </span>   
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="col-md-7">
                    <div class="meta-autocomplete-wrap" data-section-path="customerId">
                        <div class="input-group double-between-input">
                            <input type="hidden" name="customerId" id="customerId_valueField" data-path="serviceCustomerId" class="popupInit" value="<?php echo $this->customerId; ?>">
                            <input type="text" name="customerId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="customerId" id="customerId_displayField" data-processid="1454315883636" data-lookupid="1535356377337" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off" required="required" value="<?php echo $this->customerCode; ?>" title="<?php echo $this->customerCode; ?>">
                            <span class="input-group-btn">
                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('customerId', '1454315883636', '1535356377337', 'single', 'customerId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                            </span>  
                            <span class="input-group-btn">
                                <input type="text" name="customerId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="customerId" id="customerId_nameField" data-processid="1454315883636" data-lookupid="1535356377337" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off" required="required" value="<?php echo $this->customerName; ?>" title="<?php echo $this->customerName; ?>">
                            </span>   
                        </div>
                    </div>
                </div>            
            <?php } ?>
            <div class="col-md-2">
                <button class="btn yellow-casablanca btn-sm<?php echo $this->keyField == '4' ? ' hidden' : '' ?>" type="button" style="padding-top: 2px;padding-bottom: 3px;" onclick="posNFCCardRead(this, 'tempInvoice');"><?php echo $this->lang->line('POS_0127'); ?></button>
            </div>
            <?php
            } elseif ($this->keyField == '2' || $this->keyField == '3') {
                echo Form::label(array('text' => $this->lang->line('POS_0215'), 'for' => 'deskId_displayField', 'class' => 'col-form-label col-md-2', 'required'=>'required')); 
            ?>
            <div class="col-md-10">
                <div class="meta-autocomplete-wrap" data-section-path="customerId">
                    <div class="input-group double-between-input">
                        <input type="hidden" name="deskId" id="deskId_valueField" data-path="deskId" class="popupInit" value="">
                        <input type="text" name="deskId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="deskId" id="deskId_displayField" data-processid="1454315883636" data-lookupid="1506324916539" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off" required="required" value="" title="">
                        <span class="input-group-btn">
                            <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('deskId', '1454315883636', '1506324916539', 'single', 'deskId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                        </span>  
                        <span class="input-group-btn">
                            <input type="text" name="deskId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="deskId" id="deskId_nameField" data-processid="1454315883636" data-lookupid="1506324916539" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off" required="required" value="" title="">
                        </span>   
                    </div>
                </div>
            </div>
            <?php
            } else {
                echo Form::label(array('text' => $this->lang->line('POS_0128'), 'for' => 'phoneNumber', 'class' => 'col-form-label col-md-2', 'required'=>'required')); 
            ?>
            <div class="col-md-4">
                <?php 
                echo Form::text(
                    array(
                        'name' => 'phoneNumber', 
                        'id' => 'phoneNumber', 
                        'class' => 'form-control form-control-sm posAddressField', 
                        'required' => 'required', 
                        'data-inputmask-regex' => '^[0-9]{1,8}$'
                    )
                ); 
                ?>
            </div>
            <?php
            }
            ?>
        </div>
        <div class="form-group row fom-row">
            <label class="col-form-label col-md-2" for="description"><?php echo $this->lang->line('POS_0129'); ?>:</label>
            <div class="col-md-10">
                <textarea name="description" id="description" class="form-control posAddressField" placeholder="<?php echo $this->lang->line('POS_0129'); ?>" rows="4"></textarea>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="payAmount" value="<?php echo $this->payAmount; ?>">
<input type="hidden" name="isBasket" value="">
<?php echo Form::close(); ?>   