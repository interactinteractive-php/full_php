<script type="text/javascript">
    
    var $government_<?php echo $this->uniqId ?> = $('.government_<?php echo $this->uniqId ?>');
    
    if ($government_<?php echo $this->uniqId ?>.length > 0) {
        
        var height_<?php echo $this->uniqId ?> = $(window).height();
        var issueStartToStop_<?php echo $this->uniqId ?> = [];
        var stopMainTimer_<?php echo $this->uniqId; ?> = 'start';

        $('.gov_issui_<?php echo $this->uniqId ?>').attr('style', 'height: '+(height_<?php echo $this->uniqId ?>-169) + 'px !important; overflow-x: hidden; overflow-y: auto;');
        $('.government_<?php echo $this->uniqId ?> .member-list-conference').attr('style', 'height: '+(height_<?php echo $this->uniqId ?>-18) + 'px !important; overflow-x: hidden; overflow-y: auto;');
        $('#protocol-list-<?php echo $this->uniqId ?>').attr('style', 'height: '+(height_<?php echo $this->uniqId ?>-257) + 'px !important; overflow-x: hidden;  margin-bottom: 0; overflow-y: auto;');

        $(function() {
            console.clear();
            switch ('<?php echo $this->conferenceData['action'] ?>') {
                case '1':
                    /* start */
                    var $mainTime = $('.durationTimer_<?php echo $this->uniqId; ?> h5');
                    var splitTimer = $mainTime.html().split(':');
                    var mainTimer = 0;

                    mainTimer += parseInt(parseInt(splitTimer[0])*60*60);
                    mainTimer += parseInt(parseInt(splitTimer[1])*60);
                    mainTimer += parseInt(parseInt(splitTimer[2]));

                    processingTimer_<?php echo $this->uniqId ?>(mainTimer, $mainTime, 'start');
                    break;
                case '2': 
                    /* pause */
                    var $mainTime = $('.breakTimer_<?php echo $this->uniqId; ?> h5');
                    var splitTimer = $mainTime.html().split(':');
                    var mainTimer = 0;

                    mainTimer += parseInt(parseInt(splitTimer[0])*60*60);
                    mainTimer += parseInt(parseInt(splitTimer[1])*60);
                    mainTimer += parseInt(parseInt(splitTimer[2]));

                    processingTimer_<?php echo $this->uniqId ?>(mainTimer, $mainTime, 'pause');

                    break;
                case '3': 
                    /* stop */
                    break;
            } 

            var $firstIssueRow = $('.government_<?php echo $this->uniqId ?> .conferencing-issue-list > li.issue-start:eq(0)');
            $('.government_<?php echo $this->uniqId ?> .conferencing-issue-list > li.c-issue-list:eq(0)').trigger('click');

            if ($firstIssueRow.length) {
                var $issueDatarow = JSON.parse($firstIssueRow.attr('data-row'));
                setConferencingIssueHeader($issueDatarow.ordernum, $issueDatarow.id, $issueDatarow.subjectname, $issueDatarow.starttime, $issueDatarow.endtime , $issueDatarow.positionname, $issueDatarow.departmentname, $issueDatarow.saidphoto, undefined);
            }

            var $panelView = $('.government_<?php echo $this->uniqId ?> .conferencing-issue-list');

            $panelView.sortable({
                start: function(e, ui) {},
                stop: function(e, ui) {  
                    var params = [];
                    $panelView.find(".isitem").each(function(r, v) {
                        var counter = r + 1;
                        var $this = $(v);
                        $this.attr("data-ordernum", counter);
                        $this.find('.number').empty().append(counter+'.');
                        var $dataRow = JSON.parse($this.attr('data-row'));
                        $dataRow['ordernum'] = counter;
                        params.push($dataRow);
                    });
                    saveConferenceOrder_<?php echo $this->uniqId ?>(params);
                }
            }); 

            var $memberView = $('.government_<?php echo $this->uniqId ?> #other-member-list-<?php echo $this->uniqId ?>');
            $.contextMenu({
                selector: ".government_<?php echo $this->uniqId ?> #other-member-list-<?php echo $this->uniqId ?> > li",
                items: {
                    "conf_edit": {
                        name: "Засах", 
                        icon: "edit", 
                        callback: function(key, options) {
                            var $this = $(this), 
                                $dataRow = JSON.parse($this.attr('data-row'));
                                
                            if (typeof $dataRow !== 'undefined') {
                                editProcessList_<?php echo $this->uniqId ?> ($dataRow, $this);
                            }

                        }
                    },
                    "conf_delete": {
                        name: "Устгах", 
                        icon: "trash", 
                        callback: function(key, options) {
                            var $this = $(this), 
                                $dataRow = JSON.parse($this.attr('data-row'));
                                
                            if (typeof $dataRow !== 'undefined') {
                                deleteOthermember_<?php echo $this->uniqId ?> ($dataRow, $this);
                            }

                        }
                    },
                }
            });  

            $memberView.sortable({
                start: function(e, ui) {},
                stop: function(e, ui) {  
                    var params = [];
                    var counter = 1;
                    $memberView.find(".media").each(function(index, row) {
                        var $this = $(row);
                        $this.find('.number').empty().append(counter+'.');
                        var $dataRow = JSON.parse($this.attr('data-row'));
                        $dataRow['ordernum'] = counter;
                        counter++;
                        params.push($dataRow);
                    });

                    saveConferenceMember_<?php echo $this->uniqId ?>(params);
                }
            }); 

            /*-------startMention-------*/

            var employeeList = <?php echo json_encode($this->employeeList); ?>;

            if (!$("link[href='<?php echo autoVersion('assets/core/js/plugins/mention/bootstrap-suggest/bootstrap-suggest.css'); ?>']").length) {
                $("head").append('<link rel="stylesheet" type="text/css" href="<?php echo autoVersion('assets/core/js/plugins/mention/bootstrap-suggest/bootstrap-suggest.css'); ?>"/>');
            }      

            $.getScript('assets/core/js/plugins/mention/bootstrap-suggest/bootstrap-suggest.js', function(){
                $('textarea[name="enter-protocol"]').suggest('@', {
                    data: employeeList,
                    filter: {
                        casesensitive: false,
                        filterrule: 'startsWith',
                        firstdottedafter: true, 
                        beginfilterchar: 0, 
                        limit: 5
                    },  
                    map: function(user) {
                        return {
                            value: user.name,
                            text: '<strong>'+user.name+'</strong>'
                        };
                    }
                });
            });

            $('textarea[name="enter-protocol"]').on('keypress', function(e) {
                var code = (e.keyCode ? e.keyCode : e.which);

                if (code == 13) {

                    var $this = $(this);
                    var $list = $('#conferencing-protocol-list-<?php echo $this->uniqId ?>');

                    $.ajax({
                        type: 'post',
                        url: 'conference/saveConferencingProtocol', 
                        data: {subjectId: $('#conferencing-issue-number').attr('data-issue-id'), note: $this.val()}, 
                        dataType: 'json',
                        async: false, 
                        success: function (data) {

                            if (data.status === 'success') {
                                $list.prepend(setConferencingIssueListTemplate(data));
                                $this.css('height', '50px').val('@');
                            }

                            senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});
                        }
                    });

                    e.preventDefault();
                    return false;
                }
            });

            /*-------endMention-------*/
            
            <?php  if (issetParamZero($this->memberRole['readonly']) === '0')  { ?>
                $.contextMenu({
                    selector: ".tiktok-<?php echo $this->uniqId; ?>, .tiktok-<?php echo $this->uniqId; ?> div, .tiktok-<?php echo $this->uniqId; ?> p, .tiktok-<?php echo $this->uniqId; ?> ul, .tiktok-<?php echo $this->uniqId; ?> li",
                    items: {
                        "conf_start": {
                            name: "Эхлэх", 
                            icon: "play", 
                            callback: function(key, options) {
                                var $this = options.$trigger.closest('.tiktok-<?php echo $this->uniqId; ?>'), conferencingIssueId = $this.data('id');          
                                if (!$this.hasClass('issue-stop') && !$this.hasClass('issue-start')) {
                                    if ($this.closest('div.gov_issui_<?php echo $this->uniqId ?>').find('li.issue-start').length > 0) {
                                        var $selecterStop = $this.closest('div.gov_issui_<?php echo $this->uniqId ?>').find('li.issue-start');

                                        $.each($selecterStop, function ($index, row) {
                                            var $row = $(row);
                                            if ($row.attr('data-ordernum') !== $this.attr('data-ordernum')) {
                                                dieIssue<?php echo $this->uniqId ?>($row, $row.attr('data-id'));
                                                $row.removeClass('issue-start');
                                                $row.find('.setProtocol').addClass('d-none');
                                                $row.addClass('issue-stop').append('<button type="button" class="btn font-weight-bold finishadd" onclick="finishByDescription_<?php echo $this->uniqId; ?>(this, ' + conferencingIssueId +')"><i class="fa fa-gavel"></i></button>');
                                            }
                                        });
                                    }

                                    $this.addClass('issue-start').siblings().removeClass('issue-start');

                                    $this.removeClass('active');
                                    $this.removeClass('cancel');
                                    $this.removeClass('issue-stop');

                                    $this.find('.fissue').empty().append('<button type="button" class="btn font-weight-bold finishadd setProtocol" style="right: 40px;" onclick="setProtocol_<?php echo $this->uniqId; ?>(this)"><i class="icon-quill4"></i></button>');
                                    startIssue<?php echo $this->uniqId; ?>($this, conferencingIssueId);
                                }
                            }
                        },
                        "conf_finish": {
                            name: "Дуусгах", 
                            icon: "stop", 
                            callback: function(key, options) {
                                var $this = options.$trigger.closest('.tiktok-<?php echo $this->uniqId; ?>'), conferencingIssueId = $this.data('id');
                                if (!$this.hasClass('issue-stop')) {
                                    $this.addClass('issue-stop');
                                    $this.removeClass('active');
                                    $this.removeClass('cancel');
                                    $this.removeClass('issue-start');
                                    $this.find('.fissue').empty().append('<button type="button" class="btn font-weight-bold finishadd" onclick="finishByDescription_<?php echo $this->uniqId; ?>(this, ' + conferencingIssueId +')"><i class="fa fa-gavel"></i></button>');

                                    dieIssue<?php echo $this->uniqId; ?>($this, conferencingIssueId);
                                }
                            }
                        },
                        "conf_cancel": {
                            name: "Цуцлах", 
                            icon: "times", 
                            callback: function(key, options) {
                                var $this = options.$trigger.closest('.tiktok-<?php echo $this->uniqId; ?>');
                                var $dataRow = JSON.parse($this.attr('data-row'));

                                var $html = ''; 
                                switch ($dataRow.typeid) {
                                    case '1561974650875':
                                        /*
                                         * Heleltseh
                                         */
                                        $html = 'Хэлэлцэх асуудал цуцлахдаа итгэлтэй байна уу?';
                                        break;

                                    case '1561974650898': 
                                        /*
                                         * Taniltsah
                                         */
                                        $html = 'Танилцах асуудал цуцлахдаа итгэлтэй байна уу?';
                                        break;
                                }
                                dialogConfirm_<?php echo $this->uniqId ?> ('cancelIssue_<?php echo $this->uniqId; ?>', $html, $dataRow, undefined, $this);

                            }
                        },
                        "conf_edit": {
                            name: "Засах", 
                            icon: "edit", 
                            callback: function(key, options) {
                                var $this = options.$trigger.closest('.tiktok-<?php echo $this->uniqId; ?>'), 
                                    $dataRow = JSON.parse($this.attr('data-row'));

                                if (typeof $dataRow !== 'undefined') {
                                    $this.trigger('click');
                                    editProcess_<?php echo $this->uniqId ?> ($dataRow, $this);
                                }

                            }
                        },
                        "conf_remove": {
                            name: "Устгах", 
                            icon: "trash", 
                            callback: function(key, options) {
                                var $this = options.$trigger.closest('.tiktok-<?php echo $this->uniqId; ?>'), 
                                    $dataRow = JSON.parse($this.attr('data-row'));

                                if (typeof $dataRow !== 'undefined') {
                                    var $html = ''; 
                                    switch ($dataRow.typeid) {
                                        case '1561974650875':
                                            /*
                                             * Heleltseh
                                             */
                                            $html = 'Хэлэлцэх асуудал устгахдаа үйлдэл хийхдээ итгэлтэй байна уу?';
                                            break;

                                        case '1561974650898': 
                                            /*
                                             * Taniltsah
                                             */
                                            $html = 'Танилцах асуудал устгахдаа үйлдэл хийхдээ итгэлтэй байна уу?';
                                            break;
                                    }

                                    dialogConfirm_<?php echo $this->uniqId ?> ('deleteIssue_<?php echo $this->uniqId; ?>', $html, $dataRow, undefined, $this);
                                }

                            }
                        },
                        "conf_transfer": {
                            name: "Шилжүүлэх", 
                            icon: "arrow-right", 
                            callback: function(key, options) {
                                var $this = options.$trigger.closest('.tiktok-<?php echo $this->uniqId; ?>'), 
                                    $dataRow = JSON.parse($this.attr('data-row'));

                                if (typeof $dataRow !== 'undefined') {
                                    var $html = ''; 
                                    switch ($dataRow.typeid) {
                                        case '1561974650875':
                                            /*
                                             * Heleltseh
                                             */
                                            $html = 'Хэлэлцэх асуудал, Танилцах асуудал 2ын хооронд шилжүүлэх үйлдэл хийхдээ итгэлтэй байна уу?';
                                            break;

                                        case '1561974650898': 
                                            /*
                                             * Taniltsah
                                             */
                                            $html = 'Танилцах, Хэлэлцэх асуудал асуудал 2ын хооронд шилжүүлэх үйлдэл хийхдээ итгэлтэй байна уу?';
                                            break;
                                    }

                                    dialogConfirm_<?php echo $this->uniqId ?> ('changeIssue<?php echo $this->uniqId; ?>', $html, $dataRow, undefined, $this);
                                }

                            }
                        },
                    }
                });
            <?php } ?>

            <?php  if (issetParamZero($this->memberRole['readonly']) === '1')  { ?>
                $.contextMenu({
                    selector: ".tiktok-<?php echo $this->uniqId; ?>, .tiktok-<?php echo $this->uniqId; ?> div, .tiktok-<?php echo $this->uniqId; ?> p, .tiktok-<?php echo $this->uniqId; ?> ul, .tiktok-<?php echo $this->uniqId; ?> li",
                    items: {
                        "conf_edit": {
                            name: "Засах", 
                            icon: "edit", 
                            callback: function(key, options) {
                                var $this = options.$trigger.closest('.tiktok-<?php echo $this->uniqId; ?>'), 
                                    $dataRow = JSON.parse($this.attr('data-row'));
                                if (typeof $dataRow !== 'undefined') {
                                    $this.trigger('click');
                                    editProcess_<?php echo $this->uniqId ?> ($dataRow, $this);
                                }
                            }
                        },
                        "conf_remove": {
                            name: "Устгах", 
                            icon: "trash", 
                            callback: function(key, options) {
                                var $this = options.$trigger.closest('.tiktok-<?php echo $this->uniqId; ?>'), 
                                    $dataRow = JSON.parse($this.attr('data-row'));

                                if (typeof $dataRow !== 'undefined') {
                                    var $html = ''; 
                                    switch ($dataRow.typeid) {
                                        case '1561974650875':
                                            /*
                                             * Heleltseh
                                             */
                                            $html = 'Хэлэлцэх асуудал устгахдаа үйлдэл хийхдээ итгэлтэй байна уу?';
                                            break;

                                        case '1561974650898': 
                                            /*
                                             * Taniltsah
                                             */
                                            $html = 'Танилцах асуудал устгахдаа үйлдэл хийхдээ итгэлтэй байна уу?';
                                            break;
                                    }

                                    dialogConfirm_<?php echo $this->uniqId ?> ('deleteIssue_<?php echo $this->uniqId; ?>', $html, $dataRow, undefined, $this);
                                }

                            }
                        },
                    }
                });  
            <?php } ?>

        });
        
        $(document).on('contextmenu', function(e) {
            e.preventDefault();
        });

        $("body").on("shown.bs.modal", ".modal", function() {
            $(this).css({
                'top': '50%',
                'margin-top': function () {
                    return -($(this).height() / 2);
                }
            });
        });

        $("body").on('click', '.government_<?php echo $this->uniqId ?> li.c-issue-list', function() {

            var $this = $(this);
            var $adduser = '';
            var conferencingIssueId = $this.attr('data-id');                

            $this.addClass('active').siblings().removeClass('active');

           $('.protocol-title span').html(' (Асуудал ' + $this.attr('data-ordernum') + ') ');
            <?php if (issetParamZero($this->memberRole['readonly']) != '1') { ?>

                var $list = $('#conferencing-protocol-list-<?php echo $this->uniqId ?>');
                $.ajax({
                    type: 'post',
                    url: 'conference/getConferencingProtocolHistory', 
                    data: {subjectId: conferencingIssueId}, 
                    dataType: 'json',
                    async: false, 
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true,
                            target: '#protocol-list-<?php echo $this->uniqId ?>'
                        });
                    },
                    success: function (data) {

                        $list.empty().attr('data-subject-id', conferencingIssueId);

                        if (data.length) {
                            for (var key in data) {
                                $list.prepend(setConferencingIssueListTemplate(data[key]));
                            }
                        }

                        Core.unblockUI('#protocol-list-<?php echo $this->uniqId ?>');
                    },
                    error: function() {
                        Core.unblockUI('#protocol-list-<?php echo $this->uniqId ?>');
                    }
                }); 

            <?php } /*else { */?>

                var $listMember = $('#other-member-list-<?php echo $this->uniqId ?>');
                $.ajax({
                    type: 'post',
                    url: 'conference/getConferenceMember', 
                    data: {subjectId: conferencingIssueId}, 
                    dataType: 'json',
                    async: false, 
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true,
                            target: '#protocol-list-<?php echo $this->uniqId ?>'
                        });
                    },
                    success: function (data) {
                        $listMember.empty().attr('data-subject-id', conferencingIssueId);;
                        var $counter = 1;
                        if (data.length) {
                            for (var i = 0; i < data.length; i++) {
                                var $isparticipated = data[i].isparticipated;

                                $('#subject_'+ conferencingIssueId).find('.participants').empty().append('Ажлын хэсэг: '+data.length).promise().done(function () {
                                    if (data.length == 1 && typeof data[0] !== 'undefined' && typeof data[0].firstname !== 'undefined' && data[0].firstname === 'Оролцох хүн бүртгээгүй байна') {
                                        $('#subject_'+ conferencingIssueId).find('.participants').empty().append('Ажлын хэсэг: 0');
                                    }

                                    $adduser = ($isparticipated === '1') ? 'Ирсэн' : '<i class="icon-checkmark4" style="color:#CD5C5C;"></i>';

                                    $('#govaddmember<?php echo $this->uniqId ?>').empty().append('<a href="javasript:;" onclick="memberOtherAdd(this, '+data[i].subjectid+')" class="addothermem float-right">Нэмэх</a>');

                                    var organizationText = (data[i].positionname) ? data[i].positionname : '';
                                        organizationText += (data[i].organizationname) ? ' ' + data[i].organizationname : '';

                                    var $html = '<li class="media" data-row="'+ data[i].jsonrow +'">'+
                                                '<div class="mr-3 number">'+ $counter +'.</div>'+
                                                '<div class="media-body">'+
                                                    '<div class="media-title font-weight-semibold">'+data[i].firstname+'</div>'+
                                                    '<span class="text-muted">'+organizationText+'</span>'+
                                                '</div>'+
                                                '<div class="align-self-center ml-3">'+
                                                    '<div class="list-icons list-icons-extended">'+
                                                    '<p> <a title="Ирц бүртгэх" href="javascript:;" onclick="transferProcessActionCustom(this,'+data[i].id+', '+data[i].subjectid+')">'+ $adduser +'</a></p>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</li>';

                                    $listMember.append($html);

                                });
                                $counter++;
                            }
                        } else {
                            $('#govaddmember<?php echo $this->uniqId ?>').empty().append('<a href="javasript:;" onclick="memberOtherAdd(this, '+conferencingIssueId+')" class="addothermem float-right">Нэмэх</a>');
                            var $html = '<li class="media" data-row="0" data-empty="1">'+
                                                '<div class="mr-3 number">1.</div>'+
                                                '<div class="media-body">'+
                                                    '<div class="media-title font-weight-semibold">Оролцох хүн бүртгээгүй байна</div>'+
                                                    '<span class="text-muted"></span>'+
                                                '</div>'+
                                                '<div class="align-self-center ml-3">'+
                                                    '<div class="list-icons list-icons-extended">'+
                                                    '</div>'+
                                                '</div>'+
                                            '</li>';
                            $listMember.append($html);
                        }

                        Core.unblockUI('#protocol-list-<?php echo $this->uniqId ?>');
                    },
                    error: function() {
                        Core.unblockUI('#protocol-list-<?php echo $this->uniqId ?>');
                    }
                });    
            <?php /*} */?>

        });

        function dieIssue<?php echo $this->uniqId; ?>($this, conferencingIssueId) {
            $.ajax({
                type: 'post',
                url: 'conference/getConferenceEndTime', 
                data: {
                    id: conferencingIssueId,
                    dataRow: JSON.parse($this.attr('data-row'))
                },
                dataType: 'json',
                async: false, 
                beforeSend: function () {
                    Core.blockUI({
                        animate: true,
                        target: '#' + $this.attr('id')
                    });
                },
                success: function (data) {

                    if (typeof data.status !== 'undefined' && data.status === 'warning') {
                        Core.unblockUI('#' + $this.attr('id'));
                        
                        PNotify.removeAll();
                        new PNotify({
                            title: data.status,
                            text: data.text,
                            type: data.status,
                            sticker: false
                        });
                        
                        $this.removeClass('active');
                        $this.removeClass('cancel');
                        $this.removeClass('issue-start');
                        $this.removeClass('issue-stop');
                        $this.find('.fissue').empty();
                        return;
                    }
                    
                    if (typeof data.status !== 'undefined') {
                        $this.removeClass('issue-stop').find('.fissue').empty();;
                        PNotify.removeAll();
                        new PNotify({
                            title: data.status,
                            text: data.text,
                            type: data.status,
                            sticker: false
                        });
                        Core.unblockUI('#' + $this.attr('id'));
                        return;
                    }

                    var $html = '<span class="timestart timer-start"> </span>'
                            + data.starttime + ' - ' + data.endtime + '<span  class="icon-p"  data-toggle="tooltip" data-placement="bottom" title="Товлосон цагт засвар хийх"  onclick="changeConferenceTimer_<?php echo $this->uniqId ?>(this)"> <i class="icon-alarm"></i></span>';
                    $this.find('.conf-issuelist-start').empty().append($html).promise().done(function () {
                        
                        senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});
                        $this.find('.conf-issuelist-start').fadeIn(3000);
                        Core.unblockUI('#' + $this.attr('id'));
                    });
                }
            });    
        }

        function startIssue<?php echo $this->uniqId; ?>($this, conferencingIssueId) {
            $.ajax({
                type: 'post',
                url: 'conference/getConferenceStartTime', 
                data: {
                    id: conferencingIssueId,
                    dataRow: JSON.parse($this.attr('data-row'))
                },
                dataType: 'json',
                async: false, 
                beforeSend: function () {
                    Core.blockUI({
                        animate: true,
                        target: '#' + $this.attr('id')
                    });
                },
                success: function (data) {
                    
                    Core.unblockUI('#' + $this.attr('id'));
                    senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});
                    if (typeof data.status !== 'undefined' && data.status === 'warning') {
                        
                        PNotify.removeAll();
                        new PNotify({
                            title: data.status,
                            text: data.text,
                            type: data.status,
                            sticker: false
                        });
                        
                        $this.removeClass('active');
                        $this.removeClass('cancel');
                        $this.removeClass('issue-start');
                        return;
                    }
                    
                }
            });    
        }

        function reviewgov_<?php echo $this->uniqId; ?>($element, $id) {

            $.ajax({
                url: 'conference/issueGov', 
                data: {id:$id},
                type: 'post',
                dataType: "json",
                success: function (data) {},
                error: function () { }
            }).done(function(){ });
        }

        function member_list_<?php echo $this->uniqId; ?>($element, $id, $bookid, $keyid, $isarrived) { 

            var $this = $($element);
            var status = $this.attr("data-num");
            var param = { id: $id, bookid: $bookid, keyid: $keyid };
            var blockUIId = '#' + $this.closest('.media_' + $id).attr('id');
            var startTime = $('input[name="startTime"]').val();
            var $statusid = '1506391623676392';
            var $parent = $this.closest('li');
            if(startTime == ''){ $statusid = '1506391592724323';}
            
            $parent.attr('data-status', '0');
            switch (status) {
                case '0':
                    $.ajax({
                        url: 'conference/memberpercent', 
                        data: {
                            id:$id,
                            statusid:$statusid,
                            bookid:$bookid,
                            keyid:$keyid
                        },
                        type: 'post',
                        dataType: "json",
                        beforeSend: function () {
                            Core.blockUI({
                                animate: true,
                                target: blockUIId
                            });
                        },
                        success: function (response) {
                            $this.find('i').addClass('icon-pause').removeClass('icon-play4');

                            if (response.status === 'success') {
                                senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});

                                $parent.attr('data-status', '1');
                                $this.attr("data-num", '1');
                                $('.government_<?php echo $this->uniqId ?>').find('.percentOfAttendance').empty().append(response.percent);
                                $('.government_<?php echo $this->uniqId ?>').find('input[name="percent"]').val(response.percent);
                                $this.empty().html(response.time1);
                                
                                if(startTime === ''){
                                    $('#btnstatus_' + $id).empty().append('<i class="icon-circle2"></i>');
                                }else{
                                    $('#btnstatus_' + $id).empty().append('<i class="icon-alarm"></i>');
                                }
                            }

                            Core.unblockUI(blockUIId);
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
                            Core.unblockUI(blockUIId);
                        }
                    }).done(function() {
                    });
                    break;
                case '1':
                     if (confirm("Ирцийг цуцлахдаа итгэлтэй байна уу") == true) {
                        $.ajax({
                            url: 'conference/memberpercent1', 
                            data: {
                                id:$id,
                                bookid:$bookid,
                                keyid:$keyid
                            },
                            type: 'post',
                            dataType: "json",
                            beforeSend: function () {
                                Core.blockUI({
                                    animate: true,
                                    target: blockUIId
                                });
                            },
                            success: function (response) {
                                $this.find('i').addClass('icon-play4').removeClass('icon-pause');

                                if (response.status === 'success') {
                                    senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});

                                    $this.attr("data-num", '0');
                                    $('.government_<?php echo $this->uniqId ?>').find('.percentOfAttendance').empty().append(response.percent);
                                    $('.government_<?php echo $this->uniqId ?>').find('input[name="percent"]').val(response.percent);
                                    $('#btnstatus_' + $id).empty().append('<i class="icon-subtract"></i>');
                                    $this.empty().html('Ирсэн');
                                }

                                Core.unblockUI(blockUIId);
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
                                Core.unblockUI(blockUIId);
                            }
                        }).done(function() {
                        });   
                    } 
                    break;
                case '10':
                    $.ajax({
                        url: 'conference/memberpercent22', 
                        data: {
                            id:$id,
                            bookid:$bookid,
                            keyid:$keyid,
                            statusid:$statusid
                        },
                        type: 'post',
                        dataType: "json",
                        beforeSend: function () {
                            Core.blockUI({
                                animate: true,
                                target: blockUIId
                            });
                        },
                        success: function (response) {
                            $this.find('i').addClass('icon-pause').removeClass('icon-play4');

                            if (response.status === 'success') {
                                senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});

                                $parent.attr('data-status', '1');
                                $this.attr("data-num", '11');
                                $this.empty().append(response.time1);
                                
                                if(startTime === ''){
                                    $('#btnstatus_' + $id).empty().append('<i class="icon-circle2"></i>');
                                }else{
                                    $('#btnstatus_' + $id).empty().append('<i class="icon-alarm"></i>');
                                }
                            }

                            Core.unblockUI(blockUIId);
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
                            Core.unblockUI(blockUIId);
                        }
                    }).done(function() {
                    });
                    break;
                case '11':
                    if (confirm("Ирцийг цуцлахдаа итгэлтэй байна уу") == true) {
                        $.ajax({
                            url: 'conference/memberpercent11', 
                            data: {
                                id:$id,
                                bookid:$bookid,
                                keyid:$keyid
                            },
                            type: 'post',
                            dataType: "json",
                            beforeSend: function () {
                                Core.blockUI({
                                    animate: true,
                                    target: blockUIId
                                });
                            },
                            success: function (response) {
                                $this.find('i').addClass('icon-play4').removeClass('icon-pause');

                                if (response.status === 'success') {
                                    senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});

                                    $this.attr("data-num", '10');
                                    $('#btnstatus_' + $id).empty().append('<i class="icon-subtract"></i>');
                                    $this.empty().html('Ирсэн');
                                }

                                Core.unblockUI(blockUIId);
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
                                Core.unblockUI(blockUIId);
                            }
                        }).done(function() {
                        });
                    }
                    break;
                default:
                break;
            }

        }

        function member_status_<?php echo $this->uniqId; ?>($element, $id, $bookid, $keyid, $isarrived) {

            var $this = $($element);
            var blockUIId = '#' + $this.closest('.media_' + $id).attr('id');
            
            $.ajax({
                url: 'conference/getMemberConfig', 
                data: {
                    id: $id, 
                    bookid:$bookid                                         
                },
                type: 'post',
                dataType: "json",
                beforeSend: function () {
                    Core.blockUI({
                        animate: true,
                        target: blockUIId
                    });
                },
                success: function (data) {
                    var $config = (typeof data.config !== 'undefined' && typeof data.config['processcode'] !== 'undefined') ? data.config : [];
                    if (data.status === 'success' && $config) {
                        var $processCodeArr = ($config.processcode).split(',');
                        var $wfmstatusidArr = ($config.wfmstatusid).split(',');
                        var $changediconArr = ($config.changedicon).split(',');
                        var $wfmstatusnameArr = ($config.wfmstatusname).split(',');
                        var $timeSeq = ($config.timeseq).split(',');
                        
                        var $addonHtml1 = '<ul class="media-list status-top">' +
                                                '<li class="media d-flex align-items-center mt10">' +
                                                    '<div class="mr-2 position-relative">' +
                                                        '<img src="'+ (($config.picture) ? $config.picture : '') +'" onerror="onUserImgError(this);" class="rounded-circle" width="36" height="36" alt="">' +
                                                    '</div>' +
                                                    '<div class="media-body">' +
                                                        '<div class="d-flex flex-column justify-content-between">' +
                                                            '<a href="javascript:void(0);" class="membername text-uppercase font-weight-bold text-black line-height-normal font-size-12">'+ (($config.employeename) ? $config.employeename : '') +'</a>' +
                                                        '<span class="text-uppercase text-gray font-size-11">'+ (($config.positionname) ? $config.positionname : '') +'</span>' +
                                                        '</div>' +
                                                    '</div>' +
                                                    '<div class="ml-2 position-relative">' +
                                                        '<button type="button" class="btn btn-outline bg-primary border-primary text-primary-800 btn-icon border-1 ml-2"><i class="'+ (($config.icon) ? $config.icon : '') +' font-size-18"></i> '+ (($config.statusname) ? $config.statusname : '') +'</button>' +
                                                    '</div>' +
                                                '</li>' +
                                            '</ul>';
                        var i = 1;
                        var $addonhtml_2 = '';
                        
                        for (i = 1; i <= 26; i++) {
                            if (typeof $config['time' + i] !== 'undefined' && $config['time' + i] && typeof $config['time' + i + 'title'] !== 'undefined') {
                                $addonhtml_2 += '<div class="d-flex align-items-center mb-2">' +
                                                    '<div class="col-3 text-right">' +
                                                        '<span class="text-gray">'+ $config['time' + i + 'title'] +'</span>' +
                                                    '</div>' +
                                                    '<div class="col-9 pr-0">' +
                                                        '<span>' +
                                                            '<input type="hidden" temp-datapath="time_'+i+'" value="'+ $config['time' + i] +'">' +
                                                            '<input type="text" data-path="timedata" data-realpath="time'+ i +'" name="time['+ i +']" datapath="time_'+i+'" class="form-control timeInit" placeholder="" value="'+ $config['time' + i] +'">' +
                                                        '</span>' +
                                                    '</div>' +
                                                '</div>' ;
                            }
                        }
                        
                        var $checklistHtml = '<div class="col-md-8">';
                        $.each($wfmstatusidArr, function ($index, $statusId) {
                        
                            var $checkedstatus = ($statusId === $config['statusid']) ? 'checked="checked"' : '';
                            $checklistHtml +=  '<div class="form-check">' +
                                                    '<label class="form-check-label">' +
                                                        '<input type="radio" class="form-check-input" name="member_status" data-processcode="'+ $processCodeArr[$index] +'" data-statusicn="'+ $changediconArr[$index] +'" value="'+ $statusId +'">' +
                                                        '<span class="ml15">'+ $wfmstatusnameArr[$index] +'</span>' +
                                                    '</label>' +
                                                '</div>';
                        });
                        
                        $checklistHtml += '</div>';
                        
                        bootbox.dialog({
                            title: '<div>Ирцийн төлөв</div>',
                            centerVertical: true,
                            className:'memberstatus'+$id,
                            message: '<div class="row status-dialog-process process-<?php echo $this->uniqId ?>"> ' +
                                        '<div class="col">' +
                                            $addonHtml1 + 
                                            '<div class="log-edit"><form method="post" class="processform-<?php echo $this->uniqId ?>" >' +
                                                $addonhtml_2 + 
                                            '</form></div>' +
                                        '</div>' +
                                        '<div class="col-md-12">' +
                                            '<form action="">' +
                                                '<div class="form-group d-flex justify-content-center process-box">' +
                                                    '<div class="col-3 col-form-label text-right"><h4 class="text-uppercase font-size-12 font-weight-bold">Төлөв сонгох</h4></div>' 
                                                    + $checklistHtml + 
                                                '</div>' +
                                                '<div class="form-group d-flex justify-content-center mb-0">' +
                                                    '<div class="col-3 col-form-div text-right"><h4 class="text-uppercase font-size-12 font-weight-bold">Тайлбар</h4></div>' +
                                                    '<div class="col-9 pr-0">' +
                                                        '<textarea rows="3" cols="3" id="member_description" class="form-control" placeholder="Дэлгэрэнгүй">'+ (($config.wfmdescription) ? $config.wfmdescription : '') +'</textarea>' +
                                                    '</div>' +
                                                '</div>' +
                                            '</form>' +
                                        '</div>' +
                                    '</div>',
                            buttons: {
                                success: {
                                    bgiframe: true,
                                    label: 'Хадгалах', 
                                    className: 'btn-success',
                                    callback: function () {
                                        var $return = false;
                                        var $timeSeqValArr = [];
                                        
                                        $.each($timeSeq, function ($ind, $seq) {
                                            var $tempSeq = $('.process-<?php echo $this->uniqId ?>').find('input[data-realpath="'+ $seq +'"]');
                                            if ($tempSeq.length > 0) {
                                                if ($tempSeq.val()) {
                                                    $timeSeqValArr.push($tempSeq.val());
                                                } else {
                                                    $return = true;
                                                }
                                            }
                                        });
                                        
                                        if ($return) {
                                            PNotify.removeAll();
                                            new PNotify({
                                                title: 'Error',
                                                text: 'Бөглөх талбарыг сайтар шалгана уу',
                                                type: 'error',
                                                sticker: false
                                            });
                                            return false;
                                        } else {
                                            var $in = 0;
                                            
                                            while ($in < $timeSeqValArr.length) {
                                                var $ind = $in + 1;
                                                if (typeof $timeSeqValArr[$ind] !== 'undefined' &&  $timeSeqValArr[$ind] < $timeSeqValArr[$in]) {
                                                    $return = true;
                                                }
                                                $in++;
                                            }
                                            
                                            if ($return) {
                                                PNotify.removeAll();
                                                new PNotify({
                                                    title: 'Error',
                                                    text: 'Буруу утга оруулсан байна',
                                                    type: 'error',
                                                    sticker: false
                                                });
                                                return false;
                                            }
                                        }
                                        
                                        var startTime = $('input[name="startTime"]').val();
                                        var $mainSelectorBoowox = $('.processform-<?php echo $this->uniqId ?>');
                                        
                                        var desc = $('#member_description').val();
                                        var $checkOption = $('input[name="member_status"]:checked');
                                        var $status = $checkOption.val();
                                        var $processcode = $checkOption.attr('data-processcode');
                                        var $icn = $checkOption.attr('data-statusicn');
                                        var $iscome = '0';
                                        if ($mainSelectorBoowox.find('input[temp-datapath="time_1"]').length > 0 && $mainSelectorBoowox.find('input[temp-datapath="time_1"]').val() !== $mainSelectorBoowox.find('input[datapath="time_1"]').val()) {
                                            $status = '1506391592724323';
                                            $iscome = '1';
                                            if ('<?php echo $this->conferenceData['action'] ?>' == '1' && startTime <  $mainSelectorBoowox.find('input[datapath="time_1"]').val()) {
                                                $status = '1506391623676392';
                                            }
                                        }
                                        
                                        $('.memberstatus'+$id).find('input[name="member_status"]:checked').addClass('active');
                                        
                                        $.ajax({
                                            url: 'conference/memberchangestatus', 
                                            data: {
                                                id: $id, 
                                                bookid:$bookid, 
                                                keyid: $keyid,
                                                statusId: $status,
                                                processcode: $processcode,
                                                descrition: desc,
                                                iscome: $iscome,
                                                timeData: $('.processform-<?php echo $this->uniqId ?>').serialize()
                                            },
                                            type: 'post',
                                            dataType: "json",
                                            beforeSend: function () {
                                                Core.blockUI({
                                                    animate: true,
                                                    target: blockUIId
                                                });
                                            },
                                            success: function (data) {
                                                if (data.status === 'success') {
                                                    $this.attr("data-status", $status);
                                                    
                                                    if (typeof data.wfmdescription !== 'undefined' && data.wfmdescription) {
                                                        $this.closest('li').find('label[class="label'+ $id +'"]').empty().append('Тайлбар : '  + data.wfmdescription);
                                                    }
                                                    
                                                    if (typeof data.config !== 'undefined') {
                                                        var $config = data.config;
                                                        
                                                        if ($config.icon) {
                                                            $this.empty().append('<i class="'+ $config.icon +'"></i>');
                                                        } else {
                                                            $this.empty().append('<i class="'+ $icn +'"></i>');
                                                        }
                                                        
                                                        var $selectorBtn = $this.closest('li').find('button#mem' + $id);
                                                        
                                                        if ($config.time1) {
                                                            $selectorBtn.empty().append($config.time1);
                                                        } else {
                                                            $selectorBtn.empty().append('Ирсэн');
                                                        }
                                                        
                                                    } else {
                                                        $this.empty().append('<i class="'+ $icn +'"></i>');
                                                    }

                                                    senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});
                                                }
                                                Core.unblockUI(blockUIId);
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
                                                Core.unblockUI(blockUIId);
                                            }
                                        });
                                    }
                                }
                            }
                        });
                        
                        Core.blockUI({
                            message: 'Loading...', 
                            boxed: true
                        });
                        
                        setTimeout(function(){
                            Core.initTimeInput($('.process-<?php echo $this->uniqId ?>'));
                            Core.unblockUI();
                        }, 1000);
                    } else {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Error',
                            text: 'Тохиргоо олдсонгүй',
                            type: 'error',
                            sticker: false
                        });
                    }
                    
                    Core.unblockUI(blockUIId);
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
                    Core.unblockUI(blockUIId);
                }
            });
        }

        function timerAction_<?php echo $this->uniqId ?>(elem, $id) {

            var $this = $(elem),
                count = $this.attr('data-click'),
                startTime = $('input[name="startTime"]'),
                entTime = $('input[name="entTime"]'),
                type = $this.attr('data-type');

            
            var $ticketUpBreak = true;
            
            switch (type) {
                case 'start':
                    stopMainTimer_<?php echo $this->uniqId; ?> = type;
                    var $mainTime = $('.durationTimer_<?php echo $this->uniqId; ?> h5');
                    $('.breakTimer_<?php echo $this->uniqId; ?> h5').html("00:00:00");
                    $('.endTime_<?php echo $this->uniqId; ?>').html("");
                    var splitTimer = $mainTime.html().split(':');
                    var mainTimer = 0;

                    processingTimer_<?php echo $this->uniqId ?>(mainTimer, $mainTime, type);

                    currentTime = getDateTime();
                    currentdate = getDate();

                    $this.attr({'data-type': 'pause', 'title': 'Завсарлах'});
                    $this.html('<i class="fa fa-pause"></i>'); 
                    $ticketUpBreak = false;
                    
                    $.ajax({
                        url: 'conference/meetingstart', 
                        data: {
                            customdata: $('#gov_form_<?php echo $this->uniqId; ?>').serialize()
                        },
                        type: 'post',
                        dataType: "json",
                        success: function (data) {
                            if (typeof data.currentDate !== 'undefined') {
                                $('#digital-clock').text(data.currentDate);
                                startTime.val(data.currentDate);
                            } else {
                                $('#digital-clock').text(currentTime);
                                startTime.val(currentTime);
                            }

                            senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});
                        },
                        error: function () { }
                    }).done(function(){ });

                    $('.conferencestopbtn<?php echo $this->uniqId ?>').removeAttr('disabled');
                    break;
                case 'pause':
                    $('.conferencestopbtn<?php echo $this->uniqId ?>').removeAttr('disabled');
                    stopMainTimer_<?php echo $this->uniqId; ?> = type;

                    var $mainTime = $('.breakTimer_<?php echo $this->uniqId; ?> h5');
                    var splitTimer = $mainTime.html().split(':');
                    var mainTimer = 0;

                    mainTimer += parseInt(parseInt(splitTimer[0])*60*60);
                    mainTimer += parseInt(parseInt(splitTimer[1])*60);
                    mainTimer += parseInt(parseInt(splitTimer[2]))+1;
                    
                    $.ajax({
                        url: 'conference/setBreakTime', 
                        type: 'post',
                        dataType: "json",
                        data: {
                            bookId: '<?php echo $this->selectedRow['id'] ?>',
                        },
                        success: function (data) {
                            senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});
                        },
                        error: function () { }
                    });
                    
                    pauseProcessingTimer_<?php echo $this->uniqId ?>(mainTimer, $mainTime, type);

                    $this.attr({'data-type': 'play', 'title': 'Үргэлжлүүлэх'});
                    $this.html('<i class="fa fa-play"></i>'); 
                    break;

                case 'stop':
                    $ticketUpBreak = false;
                    if (confirm("Хурал дуусгахдаа итгэлтэй байна уу") == true) {
                        $.ajax({
                            url: 'conference/endConference', 
                            data: {
                                meetingData: $('#gov_form_<?php echo $this->uniqId; ?>').serialize()
                            },
                            type: 'post',
                            dataType: "json",
                            success: function (data) {
                                senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});

                                if (typeof data.currentDate !== 'undefined') {
                                    entTime.val(data.currentDate);
                                    $('.endTime h5').text(data.currentDate);
                                } else {
                                    entTime.val(currentTime);
                                    $('.endTime h5').text(currentTime);
                                }
                                
                                stopMainTimer_<?php echo $this->uniqId; ?> = 'stoped';
                                $('.conference-action-<?php echo $this->uniqId ?>').attr({'data-type': 'stoped', 'title': 'Зогссон', 'disabled': 'disabled'});
                                $this.attr({'data-type': 'stoped', 'title': 'Зогссон', 'disabled': 'disabled'});
                                $('.conference-action-<?php echo $this->uniqId ?>').html('<i class="fa fa-play"></i>'); 
                                
                                if ($('div.gov_issui_<?php echo $this->uniqId ?>').find('li.issue-start').length > 0) {
                                    var $selecterStop = $('div.gov_issui_<?php echo $this->uniqId ?>').find('li.issue-start');

                                    $.each($selecterStop, function ($index, row) {
                                        var $row = $(row);

                                        dieIssue<?php echo $this->uniqId ?>($row, $row.attr('data-id'));
                                        $row.removeClass('issue-start');
                                        $row.addClass('issue-stop').append('<button type="button" class="btn font-weight-bold finishadd" onclick="finishByDescription_<?php echo $this->uniqId; ?>(this, ' + $row.attr('data-id') +')"><i class="fa fa-gavel"></i></button>');
                                    });
                                }
                            },
                            error: function () { }
                        }).done(function() { });
                    }
                    break;
                case 'play':
                    $('.conferencestopbtn<?php echo $this->uniqId ?>').removeAttr('disabled');
                    stopMainTimer_<?php echo $this->uniqId; ?> = type;
                    $.ajax({
                        url: 'conference/setPlayTime', 
                        type: 'post',
                        dataType: "json",
                        data: {
                            bookId: '<?php echo $this->selectedRow['id'] ?>',
                        },
                        success: function (data) {
                            senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});
                        },
                        error: function () { }
                    });
                    
                    $this.attr({'data-type': 'pause', 'title': 'Завсарлах'});
                    $this.html('<i class="fa fa-pause"></i>');

                    var $mainTime = $('.durationTimer_<?php echo $this->uniqId; ?> h5');
                    var splitTimer = $mainTime.html().split(':');
                    var mainTimer = 0;

                    mainTimer += parseInt(parseInt(splitTimer[0])*60*60);
                    mainTimer += parseInt(parseInt(splitTimer[1])*60);
                    mainTimer += parseInt(parseInt(splitTimer[2]))+1;

                    playProcessingTimer_<?php echo $this->uniqId ?>(mainTimer, $mainTime, type);
                    
                    break;
            }
            
            if ($ticketUpBreak) {
                $.ajax({
                    url: 'conference/interrupt', 
                    data: {
                        count: count,
                        id: $id,
                        emplKeyIds: $('#gov_form_<?php echo $this->uniqId; ?>').serialize()
                    },
                    async: true,
                    type: 'post',
                    dataType: "json",
                    success: function (data) {
                        count++;
                        $this.attr('data-click', count);
                        senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});
                    },
                    error: function () { }
                });
            }
        }

        function getDateTime() {

            var now     = new Date(); 
            var hour    = now.getHours();
            var minute  = now.getMinutes();
            var second  = now.getSeconds(); 

            if (hour.toString().length == 2) {
                hour = '0' + hour;
            }

            if (minute.toString().length == 1) {
                minute = '0' + minute;
            }

            if (second.toString().length == 1) {
                second = '0'+second;
            }

            var dateTime = +hour+':'+minute;   
            return dateTime;

        }

        function getDate() {
            var now     = new Date(); 
            var year    = now.getFullYear();
            var month   = now.getMonth()+1; 
            var day     = now.getDate();

            if (month.toString().length == 1) {
                month = '0'+month;
            }

            if (day.toString().length == 1) {
                day = '0'+day;
            }   

            return year + '.' + month + '.' + day;;
        }

        setInterval(function() {
            currentTime = getDateTime();
            currentdate = getDate();
        }, 1000);
        
        if ('<?php echo issetParamZero($this->memberRole['readonly']); ?>' !== '0') {
            setInterval(function() {
                reload_<?php echo $this->uniqId ?>();
            }, 5000);
        }
        
        function setConferencingIssueHeader(ordernum, id, name, starttime, endtime, said, position ,imgpath, mainBtn, hidden) {

            if (typeof hidden === 'undefined') {
                $('#selected-conference-<?php echo $this->uniqId ?>').removeAttr('style');
            }

            if (typeof mainBtn === 'undefined') {
                $('#conferencing-issue-number', '.government_<?php echo $this->uniqId ?>').text(ordernum).attr('data-issue-id', id);
                $('#conferencing-issue-name', '.government_<?php echo $this->uniqId ?>').text(name);
                $('#said', '.government_<?php echo $this->uniqId ?>').text(said);
                $('#position2', '.government_<?php echo $this->uniqId ?>').text(position);
            }

            $('#conferencing-issue-starttime', '.government_<?php echo $this->uniqId ?>').text(starttime);
            $('#conferencing-issue-endtime', '.government_<?php echo $this->uniqId ?>').text(endtime);
            $('#conferencing-issue-number', '.government_<?php echo $this->uniqId ?>').text(ordernum).attr('data-issue-id', id);
            $('#conferencing-issue-name', '.government_<?php echo $this->uniqId ?>').text(name);
            $('#said', '.government_<?php echo $this->uniqId ?>').text(said);
            $('#position2', '.government_<?php echo $this->uniqId ?>').text(position);

            var $tooltipText = '';

            if (position) {
                $tooltipText += position + ' ';
            }

            if (said) {
                $tooltipText += said;
            }

            $('#profilemember', '.government_<?php echo $this->uniqId ?>').find('.profile-img')
                    .empty()
                    .append('<img src="'+URL_APP+''+imgpath+'"  width="34" height="34" class="rounded-circle" onerror="onUserImgError(this);" data-toggle="tooltip" data-placement="bottom" title="'+ $tooltipText +' "  />').promise().done(function () {
                    });
        }

        function setConferencingIssueListTemplate(data) {
            return '<li class="media">'+
                        '<div class="media-body">'+
                            '<ul class="media-title list-inline list-inline-dotted">'+
                                '<li class="list-inline-item">'+
                                    '<a href="javascript:void(0);" class="font-weight-bold text-uppercase font-size-vs">'+data.name+'</a>'+
                                '</li>'+
                                '<li class="list-inline-item">'+
                                    '<span class="font-size-sm text-muted dotted">'+date('H:i A', strtotime(data.time))+'</span>'+
                                '</li>'+
                            '</ul>'+ data.note + 
                        '</div>'+
                    '</li>';
        }

        function memberLog(elem, id) {

            var $dialogName = 'dialog-member_' +id;
            if (!$('#' + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);
            var fillDataParams = 'id='+id+'&defaultGetPf=1';

            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: '1562316727641', 
                    isDialog: true, 
                    isSystemMeta: false, 
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
                            if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                                $processForm.validate({ 
                                    ignore: '', 
                                    highlight: function(element) {
                                        $(element).addClass('error');
                                        $(element).parent().addClass('error');
                                        if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                            $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                                var tabId = $(tab).attr('id');
                                                $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                            });
                                        }
                                    },
                                    unhighlight: function(element) {
                                        $(element).removeClass('error');
                                        $(element).parent().removeClass('error');
                                    },
                                    errorPlacement: function(){} 
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
                                                senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});
                                                $(elem).closest('.fc-hrm-time-duration').prepend('<div class="fc-hrm-time-descr fc-hrm-time-wfmstatus" title="'+responseData.resultData.description+'">'+responseData.resultData.description+'</div>');
                                                $dialog.dialog('close');
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

                    setTimeout(function(){
                        $dialog.dialog('open');
                        Core.unblockUI();
                    }, 1);
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initBPAjax($dialog);
            });
        }

        function memberOtherAdd(elem, id) {

            var $dialogName = 'dialog-member_' +id;
            if (!$('#' + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);
            var fillDataParams = 'subjectId='+id+'&defaultGetPf=1';

            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: '1561450794386', 
                    isDialog: true, 
                    isSystemMeta: false, 
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
                            if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                                $processForm.validate({ 
                                    ignore: '', 
                                    highlight: function(element) {
                                        $(element).addClass('error');
                                        $(element).parent().addClass('error');
                                        if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                            $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                                var tabId = $(tab).attr('id');
                                                $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                            });
                                        }
                                    },
                                    unhighlight: function(element) {
                                        $(element).removeClass('error');
                                        $(element).parent().removeClass('error');
                                    },
                                    errorPlacement: function(){} 
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
                                                senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});
                                                $(elem).closest('.fc-hrm-time-duration').prepend('<div class="fc-hrm-time-descr fc-hrm-time-wfmstatus" title="'+responseData.resultData.description+'">'+responseData.resultData.description+'</div>');
                                                $dialog.dialog('close');
                                                $('#subject_'+id).trigger('click');
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

                    setTimeout(function(){
                        $dialog.dialog('open');
                        Core.unblockUI();
                    }, 1);
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initBPAjax($dialog);

            });
        }

        function addissue_<?php  echo $this->uniqId; ?>(elem, id) {
            
            var processId = '1563516521164';
            var $dialogName = 'dialog-businessprocess-' + processId;
            if (!$('#' + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            
            var $dialog = $('#' + $dialogName);
            var fillDataParams = 'meetingBookId='+id+'';
            var typeid = $('.gov_issui_<?php echo $this->uniqId; ?> > ul > li > a.active').attr('data-typeid');
            
            if (typeof typeid !== 'undefined' && typeid) {
                fillDataParams += '&typeId='+typeid+'';
            } 
            
            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: processId, 
                    isDialog: true, 
                    isSystemMeta: false, 
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
                    var runModeButton = '';
                    
                    if (data.run_mode === '') {
                        runModeButton = ' hide';
                    }
                    var buttons = [
                        {
                            text: data.run_mode,
                            class: 'btn green-meadow btn-sm bp-run-btn bp-btn-saveadd ' + runModeButton,
                            click: function(e) {

                                if (window['processBeforeSave_' + processUniqId]($(e.target))) {

                                    $processForm.find('select:visible').each(function() {
                                        var $this = $(this);
                                        if ($this.parent().find("div:first").hasClass("select2-container-disabled")) {
                                            $this.parent().find("div:first").attr("data-readonly", "");
                                        }
                                        if (typeof $this.parent().find("div:first").attr("data-readonly") !== 'undefined') {
                                            $this.prop("readonly", true);
                                        }
                                    });

                                    $processForm.validate({
                                        ignore: "",
                                        highlight: function(label) {
                                            $(label).addClass('error');
                                            $(label).parent().addClass('error');
                                            if (processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                                processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab) {
                                                    var tabId = $(tab).attr("id");
                                                    processForm.find('a[href="#' + tabId + '"]').tab('show');
                                                });
                                            }
                                        },
                                        unhighlight: function(label) {
                                            $(label).removeClass('error');
                                            $(label).parent().removeClass('error');
                                        },
                                        errorPlacement: function() {}
                                    });

                                    var isValidPattern = initBusinessProcessMaskEvent($processForm);

                                    if ($processForm.valid() && isValidPattern.length === 0) {

                                        if (typeof window[processUniqId + '_dialog'] !== 'undefined' && typeof window[processUniqId + '_note'] !== 'undefined' || typeof window[processUniqId + '_title'] !== 'undefined') {
                                            var $confirmDialog = $("#" + window[processUniqId + '_dialog']);
                                            $confirmDialog.empty().append(window[processUniqId + '_note']);
                                            $confirmDialog.dialog({
                                                cache: false,
                                                resizable: false,
                                                bgiframe: true,
                                                autoOpen: false,
                                                title: window[processUniqId + '_title'],
                                                width: 370,
                                                height: "auto",
                                                modal: true,
                                                open: function() {
                                                    setTimeout(function() {
                                                        $confirmDialog.dialog("option", "position", { my: "center", at: "center", of: window });
                                                    }, 100);
                                                },
                                                close: function() {
                                                    $confirmDialog.empty().dialog('destroy').remove();
                                                    uiDialogOverlayRemove();
                                                },
                                                buttons: [{
                                                        text: plang.get('yes_btn'),
                                                        class: 'btn green-meadow btn-sm',
                                                        click: function() {
                                                            if (typeof window[processUniqId + '_message'] !== 'undefined' && typeof window[processUniqId + '_messageType'] !== 'undefined') {
                                                                PNotify.removeAll();
                                                                new PNotify({
                                                                    title: window[processUniqId + '_messageType'],
                                                                    text: window[processUniqId + '_message'],
                                                                    type: window[processUniqId + '_messageType'],
                                                                    sticker: false
                                                                });
                                                            }
                                                            callWebServiceByMetaRunMode(processForm, $dialogName, processUniqId, e.target, function () {
                                                                reload_<?php echo $this->uniqId ?>('reload');
                                                            });

                                                            $confirmDialog.dialog('close');
                                                        }
                                                    },
                                                    {
                                                        text: plang.get('no_btn'),
                                                        class: 'btn blue-madison btn-sm',
                                                        click: function() {
                                                            $confirmDialog.dialog('close');
                                                        }
                                                    }
                                                ]
                                            });
                                            $confirmDialog.dialog('open');
                                        } else {
                                            callWebServiceByMetaRunMode($processForm, $dialogName, processUniqId, e.target, function () {
                                                reload_<?php echo $this->uniqId ?>('reload');
                                            });
                                        }
                                    }


                                } else {
                                    bpIgnoreGroupRemove(processForm);
                                }
                            }
                        },
                        {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                            if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                                $processForm.validate({ 
                                    ignore: '', 
                                    highlight: function(element) {
                                        $(element).addClass('error');
                                        $(element).parent().addClass('error');
                                        if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                            $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                                var tabId = $(tab).attr('id');
                                                $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                            });
                                        }
                                    },
                                    unhighlight: function(element) {
                                        $(element).removeClass('error');
                                        $(element).parent().removeClass('error');
                                    },
                                    errorPlacement: function(){} 
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
                                                /* senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'}); */
                                                $(elem).closest('.fc-hrm-time-duration').prepend('<div class="fc-hrm-time-descr fc-hrm-time-wfmstatus" title="'+responseData.resultData.description+'">'+responseData.resultData.description+'</div>');

                                                $dialog.dialog('close');
                                            } 
                                            
                                            reload_<?php echo $this->uniqId ?>('reload');
                                            Core.unblockUI();
                                        },
                                        error: function () {
                                            reload_<?php echo $this->uniqId ?>('reload');
                                            alert("Error");
                                        }
                                    });
                                }
                            }    

                        }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                            reload_<?php echo $this->uniqId ?>('reload');
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

                    setTimeout(function() {
                        $dialog.dialog('open');
                        Core.unblockUI();
                    }, 1);
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initBPAjax($dialog);
            });
        }

        function addcustomissue_<?php  echo $this->uniqId; ?>(elem, id) {

            var processId = '1563516499027';
            var $dialogName = 'dialog-businessprocess-' + processId;
            if (!$('#' + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);
            var fillDataParams = 'id='+id+'';

            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: processId, 
                    isDialog: true, 
                    isSystemMeta: false, 
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
                            if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                                $processForm.validate({ 
                                    ignore: '', 
                                    highlight: function(element) {
                                        $(element).addClass('error');
                                        $(element).parent().addClass('error');
                                        if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                            $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                                var tabId = $(tab).attr('id');
                                                $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                            });
                                        }
                                    },
                                    unhighlight: function(element) {
                                        $(element).removeClass('error');
                                        $(element).parent().removeClass('error');
                                    },
                                    errorPlacement: function(){} 
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
                                                /* senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'}); */
                                                $(elem).closest('.fc-hrm-time-duration').prepend('<div class="fc-hrm-time-descr fc-hrm-time-wfmstatus" title="'+responseData.resultData.description+'">'+responseData.resultData.description+'</div>');
                                                $dialog.dialog('close');
                                            } 
                                            reload_<?php echo $this->uniqId ?>('reload');
                                            Core.unblockUI();
                                        },
                                        error: function () {
                                            reload_<?php echo $this->uniqId ?>('reload');
                                            alert("Error");
                                        }
                                    });
                                }
                            }   
                        }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                            reload_<?php echo $this->uniqId ?>('reload');
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

                    setTimeout(function() {
                        $dialog.dialog('open');
                        Core.unblockUI();
                    }, 1);
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initBPAjax($dialog);
            });
        }

        function transferProcessActionCustom(elem, id, sid) {
            var processId = '1561449186178';
            var $dialogName = 'dialog-businessprocess-' + processId;
            if (!$('#' + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);
            var fillDataParams = 'id='+id+'';

            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: processId, 
                    isDialog: true, 
                    isSystemMeta: false, 
                    fillDataParams: fillDataParams
                },
                dataType: 'json',
                beforeSend: function () {
                    /*
                    Core.blockUI({
                        message: 'Loading...', 
                        boxed: true
                    });*/
                },
                success: function (data) {

                    $dialog.empty().append(data.Html);

                    var $processForm = $('#wsForm', '#' + $dialogName), 
                        processUniqId = $processForm.parent().attr('data-bp-uniq-id');

                    var buttons = [
                        {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                            if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                                $processForm.validate({ 
                                    ignore: '', 
                                    highlight: function(element) {
                                        $(element).addClass('error');
                                        $(element).parent().addClass('error');
                                        if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                            $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                                var tabId = $(tab).attr('id');
                                                $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                            });
                                        }
                                    },
                                    unhighlight: function(element) {
                                        $(element).removeClass('error');
                                        $(element).parent().removeClass('error');
                                    },
                                    errorPlacement: function(){} 
                                });

                                var isValidPattern = initBusinessProcessMaskEvent($processForm);

                                if ($processForm.valid() && isValidPattern.length === 0) {
                                    $processForm.ajaxSubmit({
                                        type: 'post',
                                        url: 'mdwebservice/runProcess',
                                        dataType: 'json',
                                        beforeSend: function () {
                                            /*
                                            Core.blockUI({
                                                boxed: true, 
                                                message: 'Түр хүлээнэ үү'
                                            });*/
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
                                                senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});
                                                $(elem).closest('.fc-hrm-time-duration').prepend('<div class="fc-hrm-time-descr fc-hrm-time-wfmstatus" title="'+responseData.resultData.description+'">'+responseData.resultData.description+'</div>');
                                                $dialog.dialog('close');
                                                $('#subject_'+sid).trigger('click');
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
                        modal: false,
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

                    setTimeout(function() {
                        $dialog.dialog('open');
                        Core.unblockUI();
                    }, 1);
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initBPAjax($dialog);
            });
        }

        function finishByDescription_<?php echo $this->uniqId; ?>(elem, id) {
            var $dataRow = JSON.parse($(elem).closest('li').attr('data-row'));

            var processId = '1572546879299';
            var $dialogName = 'dialog-businessprocess-' + processId;
            if (!$('#' + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);

            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: processId, 
                    dmMetaDataId: '1570518822359',
                    isDialog: true, 
                    isSystemMeta: false, 
                    oneSelectedRow: $dataRow
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
                            if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                                $processForm.validate({ 
                                    ignore: '', 
                                    highlight: function(element) {
                                        $(element).addClass('error');
                                        $(element).parent().addClass('error');
                                        if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                            $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                                var tabId = $(tab).attr('id');
                                                $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                            });
                                        }
                                    },
                                    unhighlight: function(element) {
                                        $(element).removeClass('error');
                                        $(element).parent().removeClass('error');
                                    },
                                    errorPlacement: function(){} 
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
                                                senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});
                                                $dialog.dialog('close');
                                            } 
                                            
                                            reload_<?php echo $this->uniqId ?>('reload');
                                            Core.unblockUI();
                                        },
                                        error: function () {
                                            reload_<?php echo $this->uniqId ?>('reload');
                                            alert("Error");
                                        }
                                    });
                                }
                            }    

                        }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                            reload_<?php echo $this->uniqId ?>('reload');
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

                    setTimeout(function() {
                        $dialog.dialog('open');
                        Core.unblockUI();
                    }, 1);
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initBPAjax($dialog);
            });
        }
        
        function setProtocol_<?php echo $this->uniqId; ?> (elem) {
            var $this = $(elem),
                $parent = $this.closest('li'),
                $mainRow = $parent.attr('data-row');
            $parent.trigger('click');
            setTimeout(function () {
                if (typeof $mainRow !== 'object') {
                    $mainRow = JSON.parse($mainRow);
                }

                var $mainSelector = $('.government_<?php echo $this->uniqId; ?>');
                var $empSectionParent = $mainSelector.find('ul#other-member-list-<?php echo $this->uniqId ?>');
                var $empMainmemberParent = $mainSelector.find('div.mainmember<?php echo $this->uniqId ?>');
                var $empOthermemberParent = $mainSelector.find('div.othermember<?php echo $this->uniqId ?>');
                var $mainHtml = '', $otherHtml = '', $sectionHtml = '';

                $empMainmemberParent.find('li[data-status="1"]').each(function (index, row) {
                    var $row = $(row);
                    var $rowJson = $row.attr('data-row');
                    var $rowJsonOb = JSON.parse($rowJson);

                    $mainHtml += '<li class="media" data-istalkin="0" data-id="'+ $mainRow['id'] +'" data-bookid="'+ $mainRow['meetingbookid'] +'" data-row="'+ htmlentities(JSON.stringify($rowJsonOb), 'ENT_QUOTES', 'UTF-8') +'" data-type="member">';
                        $mainHtml += '<a href="javascript:;" class="mr-2 position-relative">';
                            $mainHtml += '<img src="'+ $rowJsonOb['picture'] +'" class="rounded-circle" onerror="onUserImgError(this);" width="34" height="34">';
                        $mainHtml += '</a>';
                        $mainHtml += '<div class="media-body">';
                            $mainHtml += '<div class="membername font-weight-bold text-uppercase line-height-normal d-flex align-items-center">';
                                $mainHtml += '<span>'+ $rowJsonOb['employeename'] +'</span>';
                            $mainHtml += '</div>';
                            $mainHtml += '<span class="memberposition">'+ $rowJsonOb['positionname'] +'</span>';
                        $mainHtml += '</div>';
                        $mainHtml += '<div class="ml-3 align-self-center" style="margin-top: -3px;">';
                            $mainHtml += '<button type="button" id="mem20072106" class="btn startbtn small" title="Микрофонтой" onclick="talkProtocol<?php echo $this->uniqId ?>(this)" style="padding: 0!important;" data-num="0"  title="">';
                                $mainHtml += '<img src="assets/custom/img/ico/gif-with-audio-1.gif" onerror="onUserImgError(this);" data-path="talking" style=" height: 24px !important; width: 24px !important; margin: 0;  display: none;">';
                                $mainHtml += '<img src="assets/custom/img/ico/mic.png" onerror="onUserImgError(this);" data-path="talk" style=" height: 24px !important; width: 24px !important; margin: 0;">';
                            $mainHtml += '</button>';
                        $mainHtml += '</div>';
                        $mainHtml += '<div class="ml-1 align-self-center" style="margin-top: -3px;">';
                            $mainHtml += '<button type="button" id="mem20072106" class="btn startbtn small" title="Микрофонгүй"  onclick="nonetalkProtocol<?php echo $this->uniqId ?>(this)" style="padding: 0!important;" data-num="0"  title="">';
                                $mainHtml += '<img src="assets/custom/img/ico/ss_mic.png" onerror="onUserImgError(this);" data-path="talking_no" style=" height: 24px !important; width: 18px !important; margin: 0;  display: none;">';
                                $mainHtml += '<img src="assets/custom/img/ico/audio_mic.png" onerror="onUserImgError(this);" data-path="talk_no" style=" height: 24px !important; width: 24px !important; margin: 0;">';
                            $mainHtml += '</button>';
                        $mainHtml += '</div>';
                        $mainHtml += '<div class="ml-1 align-self-center" style="margin-top: -3px;">';
                            $mainHtml += '<button type="button" class="btn startbtn btnstatus small"  title="Цуцлах"   onclick="cancelProtocol<?php echo $this->uniqId ?>(this)">';
                                $mainHtml += '<img src="assets/custom/img/ico/mic_cancel.png" onerror="onUserImgError(this);" style="height: 21px !important;width: 20px !important;margin: 0;position: relative;right: 2px;">';
                            $mainHtml += '</button>';
                        $mainHtml += '</div>';
                    $mainHtml += '</li>';
                });
                $empOthermemberParent.find('li[data-status="1"]').each(function (index, row) {
                    var $row = $(row);
                    var $rowJson = $row.attr('data-row');
                    var $rowJsonOb = JSON.parse($rowJson);

                    $otherHtml += '<li class="media" data-istalkin="0" data-id="'+ $mainRow['id'] +'" data-bookid="'+ $mainRow['meetingbookid'] +'" data-row="'+ htmlentities(JSON.stringify($rowJsonOb), 'ENT_QUOTES', 'UTF-8') +'" data-type="member">';
                        $otherHtml += '<a href="javascript:;" class="mr-2 position-relative">';
                            $otherHtml += '<img src="'+ $rowJsonOb['picture'] +'" class="rounded-circle" onerror="onUserImgError(this);" width="34" height="34">';
                        $otherHtml += '</a>';
                        $otherHtml += '<div class="media-body">';
                            $otherHtml += '<div class="membername font-weight-bold text-uppercase line-height-normal d-flex align-items-center">';
                                $otherHtml += '<span>'+ $rowJsonOb['employeename'] +'</span>';
                            $otherHtml += '</div>';
                            $otherHtml += '<span class="memberposition">'+ $rowJsonOb['positionname'] +'</span>';
                        $otherHtml += '</div>';
                        $otherHtml += '<div class="ml-3 align-self-center" style="margin-top: -3px;">';
                            $otherHtml += '<button type="button" id="mem20072106" title="Микрофонтой"  class="btn startbtn small" onclick="talkProtocol<?php echo $this->uniqId ?>(this)" style="padding: 0!important;" data-num="0"  title="">';
                                $otherHtml += '<img src="assets/custom/img/ico/gif-with-audio-1.gif" onerror="onUserImgError(this);" data-path="talking" style=" height: 24px !important; width: 24px !important; margin: 0;  display: none;">';
                                $otherHtml += '<img src="assets/custom/img/ico/mic.png" onerror="onUserImgError(this);" data-path="talk" style=" height: 24px !important; width: 24px !important; margin: 0;">';
                            $otherHtml += '</button>';
                        $otherHtml += '</div>';
                        $otherHtml += '<div class="ml-1 align-self-center" style="margin-top: -3px;">';
                            $otherHtml += '<button type="button" id="mem20072106" title="Микрофонгүй"  class="btn startbtn small" onclick="nonetalkProtocol<?php echo $this->uniqId ?>(this)" style="padding: 0!important;" data-num="0"  title="">';
                                $otherHtml += '<img src="assets/custom/img/ico/ss_mic.png" onerror="onUserImgError(this);" data-path="talking_no" style=" height: 24px !important; width: 18px !important; margin: 0;  display: none;">';
                                $otherHtml += '<img src="assets/custom/img/ico/audio_mic.png" onerror="onUserImgError(this);" data-path="talk_no" style=" height: 24px !important; width: 24px !important; margin: 0;">';
                            $otherHtml += '</button>';
                        $otherHtml += '</div>';
                        $otherHtml += '<div class="ml-1 align-self-center" style="margin-top: -3px;">';
                            $otherHtml += '<button type="button" class="btn startbtn btnstatus small" title="Цуцлах"   onclick="cancelProtocol<?php echo $this->uniqId ?>(this)">';
                                $otherHtml += '<img src="assets/custom/img/ico/mic_cancel.png" onerror="onUserImgError(this);" style="height: 21px !important;width: 20px !important;margin: 0;position: relative;right: 2px;">';
                            $otherHtml += '</button>';
                        $otherHtml += '</div>';
                    $otherHtml += '</li>';
                });
                $empSectionParent.find('li').each(function (index, row) {
                    var $row = $(row);
                    var $rowJson = $row.attr('data-row');
                    var $rowJsonOb = JSON.parse($rowJson);
                    if (typeof $rowJsonOb !== 'undefined' && $rowJsonOb) {
                        $sectionHtml += '<li class="media" data-istalkin="0" data-id="'+ $mainRow['id'] +'" data-bookid="'+ $mainRow['meetingbookid'] +'" data-row="'+ htmlentities(JSON.stringify($rowJsonOb), 'ENT_QUOTES', 'UTF-8') +'" data-type="section">';
                            $sectionHtml += '<a href="javascript:;" class="mr-2 position-relative">';
                                $sectionHtml += '<img src="'+ $rowJsonOb['picture'] +'" class="rounded-circle" onerror="onUserImgError(this);" width="34" height="34">';
                            $sectionHtml += '</a>';
                            $sectionHtml += '<div class="media-body">';
                                $sectionHtml += '<div class="membername font-weight-bold text-uppercase line-height-normal d-flex align-items-center">';
                                    $sectionHtml += '<span>'+ $rowJsonOb['firstname'] +'</span>';
                                $sectionHtml += '</div>';
                                $sectionHtml += '<span class="memberposition">'+ $rowJsonOb['positionname'] +'</span>';
                            $sectionHtml += '</div>';
                            $sectionHtml += '<div class="ml-3 align-self-center" style="margin-top: -3px;">';
                                $sectionHtml += '<button type="button" id="mem20072106" class="btn startbtn small" title="Микрофонтой" onclick="talkProtocol<?php echo $this->uniqId ?>(this)" style="padding: 0!important;" data-num="0"  title="">';
                                    $sectionHtml += '<img src="assets/custom/img/ico/gif-with-audio-1.gif" onerror="onUserImgError(this);" data-path="talking" style=" height: 24px !important; width: 24px !important; margin: 0;  display: none;">';
                                    $sectionHtml += '<img src="assets/custom/img/ico/mic.png" onerror="onUserImgError(this);" data-path="talk" style=" height: 24px !important; width: 24px !important; margin: 0;">';
                                $sectionHtml += '</button>';
                            $sectionHtml += '</div>';
                            $sectionHtml += '<div class="ml-1 align-self-center" style="margin-top: -3px;">';
                                $sectionHtml += '<button type="button" id="mem20072106" class="btn startbtn small" title="Микрофонгүй" onclick="nonetalkProtocol<?php echo $this->uniqId ?>(this)" style="padding: 0!important;" data-num="0"  title="">';
                                    $sectionHtml += '<img src="assets/custom/img/ico/ss_mic.png" onerror="onUserImgError(this);" data-path="talking_no" style=" height: 24px !important; width: 18px !important; margin: 0;  display: none;">';
                                    $sectionHtml += '<img src="assets/custom/img/ico/audio_mic.png" onerror="onUserImgError(this);" data-path="talk_no"  style=" height: 24px !important; width: 24px !important; margin: 0;">';
                                $sectionHtml += '</button>';
                            $sectionHtml += '</div>';
                            $sectionHtml += '<div class="ml-1 align-self-center" style="margin-top: -3px;">';
                                $sectionHtml += '<button type="button" class="btn startbtn btnstatus small" title="Цуцлах"  onclick="cancelProtocol<?php echo $this->uniqId ?>(this)">';
                                    $sectionHtml += '<img src="assets/custom/img/ico/mic_cancel.png" onerror="onUserImgError(this);" style="height: 21px !important;width: 20px !important;margin: 0;position: relative;right: 2px;">';
                                $sectionHtml += '</button>';
                            $sectionHtml += '</div>';
                        $sectionHtml += '</li>';
                    }
                });

                var $dialogName = 'dialog-protocol-<?php echo $this->uniqId; ?>';
                var $html = '';
                $html += '<div class="government">';
                    $html +='<div class=" member-list-conference sidebar-right2 wmin-350 border-0 shadow-0 order-1 order-md-2 sidebar-expand-md">';
                        $html += '<div class="sidebar-content">';
                            $html += '<div class="org-choice w-100">'+ $mainRow['ordernum'] +'. '+ $mainRow['subjectname'] +'</div>';
                            $html += '<div class="w-50 pull-left">';
                                $html += '<div class="card">';
                                    $html += '<div class="card-header bg-transparent header-elements-inline">';
                                        $html += '<span class="text-uppercase font-weight-bold">Хуралдаанд оролцогчид</span>';
                                        $html += '<div class="header-elements">';
                                            $html += '<div class="list-icons">';
                                                $html += '<a class="list-icons-item" data-action="collapse"></a>';
                                            $html += '</div>';
                                        $html += '</div>';
                                    $html += '</div>';
                                    $html += '<div class="card-body pt-0">';
                                        $html += '<ul class="media-list">';
                                            $html += $mainHtml;
                                        $html += '</ul>';
                                    $html += '</div>';
                                $html += '</div>';
                            $html += '</div>';
                            $html += '<div class="w-50 pull-left pl-1">';
                                $html += '<div class="card mb-1">';
                                    $html += '<div class="card-header bg-transparent header-elements-inline">';
                                        $html += '<span class="text-uppercase font-weight-bold">Бусад оролцогчид</span>';
                                        $html += '<div class="header-elements">';
                                            $html += '<div class="list-icons">';
                                                $html += '<a class="list-icons-item" data-action="collapse"></a>';
                                            $html += '</div>';
                                        $html += '</div>';
                                    $html += '</div>';
                                    $html += '<div class="card-body pt-0">';
                                        $html += '<ul class="media-list">';
                                            $html += $otherHtml;
                                        $html += '</ul>';
                                    $html += '</div>';
                                $html += '</div>';
                                $html += '<div class="card mb-1">';
                                    $html += '<div class="card-header bg-transparent header-elements-inline">';
                                        $html += '<span class="text-uppercase font-weight-bold">Ажлын хэсгийнхэн</span>';
                                    $html += '</div>';
                                    $html += '<div class="card-body pt-0">';
                                        $html += '<ul class="media-list">';
                                            $html += $sectionHtml;
                                        $html += '</ul>';
                                    $html += '</div>';
                                $html += '</div>';
                            $html += '</div>';
                        $html += '</div>';
                    $html += '</div>';
                $html += '</div>';

                var $modalhtml = '';
                $modalhtml += '<div class="modal pl0 fade modal-after-save-close dialogoms" id="'+ $dialogName +'" tabindex="-1" role="dialog" aria-hidden="true">';
                    $modalhtml += '<div class="modal-dialog  modal-full" style="max-width: 950px !important; margin-top: 10px;">';
                        $modalhtml += '<div class="modal-content modalcontent<?php echo $this->uniqId ?>">';
                            $modalhtml += '<div class="modal-header">';
                                $modalhtml += '<h4 class="modal-title">' + plang.get('protocol_title') + '</h4>';
                                $modalhtml += '<button type="button" class="close" aria-hidden="true" onclick="closeProtocol_<?php echo $this->uniqId ?>(\''+ $mainRow['meetingbookid'] +'\', \''+ $dialogName +'\')">×</button>';
                            $modalhtml += '</div>';
                            $modalhtml += '<div class="modal-body">';
                                $modalhtml += $html ;
                            $modalhtml += '</div>';
                            $modalhtml += '<div class="modal-footer">';
                                $modalhtml += '<button type="button" class="btn btn-sm btn-danger" onclick="closeProtocol_<?php echo $this->uniqId ?>(\''+ $mainRow['meetingbookid'] +'\', \''+ $dialogName +'\')">' + plang.get('close_btn') + '</button>';
                            $modalhtml += '</div>' ;
                        $modalhtml += '</div>';
                    $modalhtml += '</div>';
                $modalhtml += '</div>';

                $($modalhtml).appendTo('body');

                var $dialog   = $('#' + $dialogName);

                $dialog.modal({
                    show: false,
                    keyboard: false,
                    backdrop: 'static'
                });

                $dialog.on('shown.bs.modal', function () {
                    setTimeout(function() {
                        Core.initAjax($dialog);
                        Core.unblockUI();
                    }, 10);    
                });   

                $dialog.draggable({
                    handle: ".modal-header"
                });

                $dialog.on('hidden.bs.modal', function () {
                    $dialog.remove();
                });            

                $dialog.modal('show');    
            }, 1000);
            
        }
        
        function nonetalkProtocol<?php echo $this->uniqId ?> (element) {
            var $this = $(element),
                $parent = $this.closest('li'),
                $content = $this.closest('.sidebar-content');
            var $dataRow = $parent.attr('data-row');
            
            
            if (typeof $dataRow !== 'object') {
                $dataRow = JSON.parse($dataRow);
            }
            
            var subjectId = $parent.attr('data-id'),
                bookId = $parent.attr('data-bookid');
            
            $content.find('img[data-path="talking_no"]').hide();
            $content.find('img[data-path="talk_no"]').show();
            
            $content.find('img[data-path="talking"]').hide();
            $content.find('img[data-path="talk"]').show();
            
            if ($parent.attr('data-istalkin') === '0') {
                $content.find('[data-istalkin]').attr('data-istalkin', '0');
                $parent.attr('data-istalkin', '1');
                
                var $param ;
                if ($parent.attr('data-type') !== 'section') {
                    $param = {
                        'subjectId': subjectId,
                        'bookId' : bookId,
                        'employeeKeyId': $dataRow['employeekeyid'],
                        'type': '2',
                    };
                } else {
                    $param = {
                        'subjectId': subjectId,
                        'bookId' : bookId,
                        'participantId': $dataRow['id'],
                        'type': '2',
                    };
                }
                
                $.ajax({
                    type: 'post',
                    url: 'conference/protocol',
                    dataType: 'json',
                    data: $param,
                    beforeSend: function () {},
                    success: function (data) {
                        $parent.attr('data-protocolid', data.id);
                        $parent.find('img[data-path="talking_no"]').show();
                        $parent.find('img[data-path="talk_no"]').hide();
                        
                        $parent.find('img[data-path="talking"]').hide();
                        $parent.find('img[data-path="talk"]').show();
                    },
                    error: function (jqXHR, exception) {
                        Core.showErrorMessage(jqXHR, exception);
                    }
                });
                
            } else {
                
                $content.find('[data-istalkin]').attr('data-istalkin', '0');
                $parent.attr('data-istalkin', '0');
                
                var $param ;
                if ($parent.attr('data-type') !== 'section') {
                    $param = {
                        'id': $parent.attr('data-protocolid'),
                        'subjectId': subjectId,
                        'employeeKeyId': $dataRow['employeekeyid'],
                    };
                } else {
                    $param = {
                        'id': $parent.attr('data-protocolid'),
                        'subjectId': subjectId,
                        'participantId': $dataRow['id'],
                    };
                }
                
                $.ajax({
                    type: 'post',
                    url: 'conference/protocol',
                    dataType: 'json',
                    data: $param,
                    beforeSend: function () {},
                    success: function (data) {
                        $parent.removeAttr('data-protocolid');
                        $parent.find('img[data-path="talking_no"]').hide();
                        $parent.find('img[data-path="talk_no"]').show();
                    },
                    error: function (jqXHR, exception) {
                        Core.showErrorMessage(jqXHR, exception);
                    }
                });
                
            }
        }
        
        function talkProtocol<?php echo $this->uniqId ?>(element) {
            var $this = $(element),
                $parent = $this.closest('li'),
                $content = $this.closest('.sidebar-content');
            var $dataRow = $parent.attr('data-row');
            
            if (typeof $dataRow !== 'object') {
                $dataRow = JSON.parse($dataRow);
            }
            
            var subjectId = $parent.attr('data-id'),
                bookId = $parent.attr('data-bookid');
            
            $content.find('img[data-path="talking_no"]').hide();
            $content.find('img[data-path="talk_no"]').show();
            
            $content.find('img[data-path="talking"]').hide();
            $content.find('img[data-path="talk"]').show();
            
            if ($parent.attr('data-istalkin') === '0') {
                
                $content.find('[data-istalkin]').attr('data-istalkin', '0');
                $parent.attr('data-istalkin', '1');
                
                var $param ;
                if ($parent.attr('data-type') !== 'section') {
                    $param = {
                        'subjectId': subjectId,
                        'bookId' : bookId,
                        'employeeKeyId': $dataRow['employeekeyid'],
                        'type': '1',
                    };
                } else {
                    $param = {
                        'subjectId': subjectId,
                        'bookId' : bookId,
                        'participantId': $dataRow['id'],
                        'type': '1',
                    };
                }
                
                $.ajax({
                    type: 'post',
                    url: 'conference/protocol',
                    dataType: 'json',
                    data: $param,
                    beforeSend: function () {},
                    success: function (data) {
                        $parent.attr('data-protocolid', data.id);
                        $parent.find('img[data-path="talking"]').show();
                        $parent.find('img[data-path="talk"]').hide();
                        
                        $parent.find('img[data-path="talking_no"]').hide();
                        $parent.find('img[data-path="talk_no"]').show();
                    },
                    error: function (jqXHR, exception) {
                        Core.showErrorMessage(jqXHR, exception);
                    }
                });
                
            } else {
                
                $content.find('[data-istalkin]').attr('data-istalkin', '0');
                $parent.attr('data-istalkin', '0');
                
                var $param ;
                if ($parent.attr('data-type') !== 'section') {
                    $param = {
                        'id': $parent.attr('data-protocolid'),
                        'subjectId': subjectId,
                        'employeeKeyId': $dataRow['employeekeyid'],
                    };
                } else {
                    $param = {
                        'id': $parent.attr('data-protocolid'),
                        'subjectId': subjectId,
                        'participantId': $dataRow['id'],
                    };
                }
                
                $.ajax({
                    type: 'post',
                    url: 'conference/protocol',
                    dataType: 'json',
                    data: $param,
                    beforeSend: function () {},
                    success: function (data) {
                        $parent.removeAttr('data-protocolid');
                        $parent.find('img[data-path="talking"]').hide();
                        $parent.find('img[data-path="talk"]').show();
                    },
                    error: function (jqXHR, exception) {
                        Core.showErrorMessage(jqXHR, exception);
                    }
                });
            }
        }
        
        function closeProtocol_<?php echo $this->uniqId ?> (meetingBookId, dialog) {
            $.ajax({
                type: 'post',
                url: 'conference/closeprotocol',
                dataType: 'json',
                data: {bookId: meetingBookId},
                beforeSend: function () {},
                success: function (data) {
                    $('#' + dialog).modal('hide');
                },
                error: function (jqXHR, exception) {
                    Core.showErrorMessage(jqXHR, exception);
                }
            });
        }
        
        function cancelProtocol<?php echo $this->uniqId ?>(element) {
            var $this = $(element),
                $parent = $this.closest('li'), 
                $content = $this.closest('.sidebar-content');
        
            if ($parent.attr('data-istalkin') === '1' && typeof $parent.attr('data-protocolid') !== 'undefined') {
                
                $content.find('img[data-path="talking"]').hide();
                $content.find('img[data-path="talking_no"]').hide();
                $content.find('[data-istalkin]').attr('data-istalkin', '0');
                
                $content.find('img[data-path="talk"]').show();
                $content.find('img[data-path="talk_no"]').show();
                
                var $param = {
                        'id': $parent.attr('data-protocolid')
                    };   
                $.ajax({
                    type: 'post',
                    url: 'conference/rmprotocol',
                    dataType: 'json',
                    data: $param,
                    beforeSend: function () {},
                    success: function (data) {
                        $parent.removeAttr('data-protocolid');
                    },
                    error: function (jqXHR, exception) {
                        Core.showErrorMessage(jqXHR, exception);
                    }
                });
                
            } else {
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: 'Ярьж эхлээгүй байна',
                    type: 'warning',
                    sticker: false
                });
            }
        }
        
        function changeConferenceTimer_<?php echo $this->uniqId ?> (elem) {
            var $this = $(elem);
            var $closestSelector = $this.closest('li');
            var $dataRow = JSON.parse($closestSelector.attr('data-row'));
            var $selectorMainId = $closestSelector.attr('id');

            var $dialogName = 'dialog-conference-time-<?php echo $this->uniqId; ?>';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }

            $.ajax({
                type: 'post',
                url: 'conference/changeTimeConferenceForm',
                dataType: 'json',
                data: {dataRow: $dataRow},
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (data) {
                    $("#" + $dialogName).empty().append(data.Html).promise().done(function () {
                        Core.initAjax($("#" + $dialogName));
                    });
                    $("#" + $dialogName).dialog({
                        cache: false,
                        resizable: false,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: data.Width,
                        height: "auto",
                        modal: true,
                        close: function () {
                            $("#" + $dialogName).empty().dialog('destroy').remove();
                        },
                        buttons: [
                            {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {
                                    
                                var $startTime = $('#saveConferenceTime_'+ data.uniqId +' input[data-path="startTime"]').val();
                                var $endTime = $('#saveConferenceTime_'+ data.uniqId +' input[data-path="endTime"]').val();

                                if ($startTime > $endTime && $endTime) {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: 'Warning',
                                        text: $startTime + ' цаг ' + $endTime + ' цагаас бага байх тул дахин оролдно уу?',
                                        type: 'warning',
                                        sticker: false
                                    });
                                    
                                    return false;
                                }
                                
                                if (!$startTime) {
                                    new PNotify({
                                        title: 'warning',
                                        text: 'Эхлэх цаг заавал бөглөнө үү',
                                        type: 'warning',
                                        sticker: false
                                    });
                                    return;
                                } 
                                
                                if (!$endTime) {
                                    new PNotify({
                                        title: 'warning',
                                        text: 'Дуусах цаг заавал бөглөнө үү',
                                        type: 'warning',
                                        sticker: false
                                    });
                                    return;
                                } 
                                
                                if ($startTime <= $endTime) {

                                } else {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: 'warning',
                                        text: 'Эхлэх цаг Дуусах цагаас ихгүй байна',
                                        type: 'warning',
                                        sticker: false
                                    });
                                    return;
                                }
                                    
                                dialogConfirm_<?php echo $this->uniqId ?> ('saveConferenceTime_<?php echo $this->uniqId ?>', 'Хадгалахдаа итгэлтэй байна уу?', data, $dialogName, $this);
                            }},
                            {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                                $("#" + $dialogName).dialog('close');
                            }}
                        ]
                    });
                    $("#" + $dialogName).dialog('open');
                    Core.unblockUI();
                },
                error: function () {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Warning',
                        text: 'Developing...',
                        type: 'warning',
                        sticker: false
                    });
                    return;
                }
            });
        }

        function saveFinishLog_<?php echo $this->uniqId ?>(data, $dialogName, $dialogConfirm, $element) {
            var selectorForm = $('#finishLogForm_' + data.uniqId);
            selectorForm.validate({errorPlacement: function () {}});
            if (selectorForm.valid()) {
                selectorForm.ajaxSubmit({
                    type: 'post',
                    url: 'conference/saveFinishLogForm',
                    dataType: 'json',
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function (dataSub) {
                        PNotify.removeAll();
                        new PNotify({
                            title: dataSub.status,
                            text: dataSub.text,
                            type: dataSub.status,
                            sticker: false
                        });

                        if (dataSub.status === 'success') {
                            $("#" + $dialogName).dialog('close');
                            $("#" + $dialogConfirm).dialog('close');
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
        }

        function saveConferenceTime_<?php echo $this->uniqId ?>(data, $dialogName, $dialogConfirm, $element) {
            var selectorForm = $('#saveConferenceTime_' + data.uniqId);
            var $selectorMainId = $element.closest('li').attr('id');

            var rowNumber = $element.closest('li').find('div.number').attr('row-number');

            selectorForm.validate({errorPlacement: function () {}});
            if (selectorForm.valid()) {
                selectorForm.ajaxSubmit({
                    type: 'post',
                    url: 'conference/saveTimeConferenceForm',
                    dataType: 'json',
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                        Core.blockUI({
                            animate: true,
                            target: '#' + $selectorMainId
                        });
                    },
                    success: function (dataSub) {
                        PNotify.removeAll();
                        new PNotify({
                            title: dataSub.status,
                            text: dataSub.text,
                            type: dataSub.status,
                            sticker: false
                        });

                        if (dataSub.status === 'success') {
                            $("#" + $dialogName).dialog('close');
                            $("#" + $dialogConfirm).dialog('close');
                            Core.unblockUI();

                            if (typeof dataSub.data !== 'undefined' && typeof dataSub.datacompress && dataSub.data && dataSub.datacompress) {
                                var conferenceData = dataSub.data;
                                var $html = '<div class="mr-3 font-weight-bold number" row-number="'+ rowNumber +'">'+ rowNumber +'</div>'
                                                + '<div class="media-body">'
                                                    + '<p class="font-weight-bold line-height-normal mb-0 conf-issuelist-name">'+ conferenceData['subjectname'] +'</p>'
                                                    + '<ul class="media-title list-inline list-inline-dotted">'
                                                        + '<li class="list-inline-item mt-1">'
                                                            + '<span class="memberposition font-weight-bold">'+ ((conferenceData['saidname']) ? conferenceData['saidname'] + ' - ' : '') +'</span>'
                                                            + '<span class="memberposition1 font-weight-bold">'+ ((conferenceData['departmentname']) ? conferenceData['departmentname'] : '') +' </span>'
                                                            + '<span class="memberposition2 font-weight-bold">'+ ((conferenceData['referentname']) ? '-' + conferenceData['referentname'] : '') + '</span>'
                                                            + '<span class="memberpic hidden">'+ conferenceData['saidphoto'] +'</span>'
                                                        + '</li>'
                                                    + '</ul>'
                                                    + '<p class="font-weight-bold line-height-normal mb-0 conf-issuelist-start timestart conf-issuelist-timer"  style="text-align: right">'
                                                        + '<span class="timestart timer-start"> </span>'
                                                        + conferenceData['starttime'] + ' - ' + conferenceData['endtime'] + ' <span class="icon-p"  data-toggle="tooltip" data-placement="bottom" title="Цагт засвар хийх" onclick="changeConferenceTimer_<?php echo $this->uniqId ?>(this)"> <i class="icon-alarm"></i></span></p>'
                                                    + '<div class="participants align-self-center ">Ажлын хэсэг: '+ ((conferenceData['subjectparticipantcount']) ? conferenceData['subjectparticipantcount'] : '') +'</div>'
                                                + '</div>'
                                                + '<div class="fissue align-self-center ml-3"> <button type="button" class="btn font-weight-bold finishadd" onclick="finishByDescription_<?php echo $this->uniqId; ?>(this)"><i class="fa fa-gavel"></i></button></div>';


                                $element.closest('li').attr('data-row', dataSub.datacompress.replace(/&quot;/g, '"'));
                                $element.closest('li').empty().append($html).promise().done(function () {
                                    Core.unblockUI('#' + $selectorMainId);
                                    $element.closest('li').css({"border": "0px solid #f37736"}).animate({
                                        "borderWidth": "4px",
                                        "borderColor": "#f37736"
                                    },500);

                                    senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});
                                });
                            }
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
        }

        function dialogConfirm_<?php echo $this->uniqId ?> (callbackFunction, $html, data, $dialogName, $element) {

            var $dialogConfirm = 'dialog-confirm-<?php echo $this->uniqId ?>';
            if (!$("#" + $dialogConfirm).length) {
                $('<div id="' + $dialogConfirm + '"></div>').appendTo('body');
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
                        window[callbackFunction](data, $dialogName, $dialogConfirm, $element);
                    }},
                    {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');

        }

        function changeIssue<?php echo $this->uniqId; ?>(data, $dialogName, $dialogConfirm, $element) {
            $.ajax({
                type: 'post',
                url: 'conference/changeIssue', 
                data: {
                    id: data.id,
                    dataRow: data
                },
                dataType: 'json',
                async: false, 
                beforeSend: function () {
                    Core.blockUI({
                        animate: true,
                        target: '.government_<?php echo $this->uniqId ?> .conferencing-issue-list'
                    });
                },
                success: function (response) {
                    if (typeof response.status !== 'undefined' && response.status === 'success') {
                        PNotify.removeAll();
                        new PNotify({
                            title: response.status,
                            text: response.text,
                            type: response.status,
                            sticker: false
                        });

                        var params = [];
                        var counter = 1;

                        var mainTab1 = $('#highlighted-justified-tab1_<?php echo $this->uniqId ?> .conferencing-issue-list');
                        var mainTab2 = $('#highlighted-justified-tab2_<?php echo $this->uniqId ?> .conferencing-issue-list');

                        if (typeof mainTab2.find('li[data-id="'+ data['id'] +'"]') !== 'undefined' && mainTab2.find('li[data-id="'+ data['id'] +'"]').length > 0) {
                            mainTab1.find(".isitem").each(function(index, row) {

                                var $dataRow = JSON.parse($(row).attr('data-row'));
                                $dataRow['ordernum'] = counter;
                                params.push($dataRow);
                                counter++;

                            });

                            data['ordernum'] = counter;
                            params.push(data);
                            counter++;

                            mainTab2.find(".isitem").each(function(index, row) {
                                var $dataRow = JSON.parse($(row).attr('data-row'));
                                if ($dataRow['id'] !== data['id']) {
                                    $dataRow['ordernum'] = counter;
                                    params.push($dataRow);
                                    counter++;
                                }
                            });
                        }

                        if (typeof mainTab1.find('li[data-id="'+ data['id'] +'"]') !== 'undefined' && mainTab1.find('li[data-id="'+ data['id'] +'"]').length > 0) {

                            mainTab1.find(".isitem").each(function(index, row) {
                                var $dataRow = JSON.parse($(row).attr('data-row'));
                                if ($dataRow['id'] !== data['id']) {
                                    $dataRow['ordernum'] = counter;
                                    params.push($dataRow);
                                    counter++;
                                }
                            });

                            data['ordernum'] = counter;
                            params.push(data);
                            counter++;

                            mainTab2.find(".isitem").each(function(index, row) {
                                var $dataRow = JSON.parse($(row).attr('data-row'));
                                $dataRow['ordernum'] = counter;
                                params.push($dataRow);
                                counter++;
                            });
                        }
                        Core.unblockUI('.government_<?php echo $this->uniqId ?> .conferencing-issue-list');
                        saveConferenceOrder_<?php echo $this->uniqId ?>(params, 'reload_<?php echo $this->uniqId ?>');

                        senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});

                    } else {
                        PNotify.removeAll();
                        new PNotify({
                            title: response.status,
                            text: response.text,
                            type: response.status,
                            sticker: false
                        });
                    }

                    $("#" + $dialogConfirm).dialog('close');
                },
                error: function () {
                    Core.unblockUI('.government_<?php echo $this->uniqId ?> .conferencing-issue-list');
                }
            });
        }

        function saveConferenceOrder_<?php echo $this->uniqId ?>(params, callbackFunction, $thisElement) {
            
            $.ajax({
                type: 'post',
                url: 'conference/saveConferenceOrderNumber', 
                data: {
                    id: '<?php echo $this->selectedRow['id'] ?>',
                    params: params
                },
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({animate: true});
                },
                success: function (data) {
                    if (typeof callbackFunction !== 'undefined') {
                        window[callbackFunction](undefined, $thisElement);
                    }

                    try {
                        senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});
                    } catch (e) {
                        console.log(e);
                    }

                    Core.unblockUI();
                },
                error: function () {
                    Core.unblockUI();
                }
            });
        }

        function saveConferenceMember_<?php echo $this->uniqId ?>(params) {
            var ticket = true;;
            $.each(params, function (index, row) {
                if (row) {
                    ticket = false;
                }
            });
            
            if (ticket) {
                console.log('empty data');
                return;
            }
            
            $.ajax({
                type: 'post',
                url: 'conference/saveConferenceMember', 
                data: {
                    id: $('.government_<?php echo $this->uniqId ?> #other-member-list-<?php echo $this->uniqId ?>').attr('data-subject-id'),
                    params: params
                },
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({animate: true});
                },
                success: function (data) {
                    senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});
                    Core.unblockUI();
                },
                error: function () {
                    Core.unblockUI();
                }
            });
        }

        function reload_<?php echo $this->uniqId ?>(dataType, $thisElement) {
            if (!$('body').find('.government_<?php echo $this->uniqId ?>').length) {
                return false;
            }
            
            $.ajax({
                type: 'post',
                url: 'conference/reloadConferenceIssue', 
                data: {
                    id: '<?php echo $this->selectedRow['id'] ?>',
                    uniqId: '<?php echo $this->uniqId ?>',
                    role: <?php echo issetParamZero($this->memberRole['readonly']); ?>
                },
                dataType: 'json',
               
                success: function (data) {
                    var mainTab1 = $('#highlighted-justified-tab1_<?php echo $this->uniqId ?> .conferencing-issue-list');
                    var mainTab2 = $('#highlighted-justified-tab2_<?php echo $this->uniqId ?> .conferencing-issue-list');

                    mainTab1.empty().append(data.xHtml).promise().done(function () {
                        mainTab2.empty().append(data.tHtml).promise().done(function () {
                            if (typeof dataType !== 'undefined' && dataType == 'reload') {
                                var params = [];
                                var counter = 1;
                                $('.government_<?php echo $this->uniqId ?> .conferencing-issue-list').find(".isitem").each(function(r, v) {
                                    var $this = $(v);
                                    $this.attr("data-ordernum", counter);
                                    $this.find('.number').empty().append(counter+'.');
                                    var $dataRow = JSON.parse($this.attr('data-row'));
                                    $dataRow['ordernum'] = counter;
                                    params.push($dataRow);
                                    counter++;
                                });
                                
                                saveConferenceOrder_<?php echo $this->uniqId ?>(params, 'reload_<?php echo $this->uniqId ?>', $thisElement);
                            }
                        });
                        
                        if (typeof $thisElement !== 'undefined') {
                            $('#' + $thisElement).trigger('click');
                        }
                    });

                },
                error: function () {
                    Core.unblockUI('.government_<?php echo $this->uniqId ?> .conferencing-issue-list');
                }
            });
        }

        function cancelIssue_<?php echo $this->uniqId; ?>(data, $dialogName, $dialogConfirm, $element) {
            $.ajax({
                type: 'post',
                url: 'conference/cancelIssue', 
                data: {
                    id: data.id,
                    dataRow: data
                },
                dataType: 'json',
                async: false, 
                beforeSend: function () {
                    Core.blockUI({
                        animate: true,
                        target: '.government_<?php echo $this->uniqId ?> .conferencing-issue-list'
                    });
                },
                success: function (response) {
                    if (typeof response.status !== 'undefined' && response.status === 'success') {
                        PNotify.removeAll();
                        new PNotify({
                            title: response.status,
                            text: response.text,
                            type: response.status,
                            sticker: false
                        });
                        
                        senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});
                        reload_<?php echo $this->uniqId ?>();
                    } else {
                        PNotify.removeAll();
                        new PNotify({
                            title: response.status,
                            text: response.text,
                            type: response.status,
                            sticker: false
                        });
                    }

                    $("#" + $dialogConfirm).dialog('close');
                },
                error: function () {
                    Core.unblockUI('.government_<?php echo $this->uniqId ?> .conferencing-issue-list');
                }
            });
        }
        
        function saveProcessTimer_<?php echo $this->uniqId ?>(mainHour, processHour, type) {
            <?php if (defined('CONFIG_IS_NO_RELOAD') && CONFIG_IS_NO_RELOAD) { ?>
                console.log('CONFIG_IS_NO_RELOAD');
                return false;
            <?php } ?>
            
            if ('<?php echo issetParamZero($this->memberRole['readonly']); ?>' === '0') {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: 'conference/setProcessingTime',
                    data: {
                        bookId: '<?php echo $this->selectedRow['id'] ?>', 
                        saveTime: mainHour, 
                        time: processHour, 
                        type: type
                    },
                    success: function (data) {
                        if (typeof data.error !== 'undefined') {
                            
                            if ($('div.gov_issui_<?php echo $this->uniqId ?>').find('li.issue-start').length > 0) {
                                var $selecterStop = $('div.gov_issui_<?php echo $this->uniqId ?>').find('li.issue-start');
                                $.each($selecterStop, function ($index, row) {
                                    var $row = $(row);
                                    $row.removeClass('issue-start');
                                    $row.addClass('issue-stop').append('<button type="button" class="btn font-weight-bold finishadd" onclick="finishByDescription_<?php echo $this->uniqId; ?>(this, ' + $row.attr('data-id') +')"><i class="fa fa-gavel"></i></button>');
                                    
                                    issueStartToStop_<?php echo $this->uniqId ?>.push($row.attr('data-id'));
                                });
                            }
                            
                            Core.blockUI({
                                message: 'Сүлжээ салсан, Сүлжээгээ шалгана уу?',
                                boxed: true
                            });
                        } else {
                        
                            if (issueStartToStop_<?php echo $this->uniqId ?>) {
                                $.each(issueStartToStop_<?php echo $this->uniqId ?>, function ($index, $rowId) {
                                    dieIssue<?php echo $this->uniqId; ?>($('div.gov_issui_<?php echo $this->uniqId ?>').find('li[data-id="'+ $rowId +'"]'), $rowId);
                                });
                                
                                issueStartToStop_<?php echo $this->uniqId ?> = [];
                            }
                            Core.unblockUI();
                        }
                    }
                 });
            }
        }

        function processingTimer_<?php echo $this->uniqId ?>(duration, display, type) {
            var timer = parseInt(duration), 
                hours = 0, 
                minutes = 0, 
                seconds = 0;

            setInterval(function () {
                if ($('.government_<?php echo $this->uniqId ?>').length > 0 && typeof stopMainTimer_<?php echo $this->uniqId; ?> !== 'undefined' && stopMainTimer_<?php echo $this->uniqId; ?> === 'start') {
                    hours = parseInt(timer / (60*60), 10);
                    minutes = parseInt((timer-(hours * 60*60)) / (60), 10);
                    var temp = parseInt((timer-(hours * 60*60)-(minutes*60*60*60)) % 60, 10);
                    seconds = (temp < 0) ? 60 - (temp *-1) : temp;

                    hours = hours < 10 ? "0" + hours : hours;
                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;

                    var mainHour = hours + ":" + minutes + ":" + seconds;
                    var processHour = hours + ' цаг ' + minutes + ' минут ' + seconds + ' секунд';

                    display.html(mainHour);
                    timer++;

                    if (typeof type !== 'undefined') {
                        switch (type) {
                            case 'start':
                                $('.government_<?php echo $this->uniqId ?>').find('input[name="durationTime"]').val(mainHour);
                                break;

                            case 'pause':
                                $('.government_<?php echo $this->uniqId ?>').find('input[name="breakTime"]').val(mainHour);
                                break;

                        }
                    }

                    saveProcessTimer_<?php echo $this->uniqId ?>(mainHour, processHour, type);
                }
            }, 1000);
        }

        function playProcessingTimer_<?php echo $this->uniqId ?>(duration, display, type) {
            var timer = parseInt(duration), 
                hours = 0, 
                minutes = 0, 
                seconds = 0;

            setInterval(function () {
                if ($('.government_<?php echo $this->uniqId ?>').length > 0 && typeof stopMainTimer_<?php echo $this->uniqId; ?> !== 'undefined' && stopMainTimer_<?php echo $this->uniqId; ?> === 'play') {
                    hours = parseInt(timer / (60*60), 10);
                    minutes = parseInt((timer-(hours * 60*60)) / (60), 10);
                    var temp = parseInt((timer-(hours * 60*60)-(minutes*60*60*60)) % 60, 10);
                    seconds = (temp < 0) ? 60 - (temp *-1) : temp;

                    hours = hours < 10 ? "0" + hours : hours;
                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;

                    var mainHour = hours + ":" + minutes + ":" + seconds;
                    var processHour = hours + ' цаг ' + minutes + ' минут ' + seconds + ' секунд';

                    display.html(mainHour);
                    timer++;

                    if (typeof type !== 'undefined') {
                        switch (type) {
                            case 'start':
                                $('.government_<?php echo $this->uniqId ?>').find('input[name="durationTime"]').val(mainHour);
                                break;

                            case 'pause':
                                $('.government_<?php echo $this->uniqId ?>').find('input[name="breakTime"]').val(mainHour);
                                break;

                        }
                    }

                    saveProcessTimer_<?php echo $this->uniqId ?>(mainHour, processHour, type);
                }
            }, 1000);
        }

        function pauseProcessingTimer_<?php echo $this->uniqId ?>(duration, display, type) {
            var timer = parseInt(duration), 
                hours = 0, 
                minutes = 0, 
                seconds = 0;

            setInterval(function () {
                if ($('.government_<?php echo $this->uniqId ?>').length > 0 && typeof stopMainTimer_<?php echo $this->uniqId; ?> !== 'undefined' && stopMainTimer_<?php echo $this->uniqId; ?> === 'pause') {
                    hours = parseInt(timer / (60*60), 10);
                    minutes = parseInt((timer-(hours * 60*60)) / (60), 10);
                    var temp = parseInt((timer-(hours * 60*60)-(minutes*60*60*60)) % 60, 10);
                    seconds = (temp < 0) ? 60 - (temp *-1) : temp;

                    hours = hours < 10 ? "0" + hours : hours;
                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;

                    var mainHour = hours + ":" + minutes + ":" + seconds;
                    var processHour = hours + ' цаг ' + minutes + ' минут ' + seconds + ' секунд';

                    display.html(mainHour);
                    timer++;

                    if (typeof type !== 'undefined') {
                        switch (type) {
                            case 'start':
                                $('.government_<?php echo $this->uniqId ?>').find('input[name="durationTime"]').val(mainHour);
                                break;

                            case 'pause':
                                $('.government_<?php echo $this->uniqId ?>').find('input[name="breakTime"]').val(mainHour);
                                break;

                        }
                    }

                    saveProcessTimer_<?php echo $this->uniqId ?>(mainHour, processHour, type);
                }
            }, 1000);
        }
        
        function deleteIssue_<?php echo $this->uniqId; ?>(data, $dialogName, $dialogConfirm, $element) {
            $.ajax({
                type: 'post',
                url: 'conference/deleteIssue', 
                data: {
                    id: data.id,
                    dataRow: data
                },
                dataType: 'json',
                async: false, 
                beforeSend: function () {
                    Core.blockUI({
                        animate: true,
                        target: '.government_<?php echo $this->uniqId ?> .conferencing-issue-list'
                    });
                },
                success: function (response) {
                    if (typeof response.status !== 'undefined' && response.status === 'success') {
                        $element.remove();
                        PNotify.removeAll();
                        new PNotify({
                            title: response.status,
                            text: response.text,
                            type: response.status,
                            sticker: false
                        });

                        var params = [];
                        var counter = 1;
                        $('.government_<?php echo $this->uniqId ?> .conferencing-issue-list').find(".isitem").each(function(r, v) {
                            var $this = $(v);
                            $this.attr("data-ordernum", counter);
                            $this.find('.number').empty().append(counter+'.');
                            var $dataRow = JSON.parse($this.attr('data-row'));
                            $dataRow['ordernum'] = counter;
                            params.push($dataRow);
                            counter++;
                        });

                        senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});
                        saveConferenceOrder_<?php echo $this->uniqId ?>(params, 'reload_<?php echo $this->uniqId ?>');
                    } else {
                        PNotify.removeAll();
                        new PNotify({
                            title: response.status,
                            text: response.text,
                            type: response.status,
                            sticker: false
                        });
                        reload_<?php echo $this->uniqId ?>();
                    }

                    $("#" + $dialogConfirm).dialog('close');
                },
                error: function () {
                    Core.unblockUI('.government_<?php echo $this->uniqId ?> .conferencing-issue-list');
                }
            });
        }
        
        function editProcess_<?php echo $this->uniqId ?> ($dataRow, $this) {
            var processId = '1566876253574';
            var $dialogName = 'dialog-businessprocess-' + processId;
            if (!$('#' + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);

            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: processId, 
                    dmMetaDataId: '1563516519871',
                    isDialog: true, 
                    isSystemMeta: false, 
                    oneSelectedRow: $dataRow
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
                            if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                                $processForm.validate({ 
                                    ignore: '', 
                                    highlight: function(element) {
                                        $(element).addClass('error');
                                        $(element).parent().addClass('error');
                                        if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                            $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                                var tabId = $(tab).attr('id');
                                                $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                            });
                                        }
                                    },
                                    unhighlight: function(element) {
                                        $(element).removeClass('error');
                                        $(element).parent().removeClass('error');
                                    },
                                    errorPlacement: function(){} 
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
                                                $dialog.dialog('close');
                                                senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});
                                            } 
                                            
                                            reload_<?php echo $this->uniqId ?>('reload', $this.attr('id'));
                                            
                                            Core.unblockUI();
                                        },
                                        error: function () {
                                            reload_<?php echo $this->uniqId ?>('reload');
                                            alert("Error");
                                        }
                                    });
                                }
                            }    

                        }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                            reload_<?php echo $this->uniqId ?>('reload', $this.attr('id'));
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

                    setTimeout(function() {
                        $dialog.dialog('open');
                        Core.unblockUI();
                    }, 1);
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initBPAjax($dialog);
            });
        }

        function editProcessList_<?php echo $this->uniqId ?> ($dataRow, $this) {
            var processId = '1572546891507';
            var $dialogName = 'dialog-businessprocess-' + processId;
            if (!$('#' + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);

            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: processId, 
                    dmMetaDataId: '1561532239568490',
                    isDialog: true, 
                    isSystemMeta: false, 
                    oneSelectedRow: $dataRow
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
                            if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                                $processForm.validate({ 
                                    ignore: '', 
                                    highlight: function(element) {
                                        $(element).addClass('error');
                                        $(element).parent().addClass('error');
                                        if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                            $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                                var tabId = $(tab).attr('id');
                                                $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                            });
                                        }
                                    },
                                    unhighlight: function(element) {
                                        $(element).removeClass('error');
                                        $(element).parent().removeClass('error');
                                    },
                                    errorPlacement: function(){} 
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
                                                $dialog.dialog('close');
                                                senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});
                                            } 
                                            
                                            reloadlist_<?php echo $this->uniqId ?>(responseData.resultData.subjectid);
                                            Core.unblockUI();
                                        },
                                        error: function () {
                                            reloadlist_<?php echo $this->uniqId ?>(responseData.resultData.subjectid);
                                            alert("Error");
                                        }
                                    });
                                }
                            }    

                        }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                            reloadlist_<?php echo $this->uniqId ?>(responseData.resultData.subjectid);
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

                    setTimeout(function() {
                        $dialog.dialog('open');
                        Core.unblockUI();
                    }, 1);
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initBPAjax($dialog);
            });
        }
        
        function deleteOthermember_<?php echo $this->uniqId ?> ($dataRow, $this) {
            dialogConfirm_<?php echo $this->uniqId ?> ('deleteOtherMemberConfirmed_<?php echo $this->uniqId ?>', 'Устгахдаа итгэлтэй байна уу?', $dataRow, undefined, $this);
        }
        
        function deleteOtherMemberConfirmed_<?php echo $this->uniqId ?> (data, $dialogName, $dialogConfirm, $element) {
            $.ajax({
                type: 'post',
                url: 'conference/deleteOtherMemberConfirmed', 
                data: {
                    id: data.id,
                    dataRow: data
                },
                dataType: 'json',
                async: false, 
                beforeSend: function () {
                    Core.blockUI({
                        animate: true,
                        target: '#protocol-list-<?php echo $this->uniqId ?>'
                    });
                },
                success: function (response) {
                    if (typeof response.status !== 'undefined' && response.status === 'success') {
                        $element.remove();
                        PNotify.removeAll();
                        new PNotify({
                            title: response.status,
                            text: response.text,
                            type: response.status,
                            sticker: false
                        });

                        $('.government_<?php echo $this->uniqId ?> li.c-issue-list:eq('+  $('div[class="gov_issui_<?php echo $this->uniqId ?>"]').find('li.active').index() +')').trigger('click');
                    } else {
                        PNotify.removeAll();
                        new PNotify({
                            title: response.status,
                            text: response.text,
                            type: response.status,
                            sticker: false
                        });
                    }

                    senderWebsocket({type: 'refresh_conference', Html: '1', postId: '<?php echo $this->selectedRow['id'] ?>'});
                    Core.unblockUI('#protocol-list-<?php echo $this->uniqId ?>');
                    $("#" + $dialogConfirm).dialog('close');
                },
                error: function () {
                    Core.unblockUI('#protocol-list-<?php echo $this->uniqId ?>');
                }
            });
        }
        
        function reloadlist_<?php echo $this->uniqId ?>(subid) {
            console.log(subid);
          
            $('.government_<?php echo $this->uniqId ?> .conferencing-issue-list').find(".isitem").each(function(r, v) {
                var $this = $(v);
                
                if($this.attr("data-id") == subid){
                    
                    $(this).trigger('click');
                }
            });
        }
        
        $(function() {
            var row = <?php echo $this->rowJson; ?>;
        
            $.ajax({
                type: 'post',
                url: 'mdobject/getWorkflowNextStatus',
                data: {metaDataId: '<?php echo $this->metaDataId ?>', dataRow: row},
                dataType: "json",
                beforeSend: function() {
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function(response) {
                    if (response.status === 'success') {
                        if (response.datastatus && response.data) {
                            var rowId = '';
                            if (typeof row.id !== 'undefined') {
                                rowId = row.id;
                            }

                            $.each(response.data, function (i, v) {
                                var advancedCriteria = '';
                                if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                    advancedCriteria = ' data-advanced-criteria="' + v.advancedCriteria.replace(/\"/g, '') + '"';
                                }
                                
                                if (typeof v.wfmusedescriptionwindow != 'undefined' && v.wfmusedescriptionwindow == '0' && typeof v.wfmuseprocesswindow != 'undefined' && v.wfmuseprocesswindow == '0') {
                                    $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.processname +'</a>'); 
                                } else {
                                    if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {
                                        if (v.wfmisneedsign == '1') {
                                            $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.processname +' <i class="fa fa-key"></i></a>'); 
                                        } else if (v.wfmisneedsign == '2') {
                                            $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.processname +' <i class="fa fa-key"></i></a>'); 
                                        } else {
                                            $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm ml5" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'">'+ v.processname +'</a>'); 
                                        }
                                    } else if (v.wfmstatusprocessid != '' || v.wfmstatusprocessid != 'null' || v.wfmstatusprocessid != null) {
                                        var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : ''; 
                                        if (v.wfmisneedsign == '1') {
                                            $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="transferProcessAction(\'signProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.processname+' <i class="fa fa-key"></i></a>');
                                        } else if (v.wfmisneedsign == '2') {
                                            $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="transferProcessAction(\'hardSignProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');">'+v.processname+' <i class="fa fa-key"></i></a>');
                                        } else {
                                            $('.workflow-buttons-<?php echo $this->id; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-circle btn-sm" style="background-color: '+v.wfmstatuscolor+'; color: #fff;" onclick="transferProcessAction(\'\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\', undefined, undefined, undefined, undefined, undefined, undefined, \'callParliamentV2_<?php echo $this->uniqId ?>\');">'+v.processname+'</a>');
                                        }
                                    }
                                }
                            });
                        } 
                    } else {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Error',
                            text: response.message,
                            type: response.status,
                            sticker: false
                        });
                    }
                    Core.unblockUI();
                },
                error: function() {
                    alert("Error");
                }
            });
        });
        
        function reloadWfmStatus(){
            $('.workflow-buttons-<?php echo $this->id; ?>').empty();
        }
        
        function callParliamentV2_<?php echo $this->uniqId ?>() {
            var row = <?php echo $this->rowJson ?>;
            $('.workflow-buttons-<?php echo $this->id; ?>').hide();
            /* callParliamentV2(this, undefined, 1559809605021332, row, undefined); */
        }
    }
    
</script>