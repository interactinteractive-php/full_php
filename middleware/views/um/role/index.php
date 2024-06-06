<div class="card light">
    <div class="card-body xs-form">
        <div class="row">
            <div class="col-md-12" id="permissionWindow">
                <div class="row mt10">
                    <div class="col-md-4">
                        <div class="input-group quick-item">
                            <div class="input-icon">
                                <i class="fa fa-search"></i>
                                <?php
                                echo Form::text(array(
                                    'id' => 'searchPermissionInput', 'class' => 'form-control usernameMask',
                                    'placeholder' => 'Хайх', 'style' => 'padding-left:33px;',
                                    'tabindex' => 4));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">

                    </div>
                    <div class="col-md-4">
                        <div class="input-group quick-item">
                            <div class="input-icon">
                                <i class="fa fa-search"></i>
                                <?php
                                echo Form::text(array(
                                    'id' => 'searchSavedPermissionInput', 'class' => 'form-control usernameMask',
                                    'placeholder' => 'Хайх', 'style' => 'padding-left:33px;',
                                    'tabindex' => 4));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                    <div class="col-md-4">
                        <div><b>Олгох эрх</b></div>
                        <div class="table-scrollable" style="height: 480px; overflow-y: auto">
                            <div class="form-group row fom-row">
                                <div class="checkbox-list">
                                    <label>
                                        <input type="checkbox" value="1" id="isCheckChild"/>Дэд эрхүүдийг дагаж сонгох эсэх </label>
                                </div>
                            </div>
                            <div id="menu-list"></div>
                            <div id="save_role_permission_append_div"></div>
                        </div>        
                    </div>
                    <div class="col-md-4" style="padding-left: 0px;">
                        <div class="row">
                            <div class="col-md-11" style="padding-right: 0px;">
                                <div class="row">
                                    <div class="col-md-10"><b>Нэмэхээр сонгогдсон эрх</b></div>
                                </div>
                                <div class="table-scrollable" style="height: 240px; overflow-y: auto">
                                    <div id="selected-permission-list"></div>
                                    <div id="selected-permission-list-adjacent"></div>
                                </div>
                            </div>
                            <div class="col-md-1" style="padding-left: 5px; padding-right: 5px;">
                                <div class="input-group">
                                    <span class="input-group-btn" style="padding-left: 5px">
                                        <?php
                                        echo Form::button(array('class' => 'dg-custom-tooltip btn green-meadow',
                                            'id' => 'savePermissionToRole',
                                            'title' => 'Эрх оноох', 'style' => 'margin-top: 120px', 'value' => '<i class="fa fa-hand-o-right"></i>'));
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1" style="padding-left: 5px; padding-right: 0px;">
                                <div class="input-group">
                                    <span class="input-group-btn" style="padding-right: 10px">
                                        <?php
                                        echo Form::button(array('class' => 'dg-custom-tooltip btn green-meadow',
                                            'id' => 'deletePermissionFromRole',
                                            'title' => 'Оноосон эрхээс хасах', 'style' => 'margin-top: 120px; float: right;',
                                            'value' => '<i class="fa fa-hand-o-left"></i>'));
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-11" style="padding-right: 10px; padding-left: 0px;">
                                <div class="row">
                                    <div class="col-md-10"><b>Хасахаар сонгогдсон эрх</b></div>
                                </div>
                                <div class="table-scrollable" style="height: 240px; overflow-y: auto">
                                    <div id="unselected-permission-list"></div>
                                    <div id="unselected-permission-list-adjacent"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" style="padding-left: 0px;">
                        <div><b>Олгогдсон эрх</b></div>
                        <div class="table-scrollable" style="height: 480px; overflow-y: auto">
                            <div class="form-group row fom-row">
                                <div class="checkbox-list">
                                    <label>
                                        <input type="checkbox" value="1" id="isCheckChildSaved"/>Дэд эрхүүдийг дагаж сонгох эсэх </label>
                                </div>
                            </div>
                            <div id="saved-permission-list"></div>
                            <div id="saved-permission-list-adjacent"></div>
                        </div>        
                    </div>
                </div>
                <div class="row mt10">
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
        </div>
    </div>
</div>

<script type="text/javascript">
    var tmpRoleId=<?php echo (isset($this->roleId) && !is_null($this->roleId)) ? $this->roleId : ""; ?>,
            tmpUserId=null,
            metaDataId=<?php echo (isset($this->metaDataId) && !is_null($this->metaDataId)) ? $this->metaDataId : ""; ?>;
    jQuery(document).ready(function(){
        $.getScript("middleware/assets/js/um/md_um_role_permission.js", function(){
            $.getScript("middleware/assets/js/um/md_um_permission_criteria.js", function(){
                if(tmpRoleId !== ""){
                    MdUmRolePermission.init(metaDataId, tmpRoleId, tmpUserId);

                    if(jQuery().datepicker){
                        $('.date-picker').datepicker({
                            rtl: Core.isRTL(),
                            orientation: "left",
                            autoclose: true
                        });
                    }

                    MdUmPermissionCriteria.init(metaDataId, tmpRoleId, tmpUserId);
                }
            });
        });
    });

</script>