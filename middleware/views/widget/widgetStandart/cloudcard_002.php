<?php $renderAtom = new Mdwidget(); ?>
<section data-sectioncode="3" class="mb-5 col-span-12 px-3">
    <div style="font-size:20px;color:#585858;margin-bottom:20px"><?php echo Lang::line(issetParam($this->jsonAttr['title'])) ?></div>          
    <div class="w-full h-full false" style="grid-gap:2%">
    <div class=" ">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 w-full gap-4 " style="gap:1.3rem">
        <?php 
        if ($this->datasrc) {
            foreach($this->datasrc as $index => $row) {
            if ($index <= 3) { ?>                       
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-citizen p-4" style="gap: 1.3rem;">
                <div class="flex items-center justify-between w-full sm:w-full">
                    <div class="flex items-center">
                    <div class="p-3 rounded-xl flex items-center justify-center" style="height: 50px;width: 50px;aspect-ratio: auto 1 / 1; color: rgb(118, 51, 107);background-color:<?php echo $renderAtom->renderAtom($row, "position4", $this->positionConfig, "#C0DCFF") ?>">
                        <i style="color:<?php echo $renderAtom->renderAtom($row, "position5", $this->positionConfig, "#3975B5") ?>" class="far <?php echo $renderAtom->renderAtom($row, "position1", $this->positionConfig, "fa-smile") ?> text-xl false hover:text-sso "></i>
                    </div>
                    <div class="ml-2">
                        <span class="text-sm lg:text-base text-base text-gray-700 font-bold block"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position2", $this->positionConfig)) ?></span>
                        <span class="false  text-sm text-gray-600 block" style="color:#67748E">
                        <span class="line-clamp-0"><?php echo $renderAtom->renderAtom($row, "position3", $this->positionConfig) ?></span>
                        </span>
                    </div>
                    </div>
                </div>
            </div>
            <?php }}
        } ?>                  
        </div>
    </div>
    </div>
</section>