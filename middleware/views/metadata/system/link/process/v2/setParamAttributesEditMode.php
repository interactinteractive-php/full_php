<div class="row">
    <div class="col-md-12 params-new-config-parent">
        
        <div class="table-toolbar xs-form mb5">
            <div class="row">
                <div class="col-md-7">
                    <div class="input-group quick-item float-left">
                        <div class="form-group-feedback form-group-feedback-left">
                            <?php echo Form::text(array('id' => 'processParamAddCode', 'class' => 'form-control process-param-add-code', 'placeholder' => $this->lang->line('META_00154'))); ?>
                            <div class="form-control-feedback form-control-feedback-lg">
                                <i class="fa fa-search"></i>
                            </div>
                        </div>
                        <span class="input-group-append">
                            <?php echo Form::button(array('class' => 'btn green-meadow', 'value' => '<i class="icon-plus3 font-size-12"></i>', 'onclick' => 'commonMetaDataGrid(\'multi\', \'group\', \'autoSearch=1&metaTypeId='.Mdmetadata::$metaGroupMetaTypeId.'|'.Mdmetadata::$fieldMetaTypeId.'\', \'processParamAddRows\', this);')); ?>
                        </span>
                    </div>
                    <?php 
                    echo Form::button(array('class' => 'btn btn-light btn-xs float-left ml50', 'value' => '<i class="fa fa-cogs"></i> '.$this->lang->line('pf_multi_field_config'), 'onclick' => 'setProcessConfigsMultiFieldForm(this);')); 
                    echo Form::button(array('class' => 'btn red-sunglo btn-xs float-left ml10', 'value' => '<i class="fa fa-trash"></i> '.$this->lang->line('META_00002'), 'onclick' => 'deleteProcessParamRows(this);')); 
                    ?>
                </div>
            </div>
        </div>
        
        <table style="width: 100%; table-layout: fixed">
            <tr>
                <td style="width: 100%; vertical-align: top;">
                    <div id="fz-process-params-option" class="freeze-overflow-xy-auto" style="border: 1px solid #dddddd;">
                        <table class="table table-sm table-hover params-new-config param-link-tree">
                            <thead>
                                <tr>
                                    <th class="middle text-left" style="min-width: 55px; max-width: 55px; width: 55px;"><input type="checkbox" class="notuniform param-check-all"></th>
                                    <th class="middle" style="min-width: 200px;">Path</th>
                                    <th style="min-width: 200px;"><?php echo $this->lang->line('META_00075'); ?></th>
                                    <th style="min-width: 130px;"><?php echo $this->lang->line('META_00145'); ?></th>
                                    <th style="min-width: 150px;"><a href="javascript:;" class="pf-params-labelname-toggle"><?php echo $this->lang->line('META_00076'); ?></a></th>
                                    <th style="min-width: 62px; vertical-align: top;"><label><input type="checkbox" class="notuniform param-check-all"><br /><?php echo $this->lang->line('META_00003'); ?></label></th>
                                    <th style="min-width: 60px; vertical-align: top;"><label><input type="checkbox" class="notuniform param-check-all"><br /><?php echo $this->lang->line('META_00121'); ?></label></th>
                                    <th style="min-width: 81px">Lookup <?php echo $this->lang->line('META_00145'); ?></th>
                                    <th style="min-width: 78px"><?php echo $this->lang->line('metadata_choose_type'); ?></th>
                                    <th style="min-width: 259px;"><?php echo $this->lang->line('META_00004'); ?></th>
                                    <th>Display field</th>
                                    <th>Value field</th>
                                    <th style="min-width: 150px"><?php echo $this->lang->line('META_00005'); ?></th>
                                    <th style="min-width: 170px;"><?php echo $this->lang->line('metadata_tab_name'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo $this->paramsRender; ?>
                            </tbody>
                        </table>  
                    </div>   
                </td>
                <td style="width: 370px; vertical-align: top; padding-left: 10px">
                    <div class="params-addon-config" style="overflow: auto">
                        <!--<h4 class="mt0">Нэмэлт тохиргоо</h4>-->
                    </div>
                </td>
            </tr>
        </table>
         
    </div>
</div>

<style type="text/css">
table.params-new-config thead tr th {
    background: #E7E7E7;
    font-size: 12px !important;
    height: 23px !important;
    vertical-align: middle;
    line-height: 13px;
    text-align: center;
    border-bottom-width: 0;
}
table.params-new-config {
    color: #444 !important;
}
table.params-new-config thead tr th label {
    font-weight: normal; 
    font-size: 12px;
    margin-top: 0;
}
table.params-new-config > tbody > tr > td {
    font-size: 12px;
    line-height: 13px;
    padding: 4px;
    vertical-align: middle;
}
table.params-new-config > tbody > tr > td.stretchInput {
    padding: 0 !important;
}
table.params-new-config > tbody > tr > td.stretchInput input.meta-name-autocomplete{
    min-width: 140px !important;
}
table.params-new-config td .btn, 
table.params-new-config th .btn {
    margin-left: 3px;
    margin-right: 0;
    padding: 2px 5px;
}
table.params-new-config td div.input-group .btn {
    margin-left: 0;
    margin-right: 0;
}
table.params-new-config > tbody > tr.currentTarget > td {
    border-bottom: 1px solid #888;
}
.depth-pl0 {
    padding-left: 10px !important;
}
.depth-padding-left-1 {
    padding-left: 25px !important;
}
.depth-padding-left-2 {
    padding-left: 40px !important;
}
.depth-padding-left-3 {
    padding-left: 56px !important;
}
.depth-padding-left-4 {
    padding-left: 85px !important;
}
.depth-pl5 {
    padding-left: 105px !important;
}
.depth-padding-left-6 {
    padding-left: 120px !important;
}
.deleted-row > td {
    background-color: #e67171 !important;
}
.param-row-up-down {
    cursor: move !important;
}
</style>

<script type="text/javascript">
$(function(){
    
    var $dialogId = $('.params-new-config-parent');
    var lastChecked = null;
    var labelNameGlobeToggle = false;
    
    processParamInitFreeze();
    
    $.contextMenu({
        selector: 'table.param-link-tree > tbody > tr:not(.deleted-row)',
        callback: function(key, opt) {
            processParamDeleteRow(opt.$trigger); 
        },
        items: {
            "delete": {name: "<?php echo $this->lang->line('META_00002'); ?>", icon: "trash"} 
        }
    });
    
    $.contextMenu({
        selector: 'table.param-link-tree > tbody > tr.deleted-row',
        callback: function(key, opt) {
            processParamRefreshRow(opt.$trigger);
        },
        items: {
            "refresh": {name: "<?php echo $this->lang->line('META_00020'); ?>", icon: "sync"} 
        }
    });
    
    $dialogId.on('click', '.pf-params-labelname-toggle', function() {
        var $this = $(this), $paramTable = $this.closest('table');
        if (labelNameGlobeToggle == false) {
            labelNameGlobeToggle = true;
            $paramTable.find('.pf-params-labelname-input').addClass('d-none');
            $paramTable.find('.pf-params-labelname-globe').removeClass('d-none');
        } else {
            labelNameGlobeToggle = false;
            $paramTable.find('.pf-params-labelname-globe').addClass('d-none');
            $paramTable.find('.pf-params-labelname-input').removeClass('d-none');
        }
    });
    
    $('.param-check-all').on('click', function() {
        var $this = $(this);
        var $paramTable = $this.closest('table');
        var $paramCol = $this.closest('tr').children().index($this.closest('th'));
        var $paramIndex = $paramCol + 1;
        $paramTable.find('td:nth-child(' + $paramIndex + ') input:checkbox').prop('checked', $this.is(':checked'));
        $paramTable.find('.process-param-ischange').val('1');
    });
    
    $('table.param-link-tree > tbody').on('click', 'tr', function(){
        var $this = $(this);

        $('table.param-link-tree tbody > tr.selected').removeClass('selected');
        $this.addClass('selected');
        
        var $paramsContainer = $this.closest('.params-new-config-parent').find('.params-addon-config');
        var $paramPath = $this.find('input.process-param-path').val();
        
        if ($paramsContainer.find("div[data-path='"+$paramPath+"']").length) {
            
            $paramsContainer.find('div[data-path]').css({display: 'none'});
            $paramsContainer.find("div[data-path='"+$paramPath+"']").css({display: ''});
            $dialogId.find('input.autonumber').trigger('change');
            
        } else {
            
            var isGroup = false, fieldDataType = $this.find('select.process-param-datatype').val();
            if ($this.hasAttr('data-id')) {
                isGroup = true;
            }
            
            var postData = {
                processMetaDataId: '<?php echo $this->metaDataId; ?>', 
                paramPath: $paramPath, 
                depth: $this.attr('data-depth'), 
                dataType: fieldDataType, 
                isGroup: isGroup, 
                isNew: $this.find('input.process-param-isnew').val(), 
                lookupMetaDataId: $this.find('#lookupMetaDataId').val(), 
                lookupType: $this.find('.bp-input-field-lookuptype').val() 
            };
            
            $.ajax({
                type: 'post', 
                url: 'mdmetadata/getProcessParamAddon', 
                async: false, 
                data: postData, 
                success: function(dataHtml) {
                    
                    $paramsContainer.find('div[data-path]').css({display: 'none'});
                    $paramsContainer.append("<div data-path='"+$paramPath+"'>" + dataHtml + '</div>').promise().done(function () {
                        
                        var $appendDiv = $paramsContainer.find('[data-path]:last');
                        
                        if (fieldDataType != 'row' && fieldDataType != 'rows') {
                            
                            if (postData.depth == 0 && postData.isNew == 1) {
                                
                                var columnWidth = '';
                                var lookupType = $this.find('.bp-input-field-lookuptype').val();
                                var lookupMetaDataId = $this.find('#lookupMetaDataId').val();
                                
                                if (postData.dataType == 'long') {
                                    if (lookupType == '' || (lookupType == 'popup' && lookupMetaDataId != '')) {
                                        columnWidth = '300px';
                                    } 
                                } else if (postData.dataType == 'description') {
                                    columnWidth = '300px';
                                } else if (postData.dataType == 'bigdecimal') {
                                    columnWidth = '130px';
                                } 
                                
                                $appendDiv.find('input[name*="[columnWidth]"]').val(columnWidth);
                            }
                            
                            $appendDiv.find('button[role="iconpicker"]').iconpicker({
                                arrowPrevIconClass: 'fa fa-arrow-left',
                                arrowNextIconClass: 'fa fa-arrow-right'
                            });
                            
                            $appendDiv.on('change', '.process-field-icon', function(e) {
                                var $this = $(this), $parentRow = $this.closest('td'), 
                                    $selectedRow = $this.closest('.params-new-config-parent').find('table.params-new-config > tbody > tr.selected');

                                if (e.icon === 'empty' || e.icon === 'fa-empty') {
                                    $parentRow.find("input[type='hidden']").val('');
                                } else {
                                    $parentRow.find("input[type='hidden']").val(e.icon);
                                }

                                $selectedRow.find('.process-param-ischange').val('1');
                            });
                        }
                        
                        Core.initSetFractionRangeInput($appendDiv);
                        Core.initLongInput($appendDiv);
                        Core.initSelect2($appendDiv);
                        $appendDiv.find('input.autonumber').trigger('change');
                    });
                }
                
            });
        }
    });
    
    $('.param-link-tree > tbody').on('click', 'tr > td > span.tabletree-expander', function(){
    
        var $this = $(this);
        var $thisRow = $this.closest('tr');
        var rowId = $thisRow.attr('data-id');
            
        if ($this.hasClass('fa-plus')) {
            
            if ($thisRow.closest('tbody').find('tr.tabletree-parent-'+rowId).length === 0) {
                
                var inputPostData = {
                    processMetaDataId: '<?php echo $this->metaDataId; ?>', 
                    paramPath: $thisRow.find('input.process-param-path').val(), 
                    rowId: rowId, 
                    depth: Number($thisRow.attr('data-depth')) + 1, 
                    dataType: $thisRow.find('select.process-param-datatype').val(), 
                    isNew: $thisRow.find('input.process-param-isnew').val() 
                };
                
                if ($thisRow.find('input.process-param-newrowid').length) {
                    inputPostData['newRowId'] = $thisRow.find('input.process-param-newrowid').val();
                }
                
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/getChildProcessParam',
                    data: inputPostData,
                    beforeSend: function(){
                        $this.removeClass('fa-plus').addClass('fa-spinner');
                    },
                    success: function (dataHtml) {
                        if ($.trim(dataHtml) !== '') {
                            if (labelNameGlobeToggle) {
                                dataHtml = dataHtml.replace(/pf-params-labelname-globe d-none/g, 'pf-params-labelname-globe');
                                dataHtml = dataHtml.replace(/pf-params-labelname-input/g, 'pf-params-labelname-input d-none');
                            }
                            $thisRow.after(dataHtml);
                        }
                    }
                }).done(function(){
                    processParamInitFreeze();
                    $thisRow.find('td:eq(0), td:eq(1)').css('background-color', 'white');
                    $this.removeClass('fa-spinner').addClass('fa-minus');
                });
                
            } else {
                $thisRow.closest('tbody').find('tr.tabletree-parent-'+rowId).css({display: ''});
                $this.removeClass('fa-plus').addClass('fa-minus');
            }  
            
        } else {
            
            processParamCollapseRows($thisRow.closest('tbody'), rowId);
            $this.removeClass('fa-minus').addClass('fa-plus');
        }
    });
    
    $dialogId.on('keydown', 'input.process-param-add-code', function (e) {
        if (e.which === 13) {
            
            Core.blockUI({message: 'Loading...', boxed: true});
            var $this = $(this); 
            
            setTimeout(function () {
            
                var _value = $this.val(); 
                var $tbody = $this.closest('.params-new-config-parent').find('table.params-new-config > tbody');
                var _isName = false; 

                if (typeof $this.attr('data-ac-id') !== 'undefined') {
                    _isName = 'idselect';
                    _value = $this.attr('data-ac-id');
                }

                var isLast = false, isEmpty = false;

                if ($tbody.find('> tr').length === 0) {

                    var paramGroupPath = '';
                    var depth = 0;
                    var parentId = '';
                    isEmpty = true;

                } else {

                    if ($tbody.find('> tr.selected').length) {

                        var $addRow = $tbody.find('> tr.selected');
                        $addRow.removeClass('selected');

                    } else {
                        var $addRow = $tbody.find('> tr:last');
                        isLast = true;
                    }

                    var paramGroupPath = '';
                    var depth = $addRow.attr('data-depth');
                    var parentId = $addRow.attr('data-parent-id');

                    if (depth !== '0') {
                        paramGroupPath = $addRow.find('.process-path-name').text();
                    }

                    if ($addRow.attr('data-row-type') === 'group' && $addRow.find('.fa-plus').length) {

                        var plusDepth = Number(depth) + 1;
                        var paramPath = $addRow.attr('data-path');

                        if ($tbody.find("tr[data-depth='"+plusDepth+"'][data-path^='"+paramPath+".']").length) {
                            $addRow = $tbody.find("> tr[data-depth='"+plusDepth+"'][data-path^='"+paramPath+".']:last");
                        }              

                    } else if ($addRow.attr('data-row-type') === 'group' && $addRow.find('.fa-minus').length) {

                        var plusDepth = Number(depth) + 1;
                        var paramPath = $addRow.attr('data-path');
                        
                        if ($addRow.hasAttr('data-id')) {
                            parentId = $addRow.attr('data-id');
                        } else {
                            parentId = $addRow.find('.process-param-rowid').val();
                        }

                        if ($tbody.find("tr[data-depth='"+plusDepth+"'][data-path^='"+paramPath+".']").length) {
                            $addRow = $tbody.find("> tr[data-depth='"+plusDepth+"'][data-path^='"+paramPath+".']:last");
                        }
                        
                        depth = plusDepth;
                        paramGroupPath = paramPath + '.' + _value;    
                    }
                }
                
                _value = _value.trim();
                
                if (_value !== '') {
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdmetadata/processParamAddCode',
                        data: {
                            code: _value,
                            isName: _isName, 
                            depth: depth, 
                            parentId: parentId, 
                            paramGroupPath: paramGroupPath 
                        },
                        async: false,
                        dataType: 'json', 
                        success: function(jsonData){

                            $this.removeAttr('data-ac-id');
                            $this.val('');

                            if (jsonData.hasOwnProperty('status')) {
                                
                                Core.unblockUI();
                                PNotify.removeAll();
                                new PNotify({
                                    title: jsonData.status,
                                    text: jsonData.message, 
                                    type: jsonData.status,
                                    sticker: false
                                });

                            } else {

                                if ($tbody.find('input.process-param-path:attrNoCase("value","'+jsonData.path+'")').length) {

                                    Core.unblockUI();
                                    PNotify.removeAll();

                                    new PNotify({
                                        title: 'Анхааруулга',
                                        text: 'Уг ('+jsonData.path+') path өмнө нь үүссэн байна!', 
                                        type: 'info',
                                        sticker: false
                                    });

                                    return;

                                } else {

                                    var dataRow = jsonData.html;

                                    if (isEmpty) {

                                        $tbody.html(dataRow);
                                        Core.unblockUI();
                                        
                                        processParamInitFreeze();

                                        var $addedRow = $tbody.find('> tr:eq(0)');
                                        isLast = true;

                                    } else {
                                        
                                        $addRow.after(dataRow);
                                        Core.unblockUI();
                                        
                                        processParamInitFreeze();

                                        var $addedRow = $addRow.next();
                                        
                                        if ($addedRow.is(':last-child')) {
                                            isLast = true;
                                        }
                                    }

                                    var $parentScrollDiv = $('div#fz-process-params-option');
                                    var scrollTopSize = 0;
                                    
                                    if (isLast) {
                                        scrollTopSize = 4000;
                                    }
                                    
                                    if (scrollTopSize > 0) {
                                        $parentScrollDiv.scrollTop(scrollTopSize);
                                    }

                                    $addedRow.trigger('click');
                                }
                            }
                        },
                        error: function () {
                            alert('Error');
                        }
                    });
                    
                } else {
                    Core.unblockUI();
                }
                
            }, 25);
        }
    });
    $dialogId.on('focus', 'input.process-param-add-code', function(e){
        processParamMetaDataAutoComplete($(this));
    });
    $dialogId.on('keydown', 'input.process-param-add-code', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var $this = $(this);
        if (code === 13) {
            if ($this.data('ui-autocomplete')) {
                $this.autocomplete('destroy');
            }
            return false;
        } else {
            if (!$this.data('ui-autocomplete')) {
                processParamMetaDataAutoComplete($this);
            }
        }
    });
    
    $('.params-new-config').on('focus', 'select.process-param-datatype', function(){
        var $ddl = $(this);
        $ddl.data('previous', $ddl.val());
    });
    
    $('.params-new-config').on('change', 'select.process-param-datatype', function(){
        
        var $this = $(this);
        
        if ($this.prop('tagName') !== 'SELECT') {
            return;
        }
        
        var $previous = $this.data('previous');
        var $row = $this.closest('tr');
        var $value = $this.val();

        if ($value == 'row' || $value == 'rows') {
            
            if ($previous != 'row' && $previous != 'rows') {
            
                if ($row.find('.tabletree-expander').length === 0) {
                    $row.find('.process-path-name').before('<span class="tabletree-expander fa fa-plus"></span>');
                }

                $row.attr({'data-fieldtogroup': '1', 'data-row-type': 'group'});
                $row.find("td[data-c-name='lookupType'] > *, td[data-c-name='chooseType'] > *, td[data-c-name='lookupMetaDataId'] > *, td[data-c-name='displayField'] > *, td[data-c-name='valueField'] > *, td[data-c-name='defaultValue'] > *").css({display: 'none'});
            }
            
        } else {
            
            $row.attr('data-row-type', 'field').removeAttr('data-fieldtogroup');
            $row.find('.tabletree-expander').remove();
            
            var paramPath = $row.find('.process-path-name').text();

            if ($row.find("td[data-c-name='lookupType'] > *").length === 0) {
            
                $row.find("td[data-c-name='lookupType']").html('<select name="inputParam['+paramPath+'][lookupType]" class="form-control form-control-sm">'+
                    '<option value="">---</option>'+
                    '<option value="combo">Combo</option>'+
                    '<option value="popup">Popup</option>'+
                '</select>');
                $row.find("td[data-c-name='chooseType']").html('<select name="inputParam['+paramPath+'][chooseType]" class="form-control form-control-sm">'+
                    '<option value="">---</option>'+
                    '<option value="single">Single</option>'+
                    '<option value="multi">Multi</option>'+
                '</select>');
                $row.find("td[data-c-name='lookupMetaDataId']").html('<div style="float:left; width: 220px;">'+
                    '<div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">'+
                        '<div class="input-group double-between-input">'+
                            '<input id="lookupMetaDataId" name="inputParam['+paramPath+'][lookupMetaDataId]" type="hidden">'+
                            '<input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">'+
                            '<span class="input-group-btn">'+
                                '<button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid(\'single\', \'\', this);"><i class="fa fa-search"></i></button>'+
                            '</span>'+     
                            '<span class="input-group-btn not-group-btn">'+
                                '<div class="btn-group pf-meta-manage-dropdown">'+
                                    '<button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>'+
                                    '<ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>'+
                                '</div>'+
                            '</span>'+
                            '<span class="input-group-btn flex-col-group-btn">'+
                                '<input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">'+      
                            '</span>'+     
                        '</div>'+
                    '</div>'+
                '</div>'+
                '<div style="float:right"><button type="button" class="btn purple-plum form-control-sm ml0 mr0" onclick="paramDefaultValues(this);" title="Утга тохируулах">...</button></div>');
                
                $row.find("td[data-c-name='displayField']").html('<select name="inputParam['+paramPath+'][displayField]" class="form-control form-control-sm paramDisplayField" style="width: 160px">'+
                    '<option value="">---</option>'+
                '</select>');
                $row.find("td[data-c-name='valueField']").html('<select name="inputParam['+paramPath+'][valueField]" class="form-control form-control-sm paramValueField" style="width: 160px">'+
                    '<option value="">---</option>'+
                '</select>');
                $row.find("td[data-c-name='defaultValue']").html('<input type="text" name="inputParam['+paramPath+'][defaultValue]" class="form-control form-control-sm" placeholder="<?php echo $this->lang->line('META_00005'); ?>">');
            }
            
            $row.find("td[data-c-name='lookupType'] > *, td[data-c-name='chooseType'] > *, td[data-c-name='lookupMetaDataId'] > *, td[data-c-name='displayField'] > *, td[data-c-name='valueField'] > *, td[data-c-name='defaultValue'] > *").css({display: ''});
            
            var depth = $row.attr('data-depth');
            var isNew = $row.find('input.process-param-isnew').val();
            
            if (isNew == '1' && depth == '0') {
                var dataType = $value;
                var columnWidth = '';
                var lookupType = $row.find('.bp-input-field-lookuptype').val();
                var lookupMetaDataId = $row.find('#lookupMetaDataId').val();

                if (dataType == 'long') {
                    if (lookupType == '' || (lookupType == 'popup' && lookupMetaDataId != '')) {
                        columnWidth = '300px';
                    } 
                } else if (dataType == 'description') {
                    columnWidth = '300px';
                } else if (dataType == 'bigdecimal') {
                    columnWidth = '130px';
                } 
                
                var $paramsContainer = $this.closest('.params-new-config-parent').find('.params-addon-config');
                var $appendDiv = $paramsContainer.find('div[data-path]:visible');
                
                $appendDiv.find('input[name*="[columnWidth]"]').val(columnWidth);
            }
        }
    });
    
    $('.params-new-config').on('change', '#lookupMetaDataId', function() {
        var $row = $(this).closest('tr');
        var $lookupMetaDataId = $row.find('#lookupMetaDataId').val();
        
        if ($lookupMetaDataId !== '') {
            
            lookupMetaDataFields($row, $lookupMetaDataId);
            
            var $lookupType = $row.find('td[data-c-name="lookupType"] > select').val();
            var $chooseType = $row.find('td[data-c-name="chooseType"] > select').val();
            
            if ($lookupType == '') {
                $row.find('td[data-c-name="lookupType"] > select').val('popup');
            }
            if ($chooseType == '') {
                $row.find('td[data-c-name="chooseType"] > select').val('single');
            }
            
            var depth = $row.attr('data-depth');
            var isNew = $row.find('input.process-param-isnew').val();
            
            if (isNew == '1' && depth == '0') {
                
                var dataType = $row.find('.process-param-datatype').val();
                var lookupType = $lookupType;
                var lookupMetaDataId = $lookupMetaDataId;
                var columnWidth = '';

                if (dataType == 'long') {
                    if (lookupType == '' || (lookupType == 'popup' && lookupMetaDataId != '')) {
                        columnWidth = '300px';
                    } 
                } else if (dataType == 'description') {
                    columnWidth = '300px';
                } else if (dataType == 'bigdecimal') {
                    columnWidth = '130px';
                } 
                
                var $paramsContainer = $row.closest('.params-new-config-parent').find('.params-addon-config');
                var $appendDiv = $paramsContainer.find('div[data-path]:visible');
                
                $appendDiv.find('input[name*="[columnWidth]"]').val(columnWidth);
            }
            
        } else {
            lookupMetaDataFields($row, '');
            $row.find('td[data-c-name="lookupType"] > select, td[data-c-name="chooseType"] > select').val('');
        }
    });
    
    $('.params-new-config').on('keydown', 'input[type="text"]:visible', function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        switch (code) {
            case 38: // <Up>   
                if ($('.ui-autocomplete.ui-widget:visible').length == 0) {
                    var $this = $(this);
                    var $rowCell = $this.closest('td'); 
                    var $row = $this.closest('tr');
                    var $colIndex = $rowCell.index();
                    var $prevRow = $row.prev('tr:visible');

                    $prevRow.find('td:eq('+$colIndex+') input[type=text]:eq(0)').focus().select();
                    $prevRow.trigger('click');

                    //scrollInView();
                    return e.preventDefault();
                }
            break;
            case 40: // <Down>
                if ($('.ui-autocomplete.ui-widget:visible').length == 0) {
                    var $this = $(this);
                    var $rowCell = $this.closest('td'); 
                    var $row = $this.closest('tr');
                    var $colIndex = $rowCell.index();
                    var $nextRow = $row.next('tr:visible');

                    $nextRow.find('td:eq('+$colIndex+') input[type=text]:eq(0)').focus().select();
                    $nextRow.trigger('click');

                    return e.preventDefault();
                }
            break;
        } 
    });
    
    $('.params-new-config').on('change', 'input.process-param-name', function() {
        var $this = $(this), $parentRow = $this.closest('tr');
        var selfParamName = $this.val(), 
            oldParamName = $parentRow.find('.process-param-oldparamname').val();
        
        if (selfParamName !== oldParamName) {
            $parentRow.find('.process-param-ispathchange').val('1');
        } else {
            $parentRow.find('.process-param-ispathchange').val('0');
        }
    });
    
    $('.params-new-config').on('change', 'input, select', function() {
        var $this = $(this), $parentRow = $this.closest('tr'),
            inputName = $this.attr('name');
            
        $parentRow.find('.process-param-ischange').val('1');
        
        if (inputName.indexOf('[labelName]') !== -1) {
            var globeName = plang.get($this.val());
            $parentRow.find('.pf-params-labelname-globe').html(globeName);
        }
    });
    
    $('.params-new-config').on('change', 'select.bp-input-field-lookuptype', function() {
        var $this = $(this), value = $this.val();
        var $thisRow = $this.closest('tr');
        var paramPath = $thisRow.find('input.process-param-path').val();
        var $activeTbody = $thisRow.closest('.params-new-config-parent').find('.params-addon-config > div[data-path="'+paramPath+'"] tbody:eq(0)');
        
        $this.attr('title', $this.find('option:selected').text());
        
        if (value == 'combo_with_popup') {
            
            if ($activeTbody.find('input#addLookupMetaDataId').length == 0) {
                
                $activeTbody.append('<tr data-addonlookupmetadata="1">'+
                    '<td style="height: 30px;" class="left-padding">'+
                    '<label for="addLookupMetaDataId_'+paramPath+'">Нэмэлт lookup:</label>'+
                    '</td>'+
                    '<td>'+
                        '<div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">'+
                            '<div class="input-group double-between-input">'+
                                '<input id="addLookupMetaDataId" name="inputParam['+paramPath+'][addonLookupMetaDataId]" type="hidden">'+
                                '<input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">'+
                                '<span class="input-group-btn">'+
                                    '<button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid(\'single\', \'\', this);"><i class="fa fa-search"></i></button>'+
                                '</span>'+     
                                '<span class="input-group-btn not-group-btn">'+
                                    '<div class="btn-group pf-meta-manage-dropdown">'+
                                        '<button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>'+
                                        '<ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>'+
                                    '</div>'+
                                '</span>'+  
                                '<span class="input-group-btn flex-col-group-btn">'+
                                    '<input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">'+
                                '</span>'+     
                            '</div>'+
                        '</div>'+
                    '</td>'+
                '</tr>');
            }
        } else {
            $activeTbody.find('[data-addonlookupmetadata="1"]').remove();
        }
    });
    
    $('.params-addon-config').on('change', 'input, select, textarea', function() {
        var $this = $(this);
        var $selectedRow = $this.closest('.params-new-config-parent').find('table.params-new-config > tbody > tr.selected');
        $selectedRow.find('.process-param-ischange').val('1');
    });
    
    $('table.param-link-tree > tbody').sortable({
        items: 'tr', 
        cursor: 'move',
        handle: 'td:first > button.param-row-up-down', 
        cancel: '', 
        connectWith: 'table.param-link-tree > tbody', 
        placeholder: 'bg-yellow', 
        helper: fixInputRowDragHelper, 
        stop: function(event, ui) { 
            var $currElem = $(ui.item);
            var $prevElem = $currElem.prev();
            var $nextElem = $currElem.next();
            var currDepth = $currElem.attr('data-depth');
            var prevDepth = $prevElem.attr('data-depth');
            var nextDepth = $nextElem.attr('data-depth');
            
            if (currDepth == '0') {
                if (typeof prevDepth !== 'undefined' && prevDepth !== currDepth) {
                    return false;
                } else if (typeof nextDepth !== 'undefined' && nextDepth !== currDepth) {
                    return false;
                }
            } else {
                if (typeof prevDepth !== 'undefined') {
                    currDepth = Number(currDepth);
                    prevDepth = Number(prevDepth);
                    
                    if (currDepth == (prevDepth + 1) && $prevElem.attr('data-row-type') == 'group' 
                        && $prevElem.find('.fa-minus').length == 0) {
                        return false;
                    } else if (currDepth == (prevDepth + 1) && $prevElem.attr('data-row-type') == 'field' 
                        && $currElem.attr('data-row-type') == 'field') {
                        return false;
                    }
                }
            }
            
            if ($currElem.attr('data-row-type') == 'group') {
                var $currElem = $(ui.item);
                var $rowId = $currElem.attr('data-id');
                var $tbody = $currElem.closest('tbody');

                if ($tbody.find('tr.tabletree-parent-'+$rowId).length) {
                    $currElem.after($tbody.find('tr.tabletree-parent-'+$rowId));
                }
            }
        }
    });
    
    $dialogId.on('change', 'input.autonumber', function(e) {
        if ($(this).is(':checked')) {
            $dialogId.find('tr.autoNumber').removeClass('hidden');
        } else {
            $dialogId.find('tr.autoNumber').addClass('hidden');
        }
    });
    
    $(document.body).on('click', '.row-control-toggle', function() {
        var $this = $(this);
        var $row = $this.closest('.form-group');
        var $control = $row.find('input[type="text"], select');
        var $checkboxControl = $row.find('div[data-control="1"] input[type="checkbox"]');
        
        if ($this.is(':checked')) {
            $control.prop('disabled', false);
            if ($checkboxControl.length) {
                $checkboxControl.prop('disabled', false);
            }
        } else {
            $control.prop('disabled', true);
            if ($checkboxControl.length) {
                $checkboxControl.prop('disabled', true);
            }
        }
    });
    
    $dialogId.on('click', 'input.process-param-isdeletecheck', function(e) {
        if (!lastChecked) {
            lastChecked = this;
            return;
        }
        
        if (e.shiftKey) {
            var $this = $(this);
            var $chkboxes = $this.closest('tbody').find('input.process-param-isdeletecheck');
            var start = $chkboxes.index(this);
            var end = $chkboxes.index(lastChecked);

            $chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).prop('checked', lastChecked.checked);
        }

        lastChecked = this;
    });
});
function fixInputRowDragHelper(e, ui) {
    var $helper = ui.clone();
    $helper.css({'width': '850px', 'background-color': '#8775a7'});
    $helper.children().each(function(i, r) {
        if (i > 4) {
            $(this).remove();
        }
    });
    $helper.children().css({'background-color': '#8775a7'});
    return $helper;
}
function scrollInView() {
    var $target = $('table.param-link-tree tbody > tr.selected');
    if ($target.length) {
        var scrollTopSize = $target.offset().top;
        $('div#fz-process-params-option').scrollTop(scrollTopSize);  
        
        return false;
    }
}
function processParamInitFreeze() {
    $('table', 'div#fz-process-params-option').tableHeadFixer({'head': true, 'left': 2, 'z-index': 9}); 
}
function processParamCollapseRows($tbody, rootId) {
    var $rows = $tbody.find('tr.tabletree-parent-'+rootId);

    $rows.each(function(){
        var $thisRow = $(this);
        var $rowId = $thisRow.attr('data-id');
        $thisRow.css({display: 'none'});

        if ($tbody.find('tr.tabletree-parent-'+$rowId+':visible').length) {
            $thisRow.find('.tabletree-expander').removeClass('fa-minus').addClass('fa-plus');
            processParamCollapseRows($tbody, $rowId);
        }
    });
}
function processParamMetaDataAutoComplete(elem) {
    var $this = elem;
    var isHoverSelect = false;

    $this.autocomplete({
        minLength: 1,
        maxShowItems: 30,
        delay: 500,
        highlightClass: 'lookup-ac-highlight', 
        appendTo: 'body',
        position: {my : "left top", at: "left bottom", collision: "flip flip"}, 
        autoSelect: false,
        source: function(request, response) {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/metaDataAutoComplete',
                dataType: 'json',
                data: { 
                    q: request.term, 
                    type: 'codename' 
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        var code = item.split('|');
                        return {
                            value: code[1], 
                            label: code[1],
                            name: code[2], 
                            id: code[0]
                        };
                    }));
                }
            });
        },
        focus: function(event, ui) {
            if (typeof event.keyCode === 'undefined' || event.keyCode == 0) {
                isHoverSelect = false;
            } else {
                if (event.keyCode == 38 || event.keyCode == 40) {
                    isHoverSelect = true;
                }
            }
            return false;
        },
        open: function() {
            /*$(this).autocomplete('widget').zIndex(99999999999999);*/
            return false;
        },
        close: function() {
            $(this).autocomplete('option', 'appendTo', 'body'); 
        }, 
        select: function(event, ui) {
            var origEvent = event;	
            
            if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
                $this.val(ui.item.label);
                $this.attr('data-ac-id', ui.item.id);
            } else {
                if (ui.item.label === $this.val()) {
                    $this.val(ui.item.label);
                } else {
                    event.preventDefault();
                }
            }

            while (origEvent.originalEvent !== undefined){
                origEvent = origEvent.originalEvent;
            }

            if (origEvent.type === 'click') {
                var e = jQuery.Event("keydown");
                e.keyCode = e.which = 13;
                $this.trigger(e);
            }
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        ul.addClass('lookup-ac-render');
        
        //if (type === 'code') {
            var re = new RegExp("(" + this.term + ")", "gi"),
                cls = this.options.highlightClass,
                template = "<span class='" + cls + "'>$1</span>",
                label = item.label.replace(re, template);

            return $('<li>').append('<div class="lookup-ac-render-code">'+label+'</div><div class="lookup-ac-render-name">'+item.name+'</div>').appendTo(ul);
        /*} else {
            var re = new RegExp("(" + this.term + ")", "gi"),
                cls = this.options.highlightClass,
                template = "<span class='" + cls + "'>$1</span>",
                name = item.name.replace(re, template);

            return $('<li>').append('<div class="lookup-ac-render-code">'+item.label+'</div><div class="lookup-ac-render-name">'+name+'</div>').appendTo(ul);
        }*/
    };
}
function paramDefaultValuesLookup(elem, type) {
    var type = (typeof type !== 'undefined' ? type : 'main'); 
    paramDefaultValues('<?php echo $this->metaDataId; ?>', elem, type);
}
function paramDefaultValues(mainMetaDataId, elem, type) {
    var $dialogName = 'dialog-param-values';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    
    var $this = $(elem);
    var $parentCell = $this.closest('td');
    var $selectedRow = $this.closest('.params-new-config-parent').find('table.params-new-config > tbody > tr.selected');
    var $paramPath = $selectedRow.find('.process-path-name').text();
    var isKey = false;
    
    if (type === 'key') {
        var lookupMetaDataId = $parentCell.closest('table').find('#lookupKeyMetaDataId').val();
        var paramValues = $parentCell.find('.param-values-config-key').find('input').serialize();
        isKey = true;
    } else {
        var lookupMetaDataId = $selectedRow.find('#lookupMetaDataId').val();
        var paramValues = $parentCell.find('.param-values-config').find('input').serialize();
    }
    
    $.ajax({
        type: 'post',
        url: 'mdmeta/setParamDefaultValues',
        data: {
            mainMetaDataId: mainMetaDataId, 
            paramName: $selectedRow.find('.process-param-name').val(),
            paramPath: $selectedRow.find('.process-path-name').text(),
            lookupMetaDataId: lookupMetaDataId,
            paramValues: paramValues, 
            isKey: isKey 
        },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 800,
                minWidth: 800,
                height: "auto",
                modal: true,
                close: function() {
                    $dialog.dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {
                            
                        var $paramTbl = $("table#param-value-list tbody").find('tr');
                        var $paramConfigs = '';
                        
                        if (isKey) {
                            $paramTbl.each(function() {
                                var $thisRow = $(this);
                                var $paramPathAttr = $thisRow.find("input[name*=valueId]");
                                $paramConfigs += '<input name="paramDefaultValueIdKey['+$paramPath+'][]" value="' + $paramPathAttr.val() + '" type="hidden">';
                            });
                            $parentCell.find('.param-values-config-key').html($paramConfigs);
                        } else {
                            $paramTbl.each(function() {
                                var $thisRow = $(this);
                                var $paramPathAttr = $thisRow.find("input[name*=valueId]");
                                $paramConfigs += '<input name="paramDefaultValueId['+$paramPath+'][]" value="' + $paramPathAttr.val() + '" type="hidden">';
                            });
                            $parentCell.find('.param-values-config').html($paramConfigs);
                        }
                        $selectedRow.find('.process-param-ischange').val('1');
                        
                        $dialog.dialog('close');
                    }},
                    {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function() {
            alert("Error");
        }
    });
}
function groupLookupFieldsMapping(elem, type) {
    var $dialogName = 'dialog-process-lookup-field';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    
    var type = (typeof type !== 'undefined' ? type : 'main'); 
    var $this = $(elem);
    var $parentCell = $this.closest('td');
    var $selectedRow = $this.closest('.params-new-config-parent').find('table.params-new-config > tbody > tr.selected');
    var $paramPath = $selectedRow.find('.process-path-name').text();
    var isKey = false;
    
    if (type === 'key') {
        var lookupMetaDataId = $parentCell.closest('table').find('#lookupKeyMetaDataId').val();
        var fieldsMapping = $parentCell.find('.param-lookup-field-config-key').find('input').serialize();
        isKey = true;
    } else {
        var lookupMetaDataId = $selectedRow.find('#lookupMetaDataId').val();
        var fieldsMapping = $parentCell.find('.param-lookup-field-config').find('input').serialize();
    }

    $.ajax({
        type: 'post',
        url: 'mdmeta/setProcessLookupFieldsMapping',
        data: {
            mainMetaDataId: '<?php echo $this->metaDataId; ?>',
            paramName: $selectedRow.find('.process-param-name').val(),
            paramPath: $paramPath,
            lookupMetaDataId: lookupMetaDataId, 
            fieldsMapping: fieldsMapping, 
            isKey: isKey 
        },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function(data) {

            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 810,
                minWidth: 810,
                height: 'auto',
                modal: false,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {
                        $("#groupLookupFieldMapping-form").validate({errorPlacement: function() {}});

                        if ($("#groupLookupFieldMapping-form").valid()) {
                            
                            var $paramTbl = $('table.group-lookup-field-map-tbl tbody').find('tr');
                            var $paramConfigs = '';
                            
                            if (isKey) {
                                $paramTbl.each(function() {
                                    var $thisRow = $(this);
                                    var groupLookupFieldMapGroupParam = $thisRow.find("input[name*=groupLookupFieldMapGroupParam]").val();
                                    var groupLookupFieldMapLookupParam = $thisRow.find("select[name*=groupLookupFieldMapLookupParam]").val();

                                    if (groupLookupFieldMapLookupParam !== '' && groupLookupFieldMapGroupParam !== '') {
                                        $paramConfigs += '<input name="fieldMappingLookupFieldPathKey[' + $paramPath + '][]" value="' + groupLookupFieldMapLookupParam + '" type="hidden">';
                                        $paramConfigs += '<input name="fieldMappingParamFieldPathKey[' + $paramPath + '][]" value="' + groupLookupFieldMapGroupParam + '" type="hidden">';
                                    }
                                });
                                $parentCell.find('.param-lookup-field-config-key').html($paramConfigs);
                            } else {
                                $paramTbl.each(function() {
                                    var $thisRow = $(this);
                                    var groupLookupFieldMapGroupParam = $thisRow.find("input[name*=groupLookupFieldMapGroupParam]").val();
                                    var groupLookupFieldMapLookupParam = $thisRow.find("select[name*=groupLookupFieldMapLookupParam]").val();

                                    if (groupLookupFieldMapLookupParam !== '' && groupLookupFieldMapGroupParam !== '') {
                                        $paramConfigs += '<input name="fieldMappingLookupFieldPath[' + $paramPath + '][]" value="' + groupLookupFieldMapLookupParam + '" type="hidden">';
                                        $paramConfigs += '<input name="fieldMappingParamFieldPath[' + $paramPath + '][]" value="' + groupLookupFieldMapGroupParam + '" type="hidden">';
                                    }
                                });
                                $parentCell.find('.param-lookup-field-config').html($paramConfigs);
                            }
                        }
                        $selectedRow.find('.process-param-ischange').val('1');

                        $dialog.dialog('close');
                    }},
                    {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function() {
            alert("Error");
        }
    }).done(function() {
        Core.initSelect2($dialog);
    });
}
function setProcessExpressionCriteria(elem) {
    var $this = $(elem);
    var $parentCell = $this.closest("td");
    var $dialogName = 'dialog-expcriteria';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    var $selectedRow = $this.closest('.params-new-config-parent').find('table.params-new-config > tbody > tr.selected');
    
    $.ajax({
        type: 'post',
        url: 'mdmeta/setProcessExpressionCriteria',
        data: {
            paramName: $selectedRow.find('.process-param-name').val(),
            paramPath: $selectedRow.find('.process-path-name').text(),
            expressionString: $parentCell.find("#expressionString").val(),
            valueCriteria: $parentCell.find("#valueCriteria").val(),
            processMetaDataId: $parentCell.find("#processMetaDataId").val(),            
            processGetParamPath: $parentCell.find("#processGetParamPath").val(),
            lookupMetaDataId: $selectedRow.find('#lookupMetaDataId').val(),
            paramConfig: $parentCell.find('.lookup-param-configs').find('input').serialize(),
            processParamConfig: $parentCell.find('.process-param-configs').find('input').serialize()
        },
        dataType: 'json',
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
                minWidth: 1000,
                height: "auto",
                modal: false,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {
                            
                        expressionEditor.save();
                        valueEditor.save();
                        
                        $parentCell.find("#expressionString").val(expressionEditor.getValue());
                        $parentCell.find("#valueCriteria").val(valueEditor.getValue());
                        
                        $parentCell.find("#processMetaDataId").val($dialog.find("#processMetaDataId_valueField").val());                        
                        $parentCell.find("#processGetParamPath").val($dialog.find("#processGetParamPath").val());
                        
                        var $paramTbl = $("table.group-param-configs tbody").find("tr");
                        var paramConfigs = '';
                        
                        $paramTbl.each(function() {
                            var $thisRow = $(this);
                            var paramPathAttr = $thisRow.find("input[name*=paramGroupConfigParamPath]");
                            var getNameParamPathAttr = paramPathAttr.attr("name");
                            var paramMetaAttr = $thisRow.find("select[name*=paramGroupConfigParamMeta]");
                            var getNameParamMetaAttr = paramMetaAttr.attr("name");
                            var defaultValAttr = $thisRow.find("input[name*=paramGroupConfigDefaultVal]");
                            var getNameDefaultValAttr = defaultValAttr.attr("name");
                            paramConfigs += '<input name="' + getNameParamPathAttr + '" value="' + paramPathAttr.val() + '" type="hidden">';
                            paramConfigs += '<input name="' + getNameParamMetaAttr + '" value="' + paramMetaAttr.val() + '" type="hidden">';
                            paramConfigs += '<input name="' + getNameDefaultValAttr + '" value="' + defaultValAttr.val() + '" type="hidden">';
                        });
                        $parentCell.find('.lookup-param-configs').html(paramConfigs);
                        
                        var paramProcessTbl = $("table.process-param-configs tbody").find("tr");
                        var paramProcessConfigs = '';
                        
                        paramProcessTbl.each(function() {
                            var $thisRow = $(this);
                            var paramPathAttr = $thisRow.find("input[name*=paramProcessConfigParamPath]");
                            var getNameParamPathAttr = paramPathAttr.attr("name");
                            var paramMetaAttr = $thisRow.find("select[name*=paramProcessConfigParamMeta]");
                            var getNameParamMetaAttr = paramMetaAttr.attr("name");
                            var defaultValAttr = $thisRow.find("input[name*=paramProcessConfigDefaultVal]");
                            var getNameDefaultValAttr = defaultValAttr.attr("name");
                            paramProcessConfigs += '<input name="' + getNameParamPathAttr + '" value="' + paramPathAttr.val() + '" type="hidden">';
                            paramProcessConfigs += '<input name="' + getNameParamMetaAttr + '" value="' + paramMetaAttr.val() + '" type="hidden">';
                            paramProcessConfigs += '<input name="' + getNameDefaultValAttr + '" value="' + defaultValAttr.val() + '" type="hidden">';
                        });
                        
                        $parentCell.find('.process-param-configs').html(paramProcessConfigs);
                        $selectedRow.find('.process-param-ischange').val('1');
                        
                        $dialog.dialog('close');
                    }},
                    {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                        $dialog.dialog('close');
                    }}
                ]
            }).dialogExtend({
                "closable": true,
                "maximizable": true,
                "minimizable": true,
                "collapsable": true,
                "dblclick": "maximize",
                "minimizeLocation": "left",
                "icons": {
                    "close": "ui-icon-circle-close",
                    "maximize": "ui-icon-extlink",
                    "minimize": "ui-icon-minus",
                    "collapse": "ui-icon-triangle-1-s",
                    "restore": "ui-icon-newwin"
                }
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function() {
            alert("Error");
        }
    }).done(function() {
        Core.initTabs($dialog);
    });
}
function setProcessExpressionCriteriaGroup(elem) {
    var $this = $(elem);
    var $parentCell = $this.closest('td');
    var $dialogName = 'dialog-expcriteria';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
            
    var $selectedRow = $this.closest('.params-new-config-parent').find('table.params-new-config > tbody > tr.selected');
    
    $.ajax({
        type: 'post',
        url: 'mdmeta/setProcessExpressionCriteriaGroup',
        data: {
            paramName: $selectedRow.find('.process-param-name').val(),
            paramPath: $selectedRow.find('.process-path-name').text(),
            visibleCriteria: $parentCell.find("#visibleCriteria").val(),
            lookupMetaDataId: $selectedRow.find('#lookupMetaDataId').val(),
            lookupMetaDataIdKey: $parentCell.closest('table').find('#lookupKeyMetaDataId').val(), 
            paramConfig: $parentCell.find('.lookup-param-configs').find('input').serialize()
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
                width: 800,
                minWidth: 800,
                height: "auto",
                modal: false,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {
                            
                        visibleEditor.save();
                        $parentCell.find("#visibleCriteria").val(visibleEditor.getValue());
                        
                        var $paramTbl = $("table.group-param-configs tbody").find("tr");
                        var paramConfigs = '';
                        
                        $paramTbl.each(function() {
                            
                            var $thisRow = $(this);
                            var $paramPathAttr = $thisRow.find("input[name*=paramGroupConfigParamPath]");
                            var getNameParamPathAttr = $paramPathAttr.attr("name");
                            var $paramMetaAttr = $thisRow.find("select[name*=paramGroupConfigParamMeta]");
                            var getNameParamMetaAttr = $paramMetaAttr.attr("name");
                            var $defaultValAttr = $thisRow.find("input[name*=paramGroupConfigDefaultVal]");
                            var getNameDefaultValAttr = $defaultValAttr.attr("name");
                            
                            paramConfigs += '<input name="' + getNameParamPathAttr + '" value="' + $paramPathAttr.val() + '" type="hidden">';
                            paramConfigs += '<input name="' + getNameParamMetaAttr + '" value="' + $paramMetaAttr.val() + '" type="hidden">';
                            paramConfigs += '<input name="' + getNameDefaultValAttr + '" value="' + $defaultValAttr.val() + '" type="hidden">';
                        });
                        
                        $parentCell.find('.lookup-param-configs').html(paramConfigs);
                        $selectedRow.find('.process-param-ischange').val('1');
        
                        $dialog.dialog('close');
                    }},
                    {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                        $dialog.dialog('close');
                    }}
                ]
            }).dialogExtend({
                "closable": true,
                "maximizable": true,
                "minimizable": true,
                "collapsable": true,
                "dblclick": "maximize",
                "minimizeLocation": "left",
                "icons": {
                    "close": "ui-icon-circle-close",
                    "maximize": "ui-icon-extlink",
                    "minimize": "ui-icon-minus",
                    "collapse": "ui-icon-triangle-1-s",
                    "restore": "ui-icon-newwin"
                }
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function() {
            alert("Error");
        }
    }).done(function() {
        Core.initTabs($dialog);
    });
}
function processParamAddRows(chooseType, elem, params, _this) {
    
    var $commonBasketMetaDataGrid = $('#commonBasketMetaDataGrid');
    var metaBasketNum = $commonBasketMetaDataGrid.datagrid('getData').total;
    
    if (metaBasketNum > 0) {
        
        var rows = $commonBasketMetaDataGrid.datagrid('getRows');
        
        Core.blockUI({message: 'Loading...', boxed: true});
        
        setTimeout(function () {
            
            var $this = $(_this);
            var $tbody = $this.closest('.params-new-config-parent').find('table.params-new-config > tbody');
            var isLast = false, isEmpty = false;
            
            if ($tbody.find('> tr').length === 0) {
                
                var paramGroupPath = '';
                var depth = 0;
                var parentId = '';
                isEmpty = true;
                
            } else {
                
                if ($tbody.find('> tr.selected').length) {

                    var $addRow = $tbody.find('> tr.selected');
                    //$addRow.find('> td:eq(0), > td:eq(1)').css('background-color', 'white');
                    $addRow.removeClass('selected');

                } else {
                    var $addRow = $tbody.find('> tr:last');
                    isLast = true;
                }

                var paramGroupPath = '';
                var depth = $addRow.attr('data-depth');
                var parentId = $addRow.attr('data-parent-id');

                if (depth !== '0') {
                    paramGroupPath = $addRow.find('.process-path-name').text();
                }

                if ($addRow.attr('data-row-type') === 'group' && $addRow.find('.fa-minus').length) {

                    var plusDepth = Number(depth) + 1;
                    var paramPath = $addRow.attr('data-path');

                    if ($tbody.find("tr[data-depth='"+plusDepth+"'][data-path^='"+paramPath+".']")) {
                        $addRow = $tbody.find("> tr[data-depth='"+plusDepth+"'][data-path^='"+paramPath+".']:last");
                    }
                    
                    depth = plusDepth;
                    paramGroupPath = paramPath;                
                } 
            }

            $.ajax({
                type: 'post',
                url: 'mdmetadata/processParamAddMulti', 
                data: {
                    selectedRows: rows, 
                    paramGroupPath: paramGroupPath, 
                    depth: depth, 
                    parentId: parentId 
                },
                async: false, 
                success: function(dataRow) {
                    
                    $this.val('');
                    
                    if (isEmpty) {
                        
                        $tbody.html(dataRow);
                        processParamInitFreeze();
                        
                        var $addedRow = $tbody.find('> tr:eq(0)');
                        isLast = true;
                        
                    } else {
                        $addRow.after(dataRow);
                        
                        processParamInitFreeze();
                        //$addRow.find('td:eq(0), td:eq(1)').css('background-color', 'white');
                        
                        var $addedRow = $addRow.next();
                        
                        if ($addedRow.is(':last-child')) {
                            isLast = true;
                        }
                    }
                    
                    if (isLast) {
                        $('div#fz-process-params-option').scrollTop(4000);
                    } /*else {
                        var scrollTopSize = addedRow.offset().top - 100;
                    }*/

                    //$addedRow.find('td:eq(0), td:eq(1)').css('background-color', 'white');
                    $addedRow.trigger('click');
                }
            }).done(function(){
                Core.unblockUI();
            });
            
        }, 25);
    }
    
    return;
}
function deleteProcessParamRows(elem) {
    var $this = $(elem);
    var $checkList = $this.closest('.params-new-config-parent').find('table.params-new-config > tbody input.process-param-isdeletecheck:checked');
    
    if ($checkList.length) {
        
        $checkList.each(function(){
            
            var $thisRow = $(this).closest('tr');
            var isNew = $thisRow.find('.process-param-isnew').val();
    
            if (isNew === '1') {
                var paramPath = $thisRow.find('input.process-param-path').val();
                $thisRow.closest('.params-new-config-parent').find('.params-addon-config > div[data-path="'+paramPath+'"]').remove();
                $thisRow.remove();
            } else {
                if ($thisRow.hasClass('deleted-row')) {
                    $thisRow.removeClass('deleted-row');
                    $thisRow.find('input.process-param-isdelete').val('0');
                } else {
                    $thisRow.addClass('deleted-row');
                    $thisRow.find('input.process-param-isdelete').val('1');
                }
            }
        });
    } else {
        alert(plang.get('msg_pls_list_select'));
    }
    return;
}
function processParamDeleteRow($elem) {
    var $isNew = $elem.find('.process-param-isnew').val();
    
    if ($isNew === '1') {
        var $paramPath = $elem.find('input.process-param-path').val();
        var $rowId = $elem.attr('data-id');
        
        $elem.closest('.params-new-config-parent').find('.params-addon-config > div[data-path="'+$paramPath+'"]').remove();
        
        processParamRemoveRows($elem.closest('tbody'), $rowId);
        $elem.remove();
        
    } else {
        $elem.addClass('deleted-row');
        $elem.find('input.process-param-isdelete').val('1');
    }
}
function processParamRemoveRows($tbody, rootId) {
    var $rows = $tbody.find('tr.tabletree-parent-'+rootId);

    $rows.each(function(){
        var $thisRow = $(this);
        var $paramPath = $thisRow.find('input.process-param-path').val();
        var $rowId = $thisRow.attr('data-id');
        
        $thisRow.remove();
        $tbody.closest('.params-new-config-parent').find('.params-addon-config > div[data-path="'+$paramPath+'"]').remove();

        if ($tbody.find('tr.tabletree-parent-'+$rowId).length) {
            processParamRemoveRows($tbody, $rowId);
        }
    });
}
function processParamRefreshRow(elem) {
    elem.removeClass('deleted-row');
    elem.find('input.process-param-isdelete').val('0');
}
function lookupMetaDataFields($thisParent, lookupMetaDataId){
    var $paramDisplayField = $thisParent.find("select.paramDisplayField");
    var $paramValueField = $thisParent.find("select.paramValueField");
    
    if (lookupMetaDataId !== '' && $paramDisplayField.length > 0 && $paramValueField.length > 0) {
        $.ajax({
            type: 'post',
            url: 'mdmetadata/lookupFieldName',
            data: {lookupMetaDataId: lookupMetaDataId},
            dataType: 'json',
            success: function(data){
                
                $paramDisplayField.find('option:gt(0)').remove();
                $paramValueField.find('option:gt(0)').remove();
                
                $.each(data.fields, function(){
                    $paramDisplayField.append($('<option />').val(this.FIELD_NAME).text(this.FIELD_NAME));
                    $paramValueField.append($('<option />').val(this.FIELD_NAME).text(this.FIELD_NAME));
                }); 
                
                var dataIdCodeName = data.idCodeName;
                
                if (dataIdCodeName.id) {
                    
                    var matchingValue = $paramValueField.find('option').filter(function () { 
                        return this.value.toLowerCase() == dataIdCodeName.id; 
                    }).attr('value');
                   
                    $paramValueField.val(matchingValue);
                }
                if (dataIdCodeName.name) {
                    
                    var matchingValue = $paramDisplayField.find('option').filter(function () { 
                        return this.value.toLowerCase() == dataIdCodeName.name; 
                    }).attr('value');
                   
                    $paramDisplayField.val(matchingValue);
                }
                
                $paramDisplayField.trigger('change');
                $paramValueField.trigger('change');
            },
            error: function(){alert('Error - lookupFieldName');}
        });
    } else {
        $paramDisplayField.find('option:gt(0)').remove();
        $paramValueField.find('option:gt(0)').remove();
        $paramDisplayField.trigger('change');
        $paramValueField.trigger('change');
    }
}
function setProcessConfigsMultiFieldForm(elem) {
    var $this = $(elem);
    var $checkedList = $this.closest('.params-new-config-parent').find('table.params-new-config > tbody input.process-param-isdeletecheck:checked');
    
    if ($checkedList.length) {
        
        var $dialogName = 'dialog-bp-multiparamsconfig'; 
        var $dialog = $('#' + $dialogName);
        
        if ($dialog.length) {
            
            $dialog.find('input[type="text"], select').prop('disabled', true).val('');
            $dialog.find('div[data-control="1"] input[type="checkbox"]').prop('disabled', true);
            
            var $dialogCheckedBoxs = $dialog.find('input[type="checkbox"], input[type="radio"]');
            $dialogCheckedBoxs.prop('checked', false);
                
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: plang.get('pf_multi_field_config'),
                width: 450,
                height: 'auto',
                modal: true, 
                closeOnEscape: isCloseOnEscape, 
                buttons: [
                    {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow bp-btn-subsave', click: function() {
                        setProcessConfigsMultiField($this, $dialog, $checkedList);
                    }}, 
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                        PNotify.removeAll();    
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            
        } else {
            
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
            var $dialog = $('#' + $dialogName);

            $.ajax({
                type: 'post',
                url: 'mdmeta/setProcessConfigsMultiField', 
                data: {isProcess: true}, 
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(data) {
                    $dialog.empty().append(data);
                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: plang.get('pf_multi_field_config'),
                        width: 450,
                        height: 'auto',
                        modal: true, 
                        closeOnEscape: isCloseOnEscape, 
                        buttons: [
                            {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow bp-btn-subsave', click: function() {
                                setProcessConfigsMultiField($this, $dialog, $checkedList);
                            }}, 
                            {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                                PNotify.removeAll();    
                                $dialog.dialog('close');
                            }}
                        ]
                    });
                    $dialog.dialog('open');
                    Core.unblockUI();
                },
                error: function() { alert('Error'); Core.unblockUI(); }
            });
        }
    } else {
        alert(plang.get('msg_pls_list_select'));
    }
    return;
}

