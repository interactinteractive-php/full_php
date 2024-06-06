<div class="xs-form" id="print-dropdown-settings">
    <table class="table table-sm table-no-bordered">
        <?php echo $this->options; ?>
        <tr>
            <td colspan="2" style="padding: 5px 0;"></td>
        </tr>
        <?php 
        if (!isset($this->isIgnoreTemplates)) { 
            
            if (isset($this->templateGroup) && $this->templateGroup) {
        ?>
        <tr>
            <td class="middle" colspan="2">
                <div class="row">
                    <span class="col-md-12" style="color:#a6a6a6; font-weight: bold;"> 
                        Темплейтийн бүлэг:
                    </span>
                    <div class="col-md-12">
                        <?php
                        echo Form::select(
                            array( 
                                'id' => 'reportTemplateGroup',
                                'class' => 'form-control select2 form-control-sm input-xxlarge',
                                'data' => $this->templateGroup,
                                'op_value' => 'id',
                                'op_text' => 'name'
                            )
                        );
                        ?>
                    </div>
                </div>    
            </td>
        </tr>    
        <?php
            }
        ?>
        <tr>
            <td class="middle" colspan="2">
                <div class="row">
                    <span class="col-md-12" style="color:#a6a6a6; font-weight: bold;"> 
                        <?php echo $this->lang->lineDefault('PRINT_0017', 'Темплейт'); ?>:
                    </span>
                    <div class="col-md-12">
                        <?php 
                        $data = isset($this->reportTemplate) ? $this->reportTemplate : array();
                        $rtTemplateIds = '';
                        $selectedAttr = array();
                        $countTemp = count($data);
                        
                        if ($countTemp == 1) {
                            $selectedAttr = array('value' => $data[0]['ID']);
                            $rtTemplateIds = $data[0]['ID'];
                        }
                        
                        if (isset($this->userRow['templates']) && is_countable($this->userRow['templates']) && count($this->userRow['templates'])) {
                            $selectedAttr = array('value' => implode(',', $this->userRow['templates']));
                            $rtTemplateIds = $selectedAttr['value'];
                        }
                        
                        if (isset($this->userRow['templateIds'])) {
                            $selectedAttr = array('value' => $this->userRow['templateIds']);
                            $rtTemplateIds = $selectedAttr['value'];
                        }

                        if (isset($this->userRow['isPrintSaveTemplate']) && $this->userRow['isPrintSaveTemplate'] == '1') {
                            $selectedAttr = array();
                        }
                        
                        if (isset($this->defaultTemplateId) && $this->defaultTemplateId) {
                            $selectedAttr = array('value' => $this->defaultTemplateId);
                            $rtTemplateIds = $this->defaultTemplateId;
                        }
                        
                        if ($this->metaDataId == '1541036120643826' || $this->metaDataId == '1540461788635') {
                            
                            if ($this->templateChooseType == 'button') {
                                
                                if ($rtTemplateIds) {
                                    $rtTemplateIds = ','.$rtTemplateIds.',';
                                }

                                foreach ($data as $row) {

                                    $selected = 'btn-outline';

                                    if ($rtTemplateIds && strpos($rtTemplateIds, ','.$row['ID'].',') !== false) {
                                        $selected = '';
                                    }

                                    echo '<button type="button" class="btn '.$selected.' bg-primary border-primary text-primary-800 mt4 rtChooseByButton" data-id="'.$row['ID'].'">'.$row['META_DATA_NAME'].'</button>';
                                }
                            
                            } else {
                                
                                echo Form::select(
                                    array_merge(  
                                        array(
                                            'name' => 'printTemplate[]', 
                                            'id' => 'printTemplate',
                                            'required' => 'required',
                                            'class' => 'form-control select2 form-control-sm input-xxlarge',
                                            'data' => $data,
                                            'op_value' => 'ID',
                                            'op_text' => 'META_DATA_NAME', 
                                            'op_custom_attr' => array(array('key' => 'TEMPLATE_GROUP_ID', 'attr' => 'group'))
                                        ), $selectedAttr
                                    )
                                );
                            }
                            
                        } else {
                            
                            echo Form::multiselect(
                                array_merge(  
                                    array(
                                        'name' => 'printTemplate[]', 
                                        'id' => 'printTemplate',
                                        'multiple' => 'multiple',
                                        'required' => 'required',
                                        'class' => 'form-control select2 form-control-sm input-xxlarge',
                                        'data' => $data,
                                        'op_value' => 'ID',
                                        'op_text' => 'META_DATA_NAME', 
                                        'op_custom_attr' => array(array('key' => 'TEMPLATE_GROUP_ID', 'attr' => 'group'))
                                    ), $selectedAttr
                                )
                            );
                        }
                        
                        $templateMetaIds = '';
                        if ($data) {
                            foreach ($data as $tempRow) {
                                $templateMetaIds .= $tempRow['TEMPLATE_META_DATA_ID'] . '_';
                            }
                            $templateMetaIds = trim($templateMetaIds, '_');
                        }
                        
                        echo Form::hidden(array('name' => 'rtTemplateIds', 'id' => 'rtTemplateIds', 'value' => $rtTemplateIds));
                        echo Form::hidden(array('name' => 'templateMetaIds', 'id' => 'templateMetaIds', 'value' => $templateMetaIds));
                        ?>
                    </div>
                </div>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>  

<style type="text/css">
.select2-results .select2-disabled {
    display: none;
}
</style>

<script type="text/javascript">
$(function() {
    
    Core.initInputType($('#print-dropdown-settings'));
    
    $("#printTemplate").closest('div').find('ul').find('li:first').find('input').attr('style', 'width:379px;');

    $("#isPrintNewPage").click(function() {
        if ($("#isPrintNewPage").is(":checked")) {
            $("#isPrintNewPage").val(1);
        } else {
            $("#isPrintNewPage").val(0);
        }
    });
    
    $("#isShowPreview").change(function() {
        
        var $dialog = $('#dialog-printSettings'), 
            $dialogButtonPane = $dialog.closest('.ui-dialog').find('.ui-dialog-buttonpane');
        
        if ($("#isShowPreview").is(":checked")) {
            $("#isShowPreview").val(1);
            $dialogButtonPane.find('.bp-btn-print, .bp-btn-pdf-export, .bp-btn-word-export').addClass('d-none');
            $dialogButtonPane.find('.bp-btn-preview').removeClass('d-none');
        } else {
            $("#isShowPreview").val(0);
            $dialogButtonPane.find('.bp-btn-print, .bp-btn-pdf-export, .bp-btn-word-export').removeClass('d-none');
            $dialogButtonPane.find('.bp-btn-preview').addClass('d-none');
        }
    });
    
    $("#isShowPreview").trigger('change');
    
    $('#printTemplate').on('change', function() {
        var $this = $(this);
        
        if ($this.is('[multiple]')) {
            var templateData = $this.select2('data');
            $('#rtTemplateIds').val(templateData.map(function(td){ return td.id; }).join(','));
        } else {
            $('#rtTemplateIds').val($this.val());
        }
    });
    
    $('#reportTemplateGroup').on('change', function() {
        var $this = $(this);
        var groupId = $this.select2('val');
        var $printTemplate = $('#printTemplate');
        
        $printTemplate.select2('destroy');
        $printTemplate.find('[disabled]').removeAttr('disabled');
        $printTemplate.find('[selected]').removeAttr('selected');
        $('#rtTemplateIds').val('');
        
        if (groupId) {
            $printTemplate.find('option:not([group="'+groupId+'"])').prop('disabled', true);
        }
        
        Core.initSelect2($this.closest('tr').next('tr:eq(0)'));
    });
    
    $('.rtChooseByButton').on('click', function(){
        var $this = $(this);
        var rtId = $this.attr('data-id');
        var $rtTemplateIds = $('#rtTemplateIds');
        var rtTemplateIds = $rtTemplateIds.val();
        
        if ($this.hasClass('btn-outline')) {
            $this.removeClass('btn-outline');
            rtTemplateIds = rtrim(rtTemplateIds, ',');
            $rtTemplateIds.val(rtTemplateIds + ',' + rtId + ',');
        } else {
            $this.addClass('btn-outline');
            rtTemplateIds = rtTemplateIds.replace(','+rtId+',', ',');
            if (rtTemplateIds == ',') {
                rtTemplateIds = '';
            }
            $rtTemplateIds.val(rtTemplateIds);
        }
    });
    
    <?php
    if ($this->rowClass == ' d-none') {
    ?>
    $('#isSettingsDialog').click(function() {
        if ($(this).is(':checked')) {
            $('.rt-notseen-option').addClass('d-none');
        } else {
            $('.rt-notseen-option').removeClass('d-none');
        }
        
        $('#dialog-printSettings').dialog('option', 'position', {my: 'center', at: 'center', of: window});
    });   
    <?php
    }
    ?>
            
    $('.rt-more-options-showhide').on('click', function() {
        var $this = $(this);
        var $i = $this.find('i');
        
        if ($i.hasClass('icon-arrow-up5')) {
            $i.removeClass('icon-arrow-up5').addClass('icon-arrow-down5');
            $('.rt-more-options').show();
        } else {
            $i.removeClass('icon-arrow-down5').addClass('icon-arrow-up5');
            $('.rt-more-options').hide();
        }
        
        $('#dialog-printSettings').dialog('option', 'position', {my: 'center', at: 'center', of: window});
    });
});
</script>    