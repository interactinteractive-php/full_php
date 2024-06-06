<div class="col-md-4 col-center-block">
    <?php
    foreach ($this->cashierList as $row) {
    ?>
    <a href="mdpos/chooseCashier/<?php echo $row['storeid']; ?>/<?php echo $row['cashregisterid']; ?>/<?php echo $row['cashierid']; ?>" class="csh-link">
        <div class="csh-tbl">
            <div class="csh-row">
                <div class="csh-name-cell"><?php echo $this->lang->line('POS_0137'); ?>:</div>
                <div class="csh-code-cell"><?php echo $row['storename']; ?></div>
            </div>   
            <div class="csh-row">
                <div class="csh-name-cell"><?php echo $this->lang->line('POS_0138'); ?>:</div>
                <div class="csh-code-cell"><?php echo $row['posname']; ?></div>
            </div>  
            <div class="csh-row">
                <div class="csh-name-cell"><?php echo $this->lang->line('POS_0139'); ?>:</div>
                <div class="csh-code-cell"><?php echo $row['cashiername']; ?></div>
            </div>  
        </div>
    </a>
    <?php
    }
    ?>

    <div class="text-center mt15"><?php echo $this->lang->line('POS_0140'); ?></div>
</div>