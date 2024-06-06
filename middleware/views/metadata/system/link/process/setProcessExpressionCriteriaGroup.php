<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'expCriteria-form', 'method' => 'post')); ?>
<div class="form-group row fom-row mb0">
    <?php echo Form::label(array('text'=>'Param name', 'class'=>'col-form-label col-md-3')); ?>
    <div class="col-md-9">
        <p class="form-control-plaintext font-weight-bold"><?php echo $this->params['paramName'].' /'.$this->params['paramPath'].'/'; ?></p>
    </div>
</div>
<div class="tabbable-line">
    <ul class="nav nav-tabs param-criteria-tabs">
        <li class="nav-item">
            <a href="#set-exp-tab4" class="nav-link active" data-toggle="tab">Visible</a>
        </li>
        <li class="nav-item">
            <a href="#set-exp-tab6" class="nav-link" data-toggle="tab">Lookup param</a>
        </li>
        <li class="nav-item">
            <a href="#set-exp-tab7" class="nav-link" data-toggle="tab">Key lookup param</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="set-exp-tab4">
            <div class="row">
                <div class="col-md-12">
                    <?php 
                    echo Form::textArea(
                        array(
                            'name' => 'visibleCriteria_set', 
                            'id' => 'visibleCriteria_set', 
                            'class' => 'form-control', 
                            'value' => $this->params['visibleCriteria'], 
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
                                if ($this->paramsConfigs && isset($this->paramsConfigs['paramGroupConfigParamPath'])) {
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
                        <label class="col-md-3 col-form-label">Lookup Key үзүүлэлт:</label>
                        <div class="col-md-9">
                            <p class="form-control-plaintext">
                                <?php echo ((empty($this->lookupMetaDataCodeKey)) ? '' : '(' . $this->lookupMetaDataCodeKey . ') ' . $this->lookupMetaDataNameKey); ?>
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
                                if ($this->paramsConfigs && isset($this->paramsConfigs['paramGroupConfigParamPathKey'])) {
                                    foreach ($this->paramsConfigs['paramGroupConfigParamPathKey'] as $k => $row) {
                                        foreach ($row as $p => $rowChild) {
                                ?>
                                <tr>
                                    <td>
                                        <?php
                                        echo Form::text(
                                            array(
                                                'name' => 'paramGroupConfigParamPathKey[' . $this->params['paramPath'] . '][]',
                                                'class' => 'form-control form-control-sm',
                                                'value' => $this->paramsConfigs['paramGroupConfigParamPathKey'][$this->params['paramPath']][$p]
                                            )
                                        );
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo Form::select(
                                            array(
                                                'name' => 'paramGroupConfigParamMetaKey[' . $this->params['paramPath'] . '][]',
                                                'class' => 'form-control form-control-sm',
                                                'data' => $this->lookupMetaDataParamMetaDataKey,
                                                'op_value' => 'FIELD_NAME',
                                                'op_text' => 'FIELD_NAME| |-| |META_DATA_NAME',
                                                'value' => $this->paramsConfigs['paramGroupConfigParamMetaKey'][$this->params['paramPath']][$p]
                                            )
                                        );
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo Form::text(
                                            array(
                                                'name' => 'paramGroupConfigDefaultValKey[' . $this->params['paramPath'] . '][]',
                                                'class' => 'form-control form-control-sm',
                                                'value' => $this->paramsConfigs['paramGroupConfigDefaultValKey'][$this->params['paramPath']][$p]
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
                            if ($this->lookupMetaDataNameKey != '') {
                            ?>
                                <tfoot>
                                    <tr>
                                        <td colspan="4">
                                            <a href="javascript:;" class="btn green btn-xs" onclick="groupParamConfigAddRowKey(this);">
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
var visibleEditor = CodeMirror.fromTextArea(document.getElementById("visibleCriteria_set"), {
    mode: "javascript",
    styleActiveLine: true,
    lineNumbers: true,
    lineWrapping: true,
    matchBrackets: true,
    autoCloseBrackets: true,
    indentUnit: 4,
    theme: "material"
});
$(function(){
    $('.param-criteria-tabs a[data-toggle="tab"]').on('shown.bs.tab', function(){
        visibleEditor.refresh();
    }); 
});
function groupParamConfigAddRow(elem){
    var $this = $(elem);
    var $parentTbl = $this.closest("table");
    $parentTbl.find("tbody").append('<tr>'+
        '<td><?php echo Form::text(array('name' => 'paramGroupConfigParamPath['.$this->params['paramPath'].'][]','class' => 'form-control form-control-sm')); ?></td>'+
        '<td><?php echo Form::select(array('name' => 'paramGroupConfigParamMeta['.$this->params['paramPath'].'][]', 'class' => 'form-control form-control-sm', 'data' => $this->lookupMetaDataParamMetaData, 'op_value' => 'FIELD_NAME','op_text' => 'FIELD_NAME| |-| |META_DATA_NAME','required' => 'required')); ?></td>'+
        '<td><?php echo Form::text(array('name' => 'paramGroupConfigDefaultVal['.$this->params['paramPath'].'][]','class' => 'form-control form-control-sm')); ?></td>'+
        '<td class="middle text-center"><a href="javascript:;" class="btn red btn-xs" onclick="groupParamConfigRemoveRow(this);"><i class="fa fa-trash"></i></a></td>'+
    '</tr>');
}    
function groupParamConfigAddRowKey(elem){
    var $this = $(elem);
    var $parentTbl = $this.closest("table");
    $parentTbl.find("tbody").append('<tr>'+
        '<td><?php echo Form::text(array('name' => 'paramGroupConfigParamPathKey['.$this->params['paramPath'].'][]','class' => 'form-control form-control-sm')); ?></td>'+
        '<td><?php echo Form::select(array('name' => 'paramGroupConfigParamMetaKey['.$this->params['paramPath'].'][]', 'class' => 'form-control form-control-sm', 'data' => $this->lookupMetaDataParamMetaDataKey, 'op_value' => 'FIELD_NAME','op_text' => 'FIELD_NAME| |-| |META_DATA_NAME','required' => 'required')); ?></td>'+
        '<td><?php echo Form::text(array('name' => 'paramGroupConfigDefaultValKey['.$this->params['paramPath'].'][]','class' => 'form-control form-control-sm')); ?></td>'+
        '<td class="middle text-center"><a href="javascript:;" class="btn red btn-xs" onclick="groupParamConfigRemoveRow(this);"><i class="fa fa-trash"></i></a></td>'+
    '</tr>');
}  
function groupParamConfigRemoveRow(elem){
    var $parentRow = $(elem).closest('tr');
    $parentRow.remove();
}
</script>