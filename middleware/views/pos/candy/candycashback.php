<div class="row xs-form ">
    <div class="container bootstrap snippet" id="candycoupon">
    
        <div class="jumbotron list-content ph0">
            <ul class="list-group">
                <li href="#" class="list-group-item text-left title">
                    <!-- <a class="icon-phone-plus2" href="javascript:;" onclick="cashbackAction(this,'','add')" title="Дугаар нэмэх">Дугаар нэмэх</a> -->
                    <div class="W-100">
                        <img src="middleware/assets/img/candy_purple.png" width="180"class="" alt="candy logo">
                    </div>
                    <div class="break"></div>
                </li>
                <li href="#" class="list-group-item text-left pt20">
                    <span class="ci-icon-28 mr15" style="font-size: 22px;"></span>
                    <p class="candyPhone"> <?php echo $this->phone; ?> </p>
                    <span class="cashinfodescription ml15"  style="color:#792c83"></span>
                    <label class="pull-right">
                        <?php if($this->phone){?>
                            <a class="btn  btn-xs " style="background:#792c83; color:#fff" href="javascript:;" title="Дугаар засах"  onclick="cashbackAction(this,'<?php echo $this->phone; ?>','edit')">Засах</a>
                        <?php } else{?>
                            <a class="btn   btn-xs " style="background:#792c83; color:#fff"  href="javascript:;" title="Дугаар Нэмэх"  onclick="cashbackAction(this,'<?php echo $this->phone; ?>','edit')">Нэмэх</a>
                        <?php }?>   
                        <!-- <a class="btn btn-danger  btn-xs" href="javascript:;" title="Дугаар устгах" onclick="cashbackAction(this,'<?php echo $this->phone; ?>','delete')" >Устгах</a> -->
                    </label>
                    <div class="break"></div>
                </li>
            </ul>
        </div>
    </div>
</div>
<style>
    .col-form-label {
        font-size: 16px;
        margin: 0;
    }
    #candycoupon .list-group-item {
        display: inline-block;
    }

    #candycoupon .list-group .candyPhone{
        display: inline-block;
        font-size: 20px;
        padding: 5px 0;
        margin: 0;
    }
    #candycoupon .list-group{
        padding: 0;
    }
    .list-content .list-group .title a::before{
        background: #fff;
        color: #792c83;
        margin-right: 5px;
        padding: 3px;
        border-radius: 4px;
    }
    .list-content .list-group .title a{
        position: absolute;
        bottom: 0;
        top: 35px;
        color: #fff;
        font-size: 20px;
    }
    .list-content .list-group .title {
        background: #792c83;
        font-weight: bold;
        color: #FFFFFF;
        display: inline-block;
        position: relative;
    }

    .list-group-item img {
        float: left;
    }

    .jumbotron .btn {
        padding: 3px 5px !important;
        font-size: 12px !important;
    }

    .prj-name {
        color: #5bc0de;
    }

    .break {
        width: 100%;
        margin: 20px;
    }

    .name {
        color: #5bc0de;
    }
</style>