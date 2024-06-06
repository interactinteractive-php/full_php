<?php
if ($this->statusList) {
?>
<div class="row wfm-status-step-fixed">
    <div class="col-md-12">
        <div class="wfm-status-step">
            <ul>
                <?php
                foreach ($this->statusList as $s => $step) {
                    
                    $cssClass = $icon = '';
                    
                    if ($s == 0 && $this->currentStatusId == $step['wfmstatusid']) {
                        
                        $cssClass = 'wfm-status-current';
                        $icon = '<i class="fa fa-cog"></i> ';
                        
                    } elseif ($this->currentStatusId == $step['wfmstatusid']) {
                        
                        $cssClass = 'wfm-status-current';
                        $icon = '<i class="fa fa-cog"></i> ';
                        
                    } elseif ($s == 0 || $step['logcount'] > 0) {
                        
                        $cssClass = 'wfm-status-done';
                        $icon = '<i class="fa fa-check"></i> ';
                    } 
                    
                    echo '<li><a href="javascript:;" class="'.$cssClass.'">'.$icon.$step['wfmstatusname'].'</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
</div>

<style type="text/css">
.wfm-status-step {
    text-align: left;
}
.wfm-status-step ul {
    list-style: none;
    display: inline-table;
    margin-bottom: 0;
    padding: 0;
}
.wfm-status-step ul li {
    display: inline;
}
.wfm-status-step ul li a {
    display: block;
    float: left;
    height: 30px;
    background-color: #eaedf4;
    text-align: center;
    padding: 5px 14px 0 28px;
    position: relative;
    margin: 0 5px 0 0; 
    font-size: 14px;
    text-decoration: none;
    color: #515f77;
    
    font-weight: 600;
    border-top: 1px #dbdee4 solid;
    border-bottom: 1px #dbdee4 solid;
}
.wfm-status-step ul li a:after {
    content: "";  
    border-top: 14px solid transparent;
    border-bottom: 14px solid transparent;
    border-left: 14px solid #eaedf4;
    position: absolute; 
    right: -14px; 
    top: 0;
    z-index: 1;
}
.wfm-status-step ul li a:before {
    content: "";  
    border-top: 14px solid transparent;
    border-bottom: 14px solid transparent;
    border-left: 14px solid #fff;
    position: absolute; 
    left: 0; 
    top: 0;
}
.wfm-status-step ul li:first-child a {
    border-top-left-radius: 17px; 
    border-bottom-left-radius: 17px;
}
.wfm-status-step ul li:first-child a:before {
    display: none; 
}
.wfm-status-step ul li:last-child a {
    padding-right: 28px;
    border-top-right-radius: 17px; 
    border-bottom-right-radius: 17px;
}
.wfm-status-step ul li:last-child a:after {
    display: none; 
}
.wfm-status-step ul li a.wfm-status-done {
    color: #fff;
    background-color: #4bca81;
    border-top: 1px #4bca81 solid;
    border-bottom: 1px #4bca81 solid;
}
.wfm-status-step ul li a.wfm-status-done:after {
    border-left: 14px solid #4bca81;
}
.wfm-status-step ul li a.wfm-status-current {
    color: #fff;
    background-color: #0070d2;
    border-top: 1px #0070d2 solid;
    border-bottom: 1px #0070d2 solid;
}
.wfm-status-step ul li a.wfm-status-current:after {
    border-left: 14px solid #0070d2;
}
.body-top-menu-style .wfm-status-step ul li a:before {
    border-left: 14px solid #5c798e;
}
.body-top-menu-style .wfm-status-step-fixed {
    width: 100%;
    position: fixed;
    z-index: 9;
    background-color: #5c798e;
    margin-top: 42px;
    padding: 5px 0 1px 0;
}
.body-top-menu-style .bp-file-view-header .wfm-status-step ul li a:before {
    border-left: 14px solid #809fbb;
}
.body-top-menu-style .bp-file-view-header .wfm-status-step-fixed {
    width: 100%;
    position: static;
    z-index: 9;
    background-color: transparent;
    margin-top: 0;
    padding: 0;
}
</style>
<?php
}
?>