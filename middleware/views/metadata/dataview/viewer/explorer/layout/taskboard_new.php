<div class="dv-task-board" id="main-item-container">
    <?php
    if ($this->columnList) {
        var_dump($this->columnList);die;
        $taskArray = array();
        
        if ($this->recordList) {
            
            foreach ($this->recordList as $row) {
                foreach ($this->columnList as $col) {
                    if ($row['wfmstatusid'] == $col['wfmstatusid']) {
                        $taskArray[$col['wfmstatusid']][] = $row;
                    }
                }
            }
        }
        
        foreach ($this->columnList as $col) {
    ?>
    <div class="dv-t-board-col">
        <div class="dv-t-board-title">
            <?php echo $col['wfmstatusname']; ?>&nbsp;&nbsp;<i class="icon-arrow-down15"></i>
        </div>
        <div class="dv-t-board-body" data-status-id="<?php echo $col['wfmstatusid']; ?>" data-color="<?php echo $col['wfmstatuscolor']; ?>">
            <?php
            if (isset($taskArray[$col['wfmstatusid']])) {
                
                $taskList = $taskArray[$col['wfmstatusid']];
                
                foreach ($taskList as $taskRow) {
            ?>
            <div class="dv-t-board-row" style="border-left-color: <?php echo $taskRow['wfmstatuscolor']; ?>" data-id="<?php echo $taskRow['id']; ?>" data-status-id="<?php echo $taskRow['wfmstatusid']; ?>">
                
                <div class="dv-t-board-name">
                    <?php echo $taskRow['companyname']; ?>
                </div>
                <div class="duedate">
                    <i class="icon-calendar"></i> 05.25
                </div>
                <div class="dv-t-board-descr">
                    <?php echo $taskRow['crm_description']; ?>
                </div>
                <!-- <a href="javascript:void(0);" class="btn btn-success btn-circle btn-sm detailbtn">Дэлгэрэнгүй...</a> -->
            </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
    <?php
        }
    }
    ?>
</div>

<style type="text/css">
    .body-top-menu-style .content, .content-wrapper {
        background: #eee;
    }
    .explorer-table-cell {
        background: none !important;
    }
    .dv-task-board {
        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        border-bottom: 1px #999 solid;
    }
    .dv-t-board-col {
        /* float: left;
        width: 280px; */
        margin-right: 10px;
        padding: 0;
        /* border-top: 1px solid #CCC; */
        margin-bottom: 40px;
        /* padding-top: 10px; */
    }
    .dv-t-board-title {
        font-size: 20px;
        font-weight: 500;
        padding-left: 20px;
        background: #9ea2b3;
        color: #FFF;
        padding-top: 5px;
        padding-bottom: 5px;
    }
    .dv-t-board-title:after {
        content: "";
        height: 1px;
        width: 100%;
        background: #000;
    }
    .dv-t-board-body {
        /* margin-top: 10px; */
        padding: 5px 2px 0 2px;
        overflow-x: hidden;
        overflow-y: auto;
    }
    .dv-t-board-body.drop-hover {
        background-color: #ccc;
    }
    .dv-t-board-row {
        /* background-color: #fff; */
        /* background: linear-gradient(to bottom, #6f07bc 0%,#3e056c 100%); */
        background: linear-gradient(to bottom, #FFF 0%,#FFF 100%);

        /* border-radius: 2px;
        -webkit-box-shadow: 0px 0px 5px 0px rgba(186,186,186,1);
        -moz-box-shadow: 0px 0px 5px 0px rgba(186,186,186,1);
        box-shadow: 0px 0px 5px 0px rgba(186,186,186,1); */
        padding: 10px;
        margin-bottom: 10px;
        border: 0;
        /* border-left: 3px blue solid;
        border-top: 1px transparent solid; 
        border-bottom: 1px transparent solid; 
        border-right: 1px transparent solid; */
        float: left;
        width: 190px;
        margin-right: 10px;
        height: 200px;
        color: #FFF;
        /* display: grid; */
        border-top: 5px solid #9ea2b3;
        margin-top: 10px;
        /* opacity: 0.8; */
    }
    .dv-t-board-row:hover {
        background: linear-gradient(to bottom, #e0e0e0 0%,#e0e0e0 100%);
        opacity: 1;
        cursor: pointer;
    }
    .dv-t-board-name {
        font-weight: 500;
        font-size: 15px;
        text-transform: uppercase;
        line-height: normal;
        height: 40px;
        color: #000;
    }
    .dv-t-board-descr {
        padding-top: 5px;
        font-size: 14px;
        line-height: normal;
        color: #555;
        border-top: 1px solid #aaa;
    }
    .card-current { 
        border-top: 1px #999 dashed; 
        border-bottom: 1px #999 dashed; 
        border-right: 1px #999 dashed; 
        filter: alpha(opacity=50); 
        opacity: 0.5;
    }
    .card-rotate {
        -ms-transform: rotate(6deg); /* IE 9 */
        -webkit-transform: rotate(6deg); /* Chrome, Safari, Opera */
        transform: rotate(6deg);
    }
    .duedate {
        font-size: 28px;
        font-weight: bold;
        color: #ab11d0;
        height: 80px;
    }
    .duedate i {
        /* color: #bb82e6; */
    }
    a.detailbtn {
        position: absolute;
        bottom: 10px;
        left: 10px;
    }
</style>

<script type="text/javascript">
$(function(){
    
    if (!$("link[href='assets/custom/css/animate/animate.min.css']").length) {
        $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/css/animate/animate.min.css"/>');
    }
    
    var dynamicHeight = $(window).height() - objectdatagrid_<?php echo $this->dataViewId; ?>.offset().top - 50;
    //$('.dv-t-board-body').css({'height': dynamicHeight});
    
    $('.dv-t-board-row').draggable({
        stack: '.dv-task-board .dv-t-board-body',
        revert: 'invalid',
        helper: 'clone',
        cursor: 'move',
        scroll: true,
        drag: function(event, ui) {
            ui.helper.width($(this).width());
            ui.helper.addClass('bg-grey-gallery card-rotate');
            $(ui.helper.prevObject).addClass('card-current');
            ui.helper.css('z-index', '9999');
        },
        stop: function(event, ui) {
            ui.helper.width($(this).width());
            $(ui.helper.prevObject).removeClass('card-current');
            $('.dv-task-board .dv-t-board-row').css("z-index", 'auto');
        }
    });
    
    $('.dv-t-board-body').droppable({
        accept: '.dv-t-board-row',
        helper: 'clone',
        hoverClass: 'drop-hover',
        drop: function(event, ui) {
            
            var dthis = $(this);
            var currStatusId = ui.draggable.attr('data-status-id');
            var nextStatusId = dthis.attr('data-status-id');
            
            if (currStatusId != nextStatusId) {
                
                var taskId = ui.draggable.attr('data-id');
                var nextStatusColor = dthis.attr('data-color');
                
                $.ajax({
                    type: 'post',
                    url: 'mdobject/setRowWfmStatus',
                    data: {
                        metaDataId: '<?php echo $this->dataViewId; ?>', 
                        newWfmStatusid: nextStatusId, 
                        description: 'taskboard', 
                        dataRow: {id: taskId, wfmstatusid: currStatusId}
                    },
                    dataType: 'json',
                    beforeSend: function(){
                        Core.blockUI({
                            target: dthis,
                            animate: true
                        });
                    },
                    success: function(data){
                        if (data.status === 'success') {
                            ui.draggable.css('border-left-color', nextStatusColor);
                            ui.draggable.attr('data-status-id', nextStatusId);

                            $(ui.draggable).fadeOut('fast', function() {
                                dthis.prepend(ui.draggable);
                                $(ui.draggable).addClass('animated bounceIn').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
                                    $(ui.draggable).removeClass('animated bounceIn');
                                });
                            });
                            $(ui.draggable).fadeIn('fast');
                            $(ui.draggable).removeClass('card-current');
                        }
                        Core.unblockUI(dthis);
                    }
                });
            }
        }
    });
    
    $(window).bind('resize', function() {
        var dynamicHeight = $(window).height() - objectdatagrid_<?php echo $this->dataViewId; ?>.offset().top - 50;
        //$('.dv-t-board-body').css({'height': dynamicHeight});
    });
});    
</script>