<?php

$i = 1;
        
$pageBreakCss = '';
$addTableLayoutFixedClass = (isset($this->isTableLayoutFixed) && $this->isTableLayoutFixed) ? ' class="reportTableLayoutFixed"' : '';
$contentCount = count($this->contentHtml);

if ($this->isPrintNewPage == 0 && $this->pt != '2colrow') {

    $pageBreakCss = 'page-break-before: auto !important; page-break-after: auto !important; page-break-inside: avoid !important;';
    echo '<page size="'.$this->pageSize.'" id="'.$this->pageOrientation.'">';
}

foreach ($this->contentHtml as $value) {

    if ($this->pt != '2colrow') {
        echo ($this->isPrintNewPage == 1 ? '<page size="'.$this->pageSize.'" id="'.$this->pageOrientation.'">' : '');
    }

    if ($this->pt == '2colrow' && ($i % 2 == 1)) {
        echo '<page size="'.$this->pageSize.'" id="'.$this->pageOrientation.'">';
    }

    if ($this->pageOrientation == 'landscape') { //Landscape page

        if ($this->pt == '1col') {

            if (isset($this->pageMargin)) {
                $isMarginConfig = true;
            }

            $pageWidth = '100%';
            $pageWidthSplit = $pageWidthSplitPlus = '50%';

            if ($this->paperInput == 'portrait' && $this->pageSize == 'a5') {
                $pageWidth = '21cm';
                $pageWidthSplit = '10.5cm';
                $pageWidthSplitPlus = '13.5cm';
            }

            echo '<div id="externalContent" style="width:'.$pageWidth.';"'.$addTableLayoutFixedClass.'>';

            for ($i = 1; $i <= $this->numberOfCopies; $i++) {
                echo '<table style="width: '.$pageWidth.'"><tr>';

                if ($this->isPrintPageRight == '1') {
                    echo '<td width="54%" style="padding: 0 !important"></td>';
                    echo '<td width="46%" style="padding: 0 !important">'.$value.'</td>';
                } else {
                    echo '<td style="width: '.$pageWidthSplit.'; padding: 0 !important">'.$value.'</td>';
                    echo '<td style="width: '.$pageWidthSplitPlus.'; padding: 0 !important"><table style="width: '.$pageWidthSplitPlus.'"><tbody><tr><td style="width: '.$pageWidthSplitPlus.'">&nbsp</td></tr></tbody></table></td>';
                }

                echo '</tr></table>';
            }

            echo '</div>';

        } elseif ($this->pt == '2col') {

            $pageWidth = '33.7cm';
            $pageWidthSplit = $pageWidthSplitPlus = '16.7cm';

            if ($this->paperInput == 'portrait' && $this->pageSize == 'a5') {
                $pageWidth = '21cm';
                $pageWidthSplit = '10.5cm';
                $pageWidthSplitPlus = '13.5cm';
            }

            echo '<div id="exContent" style="width:'.$pageWidth.';"'.$addTableLayoutFixedClass.'>';

            for ($i = 1; $i <= $this->numberOfCopies; $i++) {

                echo ($i % 2 == 1 ? '<table '.($i == 1 ? 'id="externalContent"' : '').' style="width:'.$pageWidth.';"><tr>' : '');
                echo '<td style="width:'.$pageWidthSplit.'; padding: 0 !important">'.$value.'</td>';
                echo ($i % 2 == 0 && $this->numberOfCopies == 1 || $i % 2 == 1 && $this->numberOfCopies == $i ? '<td style="width:'.$pageWidthSplitPlus.'; padding: 0 !important"><table style="width: '.$pageWidthSplitPlus.'"><tbody><tr><td style="width: '.$pageWidthSplitPlus.'; padding: 0 !important">&nbsp</td></tr></tbody></table></td>' : '');
                echo ($i % 2 == 0 || $i == $this->numberOfCopies ? '</tr></table>' : '');
            } 

            echo '</div>';

        } elseif ($this->pt == '2colrow') { 

            $pageWidth = '33.7cm';
            $pageWidthSplit = $pageWidthSplitPlus = '16.7cm';

            if ($this->paperInput == 'portrait' && $this->pageSize == 'a5') {
                $pageWidth = '21cm';
                $pageWidthSplit = '10.5cm';
                $pageWidthSplitPlus = '13.5cm';
            }

            if ($i % 2 == 1) {
                echo '<div id="exContent" style="width:'.$pageWidth.';"'.$addTableLayoutFixedClass.'>';
                echo '<table id="externalContent" style="width:'.$pageWidth.';"><tr>';
            }

            echo '<td style="width:'.$pageWidthSplit.'; padding: 0 !important">'.$value.'</td>';

            if ($i % 2 == 0 || $contentCount == $i) {
                echo '</tr></table>';
                echo '</div>';
            }

        } else {

            if (isset($this->pageMargin)) {
                $isMarginConfig = true;
            }

            echo '<div id="externalContent" style="width:100%;"'.$addTableLayoutFixedClass.'>';

            for ($i = 1; $i <= $this->numberOfCopies; $i++) {

                echo '<table style="width: 100%" width="100%"><tr>';
                echo '<td width="100%" style="padding: 0 !important">'.$value.'</td>';
                echo '</tr></table>';
            }

            echo '</div>';
        }

    } else { //Portrait page

        if ($this->pt == '1col') {

            if (isset($this->pageMargin)) {
                $isMarginConfig = true;
            }

            for ($i = 1; $i <= $this->numberOfCopies; $i++) {
                echo '<table '.($i == 1 ? 'id="externalContent"' : '').' style="width:100%;'.$pageBreakCss.'"><tr>';
                echo '<td align="center" style="width:100%; padding: 0 !important"'.$addTableLayoutFixedClass.'>'.$value.'</td>';
                //echo ($i%2==0 || $i==$this->numberOfCopies ? '<td align="center" width="'.($this->numberOfCopies==1 ? '100%':'50%').'"></td>':'');
                echo '</tr></table>';
            }

        } elseif ($this->pt == '2col') {

            echo '<div id="exContent" style="width:21cm;"'.$addTableLayoutFixedClass.'>';

            for ($i = 1; $i <= $this->numberOfCopies; $i++) {

                echo ($i % 2 == 1 ? '<table '.($i == 1 ? 'id="externalContent"' : '').' style="width:21cm;"><tr>':'');
                echo '<td style="width:10.5cm; padding: 0 !important" align="center"><center>'.$value.'</center></td>';
                echo ($i % 2 == 0 && $this->numberOfCopies == 1 || $i % 2 == 1 && $this->numberOfCopies == $i ? '<td align="center" style="width:10.5cm; padding: 0 !important"></td>' : '');
                echo ($i % 2 == 0 || $i == $this->numberOfCopies ? '</tr></table>' : '');
            } 
            echo '</div>';

        } else {

            if (isset($this->pageMargin)) {
                $isMarginConfig = true;
            }

            for ($i = 1; $i <= $this->numberOfCopies; $i++) {
                echo '<table '.($i == 1 ? 'id="externalContent"' : '').' style="width:100%;"><tr>';
                echo '<td align="center" style="width:100%; padding: 0 !important"'.$addTableLayoutFixedClass.'>'.$value.'</td>';
                echo '</tr></table>';
            }

        }
    }

    if ($this->pt == '2colrow' && (($i % 2 == 0) || ($contentCount == $i))) {
        echo '</page>';
    }

    if ($this->pt != '2colrow') {
        echo ($this->isPrintNewPage == 1 ? '</page>' : '');
    }

    $i++;
}

if ($this->isPrintNewPage == 0 && $this->pt != '2colrow') {
    echo '</page>';
}