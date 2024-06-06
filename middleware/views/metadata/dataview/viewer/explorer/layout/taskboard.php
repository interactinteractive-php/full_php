<div class="dv-task-board" id="main-item-container">
    <?php
    if ($this->columnList) {
        
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
     
        
        $firstRow = $this->recordList[0];
     
        
        $numberField = ' echo "";';
        $name1 = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['name1']);
        $name2 = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['name2']); 


        if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['userpicture'])) {
            $pic = $this->row['dataViewLayoutTypes']['explorer']['fields']['userpicture'];
        }else{
            $pic = '';
        }

        if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['date'])) {
            $createddate = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['date']); 
        }else{
            $createddate = '';
        }
        if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['number'])) {
            
            $lowerNumberField = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['number']);
            
            if (array_key_exists($lowerNumberField, $firstRow)) {
                $numberField = 'echo "<span class=\"badge bg-blue-400 align-self-center ml-auto\">".$taskRow[$lowerNumberField]."</span>";';
            }
        }
        
        foreach ($this->columnList as $col) {
    ?>
    <div class="dv-t-board-col">
        <!-- <div> -->
            <!-- <div class="triangle-left"></div> -->
            <div class="dv-t-board-title justify-content-center">
                <?php echo $col['wfmstatusname']; ?>
            </div>
            <!-- <div class="triangle-right"></div> --> 
        <!-- </div> -->
        <div class="dv-t-board-body" data-status-id="<?php echo $col['wfmstatusid']; ?>" data-color="<?php echo $col['wfmstatuscolor']; ?>">
            <?php
            if (isset($taskArray[$col['wfmstatusid']])) {
                
                $taskList = $taskArray[$col['wfmstatusid']];
               // pa($taskList);
                foreach ($taskList as $taskRow) {
                    $rowJson = htmlentities(json_encode($taskRow), ENT_QUOTES, 'UTF-8');
            ?>
            <div class="dv-t-board-row dv-explorer-row media" style="border-left-color: <?php echo $taskRow['wfmstatuscolor']; ?>" onclick="clickItemTaskBoard_<?php echo $this->dataViewId; ?>(this);" data-row-data="<?php echo $rowJson; ?>" data-id="<?php echo $taskRow['id']; ?>" data-status-id="<?php echo $taskRow['wfmstatusid']; ?>">
               
                <div class="media-body">
                    <div class="dv-t-board-name">
                        <?php echo $taskRow[$name1]; ?>
                        <?php eval($numberField); ?> 
                    </div>
                    <div class="dv-t-board-descr">
                        <?php echo $taskRow[$name2]; ?>
                    </div>
                    <?php  if (isset($taskRow[$createddate])) { ?>
                        <span class="taskcreatetime">
                            <?php echo  Date::formatter($taskRow[$createddate], 'Y-m-d') ; ?>
                        </span>
                    <?php }?>

                    <?php  if (isset($taskRow[$pic])) { ?>
                        <div class="mt-2 text-right">
                        <?php 
                            $img_path = '';
                            $imgs = explode(",", $taskRow[$pic]);
                            foreach($imgs as $img) {
                                $img = trim($img);
                                $img_path = "<img src='$img' class='rounded-circle ml-1 ' width='20' height='20' alt='profile'>";
                                echo $img_path;
                            }    
                        ?>
                        </div>
                    <?php }?>
                </div>
            </div>
            <?php
                }
            }
            ?>
            <div class="addnewbtn">
                <a href="javascript:;" data-html2canvas-ignore="true" onclick="taskBoardProcess(this, '1556097186873', '<?php echo $col['wfmstatusid']; ?>')" class="btn btn-success btn-circle btn-sm"><i class="icon-plus3 font-size-12"></i> Нэмэх</a>
            </div>
        </div>
    </div>
    <?php
        }
    }
    ?>
</div> 


<a href="javascript:;" class="" id="scr_window" data-toggle="modal" data-target="#modal_backdrop">Screenshot</a>

