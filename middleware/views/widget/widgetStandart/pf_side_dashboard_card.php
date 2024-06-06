<?php $renderAtom = new Mdwidget(); ?>
<div>
    <?php 
    if ($this->datasrc) {
        foreach($this->datasrc as $key => $row) { ?>               
            <div class="w-full h-full p-4 shadow-citizen overflow-hidden rounded-xl<?php echo $key ? ' mt-2' : ''; ?>" style="background-color:<?php echo $renderAtom->renderAtom($row, "position3", $this->positionConfig, '#fff'); ?>">
                <div class=" w-full relative">
                    <div class="flex justify-between">
                        <div class="text-sm" style="font-weight: bold;font-size: 13px;">
                            <?php echo $renderAtom->renderAtom($row, "position1", $this->positionConfig) ?>
                        </div>
                        <div class="text-sm" style="font-weight: bold;font-size: 15px;">
                            <?php echo $renderAtom->renderAtom($row, "position2", $this->positionConfig) ?>
                        </div>
                    </div>
                </div>
            </div>                
    <?php }
    } ?>   
</div>