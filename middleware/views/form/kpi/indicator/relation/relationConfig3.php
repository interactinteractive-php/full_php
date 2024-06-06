<form method="post" id="kpiDataMartVisualConfigForm">
    
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="nav-item"><a href="#datamartConfig-link2" class="nav-link pb8 active" data-toggle="tab">Холбоос</a></li>
        <li class="nav-item d-none"><a href="#datamartConfig-pivot2" class="nav-link pb8" data-toggle="tab">Баганын тохиргоо</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="datamartConfig-link2">
            <div class="row">
                <div class="col">
                    <div class="mb10 d-none" style="z-index:100;position: relative;">
                        <button type="button" class="btn btn-sm green-meadow" onclick="kpiDataMartAddObject2(this);">
                            <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('add_btn'); ?>
                        </button>
                    </div>
                    <div id="app">
                        <div id="canvas"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="datamartConfig-pivot2">
            <table class="table table-hover kpi-datamart-columns-config2">
                <thead>
                    <tr>
                        <th style="width: 10px">№</th>
                        <th style="width: 280px" class="text-center">Баганын нэр</th>
                        <th>Alias</th>
                        <th>Column</th>
                        <th style="width: 120px">Aggregate</th>
                        <th>Expression</th>
                        <th>Тэнхлэгийн төрөл</th>
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
                            echo Form::hidden(array('data-field-name' => 'id2', 'value' => $col['ID']));
                            echo Form::hidden(array('data-field-name' => 'src_column_name2', 'value' => $col['SRC_COLUMN_NAME']));
                            echo Form::hidden(array('data-field-name' => 'trg_alias_name2', 'value' => $col['TRG_ALIAS_NAME']));
                            echo Form::hidden(array('data-field-name' => 'trg_indicator_id2', 'value' => $col['TRG_INDICATOR_ID']));
                            echo Form::hidden(array('data-field-name' => 'trg_indicator_map_id2', 'value' => $col['TRG_INDICATOR_MAP_ID']));
                            
                            echo Form::select(array(
                                'data-field-name' => 'aliasName2', 
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
                                'data-field-name' => 'trgColumnName2', 
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
                                    'data-field-name' => 'aggregate2', 
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
                            if ($col['SEMANTIC_TYPE_NAME'] == 'Багана') {
                                
                                echo Form::text(array(
                                    'data-field-name' => 'expression2', 
                                    'class' => 'form-control form-control-sm', 
                                    'value' => $col['EXPRESSION_STRING']
                                )); 
                            }
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
    </div>
    <input type="hidden" value="<?php echo $this->id; ?>" data-kpidatamart-id="3"/>
    <input type="hidden" value="<?php echo $this->mainId; ?>" data-kpidatamart-id="4"/>
    <input type="hidden" value="<?php echo $this->idIndicatorId; ?>" data-kpidatamart-id="5"/>
</form>    
<script src="<?php echo autoVersion('assets/rappidjs/database2/bundle.js'); ?>" type="text/javascript"></script>
 <!--<script src="http://localhost:8080/bundle.js" type="text/javascript"></script>-->  