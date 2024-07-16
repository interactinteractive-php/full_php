<?php

    $moduleList = $this->response['rows'];

    $colorSet = '#fff';
    $cards = [];
    $firstModule = $firstModuleCode = '';
    $colorSet = explode(',', $colorSet);
    $parentId = $this->parentId;

    if (empty($parentId)) {    
        foreach ($moduleList as $rowParent) {

            if (issetParam($rowParent['CHILDRECORDCOUNT'])) {
                
                $cards[] = '<h2 class="ml-2 pt10 pb0 mb0" style="display: inline-block;width: 100%;text-transform: uppercase;font-family: Arial;font-size: 16px;">'.$rowParent[$this->relationViewConfig['c2']].'</h2>';
                $cards[] = '<h2 class="ml-2 pt0 pb10" style="font-weight: normal;display: inline-block;width: 100%;font-size: 12px;color:#67748E">'.($rowParent[$this->relationViewConfig['c3']] ? $rowParent[$this->relationViewConfig['c3']] : 'Тайлбар бичсэн бол энд харуулна').'</h2>';            

                foreach (issetParamArray($rowParent['CHILDREN']) as $row) {
                    // if (empty($row['relatedindicator'])) continue;
                    aCardHtml($cards, $row, $colorSet, $parentId, $this->uid, $this->relationViewConfig);
                }
            }
        }            
        
    } else {
        if ($moduleList) {
            foreach ($moduleList as $row) {
                // if (empty($row['relatedindicator'])) continue;
                aCardHtml($cards, $row, $colorSet, $parentId, $this->uid, $this->relationViewConfig);
            }
        }
    }

    function aCardHtml(&$cards, $row, $colorSet, $parentId, $uid, $relationViewConfig) {    
        
        $colorSetIndex = array_rand($colorSet);

        $row['photoname'] = '';
        $row['META_DATA_ID'] = '';
        $row['ID'] = $row['ID'];

        $linkHref = 'javascript:;';
        $linkTarget = '_self';
        $linkOnClick = '';
        $class = ' random-border-radius3 card-ischild-'.((issetParam($row['CHILDREN']) || issetParam($row['CHILDRECORDCOUNT'])) ? '1' : '0');
        $cartbgColor = '';

        if ($row[$relationViewConfig['c4']]) {
            $cartbgColor = 'background-color:'.$row[$relationViewConfig['c4']].';';
        } else {
            $cartbgColor = 'background-color:'.(issetParam($row['color']) ? $row['color'] : '#FF7E79').';';
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
        $linkOnClick = 'itemCardGroupInit'. $uid .'(\''.$indicatorId.'\', this);';    
        
        if (!issetParam($row['children']) && !issetParam($row['CHILDRECORDCOUNT'])) {
            $linkHref = 'javascript:;';
            $linkOnClick = 'mvProductAppmenuCardRender'. $uid .'(\''.$indicatorId.'\', \''. issetParam($row['DATA_FOLDER_ID']) .'\', \''. issetParam($row['PROCESS_FOLDER_ID']) .'\');';            
        }

        $cards[] = '<a href="' . $linkHref . '" data-parentid="' . $parentId . '" target="' . $linkTarget . '" style="'.$cartbgColor.$bgImageStyle.'" onclick="' . $linkOnClick . '" data-code="" data-modulename="' . $row[$relationViewConfig['c2']] . '" class="vr-menu-tile mixa ' . $class . '" data-metadataid="' . $row['META_DATA_ID'] . '" data-pfgotometa="1">';

            $cards[] = '<div class="d-flex align-items-center">';
                $cards[] = '<div class="vr-menu-cell">';
                $cards[] = '</div>';
                $cards[] = '<div class="vr-menu-title">';
                    $cards[] = '<div class="d-flex justify-content-between vr-menu-row'.(issetParam($row['CODE']) ? ' vr-menu-row-mcode' : '').'" style="height: 38px;">';
                        $cards[] = '<div class="vr-menu-name" data-app-name="true" style="'.$appInfoTextStyle.'">' . $row[$relationViewConfig['c2']] . '</div>';
                    $cards[] = '<div class="acard-is-child-div"><i class="icon-arrow-right8"></i></div>';
                    $cards[] = '</div>';
                $cards[] = '</div>';
            $cards[] = '</div>';
        $cards[] = '</a>';    
        
        return $cards;
    }

    echo implode('', $cards); 
?>
            