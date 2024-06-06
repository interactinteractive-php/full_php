<?php
if (Config::getFromCache('CONFIG_MULTI_TAB')) {
    if ($this->isAjax) {
        echo '<div class="layout-fullscreen-btn">'
            .'<button type="button" class="btn btn-sm btn-icon layout-manual-refresh-btn mr-1" title="Refresh" style="height: 22px;width: 22px;padding: 0;top: 28px;right: -27px;">'
                .'<i class="fa fa-refresh"></i>'
            .'</button>'
            .'<button type="button" class="btn btn-sm btn-icon mr-1 layout-print-btn" title="'.$this->lang->line('print_btn').'" style="height: 22px;width: 22px;padding: 0;">'
                .'<i class="fa fa-print"></i>'
            .'</button>'
        . '</div>';
        echo $this->replacedLayoutHtml;
    } 
    else { 
        if ($this->isMainPage == 1) { 
?>
            <div class="col-md-12">
                <?php echo $this->replacedLayoutHtml; ?>
            </div>
        <?php 
        } 
        else { 
        ?>
            <div class="col-md-12">
                <div class="card light shadow card-multi-tab">
                    <div class="card-header header-elements-inline tabbable-line">
                        <ul class="nav nav-tabs card-multi-tab-navtabs">
                            <li data-type="layout">
                                <a href="#app_tab_<?php echo $this->metaDataId; ?>" class="nav-link active" data-title="<?php echo $this->title . ' - ' . issetParam($this->moduleName) ?>" data-toggle="tab"><i class="fa fa-caret-right"></i> <?php echo $this->title; ?><span><i class="fa fa-times-circle"></i></span></a>
                            </li>
                        </ul>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="fullscreen"></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content card-multi-tab-content">
                            <div class="tab-pane active" id="app_tab_<?php echo $this->metaDataId; ?>">
                                <div class="layout-fullscreen-btn">
                                    <!--<button type="button" class="btn btn-sm btn-icon mr-1" title="Fullscreen" onclick="layoutFullScreen(this);">
                                        <i class="fa fa-expand"></i>
                                    </button>-->
                                    <button type="button" class="btn btn-sm btn-icon layout-manual-refresh-btn mr-1" title="Refresh" style="height: 22px;width: 22px;padding: 0;top: 28px;right: -27px;">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon mr-1 layout-print-btn" title="<?php echo $this->lang->line('print_btn'); ?>" style="height: 22px;width: 22px;padding: 0;">
                                        <i class="fa fa-print"></i>
                                    </button>
                                </div>
                                <?php echo $this->replacedLayoutHtml; ?>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
        <?php 
        }
    }
} else {
    $windowBorderBegin = '
    <div class="col-md-12">    
    <div class="card light shadow">
        <div class="card-header card-header-no-padding header-elements-inline">
                <div class="caption buttons">
                    ' . html_tag('a', array(
                'href' => $this->metaBackLink,
                'class' => 'btn btn-circle btn-secondary card-subject-btn-border mr10'
                    ), '<i class="icon-arrow-left7"></i>', $this->isBackLink
            ) . '                          
                </div>
                <div class="card-title">
                    <span class="caption-subject font-weight-bold uppercase card-subject-blue">' . $this->title . '</span>
                </div>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                        <a class="list-icons-item" data-action="fullscreen"></a>
                    </div>
                </div>
            </div>
        <div class="card-body form">';
    $windowBorderEnd = '</div></div></div>';

    if (!$this->isAjax) {
        echo $windowBorderBegin;
    }
    echo $this->replacedLayoutHtml;
    if (!$this->isAjax) {
        echo $windowBorderEnd;
    }
}
?>

<script type="text/javascript">
    var layoutLinkIdjs = '<?php echo $this->layoutLinkId; ?>';
</script>

<?php 
echo isset($this->defaultCss) ? $this->defaultCss : ''; 
echo isset($this->defaultJs) ? $this->defaultJs : ''; 
?>

