<div class="container">
    <div class="row">
        
        <?php echo $this->mainLeft; ?>
        
        <aside class="col col-xl-9 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12 p-0">
            
            <div class="col-md-12">
                <div class="scl-group-header-cover" data-group-id="<?php echo $this->row['ID']; ?>" style="background-image: url(<?php echo $this->cover; ?>);">
                    
                    <div class="scl-group-header-name">
                        <?php echo $this->row['GROUP_NAME']; ?>
                    </div>
                    
                </div>
                
                <div class="scl-group-footer">
                    <span class="scl-group-member-count"><?php echo $this->row['MEMBER_COUNT']; ?> гишүүнтэй</span>
                    <?php
                    if ($this->isAdmin == false) {
                        
                        if ($this->isJoined) {
                            echo html_tag('a', array(
                                    'href' => 'social/exitGroup/'.$this->row['ID'], 
                                    'class' => 'btn btn-xs blue ml10 uppercase'
                                ), 
                                'Бүлгээс гарах', true
                            );
                        } else {
                            echo html_tag('a', array(
                                    'href' => 'social/joinGroup/'.$this->row['ID'], 
                                    'class' => 'btn btn-xs blue ml10 uppercase'
                                ), 
                                'Элсэх', true
                            );
                        }
                        
                    } else {    
                    ?>
                    <div class="btn-group float-right">
                        <button class="btn btn-secondary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="fa fa-ellipsis-h"></i> <i class="fa fa-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu float-right" role="menu">
                            <li>
                                <a href="javascript:;" class="scl-group-edit" data-group-id="<?php echo $this->row['ID']; ?>">Засах</a>
                            </li>
                            <?php
                            if ($this->privacyType == 'closed') {
                            ?>
                            <li>
                                <a href="javascript:;" class="scl-group-addmember" data-group-id="<?php echo $this->row['ID']; ?>">Гишүүн нэмэх</a>
                            </li>
                            <?php
                            }
                            ?>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:;" class="scl-group-delete" data-group-id="<?php echo $this->row['ID']; ?>">Устгах</a>
                            </li>
                        </ul>
                    </div>
                    <?php
                    }
                    ?>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="d-flex flex-row">
                <main class="col col-xl-8 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
                
                    <?php echo $this->createPost; ?>

                    <div class="timeline-posts">
                        <?php echo $this->posts; ?>
                    </div>
                    <a class="infinite-more-link" href="social/posts/2/<?php echo $this->groupId; ?>"></a>

                </main>    

                <?php echo $this->mainRight; ?>
            </div>
        </aside>
        
    </div>
</div>