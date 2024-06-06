<form method="post" id="pos-loyalty-form">
    <div class="panel-group mb5">
        <?php if (Session::get(SESSION_PREFIX.'posIsUseCandy') == '1') { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle accordion-toggle-styled">
                        <img src="assets/custom/css/pos/candy.png">
                    </a>
                </h4>
            </div>
            <div class="panel-collapse collapse in">
                <div class="panel-body">

                    <div class="form-group row fom-row mb0">
                        <div class="row">
                            <div class="col-md-5">
                                <?php 
                                echo Form::select(
                                    array(
                                        'id' => 'loyaltyCandyTypeCode', 
                                        'name' => 'loyaltyCandyTypeCode', 
                                        'class' => 'form-control form-control-sm select2', 
                                        'data' => array(
                                            array(
                                                'id' => 'ISDN', 
                                                'name' => 'Утасны дугаар'
                                            ), 
                                            array(
                                                'id' => 'CARDID', 
                                                'name' => 'Картын дугаар'
                                            ), 
                                            array(
                                                'id' => 'NFCID', 
                                                'name' => 'NFC картын дугаар'
                                            ), 
                                            array(
                                                'id' => 'LOYALTYID', 
                                                'name' => 'Дансны дугаар'
                                            )
                                        ), 
                                        'op_value' => 'id', 
                                        'op_text' => 'name', 
                                        'value' => 'ISDN'
                                    )
                                ); 
                                ?>
                            </div>
                            <div class="col-md-7">
                                <?php 
                                echo Form::text(
                                    array(
                                        'id' => 'loyaltyCandyNumber', 
                                        'name' => 'loyaltyCandyNumber', 
                                        'class' => 'form-control', 
                                        'style' => 'height: 26px; padding: 6px 7px; font-size: 15px!important;font-weight: bold!important;'
                                    )
                                ); 
                                ?>
                            </div>
                        </div>
                        <div class="row mt10">
                            <div class="col-md-5 text-right">
                                Нэмэгдэх оноо:
                            </div>
                            <div class="col-md-7">
                                <?php 
                                echo Form::text(
                                    array(
                                        'id' => 'loyaltyCandyAmount', 
                                        'name' => 'loyaltyCandyAmount', 
                                        'class' => 'form-control bigdecimalInit', 
                                        'readonly' => 'readonly', 
                                        'value' => $this->pointData['candyAmount'],
                                        'style' => 'height: 26px; padding: 6px 7px; font-size: 15px!important;font-weight: bold!important;'
                                    )
                                ); 
                                ?>
                            </div>
                        </div>
                    </div>

                </div>  
            </div>
        </div>        
        <?php
        }

        if ($this->isRedpoint) {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle accordion-toggle-styled">
                        <img src="assets/custom/css/pos/redpoint.png" style="height: 25px;">
                    </a>
                </h4>
            </div>
            <div class="panel-collapse collapse in">
                <div class="panel-body">

                    <div class="form-group row fom-row mb0">
                        <div class="row">
                            <div class="col-md-5">
                                <?php 
                                echo Form::select(
                                    array(
                                        'id' => 'loyaltyRedPointTypeCode', 
                                        'name' => 'loyaltyRedPointTypeCode', 
                                        'class' => 'form-control form-control-sm select2', 
                                        'data' => array(
                                            array(
                                                'id' => 'ISDN', 
                                                'name' => 'Утасны дугаар'
                                            ), 
                                            array(
                                                'id' => 'LOYALTYID', 
                                                'name' => 'Дансны дугаар'
                                            )
                                        ), 
                                        'op_value' => 'id', 
                                        'op_text' => 'name', 
                                        'value' => 'ISDN'
                                    )
                                ); 
                                ?>
                            </div>
                            <div class="col-md-7">
                                <?php 
                                echo Form::text(
                                    array(
                                        'id' => 'loyaltyRedPointNumber', 
                                        'name' => 'loyaltyRedPointNumber', 
                                        'class' => 'form-control', 
                                        'style' => 'height: 26px; padding: 6px 7px; font-size: 15px!important;font-weight: bold!important;'
                                    )
                                ); 
                                ?>
                            </div>
                        </div>
                        <div class="row mt10">
                            <div class="col-md-5 text-right">
                                Үлдэгдэл оноо:
                            </div>
                            <div class="col-md-7">
                                <?php 
                                echo Form::text(
                                    array(
                                        'id' => 'loyaltyRedPointBalance', 
                                        'name' => 'loyaltyRedPointBalance', 
                                        'class' => 'form-control bigdecimalInit', 
                                        'readonly' => 'readonly', 
                                        'style' => 'height: 26px; padding: 6px 7px; font-size: 15px!important;font-weight: bold!important;'
                                    )
                                ); 
                                ?>
                            </div>
                        </div>
                        <div class="row mt10">
                            <div class="col-md-5 text-right">
                                Нэмэгдэх оноо:
                            </div>
                            <div class="col-md-7">
                                <?php 
                                echo Form::text(
                                    array(
                                        'id' => 'loyaltyRedPointAmount', 
                                        'name' => 'loyaltyRedPointAmount', 
                                        'class' => 'form-control bigdecimalInit', 
                                        'readonly' => 'readonly', 
                                        'value' => $this->pointData['redPoint'],
                                        'style' => 'height: 26px; padding: 6px 7px; font-size: 15px!important;font-weight: bold!important;'
                                    )
                                ); 
                                ?>
                            </div>
                        </div>
                        <div class="row mt10">
                            <div class="col-md-5 text-right">
                                Нийт оноо:
                            </div>
                            <div class="col-md-7">
                                <?php 
                                echo Form::text(
                                    array(
                                        'id' => 'loyaltyRedPointTotalAmount', 
                                        'name' => 'loyaltyRedPointTotalAmount', 
                                        'class' => 'form-control bigdecimalInit', 
                                        'readonly' => 'readonly', 
                                        'style' => 'height: 26px; padding: 6px 7px; font-size: 15px!important;font-weight: bold!important;'
                                    )
                                ); 
                                ?>
                            </div>
                        </div>
                    </div>

                </div>  
            </div>
        </div>  
        <?php
        }
        ?>
        
    </div>
    <input type="hidden" name="loyaltyTypeCode">
