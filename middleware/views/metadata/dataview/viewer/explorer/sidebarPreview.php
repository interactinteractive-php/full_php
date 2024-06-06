<div class="card light bordered wfm-more-card pt0 mt0 mb10">
    <div class="card-body preview-<?php echo $this->dataViewId; ?>">
        <div class="row">
            <div class="col-md-12">
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
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        filePreview(<?php echo json_encode($this->selectedRow) ?>, $('.preview-<?php echo $this->dataViewId; ?>'));
    });
</script>