function setProcessConfigsMultiField($this, $dialog, $checkedList) {
    PNotify.removeAll();    
    var $checkedBoxs = $dialog.find('input[type="checkbox"]:checked');

    if ($checkedBoxs.length) {

        var $checkedRows = $checkedList.closest('tr');
        var $paramsContainer = $this.closest('.params-new-config-parent').find('.params-addon-config');
        var vals = [];

        $checkedBoxs.each(function() {

            var $chkBx = $(this);
            var $chkBxRow = $chkBx.closest('.form-group');
            var $controlCell = $chkBxRow.find('[data-control="1"]');
            var $getValControl = $controlCell.find('input[type="text"], textarea, select');
            var controlVal = '', controlType = 'input';

            if ($getValControl.length) {
                controlVal = $getValControl.val();
            } else if ($controlCell.find('input[type="checkbox"]')) {
                controlVal = $controlCell.find('input[type="checkbox"]').is(':checked') ? 1 : 0;
                controlType = 'checkbox';
            } else if ($controlCell.find('input[type="radio"]')) {
                controlVal = $controlCell.find('input[type="radio"]:checked').val();
                controlType = 'radio';
            }

            vals.push({
                path: $chkBxRow.attr('data-path'), 
                val: trim(dvFieldValueShow(controlVal)), 
                type: controlType, 
                sidebar: $chkBxRow.attr('data-sidebar')
            });
        });

        $checkedRows.each(function() {

            var $sRow = $(this);
            var paramPath = $sRow.find('.process-param-path').val();
            var $addonPanel = $paramsContainer.find("div[data-path='"+paramPath+"']");

            $sRow.find('.process-param-ispathchange').val('1');

            for (var v in vals) {
                var fieldPath = vals[v]['path'];
                if (vals[v]['sidebar'] == '1') {
                    if ($addonPanel.length) {
                        if (vals[v]['type'] == 'checkbox') {
                            $addonPanel.find('[name*="['+fieldPath+']"]').prop('checked', (vals[v]['val'] == 1 ? true : false));
                        } else {
                            $addonPanel.find('[name*="['+fieldPath+']"]').val(vals[v]['val']);
                        }
                    }   
                } else {
                    if (vals[v]['type'] == 'checkbox') {
                        $sRow.find('[name*="['+fieldPath+']"]').prop('checked', (vals[v]['val'] == 1 ? true : false));
                    } else {
                        $sRow.find('[name*="['+fieldPath+']"]').val(vals[v]['val']);
                    }
                }
            }
        });

        $dialog.dialog('close');
    } else {
        new PNotify({
            title: 'Info',
            text: 'Талбарын тохиргоог оруулна уу!', 
            type: 'info',
            sticker: false, 
            addclass: 'pnotify-center'
        });
    }
}
var formatExpOpts = {
    indent_size: 4,
    indent_char: ' ',
    max_preserve_newlines: 5,
    preserve_newlines: true,
    keep_array_indentation: false,
    break_chained_methods: false,
    indent_scripts: 'normal',
    brace_style: 'collapse',
    space_before_conditional: true, 
    unescape_strings: false, 
    jslint_happy: false,
    end_with_newline: false,
    wrap_line_length: 0,
    indent_inner_html: false,
    comma_first: false,
    e4x: false,
    indent_empty_lines: false
};
function paramJsonConfig(elem) {

    var $dialogName = 'dialog-paramJsonConfigExpcriteria';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js').done(function() {
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.css"/>');

        $dialog.empty().append(
            '<div class="row">'+
                '<div class="col-md-12">'+
                '<?php
                echo Form::textArea(
                    array(
                        'name' => 'jsonConfigRowExpressionString_set',
                        'id' => 'jsonConfigRowExpressionString_set',
                        'class' => 'form-control ace-textarea',
                        'value' => '',
                        'spellcheck' => 'false',
                        'style' => 'width: 100%;'
                    )
                );
                ?>'+
                '</div>'+
            '</div>'
        );

        $dialog.find('#jsonConfigRowExpressionString_set').val($(elem).closest('tr').find('[name*="[jsonConfig]"]').val());

        $dialog.dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'JSON CONFIG',
            width: 900,
            minWidth: 900,
            height: "auto",
            modal: true,
            position: {my:'top', at:'top+50'},
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green', click: function() {
                    jsonConfigExpressionRowEditor.save();
                    
                    var $this = $(elem);
                    var $selectedRow = $this.closest('.params-new-config-parent').find('table.params-new-config > tbody > tr.selected');
                    
                    $selectedRow.find('.process-param-ischange').val('1');                    
                    $this.closest('td').find('[name*="[jsonConfig]"]').val($('#jsonConfigRowExpressionString_set').val());
                    
                    $dialog.dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                    $dialog.dialog('close');
                }}
            ]
        }).dialogExtend({
            "closable": true,
            "maximizable": true,
            "minimizable": true,
            "collapsable": true,
            "dblclick": "maximize",
            "minimizeLocation": "left",
            "icons": {
                "close": "ui-icon-circle-close",
                "maximize": "ui-icon-extlink",
                "minimize": "ui-icon-minus",
                "collapse": "ui-icon-triangle-1-s",
                "restore": "ui-icon-newwin"
            }
        });

        var jsonConfigExpressionRowEditor = CodeMirror.fromTextArea(document.getElementById("jsonConfigRowExpressionString_set"), {
            mode: 'application/json',
            styleActiveLine: true,
            lineNumbers: true,
            lineWrapping: true,
            matchBrackets: true,
            autoCloseBrackets: true,
            indentUnit: 4,
            theme: 'material', 
            foldGutter: true,
            gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"], 
            extraKeys: {
                "Alt-T": function(cm){ 
                    var formattedExpression = js_beautify(cm.getValue(), formatExpOpts);
                    cm.setValue(formattedExpression);
                },                 
                "F11": function(cm) {
                    cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                },
                "Esc": function(cm) {
                    if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                }
            }
        });
        setTimeout(function() {
            jsonConfigExpressionRowEditor.refresh();
        }, 1);        

        $dialog.dialog('open');
    });
}
</script>