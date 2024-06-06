<div class="row">    
    <div class="flowchart-savebtn-row">
        <button type="button" 
        class="btn btn-sm btn-circle btn-success" 
        style="background-color: #FFF;border-color: #1bbc9b;color: #1bbc9b;border-radius: 100px !important;position: absolute;right: 25px;top: 0px;" 
        onclick="saveBlockExpression(this)">
            <i class="icon-checkmark-circle2"></i> Хадгалах
        </button>
    </div>
    <div class="col-md-12 paper-container-container">
        <?php if (!isset($this->getRow2)) { ?>
        <div id="stencil-container"></div>
        <?php } else { ?>
        <div id="stencil-container" class="d-none"></div>
        <?php } ?>
        <div id="paper-container"></div>        
    </div>
    <input type="hidden" value="<?php echo $this->linkRecordId; ?>" data-kpidatamart-id="3"/>
</div>   

<style type="text/css">
  #stencil-container {
    position: absolute;
    left: 0;
    top: 0;
    width: 80px;
    bottom: 0;
  }

  #paper-container {
    position: absolute;
    right: 0;
    top: 0;
    left: <?php echo !isset($this->getRow2) ? 80 : 0; ?>px;
    bottom: 0;
  }

  #logo {
    position: absolute;
    bottom: 20px;
    right: 0;
  }    
  [data-action=remove]:after {
      content: '';
  }
</style>

<script type="text/javascript">
//    var dynamicHeight = $(window).height() - $("#paper-container").offset().top - 20;
    var dynamicHeight = $(window).height() - <?php echo !isset($this->getRow2) ? 300 : 100; ?>;
    $("#paper-container").css('height', dynamicHeight);
    $(".paper-container-container").css('height', dynamicHeight);

    if (typeof window.joint === 'undefined') {
    }

//    $.cachedScript("http://localhost:8080/bundle.js").done(function() {
    $.cachedScript('<?php echo autoVersion('assets/rappidjs/telco/bundle.js'); ?>').done(function() {
       loadVdevice('<?php echo URL.$this->getRow['PICTURE_MAIN'] ?>', '<?php echo $this->getRow['GRAPH_JSON'] ?>', '<?php echo isset($this->getRow2) ? $this->getRow2['GRAPH_JSON'] : '' ?>');
    });

    $(function() {    
    });    
</script>