<div class="erl-parent col-md-12" id="windowid-erl-<?php echo $this->uniqId; ?>" data-id="" data-name="" data-prepare-file-count="">    
    <?php echo Form::create(array('class' => 'form-horizontal mt-2', 'id' => 'saveform_' . $this->uniqId, 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
    <?php echo Form::hidden(array('name' => 'templateId', 'value' => '1597978136715043')) ?>
    <?php echo Form::hidden(array('name' => 'isReturnSuccessRows', 'value' => '1')) ?>
    <div class="table-toolbar exfile" <?php echo issetParam($this->ishide) === 'true' ? 'style="display: none;"' : '' ?>>
        <div class="row">
            <div class="col-md-7">
                <div class="btn-group btn-group-devided">
                    <input type="file" name="excelFile" />
                    <a class="btn btn-circle btn-sm blue" onclick="saveImport_<?php echo $this->uniqId ?>()" href="javascript:;" type="button">
                        <i class="icon-download7 font-size-12"></i> Import
                    </a>
                    <a class="btn btn-circle btn-sm warning ml15" onclick="downloadSimpleFile_<?php echo $this->uniqId ?>()" href="javascript:;" type="button">
                        <i class="fa fa-download font-size-12"></i> Эксель загвар татах
                    </a>
                </div>
            </div>
            <div class="col-md-5">
            </div>
        </div>
    </div>
    <div id="filePreview_<?php echo $this->uniqId ?>">
        <table style="table-layout: fixed; width: 100%; border: 1px #999 solid;" data-table-path="empty">
            <tbody>
                <tr>
                    <td>
                        <div class="org-choice"style="background: #ffe8436b; border: none; text-align: center;">
                            <div class="form-group mb-0" style="font-size: 12px !important;">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label align-items-center">
                                        <i class="fa fa-info-circle"></i> <span class="ml5"><?php echo issetParam($this->ishide) === 'true' ? 'Импорт хийгдсэн байна.' : 'Хоосон' ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="bp-process col-md-12 d-none"></div>
    </div>
    <?php echo Form::close(); ?>
</div>
<script type="text/javascript">

    $(function () {

    });

    function saveImport_<?php echo $this->uniqId ?>() {
        $('#filePreview_<?php echo $this->uniqId ?>').find('table[data-table-path="empty"]').removeClass('d-none');
        $('#filePreview_<?php echo $this->uniqId ?>').find('.bp-process').addClass('d-none');
        $('#filePreview_<?php echo $this->uniqId ?>').find('.bp-process').empty(); 
        
        $("#saveform_<?php echo $this->uniqId ?>").validate({ errorPlacement: function() {} });
        if ($("#saveform_<?php echo $this->uniqId ?>").valid()) {
            $("#saveform_<?php echo $this->uniqId ?>").ajaxSubmit({
                type: 'post',
                url: "mddoc/importDataCvl/",
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        message: 'Түр хүлээнэ үү',
                        boxed: true
                    });
                },
                success: function (response) {
                    PNotify.removeAll();
                    new PNotify({
                        title: response.status,
                        text: response.message,
                        type: response.status,
                        sticker: false
                    });

                    if (response.status === 'error' && typeof response.uniqId !== 'undefined') {
                        $.fileDownload(URL_APP + 'mddatamodel/errorExcelFileDownload', {
                            httpMethod: 'post',
                            data: { uniqId: response.uniqId, fileExtension: 'xlsx' }
                        }).done(function() {
                        }).fail(function(response) {
                            new PNotify({
                                title: 'Error',
                                text: response,
                                type: 'error',
                                sticker: false
                            });
                        });
                    
                        Core.unblockUI();
                    } 
                    else {
                        if (response.status === 'success') {
                            $("#saveform_<?php echo $this->uniqId ?>").find('.exfile').hide();
                            var fillDataParams = 'isSuccessExcel=1&serviceBookId=<?php echo $this->id ?>&companyName=<?php echo $this->companyname ?>&companyKeyId=<?php echo $this->companykeyid ?>&defaultGetPf=1';
                            $.ajax({
                                type: 'post',
                                url: 'mdwebservice/callMethodByMeta',
                                data: { 
                                    metaDataId: '1598258465356',
                                    dmMetaDataId: '1567584774954',
                                    isDialog: false,
                                    isGetConsolidate: false,
                                    signerParams: false,
                                    batchNumber: false,
                                    openParams: '{"callerType":"SCL_POSTS_MAIN_TYPE_DV"}',
                                    isBpOpen: 0,
                                    fillDataParams: fillDataParams
                                },
                                dataType: 'json',
                                beforeSend: function () {
                                    Core.blockUI({
                                        message: 'Loading...',
                                        boxed: true
                                    });
                                },
                                success: function (data) {

                                    $('#filePreview_<?php echo $this->uniqId ?>').find('table[data-table-path="empty"]').addClass('d-none');
                                    $('#filePreview_<?php echo $this->uniqId ?>').find('.bp-process').removeClass('d-none');
                                    $('#filePreview_<?php echo $this->uniqId ?>').find('.bp-process').empty().append(data.Html).promise().done(function () {Core.unblockUI();});
                                },
                                error: function (jqXHR, exception) {
                                    Core.showErrorMessage(jqXHR, exception);
                                    Core.unblockUI();
                                }
                            });
                            
                        } 
                        else {
                            Core.unblockUI();
                        }
                    }
                    
                    $('form[id="saveform_<?php echo $this->uniqId ?>"]').find('input[name="excelFile"]').val('');
                },
                error: function (jqXHR, exception) {
                    Core.showErrorMessage(jqXHR, exception);
                    Core.unblockUI();
                    $('form[id="saveform_<?php echo $this->uniqId ?>"]').find('input[name="excelFile"]').val('');
                }
            });
        }
    }
    
    function downloadSimpleFile_<?php echo $this->uniqId ?> () {
        $.fileDownload(URL_APP + 'mddatamodel/base64Download', {
            httpMethod: 'post',
            data: {
                vId: '1597978136715043', 
                vTable: 'imp_excel_template'
            }
        }).done(function() {
            Core.unblockUI();
        }).fail(function(response){
            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: response,
                type: 'error',
                sticker: false
            });
            Core.unblockUI();
        });
    }
</script>