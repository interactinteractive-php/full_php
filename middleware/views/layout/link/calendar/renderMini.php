<div class="card light shadow">
    <div class="card-body">  
        <div id="mini-calendar-<?php echo $this->metaDataId.'-'.$this->cellId; ?>" class="mini-fc-calendar"></div>
    </div>
</div> 
<script type="text/javascript">
$(function(){
    if (!jQuery().fullCalendar) {
        return;
    }
    
    var calIdName = $('#mini-calendar-<?php echo $this->metaDataId.'-'.$this->cellId; ?>');
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    var h = {};

    if (Core.isRTL()) {
        if (calIdName.parents(".card").width() <= 720) {
            calIdName.addClass("mobile");
            h = {
                right: 'prev, next, title',
                center: '',
                left: 'today'
            };
        } else {
            calIdName.removeClass("mobile");
            h = {
                right: 'title',
                center: '',
                left: 'today'
            };
        }
    } else {
        if (calIdName.parents(".card").width() <= 720) {
            calIdName.addClass("mobile");
            h = {
                left: 'prev, next, title',
                center: '',
                right: 'today'
            };
        } else {
            calIdName.removeClass("mobile");
            h = {
                left: 'title',
                center: '',
                right: 'today'
            };
        }
    }

    calIdName.fullCalendar('destroy');
    calIdName.fullCalendar({
        header: h,
        defaultView: 'month',
        slotMinutes: 15,
        editable: false,
        droppable: false,
        height: <?php echo str_replace(array("px", "%"), "", $this->cellAttr['height']); ?>
    });
});    
</script>