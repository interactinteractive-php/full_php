
<script type="text/javascript">
    var $checkList_<?php echo $this->uniqId; ?> = $('#mv-checklist-render<?php echo $this->uniqId ?>');
    var $checkListMenu_<?php echo $this->uniqId; ?> = $checkList_<?php echo $this->uniqId; ?>.find('.mv-checklist-menu');
    var $checkListContent_<?php echo $this->uniqId; ?> = $checkList_<?php echo $this->uniqId; ?>.find('.main-content');

    $(function() { 
    
        // $checkListMenu_<?php echo $this->uniqId; ?>.height($(window).height() - $checkListMenu_<?php echo $this->uniqId; ?>.offset().top - 390);
        $checkListMenu_<?php echo $this->uniqId; ?>.find('a[data-stepid="<?php echo $this->uniqId . '_1'; ?>"]').trigger('click');
        $checkListMenu_<?php echo $this->uniqId; ?>.find('a[data-stepid="<?php echo $this->uniqId . '_1'; ?>"]').addClass('active');
        /* $checkListContent_<?php echo $this->uniqId; ?>.height($(window).height() - $checkListMenu_<?php echo $this->uniqId; ?>.offset().top - 390); */
        
        $checkListMenu_<?php echo $this->uniqId; ?>.on('click', 'a.nav-link:not(.disabled)', function() {
            var $this = $(this);
        
            $checkListMenu_<?php echo $this->uniqId; ?>.find('a.nav-link.active').removeClass('active');
            $this.addClass('active');
            
            var rowJson = $this.attr('data-json'), uniqId = $this.attr('data-uniqid'), indicatorId = $this.attr('data-indicatorid'), 
                isComment = $this.hasAttr('data-iscomment') ? $this.attr('data-iscomment') : 0;
            
            if (typeof rowJson === 'undefined') {
                if ($this.parent().hasClass('nav-group-sub-mv-opened')) {
                    $this.parent().removeClass('nav-group-sub-mv-opened');
                } else {
                    $this.parent().addClass('nav-group-sub-mv-opened');
                }
                return;
            }
            
            /* if (typeof rowJson !== 'object') {
                var jsonObj = JSON.parse(html_entity_decode(rowJson, 'ENT_QUOTES'));
            } else {
                var jsonObj = rowJson;
            } */
        });

    });

    function videoToImageFn_<?php echo $this->uniqId; ?>(param) {
        
        var myvideo = document.getElementById('bp-video-id');
        var $self = $('.go-video-startendtime');        
            /* myvideo.currentTime = $self.attr('data-starttime'); */
            myvideo.currentTime = 0;

        setTimeout(function() {
            /* var canvas = document.getElementById('canvas_'+param); */
            var canvas = document.getElementById('canvas');
            canvas.getContext('2d').drawImage(myvideo, 0, 0, 500, 280);
            var img = canvas.toDataURL('image/png');			
            $('.detail_cart_slider_imagevideo').css('background-image', "url('"+img+"')");
            dc++;
            
            if (param == dtlRowCount) {            
                clearInterval(videoToImageCheckInterval);
                myvideo.currentTime = 0;
                $('#bp-video-id').show().parent().find('h4').remove();
            }                
        }, 500);               
    }

    function playVideoInit_<?php echo $this->uniqId; ?> (contentId, uniqId) {
        var myvideo = document.getElementById('bp-video-id');
        myvideo.controls = false;
        
        $('#playVideo' + uniqId).click(function(e){
            myvideo.play();
            videoTimeToSaveInterval = setInterval(function () {
                $.ajax({
                    type: "post",
                    url: "mdcontentui/contentVisitorLog",
                    data: { recordId: contentId, duration: myvideo.currentTime },
                    success: function (data) {                    
                    }
                });
            }, 5000);              
            e.preventDefault();
        });        
        
        $('#pauseVideo' + uniqId).click(function(e){
            myvideo.pause();
            clearInterval(videoTimeToSaveInterval);
            e.preventDefault();
        });        
        
        $.ajax({
            type: "post",
            url: "mdcontentui/contentDurationVisitorLog",
            data: { recordId: contentId },
            success: function (data) {                  
                myvideo.currentTime = data;
            }
        });    
          
    }
    function startSubject_<?php echo $this->uniqId ?>(element, filePath, contentId, uniqId) {
        var $this = $(element),
            $parent = $this.closest('.<?php echo $this->uniqId ?>');
        var html = '';
        
        html = '';
        html += '<a href="javascript:;" data-starttime="" data-endtime="" class="go-video-startendtime">';
            html += '<div class="detail_cart_slider_imagevideo">';
                
                html += '<video width="960" height="500" controls id="bp-video-id" data-id="'+ contentId +'">';
                    html += '<source src="'+ filePath +'" type="video/mp4">';
                    html += 'Your browser does not support HTML5 video.';
                html += '</video>';
                    html += '<button class=""id="playVideo'+ uniqId +'" data-id="'+ contentId +'">Play</button>';
                    html += '<button class=""id="pauseVideo'+ uniqId +'" data-id="'+ contentId +'">Pause</button>';
                html += '<br>';
            html += '</div>';
        html += '</a>';
        var $dialogName = 'dialog-preview-' + uniqId;
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        
        $("#" + $dialogName).empty().append(html).promise().done(function () {
            playVideoInit_<?php echo $this->uniqId; ?>(contentId, uniqId);
        });
        $("#" + $dialogName).dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: 'Сургалт үзэх',
            width: 1000,
            height: "auto",
            modal: true,
            close: function() {
                if (typeof videoTimeToSaveInterval !== 'undefined')
                    clearInterval(videoTimeToSaveInterval);
                $("#" + $dialogName).empty().dialog('close');
            },
            buttons: [
                {
                    text: plang.get('close_btn'),
                    class: 'btn blue-madison btn-sm',
                    click: function() {
                        if (typeof videoTimeToSaveInterval !== 'undefined')
                            clearInterval(videoTimeToSaveInterval);
                        $("#" + $dialogName).dialog('close');
                    }
                }
            ]
        });
        $("#" + $dialogName).dialog('open');
    } 

    function nextQuestion_<?php echo $this->uniqId ?>(element) {
        var $this = $(element),
            $parent = $this.closest('.<?php echo $this->uniqId ?>');
    } 

    $('a[href="#tab-question-section<?php echo $this->uniqId ?>"]').on('shown.bs.tab', function(e) {
        $('.mv_checklist_02_sub').first().trigger('click');
        /* $('.position-timer').show(); */
    });

    $('a[href="#tab-info-section-<?php echo $this->uniqId ?>"]').on('shown.bs.tab', function(e) {
        /* $('.position-timer').hide(); */
    });

    function checkMenuFnc<?php echo $this->uniqId ?>(element) {
        var _this = $(element),
            stepKey = _this.attr('data-stepid'),
            rowJson = JSON.parse(_this.attr('data-json')),
            $parentSelector = _this.closest('#mv-checklist-render<?php echo $this->uniqId ?>');
        $parentSelector.find('.main-content > .mv-checklist-render-comment').hide();
        
        if ($parentSelector.find('.main-content > .mv-checklist-render-comment[data-stepkey="'+ stepKey +'"]').length < 1) {
            var _html = '';
            _html += '<div class="mv-checklist-render-comment p-3" data-stepkey="'+ stepKey +'">';
                _html += '<p class="question-txt">'+ rowJson['position3'] +'</p>';
                _html += '<p class="comment-txt mb-4">'+ rowJson['position4'] +'</p>';
                _html += '<div class="w-100 text-center">';
                    _html += '<button type="button" class="btn btn-sm btn-circle btn-success bp-btn-save" onclick="startSubject_<?php echo $this->uniqId ?>(this, \''+ rowJson['position5'] +'\', \''+ rowJson['position6'] +'\', \'<?php echo getUID() ?>\')">';
                        _html += 'Сургалт үзэх';
                    _html += '</button>';
                _html += '</div>';
                _html += '<div class="w-100 text-center">';
                    _html += '<img src="middleware/assets/img/process/background/watch.png" />';
                _html += '</div>';
            _html += '</div>';
            $parentSelector.find('.main-content').append(_html);
        } else {
            $parentSelector.find('.main-content > .mv-checklist-render-comment[data-stepkey="'+ stepKey +'"]').show();
        }
        
    };
</script>