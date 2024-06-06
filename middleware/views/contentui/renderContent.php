<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="col-md-12">
  <div class="card light shadow">
    <div class="card-header card-header-no-padding header-elements-inline">
      <div class="caption buttons">
        <?php
        echo html_tag('a',
                array(
            'href' => $this->metaBackLink,
            'class' => 'btn btn-circle btn-secondary card-subject-btn-border mr10'
                ), '<i class="icon-arrow-left7"></i>', $this->isBackLink
        );
        ?> 
      </div>
      <div class="card-title">
        <span class="caption-subject font-weight-bold uppercase card-subject-blue">
          Динамик контент 
        </span>
        <span class="caption-subject font-weight-bold text-uppercase text-gray2">editor</span>
      </div>
      <div class="header-elements">
        <div class="list-icons">
            <a class="list-icons-item" data-action="collapse"></a>
            <a class="list-icons-item" data-action="fullscreen"></a>
        </div>
      </div>  
    </div>
    <div class="card-body form" id="mainRenderDiv">
      <div class="m-0 no-padding">
        <?php
        echo Form::create(array('class' => 'form-horizontal', 'id' => 'contentUiForm',
            'method' => 'post',
            'enctype' => 'multipart/form-data'));
        ?>
        <div class="row" id="contentUi">
          <div class="col-md-9">

            <div class="clearfix w-100"></div>
            <div class="col-md-12">
              <div id="contentLayout">
                <div id="content-container" class="content-container">   
                  <?php
                  echo isset($this->generateLayout) ? $this->generateLayout : '';
                  ?>
                </div>
              </div>

              <div id="colorPickerModal" class="modal fade" role="dialog">
                <div class="modal-dialog" style="width: 17%;">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Өнгө сонгох</h4>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Хаах</button>
                    </div>
                  </div>
                </div>
              </div>
            </div> 
            <div class="clearfix w-100"></div>
            <div class="form-actions mt20" style="border-top: none;">
              <div class="row">
                <div class="col-md-offset-4 col-md-8">
                  <?php
                  echo Form::button(
                          array(
                              'class' => 'btn btn-success',
                              'value' => $this->lang->line('save_btn'),
                              'onclick' => 'contentUi.setContentMetaData();'
                          )
                  );
                  ?>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="right-sidebar-content">
              <div class="tabbable-line">
                <ul class="nav nav-tabs">
                  <li class="nav-item">
                    <a data-toggle="tab" class="nav-link active" href="#tab_meta_list" aria-expanded="false">Хэрэглэгдэх мета</a>
                  </li>
                </ul>
                <div class="tab-content">
                  <div id="tab_meta_list" class="tab-pane active">
                    <div class="form-group row fom-row">
                      <div class="col-md-12">
                        <div id="metaDataList">
                          <?php
                          if (isset($this->metaDataList)) {
                            foreach ($this->metaDataList AS $metaData) :
                              ?>
                              <div style="position: relative; border: 1px solid #ccc; margin: 0 0 5px; padding: 5px; cursor: pointer;" 
                                   id="<?php echo $metaData['META_DATA_ID'] ?>" class="draggable ui-draggable ui-draggable-handle">
                                     <i class="fa fa-arrows"></i> <?php echo $metaData['META_DATA_NAME'] ?>
                              </div>
                            <?php endforeach;
                          } ?>                            
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div> 
              <div class="sidebarDetailSection"></div>
            </div>
          </div>
          <?php
          echo Form::hidden(
                  array(
                      'name' => 'layoutId',
                      'id' => 'layoutId',
                      'value' => isset($this->content['LAYOUT_ID']) ? $this->content['LAYOUT_ID'] : '',
                  )
          );
          ?>
          <?php
          echo Form::hidden(
                  array(
                      'name' => 'metaDataId',
                      'id' => 'metaDataId',
                      'value' => isset($this->content['META_DATA_ID']) ? $this->content['META_DATA_ID'] : '',
                  )
          );
          ?>
        <?php echo Form::close(); ?>    
        </div>   
      </div>
    </div>   
  </div>
</div>

<script>
  var layoutId=<?php echo isset($this->content['LAYOUT_ID']) ? $this->content['LAYOUT_ID'] : 0 ?>,
   cellArrayFromRender=<?php echo json_encode($this->cellArray) ?>;
  jQuery(document).ready(function(){
    $('.page-sidebar-wrapper').remove();
    $('.page-content').attr('style', 'margin-left:0px;');
    contentUi.init();
    // ESC
    $(document).keydown(function(e){
      if(e.keyCode === 27){
        $('#contentLayout').removeClass('card-fullscreen');
        $("#fullScreen").html('Full screen').removeClass('fullScreen');
      }
    });
  });

</script>