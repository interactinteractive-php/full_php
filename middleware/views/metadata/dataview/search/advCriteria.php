<?php
if (isset($this->advancedCriteria) && $this->advancedCriteria) { 
    $saveCriteriaTemplate = '';
    echo '<form class="form-horizontal xs-form adv-criteria-form-'. $this->metaDataId .'" method="post" id="adv-criteria-form" style="min-height: 350px">';
    ?>
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="nav-item"><a href="#bottom-tab1" class="nav-link active" data-toggle="tab">Үндсэн мэдээлэл</a></li>
        <li class="nav-item"><a href="#bottom-tab2" class="nav-link" data-toggle="tab">Бусад утгууд</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade active show" id="bottom-tab1">
            <?php
                echo '<div class="w-100 adv-criteria-'. $this->metaDataId .'"  style="min-height: 350px;padding: 20px 40px;overflow: hidden;overflow-y: auto;">';
                    foreach ($this->advancedCriteria as $param) {
                        echo '<div class="col-md-12 pl0 pr0">';
                         ?>
                            <div class="form-group row dv-criteria-row">
                                <label class="col-form-label col-lg-4 text-right"><?php echo $this->lang->line($param['META_DATA_NAME']) ?></label>
                                <div class="col-lg-8">
                                    <div class="input-group input-group-criteria">
                                        <?php

                                        if ($param['LOOKUP_META_DATA_ID'] != '' && $param['LOOKUP_TYPE'] == 'combo' && $param['CHOOSE_TYPE'] != 'singlealways') {
                                            $param['CHOOSE_TYPE'] = 'multi';
                                        }

                                        if ($param['LOOKUP_META_DATA_ID'] == '' && $param['LOOKUP_TYPE'] == '' && ($param['META_TYPE_CODE'] === 'bigdecimal' || $param['META_TYPE_CODE'] === 'integer')) {
                                            echo Mdcommon::dataviewRenderCriteriaCondition(
                                                $param,     
                                                Mdwebservice::renderParamControl($this->metaDataId, $param, "param[".$param['META_DATA_CODE']."][]", $param['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false)),
                                                '=',
                                                'top'
                                            );
                                        } 
                                        else {
                                            echo Mdcommon::dataviewRenderCriteriaCondition(
                                                $param,     
                                                Mdwebservice::renderParamControl($this->metaDataId, $param, "param[".$param['META_DATA_CODE']."]", $param['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false)),
                                                '=',
                                                'top'
                                            );
                                        }

                                        ?> 
                                    </div>
                                </div>
                            </div>
                    <?php        
                        echo '</div>';
                    }
                echo '</div>';
                ?>
                <div class="w-100">
                    <div class="col-md-12 pl0 pr0">
                        <hr class="<?php echo $saveCriteriaTemplate ?>" />
                        <div class="form-group<?php echo $saveCriteriaTemplate ?>">
                            <div class="col-md-12">
                                <input type="checkbox" value="1" name="isSaveCriteriaTemplate" id="isSaveCriteriaTemplate" class="form-check-input-switchery form-check-input-switchery-<?php echo $this->metaDataId; ?> notuniform" data-fouc>
                                <?php echo $this->lang->line('criteriaTemplate') ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 pl0 pr0 d-none">
                        <div class="form-group criteria-template-hidden-<?php echo $this->metaDataId ?>">
                            <label for="criteriaTemplateName" class="col-form-label text-left"><span class="text-danger">*</span> <?php echo $this->lang->line('criteriaTemplateName') ?></label>
                            <div class="col-md-12">
                                <input type="text" name="criteriaTemplateName" id="criteriaTemplateName" class="form-control form-control-sm stringInit" placeholder="<?php echo $this->lang->line('criteriaTemplateName') ?>">
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <div class="tab-pane fade" id="bottom-tab2">
            <div class="col-md-12 pl0 pr0">
                <div class="form-group <?php echo $saveCriteriaTemplate ?>">
                    <label for="criteriaTemplatesEcommerce" class="col-form-label text-left"><?php echo $this->lang->line('criteriaTemplateList') ?></label>
                    <div>
                        <?php
                        echo Form::select(
                            array(
                                'name' => 'criteriaTemplates',
                                'id' => 'criteriaTemplatesEcommerce',
                                'text' => $this->lang->line('choose'),
                                'class' => 'form-control form-control-sm dropdownInput select2 refresh-criteria-template-' . $this->metaDataId,
                                'data' => array(),
                                'op_value' => 'ID',
                                'op_text' => 'NAME'
                            )
                        );
                        ?>                                        
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    echo '</form>';
}
?>
<script type="text/javascript">
    $('.form-check-input-switchery-<?php echo $this->metaDataId; ?>').on('change', function(){
        if ($(this).is(':checked')) {
            $('.criteria-template-hidden-<?php echo $this->metaDataId ?>').removeClass('hidden');
        } else {
            $('.criteria-template-hidden-<?php echo $this->metaDataId ?>').addClass('hidden');
        }
    });
    
    $('#criteriaTemplatesEcommerce', '.adv-criteria-form-<?php echo $this->metaDataId ?>').on('change', function() {
        var $lastCriteriaRow = $('.adv-criteria-<?php echo $this->metaDataId ?>');

        $.ajax({
            type: 'post',
            url: 'mdobject/getDataviewCriteriaTemplate',
            data: {
                'metaDataId': '<?php echo $this->metaDataId; ?>', 
                'id': $(this).val(), 
                'isadvancedCriteria': '1',
                'viewtype': 'ecommerce'
            },
            dataType: 'html',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {    
                if (data !== '') {                        
                    $lastCriteriaRow.empty().append(data).promise().done(function () {
                        Core.initDVAjax($lastCriteriaRow);
                    });                        
                }
                Core.unblockUI();
            },
            error: function() {
                alert('Error');
            }   
        });
    });
    $(document.body).on('select2-opening', 'select.refresh-criteria-template-<?php echo $this->metaDataId ?>', function(e, isTrigger) {
        var $this = $(this), 
            $relateElement = $this.prev('.select2-container:eq(0)');
            
        var $thisval = $this.val();
        if (!$this.hasClass("data-combo-set")) {
            var select2 = $this.data('select2');

            $this.addClass("data-combo-set");
            Core.blockUI({
                target: $relateElement,
                animate: false,
                icon2Only: true
            });

            var comboDatas = [];
            $.ajax({
                type: 'post',
                async: false,
                url: 'mdobject/getDataviewTemplateData',
                data: {'metaDataId': '<?php echo $this->metaDataId ?>'},
                dataType: 'json',
                success: function(data) {
                    if (data.length) { 
                        $this.empty();
                        $this.append($('<option />').val('').text(plang.get('choose')));  

                        $.each(data, function(){
                            $this.append($("<option />")
                                .val(this.ID)
                                .text(this.NAME));
                            comboDatas.push({
                                id: this.ID,
                                text: this.NAME
                            });                     
                        });
                    }
                },
                error: function () {
                    alert("Ajax Error!");
                } 
            }).done(function(){
                Core.unblockUI($relateElement);
                $this.select2({results: comboDatas, closeOnSelect: false});
                if (typeof isTrigger === 'undefined' && !select2.opened()) {
                    $this.select2('open');
                    if ($thisval) {
                        $this.select2('val', $thisval);
                    }
                    $this.removeClass("data-combo-set");
                }
            });
        }
    });        
</script>