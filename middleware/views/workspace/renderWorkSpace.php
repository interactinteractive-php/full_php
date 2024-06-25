<div class="main-container-<?php echo $this->metaDataId; ?>">
    <?php 
    echo $this->replacedWorkSpaceHtml; 
    
    if (Config::getFromCache('CONFIG_CHECK_MODIFIED_CATCH')) {
        echo Form::hidden(array('data-path' => 'ws_check_modified_catch', 'value' => $this->wsRow['CHECK_MODIFIED_CATCH'])); 
    }
    ?>
</div>

<script type="text/javascript">
    var topMenuCfg = <?php echo (defined('CONFIG_TOP_MENU') ? json_encode(CONFIG_TOP_MENU) : 'false'); ?>;
    var $mainworkspace_<?php echo $this->metaDataId ?> = $("div.main-container-<?php echo $this->metaDataId; ?>");
    var $workspaceId_<?php echo $this->metaDataId ?> = $("div#workspace-id-<?php echo $this->metaDataId; ?>"),
        wsDmMetaDataId = '<?php echo $this->wsDmMetaDataId; ?>', 
        wsOneSelectedRow = <?php echo json_encode($this->wsOneSelectedRow); ?>; 

    $(function () {
        
        <?php if ($this->wsRow['THEME_CODE'] === 'theme20' && isset($this->iscontract) && $this->iscontract == '0') { ?>
            $('.vr-workspace-theme20').find('a[data-menu-id="1563516076910"]').closest('li').attr('style', 'border: 1px solid #e66767fa;')
            $('.vr-workspace-theme20').find('a[data-menu-id="1563516069359"]').closest('li').attr('style', 'border: 1px solid #e66767fa;')
        <?php } ?>
        
        <?php if ($this->wsRow['THEME_CODE'] === 'theme20') { ?>
            setTimeout(function() {
                $('.workspace-menu-v2 > li').each(function(){
                    $(this).css('display', 'inline-block');
                })
            }, 2500);
        <?php } ?>
        
        $('.more-dropdown-sub', '.vr-workspace-theme10').on('click', function() {
            $('.dropdown-fw-disabled', '.vr-workspace-theme10').removeClass('active');
            $(this).closest('.dropdown-fw-disabled').addClass('active');
        });
        
        $workspaceId_<?php echo $this->metaDataId ?>.closest(".ui-dialog-content").attr("padding", "0px");
        
        $("div#dialog-workspace-<?php echo $this->metaDataId; ?>").css("padding", "0px");
        
        $('.second-content-').show();
        $('.first-content-').hide();        
        
        <?php if ($this->isAddMode && $this->isFlow) { ?>
            $workspaceId_<?php echo $this->metaDataId ?>.find(".workspace-menu li:not(.active)").addClass("disabled");
            $workspaceId_<?php echo $this->metaDataId ?>.find("input[name='workSpaceMode']").val('1');
        <?php } else { ?>
            $workspaceId_<?php echo $this->metaDataId ?>.find("input[name='workSpaceMode']").val('0');
        <?php }
        
        if (!empty($this->defaultMenuId)) {
            if ($this->wsRow['THEME_CODE'] === 'theme19') { 
        ?>
                $workspaceId_<?php echo $this->metaDataId ?>.find(".workspace-menu").find("a[data-menu-id='-999']").trigger("click");
                
            <?php 
            } else { 
            ?>
            
            var wsClickMenuId = Core.getURLParameter('wsClickMenuId');
        
            if (wsClickMenuId != null) {
                $workspaceId_<?php echo $this->metaDataId ?>.find('[data-menu-id="'+wsClickMenuId+'"]').click();
            } else {
        
                if ($workspaceId_<?php echo $this->metaDataId ?>.find(".workspace-menu").find("a[data-menu-id='<?php echo $this->defaultMenuId; ?>']").length > 0) {
                    if ($workspaceId_<?php echo $this->metaDataId ?>.find(".workspace-menu").find("a[data-menu-id='<?php echo $this->defaultMenuId; ?>']").closest('ul.sub-menu').length) {
                        $workspaceId_<?php echo $this->metaDataId ?>.find(".workspace-menu").find("a[data-menu-id='<?php echo $this->defaultMenuId; ?>']").closest('ul').parent().children('a').trigger("click");
                    } else {
                        $workspaceId_<?php echo $this->metaDataId ?>.find(".workspace-menu").find("a[data-menu-id='<?php echo $this->defaultMenuId; ?>']").trigger("click");
                    }
                } else if ($workspaceId_<?php echo $this->metaDataId ?>.find(".workspace-cart-menu").find("a[data-menu-id='<?php echo $this->defaultMenuId; ?>']").length > 0) {
                    $workspaceId_<?php echo $this->metaDataId ?>.find(".workspace-cart-menu").find("a[data-menu-id='<?php echo $this->defaultMenuId; ?>']").trigger("click");
                } else {
                    var ticket = false;
                    $workspaceId_<?php echo $this->metaDataId ?>.find('.workspace-menu > li').each(function (index, row) {
                        if ($(row).hasClass('dropdown-fw-disabled') && !ticket) {
                            ticket = true;
                            $(row).find('a:eq(0)').trigger('click');
                        }
                    });
                }
            }
        <?php 
            }
        } 
        
        if ($this->wsRow['THEME_CODE'] === 'shop') {
        ?>
            $(".product-image-detial .owl-image-detail .item", $workspaceId_<?php echo $this->metaDataId ?>).on("click", function(){
                var _this = $(this);
                var selectImg = _this.find(".image").attr("data-img");
                $(".product-image-detial .owl-image-detail .item").removeClass("active");
                _this.addClass("active");
                $(".dopelessrotate img").attr("src", selectImg);
            });

            var $owlImageDetial = $(".owl-image-detail", $workspaceId_<?php echo $this->metaDataId ?>);

            $owlImageDetial.owlCarousel({
                items: 4, //10 items above 1000px browser width
                itemsDesktop: [1000, 4], //5 items between 1000px and 901px
                itemsDesktopSmall: [900, 3], // betweem 900px and 601px
                itemsTablet: [600, 2], //2 items between 600 and 0
                itemsMobile: false // itemsMobile disabled - inherit from itemsTablet option
            });

            $(".img-next", $workspaceId_<?php echo $this->metaDataId ?>).click(function () {
                $owlImageDetial.trigger('owl.next');
            });
            $(".img-prev", $workspaceId_<?php echo $this->metaDataId ?>).click(function () {
                $owlImageDetial.trigger('owl.prev');
            });

            var $owlHorizontal = $(".owl-horizontal", $workspaceId_<?php echo $this->metaDataId ?>);
            $owlHorizontal.owlCarousel({
                items: 5, //10 items above 1000px browser width
                itemsDesktop: [1000, 5], //5 items between 1000px and 901px
                itemsDesktopSmall: [900, 3], // betweem 900px and 601px
                itemsTablet: [600, 2], //2 items between 600 and 0
                itemsMobile: false // itemsMobile disabled - inherit from itemsTablet option
            });
            $(".btn-next", $workspaceId_<?php echo $this->metaDataId ?>).click(function () {
                $owlHorizontal.trigger('owl.next');
            });
            $(".btn-prev", $workspaceId_<?php echo $this->metaDataId ?>).click(function () {
                $owlHorizontal.trigger('owl.prev');
            });
            var $owlVertical = $(".owl-vertical", $workspaceId_<?php echo $this->metaDataId ?>);
            $owlVertical.owlCarousel({
                items: 1, //10 items above 1000px browser width
                itemsDesktop: [1000, 1], //5 items between 1000px and 901px
                itemsDesktopSmall: [900, 1], // betweem 900px and 601px
                itemsTablet: [600, 1], //2 items between 600 and 0
                itemsMobile: false // itemsMobile disabled - inherit from itemsTablet option
            });
            $(".vr-btn-next", $workspaceId_<?php echo $this->metaDataId ?>).click(function () {
                $owlVertical.trigger('owl.next');
            });
            $(".vr-btn-prev", $workspaceId_<?php echo $this->metaDataId ?>).click(function () {
                $owlVertical.trigger('owl.prev');
            });

        <?php  
        }

        if ($this->wsRow['THEME_CODE'] === 'theme10') {  
        ?>
            $('.dropdown-fw-disabled', '.vr-workspace-theme10').on('click', function() {
                var $this = $(this);
                var _widthPart = $('.workspace-part', '.vr-workspace-theme10').width();
                $('.workspace-part', '.vr-workspace-theme10').removeClass('mt50');

                $('.active', '.vr-workspace-theme10').removeClass('dropdown-clicked').removeClass('active').removeClass('open');
                $('.dropdown-fw-disabled', '.vr-workspace-theme10').removeClass('dropdown-clicked').removeClass('active').removeClass('open');
                $('.ws-hidden-params-two').empty();
                $this.addClass('dropdown-clicked active open');
                var _autoNumber = $this.attr('data-auto-number') - 1;
                var _widthPrev = 0;
                $this.parent().children().each(function (index, row) {
                    if (index < _autoNumber) {
                        _widthPrev = _widthPrev + $(row).width();
                    }
                });
                if ($this.children().hasClass('sub-menu')) {
                    if (_widthPart) {
                        $this.find('.sub-menu').attr('style', 'display:none; width: ' + _widthPart + 'px; !important; left: -' + _widthPrev + 'px;');
                    } else {
                        $this.find('.sub-menu').attr('style', 'display:none; width: 1000px; !important; left: -' + _widthPrev + 'px;');
                    }
                }
            });
        <?php  
        }

        if ($this->wsRow['USE_TOOLTIP'] === 'theme15') { 
        ?>
            $workspaceId_<?php echo $this->metaDataId ?>.find('.vr-workspace-theme15').find('.workspace-menu > li').on('click', function () {
                $workspaceId_<?php echo $this->metaDataId ?>.find('.vr-workspace-theme15').find('.workspace-menu > li').each(function (index, row) {
                    var $row = $(row);
                    $row.removeClass('active');
                    $row.find('.vr-theme-15-menu').addClass('hidden');
                    $row.find('i').removeClass('vr-menu-icon-theme15');
                    $row.width('100px');
                    $row.removeAttr('style');
                });
                
                var $this = $(this);

                $this.addClass('active');
                $this.find('i').addClass('vr-menu-icon-theme15');
                $this.find('.vr-theme-15-menu').removeClass('hidden');
                $this.attr('style', 'width:' + $this.attr('data-menu-width') +'px !important');
            });

            $('[data-toggle="tooltip"]').tooltip();
        <?php 
        }
        if ($this->wsRow['THEME_CODE'] === 'theme18') { 
        ?>
            dataViewByMeta_<?php echo $this->metaDataId; ?>('1481363580200938', 'right');
        <?php  
        }
        ?>

        $(".layout-theme", $workspaceId_<?php echo $this->metaDataId ?>).addClass('hidden');
        
        $('.layout-fill', $workspaceId_<?php echo $this->metaDataId ?>).each(function() {
            var $this = $(this);
            
            switch ($this.attr('data-meta-type-id')) {
                case '200101010000016':
                    layoutCallDataViewByMeta_<?php echo $this->metaDataId; ?>($this.attr('data-meta-id'));
                    break;
                case '200101010000024':
                    /* layoutCallGoogleMap_<?php echo $this->metaDataId; ?>(_this.attr('data-meta-id')); */
                    break;
                case '200101010000031':
                    /* layoutCallDashboardCardByMeta_<?php echo $this->metaDataId; ?>(_this.attr('data-meta-id')); */
                    break;
                case '200101010000032':
                    $.getScript(URL_APP+'assets/custom/addon/plugins/amcharts/amcharts/amChartMinify.js').done(function( script, textStatus ) {
                        $.getScript(URL_APP+'middleware/assets/js/dashboard/charts_amcharts.js').done(function( script, textStatus ) {
                            layoutCallDiagramByMeta_<?php echo $this->metaDataId; ?>($this.attr('data-meta-id'));
                        });
                    });
                    break;
                case '200101010000027':
                    layoutCallCalendar_<?php echo $this->metaDataId; ?>($this.attr('data-meta-id'));
                    break;
            }
        });
      
        setTimeout(function() {
            $(".layout-theme", $workspaceId_<?php echo $this->metaDataId ?>).removeClass('hidden');
        }, 200);
            
        Core.initTabs('.vr-workspace-<?php echo $this->wsRow['THEME_CODE']; ?>');
        getLeftMenuCount(false, $workspaceId_<?php echo $this->metaDataId ?>);
    });
        
    <?php if ($this->wsRow['CHECK_MODIFIED_CATCH'] === '1') { ?>
        if (typeof checkModifiedCatch !== 'undefined' && checkModifiedCatch === '1') {
            $('body').on("change", "input, select, textarea", "#workspace-id-<?php echo $this->metaDataId; ?> .xs-form", function () {
                $mainworkspace_<?php echo $this->metaDataId ?>.find("input[data-path='ws_check_modified_catch']").val("1"); 
            });
        }
    <?php } ?>
            
    function dataViewByMeta_<?php echo $this->metaDataId; ?>(metaDataId, positionType, criteriaCustomerId) {
        $.ajax({
            type: 'post',
            dataType: 'json',
            async: false,
            data: {},
            url: 'mdobject/dataViewDataGrid/1/1/' + metaDataId,
            beforeSend: function () {},
            success: function (data) {
                var $joinEmployee = "<ul class='right-widget-list'>";
                
                for(var drow = 0; drow < data.rows.length; drow++) {
                    $joinEmployee += '<li><a href="#" id="" class="right-widget-list-link">'+
                        '<img class="" alt="" height="56" width="56" src="assets/core/global/img/metaicon/big/profile.png">'+
                        '<div class="right-widget-detail">'+
                        '<h3 id="">'+
                        '<span class="name actor-name">' + data.rows[drow].lastname + ' ' + data.rows[drow].firstname + '</span>'+
                        '</h3>'+
                        '<p class="">' + data.rows[drow].positionname + '</p>'+
                        '</div>'+
                        "</a></li>";
                }
                $joinEmployee += "</ul>";                
                
                $('.workspace-right-widget-<?php echo $this->metaDataId ?>').empty().append($joinEmployee);
            },
            error: function(){
                alert("Error");
            }
        });
    }
    
    function backWindow(elem) {
        $('.left-stoggler .glyphicon-chevron-left').hide();
        $('.left-stoggler .glyphicon-chevron-right').show();
        backFirstContent(elem);
    }
    
    function backWindowDataViewFilter() {
        $('.second-content-').hide();
        $('.first-content-').show();
        $('.left-stoggler .glyphicon-chevron-left').hide();
        $('.left-stoggler .glyphicon-chevron-right').show();
        $(window).trigger("resize");
    }

    function clearTab(){
        $(".workspace-main-container", $workspaceId_<?php echo $this->metaDataId ?>).empty();
    }
    
    function subMenuShow(element) {
        var $elemParent = $(element);
        $('.workspace-menu', $workspaceId_<?php echo $this->metaDataId ?>).find('.active').removeClass('active');
        $elemParent.addClass('active');
        $elemParent.find('.dropdown-menu').show();
    }
    
    function layoutCallDashboardCardByMeta_<?php echo $this->metaDataId; ?>(metaDataId) {
        $.ajax({
            type: 'post',
            url: 'mdmeta/cardRenderByPost',
            data: {metaDataId: metaDataId},
            dataType: 'json',
            success: function(data){
                $("div#layout-" + metaDataId).html(data.Html);
            },
            error: function(){
                alert("Error");
            }
        }).done(function(){
            Core.initAjax($("div#layout-" + metaDataId));
        });
    }
    
    function layoutCallDiagramByMeta_<?php echo $this->metaDataId; ?>(metaDataId) {
        $.ajax({
            type: 'post',
            url: 'mddashboard/diagramRenderByPost',
            data: {metaDataId: metaDataId},
            dataType: "json",
            beforeSend: function() {
            },
            success: function(data) {
                $("div#layout-" + metaDataId).html('<div class="row">' 
                        + '<div class="meta-toolbar" style="border-left:3px solid #30a2dd; padding-top: 6px;"><span class="bold uppercase portlet-subject-blue mt10 ml10 display">'+ data.Title +'</span></div>'
                        + data.Html + '</div>');
            },
            error: function(){
              alert("Error");
            }
        }).done(function(){
            Core.initAjax($("div#layout-" + metaDataId));
        });
    }
    
    function layoutCallGoogleMap_<?php echo $this->metaDataId; ?>(metaDataId) {
        $.ajax({
            type: 'post',
            url: 'mdmetadata/googleMapView',
            data: {metaDataId: metaDataId},
            dataType: "json",
            beforeSend: function(){
                Core.blockUI({
                    animate: true
                });
            },
            success: function(data){
                $("div#layout-" + metaDataId).html('<div class="row">' + data + '</div>');
                Core.unblockUI();
            },
            error: function(){
                alert("Error");
            }
        }).done(function(){
            Core.initAjax($("div#layout-" + metaDataId));
        });
    }
    
    function layoutCallDataViewByMeta_<?php echo $this->metaDataId; ?>(metaDataId) {        
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'mdobject/dataview/' + metaDataId + '/false/json',
            success: function (data) {
                $("div#layout-" + metaDataId).html('<div class="row">' 
                        + '<div class="meta-toolbar" style="border-left:3px solid #30a2dd; padding-top: 6px;"><span class="bold uppercase portlet-subject-blue mt10 ml10 display">'+ data.Title +'</span></div>'
                        + data.Html + '</div>');
            },
            error: function(){
                alert("Error");
            }
        }).done(function(){
            Core.initAjax($("div#layout-" + metaDataId));
        });
    }
    
    function layoutCallCalendar_<?php echo $this->metaDataId; ?>(metaDataId) {
        $.ajax({
            type: 'post',
            url: 'mdcalendar/calendarRenderByPost/',
            data: {metaDataId: metaDataId},
            dataType: "json",
            beforeSend: function(){
                Core.blockUI({
                    animate: true
                });
            },
            success: function(data){
                $("div#layout-" + metaDataId).html(data.Html);
                Core.unblockUI();
            },
            error: function(){
                alert("Error");
            }
        }).done(function(){
            Core.initAjax($("div#layout-" + metaDataId));
        });
    }
