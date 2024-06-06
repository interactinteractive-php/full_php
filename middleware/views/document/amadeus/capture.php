<div class="bg-white">
    <div class="card-body form" id="mainRenderDiv" style="">
        <div class="xs-form main-action-meta bp-banner-container " id="bp-window-<?php echo $this->uniqId ?>" data-meta-type="process" data-process-id="<?php echo $this->uniqId ?>" data-bp-uniq-id="<?php echo $this->uniqId ?>">
            <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'appbp-sc-sms-form-' . $this->uniqId, 'method' => 'post')); ?>
                <?php echo Form::hidden(array('name' => 'smsTypeId', 'value' => $this->smsTypeId)); ?>
                <?php  if ($this->seeBtn) { ?>
                    <div class="meta-toolbar">
                        <span class="font-weight-bold text-uppercase text-gray2"><?php echo $this->title ?></span>
                        <div class="ml-auto">
                            <button type="button" class="btn btn-sm btn-circle btn-success sc-sms-btn-save " onclick="">
                                <i class="fa fa-save"></i> <?php echo $this->lang->line('save_btn'); ?>
                            </button>
                        </div>
                    </div>
                    <div class="clearfix w-100"></div>
                <?php } ?>
                <!-- banner -->
                <div class="row">
                    <div class="col-md-12 center-sidebar">
                        <div class="table-scrollable table-scrollable-borderless bp-header-param">
                            <table class="table table-sm table-no-bordered bp-header-param">
                                <tbody>
                                    <tr>
                                        <td class="text-right middle" data-cell-path="screenCaptureSms" style="width: 13%">
                                            <label for="param[screenCaptureSms]" data-label-path="screenCaptureSms"><span class="required">*</span>Sms: </label>
                                        </td>
                                        <td class="middle" data-cell-path="code" style="width: 77%" colspan="">
                                            <div data-section-path="code">
                                                <?php echo Form::textArea(array('name' => 'screenCaptureSms', 'id' => 'screenCaptureSms', 'class' => 'form-control-sm', 'style' => 'border: 1px solid #CCC; width: 100%; height: 300px; resize: none;', 'required' => 'required')); ?>
                                            </div>
                                        </td>
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
<?php  if ($this->seeBtn) { ?>
    <script type="text/javascript">

        $('body').on('click', '.sc-sms-btn-save', function () {
            $('#appbp-sc-sms-form-<?php echo $this->uniqId ?>').validate({errorPlacement: function () {}});
            if ($('#appbp-sc-sms-form-<?php echo $this->uniqId ?>').valid()) {
                $('#appbp-sc-sms-form-<?php echo $this->uniqId ?>').ajaxSubmit({
                    type: 'post',
                    url: 'mddoc/saveScSms',
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
<?php } ?>