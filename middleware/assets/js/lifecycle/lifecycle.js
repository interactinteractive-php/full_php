/* global Core */
var lifecycle=function(){
    //<editor-fold defaultstate="collapsed" desc="variables">
    var $leftTreeList,
            $rightSideDv,
            uniqId,
            srcRecordId,
            META_GROUP_TYPE='200101010000016',
            BUSINESS_PROCESS_TYPE='200101010000011';
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="events">
    var initEvent=function(){
        $leftTreeList=$("#left-tree-list_" + uniqId),
                $rightSideDv=$("#rightSideDv_" + uniqId);

        setTimeout(function(){
            initLifeCycleListTree();
            lifeCycleContextMenu();
        }, 1);

        $('.lifecycle-toggler').on('click', function(){
            var $this=$(this);
            var togglerMode=$this.attr('data-toggler');
            var $togglerLeft=$('.lifecycle-toggler-left');
            var $togglerRight=$('.lifecycle-toggler-right');

            if(togglerMode === 'collapse'){
                $togglerLeft.removeClass('col-md-3').addClass('col-md-1 lifecycle-phase-btn-hide');
                $togglerRight.removeClass('col-md-9').addClass('col-md-11');
                $this.find('i').removeClass('fa-chevron-circle-left').addClass('fa-chevron-circle-right');
                $this.attr('data-toggler', 'expand');
            } else {
                $togglerLeft.removeClass('col-md-1 lifecycle-phase-btn-hide').addClass('col-md-3');
                $togglerRight.removeClass('col-md-11').addClass('col-md-9');
                $this.find('i').removeClass('fa-chevron-circle-right').addClass('fa-chevron-circle-left');
                $this.attr('data-toggler', 'collapse');
            }
            $(window).resize();
        });
    };
    var loadDataViewRightSide=function(dataViewIdRight, taskId){
        var uriParams='dv[taskid]=' + taskId;
        $.ajax({
            type: 'post',
            url: 'mdobject/dataview/' + dataViewIdRight + '?' + uriParams,
            beforeSend: function(){
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            dataType: 'html',
            success: function(response){
                $rightSideDv.empty().append(response);
            }
        }).complete(function(){
            Core.unblockUI();
        });
    };
    var lifeCycleTreeParams=function(node){
        if(node.id === "#" || typeof node.id === "undefined"){
            return {
                selectiveId: srcRecordId,
                parent: 'ok',
                parentNode: ''
            };
        } else {

        }
    };
    var initLifeCycleListTree=function(){
        $leftTreeList.remove();
        $('<div id="left-tree-list_' + uniqId + '" class="lifecycle-common-div lifecycle-selected-t"></div>').insertBefore($(
                "#left-tree-list-adjacent_" + uniqId));
        $leftTreeList=$('#left-tree-list_' + uniqId);

        $leftTreeList.on("changed.jstree", function(e, data){
            if(typeof data.node !== "undefined" && typeof data.node.data.metadataid !== "undefined" && data.node.data.metadataid !== null &&
                    typeof data.node.data.metatypeid !== "undefined" && data.node.data.metatypeid !== null &&
                    typeof data.node.data.trgrecordid !== "undefined" && data.node.data.trgrecordid !== null){
                var metadataid=data.node.data.metadataid;
                var metaTypeId=data.node.data.metatypeid;
                var taskId=data.node.data.trgrecordid;
                $leftTreeList.attr('selected-task-id', taskId);
                $leftTreeList.attr('selected-selective-id', srcRecordId);

                if(typeof data.event !== 'undefined' && typeof data.event.target !== 'undefined' && typeof data.event.target.className !==
                        'undefined' && (data.event.target.className === 'fa fa-upload' || data.event.target.className === 'count-selective-task taskFileUploadBtn')){
                    var mapId=srcRecordId;
                    getFileUploadModal(mapId, taskId);
                } else {
                    if(metaTypeId === META_GROUP_TYPE){
                        loadDataViewRightSide(metadataid, taskId);
                    } else if(metaTypeId === BUSINESS_PROCESS_TYPE){
                        callWebServiceByMeta(metadataid, true);
                    }
                }
            } else {
                $rightSideDv.html('');
            }
        }).on("open_node.jstree", function(e, data){

        }).on('ready.jstree', function(){
            if ($leftTreeList.find('span[data-selectedlifecyclewfm="1"]')) {
                $leftTreeList.find('span[data-selectedlifecyclewfm="1"]').closest('.jstree-node').find('.jstree-anchor').trigger('click');
            } else {
                setTimeout(function(){
                    $leftTreeList.find('.jstree-children .jstree-node:eq(0)').find('.jstree-anchor').trigger('click');
                }, 100);
            }
        }).jstree({
            'core': {
                "check_callback": true,
                "expand_selected_onload": false,
                "open_parents": false,
                "load_open": false,
                "data": {
                    url: 'mdlifecycle/getlifeCycleTreeList',
                    dataType: "json",
                    data: function(node){
                        return lifeCycleTreeParams(node);
                    }
                },
                "themes": {
                    'responsive': true
                }
            },
            'types': {
                "default": {
                    "icon": "icon-folder2 text-orange-300"
                },
                "file": {
                    "icon": "fa fa-play-circle text-orange-400"
                }
            },
            'unique': {
                'duplicate': function(name, counter){
                    return name + ' ' + counter;
                }
            },
            'plugins': [
                'changed', 'types', 'unique', 'wholerow'
            ]
        });
    };
    //<editor-fold defaultstate="collapsed" desc="File upload">
    var getFileUploadModal=function(mapId, taskId){
        var data={id: mapId, taskId: taskId};
        Core.blockUI({
            animate: true
        });
        $.ajax({
            url: "mdlifecycle/getFileUploadModal",
            type: "POST",
            data: data,
            dataType: "JSON",
            success: function(data){
                var config={
                    title: data.Title,
                    width: data.width,
                    height: data.height,
                    buttons: [
                        {text: data.close_btn, class: 'btn btn-sm blue-madison', click: function(){
                                $('#dialog-lc-fileupload').dialog('close');
                            }}
                    ]
                };

                Core.initDialog('dialog-lc-fileupload', data.html, config, function($dialog){
                    $dialog.dialogExtend("maximize");
                });
            },
            error: function(jqXHR, exception){
                Core.unblockUI();
            }
        }).complete(function(){
            Core.unblockUI();
        });
    };

    var lifeCycleContextMenu=function(){
        $.contextMenu({
            selector: '#left-tree-list_' + uniqId + ' ul.jstree-container-ul li.jstree-node',
            build: function($triggerElement, e){
                $leftTreeList = $('#left-tree-list_' + uniqId);
                
                var menuName = 'Төлөвлөгөө';
                var processId = '1501571866720';
                var nodeId = $triggerElement.attr('data-tid');
                var nodeData = $leftTreeList.jstree(true).get_node(nodeId);

                if (nodeData.hasOwnProperty('data')) {
                    if (nodeData.data.hasOwnProperty('processid')) {
                        processId = nodeData.data.processid;
                    }
                    if (nodeData.data.hasOwnProperty('processname')) {
                        menuName = nodeData.data.processname;
                    }
                }
                        
                return {
                    callback: function(key, opt){
                        _processPostParam = 'taskId=' + nodeId;
                        callWebServiceByMeta(processId, true);
                    },
                    items: {
                        'bp': {name: menuName, icon: 'clipboard'}
                    }
                };
            }
        });
    };
    //</editor-fold>
    //</editor-fold>
    return {
        init: function(uId, recordId){
            uniqId=uId;
            srcRecordId=recordId;
            initEvent();
        },
        initLifeCycleListTree: function(){
            initLifeCycleListTree();
        }
    };
}();