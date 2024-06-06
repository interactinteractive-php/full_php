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
            if ($this->docList) {
                foreach ($this->docList as $docRow) {
            ?>
            <li class="doc" id="<?php echo $docRow['ID']; ?>">	
                <figure class="directory">
                    <a href="javascript:;" class="folder-link" title="<?php echo $docRow['NAME']; ?>">
                        <div class="img-precontainer">
                            <div class="img-container directory"><span></span>
                                <img class="directory-img" src="assets/core/global/img/document/big/<?php echo $docRow['EXTENSION']; ?>.png"/>
                            </div>
                        </div>
                        <div class="img-precontainer-mini directory">
                            <div class="img-container-mini">
                                <span></span>
                                <img class="directory-img" src="assets/core/global/img/document/small/<?php echo $docRow['EXTENSION']; ?>.png"/>
                            </div>
                        </div>
                        <div class="box">
                            <h4 class="ellipsis"><?php echo $docRow['NAME']; ?></h4>
                            <?php echo Mddoc_Model::getDocFolderPath($docRow['ID']); ?>
                        </div>
                    </a>	
                    <div class="file-date"><?php echo Date::format('Y/m/d H:i', $docRow['CREATED_DATE']); ?></div>
                    <div class="file-user"><?php echo $docRow['CREATED_PERSON_NAME']; ?></div>
                </figure>
            </li>
            <?php
                }
            }
            ?>
        </ul>
    </div>    
</div>   