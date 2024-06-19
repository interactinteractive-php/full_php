<div class="appmenu-table appmenu-newdesign-<?php echo $this->isAppmenuNewDesign; ?>">
    <div class="appmenu-table-row">
        <div class="appmenu-table-cell-left">
            <!-- <div class="sidebar-mobile-toggler text-center">
                <a href="#" class="sidebar-mobile-main-toggle">
                    <i class="icon-arrow-left8"></i>
                </a>
                Navigation
                <a href="#" class="sidebar-mobile-expand">
                    <i class="icon-screen-full"></i>
                    <i class="icon-screen-normal"></i>
                </a>
            </div> -->
            <ul class="mix-filter">
                <?php 
                $cloneMenuList = $this->menuList;
                $cards = array();
                $firstModule = $firstModuleCode = '';
                $i = 0;
                $colorSet = explode(',', $this->colorSet);
                
                foreach ($cloneMenuList as $k => $groupRow) {
                    if (empty($groupRow['row']['ptagcode'])) {
                        $firstIndex = true;
                        $activeClass = '';
                        
                        if ($k == 'яяяrow') {
                            $k = 'other';
                            $title = $this->lang->line('othermenu_title');
                        } else {
                            $title = $this->lang->line($groupRow['row']['tagname']);
                        }

                        if ($i == 0) {
                            $activeClass = ''; //' active';
                            $firstModule = $title;
                            $firstModuleCode = $k;
                        }

                        $subAppmenu = '<ul class="sub-mix-filter" style="display:none">';
                        if (!empty($groupRow['row']['tagcode'])) {
                            
                            foreach ($cloneMenuList as $ck => $childRow) { 

                                if ($groupRow['row']['tagcode'] == $childRow['row']['ptagcode']) {    
                                    
                                    $ctitle = $this->lang->line($childRow['row']['tagname']);

                                    $subAppmenu .= '<li class="filter sub-filter" data-filter="' . $ck . '">';
                                    $subAppmenu .= '<span class="title">' . Str::firstUpper(Str::lower($ctitle)) . '</span>';
                                    $subAppmenu .= '</li>';
                                    $firstIndex = false;
                                    
                                    unset($cloneMenuList[$ck]);
                                }
                            }
                        }
                        
                        $subAppmenu .= '</ul>';

                        echo '<li class="filter' . $activeClass . '" data-filter="' . $k . '">';
                        echo !$firstIndex ? '<span class="title arrow">' . Str::firstUpper(Str::lower($title)) . '</span>' . $subAppmenu : '<span class="title">' . Str::firstUpper(Str::lower($title)) . '</span>';                    
                        echo '</li>';
                    }

                    $i++;

                    foreach ($groupRow['rows'] as $row) {
                        
                        if ($row['code'] == 'ERP_MENU_MOBILE') {
                            continue;
                        }
                        $colorSetIndex = array_rand($colorSet);
                        
                        $linkHref = 'javascript:;';
                        $linkTarget = '_self';
                        $linkOnClick = '';
                        $class = ' random-border-radius'.mt_rand(1,3);
                        $cartbgColor = '';
                        
                        if ($this->isAppmenuNewDesign) {
                            if (issetParam($row['menucolor'])) {
                                $cartbgColor = 'background-color:'.$row['menucolor'].';';
                            } else {
                                $cartbgColor = 'background-color:'.$colorSet[$colorSetIndex].';';
                            }
                        }

                        if ($row['isshowcard'] == 'true') {
                            $linkHref = 'appmenu/sub/' . $row['code'];
                            $linkTarget = '_self';
                            $linkOnClick = '';
                            
                        } elseif (!empty($row['weburl'])) {
                            
                            $linkTarget = $row['urltrg'];
                            $linkOnClick = '';
                            
                            if (strtolower(substr($row['weburl'], 0, 4)) == 'http' || $row['weburl'] == 'appmenu/kpi') {
                                $linkHref = $row['weburl'];
                            } else {
                                $linkHref = $row['weburl'] . '&mmid=' . $row['metadataid'];
                            }
                            
                            if (strpos($row['weburl'], 'mdform/indicatorProduct/') !== false) {
                                $indicatorId = str_replace('mdform/indicatorProduct/', '', $row['weburl']);
                                $linkHref = 'javascript:;';
                                $linkOnClick = 'mvProductRenderInit(this, \''.$linkHref.'\', \''.$indicatorId.'\');';
                            }
                            
                        } elseif (empty($row['weburl']) && empty($row['actionmetadataid'])) {

                            $linkHref = 'appmenu/module/' . $row['metadataid'] . '?mmid=' . $row['metadataid'];
                            $linkTarget = '_self';
                            $linkOnClick = '';
                            
                        } else {

                            if ($row['actionmetatypeid'] == Mdmetadata::$contentMetaTypeId) {
                                $linkHref = 'appmenu/module/' . $row['metadataid'] . '/' . $row['actionmetadataid'] . '?mmid=' . $row['metadataid'];
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
                                $linkOnClick = "appLicenseExpireBefore(this, '" . $row['licenseenddate'] . "', '" . $row['licenseremainingdays'] . "', '$linkHref');";
                                $linkHref = 'javascript:;';
                                $linkTarget = '_self';
                            } elseif ($row['licensestatus'] == '3') {
                                $linkOnClick = "appLicenseExpireWait(this, '" . $row['licenseenddate'] . "', '" . $row['licenseremainingdays'] . "', '$linkHref');";
                                $linkHref = 'javascript:;';
                                $linkTarget = '_self';
                            } elseif ($row['licensestatus'] == '4') {
                                $linkHref = 'javascript:;';
                                $linkTarget = '_self';
                                $linkOnClick = "appLicenseExpired(this, '" . $row['licenseenddate'] . "');";
                                $class = ' disabled';
                            }
                        }
                        
                        if ($row['metadataid'] == '1668505983686113') {
                            $linkOnClick = 'mvFlowChartExecuteInit(this, \''.$linkHref.'\', \'166848750564710\', true);';
                            $linkHref = 'javascript:;';
                        }

                        if ($row['photoname'] != '' && file_exists($row['photoname'])) {
                            $imgSrc = $row['photoname'];
                        } else {
                            $imgSrc = 'assets/custom/img/appmenu.png';
                        }
                        
                        $bgImageStyle = '';
                        if (issetParam($row['bgphotoname']) != '' && file_exists($row['bgphotoname'])) {
                            $bgImageStyle = 'background-image: url('.$row['bgphotoname'].');background-size: cover;';
                        }                
                        
                        $appInfoTextStyle = '';
                        if ($bgImageStyle) {
                            $appInfoTextStyle = 'text-shadow: 2px 2px 2px rgba(0,0,0,0.6);';
                        }

                        $cards[] = '<a href="' . $linkHref . '" target="' . $linkTarget . '" style="'.$cartbgColor.$bgImageStyle.'" onclick="' . $linkOnClick . '" data-code="' . $k . '" data-modulename="' . $this->lang->line($row['name']) . '" class="vr-menu-tile mix ' . $k . $class . '" data-metadataid="' . $row['metadataid'] . '" data-pfgotometa="1">';
                            $cards[] = '<div class="d-flex align-items-center">';
                                $cards[] = '<div class="vr-menu-cell">';
                                      if (!$this->isAppmenuNewDesign) {
                                        if ($imgSrc == 'assets/custom/img/appmenu.png' && $row['icon']) {
                                            $cards[] = '<div class="vr-menu-img"><i style="font-size: 18px;" class="fa '.$row['icon'].'"></i></div>';
                                        } else {
                                            $cards[] = '<div class="vr-menu-img"><img src="' . $imgSrc . '"></div>';
                                        }                                          
                                      }
                                $cards[] = '</div>';
                                $cards[] = '<div class="vr-menu-title">';
                                    $cards[] = '<div class="vr-menu-row'.(issetParam($row['menucode']) ? ' vr-menu-row-mcode' : '').'">';
                                        $cards[] = '<div class="vr-menu-name" data-app-name="true" style="'.$appInfoTextStyle.'">' . $this->lang->line($row['name']) . '</div>';
                                    $cards[] = '</div>';
                                $cards[] = '</div>';
                            $cards[] = '</div>';
                        $cards[] = '</a>';
                    }
                    
                    if (Config::getFromCache('is_dev')) {
                        $cards[] = '<a href="javascript:;" onclick="moduleMetaAddByUser(this);" data-code="' . $k . '" class="vr-menu-tile mix ' . $k . '" style="">';
                            $cards[] = '<div class="d-flex align-items-center">';
                                $cards[] = '<div class="vr-menu-cell">';
                                    $cards[] = '<div class="vr-menu-img"><i style="font-size: 18px;" class="fa fa-plus"></i></div>';
                                $cards[] = '</div>';
                            $cards[] = '</div>';
                        $cards[] = '</a>';
                    }
                }
                ?>
            </ul>
        </div>
        <div id="mixgrid" class="appmenu-table-cell-right mix-grid">
            <!--<div class="appmenuicon"><i class="icon-circle-down2"></i></div>-->
            <h2 class="ml-1">Цэс</h2>
            <p class="noresult mt30 hidden">Илэрц олдсонгүй</p>
            <?php echo implode('', $cards); ?>
        </div>
    </div>
