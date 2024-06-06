<?php 

    $hparametrArr = ($this->bookHeadParams) ? explode(',', $this->bookHeadParams) : '';
    $dparametrArr = ($this->bookDtlParams) ? explode(',', $this->bookDtlParams) : '';

    $contentId = (isset($this->postData['cvlContentId']) ? $this->postData['cvlContentId'] : '');
    $isDisabled = 'readonly="readonly"';

    $uniqId = (isset($this->postData['uniqId']) && $this->postData['uniqId']) ? $this->postData['uniqId'] : getUID();
    $index = (isset($this->postData['trIndex']) && $this->postData['trIndex']) ? $this->postData['trIndex']-1 : 0;

    $srcTarget = (isset($this->postData['cvlBookDate']) && isset($this->postData['cvlBookType'])) ? $this->postData['cvlBookDate']. '-'.$this->postData['cvlBookType'] : '0';
    (String) $html = '';
    
    if (isset($this->postData['isDisabled']) && $this->postData['isDisabled'] == '0') {
        $html .= '<input type="hidden" data-path="contentId_'. $uniqId .'" value="0" />';
        $html .= '<input type="hidden" data-path="nodeId_'. $uniqId .'" value="0" />';
        $html .= '<input type="hidden" data-path="civilId_'. $uniqId .'" value="'. $this->civilId .'" />';
        $html .= '<div class="hide_'. $uniqId .'" style="display: none;">';
            if ($this->inType !== '7') {
                $html .= '<button type="button" class="btn btn-sm btn-circle btn-primary float-right mb10 ml10" onclick="cvlShowDeletePhotoData_'. $uniqId .'(this);"><i class="fa fa-trash"></i> '. $this->lang->line('delete_photo') .'</button>';
                $html .= '<button type="button" class="btn btn-sm btn-circle btn-success float-right mb10 ml10 cvlSaveBookData_'. $uniqId .'" style="display: none;" onclick="cvlSaveBookData_'. $uniqId .'(this);"><i class="fa fa-save"></i> '. $this->lang->line('save_btn') .'</button>';
                $html .= '<button type="button" class="btn btn-sm btn-circle btn-warning float-right mb10 cvlEditBookData_'. $uniqId .'" onclick="cvlEditBookData_'. $uniqId .'(this);"><i class="fa fa-edit"></i> '. $this->lang->line('edit_btn') .'</button>';
            }
        $html .= '</div>';
    }
    
    $html .= '<table class="table table-sm table-no-bordered bp-header-param">
                <input type="hidden" name="civilId['. $srcTarget .']" '. $isDisabled .' value="'. $this->civilId .'">
                <input type="hidden" name="civilPackId['. $srcTarget .']" '. $isDisabled .' value="'. (isset($this->bookData['civilpackid']) ? $this->bookData['civilpackid'] : '') .'">
                <input type="hidden" name="cfgHdrCode['. $srcTarget .']" '. $isDisabled .' value="'. $this->cfgHdrCode .'">
                <input type="hidden" name="cfgDtlCode['. $srcTarget .']" '. $isDisabled .' value="'. $this->cfgDtlCode .'">
                <input type="hidden" data-path="bookTypeId" name="bookTypeId['. $srcTarget .']" value="'. (isset($this->bookData['booktypeid']) ? $this->bookData['booktypeid'] : '') .'">
                <input type="hidden" data-path="bookDate" name="bookDate['. $srcTarget .']" value="'. (isset($this->bookData['bookdate']) ? $this->bookData['bookdate'] : '') .'">
                <input type="hidden" name="srcTableName['. $srcTarget .']" '. $isDisabled .' value="CVL_CIVIL_BOOK">
                <input type="hidden" name="srcRecordId['. $srcTarget .']"  '. $isDisabled .' value="'. (isset($this->bookData['id']) ? $this->bookData['id'] : '') .'">
                <input type="hidden" name="trgTableName['. $srcTarget .']" '. $isDisabled .' value="ECM_CONTENT">
                <input type="hidden" name="trgRecordId['. $srcTarget .']"  '. $isDisabled .' value="'. $contentId .'">';
    
        $html .= '<tbody>';
        
            if ($hparametrArr) {
                foreach ($hparametrArr as $hparam) {
                    $param = Security::sanitize($hparam);
                    $upperParam = Str::upper($param);
                    $addClass = (strpos($upperParam, 'DATE') !== false ) ? 'dateInit' : 'stringInit';
                    $html .= '<tr>
                                <td class="text-left middle border-bottom-cvl" data-cell-path="'. $param .'" style="width: 46%">
                                    <label for="'. $param .'" data-label-path="'. $param .'['. $srcTarget .']">'. $this->lang->line('CVL_' . $upperParam) .'</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="middle" data-cell-path="'. $param .'" style="width: 54%" colspan="">
                                    <div data-section-path="'. $param .'">
                                        <input type="text" name="'. $param .'['. $srcTarget .']" '. $isDisabled .' class="form-control form-control-sm '. $addClass .'" data-path="'. $param .'" data-field-name="'. $param .'" value="'. (isset($this->bookData[$param]) ? $this->bookData[$param] : '') .'" data-isclear="" placeholder="'. $this->lang->line('CVL_' . $upperParam) .'">
                                        <input type="hidden" name="'. $param .'_old['. $srcTarget .']" value="'. (isset($this->bookData[$param]) ? $this->bookData[$param] : '') .'">
                                    </div>
                                </td>
                            </tr>';
                }
            }

        $html .= '</tbody>';
    $html .= '</table>';

    if ($this->bookDataMarrigeData) {
        $disabled = '';
        
        if (isset($this->postData['isDisabled']) && $this->postData['isDisabled'] == '0') {
            $disabled = 'disabled="disabled"';
        }

        $html .= '<select name="cvlMarriageData['. $srcTarget .']" onchange="callMarriageFnc(this)" '. $disabled .' class="form-control form-control-sm select2" required="required" style="width: 100%">';
        $selected = '';

        if ((isset($this->postData['isDisabled']) && $this->postData['isDisabled'] == '1')) {
            $html .= '<option selected="selected" value="">- Сонгох -</option>';
        }

        foreach ($this->bookDataMarrigeData as $key => $row) {

            $tempRow = $row;
            $selectText = $tempRow['regdate'] .' бүртгэгдсэн гэрлэлт';
            
            if (isset($this->postData['isDisabled']) && $this->postData['isDisabled'] == '0' && $key == 0) {
                $selected = 'selected="selected"';
            }
            else {
                $selected = '';
                if (isset($this->bookDataMarrigeData_bookDtl[0]['marriageid']) && $this->bookDataMarrigeData_bookDtl[0]['marriageid'] == $row['id']) {
                    $tempRow = $this->bookDataMarrigeData_bookDtl[0];
                    $selectText = $tempRow['regdate'] .' бүртгэгдсэн гэрлэлт хадгалагдсан';
                    $selected = 'selected="selected"';
                }
            }

            $html .= '<option '. $selected . ' value="'. $tempRow['id'] .'">'. $selectText .'</option>';

        }

        $html .= '</select>';

        foreach ($this->bookDataMarrigeData as $key => $row) {
            $hidden = 'hidden';
            $tempRow = $row;
            if ((isset($this->postData['isDisabled']) && $this->postData['isDisabled'] == '0') && $key == 0) {
                $hidden = '';
            } elseif (isset($this->bookDataMarrigeData_bookDtl[0]['marriageid']) && $this->bookDataMarrigeData_bookDtl[0]['marriageid'] == $row['marriageid']) {
                $tempRow = $this->bookDataMarrigeData_bookDtl[0];
                $hidden = '';
            }

            $html .= "<table class='". $tempRow['id'] ." $hidden cvl-marriage-table table table-sm table-no-bordered bp-header-param'>";
                $html .= '<input type="hidden" name="marrCivilId['. $srcTarget .']['. $tempRow['id'] .']"  value="'. $tempRow['civilid'] .'">';
                $html .= '<input type="hidden" name="marriageId['. $srcTarget .']['. $tempRow['id'] .']"  value="'. $tempRow['marriageid'] .'">';
                
                if ($dparametrArr) {
                    foreach ($dparametrArr as $dparam) {
                        $param = Security::sanitize($dparam);
                        $upperParam = Str::upper($param);
                        $addClass = (strpos($upperParam, 'DATE') !== false ) ? 'dateInit' : 'stringInit';

                        $html .= '<tr>
                                    <td class="text-left middle border-bottom-cvl" data-cell-path="'. $param .'" style="width: 46%">
                                        <label for="'. $param .'" data-label-path="'. $param .'['. $srcTarget .']">'. $this->lang->line('CVL_'. $upperParam) .'</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="middle" data-cell-path="'. $param .'" style="width: 54%" colspan="">
                                        <div data-section-path="'. $param .'">
                                            <input type="text" name="'. $param .'['. $srcTarget .']['. $tempRow['id'] .']" '. $isDisabled .' class="form-control form-control-sm '. $addClass .'" data-path="'. $param .'" data-field-name="'. $param .'" value="'. $tempRow[$param] .'" data-isclear="" placeholder="'. $this->lang->line('CVL_'. $upperParam) .'">
                                            <input type="hidden" name="'. $param .'_old['. $srcTarget .']['. $tempRow['id'] .']" value="'. $tempRow[$param] .'">
                                        </div>
                                    </td>
                                </tr>';
                    }
                }
                
            $html .= "</table>";

        }
    }
    
    echo $html;
?>