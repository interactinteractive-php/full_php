<div class="row">
    <div class="col-md-9">
        <div id="windowid2-<?php echo $this->uniqId; ?>"></div>
        <div class="row imageMarkerReferenceViewContainer" data-main-locationId='<?php echo $this->locationId; ?>' id="windowid-<?php echo $this->uniqId; ?>"> 
            <div id="jcropDiv" class="pl10"></div>
        </div>
    </div>
    <div class="col-md-3">
        <div id="sidebardv-<?php echo $this->uniqId; ?>" class="freeze-overflow-xy-auto" style="position:fixed;">
            <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1 bprocess-theme1-proc">
                <thead>
                    <tr>
                        <th class="itemnameheader bp-head-sort-proc" style="">Ширээ</th>
                    </tr>
                    <tr class="bp-filter-row-proc bp-filter-row">
                        <th class="" style="background-color: rgb(231, 231, 231); position: relative; z-index: 9; background-clip: padding-box; top: -1px;"><input type="text"/></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style type="text/css">
    .imageMarkerViewDivImage {
        cursor:pointer;
        position: absolute;
        z-index: 97;
        background-color: rgba(209, 210, 40, 0.58);
        border: 2px solid transparent;
    }
    .imageMarkerViewDivImage2 {
        cursor:pointer;
        position: absolute;
        z-index: 97;
        background-color: rgba(255, 102, 0, 0.7);
        border: 2px solid transparent;
    }
    .imageMarkerViewDivImage:hover, .imageMarkerViewDivImage2:hover {
        outline: #35aa47 solid thick;
    }
    .imageMarkerViewDivImage, .imageMarkerViewDivImage2 {
        outline: #35aa47 solid 1px;
    }
    .imageMarkerViewDivImageExist {
        background: url("<?php echo URL; ?>assets/core/global/img/imageMarkerViewExist.gif");
        background-size: 28px 27px;
        background-repeat: no-repeat; 
        background-position: center center;
        background-color: rgba(209, 210, 40, 0.58);
    }
    .jcrop-tracker {
        z-index: 96 !important;
    }
</style>

