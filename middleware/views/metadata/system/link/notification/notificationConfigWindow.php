<div class="col-md-12">
    <div class="tabbable-line">
        <ul class="nav nav-tabs ">
            <li class="nav-item notificationLi">
                <a href="#notificationTab1" data-toggle="tab" class="nav-link active" onclick="changeCurrentNotification(1)">1</a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" data-toggle="tab" class="nav-link" onclick="addNotification()">+</a>
            </li>
        </ul>
        <form class="form" id="notificationForm">
            <div class="tab-content pt10">
                <?php foreach($this->defaultValue AS $notificationKey => $notificationValue) : ?>
                    <?php $notificationCounter = $notificationKey + 1;?>
                    <div class="tab-pane <?php if($notificationKey == 0) { echo 'active';}?>" id="notificationTab<?php echo $notificationCounter; ?>">

                            <div class="form-group row fom-row">
                                <div class="col-md-4"><label for="notificationId">Notification сонгох:</label></div> 
                                <div class="col-md-8"><?php
                                    echo Form::select(
                                            array(
                                                'name' => 'notificationId[]',
                                                'id' => 'notificationId'.$notificationCounter,
                                                'data' => Info::getNotificationListForSelect(),
                                                'text' => $this->lang->line('META_00043'),
                                                'op_value' => 'NOTIFICATION_ID',
                                                'op_text' => 'TEXT_SHORT',
                                                'class' => 'form-control select2 notificationId',
                                                'data-name' => $notificationCounter,
                                                'value' => isset($notificationValue['NOTIFICATION_ID']) ? $notificationValue['NOTIFICATION_ID'] : null
                                            )
                                    );
                                    ?>
                                </div>
                            </div>
                            <div class="form-group row fom-row">
                                <div class="col-md-4"><label for="inParamCriteria">Process input criteria:</label></div> 
                                <div class="col-md-8"><?php
                                    echo Form::textArea(
                                            array(
                                                'name' => 'inParamCriteria[]',
                                                'id' => 'inParamCriteria'.$notificationCounter,
                                                'class' => 'form-control',
                                                'data-name' => $notificationCounter,
                                                'value' => isset($notificationValue['IN_PARAM_CRITERIA']) ? $notificationValue['IN_PARAM_CRITERIA'] : null
                                            )
                                    );
                                    ?>
                                </div>
                            </div>
                            <div class="form-group row fom-row">
                                <div class="col-md-4"><label for="outParamCriteria<?php echo $notificationCounter; ?>">Process output criteria:</label></div> 
                                <div class="col-md-8"><?php
                                    echo Form::textArea(
                                            array(
                                                'name' => 'outParamCriteria[]',
                                                'id' => 'outParamCriteria'.$notificationCounter,
                                                'class' => 'form-control',
                                                'data-name' => $notificationCounter,
                                                'value' => isset($notificationValue['OUT_PARAM_CRITERIA']) ? $notificationValue['OUT_PARAM_CRITERIA'] : null
                                            )
                                    );
                                    ?>
                                </div>
                            </div>
                            <div class="form-group row fom-row">
                                <div class="col-md-4"><label for="userQuery<?php echo $notificationCounter; ?>">Хэрэглэгчдийн жагсаалт татах criteria:</label></div> 
                                <div class="col-md-8"><?php
                                    echo Form::textArea(
                                            array(
                                                'name' => 'userQuery[]',
                                                'id' => 'userQuery'.$notificationCounter,
                                                'class' => 'form-control',
                                                'data-name' => $notificationCounter,
                                                'value' => isset($notificationValue['USER_QUERY']) ? $notificationValue['USER_QUERY'] : null
                                            )
                                    );
                                    ?>
                                    <i>UM_USER - н ID г "as ntfuserid" гэж авна!</i>
                                </div>
                            </div>
                            <div class="form-group row fom-row">
                                <div class="col-md-4"><label for="notifyDate<?php echo $notificationCounter; ?>">Notification илгээх огноо (логик):</label></div> 
                                <div class="col-md-8"><?php
                                    echo Form::textArea(
                                            array(
                                                'name' => 'notifyDate[]',
                                                'id' => 'notifyDate'.$notificationCounter,
                                                'class' => 'form-control',
                                                'data-name' => $notificationCounter,
                                                'value' => isset($notificationValue['NOTIFY_DATE']) ? $notificationValue['NOTIFY_DATE'] : null
                                            )
                                    );
                                    ?>
                                </div>
                            </div>
                            <div class="form-group row fom-row">
                                <div class="col-md-4"><label for="notificationText">Notification текст:</label></div> 
                                <div class="col-md-8"><?php
                                    echo Form::textArea(
                                            array(
                                                'name' => 'notificationText[]',
                                                'id' => 'notificationText'.$notificationCounter,
                                                'class' => 'form-control',
                                                'readonly' => 'readonly',
                                                'data-name' => $notificationCounter,
                                                'value' => null
                                            )
                                    );
                                    ?>
                                </div>
                            </div>
                            <div class="form-group row fom-row">
                                <div class="col-md-4"><label for="notificationText">И-мэйл илгээх эсэх:</label></div> 
                                <div class="col-md-8"><?php
                                    if ($notificationValue['IS_SEND_MAIL'] == 1) {
                                        echo Form::checkbox(
                                                array(
                                                    'name' => 'isSendMail[]',
                                                    'id' => 'isSendMail',
                                                    'value' => '1',
                                                    'checked' => 'checked'
                                                )
                                        );
                                    } else {
                                        echo Form::checkbox(
                                                array(
                                                    'name' => 'isSendMail[]',
                                                    'id' => 'isSendMail',
                                                    'value' => '1'
                                                )
                                        );
                                    }                           
                                    ?>
                                </div>
                            </div>

                            <div class="form-group row fom-row">
                                <div class="col-md-12">
                                    <table class="table" id="notificationTable<?php echo $notificationCounter; ?>">
                                        <thead>
                                            <tr>
                                                <th>№</th>
                                                <th>Хувьсагч</th>
                                                <th>Гаралтын параметр</th>
                                                <th>Тогтмол утга</th>
                                            </tr>
                                        </thead>
                                        <tbody id="notificationParamTbody<?php echo $notificationCounter; ?>">
                                            <?php if(isset($notificationValue['PARAMS']) && count($notificationValue['NOTIFICAITON_PARAM']) > 0) : ?>
                                                <?php $notificationParamCounter = 1; ?>
                                                <?php foreach($notificationValue['PARAMS'] AS $k => $notificationParam) :  ?>
                                                    <?php if(isset($notificationValue['NOTIFICAITON_PARAM'][$k])) { ?>
                                                        <tr>
                                                            <td><?php echo $notificationParamCounter; ?></td>
                                                            <td>
                                                                <?php        
                                                                if(count($notificationValue['NOTIFICAITON_PARAM']) > 0) {
                                                                    echo $notificationValue['NOTIFICAITON_PARAM'][$k];
                                                                    echo Form::hidden(array('name' => 'notificationParamList['.$notificationKey.'][]', 'value' => $notificationValue['NOTIFICAITON_PARAM'][$k]));
                                                                }
                                                                ?>                                                        
                                                            </td>
                                                            <td>
                                                                <?php echo '<select name="notificationInputParamsp['.$notificationKey.'][]" class="form-control select2">';?>
                                                                <?php if($this->outputMetaDataList != null) : ?>
                                                                    <?php foreach($this->outputMetaDataList AS $outputKey => $outputValue){ 
                                                                        echo '<option value="'.$outputValue['META_DATA_CODE'].'"';
                                                                        if(isset($notificationValue['PARAMS'][$k]['PROCESS_PARAM_PATH'])){
                                                                            if($outputValue['META_DATA_CODE'] == $notificationValue['PARAMS'][$k]['PROCESS_PARAM_PATH']) {
                                                                                echo 'selected="selected"';
                                                                            }
                                                                        }
                                                                        echo '>'.$outputValue['META_DATA_CODE'].'</option>';
                                                                    }
                                                                    ?>
                                                                <?php endif; ?>
                                                                <?php echo '</select>';?>
                                                            </td>
                                                            <td><?php echo Form::text(array(
                                                                                'name' => 'defaultValue['.$notificationKey.'][]',
                                                                                'id' => 'defaultValue'.$notificationCounter,
                                                                                'class' => 'form-control',
                                                                                'data-name' => $notificationCounter,
                                                                                'value' => isset($notificationValue['PARAMS'][$k]['DEFAULT_VALUE']) ? $notificationValue['PARAMS'][$k]['DEFAULT_VALUE'] : null
                                                                ));     ?>
                                                            </td>
                                                        </tr>
                                                        <?php $notificationParamCounter++; ?>
                                                    <?php } ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                    </div>
                <?php endforeach; ?>
            </div>
        </form>
        <?php echo Form::hidden(array('name' => 'notificationCount', 'id' => 'notificationCount', 'value' => count($this->defaultValue))); ?>
        <?php echo Form::hidden(array('name' => 'currentNotificationIndex', 'id' => 'currentNotificationIndex', 'value' => 1)); ?>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        //changeNotification();
        
        $('.notificationId').change(function () {
            changeNotification();
        });
    });
    
    function changeNotification(){
        var index = parseInt($('#currentNotificationIndex').val());
        var notificationId = $('#notificationId'+index).val();
        $.ajax({
                type: 'post',
                url: 'mdnotification/findParams',
                data: {'notificationId' : notificationId},
                dataType: "json",
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (data) {
                    $('#notificationText'+index).val(data.text);
                    var html = '';
                    var counter = 1;
                    var paramIndex = index - 1; // PHP deer index 0 oos ehelj toolono.
                    jQuery.each(data.params, function(i, val) {
                        html += '<tr><td>'+counter+'</td><td>'+val+'<input type="hidden" name="notificationParamList['+paramIndex+'][]" value="'+val+'"></td><td>';
                        html += '<select name="notificationInputParamsp['+paramIndex+'][]" class="form-control select2">';
                            html += '<option value=""> Сонгох </option>';
                            <?php foreach($this->outputMetaDataList AS $outputKey => $outputValue) :  ?>
                                 html += '<option value="<?php echo $outputValue['META_DATA_CODE']; ?>"><?php echo $outputValue['META_DATA_CODE']; ?></option>';
                            <?php endforeach;?>
                        html += '</select>';
                        html += '</td><td><input type="text" class="form-control" name="defaultValue['+paramIndex+'][]"></td></tr>';
                        counter++;
                    });
                    $('#notificationParamTbody'+index).html(html);
                    
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initAjax();
                Core.unblockUI();
            });
    }
    
    function addNotification() {
    
        var counter = $('#notificationCount').val();
        counter++;
        var html = '<li class="nav-item notificationLi" id="notificationLi'+counter+'"><a href="#notificationTab'+counter+'" data-toggle="tab" class="nav-link active" onclick="changeCurrentNotification('+counter+')">'+counter+'</a></li>';        
        $('.notificationLi:last').after(html);
        
        var html2 = '<div class="tab-pane active" id="notificationTab'+counter+'">'+
                        '<div class="form-group row fom-row">'+
                            '<div class="col-md-4"><label for="notificationId'+counter+'">Notification сонгох:</label></div> '+
                            '<div class="col-md-8">'+
                            '<select name="notificationId[]" id="notificationId'+counter+'" class="form-control select2 notificationId" data-name="">'+
                                '<option value=""> Сонгох </option>'+
                                <?php foreach(Info::getNotificationListForSelect() AS $key => $value):?>
                                    '<option value="<?php echo $value['NOTIFICATION_ID']; ?>"><?php echo str_replace(array("'", '"'), array("&#39;", "&#34;"), Str::nlToSpace($value['TEXT_SHORT'])); ?></option>'+
                                <?php endforeach; ?>
                            '</select>'+
                            '</div>'+
                        '</div>'+
                        '<div class="form-group row fom-row">'+
                            '<div class="col-md-4"><label for="inParamCriteria'+counter+'">Process input criteria:</label></div> '+
                            '<div class="col-md-8">'+
                            '<textarea name="inParamCriteria[]" id="inParamCriteria'+counter+'" class="form-control"></textarea>'+
                            '</div>'+
                        '</div>'+
                        '<div class="form-group row fom-row">'+
                            '<div class="col-md-4"><label for="outParamCriteria'+counter+'">Process output criteria:</label></div> '+
                            '<div class="col-md-8">'+
                            '<textarea name="outParamCriteria[]" id="outParamCriteria'+counter+'" class="form-control"></textarea>'+
                            '</div>'+
                        '</div>'+
                        '<div class="form-group row fom-row">'+
                            '<div class="col-md-4"><label for="userQuery'+counter+'">Хэрэглэгчдийн жагсаалт татах criteria:</label></div> '+
                            '<div class="col-md-8">'+
                            '<textarea name="userQuery[]" id="userQuery'+counter+'" class="form-control"></textarea>'+
                            '</div>'+
                        '</div>'+
                        '<div class="form-group row fom-row">'+
                            '<div class="col-md-4"><label for="notifyDate'+counter+'">Notification илгээх огноо (логик):</label></div> '+
                            '<div class="col-md-8">'+
                            '<textarea name="notifyDate[]" id="notifyDate'+counter+'" class="form-control"></textarea>'+
                            '</div>'+
                        '</div>'+
                        '<div class="form-group row fom-row">'+
                            '<div class="col-md-4"><label for="notificationText">Notification текст:</label></div> '+
                            '<div class="col-md-8">'+
                            '<textarea name="notificationText[]" id="notificationText'+counter+'" class="form-control" readonly="readonly"></textarea>'+
                            '</div>'+
                        '</div>'+

                        '<div class="form-group row fom-row">'+
                            '<div class="col-md-12">'+
                                '<table class="table" id="notificationTable'+counter+'">'+
                                    '<thead>'+
                                        '<tr>'+
                                            '<th>№</th>'+
                                            '<th>Хувьсагч</th>'+
                                            '<th>Гаралтын параметр</th>'+
                                        '</tr>'+
                                    '</thead>'+
                                    '<tbody id="notificationParamTbody'+counter+'">'+
                                    '</tbody>'+
                                '</table>'+
                            '</div>'+
                        '</div>'+
                '</div>';
        $('.tab-pane:last').after(html2); // append
        
        $('#currentNotificationIndex').val(counter);// set currenct notification index
        $('#notificationCount').val(counter); // set sum of nofication
        
        $('.notificationId').change(function () {
            changeNotification();
        });
        Core.initAjax();
        $('#notificationLi'+counter).trigger('click');
    }
    
    function changeCurrentNotification(counter) {
        $('#currentNotificationIndex').val(counter);
    }
</script>
