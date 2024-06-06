function helpDeskPopup(hdOrgId, hdSystemId)
{
    if (!$("#dialog-ticket").length) {
        $('<div id="dialog-ticket" class="hide"></div>').appendTo('body');
    }
    
    var $dialogName = 'dialog-ticket';
    
    $.ajax({
        type: 'post',
        url: 'mdhelpdesk/viewTickets',
        data: {orgId: hdOrgId, sysId: hdSystemId},
        beforeSend:function(){
            App.blockUI("body");
        },
        success:function(data){
            $("body").find("#"+$dialogName).empty().append(data);  
            $("#"+$dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: "HelpDesk - Санал хүсэлтүүд",
                width: 800,
                height: 600,
                modal: true,      
                closeOnEscape: false,
                dialogClass: 'no-close',
                open: function() {                       
                    $(this).parent().find(".ui-dialog-titlebar-close").hide();                           
                }   
            });
            $("#"+$dialogName).dialog('open');
            App.unblockUI("body");
        },
        error:function(){
            alert("Error");
        }
    });
    
}