<script type="text/javascript">
    var windowId_<?php echo $this->uniqId; ?> = "#windowid-<?php echo $this->uniqId; ?>";
    var j = '';
    
    $(function(){
        if (!$().Jcrop) {
            $.getScript(URL_APP + 'assets/custom/addon/plugins/jcrop/js/jquery.Jcrop.min.js', function(){
                $.getStylesheet(URL_APP + 'assets/custom/addon/plugins/jcrop/css/jquery.Jcrop.min.css');
                $.getScript(URL_APP + 'assets/custom/pages/scripts/form-image-crop.js', function(){});
            });
        }
        
        setTimeout(function(){
            $("#jcropDiv", windowId_<?php echo $this->uniqId; ?>).empty().append('<img src="<?php echo $this->getPhoto['url']; ?>" data-id="7"  style="max-width: 1150px;" alt="" />');
            $("#jcropDiv img", windowId_<?php echo $this->uniqId; ?>).Jcrop({
              onChange: canvas,
              bgColor: '#fff',
              bgOpacity: 1,
              onSelect: canvas,
              /*boxWidth: '950',
              boxHeight: '600',*/
              setSelect: j
            });        
            
            $('#sidebardv-<?php echo $this->uniqId; ?>').css('max-height', $(window).height() - 105 + 'px');
            $('#sidebardv-<?php echo $this->uniqId; ?>').css('width', $('#sidebardv-<?php echo $this->uniqId; ?>').parent().width() + 'px');
            
        }, 200);
        
        setTimeout(function(){
            $("#jcropDiv", windowId_<?php echo $this->uniqId; ?>).find('.jcrop-holder').children().children().find('.jcrop-tracker').css('background-color', 'rgba(255, 102, 0, 0.7)');
        }, 250);
      
        var canvas = function(coords){
            var js = {};
            js.h = coords.h;
            js.w = coords.w;
            js.x = coords.x;
            js.y = coords.y;
            $("#jcropDiv", windowId_<?php echo $this->uniqId; ?>).attr('data-imagejsonstring', JSON.stringify(js));
        };        
        
        $('table.bprocess-theme1-proc', '#sidebardv-<?php echo $this->uniqId; ?>').tableHeadFixer({'head': true, 'z-index': 9});
        
        $(document).on('click', '.callDataviewImageMarker', function(e){
            var $this = $(this);
            var locationId = $this.closest('.imageMarkerViewDivImage2').attr('data-locationId');
            
            var $dialogName = 'dialog-confirm';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);      
            
            $dialog.empty().append('Та устгахдаа итгэлтэй байна уу?');
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: 'Анхааруулга',
                width: 370,
                height: "auto",
                modal: true,
                open: function() {
                },
                close: function() {
                    $dialog.empty().dialog('close');
                },
                buttons: [{
                        text: plang.get('yes_btn'),
                        class: 'btn green-meadow btn-sm',
                        click: function() {            
                            $.ajax({
                                type: 'post',
                                dataType: 'json',
                                url: 'mdpos/deleteTableLocation',
                                data: {
                                    id: locationId
                                },
                                beforeSend: function () {
                                    Core.blockUI({
                                        message: 'Loading...',
                                        boxed: true
                                    });                
                                },
                                success: function (data) {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: 'Success',
                                        text: 'Амжилттай устгагдлаа',
                                        type: 'success', 
                                        sticker: false
                                    });   
                                    getTableHtml();
                                    getLocationHtml();                    
                                    Core.unblockUI();
                                    $dialog.dialog('close');
                                },
                                error: function(){
                                  alert("Error");
                                  Core.unblockUI();
                                }
                            }).done(function(){
                            });  
                        }
                    },
                    {
                        text: plang.get('no_btn'),
                        class: 'btn blue-madison btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');                        
            
            e.preventDefault();    
            e.stopPropagation();
        });        
        
        getTableHtml();
        getLocationHtml();
        
        $("#windowid2-<?php echo $this->uniqId; ?>").on('mouseenter mouseleave', '.imageMarkerViewDivImage2', function(e){
            var _this = $(this);            
            if (e.type === 'mouseleave') {
                _this.children('span:last').addClass('hidden');
            } else {
                _this.children('span:last').removeClass('hidden');
            }
        });        
    });
    
    function refreshLocationPhoto(rowId, photoUrl) {
        console.log(rowId);
        console.log(photoUrl);
        $('.imageMarkerReferenceViewContainer:visible:last').find('.imageMarkerViewDivImage[data-locationkeyid="'+rowId+'"]').css('background-image', 'url('+photoUrl+')');
    }
    
    function drillDownImageMarkerReferenceView(elem, type) {
        var loId = (typeof type !== 'undefined') ? $(elem).attr('data-prev-locationid') : $(elem).attr('data-locationId'),
            planPicture = $(elem).attr('data-picturepath');
        
        $.ajax({
            url: 'mdmeta/customImageMarkerDrillDownViewReferenceCtrl',
            data: {
                locationId: loId,
                rlocationId: '<?php echo $this->locationId ?>',
                type: type,
                postParams: '<?php echo Arr::encode($this->postParams); ?>',
                planPicture: planPicture
            },
            type: 'POST',
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                if (data.Html != '') {
                    var backBtn = '<a class="btn btn-success btn-circle btn-sm" data-prev-locationid="'+ data.prevLocationId +'" data-locationId="<?php echo $this->locationId ?>" data-picturepath="<?php echo $this->getPhoto['url']; ?>" onclick="drillDownImageMarkerReferenceView(this, \'back\');" style="position: absolute;z-index: 98;margin-left: 15px;margin-top: 4px;" href="javascript:;" title="<?php echo $this->lang->line('META_00195'); ?>"><i class="fa fa-arrow-left"></i> Буцах</a>';
                    if($(windowId_<?php echo $this->uniqId; ?>).attr('data-main-locationId') == loId)
                        backBtn = '';
                        
                    $(windowId_<?php echo $this->uniqId; ?>).empty().append(backBtn + data.Html);
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Info',
                        text: 'Хоосон байна',
                        type: 'info',
                        sticker: false
                    });                    
                }
                Core.unblockUI();
            }
        });
    }
    
    function layoutCallDataViewByMeta_<?php echo $this->uniqId; ?>(metaDataId) {        
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'mdobject/dataview/' + metaDataId + '/' + 'false'+ '/json' ,
            data: {
                dvIgnoreToolbar: '1',
                /*uriParams: JSON.stringify({locationId:<?php echo $this->locationId; ?>})*/
            },
            beforeSend: function () {
            },
            success: function (data) {
                $("div#sidebardv-<?php echo $this->uniqId; ?>").empty().append(''+
                    '<div class="card light ws-widget-light">'+
                        '<div class="card-header card-header-no-padding header-elements-inline">'+
                            '<div class="caption caption-md">'+
                                '<span class="caption-subject font-blue-madison font-weight-bold uppercase">'+data.Title+'</span>'+
                            '</div>'+
                        '</div>'+
                        '<div class="card-body widget-body">'+
                            data.Html
                        +'</div>'+
                    '</div>');
            },
            error: function(){
              alert("Error");
            }
        }).done(function(){
            Core.initAjax($("div#sidebardv-<?php echo $this->uniqId; ?>"));
        });
    }    
    
    function savePositionChair(elem, id) {
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'mdpos/saveLocationImage',
            data: {
                id: id,
                location: $("#jcropDiv", windowId_<?php echo $this->uniqId; ?>).attr('data-imagejsonstring')
            },
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });                
            },
            success: function (data) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Success',
                    text: 'Амжилттай хадгалагдлаа',
                    type: 'success', 
                    sticker: false
                });                
                getTableHtml();
                getLocationHtml();                
                Core.unblockUI();
            },
            error: function(){
              alert("Error");
            }
        }).done(function(){
        });        
    }
    
    function getTables(stype, callback) {
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'mdpos/getTables',
            data: {
                stype: stype,
                dataViewId: '<?php echo $this->postParams['dataViewId'] ?>'
            },
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });                
            },
            success: function (data) {
                callback(data);
            },
            error: function(){
              alert("Error");
              Core.unblockUI();
            }
        }).done(function(){
        });        
    }
    
    function getTableHtml() {
        getTables('', function(data){
            if (data) {
                var html = '';
                for (var i = 0; i < data.length; i++) {
                    if (data[i]['locationname']) {
                        html += '<tr><td style="padding:10px;cursor:pointer;" onclick="savePositionChair(this, \''+data[i]['id']+'\')">'+data[i]['locationname']+'</td></tr>';
                    }
                }      
                $('table.bprocess-theme1-proc', '#sidebardv-<?php echo $this->uniqId; ?>').find('tbody').empty().append(html);
            }
            Core.unblockUI();
        });
    }
    
    function getLocationHtml() {
        getTables('1', function(data){
            if (data) {
                var htmlLocation = '', position;
                for (var i = 0; i < data.length; i++) {
                    if (data[i]['locationname'] && data[i]['position']) {
                        position = JSON.parse(html_entity_decode(data[i]['position']));
                        htmlLocation += "<div class='imageMarkerViewDivImage2' data-locationId='"+data[i]['id']+"' style='left:"+(position['x']+12)+"px;top:"+(position['y']+1)+"px;height:"+position['h']+"px;width:"+position['w']+"px;'>"+
                                        "<span style='position: absolute;margin-top: -19px;font-size: 10px;line-height: 8px;font-weight: bold;padding: 1px;color: #ddd;'>"+data[i]['locationname']+"</span>"+
                                        "<span class='hidden' style='position: absolute;bottom: -2px;left: -2px;'><a class='btn btn-sm red callDataviewImageMarker' style='padding: 0px 5px 0px 5px;' href='javascript:;'><i class='fa fa-trash'></i></a></span>";
                        htmlLocation += "</div>";
                    }
                }
                $("#windowid2-<?php echo $this->uniqId; ?>").empty().append(htmlLocation);
            }
            Core.unblockUI();
        });
    }
</script>

