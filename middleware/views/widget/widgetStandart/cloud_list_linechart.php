<?php $renderAtom = new Mdwidget(); ?>
<section data-sectioncode="6" class="col-span-12">
    <div class="w-full h-full grid grid-cols-12 gap-5" style="">
    <div class="w-full col-span-12 lg:col-span-12">
        <div style="font-size:18px;color:#585858;" class="font-bold"><?php echo Lang::line(issetParam($this->jsonAttr['title'])) ?></div>
        <div style="color:#BCB5C3;font-size: 14px;margin-bottom: <?php echo issetParam($this->jsonAttr['isListHideTitle']) == 1 ? '15' : '0'; ?>px"><?php echo Lang::line(issetParam($this->jsonAttr['subTitle'])) ?></div>
        <div class="overflow-y-auto">
            <table class="w-full whitespace-nowrap" id="table-<?php echo $this->uniqId; ?>">
                <?php if (issetParam($this->jsonAttr['isListHideTitle']) != 1) { ?>
                    <thead>
                        <tr tabindex="0" class="bold h-12" style="color:#BCB5C3;    font-size: 12px;">
                            <th class="font-normal text-left" style="color:#BCB5C3;font-weight:600;width:200px">Дэлгүүр</th>
                            <th class="font-normal text-left pl-2" style="color:#BCB5C3;font-weight:600;width:80px">Баримтын огноо</th>
                            <th class="font-normal text-left pl-2" style="color:#BCB5C3;font-weight:600;width:200px">Чарт</th>
                            <th class="font-normal text-left px-2" style="color:#BCB5C3;font-weight:600;text-align: right !important">Нийт дүн</th>
                        </tr>
                    </thead>
                <?php } ?>
                <tbody class="w-full">
                <?php 
                if ($this->datasrc) {
                    foreach($this->datasrc as $index => $row) { 
                        $chartdata = explode('.', $renderAtom->renderAtomPath("xaxis", $this->positionConfig));
                        ?>                    
                        <tr tabindex="0" class="<?php echo issetParam($this->jsonAttr['isListHideTitle']) == 1 ? '' : 'border-b border-t'; ?> focus:outline-none text-sm leading-none text-gray-800 bg-white hover:bg-gray-100 border-gray-100" style="height: 40px;background-color: transparent !important;">
                            <?php if ($renderAtom->renderAtom($row, "position5", $this->positionConfig)) { ?>
                            <td class="<?php echo issetParam($this->jsonAttr['isListHideTitle']) == 1 ? 'py-1' : ''; ?>">
                                <div class="p-3 rounded-xl flex items-center justify-center" style="height: 50px;width: 50px;aspect-ratio: auto 1 / 1; color: rgb(118, 51, 107);background-color:<?php echo $renderAtom->renderAtom($row, "posiotn7", $this->positionConfig, "#C0DCFF") ?>">
                                    <i data-tposition="position5" data-tpath="<?php echo $renderAtom->renderAtomPath("position5", $this->positionConfig); ?>" style="color:<?php echo $renderAtom->renderAtom($row, "position6", $this->positionConfig) ?>" class="far <?php echo $renderAtom->renderAtom($row, "position5", $this->positionConfig, "fa-smile") ?> hover:text-sso "></i>
                                </div>                            
                            </td>
                            <?php } ?>
                            <td class="<?php echo issetParam($this->jsonAttr['isListHideTitle']) == 1 ? 'py-1 pl-2' : ''; ?>">
                                <p style="font-size: 12px;" class="<?php echo issetParam($this->jsonAttr['isListHideTitle']) == 1 ? 'font-bold' : ''; ?>" data-tposition="position1" data-tpath="<?php echo $renderAtom->renderAtomPath("position1", $this->positionConfig); ?>"><?php echo $renderAtom->renderAtom($row, "position1", $this->positionConfig, 'Default value') ?></p>
                            </td>
                            <td class="<?php echo issetParam($this->jsonAttr['isListHideTitle']) == 1 ? 'py-1' : ''; ?> pl-2" style="width:130px">
                                <p style="font-size: 12px;" data-tposition="position2" data-tpath="<?php echo $renderAtom->renderAtomPath("position2", $this->positionConfig); ?>"><?php echo $renderAtom->renderAtom($row, "position2", $this->positionConfig, 'Default value') ?></p>
                            </td>
                            <td class="<?php echo issetParam($this->jsonAttr['isListHideTitle']) == 1 ? 'py-1' : ''; ?> pl-2">
                                <div class="widget_chartdiv" data-srcjson='<?php echo json_encode($row[$chartdata[0]]); ?>' id="chartdiv<?php echo $this->uniqId.$index; ?>" style="height: 60px;width:100%"></div>
                            </td>
                            <td class="<?php echo issetParam($this->jsonAttr['isListHideTitle']) == 1 ? 'py-1' : ''; ?> px-2">
                                <p style="font-size: 12px;text-align: right" data-tposition="position4" data-tpath="<?php echo $renderAtom->renderAtomPath("position4", $this->positionConfig); ?>"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position4", $this->positionConfig, '115000')) ?></p>
                            </td>
                        </tr>
                    <?php }
                } ?>                       
                </tbody>
            </table>
        </div>
        <div class="mt-2 cursor-pointer" style="display: inline-block;float: right;">
            <?php echo '<span style="color:#A0A0A0;font-size: 11px" data-tposition="position13" data-tpath="'.$renderAtom->renderAtomPath("position13", $this->positionConfig).'" style="">'.Lang::line($renderAtom->renderAtom($row, "position13", $this->positionConfig, 'Бүгдийг харах')).'</span>'; ?>
        </div>
    </div>                 
    </div>
