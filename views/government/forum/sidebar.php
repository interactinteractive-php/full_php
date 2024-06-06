<div class="search-big p-0">
    <form action="/projects" class="" method="get">                    
        <div class="row search-options "></div>
        <div class=" p-3">
            <p>Системийн шинэ хэрэглэгчээр бүртгүүлж, хэлэлцүүлгийн төсөлд оролцох дараалал. </p>
            <p>Step1. Шинэ хэрэглэгчээр бүртгүүлэх линк -&gt;&gt; https://legalinfo.interactive.mn/legal/lispersonregister</p>
            <p>Step2. Шинэ хэрэглэгчийн форм бөглөх. </p>
            <p>Step3. Системээс таны оруулсан имэйл хаягаар баталгаажуулах линк илгээгдэнэ. Имэйл хаягаараа тухайн линк дээр дарж хэрэглэгчийн бүртгэлээ баталгаажуулна. </p>
            <p>Step4. Өөрийн үүсгэсэн хэрэглэгчийн эрхээр систем руу нэвтэрнэ. -&gt;&gt; https://legalinfo.interactive.mn/legal/login </p>
            <p>/Анхаарах зүйл: Та өөрийн үүсгэсэн хэрэглэгчийн эрхээр нэвтрэхийн өмнө систем руу админ эрхээр нэвтэрсэн байсан бол Logout хийж гарсан байна. /</p>
            <p>Step5. Хэлэлцүүлгийн жагсаалтнаас өөрийн сонирхсон хэлэлцүүлэг дээр дарж дэлгэрэнгүй хуудаснаас хэлэлцүүлэгт санал өгч, сэтгэгдэл бичнэ. </p>
            <p>Step6. Өөрийн сонирхсон хэлэлцүүлгийг дагана. </p>
            <p>Step7. Таны дагасан хэлэлцүүлгийн төлөв өөрчлөгдөх үед таны бүртгэлтэй имэйл хаягаар мэдээлэл очно. </p>
        </div>
    </form>            
</div>

