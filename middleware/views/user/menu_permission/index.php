<div class="col-md-12" id="permissionWindow">
    <div class="card light shadow">      
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title">
                <i class="fa fa-cogs"></i>Хэрэглэгчийн тохиргоо удирдах 
            </div>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body xs-form">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-body">
                        <div class="card blue-hoki box">
                            <div class="card-header card-header-no-padding header-elements-inline">
                                <div class="card-title">
                                    <i class="fa fa-cogs"></i>Тохируулах төрөл
                                </div>
                                <div class="header-elements">
                                    <div class="list-icons">
                                        <a class="list-icons-item" data-action="collapse"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6 form-group row fom-row">
                                        <div class="col-sm-12 input-group ">
                                            <?php echo Form::label(array('text' => 'Мета төрөл', 'for' => 'metaTypeId', 'class' => 'col-form-label col-sm-4')); ?>
                                            <div class="col-sm-6">
                                                <?php
                                                echo Form::select(
                                                        array(
                                                            'name' => 'metaTypeId',
                                                            'id' => 'metaTypeId',
                                                            'class' => 'form-control select2 form-control-sm input-xxlarge',
                                                            'data' => (new Mduser())->getPermissionMetaTypeList(),
                                                            'op_text' => 'META_TYPE_NAME',
                                                            'op_value' => 'META_TYPE_ID',
                                                            'value' => $this->defaultType
                                                        )
                                                );
                                                ?>
                                            </div>
                                        </div>                           
                                    </div>
                                </div>                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt10">
                <div class="col-md-4">
                    <div class="row">
                         <div class="col-md-12">
                            <?php echo Form::button(array('class' => 'btn btn-sm green-meadow', 'value' => '<i class="icon-plus3 font-size-12"></i>' . 'Эрх тохируулах', 'onclick' => 'createPermissionSelectedMetas(this);')); ?> 
                         </div>    
                    </div>
                    <fieldset class="mb15" data-initialized="1" id="meta_fieldset">
                        <legend>Меню</legend>
                        <div id="meta_section">
                            <div id="menu-list" class="tree-demo"></div>
                        </div>
                    </fieldset>   
                </div>
                <div class="col-md-8 col-sm-12">
                    <div id = "userSection"> </div>
                </div>
            </div>
            <?php echo Form::close(); ?>
        </div>
    </div>
