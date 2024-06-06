<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="col-md-12">
  <div class="card light shadow">	
    <div class="card-header card-header-no-padding header-elements-inline">
      <div class="card-title"><?php echo $this->title; ?></div>
      <div class="header-elements">
        <div class="list-icons">
            <a class="list-icons-item" data-action="collapse"></a>
            <a class="list-icons-item" data-action="fullscreen"></a>
        </div>
      </div>
    </div>
    <div class="card-body form" id="mainRenderDiv">
      <div class="m-0 no-padding">
        <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'contentUiForm', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
        <div class="row" id="contentUi">
          <div class="col-md-12 center-sidebar">
            <div class="col-md-7">
              <div class="form-body">
                <div class="form-group row fom-row icon-dark mb0">
                  <div class="input-icon input-icon-sm">
                    <i class="fa fa-tag"></i> 
                    <?php
                    echo Form::text(
                            array(
                                'name' => 'layoutCode',
                                'id' => 'layoutCode',
                                'class' => 'form-control form-control-sm border-0 focus-border-grey',
                                'required' => 'required',
                                'value' => isset($this->content['LAYOUT_CODE']) ? $this->content['LAYOUT_CODE']
                                            : '',
                                'placeholder' => 'Код'
                            )
                    );
                    ?>
                  </div>
                </div>
                <div class="form-group row fom-row">
                  <div class="col-md-12">
                      <?php
                      echo Form::textArea(
                              array(
                                  'name' => 'layoutName',
                                  'id' => 'layoutName',
                                  'class' => 'form-control input-text-lg border-0',
                                  'required' => 'required',
                                  'style' => 'height: 65px',
                                  'value' => isset($this->content['LAYOUT_NAME']) ? $this->content['LAYOUT_NAME']
                                              : '',
                                  'placeholder' => 'Нэр'
                              )
                      );
                      ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="clearfix w-100"></div><hr>
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
                                'onclick' => 'contentUi.createLayout();'
                            )
                    );
                    ?>
                </div>
              </div>
            </div>
          </div>
          <div class="right-sidebar" data-status="opened">
            <div class="stoggler sidebar-right">
              <span style="display: none;" class="fa fa-chevron-right">&nbsp;</span> 
              <span style="display: block;" class="fa fa-chevron-left">&nbsp;</span>
            </div>
            <div class="right-sidebar-content">
              <div class="tabbable-line">
                <ul class="nav nav-tabs">
                  <li class="nav-item">
                    <a data-toggle="tab" class="nav-link active" href="#tab_additional" aria-expanded="true">Нэмэлт мэдээлэл</a>
                  </li>
                </ul>
                <div class="tab-content">
                  <div id="tab_additional" class="tab-pane active">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="panel panel-default bg-inverse">
                          <table class="table sheetTable">
                            <tbody>
                              <tr class="isInsert">
                                <td class="left-padding">Мөр:</td>
                                <td class="simplewidth">
                                    <?php
                                    echo Form::text(
                                            array(
                                                'name' => 'rowCount',
                                                'id' => 'rowCount',
                                                'maxlength' => 2,
                                                'required' => 'required',
                                                'value' => isset($this->content['ROW_COUNT']) ? $this->content['ROW_COUNT']
                                                            : '',
                                                'class' => 'form-control form-control-sm border-0 focus-border-grey'
                                            )
                                    );
                                    ?>
                                </td>
                              </tr>
                              <tr class="isInsert">
                                <td class="left-padding">Багана:</td>
                                <td class="simplewidth">
                                    <?php
                                    echo Form::text(
                                            array(
                                                'name' => 'colCount',
                                                'id' => 'colCount',
                                                'maxlength' => 2,
                                                'required' => 'required',
                                                'value' => isset($this->content['COL_COUNT']) ? $this->content['COL_COUNT']
                                                            : '',
                                                'class' => 'form-control form-control-sm border-0 focus-border-grey'
                                            )
                                    );
                                    ?>
                                </td>
                              </tr>
                              <tr>
                                <td class="left-padding">Суурь өнгө:</td>
                                <td class="simplewidth">
                                    <?php
                                    echo Form::hidden(
                                            array(
                                                'name'  => 'bgColor',
                                                'id'    => 'bg-color',
                                                'type'  => 'hidden',
                                                'value' => isset($this->content['BG_COLOR']) ? $this->content['BG_COLOR'] : '',
                                                'class' => 'form-control form-control-sm border-0 focus-border-grey select-color bgColor'
                                            )
                                    );
                                    ?>
                                  <input type="text" id="bgColorSpectrum">
                                </td>
                              </tr>
                              <tr>
                                <td class="left-padding">Хүрээ:</td>
                                <td class="simplewidth">
                                    <?php
                                    echo Form::text(
                                            array(
                                                'name' => 'borderWidth',
                                                'id' => 'borderWidth',
                                                'value' => isset($this->content['BORDER_WIDTH']) ? $this->content['BORDER_WIDTH']
                                                            : '',
                                                'class' => 'form-control'
                                            )
                                    );
                                    ?>
                                </td>
                              </tr>
                              <tr>
                                <td class="left-padding">Суурь зураг:</td>
                                <td class="simplewidth">
                                  <div class="col-lg-7">
                                    <span class="btn btn-sm green fileinput-button" id="fileToSave">
                                      <span><i class="icon-plus3 font-size-12"></i></span>
                                      <input type="file" name="bgImage" id="bgImage">
                                    </span>
                                  </div>
                                </td>
                              </tr>
                              <tr class="inner-tds">
                                <td class="left-padding">Хүрээ /дээд/:</td>
                                <td class="simplewidth">
                                    <?php
                                    echo Form::text(
                                            array(
                                                'name' => 'borderTop',
                                                'id' => 'border-top',
                                                'value' => '',
                                                'class' => 'form-control inner-borders'
                                            )
                                    );
                                    ?>
                                </td>
                              </tr>
                              <tr class="inner-tds">
                                <td class="left-padding">Хүрээ /зүүн/:</td>
                                <td class="simplewidth">
                                    <?php
                                    echo Form::text(
                                            array(
                                                'name' => 'borderLeft',
                                                'id' => 'border-left',
                                                'value' => '',
                                                'class' => 'form-control inner-borders'
                                            )
                                    );
                                    ?>
                                </td>
                              </tr>
                              <tr class="inner-tds">
                                <td class="left-padding">Хүрээ /доод/:</td>
                                <td class="simplewidth">
                                    <?php
                                    echo Form::text(
                                            array(
                                                'name' => 'borderBottom',
                                                'id' => 'border-bottom',
                                                'value' => '',
                                                'class' => 'form-control inner-borders'
                                            )
                                    );
                                    ?>
                                </td>
                              </tr>
                              <tr class="inner-tds">
                                <td class="left-padding">Хүрээ /баруун/:</td>
                                <td class="simplewidth">
                                    <?php
                                    echo Form::text(
                                            array(
                                                'name' => 'borderRight',
                                                'id' => 'border-right',
                                                'value' => '',
                                                'class' => 'form-control inner-borders'
                                            )
                                    );
                                    ?>
                                </td>
                              </tr>
                              <tr class="inner-tds">
                                <td class="left-padding">Хүрээний өнгө /нүд/:</td>
                                <td class="simplewidth">
                                    <?php
                                    echo Form::hidden(
                                            array(
                                                'name' => 'borderColor',
                                                'id' => 'border-color',
                                                'value' => '',
                                                'class' => 'form-control inner-borders select-color borderColor'
                                            )
                                    );
                                    ?>
                                  <input type="text" id="borderColorSpectrum">
                                </td>
                              </tr>
                              <tr class="inner-tds">
                                <td class="left-padding">Суурь өнгө /нүд/:</td>
                                <td class="simplewidth">
                                    <?php
                                    echo Form::hidden(
                                            array(
                                                'name' => 'backgroundColor',
                                                'id' => 'background-color',
                                                'value' => '',
                                                'class' => 'form-control inner-borders select-color backgroundColor'
                                            )
                                    );
                                    ?>
                                  <input type="text" id="cellBgColorSpectrum">
                                </td>
                              </tr>
                              <tr class="inner-tds">
                                <td class="left-padding">Гарчиг:</td>
                                <td class="simplewidth">
                                    <?php
                                    echo Form::text(
                                            array(
                                                'name' => 'caption',
                                                'id' => 'caption',
                                                'value' => '',
                                                'class' => 'form-control inner-borders'
                                            )
                                    );
                                    ?>
                                </td>
                              </tr>
                              <tr>
                                <td class="left-padding">Арилгах:</td>
                                <td class="simplewidth">
                                  <div class="col-lg-7">
                                    <span class="btn btn-sm blue" id="exitsTableRender">
                                      <span><i class="fa fa-trash"></i></span>
                                    </span>
                                  </div>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div> 
              <div class="sidebarDetailSection"></div>
            </div>
          </div>
        </div>   
        <?php echo Form::close(); ?>    
      </div>
    </div>   
  </div>
</div>
<script type="text/javascript">
    var layoutId            = <?php echo isset($this->content['LAYOUT_ID']) ? $this->content['LAYOUT_ID'] : 0 ?>,
        cellArrayFromRender = <?php echo isset($this->cellArray) ? json_encode($this->cellArray) : 0 ?>;

    jQuery(document).ready(function() {
      contentUi.init();
      $(document).keydown(function(e) {
        if(e.keyCode === 27){
          $('#contentLayout').removeClass('card-fullscreen');
          $("#fullScreen").html('Full screen').removeClass('fullScreen');
        }
      });
    });

</script>
