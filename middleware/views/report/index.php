<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="card light shadow" id="reportEditDiv">
  <div class="card-header card-header-no-padding header-elements-inline">
    <div class="card-title">
      <i class="fa fa-bar-chart"></i>
      <span class="caption-subject">Тайлангийн өгөгдөл</span>
    </div>
    <div class="caption buttons ml10">
      <?php echo HTML2PDF_parsingHtml::anchor('rmreport/report', '<i class="fa"></i> ' . "Буцах" . '',
              array('title' => "Цуцлах", 'class' => 'btn btn-circle btn-sm default cancelSaleRefund', 'onclick' => ''));
      ?>
      <?php
      echo HTML2PDF_parsingHtml::anchor('javascript:;', '<i class="fa fa-save"></i> ' . "Хадгалах" . '',
              array('title' => "Хадгалах", 'class' => 'btn btn-circle btn-sm btn-success saveSaleRefund',
          'onclick' => 'saveReportModel()'));
      ?>
    </div>
    <div class="tools">
      <a title="" data-original-title="" href="javascript:;" class="reload"></a>
    </div>
  </div>

  <div class="card-body xs-form"> 
<?php echo Form::create(array('role' => 'form', 'id' => 'mainForm', 'method' => 'POST', 'class' => "form-horizontal")) ?>
<?php echo Form::hidden(array('name' => 'modelId', 'id' => 'modelId', 'value' => $this->row['modelId'],
    'class' => 'form-control'));
?>
    <fieldset>
      <legend>Ерөнхий мэдээлэл</legend>
      <div class="row column-container-row">

        <div class="form-body">
          <div class="row column-container-col">

            <div class="col-md-4 form-group row fom-row">
                <?php echo Form::label(array('class' => 'col-md-4 col-form-label', "text" => 'Хүснэгт')); ?>   
              <div class="col-md-8">
<?php
echo Form::select(array('name' => 'tableName', 'id' => 'tableName',
    'data' => $this->tables, 'op_value' => 'id', 'value' => $this->row['viewName'], 'op_text' => 'name',
    'class' => 'form-control', 'onchange' => 'reloadColumns()'));
?>
              </div>
            </div>

            <div class="col-sm-4 form-group row fom-row">
<?php echo Form::label(array('class' => 'col-sm-4 col-form-label', "text" => 'Тайлангийн нэр')); ?>   
              <div class="col-md-8">
                <div class="input-group">
                                        <?php
                    echo Form::text(array('name' => 'reportModelName', 'id' => 'reportModelName',
                        'value' => $this->row['modelName'], 'class' => 'form-control'));
                    ?>
                  <span class="input-group-btn">
                    <button type="button" class="btn blue" onclick="showReportModelList();">
                      <i class="fa fa-search"></i>
                    </button>
                  </span>
                </div>
              </div>
            </div>
            <div class="col-sm-4 form-group row fom-row">
<?php echo Form::label(array('class' => 'col-sm-4 col-form-label', "text" => 'Өгөгдөл харагдах эсэх')); ?>   
              <div class="col-md-8">
                <input type="checkbox" id="isPreviewData" name="isPreviewData" onchange="generatePreview()" value="false">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="row column-container-row">
            <div class="col-md-3 column-container-col">
              <fieldset>
                <legend>Хүснэгтийн багана</legend>
                <div class="column-container">
                  <div class="dd" id="nestable_field_list">
                    <?php
                    if ($this->row['modelId'] == 0) {
                      echo '<ol class="dd-list"></ol>';
                    } else {
                      if(sizeof($this->row['viewCols']) !== 0){
                        echo '<ol class="dd-list">';
                        foreach ($this->row['viewCols'] as $key) {
                          echo
                          '<li class="dd-item" data-field="' . $key['id'] . '" data-type="' . $key['fieldType'] . '" data-isVisible="'.$key['isVisible'].'">
                                                      <div class="dd-handle dd3-handle">
                                                      </div>
                                                      <div class="dd3-content">
                                                          <div class="row">
                                                              <div align="left" class="col-sm-8">' . $key['id'] . '</div>
                                                                  <div align="right" class="col-sm-4">
                                                                      <a class="btn" style="margin-top: -8px" onclick="showDetail(this)"><i class="fa fa-link"></i></a>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </li>';
                        }
                        echo '</ol>';
                      } else {
                        echo '<ol class="dd-empty"></ol>';
                      }
                    }
                    ?>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="col-md-3  column-container-col">
              <fieldset>
                <legend>Мөр</legend>
                <div class="column-container">
                  <div class="dd" id="nestable_row_list">
                    <?php
                    if ($this->row['modelId'] == 0) {
                      echo '<ol class="dd-empty"></ol>';
                    } else {
                      echo '<ol class="dd-list">';
                      foreach ($this->row['rows'] as $key) {
                        echo
                        '<li class="dd-item" data-field="' . $key['field'] . '" data-type="' . $key['colType'] . '" data-isVisible="'.$key['isVisible'].'">
                                                    <div class="dd-handle dd3-handle">
                                                    </div>
                                                    <div class="dd3-content">
                                                        <div class="row">
                                                            <div align="left" class="col-sm-8">' . $key['field'] . '</div>
                                                                <div align="right" class="col-sm-4">
                                                                    <a class="btn" style="margin-top: -8px" onclick="showDetail(this)"><i class="fa fa-link"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>';
                      }
                      echo '</ol>';
                    }
                    ?>

                  </div>
                </div>
              </fieldset>
            </div>
            <div class="col-md-3  column-container-col">
              <fieldset>
                <legend>Факт</legend>
                <div class="column-container">
                  <div class="dd" id="nestable_column_list">
                    <?php
                    if ($this->row['modelId'] == 0) {
                      echo '<ol class="dd-empty"></ol>';
                    } else {

                      echo '<ol class="dd-list">';
                      foreach ($this->row['cols'] as $key) {
                        echo
                        '<li class="dd-item" data-field="' . $key['field'] . '" data-type="' . $key['colType'] . '" data-isVisible="'.$key['isVisible'].'">
                                                    <div class="dd-handle dd3-handle">
                                                    </div>
                                                    <div class="dd3-content">
                                                        <div class="row">
                                                            <div align="left" class="col-sm-8">' . $key['field'] . '</div>
                                                                <div align="right" class="col-sm-4">
                                                                    <a class="btn" style="margin-top: -8px" onclick="showDetail(this)"><i class="fa fa-link"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>';
                      }
                      echo '</ol>';
                    }
                    ?>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="col-md-3  column-container-col">
              <fieldset>
                <legend>Багана</legend>
                <div class="column-container">
                  <div class="dd" id="nestable_fact_list">
                    <?php
                    if ($this->row['modelId'] == 0) {
                      echo '<ol class="dd-empty"></ol>';
                    } else {
                      if (sizeof($this->row['facts']) == 0) echo '<ol class="dd-empty"></ol>';
                      else {
                        echo '<ol class="dd-list">';
                        foreach ($this->row['facts'] as $key) {
//                          var_dump($key);
//                          if (isset($key['colType'])) {
                            echo '<li class="dd-item" data-field="' . $key['field'] . '" >
                                                        <div class="dd-handle dd3-handle"></div>
                                                        <div class="dd3-content">
                                                            <div class="row">
                                                                <div align="left" class="col-sm-8">' . $key['field'] . '</div>
                                                                <div align="right" class="col-sm-4">
                                                                    <a class="btn" style="margin-top: -8px" onclick="showDetail(this)"><i class="fa fa-link"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        </li>';
//                          }
                        }
                        echo '</ol>';
                      }
                    }
                    ?>
                  </div>
                </div>
              </fieldset>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="row column-container-row">
            <div class="col-md-12 column-container-col" style="padding-right: 20px; height: 100%;">
            <fieldset>
              <legend>Шүүлт</legend>
              <div class="row" style="margin-left: 0px;margin-right: 0px;">
                <div class="col-sm-12 form-group row fom-row" style="margin-bottom: 5px;">  
                  <div class="btn-toolbar float-left">
                    <div class="btn-group btn-group-solid">
