<div class="row kpi-layout-100 position-relative" id="<?php echo $this->uniqId; ?>">
    <div class="col-md-12">
        <iframe id="frame<?php echo $this->uniqId; ?>" src="https://expo.veritech.mn/darkhan" frameborder="0" allowfullscreen style="width: 100%;height: 100vh;position: absolute;z-index: 1;;"></iframe>
    </div>
    <div class="position-absolute w-100">
        <div class="col-md-12">
            <div class="row px-1 ly-left-side">
                <div class="col-md-8 col-form bl-section py-0 w-100" data-kl-col="1">
                    <div class="card card-hover card-profile-sidebar card-weather left pt-3">
                        <div class="card-header bg-transparent">
                            <h6 class="card-title mg-b-0">Цаг агаар</h6>
                            <nav class="nav nav-card-icon">
                                
                                <a href="javascript:void(0);"><i data-feather="refresh-ccw"></i></a>
                            </nav>
                        </div>
                        <div class="p-3 d-flex row">
                            <?php if ($this->weatherData) { ?>
                                <div class="d-grid align-items-center justify-content-between col-md-5">
                                    <div class="col-md-12">
                                        <h2 class="text-white mb-0 "><?php echo issetDefaultVal($this->weatherData[0]['temperatureDay'], '0') ?>°C</h2>
                                    </div>
                                    <div class="col-md-12">
                                        <img src="<?php echo issetParam($this->weatherData[0]['filepath']) ? $this->weatherData[0]['filepath'] : 'assets/custom/img/app_dashboard/weather.png'; ?>" class="img-fluid" style="width: 60px;" onerror="onUserImageError(this);">
                                    </div>
                                </div>
                                <div class="profile-info mt-3 col-md-7">
                                    <div class="row">
                                        <?php foreach ($this->weatherData as $key => $row) {
                                            if ($key != 0 && $key < 4) { ?>
                                                <div class="col-4">
                                                    <h5 class="text-white font-size-xs mb-0"><?php echo issetParam($row['temperatureDay']) ? $row['temperatureDay'] : ''  ?>°C</h5>
                                                    <p class="text-white font-size-xs mb-0"><?php echo issetParam($row['date']) ? Str::utf8_substr(Date::format('l', $row['date'], true), 0, 2) : ''  ?></p>
                                                    <img src="<?php echo issetParam($row['filepath']) ? $row['filepath'] : 'assets/custom/img/app_dashboard/weather.png'; ?>" class="img-fluid" style="width: 60px;" onerror="onUserImageError(this);">
                                                </div>        
                                            <?php }
                                        } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-form bl-section py-0 w-100" data-kl-col="1">
                    <div class="card p-3 kl-1-card left">
                        <!--sectionCode1-title-->
                        <div class="card-body" data-section-code="1" style="height: 200px">
                            <!--sectionCode1-->
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-form bl-section py-0 w-100" data-kl-col="2">
                    <div class="card p-3 kl-sectioncode2-card left">
                        <!--sectionCode2-title-->
                        <div class="card-body" data-section-code="2" style="height: 200px">
                            <!--sectionCode2-->
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-form bl-section py-0 w-100" data-kl-col="3">
                    <div class="card p-3 kl-sectioncode3-card left">
                        <!--sectionCode3-title-->
                        <div class="card-body" data-section-code="3" style="height: 200px">
                            <!--sectionCode3-->
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-form bl-section py-0 w-100" data-kl-col="4">
                    <div class="card p-3 kl-sectioncode4-card left">
                        <!--sectionCode4-title-->
                        <div class="card-body" data-section-code="4" style="height: 200px">
                            <!--sectionCode4-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="row px-1 ly-right-side">
                <div class="col-md-12 p-0 col-form bl-section py-0 w-100" data-kl-col="5">
                    <div class="card p-3 kl-sectioncode5-card right">
                        <!--sectionCode5-title-->
                        <div class="card-body" data-section-code="5" style="height: 200px">
                            <!--sectionCode5-->
                        </div>
                    </div>
                </div>
                <div class="col-md-12 p-0 col-form bl-section py-0 w-100" data-kl-col="6">
                    <div class="card p-3 kl-sectioncode6-card right">
                        <!--sectionCode6-title-->
                        <div class="card-body" data-section-code="6" style="height: 200px">
                            <!--sectionCode6-->
                        </div>
                    </div>
                </div>
                <div class="col-md-12 p-0 col-form bl-section py-0 w-100" data-kl-col="7">
                    <div class="card p-3 kl-sectioncode7-card right">
                        <!--sectionCode7-title-->
                        <div class="card-body" data-section-code="7" style="height: 200px">
                            <!--sectionCode7-->
                        </div>
                    </div>
                </div>
                <div class="col-md-12 p-0 col-form bl-section py-0 w-100" data-kl-col="8">
                    <div class="card p-3 kl-sectioncode8-card right">
                        <!--sectionCode8-title-->
                        <div class="card-body" data-section-code="8" style="height: 200px">
                            <!--sectionCode8-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    
    /* (function(window, document, $) {
        var hiddenContent = false;
        
        refreshIntervalId = setInterval(() => {
            console.log(hiddenContent, 'frame<?php echo $this->uniqId; ?>');
            if (hiddenContent) {
                clearInterval(refreshIntervalId);
            } else {
                if ($('.esri-ui-top-right').legth > 0) {
                    hiddenContent = true;
                    $('.esri-ui-top-right').hide();
                    $('#frame<?php echo $this->uniqId; ?>').contents().find('.esri-ui-top-right').hide();
                }
            }
        }, 1000);
})(window, document, jQuery); */
    
</script>
<style type="text/css">

    #kl-layout-<?php echo $this->uniqId; ?> .d-grid{ 
        display: grid;
    }

    #kl-layout-<?php echo $this->uniqId; ?> {
        background: rgb(234 237 239);
    }

    #kl-layout-<?php echo $this->uniqId; ?> .ly-left-side {
        position: absolute;
        display: grid;
        left: 0;
        z-index: 2;
    }

    #kl-layout-<?php echo $this->uniqId; ?> .ly-right-side {
        top: 200px;
        position: absolute;
        display: grid;
        right: 0;
        z-index: 2;
    }

    #kl-layout-<?php echo $this->uniqId; ?> .card > .card-header > .card-title {
        color: #FFF !important;
    }

    #kl-layout-<?php echo $this->uniqId; ?> .card {
        /* background: linear-gradient(0.25turn, #89bdd3, #ebf8e1, #f69d3c0a); */
        background: linear-gradient(0.25turn, #3e6373, #707b67, #f69d3c0a);
        padding-left: 0 !important;
        padding-right: 0 !important;
        border: none;
    }

    #kl-layout-<?php echo $this->uniqId; ?> .card.left {
        /* background: linear-gradient(0.25turn, #89bdd3, #ebf8e1, #f69d3c0a); */
        background: linear-gradient(0.25turn, #3e6373, #707b67, #f69d3c0a);
    }

    #kl-layout-<?php echo $this->uniqId; ?> .card.right {
        /* background: linear-gradient(0.25turn, #89bdd3, #ebf8e1, #f69d3c0a); */
        background: linear-gradient(0.25turn, #f69d3c0a, #707b67, #3e6373);
    }
    
    #kl-layout-<?php echo $this->uniqId; ?> .card > .card-header:not(.invisible) {
        border: none !important;
        border-bottom: 1px solid #5b6571 !important;
        padding: 0.25rem 1rem;
        background: transparent; /* #5b6571; */
        border-top-right-radius: 10px;
        border-top-left-radius: 10px;
        margin-bottom: 0;
    }

    #kl-layout-<?php echo $this->uniqId; ?> .card > .card-body:not(.invisible) {
        background: transparent;
    }

    .esri-ui-top-right {
        display: none !important;
    }

</style>