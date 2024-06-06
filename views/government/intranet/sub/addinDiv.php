<?php $uniqid = getUID(); ?>
<div class="questiondv question-index-<?php echo $this->otherUniqId ?>">
    <div class="d-flex flex-row align-items-center justify-content-between">
        <div class="w-100 mr-3">
            <input type="text" name="questionTxt[<?php echo $this->order; ?>]" data-path="questionTxt" placeholder="Асуулт <?php echo $this->order+1; ?>" class="input-text-style2 w-100 border-gray font-size-22" required="required">
        </div>
        <input type="hidden" name="questionType[]" data-path="qtype" value="1" />
        <div class="select-option-box">
            <div class="select-option-style2 select-option-style2_<?php echo $uniqid ?>" style="width:200px;">
                <select data-path="questionType">
                    <option value="1" data-icon="" selected="selected">Нэг сонголттой</option>
                    <option value="1" data-icon="">Нэг сонголттой</option>
                    <option value="2" data-icon="">Олон сонголттой</option>
                    <option value="3" data-icon="">Нээлттэй асуулт</option>
                </select>
            </div>
        </div>
        <div class="d-flex flex-row isother">
            <span class="mr-2"><?php echo Lang::line('isOther') ?></span>
            <div class="form-check form-check-switchery">
                <label class="form-check-label w-100">
                    <input type="checkbox" name="isother[<?php echo $this->order; ?>]" data-path="isother" value="1" class="form-check-input-switchery notuniform" d-is-checked="1" checked data-fouc>
                </label>
            </div>
        </div>
    </div>
    <div class="main-selector">
        <div class="checkboxes checkbox-option">
            <div class="form-group pt-2 mb-0 data-selectoption">
                <div class="form-check">
                    <label class="form-check-label d-flex justify-content-between align-items-center pl15">
                        <input type="text" name="answerTxt[<?php echo $this->order; ?>][]" data-path="answerTxt" placeholder="Хариулт 1" class="input-text-style2 imp ml-1">
                        <span>
                            <a href="javascript:;" onclick="removeAnswer_<?php echo $this->otherUniqId ?>(this)">
                                <i class="icon-cross2 text-gray"></i>
                            </a>
                        </span>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group pt-2 mb-0">
            <div class="form-check mb-3">
                <label class="form-check-label d-flex align-items-center">
                    <span class="text-gray ml-1 w-100 addanswerbtn" data-order="<?php echo $this->order; ?>" onclick="addAnswer_<?php echo $this->otherUniqId ?>(this, '<?php echo $this->otherUniqId ?>')"><?php echo Lang::line('Add_option_or_ADD_OTHER') ?></span>
                </label>
            </div>
            <div class="d-flex flex-row justify-content-end border-top-1 border-gray pt-2">
                <div class="border-right-1 border-gray pr10 mr10">
                    <span class="mr-2" style="position: relative; top: -4px;">Асуулт нэмэх</span>
                    <a href="javascript:;" class="questiondv-copy-btn questiondv-copy-btn-<?php echo $this->otherUniqId ?>"><i class="fa fa-clipboard font-size-18 mr-2"></i></a>
                    <a href="javascript:;" class="questiondv-delete-btn-<?php echo $this->otherUniqId ?>"><i class="fa fa-trash-o font-size-18 mr-2"></i></a>
                </div>
                <div class="d-flex flex-row isother">
                    <span class="mr-2"><?php echo Lang::line('Required') ?></span>
                    <div class="form-check form-check-switchery">
                        <label class="form-check-label w-100">
                            <input type="checkbox" name="isrequired[<?php echo $this->order; ?>]" data-path="isrequired" value="1" class="form-check-input-switchery notuniform" checked data-fouc>
                        </label>
                    </div>
                </div>
                <div>
                    <input type="text" name="limitCount[<?php echo $this->order; ?>]" data-path="limitCount" class="form-control longInit" maxlength="3" value="0" style="text-align: right; width: 50px; display: none;">
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        select2style_<?php echo $this->otherUniqId ?>('select-option-style2_<?php echo $uniqid ?>');
    });
    
    $('.question-index-<?php echo $this->otherUniqId ?>').on('change', 'input[data-path="isother"]', function () {
        var $this = $(this);
        var $parent = $this.closest('.question-index-<?php echo $this->otherUniqId ?>');
        
        $this.attr('d-is-checked', '0');
        $parent.find('input[data-path="answerTxt"]').attr('required', 'required');
        
        if (this.checked) {
            $parent.find('input[data-path="answerTxt"]').removeAttr('required');
            $this.attr('d-is-checked', '1');
        }
    });
</script>
<style type="text/css">
    
    .question-index-<?php echo $this->otherUniqId ?> .error {
        border: 1px solid #F00 !important;
    }
    
    .questiondv .input-text-style2.imp {
        border-bottom: 1px solid transparent;
    }
</style>