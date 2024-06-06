<div class="ui-block">
    <div class="ui-block-title">
        <h6 class="title">Идэвхитэй гишүүд</h6>
    </div>
    <div class="scroller" style="height: 400px" data-handle-color="#999">
        <ul class="widget w-friend-pages-added notification-list friend-requests">
            <?php
            if ($this->activeUsersData) {
                foreach ($this->activeUsersData as $row) {
            ?>
            <li class="inline-items">
                <div class="author-thumb">
                    <?php echo Ue::getProfilePhoto($row['PICTURE']); ?>
                </div>
                <div class="notification-event">
                    <a href="javascript:;" class="h6 notification-friend"><?php echo $row['FIRST_NAME']; ?></a>
                    <span class="chat-message-item"><?php echo $row['LAST_NAME']; ?></span>
                </div>
            </li>
            <?php
                }
            } 
            ?>
        </ul>
    </div>
</div>
