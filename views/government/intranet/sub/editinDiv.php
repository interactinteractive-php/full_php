<?php 
$uniqid = getUID(); 
if ($this->isEdit && $this->mainData['int_poll_question_dv']) {
    foreach ($this->mainData['int_poll_question_dv'] as $key => $question) { ?>
        <div class="questiondv question-index-<?php echo $this->otherUniqId ?>">
            <input type="hidden" name="questionid[]" value="<?php echo $question['id'] ?>" />
            <input type="hidden" name="questionType[]" data-path="qtype" value="<?php echo $question['answertypeid'] ?>" />
            <div class="d-flex flex-row align-items-center justify-content-between">
                <div class="w-100 mr-3">
                    <input type="text" name="questionTxt[<?php echo $key; ?>]" data-path="questionTxt" placeholder="Асуулт <?php echo $key+1; ?>" class="input-text-style2 w-100 border-gray font-size-22" value="<?php echo Str::quoteToHtmlChar($question['questiontxt']) ?>">
                </div>
                <div class="select-option-box">
                    <div class="select-option-style2 select-option-style2_<?php echo $uniqid ?>" style="width:200px;">
                        <select data-path="questionType">
                            <option value="1" <?php echo $question['answertypeid'] == '1' ? 'selected="selected"' : '' ?> data-icon="">Нэг сонголттой</option>
                            <option value="1" data-icon="">Нэг сонголттой</option>
                            <option value="2" <?php echo $question['answertypeid'] == '2' ? 'selected="selected"' : '' ?> data-icon="">Олон сонголттой</option>
                            <option value="3" <?php echo $question['answertypeid'] == '3' ? 'selected="selected"' : '' ?> data-icon="">Нээлттэй асуулт</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex flex-row isother" style="<?php echo $question['answertypeid'] == '3' ? 'display:none !important' : '' ?>">
                    <span class="mr-2"><?php echo Lang::line('isOther') ?></span>
                    <div class="form-check form-check-switchery">
                        <label class="form-check-label w-100">
                            <input type="checkbox" name="isother[<?php echo $key; ?>]" data-path="isother" value="1" class="form-check-input-switchery notuniform" <?php echo (isset($question['isother']) && $question['isother'] == '1') ? 'checked' : '' ?>  data-fouc>
                        </label>
                    </div>
                </div>
            </div>
            <div class="main-selector">
                <div class="checkboxes checkbox-option">
                    <?php if (isset($question['int_poll_answer_dv'])) {
                        foreach ($question['int_poll_answer_dv'] as $key1 => $answer) { 
                            if (isset($answer['isother']) && $answer['isother'] == '1') {} else {
                                ?>
                                <div class="form-group pt-2 mb-0 data-selectoption">
                                    <div class="form-check" style="<?php echo $question['answertypeid'] == '3' ? 'display:none !important' : '' ?>">
                                        <label class="form-check-label d-flex justify-content-between align-items-center pl15">
                                            <input type="text" name="answerTxt[<?php echo $key; ?>][]" value="<?php echo $answer['answertxt'] ?>" data-path="answerTxt" placeholder="Хариулт 1" class="input-text-style2 imp ml-1">
                                            <span>
                                                <a href="javascript:;" onclick="removeAnswer_<?php echo $this->otherUniqId ?>(this)">
                                                    <i class="icon-cross2 text-gray"></i>
                                                </a>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                        <?php }
                        }
                    } ?>
                </div>
                
                <div class="form-group pt-2 mb-0">
                    <div class="form-check mb-3" style="<?php echo $question['answertypeid'] == '3' ? 'display:none !important' : '' ?>">
                        <label class="form-check-label d-flex align-items-center">
                            <span class="text-gray ml-1 w-100 addanswerbtn" data-order="<?php echo $key; ?>" onclick="addAnswer_<?php echo $this->otherUniqId ?>(this)"><?php echo Lang::line('Add_option_or_ADD_OTHER') ?></span>
                        </label>
                    </div>
                    <div class="d-flex flex-row justify-content-end border-top-1 border-gray pt-2">
                        <div class="border-right-1 border-gray pr10 mr10">
                            <span class="mr-2" style="position: relative; top: -4px;">Асуулт нэмэх</span>
                            <a href="javascript:;" class="questiondv-copy-btn questiondv-copy-btn-<?php echo $this->otherUniqId ?>"><i class="fa fa-clipboard font-size-18 mr-2"></i></a>
                            <a href="javascript:;" class="questiondv-delete-btn-<?php echo $this->otherUniqId ?>"><i class="fa fa-trash-o font-size-18 mr-2"></i></a>
                        </div>
                        <div class="d-flex flex-row isother" style="<?php echo $question['answertypeid'] == '3' ? 'display:none !important' : '' ?>">
                            <span class="mr-2"><?php echo Lang::line('Required') ?></span>
                            <div class="form-check form-check-switchery">
                                <label class="form-check-label w-100">
                                    <input type="checkbox" name="isrequired[<?php echo $key; ?>]" data-path="isrequired" value="1"  class="form-check-input-switchery notuniform" <?php echo (isset($question['isrequired']) && $question['isrequired'] == '1') ? 'checked' : '' ?> data-fouc>
                                </label>
                            </div>
                        </div>
                        <div>
                            <input type="text" name="limitCount[<?php echo $key; ?>]" data-path="limitCount" class="form-control longInit" maxlength="3" value="<?php echo (isset($question['limitcount']) && $question['answertypeid'] != '1') ? $question['limitcount'] : '' ?>" style="text-align: right; width: 50px; <?php echo ($question['answertypeid'] != '2') ? 'display: none' : '' ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }
}
?>
<script type="text/javascript">
    $(function () {
        select2style_<?php echo $this->otherUniqId ?>('select-option-style2_<?php echo $uniqid ?>');
    });
</script>
