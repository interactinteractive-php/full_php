<div class="row xs-form">
    <div class="w-100">
        <img src="middleware/assets/img/candy_purple.png" width="140" class="d-block text-center pb15 m-auto"  alt="candy logo">
    </div>
    <div class="col-md-12 form-horizontal">
        <div class="form-group row fom-row">
            <label class="col-form-label col-md-5"></label>
            <div class="col-md-7">
                <span class="form-text text-muted">Дүнгээ оруулаад <strong>ENTER</strong> дарна уу.</span>
                <?php 
                echo Form::text(
                    array(
                        'id' => 'candyAmount', 
                        'class' => 'form-control bigdecimalInit', 
                        'placeholder' => $this->lang->line('amount'), 
                        'value' => $this->amount, 
                        'required' => 'required', 
                        'style' => 'height: 26px; padding: 6px 7px; font-size: 15px!important;font-weight: bold!important;'
                    )
                ); 
                ?>
                <span class="form-text text-danger candy-overflow-amount-message d-none">Төлөх дүнгээс их байна!</span>
            </div>
        </div>
        <div class="form-group row fom-row">
            <div class="col-md-5 pr0">
                <?php 
                echo Form::select(
                    array(
                        'id' => 'candyTypeCode', 
                        'class' => 'form-control form-control-sm select2', 
                        'required' => 'required', 
                        'data' => array(
                            // array(
                            //     'id' => 'ISDN', 
                            //     'name' => 'Утасны дугаар'
                            // ), 
                            // array(
                            //     'id' => 'CARDID', 
                            //     'name' => 'Картын дугаар'
                            // ), 
                            // array(
                            //     'id' => 'NFCID', 
                            //     'name' => 'NFC картын дугаар'
                            // ), 
                            // array(
                            //     'id' => 'LOYALTYID', 
                            //     'name' => 'Дансны дугаар'
                            // ), 
                            array(
                                'id' => 'QRCODEGENERATE', 
                                'name' => 'QR CODE үүсгэх'
                            ), 
                            array(
                                'id' => 'QRCODEREAD', 
                                'name' => 'QR CODE унших'
                            )
                        ), 
                        'op_value' => 'id', 
                        'op_text' => 'name',
                        'value' => 'QRCODEREAD'
                    )
                ); 
                ?>
            </div>
            <div class="col-md-7">
                <?php 
                echo Form::text(
                    array(
                        'id' => 'candyNumber', 
                        'class' => 'form-control', 
                        'required' => 'required', 
                        'readonly' => 'readonly', 
                        'style' => 'height: 26px; padding: 6px 7px; font-size: 15px!important;font-weight: bold!important;'
                    )
                ); 
                ?>
            </div>
        </div>
        <div class="form-group row fom-row" id="tancode-row" style="display: none">
            <?php echo Form::label(array('text' => $this->lang->line('ТАН КОД'), 'class' => 'col-form-label col-md-5')); ?>
            <div class="col-md-7">
                <?php 
                echo Form::text(
                    array(
                        'id' => 'candyTanCode', 
                        'class' => 'form-control', 
                        'placeholder' => $this->lang->line('ТАН КОД'), 
                        'readonly' => 'readonly', 
                        'style' => 'height: 26px; padding: 6px 7px; font-size: 15px!important;font-weight: bold!important;'
                    )
                ); 
                ?>
            </div>
        </div>
        <div class="form-group row fom-row" id="pincode-row" style="display: none">
            <?php echo Form::label(array('text' => $this->lang->line('ПИН КОД'), 'class' => 'col-form-label col-md-5')); ?>
            <div class="col-md-7">
                <?php 
                echo Form::text(
                    array(
                        'id' => 'candyPinCode', 
                        'class' => 'form-control', 
                        'placeholder' => $this->lang->line('ПИН КОД'), 
                        'style' => 'height: 26px; padding: 6px 7px; font-size: 15px!important;font-weight: bold!important;'
                    )
                ); 
                ?>
            </div>
        </div>
    </div>
</div>