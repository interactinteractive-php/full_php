<?php $renderAtom = new Mdwidget(); ?>
<section data-sectioncode="3" class="col-span-12 px-3 py-3 rounded-xl" style="background-color: #F9F9F9;">
    <div style="font-size:18px;color:#585858;" class="font-bold"><?php echo Lang::line(issetParam($this->jsonAttr['title'])) ?></div>
    <div style="color:#BCB5C3;font-size: 14px;margin-bottom:15px"><?php echo Lang::line(issetParam($this->jsonAttr['subTitle'])) ?></div>
    <div class="w-full h-full">
        <div class="list-feed list-feed-solid">
            <?php 
            if ($this->datasrc) {
                foreach($this->datasrc as $index => $row) { 
                    if (!$index) { ?>
                    <div class="d-flex justify-content-between mb-3">
                        <div style="font-size:12px;font-weight: bold; color:#BCB5C3" data-tposition="position4" data-tpath="<?php echo $renderAtom->renderAtomPath("position4", $this->positionConfig); ?>"><?php echo $renderAtom->renderAtom($row, "position4", $this->positionConfig, 'Default value') ?></div>
                        <div style="font-size:12px;color:#009EF7;font-weight: bold;background-color: #009ef729;padding: 2px;border-radius: 5px;" class="" data-tposition="position5" data-tpath="<?php echo $renderAtom->renderAtomPath("position5", $this->positionConfig); ?>"><?php echo $renderAtom->renderAtom($row, "position5", $this->positionConfig, 'Default value') ?></div>
                    </div>            
                    <?php }
                    ?>            
                    <div class="list-feed-item d-flex justify-content-between">
                        <div style="z-index: 80;position: absolute;margin-left: -29px;height: 18px;width: 18px;border-radius: 50px;background: <?php echo $renderAtom->renderAtom($row, "position6", $this->positionConfig, "#C0DCFF").'99' ?>;">
                        <div style="position: absolute;margin-left: 4px;margin-top:4px;height: 10px;width: 10px;border-radius: 50px;background: <?php echo $renderAtom->renderAtom($row, "position6", $this->positionConfig, "#C0DCFF") ?>;"></div>
                        </div>
                        <div style="font-size:12px;" data-tposition="position1" data-tpath="<?php echo $renderAtom->renderAtomPath("position1", $this->positionConfig); ?>"><?php echo $renderAtom->renderAtom($row, "position1", $this->positionConfig) ?></div>
                        <div style="font-size:12px;" data-tposition="position3" data-tpath="<?php echo $renderAtom->renderAtomPath("position3", $this->positionConfig); ?>"><?php echo $renderAtom->renderAtom($row, "position3", $this->positionConfig, 'Default value') ?></div>
                    </div>
                <?php }
            } ?>              
        </div>
    </div>
</section>
<style>
    div[data-widgetcode="cloudtimeline_list"] .list-feed-solid .list-feed-item:before {
        content: '';
        border-width: 0.5rem;
        width: 0;
        height: 0;        
    }
    div[data-widgetcode="cloudtimeline_list"] .list-feed-item:before {
        border: none;
    }
    div[data-widgetcode="cloudtimeline_list"] .list-feed-item {
        padding-bottom: 2rem;
    }
    div[data-widgetcode="cloudtimeline_list"] .list-feed-item:after {
        content: '';
        position: absolute;
        top: 0.31252rem;
        left: 0.45rem;
        bottom: -0.43752rem;
        width: 0;
        border-left: 1px solid #e9e9e9;
        border-right: 1px solid #e9e9e9;
        z-index: 2;        
    }
    div[data-widgetcode="cloudtimeline_list"] .list-feed-item:last-child:after {
        border-left: none;
        border-right: none; 
    }
</style>