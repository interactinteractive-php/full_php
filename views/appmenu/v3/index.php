<div class="appmenu-table d-flex align-items-center justify-content-center">
    <div class="appmenu-table-row">
        <div class="col-md-12">
            <div id="mixgrid" class="appmenu-table-cell-right px-0 mix-grid isuppermenu<?php echo Config::getFromCacheDefault('ISFIRST_LETTER_UPPER_MENU', null, '1'); ?>">
                <div class="white-card-menu m-0 animated zoomIn">
                    <?php
                    if ($appmenuTopCenterTitle = Config::getFromCache('appmenuTopCenterTitle')) {
                        echo html_tag('div', array('class' => 'text-center font-size-14 font-weight-bold mb10'), Mdcommon::titleReplacerByVar($appmenuTopCenterTitle));
                    }
                    
                    foreach ($this->menuList['menuData'] as $row) {
                        
                        if ($row['code'] == 'ERP_MENU_MOBILE') {
                            continue;
                        }

                        $linkHref = 'javascript:;';
                        $linkTarget = '_self';
                        $linkOnClick = $class = '';
                        
                        if ($row['isshowcard'] == 'true') {
                            
                            $linkHref = 'appmenu/sub/'.$row['code'];
                            $linkTarget = '_self';
                            $linkOnClick = '';
                            
                        } elseif (!empty($row['weburl'])) {
                            
                            if (strtolower(substr($row['weburl'], 0, 4)) == 'http' || $row['weburl'] == 'appmenu/kpi') {
                                $linkHref = $row['weburl'];
                            } else {
                                $linkHref = $row['weburl'].'&mmid='.$row['metadataid'];                
                            }
                            
                            $linkTarget = $row['urltrg'];
                            $linkOnClick = '';
                            
                        } elseif (empty($row['weburl']) && empty($row['actionmetadataid'])) {
                            
                            $linkHref = 'appmenu/module/'.$row['metadataid'].'?mmid='.$row['metadataid'];
                            $linkTarget = '_self';
                            $linkOnClick = '';
                            
                        } else {
                            
                            if ($row['actionmetatypeid'] == Mdmetadata::$contentMetaTypeId) {
                                $linkHref = 'appmenu/module/'.$row['metadataid'].'/'.$row['actionmetadataid'].'?mmid='.$row['metadataid'];
                                $linkTarget = '_self';
                                $linkOnClick = '';
                            } else {
                                $linkMeta = Mdmeta::menuServiceAnchor($row, $row['metadataid'], $row['metadataid']);
                                $linkHref = $linkMeta['linkHref'];
                                $linkTarget = $linkMeta['linkTarget'];
                                $linkOnClick = $linkMeta['linkOnClick'];
                            }
                        }
                        
                        if (isset($row['licensestatus'])) {

                            if ($row['licensestatus'] == '2') {
                                $linkOnClick = "appLicenseExpireBefore(this, '".$row['licenseenddate']."', '".$row['licenseremainingdays']."', '$linkHref');";
                                $linkHref = 'javascript:;';
                                $linkTarget = '_self';
                            } elseif ($row['licensestatus'] == '3') {
                                $linkOnClick = "appLicenseExpireWait(this, '".$row['licenseenddate']."', '".$row['licenseremainingdays']."', '$linkHref');";
                                $linkHref = 'javascript:;';
                                $linkTarget = '_self';
                            } elseif ($row['licensestatus'] == '4') {
                                $linkHref = 'javascript:;';
                                $linkTarget = '_self';
                                $linkOnClick = "appLicenseExpired(this, '".$row['licenseenddate']."');";
                                $class = ' disabled';
                            }
                        }
                        
                        if ($row['metadataid'] == '1668505983686113') {
                            $linkOnClick = 'mvFlowChartExecuteInit(this, \''.$linkHref.'\', \'166848750564710\', true);';
                            $linkHref = 'javascript:;';
                        }
                    ?>
                    <a href="<?php echo $linkHref; ?>" target="<?php echo $linkTarget; ?>" onclick="<?php echo $linkOnClick; ?>" class="vr-menu-tile animated<?php echo $class; ?>" data-metadataid="<?php echo $row['metadataid']; ?>" data-pfgotometa="1">
                        <div class="d-flex align-items-center">
                            <div class="vr-menu-cell">
                                <div class="vr-menu-img">
                                    <?php
                                    $imgSrc = 'assets/core/global/img/appmenu.v2.png';
                                    if ($row['photoname'] != '' && file_exists($row['photoname'])) {
                                        $imgSrc = $row['photoname'];
                                    }
                                    if ($imgSrc == 'assets/core/global/img/appmenu.v2.png' && $row['icon']) {
                                        echo '<i style="font-size: 18px;" class="fa '.$row['icon'].'"></i>';
                                    } else {
                                        echo '<img src="'.$imgSrc.'">';
                                    }                                    
                                    ?>
                                </div>
                            </div>
                            <div class="vr-menu-title">
                                <div class="vr-menu-row">
                                    <div class="vr-menu-name text-left" data-app-name="true"><?php echo $this->lang->line($row['name']); ?></div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <?php
                    }
                    ?>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .appmenu-table-cell-right .vr-menu-tile {
        display: inline-block;
        float: none;
        position: inherit;
    }
    <?php
    if (Config::getFromCache('isAppmenuBigCard')) {
    ?>
    .appmenu-table-cell-right .vr-menu-tile {
        width: 300px;
        height: 115px;
        padding-top: 26px;
    }
    .appmenu-table-cell-right .vr-menu-img {
        width: 60px !important;
        height: 60px !important;
    }
    .appmenu-table-cell-right .vr-menu-img img {
        width: 40px;
    }
    .appmenu-table-cell-right .vr-menu-title .vr-menu-row .vr-menu-name {
        font-size: 19px;
    }
    <?php
    }
    ?>
