<!--<button id="playVideo">Play</button>
<button id="pauseVideo">Pause</button>
<br>-->
<div class="slick-videotoimage-carousel d-flex align-items-center mt20">
    <?php
    if (!isset($ws)) {
        $ws = new Mdwebservice();
    }

    $row = $this->row;
    $isView = (issetParam($row['viewMode']) == 'view') ? true : false;

    if ($isView) {
        $renderParamControlFnc = 'renderViewParamControl';
    } else {
        $renderParamControlFnc = 'renderParamControl';
    }

    foreach ($this->fillParamData as $rk => $rowData) {
        $controls = $position = array();
        
        foreach ($row['data'] as $ind => $val) {

            if ($val['THEME_POSITION_NO']) {
                $position[$val['THEME_POSITION_NO']] = issetParam($rowData[$val['LOWER_PARAM_NAME']]);
            } 

            $controls[] = '<div data-cell-path="' . $val['PARAM_REAL_PATH'] . '" class="d-none">';
                $controls[] = Mdwebservice::{$renderParamControlFnc}($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . ']['.$rk.'][]', $val['PARAM_REAL_PATH'], $rowData);
            $controls[] = '</div>';
        }        
        
        $position1 = issetParam($position[1]);
        $position2 = issetParam($position[2]);
        $position3 = issetParam($position[3]);
        $position4 = issetParam($position[4]);
        $parsed = date_parse($position1);
        $startTimeSecond = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];    
        $parsed = date_parse($position2);
        $endTimeSecond = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];    
        $startTime = str_replace('$00:', '', '$'.$position1);
        $endTime = str_replace('$00:', '', '$'.$position2);        
        
    ?>
    <div class="bp-detail-row saved-bp-row mr20<?php echo !$rk ? ' active' : ''; ?>">
        <a href="javascript:;" data-starttime="<?php echo $startTimeSecond ?>" data-endtime="<?php echo $endTimeSecond ?>" class="go-video-startendtime">
            <div class="detail_cart_slider_imagevideo">
                <canvas id="canvas_<?php echo $rk ?>" width="500" height="280"></canvas>
            </div>        
        </a>
        <input type="hidden" name="param[<?php echo $row['code']; ?>.mainRowCount][]" value="<?php echo $rk; ?>"/>
        <input type="hidden" name="param[<?php echo $row['code']; ?>.rowState][<?php echo $rk; ?>][]" data-path="<?php echo $row['code']; ?>.rowState" data-field-name="rowState" data-isclear="0" value="unchanged">

        <?php        
        $controls[] = '<div class="mt-1">
            <div class="d-flex flex-column mt10">
                <div class="pos-1"><a href="javascript:;" data-starttime="'.$startTimeSecond.'" data-endtime="'.$endTimeSecond.'" class="go-video-startendtime" style="color:#3ea6ff;font-weight: bold;">'.$startTime.' - '.$endTime.'</a></div>
                <div class="pos-3">'.$position3.'</div> 
            </div>
        </div>';

        if (!$isView) {
            $controls[] = html_tag('a', array('href' => 'javascript:;', 'class' => 'btn red btn-xs bp-remove-row', 'title' => $this->lang->line('delete_btn')), '<i class="icon-cross3"></i>', $row['isShowDelete']);
        }

        echo implode('', $controls);
        ?>
    </div>
    <?php
    }
    ?>
</div>

<style type="text/css">
    .detail_cart_slider_imagevideo {
        height: 100px;
        width: 150px;
        border-radius: 10px;
        background-position: center;
        background-size: cover;        
    }
    .detail_cart_slider_imagevideo canvas {
        display: none;
    }
    .slick-videotoimage-carousel .bp-detail-row.active .detail_cart_slider_imagevideo {
        border: 2px solid #065fd4;
    }
</style>

<script type="text/javascript">
    var myvideo = document.getElementById('bp-video-id');
    var videoToImageCheckInterval;
    var dtlRowCount = $('div[data-section-path="ecmContentEvent"]').find('.bp-detail-row').length - 1;
    var dc = 0;
    var $rowDtl = $('div[data-section-path="ecmContentEvent"]').find('.bp-detail-row');
    var videoTimeToSaveInterval;
    
    $(function() {
        $('.go-video-startendtime').click(function(){
            var start = $(this).data('starttime');
            var end = $(this).data('endtime');
            playVideo(start, end);
        });
        
        $('div[data-section-path="ecmContentEvent"]').on('click', '.bp-detail-row', function(){
            $('div[data-section-path="ecmContentEvent"]').find('.bp-detail-row').removeClass('active');
            $(this).addClass('active');
        });
        
        $('#bp-video-id').hide().parent().append('<h4>Screen shot loading...</h4>');
        
        videoToImageCheckInterval = setInterval(function () {
          videoToImageFn(dc);          
        }, 800);        
        
        $('.slick-videotoimage-carousel').slick({
            // autoplay: true,
            // autoplaySpeed: 1500,
            infinite: false,
            slidesToShow: 4,
            slidesToScroll: 1,
            arrows: true,
            variableWidth: true,
            dots: false,
            prevArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer" class=""><i class="far fa-angle-left" style="font-size:22px;margin: 9px;"></i></div>',
            nextArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer" class=""><i class="far fa-angle-right" style="font-size:22px;margin: 9px;"></i></div>'       
        });    
        setTimeout(function() {
            $(".slick-videotoimage-carousel").css("width", $(window).width() - 800);
        }, 300);        
        
        
        
        /**
         * 
         * Video reading time to save
         */
//        myvideo.controls = false;
//        
//        $('#playVideo').click(function(e){
//            myvideo.play();
//            videoTimeToSaveInterval = setInterval(function () {
//                $.ajax({
//                    type: "post",
//                    url: "Mdcontentui/contentVisitorLog",
//                    data: { recordId: 11223344, duration: myvideo.currentTime },
//                    success: function (data) {                    
//                    }
//                });
//            }, 5000);              
//            e.preventDefault();
//        });        
//        
//        $('#pauseVideo').click(function(e){
//            myvideo.pause();
//            clearInterval(videoTimeToSaveInterval);
//            e.preventDefault();
//        });        
//        
//        $.ajax({
//            type: "post",
//            url: "Mdcontentui/contentDurationVisitorLog",
//            data: { recordId: 11223344 },
//            success: function (data) {                  
//                myvideo.currentTime = data;
//            }
//        });        
                
    });        
    
    function videoToImageFn(param) {
        
        var $self = $rowDtl.eq(dc).find('.go-video-startendtime');        
            myvideo.currentTime = $self.attr('data-starttime');

            setTimeout(function() {
                var canvas = document.getElementById('canvas_'+param);
                canvas.getContext('2d').drawImage(myvideo, 0, 0, 500, 280);
                var img = canvas.toDataURL('image/png');			
                $rowDtl.eq(dc).find('.detail_cart_slider_imagevideo').css('background-image', "url('"+img+"')");
                dc++;
                
                if (param == dtlRowCount) {            
                    clearInterval(videoToImageCheckInterval);
                    myvideo.currentTime = 0;
                    $('#bp-video-id').show().parent().find('h4').remove();
                }                
            }, 500);               
    }
    
    function playVideo(startTime, endTime) {

        function checkTime() {
            if (myvideo.currentTime >= endTime) {
               myvideo.pause();
            } else {
               setTimeout(checkTime, 100);
            }
        }

        myvideo.pause();
        myvideo.currentTime = startTime;
        setTimeout(function () {
            myvideo.play();
            checkTime();
        }, 100);
    }    
</script>