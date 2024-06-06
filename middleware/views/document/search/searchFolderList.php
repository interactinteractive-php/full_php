<div class="row mb10">
    <div class="col-md-5"></div>
    <div class="col-md-7">
        <div class="btn-bar">
            <?php echo Form::select(array('name' => 'doc_search_type', 'id' => 'doc_search_type', 'data' => (new Mddoc())->searchType(),'onchange'=>'searchDocType(this);','value'=>$this->searchType, 'class'=>'form-control form-control-sm  display-inline', 'op_value'=>'code', 'op_text'=>'name', 'text'=>'notext')); ?>
            <?php echo Form::text(array('name' => 'doc_search_txt', 'id' => 'doc_search_txt', 'class' => 'form-control form-control-sm d-inline', 'value'=>$this->searchValue, 'placeholder'=>$this->lang->line('search'), 'onkeydown'=>'if(event.keyCode==13) searchDocType(this);', 'style'=>'width:240px')); ?>
            <?php echo Form::text(array('name' => 'doc_filter_txt', 'id' => 'doc_filter_txt', 'class' => 'form-control form-control-sm  display-inline', 'onkeyup'=>'searchDocFileType(this);', 'placeholder'=>$this->lang->line('metadata_filter'))); ?>
        </div>
    </div>
</div>

<div class="row mb10">
    <div class="col-md-12">
        <ul class="page-breadcrumb breadcrumb bg-grey mb0">
            <li>
                <i class="fa fa-home"></i>
                <a href="javascript:;" onclick="docListDefault('');"><?php echo $this->lang->line('metadata_home'); ?></a> 
                <span class="fa fa-angle-right"></span>
            </li>
        </ul>
    </div>    
</div>    
    
<div class="row">
    <div class="col-md-12">
        <ul class="grid cs-style-2 list-view1" id="main-item-container">
            <?php
            if ($this->isBack) {
            ?>
            <li class="back">
                <figure class="back-directory">
                    <a class="folder-link" href="javascript:;" onclick="docHistoryBackList('<?php echo $this->rowId; ?>', '<?php echo $this->rowType; ?>', '<?php echo $this->params; ?>');">
                        <div class="img-precontainer">
                            <div class="img-container directory"><span></span>
                                <img class="directory-img" src="assets/core/global/img/meta/folder_back.png"/>
                            </div>
                        </div>
                        <div class="img-precontainer-mini directory">
                            <div class="img-container-mini">
                                <span></span>
                                <img class="directory-img" src="assets/core/global/img/meta/back-mini.png"/>
                            </div>
                        </div>
                        <div class="box no-effect">
                            <h4><?php echo $this->lang->line('back_btn'); ?></h4>
                        </div>
                    </a>
                </figure>
            </li>
            <?php
            } 
            if ($this->folderList) {
                foreach ($this->folderList as $folderRow) {
            ?>
            <li class="dir" id="<?php echo $folderRow['ID']; ?>">	
                <figure class="directory">
                    <a href="javascript:;" onclick="docChildRecordView('<?php echo $folderRow['ID']; ?>', 'folder', '<?php echo $this->params; ?>');" class="folder-link" title="<?php echo $folderRow['NAME']; ?>">
                        <div class="img-precontainer">
                            <div class="img-container directory"><span></span>
                                <img class="directory-img" src="assets/core/global/img/meta/folder.png"/>
                            </div>
                        </div>
                        <div class="img-precontainer-mini directory">
                            <div class="img-container-mini">
                                <span></span>
                                <img class="directory-img" src="assets/core/global/img/meta/folder-mini.png"/>
                            </div>
                        </div>
                        <div class="box">
                            <h4 class="ellipsis"><?php echo $folderRow['NAME']; ?></h4>
                            <?php echo Mddoc_Model::getResultPath($folderRow['PARENT_ID'], '0', $folderRow['PARENT_ID']); ?>
                        </div>
                    </a>	
                    <div class="file-date"><?php echo Date::format('Y/m/d H:i', $folderRow['CREATED_DATE']); ?></div>
                    <div class="file-user"><?php echo $folderRow['CREATED_PERSON_NAME']; ?></div>
                </figure>
            </li>
            <?php
                }
            }
            ?>
        </ul>
    </div>    
</div>   