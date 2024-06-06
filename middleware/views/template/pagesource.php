<html>
<head>
<meta charset="utf-8" />
<title>Report Template</title>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<base href="<?php echo URL; ?>">
<link href="<?php echo autoVersion('assets/custom/css/print/reportPrint.css'); ?>" rel="stylesheet" type="text/css">
<style type="text/css">
    body {
        background-color: #fff;
        font-family: Arial;
        color: #000;
        padding: 0 <?php echo $this->pageMarginRight; ?> 0 <?php echo $this->pageMarginLeft; ?>;
    }
    table {
        border-collapse: collapse;
    }
    table thead th, table thead td, table tbody td, table tfoot td {
        padding: 3px;
    }
</style>
</head>
<body>
    <div id="rp-pagerender">
        <?php echo $this->contentHtml; ?>
    </div>
    <style type="text/css">
    span {
        line-height: 20px!important;
    }
    </style>
</body>
</html>