<style type="text/css">

    @media (min-width: 576px){
        .modal-dialog {
            max-width: 800px !important;
            margin: 1.75rem auto;
        }
    }
    .page-content{
        overflow:hidden;
    }
    .explorer-table-cell {
        background: none !important;
    }
    .scrwindow{
        position: absolute;
        top: -45px;
        right: 15%;
        background: #cf334f;
        padding: 3px 15px;
        border-radius: 4px;
        color: #fff;
    }
   .explorer-table-cell , .explorer-table, .explorer-table-row {
        display: block !important;
    }
    .dv-task-board .media-body .taskcreatetime{
        position: absolute;
        top: -10px;
        right: -5px;
        font-size: 11px;
    }
    .dv-task-board .media-body{
        position: relative;
        
    }
    .dv-task-board {
        width: 100%;
        display: flex;
        overflow-x: auto;
        overflow-y: hidden;
        /* border-bottom: 1px #999 solid; */
    }
    .dv-t-board-col {
        float: left;
        width: 290px;
        /* margin-right: 10px; */
        padding: 0;
    }
    .dv-t-board-title {
        background: url('assets/custom/img/bgtriangle.png') no-repeat;
        font-size: 15px;
        background-size: contain;
        font-weight: 500;
        padding: 2px 14px 2px 10px;
        color: #fff;
        width: 100%;
        float: left;
        /* margin-left: -11%; */
        text-align: center;
        align-items: center;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        /* overflow: hidden; */
        height: 40px;
        line-height: normal;
    }
    /* .dv-t-board-title:after {
        content: "";
        float:left;
        display: inline-block;
        width: 11%;
        height: 0;
        border-style: solid;
        border-width: 20px 0 20px 25px;
        border-color: transparent transparent transparent #f0f0f0;
        background: #6980fe;
        margin-left: -4%;
    }
    .dv-t-board-title:before {
        content: "";
        float: right;
        display: inline-block;
        width: 11%;
        height: 0;
        border-style: solid;
        border-width: 20px 0 20px 25px;
        border-color: transparent transparent transparent #6980fe;
        margin-right: -15%;
    } */
    .dv-t-board-title {
        /* background: #000; */
    }
    .dv-t-board-body {
        display: inline-block;
        margin-top: 10px;
        padding: 5px 2px 0 2px;
        overflow-x: hidden;
        overflow-y: auto;
        width: 280px;
        min-height: 50px;
        height: auto !important
    }
    .dv-t-board-body.drop-hover {
        background-color: #ccc;
    }
    .dv-t-board-row {
        background-color: #fff;
        border-radius: 2px;
        -webkit-box-shadow: 0px 0px 5px 0px rgba(186,186,186,1);
        -moz-box-shadow: 0px 0px 5px 0px rgba(186,186,186,1);
        box-shadow: 0px 0px 5px 0px rgba(186,186,186,1);
        padding: 10px;
        margin-bottom: 10px;
        border-left: 3px blue solid;
        border-top: 1px transparent solid; 
        border-bottom: 1px transparent solid; 
        border-right: 1px transparent solid; 
    }
    .dv-t-board-row:hover {
        background-color: #eee;
        cursor: pointer;
    }
    .dv-t-board-name {
        display: flex;
        font-weight: 700;
    }
    .dv-t-board-descr {
        margin-top: 5px;
        font-size: 12px;
        line-height: 12px;
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
    .addnewbtn a {
        background-color: #dedede;
        width: 100%;
        color: #777;
    }
</style>

<div id="modal_backdrop" class="modal fade show" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Screenshot</h5>
                <button type="button" class="close scr_btn" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div id="show_img"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link scr_btn" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $.when(
        $.getScript(URL_APP+'assets/custom/addon/plugins/html2canvas/html2canvas.js') 
    ).then(function () {
        
        $(".scr_btn").click(function() { 
            $('body').find('.page-content').css('overflow','hidden'); // Change absolute to initial
            $('body').find('.content-wrapper').css('overflow','auto'); // Change absolute to initial
        });

        $("#scr_window").click(function() { 
           
            $('body').find('.page-content').css('overflow','unset'); // Change absolute to initial
            $('body').find('.content-wrapper').css('overflow','unset'); // Change absolute to initial
            window.scrollTo(0,0);  

            var useHeight = $('.dv-task-board').prop('scrollHeight');

            window.scrollTo(0, document.body.scrollHeight || document.documentElement.scrollHeight);

            html2canvas($(".dv-task-board"), {
                onrendered: function(canvas) {
                    theCanvas = canvas;
                    var link=document.createElement("a");
                    link.href=theCanvas.toDataURL('image/jpg'); 
                    link.download = 'screenshot.jpg';
                    link.click();
                    $("<img/>", {
                    id: "image",
                    src: canvas.toDataURL("image/jpg"),
                    width: '100%',
                    height: '100%'
                    }).appendTo($("#show_img").empty());
                    document.$("#show_img").appendChild(canvas);
                }
            });
        });
    }, function () {
        console.log('an error occurred somewhere');
    });

