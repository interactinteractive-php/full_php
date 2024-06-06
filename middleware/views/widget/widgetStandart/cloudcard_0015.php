<?php 
$uid = getUID();
$renderAtom = new Mdwidget(); ?>
<section data-sectioncode="3" class="col-span-12 px-3 cloudcard_<?php echo $uid; ?>">
    <div style="font-size:18px;color:#585858;line-height: 22px;" class="font-bold"><?php echo Lang::line(issetParam($this->jsonAttr['title'])) ?></div>
    <div style="color:#BCB5C3;font-size: 14px;margin-bottom:15px"><?php echo Lang::line(issetParam($this->jsonAttr['subTitle'])) ?></div>
    <div class="w-full h-full false" style="grid-gap:2%">
        <div>
            <?php 
                if ($this->datasrc) { ?> 
                <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 w-full gap-4 " style="gap:0.3rem">
                <?php 
                    foreach($this->datasrc as $index => $row) { 
                        $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                    ?>
                    <div class="rounded-xl p-2 card-004 cloud-call-indicator" style="gap: 1.3rem;background-color: #FFF;" data-rowdata="<?php echo $rowJson ?>">
                        <div class="flex items-center justify-between w-full sm:w-full">
                            <div class="p-3 rounded-3xl flex items-center justify-center" style="height: 40px;width: 40px; border-radius: 6px; aspect-ratio: auto 1 / 1; color: rgb(118, 51, 107);background-color:<?php echo $renderAtom->renderAtom($row, "position3", $this->positionConfig, "#EB735B") ?>">
                                <i data-tposition="position0" data-tpath="<?php echo $renderAtom->renderAtomPath("position0", $this->positionConfig); ?>" style="font-size: 24px; color:#FFF" class="<?php echo $renderAtom->renderAtom($row, "position0", $this->positionConfig, "icon-gear") ?> hover:text-sso "></i>
                            </div>
                            <div class="ml-2 grid items-center line-camp-3">
                                <p class="text-one-line" data-tposition="position1" data-tpath="<?php echo $renderAtom->renderAtomPath("position1", $this->positionConfig); ?>" style="font-size:14px;color:#3F4254;line-height: 18px;"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position1", $this->positionConfig, 'Default value')) ?></p>
                                <p class="text-one-line" data-tposition="position2" data-tpath="<?php echo $renderAtom->renderAtomPath("position2", $this->positionConfig); ?>" style="font-size:12px;color:#A1A5B7;line-height: 18px;"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position2", $this->positionConfig, 'Default value')) ?></p>
                            </div>
                            <div class="p-3 rounded-3xl flex items-center justify-center items-center pull-right ml-auto w-auto" style="height: 40px;width: 40px; border-radius: 6px; aspect-ratio: auto 1 / 1; color: rgb(118, 51, 107);background-color:#e5e5e5">
                                <span class="false  text-sm text-gray-600 block" style="color:#67748E">
                                    <i class="icon-arrow-right8"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    </div>
                    <?php
                } else { ?>
                    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 w-full gap-4 " style="gap:0.3rem">
                        <img src="middleware/assets/img/icon/no-data.png" alt="no-data" class="w-auto mx-auto"/>              
                    </div>
                <?php } ?>                  
            </div>
        </div>
    </div>
    <?php if(issetParam($this->jsonAttr['threeCard'])) { ?>
        <div class="mt-2 cursor-pointer" style="text-align: right;">
            <?php echo '<span style="color:#A0A0A0;font-size: 11px" data-tposition="position4" data-tpath="'.$renderAtom->renderAtomPath("position4", $this->positionConfig).'" onclick="dataViewAll(this)" data-row={} data-dataviewid="'.issetParam($jsonAttr['viewAll']).'">'.Lang::line($renderAtom->renderAtom($row, "position4", $this->positionConfig, 'Бүгдийг харах')).'</span>'; ?>
        </div>    
    <?php } else { ?>
        <div class="mt-2 cursor-pointer px-4" style="text-align: right;">
            <?php echo '<span style="color:#A0A0A0;font-size: 11px" data-tposition="position4" >'.Lang::line('Бүгдийг харах').'</span>'; ?>
        </div>    
    <?php } ?>
</section>

<style type="text/css">
    .line-camp-3 {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        
    }
    .card-004{
        border:1px solid transparent;
        cursor: pointer;
    }
    .card-004:hover{
        border:1px solid #E1E1E1;
    }
</style>

<script type="text/javascript">
    var idField_190189444 = 'id';
    $(function() {
        if (typeof isKpiIndicatorScript === 'undefined') {
            $.cachedScript('<?php echo autoVersion('middleware/assets/js/addon/indicator.js'); ?>', {async: false});
        }
    });
    
    $('body').on('click', '.cloudcard_<?php echo $uid; ?> .cloud-call-indicator', function () {
        var _this = $(this),
            rowJson = JSON.parse(_this.attr('data-rowdata'));
        mvNormalRelationRender(this, '2008', '190189444', {methodIndicatorId: '190189655', structureIndicatorId: '190189444', mode: 'update', rows: [rowJson]});
    });
</script>