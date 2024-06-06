<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="card light shadow">
    <style>
        .template-section{
            padding-left: 25px;
            padding-right: 25px;
        }     
        .card {
            margin-bottom: 5px;
        }
    </style>
    <div class="card-header card-header-no-padding header-elements-inline">
        <div class="card-title">
            <i class="fa fa-cogs"></i>
            <span class="caption-subject">Тайлан:</span>
        </div>
        <div class="caption buttons ml10">
            <?php echo Html::anchor('javascript:;', '<i class="fa fa-save"></i> ' . "Хадгалах" . '', array('title' => "Хадгалах", 'class' => 'btn btn-circle btn-sm btn-success saveSaleRefund', 'onclick' => 'saveChart()')); ?>
        </div>
    </div>

    <div class="card-body xs-form"> 
        <div class='row'>
            <div class="col-sm-9 template-section">
                <div class="row">

                    <div id="filter">
                    </div>
                </div>

                <div class='row'>
                    <?php echo Form::create(array('role' => 'form', 'id' => 'reportForm', 'method' => 'POST')) ?>                        


                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-toolbar float-right">
                                <div class="btn-group btn-group-solid">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="template-section">
                        <div class="row" id="reportPreview">
                            <div id='prvReportHeader'>
                            </div>
                            <div class="table-scrollable" style="max-height: 450px; overflow-y: scroll;">
                                <div id="prvReportSource">
                                </div>
                            </div>
                            <div id="prvReportFooter">
                            </div>
                        </div>
                    </div>
                    <?php echo Form::close(); ?>
                </div>
            </div>
            <div class="col-sm-3" style="padding-left: 5px">
                <?php echo Form::create(array('role' => 'form', 'id' => 'mForm4', 'method' => 'POST')) ?>
                <?php echo Form::hidden(array('name' => 'templateChart', 'id' => 'templateChart', 'value' => $this->row['chartType'], 'class' => 'form-control')); ?>
                <?php echo Form::hidden(array('name' => 'rmchartId', 'id' => 'rmchartId', 'value' => $this->row['id'], 'class' => 'form-control')); ?>
                <fieldset>

                    <div class="form-body">

                        <div class="form-group row fom-row">
                            <?php echo Form::label(array('class' => 'col-form-label', "text" => 'Чартны нэр')); ?>   
                            <div class="input-group">
                                <?php echo Form::text(array('name' => 'rmchartName', 'id' => 'rmchartName', 'value' => $this->row['chartName'], 'class' => 'form-control')); ?>
                                <span class="input-group-btn">
                                    <button type="button" class="btn blue" onclick="showChartList();">
                                        <i class="fa fa-search"></i>
                                    </button>                                         
                                </span>
                            </div>
                        </div>


                        <?php
                        // if ($this->row['modelId'] == 0) {
                        echo '<div class = "form-group row fom-row">';
                        echo Form::label(array('class' => 'col-form-label', "text" => 'Data mart'));
                        echo Form::select(array('name' => 'modelId', 'id' => 'modelId', 'data' => $this->modelList, 'op_value' => 'id', 'value' => $this->row['modelId'], 'op_text' => 'modelName', 'selectedIndex' => "2", 'class' => 'form-control', 'onchange' => 'selectModel()'));
                        echo '</div>';