</form>    

<script type="text/javascript">
$(function(){
    
    setTimeout(function(){
        $('#loyaltyCandyNumber').focus();
    }, 10);
    
    $('#loyaltyCandyTypeCode').on('change', function(){
        $('#loyaltyCandyNumber').focus().select();
    });
    
    $('#loyaltyRedPointTypeCode').on('change', function(){
        $('#loyaltyRedPointNumber').focus().select();
    });
    
    $('#loyaltyCandyNumber').on('change', function(){
        var thisVal = $.trim($(this).val());
        if (thisVal != '') {
            $('input[name="loyaltyTypeCode"]').val('candy');
            $('#loyaltyRedPointNumber').val('');
            $('#loyaltyRedPointBalance, #loyaltyRedPointTotalAmount').autoNumeric('set', '');
        } else {
            $('input[name="loyaltyTypeCode"]').val('');
        }
    });
    
    $('#loyaltyRedPointNumber').on('change', function(){
        var thisVal = $.trim($(this).val());
        if (thisVal != '') {
            $('input[name="loyaltyTypeCode"]').val('redpoint');
            $('#loyaltyCandyNumber').val('');
        } else {
            $('input[name="loyaltyTypeCode"]').val('');
            $('#loyaltyRedPointBalance, #loyaltyRedPointTotalAmount').autoNumeric('set', '');
        }
    });
    
    $('#loyaltyRedPointNumber').on('keydown', function(e){
        var keyCode = (e.keyCode ? e.keyCode : e.which), $this = $(this);
        
        if (keyCode == 13) {
            var thisVal = $.trim($this.val());
            
            PNotify.removeAll();
            
            if (thisVal != '') {
                
                $.ajax({
                    type: 'post',
                    url: 'mdpos/getRedPointBalance',
                    data: {number: thisVal},
                    dataType: 'json',
                    beforeSend: function () {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function (data) {
                        
                        if (data.status == 'success') {
                            
                            $('#loyaltyRedPointBalance').autoNumeric('set', data.balance);
                            $('#loyaltyRedPointTotalAmount').autoNumeric('set', Number(data.balance) + Number($('#loyaltyRedPointAmount').autoNumeric('get')));
                            
                        } else {
                            
                            new PNotify({
                                title: data.status,
                                text: data.message,
                                type: data.status, 
                                sticker: false
                            });
                            
                            $('#loyaltyRedPointBalance, #loyaltyRedPointTotalAmount').autoNumeric('set', '');
                        }
                        
                        $('input[name="loyaltyTypeCode"]').val('redpoint');
                        $('#loyaltyCandyNumber').val('');
            
                        Core.unblockUI();
                    }
                });
                
            } else {
                $('input[name="loyaltyTypeCode"]').val('');
                $('#loyaltyRedPointBalance, #loyaltyRedPointTotalAmount').autoNumeric('set', '');
            }
        }
    });
});    
</script>