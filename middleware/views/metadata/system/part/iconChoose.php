<div class="row">
    <div class="col-md-3">
        <div class="list-group list-group-bordered metaIconTypeList">
            <?php
            if ($this->iconType) {
                foreach ($this->iconType as $iconType) {
            ?>
            <a href="javascript:;" class="list-group-item list-group-item-action" id="<?php echo $iconType['ICON_TYPE_ID']; ?>" data-default="<?php echo $iconType['IS_DEFAULT']; ?>">
                <?php echo $iconType['ICON_TYPE_NAME']; ?> 
                <span class="badge badge-pill badge-warning ml-auto"><?php echo $iconType['COUNT_ICON']; ?></span>
            </a>
            <?php
                }
            }
            ?>
        </div>
    </div>
    <div class="col-md-9">
        <div class="scroller" id="iconRenderDiv" style="height: 410px;" data-handle-color="#637283" data-always-visible="1" data-rail-visible="1" data-handle-size="8px"></div>
    </div>
</div>

<script type="text/javascript">
$(function(){
    $(".metaIconTypeList").on("click", "a.list-group-item-action", function(){
        var _this = $(this);
        var iconTypeId = _this.attr("id");
        $(".metaIconTypeList a.list-group-item-action").removeClass("active");
        _this.addClass("active");
        renderMetaIcon(iconTypeId);
    });
    
    $(".metaIconTypeList a.list-group-item-action").each(function(){
        var _this = $(this);
        var isDefault = _this.attr("data-default");
        if (isDefault === "1") {
            $(".metaIconTypeList a.list-group-item-action").removeClass("active");
            _this.addClass("active");
            renderMetaIcon(_this.attr("id"));
            return;
        }
    });
});    

function renderMetaIcon(iconTypeId){
    $.ajax({
        type: 'post',
        url: 'mdmetadata/iconList',
        data: {iconTypeId: iconTypeId},
        beforeSend:function(){
            Core.blockUI({target: '#iconRenderDiv', animate: true});
        },
        success:function(data){
            $("#iconRenderDiv").empty().html(data);  
            Core.unblockUI('#iconRenderDiv');
        },
        error:function(){
            alert("Error");
        }
    });
}
</script>