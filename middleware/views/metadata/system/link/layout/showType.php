<div id="showTypeLayout">
  <div class="panel panel-default bg-inverse">
    <table class="table sheetTable border-bottom-grey showTypeLayoutTable">
      <tbody>
        <tr>
          <td class="left-padding first">ShowTypeLayout:</td>
          <td>
            <?php
            echo Form::select(
                array(
                    'name' => 'showTypeThemeCode',
                    'id' => 'showTypeThemeCode',
                    'data' => isset($this->showTypeThemeList) ? $this->showTypeThemeList : array(),
                    'op_value' => 'code',
                    'op_text' => 'name',
                    'class' => 'form-control',
                    'value' => isset($this->getMetaWidgetLink['SUBTYPE']) ? $this->getMetaWidgetLink['SUBTYPE'] : ''
                )
            );
            ?>
          </td>
          <td class="last">
            <button type="button" class="btn btn-sm purple-plum" id="viewShowTypeThemeBtn">...</button></div>
          </td>
        </tr>
        <tr>
          <td class="left-padding first">Row Count:</td>
          <td colspan="2">
            <?php
            echo Form::text(
                    array(
                        'name' => 'widgetLinkRowCount',
                        'id' => 'widgetLinkRowCount',
                        'class' => 'form-control form-control-sm',
                        'value' => isset($this->getMetaWidgetLink['ROW_COUNT']) ? $this->getMetaWidgetLink['ROW_COUNT']
                                    : ''
                    )
            );
            ?>      
             <?php
            echo Form::hidden(
                    array(
                        'name' => 'listMetaDataId',
                        'id' => 'listMetaDataId',
                        'value' => isset($this->getMetaWidgetLink['LIST_META_DATA_ID']) ? $this->getMetaWidgetLink['LIST_META_DATA_ID']
                                    : ''
                    )
            );
            ?>      
          </td>
        </tr>
      </tbody>
    </table>
    <table class="table sheetTable showTypeLayoutParameterList" id="showTypeLayoutParameterList">
      <tbody>
        <?php
        if (isset($this->getMetaWidgetParam)) {
            echo $this->getMetaWidgetParam;
        }
        ?>
      </tbody>
    </table>
  </div>      
</div>