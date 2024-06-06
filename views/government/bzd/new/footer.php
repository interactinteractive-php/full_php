</div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            totalProtocol<?php echo $this->uniqId ?>(<?php echo $this->id ?>, <?php echo $this->subjectId ?>);
        });

        var intervalProtocal = null;
        
        function totalProtocol<?php echo $this->uniqId ?>(id,subjectId,duration, display, type) {
            intervalProtocal = setInterval(() => {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: 'conference/reviewTotalData',
                    data:{
                            id:id,
                            selectedId:subjectId,
                        },
                    success: function (data) {
                        let $approvedparticipant = data.data.attendance.approvedparticipant ? data.data.attendance.approvedparticipant : '0/0';
                        let $approvedpercent = data.data.attendance.approvedpercent ? data.data.attendance.approvedpercent : '0%';
                        let $declinedparticipant = data.data.attendance.declinedparticipant ? data.data.attendance.declinedparticipant : '0/0';
                        let $declinedpercent = data.data.attendance.declinedpercent ? data.data.attendance.declinedpercent : '0%';
                        let $manyTotal = data.data.attendance.attendance ? data.data.attendance.attendance : '00';
                        let $total = data.data.attendance.total ? data.data.attendance.total : '00';
                        let $finish = data.data.timer.endtime ? data.data.timer.endtime : '';
                        let $time = data.time ? data.time : '';
                        let $minute = data.minute ? data.minute : '';
                        let $second = data.second ? data.second : '';
                       
                        if ($finish) {
                            $("#dialog-popupItem-"+id).modal("hide");
                            window.location.href = "/contentui/previewatten/" + subjectId;
                        }

                        var $html = "";
                        var $btml = "";
                        var $dhtml = "";
                        var $rhtml = "";
                        var $thtml = "";
                        $('.governmentReview_<?php echo $this->uniqId ?>').find('span[data-name="reviewMinit"]').text($minute);
                        $('.governmentReview_<?php echo $this->uniqId ?>').find('span[data-name="reviewSeconds"]').text($second);
                        $dhtml +='<span class="p-1 mr-2 text-secondary" style="font-size: 28px">' + $approvedparticipant + ' ' + $approvedpercent +' </span>';
                        $rhtml +='<span class="p-1 mr-2 text-secondary" style="font-size: 28px">' + $declinedparticipant + ' ' + $declinedpercent +' </span>';
                        $thtml +='<span class="px-2 py-1">' + $manyTotal + '</span> / <span class="px-2 py-1">' + $total + '</span>';

                        $.each(data.data.approved, function (index, row) {
                            $html +='<span style="font-size: 28px;" class="text-primary">' +row["fullname"] +'</span>';
                        });

                        $('#approved').empty().append($html).promise().done(function () {
                        });

                        $.each(data.data.declined, function (index, row) {
                            $btml +='<span style="font-size: 28px;" class="text-primary">' +row["fullname"] +'</span>';
                        });

                        $('#declined').empty().append($btml).promise().done(function () {
                        });

                        $('.approvedparticipant').empty().append($dhtml).promise().done(function () {
                        });

                        $('.declinedpercent').empty().append($rhtml).promise().done(function () {
                        });

                        $('.reviewTotal').empty().append($thtml).promise().done(function () {
                        });
                    }
                }); 
            }, 1000);
        }
    </script>
</body>
</html>