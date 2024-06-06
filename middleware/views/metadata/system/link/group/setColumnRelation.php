<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'groupRelation-form', 'method' => 'post')); ?>
<div class="form-group row fom-row">
    <?php echo Form::label(array('text'=>'Param name', 'class'=>'col-form-label col-md-3')); ?>
    <div class="col-md-9">
        <p class="form-control-plaintext font-weight-bold"><?php echo $this->params['paramName'].' /'.$this->params['paramPath'].'/'; ?></p>
    </div>
</div>
<div class="table-scrollable">
    <table class="table group-relation-configs">
        <thead>
            <tr>
                <th style="width: 30px;">Batch number</th>
                <th>Source param</th>
                <th style="width: 40%;">Target param</th>
                <th>Default Value</th>
                <th style="width: 15px;"></th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($this->paramsConfigs && isset($this->paramsConfigs['paramGroupRelationBatchNumber'])) {
            foreach ($this->paramsConfigs['paramGroupRelationBatchNumber'] as $k=>$row) {
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
                    echo Form::text(
                        array(
                            'name' => 'paramGroupRelationSrcParamPath['.$this->params['paramPath'].'][]',
                            'class' => 'form-control form-control-sm', 
                            'required' => 'required',
                            'value' => $this->paramsConfigs['paramGroupRelationSrcParamPath'][$k][$n]
                        )
                    ); 
                    ?>
                </td>
                <td>
                    <?php 
                    echo Form::text(
                        array(
                            'name' => 'paramGroupRelationTrgParamPath['.$this->params['paramPath'].'][]',
                            'class' => 'form-control form-control-sm', 
                            'required' => 'required',
                            'value' => $this->paramsConfigs['paramGroupRelationTrgParamPath'][$k][$n]
                        )
                    ); 
                    ?>
                </td>
                <td>
                    <?php 
                    echo Form::text(
                        array(
                            'name' => 'paramGroupRelationDefaultValue['.$this->params['paramPath'].'][]',
                            'class' => 'form-control form-control-sm',
                            'value' => $this->paramsConfigs['paramGroupRelationDefaultValue'][$k][$n]
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
        <tfoot>
            <tr>
                <td colspan="5">
                    <a href="javascript:;" class="btn green btn-xs" onclick="groupParamConfigAddRow(this);">
                        <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('META_00103'); ?> 
                    </a>
                </td>
            </tr>
        </tfoot>
    </table>
</div>       
<?php echo Form::close(); ?>

<script type="text/javascript">
function groupParamConfigAddRow(elem){
    var $this = $(elem);
    var $parentTbl = $this.closest("table");
    $parentTbl.find("tbody").append('<tr>'+
        '<td><?php echo Form::text(array('name' => 'paramGroupRelationBatchNumber['.$this->params['paramPath'].'][]','class' => 'form-control form-control-sm longInit')); ?></td>'+
        '<td><?php echo Form::text(array('name' => 'paramGroupRelationSrcParamPath['.$this->params['paramPath'].'][]','class' => 'form-control form-control-sm')); ?></td>'+
        '<td><?php echo Form::text(array('name' => 'paramGroupRelationTrgParamPath['.$this->params['paramPath'].'][]','class' => 'form-control form-control-sm')); ?></td>'+
        '<td><?php echo Form::text(array('name' => 'paramGroupRelationDefaultValue['.$this->params['paramPath'].'][]','class' => 'form-control form-control-sm')); ?></td>'+
        '<td class="middle text-center"><a href="javascript:;" class="btn red btn-xs" onclick="groupParamConfigRemoveRow(this);"><i class="fa fa-trash"></i></a></td>'+
    '</tr>');
    
    Core.initNumber($parentTbl.find('tbody > tr:last'));
}    
function groupParamConfigRemoveRow(elem){
    var $parentRow = $(elem).closest("tr");
    $parentRow.remove();
}
</script>