<script type="text/javascript">
    function departmentToggleClass(element) {
        $(element).find('span').toggleClass('checked');
        $(element).find('a').toggleClass('active');
        //searchList();
    }

    $('#filterName').keypress(function (e) {
        var key = e.which;
        if (key === 13)
        {
<?php
if (isset($this->type)) {
    if ($this->type == 'issue') {
        ?>
                    searchIssue()
    <?php } else { ?>
                    searchSolution();
        <?php
    }
} else {
    ?>
                searchList();
<?php } ?>
        }
    });

    $('body').on('click', '.search-button', function () {
<?php
if (isset($this->type)) {
    if ($this->type == 'issue') {
        ?>
                searchIssue()
    <?php } else { ?>
                searchSolution();
        <?php
    }
} else {
    ?>
            searchList();
<?php } ?>
    });

    $("#filterType").change(function () {
        var filterType = $("#filterType").val();
<?php
if (isset($this->type)) {
    if ($this->type == 'issue') {
        ?>
                searchIssue()
    <?php } else { ?>
                searchSolution();
        <?php
    }
} else {
    ?>
            searchList();
<?php } ?>
    });

    function filterDepartment() {
        var element = $("ul .category").find("a.active");

        var cat = [];
        $.each(element, function (key, el) {
            var categoryId = $(el).data("categoryid");
            cat.push({category: categoryId});
        });

        return cat;
    }

    function searchList() {

        var filterName = $("#filterName").val();
        var filterType = $("#filterType").val();
        var filterCategory = filterDepartment();
        var offset = $("#offset").val();

        if (filterType === 'Сонгох') {
            filterType = '';
        }

        $.ajax({
            type: 'post',
            url: 'government/srcforum',
            data: {filterName: filterName, filterType: filterType, filterCategory: filterCategory, offset: offset},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    boxed: true,
                    message: 'Түр хүлээнэ үү'
                });
            },
            success: function (data) {
                var html = '';

                if (typeof data['result'] === 'undefined' || data['result'] == '') {
                    html += '<div class="card card-body"><p class="text-center text-grey mt5">Тохирох үр дүн олдсонгүй</p></div>';
                } else {
                    var $co = 1;
                    var off = parseInt(data['paging']['offset']);
                    $co = $co + ((off - 1) * 10);

                    $.each(data['result'], function (key, list) {
                        var voteHtml = '';
                        if (list.count == null) {
                            voteHtml = '<h1 class="poll-count"></h1>' +
                                    '<div class="d-none">' +
                                    '<i class="icon-star-full2 font-size-base text-warning-300"></i>' +
                                    '<i class="icon-star-full2 font-size-base text-warning-300"></i>' +
                                    '<i class="icon-star-full2 font-size-base text-warning-300"></i>' +
                                    '<i class="icon-star-full2 font-size-base text-warning-300"></i>' +
                                    '<i class="icon-star-full2 font-size-base text-warning-300"></i>' +
                                    '</div>' +
                                    '<div class="text-muted">Одоогоор саналгүй</div>';
                        } else {
                            voteHtml = '<h1 class="poll-count">' + list.count + '</h1>' +
                                    '<div class="d-none">' +
                                    '<i class="icon-star-full2 font-size-base text-warning-300"></i>' +
                                    '<i class="icon-star-full2 font-size-base text-warning-300"></i>' +
                                    '<i class="icon-star-full2 font-size-base text-warning-300"></i>' +
                                    '<i class="icon-star-full2 font-size-base text-warning-300"></i>' +
                                    '<i class="icon-star-full2 font-size-base text-warning-300"></i>' +
                                    '</div>' +
                                    '<div class="polldesc">саналтай</div>';
                        }

                        if (list.tuluw === 'Санал авч байна') {
                            html += '<div class="card card-body">' +
                                    '<div class="media align-items-center align-items-center text-center text-lg-left flex-column flex-lg-row">' +
                                    '<div class="date-before">' +
                                    '<h1>' + list.datediff + '</h1>' +
                                    '<div class="datedesc"> ' + list.datedesc + '</div>' +
                                    '</div>' +
                                    '<div class="media-body">' +
                                    '<a href="main/detail/' + list.id + '" class="poll-title">' + $co + '. ' + list.subjectname + '</a>' +
                                    '<div class="statusbtns">' +
                                    '<li class="list-inline-item"><a href="javascript:void(0);" class="text-muted">' + list.departmentname + '</a></li>' +
                                    '</div>' +
                                    '<div class="btn btn-sm btn-success status mr4">' + list.tuluw + '</div>' +
                                    '<div class="btn btn-sm btn-danger status day">' + list.remainedday + '</div>' +
                                    '</div>' +
                                    '<div class="pollcount">' +
                                    voteHtml +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                        } else if (list.tuluw === 'Санал авч дууссан') {
                            html += '<div class="card card-body closed">' +
                                    '<div class="media align-items-center align-items-center text-center text-lg-left flex-column flex-lg-row">' +
                                    '<div class="date-before">' +
                                    '<h1>' + list.datediff + '</h1>' +
                                    '<div class="datedesc"> ' + list.datedesc + '</div>' +
                                    '</div>' +
                                    '<div class="media-body">' +
                                    '<a href="main/detail/' + list.id + '" class="poll-title">' + $co + '. ' + list.subjectname + '</a>' +
                                    '<div class="statusbtns">' +
                                    '<li class="list-inline-item"><a href="javascript:void(0);" class="text-muted">' + list.departmentname + '</a></li>' +
                                    '</div>' +
                                    '<div class="btn status">' + list.tuluw + '</div>' +
                                    '</div>' +
                                    '<div class="pollcount">' +
                                    voteHtml +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                        }
                        $co++;
                    });
                }

                var total = (typeof data['paging'] === 'undefined') ? '0' : data['paging']['totalcount'];
                var offset = (typeof data['paging'] === 'undefined') ? '0' : data['paging']['offset'];
                var pagesize = (typeof data['paging'] === 'undefined') ? '0' : data['paging']['pagesize'];
                var pageNum = (typeof data['paging'] === 'undefined') ? 0 : Math.ceil(total / pagesize);
                console.log('total = ' + total);
                console.log('offset = ' + offset);
                console.log('pagesize = ' + pagesize);
                console.log('pageNum = ' + pageNum);

                var loopHtml = '';

                for (var i = 1; i <= pageNum; i++) {
                    const activeClass = (i == offset) ? "active" : "";
                    loopHtml += '<li class="page-item ' + activeClass + '" data-offset="' + i + '"><a href="javascript:;" class="page-link">' + i + '</a></li>';
                }

                const rdisabled = (offset - 1 == 0) ? "disabled" : "";
                const ldisabled = (parseInt(offset) + 1 > pageNum) ? "disabled" : "";

                html += '<div class="d-flex justify-content-center pt-2 mb-3">' +
                        '<ul class="pagination">' +
                        '<li class="page-item ' + rdisabled + '" data-offset="' + (offset - 1) + '"><a href="javascript:;" class="page-link"><i class="icon-arrow-left12"></i></a></li>' +
                        loopHtml +
                        '<li class="page-item ' + ldisabled + '" data-offset="' + (parseInt(offset) + 1) + '"><a href="javascript:;" class="page-link"><i class="icon-arrow-right13"></i></a></li>' +
                        '</ul>' +
                        '</div>' +
                        '<input type="hidden" id="offset">';

                $(".forum-content-<?php echo $this->uniqId ?>").empty().append(html);
                Core.unblockUI();
            }
        });
    }

    $('body').on('click', '.page-item', function () {
        $("#offset").val($(this).data('offset'));
<?php
if (isset($this->type)) {
    if ($this->type == 'issue') {
        ?>
                searchIssue()
    <?php } else { ?>
                searchSolution();
        <?php
    }
} else {
    ?>
            searchList();
<?php } ?>
    });

</script>