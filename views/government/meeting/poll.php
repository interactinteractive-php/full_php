<?php  $colorData = array('#ff7e79','#ff8e51','#fec244','#39e0d0','#48c6f3','#6373ed'); ?>
<div class="card-header bg-transparent poll-refresh" style="padding: 4px 15px; margin-top: 5px;" data-id="<?php echo isset($this->pollData['id']) ? $this->pollData['id'] : '' ?>">
    <h6 class="card-title mg-b-0 float-left">Санал асуулга</h6>
    <span class="pull-right poll-counter" style="position: relative; width: 40px; top: 0px;">
        <span id="poll-count" class="font-size-15 font-weight-bold"><?php echo isset($this->pollData['scl_posts_question_list']) ?  '1/' . sizeof($this->pollData['scl_posts_question_list']) : '' ?></span>
        <a class="carousel-control-prev" href="#carouselSlidePoll_<?php echo $this->uniqId ?>" onclick="slidepolltop<?php echo $this->uniqId ?>('2')" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"><i class="icon-arrow-left32"></i></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselSlidePoll_<?php echo $this->uniqId ?>" onclick="slidepolltop<?php echo $this->uniqId ?>('3')" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"><i class="icon-arrow-right32"></i></span>
            <span class="sr-only">Next</span>
        </a>
        </span>
    <nav class="nav nav-card-icon">
        <a href="javascript:void(0);"><i data-feather="refresh-ccw"></i></a>
    </nav>
</div>
<?php if (isset($this->pollData['scl_posts_question_list']) || isset($this->pollData['scl_posts_result_list'])) {
        (Array) $rowparam = array();
        $rowparam['posttypeid'] = '3';
        $rowparam['id'] = $this->pollData['id'];
        $rowJson = htmlentities(json_encode($rowparam), ENT_QUOTES, 'UTF-8');
    ?>
<div class="card-header bg-transparent border-none">
    <span class="line-height-normal font-size-13 font-weight-bold"><a onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)"  data-rowdata="<?php echo $rowJson; ?>" href="javascript:;" class="text-black "><?php echo $this->pollData['description'] ?></a></span>
