<div>
    <button type="button" class="btn btn-sm btn-light position-absolute" onclick="_jm.view.zoomIn()" style="z-index: 9;top: 5px;left: 15px;" title="Zoom in"><i class="icon-zoomin3"></i></button>
    <button type="button" class="btn btn-sm btn-light position-absolute" onclick="_jm.view.zoomOut()" style="left: 59px; z-index: 9; top: 5px;" title="Zoom out"><i class="icon-zoomout3"></i></button>
    <div id="mindchart-<?php echo $this->dataViewId; ?>"></div>
</div>
    
<style type="text/css">
    #mindchart-<?php echo $this->dataViewId; ?> {
        float:left;
        width:100%;
        background:transparent;
    }
</style>

<script type="text/javascript">
$(function(){
    
    var dynamicHeight = $(window).height() - $('#mindchart-<?php echo $this->dataViewId; ?>').offset().top - 40;
    $('#mindchart-<?php echo $this->dataViewId; ?>').css('height', dynamicHeight);

    if (typeof _jm === 'undefined') {
        $.when(
            $.getStylesheet('assets/custom/addon/plugins/jsmind/style/jsmind.css'),
            $.getScript('assets/custom/addon/plugins/jsmind/js/jsmind.js')
        ).then(function () {
            mindChart_<?php echo $this->dataViewId; ?>();
        }, function () {
            console.log('an error occurred somewhere');
        });
    } else {
        mindChart_<?php echo $this->dataViewId; ?>();
    }

    $(window).bind('resize', function() {
        var dynamicHeight = $(window).height() - objectdatagrid_<?php echo $this->dataViewId; ?>.offset().top - 20;
        $("#mindchart-<?php echo $this->dataViewId; ?>").css('height', dynamicHeight);        
        _jm.resize();
    });    

    objectdatagrid_<?php echo $this->dataViewId; ?>.on('click', 'jmnode', function(){
        var selected_node = _jm.get_selected_node();
        if (selected_node) {
            if (objectdatagrid_<?php echo $this->dataViewId; ?>.find('div.selected-row').length > 0) {
                objectdatagrid_<?php echo $this->dataViewId; ?>.find('div.selected-row').remove();
            }
            objectdatagrid_<?php echo $this->dataViewId; ?>.append('<div class="selected-row" data-row-data="'+selected_node.data.rowdata+'"></div>');
        }
    });
     
});   

function mindChart_<?php echo $this->dataViewId; ?>() {

    <?php if (!isset($this->dataSource)) { ?>
        return false;
    <?php } ?>
    var options = {
            container: 'mindchart-<?php echo $this->dataViewId; ?>',
            theme: 'greensea',
            editable: false
        }
    _jm = jsMind.show(options);    

    var dataSource = [<?php echo isset($this->dataSource) ? $this->dataSource : '{};'; ?>];

    var dataSrcLength = dataSource[0]['children'].length;
    dataSrcLength = parseInt(dataSrcLength / 2, 10);
    var i = 0;

    for (i; i < dataSrcLength; i++) {
        dataSource[0]['children'][i]['direction'] = 'left';    
    }

    var mind = {
        "meta":{
            "name":"MindMap",
            "author":"Ulaankhuu Ts",
            "version":"1.0"
        },
        "format":"node_tree",
        shortcut: {
            handles: {
                click: function(j,e) {
                    console.log(j);
                }
            }
        },        
        "data":dataSource[0]
    };

    _jm.show(mind);    
    
    <?php
    if ($expandToLevel = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['expandtolevel'])) {
        echo '_jm.expand_to_depth('.$expandToLevel.');';
    }
    ?>
}
</script>