/* global Core */
var lifecycle = function () {
    //<editor-fold defaultstate="collapsed" desc="variables">
    var $leftTreeList,
            $rightSideDv,
            uniqId,
            srcRecordId,
            lifecycleId,
            lifecycletaskId,
            treeDvId,
            META_GROUP_TYPE = '200101010000016',
            BUSINESS_PROCESS_TYPE = '200101010000011';
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="events">
    var initEvent = function (treeDvId) {
        $leftTreeList = $("#left-tree-list-" + uniqId),
        $rightSideDv = $("#rightSideDv_" + uniqId);

        setTimeout(function () {
            initLifeCycleListTree(treeDvId);
            lifeCycleContextMenu();
        }, 1);

        $('.lifecycle-toggler').on('click', function () {
            var $this = $(this);
            var togglerMode = $this.attr('data-toggler');
            var $togglerLeft = $('.lifecycle-toggler-left');
            var $togglerRight = $('.lifecycle-toggler-right');

            if (togglerMode === 'collapse') {
                $togglerLeft.removeClass('col-md-3').addClass('col-md-2');
                $togglerRight.removeClass('col-md-9').addClass('col-md-10');
                $this.find('i').removeClass('fa-chevron-circle-left').addClass('fa-chevron-circle-right');
                $this.attr('data-toggler', 'expand');
            } else {
                $togglerLeft.removeClass('col-md-2').addClass('col-md-3');
                $togglerRight.removeClass('col-md-10').addClass('col-md-9');
                $this.find('i').removeClass('fa-chevron-circle-right').addClass('fa-chevron-circle-left');
                $this.attr('data-toggler', 'collapse');
            }
            $(window).resize();
        });
    };

    var loadDataViewRightSide = function (dataViewIdRight, lifecycletaskid, srcRecordId, childrenString = '') {
        var uriParams = '';
        if (lifecycletaskid === '' || lifecycletaskid === null) {
            uriParams = childrenString + 'dv[recordid]=' + srcRecordId;
        } else {
            uriParams = 'dv[lifecycletaskid]=' + lifecycletaskid + '&dv[recordid]=' + srcRecordId;
        }

        $.ajax({
            type: 'post',
            url: 'mdobject/dataview/' + dataViewIdRight + '?' + uriParams,
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            dataType: 'html',
            success: function (response) {
                $rightSideDv.empty().append(response);
            }
        }).complete(function () {
            Core.unblockUI();
        });
    };

    var lifeCycleTreeParams = function (node, treeDvId) {
        return {
                lifecycleId: lifecycleId,
                lifecycletaskId: lifecycletaskId,
                recordId: srcRecordId,
                parent: typeof node.id !== 'undefined' && node.id !== '#' ? node.id : 'ok',
                parentNode: '',
                treeDvId: treeDvId
            };
        /*
        if (node.id === "#" || typeof node.id === "undefined") {
            return {
                lifecycleId: lifecycleId,
                lifecycletaskId: lifecycletaskId,
                recordId: srcRecordId,
                parent: 'ok',
                parentNode: '',
                treeDvId: treeDvId
            };
        }*/
    };

    var initLifeCycleListTree = function (treeDvId) {
        $leftTreeList.remove();
        $('<div id="left-tree-list-' + uniqId + '" class="lifecycle-common-div lifecycle-selected-t"></div>').insertBefore($("#left-tree-list-adjacent_" + uniqId));
        $leftTreeList = $('#left-tree-list-' + uniqId);
        $leftTreeList.on("changed.jstree", function (e, data) {

            if (typeof data.node !== "undefined" && typeof data.node.data.metadataid !== "undefined" && data.node.data.metadataid !== null &&
                    typeof data.node.data.metatypeid !== "undefined" && data.node.data.metatypeid !== null &&
                    typeof data.node.data.id !== "undefined" && data.node.data.id !== null) {
                var metadataid = data.node.data.metadataid;
                var metaTypeId = data.node.data.metatypeid;
                var childrenString = '';
                
                if (typeof data.node.children !== 'undefined') {
                    
                    $.each( data.node.children, function( index, value ){
                        childrenString += 'dv[lifecycletaskid][]=' + value + '&';
                    });
                }
                var lifecycletaskid = (typeof data.event.target.className !== 'undefined' && data.node.parent === '#' && data.node.children.length !== 0) ? '' : data.node.data.id;

                $leftTreeList.attr('selected-task-id', lifecycletaskid);
                $leftTreeList.attr('selected-selective-id', lifecycleId);
                $leftTreeList.attr('selected-lifecycle-id', lifecycleId);
                $leftTreeList.attr('selected-recordId-id', srcRecordId);

                if (typeof data.event !== 'undefined' && typeof data.event.target !== 'undefined' && typeof data.event.target.className !== 'undefined' && (data.event.target.className === 'fa fa-upload' || data.event.target.className === 'count-selective-task taskFileUploadBtn')) {
                    var mapId = srcRecordId;
                    var mapLifecycleId = lifecycleId;
                    getFileUploadModal(mapId, lifecycletaskid);
                } else {
                    if (metaTypeId == META_GROUP_TYPE) {
                        loadDataViewRightSide(metadataid, lifecycletaskid, srcRecordId, childrenString);
                    } else if (metaTypeId == BUSINESS_PROCESS_TYPE) {
                        callWebServiceByMeta(metadataid, true);
                    }
                }
            } else {
                $rightSideDv.html('');
            }
        }).on("open_node.jstree", function (e, data) {

        }).on('ready.jstree', function () {
            setTimeout(function () {
                $leftTreeList.find('.jstree-children .jstree-node:eq(0)').find('.jstree-anchor').trigger('click');
            }, 100);
        }).jstree({
            'core': {
                "check_callback": true,
                "expand_selected_onload": false,
                "open_parents": true,
                "load_open": true,
                "data": {
                    url: 'mdlifecycle/getlifeCycleTreeList_v1',
                    type: 'post',
                    dataType: "json",
                    data: function (node) {
                        return lifeCycleTreeParams(node, treeDvId);
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
                'duplicate': function (name, counter) {
                    return name + ' ' + counter;
                }
            },
            'plugins': [
                'changed', 'types', 'unique', 'wholerow'
            ]
        });
    };

    //<editor-fold defaultstate="collapsed" desc="File upload">
    var getFileUploadModal = function (mapId, taskId) {
        var data = {id: mapId, taskId: taskId};
        Core.blockUI({
            animate: true
        });
        $.ajax({
            url: "mdlifecycle/getFileUploadModal",
            type: "POST",
            data: data,
            dataType: "JSON",
            success: function (data) {
                var config = {
                    title: data.Title,
                    width: data.width,
                    height: data.height,
                    buttons: [
                        {text: data.close_btn, class: 'btn btn-sm blue-madison', click: function () {
                                $('#dialog-lc-fileupload').dialog('close');
                            }}
                    ]
                };

                Core.initDialog('dialog-lc-fileupload', data.html, config, function ($dialog) {
                    $dialog.dialogExtend("maximize");
                });
            },
            error: function (jqXHR, exception) {
                Core.unblockUI();
            }
        }).complete(function () {
            Core.unblockUI();
        });
    };

    var lifeCycleContextMenu = function () {
        $.contextMenu({
            selector: '#left-tree-list-' + uniqId + ' ul.jstree-container-ul li.jstree-node',
            callback: function (key, opt) {
                if (key === 'bp') {
                    _processPostParam = 'taskId=' + opt.$trigger.attr('data-tid');
                    callWebServiceByMeta('1501571866720', true);
                }
            },
            items: {
                "bp": {name: "Төлөвлөгөө", icon: "clipboard"}
            }
        });
    };
    //</editor-fold>
    //</editor-fold>
    return {
        init: function (uId, lcId, recordId, lphaseId, treeDvId, parentId) {
            uniqId = uId;
            lifecycleId = lcId;
            srcRecordId = recordId;
            lifecycletaskId = lphaseId;
            treeDvId = treeDvId;
            initEvent(treeDvId);
        },
        initLifeCycleListTree: function () {
            initLifeCycleListTree();
        }
    };
}();