//                        } else {
//                            echo Form::hidden(array('name' => 'modelId', 'id' => 'modelId', 'value' => $this->row['modelId'], 'class' => 'form-control'));
//                        }
                        ?>
                        <div class="form-group row fom-row">
                            <?php echo Form::label(array('class' => 'col-form-label', "text" => 'Утга болох багана')); ?>   
                            <select style="height: 200px" size="20" name="valueColumns" id="valueColumns" class="form-control"  onChange='reloadChart()'>
                            </select>
                        </div>

                        <div class="form-group row fom-row" >
                            <?php echo Form::label(array('class' => 'col-form-label', "text" => 'Загвар')); ?>   
                            <?php
                            echo '<ul class="grid cs-style-2  list-view0" id="main-item-container">';
                            foreach ($this->chartTemplateList as $key) {
                                echo
                                '<li style="width: 124px;" class="meta">	
                                    <figure style="width: 122px;" class="directory">
                                        <a class="folder-link" onclick="selectTemplate(\'' . $key['code'] . '\')">
                                            <div class="img-precontainer">
                                                <div class="img-container directory"><span></span>
                                                    <img class="directory-img" src="' . $key['icon'] . '">
                                                </div>
                                            </div>
                                            <div class="img-precontainer-mini directory">
                                                <div class="img-container-mini">
                                                    <span></span>
                                                    <img class="directory-img" src="' . $key['icon'] . '">
                                                </div>
                                            </div>
                                        </a>
                                        <div class="box">
                                            <h4 class="ellipsis">
                                                <a class="folder-link" title="Харилцагч" onclick="selectTemplate(\'' . $key['code'] . '\')"> ' . $key['name'] . ' </a>
                                            </h4>
                                        </div>	
                                        <div class="file-date">2015/04/20 00:00</div>
                                        <div class="file-user">Админ</div>
                                    </figure>
                                </li>';
                            }
                            echo '</ul>';
                            ?>
                        </div>

                    </div>
                </fieldset>
            </div>
        </div>
        <a id="dlink"  style="display:none;"></a>
        <div class="form-actions mt15 form-actions-btn">
            <div class="row">
                <div class="col-md-offset-3 col-md-9">
                    <?php echo Html::anchor('javascript:;', '<i class="fa fa-save"></i> ' . "Хадгалах" . '', array('title' => "Хадгалах", 'class' => 'btn btn-circle btn-sm btn-success saveSaleRefund', 'onclick' => 'saveChart()')); ?>
                </div>
            </div>
        </div>
        <?php echo Form::close(); ?>
    </div>
    <div id="chartListDialog"></div>
</div>

