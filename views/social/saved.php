<div class="container">
    <div class="row">
        <main class="col col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
            
            <?php
            if ($this->savedItems) {
                foreach ($this->savedItems as $item) {
                    $fullName = $item['LAST_NAME'].' '.$item['FIRST_NAME'];
            ?>
            <div class="scl-save-item">
                <div class="scl-save-item-avatar">
                    <?php echo Ue::getProfilePhoto($item['PICTURE'], 'class="rounded-circle" title="'.$fullName.'"'); ?>
                </div>
                <div class="scl-save-item-content">
                    
                    <button type="button" data-post-id="<?php echo $item['ID']; ?>" class="post-unsave-item" data-mode="remove">
                        <i class="icon-cross2 font-size-12"></i>
                    </button>
                    
                    <a href="social/post/<?php echo $item['ID']; ?>" class="scl-save-item-title"><?php echo $fullName; ?></a>
                    <div class="scl-save-item-descr"><?php echo nl2br($item['DESCRIPTION']); ?></div>
                    
                    <i class="fa fa-comment-o"></i> <?php echo $item['COMMENT_COUNT']; ?> Comments
                </div>
                <div class="clearfix"></div>
            </div>
            <?php
                }
            }
            ?>
            
        </main>    
        
        <?php
        echo $this->mainLeft; 
        echo $this->mainRight; 
        ?>
    </div>
</div>