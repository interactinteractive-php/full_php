<div class="bl-widget-form_bank_card">
    <div class="media">
        <div class="mr-2">
            <img src="<?php echo issetParam($this->fillData[2]['value']); ?>" onerror="onUserImageError(this);" class="rounded-circle">
        </div>

        <div class="media-body">
            <div class="bank-card-name1"><?php echo issetParam($this->fillData[3]['value']); ?></div>
            <div class="bank-card-name2"><?php echo issetParam($this->fillData[4]['value']); ?></div>
        </div>

        <div class="ml-3">
            <?php
            if (isset($this->fillData[5])) {
                
                $fillData5 = $this->fillData[5];
                $param = $fillData5['param'];
                
                $control = Mdwebservice::renderParamControl($this->methodId, $param, 'param[' . $param['PARAM_REAL_PATH'] . ']', $param['PARAM_REAL_PATH'], array($param['LOWER_PARAM_REAL_PATH'] => $fillData5['value']));
                
                if ($param['META_TYPE_CODE'] == 'bigdecimal' || $param['META_TYPE_CODE'] == 'long' || $param['META_TYPE_CODE'] == 'integer') {
                    $isNumberInput = true;
                }
            ?>
                <div class="bank-card-name4-label"><?php echo Lang::line($fillData5['param']['META_DATA_NAME']); ?></div>
                <div class="bank-card-name4-value"><?php echo $control; ?></div>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="media-info">
        <div class="d-flex align-items-start flex-column" style="height: 90px;">
            <?php
            if (isset($this->fillData[6])) {
                $fillData6 = $this->fillData[6];
                $fillData7 = issetParam($this->fillData[7]['value']);
            ?>
                <div class="bank-card-name6-label mt-auto"><?php echo Lang::line($fillData6['param']['META_DATA_NAME']); ?></div>
                <div class="bank-card-name6-value">
                    <?php echo $fillData6['value']; ?>
                    <span>
                        <?php echo $fillData7; ?>
                    <span>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>

<style type="text/css">
.bl-widget-form_bank_card-<?php echo $this->methodId; ?>-<?php echo $this->sectionCode; ?> {
    background-repeat: no-repeat;
    background-size: cover;
    background-color: #319def;
    background-image: url(<?php echo issetParam($this->fillData[1]['value']); ?>);
    height: 200px;
    padding: 0;
    margin: 0;
}
.bl-widget-form_bank_card-<?php echo $this->methodId; ?>-<?php echo $this->sectionCode; ?> .media {
    color: #fff;
    margin-bottom: 25px;
}
.bl-widget-form_bank_card-<?php echo $this->methodId; ?>-<?php echo $this->sectionCode; ?> .media .media-body {
    -ms-flex-item-align: center;
    align-self: center;
}
.bl-widget-form_bank_card-<?php echo $this->methodId; ?>-<?php echo $this->sectionCode; ?> .media img.rounded-circle {
    width: 50px;
    height: 50px;
    border: 2px #fff solid;
}
.bl-widget-form_bank_card-<?php echo $this->methodId; ?>-<?php echo $this->sectionCode; ?> .media .bank-card-name1 {
    font-weight: 700;
    line-height: 14px;
    font-size: 13px;
    margin-bottom: 5px;
}
.bl-widget-form_bank_card-<?php echo $this->methodId; ?>-<?php echo $this->sectionCode; ?> .media .bank-card-name2 {
    font-weight: normal;
    font-size: 11px;
}
.bl-widget-form_bank_card-<?php echo $this->methodId; ?>-<?php echo $this->sectionCode; ?> .media .badge {
    color: #000;
    padding: 4px 5px;
}
.bl-widget-form_bank_card-<?php echo $this->methodId; ?>-<?php echo $this->sectionCode; ?> .media-info {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-align: start;
    align-items: flex-start;
    margin-top: 15px;
    color: #585858;
}
.bl-widget-form_bank_card-<?php echo $this->methodId; ?>-<?php echo $this->sectionCode; ?> .bank-card-name4-label {
    text-align: right;
    font-size: 11px;
}
.bl-widget-form_bank_card-<?php echo $this->methodId; ?>-<?php echo $this->sectionCode; ?> .bank-card-name4-value input[type=text] {
    background: transparent;
    border: none;
    outline: none;
    padding: 0;
    color: #fff;
    font-size: 18px;
    max-width: 145px;
    min-width: 60px;
}
.bl-widget-form_bank_card-<?php echo $this->methodId; ?>-<?php echo $this->sectionCode; ?> .bank-card-name5-label {
    font-size: 11px;
    text-align: right;
}
.bl-widget-form_bank_card-<?php echo $this->methodId; ?>-<?php echo $this->sectionCode; ?> .bank-card-name5-value {
    font-size: 18px;
}
.bl-widget-form_bank_card-<?php echo $this->methodId; ?>-<?php echo $this->sectionCode; ?> .bank-card-name6-label {
    font-size: 11px;
    text-align: left;
    color: #555;
}
.bl-widget-form_bank_card-<?php echo $this->methodId; ?>-<?php echo $this->sectionCode; ?> .bank-card-name6-value {
    font-size: 15px;
    color: #555;
    font-weight: 600;
}
.bl-widget-form_bank_card-<?php echo $this->methodId; ?>-<?php echo $this->sectionCode; ?> .bank-card-name6-value span {
    padding-left: 5px;
}
</style>

<?php 
if (isset($isNumberInput)) {
?>
<script type="text/javascript">
$(function() {
    
    var $formBankCardInputs_<?php echo $this->methodId; ?>_<?php echo $this->sectionCode; ?> = $('.bl-widget-form_bank_card-<?php echo $this->methodId; ?>-<?php echo $this->sectionCode; ?> .bigdecimalInit');
    
    function setWidthFitContent_<?php echo $this->methodId; ?>_<?php echo $this->sectionCode; ?>() {
        $formBankCardInputs_<?php echo $this->methodId; ?>_<?php echo $this->sectionCode; ?>.each(function() {
            this.style.width = (this.value.length - 1) + 'ch';
        });
    }
    
    setWidthFitContent_<?php echo $this->methodId; ?>_<?php echo $this->sectionCode; ?>();
    
    $formBankCardInputs_<?php echo $this->methodId; ?>_<?php echo $this->sectionCode; ?>.on('input change keyup paste', function(){
        setTimeout(function() {
            setWidthFitContent_<?php echo $this->methodId; ?>_<?php echo $this->sectionCode; ?>();
        }, 0);
    });
});
</script>
<?php 
}
?>