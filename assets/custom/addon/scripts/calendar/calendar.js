var Calendar=function(){


  return {
    //main function to initiate the module
    init: function(){
      Calendar.initCalendar();
    },
    initCalendar: function(){

      if(!jQuery().fullCalendar){
        return;
      }

      var date=new Date();
      var d=date.getDate();
      var m=date.getMonth();
      var y=date.getFullYear();

      var h={};

//      if(Core.isRTL()){
//        if($('#calendar').parents(".portlet").width() <= 720){
//          $('#calendar').addClass("mobile");
//          h={
//            right: 'title, prev, next',
//            center: '',
//            left: 'agendaDay, agendaWeek, month, today'
//          };
//        } else {
//          $('#calendar').removeClass("mobile");
//          h={
//            right: 'title',
//            center: '',
//            left: 'agendaDay, agendaWeek, month, today, prev,next'
//          };
//        }
//      } else {
//        if($('#calendar').parents(".portlet").width() <= 720){
//          $('#calendar').addClass("mobile");
//          h={
//            left: 'title, prev, next',
//            center: '',
//            right: 'today,month,agendaWeek,agendaDay'
//          };
//        } else {
//          $('#calendar').removeClass("mobile");
//          h={
//            left: 'title',
//            center: '',
//            right: 'prev,next,today,month,agendaWeek,agendaDay'
//          };
//        }
//      }


      $('#calendar').fullCalendar('destroy'); // destroy the calendar
      $('#calendar').fullCalendar({//re-initialize the calendar
        header: {
            left: 'title',
            center: '',
            right: 'prev,next,today,month,agendaWeek,agendaDay'
          },
        defaultView: 'month',
        slotMinutes: 5,
        editable: false,
        droppable: true, // this allows things to be dropped onto the calendar !!!
        drop: function(date, allDay){ // this function is called when something is dropped

          // retrieve the dropped element's stored Event Object
          var originalEventObject=$(this).data('eventObject');
          // we need to copy it, so that multiple events don't have a reference to the same object
          var copiedEventObject=$.extend({}, originalEventObject);

          // assign it the date that was reported
          copiedEventObject.start=date;
          copiedEventObject.allDay=allDay;
          copiedEventObject.className=$(this).attr("data-class");

          // render the event on the calendar
          // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
          $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

          // is the "remove after drop" checkbox checked?
          if($('#drop-remove').is(':checked')){
            // if so, remove the element from the "Draggable Events" list
            $(this).remove();
          }
        },
        events: 'calendar/getEvents/',
        eventDataTransform: function(dataResult){
          return {
            id: dataResult.ID,
            title: dataResult.TITLE,
            start: dataResult.START_DATE,
            end: dataResult.END_DATE,
            description: dataResult.DESCRIPTION,
            contact: dataResult.CONTACT,
            location: dataResult.LOCATION,
            color: dataResult.COLOR,
            allDay: false
          };
        },
        timeFormat: 'H(:mm)',
        eventClick: function(event, jsEvent, view){
          var dialogName='showEventDialog';
          if(!$("#" + dialogName).length){
            $('<div id="' + dialogName + '" class="hide fc-calendar"></div>').appendTo('body');
          }

          var $showEventModal=$("#showEventModal");
          $showEventModal.find('#modalTitle').html(event.title);
          $showEventModal.find('#eventId').val(event.id);
          $showEventModal.find('#eventDescription').html(event.description);
          $showEventModal.find('#eventStartDate').html(event._start._i);
          $showEventModal.find('#eventEndDate').html(event._end._i);
          $showEventModal.find('#eventLocation').html(event.location);
          $showEventModal.find('#eventContact').html(event.contact);


          var $dialogObj=$("#" + dialogName);
          $dialogObj.html($showEventModal.html());

          $dialogObj.dialog({
            appendTo: 'body',
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: event.title,
            width: 500,
            minWidth: 400,
            height: 250,
            modal: true,
            open: function(){

            },
            close: function(){
              $dialogObj.dialog('close');
            },
            buttons: [
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
        }
      });

    }

  };

}();