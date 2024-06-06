<?php  
try {
    $metaTypeCode = 'CODE';
    $metaTypeName = 'NAME';
    /* $metaTypeCode = 'id'; */
    $chartDefaultTheme = Info::chartDefaultTheme();
    $chartDefaultAggerates = Info::chartDefaultAggerates();
    $chartDefaultTypes = $this->chartMainTypeData['typeCode']; /* Info::chartDefaultTypes(); */
    $chartTypesConfigration = $this->chartMainTypeData['typeTreeList']; /* Info::chartTypesConfigration(); */
    
    $returnBuilder = issetParam($this->returnBuilder);
} catch (\Throwable $th) {
    (Array) $chartDefaultTheme = $chartDefaultAggerates = $chartDefaultTypes = $chartTypesConfigration = $chartConfigration = array();
}
/* var_dump($this->graphJsonConfig); */
?>
<div id="kpi-datamart-chart-<?php echo $this->uniqId; ?>" class="row theme-builder theme-builder-<?php echo $this->uniqId; ?>" data-kpi-layout="<?php echo issetParamZero($this->kolIndex) ?>">
    <div class="col-md-auto pl-0 ">
        <div class="kpidv-data-filter-col pr-1"></div>
    </div>
    <div class="col right-sidebar-content-for-resize content-wrapper pl-0 pr-0">
        <div class="row h-100">
        <div class="col-md-8 h-100 <?php echo ($returnBuilder === '1' ? 'd-none' : '') ?>">
            <div id="kpi-datamart-chart-render-<?php echo $this->uniqId; ?>" style="height: 500px"></div>
        </div>
        <div class="h-100 <?php echo ($returnBuilder === '1' ? 'col-md-12' : 'col-md-4 ') ?>">
            <div class="col-md-12 text-right p-0 mb-1 <?php echo ($returnBuilder === '1' ? 'd-none' : '') ?>">
                <button type="button" class="btn btn-sm green-meadow kpi-dm-chart-create" title="Чарт үүсгэх"><i class="far fa-user-chart"></i> Чарт үүсгэх</button>
            </div>
            <form class="<?php echo ($returnBuilder === '1' ? '' : 'scroll-parent px-2' ) ?> w-100">
                <div class="theme-config w-100">
                    <div class="echart card mb-0 rounded-bottom-0 p-0 ">
                        <div class="card-header h-auto m-0 px-1 py-2 bg-grey-light">
                            <h6 class="card-title pull-left w-100">
                                <a data-toggle="collapse" class="text-dark-info w-100 pull-left collapsed" href="#chart-builder-group1" aria-expanded="true"><?php echo Lang::line('Engine settings') ?></a>
                            </h6>
                        </div>
                        <div id="chart-builder-group1" class="collapse">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo Form::label(array('text'=>$this->lang->line('chart_theme'), 'class'=>'col-form-label col-md-12 text-left pl-0 pt-1 mb-1')); ?>
                                        <div class="theme-plan-row">
                                            <?php
                                                foreach ($chartDefaultTheme as $key => $row) {
                                                    $selected = $themeColor = '';
                                                    $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8'); 

                                                    if (issetParam($this->graphJsonConfig['chartConfig']['themeCode']) !== '') {
                                                        if (issetParam($this->graphJsonConfig['chartConfig']['themeCode']) === $row['code']) {
                                                            $selected = 'selected';    
                                                        }
                                                    } else {
                                                        if ($key == '0') {
                                                            $selected = 'selected';    
                                                        }
                                                    }
                                                    ?> 
                                                    <div class="row">
                                                        <div class="col-md-auto">
                                                            <a class="theme-plan-group <?php echo $selected ?>" data-rowdata="<?php echo $rowJson ?>" title="purple-passion" style="background-color: <?php echo $row['bgColor'] ?>;">
                                                                <?php foreach ($row['themeColor'] as $value) {
                                                                    $themeColor .= '<div class="theme-plan-color" style="background-color: '. $value .';"></div>';
                                                                } 
                                                                echo $themeColor;
                                                                ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card rounded-top-0 p-0 mb-0 border-top-0 border-bottom-1">
                        <div class="card-header h-auto m-0 px-1 py-2 bg-grey-light">
                            <h6 class="card-title pull-left w-100">
                                <a class="text-dark-info w-100 pull-left collapsed" data-toggle="collapse" href="#chart-builder-group2" aria-expanded="false"><?php echo Lang::line('Main') ?></a>
                            </h6>
                        </div>
                        <div id="chart-builder-group2" class="collapse show">
                            <input type="hidden" name="kpiDMChartMainType" value="<?php echo checkDefaultVal($this->graphJsonConfig['chartConfig']['mainType'], 'amchart'); ?>" />
                            <input type="hidden" name="kpiDMChartType" value="<?php echo issetParam($this->graphJsonConfig['chartConfig']['type']); ?>" />
                            
                            <div class="card-body chartRootConfigrations">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <?php echo Form::label(array('text'=>$this->lang->line('chart_type_btn'), 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1', 'style' => 'width: 100px')); ?>
                                        <div class="col">
                                            <select name="kpiDMChartType" class="form-control form-control-sm" data-placeholder="- Сонгох -">
                                                <option value="">- Сонгох -</option>
                                                <?php if ($chartDefaultTypes) {
                                                    foreach ($chartDefaultTypes as $key => $row) { 
                                                        $selected = ($row[$metaTypeCode] === issetParam($this->graphJsonConfig['chartConfig']['type'])) ? 'selected="selected"' : ''; ?>
                                                        <option data-value="<?php echo issetParam($row[$metaTypeCode]) ?>"<?php echo $selected ?> value="<?php echo $row['ID'] ?>" data-config="<?php echo $row['SUB_TYPE_CODE'] ?>" data-maintype="<?php echo $row['TYPE_CODE'] ?>"><?php echo $row[$metaTypeName] ?></option>
                                                    <?php }
                                                } ?>
                                                <option data-value="card" value="card">Card</option>
                                                <option data-value="card_vertical" value="card_vertical">Card vertical</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 kpiDMChartMapCountry-row d-none">
                                    <div class="form-group row">
                                        <?php echo Form::label(array('text'=>'Country', 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1', 'style' => 'width: 100px')); ?>
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
                                                    ),
                                                    array(
                                                        'id' => 'earth', 
                                                        'name' => 'Earth'
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
                                <div class="col-md-12 kpiDMChartCategory-row">
                                    <div class="form-group row">
                                        <?php echo Form::label(array('text'=>$this->lang->line('x_axis_btn'), 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1', 'style' => 'width: 100px')); ?>
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
                                <div class="col-md-12 kpiDMChartCategoryGroup-row d-none">
                                    <div class="form-group row">
                                        <?php echo Form::label(array('text'=>$this->lang->line('X тэнхлэгт багцлах'), 'class'=>'col-form-label col-md-auto text-right pr-0 pt-0 line-height-normal', 'style' => 'width: 100px')); ?>
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
                                <div class="col-md-12 kpiDMChartValue-row">
                                    <div class="form-group row">
                                        <?php echo Form::label(array('text'=>$this->lang->line('y_axis_btn'), 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1', 'style' => 'width: 100px')); ?>
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
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <?php echo Form::label(array('text'=>'Aggregate', 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1', 'style' => 'width: 100px')); ?>
                                        <div class="col">
                                            <?php
                                            echo Form::select(array(
                                                'class' => 'form-control form-control-sm', 
                                                'name' => 'kpiDMChartAggregate', 
                                                'data' => $chartDefaultAggerates, 
                                                'op_value' => 'id', 
                                                'op_text' => 'name', 
                                                'text' => 'notext', 
                                                'value' => issetDefaultVal($this->graphJsonConfig['chartConfig']['aggregate'], 'SUM')
                                            ));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <?php echo Form::label(array('text'=>'Утгын эрэмбэ', 'class'=>'col-form-label col-md-auto text-right pr-0 pt-0 line-height-normal', 'style' => 'width: 100px')); ?>
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
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <?php echo Form::label(array('text'=>'Мөрийн тоо', 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1', 'style' => 'width: 100px')); ?>
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
                                <div class="col-md-12 kpiDMChartLabelText-row d-none">
                                    <div class="form-group row">
                                        <?php echo Form::label(array('text'=>'Текст', 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1', 'style' => 'width: 100px')); ?>
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
                                <div class="col-md-12 kpiDMChartBgColor-row d-none">
                                    <div class="form-group row">
                                        <?php echo Form::label(array('text'=>'Өнгө', 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1', 'style' => 'width: 100px')); ?>
                                        <div class="col">
                                            <?php
                                            $bgColor = issetParam($this->graphJsonConfig['chartConfig']['bgColor']);
                                            echo Form::select(array(
                                                'class' => 'form-control form-control-sm', 
                                                'style' => 'background-color: '.$bgColor,
                                                'name' => 'kpiDMChartBgColor', 
                                                /* 'data' => $chartDefaultTheme, */
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
                                <div class="col-md-12 kpiDMChartIconName-row d-none">
                                    <div class="form-group row">
                                        <?php echo Form::label(array('text'=>'Дүрс', 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1', 'style' => 'width: 100px')); ?>
                                        <div class="col">
                                            <?php echo Form::hidden(array('name' => 'kpiDMChartIconName', 'value' => issetParam($this->graphJsonConfig['chartConfig']['iconName']))); ?>
                                            <button id="menu-iconpicker" class="btn btn-secondary btn-sm" data-search-text="<?php echo $this->lang->line('META_00109'); ?>" data-placement="top" data-iconset="fontawesome5" data-cols="6" data-rows="6" data-icon="<?php echo issetParam($this->graphJsonConfig['chartConfig']['iconName']); ?>" name="name" role="iconpicker"></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 kpiDMChartLineChartColumn-row d-none">
                                    <div class="form-group row">
                                        <?php echo Form::label(array('text'=>$this->lang->line('y_axis_btn').' /Line/', 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1 line-height-normal', 'style' => 'width: 100px')); ?>
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
                                <div class="col-md-12 kpiDMChartLineChartAggregate-row d-none">
                                    <div class="form-group row">
                                        <?php echo Form::label(array('text'=>'Aggregate /Line/', 'class'=>'col-form-label col-md-auto text-right pr-0 pt-1 line-height-normal', 'style' => 'width: 100px')); ?>
                                        <div class="col">
                                            <?php
                                            echo Form::select(array(
                                                'class' => 'form-control form-control-sm', 
                                                'name' => 'kpiDMChartLineChartAggregate', 
                                                'data' => $chartDefaultAggerates, 
                                                'op_value' => 'id', 
                                                'op_text' => 'name', 
                                                'text' => 'notext', 
                                                'value' => issetDefaultVal($this->graphJsonConfig['chartConfig']['lineChartConfig']['aggregate'], 'SUM')
                                            ));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="form-group row">
                                        <?php echo Form::label(array('text'=>'Шүүлт хадгалах', 'class'=>'col-form-label col-md-auto text-right pr-0 pt-0 line-height-normal', 'style' => 'width: 100px')); ?>
                                        <div class="col">
                                            <?php
                                            echo Form::checkbox(array(
                                                'class' => 'booleanInit', 
                                                'name' => 'kpiDMChartIsFilterSave', 
                                                'value' => '1', 
                                                'saved_val' => issetParam($this->graphJsonConfig['chartFilterCriteria']) ? '1' : ''
                                            ));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card rounded-top-0 p-0 mb-0 border-top-0 border-bottom-1 type-config<?php echo $this->uniqId ?>">
                    </div>
                    <?php 
                    /* var_dump($this->graphJsonConfig['chartConfig']); */
                    if (1 == 0 && $chartTypesConfigration) {
                        foreach ($chartTypesConfigration as $config) { 
                            ?> 
                                <div class="echart card rounded-top-0 p-0 mb-0 border-top-0 border-bottom-1 chartTypesConfigration conf_<?php echo $config['TYPE_CODE']; ?>">
                                    <div class="card-header h-auto m-0 px-1 py-2 bg-grey-light">
                                        <h6 class="card-title pull-left w-100">
                                            <a class="text-dark-info w-100 pull-left collapsed" data-toggle="collapse" href="#collapsible-<?php echo $config['TYPE_CODE']; ?>-group" aria-expanded="true"><?php echo $config['LABEL_NAME']; ?></a>
                                        </h6>
                                    </div>
                                    <div id="collapsible-<?php echo $config['TYPE_CODE']; ?>-group" class="collapse">
                                        <div class="card-body">
                                            <div class="col-md-12">
                                                <?php if (issetParamArray($config['children'])) { 
                                                    foreach ($config['children'] as $panel) { 
                                                        echo (new Mdform_Model())->renderConfigControl($panel, $this->graphJsonConfig, $config['TYPE_CODE']);
                                                    }
                                                } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                        }
                    } ?>
                </div>
            </form>
        </div>
    </div>
</div>
<style type="text/css">
    #kpi-datamart-chart-<?php echo $this->uniqId; ?> .bg-grey-light {
        background-color: #f5f5f5
    }

    #kpi-datamart-chart-<?php echo $this->uniqId; ?> .text-dark-info {
        color: #293c55 !important;
    }

    .theme-builder-<?php echo $this->uniqId; ?> .scroll-parent {
        position: absolute;
        top: 35px;
        bottom: 0;
        right: 0px;
        overflow: hidden
    }

    .theme-builder-<?php echo $this->uniqId; ?> .scroll-parent>div {
        height: 100%;
        overflow-y: auto
    }

    .theme-builder-<?php echo $this->uniqId; ?> .space-row {
        margin-left: -4px
    }

    .theme-builder-<?php echo $this->uniqId; ?> .theme-plan-row .col-xs-6:nth-child(even) {
        padding-left: 5px
    }

    .theme-builder-<?php echo $this->uniqId; ?> .theme-plan-row .col-xs-6:nth-child(odd) {
        padding-right: 5px
    }

    .theme-builder-<?php echo $this->uniqId; ?> .theme-plan-group {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        width: auto;
        height: 32px;
        overflow: hidden;
        border: 1px solid #eee;
        padding: 5px;
        border-radius: 4px;
        margin-bottom: 8px
    }

    .theme-builder-<?php echo $this->uniqId; ?> .theme-plan-group:hover {
        cursor: pointer;
    }

    .theme-builder-<?php echo $this->uniqId; ?> .theme-plan-color {
        width: 20px;
        height: 20px;
        margin-bottom: 10px;
        margin-left: 2px;
        margin-right: 2px;
        display: inline-block;
        border-radius: 3px
    }
    
</style>