<script type="text/javascript" src="assets/custom/addon/plugins/jquery-orgchart/orgchartjs/orgchart.js?v=4"></script>

<div id="orgchart-<?php echo $this->dataViewId; ?>" class="ba-orgchart-container" style="background-color: #E5F4FF;"></div>

<style type="text/css">
    #orgchart-<?php echo $this->dataViewId; ?> .node_name {
        min-height: 65px;
        max-height: 65px;
        height: 65px;
        color: #fff;
        text-align: center;
        font-size: 16px;
    }
    #orgchart-<?php echo $this->dataViewId; ?> .node_name div {
        color: #fff;
        position: relative;
        top: 50%;
        transform: translateY(-50%);
        text-align: center;
    }
    #orgchart-<?php echo $this->dataViewId; ?> .node_name a {
        display: block;
        color: #fff;
    }
    #orgchart-<?php echo $this->dataViewId; ?> .node_name a:hover {
        text-decoration: underline;
        cursor: pointer;
    }
</style>

<script type="text/javascript">
$(function() {
    
    /*var nodes_<?php echo $this->dataViewId; ?> = [
        { id: 1, name: "ДЭД БҮТЭЦ ТӨЛӨВЛӨЛТИЙН ХЭЛТЭС Ashley Barnett", code: "001" },
        { id: 2, pid: 1, name: "Ashley Barnett", code: "Mobi011523" },
        { id: 3, pid: 2, name: "Caden Ellison", code: "Mobi8-1523 fff" },
        { id: 4, pid: 2, name: "Elliot Patel", code: "001"},
        { id: 5, pid: 2, name: "Lynn Hussain", code: "001" },
        { id: 6, pid: 3, name: "Tanner May", code: "001" },
        { id: 7, pid: 3, name: "Fran Parsons", code: "001", color: 'red'}, 
        { id: 8, pid: 2, name: "Bataa", code: "001", tags: ["level_1"]}
    ];*/
    
    var nodes_<?php echo $this->dataViewId; ?> = [
        <?php
        $id = $this->row['dataViewLayoutTypes']['explorer']['fields']['id'];
        $parent = $this->row['dataViewLayoutTypes']['explorer']['fields']['parent'];
        $dependencydepartmentid = 'dependencydepartmentid';

        $name1 = $this->row['dataViewLayoutTypes']['explorer']['fields']['name1'];
        $name2 = $this->row['dataViewLayoutTypes']['explorer']['fields']['name2'];
        
        $colorValue = $photoValue = 'return "";';
        $isColorField = $isNameRunProcess = $isNameRunDataview = $isNameRunPackage = $isNameRunWorkspace = false;
        
        if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['color']) 
            && $this->row['dataViewLayoutTypes']['explorer']['fields']['color'] != '') {
            
            $colorField = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['color']);
            
            if (array_key_exists($colorField, $this->recordList[0])) {    
                $colorValue = 'return $row[$colorField];';
                $isColorField = true;
            }
        }
        
        if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['photo']) 
            && $this->row['dataViewLayoutTypes']['explorer']['fields']['photo'] != '') {
            
            $photoField = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['photo']);
            
            if (array_key_exists($photoField, $this->recordList[0])) {
                $photoValue = 'return $row[$photoField];';
            }
        }
        
        if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['process']) 
            && $this->row['dataViewLayoutTypes']['explorer']['fields']['process'] != '') {
            
            $processId = $this->row['dataViewLayoutTypes']['explorer']['fields']['process'];
            $isNameRunProcess = true;
        }
        
        if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['dataview']) 
            && $this->row['dataViewLayoutTypes']['explorer']['fields']['dataview'] != '') {
            
            $processId = $this->row['dataViewLayoutTypes']['explorer']['fields']['dataview'];
            $isNameRunDataview = true;
        }
        
        if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['package']) 
            && $this->row['dataViewLayoutTypes']['explorer']['fields']['package'] != '') {
            
            $processId = $this->row['dataViewLayoutTypes']['explorer']['fields']['package'];
            $isNameRunPackage = true;
        }
        
        if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['workspace']) 
            && $this->row['dataViewLayoutTypes']['explorer']['fields']['workspace'] != '') {
            
            $processId = $this->row['dataViewLayoutTypes']['explorer']['fields']['workspace'];
            $isNameRunWorkspace = true;
        }

        foreach ($this->recordList as $row) {
            $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
            echo '{id: '.$row[$id].', pid: \''.$row[$parent].'\', name: \''.str_replace("\\", '', $row[$name2]).'\', code: \''.$row[$name1].'\', color: \''.eval($colorValue).'\', image: \''.eval($photoValue).'\'},';
        }
        ?>
    ]; 
    
    orgChartResizer_<?php echo $this->dataViewId; ?>();
    
    OrgChart.templates.erpOrgChartTmplt = Object.assign({}, OrgChart.templates.ana);
    OrgChart.templates.erpOrgChartTmplt.field_0 = '<foreignObject class="node_name" x="0" y="40" width="250" height="52"><div>{val}</div></foreignObject>';
    OrgChart.templates.erpOrgChartTmplt.field_1 = '<text style="font-size: 14px;" data-width="250" data-text-overflow="multiline" fill="#ffffff" x="11" y="20" text-anchor="start">{val}</text>';
    
    var chart_<?php echo $this->dataViewId; ?> = new OrgChart(document.getElementById('orgchart-<?php echo $this->dataViewId; ?>'), {
        template: 'erpOrgChartTmplt',
        layout: OrgChart.treeLeftOffset,
        collapse: {
            level: <?php echo isset($this->row['dataViewLayoutTypes']['explorer']['fields']['expandtolevel']) ? $this->row['dataViewLayoutTypes']['explorer']['fields']['expandtolevel'] : '1'; ?>
        },
        toolbar: {
            layout: false,
            zoom: true,
            fit: true,
            expandAll: true
        },
        nodeBinding: {
            field_0: 'name', 
            field_1: 'code'
        }, 
        nodeMouseClick: OrgChart.action.none,
        tags: {
            "level_0": {
                subLevels: 0
            },
            "level_1": {
                subLevels: 1
            },
            "level_2": {
                subLevels: 2
            },
            "level_3": {
                subLevels: 3
            }, 
            "level_4": {
                subLevels: 4
            }, 
            "level_5": {
                subLevels: 5
            }, 
            "level_6": {
                subLevels: 6
            }
        }
    });

    chart_<?php echo $this->dataViewId; ?>.on('redraw', function() { 
        var allNodes = nodes_<?php echo $this->dataViewId; ?>;
        
        for (var i in allNodes) {
            if (allNodes[i].color && allNodes[i].color != '') {
                var node = document.querySelector('[data-n-id="' + (allNodes[i].id) + '"] rect');
                if (node) {
                    node.style.fill = allNodes[i].color;
                }
            }
        }
    });
    
    chart_<?php echo $this->dataViewId; ?>.on('field', function(sender, args){
        if (args.name == 'name' && 'data' in args && args.data) {
            var name = args.data['name'];
            <?php
            if ($isNameRunProcess) {
            ?>
            args.value = '<a href="javascript:;" onclick="orgChartRunProcess(\'<?php echo $processId; ?>\', \''+args.data['id']+'\')">' + name + '</a>';
            <?php
            } elseif ($isNameRunDataview) {
            ?>
            args.value = '<a href="javascript:;" onclick="orgChartRunDataview(\'<?php echo $processId; ?>\', \''+args.data['id']+'\')">' + name + '</a>';
            <?php
            } elseif ($isNameRunPackage) {
            ?>
            args.value = '<a href="javascript:;" onclick="orgChartRunPackage(\'<?php echo $processId; ?>\', \''+args.data['id']+'\')">' + name + '</a>';       
            <?php
            } elseif ($isNameRunWorkspace) {
            ?>
            args.value = '<a href="javascript:;" onclick="orgChartRunWorkspace(\'<?php echo $processId; ?>\', \''+args.data['id']+'\')">' + name + '</a>';       
            <?php
            }
            ?>
        }
    });
    
    /*chart_<?php echo $this->dataViewId; ?>.on('click', function() {
        return false;
    });*/

    chart_<?php echo $this->dataViewId; ?>.load(nodes_<?php echo $this->dataViewId; ?>);
});

