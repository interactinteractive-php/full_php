<style type="text/css">
.mv_tiny_card3_with_list_widget {
    display: inline-block;
    width: 90px;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    -ms-border-radius: 4px;
    -o-border-radius: 4px;
    border-radius: 4px;
}
.mv_tiny_card3_with_list_widget:hover {
    box-shadow: none;
}
.mv_tiny_card3_with_list_widget:hover .no-dataview {
    display: block !important;
}
.mv_tiny_card3_with_list_widget .card {
    margin-bottom: 0;
}
.mv_tiny_card3_with_list_widget .card-body .card-img {
    border-radius: 0;
    border-bottom: 1px #eee solid;
}
.mv_tiny_card3_with_list_widget h5 {
    display: block;
    font-size: 15px;
    color: #fff;
    line-height: 20px;
    overflow: hidden;
}
.mv_tiny_card3_with_list_widget_main .mv_tiny_card3_with_list_widget.active img {
    border-color: #ca3361 !important;
}
</style>

<div class="mv_tiny_card3_with_list_widget_main">
    <?php
    $c = ['rgb(238, 103, 99)','rgb(54, 185, 233)','rgb(32, 175, 162)','rgb(130, 96, 229)','rgb(255, 198, 0)'];
    foreach ($this->recordList as $recordRow) {
        $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
    ?>
        <a href="javascript:;" style="width: 211px;margin-right: 12px; margin-bottom: 12px;" onclick="checkList5SelectRowDv<?php echo $this->dataViewId; ?>(this);" class="mv_tiny_card3_with_list_widget no-dataview" data-row-data="<?php echo $rowJson; ?>">
            <div class="card" style="border-width: 1px;box-shadow: none;padding: 0;">
                <div class="card-body" style="padding:0">
                    <div class="card-img-actions mb-2 p-3" style="height: 230px;background: <?php echo $c[array_rand($c)] ?>">
                        <h5 style="font-weight: bold;" class="d-flex align-items-end mb0">
                            <div style="height: 16px;"><?php echo issetParam($recordRow[$this->name1]); ?></div>
                        </h5>
                        <h5 style="color: #dcdcdc;font-size: 13px;">
                            <?php echo issetParam($recordRow[$this->name5]); ?> 
                        </h5>                        
                        <div style="height: 80px;text-align: center;">
                            <img class="directory-img mt8" style="width: 50px;" src="https://dev.veritech.mn/<?php echo issetParam($recordRow[$this->name4]); ?>"/>
                        </div>                        
                        <div style="width: 100%;height: 1px;background: #ccc"></div>
                        <h5 style="font-weight: bold;" class="d-flex align-items-end mb0 mt15">
                            <div style="height: 40px;"><?php echo issetParam($recordRow[$this->name2]); ?></div>
                        </h5>                        
                        <h5 style="color: #dcdcdc;font-size: 13px;height: 20px;">
                            <?php echo issetParam($recordRow[$this->name6]); ?> 
                        </h5>
                    </div>
                    <div class="p-3" style="">
                        <div class=""><span style="color:#585858;font-size: 13px;">Хүлээн авсан:</span><span style="color:#A0A0A0;font-size: 13px;margin-left: 5px;"><?php echo Date::formatter(issetParam($recordRow[$this->name3]), 'Y.m.d') ?></span></div>
                    </div>
                </div>
            </div>
        </a>
    <?php
    } 
    ?>
</div>

<script type="text/javascript">
$('.mv_tiny_card3_with_list_widget').click(function() {
    var $this = $(this);
    if ($this.hasClass('active')) {
        $this.removeClass('active');
        return;
    }
    $this.closest('.mv_tiny_card3_with_list_widget_main').find('.active').removeClass('active');
    $this.addClass('active');
}); 
function mvWidgetFileViewCreateCallback() {
    $('div[data-menu-id="164723019841110"]').remove();
    $('a[data-menu-id="164723019841110"]').trigger('click');
}    
function mvWidgetFileViewDeleteCallback(elem) {
    mvWidgetFileViewCreateCallback();
}
</script>