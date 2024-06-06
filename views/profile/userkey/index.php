<div class="content">
    <div class="container contaner-xxsmall">
        <div class="row d-flex justify-content-center">
            <?php
            if ($this->userkeys) {
                foreach ($this->userkeys as $row) {
                    $id = Compression::gzdeflate($row['id']);
                    $id = Str::urlCharReplace($id);

                    if (isset($row['objectphoto']) && file_exists($row['objectphoto'])) {
                        $logo = '<img class="mr-2" src="api/image_thumbnail?width=84&src='.$row['objectphoto'].'">';
                    } else {
                        $logo = 'No logo';
                    }
            ?>
                <div class="col-sm-12 login-type listBox">
                    <a href="login/connectClient/<?php echo $id.'/'.$this->systemUserId; ?>" class="uk-link">
                        <div class="card card-body p15">
                            <div class="media">
                                <div class="mr-2 org_logo align-self-center">
                                    <?php echo $logo; ?>
                                </div>
                                <div class="media-body align-self-center">
                                    <h6 class="font-size-sm mb-0 text-uppercase text-primary"><?php echo $row['name']; ?></h6>
                                </div>
                                <div class="text-right align-self-center"><span class="text-uppercase font-size-sm text-muted"><?php echo $row['code']; ?></span></div>
                            </div>    
                        </div>
                    </a>
                </div>
            <?php }} ?>
        </div>
        <div>
            <p class="alert-heading font-weight-semibold mt-2 mb-0 text-center">
                <?php echo Lang::lineDefault('loginUserKeyChooseMessage', 'Та дээрх салбар нэгжээс сонголт хийж цааш нэвтэрнэ үү.'); ?>
            </p>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".login-type a").click(function() {
            $(this).parent().addClass('selected').siblings().removeClass('selected');
        });
    });
</script>