</div> 
<script type="text/javascript">
    $(function() {
        $('#menu-list').jstree({
            'plugins': ["wholerow", "checkbox", "types", "contextmenu"],
//            "contextmenu": {
//                "items": function($node) {
//                    return {
//                        "Бүх эрхийг идэвхжүүлэх": {
//                            "label": "Бүх эрхийг идэвхжүүлэх",
//                            "action": function(obj) {
//                                checkAllMetaForMenu();
//                            }
//                        }
//                    };
//                }
//            },
            'core': {
                "themes": {
                    "responsive": false
                },
                'data': [<?php echo $this->menuTreeList; ?>]
            },
            "checkbox": {
                real_checkboxes: true,
                real_checkboxes_names: function(n) {
                    var nid = 0;
                    $(n).each(function(data) {
                        nid = $(this).attr("nodeid");
                    });
                    return (["check_" + nid, nid]);
                },
                two_state: true,
                whole_node : true
            },
            "types": {
                "default": {
                    "icon": "fa fa-play-circle text-orange-400"
                },
                "file": {
                    "icon": "fa fa-play-circle text-orange-400"
                }
            }
        });
        $("#permissionWindow").on("change", "select#metaTypeId", function() {
            $("#userSection").empty();
            changePermissionGrid(this.value, $(this).find("option:selected").text());
        });
        $('#menu-list').on('click', 'i.jstree-checkbox', function() {
            $('#menu-list').removeClass('hidden');
            var _this = $(this);
            var row = _this.parents('a');
            var id = row.find('input[name="trgMetaDataId[]"]').val();
            callUserSectionGrid(id);
        });
    });
    function clickOnMeta(id, typeId) {
        var types = [];
        var tabTypes = [];
        tabTypes.push({0: {"key": "dataViewList", "value": 200101010000016}, 1: {"key": "package", "value": 200101010000009}, 2: {"key": "process", "value": 200101010000011}, 3: {"key": "report", "value": 200101010000035}});
        if (id) {
            $.ajax({
                type: 'post',
                url: 'mduser/getActionMeta',
                data: {metaDataId: id, typeId: typeId},
                dataType: 'json',
                success: function(data) {
                    if (data != null) {
                        $.each(data, function(i, value) {
                            types.push(value['META_TYPE_ID']);
                        });
                        $.unique(types);
                        $.each(tabTypes[0], function(i, v) {
                            if ($.inArray(v['value'].toString(), types) == -1) {
                                $("#menuSettings").find("ul").find("#" + v['key']).addClass("hidden");
                                $("#menuSettings").find("div#" + v['key']).addClass("hidden");
                            } else {
                                if ($("#menuSettings").find("ul").find("#" + v['key']).hasClass("hidden")) {
                                    $("#menuSettings").find("ul").find("#" + v['key']).removeClass("hidden");
                                    $("#menuSettings").find("div#" + v['key']).removeClass("hidden");
                                }
                            }
                        });
                        $('#menuSettings').find("ul").find("li").each(function(i) {
                            if ($(this).hasClass("active") && $(this).hasClass("hidden")) {
                                $(this).removeClass("active");
                                $(".menuSettings").find("div#" + $(this).attr("id")).removeClass("active");
                            }
                            if (!$('.menuSettings').find("ul").find("li").not(".hidden").first().hasClass("active")) {
                                var firstActiveLi = $('.menuSettings').find("ul").find("li").not(".hidden").first();
                                $('.menuSettings').find("ul").find("#" + $(firstActiveLi).attr('id')).addClass("active");
                                $('.menuSettings').find("div#" + $(firstActiveLi).attr('id')).addClass("active");
                            }
                            if (!$(this).hasClass("hidden")) {
                                var tableId = $(this).attr("id");
                                var typeId = "";
                                $.each(tabTypes[0], function(i, v) {
                                    if (v['key'].toString() == tableId) {
                                        typeId = v['value'];
                                    }
                                });
                                $.each(data, function(i, value) {
                                    if (value['META_TYPE_ID'] == typeId) {
                                        $("div#" + tableId).find("table").find("tbody").append("<tr>\n\
                                                                                             <td><input type='checkbox' class='checkboxes' value='1'/></td>\n\
                                                                                             <td>" + value['SRC_META_DATA_CODE'] + "</td>\n\
                                                                                             <td>" + value['TRG_META_DATA_CODE'] + "</td>\n\
                                                                                             <td>" + value['TRG_META_DATA_NAME'] + "</td>\n\
                                                                                             <td><button type='button' class='btn btn-xs purple-plum' onclick='referencedMeta(" + value['ACTION_META_DATA_ID'] + ", " + value['META_DATA_ID'] + ", " + value['META_TYPE_ID'] + ", " + value['TRG_META_TYPE_ID'] + ")'>...</button></td>\n\
                                                                                          </tr>");
                                    }
                                });
                            }
                        });
                    }
                }
            });
        }
    }
    
    function changePermissionGrid(metaTypeId, metaTypeName) {
        $("#meta_fieldset").find('legend').html(metaTypeName);
        if (metaTypeId === '200101010000025') {
            $("#meta-list").remove();
            if ($("#meta_section").find("#menu-list").length == 0) {
                $("#meta_section").append("<div id='menu-list' class='tree-demo'></div>");
            }
            $('#menu-list').jstree({
                'plugins': ["wholerow", "checkbox", "types", "contextmenu"],
//                "contextmenu": {
//                    "items": function($node) {
//                        return {
//                            "Бүх эрхийг идэвхжүүлэх": {
//                                "label": "Бүх эрхийг идэвхжүүлэх",
//                                "action": function(obj) {
//                                    checkAllMetaForMenu();
//                                }
//                            }
//                        };
//                    }
//                },
                'core': {
                    "themes": {
                        "responsive": false
                    },
                    'data': [<?php echo $this->menuTreeList; ?>]
                },
                "checkbox": {
                    real_checkboxes: true,
                    real_checkboxes_names: function(n) {
                        var nid = 0;
                        $(n).each(function(data) {
                            nid = $(this).attr("nodeid");
                        });
                        return (["check_" + nid, nid]);
                    },
                    two_state: true
                },
                "types": {
                    "default": {
                        "icon": "fa fa-play-circle text-orange-400"
                    },
                    "file": {
                        "icon": "fa fa-play-circle text-orange-400"
                    }
                }
            });
            $('#menu-list').on('click', 'i.jstree-checkbox', function() {
                //$('#menu-list').removeClass('hidden');
                var _this = $(this);
                var row = _this.parents('a');
                var id = row.find('input[name="trgMetaDataId[]"]').val();
                callUserSectionGrid(id);
            });
        } else {
            $("#menu-list").remove();
            $("#meta-list").remove();
            if ($("#meta-list").length == 0) {
                $("#meta_section").append("<div id='meta-list'><div class='jeasyuiTheme3'><table id='metaList'></table></div></div>");
            }

            $("#metaList").datagrid({
                view: horizonscrollview,
                url: 'mduser/getMetaList',
                queryParams: {metaTypeId: metaTypeId},
                rownumbers: true,
                singleSelect: false,
                pagination: true,
                pageSize: 10,
                striped: true,
                showFooter: true,
                remoteFilter: true,
                filterDelay: 10000000000,
                columns: [[
                        {field: 'ck', checkbox: true},
                        {field: 'META_DATA_CODE', title: metaTypeName + ' код', sortable: true, halign: 'center', align: 'left', width: 150},
                        {field: 'META_DATA_NAME', title: metaTypeName + ' нэр', sortable: true, halign: 'center', align: 'left', width: 250},
                        {field: 'DESCRIPTION', title: 'Дэлгэрэнгүй', sortable: true, halign: 'center', align: 'left', width: 250},
                        {field: 'USER_NAME', title: 'Үүсгэсэн хэрэглэгч', sortable: true, halign: 'center', align: 'left', width: 150}
                    ]],
                onLoadSuccess: function() {
                    showGridMessage($("#metaList"));
                },
                onClickRow: function(index, row) {
                    callUserSectionGrid(row.META_DATA_ID);
                }
            });
            $("#metaList").datagrid('enableFilter');
        }

    }
    function callUserSectionGrid(id) {
        $.ajax({
            type: 'post',
            url: 'mduser/userPermissionByMeta',
            data: {metaDataId: id},
            dataType: 'json',
            success: function(data) {
                if (data != null) {
                    $("#userSection").empty();
                    $("#userSection").append(data.Html);
                }
            }
        });
    }
    function createPermissionSelectedMetas() {
        var metaDataIds = [];
        if ($('#menu-list').length > 0) {
            $.each($("#menu-list").jstree("get_checked", true),function(){
//                console.log(this.)
            });
            if(metaDataIds.length > 0){
                addMultiplePermission(metaDataIds);
            }else{
                alert("Please choose at least one row"); 
            }
        } else if ($('#meta-list').length > 0) {
            var rows = $("#metaList").datagrid('getChecked');
            if (rows.length > 0) {
                $.each(rows, function(key, value) {
                    metaDataIds.push(value.META_DATA_ID);
                });
                addMultiplePermission(metaDataIds);
            } else {
                alert("Please choose at least one row");
            }
        }
    }
    function addMultiplePermission(metaDataIds) {
        var $dialogName = 'dialog-addMultiPermission';
        if (!$($dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: 'mduser/addMultiPermission',
            data: {metaDataIds: metaDataIds},
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {
                $("#" + $dialogName).empty().html(data.Html);
                var dialog = $("#" + $dialogName).dialog({
                    appendTo: "body",
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 900,
                    minWidth: 900,
                    height: 650,
                    modal: false,
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm blue-hoki', click: function() {
                                $("#addMultiPermission-form").validate({
                                    ignore: "",
                                    highlight: function(label) {
                                        $(label).closest('.control-group').addClass('error');

                                    },
                                    unhighlight: function(label) {
                                        $(label).closest('.control-group').removeClass('error');
                                    },
                                    errorPlacement: function() {
                                    }
                                });
                                if ($("#addMultiPermission-form").valid()) {
                                    addfieldCriteriaEditor.save();
                                    addrecordCriteriaEditor.save();
                                    $.ajax({
                                        type: 'post',
                                        url: 'mduser/createPermission',
                                        data: $("#addMultiPermission-form").serialize(),
                                        dataType: "json",
                                        beforeSend: function() {
                                            Core.blockUI({
                                                animate: true
                                            });
                                        },
                                        success: function(data) {
                                            Core.unblockUI();
                                            if (data.status === 'success') {
                                                new PNotify({
                                                    title: 'Success',
                                                    text: data.result,
                                                    type: 'success',
                                                    sticker: false
                                                });
                                            } else {
                                                new PNotify({
                                                    title: 'Error',
                                                    text: data.text,
                                                    type: 'error',
                                                    sticker: false
                                                });
                                            }
                                            $('#userPermissionList').datagrid('reload');
                                        },
                                        error: function() {
                                            alert("Error");
                                        }
                                    });
                                }
                                dialog.empty().dialog('close');
                                dialog.dialog('destroy').remove();
                            }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                                dialog.empty().dialog('close');
                                dialog.dialog('destroy').remove();
                            }}
                    ]
                });
                dialog.dialog('open');
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
            }
        }).done(function() {
            Core.initAjax();
        });
    }
</script>
