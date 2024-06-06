<?php $renderAtom = new Mdwidget(); ?>
<section data-sectioncode="3" class="col-span-12">
    <div class="w-full h-full false" style="grid-gap:2%">
        <div class="flex">
            <div>
        <div style="width: 250px;">
            <div>
            <?php 
            if ($this->datasrc) { 
                $row = $this->datasrc;
                ?>                       
                <div class="rounded-xl p-4" data-tposition="position14" data-tpath="<?php echo $renderAtom->renderAtomPath("position14", $this->positionConfig); ?>" style="gap: 1.3rem;background: <?php echo $renderAtom->renderAtom($row, "position14", $this->positionConfig, 'linear-gradient(90deg, rgba(250,138,166,1) 0%, rgba(228,55,98,1) 100%, rgba(0,212,255,1) 100%)') ?>;">
                    <div class="mb-2 flex justify-between">
                        <span class="text-white font-bold mr-1 text-truncate" style="font-size: 23px;" data-tposition="position6" data-tpath="<?php echo $renderAtom->renderAtomPath("position6", $this->positionConfig); ?>">
                            <?php echo Number::formatMoney($renderAtom->renderAtom($row, "position6", $this->positionConfig)) ?>
                            <?php if ($renderAtom->renderAtom($row, "position8", $this->positionConfig)) { ?>
                                <span style="margin-left:-6px"><?php echo $renderAtom->renderAtom($row, "position8", $this->positionConfig, "#3975B5") ?></span>
                            <?php } ?>
                        </span>
                        <span data-tposition="position7" data-tpath="<?php echo $renderAtom->renderAtomPath("position7", $this->positionConfig); ?>">
                        <?php echo $renderAtom->renderAtom($row, "position7", $this->positionConfig, '<span style="font-size: 16px;background-color: #fff;border-radius: 5px;padding: 5px;margin-top: 5px;height: 21px;opacity: 0.3;"><i class="fas fa-link"></i></span>') ?>
                        </span>
                    </div>                    
                    <div class="">
                        <span class="" style="font-size:14px;color:#fff;font-weight: bold" data-tposition="position2" data-tpath="<?php echo $renderAtom->renderAtomPath("position2", $this->positionConfig); ?>"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position2", $this->positionConfig)) ?></span>
                        <span class="false   block cursor-pointer" style="text-align: right;">
                        <span class="line-clamp-0 text-white" style="font-size:14px" data-tposition="position3" data-tpath="<?php echo $renderAtom->renderAtomPath("position3", $this->positionConfig); ?>"><?php echo $renderAtom->renderAtom($row, "position3", $this->positionConfig, 'Default value') ?></span>
                        </span>
                    </div>
                </div>
                <?php
            } ?>                  
            </div>
        </div>
        <div class="mt-3" style="width: 250px;">
            <div>
            <?php 
            if ($this->datasrc) {
                $row = $this->datasrc; ?>                       
                <div class="rounded-xl p-4" data-tposition="position15" data-tpath="<?php echo $renderAtom->renderAtomPath("position15", $this->positionConfig); ?>" style="gap: 1.3rem;background: <?php echo $renderAtom->renderAtom($row, "position15", $this->positionConfig, 'linear-gradient(90deg, rgba(255,168,189,1) 0%, rgba(253,111,146,0.99) 100%, rgba(228,55,98,1) 100%)') ?>;">
                    <div class="mb-2 flex justify-between">
                        <span class="text-white text-lg font-bold mr-1 text-truncate" data-tposition="position20" style="font-size: 23px;" data-tpath="<?php echo $renderAtom->renderAtomPath("position20", $this->positionConfig); ?>">
                            <?php echo Number::formatMoney($renderAtom->renderAtom($row, "position20", $this->positionConfig, 'Default value')) ?>
                        </span>
                        <span data-tposition="position7" data-tpath="<?php echo $renderAtom->renderAtomPath("position7", $this->positionConfig); ?>">
                        <?php echo $renderAtom->renderAtom($row, "position7", $this->positionConfig, '<span style="font-size: 16px;background-color: #fff;border-radius: 5px;padding: 5px;margin-top: 5px;height: 21px;opacity: 0.3;"><i class="fas fa-link"></i></span>') ?>
                        </span>
                    </div>                    
                    <div class="">
                        <span class="" style="font-size:14px;color:#fff;font-weight: bold" data-tposition="position22" data-tpath="<?php echo $renderAtom->renderAtomPath("position22", $this->positionConfig); ?>"><?php echo $renderAtom->renderAtom($row, "position22", $this->positionConfig, 'Default value') ?></span>
                    </div>
                </div>
                <?php
            } ?>                  
            </div>
        </div>
        <div class="mt-3" style="width: 250px;">
            <div>
            <?php 
            if ($this->datasrc && $renderAtom->renderAtom($this->datasrc, "position23", $this->positionConfig)) {
                $row = $this->datasrc; ?>                       
                <div class="rounded-xl p-4" data-tposition="position24" data-tpath="<?php echo $renderAtom->renderAtomPath("position24", $this->positionConfig); ?>" style="gap: 1.3rem;background: <?php echo $renderAtom->renderAtom($row, "position24", $this->positionConfig, 'linear-gradient(90deg, rgba(255,168,189,1) 0%, rgba(253,111,146,0.99) 100%, rgba(228,55,98,1) 100%)') ?>;">
                    <div class="mb-2 flex justify-between">
                        <span class="text-white text-lg font-bold mr-1 text-truncate" data-tposition="position23" style="font-size: 23px;" data-tpath="<?php echo $renderAtom->renderAtomPath("position23", $this->positionConfig); ?>">
                            <?php echo Number::formatMoney($renderAtom->renderAtom($row, "position23", $this->positionConfig, 'Default value')) ?>
                        </span>
                        <span data-tposition="position25" data-tpath="<?php echo $renderAtom->renderAtomPath("position25", $this->positionConfig); ?>">
                        <?php echo $renderAtom->renderAtom($row, "position25", $this->positionConfig, '<span style="font-size: 16px;background-color: #fff;border-radius: 5px;padding: 5px;margin-top: 5px;height: 21px;opacity: 0.3;"><i class="fas fa-link"></i></span>') ?>
                        </span>
                    </div>                    
                    <div class="">
                        <span class="" style="font-size:14px;color:#fff;font-weight: bold" data-tposition="position26" data-tpath="<?php echo $renderAtom->renderAtomPath("position26", $this->positionConfig); ?>"><?php echo $renderAtom->renderAtom($row, "position26", $this->positionConfig, 'Default value') ?></span>
                    </div>
                </div>
                <?php
            } ?>                  
            </div>
        </div>
        </div>
        <div class="ml-3 w-full">
            <?php 
            if ($this->datasrc) { 
                $row = $this->datasrc;
                ?>
                <div class="rounded-xl p-4" style="gap: 1.3rem; background-color: #F9F9F9;">
                <div>
                    <span class="text-black font-bold" data-tposition="position11" data-tpath="<?php echo $renderAtom->renderAtomPath("position11", $this->positionConfig); ?>" style="font-size:18px;color:#252F4A"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position11", $this->positionConfig, 'Default value')) ?></span>
                </div>
                <div class="mb-3">
                    <span style="color:#BCB5C3;font-size: 14px" data-tposition="position12" data-tpath="<?php echo $renderAtom->renderAtomPath("position12", $this->positionConfig); ?>" style=""><?php echo $renderAtom->renderAtom($row, "position12", $this->positionConfig, 'Default date'); ?></span>
                </div>
            <?php
                $spath = explode('.', $renderAtom->renderAtomPath("position9", $this->positionConfig));
                foreach($this->datasrc[$spath[0]] as $index => $row) { ?>                                       
                    <div>
                    <div class="mb-2 flex justify-between items-center">
                        <div class="flex items-center">
                        <div class="p-2 flex items-center justify-center" data-tposition="position4" data-tpath="<?php echo $renderAtom->renderAtomPath("position4", $this->positionConfig); ?>" style="border-radius: 6px;background-color:<?php echo $renderAtom->renderAtom($row, "position4", $this->positionConfig, "#F1F1F1", true) ?>">
                            <i data-tposition="position1" data-tpath="<?php echo $renderAtom->renderAtomPath("position1", $this->positionConfig); ?>" style="color:<?php echo $renderAtom->renderAtom($row, "position5", $this->positionConfig, "#3975B5") ?>;font-size:12px;" class="<?php echo $renderAtom->renderAtom($row, "position1", $this->positionConfig, "far fa-smile", true) ?> hover:text-sso "></i>
                        </div>
                        <div class="ml-2">
                            <span class="">
                            <span data-tposition="position9" data-tpath="<?php echo $renderAtom->renderAtomPath("position9", $this->positionConfig); ?>" class="line-clamp-0" style="font-size:12px"><?php echo $renderAtom->renderAtom($row, "position9", $this->positionConfig, 'Default value', true) ?></span>
                            </span>
                        </div>
                        </div>                        
                        <span class="text-black font-bold" data-tposition="position10" data-tpath="<?php echo $renderAtom->renderAtomPath("position10", $this->positionConfig); ?>" style=""><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position10", $this->positionConfig, '320000', true)) ?><span data-tposition="position21" data-tpath="<?php echo $renderAtom->renderAtomPath("position21", $this->positionConfig); ?>" style=""><?php echo $renderAtom->renderAtom($row, "position21", $this->positionConfig, '₮', true) ?></span></span>
                    </div>                    
                    </div>                
                    <?php
                    if($index % 2 != 0 || $index == 0){
                        echo "<div style='border-top: 1px dashed #bcb5c396;margin-top: 17px;margin-bottom: 15px;'></div>";
                    }
                    ?>
                <?php }
                echo '<div class="mt-2 cursor-pointer" style="text-align: right;">
                    <span style="color:#A0A0A0;font-size: 11px" data-tposition="position13" data-tpath="'.$renderAtom->renderAtomPath("position13", $this->positionConfig).'" onclick="dataViewAll(this)" data-row={} data-dataviewid="'.issetParam($jsonAttr['viewAll']).'">'.Lang::line($renderAtom->renderAtom($row, "position13", $this->positionConfig, 'Default value')).'</span>
                </div>';
            } ?>  
        </div>
        </div>
    </div>
</section>