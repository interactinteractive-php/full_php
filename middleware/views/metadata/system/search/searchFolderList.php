<div class="row mb10">
    <div class="col-md-4"></div>
    <div class="col-md-7 text-center metaTopFilter ">
        <?php 
            $meta = new Mdmetadata();
            $getValues = $meta->metaSearchStandartType();            
            echo '<div class="d-inline-block mr15">';
            foreach ($getValues as $key => $val) {
                echo '<div class="form-check form-check-inline">'.
                        '<label class="form-check-label">'.
                            '<input type="radio"'.($key === 0 ? ' checked' : '').' value="'.$val['code'].'" onchange="searchType(this);" class="form-check-input mt3 search_type" name="unstyled-radio-left">'.
                            $val['name'].
                        '</label>'.
                    '</div>';
            }
            echo '</div>'; 
        ?>
            <label class="form-check-label">
                <input type="checkbox" data-off-color="info" onchange="searchType(this);" data-on-text="<?php echo $this->lang->line('META_00192') ?>" data-off-text="<?php echo $this->lang->line('META_00134') ?>" data-size="small" class="meta-search-form-check-input-switch" checked>
                <input type="hidden" id="search_type_condition" value="like">
            </label>
        <?php
            echo Form::text(array('name' => 'search_txt', 'id' => 'search_txt', 'class' => 'form-control form-control-sm d-inline', 'value'=>$this->searchValue, 'placeholder'=>'Бүгдээс хайх /Ctrl+Shift+X/', 'style'=>'width:230px')); 
            echo Form::text(array('name' => 'filter_txt', 'id' => 'filter_txt', 'class' => 'form-control form-control-sm d-inline', 'onkeyup'=>'searchFileType(this);', 'placeholder'=>'Эндээсээ шүүх','style'=>'width:145px')); 
        ?>
    </div>
</div>

<div class="row mb10">
    <div class="col-md-12">
        <div class="metadata-breadcrumb">
            <div class="d-flex">
                <div class="breadcrumb breadcrumb-caret">
                    <a href="javascript:;" onclick="metaDataDefault();" class="breadcrumb-item py-1"><i class="icon-home2 mr-2"></i> <?php echo $this->lang->line('metadata_home'); ?></a> 
                </div>
            </div>
        </div>
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
                    <a class="folder-link" href="javascript:;" onclick="historyBackList('<?php echo $this->rowId; ?>', '<?php echo $this->rowType; ?>', '<?php echo $this->params; ?>');">
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
            <li class="dir" id="<?php echo $folderRow['FOLDER_ID']; ?>">	
                <figure class="directory">
                    <a href="javascript:;" ondblclick="childRecordView('<?php echo $folderRow['FOLDER_ID']; ?>', 'folder', '<?php echo $this->params; ?>');" class="folder-link" title="<?php echo $folderRow['FOLDER_NAME']; ?>">
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
                            <h4 class="ellipsis"><?php echo $folderRow['FOLDER_NAME']; ?></h4>
                            <?php echo Mdmetadata_Model::getResultPath($folderRow['PARENT_FOLDER_ID'], '0', $folderRow['PARENT_FOLDER_ID']); ?>
                        </div>
                    </a>	
                    <div class="file-date"><?php echo Date::formatter($folderRow['CREATED_DATE'], 'Y/m/d H:i'); ?></div>
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

<script type="text/javascript">
$(function() {
    var typeValue = $.cookie('meta_search_type');
    var conditionValue = $.cookie('meta_search_condition');
    
    $('input.search_type').filter('[value="'+typeValue+'"]').attr('checked', true);
    if (conditionValue === 'like') {
        $('.meta-search-form-check-input-switch').prop('checked', true);
        $('#search_type_condition').val('like');
    } else {
        $('.meta-search-form-check-input-switch').prop('checked', false);
        $('#search_type_condition').val('equal');
    }        
    $('.meta-search-form-check-input-switch').bootstrapSwitch({
        onSwitchChange: function(e, state) { 
            if(state) {
                $('#search_type_condition').val('like');
            } else {
                $('#search_type_condition').val('equal');
            }
        } 
    });

    var $firstItem = $('ul.grid > li:not(.back):eq(0)');

    if ($firstItem.length) {
        $firstItem.find('> figure > a').focus();
        $firstItem.addClass('meta-selected');
    } 
    
    $('ul.grid > li.dir').on('click', function() {
        var $this = $(this);
        var $a = $this.find('> figure > a');
        
        $('ul.grid').find('li.ui-selected, li.meta-selected').removeClass('ui-selected meta-selected');
        
        $this.addClass('meta-selected');
        
        $a.focus();
    });
    
    $('ul.grid > li > figure > a').on('keydown', function(e) {
        
        var code = (e.keyCode ? e.keyCode : e.which);
        var $this = $(this);
        var $li = $this.closest('li');
        var isShiftKey = e.shiftKey;
        var isOpenContextMenu = false;

        if ($('body').find('.context-menu-root').length > 0 && $('body').find('.context-menu-root').is(':visible')) {
            isOpenContextMenu = true;
        }
        
        if (isOpenContextMenu == false) {
            
            if (code == 13) { /*enter*/
            
                if ($this.attr('ondblclick')) {
                    $this.dblclick();
                } else {
                    $li.dblclick();
                }

            } else if (code == 40) { /*down*/

                var $nextItem = $li.next('li');

                if ($nextItem.length) {
                    var $ul = $('ul.grid');
                    var offsetTop = $nextItem.offset().top;
                    var outerHeight = $nextItem.outerHeight();
                    var windowHeight = $(window).height();

                    if (offsetTop >= windowHeight || (windowHeight > offsetTop && (windowHeight - offsetTop) < outerHeight)) {
                        $('html, body').animate({scrollTop: offsetTop - windowHeight + outerHeight}, 'fast');
                    } else {
                        $('html, body').animate({scrollTop: 0}, 'fast');
                    }
                    
                    $nextItem.find('> figure > a').focus();

                    $ul.find('.meta-selected').removeClass('meta-selected');
                    $nextItem.addClass('meta-selected');
                }

            } else if (code == 38) { /*up*/

                var $prevItem = $li.prev('li');

                if ($prevItem.length) {
                    var $ul = $('ul.grid');
                    var offsetTop = $prevItem.offset().top - 60;
                    var scrollTop = $(window).scrollTop();
                    var positionTop = offsetTop - scrollTop;
                    
                    if (positionTop < 60) {
                        if (positionTop < 0) {
                            $('html, body').animate({scrollTop: scrollTop - (positionTop * -1)}, 'fast');
                        } else {
                            var outerHeight = $prevItem.outerHeight();
                            $('html, body').animate({scrollTop: scrollTop - outerHeight}, 'fast');
                        }
                    } else {
                        $('html, body').animate({scrollTop: scrollTop}, 'fast');
                    }
                    
                    $prevItem.find('> figure > a').focus();

                    $ul.find('.meta-selected').removeClass('meta-selected');
                    $prevItem.addClass('meta-selected');
                }

            } else if (isShiftKey && code == 69) { /*Shift+e Edit Folder*/
                
                editFormFolder($li.attr('id'), '', this);

            } else if (isShiftKey && code == 67) { /*Shift+c contextMenu Show*/
                
                $li.first().trigger(
                    $.Event('contextmenu', {pageX: $li.offset().left + 50, pageY: $li.offset().top + 50})
                );
                $li.trigger('contextmenu');
                
            }
            
        } else if (isShiftKey && code == 67) { /*Shift+c contextMenu Hide*/
            $('.context-menu-list').trigger('contextmenu:hide');
        }
        
    });
    
});
</script>