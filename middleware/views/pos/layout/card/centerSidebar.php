<div class="pos-center-inside">
    <div class="pos-bill-number d-none">
        <div class="d-flex" style="justify-content: space-between;">
            <div>
                <?php
                $cashRegisterCode = Session::get(SESSION_PREFIX.'cashRegisterCode');
                
                if ($cashRegisterCode) {
                    echo '<span style="font-weight: normal;">' . $cashRegisterCode.' - '.Session::get(SESSION_PREFIX.'cashRegisterName') . '</span><span class="ml6 mr6">|</span>'; 
                }
                
                if ($this->getDateCashier && $this->getDateCashier['bookdate']) {
                    echo '<span>'.$this->getDateCashier['bookdate'].'</span></span><span class="ml6 mr6">|</span>';
                }  
                ?>
                
                <span style="font-size: 12px;font-weight: normal;"><?php $this->lang->line('POS_0210'); ?> #: <span id="pos-bill-number"><?php echo $this->billNum; ?></span></span>
                
                <?php if ($this->isBasketOnly && $this->posOrderTimer) { ?>
                    <span class="ml6 mr6">|</span>
                    <span class="posTimerInit"></span>
                <?php } ?>
            </div>
            <div>
                <span class="mr10" style="font-weight: normal;width: 250px;overflow: hidden;display: block;font-size: 13px;text-align: right;line-height: 21px;"><?php echo $this->getApiInfo['registerNo'] . (isset($this->getApiNameInfo['name']) && $this->getApiNameInfo['name'] ? ' - ' . $this->getApiNameInfo['name'] : $this->getApiNameInfo['name']); ?></span>
            </div>
        </div>
        <?php
        // if (isset($this->getLocker)) {
        //     echo '<div style="position: absolute;right: 50px;top: -5px;"><a href="javascript:;" style="color:#fff" class="navbar-nav-link pos-header-basket" title="Хүлээлгийн талонууд" onclick="posBasketList(this);" data-criteria="keyCode='.$this->getLocker['keycode'].'">'.
        //         '<div class="pos-basket-icon"><i class="fa fa-shopping-cart"></i></div>'.
        //         '<div class="pos-basket-count" style="margin-top:3px">'.$this->basketCount.'</div>'.
        //         '</a></div>';
        // }
        ?>
    </div>
    <div class="pos-center-inside-height mt8">
        <table class="fancyTable" id="posTable" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="width: 70px;" class="d-none"></th>
                    <th style="width: 140px; text-align: left" class="d-none"><?php echo $this->lang->line('code'); ?></th>
                    <th style="width: 120px; text-align: left" data-config-column="serialnumber"><?php echo $this->lang->line('POS_0211'); ?></th>
                    <th style="width: <?php echo $this->isIpad ? 100 : 150 ?>px; text-align: left"><span style="left: 62px;position: absolute"><?php echo $this->lang->line('POS_0004'); ?></span> <input type="checkbox" style="height: 24px; width: 42px;" class="seperate-calculation notuniform notuniformpos ml6 d-none" title="Тооцоо салгах эсэх"></th>
                    <th style="width: 45px; text-align: right" class=""></th>
                    <th style="width: 100px; text-align: right" data-config-column="unitreceivable" class="d-none"><?php echo $this->lang->line('POS_0158'); ?></th>
                    <th style="width: 94px; text-align: center"><button type="button" class="btn btn-sm btn-circle uppercase posRemoveItemBtnHeader<?php echo Session::get(SESSION_PREFIX.'posTypeCode') != '3' ? " hidden" : ""; ?>" style="position: absolute;background-color: #fb7257;margin-left: -65px;color: #fff;padding: 3px 10px 3px 10px;margin-top: -5px;" onclick="posRemoveItemHeader();">Устгах</button><span class="infoShortcut" style="position: absolute;margin-left: 27px;font-size: 9px;">(F10)</span> <?php echo $this->lang->line('FIN_QUANTITY'); ?></th>
                    <th style="width: 60px; text-align: right">Нийт</th>
                    <th style="width: 20px; text-align: center" data-config-column="delivery"><i class="fa fa-truck" title="<?php echo $this->lang->line('POS_0014'); ?>"></i></th>
                    <th style="width: 280px; text-align: center" data-config-column="salesperson"><?php echo $this->lang->line('POS_0161'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($this->getItems) { 
                    echo $this->getItems;
                }
                ?>
            </tbody>
        </table>
    </div>    
</div>