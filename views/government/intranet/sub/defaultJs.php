<script type="text/javascript">

    var panelDv_<?php echo $this->uniqId; ?> = $('body').find('.intranet-<?php echo $this->uniqId ?>');
    var firstList_<?php echo $this->uniqId; ?> = panelDv_<?php echo $this->uniqId; ?>.find('ul[data-part="dv-twocol-first-list"]');
    var appendList_<?php echo $this->uniqId; ?> = panelDv_<?php echo $this->uniqId; ?>.find('.all-content-<?php echo $this->uniqId ?>');
    var unreadappendList_<?php echo $this->uniqId; ?> = panelDv_<?php echo $this->uniqId; ?>.find('.all-content-<?php echo $this->uniqId ?>.unread');
    var firstcontentclicked = 0;
    var childIndex_<?php echo $this->uniqId; ?> = -1;
    var pagination<?php echo $this->uniqId ?> = 1;
    var $totalcount_<?php echo $this->uniqId ?> = 0;
    var subQueryHeight_<?php echo $this->uniqId; ?> = <?php echo (isset($this->row['subQuery']) && $this->row['subQuery']) ? 20 : 0; ?>;
    var $postContentTypeId = '<?php echo(isset($this->postParams['id']) && isset($this->postParams['posttypeid'])) ? $this->postParams['posttypeid'] : ''; ?>';
    var $postContentId = '<?php echo (isset($this->postParams['id']) && $this->postParams['id']) ? $this->postParams['id'] : ''; ?>';
    

    $('body').on('click', '.printContent_<?php echo $this->uniqId ?>', function () {
        console.log(dataCSS<?php echo $this->uniqId ?>);
        $("div.fill_<?php echo $this->uniqId ?>").printThis({
            debug: false,             
            importCSS: true,
            importStyle: true, 
            printContainer: false,      
            dataCSS: typeof dataCSS<?php echo $this->uniqId ?> !== 'undefined' ? dataCSS<?php echo $this->uniqId ?> : '',
            removeInline: false
        });
    });

    $('body').on('click', 'a[href="#bottom-justified-tab2<?php echo $this->uniqId ?>"]', function () {
        var value = $("#search").val();
        var $this = firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected');
        var $rowData = $this.data('rowdata');
        var $thisc = $(this);

        if (typeof $rowData !== 'object') {
            $rowData = JSON.parse($rowData);
        }
        
        /* if (typeof $thisc.attr('unreadcalled') === 'undefined') { */
            $.ajax({
                url: 'government/getsecondContent',
                type: 'post',
                data: {
                    uniqId: '<?php echo $this->uniqId ?>',
                    id: $this.hasClass('v2') ? '' : $rowData['id'],
                    categoryId: $rowData['categoryid'],
                    mainRow: $rowData,
                    unread: '1'
                },
                dataType: 'JSON',
                beforeSend: function () {
                    unreadappendList_<?php echo $this->uniqId; ?>.empty();
                    blockContent_<?php echo $this->uniqId ?>('.all-content-<?php echo $this->uniqId ?>.unread');
                    blockContent_<?php echo $this->uniqId ?>('#bottom-justified-tab2<?php echo $this->uniqId ?>');
                },
                success: function (data) {
                    unreadappendList_<?php echo $this->uniqId; ?>.empty().append(data.Html).promise().done(function () {
                        if (!firstcontentclicked && $('body').find('.all-content-<?php echo $this->uniqId ?>.unread li:not(".unread"):eq(0)').length > 0) {
                            firstcontentclicked = 1;
                            $('body').find('.all-content-<?php echo $this->uniqId ?>.unread li:not(".unread"):eq(0) > a').trigger('click');
                        }
                    });
                    $thisc.attr('unreadcalled', '1');
                    Core.unblockUI('.all-content-<?php echo $this->uniqId ?>.unread');
                    Core.unblockUI('#bottom-justified-tab2<?php echo $this->uniqId ?>');
                }
            });
        /*}  else {
            buildOneColSecondPart<?php echo $this->uniqId ?>('<?php echo $this->uniqId; ?>', $this.data('id'), $this);
        } */
    });
    
    panelDv_<?php echo $this->uniqId; ?>.on('change', '.filter-date input[data-path="filterStartDate"]', function () {
        var $this = firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected');
        var $rowData = $this.data('rowdata');
        var $thisc = $(this);

        if (typeof $rowData !== 'object') {
            $rowData = JSON.parse($rowData);
        }
        
        $.ajax({
            url: 'government/getsecondContent',
            type: 'post',
            async: true,
            data: {
                uniqId: '<?php echo $this->uniqId ?>',
                id: $this.hasClass('v2') ? '' : $rowData['id'],
                categoryId: $rowData['categoryid'],
                mainRow: $rowData,
                filterStartDate: panelDv_<?php echo $this->uniqId ?>.find('.filter-date input[data-path="filterStartDate"]').val(),
                filterEndDate: panelDv_<?php echo $this->uniqId ?>.find('.filter-date input[data-path="filterEndDate"]').val(),
            },
            dataType: 'JSON',
            beforeSend: function () {
                unreadappendList_<?php echo $this->uniqId; ?>.empty();
                appendList_<?php echo $this->uniqId; ?>.empty();

                blockContent_<?php echo $this->uniqId ?>('.all-content-<?php echo $this->uniqId ?>.unread');
                blockContent_<?php echo $this->uniqId ?>('#bottom-justified-tab2<?php echo $this->uniqId ?>');
            },
            success: function (data) {
                appendList_<?php echo $this->uniqId; ?>.empty().append(data.Html).promise().done(function () {
                    if (!firstcontentclicked && $('body').find('.all-content-<?php echo $this->uniqId ?>.unread li:not(".unread"):eq(0)').length > 0) {
                        firstcontentclicked = 1;
                        $('body').find('.all-content-<?php echo $this->uniqId ?>.unread li:not(".unread"):eq(0) > a').trigger('click');
                    }
                    
                    $.ajax({
                        url: 'government/getsecondContent',
                        type: 'post',
                        async: true,
                        data: {
                            uniqId: '<?php echo $this->uniqId ?>',
                            id: $this.hasClass('v2') ? '' : $rowData['id'],
                            categoryId: $rowData['categoryid'],
                            mainRow: $rowData,
                            unread: '1',
                            filterStartDate: panelDv_<?php echo $this->uniqId ?>.find('.filter-date input[data-path="filterStartDate"]').val(),
                            filterEndDate: panelDv_<?php echo $this->uniqId ?>.find('.filter-date input[data-path="filterEndDate"]').val(),
                        },
                        dataType: 'JSON',
                        beforeSend: function () {
                            unreadappendList_<?php echo $this->uniqId; ?>.empty();
                            blockContent_<?php echo $this->uniqId ?>('.all-content-<?php echo $this->uniqId ?>.unread');
                            blockContent_<?php echo $this->uniqId ?>('#bottom-justified-tab2<?php echo $this->uniqId ?>');
                        },
                        success: function (data) {
                            unreadappendList_<?php echo $this->uniqId; ?>.empty().append(data.Html).promise().done(function () {
                                if (!firstcontentclicked && $('body').find('.all-content-<?php echo $this->uniqId ?>.unread li:not(".unread"):eq(0)').length > 0) {
                                    firstcontentclicked = 1;
                                    $('body').find('.all-content-<?php echo $this->uniqId ?>.unread li:not(".unread"):eq(0) > a').trigger('click');
                                }
                            });
                            $thisc.attr('unreadcalled', '1');
                            Core.unblockUI('.all-content-<?php echo $this->uniqId ?>.unread');
                            Core.unblockUI('#bottom-justified-tab2<?php echo $this->uniqId ?>');
                        }
                    });
                });
                $thisc.attr('unreadcalled', '1');
                Core.unblockUI('.all-content-<?php echo $this->uniqId ?>.unread');
                Core.unblockUI('#bottom-justified-tab2<?php echo $this->uniqId ?>');
            }
        });
    });

    jQuery(document).ready(function () {
        
        $("head").append('<link rel="stylesheet" type="text/css" href="'+ URL_APP +'middleware/assets/css/intranet/style.v'+ getUniqueId(1) +'.css"/>');
        $.getScript(URL_APP + 'assets/custom/addon/admin/pages/scripts/app.js', function() {});
        $.getScript(URL_APP + 'assets/custom/addon/plugins/pdfobject/pdfobject.min.js', function () {});
        if (!$("link[href='assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css']").length) {
            $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
        }
        
        if (typeof isGovAddonScript === 'undefined') {
            $.getScript(URL_APP + 'assets/custom/gov/script.js');
        }

        Core.initFancybox($(".galleryfancy"));

        dvOneFixHeight_<?php echo $this->uniqId; ?> = $(window).height() - firstList_<?php echo $this->uniqId; ?>.offset().top - 16;
        dvOneFirstListHeight_<?php echo $this->uniqId; ?> = dvOneFixHeight_<?php echo $this->uniqId; ?> - subQueryHeight_<?php echo $this->uniqId; ?>;

        panelDv_<?php echo $this->uniqId; ?>.find('.dv-onecol-first-sidebar').css({'overflow': 'auto', 'height': dvOneFixHeight_<?php echo $this->uniqId; ?> + 70});
        firstList_<?php echo $this->uniqId; ?>.css({'display': 'block', 'overflow': 'auto', 'max-height': dvOneFirstListHeight_<?php echo $this->uniqId; ?>});
        
        if ($postContentTypeId && $postContentId) {
            firstList_<?php echo $this->uniqId; ?>.find('li[id="menu_<?php echo $this->uniqId ?>_<?php echo $this->postParams['posttypeid']; ?>"] > a').trigger('click');
        } else {
            firstList_<?php echo $this->uniqId; ?>.find('li:eq(0) > a').trigger('click');
        }
        
        $('#search').keypress(function (event) {
            
            var keycode = (event.keyCode ? event.keyCode : event.which);
            
            if (keycode == '13') {
                search<?php echo $this->uniqId ?>();
            }
            
        });
        
    });

    $('.cardlist-<?php echo $this->uniqId ?> a.mail-search').click(function() {
    
        var $mainSelector = $('.cardlist-<?php echo $this->uniqId ?> .text-one-line');
        
        if ($mainSelector.hasClass('d-none')) {
            $mainSelector.removeClass('d-none').addClass('d-flex');
            $(".cardlist-<?php echo $this->uniqId ?> .second-sidebar-search-box").removeClass('d-flex').addClass('d-none');
        } 
        else {
            $mainSelector.addClass('d-none').removeClass('d-flex');
            $(".cardlist-<?php echo $this->uniqId ?> .second-sidebar-search-box").addClass('d-flex').removeClass('d-none');
        }
        
    });

    $('body').on('click', '.dtlPostDelete_<?php echo $this->uniqId ?>', function () {
        var id = $(this).attr('data-id');
        var $selector = appendList_<?php echo $this->uniqId; ?>.find('li[data-id="' + id + '"]');
        
        dialogConfirm_<?php echo $this->uniqId ?> ('deletePost_<?php echo $this->uniqId; ?>', 'Та устгахдаа итгэлтэй байна yy?', id, $selector);
        
    });

    $('body').on('click', '.dtlPostExcel_<?php echo $this->uniqId ?>', function () {
        var id = $(this).attr('data-id');
        var $selector = appendList_<?php echo $this->uniqId; ?>.find('li[data-id="' + id + '"]');
                
    });

    $('body').on('click', '.dtlPostEdit_<?php echo $this->uniqId ?>', function () {
        var id = $(this).attr('data-id');
        var $selector = appendList_<?php echo $this->uniqId; ?>.find('li[data-id="' + id + '"]');
        editPost_<?php echo $this->uniqId; ?>(id, $selector);
    });

    $('body').on('click', '.downloadFiles_<?php echo $this->uniqId ?>', function () {
        var dataFiles = JSON.parse($(this).attr('data-files'));
        $.fileDownload(URL_APP + 'government/allDownloadContent', {
            httpMethod: 'post',
            data: {
                dataFiles: dataFiles
            }
        }).done(function () {
            Core.unblockUI();
            PNotify.removeAll();
            new PNotify({
                title: 'Амжилттай',
                text: 'Та файлыг татаж авах боломжтой',
                type: 'success',
                sticker: false
            });
            
        }).fail(function (response) {
            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: response,
                type: 'error',
                sticker: false
            });
            Core.unblockUI();
        });

    });
    
    $('body').on('click', '.group-search', function () {
        var $this = $(this);
        var $html = '';
        var iconUniqId = Core.getUniqueID('icon_');
        var $parent = $this.closest('ul');
        
        //$parent.find('a.intranet_tab1').removeClass('pt-2');
        
        panelDv_<?php echo $this->uniqId; ?>.find('.search_menu_<?php echo $this->uniqId ?>').empty();
        
        if ($this.attr('data-status') == '0') {
            $this.hide();
            $parent.find('div[data-status="0"]').attr('data-status', '1');
            
            panelDv_<?php echo $this->uniqId; ?>.find('.menu_<?php echo $this->uniqId ?>').hide();
            panelDv_<?php echo $this->uniqId; ?>.find('.search_menu_<?php echo $this->uniqId ?>').show();
            $html += '<div class="form-group form-group-feedback form-group-feedback-left">';
                $html += '<input type="text" name="searchp" class="custom-input-search form-control" data-path="search_value" placeholder="search typing...">';
                $html += '<div class="form-control-feedback form-control-feedback-sm group-search" data-status="1">';
                    $html += '<svg height="23pt" viewbox="1 1 511.999 511.999" width="23pt" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><lineargradient id="'+ iconUniqId +'" gradientunits="userspaceonuse" x1="255.9998561073" x2="255.9998561073" y1="0" y2="511.9997122146"><stop offset="0" stop-color="#2af598"/><stop offset="1" stop-color="#009efd"/></lineargradient><path d="m302.058594 0c-115.761719 0-209.941406 94.179688-209.941406 209.941406 0 50.695313 18.0625 97.253906 48.089843 133.574219l-140.207031 140.207031 28.277344 28.277344 140.207031-140.210938c36.320313 30.027344 82.878906 48.09375 133.574219 48.09375 115.761718 0 209.941406-94.179687 209.941406-209.941406 0-115.761718-94.179688-209.941406-209.941406-209.941406zm0 379.894531c-93.710938 0-169.953125-76.242187-169.953125-169.953125 0-93.710937 76.242187-169.953125 169.953125-169.953125 93.710937 0 169.953125 76.242188 169.953125 169.953125 0 93.710938-76.242188 169.953125-169.953125 169.953125zm19.992187-169.953125c0 11.042969-8.949219 19.996094-19.992187 19.996094-11.042969 0-19.996094-8.953125-19.996094-19.996094 0-11.042968 8.953125-19.996094 19.996094-19.996094 11.042968 0 19.992187 8.953126 19.992187 19.996094zm-79.976562 0c0 11.042969-8.953125 19.996094-19.996094 19.996094s-19.992187-8.953125-19.992187-19.996094c0-11.042968 8.949218-19.996094 19.992187-19.996094s19.996094 8.953126 19.996094 19.996094zm159.957031 0c0 11.042969-8.953125 19.996094-19.996094 19.996094-11.042968 0-19.996094-8.953125-19.996094-19.996094 0-11.042968 8.953126-19.996094 19.996094-19.996094 11.042969 0 19.996094 8.953126 19.996094 19.996094zm0 0" fill="url(#'+ iconUniqId +')"/></svg>';
                $html += '</div>';
            $html += '</div>';
            
            $parent.find('a.intranet_tab1').empty();
            //$parent.find('a.intranet_tab1').addClass('pt-2');
            
            $($html).appendTo($parent.find('a.intranet_tab1')).hide().show("slide", {direction: "left"}, "500");
            
            $parent.find('a.intranet_tab1').promise().done(function () {
                
                $parent.find('input[name="searchp"]').focus();
                $parent.find('.custom-input-search').keypress(function(e) {
                    if (e.keyCode == 13 && !e.shiftKey) {
                        var $searchval = $(this).val();
                        $.ajax({
                            url: 'government/searchIntranet',
                            type: 'post',
                            data: {
                                searchval: $searchval
                            },
                            dataType: 'JSON',
                            success: function (result) {
                                var $filterTypeList = typeof result.filterTypeList !== 'undefined' ? result.filterTypeList : [];
                                var $sHtml = '';
                                
                                $sHtml += '<ul class="nav nav-sidebar">';
                                
                                    $sHtml += '<li class="nav-item activing active">';
                                        $sHtml += '<a href="javascript:void(0);" class="nav-link" data-id="1" title="Мэдээ мэдээлэл" data-rowdata="">';
                                            $sHtml += '<i class="icon-stack4 font-size-18" style="color: BLUE;"></i>';
                                            $sHtml += '<span>'+ plang.get('all') +'</span>';
                                        $sHtml += '</a>';
                                    $sHtml += '</li>';
                                    
                                    $.each($filterTypeList, function (index, row) {
                                        $sHtml += '<li class="nav-item activing">';
                                            $sHtml += '<a href="javascript:void(0);" class="nav-link" data-id="'+ row.id +'" title="'+ row.name +'" data-rowdata="'+htmlentities(JSON.stringify(row), 'ENT_QUOTES', 'UTF-8')+'">';
                                                $sHtml += '<i class=" '+ ((typeof row.icon !== 'undefined' && row.icon) ? row.icon : 'icon-stack4') +'  font-size-18" style="color: BLUE;"></i>';
                                                $sHtml += '<span>'+ row.name +'</span>';
                                            $sHtml += '</a>';
                                        $sHtml += '</li>';
                                    });
                                    
                                $sHtml += '</ul>';
                                
                                panelDv_<?php echo $this->uniqId; ?>.find('.search_menu_<?php echo $this->uniqId ?>').empty().append($sHtml).promise().done(function () {
                                    panelDv_<?php echo $this->uniqId; ?>.find('.search_menu_<?php echo $this->uniqId ?>').on('click', '.nav-link', function () {
                                        var $this = $(this);
                                        $this.closest('ul').find('.nav-item').removeClass('active');
                                        $this.parent().addClass('active');
                                    });
                                    
                                    //$('.timeline-posts').empty().append(result.content);
                                });
                            },
                            error: function (jqXHR, exception) {
                                var msg = '';
                                if (jqXHR.status === 0) {
                                    msg = 'Not connect.\n Verify Network.';
                                } else if (jqXHR.status == 404) {
                                    msg = 'Requested page not found. [404]';
                                } else if (jqXHR.status == 500) {
                                    msg = 'Internal Server Error [500].';
                                } else if (exception === 'parsererror') {
                                    msg = 'Requested JSON parse failed.';
                                } else if (exception === 'timeout') {
                                    msg = 'Time out error.';
                                } else if (exception === 'abort') {
                                    msg = 'Ajax request aborted.';
                                } else {
                                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                                }

                                PNotify.removeAll();
                                new PNotify({
                                    title: 'Error',
                                    text: msg,
                                    type: 'error',
                                    sticker: false
                                });
                                if (typeof element !== 'undefined') {
                                    Core.unblockUI(element);
                                } else {
                                    Core.unblockUI();
                                }
                            }
                        });
                    }
                });
                
                
                $parent.find('.custom-input-search').keyup(function(e) {
                    if (e.keyCode == 27) {
                        $parent.find('div.group-search').trigger('click');
                    }
                });
            });
        } else {
            
            $parent.find('a.group-search').show();
            $parent.find('a[data-status="1"]').attr('data-status', '0');
            $('.menu_<?php echo $this->uniqId ?>').show();
            $('.search_menu_<?php echo $this->uniqId ?>').hide();
            $html += '<span><?php echo Lang::line('OLONNIIT_TITLE') ?></span>';
            $parent.find('a.intranet_tab1').empty();
            $($html).appendTo($parent.find('a.intranet_tab1'));
            
        }
        
    });
    
    var splitobj = Split(["#split-second-sidebar-<?php echo $this->uniqId; ?>",".main-content-<?php echo $this->uniqId ?>"], {
        elementStyle: function (dimension, size, gutterSize) { 
            $(window).trigger('resize');
            return {'flex-basis': 'calc(' + size + '% + 20px)'};
        },
        gutterStyle: function (dimension, gutterSize) { 
            return {'flex-basis':  gutterSize + 'px'}; 
        },
        sizes: [20,60,20],
        minSize: 50,
        gutterSize: 6,
        cursor: 'col-resize'
    });

    panelDv_<?php echo $this->uniqId; ?>.on('click', 'a[data-listmetadataid]', function() {

        var $this         = $(this);
        var rowId         = $this.data('id');
        var $parent       = $this.parent();
        var isChild       = $parent.hasClass('nav-item-submenu');
        var isSubItem     = $this.hasClass('v2');
        var $openMenu     = firstList_<?php echo $this->uniqId; ?>.find('.nav-item-open');
        var openMenuCount = $openMenu.length;
        
        firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').removeClass('dv-twocol-f-selected');
        firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-sub-selected').removeClass('dv-twocol-f-sub-selected');

        if ($this.closest('ul.nav').hasClass('nav-group-sub')) {
            $this.closest('ul.nav-group-sub').parent().find('a:eq(0)').addClass('dv-twocol-f-selected');
            childIndex_<?php echo $this->uniqId; ?> = $parent.index();
            $this.addClass('dv-twocol-f-sub-selected');
        } else {
            childIndex_<?php echo $this->uniqId; ?> = -1;
            $this.addClass('dv-twocol-f-selected');
        }
        
        blockContent_<?php echo $this->uniqId ?>($parent);
        
        if (!isSubItem && openMenuCount) {
            $openMenu.not($parent).removeClass('nav-item-open');
            firstList_<?php echo $this->uniqId; ?>.not($parent).find('.nav-group-sub').hide();
        }

        if (isSubItem) {

            var $subMenuParent = $this.closest('ul.nav-group-sub');
            var $openSubMenu = $subMenuParent.find('.nav-item-open');
            var openSubMenuCount = $openSubMenu.length;

            if (openSubMenuCount) {
                $openSubMenu.not($parent).removeClass('nav-item-open');
                $openSubMenu.not($parent).find('.nav-group-sub').hide();
            }
        }

        if (isChild) {

            if ($parent.hasClass('nav-item-open')) {
                $parent.removeClass('nav-item-open');
                $parent.find('.nav-group-sub').hide();
                Core.unblockUI(firstList_<?php echo $this->uniqId; ?>.find('.nav-item'));
                return;
            }

            if ($parent.find('.nav-group-sub').length == 0) {

                $.ajax({
                    url: 'government/getIntranetSubMenuRender/',
                    data: {
                        'id': rowId, 
                        uniqId: '<?php echo $this->uniqId ?>',
                        metaDataId: '<?php echo $this->metaDataId ?>'
                    },
                    type: 'post',
                    dataType: 'JSON',
                    beforeSend: function () {

                    },
                    success: function (data) {
                        var treeData = data.treeData;

                        if (treeData) {
                            var subMenu = '', subMenuClass = '', btnAction = '', icon = '', listMetaDataCriteria = '', metaTypeId = '';

                            for (var key in treeData) {

                                subMenuClass = '';
                                icon = '';
                                btnAction = '';

                                if (treeData[key].hasOwnProperty('ischild') && treeData[key]['ischild'] !== '0') {
                                    subMenuClass = ' nav-item-submenu';
                                }

                                if (treeData[key].hasOwnProperty('icon') && treeData[key]['icon']) {
                                    icon = '<i class="icon-'+treeData[key]['icon']+' font-size-18" style="color: '+treeData[key]['color']+';"></i> ';
                                    subMenuClass += ' with-icon';
                                }

                                if (treeData[key].hasOwnProperty('isdelete') && treeData[key]['isdelete'] == '1') {

                                    btnAction += '<div class="icon-trash-fix">';
                                        btnAction += '<button type="button" onclick="editFolder(\''+ treeData[key]['categoryid'] +'\', this)" class="btn btn-sm btn-icon  pt1 pb-0"><i class="fa fa-edit del"></i></button>';
                                    btnAction += '</div>';

                                    btnAction += '<div class="icon-trash-fix">';
                                        btnAction += '<button type="button" onclick="deleteFolder(\''+ treeData[key]['categoryid'] +'\', this)" class="btn btn-sm btn-icon  pt-0 pb-0"><i class="fa fa-trash del"></i></button>';
                                    btnAction += '</div>';
                                }
                                
                                if (childIndex_<?php echo $this->uniqId; ?> !== -1 && key == childIndex_<?php echo $this->uniqId; ?>) {
                                    subMenuClass += ' dv-twocol-f-sub-selected';
                                }
                                
                                subMenu += '<li class="nav-item'+subMenuClass+'" id="submenu_<?php echo getUID() ?>"><a href="javascript:void(0);" data-id="' + treeData[key]['id'] + '" data-listmetadataid="' + treeData[key]['id'] + '" data-rowdata="'+htmlentities(JSON.stringify(treeData[key]), 'ENT_QUOTES', 'UTF-8')+'" class="nav-link v2">' + icon + treeData[key]['name'] 
                                            + btnAction
                                        + '</a></li>';
                            }
                        }

                        $parent.append('<ul class="nav nav-group-sub" style="display: block;">'+subMenu+'</ul>').promise().done(function () {
                            Core.unblockUI(firstList_<?php echo $this->uniqId; ?>.find('.nav-item'));
                            if (childIndex_<?php echo $this->uniqId; ?> !== -1 ) {
                                firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-sub-selected').trigger('click');
                            }
                        });

                        $parent.addClass('nav-item-open');
                    }
                });

            } else {
                $parent.addClass('nav-item-open');
                $parent.find('.nav-group-sub').show();
                
            }   
        }
        
        buildOneColSecondPart<?php echo $this->uniqId ?>('<?php echo $this->uniqId; ?>', rowId, $this);
    });

    panelDv_<?php echo $this->uniqId; ?>.on('click', 'a[data-secondlistaddprocessid]', function () {
        
        var $rowData = firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').data('rowdata');
        if (firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').parent().find('.dv-twocol-f-sub-selected').length) {
            $rowData = firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').parent().find('.dv-twocol-f-sub-selected').data('rowdata');
        }
        
        var $modalId_<?php echo $this->uniqId ?> = 'modal-intranet<?php echo $this->uniqId ?>';
        
        if (typeof $rowData !== 'object') {
            $rowData = JSON.parse($rowData);
        }
        
        $.ajax({
            url: 'government/addForm',
            type: 'post',
            dataType: 'json',
            data: {
                dataRow: $rowData,
                uniqId: '<?php echo $this->uniqId ?>'
            },
            beforeSend: function () {
                blockContent_<?php echo $this->uniqId ?>('.intranet-<?php echo $this->uniqId ?>');
            },
            success: function (data) {
                var title = 'Нэмэх';
                var isconfirmed = '0';
                if ($rowData.posttypeid === '1') {
                    title = 'Мэдээ нэмэх';
                } else if ($rowData.posttypeid === '2') {
                    title = 'Хэлэлцүүлэг нэмэх';
                } else if ($rowData.posttypeid === '3') {
                    title = 'Санал асуулга нэмэх';
                } else if ($rowData.posttypeid === '4') {
                    title = ($rowData.windowtypeid === '5') ? 'Сургалт нэмэх' : 'Файлын сан нэмэх';
                    isconfirmed = '1';
                } else if ($rowData.posttypeid === '5') {
                    title = 'Зургийн цомог нэмэх';
                    isconfirmed = '1';
                } else {
                    title = ($rowData.windowtypeid === '5') ? 'Сургалт нэмэх' : 'Нэмэх';
                }

                if ($rowData.windowtypeid) {
                    switch ($rowData.windowtypeid) {
                        case '2':
                            $('<div id="' + $modalId_<?php echo $this->uniqId ?> + '" class="modal intranet_sent_email fade" tabindex="-1" role="dialog">' +
                                    '<div class="modal-dialog modal-lg">' +
                                        '<div class="modal-content modalcontent<?php echo $this->uniqId ?>">' +
                                            '<div class="modal-header">' +
                                                '<h5 class="modal-title">Санал асуулга нэмэх</h5>' +
                                                '<button type="button" class="close" onclick="close_<?php echo $this->uniqId ?>('+ isconfirmed +')" style="filter: inherit;">&times;</button>' +
                                            '</div>' +
                                            '<div class="modal-body pt-2">' +
                                                data.Html +
                                            '</div>' +
                                            '<div class="border-top-1 border-gray">' +
                                                '<div class="modal-footer pt-2 pb-2">' +
                                                    '<button type="button" class="btn send-btn-<?php echo $this->uniqId ?>" onclick="save_<?php echo $this->uniqId ?>(\'.modalcontent<?php echo $this->uniqId ?>\')">Хадгалах</button>' +
                                                    '<button type="button" class="btn btn-link" onclick="close_<?php echo $this->uniqId ?>('+ isconfirmed +')">Хаах</button>' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>').appendTo('body');
                            var $dialog = $('#' + $modalId_<?php echo $this->uniqId ?>);

                            $dialog.modal({
                                show: false,
                                keyboard: false,
                                backdrop: 'static'
                            });

                            $dialog.on('shown.bs.modal', function () {
                                setTimeout(function () {

                                }, 10);
                                disableScrolling();
                            });

                            $dialog.draggable({
                                handle: ".modal-header"
                            });
                            
                            $dialog.on('hidden.bs.modal', function () {
                                $dialog.remove();
                                enableScrolling();
                            });

                            $dialog.modal('show');
                            Core.initAjax($dialog);

                            $dialog.find('.modal-backdrop').remove();

                            break;
                        case '1':
                        case '3':
                        case '4':
                        case '5': //Сургалт

                            $('<div id="' + $modalId_<?php echo $this->uniqId ?> + '" class="modal intranet_sent_email fade" tabindex="-1" role="dialog">' +
                                    '<div class="modal-dialog modal-lg">' +
                                        '<div class="modal-content modalcontent<?php echo $this->uniqId ?>">' +
                                            '<div class="modal-header">' +
                                                '<h6 class="modal-title">' + title + '</h6>' +
                                                '<button type="button" class="close" onclick="close_<?php echo $this->uniqId ?>('+ isconfirmed +')" style="filter: inherit;">&times;</button>' +
                                            '</div>' +
                                            '<div class="modal-body">' +
                                                data.Html +
                                            '</div>' +
                                            '<div class="modal-footer">' +
                                                '<button type="button" class="btn send-btn-<?php echo $this->uniqId ?>" onclick="save_<?php echo $this->uniqId ?>(\'.modalcontent<?php echo $this->uniqId ?>\')">Хадгалах</button>' +
                                                '<button type="button" class="btn btn-link" onclick="close_<?php echo $this->uniqId ?>('+ isconfirmed +')">Хаах</button>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>').appendTo('body');

                            var $dialog = $('#' + $modalId_<?php echo $this->uniqId ?>);

                            $dialog.modal({
                                show: false,
                                keyboard: false,
                                backdrop: 'static'
                            });

                            $dialog.on('shown.bs.modal', function () {
                                setTimeout(function () {

                                }, 10);
                                disableScrolling();
                            });
                            
                            $dialog.draggable({
                                handle: ".modal-header"
                            });
                            
                            $dialog.on('hidden.bs.modal', function () {
                                $dialog.remove();
                                enableScrolling();
                            });
                            
                            if ($rowData.windowtypeid == '1') {
                                if (typeof tinymce === 'undefined') {
                                    $.getScript(URL_APP + 'assets/custom/addon/plugins/tinymce/tinymce.min.js', function () {
                                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/autocomplete.css"/>');
                                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/rte-content.css"/>');
                                        $.getScript(URL_APP + 'assets/custom/addon/plugins/tinymce/plugins/mention/plugin.min.js').done(function (script, textStatus) {
                                            initTinyMceEditor_<?php echo $this->uniqId ?>('#body_<?php echo $this->uniqId ?>', '520');
                                            $dialog.modal('show');
                                        });
                                    });
                                } else {
                                    initTinyMceEditor_<?php echo $this->uniqId ?>('#body_<?php echo $this->uniqId ?>', '520');
                                    $dialog.modal('show');
                                }
                            } else {
                                $dialog.modal('show');
                            }

                            Core.initAjax($dialog);
                            $dialog.find('.modal-backdrop').remove();
                            break;
                        case '9988':

                            $('<div id="' + $modalId_<?php echo $this->uniqId ?> + '" class="modal intranet_sent_email fade" tabindex="-1" role="dialog">' +
                                    '<div class="modal-dialog modal-lg">' +
                                        '<div class="modal-content modalcontent<?php echo $this->uniqId ?>">' +
                                            '<div class="modal-header">' +
                                                    '<h6 class="modal-title">Бүлгэм</h6>' +
                                                    '<button type="button" class="close" onclick="close_<?php echo $this->uniqId ?>('+ isconfirmed +')" style="filter: inherit;">&times;</button>' +
                                            '</div>' +
                                        '<div class="modal-body">' +
                                            data.Html +
                                        '</div>' +
                                        '<div class="modal-footer">' +
                                            '<button type="button" class="btn send-btn-<?php echo $this->uniqId ?>" onclick="save_<?php echo $this->uniqId ?>(\'.modalcontent<?php echo $this->uniqId ?>\')">Хадгалах</button>' +
                                            '<button type="button" class="btn btn-link" onclick="close_<?php echo $this->uniqId ?>('+ isconfirmed +')">Хаах</button>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>').appendTo('body');

                            var $dialog = $('#' + $modalId_<?php echo $this->uniqId ?>);

                            $dialog.modal({
                                show: false,
                                keyboard: false,
                                backdrop: 'static'
                            });

                            $dialog.on('shown.bs.modal', function () {
                                setTimeout(function () {

                                }, 10);
                                disableScrolling();
                            });
                            
                            $dialog.draggable({
                                handle: ".modal-header"
                            });
                            
                            $dialog.on('hidden.bs.modal', function () {
                                $dialog.remove();
                                enableScrolling();
                            });
                            
                            if ($rowData.windowtypeid == '1') {
                                if (typeof tinymce === 'undefined') {
                                    $.getScript(URL_APP + 'assets/custom/addon/plugins/tinymce/tinymce.min.js', function () {
                                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/autocomplete.css"/>');
                                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/rte-content.css"/>');
                                        $.getScript(URL_APP + 'assets/custom/addon/plugins/tinymce/plugins/mention/plugin.min.js').done(function (script, textStatus) {
                                            initTinyMceEditor_<?php echo $this->uniqId ?>('#body_<?php echo $this->uniqId ?>', '520');
                                            $dialog.modal('show');
                                        });
                                    });
                                } else {
                                    initTinyMceEditor_<?php echo $this->uniqId ?>('#body_<?php echo $this->uniqId ?>', '520');
                                    $dialog.modal('show');
                                }
                            } else {
                                $dialog.modal('show');
                            }

                            Core.initAjax($dialog);
                            $dialog.find('.modal-backdrop').remove();
                            break;
                        default:
                            break;
                    }
                }
                
                Core.unblockUI('.intranet-<?php echo $this->uniqId ?>');
            }
        });
        
    });

    panelDv_<?php echo $this->uniqId; ?>.on('click', 'button[data-deleteactionbtn]', function (e) {
        var $this = $(this);
        var id = $this.data('deleteactionbtn');
        if (id) {
            runIsOneBusinessProcess(1564662242403286, 1568017432968, true, {id: id}, function () {
                $this.closest('li').fadeOut(1000, function () {
                    $(this).remove();
                });
            });
            e.preventDefault();
            e.stopPropagation();
        }
    });
    
    function panelscrolling<?php echo $this->uniqId ?> () {
            
        var $this = firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected');
        
        if ($this.find('.dv-twocol-f-sub-selected').length) {
            $this = $this.find('.dv-twocol-f-sub-selected');
        }
        
        panelDv_<?php echo $this->uniqId; ?>.find('.loadmore<?php echo $this->uniqId ?>').hide();

        if ($totalcount_<?php echo $this->uniqId ?> != appendList_<?php echo $this->uniqId; ?>.find('li').length) {
            panelDv_<?php echo $this->uniqId; ?>.find('.loadmore<?php echo $this->uniqId ?>').show();
            pagination<?php echo $this->uniqId ?>++;
            blockContent_<?php echo $this->uniqId ?>(appendList_<?php echo $this->uniqId; ?>);
            buildOneColSecondPart<?php echo $this->uniqId ?>('<?php echo $this->uniqId; ?>', $this.data('id'), $this, pagination<?php echo $this->uniqId ?>);
        }
    }
    
    function initTinyMceEditor_<?php echo $this->uniqId ?>($elementSelector, otherheight) {
        
        tinymce.dom.Event.domLoaded = true;
        tinymce.baseURL = URL_APP + 'assets/custom/addon/plugins/tinymce';
        tinymce.suffix = ".min";
        tinymce.init({
            selector: $elementSelector,
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'autoresize',
                'emoticons template paste textcolor colorpicker textpattern imagetools moxiemanager mention lineheight fullpage'
            ],
            toolbar1: 'fontselect fontsizeselect | forecolor backcolor | bold italic underline | alignleft aligncenter alignright alignjustify | customInsertButton currentdate',
            fontsize_formats: '8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 36px 8pt 9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 17pt 18pt 19pt 20pt 21pt 22pt 23pt 24pt 25pt 36pt',
            lineheight_formats: '8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 1.0 1.15 1.5 2.0 2.5 3.0',
            image_advtab: true, 
            setup: function(editor) {
                
                function insertImageCustom_<?php echo $this->uniqId ?>() {
                    $('#upload_<?php echo $this->uniqId ?>').trigger('click');
                    $('#upload_<?php echo $this->uniqId ?>').on('change', function() {
                        var file = this.files[0];
                        if (file) {
                            $(this).val('');
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                editor.insertContent('<img src="'+e.target.result+'" />');
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                }

                editor.on('change', function() {
                    tinymce.triggerSave();
                });

                editor.addButton('currentdate', {
                    icon: 'image',
                    tooltip: "Insert image",
                    onclick: insertImageCustom_<?php echo $this->uniqId ?>
                });
                
                editor.on('SetContent',function(e) {
                    const content = e.content;
                    var newHeight = String(content.length/2);
                    editor.theme.resizeTo("100%",newHeight);          
               });  
            },
            force_br_newlines: true,
            force_p_newlines: false,
            forced_root_block: '',
            paste_data_images: true,
            menubar: false,
            statusbar: true,
            resize: true,
            theme_advanced_toolbar_location: "bottom",
            theme_modern_toolbar_location: "bottom",
            paste_word_valid_elements: "b,p,br,strong,i,em,h1,h2,h3,h4,ul,li,ol,table,span,div,font",
            mentions: {
                delimiter: '#'
            },
            document_base_url: URL_APP,
            content_css: URL_APP + 'assets/custom/css/print/tinymce.css',
        });
    }

    function close_<?php echo $this->uniqId ?>(isconfirmed) {
        var $html = 'Хаахдаа итгэлтэй байна уу?';
        dialogConfirm_<?php echo $this->uniqId ?> ('fieldclear_<?php echo $this->uniqId ?>', $html);
        return; 
        if (typeof isconfirmed !== 'undefined' && isconfirmed == '1') {
            
        } else {
            fieldclear_<?php echo $this->uniqId ?>();
        }
    }
    
    function dialogConfirm_<?php echo $this->uniqId ?> (callbackFunction, $html, $param1, $param2) {

        var $dialogConfirm = 'dialog-confirm-<?php echo $this->uniqId ?>';
        if (!$("#" + $dialogConfirm).length) {
            $('<div id="' + $dialogConfirm + '"></div>').appendTo('.intranet-<?php echo $this->uniqId ?>');
        }
        var $dialog = $("#" + $dialogConfirm);

        $dialog.empty().append($html);
        $dialog.dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: 'Баталгаажуулалт',
            width: 400,
            height: "auto",
            modal: true,
            close: function () {
                $dialog.empty().dialog('close');
            },
            buttons: [
                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                    window[callbackFunction]($param1, $param2);
                    $dialog.empty().dialog('close');
                }},
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');

    }

    function fieldclear_<?php echo $this->uniqId ?>($type) {

        if (typeof tinymce !== 'undefined') {
            tinymce.remove('textarea#body_<?php echo $this->uniqId ?>');
        }

        $("#modal-intranet<?php echo $this->uniqId ?>").modal('hide');

        $("#modal-intranet<?php echo $this->uniqId ?>").find('input').val('');
        $("#modal-intranet<?php echo $this->uniqId ?>").find('textarea').val('');
        $("#modal-intranet<?php echo $this->uniqId ?>").find('select').select2('val', '');

        /*
         $("button.cc").removeAttr('show-status');
         $("button.bcc").removeAttr('show-status');
         
         $('.mailcontainer_<?php echo $this->uniqId ?>').find(".inputcc").hide();
         $('.mailcontainer_<?php echo $this->uniqId ?>').find(".inputbcc").hide();
         */
            
        $('#modal-intranet<?php echo $this->uniqId ?> .list-view-file-new').empty();
        
        if (typeof $type !== 'undefined' && $type === 'noreload') {} else {
            reloadmenu_<?php echo $this->uniqId ?>(firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').parent().index());
        }
    }

    function save_<?php echo $this->uniqId ?>(element) {
        
        $("#saveform_<?php echo $this->uniqId ?>").validate({ errorPlacement: function() {} });
        if ($("#saveform_<?php echo $this->uniqId ?>").valid()) {
            $("#saveform_<?php echo $this->uniqId ?>").ajaxSubmit({
                type: 'post',
                url: "government/savePost/",
                dataType: 'json',
                beforeSend: function () {
                    if (typeof element !== 'undefined') {
                        blockContent_<?php echo $this->uniqId ?>(element);
                    } else {
                        Core.blockUI({
                            message: 'Түр хүлээнэ үү',
                            boxed: true
                        });
                    }
                },
                success: function (response) {

                    PNotify.removeAll();
                    new PNotify({
                        title: response.status,
                        text: response.text,
                        type: response.status,
                        sticker: false
                    });

                    if (response.status === 'success') {

                        if (typeof $dialogConfirm !== 'undefined') {
                            $("#" + $dialogConfirm).dialog('close');
                        }
                        
                        try {
                            if (response.hasOwnProperty('result') && response['result']) {
                                var $rsData = response['result'];
                                /*if ($rsData.hasOwnProperty('int_post_user_dv') && $rsData['int_post_user_dv']) {
                                    $.each($rsData['int_post_user_dv'], function ($pindex, $puser) {
                                        var postType = 'postIntranet_' + $puser['userid'];
                                        rtc.apiSendOneUser($puser['userid'], {type: postType, data: response.data, Html: ''});
                                    });
                                }*/
                            }
                        } catch (e) {
                            console.log(e);
                        }
                        
                        fieldclear_<?php echo $this->uniqId ?>();
                    }
                    if (typeof element !== 'undefined') {
                        Core.unblockUI(element);
                    } else {
                        Core.unblockUI();
                    }
                    
                },
                error: function (jqXHR, exception) {
                    Core.showErrorMessage(jqXHR, exception);
                    if (typeof element !== 'undefined') {
                        Core.unblockUI(element);
                    } else {
                        Core.unblockUI();
                    }
                }
            });
        }
    }

    function reloadmenu_<?php echo $this->uniqId; ?>($activeindex) {
        childIndex_<?php echo $this->uniqId; ?> = -1; 
        
        if (firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').parent().find('.dv-twocol-f-sub-selected').length) {
            $activeindex= firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').parent().index();
            childIndex_<?php echo $this->uniqId; ?> = firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').parent().find('.dv-twocol-f-sub-selected').parent().index();
        }
        
        $.ajax({
            url: 'government/reloadIntranetMenu',
            type: 'post',
            dataType: 'json',
            beforeSend: function () {
                blockContent_<?php echo $this->uniqId ?>('.menu_<?php echo $this->uniqId ?>');
            },
            data: {
                bookId: '1',
                uniqId: '<?php echo $this->uniqId ?>',
                metaDataId: '<?php echo $this->metaDataId ?>'
            },
            success: function (data) {
                firstList_<?php echo $this->uniqId; ?>.empty().append(data.Html).promise().done(function () {
                    var _activeindex = (typeof $activeindex  !== 'undefined') ? $activeindex : '0';
                    
                    firstList_<?php echo $this->uniqId; ?>.find('li.nav-item:eq('+ _activeindex +') > a').trigger('click');
                    Core.unblockUI('.menu_<?php echo $this->uniqId ?>');
                });
            },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }

                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: msg,
                    type: 'error',
                    sticker: false
                });
                Core.unblockUI('.menu_<?php echo $this->uniqId ?>');
            }
        });
    }
    
    function editFolder(categoryId, element) {
        var rowData = $(element).data('rowdata');

        if (typeof rowData !== 'object') {
            rowData = JSON.parse(rowData);
        }
        
        var processId = '1572930649085';
        if (processId) {

            var $dialogName = 'dialog-businessprocess-' + processId;
            if (!$('#' + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);

            var fillDataParams = '';
            fillDataParams = '&selectedId=' + rowData.id;

            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: 1572930649085,
                    isDialog: true,
                    dmMetaDataId: 1565319131590210,
                    oneSelectedRow: rowData,
                    isGetConsolidate: false,
                    workSpaceId: '',
                    workSpaceParams: '',
                    wfmStatusParams: '',
                    signerParams: false,
                    batchNumber: false,
                    openParams: '{"callerType":"SCL_POSTS_MAIN_TYPE_DV"}',
                    isBasketWindow: '',
                    isBpOpen: 0,
//                    fillDataParams: fillDataParams
                },
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function (data) {

                    $dialog.empty().append(data.Html);

                    var $processForm = $('#wsForm', '#' + $dialogName),
                            processUniqId = $processForm.parent().attr('data-bp-uniq-id');

                    var buttons = [
                        {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                                if (window['processBeforeSave_' + processUniqId]($(e.target))) {

                                    $processForm.validate({
                                        ignore: '',
                                        highlight: function (element) {
                                            $(element).addClass('error');
                                            $(element).parent().addClass('error');
                                            if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                                $processForm.find("div.tab-pane:hidden:has(.error)").each(function (index, tab) {
                                                    var tabId = $(tab).attr('id');
                                                    $processForm.find('a[href="#' + tabId + '"]').tab('show');
                                                });
                                            }
                                        },
                                        unhighlight: function (element) {
                                            $(element).removeClass('error');
                                            $(element).parent().removeClass('error');
                                        },
                                        errorPlacement: function () {
                                        }
                                    });

                                    var isValidPattern = initBusinessProcessMaskEvent($processForm);

                                    if ($processForm.valid() && isValidPattern.length === 0) {
                                        $processForm.ajaxSubmit({
                                            type: 'post',
                                            url: 'mdwebservice/runProcess',
                                            dataType: 'json',
                                            beforeSend: function () {
                                                Core.blockUI({
                                                    boxed: true,
                                                    message: 'Түр хүлээнэ үү'
                                                });
                                            },
                                            success: function (responseData) {
                                                PNotify.removeAll();
                                                new PNotify({
                                                    title: responseData.status,
                                                    text: responseData.message,
                                                    type: responseData.status,
                                                    sticker: false
                                                });

                                                if (responseData.status === 'success') {
                                                    var $resultData = responseData.resultData;
                                                    $dialog.dialog('close');
                                                    reloadmenu_<?php echo $this->uniqId; ?>($('.menu_<?php echo $this->uniqId ?>').find('li[id="menu_<?php echo $this->uniqId ?>_' + $resultData.typeid + '"]').index());
                                                }
                                                Core.unblockUI();
                                            },
                                            error: function () {
                                                alert("Error");
                                            }
                                        });
                                    }
                                }
                            }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {

                                $dialog.dialog('close');
                            }}
                    ];
                    var dialogWidth = data.dialogWidth, dialogHeight = data.dialogHeight;

                    if (data.isDialogSize === 'auto') {
                        dialogWidth = 1200;
                        dialogHeight = 'auto';
                    }

                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: dialogWidth,
                        height: dialogHeight,
                        modal: true,
                        closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape),
                        close: function () {
                            $dialog.empty().dialog('destroy').remove();
                        },
                        buttons: buttons
                    }).dialogExtend({
                        "closable": true,
                        "maximizable": true,
                        "minimizable": true,
                        "collapsable": true,
                        "dblclick": "maximize",
                        "minimizeLocation": "left",
                        "icons": {
                            "close": "ui-icon-circle-close",
                            "maximize": "ui-icon-extlink",
                            "minimize": "ui-icon-minus",
                            "collapse": "ui-icon-triangle-1-s",
                            "restore": "ui-icon-newwin"
                        }
                    });

                    if (data.dialogSize === 'fullscreen') {
                        $dialog.dialogExtend("maximize");
                    }

                    setTimeout(function () {
                        $dialog.dialog('open');
                        Core.unblockUI();
                    }, 1);
                },
                error: function () {
                    alert('Error');
                }
            });
        }
    }

    function deleteFolder(categoryId, element) {
        if (categoryId) {
            runIsOneBusinessProcess(1567592452382272, 1567584413074, true, {id: categoryId}, function () {
                $(element).closest('li').fadeOut(1000, function () {
                    $(this).remove();
                });
            });
        }
    }

    function replyComment_<?php echo $this->uniqId; ?>(id, type, element) {

        var $mainSelector = $(element).closest('ul');
        var comment_id = $(element).attr('data-comment-id');
        var user_id = $mainSelector.attr('data-comment-user');

        $('body').find('.intranet-<?php echo $this->uniqId ?>').find('.replycomment-<?php echo $this->uniqId ?>').empty();

        var $subHtml = '<div data-path="reply-tag"><div class="subaddcomment-<?php echo $this->uniqId ?> mb-2 mt-2">' +
                            '<textarea data-comment-id="' + comment_id + '" rows="3" cols="3" class="form-control" placeholder="Та сэтгэгдлээ бичээд enter товч дарна уу." style="margin-top: 0px; margin-bottom: 0px; height: 76px;" required></textarea>' +
                        '</div>' +
                        '<div class="dv-process-buttons"><button type="button" id="save_comment" data-comment-user="' + user_id + '"  data-comment-save="' + comment_id + '" onclick="saveComment_<?php echo $this->uniqId ?>(this, \'reply\')" class="btn btn-success btn-circle mb-3">Хадгалах</button></div></div>';

        $("#gg" + id).empty().append($subHtml).promise().done(function () {
            $('textarea[data-comment-id="' + comment_id + '"]').keypress(function(e) {
                if (e.keyCode == 13 && !e.shiftKey) {
                    $('button[data-comment-save="' + comment_id + '"]').trigger('click');
                }
            });
        });
    }

    function getComments_<?php echo $this->uniqId ?>(postId) {
        $.ajax({
            url: 'government/getIntranetComments',
            type: 'post',
            data: {
                postId: postId,
                uniqId: '<?php echo $this->uniqId ?>'
            },
            dataType: 'JSON',
            beforeSend: function () {
                blockContent_<?php echo $this->uniqId ?>('#commentbody-<?php echo $this->uniqId ?>');
            },
            success: function (data) {
                $("#commentbody-<?php echo $this->uniqId ?>").empty().append(data.Html).promise().done(function () {
                    Core.unblockUI('#commentbody-<?php echo $this->uniqId ?>');
                    $("#commentbody-<?php echo $this->uniqId ?>").find('[data-comment-dd]').each(function (index, row) {
                        $(row).qtip({
                            content: {
                                text: function(event1, api1) {
                                    var response = $.ajax({
                                        url: 'government/getUserData',
                                        type: 'post',
                                        data: {
                                            userId: $(row).attr('data-comment-dd')
                                        },
                                        dataType: 'JSON',
                                        beforeSend: function () {},
                                        success: function (data) {
                                            var $data = (typeof data.result !== 'undefined') ? data.result : [];
                                            var $html = '<div class="media-body d-flex align-items-center justify-content-center" style="padding: 8px;">';
                                                    $html += '<div class="col-4 text-center border-right-1 border-gray mr-2">';
                                                        $html += '<img src="'+ ((typeof $data.picture !== 'undefined' && $data.picture) ? $data.picture : 'assets/custom/addon/admin/layout4/img/user.png') +'" onerror="onNCUserImgError(this);" class="rounded-circle" style="width:50px;height:50px;">';
                                                        $html += '<h6 class="text-blue font-weight-bold mt-1 mb-1 line-height-normal">'+ ((typeof $data.name !== 'undefined' && $data.name) ? $data.name : '') +'</h6>';
                                                        $html += '<p class="text-blu mb-0 font-size-12 line-height-normal">'+ ((typeof $data.position !== 'undefined' && $data.position) ? $data.position : '') +'</p>';
                                                    $html += '</div>';
                                                    $html += '<div class="col-8">';
                                                        $html += '<ul class="media-list font-size-13">';
                                                            $html += '<li class="d-flex flex-row align-items-center">';
                                                                $html += '<i class="icon-mail5 text-blue mr-1"></i>';
                                                                $html += '<a href="mailto:">'+ ((typeof $data.employeeemail !== 'undefined' && $data.employeeemail) ? $data.employeeemail : '') +'</a>';
                                                            $html += '</li>';
                                                            $html += '<li class="d-flex flex-row align-items-center mt10">';
                                                                $html += '<i class="icon-mobile text-blue mr-1"></i>';
                                                                $html += '<span>'+ ((typeof $data.employeemobile !== 'undefined' && $data.employeemobile) ? $data.employeemobile : '') +'</span>';
                                                            $html += '</li>';
                                                            $html += '<li class="d-flex flex-row align-items-center mt10">';
                                                                $html += '<i class="icon-location3 text-blue mr-1"></i>';
                                                                $html += '<span class="line-height-normal">'+ ((typeof $data.departmentname !== 'undefined' && $data.departmentname) ? $data.departmentname: '') +'</span>';
                                                            $html += '</li>';
                                                        $html += '</ul>';
                                                    $html += '</div>';
                                                $html += '</div>';
                                            $('body').find('#' + api1['_id']).find('.qtip-content').html($html);
                                        }
                                    });
                                }
                            },
                            position:{
                                effect:!1,at:"center left",
                                my: "right center",
                            },
                            show:{effect:!1,delay:500},
                            hide:{effect:!1,fixed:!0,delay:70},
                            style:{classes:"qtip-bootstrap",width:450,tip:{width:12,height:7}}
                        });
                    });
                });
            },
            error: function () {
                Core.unblockUI('.main-content-<?php echo $this->uniqId ?>');
            }
        });
    }

    function getNewsContent_<?php echo $this->uniqId ?>(id) {
        
        var $dataRow = JSON.parse(appendList_<?php echo $this->uniqId; ?>.find('li[data-id="' + id + '"] > a').attr('data-rowdata'));
        
        if (typeof $dataRow.isread !== 'undefined' && $dataRow.isread == '0') {
            readContent_<?php echo $this->uniqId ?>(id, $dataRow);
        }
        
        appendList_<?php echo $this->uniqId; ?>.find('.active').removeClass('active');
        appendList_<?php echo $this->uniqId; ?>.find('li[data-id="' + id + '"]').addClass('active');
        
        if ($dataRow) {
            var strRows = JSON.stringify($dataRow);
            strRows = strRows.replace(/<\/?.+?>/ig, '');
            $dataRow = JSON.parse(strRows);
        }
        
        if ($dataRow.categoryid == '9988' ) {
            Core.unblockUI('.main-content-<?php echo $this->uniqId ?>');
            $('.main-content-<?php echo $this->uniqId ?>').empty();
            renderSocialMedia<?php echo $this->uniqId ?>($dataRow);
            return;
        }
        
        $.ajax({
            url: 'government/contentByIntranet/',
            type: 'post',
            dataType: 'JSON',
            data: {
                uniqId: '<?php echo $this->uniqId ?>',
                dataRow: $dataRow,
                id: id
            },
            beforeSend: function () {
                blockContent_<?php echo $this->uniqId ?>('.main-content-<?php echo $this->uniqId ?>');
            },
            success: function (response) {
                $('.main-content-<?php echo $this->uniqId ?>').empty().append(response.Html).promise().done(function () {
                    var result = response.data;
                    var likehtml = '', unlikehtml = '';

                    $("#post_id").val(id);
                    $("#modal_default_show_view").find('.modal-body > #viewslist').empty();

                    var viewhtml = '<div class="alert bg-teal text-white alert-styled-left alert-styled-custom alert-dismissible text-center mb-0 p-0 pt-2 pb-2">' +
                            '<span class="font-weight-semibold">Үзээгүй хэрэглэгчийн тоо : <b>' + result.unseencount + '</b></span>' +
                            '</div><hr>';
/*
                    if (typeof result.scl_posts_view_dv !== 'undefined' && result.scl_posts_view_dv !== null) {

                        $.each(result.scl_posts_view_dv, function (index, row) {
                            var read = '';
                            if(row.isread == '1') {
                                read = '<span class="badge badge-success">Үзсэн</span>';
                            } else {
                                read = '';
                            }

                            viewhtml += '<li class="media">' +
                                    '<div class="mr-3">' +
                                    '<img src="' + row.picture + '" width="36" height="36" class="rounded-circle" alt="' + row.createdusername + '" onerror="onNCUserImgError(this);">' +
                                    '</div>' +
                                    '<div class="media-body p-1">' +
                                    '<a href="javascript:void(0);" class="media-title font-weight-semibold pull-left" style="width: 40%">' + row.createdusername + '</a>' +
                                    '<span class="d-block text-muted font-size-sm">' + (row.readdate ? row.readdate : '') + '</span>' +
                                    '</div>' + read + 
                                    '</li>';
                        });

                    }*/
                    if (typeof result.scl_post_like_list !== 'undefined' && result.scl_post_like_list !== null) {
                        $.each(result.scl_post_like_list, function (index, row) {
                            if (row.liketype === 'Like') {
                                likehtml += '<li class="media">' +
                                        '<div class="mr-3">' +
                                        '<img src="" width="36" height="36" class="rounded-circle" alt="" onerror="onNCUserImgError(this);">' +
                                        '</div>' +
                                        '<div class="media-body">' +
                                        '<a href="javascript:void(0);" class="media-title font-weight-semibold">' + row.name + '</a>' +
                                        '<span class="d-block text-muted font-size-sm">' + row.createddate + '</span>' +
                                        '</div>' +
                                        '<div class="ml-3 align-self-center"><i class="icon-thumbs-up2 text-success mr-1"></i></div>' +
                                        '</li>';

                            } else {
                                unlikehtml += '<li class="media">' +
                                        '<div class="mr-3">' +
                                        '<img src="" width="36" height="36" class="rounded-circle" alt="" onerror="onNCUserImgError(this);">' +
                                        '</div>' +
                                        '<div class="media-body">' +
                                        '<a href="javascript:void(0);" class="media-title font-weight-semibold">' + row.name + '</a>' +
                                        '<span class="d-block text-muted font-size-sm">' + row.createddate + '</span>' +
                                        '</div>' +
                                        '<div class="ml-3 align-self-center"><i class="icon-thumbs-down2 text-danger mr-1"></i></div>' +
                                        '</li>';

                            }
                        });
                    }

                    $("#modal_post_show_like").find('.modal-body').empty().append(likehtml);
                    $("#modal_post_show_dislike").find('.modal-body').empty().append(unlikehtml);
                    $("#modal_default_show_view").find('.modal-body > #viewslist').empty().append(viewhtml);

                    //санал асуулга
                    /* if (result.typeid === "3") {
                     getPollAttept(id);
                     } **/

                    $(".main-content-<?php echo $this->uniqId ?>").find('[data-user-dd]').each(function (index, row) {
                        $(row).qtip({
                            content: {
                                text: function(event1, api1) {
                                    var response = $.ajax({
                                        url: 'government/getUserData',
                                        type: 'post',
                                        data: {
                                            userId: $(row).attr('data-user-dd')
                                        },
                                        dataType: 'JSON',
                                        beforeSend: function () {},
                                        success: function (data) {
                                            var $data = (typeof data.result !== 'undefined') ? data.result : [];
                                            var $html = '<div class="media-body d-flex align-items-center justify-content-center" style="padding: 8px;">';
                                                    $html += '<div class="col-4 text-center border-right-1 border-gray mr-2">';
                                                        $html += '<img src="'+ ((typeof $data.picture !== 'undefined' && $data.picture) ? $data.picture : 'assets/custom/addon/admin/layout4/img/user.png') +'" onerror="onNCUserImgError(this);" class="rounded-circle" style="width:50px;height:50px;">';
                                                        $html += '<h6 class="text-blue font-weight-bold mt-1 mb-1 line-height-normal">'+ ((typeof $data.name !== 'undefined' && $data.name) ? $data.name : '') +'</h6>';
                                                        $html += '<p class="text-blu mb-0 font-size-12 line-height-normal">'+ ((typeof $data.position !== 'undefined' && $data.position) ? $data.position : '') +'</p>';
                                                    $html += '</div>';
                                                    $html += '<div class="col-8">';
                                                        $html += '<ul class="media-list font-size-13">';
                                                            $html += '<li class="d-flex flex-row align-items-center">';
                                                                $html += '<i class="icon-mail5 text-blue mr-1"></i>';
                                                                $html += '<a href="mailto:">'+ ((typeof $data.employeeemail !== 'undefined' && $data.employeeemail) ? $data.employeeemail : '') +'</a>';
                                                            $html += '</li>';
                                                            $html += '<li class="d-flex flex-row align-items-center mt10">';
                                                                $html += '<i class="icon-mobile text-blue mr-1"></i>';
                                                                $html += '<span>'+ ((typeof $data.employeemobile !== 'undefined' && $data.employeemobile) ? $data.employeemobile : '') +'</span>';
                                                            $html += '</li>';
                                                            $html += '<li class="d-flex flex-row align-items-center mt10">';
                                                                $html += '<i class="icon-location3 text-blue mr-1"></i>';
                                                                $html += '<span class="line-height-normal">'+ ((typeof $data.departmentname !== 'undefined' && $data.departmentname) ? $data.departmentname: '') +'</span>';
                                                            $html += '</li>';
                                                        $html += '</ul>';
                                                    $html += '</div>';
                                                $html += '</div>';
                                            $('body').find('#' + api1['_id']).find('.qtip-content').html($html);
                                        }
                                    });
                                }
                            },
                            position:{
                                effect:!1,at:"center left",
                                my: "right center",
                            },
                            show:{effect:!1,delay:500},
                            hide:{effect:!1,fixed:!0,delay:70},
                            style:{classes:"qtip-bootstrap",width:450,tip:{width:12,height:7}}
                        });
                    });

                    Core.unblockUI('.main-content-<?php echo $this->uniqId ?>');
                });
            },
            error: function () {
                Core.unblockUI('.main-content-<?php echo $this->uniqId ?>');
            }
        });

    }
    
    function saveComment_<?php echo $this->uniqId ?>(element, type) {
        var $this = $(element);
        var commentId = '0', userId = '0';
        var text = $this.closest('div.card-body').find('textarea').val();
        var postId = $('.communication-<?php echo $this->uniqId; ?>').attr("data-post-id");
        
        if (type == 'reply') {
            
            commentId = $this.attr('data-comment-save');
            userId = $this.attr('data-comment-user');
            text = $this.closest('div[data-path="reply-tag"]').find('textarea').val();
            
        }
        
        if (text === '') {
            PNotify.removeAll();
            new PNotify({
                title: 'Анхаар',
                text: 'Сэтгэгдэл бичих хэсэгт утга оруулна уу',
                type: 'error',
                sticker: false
            });
            return;
        }

        $.ajax({
            url: 'government/saveIntanetComment',
            dataType: 'JSON',
            type: 'POST',
            data: {
                postId: postId,
                commentType: type,
                commentText: text,
                commentId: commentId,
                userId: userId
            },
            beforeSend: function () {
                Core.blockUI({
                    message: 'Уншиж байна түр хүлээнэ үү...',
                    boxed: true
                });
            },
            success: function (result) {
                if (result) {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Амжилттай',
                        text: 'Амжилттай хадгалагдлаа',
                        type: 'success',
                        sticker: false
                    });
                    $("#comment_writing").val('');
                    $("#comment_writing1").val('');
                    $('body').find('.intranet-<?php echo $this->uniqId; ?>').find('.replycomment-<?php echo $this->uniqId; ?>').empty();
                    getComments_<?php echo $this->uniqId ?>(postId);
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Алдаа',
                        text: 'Алдаа',
                        type: 'error',
                        sticker: false
                    });
                }
                Core.unblockUI();
            }
        });
    }

    function like_<?php echo $this->uniqId ?>(id, post, liketype) {
        $.ajax({
            url: 'government/saveLike',
            type: 'post',
            data: {
                postId: id,
                likeTypeId: liketype,
                targetType: post
            },
            dataType: 'JSON',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Уншиж байна түр хүлээнэ үү...',
                    boxed: true
                });
            },
            success: function (result) {
                var postid = $("#post_id").val();
                getComments_<?php echo $this->uniqId ?>(postid);
                getIntervalData(postid);
                if (!result) {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Алдаа',
                        text: 'Алдаа',
                        type: 'danger',
                        sticker: false
                    });
                }

                Core.unblockUI();
            },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }

                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: msg,
                    type: 'error',
                    sticker: false
                });

                Core.unblockUI();
            }
        });
    }

    function getLike_<?php echo $this->uniqId ?>(id, count, type, post, modalId) {
        //type 1 like ,type 2 dislike

        $.ajax({
            url: 'government/getLikeInformation',
            type: 'post',
            data: {commentId: id, targetType: post},
            dataType: 'JSON',
            success: function (response) {
                var likehtml = '', dislikehtml = '';
                var noimage = "'assets/core/global/img/user.png'";
                var $result = response['result'];

                switch (post) {
                    case 2:
                        $.each($result.scl_comment_like_list, function (i, data) {
                            if (data.liketype == 'Like') {
                                likehtml += '<li class="media">' +
                                        '<div class="mr-3">' +
                                        '<img src="" width="36" height="36" class="rounded-circle" alt="" onerror="onNCUserImgError(this);">' +
                                        '</div>' +
                                        '<div class="media-body">' +
                                        '<a href="javascript:void(0);" class="media-title font-weight-semibold">' + data.name + '</a>' +
                                        '<span class="d-block text-muted font-size-sm">' + data.createddate + '</span>' +
                                        '</div>' +
                                        '<div class="ml-3 align-self-center"><i class="icon-thumbs-up2 text-success mr-1"></i></div>' +
                                        '</li>';
                            } else {
                                dislikehtml += '<li class="media">' +
                                        '<div class="mr-3">' +
                                        '<img src="" width="36" height="36" class="rounded-circle" alt="" onerror="onNCUserImgError(this);">' +
                                        '</div>' +
                                        '<div class="media-body">' +
                                        '<a href="javascript:void(0);" class="media-title font-weight-semibold">' + data.name + '</a>' +
                                        '<span class="d-block text-muted font-size-sm">' + data.createddate + '</span>' +
                                        '</div>' +
                                        '<div class="ml-3 align-self-center"><i class="icon-thumbs-down2 text-danger mr-1"></i></div>' +
                                        '</li>';
                            }
                        });

                        if (modalId === '#modal_default_show_like') {
                            $("#modal_default_show_like").find('.modal-body').empty().append(likehtml).promise().done(function () {
                                $(modalId).modal('show');
                            });
                        } else {
                            $("#modal_default_show_dislike").find('.modal-body').empty().append(dislikehtml).promise().done(function () {
                                $(modalId).modal('show');
                            });
                        }

                        break;

                    default:
                        $.each($result.scl_comment_reply_like_list, function (i, data) {
                            if (data.liketype == 'Like') {
                                likehtml += '<li class="media">' +
                                        '<div class="mr-3">' +
                                        '<img src="" width="36" height="36" class="rounded-circle" alt="">' +
                                        '</div>' +
                                        '<div class="media-body">' +
                                        '<a href="javascript:void(0);" class="media-title font-weight-semibold">' + data.name + '</a>' +
                                        '<span class="d-block text-muted font-size-sm">' + data.createddate + '</span>' +
                                        '</div>' +
                                        '<div class="ml-3 align-self-center"><i class="icon-thumbs-up2 text-success mr-1"></i></div>' +
                                        '</li>';
                            } else {
                                dislikehtml += '<li class="media">' +
                                        '<div class="mr-3">' +
                                        '<img src="" width="36" height="36" class="rounded-circle" alt="">' +
                                        '</div>' +
                                        '<div class="media-body">' +
                                        '<a href="javascript:void(0);" class="media-title font-weight-semibold">' + data.name + '</a>' +
                                        '<span class="d-block text-muted font-size-sm">' + data.createddate + '</span>' +
                                        '</div>' +
                                        '<div class="ml-3 align-self-center"><i class="icon-thumbs-down2 text-danger mr-1"></i></div>' +
                                        '</li>';
                            }
                        });

                        if (modalId === '#modal_default_show_like') {
                            $("#modal_default_show_like").find('.modal-body').empty().append(likehtml).promise().done(function () {
                                $(modalId).modal('show');
                            });
                        } else {
                            $("#modal_default_show_dislike").find('.modal-body').empty().append(dislikehtml).promise().done(function () {
                                $(modalId).modal('show');
                            });
                        }

                        $(modalId).modal('show');
                        break;
                }
            }
        });
    }

    function getVoting(id) {
        $.ajax({
            url: 'government/getIntranetVoting',
            type: 'post',
            data: {postId: id},
            dataType: 'JSON',
            success: function (result) {
                var question_html = '';
                var answer_html = '';

                $.each(result.scl_posts_sanal_asuult_list, function (i, question) {
                    if (question.answertypeid === '1') {
                        $.each(question.scl_posts_sanal_hariult_list, function (j, answer) {
                            answer_html += '<input type="radio" name="' + answer.pollquestionid + '" value=' + answer.id + '> ' + answer.answertxt + '<br>';
                        });
                    }

                    if (question.answertypeid === '2') {
                        answer_html += '<select class="form-control" name="' + question.id + '">' +
                                '<option value="" disabled selected>Сонгох</option>';
                        $.each(question.scl_posts_sanal_hariult_list, function (j, answer) {
                            answer_html += '<option value="' + answer.id + '">' + answer.answertxt + '</option>';
                        });
                        answer_html += '</select>';
                    }

                    question_html += '<div class="card mb-2 p-3">' +
                            '<div class="card-header">' +
                            ' <h6 class="card-title">' +
                            '<a class="text-default" data-toggle="collapse" href="#question' + question.id + '" aria-expanded="true">' + question.questiontxt + '</a>' +
                            '</h6>' +
                            '</div>' +
                            '<div id="question' + question.id + '" class="collapse show" style="">' +
                            '<div class="table-responsive">' +
                            '<table class="table">' +
                            '<tbody>' + answer_html + '</tbody>' +
                            '</table>' +
                            '</div>' +
                            '</div>' +
                            '</div>';

                    answer_html = '';

                });

                $("#questionbody_<?php echo $this->uniqId ?>").empty().append(question_html);
            }
        });
    }

    function savePoll_<?php echo $this->uniqId ?>() {
        var $processForm = $("#savePollForm_<?php echo $this->uniqId ?>");
        var $ticket = true;
        
        if ($processForm.find('input[required="required"]').length > 0) {
            $processForm.find('input[required="required"]').each(function (index, row) {
                var $inputSelector = $(row);
                if ($inputSelector.val() === '1') {
                    $inputSelector.parent().removeAttr('style');
                } else {
                    $ticket = false;
                    $inputSelector.parent().attr('style', 'border: 1px solid #ff000036 !important');
                }
            });
        }
        
        //$processForm.validate({errorPlacement: function () {}});
        //$processForm.valid()
        if ($ticket) {
            $processForm.ajaxSubmit({
                type: 'post',
                url: 'government/savePoll',
                dataType: 'json',
                beforeSend: function () {
                    blockContent_<?php echo $this->uniqId ?>('.intranet-<?php echo $this->uniqId ?>');
                },
                success: function (response) {
                    PNotify.removeAll();
                    new PNotify({
                        title: response.status,
                        text: response.text,
                        type: response.status,
                        sticker: false
                    });

                    if (response.status === 'success') {
                        getNewsContent_<?php echo $this->uniqId ?>($processForm.find('input[name="postid"]').val());
                    }

                    Core.unblockUI('.intranet-<?php echo $this->uniqId ?>');
                },
                error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }

                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: msg,
                        type: 'error',
                        sticker: false
                    });

                    Core.unblockUI('.intranet-<?php echo $this->uniqId ?>');
                }
            });
        } else {
            PNotify.removeAll();
            new PNotify({
                title: 'Анхааруулга',
                text: 'Заавал бөглөх талбарыг бөглөнө үү',
                type: 'warning',
                sticker: false
            });
        }
        return;

        var test = document.getElementById("#savePollForm_<?php echo $this->uniqId ?>").elements;
        var postId = $("#post_id").val();
        var polldata = [];

        for (i = 0; i < test.length; i++) {
            if ((test[i].nodeName === "INPUT" && test[i].checked === true) || test[i].nodeName === "SELECT") {
                polldata.push({post_id: postId, question_id: test[i].name, answer_id: test[i].value});
            }
        }

        $.ajax({
            url: 'government/saveIntranetPoll',
            type: 'post',
            data: {polldata: polldata},
            success: function (result) {
                var res = JSON.parse(result);
                if (res.status === 'success') {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Амжилттай',
                        text: 'Амжилттай хадгалагдлаа',
                        type: 'success',
                        sticker: false
                    });
                    $("#questionbody_<?php echo $this->uniqId ?>").empty().append('<center><h2 class="text-success"> Амжилттай! Таны өгсөн саналыг хүлээн авлаа </h2></center>');
                    getPollAttept(postId);
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Анхаар',
                        text: 'Алдаа гарлаа',
                        type: 'error',
                        sticker: false
                    });
                }
            }
        });

    }

    function ischeckedFnc(element) {
        if (element.checked) {
            $(element).closest('li').addClass('checked-data');
            $("#rowsDeleteButton").show();
        } else {
            $(element).closest('li').removeClass('checked-data');
        }

        var mainSelector = $("#all-content").find('.checked-data');

        if (mainSelector.length === 0 || mainSelector.length <= 1) {
            $("#rowsDeleteButton").hide();
        }
    }

    function postSelect() {
        var test = document.getElementById("all-content-form").elements;
        var polldata = [];
        var mainSelector = $("#all-content").find('.checked-data');

        mainSelector.each(function (index, row) {
            var rowVal = $(row).attr('data-id');
            polldata.push({'id': rowVal});
        });

        if (polldata) {
            runIsMultiBusinessProcess(1564662242403286, 1568017432968, true, polldata, function () {
                mainSelector.each(function (index, row) {
                    $(row).fadeOut(1000, function () {
                        $(this).remove();
                    });
                });
            });
        }
    }

    function getPollAttept(id) {
        $.ajax({
            url: 'government/getIntranetPollAttept',
            type: 'post',
            data: {postId: id},
            dataType: 'JSON',
            success: function (result) {
                if (result) {
                    $.ajax({
                        url: 'government/getIntranetPollResult',
                        type: 'post',
                        data: {postId: id},
                        dataType: 'JSON',
                        success: function (result) {
                            $("#questionbody_<?php echo $this->uniqId ?>").empty().append('<center><h4 class="text-primary">  Та энэ санал асуулгыг өмнө нь өгсөн байна. Доорх үр дүнтэй танилцана уу </h4></center>');

                            var html = '<center><div class="col-6">' +
                                    '<div class="table-responsive mb-4">' +
                                    '<table class="table table-striped table-borderless">' +
                                    '<tbody>' +
                                    '<tr>' +
                                    '<td class="text-right text-gray">Санал өгсөн хүний тоо:</td>' +
                                    '<td class="font-weight-bold">' + result.votedcount + ' санал</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                    '<td class="text-right text-gray">Нууцлалын төрөл:</td>' +
                                    '<td class="font-weight-bold">' + result.privacytype + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                    '<td class="text-right text-gray">Дуусах хугацаа:</td>' +
                                    '<td class="font-weight-bold">' + result.enddate + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                    '<td class="text-right text-gray">Үлдсэн хоног:</td>' +
                                    '<td class="font-weight-bold">' + result.leftdays + '</td>' +
                                    '</tr>' +
                                    '</tbody>' +
                                    '</table>' +
                                    '</div>';



                            var tableHtml = '';
                            var num = 0;
                            var modalHtml = '';
                            $.each(result.scl_answered_count_list, function (index, row) {
                                num++;

                                tableHtml += '<div class="poll_result">' +
                                        '<div class="poll-box">' +
                                        '<div class="d-flex">' +
                                        '<div class="mr-1">' +
                                        '<h5 class="mb-0 font-weight-bold">' + row.answertxt + '</h5>' +
                                        '</div>' +
                                        '<div class="">' +
                                        '<h5 class="pt6 mb-0 text-gray text-uppercase font-size-12 font-weight-bold">(' + row.countedfor + ' саналтай)&nbsp;&nbsp;<a href="javascript:;" data-toggle="modal" data-target="#modal_poll_people' + row.answerid + '">Оролцогчид</a></h5>' +
                                        '</div>' +
                                        '<div class="ml-auto">' +
                                        '<h5 class="mb-0 font-weight-bold">' + row.countforpercent + '%</h5>' +
                                        '</div>' +
                                        '</div>' +
                                        '<div class="progress mb-3" style="height: 0.625rem;">' +
                                        '<div class="progress-bar progress-bar-striped bg-warning" style="width: ' + row.countforpercent + '%">' +
                                        '<span class="sr-only">' + row.countforpercent + '% Complete</span>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>';


                                var userHtml = '';
                                $.each(row.scl_answered_name_get_list, function (j, drow) {
                                    userHtml += '<li class="media">' +
                                            '<div class="mr-3">' +
                                            '<img src="' + drow.picture + '" width="36" height="36" class="rounded-circle" alt="1" onerror="onNCUserImgError(this);">' +
                                            '</div>' +
                                            '<div class="media-body">' +
                                            '<a href="javascript:void(0);" class="media-title font-weight-semibold">' + drow.name + '</a>' +
                                            '<span class="d-block text-muted font-size-sm"></span>' +
                                            '</div>' +
                                            '</li>';
                                });

                                modalHtml = '<div id="modal_poll_people' + row.answerid + '" class="modal fade" tabindex="-1">' +
                                        '<div class="modal-dialog mini-dialog">' +
                                        '<div class="modal-content">' +
                                        '<div class="modal-header">' +
                                        '<h5 class="modal-title">Санал өгсөн хүмүүс</h5>' +
                                        '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
                                        '</div>' +
                                        '<div class="modal-body">' +
                                        userHtml +
                                        '</div>' +
                                        '<div class="modal-footer">' +
                                        '<button type="button" class="btn btn-primary" data-dismiss="modal">Хаах</button>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>';

                                $("#pollmodal").append(modalHtml);
                            });

                            html += tableHtml + '</div></center>';

                            $("#questionbody_<?php echo $this->uniqId ?>").append(html);

                        }
                    });
                } else {
                    getVoting(id);
                }
            }
        });
    }

    function addProcess() {
        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: {
                metaDataId: 1567154435267,
                isDialog: false,
                isHeaderName: false,
                isBackBtnIgnore: 1,
                callerType: 'dv',
                openParams: '{"callerType":"dv","afterSaveNoAction":true,"afterSaveNoActionFnc":"panelDvRefreshSecondList(1567578460751687)"}'
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {
                $("#body").empty().append(data.Html);
                Core.unblockUI();
            },
            error: function () {
                alert('Error');
            }
        });
    }

    function addFolder<?php echo $this->uniqId ?>() {
        var rowData = firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').data('rowdata');
        if (firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').parent().find('.dv-twocol-f-sub-selected').length) {
            rowData = firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').parent().find('.dv-twocol-f-sub-selected').data('rowdata');
        }
        
        if (typeof rowData !== 'object') {
            rowData = JSON.parse(rowData);
        }
        
        var processId = '1567584412244';
        if (processId) {

            var $dialogName = 'dialog-businessprocess-' + processId;
            if (!$('#' + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);

            var fillDataParams = '';
            fillDataParams = 'typeId=' + rowData.posttypeid + '&parentId=' + rowData.postcategoryid;
            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: processId,
                    isDialog: true,
                    isHeaderName: false,
                    isBackBtnIgnore: 1,
                    callerType: 'dv',
                    openParams: '{"callerType":"dv","afterSaveNoAction":true,"afterSaveNoActionFnc":"panelDvRefreshSecondList(<?php echo $this->uniqId; ?>)"}',
                    fillDataParams: fillDataParams
                },
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function (data) {

                    $dialog.empty().append(data.Html);

                    var $processForm = $('#wsForm', '#' + $dialogName),
                            processUniqId = $processForm.parent().attr('data-bp-uniq-id');

                    var buttons = [
                        {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                                if (window['processBeforeSave_' + processUniqId]($(e.target))) {

                                    $processForm.validate({
                                        ignore: '',
                                        highlight: function (element) {
                                            $(element).addClass('error');
                                            $(element).parent().addClass('error');
                                            if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                                $processForm.find("div.tab-pane:hidden:has(.error)").each(function (index, tab) {
                                                    var tabId = $(tab).attr('id');
                                                    $processForm.find('a[href="#' + tabId + '"]').tab('show');
                                                });
                                            }
                                        },
                                        unhighlight: function (element) {
                                            $(element).removeClass('error');
                                            $(element).parent().removeClass('error');
                                        },
                                        errorPlacement: function () {
                                        }
                                    });

                                    var isValidPattern = initBusinessProcessMaskEvent($processForm);

                                    if ($processForm.valid() && isValidPattern.length === 0) {
                                        $processForm.ajaxSubmit({
                                            type: 'post',
                                            url: 'mdwebservice/runProcess',
                                            dataType: 'json',
                                            beforeSend: function () {
                                                Core.blockUI({
                                                    boxed: true,
                                                    message: 'Түр хүлээнэ үү'
                                                });
                                            },
                                            success: function (responseData) {
                                                PNotify.removeAll();
                                                new PNotify({
                                                    title: responseData.status,
                                                    text: responseData.message,
                                                    type: responseData.status,
                                                    sticker: false
                                                });

                                                if (responseData.status === 'success') {
                                                    var $resultData = responseData.resultData;
                                                    $dialog.dialog('close');
                                                    reloadmenu_<?php echo $this->uniqId; ?>($('.menu_<?php echo $this->uniqId ?>').find('li[id="menu_<?php echo $this->uniqId ?>_' + $resultData.typeid + '"]').index());
                                                }
                                                Core.unblockUI();
                                            },
                                            error: function () {
                                                alert("Error");
                                            }
                                        });
                                    }
                                }
                            }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {

                                $dialog.dialog('close');
                            }}
                    ];
                    var dialogWidth = data.dialogWidth, dialogHeight = data.dialogHeight;

                    if (data.isDialogSize === 'auto') {
                        dialogWidth = 1200;
                        dialogHeight = 'auto';
                    }

                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: dialogWidth,
                        height: dialogHeight,
                        modal: true,
                        closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape),
                        close: function () {
                            $dialog.empty().dialog('destroy').remove();
                        },
                        buttons: buttons
                    }).dialogExtend({
                        "closable": true,
                        "maximizable": true,
                        "minimizable": true,
                        "collapsable": true,
                        "dblclick": "maximize",
                        "minimizeLocation": "left",
                        "icons": {
                            "close": "ui-icon-circle-close",
                            "maximize": "ui-icon-extlink",
                            "minimize": "ui-icon-minus",
                            "collapse": "ui-icon-triangle-1-s",
                            "restore": "ui-icon-newwin"
                        }
                    });

                    if (data.dialogSize === 'fullscreen') {
                        $dialog.dialogExtend("maximize");
                    }

                    setTimeout(function () {
                        $dialog.dialog('open');
                        Core.unblockUI();
                    }, 1);
                },
                error: function () {
                    alert('Error');
                }
            });
        }
    }

    function blockContent_<?php echo $this->uniqId ?>(mainSelector) {
        $(mainSelector).block({
            message: '<i class="icon-spinner4 spinner"></i>',
            centerX: 0,
            centerY: 0,
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                width: 16,
                top: '15px',
                left: '',
                right: '15px',
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
    }

    function onChangeAttachFIleAddMode<?php echo $this->uniqId ?>(input, $type) {
        if ($(input).hasExtension(["png", "gif", "jpeg", "pjpeg", "jpg", "x-png", "bmp", "doc", "docx", "xls", "xlsx", "pdf", "ppt", "pptx", "zip", "rar", "mp3", "mp4"])) {
            var ext = input.value.match(/\.([^\.]+)$/)[1],
                i = 0;
            if (typeof ext !== "undefined") {

                for (i; i < input.files.length; i++) {
                    ext = input.files[i].name.match(/\.([^\.]+)$/)[1];

                    var li = '',
                        fileImgUniqId = Core.getUniqueID('file_img'),
                        fileAUniqId = Core.getUniqueID('file_a'),
                        extension = ext.toLowerCase();
                    var $icon = 'icon-file-pdf';
                    switch (extension) {
                        case 'png':
                        case 'gif':
                        case 'jpeg':
                        case 'pjpeg':
                        case 'jpg':
                        case 'x-png':
                        case 'bmp':
                            $icon = "icon-file-picture text-danger-400";
                            break;
                        case 'zip':
                        case 'rar':
                            $icon = "icon-file-zip text-danger-400";
                            break;
                        case 'mp3':
                            $icon = "icon-file-music text-danger-400";
                            break;
                        case 'mp4':
                            $icon = "icon-file-video text-danger-400";
                            break;
                        case 'doc':
                        case 'docx':
                            $icon = "icon-file-word text-blue-400";
                            break;
                        case 'pdf':
                            $icon = "icon-file-pdf text-danger-400";
                            break;
                        case 'ppt':
                        case 'pptx':
                            $icon = "icon-file-presentation text-danger-400";
                            break;
                        case 'xls':
                        case 'xlsx':
                            $icon = "icon-file-excel text-green-400";
                            break;
                        default:
                            $icon = "icon-file-empty text-danger-400";
                            break;
                    }

                    var $liAfter = '<div class="col-4 pl-0">' +
                            '<li class="list-inline-item w-100">' +
                            '<div class="card bg-light py-1 px-2 mt-2 mb-0">' +
                            '<div class="media my-1">' +
                            '<div class="mr-3 align-self-center">' +
                            '<i class="' + $icon + ' icon-2x top-0"></i>' +
                            '</div>' +
                            '<div class="media-body">' +
                            '<div class="font-weight-semibold">' + input.files[i].name + '</div>' +
                            '<ul class="list-inline list-inline-condensed mb-0">' +
                            '<li class="list-inline-item text-muted">' + formatSizeUnits<?php echo $this->uniqId ?>(input.files[i].size) + '</li>' +
                            '<li class="list-inline-item">' +
                            '<input type="hidden" name="bp_file_name[' + i + ']" class="form-control col-md-12 bp_file_name" placeholder="Тайлбар"/>' +
                            '<a href="javascript:void(0);" id="' + fileAUniqId + '" class="fancybox-button main" data-rel="fancybox-button">' +
                            'Харах' +
                            '</a>' +
                            '</li>' +
                            '</ul>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</li>' +
                            '</div>';

                    var $listViewFile = $('#modal-intranet<?php echo $this->uniqId ?> .list-view-file-new');
                    
                    if (typeof $type !== 'undefined' && $type === '1') {
                        $listViewFile.empty();
                    }
                    
                    $listViewFile.append($liAfter);
                    Core.initFancybox($listViewFile);

                    previewPhotoAddMode<?php echo $this->uniqId ?>(input.files[i], $listViewFile.find('#' + fileImgUniqId), $listViewFile.find('#' + fileAUniqId));

                    initFileContentMenuAddMode<?php echo $this->uniqId ?>();
                }
                if (typeof $type !== 'undefined' && $type === '1') {} else{
                    var $this = $(input), $clone = $this.clone();
                    $this.after($clone).appendTo($('.hiddenFileDiv'));
                }

            }
        } else {
            alert('Файл сонгоно уу.');
            $(input).val('');
        }
    }

    function formatSizeUnits<?php echo $this->uniqId ?>(bytes) {
        if (bytes >= 1073741824) {
            bytes = (bytes / 1073741824).toFixed(2) + " gb";
        } else if (bytes >= 1048576) {
            bytes = (bytes / 1048576).toFixed(2) + " mb";
        } else if (bytes >= 1024) {
            bytes = (bytes / 1024).toFixed(2) + " kb";
        } else if (bytes > 1) {
            bytes = bytes + " bytes";
        } else if (bytes == 1) {
            bytes = bytes + " byte";
        } else {
            bytes = "0 bytes";
        }
        return bytes;
    }

    function previewPhotoAddMode<?php echo $this->uniqId ?>(input, $targetImg, $targetAnchor) {
        if (input) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $targetImg.attr('src', e.target.result);
                $targetAnchor.attr('href', e.target.result);
            };
            reader.readAsDataURL(input);
        }
    }

    function initFileContentMenuAddMode<?php echo $this->uniqId ?>() {
        $.contextMenu({
            selector: '#modal_theme_primary_<?php echo $this->uniqId ?> ul.list-view-file-new li',
            callback: function (key, opt) {
                if (key === 'delete') {
                    deleteBpTabFileAddMode(opt.$trigger);
                }
            },
            items: {
                "delete": {name: "Устгах", icon: "trash"}
            }
        });
    }

    function fileContent_<?php echo $this->uniqId ?>(data) {
        $('.fileviewer-<?php echo $this->uniqId ?>').empty().append(data.html).promise().done(function () {
            Core.unblockUI();
        });
    }

    function editPost_<?php echo $this->uniqId; ?>(id, element) {
        var $dataRow = JSON.parse($(element).find('a').attr('data-rowdata'));

        var $rowData = firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').data('rowdata');
        if (firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').parent().find('.dv-twocol-f-sub-selected').length) {
            $rowData = firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').parent().find('.dv-twocol-f-sub-selected').data('rowdata');
        }
        
        if (typeof $rowData !== 'object') {
            $rowData = JSON.parse($rowData);
        }
        
        var $modalId_<?php echo $this->uniqId ?> = 'modal-intranet<?php echo $this->uniqId ?>';

        $.ajax({
            url: 'government/editPost',
            type: 'post',
            dataType: 'JSON',
            data: {
                postId: id,
                selectedRow: $dataRow,
                dataRow: $rowData,
                uniqId: '<?php echo $this->uniqId ?>'
            },
            success: function (data) {
                var title = 'Засах';
                var isconfirmed = '1';
                if ($rowData.windowtypeid) {
                    switch ($rowData.windowtypeid) {
                        case '2':
                            $('<div id="' + $modalId_<?php echo $this->uniqId ?> + '" class="modal intranet_sent_email fade" tabindex="-1" role="dialog">' +
                                    '<div class="modal-dialog modal-lg">' +
                                    '<div class="modal-content modalcontent<?php echo $this->uniqId ?>">' +
                                    '<div class="modal-header">' +
                                    '<h5 class="modal-title">Санал асуулга нэмэх</h5>' +
                                    '<button type="button" class="close" onclick="close_<?php echo $this->uniqId ?>()" style="filter: inherit;">&times;</button>' +
                                    '</div>' +
                                    '<div class="modal-body pt-2">' +
                                    data.Html +
                                    '</div>' +
                                    '<div class="border-top-1 border-gray">' +
                                    '<div class="modal-footer pt-2 pb-2">' +
                                    '<button type="button" class="btn send-btn-<?php echo $this->uniqId ?>" onclick="save_<?php echo $this->uniqId ?>(\'.modalcontent<?php echo $this->uniqId ?>\')">Хадгалах</button>' +
                                    '<button type="button" class="btn btn-link" onclick="close_<?php echo $this->uniqId ?>()">Хаах</button>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>').appendTo('body');
                            var $dialog = $('#' + $modalId_<?php echo $this->uniqId ?>);

                            $dialog.modal({
                                show: false,
                                keyboard: false,
                                backdrop: 'static'
                            });

                            $dialog.on('shown.bs.modal', function () {
                                setTimeout(function () {

                                }, 10);
                                disableScrolling();
                            });

                            $dialog.on('hidden.bs.modal', function () {
                                $dialog.remove();
                                enableScrolling();
                            });

                            $dialog.modal('show');
                            Core.initAjax($dialog);

                            $dialog.find('.modal-backdrop').remove();

                            break;
                        case '1':
                        case '3':
                        case '4':
                        case '5': //СУРГАЛТ
                            $('<div id="' + $modalId_<?php echo $this->uniqId ?> + '" class="modal intranet_sent_email fade" tabindex="-1" role="dialog">' +
                                    '<div class="modal-dialog modal-lg">' +
                                    '<div class="modal-content modalcontent<?php echo $this->uniqId ?>">' +
                                    '<div class="modal-header">' +
                                    '<h6 class="modal-title">' + title + '</h6>' +
                                    '<button type="button" class="close" onclick="close_<?php echo $this->uniqId ?>()" style="filter: inherit;">&times;</button>' +
                                    '</div>' +
                                    '<div class="modal-body">' +
                                    data.Html +
                                    '</div>' +
                                    '<div class="modal-footer">' +
                                    '<button type="button" class="btn send-btn-<?php echo $this->uniqId ?>" onclick="save_<?php echo $this->uniqId ?>(\'.modalcontent<?php echo $this->uniqId ?>\')">Хадгалах</button>' +
                                    '<button type="button" class="btn btn-link" onclick="close_<?php echo $this->uniqId ?>()">Хаах</button>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>').appendTo('body');

                            var $dialog = $('#' + $modalId_<?php echo $this->uniqId ?>);

                            $dialog.modal({
                                show: false,
                                keyboard: false,
                                backdrop: 'static'
                            });

                            $dialog.on('shown.bs.modal', function () {
                                setTimeout(function () {

                                }, 10);
                                disableScrolling();
                            });

                            $dialog.on('hidden.bs.modal', function () {
                                $dialog.remove();
                                enableScrolling();
                            });

                            if ($rowData.windowtypeid == '1' || $rowData.windowtypeid == '4') {
                                if (typeof tinymce === 'undefined') {
                                    $.getScript(URL_APP + 'assets/custom/addon/plugins/tinymce/tinymce.min.js', function () {
                                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/autocomplete.css"/>');
                                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/rte-content.css"/>');
                                        $.getScript(URL_APP + 'assets/custom/addon/plugins/tinymce/plugins/mention/plugin.min.js').done(function (script, textStatus) {
                                            initTinyMceEditor_<?php echo $this->uniqId ?>('#body_<?php echo $this->uniqId ?>', '520');
                                            $dialog.modal('show');
                                        });
                                    });
                                } else {
                                    initTinyMceEditor_<?php echo $this->uniqId ?>('#body_<?php echo $this->uniqId ?>', '520');
                                    $dialog.modal('show');
                                }
                            } else {
                                $dialog.modal('show');
                            }

                            Core.initAjax($dialog);
                            $dialog.find('.modal-backdrop').remove();
                            break;
                        case '9988':

                            $('<div id="' + $modalId_<?php echo $this->uniqId ?> + '" class="modal intranet_sent_email fade" tabindex="-1" role="dialog">' +
                                    '<div class="modal-dialog modal-lg">' +
                                    '<div class="modal-content modalcontent<?php echo $this->uniqId ?>">' +
                                    '<div class="modal-header">' +
                                    '<h6 class="modal-title">Бүлгэм</h6>' +
                                    '<button type="button" class="close" onclick="close_<?php echo $this->uniqId ?>('+ isconfirmed +')" style="filter: inherit;">&times;</button>' +
                                    '</div>' +
                                    '<div class="modal-body">' +
                                    data.Html +
                                    '</div>' +
                                    '<div class="modal-footer">' +
                                    '<button type="button" class="btn send-btn-<?php echo $this->uniqId ?>" onclick="save_<?php echo $this->uniqId ?>(\'.modalcontent<?php echo $this->uniqId ?>\')">Хадгалах</button>' +
                                    '<button type="button" class="btn btn-link" onclick="close_<?php echo $this->uniqId ?>('+ isconfirmed +')">Хаах</button>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>').appendTo('body');

                            var $dialog = $('#' + $modalId_<?php echo $this->uniqId ?>);

                            $dialog.modal({
                                show: false,
                                keyboard: false,
                                backdrop: 'static'
                            });

                            $dialog.on('shown.bs.modal', function () {
                                setTimeout(function () {

                                }, 10);
                                disableScrolling();
                            });
                            
                            $dialog.draggable({
                                handle: ".modal-header"
                            });
                            
                            $dialog.on('hidden.bs.modal', function () {
                                $dialog.remove();
                                enableScrolling();
                            });
                            
                            if ($rowData.windowtypeid == '1') {
                                if (typeof tinymce === 'undefined') {
                                    $.getScript(URL_APP + 'assets/custom/addon/plugins/tinymce/tinymce.min.js', function () {
                                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/autocomplete.css"/>');
                                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/rte-content.css"/>');
                                        $.getScript(URL_APP + 'assets/custom/addon/plugins/tinymce/plugins/mention/plugin.min.js').done(function (script, textStatus) {
                                            initTinyMceEditor_<?php echo $this->uniqId ?>('#body_<?php echo $this->uniqId ?>', '520');
                                            $dialog.modal('show');
                                        });
                                    });
                                } else {
                                    initTinyMceEditor_<?php echo $this->uniqId ?>('#body_<?php echo $this->uniqId ?>', '520');
                                    $dialog.modal('show');
                                }
                            } else {
                                $dialog.modal('show');
                            }

                            Core.initAjax($dialog);
                            $dialog.find('.modal-backdrop').remove();
                            break;
                        default:
                            break;
                    }
                }
                
                Core.unblockUI('.intranet-<?php echo $this->uniqId ?>');
            },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }

                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: msg,
                    type: 'error',
                    sticker: false
                });
                if (typeof element !== 'undefined') {
                    Core.unblockUI(element);
                } else {
                    Core.unblockUI();
                }
            }
        });

    }

    function deletePost_<?php echo $this->uniqId; ?>(id, element) {
        var $dataRow = JSON.parse($(element).find('a').attr('data-rowdata'));

        $.ajax({
            url: 'government/deletePost',
            type: 'post',
            data: {
                postId: id,
                dataRow: $dataRow
            },
            dataType: 'JSON',
            success: function (result) {
                firstcontentclicked = 0;
                reloadmenu_<?php echo $this->uniqId ?>(firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').parent().index());
            },
            error: function () {
                firstcontentclicked = 0;
                reloadmenu_<?php echo $this->uniqId ?>(firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').parent().index());
            }
        });
    }

    function readContent_<?php echo $this->uniqId ?>(id, selectedRow) {
        $.ajax({
            url: 'government/readContent',
            type: 'post',
            data: {
                postId: id,
                dataRow: selectedRow
            },
            dataType: 'JSON',
            success: function (result) {
                appendList_<?php echo $this->uniqId; ?>.find('li[data-id="' + id + '"]').find('.font-weight-bold').removeClass('font-weight-bold');
                appendList_<?php echo $this->uniqId; ?>.find('li[data-id="' + id + '"]').removeClass('unread');

                var $activeelement = $('.menu_<?php echo $this->uniqId ?> li.dv-twocol-f-selected');
                if ($activeelement.find('span.badge-pill')) {
                    var $len = parseInt($activeelement.find('span.badge-pill').html());
                    if ($len > 0) {
                        $activeelement.find('span.badge-pill').text($len - 1);
                        if ($len - 1 <= 0) {
                            $activeelement.find('span.badge-pill').hide();
                        }
                    }
                }
            }
        });
    }

    function removecontent_<?php echo $this->uniqId ?>(element) {
        var $element = $(element);
        var $contentTag = $element.closest('.filecontent-tag');

        $contentTag.fadeOut("slow", function () {
            $contentTag.remove();
        });
    }

    function fileview_<?php echo $this->uniqId ?>(element, $contentId) {
        var $this = $(element);
        var $mainSelector = $this.closest('.filelibrarybody');
        $mainSelector.find('.fileviewer').empty().append($mainSelector.find('.fileviewcontent_' + $contentId).html());
        
        $.ajax({
            url: 'government/contentViewUser',
            type: 'post',
            data: {contentId: $contentId, type: '1'},
            dataType: 'JSON',
            beforeSend: function () {},
            success: function (data) {}
        });
    }

    function search<?php echo $this->uniqId ?>() {
        var value = $("#search").val();
        var $this = firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected');
        var $rowData = $this.data('rowdata');
        
        if (typeof $rowData !== 'object') {
            $rowData = JSON.parse($rowData);
        }
        
        if (value !== '') {
            $.ajax({
                url: 'government/getsecondContent',
                type: 'post',
                data: {
                    searchValue: value, 
                    uniqId: '<?php echo $this->uniqId ?>',
                    mainRow: $rowData,
                },
                dataType: 'JSON',
                beforeSend: function () {
                    blockContent_<?php echo $this->uniqId ?>('.all-content-<?php echo $this->uniqId ?>:not(#all-unread-content)');
                },
                success: function (data) {
                    appendList_<?php echo $this->uniqId; ?>.empty().append(data.Html).promise().done(function () {
                        if (!firstcontentclicked && $('body').find('.all-content-<?php echo $this->uniqId ?>:not(#all-unread-content) li:not(".unread"):eq(0)').length > 0) {
                            firstcontentclicked = 1;
                            $('body').find('.all-content-<?php echo $this->uniqId ?>:not(#all-unread-content) li:not(".unread"):eq(0) > a').trigger('click');
                        }
                    });

                    Core.unblockUI('.all-content-<?php echo $this->uniqId ?>:not(#all-unread-content)');
                }
            });
        } else {
            buildOneColSecondPart<?php echo $this->uniqId ?>('<?php echo $this->uniqId; ?>', $this.data('id'), $this);
        }
    }
    
    function renderSocialMedia<?php echo $this->uniqId ?>($mainRow) {
        $.ajax({
            url: 'government/social',
            type: 'post',
            dataType: 'JSON',
            data: {
                uniqId: '<?php echo $this->uniqId ?>',
                mainRow: $mainRow
            },
            beforeSend: function () {
                blockContent_<?php echo $this->uniqId ?>('.main-content-<?php echo $this->uniqId ?>');
            },
            success: function (response) {
                $('.main-content-<?php echo $this->uniqId ?>').empty().append(response.Html).promise().done(function () {
                    //appendList_<?php echo $this->uniqId; ?>.append();
                });
            },
            error: function () {
                Core.unblockUI('.main-content-<?php echo $this->uniqId ?>');
            }
        }).done(function () {
            var $parent  = firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').parent();
            Core.unblockUI('#' + $parent.attr('id'));
        });
    }
    
    function buildOneColSecondPart<?php echo $this->uniqId ?>($uniqId, rowId, $this, $pagination) {
        
        panelDv_<?php echo $this->uniqId; ?>.find('.siderbarsecond<?php echo $this->uniqId ?>').removeClass('d-none');
        
        var rowData = $this.data('rowdata');
        
        if (typeof rowData !== 'object') {
            rowData = JSON.parse(rowData);
        }
        
        if (rowData.posttypeid == '9999' ) {
            $('.siderbarsecond<?php echo $this->uniqId ?>').addClass('d-none');
            $('.main-content-<?php echo $this->uniqId ?>').empty();
            renderSocialMedia<?php echo $this->uniqId ?>(rowData);
            return;
        }
        
        $.ajax({
            url: 'government/getsecondContent',
            type: 'post',
            data: {
                uniqId: '<?php echo $this->uniqId ?>',
                id: $this.hasClass('v2') ? '' : rowData['id'],
                categoryId: rowData['categoryid'],
                mainRow: rowData,
                offset: (typeof $pagination !== 'undefined') ? $pagination : ''
            },
            async: false,
            dataType: 'JSON',
            beforeSend: function () {
                blockContent_<?php echo $this->uniqId ?>('.cardlist-<?php echo $this->uniqId ?>');
            },
            success: function (data) {
                
                if (typeof $pagination === 'undefined') {
                    
                    $totalcount_<?php echo $this->uniqId ?> = data.totalcount;
                    
                    $('.main-content-<?php echo $this->uniqId ?>').empty();
                    
                    appendList_<?php echo $this->uniqId; ?>.empty();
                    appendList_<?php echo $this->uniqId; ?>.append(data.Html).promise().done(function () {

                        if (typeof $pagination === 'undefined') {
                            if (rowData.posttypeid == '9988' ) {
                                Core.unblockUI('.cardlist-<?php echo $this->uniqId ?>');
                                $('.main-content-<?php echo $this->uniqId ?>').empty();
                                appendList_<?php echo $this->uniqId; ?>.find('li:eq(0) > a').trigger('click');
                                return;
                            } else {
                                if ($postContentTypeId && $postContentId) {
                                    getNewsContent_<?php echo $this->uniqId ?>($postContentId);
                                } else {
                                    if (appendList_<?php echo $this->uniqId; ?>.find('li:not(".unread"):eq(0)').length > 0 && (typeof $pagination === 'undefined')) {
                                        appendList_<?php echo $this->uniqId; ?>.find('li:not(".unread"):eq(0) > a').trigger('click');
                                    }
                                }
                                
                            }

                            
                            appendList_<?php echo $this->uniqId; ?>.parent().attr('style',  'overflow: auto; height: 90vh;');
                        }

                        Core.unblockUI('.cardlist-<?php echo $this->uniqId ?>');

                        setTimeout(function () {
                            Core.unblockUI(firstList_<?php echo $this->uniqId; ?>.find('.nav-item'));
                            Core.unblockUI(appendList_<?php echo $this->uniqId; ?>);
                        }, 1000);
                        
                        if ($totalcount_<?php echo $this->uniqId ?> > 50) {
                            var $moreHtml = '';
                            $moreHtml += '<div class="loadmore<?php echo $this->uniqId ?>" style="padding: 2px 10px; text-align: center;">';
                                $moreHtml += '<span class="d-none w-100" style="text-align: center; font-style: italic; padding-bottom: 8px; padding-top: 8px;">Showing recent results...</span>';
                                $moreHtml += '<a class="w-100" href="javascript:;" onclick="panelscrolling<?php echo $this->uniqId ?>()" style="padding-top: 8px; text-decoration: underline;">More</a>';
                            $moreHtml += '</div>';

                            appendList_<?php echo $this->uniqId; ?>.append($moreHtml);
                        }
                    });
                } else {
                
                    if (data) {
                        var $tik = false;
                        $.each(data, function (key, $row) {
                            $tik = true;
                            var $rowJson = htmlentities(JSON.stringify($row), 'ENT_QUOTES', 'UTF-8');
                            
                            var $html = '<li class="'+ (($row['isread'] == '0') ? 'unread' : '') +' dv-twocol-remove-li" data-id="'+ $row['id'] +'">';
                                $html += '<a href="javascript:void(0);" onclick="getNewsContent_<?php echo $this->uniqId ?>(\''+ $row['id'] +'\')" data-second-id="'+ $row['id'] +'" data-secondprocessid="1560141744604" data-secondtypecode="process" class="media d-flex align-items-center justify-content-center" id="edit'+ $row['id'] +'" data-rowdata="'+ $rowJson +'">'
                                    $html += '<span class="line-height-0"></span>';
                                    $html += '<span class="mr-2 text-right" style="width:29px;">';
                                        $html += '<i class="icon-file-text2 text-gray"></i>';
                                    $html += '</span>';
                                    $html += '<div class="media-body">';
                                        $html += '<div id="removal'+ $row['id'] +'" class="media-title d-flex flex-row mb-0" style="line-height: normal;font-size: 12px;">';
                                            $html += '<div class="'+ (($row['isread'] == '0') ? 'font-weight-bold' : '') +'">'+ $row['description'] +'</div>';
                                            if ($row['isattached']) {
                                                $html += '<span class="ml-auto">';
                                                    $html += '<i class="icon-attachment text-gray font-size-15" style="top: 0;"></i>';
                                                $html += '</span>';
                                            } 
                                        $html += '</div>';
                                        $html += '<div class="d-flex justify-content-between">';
                                            $html += '<span class="text-muted font-size-sm mr-1 '+ (($row['isread'] == '0') ? 'font-weight-bold' : '') +'">'+ $row['tsag'] +'</span>';
                                            $html += '<span class="text-muted font-size-sm '+ (($row['isread'] == '0') ? 'font-weight-bold' : '') +'">'+ $row['minute'] +'</span>';
                                        $html += '</div>';
                                    $html += '</div>';
                                $html += '</a>';
                            $html += '</li>';
                            
                            if (appendList_<?php echo $this->uniqId; ?>.find('div[data-target222="'+ $row['timegroupname'] +'"]').length) {
                                appendList_<?php echo $this->uniqId; ?>.find('div[data-source222="'+ $row['timegroupname'] +'"]').append($html);
                            } else {
                                
                                appendList_<?php echo $this->uniqId; ?>.find('div[data-source222="'+ $row['timegroupname'] +'"]');
                                var $guniqId = getUniqueId();
                                
                                var $headhtml = '<div class="date-filter" data-toggle="collapse" href="#collapse'+ $guniqId +'" data-target222="'+ $row['timegroupname'] +'">';
                                    $headhtml += $row['timegroupname'];
                                    $headhtml += '<i class="icon-arrow-down5"></i>';
                                $headhtml += '</div>';
                                $headhtml += '<div class="collapse show" id="collapse'+ $guniqId +'" data-source222="'+ $row['timegroupname'] +'">';
                                $headhtml += $html;
                                $headhtml += '</div>';
                                
                                appendList_<?php echo $this->uniqId; ?>.append($headhtml);
                                
                            }
                            
                        });

                        if (!$tik) {
                            $('.loadmore<?php echo $this->uniqId ?>').hide();
                        }
                    } else {
                        $('.loadmore<?php echo $this->uniqId ?>').hide();
                    }
                        
                    setTimeout(function () {
                    
                        Core.unblockUI(firstList_<?php echo $this->uniqId; ?>.find('.nav-item'));
                        Core.unblockUI(appendList_<?php echo $this->uniqId; ?>);
                        Core.unblockUI('.cardlist-<?php echo $this->uniqId ?>');
                        
                    }, 1000);
                }
                
                if (rowData['name'] !== null) {
                    $(".cardlist-<?php echo $this->uniqId ?> #category_title").text(rowData['name']);
                }
                
            },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }

                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: msg,
                    type: 'error',
                    sticker: false
                });
                
                Core.unblockUI();
            }
        });
    }
    
    function onNCUserImgError(source) {
        source.src = "assets/custom/img/ico/user.png";
        source.onerror = "";
        return true;
    }
    
    function onFiUserImgError(source) {
        source.src = "assets/custom/img/ico/interface.png";
        source.onerror = "";
        return true;
    }
    
    function onFiUserCovError(source) {
        source.src = "assets/custom/img/ico/interface_cover.png";
        source.onerror = "";
        return true;
    }
    
    function modalShowOther<?php echo $this->uniqId ?>(element) {
        var rowJson = $(element).attr('data-rowdata');
        //var $data = JSON.parse(decodeURIComponent(rowJson).replace('\n', ' '));
        var $data = JSON.parse(rowJson);
        
        var $html = '';
        var $counter = 1;
        $html += '<ul id="viewslist" class="media-list  ">';
            $.each($data, function (index, row) {
                $html += '<div class="d-flex align-items-center p-2 w-100">';
                    $html += '<table class="table ">';
                        $html += '<tbody>';
                            $html += '<tr>';
                                $html += '<td class="pl-0 pr-2 py-1" style="width: 50px;">';
                                    $html += '<div class="d-flex align-items-center">';
                                    $html += '<div class="mr-2">';
                                        $html += '<img src="assets/custom/img/ico/user.png" data-comment-dd="1569229434702" class="rounded-circle" width="40" height="40" onerror="onNCUserImgError(this);" data-hasqtip="20" aria-describedby="qtip-20">';
                                    $html += '</div>';
                                $html += '</div>';
                                $html += '</td>';
                                $html += '<td class="pr-2">';
                                    $html += '<div class="media-chat-item line-height-normal" data-comment="Байхгүй">'+ (row.answerdescription ? row.answerdescription : '') +'</div>';
                                $html += '</td>';
                            $html += '</tr>';
                        $html += '</tbody>';
                    $html += '</table>';
                $html += '</div>';
                /* $html += '<li class="media">';
                    $html += '<div class="media-body p-1">';
                        $html += '<span href="javascript:void(0);" class="d-block media-title font-weight-semibold pull-left text-left" style="width: 100px !important /* 30% !important *//* ">' +  $counter + '. ********' +'</span>'; */
                   /*     $html += '<span class="d-block text-muted font-size-sm text-right pull-left" style="width: 70% !important; text-align: justify !important;">'+ (row.answerdescription ? row.answerdescription : '') +'</span>';
                    $html += '</div>';
                $html += '</li>'; */
                $counter++;
            });
        $html += '</ul>';
        
        var $modalId_<?php echo $this->uniqId ?> = 'modal-wordstrue<?php echo $this->uniqId ?>';
        $('<div id="' + $modalId_<?php echo $this->uniqId ?>  +'" class="modal fade" tabindex="-1" role="dialog">' +
                '<div class="modal-dialog app_dashboard">' +
                    '<div class="modal-content intranet modalcontent<?php echo $this->uniqId ?>">' +
                        '<div class="modal-header">' +
                            '<h5 class="modal-title">'+ plang.get('other_desc') +'</h5>' +
                            '<button type="button" class="close" data-dismiss="modal">×</button>' +
                        '</div>' +
                        '<div class="modal-body">' + $html + '</div>' +
                        '<div class="modal-footer">' +
                            '<button type="button" class="btn btn-light" data-dismiss="modal">Хаах</button>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>').appendTo('body');
        var $dialog = $('#' + $modalId_<?php echo $this->uniqId ?>);

        $dialog.modal({
        });

        $dialog.on('hidden.bs.modal', function () {
            $dialog.remove();
            enableScrolling();
        });

        $dialog.modal('show');
        Core.initAjax($dialog);

    }
    
    function viewPost<?php echo $this->uniqId ?>(postId) {
        $.ajax({
            url: 'government/getPostViewData',
            type: 'post',
            data: {
                postId: postId
            },
            dataType: 'JSON',
            success: function (result) {
                var $readData = (typeof result.response !== 'undefined' && typeof result.response['0'] !== 'undefined') ? result.response['0'] : [];
                var $readedData = (typeof result.response !== 'undefined' && typeof result.response['1'] !== 'undefined') ? result.response['1'] : [];
                
                var $html="";
                    $html += '<div class="card-body">';
                        $html += '<ul class="nav nav-tabs nav-tabs-bottom nav-justified">';
                            $html += '<li class="nav-item"><a href="#bt-justified1-tab<?php echo $this->uniqId ?>" class="nav-link active show" data-toggle="tab">Уншсан ('+ $readedData.length +')</a></li>';
                            $html += '<li class="nav-item"><a href="#bt-justified2-tab<?php echo $this->uniqId ?>" class="nav-link" data-toggle="tab">Уншаагүй ('+ $readData.length +')</a></li>';
                        $html += '</ul>';
                        $html += '<div class="tab-content">';
                            $html += '<div class="tab-pane fade active show" id="bt-justified1-tab<?php echo $this->uniqId ?>">';
                                $html += '<ul class="media-list height-scroll" style=" height: calc(100vh - 300px);">';
                                /*
                                $html += '<div class="alert bg-teal text-white alert-styled-left alert-styled-custom alert-dismissible text-center mb-0 p-0 pt-2 pb-2">' +
                                            '<span class="font-weight-semibold">Үзээгүй хэрэглэгчийн тоо : <b>' + result.unseencount + '</b></span>' +
                                        '</div><hr>';
                                */
                                $.each($readedData, function (index, row) {
                                    $html += '<li class="media">' +
                                                '<div class="mr-3">' +
                                                    '<img src="' + row.picture + '" width="36" height="36" class="rounded-circle" alt="' + row.createdusername + '" onerror="onNCUserImgError(this);">' +
                                                '</div>' +
                                                '<div class="media-body p-1">' +
                                                    '<a href="javascript:void(0);" class="media-title font-weight-semibold pull-left" style="width: 40%">' + row.createdusername + '</a>' +
                                                    '<span class="d-block text-muted font-size-sm text-right">' + (row.readdate ? row.readdate : '') + '</span>' +
                                                '</div>'
                                            '</li>';
                                });
                                $html += '</ul>';
                            $html += '</div>';
                            $html += '<div class="tab-pane fade" id="bt-justified2-tab<?php echo $this->uniqId ?>">';
                                $html += '<ul class="media-list height-scroll" style=" height: calc(100vh - 300px);">';
                                $.each($readData, function (index, row) {
                                    $html += '<li class="media">' +
                                                '<div class="mr-3">' +
                                                    '<img src="' + row.picture + '" width="36" height="36" class="rounded-circle" alt="' + row.createdusername + '" onerror="onNCUserImgError(this);">' +
                                                '</div>' +
                                                '<div class="media-body p-1">' +
                                                    '<a href="javascript:void(0);" class="media-title font-weight-semibold pull-left" style="width: 50%">' + row.createdusername + '</a>' +
                                                '</div>'
                                            '</li>';
                                });
                                $html += '</ul>';
                            $html += '</div>';
                        $html += '</div>';
                    $html += '</div>';
                /*var $html = '<ul id="viewslist" class="media-list">';
                $html += '</ul>';
                */
                
                            
                var $modalId_<?php echo $this->uniqId ?> = 'modal-wordstrue<?php echo $this->uniqId ?>';
                $('<div id="' + $modalId_<?php echo $this->uniqId ?>  +'" class="modal fade" tabindex="-1" role="dialog">' +
                        '<div class="modal-dialog">' +
                            '<div class="modal-content modalcontent<?php echo $this->uniqId ?>">' +
                                '<div class="modal-header bg-info" style="padding: 1.25rem 1.25rem;">' +
                                    '<h5 class="modal-title">Үзсэн хэрэглэгчид</h5>' +
                                    '<button type="button" class="close" data-dismiss="modal">×</button>' +
                                '</div>' +
                                '<div class="modal-body">' + $html + '</div>' +
                                '<div class="modal-footer">' +
                                    '<button type="button" class="btn bg-info" data-dismiss="modal" style="padding: .4375rem .875rem;color: #fff !important;border-radius: 5px !important;">Хаах</button>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>').appendTo('body');
            
                var $dialog = $('#' + $modalId_<?php echo $this->uniqId ?>);

                $dialog.modal({});

                $dialog.on('hidden.bs.modal', function () {
                    $dialog.remove();
                    enableScrolling();
                });

                $dialog.modal('show');
                Core.initAjax($dialog);
                
            },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }

                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: msg,
                    type: 'error',
                    sticker: false
                });
                if (typeof element !== 'undefined') {
                    Core.unblockUI(element);
                } else {
                    Core.unblockUI();
                }
            }
        });
    }
    
    function viewFilecontent<?php echo $this->uniqId ?>(contentId, type) {
        $.ajax({
            url: 'government/getPostFileViewData',
            type: 'post',
            data: {
                contentId: contentId
            },
            dataType: 'JSON',
            success: function (result) {
                var $readedData  = (typeof result.viewdtl !== 'undefined') ? result.viewdtl : [];
                var $readData = (typeof result.downloaddtl !== 'undefined') ? result.downloaddtl : [];
                
                var $html="";
                    $html += '<div class="card-body">';
                        $html += '<ul class="nav nav-tabs nav-tabs-bottom nav-justified d-none">';
                            $html += '<li class="nav-item"><a href="#bt-justified1-tab<?php echo $this->uniqId ?>" class="nav-link active show" data-toggle="tab">Үзсэн ('+ $readedData.length +')</a></li>';
                            $html += '<li class="nav-item"><a href="#bt-justified2-tab<?php echo $this->uniqId ?>" class="nav-link" data-toggle="tab">Татсан ('+ $readData.length +')</a></li>';
                        $html += '</ul>';
                        $html += '<div class="tab-content">';
                            $html += '<div class="tab-pane fade '+ (type === 'download' ? 'd-none' : 'active show') +'" id="bt-justified1-tab<?php echo $this->uniqId ?>">';
                                $html += '<ul class="media-list height-scroll" style=" height: calc(100vh - 300px);">';
                                /*
                                $html += '<div class="alert bg-teal text-white alert-styled-left alert-styled-custom alert-dismissible text-center mb-0 p-0 pt-2 pb-2">' +
                                            '<span class="font-weight-semibold">Үзээгүй хэрэглэгчийн тоо : <b>' + result.unseencount + '</b></span>' +
                                        '</div><hr>';
                                */
                                $.each($readedData, function (index, row) {
                                    $html += '<li class="media">' +
                                                '<div class="mr-3">' +
                                                    '<img src="' + row.picture + '" width="36" height="36" class="rounded-circle" alt="' + row.employeename + '" onerror="onNCUserImgError(this);">' +
                                                '</div>' +
                                                '<div class="media-body p-1">' +
                                                    '<a href="javascript:void(0);" class="media-title font-weight-semibold pull-left" style="width: 40%">' + row.employeename + '</a>' +
                                                    '<span class="d-block text-muted font-size-sm text-right">' + (row.createddate ? row.createddate : '') + '</span>' +
                                                '</div>'
                                            '</li>';
                                });
                                $html += '</ul>';
                            $html += '</div>';
                            $html += '<div class="tab-pane fade '+ (type !== 'download' ? 'd-none' : 'active show') +'" id="bt-justified2-tab<?php echo $this->uniqId ?>">';
                                $html += '<ul class="media-list height-scroll" style=" height: calc(100vh - 300px);">';
                                $.each($readData, function (index, row) {
                                    $html += '<li class="media">' +
                                                '<div class="mr-3">' +
                                                    '<img src="' + row.picture + '" width="36" height="36" class="rounded-circle" alt="' + row.employeename + '" onerror="onNCUserImgError(this);">' +
                                                '</div>' +
                                                '<div class="media-body p-1">' +
                                                    '<a href="javascript:void(0);" class="media-title font-weight-semibold pull-left" style="width: 40%">' + row.employeename + '</a>' +
                                                    '<span class="d-block text-muted font-size-sm text-right">' + (row.createddate ? row.createddate : '') + '</span>' +
                                                '</div>'
                                            '</li>';
                                });
                                $html += '</ul>';
                            $html += '</div>';
                        $html += '</div>';
                    $html += '</div>';
                /*var $html = '<ul id="viewslist" class="media-list">';
                $html += '</ul>';
                */
                
                            
                var $modalId_<?php echo $this->uniqId ?> = 'modal-wordstrue<?php echo $this->uniqId ?>';
                $('<div id="' + $modalId_<?php echo $this->uniqId ?>  +'" class="modal fade" tabindex="-1" role="dialog">' +
                        '<div class="modal-dialog">' +
                            '<div class="modal-content modalcontent<?php echo $this->uniqId ?>">' +
                                '<div class="modal-header bg-info" style="padding: 1.25rem 1.25rem;">' +
                                    '<h5 class="modal-title">'+ (type === 'download' ? plang.get('downloadfile_title') : plang.get('viewfile_title')) +'</h5>' +
                                    '<button type="button" class="close" data-dismiss="modal">×</button>' +
                                '</div>' +
                                '<div class="modal-body">' + $html + '</div>' +
                                '<div class="modal-footer">' +
                                    '<button type="button" class="btn bg-info" data-dismiss="modal" style="padding: .4375rem .875rem;color: #fff !important;border-radius: 5px !important;">Хаах</button>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>').appendTo('body');
            
                var $dialog = $('#' + $modalId_<?php echo $this->uniqId ?>);

                $dialog.modal({});

                $dialog.on('hidden.bs.modal', function () {
                    $dialog.remove();
                    enableScrolling();
                });

                $dialog.modal('show');
                Core.initAjax($dialog);
                
            },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }

                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: msg,
                    type: 'error',
                    sticker: false
                });
                if (typeof element !== 'undefined') {
                    Core.unblockUI(element);
                } else {
                    Core.unblockUI();
                }
            }
        });
    }

    function getIntervalData(id) {
        $.ajax({
            url: 'government/getRealTimeData',
            type: 'post',
            data: {id: id, nult: 1},
            dataType: 'JSON',
            success: function (response) {
                var likehtml = '', dislikehtml = '';

                if (typeof response.seenpercent !== 'undefined') {
                    $("#view-count").text(response.seenpercent + '%');
                }
                if (typeof response.likecount !== 'undefined') {
                    $("#like-count").text(response.likecount);
                }

                if (typeof response.dislikecount !== 'undefined') {
                    $("#dislike-count").text(response.dislikecount);
                }

                if (typeof response.commentcount !== 'undefined') {
                    $("#total-comment").text(response.commentcount);
                }

                var viewhtml = '<div class="alert bg-teal text-white alert-styled-left alert-styled-custom alert-dismissible text-center mb-0 p-0 pt-2 pb-2">' +
                        '<span class="font-weight-semibold">Үзээгүй хэрэглэгчийн тоо : <b>' + response.unseencount + '</b></span>' +
                        '</div><hr>';

                //view info  
                if (typeof response.scl_posts_view_dv !== 'undefined' && response.scl_posts_view_dv !== null) {
                    $.each(response.scl_posts_view_dv, function (index, row) {
                        var read = '';
                        if(row.isread == '1') {
                            read = '<span class="badge badge-success">Үзсэн</span>';
                        } else {
                            read = '';
                        }
                        viewhtml += '<li class="media">' +
                                '<div class="mr-3">' +
                                '<img src="' + row.picture + '" width="36" height="36" class="rounded-circle" alt="' + row.createdusername + '" onerror="onNCUserImgError(this);">' +
                                '</div>' +
                                '<div class="media-body">' +
                                '<a href="javascript:void(0);" class="media-title font-weight-semibold">' + row.createdusername + '</a>' +
                                '<span class="d-block text-muted font-size-sm">' + row.createddate + '</span>' +
                                '</div>' + read + 
                                '</li>';
                    });
                }

                $("#modal_default_show_view").find('.modal-body').empty().append(viewhtml).promise().done(function () {
                    //action
                });

                //like info
                if (response.islike === '1') {
                    $.each(response.scl_post_like_list, function (i, data) {
                        if (data.liketype === 'Like') {
                            likehtml += '<li class="media">' +
                                    '<div class="mr-3">' +
                                    '<img src="" width="36" height="36" class="rounded-circle" alt="" onerror="onNCUserImgError(this);">' +
                                    '</div>' +
                                    '<div class="media-body">' +
                                    '<a href="javascript:void(0);" class="media-title font-weight-semibold">' + data.name + '</a>' +
                                    '<span class="d-block text-muted font-size-sm">' + data.createddate + '</span>' +
                                    '</div>' +
                                    '<div class="ml-3 align-self-center"><i class="icon-thumbs-up2 text-success mr-1"></i></div>' +
                                    '</li>';
                        } else {
                            dislikehtml += '<li class="media">' +
                                    '<div class="mr-3">' +
                                    '<img src="" width="36" height="36" class="rounded-circle" alt="" onerror="onNCUserImgError(this);">' +
                                    '</div>' +
                                    '<div class="media-body">' +
                                    '<a href="javascript:void(0);" class="media-title font-weight-semibold">' + data.name + '</a>' +
                                    '<span class="d-block text-muted font-size-sm">' + data.createddate + '</span>' +
                                    '</div>' +
                                    '<div class="ml-3 align-self-center"><i class="icon-thumbs-down2 text-danger mr-1"></i></div>' +
                                    '</li>';
                        }
                    });

                    $("#modal_post_show_like").find('.modal-body').empty().append(likehtml).promise().done(function () {
                        //action
                    });

                    $("#modal_post_show_dislike").find('.modal-body').empty().append(dislikehtml).promise().done(function () {
                        //action
                    });
                }
            }
        });
    }

    setInterval(function () {
        if (!$('.intranet-<?php echo $this->uniqId ?>').is(":visible")) {
            console.log('no visible');
            return;
        }
        
        <?php if (defined('CONFIG_IS_NO_RELOAD') && CONFIG_IS_NO_RELOAD) { ?>
            console.log('CONFIG_IS_NO_RELOAD');
            return false;
        <?php } ?>
            
        var $id = $("#post_id").val();
        if ($id) { getIntervalData($id); }
        
    }, 60000);
    
</script>