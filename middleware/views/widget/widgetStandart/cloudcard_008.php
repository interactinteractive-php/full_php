<?php $renderAtom = new Mdwidget(); ?>
<section data-sectioncode="3" class="col-span-12 px-3">
    <div style="font-size:18px;color:#585858;" class="font-bold"><?php echo Lang::line(issetParam($this->jsonAttr['title'])) ?></div>
    <div style="color:#BCB5C3;font-size: 14px;margin-bottom:15px"><?php echo Lang::line(issetParam($this->jsonAttr['subTitle'])) ?></div>
    <div class="w-full h-full false" style="grid-gap:2%">
        <div class="">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 w-full gap-4 " style="gap:1.2rem">
            <?php 
            if ($this->datasrc) {
                foreach($this->datasrc as $index => $row) { ?>
                <div class="rounded-xl flex flex-column items-center justify-center py-2 px-3" style="gap: 0.5rem;background-color: #fff;border: 1px dashed #ccc;">
                    <i data-tposition="position1" data-tpath="<?php echo $renderAtom->renderAtomPath("position1", $this->positionConfig); ?>" style="font-size: 21px;color:<?php echo $renderAtom->renderAtom($row, "position5", $this->positionConfig, "#3975B5") ?>" class="<?php echo $renderAtom->renderAtom($row, "position1", $this->positionConfig, "far fa-smile") ?> hover:text-sso "></i>
                    <div style="height: 32px;">
                        <div class="ml-2 flex items-center" style="line-height: 1;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;text-align: center;">
                            <span data-tposition="position2" data-tpath="<?php echo $renderAtom->renderAtomPath("position2", $this->positionConfig); ?>" style="font-size:12px;color:#888888"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position2", $this->positionConfig, 'Default value')) ?></span>
                        </div>
                    </div>
                </div>
                <?php }
            } ?>                  
            </div>
        </div>
    </div>
</section>