</div>
<?php } ?>
<div class="polldata_<?php echo $this->uniqId ?>" style="padding: 15px; padding-top: 0">
    <?php 
    if (isset($this->pollData['scl_posts_question_list']) || isset($this->pollData['scl_posts_result_list'])) {
        
        echo Form::create(array('class' => 'form-horizontal', 'id' => 'savepoll' . $this->uniqId, 'method' => 'post', 'enctype' => 'multipart/form-data','action' => 'javascript:void(0)'));
        
        $nextbtn = (isset($this->pollData['scl_posts_question_list']) && sizeof($this->pollData['scl_posts_question_list']) > 1) ? true : false;
        
        if (isset($this->pollData['scl_posts_question_list']) && Date::currentDate('Y-m-d') <= issetParam($this->pollData['enddate'])) {
            $resultList = $this->pollData['scl_posts_result_list'][0];
            foreach ($this->pollData['scl_posts_question_list'] as $key => $row) { ?>
                <div class="polldiv flex-column pollform_<?php echo $key; ?> " data-limitcount="<?php echo isset($row['limitcount']) ? $row['limitcount'] : '' ?>" data-key="<?php echo $key; ?>" <?php echo ($key == 0) ? 'style="height:250px;"' : 'style="display: none;"' ?>>
                    <div style="height: 200px;">
                        <div class="line-height-normal font-size-13 text-two-line" style="height:30px;">
                            <a onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)" data-rowdata="<?php echo $rowJson; ?>" href="javascript:;" class="text-black "><?php echo $row['questiontxt'] ?></a>
                        </div>
                        <?php 
                            echo Form::hidden(array('name' => 'postId', 'value' => $row['postid']));
                            echo Form::hidden(array('name' => 'questionid['. $row['id'] .']', 'value' => $row['id']));
                            $answertypeid = $row['answertypeid'] == '1' ? 'radio' : 'checkbox';
                        ?>
                        <div class="form-group pt-2">
                            <div class="overflow-auto mb-1" style="height: 160px;">
                                <?php if (isset($row['scl_posts_answer_list']) && $row['answertypeid'] !== '3') {
                                foreach ($row['scl_posts_answer_list'] as $srow) { ?>
                                    <div class="d-flex mb-1">
                                        <input type="<?php echo $answertypeid; ?>" id="answerid<?php echo $row['id'] . '_' . $srow['id']; ?>" name="answerid[<?php echo $row['id']; ?>][]" value="<?php echo $srow['id'] ?>">
                                        <label class="ml-1" for="answerid<?php echo $row['id'] . '_' . $srow['id']; ?>"><?php echo $srow['answertxt'] ?></label>
                                    </div>
                                <?php }
                                } else { ?>
                                <div class="d-flex mb-1">
                                    <input type="hidden" name="answerid[<?php echo $row['id']; ?>][]" value="<?php echo $row['scl_posts_answer_list'][0]['id'] ?>">
                                    <textarea name="answerdesc[<?php echo $row['id']; ?>][]" style="width: 100%; height: 140px; resize: none;"></textarea>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="mt-auto d-flex align-items-center justify-content-center poll_bottom_btn mb-2">
                        <button type="button" class="btn btn-customer-style bg-grey rounded-round <?php echo ($nextbtn) ? '' : 'd-none'; ?> mr-2 pr20" onclick="slidepoll<?php echo $this->uniqId ?>(this, '2')"><i class="icon-arrow-left5"></i> Өмнөх</button>
                        <button type="button" class="btn btn-customer-style bg-grey rounded-round slidepoll <?php echo ($nextbtn) ? '' : 'd-none'; ?> pl20" onclick="slidepoll<?php echo $this->uniqId ?>(this, '3')">Дараах <i class="icon-arrow-right5"></i></button>
                        <button type="button" class="btn bg-green rounded-round px-5 mr-2 <?php echo ($nextbtn) ? 'd-none' : ''; ?> savePollFormData" onclick="savePollFormData<?php echo $this->uniqId ?>('<?php echo '#savepoll' . $this->uniqId ?>', this)">Санал өгөх</button>
                    </div>
                </div>
            <?php }
        } elseif (isset($this->pollData['scl_posts_result_list'])) {
            $resultList = $this->pollData['scl_posts_result_list'][0];
            if ($resultList['scl_result_question_list']) { 
                ?>
                <div id="carouselSlidePoll_<?php echo $this->uniqId ?>" class="carousel slide" data-interval="false"  data-ride="carousel" >
                    <ol class="carousel-indicators d-none">
                        <?php if ($resultList['scl_result_question_list']) {
                            foreach ($resultList['scl_result_question_list'] as $sskey => $row) { ?>
                                <li data-target="#carouselSlide2" data-slide-to="<?php echo $sskey; ?>" class="<?php echo $sskey == '0' ? 'active' : ''; ?>" data-id="<?php echo $row['id'] ?>"></li>
                        <?php }} ?>
                    </ol>
                    <div class="carousel-inner" >
                    <?php 
                    foreach ($resultList['scl_result_question_list'] as $sskey => $rrow) { ?>
                        <div class="carousel-item <?php echo $sskey == '0' ? 'active' : ''; ?> polldiv poll_result<?php echo $this->uniqId ?> ">
                            <div class="form-group">
                                <h6 class="line-height-normal font-size-13"><?php echo $rrow['questiontxt'] ?></h6>
                                <div id="poll-box" style="height: 200px; overflow: auto; overflow-x: hidden;">
                                    <?php if ($rrow['scl_post_result_answer_list']) {
                                        foreach ($rrow['scl_post_result_answer_list'] as $arow) { ?>
                                            <div class="result_box p-2">
                                                <?php if (issetParam($arow['isother']) != '1') { ?>
                                                    <span class="badge badge-mark border-gray mt3 float-left"></span>
                                                <?php } ?>
                                                <div class="ml-3">
                                                    <?php if (issetParam($arow['isother']) == '1') { ?>
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <span class="mr-2 text-left"><?php echo issetParam($arow['answerdescription']); ?></span>
                                                        </div>
                                                    <?php }  
                                                    else { ?>
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <span class="mr-2 text-left"><?php echo $arow['answertxt']; ?></span>
                                                            <span class="mr-2 text-rigth"><?php echo ($arow['answerpercent'] ? $arow['answerpercent'] : '0') . '%'; ?></span>
                                                        </div>
                                                        <div class="progress mb-3" style="height: 0.375rem;">
                                                            <div class="progress-bar" style="width: <?php echo ($arow['answerpercent'] ? $arow['answerpercent'] : '0') . '%'; ?>;background-color: <?php echo $colorData[rand(0, 5)]; ?>;">
                                                                <span class="sr-only"><?php echo ($arow['answerpercent'] ? $arow['answerpercent'] : '0') . '%'; ?> Complete</span>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php }
                                    } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                </div>
                <?php 
            } 
            if (isset($resultList['votedcount'])) { ?>
                <div class="w-100" style="position:absolute;bottom:10px;left:0;background: #00968830;">
                    <div style="color: black; padding: 4px 20px;">
                        <div class="poll-text"><span class="float-left">Нийт санал: <?php echo $resultList['votedcount']; ?> </span>
                            <span class="float-right"><?php echo isset($resultList['enddate']) ? ($resultList['enddate'] < Date::currentDate('Y-m-d')  ? 'Санал асуулгын хугацаа дууссан байна.' : 'Дуусах хугацаа: ' . $resultList['enddate']) : '' ?></span>
                        </div>
                    </div>
                </div>
            <?php }
        }
        
        echo Form::close(); 
        
    }
    ?>
</div>
<script type="text/javascript">
    
    $(function () {
        Core.initUniform($('.polldata_<?php echo $this->uniqId ?>'));
    });

    function savePollFormData<?php echo $this->uniqId ?>(formId, element) {
        var $mainForm = $(formId);
        var $this = $(element);
        
        $mainForm.ajaxSubmit({
            type: 'post',
            url: 'government/saveSinglePoll',
            dataType: 'json',
            data: {'uniqId': '<?php echo $this->uniqId ?>'},
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
                    text: responseData.text,
                    type: responseData.status, 
                    sticker: false
                });

                if (responseData.status === 'success') {
                    $this.closest('.poll<?php echo $this->uniqId ?>').empty().append(responseData.html);
                } 
                
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    }
    
    function slidepoll<?php echo $this->uniqId ?>(element, type) {
        
        var $this = $(element);
        var $mainSelector = $this.closest('.polldiv');
        var $mainSelectorPrev = $this.closest('.polldiv').prev();
        var $mainSelectorNext = $this.closest('.polldiv').next();
        var $pollcounterSelector = $('.poll<?php echo $this->uniqId ?>').find('.poll-counter');
        var $pollhtml = $pollcounterSelector.html();
        var $pollsplit = $pollhtml.split('/');
        
        switch (type) {
            case '2':
                if ($mainSelectorPrev.length > 0) {
                    $mainSelector.toggle();
                    $mainSelectorPrev.slideToggle();
                    var $a1 = parseInt($pollsplit[0])-1;
                    var $a2 = $pollsplit[1];
                    $pollcounterSelector.empty().append($a1 + '/' + $a2);
                }
                break;
            case '3':
                
                if ($mainSelectorNext.length > 0) {
                    $mainSelector.toggle();
                    $mainSelectorNext.slideToggle();
                    
                    var $a1 = parseInt($pollsplit[0])+1;
                    var $a2 = $pollsplit[1];
                    $pollcounterSelector.empty().append($a1 + '/' + $a2);
                }
                
                break;
        }
        
        if (typeof $mainSelectorNext.next().attr('data-key') === 'undefined') {
            console.log($mainSelectorNext);
            
            $mainSelectorNext.find('.slidepoll').addClass('d-none');
            $mainSelectorNext.find('.savePollFormData').removeClass('d-none');
        }
        
    }
    
//    $('#carouselSlidePoll_<?php echo $this->uniqId ?>').bind('slide',function(){
////            alert("Slide Event");
//        console.log('slider');
//    });
    
//    $('#carouselSlidePoll_<?php echo $this->uniqId ?>').bind('slide.bs.carousel', function (e) {
//        var $pollcounterSelector = $('.poll<?php echo $this->uniqId ?>').find('#poll-count');
//        var $pollhtml = $pollcounterSelector.html();
//        var $pollsplit = $pollhtml.split('/');
// 
//        var $a1 = parseInt($pollsplit[0])+1;
//        var $a2 = $pollsplit[1];
//
//        if($a1 > $a2) {
//            $a1 = 1;
//        }
//        $pollcounterSelector.empty().append($a1 + '/' + $a2);
// 
//    });
    
    function slidepolltop<?php echo $this->uniqId ?>(type) {
        

        var $pollcounterSelector = $('.poll<?php echo $this->uniqId ?>').find('#poll-count');
        var $pollhtml = $pollcounterSelector.html();
        var $pollsplit = $pollhtml.split('/');
        console.log('slide changed2');

        $('#carouselSlidePoll_<?php echo $this->uniqId ?>').bind('slid.bs.carousel', function (e) {
            switch (type) { 
            case '2':
                var $a1 = parseInt($pollsplit[0])-1;
                var $a2 = $pollsplit[1];
                if($a1 <= 0) {
                    $a1 = $a2;
                }
                $pollcounterSelector.empty().append($a1 + '/' + $a2);
                break;

            case '3': 
                var $a1 = parseInt($pollsplit[0])+1;
                var $a2 = $pollsplit[1];

                if($a1 > $a2) {
                    $a1 = 1;
                }
                $pollcounterSelector.empty().append($a1 + '/' + $a2);
                break;
        }
        });
        
    }
    
//    $('#carouselSlidePoll_<?php echo $this->uniqId ?>').bind('slide.bs.carousel', function (e) { 
//        console.log('auto carousel');
//        
//    });
</script>