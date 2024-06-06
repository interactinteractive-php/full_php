<?php $renderAtom = new Mdwidget(); ?>
<hr class="pr-3" />
<div class="w-full flex justify-start flex-col pl-1 pr-3 py-3">
    <div style="color:#585858;font-size:20px"><?php echo Lang::lineDefault(issetParam($this->jsonAttr['title']), 'Ханш'); ?></div>
    <div class="flex justify-between mt-2">
        <div style="color:#9FA2B4;font-szie:14px;"><?php echo Lang::line($renderAtom->renderAtom([], "position4", $this->positionConfig, 'Валют')); ?></div>
        <div style="color:#9FA2B4;font-szie:14px;"><?php echo Lang::line($renderAtom->renderAtom([], "position5", $this->positionConfig, 'Одоогийн ханш')); ?></div>
    </div>
    <?php
        if ($this->datasrc) { 
            foreach ($this->datasrc as $row) {
            ?>
            <div class="flex justify-between">
            <div>                
                <div class="flex mt-2">
                    <span class="" style="background: #E1EBFD;width: 40px;height: 40px;border-radius: 40px;">
                    </span>
                    <img src="<?php echo $renderAtom->renderAtom($row, "position1", $this->positionConfig) ?>" 
                        style="width: 20px;height: 20px;position: absolute;margin-top: 10px;margin-left: 10px;">
                    <span class="self-center ml-2" style="font-szie:14px;color:#585858;font-weight: 700;"><?php echo $renderAtom->renderAtom($row, "position2", $this->positionConfig) ?></span>
                </div>
            </div>
            <div>
                <div class="mt-3" style="color:#585858;font-szie:14px;"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position3", $this->positionConfig)) ?></div>
            </div>
            </div>
        <?php }
        }
    ?>        
    <div style="color:#9FA2B4;font-size:14px;" class="mt-3 hidden">Дэлгэрэнгүй</div>
</div>