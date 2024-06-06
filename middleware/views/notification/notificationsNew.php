<?php
if (!defined('_VALID_PHP')) {
    exit('Direct access to this location is not allowed.');
}
if ($this->totalCount > 0) {
    if ($this->list) {
        ?>
        <li class="separator hide"></li>
        <li class="dropdown dropdown-extended dropdown-notification dropdown-dark" id="header_notification_bar">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true">
                <i class="fa fa-exclamation-triangle fa-2"></i>
                <span class="badge badge-danger" alt="<?php echo $this->time.'ms' ?>"><?php echo $this->totalCount; ?></span>
            </a>
            <ul class="dropdown-menu">
                <li class="external">
                    <h3><a href="mdnotification" style="color: #aeb2c4">Бүгдийг харах</a></h3>
                </li>
                <li>
                    <ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
                        <?php
                        foreach ($this->list as $value) {
                            $class = '';
                            $iClass = '';
                            if ($value['NOTIFICATIONTYPEID'] == 4) {
                                $class = 'badge-danger';
                                $iClass = 'fa-times';
                            } else if ($value['NOTIFICATIONTYPEID'] == 3) {
                                $class = 'badge-warning';
                                $iClass = 'fa-warning';
                            } else if ($value['NOTIFICATIONTYPEID'] == 2) {
                                $class = 'badge-info';
                                $iClass = 'fa-info';
                            } else if ($value['NOTIFICATIONTYPEID'] == 1) {
                                $class = 'badge-success';
                                $iClass = 'fa-check';
                            }
                            ?>
                            <li>
                               <a href="<?php echo URL; ?>mdnotification/show/<?php echo $value['NOTIFICATIONUSERID']; ?>" >
                                    <span class="time">
                                        <?php
                                        $now = new \DateTime('NOW');
                                        $notifyDate = new \DateTime($value['NOTIFYDATE']);
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
                                    <span class="details">
                                        <span class="badge label-sm badge-icon <?php echo $class; ?>">
                                            <i class="fa <?php echo $iClass; ?>"></i>
                                        </span>
                                        <?php echo isset($value['MESSAGE']) ? html_entity_decode($value['MESSAGE'], ENT_QUOTES, 'UTF-8') : ''; ?> 
                                    </span>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </li>
            </ul>
        </li>
        <li class="separator hide"></li>
        <?php
    }
}
?>