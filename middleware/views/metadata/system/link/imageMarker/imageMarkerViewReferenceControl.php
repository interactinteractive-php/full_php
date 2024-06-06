<?php if (isset($this->postParams['sidebardvid'])) { ?>
<div class="row">
    <div class="col-md-9">
        <div class="row imageMarkerReferenceViewContainer" data-main-locationId='<?php echo $this->locationId; ?>' id="windowid-<?php echo $this->uniqId; ?>">
            <?php 
            if (!empty($this->getPhoto['region'])) {
                foreach ($this->getPhoto['region'] as $row) {
                    $rowVal = $row;
                    $existItem = ' imageMarkerViewDivImageExist';
                    $row = json_decode(html_entity_decode($row['REGION']), true);
                    if (empty($rowVal['ITEM_KEY_ID'])) {
                        $existItem = '';
                    }
                    
                    $title = $rowVal['LOCATION_CODE'].' '.$rowVal['LOCATION_NAME'];

                    if (isset($rowVal['COLOR']) && $rowVal['COLOR'] == '1') {
                        echo "<div class='imageMarkerViewDivImage2".$existItem."' data-locationId='".$rowVal['LOCATION_ID']."' style='left:".($row['x']+0)."px;top:".($row['y']+38)."px;height:".$row['h']."px;width:".$row['w']."px;'>
                              <span style='position: absolute;margin-top: -22px;font-size: 10px;line-height: 8px;font-weight: bold;padding: 1px;'>".$rowVal['LOCATION_CODE']." ".$rowVal['LOCATION_NAME']."</span>
                              <span class='hidden' style='position: absolute;bottom: -5px;left: -5px;'><a class='btn btn-sm green callDataviewImageMarker' href='javascript:;'><i class='fa fa-navicon'></i></a></span></div>";
                    } else {
                        if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) {
                            echo "<div title='$title' class='imageMarkerViewDivImage".$existItem."' ".(isset($rowVal['CHILD_PHOTO']) ? 'data-assetmarker=""' : '')." data-locationKeyId='".$rowVal['LOCATION_KEY_ID']."' data-locationId='".$rowVal['LOCATION_ID']."' data-picturepath='".$rowVal['PHOTO']."' style='left:".($row['x']+(isset($this->isWorkspace) && $this->isWorkspace == 1 ? 12 : 12))."px;top:".($row['y']+1)."px;height:".$row['h']."px;width:".$row['w']."px; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover; background-image: ".(issetVar($rowVal['CHILD_PHOTO']) ? 'url('.$rowVal['CHILD_PHOTO'].')' : 'none')."'>
                                  <span class='hidden' style='position: absolute;bottom: -5px;left: -5px;'><a class='btn btn-sm green callDataviewImageMarker' data-processId='".issetVar($this->postParams['processid'])."' href='javascript:;'><i class='fa fa-navicon'></i></a></span>
                                </div>";
                        } else {
                            echo "<div class='imageMarkerViewDivImage".$existItem."' data-locationId='".$rowVal['LOCATION_ID']."' data-picturepath='".$rowVal['PHOTO']."' style='left:".($row['x']+0)."px;top:".($row['y']+38)."px;height:".$row['h']."px;width:".$row['w']."px;'>
                                  <span style='position: absolute;margin-top: -22px;font-size: 10px;line-height: 8px;font-weight: bold;padding: 1px;'>".$rowVal['LOCATION_CODE']." ".$rowVal['LOCATION_NAME']."</span>
                                  <span class='hidden' style='position: absolute;bottom: -5px;left: -5px;'><a class='btn btn-sm green callDataviewImageMarker' data-processId='".issetVar($this->postParams['processid'])."' href='javascript:;'><i class='fa fa-navicon'></i></a></span></div>";
                        }
                    }
                }
            }
            ?>    
            <div id="jcropDiv" class="pl10"></div>
        </div>
    </div>
    <div class="col-md-3">
        <div id="sidebardv-<?php echo $this->uniqId; ?>">
        </div>
    </div>
</div>

