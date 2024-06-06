<?php 
$html = '';

if (!$this->isAjax) { 
    $html = '<div class="col-md-12 employeeTimeBalance_'. $this->uniqId .'" id="employeeTimeBalance">';
        $html .= '<div class="card light shadow tna-card">';
            $html .= '<div class="card-header card-header-no-padding header-elements-inline">';
                $html .= '<div class="card-title" style="background-color: transparent;"><i class="fa fa-pencil-square"></i> '. $this->title .'</div>';
                $html .= '<div class="tools float-right"><a href="javascript:;" class="collapse"></a></div>';
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

