<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'expCriteria-form', 'method' => 'post')); ?>
    <div class="form-group row fom-row mb0">
        <?php echo Form::label(array('text' => 'Param name', 'class' => 'col-form-label col-md-3')); ?>
        <div class="col-md-9">
            <p class="form-control-plaintext font-weight-bold"><?php echo $this->params['paramName'] . " /" . $this->params['paramPath'] . "/"; ?></p>
        </div>
    </div>
    <div class="tabbable-line">
        <ul class="nav nav-tabs param-criteria-tabs">
            <li class="nav-item">
                <a href="#set-exp-tab1" class="nav-link active" data-toggle="tab">Expression</a>
            </li>
            <li class="nav-item">
                <a href="#set-exp-tab10" class="nav-link" data-toggle="tab">Value</a>
            </li>
            <li class="nav-item">
                <a href="#set-exp-tab12" class="nav-link" data-toggle="tab">Column show</a>
            </li>
            <li class="nav-item">
                <a href="#set-exp-tab6" class="nav-link" data-toggle="tab">Lookup param</a>
            </li>
            <li class="nav-item">
                <a href="#set-exp-tab7" class="nav-link" data-toggle="tab">Process param</a>
            </li>
            <li class="nav-item">
                <a href="#set-exp-tab11" class="nav-link" data-toggle="tab">Process</a>
            </li>            
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="set-exp-tab1">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        echo Form::textArea(
                            array(
                                'name' => 'expressionString_set',
                                'id' => 'expressionString_set',
                                'class' => 'form-control',
                                'value' => $this->params['expressionString'],
                                'spellcheck' => 'false',
                                'rows' => 4
                            )
                        );
                        ?>
                    </div>
                </div>    
            </div> 
            <div class="tab-pane" id="set-exp-tab10">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        echo Form::textArea(
                            array(
                                'name' => 'valueCriteria_set',
                                'id' => 'valueCriteria_set',
                                'class' => 'form-control',
                                'value' => $this->params['valueCriteria'],
                                'spellcheck' => 'false',
                                'rows' => 4
                            )
                        );
                        ?>
                    </div>
                </div>
            </div>    
            <div class="tab-pane" id="set-exp-tab12">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        echo Form::textArea(
                            array(
                                'name' => 'styleCriteria_set',
                                'id' => 'styleCriteria_set',
                                'class' => 'form-control',
                                'value' => issetParam($this->params['styleCriteria']),
                                'spellcheck' => 'false',
                                'rows' => 4
                            )
                        );
                        ?>
                    </div>
                </div>
            </div>    
            <div class="tab-pane" id="set-exp-tab6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row fom-row">
                            <label class="col-md-3 col-form-label"><?php echo $this->lang->line('META_00004'); ?></label>
                            <div class="col-md-9">
                                <p class="form-control-plaintext">
                                    <?php echo ((empty($this->lookupMetaDataCode)) ? '' : "(" . $this->lookupMetaDataCode . ") " . $this->lookupMetaDataName); ?>
                                </p>
                            </div>
                        </div>
                        <div class="table-scrollable">
                            <table class="table group-param-configs">
                                <thead>
                                    <tr>
                                        <th>Param path</th>
                                        <th>Param meta</th>
                                        <th>Default value</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($this->paramsConfigs) {
                                        foreach ($this->paramsConfigs['paramGroupConfigParamPath'] as $k => $row) {
                                            foreach ($row as $p => $rowChild) {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php
                                                        echo Form::text(
                                                            array(
                                                                'name' => 'paramGroupConfigParamPath[' . $this->params['paramPath'] . '][]',
                                                                'class' => 'form-control form-control-sm',
                                                                'value' => $this->paramsConfigs['paramGroupConfigParamPath'][$this->params['paramPath']][$p]
                                                            )
                                                        );
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo Form::select(
                                                            array(
                                                                'name' => 'paramGroupConfigParamMeta[' . $this->params['paramPath'] . '][]',
                                                                'class' => 'form-control form-control-sm',
                                                                'data' => $this->lookupMetaDataParamMetaData,
                                                                'op_value' => 'FIELD_NAME',
                                                                'op_text' => 'FIELD_NAME| |-| |META_DATA_NAME',
                                                                'value' => $this->paramsConfigs['paramGroupConfigParamMeta'][$this->params['paramPath']][$p]
                                                            )
                                                        );
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo Form::text(
                                                            array(
                                                                'name' => 'paramGroupConfigDefaultVal[' . $this->params['paramPath'] . '][]',
                                                                'class' => 'form-control form-control-sm',
                                                                'value' => $this->paramsConfigs['paramGroupConfigDefaultVal'][$this->params['paramPath']][$p]
                                                            )
                                                        );
                                                        ?>
                                                    </td>
                                                    <td class="middle text-center">
                                                        <a href="javascript:;" class="btn red btn-xs" onclick="groupParamConfigRemoveRow(this);"><i class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                                <?php
                                if ($this->lookupMetaDataName != '') {
                                ?>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4">
                                                <a href="javascript:;" class="btn green btn-xs" onclick="groupParamConfigAddRow(this);">
                                                    <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('META_00103'); ?> 
                                                </a>
                                            </td>
                                        </tr>
                                    </tfoot>
                                <?php
                                }
                                ?>
                            </table>
                        </div>     
                    </div>    
                </div>
            </div>
            <div class="tab-pane" id="set-exp-tab7">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row fom-row">
                            <label class="col-md-3 col-form-label">Process meta:</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="hidden" id="processMetaDataId_valueField" name="processMetaDataId" value="<?php echo $this->processMetaDataId; ?>">
                                    <input type="text" value="<?php echo $this->processMetaDataCode; ?>" id="processMetaDataId_displayField" class="form-control form-control-sm process-metadata-criteria" style="min-width: 150px;">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn blue form-control-sm mr0" onclick="commonMetaDataGrid('single', 'metaObject', 'autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>', 'processMetaSelectCriteria', this);"><i class="fa fa-search"></i></button>
                                    </span>
                                </div><div id="processMetaDataId_nameField"><?php echo $this->processMetaDataName; ?></div>
                            </div>
                        </div>
                        <div class="form-group row fom-row">
                            <label class="col-md-3 col-form-label">Get param:</label>
                            <div class="col-md-9">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'processGetParamPath',
                                        'id' => 'processGetParamPath',
                                        'class' => 'form-control form-control-sm',
                                        'value' => $this->params['processGetParamPath'],
                                        'required' => 'required'
                                    )
                                );
                                ?>
                            </div>
                        </div>    
                        <div class="table-scrollable">
                            <table class="table process-param-configs">
                                <thead>
                                    <tr>
                                        <th>Param path</th>
                                        <th>Param meta</th>
                                        <th>Default value</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($this->processParamConfigs) {
                                        foreach ($this->processParamConfigs['paramProcessConfigParamPath'] as $pk => $prow) {
                                            foreach ($prow as $pkc => $prowChild) {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php
                                                        echo Form::text(
                                                            array(
                                                                'name' => 'paramProcessConfigParamPath[' . $this->params['paramPath'] . '][]',
                                                                'class' => 'form-control form-control-sm',
                                                                'value' => $this->processParamConfigs['paramProcessConfigParamPath'][$this->params['paramPath']][$pkc]
                                                            )
                                                        );
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo Form::select(
                                                            array(
                                                                'name' => 'paramProcessConfigParamMeta[' . $this->params['paramPath'] . '][]',
                                                                'class' => 'form-control form-control-sm',
                                                                'data' => $this->processMetaDataParamMetaData,
                                                                'op_value' => 'PARAM_NAME',
                                                                'op_text' => 'PARAM_REAL_PATH| |-| |LABEL_NAME',
                                                                'value' => $this->processParamConfigs['paramProcessConfigParamMeta'][$this->params['paramPath']][$pkc],
                                                                'required' => 'required'
                                                            )
                                                        );
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo Form::text(
                                                            array(
                                                                'name' => 'paramProcessConfigDefaultVal[' . $this->params['paramPath'] . '][]',
                                                                'class' => 'form-control form-control-sm',
                                                                'value' => $this->processParamConfigs['paramProcessConfigDefaultVal'][$this->params['paramPath']][$pkc]
                                                            )
                                                        );
                                                        ?>
                                                    </td>
                                                    <td class="middle text-center">
                                                        <a href="javascript:;" class="btn red btn-xs" onclick="groupParamConfigRemoveRow(this);"><i class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                                <?php
                                if ($this->processMetaDataCode != '') {
                                ?>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4">
                                                <a href="javascript:;" class="btn green btn-xs" onclick="processParamConfigAddRow(this);">
                                                    <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('META_00103'); ?> 
                                                </a>
                                            </td>
                                        </tr>
                                    </tfoot>
                                <?php
                                }
                                ?>
                            </table>
                        </div>     
                    </div>    
                </div>
            </div>          
            <div class="tab-pane" id="set-exp-tab11">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row fom-row">
                            <label class="col-md-3 col-form-label">Process meta:</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="hidden" id="processMetaDataIdPath_valueField" name="processMetaDataIdPath" value="<?php echo $this->processMetaDataIdPath; ?>">
                                    <input type="text" value="<?php echo $this->processMetaDataCodePath; ?>" id="processMetaDataIdPath_displayField" class="form-control form-control-sm process-metadata-criteria" style="min-width: 150px;">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn blue form-control-sm mr0" onclick="commonMetaDataGrid('single', 'metaObject', 'autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>', 'processMetaPathSelectCriteria', this);"><i class="fa fa-search"></i></button>
                                    </span>
                                </div><div id="processMetaDataIdPath_nameField"><?php echo $this->processMetaDataNamePath; ?></div>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>            
        </div>
    </div>
<?php echo Form::close(); ?>
<style type="text/css">
    .CodeMirror .cm-error {
        background-color: transparent !important;
        color: #82b1ff !important;
    }
</style>
<script type="text/javascript">
    var expressionEditor = CodeMirror.fromTextArea(document.getElementById("expressionString_set"), {
        mode: "javascript",
        styleActiveLine: true,
        lineNumbers: true,
        lineWrapping: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        indentUnit: 4,
        theme: "material"
    });
    var valueEditor = CodeMirror.fromTextArea(document.getElementById("valueCriteria_set"), {
        mode: "javascript",
        styleActiveLine: true,
        lineNumbers: true,
        lineWrapping: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        indentUnit: 4,
        theme: "material"
    });
    var styleEditor = CodeMirror.fromTextArea(document.getElementById("styleCriteria_set"), {
        mode: "javascript",
        styleActiveLine: true,
        lineNumbers: true,
        lineWrapping: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        indentUnit: 4,
        theme: "material"
    });
    $(function() {
        $('.param-criteria-tabs a[data-toggle="tab"]').on('shown.bs.tab', function() {
            expressionEditor.refresh();
            valueEditor.refresh();
            styleEditor.refresh();
        });
        $('input.process-metadata-criteria').on('keydown', function(e) {
            if (e.which === 13) {
                var $this = $(this);
                var $value = $this.val();
                var $parentCell = $this.closest('.form-group');
                $.ajax({
                    type: 'post',
                    url: 'mdobject/autoCompleteObjectTypeByMetaCode',
                    data: {code: $value},
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({
                            target: $this,
                            animate: true
                        });
                        $this.addClass('spinner2');
                    },
                    success: function(data) {
                        if (data.META_DATA_ID !== '') {
                            $parentCell.find('#processMetaDataId_valueField').val(data.META_DATA_ID);
                            $parentCell.find('#processMetaDataId_displayField').val(data.META_DATA_CODE);
                            $parentCell.find('#processMetaDataId_nameField').text(data.META_DATA_NAME);
                        } else {
                            $parentCell.find('#processMetaDataId_valueField, #processMetaDataId_displayField').val('');
                            $parentCell.find('#processMetaDataId_nameField').text('');
                        }
                        Core.unblockUI();
                        $this.removeClass('spinner2');
                    },
                    error: function() {
                        alert("Error");
                    }
                });
            }
        });
    });
    function processMetaSelectCriteria(chooseType, elem, params, _this) {
        var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
        if (metaBasketNum > 0) {
            var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
            var row = rows[0];
            var parentCell = $(_this).closest("div.form-group");
            parentCell.find("#processMetaDataId_valueField").val(row.META_DATA_ID);
            parentCell.find("#processMetaDataId_displayField").val(row.META_DATA_CODE);
            parentCell.find("#processMetaDataId_nameField").text(row.META_DATA_NAME);
        }
    }
    function groupParamConfigAddRow(elem) {
        var _this = $(elem);
        var parentTbl = _this.closest("table");
        parentTbl.find("tbody").append('<tr>' +
                '<td><?php echo Form::text(array('name' => 'paramGroupConfigParamPath[' . $this->params['paramPath'] . '][]', 'class' => 'form-control form-control-sm')); ?></td>' +
                '<td><?php echo Form::select(array('name' => 'paramGroupConfigParamMeta[' . $this->params['paramPath'] . '][]', 'class' => 'form-control form-control-sm', 'data' => $this->lookupMetaDataParamMetaData, 'op_value' => 'FIELD_NAME', 'op_text' => 'FIELD_NAME| |-| |META_DATA_NAME', 'required' => 'required')); ?></td>' +
                '<td><?php echo Form::text(array('name' => 'paramGroupConfigDefaultVal[' . $this->params['paramPath'] . '][]', 'class' => 'form-control form-control-sm')); ?></td>' +
                '<td class="middle text-center"><a href="javascript:;" class="btn red btn-xs" onclick="groupParamConfigRemoveRow(this);"><i class="fa fa-trash"></i></a></td>' +
                '</tr>');
    }
    function processParamConfigAddRow(elem) {
        var _this = $(elem);
        var parentTbl = _this.closest("table");
        parentTbl.find("tbody").append('<tr>' +
                '<td><?php echo Form::text(array('name' => 'paramProcessConfigParamPath[' . $this->params['paramPath'] . '][]', 'class' => 'form-control form-control-sm')); ?></td>' +
                '<td><?php echo Form::select(array('name' => 'paramProcessConfigParamMeta[' . $this->params['paramPath'] . '][]', 'class' => 'form-control form-control-sm', 'data' => $this->processMetaDataParamMetaData, 'op_value' => 'PARAM_NAME', 'op_text' => 'PARAM_REAL_PATH| |-| |LABEL_NAME', 'required' => 'required')); ?></td>' +
                '<td><?php echo Form::text(array('name' => 'paramProcessConfigDefaultVal[' . $this->params['paramPath'] . '][]', 'class' => 'form-control form-control-sm')); ?></td>' +
                '<td class="middle text-center"><a href="javascript:;" class="btn red btn-xs" onclick="groupParamConfigRemoveRow(this);"><i class="fa fa-trash"></i></a></td>' +
                '</tr>');
    }
    function groupParamConfigRemoveRow(elem) {
        var parentRow = $(elem).closest("tr");
        parentRow.remove();
    }
    function processMetaPathSelectCriteria(chooseType, elem, params, _this) {
        var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
        if (metaBasketNum > 0) {
            var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
            var row = rows[0];
            var parentCell = $(_this).closest("div.form-group");
            parentCell.find("#processMetaDataIdPath_valueField").val(row.META_DATA_ID);
            parentCell.find("#processMetaDataIdPath_displayField").val(row.META_DATA_CODE);
            parentCell.find("#processMetaDataIdPath_nameField").text(row.META_DATA_NAME);
        }
    }    
</script>