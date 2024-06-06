<div class="bg-white">
    <div class="card-body form" id="mainRenderDiv" style="">
        <div class="xs-form main-action-meta bp-banner-container " id="bp-window-<?php echo $this->uniqId ?>" data-meta-type="process" data-process-id="<?php echo $this->uniqId ?>" data-bp-uniq-id="<?php echo $this->uniqId ?>">
            <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'appbp-air-sms-form-' . $this->uniqId, 'method' => 'post')); ?>
                <div class="meta-toolbar">
                    <span class="font-weight-bold text-uppercase text-gray2"><?php echo $this->title ?></span>
                    <div class="ml-auto">
                        <button type="button" class="btn btn-sm btn-circle btn-success air-sms-btn-save-<?php echo $this->uniqId ?> " onclick="">
                            <i class="fa fa-save"></i> <?php echo $this->lang->line('save_btn'); ?>
                        </button>
                    </div>
                </div>
                <div class="clearfix w-100"></div>
                <!-- banner -->
                <div class="row">
                    <div class="col-md-12 center-sidebar">
                        <div class="table-scrollable table-scrollable-borderless bp-header-param">
                            <table class="table table-sm table-no-bordered bp-header-param">
                                <tbody>
                                    <tr>
                                        <td class="text-right middle" data-cell-path="code" style="width: 23%">
                                            <label for="param[id]" data-label-path="id"><span class="required">*</span>Sms file: </label>
                                        </td>
                                        <td class="middle" data-cell-path="code" style="width: 27%" colspan="">
                                            <div data-section-path="code">
                                                <?php echo Form::file(array('name' => 'airSmsFile[]', 'multiple'=>'multiple', 'id' => 'airSmsFile', 'class' => 'form-control-sm', 'style' => 'border: 1px solid #CCC;', 'required' => 'required')); ?>
                                            </div>
                                        </td>
                                        <td class="text-right middle" data-cell-path="id" style="width: 23%"></td>
                                        <td class="middle" data-cell-path="id" style="width: 27%" colspan=""></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="clearfix w-100"></div>
            <?php echo Form::close(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">

    $('body').on('click', '.air-sms-btn-save-<?php echo $this->uniqId ?>', function () {
        $('#appbp-air-sms-form-<?php echo $this->uniqId ?>').validate({errorPlacement: function () {}});
        if ($('#appbp-air-sms-form-<?php echo $this->uniqId ?>').valid()) {
            $('#appbp-air-sms-form-<?php echo $this->uniqId ?>').ajaxSubmit({
                type: 'post',
                url: 'mdintegration/saveAirSmsSystem',
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        message: plang.get('msg_saving_block'),
                        boxed: true
                    });
                },
                success: function (data) {
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    
                    Core.unblockUI();
                },
                error: function (jqXHR, exception) {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: 'Алдаатай өгөгдөл байна. Шалгаад дахин оролдлого хийнэ үү',
                        type: 'error',
                        sticker: false
                    });
                    return;
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
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
        } else {
            
            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: 'Заавал бөглөх талбараа бөглөнө үү',
                type: 'error',
                sticker: false
            });
        }
    });

</script>