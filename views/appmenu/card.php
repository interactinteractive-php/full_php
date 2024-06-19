<div class="appmenu-table appmenu-newdesign-<?php echo $this->isAppmenuNewDesign; ?>">
    <div style="text-align: center;width: 100%;text-transform: uppercase;font-size: 16px;font-weight: 600;padding-top: 15px;"><?php echo $this->getInfo['NAME']; ?></div>
    <div class="appmenu-table-row">          
        <div id="mixgrid" class="pt0 appmenu-table-cell-right mix-grid appmenu-card-html">
        </div>
    </div>
</div>

<style>
    .new-vlogo-link-selector {
        padding-top: 12px !important;
        padding-bottom: 12px !important
    }
    .new-vlogo-link-selector .header-logo img {
        max-height: 33px !important;
    }    
    .appmenu-newdesign-1 .appmenu-table-cell-right {
        background-color: #fff;
    }
    .appmenu-table.appmenu-newdesign-1 {
        background-color: #fff;
    }
    .back-item-btn {
        background: #FFFFFF;
        border: 1px solid #E6E6E6;
        box-sizing: border-box;
        border-radius: 10px;
        width: 40px;
        height: 40px;
        text-align: center;
        padding-top: 6px;
    }      
    .item-card-toptitle {
        color: #3C3C3C;
        font-size: 18px;
        font-family: 'Rubik';
        text-transform: uppercase;
        font-weight: 600;        
    }
    .appmenu-newdesign-1 .white-card-menu .vr-menu-title .vr-menu-row .vr-menu-name, 
    .appmenu-newdesign-1 .appmenu-table-cell-right .vr-menu-title .vr-menu-row .vr-menu-name {
        max-height: 38px;
    }
    .appmenu-newdesign-1 .white-card-menu .vr-menu-tile, .appmenu-newdesign-1 .appmenu-table-cell-right .vr-menu-tile {
        height: 98px;
    }    
    .appmenu-newdesign-1 .white-card-menu .vr-menu-title, .appmenu-newdesign-1 .appmenu-table-cell-right .vr-menu-title {
        margin-top: 30px;
    }
    .card-ischild-0 .acard-is-child-div {
        display: none;
    }
    .acard-is-child-div i {
        color: #ffffff9e;
    }
    .vr-menu-tile:hover .acard-is-child-div i {
        color: #fff;
    }
    .appmenu-newdesign-1 .white-card-menu .vr-menu-tile.random-border-radius3, 
    .appmenu-newdesign-1 .appmenu-table-cell-right .vr-menu-tile.random-border-radius3 {
        border-radius: 15px;
    }
</style>

<script type="text/javascript">
    function itemCardGroupInit(id, elem) {
      $.ajax({
        type: "post",
        url: "appmenu/cardHtml",
        data: { parentId: id },
        dataType: "json",
        beforeSend: function () {
          Core.blockUI({
            message: "Loading...",
            boxed: true
          });
        },
        success: function (data) {
            if (data.html == '') {
                alert('Хоосон байна.');
                Core.unblockUI();
                return;
            }
//          if (id == '') {
//            $(".pos-card-layout")
//              .find(".card-options")
//              .hide()
//              .addClass("justify-content-center");
//            $(".pos-card-layout").find(".back-item-btn").hide();
//            $(".pos-card-layout").find(".item-card-toptitle").text("Ангилалууд");
//          }

            var htmlStr = '';
            if (id != '') {
                htmlStr += '<h2 class="ml-2 pt10 pb10" style="display: flex;width: 100%;text-transform: uppercase;font-family: Arial;font-size: 16px;"><a href="javascript:;" class="back-item-btn d-block" data-parentid=""><i class="icon-arrow-left8" style="color:#000"></i></a><span class="pt6 pl12">'+$(elem).data('modulename')+'</span></h2>';
            }
            htmlStr += data.html;

            var $mainContent = $(".appmenu-card-html");
            $mainContent.empty().append(htmlStr);
            Core.unblockUI();
            $('html, body').scrollTop(0);          
        }
      });
    }
    
    itemCardGroupInit('');
    
    $(function(){                

        $(document).on('click', '.back-item-btn', function(e){
            itemCardGroupInit('');
        });
        
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
