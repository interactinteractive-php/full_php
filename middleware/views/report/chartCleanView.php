<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<style>
    .caption {
        text-align: left;
        color:#333;
    }
    
    .card-title {
        border-bottom: 1px solid #ccc;
        margin-bottom: 10px;
        padding-bottom: 10px;
    }
</style>
<div class="card-header card-header-no-padding header-elements-inline">
    <div class="card-title">
            <span class="caption-subject"><?php echo $this->row['chartName'] ?></span>
    </div>
    <div class="tools">
            <a href="javascript:;" data-original-title="" class="expand" title=""></a>
            <a href="javascript:;" data-original-title="" onclick="reloadChart<?php echo $this->row['id'] ?>()" class="fullscreen" title=""></a>
    </div>
</div>
<div class="no-padding m-0">
    <div id="filter" style="display: none;"></div>
    <div style="display: none;">
        <?php echo Form::create(array('role' => 'form', 'id' => 'mForm', 'method' => 'POST')) ?>
        <?php echo Form::hidden(array('name' => 'templateChart', 'id' => 'templateChart', 'value' => $this->row['chartType'], 'class' => 'form-control')); ?>
        <?php echo Form::hidden(array('name' => 'rmchartId', 'id' => 'rmchartId', 'value' => $this->row['id'], 'class' => 'form-control')); ?>
        <?php echo Form::hidden(array('name' => 'modelId', 'id' => 'modelId', 'value' => $this->row['modelId'], 'class' => 'form-control')); ?>
        <?php echo Form::hidden(array('name' => 'valueColumns', 'id' => 'valueColumns', 'value' => $this->row['valueColumnId'], 'class' => 'form-control')); ?>
    </div>                      
    <div id="prvReportSource"></div>
</div>

<script>
    var result<?php echo $this->row['id'] ?> = null;
    $(document).ready(function () {
        reloadFilter<?php echo $this->row['id'] ?>();
        reloadModel<?php echo $this->row['id'] ?>();
    });

    function reloadFilter<?php echo $this->row['id'] ?>()
    {
        //alert($("<?php echo $this->row['rootElement'] ?> > div > div >  #mForm").serialize());

        $('<?php echo $this->row['rootElement'] ?> > #filter').empty();
        reloadModel<?php echo $this->row['id'] ?>();
        $.ajax({
            type: 'post',
            url: 'Rmreport/getFilterArea',
            dataType: "json",
            data: {
                values: $("<?php echo $this->row['rootElement'] ?> > div > div > #mForm").serialize(),
                chartId: <?php echo $this->row['id'] ?>
            },
            beforeSend: function () {
            },
            success: function (result) {
                $('<?php echo $this->row['rootElement'] ?> > div > div > #filter').empty().html(result.Html);
            },
            error: function (msg) {
            }
        }).done(function () {
            Core.initAjax();
        });
    }

    function reloadModel<?php echo $this->row['id'] ?>()
    {
        $('<?php echo $this->row['rootElement'] ?> > #prvReportSource').empty();
        $.ajax({
            type: 'post',
            url: 'Rmreport/getReportSource',
            dataType: "json",
            data: {
                values: $("<?php echo $this->row['rootElement'] ?> > div > div > #mForm").serialize(),
                filters: $("#filterForm<?php echo $this->row['id'] ?>").serialize()
            },
            beforeSend: function () {
                Core.blockUI({
                    target: "form#reportForm",
                    animate: true
                });
            },
            success: function (res) {
                result<?php echo $this->row['id'] ?> = res;

                reloadChart<?php echo $this->row['id'] ?>();
                Core.unblockUI("form#reportForm");
            },
            error: function (msg) {
                Core.unblockUI("form#reportForm");
            }
        });
    }

    function reloadChart<?php echo $this->row['id'] ?>()
    {

        //console.log($('<?php echo $this->row['rootElement'] ?> > div > div > #mForm > #valueColumns').val());
        //console.log(result<?php echo $this->row['id'] ?>);
        var result = result<?php echo $this->row['id'] ?>;
        if (result != null)
        {
            if ('<?php echo $this->row['chartType'] ?>' == 'table')
            {
                $('<?php echo $this->row['rootElement'] ?> > div > #prvReportSource').attr('style', 'max-height: 450px; overflow-y: scroll;');

                loadRmReport('<?php echo $this->row['rootElement'] ?> > div > #prvReportSource', result.rows, result.cols, result.facts, result.data);
                setCustomHeader(result.headerHtml);
            }
            else
            {
                loadRmChart('<?php echo $this->row['rootElement'] ?> > div > #prvReportSource', result.rows, result.cols, result.facts, result.data, $('<?php echo $this->row['rootElement'] ?> > div > div > #mForm > #templateChart').val(), $('<?php echo $this->row['rootElement'] ?> > div > div > #mForm > #valueColumns').val());
            }
        }

    }
</script>