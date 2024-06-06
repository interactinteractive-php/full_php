<?php 
(String) $html = '';

if (!$this->isAjax) { 
    $html = '<div class="col-md-12 employeeTimeBalance_'. $this->uniqId .'" id="employeeTimeBalance">';
        $html .= '<div class="card light shadow tna-card">';
            $html .= '<div class="card-header card-header-no-padding header-elements-inline">';
                $html .= '<div class="card-title"><i class="fa fa-pencil-square"></i> '. $this->title .'</div>';
                $html .= '<div class="caption buttons ml10 float-right">'. $this->balanceBtn['all'] 
                            . '<button type="button" style="padding:0px 7px 1px 7px" data-uniqid="'. $this->uniqId .'" class="btn btn-sm yellow-crusta btn-circle mr10 float-right downloadData" title="Өгөгдөл татах"><i class="fa fa-download"></i> Өгөгдөл татах</button>'
                        .'</div>';
                $html .= '<div class="tools float-right"><a href="javascript:;" class="collapse"></a><a href="javascript:;" class="fullscreen"></a></div>';
            $html .= '</div>';
            $html .= '<div class="card-body xs-form row">';
}
    $html .= $this->timebalanceMain;
    
if (!$this->isAjax) {
            $html .= '</div>';
        $html .= '</div>';
    $html .= '</div>';
} 

$html .= '<div id="dialog-fillInFor-employee"></div>'
        . '<div id="dialogDescription"></div>'
        . '<div id="loadAccount"></div>';

echo $html;
?>

