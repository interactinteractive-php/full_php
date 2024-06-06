<link rel="stylesheet" type="text/css" href="<?php echo autoVersion('middleware/assets/css/gridlayout/postview.css'); ?>"/>

<div class="d-flex postview-listtheme flex-wrap">
    <?php
        $fields = $this->row['dataViewLayoutTypes']['explorer']['fields'];

        if ($this->recordList) {

            if (isset($this->recordList['status'])) {
                echo html_tag('div', array('class' => 'alert alert-danger'), 'DV error message: ' . $this->recordList['message']);
                exit();
            } 
            
            /**
             * init position layout
             */
//            pa($this->recordList);
//            pa($fields);
            $backgroundImage = issetParam($fields['backgroundImage']);
            $userpicture = issetParam($fields['userpicture']);
            $name1 = issetParam($fields['name1']);
            $name2 = issetParam($fields['name2']);
            $name3 = issetParam($fields['name3']);
            $name4 = issetParam($fields['name4']);
            $name5 = issetParam($fields['name5']);            
            $imageArr = [];
            
            function multipleImageRender($imageArr) {
                $imageString = '';
                $countImg = count($imageArr);                
                
                switch ($countImg) {
                    case 1:
                        $imageString .= '<div class="card-img-actions d-flex justify-content-center">';
                            $imageString .= '<img class="img-fluid posi-backgroundImage" src="'.$imageArr[0].'" alt="row photo">';
                        $imageString .= '</div>';
                        break;
                    
                    case 2:
                        $imageString .= '<div class="card-img-actions d-flex justify-content-center" style="flex-direction: row">';
                            $imageString .= '<img class="img-fluid posi-backgroundImage" style="width: 50%;" src="'.$imageArr[0].'" alt="row photo">';
                            $imageString .= '<img class="img-fluid posi-backgroundImage" style="width: 50%;" src="'.$imageArr[1].'" alt="row photo">';
                        $imageString .= '</div>';
                        break;
                    
                    case 3:
                        $imageString .= '<div class="card-img-actions d-flex justify-content-center" style="flex-direction: row">';
                            $imageString .= '<img class="img-fluid posi-backgroundImage" style="width: 60%;" src="'.$imageArr[0].'" alt="row photo">';
                            $imageString .= '<div style="width: 40%;">';
                                $imageString .= '<img class="img-fluid posi-backgroundImage" style="width: 100%;max-height: 100px;" src="'.$imageArr[1].'" alt="row photo">';
                                $imageString .= '<img class="img-fluid posi-backgroundImage" style="width: 100%;max-height: 100px;" src="'.$imageArr[2].'" alt="row photo">';
                            $imageString .= '</div>';
                        $imageString .= '</div>';
                        break;
                    
                    default:
                        $imageString .= '<div class="card-img-actions d-flex justify-content-center" style="flex-direction: row">';
                            $imageString .= '<img class="img-fluid posi-backgroundImage" style="width: 60%;" src="'.$imageArr[0].'" alt="row photo">';
                            $imageString .= '<div style="width: 40%;" class="side-images-containter">';
                                $imageString .= '<img class="img-fluid posi-backgroundImage" style="width: 100%;max-height: 100px;" src="'.$imageArr[1].'" alt="row photo">';
                                $imageString .= '<img class="img-fluid posi-backgroundImage" style="width: 100%;max-height: 100px;" src="'.$imageArr[2].'" alt="row photo">';
                                $imageString .= '<div class="centered-image-text">+'.($countImg-3).'</div>';
                            $imageString .= '</div>';
                        $imageString .= '</div>';
                        break;
                }                
                
                return $imageString;
            }
            
            foreach ($this->recordList as $recordIndex => $recordRow) {
                if ($backgroundImage && issetParam($recordRow[$backgroundImage])) {
                    $imageArr = explode(',', $recordRow[$backgroundImage]);                    
                }
                $imgjson = htmlentities(json_encode($imageArr), ENT_QUOTES, 'UTF-8');
                $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
            ?>

            <div class="postview-listtheme-row animated bounceIn" style="<?php echo 'z-index:'.(!$recordIndex % 2) ?>" data-row-images="<?php echo $imgjson ?>" data-row-dataid="<?php echo issetParam($recordRow['id']) ?>" data-row-data="<?php echo $rowJson; ?>">
                    <div class="card">
                        <div class="card-header bg-light d-flex justify-content-between hidden">
                            <span><i class="icon-user-check mr-2"></i> <a href="#">Eugene Kopyov</a></span>
                            <span class="text-muted">Added 2 hours ago</span>
                        </div>
                        
                        <?php echo multipleImageRender($imageArr); ?>

                        <div class="card-body p-3">
                            <h6 class="card-title font-weight-bold"><?php echo issetParam($recordRow[$name2]) ?></h6>
                            <p class="card-text posi-name3"><?php echo issetParam($recordRow[$name3]) ?></p>
                            <div class="postview-readmore <?php echo issetParam($recordRow[$name3]) ? '' : 'hidden'; ?>">Цааш үзэх...</div>
                        </div>

                        <div class="card-footer bg-transparent d-flex justify-content-between border-top-0 pt-0">
                            <div class="d-flex">
                                <div class="user-photo">
                                    <img class="img-fluid usrphtimg" class="posi-userpicture" src="<?php echo issetParam($recordRow[$userpicture]) ?>" alt="">
                                </div>
                                <div class="ml10">
                                    <div class="user-name posi-name1"><?php echo issetParam($recordRow[$name1]) ?></div>
                                    <!--<div class="user-date posi-name4"><?php echo issetParam($recordRow[$name4]) ?></div>-->
                                </div>
                            </div>

                            <div class="bp-action-btns">
                                <?php if ($name5) { ?>
                                    <a class="btn btn-success btn-circle btn-sm show-comment-btn" href="javascript:;">
                                        <i class="far fa-comment"></i> <span class="show-comment-count"><?php echo issetParamZero($recordRow[$name5]) ?></span>
                                    </a>
                                <?php } ?>
                                <?php echo $this->dataViewProcessCommand['commandBtn'] ?>
                            </div>
                        </div>
                        <div class="show-comment-container pt-0 hidden">
                        </div>
                    </div>                
                </div>                


        <?php
            }
        } else { ?>
            <div class="dv-process-buttons" style="min-height: 300px;">
                <div class="bp-action-btns mt10">
                    <?php echo $this->dataViewProcessCommand['commandBtn'] ?>
                </div>        
            </div>        
        <?php }
    ?>    
