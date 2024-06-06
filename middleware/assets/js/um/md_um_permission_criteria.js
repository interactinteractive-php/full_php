/* global go, Core, msg_saving_block, PNotify */
var MdUmPermissionCriteria=function(){
    //<editor-fold defaultstate="collapsed" desc="Variables">
    var metaDataId,
            roleId,
            userId,
            uuId,
            tmpNode,
            saveCriteriaBtn=$("#save_role_criteria"),
            permissionCriteria={},
            permissionCriteriaUpdate={},
            $permissionWindow;
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Events">
    var initEvent=function(){
        $permissionWindow=$("#permissionWindow-" + uuId);

        $permissionWindow.on("click", ".checkall", function(){
            if ($(this).is(":checked")) {
                $(".rowCheckbox").attr("checked", "checked");
                $(".rowCheckbox").parent().addClass('checked');
                $(".checkedCriteria").val('1');
            } else {
                $(".rowCheckbox").removeAttr("checked");
                $(".rowCheckbox").parent().removeClass('checked');
                $(".checkedCriteria").val('0');
            }
        });

        $permissionWindow.on("click", ".rowCheckbox", function(){
            if ($(this).is(":checked")) {
                $(this).closest("td").find(".checkedCriteria").val('1');
            } else {
                $(this).closest("td").find(".checkedCriteria").val('0');
            }
        });

        $('body').on('click', '.criteriaListShower', function(e){
            e.preventDefault();
            e.stopPropagation();
            tmpNode = $(e.target).parents("div.jstree-default").jstree(true).get_node($(e.target).attr("metaid"));
            $permissionWindow.find("form#umMetaPermCriteriaCreateForm #permissionId").val(tmpNode.state.PERMISSION_ID);
            criteriaList($(e.target).attr('metaid'));
        });

        saveCriteriaBtn.click(function(){
            Core.blockUI({
                message: plang.get('msg_saving_block'),
                boxed: true
            });

            $.ajax({
                type: 'post',
                url: 'mdum/savePermissionCriteria',
                data: {
                    userId: userId,
                    roleId: roleId,
                    permissionCriteria: permissionCriteria,
                    permissionCriteriaUpdate: permissionCriteriaUpdate,
                    //uncheckedMeta: menuListTree.find("input").serialize(),
                    permissionCreateData: $permissionWindow.find("#umMetaPermCriteriaCreateForm").serialize()
                },
                dataType: "json",
                success: function(res){
                    PNotify.removeAll();
                    new PNotify({
                        title: res.status,
                        text: res.message,
                        type: res.status,
                        sticker: false
                    });
                    if(res.status === 'success'){
                        deletePermissionIdList=[];
                        deleteMetaIdList=[];
                        permissionCriteria={};
                        parentCechkedData=[];
                        permissionCriteriaUpdate={};
                    }
                },
                error: function(){
                    alert("Уучлаарай, Дахин оролдно уу");
                }
            }).complete(function(){
                $.unblockUI();
            });
        });
    };

    var criteriaList=function(id){
        $.ajax({
            type: 'post',
            url: 'mdum/getCriteriaListByDataview',
            data: {metaId: id, roleId: roleId, userId: userId},
            dataType: "html",
            success: function(data){
                PNotify.removeAll();
                if (data == 'empty') {
                    new PNotify({
                        title: 'Info',
                        text: 'Хоосон байна!',
                        type: 'info',
                        sticker: false
                    });
                } else {
                    $permissionWindow.find("table#umCriteriaList").parent().removeClass("hide");
                    $permissionWindow.find("table#umCriteriaList tbody").empty().append(data);
                    Core.initUniform();
                    Core.initLongInput();
                }
            },
            error: function(){
                Core.unblockUI();
            }
        }).complete(function(){
            Core.unblockUI();
        });
    };
    //</editor-fold>
    return {
        init: function(pMetaDataId, pRoleId, pUserId, pUniqId){
            metaDataId=pMetaDataId;
            userId=pUserId;
            roleId=pRoleId;
            uuId=pUniqId;
            initEvent();
        }
    };
}();