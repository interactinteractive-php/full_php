<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?> 
<?php 
$tmparr = $hideTmparr = array();
$headerPathArray = $rowsArray = $position1GroupArray = array();
if (issetParamArray($this->relationComponentsConfigData['header'])) {
    $headerPathArray = $this->relationComponentsConfigData['header'];
    $headerArray = Arr::groupByArrayOnlyRow($headerPathArray, 'trg_indicator_path', false);
}

if (issetParamArray($this->relationComponentsConfigData['rows'])) {
    $rowsPathArray = $this->relationComponentsConfigData['rows'];
    $rowsArray = Arr::groupByArrayOnlyRow($rowsPathArray, 'trg_indicator_path', false);
    
    $position1PathArr = explode('.', $rowsArray['position-1']['src_indicator_path']);
    $position1GroupArray = Arr::groupByArrayOnlyRows($this->rowData[$position1PathArr['0']], $position1PathArr['1']);
    $index= $counter= 0;
    foreach ($position1GroupArray as $position1Key =>  $position1Grp) { 
        $position1Group = $position1Grp['0'];
        $tmparr = $hideTmparr = array();
        foreach ($position1Grp as $rKey => $rVal) {
            
            for ($c=1; $c<=10; $c++) {
                if (issetParam($rowsArray['position-' . $c]['src_indicator_path'])) {
                    $position1PathArr = explode('.', $rowsArray['position-' . $c]['src_indicator_path']);
                    if (issetParam($position1PathArr['1'])) {
                        $tmp['position' . $c] = issetParam($rVal[$position1PathArr['1']]);
                    }
                }
            }
            
            $tmp['position-recordid'] = $tmp['position-starttime'] = $tmp['position-endtime'] = '';

            if (issetParam($rVal['START_TIME'])) {
                $parsed = explode(':', $rVal['START_TIME']);
                $timeSecond = $parsed['0'] * 3600 + $parsed['1'] * 60 + $parsed['2'];    
                $tmp['position-starttime'] = $timeSecond;
            }

            if (issetParam($rVal['CONTENT_ID'])) {
                $tmp['position-recordid'] = $rVal['CONTENT_ID'];
            }

            if (issetParam($rVal['END_TIME'])) {
                $parsed = explode(':', $rVal['END_TIME']);
                $timeSecond = $parsed['0'] * 3600 + $parsed['1'] * 60 + $parsed['2'];    

                $tmp['position-endtime'] = $timeSecond;
            }
            
            array_push($tmparr, $tmp);
            array_push($hideTmparr, $rVal);
        }
    }
}

