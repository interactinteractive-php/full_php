<?php $renderAtom = new Mdwidget(); ?>
<section data-sectioncode="3" class="col-span-12 px-3">
    <div style="font-size:18px;color:#585858;" class="font-bold"><?php echo Lang::line(issetParam($this->jsonAttr['title'])) ?></div>
    <div style="color:#BCB5C3;font-size: 14px;margin-bottom:15px"><?php echo Lang::line(issetParam($this->jsonAttr['subTitle'])) ?></div>
    <div class="w-full h-full false" style="grid-gap:2%">
        <div class="">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 w-full gap-4 " style="gap:1.2rem">
            <?php 
            if ($this->datasrc) {
                foreach($this->datasrc as $index => $row) { ?>
                <div class="rounded-xl p-4 flex flex-column items-center justify-center" style="gap: 0.5rem;background-color: #F9F9F9;">
                    <div class="rounded-2xl" style="height: 28px;width: 28px;text-align: center;background-color:<?php echo $renderAtom->renderAtom($row, "position4", $this->positionConfig, "#C0DCFF") ?>">
                        <i data-tposition="position1" data-tpath="<?php echo $renderAtom->renderAtomPath("position1", $this->positionConfig); ?>" style="padding-top: 8px;font-size: 12px;color:<?php echo $renderAtom->renderAtom($row, "position5", $this->positionConfig, "#3975B5") ?>" class="<?php echo $renderAtom->renderAtom($row, "position1", $this->positionConfig, "far fa-smile") ?> hover:text-sso "></i>
                    </div>                    
                    <div class="flex justify-between">
                        <span class="text-black text-lg font-bold" data-tposition="position6" data-tpath="<?php echo $renderAtom->renderAtomPath("position6", $this->positionConfig); ?>" style=""><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position6", $this->positionConfig, '56000000')) ?></span>
                    </div>                    
                    <div style="height: 32px;">
                        <div class="ml-2 flex items-center" style="line-height: 1;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;text-align: center;">
                            <span data-tposition="position2" data-tpath="<?php echo $renderAtom->renderAtomPath("position2", $this->positionConfig); ?>" style="font-size:12px;color:#888888"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position2", $this->positionConfig, 'Default value')) ?></span>
                            <span class="false  text-sm text-gray-600 block" style="color:#67748E">
                            <span data-tposition="position3" data-tpath="<?php echo $renderAtom->renderAtomPath("position3", $this->positionConfig); ?>" class="line-clamp-0"><?php echo $renderAtom->renderAtom($row, "position3", $this->positionConfig) ?></span>
                            </span>
                        </div>
                    </div>
                    <div>
                        <span data-tposition="position7" data-tpath="<?php echo $renderAtom->renderAtomPath("position7", $this->positionConfig); ?>">
                            <?php echo html_entity_decode($renderAtom->renderAtom($row, "position7", $this->positionConfig, '<span style="color: #F35D82;font-size: 9px;background-color: #f35d8229;border-radius: 5px;padding: 3px;margin-top: 5px;height: 17px;">8,02</span>')) ?>
                        </span>                        
                    </div>
                </div>
                <?php }
            } ?>                  
            </div>
        </div>
    </div>
    <?php if(issetParam($this->jsonAttr['threeCard'])) { ?>
    <div class="mt-2 cursor-pointer" style="text-align: right;">
        <?php echo '<span style="color:#A0A0A0;font-size: 11px" data-tposition="position13" data-tpath="'.$renderAtom->renderAtomPath("position13", $this->positionConfig).'" onclick="dataViewAll(this)" data-row={} data-dataviewid="'.issetParam($jsonAttr['viewAll']).'">'.Lang::line($renderAtom->renderAtom($row, "position13", $this->positionConfig, 'Бүгдийг харах'))    .'</span>'; ?>
    </div>    
    <?php } ?>
</section>