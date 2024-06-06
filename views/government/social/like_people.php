<?php
if ($this->people) {
    ?>
    <div class="general-item-list">
        <?php
        foreach ($this->people as $row) {
            $userName = $row['LAST_NAME'] . ' ' . $row['FIRST_NAME'];
            $likeDate = $row['CREATED_DATE'];
            ?>
            <div class="item">
                <div class="item-head">
                    <div class="item-details">
                        <?php echo Ue::getProfilePhoto($row['PICTURE'], 'class="item-pic"'); ?>
                        <a href="social/profile/<?php echo $row['USER_ID']; ?>" class="item-name primary-link"><?php echo $userName; ?></a>
                        <span class="item-label timeago" datetime="<?php echo $likeDate; ?>" title="<?php echo $likeDate; ?>"></span>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}
?>