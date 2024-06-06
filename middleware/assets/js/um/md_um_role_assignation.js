/* global go, Core, msg_saving_block */
var MdUmRoleAssignation=function(metaDataId, uniqId){
    this.uniqId=uniqId;
    this.metaDataId=metaDataId;
};

MdUmRoleAssignation.prototype.initEventListener=function(){
    $('#assignationToUserSelectedTree')
            .on("loaded.jstree", function(e, data){

            })
            .on("changed.jstree", function(e, data){
                ;
            })
            .on("before_open.jstree", function(e, data){

            })
            .jstree({
                'core': {
                    expand_selected_onload: false,
                    "open_parents": false,
                    "load_open": false,
                    'data': {
//                  url: URL_APP + 'mdum/getData',
//                  dataType: "json",
//                  data: function(node){
//                    if(node.id === "#"){
//                      return {
//                        roleId: roleId,
//                        metaDataId: metaDataId,
//                        parent: 'ok',
//                        roleOrUser: $("#roleOrUser").val(),
//                        parentNode: ''
//                      };
//                    } else {
//                      return {
//                        roleId: roleId,
//                        metaDataId: node.id,
//                        roleOrUser: $("#roleOrUser").val(),
//                        parentNode: (node.parent === "#" ? '' : node.parent)
//                      };
//                    }
//                  }
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
                    three_state: false,
                    two_state: true,
                    whole_node: true
                },
                'types': {
//                'default': {'icon': 'folder'},
//                'file': {'valid_children': [], 'icon': 'file'}
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

MdUmRoleAssignation.prototype.initUserAutocomplete=function(){
    var umRoleAssignation=this;

    var getDisplayTxt=function(item){
        return item.USERNAME.concat(" (" + (item.LAST_NAME == null ? "" :
                item.LAST_NAME) + " " + (item.FIRST_NAME == null ? "" :
                item.FIRST_NAME) +
                ")");
    };

    $("#container_" + this.uniqId).on("focus, keydown",
            'input#assignationFromUser:not(disabled, readonly), input#assignationToUser:not(disabled, readonly)',
            function(e){
                var _this=$(this);
                var isHoverSelect=false;

                _this.autocomplete({
                    minLength: 1,
                    maxShowItems: 30,
                    delay: 500,
                    highlightClass: "lookup-ac-highlight",
                    appendTo: "body",
                    position: {my: "left top", at: "left bottom", collision: "flip flip"},
                    autoFocus: false,
                    source: function(request, response){
                        switch(_this.attr("id")){
                            case "assignationFromUser":
                                $.ajax({
                                    type: 'post',
                                    url: 'mdum/getUserList',
                                    dataType: "json",
                                    data: {q: request.term},
                                    success: function(data){
                                        response($.map(data, function(item){
                                            return {
                                                label: item.USERNAME,
                                                name: getDisplayTxt(item),
                                                data: item
                                            };
                                        }));
                                    }
                                });
                                break;
                            case "assignationToUser":
                                $.ajax({
                                    type: 'post',
                                    url: 'mdum/getUserList',
                                    dataType: "json",
                                    data: {q: request.term},
                                    success: function(data){
                                        response($.map(data, function(item){
                                            return {
                                                label: item.USERNAME,
                                                name: getDisplayTxt(item),
                                                data: item
                                            };
                                        }));
                                    }
                                });
                                break;
                        }
                    },
                    focus: function(event, ui){
                        if(typeof event.keyCode === 'undefined' || event.keyCode == 0){
                            isHoverSelect=false;
                        } else {
                            if(event.keyCode == 38 || event.keyCode == 40){
                                isHoverSelect=true;
                            }
                        }
                        return false;
                    },
                    open: function(){
                        $(this).autocomplete('widget').zIndex(99999999999999);
                        return false;
                    },
                    close: function(event, ui){
                        $(this).autocomplete("option", "appendTo", "body");
                    },
                    select: function(event, ui){
                        _this.val(getDisplayTxt(ui.item.data));
                        $("#" + _this.attr("id") + "Id").val(ui.item.data.USER_ID);
                        switch(_this.attr("id")){
                            case "assignationFromUser":
                                $("#assignationFromUserTree").parent("div").html(
                                        '<div id="assignationFromUserTree"></div>');
                                initJsTree($("#assignationFromUserTree"), ui.item.data.USER_ID,
                                        umRoleAssignation.metaDataId);

                                $("#assignationToUserSelectedTree").parent("div").html(
                                        '<div id="assignationToUserSelectedTree"></div>');

                                $("#assignationToUserSelectedTree").on("loaded.jstree", function(e, data){
                                    $(this).find('li').each(function(){
                                        $(this).hide();
                                    });
                                    Core.unblockUI("#permissionWindow");
                                }).on("changed.jstree", function(e, data){
                                    if(data.action === "deselect_node"){
                                        deSelectNode($('#assignationFromUserTree'), data.node.id);
                                    }
                                });
                                initJsTree($("#assignationToUserSelectedTree"), ui.item.data.USER_ID,
                                        umRoleAssignation.metaDataId);
                                break;
                            case "assignationToUser":
                                $("#assignationToUserTree").parent("div").html(
                                        '<div id="assignationToUserTree"></div>');
                                initJsTree($("#assignationToUserTree"), ui.item.data.USER_ID,
                                        umRoleAssignation.metaDataId);
                                break;
                        }
                        return false;
                    }
                }).autocomplete("instance")._renderItem=function(ul, item){
                    ul.addClass('lookup-ac-render');

                    var re=new RegExp("(" + this.term + ")", "gi"),
                            cls=this.options.highlightClass,
                            template="<span class='" + cls + "'>$1</span>",
                            label=item.label.replace(re, template);

                    return $('<li>').append('<div class="lookup-ac-render-code">' + label +
                            '</div><div class="lookup-ac-render-name">' + item.name + '</div>').appendTo(
                            ul);
                };
            });
};

var initJsTree=function(el, userId, metaDataId){
    el.on("changed.jstree", function(e, data){
        if(data.action === "select_node"){
            selectShowNode($('#assignationToUserSelectedTree'), data.node.id);
        } else if(data.action === "deselect_node"){
            deSelectHideNode($('#assignationToUserSelectedTree'), data.node.id);
        }
    })
//          .on("open_node.jstree", function(e, data){
//            $('#assignationToUserSelectedTree').jstree(true).open_node(data.node.id);
//          })
//          .on("close_node.jstree", function(e, data){
//            $('#assignationToUserSelectedTree').jstree(true).close_node(data.node.id);
//          })
            .jstree({
                core: {
                    "animation": 0,
                    "check_callback": true,
                    "expand_selected_onload": false,
                    "open_parents": false,
                    "load_open": false,
                    "data": {
                        url: URL_APP + 'mdum/getRoleAndPermission',
                        dataType: "json",
                        data: function(node){

                            if(node.id === "#"){
                                return {
                                    userId: userId,
//              metaDataId: metaDataId,
                                    parent: 'ok',
                                    parentNode: ''
                                };
                            } else {
                                return {
                                    userId: userId,
                                    metaDataId: node.state.ROLE_ID != null ? metaDataId : node.id,
                                    roleId: getRoleId(node),
                                    parentNode: (node.parent === "#" ? '' : node.parent)
                                };
                            }
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
//                'default': {'icon': 'folder'},
//                'file': {'valid_children': [], 'icon': 'file'}
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

var getRoleId=function(node){
    if(node.state.ROLE_ID != null){
        return node.state.ROLE_ID;
    } else {
        return node.parents[node.parents.length - 2];
    }
};

var selectShowNode=function(el, id){
    el.jstree(true).select_node(id);
    el.find('li#' + id).show();
};

var deSelectHideNode=function(el, id){
    el.jstree(true).deselect_node(id);
    el.find('li#' + id).hide();
};

var deSelectNode=function(el, id){
    el.jstree("deselect_node", id, true, true);
};