<?php 
(String) $html = '';

if (!$this->isAjax) {
    $html = '<div class="col-md-12 plan_'. $this->uniqId .'" id="tnaPlan">';
        $html .= '<div class="card light shadow tna-card">';
            $html .= '<div class="card-header card-header-no-padding header-elements-inline">';
                $html .= '<div class="card-title"><i class="fa fa-pencil-square"></i> '. $this->title .'</div>';
                $html .= '<div class="tools float-right"><a href="javascript:;" class="collapse"></a><a href="javascript:;" class="fullscreen"></a></div>';
            $html .= '</div>';
            $html .= '<div class="card-body xs-form row">';
}
    $html .= $this->planMain;
    
if (!$this->isAjax) {
            $html .= '</div>';
        $html .= '</div>';
    $html .= '</div>';
} 

$html .= '<div id="dialogDescription"></div>';

echo $html;
?>