<script type="text/javascript">
    var dgPreviewReport = '#dgPreviewReport';
    var chartListDialog = '#chartListDialog';
    var result = null;
    $(document).ready(function () {
        selectModel();
        reloadModel();
    });

    function showChartList(){
        $(chartListDialog).empty();
        $.ajax({
            type: 'post',
            url: 'Rmreport/chartList',
            dataType: "json",
            data: {
            },
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $(chartListDialog).empty().html(data.Html);
                $(chartListDialog).dialog({
                    autoOpen: false,
                    title: data.dialogTitle,
                    width: 900,
                    resizable: false,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(chartListDialog).empty().dialog('close');
                    },
                    buttons: [{text: data.close_btn, class: 'btn btn-sm', click: function () {
                        $(chartListDialog).dialog('close');
                    }}]
                });
                $(chartListDialog).dialog('open');
                Core.unblockUI("body");
            },
            error: function (msg) {
                Core.unblockUI("body");
                console.log(msg);
            }
        }).done(function () {
            Core.initAjax();
        });
    }

    function printPDF(){
        var pdf = new jsPDF('p', 'pt', 'a4');
        var options = {
            pagesplit: true
        };
        pdf.addHTML($("#reportPreview"), options, function (){
            pdf.save("test.pdf");
        });
    }

    function exportExcel(){
        var rep = document.getElementById('reportPreview');
        console.log(rep.innerHTML);
        var inputs = "<input type='hidden' name='html' value='" + rep.innerHTML.replace(/(\r\n|\n|\r)/gm, '').replace(' ', '') + "' />";
        jQuery("<form action='Rmreport/export_excel' method='post' _target>" + inputs + "</form>").appendTo('body').submit().remove();
    }

    function exportPDF(){
        printPDF();
    }
    
    function reloadFilter(){
        $('#filter').empty();

        reloadModel();
        $.ajax({
            type: 'post',
            url: 'Rmreport/getFilterArea',
            dataType: "json",
            data: {
                values: $("#mForm4").serialize()
            },
            beforeSend: function () {
            },
            success: function (result) {
                $('#filter').empty().html(result.Html);
            },
            error: function (msg) {
            }
        }).done(function () {
            Core.initAjax();
        });
    }

    function selectModel()
    {
        reloadFilter();
        $('#prvReportSource').empty();
        $('#valueColumns').empty();
        $.ajax({
            type: 'post',
            url: 'Rmreport/getReportSource',
            dataType: "json",
            data: {values: $("#mForm4").serialize(),
                filters: $("#filterForm").serialize()

            },
            beforeSend: function () {
                Core.blockUI({
                    target: "form#reportForm",
                    animate: true
                });
            },
            success: function (res) {
                result = res;

                for (var i = 0; i < res.cols.length; i++)
                {
                    var selected = "";
                    if (<?php echo $this->row['valueColumnId'] ?> == 0)
                    {
                        if (i == 0) {
                            selected = " selected ";
                        }
                    }
                    else
                    {
                        if (<?php echo $this->row['valueColumnId'] ?> == res.cols[i].id)
                        {
                            selected = " selected ";
                        }
                    }
                    $('#valueColumns').append("<option " + selected + " value='" + res.cols[i].id + "'>" + res.cols[i].field + "-" + res.cols[i].title + "</option>");
                }

                reloadChart();
                Core.unblockUI("form#reportForm");
            },
            error: function (msg) {
                Core.unblockUI("form#reportForm");
            }
        });
    }

    function reloadModel()
    {
        $('#prvReportSource').empty();
        $.ajax({
            type: 'post',
            url: 'Rmreport/getReportSource',
            dataType: "json",
            data: {values: $("#mForm4").serialize(),
                filters: $("#filterForm").serialize()

            },
            beforeSend: function () {
                Core.blockUI({
                    target: "form#reportForm",
                    animate: true
                });
            },
            success: function (res) {
                result = res;

                reloadChart();
                Core.unblockUI("form#reportForm");
            },
            error: function (msg) {
                Core.unblockUI("form#reportForm");
            }
        });
    }

    function selectTemplate(code) {
        document.getElementById('templateChart').value = code;
        reloadChart();
    }

    function reloadChart()
    {
        //console.log(document.getElementById('valueColumns').value);

        if (result != null)
        {
            if (document.getElementById('templateChart').value == 'table')
            {
                loadRmReport('#prvReportSource', result.rows, result.cols, result.facts, result.data);
                setCustomHeader(result.headerHtml);
            }
            else
            {
                loadRmChart('#prvReportSource', result.rows, result.cols, result.facts, result.data, document.getElementById('templateChart').value, document.getElementById('valueColumns').value);
            }
        }
    }

    function saveChart()
    {
        //  console.log(CKEDITOR.instances['editor1'].getData().replace(/^\s+|\s+$/gm, ''));
        //return;
        $.ajax({
            type: 'post',
            url: 'Rmreport/saveChart',
            dataType: "json",
            data: {
                values: $("#mForm4").serialize(),
            },
            beforeSend: function () {
            },
            success: function (data) {
                PNotify.removeAll();
                if (data.status === 'success') {
                    document.getElementById("rmchartId").value = data.chartId;
                    location.href = "rmreport/chart";
                    new PNotify({
                        title: 'Success',
                        text: data.message,
                        type: 'success',
                        sticker: false
                    });
                } else {
                    new PNotify({
                        title: 'Error',
                        text: data.message,
                        type: 'error',
                        sticker: false
                    });
                }
                Core.unblockUI();
            },
            error: function (msg) {
                new PNotify({
                    title: 'Error',
                    text: msg,
                    type: 'error',
                    sticker: false
                });
            }
        });
    }
</script>