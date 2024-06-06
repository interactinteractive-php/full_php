
<?php if (issetParamArray($this->registerData)) { ?>
    <form class="form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="param[id]" value="<?php echo issetParam($this->id) ?>" >
<?php
    foreach ($this->registerData as $key => $row) { ?>
        <input type="hidden" name="param[crAfisFingerCheckGetDtlDv.mainRowCount][<?php echo $key ?>][]" value="" >
        <input type="hidden" name="param[crAfisFingerCheckGetDtlDv.id][<?php echo $key ?>][]" value="<?php echo issetParam($row['id']) ?>" >
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
                                                    'name' => "param[crAfisFingerCheckGetDtlDv.statusAfis][". $key ."][]",
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
                                                    'name' => "param[crAfisFingerCheckGetDtlDv.afisRegTypeCode][". $key ."][]",
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
<?php } 
if (issetParamArray($this->duplicateData)) {
    $key++;
    foreach ($this->duplicateData as $index => $row) { ?>
        <input type="hidden" name="param[crAfisFingerCheckGetDtlDv.mainRowCount][<?php echo $key ?>][]" value="" >
        <input type="hidden" name="param[crAfisFingerCheckGetDtlDv.id][<?php echo $key ?>][]" value="<?php echo issetParam($row['id']) ?>" >
        <div class="card p-3 bl-sectioncode1-card alpha-info title-font-size-small">
            <div class="card-body" data-section-code="1">
                <fieldset>
                    <legend class="mb-1 pb-1">Давхардсан иргэн</legend>
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
                                                    'name' => "param[crAfisFingerCheckGetDtlDv.statusAfis][". $key ."][]",
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
                                                    'name' => "param[crAfisFingerCheckGetDtlDv.afisRegTypeCode][". $key ."][]",
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
<?php } 
} ?>