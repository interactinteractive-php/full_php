<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdtemplate Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Report Template
 * @author	B.Och-Erdene, Ts.Ulaankhuu
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdtemplate
 */
class Mdtemplate extends Controller {

    private $viewPath = 'middleware/views/template/';
    private static $employeeNDDmetaCode = 'salarySiDataByMonth';
    public static $dataViewColumnsType = array();
    public static $dataViewColumnsTypeScale = array();
    public static $dataViewColumnsSetScale = array();
    public static $responseData = array();
    public static $rtRow = array();
    public static $mergeResponseData = array();
    public static $templateLinkId = null;
    public static $templateDataModelId = null;
    public static $getListCommand = null;
    public static $mobileJson = null;
    public static $isKpiIndicator = false;
    public static $numIterations = 0;
    public static $isRunMobile = 0;
    private $reportData;

    public function __construct() {
        parent::__construct();
    }
    
    public static function getTypeCodeTemplate($dataViewColumnsType, $column) {
        
        if (isset($dataViewColumnsType[$column])) {
            return $dataViewColumnsType[$column];
        }
        
        return 'string';
    }
    
    public static function valueFormatting($value, $key) {
        
        $key = strtolower($key);
        $typeCode = self::getTypeCodeTemplate(Mdtemplate::$dataViewColumnsType, $key);
        
        switch ($typeCode) {
            case 'bigdecimal':
            case 'decimal':
            case 'number':    
                return Number::formatMoney($value, true);
                break;
            case 'scale':    
                return Mdtemplate::detailFormatMoneySetScale($value, $key);
                break;
            case 'date':
                return Date::formatter($value, 'Y-m-d');
                break;
            case 'datetime':
                return Date::formatter($value, 'Y-m-d H:i:s');
                break;
            case 'boolean':
            case 'check':    
                return Info::convertLetterToNumberBoolean(Info::showYesNoByNumber($value));
                break;    
            case 'base64':
                return '<img src="data:image/jpeg;base64,'.$value.'" style="width: 60px; height : 60px">';
                break;
            case 'signature':
                return '<img src="data:image/jpeg;base64,'.$value.'" style="width: 80px; height : 60px">';
                break;
            case 'text_editor':
            case 'html_decode':
                return Str::cleanOut($value);
                break;
            case 'decimal_to_time':
                if ($value != '' && $value != '0') {
                    $h = floor($value / 60);
                    $m = $value % 60;
                    $h = $h < 10 ? '0'.$h : $h;
                    $m = $m < 10 ? '0'.$m : $m;
                    return $h.':'.$m;
                } 
                return $value;
                break;
            case 'clob':
                
                switch ($key) {
                    case 'requeststring':
                        $array = json_decode($value, true);
                        if (is_array($array) && $array) {
                            return $this->clobToHtml($array);
                        } else {
                            return $value;
                        }
                        break;
                    case 'responsestring':
                        $array = json_decode($value, true);
                        if (is_array($array) && $array && isset($array['result'])) {
                            return $this->clobToHtml($array['result']);
                        } else {
                            return $value;
                        }
                        break;

                    default:
                        return Str::cleanOut($value);
                }
                
                break;
            default:
                return Str::nlTobr($value);
                break;
        }
    }
    
    public static function clobToHtml($array) {
        $chtml = '<ul>';
        
        foreach ($array as $key => $row) {
            
            switch ($key) {
                case 'sessionUserId':
                case 'civilId':
                    break;
                
                case 'citizenFingerPrint':
                case 'operatorFingerPrint':
                case 'fingerprint':
                case 'image':
                    $chtml .= '<li>' . Lang::line($key) . ': <strong><img src="data:image/jpeg;base64,'.$row.'" style="width: 45px; height : 60px"></strong></li>';
                    break;
                default:
                    if (is_array($row)) {
                        $chtml .= $this->changeHtmlClob($row, $chtml);
                    } else {
                        $chtml .= '<li>' . Lang::line($key) . ': <strong>' . $row . '</strong></li>';
                    }
                    break;
            }
            
        }

        $chtml .= '</ul>';

        return $chtml;
    }
    
    public function changeHtmlClob($val, $chtml) {
        foreach ($val as $key => $value) {
            switch ($key) {
                case 'sessionUserId':
                case 'citizenFingerPrint':
                case 'operatorFingerPrint':
                case 'civilId':
                case 'fingerprint':
                    break;

                default:
                    if (is_array($value)) {
                        $chtml .= $this->changeHtmlClob($value, $chtml);
                    } else {
                        $chtml .= '<li>' . $key . ': <strong>' . $value . '</strong></li>';
                    }
                    break;
            }
        }

        return $chtml;
    }

    public static function detailFormatMoneySetScale($v, $field) {
        if (empty($v)) {
            return '0';
        } else {
            $scale = Mdtemplate::$dataViewColumnsTypeScale[$field];
            $number = number_format($v, $scale, '.', ',');
            
            return $number;
        }
    }