<?php
echo HTML2PDF_parsingHtml::anchor('javascript:;',
        '<i class="icon-plus3 font-size-12"></i> ' . $this->lang->line('add_btn'),
        array('class' => 'btn btn-sm blue', 'onclick' => "addFilterDetail()"));
?>
                    </div>
                  </div>
                </div>
<!--                        <textarea id="txt_filter" class="normal-color" rows="10" style="height: 100%; width: 100%;" ></textarea>
<div class="dd" id="nestable_filter_list" style="height: 100%">
<ol class="dd-list"></ol>
</div>-->
              </div>  
              <div class="row" style="margin-left: 0px;margin-right: 0px;">
                <div class="dd" id="nestable_filter_list">
                  <?php
                  if ($this->row['modelId'] == 0) {
                    echo '<ol class="dd-empty"></ol>';
                  } else {
                    if (sizeof($this->row['filters']) == 0) echo '<ol class="dd-list"></ol>';
                    else {
                      echo '<ol class="dd-list">';

                      // echo $this->row['filters'][0];

                      foreach ($this->row['filters'] as $key) {
//                        var_dump($key);
                        echo
                        '<li class="dd-item" data-field="' . $key['field'] . '" data-title="' . $key['title'] . '" data-metadata="' . $key['metadata'] . '" data-isrequired="' . $key['isrequired'] . '" data-filterdefaultvalue="' . $key['filterdefaultvalue'] . '">
                                                <div class="dd-handle dd3-handle">
                                                </div>
                                                <div class="dd3-content">
                                                    <div class="row">
                                                        <div align="left" class="col-sm-8">' . $key['field'] . '-' . $key['title'] . '</div>
                                                            <div align="right" class="col-sm-4">
                                                                <a class="btn" style="margin-top: -8px" onClick="removeFilter(this)"><i class="fa fa-minus-circle"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                </li>';
                      }
                      echo '</ol>';
                    }
                  }
                  ?>

                </div>
              </div>
            </fieldset>
          </div>
          </div>
        </div>
        <!--<div class="row">-->
          <div class='col-md-12'>
            <div class="col-md-12 column-container-col">
              <fieldset>
                <legend><?php echo $this->lang->line('metadata_view'); ?></legend>
                <div class="row" style="margin-left: 0px;margin-right: 0px;">
                  <div class="col-sm-12 form-group row fom-row" style="margin-bottom: 5px;">  
                    <div class="btn-toolbar float-left">
                      <div class="btn-group btn-group-solid">
                        <?php
                        echo HTML2PDF_parsingHtml::anchor('javascript:;',
                                '<i class="fa fa-edit"></i> ' . "Толгойн мэдээлэл засах",
                                array('class' => 'btn btn-sm blue', 'onclick' => "showHeaderEdit()"));
                        ?>
                      </div>
                      <div class="btn-group btn-group-solid">
<?php
echo HTML2PDF_parsingHtml::anchor('javascript:;', '<i class="fa fa-edit"></i> ' . "Хөлийн мэдээлэл засах",
        array('class' => 'btn btn-sm green', 'onclick' => "showFooterEdit()"));
?>
                      </div>

                    </div>
                  </div>
                </div>  
                <div class="table-scrollable">
                  <div id="reportHeader">
                  </div>
                  <div id="prvReportSource">
                  </div>
                  <div id="reportFooter">
                  </div>
                </div>
              </fieldset>
            </div>
          </div>
          
        <!--</div>-->
        <div class="form-actions mt15 form-actions-btn">
          <div class="row">
            <div class="col-md-offset-3 col-md-9">
<?php echo HTML2PDF_parsingHtml::anchor('rmreport/report', '<i class="fa"></i> ' . "Буцах" . '',
              array('title' => "Цуцлах", 'class' => 'btn btn-circle btn-sm default cancelSaleRefund', 'onclick' => ''));
?>
<?php
echo HTML2PDF_parsingHtml::anchor('javascript:;', '<i class="fa fa-save"></i> ' . "Хадгалах" . '',
        array('title' => "Хадгалах", 'class' => 'btn btn-circle btn-sm btn-success saveSaleRefund', 'onclick' => 'saveReportModel()'));
?>
            </div>
          </div>
        </div>