</section>
<style>
    .cloud_list_widget_bgF9F9F9 {
        background-color: #F9F9F9;
    }
</style>
<script>
    $('#table-<?php echo $this->uniqId; ?> tbody tr').each(function(i, r){
        am4core.useTheme(am4themes_animated);
        var container = am4core.create("chartdiv<?php echo $this->uniqId; ?>"+i, am4core.Container);
        container.layout = "grid";
        container.fixedWidthGrid = false;
        container.width = am4core.percent(100);
        container.height = am4core.percent(100);

        // Color set
        var colors = new am4core.ColorSet();    

        function createLine(title, data, color) {
            var chart = container.createChild(am4charts.XYChart);
            chart.width = am4core.percent(45);
            chart.height = 70;
            chart.data = data;
            chart.titles.template.fontSize = 10;
            chart.titles.template.textAlign = "left";
            chart.titles.template.isMeasured = false;
            chart.titles.create().text = title;
            chart.padding(5, 5, 2, 5);
            // Create axes
            let categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "year";
            categoryAxis.startLocation = 0.5;
            categoryAxis.endLocation = 0.7;
            categoryAxis.renderer.grid.template.disabled = true;
            categoryAxis.renderer.labels.template.disabled = true;
            categoryAxis.cursorTooltipEnabled = false;

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.min = 0;
            valueAxis.renderer.grid.template.disabled = true;
            valueAxis.renderer.baseGrid.disabled = true;
            valueAxis.renderer.labels.template.disabled = true;
            valueAxis.cursorTooltipEnabled = false;

            chart.cursor = new am4charts.XYCursor();
            chart.cursor.lineY.disabled = true;
            chart.cursor.behavior = "none";

            var series = chart.series.push(new am4charts.LineSeries());
            series.tooltipText = "{year}: {value}";
            series.dataFields.categoryX = "year";
            series.dataFields.valueY = "value";
            series.tensionX = 0.8;
            series.strokeWidth = 2;
            series.stroke = color;

            // render data points as bullets
            var bullet = series.bullets.push(new am4charts.CircleBullet());
            bullet.circle.opacity = 0;
            bullet.circle.fill = color;
            bullet.circle.propertyFields.opacity = "opacity";
            bullet.circle.radius = 3;

            return chart;
        }    
        var chdata = $(this).find('.widget_chartdiv').data('srcjson');
        createLine("", chdata, colors.getIndex(0));  
    });
</script>