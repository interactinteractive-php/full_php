{sidebar}

$sidebarCell = '';

if ($sidebarShowRowsDtl_[$row['id']]) {
                            
    $sidebarCell .= '<div class="sidebarDetailSection hide">';

    if (!empty($sidebarGroupArr_[$row['id']])) {
        foreach ($sidebarGroupArr_[$row['id']] as $keyPopGroup => $rowPopGroup) {

            $sidebarCell .= '<p class="property_page_title">' . $this->lang->line($rowPopGroup) . '</p>' .
            '<div class="panel panel-default bg-inverse grid-row-content">' .
            '<table class="table sheetTable sidebar_detail">' .
            '<tbody>';
            if (isset($sidebarDtlRowsContentArr_[$row['id']][$keyPopGroup][$k])) {
                foreach ($sidebarDtlRowsContentArr_[$row['id']][$keyPopGroup][$k] as $subrowPopGroup) {
                    $sidebarCell .= "<tr data-cell-path='".$subrowPopGroup['data_path']."'>" .
                    "<td style='width: 229px;' class='left-padding'>" . $this->lang->line($subrowPopGroup['input_label_txt']) . "</td>" .
                    "<td>" . $subrowPopGroup['input_html'] . "</td>" .
                    "</tr>";
                }
            }
            $sidebarCell .= '</tbody></table></div>';
        }
    }  

    $sidebarCell .= '</div>';
}

$result .= '<div class="bp-view-bigcard-item">
    <div class="bp-view-bigcard-header">';
        
        if ($rowData['{fieldName1}'] && file_exists($rowData['{fieldName1}'])) {
            $result .= '<img src="'.$rowData['{fieldName1}'].'" class="rounded-circle">';
        } else {
            $result .= '<img src="assets/core/global/img/user.png" class="rounded-circle">';
        }
        
$result .= '<div class="bp-view-bigcard-names">
            <span class="bp-view-bigcard-code">{pos2}</span>
            <span class="bp-view-bigcard-name1">{pos3}</span>
            <span class="bp-view-bigcard-name2">{pos4}</span>
        </div>
        <div class="bp-view-bigcard-button">';
            
if ($isTab) {
    $result .= '<button type="button" class="btn btn-xs purple-plum mt20" onclick="bpRowsView(this);" title="Дэлгэрэнгүй">...</button>';
                            
    $result .= '<div class="param-tree-container-tab param-tree-container hide">';
    $result .= '<div class="tabbable-line">
            <ul class="nav nav-tabs">' . $gridTabContentHeader . '</ul>
            <div class="tab-content">
            ' . $gridTabContentBody . '
            </div>
        </div>';
    $result .= '</div>';
}

if ($sidebarCell != '') {            
    $result .= $sidebarCell.'<button type="button" class="btn btn-xs purple-plum mt20 ml5" onclick="bpSidebarView(\'div#bp-window-' . $processId . '\', this);" title="Popup Ñ†Ð¾Ð½Ñ…Ð¾Ð¾Ñ€ Ñ…Ð°Ñ€Ð°Ñ…"><i class="fa fa-edit"></i></button>';
}

$result .= '</div>
        <div class="clearfix w-100"></div>
    </div>
    
    <div class="bp-view-bigcard-body">
        <table class="table table-hover table-light">
            <tbody>
                <tr>
                    <td style="width: 50%" data-cell-path="{path5}">
                        <div class="bp-view-bigcard-label">{label5}:</div>
                        {pos5}
                    </td>
                    <td style="width: 50%" data-cell-path="{path6}">
                        <div class="bp-view-bigcard-label">{label6}:</div>
                        {pos6}
                    </td>
                </tr>
                <tr>
                    <td data-cell-path="{path7}">
                        <div class="bp-view-bigcard-label">{label7}:</div>
                        {pos7}
                    </td>
                    <td data-cell-path="{path8}">
                        <div class="bp-view-bigcard-label">{label8}:</div>
                        {pos8}
                    </td>
                </tr>
                <tr>
                    <td data-cell-path="{path9}">
                        <div class="bp-view-bigcard-label">{label9}:</div>
                        {pos9}
                    </td>
                    <td data-cell-path="{path10}">
                        <div class="bp-view-bigcard-label">{label10}:</div>
                        {pos10}
                    </td>
                </tr>
                <tr>
                    <td data-cell-path="{path11}">
                        <div class="bp-view-bigcard-label">{label11}:</div>
                        {pos11}
                    </td>
                    <td data-cell-path="{path12}">
                        <div class="bp-view-bigcard-label">{label12}:</div>
                        {pos12}
                    </td>
                </tr>
                <tr>
                    <td data-cell-path="{path13}">
                        <div class="bp-view-bigcard-label">{label13}:</div>
                        {pos13}
                    </td>
                    <td data-cell-path="{path14}">
                        <div class="bp-view-bigcard-label">{label14}:</div>
                        {pos14}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
</div>';