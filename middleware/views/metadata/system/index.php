<?php $isAddMeta = Mdmeta::isAddMetaAccess(); ?>
<div class="col-md-12">
    <div class="bg-white">	
        <div class="card-header card-header-no-padding bg-white header-elements-inline">
            <div class="card-title"><?php echo $this->title; ?></div>
        </div>
        <div class="card-body form" id="mainRenderDiv">
            <div class="m-0 no-padding fileexplorer" id="renderMeta"></div>
            
            <div class="m-0 no-padding hide" id="viewFormMeta"></div>
            <div class="m-0 no-padding hide" id="editFormMeta"></div>
            
            <div class="m-0 no-padding hide" id="editFormGroup"></div>
            
            <div class="m-0 no-padding hide" id="editFormFolder"></div>
        </div>
    </div>   
</div>

<input type="hidden" id="metaSystemView" value="0"/>
<input type="hidden" id="singleMetaSystemView" value="2"/>

<script type="text/javascript">
var metaIdData = [];
var lastIndexCheckedMeta = null;

$(window).on('load', function() {
    if (window.location.hash !== '') {
        var parsedHash = queryString.parse(location.hash);
        if (typeof parsedHash.objectType !== undefined && typeof parsedHash.objectId !== undefined && parsedHash.objectType !== '' && parsedHash.objectId !== '') {
            var folderId = parsedHash.objectId;
            childRecordView(folderId, parsedHash.objectType, '<?php echo $this->params; ?>');
        } else {
            metaDataDefault('<?php echo $this->params; ?>');
        }
    } else {
        metaDataDefault('<?php echo $this->params; ?>');
    }
});  