function orgChartResizer_<?php echo $this->dataViewId; ?>() {
    var $orgChartElement = $('#orgchart-<?php echo $this->dataViewId; ?>');
    var getHeight = $(window).height() - $orgChartElement.offset().top - 50;
    $orgChartElement.height(getHeight);
}
function orgChartRunProcess(processId, recordId) {
    _processRecordId = recordId;
    callWebServiceByMeta(processId, true, '', false, {callerType: '<?php echo $this->row['META_DATA_CODE']; ?>', isMenu: false})
}
function orgChartRunDataview(dvid, recordId) {
    var defaultCriteriaParams = {};
    defaultCriteriaParams.filterDepartmentId = recordId;
    var $dialogName = 'dialog-dataview-orgchar-extract';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdobject/dataview/'+dvid+'/0/json',
        data: {
            uriParams: JSON.stringify(defaultCriteriaParams)
        },
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                boxed: true, 
                message: 'Loading...'
            });
        },
        success: function (data) {

            $dialog.empty().append(data.Html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 1000,
                height: $(window).height() - 90,
                modal: true,
                position: {my:'top', at:'top+50'},
                closeOnEscape: isCloseOnEscape, 
                close: function () {
                    $dialog.empty().dialog('close');
                },
                buttons: [
                    {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function () {
            alert('Error');
        }
    }).done(function () {
        Core.initDVAjax($dialog);
    });        
}
function orgChartRunPackage(dvid, recordId) {
    var defaultCriteriaParams = {};
    defaultCriteriaParams.filterDepartmentId = recordId;
    var $dialogName = 'dialog-package-orgchar-extract';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'mdobject/package/' + dvid + '/json',
        data: {metaDataId: dvid},
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (data) {
            $dialog.empty().append(data.Html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 1000,
                height: $(window).height() - 90,
                modal: true,
                closeOnEscape: isCloseOnEscape, 
                close: function () {
                    $dialog.empty().dialog('close');
                },
                buttons: [
                    {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            }).dialogExtend({
                'closable': true,
                'maximizable': true,
                'minimizable': true,
                'collapsable': true,
                'dblclick': 'maximize',
                'minimizeLocation': 'left',
                'icons': {
                    'close': 'ui-icon-circle-close',
                    'maximize': 'ui-icon-extlink',
                    'minimize': 'ui-icon-minus',
                    'collapse': 'ui-icon-triangle-1-s',
                    'restore': 'ui-icon-newwin'
                }
            });
            $dialog.dialog('open');
            $dialog.dialogExtend("maximize");
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    }).done(function () {
        Core.initAjax($dialog);
    });
}
function orgChartRunWorkspace(dvid, recordId) {
    var defaultCriteriaParams = {};
    defaultCriteriaParams.filterDepartmentId = recordId;
    var $dialogName = 'dialog-workspace-orgchar-extract';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'mdworkspace/renderWorkSpace',
        data: { metaDataId: dvid, dmMetaDataId: '<?php echo $this->dataViewId; ?>', selectedRow: {departmentid: recordId, id: recordId} },
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function (data) {
            $("link[href='middleware/assets/theme/" + data.theme + "/css/main.css']").remove();
            $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/theme/' + data.theme + '/css/main.css"/>');

            if (data.theme == 'theme10') {
                $.getScript("assets/custom/addon/plugins/jquery-easypiechart/jquery.easypiechart.min.js");
                $.getScript("assets/custom/addon/plugins/jquery.sparkline.min.js");
            }

            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 1000,
                height: $(window).height() - 90,
                modal: true,
                closeOnEscape: isCloseOnEscape, 
                close: function () {
                    $dialog.empty().dialog('close');
                },
                buttons: [
                    {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            }).dialogExtend({
                'closable': true,
                'maximizable': true,
                'minimizable': true,
                'collapsable': true,
                'dblclick': 'maximize',
                'minimizeLocation': 'left',
                'icons': {
                    'close': 'ui-icon-circle-close',
                    'maximize': 'ui-icon-extlink',
                    'minimize': 'ui-icon-minus',
                    'collapse': 'ui-icon-triangle-1-s',
                    'restore': 'ui-icon-newwin'
                }
            });
            $dialog.dialog('open');
            $dialog.dialogExtend("maximize");
            $dialog.css('overflow-x', 'hidden');
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    }).done(function () {
        Core.initAjax($dialog);
    });
}
</script>
