<?php
foreach ($this->userkeys as $uRow) {

    if (issetParam($uRow['userkeyid'])) {
        
        $id = Compression::gzdeflate($uRow['userkeyid']);
        $id = Str::urlCharReplace($id);

        $title = '<a href="login/connectClient/'.$id.'/'.$this->systemUserId.'" class="ukey-redirect">'.$uRow['departmentname'].'</a>';
        
    } else {
        $title = $uRow['departmentname'];
    }
    
    $hasChild = '';
    
    if (issetParam($uRow['haschild'])) {
        $hasChild = '<a href="javascript:;" class="ukey-child" data-id="'.$uRow['departmentid'].'"><i class="icon-tree6"></i></a>';
    }
    
    if (issetParam($uRow['objectphoto']) && file_exists($uRow['objectphoto'])) {
        $logo = $uRow['objectphoto'];
    } else {
        $logo = 'assets/custom/img/organization.png';
    }
?>
<div class="ukey-card" style="background-color: <?php echo issetParam($uRow['rowcolor']); ?>">
    <div class="ukey-logo-wrap">
        <img src="<?php echo $logo; ?>" class="ukey-logo"/>
    </div>
    <div class="ukey-h-line"></div>
    <div class="ukey-title"><?php echo $title; ?></div>
    <div class="ukey-footer"><?php echo $hasChild; ?></div>
</div>
<?php
}
?>