<?php
if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.'); ?>
    
<li class="dropdown" id="header_notification_bar">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-envelope-alt"></i>
        <span class="badge"><?php echo $this->totalCount; ?></span>
    </a>
    <ul class="dropdown-menu extended notification">
        <li class="external">
            <a href="mdnotification/showAll">Бүгдийг харах <i class="m-icon-swapright"></i></a>
        </li>                
        <?php
        if(isset($this->list[0])) {
            foreach ($this->list as $value) {
                $class = '';
                $iClass = '';
                if ($value['notificationTypeId'] == 4) {
                    $class = 'badge-important';
                    $iClass = 'icon-bolt';
                } elseif ($value['notificationTypeId'] == 3) {
                    $class = 'badge-warning';
                    $iClass = 'icon-warning-sign';
                } elseif ($value['notificationTypeId'] == 2) {
                    $class = 'badge-info';
                    $iClass = 'icon-info-sign';
                } elseif ($value['notificationTypeId'] == 1) {
                    $class = 'badge-success';
                    $iClass = 'icon-check';
                }                
            ?>
                <li>
                    <a href="<?php echo isset($value['directUrl']) ? $value['directUrl'] . '/' . $value['notificationId'] : 'javascript:;'; ?>" target="_blank">
                        <span class="details">
                            <span class="badge <?php echo $class; ?>">
                                <i class="<?php echo $iClass; ?>"></i>
                            </span>
                            <?php echo isset($value['message']) ? html_entity_decode($value['MESSAGE'], ENT_QUOTES, 'UTF-8') : ''; ?> 
                        </span>
                        <span class="time float-right">
                            <?php
                            $now = new \DateTime('NOW');
                            $notifyDate = new \DateTime($value['notifyDate']);
                            $date_diff = date_diff($now, $notifyDate);
                            if ($date_diff->d) {
                                echo $date_diff->d . ' ' . $this->lang->line('day');
                            } else if ($date_diff->h) {
                                echo $date_diff->h . ' ' . $this->lang->line('date_hour');
                            } else if ($date_diff->i) {
                                echo $date_diff->i . ' ' . $this->lang->line('date_minute');
                            } else {
                                echo $date_diff->s . ' ' . $this->lang->line('second');
                            }
                            ?>                            
                        </span>
                    </a>
                </li>
            <?php } 
        } ?>
    </ul>
</li>