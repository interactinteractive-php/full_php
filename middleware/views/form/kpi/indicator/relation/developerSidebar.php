<?php
$relationList = Arr::groupByArray($this->relationList, 'KPI_TYPE_ID');
$html = [];

foreach ($relationList as $kpiTypeId => $relation) {
    
    $row = $relation['row'];
    $rows = $relation['rows'];
    $icon = 'wrench';
    
    if ($kpiTypeId == '2020') {
        $icon = 'newspaper';
    } elseif ($kpiTypeId == '1000' || $kpiTypeId == '1000' || $kpiTypeId == '1040' || $kpiTypeId == '1044' || $kpiTypeId == '1045' || $kpiTypeId == '16641793815766') {
        $icon = 'database';
    } elseif ($kpiTypeId == '1060') {
        $icon = 'chart-pie';
    } elseif ($kpiTypeId == '1130' || $kpiTypeId == '1140') {
        $icon = 'chart-network';
    } elseif ($kpiTypeId == '1120') {
        $icon = 'list';
    } 
            
    $html[] = '<li class="nav-item nav-item-submenu">';
        $html[] = '<a href="#" class="nav-link"><i class="far fa-'.$icon.'"></i> '.$row['KPI_TYPE_NAME'].'</a>';
        $html[] = '<ul class="nav nav-group-sub">';
        
        foreach ($rows as $val) {
            
            if ($val['KPI_TYPE_ID'] == '1120') {
                
                $rowId = $val['ID'];
                $parentId = $val['PARENT_ID'];
                
                $childMenus = array_filter($rows, function($ar) use($rowId) {
                    return ($ar['PARENT_ID'] == $rowId);
                });
                
                $parentMenus = array_filter($rows, function($ar) use($parentId) {
                    return ($ar['ID'] == $parentId);
                });
                
                if ($childMenus) {
                    
                    $html[] = '<li class="nav-item">';
                        $html[] = '<a href="javascript:;" class="nav-link mv-developer-workspace-indicator" data-indicator-id="'.$val['ID'].'" data-type-id="'.$val['KPI_TYPE_ID'].'">';
                            $html[] = '<i class="fa fa-folder-open"></i> '.$val['NAME'];
                        $html[] = '</a>';
                    $html[] = '</li>';
                    
                    foreach ($childMenus as $childMenu) {
                        
                        $html[] = '<li class="nav-item">';
                            $html[] = '<a href="javascript:;" class="nav-link mv-developer-workspace-indicator pl-5" data-indicator-id="'.$childMenu['ID'].'" data-type-id="'.$childMenu['KPI_TYPE_ID'].'">';
                                $html[] = '<i class="fa fa-circle mt6 mr-1 font-size-6"></i> '.$childMenu['NAME'];
                            $html[] = '</a>';
                        $html[] = '</li>';
                    }
                    
                } elseif (!$parentMenus) {
                    
                    $html[] = '<li class="nav-item">';
                        $html[] = '<a href="javascript:;" class="nav-link mv-developer-workspace-indicator" data-indicator-id="'.$val['ID'].'" data-type-id="'.$val['KPI_TYPE_ID'].'">';
                            $html[] = '<i class="fa fa-circle mt6 mr-1 font-size-6"></i> '.$val['NAME'];
                        $html[] = '</a>';
                    $html[] = '</li>';
                }
                
            } else {
                $html[] = '<li class="nav-item">';
                    $html[] = '<a href="javascript:;" class="nav-link mv-developer-workspace-indicator" data-indicator-id="'.$val['ID'].'" data-type-id="'.$val['KPI_TYPE_ID'].'">';
                        $html[] = '<i class="fa fa-circle mt6 mr-1 font-size-6"></i> '.$val['NAME'];
                    $html[] = '</a>';
                $html[] = '</li>';
            }
        }
    
        $html[] = '</ul>';
    $html[] = '</li>';
}

echo implode('', $html);