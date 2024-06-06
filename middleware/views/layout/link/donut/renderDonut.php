<style>
.circliful {
    position: relative; 
}

.circle-text, .circle-info, .circle-text-half, .circle-info-half {
    width: 100%;
    position: absolute;
    text-align: center;
    display: inline-block;
}

.circle-info, .circle-info-half {
	color: #999;
}

.circliful .fa {
	margin: -10px 3px 0 3px;
	position: relative;
	bottom: 4px;
}

</style>
<?php if($this->donut != null) { ?>
    <?php $metaId = $this->donut['META_DATA_ID']; ?>
    <div id="donut<?php echo $metaId;?>" 
        data-dimension="<?php echo (isset($this->donut['DIMENSION'])) ? $this->donut['DIMENSION'] : '250'; ?>" 
        data-text="<?php if(isset($this->donut['TEXT'])){ if (strpos($this->donut['TEXT'], '%') !== FALSE){echo $this->donut['TEXT'];}else{echo $this->donut['TEXT'].'%';}}else{ echo '';}; ?>" 
        data-info="<?php echo (isset($this->donut['META_DATA_NAME'])) ? $this->donut['META_DATA_NAME'] : ''; ?>" 
        data-width="<?php echo (isset($this->donut['WIDTH'])) ? $this->donut['WIDTH'] : '30'; ?>" 
        data-fontsize="<?php echo (isset($this->donut['FONTSIZE'])) ? $this->donut['FONTSIZE'] : '38'; ?>" 
        data-percent="<?php echo (isset($this->donut['TEXT'])) ? $this->donut['TEXT'] : ''; ?>" 
        data-fill="<?php if(isset($this->donut['BGCOLOR'])){ if (strpos($this->donut['FILL'], '#') !== FALSE){echo $this->donut['FILL'];}else{echo '#'.$this->donut['FILL'];}}else{ echo '#fff';}; ?>"
        data-fgcolor="<?php if(isset($this->donut['FGCOLOR'])){ if (strpos($this->donut['FGCOLOR'], '#') !== FALSE){echo $this->donut['FGCOLOR'];}else{echo '#'.$this->donut['FGCOLOR'];}}else{ echo '#61a9dc';}; ?>" 
        data-bgcolor="<?php if(isset($this->donut['BGCOLOR'])){ if (strpos($this->donut['BGCOLOR'], '#') !== FALSE){echo $this->donut['BGCOLOR'];}else{echo '#'.$this->donut['BGCOLOR'];}}else{ echo '#eee';}; ?>" 
        data-link="" >        
    </div>
<?php }else{ ?>
    <?php $metaId = ''; ?>
<?php } ?>
<script src="assets/custom/addon/plugins/jquery-circliful/js/jquery.circliful.js" type="text/javascript"></script>
<script>
$( document ).ready(function() {
    $('#donut<?php echo $metaId;?>').circliful();    
});
</script>
