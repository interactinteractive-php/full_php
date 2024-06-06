<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="card light shadow">
    <div class="card-header card-header-no-padding header-elements-inline">
        <div class="card-title">
            <i class="fa fa-cogs"></i>
            <span class="caption-subject">Тайлангийн өгөгдөл</span>
        </div>
        <div class="caption buttons ml10">
            <?php echo Html::anchor('javascript:;', '<i class="fa fa-save"></i> ' . "Хадгалах" . '', array('title' => "Хадгалах", 'class' => 'btn btn-circle btn-sm green saveSaleRefund', 'onclick' => 'saveReportModel()')); ?>
            <?php echo Html::anchor('javascript:;', '<i class="fa"></i> ' . "Цуцлах" . '', array('title' => "Цуцлах", 'class' => 'btn btn-circle btn-sm default cancelSaleRefund', 'onclick' => '')); ?>
        </div>
        <div class="tools">
            <a title="" data-original-title="" href="javascript:;" class="reload"></a>
        </div>
    </div>

    <div class="card-body xs-form"> 
        <?php echo Form::create(array('role' => 'form', 'id' => 'mForm', 'method' => 'POST', 'class' => "form-horizontal")) ?>
        <?php echo Form::hidden(array('name' => 'modelId', 'id' => 'modelId', 'value' => $this->row['modelId'], 'class' => 'form-control')); ?>
        <fieldset>
            <legend>Ерөнхий мэдээлэл</legend>
            <div class="row column-container-row">

                <div class="form-body">
                    <div class="row column-container-col">

                        <div class="col-md-4 form-group row fom-row">
                            <?php echo Form::label(array('class' => 'col-md-4 col-form-label', "text" => 'Хүснэгт')); ?>   
                            <div class="col-md-8">
                                <?php echo Form::select(array('name' => 'tableName', 'id' => 'tableName', 'data' => $this->tables, 'op_value' => 'id', 'value' => $this->row['viewName'], 'op_text' => 'name', 'class' => 'form-control', 'onchange' => 'reloadColumns()')); ?>
                            </div>
                        </div>

                        <div class="col-sm-4 form-group row fom-row">
                            <?php echo Form::label(array('class' => 'col-sm-4 col-form-label', "text" => 'Тайлангийн нэр')); ?>   
                            <div class="col-md-8">
                                <div class="input-group">
                                    <?php echo Form::text(array('name' => 'reportModelName', 'id' => 'reportModelName', 'value' => $this->row['modelName'], 'class' => 'form-control')); ?>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn blue" onclick="showReportModelList();">
                                            <i class="fa fa-search"></i>
                                        </button>                                         
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class='col-md-12 column-container-row'>
                        <div class="col-md-12 column-container-col">
                            <fieldset>
                                <legend><?php echo $this->lang->line('metadata_view'); ?></legend>
                                <div class="table-scrollable">
                                    <div id="prvReportSource">
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="form-actions mt15 form-actions-btn">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <?php echo Html::anchor('javascript:;', '<i class="fa fa-save"></i> ' . "Хадгалах" . '', array('title' => "Хадгалах", 'class' => 'btn btn-circle btn-sm green saveSaleRefund', 'onclick' => 'saveReportModel()')); ?>
                            <?php echo Html::anchor('javascript:;', '<i class="fa"></i> ' . "Цуцлах" . '', array('title' => "Цуцлах", 'class' => 'btn btn-circle btn-sm default cancelSaleRefund', 'onclick' => '')); ?>
                        </div>
                    </div>
                </div>
                <?php echo Form::close(); ?>
        </fieldset>
    </div>



    <div class="hidden" >
        <div id="detailDialog">
            <form id="detailForm" method="POST" role="form">
                <div class="panel-body">
                    <div class="form-body">
                        <div class="row dialog">
                            <div class="col-sm-12 form-group row fom-row">
                                <label class="col-sm-4 col-form-label" for="fieldName">Талбарын нэр:</label>   
                                <div class="col-sm-8">
                                    <input id="fieldName" name="fieldName" class="form-control" type="text" value=":fieldName">
                                </div>
                            </div>
                        </div>
                        <div class="row dialog">
                            <div class="col-sm-12 form-group row fom-row">
                                <label class="col-sm-4 col-form-label" for="fieldHeader">Баганы нэр:</label>   
                                <div class="col-sm-8">
                                    <input id="fieldHeader" name="fieldHeader" class="form-control" type="text" value=":fieldHeader">
                                </div>
                            </div>
                        </div>

                        <div class="row dialog">
                            <div class="col-sm-12 form-group row fom-row">
                                <label class="col-sm-4 col-form-label" for="fieldHeader">Харагдах эсэх:</label>   
                                <div class="col-sm-8">
                                    <input type="checkbox" id="isVisible" name="isVisible"  value="true" checked>
                                </div>
                            </div>
                        </div>

                        <div class="row dialog">
                            <div class="col-sm-12 form-group row fom-row">
                                <label class="col-sm-4 col-form-label" for="fieldFormat">Хөлийн утга</label>   
                                <div class="col-sm-8">
                                    <select id="fieldFormat" name="fieldFormat" class="form-control" value=":fieldFormat">
                                        <option value="">- Сонгох -</option>
                                        <option value="SUM">Нийлбэр</option>
                                        <option value="COUNT">Мөрийн тоо</option>
                                        <option value="AVG">Дундаж</option>
                                    </select>  
                                </div>
                            </div>
                        </div>
                        <div class="row dialog">
                            <div class="col-sm-12 form-group row fom-row">
                                <label class="col-sm-4 col-form-label" for="fieldAlign">Зэрэгцүүлэлт</label>   
                                <div class="col-sm-8">
                                    <select id="fieldAlign" name="fieldAlign" class="form-control" value="left">
                                        <option value="">- Сонгох -</option>
                                        <option value="left">Зүүн</option>
                                        <option value="right">Баруун</option>
                                        <option value="center">Төв</option>
                                    </select>  
                                </div>
                            </div>
                        </div>
                        <div class="row dialog">
                            <div class="col-sm-12 form-group row fom-row">
                                <label class="col-sm-4 col-form-label" for="fieldMask">Төрөл</label>   
                                <div class="col-sm-8">
                                    <select id="fieldMask" name="fieldMask" class="form-control" value="text">
                                        <option value="">- Сонгох -</option>
                                        <option value="text">Текст</option>
                                        <option value="num">Тоо</option>
                                        <option value="date">Огноо</option>
                                        <option value="image">Зураг</option>
                                    </select>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="hidden" >
        <div id="detailFilterDialog">
            <form id="detailFilterForm" method="POST" role="form">
                <div class="panel-body">
                    <div class="form-body">
                        <div class="row dialog">
                            <div class="col-sm-12 form-group row fom-row">
                                <div class="col-sm-12 input-group ">
                                    <label class="col-sm-3 col-form-label">Талбар</label>   
                                    <div class="col-sm-9">
                                        <select id="cmbfieldNames" name="cmbfieldNames" class="form-control">
                                            <option value="">- Сонгох -</option>
                                        </select>  
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row dialog">
                            <div class="col-sm-12 form-group row fom-row">
                                <label class="col-sm-4 col-form-label" for="fieldHeader">Баганы нэр</label>   
                                <div class="col-sm-8">
                                    <input id="filterFieldHeader" name="filterFieldHeader" class="form-control" type="text" value=":fieldHeader">
                                </div>
                            </div>
                        </div>

                        <div class="row dialog">
                            <div class="col-sm-12 form-group row fom-row">
                                <div class="col-sm-12 input-group ">
                                    <label class="col-sm-3 col-form-label">Мета</label>   
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="hidden" id="metaDataId" name="metaDataId">                                                
                                            <input type="text" id="metaDataName" name="metaDataName" class="form-control" readonly="readonly" required="required">                                                <span class="input-group-btn">
                                                <button type="button" class="btn blue" onclick="selectMetaData();">
                                                    <i class="fa fa-search"></i>
                                                </button>                                         
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="hidden" >
        <div id="headerEditDialog">
            <form id="headerEditForm" method="POST" role="form">
                <div class="panel-body">
                    <div class="form-body">
                        <textarea id="headerEditor" name="headerEditor"></textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="reportModelListDialog" >
    </div>
