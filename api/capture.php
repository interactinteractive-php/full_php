<?php
if (isset($_GET['format']) && $_GET['format'] != '' && isset($_GET['url']) && $_GET['url'] != '') {
    
    define('_VALID_PHP', true);
    require '../helper/functions.php';
    
    $url = $_GET['url'];
    
    if (filter_var($url, FILTER_VALIDATE_URL) && isValidUrl($url)) {
        
        $realpath = str_replace('api\capture.php', '', realpath(__FILE__));
        $realpath = str_replace('api/capture.php', '', $realpath);
        
        define('BASEPATH', $realpath);
        require '../config/config.php'; 

        $format = strtolower($_GET['format']);

        if ($format == 'pdf') {

            require '../libs/PDF/Pdf.php';

            $options = array(
                'title'            => 'Veritech ERP',
                'orientation'      => 'Landscape',
                'page-size'        => 'A4',
                'encoding'         => 'UTF-8',
                'margin-top'       => 10,
                'margin-left'      => 10,
                'margin-right'     => 10,
                'margin-bottom'    => 10,
                'images'           => true,
                'enable-javascript'=> true, 
                'javascript-delay' => 3000, 
                'viewport-size'    => '1800x1000', 
                'footer-line'      => false
            );

            $pdf = Pdf::webUrlToPdf($options);

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="file.pdf"');

            exit($pdf->getOutput($url));
            
        } else {
            echo $format.' format is not valid!'; exit;
        }
        
    } else {
        echo 'URL is not valid!'; exit;
    }
    
}