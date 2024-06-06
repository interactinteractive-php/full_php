<?php
if ($this->metaFileRows) {
?>
<div class="row">
    <div class="col-md-12 text-right">
        <div class="btn-group btn-group-solid meta-file-view-controller">
            <button class="btn btn-sm default tooltips active" type="button" data-value="0" data-placement="top" data-original-title="Box view" data-container="body"><i class="fa fa-th-large"></i></button>
            <button class="btn btn-sm default tooltips" type="button" data-value="1" data-placement="top" data-original-title="List view" data-container="body"><i class="fa fa-reorder"></i></button>
            <button class="btn btn-sm default tooltips" type="button" data-value="3" data-placement="top" data-original-title="Columns view" data-container="body"><i class="fa fa-columns"></i></button>
        </div>
    </div>
</div> 
<div class="row" id="meta-value-file-grid">
    <div class="col-md-12">
        <ul class="grid cs-style-2 list-view0" id="meta-photo-sortable">
            <?php
            foreach ($this->metaFileRows as $file) {
                $bigIcon = "assets/core/global/img/filetype/64/".$file['FILE_EXTENSION'].".png";
                $smallIcon = "assets/core/global/img/filetype/32/".$file['FILE_EXTENSION'].".png";
            ?>
            <li class="meta-by-group">
                <figure class="directory">
                    <a href="<?php echo $file['ATTACH']; ?>" class="folder-link" title="<?php echo $file['ATTACH_NAME']; ?>">
                        <div class="img-precontainer">
                            <div class="img-container directory"><span></span>
                                <img class="directory-img" src="<?php echo $bigIcon; ?>"/>
                            </div>
                        </div>
                        <div class="img-precontainer-mini directory">
                            <div class="img-container-mini"><span></span>
                                <img class="directory-img" src="<?php echo $smallIcon; ?>"/>
                            </div>
                        </div>
                        <div class="box">
                            <h4 class="ellipsis"><?php echo $file['ATTACH_NAME']; ?></h4>
                        </div>
                    </a>
                </figure>
            </li>
            <?php
            }
            ?>
        </ul>
    </div>
</div>  
<script type="text/javascript">
$(function(){
    $(".meta-file-view-controller button").on("click", function() {
        var e = $(this);
        $(".meta-file-view-controller button").removeClass("active");
        e.addClass("active");
        typeof $("#meta-value-file-grid ul.grid")[0] != "undefined" && $("#meta-value-file-grid ul.grid")[0] && ($("#meta-value-file-grid ul.grid")[0].className = $("#meta-value-file-grid ul.grid")[0].className.replace(/\blist-view.*?\b/g, ""));
        var t = e.attr("data-value");
        $("#meta-value-file-grid ul.grid").addClass("list-view" + t);
        t >= 1 ? fix_colums(14) : ($("#meta-value-file-grid ul.grid li").css("width", 124), $("#meta-value-file-grid ul.grid figure").css("width", 122));
    }); 
});    
</script>
<?php
}
?>