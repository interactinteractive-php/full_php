<div class="gallery3-dv not-datagrid datagrid-view" id="gallery3-dv-<?php echo $this->dataViewId; ?>">
    <ul class="gallery row" data-ref-structure-id="<?php echo $this->refStructureId; ?>">
        <?php
        if ($this->isBack) {
        ?>
        <li class="gallery-item folder" onclick="explorerBackList_<?php echo $this->dataViewId; ?>('<?php echo $this->folderId; ?>');">
            <a href="javascript:;">
                <img class="img-fluid img-preview img-back" src="assets/core/global/img/meta/folder_back_transparent.png"/>
            </a>
        </li>            
        <?php
        }

        if ($this->recordList) {

            foreach ($this->recordList as $row) {

                if (isset($row['fileextension'])) {

                    $fileExtension = $row['fileextension'];

                    if ($fileExtension == 'png' || 
                            $fileExtension == 'gif' || 
                            $fileExtension == 'jpeg' || 
                            $fileExtension == 'pjpeg' || 
                            $fileExtension == 'jpg' || 
                            $fileExtension == 'x-png' || 
                            $fileExtension == 'bmp') {

                        $photoField = isset($row['physicalpath']) ? $row['physicalpath'] : '';

                    } else {
                        $photoField = isset($row[$this->photoField]) ? $row[$this->photoField] : '';
                    }

                } else {
                    $photoField = isset($row[$this->photoField]) ? $row[$this->photoField] : '';
                }

                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
        ?>
            <li data-full="<?php echo $photoField; ?>">
                <a href="javascript:;" class="selected-row-link" data-row-data="<?php echo $rowJson; ?>">
                    <div class="card mb10">
                        <center>
                            <img class="card-img-top img-fluid" src="<?php echo $photoField; ?>" data-default-image="<?php echo $this->defaultImage; ?>" onerror="onDataViewImgError(this);">
                        </center>
                        <div class="card-body border-top-lightgray pt10 pb10 pl15 pr15" data-row-data="<?php echo $rowJson; ?>" onclick="clickItem_<?php echo $this->dataViewId; ?>(this);">
                            <h5 class="card-title mb-0 font-weight-bold"><?php echo isset($row[$this->name1]) ? $row[$this->name1] : ''; ?></h5>
                            <p class="card-text text-black letter-spacing--03"><?php echo isset($row[$this->name2]) ? $row[$this->name2] : ''; ?></p>
                        </div>
                    </div>
                </a>
            </li>
        <?php
            }
        } else {
            echo html_tag('div', array('class' => 'alert alert-info'), 'No data!');
        }
        ?>
    </ul>

    <div class="lightbox">
        <figure></figure>
    </div>
</div>

<script type="text/javascript">
$(function() {
    
    if (!$("link[href='middleware/assets/css/gridlayout/gallery3.css?v=2']").length) {
        $("head").prepend('<link rel="stylesheet" type="text/css" href="middleware/assets/css/gridlayout/gallery3.css?v=2"/>');
    }
    
    var $galleryDv = $('.gallery3-dv'), $lightbox = $galleryDv.find('.lightbox'), $figure = $lightbox.find('figure');

    $galleryDv.find('ul.gallery .item img').on('click', function(){
        var $full = $(this).closest('div').attr('data-full');
        toggleLightbox($full);
    });

    function toggleLightbox(url) {
        if ($lightbox.is('.open')) {
            $lightbox.removeClass('open').fadeOut(200);
        } else {
            $figure.css('background-image', 'url(' + url + ')');
            $lightbox.addClass('open').fadeIn(200);
        }
    }

    $lightbox.on('click', toggleLightbox);

    $('#gallery3-dv-<?php echo $this->dataViewId; ?>').on('contextmenu', '.gallery-item', function(e){
        var $this = $(this);
        var $parent = $this.closest('.gallery3-dv');
        $parent.find('.selected-row').removeClass('selected-row');
        $this.addClass('selected-row');
    });

    var $parentAppTab = $('#app_tab_<?php echo $this->dataViewId; ?>');
    $parentAppTab.find('.gallery3-dv').parents('.main-dataview-container').addClass('dv-explorer-3-parent');
    $parentAppTab.find('.dv-explorer-3-parent .explorer-table > .explorer-table-row > .explorer-table-cell').css({height: ($('.page-sidebar-menu').attr('data-height') - 120)});
        
    $('#objectdatagrid-<?php echo $this->dataViewId; ?>').find('a[data-ismain="0"]').hide();
});
</script>

<style type="text/css">
.explorer-table-cell-sidebar.explorer-sidebar-<?php echo $this->dataViewId; ?> {
    display: none !important;
}
</style>