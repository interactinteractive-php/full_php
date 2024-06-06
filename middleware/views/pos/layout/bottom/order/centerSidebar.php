<div class="pos-center-inside">
    <div class="pos-bill-number hide">БИЛЛ #: <span id="pos-bill-number">1</span></div>
    <div class="pos-center-inside-height">
        <table class="fancyTable" id="posTable" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="width: 40px;"></th>
                    <th style="width: 140px; text-align: left"><?php echo $this->lang->line('code'); ?></th>
                    <th style="width: 120px; text-align: left" data-config-column="serialnumber">Сериал</th>
                    <th style="text-align: center"><?php echo $this->lang->line('POS_0004'); ?></th>
                    <th style="width: 100px; text-align: right"><?php echo $this->lang->line('POS_0007'); ?></th>
                    <th style="width: 100px; text-align: right" data-config-column="unitreceivable"><?php echo $this->lang->line('POS_0158'); ?></th>
                    <th style="width: 50px; text-align: right"><?php echo $this->lang->line('FIN_QUANTITY'); ?></th>
                    <th style="width: 110px; text-align: right"><?php echo $this->lang->line('POS_0160'); ?></th>
                    <th style="width: 20px; text-align: center" data-config-column="delivery"><i class="fa fa-truck" title="<?php echo $this->lang->line('POS_0014'); ?>"></i></th>
                    <th style="width: 280px; text-align: center" data-config-column="salesperson"><?php echo $this->lang->line('POS_0161'); ?></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>    
</div>