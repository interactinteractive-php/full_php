<?php
if ($this->isAjax == false) {
    if (Config::getFromCache('CONFIG_MULTI_TAB')) {
?>
<div class="col-md-12">
    <div class="card light shadow card-multi-tab">
        <div class="card-header header-elements-inline tabbable-line">
            <ul class="nav nav-tabs card-multi-tab-navtabs">
                <li>
                    <a href="#app_tab_mdlayouttreetemplate" class="active" data-toggle="tab"><i class="fa fa-caret-right"></i> <?php echo $this->title; ?><span><i class="fa fa-times-circle"></i></span></a>
                </li>
            </ul>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content card-multi-tab-content">
                <div class="tab-pane active" id="app_tab_mdlayouttreetemplate">
                    
                    <div class="tree-model-<?php echo $this->uniqId ?>">
                        <div class="clearfix w-100"></div>
                        <div class="d3-layout">
                            <?php 
                            if (isset($this->legendData) && $this->legendData) {
                                foreach ($this->legendData as $key => $row) {
                                    $criteria = Str::htmlCharToDoubleQuote($row['criteria']);
                                    $rowJson = ''; 
                            ?> 
                                <span class="legend-tree" data-rowdata='<?php echo urlencode(json_encode($row)); ?>' onclick="clickCriteria_<?php echo $this->uniqId; ?>(this, '<?php echo $row['id'] ?>', '<?php echo $row['id'] ?>', '<?php echo $rowJson; ?>', '<?php echo $row['name'] ?>', '<?php echo $row['color']; ?>')"><i class="fa fa-circle" style="color:<?php echo $row['color']; ?>"></i> <?php echo $row['name'] ?> </span>
                            <?php 
                                }
                            } 
                            ?>
                        </div>

                        <div class="col-md-12 mt0" style="background: #FFF;" id="d3-tree-model-<?php echo $this->uniqId ?>"></div>
                    </div>
                    
                    <?php echo isset($this->tree) ? $this->tree : ''; ?>
                </div>
            </div>
        </div>
    </div>
</div>    
<?php
    } 
} else {
?>
<div class="tree-model-<?php echo $this->uniqId ?>">
    <div class="clearfix w-100"></div>
    <div class="d3-layout">
        <?php 
        if (isset($this->legendData) && $this->legendData) {
            foreach ($this->legendData as $key => $row) {
                $criteria = Str::htmlCharToDoubleQuote($row['criteria']);
                $rowJson = ''; 
        ?> 
            <span class="legend-tree" data-rowdata='<?php echo urlencode(json_encode($row)); ?>' onclick="clickCriteria_<?php echo $this->uniqId; ?>(this, '<?php echo $row['id'] ?>', '<?php echo $row['id'] ?>', '<?php echo $rowJson; ?>', '<?php echo $row['name'] ?>', '<?php echo $row['color']; ?>')"><i class="fa fa-circle" style="color:<?php echo $row['color']; ?>"></i> <?php echo $row['name'] ?> </span>
        <?php 
            }
        } 
        ?>
    </div>

    <div class="col-md-12 mt0" style="background: #FFF;" id="d3-tree-model-<?php echo $this->uniqId ?>"></div>
</div>     
<?php
echo isset($this->tree) ? $this->tree : '';
}
?>

<script type="text/javascript">
    var IS_LOAD_D3_V2_SCRIPT = false;
    
    $(document).ready(function () {
        
        <?php if (isset($this->colorPath)) { ?>
            var params = {
                legendData: <?php echo json_encode($this->legendData); ?>, 
                id: '<?php echo $this->id ?>',
                processId: '<?php echo $this->processId ?>',
                chartType: ('<?php echo $this->chartType; ?>').toLowerCase(),
                treeTemplate: '1'
            };
        <?php } else { ?>
            var params = {
                legendData: <?php echo json_encode($this->legendData); ?>, 
                id: '<?php echo $this->id ?>',
                processId: '<?php echo $this->processId ?>',
                chartType: ('<?php echo $this->chartType; ?>').toLowerCase()
            };
        <?php } ?>
        
        $.ajax({
            type: 'post',
            url: 'mdlayout/getTreeJsonData',
            data: params,
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (response) {
                if (response.status === 'success' && typeof response.data !== 'undefined' && response.data.length != 0) {
                    D3TreeCollapsible_<?php echo $this->uniqId ?>.init(response.data);
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: 'Өгөгдөл олдсонгүй',
                        type: 'error',
                        sticker: false
                    });
                }

                Core.unblockUI();
            },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } 
                else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } 
                else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } 
                else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } 
                else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } 
                else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } 
                else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }

                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: msg,
                    type: 'error',
                    sticker: false
                });
                Core.unblockUI();
            }
        });
        
    });
    
    function clickCriteria_<?php echo $this->uniqId; ?>(element, categoryId, id, criteria, name, color) {
        var $curr = $(element), legend = [], $prevs = $curr.prevAll();
        
        $prevs.each(function(){
            legend.push(JSON.parse(decodeURIComponent($(this).data('rowdata'))));
        });
        legend.push(JSON.parse(decodeURIComponent($curr.data('rowdata'))));
        
        <?php if (isset($this->colorPath)) { ?>
            var params = {
                legendData: <?php echo json_encode($this->legendData); ?>, 
                id: '<?php echo $this->id ?>',
                processId: '<?php echo $this->processId ?>',
                chartType: ('<?php echo $this->chartType; ?>').toLowerCase(),
                categoryId: categoryId,
                treeTemplate: '1'
            };
        <?php } else { ?>
            var params = {
                legendData: legend, 
                id: '<?php echo $this->id ?>',
                processId: '<?php echo $this->processId ?>',
                chartType: ('<?php echo $this->chartType; ?>').toLowerCase(),
                categoryId: categoryId
            };
        <?php } ?>
        $.ajax({
            type: 'post',
            url: 'mdlayout/getTreeJsonData',
            data: params,
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (response) {
                if (response.status === 'success' && typeof response.data !== 'undefined' && response.data.length != 0) {
                    $('#d3-tree-model-<?php echo $this->uniqId ?>').empty();
                    D3TreeCollapsible_<?php echo $this->uniqId ?>.init(response.data);
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: 'Өгөгдөл олдсонгүй',
                        type: 'error',
                        sticker: false
                    });
                }

                Core.unblockUI();
            },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } 
                else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } 
                else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } 
                else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } 
                else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } 
                else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } 
                else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }

                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: msg,
                    type: 'error',
                    sticker: false
                });
                Core.unblockUI();
            }
        });
    }
    
</script>

<style type="text/css">
.legend-tree {
    padding: 5px;
}
.legend-tree:hover {
    cursor: pointer;
    background: #d3e7f9;
}
</style>