<?php } else { ?>

<div class="row imageMarkerReferenceViewContainer" data-main-locationId='<?php echo $this->locationId; ?>' id="windowid-<?php echo $this->uniqId; ?>">
    <?php 
    if (!empty($this->getPhoto['region'])) {
        foreach ($this->getPhoto['region'] as $row) {
            $rowVal = $row;
            $existItem = ' imageMarkerViewDivImageExist';
            $row = json_decode(html_entity_decode($row['REGION']), true);

            if (empty($rowVal['ITEM_KEY_ID'])) {
                $existItem = '';
            }

            $title = $rowVal['LOCATION_CODE'].' '.$rowVal['LOCATION_NAME'];

            if (isset($rowVal['COLOR']) && $rowVal['COLOR'] == '1') {
                echo "<div class='imageMarkerViewDivImage2".$existItem."' data-locationId='".$rowVal['LOCATION_ID']."' style='left:".($row['x']+0)."px;top:".($row['y']+38)."px;height:".$row['h']."px;width:".$row['w']."px;'>
                      <span style='position: absolute;margin-top: -22px;font-size: 10px;line-height: 8px;font-weight: bold;padding: 1px;'>".$rowVal['LOCATION_CODE']." ".$rowVal['LOCATION_NAME']."</span>
                      <span class='hidden' style='position: absolute;bottom: -5px;left: -5px;'><a class='btn btn-sm green callDataviewImageMarker' href='javascript:;'><i class='fa fa-navicon'></i></a></span></div>";
            } else {
                if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) {
                    echo "<div title='$title' class='imageMarkerViewDivImage".$existItem."' ".(isset($rowVal['CHILD_PHOTO']) ? 'data-assetmarker=""' : '')." data-locationKeyId='".$rowVal['LOCATION_KEY_ID']."' data-locationId='".$rowVal['LOCATION_ID']."' data-picturepath='".$rowVal['PHOTO']."' style='left:".($row['x']+(isset($this->isWorkspace) && $this->isWorkspace == 1 ? 35 : 12))."px;top:".($row['y']+11)."px;height:".$row['h']."px;width:".$row['w']."px; -webkit-background-size: contain; -moz-background-size: contain; -o-background-size: contain; background-size: contain; background-repeat: no-repeat;background-position: center;background-image: ".(issetVar($rowVal['CHILD_PHOTO']) ? 'url('.$rowVal['CHILD_PHOTO'].')' : 'none')."'>
                          <span class='' style='position: absolute;left: -3px;'><a class='callDataviewImageMarker' data-processId='".issetVar($this->postParams['processid'])."' href='javascript:;'><i style='background-color: black' class='fa fa-navicon'></i></a></span>
                        </div>";
                } else {
                    echo "<div class='imageMarkerViewDivImage".$existItem."' data-locationId='".$rowVal['LOCATION_ID']."' data-locationKeyId='".$rowVal['LOCATION_KEY_ID']."' data-picturepath='".$rowVal['PHOTO']."' style='left:".($row['x']+0)."px;top:".($row['y']+38)."px;height:".$row['h']."px;width:".$row['w']."px;'>
                          <span style='position: absolute;margin-top: -22px;font-size: 10px;line-height: 8px;font-weight: bold;padding: 1px;'>".$rowVal['LOCATION_CODE']." ".$rowVal['LOCATION_NAME']."</span>
                          <span class='hidden' style='position: absolute;left: -3px;'><a class='btn btn-sm green callDataviewImageMarker' data-processId='".issetVar($this->postParams['processid'])."' href='javascript:;'><i style='background-color: black' class='fa fa-navicon'></i></a></span></div>";
                }
            }
        }
    }
    ?>    
    <div id="jcropDiv" class="pl10"></div>
</div>
<?php } ?>

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
        background-color: rgba(152, 251, 152, 0.58);
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
    
    $(function(){
        if (!$().Jcrop) {
            $.getScript(URL_APP + 'assets/custom/addon/plugins/jcrop/js/jquery.Jcrop.min.js', function(){
                $.getStylesheet(URL_APP + 'assets/custom/addon/plugins/jcrop/css/jquery.Jcrop.min.css');
                $.getScript(URL_APP + 'assets/custom/pages/scripts/form-image-crop.js', function(){});
            });
        }
        
        setTimeout(function(){
            $("#jcropDiv", windowId_<?php echo $this->uniqId; ?>).empty().append('<img src="<?php echo $this->getPhoto['url']; ?>" data-id="5"  style="max-width: 1150px;" alt="" />');
            $("#jcropDiv img", windowId_<?php echo $this->uniqId; ?>).Jcrop({
                bgColor: '#fff',
                bgOpacity: 1,
                allowSelect: false,
                allowMove: true,
                allowResize: false
            });
        }, 200);
        
        <?php if(!Input::postCheck('callDataviewMarker')) { ?>
            $(windowId_<?php echo $this->uniqId; ?>).on('mouseenter mouseleave', '.imageMarkerViewDivImage, .imageMarkerViewDivImage2', function(e){
                var _this = $(this);            
                if (e.type === 'mouseleave') {
                    _this.children('span:last').addClass('hidden');
                } else {
                    _this.children('span:last').removeClass('hidden');
                }
            });
        <?php } ?>
        
        $(windowId_<?php echo $this->uniqId; ?>).on('click', '.callDataviewImageMarker', function(e){
            var $this = $(this), locationKeyId = '';
                
            if ($this.closest('.imageMarkerViewDivImage').length) {
                var locationId = $this.closest('.imageMarkerViewDivImage').attr('data-locationId');
                locationKeyId = $this.closest('.imageMarkerViewDivImage').attr('data-locationKeyId');
            } else {
                var locationId = $this.closest('.imageMarkerViewDivImage2').attr('data-locationId');
            }
            
            if (typeof $this.attr('data-processId') !== 'undefined' && $this.attr('data-processId') != '') {
                _processPostParam = '';
                callWebServiceByMeta($this.attr('data-processId'), true, '', false, '', undefined, undefined, undefined, undefined, undefined, undefined, {'assetLocationId': locationId, 'id': locationKeyId}, '1', 'id='+locationKeyId);
            } else {
                checkMetaDataTypeFunction('1493026165006', null, '<?php echo $this->lang->line('META_00062'); ?>', null, '<?php echo $this->lang->line('META_00033'); ?>', '', '', JSON.stringify({filterLocationId: locationId}));
            }
            
            setTimeout(function(){
                $('.ui-dialog').css('z-index', '999');
            }, 300);
            
            e.preventDefault();    
            e.stopPropagation();
        });
        
        $(windowId_<?php echo $this->uniqId; ?>).on('click', '.imageMarkerViewDivImage, .imageMarkerViewDivImage2', function(e){
            drillDownImageMarkerReferenceView(this);
        });
        
        <?php if(isset($this->postParams['sidebardvid'])) { ?>
            layoutCallDataViewByMeta_<?php echo $this->uniqId; ?>(<?php echo $this->postParams['sidebardvid']; ?>);
        <?php } ?>
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
                uriParams: JSON.stringify({locationId:<?php echo $this->locationId; ?>})
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
</script>

