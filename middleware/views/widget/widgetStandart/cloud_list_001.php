<?php $renderAtom = new Mdwidget(); ?>
<section data-sectioncode="6" class="col-span-12">
    <div class="w-full h-full grid grid-cols-12 gap-5" style="">
    <div class="w-full col-span-12 lg:col-span-12">
        <div style="font-size:18px;color:#585858;" class="font-bold"><?php echo Lang::line(issetParam($this->jsonAttr['title'])) ?></div>
        <div style="color:#BCB5C3;font-size: 14px;margin-bottom:<?php echo issetParam($this->jsonAttr['isListHideTitle']) == 1 ? '15' : '0'; ?>px"><?php echo Lang::line(issetParam($this->jsonAttr['subTitle'])) ?></div>
        <div class="overflow-y-auto">
            <table class="w-full whitespace-nowrap">
                <?php if (issetParam($this->jsonAttr['isListHideTitle']) != 1) { ?>
                    <thead>
                        <tr tabindex="0" class="bold h-12" style="color:#BCB5C3;    font-size: 12px;">
                            <th class="font-normal text-left" style="color:#BCB5C3;font-weight:600">Бараа</th>
                            <th class="font-normal text-left px-2" style="color:#BCB5C3;font-weight:600;text-align: right !important">Нэгж үнэ</th>
                            <th class="font-normal text-left px-2" style="color:#BCB5C3;font-weight:600;text-align: right !important">Тоо хэмжээ</th>
                        </tr>
                    </thead>
                <?php } ?>
                <tbody class="w-full">
                <?php 
                if ($this->datasrc) {
                    foreach($this->datasrc as $index => $row) { ?>                    
                        <tr tabindex="0" class="<?php echo issetParam($this->jsonAttr['isListHideTitle']) == 1 ? '' : 'border-b border-t'; ?> focus:outline-none text-sm leading-none text-gray-800 bg-white hover:bg-gray-100 border-gray-100" style="height: 40px;background-color: transparent !important;">
                            <td class="py-1">
                                <div class="flex items-center">
                                    <div style="background-color:#EFEFEF;height: 38px;width: 38px">
                                        <img data-tposition="position8" data-tpath="<?php echo $renderAtom->renderAtomPath("position8", $this->positionConfig); ?>" src="<?php echo $renderAtom->renderAtom($row, "position8", $this->positionConfig, "assets/core/global/img/user.png") ?>"/>
                                    </div>
                                    <div class="pl-2">
                                        <p style="font-size: 12px;" data-tposition="position2" data-tpath="<?php echo $renderAtom->renderAtomPath("position2", $this->positionConfig); ?>"><?php echo $renderAtom->renderAtom($row, "position2", $this->positionConfig, 'Default value') ?></p>
                                        <p class="pt-2" style="font-size: 10px;color:#BCB5C3" data-tposition="position7" data-tpath="<?php echo $renderAtom->renderAtomPath("position7", $this->positionConfig); ?>"><?php echo $renderAtom->renderAtom($row, "position7", $this->positionConfig, 'Default value') ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-1" style="width:130px">
                                <p style="font-size: 12px;text-align: right" data-tposition="position3" data-tpath="<?php echo $renderAtom->renderAtomPath("position3", $this->positionConfig); ?>"><?php echo $renderAtom->renderAtom($row, "position3", $this->positionConfig, 'Default value') ?></p>
                            </td>
                            <td class="py-1" style="width:130px">
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