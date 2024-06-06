<div id="permissionWindow-<?php echo $this->uniqId; ?>" >
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="row">
                <div class="col-md-11">
                    <div class="row">
                        <div class="col-md-10"><b>Олгох эрх</b></div>
                    </div>
                    <div class="table-scrollable" style="height: 350px; overflow-y: auto; margin-bottom: 0 !important">
                        <div class="checkbox-list">
                            <label>
                                <input type="checkbox" value="1" id="notassignedIsCheckChild"/> Дэд эрхүүдийг дагаж сонгох эсэх
                            </label>
                        </div>
                        <div id="notassigned-permission-list-adjacent"></div>
                    </div>
                </div>
                <div class="col-md-1" style="padding-left: 5px; padding-right: 5px;">
                    <div class="row">
                        <div class="input-group" style="text-align: center;">
                            <span class="input-group-btn">
                                <?php
                                echo Form::button(
                                    array(
                                        'class' => 'dg-custom-tooltip btn green-meadow',
                                        'id' => 'savePermissionToUser',
                                        'title' => 'Эрх оноох', 
                                        'style' => 'margin-top: 140px;', 
                                        'value' => '<i class="fa fa-hand-o-right"></i>'
                                    )
                                );
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group" style="text-align: center;">
                            <span class="input-group-btn">
                                <?php
                                echo Form::button(
                                    array(
                                        'class' => 'dg-custom-tooltip btn green-meadow',
                                        'id' => 'unsetPermissionToUser',
                                        'title' => 'Оноосон эрх хасах', 
                                        'style' => 'margin-top: 100px;', 
                                        'value' => '<i class="fa fa-hand-o-left"></i>'
                                    )
                                );
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-10"><b>Олгогдсон эрх</b></div>
                    </div>
                    <div class="table-scrollable" style="height: 350px; overflow-y: auto; margin-bottom: 0 !important">
                        <div class="checkbox-list">
                            <label>
                                <input type="checkbox" value="1" id="assignedIsCheckChild"/> Дэд эрхүүдийг дагаж сонгох эсэх
                            </label>
                        </div>
                        <div id="assigned-permission-list-adjacent"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form method="post" id="umMetaPermCriteriaCreateForm">
                <div class="table-scrollable hide" style="height: 480px; overflow-y: auto">
                    <table class="table table-sm table-hover table-striped" id="umCriteriaList">
                        <thead>
                            <tr>
                                <th style="width:3%;">#</th>
                                <th style="width:1%;"><input type="checkbox" class="checkall rowCheckbox" /></th>
                                <th style="width:5%;">Багц</th>
                                <th>Код</th>
                                <th>Нэр</th>
                                <th>Тайлбар</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <a id="save_role_criteria" href="javascript:;" class="btn green-meadow btn-sm float-left mt5"><i class="icon-plus3 font-size-12"></i> Criteria хадгалах</a>
                </div>
                <input type="hidden" id="permissionId" name="permissionId" />
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
var userId = <?php echo (isset($this->userId) && !is_null($this->userId)) ? "'" . $this->userId . "'" : 'null'; ?>,
    roleId = <?php echo (isset($this->roleId) && !is_null($this->roleId)) ? "'" . $this->roleId . "'" : 'null'; ?>,
    uniqId = <?php echo (isset($this->uniqId) && !is_null($this->uniqId)) ? $this->uniqId : ''; ?>,
    metaDataId = <?php echo (isset($this->metaDataId) && !is_null($this->metaDataId)) ? $this->metaDataId : ''; ?>;

$(document).ready(function(){
    $.getScript("middleware/assets/js/um/md_um_user_role_permission.js", function(){
        $.getScript("middleware/assets/js/um/md_um_permission_criteria.js", function(){
            if (userId !== '' || roleId !== '') {
                MdUmUserRolePermission.init(metaDataId, roleId, userId, uniqId);
                MdUmPermissionCriteria.init(metaDataId, roleId, userId, uniqId);
            }
        });
    });
});
</script>