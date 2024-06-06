<?php $renderAtom = new Mdwidget(); ?>

<div class="bg-white dark:bg-gray-800 w-full h-full rounded-2xl p-4">
<?php 
    if ($this->datasrc) { 
        $groupedData = Arr::groupByArray($this->datasrc, $renderAtom->renderAtom([], "position1", $this->positionConfig));    

        foreach($groupedData as $key => $row) {              
            ?>            
                <p tabindex="0" class="focus:outline-none text-lg leading-none text-gray-600 dark:text-gray-100" style="margin-top: 5px;color:#333"><?php echo $key; ?></p>
                <div class="mx-2 ml-2">
                    <?php foreach($row["rows"] as $row2) { ?>
                    <div class="flex justify-between">
                        <p tabindex="0" class="focus:outline-none text-sm font-medium leading-5 text-gray-600 dark:text-gray-100 w-9/12"><?php echo $renderAtom->renderAtom($row2, "position2", $this->positionConfig) ?></p>
                        <p tabindex="0" class="focus:outline-none text-sm leading-4 text-gray-600 dark:text-gray-400"><i style="font-size: 12px;" class="<?php echo $renderAtom->renderAtom($row2, "position4", $this->positionConfig) ?>"></i> <?php echo $renderAtom->renderAtom($row2, "position3", $this->positionConfig) ?></p>
                    </div>
                    <?php } ?>
                </div>
                <?php
        }
    } ?>
</div>
    