$(function(){
    
    // if (!$("link[href='assets/custom/css/animate/animate.min.css']").length) {
    //     $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/css/animate/animate.min.css"/>');
    // }
    
    var dynamicHeight = $(window).height() - objectdatagrid_<?php echo $this->dataViewId; ?>.offset().top - 100;
    $('.dv-t-board-body').css({'height': dynamicHeight});
    
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
        $('.dv-t-board-body').css({'height': dynamicHeight});
    });
});    

function taskBoardProcess(elem, pid, recordid) {
        var $dialogName = 'dialog-taskboard-bp';
        if (!$('#' + $dialogName).length) {
            $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName), fillDataParams = '', 
            saveUrl = 'mdwebservice/runProcess';
            fillDataParams = 'wfmStatusId='+recordid;

        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: {
                metaDataId: pid, 
                isDialog: true, 
                isSystemMeta: false,
                fillDataParams: fillDataParams
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...', 
                    boxed: true
                });
            },
            success: function (data) {

                $dialog.empty().append(data.Html);

                var $processForm = $dialog.find('#wsForm'), processUniqId = $processForm.parent().attr('data-bp-uniq-id');
                var buttons = [
                    {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                        if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                            $processForm.validate({ 
                                ignore: '', 
                                highlight: function(element) {
                                    $(element).addClass('error');
                                    $(element).parent().addClass('error');
                                    if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                        $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                            var tabId = $(tab).attr('id');
                                            $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                        });
                                    }
                                },
                                unhighlight: function(element) {
                                    $(element).removeClass('error');
                                    $(element).parent().removeClass('error');
                                },
                                errorPlacement: function(){} 
                            });

                            var isValidPattern = initBusinessProcessMaskEvent($processForm);

                            if ($processForm.valid() && isValidPattern.length === 0) {
                                $processForm.ajaxSubmit({
                                    type: 'post',
                                    url: saveUrl,
                                    dataType: 'json',
                                    beforeSubmit: function (formData, jqForm, options) {
                                    },
                                    beforeSend: function () {
                                        Core.blockUI({
                                            boxed: true, 
                                            message: 'Түр хүлээнэ үү'
                                        });
                                    },
                                    success: function (responseData) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: responseData.status,
                                            text: responseData.message,
                                            type: responseData.status, 
                                            sticker: false
                                        });

                                        if (responseData.status === 'success') {
                                            var defaultCriteria = {};
                                            defaultCriteria.defaultCriteriaData = $("div#dv-search-<?php echo $this->dataViewId; ?> form#default-criteria-form").serialize();
                                            explorerRefresh_<?php echo $this->dataViewId; ?>(elem, defaultCriteria);
                                            /*if (isMulti) {
                                                dataGrid.datagrid('reload');
                                            } else {
                                                $(elem).closest('div.datagrid-view').children('table').datagrid('reload');
                                            }*/
                                            $dialog.dialog('close');
                                        } 
                                        Core.unblockUI();
                                    },
                                    error: function () {
                                        alert("Error");
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }    
                    }},
                    {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ];

                var dialogWidth = data.dialogWidth, dialogHeight = data.dialogHeight;

                if (data.isDialogSize === 'auto') {
                    dialogWidth = 1200;
                    dialogHeight = 'auto';
                }

                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: dialogWidth,
                    height: dialogHeight,
                    modal: true,
                    closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape), 
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: buttons
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
                if (data.dialogSize === 'fullscreen') {
                    $dialog.dialogExtend('maximize');
                }
                $dialog.dialog('open');
            },
            error: function () {
                alert('Error');
                Core.unblockUI();
            }
        }).done(function () {
            Core.initBPAjax($dialog);
            Core.unblockUI();
        });
    }

function clickItemTaskBoard_<?php echo $this->dataViewId; ?>(elem) {
    var $this = $(elem);
    $this.parent().find('.selected-row').removeClass('selected-row');
    $this.addClass('selected-row');        
    clickItem_<?php echo $this->dataViewId; ?>(elem);
}
</script>