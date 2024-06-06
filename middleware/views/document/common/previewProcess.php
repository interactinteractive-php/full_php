<?php if (!$this->isAjax) {
?>
<div class="col-md-12">
    <div class="card light shadow">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title"><i class="fa fa-clock-o"></i> <?php echo $this->title; ?></div>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
<?php } ?>
            <div class="row">
                <div class="col-md-12">
                    <?php echo $this->converthtml ?>
                </div>        
            </div>        
<?php if (!$this->isAjax) { ?>      
        </div>
    </div>
</div>
<?php
}
?>