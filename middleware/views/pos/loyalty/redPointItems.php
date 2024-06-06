<div class="row mb10">
    <div class="col-md-12" style="border-bottom: 1px #ddd solid; padding-bottom: 5px">
        Сонгосон барааны тоо: <span id="redpoint-selected-count" style="font-size: 20px; padding-right: 20px;">0</span> 
        Сонгосон барааны нийт оноо: <span id="redpoint-selected-point" style="font-size: 20px;">0</span>
    </div>
</div>
<div class="row" style="max-height: 562px; overflow: auto">
    <?php
    if ($this->items) {
        foreach ($this->items as $item) {
    ?>
    <div class="col-md-3">
        <div class="pos-redpoint-item" data-item-code="<?php echo $item['offer_code']; ?>" data-point="<?php echo $item['price']; ?>">
            <div class="pos-redpoint-item-frame">
                <img src="<?php echo $item['image_url']; ?>" class="pos-redpoint-item-img">
            </div>
            <div class="pos-redpoint-item-info">
                <div class="pos-redpoint-item-name">
                    <?php echo $item['offer_name']; ?>
                </div>  
                <div class="pos-redpoint-item-point">
                    <?php echo $item['price']; ?> <img src="assets/custom/css/pos/redpoint.png" style="height: 15px; margin-top: -3px;">
                    <button type="button" class="btn btn-icon-only btn-circle default float-right" style="margin-top: -10px;"><i class="fa fa-check"></i></button>
                </div>  
            </div>
        </div>
    </div>
    <?php
        }
    }
    ?>
    <div class="col-md-3">
        <div class="pos-redpoint-item" data-item-code="1212" data-point="100">
            <div class="pos-redpoint-item-frame">
                <img src="https://www.redpoint.mn/files/product/1507774771214.jpg" class="pos-redpoint-item-img">
            </div>
            <div class="pos-redpoint-item-info">
                <div class="pos-redpoint-item-name">
                    Test offer                </div>  
                <div class="pos-redpoint-item-point">
                    100 <img src="assets/custom/css/pos/redpoint.png" style="height: 15px; margin-top: -3px;">
                    <button type="button" class="btn btn-icon-only btn-circle float-right default" style="margin-top: -10px;"><i class="fa fa-check"></i></button>
                </div>  
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="pos-redpoint-item" data-item-code="12121" data-point="100">
            <div class="pos-redpoint-item-frame">
                <img src="https://www.redpoint.mn/files/product/1507774771214.jpg" class="pos-redpoint-item-img">
            </div>
            <div class="pos-redpoint-item-info">
                <div class="pos-redpoint-item-name">
                    Test offer                </div>  
                <div class="pos-redpoint-item-point">
                    100 <img src="assets/custom/css/pos/redpoint.png" style="height: 15px; margin-top: -3px;">
                    <button type="button" class="btn btn-icon-only btn-circle float-right default" style="margin-top: -10px;"><i class="fa fa-check"></i></button>
                </div>  
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="pos-redpoint-item" data-item-code="12122" data-point="100">
            <div class="pos-redpoint-item-frame">
                <img src="https://www.redpoint.mn/files/product/1507774771214.jpg" class="pos-redpoint-item-img">
            </div>
            <div class="pos-redpoint-item-info">
                <div class="pos-redpoint-item-name">
                    Test offer                </div>  
                <div class="pos-redpoint-item-point">
                    100 <img src="assets/custom/css/pos/redpoint.png" style="height: 15px; margin-top: -3px;">
                    <button type="button" class="btn btn-icon-only btn-circle float-right default" style="margin-top: -10px;"><i class="fa fa-check"></i></button>
                </div>  
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="pos-redpoint-item" data-item-code="P10001" data-point="100">
            <div class="pos-redpoint-item-frame">
                <img src="https://www.redpoint.mn/files/product/1507774771214.jpg" class="pos-redpoint-item-img">
            </div>
            <div class="pos-redpoint-item-info">
                <div class="pos-redpoint-item-name">
                    Test offer                </div>  
                <div class="pos-redpoint-item-point">
                    100 <img src="assets/custom/css/pos/redpoint.png" style="height: 15px; margin-top: -3px;">
                    <button type="button" class="btn btn-icon-only btn-circle float-right default" style="margin-top: -10px;"><i class="fa fa-check"></i></button>
                </div>  
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="pos-redpoint-item" data-item-code="P10001" data-point="100">
            <div class="pos-redpoint-item-frame">
                <img src="https://www.redpoint.mn/files/product/1507774771214.jpg" class="pos-redpoint-item-img">
            </div>
            <div class="pos-redpoint-item-info">
                <div class="pos-redpoint-item-name">
                    Test offer                </div>  
                <div class="pos-redpoint-item-point">
                    100 <img src="assets/custom/css/pos/redpoint.png" style="height: 15px; margin-top: -3px;">
                    <button type="button" class="btn btn-icon-only btn-circle float-right default" style="margin-top: -10px;"><i class="fa fa-check"></i></button>
                </div>  
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="pos-redpoint-item" data-item-code="P10001" data-point="100">
            <div class="pos-redpoint-item-frame">
                <img src="https://www.redpoint.mn/files/product/1507774771214.jpg" class="pos-redpoint-item-img">
            </div>
            <div class="pos-redpoint-item-info">
                <div class="pos-redpoint-item-name">
                    Test offer                </div>  
                <div class="pos-redpoint-item-point">
                    100 <img src="assets/custom/css/pos/redpoint.png" style="height: 15px; margin-top: -3px;">
                    <button type="button" class="btn btn-icon-only btn-circle float-right default" style="margin-top: -10px;"><i class="fa fa-check"></i></button>
                </div>  
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="pos-redpoint-item" data-item-code="P10001" data-point="100">
            <div class="pos-redpoint-item-frame">
                <img src="https://www.redpoint.mn/files/product/1507774771214.jpg" class="pos-redpoint-item-img">
            </div>
            <div class="pos-redpoint-item-info">
                <div class="pos-redpoint-item-name">
                    Test offer                </div>  
                <div class="pos-redpoint-item-point">
                    100 <img src="assets/custom/css/pos/redpoint.png" style="height: 15px; margin-top: -3px;">
                    <button type="button" class="btn btn-icon-only btn-circle float-right default" style="margin-top: -10px;"><i class="fa fa-check"></i></button>
                </div>  
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="pos-redpoint-item" data-item-code="P10001" data-point="100">
            <div class="pos-redpoint-item-frame">
                <img src="https://www.redpoint.mn/files/product/1507774771214.jpg" class="pos-redpoint-item-img">
            </div>
            <div class="pos-redpoint-item-info">
                <div class="pos-redpoint-item-name">
                    Test offer                </div>  
                <div class="pos-redpoint-item-point">
                    100 <img src="assets/custom/css/pos/redpoint.png" style="height: 15px; margin-top: -3px;">
                    <button type="button" class="btn btn-icon-only btn-circle float-right default" style="margin-top: -10px;"><i class="fa fa-check"></i></button>
                </div>  
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function(){
    $('.pos-redpoint-item').on('click', function(){
        var $this = $(this), redPointSum = 0;
        
        if ($this.hasClass('pos-redpoint-item-selected')) {
            $this.find('input').remove();
            $this.removeClass('pos-redpoint-item-selected');
            $this.find('.btn-icon-only').removeClass('green').addClass('default');
        } else {
            $this.addClass('pos-redpoint-item-selected');
            $this.find('.btn-icon-only').removeClass('default').addClass('green');
            $this.append('<input type="hidden" name="redPointItemCode[]" value="'+$this.attr('data-item-code')+'">');
        }
        
        $('#redpoint-selected-count').text($('.pos-redpoint-item-selected').length);
        
        $('.pos-redpoint-item-selected').each(function(index, elem) {
            redPointSum += parseInt($(elem).attr('data-point'));
        });
        
        $('#redpoint-selected-point').text(redPointSum);
    });
});    
</script>
    