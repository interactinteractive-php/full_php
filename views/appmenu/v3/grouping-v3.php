<div class="appmenu-table appmenu-newdesign-<?php echo $this->isAppmenuNewDesign; ?>">
    <div class="appmenu-table-row">
        <div class="appmenu-table-cell-left">
            <ul class="mix-filter">
                <?php 
                $categoryList = Arr::groupByArray($this->moduleList, 'CATEGORY_ID');
                $cards = [];
                $firstModule = $firstModuleCode = '';
                $i = 0;
                $colorSet = explode(',', $this->colorSet);
                
                foreach ($categoryList as $k => $groupRow) {
                    
                    $firstIndex = true;
                    $activeClass = $subAppmenu = '';
                    $title = $this->lang->line($groupRow['row']['CATEGORY_NAME']);

                    if ($i == 0) {
                        $activeClass = '';
                        $firstModule = $title;
                        $firstModuleCode = $k;
                    }

                    echo '<li class="filter' . $activeClass . '" data-filter="' . $k . '">';
                    echo !$firstIndex ? '<span class="title arrow">' . Str::firstUpper(Str::lower($title)) . '</span>' . $subAppmenu : '<span class="title">' . Str::firstUpper(Str::lower($title)) . '</span>';                    
                    echo '</li>';

                    $i++;

                    foreach ($groupRow['rows'] as $row) {
                        if ($groupRow['row']['CATEGORY_ID'] != $row['CATEGORY_ID']) {
                            continue;
                        }
                        
                        $colorSetIndex = array_rand($colorSet);
                        
                        $row['menucolor'] = '';
                        $row['isshowcard'] = '';
                        $row['weburl'] = '';
                        $row['actionmetadataid'] = '';
                        $row['actionmetatypeid'] = '';
                        $row['photoname'] = '';
                        
                        $linkHref = 'javascript:;';
                        $linkTarget = '_self';
                        $linkOnClick = '';
                        $class = ' random-border-radius'.mt_rand(1,3);
                        $cartbgColor = '';
                        
                        if ($this->isAppmenuNewDesign) {
                            if ($row['menucolor']) {
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

                            if (strtolower(substr($row['weburl'], 0, 4)) == 'http' || $row['weburl'] == 'appmenu/kpi') {
                                $linkHref = $row['weburl'];
                            } else {
                                $linkHref = $row['weburl'] . '&mmid=' . $row['metadataid'];
                            }

                            $linkTarget = $row['urltrg'];
                            $linkOnClick = '';
                            
                        } elseif (!empty($row['MENU_INDICATOR_ID']) && !$row['IS_RELATION']) {
                            
                            $linkHref = 'appmenu/module/'.$row['MENU_INDICATOR_ID'].'?kmid='.$row['MENU_INDICATOR_ID'];
                            $linkTarget = '_self';
                            $linkOnClick = '';                            
                            
                        } elseif (!empty($row['META_DATA_ID'])) {
                        
                            if (!$row['ACTION_META_TYPE_ID']) {
                                $linkHref = 'appmenu/module/'.$row['META_DATA_ID'].'?mmid='.$row['META_DATA_ID'].'&mid='.$row['META_DATA_ID'];
                                $linkTarget = '_self';
                                $linkOnClick = '';
                            } else {

                                $row['metadataid'] = $row['META_DATA_ID'];
                                $row['actionmetadataid'] = $row['ACTION_META_DATA_ID'];
                                $row['weburl'] = null;
                                $row['actionmetatypeid'] = $row['ACTION_META_TYPE_ID'];
                                $row['grouptype'] = 'dataview';

                                $linkMeta = Mdmeta::menuServiceAnchor($row, $row['META_DATA_ID'], $row['META_DATA_ID']);
                                $linkHref = $linkMeta['linkHref'];
                                $linkTarget = $linkMeta['linkTarget'];
                                $linkOnClick = $linkMeta['linkOnClick'];
                            }
                            
                        } else {
                            $indicatorId = $row['ID'];
                            $linkHref = 'javascript:;';
                            $linkOnClick = 'mvProductRenderInit(this, \''.$linkHref.'\', \''.$indicatorId.'\');';
                        }
                        
                        if ($row['META_DATA_ID'] == '1668505983686113') {
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

                        $cards[] = '<a href="' . $linkHref . '" target="' . $linkTarget . '" style="'.$cartbgColor.$bgImageStyle.'" onclick="' . $linkOnClick . '" data-code="' . $k . '" data-modulename="' . $this->lang->line($row['NAME']) . '" class="vr-menu-tile mix ' . $k . $class . '" data-metadataid="' . $row['META_DATA_ID'] . '" data-pfgotometa="1">';
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
                                        $cards[] = '<div class="vr-menu-name" data-app-name="true" style="'.$appInfoTextStyle.'">' . $this->lang->line($row['NAME']) . '</div>';
                                        if (issetParam($row['menucode'])) {
                                            $cards[] = '<div class="vr-menu-code mt6" style="'.$appInfoTextStyle.'" data-app-code="true">' . issetParam($row['menucode']) . '</div>';
                                        }   
                                    $cards[] = '</div>';
                                $cards[] = '</div>';
                            $cards[] = '</div>';
                        $cards[] = '</a>';
                    }                                 
                }
                           
                echo '<li class="hidden filter' . $activeClass . '" data-filter="' . 999999 . '">';
                echo '<span class="title">Бусад</span>';                    
                echo '</li>';                      
                ?>
            </ul>
        </div>
        <div id="mixgrid" class="appmenu-table-cell-right mix-grid">
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
        $('.appmenu-table-cell-left').attr('style', 'height: ' + (sidebarHeight + 200) + 'px');  
        
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
