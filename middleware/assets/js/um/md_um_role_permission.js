/* global Core, PNotify, msg_saving_block */
var MdUmRolePermission=function(){
    //<editor-fold defaultstate="collapsed" desc="Variables">
    var metaDataId,
            roleId,
            menuListTree=$('#menu-list'),
            tmpNode,
            savePermissionToRoleBn=$("#savePermissionToRole"),
            deletePermissionFromRoleBn=$("#deletePermissionFromRole"),
            checkedDataList=[],
            unCheckedDataList=[],
            savedPermissionList=$('#saved-permission-list'),
            selectedPermissionList=$('#selected-permission-list'),
            unSelectedPermissionList=$('#unselected-permission-list'),
            checkedIdList=[],
            unCheckedIdList=[],
            tmpData;
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Events">
    var openNode=function(mList, list, id){
        showNode(list, id);
        list.jstree(true).open_node(id);
    };
    var closeNode=function(mList, list, id){
        list.jstree(true).close_node(id);
    };
    var showParentNodes=function(firstList, list, id){
        if(list.jstree(true).get_node(id)){
            $.each(list.jstree(true).get_node(id).parents, function(key, value){
                if(value !== "#"){
                    showNode(list, value);
                }
            });
        } else {
            openParentNodes(firstList, list, id);
        }
    };
    var openParentNodes=function(firstList, list, id){
        $.each(firstList.jstree(true).get_node(id).parents, function(key, value){
            if(value !== "#" && list.jstree(true).get_node(value)){
                openNode(firstList, list, value);
            }
        });
    };
    var selectNode=function(list, id){
        list.jstree(true).select_node(id);
    };
    var showNode=function(list, id){
        list.find('li#' + id).show();
    };
    var deSelectNode=function(list, id){
        list.jstree(true).deselect_node(id);
    };
    var hideNode=function(list, id){
        list.find('li#' + id).hide();
    };
    var initEvent=function(){
        savePermissionToRoleBn.click(function(){
            checkedDataList=[];
            unCheckedDataList=[];
            checkedIdList=[];
            unCheckedIdList=[];
            menuListTree.find('li').each(function(){
                var thisId=$(this).attr("id");
                tmpNode=menuListTree.jstree(true).get_node(thisId);
                if(tmpNode.state.selected){
                    checkedDataList.push(
                            {
                                META_DATA_ID: thisId,
                                META_TYPE_ID: tmpNode.state.META_TYPE_ID,
                                PERMISSION_ID: tmpNode.state.PERMISSION_ID
                            }
                    );
                    checkedIdList.push(thisId);
                    checkedParentNodeSave(tmpNode);
                } else {
                    var hasChildChecked=false;
                    $.each(tmpNode.children, function(key, val){
                        if(menuListTree.jstree(true).get_node(val).state.selected){
                            hasChildChecked=true;
                            return false;
                        }
                    });
                    if(hasChildChecked){
                        checkedDataList.push(
                                {
                                    META_DATA_ID: thisId,
                                    META_TYPE_ID: tmpNode.state.META_TYPE_ID,
                                    PERMISSION_ID: tmpNode.state.PERMISSION_ID
                                }
                        );
                        checkedIdList.push(thisId);
                    }
                }
            });

            if(checkedDataList.length === 0){
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: "Тухайн дүрд эрх оноох гэж байгаа бол олгоогдоогүй эрх хэсгээс олгохыг хүссэн эрхээ сонгож эрхээ онооно уу.",
                    type: 'success',
                    sticker: false
                });
                return;
            }
            Core.blockUI({
                message: plang.get('msg_saving_block'),
                boxed: true
            });
            $.ajax({
                url: 'mdum/saveRolePermission',
                data: {
                    roleId: roleId,
                    checkedDataList: checkedDataList,
                    checkedIdList: checkedIdList,
                    unCheckedDataList: unCheckedDataList,
                    unCheckedIdList: unCheckedIdList,
                    isDenied: 0,
                    roleOrUser: $("#roleOrUser").val()
                },
                dataType: "json",
                type: "POST",
                success: function(data){
                    PNotify.removeAll();
                    if(data.status === 'success'){
                        new PNotify({
                            title: 'Success',
                            text: data.message,
                            type: 'success',
                            sticker: false
                        });
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: data.message,
                            type: 'error',
                            sticker: false
                        });
                    }
                    refreshTree();
                },
                error: function(jqXHR, exception){
                    $.unblockUI();
                }
            }).complete(function(){
                $.unblockUI();
            });
        });

        deletePermissionFromRoleBn.click(function(){
            checkedDataList=[];
            unCheckedDataList=[];
            checkedIdList=[];
            unCheckedIdList=[];
            savedPermissionList.find('li').each(function(){
                var thisId=$(this).attr("id");
                tmpNode=savedPermissionList.jstree(true).get_node(thisId);
                if(tmpNode.state.selected){
                    checkedDataList.push(
                            {
                                metaDataId: thisId,
                                metaTypeId: tmpNode.state.META_TYPE_ID,
                                permissionId: tmpNode.state.PERMISSION_ID
                            }
                    );
                    checkedIdList.push(thisId);
                } else {
                    var hasChildChecked=false;
                    $.each(tmpNode.children, function(key, val){
                        if(savedPermissionList.jstree(true).get_node(val).state.selected){
                            hasChildChecked=true;
                            return false;
                        }
                    });
                    if(!hasChildChecked){
                        unCheckedDataList.push(
                                {
                                    metaDataId: thisId,
                                    metaTypeId: tmpNode.state.META_TYPE_ID,
                                    permissionId: tmpNode.state.PERMISSION_ID
                                }
                        );
                        unCheckedIdList.push(thisId);
                    }
                }
            });

            if(checkedDataList.length === 0){
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: "Тухайн дүрээс эрх хасах гэж байгаа бол олгогдсон эрх хэсгээс хасахыг хүссэн эрхээ сонгож эрхээ хасна уу.",
                    type: 'success',
                    sticker: false
                });
                return;
            }
            Core.blockUI({
                message: plang.get('msg_saving_block'),
                boxed: true
            });
            $.ajax({
                url: 'mdum/deleteRolePermission',
                data: {
                    roleId: roleId,
                    checkedDataList: checkedDataList,
                    checkedIdList: checkedIdList,
                    unCheckedDataList: unCheckedDataList,
                    unCheckedIdList: unCheckedIdList,
                    isDenied: 0,
                    roleOrUser: $("#roleOrUser").val()
                },
                dataType: "json",
                type: "POST",
                success: function(data){
                    PNotify.removeAll();
                    if(data.status === 'success'){
                        new PNotify({
                            title: 'Success',
                            text: data.message,
                            type: 'success',
                            sticker: false
                        });
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: data.message,
                            type: 'error',
                            sticker: false
                        });
                    }
                    refreshTree();
                },
                error: function(jqXHR, exception){
                    $.unblockUI();
                }
            }).complete(function(){
                $.unblockUI();
            });
        });
    };
    var refreshTree=function(){
        menuListTree.remove();
        $('<div id="menu-list"></div>').insertBefore($("#save_role_permission_append_div"));
        menuListTree=$('#menu-list');

        selectedPermissionList.remove();
        $('<div id="selected-permission-list"></div>').insertBefore($("#selected-permission-list-adjacent"));
        selectedPermissionList=$('#selected-permission-list');

        savedPermissionList.remove();
        $('<div id="saved-permission-list"></div>').insertBefore($("#saved-permission-list-adjacent"));
        savedPermissionList=$('#saved-permission-list');

        unSelectedPermissionList.remove();
        $('<div id="unselected-permission-list"></div>').insertBefore($("#unselected-permission-list-adjacent"));
        unSelectedPermissionList=$('#unselected-permission-list');

        initJsTree();
    };
    var initJsTree=function(){
        //<editor-fold defaultstate="collapsed" desc="Олгох эрх">
        var menuListTreeParams=function(node){
            if(node.id === "#" || typeof node.id === "undefined"){
                return {
                    haveCriteria: 0,
                    roleId: roleId,
                    metaDataId: metaDataId,
                    parent: 'ok',
                    roleOrUser: $("#roleOrUser").val(),
                    parentNode: '',
                    isDisabled: 0,
                    isDenied: 0,
                    isSelected: 0
                };
            } else {
                return {
                    haveCriteria: 0,
                    roleId: roleId,
                    metaDataId: node.id,
                    roleOrUser: $("#roleOrUser").val(),
                    parentNode: (node.parent === "#" ? '' : node.parent),
                    isDisabled: 0,
                    isDenied: 0,
                    isSelected: 0
                };
            }
        };
        var menuListTreeParamsGet="";
        $.each(menuListTreeParams({}), function(key, val){
            menuListTreeParamsGet+=key + '=' + val + '&';
        });
        menuListTree.on("loaded.jstree", function(e, data){
            var menuListTreeData=menuListTree.jstree(true).get_json('#', {flat: true});
            tmpData=menuListTreeData;
            initSelectedPermission();
        }).on("changed.jstree", function(e, data){
            if(data.action === "select_node"){
                selectNode(selectedPermissionList, data.node.id);
                showNode(selectedPermissionList, data.node.id);
                showParentNodes(menuListTree, selectedPermissionList, data.node.id);

                if(typeof $("#isCheckChild").attr('checked') !== "undefined"){
                    $.each(menuListTree.jstree(true).get_node(data.node.id).children, function(key, val){
                        selectNode(menuListTree, val);
                    });
                }
            } else if(data.action === "deselect_node"){
                hideNode(selectedPermissionList, data.node.id);
                deSelectNode(selectedPermissionList, data.node.id);

                if(typeof $("#isCheckChild").attr('checked') !== "undefined"){
                    $.each(menuListTree.jstree(true).get_node(data.node.id).children, function(key, val){
                        deSelectNode(menuListTree, val);
                    });
                }
            }
        }).on("open_node.jstree", function(e, data){
            selectedPermissionList.find('li:visible').each(function(){
//                selectNode(menuListTree, $(this).attr("id"));
            });
            if(menuListTree.jstree(true).get_node(data.node.id).state.selected){
                $.each(menuListTree.jstree(true).get_node(data.node.id).children, function(key, val){
                    selectNode(menuListTree, val);
                });
            } else {
                $.each(menuListTree.jstree(true).get_node(data.node.id).children, function(key, val){
                    deSelectNode(menuListTree, val);
                });
            }
        }).jstree({
            'core': {
                "check_callback": true,
                "expand_selected_onload": false,
                "multiple": true,
                "open_parents": false,
                "load_open": false,
                "data": {
                    url: URL_APP + 'mdum/getData',
                    dataType: "json",
                    type: 'POST',
                    data: function(node){
                        return menuListTreeParams(node);
                    }
                },
                "themes": {
                    'responsive': false,
                    'stripes': true
                }
            },
            "checkbox": {
                keep_selected_style: false,
                real_checkboxes: true,
                real_checkboxes_names: function(n){
                    var nid=0;
                    $(n).each(function(data){
                        nid=$(this).attr("nodeid");
                    });
                    return (["check_" + nid, nid]);
                },
                three_state: false,
                two_state: true,
                whole_node: true
            },
            'types': {
                "default": {
                    "icon": "fa fa-play-circle text-orange-400"
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
                'changed', 'types', 'unique', 'wholerow', 'checkbox', 'search'
            ],
            "search": {
                'show_only_matches': true,
                'ajax': {
                    url: URL_APP + 'mdum/getData/?' + menuListTreeParamsGet,
                    type: 'POST',
                    "data": function(str){
                        return {
                            "operation": "search",
                            "q": str
                        };
                    }
                }
            }
        });
        $('#searchPermissionInput').keydown(function(e){
            var code=e.keyCode || e.which;
            if(code === 13){
                Core.blockUI({
                    message: plang.get('msg_saving_block'),
                    boxed: true
                });
                $.ajax({
                    url: 'mdum/getData',
                    data: $.extend(menuListTreeParams({}), {searchText: $(this).val()}),
                    dataType: "json",
                    type: 'POST',
                    success: function(data){
                        menuListTree.jstree(true).settings.core.data=data;
                        menuListTree.jstree(true).refresh();
                    },
                    error: function(jqXHR, exception){
                        $.unblockUI();
                    }
                }).complete(function(){
                    $.unblockUI();
                });
            }
        });
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Нэмэхээр сонгогдсон эрх">
        var initSelectedPermission=function(){
            selectedPermissionList.on("loaded.jstree", function(e, data){
                $(this).find('li').each(function(){
                    $(this).hide();
                });
            }).on("open_node.jstree", function(e, data){
                var haveCheckedNode=false;
                selectedPermissionList.find('li:visible').each(function(){
                    var thisId=$(this).attr("id");
                    var menuListTreeState=menuListTree.jstree(true).get_node(thisId).state;
                    if(typeof menuListTreeState !== 'undefined' && !menuListTreeState.selected){
                        haveCheckedNode=false;
                        menuListTree.find("#" + thisId).find("li").each(function(){
                            var $childLi=$(this);
                            if($childLi.attr('aria-selected')){
                                haveCheckedNode=true;
                                return false;
                            }
                        });
                        if(!haveCheckedNode){
                            hideNode(selectedPermissionList, thisId);
                        }
                    }
                });
            }).jstree({
                'core': {
                    "check_callback": true,
                    "expand_selected_onload": false,
                    "multiple": false,
                    "open_parents": false,
                    "load_open": false,
                    'data': {
                        url: URL_APP + 'mdum/getData',
                        type: 'POST',
                        dataType: "json",
                        data: function(node){
                            if(node.id === "#"){
                                return {
                                    haveCriteria: 0,
                                    roleId: roleId,
                                    metaDataId: metaDataId,
                                    parent: 'ok',
                                    roleOrUser: $("#roleOrUser").val(),
                                    parentNode: '',
                                    isDisabled: 0,
                                    isDenied: 0,
                                    isSelected: 0,
                                    tmpData: tmpData
                                };
                            } else {
                                return {
                                    haveCriteria: 0,
                                    roleId: roleId,
                                    metaDataId: node.id,
                                    roleOrUser: $("#roleOrUser").val(),
                                    parentNode: (node.parent === "#" ? '' : node.parent),
                                    isDisabled: 0,
                                    isDenied: 0,
                                    isSelected: 0
                                };
                            }
                        }
                    },
                    'themes': {
                        'responsive': false,
                        'stripes': true
                    }
                },
                'types': {
                    "default": {
                        "icon": "fa fa-play-circle text-orange-400"
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
                    'changed', 'types', 'unique', 'wholerow', 'search'
                ],
                'search': {
                    'case_insensitive': true,
                    'show_only_matches': true
                }
            });
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Олгогдсон эрх">
        var savedPermissionListParams=function(node){
            if(node.id === "#"){
                return {
                    isSaved: 1,
                    roleId: roleId,
                    metaDataId: metaDataId,
                    parent: 'ok',
                    roleOrUser: $("#roleOrUser").val(),
                    parentNode: '',
                    isDisabled: 0,
                    isDenied: 0,
                    isSelected: 0,
                    haveCriteria: 1
                };
            } else {
                return {
                    isSaved: 1,
                    roleId: roleId,
                    metaDataId: node.id,
                    roleOrUser: $("#roleOrUser").val(),
                    parentNode: (node.parent === "#" ? '' : node.parent),
                    isDisabled: 0,
                    isDenied: 0,
                    isSelected: 0,
                    haveCriteria: 1
                };
            }
        };
        savedPermissionList.on("loaded.jstree", function(e, data){
            var savedPermissionListData=savedPermissionList.jstree(true).get_json('#', {flat: true});
            tmpData=savedPermissionListData;
            initUnSelectedPermission();
        }).on("changed.jstree", function(e, data){
            if(data.action === "select_node"){
                showParentNodes(savedPermissionList, unSelectedPermissionList, data.node.id);
                showNode(unSelectedPermissionList, data.node.id);

                if(typeof $("#isCheckChildSaved").attr('checked') !== "undefined"){
                    $.each(savedPermissionList.jstree(true).get_node(data.node.id).children,
                            function(key, val){
                                deSelectNode(savedPermissionList, val);
                            });
                }
            } else if(data.action === "deselect_node"){
                hideNode(unSelectedPermissionList, data.node.id);


                if(typeof $("#isCheckChildSaved").attr('checked') !== "undefined"){
                    $.each(savedPermissionList.jstree(true).get_node(data.node.id).children,
                            function(key, val){
                                selectNode(savedPermissionList, val);
                            });
                }
            }
        }).on("open_node.jstree", function(e, data){
            unSelectedPermissionList.find('li:visible').each(function(){
//                deSelectNode(savedPermissionList, $(this).attr("id"));
            });

            if(!savedPermissionList.jstree(true).get_node(data.node.id).state.selected){
                $.each(savedPermissionList.jstree(true).get_node(data.node.id).children,
                        function(key, val){
                            deSelectNode(savedPermissionList, val);
                        });
            } else {
                $.each(savedPermissionList.jstree(true).get_node(data.node.id).children,
                        function(key, val){
                            selectNode(savedPermissionList, val);
                        });
            }
        }).jstree({
            'core': {
                "check_callback": true,
                "expand_selected_onload": false,
                "multiple": true,
                "open_parents": false,
                "load_open": false,
                'data': {
                    url: URL_APP + 'mdum/getData',
                    dataType: "json",
                    type: 'POST',
                    data: function(node){
                        return savedPermissionListParams(node);
                    }
                },
                'themes': {
                    'responsive': false,
                    'stripes': true
                }
            },
            "checkbox": {
                keep_selected_style: false,
                real_checkboxes: true,
                real_checkboxes_names: function(n){
                    var nid=0;
                    $(n).each(function(data){
                        nid=$(this).attr("nodeid");
                    });
                    return (["check_" + nid, nid]);
                },
                three_state: true,
                two_state: true,
                whole_node: true
            },
            'types': {
                "default": {
                    "icon": "fa fa-play-circle text-orange-400"
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
                'changed', 'types', 'unique', 'wholerow', 'checkbox'
            ]
        });
        $('#searchSavedPermissionInput').keydown(function(e){
            var code=e.keyCode || e.which;
            if(code === 13){
                Core.blockUI({
                    message: plang.get('msg_saving_block'),
                    boxed: true
                });
                $.ajax({
                    url: 'mdum/getData',
                    data: $.extend(savedPermissionListParams({}), {searchText: $(this).val()}),
                    dataType: "json",
                    type: 'POST',
                    success: function(data){
                        savedPermissionList.jstree(true).settings.core.data=data;
                        savedPermissionList.jstree(true).refresh();
                    },
                    error: function(jqXHR, exception){
                        $.unblockUI();
                    }
                }).complete(function(){
                    $.unblockUI();
                });
            }
        });
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Хасахаар сонгогдсон эрх">
        var initUnSelectedPermission=function(){
            unSelectedPermissionList.on("loaded.jstree", function(e, data){
                $(this).find('li').each(function(){
                    $(this).hide();
                });
            }).on("open_node.jstree", function(e, data){
                unSelectedPermissionList.find('li:visible').each(function(){
                    var thisId=$(this).attr("id");
                    var savedPermissionListState=savedPermissionList.jstree(true).get_node(thisId).state;
                    if(typeof savedPermissionListState !== 'undefined' && savedPermissionListState.selected){
                        hideNode(unSelectedPermissionList, thisId);
                    }
                });
            }).jstree({
                'core': {
                    "check_callback": true,
                    "expand_selected_onload": false,
                    "multiple": false,
                    "open_parents": false,
                    "load_open": false,
                    'data': {
                        url: URL_APP + 'mdum/getData',
                        dataType: "json",
                        type: 'POST',
                        data: function(node){
                            if(node.id === "#"){
                                return {
                                    isSaved: 1,
                                    roleId: roleId,
                                    metaDataId: metaDataId,
                                    parent: 'ok',
                                    roleOrUser: $("#roleOrUser").val(),
                                    parentNode: '',
                                    isDisabled: 0,
                                    isDenied: 0,
                                    isSelected: 0,
                                    haveCriteria: 1,
                                    tmpData: tmpData
                                };
                            } else {
                                return {
                                    isSaved: 1,
                                    roleId: roleId,
                                    metaDataId: node.id,
                                    roleOrUser: $("#roleOrUser").val(),
                                    parentNode: (node.parent === "#" ? '' : node.parent),
                                    isDisabled: 0,
                                    isDenied: 0,
                                    isSelected: 0,
                                    haveCriteria: 1
                                };
                            }
                        }
                    },
                    'themes': {
                        'responsive': false,
                        'stripes': true
                    }
                },
                'types': {
                    "default": {
                        "icon": "fa fa-play-circle text-orange-400"
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
                    'changed', 'types', 'unique', 'wholerow', 'search'
                ],
                "search": {
                    'case_insensitive': true,
                    'show_only_matches': true
                }
            });
        };
        //</editor-fold>
    };
    var checkedParentNodeSave=function(tmpNode){
        $.each(tmpNode.parents, function(key, val){
            if(val !== "#" && checkedIdList.indexOf(val) < 0){
                checkedIdList.push(val);
            }
        });
    };
    //</editor-fold>
    return {
        init: function(pMetaDataId, pRoleId, pUserId){
            metaDataId=pMetaDataId;
            roleId=pRoleId;
            initJsTree();
            initEvent();
            $('.dg-custom-tooltip').tooltip();
        }
    };
}();