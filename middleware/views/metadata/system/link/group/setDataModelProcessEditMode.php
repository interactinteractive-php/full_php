<div class="row" id="window-dataModelProcess">
    <div class="col-md-12">
        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#data-model-btn" class="nav-link active" data-toggle="tab">Процесс</a>
                </li>
                <li class="nav-item">
                    <a href="#data-model-batch" data-toggle="tab" class="nav-link">Багцлалт</a>
                </li>
            </ul>
            <div class="tab-content pb0">
                <div class="tab-pane active" id="data-model-btn">
                    <a href="javascript:;" class="btn green btn-xs mb10" onclick="addDMProcessDtl(this);">
                        <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('META_00103'); ?> 
                    </a>
                    <div id="fz-group-process-dtl" class="freeze-overflow-xy-auto" style="border: 1px solid #dddddd; max-height: 450px"> 
                        <table class="table table-hover table-small-header-text" id="group-process-dtl">
                            <thead>
                                <tr>
                                    <th>Процесс</th>
                                    <th style="width: 106px; min-width: 106px; max-width: 106px"><a href="javascript:;" class="pf-bp-labelname-toggle">Товчны нэр</a></th>
                                    <th class="text-center" style="width: 76px; min-width: 76px; max-width: 76px">Дүрс</th>
                                    <th class="text-center" style="width: 61px; min-width: 61px; max-width: 61px">Д/д</th>
                                    <th class="text-center" style="width: 61px; min-width: 61px; max-width: 61px">Багцын<br />дугаар</th>
                                    <th class="text-center pl0 pr0" style="width: 50px; min-width: 50px; max-width: 50px">Олон<br />сонгох</th>
                                    <th class="text-center pl0 pr0" style="width: 50px; min-width: 50px; max-width: 50px">Is<br />Confim</th>
                                    <th class="text-center pl0 pr0" style="width: 50px; min-width: 50px; max-width: 50px">Is lookup<br />Basket</th>
                                    <th class="text-center pl0 pr0" style="width: 50px; min-width: 50px; max-width: 50px">Is<br />Workflow</th>
                                    <th class="text-center pl0 pr0" style="width: 50px; min-width: 50px; max-width: 50px">Is<br />Main</th>
                                    <th class="text-center pl0 pr0" style="width: 50px; min-width: 50px; max-width: 50px">Is show<br />basket</th>
                                    <th class="text-center pl0 pr0" style="width: 50px; min-width: 50px; max-width: 50px">Is row<br />reload</th>
                                    <th class="text-center" style="width: 80px;min-width: 80px;max-width: 80px">Өнгө</th>
                                    <th style="width: 125px;min-width: 125px;max-width: 125px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($this->processDtl) {
                                    foreach ($this->processDtl as $row) {
                                        $processNameGlobe = Lang::line($row['PROCESS_NAME']);
                                ?>
                                <tr>
                                    <td class="middle">
                                        <div class="meta-autocomplete-wrap" data-params="">
                                            <div class="input-group double-between-input">
                                                <?php echo Form::hidden(array('name' => 'groupProcessDtlMetaId[' . $row['META_DATA_ID'] . '][]', 'value' => $row['META_DATA_ID'])); ?>
                                                <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" value="<?php echo $row['META_DATA_CODE']; ?>" type="text">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataGrid('single', 'metaObject', 'autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>|<?php echo Mdmetadata::$workSpaceMetaTypeId; ?>|<?php echo Mdmetadata::$bookmarkMetaTypeId; ?>', 'getDMProcessDtlChooseRow', this)"><i class="fa fa-search"></i></button>
                                                </span>
                                                <span class="input-group-btn not-group-btn">
                                                    <div class="btn-group pf-meta-manage-dropdown">
                                                        <button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>
                                                        <ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>
                                                    </div>
                                                </span>
                                                <span class="input-group-btn flex-col-group-btn">
                                                    <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo $row['META_DATA_NAME']; ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="middle">
                                        <span class="pf-bp-labelname-globe d-none"><?php echo $processNameGlobe; ?></span>
                                        <span class="pf-bp-labelname-input">
                                            <?php echo Form::text(array('name' => 'groupProcessDtlProcessName[' . $row['META_DATA_ID'] . '][]', 'value' => $row['PROCESS_NAME'], 'title' => $processNameGlobe, 'class' => 'form-control form-control-sm', 'style' => 'width: 90px;')); ?>
                                        </span>
                                    </td>
                                    <td class="text-center middle">
                                        <?php echo Form::hidden(array('name' => 'groupProcessDtlIconName[' . $row['META_DATA_ID'] . '][]', 'value' => $row['ICON_NAME'])); ?>
                                        <button type="button" class="btn btn-secondary btn-sm process-dtl-icon mr0" data-search-text="<?php echo $this->lang->line('META_00109'); ?>" data-placement="top" data-iconset="fontawesome5" data-cols="6" data-rows="6" data-icon="<?php echo $row['ICON_NAME']; ?>" role="iconpicker"></button>
                                    </td>
                                    <td class="text-center middle">
                                        <?php echo Form::text(array('name' => 'groupProcessDtlOrderNum[' . $row['META_DATA_ID'] . '][]', 'value' => $row['ORDER_NUM'], 'class' => 'form-control form-control-sm longInit', 'style' => 'width: 45px;')); ?>
                                    </td>
                                    <td class="text-center middle">
                                        <?php echo Form::text(array('name' => 'groupProcessDtlBatchNum[' . $row['META_DATA_ID'] . '][]', 'value' => $row['BATCH_NUMBER'], 'class' => 'form-control form-control-sm longInit', 'style' => 'width: 45px;')); ?>
                                    </td>
                                    <td class="text-center middle">
                                        <?php echo Form::checkbox(array('name' => 'groupProcessDtlIsMulti[' . $row['META_DATA_ID'] . '][]', 'value' => '1', 'saved_val' => $row['IS_MULTI'], 'class' => 'notuniform')); ?>
                                    </td>
                                    <td class="text-center middle">
                                        <?php echo Form::checkbox(array('name' => 'groupProcessDtlIsConfirm[' . $row['META_DATA_ID'] . '][]', 'value' => '1', 'saved_val' => $row['IS_CONFIRM'], 'class' => 'notuniform')); ?>
                                    </td>
                                    <td class="text-center middle">
                                        <?php echo Form::checkbox(array('name' => 'groupProcessDtlIsShowPopup[' . $row['META_DATA_ID'] . '][]', 'value' => '1', 'saved_val' => $row['IS_SHOW_POPUP'], 'class' => 'notuniform')); ?>
                                    </td>
                                    <td class="text-center middle">
                                        <?php echo Form::checkbox(array('name' => 'groupProcessDtlIsWorkFlow[' . $row['META_DATA_ID'] . '][]', 'value' => '1', 'saved_val' => $row['IS_WORKFLOW'], 'class' => 'notuniform')); ?>
                                    </td>
                                    <td class="text-center middle">
                                        <?php echo Form::checkbox(array('name' => 'groupProcessDtlIsMain[' . $row['META_DATA_ID'] . '][]', 'value' => '1', 'saved_val' => $row['IS_MAIN'], 'class' => 'notuniform')); ?>
                                    </td>
                                    <td class="text-center middle">
                                        <?php echo Form::checkbox(array('name' => 'groupProcessDtlIsBasket[' . $row['META_DATA_ID'] . '][]', 'value' => '1', 'saved_val' => $row['IS_SHOW_BASKET'], 'class' => 'notuniform')); ?>
                                    </td>
                                    <td class="text-center middle">
                                        <?php echo Form::checkbox(array('name' => 'groupProcessDtlIsRowUpdate[' . $row['META_DATA_ID'] . '][]', 'value' => '1', 'saved_val' => $row['IS_ROW_UPDATE'], 'class' => 'notuniform')); ?>
                                    </td>
                                    <td class="text-center middle">
                                        <?php
                                        echo
                                        '<select class="form-control form-control-sm" name="groupProcessDtlColor[' . $row['META_DATA_ID'] . '][]" id="buttonColor">';
                                        foreach ($this->colors as $value) {

                                            $selected = '';
                                            $id = $value['id'];
                                            $name = $value['name'];
                                            $class = $value['class'];

                                            if ($id == $row['BUTTON_STYLE']) {
                                                $selected = ' selected="selected"';
                                            }
                                            echo '<option value="' . $id . '" class="' . $class . '" ' . $selected . '>' . $name . '</option>';
                                        }
                                        echo '</select>';
                                        ?>
                                    </td>
                                    <td class="text-center middle">
                                        <?php 
                                        echo Form::hidden(array('name' => 'groupProcessDtlCriteria[' . $row['META_DATA_ID'] . '][]', 'value' => htmlentities($row['CRITERIA']))); 
                                        echo Form::hidden(array('name' => 'groupProcessDtlAdvancedCriteria[' . $row['META_DATA_ID'] . '][]', 'value' => htmlentities($row['ADVANCED_CRITERIA']))); 
                                        echo Form::hidden(array('name' => 'groupProcessDtlPostParam[' . $row['META_DATA_ID'] . '][]', 'value' => $row['POST_PARAM'])); 
                                        echo Form::hidden(array('name' => 'groupProcessDtlGetParam[' . $row['META_DATA_ID'] . '][]', 'value' => $row['GET_PARAM'])); 
                                        echo Form::hidden(array('name' => 'groupProcessDtlPasswordPath[' . $row['META_DATA_ID'] . '][]', 'value' => $row['PASSWORD_PATH']));
                                        echo Form::hidden(array('name' => 'groupProcessDtlOpenBP[' . $row['META_DATA_ID'] . '][]', 'value' => $row['IS_BP_OPEN']));
                                        echo Form::hidden(array('name' => 'groupProcessDtlOpenBPdefault[' . $row['META_DATA_ID'] . '][]', 'value' => $row['IS_BP_OPEN_DEFAULT']));
                                        echo Form::hidden(array('name' => 'groupProcessDtlIconColor[' . $row['META_DATA_ID'] . '][]', 'value' => $row['ICON_COLOR']));
                                        echo Form::hidden(array('name' => 'groupProcessShowPosition[' . $row['META_DATA_ID'] . '][]', 'value' => $row['SHOW_POSITION']));
                                        echo Form::hidden(array('name' => 'groupProcessDtlIsShowRowSelect[' . $row['META_DATA_ID'] . '][]', 'value' => $row['IS_SHOW_ROWSELECT']));
                                        echo Form::hidden(array('name' => 'groupProcessDtlUseProcessToolbar[' . $row['META_DATA_ID'] . '][]', 'value' => $row['IS_USE_PROCESS_TOOLBAR']));
                                        echo Form::hidden(array('name' => 'groupProcessDtlProcessToolbar[' . $row['META_DATA_ID'] . '][]', 'value' => $row['IS_PROCESS_TOOLBAR']));
                                        echo Form::hidden(array('name' => 'groupProcessDtlIsContextMenu[' . $row['META_DATA_ID'] . '][]', 'value' => $row['IS_CONTEXT_MENU']));
                                        echo Form::hidden(array('name' => 'groupProcessDtlIsRunLoop[' . $row['META_DATA_ID'] . '][]', 'value' => $row['IS_RUN_LOOP']));
                                        echo Form::textArea(array('name' => 'groupProcessDtlConfirmMsg[' . $row['META_DATA_ID'] . '][]', 'value' => $row['CONFIRM_MESSAGE'], 'style' => 'display: none')); 
                                        ?>
                                        <div class="process-transfer-configs display-none">
                                            <?php echo (new Mdmeta())->getDMTransferProcess($this->metaDataId, $row['META_DATA_ID']); ?>
                                        </div>
                                        <div class="process-row-transfer-configs display-none">
                                            <?php echo (new Mdmeta())->getDMRowProcess($this->metaDataId, $row['META_DATA_ID']); ?>
                                        </div>
                                        <div class="process-transfer-basket-configs display-none">
                                            <?php echo (new Mdmeta())->getDMTransferProcess($this->metaDataId, $row['META_DATA_ID'], true); ?>
                                        </div>
                                        <div class="process-transfer-automap-configs display-none">
                                            <?php 
                                            echo Form::hidden(array('name' => 'groupProcessDtlTransferAutoMapValue[' . $row['META_DATA_ID'] . '][]', 'value' => $row['IS_AUTO_MAP'])); 
                                            echo Form::hidden(array('name' => 'groupProcessDtlTransferAutoMapSrcValue[' . $row['META_DATA_ID'] . '][]', 'value' => $row['AUTO_MAP_SRC'])); 
                                            echo Form::hidden(array('name' => 'groupProcessDtlTransferAutoMapOnDeleteValue[' . $row['META_DATA_ID'] . '][]', 'value' => $row['AUTO_MAP_ON_DELETE'])); 
                                            echo Form::hidden(array('name' => 'groupProcessDtlTransferAutoMapOnUpdateValue[' . $row['META_DATA_ID'] . '][]', 'value' => $row['AUTO_MAP_ON_UPDATE'])); 
                                            echo Form::hidden(array('name' => 'groupProcessDtlTransferAutoMapSrcPath[' . $row['META_DATA_ID'] . '][]', 'value' => $row['AUTO_MAP_SRC_PATH'])); 
                                            echo Form::hidden(array('name' => 'groupProcessDtlTransferAutoMapTableName[' . $row['META_DATA_ID'] . '][]', 'value' => $row['AUTO_MAP_SRC_TABLE_NAME'])); 
                                            echo Form::hidden(array('name' => 'groupProcessDtlTransferDeleteMetaId[' . $row['META_DATA_ID'] . '][]', 'value' => $row['AUTO_MAP_DELETE_PROCESS_ID'])); 
                                            echo Form::hidden(array('name' => 'groupProcessDtlTransferListMetaId[' . $row['META_DATA_ID'] . '][]', 'value' => $row['AUTO_MAP_DATAVIEW_ID'])); 
                                            echo Form::hidden(array('name' => 'groupProcessDtlTransferPattern[' . $row['META_DATA_ID'] . '][]', 'value' => $row['AUTO_MAP_NAME_PATTERN'])); 
                                            echo Form::hidden(array('name' => 'groupProcessDtlTransferTrgPattern[' . $row['META_DATA_ID'] . '][]', 'value' => $row['AUTO_MAP_TRG_NAME_PATTERN'])); 
                                            ?>
                                        </div>
                                        <a href="javascript:;" class="btn purple-plum btn-xs" onclick="groupBpDtlTransferRow(this);">...</a>
                                        <a href="javascript:;" class="btn green btn-xs" onclick="groupBpDtlCriteriaRow(this);">...</a>
                                        <a href="javascript:;" class="btn btn-primary btn-xs mr0" onclick="groupBpDtlRequestParamRow(this);">...</a>
                                        <a href="javascript:;" class="btn btn-xs bg-yellow-gold mt5" onclick="groupBpDtlTransferAutoMap(this);" title="Auto Map тохиргоо">...</a>
                                        <a href="javascript:;" class="btn bg-yellow-lemon btn-xs mt5" onclick="groupBpDtlTransferRowInlineEdit(this);" title="Dataview Inline Edit Map Config">...</a>
                                        <a href="javascript:;" class="btn red btn-xs mr0 mt5" onclick="groupBpDtlRemoveRow(this);"><i class="far fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>  
                    </div> 
                </div>
                <div class="tab-pane" id="data-model-batch">
                    <a href="javascript:;" class="btn green btn-xs mb10" onclick="addBatchNumber(this);">
                        <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('META_00103'); ?> 
                    </a>
                    <div class="freeze-overflow-xy-auto" style="border: 1px solid #dddddd;"> 
                        <table class="table table-hover table-small-header-text" id="batch-number">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">Багцын дугаар</th>
                                    <th style="width: 300px;"><a href="javascript:;" class="pf-batch-labelname-toggle">Багцын нэр</a></th>
                                    <th style="width: 100px;" class="text-center"><?php echo $this->lang->line('meta_00197'); ?></th>
                                    <th style="width: 100px;" class="text-center">Is drop</th>
                                    <th style="width: 100px;" class="text-center">Is show popup</th>
                                    <th class="text-center">Өнгө</th>
                                    <th style="width: 70px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($this->batchDtl) {
                                    foreach ($this->batchDtl as $batch) {
                                        $batchNameGlobe = Lang::line($batch['BATCH_NAME']);
                                ?>
                                <tr> 
                                    <td class="middle">
                                        <input type="text" name="batchNumber[]" class="form-control form-control-sm longInit" value="<?php echo $batch['BATCH_NUMBER']; ?>">
                                    </td>
                                    <td>
                                        <span class="pf-batch-labelname-globe d-none"><?php echo $batchNameGlobe; ?></span>
                                        <span class="pf-batch-labelname-input">
                                            <input type="text" name="batchNumberName[]" class="form-control form-control-sm globeCodeInput" value="<?php echo $batch['BATCH_NAME']; ?>" title="<?php echo $batchNameGlobe; ?>">
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <input name="batchNumberIcon[]" type="hidden" value="<?php echo $batch['ICON_NAME']; ?>">
                                        <button type="button" class="btn btn-secondary btn-sm batch-number-icon" data-search-text="<?php echo $this->lang->line('META_00109'); ?>" data-placement="top" data-iconset="fontawesome5" data-cols="6" data-rows="6" data-icon="<?php echo $batch['ICON_NAME']; ?>" role="iconpicker"></button>
                                    </td>
                                    <td class="text-center middle">
                                        <input type="hidden" name="batchNumberIsDrop[]" value="<?php echo $batch['IS_DROPDOWN']; ?>">
                                        <input type="checkbox" class="notuniform" onclick="batchNumberDrop(this)"<?php echo ($batch['IS_DROPDOWN'] == '1') ? ' checked="checked"' : ''; ?>>
                                    </td>
                                    <td class="text-center middle">
                                        <input type="hidden" name="batchNumberIsShowPopup[]" value="<?php echo $batch['IS_SHOW_POPUP']; ?>">
                                        <input type="checkbox" class="notuniform" onclick="batchNumberShowPopup(this)"<?php echo ($batch['IS_SHOW_POPUP'] == '1') ? ' checked="checked"' : ''; ?>>
                                    </td>
                                    <td class="text-center middle">
                                        <?php
                                        echo
                                        '<select class="form-control form-control-sm" name="batchNumberColor[]" id="buttonColor">';
                                        foreach ($this->colors as $value) {
                                            $selected = "";
                                            $id = $value['id'];
                                            $class = $value['class'];
                                            $name = $value['name'];
                                            if ($id == $batch['BUTTON_STYLE']) {
                                                $selected = ' selected="selected"';
                                            }
                                            echo '<option value="' . $id . '" class="' . $class . '" ' . $selected . '>' . $name . '</option>';
                                        }
                                        echo '</select>';
                                        ?>
                                    </td>
                                    <td class="text-center middle">
                                        <a href="javascript:;" class="btn red btn-xs" onclick="batchNumberRemoveRow(this);"><i class="far fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="saveDataModelProcessDtl" value="1">

<script type="text/javascript">
    var bpNameGlobeToggle = false;
    var batchNameGlobeToggle = false;
    
    $(function() {
        
        $('table', 'div#fz-group-process-dtl').tableHeadFixer({'head': true, 'z-index': 9}); 
        
        setProcessDtlIcon();
        setBatchNumberIcon();
        
        $('button[role="iconpicker"]').iconpicker({
            arrowPrevIconClass: 'fa fa-arrow-left',
            arrowNextIconClass: 'fa fa-arrow-right'
        });
        
        $('.pf-batch-labelname-toggle').on('click', function() {
            var $this = $(this), $paramTable = $this.closest('table');
            if (batchNameGlobeToggle == false) {
                batchNameGlobeToggle = true;
                $paramTable.find('.pf-batch-labelname-input').addClass('d-none');
                $paramTable.find('.pf-batch-labelname-globe').removeClass('d-none');
            } else {
                batchNameGlobeToggle = false;
                $paramTable.find('.pf-batch-labelname-globe').addClass('d-none');
                $paramTable.find('.pf-batch-labelname-input').removeClass('d-none');
            }
        });
        
        $('.pf-bp-labelname-toggle').on('click', function() {
            var $this = $(this), $paramTable = $this.closest('table');
            if (bpNameGlobeToggle == false) {
                bpNameGlobeToggle = true;
                $paramTable.find('.pf-bp-labelname-input').addClass('d-none');
                $paramTable.find('.pf-bp-labelname-globe').removeClass('d-none');
            } else {
                bpNameGlobeToggle = false;
                $paramTable.find('.pf-bp-labelname-globe').addClass('d-none');
                $paramTable.find('.pf-bp-labelname-input').removeClass('d-none');
            }
        });
        
        $('#data-model-btn').on('change', 'input[name*="groupProcessDtlProcessName["]', function() {
            var $this = $(this), $parentRow = $this.closest('tr');
            var globeName = plang.get($this.val());
            
            $parentRow.find('.pf-bp-labelname-globe').html(globeName);
        });
        
        $('#data-model-batch').on('change', 'input[name="batchNumberName[]"]', function() {
            var $this = $(this), $parentRow = $this.closest('tr');
            var globeName = plang.get($this.val());
            
            $parentRow.find('.pf-batch-labelname-globe').html(globeName);
        });
        
        $("#data-model-btn, #data-model-batch").on("change", "select#buttonColor", function() {
            var thisVal = this.value;
            var $this = $(this);
            var selectedOption = $this.find("option[value='"+thisVal+"']");
            var bgColor = selectedOption.css("background-color");
            var fontColor = selectedOption.css("color");
            $this.css({"background-color": bgColor, "color": fontColor});  
        });
        
        $("tbody tr", "table#group-process-dtl").on("click", function() {
            var $this = $(this);
            var $table = $this.closest('tbody');
            $table.find('tr').removeClass('selected');
            $this.addClass('selected');
        });
        
        $(document.body).on("change", "input[name*='groupProcessDtlMetaId']", function() {
            var $this = $(this);
            
            var processDtlTbl = $("table#group-process-dtl tbody");            
            var isAddRow = 0, thisVal = $this.val();
            
            processDtlTbl.find("tr").each(function() {
                if ($(this).find("input[name*='groupProcessDtlMetaId[']").val() === thisVal) {
                    isAddRow++;
                }
            });

            if (isAddRow <= 1) {
                var $topTr = $this.closest('tr');
                var rowProcessId = $topTr.find("input[name*='groupProcessDtlMetaId']").attr('name').match(/\[(.*?)\]/g);
                rowProcessId = rowProcessId.map(function(match) { return match.slice(1, -1); });
                
                $topTr.find('input, select, textarea').each(function(){
                    var $AttrName = $(this).attr('name');
                    if ($AttrName) {
                        if ($AttrName.indexOf("__") !== -1) {
                            $(this).attr('name', $AttrName.replace('__', thisVal));
                        } else {
                            $(this).attr('name', $AttrName.replace(rowProcessId[0], thisVal));
                        }
                    }
                });
            }            
        });
    });
    
    function groupBpDtlRemoveRow(elem) {
        var $parentRow = $(elem).closest("tr");
        $parentRow.remove();
    }
    function groupBpDtlCriteriaRow(elem) {
        var _this = $(elem);
        var _parentCell = _this.closest("td");
        var $dialogName = 'dialog-dm-criteria';

        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: 'mdmeta/processDtlCriteriaProcess',
            data: {
                groupBpDtlCriteria: _parentCell.find("input[name*='groupProcessDtlCriteria']").val(),
                groupBpDtlAdvancedCriteria: _parentCell.find("input[name*='groupProcessDtlAdvancedCriteria']").val(), 
                groupBpDtlConfirmMsg: _parentCell.find("textarea[name*='groupProcessDtlConfirmMsg']").val()
            },
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {
                $("#" + $dialogName).empty().append(data.Html);
                $("#" + $dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 800,
                    minWidth: 800,
                    height: "auto",
                    modal: true,
                    close: function() {
                        $("#" + $dialogName).empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {
                            groupBpDtlAdvancedCriteriaEditor.save();
                            _parentCell.find("input[name*='groupProcessDtlCriteria']").val($("#groupBpDtlCriteria", "#" + $dialogName).val());
                            _parentCell.find("input[name*='groupProcessDtlAdvancedCriteria']").val(groupBpDtlAdvancedCriteriaEditor.getValue());
                            _parentCell.find("textarea[name*='groupProcessDtlConfirmMsg']").val($("#groupBpDtlConfirmMsg", "#" + $dialogName).val());
                            $("#" + $dialogName).dialog('close');
                        }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                            $("#" + $dialogName).dialog('close');
                        }}
                    ]
                });
                $("#" + $dialogName).dialog('open');
                Core.unblockUI();
            }
        });
    }
    function groupBpDtlRequestParamRow(elem) {
        var $this = $(elem);
        var $parentCell = $this.closest("td");
        var $dialogName = 'dialog-dm-request-param';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $("#" + $dialogName);
                                                
        $.ajax({
            type: 'post',
            url: 'mdmeta/processDtlRequestParam',
            data: {
                groupProcessDtlPostParam: $parentCell.find("input[name*='groupProcessDtlPostParam']").val(), 
                groupProcessDtlGetParam: $parentCell.find("input[name*='groupProcessDtlGetParam']").val(), 
                groupProcessDtlPasswordPath: $parentCell.find("input[name*='groupProcessDtlPasswordPath']").val(),
                groupProcessDtlOpenBP: $parentCell.find("input[name*='groupProcessDtlOpenBP']").val(),
                groupProcessDtlOpenBPdefault: $parentCell.find("input[name*='groupProcessDtlOpenBPdefault']").val(),
                groupProcessDtlIconColor: $parentCell.find("input[name*='groupProcessDtlIconColor']").val(),
                groupProcessShowPosition: $parentCell.find("input[name*='groupProcessShowPosition']").val(),
                groupProcessDtlIsShowRowSelect: $parentCell.find("input[name*='groupProcessDtlIsShowRowSelect']").val(), 
                groupProcessDtlIsContextMenu: $parentCell.find("input[name*='groupProcessDtlIsContextMenu']").val(), 
                groupProcessDtlIsRunLoop: $parentCell.find("input[name*='groupProcessDtlIsRunLoop']").val(),
                groupProcessDtlUseProcessToolbar: $parentCell.find("input[name*='groupProcessDtlUseProcessToolbar']").val(),
                groupProcessDtlProcessToolbar: $parentCell.find("input[name*='groupProcessDtlProcessToolbar']").val()
            },
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
                if (!$().colorpicker) {
                    $.cachedScript('assets/custom/addon/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js').done(function() {      
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-colorpicker/css/colorpicker.css"/>');
                    });
                }              
            },
            success: function(data) {
                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 800,
                    minWidth: 800,
                    height: "auto",
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {
                            $parentCell.find("input[name*='groupProcessDtlPostParam']").val($("#groupProcessDtlPostParam", "#" + $dialogName).val());
                            $parentCell.find("input[name*='groupProcessDtlGetParam']").val($("#groupProcessDtlGetParam", "#" + $dialogName).val());
                            $parentCell.find("input[name*='groupProcessDtlPasswordPath']").val($("#groupProcessDtlPasswordPath", "#" + $dialogName).val());
                            $parentCell.find("input[name*='groupProcessDtlOpenBP']").val($("#groupProcessDtlOpenBP", "#" + $dialogName).is(':checked') ? '1' : '');
                            $parentCell.find("input[name*='groupProcessDtlOpenBPdefault']").val($("#groupProcessDtlOpenBPdefault", "#" + $dialogName).is(':checked') ? '1' : '');
                            $parentCell.find("input[name*='groupProcessDtlIconColor']").val($("#groupProcessDtlIconColor", "#" + $dialogName).val());
                            $parentCell.find("input[name*='groupProcessShowPosition']").val($("#groupProcessShowPosition", "#" + $dialogName).val());
                            $parentCell.find("input[name*='groupProcessDtlIsShowRowSelect']").val($("#groupProcessDtlIsShowRowSelect", "#" + $dialogName).is(':checked') ? '1' : '');
                            $parentCell.find("input[name*='groupProcessDtlUseProcessToolbar']").val($("#groupProcessDtlUseProcessToolbar", "#" + $dialogName).is(':checked') ? '1' : '');
                            $parentCell.find("input[name*='groupProcessDtlProcessToolbar']").val($("#groupProcessDtlProcessToolbar", "#" + $dialogName).is(':checked') ? '1' : '');
                            $parentCell.find("input[name*='groupProcessDtlIsContextMenu']").val($("#groupProcessDtlIsContextMenu", "#" + $dialogName).is(':checked') ? '1' : '');
                            $parentCell.find("input[name*='groupProcessDtlIsRunLoop']").val($("#groupProcessDtlIsRunLoop", "#" + $dialogName).is(':checked') ? '1' : '');
                            $dialog.dialog('close');
                        }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            }
        }).done(function(){
            Core.initUniform($dialog);
        });
    }
    function groupBpDtlTransferRow(elem) {
        var $this = $(elem);
        var $parentCell = $this.closest("td");
        var $dialogName = 'dialog-grouprelation';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $("#" + $dialogName);
        
        $.ajax({
            type: 'post',
            url: 'mdmeta/processDtlTransferProcess',
            data: {
                metaDataId: '<?php echo $this->metaDataId; ?>',
                processMetaDataId: $parentCell.closest("tr").find("input[name*='groupProcessDtlMetaId']").val(),
                transferProcessData: $parentCell.find("div.process-transfer-configs").find("input").serialize(),
                transferBasketProcessData: $parentCell.find("div.process-transfer-basket-configs").find("input").serialize()
            },
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {
                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1000,
                    minWidth: 1000,
                    height: "auto",
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {
                            $("#processDtlTransfer-form").validate({errorPlacement: function() {}});

                            if ($("#processDtlTransfer-form").valid()) {
                                var getProcessId = $parentCell.closest("tr").find("input[name*='groupProcessDtlMetaId[']").val();
                                var paramTbl = $("table.process-dtl-transfer-tbl tbody").find("tr");
                                var paramConfigs = "";
                                paramTbl.each(function() {
                                    var $thisRow = $(this);
                                    var groupProcessDtlTransferParamPath = $thisRow.find("select[name*=groupProcessDtlTransferParamPath]").val();
                                    
                                    if (groupProcessDtlTransferParamPath !== '') {
                                        paramConfigs += '<input name="groupProcessDtlTransferGetMetaId[' + getProcessId + '][]" value="' + $thisRow.find("input[name*=groupProcessDtlTransferGetMetaId]").val() + '" type="hidden">';
                                        paramConfigs += '<input name="groupProcessDtlTransferViewPath[' + getProcessId + '][]" value="' + $thisRow.find("select[name*=groupProcessDtlTransferViewPath]").val() + '" type="hidden">';
                                        paramConfigs += '<input name="groupProcessDtlTransferParamPath[' + getProcessId + '][]" value="' + groupProcessDtlTransferParamPath + '" type="hidden">';
                                        paramConfigs += '<input name="groupProcessDtlTransferDefaultValue[' + getProcessId + '][]" value="' + $thisRow.find("input[name*=groupProcessDtlTransferDefaultValue]").val() + '" type="hidden">';
                                    }
                                });
                                $parentCell.find("div.process-transfer-configs").html(paramConfigs);
                                
                                var paramBasketTbl = $("table.process-transfer-basket-configs-tbl tbody").find("tr");
                                var paramBasketConfigs = "";
                                
                                paramBasketTbl.each(function() {
                                    var $thisRow = $(this);
                                    
                                    paramBasketConfigs += '<input name="groupProcessDtlBasketTransferViewPath[' + getProcessId + '][]" value="' + $thisRow.find("select[name*=groupProcessDtlBasketTransferViewPath]").val() + '" type="hidden">';
                                    paramBasketConfigs += '<input name="groupProcessDtlBasketTransferParamPath[' + getProcessId + '][]" value="' + $thisRow.find("input[name*=groupProcessDtlBasketTransferParamPath]").val() + '" type="hidden">';
                                    paramBasketConfigs += '<input name="groupProcessDtlBasketTransferDefaultValue[' + getProcessId + '][]" value="' + $thisRow.find("input[name*=groupProcessDtlBasketTransferDefaultValue]").val() + '" type="hidden">';
                                });
                                $parentCell.find("div.process-transfer-basket-configs").html(paramBasketConfigs);
                                
                                $dialog.dialog('close');
                            }
                        }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            }
        }).done(function() {
            Core.initAjax($dialog);
        });
    }
    function groupBpDtlTransferRowInlineEdit(elem) {
        var _this = $(elem);
        var _parentCell = _this.closest("td");
        var $dialogName = 'dialog-grouprelation-inline-edit';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: 'mdmeta/processDtlTransferProcessInlineEdit',
            data: {
                metaDataId: '<?php echo $this->metaDataId; ?>',
                processMetaDataId: _parentCell.closest("tr").find("input[name*='groupProcessDtlMetaId']").val(),
                transferProcessData: _parentCell.find("div.process-row-transfer-configs").find("input").serialize()
            },
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {
                $("#" + $dialogName).empty().append(data.Html);
                $("#" + $dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1000,
                    minWidth: 1000,
                    height: "auto",
                    modal: true,
                    close: function() {
                        $("#" + $dialogName).empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {
                            $("#processDtlRowTransfer-form").validate({errorPlacement: function() {}});

                            if ($("#processDtlRowTransfer-form").valid()) {
                                var getProcessId = _parentCell.closest("tr").find("input[name*='groupProcessDtlMetaId[']").val();
                                var paramTbl = $("table.process-dtl-row-transfer-tbl tbody").find("tr");
                                var paramConfigs = "";
                                paramTbl.each(function() {
                                    var _thisRow = $(this);
                                    var groupProcessDtlTransferParamPath = _thisRow.find("select[name*=groupProcessDtlTransferProcessParamPath]").val();
                                    
                                    if (groupProcessDtlTransferParamPath !== '') {
                                        paramConfigs += '<input name="groupProcessDtlTransferDataViewPath[' + getProcessId + '][]" value="' + _thisRow.find("select[name*=groupProcessDtlTransferDataViewPath]").val() + '" type="hidden">';
                                        paramConfigs += '<input name="groupProcessDtlTransferProcessParamPath[' + getProcessId + '][]" value="' + groupProcessDtlTransferParamPath + '" type="hidden">';
                                    }
                                });
                                _parentCell.find("div.process-row-transfer-configs").html(paramConfigs);
                                
                                $("#" + $dialogName).dialog('close');
                            }
                        }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                            $("#" + $dialogName).dialog('close');
                        }}
                    ]
                });
                $("#" + $dialogName).dialog('open');
                Core.unblockUI();
            }
        }).done(function() {
            Core.initAjax($("#" + $dialogName));
        });
    }
    function groupBpDtlTransferAutoMap(elem) {
        var _this = $(elem);
        var _parentCell = _this.closest("td");
        var $dialogName = 'dialog-groupAutoMap';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $("#" + $dialogName);
        
        $.ajax({
            type: 'post',
            url: 'mdmeta/processDtlTransferAutoMap',
            data: {
                metaDataId: '<?php echo $this->metaDataId; ?>',
                processMetaDataId: _parentCell.closest("tr").find("input[name*='groupProcessDtlMetaId']").val(),
                transferProcessData: _parentCell.find("div.process-transfer-automap-configs").find("input").serialize()
            },
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1000,
                    minWidth: 850,
                    height: "auto",
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {
                            $("#processDtlTransferAutoMap-form").validate({errorPlacement: function() {}});

                            if ($("#processDtlTransferAutoMap-form").valid()) {
                                
                                var getProcessId = _parentCell.closest("tr").find("input[name*='groupProcessDtlMetaId[']").val();
                                var paramTbl = $("table.process-dtl-transfer-automap-tbl tbody").find("tr");
                                var paramConfigs = '', cascadeDeleteProcess = true;
                                
                                paramTbl.each(function() {
                                    var _thisRow = $(this);
                                    var groupProcessDtlTransferParamPath = _thisRow.find("select[name*=groupProcessDtlTransferParamPath]").val();
                                    
                                    if (_thisRow.find("input[name*=groupProcessDtlTransferAutoMapValue]").is(':checked')) {
                                        if (_thisRow.find("select[name*=groupProcessDtlTransferAutoMapOnDeleteValue]").val() === 'cascade' || _thisRow.find("select[name*=groupProcessDtlTransferAutoMapOnUpdateValue]").val() === 'cascade') {
                                            if (_thisRow.find("input[name*=groupProcessDtlTransferDeleteMetaId]").val() == '') {
                                                PNotify.removeAll();
                                                new PNotify({
                                                    title: 'Warning',
                                                    text: 'CASCADE нөхцөл сонгосон үед УСТГАХ ПРОЦЕСС заавал сонгоно.',
                                                    type: 'warning',
                                                    sticker: false
                                                });                  
                                                cascadeDeleteProcess = false;
                                            }
                                        }
                                    }
                                    
                                    if (groupProcessDtlTransferParamPath !== '') {
                                        paramConfigs += '<input name="groupProcessDtlTransferAutoMapValue[' + getProcessId + '][]" value="' + (_thisRow.find("input[name*=groupProcessDtlTransferAutoMapValue]").is(':checked') ? '1' : '0') + '" type="hidden"/>';
                                        paramConfigs += '<input name="groupProcessDtlTransferAutoMapSrcValue[' + getProcessId + '][]" value="' + (_thisRow.find("input[name*=groupProcessDtlTransferAutoMapSrcValue]").is(':checked') ? '1' : '0') + '" type="hidden"/>';
                                        paramConfigs += '<input name="groupProcessDtlTransferAutoMapOnDeleteValue[' + getProcessId + '][]" value="' + _thisRow.find("select[name*=groupProcessDtlTransferAutoMapOnDeleteValue]").val() + '" type="hidden"/>';
                                        paramConfigs += '<input name="groupProcessDtlTransferAutoMapOnUpdateValue[' + getProcessId + '][]" value="' + _thisRow.find("select[name*=groupProcessDtlTransferAutoMapOnUpdateValue]").val() + '" type="hidden"/>';
                                        paramConfigs += '<input name="groupProcessDtlTransferAutoMapSrcPath[' + getProcessId + '][]" value="' + _thisRow.find("select[name*=groupProcessDtlTransferAutoMapSrcPath]").val() + '" type="hidden"/>';
                                        paramConfigs += '<input name="groupProcessDtlTransferAutoMapTableName[' + getProcessId + '][]" value="' + _thisRow.find("input[name*=groupProcessDtlTransferAutoMapTableName]").val() + '" type="hidden"/>';
                                        paramConfigs += '<input name="groupProcessDtlTransferDeleteMetaId[' + getProcessId + '][]" value="' + _thisRow.find("input[name*=groupProcessDtlTransferDeleteMetaId]").val() + '" type="hidden"/>';
                                        paramConfigs += '<input name="groupProcessDtlTransferListMetaId[' + getProcessId + '][]" value="' + _thisRow.find("input[name*=groupProcessDtlTransferListMetaId]").val() + '" type="hidden"/>';
                                        paramConfigs += '<input name="groupProcessDtlTransferPattern[' + getProcessId + '][]" value="' + _thisRow.find("input[name*=groupProcessDtlTransferPattern]").val() + '" type="hidden"/>';
                                        paramConfigs += '<input name="groupProcessDtlTransferTrgPattern[' + getProcessId + '][]" value="' + _thisRow.find("input[name*=groupProcessDtlTransferTrgPattern]").val() + '" type="hidden"/>';
                                    }
                                });
                                _parentCell.find("div.process-transfer-automap-configs").html(paramConfigs);

                                if (cascadeDeleteProcess) {
                                    $dialog.dialog('close');
                                }
                            }
                        }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            }
        }).done(function() {
            Core.initAjax($dialog);
        });
    }

    function addDMProcessDtl(elem) {
        getDMProcessDtlAddRow();
        $('#fz-group-process-dtl').animate({
            scrollTop: $("table#group-process-dtl").height()
        }, 1000);
    }

    function getDMProcessDtlAddRow() {
        var $processDtlTbl = $("table#group-process-dtl > tbody");
        var isAddRow = true;
        
        $processDtlTbl.find("tr").each(function() {
            if ($(this).find("input[name*='groupProcessDtlMetaId[']").val() === '__') {
                isAddRow = false;
            }
        });

        if (isAddRow) {
            
            var processNameHtml = '<span class="pf-bp-labelname-globe d-none"></span>';
            processNameHtml += '<span class="pf-bp-labelname-input">';
                processNameHtml += '<input type="text" name="groupProcessDtlProcessName[__][]" class="form-control form-control-sm" style="width: 90px;">';
            processNameHtml += '</span>';
                    
            $processDtlTbl.append('<tr>' +
                '<td class="middle">' + 
                    '<div class="meta-autocomplete-wrap" data-params="">' +
                        '<div class="input-group double-between-input">' +
                            '<input type="hidden" name="groupProcessDtlMetaId[__][]" value="">' +
                            '<input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">' +
                            '<span class="input-group-btn">' +
                                '<button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataGrid(\'single\', \'metaObject\', \'autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>|<?php echo Mdmetadata::$workSpaceMetaTypeId; ?>|<?php echo Mdmetadata::$bookmarkMetaTypeId; ?>\', \'getDMProcessDtlChooseRow\', this)"><i class="fa fa-search"></i></button>' +
                            '</span>' +     
                            '<span class="input-group-btn not-group-btn">' +
                                '<div class="btn-group pf-meta-manage-dropdown">' +
                                    '<button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>' +
                                    '<ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>' +
                                '</div>' + 
                            '</span>' + 
                            '<span class="input-group-btn flex-col-group-btn">' +
                                '<input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">' +
                            '</span>' + 
                        '</div>' +
                    '</div>' +
                '</td>' +
                '<td>' + processNameHtml + '</td>' +
                '<td class="text-center">' +
                    '<input name="groupProcessDtlIconName[__][]" value="fa-plus" type="hidden">' +
                    '<button type="button" class="btn btn-secondary btn-sm process-dtl-icon" data-search-text="<?php echo $this->lang->line('META_00109'); ?>" data-placement="top" data-iconset="fontawesome5" data-cols="5" data-icon="" role="iconpicker"></button>' +
                '</td>' +
                '<td class="text-center middle">' +
                    '<input type="text" name="groupProcessDtlOrderNum[__][]" class="form-control form-control-sm longInit" style="width: 45px;">' +
                '</td>' +
                '<td class="text-center middle">' +
                    '<input type="text" name="groupProcessDtlBatchNum[__][]" class="form-control form-control-sm longInit" style="width: 45px;">' +
                '</td>' +
                '<td class="text-center middle">' +
                    '<input type="checkbox" name="groupProcessDtlIsMulti[__][]" value="1" class="notuniform">' +
                '</td>' +
                '<td class="text-center middle">' +
                    '<input type="checkbox" name="groupProcessDtlIsConfirm[__][]" value="1" class="notuniform">' +
                '</td>' +
                '<td class="text-center middle">' +
                    '<input type="checkbox" name="groupProcessDtlIsShowPopup[__][]" value="1" class="notuniform">' +
                '</td>' +
                '<td class="text-center middle">' +
                    '<input type="checkbox" name="groupProcessDtlIsWorkFlow[__][]" value="1" class="notuniform">' +
                '</td>' +
                '<td class="text-center middle">' +
                    '<input type="checkbox" name="groupProcessDtlIsMain[__][]" value="1" class="notuniform">' +
                '</td>' +
                '<td class="text-center middle">' +
                    '<input type="checkbox" name="groupProcessDtlIsBasket[__][]" value="1" class="notuniform">' +
                '</td>' +
                '<td class="text-center middle">' +
                    '<input type="checkbox" name="groupProcessDtlIsRowUpdate[__][]" value="1" class="notuniform">' +
                '</td>' +
                '<td class="text-center middle">' +
                '<select class="form-control form-control-sm" name="groupProcessDtlColor[__][]" id="buttonColor">' +
                    setButtonColors() +
                '</select>' +
                '</td>' +
                '<td class="text-center middle">' +
                    '<input type="hidden" name="groupProcessDtlCriteria[__][]">' +
                    '<input type="hidden" name="groupProcessDtlAdvancedCriteria[__][]">' +
                    '<input type="hidden" name="groupProcessDtlPostParam[__][]">' +
                    '<input type="hidden" name="groupProcessDtlGetParam[__][]">' +
                    '<input type="hidden" name="groupProcessDtlPasswordPath[__][]">' +
                    '<input type="hidden" name="groupProcessDtlOpenBP[__][]">' +
                    '<input type="hidden" name="groupProcessDtlOpenBPdefault[__][]">' +
                    '<input type="hidden" name="groupProcessDtlIconColor[__][]">' +
                    '<input type="hidden" name="groupProcessShowPosition[__][]">' +
                    '<input type="hidden" name="groupProcessDtlIsShowRowSelect[__][]">' +
                    '<input type="hidden" name="groupProcessDtlUseProcessToolbar[__][]">' +
                    '<input type="hidden" name="groupProcessDtlProcessToolbar[__][]">' +
                    '<input type="hidden" name="groupProcessDtlIsContextMenu[__][]">' +
                    '<input type="hidden" name="groupProcessDtlIsRunLoop[__][]">' +
                    '<textarea name="groupProcessDtlConfirmMsg[__][]" style="display: none"></textarea>' +
                    '<div class="process-transfer-configs display-none"></div>' +
                    '<div class="process-transfer-basket-configs display-none"></div>' +
                    '<div class="process-transfer-automap-configs display-none"></div>' +
                    '<a href="javascript:;" class="btn purple-plum btn-xs" onclick="groupBpDtlTransferRow(this);">...</a> ' +
                    '<a href="javascript:;" class="btn green btn-xs" onclick="groupBpDtlCriteriaRow(this);">...</a> ' +
                    '<a href="javascript:;" class="btn btn-primary btn-xs" onclick="groupBpDtlRequestParamRow(this);">...</a> ' + 
                    '<a href="javascript:;" class="btn bg-yellow-gold btn-xs" onclick="groupBpDtlTransferAutoMap(this);" title="Auto Map тохиргоо">...</a> ' +
                    '<a href="javascript:;" class="btn red btn-xs mr0" onclick="groupBpDtlRemoveRow(this);"><i class="far fa-trash"></i></a>' +
                '</td>' +
            '</tr>');
        
        } else {
            alert('Choose Process!');
        }
        
        var $lastRow = $processDtlTbl.find('tr:last-child');
        
        Core.initUniform($lastRow);
        Core.initLongInput($lastRow);
        
        $lastRow.find('button[role="iconpicker"]').iconpicker({
            arrowPrevIconClass: 'fa fa-arrow-left',
            arrowNextIconClass: 'fa fa-arrow-right'
        });
        setProcessDtlIcon();
    }
    function getDMProcessDtlChooseRow(chooseType, elem, params, _this) {
        var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
        if (metaBasketNum > 0) {
            var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
            var processDtlTbl = $("table#group-process-dtl tbody");            
            var row = rows[0];
            var isAddRow = true;
            
            processDtlTbl.find("tr").each(function() {
                if ($(this).find("input[name*='groupProcessDtlMetaId[']").val() === row.META_DATA_ID) {
                    isAddRow = false;
                }
            });

            if (isAddRow) {
                var $topTr = $(_this).closest('tr');
                var rowProcessId = $topTr.find("input[name*='groupProcessDtlMetaId']").val();
                $topTr.find("input[name*='groupProcessDtlMetaId']").val(row.META_DATA_ID);
                $topTr.find("#_displayField").val(row.META_DATA_CODE);
                $topTr.find("#_nameField").val(row.META_DATA_NAME);
                
                $topTr.find('input, select, textarea').each(function(){
                    var $AttrName = $(this).attr('name');
                    if ($AttrName) {
                        if ($AttrName.indexOf("__") !== -1) {
                            $(this).attr('name', $AttrName.replace('__', row.META_DATA_ID));
                        } else {
                            $(this).attr('name', $AttrName.replace(rowProcessId, row.META_DATA_ID));
                        }
                    }
                });
            }
        }        
    }
    function addBatchNumber() {
        var $batchNumberTbl = $('table#batch-number > tbody');
        
        var batchNameHtml = '<span class="pf-batch-labelname-globe d-none"></span>';
        batchNameHtml += '<span class="pf-batch-labelname-input">';
            batchNameHtml += '<input type="text" name="batchNumberName[]" class="form-control form-control-sm globeCodeInput">';
        batchNameHtml += '</span>';
                                        
        $batchNumberTbl.append('<tr>' +
            '<td class="middle">' +
                '<input type="text" name="batchNumber[]" class="form-control form-control-sm longInit">' +
            '</td>' +
            '<td>' + batchNameHtml + '</td>' +
            '<td class="text-center">' +
                '<input name="batchNumberIcon[]" type="hidden">' +
                '<button type="button" class="btn btn-secondary btn-sm batch-number-icon" data-search-text="<?php echo $this->lang->line('META_00109'); ?>" data-placement="top" data-iconset="fontawesome5" data-cols="5" data-icon="" role="iconpicker"></button>' +
            '</td>' +
            '<td class="text-center middle">' +
                '<input type="hidden" name="batchNumberIsDrop[]" value="0">' +
                '<input type="checkbox" onclick="batchNumberDrop(this)" class="notuniform">' +
            '</td>' +
            '<td class="text-center middle">' +
                '<input type="hidden" name="batchNumberIsShowPopup[]" value="0">' +
                '<input type="checkbox" onclick="batchNumberShowPopup(this)" class="notuniform">' +
            '</td>' +
            '<td class="text-center middle">' +
                '<select class="form-control form-control-sm" name="batchNumberColor[]" id="buttonColor">' +
                setButtonColors() +
                '</select>' +
            '</td>' +
            '<td class="text-center middle">' +
                '<a href="javascript:;" class="btn red btn-xs" onclick="batchNumberRemoveRow(this);"><i class="far fa-trash"></i></a>' +
            '</td>' +
        '</tr>');
        
        var $lastRow = $batchNumberTbl.find('tr:last-child');
        
        Core.initUniform($lastRow);
        Core.initLongInput($lastRow);
        
        $lastRow.find('button[role="iconpicker"]').iconpicker({
            arrowPrevIconClass: 'fa fa-arrow-left',
            arrowNextIconClass: 'fa fa-arrow-right'
        });
        setBatchNumberIcon();
    }
    function setBatchNumberIcon() {
        $('.batch-number-icon').on('change', function(e) {
            var $parentRow = $(this).closest("tr");
            if (e.icon === 'empty' || e.icon === 'fa-empty') {
                $parentRow.find("input[name='batchNumberIcon[]']").val('');
            } else {
                $parentRow.find("input[name='batchNumberIcon[]']").val(e.icon);
            }
        });
    }
    function setProcessDtlIcon() {
        $('.process-dtl-icon').on('change', function(e) {
            var $parentRow = $(this).closest("tr");
            if (e.icon === 'empty' || e.icon === 'fa-empty') {
                $parentRow.find("input[name*='groupProcessDtlIconName']").val('');
            } else {
                $parentRow.find("input[name*='groupProcessDtlIconName']").val(e.icon);
            }
        });
    }
    function batchNumberRemoveRow(elem) {
        var $parentRow = $(elem).closest("tr");
        $parentRow.remove();
    }
    function batchNumberDrop(elem) {
        var $this = $(elem);
        var $row = $this.parents('tr');
        var batchNumberIsDrop = $row.find('input[name="batchNumberIsDrop[]"]');

        if ($this.prop('checked')) {
            batchNumberIsDrop.val(1);
        } else {
            batchNumberIsDrop.val(0);
        }
    }
    function batchNumberShowPopup(elem) {
        var $this = $(elem);
        var $row = $this.parents('tr');
        var batchNumberIsShowPopup = $row.find('input[name="batchNumberIsShowPopup[]"]');
        if ($this.prop('checked')) {
            batchNumberIsShowPopup.val(1);
        } else {
            batchNumberIsShowPopup.val(0);
        }
    }
    function setButtonColors() {
        var options = '';
        <?php
        foreach ($this->colors as $color) {
            echo "options += '<option value=\"".$color['id']."\" class=\"".$color['class']."\">".$color['name']."</option>';"."\n";
        }
        ?>
        return options;
    }
</script>