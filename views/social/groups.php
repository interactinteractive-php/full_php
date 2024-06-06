<div class="container">
    <div class="row">
        <main class="col col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div class="scl-groups-title">
                    <span><i class="fa fa-users"></i> Бүлгэмүүд</span>
                </div>
                <button type="button" class="btn btn-sm green float-right" onclick="createGroup(this);"><i class="icon-plus3 font-size-12"></i> Бүлгэм үүсгэх</button>
            </div>
            <div class="clearfix"></div>
            
            <div class="scl-groups">
                
                <?php
                if (isset($this->groups['rows'])) {
                    
                    $groups = $this->groups['rows'];
                    
                    foreach ($groups as $item) {
                        
                        if ($item['COVER_PICTURE'] && file_exists('storage/uploads/social/posts/images/thumb/' . $item['COVER_PICTURE'])) {
                            $coverSrc = 'storage/uploads/social/posts/images/thumb/' . $item['COVER_PICTURE'];
                        } else {
                            $coverSrc = 'assets/core/global/img/team.png';
                        }
                        
                        $privacyName = ($item['PRIVACY_TYPE'] == 'public' ? 'Нээлттэй' : 'Хаалттай');
                ?>
                <div class="scl-group-item">
                    <div class="scl-group-item-avatar">
                        <img src="<?php echo $coverSrc; ?>">
                    </div>
                    <div class="scl-group-item-content">
                        <a href="social/group/<?php echo $item['ID']; ?>" class="scl-group-item-title"><?php echo $item['GROUP_NAME']; ?></a>
                        <?php echo $privacyName; ?><br /> 
                        <?php echo $item['MEMBER_COUNT']; ?> гишүүд
                    </div>
                    <div class="clearfix"></div>
                </div>
                <?php
                    }
                }
                ?>
            </div>
            <a class="infinite-more-link" href="social/groups/2"></a>
            
        </main>    
        
        <?php
        echo $this->mainLeft; 
        echo $this->mainRight; 
        ?>
        
    </div>
</div>