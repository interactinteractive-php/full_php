<div class="<?php echo (isset($this->ajax) && $this->ajax) ? 'socialIntranet'. $this->uniqId .' pl15 pr15 pt15' : 'container'; ?>">
    <div class="row">
        <main class="col <?php echo (isset($this->ajax) && $this->ajax) ? 'col-xl-9' : 'col-xl-6'; ?>  order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
            <div class="row socialPin<?php echo $this->uniqId ?>">
                <div class="col-3">
                    <div class="ui-block">			
                        <div class="friend-item">
                            <div class="friend-header-thumb">
                                <img src="https://external-preview.redd.it/NDWiM1Pz_Up4zEU-cmXjSXyKqVrp18Vn2sBhJvfHjTk.jpg?auto=webp&s=bc275f14089c6de38fb328c1714f0ae7af4e08a5">
                            </div>
                            <div class="friend-item-content">
                                TEST TEST TEST
                            </div>
                        </div>			
                    </div>
                </div>
                <div class="col-3">
                    <div class="ui-block">			
                        <div class="friend-item">
                            <div class="friend-header-thumb">
                                <img src="https://external-preview.redd.it/NDWiM1Pz_Up4zEU-cmXjSXyKqVrp18Vn2sBhJvfHjTk.jpg?auto=webp&s=bc275f14089c6de38fb328c1714f0ae7af4e08a5">
                            </div>
                        </div>			
                    </div>
                </div>
                <div class="col-3">
                    <div class="ui-block">			
                        <div class="friend-item">
                            <div class="friend-header-thumb">
                                <img src="https://external-preview.redd.it/NDWiM1Pz_Up4zEU-cmXjSXyKqVrp18Vn2sBhJvfHjTk.jpg?auto=webp&s=bc275f14089c6de38fb328c1714f0ae7af4e08a5">
                            </div>
                        </div>			
                    </div>
                </div>
                <div class="col-3">
                    <div class="ui-block">			
                        <div class="friend-item">
                            <div class="friend-header-thumb">
                                <img src="https://external-preview.redd.it/NDWiM1Pz_Up4zEU-cmXjSXyKqVrp18Vn2sBhJvfHjTk.jpg?auto=webp&s=bc275f14089c6de38fb328c1714f0ae7af4e08a5">
                            </div>
                        </div>			
                    </div>
                </div>
            </div>
            <?php echo $this->createPost; ?>

            <div class="timeline-posts">
                <?php echo $this->posts; ?>
            </div>
            <a class="infinite-more-link" href="social/posts/2"></a>

        </main>    

        <?php
            echo $this->mainLeft;
            echo $this->mainRight;
        ?>

    </div>
</div>