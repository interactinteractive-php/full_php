<div class="row viewer-container ">
    <div class="clearfix w-100"></div>
    <?php if (isset($this->headerLegendData['islegend']) && $this->headerLegendData['islegend'] === '1') { ?>
        <div class="col-md-12">
            <?php 
            if (isset($this->legendData) && $this->legendData) {
                foreach ($this->legendData as $key => $row) {
                    $criteria = Str::htmlCharToDoubleQuote($row['criteria']);
                    $rowJson = ''; ?> 
                    <span class="legend-tree" data-rowdata='<?php echo urlencode(json_encode($row)); ?>' onclick="clickCriteria_<?php echo $this->uniqId; ?>(this, '<?php echo $row['id'] ?>', '<?php echo $row['id'] ?>', '<?php echo $rowJson; ?>', '<?php echo $row['name'] ?>', '<?php echo $row['color']; ?>')"><i class="fa fa-circle" style="color:<?php echo $row['color']; ?>"></i> <?php echo $row['name'] ?> </span>
                <?php }
            } ?>
            <br>
            <!--<i>Note: Shaded circles have more data, hover on name to offset and increase font</i>-->
            <hr>
        </div>
    <?php } ?>
    <div class="col-md-12 mt0" style="background: #FFF;" id="d3-tree-model-<?php echo $this->uniqId ?>"></div>
</div>

<?php echo isset($this->tree) ? $this->tree : ''; ?>

<script type="text/javascript">
    var IS_LOAD_D3_SCRIPT = false;
    
    $(document).ready(function () {
        $.ajax({
            type: 'post',
            url: 'mdlayout/getTreeJsonData',
            data: {
                legendData: <?php echo json_encode($this->legendData); ?>, 
                id: '<?php echo $this->id ?>',
                processId: '<?php echo $this->processId ?>',
                chartType: ('<?php echo $this->chartType; ?>').toLowerCase()
            },
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
    
    function clickCriteria_<?php echo $this->uniqId; ?>(element, templateId, id, criteria, name, color) {
        var $curr = $(element), legend = [], $prevs = $curr.prevAll();
        
        $prevs.each(function(){
            legend.push(JSON.parse(decodeURIComponent($(this).data('rowdata'))));
        });
        legend.push(JSON.parse(decodeURIComponent($curr.data('rowdata'))));

        $.ajax({
            type: 'post',
            url: 'mdlayout/getTreeJsonData',
            data: {
                legendData: legend, 
                id: '<?php echo $this->id ?>',
                processId: '<?php echo $this->processId ?>',
            },
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