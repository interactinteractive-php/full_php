<link href="<?php echo autoVersion('middleware/assets/css/scss/sales-main.css'); ?>" rel="stylesheet"/> 
<link href="<?php echo autoVersion('middleware/assets/css/scss/boxicons.css'); ?>" rel="stylesheet"/>
<?php
if (!$this->isAjax) {
?>
<div class="col-md-12">
    <div class="card light shadow card-multi-tab">
        <div class="card-header header-elements-inline tabbable-line">
            <ul class="nav nav-tabs card-multi-tab-navtabs">
                <li data-type="layout">
                    <a href="#app_tab_mdassetsales_dashboard" class="active" data-toggle="tab"><i class="fa fa-caret-right"></i> <?php echo $this->title; ?><span><i class="fa fa-times-circle"></i></span></a>
                </li>
            </ul>
        </div>
        <div class="card-body pt0">
            <div class="tab-content card-multi-tab-content">
                <div class="tab-pane active" id="app_tab_mdassetsales_dashboard">
                    <?php
                    }
                    ?>  
                    <div class="content sales_dashboard p-0">
                        <div class="container-fluid" id="sales_content" style="background:#f0f0f7"></div>
                    </div>
                    <?php
                    if (!$this->isAjax) {
                    ?>                    
                </div>
            </div>
        </div>
    </div>    
</div>
<?php
}
?>


<!-- <script type="text/javascript" src="assets/custom/js/core.js"></script> -->
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/amcharts.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/serial.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/pie.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/ammap_amcharts_extension.v1.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/mongoliaLow.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amchart4/core.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amchart4/charts.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amchart4/themes/animated.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.3/Chart.bundle.js"></script> -->

<!-- amCharts javascript code -->
<script type="text/javascript">
    var $mainSelector = $('#sales_content').empty();                                                          
    var $theme = "<?php echo $this->theme; ?>";
   
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'mdasset/sales_dashboardtheme/'+ $theme,
        data: {path:'isDefault'},
        beforeSend: function() {
            
            Core.blockUI({
                boxed: true,
                message: 'Loading ... '
            });
        },
        success: function(data) {
          
            if (typeof data.status !== 'undefined') {
                new PNotify({
                    title: data.status,
                    text: data.text,
                    type: data.status,
                    sticker: false
                });  
            } else {
                $mainSelector.append(data.Html);
            }
            Core.unblockUI();
        }
    });

</script> 