$(function() {

    var $body = $(document.body);
    
    $body.on('keydown', '#search_txt, #filter_txt', function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        
        if (code == 13) { 
            
            searchType(this);
            
        } else if (code == 40) {
            
            var $ul = $('ul.grid');
            var $firstItem = $ul.find('> li:eq(0) a');
            
            if ($firstItem.length) {
                
                var $li = $firstItem.closest('li');
                
                $firstItem.focus();
                
                $('html, body').animate({scrollTop: 0}, 'fast');
                
                $ul.find('.meta-selected').removeClass('meta-selected');
                $li.find('input').prop('checked', true);
                $li.addClass('meta-selected');
                
                metaIdsSelectionHelper();
            }
        }
    });
    
    $body.on('dblclick', 'ul.grid > li.dataview, ul.grid > li.tablestructure', function() {
        var metaDataId = $(this).attr('data-id');
        window.open('mdobject/dataview/' + metaDataId, '_blank');
    });
    
    $body.on('dblclick', 'ul.grid > li.statement', function() {
        var metaDataId = $(this).attr('data-id');
        window.open('mdstatement/index/' + metaDataId, '_blank');
    });
    
    $body.on('dblclick', 'ul.grid > li.package', function() {
        var metaDataId = $(this).attr('data-id');
        window.open('mdobject/package/' + metaDataId, '_blank');
    });
    
    $body.on('dblclick', 'ul.grid > li.workspace', function() {
        var metaDataId = $(this).attr('data-id');
        window.open('mdworkspace/index/' + metaDataId, '_blank');
    });
    
    $body.on('dblclick', 'ul.grid > li.widget', function() {
        var metaDataId = $(this).attr('data-id');
        window.open('mdwidget/index/' + metaDataId, '_blank');
    });
    
    $body.on('dblclick', 'ul.grid > li.layout', function() {
        var metaDataId = $(this).attr('data-id');
        window.open('mdlayoutrender/index/' + metaDataId, '_blank');
    });
     
    $body.on('click', '.addMetaFile', function(){    
        $('.meta_files').append(
            '<tr>'+
                '<td style="width: 210px"><input type="file" name="meta_file[]" class="col-md-12" onchange="hasFileExtension(this);"></td>'+
                '<td><input type="text" name="meta_file_name[]" class="form-control col-md-12" placeholder="'+plang.get('description')+'"/></td>'+
                '<td>'+
                    '<a href="javascript:;" class="btn btn-xs btn-danger" onclick="removeMetaFile(this);"><i class="icon-cross2 font-size-12"></i></a>' + 
                '</td>'+
            '</tr>');
    }); 
    
    $body.on('click', 'ul.grid > li.dir > figure > a.folder-link', function() {
        var $this = $(this), $li = $this.closest('li'), $mainMetaWrap = $('#main-meta-wrap');
        
        $mainMetaWrap.find('li.ui-selected, li.meta-selected').removeClass('ui-selected meta-selected');
        $mainMetaWrap.find('input:checked').prop('checked', false);
        
        $li.addClass('meta-selected');
        
        $this.focus();
    });
    
    $body.on('click', 'ul.grid > li.meta > figure > a.folder-link', function(e) {
        var $this = $(this), $li = $this.closest('li'), $mainMetaWrap = $('#main-meta-wrap'), 
            thisIndex = $mainMetaWrap.find('li.meta').index($li);
        
        if (lastIndexCheckedMeta == null) {
            lastIndexCheckedMeta = thisIndex;
        }
        
        $mainMetaWrap.find('li.dir.ui-selected, li.dir.meta-selected').removeClass('ui-selected meta-selected');
        
        if (e.ctrlKey == false) {
            $mainMetaWrap.find('li.ui-selected, li.meta-selected').removeClass('ui-selected meta-selected');
            $mainMetaWrap.find('input:checked').prop('checked', false);
        }
        
        $li.addClass('ui-selected');
        $li.find('input').prop('checked', true);
        
        if (e.shiftKey) {
            var start = thisIndex;
            var end = lastIndexCheckedMeta;
            var $selection = $mainMetaWrap.find('li.meta').slice(Math.min(start, end), Math.max(start, end) + 1);
            
            $selection.addClass('ui-selected');
            $selection.find('input').prop('checked', true);
        }
        
        lastIndexCheckedMeta = thisIndex;
        
        $this.focus();
        metaIdsSelectionHelper();
    });
    
    $body.on('click', 'li.meta input.notuniform', function() {
        var $this = $(this), $parent = $this.closest('li.meta'), 
            $mainMetaWrap = $('#main-meta-wrap'), thisIndex = $mainMetaWrap.find('li.meta').index($parent);
        
        lastIndexCheckedMeta = thisIndex;
        
        if ($this.is(':checked')) {
            $parent.addClass('ui-selected');
        } else {
            $parent.removeClass('ui-selected');
        }
        
        $parent.find('a').focus();
        metaIdsSelectionHelper();
    });
    
    $body.on('keydown', 'ul.grid > li > figure > a', function(e) {

        var code = (e.keyCode ? e.keyCode : e.which);
        var $this = $(this);
        var $li = $this.closest('li');
        var isMeta = $li.hasClass('meta');
        var isShiftKey = e.shiftKey;
        var isAltKey = e.altKey;
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

            } else if (code == 39) { /*right*/

                var $ul = $('ul.grid');
                var $nextItem = $li.next('li');

                if ($nextItem.length) {

                    $nextItem.find('a').focus();

                    if (!isMeta || !isShiftKey) {
                        $ul.find('input').prop('checked', false);
                        $ul.find('.meta-selected').removeClass('meta-selected');
                    }

                    $nextItem.find('input').prop('checked', true);
                    $nextItem.addClass('meta-selected');
                }

            } else if (code == 37) { /*left*/

                var $ul = $('ul.grid');
                var $prevItem = $li.prev('li');

                if ($prevItem.length) {
                    $prevItem.find('a').focus();

                    if (!isMeta || !isShiftKey) {
                        $ul.find('input').prop('checked', false);
                        $ul.find('.meta-selected').removeClass('meta-selected');
                    }

                    $prevItem.find('input').prop('checked', true);
                    $prevItem.addClass('meta-selected');
                }

            } else if (code == 40) { /*down*/

                var $ul = $('ul.grid');
                var currentIndex = $li.index();
                var blocksPerRow = 0;
                var offsetTop = $('ul.grid > li:eq(0)').offset().top;

                $ul.find('> li').each(function(i) {
                    if (offsetTop == $(this).offset().top) {
                        blocksPerRow++;
                    }
                    if (i == 20) {
                        return false;
                    }
                });

                var newIndex = currentIndex + blocksPerRow;       
                var $nextItem = $ul.find('> li:eq('+newIndex+')');

                if ($nextItem.length) {
                    
                    var offsetTop = $nextItem.offset().top;
                    var outerHeight = $nextItem.outerHeight();
                    var windowHeight = $(window).height();
                    
                    if (offsetTop >= windowHeight || (windowHeight > offsetTop && (windowHeight - offsetTop) < outerHeight)) {
                        $('html, body').animate({scrollTop: offsetTop - windowHeight + outerHeight}, 'fast');
                    } else {
                        $('html, body').animate({scrollTop: 0}, 'fast');
                    }
                    
                    $nextItem.find('a').focus();
                    
                    if (!isMeta || !isShiftKey) {
                        $ul.find('input').prop('checked', false);
                        $ul.find('.meta-selected').removeClass('meta-selected');
                    }

                    $nextItem.find('input').prop('checked', true);
                    $nextItem.addClass('meta-selected');
                }

            } else if (code == 38) { /*up*/

                var $ul = $('ul.grid');
                var currentIndex = $li.index();
                var blocksPerRow = 0;
                var offsetTop = $('ul.grid > li:eq(0)').offset().top;

                $ul.find('> li').each(function(i) {
                    if (offsetTop == $(this).offset().top) {
                        blocksPerRow++;
                    }
                    if (i == 20) {
                        return false;
                    }
                });

                var newIndex = currentIndex - blocksPerRow; 
                
                if (newIndex < 0) {
                    $('html, body').animate({scrollTop: 0}, 'fast');
                    return;
                }
                
                var $prevItem = $ul.find('> li:eq('+newIndex+')');

                if ($prevItem.length) {
                    
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
                    
                    $prevItem.find('a').focus();

                    if (!isMeta || !isShiftKey) {
                        $ul.find('input').prop('checked', false);
                        $ul.find('.meta-selected').removeClass('meta-selected');
                    }

                    $prevItem.find('input').prop('checked', true);
                    $prevItem.addClass('meta-selected');
                }

            } else if (isShiftKey && code == 69) { /*Shift+e Edit Folder Meta*/
                
                if ($li.hasClass('dir')) {
                    var folderId = '';
                    if (window.location.hash !== '') {
                        var parsedHash = queryString.parse(location.hash);
                        if (typeof parsedHash.objectType !== undefined && typeof parsedHash.objectId !== undefined && parsedHash.objectType !== '' && parsedHash.objectId !== '') {
                            folderId = parsedHash.objectId;
                        } 
                    }
                    editFormFolder($li.attr('id'), folderId, this);
                } else if (isMeta) {
                    editFormMeta($li.attr('id'), $li.attr('data-folder-id'), this);
                }

            } else if (isShiftKey && code == 70 && $li.hasClass('process')) { /*Shift+f Full Expression*/

                bpFullExpressionCP($li.attr('id'));

            }
            <?php
            if ($isAddMeta) {
            ?>
            else if (isShiftKey && code == 73 && ($li.hasClass('dataview') || $li.hasClass('tablestructure'))) { /*Shift+i Internal Process*/

                internalProcess($li.attr('id'), $li.attr('data-folder-id'));

            }
            <?php
            }
            ?>
            else if (isShiftKey && code == 71) { /*Shift+g Query editor || Process input params*/
                
                if ($li.hasClass('dataview')) {
                    dataViewQueryEditor($li.attr('id'));
                } else if ($li.hasClass('process')) {
                    bpInputParams($li.attr('id'));
                }

            } else if (isShiftKey && code == 67) { /*Shift+c contextMenu Show*/
                
                $li.first().trigger(
                    $.Event('contextmenu', {pageX: $li.offset().left + 50, pageY: $li.offset().top + 50})
                );
                $li.trigger('contextmenu');
                
            } 
            <?php
            if ($isAddMeta) {
            ?>
            else if (isShiftKey && code == 78) { /*Shift+n add folder*/
                
                $('#renderMeta').find('a[onclick*="addFolder("]').click();
                
            } else if (isShiftKey && code == 77) { /*Shift+m add meta*/
                
                $('#renderMeta').find('a[onclick*="addMetaBySystem("]').click();
                
            } else if (isShiftKey && code == 46) { /*Shift+delete delete meta*/
                
                if (isMeta) {
                    metaDataDelete($li.attr('id'));
                } else {
                    deleteFolder($li.attr('id'));
                }
                
            } else if (e.ctrlKey && code == 67 && metaIdData.length) { /*Ctrl+c copy*/
                
                var $copyElement = $('<input>');
                $('body').append($copyElement);
                $copyElement.val('metaCopyPf0e41gT015p-' + metaIdData.join(',')).select();
                document.execCommand('copy');
                $copyElement.remove();

                $this.focus();   
            } 
            <?php
            }
            ?>
            else if (code == 8) { /*backspace*/
                
                var folderId = '';
                if (window.location.hash !== '') {
                    var parsedHash = queryString.parse(location.hash);
                    if (typeof parsedHash.objectType !== undefined && typeof parsedHash.objectId !== undefined && parsedHash.objectType !== '' && parsedHash.objectId !== '') {
                        folderId = parsedHash.objectId;
                    } 
                }
                    
                historyBackList(folderId, 'folder', '');
                
            } else if (isAltKey && code == 83) {
            
                var event = $.Event('keydown');
                event.altKey = true;
                event.which = 83;
                $(document).trigger(event);
            }
            
            metaIdsSelectionHelper();
            
        } else if (isShiftKey && code == 67) { /*Shift+c contextMenu Hide*/
            $('.context-menu-list').trigger('contextmenu:hide');
        } 
    });
    <?php
    if ($isAddMeta) {
    ?>
    $(document).bind('keydown', 'Shift+n', function() {
        var $renderMeta = $('#renderMeta');
        if ($renderMeta.is(':visible')) {
            $renderMeta.find('a[onclick*="addFolder("]').click();
        }
    });
    $(document).bind('keydown', 'Shift+m', function() {
        var $renderMeta = $('#renderMeta');
        
        if ($renderMeta.is(':visible')) {
            $renderMeta.find('a[onclick*="addMetaBySystem("]').click();
        }
    });
    <?php
    }
    ?>
    $(document).bind('keydown', 'Ctrl+q', function(e){
        if ($('body').find('button.meta-btn-back').length > 0 && $('body').find('button.meta-btn-back').is(':visible')) {
            var $buttonElement = $('body').find('button.meta-btn-back:visible:last');
            if (!$buttonElement.is(':disabled')) {
                $buttonElement.click();
            }
        } else if ($('body').find('.bp-btn-back').length > 0 && $('body').find('.bp-btn-back').is(':visible')) {
            var $buttonElement = $('body').find('.bp-btn-back:visible:last');
            if (!$buttonElement.is(':disabled')) {
                $buttonElement.click();
            }
        }
        e.preventDefault();
        return false;
    });
    $(document.body).on('keydown', 'input, select, textarea, a, button', 'Ctrl+q', function(e){
        if ($('body').find('button.meta-btn-back').length > 0 && $('body').find('button.meta-btn-back').is(':visible')) {
            var $buttonElement = $('body').find('button.meta-btn-back:visible:last');
            if (!$buttonElement.is(':disabled')) {
                $buttonElement.click();
            }
        } else if ($('body').find('.bp-btn-back').length > 0 && $('body').find('.bp-btn-back').is(':visible')) {
            var $buttonElement = $('body').find('.bp-btn-back:visible:last');
            if (!$buttonElement.is(':disabled')) {
                $buttonElement.click();
            }
        }
        e.preventDefault();
        return false;
    });
    $(document.body).on('click', '#main-meta-wrap ul.grid div.file-code', function() {
        var sel, range;
        var el = $(this)[0];
        if (window.getSelection && document.createRange) { 
            sel = window.getSelection();
            if (sel.toString() == '') { 
                window.setTimeout(function(){
                    range = document.createRange(); 
                    range.selectNodeContents(el); 
                    sel.removeAllRanges(); 
                    sel.addRange(range);
                },1);
            }
        } else if (document.selection) { 
            sel = document.selection.createRange();
            if (sel.text == '') { 
                range = document.body.createTextRange();
                range.moveToElementText(el);
                range.select();
            }
        }
    });
    
    <?php
    if ($isAddMeta) {
    ?>
    $(document.body).off('paste').on('paste', function(e) { /*Ctrl+v paste*/

        var $focusedElem = $(document.activeElement);
        if (
            (
                $focusedElem.length 
                && (
                    $focusedElem.prop('tagName') == 'INPUT' 
                    || $focusedElem.prop('tagName') == 'TEXTAREA' 
                    || $focusedElem.prop('tagName') == 'SELECT' 
                )
            ) 
            || $('.ui-dialog:visible').length
            ) {
            return;
        }
                        
        var source;

        if (window.clipboardData !== undefined) {
            source = window.clipboardData;
        } else {
            source = e.originalEvent.clipboardData;
        }
        var data = source.getData('Text');
        var folderId = '';
        
        if (window.location.hash !== '') {
            var parsedHash = queryString.parse(location.hash);
            if (typeof parsedHash.objectType !== undefined && typeof parsedHash.objectId !== undefined && parsedHash.objectType !== '' && parsedHash.objectId !== '') {
                folderId = parsedHash.objectId;
            } 
        }
        
        clipboardMetaPaste(folderId, data);
    });
    <?php
    }
    ?>
    $(window).scroll(function () {
        
        var $renderMeta = $('#renderMeta');
        
        if ($renderMeta.is(':visible') && $renderMeta.find('li.meta').length > 99) {
            
            var scrollTop = parseInt($(window).scrollTop());
            var heightMinus = $(document).height() - $(window).height();
            var $loadMore = $('.load-more');
            var lastPage = Number($loadMore.attr('data-last-page'));

            if ((scrollTop == heightMinus || (heightMinus - scrollTop) <= 10) && lastPage != 0) {

                lastPage = lastPage + 1;
                var postData = {page: lastPage};

                if (window.location.hash !== '') {
                    var parsedHash = queryString.parse(location.hash);
                    if (typeof parsedHash.objectType !== undefined && typeof parsedHash.objectId !== undefined && parsedHash.objectType !== '' && parsedHash.objectId !== '') {
                        postData.folderId = parsedHash.objectId;
                    } 
                }

                $loadMore.attr('data-last-page', lastPage);

                $.ajax({
                    type: 'post', 
                    url: 'mdmetadata/metaRender', 
                    data: postData, 
                    beforeSend: function () {
                        $loadMore.show();
                    },
                    success: function (html) {
                        if (html) {
                            var $container = $('#main-item-container');
                            $container.append(html).promise().done(function() {
                                var t = $('#metaSystemView').val();
                                if (t >= 1) {
                                    fix_colums(0, t);
                                }
                                folderMetaCountHelper($container);
                            });
                        } else {
                            $loadMore.attr('data-last-page', 0);
                        }
                    }
                }).done(function() {
                    $loadMore.hide();
                });
            }
        }
    });

    $(document).bind('keydown', 'Ctrl+Shift+X', function(e){
        $('#search_txt').select().focus();
        e.preventDefault();
        return false;
    });
    $(document.body).on('keydown', 'input, select, textarea, a, button', 'Ctrl+Shift+X', function(e){
        $('#search_txt').select().focus();
        e.preventDefault();
        return false;
    });        
});   
function folderMetaCountHelper($parent) {
    var $mainMetaWrap = (typeof $parent !== 'undefined') ? $parent : $('#main-meta-wrap');
    $('#pfm-folder-count').text($mainMetaWrap.find('li.dir').length);
    $('#pfm-meta-count').text($mainMetaWrap.find('li.meta').length);
}
function fix_colums(e, t) {
    if (t > 0) {
        if (1 == t || 2 == t) { 
            $("#main-meta-wrap ul.grid li, #main-meta-wrap ul.grid figure").css("width", "100%");
        } else {
            var a = $("#mainRenderDiv").width() + e - 20;
            var tt = Math.floor(a / 3);
            $("#main-meta-wrap ul.grid li, #main-meta-wrap ul.grid figure").css("width", tt);
        }
    }
}
function metaIdsSelectionHelper() {
    metaIdData = [];
    var $wrap = $('#main-meta-wrap');
    $wrap.find('.meta.ui-selected, .meta.meta-selected').each(function() {
        metaIdData.push($(this).attr('id'));
    });
    
    var metaIdDataLength = metaIdData.length;
    
    if (metaIdDataLength) {
        $('#pfm-selection-count').text(metaIdDataLength + ' сонгогдсон');
    } else {
        $('#pfm-selection-count').text('');
    }
}
function setMinHeightPfMetaGrid() {
    var $ul = $('ul.grid');
    $ul.css({'min-height': $(window).height() - 238});
}
</script>