</div>    

<script type="text/javascript">
    var refStructureId = '<?php echo $this->refStructureId ?>';
    
    $(function() {
        $('#objectdatagrid-<?php echo $this->dataViewId; ?>').on('click', '.postview-listtheme-row', function(){
            var $this = $(this);
            var $parent = $this.closest('.postview-listtheme');
            $parent.find('.selected-row').removeClass('selected-row');
            $this.addClass('selected-row');
        });
        $('#objectdatagrid-<?php echo $this->dataViewId; ?>').on('click', '.postview-listtheme-row img:not(.usrphtimg),.postview-listtheme-row .centered-image-text', function(){
            var $this = $(this); $parent = $this.closest('.postview-listtheme-row');
            postviewShowImage($parent.data('row-images'));
        });
        $('#objectdatagrid-<?php echo $this->dataViewId; ?>').on('click', '.postview-listtheme-row .postview-readmore', function(){
            var $this = $(this);
            if ($this.text() !== 'Хураах') {
                $this.closest('.card-body').find('.posi-name3').removeClass('card-text');
                $this.text('Хураах');
            } else {
                $this.closest('.card-body').find('.posi-name3').addClass('card-text');
                $this.text('Цааш үзэх...');                
            }
        });
        $('#objectdatagrid-<?php echo $this->dataViewId; ?>').on('click', '.postview-listtheme-row .posi-name3', function(){
            var $this = $(this);
            $this.closest('.card-body').find('.postview-readmore').trigger('click');
        });
        $('#objectdatagrid-<?php echo $this->dataViewId; ?>').on('click', '.show-comment-btn', function() {
            var $this = $(this); $parent = $this.closest('.postview-listtheme-row');
            var sourceId = $parent.data('row-dataid');
            
            if (!$parent.find('.show-comment-container').hasClass('hidden')) {
                $parent.find('.show-comment-container').addClass('hidden');
                return;
            }            

            $.ajax({
                type: 'post',
                url: 'mdwebservice/renderEditModeBpCommentTab',
                data: {uniqId: getUniqueId(''), refStructureId: refStructureId, sourceId: sourceId},
                beforeSend: function() {
                    Core.blockUI({
                        target: $parent,
                        animate: true,
                        icon2Only: true
                    });
                },
                success: function(data) {
                    $parent.find('.show-comment-container').removeClass('hidden').empty().append(data);
                    Core.unblockUI($parent);
                },
                error: function() {
                    alert('Error');
                }
            });            
        });
        <?php
        if (issetParam($this->dataGridOptionData['DRILL_CLICK_FNC'])) {
        ?>
            $('#objectdatagrid-<?php echo $this->dataViewId; ?>').on('dblclick', '.postview-listtheme-row', function(){
                var $this = $(this);
                $this.trigger('click');
                <?php echo $this->dataGridOptionData['DRILL_CLICK_FNC']; ?>
            });
        <?php
        } ?>    
    });
    
    function mdCommentCallback_<?php echo $this->refStructureId ?>(total, id) {
        if (total === '') {
            $('#objectdatagrid-<?php echo $this->dataViewId; ?>').find('div[data-row-dataid="'+id+'"]').find('.show-comment-count').text(Number($('#objectdatagrid-<?php echo $this->dataViewId; ?>').find('div[data-row-dataid="'+id+'"]').find('.show-comment-count').text()) - 1);
        } else {
            $('#objectdatagrid-<?php echo $this->dataViewId; ?>').find('div[data-row-dataid="'+id+'"]').find('.show-comment-count').text(total);
        }
    }
    
    function postviewShowImage(images) {
        var $dialogName = "dialog-postview-image";
        if (!$("#" + $dialogName).length) {
          $('<div id="' + $dialogName + '"></div>').appendTo("body");
        }
        var $dialog = $("#" + $dialogName), imagesString = '', addonClass = '';
        
        for (var i=0; i < images.length; i++) {
            addonClass = i ? ' mt12' : '';
            imagesString += '<img class="img-fluid'+addonClass+'" style="" src="'+images[i]+'" alt="row photo">';
        }

        $dialog.empty().append(imagesString);
        $dialog.dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: images.length > 1 ? "Нийт "+images.length+" зураг" : "Зураг харах",
          width: 800,
          height: "auto",
          modal: true,
          closeOnEscape: true,
          open: function () {
          },
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: plang.get("close_btn"),
              class: "btn btn-sm blue-madison",
              click: function () {
                $dialog.dialog("close");
              },
            },
          ],
        });
        $dialog.dialog("open");        
    }
</script>