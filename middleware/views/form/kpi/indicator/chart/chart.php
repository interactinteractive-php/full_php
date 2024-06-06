<div id="kpi-datamart-chart-<?php echo $this->uniqId; ?>" class="row">
    <div class="col-md-auto pl-0">
        <div class="kpidv-data-filter-col pr-1"></div>
    </div>
    <div class="col right-sidebar-content-for-resize content-wrapper pl-0 pr-0">
        <form>
            <div class="row">
                <div class="col-md-auto" style="width: 200px">
                    
                    <div class="form-group row">
                        <?php echo Form::label(array('text'=>$this->lang->line('chart_type_btn'), 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1', 'style' => 'width: 80px')); ?>
                        <div class="col">
                            <?php 
                            $aggregate = array(
                                array(
                                    'id' => 'SUM', 
                                    'name' => 'SUM'
                                ), 
                                array(
                                    'id' => 'MAX', 
                                    'name' => 'MAX'
                                ), 
                                array(
                                    'id' => 'MIN', 
                                    'name' => 'MIN'
                                ), 
                                array(
                                    'id' => 'COUNT', 
                                    'name' => 'COUNT'
                                ), 
                                array(
                                    'id' => 'AVG', 
                                    'name' => 'AVG'
                                )
                            );
                            
                            echo Form::select(array(
                                'class' => 'form-control form-control-sm', 
                                'name' => 'kpiDMChartType', 
                                'data' => array(
                                    array(
                                        'id' => 'pie', 
                                        'name' => 'Pie'
                                    ), 
                                    array(
                                        'id' => 'donut', 
                                        'name' => 'Donut'
                                    ), 
                                    array(
                                        'id' => 'column', 
                                        'name' => 'Column'
                                    ), 
                                    array(
                                        'id' => 'bar', 
                                        'name' => 'Bar'
                                    ), 
                                    array(
                                        'id' => 'line', 
                                        'name' => 'Line'
                                    ), 
                                    array(
                                        'id' => 'radar', 
                                        'name' => 'Radar'
                                    ), 
                                    array(
                                        'id' => 'pyramid', 
                                        'name' => 'Pyramid'
                                    ), 
                                    array(
                                        'id' => 'clustered_column', 
                                        'name' => 'Clustered column'
                                    ), 
                                    array(
                                        'id' => 'stacked_column', 
                                        'name' => 'Stacked column'
                                    ), 
                                    array(
                                        'id' => 'maps', 
                                        'name' => 'Maps'
                                    ), 
                                    array(
                                        'id' => 'card', 
                                        'name' => 'Card'
                                    )
                                ), 
                                'op_value' => 'id', 
                                'op_text' => 'name', 
                                'value' => issetParam($this->graphJsonConfig['chartConfig']['type'])
                            ));
                            ?>
                        </div>
                    </div>
                    
                </div>
                
                <div class="col-md-auto kpiDMChartMapCountry-row d-none" style="width: 210px">
                    
                    <div class="form-group row">
                        <?php echo Form::label(array('text'=>'Country', 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1', 'style' => 'width: 80px')); ?>
                        <div class="col">
                            <?php
                            echo Form::select(array(
                                'class' => 'form-control form-control-sm', 
                                'name' => 'kpiDMChartMapCountry', 
                                'data' => array(
                                    array(
                                        'id' => 'usa', 
                                        'name' => 'USA'
                                    ),
                                    array(
                                        'id' => 'mongolia', 
                                        'name' => 'Mongolia'
                                    )
                                ), 
                                'op_value' => 'id', 
                                'op_text' => 'name', 
                                'value' => issetParam($this->graphJsonConfig['chartConfig']['mapsChartConfig']['country'])
                            ));
                            ?>
                        </div>
                    </div>
                    
                </div>
                
                <div class="col-md-auto kpiDMChartCategory-row" style="width: 240px">
                    
                    <div class="form-group row">
                        <?php echo Form::label(array('text'=>$this->lang->line('x_axis_btn'), 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1', 'style' => 'width: 80px')); ?>
                        <div class="col">
                            <?php
                            echo Form::select(array(
                                'class' => 'form-control form-control-sm', 
                                'name' => 'kpiDMChartCategory', 
                                'data' => $this->categoryColumns, 
                                'op_value' => 'COLUMN_NAME', 
                                'op_text' => 'LABEL_NAME', 
                                'value' => issetParam($this->graphJsonConfig['chartConfig']['axisX'])
                            ));
                            ?>
                        </div>
                    </div>
                    
                </div>
                
                <div class="col-md-auto kpiDMChartCategoryGroup-row d-none" style="width: 240px">
                    
                    <div class="form-group row">
                        <?php echo Form::label(array('text'=>$this->lang->line('X тэнхлэгт багцлах'), 'class'=>'col-form-label col-md-auto text-right pr-0 pt-0 line-height-normal', 'style' => 'width: 80px')); ?>
                        <div class="col">
                            <?php
                            echo Form::select(array(
                                'class' => 'form-control form-control-sm', 
                                'name' => 'kpiDMChartCategoryGroup', 
                                'data' => $this->categoryColumns, 
                                'op_value' => 'COLUMN_NAME', 
                                'op_text' => 'LABEL_NAME', 
                                'value' => issetParam($this->graphJsonConfig['chartConfig']['axisXGroup'])
                            ));
                            ?>
                        </div>
                    </div>
                    
                </div>
                
                <div class="col-md-auto kpiDMChartValue-row" style="width: 240px">
                    
                    <div class="form-group row">
                        <?php echo Form::label(array('text'=>$this->lang->line('y_axis_btn'), 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1', 'style' => 'width: 90px')); ?>
                        <div class="col">
                            <?php
                            if (issetParam($this->graphJsonConfig['chartConfig']['type']) == 'clustered_column') {
                                
                                echo Form::multiselect(array(
                                    'class' => 'form-control form-control-sm select2', 
                                    'name' => 'kpiDMChartValue', 
                                    'data' => $this->valueColumns, 
                                    'op_value' => 'COLUMN_NAME', 
                                    'op_text' => 'LABEL_NAME', 
                                    'multiple' => 'multiple', 
                                    'value' => issetParam($this->graphJsonConfig['chartConfig']['axisY'])
                                ));
                                
                            } else {
                                echo Form::select(array(
                                    'class' => 'form-control form-control-sm select2', 
                                    'name' => 'kpiDMChartValue', 
                                    'data' => $this->valueColumns, 
                                    'op_value' => 'COLUMN_NAME', 
                                    'op_text' => 'LABEL_NAME', 
                                    'op_custom_attr' => array(
                                        array(
                                            'attr' => 'data-showtype', 
                                            'key' => 'SHOW_TYPE'
                                        )
                                    ),  
                                    'value' => issetParam($this->graphJsonConfig['chartConfig']['axisY'])
                                ));
                            }
                            ?>
                        </div>
                    </div>
                    
                </div>
                
                <div class="col-md-auto" style="width: 170px">
                    
                    <div class="form-group row">
                        <?php echo Form::label(array('text'=>'Aggregate', 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1', 'style' => 'width: 80px')); ?>
                        <div class="col">
                            <?php
                            echo Form::select(array(
                                'class' => 'form-control form-control-sm', 
                                'name' => 'kpiDMChartAggregate', 
                                'data' => $aggregate, 
                                'op_value' => 'id', 
                                'op_text' => 'name', 
                                'text' => 'notext', 
                                'value' => issetDefaultVal($this->graphJsonConfig['chartConfig']['aggregate'], 'SUM')
                            ));
                            ?>
                        </div>
                    </div>
                    
                </div>
                <div class="col-md-auto" style="width: 190px">
                    
                    <div class="form-group row">
                        <?php echo Form::label(array('text'=>'Утгын эрэмбэ', 'class'=>'col-form-label col-md-auto text-right pr-0 pt-0 line-height-normal', 'style' => 'width: 80px')); ?>
                        <div class="col">
                            <?php
                            echo Form::select(array(
                                'class' => 'form-control form-control-sm', 
                                'name' => 'kpiDMChartValueSortType', 
                                'data' => array(
                                    array(
                                        'id' => 'ASC', 
                                        'name' => 'Өсөхөөр'
                                    ), 
                                    array(
                                        'id' => 'DESC', 
                                        'name' => 'Буурхаар'
                                    )
                                ), 
                                'op_value' => 'id', 
                                'op_text' => 'name', 
                                'value' => issetParam($this->graphJsonConfig['chartConfig']['axisYSortType'])
                            ));
                            ?>
                        </div>
                    </div>
                    
                </div>
                <div class="col-md-auto" style="width: 140px">
                    
                    <div class="form-group row">
                        <?php echo Form::label(array('text'=>'Мөрийн тоо', 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1', 'style' => 'width: 80px')); ?>
                        <div class="col">
                            <?php
                            echo Form::text(array(
                                'class' => 'form-control form-control-sm longInit', 
                                'name' => 'kpiDMChartRowNum', 
                                'value' => issetParam($this->graphJsonConfig['chartConfig']['rowNum'])
                            ));
                            ?>
                        </div>
                    </div>
                    
                </div>
                <div class="col-md-auto kpiDMChartLabelText-row d-none" style="width: 300px">
                    
                    <div class="form-group row">
                        <?php echo Form::label(array('text'=>'Текст', 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1', 'style' => 'width: 50px')); ?>
                        <div class="col">
                            <?php
                            echo Form::text(array(
                                'class' => 'form-control form-control-sm', 
                                'name' => 'kpiDMChartLabelText', 
                                'value' => issetParam($this->graphJsonConfig['chartConfig']['labelText'])
                            ));
                            ?>
                        </div>
                    </div>
                    
                </div>
                <div class="col-md-auto kpiDMChartBgColor-row d-none" style="width: 170px">
                    
                    <div class="form-group row">
                        <?php echo Form::label(array('text'=>'Өнгө', 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1', 'style' => 'width: 50px')); ?>
                        <div class="col">
                            <?php
                            $bgColor = issetParam($this->graphJsonConfig['chartConfig']['bgColor']);
                            echo Form::select(array(
                                'class' => 'form-control form-control-sm', 
                                'style' => 'background-color: '.$bgColor,
                                'name' => 'kpiDMChartBgColor', 
                                'data' => array(
                                    array(
                                        'bgColor' => '#29b6f6'
                                    ), 
                                    array(
                                        'bgColor' => '#ef5350'
                                    ), 
                                    array(
                                        'bgColor' => '#66bb6a'
                                    ), 
                                    array(
                                        'bgColor' => '#5c6bc0'
                                    )
                                ),
                                'op_custom_attr' => array(
                                    array(
                                        'attr' => 'style', 
                                        'key' => 'bgColor'
                                    )
                                ),
                                'op_value' => 'bgColor', 
                                'op_text' => 'bgColor', 
                                'value' => $bgColor
                            ));
                            ?>
                        </div>
                    </div>
                    
                </div>
                <div class="col-md-auto kpiDMChartIconName-row d-none" style="width: 150px">
                    
                    <div class="form-group row">
                        <?php echo Form::label(array('text'=>'Дүрс', 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1', 'style' => 'width: 50px')); ?>
                        <div class="col">
                            <?php echo Form::hidden(array('name' => 'kpiDMChartIconName', 'value' => issetParam($this->graphJsonConfig['chartConfig']['iconName']))); ?>
                            <button id="menu-iconpicker" class="btn btn-secondary btn-sm" data-search-text="<?php echo $this->lang->line('META_00109'); ?>" data-placement="top" data-iconset="fontawesome5" data-cols="6" data-rows="6" data-icon="<?php echo issetParam($this->graphJsonConfig['chartConfig']['iconName']); ?>" name="name" role="iconpicker"></button>
                        </div>
                    </div>
                    
                </div>
                <div class="col-md-auto kpiDMChartLineChartColumn-row d-none" style="width: 290px">
                    
                    <div class="form-group row">
                        <?php echo Form::label(array('text'=>$this->lang->line('y_axis_btn').' /Line/', 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1 line-height-normal', 'style' => 'width: 65px')); ?>
                        <div class="col">
                            <?php 
                            echo Form::select(array(
                                'class' => 'form-control form-control-sm select2', 
                                'name' => 'kpiDMChartLineChartColumn', 
                                'data' => $this->valueColumns, 
                                'op_value' => 'COLUMN_NAME', 
                                'op_text' => 'LABEL_NAME', 
                                'value' => issetParam($this->graphJsonConfig['chartConfig']['lineChartConfig']['column'])
                            ));
                            ?>
                        </div>
                    </div>
                    
                </div>
                <div class="col-md-auto kpiDMChartLineChartAggregate-row d-none" style="width: 170px">
                    
                    <div class="form-group row">
                        <?php echo Form::label(array('text'=>'Aggregate /Line/', 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1 line-height-normal', 'style' => 'width: 80px')); ?>
                        <div class="col">
                            <?php
                            echo Form::select(array(
                                'class' => 'form-control form-control-sm', 
                                'name' => 'kpiDMChartLineChartAggregate', 
                                'data' => $aggregate, 
                                'op_value' => 'id', 
                                'op_text' => 'name', 
                                'text' => 'notext', 
                                'value' => issetDefaultVal($this->graphJsonConfig['chartConfig']['lineChartConfig']['aggregate'], 'SUM')
                            ));
                            ?>
                        </div>
                    </div>
                    
                </div>
                <div class="col-md-auto" style="width: 140px">
                    
                    <div class="form-group row">
                        <?php echo Form::label(array('text'=>'Шүүлт хадгалах', 'class'=>'col-form-label col-md-auto text-right pr-0 pt-0 line-height-normal', 'style' => 'width: 80px')); ?>
                        <div class="col">
                            <?php
                            echo Form::checkbox(array(
                                'class' => 'form-control form-control-sm', 
                                'name' => 'kpiDMChartIsFilterSave', 
                                'value' => '1', 
                                'saved_val' => issetParam($this->graphJsonConfig['chartFilterCriteria']) ? '1' : ''
                            ));
                            ?>
                        </div>
                    </div>
                    
                </div>
                <div class="col-md-auto" style="width: 50px">
                    <button type="button" class="btn btn-sm green-meadow kpi-dm-chart-create" title="Чарт үүсгэх"><i class="far fa-user-chart"></i></button>
                </div>
            </div>
            
        </form>
        
        <div id="kpi-datamart-chart-render-<?php echo $this->uniqId; ?>" style="height: 500px"></div>
    </div>
</div>   