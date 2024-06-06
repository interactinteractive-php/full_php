/* global Calendar, Metronic, departmentBasket */

var homeCalendar=function(){
  var addEventForm=$('#addEventForm');
  var initEvent=function(){

    $("#addEventBtn").click(function(){
      var dialogName='addEventDialog';
      if(!$("#" + dialogName).length){
        $('<div id="' + dialogName + '" class="hide fc-calendar"></div>').appendTo('body');
      }

      var $addEventModal=$("#addEventModal");
      var $dialogObj=$("#" + dialogName);
      $dialogObj.html($addEventModal.html());
      $dialogObj.find('#colorInput').html('<input type="hidden" id="color" name="color" value="#123456" />');
      $dialogObj.find('#colorpicker').farbtastic('#color');
      $dialogObj.dialog({
        appendTo: 'body',
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: event.title,
        width: 500,
        minWidth: 400,
        height: 500,
        modal: true,
        open: function(){
          Core.initDateTimeInput($dialogObj);
        },
        close: function(){
          $dialogObj.dialog('close');
        },
        buttons: [
          {text: save_btn, class: 'btn btn-sm green', click: function(){
              var data=$dialogObj.find("#addEventForm").serialize();

              Core.blockUI();
              $.ajax({
                url: "calendar/addEvent",
                type: "POST",
                data: data,
                dataType: "JSON",
                success: function(response){
                  PNotify.removeAll();
                  if(response && response !== null && typeof response[0] !== "undefined"){
                    new PNotify({
                      title: 'Success',
                      text: 'Амжилттай хадгалагдлаа',
                      type: 'success',
                      sticker: false
                    });
                    Calendar.init();
                    $dialogObj.dialog('close');
                  } else {
                    new PNotify({
                      title: 'Error',
                      text: 'Алдаа гарлаа',
                      type: 'error',
                      sticker: false
                    });
                  }
                },
                error: function(jqXHR, exception){
                  Core.unblockUI();
                }
              }).complete(function(){
                Core.unblockUI();
              });
            }},
          {text: close_btn, class: 'btn btn-sm blue-hoki', click: function(){
              $dialogObj.dialog('close');
            }}
        ]
      }).dialogExtend({
        "closable": true,
        "maximizable": true,
        "minimizable": true,
        "collapsable": true,
        "dblclick": "maximize",
        "minimizeLocation": "left",
        "icons": {
          "close": "ui-icon-circle-close",
          "maximize": "ui-icon-extlink",
          "minimize": "ui-icon-minus",
          "collapse": "ui-icon-triangle-1-s",
          "restore": "ui-icon-newwin"
        }
      });
      $dialogObj.dialog('open');
    });
  };
  return{
    init: function(){
      initEvent();
      Calendar.init();
    }
  };
}();