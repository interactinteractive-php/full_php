<aside class="col col-xl-3 order-xl-3 col-lg-6 order-lg-3 col-md-6 col-sm-12 col-12">
            
    <div class="ui-block">			
        <div class="friend-item">
            <div class="friend-header-thumb">
                <img src="assets/custom/css/social/img/cover.jpg">
            </div>

            <div class="friend-item-content">

                <div class="friend-avatar">
                    <div class="author-thumb">
                        <?php echo Ue::getSessionPhoto(); ?>
                    </div>
                    <div class="author-content">
                        <a href="profile" class="h5 author-name"><?php echo Ue::getSessionPersonWithLastName(); ?></a>
                    </div>
                </div>

                <div class="friend-count">
                    <a href="javascript:;" class="friend-count-item">
                        <div class="h6"><?php echo $this->postsCount['POSTS_COUNT']; ?></div>
                        <div class="title">Нийтлэл</div>
                    </a>
                    <a href="javascript:;" class="friend-count-item">
                        <div class="h6"><?php echo $this->postsCount['IMAGE_COUNT']; ?></div>
                        <div class="title">Photos</div>
                    </a>
                    <a href="javascript:;" class="friend-count-item">
                        <div class="h6"><?php echo $this->postsCount['VIDEO_COUNT']; ?></div>
                        <div class="title">Videos</div>
                    </a>
                </div>

            </div>
        </div>			
    </div>

    <?php echo $this->activeUsers; ?>

</aside>