<div id="widget-area-<?php echo $this->uniqId; ?>">
    <div class="render-object-viewer">
        <div class="row">
            <div class="col-md-12">
                <?php
                if ($this->mainData) {
                    foreach ($this->mainData as $key => $recordRow) {
                        ?>
                        <div class="row viewer-container">

                            <div class="explorer7_<?php echo $this->dataViewId . '_' . $key ?>">
                                <?php $color = '#0ca766'; ?>
                                <ul>

                                    <li class="dv-explorer-row">	
                                        <a class="wfm-status-done" href="<?php echo ((isset($recordRow['METADATAID']) && isset($recordRow['WFMSTATUSID'])) ? "mdobject/dataview/" . $recordRow['METADATAID'] . "&dv[wfmstatusid][]=" . $recordRow['WFMSTATUSID'] . "" : 'javascript:;'); ?>">
                                            <?php echo $recordRow['TYPENAME']; ?> 
                                        </a>
                                    </li>
                                    <?php
                                    $color = (isset($recordRow['COLOR']) && $recordRow['COLOR']) ? $recordRow['COLOR'] : '#0ca766';
                                    $event = "mdobject/dataview/" . $recordRow['METADATAID'];
                                    $event = '';

                                    $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
                                    foreach ($recordRow as $sKey => $sRow) {
                                        switch ($sKey) {
                                            case 'TYPEID':
                                            case 'TYPENAME':
                                            case 'CHECK1':
                                            case 'COLOR':
                                            case 'METADATAID':
                                                break;

                                            default:
                                                if (isset($recordRow[$sKey]) && $recordRow[$sKey]) {
                                                    $recordValueExplode = explode('#', $recordRow[$sKey]);

                                                    if (isset($recordValueExplode[2]) && isset($recordValueExplode[1])) {
                                                        $event = $recordValueExplode[2] . "=" . $recordValueExplode[1];
                                                    }

                                                    if (isset($recordValueExplode[0]) && $recordValueExplode[0]) {
                                                        ?>
                                                        <li class="dv-explorer-row selected-row" data-row-data="<?php echo $rowJson ?>">	
                                                            <a data-row-data="<?php echo $rowJson ?>" data-row="<?php echo $rowJson ?>" class="wfm-status-done selected-row-link" href="javascript:;" onclick="gridDrillDownLink(this, 'HRM_EMPLOYEE_KEY_DV_GB', 'metagroup', '1', '', '<?php echo $recordRow['METADATAID'] ?>', 'code', '<?php echo $recordRow['METADATAID'] ?>', '<?php echo $event; ?>', true, true)">
                            <?php echo str_replace("'", "", $sKey); ?> 
                                                                <div><?php echo $recordValueExplode[0]; ?> </div>
                                                            </a>
                                                        </li>
                                                        <?php
                                                    }
                                                }
                                                break;
                                        }
                                    }
                                    ?>
                                </ul>   
                            </div>

                            <style type="text/css">
                                .explorer7_<?php echo $this->dataViewId . '_' . $key ?> {
                                    text-align: left;
                                }
                                .explorer7_<?php echo $this->dataViewId . '_' . $key ?> ul {
                                    list-style: none;
                                    display: inline-table;
                                    margin-bottom: 0;
                                    padding: 0;
                                }
                                .explorer7_<?php echo $this->dataViewId . '_' . $key ?> ul li {
                                    display: inline;
                                }
                                .explorer7_<?php echo $this->dataViewId . '_' . $key ?> ul li a {
                                    display: block;
                                    float: left;
                                    height: 60px;
                                    background-color: #eaedf4;
                                    text-align: center;
                                    padding: 9px 37px 0 45px;
                                    position: relative;
                                    margin: 0 5px 0 0; 
                                    font-size: 14px;
                                    text-decoration: none;
                                    color: #515f77;

                                    font-weight: 600;
                                    border-top: 1px <?php echo $color ?> solid;
                                    border-bottom: 1px <?php echo $color ?> solid;
                                    border-right: 8px <?php echo $color ?> solid;
                                    margin-left: -6px;
                                }
                                .explorer7_<?php echo $this->dataViewId . '_' . $key ?> ul li a:after {
                                    content: "";  
                                    border-top: 28px solid transparent;
                                    border-bottom: 28px solid transparent;
                                    border-left: 28px solid #eaedf4;
                                    position: absolute; 
                                    right: -28px; 
                                    top: 0;
                                    z-index: 1;
                                }
                                .explorer7_<?php echo $this->dataViewId . '_' . $key ?> ul li a:before {
                                    content: "";  
                                    border-top: 28px solid transparent;
                                    border-bottom: 28px solid transparent;
                                    border-left: 28px solid #fff;
                                    position: absolute; 
                                    left: 0; 
                                    top: 0;
                                }
                                .explorer7_<?php echo $this->dataViewId . '_' . $key ?> ul li:first-child a {
                                    text-transform: uppercase;
                                    padding: 11px 0px 0 10px;
                                    word-wrap: break-word;
                                    width: 160px;
                                    color: #FFF !important;
                                    background-color: <?php echo $color ?> !important;
                                    border-left: 1px solid <?php echo $color ?>;
                                }
                                .explorer7_<?php echo $this->dataViewId . '_' . $key ?> ul li:first-child a:after {
                                    background-color: <?php echo $color ?> !important;
                                    z-index: -1;
                                }
                                .explorer7_<?php echo $this->dataViewId . '_' . $key ?> ul li:first-child a:before {
                                    display: none; 
                                }
                                .explorer7_<?php echo $this->dataViewId . '_' . $key ?> ul li:last-child a {
                                    padding-right: 28px;
                                    text-transform: uppercase;
                                    border-right: 3px <?php echo $color ?> solid;
                                }
                                .explorer7_<?php echo $this->dataViewId . '_' . $key ?> ul li:last-child a:after {
                                    display: none; 
                                }
                                .explorer7_<?php echo $this->dataViewId . '_' . $key ?> ul li a.wfm-status-done:hover {
                                    background-color: <?php echo $color ?>;
                                    color: #FFF;
                                    border-top: 1px <?php echo $color ?> solid;
                                    border-bottom: 1px <?php echo $color ?> solid;
                                }
                                .explorer7_<?php echo $this->dataViewId . '_' . $key ?> ul li a.wfm-status-done:hover:after {
                                    border-left-color: <?php echo $color ?>;
                                }
                                .explorer7_<?php echo $this->dataViewId . '_' . $key ?> ul li a.wfm-status-done {
                                    color: #000;
                                    background-color: #FFF;
                                    border-top: 3px <?php echo $color ?> solid;
                                    border-bottom: 3px <?php echo $color ?> solid;
                                }
                                .explorer7_<?php echo $this->dataViewId . '_' . $key ?> ul li a.wfm-status-done:after {
                                    border-left: 28px solid #FFF;
                                }
                                .explorer7_<?php echo $this->dataViewId . '_' . $key ?> ul li a.wfm-status-current {
                                    color: #fff;
                                    background-color: #0070d2;
                                    border-top: 1px #0070d2 solid;
                                    border-bottom: 1px #0070d2 solid;
                                }
                                .explorer7_<?php echo $this->dataViewId . '_' . $key ?> ul li a.wfm-status-current:after {
                                    border-left: 28px solid #0070d2;
                                }
                                .body-top-menu-style .explorer7_<?php echo $this->dataViewId . '_' . $key ?> ul li a:before {
                                    border-left: 28px solid <?php echo $color ?>;
                                }

                            </style>

                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    #widget-area-<?php echo $this->uniqId; ?> .spinner{
        width: initial !important;
        height: initial !important;
    }
</style>
<?php if (issetParam($this->reload) !== '1') { ?>
    <script type="text/javascript">
        
    setInterval(function () {
        reloadInterval_<?php echo $this->uniqId ?> ();
    }, 30000);
    
    function reloadInterval_<?php echo $this->uniqId ?> () {
        $.ajax({
            url: 'mdwidget/dashboardV2Widget/<?php echo issetParam($this->dataViewId); ?>',
            type: 'post',
            data: {
                reload: '1',
                uniqId: '<?php echo $this->uniqId; ?>'
            },
            dataType: 'JSON',
            beforeSend: function () {
                blockContent_<?php echo $this->uniqId ?>('#widget-area-<?php echo $this->uniqId; ?> > .render-object-viewer');  
            },
            success: function (response) {
                $('#widget-area-<?php echo $this->uniqId; ?> > .render-object-viewer').empty().append(response.Html).promise().done(function () {
                    Core.unblockUI('#widget-area-<?php echo $this->uniqId; ?> > .render-object-viewer');
                });
            },
            error: function (jqXHR, exception) {
                Core.unblockUI('#widget-area-<?php echo $this->uniqId; ?> > .render-object-viewer');
                Core.showErrorMessage(jqXHR, exception);
            }
        });
    }
    
    function blockContent_<?php echo $this->uniqId ?>(mainSelector) {
        $(mainSelector).block({
            message: '<i class="icon-spinner4 spinner"></i>',
            centerX: 0,
            centerY: 0,
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                width: 16,
                top: '15px',
                left: '',
                right: '15px',
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
    }
    
    </script>
<?php } ?>