<?php // var_dump($this->groups) ?>
<div class="row user-create-group">
    <div class="col-6 border-right-1 border-gray pr-4">
        <div class="mb-3">
            <button type="button" class="btn btn-sm btn-primary group-add"><i class="icon-folder-plus3 mr-2 font-size-18"></i> Бүлэг нэмэх</button>
        </div>
        <div class="group-add-box mb-3" style="display:none;">
            <form id="groupForm">
                <div class="form-group row d-flex align-items-center">
                    <label class="col-form-label col-lg-3 text-right">Бүлгийн нэр</label>
                    <div class="col-lg-9">
                        <input type="text" name="group_name" class="form-control">
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center">
                    <label class="col-form-label col-lg-3 text-right">Байрлал</label>
                    <div class="col-lg-9">
                        <select name="group_position" class="form-control form-control-sm dropdownInput select2 data-combo-set select2-offscreen">
                            <?php if(isset($this->groupOrderPosition) && $this->groupOrderPosition) {
                                foreach($this->groupOrderPosition as $key => $position) { ?>
                                        <option value="<?php echo $position['id'] ?>"><?php echo $position['name'] ?></option>
                            <?php    }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center">
                    <label class="col-form-label col-lg-3 text-right">Хэрэглэгч</label>
                    <div class="col-lg-9">
                        <div class="input-group">
                            <select id="param[userIds]" name="param[userIds][]" class="form-control form-control-sm dropdownInput select2 bp-field-with-popup-combo select2-offscreen" data-path="userIds" data-field-name="userIds" data-row-data="{&quot;META_DATA_ID&quot;:&quot;1565070936581248&quot;,&quot;ATTRIBUTE_ID_COLUMN&quot;:&quot;id&quot;,&quot;ATTRIBUTE_CODE_COLUMN&quot;:null,&quot;ATTRIBUTE_NAME_COLUMN&quot;:&quot;name&quot;,&quot;PARAM_REAL_PATH&quot;:&quot;userIds&quot;,&quot;PROCESS_META_DATA_ID&quot;:&quot;1567154435267&quot;,&quot;CHOOSE_TYPE&quot;:&quot;multicomma&quot;}" multiple="multiple" data-isclear="0" data-placeholder="- Хэрэглэгч -" tabindex="-1" style=""></select> 
                            <span class="input-group-append"> 
                                <button class="btn btn-sm btn-primary mr0" type="button" data-lookupid="1565070936581248" data-paramcode="userIds" data-processid="1567154435267" data-choosetype="multicomma" data-idfield="id" data-namefield="name" onclick="bpBasketDvWithPopupCombo(this);">..</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" onclick="addGroup()" class="btn btn-sm btn-primary"><i class="icon-checkmark3 mr-1 font-size-18"></i> Хадгалах</button>
                </div>
            </form>
        </div>
        <div id="editGroup" class="group-edit-box mb-3" style="">
            <form id="editGroupForm">
                
            </form>
        </div>
        <div id="groups" class="group-height-scroll">
        <?php if(isset($this->groups) && $this->groups) { 
            foreach($this->groups as $key=> $groups) {
                $rowJson = htmlentities(json_encode($groups), ENT_QUOTES, 'UTF-8'); 
                ?>
                <div class="box box<?php echo $groups['id'] ?>" onclick="members('<?php echo $groups['id'] ?>');">
                    <div class="mr-2">
                        <img src="assets/custom/img/org.png" class="rounded-circle" width="40" height="40">
                    </div>
                    <div class="d-flex flex-column">
                        <a href="javascript:void(0);" data-id="<?php echo $groups['id'] ?>" onclick="members('<?php echo $groups['id'] ?>');" data-rowdata="<?php echo $rowJson; ?>" class="text-blue media-title mb-0 line-height-normal word-break-all"><?php echo $groups['groupname'] ?></a>
                    </div>
                    <div class="ml-auto d-flex align-items-center">
                        <div class="action-btn" style="display:none;">
                            <a href="javascript:void(0);">
                                <i class="fa fa-cog mr-1" onclick="renderEditGroupForm(<?php echo $groups['id'] ?>)"></i>
                            </a>
                            <a href="javascript:void(0);" id="confirm" onclick="deleteGroup(<?php echo $groups['id'] ?>)">
                                <i class="fa fa-trash-o mr-2"></i>
                            </a>
                        </div>
                        <a href="javascript:void(0);">
                            <span class="text-gray font-size-12">(<?php echo $groups['membercount'] ?>)</span>
                        </a>
                    </div>
                </div>
           <?php  }
        } else {
            echo '<p>Бүлэг үүсгээгүй байна...</p>';
        }
        ?>
        </div>
    </div>
    <div class="col-6 pl-4">
        <div class="mb-3">
            <button type="button" class="btn btn-sm bg-teal-400 member-add"><i class="icon-user-plus mr-2 font-size-18"></i> Хэрэглэгч нэмэх</button>
        </div>
        <div class="member-add-box mb-3" style="display:none;">
            <form id="memberForm">
                <div class="form-group row d-flex align-items-center">
                    <label class="col-form-label col-lg-3 text-right">Хэрэглэгч</label>
                    <div class="col-lg-9">
                        <div class="input-group">
                            <select id="param[userIds]" name="param[userIds][]" class="form-control form-control-sm dropdownInput select2 bp-field-with-popup-combo select2-offscreen" data-path="userIds" data-field-name="userIds" data-row-data="{&quot;META_DATA_ID&quot;:&quot;1565070936581248&quot;,&quot;ATTRIBUTE_ID_COLUMN&quot;:&quot;id&quot;,&quot;ATTRIBUTE_CODE_COLUMN&quot;:null,&quot;ATTRIBUTE_NAME_COLUMN&quot;:&quot;name&quot;,&quot;PARAM_REAL_PATH&quot;:&quot;userIds&quot;,&quot;PROCESS_META_DATA_ID&quot;:&quot;1567154435267&quot;,&quot;CHOOSE_TYPE&quot;:&quot;multicomma&quot;}" multiple="multiple" data-isclear="0" data-placeholder="- Хэрэглэгч -" tabindex="-1" style=""></select> 
                            <span class="input-group-append"> 
                                <button class="btn btn-sm btn-primary mr0" type="button" data-lookupid="1565070936581248" data-paramcode="userIds" data-processid="1567154435267" data-choosetype="multicomma" data-idfield="id" data-namefield="name" onclick="bpBasketDvWithPopupCombo(this);">..</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" onclick="addMember()" class="btn btn-sm btn-primary"><i class="icon-checkmark3 mr-1 font-size-18"></i> Хадгалах</button>
                </div>
            </form>
        </div>
        <div class="member-body group-height-scroll">
            <p class="text-grey text-center">Бүлэг сонгоно уу...</p>
        </div>
    </div>
</div>
<script type="text/javascript">
    var $optionGroupIntra = <?php echo (isset($this->groupOrderPosition) && $this->groupOrderPosition) ? json_encode($this->groupOrderPosition) : ''; ?>;
    $(function () {
        
        Core.initAjax($("#groupForm"));
        Core.initAjax($("#memberForm"));
        Core.initAjax($("#editGroupForm"));
        
        $(".group-add").click(function(){
            $(".group-add-box").toggle();
            $(".group-edit-box").hide();
        });
        
        $(".member-add").click(function(){
            $(".member-add-box").toggle();
        });
       
    });
</script>

<style type="text/css">
    
    .group-height-scroll {
        height: calc(100vh - 255px);
        overflow: auto; 
        margin: 0;
    }
    
    .ui-widget[aria-describedby="dialog-dataview-selectable-1565070936581248"] ,
    .ui-widget[aria-describedby="dialog-confirm"] 
    
    {
        z-index: 1052 !important; 
    }
    
    .ui-widget-overlay[aria-describedby="dialog-dataview-selectable-1565070936581248"],
    .ui-widget-overlay[aria-describedby="dialog-confirm"]
    {
        z-index: 1051 !important; 
    }
    
    .group_active {
        background: #f0f0f0 !important;
        cursor: pointer !important;
    }
    
    .user-create-group .select2-container-multi .select2-choices .select2-search-field input
    {
        padding: 2px !important;
    }
    
    .user-create-group .select2-container-multi .select2-choices
    {
        /*min-height: 40px !important;*/
    }
</style>