</script>

<style type="text/css">
    .workspace-right-widget-<?php echo $this->metaDataId ?> .pagination table {
        width: 100%;
    }
    .workspace-right-widget-<?php echo $this->metaDataId ?> .pagination-page-list {
        display:none;
    }
    .workspace-right-widget-<?php echo $this->metaDataId ?> .pagination-info {
        width: 100%;
        margin: 0;
        padding-left: 7px;
        float: right;
        text-align: right;
    }
    .vr-workspace-<?php echo $this->wsRow['THEME_CODE']; ?> .left-side .portlet {
        margin-top: 10px;
        border:0 !important;
        border-radius: 0 !important;
    }
    .vr-workspace-<?php echo $this->wsRow['THEME_CODE'] ?> .left-side .layout-theme {
        padding: 0;
    }
    .vr-workspace-<?php echo $this->wsRow['THEME_CODE'] ?> .left-side .layout-theme > .row {
        margin: 0;
    }
    .vr-workspace-<?php echo $this->wsRow['THEME_CODE'] ?> .left-side .layout-cell {
        padding-right: 0 !important;
        padding-left: 0 !important;
        width: 100% !important;
        margin:0 !important;
    }
    .vr-workspace-<?php echo $this->wsRow['THEME_CODE'] ?> .left-side .layout-cell>div>div {
        background: #FFF;
        margin-bottom: 5px !important;
    }
    .vr-workspace-<?php echo $this->wsRow['THEME_CODE'] ?> .left-side .layout-cell .row {
        margin-right: 0 !important;
        margin-left: 0 !important;
    }
    .vr-workspace-<?php echo $this->wsRow['THEME_CODE'] ?> .left-side .layout-cell .col-md-12 {
        padding: 1px !important;
    }
    .vr-workspace-<?php echo $this->wsRow['THEME_CODE'] ?> .left-side .amChartsLegend {
        display:none;
    }
    .vr-workspace-<?php echo $this->wsRow['THEME_CODE'] ?> .left-side .left-side .table-toolbar {
        display: none;
    }
    .vr-workspace-<?php echo $this->wsRow['THEME_CODE'] ?> .left-side .portlet.light.bordered {
        border: 0 !important;
    }
</style>