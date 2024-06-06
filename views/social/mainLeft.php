<aside class="col col-xl-3 order-xl-1 col-lg-6 order-lg-2 col-md-6 col-sm-12 col-12">
    <div class="ui-block">	
        <div class="ui-block-title">
            <a href="social" class="h6 title">Эхлэл хуудас</a>
        </div>
        <!--<div class="ui-block-title">
            <a href="social/profile" class="h6 title">Миний профайл</a>
        </div>-->
        <div class="ui-block-title">
            <a href="social/groups" class="h6 title">Бүлгэмүүд</a>
        </div>
        <div class="ui-block-title">
            <a href="social/saved" class="h6 title">Хадгалсан</a>
        </div>
        <!--<div class="ui-block-title">
            <a href="social/members" class="h6 title">Гишүүд</a>
        </div>-->
    </div>
    
    <?php
    if (isset($this->createdGroups) && $this->createdGroups) {
    ?>
    <div class="ui-block">
        <div class="ui-block-title">
            <h6 class="title">Миний үүсгэсэн бүлгэмүүд</h6>
        </div>

        <ul class="widget w-friend-pages-added notification-list friend-requests">
            <?php
            foreach ($this->createdGroups as $createdGroups) {
                
                if ($createdGroups['COVER_PICTURE'] && file_exists('storage/uploads/social/posts/images/thumb/' . $createdGroups['COVER_PICTURE'])) {
                    $coverSrc = 'storage/uploads/social/posts/images/thumb/' . $createdGroups['COVER_PICTURE'];
                } else {
                    $coverSrc = 'assets/core/global/img/team.png';
                }    
            ?>
            <li class="inline-items">
                <div class="author-thumb">
                    <img src="<?php echo $coverSrc; ?>">
                </div>
                <div class="notification-event">
                    <a href="social/group/<?php echo $createdGroups['ID']; ?>" class="h6 notification-friend"><?php echo $createdGroups['GROUP_NAME']; ?></a>
                </div>
            </li>
            <?php
            }
            ?>
        </ul>
    </div>
    <?php
    }
    
    if (isset($this->joinedGroups) && $this->joinedGroups) {
    ?>

    <div class="ui-block">
        <div class="ui-block-title">
            <h6 class="title">Миний элссэн бүлгэмүүд</h6>
        </div>

        <ul class="widget w-friend-pages-added notification-list friend-requests">
            <?php
            foreach ($this->joinedGroups as $joinedGroups) {
                
                if ($joinedGroups['COVER_PICTURE'] && file_exists('storage/uploads/social/posts/images/thumb/' . $joinedGroups['COVER_PICTURE'])) {
                    $coverSrc = 'storage/uploads/social/posts/images/thumb/' . $joinedGroups['COVER_PICTURE'];
                } else {
                    $coverSrc = 'assets/core/global/img/team.png';
                }    
            ?>
            <li class="inline-items">
                <div class="author-thumb">
                    <img src="<?php echo $coverSrc; ?>">
                </div>
                <div class="notification-event">
                    <a href="social/group/<?php echo $joinedGroups['ID']; ?>" class="h6 notification-friend"><?php echo $joinedGroups['GROUP_NAME']; ?></a>
                </div>
            </li>
            <?php
            }
            ?>
        </ul>
    </div>
    <?php
    }
    ?>
</aside>