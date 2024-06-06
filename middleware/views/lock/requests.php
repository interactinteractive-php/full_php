<table class="table table-hover lock-requests-tbl">
    <thead>
        <tr>
            <th>#</th>
            <th>Үзүүлэлтийн код</th>
            <th>Үзүүлэлтийн нэр</th>
            <th>Хэрэглэгчийн нэр</th>
            <th>Тайлбар</th>
            <th>Дуусах огноо</th>
            <th>Илгээсэн огноо</th>
            <th class="text-center">Зөвшөөрөх</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($this->requests) {
            foreach ($this->requests as $k => $req) {
        ?>
            <tr>
                <td><?php echo ++$k; ?>.</td>
                <td><?php echo $req['META_DATA_CODE']; ?></td>
                <td><?php echo $req['META_DATA_NAME']; ?></td>
                <td><?php echo $req['USERNAME']; ?></td>
                <td><?php echo $req['DESCRIPTION']; ?></td>
                <td><?php echo $req['END_TIME']; ?></td>
                <td><?php echo $req['CREATED_DATE']; ?></td>
                <td class="text-center">
                    <a class="btn btn-success btn-circle btn-sm" onclick="lockRequestAccept('<?php echo $req['ID']; ?>');" href="javascript:;"><i class="fa fa-check"></i></a>
                </td>
            </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>

<script type="text/javascript">
$(function(){
    $('table.lock-requests-tbl > tbody').on('click', 'tr', function(){
        var $this = $(this);

        $('table.lock-requests-tbl tbody > tr.selected').removeClass('selected');
        $this.addClass('selected');
    });
});    
function lockRequestAccept(id) {
    var $dialogName = 'dialog-request-accept';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    
    var $dialog = $("#" + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdlock/requestAcceptForm', 
        data: {id: id}, 
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: 'Loading...', 
                boxed: true 
            });
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 400,
                height: 'auto',
                modal: true,
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {
                        $.ajax({
                            type: 'post',
                            url: 'mdlock/requestAccept',
                            data: $('#request-accept-form').serialize(), 
                            dataType: 'json',
                            beforeSend: function(){
                                Core.blockUI({
                                    message: 'Loading...',
                                    boxed: true
                                });
                            },
                            success: function(dataSub) {
                                Core.unblockUI();

                                PNotify.removeAll();
                                new PNotify({
                                    title: dataSub.status,
                                    text: dataSub.message,
                                    type: dataSub.status,
                                    sticker: false
                                });  

                                if (dataSub.status == 'success') {
                                    $dialog.dialog('close');
                                    requestCount();
                                    lockRequest();
                                } 
                            }
                        });
                    }}, 
                    {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
        },
        error: function () {
            alert("Error");
        }
    }).done(function(){
        Core.initDateTimeInput($dialog);
        Core.unblockUI();
    });
}    
</script>