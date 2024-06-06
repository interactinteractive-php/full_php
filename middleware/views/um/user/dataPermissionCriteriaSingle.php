<fieldset class="">
  <i class="fa fa-trash remove-batch-btn"></i>
  <legend>
    <div class="col-md-3 no-padding">
      Criteria
    </div>
    <div class="col-md-9 no-padding ta-r">
      <div class="form-group row fom-row">
        <div class="col-md-2 no-padding ta-r f-r">
          <select name="batchNumber[]" id="batchNumber" class="form-control form-control-sm select2 batch-number f-r">
              <?php
              foreach (range(1, 10) as $x) {
                  echo '<option value="' . $x . '">' . $x . '</option>';
              }
              ?>
          </select>
        </div>
        <label for="batchNumber" class="col-md-2 no-padding f-r">Багц: </label>
      </div>
    </div>
    <div class="clearfix w-100"></div>
  </legend>
  <?php
  if (!is_null($this->dvGridHeaderList)) {
      foreach ($this->dvGridHeaderList as $value) {
          ?>

          <div class="single-row-label">
            <div class="col-md-5">
              <span><?php echo $value['LABEL_NAME']; ?></span>
              <input type="hidden" name="paramName[0][]" value="<?php echo $value['FIELD_PATH']; ?>"/>
            </div>
            <div class="col-md-3">
              <div class="input-group input-group-criteria ta-l">
                <span class="input-group-btn input-group-date">
                  <button type="button" class="btn dropdown-toggle criteria-condition-btn  dropdown-none-arrow" data-toggle="dropdown" aria-expanded="false">=</button>
                  <input type="hidden" name="paramAction[0][]" value="=">
                  <ul class="dropdown-menu dropdown-menu-default float-right dropdown-menu-display" role="menu">
                    <li><a href="javascript:;" class="li-criteriaCondition" data-criteria-condition="=">=</a></li>
                    <li><a href="javascript:;" class="li-criteriaCondition" data-criteria-condition="!=">Ялгаатай</a></li>
                    <li><a href="javascript:;" class="li-criteriaCondition" data-criteria-condition=">">&gt;</a></li>
                    <li><a href="javascript:;" class="li-criteriaCondition" data-criteria-condition="<">&lt;</a></li>
                    <li><a href="javascript:;" class="li-criteriaCondition" data-criteria-condition=">=">&gt;=</a></li>
                    <li><a href="javascript:;" class="li-criteriaCondition" data-criteria-condition="<=">&lt;=</a></li>
                    <li><a href="javascript:;" class="li-criteriaCondition" data-criteria-condition="BETWEEN">Хооронд</a></li>
                  </ul>
                </span> 
              </div>
            </div>
            <div class="col-md-4">
              <input type="text" class="form-control form-control-sm" name="paramValue[0][]"/>
            </div>
            <div class="clearfix w-100"></div>
          </div>
          <?php
      }
  }
  ?>
</fieldset>
