<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <script src="<?php echo URL; ?>assets/custom/addon/plugins/print-header-footer/node_modules/css-regions-polyfill/bin/css-regions-polyfill.min.js"></script>
        <script src="<?php echo URL; ?>assets/custom/addon/plugins/print-header-footer/src/print-headers-and-footers.js"></script>
        <link rel="stylesheet" href="<?php echo URL; ?>assets/custom/addon/plugins/print-header-footer/src/print-headers-and-footers.css">
    </head>

    <body>
        <div id="print-container"> 
            <?php echo html_entity_decode($this->html, ENT_QUOTES, 'UTF-8'); ?>
        </div>

        <style media="print">
            @page {
/*                margin-top: 50px;
                margin-bottom: 50px;*/
                width: 100%;
            }
        </style>		

        <!--marginTop: '<?php // echo $this->top ? $this->top : 5; ?>',-->
        <!--marginBottom: '<?php // echo $this->bottom ? $this->bottom : 5; ?>',-->        
        <script>
            PrintHAF.init({
                domID: 'print-container',
                size: 'legal',
                marginTop: '50',
                marginBottom: '50',
                marginLeft: '<?php echo $this->left ? $this->left : 84; ?>',
                marginRight: '<?php echo $this->right ? $this->right : 84; ?>',
                createHeaderTemplate: function (pageNumber) {
                    var header = document.createElement('div');
                    header.innerHTML = '<?php echo html_entity_decode($this->headerTemp, ENT_QUOTES, 'UTF-8'); ?>';

                    return header;
                },
                createFooterTemplate: function (pageNumber) {
                    var footer = document.createElement('div');
                    footer.innerHTML = '<span style="float: left"><?php echo html_entity_decode($this->footerTemp, ENT_QUOTES, 'UTF-8'); ?></span>' + '<span style="float: right">' + pageNumber + '</span>';

                    return footer;
                }
            });
            
            PrintHAF.print();
        </script>
    </body>

</html>