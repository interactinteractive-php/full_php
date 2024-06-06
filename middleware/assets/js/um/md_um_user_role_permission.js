/* global go, Core, msg_saving_block, PNotify */
var MdUmUserRolePermission=function(){

    //<editor-fold defaultstate="collapsed" desc="Variables">
    var metaDataId,
            roleId,
            userId,
            uuId,
            tmpNode,
            $permissionWindow,
            notassignedRoleListTree,
            notassignedPermissionListTree,
            assignedRoleListTree,
            assignedPermissionListTree,
            deniedPermissionListTree,
            saveRoleToUserBn,
            unsetRoleToUserBn,
            savePermissionToUserBn,
            unsetPermissionToUserBn,
            saveDeniedToUserBn,
            deleteDeniedToUserBn,
            checkedDataList=[],
            unCheckedDataList=[],
            checkedIdList=[],
            unCheckedIdList=[];
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Events">
    var initEvent=function(){
        saveRoleToUserBn.click(function(){
            checkedDataList=[];
            unCheckedDataList=[];
            checkedIdList=[];
            unCheckedIdList=[];
            notassignedRoleListTree.find('li').each(function(){
                var mId = $(this).attr("id");
                tmpNode = notassignedRoleListTree.jstree(true).get_node(mId);
                if (typeof tmpNode.state.ROLE_ID !== "undefined" && tmpNode.state.selected) {
                    checkedDataList.push({ROLE_ID: tmpNode.state.ROLE_ID});
                    /*checkedIdList.push($(this).attr("id"));*/

                    var mIdArr=mId.split('-');
                    checkedIdList.push({
                        id: mIdArr[0],
                        isOpen: notassignedRoleListTree.jstree(true).is_open(mId)
                    });

                    checkedParentNodeSave(tmpNode, notassignedRoleListTree);
                }
            });
            if (checkedDataList.length == 0) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: "Тухайн хэрэглэгчид дүр оноох гэж байгаа бол олгоогдоогүй дүр хэсгээс олгохыг хүссэн дүрээ сонгож дүрээ онооно уу.",
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
                url: 'mdum/setRoleToUser',
                data: {
                    roleId: roleId,
                    userId: userId,
                    checkedDataList: checkedDataList
                },
                dataType: "json",
                type: "POST",
                success: function(data){
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });

                    initNotassignedRoleListTree();
                    initAssignedRoleListTree();
                },
                error: function(jqXHR, exception){
                    $.unblockUI();
                }
            }).complete(function(){
                $.unblockUI();
            });
        });

        unsetRoleToUserBn.click(function(){
            checkedDataList=[];
            unCheckedDataList=[];
            checkedIdList=[];
            unCheckedIdList=[];
            assignedRoleListTree.find('li').each(function(){
                tmpNode = assignedRoleListTree.jstree(true).get_node($(this).attr("id"));
                if (typeof tmpNode.state.USER_ROLE_ID !== "undefined" && tmpNode.state.selected) {
                    checkedDataList.push({USER_ROLE_ID: tmpNode.state.USER_ROLE_ID});
                }
            });
            if (checkedDataList.length == 0) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: "Тухайн хэрэглэгчид оноогдсон дүрээс хасах гэж байгаа бол олгоогдсон дүр хэсгээс хасахыг хүссэн дүрээ сонгоно уу.",
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
                url: 'mdum/unSetRoleToUser',
                data: {
                    roleId: roleId,
                    userId: userId,
                    checkedDataList: checkedDataList
                },
                dataType: "json",
                type: "POST",
                success: function(data){
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });

                    initNotassignedRoleListTree();
                    initAssignedRoleListTree();
                },
                error: function(jqXHR, exception){
                    $.unblockUI();
                }
            }).complete(function(){
                $.unblockUI();
            });
        });

        savePermissionToUserBn.click(function(){
            checkedDataList=[];
            unCheckedDataList=[];
            checkedIdList=[];
            unCheckedIdList=[];

            notassignedPermissionListTree.find('li').each(function(){
                var mId = $(this).attr("id");
                tmpNode = notassignedPermissionListTree.jstree(true).get_node(mId);
                if (tmpNode.state.selected) {
                    checkedDataList.push({META_DATA_ID: mId,
                        META_TYPE_ID: tmpNode.state.META_TYPE_ID,
                        PERMISSION_ID: tmpNode.state.PERMISSION_ID});
                    /*checkedIdList.push($(this).attr("id"));*/
                    var mIdArr=mId.split('-');
                    checkedIdList.push({
                        id: mIdArr[0],
                        isOpen: notassignedPermissionListTree.jstree(true).is_open(mId)
                    });
                    checkedParentNodeSave(tmpNode, notassignedPermissionListTree);
                } else {
                    var hasChildChecked = false;
                    $.each(tmpNode.children, function(key, val){
                        if (notassignedPermissionListTree.jstree(true).get_node(val).state.selected) {
                            hasChildChecked=true;
                            return false;
                        }
                    });
                    if (!hasChildChecked) {
                        unCheckedDataList.push({
                            META_DATA_ID: mId,
                            META_TYPE_ID: tmpNode.state.META_TYPE_ID,
                            PERMISSION_ID: tmpNode.state.PERMISSION_ID
                        });
                        unCheckedIdList.push(mId);
                    }
                }
            });

            if (checkedDataList.length == 0) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: "Тухайн хэрэглэгчид эрх оноох гэж байгаа бол олгоогдоогүй эрх хэсгээс олгохыг хүссэн эрхээ сонгож эрхээ онооно уу.",
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
                    userId: userId,
                    checkedDataList: checkedDataList,
                    checkedIdList: checkedIdList,
                    unCheckedDataList: unCheckedDataList,
                    roleOrUser: $("#roleOrUser").val(),
                    isDenied: 0
                },
                dataType: "json",
                type: "POST",
                success: function(data){
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });

                    initNotassignedPermissionListTree();
                    initAssignedPermissionListTree();
                },
                error: function(jqXHR, exception){
                    $.unblockUI();
                }
            }).complete(function(){
                $.unblockUI();
            });
        });

        unsetPermissionToUserBn.click(function(){
            checkedDataList=[];
            unCheckedDataList=[];
            checkedIdList=[];
            unCheckedIdList=[];
            assignedPermissionListTree.find('li').each(function(){
                var mId=$(this).attr("id");
                tmpNode=assignedPermissionListTree.jstree(true).get_node(mId);
                if(tmpNode.state.selected){
                    checkedDataList.push({metaDataId: mId,
                        metaTypeId: tmpNode.state.META_TYPE_ID,
                        permissionId: tmpNode.state.PERMISSION_ID});
                    /*checkedIdList.push($(this).attr("id"));*/

                    var mIdArr=mId.split('-');
                    checkedIdList.push({
                        id: mIdArr[0],
                        isOpen: assignedPermissionListTree.jstree(true).is_open(mId)
                    });

                } else {
                    var hasChildChecked=false;
                    $.each(tmpNode.children, function(key, val){
                        if(assignedPermissionListTree.jstree(true).get_node(val).state.selected){
                            hasChildChecked=true;
                            return false;
                        }
                    });
                    if(!hasChildChecked){
                        unCheckedDataList.push({metaDataId: $(this).attr("id"),
                            metaTypeId: tmpNode.state.META_TYPE_ID,
                            permissionId: tmpNode.state.PERMISSION_ID});
                        unCheckedIdList.push($(this).attr("id"));
                    }
                }
            });

            if(checkedDataList.length === 0){
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: "Тухайн хэрэглэгчээс эрх хасах гэж байгаа бол олгоогдсон эрх хэсгээс хасахыг хүссэн эрхээ сонгож эрхээ онооно уу.",
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
                    userId: userId,
                    checkedDataList: checkedDataList,
                    checkedIdList: checkedIdList,
                    unCheckedDataList: unCheckedDataList,
                    unCheckedIdList: unCheckedIdList,
                    isDenied: 0
                },
                dataType: "json",
                type: "POST",
                success: function(data){
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    initNotassignedPermissionListTree();
                    initAssignedPermissionListTree();
                },
                error: function(jqXHR, exception){
                    $.unblockUI();
                }
            }).complete(function(){
                $.unblockUI();
            });
        });

        saveDeniedToUserBn.click(function(){
            checkedDataList=[];
            unCheckedDataList=[];
            checkedIdList=[];
            unCheckedIdList=[];
            assignedRoleListTree.find('li').each(function(){
                var mId=$(this).attr("id");
                tmpNode=assignedRoleListTree.jstree(true).get_node(mId);
                if(tmpNode.state.selected && typeof tmpNode.state.ROLE_ID === "undefined"){
                    checkedDataList.push({META_DATA_ID: mId,
                        META_TYPE_ID: tmpNode.state.META_TYPE_ID,
                        PERMISSION_ID: tmpNode.state.PERMISSION_ID});
                    /*checkedIdList.push($(this).attr("id"));*/

                    var mIdArr=mId.split('-');
                    checkedIdList.push({
                        id: mIdArr[0],
                        isOpen: assignedRoleListTree.jstree(true).is_open(mId)
                    });

                    $.each(tmpNode.parents, function(key, val){
                        if(val !== "#" && typeof assignedRoleListTree.jstree(true).get_node(val).state.ROLE_ID === "undefined" && checkedIdList.indexOf(val) < 0){
                            /*checkedIdList.push(val);*/
                            var mIdArr=val.split('-');
                            checkedIdList.push({
                                id: mIdArr[0],
                                isOpen: assignedRoleListTree.jstree(true).is_open(val)
                            });
                        }
                    });
                } else {
                    var hasChildChecked=false;
                    $.each(tmpNode.children, function(key, val){
                        if(assignedRoleListTree.jstree(true).get_node(val).state.selected){
                            hasChildChecked=true;
                            return false;
                        }
                    });
                    if(!hasChildChecked){
                        unCheckedDataList.push({META_DATA_ID: $(this).attr("id"),
                            META_TYPE_ID: tmpNode.state.META_TYPE_ID,
                            PERMISSION_ID: tmpNode.state.PERMISSION_ID});
                        unCheckedIdList.push($(this).attr("id"));
                    }
                }
            });

            if(checkedDataList.length === 0){
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: "Тухайн хэрэглэгчид хандах эрхгүй эрх тохируулах гэж байгаа бол олгодсон дүр хэсгээс эрхээ сонгоно уу.",
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
                    userId: userId,
                    checkedDataList: checkedDataList,
                    checkedIdList: checkedIdList,
                    unCheckedDataList: unCheckedDataList,
                    roleOrUser: $("#roleOrUser").val(),
                    isDenied: 1
                },
                dataType: "json",
                type: "POST",
                success: function(data){
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });

                    initAssignedRoleListTree();
                    initDeniedPermissionListTree();
                },
                error: function(jqXHR, exception){
                    $.unblockUI();
                }
            }).complete(function(){
                $.unblockUI();
            });
        });

        deleteDeniedToUserBn.click(function(){
            checkedDataList=[];
            unCheckedDataList=[];
            checkedIdList=[];
            unCheckedIdList=[];
            deniedPermissionListTree.find('li').each(function(){
                var mId=$(this).attr("id");
                tmpNode=deniedPermissionListTree.jstree(true).get_node(mId);
                if(tmpNode.state.selected){
                    checkedDataList.push({metaDataId: mId,
                        metaTypeId: tmpNode.state.META_TYPE_ID,
                        permissionId: tmpNode.state.PERMISSION_ID});
                    /*checkedIdList.push($(this).attr("id"));*/

                    var mIdArr=mId.split('-');
                    checkedIdList.push({
                        id: mIdArr[0],
                        isOpen: deniedPermissionListTree.jstree(true).is_open(mId)
                    });
                } else {
                    var hasChildChecked=false;
                    $.each(tmpNode.children, function(key, val){
                        if(deniedPermissionListTree.jstree(true).get_node(val).state.selected){
                            hasChildChecked=true;
                            return false;
                        }
                    });
                    if(!hasChildChecked){
                        unCheckedDataList.push({metaDataId: $(this).attr("id"),
                            metaTypeId: tmpNode.state.META_TYPE_ID,
                            permissionId: tmpNode.state.PERMISSION_ID});
                        unCheckedIdList.push($(this).attr("id"));
                    }
                }
            });
            if(checkedDataList.length === 0){
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: "Тухайн хэрэглэгчээс эрх хасах гэж байгаа бол олгогдсон эрх хэсгээс хасахыг хүссэн эрхээ сонгож эрхээ хасна уу.",
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
                    userId: userId,
                    checkedDataList: checkedDataList,
                    checkedIdList: checkedIdList,
                    unCheckedDataList: unCheckedDataList,
                    unCheckedIdList: unCheckedIdList,
                    isDenied: 1
                },
                dataType: "json",
                type: "POST",
                success: function(data){
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    initAssignedRoleListTree();
                    initDeniedPermissionListTree();
                },
                error: function(jqXHR, exception){
                    $.unblockUI();
                }
            }).complete(function(){
                $.unblockUI();
            });
        });

        $('#searchNotassignedRoleInput').keydown(function(e){
            var code=e.keyCode || e.which;
            if(code === 13){
                Core.blockUI({
                    message: plang.get('msg_saving_block'),
                    boxed: true
                });
                $.ajax({
                    url: 'mdum/getData',
                    data: $.extend(assignedRoleParams({}), {searchText: $(this).val()}),
                    dataType: "json",
                    type: "POST",
                    success: function(data){
                        notassignedRoleListTree.jstree(true).settings.core.data=data;
                        notassignedRoleListTree.jstree(true).refresh();
                    },
                    error: function(jqXHR, exception){
                        $.unblockUI();
                    }
                }).complete(function(){
                    $.unblockUI();
                });
            }
        });

        $('#searchNotassignedPermissionInput').keydown(function(e){
            var code=e.keyCode || e.which;
            if(code === 13){
                Core.blockUI({
                    message: plang.get('msg_saving_block'),
                    boxed: true
                });
                $.ajax({
                    url: 'mdum/getData',
                    data: $.extend(notassignedPermissionParams({}), {searchText: $(this).val()}),
                    dataType: "json",
                    type: "POST",
                    success: function(data){
                        notassignedPermissionListTree.jstree(true).settings.core.data=data;
                        notassignedPermissionListTree.jstree(true).refresh();
                    },
                    error: function(jqXHR, exception){
                        $.unblockUI();
                    }
                }).complete(function(){
                    $.unblockUI();
                });
            }
        });

        $('#searchAssignedRoleInput').keydown(function(e){
            var code=e.keyCode || e.which;
            if(code === 13){
                Core.blockUI({
                    message: plang.get('msg_saving_block'),
                    boxed: true
                });
                $.ajax({
                    url: 'mdum/getData',
                    data: $.extend(assignedRoleParams({}), {searchText: $(this).val()}),
                    dataType: "json",
                    type: "POST",
                    success: function(data){
                        assignedRoleListTree.jstree(true).settings.core.data=data;
                        assignedRoleListTree.jstree(true).refresh();
                    },
                    error: function(jqXHR, exception){
                        $.unblockUI();
                    }
                }).complete(function(){
                    $.unblockUI();
                });
            }
        });

        $('#searchAssignedPermissionInput').keydown(function(e){
            var code = e.keyCode || e.which;
            if (code === 13) {
                Core.blockUI({
                    message: plang.get('msg_saving_block'),
                    boxed: true
                });
                $.ajax({
                    url: 'mdum/getData',
                    data: $.extend(assignedPermissionParams({}), {searchText: $(this).val()}),
                    dataType: "json",
                    type: "POST",
                    success: function(data){
                        assignedPermissionListTree.jstree(true).settings.core.data=data;
                        assignedPermissionListTree.jstree(true).refresh();
                    },
                    error: function(jqXHR, exception){
                        $.unblockUI();
                    }
                }).complete(function(){
                    $.unblockUI();
                });
            }
        });

        $('#searchDeniedPermissionInput').keydown(function(e){
            var code=e.keyCode || e.which;
            if (code === 13) {
                Core.blockUI({
                    message: plang.get('msg_saving_block'),
                    boxed: true
                });
                $.ajax({
                    url: 'mdum/getData',
                    data: $.extend(deniedPermissionParams({}), {searchText: $(this).val()}),
                    dataType: "json",
                    type: "POST",
                    success: function(data){
                        deniedPermissionListTree.jstree(true).settings.core.data=data;
                        deniedPermissionListTree.jstree(true).refresh();
                    },
                    error: function(jqXHR, exception){
                        $.unblockUI();
                    }
                }).complete(function(){
                    $.unblockUI();
                });
            }
        });
    };

    var initParams=function(){
        $permissionWindow=$("#permissionWindow-" + uuId),
        notassignedRoleListTree=$permissionWindow.find("#notassigned-role-list"),
        notassignedPermissionListTree=$permissionWindow.find("#notassigned-permission-list"),
        assignedRoleListTree=$permissionWindow.find("#assigned-role-list"),
        assignedPermissionListTree=$permissionWindow.find("#assigned-permission-list"),
        deniedPermissionListTree=$permissionWindow.find("#denied-permission-list"),
        saveRoleToUserBn=$permissionWindow.find("#saveRoleToUser"),
        unsetRoleToUserBn=$permissionWindow.find("#unsetRoleToUser"),
        savePermissionToUserBn=$permissionWindow.find("#savePermissionToUser"),
        unsetPermissionToUserBn=$permissionWindow.find("#unsetPermissionToUser"),
        saveDeniedToUserBn=$permissionWindow.find("#saveDeniedToUser"),
        deleteDeniedToUserBn=$permissionWindow.find("#deleteDeniedToUser"), 
        $tableScrollable = $permissionWindow.find('.table-scrollable');
        if ($tableScrollable.length) {
            var dynamicHeight = $(window).height() - $tableScrollable.eq(0).offset().top - 40;
            $tableScrollable.css({'height': dynamicHeight+'px'});
        }
    };

    var initJsTree=function(){
        initNotassignedRoleListTree();
        initNotassignedPermissionListTree();
        initAssignedRoleListTree();
        initAssignedPermissionListTree();
        initDeniedPermissionListTree();
    };

    var notAssignedRoleParams=function(node){
        if (node.id === "#" || typeof node.id === "undefined") {
            return {
                isSavedRole: 0,
                userId: userId,
                parent: 'ok',
                parentNode: '',
                isDenied: 0,
                isSelected: 0,
                haveCriteria: 0
            };
        } else {
            return {
                isSavedRole: 0,
                userId: userId,
                metaDataId: node.state.ROLE_ID != null ? metaDataId : node.id,
                roleId: getRoleId(node),
                parentNode: (node.parent === "#" ? '' : node.parent),
                isDisabled: 1,
                isDenied: 0,
                isSelected: 0,
                haveCriteria: 0
            };
        }
    };

    var initNotassignedRoleListTree=function(){
        return;
        notassignedRoleListTree.remove();
        $('<div id="notassigned-role-list"></div>').insertBefore($("#notassigned-role-list-adjacent"));
        notassignedRoleListTree=$('#notassigned-role-list');

        notassignedRoleListTree.jstree({
            core: {
                "check_callback": true,
                "expand_selected_onload": false,
                "open_parents": false,
                "load_open": false,
                "data": {
                    url: URL_APP + 'mdum/getRoleAndPermission',
                    dataType: "json",
                    data: function(node){
                        return notAssignedRoleParams(node);
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
                three_state: false,
                two_state: false,
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
    };

    var notassignedPermissionParams=function(node){
        if (node.id === "#" || typeof node.id === "undefined") {
            return {
                roleId: roleId,
                userId: userId,
                metaDataId: metaDataId,
                parent: 'ok',
                parentNode: '',
                isDenied: 0,
                isSelected: 0,
                haveCriteria: 0
            };
        } else {
            return {
                roleId: roleId,
                userId: userId,
                metaDataId: node.id,
                parentNode: (node.parent === "#" ? '' : node.parent),
                isDisabled: 0,
                isDenied: 0,
                isSelected: 0,
                haveCriteria: 0
            };
        }
    };

    var initNotassignedPermissionListTree = function() {
        
        notassignedPermissionListTree.remove();
        $('<div id="notassigned-permission-list-' + uuId + '"></div>').insertBefore($permissionWindow.find("#notassigned-permission-list-adjacent"));
        notassignedPermissionListTree = $permissionWindow.find('#notassigned-permission-list-' + uuId);

        notassignedPermissionListTree.on("changed.jstree", function(e, data) {
            if (data.action == "select_node") {
                if (typeof $permissionWindow.find("#notassignedIsCheckChild").attr('checked') !== "undefined") {
                    $.each(notassignedPermissionListTree.jstree(true).get_node(data.node.id).children, function(key, val){
                        selectNode(notassignedPermissionListTree, val);
                    });
                }
            } else if (data.action === "deselect_node") {
                if (typeof $permissionWindow.find("#notassignedIsCheckChild").attr('checked') !== "undefined") {
                    $.each(notassignedPermissionListTree.jstree(true).get_node(data.node.id).children, function(key, val){
                        deSelectNode(notassignedPermissionListTree, val);
                    });
                }
            }
        }).on("open_node.jstree", function(e, data){
        }).on("deselect_node.jstree", function(e, data){
            var $parent=notassignedPermissionListTree.jstree(true).get_node(data.node.parent);
            if (typeof $parent.icon !== 'undefined' && $parent.icon === 'icon-list icon-state-warning') {
                e.stopImmediatePropagation();
            }
        }).jstree({
            'core': {
                "check_callback": true,
                "expand_selected_onload": false,
                "multiple": true,
                "open_parents": false,
                "load_open": false,
                "data": {
                    url: 'mdum/getData',
                    dataType: "json",
                    type: "POST",
                    data: function(node){
                        return notassignedPermissionParams(node);
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
                'changed', 'types', 'unique', 'wholerow', 'checkbox', 'search'
            ],
            "search": {
                'case_insensitive': true,
                'show_only_matches': true
            }
        });
    };

    var assignedRoleParams=function(node){
        if(node.id === "#" || typeof node.id === "undefined"){
            return {
                isSavedRole: 1,
                isSaved: 1,
                userId: userId,
                parent: 'ok',
                parentNode: '',
                isDenied: 0,
                isDisabled: 0,
                isSelected: 0,
                haveCriteria: 0
            };
        } else {
            return {
                isSavedRole: 1,
                isSaved: 1,
                metaDataId: node.state.ROLE_ID != null ? metaDataId : node.id,
                roleId: getRoleId(node),
                parentNode: (node.parent === "#" ? '' : node.parent),
                isDenied: 0,
                isDisabled: 0,
                isSelected: 0,
                haveCriteria: 0
            };
        }
    };

    var initAssignedRoleListTree=function(){
        return;
        assignedRoleListTree.remove();
        $('<div id="assigned-role-list"></div>').insertBefore($("#assigned-role-list-adjacent"));
        assignedRoleListTree=$('#assigned-role-list');

        assignedRoleListTree.jstree({
            core: {
                "check_callback": true,
                "expand_selected_onload": false,
                "open_parents": false,
                "load_open": false,
                "data": {
                    url: 'mdum/getRoleAndPermission',
                    dataType: "json",
                    data: function(node){
                        return assignedRoleParams(node);
                    }
                },
                'themes': {
                    'responsive': false,
//                  'variant': 'small',
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
    };

    var assignedPermissionParams=function(node){
        if(node.id === "#" || typeof node.id === "undefined"){
            return {
                isSaved: 1,
                roleId: roleId,
                userId: userId,
                metaDataId: metaDataId,
                parent: 'ok',
                parentNode: '',
                isDenied: 0,
                isSelected: 0,
                haveCriteria: 1
            };
        } else {
            return {
                isSaved: 1,
                roleId: roleId,
                userId: userId,
                metaDataId: node.id,
                parentNode: (node.parent === "#" ? '' : node.parent),
                isDenied: 0,
                isSelected: 0,
                haveCriteria: 1
            };
        }
    };

    var initAssignedPermissionListTree=function(){
        assignedPermissionListTree.remove();
        $('<div id="assigned-permission-list-' + uuId + '"></div>').insertBefore($permissionWindow.find("#assigned-permission-list-adjacent"));
        assignedPermissionListTree=$permissionWindow.find('#assigned-permission-list-' + uuId);

        assignedPermissionListTree.on("changed.jstree", function(e, data){
            if(data.action === "select_node"){
                if(typeof $permissionWindow.find("#assignedIsCheckChild").attr('checked') !== "undefined"){
                    $.each(assignedPermissionListTree.jstree(true).get_node(data.node.id).children, function(key, val){
                        selectNode(assignedPermissionListTree, val);
                    });
                }
            } else if(data.action === "deselect_node"){
                if(typeof $permissionWindow.find("#assignedIsCheckChild").attr('checked') !== "undefined"){
                    $.each(assignedPermissionListTree.jstree(true).get_node(data.node.id).children, function(key, val){
                        deSelectNode(assignedPermissionListTree, val);
                    });
                }
            }
        }).on("open_node.jstree", function(e, data){

        }).on("select_node.jstree", function(e, data){
            var $parent=assignedPermissionListTree.jstree(true).get_node(data.node.parent);
            if(typeof $parent.icon !== 'undefined' && $parent.icon === 'icon-list icon-state-warning'){
                e.stopImmediatePropagation();
            }
        }).jstree({
            'core': {
                "check_callback": true,
                "expand_selected_onload": false,
                "multiple": true,
                "open_parents": false,
                "load_open": false,
                "data": {
                    url: 'mdum/getData',
                    dataType: "json",
                    type: "POST",
                    data: function(node){
                        return assignedPermissionParams(node);
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
    };

    var deniedPermissionParams=function(node){
        if(node.id === "#" || typeof node.id === "undefined"){
            return {
                isSaved: 1,
                roleId: roleId,
                userId: userId,
                metaDataId: metaDataId,
                parent: 'ok',
                parentNode: '',
                isDisabled: 0,
                isDenied: 1,
                isSelected: 0,
                haveCriteria: 0
            };
        } else {
            return {
                isSaved: 1,
                roleId: roleId,
                userId: userId,
                metaDataId: node.id,
                parentNode: (node.parent === "#" ? '' : node.parent),
                isDisabled: 0,
                isDenied: 1,
                isSelected: 0,
                haveCriteria: 0
            };
        }
    };

    var initDeniedPermissionListTree=function(){
        return;
        deniedPermissionListTree.remove();
        $('<div id="denied-permission-list"></div>').insertBefore($("#denied-permission-list-adjacent"));
        deniedPermissionListTree=$('#denied-permission-list');

        deniedPermissionListTree
                .on("changed.jstree", function(e, data){
                    if (data.action === "select_node") {
                        if (typeof $("#deniedIsCheckChild").attr('checked') !== "undefined") {
                            $.each(deniedPermissionListTree.jstree(true).get_node(data.node.id).children, function(key, val){
                                selectNode(deniedPermissionListTree, val);
                            });
                        }
                    } else if (data.action === "deselect_node") {
                        if (typeof $("#deniedIsCheckChild").attr('checked') !== "undefined") {
                            $.each(deniedPermissionListTree.jstree(true).get_node(data.node.id).children, function(key, val){
                                deSelectNode(deniedPermissionListTree, val);
                            });
                        }
                    }
                })
                .on("open_node.jstree", function(e, data){

                })
                .jstree({
                    'core': {
                        "check_callback": true,
                        "expand_selected_onload": false,
                        "multiple": true,
                        "open_parents": false,
                        "load_open": false,
                        "data": {
                            url: 'mdum/getData',
                            dataType: "json",
                            data: function(node){
                                return deniedPermissionParams(node);
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
                        'case_insensitive': true,
                        'show_only_matches': true
                    }
                });
    };

    var getRoleId=function(node){
        if(node.state.ROLE_ID != null){
            return node.state.ROLE_ID;
        } else {
            return node.parents[node.parents.length - 2];
        }
    };

    var checkedParentNodeSave=function(tmpNode, objJsTree){
        $.each(tmpNode.parents, function(key, val){
            if(val !== "#" && checkedIdList.indexOf(val) < 0){
                /*checkedIdList.push(val);*/
                var mIdArr=val.split('-');
                checkedIdList.push({
                    id: mIdArr[0],
                    isOpen: objJsTree.jstree(true).is_open(val)
                });
            }
        });
    };

    var selectNode=function(list, id){
        list.jstree(true).select_node(id);
    };

    var deSelectNode=function(list, id){
        list.jstree(true).deselect_node(id);
    };

    //</editor-fold>
    return {
        init: function(pMetaDataId, pRoleId, pUserId, uniqId){
            metaDataId=pMetaDataId;
            userId=pUserId;
            roleId=pRoleId;
            uuId=uniqId;
            initParams();
            initJsTree();
            initEvent();
            $('.dg-custom-tooltip').tooltip();
        }
    };
}();