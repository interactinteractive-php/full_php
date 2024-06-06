<div class="bp-paperclip1-bg">
    <div class="row w-100 m-0">
        <div class="col d-flex justify-content-center ml-5">
            <div class="bp-clamp1"></div>
        </div>
        <div class="col d-flex justify-content-center mr-5">
            <div class="bp-clamp1"></div>
        </div>
    </div>
    <div class="bp-render1">
        <?php echo $this->bpRenderHtml; ?>
    </div>
</div>

<style>
    .bp-paperclip1-bg {
        background-image: url("middleware/assets/img/process/background/paperclip.png"); 
        background-repeat: repeat; 
        background-position: top center;
        background-color: #eccead;
        margin: -10px -15px;
    }
    .bp-paperclip1-bg .bp-clamp1 {
        content: url("middleware/assets/img/process/background/clamp.png");
    }
    .bp-paperclip1-bg .bp-render1 {
        width: 1040px;
        margin-top: -15px;
        margin-left: auto;
        margin-right: auto;
        background: #FFF;
        padding: 30px 20px;
        box-shadow: 0 5px 5px 0 rgba(0,0,0,.5);
    }
</style>