    public function renderTemplate($dataElement, $templateId, $isTemplateMetaId = false, $dataModelId = null) {
        
        $this->load->model('mdtemplate', 'middleware/models/');
        
        $template = $this->model->getReportTemplate($templateId, $isTemplateMetaId);
        
        if ($template) {
            
            Mdtemplate::$templateLinkId = $template['ID'];
            Mdtemplate::$templateDataModelId = $template['DATA_MODEL_ID'];

            $dataViewColumnsType = $this->model->getTypeCodeDataViewModel(Mdtemplate::$templateDataModelId);
        
            if ($template['HTML_CONTENT'] == '') {
                
                if (file_exists($template['HTML_FILE_PATH'])) {
                    $template['HTML_CONTENT'] = file_get_contents($template['HTML_FILE_PATH']);
                }
                
            } else {
                includeLib('Compress/Compression');
                $template['HTML_CONTENT'] = html_entity_decode(Compression::decompress($template['HTML_CONTENT']), ENT_QUOTES, 'UTF-8');
            }
        }
        
        $templateHTML = '';
        
        if ($template['HTML_CONTENT'] != '') {
            
            $templateHTML = htmlspecialchars_decode($template['HTML_CONTENT']);
            $templateHTML = str_replace('background-color: #', 'background-color: colorCode', $templateHTML);
            
            if (!empty($dataElement)) {                
                
                Mdtemplate::$responseData = $dataElement;
                
                $templateHTML = self::aggregateDtlGroup($templateHTML, $dataElement, $dataModelId);
                
                foreach ($dataElement as $key => $value) { 
                    
                    if (strpos($templateHTML, $key) !== false) {
                        
                        if (!is_array($value)) {
                            
                            $templateHTML = str_replace('#'.$key.'#', Mdtemplate::valueFormatting($value, $key), $templateHTML);
                            
                        } else {
                            
                            if (strpos($templateHTML,  '#'.$key.'#') !== false) {
                                $templateHTML = self::parseTemplateDtl($templateHTML, $key, $value, $template['DATA_MODEL_ID'], 0);
                            }
                            
                            //convert table dtl
                            $specAllTable = self::getContents($templateHTML, '<table', '>');
                            
                            foreach ($specAllTable as $v) {
                                $specificTable = new SimpleXMLElement("<element $v />");
                                
                                if (isset($specificTable['id'])) {
                                    
                                    $tableId = $specificTable['id'];
                                    
                                    if ($tableId == $key) {
                                        $table = self::getTable($tableId, $templateHTML);
                                        $templateHTML = self::parseSpecificPartOfTable($key, $table, $value, $templateHTML, $tableId, $template['DATA_MODEL_ID']);
                                    }
                                }
                            }
                            
                            //convert footer methods
                            $footerMethods = self::getContents($templateHTML, '{', '}');
                            
                            if (!empty($footerMethods)) {
                                
                                $args = self::getFooterMethods();
                                $specAllTable = self::getContents($templateHTML, '<table', '</table>');
                                
                                foreach ($specAllTable as $v) {
                                    
                                    foreach ($args as $arg) {
                                        
                                        if (strpos($v, '{' . $arg . '}')) {
                                            $tableTr = self::getContents($v, '<tr>', '</tr>');
                                            $trFoot = end($tableTr);
                                            $footTds = self::getContents($trFoot, '<td>', '</td>');
                                            
                                            foreach ($footTds as $key => $foot) {
                                                if (strpos($foot, '{' . $arg . '}')) {
                                                    $footerValue = self::parseFooterMethods($arg, $v, $key);
                                                    $foot = str_replace('{' . $arg . '}', $footerValue, $foot);
                                                    $templateHTML = str_replace('{' . $arg . '}', Format::valueFormatting($foot, 'number'), $templateHTML);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                
                $templateHTML = self::countDtlGroup($templateHTML, $dataElement);
            }
            
            //convert constant values
            $constantValues = self::getContents($templateHTML, '*', '*');
            
            if (!empty($constantValues)) {
                $constants = self::getConstantValues();
                
                foreach ($constantValues as $cons) {
                    if (isset($constants[$cons])) {
                        $templateHTML = str_replace('*' . $cons . '*', $constants[$cons], $templateHTML);
                    }
                    if (substr($cons, 0, 7) == 'config_') {
                        $configValue = self::configValueReplacer($cons);
                        $templateHTML = str_replace('*' . $cons . '*', $configValue, $templateHTML);
                    }
                }
            }
            
            //check new wrapped values
            $templateHTML = self::sysKeywordReplacer($templateHTML);
            $templateHTML = Mdstatement::configValueReplacer($templateHTML);
            $templateHTML = self::dottedConfigValueReplacer($templateHTML);
            $templateHTML = Mdstatement::textStyler($templateHTML);
            $templateHTML = self::rowRowsPathReplacer($templateHTML);
            
            $templateHTML = preg_replace('/\#([A-Za-z0-9_.-]+)\#/s', '', $templateHTML);
            
            $templateHTML = Mdstatement::calculateExpression($templateHTML);
            $templateHTML = Mdstatement::runExpression($templateHTML);
            $templateHTML = Mdstatement::runExpressionStr($templateHTML);
            $templateHTML = Mdstatement::runExpressionTag($templateHTML);
            $templateHTML = self::absoluteAmount($templateHTML);
            $templateHTML = self::setScale($templateHTML);
            $templateHTML = Mdstatement::numberToWords($templateHTML);
            $templateHTML = Mdstatement::reportDateDiff($templateHTML);
            $templateHTML = Mdstatement::editable($templateHTML);
            $templateHTML = Mdstatement::assetsReplacer($templateHTML);
            $templateHTML = Mdstatement::barcode($templateHTML);
            $templateHTML = Mdstatement::langLine($templateHTML);
            $templateHTML = Mdstatement::reportSubstr($templateHTML);
            $templateHTML = Mdstatement::reportCase($templateHTML);
            $templateHTML = Mdstatement::reportPageBreak($templateHTML);
            $templateHTML = Mdstatement::qrcode($templateHTML);
            $templateHTML = Mdstatement::replaceCyrillicToLatin($templateHTML);
            $templateHTML = self::printPivotDetail($templateHTML);
            $templateHTML = self::printKpiForm($templateHTML);
            $templateHTML = self::renderChartReplacer($templateHTML);
        }    
        
        $templateHTML = str_replace('background-color: colorCode', 'background-color: #', $templateHTML);
        $templateHTML = preg_replace('/(;| )\/([_\-.,A-Za-zА-Яа-яӨҮөүх0-9]+)\//u', '$1<nobr>/$2/</nobr>', $templateHTML);
        $templateHTML = preg_replace('/(;| )\/([_\-.,A-Za-zА-Яа-яӨҮөүх0-9]+)(&nb| )/u', '$1<nobr>/$2</nobr>$3', $templateHTML);
        $templateHTML = preg_replace('/(;| )([_\-.,A-Za-zА-Яа-яӨҮөүх0-9]+)\/(&nb| )/u', '$1<nobr>$2/</nobr>$3', $templateHTML);
        
        return $templateHTML;
    }
    
    public static function rowRowsPathReplacer($html) {
        preg_match_all('/\#([A-Za-z0-9_.-]+)\#/i', $html, $pathReplace);
        
        if (isset($pathReplace[1][0])) {
            foreach ($pathReplace[1] as $k => $path) {
                if (strpos($path, '.') !== false) {
                    $path = strtolower($path);
                    $pathArr = explode('.', $path); 
                    $groupPath = $pathArr[0];
                    $fieldPath = $pathArr[1];
                    if (isset(Mdtemplate::$responseData[$groupPath])) {
                        if (isset(Mdtemplate::$responseData[$groupPath][$fieldPath])) {
                            $html = str_replace($pathReplace[0][$k], Mdtemplate::$responseData[$groupPath][$fieldPath], $html);
                        } elseif (isset(Mdtemplate::$responseData[$groupPath][0][$fieldPath])) {
                            $html = str_replace($pathReplace[0][$k], Mdtemplate::$responseData[$groupPath][0][$fieldPath], $html);
                        }
                    }
                }
            }
        }
        
        return $html;
    }
    
    public static function absoluteAmount($html) {
        if (strpos($html, 'abs(') !== false) {
            
            preg_match_all('/abs\((.*?)\)/i', $html, $htmlAbsoluteAmount);

            if (count($htmlAbsoluteAmount[0]) > 0) {
                
                foreach ($htmlAbsoluteAmount[1] as $ek => $ev) {
                    
                    $amount = trim(str_replace(',', '', strip_tags($ev)));
                    
                    if ($amount != '' && $amount < 0) {
                        $html = str_replace($htmlAbsoluteAmount[0][$ek], Number::formatMoney(abs($amount), true), $html);
                    } else {
                        $html = str_replace($htmlAbsoluteAmount[0][$ek], Number::formatMoney($amount, true), $html);
                    }
                }
            }
        }
        
        return $html;
    }
    
    public static function setScale($html) {
        if (strpos($html, 'setScale(') !== false) {
            
            preg_match_all('/setScale\((.*?)\)/i', $html, $htmlScaleAmount);

            if (count($htmlScaleAmount[0]) > 0) {
                
                foreach ($htmlScaleAmount[1] as $ek => $ev) {
                    
                    $evArr  = explode(',', trim(strip_tags($ev)));
                    $endVal = end($evArr);

                    $amount = trim(issetParamZero($evArr[0]));
                    $scale  = trim(issetParamZero($endVal));
                    
                    $html = str_replace($htmlScaleAmount[0][$ek], Number::fractionRange($amount, $scale), $html);
                }
            }
        }
        
        return $html;
    }
    
    public static function countDtlGroup($templateHTML, $dataElement) {
        if (strpos($templateHTML, 'count(') !== false) {
            preg_match_all('/count\((.*?)\)/i', $templateHTML, $htmlCount);

            if (count($htmlCount[0]) > 0) {
                foreach ($htmlCount[1] as $ek => $ev) {
                    
                    $ev = strip_tags(strtolower($ev));
                    
                    if (isset($dataElement[$ev])) {
                        $count = count($dataElement[$ev]);
                    } else {
                        $count = 0;
                    }
                    
                    $templateHTML = str_replace($htmlCount[0][$ek], $count, $templateHTML);
                }
            }
        }
        
        return $templateHTML;
    }
    
    public function aggregateDtlGroup($templateHTML, $dataElement, $dataModelId) {
        
        if (strpos($templateHTML, 'sum(') !== false) {
            preg_match_all('/sum\(#(.*?)#\)/i', $templateHTML, $htmlSum);

            if (count($htmlSum[0]) > 0) {
                foreach ($htmlSum[1] as $ek => $ev) {
                    
                    $ev = strip_tags(strtolower($ev));
                    $evArr = explode('.', $ev);
                    
                    if (count($evArr) == 2) {
                        $groupPath = $evArr[0];
                        
                        if (isset($dataElement[$groupPath])) {
                            
                            $dtls = $dataElement[$groupPath];
                            $sumField = $evArr[1];
                            
                            $sum = array_reduce($dtls,
                                function($totalAmount, $item) use($sumField) {
                                    $totalAmount += $item[$sumField]; 
                                    return $totalAmount;
                                },
                                0
                            );
                                
                            $type = $this->model->getMetaTypeCodeByDataViewId($sumField, $dataModelId);
                            $sum = Mdtemplate::valueFormatting($sum, $ev);
                    
                        } else {
                            $sum = 0;
                        }

                        $templateHTML = str_replace($htmlSum[0][$ek], $sum, $templateHTML);
                    }
                }
            }
        }
        
        if (strpos($templateHTML, 'avg(') !== false) {
            preg_match_all('/avg\(#(.*?)#\)/i', $templateHTML, $htmlAvg);

            if (count($htmlAvg[0]) > 0) {
                foreach ($htmlAvg[1] as $ek => $ev) {
                    
                    $ev = strip_tags(strtolower($ev));
                    $evArr = explode('.', $ev);
                    
                    if (count($evArr) == 2) {
                        $groupPath = $evArr[0];
                        
                        if (isset($dataElement[$groupPath])) {
                            
                            $dtls = $dataElement[$groupPath];
                            $count = count($dtls);
                            $sumField = $evArr[1];
                            
                            $sum = array_reduce($dtls,
                                function($totalAmount, $item) use($sumField) {
                                    $totalAmount += $item[$sumField]; 
                                    return $totalAmount;
                                },
                                0
                            );
                                
                            $type = $this->model->getMetaTypeCodeByDataViewId($sumField, $dataModelId);
                            $avg = Mdtemplate::valueFormatting($sum / $count, $ev);
                    
                        } else {
                            $avg = 0;
                        }

                        $templateHTML = str_replace($htmlAvg[0][$ek], $avg, $templateHTML);
                    }
                }
            }
        }
        
        if (strpos($templateHTML, 'min(') !== false) {
            preg_match_all('/min\(#(.*?)#\)/i', $templateHTML, $htmlMin);

            if (count($htmlMin[0]) > 0) {
                foreach ($htmlMin[1] as $ek => $ev) {
                    
                    $ev = strip_tags(strtolower($ev));
                    $evArr = explode('.', $ev);
                    
                    if (count($evArr) == 2) {
                        $groupPath = $evArr[0];
                        
                        if (isset($dataElement[$groupPath])) {
                            
                            $dtls = $dataElement[$groupPath];
                            $count = count($dtls);
                            $sumField = $evArr[1];
                            
                            $min = min(array_column($dtls, $sumField));
                                
                            $type = $this->model->getMetaTypeCodeByDataViewId($sumField, $dataModelId);
                            $min = Mdtemplate::valueFormatting($min / $count, $ev);
                    
                        } else {
                            $min = 0;
                        }

                        $templateHTML = str_replace($htmlMin[0][$ek], $min, $templateHTML);
                    }
                }
            }
        }
        
        if (strpos($templateHTML, 'max(') !== false) {
            preg_match_all('/max\(#(.*?)#\)/i', $templateHTML, $htmlMax);

            if (count($htmlMax[0]) > 0) {
                foreach ($htmlMax[1] as $ek => $ev) {
                    
                    $ev = strip_tags(strtolower($ev));
                    $evArr = explode('.', $ev);
                    
                    if (count($evArr) == 2) {
                        $groupPath = $evArr[0];
                        
                        if (isset($dataElement[$groupPath])) {
                            
                            $dtls = $dataElement[$groupPath];
                            $count = count($dtls);
                            $sumField = $evArr[1];
                            
                            $max = max(array_column($dtls, $sumField));
                                
                            $type = $this->model->getMetaTypeCodeByDataViewId($sumField, $dataModelId);
                            $max = Mdtemplate::valueFormatting($max / $count, $ev);
                    
                        } else {
                            $max = 0;
                        }

                        $templateHTML = str_replace($htmlMax[0][$ek], $max, $templateHTML);
                    }
                }
            }
        }
        
        return $templateHTML;
    }

    public function parseTemplateDtl($templateHTML, $fieldpath, $dataValue, $dataModelId) {
        $this->load->model('mdmetadata', 'middleware/models/');
        $childs = $this->model->getReplacedMetaDataByGroupModel($dataModelId, $fieldpath);
        
        $this->load->model('mdtemplate', 'middleware/models/');
        
        $tableHtml = "<table class='table table-bordered'>";
        $tableHtml .= "<thead><tr>";
        
        foreach ($childs as $thead) {
            $attrs = '';
            if ($thead['COLUMN_WIDTH'] != null) {
                $attrs .= ' data-width="' . $thead['COLUMN_WIDTH'] . '"';
            }
            if ($thead['TEXT_WEIGHT'] != null) {
                $attrs .= ' data-textweight="' . $thead['TEXT_WEIGHT'] . '"';
            }
            if ($thead['TEXT_COLOR'] != null) {
                $attrs .= ' data-textcolor="' . $thead['TEXT_COLOR'] . '"';
            }
            $tableHtml .= "<td " . $attrs . ">" . Lang::line($thead['LABEL_NAME']) . "</td>";
        }
        $tableHtml .= "</tr></thead>";
        
        $tableHtml .= "<tbody>";
        
        foreach ($dataValue as $row) {
            $tableHtml .= '<tr>';
            
            foreach ($childs as $body) {
                
                $attrs = '';
                if ($body['COLUMN_WIDTH'] != null) {
                    $attrs .= ' data-width="' . $body['COLUMN_WIDTH'] . '"';
                }
                if ($body['TEXT_WEIGHT'] != null) {
                    $attrs .= ' data-textweight="' . $body['TEXT_WEIGHT'] . '"';
                }
                if ($body['TEXT_COLOR'] != null) {
                    $attrs .= ' data-textcolor="' . $body['TEXT_COLOR'] . '"';
                }
                
                $value = '';
                
                if (isset($row[strtolower($body['FIELD_PATH'])])) {
                    $type = $this->model->getMetaTypeCodeByDataViewId($body['FIELD_PATH'], $dataModelId);
                    $value = Format::valueFormatting($row[strtolower($body['FIELD_PATH'])], $type);
                }
                
                $tableHtml .= "<td " . $attrs . ">" . $value . "</td>";
            }
            
            $tableHtml .= '</tr>';
        }
        
        $tableHtml .= '</tbody>';
        $tableHtml .= '</table>';
        
        $templateHTML = str_replace('#' . $fieldpath . '#', $tableHtml, $templateHTML);
        
        return $templateHTML;
    }
    
    public static function generateQrCode($data) {
        require_once BASEPATH.'libs/QRCode/custom/QRGenerator.php';
        $qrcode = new QRGenerator($data, 150, 'UTF-8', 'L', 0); 
        return '<img src="'.$qrcode->generate().'">';
    }
    
    public function qrcode($templateId, $dataElement, $html) {
        if (strpos($html, 'qrcode(') !== false) {
            preg_match_all('/qrcode\((.*?)\)/i', $html, $htmlQrcodes);
            
            if (count($htmlQrcodes[0]) > 0) {
                
                $this->load->model('mdtemplate', 'middleware/models/');
                $data = $this->model->generateQrFields($templateId, $dataElement);
                
                if ($data) {
                    $qrCode = self::generateQrCode($data);
                
                    foreach ($htmlQrcodes[1] as $ek => $ev) {
                        $html = str_replace($htmlQrcodes[0][$ek], $qrCode, $html);
                    }
                }
            }
        }
        
        return $html;
    }
    
    public static function printPivotDetail($html) {
        if (strpos($html, 'printPivotDetail(') !== false) {
            preg_match_all('/printPivotDetail\((.*?)\)/i', $html, $htmlPivots);
            
            if (count($htmlPivots[0]) > 0) {
                
                foreach ($htmlPivots[1] as $ek => $ev) {
                    
                    if (strpos($ev, ',') !== false) {
                        $evArr = explode(',', $ev);
                        
                        if (count($evArr) > 1) {
                            
                            $firstPath  = trim(strip_tags($evArr[0]));
                            $pivotPath  = trim(strip_tags($evArr[1]));
                            $configAttr = isset($evArr[2]) ? trim(strip_tags($evArr[2])) : '';
                            
                            $returnPivotDetail = (new Mdtemplate())->returnPivotDetail($firstPath, $pivotPath, $configAttr);
                        
                            $html = str_replace($htmlPivots[0][$ek], $returnPivotDetail, $html);
                        }
                    } 
                }
            }
        }
        
        return $html;
    }
    
    public function returnPivotDetail($firstPath, $pivotPath, $configAttr = '') {
        $this->load->model('mdtemplate', 'middleware/models/');
        
        //printPivotDetail(EXT_PRICE_COMPARISON_DTL.COMPARISON_DTL, COMPARE_CUSTOMER_COLUMN[supplierid|suppliername])
        
        $responseData = Mdtemplate::$responseData;
        $pivotPathArr = explode('[', $pivotPath);
        $pivotGroupPath = strtolower($pivotPathArr[0]);
        
        $table = '';
        
        if (!isset($responseData[$pivotGroupPath])) {
            $responseData[$pivotGroupPath] = array();
        }
            
        $firstPath = strtolower($firstPath);
        $firstPathArr = explode('.', $firstPath);
        $firstCol = strtolower($firstPathArr[0]);

        $showColumns = $this->model->getShowColumnsByTemplateModel($firstCol, Mdtemplate::$templateDataModelId, true);

        if ($showColumns && $detailData = issetParamArray($responseData[$firstCol])) {

            $tbl = $tblFoot = $columnAggregate = $pivotColumnAggregate = $pivotColumnFontSize = $pivotColumnAggregateVals = $pivotTblRow = array();
            $width = 20;
            $pivotData = $responseData[$pivotGroupPath];
            $secondCol = strtolower($firstPathArr[1]);
            $colArr = explode('|', str_replace(']', '', strtolower($pivotPathArr[1])));
            $rowId = $colArr[0];
            $rowName = $colArr[1];

            $subShowColumns = $this->model->getShowColumnsByTemplateModel($firstPath, Mdtemplate::$templateDataModelId);
            $colSpanCount = count($subShowColumns);
            $subMergeColumns = '';

            $isPivotDataCol = $isPivot = true;
            $isPivotLeftRotate = false;
            $mainRowSpan = 2;

            if ($colSpanCount == 1) {
                $subShowColumnsFirstRow = $subShowColumns[0];
                if ($subShowColumnsFirstRow['LABEL_NAME'] == '') {
                    $isPivotDataCol = false;
                    $subShowColumnWidth = (int) $subShowColumnsFirstRow['COLUMN_WIDTH'];
                    $mainRowSpan = 1;
                }
            }

            if (count($pivotData) == 0) {
                $isPivot = false;
                $mainRowSpan = 1;
            }

            $headerStyle = $pivotHeaderStyle = $pivotGroupName = $rownumStyle = '';

            if ($configAttr != '') {

                //headerFontSize=9px&pivotHeaderFontSize=9px&headerColor=#dsfgfsd&pivotHeaderColor=#dsfgfsd&headerTextColor=#dsfgfsd&pivotHeaderTextColor=#dsfgfsd&headerFontFamily=Times&pivotHeaderFontFamily=Times&pivotGroupName=sdgvdsdds&leftRotate=paramName1|paramName2&rightRotate=paramName1|paramName2&bodyRowHeight=50px&pivotLeftRotate=1
                parse_str(html_entity_decode($configAttr, ENT_QUOTES, 'UTF-8'), $configAttrArr);

                if ($headerFontSize = issetParam($configAttrArr['headerFontSize'])) {
                    $headerStyle .= "font-size: $headerFontSize;";
                }

                if ($headerColor = issetParam($configAttrArr['headerColor'])) {
                    $headerStyle .= "background-color: $headerColor;";
                }
                
                if ($headerTextColor = issetParam($configAttrArr['headerTextColor'])) {
                    $headerStyle .= "color: $headerTextColor;";
                }

                if ($headerFontFamily = issetParam($configAttrArr['headerFontFamily'])) {
                    $headerStyle .= "font-family: $headerFontFamily;";
                }

                if ($pivotHeaderFontSize = issetParam($configAttrArr['pivotHeaderFontSize'])) {
                    $pivotHeaderStyle .= "font-size: $pivotHeaderFontSize;";
                }

                if ($pivotHeaderColor = issetParam($configAttrArr['pivotHeaderColor'])) {
                    $pivotHeaderStyle .= "background-color: $pivotHeaderColor;";
                }
                
                if ($pivotHeaderTextColor = issetParam($configAttrArr['pivotHeaderTextColor'])) {
                    $pivotHeaderStyle .= "color: $pivotHeaderTextColor;";
                }
                
                if ($pivotHeaderFontFamily = issetParam($configAttrArr['pivotHeaderFontFamily'])) {
                    $pivotHeaderStyle .= "font-family: $pivotHeaderFontFamily;";
                }

                if ($rownumFontSize = issetParam($configAttrArr['rownumFontSize'])) {
                    $rownumStyle .= "font-size: $rownumFontSize;";
                }

                if ($bodyRowHeight = issetParam($configAttrArr['bodyRowHeight'])) {
                    $rownumStyle .= "height: $bodyRowHeight;";
                }
                
                if ($pivotLeftRotate = issetParam($configAttrArr['pivotLeftRotate'])) {
                    $isPivotLeftRotate = true;
                }
                
                if ($leftRotate = issetParam($configAttrArr['leftRotate'])) {
                    $leftRotates = explode('|', $leftRotate);
                    $columnsLeftRotate = array();
                    foreach ($leftRotates as $leftRotateCol) {
                        $columnsLeftRotate[strtolower($leftRotateCol)] = 1;
                    }
                }

                $pivotGroupName = issetParam($configAttrArr['pivotGroupName']);
            }

            if ($isPivotDataCol == false && $pivotGroupName != '') {
                $mainRowSpan = 2;
            }

            $tbl[] = '<table border="1" style="width: {width}; table-layout: fixed;">';
                $tbl[] = '<thead>';
                    $tbl[] = '<tr>';
                        $tbl[] = '<th style="text-align:center;width:30px;'.$headerStyle.'" rowspan="'.$mainRowSpan.'">№</th>';

                        $tblFoot[] = '<td data-col="rownum"></td>';

                        foreach ($showColumns as $showCol) {

                            $paramName = $showCol['PARAM_NAME'];

                            if ($secondCol == $paramName) {

                                foreach ($pivotData as $pivotRow) {

                                    $pivotHeadName = $pivotRow[$rowName];
                                    
                                    if ($isPivotLeftRotate) {
                                        $pivotHeadNameLabel = '<div class="left-rotate-span" style="display:inline;text-align:left;height:100px;-webkit-writing-mode:vertical-rl; -ms-writing-mode:tb-rl; writing-mode:vertical-rl;transform: rotate(180deg);">'.$pivotHeadName.'</div>';
                                    } else {
                                        $pivotHeadNameLabel = $pivotHeadName;
                                    }

                                    if ($isPivotDataCol == false && $pivotGroupName != '') {
                                        $pivotTblRow[] = '<th style="text-align:center;vertical-align:bottom;'.$pivotHeaderStyle.((!$isPivotDataCol && $subShowColumnWidth) ? 'width:'.$subShowColumnWidth.'px' : '').'">'.$pivotHeadNameLabel.'</th>';
                                    } else {
                                        $tbl[] = '<th style="text-align:center;vertical-align:bottom;'.$pivotHeaderStyle.((!$isPivotDataCol && $subShowColumnWidth) ? 'width:'.$subShowColumnWidth.'px' : '').'" colspan="'.$colSpanCount.'">'.$pivotHeadNameLabel.'</th>';
                                    }

                                    foreach ($subShowColumns as $subShowCol) {

                                        $subColAggregate = $subShowCol['COLUMN_AGGREGATE'];
                                        $subLabelName    = $subShowCol['LABEL_NAME'];
                                        $subParamName    = $subShowCol['PARAM_NAME'];
                                        $subFontSize     = $subShowCol['FONT_SIZE'];
                                        $subColName      = $pivotHeadName.'_'.$subParamName;

                                        if ($isPivotDataCol) {
                                            $subMergeColumns .= '<th style="text-align:center;'.$pivotHeaderStyle.'width: '.$subShowCol['COLUMN_WIDTH'].'">'.$this->lang->line($subLabelName).'</th>';
                                        }

                                        $width += (int) $subShowCol['COLUMN_WIDTH'];

                                        $tblFoot[] = '<td data-col="'.$subColName.'"></td>';

                                        if ($subColAggregate) {
                                            $isFoot = true;
                                            $pivotColumnAggregate[$subColName] = $subColAggregate;
                                            $pivotColumnFontSize[$subColName] = $subFontSize;
                                        }
                                    }
                                }

                                if ($isPivot && $isPivotDataCol == false && $pivotGroupName != '') {
                                    $tbl[] = '<th colspan="'.count($pivotData).'" style="text-align:center;'.$pivotHeaderStyle.'">'.$pivotGroupName.'</th>';
                                }

                            } elseif ($showCol['DATA_TYPE'] != 'group') {

                                $colAggregate = $showCol['COLUMN_AGGREGATE'];
                                $fontSize     = $showCol['FONT_SIZE'];
                                $width       += (int) $showCol['COLUMN_WIDTH'];
                                
                                if (isset($columnsLeftRotate) && isset($columnsLeftRotate[$paramName])) {
                                    $headNameLabel = '<div class="left-rotate-span" style="display:inline;text-align:left;height:100px;-webkit-writing-mode:vertical-rl; -ms-writing-mode:tb-rl; writing-mode:vertical-rl;transform: rotate(180deg);">'.$this->lang->line($showCol['LABEL_NAME']).'</div>';
                                } else {
                                    $headNameLabel = $showCol['LABEL_NAME'];
                                }

                                $tbl[] = '<th style="text-align:center;vertical-align:middle;'.$headerStyle.'width: '.$showCol['COLUMN_WIDTH'].'" rowspan="'.$mainRowSpan.'">'.$headNameLabel.'</th>';
                                $tblFoot[] = '<td data-col="'.$paramName.'"></td>';

                                if ($colAggregate) {
                                    $isFoot = true;
                                    $columnAggregate[] = array('paramName' => $paramName, 'aggregate' => $colAggregate, 'fontSize' => $fontSize);
                                }
                            }
                        }

                    $tbl[] = '</tr>';

                    if ($subMergeColumns) {
                        $tbl[] = '<tr>'.$subMergeColumns.'</tr>';
                    } elseif ($pivotTblRow) {
                        $tbl[] = '<tr>'.implode('', $pivotTblRow).'</tr>';
                    }

                $tbl[] = '</thead>';
                $tbl[] = '<tbody>';

            foreach ($detailData as $k => $detail) {

                $tbl[] = '<tr>';
                    $tbl[] = '<td style="text-align: center;'.$rownumStyle.'">';

                        if (isset($detail['rownum'])) {
                            $tbl[] = $detail['rownum'];
                        } else {
                            $tbl[] = ++$k;
                        }

                    $tbl[] = '</td>';

                    foreach ($showColumns as $showCol) {

                        $dataType  = $showCol['DATA_TYPE'];
                        $paramName = $showCol['PARAM_NAME'];

                        if ($secondCol == $paramName) {

                            foreach ($pivotData as $pivotRow) {

                                if (isset($detail[$secondCol])) {

                                    $pivotHeadName = $pivotRow[$rowName];
                                    $subDataRow = array();

                                    $subDtl = $detail[$secondCol];

                                    foreach ($subDtl as $subRow) {

                                        if ($pivotRow[$rowId] == $subRow[$rowId]) {
                                            $subDataRow = $subRow;
                                            break;
                                        }
                                    }

                                    foreach ($subShowColumns as $subShowCol) {

                                        $subDataType  = $subShowCol['DATA_TYPE'];
                                        $subBodyAlign = $subShowCol['BODY_ALIGN'];
                                        $subParamName = $subShowCol['PARAM_NAME'];
                                        $subFontSize  = $subShowCol['FONT_SIZE'];
                                        $subColName   = $pivotHeadName.'_'.$subParamName;

                                        $val = issetParam($subDataRow[$subParamName]);
                                        $subStyle = '';

                                        if ($val != '' && isset($pivotColumnAggregate[$subColName])) {

                                            $pivotColumnAggregateVals[$subColName][] = $val;
                                        }

                                        if ($subBodyAlign) {

                                            $subStyle = 'text-align: '.$subBodyAlign.';';
                                            
                                        } elseif (($subDataType == 'bigdecimal' || $subDataType == 'decimal' || $subDataType == 'number' || $subDataType == 'integer') && $val != '') {

                                            $subStyle = 'text-align: right;';
                                            $val = Number::amount($val);

                                        } elseif ($subDataType == 'boolean' && $val != '') {

                                            $subStyle = 'text-align: center; font-weight: bold;';
                                            $val = ($val == '1' ? '✓' : '');
                                        } 

                                        if ($subFontSize) {
                                            $subStyle .= 'font-size: '.$subFontSize;
                                        }

                                        $tbl[] = '<td style="'.$subStyle.'">'.$val.'</td>';
                                    }

                                } else {
                                    foreach ($subShowColumns as $subShowCol) {
                                        $tbl[] = '<td></td>';
                                    }
                                }
                            }

                        } elseif ($dataType != 'group') {

                            $bodyAlign = $showCol['BODY_ALIGN'];
                            $fontSize  = $showCol['FONT_SIZE'];
                            $isMerge   = $showCol['IS_MERGE'];

                            $val = issetParam($detail[$paramName]);
                            $style = '';

                            if (($dataType == 'bigdecimal' || $dataType == 'decimal' || $dataType == 'number' || $dataType == 'integer') && $val != '') {

                                $style = 'text-align: right;';
                                $val = Number::amount($val);

                            } elseif ($dataType == 'boolean' && $val != '') {

                                $style = 'text-align: center; font-weight: bold;';
                                $val = ($val == '1' ? '✓' : '');

                            } elseif ($bodyAlign) {

                                $style = 'text-align: '.$bodyAlign.';';
                            }

                            if ($fontSize) {
                                $style .= 'font-size: '.$fontSize;
                            }

                            $tbl[] = '<td style="'.$style.'"'.($isMerge ? ' data-merge-cell="true"' : '').'>'.$val.'</td>';
                        }
                    }

                $tbl[] = '</tr>';
            }

                if (isset($isFoot)) {

                    $tblFootHtml = implode('', $tblFoot);

                    foreach ($columnAggregate as $columnAggrRow) {

                        $aggregateVal = '';

                        if ($columnAggrRow['aggregate'] == 'sum') {
                            $aggregateVal = Number::amount(helperSumFieldBp($detailData, $columnAggrRow['paramName']));
                        } elseif ($columnAggrRow['aggregate'] == 'min') {
                            $aggregateVal = Number::amount(helperMinFieldBp($detailData, $columnAggrRow['paramName']));
                        } elseif ($columnAggrRow['aggregate'] == 'max') {
                            $aggregateVal = Number::amount(helperMaxFieldBp($detailData, $columnAggrRow['paramName']));
                        }

                        $tblFootHtml = str_replace('<td data-col="'.$columnAggrRow['paramName'].'"></td>', '<td data-col="'.$columnAggrRow['paramName'].'" style="text-align:right;font-weight:bold;'.($columnAggrRow['fontSize'] ? 'font-size:'.$columnAggrRow['fontSize'] : '').'">'.$aggregateVal.'</td>', $tblFootHtml);
                    }

                    foreach ($pivotColumnAggregateVals as $pivotColumnAggregateCol => $pivotColumnAggregateVal) {

                        $aggregateVal = '';

                        if ($pivotColumnAggregate[$pivotColumnAggregateCol] == 'sum') {
                            $aggregateVal = Number::amount(array_sum($pivotColumnAggregateVal));
                        } elseif ($pivotColumnAggregate[$pivotColumnAggregateCol] == 'min') {
                            $aggregateVal = Number::amount(min($pivotColumnAggregateVal));
                        } elseif ($pivotColumnAggregate[$pivotColumnAggregateCol] == 'max') {
                            $aggregateVal = Number::amount(max($pivotColumnAggregateVal));
                        } elseif ($pivotColumnAggregate[$pivotColumnAggregateCol] == 'avg') {
                            $aggregateVal = Number::amount(array_sum($pivotColumnAggregateVal) / count($pivotColumnAggregateVal));
                        }

                        $fontSize = $pivotColumnFontSize[$pivotColumnAggregateCol];

                        $tblFootHtml = str_replace('<td data-col="'.$pivotColumnAggregateCol.'"></td>', '<td data-col="'.$pivotColumnAggregateCol.'" style="text-align:right;font-weight:bold;'.($fontSize ? 'font-size:'.$fontSize : '').'">'.$aggregateVal.'</td>', $tblFootHtml);
                    }

                    $tbl[] = '<tr>';
                        $tbl[] = $tblFootHtml;
                    $tbl[] = '</tr>';
                }

                $tbl[] = '</tbody>';
            $tbl[] = '</table>';

            $table = implode('', $tbl);

            if ($width > 1200 || $isPivot) {
                $replaceWidth = $width.'px';
            } else {
                $replaceWidth = '100%';
            }

            $table = str_replace('{width}', $replaceWidth, $table);
        }
        
        return $table;
    }
    
    public static function getContentBetween($html, $startDelimiter, $endDelimiter) {
        $html = ' ' . $html;
        $ini = strpos($html, $startDelimiter);
        if ($ini == 0)
            return '';
        $ini += strlen($startDelimiter);
        $len = strpos($html, $endDelimiter, $ini) - $ini;
        return substr($html, $ini, $len);
    }

    public static function getContents($str, $startDelimiter, $endDelimiter) {
        $contents = array();
        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength = strlen($endDelimiter);
        $startFrom = $contentStart = $contentEnd = 0;
        while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
            $contentStart += $startDelimiterLength;
            $contentEnd = strpos($str, $endDelimiter, $contentStart);
            if (false === $contentEnd) {
                break;
            }
            $contents[] = substr($str, $contentStart, ($contentEnd - $contentStart));
            $startFrom = $contentEnd + $endDelimiterLength;
        }
        return $contents;
    }

    public function printOption() {
        
        if (!isset($this->model)) {
            $this->model = new Model();
            $this->load->model('mdtemplate', 'middleware/models/');
        }  
        
        if (!isset($this->view)) {
            $this->view = new View();
        }                   
        
        $base64Params = Input::get('base64Params');
        
        if ($base64Params) {
            $getJsonParam = base64_decode($base64Params);
        } else {
            $getJsonParam = file_get_contents('php://input');
        }
        
        if ($getJsonParam && !isset($_POST['print_options'])) {
            
            self::$isRunMobile = 1;
            self::$mobileJson = $getJsonParam;
            
            $getJsonParam = json_decode($getJsonParam, true);
            $getJsonParam = $getJsonParam['parameter'];
            $dataRow = $getJsonParam['datarow'];
            $print_options = $getJsonParam['print_options'];       
            $isPageSource = issetParam($getJsonParam['isimage']); 
            $isHtml = issetParam($getJsonParam['ishtml']); 
            $this->view->metaDataId = $getJsonParam['metadataid'];

            $this->view->numberOfCopies = Input::param($print_options['numberofcopies']);
            $this->view->isPrintNewPage = Input::param($print_options['isprintnewpage']);
            $this->view->isSettingsDialog = Input::param($print_options['issettingsdialog']);
            $this->view->isShowPreview = Input::param($print_options['isshowpreview']);
            $this->view->isPrintPageBottom = Input::param($print_options['isprintpagebottom']);
            $this->view->isPrintSaveTemplate = Input::param($print_options['isprintsavetemplate']);
            $this->view->isPrintPageRight = Input::param($print_options['isprintpageright']);
            $this->view->pageOrientation = Input::param($print_options['pageorientation']);
            $this->view->paperInput = Input::param($print_options['paperinput']);
            $this->view->pageSize = Input::param($print_options['pagesize']);
            $this->view->pt = Input::param($print_options['printtype']);
            $templateVarName = 'templatemetaid';
            $templateIdsVar = 'templateids';
                        
        } else {
            Auth::handleLogin();

            $dataRow = $_POST['dataRow'];
            $print_options = $_POST['print_options'];            
            $this->view->metaDataId = Input::numeric('metaDataId');

            $this->view->numberOfCopies = Input::param($print_options['numberOfCopies']);
            $this->view->isPrintNewPage = Input::param($print_options['isPrintNewPage']);
            $this->view->isSettingsDialog = Input::param($print_options['isSettingsDialog']);
            $this->view->isShowPreview = Input::param($print_options['isShowPreview']);
            $this->view->isPrintPageBottom = Input::param($print_options['isPrintPageBottom']);
            $this->view->isPrintSaveTemplate = issetVar($print_options['isPrintSaveTemplate']);
            $this->view->isPrintPageRight = Input::param($print_options['isPrintPageRight']);
            $this->view->pageOrientation = Input::param($print_options['pageOrientation']);
            $this->view->paperInput = Input::param($print_options['paperInput']);
            $this->view->pageSize = Input::param($print_options['pageSize']);
            $this->view->pt = Input::param($print_options['printType']);            
            $exportMode = issetParam($print_options['exportMode']);
            
            $templateVarName = 'templateMetaId';
            $templateIdsVar = 'templateIds';
            
            if (issetParam($print_options['isKpiIndicator']) == 1) {
                self::$isKpiIndicator = true;
            }
        }        
        
        $this->view->pageHeaderTitle = 'Tемплейт';
        $this->view->isArchiveBtn = false;        
        $this->view->isArchiveName = null;
        $this->view->isEmailBtn = false;
        $this->view->isAutoArchiveBtn = false;
        $this->view->isPrintBtn = true;
        $this->view->isExcelBtn = true;
        $this->view->isPdfBtn = true;
        $this->view->isWordBtn = true;
        $this->view->wfmArchiveBtnArr = array();
        $this->view->defaultDirectoryId = null;
        $this->view->recordId = null;
        $this->view->downloadFileName = null;
        $this->view->isSmartShrinking = 0;
        $this->view->isBlockChainVerify = 0;
        
        $isTemplateMetaId = false;
        
        if (isset($print_options[$templateVarName])) {
            $isTemplateMetaId = true;
            $this->view->templates[] = Input::param($print_options[$templateVarName]);
        } else {
            if (isset($print_options[$templateIdsVar]) && $print_options[$templateIdsVar]) {
                $templateIds = ltrim(rtrim($print_options[$templateIdsVar], ','), ',');
                $print_options[$templateIdsVar] = $templateIds;
                $this->view->templates = explode(',', $templateIds);
            } else {
                $this->view->templates = isset($print_options['templates']) ? $print_options['templates'] : null;
            }
        }             
        
        $firstRow = array_key_exists(0, $dataRow) ? $dataRow[0] : $dataRow;
        
        $array = $headerArray = $footerArray = $mergeFiles = array();
        $i = 0;

        if (issetParam($print_options['isMergeDataRow'])) {
            Mdtemplate::$mergeResponseData = $firstRow;                        
        }        

        if (Config::getFromCache('CONFIG_REPORT_TEMPLATE_SERVER_ADDRESS')) { 
            $rtRow = $this->model->getDataModelByTemplate($this->view->templates[0], $isTemplateMetaId);

            $this->load->model('mdstatement', 'middleware/models/');
            $layoutId = $this->model->getReportLayoutIdModel($rtRow['META_DATA_ID']);                        
        }
        
        $this->load->model('mdtemplate', 'middleware/models/');
        
        if (Config::getFromCache('CONFIG_REPORT_TEMPLATE_SERVER_ADDRESS') && isset($layoutId)) {

            $this->view->defaultUrl = Config::getFromCache('CONFIG_REPORT_TEMPLATE_SERVER_ADDRESS') . 'Viewer.aspx';
            $this->view->layoutId   = $layoutId;                        
            
            $this->view->reportUrl  = $this->view->defaultUrl;
            $criteria = array(
                'criteria' => array(
                    'id' => array(
                        array(
                            'operator' => '=',
                            'operand' => $firstRow['id']
                        )
                    )
                )                
            );
            $getReport = $this->model->getReportIdModel(Input::numeric('metaDataId'), $criteria);

            $this->load->model('mdstatement', 'middleware/models/');
            $this->model->setReportParamsModel($getReport['reportId'], $rtRow['META_DATA_ID'], array());
            
            if (isset($getReport['reportId'])) {
                
                $this->view->reportUrl .= '?reportid=' . $getReport['reportId'] . '&layoutId=' . $this->view->layoutId . '&sourcetype=dv';
                
                if (isset($pageProperties['expandReportId'])) { 
                    $this->view->reportUrl .= '&subReportIds=' . $pageProperties['expandReportId'];
                }
            }

            $response = array(
                'status' => 'success',
                'isExternalTool' => '1',
                'Html' => $this->view->renderPrint('iframeReportTemplate', 'middleware/views/preview/'),
                'Title' => $this->lang->line('print_btn'),
                'print_btn' => $this->lang->line('print_btn'),
                'close_btn' => $this->lang->line('close_btn')
            );
            echo json_encode($response); exit;
            
        } else {
        
            loadBarCodeImageData();
            loadPhpQuery();
            
            $countDataRow = count($dataRow);
            
            foreach ($this->view->templates as $templateId) {
                
                $rtRow = $this->model->getDataModelByTemplate($templateId, $isTemplateMetaId);
                
                $paging = false;
                
                if (!empty($rtRow['PAGING_CONFIG'])) {
                    $pagingConfig = $rtRow['PAGING_CONFIG'];
                    $paging = true;
                }
                
                $dataModelId = $rtRow['DATA_MODEL_ID'];
                
                $this->view->reportMetaDataId = $rtRow['META_DATA_ID'];
                $this->view->isTableLayoutFixed = false;
                
                if ($rtRow['IS_TABLE_LAYOUT_FIXED'] == '1') {
                    $this->view->isTableLayoutFixed = true; 
                }
                
                if ($rtRow['IS_PDF_SMART_SHRINKING'] == '1') {
                    $this->view->isSmartShrinking = 1; 
                }
                
                if ($rtRow['IS_ARCHIVE'] == '1') {
                    $this->view->isArchiveBtn = true; 
                    $this->view->isArchiveName = $rtRow['META_DATA_NAME'];
                }
                
                if ($rtRow['IS_EMAIL'] == '1') {
                    $this->view->isEmailBtn = true; 
                }
                
                if ($rtRow['IS_AUTO_ARCHIVE'] == '1') {
                    $this->view->isAutoArchiveBtn = true; 
                    $this->view->isArchiveName = isset($firstRow['booknumber']) ? $firstRow['booknumber'] : $rtRow['META_DATA_NAME'];
                    $this->view->defaultDirectoryId = $rtRow['DIRECTORY_ID'];
                }
                
                if ($rtRow['IS_IGNORE_PRINT'] == '1') {
                    $this->view->isPrintBtn = false;
                }
                
                if ($rtRow['IS_IGNORE_EXCEL'] == '1') {
                    $this->view->isExcelBtn = false;
                }
                
                if ($rtRow['IS_IGNORE_PDF'] == '1') {
                    $this->view->isPdfBtn = false;
                }
                
                if ($rtRow['IS_IGNORE_WORD'] == '1') {
                    $this->view->isWordBtn = false;
                }
                
                if ($rtRow['IS_BLOCKCHAIN_VERIFY'] == '1') {
                    $this->view->isBlockChainVerify = 1;
                }
                
                if (!$this->view->isArchiveBtn && !$this->view->isAutoArchiveBtn) {
                    $this->view->isArchiveName = isset($firstRow['booknumber']) ? $firstRow['booknumber'] : $rtRow['META_DATA_NAME'];
                    $this->view->defaultDirectoryId = $rtRow['DIRECTORY_ID'];
                }
                
                if ($rtRow['PAGE_MARGIN_TOP'] != '' || $rtRow['PAGE_MARGIN_LEFT'] != '' || $rtRow['PAGE_MARGIN_RIGHT'] != '' || $rtRow['PAGE_MARGIN_BOTTOM'] != '') {
                    $this->view->pageMargin = array(
                        'top' => $rtRow['PAGE_MARGIN_TOP'], 
                        'left' => $rtRow['PAGE_MARGIN_LEFT'], 
                        'right' => $rtRow['PAGE_MARGIN_RIGHT'], 
                        'bottom' => $rtRow['PAGE_MARGIN_BOTTOM']
                    ); 
                }                
                
                $templateGetMode = $rtRow['GET_MODE'];
                
                switch ($templateGetMode) {
                    
                    case 'consolidate':
                    case 'getlist':
                        
                        if ($templateGetMode == 'getlist') {
                            Mdtemplate::$getListCommand = Mddatamodel::$getRowDataViewCommand;
                        }
                        
                        $dataElement = $this->model->getRowConsolidateDataDtl($dataModelId, $dataRow, $this->view->metaDataId, $rtRow['META_DATA_ID']);
                        
                        $renderTemplate = self::renderTemplate($dataElement, $templateId, $isTemplateMetaId, $dataModelId);
                        
                        if ($rtRow['UI_EXPRESSION']) {

                            $UIExpressionEval = Mdexpression::reportTemplateUIExpression($rtRow['UI_EXPRESSION']);

                            $domHtml = phpQuery::newDocument($renderTemplate);

                            eval($UIExpressionEval);

                            $renderTemplate = $domHtml;
                        }

                        array_push($array, $renderTemplate);

                    break;
                    case 'parse_json':
                        
                        $dataElement = $this->model->getRowParseJsonDataDtl($dataModelId, $dataRow);
                        $dataElement = issetParam($dataElement['result']);
                        array_push($array, self::renderTemplate($dataElement, $templateId, $isTemplateMetaId, $dataModelId));     

                    break;
                    default:
                        
                        if (isset($print_options['queryStrCriteria'])) {
                            $_POST['queryStrCriteria'] = Input::param($print_options['queryStrCriteria']);
                        }
                        
                        foreach ($dataRow as $row) {

                            $dataElement = $this->model->getRowDataDtl($dataModelId, $row, $this->view->metaDataId, $rtRow['META_DATA_ID']);

                            if ($dataElement) {
                                
                                $isPagingRow = false;
                                
                                if ($paging && strpos($pagingConfig, '|') !== false) {
                                    
                                    $pagingConfigArr = explode('|', $pagingConfig);
                                    
                                    if (isset($pagingConfigArr[1])) {
                                        
                                        $pagingConfigGroup = strtolower($pagingConfigArr[0]);
                                        $pagingConfigSize  = $pagingConfigArr[1];

                                        if ($pagingConfigGroup && $pagingConfigSize && isset($dataElement[$pagingConfigGroup]) && $dataElement[$pagingConfigGroup]) {
                                            $isPagingRow = true;
                                            $dataElementGroup = $dataElement[$pagingConfigGroup];
                                            $dataGroupCount = count($dataElementGroup);
                                        }
                                    }
                                }
                                
                                if ($isPagingRow && $pagingConfigSize < $dataGroupCount) {

                                    unset($dataElement[$pagingConfigGroup]);

                                    $numPages = ceil($dataGroupCount / $pagingConfigSize);
                                    $start = 0;

                                    for ($p = 1; $p <= $numPages; $p++) {

                                        $dataElementLimited = array_slice($dataElementGroup, $start, $pagingConfigSize);

                                        $dataElement[$pagingConfigGroup] = $dataElementLimited;

                                        $renderTemplate = $this->renderTemplate($dataElement, $templateId, $isTemplateMetaId, $dataModelId);
                                        array_push($array, $renderTemplate);

                                        $start = $start + $pagingConfigSize;
                                    }

                                } else {

                                    $renderTemplate = $this->renderTemplate($dataElement, $templateId, $isTemplateMetaId, $dataModelId);

                                    if ($rtRow['UI_EXPRESSION']) {

                                        $UIExpressionEval = Mdexpression::reportTemplateUIExpression($rtRow['UI_EXPRESSION']);

                                        $domHtml = phpQuery::newDocument($renderTemplate);

                                        eval($UIExpressionEval);

                                        $renderTemplate = $domHtml;
                                    }

                                    array_push($array, $renderTemplate);

                                    if (isset($row['isrtmergefiles']) && $row['isrtmergefiles'] == '1' 
                                        && isset($row['mergefiles']) && $row['mergefiles']) {

                                        $mergeFilesExplode = explode(',', $row['mergefiles']);

                                        foreach ($mergeFilesExplode as $mf) {
                                            $mf = trim($mf);

                                            if ($mf && pathinfo($mf, PATHINFO_EXTENSION) == 'pdf' && file_exists($mf)) {
                                                $mergeFiles[$mf] = 1;
                                            }
                                        }
                                    }
                                }

                                if (issetParam($dataElement['downloadfilename'])) {
                                    $this->view->downloadFileName .= $dataElement['downloadfilename'] . ', ';
                                }

                                if (issetParam($rtRow['COUNT_SECOND_PRINT_TEMPLATE'])) {

                                    $secondPrintTemplateList = $this->model->getSecondPrintTemplateModel($rtRow['META_DATA_ID'], $row);

                                    if ($secondPrintTemplateList) {
                                        $this->view->rowData = $row;
                                        $this->view->secondPrintTemplateList = $secondPrintTemplateList;
                                    }
                                }
                            }

                            if (isset($row['id'])) {
                                $this->view->recordId = $row['id'];
                            }

                            if ($countDataRow == 1 && isset($row['wfmstatuscode']) && $row['wfmstatuscode'] != '' && $rtRow['ARCHIVE_WFM_STATUS_CODE'] && !$this->view->wfmArchiveBtnArr) {
                                
                                $lowerWfmStatusCode = strtolower($row['wfmstatuscode']);
                                $arrWfmStatus = array_map('trim', explode(',', rtrim($rtRow['ARCHIVE_WFM_STATUS_CODE'], ',')));
                                
                                if (in_array($lowerWfmStatusCode, $arrWfmStatus)) {
                                    
                                    $nextStatus = $this->model->getWfmNextStatusByRow($this->view->metaDataId, $row);

                                    if ($nextStatus) {
                                        $this->view->wfmArchiveBtnArr['nextStatus'] = $nextStatus;
                                        $this->view->wfmArchiveBtnArr['metaDataId'] = $this->view->metaDataId;
                                        $this->view->wfmArchiveBtnArr['reportMetaDataId'] = $rtRow['META_DATA_ID'];
                                        $this->view->wfmArchiveBtnArr['defaultDirectoryId'] = $this->view->defaultDirectoryId;
                                        $this->view->wfmArchiveBtnArr['recordId'] = $this->view->recordId;
                                        $this->view->wfmArchiveBtnArr['archiveName'] = $this->view->isArchiveName;
                                        $this->view->wfmArchiveBtnArr['isSmartShrinking'] = $this->view->isSmartShrinking;
                                        
                                        if (issetParam($dataElement['printmode']) == 'signature_print') {
                                            $this->view->wfmArchiveBtnArr['signature_print'] = 1;
                                            
                                            foreach ($dataElement as $dataElementKey => $dataElementVal) {
                                                if (!is_array($dataElementVal)) {
                                                    $this->view->wfmArchiveBtnArr['signature_print_param'][$dataElementKey] = $dataElementVal;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        
                    break;
                }

                $headerFooter = self::renderHeaderFooterTemplate($templateId, $isTemplateMetaId, $dataElement);
                
                $search  = array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s');
                $replace = array('>',            '<',            '\\1');
                
                $headerFooter['header'] = preg_replace($search, $replace, $headerFooter['header']);
                $headerFooter['footer'] = preg_replace($search, $replace, $headerFooter['footer']);
                
                array_push($headerArray, $headerFooter['header']);
                array_push($footerArray, $headerFooter['footer']);                
                
                $i++;
            }
            
            $this->view->emailTo = null;
            $this->view->emailSubject = null;
            $this->view->emailFileName = null;
            $this->view->emailSentParams = null;
            
            if ($this->view->isEmailBtn) {
                
                if (isset($firstRow['email']) && isset($firstRow['emailsubject']) 
                    && !empty($firstRow['email']) && !empty($firstRow['emailsubject'])) {
                    
                    foreach ($dataRow as $row) {
                        if (!empty($row['email'])) {
                            $this->view->emailTo .= Input::param($row['email']).'; ';
                        }
                    }
                    
                    $this->view->emailTo = rtrim($this->view->emailTo, '; ');
                    $this->view->emailSubject = Input::param($firstRow['emailsubject']);
                    $this->view->emailFileName = Input::param(issetParam($firstRow['emailfilename']));
                    
                    if (isset($firstRow['sendtablename']) || (isset($firstRow['sendissent']) 
                        && isset($firstRow['sendprimaryfield']) && isset($firstRow['senddate']))) {
                        
                        $this->view->emailSentParams = 'id='.$firstRow['id'].'&tableName='.$firstRow['sendtablename'].'&isSent='.issetParam($firstRow['sendissent']).'&primaryField='.issetParam($firstRow['sendprimaryfield']).'&date='.issetParam($firstRow['senddate']).'&refStructureId='.issetParam($firstRow['refstructureid']);
                    }
                }
            }
            
            $this->load->model('mdtemplate', 'middleware/models/');
            
            $this->model->savePrintConfigModel($this->view->metaDataId, $print_options);
            
            $this->view->contentHtml = $array;
            $this->view->contentHeaderHtml = $headerArray;
            $this->view->contentFooterHtml = $footerArray;
            
            $this->view->downloadFileName = rtrim($this->view->downloadFileName, ', ');
            
            if ($mergeFiles) {
                
                includeLib('PDF/merge/libmergepdf/vendor/autoload');            

                $mergePdf = new \iio\libmergepdf\Merger(new iio\libmergepdf\Driver\TcpdiDriver);
                
                $cacheDir = self::clearPdfTempFile();
                
                $site_url = defined('LOCAL_URL') ? LOCAL_URL : URL;
                $previewTemplate = $this->view->renderPrint('previewTemplate', $this->viewPath);
                
                $htmlContent = preg_replace('/(<img.*?src=")(?!http|data:image\/)(.*">)/', "$1$site_url/$2", $previewTemplate);
                $htmlContent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $htmlContent);
                $htmlContent = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $htmlContent);
                
                $this->view->uid = getUID();
                $fileName = 'file_'.$this->view->uid;
                $fileToSave = $cacheDir.$fileName;
                
                if (isset($this->view->pageMargin)) {
                    $_POST['top'] = $this->view->pageMargin['top'];
                    $_POST['bottom'] = $this->view->pageMargin['bottom'];
                    $_POST['left'] = $this->view->pageMargin['left'];
                    $_POST['right'] = $this->view->pageMargin['right'];
                    $_POST['orientation'] = 'portrait';
                    $_POST['size'] = 'a4';
                }

                $css = '<style type="text/css">';
                $css .= self::printCss('return');
                $css .= '</style>';            
                
                $_POST['isIgnoreFooter'] = 1;
                
                includeLib('PDF/Pdf');
                
                $pdf = Pdf::createSnappyPdfResolverMerge('Portrait', 'A4');
                
                Pdf::generateFromHtml($pdf, $css . $htmlContent, $fileToSave);      
                
                $mergePdf->addFile("cache/report_template_pdf/$fileName.pdf");
                
                foreach ($mergeFiles as $mFileUrl => $mFileVal) {
                    $mergePdf->addFile($mFileUrl);
                }
                
                $this->view->mergedFile = 'cache/report_template_pdf/'.$this->view->uid.'.pdf';
                
                $createdPdf = $mergePdf->merge();
                file_put_contents($this->view->mergedFile, $createdPdf);

                $html = $this->view->renderPrint('previewPdf', $this->viewPath);
                
            } else {
                
                if (isset($isPageSource) && $isPageSource == '1') {
                    
                    $this->view->pageMarginRight = 0;
                    $this->view->pageMarginLeft = 0;
                    
                    $this->view->contentHtml = $this->view->renderPrint('previewTemplate', $this->viewPath);
                    $this->view->render('pagesource', $this->viewPath); exit;

                } elseif (issetParam($isHtml) == '1') {
                    
                    $this->view->render('previewTemplate', $this->viewPath); exit;
                    
                } elseif (issetParam($exportMode) == 'pdf' || issetParam($exportMode) == 'word') {
                    
                    $contentHtml = $this->view->renderPrint('previewTemplate', $this->viewPath); 
    
                    $_POST['top'] = issetDefaultVal($this->view->pageMargin['top'], '0.26cm');
                    $_POST['bottom'] = issetDefaultVal($this->view->pageMargin['bottom'], '0.26cm');
                    $_POST['left'] = issetDefaultVal($this->view->pageMargin['left'], '0.26cm');
                    $_POST['right'] = issetDefaultVal($this->view->pageMargin['right'], '0.26cm');
                    $_POST['orientation'] = $this->view->pageOrientation;
                    $_POST['size'] = $this->view->pageSize;
                    $_POST['htmlContent'] = $contentHtml;
                    $_POST['reportName'] = $this->view->downloadFileName ? $this->view->downloadFileName : 'Tемплейт';
                    
                    if ($exportMode == 'pdf') {
                        self::reportPdfExport();
                    } elseif ($exportMode == 'word') {
                        self::reportWordExport();
                    }
                    
                    exit;
                    
                } else {
                    
                    if (!empty($this->view->contentHeaderHtml[0]) || !empty($this->view->contentFooterHtml[0])) {
                        
                        $this->view->isExcelBtn = false;
                        $this->view->isWordBtn = false;
                    }
        
                    $html = $this->view->isShowPreview == '1' ? $this->view->renderPrint('printOption', $this->viewPath) : $this->view->contentHtml;
                }
            }
            
            $response = array(
                'status' => 'success', 
                'templates-debug' => $rtRow['GET_MODE'],
                'rtMetaId' => $rtRow['META_DATA_ID'],
                'isArchiveName' => issetParam($this->view->isArchiveName),
                'defaultDirectoryId' => issetParam($this->view->defaultDirectoryId),
                'Html' => $html,
                'Title' => $this->lang->line('print_btn'),
                'print_btn' => $this->lang->line('print_btn'),
                'close_btn' => $this->lang->line('close_btn')
            );
            
            if (Input::post('responseType') == 'outputArray') {
                return $response;
            } else {
                echo json_encode($response, JSON_UNESCAPED_UNICODE); exit;
            }
        }
    }

    public function printByProcess($return = false, $ctlButtons = true) {            
        
        $this->load->model('mdtemplate', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $isTemplateMetaId = false;
        $array = $headerArray = $footerArray = array();
        $this->view->isArchiveBtn = false;
        $this->view->isArchiveName = null;
        $this->view->isEmailBtn = false;
        $this->view->isAutoArchiveBtn = false;
        $this->view->isPrintBtn = ($ctlButtons) ? true : false;
        $this->view->isExcelBtn = ($ctlButtons) ? true : false;
        $this->view->isPdfBtn = ($ctlButtons) ? true : false;
        $this->view->isWordBtn = ($ctlButtons) ? true : false;
        $this->view->defaultDirectoryId = null;
        $this->view->wfmArchiveBtnArr = array();
        $this->view->recordId = null;
        $this->view->downloadFileName = null;
        $this->view->isSmartShrinking = 0;
        
        $base64Params = Input::get('base64Params');
        
        if ($base64Params) {
            $getJsonParam = base64_decode($base64Params);
        } else {
            $getJsonParam = file_get_contents('php://input');
        }
        
        @file_put_contents('log/postparam.txt', Date::currentDate().' === '.$getJsonParam);
        
        if ($getJsonParam && !isset($_POST['print_options'])) {
            
            $getJsonParam = json_decode($getJsonParam, true);
            $getJsonParam = $getJsonParam['parameter'];
            $dataRow = $getJsonParam['datarow'];
            $print_options = $getJsonParam['print_options']; 
            $isPageSource = issetParam($getJsonParam['isimage']); 
            $this->view->metaDataId = $getJsonParam['metadataid'];

            $this->view->numberOfCopies = Input::param($print_options['numberofcopies']);
            $this->view->isPrintNewPage = Input::param($print_options['isprintnewpage']);
            $this->view->isSettingsDialog = Input::param($print_options['issettingsdialog']);
            $this->view->isShowPreview = Input::param($print_options['isshowpreview']);
            $this->view->isPrintPageBottom = Input::param($print_options['isprintpagebottom']);
            $this->view->isPrintSaveTemplate = Input::param($print_options['isprintsavetemplate']);
            $this->view->isPrintPageRight = Input::param($print_options['isprintpageright']);
            $this->view->pageOrientation = Input::param($print_options['pageorientation']);
            $this->view->paperInput = Input::param($print_options['paperinput']);
            $this->view->pageSize = Input::param($print_options['pagesize']);
            $this->view->pt = Input::param($print_options['printtype']);
            $templateVarName = 'templatemetaid';
            $templateIdsVar = 'templateids';
                        
        } else {        
            
            Auth::handleLogin();
            $dataRow = Input::post('dataRow');
            $this->view->metaDataId = Input::numeric('metaDataId');

            $print_options = $_POST['print_options'];
            $this->view->numberOfCopies = $print_options['numberOfCopies'];
            $this->view->isPrintNewPage = $print_options['isPrintNewPage'];
            $this->view->isShowPreview = $print_options['isShowPreview'];
            $this->view->isSettingsDialog = Input::param($print_options['isSettingsDialog']);
            $this->view->isPrintPageBottom = Input::param($print_options['isPrintPageBottom']);
            $this->view->isPrintSaveTemplate = Input::param($print_options['isPrintSaveTemplate']);
            $this->view->isPrintPageRight = Input::param($print_options['isPrintPageRight']);
            $this->view->pageOrientation = $print_options['pageOrientation'];
            $this->view->paperInput = Input::param($print_options['paperInput']);
            $this->view->pageSize = $print_options['pageSize'];
            $this->view->pt = $print_options['printType'];
            $templateVarName = 'templateMetaId';
            $templateIdsVar = 'templateIds';            
        }
        
        if (isset($print_options[$templateVarName]) && $print_options[$templateVarName]) {
            $isTemplateMetaId = true;
            $this->view->templates[] = Input::param($print_options[$templateVarName]);
        } else {
            if (isset($print_options[$templateIdsVar]) && $print_options[$templateIdsVar]) {
                $this->view->templates = explode(',', $print_options[$templateIdsVar]);
            } else {
                $this->view->templates = isset($print_options['templates']) ? $print_options['templates'] : null;
            }
        }
            
        if (is_array($dataRow)) {
            $dataRowArr = $dataRow;
        } else {
            parse_str($dataRow, $dataRowArr);
        }
        
        $dataRowArr = Arr::changeKeyLower(isset($dataRowArr['param']) ? $dataRowArr['param'] : $dataRowArr);
        $mergeFiles = array();

        loadBarCodeImageData();
        loadPhpQuery();
        $configPrintOption = '';

        foreach ($this->view->templates as $templateId) {
            
            $rtRow = $this->model->getDataModelByTemplate($templateId, $isTemplateMetaId);
            $configPrintOption = issetParam($rtRow['CONFIG_STR']) !== '' ? Str::htmlCharToDoubleQuote($rtRow['CONFIG_STR']) : '';
            
            $this->view->reportMetaDataId = $rtRow['META_DATA_ID'];
            $this->view->isTableLayoutFixed = false;
            
            if ($rtRow['IS_TABLE_LAYOUT_FIXED'] == '1') {
                $this->view->isTableLayoutFixed = true; 
            }
            
            if ($rtRow['IS_PDF_SMART_SHRINKING'] == '1') {
                $this->view->isSmartShrinking = 1; 
            }
            
            if ($rtRow['IS_ARCHIVE'] == '1') {
                $this->view->isArchiveBtn = true; 
                $this->view->isArchiveName = $rtRow['META_DATA_NAME'];
            }
            
            if ($rtRow['IS_EMAIL'] == '1') {
                $this->view->isEmailBtn = true; 
            }
            
            if ($rtRow['IS_AUTO_ARCHIVE'] == '1') {
                $this->view->isAutoArchiveBtn = true; 
                $this->view->isArchiveName = isset($dataRowArr['booknumber']) ? $dataRowArr['booknumber'] : $rtRow['META_DATA_NAME'];
                $this->view->defaultDirectoryId = $rtRow['DIRECTORY_ID'];
            }
            
            if ($rtRow['IS_IGNORE_PRINT'] == '1') {
                $this->view->isPrintBtn = false;
            }
            
            if ($rtRow['IS_IGNORE_EXCEL'] == '1') {
                $this->view->isExcelBtn = false;
            }
            
            if ($rtRow['IS_IGNORE_PDF'] == '1') {
                $this->view->isPdfBtn = false;
            }
            
            if ($rtRow['IS_IGNORE_WORD'] == '1') {
                $this->view->isWordBtn = false;
            }
            
            if ($rtRow['PAGE_MARGIN_TOP'] != '' || $rtRow['PAGE_MARGIN_LEFT'] != '' || $rtRow['PAGE_MARGIN_RIGHT'] != '' || $rtRow['PAGE_MARGIN_BOTTOM'] != '') {
                $this->view->pageMargin = array(
                    'top' => $rtRow['PAGE_MARGIN_TOP'], 
                    'left' => $rtRow['PAGE_MARGIN_LEFT'], 
                    'right' => $rtRow['PAGE_MARGIN_RIGHT'], 
                    'bottom' => $rtRow['PAGE_MARGIN_BOTTOM']
                ); 
            }
            
            $dataModelId = $rtRow['DATA_MODEL_ID'];
            $templateGetMode = $rtRow['GET_MODE']; 
            
            switch ($templateGetMode) {

                case 'parse_json':

                    $dataElement = $this->model->getRowParseJsonDataDtl($dataModelId, $dataRow);
                    $dataElement = issetParam($dataElement['result']);
                    
                    array_push($array, self::renderTemplate($dataElement, $templateId, $isTemplateMetaId, $dataModelId));     

                    break;

                case 'consolidate':
                case 'getlist':
                default:
                    
                    $dataElement = $this->model->getRowDataDtl($dataModelId, $dataRowArr, $this->view->metaDataId, $rtRow['META_DATA_ID']);       
                    
                    if ($dataElement) {
                        
                        $renderTemplate = self::renderTemplate($dataElement, $templateId, $isTemplateMetaId, $dataModelId);

                        if ($rtRow['UI_EXPRESSION']) {

                            $UIExpressionEval = Mdexpression::reportTemplateUIExpression($rtRow['UI_EXPRESSION']);

                            $domHtml = phpQuery::newDocument($renderTemplate);

                            eval($UIExpressionEval);

                            $renderTemplate = $domHtml;
                        }

                        array_push($array, $renderTemplate);
                    }
                    
                    break;
            }

            $headerFooter = self::renderHeaderFooterTemplate($templateId, $isTemplateMetaId, $dataElement);
            
            $search = array(
                '/\>[^\S ]+/s',
                '/[^\S ]+\</s',
                '/(\s)+/s'
            );
            $replace = array(
                '>',
                '<',
                '\\1'
            );
            
            $headerFooter['header'] = preg_replace($search, $replace, $headerFooter['header']);
            $headerFooter['footer'] = preg_replace($search, $replace, $headerFooter['footer']);
            
            array_push($headerArray, $headerFooter['header']);
            array_push($footerArray, $headerFooter['footer']);            
            
            if (isset($dataRowArr['id'])) {
                $this->view->recordId = $dataRowArr['id'];
            }
            
            if (isset($dataElement['downloadfilename']) && $dataElement['downloadfilename']) {
                $this->view->downloadFileName .= $dataElement['downloadfilename'] . ', ';
            }
        } 
        
        if (isset($dataRowArr['isrtmergefiles']) && $dataRowArr['isrtmergefiles'] == '1' 
            && isset($dataRowArr['mergefiles']) && $dataRowArr['mergefiles']) {

            $mergeFilesExplode = explode(',', $dataRowArr['mergefiles']);

            foreach ($mergeFilesExplode as $mf) {
                $mf = trim($mf);

                if ($mf && pathinfo($mf, PATHINFO_EXTENSION) == 'pdf' && file_exists($mf)) {
                    $mergeFiles[$mf] = 1;
                }
            }
        }        
        
        $this->view->emailTo = null;
        $this->view->emailSubject = null;
        $this->view->emailFileName = null;
        $this->view->emailSentParams = null;
        
        if ($this->view->isEmailBtn) {
            
            if (isset($dataElement[0]['email']) && isset($dataElement[0]['emailsubject']) && 
               !empty($dataElement[0]['email']) && !empty($dataElement[0]['emailsubject'])) {
                
                $this->view->emailTo = Input::param($dataElement[0]['email']);
                $this->view->emailSubject = Input::param($dataElement[0]['emailsubject']);
                $this->view->emailFileName = Input::param($dataElement[0]['emailfilename']);
                
            } else {
                $this->view->isEmailBtn = false;
            }
        }
        
        $this->model->savePrintConfigModel($this->view->metaDataId, $print_options);
        
        $this->view->pageHeaderTitle = 'Tемплейт';
        $this->view->contentHtml = $array;
        $this->view->contentHeaderHtml = $headerArray;
        $this->view->contentFooterHtml = $footerArray;
        
        $this->view->downloadFileName = rtrim($this->view->downloadFileName, ', ');
        
        if ($mergeFiles) {
            
            includeLib('PDF/merge/libmergepdf/vendor/autoload');

            $mergePdf = new \iio\libmergepdf\Merger(new iio\libmergepdf\Driver\TcpdiDriver);
            
            $cacheDir = self::clearPdfTempFile();
            
            if ($this->view->contentHtml) {
            
                $site_url = defined('LOCAL_URL') ? LOCAL_URL : URL;
                $previewTemplate = $this->view->renderPrint('previewTemplate', $this->viewPath);

                $htmlContent = preg_replace('/(<img.*?src=")(?!http|data:image\/)(.*">)/', "$1$site_url/$2", $previewTemplate);
                $htmlContent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $htmlContent);
                $htmlContent = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $htmlContent);

                $this->view->uid = getUID();
                $fileName = 'file_'.$this->view->uid;
                $fileToSave = $cacheDir.$fileName;

                if (isset($this->view->pageMargin)) {
                    $_POST['top'] = $this->view->pageMargin['top'];
                    $_POST['bottom'] = $this->view->pageMargin['bottom'];
                    $_POST['left'] = $this->view->pageMargin['left'];
                    $_POST['right'] = $this->view->pageMargin['right'];
                    $_POST['orientation'] = 'portrait';
                    $_POST['size'] = 'a4';
                }

                $css = '<style type="text/css">';
                $css .= self::printCss('return');
                $css .= '</style>';            

                $_POST['isIgnoreFooter'] = 1;

                includeLib('PDF/Pdf');

                $pdf = Pdf::createSnappyPdfResolverMerge('Portrait', 'A4');

                Pdf::generateFromHtml($pdf, $css . $htmlContent, $fileToSave);

                $mergePdf->addFile("cache/report_template_pdf/$fileName.pdf");
            }
            
            foreach ($mergeFiles as $mFileUrl => $mFileVal) {
                $mergePdf->addFile($mFileUrl);
            }
            
            $this->view->mergedFile = 'cache/report_template_pdf/'.$this->view->uid.'.pdf';
            
            $createdPdf = $mergePdf->merge();
            file_put_contents($this->view->mergedFile, $createdPdf);

            $html = $this->view->renderPrint('previewPdf', $this->viewPath);
            
        } else { 
            
            if (isset($isPageSource) && $isPageSource == '1') {
                    
                $this->view->pageMarginRight = 0;
                $this->view->pageMarginLeft = 0;

                $this->view->contentHtml = $this->view->renderPrint('previewTemplate', $this->viewPath);
                $this->view->render('pagesource', $this->viewPath); exit;

            } else {
                $html = $this->view->isShowPreview == '1' ? $this->view->renderPrint('printOption', $this->viewPath) : $this->view->contentHtml;
            }
        }
        
        $response = array(
            'debug' => $rtRow['GET_MODE'],
            'Html' => $html,
            'print_options' => issetParam($configPrintOption) !== '' ? json_decode($configPrintOption, true) : '',
            'Title' => $this->lang->line('print_btn'),
            'print_btn' => $this->lang->line('print_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        
        if ($return) {
            return $response;
        } else {
            echo json_encode($response); exit;
        }
    }

    public function chooseTemplate() {
        
        $tempIds = implode(',', Input::post('templates'));
        $this->view->templates = $this->model->getTemplates($tempIds);
        
        $response = array(
            'Html' => $this->view->renderPrint('chooseTemplate', $this->viewPath),
            'Title' => 'Темплейт сонгох',
            'choose_btn' => $this->lang->line('choose_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function getReportTemplates($metadataId, $isProcess) {
        $this->load->model('mdtemplate', 'middleware/models/');
        $result = $this->model->getReportTemplateByDataModel($metadataId, $isProcess);
        return $result;
    }

    public function parseSpecificPartOfTable($key, $tableContent, $dataValue, $htmlContent, $tableName, $dataModelId, $dept = 0) {
        $this->load->model('mdtemplate', 'middleware/models/');
        
        if (array_key_exists(0, $dataValue)) {
            $dataRows = $dataValue;
        } else {
            $dataRows[] = $dataValue;
        }
        
        $groupingData = $this->model->getReportTemplateGroupingModel(Mdtemplate::$templateLinkId, $key); 
        
        $detailHtml = phpQuery::newDocumentHTML($htmlContent);
        $tableHtml  = $detailHtml['table[id="'.$tableName.'"]:eq(0)'];
        $bodyRow    = $tableHtml['tbody:eq(0)']->html();
        
        /*if ($dept) {
            $bodyRow = $htmlContent;
        }*/
            
        if ($groupingData && $dataRows[0][$groupingData[0]['FIELD_PATH']]) {
            
            Mdtemplate::$numIterations = 0;
            $this->reportData['count'] = 0;

            $reportDetailHtml = self::reportTemplateGrouping($key, $groupingData, $dataRows, $bodyRow, 0);
            
            $tableHtml->addClass('pf-rt-col-sorting');
            $tableHtml['tbody']->remove();
            
            if ($tableHtml['thead']->length) {
                $tableHtml['thead:last']->after($reportDetailHtml);
            } else {
                $tableHtml->html($reportDetailHtml);
            }
            
            $html = $detailHtml->html();

            return $html;
            
        } else {

            if ($tableHtml->length > 0) {
                
                $tableHtml->addClass('pf-rt-col-sorting');
                
                $appendTableRow = '';
                            
                foreach ($dataRows as $trIndex => $row) {

                    $tableBodyRow = $bodyRow;

                    foreach ($row as $k => $v) {

                        if (!is_array($v)) {
                            
                            $tableBodyRow = str_replace('#'.$key.'.'.$k.'#', Mdtemplate::valueFormatting($v, $key.'.'.$k), $tableBodyRow);
                            
                        } else {
                            
                            $subDtlTable = false;
                            $specAllTable = self::getContents($bodyRow, '<table', '>');
                            
                            foreach ($specAllTable as $tv) {
                                
                                $specificTable = new SimpleXMLElement("<element $tv />");
                                
                                if (isset($specificTable['id'])) {
                                    
                                    $tableId = $specificTable['id'];     
                                
                                    if ($tableId == $key.'.'.$k) {
                                    
                                        $table = self::getTable($tableId, $htmlContent);
                                    
                                        $tableBodyRow = self::parseSpecificPartOfTable($key.'.'.$k, $table, $v, $tableBodyRow, $tableId, $dataModelId, 1);
                                        $subDtlTable = true;
                                    }
                                }
                            }                            
                            
                            if (!$subDtlTable) {
                                foreach ($v as $vk => $vv) {
                                    if (!is_array($vv)) {
                                        $tableBodyRow = str_replace('#'.$key.'.'.$k.'.'.$vk.'#', Mdtemplate::valueFormatting($vv, $key.'.'.$k.'.'.$vk), $tableBodyRow);
                                    }
                                }
                            }
                        }
                    }

                    $tableBodyRow = str_replace('#rownum#', '<span data-nosort-col="1">'.($trIndex + 1).'</span>', $tableBodyRow);
                    
                    /*if ($dept) {
                        return $tableBodyRow;
                    }*/
                    
                    $appendTableRow .= $tableBodyRow;
                }
                
                preg_match_all('/\#(.*?)\#/i', $appendTableRow, $appendTableRowParse);
                
                if (isset($appendTableRowParse[1])) {
                    foreach ($appendTableRowParse[1] as $appendTableRowParseKey) {
                        $appendTableRowParseKeyLower = strtolower($appendTableRowParseKey);
                        if (strpos($appendTableRowParseKeyLower, '.') === false && array_key_exists($appendTableRowParseKeyLower, Mdtemplate::$responseData)) {
                            $appendTableRow = str_replace('#'.$appendTableRowParseKey.'#', Mdtemplate::valueFormatting(Mdtemplate::$responseData[$appendTableRowParseKeyLower], $appendTableRowParseKeyLower), $appendTableRow);
                        }
                    }
                } 

                $tableHtml['tbody']->html($appendTableRow)->attr('data-sort-body', '1');
                $reportDetailHtml = $detailHtml->html();
                
                if ($dept && strpos($reportDetailHtml, 'count('.$tableName.')') !== false) {
                    $reportDetailHtml = str_replace('count('.$tableName.')', count($dataRows), $reportDetailHtml);
                }

                return $reportDetailHtml;
            }
        }
        
        return $htmlContent;
    }
    
    public function reportTemplateGrouping($groupPath, $groupingData, $dataRows, $bodyRow, $depth = 0) {
        
        $html = '';
        
        if (isset($groupingData[$depth])) {
            
            if (!isset($dataRows[0][$groupingData[$depth]['FIELD_PATH']])) {
                return html_tag('div', array('class' => 'alert alert-warning'), 'Grouping хийж байгаа талбар үндсэн Datasource-с олдсонгүй!');
            }
            
            $groupingCount = count($groupingData);
        
            if ($groupingData[$depth]['GROUP_HEADER'] != '') {
                $groupHeaderHtml = '<tbody>'.$groupingData[$depth]['GROUP_HEADER'].'</tbody>';
            } else {
                $groupHeaderHtml = '';
            }

            if ($groupingData[$depth]['GROUP_FOOTER'] != '') {
                $groupFooterHtml = '<tbody>'.$groupingData[$depth]['GROUP_FOOTER'].'</tbody>';
            } else {
                $groupFooterHtml = '';
            }

            $groupedArray = Arr::groupByArray($dataRows, $groupingData[$depth]['FIELD_PATH']);

            foreach ($groupedArray as $groupedRow) {

                $rowDepth = $depth + 1;
                $this->reportData['rownum_'.$rowDepth] = 0;

                $groupHeader = $groupHeaderHtml;
                $groupFooter = $groupFooterHtml;

                foreach ($groupedRow['row'] as $groupKey => $groupValue) {

                    $groupKey = $groupPath.'.'.$groupKey;
                    $groupValue = Mdtemplate::valueFormatting($groupValue, $groupKey);

                    if (strpos($groupHeader.$groupFooter, 'sum(#'.$groupKey.'#)')) {

                        $groupHeader = str_replace('sum(#'.$groupKey.'#)', $groupKey.'_sum_'.$rowDepth, $groupHeader);
                        $groupFooter = str_replace('sum(#'.$groupKey.'#)', $groupKey.'_sum_'.$rowDepth, $groupFooter);

                        foreach (range($rowDepth, $groupingCount) as $number) {
                            $this->reportData[$groupKey.'_sum_'.$number] = 0;
                        }
                    }

                    if (isset($this->reportData[$groupKey.'_sum_'.$rowDepth])) {
                        $this->reportData[$groupKey.'_sum_'.$rowDepth] = 0;
                    }

                    $groupHeader = str_replace('#'.$groupKey.'#', $groupValue, $groupHeader);
                    $groupFooter = str_replace('#'.$groupKey.'#', $groupValue, $groupFooter);
                }

                $html .= $groupHeader;

                if ($groupingCount == $rowDepth) {

                    $htmlDetail = self::reportTemplateDetail($groupPath, $groupedRow['rows'], $bodyRow, $rowDepth);
                    $html .= $htmlDetail;
                    
                } else {
                    
                    $html .= self::reportTemplateGrouping($groupPath, $groupingData, $groupedRow['rows'], $bodyRow, $rowDepth);
                }

                $html .= $groupFooter;

                foreach ($groupedRow['row'] as $groupKey => $groupValue) {

                    $groupKey = $groupPath.'.'.$groupKey;

                    if (isset($this->reportData[$groupKey.'_sum_'.$rowDepth])) {

                        if (isset($this->reportData[$groupKey.'_sum_'.($rowDepth - 1)])) {
                            $this->reportData[$groupKey.'_sum_'.($rowDepth - 1)] += $this->reportData[$groupKey.'_sum_'.$rowDepth];
                        }

                        $html = preg_replace('/\b'.$groupKey.'_sum_'.$rowDepth.'\b/u', Mdstatement::detailFormatMoney($this->reportData[$groupKey.'_sum_'.$rowDepth]), $html);
                    }                
                }   
            }
        }
        
        return $html;
    }
    
    public function reportTemplateDetail($groupPath, $rows, $bodyRow, $rowDepth) {
        
        $appendTableRow = ''; 
        
        if (count($rows) > 0) {
            
            $appendTableRow = '<tbody data-sort-body="1">';
            
            foreach ($rows as $n => $row) {
                
                Mdtemplate::$numIterations++;

                $tableBodyRow = $bodyRow;
                
                foreach ($row as $k => $v) {
                    
                    $k = $groupPath.'.'.$k;
                    
                    if (isset($this->reportData[$k.'_sum'])) {
                        $this->reportData[$k.'_sum'] += $v;
                    }
                    if (isset($this->reportData[$k.'_sum_'.$rowDepth])) {
                        $this->reportData[$k.'_sum_'.$rowDepth] += $v;
                    }
                    
                    if (!is_array($v)) {
                        $tableBodyRow = str_replace('#'.$k.'#', Mdtemplate::valueFormatting($v, $k), $tableBodyRow);
                    }                             
                }
                
                $tableBodyRow = str_replace('#rownum#', '<span data-nosort-col="1">'.Mdtemplate::$numIterations.'</span>', $tableBodyRow);
                
                $this->reportData['count'] += 1;
                $this->reportData['rownum_'.$rowDepth] += 1;

                $appendTableRow .= $tableBodyRow;
            }
            
            $appendTableRow .= '</tbody>';
        }
        
        return $appendTableRow;
    }

    public static function getConstantValues() {
        return array(
            'sysdatetime' => Date::currentDate('Y-m-d H:i:s'), 
            'sysdate' => Date::currentDate('Y-m-d'), 
            'sysyear' => Date::currentDate('Y'), 
            'sysmonth' => Date::currentDate('m'), 
            'sysday' => Date::currentDate('d'),
            'systime' => Date::currentDate('H:i'),
            'sessionPersonName' => Ue::getSessionPersonWithLastName(),
            'sessionUserName' => Ue::getSessionUserName(),
            'sessionEmployeeId' => Ue::sessionEmployeeId(), 
            'sessionPosition' => Ue::getSessionPositionName(), 
            'sessionEmail' => Ue::getSessionEmail()
        );
    }
    
    public static function constantKeys() {
        return array(
            '#sysdatetime#' => Date::currentDate('Y-m-d H:i:s'), 
            '#sysdate#' => Date::currentDate('Y-m-d'), 
            '#sysyear#' => Date::currentDate('Y'), 
            '#sysmonth#' => Date::currentDate('m'), 
            '#sysday#' => Date::currentDate('d'),
            '#systime#' => Date::currentDate('H:i'),
            '#sessionPersonName#' => Ue::getSessionPersonWithLastName(),
            '#sessionUserName#' => Ue::getSessionUserName(),
            '#sessionEmployeeId#' => Ue::sessionEmployeeId(), 
            '#sessionPosition#' => Ue::getSessionPositionName(), 
            '#sessionEmail#' => Ue::getSessionEmail()
        );
    }
    
    public static function configValueReplacer($content) {
        $configValue = explode('_', $content);
        
        if (isset(Mdtemplate::$responseData['filterdepartmentid'])) {
            $departmentId = Mdtemplate::$responseData['filterdepartmentid'];
        } else {
            $sessionUserKeyDepartmentId = Ue::sessionUserKeyDepartmentId();
            $departmentId = $sessionUserKeyDepartmentId ? $sessionUserKeyDepartmentId : Ue::sessionDepartmentId();
        }
        
        $content = str_ireplace($content, Config::get($configValue[1], 'departmentId='.$departmentId.';'), $content);        
        
        return $content;
    }
    
    public static function dottedConfigValueReplacer($content) {

        preg_match_all('/\*config_(.*?)\*/', $content, $parseContent);

        if (count($parseContent[1]) > 0) {
            
            if (isset(Mdtemplate::$responseData['filterdepartmentid'])) {
                $departmentId = Mdtemplate::$responseData['filterdepartmentid'];
            } else {
                $sessionUserKeyDepartmentId = Ue::sessionUserKeyDepartmentId();
                $departmentId = $sessionUserKeyDepartmentId ? $sessionUserKeyDepartmentId : Ue::sessionDepartmentId();
            }
            
            foreach ($parseContent[1] as $k => $val) {
                $content = str_ireplace($parseContent[0][$k], Config::get($val, 'departmentId='.$departmentId.';'), $content);
            }
        }
        
        return $content;
    }
    
    public static function sysKeywordReplacer($content) {
        
        foreach (self::constantKeys() as $constantKey => $constantKeyValue) {
            $content = str_ireplace($constantKey, $constantKeyValue, $content);
        }
        
        return $content;
    }

    public static function getFooterMethods() {
        return array('sum', 'avg', 'min', 'max', 'first', 'last', 'count');
    }

    public static function getTable($tableId, $html) {
        $specAllTable = self::getContents($html, '<table', '</table>');
        foreach ($specAllTable as $value) {
            if (strpos($value, 'id="' . $tableId . '"') !== false) {
                return $value;
            }
        }
    }

    public function convertAllDtlValues($dtlKey, $dtlValue, $templateHtml, $dataModelId) {
        foreach ($dtlValue as $key => $dtlSeparatedValue) {
            if (!is_array($dtlSeparatedValue)) {
                $replaceValue = $dtlKey . '.' . $key;
                if (strpos($templateHtml, $replaceValue) !== false) {
                    $type = $this->model->getMetaTypeCodeByDataViewId($key, $dataModelId);
                    $templateHtml = str_replace('#' . $replaceValue . '#', Format::valueFormatting($dtlSeparatedValue, $type), $templateHtml);
                }
            } else {
                $templateHtml = self::convertAllDtlValues($key, $dtlSeparatedValue, $templateHtml, $dataModelId);
            }
        }
        
        return $templateHtml;
    }

    public static function parseFooterMethods($arg, $tableContent, $columnIndex) {
        $array = array();
        
        $tableTr = self::getContents($tableContent, '<tr', '</tr>');
        $trLength = count($tableTr);
        
        foreach ($tableTr as $key => $value) {
            if ($key != 0 && $key != ($trLength - 1)) {
                $td = self::getContents($value, '<td', '</td>');
                $tdValue = self::getContentBetween($td[$columnIndex], '<span class="tag-meta">', '</span>');
                if (empty($tdValue)) {
                    $tdValue = $td[$columnIndex];
                }
                $tdValue = str_replace(',', '', $tdValue);
                if (is_numeric($tdValue)) {
                    array_push($array, Number::numberFormat($tdValue, 0));
                }
            }
        }
        
        $aggrigateValue = '';
        
        switch ($arg) {
            case 'sum':
                $aggrigateValue = count($array) > 0 ? array_sum($array) : '0';
                break;
            case 'avg':
                $aggrigateValue = count($array) > 0 ? array_sum($array) / count($array) : '0';
                break;
            case 'min':
                $aggrigateValue = count($array) > 0 ? min($array) : '0';
                break;
            case 'max':
                $aggrigateValue = count($array) > 0 ? max($array) : '0';
                break;
            case 'first':
                $aggrigateValue = count($array) > 0 ? current($array) : '0';
                break;
            case 'last':
                $aggrigateValue = count($array) > 0 ? end($array) : '0';
                break;
            case 'count':
                $aggrigateValue = count($array);
                break;
            default:
                $aggrigateValue = 'error';
                break;
        }
        
        return $aggrigateValue;
    }
    
    public function pdfToTemp() {
        
        includeLib('PDF/Pdf');
        
        if (!class_exists('phpQuery')) {
            loadPhpQuery();
        }
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $orientation = Input::post('orientation');
        $size        = Input::post('size');
        
        $site_url = defined('LOCAL_URL') ? LOCAL_URL : URL;
        
        $htmlContent = preg_replace('/(<img.*?src=")(?!http|data:image\/)(.*">)/', "$1$site_url/$2", Input::postNonTags('content'));
        $htmlContent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $htmlContent);
        $htmlContent = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $htmlContent);
        $htmlContent = str_replace('  ', '<span style="display: inline-block; width: 30px;"></span>', $htmlContent);
        $htmlContent = str_replace(array('<nobr>', '</nobr>'), '', $htmlContent);
        
        preg_match_all('/([A-Za-zА-Яа-яӨҮөү0-9])(&nbsp;)([A-Za-zА-Яа-яӨҮөү0-9])/u', $htmlContent, $replaceMatches);
        
        if (isset($replaceMatches[0][0])) {
            foreach ($replaceMatches[0] as $replaceMatch) {
                $htmlContent = str_replace($replaceMatch, str_replace('&nbsp;', ' ', $replaceMatch), $htmlContent);
            }
        }
        
        if (strpos($htmlContent, 'contenteditable="true"') !== false) {
            
            $htmlObj = phpQuery::newDocumentHTML($htmlContent);
            $matches = $htmlObj->find('span[contenteditable="true"]');
            
            if ($matches->length) {
                
                foreach($matches as $span) {
                    $span = pq($span);
                    $span->replaceWith($span->html());
                }
            }
            
            $htmlContent = $htmlObj->html();
        }
        
        $_POST['isSmartShrinking'] = '1';
        
        $cacheDir = self::clearPdfTempFile();
        
        $fileName = 'file_'.getUID();
        $fileToSave = $cacheDir.$fileName;
        
        $css = '<style type="text/css">';
        $css .= self::printCss('return');
        $css .= '</style>';
        
        $pdf = Pdf::createSnappyPdf(($orientation == 'portrait' ? 'Portrait' : 'Landscape'), ($size != 'custom' ? $size : 'letter'));

        Pdf::generateFromHtml($pdf, $css . $htmlContent, $fileToSave);
        
        $fileToSave = $fileToSave.'.pdf';        
        
        if (file_exists($fileToSave)) {
            
            chmod($cacheDir . $fileName . '.pdf', 0755);
            $filepath = "cache/report_template_pdf/$fileName.pdf";
            
            $response = array('status' => 'success', 'message' => $filepath);
                    
        } else {
            $response = array('status' => 'error', 'message' => 'File write error');
        }
        
        echo json_encode($response); exit;
    }
    
    public function reportPdfExport() {
        
        includeLib('PDF/Pdf');
        
        $orientation        = Input::post('orientation');
        $size               = Input::post('size');
        $htmlContent        = Input::postNonTags('htmlContent');
        $isBlockChainVerify = Input::numeric('isBlockChainVerify'); 
        
        $_POST['isSmartShrinking'] = '1';
        
        $htmlContent = str_replace("\xE2\x80\x8B", '', $htmlContent);   
        $htmlContent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $htmlContent);
        $htmlContent = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $htmlContent);
        $htmlContent = str_replace('  ', '<span style="display: inline-block; width: 30px;"></span>', $htmlContent);
        $htmlContent = str_replace(array('<nobr>', '</nobr>'), '', $htmlContent);
        
        preg_match_all('/([A-Za-zА-Яа-яӨҮөү0-9])(&nbsp;)([A-Za-zА-Яа-яӨҮөү0-9])/u', $htmlContent, $replaceMatches);
        
        if (isset($replaceMatches[0][0])) {
            foreach ($replaceMatches[0] as $replaceMatch) {
                $htmlContent = str_replace($replaceMatch, str_replace('&nbsp;', ' ', $replaceMatch), $htmlContent);
            }
        }
        
        $css = '<style type="text/css">';
        $css .= self::printCss('return');
        $css .= '</style>';
                
        $_POST['isIgnoreFooter'] = 1;
        
        $pdf = Pdf::createSnappyPdf(($orientation == 'portrait' ? 'Portrait' : 'Landscape'), ($size != 'custom' ? strtoupper($size) : 'A4'));
        
        $reportName       = Input::post('reportName');
        $downloadFileName = Input::post('downloadFileName');
        
        if ($downloadFileName) {
            $reportName = $downloadFileName;
        }
        
        if ($isBlockChainVerify && Config::get('IS_BLOCKCHAIN_VERIFY')) {
            
            $sourcePath = 'verify_files/file_'.getUID();
            
            Pdf::generateFromHtml($pdf, $css . $htmlContent, UPLOADPATH . $sourcePath);
            
            $sourcePath = $sourcePath.'.pdf';
            
            self::pdfFileBlockChain($sourcePath, $reportName);
        
        } else {
            
            if (!is_null($pdf)) {
                Pdf::setSnappyOutput($pdf, $css . $htmlContent, $reportName);
            } else {
                $pdf = Pdf::createMPdf(($orientation == 'portrait' ? 'P' : 'L'), ($size != 'custom' ? strtoupper($size) : 'A4'));  
                Pdf::setMpdfOutput($pdf, $css . $htmlContent, $reportName);
            }
        }
    }
    
    public function pdfFileBlockChain($sourcePath, $reportName) {
        
        if (file_exists(UPLOADPATH . $sourcePath)) {
            
            Mdcache::createCacheFolder(UPLOADPATH . '/verify_files', 0.5);
            $envFile = '/root/corex/verify-service.env';
            
            if (!file_exists($envFile)) {
                
                header('Pragma: no-cache');
                header('Expires: 0');
                header('Set-Cookie: fileDownload=false; path=/');

                echo 'env file does not exist'; exit();
            }
            
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
            foreach ($lines as $line) {

                if (strpos(trim($line), '#') === 0) {
                    continue;
                }

                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                
                if ($name == 'AUTH_TOKEN') {
                    $apiKey = $value;
                    break;
                }
            }
            
            if (!isset($apiKey)) {
                
                header('Pragma: no-cache');
                header('Expires: 0');
                header('Set-Cookie: fileDownload=false; path=/');

                echo 'api key not found'; exit();
            }
        
            $destinationPath = 'verify_files/file_'.getUID().'.pdf';
            $url = 'http://localhost:8081/api/v1/issuer/issue-test';
            $params = array(
                'sourcePath' => 'uploads/' . $sourcePath,
                'destinationPath' => 'uploads/' . $destinationPath,
                'desc' => 'Veritech ERP'
            );

            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 300);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'X-API-KEY: ' . $apiKey, 
                'Accept: application/json', 
                'Content-Type: application/json;charset=UTF-8'
            ));

            $response = curl_exec($ch);

            if (curl_errno($ch)) {

                $msg = curl_error($ch);
                curl_close($ch);

                header('Pragma: no-cache');
                header('Expires: 0');
                header('Set-Cookie: fileDownload=false; path=/');

                echo $msg; exit();
            }

            curl_close($ch); 
            
            $destinationPath = UPLOADPATH . $destinationPath;
            
            if (file_exists($destinationPath)) {
                
                header('Content-Disposition: attachment; filename="' . $reportName . '.pdf"');
                header('Content-Type: application/pdf');
                header('Pragma: no-cache');
                header('Expires: 0');
                header('Set-Cookie: fileDownload=true; path=/');
                
                echo file_get_contents($destinationPath); exit;
                
            } else {
                header('Pragma: no-cache');
                header('Expires: 0');
                header('Set-Cookie: fileDownload=false; path=/');

                echo 'Could not create file!'; exit;
            }

        } else {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');

            echo 'Could not create file!'; exit;
        }
    }
    
    public function reportExcelExport() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $htmlContent = Input::postNonTags('htmlContent');
        
        $reportName       = Input::post('reportName');
        $downloadFileName = Input::post('downloadFileName');
        
        if ($downloadFileName) {
            $reportName = $downloadFileName;
        }
        
        try {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
            header('Content-Disposition: attachment;filename="' . $reportName . '.xls"');
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            flush();
            
            echo excelHeadTag($htmlContent);
            
        } catch (Exception $e) {
            
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');
            
            echo $e->getMessage();
        }
        
        exit;
    }
    
    public function reportWordExport() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $htmlContent = Input::postNonTags('htmlContent');
        $orientation = Input::post('orientation');
        preg_match_all('/\<img src="data:image\/png;base64,(.*?)"/', $htmlContent, $imgCatch);

        foreach ($imgCatch[0] as $key => $row) {
            $htmlContent = str_replace('<img src="data:image/png;base64,'.$imgCatch[1][$key].'"', '<object type="image/png" data="data:image/png;base64,'.$imgCatch[1][$key].'"', $htmlContent);
        }
        
        $reportName       = Input::post('reportName');
        $downloadFileName = Input::post('downloadFileName');
        
        if ($downloadFileName) {
            $reportName = $downloadFileName;
        }
        
        try {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Type:application/vnd.openxmlformats-officedocument.wordprocessingml.document;charset=utf-8');
            header('Content-Disposition: attachment;filename="' . $reportName . '.doc"');
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            flush();
            
            $attr = array('orientation' => $orientation);
            
            if (Input::postCheck('top')) {
                $attr['top'] = Input::post('top');
                $attr['left'] = Input::post('left');
                $attr['right'] = Input::post('right');
                $attr['bottom'] = Input::post('bottom');
            }
            
            echo wordHeadTag($htmlContent, $attr);  
            
        } catch (Exception $e) {
            
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');
            
            echo $e->getMessage();
        } 
        
        exit;
    }
    
    public function getAllVariablesByJson() {
        $dataViewId = Input::post('dataViewId');
        
        $this->load->model('mdtemplate', 'middleware/models/');

        $variables = array();

        $dataViewColumns = $this->model->getDataViewColumnsModel($dataViewId);

        if ($dataViewColumns) {
            $variables = $dataViewColumns;
        } 

        header('Content-type: application/json');
        echo json_encode($variables); exit;
    }

    public function checkCriteria($return = false) {
        
        Auth::handleLogin();
        
        $this->load->model('mdtemplate', 'middleware/models/');
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $isProcess = Input::post('isProcess');
        $indicatorId = Input::numeric('indicatorId');
        
        if ($indicatorId) {
            
            $this->view->metaDataId = $indicatorId;
            $templates = $this->model->getReportTemplateKpiIndicatorModel($this->view->metaDataId);
            
        } else {
        
            if (Input::isEmpty('templateId') == false) {
                $templates = $this->model->getReportTemplateByIdDataModel($this->view->metaDataId, Input::post('templateId'));
            } else {
                $templates = $this->model->getReportTemplateByDataModel($this->view->metaDataId, $isProcess);
            }
        }
        
        $dataRow = $_POST['dataRow'];
        $hasCriteria = false;
        $crTemplate = $templateGroup = array();
        
        if (!is_array($dataRow)) {
            parse_str(urldecode($dataRow), $dataRow);
            
            if (isset($dataRow['param'])) {
                $dataRow = array($dataRow['param']);
            }
        }
        
        if (!array_key_exists(0, $dataRow)) {
            $dataRowOld = $dataRow;
            $dataRow = array();
            $dataRow[] = $dataRowOld;
        }
        
        if ($isProcess == 'true') {
            $dataRow = Arr::changeKeyLower($dataRow);
        }

        if (count($dataRow) > 1) {
            
            foreach ($templates as $temp) {
                
                $isTrueTemp = false;
                
                if ($temp['CRITERIA'] != '') {
                    
                    $doneTemp = array();
                    
                    foreach ($dataRow as $dRow) {
                        
                        if (!isset($dRow['printbutton'])) {
                            $dRow['printbutton'] = '';
                        }
            
                        $criteria = self::convertToCriteria($temp['CRITERIA'], $dRow);
                    
                        if (Mdcommon::expressionEvalFixWithReturn($criteria)) {
                            $hasCriteria = true;
                            
                            if (!isset($doneTemp[$temp['ID']])) {
                                
                                $doneTemp[$temp['ID']] = 1;
                                array_push($crTemplate, $temp);
                                $isTrueTemp = true;
                                
                                if (issetParam($temp['IS_DEFAULT']) == '1') {
                                    $this->view->defaultTemplateId = $temp['ID'];
                                }
                            }
                        } 
                    }
                    
                } else {
                    
                    $hasCriteria = true;
                    array_push($crTemplate, $temp);
                    
                    $isTrueTemp = true;
                    
                    if (issetParam($temp['IS_DEFAULT']) == '1') {
                        $this->view->defaultTemplateId = $temp['ID'];
                    }
                }
                
                if ($isTrueTemp && $temp['TEMPLATE_GROUP_ID']) {
                    $templateGroup[$temp['TEMPLATE_GROUP_ID']] = array(
                        'id' => $temp['TEMPLATE_GROUP_ID'], 
                        'name' => $temp['TEMPLATE_GROUP_NAME']
                    );
                }
            }
            
        } else {
            
            $firstRow = $dataRow[0];
            
            if (!isset($firstRow['printbutton'])) {
                $firstRow['printbutton'] = '';
            }
            
            foreach ($templates as $temp) {
                
                $isTrueTemp = false;
                
                if ($temp['CRITERIA'] != '') {
                    $criteria = self::convertToCriteria($temp['CRITERIA'], $firstRow);
                    
                    if (Mdcommon::expressionEvalFixWithReturn($criteria)) {
                        
                        $hasCriteria = true;
                        $isTrueTemp = true;
                        
                        array_push($crTemplate, $temp);
                        
                        if (issetParam($temp['IS_DEFAULT']) == '1') {
                            $this->view->defaultTemplateId = $temp['ID'];
                        }
                    } 
                    
                } else {
                    $hasCriteria = true;
                    $isTrueTemp = true;
                    
                    array_push($crTemplate, $temp);
                    
                    if (issetParam($temp['IS_DEFAULT']) == '1') {
                        $this->view->defaultTemplateId = $temp['ID'];
                    }
                }
                
                if ($isTrueTemp && $temp['TEMPLATE_GROUP_ID']) {
                    $templateGroup[$temp['TEMPLATE_GROUP_ID']] = array(
                        'id' => $temp['TEMPLATE_GROUP_ID'], 
                        'name' => $temp['TEMPLATE_GROUP_NAME']
                    );
                }
            }
        }
        
        if ($hasCriteria) {
            $templates = $crTemplate;
        } else {
            $templates = array();
        }
        
        if (count($templates) == 1 && issetParam($templates[0]['CONFIG_STR']) !== '') {
            echo Str::htmlCharToDoubleQuote($templates[0]['CONFIG_STR']); exit;
        }
            
        if ($return) {
            return $templates;
        }
        
        $this->view->templatesCount = count($templates);
        $this->view->userRow = $this->model->getPrintConfigByUserModel($this->view->metaDataId, $templates);
        $this->view->rowClass = '';
        
        if (isset($this->view->userRow['isSettingsDialog']) && $this->view->userRow['isSettingsDialog'] == '1') {
            
            if ($this->view->templatesCount == 1) {
                echo json_encode($this->view->userRow); exit;
            }
            
            $this->view->rowClass = ' d-none';
        }
        
        if (!$this->view->templatesCount) {
            
            $response = array(
                'status' => 'warning', 
                'message' => $this->lang->lineDefault('rt_notemplate_msg', 'Хэвлэх загвар тохируулагдаагүй байна.'), 
                'title' => $this->lang->line('MET_99990001'),
                'html' => ''
            );
            
        } else {
        
            $this->view->reportTemplate = $templates;
            $this->view->templateGroup = $templateGroup;
            $this->view->templateChooseType = 'singleselect';

            if (Config::getFromCache('isReportTemplateCollapseOptions') == '1') {
                $optionsName = 'collapseOptions';
            } else {
                $optionsName = 'options';
            }

            $this->view->options = $this->view->renderPrint($optionsName, 'middleware/views/template/options/');

            $response = array(
                'status' => 'success', 
                'title' => $this->lang->line('MET_99990001'),
                'html' => $this->view->renderPrint('printDropdownSettings', 'middleware/views/template/')
            );
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public static function convertToCriteria($criteria, $dataRow) {
        
        $criteria = Str::lower(html_entity_decode($criteria, ENT_QUOTES, 'UTF-8'));
        
        foreach ($dataRow as $key => $value) {
            
            if (!is_array($value)) {
                
                if (is_string($value) && strpos($value, "'") === false) {
                    $value = "'".Str::lower($value)."'";
                } elseif (is_null($value)) {
                    $value = "''";
                }
                
                $key = ($key == '' ? 'tmpkey' : $key);
                        
                $criteria = preg_replace('/\b'.$key.'\b/u', $value, $criteria);
            }
        }
        
        $criteria = Mdmetadata::defaultKeywordReplacer($criteria);
        $criteria = str_replace('isclosedfiscalperiod', '(new Mdcommon())->isClosedFiscalPeriod', $criteria);
        
        return $criteria;
    }
    
    public function nddPrintSettingsCtrl() {
        $this->load->model('mdwebservice', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }        
        
        $this->view->employeeKeyId = Input::post('id');
        $this->view->bookTypeId = Input::post('booktypeid');
        
        if (is_null($this->view->employeeKeyId)) {
            die('<h3><center>Workspace Menu тохиргоо буруу байна!</center></h3>');
        }  
        
        $this->view->getEmployeePrintConfig = $this->model->getEmployeePrintConfigModel($this->view->employeeKeyId); 
        $this->view->getNDDprintYear = $this->model->getNDDprintYearModel();
        
        $this->view->render('NDD/printOption', $this->viewPath);
    }     
    
    public function getNDDprintTemplateCtrl() {
        $this->load->model('mdmeta', 'middleware/models/');
        $metaDataId = $this->model->getMetaDataIdByCodeModel(self::$employeeNDDmetaCode);
        
        $this->load->model('mdwebservice', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }        
        
        $employeeKeyId = Input::post('empKeyId');
        $printOptions = $_POST['print_options'];
        $varGetNDDPosition = $this->model->getNDDprintPositionModel($employeeKeyId, $metaDataId, $printOptions);
        $this->view->getNDDprintPreview = $varGetNDDPosition['previewSize'];
        
        unset($varGetNDDPosition['previewSize']);
        
        $this->view->getNDDprintPosition = json_encode($varGetNDDPosition);
        $this->view->getNDDprintPreviewJson = json_encode($this->view->getNDDprintPreview);
        $this->view->htmlTemplate = $this->view->renderPrint('NDD/printPreviewTemp', $this->viewPath);
        
        if ($varGetNDDPosition === false) {
            $response = array(
                'status' => 'info',
                'message' => 'Хэвлэх тохиргоо хийгдээгүй байна.'
            );
            echo json_encode($response); exit;
        }
        
        $response = array(
            'html' => $this->view->renderPrint('NDD/printPreview', $this->viewPath),
            'title' => 'НДД хэвлэхээр харах',
            'print_btn' => Lang::line('print_btn'),
            'close_btn' => Lang::line('close_btn')
        );       
        echo json_encode($response); exit;
    }     
    
    public function alertNDDTemplateCtrl() {
        $this->load->model('mdwebservice', 'middleware/models/');
        
        $employeeKeyId = Input::post('empKeyId');
        $printOptions = $_POST['print_options'];
        
        $varGetNDDPosition = $this->model->getNDDprintPositionCheckModel($employeeKeyId, $printOptions);
        
        if (issetVar($varGetNDDPosition['ID']) === '') {
            $response = array(
                'status' => 'not-found',
                'message' => 'Хэвлэх тохиргоо хийгдээгүй байна.'
            );
            echo json_encode($response); exit;
        }
        $getEmployeeName = $this->model->getEmployeeNameModel($employeeKeyId);
        
        $response = array(
            'status' => 'found',
            'html' => '<br><strong>' . $getEmployeeName . '</strong> ажилтны хуучин тохиргоо өөрчлөгдөхийг анхаарна уу!</br></br>',
            'title' => 'Анхааруулга',
            'yes_btn' => Lang::line('yes_btn'),
            'no_btn' => Lang::line('no_btn')
        );       
        echo json_encode($response); exit;
    }     
    
    public function sendMailForm() {
        
        Auth::handleLogin();
        
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $this->view->emailTo = Input::post('emailTo');
        $this->view->emailSubject = Input::post('emailSubject');
        $this->view->emailFileName = Input::post('emailFileName');
        
        $response = array(
            'html' => $this->view->renderPrint('sendMail', $this->viewPath),
            'title' => $this->lang->line('sendmail'),
            'send_btn' => $this->lang->line('send_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function sendMail() {
        
        Auth::handleLogin();
        
        $orientation = Input::post('orientation');
        $size = Input::post('size');
        $_POST['left'] = $_POST['right'] = 1;
        $emailTo = Input::post('emailTo');
        $emailToCc = Input::post('emailToCc');
        $emailToBcc = Input::post('emailToBcc');
        $emailSubject = Input::post('emailSubject');
        $emailBody = Input::post('emailBody');
        $emailFileName = Input::post('emailFileName');
        $isSendPdf = Input::post('isSendPdf');
        $isSendExcel = Input::post('isSendExcel');
        $isSendWord = Input::post('isSendWord');
        $filePdfExist = $fileExcelExist = $fileWordExist = true;

        $site_url = URL;
        $htmlContent = preg_replace('/(<img.*?src=")(?!http|data:image\/)(.*">)/', "$1$site_url/$2", Input::postNonTags('content'));
        $htmlContent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $htmlContent);
        $htmlContent = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $htmlContent);
        
        $uniqFileName = 'file_' . getUID();
        $emailFileName = $emailFileName ? $emailFileName : $uniqFileName;

        $fileToSave = UPLOADPATH . Mdwebservice::$uploadedPath . $uniqFileName;
        
        if ($isSendPdf == '1') {
            
            $css = '<style type="text/css">';
            $css .= self::printCss('return');
            $css .= '</style>';

            includeLib('PDF/Pdf');
            
            $_POST['isIgnoreFooter'] = 1;
            
            $pdf = Pdf::createSnappyPdf(($orientation == 'portrait' ? 'Portrait' : 'Landscape'), ($size != 'custom' ? strtoupper($size) : 'A4'));

            Pdf::generateFromHtml($pdf, $css . $htmlContent, $fileToSave);

            $filePdfExist = file_exists(BASEPATH . $fileToSave . '.pdf');
        }

        if ($isSendExcel == '1') {
            try {
                header('Pragma: no-cache');
                header('Expires: 0');
                header('Set-Cookie: fileDownload=true; path=/');
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
                header('Content-Disposition: attachment;filename="' . $fileToSave . '.xls"');
                header('Content-Transfer-Encoding: binary');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                flush();

                file_put_contents($fileToSave . '.xls', excelHeadTag($htmlContent));

                $fileExcelExist = file_exists(BASEPATH . $fileToSave . '.xls');
                
            } catch (Exception $e) {
                echo json_encode(array('status' => 'error', 'message' => $e->getMessage())); exit;
            }
        }
        
        if ($isSendWord == '1') {
            try {
                header('Pragma: no-cache');
                header('Expires: 0');
                header('Set-Cookie: fileDownload=true; path=/');
                header('Content-Type:application/vnd.openxmlformats-officedocument.wordprocessingml.document;charset=utf-8');
                header('Content-Disposition: attachment;filename="' . $fileToSave . '.doc"');
                header('Content-Transfer-Encoding: binary');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                flush();

                file_put_contents($fileToSave . '.doc', wordHeadTag($htmlContent));

                $fileWordExist = file_exists(BASEPATH . $fileToSave . '.doc');
                
            } catch (Exception $e) {
                echo json_encode(array('status' => 'error', 'message' => $e->getMessage())); exit;
            }
        }

        if ($filePdfExist && $fileExcelExist && $fileWordExist) {
            
            $this->load->model('mdtemplate', 'middleware/models/');
        
            $response = $this->model->sendMailModel($emailTo, $emailToCc, $emailToBcc, $emailSubject, $emailBody, $fileToSave, $isSendPdf, $isSendExcel, $isSendWord, $emailFileName);
        
        } else {
            $response = array('status' => 'error', 'message' => 'File write error');
        }

        echo json_encode($response); exit;
    }
    
    public function nddBookPrint() {
        
        $this->view->uniqId = getUID();
        $this->view->getNDDprintYear = $this->model->getNDDYearModel();
        
        $this->view->type = Input::post('type');
        
        if ($this->view->type == 'social') {
            
            $title = 'Нийгмийн даатгалын дэвтэр хэвлэх';
            
            $this->view->getNDDprintPreview = array(
                'left' => '13',
                'top' => '19',
                'between' => '10',
                'head_height' => 26,
                'col1Width' => '16',
                'col2Width' => '14',
                'col3Width' => '14', 
                'width' => '80', 
                'rowHeight' => '6',
                'height' => 98
            );
            
            $this->view->getNDDBookType = $this->model->getNDDBookTypeModel($this->view->type);
            
            $this->view->htmlTemplate = $this->view->renderPrint('NDD/printPreviewTemp', $this->viewPath);
            $this->view->nddPreview = $this->view->renderPrint('NDD/nddPreview', $this->viewPath);
        
        } else {
            
            $title = 'Эрүүл мэндийн даатгалын дэвтэр хэвлэх';
            
            $this->view->getNDDprintPreview = array(
                'left' => '13',
                'top' => '19',
                'between' => '10',
                'head_height' => 26,
                'col1Width' => '16',
                'col2Width' => '14',
                'col3Width' => '14', 
                'width' => '80', 
                'rowHeight' => '6',
                'height' => 98
            );
            
            $this->view->getNDDBookType = $this->model->getNDDBookTypeModel($this->view->type);
            
            $this->view->htmlTemplate = $this->view->renderPrint('NDD/printPreviewTempEmdd', $this->viewPath);
            $this->view->nddPreview = $this->view->renderPrint('NDD/nddPreviewEmdd', $this->viewPath);
        }
        
        $response = array(
            'html' => $this->view->renderPrint('NDD/nddBookPrint', $this->viewPath),
            'title' => $title,
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit();
    }
    
    public function getEmployeeLastConfig() {

        $employeeId = Input::post('employeeId');
        $type = Input::post('type');
        $row = $this->model->getEmployeeLastConfigModel($employeeId, $type); 
        
        echo json_encode($row); exit();
    }     
    
    public function renderNDDBook() {
        
        $postData = Input::postData();
        $employeeId = Input::param($postData['nddEmployeeId']);
        $type = Input::param($postData['type']);
        
        $varGetNDDPosition = $this->model->getNDDprintPositionModel($employeeId, $postData);
        $this->view->getNDDprintPreview = $varGetNDDPosition['previewSize'];
        
        unset($varGetNDDPosition['previewSize']);
        
        $this->view->getNDDprintPosition = $varGetNDDPosition;
        
        if ($type == 'social') {
            $this->view->htmlTemplate = $this->view->renderPrint('NDD/printPreviewTemp', $this->viewPath);
            $nddPreview = $this->view->renderPrint('NDD/nddPreview', $this->viewPath);
        } else {
            $this->view->htmlTemplate = $this->view->renderPrint('NDD/printPreviewTempEmdd', $this->viewPath);
            $nddPreview = $this->view->renderPrint('NDD/nddPreviewEmdd', $this->viewPath);
        }
        
        echo json_encode(array('html' => $nddPreview)); exit();
    }
    
    public function reportTemplateViewer() {
        
        Auth::handleLogin();

        $this->view->isAdd = true;
        $this->view->isEdit = true;
        $this->view->isBack = false;

        $this->view->folderId = null;
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->isExternalTool = Config::getFromCacheDefault('CONFIG_REPORT_TEMPLATE_SERVER_ADDRESS', null, '');
        
        if (Input::postCheck('folderId') && Input::isEmpty('folderId') == false) {
            
            $this->view->isBack = true;
            $this->view->folderId = Input::post('folderId');
            
            $this->view->folderList = $this->model->getChildFolderReportTemplateModel($this->view->metaDataId, $this->view->folderId);
            $this->view->reportTemplateList = $this->model->getChildReportTemplateListModel($this->view->metaDataId, $this->view->folderId);
            
        } else {
            $this->view->folderList = $this->model->getParentFolderReportTemplateModel($this->view->metaDataId);
            $this->view->reportTemplateList = $this->model->getReportTemplateListModel($this->view->metaDataId);
        }
        
        $response = array(
            'status' => 'success',
            'html' => $this->view->renderPrint('view/reportTemplateList', 'middleware/views/template/')
        );
        echo json_encode($response); exit;
    }
    
    public function addTemplateFolder() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->folderId = Input::post('folderId');
        
        $this->view->folderList = $this->model->getTemplateFolderListModel($this->view->metaDataId);
        
        $response = array(
            'html' => $this->view->renderPrint('view/addTemplateFolder', 'middleware/views/template/'),
            'title' => $this->lang->line('add_folder'), 
            'save_btn' => $this->lang->line('save_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function addTemplateFolderSave() {
        Auth::handleLogin();
        
        $response = $this->model->addTemplateFolderSaveModel(); 
        echo json_encode($response); exit;
    }
    
    public function historyBackTemplateList() {
        
        $row = $this->model->getParentTemplateFolderModel(Input::numeric('metaDataId'), Input::post('folderId'));

        $response = array('parentFolderId' => $row['PARENT_ID']);
        echo json_encode($response); exit;
    }
    
    public function editTemplate() {
        
        Auth::handleLogin();
        
        $this->view->id = Input::post('id');
        $this->view->metaDataId = Input::post('dataViewId');
        $this->view->folderId = Input::post('folderId');
        
        if ($checkPermission = $this->model->checkReportTemplatePermissionModel($this->view->id)) {
            echo json_encode($checkPermission); exit;
        }
        
        $this->view->row = $this->model->getMetaReportTemplateRowModel($this->view->id, $this->view->metaDataId);
        $this->view->folderList = $this->model->getTemplateFolderListModel($this->view->metaDataId);
        $this->view->userList = $this->model->getReportTemplateUserPermissionModel($this->view->id);
        
        $response = array(
            'html' => $this->view->renderPrint('view/editTemplate', 'middleware/views/template/'),
            'title' => $this->lang->line('edit_btn'), 
            'save_btn' => $this->lang->line('save_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function editTemplateFile() {
        
        Auth::handleLogin();
        
        $this->view->id = Input::post('id');
        
        if ($checkPermission = $this->model->checkReportTemplatePermissionModel($this->view->id)) {
            echo json_encode($checkPermission); exit;
        }
        
        $this->view->row = $this->model->getReportTemplateByMetaDataId($this->view->id);
        
        if ($this->view->row && file_exists($this->view->row['HTML_FILE_PATH'])) {
            $this->view->htmlContent = file_get_contents($this->view->row['HTML_FILE_PATH']);
        } else {
            $this->view->htmlContent = null;
        }
        
        $this->view->htmlHeaderContent = $this->view->row['HTML_HEADER_CONTENT'];
        $this->view->htmlFooterContent = $this->view->row['HTML_FOOTER_CONTENT'];
        
        $this->view->metaDataId = $this->view->row['DATA_MODEL_ID'];
        
        $this->load->model('mdmetadata', 'middleware/models/');
        $this->view->paths = $this->model->getHierarchyPathsByDvModel($this->view->metaDataId);
        
        $this->view->sysKeywords = (new Mdstatement())->sysKeywords();
        
        $this->view->fields = Form::hidden(array('name' => 'metaDataId', 'value' => Input::post('dataViewId')));
        $this->view->fields .= Form::hidden(array('name' => 'id', 'value' => $this->view->id));
        
        $this->view->pageOption = $this->view->renderPrint('system/link/reportTemplate/pageOption', Mdmetadata::$viewPath);

        $response = array(
            'html' => $this->view->renderPrint('system/link/reportTemplate/tinymce_editor', Mdmetadata::$viewPath),
            'title' => 'Template editor',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function iframeReportDesigner() {
        
        $reportTemplateId = Input::post('reportTemplateId');
        
        if (Input::postCheck('dvId')) {
            
            $dvId = Input::post('dvId');
            $getReport = $this->model->getReportIdModel($dvId);
            
        } else {
            
            $stRow = $this->model->getReportTemplate($reportTemplateId, true);
            
            $dvId = $stRow['DATA_MODEL_ID'];
            $getReport = $this->model->getReportIdModel($dvId);
        }
        
        if ($getReport['status'] == 'success') {
            
            $this->view->uniqId = getUID();
            $this->view->layoutId = $reportTemplateId;
            $this->view->reportId = $getReport['reportId'];
            $this->view->windowHeight = Input::post('windowHeight') - 87;
            
            if (Input::isEmpty('expandDvId') == false) {
                
                $expandReport = $this->model->getReportIdModel(Input::post('expandDvId'));
                
                if ($expandReport['status'] != 'success') {
                    
                    $response = array(
                        'status' => 'error', 
                        'message' => $expandReport['message']
                    );
                    
                    echo json_encode($response); exit;
                }
                
                $expandReportId = $expandReport['reportId'];
            }
            
            $this->view->iframeUrl = Config::getFromCache('CONFIG_REPORT_TEMPLATE_SERVER_ADDRESS') . 'Designer.aspx?reportid='.$this->view->reportId.'&layoutId='.$this->view->layoutId;
            
            if (isset($expandReportId)) {
                $this->view->iframeUrl .= '&subReportIds=' . $expandReportId;
            }
            
            $response = array(
                'status' => 'success', 
                'html' => $this->view->renderPrint('iframeDesigner', 'middleware/views/metadata/system/link/reportTemplate/'),
                'title' => 'Report designer', 
                'close_btn' => $this->lang->line('close_btn')
            );
            
        } else {
            $response = array(
                'status' => 'error', 
                'message' => $getReport['message']
            );
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function editTemplateFolder() {
        
        Auth::handleLogin();
        
        $this->view->folderId = Input::post('folderId');
        $this->view->metaDataId = Input::numeric('metaDataId');

        if ($checkPermission = $this->model->checkReportTemplatePermissionModel($this->view->folderId)) {
            echo json_encode($checkPermission); exit;
        }        
        
        $this->view->row = $this->model->getTemplateFolderModel($this->view->folderId);
        $this->view->folderList = $this->model->getTemplateFolderListModel($this->view->metaDataId, $this->view->folderId);
        $this->view->userList = $this->model->getReportTemplateFolderUserPermissionModel($this->view->folderId, $this->view->metaDataId);
        
        $response = array(
            'html' => $this->view->renderPrint('view/editTemplateFolder', 'middleware/views/template/'),
            'title' => $this->lang->line('add_folder'), 
            'save_btn' => $this->lang->line('save_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    } 
    
    public function editTemplateFolderSave() {
        Auth::handleLogin();
        
        $response = $this->model->editTemplateFolderSaveModel(); 
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function deleteTemplateFolder() {
        Auth::handleLogin();
        
        $response = $this->model->deleteTemplateFolderModel(); 
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function editTemplateSave() {
        Auth::handleLogin();
        
        $response = $this->model->editTemplateSaveModel(); 
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function editTemplateFileSave() {
        Auth::handleLogin();
        
        $response = $this->model->editTemplateFileSaveModel(); 
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function copyTemplate() {
        
        Auth::handleLogin();
        
        $this->view->id = Input::post('id');
        $this->view->metaDataId = Input::post('dataViewId');
        $this->view->folderId = Input::post('folderId');
        
        $this->view->row = $this->model->getMetaReportTemplateRowModel($this->view->id, $this->view->metaDataId);
        $this->view->folderList = $this->model->getTemplateFolderListModel($this->view->metaDataId);
        $this->view->userList = $this->model->getReportTemplateUserPermissionModel($this->view->id);
        
        $response = array(
            'html' => $this->view->renderPrint('view/editTemplate', 'middleware/views/template/'),
            'title' => $this->lang->line('copy_btn'), 
            'save_btn' => $this->lang->line('save_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response, JSON_UNESCAPED_UNICODE); 
    }
    
    public function copyTemplateSave() {
        Auth::handleLogin();
        
        $response = $this->model->copyTemplateSaveModel(); 
        echo json_encode($response, JSON_UNESCAPED_UNICODE); 
    }
    
    public function deleteDataViewTemplate() {
        Auth::handleLogin();
        
        $response = $this->model->deleteDataViewTemplateModel(); 
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function previewTemplateFile() {
        
        Auth::handleLogin();
        
        $this->view->id = Input::post('id');
        
        if ($checkPermission = $this->model->checkReportTemplatePermissionModel($this->view->id)) {
            echo json_encode($checkPermission); exit;
        }
        
        $this->view->row = $this->model->getReportTemplateByMetaDataId($this->view->id);
        
        if ($this->view->row && file_exists($this->view->row['HTML_FILE_PATH'])) {
            $this->view->htmlContent = file_get_contents($this->view->row['HTML_FILE_PATH']);
        } else {
            $this->view->htmlContent = null;
        }

        $response = array(
            'title' => 'Template preview', 
            'html' => $this->view->htmlContent
        );
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public static function printCss($mode = null) {
        
        $orientation = Input::post('orientation');
        $size = strtoupper(Input::post('size'));
        $top = Input::post('top');
        $top = ($top != '') ? $top : '0';
        $left = Input::post('left');
        $left = ($left != '') ? $left : '0';
        $bottom = Input::post('bottom');
        $bottom = ($bottom != '') ? $bottom : '0';
        $right = Input::post('right');
        $right = ($right != '') ? $right : '0';
        $isPrintNewPage = Input::post('isPrintNewPage');
        
        $css = '@page {
            margin-top: '.$top.';
            margin-right: '.$right.';
            margin-bottom: '.$bottom.';
            margin-left: '.$left.';
            size: '.$size.' '.$orientation.';
            width: 100%;
            orientation: '.$orientation.';
        }
        * {
            -webkit-box-sizing: border-box;
               -moz-box-sizing: border-box;
                    box-sizing: border-box;
        }
        *:before,
        *:after {
            -webkit-box-sizing: border-box;
               -moz-box-sizing: border-box;
                    box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            line-height: 1.4em;
            font-size: 12px;
            font-family: "Times Roman", sans-serif;
            color: #000;
            width: 100%;
            letter-spacing: 0.1px;
            font-kerning: normal;
            text-rendering: geometricPrecision;
            -webkit-print-color-adjust: exact;
        }
        a, a:visited, a:hover, a:active {
            color: inherit; 
            text-decoration: none; 
        } 
        a:after { content: \'\'; } 
        a[href]:after { content: none !important; } 
        .navbar, .sidebar-nav {
            display: none;
        }
        table {
            border-collapse: collapse !important;
            font-size: 12px;
            border-color: grey;
            line-height: 1em;
        }
        .reportTableLayoutFixed table {
            table-layout: fixed;
            clear: both;
            border-collapse: collapse;
            word-wrap: break-word;
        }
        #main_table table {
            border-collapse: collapse !important;
            page-break-inside: avoid !important;
        }
        td, th { 
            -webkit-column-break-inside: avoid; 
            page-break-inside: avoid; 
            break-inside: avoid; 
            -webkit-column-break-after: auto;
            page-break-after: auto;
            break-after: auto;
        }
        tr { 
            page-break-inside: avoid; 
            page-break-after: auto;
        }
        thead {
            display: table-header-group; 
        }
        tbody {
            display: table-row-group;
        }
        tfoot {
            display: table-footer-group;
        }
        table thead th, table thead td, table tbody td, table tfoot td {
            border-collapse: collapse !important;
            padding: 2px 3px;
            page-break-inside: avoid;
            position: relative;
        }
        table tbody td.bold {
            font-weight: bold;
        }
        table tbody td.cell-depth-1 {
            padding: 3px 4px 3px 15px !important;
        }
        table tbody td.cell-depth-2 {
            padding: 3px 4px 3px 35px !important;
        }
        table tbody td.cell-depth-3 {
            padding: 3px 4px 3px 55px !important;
        }
        p {
            margin: 0;
        }
        .right-rotate {
            -webkit-transform: rotate(90deg);
            -moz-transform: rotate(90deg);
            -ms-transform: rotate(90deg);
            -o-transform: rotate(90deg);
            transform: rotate(90deg);
            word-wrap: break-word;
        }
        .left-rotate {
            transform-origin: 0 50%;
            -webkit-transform: rotate(-90deg);
            -moz-transform: rotate(-90deg);
            -ms-transform: rotate(-90deg);
            -o-transform: rotate(-90deg);
            transform: rotate(-90deg);
            white-space: nowrap;
            display: block;
            position: absolute;
            bottom: 0;
            left: 50%;      
        }
        .right-rotate span, .left-rotate span {
            display: block;
        }
        table.pf-report-table-none, 
        table.pf-report-table-none td, 
        table.pf-report-table-none th {
            border: 0px #fff solid;
        }
        table.pf-report-table-dotted, 
        table.pf-report-table-dotted td, 
        table.pf-report-table-dotted th {
            border: 1px #000 dotted;
        }
        table.pf-report-table-dashed, 
        table.pf-report-table-dashed td, 
        table.pf-report-table-dashed th {
            border: 1px #000 dashed;
        }
        table.pf-report-table-solid, 
        table.pf-report-table-solid td, 
        table.pf-report-table-solid th {
            border: 1px #000 solid;
        }';
        
        if ($isPrintNewPage == '1') {
            $css .= 'tr { -webkit-column-break-inside: avoid; page-break-inside: avoid; break-inside: avoid; -webkit-column-break-after: auto;page-break-after:auto;break-after:auto;}';
        }
        
        if ($mode) {
            
            ob_start("ob_html_compress");
            $compressCss = $css; 
            ob_end_flush();
            
            return $compressCss;
            
        } else {
            ob_start("ob_html_compress"); 
                echo $css;
            ob_end_flush();
            exit;
        }
    }
    
    public function renderHeaderFooterTemplate($templateId, $isTemplateMetaId = false, $dataElement = false) {
        
        $this->load->model('mdtemplate', 'middleware/models/');
        
        $template = $this->model->getReportTemplate($templateId, $isTemplateMetaId);
        
        Mdtemplate::$templateLinkId = $template['ID'];
        
        $templateHTML = $templateFooterHTML = '';
        
        if ($template['HTML_HEADER_CONTENT'] != '') {
            
            $templateHTML = Str::cleanOut($template['HTML_HEADER_CONTENT']);
            $templateHTML = self::htmlKeyValueReplacer($templateHTML, $dataElement);
        }    
        
        if ($template['HTML_FOOTER_CONTENT'] != '') {
            
            $templateFooterHTML = Str::cleanOut($template['HTML_FOOTER_CONTENT']);
            $templateFooterHTML = self::htmlKeyValueReplacer($templateFooterHTML, $dataElement);
        }    
        
        return array('header' => $templateHTML, 'footer' => $templateFooterHTML);
    }    
    
    public static function htmlKeyValueReplacer($templateHTML, $dataElement) {
        
        $templateHTML = str_replace('background-color: #', 'background-color: colorCode', $templateHTML);
            
        //convert constant values
        $constantValues = self::getContents($templateHTML, '*', '*');

        if (!empty($constantValues)) {
            $constants = self::getConstantValues();

            foreach ($constantValues as $cons) {
                if (isset($constants[$cons])) {
                    $templateHTML = str_replace('*' . $cons . '*', $constants[$cons], $templateHTML);
                }
                if (substr($cons, 0, 7) == 'config_') {
                    $configValue = self::configValueReplacer($cons);
                    $templateHTML = str_replace('*' . $cons . '*', $configValue, $templateHTML);
                }
            }
        }

        if ($dataElement && strpos($templateHTML, '#') !== false) {
            foreach ($dataElement as $key => $value) {
                if (!is_array($value) && stripos($templateHTML, '#'.$key.'#') !== false) {
                    $templateHTML = str_ireplace('#'.$key.'#', Mdtemplate::valueFormatting($value, $key), $templateHTML);                                                 
                }
            }
        }            

        //check new wrapped values
        $templateHTML = self::sysKeywordReplacer($templateHTML);
        $templateHTML = Mdstatement::configValueReplacer($templateHTML);
        $templateHTML = self::dottedConfigValueReplacer($templateHTML);
        $templateHTML = Mdstatement::textStyler($templateHTML);

        $templateHTML = preg_replace('/\#([A-Za-z0-9_.]+)\#/s', '', $templateHTML);

        $templateHTML = Mdstatement::calculateExpression($templateHTML);
        $templateHTML = Mdstatement::runExpression($templateHTML);
        $templateHTML = Mdstatement::runExpressionStr($templateHTML);
        $templateHTML = Mdstatement::numberToWords($templateHTML);
        $templateHTML = Mdstatement::reportDateDiff($templateHTML);
        $templateHTML = Mdstatement::editable($templateHTML);
        $templateHTML = Mdstatement::assetsReplacer($templateHTML);
        $templateHTML = Mdstatement::barcode($templateHTML);
        $templateHTML = Mdstatement::qrcode($templateHTML);
        $templateHTML = Mdstatement::langLine($templateHTML);
        $templateHTML = Mdstatement::reportSubstr($templateHTML);
        $templateHTML = self::printKpiForm($templateHTML);

        $templateHTML = str_replace('background-color: colorCode', 'background-color: #', $templateHTML);
        
        return $templateHTML;
    }
    
    public function repeatHeaderFooterPreview() {
        $this->view->html = Input::post('html');
        $this->view->headerTemp = Input::post('headerTemp');
        $this->view->footerTemp = Input::post('footerTemp');
        $this->view->top = Input::post('top');
        $this->view->left = Input::post('left');
        $this->view->bottom = Input::post('bottom');
        $this->view->right = Input::post('right');
        
        $htmlString = $this->view->renderPrint('header-footer-raw-template', 'middleware/views/template/');
        
        file_put_contents(BASEPATH.'log/temp-header-footer-raw-template.log', $htmlString); exit;
    }  
    
    public function tempRepeatHeaderFooterPreview() {
        echo @file_get_contents(BASEPATH.'log/temp-header-footer-raw-template.log');
    }  
    
    public function generateReportTemplate() {
        
        if (Input::isEmptyGet('templateMetaId') == true && Input::isEmptyGet('dataRow') == true) {
            echo 'Oroltiin parameter dutuu baina. (templateMetaId, dataRow)'; exit;
        }
        
        $templateMetaId = Input::get('templateMetaId');
        $metaDataId     = Input::get('srcMetaDataId');
        $dataRow        = Input::get('dataRow');
        
        if (!array_key_exists(0, $dataRow)) {
            $dataRow = array($dataRow);
        }
        
        $isTemplateMetaId = true;
        $templates[] = $templateMetaId;
        $pageMarginLeft = $pageMarginRight = '0';
        
        $array = array();
        $i = 0;
        
        loadBarCodeImageData();
        loadPhpQuery();
        
        foreach ($templates as $templateId) {
            
            $rtRow = $this->model->getDataModelByTemplate($templateId, $isTemplateMetaId);
            
            $paging = false;
            
            if (!empty($rtRow['PAGING_CONFIG'])) {
                $pagingConfig = $rtRow['PAGING_CONFIG'];
                $paging = true;
            }
            
            $dataModelId = $rtRow['DATA_MODEL_ID'];
            
            if ($rtRow['GET_MODE'] == 'consolidate') {
                
                $dataElement = $this->model->getRowConsolidateDataDtl($dataModelId, $dataRow, $metaDataId, $rtRow['META_DATA_ID']);
                
                if ($dataElement) {
                    array_push($array, self::renderTemplate($dataElement, $templateId, $isTemplateMetaId, $dataModelId)); 
                } else {
                    array_push($array, 'Consolidate get функц ажилласангүй!');
                }
                
            } else {
                
                if (isset($print_options['queryStrCriteria'])) {
                    $_POST['queryStrCriteria'] = Input::param($print_options['queryStrCriteria']);
                }
                
                foreach ($dataRow as $row) {
                    
                    if ($lastModifiedHtmlData = $this->model->getLastModifiedHtmlData($row)) {
                        array_push($array, $lastModifiedHtmlData);
                        
                        if ($rtRow['PAGE_MARGIN_LEFT']) {
                            $pageMarginLeft = $rtRow['PAGE_MARGIN_LEFT'];
                        }
                        if ($rtRow['PAGE_MARGIN_RIGHT']) {
                            $pageMarginRight = $rtRow['PAGE_MARGIN_RIGHT'];
                        }
        
                        continue;
                    }
                    
                    $dataElement = $this->model->getRowDataDtl($dataModelId, $row, $metaDataId, $rtRow['META_DATA_ID']);
                    
                    if ($dataElement) {
                        
                        if ($paging) {
                            
                            $pagingConfigArr = explode('|', $pagingConfig);
                            $pagingConfigGroup = strtolower($pagingConfigArr[0]);
                            $pagingConfigSize = $pagingConfigArr[1];
                            
                            $dataElementGroup = $dataElement[$pagingConfigGroup];
                            $dataGroupCount = count($dataElementGroup);
                            
                            if ($pagingConfigSize < $dataGroupCount) {
                                
                                unset($dataElement[$pagingConfigGroup]);
                                
                                $numPages = ceil($dataGroupCount / $pagingConfigSize);
                                $start = 0;
                                
                                for ($p = 1; $p <= $numPages; $p++) {
                                    
                                    $dataElementLimited = array_slice($dataElementGroup, $start, $pagingConfigSize);
                                    
                                    $dataElement[$pagingConfigGroup] = $dataElementLimited;
                                    
                                    $renderTemplate = self::renderTemplate($dataElement, $templateId, $isTemplateMetaId, $dataModelId);
                                    array_push($array, $renderTemplate);
                            
                                    $start = $start + $pagingConfigSize;
                                }
                                
                            } else {
                                $renderTemplate = self::renderTemplate($dataElement, $templateId, $isTemplateMetaId, $dataModelId);
                                array_push($array, $renderTemplate);
                            }
                            
                        } else {
                            $renderTemplate = self::renderTemplate($dataElement, $templateId, $isTemplateMetaId, $dataModelId);

                            if ($rtRow['UI_EXPRESSION']) {

                                $UIExpressionEval = Mdexpression::reportTemplateUIExpression($rtRow['UI_EXPRESSION']);

                                $domHtml = phpQuery::newDocument($renderTemplate);

                                eval($UIExpressionEval);

                                $renderTemplate = $domHtml;
                            }
                                
                            array_push($array, $renderTemplate);
                        }
                        
                    } else {
                        array_push($array, 'Get функц ажилласангүй!');
                    }
                }
            }
            $i++;
        }
        
        $this->view->contentHtml = '';
        $this->view->pageMarginRight = $pageMarginRight;
        $this->view->pageMarginLeft = $pageMarginLeft;
        
        foreach ($array as $value) {
            $this->view->contentHtml .= $value . '<br />';
        }
        
        $this->view->render('pagesource', $this->viewPath); exit;
    }
    
    public function toArchiveWfm() {
        
        Session::init();
        
        includeLib('PDF/Pdf');
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $params      = Input::post('params');
        $contentName = Input::param($params['archiveName']);
        $directoryId = Input::param($params['defaultDirectoryId']);
        $fileType    = Input::param(issetDefaultVal($params['fileType'], 'pdf'));
        $orientation = Input::post('orientation');
        $size        = Input::post('size');
        
        $site_url    = defined('LOCAL_URL') ? LOCAL_URL : URL;
        $htmlContent = Input::postNonTags('content');
        $htmlContent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $htmlContent);
        $htmlContent = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $htmlContent);        
        $htmlContent = preg_replace('/(<img.*?src=")(?!http|data:image\/)(.*">)/', "$1$site_url/$2", $htmlContent);
        $htmlContent = str_replace('  ', '<span style="display: inline-block; width: 30px;"></span>', $htmlContent);
        $htmlContent = str_replace(array('<nobr>', '</nobr>'), '', $htmlContent);
        
        preg_match_all('/([A-Za-zА-Яа-яӨҮөү0-9])(&nbsp;)([A-Za-zА-Яа-яӨҮөү0-9])/u', $htmlContent, $replaceMatches);
        
        if (isset($replaceMatches[0][0])) {
            foreach ($replaceMatches[0] as $replaceMatch) {
                $htmlContent = str_replace($replaceMatch, str_replace('&nbsp;', ' ', $replaceMatch), $htmlContent);
            }
        }
        
        $qrCode      = null;
        $fileToSave  = UPLOADPATH.Mdwebservice::$uploadedPath.'file_'.getUID();
        
        $css = '<style type="text/css">';
            $css .= self::printCss('return');
        $css .= '</style>';
        
        if (issetParam($params['signature_print']) == '1' && issetParam($params['wfmisneedsign']) == '5' && isset($params['signature_print_param'])) {
            
            $htmlContent = str_replace('  ', '<span style="display: inline-block; width: 30px;"></span>', $htmlContent);
            
            $this->load->model('mddoc', 'middleware/models/');
            $response = $this->model->digitalSignatureWritePdfModel($css, $htmlContent, $fileToSave, $params['signature_print_param']);
            
            if ($response['status'] == 'success') {
                $qrCode = $response['qrcode'];
                $fileToSave = $response['fileUrl'];
                $htmlContent = $response['htmlContent'];
                
                if (strpos($contentName, '.pdf') === false) {
                    $contentName .= '.pdf';
                }
            } else {
                echo json_encode($response); exit;
            }
            
        } else {
            
            if ($fileType == 'pdf') {
                
                $_POST['isIgnoreFooter'] = 1;
                $_POST['isSmartShrinking'] = 1;

                $pdfHtmlContent = str_replace('  ', '<span style="display: inline-block; width: 30px;"></span>', $htmlContent);

                $pdf = Pdf::createSnappyPdf(($orientation == 'portrait' ? 'Portrait' : 'Landscape'), ($size != 'custom' ? $size : 'letter'));
                Pdf::generateFromHtml($pdf, $css . $pdfHtmlContent, $fileToSave);

                $fileToSave = $fileToSave.'.pdf';
            }
        }
        
        if (file_exists(BASEPATH.$fileToSave)) {
            
            $this->load->model('mddoc', 'middleware/models/');
            
            $_POST['htmlContent'] = $htmlContent;
            $_POST['setWfmStatusParams'] = 1;
            $_POST['dataViewId'] = $params['metaDataId'];
            $_POST['recordId'] = $params['recordId'];
            $_POST['dataRow'] = $params;
            
            $response = $this->model->toArchiveSaveModel($contentName, $directoryId, $fileToSave, 'pdf', $qrCode);
                    
        } else {
            $response = array('status' => 'error', 'message' => 'File write error');
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE); 
    }
    
    public function saveEcmContentReportTemplateToFile() {
        
        try {
            $processMetaDataId = Input::numeric('processMetaDataId');
            
            if (!$processMetaDataId) {
                throw new Exception('Invalid processMetaDataId!'); 
            }
            
            $this->load->model('mdwebservice', 'middleware/models/');
            $bpRow = $this->model->getMethodIdByMetaDataModel($processMetaDataId);
            
            if (!$bpRow) {
                throw new Exception('No process config!'); 
            }
            
            $refStructureId = $bpRow['REF_META_GROUP_ID'];
            
            if (!$refStructureId) {
                throw new Exception('No structure!'); 
            }
            
            $selectedRow = Input::post('selectedRow');
            $recordId = issetParam($selectedRow['id']);
            
            if (!$recordId) {
                throw new Exception('No recordId!'); 
            }
            
            $_POST['ignoreSetWfmStatusParams'] = 1;
            $_POST['refStructureId'] = $refStructureId;
            
            self::toArchiveWfm(); exit;
            
        } catch (Exception $ex) {
            $response = ['status' => 'error', 'message' => $ex->getMessage()];
        }
        
        convJson($response);
    }
    
    public static function clearPdfTempFile() {
        
        $cacheTmpDir = Mdcommon::getCacheDirectory();
        $cacheDir    = $cacheTmpDir . '/report_template_pdf/';        
        
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777);
        } else {

            $currentHour = (int) Date::currentDate('H');

            /* Оройны 17 цагаас 19 цагийн хооронд шалгаж өмнө нь үүссэн файлуудыг устгана */
            if ($currentHour >= 17 && $currentHour <= 19) { 

                $files = glob($cacheDir.'/*');
                $now   = time();
                $day   = 0.5;

                foreach ($files as $file) {
                    if (is_file($file) && ($now - filemtime($file) >= 60 * 60 * 24 * $day)) {
                        @unlink($file);
                    } 
                }
            }
        }
        
        return $cacheDir;
    }
    
    public function setReportExpression() {
        
        Auth::handleLogin();
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $rtRow = $this->model->getDataModelByTemplate($this->view->metaDataId, true);
        $this->view->expression = $rtRow['UI_EXPRESSION'];
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        $this->view->metaDatas = $this->model->getOnlyMetaDataByGroupModel(Input::post('metaGroupId'));       

        $response = array(
            'html' => $this->view->renderPrint('system/link/reportTemplate/uiExpression', 'middleware/views/metadata/'),
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }    
    
    public function getTemplateByArguments($templateId, $dataViewId, $rowData) {
        
        $this->load->model('mdtemplate', 'middleware/models/');
        
        loadBarCodeImageData();
        loadPhpQuery();
        
        self::$rtRow = $this->model->getDataModelByTemplate($templateId, true);
        
        $dataModelId = self::$rtRow['DATA_MODEL_ID'];
        $rowData = Arr::changeKeyLower($rowData);
        
        if ($dataViewId == 'selfDvId') {
            $dataViewId = self::$rtRow['DATA_MODEL_ID'];
        }
        
        $dataElement = $this->model->getRowDataDtl($dataModelId, $rowData, $dataViewId, self::$rtRow['META_DATA_ID']);
        
        $renderTemplate = self::renderTemplate($dataElement, $templateId, true, $dataModelId);

        if (self::$rtRow['UI_EXPRESSION']) {

            $UIExpressionEval = Mdexpression::reportTemplateUIExpression(self::$rtRow['UI_EXPRESSION']);

            $domHtml = phpQuery::newDocument($renderTemplate);

            eval($UIExpressionEval);

            $renderTemplate = $domHtml;
        }
        
        return $renderTemplate;
    }
    
    public function printTemplateByPost() {
        
        $templateId = Input::numeric('rtMetaId');
        $dataViewId = Input::numeric('dataViewId');
        $rowData    = Input::post('rowData');
        
        $template = $this->getTemplateByArguments($templateId, $dataViewId, $rowData);
        
        if ($template) {

            $_POST['orientation'] = 'portrait';
            $_POST['size'] = 'a4';
            $_POST['top'] = issetDefaultVal(self::$rtRow['PAGE_MARGIN_TOP'], '0.5cm');
            $_POST['bottom'] = issetDefaultVal(self::$rtRow['PAGE_MARGIN_BOTTOM'], '0.5cm');
            $_POST['left'] = issetDefaultVal(self::$rtRow['PAGE_MARGIN_LEFT'], '0.5cm');
            $_POST['right'] = issetDefaultVal(self::$rtRow['PAGE_MARGIN_RIGHT'], '0.5cm');
        
            $response = array('status' => 'success', 'css' => $this->printCss('return'), 'html' => $template);
        } else {
            $response = array('status' => 'error', 'message' => 'Empty!');
        }
        
        jsonResponse($response);
    }
    
    public function changeUserPrintOption() {
        Auth::handleLogin();
        $response = $this->model->changeUserPrintOptionModel();
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function getTemplateByRowData() {
        
        $rowData = Input::post('rowData');
        
        $_POST['dataRow'] = array($rowData);
        $_POST['isProcess'] = 'false';

        $templateArr = self::checkCriteria(true);
        
        if (isset($templateArr[0]['ID'])) {
            
            $_POST['print_options'] = array(
                'numberOfCopies' => 1, 
                'isPrintNewPage' => 1,
                'isSettingsDialog' => 0,
                'isShowPreview' => 1,
                'isPrintPageBottom' => 0,
                'isPrintPageRight' => 0,
                'pageOrientation' => 'portrait',
                'isPrintSaveTemplate' => 0,
                'paperInput' => 'portrait',
                'pageSize' => 'a4',
                'printType' => '1col', 
                'templates' => array($templateArr[0]['ID']), 
                'templateIds' => $templateArr[0]['ID'], 
                'templateMetaIds' => $templateArr[0]['TEMPLATE_META_DATA_ID']
            );
            
            self::printOption();
            
        } else {
            $response = array('status' => 'error', 'message' => 'Темплэйт олдсонгүй!');
        }
        
        echo json_encode($response); exit;
    }

    public static function printKpiForm($html) {
        if (strpos($html, 'printKpiForm(') !== false) {
            preg_match_all('/printKpiForm\((.*?)\)/i', $html, $htmlKpiForms);
            
            if (count($htmlKpiForms[0]) > 0) {
                
                foreach ($htmlKpiForms[1] as $ek => $ev) {
                    
                    if (strpos($ev, ',') !== false) {
                        $evArr = explode(',', $ev);
                        
                        if (count($evArr) >= 2) {
                            
                            $templateId = trim(strip_tags($evArr[0]));
                            $bookId = trim(strip_tags($evArr[1]));
                            $getCode = trim(strip_tags(issetParam($evArr[2])));
                            
                            $returnKpiForm = (new Mdform())->returnKpiForm($templateId, $bookId, $getCode);
                        
                            $html = str_replace($htmlKpiForms[0][$ek], $returnKpiForm, $html);
                        }
                    } 
                }
            }
        }
        
        return $html;
    }    
    
    public function renderChartReplacer($html) {
        if (strpos($html, 'renderChart(') !== false) {
            preg_match_all('/renderChart\((.*?)\)/i', $html, $htmlCharts);
            
            if (count($htmlCharts[0]) > 0) {
                
                foreach ($htmlCharts[1] as $ek => $ev) {
                    
                    if (strpos($ev, ',') !== false) {
                        $ev = str_replace("'", '', $ev);
                        $evArr = explode(',', $ev);
                        
                        if (count($evArr) == 3) {
                            
                            $metaCode = trim(strip_tags($evArr[0]));
                            $criteria = trim(strip_tags($evArr[1]));
                            $style = trim(strip_tags(issetParam($evArr[2])));

                            $this->load->model('mdmetadata', 'middleware/models/');        
                            $metaRow = $this->model->getMetaDataByCodeModel($metaCode);

                            if ($metaRow) {
                                $this->load->model('mddashboard', 'middleware/models/');        
                                $diagram = $this->model->getMetaDiagramLinkModel($metaRow['META_DATA_ID']);
                            }

                            $formatCriteria = "";
                            parse_str($criteria, $defaultCriteriaData);
                            if (is_array($defaultCriteriaData)) {
                                foreach ($defaultCriteriaData as $key => $row) {
                                    $formatCriteria .= "param[".$key."]=".$row."&";
                                }
                                $formatCriteria = rtrim($formatCriteria, "&");
                            }
                        
                            $html = str_replace($htmlCharts[0][$ek], '<div data-chartmetacode="'.$metaCode.'" data-chartmetaid="'.issetParam($metaRow['META_DATA_ID']).'" class="reporttemplate-chart-'.issetParam($metaRow['META_DATA_ID']).'" data-charttype="'.issetParam($diagram['DIAGRAM_TYPE']).'" data-criteria="'.$formatCriteria.'" style="'.$style.'"></div>', $html);
                        }
                    } 
                }
            }
        }
        
        return $html;
    }
    
    public function printTemplateByResponse() {
        
        loadBarCodeImageData();
        loadPhpQuery();
        
        $metaDataCode = Input::post('metaDataCode');
        $metaDataId = Input::numeric('metaDataId');
        
        $ml = &getInstance();
        $ml->load->model('mdmetadata', 'middleware/models/');    
        
        if ($metaDataId) {
            $metaRow = $ml->model->getMetaDataModel($metaDataId);
        } else {
            $metaRow = $ml->model->getMetaDataByCodeModel($metaDataCode);
        }
        
        if ($metaRow) {
            
            $dataElement = Input::post('responseData');

            if ($dataElement) {
                
                $templateId = $metaRow['META_DATA_ID'];
            
                $this->load->model('mdtemplate', 'middleware/models/');

                self::$rtRow = $this->model->getDataModelByTemplate($templateId, true);

                $dataModelId = self::$rtRow['DATA_MODEL_ID'];

                $renderTemplate = self::renderTemplate($dataElement, $templateId, true, $dataModelId);

                if (self::$rtRow['UI_EXPRESSION']) {

                    $UIExpressionEval = Mdexpression::reportTemplateUIExpression(self::$rtRow['UI_EXPRESSION']);

                    $domHtml = phpQuery::newDocument($renderTemplate);

                    eval($UIExpressionEval);

                    $renderTemplate = $domHtml;
                }
                
                $responseData = $_POST['responseData'];
                
                unset($_POST);
                
                $_POST['orientation'] = 'portrait';
                $_POST['size'] = checkDefaultVal($responseData['printpapersize'], 'a4'); 
                $_POST['top'] = (self::$rtRow['PAGE_MARGIN_TOP'] != '') ? self::$rtRow['PAGE_MARGIN_TOP'] : '';
                $_POST['bottom'] = (self::$rtRow['PAGE_MARGIN_BOTTOM'] != '') ? self::$rtRow['PAGE_MARGIN_BOTTOM'] : '';
                $_POST['left'] = (self::$rtRow['PAGE_MARGIN_LEFT'] != '') ? self::$rtRow['PAGE_MARGIN_LEFT'] : '';
                $_POST['right'] = (self::$rtRow['PAGE_MARGIN_RIGHT'] != '') ? self::$rtRow['PAGE_MARGIN_RIGHT'] : '';
            
                $response = array('status' => 'success', 'printData' => $renderTemplate, 'css' => $this->printCss('return'));
                
                $headerFooter = self::renderHeaderFooterTemplate($templateId, true, $dataElement);
                
                if ($headerFooter['header'] != '' || $headerFooter['footer'] != '') {
                    
                    $printIsHeader = array_key_exists('printisheader', $responseData) ? $responseData['printisheader'] : '1';
                    $printIsFooter = array_key_exists('printisfooter', $responseData) ? $responseData['printisfooter'] : '1';
                    
                    $search  = array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s');
                    $replace = array('>',            '<',            '\\1');
                    
                    $headerFooter['header'] = preg_replace($search, $replace, $headerFooter['header']);
                    $headerFooter['footer'] = preg_replace($search, $replace, $headerFooter['footer']); 
    
                    $_POST['content'] = $renderTemplate;
                    
                    if ($printIsHeader == '1') {
                        $_POST['headerHtml'] = $headerFooter['header'];
                    }
                    
                    if ($printIsFooter == '1') {
                        $_POST['footerHtml'] = $headerFooter['footer'];
                    }
                    
                    $this->pdfToTemp();
                }
                
            } else {
                $response = array('status' => 'error', 'message' => 'No data!');
            }
            
        } else {
            $response = array('status' => 'error', 'message' => $metaDataCode . ' кодтой үзүүлэлт олдсонгүй!');
        }
        
        jsonResponse($response);
    }
    
    public function previewTempDataFromBp() {
        
        $_POST['responseType'] = 'returnRequestParams';
        
        $param = (new Mdwebservice())->runProcess();
        
        $processResponseParam = Arr::changeKeyLower($param);
        
        if (Input::postCheck('kpiParam')) {
            $processResponseParam = array_merge($processResponseParam, Input::post('kpiParam'));
        }
        
        $_POST['responseData'] = $processResponseParam;
        
        $this->printTemplateByResponse();
    }
    
    public function getReportTemplateHtml() {
        
        $metaDataId = Input::numeric('processId');
        $templateMetaId = Input::numeric('templateMetaId');
        $pageSize = Input::post('pageSize');
        $pageOrientation = Input::post('pageOrientation');
        
        parse_str(urldecode($_POST['qryStr']), $dataRow);
        
        $_POST['metaDataId'] = $metaDataId;
        $_POST['dataRow'] = [$dataRow];
        $_POST['isProcess'] = 'false';
        $_POST['responseType'] = 'outputArray';

        $_POST['print_options'] = [
            'numberOfCopies' => 1, 
            'isPrintNewPage' => 1,
            'isSettingsDialog' => 0,
            'isShowPreview' => 1,
            'isPrintPageBottom' => 0,
            'isPrintPageRight' => 0,
            'pageOrientation' => $pageOrientation,
            'isPrintSaveTemplate' => 0,
            'paperInput' => $pageOrientation,
            'pageSize' => strtolower($pageSize ? $pageSize : 'a4'),
            'printType' => '1col', 
            'templateMetaId' => $templateMetaId
        ];

        $reportTemplate = (new Mdtemplate())->printOption();

        echo $reportTemplate['Html'];
    }
    
}
