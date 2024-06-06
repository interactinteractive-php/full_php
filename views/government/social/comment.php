<?php
    $sessionUserId = Ue::sessionUserId();
    if ($this->treeData) {
        foreach ($this->treeData as $commentRow) {
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
                        <img src="<?php echo $commentRow['userpicture']; ?>" data-userid="<?php echo issetParam($this->post['userid']); ?>" class="rounded-circle" width="40" height="40" onerror="onNCUserImgError(this);">
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
                        <ul class="list-inline comment-options" data-comment-id="<?php echo $commentId; ?>" data-post-id="<?php echo issetParam($this->post['id']); ?>">
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
                            <li class="show-likes like4-584 pull-right"><a href="javascript:;" data-post-id="<?php echo issetParam($this->post['id']); ?>" class="like-people" data-comment-id="<?php echo $commentId; ?>"><span class="like-count"><?php echo issetParamZero($commentRow['likecnt']) ?></span> people</a> like comment</li>
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
                                                    <img src="<?php echo $child_commentRow['userpicture']; ?>" data-userid="<?php echo issetParam($this->post['userid']); ?>" class="rounded-circle" width="40" height="40" onerror="onNCUserImgError(this);">
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
                                                    <ul class="list-inline comment-options" data-comment-id="<?php echo $child_commentId; ?>" data-post-id="<?php echo issetParam($this->post['id']); ?>">
                                                        <li>
                                                            <a href="javascript:;" class="show-comment-reply comment-reply">reply</a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:;" class="show-likes comment-like like3-584" data-comment-islike="<?php echo issetParam($child_commentRow['isliked']) ?>"><?php echo issetParam($child_commentRow['isliked']) == '1' ? '<img src="assets/custom/img/ico/like.png" width="24" height="24"/> ' : '<i class="fa ci-icon-like"></i>'; ?> <?php echo issetParam($child_commentRow['isliked']) == '1' ? 'Unlike' : 'Like' ?></a>
                                                        </li>
                                                        <li>
                                                            <time class="post-time timeago" datetime="<?php echo $child_commentDate; ?>" title="<?php echo $child_commentDate; ?>">less than a minute ago</time>
                                                        </li>
                                                        <li class="show-likes like4-584 pull-right"><a href="javascript:;" data-post-id="<?php echo issetParam($this->post['id']); ?>" class="like-people" data-comment-id="<?php echo $child_commentId; ?>"><span class="like-count"><?php echo issetParamZero($child_commentRow['likecnt']) ?></span> people</a> like comment</li>
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
                                                                                    <img src="<?php echo $child2_commentRow['userpicture']; ?>" data-userid="<?php echo issetParam($this->post['userid']); ?>" class="rounded-circle" width="40" height="40" onerror="onNCUserImgError(this);">
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
                                                                                    <ul class="list-inline comment-options" data-comment-id="<?php echo $child2_commentId; ?>" data-post-id="<?php echo issetParam($this->post['id']); ?>">
                                                                                        <li>
                                                                                            <a href="javascript:;" class="show-likes comment-like like3-584" data-comment-islike="<?php echo issetParam($child2_commentRow['isliked']) ?>"><?php echo issetParam($child2_commentRow['isliked']) == '1' ? '<img src="assets/custom/img/ico/like.png" width="24" height="24"/> ' : '<i class="fa ci-icon-like"></i>'; ?> <?php echo issetParam($child2_commentRow['isliked']) == '1' ? 'Unlike' : 'Like' ?></a>
                                                                                        </li>
                                                                                        <li>
                                                                                            <time class="post-time timeago" datetime="<?php echo $child2_commentDate; ?>" title="<?php echo $child2_commentDate; ?>">less than a minute ago</time>
                                                                                        </li>
                                                                                        <li class="show-likes like4-584 pull-right"><a href="javascript:;" data-post-id="<?php echo issetParam($this->post['id']); ?>" class="like-people" data-comment-id="<?php echo $child2_commentId; ?>"><span class="like-count"><?php echo issetParamZero($child2_commentRow['likecnt']) ?></span> people</a> like comment</li>
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