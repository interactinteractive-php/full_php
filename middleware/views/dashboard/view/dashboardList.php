<div class="row pb10 ">
    <div class="col-md-2 ml15 mb10 remove-type-<?php echo $this->metaDataId; ?>">
        <?php if ($this->isAdd) { ?>
        <div class="btn-group">
            <a href="javascript:;" class="btn green btn-sm" onclick="addMetaBySystem('', 'dialog', '<?php echo $this->metaDataId; ?>', 'dashboard');">
                <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('add_btn'); ?>
            </a>
        </div>
        <?php } ?>
    </div>
</div>
<div class="row" id="main-meta-wrap">
    <div class="col-md-12">
        <ul class="grid cs-style-2 list-view0" id="main-item-container">
            <?php
            if ($this->dashboardList) {
                foreach ($this->dashboardList as $dashboardRow) {
                    $rowDashboard = (new Mdmetadata())->renderMetaRow($dashboardRow);
            ?>
            <li class="meta <?php echo $rowDashboard['META_TYPE_CODE']; ?>" id="<?php echo $rowDashboard['META_DATA_ID']; ?>" data-folder-id="<?php echo $this->rowId; ?>">	
                <figure class="directory">
                    <a href="<?php echo $rowDashboard['linkHref']; ?>" target="<?php echo $rowDashboard['linkTarget']; ?>" class="folder-link" title="<?php echo $rowDashboard['META_DATA_NAME']; ?>" onclick="<?php echo $rowDashboard['linkOnClick']; ?>">
                        <div class="img-precontainer">
                            <div class="img-container directory"><span></span>
                              <img class="directory-img" src="<?php echo $rowDashboard['BIG_ICON']; ?>" height="90"/>
                            </div>
                        </div>
                        <div class="img-precontainer-mini directory">
                            <div class="img-container-mini">
                                <span></span>
                                <img class="directory-img" src="<?php echo $rowDashboard['SMALL_ICON']; ?>"/>
                            </div>
                        </div>
                        <div class="box">
                            <h4 class="ellipsis"><?php echo $rowDashboard['META_DATA_NAME']; ?></h4>
                        </div>
                    </a>	
                    <div class="file-code"><?php echo $rowDashboard['META_DATA_CODE']; ?></div>
                    <div class="file-date"><?php echo Date::format('Y/m/d H:i', $rowDashboard['CREATED_DATE']); ?></div>
                    <div class="file-user"><?php echo $rowDashboard['CREATED_PERSON_NAME']; ?></div>
                </figure>
            </li>
            <?php
                }
            }
            ?>
        </ul>
    </div>    
</div>   
<script type="text/javascript">
var metaIdData = [];
$(function(){
    
    <?php
    if ($this->isControl) {
    ?>
    $.contextMenu({
        selector: 'ul.grid li.meta:not(.combo, .view, .table, .metamodel, .plan, .process, .dataview, .metagroup, .tablestructure, .back, .content)',
        callback: function(key, opt) {
            if (key === 'edit') {
                var params = {isDialog: true, dataView:true};
                editFormMeta(opt.$trigger.attr("id"), opt.$trigger.attr("data-folder-id"), this, params, '<?php echo $this->metaDataId; ?>', 'dashboard');
            } else if (key === 'delete') {
                metaDataDelete(opt.$trigger.attr("id"), '<?php echo $this->metaDataId; ?>' , 'dashboard');
            } 
        },
        items: {
            "edit": {name: plang.get('edit_btn'), icon: "edit"},
            "delete": {name: "Устгах", icon: "trash"},
        }
    });
    
    <?php
    }
    ?>
    $(window).bind('resize', function() {
        fix_colums(0, $("#metaSystemView").val());
    });
});

function fix_colums(e, t) {
    var a = $("#mainRenderDiv").width() + e - 10;
    if (t > 0) {
        if (1 == t || 2 == t) $("#main-meta-wrap ul.grid li, #main-meta-wrap ul.grid figure").css("width", "100%");
        else {
            var tt = Math.floor(a / 3);
            $("#main-meta-wrap ul.grid li, #main-meta-wrap ul.grid figure").css("width", tt);
        }
    }
}
</script>