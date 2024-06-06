<div class="col-md-12">
    <div class="row">
        <div class="form-body xs-form popup-parent-tag" id="gl_meta_row_<?php echo $this->uniqId; ?>">
            <table class="table table-sm table-no-bordered mb0" style="margin-top: -5px; table-layout: fixed !important">  
                <?php
                $segmentSeparator = '';
                
                foreach ($this->segmentList as $k => $value) {
                    
                    $fillParamData = array();
                    $lowerPath = strtolower($value['PARAM_REAL_PATH']);
                    
                    if (isset($this->segmentSplit[$k]) && $value['LOOKUP_META_DATA_ID'] != '') {
                        
                        $attributes = Mdobject_Model::getDataViewMetaValueAttributes('', '', $value['LOOKUP_META_DATA_ID']);
                        
                        $idField    = strtolower($attributes['id']);
                        $codeField  = strtolower($attributes['code']);
                        
                        $criteria = array();
                        
                        $criteria[$codeField][] = array(
                            'operator' => '=',
                            'operand' => $this->segmentSplit[$k]
                        );
                        
                        $idCodeName = Mdobject_Model::getDataViewByCriteriaModel($value['LOOKUP_META_DATA_ID'], $criteria, $idField, 'code');
                        
                        if ($idCodeName) {
                            
                            $nameField = strtolower($attributes['name']);
                            
                            $fillParamData[$lowerPath] = array(
                                'id' => $idField ? $idCodeName[$idField] : (isset($idCodeName['id']) ? $idCodeName['id'] : ''),
                                'code' => isset($idCodeName[$codeField]) ? html_entity_decode($idCodeName[$codeField], ENT_QUOTES, 'UTF-8') : '',
                                'name' => isset($idCodeName[$nameField]) ? html_entity_decode($idCodeName[$nameField], ENT_QUOTES, 'UTF-8') : '', 
                                'rowdata' => htmlentities(json_encode($idCodeName), ENT_QUOTES, 'UTF-8')
                            );
                        }
                    }

                    echo '<tr data-cell-path="'.$value['PARAM_REAL_PATH'].'" data-segment-row="1">';
                        echo '<td class="text-right middle" style="width: 28%"><label data-label-path="'.$value['PARAM_REAL_PATH'].'">'.$this->lang->line($value['LABEL_NAME']).':</label></td>'. 
                             '<td class="middle" style="width: 72%">';
                        echo $metaControllers = Mdwebservice::renderParamControl(Mdgl::$glBookDtlGroupProcessId, $value, 'accountSegment['.$this->path.']['.$lowerPath.']', '', $fillParamData);
                        echo '<input type="hidden" name="accountSegment['.$this->path.']['.$lowerPath.'_segmentCode]" data-segment-code="'.$value['PARAM_REAL_PATH'].'">';
                        echo '<input type="hidden" name="accountSegment['.$this->path.']['.$lowerPath.'_segmentSeparator]" value="'.$value['SEPRATOR_CHAR'].'">';
                        echo '<input type="hidden" name="accountSegment['.$this->path.']['.$lowerPath.'_segmentReplaceValue]" value="'.$value['REPLACE_VALUE'].'">';
                        echo '</td>';
                    echo '</tr>';  

                    $segmentSeparator .= $value['SEPRATOR_CHAR'].'<span data-st-path="'.$value['PARAM_REAL_PATH'].'" class="gl-segment-part">'.str_repeat('_', strlen($value['REPLACE_VALUE'])).'</span>';
                }

                echo '<tr>
                        <td class="text-right pt5">Dimension:</td>
                        <td class="text-left pt5 segment-separator-val">'.$segmentSeparator.'</td>
                    </tr>';  
                ?>
            </table>
            <input type="hidden" name="accountSegmentFullCode[<?php echo $this->path; ?>]">
        </div>
    </div>
</div>

<script type="text/javascript">
    var $row_<?php echo $this->uniqId; ?> = $('div#gl_meta_row_<?php echo $this->uniqId; ?>');

    Core.initNumberInput($row_<?php echo $this->uniqId; ?>);
    Core.initDateInput($row_<?php echo $this->uniqId; ?>);
    Core.initLongInput($row_<?php echo $this->uniqId; ?>);
    Core.initSelect2WidthAutoFalse($row_<?php echo $this->uniqId; ?>);
    Core.initUniform($row_<?php echo $this->uniqId; ?>);
    Core.initRegexMaskInput($row_<?php echo $this->uniqId; ?>);
    Core.initAccountCodeMask($row_<?php echo $this->uniqId; ?>);
        
    $(function() {
        
        $row_<?php echo $this->uniqId; ?>.find('[readonly]').removeAttr('readonly');
        
        $row_<?php echo $this->uniqId; ?>.find('tr[data-segment-row="1"]').each(function(){
            var $segmentRow = $(this), segmentPath = $segmentRow.attr('data-cell-path'), 
                segId = $segmentRow.find('input.popupInit').val();
            
            if (segId) {
                var segCode = $segmentRow.find('input.lookup-code-autocomplete').val(), 
                    segName = $segmentRow.find('input.lookup-name-autocomplete').val(), 
                    $segmentInput = $segmentRow.find('input[data-segment-code]');
                
                $segmentInput.val(segCode+'|'+segName);
                $row_<?php echo $this->uniqId; ?>.find('span[data-st-path="'+segmentPath+'"]').text(segCode);
            }
        });
        
        $row_<?php echo $this->uniqId; ?>.on('change', 'input.popupInit', function(){
            var $this = $(this), $parent = $this.closest('.meta-autocomplete-wrap'), 
                $parentCell = $this.closest('td'), 
                $segmentInput = $parentCell.find('input[data-segment-code]'), 
                segmentPath = $segmentInput.attr('data-segment-code'), 
                $parentRow = $this.closest('tr'), 
                segCode = '', segName = '', 
                $nextRow = $parentRow.next('tr[data-cell-path]:visible:eq(0)');
            
            if ($segmentInput.length) {       
                if ($parent.find('input[type="hidden"]').val() != '') {
                    segCode = $parent.find('input.lookup-code-autocomplete').val(), 
                    segName = $parent.find('input.lookup-name-autocomplete').val();
                        
                    $segmentInput.val(segCode+'|'+segName);
                } else {
                    segCode = '__';
                    $segmentInput.val('');
                }
                
                $row_<?php echo $this->uniqId; ?>.find('span[data-st-path="'+segmentPath+'"]').text(segCode);
            }
            
            $row_<?php echo $this->uniqId; ?>.find('input[name*="accountSegmentFullCode["]').val($row_<?php echo $this->uniqId; ?>.find('.segment-separator-val').text());
            
            if ($nextRow.length) {
                $nextRow.find('input:visible:first').focus().select();
            } else {
                $parentCell.closest('.ui-dialog').find('button.bp-btn-save').focus();
            }
        });
        
        $row_<?php echo $this->uniqId; ?>.find('input[name*="accountSegmentFullCode["]').val($row_<?php echo $this->uniqId; ?>.find('.segment-separator-val').text());
    });
</script>