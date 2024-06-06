<?php $renderAtom = new Mdwidget(); ?>
<div class="w-full h-full bg-white p-4 shadow-citizen overflow-hidden rounded-xl">
    <div style="font-size:20px;color:#585858;font-weight: 500;" class="">Шинэ мэдээ</div>        
    <div class="mt-3">
    <span class="badge badge-flat border-primary text-primary mr-2 cloud-badge active" style="">Борлуулалт</span>
    <span class="badge badge-flat border-primary text-primary mr-2 cloud-badge" style="">Маркетинг</span>
    <span class="badge badge-flat border-primary text-primary mr-2 cloud-badge" style="">Хүний нөөц</span>
    <span class="badge badge-flat border-primary text-primary mr-2 cloud-badge" style="">Санхүү</span>
    </div>
    <div class="grid grid-cols-3 gap-4 pt-3 pb-0">
        <?php 
        if ($this->datasrc) {
            foreach($this->datasrc as $row) { ?>               
                <div>
                    <div class=" w-full relative bg-white dark:bg-gray-800">
                        <img src="<?php echo $renderAtom->renderAtom($row, "position1", $this->positionConfig) ?>" class="w-full" alt="protest" style="border-top-left-radius: 10px;border-top-right-radius: 10px;height: 160px;">
                        <div style="background-color: #F3F4F6;padding: 12px;border-bottom-left-radius: 10px;border-bottom-right-radius: 10px;">
                            <div class="flex justify-between">
                                <div class="text-gray-400 font-normal mt-2.5 text-sm px-2" style="font-weight: 500;font-size: 12px;">
                                    <?php echo $renderAtom->renderAtom($row, "position2", $this->positionConfig) ?>
                                </div>
                                <div class="text-gray-400 font-normal mt-2.5 text-sm px-2" style="color:#699BF7;font-weight: 500;font-size: 12px;">
                                    <?php echo $renderAtom->renderAtom($row, "position3", $this->positionConfig) ?>
                                </div>
                            </div>
                            <div class="py-2 px-2">
                                <p class="text-lg font-bold text-gray-800 overflow-ellipsis overflow-hidden" style="height: 50px;line-height: 1.3;color: #585858;font-weight: 500;"><?php echo $renderAtom->renderAtom($row, "position4", $this->positionConfig) ?></p>
                                <p class="text-sm leading-5 text-gray-400 pt-2.5 overflow-ellipsis overflow-hidden" style="color:#67748E"><?php echo Str::moreMB($renderAtom->renderAtom($row, "position5", $this->positionConfig), 90) ?></p>
                            </div>
                        </div>
                    </div>
                </div>                
        <?php }
        } ?>   
    </div>
</div>