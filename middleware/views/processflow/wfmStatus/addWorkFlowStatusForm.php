<div class="col-md-12">
    <?php echo Form::create(array('class' => 'form-horizontal xs-form', 'id' => 'createWfmStatus-from', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
    <table class="table table-sm table-no-bordered" style="table-layout: fixed !important">
        <tbody>
            <tr>
                <td class="text-right middle" style="width: 45%">
                    <label for="wfmStatusCode" data-label-path="title" required="required">Төлвийн код:</label>
                </td>
                <td class="middle" style="width: 55%" colspan="">
                    <div data-section-path="wfmStatusCode">
                      <input type="text" id="wfmStatusCode" name="wfmStatusCode" placeholder="Төлвийн код" class="form-control form-control-sm" required="required">
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-right middle" style="width: 45%">
                    <label for="wfmStatusName" data-label-path="title" required="required">Төлвийн нэр:</label>
                </td>
                <td class="middle" style="width: 55%" colspan="">
                    <div data-section-path="wfmStatusName">
                        <input type="text" id="wfmStatusName" name="wfmStatusName" placeholder="Төлвийн нэр" class="form-control form-control-sm" required="required">
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-right middle" style="width: 45%">
                    <label for="wfmProcessName" data-label-path="title" required="required">Процессийн нэр:</label>
                </td>
                <td class="middle" style="width: 55%" colspan="">
                    <div data-section-path="wfmProcessName">
                        <input type="text" id="wfmProcessName" name="wfmProcessName" placeholder="Процессийн нэр" class="form-control form-control-sm" required="required">
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-right middle" style="width: 45%">
                    <label for="wfmStatusColor" data-label-path="title" required="required">Төлвийн өнгө:</label>
                </td>
                <td class="middle" style="width: 55%">
                    <div class="input-group color colorpicker-default" data-color="#3f9eda" data-color-format="rgba">
                        <input type="text" name="wfmStatusColor" id="wfmStatusColor" class="form-control" value="#3f9eda"  required="required">
                        <span class="input-group-btn">
                            <button class="btn default colorpicker-input-addon px-1" type="button"><i style="background-color: #3f9eda;"></i>&nbsp;</button>
                        </span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-right middle" style="width: 45%">
                    <label for="wfmProcessId" data-label-path="title" required="required">Процесс сонгох:</label>
                </td>
                <td class="middle" style="width: 55%">
                    <div data-section-path="wfmProcessId">
                        <div class="input-group quick-item" style="width: 0px">
                            <input type="hidden" id="wfmProcessId" name="wfmProcessId" placeholder="" class="form-control form-control-sm">
                            <input type="text" id="wfmProcessCodeName" disabled name="wfmProcessCodeName" class="form-control form-control-sm" placeholder="Процесс сонгох">                                    
                            <span class="input-group-btn">
                                <button type="button" class="btn green-meadow" onclick="dataViewCustomSelectableGrid('sysMetaProcessList', 'single', 'proccessSelectabledGridForWfm', '', this);"><i class="icon-plus3 font-size-12"></i></button>                                    
                                <button type="button" class="btn red-meadow" onclick="deleteWfmProcess(this);"><i class="icon-plus3 font-size-12"></i></button>
                            </span>
                        </div>                        
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-right middle" style="width: 45%">
                    <label for="wfmIsSign" data-label-path="title" required="required">Гарын үсэгтэй эсэх:</label>
                </td>
                <td class="middle" style="width: 55%" colspan="">
                    <div data-section-path="wfmIsSign">
                        <?php
                        $signChooseData = array(
                            array(
                                'id' => '0',
                                'name' => '- '.Lang::line('select_btn').' -'
                            ),
                            array(
                                'id' => '1',
                                'name' => 'Desktop Client'
                            ),
                            array(
                                'id' => '2',
                                'name' => 'Monpass Client Position'
                            ),
                            array(
                                'id' => '3',
                                'name' => 'Cloud'
                            ),
                            array(
                                'id' => '4',
                                'name' => 'Pin code'
                            ), 
                            array(
                                'id' => '5',
                                'name' => 'Digital signature'
                            ), 
                            array(
                                'id' => '6',
                                'name' => 'OTP'
                            ), 
                            array(
                                'id' => '7',
                                'name' => 'Pdf watermark'
                            ) 
                        );
                        echo Form::select(array('name' => 'wfmIsSign', 'text' => 'notext', 'id' => 'wfmIsSign', 'data' => $signChooseData, 'op_value' => 'id', 'op_text' => 'name', 'class' => 'form-control'))                         
                        ?>
                    </div>
                </td>
            </tr>    
            <tr>
                <td class="text-right middle" style="width: 45%">
                    <label for="isDescRequired" data-label-path="title">Тайлбар заавал оруулах эсэх:</label>
                </td>
                <td class="middle" style="width: 55%" colspan="">
                    <div data-section-path="wfmIsSign">
                        <?php echo Form::checkbox(array('name' => 'isDescRequired', 'id' => 'isDescRequired', 'class' => 'form-control')); ?>
                    </div>
                </td>
            </tr>            
            <tr>
                <td class="text-right middle" style="width: 45%">
                    <label for="isSendMail" data-label-path="title">Мэйл илгээх эсэх:</label>
                </td>
                <td class="middle" style="width: 55%" colspan="">
                    <div data-section-path="isSendMail">
                        <?php echo Form::checkbox(array('name' => 'isSendMail', 'id' => 'isSendMail', 'class' => 'form-control')); ?>
                    </div>
                </td>
            </tr>            
        </tbody>
    </table>
  
    <?php 
    echo Form::hidden(array('name' => 'metaDataId', 'value' => $this->metaDataId)); 
    echo Form::hidden(array('name' => 'transitionId', 'value' => $this->transitionId)); 
    echo Form::close(); 
    ?>  
</div>
<script type="text/javascript">
    $(function() {
        Core.initUniform($('#createWfmStatus-from'));
        $('.colorpicker-default').colorpicker({
            format: 'hex'
        });
    });
    function proccessSelectabledGridForWfm (metaDataCode, chooseType, elem, rows) {
        var currTarget = $(elem).closest('.input-group');
        currTarget.find('#wfmProcessId').val(rows[0].id);
        currTarget.find('#wfmProcessCodeName').val(rows[0].metadatacode + ' | ' + rows[0].metadataname);
    }    
    function deleteWfmProcess(element) {
        var currTarget = $(elem).closest('.input-group');
        currTarget.find('#wfmProcessId').val('');
        currTarget.find('#wfmProcessCodeName').val('');
    }
</script>