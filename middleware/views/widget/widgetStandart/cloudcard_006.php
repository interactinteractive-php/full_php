<?php $renderAtom = new Mdwidget(); ?>
<section data-sectioncode="3" class="col-span-12 px-3 py-3 rounded-xl" style="background-color: #F9F9F9;">
    <div class="w-full h-full">
        <div class="list-feed list-feed-solid">
            <?php 
            if ($this->datasrc) { 
                $row = $this->datasrc[0];
                ?>       
                <div class="flex items-center">
                    <div style="background-color:#EFEFEF" class="px-3 py-3 rounded-xl">
                        <i data-tposition="position1" data-tpath="<?php echo $renderAtom->renderAtomPath("position1", $this->positionConfig); ?>" style="color:<?php echo $renderAtom->renderAtom($row, "position5", $this->positionConfig, "#3975B5") ?>;font-size:20px" class="<?php echo $renderAtom->renderAtom($row, "position1", $this->positionConfig, "far fa-smile") ?> hover:text-sso "></i>
                    </div>
                    <div class="ml-3">
                        <div style="font-size:15px;color:#585858;" class="font-bold"><?php echo Lang::line(issetParam($this->jsonAttr['title'])) ?></div>
                        <div style="color:#BCB5C3;font-size: 11px;margin-bottom:15px"><?php echo Lang::line(issetParam($this->jsonAttr['subTitle'])) ?></div>            
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <div style="font-size:12px;font-weight: bold" data-tposition="position4" data-tpath="<?php echo $renderAtom->renderAtomPath("position4", $this->positionConfig); ?>"><?php echo $renderAtom->renderAtom($row, "position4", $this->positionConfig, 'Default value') ?></div>
                    <div style="color:#BCB5C3;font-size: 11px;" data-tposition="position3" data-tpath="<?php echo $renderAtom->renderAtomPath("position3", $this->positionConfig); ?>"><?php echo $renderAtom->renderAtom($row, "position3", $this->positionConfig, 'Default value') ?></div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <div style="font-size:12px;font-weight: bold" data-tposition="position9" data-tpath="<?php echo $renderAtom->renderAtomPath("position9", $this->positionConfig); ?>"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position9", $this->positionConfig, '15000000')) ?></div>
                </div>
                <div class="flex items-center mt-4">
                    <div style="background-color:#EFEFEF;height: 30px;width: 30px">
                        <img class="rounded-xl" data-tposition="position6" data-tpath="<?php echo $renderAtom->renderAtomPath("position6", $this->positionConfig); ?>" src="<?php echo $renderAtom->renderAtom($row, "position6", $this->positionConfig, "assets/core/global/img/user.png") ?>"/>
                    </div>
                    <div class="ml-3">
                        <div style="font-size:13px;color:#585858;" class="font-bold" data-tposition="position7" data-tpath="<?php echo $renderAtom->renderAtomPath("position7", $this->positionConfig); ?>"><?php echo $renderAtom->renderAtom($row, "position7", $this->positionConfig, "Default value") ?></div>
                        <div style="color:#BCB5C3;font-size: 11px;" data-tposition="position8" data-tpath="<?php echo $renderAtom->renderAtomPath("position8", $this->positionConfig); ?>"><?php echo $renderAtom->renderAtom($row, "position8", $this->positionConfig, "Default value") ?></div>            
                    </div>
                </div> 
            <?php
            } ?>              
        </div>
    </div>
</section>