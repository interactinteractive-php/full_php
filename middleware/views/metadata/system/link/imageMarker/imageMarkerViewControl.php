<div class="row imageMarkerViewContainer" data-main-locationId='<?php echo $this->locationId; ?>'>
    <?php 
    if(!empty($this->getPhoto['region'])) {
        foreach ($this->getPhoto['region'] as $row) {
            $rowVal = $row;
            $existItem = ' imageMarkerViewDivImageExist';
            $row = json_decode(html_entity_decode($row['REGION']), true);
            if(empty($rowVal['ITEM_KEY_ID']))
                $existItem = '';
            
            echo "<div class='imageMarkerViewDivImage".$existItem."' data-locationId='".$rowVal['LOCATION_ID']."' data-prev-locationId='".$this->locationId."' data-itemkeyid='".$rowVal['ITEM_KEY_ID']."' data-photo-url='".$rowVal['PHOTO']."' data-prev-photo-url='".$this->getPhoto['url']."' style='left:".($row['x']+0)."px;top:".($row['y']+38)."px;height:".$row['h']."px;width:".$row['w']."px;'>
                  <span style='position: absolute;margin-top: -22px;font-size: 10px;line-height: 8px;font-weight: bold;padding: 1px;'>".$rowVal['LOCATION_CODE']." ".$rowVal['LOCATION_NAME']."</span>
                  <span class='hidden' style='position: absolute;bottom: -5px;left: -5px;'><a class='btn btn-sm green callDataviewImageMarker' href='javascript:;'><i class='fa fa-navicon'></i></a></span></div>";
        }
    }
    ?>    
    <div id="jcropDiv" class="pl10"></div>
</div>

<style>
    .imageMarkerViewDivImage {
        cursor:pointer;
        position: absolute;
        background-color: rgba(209, 210, 40, 0.58);
        z-index: 291;   
    }
    .imageMarkerViewDivImage:hover {
        outline: #35aa47 solid thick;
    }
    .imageMarkerViewDivImageExist {
        background: url("<?php echo URL; ?>assets/core/global/img/imageMarkerViewExist.gif");
        background-size: 28px 27px; 
        background-repeat: no-repeat; 
        background-position: center center;
        background-color: rgba(209, 210, 40, 0.58);
    }
</style>

<script type="text/javascript">
    $(function(){
        if(!$().Jcrop){
          $.getScript(URL_APP + 'assets/custom/addon/plugins/jcrop/js/jquery.Jcrop.min.js', function(){
            $.getStylesheet(URL_APP + 'assets/custom/addon/plugins/jcrop/css/jquery.Jcrop.min.css');
            $.getScript(URL_APP + 'assets/custom/addon/admin/pages/scripts/form-image-crop.js', function(){
            });
          });
        }
        
        setTimeout(function(){
            $("#jcropDiv", ".imageMarkerViewContainer").html("").append('<img src="<?php echo $this->getPhoto['url']; ?>" data-id="4"  style="max-width: 1150px;" alt="" />');
            $("#jcropDiv img", ".imageMarkerViewContainer").Jcrop({
              bgColor: '#fff',
              bgOpacity: 1,
              allowSelect: false,
              allowMove: true,
              allowResize: false
            });
            
        }, 100);
        
        $(document).on('mouseenter mouseleave', '.imageMarkerViewDivImage', function(e){
            var _this = $(this);            
            if(e.type === 'mouseleave') {
                _this.children('span:last').addClass('hidden');
            } else {
                _this.children('span:last').removeClass('hidden');
            }
        });
        
        $('.imageMarkerViewContainer').on('click', '.callDataviewImageMarker', function(e){
            var _this = $(this);
            var locationId = _this.closest('.imageMarkerViewDivImage').attr('data-locationId');
            
            checkMetaDataTypeFunction('1493026165006', null, '<?php echo $this->lang->line('META_00062'); ?>', null, '<?php echo $this->lang->line('META_00033'); ?>', '', '', JSON.stringify({filterLocationId: locationId}));
            setTimeout(function(){
                $('.ui-dialog').css('z-index', '999');
            }, 300);
            
            e.preventDefault();    
            e.stopPropagation();
        });
        
        $('.imageMarkerViewContainer').on('click', '.imageMarkerViewDivImage', function(e){
            drillDownImageMarkerView(this);
        });                
    });
    
    function drillDownImageMarkerView(elem){
        var loId = $(elem).attr('data-locationId');
        
        $.ajax({
            url: 'mdmeta/customImageMarkerDrillDownViewCtrl', 
            data: {
                locationId: loId,
                prevLocationId: $(elem).attr('data-prev-locationId'),
                objectPhoto: $(elem).attr('data-photo-url'),
                prevObjectPhoto: $(elem).attr('data-prev-photo-url'),
                itemKeyId: $(elem).attr('data-itemkeyid')
            },
            type: 'POST',
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                if(data.Html != '') {
                    var backBtn = '<a class="btn btn-success btn-circle btn-sm" data-locationId="'+data.prevLocation+'" data-prev-locationId="'+data.prevLocation+'" data-itemkeyid="'+data.itemKeyId+'" data-photo-url="'+data.prevObjectPhoto+'" data-prev-photo-url="'+data.prevObjectPhoto+'" onclick="drillDownImageMarkerView(this);" style="position: absolute;z-index: 999;margin-left: 15px;margin-top: 4px;" href="javascript:;" title="<?php echo $this->lang->line('META_00195'); ?>"><i class="fa fa-arrow-left"></i> Буцах</a>';
                    if($('.imageMarkerViewContainer').attr('data-main-locationId') == loId)
                        backBtn = '';
                        
                    $('.imageMarkerViewContainer').empty().append(backBtn + data.Html);
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
            },
            error: function () {

            }
        }).done(function(){
        });
    }
</script>

