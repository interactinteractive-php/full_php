<?php 
echo Form::create(array('class' => 'form-horizontal', 'id' => 'saveform_' . $this->uniqId, 'method' => 'post', 'enctype' => 'multipart/form-data')); 
    $currentDate = Date::currentDate('Y-m-d'); 
    if ($this->isedit) {
        includeLib('Compress/Compression');
        $decompressContent = Compression::encode_string_array($this->data);
        
        echo Form::hidden(array('name' => 'isedit', 'value' => '1'));
        echo Form::hidden(array('name' => 'id', 'value' => $this->data['id']));
        echo Form::hidden(array('name' => 'mainData', 'value' => $decompressContent));
    } ?>
    
    <!--<h4><i class="icon-calendar mt-n2 mr5 text-blue"></i> 2019-11-29</h4>-->
    <div class="table-responsive">
        <table class="table table-striped table-borderless">
            <tbody>
                <tr>
                    <td>
                        <label for="name">Гарчиг</label>
                    </td>
                    <td class="pl-0" style='width: 292px;'>
                        <input type="text" class="form-control" name="name" placeholder="Гарчиг" required="required" value="<?php echo ($this->isedit && $this->data) ? $this->data['name'] : '' ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="templateId">Танхим</label>
                    </td>
                    <td class="pl-0">
                        <select class="form-control" id="templateId<?php echo $this->uniqId ?>" name="templateId" onchange="checkRoomSlot();" required="required">
                            <option value="">- Сонгох -</option>
                            <?php if ($this->option) {
                                foreach ($this->option as $row) { ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo ($this->isedit && $this->data) ? $row['id'] == $this->data['templateid'] ? 'selected="selected"' : '' : '' ?>><?php echo $row['name'] ?></option>
                                <?php }
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="startDate">Хурлын огноо</label>
                    </td>
                    <td class="pl-0">
                        <div class="dateElement input-group">
                            <input type="text" class="form-control dateInit" name="startDate" id="startDate<?php echo $this->uniqId ?>" placeholder="Эхлэх огноо" onchange="checkRoomSlot();" required="required" value="<?php echo ($this->isedit && $this->data) ? $this->data['startdate'] : '' ?>">
                            <span class="input-group-btn"><button tabindex="-1" onclick="return false;" class="btn" style="padding: 7.5px 10px !important;"><i class="fa fa-calendar"></i></button></span>
                        </div>
                    </td>
                </tr>
                <tr id="suggestedTime<?php echo $this->uniqId ?>" style="display:none;">
                    <td>
                        <label>Сул цаг</label>
                    </td>
                    <td class="pl-0">
                    <center>
                        <div class="w-100 pull-left alert mb0 alert-primary" id="suggestappend<?php echo $this->uniqId ?>">
                            <div class="row">
                                
                            </div>
                        </div>
                    </center>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="startTime">Эхлэх цаг</label>
                    </td>
                    <td class="pl-0">
                        <div class="dateElement input-group">
                            <input type="text" class="form-control timeInit" name="startTime" id="startTime<?php echo $this->uniqId ?>" placeholder="Эхлэх цаг" onchange="checkRoomSlot();" required="required" value="<?php echo ($this->isedit && $this->data) ? Date::format('H:i', $this->data['starttime']) : '' ?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="endTime">Дуусах цаг</label>
                    </td>
                    <td class="pl-0">
                        <div class="dateElement input-group">
                            <input type="text" class="form-control timeInit" name="endTime" id="endTime<?php echo $this->uniqId ?>" placeholder="Дуусах цаг" onchange="checkRoomSlot();" required="required" value="<?php echo ($this->isedit && $this->data) ? Date::format('H:i', $this->data['endtime']) : '' ?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="description">Тайлбар</label>
                    </td>
                    <td class="pl-0">
                        <textarea rows="4" cols="4" class="form-control elastic" name="description" id="description" placeholder="Тайлбар"><?php echo ($this->isedit && $this->data) ? $this->data['description'] : '' ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="taskTypeIds"><?php echo Lang::line('TASK_TYPE_IDS') ?></label>
                    </td>
                    <td class="pl-0">
                        <select class="form-control form-control-sm dropdownInput select2 select2-offscreen" name="taskTypeIds[]" multiple="multiple">
                            <?php if ($this->optionType) {
                                foreach ($this->optionType as $row) { ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo isset($this->selectedIds[$row['id']]) ? 'selected="selected"' : '' ?> ><?php echo $row['name'] ?></option>
                                <?php }
                            } ?>
                        </select>
                    </td>
                </tr>
                
<!--                <tr>
                    <td>
                        <label for="privacyType">Хурлын оролцогчид</label>
                    </td>
                    <td class="pl-0">
                        <select class="form-control" id="privacyType" name="privacyType">
                            <option value="1">Байгууллага дотроо</option>
                            <option value="2">Хэрэглэгч</option>
                            <option value="3">Хэлтэс</option>
                            <option value="4">Бүгд</option>
                        </select>
                    </td>
                </tr>-->
<!--                <tr>
                    <td>
                        
                    </td>
                    <td class="pl-0">
                        
                    </td>
                </tr>-->
            </tbody>
        </table>
        <div class="org-choice"style="background: #ffe8436b; border: none; text-align: center;">
            <div class="form-group mb-0" style="font-size: 12px !important;">
                <div class="form-check form-check-inline">
                    <label class="form-check-label align-items-center">
                        <i class="fa fa-info-circle"></i> <span class="ml5">Хуралд оролцогчдыг сонгоно уу</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="org-choice">
            <div class="form-group mb-0" style="font-size: 12px !important;">
                <div class="form-check form-check-inline">
                    <label class="form-check-label align-items-center">
                        <span class="checked"><input type="radio" name="privacyType" value="1" <?php echo ($this->isedit && $this->data) ?  $this->data['privacytype'] == 'public' ? 'checked="checked"' : '' : 'checked="checked"' ?>></span>
                        Байгууллага дотроо
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label align-items-center">
                        <span><input type="radio" name="privacyType" value="2" <?php echo ($this->isedit && $this->data && $this->data['privacytype'] == 'user') ? 'checked="checked"' : '' ?>></span>
                        Хэрэглэгч
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label align-items-center">
                        <span><input type="radio" name="privacyType" value="3" <?php echo ($this->isedit && $this->data && $this->data['privacytype'] == 'department') ? 'checked="checked"' : '' ?>></span>
                        Хэлтэс
                    </label>
                </div>
            </div>
        </div>
        <div class="<?php echo ($this->isedit && issetParam($this->data['omsmeetinguserdv']) && $this->data['privacytype'] == 'user') ? '' : 'd-none' ?> userIds<?php echo $this->uniqId ?>">
            <div class="d-flex flex-row align-items-center mb10">
                <div class="col-12 pl0 pr0">
                    <div class="input-group">
                        <select id="userIds" name="userIds[]" class="form-control form-control-sm dropdownInput select2 bp-field-with-popup-combo select2-offscreen" data-path="userIds" data-field-name="userIds" data-row-data="{&quot;META_DATA_ID&quot;:&quot;1585127564874&quot;,&quot;ATTRIBUTE_ID_COLUMN&quot;:&quot;id&quot;,&quot;ATTRIBUTE_CODE_COLUMN&quot;:null,&quot;ATTRIBUTE_NAME_COLUMN&quot;:&quot;name&quot;,&quot;PARAM_REAL_PATH&quot;:&quot;userIds&quot;,&quot;PROCESS_META_DATA_ID&quot;:&quot;1567154435267&quot;,&quot;CHOOSE_TYPE&quot;:&quot;multicomma&quot;}" multiple="multiple" data-isclear="0" data-placeholder="- Хэрэглэгч -" tabindex="-1" style="">
                            <?php 
                                $countUser = 0;
                                if ($this->isedit && issetParam($this->data['omsmeetinguserdv'])) {
                                    foreach ($this->data['omsmeetinguserdv'] as $row) {
                                        if ($row['userid']) {
                                            $drowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                                            if ($row['userid'] !== $sessionUserId) {
                                                $countUser++;
                                                echo '<option selected="selected" value="'. $row['userid'] .'" data-row-data="'. $drowJson .'">'. $row['personname'] .'</option>';
                                            }
                                        }
                                    }
                                } 
                            ?>
                        </select> 
                        <span class="input-group-append"> 
                            <button class="btn btn-primary mr0" type="button" data-lookupid="1565070936581248" data-paramcode="userIds" data-processid="1567154435267" data-choosetype="multicomma" data-idfield="id" data-namefield="name" onclick="bpBasketDvWithPopupCombo(this);"><?php echo ($countUser) ? $countUser : '..' ?></button>
                            <button class="btn btn-danger mr0 removebtn" type="button" data-lookupid="1565070936581248" onclick="bpRemoveAllBasketWithPopupCombo(this);" style="display: none;"><i class="fa fa-trash"></i></button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="<?php echo ($this->isedit && issetParam($this->data['omsmeetinggroupdepartmentdv'])) && $this->data['privacytype'] == 'department' ? '' : 'd-none' ?> departmentIds<?php echo $this->uniqId ?>">
            <div class="d-flex flex-row align-items-center mb10">
                <div class="col-12 pl0 pr0">
                    <div class="input-group">
                        <select id="departmentIds" name="departmentIds[]" class="form-control form-control-sm dropdownInput select2 bp-field-with-popup-combo select2-offscreen" data-path="departmentIds" data-field-name="departmentIds" data-row-data="{&quot;META_DATA_ID&quot;:&quot;1565070690138433&quot;,&quot;ATTRIBUTE_ID_COLUMN&quot;:&quot;id&quot;,&quot;ATTRIBUTE_CODE_COLUMN&quot;:null,&quot;ATTRIBUTE_NAME_COLUMN&quot;:&quot;departmentName&quot;,&quot;PARAM_REAL_PATH&quot;:&quot;departmentIds&quot;,&quot;PROCESS_META_DATA_ID&quot;:&quot;1567154435267&quot;,&quot;CHOOSE_TYPE&quot;:&quot;multicomma&quot;}" multiple="multiple" data-isclear="0" data-placeholder="- Хэлтэс нэгж -" tabindex="-1">
                            <?php 
                                $countDep = 0;
                                if ($this->isedit && issetParam($this->data['omsmeetinggroupdepartmentdv'])) {
                                    foreach ($this->data['omsmeetinggroupdepartmentdv'] as $row) {
                                        if ($row['departmentid']) {
                                            $drowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                                            $countDep++;
                                            echo '<option selected="selected" value="'. $row['departmentid'] .'" data-row-data="'. $drowJson .'">'. $row['departmentname'] .'</option>';
                                        }
                                    }
                                } 
                            ?>
                        </select> 
                        <span class="input-group-append"> 
                            <button class="btn btn-primary mr0" type="button" data-lookupid="1565070690138433" data-paramcode="departmentIds" data-processid="1567154435267" data-choosetype="multicomma" data-idfield="id" data-namefield="departmentName" onclick="bpBasketDvWithPopupCombo(this);"><?php echo ($countDep) ? $countDep : '..' ?></button>
                            <button class="btn btn-danger mr0 removebtn" type="button" data-lookupid="1565070936581248" onclick="bpRemoveAllBasketWithPopupCombo(this);" style="display: none;"><i class="fa fa-trash"></i></button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt10">
            <input type="checkbox" name="isPost" id="isPost" value="1" <?php echo (isset($this->data['ispost']) && $this->data['ispost']) ? 'checked="checked"' : ''  ?>>
            <label for="isPost" class="ml10" style="font-size: 12px;">Зар мэдээ хэсэгт хэсэгт нийтлэх эсэх</label>
        </div>
    </div>
    
    <div class="row d-none">
        <div class="col-2">
            <div class="mt-2 mb10">
                <a href="javascript:;" class="btn btn-sm btn-outline bg-primary border-primary text-primary-800 fileinput-button" title="Файл нэмэх">
                    <i class="icon-attachment mr-1"></i> Файл нэмэх
                    <input type="file" name="bp_file[]" class="" multiple onchange="onChangeAttachFIleAddMode<?php echo $this->uniqId ?>(this)" />
                </a>
            </div>
        </div>
    </div>
    <div class="card-body d-none">
        <ul class="row list-inline mb-0 list-view-file-new filelist-<?php echo $this->uniqId ?>"></ul>
    </div>
    
<?php echo Form::close(); ?>
    
<script type="text/javascript">
    
    $(function () {
        $('#saveform_<?php echo $this->uniqId ?>').find('input.dateInit').on('changeDate', function(){
            var $thisval = new Date($(this).val());
            var $thisEndDateVal = new Date('<?php echo $currentDate ?>');
            
            if ($thisval.getTime() < $thisEndDateVal.getTime()) {

                PNotify.removeAll();
                new PNotify({
                    title: '<?php echo Lang::line('warning') ?>',
                    text: '<?php echo Lang::line('WRONG_DATE_GOVERMENT_POST') ?>',
                    type: 'warning',
                    sticker: false
                });
                
                $(this).datepicker('update', '<?php echo $currentDate ?>');
            }
        });
    });
    
    $('body').on('change', 'input[name="privacyType"]', function () {
        var $this = $(this);
        var $id = $this.val();
        var mainSelector = $('#saveform_<?php echo $this->uniqId ?>');
        
        $this.closest('.create_reg_btn').find('button.active').removeClass('active');
        $this.addClass('active');
        
        $('#saveform_<?php echo $this->uniqId ?>').find('select[data-path="departmentIds"]').select2('val', '');
        $('#saveform_<?php echo $this->uniqId ?>').find('select[data-path="userIds"]').select2('val', '');
        
        $('#saveform_<?php echo $this->uniqId ?>').find('.userIds<?php echo $this->uniqId ?>').find('button[data-paramcode="userIds"]').html('..');
        $('#saveform_<?php echo $this->uniqId ?>').find('.userIds<?php echo $this->uniqId ?>').find('button.removebtn').hide();
        
        $('#saveform_<?php echo $this->uniqId ?>').find('.departmentIds<?php echo $this->uniqId ?>').find('button[data-paramcode="userIds"]').html('..');
        $('#saveform_<?php echo $this->uniqId ?>').find('.departmentIds<?php echo $this->uniqId ?>').find('button.removebtn').hide();
        
        switch ($id) {
            case '1':
                mainSelector.find('.userIds<?php echo $this->uniqId ?>').addClass('d-none');
                mainSelector.find('.departmentIds<?php echo $this->uniqId ?>').addClass('d-none');
                break;
            case '2':
                mainSelector.find('.departmentIds<?php echo $this->uniqId ?>').addClass('d-none');
                mainSelector.find('.userIds<?php echo $this->uniqId ?>').removeClass('d-none');
                break;
            case '3':
                mainSelector.find('.userIds<?php echo $this->uniqId ?>').addClass('d-none');
                mainSelector.find('.departmentIds<?php echo $this->uniqId ?>').removeClass('d-none');
                break;
            case '4':
                mainSelector.find('.userIds<?php echo $this->uniqId ?>').removeClass('d-none');
                mainSelector.find('.departmentIds<?php echo $this->uniqId ?>').removeClass('d-none');
                break;
        }
        
    });
    
    function setTime(stime, etime) {
        $("#startTime<?php echo $this->uniqId ?>").val(stime);
        $("#endTime<?php echo $this->uniqId ?>").val(etime);
        $("#savebutton<?php echo $this->uniqId ?>").attr("disabled", false);
    }
    
    function checkRoomSlot() {
        
        $('body').find('#savebutton<?php echo $this->uniqId ?>').removeAttr('disabled');
        var notsave = false;
        var roomId = $("#templateId<?php echo $this->uniqId ?>").val();
        var startDate = $("#startDate<?php echo $this->uniqId ?>").val();
        var startTime = $("#startTime<?php echo $this->uniqId ?>").val();
        var endTime = $("#endTime<?php echo $this->uniqId ?>").val();
        var filterStartDate = startDate + ' ' + startTime + ':00';
        var filterEndDate = startDate + ' ' + endTime + ':00';
        
        if (startTime !== "") {
            if (startTime < '08:30') {
                PNotify.removeAll();
                new PNotify({
                    title: 'Анхаар',
                    text: 'Хурлын танхим захиалгын эхлэх цаг өглөөний 08 цаг 30 минутаас хойш байна.',
                    type: 'warning',
                    sticker: false
                });
                
                $("#startTime<?php echo $this->uniqId ?>").focus();
                $("#startTime<?php echo $this->uniqId ?>").val('');
                $("#savebutton<?php echo $this->uniqId ?>").attr("disabled", true);
                notsave = true;
            } 
            else {
                $("#savebutton<?php echo $this->uniqId ?>").attr("disabled", false);
            }
            
            if('17:30' <= startTime) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Анхаар',
                    text: 'Хурлын танхим захиалгын эхлэх цаг оройны 17 цаг 30 минутаас хэтрэхгүй байна.',
                    type: 'warning',
                    sticker: false
                });
                $("#startTime<?php echo $this->uniqId ?>").focus();
                $("#startTime<?php echo $this->uniqId ?>").val('');
                $("#savebutton<?php echo $this->uniqId ?>").attr("disabled", true);
                notsave = true;
            } 
        }
        
        if (endTime !== "") {
            if(endTime > '17:30') {
                PNotify.removeAll();
                new PNotify({
                    title: 'Анхаар',
                    text: 'Хурлын танхим захиалгын дуусах цаг оройны 17 цаг 30 минутаас хэтрэхгүй байна.',
                    type: 'warning',
                    sticker: false
                });
                $("#endTime<?php echo $this->uniqId ?>").focus();
                $("#endTime<?php echo $this->uniqId ?>").val('');
                $("#savebutton<?php echo $this->uniqId ?>").attr("disabled", true);
                notsave = true;
            } else {
                $("#savebutton<?php echo $this->uniqId ?>").attr("disabled", false);
            }
        }
        
        if (startTime > endTime && startTime !== "" && endTime !== "") {
            PNotify.removeAll();
            new PNotify({
                title: 'Анхаар',
                text: 'Цагийн интервал буруу байна',
                type: 'warning',
                sticker: false
            });
            $("#endTime<?php echo $this->uniqId ?>").val('');
            return;
        }
        
        if (roomId !== "" && startDate !== "" && startTime == "" && endTime == "") {
            $.ajax({
                type: 'post',
                url: 'government/getSuggestedTime',
                data: {date: startDate, roomId: roomId},
                dataType: 'json',
                success: function (data) {
                    var html = '<div class="row">';
                    
                    $('#saveform_<?php echo $this->uniqId ?> input[name="startTime"]').removeAttr('disabled', 'disabled').val('');
                    $('#saveform_<?php echo $this->uniqId ?> input[name="endTime"]').removeAttr('disabled', 'disabled').val('');
                    var $changeCode = '';
                    $("#suggestappend<?php echo $this->uniqId ?>").addClass('alert-primary').removeClass('alert-warning');
                    
                    if (data !== null) {
                        if (data.timedtl !== null) {
                            if (data.timedtl[0]['typecode'] === '404') {
                                PNotify.removeAll();
                                $changeCode = '404';
                                new PNotify({
                                    title: 'Анхаар',
                                    text: (data.timedtl[0]['text'] ? data.timedtl[0]['text'] : 'Ажилтай'),
                                    type: 'warning',
                                    sticker: false
                                });
                                
                                $('#saveform_<?php echo $this->uniqId ?> input[name="startTime"]').attr('disabled', 'disabled').val('');
                                $('#saveform_<?php echo $this->uniqId ?> input[name="endTime"]').attr('disabled', 'disabled').val('');
                                
                                html = '<div class="pull-left"><span class="badge badge-warning badge-pill mr-1 mb-1 cursor-pointer">'+  (data.timedtl[0]['text'] ? data.timedtl[0]['text'] : 'Ажилтай')  +'</span></div>';
                                notsave = true;
                                $('body').find('#savebutton<?php echo $this->uniqId ?>').attr('disabled', 'disabled');
                               
                            } else {
                                $.each( data.timedtl, function( key, value ) {
                                    html += '<div class="col-3 mr-1"><span onclick="setTime(\''+ value.stime +'\',\''+ value.etime +'\')" class="badge badge-primary badge-pill mr-1 mb-1 cursor-pointer">'+value.conftime+'</span></div>';
                                });
                            }
                        } 
                        else {
                            $("#suggestedTime<?php echo $this->uniqId ?>").hide();
                            html += "<span class='text-center'>Сул цаг байхгүй байна</span>";
                            notsave = true;
                            $('#saveform_<?php echo $this->uniqId ?> input[name="startTime"]').attr('disabled', 'disabled').val('');
                            $('#saveform_<?php echo $this->uniqId ?> input[name="endTime"]').attr('disabled', 'disabled').val('');
                            $('body').find('#savebutton<?php echo $this->uniqId ?>').attr('disabled', 'disabled');
                            
                        }
                    } else {
                        html += "<span class='text-center'>Сул цаг байхгүй байна</span>";
                    }

                    html += "</div>";
                    
                    if ($changeCode) {
                        $("#suggestappend<?php echo $this->uniqId ?>").addClass('alert-warning').removeClass('alert-primary');
                    }
                    
                    $("#suggestappend<?php echo $this->uniqId ?>").empty().append(html);
                    $("#suggestedTime<?php echo $this->uniqId ?>").show();
                }
            });
        }
        
        if (roomId !== "" && startDate !== "" && startTime !== "" && endTime !== "") {
            $.ajax({
                type: 'post',
                url: 'government/checkMeetingRoomSlot',
                data: {date: startDate, roomId: roomId, startDate: filterStartDate, endDate: filterEndDate},
                dataType: 'json',
                success: function (data) {
                    if (data != null) {
                        if (data.count === '1') {
                            
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Анхаар',
                                text: 'Таны оруулсан цаг бусад захиалгын цагтай давхардаж байна. Та календараас захиалгын мэдээллийг шалган сул цаг сонгоно уу',
                                type: 'warning',
                                sticker: false
                            });
                            notsave = true;
                            $('body').find('#savebutton<?php echo $this->uniqId ?>').attr('disabled', 'disabled');
                        } 
                        else {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Амжилттай',
                                text: 'Сонгосон цагт танхим захиалах боломжтой байна.',
                                type: 'success',
                                sticker: false
                            });
                        }
                    } 
                    else {
                        
                        if (startTime < '<?php echo Date::currentDate('H:i') ?>' && startDate == '<?php echo $currentDate ?>') {
                            notsave = true;
                            PNotify.removeAll();
                            new PNotify({
                                title: '<?php echo Lang::line('warning') ?>',
                                text: '<?php echo Lang::line('WRONG_DATE_GOVERMENT_POST') ?>',
                                type: 'warning',
                                sticker: false
                            });
                            $('body').find('#savebutton<?php echo $this->uniqId ?>').attr('disabled', 'disabled');
                        } 
                        else {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Амжилттай',
                                text: 'Сонгосон цагт танхим захиалах боломжтой байна.',
                                type: 'success',
                                sticker: false
                            });
                        }
                    }
                }
            });
        }
        
        if (notsave) {
            $('body').find('#savebutton<?php echo $this->uniqId ?>').attr('disabled', 'disabled');
        }
    }
    
</script>

<style type="text/css">
    .modal .spinner {
        width: initial !important;
        height: initial !important;
    }
    .ui-widget[aria-describedby="dialog-dataview-selectable-1565070690138433"],
    .ui-widget[aria-describedby="dialog-dataview-selectable-1565070936581248"] {
        z-index: 1052 !important; 
    }
    .ui-widget-overlay[aria-describedby="dialog-dataview-selectable-1565070690138433"],
    .ui-widget-overlay[aria-describedby="dialog-dataview-selectable-1565070936581248"]
    {
        z-index: 1051 !important; 
    }
</style>