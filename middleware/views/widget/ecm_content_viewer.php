<div class="dvecommerce dvecommerce-<?php echo $this->uniqId ?>">
    <div style="margin-top:15px; background-color: #fff;">
        <div class="row">
            <div class="col-md-12">
                <div class="row barimts colplr">
                    <div style="" class="col-md-11">
                        <iframe id="viewFile" src="" frameborder="0" style="width: 100%;height: 700px; border-bottom: 10px solid #414141;"></iframe>  
                        <img id="viewImage" src="" class="img-fluid mar-auto">
                    </div>
                    <div style="" class="col-md-1 barimtatt">
                            <?php 
                            if ($this->getContent) { ?>
                                <div style="padding-right: 0;" class="col-md-12">
                                <?php
                                $ecmGroup = Arr::groupByArray($this->getContent, 'CONTENT_TYPE_NAME');
                                foreach($ecmGroup as $gk => $group) {
                                    echo '<h4 style="color:#00abe5; text-transform: uppercase; font-weight: bold; font-size: 14px;  margin-top: 6px;">' . $gk . '</h4>';
                                    foreach ($group['rows'] as $key => $row) { 
                                        $fileExtension = strtolower(substr($row["PHYSICAL_PATH"], strrpos($row["PHYSICAL_PATH"], '.') + 1));
                                        $smallIcon = 'assets/core/global/img/filetype/64/'. $fileExtension .'.png'; ?>
                                        <img class="thumb click_data" src="<?php echo $smallIcon ?>" data-filepath="<?php echo $row["PHYSICAL_PATH"] ?>" onerror="onDocError(this);" data-attr-ext="<?php echo $fileExtension; ?>" style="width: 60px; height: 60px; cursor: pointer; margin-top:3px">
                                        <?php 
                                    } 
                                } 
                                ?>
                                    </div>
                        <?php } ?>
                    </div>                        
                </div>
                <div class="clearfix w-100"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
      
      $(function () {
        $('.dvecommerce-<?php echo $this->uniqId ?>').find('.colplr').find('img#viewImage').show();
        $('.dvecommerce-<?php echo $this->uniqId ?>').find('.colplr').find('iframe#viewFile').hide();   

            $("#topbuttonInput").on("keyup", function() {
              var value = $(this).val().toLowerCase();
              $(".dvecommerce .topbutton .btn-lg").filter(function() {
                  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
              });
          });

          Core.initFancybox($('.list-view-photo'));
          $('body').on('click', '.data-filter > a', function(e) {
              var $this = $(this);

              $('.data-filter').find('a').removeClass('green-meadow').addClass('btn-secondary');
              $this.addClass('green-meadow').removeClass('btn-secondary');
              var filterType = $this.attr('data-filter').replace('.', ',');

              if (filterType === 'all') {
                  $('ul.list-view-photo li.shadow').removeClass('hidden');
              } else {
                  $('ul.list-view-photo li.shadow').addClass('hidden');
                  $('ul.list-view-photo li[data-src-id*="'+filterType+'"]').removeClass('hidden');
              }
          });

          $(document.body).on('click', 'img.click_data', function() {
            var _$this = $(this);

            _$this.closest('.colplr').find('img#viewImage').show();
            _$this.closest('.colplr').find('iframe#viewFile').hide();
            
            _$this.closest('.colplr').find('img#viewImage').attr('src', _$this.attr('src'));
            
            switch (_$this.attr('data-attr-ext')) {
                case 'pdf':
                    _$this.closest('.colplr').find('img#viewImage').hide();
                    _$this.closest('.colplr').find('iframe#viewFile').show();
                    _$this.closest('.colplr').find('iframe#viewFile').attr('src', '<?php echo URL; ?>api/pdf/web/viewer.html?file=<?php echo URL; ?>' + _$this.attr('data-filepath'));
                    break;
                case 'doc':
                    _$this.closest('.colplr').find('img#viewImage').hide();
                    _$this.closest('.colplr').find('iframe#viewFile').show();
                    _$this.closest('.colplr').find('iframe#viewFile').attr('src', '<?php echo CONFIG_FILE_VIEWER_ADDRESS; ?>DocEdit.aspx?showRb=0&url=<?php echo URL; ?>' + _$this.attr('data-filepath'));
                    break;
                case 'docx':
                    _$this.closest('.colplr').find('img#viewImage').hide();
                    _$this.closest('.colplr').find('iframe#viewFile').show();
                    _$this.closest('.colplr').find('iframe#viewFile').attr('src', '<?php echo CONFIG_FILE_VIEWER_ADDRESS; ?>DocEdit.aspx?showRb=0&url=<?php echo URL; ?>' + _$this.attr('data-filepath'));
                    break;
                case 'xls': 
                    _$this.closest('.colplr').find('img#viewImage').hide();
                    _$this.closest('.colplr').find('iframe#viewFile').show();
                    _$this.closest('.colplr').find('iframe#viewFile').attr('src', '<?php echo CONFIG_FILE_VIEWER_ADDRESS; ?>SheetEdit.aspx?showRb=0&url=<?php echo URL; ?>' + _$this.attr('data-filepath'));
                    break;
                case 'xlsx':
                    _$this.closest('.colplr').find('img#viewImage').hide();
                    _$this.closest('.colplr').find('iframe#viewFile').show();
                    _$this.closest('.colplr').find('iframe#viewFile').attr('src', '<?php echo CONFIG_FILE_VIEWER_ADDRESS; ?>SheetEdit.aspx?showRb=0&url=<?php echo URL; ?>' + _$this.attr('data-filepath'));
                    break;
                default:
                    _$this.closest('.colplr').find('img#viewImage').attr('src', _$this.attr('data-filepath'));
                    break;
            }
        });

          $('.barimtatt').find('img.click_data:eq(0)').trigger('click');
      });
</script>

<style type="text/css">
  
    .main-dvcommerce {
        position: relative;
        z-index: 999999;
        background: #f2f2f2;
        top: -20px;
        margin-left: -15px;
        padding-left: 15px;
        margin-right: -15px;
        padding-right: 15px;
        padding-top: 10px;margin-bottom: -15px;
    }
    
    .main-charvideo {
        margin-bottom: 20px;
        background: #f2f2f2;
        margin-left: -15px;
        margin-right: -15px;
        padding-left: 15px;
        margin-top: -20px;
        padding-top: 10px;
        padding-bottom: 5px;
    }
    
    .dvecommerce-body .ui-dialog .ui-widget-header {
        height: 40px;
    }
    
    .dvecommerce-body .ui-dialog .ui-dialog-title {
        line-height: 24px;
    }
    
    .dvecommerce-body .ui-dialog .ui-dialog-buttonpane button {
        padding: 5px 20px;
        text-transform: uppercase;
    }
    
    .dvecommerce-body .ui-dialog .ui-dialog-buttonpane {
        margin-top: 0;
        background: #DDD;
        border: 0;
        padding: 5px 10px;
    }
    
    .dvecommerce-body .ui-dialog .ui-dialog-content {
        padding: 10px 15px 0;
    }
    
</style>