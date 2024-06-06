<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="card light shadow">
    <div class="row">
        <div class="col-sm-4">
            <div id="root1">
                <div id="ll" style="width:100%">
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div id="root2">
                <div id="ll" style="width:100%">
                </div>
            </div>

        </div>
    </div> <div class="col-sm-12">
        <div id="root3">
            <div id="ll" style="width:100%">
            </div>
        </div>
    </div>
    <div id="root4">
        <div id="ll">
        </div>
    </div>
</div>

<!--<iframe src="https://202.131.226.85:8442/api/trusted">
    
<form action="https://202.131.226.85:8442/api/trusted" method="POST" accept-charset="UTF-8"
      enctype="application/xml" autocomplete="off" novalidate>
    <input type="submit" value="Submit">
</form>
</iframe> -->

<form action="https://202.131.226.85:8442/api/trusted" method="POST" accept-charset="UTF-8"
      enctype="application/xml" autocomplete="off" novalidate>
    <input type="submit" value="Submit">
</form>


<script>

    $.ajax({
        type: "POST",
        url: "Rmreport/tdbmTest",
        dataType: "json",
        data: "",
        success: function (res) {
            console.log(res);
        //    alert("XML: it works!");
        },
        error: function (res) {
            console.log(res);
       //     alert("XML: not working! " + res.statusText);
        }
    });



//    $.ajax({
//        type: 'post',
//        url: 'https://202.131.226.85:8442/api/trusted',
//        dataType: "xml",
//        data: {
//        },
//        beforeSend: function () {
//            Core.blockUI({
//                target: "body",
//                animate: true
//            });
//        },
//        success: function (data) {
//            console.log(data);
//        },
//        error: function (msg) {
//            Core.unblockUI("body");
//            console.log(msg);
//        }
//    }).done(function () {
//        Core.initAjax();
//    });
//        
    //console.log($('#root1 > #ll > div > #aaa').serialize());

//        $("#root1 > #ll").html("asdfasdfasdf1");
//        $("#root4 > #ll").html("asdfasdfasdf4");
//        $("#root2 > #ll").html("asdfasdfasdf2");
//        $("#root3 > #ll").html("asdfasdfasdf3");
    // });
//    $(window).resize(function ()
//    {
//        $().highcharts.setSize(
//                $(document).width(),
//                $(document).height() / 2,
//                false
//                );
//    });
</script>