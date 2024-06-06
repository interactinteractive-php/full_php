<?php
use JonnyW\PhantomJs\Client;

define('BASEPATH', str_replace('api', '', dirname(dirname(__FILE__))));
define('_VALID_PHP', true);

require '../../config/config.php';

switch (ENVIRONMENT) {
    case 'development':
        ini_set('display_errors', 'On');
        ini_set('display_startup_errors', 'On');
        error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);
    break;
    case 'production':
        ini_set('display_errors', '0');
        ini_set('display_startup_errors', '0');
        error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);
    break;
    default:
        exit('The application environment is not set correctly.');
}

require '../../helper/functions.php';
require '../../libs/Security.php';
require '../../libs/Str.php';
require '../../libs/Input.php';

$format = Input::post('format');
$fileName = Input::post('filename');
$isSaveImage = Input::post('saveImage');
$withoutExtFileName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $fileName);

if ($isSaveImage) {
    $xml = str_replace('https', 'http', $_POST['xml']);
} else {
    $xml = str_replace('https', 'http', urldecode($_POST['xml']));
}

if ($format == 'xml') {
    
    $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>'.$xml;
    
    header('Content-Type: text/xml; charset="utf8"');
    header('Content-Disposition: attachment; filename="'.$withoutExtFileName.'.xml"');
    Header('Cache-control: private, no-cache, no-store');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo $xml; exit;
    
} elseif ($format == 'svg') {
    
    $svg = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>'.$xml;
    
    header('Content-Type: image/svg+xml; charset="utf8"');
    header('Content-Disposition: attachment; filename="'.$withoutExtFileName.'.svg"');
    Header('Cache-control: private, no-cache, no-store');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo $svg; exit;
    
} elseif ($format == 'pdf') {
    
    /*require '../../libs/PDF/Pdf.php';
    
    $_POST['left'] = $_POST['right'] = $_POST['isIgnoreFooter'] = 1;
    
    $xml = str_replace('[Not supported by viewer]', '', $xml);
    
    $pdf = Pdf::createSnappyPdf('Portrait', 'A4');
    Pdf::setSnappyOutput($pdf, $xml, $withoutExtFileName);*/
    
    require '../phantomjs/index.php';
    
    $uniqId = getUID();
    $html = '<!DOCTYPE html><html><head><meta charset="utf-8"/></head><body>'.$xml.'</body></html>';
    $filePath = '../../log/'.$uniqId.'.html';
    $outputFile = '../../log/'.$uniqId.'.'.$format;
    
    file_put_contents($filePath, $html);
    
    $client = Client::getInstance();

    // isLazy() is useful when we want to wait for all resources on the page to load.
    $client->isLazy();
    
    if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
        $client->getEngine()->setPath('C:\phantomjs\bin\phantomjs.exe');
    } else {
        $client->getEngine()->setPath('/home/backend/phantomjs-2.1.1-linux-x86_64/bin/phantomjs');
    }

    $client->getEngine()->addOption('--load-images=true');
    $client->getEngine()->addOption('--ignore-ssl-errors=true');
    $client->getEngine()->addOption('--ssl-protocol=any');
    $client->getEngine()->addOption('--debug=true');
    
    $url = str_replace('https', 'http', URL);
    
    $request = $client->getMessageFactory()->createPdfRequest($url.'log/'.$uniqId.'.html');

    $request->setBodyStyles(['backgroundColor' => '#ffffff']);
    $request->setFormat('A4');
    $request->setOrientation('landscape');
    $request->setMargin('0.5cm');
    $request->setOutputFile($outputFile);

    $response = $client->getMessageFactory()->createResponse();
    $client->send($request, $response);
    
    @unlink($filePath);
    
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="'.$withoutExtFileName.'.'.$format.'"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($outputFile));
    
    ob_clean();
    flush();
    readfile($outputFile);
    
    exit;
    
} else {
    
    require '../phantomjs/index.php';
    
    $uniqId = getUID();
    $html = '<!DOCTYPE html><html><head><meta charset="utf-8"/></head><body>'.$xml.'</body></html>';
    $filePath = '../../log/'.$uniqId.'.html';
    $outputFile = '../../log/'.$uniqId.'.'.$format;
    if ($isSaveImage) {
        $outputFile = '../../storage/uploads/process/'.$isSaveImage.'.'.$format;
    }
    
    file_put_contents($filePath, $html);
    
    $client = Client::getInstance();

    // isLazy() is useful when we want to wait for all resources on the page to load.
    $client->isLazy();
    
    if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
        $client->getEngine()->setPath('C:\phantomjs\bin\phantomjs.exe');
    } else {
        $client->getEngine()->setPath('/home/backend/phantomjs-2.1.1-linux-x86_64/bin/phantomjs');
    }
        
    $client->getEngine()->addOption('--load-images=true');
    $client->getEngine()->addOption('--ignore-ssl-errors=true');
    
    $url = str_replace('https', 'http', URL);

    $request = $client->getMessageFactory()->createCaptureRequest($url.'log/'.$uniqId.'.html');
    
    $width  = Input::post('w');
    $height = Input::post('h');
    $top    = 0;
    $left   = 0;
    
    $request->setViewportSize($width, $height);
    $request->setBodyStyles(['backgroundColor' => '#ffffff']);
    $request->setFormat($format);
    $request->setQuality(100);
    $request->setOutputFile($outputFile);

    $response = $client->getMessageFactory()->createResponse();
    $client->send($request, $response);
    
    @unlink($filePath);
    
    if (!$isSaveImage) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$withoutExtFileName.'.'.$format.'"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($outputFile));
        ob_clean();
        flush();
        readfile($outputFile);

        @unlink($outputFile);
        
    } else {
        $arr = array('status' => $response->getStatus());
        echo json_encode($arr);
    }
    
    exit;
    
    /*require '../../libs/DOM/phpquery/phpQuery/phpQuery.php';
    
    $xml = str_replace(array('&lt;', '&gt;', '&quot;', "&nbsp;"), array('<', '>', '"', ' '), $xml);
    
    $xmlDom = phpQuery::newDocumentHTML($xml);
    $foreignObjects = $xmlDom->find('foreignobject');
    $foreignObjectsCount = $foreignObjects->length();
    
    if ($foreignObjectsCount) {
        
        foreach ($foreignObjects as $foreignObject) {
                
            $foreignObjectDiv = pq($foreignObject)->find('div:last');
            $foreignObjectBr = $foreignObjectDiv->find('br');
            
            $foreignObjectText = '';
            
            if ($foreignObjectBr->length()) {
                
                $foreignObjectHtml = $string = trim(preg_replace('/\s+/', ' ', $foreignObjectDiv->html()));
                $foreignObjectBrs = explode('<br>', $foreignObjectHtml);
                
                foreach ($foreignObjectBrs as $foreignObjectBr) {
                    $foreignObjectBr = trim($foreignObjectBr);
                    if ($foreignObjectBr != '') {
                        $foreignObjectText .= '<tspan x="0" dy="1.2em">'.$foreignObjectBr.'</tspan>';
                    }
                }
                
            } else {
                $foreignObjectText = $foreignObjectDiv->text();
            }
            
            pq($foreignObject)->next('text')->html($foreignObjectText);
            pq($foreignObject)->remove();
        }
        
        $xml = $xmlDom->html();
    }
    
    $svg = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>'.$xml;

    $im = new Imagick();

    $im->readImageBlob($svg);
    $im->setImageFormat($format);

    header('Content-Type: image/'.$im->getImageFormat().'; charset="utf8"');
    header('Content-Disposition: attachment; filename="'.$withoutExtFileName.'.'.$format.'"');
    Header('Cache-control: private, no-cache, no-store');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo $im->getImageBlob(); exit;*/
}