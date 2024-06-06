<?php $mvctrl = new Mdwidget(); ?>
<div class="bg-white p-2 pl3 pr0 pt0">
    <?php
    $contextMenu = [];

    foreach ($this->process as $process) {

        $srcIndicatorId = $process['structure_indicator_id'];
        $crudIndicatorId = issetParam($process['crud_indicator_id']);
        $isFillRelation = issetParam($process['is_fill_relation']);
        $isNormalRelation = issetParam($process['is_normal_relation']);
        $typeCode = $process['type_code'];
        $kpiTypeId = $process['kpi_type_id'];
        $buttonName = $className = $onClick = $description = $opt = '';

        if ($srcIndicatorId == $this->indicatorId) {

            if ($typeCode == 'create') {

                $labelName = $process['label_name'] == 'Нэмэх' ? $this->lang->line('add_btn') : $this->lang->line($process['label_name']);
                $className = 'btn btn-success btn-circle btn-sm';
                $buttonName = '<i class="far fa-plus"></i> '.$labelName;

                if ($isNormalRelation) {
                    $onClick = "mvNormalRelationRender(this, '$kpiTypeId', '".$this->indicatorId."', {methodIndicatorId: $crudIndicatorId, structureIndicatorId: $srcIndicatorId});";
                } else {
                    $onClick = "manageKpiIndicatorValue(this, '$kpiTypeId', '".$this->indicatorId."', false);";
                }

            } elseif ($typeCode == 'update') {

                $labelName = $process['label_name'] == 'Засах' ? $this->lang->line('edit_btn') : $this->lang->line($process['label_name']);
                $isUpdate = true;

                if ($isFillRelation) {
                    $opt = ', {fillSelectedRow: true, mode: \'update\'}';
                } 

                $className = 'btn btn-warning btn-circle btn-sm';
                $buttonName = '<i class="far fa-edit"></i> '.$labelName;

                if ($isNormalRelation) {
                    $onClick = "mvNormalRelationRender(this, '$kpiTypeId', '".$this->indicatorId."', {methodIndicatorId: $crudIndicatorId, structureIndicatorId: $srcIndicatorId, mode: 'update'});";
                } else {
                    if (issetParam($process['widget_code']) !== '') {
                        $onClick = "mvWidgetRelationRender(this, '$kpiTypeId', '".$this->indicatorId."', {methodIndicatorId: $crudIndicatorId, structureIndicatorId: $srcIndicatorId, mode: 'update', widgetCode: '". $process['widget_code'] ."'});";
                    } else {
                        $onClick = "manageKpiIndicatorValue(this, '$kpiTypeId', '".$this->indicatorId."', true$opt);";
                    }
                }

                $contextMenu[] = array(
                    'crudIndicatorId' => $crudIndicatorId, 
                    'labelName' => $labelName,
                    'onClick' => $onClick,
                    'actionName' => 'edit',
                    'iconName' => 'edit', 
                    'data-actiontype' => $typeCode, 
                    'data-main-indicatorid' => $this->indicatorId, 
                    'data-structure-indicatorid' => $this->indicatorId, 
                    'data-crud-indicatorid' => $crudIndicatorId,
                    'data-mapid' => issetParam($process['map_id'])
                );

            } elseif ($typeCode == 'read') {

                $isUpdate = true;
                $className = 'btn purple btn-circle btn-sm';
                $buttonName = '<i class="fas fa-eye"></i> '.$this->lang->line('view_btn');

                if ($isNormalRelation) {
                    $onClick = "mvNormalRelationRender(this, '$kpiTypeId', '".$this->indicatorId."', {methodIndicatorId: $crudIndicatorId, structureIndicatorId: $srcIndicatorId, mode: 'view'});";
                } else {
                    $onClick = "manageKpiIndicatorValue(this, '$kpiTypeId', '".$this->indicatorId."', true, {mode: 'view'});";
                }

                $contextMenu[] = array(
                    'crudIndicatorId' => $crudIndicatorId, 
                    'labelName' => $this->lang->line('view_btn'),
                    'onClick' => $onClick,
                    'actionName' => 'view',
                    'iconName' => 'eye', 
                    'data-actiontype' => $typeCode, 
                    'data-main-indicatorid' => $this->indicatorId, 
                    'data-structure-indicatorid' => $this->indicatorId, 
                    'data-crud-indicatorid' => $crudIndicatorId,
                    'data-mapid' => issetParam($process['map_id'])
                );

            } elseif ($typeCode == 'delete') {

                $isDelete = true;
                $className = 'btn btn-danger btn-circle btn-sm';
                $buttonName = '<i class="far fa-trash"></i> '.$this->lang->line('delete_btn');
                $onClick = "removeKpiIndicatorValue(this, '".$this->indicatorId."');";

                $contextMenu[] = array(
                    'crudIndicatorId' => $crudIndicatorId, 
                    'labelName' => $this->lang->line('delete_btn'),
                    'onClick' => $onClick,
                    'actionName' => 'delete',
                    'iconName' => 'trash', 
                    'data-actiontype' => $typeCode, 
                    'data-main-indicatorid' => $this->indicatorId, 
                    'data-structure-indicatorid' => $this->indicatorId, 
                    'data-crud-indicatorid' => $crudIndicatorId,
                    'data-mapid' => issetParam($process['map_id'])
                );

            } elseif ($typeCode == 'config') {

                $isDelete = true;
                $className = 'btn blue-steel btn-circle btn-sm';
                $buttonName = '<i class="far fa-tools"></i> '.$this->lang->line('Config');
                $onClick = "mapKpiIndicatorValue(this, '$kpiTypeId', '".$this->indicatorId."', 'config');";

                $contextMenu[] = array(
                    'crudIndicatorId' => $crudIndicatorId, 
                    'labelName' => $this->lang->line('Config'),
                    'onClick' => $onClick,
                    'actionName' => 'config',
                    'iconName' => 'tools', 
                    'data-actiontype' => $typeCode, 
                    'data-main-indicatorid' => $this->indicatorId, 
                    'data-structure-indicatorid' => $this->indicatorId, 
                    'data-crud-indicatorid' => $crudIndicatorId,
                    'data-mapid' => issetParam($process['map_id'])
                );

            } elseif ($typeCode == '360') {

                $isDelete = true;
                $className = 'btn blue-steel btn-circle btn-sm';
                $buttonName = '<i class="far fa-tools"></i> '.$this->lang->line('360');
                $onClick = "mapKpiIndicatorValue(this, '$kpiTypeId', '".$this->indicatorId."', '360');";

                $contextMenu[] = array(
                    'crudIndicatorId' => $crudIndicatorId, 
                    'labelName' => $this->lang->line('360'),
                    'onClick' => $onClick,
                    'actionName' => '360',
                    'iconName' => 'tools', 
                    'data-actiontype' => $typeCode, 
                    'data-main-indicatorid' => $this->indicatorId, 
                    'data-structure-indicatorid' => $this->indicatorId, 
                    'data-crud-indicatorid' => $crudIndicatorId,
                    'data-mapid' => issetParam($process['map_id'])
                );

            } elseif ($typeCode == 'excel') {

                $className = 'btn green btn-circle btn-sm';
                $buttonName = '<i class="far fa-file-excel"></i> '.$this->lang->line('pf_excel_import');
                $onClick = "excelImportKpiIndicatorValue(this, '".$this->indicatorId."');";

            } elseif ($typeCode == 'excel_export_one_line') {

                $className = 'btn green btn-circle btn-sm';
                $buttonName = '<i class="far fa-file-excel"></i> Эксель нэг мөрөөр татах';
                $onClick = "exportExcelOneLineKpiIndicatorValue(this, '".$this->indicatorId."');";

            } elseif ($typeCode == 'export') {

                $isDelete = true;
                $className = 'btn green btn-circle btn-sm';
                $buttonName = '<i class="far fa-download"></i> '.$this->lang->line('excel_export_btn');
                $onClick = "exportKpiIndicatorValue(this, '".$this->indicatorId."');";

            } elseif ($kpiTypeId == '1191') {

                $className = 'btn blue-steel btn-circle btn-sm';
                $buttonName = '<i class="far fa-play"></i> ' . $this->lang->line($process['label_name'] ? $process['label_name'] : $process['name']);
                $onClick = "manageKpiIndicatorValue(this, '$kpiTypeId', '$crudIndicatorId', false, {transferSelectedRow: true});";

            } elseif ($kpiTypeId == '1080') {

                $className = 'btn blue-steel btn-circle btn-sm';
                $buttonName = '<i class="far fa-play"></i> ' . $this->lang->line($process['label_name'] ? $process['label_name'] : $process['name']);
                $onClick = "callWebServiceKpiIndicatorValue(this, '$crudIndicatorId');";
            } 

        }
    }    
    
    $segmentData = Arr::groupByArrayByNullKey($this->segmentData, 'SEGMENTATION_NAME'); 
    ?>
    <div class="card light shadow card-multi-tab">
        <div class="card-header header-elements-inline tabbable-line" style="border-bottom: none;">
            <ul class="nav nav-tabs card-multi-tab-navtabs">
                <?php
                $tindex = 12324234221;
                foreach ($segmentData as $groupName => $groupRow) {
                    if ($groupName != 'яяяrow') {                
                ?>
                    <li>
                        <a style="font-weight: normal;" class="<?php echo $tindex == 12324234221 ? ' active' : ''; ?>" href="#app_tab_<?php echo $tindex++; ?>" data-toggle="tab"><?php echo $groupName; ?></a>
                    </li>
                <?php } 
                } ?>
            </ul>
        </div>
        <div class="card-body p-0 mt15">
            <div class="tab-content card-multi-tab-content">
                <?php
                $tindex = 12324234221;
                foreach ($segmentData as $groupName => $groupRow) {
                    if ($groupName != 'яяяrow') {              
                ?>                
                <div class="tab-pane<?php echo $tindex == 12324234221 ? ' active' : ''; ?>" id="app_tab_<?php echo $tindex++; ?>">    
                    <div class="slick-carousel">
                        <?php 
                        $ids = [];
                        if ($groupRow['rows']) {
                            foreach ($groupRow['rows'] as $rdata) {
                                $ids[] = $rdata['SRC_RECORD_ID'];
                            }
                        }
                        $dataResult = $mvctrl->mvWidgetCardListData($this->indicatorId, $ids);
                        $dataResult = $dataResult['rows'];
                        
                        if ($dataResult) {
                            foreach($dataResult as $row) { ?>                
                                <div class="rounded-xl no-dataview" data-rowdata="<?php echo htmlentities(json_encode($row, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>" style="background-color:#fff;">
                                    <div class="flex justify-between w-full h-full">
                                        <div class="p-3">
                                            <div class="flex" style="text-align: center;">
                                                <div class="p-4 rounded-3xl flex items-center justify-center ml55" style="height: 50px;width: 50px;background-size: cover; color: rgb(118, 51, 107);background-color:#C0DCFF;background-image:url('<?php echo issetParam($row[$this->relationViewConfig['position-1']]) ?>');background-position: center;border-radius: 50px;">
                                                </div>
                                                <div class="mt15">
                                                    <span class="text-sm txt-span lg:text-base text-base text-gray-700 block font-bold" style="font-size:14px;text-overflow: ellipsis;overflow: hidden;white-space: nowrap;width: 180px;">
                                                        <?php echo issetParam($row[$this->relationViewConfig['position-2']]) ?>
                                                    </span>
                                                    <span class="" style="text-align:center">
                                                        <span class="line-clamp-0 d-block txt-span" style="color:#bababa;text-overflow: ellipsis;overflow: hidden;white-space: nowrap;width: 180px;">
                                                            <?php echo issetParam($row[$this->relationViewConfig['position-3']]) ?>
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between" style="margin-top: 15px;">
                                                <div style="text-align: center;padding-left: 15px;">
                                                    <div style="width: 40px;height: 40px;background-color: #ededed;padding: 12px 9px 12px 9px;border-radius: 50px;"><?php echo issetParam($row[$this->relationViewConfig['position-4']]) ?></div>
                                                    <div class="mt6 txt-span"><?php echo issetParam($row[$this->relationViewConfig['position-6']]) ?></div>
                                                </div>
                                                <div style="height: 40px;width: 1px;background: #e8e8e8;"></div>
                                                <div style="text-align: center;padding-right: 15px;">
                                                    <div style="width: 40px;height: 40px;background-color: #d5fff6;padding: 12px 9px 12px 9px;border-radius: 50px;"><?php echo issetParam($row[$this->relationViewConfig['position-5']]) ?></div>
                                                    <div class="mt6 txt-span"><?php echo issetParam($row[$this->relationViewConfig['position-7']]) ?></div>
                                                </div>
                                            </div>
                                            <div class="mt15" style="text-align: center;">
                                                <a class="btn btn-success btn-circle btn-sm txt-span" style="background-color: #FFF;border: 1px solid #41c7ae;border-radius: 100px !important;color: #41c7ae;padding: 6px 20px 6px 20px;" data-actiontype="update" href="javascript:;"><?php echo issetParam($row[$this->relationViewConfig['position-8']]) ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>      
                    </div>
                </div>
                <?php } 
                } ?>                
            </div>
        </div>
    </div>                    
</div>
<div class="row indicatorView mt20" style="padding: 0 5px;">
    <div id="object-value-list-<?php echo $this->indicatorId; ?>" class="col-md-12">
        <div class="render-object-viewer">
            <div class="row">
                <div class="col-md-12">
                    <div class="row viewer-container">
                        <?php echo $this->renderGridList; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>     
</div>    

<style>
    .slick-carousel {
        display: flex;
        align-items: center;
    }
    .slick-carousel .slick-slide {
        width: 220px;
        height: 290px;
        margin: 0 10px;
        border: 1px solid #e8e8e8;
        border-radius: 12px;        
    }
    .slick-carousel .slick-slide.selectedcard .txt-span {
        color:#fff !important;
    }
    .slick-carousel .slick-slide.selectedcard a.txt-span {
        background-color:#5862e3 !important;
        border-color: #fff !important;
    }
    .slick-carousel .slick-slide.selectedcard {
        background-color:#5862e3 !important;        
    }
    .slick-carousel .slick-slide:hover {
        border-color:#5862e3 !important;
        cursor: pointer !important;
    }
    .slick-carousel .slick-list {
        margin-left: 20px;
        margin-right: 20px;
    }
    .mv-datalist-container .div-objectdatagrid-<?php echo $this->indicatorId ?> .datagrid-header td, 
    .mv-datalist-container .div-objectdatagrid-<?php echo $this->indicatorId ?> .datagrid-footer td {
        background-color:#fff !important;
    }    
    .mv-datalist-container .div-objectdatagrid-<?php echo $this->indicatorId ?> .datagrid-body td {
        border-bottom: 1px solid #ddd;
        padding-top: 8px;
        padding-bottom: 8px;
    }    
    .mv-datalist-container .div-objectdatagrid-<?php echo $this->indicatorId ?> .datagrid-filter-row {
        display: none;
    }    
</style>

<script>
    $('.slick-carousel').slick({
        // autoplay: true,
        // autoplaySpeed: 1500,
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: true,
        variableWidth: true,
        dots: false,
        prevArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer" class=""><i class="far fa-angle-left" style="font-size:22px;margin: 9px;"></i></div>',
        nextArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer" class=""><i class="far fa-angle-right" style="font-size:22px;margin: 9px;"></i></div>'       
    });    
    setTimeout(function() {
        $(".slick-carousel").css("width", $('#objectdatacustomgrid-<?php echo $this->indicatorId ?>').width() + 240);
    }, 10);
    $('.slick-slide').click(function() {
        var $this = $(this);
        if ($this.hasClass('selectedcard')) {
            $this.removeClass('selectedcard');
            return;
        }
        $this.closest('.slick-carousel').find('.selectedcard').removeClass('selectedcard');
        $this.addClass('selectedcard');
    });
    
    <?php
    $menuCallBack = $menuItems = '';

    foreach ($contextMenu as $menu) {

//        $menu['onClick'] = str_replace('this', '$a', $menu['onClick']);

        $menuCallBack .= 'if (key === \''.$menu['crudIndicatorId'].'_'.$menu['data-actiontype'].'\') { ';

//            $menuCallBack .= 'var $a = $(\'<a />\'); ';
//            $menuCallBack .= '$a.attr(\'data-actiontype\', \''.$menu['data-actiontype'].'\')';
//            $menuCallBack .= '.attr(\'data-main-indicatorid\', \''.$menu['data-main-indicatorid'].'\')';
//            $menuCallBack .= '.attr(\'data-structure-indicatorid\', \''.$menu['data-structure-indicatorid'].'\')';
//            $menuCallBack .= '.attr(\'data-crud-indicatorid\', \''.$menu['data-crud-indicatorid'].'\')';
//            $menuCallBack .= '.attr(\'data-mapid\', \''.$menu['data-mapid'].'\'); ';

            $menuCallBack .= $menu['onClick'];
        $menuCallBack .= '} ';

        $menuItems .= '"'.$menu['crudIndicatorId'].'_'.$menu['data-actiontype'].'": {name: \''.$menu['labelName'].'\', icon: \''.$menu['iconName'].'\'}, ';
    }
    ?>
    $.contextMenu({
        selector: '.slick-slide',
        callback: function (key, opt) {
            <?php echo $menuCallBack; ?>
        },
        items: {
            <?php echo $menuItems; ?> 
        }
    });    
    
</script>