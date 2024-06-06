<?php if (!defined('_VALID_PHP')) { exit('Direct access to this location is not allowed.'); }

$targetMode = Config::getFromCache('ISTABSELFNOTIFICATION');
if ($targetMode) {
    $targetMode = '_self';
} else {
    $targetMode = '_blank';
}
if ($this->list) {
    foreach ($this->list as $value) {
        
        $message = trim($value['MESSAGE']);
        
        if ($message) {
            
            $noHtmlMessage = strip_tags(str_replace(array('<br />', '<br>', '<br/>'), ' ', html_entity_decode($message)));
            
            if ($value['IS_NO_MSG_SUBSTR'] != '1') {
                $message = mb_substr($noHtmlMessage, 0, 82) . '..';
            } else {
                $message = $noHtmlMessage;
                $noHtmlMessage = '';
            }
            
            $class = $iClass = '';
            
            if ($value['ICON'] && $value['ICON_COLOR']) {
                
                $class = $value['ICON_COLOR'];
                $iClass = $value['ICON'];
                
            } else {

                if ($value['NOTIFICATIONTYPEID'] == 4) {

                    $class = 'badge-danger';
                    $iClass = 'fa fa-times';

                } elseif ($value['NOTIFICATIONTYPEID'] == 3) {

                    $class = 'badge-warning';
                    $iClass = 'fa fa-warning';

                } elseif ($value['NOTIFICATIONTYPEID'] == 2) {

                    $class = 'badge-info';
                    $iClass = 'fa fa-info';

                } elseif ($value['NOTIFICATIONTYPEID'] == 1) {

                    $class = 'badge-success';
                    $iClass = 'fa fa-check';

                } elseif ($value['NOTIFICATIONTYPEID'] == 6) {

                    $class = 'badge-warning bg-warning-300';
                    $iClass = 'fa fa-sign-in';

                } elseif ($value['NOTIFICATIONTYPEID'] == 7) {

                    $class = 'badge-warning bg-warning-300';
                    $iClass = 'fa fa-sign-out';
                } 
            }

            $viewedClass = ($value['VIEWED_DATE'] ? '' : 'unread');
?>
    <li class="media <?php echo $viewedClass; ?>">
        <a href="mdnotification/show/<?php echo $value['NOTIFICATIONUSERID']; ?>" title="<?php echo $noHtmlMessage; ?>" class="w-100 p-3" target="<?php echo $targetMode ?>">
            <div class="col-9 pl-0">
                <div class="d-flex flex-row align-items-center">
                    <div class="mr-2">
                        <span class="badge rounded-circle badge-icon <?php echo $class; ?>">
                            <i class="<?php echo $iClass; ?>"></i>
                        </span>
                    </div>
                    <div>
                        <p class="line-height-normal mb-0 font-size-12">
                            <?php echo $message; ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-3 pl-0 pr-0">
                <p class="text-muted font-size-sm text-right line-height-normal mb-0">
                    <?php
                    $now = new \DateTime('NOW');
                    $notifyDate = new \DateTime($value['NOTIFYDATE']);
                    $date_diff = date_diff($now, $notifyDate);

                    if ($date_diff->m) {
                        echo $date_diff->m . ' ' . $this->lang->line('month'); 
                        if ($date_diff->d) {
                            echo ' ' . $date_diff->d . ' ' . $this->lang->line('day');
                        }
                    } elseif ($date_diff->d) {
                        echo $date_diff->d . ' ' . $this->lang->line('day');
                    } elseif ($date_diff->h) {
                        echo $date_diff->h . ' ' . $this->lang->line('date_hour').' '.$date_diff->i.' '.$this->lang->line('date_minute');
                    } elseif ($date_diff->i) {
                        echo $date_diff->i . ' ' . $this->lang->line('date_minute');
                    } else {
                        echo $date_diff->s . ' ' . $this->lang->line('second');
                    }
                    ?> 
                    өмнө
                </p>
            </div>
        </a>
    </li>
<?php
        }
    }
} else {
    echo '<div class="text-center p-3">No data!</div>';
}
?>