<script type="text/javascript">
    
    $(function () {
        
        <?php if ($this->layoutLink['THEME_CODE'] === 'theme23') { ?> 
            initLayout<?php echo $this->metaDataId; ?>('layout-id-<?php echo $this->layoutLinkId; ?>');
        <?php } else { ?>
            initLayout<?php echo $this->metaDataId; ?>(null);
        <?php } ?>
        
        /*
         * Үүнийг тохиргоогоор шийдэх ёстой тиймээс идэвхигүй болгов!
         */ 
        $('#layout-id-<?php echo $this->layoutLinkId; ?> .layout-criteria-div').on('click', '.dataview-default-filter-btn, #default-mandatory-criteria-form', function () {
            initLayout<?php echo $this->metaDataId; ?>('layout-id-<?php echo $this->layoutLinkId; ?>');
        });     
        
        $('#layout-id-<?php echo $this->layoutLinkId; ?> .layout-criteria-div').on('click', '.dataview-default-filter-reset-btn, #default-mandatory-criteria-form', function () {
            
            var $this = $(this), $thisForm = $this.closest("form");
                
            $thisForm.find("input[type=text], input[type=hidden], textarea").not("input[name='inputMetaDataId'], select.right-radius-zero, input[name*='criteriaCondition[']").val('');
            $thisForm.find("input[type=radio], input[type=checkbox]").removeAttr('checked');
            $thisForm.find("input[type=radio], input[type=checkbox]").closest('span.checked').removeClass('checked');
            $thisForm.find("select.select2").select2('val', '');
            $thisForm.find('.bp-icon-selection > li.active').removeClass('active');
            $thisForm.find('.btn.removebtn[data-lookupid]').hide();
            $thisForm.find('.btn[data-lookupid][data-choosetype][data-idfield][onclick]').text('..');
            $thisForm.find('input[name*="idWithComma["], button[onclick*="dvOnlySearchFormReset"]').remove();
            
            initLayout<?php echo $this->metaDataId; ?>('layout-id-<?php echo $this->layoutLinkId; ?>');
        });    

        if ($("div#layout-id-<?php echo $this->layoutLinkId; ?>").find('form:eq(0)').find('.col-md-4.pr0').length) {
            
            $("div#layout-id-<?php echo $this->layoutLinkId; ?>").find('form:eq(0)').find('.dataview-default-filter-btn').parent().removeClass('col-md-12').addClass('col-md-4 col-md-offset-4');
            $("div#layout-id-<?php echo $this->layoutLinkId; ?>").find('form:eq(0)').find('.col-md-4.pr0').addClass('col-md-offset-4');

            $("div#layout-id-<?php echo $this->layoutLinkId; ?>").find('form:eq(0)').closest('.col-md-12').addClass('hide');
            $("div#layout-id-<?php echo $this->layoutLinkId; ?>").find('form:eq(0)').closest('.col-md-12').parent().prepend('<div id="layout-criteria-btn-<?php echo $this->layoutLinkId; ?>" class="col-md-12" style="text-align: center;cursor:pointer; font-size: 15px !important;"><i class="fa fa-search"></i> Шүүлт</div>');

            $('#layout-criteria-btn-<?php echo $this->layoutLinkId; ?>').on('click', function () {
                var $layoutCriteria = $("div#layout-id-<?php echo $this->layoutLinkId; ?>").find('form:eq(0)').closest('.col-md-12');
                if ($layoutCriteria.hasClass('hide')) {
                    $layoutCriteria.removeClass('hide');
                } else {
                    $layoutCriteria.addClass('hide');
                }
            });
            
        }

        <?php
        if (!empty($this->layoutLink['REFRESH_TIMER'])) {
            $refTimer = (int) $this->layoutLink['REFRESH_TIMER'];
            $refTimer = $refTimer >= 10 ? $refTimer : 10;
        ?>
                
        if ($('.layout-fill-<?php echo $this->metaDataId; ?>').is(":visible")) {
            setInterval(function () {
                var isInterval = true;
                $('.layout-fill-<?php echo $this->metaDataId; ?>').each(function (key, row) {
                    if (typeof $(this).attr('data-fetched') === 'undefined') {
                        isInterval = false;
                    }
                });

                if (isInterval && !document.hidden) {
                    $('.layout-fill-<?php echo $this->metaDataId; ?>').removeAttr('data-fetched');
                    initLayout<?php echo $this->metaDataId; ?>('refreshTimer');
                }

            }, <?php echo $refTimer; ?> * 1000);
        }
        <?php } ?>

        setTimeout(function () {
            $(".layout-theme").show();
        }, 200);
        
        $('.layout-print-btn').on('click', function() { 
            var $this = $(this); 
            Core.blockUI({message: 'Loading...', boxed: true}); 
            
            setTimeout(function() {
                
                $.when(
                    $.getScript('assets/custom/addon/plugins/html2canvas/dom-to-image.js')
                ).then(function () {
                    
                    var $layoutPanel = $this.closest('.tab-pane');
                    
                    if ($layoutPanel.length == 0) {
                        var $wsPanel = $this.closest('.workspace-part'); 
                        if ($wsPanel.length) {
                            $layoutPanel = $wsPanel;
                        }
                    }
                    
                    var node = $layoutPanel.find('.layout-theme')[0];

                    domtoimage.toPng(node, {filter: htmlToImageTagFilter}).then(function(dataUrl) {  

                        var $printHtml = $('<div />', {html: '<img src="' + dataUrl + '" style="width:100%;"/>'});

                        $printHtml.printThis({
                            debug: false,
                            importCSS: false,
                            printContainer: false,
                            removeInline: false
                        });

                        $printHtml.remove();

                    }).catch(function (error) {
                        console.error('oops, something went wrong!', error);
                    });
                    
                    Core.unblockUI();

                }, function () {
                    console.log('an error occurred somewhere');
                    Core.unblockUI();
                }); 
            }, 100);
        });
        
        $('.layout-manual-refresh-btn').on('click', function() { 
            initLayout<?php echo $this->metaDataId; ?>('manual-refresh');
        });

        $('#layout-id-<?php echo $this->layoutLinkId; ?>').on('click', '.dv-criteria', function () {
            var $self = $(this);            

            if ($self.hasClass('dv-criteria-open')) {
                $self.closest('.layout-dv-criteria').css('width', '40px');
                $self.closest('.layout-dv-criteria').find('.layout-criteria-div').addClass('d-none');
            } else {
                $self.closest('.layout-dv-criteria').css('width', '300px');
                $self.closest('.layout-dv-criteria').find('.layout-criteria-div').removeClass('d-none');
            }
            $self.toggleClass('dv-criteria-open');
        });                 
        
    <?php if (isset($this->getResetUser) && $this->getResetUser) { ?>
        var $dialogName = 'dialog-user-startup-resetpassword';
        if (!$('#' + $dialogName).length) {
            $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);
        var showMessage = '<?php echo $this->getResetUser['PASSWORD_RESET_DATE'] ? Lang::lineVar('UM_0001', array('day' => Config::getFromCache('ChangePasswordDate'))) : Lang::line('UM_0002'); ?>'

        $.ajax({
            type: 'post',
            url: 'profile/changePasswordForm',
            dataType: 'json',
            data: {no_nowpassword: '1', showMessage: showMessage},
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 500,
                    minWidth: 500,
                    height: 'auto',
                    modal: true,
                    closeOnEscape: false, 
                    open: function () {
                        $dialog.parent().find('.ui-dialog-titlebar-close').remove();
                    },
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [{
                            text: data.save_btn,
                            "class": 'btn btn-sm green-meadow',
                            click: function() {
                                $.validator.addMethod(
                                    "regex",
                                    function(value, element, regexp) {
                                        if (regexp.constructor != RegExp) {
                                            regexp = new RegExp(regexp);
                                        } else if (regexp.global) {
                                            regexp.lastIndex = 0;
                                        }
                                        return this.optional(element) || regexp.test(value);
                                    },
                                    'Хамгийн багадаа 8 тэмдэгт, том жижиг үсэг, тоо болон тусгай тэмдэгт оролцсон байх'
                                );

                                $("#form-change-password").validate({
                                    rules: {
                                        currentPassword: {
                                            required: true
                                        },
                                        newPassword: {
                                            required: true,
                                            minlength: 8,
                                            regex: '^(?=.*[a-zа-яөү])(?=.*[A-ZА-ЯӨҮ])(?=.*[0-9])(?=.*[!@#\$%\^&\*_])(?=.{8,})'
                                        },
                                        confirmPassword: {
                                            required: true,
                                            minlength: 8,
                                            equalTo: "#newPassword",
                                            regex: '^(?=.*[a-zа-яөү])(?=.*[A-ZА-ЯӨҮ])(?=.*[0-9])(?=.*[!@#\$%\^&\*_])(?=.{8,})'
                                        }
                                    },
                                    messages: {
                                        currentPassword: {
                                            required: plang.get('user_insert_password')
                                        },
                                        newPassword: {
                                            required: plang.get('user_insert_password'),
                                            minlength: plang.get('user_minlenght_password')
                                        },
                                        confirmPassword: {
                                            required: plang.get('user_insert_password'),
                                            minlength: plang.get('user_minlenght_password'),
                                            equalTo: plang.get('user_equal_password')
                                        }
                                    }
                                });

                                if ($("#form-change-password").valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: 'profile/changePassword',
                                        data: $("#form-change-password").serialize()+'&resetPassword=1',
                                        dataType: "json",
                                        beforeSend: function() {
                                            Core.blockUI({message: 'Loading...', boxed: true});
                                        },
                                        success: function(data) {
                                            PNotify.removeAll();
                                            new PNotify({
                                                title: data.status,
                                                text: data.message,
                                                type: data.status,
                                                sticker: false
                                            });

                                            if (data.status === 'success') {
                                                $dialog.dialog("close");
                                            }
                                            Core.unblockUI();
                                        },
                                        error: function() {
                                            alert("Error");
                                            Core.unblockUI();
                                        }
                                    });
                                }
                            }
                        }
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            }
        });        
    <?php } ?>            
        
    });

    function initLayout<?php echo $this->metaDataId; ?>(executeType) {
        
        $('.layout-fill-<?php echo $this->metaDataId; ?>').each(function () {
            var $this = $(this),
                $metaTypeId = $this.attr('data-meta-type-id'),
                $metaDataId = $this.attr('data-meta-id');
                        
            switch ($metaTypeId) {
                case '200101010000016':
                    layoutCallDataViewByMeta($metaDataId, executeType);
                    break;
                    
                case '200101010000024':
                    layoutCallGoogleMap($metaDataId);
                    break;
                    
                case '200101010000031':
                    layoutCallDashboardCardByMeta($metaDataId, executeType);
                    break;
                    
                case '200101010000032':
                    layoutCallDiagramByMeta($metaDataId, executeType);
                    break;
                    
                case '200101010000027':
                    layoutCallCalendar($metaDataId);
                    break;
                    
                case '200101010000033':
                    layoutCallPackage($metaDataId);
                    break;
                    
                case '200101010000038':
                    
                    var $metaDataCode = $this.attr('data-meta-code'),
                        $paramMapId = $this.attr('data-layout-param-map-id');

                    layoutCallWidget($metaDataId, $metaDataCode, $paramMapId);
                    break;

                default:

                    break;
            }
            
        });
        
    }
    
    function layoutCallDashboardCardByMeta(metaDataId, executeType) {
        var $layout = $("div#layout-" + metaDataId);
        var workSpaceId = '', workSpaceParams = '';

        if ($layout.closest('div.ws-area').length > 0) {
            var $wsArea = $layout.closest('div.ws-area');
            var workSpaceIdAttr = $wsArea.attr('id').split('-');
            workSpaceId = workSpaceIdAttr[2];
            workSpaceParams = $("div.ws-hidden-params", $wsArea).find("input[type=hidden]").serialize();
        }

        $.ajax({
            type: 'post',
            url: 'mdmeta/cardRenderByPost',
            data: {
                metaDataId: metaDataId, 
                workSpaceId: workSpaceId, 
                workSpaceParams: workSpaceParams, 
                defaultCriteriaData: $("div#layout-id-<?php echo $this->layoutLinkId; ?>").find('form#default-criteria-form:eq(0),form#default-mandatory-criteria-form:eq(0)').serialize()
            },
            dataType: 'json',
            success: function (data) {
                $layout.empty().append(data.Html);
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            $layout.attr('data-fetched', true);
            Core.initAjax($layout);
        });
    }
    
    function layoutCallDiagramByMeta(metaDataId, executeType) {
        var $layout = $("div#layout-" + metaDataId);
        var workSpaceId = '', workSpaceParams = '';

        if ($layout.closest('div.ws-area').length > 0) {
            var $wsArea = $layout.closest('div.ws-area');
            var workSpaceIdAttr = $wsArea.attr('id').split('-');
            workSpaceId = workSpaceIdAttr[2];
            workSpaceParams = $("div.ws-hidden-params", $wsArea).find("input[type=hidden]").serialize();
        }

        if (typeof executeType === 'undefined') {
            executeType = '<?php echo isset($this->executeType) ? $this->executeType : ''; ?>';
        }

        $.ajax({
            type: 'post',
            url: 'mddashboard/diagramRenderByPost',
            data: {
                metaDataId: metaDataId,
                executeType: executeType == 'refreshTimer' ? '' : executeType,
                workSpaceParams: workSpaceParams,
                workSpaceId: workSpaceId,
                defaultCriteriaData: $("div#layout-id-<?php echo $this->layoutLinkId; ?>").find('form#default-criteria-form:eq(0),form#default-mandatory-criteria-form:eq(0)').serialize()
            },
            dataType: "json",
            //async: isChartAsync, 
            beforeSend: function () {
                if (typeof activeAjaxRequests !== 'undefined') {
                    activeAjaxRequests = activeAjaxRequests+2;
                }
                if (executeType !== 'refreshTimer') {
                    Core.blockUI({
                        animate: true
                    });
                }
            },
            success: function (data) {
                /* $("div#layout-" + metaDataId).html('<div class="meta-toolbar" style="padding-top: 6px; "><span class="font-weight-bold text-uppercase card-subject-blue mt10 ml10 display" style="color:#555555 !important;"><i class="fa fa-bar-chart-o"></i>'+ data.Title +'</span></div>'+ data.Html); */
                $("div#layout-" + metaDataId).empty().append(data.Html);
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            $layout.attr('data-fetched', true);
            Core.initAjax($("div#layout-" + metaDataId));
        });
    }
    
    function layoutCallGoogleMap(metaDataId) {
        $.ajax({
            type: 'post',
            url: 'mdmetadata/googleMapView',
            data: {metaDataId: metaDataId},
            dataType: "json",
            //async: isChartAsync, 
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $("div#layout-" + metaDataId).empty().append('<div class="row">' + data + '</div>');
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initAjax($("div#layout-" + metaDataId));
        });
    }
    
    function layoutCallDataViewByMeta(metaDataId, executeType) {
        if (executeType === 'manual-refresh') {
            return;
        }

        var dvDefaultCriteria = {};        
        var getPostData = $("div#layout-id-<?php echo $this->layoutLinkId; ?>").find('form#default-criteria-form:eq(0),form#default-mandatory-criteria-form:eq(0)').serializeArray();
        
        if (getPostData) {
            for (var fdata = 0; fdata < getPostData.length; fdata++) {
                var mPath = /param\[([\w.]+)\]/g.exec(getPostData[fdata].name);
                if(mPath === null) continue;

                dvDefaultCriteria[mPath[1]] = getPostData[fdata].value;
            }        
        }        

        var $layout = $("div#layout-" + metaDataId);
        var workSpaceId = '', workSpaceParams = '';

        <?php if (isset($this->workSpaceParams) && $this->workSpaceParams) { ?>
            workSpaceParams = '<?php echo $this->workSpaceParams ?>';
            workSpaceId = '<?php echo $this->workSpaceId ?>';
        <?php } else { ?>
            if ($layout.closest('div.ws-area').length > 0) {
                var $wsArea = $layout.closest('div.ws-area');
                var workSpaceIdAttr = $wsArea.attr('id').split('-');
                    workSpaceId = workSpaceIdAttr[2];
                    workSpaceParams = $("div.ws-hidden-params", $wsArea).find("input[type=hidden]").serialize();
            }        
        <?php } ?>
        
        $.ajax({
            type: 'post',
            data: {
                isDynamicHeight: 0, 
                isNeedTitle: 1, 
                workSpaceId: workSpaceId, 
                workSpaceParams: workSpaceParams,
                dvDefaultCriteria: dvDefaultCriteria
            },
            url: 'mdobject/dataview/' + metaDataId + '/<?php echo $this->layoutLink['IS_HIDE_BUTTON'] ?>',
            //async: isChartAsync, 
            beforeSend: function () {
                if (typeof activeAjaxRequests !== 'undefined') {
                    activeAjaxRequests = activeAjaxRequests+2;
                }                
                if (executeType !== 'refreshTimer') {
                    Core.blockUI({
                        animate: true
                    });
                }
            },
            success: function (data) {
                $("div#layout-" + metaDataId).empty().append('<div class="bg-white">' + data + '</div>');
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            $("div#layout-" + metaDataId).attr('data-fetched', true);
            Core.initAjax($("div#layout-" + metaDataId));
        });
    }
    
    function layoutCallCalendar(metaDataId) {
        $.ajax({
            type: 'post',
            url: 'mdcalendar/calendarRenderByPost/',
            data: {metaDataId: metaDataId},
            dataType: "json",
            //async: isChartAsync, 
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $("div#layout-" + metaDataId).empty().append(data.Html);
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initAjax($("div#layout-" + metaDataId));
        });
    }
    
    function layoutCallPackage(metaDataId) {
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'mdobject/package/' + metaDataId + '/json',
            data: {metaDataId: metaDataId},
            dataType: "json",
            //async: isChartAsync, 
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $("div#layout-" + metaDataId).empty().append(data.Html);
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initAjax($("div#layout-" + metaDataId));
        });
    }
    
    function layoutCallWidget(metaDataId, metaDataCode, paramMapId) {
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'mdwidget/runWidget',
            data: {
                widgetCode: metaDataCode,
                metaDataId: metaDataId,
                paramMapId: paramMapId,
                linkMetaDataId: <?php echo $this->metaDataId; ?>,
                uniqId: '<?php echo getUID(); ?>'
            },
            dataType: "json",
            //async: isChartAsync, 
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $("div#layout-" + metaDataId).empty().append(data.html);
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initAjax($("div#layout-" + metaDataId));
        });
    }
    
    function layoutCallWidget2(metaDataId, paramMapId, position) {
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'mdwidget/runWidget',
            data: {
                widgetCode: 'contentWidget',
                metaDataId: metaDataId,
                paramMapId: paramMapId,
                linkMetaDataId: <?php echo $this->metaDataId; ?>,
                uniqId: '<?php echo getUID(); ?>',
                position: position
            },
            dataType: "json",
            //async: isChartAsync, 
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $("div#layout-" + metaDataId).eq(position - 1).empty().append(data.html);
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initAjax($("div#layout-" + metaDataId));
        });
    }
    
</script>
<?php 
if (isset($this->isWorkAlone) && $this->isWorkAlone) {
/*<script type="text/javascript" src="dashboard/delayUrl/<?php echo getUID(); ?>"></script>*/
} 
?>
