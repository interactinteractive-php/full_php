<?php if (!$this->isAjax) { ?>
    
    <div class="bg-white">
        <div class="card-body form" id="mainRenderDiv" style="">
            <div class="xs-form main-action-meta bp-banner-container " id="bp-window-<?php echo $this->uniqId ?>" data-meta-type="process" data-process-id="<?php echo $this->uniqId ?>" data-bp-uniq-id="<?php echo $this->uniqId ?>">
                <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'eaportal-tree-layout-form', 'method' => 'post')); ?>
                <div class="meta-toolbar">
                    <span class="font-weight-bold text-uppercase text-gray2"><?php echo $this->title ?></span>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php 
                        if (isset($this->legendData) && $this->legendData) {
                            foreach ($this->legendData as $key => $row) { 
                                $criteria = Str::htmlCharToDoubleQuote($row['criteria']);
                                $rowJson = ''; ?> 
                                <span class="legend-tree" onclick="clickCriteria_<?php echo $this->uniqId; ?>(this, '<?php echo $row['id'] ?>', '<?php echo $row['id'] ?>', '<?php echo $rowJson; ?>', '<?php echo $row['name'] ?>', '<?php echo $row['color']; ?>')"><i class="fa fa-circle" style="color:<?php echo $row['color']; ?>"></i> <?php echo $row['name'] ?> </span>
                            <?php }
                        } ?>
                        <br>
                        <i>Note: Shaded circles have more data, hover on name to offset and increase font</i>
                        <hr>
                    </div>
                    <div class="col-md-12 center-sidebar">
                        <div class="mt0" id="d3-tree-model-<?php echo $this->uniqId ?>"></div>
                    </div>
                </div>
                <div class="clearfix w-100"></div>
                <?php echo Form::close(); ?>
            </div>
        </div>
    </div>

<?php } else { ?>
    <div class="meta-toolbar">
        <span class="text-uppercase"><?php echo $this->title ?></span>
        <div class="clearfix w-100"></div>
    </div>
    <div class="row viewer-container ">
        <div class="clearfix w-100"></div>
            <div class="col-md-12">
                <?php 
                if (isset($this->legendData) && $this->legendData) {
                    foreach ($this->legendData as $key => $row) {
                        $criteria = Str::htmlCharToDoubleQuote($row['criteria']);
                        $rowJson = ''; ?> 
                        <span class="legend-tree" onclick="clickCriteria_<?php echo $this->uniqId; ?>(this, '<?php echo $row['id'] ?>', '<?php echo $row['id'] ?>', '<?php echo $rowJson; ?>', '<?php echo $row['name'] ?>', '<?php echo $row['color']; ?>')"><i class="fa fa-circle" style="color:<?php echo $row['color']; ?>"></i> <?php echo $row['name'] ?> </span>
                    <?php }
                } ?>
                <br>
                <i>Note: Shaded circles have more data, hover on name to offset and increase font</i>
                <hr>
            </div>

        <div class="col-md-12 mt0" style="background: #FFF;" id="d3-tree-model-<?php echo $this->uniqId ?>"></div>
    </div>
<?php } ?>
<?php echo isset($this->basicTree) ? $this->basicTree : ''; ?>
<?php echo isset($this->collapsibleTree) ? $this->collapsibleTree : ''; ?>

<script type="text/javascript">
    var IS_LOAD_D3_SCRIPT = false;
    
    $(document).ready(function () {
        $.ajax({
            type: 'post',
            url: 'mdlayout/getJsonData',
            data: {legendData: <?php echo json_encode($this->legendData); ?>},
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (response) {
                if (response.status === 'success' && typeof response.data2 !== 'undefined' && response.data2.length != 0) {
                    D3TreeCollapsible_<?php echo $this->uniqId ?>.init(response.data2);
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
        console.log(templateId, id, criteria, name, color);
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