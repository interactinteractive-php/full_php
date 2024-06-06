var isSysUpdateAddonScript = true;

function sysUpdatePopup() {
    
    $.ajax({
        type: 'post',
        url: 'mdupgrade/sysUpdatePopup',
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function (data) {
            
            var $dialogName = 'dialog-sysupdatepopup';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);

            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: 'System upgrade',
                width: 650,
                height: "auto",
                modal: true,
                position: {my: 'top', at: 'top+50'}, 
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            
            $dialog.dialog('open');
            
            Core.unblockUI();
        }
    });
}