</style>

<script type="text/javascript">
    $(function(){
        
        $('#appmenusearchinput').on('keyup', function(){
            var $this = $(this);
            var title, i; 
            var cards = $('#mixgrid').find('.vr-menu-tile');
            var filter = ($this.val()).toLowerCase();

            if ($this.val() != '') {
                var cardsLength = cards.length
                for (i = 0; i < cardsLength; i++) {
                    title = cards[i].querySelector(".vr-menu-name");
                    if (title.innerText.toLowerCase().indexOf(filter) > -1) {
                        cards[i].setAttribute("style", "display: inline-block;");
                    } else {
                        cards[i].style.display = "none";
                    }
                }
            } else {
                cards.css('display', '');
            }
        });

    <?php if ($this->getResetUser) { ?>
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
                                
                                var $form = $("#form-change-password");
                                var minLength = 8;

                                if (typeof $form.attr('minlength') != 'undefined' && $form.attr('minlength') != '') {
                                    minLength = $form.attr('minlength');
                                }
                                
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
                                    plang.getDefault('user_requirements_password', 'Хамгийн багадаа '+minLength+' тэмдэгт, том жижиг үсэг, тоо болон тусгай тэмдэгт оролцсон байх')
                                );

                                $form.validate({
                                    rules: {
                                        currentPassword: {
                                            required: true
                                        },
                                        newPassword: {
                                            required: true,
                                            minlength: minLength,
                                            regex: '^(?=.*[a-zа-яөү])(?=.*[A-ZА-ЯӨҮ])(?=.*[0-9])(?=.*[!@#\$%\^&\*_])(?=.{'+minLength+',})'
                                        },
                                        confirmPassword: {
                                            required: true,
                                            minlength: minLength,
                                            equalTo: "#newPassword",
                                            regex: '^(?=.*[a-zа-яөү])(?=.*[A-ZА-ЯӨҮ])(?=.*[0-9])(?=.*[!@#\$%\^&\*_])(?=.{'+minLength+',})'
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

                                if ($form.valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: 'profile/changePassword',
                                        data: $form.serialize()+'&resetPassword=1',
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
</script>
<?php echo (new Mduser())->startupMetaScriptFooter(); ?>
    