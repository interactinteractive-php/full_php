<?php
if ($this->comments) {
    foreach ($this->comments as $commentRow) {
        $commentId = $commentRow['ID'];
        $commentUserName = $commentRow['LAST_NAME'] . ' ' . $commentRow['FIRST_NAME'];
        $commentDate = $commentRow['CREATED_DATE'];
        $commentUserId = $commentRow['USER_ID'];
?>
<ul class="list-unstyled main-comment has-replies" id="comment<?php echo $commentId; ?>">    
    <li> 
        <div class="comments">
            <div class="commenter-avatar">
                <a href="javascript:;">
                    <?php echo Ue::getProfilePhoto($commentRow['PICTURE'], 'title="'.$commentUserName.'"'); ?>
                </a>
            </div>
            <div class="comments-list">
                <div class="commenter">
                    <?php
                    if ($commentUserId == $this->userId) {
                    ?>
                    <a href="javascript:;" class="delete-comment delete_comment" data-comment-id="<?php echo $commentId; ?>" data-post-id="<?php echo $this->postId; ?>"><i class="fa fa-times"></i></a>
                    <?php
                    }
                    ?>
                    <div class="commenter-name">
                        <a href="javascript:;"><?php echo $commentUserName; ?></a>
                        <span class="comment-description"><?php echo $commentRow['COMMENT_TXT']; ?></span>
                    </div>
                    <ul class="list-inline comment-options">
                        <!--<li><a href="#" class="show-comment-reply">reply</a></li>    
                        <li>.</li>-->
                        <li><a href="javascript:;" class="show-likes like3-584"><i class="fa fa-thumbs-up"></i> Like</a></li>
                        <li class="show-likes like4-584 hidden"></li>
                        <li>
                            <time class="post-time timeago" datetime="<?php echo $commentDate; ?>" title="<?php echo $commentDate; ?>">less than a minute ago</time>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </li>
</ul>
<?php
    }
}
?>