
<style type="text/css">
    .chooseSvgIcon {
        border: 1px solid #ddd;
        padding: 10px;
    }
    .svgBox {
        margin: 5px;
        background-color: #ddd;
        border: 1px solid #ddd;
        width: 40px;
        height: 30px;
        text-align: center;
        vertical-align: middle;
        padding: 3px;
        border-radius: 4px;
        display: inline-block;
        border: 1px solid #ddd;
    }
    .svgBox.active {
        border: 1px solid #000;
    }
    .svgIcon {
        width: 20px;
        height: 20px;
        text-align: center;
        margin: auto;
    }
    .btn {
        padding: 6px 14px;
    }
</style>
<div id="chooseMarkerIconWindow">
    <div class="form-group row fom-row mb20">
        <label class="col-md-3 col-form-label">Өнгө: </label>
        <div class="col-md-6">
            <div class="input-group color colorpicker-default" data-color="#<?php echo $this->displayColor; ?>" data-color-format="rgba" style="max-width: 130px;">
                <input type="text" class="form-control" name="displayColor" value="#<?php echo trim($this->displayColor, '#'); ?>" readonly>
                <span class="input-group-btn">
                    <button class="btn default colorpicker-input-addon px-1" type="button"><i style="background-color: #<?php echo $this->displayColor; ?>;"></i>&nbsp;</button>
                </span>
            </div>
        </div>
    </div>
    <div class="clearfix w-100"></div>
    <div class="form-group row fom-row mt20">
        <label class="col-md-3 col-form-label"><?php echo $this->lang->line('META_00197'); ?> </label>
        <div class="col-md-9">
            <div class="chooseSvgIcon border-radius" style=" max-height: 100px;">
                <?php 
                foreach (Mdcommon::svgIconList() as $k => $row) {
                    echo '<div class="svgBox ' . (Input::post('iconName') == $row ? 'active' : '') . '" onclick="selectedIcon(this);">' . Mdcommon::svgIconByColor('282828', $row, false) . '</div>';
                }
                ?>

            </div>
            <input type="hidden" class="form-control" name="iconName" value="<?php echo $this->iconName; ?>">
        </div>
    </div>
</div>

<script type="text/javascript">
    function selectedIcon(elem){
        var _this = $(elem);
        $('.svgBox').removeClass('active');
        _this.addClass('active');
        $('input[name="iconName"]').val(_this.find('.svgIcon').attr('id'));
    }
</script>