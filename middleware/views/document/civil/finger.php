<div class="row civil_container<?php echo $this->uniqId ?>">
    <div class="col overflow-auto col-form bl-section" data-bl-col="1">
        <div class="card p-3 bl-sectioncode1-card alpha-info title-font-size-small">
            <div class="card-body" data-section-code="1">
                <fieldset>
                    <legend class="mb-1 pb-1">Иргэн хайх</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row align-items-start mb-1" data-cell-path="chRegisterNum">
                                <label class="col-form-label text-right w-25 col-md-3 pr-0 line-height-normal">Регистрийн дугаар <span class="label-colon">:</span>
                                </label>
                                <div class="col-md-9 col-form-control bp-header-param">
                                    <div data-section-path="chRegisterNum" style="">
                                        <input type="text" class="form-control form-control-sm stringInit" data-path="registerNum" title="Талбарыг заавал бөглөнө." placeholder="Регистрийн дугаар" data-regex="^[фцужэнгшүзкъйыбөахролдпячёсмитьвюещabcdefghijklmnopqrstuvwxyz]{2}[0-9]{8}$" data-regex-message="Регистийн дугаараа зөв оруулна уу!" data-inputmask-regex="^[фцужэнгшүзкъйыбөахролдпячёсмитьвюещabcdefghijklmnopqrstuvwxyz]{2}[0-9]{8}$" style="color:#000; border:1px #F110A0 solid; font-weight:bold; !important; width: 188px !important;">
                                    </div>
                                    <div class="btn btn-groups px-0">
                                        <button type="button" class="btn btn-sm btn-circle btn-info btn-search"><i class="fa fa-search"></i> Хайх</button>
                                        <button type="button" class="btn btn-sm btn-circle btn-default btn-clear"><i class="fa fa-trash"></i> Цэвэрлэх</button>
                                        <button type="button" class="btn btn-sm btn-circle btn-success btn-save"><i class="icon-checkmark-circle2"></i> Хадгалах</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="civil-card">
        <?php if (issetParamArray($this->registerData)) { ?>
            <form class="form" enctype="multipart/form-data" method="post">
                <input type="hidden" name="param[id]" value="<?php echo issetParam($this->id) ?>" >
            <?php
                foreach ($this->registerData as $key => $row) { ?>
                    <input type="hidden" name="param[crAfisFingerCheckGetDtlDv.mainRowCount][<?php echo $key ?>][]" value="" >
                    <input type="hidden" name="param[crAfisFingerCheckGetDtlDv.id][<?php echo $key ?>][]" value="<?php echo issetParam($row['id']) ?>" >
                    <div class="card p-3 bl-sectioncode1-card alpha-info title-font-size-small">
                    <div class="card p-3 bl-sectioncode1-card alpha-info title-font-size-small">
                        <div class="card-body" data-section-code="1">
                            <fieldset>
                                <legend class="mb-1 pb-1">Иргэн</legend>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless table-custome-civil">
                                            <tbody>
                                                <tr>
                                                    <td rowspan="6" style="width: 3cm;">
                                                        <img src="data:image/png;base64,<?php echo $row['fingerimage'] ?>" style="width: 3cm; height: 4cm">
                                                    </td>
                                                    <td class="text-right w-25">
                                                        CivilId <span class="label-colon">:</span>
                                                    </td>
                                                    <td class="w-50">
                                                        <input type="text" class="form-control form-control-sm" readonly value="<?php echo checkDefaultVal($row['civilid'], '...') ?>" >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right w-25">
                                                        Маягтын дугаар <span class="label-colon">:</span>
                                                    </td>
                                                    <td class="w-50">    
                                                        <input type="text" class="form-control form-control-sm" readonly value="<?php echo checkDefaultVal($row['sheetnum'], '...') ?>" >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right w-25">
                                                        Регистрийн дугаар <span class="label-colon">:</span>
                                                    </td>
                                                    <td class="w-50">    
                                                        <input type="text" class="form-control form-control-sm" readonly value="<?php echo checkDefaultVal($row['registernum'], '...') ?>" >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right w-25">
                                                        Ургийн овог <span class="label-colon">:</span>
                                                    </td>
                                                    <td class="w-50">    
                                                        <input type="text" class="form-control form-control-sm" readonly value="<?php echo checkDefaultVal($row['forename'], '...') ?>" >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right w-25">
                                                        Эцэг, эхийн нэр <span class="label-colon">:</span>
                                                    </td>
                                                    <td class="w-50">    
                                                        <input type="text" class="form-control form-control-sm" readonly value="<?php echo checkDefaultVal($row['surname'], '...') ?>" >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right w-25">
                                                        Нэр <span class="label-colon">:</span>
                                                    </td>
                                                    <td class="w-50">    
                                                        <input type="text" class="form-control form-control-sm" readonly value="<?php echo checkDefaultVal($row['givenname'], '...') ?>" >
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless table-custome-civil">
                                            <tbody>
                                                <tr>
                                                    <td class="text-right w-25">
                                                        Бүртгэгдсэн огноо <span class="label-colon">:</span>
                                                    </td>
                                                    <td class="w-50">
                                                        <input type="text" class="form-control form-control-sm" readonly value="<?php echo checkDefaultVal($row['createddate'], '...') ?>" >
                                                    </td>
                                                    <td rowspan="6" style="width: 3cm; max-width: 3cm;">
                                                        <fieldset>
                                                            <legend class="mb-1 pb-1">Алдаа</legend>
                                                            <?php if (issetParamArray($row['liveapproveerrordv'])) {
                                                                foreach ($row['liveapproveerrordv'] as $subRow) {
                                                                    # code... ?>
                                                                    <p class="badge bg-danger text-wrap text-justify" style="line-height: 1.5 !important;"><?php echo $subRow['errorname'] ?></p>
                                                                    <?php
                                                                }
                                                            } ?>
                                                            
                                                        </fieldset>
                                                    </td>
                                                </tr>
                                                <tr class="d-none">
                                                    <td class="text-right w-25">
                                                        Импортлогдсон огноо <span class="label-colon">:</span>
                                                    </td>
                                                    <td class="w-50">    
                                                        <input type="text" class="form-control form-control-sm" readonly value="<?php echo checkDefaultVal($row['modifieddate'], '...') ?>" >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right w-25">
                                                        Статус <span class="label-colon">:</span>
                                                    </td>
                                                    <td class="w-50">    
                                                        <input type="text" class="form-control form-control-sm" readonly value="<?php echo checkDefaultVal($row['status'], '...') ?>" >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right w-25">
                                                        Х.Х-ны статус <span class="label-colon">:</span>
                                                    </td>
                                                    <td class="w-50">    
                                                        <?php 
                                                        echo Form::select(
                                                            array(
                                                                'name' => 'statusafis[]',
                                                                'id' => 'statusafis[]',
                                                                'class' => 'form-control form-control-sm',
                                                                'op_value' => 'code',
                                                                'op_text' => 'name',
                                                                'data' => $this->reqTypeList,
                                                                'value' => $row['statusafis']
                                                            )
                                                        ); 
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right w-25">
                                                        Төрөл <span class="label-colon">:</span>
                                                    </td>
                                                    <td class="w-50">    
                                                        <?php 
                                                        echo Form::select(
                                                            array(
                                                                'name' => 'afisregtypecode[]',
                                                                'id' => 'afisregtypecode[]',
                                                                'class' => 'form-control form-control-sm',
                                                                'op_value' => 'id',
                                                                'op_text' => 'requesttypename',
                                                                'data' => $this->afisStatusChangeList,
                                                                'value' => $row['afisregtypecode']
                                                            )
                                                        ); 
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </fieldset>
                            <!--sectionCode1-->
                            
                            <fieldset>
                                <legend class="mb-1 pb-1">Хурууны хээ</legend>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-borderless table-custome-civil">
                                            <tbody>
                                                <tr>
                                                    <td colspan="5">Баруун гар:</td>
                                                    <td colspan="5">Зүүн гар:</td>
                                                </tr>
                                                <tr>
                                                    <?php foreach ($row['fingerleftdv'] as $subRow) { ?>
                                                        <td>
                                                            <div class="td-custome">
                                                                <span class="finger-title"><?php echo Lang::lineCode('leftfinger_' . $subRow['fingerid']) ?></span>
                                                                <img src="data:image/png;base64,<?php echo $subRow['fingerimage'] ?>" class="finger-image">
                                                            </div>
                                                        </td>
                                                    <?php } ?>
                                                    <?php foreach ($row['fingerrightdv'] as $subRow) { ?>
                                                        <td>
                                                            <div class="td-custome">
                                                                <span class="finger-title"><?php echo Lang::lineCode('rightfinger_' . $subRow['fingerid']) ?></span>
                                                                <img src="data:image/png;base64,<?php echo $subRow['fingerimage'] ?>" class="finger-image">
                                                            </div>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
            <?php } ?>
            </form>
            <?php } ?>
        </div>
    </div>
</div>
<style type="text/css">
    .civil_container<?php echo $this->uniqId ?> {
        max-width: 1200px;
        margin: 0 auto;

        .table-custome-civil .td-custome { 
            width: 80px;
            text-align: center;
            display: grid;
    
            .finger-title {
                background: #FFEB3B;
                padding: 2px;
            }
    
            .finger-image {
                max-width: 80px !important;
            }
        }
        .table-custome-civil td {
            padding: 0 !important;
            padding-left: 0.75rem !important;
        }
        .table-custome-civil tr {
            height: 32px !important;
        }
        .table-custome-civil .w-25 {
            width: 25%;
        }
        .table-custome-civil .w-50 {
            width: 50%;
        }
    }
</style>
<script type="text/javascript">
    $('body').on('keydown', '.civil_container<?php echo $this->uniqId ?> input[data-path="registerNum"]:eq(0)', function (e) {
        var _this = $(this),
            _parentSelector = _this.closest('.civil_container<?php echo $this->uniqId ?>');
            
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        if (keyCode == 13) {
            $.ajax({
                url: 'mddoc/findRegisterAfis',
                type: 'post',
                dataType: 'json',
                data: {
                    registerNum: _this.val()
                },
                beforeSend: function () {
                    Core.blockUI({
                        target: '.civil_container<?php echo $this->uniqId ?>',
                        boxed: true,
                        message: 'Түр хүлээнэ үү...'
                    });
                },
                success: function (data) {
                    _parentSelector.find('.civil-card').empty().append(data.Html).promise().done(function () {
                        Core.unblockUI('.civil_container<?php echo $this->uniqId ?>');
                    });
                },
                error: function (jqXHR, exception) {
                    Core.showErrorMessage(jqXHR, exception);
                    Core.unblockUI('.civil_container<?php echo $this->uniqId ?>');
                }
            });
        }
    });
    
    $('body').on('click', '.civil_container<?php echo $this->uniqId ?> .btn-search', function () {
        var _this = $(this),
            _parentSelector = _this.closest('.civil_container<?php echo $this->uniqId ?>');

        $.ajax({
            url: 'mddoc/findRegisterAfis',
            type: 'post',
            dataType: 'json',
            data: {
                registerNum: _parentSelector.find('input[data-path="registerNum"]:eq(0)').val()
            },
            beforeSend: function () {
                Core.blockUI({
                    target: '.civil_container<?php echo $this->uniqId ?>',
                    boxed: true,
                    message: 'Түр хүлээнэ үү...'
                });
            },
            success: function (data) {
                _parentSelector.find('.civil-card').empty().append(data.Html).promise().done(function () {
                    Core.unblockUI('.civil_container<?php echo $this->uniqId ?>');
                });
            },
            error: function (jqXHR, exception) {
                Core.showErrorMessage(jqXHR, exception);
                Core.unblockUI('.civil_container<?php echo $this->uniqId ?>');
            }
        });
    });

    $('body').on('click', '.civil_container<?php echo $this->uniqId ?> .btn-clear', function () {
        var _this = $(this),
            _parentSelector = _this.closest('.civil_container<?php echo $this->uniqId ?>');

        _parentSelector.find('.civil-card').empty();
        _parentSelector.find('input[data-path="registerNum"]:eq(0)').val('')
    });

    $('body').on('click', '.civil_container<?php echo $this->uniqId ?> .btn-save', function () {
        var _this = $(this),
            _parentSelector = _this.closest('.civil_container<?php echo $this->uniqId ?>');
        _parentSelector.find('form').ajaxSubmit({
            type: 'post',
            url: 'mddoc/saveFingerSearchForm',
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI();
            },
            success: function (response) {
                PNotify.removeAll();
                new PNotify({
                    title: response.status,
                    text: response.text,
                    type: response.status, 
                    sticker: false
                });

                Core.unblockUI();
            },
            error: function(jqXHR, exception) {
                Core.unblockUI();
                Core.showErrorMessage(jqXHR, exception);
            }
        });
    });

</script>