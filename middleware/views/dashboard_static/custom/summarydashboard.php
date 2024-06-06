<a href="javascript:;" class="navbar-nav-link  d-none d-md-block" id="showLeft_<?php echo $this->metaDataId;?>">
    <i class="icon-filter4" style="font-size:16px;font-weight:bold;"></i>
</a>
<div class="w-100 hrddashboard">
    <div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1_<?php echo $this->metaDataId;?>">
        <a href="javascript:;" class="filterclose"><span class="icon-cross"></span></a>
        <h3><?php echo $this->lang->line('filter'); ?></h3>
        <?php echo $this->defaultCriteria ?>
    </div>
    <div id="summerycontent_<?php echo $this->uniqId;?>"  class="content-wrapper">
        data ...
    </div>
</div>


<link href="<?php echo autoVersion('middleware/assets/css/scss/hr-main.css'); ?>" rel="stylesheet"/> 

<script type="text/javascript" src="assets/custom/addon/plugins/apexcharts/apexcharts.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/amcharts.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/serial.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/radar.js"></script>
<script type="text/javascript" src="assets/custom/addon/plugins/apexcharts/apexcharts.js"></script>

<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/pie.js"></script>

<script type="text/javascript" src="assets/custom/addon/plugins/amcharts4/core.js"></script>
<script type="text/javascript" src="assets/custom/addon/plugins/amcharts4/charts.js"></script>
<script type="text/javascript" src="assets/custom/addon/plugins/amcharts4/themes/animated.js"></script>
<script type="text/javascript" src="assets/custom/faq/modernizr.js"></script>
<script type="text/javascript" src="assets/custom/faq/classie.js"></script>


<script type="text/javascript">
    var menuLeft = document.getElementById( 'cbp-spmenu-s1_<?php echo $this->metaDataId;?>' ),
        showLeft = document.getElementById( 'showLeft_<?php echo $this->metaDataId;?>' ),
        body = document.body;

    var $mainSelector = $('#summerycontent_<?php echo $this->uniqId;?>').empty();                                                          
    var $style = "<?php echo $this->theme; ?>";
    var criteria_search_<?php echo $this->metaDataId; ?> = $("div#dv-search-<?php echo $this->metaDataId; ?>");

    // sidebar show/hide
    showLeft.onclick = function() {
        classie.toggle( this, 'active' );
        classie.toggle( menuLeft, 'cbp-spmenu-open' );
       // disableOther( 'showLeft_<?php echo $this->metaDataId;?>' );
    };
    function disableOther( button ) {
        if( button !== 'showLeft_<?php echo $this->metaDataId;?>' ) {
            classie.toggle( showLeft, 'disabled' );
        }
    }

    $(document).on('click', '.filterclose', function (e) {
        showLeft.click();
    });
    $(document).on('click', '.sidebar-main-hide', function (e) {
        e.preventDefault();
        $('.sidebar-main').toggleClass('hidden');
        $('body').toggleClass('sidebar-main-hidden');
    });
    
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'dashboard/hrSummaryDashboardData/'+ $style,
        data: {id:'1',path:'isDefault'},
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

    criteria_search_<?php echo $this->metaDataId; ?>.on('click', 'button.dataview-default-filter-btn', function(){
      var $this = $(this);
      showLeft.click();
        var dvDefaultCriteria = {};        
        var getPostData = $this.closest('form').serializeArray();
        
        if (getPostData) {
            for (var fdata = 0; fdata < getPostData.length; fdata++) {
                var mPath = /param\[([\w.]+)\]/g.exec(getPostData[fdata].name);
                if(mPath === null) continue;

                if (dvDefaultCriteria.hasOwnProperty(mPath[1])) {
                    dvDefaultCriteria[mPath[1]] = dvDefaultCriteria[mPath[1]] + '#,#' + getPostData[fdata].value;
                } else {
                    dvDefaultCriteria[mPath[1]] = getPostData[fdata].value;
                }
            }        
        }
      
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'mdasset/hrSummaryDashboardData/'+ $style,
            data: {'formtData': dvDefaultCriteria},
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
                    $mainSelector.empty().append(data.Html);
                }
                Core.unblockUI();
            }
        });
    });

    // criteria_search_<?php echo $this->metaDataId; ?>.find('button.dataview-default-filter-btn').hide();

    criteria_search_<?php echo $this->metaDataId; ?>.on('click', 'button.dataview-default-filter-reset-btn', function(){
        criteria_search_<?php echo $this->metaDataId; ?>.find("input[type=checkbox]").removeAttr('checked');
        criteria_search_<?php echo $this->metaDataId; ?>.find("input[type=checkbox]").closest('span.checked').removeClass('checked');
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'mdasset/hrSummaryDashboardData/'+ $style,
            data: {id:'1',path:'isDefault'},
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
                    $mainSelector.empty().append(data.Html);
                }
                Core.unblockUI();
            }
        });
    });
 
    function groupBy(chartdata, property) {
        return chartdata.reduce((acc, obj) => {
            const key = obj[property];
            if (!acc[key]) {
            acc[key] = [];
            }
            acc[key].push(obj);
            return acc;
        }, {});
    }
 
    function searchRow(rowdata, text) {
        for (var i = 0; i < rowdata.length; i++) {
            if (rowdata[i]['name'] == text) {
                return rowdata[i];
            }
        }
        return '';
    }

    function drilldownHrList(element,mName,mId) {
        var row = JSON.parse($(element).attr('data-row'));

        var dvDefaultCriteria = '';       

        var getPostData = $('body').find('#default-criteria-form').serializeArray();
        if (getPostData) {
            for (var fdata = 0; fdata < getPostData.length; fdata++) {
                var mPath = /param\[([\w.]+)\]/g.exec(getPostData[fdata].name);
                if (mPath) {
                    dvDefaultCriteria += mPath[1] +'='+ getPostData[fdata].value +'&';
                }
                    //'defaultmetaid=1578488805200&isnew1='+ row.isnew1+'&typeid='+ row.typeid +'
            }        
        }
        gridDrillDownLink(element, mName, 'metagroup', '1', '',  mId, '','', dvDefaultCriteria, true, true);
    }
    
    function drilldownMeta(rowdata, metadataid) {
        var dvDefaultCriteria = '';       
        var getPostData = $('body').find('#default-criteria-form').serializeArray();
        if (getPostData) {
            for (var fdata = 0; fdata < getPostData.length; fdata++) {
                var mPath = /param\[([\w.]+)\]/g.exec(getPostData[fdata].name);
                if (mPath) {
                    dvDefaultCriteria += mPath[1] +'='+ getPostData[fdata].value +'&';
                }
            }        
        }        
        
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'mdasset/getDrill',
            data: {
                metadataid: metadataid,
                rowdata: rowdata
            },
            beforeSend: function() {
            },
            success: function(data) {
                if (Object.keys(data).length) {
                    gridDrillDownLink(this, '', data.link_metatypecode, data.clinkMetadataId, undefined, metadataid, 'name', data.link_linkmetadataid, data.sourceParam+'&'+dvDefaultCriteria, data.isnewTab, undefined, data.link_dialogWidth, data.link_dialogHeight);
                }
            }
        });        
    }

</script>
