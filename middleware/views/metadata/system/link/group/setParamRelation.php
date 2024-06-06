<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'paramRelation-form', 'method' => 'post')); ?>
<div class="col-md-12">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Param name', 'class'=>'col-form-label col-md-3')); ?>
        <div class="col-md-9">
            <p class="form-control-plaintext font-weight-bold"><?php echo $this->params['paramName'].' /'.$this->params['paramPath'].'/'; ?></p>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Join type', 'for'=>'joinType', 'class'=>'col-form-label col-md-3')); ?>
        <div class="col-md-3">
            <?php
            echo Form::select(
                array(
                    'name' => 'joinType',
                    'id' => 'joinType',
                    'class' => 'form-control form-control-sm',
                    'data' => (new Mddatamodel())->joinType(),
                    'op_value' => 'code',
                    'op_text' => 'name',
                    'value' => $this->joinType
                )
            );
            ?>
        </div>
    </div>
    <div class="col-md-12">
        <div class="table-scrollable">
            <table class="table group-relation-configs">
                <thead>
                    <tr>
                        <th style="width: 30px;">Batch number</th>
                        <th>Source param</th>
                        <th>Target param</th>
                        <th style="width: 15px;"></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($this->paramsConfigs) {
                    foreach ($this->paramsConfigs['paramGroupRelationSrcGroupId'] as $k=>$row) {
                        foreach ($row as $n=>$rowChild) {
                ?>
                    <tr>
                        <td>
                            <?php 
                            echo Form::text(
                                array(
                                    'name' => 'paramGroupRelationBatchNumber['.$this->params['paramPath'].'][]',
                                    'class' => 'form-control form-control-sm longInit', 
                                    'required' => 'required',
                                    'value' => $this->paramsConfigs['paramGroupRelationBatchNumber'][$k][$n]
                                )
                            ); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo Form::comboGroupNotChildMetas(
                                array(
                                    'name' => 'paramGroupRelationSrcParamPath['.$this->params['paramPath'].'][]',
                                    'class' => 'form-control form-control-sm', 
                                    'required' => 'required'
                                ), 
                                $this->metaDataId, 
                                $this->allMetas, 
                                $this->params['paramPath'], 
                                $this->paramId, 
                                $this->paramsConfigs['paramGroupRelationSrcParamPath'][$k][$n]     
                            ); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo Form::comboStructureChildMetas(
                                array(
                                    'name' => 'paramGroupRelationTrgParamPath['.$this->params['paramPath'].'][]',
                                    'class' => 'form-control form-control-sm', 
                                    'required' => 'required'
                                ), 
                                $this->metaDataId, 
                                $this->refStructureData, 
                                $this->params['paramPath'], 
                                $this->paramId, 
                                $this->refParamName,     
                                $this->paramsConfigs['paramGroupRelationTrgParamPath'][$k][$n]     
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
                <tfoot>
                    <tr>
                        <td colspan="4">
                            <a href="javascript:;" class="btn green btn-xs" onclick="groupParamConfigAddRow(this);">
                                <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('META_00103'); ?> 
                            </a>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>     
    </div>    
</div>
<?php echo Form::close(); ?>
<script type="text/javascript">
function groupParamConfigAddRow(elem){
    var _this = $(elem);
    var parentTbl = _this.closest("table");
    parentTbl.find("tbody").append('<tr>'+
            '<td><?php echo Form::text(array('name' => 'paramGroupRelationBatchNumber['.$this->params['paramPath'].'][]','class' => 'form-control form-control-sm','value'=>'1')); ?></td>'+
            '<td><?php echo Form::comboGroupNotChildMetas(array('name' => 'paramGroupRelationSrcParamPath['.$this->params['paramPath'].'][]','class' => 'form-control form-control-sm'), $this->metaDataId, $this->allMetas, $this->params['paramPath'], $this->paramId, $this->params['paramPath']); ?></td>'+
            '<td><?php echo Form::comboStructureChildMetas(array('name' => 'paramGroupRelationTrgParamPath['.$this->params['paramPath'].'][]','class' => 'form-control form-control-sm'), $this->metaDataId, $this->refStructureData, $this->params['paramPath'], $this->paramId, $this->refParamName); ?></td>'+
            '<td class="middle text-center"><a href="javascript:;" class="btn red btn-xs" onclick="groupParamConfigRemoveRow(this);"><i class="fa fa-trash"></i></a></td>'+
            '</tr>');
}    
function groupParamConfigRemoveRow(elem){
    var parentRow = $(elem).closest("tr");
    parentRow.remove();
}
</script>