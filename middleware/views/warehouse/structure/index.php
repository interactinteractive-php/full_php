<div class="col-md-12" id="whIncome">
    <div class="card light shadow">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title">
                <i class="fa fa-pencil-square"></i> <?php echo $this->title; ?>
            </div>
            <div class="caption buttons ml10">
                
            </div>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body xs-form">
            <form class="form-horizontal" role="form" method="post" id="saveIncome-form">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="collapsible">
                                <legend>Ерөнхий мэдээлэл</legend>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group row fom-row">
                                            <?php echo Form::label(array('text' => 'Агуулах', 'for' => 'wareHouseId', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
                                            <div class="col-md-9">
                                                <?php
                                                echo Form::select(
                                                        array(
                                                            'name' => 'WAREHOUSE_ID',
                                                            'id' => 'WAREHOUSE_ID',
                                                            'class' => 'form-control select2 form-control-sm input-xxlarge',
                                                            'data' => $this->getActiveWareHouseList,
                                                            'op_value' => 'WAREHOUSE_ID',
                                                            'op_text' => 'WAREHOUSE_NAME',
                                                            'required' => 'required'
                                                        )
                                                );
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row fom-row">
                                            <?php echo Form::label(array('text' => 'Байршил', 'for' => 'LOCATION', 'class' => 'col-form-label col-md-3')); ?>
                                            <div class="col-md-9">
                                                <?php
                                                echo Form::select(
                                                        array(
                                                            'name' => 'LOCATION_ID',
                                                            'id' => 'LOCATION_ID',
                                                            'class' => 'form-control select2 form-control-sm input-xxlarge',
                                                            'disabled' => true
                                                        )
                                                );
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!--<div class="col-md-2">
                        <?php echo Form::button(array('class' => 'btn purple btn-block', 'value' => 'Гүйлгээнд холбох')); ?>
                        </div>-->
                    </div>
                    <div class="row mt10">
                        <div class="col-md-12">
                            <div id="_hotspotControl"></div>
                            <div id="_hotSpot">
                                <div id="_backSpace"></div>
                                <div id="_hotSpotContainer"></div>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="form-actions mt15 form-actions-btn">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type='text/javascript'>
    $(function(){
        $("#_backSpace").on("click", function(){
            if($("#_hotSpotContainer").find("#markerParentId").val()=='0' || $("#_hotSpotContainer").find("#markerParentId").val()=='null'){
                $("#_backSpace").html('');
                $('#LOCATION_ID option:selected').val('0');
                $('#WAREHOUSE_ID, #LOCATION_ID').trigger('change');
                
            }else{
                if($('#LOCATION_ID option:selected').val()!='0'){
                    $("#_backSpace").html('');
                    $('#LOCATION_ID').val($("#_hotSpotContainer").find("#markerParentId").val());
                    $('#LOCATION_ID').trigger('change');
                }
            }
        });
                
        
        $.contextMenu({
            selector: '._hs-marker-object', 
            callback: function(key, options) {
                if(key=='view'){locationInfoDialog(this);}
                if(key=='edit'){frmDialog(this);}
                if(key=='delete'){locationDelete(this);}
            },
            items: {
                "view": {name: "Мэдээлэл", icon: "eye"},
                "edit": {name: "Засах", icon: "edit"},
                "delete": {name: "Устгах", icon: "trash"}
            }
        });
        $('#WAREHOUSE_ID').change(function(){
            var selVal = "<option value='0' selected='selected'>- Байршил -</option>";
            $("#_hotspotControl").empty();
            $("#_hotSpotContainer").empty().css("width",0).css("height",0);
            $('#LOCATION_ID').empty().prop('disabled', true);
            $('#LOCATION_ID').append(selVal);
            $('#LOCATION_ID').trigger('change');
            $.post('mdwarehouse/whLocation', {WAREHOUSE_ID: $(this).val()}, function(res) {
                $("#_hotSpotContainer").empty();
                if(res!=null){
                    $('#LOCATION_ID').empty().prop('disabled', false);
                    $.each(res, function(index) {
                        //selVal += hotSpotInitTree(this, this.PARENT_ID, " ");
                        selVal += "<option value='" + this.LOCATION_ID + "'>" + this.LOCATION_CODE + "-" + this.LOCATION_NAME + "</option>";
                    });
                    $('#LOCATION_ID').append(selVal);
                    showImage($('#LOCATION_ID option:selected').val(), $('#WAREHOUSE_ID option:selected').val());
                }else{
                    $("#_hotSpotContainer").css("border","none");
                    $("#_hotSpotContainer").css("min-width",0);
                    $("#_hotSpotContainer").css("min-height",0);
                }
            }, 'json');
            if($(this).val()==''){
                $("#_hotSpotContainer").css("border","none");
                $("#_hotSpotContainer").css("min-width",0);
                $("#_hotSpotContainer").css("min-height",0);
            }
            console.log($(this).val());
        });
        $('select#LOCATION_ID').change(function(){
            if($('#LOCATION_ID option:selected').val()!='0'){
                $.post('mdwarehouse/getLastLocationId', {LOCATION_ID: $('#LOCATION_ID option:selected').val()}, function(res) {
                    if(res.PARENT_ID!='0'){
                        showImage(res.PARENT_ID, $('#WAREHOUSE_ID option:selected').val());
                    }else{
                        showImage($('#LOCATION_ID option:selected').val(), $('#WAREHOUSE_ID option:selected').val());
                    }
                }, 'json');
            }else{
                showImage($('#LOCATION_ID option:selected').val(), $('#WAREHOUSE_ID option:selected').val());
            }
        });
        
    });
    
    function showImage(locationId, wareHouseId){
        $.post('mdwarehouse/whLocationImage', {LOCATION_ID: locationId, WAREHOUSE_ID: wareHouseId}, function(res) {
            $("#_hotSpotContainer").width(0);
            $("#_hotSpotContainer").height(0);
            if (res != null) {
                $("#_hotspotControl").empty();
                $("#_hotSpotContainer").empty();
                $("._hs-marker-object").empty();

                var hotSpotCoordinates = function(elm) {
                    element = $(elm);
                    var width = $("#_hotSpotContainer").width();
                    var height = $("#_hotSpotContainer").height();
                    var top = element.position().top;
                    var left = element.position().left;
                    var topPrencent = (top * 100) / height;
                    var leftPrencent = (left * 100) / width;
                    element.css("left", leftPrencent + "%");
                    element.css("top", topPrencent + "%");
                    element.attr("data-old-y", element.attr("data-old-y"));
                    element.attr("data-old-x", element.attr("data-old-x"));
                    element.attr("data-y", topPrencent);
                    element.attr("data-x", leftPrencent);
                    //$('#results').text('width: ' + width + ' ' + 'height: ' + height + ' X: ' + left + ' ' + 'Y: ' + top + ' top%: ' + topPrencent + ' ' + 'left%: ' + leftPrencent);
                }
                if (res.Image.ATTACH != null) {
                    $("img#_hotSpotImage").remove();
                    $("#_hotSpotContainer").css("border","#ddd 1px solid");
                    $("#_hotSpotContainer").css("min-width",300);
                    $("#_hotSpotContainer").css("min-height",200);
                    if($("#LOCATION_ID option:selected").val()!='0'){$("#_backSpace").html('<a href="javascript:;" class="btn blue btn-xs backspace"><i class="fa fa-angle-left"></i> Буцах</a>');}
                    $("#_hotSpotContainer").append('<img src="' + res.Image.ATTACH + '" id="_hotSpotImage">');
                    $("#_hotSpotContainer").append('<input type="hidden" id="markerCounterId" value="0">');
                    $("#_hotSpotContainer").append('<input type="hidden" id="markerDefaultPosition" value="2">');
                    $("#_hotSpotContainer").append('<input type="hidden" id="markerParentId" value="' + res.Parent.PARENT_ID + '">');
                    
                    $("#_hotSpotContainer").find("img#_hotSpotImage").load(function(){
                        $("#_hotSpotContainer").width($("#_hotSpotContainer").find("img#_hotSpotImage").width());
                        $("#_hotSpotContainer").height($("#_hotSpotContainer").find("img#_hotSpotImage").height());
                    }).error(function(){
                        console.log('Image is not loaded!');
                    });
        
//                    setTimeout(function(){ 
//                        $("#_hotSpotContainer").width($("#_hotSpotContainer").find("img#_hotSpotImage").width());
//                        $("#_hotSpotContainer").height($("#_hotSpotContainer").find("img#_hotSpotImage").height());
//                    }, 50);
                    
                    
                    $("#_hotspotControl").append('\
                                                <div class="_hs-marker-object-creater _hs-marker-object-red addMarker" data-class="_hs-marker-object-red"></div>\n\
                                                <div class="_hs-marker-object-creater _hs-marker-object-blue addMarker" data-class="_hs-marker-object-blue"></div>\n\
                                                <div class="_hs-marker-object-creater _hs-marker-object-pink addMarker" data-class="_hs-marker-object-pink"></div>\n\
                                                <div class="_hs-marker-object-creater _hs-marker-object-green addMarker" data-class="_hs-marker-object-green"></div>\n\
                                                <div class="_hs-marker-object-creater _hs-marker-object-brown addMarker" data-class="_hs-marker-object-brown"></div>\n\
                                                <div class="_hs-marker-object-creater _hs-marker-object-white addMarker" data-class="_hs-marker-object-white"></div>\n\
                                                <div class="_hs-marker-object-creater _hs-marker-object-borderBlack-red addMarker" data-class="_hs-marker-object-borderBlack-red"></div>\n\
                                                <div class="_hs-marker-object-creater _hs-marker-object-borderBlack-blue addMarker" data-class="_hs-marker-object-borderBlack-blue"></div>\n\
                                                <div class="_hs-marker-object-creater _hs-marker-object-borderBlack-pink addMarker" data-class="_hs-marker-object-borderBlack-pink"></div>\n\
                                                <div class="_hs-marker-object-creater _hs-marker-object-borderBlack-green addMarker" data-class="_hs-marker-object-borderBlack-green"></div>\n\
                                                <div class="_hs-marker-object-creater _hs-marker-object-borderBlack-brown addMarker" data-class="_hs-marker-object-borderBlack-brown"></div>\n\
                                                <div class="_hs-marker-object-creater _hs-marker-object-borderBlack-white addMarker" data-class="_hs-marker-object-borderBlack-white"></div>\n\
                                                ');
                    $(".addMarker").on("click", function(){
                        var defaultTopPrecent = 2;
                        var defaultLeftPrecent = $("#markerDefaultPosition").val();
                        var tempPosition = parseInt(defaultLeftPrecent) + 5;
                        $("#markerDefaultPosition").val(tempPosition);
                        var _markerCounter = parseInt($("#markerCounterId").val()) + 1;
                        $("#markerCounterId").val(_markerCounter);
                        $("#_hotSpotContainer").append('<div class="_hs-marker-object ' + $(this).attr("data-class") + '" id="_markerId-' + _markerCounter + '" data-class="' + $(this).attr("data-class") + '" data-type="add" style="left: ' + defaultLeftPrecent + '%; top: ' + defaultTopPrecent + '%;" data-x="' + defaultLeftPrecent + '" data-y="' + defaultTopPrecent + '"  data-old-x="' + defaultLeftPrecent + '" data-old-y="' + defaultTopPrecent + '" data-parent="0"></div>');
                        $('._hs-marker-object').draggable({
                            containment: '#_hotSpotContainer',
                            start: function() {
                                hotSpotCoordinates(this);
                            },
                            stop: function() {
                                hotSpotCoordinates(this);//SAVE
                            }
                        });
                        $("._hs-marker-object").on("dblclick", function(){
                            frmDialog(this);
                        });
                    });
                }
                var _markerCounter = 0;
                $.each(res.Coordinate, function (key,value) {
                    _markerCounter++;
                    $("#_hotSpotContainer").append('<div class="_hs-marker-object ' + res.Coordinate[key].MARKER_NAME + '" id="_markerId-' + _markerCounter + '" data-class="' + res.Coordinate[key].MARKER_NAME + '" data-type="edit" data-warehouseid="' + res.Coordinate[key].WAREHOUSE_ID + '" data-locationid="' + res.Coordinate[key].LOCATION_ID + '" data-x="' + res.Coordinate[key].COORDINATE_X + '" data-y="' + res.Coordinate[key].COORDINATE_Y + '" data-old-x="' + res.Coordinate[key].COORDINATE_X + '" data-old-y="' + res.Coordinate[key].COORDINATE_Y + '" data-parent="' + res.Coordinate[key].IS_PARENT + '" style="left: ' + res.Coordinate[key].COORDINATE_X + '%; top: ' + res.Coordinate[key].COORDINATE_Y + '%;"></div>');
                    
                });
                $("#markerCounterId").val(_markerCounter);
                $('._hs-marker-object').draggable({
                    containment: '#_hotSpotContainer',
                    start: function() {
                        hotSpotCoordinates(this);
                    },
                    stop: function() {
                        hotSpotCoordinates(this);
                    }
                });
                $("._hs-marker-object").on("dblclick", function(){
                    if(parseInt($(this).attr("data-parent"))==1){
                        var selVal, locationId = $(this).attr("data-locationid");
                        $("#LOCATION_ID option").each(function() {
                            if(locationId==$(this).val()){
                                selVal += "<option value='" + $(this).val() + "' selected='selected'>" + $(this).html() + "</option>";
                            }else{
                                selVal += "<option value='" + $(this).val() + "'>" + $(this).html() + "</option>";
                            }
                        });
                        $('#LOCATION_ID').empty().prop('disabled', false);
                        $('#LOCATION_ID').append(selVal);
                        $('#LOCATION_ID').trigger('change');
                        showImage($(this).attr("data-locationid"), $(this).attr("data-warehouseid"));
                    }else{
                        locationInfoDialog(this);
                    }
                });
            }
        }, 'json');
        
        
    }
    function locationInfoDialog(elem){
        var locationId = $(elem).attr("data-locationid");
        var wareHouseId = $(elem).attr("data-warehouseid");
        if(locationId==undefined){locationId = $('#LOCATION_ID option:selected').val();}
        if(wareHouseId==undefined){wareHouseId = $('#WAREHOUSE_ID option:selected').val();}
        if(locationId==undefined){locationId = $('#LOCATION_ID option:selected').val();}
        
        var $dialogName = 'dialog-whLocation';
        if (!$("#" + $dialogName).length) {$('<div id="' + $dialogName + '"></div>').appendTo('body');}
        $.ajax({
            type: 'post',
            url: 'mdwarehouse/locationInfoDialog',
            data: {LOCATION_ID:locationId, WAREHOUSE_ID:wareHouseId}, 
            dataType: 'json',
            beforeSend:function(){
                Core.blockUI({
                    animate: true
                });
            },
            success:function(data){
                $("#"+$dialogName).empty().html(data.Html);  
                $("#"+$dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 700,
                    minWidth: 700,
                    height: "auto",
                    modal: true, 
                    position: {my:'top', at:'top+50'},
                    close:function(){
                        $("#"+$dialogName).empty().dialog('close');
                    }, 
                    buttons: [
                        {
                            text: data.close_btn, 
                            class: 'btn btn-sm blue-hoki',
                            click: function(){
                                var marker = $("#addWH-form").find("#MARKER_ID").val();
                                $("#" + marker).css("left", $("#addWH-form").find("#OLD_COORDINATE_X").val() + "%");
                                $("#" + marker).css("top", $("#addWH-form").find("#OLD_COORDINATE_Y").val() + "%");
                                $("#" + marker).attr("data-y", $("#addWH-form").find("#OLD_COORDINATE_Y").val());
                                $("#" + marker).attr("data-x", $("#addWH-form").find("#OLD_COORDINATE_X").val());
                                $("#"+$dialogName).dialog('close');
                            }
                        }
                    ]        
                });
                $("#"+$dialogName).dialog('open');
                $.unblockUI();
            },
            error:function(){
                alert("Error");
            }
        }).done(function(){
            Core.initAjax();
        });
    }
    function frmDialog(elem){
        var locationId = $(elem).attr("data-locationid");
        var wareHouseId = $(elem).attr("data-warehouseid");
        var isWareHouse = 'NO';
        if(locationId==undefined){locationId = $('#LOCATION_ID option:selected').val();}
        if(wareHouseId==undefined){wareHouseId = $('#WAREHOUSE_ID option:selected').val();}
        if(locationId==undefined){locationId = $('#LOCATION_ID option:selected').val();}
        if(wareHouseId!='null' && locationId!='0'){isWareHouse = 'YES';}
        if(locationId==''){locationId = 0;}
        var $dialogName = 'dialog-whLocation';
        if (!$("#" + $dialogName).length) {$('<div id="' + $dialogName + '"></div>').appendTo('body');}
        $.ajax({
            type: 'post',
            url: 'mdwarehouse/structureDialog',
            data: {COORDINATE_X: $(elem).attr("data-x"), COORDINATE_Y: $(elem).attr("data-y"), OLD_COORDINATE_X: $(elem).attr("data-old-x"), OLD_COORDINATE_Y: $(elem).attr("data-old-y"), LOCATION_ID:locationId, WAREHOUSE_ID:wareHouseId, QUERYTYPE:$(elem).attr("data-type"), ISWAREHOUSE:isWareHouse, MARKER_NAME:$(elem).attr("data-class"), MARKER_ID:$(elem).attr("id")}, 
            dataType: 'json',
            beforeSend:function(){
                Core.blockUI({
                    animate: true
                });
            },
            success:function(data){
                $("#"+$dialogName).empty().html(data.Html);  
                $("#"+$dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 700,
                    minWidth: 700,
                    height: "auto",
                    modal: true, 
                    position: {my:'top', at:'top+50'},
                    close:function(){
                        $("#"+$dialogName).empty().dialog('close');
                    }, 
                    buttons: [
                        {   
                            text: data.save_btn,
                            class: 'btn btn-sm green', 
                            click: function(){
                                $("#addWH-form").validate({ errorPlacement: function(){} });
                                if ($("#addWH-form").valid()) {
                                    //mdPriceSaleAddItemRename();
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdwarehouse/queryLocationPosition',
                                        data: $("#addWH-form").serialize(),
                                        dataType: "json",
                                        beforeSend: function(){
                                            Core.blockUI({
                                                message: plang.get('msg_saving_block'),
                                                boxed: true
                                            });
                                        },
                                        success: function(data) {
                                            $.unblockUI();
                                            if (data.status === 'success') {
                                                new PNotify({
                                                    title: 'Success',
                                                    text: data.message,
                                                    type: 'success',
                                                    sticker: false
                                                });
                                                $("#"+$dialogName).dialog('close');
                                                showImage($('#LOCATION_ID option:selected').val(), $('#WAREHOUSE_ID option:selected').val());
                                            } else {
                                                new PNotify({
                                                    title: 'Error',
                                                    text: data.message,
                                                    type: 'error',
                                                    sticker: false
                                                });
                                            }
                                        },
                                        error: function(){
                                            alert("Error");
                                        }
                                    });
                                }
                            }
                        },
                        {
                            text: data.close_btn, 
                            class: 'btn btn-sm blue-hoki',
                            click: function(){
                                var marker = $("#addWH-form").find("#MARKER_ID").val();
                                $("#" + marker).css("left", $("#addWH-form").find("#OLD_COORDINATE_X").val() + "%");
                                $("#" + marker).css("top", $("#addWH-form").find("#OLD_COORDINATE_Y").val() + "%");
                                $("#" + marker).attr("data-y", $("#addWH-form").find("#OLD_COORDINATE_Y").val());
                                $("#" + marker).attr("data-x", $("#addWH-form").find("#OLD_COORDINATE_X").val());
                                $("#"+$dialogName).dialog('close');
                            }
                        }
                    ]        
                });
                $("#"+$dialogName).dialog('open');
                $.unblockUI();
            },
            error:function(){
                alert("Error");
            }
        }).done(function(){
            Core.initAjax();
        });
    }
    function locationDelete(elem){
        var locationId = $(elem).attr("data-locationid");
        var wareHouseId = $(elem).attr("data-warehouseid");
        if(locationId==undefined){locationId = $('#LOCATION_ID option:selected').val();}
        if(wareHouseId==undefined){wareHouseId = $('#WAREHOUSE_ID option:selected').val();}
        $.ajax({
            type: 'post',
            url: 'mdwarehouse/removeLocationPosition',
            data: {LOCATION_ID: locationId, WAREHOUSE_ID: wareHouseId, COORDINATE_X: $(elem).attr("data-x"), COORDINATE_Y: $(elem).attr("data-y")},
            dataType: "json",
            beforeSend: function(){
                Core.blockUI({
                    message: plang.get('msg_saving_block'),
                    boxed: true
                });
            },
            success: function(data) {
                $.unblockUI();
                if (data.status === 'success') {
                    new PNotify({
                        title: 'Success',
                        text: data.message,
                        type: 'success',
                        sticker: false
                    });
                    showImage($('#LOCATION_ID option:selected').val(), $('#WAREHOUSE_ID option:selected').val());
                } else if(data.status === 'empty') {
                    new PNotify({
                        title: 'Анхааруулга',
                        text: data.message,
                        type: 'error',
                        sticker: false
                    });
                    showImage($('#LOCATION_ID option:selected').val(), $('#WAREHOUSE_ID option:selected').val());
                }else {
                    new PNotify({
                        title: 'Error',
                        text: data.message,
                        type: 'error',
                        sticker: false
                    });
                }
            },
            error: function(){
                alert("Error");
            }
        });
    }
</script>