?>
<div class="wg-form-paper <?php echo $this->uniqId ?> " id="mv-checklist-render<?php echo $this->uniqId ?>">
    <div class="wg-form">
        <div class="card card-side no-border px-0">
            <div class="card-body">
                <div class="w-100 main-content" style="background-color: #F9F9F9">
                    <div class="mv-checklist-render-comment p-3">
                        <div class="w-100 text-center vid-component">
                            <?php 
                                $html = '';
                                $html .= '<a href="javascript:;" data-starttime="" data-endtime="" class="go-video-startendtime">';
                                    $html .= '<div class="detail_cart_slider_imagevideo detail_cart_slider_mainvideo'. $this->uniqId .'" data-starttime="'. issetParam($tmparr['0']['position-starttime']) .'">';
                                        $html .= '<video width="100%" controls id="main_video_'. $this->uniqId .'">';
                                            $html .= '<source src="'. issetParam($tmparr['0']['position1']) .'" type="video/mp4" >';
                                            $html .= 'Your browser does not support HTML5 video.';
                                        $html .= '</video>';
                                        $html .= '<button class="d-none" id="playVideo'. $this->uniqId .'" data-id="main_video_'. $this->uniqId .'">Play</button>';
                                        $html .= '<button class="d-none" id="pauseVideo'. $this->uniqId .'" data-id="main_video_'. $this->uniqId .'">Pause</button>';
                                    $html .= '</div>';
                                $html .= '</a>';
                                echo $html;
                            ?>
                        </div>
                        <div class="text-center overflow-auto d-flex col-component">
                            <?php foreach ($tmparr as $key => $row) { ?>
                                <div class="media-component">
                                    <?php echo '<div class="detail_cart_slider_imagevideo'. $this->uniqId .'" style="height: 115px;" data-starttime="'. issetParam($row['position-starttime']) .'" data-endtime="'. issetParam($row['position-endtime']) .'" data-recordid="'. issetParam($row['position-recordid']) .'">'
                                                . '<canvas id="canvas_'. $key . '_' . $this->uniqId .'" width="125" class="d-none" height="125"></canvas>'
                                                . '<canvas id="canvaslg_'. $key . '_' . $this->uniqId .'" width="940" class="d-none" height="500"></canvas>'
                                                . '<video width="100%" controls id="video_' . $key . '_' . $this->uniqId .'"  class="d-none" >'
                                                    . '<source src="'. issetParam($row['position1']) .'" type="video/mp4" data-id="' . $key . '_' . $this->uniqId .'" class="mx-auto videotoimg" height="110px"/>'
                                                . '</video>'
                                            . '</div>'; ?>
                                    <p class="m-0 text-left text-info"><?php echo checkDefaultVal($row['position2'], 'pos-2') ?></p>
                                    <p class="m-0 text-left"><?php echo checkDefaultVal($row['position3'], 'pos-3') ?></p>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo issetParam($this->uniqCss) ?>
<?php echo issetParam($this->uniqJs) ?>
<script type="text/javascript">
    var mainVideo<?php echo $this->uniqId ?> = document.getElementById('main_video_<?php echo $this->uniqId ?>');
    var myvideo<?php echo $this->uniqId ?> = document.getElementById('main_video_<?php echo $this->uniqId ?>');
    var videoToImageCheckInterval<?php echo $this->uniqId ?>;
    var dtlRowCount<?php echo $this->uniqId ?> = $('.detail_cart_slider_imagevideo<?php echo $this->uniqId ?>').length;
    var dc<?php echo $this->uniqId ?> = 0;

    $(function() {
        $('#main_video_<?php echo $this->uniqId ?>').hide()/* .parent().append('<h4>'+ plang.get('waiting_video_play') +'...</h4>') */;
        videoToImageCheckInterval<?php echo $this->uniqId ?> = setInterval(function () {
            videoToImageFn<?php echo $this->uniqId ?>('#mv-checklist-render<?php echo $this->uniqId ?> .videotoimg:eq('+ dc<?php echo $this->uniqId ?> +')');          
        }, 800);        
    });


    function videoToImageFn<?php echo $this->uniqId ?>(param) {
        try {
            var $self = $(param);
                dataId = $self.attr('data-id'),
                startTimer = parseInt($(param).closest('.detail_cart_slider_imagevideo<?php echo $this->uniqId ?>').attr('data-starttime'));
            if (startTimer > 0) {
                /* if (typeof startTimer === '') */
                    myvideo<?php echo $this->uniqId ?> = document.getElementById('video_' + dataId),
                    myvideo<?php echo $this->uniqId ?>.currentTime = startTimer;
                    
    
                setTimeout(function() {
                    canvas = document.getElementById('canvas_' + dataId);
                    canvas.getContext('2d').drawImage(myvideo<?php echo $this->uniqId ?>, 0, 0, 125, 125);	
                    var img = canvas.toDataURL('image/png');
                    $(param).closest('.detail_cart_slider_imagevideo<?php echo $this->uniqId ?>').css('background-image', "url('"+img+"')");
    
                    canvasLg = document.getElementById('canvaslg_' + dataId);
                    canvasLg.getContext('2d').drawImage(myvideo<?php echo $this->uniqId ?>, 0, 0, 940, 500);
                    var imgLg = canvasLg.toDataURL('image/png');		
                                
                    if (dc<?php echo $this->uniqId ?> === 0) {
                        $('#main_video_<?php echo $this->uniqId ?>').closest('.detail_cart_slider_imagevideo').css('background-image', "url('"+imgLg+"')");
                    }
    
                    dc<?php echo $this->uniqId ?>++;
                    if (dc<?php echo $this->uniqId ?> == dtlRowCount<?php echo $this->uniqId ?>) {            
                        clearInterval(videoToImageCheckInterval<?php echo $this->uniqId ?>);
                        myvideo<?php echo $this->uniqId ?>.currentTime = 0;
                    }
                }, 500);
            }
        } catch (error) {
            
        }
    }

    $('body').on('click', '.detail_cart_slider_mainvideo<?php echo $this->uniqId ?>', function (e) {
        if (typeof $('#main_video_<?php echo $this->uniqId ?>').attr('data-change') === 'undefined') {
            $('.detail_cart_slider_imagevideo<?php echo $this->uniqId ?>:eq(0)').trigger('click');
        }
    });

    $('body').on('click', '.detail_cart_slider_imagevideo<?php echo $this->uniqId ?>', function (e) {
        var _this = $(this),
            video = _this.find('source.videotoimg');

        var start = _this.data('starttime');
        var end = _this.data('endtime');
        if (typeof videoTimeToSaveInterval<?php echo $this->uniqId ?> !=='undefined')
            clearInterval(videoTimeToSaveInterval<?php echo $this->uniqId ?>);
            
        $('#main_video_<?php echo $this->uniqId ?>').show().parent().find('h4').remove();
        $('#main_video_<?php echo $this->uniqId ?>').attr('data-change', '1');

        function checkTime() {
            if (mainVideo<?php echo $this->uniqId ?>.currentTime >= end) {
               mainVideo<?php echo $this->uniqId ?>.pause();
               clearInterval(videoTimeToSaveInterval<?php echo $this->uniqId ?>);
            } else {
               setTimeout(checkTime, 100);
            }
        }

        mainVideo<?php echo $this->uniqId ?>.pause();
        mainVideo<?php echo $this->uniqId ?>.currentTime = start;        
        setTimeout(function () {
            mainVideo<?php echo $this->uniqId ?>.play();
            checkTime();
        }, 100);
        
        $.ajax({
            type: "post",
            url: "mdcontentui/contentVisitorLog",
            data: { recordId: _this.attr('data-recordid'), duration: mainVideo<?php echo $this->uniqId ?>.currentTime },
            success: function (data) {                   
                if (!data && Config::getFromCache('IS_CONTENT_VIEW')) {
                    mainVideo<?php echo $this->uniqId ?>.controls = false;
                }
            }
        });         

        if (typeof _this.attr('data-recordid') !== 'undefined' && _this.attr('data-recordid')) {
            videoTimeToSaveInterval<?php echo $this->uniqId ?> = setInterval(function () {
                $.ajax({
                    type: "post",
                    url: "mdcontentui/contentVisitorLog",
                    data: { recordId: _this.attr('data-recordid'), duration: mainVideo<?php echo $this->uniqId ?>.currentTime },
                    success: function (data) {                   
                    }
                });             
                e.preventDefault();
            }, 1000);  
        }
    });
</script>