<?php echo Form::close(); ?>
    </fieldset>
  </div>

  <div class="hidden" >
    <textarea id="nestable-field-list-output"></textarea>
    <textarea id="nestable-row-list-output"></textarea>
    <textarea id="nestable-column-list-output"></textarea>
    <textarea id="nestable-filter-list-output"></textarea>
    <textarea id="nestable-fact-list-output"></textarea>
  </div>
  <div class="hidden">
    <div id="detailDialog">
      <form id="detailForm" method="POST" role="form">
        <div class="panel-body">
          <div class="form-body">
            <div class="row dialog">
              <div class="col-sm-12 form-group row fom-row">
                <label class="col-sm-4 col-form-label" for="fieldName">Талбарын нэр:</label>   
                <div class="col-sm-8">
                  <input id="fieldName" name="fieldName" class="form-control" type="text" value="Талбар">
                </div>
              </div>
            </div>
            <div class="row dialog">  
              <div class="col-sm-12 form-group row fom-row">
                <label class="col-sm-4 col-form-label" for="fieldHeader">Баганы нэр:</label>   
                <div class="col-sm-8">
                  <input id="fieldHeader" name="fieldHeader" class="form-control" type="text" value="Баганы нэр">
                </div>
              </div>
            </div>
            <div class="row dialog">
              <div class="col-sm-12 form-group row fom-row">
                <label class="col-sm-4 col-form-label" for="isVisible">Харагдах эсэх:</label>   
                <div class="col-sm-8">
                  <input type="checkbox" id="isVisible" name="isVisible"  value="true" checked>
                </div>
              </div>
            </div>

            <div class="row dialog">
              <div class="col-sm-12 form-group row fom-row">
                <label class="col-sm-4 col-form-label" for="fieldFormat">Хөлийн утга</label>   
                <div class="col-sm-8">
                  <select id="fieldFormat" name="fieldFormat" class="form-control" value=":fieldFormat">
                    <option value="">- Сонгох -</option>
                    <option value="SUM">Нийлбэр</option>
                    <option value="COUNT">Мөрийн тоо</option>
                    <option value="AVG">Дундаж</option>
                  </select>  
                </div>
              </div>
            </div>
            <div class="row dialog">
              <div class="col-sm-12 form-group row fom-row">
                <label class="col-sm-4 col-form-label" for="fieldAlign">Зэрэгцүүлэлт</label>   
                <div class="col-sm-8">
                  <select id="fieldAlign" name="fieldAlign" class="form-control" value="left">
                    <option value="">- Сонгох -</option>
                    <option value="left">Зүүн</option>
                    <option value="right">Баруун</option>
                    <option value="center">Төв</option>
                  </select>  
                </div>
              </div>
            </div>
            <div class="row dialog">
              <div class="col-sm-12 form-group row fom-row">
                <label class="col-sm-4 col-form-label" for="fieldMask">Төрөл</label>   
                <div class="col-sm-8">
                  <select id="fieldMask" name="fieldMask" class="form-control" value="text">
                    <option value="">- Сонгох -</option>
                    <option value="text">Текст</option>
                    <option value="num">Тоо</option>
                    <option value="date">Огноо</option>
                    <option value="image">Зураг</option>
                  </select>  
                </div>
              </div>
            </div>
            <!-- Order хийх-->
            <div class="row dialog">
              <div class="col-sm-12 form-group row fom-row">
                <label class="col-sm-4 col-form-label" for="fieldOrder">Дараалалд оруулах</label>
                <div class="col-sm-8">
                  <select id="fieldOrder" name="fieldOrder" class="form-control" value="text">
                    <option value="">- Сонгох -</option>
                    <option value="asc">A-Z/1-9</option>
                    <option value="desc">Z-A/9-1</option>
                  </select>
                </div>
              </div>
            </div>
            <!-- MERGE хийх -->
            <div class="row dialog">
              <div class="col-sm-12 form-group row fom-row">
                <label class="col-sm-4 col-form-label" for="isMerge">Ижил утгаар нэгтгэх эсэх:</label>   
                <div class="col-sm-8" style="padding-top: 10px;">
                  <div class="checkbox-list">
                  <?php
                    echo Form::checkbox(
                            array(
                                'name' => 'isMerge',
                                'id' => 'isMerge',
                                'value' => '0'
                            )
                    );
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="hidden" >
    <div id="detailFilterDialog">
      <form id="detailFilterForm" method="POST" role="form">
        <div class="panel-body">
          <div class="form-body">
            <div class="row dialog">
              <div class="col-sm-12 form-group row fom-row">
                <div class="col-sm-12 input-group ">
                  <label class="col-sm-3 col-form-label">Талбар:</label>   
                  <div class="col-sm-9">
                    <select id="cmbfieldNames" name="cmbfieldNames" class="form-control">
                      <option value="">- Сонгох -</option>
                    </select>  
                  </div>
                </div>
              </div>
            </div>
            <div class="row dialog">
              <div class="col-sm-12 form-group row fom-row">
                <label class="col-sm-4 col-form-label" for="fieldHeader">Баганы нэр:</label>   
                <div class="col-sm-8">
                  <input id="filterFieldHeader" name="filterFieldHeader" class="form-control" type="text" value="Баганы нэр">
                </div>
              </div>
            </div>
            <div class="row dialog">
              <div class="col-sm-12 form-group row fom-row">
                <label class="col-sm-4 col-form-label" for="filterdefaultvalue">Үндсэн утга:</label>
                <div class="col-sm-8">
                  <input id="filterdefaultvalue" name="filterdefaultvalue" class="form-control" type="text" value="">
                </div>
              </div>
            </div>
            <div class="row dialog">
              <div class="col-sm-12 form-group row fom-row">
                <label class="col-sm-4 col-form-label" for="isrequired">Заавал бөглөх эсэх:</label>   
                <div class="col-sm-8">
                  <div class="checkbox-list">
                  <?php
                    echo Form::checkbox(
                            array(
                                'name' => 'isrequired',
                                'id' => 'isrequired',
                                'value' => '0'
                            )
                    );
                    ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="row dialog">
              <div class="col-sm-12 form-group row fom-row">
                <div class="col-sm-12 input-group">
                  <label class="col-sm-3 col-form-label">Мета</label>   
                  <div class="col-sm-9">
                    <div class="input-group">
                      <input type="hidden" id="metaDataId" name="metaDataId">                                                
                      <input type="text" id="metaDataName" name="metaDataName" class="form-control" readonly="readonly" required="required">                                                <span class="input-group-btn">
                        <button type="button" class="btn blue" onclick="selectMetaData();">
                          <i class="fa fa-search"></i>
                        </button>                                         
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </form>
    </div>
  </div>


  <div class="hidden" >
    <div id="headerEditDialog">
<!--      <div class="col-md-2">
        <div class="childs">
          <h4>Авах утгууд</h4>
          <ul id="childList">
                <?php
                foreach ($this->childs as $key => $value) {
                  ?>
              <li>
                <div class="metaData h-card" data-metaData="<?php echo $key ?>" draggable="true" tabindex="0">
                  <?php
                  if ($value == 'username') {
                    echo 'Хэрэглэгчийн нэр';
                  } else if ($value == 'departmentname') {
                    echo 'Хэлтэсийн нэр';
                  } else if ($value == 'lastname') {
                    echo 'Овог';
                  } else if ($value == 'firstname') {
                    echo 'Нэр';
                  } else if ($value == 'rolename') {
                    echo 'Албан тушаал';
                  } else if ($value == 'date') {
                    echo 'Огноо';
                  } else {
                    echo $value;
                  }
                  ?>
                </div>
              </li>   
  <?php
}
?>
          </ul>
        </div>
      </div>-->
      <div class="col-md-12">
        <form id="headerEditForm" method="POST" role="form">
          <div class="panel-body">
            <div class="form-body">
              <textarea id="headerEditor" name="headerEditor"></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="hidden" >
    <div id="footerEditDialog">
<!--      <div class="col-md-2">
        <div class="childs">
          <h4>Авах утгууд</h4>
          <ul id="childListFoot">
                <?php
                foreach ($this->childs as $key => $value) {
                  ?>
              <li>
                <div class="metaData h-card" data-metaData="<?php echo $key ?>" draggable="true" tabindex="0">
                  <?php
                  if ($value == 'username') {
                    echo 'Хэрэглэгчийн нэр';
                  } else if ($value == 'departmentname') {
                    echo 'Хэлтэсийн нэр';
                  } else if ($value == 'lastname') {
                    echo 'Овог';
                  } else if ($value == 'firstname') {
                    echo 'Нэр';
                  } else if ($value == 'rolename') {
                    echo 'Албан тушаал';
                  } else if ($value == 'date') {
                    echo 'Огноо';
                  } else {
                    echo $value;
                  }
                  ?>
                </div>
              </li>   
  <?php
}
?>
          </ul>
        </div>
      </div>-->
      <div class="col-md-12">
        <form id="footerEditForm" method="POST" role="form">
          <div class="panel-body">
            <div class="form-body">
              <textarea id="footerEditor" name="footerEditor"></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="reportModelListDialog">
  </div>
