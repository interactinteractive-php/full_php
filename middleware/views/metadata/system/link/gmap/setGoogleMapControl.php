<?php 
$this->colorPaletteActive = 'a52a2a';
$colorPalatte = array('f0f8ff', 'faebd7', '00ffff', '7fffd4', 'f0ffff', 'f5f5dc', 'ffe4c4', '000000', 'ffebcd', '0000ff', '8a2be2', 'a52a2a', 'deb887', '5f9ea0', '7fff00', 'd2691e', 'ff7f50', '6495ed', 'fff8dc', 'dc143c', '00ffff', '00008b', '008b8b', 'b8860b', 'a9a9a9', '006400', 'a9a9a9', 'bdb76b', '8b008b', '556b2f', 'ff8c00', '9932cc', '8b0000', 'e9967a', '8fbc8f', '483d8b', '2f4f4f', '2f4f4f', '00ced1', '9400d3', 'ff1493', '00bfff', '696969', '1e90ff', 'b22222', 'fffaf0', '228b22', 'ff00ff', 'dcdcdc', 'f8f8ff', 'ffd700', 'daa520', '808080', '008000', 'adff2f', '808080', 'f0fff0');
?>

<div class="md-map-container" id="bp_map_control_<?php echo $this->metaDataId; ?>">
    <div class="md-map-filter-panel left">
        <div class="md-map-selector-toggle">
            <i class="fa fa-angle-double-right"></i>
        </div>
        <div class="md-map-filter-container">
            <div id="marker-position"></div>
            <ul class="colorPalette">
                <?php 
                foreach ($colorPalatte as $row) {
                    echo '<li style="background-color:#'.$row.'" data-color="'.$row.'" class="'.($row == $this->colorPaletteActive ? 'active' : '').'" onclick="googleMapChangeSelectColor(this);"></li>';
                }
                ?>
            </ul>
        </div>        
    </div>
    <div id="md_set_map_canvas" style="width:100%; height:100%; overflow:auto; position: relative; margin: 0; padding: 0;"></div>
</div>

<script type="text/javascript">
    
    $(function(){
        mapToggleBtn();
    });
    
    function mapToggleBtn() {
        var $mapFilter = $("div.md-map-container div.md-map-filter-panel"), 
            $leftToggleBtn = $("div.md-map-filter-panel.left .md-map-selector-toggle i.fa"), 
            $rightToggleBtn = $("div.md-map-filter-panel.right .md-map-selector-toggle i.fa");

        $leftToggleBtn.removeClass("fa-angle-double-left").addClass("fa-angle-double-right");
        $rightToggleBtn.removeClass("fa-angle-double-right").addClass("fa-angle-double-left");

        $("div.md-map-selector-toggle").on('click', function () {
            var $mapToggleBtn = $(this);
            $mapFilter.toggleClass("open");
            if ($mapToggleBtn.toggleClass("open").hasClass("open")) {
                $leftToggleBtn.removeClass("fa-angle-double-right").addClass("fa-angle-double-left");
                $rightToggleBtn.removeClass("fa-angle-double-left").addClass("fa-angle-double-right");
            } else {
                $leftToggleBtn.removeClass("fa-angle-double-left").addClass("fa-angle-double-right");
                $rightToggleBtn.removeClass("fa-angle-double-right").addClass("fa-angle-double-left");
            }
        });
    }
</script>
