<?php $renderAtom = new Mdwidget(); ?>

<div class="w-full h-full false" style="grid-gap:2%">
    <div class=" ">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 w-full gap-4 ">
        <?php 
        if ($this->datasrc) {
            foreach($this->datasrc as $row) { ?>
            <div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-citizen p-4" style="border-bottom-right-radius: 0;border-bottom-left-radius: 0;">
                    <div class="flex items-center justify-between w-full sm:w-full">
                        <div class="flex items-center">
                            <div class="p-3 rounded-xl flex items-center justify-center" style="height: 50px;width: 50px;aspect-ratio: auto 1 / 1; color: rgb(118, 51, 107);background-color:rgba(191, 219, 254, 1)">
                                <i style="color:<?php echo issetParam($row["fontcolor"]) ?>" class="<?php echo $row["icon"] ?> text-xl false hover:text-sso "></i>
                            </div>
                            <div class="ml-3">
                                <span class="false text-sm text-gray-400 block">
                                    <span class="line-clamp-0"><?php echo $renderAtom->renderAtom($row, "position40", $this->positionConfig) ?></span>
                                </span>
                                <span class="text-xl text-gray-600 font-bold block"><?php echo Str::formatMoney($renderAtom->renderAtom($row, "position4", $this->positionConfig)) ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-2">
                        <span class="false  text-sm text-gray-400 block">
                            Касс
                        </span>                        
                        <p class="text-sm text-gray-600 font-bold">1,000,000,000₮</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="false  text-sm text-gray-400 block">
                            Банк
                        </span>                        
                        <p class="text-sm text-gray-600 font-bold">1,000,000,000₮</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-citizen p-4 mt-2" style="border-top-right-radius: 0;border-top-left-radius: 0;">
                    <div class="flex items-center justify-between">
                        <span class="false  text-sm text-gray-400 block">
                            Журналд холбоогүй
                        </span>                        
                        <p class="text-sm font-semibold text-gray-600">50</p>
                    </div>
                </div>
            </div>
        <?php }
        } ?>
        </div>
    </div>
</div>