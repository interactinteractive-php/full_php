<div class="row xs-form " id="candycoupon">
    <div class="w-100">
        <img src="middleware/assets/img/candy_purple.png" width="140" class="d-block text-center pb8 m-auto"  alt="candy logo">
    </div>
    <div class="col-md-12 form-horizontal">
        <div class="form-group row fom-row mt-3">
            <?php echo Form::label(array('text' =>'Купон оо уншуулна уу ', 'class' => 'col-form-label col-md-5')); ?>
            <div class="col-md-7">
                <?php 
                echo Form::text(
                    array(
                        'id' => 'candyCoupen', 
                        'class' => 'form-control', 
                        'placeholder' => $this->lang->line('Coupen Code'), 
                        'value' =>'', 
                        'style' => 'height: 26px; padding: 6px 7px; font-size: 15px!important;font-weight: bold!important;'
                    )
                ); 
                ?>
            </div>
            <div class="coupen-response"></div>
        </div>
    </div>
</div>
<style>
    .col-form-label{
        font-size: 16px;
        margin: 0;
    }
    .coupen-response p{
        text-transform: capitalize;
        font-size: 18px;
        margin-left: 20px;
    }
    .coupen-response ul li{
        padding: 2px 0;
    }
    .coupen-response ul{
        list-style-type: none;
    }
    .coupen-response{
        width: 100%;
        margin: auto;
        background: aliceblue;
        padding: 15px;
    }
</style>