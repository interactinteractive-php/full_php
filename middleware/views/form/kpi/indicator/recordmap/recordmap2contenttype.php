<?php
    echo Form::hidden(array('name' => 'isKpiComponent', 'value' => '1'));
    
    $add_btn = $this->lang->line('add_btn');
    $view_btn = $this->lang->line('view_btn');
    $delete_btn = $this->lang->line('delete_btn');
    $basketClickAction = '';
    
    if ($this->components) {
      $groupComp = Arr::groupByArray($this->components, $this->viewType); 
      
      foreach ($groupComp as $rowGroupKey => $rowGroupVal) {
          if (empty($rowGroupKey)) continue;
      ?>
        <div class="col-6 view-type" onclick="relationListViewTypeMore('<?php echo str_replace([' ', '=', '.'], ['', '-', ''], $rowGroupKey) ?>', '<?php echo $rowGroupKey ?>')">
            <div class="reldetail mt-2">                       
                <div class="d-flex align-items-center align-items-md-start flex-column flex-md-row pt-2">
                    <h5 class="reltitle line-height-normal font-size-14 font-weight-bold cursor-pointer text-select-none" style="-ms-flex: 1;flex: 1;">
                        <?php echo $rowGroupKey.' ('.count($rowGroupVal['rows']).')'; ?>                  
                        <span class="ml-1 font-size-12" style="font-weight: normal;color: #404040;"></span>
                    </h5>
                </div>
            </div>
        </div>
      <?php          
      foreach ($rowGroupVal['rows'] as $row) {
          
          if ($row['CODE'] != '' && Mdform::$defaultTplSavedId) {
              
              $columnsData = (new Mdform_Model())->getKpiIndicatorColumnsModel($this->indicatorId, array('isIgnoreStandardFields' => true));
              $fieldConfig = (new Mdform_Model())->getKpiIndicatorIdFieldModel($this->indicatorId, $columnsData);
              
              if (isset($fieldConfig['codeField']) 
                  && $fieldConfig['codeField'] != '' 
                  && isset(Mdform::$kpiDmMart[$fieldConfig['codeField']]) 
                  && Str::lower(Mdform::$kpiDmMart[$fieldConfig['codeField']]) != Str::lower($row['CODE'])) {
                  
                  continue;
              }
          }

        if (isset($this->fromWebLink)) {
            $basketClickAction = 'chooseKpiIndicatorRowsFromBasket(this, \''.$row['ID'].'\', \'multi\', \'kpiIndicatorMainRelationFillRows\');';
        } else {
            $basketClickAction = 'chooseKpiIndicatorRowsFromBasket(this, \''.$row['ID'].'\', \'multi\');';
        }          

        if (issetParam($row['LOOKUP_META_DATA_ID'])) {
            $basketClickAction = 'dataViewSelectableGrid(\'nullmeta\', 0, \''.$row['LOOKUP_META_DATA_ID'].'\', \'multi\', \'nullmeta\', this, \'kpiIndicatorMainRelationMetaFillRows\');';
        }
        
      ?>
        <div class="col-6 view-type-all view-type-<?php echo str_replace([' ', '=', '.'], ['', '-', ''], $rowGroupKey) ?> d-none">
            <div class="reldetail mt-2" data-rowid="<?php echo $row['MAP_ID'] ?>" data-userid="<?php echo issetParam($row['CREATED_USER_ID']) ?>">                       
                <div class="d-flex align-items-center align-items-md-start flex-column flex-md-row pt-2">
                    <h5 class="reltitle line-height-normal font-size-14 font-weight-bold cursor-pointer text-select-none" style="-ms-flex: 1;flex: 1;" onclick="kpiIndicatorRelationCollapse(this);">
                        <i class="far fa-angle-down"></i> <?php echo $row['NAME']; ?>                  
                        <span class="ml-1 font-size-12" style="font-weight: normal;color: #404040;">- <?php echo $row['SEMANTIC_TYPE_NAME']; ?></span>
                    </h5>

                    <div class="input-group quick-item-process float-left" style="margin-top: -6px;">
                        <div class="input-icon meta-autocomplete-wrap mv-popup-control d-none">
                            <i class="far fa-search"></i>
                            <?php
                            echo Form::text(array(
                                'class' => 'form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete',
                                'id' => $row['ID'].'_nameField', 
                                'style' => 'padding-left:25px;border-top-left-radius: 0.1875rem;border-bottom-left-radius: 0.1875rem;',
                                'data-processid' => $row['ID'],
                                'data-lookupid' => $row['ID'],
                                'placeholder' => 'Хайх'
                            ));
                            echo Form::hidden(array('id' => $row['ID'].'_valueField', 'data-listen-path' => 1, 'data-lookupid' => $row['ID']));
                            ?>
                        </div>
                        <span class="">
                            <?php 
                              $configArr = array('isAddonForm' => $row['IS_ADDON_FORM'], 'metaInfoIndicatorId' => $row['META_INFO_INDICATOR_ID']);
                              echo Form::button(array(
                                  'class' => 'btn btn-xs green-meadow',
                                  'value' => '<i class="icon-plus3 font-size-12"></i>', 
                                  'data-config' => htmlentities(str_replace('&quot;', '\\&quot;', json_encode($configArr)), ENT_QUOTES, 'UTF-8'), 
                                  'onclick' => $basketClickAction
                              ));
                            ?>
                        </span>
                    </div>

                </div>

                <table class="table table-sm table-hover mv-record-map-tbl" style="border-top: 1px #ddd solid;">
                    <tbody>
                        <?php
                        if (isset($this->savedComponentRows[$row['ID']]) || isset($this->savedComponentRows[$row['LOOKUP_META_DATA_ID']])) {

                          if (isset($this->savedComponentRows[$row['ID']])) {
                            $childRows = $this->savedComponentRows[$row['ID']];
                          } else {
                              $childRows = $this->savedComponentRows[$row['LOOKUP_META_DATA_ID']];
                          }

                          if ($childRows) {

                              foreach ($childRows as $childRow) {
                          ?>

                              <tr data-basketrowid="<?php echo $childRow['PF_MAP_RECORD_ID']; ?>">
                                  <td style="height: 25px; max-width: 0;" class="text-left text-truncate">
                                      <input type="hidden" name="metaDmRecordMaps[indicatorId][]" value="<?php echo $row['ID']; ?>">
                                      <input type="hidden" name="metaDmRecordMaps[recordId][]" value="<?php echo $childRow['PF_MAP_RECORD_ID']; ?>">
                                      <input type="hidden" name="metaDmRecordMaps[mapId][]" value="<?php echo $childRow['PF_MAP_ID']; ?>">
                                      <input type="hidden" name="metaDmRecordMaps[rowState][]" value="saved">
                                      <input type="hidden" name="metaDmRecordMaps[childRecordId][]" value="<?php echo $childRow['PF_MAP_TRG_RECORD_ID']; ?>">
                                      <textarea class="d-none" name="metaDmRecordMaps[childRowData][]"><?php echo json_encode($childRow, JSON_UNESCAPED_UNICODE); ?></textarea>

                                      <?php if ($row['SEMANTIC_TYPE_ID'] == 10000011) { ?>
                                        <a href="javascript:;" onclick="callWebServiceByMeta('<?php echo $childRow['PF_MAP_RECORD_ID']; ?>', true, '', false, '');" class="font-size-14" title="<?php echo $view_btn; ?>">
                                            <i style="color:blue" class="<?php echo issetParam($row['SEMANTIC_TYPE_ICON']) ? $row['SEMANTIC_TYPE_ICON'] : 'far fa-file-search'; ?> mr-1"></i>
                                            <?php echo Lang::line($childRow['PF_MAP_NAME']); ?>
                                        </a>
                                      <?php } else { ?>
                                        <a href="javascript:;" onclick="bpCallKpiIndicatorForm(this, this, '<?php echo $row['ID']; ?>', '<?php echo $childRow['PF_MAP_RECORD_ID']; ?>', 'view');" class="font-size-14" title="<?php echo $view_btn; ?>">
                                            <i style="color:blue" class="<?php echo issetParam($row['SEMANTIC_TYPE_ICON']) ? $row['SEMANTIC_TYPE_ICON'] : 'far fa-file-search'; ?> mr-1"></i>
                                            <?php echo Lang::line($childRow['PF_MAP_NAME']); ?>
                                        </a>                                
                                      <?php } ?>
                                  </td>
                                  <td style="width: 60px" class="text-right">
                                      <?php
                                      if ($row['IS_ADDON_FORM'] && $row['META_INFO_INDICATOR_ID']) {
                                      ?>
                                      <a href="javascript:;" onclick="kpiIndicatorRelationSubRows(this, '<?php echo $row['META_INFO_INDICATOR_ID']; ?>', '<?php echo $childRow['PF_MAP_TRG_RECORD_ID']; ?>');" class="font-size-16 mr-3" title="Холбоос"><i style="color:#5c6bc0;" class="far fa-external-link-square"></i></a>
                                      <?php
                                      }
                                      ?>
                                      <a href="javascript:;" onclick="kpiIndicatorRelation2RemoveRows(this, '<?php echo $childRow['PF_MAP_ID']; ?>');" class="font-size-14" title="<?php echo $delete_btn; ?>"><i style="color:red" class="far fa-trash"></i></a>
                                  </td>
                              </tr>

                          <?php
                              }
                            }
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
      <?php
      }
      }
    } else {
      echo '<div class="mt-2">'.$this->lang->line('msg_no_record_found').'</div>';
    }
?>