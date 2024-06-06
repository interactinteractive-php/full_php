<?php $otherUniqId = getUID(); ?>
<div class="<?php echo $otherUniqId ?>">
    <?php
    if (isset($this->postsData)) {
        $sessionUserId = Ue::sessionUserId();

        foreach ($this->postsData as $post) {
            $actionClass = false;
            if ((defined('IS_ADMIN') && IS_ADMIN == '1') || $post['userid'] === Ue::sessionUserId() || (Ue::sessionUserId() === '2' || Ue::sessionUserId() === '1')) {
                $actionClass = true;
            }

            $commentList = issetParamArray($post['ntr_social_comment_list']);
            $mediaList   = issetParamArray($post['int_content_map_dv']);

            $commentCount = count($commentList);
    ?>
    <div class="panel panel-default panel-post animated" id="post<?php echo $post['id']; ?>">
        <div class="panel-heading no-bg">
            <div class="post-author">

                <?php if ($actionClass) { ?>
                    <div class="post-options">
                        <ul class="list-inline m-0 remove-actions">
                            <li class="dropdown">
                                <a href="javascript:;" class="dropdown-togle post_actions1" data-toggle="dropdown" role="button" aria-expanded="false" data-post-id="<?php echo $post['id']; ?>"><i class="fa fa-ellipsis-h"></i></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="javascript:;" class="dropdown-item" data-post-id="<?php echo $post['id']; ?>" onclick="editSocialPost_<?php echo $this->uniqId ?>(this)"><i class="fa fa-edit"></i> <?php echo Lang::line('edit_btn') ?></a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="dropdown-item" data-post-id="<?php echo $post['id']; ?>" onclick="deleteSocialPost_<?php echo $this->uniqId ?>(this)"><i class="fa fa-trash"></i> <?php echo Lang::line('delete_btn') ?></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                <?php } ?>

                <div class="user-avatar">
                    <a href="javascript:;" shref="social/profile/<?php echo $post['userid']; ?>">
                        <img src="<?php echo $post['userpicture']; ?>" data-userid="<?php echo $post['userid']; ?>" class="rounded-circle" width="40" height="40" onerror="onNCUserImgError(this);">
                    </a>
                </div>
                <div class="user-post-details">
                    <ul class="list-unstyled m-0">
                        <li>
                            <a href="javascript:;" shref="social/profile/<?php echo $post['userid']; ?>" class="user-name user">
                                <?php echo $post['name']; ?>
                            </a>
                            <div class="small-text"></div>
                        </li>
                        <li>
                            <time class="post-time" datetime="<?php echo $post['createddate']; ?>" title="<?php echo $post['createddate']; ?>"><?php echo $post['tsag'] ?></time>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="text-wrapper">
                <p class="post-description">
                    <?php echo nl2br($post['longdescr']); ?>
                </p>
                <?php
                if (isset($post['youtubeid']) && $post['youtubeid'] != '') {
                    echo '<div class="post-v-holder"><iframe width="100%" height="350px" src="//www.youtube.com/embed/'.$post['youtubeid'].'?autoplay=0&showinfo=0&rel=0" frameborder="0" allowfullscreen></iframe></div>';
                }

                if ($mediaList) { ?>
                <div class="post-image-holder">
                    <?php
                        (String) $images = $otherFiles = '';

                        foreach ($mediaList as $mediaCon) {
                            if (issetParam($mediaCon['ecm_content'])) {
                                $media = $mediaCon['ecm_content'];

                                if (in_array($media['fileextension'], array('jpg', 'jpeg', 'png', 'gif', 'bmp'))) {
                                    $images .= '<div class="post-image"><img class="scl-fancybox" src="'.$media['thumbphysicalpath'].'" title="'.$media['filename'].'" data-src="'.$media['physicalpath'].'"/></div>';
                                } else {
                                    $otherFiles .= '<a href="'.$media['physicalpath'].'" class="btn blue btn-circle btn-xs post-file" target="_blank"><i class="fa fa-file"></i> '.$media['filename'].'</a>';
                                }
                            }
                        }

                        echo $images;
                        echo '<div class="clearfix"></div>';
                        echo $otherFiles;
                    ?>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="panel-footer socialite">
            <ul class="list-inline footer-list">
                <li>
                    <a href="javascript:;" class="like-post" data-post-id="<?php echo $post['id']; ?>" data-like-id="<?php echo issetParam($post['id']); ?>" data-islike="<?php echo issetParam($post['isliked']) ?>">
                        <?php 
                        if (issetParam($post['isliked']) == '1') {
                            echo '<img src="assets/custom/img/ico/like.png" width="24" height="24"/> Unlike';
                        } else {
                            echo '<i class="fa ci-icon-like"></i> Like';
                        }
                        ?>
                    </a>
                </li>
                <li><a href="javascript:;" class="show-comments"><i class="fa fa-comment-o"></i> <span class="comment-count"><?php echo $commentCount; ?></span> Comments</a></li>
                <li class="float-right text-right"><a href="javascript:;" data-post-id="<?php echo $post['id']; ?>" class="like-people"><span class="like-count"><?php echo issetParamZero($post['likecnt']); ?></span> people</a> like this</li>
            </ul>
        </div>
        <div class="comments-section all_comments">
            <div class="comments-wrapper">         

                <div class="comments post-comments-list">

                    <?php
                    if ($commentList) {
                        foreach ($commentList as $commentRow) {
                            $drowJson = htmlentities(json_encode($commentRow), ENT_QUOTES, 'UTF-8');
                            $commentId       = $commentRow['id'];
                            $commentUserName = $commentRow['employeename'];
                            $commentDate     = $commentRow['createddate'];
                            $commentUserId   = $commentRow['userid'];
                    ?>
                    <ul class="list-unstyled main-comment has-replies" id="comment<?php echo $commentId; ?>">    
                        <li> 
                            <div class="comments">
                                <div class="commenter-avatar">
                                    <a href="javascript:;">
                                        <img src="<?php echo $commentRow['userpicture']; ?>" data-userid="<?php echo $post['userid']; ?>" class="rounded-circle" width="40" height="40" onerror="onNCUserImgError(this);">
                                    </a>
                                </div>
                                <div class="comments-list">
                                    <div class="commenter" data-rowdata="<?php echo $drowJson; ?>">
                                        <?php 
                                        if ($commentUserId == $sessionUserId || (defined('IS_ADMIN') && IS_ADMIN == '1')) { ?>
                                            <ul class="list-inline m-0 pull-right remove-actions">
                                                <li class="dropdown">
                                                    <a href="javascript:;" class="dropdown-togle" data-toggle="dropdown" role="button" aria-expanded="false" data-post-id="<?php echo $commentRow['postid']; ?>"><i class="fa fa-ellipsis-h"></i></a>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="javascript:;" class="dropdown-item" data-comment-id="<?php echo $commentId; ?>" data-comment="<?php echo $commentRow['comments']; ?>" data-post-id="<?php echo $commentRow['postid']; ?>" onclick="editComment_<?php echo $this->uniqId ?>(this)"><i class="fa fa-edit"></i> <?php echo Lang::line('edit_btn') ?></a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:;" class="dropdown-item" data-comment-id="<?php echo $commentId; ?>" data-post-id="<?php echo $commentRow['postid']; ?>" onclick="deleteComment_<?php echo $this->uniqId ?>(this)"><i class="fa fa-trash"></i> <?php echo Lang::line('delete_btn') ?></a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                            <?php
                                            }
                                        ?>
                                        <div class="commenter-name">
                                            <a href="javascript:;"><?php echo $commentUserName; ?></a>
                                            <span class="comment-description"><?php echo $commentRow['comments']; ?></span>
                                        </div>
                                        <ul class="list-inline comment-options" data-comment-id="<?php echo $commentId; ?>" data-post-id="<?php echo $post['id']; ?>">
                                            <li>
                                                <a href="javascript:;" class="show-comment-reply comment-reply">reply</a>
                                            </li>
                                            <li>.</li>
                                            <li>
                                                <a href="javascript:;" class="show-likes comment-like like3-584" data-comment-islike="<?php echo issetParam($commentRow['isliked']) ?>"><?php echo issetParam($commentRow['isliked']) == '1' ? '<img src="assets/custom/img/ico/like.png" width="24" height="24"/>' : '<i class="fa ci-icon-like"></i> ' ?><?php echo issetParam($commentRow['isliked']) == '1' ? 'Unlike' : 'Like' ?></a>
                                            </li>
                                            <li>
                                                <time class="post-time timeago" datetime="<?php echo $commentDate; ?>" title="<?php echo $commentDate; ?>">less than a minute ago</time>
                                            </li>
                                            <li class="show-likes like4-584 pull-right"><a href="javascript:;" data-post-id="<?php echo $post['id']; ?>" class="like-people" data-comment-id="<?php echo $commentId; ?>"><span class="like-count"><?php echo issetParamZero($commentRow['likecnt']) ?></span> people</a> like comment</li>
                                        </ul>
                                        <?php 
                                        if (issetParamArray($commentRow['children'])) {
                                            foreach ($commentRow['children'] as $child_commentRow) {
                                                $child_drowJson = htmlentities(json_encode($child_commentRow), ENT_QUOTES, 'UTF-8');
                                                $child_commentId       = $child_commentRow['id'];
                                                $child_commentUserName = $child_commentRow['employeename'];
                                                $child_commentDate     = $child_commentRow['createddate'];
                                                $child_commentUserId   = $child_commentRow['userid'];
                                                ?>
                                                <ul class="list-unstyled main-comment has-replies" id="comment<?php echo $child_commentId; ?>">    
                                                    <li> 
                                                        <div class="comments">
                                                            <div class="commenter-avatar">
                                                                <a href="javascript:;">
                                                                    <img src="<?php echo $child_commentRow['userpicture']; ?>" data-userid="<?php echo $post['userid']; ?>" class="rounded-circle" width="40" height="40" onerror="onNCUserImgError(this);">
                                                                </a>
                                                            </div>
                                                            <div class="comments-list">
                                                                <div class="commenter" data-rowdata="<?php echo $child_drowJson; ?>">
                                                                    <?php 
                                                                    if ($child_commentUserId == $sessionUserId || (defined('IS_ADMIN') && IS_ADMIN == '1')) { ?>
                                                                        <ul class="list-inline m-0 pull-right remove-actions">
                                                                            <li class="dropdown">
                                                                                <a href="javascript:;" class="dropdown-togle" data-toggle="dropdown" role="button" aria-expanded="false" data-post-id="<?php echo $commentRow['postid']; ?>"><i class="fa fa-ellipsis-h"></i></a>
                                                                                <ul class="dropdown-menu">
                                                                                    <li>
                                                                                        <a href="javascript:;" class="dropdown-item" data-comment-id="<?php echo $child_commentId; ?>" data-comment="<?php echo $child_commentRow['comments']; ?>" data-post-id="<?php echo $child_commentRow['postid']; ?>" onclick="editComment_<?php echo $this->uniqId ?>(this)"><i class="fa fa-edit"></i> <?php echo Lang::line('edit_btn') ?></a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href="javascript:;" class="dropdown-item" data-comment-id="<?php echo $child_commentId; ?>" data-post-id="<?php echo $child_commentRow['postid']; ?>" onclick="deleteComment_<?php echo $this->uniqId ?>(this)"><i class="fa fa-trash"></i> <?php echo Lang::line('delete_btn') ?></a>
                                                                                    </li>
                                                                                </ul>
                                                                            </li>
                                                                        </ul>
                                                                        <?php
                                                                        }
                                                                    ?>
                                                                    <div class="commenter-name">
                                                                        <a href="javascript:;"><?php echo $child_commentUserName; ?></a>
                                                                        <span class="comment-description"><?php echo $child_commentRow['comments']; ?></span>
                                                                    </div>
                                                                    <ul class="list-inline comment-options" data-comment-id="<?php echo $child_commentId; ?>" data-post-id="<?php echo $post['id']; ?>">
                                                                        <li>
                                                                            <a href="javascript:;" class="show-comment-reply comment-reply">reply</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="javascript:;" class="show-likes comment-like like3-584" data-comment-islike="<?php echo issetParam($child_commentRow['isliked']) ?>"><?php echo issetParam($child_commentRow['isliked']) == '1' ? '<img src="assets/custom/img/ico/like.png" width="24" height="24"/> ' : '<i class="fa ci-icon-like"></i>'; ?> <?php echo issetParam($child_commentRow['isliked']) == '1' ? 'Unlike' : 'Like' ?></a>
                                                                        </li>
                                                                        <li>
                                                                            <time class="post-time timeago" datetime="<?php echo $child_commentDate; ?>" title="<?php echo $child_commentDate; ?>">less than a minute ago</time>
                                                                        </li>
                                                                        <li class="show-likes like4-584 pull-right"><a href="javascript:;" data-post-id="<?php echo $post['id']; ?>" class="like-people" data-comment-id="<?php echo $child_commentId; ?>"><span class="like-count"><?php echo issetParamZero($child_commentRow['likecnt']) ?></span> people</a> like comment</li>
                                                                    </ul>
                                                                    <?php 
                                                                        if (issetParamArray($child_commentRow['children'])) {
                                                                            foreach ($child_commentRow['children'] as $child2_commentRow) {
                                                                                $child2_drowJson = htmlentities(json_encode($child2_commentRow), ENT_QUOTES, 'UTF-8');
                                                                                $child2_commentId       = $child2_commentRow['id'];
                                                                                $child2_commentUserName = $child2_commentRow['employeename'];
                                                                                $child2_commentDate     = $child2_commentRow['createddate'];
                                                                                $child2_commentUserId   = $child2_commentRow['userid'];
                                                                                ?>
                                                                                <ul class="list-unstyled main-comment has-replies" id="comment<?php echo $child2_commentId; ?>">    
                                                                                    <li> 
                                                                                        <div class="comments">
                                                                                            <div class="commenter-avatar">
                                                                                                <a href="javascript:;">
                                                                                                    <img src="<?php echo $child2_commentRow['userpicture']; ?>" data-userid="<?php echo $post['userid']; ?>" class="rounded-circle" width="40" height="40" onerror="onNCUserImgError(this);">
                                                                                                </a>
                                                                                            </div>
                                                                                            <div class="comments-list">
                                                                                                <div class="commenter" data-rowdata="<?php echo $child2_drowJson; ?>">
                                                                                                    <?php 
                                                                                                    if ($child2_commentUserId == $sessionUserId || (defined('IS_ADMIN') && IS_ADMIN == '1')) { ?>
                                                                                                        <ul class="list-inline m-0 pull-right remove-actions">
                                                                                                            <li class="dropdown">
                                                                                                                <a href="javascript:;" class="dropdown-togle" data-toggle="dropdown" role="button" aria-expanded="false" data-post-id="<?php echo $commentRow['postid']; ?>"><i class="fa fa-ellipsis-h"></i></a>
                                                                                                                <ul class="dropdown-menu">
                                                                                                                    <li>
                                                                                                                        <a href="javascript:;" class="dropdown-item" data-comment-id="<?php echo $child2_commentId; ?>" data-comment="<?php echo $child2_commentRow['comments']; ?>" data-post-id="<?php echo $child2_commentRow['postid']; ?>" onclick="editComment_<?php echo $this->uniqId ?>(this)"><i class="fa fa-edit"></i> <?php echo Lang::line('edit_btn') ?></a>
                                                                                                                    </li>
                                                                                                                    <li>
                                                                                                                        <a href="javascript:;" class="dropdown-item" data-comment-id="<?php echo $child2_commentId; ?>" data-post-id="<?php echo $child2_commentRow['postid']; ?>" onclick="deleteComment_<?php echo $this->uniqId ?>(this)"><i class="fa fa-trash"></i> <?php echo Lang::line('delete_btn') ?></a>
                                                                                                                    </li>
                                                                                                                </ul>
                                                                                                            </li>
                                                                                                        </ul>
                                                                                                        <?php
                                                                                                        }
                                                                                                    ?>
                                                                                                    <div class="commenter-name">
                                                                                                        <a href="javascript:;"><?php echo $child2_commentUserName; ?></a>
                                                                                                        <span class="comment-description"><?php echo $child2_commentRow['comments']; ?></span>
                                                                                                    </div>
                                                                                                    <ul class="list-inline comment-options" data-comment-id="<?php echo $child2_commentId; ?>" data-post-id="<?php echo $post['id']; ?>">
                                                                                                        <li>
                                                                                                            <a href="javascript:;" class="show-likes comment-like like3-584" data-comment-islike="<?php echo issetParam($child2_commentRow['isliked']) ?>"><?php echo issetParam($child2_commentRow['isliked']) == '1' ? '<img src="assets/custom/img/ico/like.png" width="24" height="24"/> ' : '<i class="fa ci-icon-like"></i>'; ?> <?php echo issetParam($child2_commentRow['isliked']) == '1' ? 'Unlike' : 'Like' ?></a>
                                                                                                        </li>
                                                                                                        <li>
                                                                                                            <time class="post-time timeago" datetime="<?php echo $child2_commentDate; ?>" title="<?php echo $child2_commentDate; ?>">less than a minute ago</time>
                                                                                                        </li>
                                                                                                        <li class="show-likes like4-584 pull-right"><a href="javascript:;" data-post-id="<?php echo $post['id']; ?>" class="like-people" data-comment-id="<?php echo $child2_commentId; ?>"><span class="like-count"><?php echo issetParamZero($child2_commentRow['likecnt']) ?></span> people</a> like comment</li>
                                                                                                    </ul>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </li>
                                                                                </ul>
                                                                            <?php }
                                                                        }
                                                                        ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            <?php }
                                        }
                                        ?>
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
                        <a href="javascript:;">
                            <img src="<?php echo issetParam($this->userInfo['result']['userpicture']); ?>" data-userid="<?php echo $post['userid']; ?>" class="rounded-circle" width="40" height="40" onerror="onNCUserImgError(this);">
                        </a>
                    </div>

                    <div class="comment-textfield">
                        <div class="comment-holder">
                            <textarea class="form-control post-comment" autocomplete="off" data-post-id="<?php echo $post['id']; ?>" name="post_comment" placeholder="Write a comment... Press Enter to Post" required="required" style="resize: none; border-radius: 10px;"></textarea>
                            <input type="file" class="comment-images-upload hidden" accept="image/jpeg,image/png,image/gif" name="comment_images_upload">
                            <ul class="list-inline meme-reply hidden">
                                <li><a href="javascript:;" id="imageComment"><i class="fa fa-camera" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>                 
                        <div id="comment-image-holder"></div>               
                    </div>
                    <div class="clearfix"></div>
                </div><!-- to-comment -->
            </div>        
        </div>
    </div>
    <?php }
    }
    ?>
</div>
<script type="text/javascript">
    
jQuery(document).ready(function () {

    $('.<?php echo $otherUniqId ?>').find('.scl-fancybox:not([data-fancybox])').each(function () {
        var $this = $(this);
        $this.fancybox({
            href: $this.attr('data-src'),
            prevEffect: 'none',
            nextEffect: 'none',
            titlePosition: 'over',
            closeBtn: true,
            helpers: {
                overlay: {
                    locked: false
                }
            }
        });

        $this.attr('data-fancybox', '1');
    });

});

</script>