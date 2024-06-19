<?php 
$cards = [];
$firstModule = $firstModuleCode = '';
$colorSet = explode(',', $this->colorSet);
$parentId = $this->parentId;

if (empty($this->parentId)) {
    foreach ($this->moduleList as $rowParent) {

        $cards[] = '<h2 class="ml-2 pt10 pb0 mb0" style="display: inline-block;width: 100%;text-transform: uppercase;font-family: Arial;font-size: 16px;">'.$rowParent['name'].'</h2>';
        $cards[] = '<h2 class="ml-2 pt0 pb10" style="font-weight: normal;display: inline-block;width: 100%;font-size: 12px;color:#67748E">'.($rowParent['description'] ? $rowParent['description'] : 'Тайлбар бичсэн бол энд харуулна').'</h2>';

        if (issetParam($rowParent['children'])) {

            foreach ($rowParent['children'] as $row) {

                aCardHtml($cards, $row, $colorSet, $parentId);
            }
        }
    }            
    
} else {
    
    if ($this->moduleList) {
        foreach ($this->moduleList as $row) {
            aCardHtml($cards, $row, $colorSet, $parentId);
        }
    }
}

function aCardHtml(&$cards, $row, $colorSet, $parentId) {
    $colorSetIndex = array_rand($colorSet);

    $row['menucolor'] = '';
    $row['isshowcard'] = '';
    $row['weburl'] = '';
    $row['actionmetadataid'] = '';
    $row['actionmetatypeid'] = '';
    $row['photoname'] = '';
    $row['icon'] = '';
    $row['META_DATA_ID'] = '';
    $row['ID'] = $row['id'];

    $linkHref = 'javascript:;';
    $linkTarget = '_self';
    $linkOnClick = '';
    $class = ' random-border-radius3 card-ischild-'.(issetParam($row['children']) ? '1' : '0');
    $cartbgColor = '';

    if ($row['menucolor']) {
        $cartbgColor = 'background-color:'.$row['menucolor'].';';
    } else {
        $cartbgColor = 'background-color:'.$colorSet[$colorSetIndex].';';
    }

    if ($row['isshowcard'] == 'true') {
        $linkHref = 'appmenu/sub/' . $row['code'];
        $linkTarget = '_self';
        $linkOnClick = '';

    } elseif (!empty($row['weburl'])) {

        if (strtolower(substr($row['weburl'], 0, 4)) == 'http' || $row['weburl'] == 'appmenu/kpi') {
            $linkHref = $row['weburl'];
        } else {
            $linkHref = $row['weburl'] . '&mmid=' . $row['metadataid'];
        }

        $linkTarget = $row['urltrg'];
        $linkOnClick = '';

    } elseif (!empty($row['MENU_INDICATOR_ID']) && !$row['IS_RELATION']) {

        $linkHref = 'appmenu/module/'.$row['MENU_INDICATOR_ID'].'?kmid='.$row['MENU_INDICATOR_ID'];
        $linkTarget = '_self';
        $linkOnClick = '';                            

    } elseif (!empty($row['META_DATA_ID'])) {

        if (!$row['ACTION_META_TYPE_ID']) {
            $linkHref = 'appmenu/module/'.$row['META_DATA_ID'].'?mmid='.$row['META_DATA_ID'].'&mid='.$row['META_DATA_ID'];
            $linkTarget = '_self';
            $linkOnClick = '';
        } else {

            $row['metadataid'] = $row['META_DATA_ID'];
            $row['actionmetadataid'] = $row['ACTION_META_DATA_ID'];
            $row['weburl'] = null;
            $row['actionmetatypeid'] = $row['ACTION_META_TYPE_ID'];
            $row['grouptype'] = 'dataview';

            $linkMeta = Mdmeta::menuServiceAnchor($row, $row['META_DATA_ID'], $row['META_DATA_ID']);
            $linkHref = $linkMeta['linkHref'];
            $linkTarget = $linkMeta['linkTarget'];
            $linkOnClick = $linkMeta['linkOnClick'];
        }

    } else {
        $indicatorId = $row['ID'];
        $linkHref = 'javascript:;';
        $linkOnClick = 'mvProductRenderInit(this, \''.$linkHref.'\', \''.$indicatorId.'\');';
    }

    if ($row['META_DATA_ID'] == '1668505983686113') {
        $linkOnClick = 'mvFlowChartExecuteInit(this, \''.$linkHref.'\', \'166848750564710\', true);';
        $linkHref = 'javascript:;';
    }

    if ($row['photoname'] != '' && file_exists($row['photoname'])) {
        $imgSrc = $row['photoname'];
    } else {
        $imgSrc = 'assets/custom/img/appmenu.png';
    }

    $bgImageStyle = '';
    if (issetParam($row['bgphotoname']) != '' && file_exists($row['bgphotoname'])) {
        $bgImageStyle = 'background-image: url('.$row['bgphotoname'].');background-size: cover;';
    }                

    $appInfoTextStyle = '';
    if ($bgImageStyle) {
        $appInfoTextStyle = 'text-shadow: 2px 2px 2px rgba(0,0,0,0.6);';
    }


    $indicatorId = $row['ID'];
    $linkHref = 'javascript:;';
    $linkOnClick = 'itemCardGroupInit(\''.$indicatorId.'\', this);';    


    $cards[] = '<a href="' . $linkHref . '" data-parentid="' . $parentId . '" target="' . $linkTarget . '" style="'.$cartbgColor.$bgImageStyle.'" onclick="' . $linkOnClick . '" data-code="" data-modulename="' . $row['name'] . '" class="vr-menu-tile mixa ' . $class . '" data-metadataid="' . $row['META_DATA_ID'] . '" data-pfgotometa="1">';

        $cards[] = '<div class="d-flex align-items-center">';
            $cards[] = '<div class="vr-menu-cell">';
            $cards[] = '</div>';
            $cards[] = '<div class="vr-menu-title">';
                $cards[] = '<div class="d-flex justify-content-between vr-menu-row'.(issetParam($row['menucode']) ? ' vr-menu-row-mcode' : '').'">';
                    $cards[] = '<div class="vr-menu-name" data-app-name="true" style="'.$appInfoTextStyle.'">' . $row['name'] . '</div>';
                    if (issetParam($row['menucode'])) {
                        $cards[] = '<div class="vr-menu-code mt6" style="'.$appInfoTextStyle.'" data-app-code="true">' . issetParam($row['menucode']) . '</div>';
                    }   
                $cards[] = '<div class="acard-is-child-div"><i class="icon-arrow-right8"></i></div>';
                $cards[] = '</div>';
            $cards[] = '</div>';
        $cards[] = '</div>';
    $cards[] = '</a>';    
    
    return $cards;
}

echo implode('', $cards); 
?>
