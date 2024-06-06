<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');  ?>

<div class="page_<?php echo $this->uniqId ?> col-md-12">
<?php 
    if (issetParamArray($this->pageJson['page']['body'])) {
        $pageHtml = $pageCss = '';
        $pageCss .= '<style type="text/css"> .page_' . $this->uniqId . ' { ';
        
        $pageAttr = Mdwidget::renderShowFields(array($this->pageJson['page']['body']), $this->pageJson['page']['body']['widgetCode'], $this->uniqId);
        $pageHtml .= issetParam($pageAttr['html']);
        $pageCss .= issetParam($pageAttr['css']);

        $pageCss .= "} </style>";
        echo  $pageHtml . $pageCss;
    }

?>
</div>