</div>

<?php
if (Config::getFromCache('isAppmenuBigCard')) {
?>
<style type="text/css">
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
</style>
<?php
}
?>

<script type="text/javascript">
    $(function(){        

        $('ul.mix-filter > li > ul.sub-mix-filter > li').on('click', function(e){
            var $this = $(this);
            $('.isSelected').removeClass('selected');
            $('.mix-grid h2').text($this.text());
           
            $(".mix-grid").animate({ scrollTop: 0 }, "slow");
            $this.find('.isSelected').addClass('selected');
            
            window.location.hash = $this.attr('data-filter');

            setTimeout(function(){ 
                var bodyHeight = $('.appmenu-table-cell-right').height();
                var windowHeight = $(window).height();
                var sidebarHeight;
                if (bodyHeight > windowHeight) {
                    sidebarHeight = bodyHeight + 30;
                } else {
                    sidebarHeight = windowHeight - 127;
                }
                $('.appmenu-table-cell-left').attr('style', 'height: ' + sidebarHeight + 'px');
            }, 1000);

            e.stopPropagation();
        });        
        $('ul.mix-filter > li').on('click', function(){
            var $this = $(this);
            $('.isSelected').removeClass('selected');
            scrollToElement($(".mix-grid"), 800);

            $('.mix-grid h2').text($this.find('span.title:first').text());
            $this.find('.isSelected').addClass('selected');
            
            window.location.hash = $this.attr('data-filter');
          
            setTimeout(function(){
                var bodyHeight = $('.appmenu-table-cell-right').height();
                var windowHeight = $(window).height();
                var sidebarHeight;
                if (bodyHeight > windowHeight) {
                    windowHeight = bodyHeight + 20;
                } else {
                    sidebarHeight = windowHeight ; 
                }
                // $('.appmenu-table-cell-left').attr('style', 'height: ' + sidebarHeight + 'px');
            }, 600);

            if ($this.find('.sub-mix-filter').length) {
                $this.removeClass('active');

                if ($this.find('span.arrow').hasClass('open')) {
                    $this.find('span.arrow').removeClass('open');
                } else {
                    $this.find('span.arrow').addClass('open');
                }
                $this.find('.sub-mix-filter').slideToggle();
                
            } else if ($this.parent().find('span.arrow').hasClass('open')) {
                
                $this.parent().find('.sub-mix-filter:visible').slideToggle();
                $this.parent().find('span.arrow').removeClass('open');
            }
        });
        
        $('#appmenusearchinput').on('keyup', function(){
            var $this = $(this);
            var title, i; 
            var cards = $('#mixgrid').find('.mix');

            if ($this.val() == '') {
                $('.appmenu-table-cell-left').find('.mix-filter .filter:first-child').trigger('click');
                $('.mix-grid h2').text('САНХҮҮГИЙН МЕНЕЖЕМЕНТ');
            } else {
                $('.mix-grid h2').text('Таны илэрц ...');
                
                var filter = ($this.val()).toLowerCase();
                var cardsLength = cards.length;
                
                for (i = 0; i < cardsLength; i++) {
                    var $card = $(cards[i]);
                    title = $card.find(".vr-menu-name"),
                    code = $card.find(".vr-menu-code");
                    if ((title.length && (title.text()).toLowerCase().indexOf(filter) > -1) || (code.length && (code.text()).toLowerCase().indexOf(filter) > -1)) {
                        $card.css({'display': 'block', 'opacity': '1'});
                    } else {
                        $card.css('display', 'none');
                        $('.appmenu-table-cell-right').find('.noresult').attr("style", "display: block; opacity: 1;");
                    }
                }
            }
        });
        
        var hash = window.location.hash;

        if (hash) {
            var noHash = hash.replace('#', '');
            
            $('.mix-grid').mixitup({
                showOnLoad: noHash
            });
            
            $('ul.mix-filter').find(' > li[data-filter="'+noHash+'"]').click();
            
        } else {
            $('.mix-grid').mixitup({
                showOnLoad: '<?php echo $firstModuleCode; ?>'
            });
            
            $('.mix-grid h2').text('<?php echo $firstModule; ?>');
        }

        $(window).resize(function(){

            var bodyHeight = $('.appmenu-table-cell-right').height();
            var windowHeight = $(window).height();
            var sidebarHeight;

            if (bodyHeight > windowHeight) {
                sidebarHeight = bodyHeight + 30;
            } else {
                sidebarHeight = windowHeight - 127;
            }
            $('.appmenu-table-cell-left').attr('style', 'height: ' + sidebarHeight + 'px');
        });

        var bodyHeight = $('body').height();
        var windowHeight = $(window).height();
        var sidebarHeight;

        if (bodyHeight > windowHeight) {
            sidebarHeight = bodyHeight;
        } else {
            sidebarHeight = windowHeight;
        }
        $('.appmenu-table-cell-left').attr('style', 'height: ' + (sidebarHeight - 127) + 'px');  
        
        function scrollToElement(scrollTo, speed) {
            $('html, body').animate({
                scrollTop: scrollTo.offset().top - 150
            }, speed);
        }
    
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
                            class: 'btn btn-sm green-meadow',
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
