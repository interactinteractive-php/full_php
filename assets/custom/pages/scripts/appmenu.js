function appLicenseExpired(elem, endDate) {
    var $dialogName = 'dialog-app-info';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'appmenu/licenseExpired',
        data: {appName: $(elem).find('[data-app-name="true"]').text(), endDate: endDate}, 
        dataType: 'json',
        beforeSend: function(){
            Metronic.blockUI({
                animate: true
            });
        },
        success: function(data){
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 597,
                height: "auto",
                modal: true,
                close: function(){
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function(){
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            Metronic.unblockUI();
        }
    });
}
function appLicenseExpireWait(elem, endDate, days, href) {
    var $dialogName = 'dialog-app-info';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'appmenu/licenseExpireWait',
        data: {appName: $(elem).find('[data-app-name="true"]').text(), endDate: endDate, days: days}, 
        dataType: 'json',
        beforeSend: function(){
            Metronic.blockUI({
                animate: true
            });
        },
        success: function(data){
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 597,
                height: "auto",
                modal: true,
                close: function(){
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {html: data.continue_btn, class: 'btn green-meadow btn-sm', click: function(){
                        Metronic.blockUI({
                            message: 'Loading...', 
                            boxed: true
                        });      
                        window.location = href;
                    }}, 
                    {html: data.close_btn, class: 'btn blue-madison btn-sm', click: function(){
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            Metronic.unblockUI();
        }
    });
}
function appLicenseExpireBefore(elem, endDate, days, href) {
    var $dialogName = 'dialog-app-info';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'appmenu/licenseExpireBefore',
        data: {appName: $(elem).find('[data-app-name="true"]').text(), endDate: endDate, days: days}, 
        dataType: 'json',
        beforeSend: function(){
            Metronic.blockUI({
                animate: true
            });
        },
        success: function(data){
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 597,
                height: "auto",
                modal: true,
                close: function(){
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {html: data.continue_btn, class: 'btn green-meadow btn-sm', click: function(){
                        Metronic.blockUI({
                            message: 'Loading...', 
                            boxed: true
                        });    
                        window.location = href;
                    }}, 
                    {html: data.close_btn, class: 'btn blue-madison btn-sm', click: function(){
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            Metronic.unblockUI();
        }
    });
}