</div>
<style>
    .av-flds .drp-trgt{
        height:50px !important; 
        width:1000px !important; 
        overflow-x: scroll !important; 
        overflow-y: hide !important;
        padding-bottom:10px !important; 
    }
    
    .orb-dialog .orb-dialog-body{
        height:500px !important; 
        width:1000px !important; 
        overflow-x: scroll !important; 
        overflow-y: scroll !important;
        padding-bottom:10px !important; 
        padding-right:10px !important; 
    }
</style>
<script type="text/javascript">
    var fields = [];
    var dataSet = {
        aggregateFunc: 'sum',
        aggregateFuncName: 'Нийлбэр',
        formatFunc: function (value) {
            return value ? Number(value).toFixed(0) + ' $' : '';
        }
    }
    function reloadColumns()
    {
        $.ajax({
            type: 'post',
            url: 'Rmreport/getColumnList',
            dataType: "json",
            data: {values: $("#mForm").serialize()},
            beforeSend: function () {
            },
            success: function (data) {
                //allColumns = data;

                for (var i = 0; i < data.length; i++)
                {
                    fields.push({name: data[i].id, caption: data[i].id + " (" + data[i].fieldType + ")"});

                }
                generatePreview();
            },
            error: function (msg) {

                console.log(msg);
            }
        });
    }

    function generatePreview()
    {
        $.ajax({
            type: 'post',
            url: 'Rmreport/getAllList',
            dataType: "json",
            data: {
                rows: [],
                cols: [],
                filters: [],
                values: $("#mForm").serialize()
            },
            beforeSend: function () {
            }, success: function (result) {

                var config = {
                    dataSource: result,
                    canMoveFields: true,
                    dataHeadersLocation: 'columns',
                    theme: 'green',
                    width: 1110,
                    height: 645,
                    toolbar: {
                        visible: true
                    },
                    grandTotal: {
                        rowsvisible: false,
                        columnsvisible: false
                    },
                    subTotal: {
                        visible: true,
                        collapsed: true,
                        collapsible: true
                    },
                    rowSettings: {
                        subTotal: {
                            visible: true,
                            collapsed: true,
                            collapsible: true
                        }
                    },
                    columnSettings: {
                        subTotal: {
                            visible: false,
                            collapsed: true,
                            collapsible: true
                        }
                    },
                    fields: fields,
                    rows: [],
                    columns: [],
                    data: []
                };

                var elem = document.getElementById('prvReportSource');
                elem.innerHTML = "";
                var pgridwidget = new orb.pgridwidget(config);
                pgridwidget.render(elem);
            },
            error: function (msg) {
                console.log(msg);
            }
        });
    }
</script>


