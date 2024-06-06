<div class="row pb10">
    <div class="col-md-12 ml15 mb10 remove-type-<?php echo $this->metaDataId; ?>">
        <div class="btn-group">
            <a href="javascript:;" class="btn green-meadow btn-sm" onclick="addTemplateFolder('<?php echo $this->metaDataId; ?>', '<?php echo $this->folderId; ?>');">
                <i class="icon-folder-plus2"></i> <?php echo $this->lang->line('add_folder'); ?>
            </a>
        </div>
        <div class="btn-group">
            <a href="javascript:;" class="btn bg-slate btn-sm" onclick="objectDataView_<?php echo $this->metaDataId; ?>();">
                <i class="icon-arrow-left8"></i> <?php echo $this->lang->line('backlist_btn'); ?>
            </a>
        </div>
    </div>
</div>
<div class="col-md-12" id="temp-meta-wrap-<?php echo $this->metaDataId; ?>">
    <ul class="grid cs-style-2 list-view0">
        <?php
        if ($this->isBack) {
        ?>
        <li class="back">
            <figure class="back-directory">
                <a class="folder-link" href="javascript:;" onclick="historyBackTemplateList('<?php echo $this->metaDataId; ?>', '<?php echo $this->folderId; ?>');">
                    <div class="img-precontainer">
                        <div class="img-container directory"><span></span>
                            <img class="directory-img" src="assets/core/global/img/meta/folder_back.png"/>
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
        <li class="dir dv-report-template-folder" id="<?php echo $folderRow['ID']; ?>" data-folder-id="<?php echo $this->folderId; ?>">	
            <figure class="directory">
                <a href="javascript:;" onclick="childDataViewTemplate('<?php echo $this->metaDataId; ?>', '<?php echo $folderRow['ID']; ?>');" class="folder-link" title="<?php echo $folderRow['NAME']; ?>">
                    <div class="img-precontainer">
                        <div class="img-container directory"><span></span>
                            <img class="directory-img" src="assets/core/global/img/meta/folder.png"/>
                        </div>
                    </div>
                    <div class="box">
                        <h4 class="ellipsis"><?php echo $folderRow['NAME']; ?></h4>
                    </div>
                </a>	
            </figure>
        </li>
        <?php
            }
        }
        if ($this->reportTemplateList) {
            foreach ($this->reportTemplateList as $dashboardRow) {
        ?>
        <li class="meta dv-report-template" id="<?php echo $dashboardRow['META_DATA_ID']; ?>" data-folder-id="<?php echo $this->folderId; ?>">	
            <figure class="directory">
                <a href="javascript:;" class="folder-link" title="<?php echo $dashboardRow['META_DATA_NAME']; ?>">
                    <div class="img-precontainer">
                        <div class="img-container directory"><span></span>
                          <img class="directory-img" src="assets/core/global/img/meta/file.png" height="90"/>
                        </div>
                    </div>
                    <div class="box">
                        <h4 class="ellipsis"><?php echo $dashboardRow['META_DATA_NAME']; ?></h4>
                    </div>
                </a>	
            </figure>
        </li>
        <?php
            }
        }
        ?>
    </ul>
</div>      

<script type="text/javascript">
$(function(){

    var isTool = '<?php echo $this->isExternalTool; ?>';
    var menusObj = {
        "edit": {name: plang.get('edit_btn'), icon: "edit"},
        "edittemplate": {name: 'Загвар засах', icon: "file-text-o"},
        "copy": {name: 'Хуулах', icon: "copy"},
        "preview": {name: 'Харах', icon: "file-text-o"},
        "delete": {name: 'Устгах', icon: "trash"}
    };

    if (isTool != '') {
        menusObj['editexternaltemplate'] = {name: 'Загвар засах /Designer Tool/', icon: "file-text-o"};
    }
    
    $.contextMenu({
        selector: '#temp-meta-wrap-<?php echo $this->metaDataId; ?> ul.grid li.dv-report-template-folder',
        callback: function(key, opt) {
            if (key === 'edit') {
                editTemplateFolder(opt.$trigger.attr('id'), opt.$trigger.attr('data-folder-id'), '<?php echo $this->metaDataId; ?>');
            } else if (key === 'delete') {
                deleteTemplateFolder(opt.$trigger.attr('id'), opt.$trigger.attr('data-folder-id'), '<?php echo $this->metaDataId; ?>', opt.$trigger);
            } 
        },
        items: {
            "edit": {name: plang.get('edit_btn'), icon: "edit"},
            "delete": {name: 'Устгах', icon: "trash"}
        }
    });
    
    $.contextMenu({
        selector: '#temp-meta-wrap-<?php echo $this->metaDataId; ?> ul.grid li.dv-report-template',
        callback: function(key, opt) {
            if (key === 'edit') {
                editDataViewTemplate(opt.$trigger.attr('id'), '<?php echo $this->metaDataId; ?>', opt.$trigger.attr('data-folder-id'));
            } else if (key === 'edittemplate') {
                editDataViewReportTemplate(opt.$trigger.attr('id'), '<?php echo $this->metaDataId; ?>', opt.$trigger.attr('data-folder-id'));
            } else if (key === 'editexternaltemplate') {
                iframeReportDesigner(opt.$trigger.attr('id'), '<?php echo $this->metaDataId; ?>');
            } else if (key === 'copy') {
                copyDataViewReportTemplate(opt.$trigger.attr('id'), '<?php echo $this->metaDataId; ?>', opt.$trigger.attr('data-folder-id'));
            } else if (key === 'preview') {
                previewDataViewReportTemplate(opt.$trigger.attr('id'));
            } else if (key === 'delete') {
                deleteDataViewTemplate(opt.$trigger.attr('id'), opt.$trigger);
            } 
        },
        items: menusObj
    });
    
});
</script>