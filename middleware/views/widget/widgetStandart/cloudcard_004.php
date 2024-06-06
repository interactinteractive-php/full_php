<?php $renderAtom = new Mdwidget(); ?>
<section data-sectioncode="3" class="col-span-12 px-3">
    <div style="font-size:18px;color:#585858;line-height: 22px;" class="font-bold"><?php echo Lang::line(issetParam($this->jsonAttr['title'])) ?></div>
    <div style="color:#BCB5C3;font-size: 14px;margin-bottom:15px"><?php echo Lang::line(issetParam($this->jsonAttr['subTitle'])) ?></div>
    <div class="w-full h-full false" style="grid-gap:2%">
        <div class="">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-<?php echo issetParam($this->jsonAttr['threeCard']) ? 3 : 6; ?> w-full gap-4 " style="gap:1.3rem">
            <?php 
            if ($this->datasrc) {
                foreach($this->datasrc as $index => $row) { ?>
                <div class="rounded-xl p-6 card-004" style="gap: 1.3rem;background-color: #F9F9F9;">
                    <div class="mb-2 flex justify-between">
                        <span class="text-truncate font-bold" data-tposition="position6" data-tpath="<?php echo $renderAtom->renderAtomPath("position6", $this->positionConfig); ?>" style="font-size: 23px;color: #252F4A;">
                            <?php echo Number::formatMoney($renderAtom->renderAtom($row, "position6", $this->positionConfig, '56000000')) ?>
                            <?php if ($renderAtom->renderAtom($row, "position8", $this->positionConfig)) { ?>
                                <span data-tposition="position8" data-tpath="<?php echo $renderAtom->renderAtomPath("position8", $this->positionConfig); ?>" style="margin-left:-6px"><?php echo $renderAtom->renderAtom($row, "position8", $this->positionConfig, "#3975B5") ?></span>
                            <?php } ?>
                        </span>
                            
                        <span data-tposition="position7" data-tpath="<?php echo $renderAtom->renderAtomPath("position7", $this->positionConfig); ?>" style="white-space: nowrap;">
                            <?php echo html_entity_decode($renderAtom->renderAtom($row, "position7", $this->positionConfig, '<span style="color: #F35D82;font-size: 9px;background-color: #f35d8229;border-radius: 5px;padding: 3px;margin-top: 5px;height: 17px;">8,02</span>')) ?>
                        </span>
                    </div>                    
                    <div class="flex items-center justify-between w-full sm:w-full" style="    margin-top: 15px;">
                        <div class="flex items-center">
                        <div class="p-3 rounded-3xl flex items-center justify-center" style="height: 40px;width: 40px;aspect-ratio: auto 1 / 1; color: rgb(118, 51, 107);background-color:<?php echo $renderAtom->renderAtom($row, "position5", $this->positionConfig, "#C0DCFF").'33' ?>">
                            <i data-tposition="position1" data-tpath="<?php echo $renderAtom->renderAtomPath("position1", $this->positionConfig); ?>" style="color:<?php echo $renderAtom->renderAtom($row, "position5", $this->positionConfig, "#3975B5") ?>" class="<?php echo $renderAtom->renderAtom($row, "position1", $this->positionConfig, "far fa-smile") ?> hover:text-sso "></i>
                        </div>
                        <div class="ml-2  flex items-center" style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;">
                            <p data-tposition="position2" data-tpath="<?php echo $renderAtom->renderAtomPath("position2", $this->positionConfig); ?>" style="font-size:14px;color:#888888;line-height: 18px;"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position2", $this->positionConfig, 'Default value')) ?></p>
                            <span class="false  text-sm text-gray-600 block" style="color:#67748E">
                            <span data-tposition="position3" data-tpath="<?php echo $renderAtom->renderAtomPath("position3", $this->positionConfig); ?>" class="line-clamp-0"><?php echo $renderAtom->renderAtom($row, "position3", $this->positionConfig) ?></span>
                            </span>
                        </div>
                        </div>
                    </div>
                </div>
                <?php }
            } ?>                  
            </div>
        </div>
    </div>
    <?php if(issetParam($this->jsonAttr['threeCard'])) { ?>
    <div class="mt-2 cursor-pointer" style="text-align: right;">
        <?php echo '<span style="color:#A0A0A0;font-size: 11px" data-tposition="position13" data-tpath="'.$renderAtom->renderAtomPath("position13", $this->positionConfig).'" onclick="dataViewAll(this)" data-row={} data-dataviewid="'.issetParam($jsonAttr['viewAll']).'">'.Lang::line($renderAtom->renderAtom($row, "position13", $this->positionConfig, 'Бүгдийг харах')).'</span>'; ?>
    </div>    
    <?php } ?>
</section>

<style>
    .card-004{
        border:1px solid transparent;
        cursor: pointer;
    }
    .card-004:hover{
        border:1px solid #E1E1E1;
    }
</style>