</div>
<style type="text/css">
  #reportEditDiv .column-container {
    height: 400px;
    overflow-y: scroll;
    padding-right: 10px;
  }
  #reportEditDiv .form-group {
    margin-bottom: 0px;
  }
  #reportEditDiv .column-container-col{
    padding-left: 5px;
    padding-right: 5px;
  }
  #reportEditDiv .column-container-row {
    padding-left: 15px;
    padding-right: 0px;
  }
  #reportEditDiv .card {
    margin-bottom: 5px;
  }
  #reportEditDiv .error-color
  {
    color: red;
  }
  #reportEditDiv .normal-color
  {
    color: black;
  }
  #reportEditDiv #childList {
    list-style-type: none;
    margin: 0 !important;
    padding: 0;
  }
  #reportEditDiv #childList li {
    background: #FAFAFA;
    margin-bottom: 1px;
    height: 40px;
    line-height: 40px;
    cursor: pointer;
    padding-left: 10px;
  }
  #reportEditDiv #childList li:nth-child(2n) {
    background: #F3F3F3;
  }
  #reportEditDiv #childList li:hover {
    background: #FFFDE3;
    border-left: 5px solid #DCDAC1;
    margin-left: -5px;
  }

  #reportEditDiv #childListFoot {
    list-style-type: none;
    margin: 0 !important;
    padding: 0;
  }
  #reportEditDiv #childListFoot li {
    background: #FAFAFA;
    margin-bottom: 1px;
    height: 40px;
    line-height: 40px;
    cursor: pointer;
    padding-left: 10px;
  }
  #reportEditDiv #childListFoot li:nth-child(2n) {
    background: #F3F3F3;
  }
  #reportEditDiv #childListFoot li:hover {
    background: #FFFDE3;
    border-left: 5px solid #DCDAC1;
    margin-left: -5px;
  }
</style>    
<script type="text/javascript">
  //    var dgPreviewReport = '#dgPreviewReport';
  var reportDataGrid;
  var detailDialog='#detailDialog';
  var detailFilterDialog='#detailFilterDialog';
  var headerEditDialog='#headerEditDialog';
  var footerEditDialog='#footerEditDialog';
  var reportModelListDialog='#reportModelListDialog';
  var sessionLastname='<?php echo SUBSTR(Session::get(SESSION_PREFIX . 'lastname'), 0, 6); ?>';
  var sessionFirstname='<?php echo Session::get(SESSION_PREFIX . 'firstname'); ?>';
  var sessionUsername='<?php echo Session::get(SESSION_PREFIX . 'firstname'); ?>';
  var sessionDepartmentname='<?php echo Session::get(SESSION_PREFIX . 'departmentname'); ?>';
  var sessionRolename='<?php echo Session::get(SESSION_PREFIX . 'rolename'); ?>';
  var width = $(window).width();
  var height = $(window).height();
  var datas=<?php
if ($this->row['modelId'] == 0) {
  echo ' []';
} else {
  $dts = array_merge($this->row['rows'], $this->row['cols']);
  $details = array_merge($dts, $this->row['facts']);
//  var_dump($dts);die;
  $datas = array();
  foreach ($details as $key) {
    // var_dump($key);
    array_push($datas,
            array(
        'fieldName' => isset($key['field']) ? $key['field'] : '',
        'fieldFormat' => isset($key['format']) ? $key['format'] : '',
        // 'viewOrder  ' => $key['viewOrder'],
        'fieldAlign' => isset($key['align']) ? $key['align'] : '',
        'fieldMask' => isset($key['mask']) ? $key['mask'] : '',
        'fieldHeader' => isset($key['title']) ? $key['title'] : '',
        'isVisible' => isset($key['isVisible']) ? $key['isVisible'] : '',
        'isMerge' => isset($key['isMerge']) ? $key['isMerge'] : '',
        'fieldOrder' => isset($key['order']) ? $key['order'] : '' 
    ));
  }
  echo json_encode($datas);
}
?>;
  var allColumns=<?php
if ($this->row['modelId'] == 0) {
  echo ' []';
} else {
  echo json_encode($this->row['viewCols']);
}
?>;
  
  var allFilterValues=<?php
if ($this->row['modelId'] == 0) {
  echo ' []';
} else {
  echo json_encode($this->row['allRowsCols']);
}
?>;
  var headerString=<?php
if ($this->row['modelId'] == 0) {
  echo '""';
} else {
  echo json_encode($this->row['headerHtml'] == null ? "" : $this->row['headerHtml']);
}
?>;

  var footerString=<?php
if ($this->row['modelId'] == 0) {
  echo '""';
} else {
  echo json_encode($this->row['footerHtml'] == null ? "" : $this->row['footerHtml']);
}
?>;
  $('#reportHeader').html(headerString);
  $('#reportFooter').html(footerString);
