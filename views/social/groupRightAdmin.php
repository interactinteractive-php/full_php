<aside class="col col-xl-4 order-xl-3 col-lg-6 order-lg-3 col-md-6 col-sm-12 col-12">      
    <div class="ui-block">
        <div class="ui-block-title">
            <h6 class="title">Бүлгийн гишүүд</h6>
        </div>
        <ul class="widget w-friend-pages-added notification-list friend-requests">
            <?php
            if ($this->members) {
                foreach ($this->members as $row) {
            ?>
            <li class="inline-items">
                <div class="author-thumb">
                    <?php echo Ue::getProfilePhoto($row['PICTURE']); ?>
                </div>
                <div class="notification-event">
                    <a href="javascript:;" class="h6 notification-friend"><?php echo $row['FIRST_NAME']; ?></a>
                    <span class="chat-message-item"><?php echo $row['LAST_NAME']; ?></span>
                </div>
                
                <?php
                if ($this->userId != $row['USER_ID']) {
                ?>
                <a href="javascript:;" class="float-right scl-group-exit-member" data-user-id="<?php echo $row['USER_ID']; ?>" data-group-id="<?php echo $this->groupId; ?>" title="Бүлгээс хасах">
                    <i class="icon-cross2 font-size-12"></i>
                </a>
                <?php
                }
                ?>
            </li>
            <?php
                }
            } else {
                echo html_tag('div', array('class' => 'alert alert-info'), 'No member!'); 
            }
            ?>
        </ul>
    </div>
</aside>