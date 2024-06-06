<div class="explorer-toggler" onclick="$(this).closest('.explorer-table-cell-sidebar').addClass('d-none');">
    <span class="fa fa-chevron-right">&nbsp;</span> 
</div>

<div class="clearfix w-100"></div>

<div class="card light bordered wfm-more-card pt0 mt0 mb10">
    <div class="card-title tabbable-line">
        <ul class="nav nav-tabs float-left">
            <li class="nav-item">
                <a href="#explorer_more_<?php echo $this->dataViewId; ?>" class="nav-link active" data-toggle="tab">Дэлгэрэнгүй</a>
            </li>
            <?php
            if (isset($this->selectedRow['physicalpath']) && !empty($this->selectedRow['physicalpath'])) {
            ?>
            <li class="nav-item">
                <a href="#explorer_preview_<?php echo $this->dataViewId; ?>" data-toggle="tab" onclick="explorerFilePreview(this);" class="nav-link">Preview</a>
            </li>
            <?php
            }
            ?>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane active" id="explorer_more_<?php echo $this->dataViewId; ?>">
                
              <div class="sidebarDvList" id="sidebarDvList_<?php echo $this->dataViewId; ?>"></div>
              
                <table class="table table-hover table-light mb0 explorer-sidebar-table">
                    <tbody>							
                    <?php
                    if ($this->dataGridHeadData) {
                        foreach ($this->dataGridHeadData as $k => $v) {

                            if ($v['FIELD_PATH'] != 'id' && isset($this->selectedRow[$v['FIELD_PATH']])) {

                                $value = html_entity_decode($this->selectedRow[$v['FIELD_PATH']]);

                                if ($v['META_TYPE_CODE'] == 'file') {
                                    $value = '<img src="'.$this->selectedRow[$v['FIELD_PATH']].'" height="40">';
                                }

                                if ($v['FIELD_PATH'] == 'wfmstatusname' && isset($this->selectedRow['wfmstatusname']) && isset($this->selectedRow['wfmstatuscolor'])) {
                                    $value = '<span class="badge label-sm" style="background-color:'.$this->selectedRow['wfmstatuscolor'].'">'.$value.'</span>';
                                }
                    ?>
                        <tr>
                            <td class='sidebar-label-name' title='<?php echo $this->lang->line($v['LABEL_NAME']); ?>:'>
                                <?php echo $this->lang->line($v['LABEL_NAME']); ?>:
                            </td>
                            <td class='sidebar-value' title='<?php echo $value; ?>'>
                                <?php echo $value; ?>
                            </td>
                        </tr>
                    <?php
                            }
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <?php
            if (isset($this->selectedRow['physicalpath']) && !empty($this->selectedRow['physicalpath'])) {
            ?>
            <div class="tab-pane" id="explorer_preview_<?php echo $this->dataViewId; ?>">
                <?php
                $button = '';
                $physicalpath = $this->selectedRow['physicalpath'];
                $fileExtension = strtolower(substr($physicalpath, strrpos($physicalpath, '.') + 1));

                if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif')) === true) {

                    $button = '<a href="'.$physicalpath.'" class="btn blue btn-sm mb10 float-left fancybox-button" data-rel="fancybox-button"><i class="fa fa-search"></i> Томоор харах</a>';

                } elseif (in_array($fileExtension, array('pdf', 'xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx')) === true) {

                    $button = '<a href="javascript:;" class="btn blue btn-sm float-left mb10" onclick="dataViewFileViewer(this, \''.$this->selectedRow['id'].'\', \''.$fileExtension.'\', \''.$this->selectedRow['filename'].'\', \''.$physicalpath.'\');"><i class="fa fa-search"></i> Томоор харах</a>';

                }
                echo $button;
                echo '<a href="mdobject/downloadFile?file='.$this->selectedRow['physicalpath'].'" target="_blank" class="btn blue btn-sm float-right mb10"><i class="fa fa-download"></i> Татах</a>';
                ?>
                <div class="clearfix w-100"></div>
                <div class="explorer_dv_preview"></div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>

<?php
if ($this->workflow) {
?>
<div class="card light bordered pt0 wfm-card">
    <div class="card-title tabbable-line">
        <ul class="nav nav-tabs float-left">
            <li class="nav-item">
                <a href="#explorer_wfm_<?php echo $this->dataViewId; ?>" class="nav-link active" data-toggle="tab">Workflow</a>
            </li>
            <li class="nav-item">
                <a href="#explorer_ass_<?php echo $this->dataViewId; ?>" data-toggle="tab" class="nav-link">Assignment</a>
            </li>
            <li class="nav-item">
                <a href="#explorer_flw_<?php echo $this->dataViewId; ?>" data-toggle="tab" class="nav-link">Followers</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane active" id="explorer_wfm_<?php echo $this->dataViewId; ?>">
                <?php echo $this->workflow; ?>
            </div>
            <div class="tab-pane" id="explorer_ass_<?php echo $this->dataViewId; ?>">
                <?php echo $this->assignment; ?>
            </div>
            <div class="tab-pane" id="explorer_flw_<?php echo $this->dataViewId; ?>">
                Followers
            </div>
        </div>
    </div>
</div>
<?php
}
?>

<script type="text/javascript">
    var $sidebarDvList;
    $(function(){
        var sidebarDataviewList = <?php echo json_encode($this->sidebarDataviewList) ?>;
            selectedRow = <?php echo json_encode($this->selectedRow) ?>;
            $sidebarDvList = $('#sidebarDvList_<?php echo $this->dataViewId; ?>');

        $.each(sidebarDataviewList, function(key, value){
            if(typeof selectedRow[value.SRC_PARAM_PATH] !== "undefined"){
                var uriParams = 'dv[' + value.SRC_PARAM_PATH + ']=' + selectedRow[value.SRC_PARAM_PATH];
                $.ajax({
                    type: 'post',
                    url: 'mdobject/dataview/' + value.TRG_META_DATA_ID + '?' + uriParams,
                    dataType: 'html',
                    success: function(response){
                      renderSidebarDv(response, value);
                    }
                }).complete(function(){
                    
                });
            }
        });
    });

    function renderSidebarDv(response, value) {        
        $sidebarDvList.append('<div class="sidebar-dv-list"><h4 class="sidebar-dv-title">' + value.META_DATA_NAME + '</h4>' + response + '</div>');
        var css = document.createElement("style");
        css.type = "text/css";
        css.innerHTML = ".object-height-row2-minus-" + value.TRG_META_DATA_ID + ", .remove-type-" + value.TRG_META_DATA_ID + " { display: none; }";
        document.body.appendChild(css);
    }
</script>