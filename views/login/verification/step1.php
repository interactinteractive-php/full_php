<div class="verification-step1">
    <span class="text-black font-weight-bold line-height-normal">Та баталгаажуулах код хүлээн авах утасны дугаар<br>эсвэл и-мэйл хаягаа сонгоно уу.</span>
    <div class="list-group list-group-flush bg-transparent mt-2">
        <?php
        if ($this->email) {
        ?>
        <a href="javascript:;" class="list-group-item list-group-item-action border-radius-3 active" data-type="email">
            <i class="icon-envelop2 mr-3"></i>
            <?php echo maskEmail($this->email); ?>
            <i class="icon-checkmark3 text-green ml-auto"></i>
        </a>
        <?php
        }
        
        if ($this->phoneNumber) {
            
            $activeClass = $activeIcon = '';
            
            if (!$this->email) {
                
                $activeClass = ' active';
                $activeIcon = '<i class="icon-checkmark3 text-green ml-auto"></i>';
            }
        ?>
        <a href="javascript:;" class="list-group-item list-group-item-action border-radius-3<?php echo $activeClass; ?>" data-type="phoneNumber">
            <i class="icon-mobile mr-3"></i>
            <?php echo maskPhoneNumber($this->phoneNumber) . $activeIcon; ?>
        </a>
        <?php
        }
        ?>
    </div>
    <a href="logout" class="verification-logout btn btn-lg btn-light btn-block mt-1 font-size-13 py-2" style="float:left;width:48%;margin-right:2%;">Буцах</a>
    <button type="button" class="btn btn-lg bg-primary bg-root-color btn-block mt-1 font-size-13 py-2" style="width:50%;">Код илгээх</button>
</div>