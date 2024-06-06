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
            <span class="caption-subject">Тайлангийн загвар</span>
        </div>
        <div class="tools">
            <a title="" data-original-title="" href="javascript:;" class="reload"></a>
        </div>
    </div>

    <div class="card-body xs-form"> 
        <div class='row'>
            <div class="col-md-9 template-section">
                <div class='row'>
                    <fieldset>
                        <legend>Тайлангийн толгой</legend>
                        <textarea class="ckeditor" id="editor1"></textarea>
                    </fieldset>
                </div>
                <div class='row'>
                    <fieldset>
                        <legend>Тайлангийн хөл</legend>
                        <textarea class="ckeditor" id="editor2"></textarea>
                    </fieldset>
                </div>
            </div>
            <div class="col-md-3" style="padding-left: 5px" >
                <?php echo Form::create(array('role' => 'form', 'id' => 'mForm3', 'method' => 'POST')) ?>
                <?php echo Form::hidden(array('name' => 'templateId', 'id' => 'templateId', 'value' => $this->row['templateId'], 'class' => 'form-control')); ?>
                <fieldset>
                    <legend>Ерөнхий мэдээлэл</legend>
                    <div class="form-body">
                        <div class="form-group row fom-row">
                            <?php echo Form::label(array('class' => 'col-form-label', "text" => 'Загварын нэр')); ?>   
                            <?php echo Form::text(array('name' => 'templateName', 'id' => 'templateName', 'class' => 'form-control')); ?>
                        </div>
                        <div class="form-group row fom-row">
                            <?php echo Html::anchor('javascript:;', '<i class="fa fa-save"></i> ' . "Хадгалах" . '', array('title' => "Хадгалах", 'class' => 'btn btn-sm blue', 'onclick' => 'saveReportTemplate()')); ?>
                        </div>

                    </div>
                </fieldset>
                <?php echo Form::close(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    var dgPreviewReport = '#dgPreviewReport';
    $(document).ready(function () {

    });
    function saveReportTemplate()
    {
        console.log(CKEDITOR.instances['editor1'].getData().replace(/^\s+|\s+$/gm, ''));
        //return;
        $.ajax({
            type: 'post',
            url: 'Rmreport/saveReportTemplate',
            dataType: "json",
            data: {
                values: $("#mForm3").serialize(),
                headerHtml: CKEDITOR.instances['editor1'].getData().replace(/^\s+|\s+$/gm, ''),
                footerHtml: CKEDITOR.instances['editor2'].getData().replace(/^\s+|\s+$/gm, '')
            },
            beforeSend: function () {
            },
            success: function (data) {
                $.unblockUI();
                if (data.status === 'success') {

                    document.getElementById("templateId").value = data.templateId;
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