//  $("#isPreviewData").click(function(){
//    var typeCheck = document.getElementById("isPreviewData").checked;
//    alert(typeCheck);
//  if($("#isPreviewData").prop("checked", false)){
//    $("#isPreviewData").prop("checked", true);
//    $("#isPreviewData").val('true');
//  } else if($("#isPreviewData").prop("checked", true)){
//    $("#isPreviewData").prop("checked", false);
//    $("#isPreviewData").val('false');
//  }
//  });
    $('#isrequired').click(function(){
      if($('#isrequired').parent('span').hasClass('checked')){
        $('#isrequired').attr('checked', false);
        $('#isrequired').val('0');
      } else {
        $('#isrequired').attr('checked', true);
        $('#isrequired').val('1');
      }
    });
    
    $('#isMerge').click(function(){
      if($('#isMerge').parent('span').hasClass('checked')){
        $('#isMerge').attr('checked', false);
        $('#isMerge').val('0');
      } else {
        $('#isMerge').attr('checked', true);
        $('#isMerge').val('1');
      }
    });
    
  $(function(){
    var updateOutput=function(e)
    {
      var list=e.length ? e : $(e.target), output=list.data('output');
      if(window.JSON){
        output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
//                headerString = "";
        generatePreview();
      } else {
        output.val('JSON browser support required for this demo.');
      }
    };

    var updateFilterOutput=function(e)
    {
      var list=e.length ? e : $(e.target), output=list.data('output');
      if(window.JSON){
        output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
        generatePreview();
      } else {
        output.val('JSON browser support required for this demo.');
      }
    };

    $('#nestable_field_list').nestable({group: 1, maxDepth: 1}).on('change', updateOutput);
    $('#nestable_row_list').nestable({group: 1, maxDepth: 1}).on('change', updateOutput);
    $('#nestable_column_list').nestable({group: 1, maxDepth: 1}).on('change', updateOutput);
    $('#nestable_fact_list').nestable({group: 1, maxDepth: 1}).on('change', updateOutput);
    $('#nestable_filter_list').nestable({group: 2, maxDepth: 1}).on('change', updateFilterOutput);
    updateOutput($('#nestable_field_list').data('output', $('#nestable-field-list-output')));
    updateOutput($('#nestable_row_list').data('output', $('#nestable-row-list-output')));
    updateOutput($('#nestable_column_list').data('output', $('#nestable-column-list-output')));
    updateOutput($('#nestable_fact_list').data('output', $('#nestable-fact-list-output')));
    updateFilterOutput($('#nestable_filter_list').data('output', $('#nestable-filter-list-output')));
    //$(detailDialog).dialog();

    generatePreview();
  });
  function refreshColList()
  {
    var colList=$('#nestable_column_list').nestable('serialize');
   
    for(var i=0; i < colList.length; i++)
    {
      var exist=false;
      for(var j=0; j < datas.length; j++)
      {
        if(colList[i].field == datas[j].fieldName)
        {
          datas[j].viewOrder=i + 1;
          exist=true;
        }
      }
      if(exist == false)
      {
        var data={type: 'col', fieldName: colList[i].field, fieldAlign: 'left', fieldMask: 'text',
          viewOrder: i + 1, fieldType: colList[i].type, fieldFormat: colList[i].type == 'FLOAT' ?
                  'SUM' : 'COUNT', fieldHeader: '', isVisible: true, isMerge: '0'};
        datas.push(data);
      }
    }
  }

  function generateFilter()
  {
    var filter=document.getElementById('txt_filter').value;

    try{
      $("#nestable_filter_list").html("<ol class='dd-list'></ol>");
      SQLParser.parse('select * from d where ' + filter.replace('{', '').replace('}', ''));
      var filterString=filter;
      var reg=/[{]([a-zA-Z0-9]|[_]){1,50}[}]/;
      var words=filterString.match(reg);
      var params=[];
      while(words != null && words.length > 0)
      {
        params.push(words[0]);
        filterString=filterString.replace(words[0]);
        words=filterString.match(reg);
      }


      for(var i=0; i < params.length; i++)
      {
        addFilterFieldNode(params[i]);
      }

      document.getElementById('txt_filter').className='normal-color';
    }
    catch(err){
      document.getElementById('txt_filter').className='error-color';
    }

  }

  function refreshRowList()
  {
    var rowList=$('#nestable_row_list').nestable('serialize');
    var isMerge;
    for(var i=0; i < rowList.length; i++)
    {
      var exist=false;
      for(var j=0; j < datas.length; j++)
      {
        if(rowList[i].field == datas[j].fieldName)
        {
          datas[j].viewOrder=i + 1;
          exist=true;
        }
      }
      if(exist == false)
      {
        var data={type: 'row', fieldName: rowList[i].field, fieldAlign: 'left', fieldMask: 'text',
          viewOrder: i + 1, fieldType: rowList[i].type, fieldFormat: '', fieldHeader: '',
          isVisible: true, isMerge: '0'};
        datas.push(data);
      }
    }
  }

  function addFilterFieldNode(fieldName, fieldHeader, metaDataId, metaDataName, isrequired, filterdefaultvalue){
    $("#nestable_filter_list > ol").append("\
<li class='dd-item' data-field='" + fieldName +
              "' data-title='" + fieldHeader + "' data-metadata='" + metaDataId +
              "' data-isrequired='" + isrequired + "' data-filterdefaultvalue='" +
              filterdefaultvalue +
              "'>\n\
<div class='dd-handle dd3-handle'>\n\
</div>\n\
<div class='dd3-content'>\n\
    <div class='row'>\n\
        <div align='left' class='col-sm-8'>" + fieldName + "-" + fieldHeader + "</div>\n\
            <div align='right' class='col-sm-4'>\n\
                <a class='btn' style='margin-top: -8px' onClick='removeFilter(this)'><i class='fa fa-minus-circle'></i></a>\n\
            </div>\n\
        </div>\n\
    </div>\n\
</div>\n\
</li>");
  }

  function selectMetaData()
  {

    commonMetaDataGrid('single', 'MetaGroup', '');
  }

  function addFilter()
  {

  }

  function removeFilter()
  {

  }

  function selectableCommonMetaDataGrid(chooseType, elem, params){

    if(elem === 'MetaGroup'){
      var metaBasketNum=$('#commonBasketMetaDataGrid').datagrid('getData').total;
      if(metaBasketNum > 0){
        var rows=$('#commonBasketMetaDataGrid').datagrid('getRows');
        var row=rows[0];

        document.getElementById('metaDataId').value=row.META_DATA_ID;
        document.getElementById('metaDataName').value=row.META_DATA_NAME;
      }
    }
  }

  function addFieldNode(fieldName, fieldType, fieldFormat, fieldHeader){
    $("#nestable_field_list > ol").append("\
<li class='dd-item' data-field='" + fieldName + "' data-type='" + fieldType + "'>\n\
<div class='dd-handle dd3-handle'>\n\
</div>\n\
<div class='dd3-content'>\n\
    <div class='row'>\n\
        <div align='left' class='col-sm-8'>" + fieldName + "</div>\n\
            <div align='right' class='col-sm-4'>\n\
                <a class='btn' style='margin-top: -8px' onClick='showDetail(this)'><i class='fa fa-link'></i></a>\n\
            </div>\n\
        </div>\n\
    </div>\n\
</div>\n\
</li>");
  }
  function showReportModelList()
  {
    if(typeof reportDataGrid !== "undefined"){
        $(reportModelListDialog).dialog('open');
    } else {
      $(reportModelListDialog).empty();
      $.ajax({
        type: 'post',
        url: 'rmreport/reportList',
        dataType: "json",
        data: {
        },
        beforeSend: function(){
          Core.blockUI({
            animate: true
          });
        },
        success: function(data){
          $(reportModelListDialog).empty().html(data.Html);
          $(reportModelListDialog).dialog({
            autoOpen: false,
            title: data.dialogTitle,
            width: 800,
            resizable: false,
            height: 550,
            modal: true,
            open: function(){
            },
            close: function(){
              $(reportModelListDialog).dialog('close');
            },
            buttons: [{text: data.close_btn, class: 'btn btn-sm', click: function(){
                  $(reportModelListDialog).dialog('close');
                }}]
          });
          
          $('.ui-dialog').css('top', $('.page-logo').height() + 10);
          $(reportModelListDialog).dialog('open');
          reportDataGrid=$("#reportDataGrid");
          Core.unblockUI("body");
        },
        error: function(msg){
          Core.unblockUI("body");
        }
      }).done(function(){
        Core.initAjax();
      });
    }
  }

  function showHeaderEdit(target)
  {
      $.ajax({
        type: 'post',
        url: 'rmreport/testFunc',
        data: {},
        dataType: "json",
        beforeSend: function(){
          if($("#isPreviewData").prop('checked') === false){
            $("#isPreviewData").trigger('click');
          }
          Core.blockUI({
            message: 'Loading...',
            boxed: true
          });
          window.CKEDITOR_BASEPATH=URL_APP + 'assets/custom/addon/plugins/ckeditor/4.5.4/';
          $.getScript(URL_APP + 'assets/custom/addon/plugins/ckeditor/4.5.4/ckeditor.js');
        },
        success: function(data){
          Core.unblockUI();
          setTimeout(function(){
//            var CONTACTS=<?php echo $this->childsInfo; ?>;
//          CKEDITOR.disableAutoInline=true;
//          CKEDITOR.document.getById('childList').on('dragstart', function(evt){
//            var target=evt.data.getTarget().getAscendant('div', true);
//            CKEDITOR.plugins.clipboard.initDragDataTransfer(evt);
//            var dataTransfer=evt.data.dataTransfer;
//            dataTransfer.setData('metadata', CONTACTS[ target.data('metadata') ]);
//            dataTransfer.setData('text/html', target.getText());
//          });
          $(headerEditDialog).dialog({
            autoOpen: false,
            title: 'Засах',
            width: width,
            resizable: false,
            height: height,
            modal: true,
            open: function(){
              CKEDITOR.replace('headerEditor', {
                language: 'mn',
                height: '330px'
              });
              
              var html=$("#reportHeader").html();
              CKEDITOR.instances['headerEditor'].setData(html);
//                            uiColor: '#AADC6E',
//                            //enterMode: CKEDITOR.ENTER_P,
//                            //shiftEnterMode: CKEDITOR.ENTER_P,
//                            //autoParagraph: false,
//                            //removePlugins: 'elementspath',
//                            toolbar: 'Trimmed',
//                            toolbar_Trimmed: [
//                                ['Bold', 'Italic', 'Underline', 'Strike', 'Format', 'Font', 'FontSize', 'TextColor'],
//                                ['Table', 'Image']
//                            ]
              //removePlugins : 'scayt,menubutton,contextmenu',
//              var html=$("#prvReportSource").html();
//              var wrapped=$(html);
//              wrapped.find('tbody').remove();
//              wrapped.find('tfoot').remove();
//              CKEDITOR.instances['headerEditor'].setData(wrapped.html());
            },
            close: function(){
              CKEDITOR.instances.headerEditor.destroy();
              $(headerEditDialog).dialog('close');
            },
            buttons: [
              {text: 'Оруулах', class: 'btn btn-sm green float-left', click: function(){

                  var hString=CKEDITOR.instances['headerEditor'].getData();
                  $('#reportHeader').empty().html(hString);
                  if(hString !== undefined)
                  {
                    headerString=hString.replace(/^\s+|\s+$/gm, '');
                  }
                  $(headerEditDialog).dialog('close');
                }
              },
              {text: 'Болих', class: 'btn btn-sm', click: function(){
                  $(headerEditDialog).dialog('close');
                }}
            ]
          });
          $(headerEditDialog).dialog('open');
          }, 1000);
          
        },
        error: function(){
          alert("Error");
        }
      }).done(function(){
        Core.initAjax();
      });
  }
  
  function showFooterEdit(target)
  {
      $.ajax({
        type: 'post',
        url: 'rmreport/testFunc',
        data: {},
        dataType: "json",
        beforeSend: function(){
          if($("#isPreviewData").prop('checked') === false){
            $("#isPreviewData").trigger('click');
          }
          Core.blockUI({
            message: 'Loading...',
            boxed: true
          });
          window.CKEDITOR_BASEPATH=URL_APP + 'assets/custom/addon/plugins/ckeditor/4.5.4/';
          $.getScript(URL_APP + 'assets/custom/addon/plugins/ckeditor/4.5.4/ckeditor.js');
        },
        success: function(data){
          Core.unblockUI();
          setTimeout(function(){
//            var CONTACTS=<?php echo $this->childsInfo; ?>;
//          CKEDITOR.disableAutoInline=true;
//          CKEDITOR.document.getById('childListFoot').on('dragstart', function(evt){
//            var target=evt.data.getTarget().getAscendant('div', true);
//            CKEDITOR.plugins.clipboard.initDragDataTransfer(evt);
//            var dataTransfer=evt.data.dataTransfer;
//            dataTransfer.setData('metadata', CONTACTS[ target.data('metadata') ]);
//            dataTransfer.setData('text/html', target.getText());
//          });
          $(footerEditDialog).dialog({
            autoOpen: false,
            title: 'Засах',
            width: width,
            resizable: false,
            height: height,
            modal: true,
            open: function(){
              CKEDITOR.replace('footerEditor', {
                language: 'mn',
                height: '330px'
              });
              
              var html=$("#reportFooter").html();
              CKEDITOR.instances['footerEditor'].setData(html);
//                            uiColor: '#AADC6E',
//                            //enterMode: CKEDITOR.ENTER_P,
//                            //shiftEnterMode: CKEDITOR.ENTER_P,
//                            //autoParagraph: false,
//                            //removePlugins: 'elementspath',
//                            toolbar: 'Trimmed',
//                            toolbar_Trimmed: [
//                                ['Bold', 'Italic', 'Underline', 'Strike', 'Format', 'Font', 'FontSize', 'TextColor'],
//                                ['Table', 'Image']
//                            ]
              //removePlugins : 'scayt,menubutton,contextmenu',
//              var html=$("#prvReportSource").html();
//              var wrapped=$(html);
//              wrapped.find('tbody').remove();
//              wrapped.find('thead').remove();
//              CKEDITOR.instances['footerEditor'].setData(wrapped.html());
            },
            close: function(){
              CKEDITOR.instances.footerEditor.destroy();
              $(footerEditDialog).dialog('close');
            },
            buttons: [
              {text: 'Оруулах', class: 'btn btn-sm green float-left', click: function(){

                  var hString=CKEDITOR.instances['footerEditor'].getData();
                  $('#reportFooter').empty().html(hString);
                  if(hString !== undefined)
                  {
                    footerString=hString.replace(/^\s+|\s+$/gm, '');
                  }
                  $(footerEditDialog).dialog('close');
                }
              },
              {text: 'Болих', class: 'btn btn-sm', click: function(){
                  $(footerEditDialog).dialog('close');
                }}
            ]
          });
          $(footerEditDialog).dialog('open');
          }, 1000);
        },
        error: function(){
          alert("Error");
        }
      }).done(function(){
        Core.initAjax();
      });
      }
