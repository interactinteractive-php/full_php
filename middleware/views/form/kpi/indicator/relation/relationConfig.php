<form method="post" id="kpiDataMartVisualConfigForm">
    
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="nav-item"><a href="#datamartConfig-link" class="nav-link pb8 active" data-toggle="tab">Холбоос</a></li>
        <li class="nav-item"><a href="#datamartConfig-columns" class="nav-link pb8" data-toggle="tab">Баганын тохиргоо</a></li>
        <li class="nav-item"><a href="#datamartConfig-criteria" class="nav-link pb8" data-toggle="tab">Нөхцөлийн тохиргоо</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="datamartConfig-link">
            <div class="row">
                <div class="col">
                    <div class="mb10">
                        <button type="button" class="btn btn-sm green-meadow" onclick="kpiDataMartAddObject(this);">
                            <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('add_btn'); ?>
                        </button>
                    </div>
                    <div class="heigh-editor">
                        <div class="css-editor" id="datamart-editor" style="height: 400px; position: relative;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="datamartConfig-columns">
            <table class="table table-hover kpi-datamart-columns-config">
                <thead>
                    <tr>
                        <th style="width: 10px">№</th>
                        <th style="width: 280px" class="text-center">Баганын нэр</th>
                        <th style="width: 250px">Alias</th>
                        <th style="width: 320px">Column</th>
                        <th style="width: 110px">Aggregate</th>
                        <th>Expression</th>
                        <th style="width: 80px">Тэнхлэгийн төрөл</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($this->columns) {
                        foreach ($this->columns as $k => $col) {
                    ?>
                    <tr data-id="<?php echo $col['ID']; ?>">
                        <td><?php echo (++$k); ?>.</td>
                        <td class="text-left font-weight-bold" data-column-name="1">
                            <?php 
                            echo $col['LABEL_NAME'] . ' | ' . $col['SRC_COLUMN_NAME']; 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo Form::hidden(array('data-field-name' => 'id', 'value' => $col['ID']));
                            echo Form::hidden(array('data-field-name' => 'src_column_name', 'value' => $col['SRC_COLUMN_NAME']));
                            echo Form::hidden(array('data-field-name' => 'trg_alias_name', 'value' => $col['TRG_ALIAS_NAME']));
                            echo Form::hidden(array('data-field-name' => 'trg_indicator_id', 'value' => $col['TRG_INDICATOR_ID']));
                            echo Form::hidden(array('data-field-name' => 'trg_indicator_map_id', 'value' => $col['TRG_INDICATOR_MAP_ID']));
                            
                            echo Form::select(array(
                                'data-field-name' => 'aliasName', 
                                'class' => 'form-control form-control-sm', 
                                'data' => array(), 
                                'op_value' => 'code', 
                                'op_text' => 'code'
                            )); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo Form::select(array(
                                'data-field-name' => 'trgColumnName', 
                                'class' => 'form-control form-control-sm', 
                                'data' => array(), 
                                'op_value' => 'code', 
                                'op_text' => 'code'
                            )); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            if ($col['SEMANTIC_TYPE_NAME'] == 'Багана') {
                                
                                echo Form::select(array(
                                    'data-field-name' => 'aggregate', 
                                    'class' => 'form-control form-control-sm', 
                                    'data' => array(
                                        array(
                                            'code' => 'SUM'
                                        ), 
                                        array(
                                            'code' => 'MIN'
                                        ), 
                                        array(
                                            'code' => 'MAX'
                                        ), 
                                        array(
                                            'code' => 'AVG'
                                        ), 
                                        array(
                                            'code' => 'COUNT'
                                        )
                                    ), 
                                    'op_value' => 'code', 
                                    'op_text' => 'code', 
                                    'value' => $col['AGGREGATE_FUNCTION']
                                )); 
                            }
                            ?>
                        </td>
                        <td>
                            <?php 
                            //if ($col['SEMANTIC_TYPE_NAME'] == 'Багана') {
                                
                                echo Form::text(array(
                                    'data-field-name' => 'expression', 
                                    'class' => 'form-control form-control-sm', 
                                    'value' => $col['EXPRESSION_STRING']
                                )); 
                            //}
                            ?>
                        </td>
                        <td>
                            <?php echo ($col['SEMANTIC_TYPE_NAME'] == 'Багана') ? 'Y тэнхлэг' : 'X тэнхлэг'; ?>
                        </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="datamartConfig-criteria">
            
            <table class="table table-hover kpi-datamart-criterias-config">
                <thead>
                    <tr>
                        <th style="width: 10px">№</th>
                        <th style="width: 500px;">Alias</th>
                        <th style="width: 400px;">Columns</th>
                        <th>Нөхцөл</th>
                        <th style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($this->criterias)) {
                        foreach ($this->criterias as $c => $criteria) {
                    ?>
                    <tr>
                        <td><?php echo (++$c); ?>.</td>
                        <td>
                            <?php 
                            echo Form::hidden(array('data-field-name' => 'id', 'value' => $criteria['ID']));
                            echo Form::hidden(array('data-field-name' => 'criteria_alias_name', 'value' => $criteria['ALIAS_NAME']));
                            echo Form::hidden(array('data-field-name' => 'criteria_indicator_id', 'value' => $criteria['INDICATOR_ID']));
                            
                            echo Form::select(array(
                                'data-field-name' => 'criteriaAliasName', 
                                'class' => 'form-control form-control-sm', 
                                'data' => array(), 
                                'op_value' => 'code', 
                                'op_text' => 'code'
                            )); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo Form::select(array(
                                'data-field-name' => 'criteriaColumnName', 
                                'class' => 'form-control form-control-sm', 
                                'data' => array(), 
                                'op_value' => 'code', 
                                'op_text' => 'code'
                            )); 
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo Form::text(array(
                                'data-field-name' => 'criteriaCriteria', 
                                'class' => 'form-control form-control-sm', 
                                'value' => $criteria['CRITERIA']
                            )); 
                            ?>
                        </td>
                        <td class="text-center">
                            <a href="javascript:;" class="btn red btn-xs kpi-datamart-criterias-remove" title="<?php echo $this->lang->line('delete_btn'); ?>">
                                <i class="far fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <a href="javascript:;" class="btn green btn-xs kpi-datamart-criterias-addrow">
                                <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('add_btn'); ?> 
                            </a>
                        </td>
                    </tr>
                </tfoot>
            </table>    
        </div>
    </div>
    <input type="hidden" name="id" value="<?php echo $this->id; ?>" data-kpidatamart-id="1"/>
</form>    

<style type="text/css">
    #kpiDataMartVisualConfigForm ._jsPlumb_overlay {
        display: none;
        width: 100px;
        background-color: rgba(223, 223, 223, 0.9);
        font-size: 11px;
        line-height: 12px;
        padding: 2px;
        border: 1px #b4b4b4 solid;
        color: #000;
        z-index: 2;
    }
    #kpiDataMartVisualConfigForm ._jsPlumb_overlay._jsPlumb_hover {
        display: block;
    }
</style>