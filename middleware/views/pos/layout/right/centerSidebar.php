<div class="pos-center-inside">
    <div class="pos-bill-number">БИЛЛ #: <span id="pos-bill-number"><?php echo $this->billNum; ?></span></div>
    <div class="pos-center-inside-height">
        <table class="fancyTable" id="posTable" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="width: 40px;"></th>
                    <th style="width: 140px; text-align: center">Код</th>
                    <th style="text-align: center"><?php echo $this->lang->line('POS_0004'); ?></th>
                    <th style="width: 120px; text-align: right"><?php echo $this->lang->line('POS_0007'); ?></th>
                    <th style="width: 50px; text-align: right">Тоо</th>
                    <th style="width: 120px; text-align: right"><?php echo $this->lang->line('POS_0160'); ?></th>
                    <th style="width: 20px; text-align: center"><i class="fa fa-truck" title="<?php echo $this->lang->line('POS_0014'); ?>"></i></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right">НИЙТ:</td>
                    <td style="text-align: right" class="pos-total-qty">0</td>
                    <td style="text-align: right"></td>
                    <td style="text-align: right"></td>
                </tr>
            </tfoot>
        </table>
    </div>    
</div>