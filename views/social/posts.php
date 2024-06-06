<?php
if (isset($this->posts['rows'])) {
    
    $posts = $this->posts['rows'];
    $scModel = new Social_Model(); 
    
    foreach ($posts as $post) {
        
        $postId = $post['ID'];
        $userId = $post['USER_ID'];
        $userName = $post['LAST_NAME'] . ' ' . $post['FIRST_NAME'];
        $postDate = $post['CREATED_DATE'];
        
        $commentList = $scModel->getPostsCommentByPostIdModel($postId);
        $mediaList   = $scModel->getPostsMediaByPostIdModel($postId);
        
        $commentCount = count($commentList);
?>
<div class="panel panel-default panel-post animated" id="post<?php echo $postId; ?>">
    <div class="panel-heading no-bg">
        <div class="post-author">
            <div class="post-options">
                <ul class="list-inline m-0">
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-togle post_actions" data-toggle="dropdown" role="button" aria-expanded="false" data-post-id="<?php echo $postId; ?>"><i class="fa fa-ellipsis-h"></i></a>
                        <ul class="dropdown-menu"></ul>
                    </li>
                </ul>
            </div>
            <div class="user-avatar">
                <a href="social/profile/<?php echo $userId; ?>">
                    <?php echo Ue::getProfilePhoto($post['PICTURE']); ?>
                </a>
            </div>
            <div class="user-post-details">
                <ul class="list-unstyled m-0">
                    <li>
                        <a href="social/profile/<?php echo $userId; ?>" class="user-name user">
                            <?php echo $userName; ?>
                        </a>
                        <div class="small-text"></div>
                    </li>
                    <li>
                        <time class="post-time timeago" datetime="<?php echo $postDate; ?>" title="<?php echo $postDate; ?>">less than a minute ago</time>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="text-wrapper">
            <p class="post-description">
                <?php echo nl2br($post['DESCRIPTION']); ?>
            </p>
            
            <?php
            if ($post['YOUTUBE_ID'] != '') {
                echo '<div class="post-v-holder"><iframe width="100%" height="350px" src="//www.youtube.com/embed/'.$post['YOUTUBE_ID'].'?autoplay=0&showinfo=0&rel=0" frameborder="0" allowfullscreen></iframe></div>';
            }

            if ($mediaList) {
            ?>
            <div class="post-image-holder">
                <?php
                $images = $otherFiles = '';
                
                foreach ($mediaList as $media) {
                    
                    $newFileName   = $media['NEW_FILE_NAME'];
                    $fileExtension = $media['FILE_EXTENSION'];
                    $filePath      = $media['FILE_PATH'];
                    $fileName      = $media['FILE_NAME'];
                    
                    if (in_array($fileExtension, Social::$acceptImageExtension)) {
                        
                        $images .= '<div class="post-image"><img class="scl-fancybox" src="'.$filePath.'thumb/'.$newFileName.'" title="'.$fileName.'" data-src="'.$filePath.$newFileName.'"/></div>';
                        
                    } else {
                        
                        $otherFiles .= '<a href="'.$filePath.$newFileName.'" class="btn blue btn-circle btn-xs post-file" target="_blank"><i class="fa fa-file"></i> '.$fileName.'</a>';
                    }
                }
                
                echo $images;
                echo '<div class="clearfix"></div>';
                echo $otherFiles;
                ?>
            </div>
            <?php
            }
            ?>
        </div>
    </div>

    <div class="panel-footer socialite">
        <ul class="list-inline footer-list">
            <li>
                <a href="javascript:;" class="like-post" data-post-id="<?php echo $postId; ?>" data-like-id="<?php echo $post['LIKE_ID']; ?>">
                    <?php 
                    if ($post['LIKE_ID']) {
                        echo '<i class="fa fa-thumbs-o-down"></i> Unlike';
                    } else {
                        echo '<i class="fa fa-thumbs-o-up"></i> Like';
                    }
                    ?>
                </a>
            </li>
            <li><a href="javascript:;" class="show-comments"><i class="fa fa-comment-o"></i> <span class="comment-count"><?php echo $commentCount; ?></span> Comments</a></li>
            <li class="float-right text-right"><a href="javascript:;" data-post-id="<?php echo $postId; ?>" class="like-people"><span class="like-count"><?php echo $post['LIKE_COUNT']; ?></span> people</a> like this</li>
        </ul>
    </div>

    <div class="comments-section all_comments">
        <div class="comments-wrapper">         

            <div class="comments post-comments-list">
                
                <?php
                if ($commentList) {
                    foreach ($commentList as $commentRow) {
                        
                        $commentId       = $commentRow['ID'];
                        $commentUserName = $commentRow['LAST_NAME'] . ' ' . $commentRow['FIRST_NAME'];
                        $commentDate     = $commentRow['CREATED_DATE'];
                        $commentUserId   = $commentRow['USER_ID'];
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
                                    <a href="javascript:;" class="delete-comment delete_comment" data-comment-id="<?php echo $commentId; ?>" data-post-id="<?php echo $postId; ?>"><i class="fa fa-times"></i></a>
                                    <?php
                                    }
                                    ?>
                                    <div class="commenter-name">
                                        <a href="javascript:;"><?php echo $commentUserName; ?></a>
                                        <span class="comment-description"><?php echo $commentRow['COMMENT_TXT']; ?></span>
                                    </div>
                                    <ul class="list-inline comment-options">
                                        <!--<li><a href="javascript:;" class="show-comment-reply">reply</a></li>    
                                        <li>.</li>-->
                                        <li><a href="javascript:;" class="show-likes like3-584"><i class="fa fa-thumbs-up"></i> like</a></li>
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
            </div>        
            
            <div class="to-comment">  <!-- to-comment -->
                
                <div class="commenter-avatar">
                    <a href="javascript:;"><?php echo Ue::getSessionProfilePhoto(); ?></a>
                </div>
                
                <div class="comment-textfield">
                    <form class="comment-form" method="post" enctype="multipart/form-data">
                        <div class="comment-holder">
                            <input class="form-control post-comment" autocomplete="off" data-post-id="<?php echo $postId; ?>" name="post_comment" placeholder="Write a comment... Press Enter to Post" required="required">
                            <input type="file" class="comment-images-upload hidden" accept="image/jpeg,image/png,image/gif" name="comment_images_upload">
                            <ul class="list-inline meme-reply hidden">
                                <li><a href="javascript:;" id="imageComment"><i class="fa fa-camera" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>                 
                        <div id="comment-image-holder"></div>               
                    </form>
                </div>
                <div class="clearfix"></div>
            </div><!-- to-comment -->
        </div>        
    </div>
</div> 
<?php
    }
    
    if (isset($this->pageNumber)) {
?>
    <div class="load-more text-center" data-last-page="<?php echo $this->pageNumber; ?>" style="display: none;">
        <img src="assets/core/global/img/loading-spinner-grey.gif"/>
    </div>
<?php
    }
} else {
?>
<div class="load-more text-center" data-last-page="0" style="display: none;">
    <img src="assets/core/global/img/loading-spinner-grey.gif"/>
</div>
<?php
}
?>