//    }

  function addFilterDetail(target)
    {
      var exist=false;
      var data={};
      if($('#isrequired').parent('span').hasClass('checked')){
        $('#isrequired').parent('span').removeClass('checked');
        $('#isrequired').val('0');
      }
      if(target === undefined)
      {
        $(detailFilterDialog).dialog({
          autoOpen: false,
          title: 'Хайлт',
          width: 400,
          resizable: false,
          height: "auto",
          modal: true,
          open: function(){

            var select=document.getElementById('cmbfieldNames');
            select.innerHTML="";

            for(var i=0; i <= allFilterValues.length; i++){
              var opt=document.createElement('option');
              opt.value=allFilterValues[i].id;
              opt.innerHTML=allFilterValues[i].id;
              select.appendChild(opt);
            }
            console.log(document.getElementById('filterFieldHeader').value);
            document.getElementById('filterFieldHeader').value="";
            document.getElementById('metaDataId').value="";
            document.getElementById('metaDataName').value="";
            document.getElementById('cmbfieldNames').value="";
          },
          close: function(){
            $(detailFilterDialog).dialog('close');
          },
          buttons: [
            {text: 'Оруулах', class: 'btn btn-sm green float-left', click: function(){
                addFilterFieldNode(document.getElementById('cmbfieldNames').value,
                        document.getElementById('filterFieldHeader').value,
                        document.getElementById('metaDataId').value,
                        document.getElementById('metaDataName').value,
                        document.getElementById('isrequired').value,
                        document.getElementById('filterdefaultvalue').value);
                $(detailFilterDialog).dialog('close');
              }
            },
            {text: 'Болих', class: 'btn btn-sm', click: function(){
                $(detailFilterDialog).dialog('close');
              }}
          ]
        });
        $(detailFilterDialog).dialog('open');
      }
    }
    
  function showFilterDetail(target){
      var item=$(target).closest('li.dd-item');
      var field=$(item[0]).attr('data-field');
      var title=$(item[0]).attr('data-title');
      var filterdefaultvalue=$(item[0]).attr('data-filterdefaultvalue');
      var isrequired=$(item[0]).attr('data-isrequired');
//      var metadataid=$(item[0]).attr('data-metadata');

      $(detailFilterDialog).dialog({
        autoOpen: false,
        title: 'Дэлгэрэнгүй тохиргоо',
        width: 435,
        resizable: false,
        height: "auto",
        modal: true,
        open: function(){
          $('#detailFilterForm').find('#filterFieldHeader').val(title);
          $('#detailFilterForm').find('#filterdefaultvalue').val(filterdefaultvalue);
          $('#detailFilterForm').find('#filterdefaultvalue').val(filterdefaultvalue);
        },
        close: function(){
          $(detailFilterDialog).dialog('close');
        },
        buttons: [
          {text: 'Оруулах', class: 'btn btn-sm green float-left', click: function(){
              $(item[0]).attr('data-title', $('#detailFilterForm').find('#filterFieldHeader').val());
              $(item[0]).attr('data-filterdefaultvalue', $('#detailFilterForm').find('#filterdefaultvalue').val());
              $(detailFilterDialog).dialog('close');
            }
          },
          {text: 'Болих', class: 'btn btn-sm',
            click: function(){
              $(detailFilterDialog).dialog('close');
            }}
        ]
      });
      $(detailFilterDialog).dialog('open');
    }

  function showDetail(target)
  {
    var exist=false;
    var data={};
    var item=$(target).closest('li.dd-item');
    var field=$(item[0]).attr('data-field');
    var type=$(item[0]).attr('data-type');
    var align=$(item[0]).attr('data-align');
    var mask=$(item[0]).attr('data-text');
    var isVisible=$(item[0]).attr('data-isVisible');
    var isMerge=$(item[0]).attr('data-isMerge');
    var order=$(item[0]).attr('data-order');
    
//    if(typeof isMerge === 'undefined'){
//      
//      isMerge === 0;
//    }
    //        var format = $(item[0]).attr('data-format');
    //        
    var header=$(item[0]).attr('data-header');
    var i=0;
    for(; i < datas.length; i++){
      if(datas[i].fieldName == field)
      {
        exist=true;
        break;
      }
    }
    if(exist == false)
    {
      data={fieldName: field, fieldAlign: align, fieldMask: mask, fieldType: type,
        fieldFormat: type == 'FLOAT' ? 'SUM' : 'COUNT', fieldHeader: '', isVisible: isVisible, isMerge: typeof isMerge === 'undefined' ? '0' : isMerge, fieldOrder: order};
      datas.push(data);
      i=datas.length - 1;
    }
//    console.log(datas[i]);
    document.getElementById("fieldName").value=datas[i].fieldName;
    //document.getElementById("fieldType").value = datas[i].fieldType;
    document.getElementById("fieldHeader").value=datas[i].fieldHeader;
    document.getElementById("fieldFormat").value=datas[i].fieldFormat;
    document.getElementById("fieldAlign").value=datas[i].fieldAlign;
    document.getElementById("fieldMask").value=datas[i].fieldMask;
    document.getElementById("isVisible").value=datas[i].isVisible;
    document.getElementById("isMerge").value=datas[i].isMerge;
    document.getElementById("fieldOrder").value=datas[i].fieldOrder;
//    console.log(datas[i].isMerge);
    $(detailDialog).dialog({
      autoOpen: false,
      title: 'Дэлгэрэнгүй тохиргоо',
      width: 435,
      resizable: false,
      height: "auto",
      modal: true,
      open: function(){     
        //if(!$('#isVisible').parent('span').hasClass('checked')){
        //  $('#isVisible').parent('span').toggleClass('checked');
        //}
		if($('#isVisible').val() == 'true'){
            $('#isVisible').parent('span').addClass('checked');
        }else{
          $('#isVisible').parent('span').removeClass('checked');
        }
        if($('#isMerge').parent('span').hasClass('checked')){
          $('#isMerge').parent('span').removeClass('checked');
        }
        $('#isMerge').val('0');
      },
      close: function(){
        $(detailDialog).dialog('close');
      },
      buttons: [
        {text: 'Оруулах', class: 'btn btn-sm green float-left', click: function(){

            datas[i].fieldName=document.getElementById("fieldName").value;
            //datas[i].fieldName = document.getElementById("fieldName").fieldType;
            datas[i].fieldHeader=document.getElementById("fieldHeader").value;
            datas[i].fieldFormat=document.getElementById("fieldFormat").value;
            datas[i].fieldAlign=document.getElementById("fieldAlign").value;
            datas[i].fieldMask=document.getElementById("fieldMask").value;
            datas[i].fieldOrder=document.getElementById("fieldOrder").value;
            datas[i].isMerge=document.getElementById("isMerge").value;

            if(datas[i].isVisible != document.getElementById("isVisible").checked)
              headerString="";
            datas[i].isVisible=document.getElementById("isVisible").checked;
            generatePreview();
            $(detailDialog).dialog('close');
          }
        },
        {text: 'Болих', class: 'btn btn-sm',
          click: function(){                                 //alert('asdfasdf');
            $(detailDialog).dialog('close');
          }}
      ]
    });
    $(detailDialog).dialog('open');
  }

  function removeFilter(target)
  {
    var item=$(target).closest('li.dd-item');
    // var field = $(item[0]).attr('data-field');
    $("#nestable_field_list > ol").append(item);
  }


  function reloadColumns()
  {
    $.ajax({
      type: 'post',
      url: 'rmreport/getColumnList',
      dataType: "json",
      data: {values: $("#mainForm").serialize()},
      beforeSend: function(){
      },
      success: function(data){
        //allColumns=data;
        allFilterValues=data;
        console.log(allFilterValues);
        $("#nestable_field_list").html("<ol class='dd-list'></ol>");
        $("#nestable_row_list").html("<ol class='dd-empty'></ol>");
        $("#nestable_column_list").html("<ol class='dd-empty'></ol>");
        $("#nestable_fact_list").html("<ol class='dd-empty'></ol>");
        $("#nestable_filter_list").html("<ol class='dd-list'></ol>");
        for(var i=0; i < data.length; i++)
        {
          addFieldNode(data[i].id, data[i].fieldType);
        }
      },
      error: function(msg){

      }
    });
  }


  function generatePreview()
  {
    refreshRowList();
    refreshColList();
    var row_datas=$('#nestable_row_list').nestable('serialize');
    var column_datas=$('#nestable_column_list').nestable('serialize');

    var fact_datas=$('#nestable_fact_list').nestable('serialize');
    var filter_datas=[];
    var isPreviewData=document.getElementById("isPreviewData");

    for(var i=0; i < row_datas.length; i++)
    {
      row_datas[i].title=row_datas[i].field;
      for(var j=0; j < datas.length; j++){
        if(row_datas[i].field == datas[j].fieldName)
        {
//          console.log(datas[j]);
          row_datas[i].format=datas[j].fieldFormat;
          row_datas[i].viewOrder=datas[j].viewOrder;
          row_datas[i].align=datas[j].fieldAlign;
          row_datas[i].mask=datas[j].fieldMask;
          row_datas[i].title=datas[j].fieldHeader == "" ? datas[j].fieldName :
                  datas[j].fieldHeader;
          row_datas[i].isVisible=datas[j].isVisible;
          row_datas[i].isMerge=datas[j].isMerge;
        }
      }
    }

    for(var i=0; i < column_datas.length; i++)
    {
      column_datas[i].title=column_datas[i].field;
      for(var j=0; j < datas.length; j++)
      {
        if(column_datas[i].field == datas[j].fieldName)
        {
//          console.log(datas[j]);
          column_datas[i].format=datas[j].fieldFormat;
          column_datas[i].viewOrder=datas[j].viewOrder;
          column_datas[i].align=datas[j].fieldAlign;
          column_datas[i].mask=datas[j].fieldMask;
          column_datas[i].title=datas[j].fieldHeader == "" ? datas[j].fieldName :
                  datas[j].fieldHeader;
          column_datas[i].isVisible=datas[j].isVisible;
          column_datas[i].isMerge=datas[j].isMerge;
        }
      }
    }

    if(row_datas.length != 0 && column_datas.length != 0)
    {
      if(isPreviewData.checked == true)
      {
        $.ajax({
          type: 'post',
          url: 'rmreport/getPreviewList',
          dataType: "json",
          data: {
            rows: fact_datas.concat(row_datas),
            cols: column_datas,
            filters: filter_datas,
            values: $("#mainForm").serialize()
          },
          beforeSend: function(){
            Core.blockUI({
                target: ".page-container",
                message: 'Уншиж байна. Та түр хүлээнэ үү...',
                boxed: true
              });
          }, success: function(result){
            $('#prvReportSource').empty().html(getTableHtml(row_datas, column_datas, fact_datas,
                    result));
            var tmpModelId = <?php echo $this->row['modelId']?>;
            if(tmpModelId !== 0){
              $('#reportHeader').empty().html(headerString);
              $('#reportFooter').empty().html(footerString);
            }
            Core.unblockUI(".page-container");
          },
          error: function(msg){
            Core.unblockUI(".page-container");
          }
        });
      }
      else
      {
        $('#prvReportSource').empty();
//        $('#prvReportSource').empty().append(getTableHtml(row_datas, column_datas, fact_datas));
//        setCustomHeader(headerString);
//        setCustomFooter(footerString);
      }

    }
  }

  function saveReportModel()
  {
    var row_datas=$('#nestable_row_list').nestable('serialize');
    var column_datas=$('#nestable_column_list').nestable('serialize');
    var fact_datas=$('#nestable_fact_list').nestable('serialize');
    var filter_datas=$('#nestable_filter_list').nestable('serialize');
    for(var i=0; i < row_datas.length; i++)
    {
      row_datas[i].title=row_datas[i].field;
      for(var j=0; j < datas.length; j++){
        if(row_datas[i].field == datas[j].fieldName)
        {
          row_datas[i].format=datas[j].fieldFormat;
          row_datas[i].viewOrder=datas[j].viewOrder;
          row_datas[i].align=datas[j].fieldAlign;
          row_datas[i].mask=datas[j].fieldMask;
          row_datas[i].title=datas[j].fieldHeader == "" ? datas[j].fieldName :
                  datas[j].fieldHeader;
          row_datas[i].isVisible=datas[j].isVisible;
          row_datas[i].isMerge=datas[j].isMerge;
          row_datas[i].order=datas[j].fieldOrder; 
        }
      }
    }
    
    for(var i=0; i < column_datas.length; i++)
    {
      column_datas[i].title=column_datas[i].field;
      for(var j=0; j < datas.length; j++)
      {
        if(column_datas[i].field == datas[j].fieldName)
        {
          column_datas[i].format=datas[j].fieldFormat;
          column_datas[i].viewOrder=datas[j].viewOrder;
          column_datas[i].align=datas[j].fieldAlign;
          column_datas[i].mask=datas[j].fieldMask;
          column_datas[i].title=datas[j].fieldHeader == "" ? datas[j].fieldName :
                  datas[j].fieldHeader;
          column_datas[i].isVisible=datas[j].isVisible;
          column_datas[i].isMerge=datas[j].isMerge;
          column_datas[i].order=datas[j].fieldOrder;
        }
      }
    }
    
    for(var i=0; i < fact_datas.length; i++)
    {
      for(var j=0; j < datas.length; j++)
      {
        if(fact_datas[i].field == datas[j].fieldName)
        {
          fact_datas[i].isVisible=datas[j].isVisible;
          fact_datas[i].order=datas[j].fieldOrder;
        } else {
          fact_datas[i].isVisible='';
          fact_datas[i].order='';
        }
      }
    }
    
    if(row_datas.length != 0 && column_datas.length != 0)
    {
      $.ajax({
        type: 'post',
        url: 'rmreport/saveReport',
        dataType: "json",
        data: {
          rows: row_datas,
          cols: column_datas,
          facts: fact_datas,
          filters: filter_datas,
          headerHtml: $('#reportHeader').html(),
          footerHtml: $('#reportFooter').html(),
          values: $("#mainForm").serialize()},
        beforeSend: function(){
          Core.blockUI({
            message: 'Хадгалж байна...',
            boxed: true
          });
        },
        success: function(data){
          Core.unblockUI();
          if(data.status === 'success'){

            document.getElementById("modelId").value=data.modelId;

            location.href="rmreport";
            new PNotify({
              title: 'Success',
              text: data.message, type: 'success',
              sticker: false
            });
          } else {
            new PNotify({
              title: 'Error',
              text: data.message,
              type: 'error',
              sticker: false
            });
          }
        },
        error: function(msg){
          new PNotify({
            title: 'Error',
            text: msg,
            type: 'error',
            sticker: false
          });
        }
      });
    }
  }

  $(document).on('focusin', function(e){
    e.stopImmediatePropagation();
  });
</script>


