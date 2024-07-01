<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdexpression Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Expression
 * @author	B.Och-Erdene <ocherdene@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdexpression
 */
class Mdexpression extends Controller {

    public static $mainSelector = 'bp_window_';
    public static $searchMainSelector = 'dv_search_';
    public static $cachePrefix = '$cacheExpRow';
    public static $cachePrefixHeader = '$cacheExpHdr';
    public static $isMultiPathConfig = false;
    public static $isFromMetaGroup = false;
    public static $multiPathConfig = array();
    public static $detectedFunctionNames = array();
    public static $flowData = array();
    public static $precisionScalePath = array();
    public static $enableDisable = array();
    public static $setMainSelector = null;
    public static $setSubMainSelector = null;
    public static $kpiExpresssionPrefix = null;
    public static $sensorBracket = '';
    public static $flowCodeString = '';

    public function __construct() {
        parent::__construct();
    }

    public static function searchGenerateScripts($dataViewId) {
        return array('scripts' => null);
    }

    public function setMultiPathConfig($processId) {
        $this->load->model('mdexpression', 'middleware/models/');

        $result = $this->model->setMultiPathConfigModel($processId);

        self::$isMultiPathConfig = true;
        self::$multiPathConfig = $result;

        return;
    }

    public function startReplaceResolver($fullExpression) {
        $fullExpression = str_replace('х', 'smllxlttr', $fullExpression);
        return $fullExpression;
    }

    public function endReplaceResolver($fullExpression) {
        $fullExpression = str_replace('smllxlttr', 'х', $fullExpression);
        $fullExpression = str_replace('feoperator_01', '=', $fullExpression);
        return $fullExpression;
    }

    public function fullExpressionConvertGet($getMetaTypeCode, $getMetaRow, $mainSelector, $sidebarExpression = '', $search, $replace, $exp, $processActionType = '') {

        if ($processActionType == 'view') {

            if ($getMetaRow['parentId'] == '' && $getMetaRow['isShow'] != '1') {
                $exp = str_replace($search, 'getBpHdrHiddenVal(' . $mainSelector . ', \'' . $replace . '\')', $exp);
            } elseif ($getMetaRow['parentId'] == '' && $getMetaRow['isShow'] == '1') {
                $exp = str_replace($search, 'getBpRowParamViewVal(' . $mainSelector . ', \'open\', \'' . $replace . '\')', $exp);
            } elseif ($getMetaTypeCode == 'number' || $getMetaTypeCode == 'bigdecimal' || $getMetaTypeCode == 'integer') {
                $exp = str_replace($search, 'getBpRowParamViewNum(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
            } else if ($getMetaRow['parentId'] == '' && $getMetaRow['isShow'] == '1' && $getMetaTypeCode == 'qrcode') {
                $exp = str_replace($search, 'getBpHdrHiddenVal(' . $mainSelector . ', \'' . $replace . '\')', $exp);
            } else {
                $exp = str_replace($search, 'getBpRowParamViewVal(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
            }

            return $exp;
        }

        if ($getMetaTypeCode == 'number' || $getMetaTypeCode == 'bigdecimal' || $getMetaTypeCode == 'integer') {

            if ($getMetaRow['parentId'] != '' && empty($sidebarExpression)) {
                if ($getMetaRow['isShow'] == '1' && $getMetaTypeCode == 'bigdecimal') {
                    $exp = str_replace($search, 'getBpRowParamBigdecimal(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
                } elseif ($getMetaRow['isShow'] == '1' && ($getMetaTypeCode == 'integer' || $getMetaTypeCode == 'number' || $getMetaTypeCode == 'decimal')) {
                    $exp = str_replace($search, 'getBpRowParamInteger(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
                } else {
                    $exp = str_replace($search, 'getBpRowParamNum(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
                }
            } elseif (!empty($sidebarExpression)) {

                $exp = str_replace($search, 'getBpRowParamNumSidebar(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
            } elseif ($getMetaRow['isShow'] == '1') {

                if (($getMetaRow['LOOKUP_TYPE'] === 'combo' || $getMetaRow['LOOKUP_TYPE'] === 'combo_with_popup') && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, '$("select[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'popup' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, '$("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'radio' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, 'getBpHdrRadioParam(' . $mainSelector . ', \'' . $replace . '\')', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'range_slider' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, 'getBpHdrRangeSliderParam(' . $mainSelector . ', \'' . $replace . '\')', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'checkbox' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, 'getBpHdrMultiCheckboxParam(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'star' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, 'Number($("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val())', $exp);
                } elseif ($getMetaTypeCode == 'bigdecimal') {
                    $exp = str_replace($search, 'getBpHdrParamNum(' . $mainSelector . ', \'' . $replace . '\')', $exp);
                } elseif ($getMetaTypeCode == 'integer') {
                    $exp = str_replace($search, 'Number($("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val())', $exp);
                } else {
                    $exp = str_replace($search, 'Number($("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').autoNumeric("get"))', $exp);
                }
            } else {
                $exp = str_replace($search, 'Number($("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val())', $exp);
            }
        } elseif ($getMetaTypeCode == 'long') {

            if ($getMetaRow['parentId'] != '' && empty($sidebarExpression)) {

                if ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_TYPE'] === 'radio' && $getMetaRow['LOOKUP_META_DATA_ID']) {
                    $exp = str_replace($search, 'getBpDtlRadioParam(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
                } else {
                    $exp = str_replace($search, 'getBpRowParamNum(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
                }
            } elseif (!empty($sidebarExpression)) {

                $exp = str_replace($search, 'getBpRowParamNumSidebar(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
            } elseif ($getMetaRow['isShow'] == '1') {

                if (($getMetaRow['LOOKUP_TYPE'] === 'combo' || $getMetaRow['LOOKUP_TYPE'] === 'combo_with_popup') && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, '$("select[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'popup' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, '$("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'radio' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, 'getBpHdrRadioParam(' . $mainSelector . ', \'' . $replace . '\')', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'range_slider' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, 'getBpHdrRangeSliderParam(' . $mainSelector . ', \'' . $replace . '\')', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'checkbox' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, 'getBpHdrMultiCheckboxParam(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'star' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, 'Number($("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val())', $exp);
                } else {
                    $exp = str_replace($search, 'Number($("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val())', $exp);
                }
            } else {
                $exp = str_replace($search, 'Number($("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val())', $exp);
            }
        } elseif (
            $getMetaTypeCode == 'text'
            || $getMetaTypeCode == 'string'
            || $getMetaTypeCode == 'code'
            || $getMetaTypeCode == 'name'
            || $getMetaTypeCode == 'group'
            || $getMetaTypeCode == 'datetime'
            || $getMetaTypeCode == 'date'
            || $getMetaTypeCode == 'time'
        ) {

            if ($getMetaRow['parentId'] != '' && empty($sidebarExpression)) {

                if ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_TYPE'] === 'radio' && $getMetaRow['LOOKUP_META_DATA_ID']) {
                    $exp = str_replace($search, 'getBpDtlRadioParam(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
                } else {
                    $exp = str_replace($search, 'getBpRowParamVal(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
                }
            } elseif (!empty($sidebarExpression)) {

                $exp = str_replace($search, 'getBpRowParamNumSidebar(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
            } elseif ($getMetaRow['isShow'] == '1') {
                if (($getMetaRow['LOOKUP_TYPE'] === 'combo' || $getMetaRow['LOOKUP_TYPE'] === 'combo_with_popup') && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, '$("select[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'popup' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, '$("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'radio' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, 'getBpHdrRadioParam(' . $mainSelector . ', \'' . $replace . '\')', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'range_slider' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, 'getBpHdrRangeSliderParam(' . $mainSelector . ', \'' . $replace . '\')', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'checkbox' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, 'getBpHdrMultiCheckboxParam(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'star' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, '$("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
                } else {
                    $exp = str_replace($search, '$("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
                }
            } else {
                $exp = str_replace($search, '$("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
            }
        } elseif ($getMetaTypeCode == 'description' || $getMetaTypeCode == 'description_auto' || $getMetaTypeCode == 'textarea' || $getMetaTypeCode == 'text_editor' || $getMetaTypeCode == 'rule_expression') {

            if ($getMetaRow['parentId'] != '' && empty($sidebarExpression)) {

                $exp = str_replace($search, 'getBpRowParamVal(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
            } elseif (!empty($sidebarExpression)) {

                $exp = str_replace($search, 'getBpRowParamNumSidebar(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
            } elseif ($getMetaRow['isShow'] == '1') {

                if (($getMetaRow['LOOKUP_TYPE'] === 'combo' || $getMetaRow['LOOKUP_TYPE'] === 'combo_with_popup') && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, '$("select[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
                } elseif ($getMetaTypeCode == 'text_editor') {
                    $exp = str_replace($search, '(tinymce.editors.length ? tinyMCE.get(\'param[' . $replace . ']\').getContent({format : \'raw\'}) : \'\')', $exp);
                } else {
                    $exp = str_replace($search, '$("textarea[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
                }
            } else {
                $exp = str_replace($search, '$("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
            }
        } elseif ($getMetaTypeCode == 'label') {

            if ($getMetaRow['parentId'] != '' && empty($sidebarExpression)) {

                $exp = str_replace($search, 'getBpRowParamVal(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
            } elseif (!empty($sidebarExpression)) {

                $exp = str_replace($search, 'getBpRowParamNumSidebar(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
            } elseif ($getMetaRow['isShow'] == '1') {

                $exp = str_replace($search, '$("[data-path=\'' . $replace . '\']", ' . $mainSelector . ').text()', $exp);
            } else {
                $exp = str_replace($search, '$("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
            }
        } elseif ($getMetaTypeCode == 'boolean' && $getMetaRow['isShow'] == '1') {

            if ($getMetaRow['parentId'] != '') {
                $exp = str_replace($search, 'getBpRowParamCheckBox(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
            } else {
                $exp = str_replace($search, '$("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').is(":checked")', $exp);
            }
        } else {
            if ($getMetaRow['parentId'] != '') {
                $exp = str_replace($search, 'getBpRowParamVal(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
            } else {
                $exp = str_replace($search, '$("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
            }
        }

        return $exp;
    }

    public function fullExpressionWithoutEventConvertGet($getMetaTypeCode, $getMetaRow, $mainSelector, $sidebarExpression = '', $search, $replace, $exp, $processActionType = '') {

        if ($processActionType == 'view') {

            if ($getMetaRow['parentId'] == '' && $getMetaRow['isShow'] != '1') {
                $exp = str_replace($search, 'getBpHdrHiddenVal(' . $mainSelector . ', \'' . $replace . '\')', $exp);
            } elseif ($getMetaTypeCode == 'number' || $getMetaTypeCode == 'bigdecimal' || $getMetaTypeCode == 'integer') {
                $exp = str_replace($search, 'getBpRowParamViewNum(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $replace . '\')', $exp);
            } else if ($getMetaRow['parentId'] == '' && $getMetaRow['isShow'] == '1' && $getMetaTypeCode == 'qrcode') {
                $exp = str_replace($search, 'getBpHdrHiddenVal(' . $mainSelector . ', \'' . $replace . '\')', $exp);
            } else {
                $exp = str_replace($search, 'getBpRowParamViewVal(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $replace . '\')', $exp);
            }

            return $exp;
        }

        if ($getMetaTypeCode == 'number' || $getMetaTypeCode == 'bigdecimal' || $getMetaTypeCode == 'integer') {

            if ($getMetaRow['parentId'] != '' && empty($sidebarExpression)) {
                if ($getMetaRow['isShow'] == '1' && $getMetaTypeCode == 'bigdecimal') {
                    $exp = str_replace($search, 'getBpRowParamBigdecimal(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $replace . '\')', $exp);
                } elseif ($getMetaRow['isShow'] == '1' && ($getMetaTypeCode == 'integer' || $getMetaTypeCode == 'number' || $getMetaTypeCode == 'decimal')) {
                    $exp = str_replace($search, 'getBpRowParamInteger(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $replace . '\')', $exp);
                } else {
                    $exp = str_replace($search, 'getBpRowParamNum(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $replace . '\')', $exp);
                }
            } elseif (!empty($sidebarExpression)) {

                $exp = str_replace($search, 'getBpRowParamNumSidebar(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $replace . '\')', $exp);
            } elseif ($getMetaRow['isShow'] == '1') {

                if (($getMetaRow['LOOKUP_TYPE'] === 'combo' || $getMetaRow['LOOKUP_TYPE'] === 'combo_with_popup') && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, '$("select[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'popup' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, '$("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'checkbox' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, 'getBpHdrMultiCheckboxParam(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $replace . '\')', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'star' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, 'Number($("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val())', $exp);
                } elseif ($getMetaTypeCode == 'bigdecimal') {
                    $exp = str_replace($search, 'getBpHdrParamNum(' . $mainSelector . ', \'' . $replace . '\')', $exp);
                } elseif ($getMetaTypeCode == 'integer') {
                    $exp = str_replace($search, 'Number($("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val())', $exp);
                } else {
                    $exp = str_replace($search, 'Number($("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').autoNumeric("get"))', $exp);
                }
            } else {
                $exp = str_replace($search, 'Number($("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val())', $exp);
            }
        } elseif ($getMetaTypeCode == 'long') {

            if ($getMetaRow['parentId'] != '' && empty($sidebarExpression)) {

                if ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_TYPE'] === 'radio' && $getMetaRow['LOOKUP_META_DATA_ID']) {
                    $exp = str_replace($search, 'getBpDtlRadioParam(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $replace . '\')', $exp);
                } else {
                    $exp = str_replace($search, 'getBpRowParamNum(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $replace . '\')', $exp);
                }
            } elseif (!empty($sidebarExpression)) {

                $exp = str_replace($search, 'getBpRowParamNumSidebar(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $replace . '\')', $exp);
            } elseif ($getMetaRow['isShow'] == '1') {

                if (($getMetaRow['LOOKUP_TYPE'] === 'combo' || $getMetaRow['LOOKUP_TYPE'] === 'combo_with_popup') && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, '$("select[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'radio' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, 'getBpHdrRadioParam(' . $mainSelector . ', \'' . $replace . '\')', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'range_slider' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, 'getBpHdrRangeSliderParam(' . $mainSelector . ', \'' . $replace . '\')', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'checkbox' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, 'getBpHdrMultiCheckboxParam(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $replace . '\')', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'star' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, 'Number($("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val())', $exp);
                } else {
                    $exp = str_replace($search, '$("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
                }
            } else {
                $exp = str_replace($search, '$("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
            }
        } elseif (
            $getMetaTypeCode == 'text'
            || $getMetaTypeCode == 'string'
            || $getMetaTypeCode == 'code'
            || $getMetaTypeCode == 'name'
            || $getMetaTypeCode == 'group'
            || $getMetaTypeCode == 'datetime'
            || $getMetaTypeCode == 'date'
            || $getMetaTypeCode == 'time'
        ) {

            if ($getMetaRow['parentId'] != '' && empty($sidebarExpression)) {

                if ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_TYPE'] === 'radio' && $getMetaRow['LOOKUP_META_DATA_ID']) {
                    $exp = str_replace($search, 'getBpDtlRadioParam(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $replace . '\')', $exp);
                } else {
                    $exp = str_replace($search, 'getBpRowParamVal(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $replace . '\')', $exp);
                }
            } elseif (!empty($sidebarExpression)) {

                $exp = str_replace($search, 'getBpRowParamNumSidebar(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $replace . '\')', $exp);
            } elseif ($getMetaRow['isShow'] == '1') {

                if (($getMetaRow['LOOKUP_TYPE'] === 'combo' || $getMetaRow['LOOKUP_TYPE'] === 'combo_with_popup') && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, '$("select[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'radio' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, 'getBpHdrRadioParam(' . $mainSelector . ', \'' . $replace . '\')', $exp);
                } elseif ($getMetaRow['LOOKUP_TYPE'] === 'checkbox' && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, 'getBpHdrMultiCheckboxParam(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $replace . '\')', $exp);
                } else {
                    $exp = str_replace($search, '$("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
                }
            } else {
                $exp = str_replace($search, '$("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
            }
        } elseif ($getMetaTypeCode == 'textarea' || $getMetaTypeCode == 'description' || $getMetaTypeCode == 'description_auto' || $getMetaTypeCode == 'text_editor') {

            if ($getMetaRow['parentId'] != '' && empty($sidebarExpression)) {
                $exp = str_replace($search, 'getBpRowParamVal(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $replace . '\')', $exp);
            } elseif (!empty($sidebarExpression)) {
                $exp = str_replace($search, 'getBpRowParamNumSidebar(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $replace . '\')', $exp);
            } elseif ($getMetaRow['isShow'] == '1') {
                if (($getMetaRow['LOOKUP_TYPE'] === 'combo' || $getMetaRow['LOOKUP_TYPE'] === 'combo_with_popup') && !empty($getMetaRow['LOOKUP_META_DATA_ID'])) {
                    $exp = str_replace($search, '$("select[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
                } elseif ($getMetaRow['isShow'] == '1' && $getMetaTypeCode == 'text_editor') {
                    $exp = str_replace($search, '(tinymce.editors.length ? tinyMCE.get(\'param[' . $replace . ']\').getContent({format : \'raw\'}) : \'\')', $exp);
                } else {
                    $exp = str_replace($search, '$("textarea[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
                }
            } else {
                $exp = str_replace($search, '$("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
            }
        } elseif ($getMetaTypeCode == 'label') {

            if ($getMetaRow['parentId'] != '' && empty($sidebarExpression)) {

                $exp = str_replace($search, 'getBpRowParamVal(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
            } elseif (!empty($sidebarExpression)) {

                $exp = str_replace($search, 'getBpRowParamNumSidebar(' . $mainSelector . ', $(this), \'' . $replace . '\')', $exp);
            } elseif ($getMetaRow['isShow'] == '1') {

                $exp = str_replace($search, '$("[data-path=\'' . $replace . '\']", ' . $mainSelector . ').text()', $exp);
            } else {
                $exp = str_replace($search, '$("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
            }
        } elseif ($getMetaTypeCode == 'boolean' && $getMetaRow['isShow'] == '1') {

            if ($getMetaRow['parentId'] != '') {
                $exp = str_replace($search, 'getBpRowParamCheckBox(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $replace . '\')', $exp);
            } else {
                $exp = str_replace($search, '$("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').is(":checked")', $exp);
            }
        } else {

            if ($getMetaRow['parentId'] != '') {
                $exp = str_replace($search, 'getBpRowParamVal(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $replace . '\')', $exp);
            } else {
                $exp = str_replace($search, '$("input[data-path=\'' . $replace . '\']", ' . $mainSelector . ').val()', $exp);
            }
        }

        return $exp;
    }

    public function fullExpressionConvertEvent($fullExpression = '', $processId = '', $processActionType = '')
    {
        if (empty($fullExpression)) {
            return '';
        }

        if (self::$isMultiPathConfig == false) {
            self::setMultiPathConfig($processId);
        } else {
            $this->load->model('mdexpression', 'middleware/models/');
        }

        $mainSelector = self::$setMainSelector ? self::$setMainSelector : self::$mainSelector . $processId;

        $fullExpression = self::startReplaceResolver($fullExpression);

        if (Mdexpression::$detectedFunctionNames) {
            $fullExpression = self::detectedFunctionNamesReplacer($fullExpression);
        }

        $fullExpression = self::fullExpressionReplaceFncNames($processId, $mainSelector, $fullExpression);

        $result = array_filter(explode("};", $fullExpression));
        $expressionToJs = '';

        foreach ($result as $valRowExp) {

            preg_match('/\[(.*?)\]/', $valRowExp, $expEventCatch);
            preg_match_all('/(?<![a-zA-Z0-9\[+])\[[\w.]+\]\s*=\s*[\[|\(\'\w.]+(.*)/', $valRowExp, $parseExpressionEqual);
            preg_match_all('/\[(.*?)\].hide\(\)|\[(.*?)\].softhide\(\)|\[(.*?)\].show\(\)|\[(.*?)\].disable\(\)|\[(.*?)\].enable\(\)|\[(.*?)\].required\(\)|\[(.*?)\].nonrequired\(\)/', $fullExpression, $parseExpressionlAttr);
            preg_match_all('/\[[\w.]+\](.*)\s*(==|===|!=|!==|>|<|>=|<=)\s*[\[\w]*(.*)/', $fullExpression, $parseExpressionEqualEqual);
            preg_match_all('/message\((.*)+\)/', $valRowExp, $parseExpressionMessage);
            preg_match_all('/fiscalPeriodMessage\((.*)+\)/', $valRowExp, $parseExpressionFiscalPeriodMessage);
            preg_match_all('/\[[\w.]+\](.label|.control)\((.*)\)/', $valRowExp, $parseExpressionStyle);
            preg_match_all('/\[[\w.]+\].trigger\((.*)\)/', $valRowExp, $parseExpressionTrigger);
            preg_match_all('/\[[\w.]+\].rowTrigger\((.*)\)/', $valRowExp, $parseExpressionTriggerRow);
            preg_match_all('/\[[\w.]+\].focus\(\);/', $valRowExp, $parseExpressionFocus);

            // <editor-fold defaultstate="collapsed" desc="CONVERT JS EVENT">
            if (!empty($parseExpressionEqual[0])) {
                foreach ($parseExpressionEqual[0] as $kkk => $expVal) {
                    $expExplode = explode('=', $expVal);
                    preg_match('/\[(.*?)\]/', $expExplode[0], $expSet);
                    preg_match_all('/\[(.*?)\]/', $expExplode[1], $expGet);
                    $exp = trim(str_replace(';', '', $expExplode[1]));

                    $getMetaRow = $this->model->getMetaTypeCode($processId, $expSet[1]);
                    $typeCode = $getMetaRow['type'];
                    $isAcceptTranslate = false;
                    
                    if ($getMetaRow['IS_TRANSLATE'] == '1' && !empty($expGet[1]) && count($expGet[1]) == 1) {
                        $getMetaRowGet = $this->model->getMetaTypeCode($processId, $expGet[1][0]);
                        if ($getMetaRowGet['IS_TRANSLATE'] == '1') {
                            $getFieldValRemove = str_replace($expGet[0][0] . '.val()', '', $exp);
                            if (!$getFieldValRemove) {
                                $isAcceptTranslate = true;
                            }
                        }
                    }

                    $getReplace = $exp;

                    if (!empty($expGet[1]) && !$isAcceptTranslate) {
                        foreach ($expGet[1] as $key => $valGetPath) {
                            if (strpos($expExplode[1], '.val()') !== false) {
                                $getMetaRow2 = $this->model->getMetaTypeCode($processId, $valGetPath);
                                $exp = self::fullExpressionConvertGet($getMetaRow2['type'], $getMetaRow2, $mainSelector, $getMetaRow2['sidebarName'], $expGet[0][$key] . '.val()', $valGetPath, $exp, $processActionType);
                            }
                        }
                    }

                    $getReplaced = strtr($getReplace, array("(" => "\(", ")" => "\)", "|" => "\|", "'" => "\'", "*" => "\*", "[" => "\[", "]" => "\]", "/" => "\/", "+" => "\+", "=" => "\=", ":" => "\:", '$' => '\$', '?' => '\?'));

                    if ($isAcceptTranslate) {

                        $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpTranslateFieldVal(' . $mainSelector . ', $(this), \'' . $expSet[1] . '\', getBpElement(' . $mainSelector . ', $(this), \'' . $expGet[1][0] . '\'));', $valRowExp);

                        continue;
                    }

                    if ($getMetaRow['parentId'] != '') {
                        if ($typeCode == 'boolean') {
                            $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'checkboxCheckerUpdate(getBpElement(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSet[1] . '\'), ' . $getReplace . ')', $valRowExp);
                        } elseif ($typeCode == 'label') {
                            $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpRowParamLabel(' . $mainSelector . ', $(this), \'' . $expSet[1] . '\', (' . $exp . '));', $valRowExp);
                        } else {
                            if ($getMetaRow['sidebarName'] != '') {
                                if ($typeCode == 'bigdecimal' && $getMetaRow['isShow'] == '1') {
                                    $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setBpRowParamBigdecimalSidebar(' . $mainSelector . ', $(this), \'' . $expSet[1] . '\', (' . $exp . '))', $valRowExp);
                                } else {
                                    $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setBpRowParamNumSidebar(' . $mainSelector . ', $(this), \'' . $expSet[1] . '\', (' . $exp . '))', $valRowExp);
                                }
                            } else {
                                if ($typeCode == 'bigdecimal' && $getMetaRow['isShow'] == '1') {
                                    $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpRowParamBigdecimal(' . $mainSelector . ', $(this), \'' . $expSet[1] . '\', (' . $exp . '));', $valRowExp);
                                } elseif ($typeCode == 'text_editor' && $getMetaRow['isShow'] == '1') {
                                    $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpHdrParamTextEditor(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '));', $valRowExp);
                                } else {
                                    $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpRowParamNum(' . $mainSelector . ', $(this), \'' . $expSet[1] . '\', (' . $exp . '));', $valRowExp);
                                }
                            }
                        }
                    } else {
                        if ($typeCode == 'boolean' && $getMetaRow['isShow'] == '1') {
                            $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'checkboxCheckerUpdate($("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . '), ' . $getReplace . ')', $valRowExp);
                        } elseif ($typeCode == 'date' && $getMetaRow['isShow'] == '1') {
                            $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setBpHdrParamDate(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '))', $valRowExp);
                        } elseif ($typeCode == 'datetime' && $getMetaRow['isShow'] == '1') {
                            $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setBpHdrParamDateTime(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '))', $valRowExp);
                        } elseif ($typeCode == 'label' && $getMetaRow['isShow'] == '1') {
                            $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setBpHdrParamLabel(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '))', $valRowExp);
                        } elseif ($typeCode == 'text_editor' && $getMetaRow['isShow'] == '1') {
                            $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setBpHdrParamTextEditor(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '))', $valRowExp);
                        } elseif ($typeCode == 'bigdecimal' || $typeCode == 'long' || $typeCode == 'number' || $typeCode == 'integer' || $typeCode == 'decimal') {
                            if ($getMetaRow['LOOKUP_TYPE'] == 'popup' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['isShow'] == '1') {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setLookupPopupValue($("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . '), ' . $exp . ')', $valRowExp);
                            } elseif (($getMetaRow['LOOKUP_TYPE'] == 'combo' || $getMetaRow['LOOKUP_TYPE'] == 'combo_with_popup') && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['isShow'] == '1') {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpRowParamNum(' . $mainSelector . ', $(this), \'' . $expSet[1] . '\', (' . $exp . '));', $valRowExp);
                            } elseif ($getMetaRow['LOOKUP_TYPE'] == 'combogrid' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['isShow'] == '1') {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setLookupComboGridValue($("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . '), ' . $exp . ')', $valRowExp);
                            } elseif ($getMetaRow['LOOKUP_TYPE'] == 'radio' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['isShow'] == '1') {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setBpHdrRadio(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '))', $valRowExp);
                            } elseif ($getMetaRow['LOOKUP_TYPE'] == 'range_slider' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['isShow'] == '1') {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setBpHdrRangeSlider(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '))', $valRowExp);
                            } elseif (($typeCode == 'number' || $typeCode == 'integer' || $typeCode == 'decimal') && $getMetaRow['isShow'] == '1') {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', '$("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').autoNumeric("set", ' . $exp . ');', $valRowExp);
                            } elseif ($typeCode == 'bigdecimal' && $getMetaRow['isShow'] == '1') {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpHdrParamNum(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '));', $valRowExp);
                            } else {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', '$("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').val(' . $exp . ')', $valRowExp);
                            }
                        } else {

                            if (($getMetaRow['LOOKUP_TYPE'] == 'combo' || $getMetaRow['LOOKUP_TYPE'] == 'combo_with_popup') && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['isShow'] == '1') {

                                if ($getMetaRow['CHOOSE_TYPE'] == 'multi' || $getMetaRow['CHOOSE_TYPE'] == 'multicomma') {
                                    $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpHdrMultipleCombo(' . $mainSelector . ', $(this), \'' . $expSet[1] . '\', (' . $exp . '));', $valRowExp);
                                } else {
                                    $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpRowParamNum(' . $mainSelector . ', $(this), \'' . $expSet[1] . '\', (' . $exp . '));', $valRowExp);
                                }
                            } elseif ($getMetaRow['LOOKUP_TYPE'] == 'popup' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['isShow'] == '1') {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setLookupPopupValue($("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . '), ' . $exp . ')', $valRowExp);
                            } elseif ($getMetaRow['LOOKUP_TYPE'] == 'combogrid' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['isShow'] == '1') {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setLookupComboGridValue($("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . '), ' . $exp . ')', $valRowExp);
                            } elseif ($getMetaRow['LOOKUP_TYPE'] == 'radio' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['isShow'] == '1') {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'radioButtonCheckerUpdate($("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . '), ' . $exp . ')', $valRowExp);
                            } else if (($typeCode == 'description' || $typeCode == 'description_auto' || $typeCode == 'expression_editor' || $typeCode == 'base64_to_file' || $typeCode == 'clob') && $getMetaRow['isShow'] == '1') {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', '$("textarea[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').val(' . $exp . ')', $valRowExp);
                            } else {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', '$("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').val(' . $exp . ')', $valRowExp);
                            }
                        }
                    }
                }
            }

            if (!empty($parseExpressionEqualEqual[0])) {
                foreach ($parseExpressionEqualEqual[0] as $expVal) {
                    preg_match_all('/\[(.*?)\]/', $expVal, $expGet);
                    $expValVar = $expVal;

                    if (!empty($expGet[1])) {
                        foreach ($expGet[1] as $key => $valGetPath) {
                            $getMetaRow = $this->model->getMetaTypeCode($processId, $valGetPath);
                            $expVal = self::fullExpressionConvertGet($getMetaRow['type'], $getMetaRow, $mainSelector, "", $expGet[0][$key] . '.val()', $valGetPath, $expVal, $processActionType);
                        }
                    }
                    $valRowExp = str_replace($expValVar, $expVal, $valRowExp);
                }
            }

            if (!empty($parseExpressionlAttr[0])) {
                foreach ($parseExpressionlAttr[0] as $expVal) {
                    preg_match('/\[(.*?)\]/', $expVal, $expSetAttr);

                    if (strpos($expSetAttr[1], ',') !== false) {

                        $expSetAttrExpression = '';
                        $expSetAttrSplitArr = explode(',', $expSetAttr[1]);

                        if (strpos($expVal, '.hide()') !== false) {

                            foreach ($expSetAttrSplitArr as $expSetAttrSplit) {

                                $expSetAttrSplit = trim($expSetAttrSplit);
                                $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttrSplit);

                                if ($getMetaRow['parentId'] != '') {
                                    $expSetAttrExpression .= 'setBpRowParamHide(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                } elseif ($getMetaRow['type'] == 'text_editor') {
                                    $expSetAttrExpression .= '$("[data-cell-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').css({\'display\': \'none\'});';
                                } else {
                                    $expSetAttrExpression .= '$("[data-path=\'' . $expSetAttrSplit . '\'], th[data-cell-path=\'' . $expSetAttrSplit . '\'], tr[data-cell-path=\'' . $expSetAttrSplit . '\'], td[data-cell-path=\'' . $expSetAttrSplit . '\'], label[data-label-path=\'' . $expSetAttrSplit . '\'], div[data-cell-path=\'' . $expSetAttrSplit . '\'], div[data-section-path=\'' . $expSetAttrSplit . '\'], li[data-li-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').css({\'display\': \'none\'});';
                                }
                            }

                            $valRowExp = str_replace($expSetAttr[0] . ".hide();", $expSetAttrExpression, $valRowExp);
                        } elseif (strpos($expVal, '.softhide()') !== false) {

                            foreach ($expSetAttrSplitArr as $expSetAttrSplit) {
                                $expSetAttrSplit = trim($expSetAttrSplit);
                                $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttrSplit);

                                if ($getMetaRow['parentId'] != '') {
                                    $expSetAttrExpression .= 'setBpRowParamSoftHide(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                } else {
                                    $expSetAttrExpression .= '$("label[data-label-path=\'' . $expSetAttrSplit . '\'], div[data-section-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').css({\'visibility\': \'hidden\'});';
                                }
                            }

                            $valRowExp = str_replace($expSetAttr[0] . ".softhide();", $expSetAttrExpression, $valRowExp);
                        } elseif (strpos($expVal, '.show()') !== false) {

                            foreach ($expSetAttrSplitArr as $expSetAttrSplit) {
                                $expSetAttrSplit = trim($expSetAttrSplit);
                                $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttrSplit);

                                if ($getMetaRow['parentId'] != '') {
                                    $expSetAttrExpression .= 'setBpRowParamShow(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                } elseif ($getMetaRow['type'] == 'text_editor') {
                                    $expSetAttrExpression .= '$("[data-cell-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').css({\'display\': \'\'});';
                                } else {
                                    $expSetAttrExpression .= '$("[data-path=\'' . $expSetAttrSplit . '\'], th[data-cell-path=\'' . $expSetAttrSplit . '\'], tr[data-cell-path=\'' . $expSetAttrSplit . '\'], td[data-cell-path=\'' . $expSetAttrSplit . '\'], label[data-label-path=\'' . $expSetAttrSplit . '\'], div[data-cell-path=\'' . $expSetAttrSplit . '\'], div[data-section-path=\'' . $expSetAttrSplit . '\'], li[data-li-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').css({\'display\': \'\', \'visibility\': \'\'});';
                                }
                            }

                            $valRowExp = str_replace($expSetAttr[0] . ".show();", $expSetAttrExpression, $valRowExp);
                        } elseif (strpos($expVal, '.disable()') !== false) {

                            foreach ($expSetAttrSplitArr as $expSetAttrSplit) {
                                $expSetAttrSplit = trim($expSetAttrSplit);
                                $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttrSplit);

                                if ($getMetaRow['ABILITY_TOGGLE'] == 'enable') {
                                    $expSetAttrExpression .= '';
                                } else {

                                    if ($getMetaRow['type'] == 'group') {
                                        $expSetAttrExpression .= 'setBpRowGroupDisable(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                    } else {
                                        if ($getMetaRow['parentId'] != '') {

                                            if ($getMetaRow['type'] == 'button') {
                                                $expSetAttrExpression .= 'setBpRowButtonDisable(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                            } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'radio') {

                                                $expSetAttrExpression .= 'setBpRowRadioDisable(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                            } else {
                                                $expSetAttrExpression .= 'setBpRowParamDisable(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                            }
                                        } else {
                                            if ($getMetaRow['type'] == 'boolean' && $getMetaRow['isShow'] == '1') {
                                                $expSetAttrExpression .= 'checkboxDisableUpdate( $("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . '));';
                                            } elseif ($getMetaRow['type'] == 'button' && $getMetaRow['isShow'] == '1') {
                                                $expSetAttrExpression .= 'bpButtonDisable(\'' . $expSetAttrSplit . '\', ' . $mainSelector . ');';
                                            } elseif ($getMetaRow['type'] == 'file' && $getMetaRow['isShow'] == '1') {
                                                $expSetAttrExpression .= 'setBpHeaderFileFieldDisable(' . $mainSelector . ', \'' . $expSetAttrSplit . '\');';
                                            } else {

                                                if ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && ($getMetaRow['LOOKUP_TYPE'] == 'combo' || $getMetaRow['LOOKUP_TYPE'] == 'combo_with_popup' || $getMetaRow['LOOKUP_TYPE'] == 'combogrid')) {

                                                    $expSetAttrExpression .= 'setBpHeaderParamDisable(' . $mainSelector . ', \'' . $expSetAttrSplit . '\');';
                                                } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'radio') {

                                                    $expSetAttrExpression .= 'setBpHeaderRadioDisable(' . $mainSelector . ', \'' . $expSetAttrSplit . '\');';
                                                } else {
                                                    $expSetAttrExpression .= 'if ($("[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').hasClass("descriptionInit")) {
                                                        $("textarea[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').prop("readonly", true).attr("tabindex", "-1");                        
                                                    } else {
                                                        $("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').prop("readonly", true).attr("tabindex", "-1");                        
                                                    }';
                                                    $expSetAttrExpression .= 'if ($("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").length > 0) {
                                                        $("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("input[type=\'text\']").attr({\'readonly\': \'readonly\', \'tabindex\': \'-1\'});
                                                        $("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find(".input-group-btn > button:not([data-more-metaid])").attr(\'style\', \'pointer-events: none; background-color: #eeeeee !important\').prop(\'disabled\', true);                                
                                                    }';
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            $valRowExp = str_replace($expSetAttr[0] . '.disable();', $expSetAttrExpression, $valRowExp);
                        } elseif (strpos($expVal, '.enable()') !== false) {

                            foreach ($expSetAttrSplitArr as $expSetAttrSplit) {
                                $expSetAttrSplit = trim($expSetAttrSplit);
                                $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttrSplit);

                                if ($getMetaRow['ABILITY_TOGGLE'] == 'disable') {
                                    $expSetAttrExpression .= '';
                                } else {

                                    if ($getMetaRow['type'] == 'group') {
                                        $expSetAttrExpression .= 'setBpRowGroupEnable(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                    } else {
                                        if ($getMetaRow['parentId'] != '') {

                                            if ($getMetaRow['isShow'] == '1' && $getMetaRow['type'] == 'boolean') {

                                                $expSetAttrExpression .= 'setBpRowCheckboxEnable(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                            } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['type'] == 'button') {

                                                $expSetAttrExpression .= 'setBpRowButtonEnable(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                            } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'radio') {

                                                $expSetAttrExpression .= 'setBpRowRadioEnable(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                            } else {
                                                $expSetAttrExpression .= 'setBpRowParamEnable(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                            }
                                        } elseif ($getMetaRow['type'] == 'boolean' && $getMetaRow['isShow'] == '1') {
                                            $expSetAttrExpression .= 'checkboxEnableUpdate($("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . '));';
                                        } elseif ($getMetaRow['type'] == 'button' && $getMetaRow['isShow'] == '1') {
                                            $expSetAttrExpression .= 'bpButtonEnable(\'' . $expSetAttrSplit . '\', ' . $mainSelector . ');';
                                        } elseif ($getMetaRow['type'] == 'file' && $getMetaRow['isShow'] == '1') {

                                            $expSetAttrExpression .= 'setBpHeaderFileFieldEnable(' . $mainSelector . ', \'' . $expSetAttrSplit . '\');';
                                        } else {

                                            if ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && ($getMetaRow['LOOKUP_TYPE'] == 'combo' || $getMetaRow['LOOKUP_TYPE'] == 'combo_with_popup' || $getMetaRow['LOOKUP_TYPE'] == 'combogrid')) {

                                                $expSetAttrExpression .= 'setBpHeaderParamEnable(' . $mainSelector . ', \'' . $expSetAttrSplit . '\');';
                                            } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'radio') {

                                                $expSetAttrExpression .= 'setBpHeaderRadioEnable(' . $mainSelector . ', \'' . $expSetAttrSplit . '\');';
                                            } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'popup') {
                                                $expSetAttrExpression .= '
                                                    $("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("input[type=\'text\']").removeAttr(\'readonly tabindex\');
                                                    $("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("button").removeAttr(\'style\').prop(\'disabled\', false);';
                                            } else {
                                                $expSetAttrExpression .= '$("input[data-path=\'' . $expSetAttrSplit . '\'], textarea[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').removeAttr("readonly tabindex");';
                                            }
                                        }
                                    }
                                }
                            }

                            $valRowExp = str_replace($expSetAttr[0] . '.enable();', $expSetAttrExpression, $valRowExp);
                        } elseif (strpos($expVal, '.required()') !== false) {

                            foreach ($expSetAttrSplitArr as $expSetAttrSplit) {
                                $expSetAttrSplit = trim($expSetAttrSplit);
                                $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttrSplit);

                                if ($getMetaRow['parentId'] != '') {
                                    $expSetAttrExpression .= 'setBpRowParamRequired(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                } else {
                                    $expSetAttrExpression .= 'bpSetHeaderParamRequired(' . $mainSelector . ', \'' . $expSetAttrSplit . '\');';
                                }
                            }

                            $valRowExp = str_replace($expSetAttr[0] . ".required();", $expSetAttrExpression, $valRowExp);
                        } elseif (strpos($expVal, '.nonrequired()') !== false) {

                            foreach ($expSetAttrSplitArr as $expSetAttrSplit) {
                                $expSetAttrSplit = trim($expSetAttrSplit);
                                $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttrSplit);

                                if ($getMetaRow['parentId'] != '') {
                                    $expSetAttrExpression .= 'setBpRowParamNonRequired(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                } else {
                                    $expSetAttrExpression .= 'bpSetHeaderParamNonRequired(' . $mainSelector . ', \'' . $expSetAttrSplit . '\');';
                                }
                            }

                            $valRowExp = str_replace($expSetAttr[0] . ".nonrequired();", $expSetAttrExpression, $valRowExp);
                        }
                    } else {
                        $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttr[1]);

                        if (strpos($expVal, '.hide()') !== false) {
                            if ($getMetaRow['parentId'] != '') {
                                $valRowExp = str_replace($expSetAttr[0] . ".hide();", 'setBpRowParamHide(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $valRowExp);
                            } elseif ($getMetaRow['type'] == 'text_editor') {
                                $valRowExp = str_replace($expSetAttr[0] . ".hide();", '$("[data-cell-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').css({\'display\': \'none\'});', $valRowExp);
                            } else {
                                $valRowExp = str_replace($expSetAttr[0] . ".hide();", '$("[data-path=\'' . $expSetAttr[1] . '\'], th[data-cell-path=\'' . $expSetAttr[1] . '\'], tr[data-cell-path=\'' . $expSetAttr[1] . '\'], td[data-cell-path=\'' . $expSetAttr[1] . '\'], label[data-label-path=\'' . $expSetAttr[1] . '\'], div[data-cell-path=\'' . $expSetAttr[1] . '\'], div[data-section-path=\'' . $expSetAttr[1] . '\'], li[data-li-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').css({\'display\': \'none\'});', $valRowExp);
                            }
                        } elseif (strpos($expVal, '.softhide()') !== false) {
                            if ($getMetaRow['parentId'] != '') {
                                $valRowExp = str_replace($expSetAttr[0] . ".softhide();", 'setBpRowParamSoftHide(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $valRowExp);
                            } else {
                                $valRowExp = str_replace($expSetAttr[0] . ".softhide();", '$("label[data-label-path=\'' . $expSetAttr[1] . '\'], div[data-section-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').css({\'visibility\': \'hidden\'});', $valRowExp);
                            }
                        } elseif (strpos($expVal, '.show()') !== false) {
                            if ($getMetaRow['parentId'] != '') {
                                $valRowExp = str_replace($expSetAttr[0] . ".show();", 'setBpRowParamShow(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $valRowExp);
                            } elseif ($getMetaRow['type'] == 'text_editor') {
                                $valRowExp = str_replace($expSetAttr[0] . ".show();", '$("[data-cell-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').css({\'display\': \'\'});', $valRowExp);
                            } else {
                                $valRowExp = str_replace($expSetAttr[0] . ".show();", '$("[data-path=\'' . $expSetAttr[1] . '\'], th[data-cell-path=\'' . $expSetAttr[1] . '\'], tr[data-cell-path=\'' . $expSetAttr[1] . '\'], td[data-cell-path=\'' . $expSetAttr[1] . '\'], label[data-label-path=\'' . $expSetAttr[1] . '\'], div[data-cell-path=\'' . $expSetAttr[1] . '\'], div[data-section-path=\'' . $expSetAttr[1] . '\'], li[data-li-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').css({\'display\': \'\', \'visibility\': \'\'});', $valRowExp);
                            }
                        } elseif (strpos($expVal, '.disable()') !== false) {

                            if ($getMetaRow['parentId'] != '') {

                                if ($getMetaRow['ABILITY_TOGGLE'] == 'enable') {
                                    $valRowExp = str_replace($expSetAttr[0] . '.disable();', '', $valRowExp);
                                } else {

                                    if ($getMetaRow['type'] == 'boolean' && $getMetaRow['isShow'] == '1') {
                                        $valRowExp = str_replace($expSetAttr[0] . ".disable();", 'setBpRowCheckboxDisable(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $valRowExp);
                                    } elseif ($getMetaRow['type'] == 'button' && $getMetaRow['isShow'] == '1') {
                                        $valRowExp = str_replace($expSetAttr[0] . ".disable();", 'setBpRowButtonDisable(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $valRowExp);
                                    } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'radio') {

                                        $valRowExp = str_replace($expSetAttr[0] . ".disable();", 'setBpRowRadioDisable(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $valRowExp);
                                    } else {
                                        $valRowExp = str_replace($expSetAttr[0] . ".disable();", 'setBpRowParamDisable(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $valRowExp);
                                    }
                                }
                            } else {

                                if ($getMetaRow['ABILITY_TOGGLE'] == 'enable') {

                                    $setDisable = '';
                                } else {

                                    if ($getMetaRow['type'] == 'boolean' && $getMetaRow['isShow'] == '1') {
                                        $setDisable = 'checkboxDisableUpdate( $("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . '));';
                                    } elseif ($getMetaRow['type'] == 'button' && $getMetaRow['isShow'] == '1') {
                                        $setDisable = 'bpButtonDisable(\'' . $expSetAttr[1] . '\', ' . $mainSelector . ');';
                                    } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && ($getMetaRow['LOOKUP_TYPE'] == 'combo' || $getMetaRow['LOOKUP_TYPE'] == 'combogrid')) {
                                        $setDisable = 'setBpHeaderParamDisable(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');';
                                    } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'combo_with_popup') {
                                        $setDisable = 'setBpHeaderComboWithPopupDisable(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');';
                                    } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'radio') {
                                        $setDisable = 'setBpHeaderRadioDisable(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');';
                                    } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['type'] == 'file') {
                                        $setDisable = 'setBpHeaderFileFieldDisable(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');';
                                    } else {
                                        $setDisable = '$("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').prop("readonly", true);';
                                        $setDisable .= 'if($("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").length > 0) {
                                                        $("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("input[type=\'text\']").attr(\'readonly\', \'readonly\');
                                                        $("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find(".input-group-btn > button:not([data-more-metaid])").attr(\'style\', \'pointer-events: none; background-color: #eeeeee !important\');                                
                                                        }';
                                    }
                                }

                                $valRowExp = str_replace($expSetAttr[0] . '.disable();', $setDisable, $valRowExp);
                            }
                        } elseif (strpos($expVal, '.enable()') !== false) {

                            if ($getMetaRow['parentId'] != '') {

                                if ($getMetaRow['ABILITY_TOGGLE'] == 'disable') {

                                    $valRowExp = str_replace($expSetAttr[0] . '.enable();', '', $valRowExp);
                                } else {

                                    if ($getMetaRow['isShow'] == '1' && $getMetaRow['type'] == 'button') {
                                        $valRowExp = str_replace($expSetAttr[0] . ".enable();", 'setBpRowButtonEnable(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $valRowExp);
                                    } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'radio') {

                                        $valRowExp = str_replace($expSetAttr[0] . ".enable();", 'setBpRowRadioEnable(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $valRowExp);
                                    } else {
                                        $valRowExp = str_replace($expSetAttr[0] . ".enable();", 'setBpRowParamEnable(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $valRowExp);
                                    }
                                }
                            } else {

                                if ($getMetaRow['ABILITY_TOGGLE'] == 'disable') {
                                    $setEnable = '';
                                } else {

                                    if ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && ($getMetaRow['LOOKUP_TYPE'] == 'combo' || $getMetaRow['LOOKUP_TYPE'] == 'combogrid')) {

                                        $setEnable = 'setBpHeaderParamEnable(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');';
                                    } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'combo_with_popup') {

                                        $setEnable = 'setBpHeaderComboWithPopupEnable(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');';
                                    } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'radio') {

                                        $setEnable = 'setBpHeaderRadioEnable(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');';
                                    } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'popup') {
                                        $setEnable = '
                                            $("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("input[type=\'text\']").removeAttr(\'readonly\');
                                            $("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("button").removeAttr(\'style\').prop(\'disabled\', false);';
                                    } elseif ($getMetaRow['type'] == 'boolean' && $getMetaRow['isShow'] == '1') {
                                        $setEnable = 'checkboxEnableUpdate( $("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . '));';
                                    } elseif ($getMetaRow['type'] == 'button' && $getMetaRow['isShow'] == '1') {
                                        $setEnable = 'bpButtonEnable(\'' . $expSetAttr[1] . '\', ' . $mainSelector . ');';
                                    } elseif ($getMetaRow['type'] == 'file' && $getMetaRow['isShow'] == '1') {

                                        $setEnable = 'setBpHeaderFileFieldEnable(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');';
                                    } else {
                                        $setEnable = '$("[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').removeAttr("readonly");';
                                    }
                                }

                                $valRowExp = str_replace($expSetAttr[0] . '.enable();', $setEnable, $valRowExp);
                            }
                        } elseif (strpos($expVal, '.required()') !== false) {

                            if ($getMetaRow['parentId'] != '') {
                                $valRowExp = str_replace($expSetAttr[0] . ".required();", 'setBpRowParamRequired(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $valRowExp);
                            } else {
                                $valRowExp = str_replace($expSetAttr[0] . ".required();", 'bpSetHeaderParamRequired(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');', $valRowExp);
                            }
                        } elseif (strpos($expVal, '.nonrequired()') !== false) {
                            if ($getMetaRow['parentId'] != '') {
                                $valRowExp = str_replace($expSetAttr[0] . ".nonrequired();", 'setBpRowParamNonRequired(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $valRowExp);
                            } else {
                                $valRowExp = str_replace($expSetAttr[0] . ".nonrequired();", 'bpSetHeaderParamNonRequired(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');', $valRowExp);
                            }
                        }
                    }
                }
            }

            if (!empty($parseExpressionMessage[0])) {
                foreach ($parseExpressionMessage[0] as $expVal) {
                    preg_match_all('/message(\((.*)+\))/', $expVal, $mesgGet);
                    $mesgGet = explode(',', $mesgGet[1][0]);
                    $seconds = '';
                    $message = trim(rtrim($mesgGet[1], ')'));

                    if (strpos($message, "'") === false) {
                        $message = "'" . Lang::line($message) . "'";
                    }

                    if (isset($mesgGet[2])) {
                        $seconds = 'delay: ' . ((int)$mesgGet[2] * 1000) . ', ';
                    }

                    $valRowExp = str_replace($expVal . ';', 'PNotify.removeAll(); new PNotify({title: \'' . ltrim($mesgGet[0], "(") . '\', text: ' . $message . ', type: \'' . ltrim($mesgGet[0], "(") . '\', sticker: false, ' . $seconds . 'addclass: pnotifyPosition});', $valRowExp);
                }
            }

            if (!empty($parseExpressionFiscalPeriodMessage[0])) {
                foreach ($parseExpressionFiscalPeriodMessage[0] as $expFpVal) {
                    preg_match_all('/fiscalPeriodMessage(\((.*)+\))/', $expFpVal, $mesgGet);
                    $mesgGet = explode(',', $mesgGet[1][0]);

                    $message = trim(rtrim($mesgGet[1], ')'));

                    if (strpos($message, "'") === false) {
                        $message = "'" . Lang::line($message) . "'";
                    }

                    $valRowExp = str_replace($expFpVal . ';', 'showFiscalPeriodMessage(\'' . ltrim($mesgGet[0], "(") . '\', ' . $message . ');', $valRowExp);
                }
            }

            if (!empty($parseExpressionStyle[0])) {
                foreach ($parseExpressionStyle[0] as $expVal) {
                    if (strpos($expVal, '.label(') !== false && strpos($expVal, '.control(') !== false) {
                        preg_match_all('/\[(.*)\](.label\((.*)\))(.control\((.*)\))/', $expVal, $expStyleGet);
                        $expVal = $expVal . ";";
                        $getMetaRow = $this->model->getMetaTypeCode($processId, $expStyleGet[1][0]);
                        $typeCode = $getMetaRow['type'];

                        if ($typeCode == 'group') {

                            if ($expStyleGet[3][0] === 'reset') {
                                $valRowExp = str_replace($expVal, 'setBpGroupRemoveStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\');', $valRowExp);
                            } else {
                                $valRowExp = str_replace($expVal, 'setBpGroupStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\', \'' . $expStyleGet[3][0] . '\');', $valRowExp);
                            }
                        } else {

                            if ($expStyleGet[3][0] === 'reset') {
                                if ($getMetaRow['parentId'] != '') {
                                    $valRowExp = str_replace($expVal, 'setBpRowParamLabelRemoveStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\'); ##CONTROL##', $valRowExp);
                                } else {
                                    $valRowExp = str_replace($expVal, '$("label[data-label-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').removeAttr("style"); ##CONTROL##', $valRowExp);
                                }
                            } else {
                                if ($getMetaRow['parentId'] != '') {
                                    $valRowExp = str_replace($expVal, 'setBpRowParamLabelStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\', \'' . $expStyleGet[3][0] . '\'); ##CONTROL##', $valRowExp);
                                } else {
                                    $valRowExp = str_replace($expVal, '$("label[data-label-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').attr("style", "' . $expStyleGet[3][0] . '"); ##CONTROL##', $valRowExp);
                                }
                            }
                            if ($expStyleGet[5][0] === 'reset') {
                                if ($getMetaRow['parentId'] != '') {
                                    if ($getMetaRow['LOOKUP_TYPE'] == 'popup' && $getMetaRow['LOOKUP_META_DATA_ID'] != '') {
                                        $valRowExp = str_replace('##CONTROL##', 'setBpRowPopupParamRemoveStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\');', $valRowExp);
                                    } else {
                                        $valRowExp = str_replace('##CONTROL##', 'setBpRowParamRemoveStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\');', $valRowExp);
                                    }
                                } else {
                                    if ($typeCode == 'description' || $typeCode == 'description_auto' || $typeCode == 'text_editor') {
                                        $valRowExp = str_replace('##CONTROL##', '$("textarea[data-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').removeAttr("style");', $valRowExp);
                                    } else {
                                        $valRowExp = str_replace('##CONTROL##', '$("input[data-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').removeAttr("style");', $valRowExp);
                                    }
                                }
                            } else {
                                $styles = ltrim($expStyleGet[5][0], "'");
                                $styles = rtrim($styles, "'");
                                if ($getMetaRow['parentId'] != '') {
                                    if ($getMetaRow['LOOKUP_TYPE'] == 'popup' && $getMetaRow['LOOKUP_META_DATA_ID'] != '') {
                                        $valRowExp = str_replace('##CONTROL##', 'setBpRowPopupParamStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\', \'' . $styles . '\');', $valRowExp);
                                    } else {
                                        $valRowExp = str_replace('##CONTROL##', 'setBpRowParamStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\', \'' . $styles . '\');', $valRowExp);
                                    }
                                } else {
                                    if ($typeCode == 'description' || $typeCode == 'description_auto' || $typeCode == 'text_editor') {
                                        $valRowExp = str_replace('##CONTROL##', '$("textarea[data-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').attr("style", "' . $styles . '");', $valRowExp);
                                    } else {
                                        $valRowExp = str_replace('##CONTROL##', '$("input[data-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').attr("style", "' . $styles . '");', $valRowExp);
                                    }
                                }
                            }
                        }
                    }
                    if (strpos($expVal, '.label(') !== false && strpos($expVal, '.control(') === false) {
                        preg_match_all('/\[(.*)\](.label\((.*)\))/', $expVal, $expStyleGet);
                        $expVal = $expVal . ';';
                        $getMetaRow = $this->model->getMetaTypeCode($processId, $expStyleGet[1][0]);

                        if ($getMetaRow['type'] == 'group') {

                            if ($expStyleGet[3][0] === 'reset') {
                                $fullExpression = str_replace($expVal, 'setBpGroupRemoveStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\');', $fullExpression);
                            } else {
                                $fullExpression = str_replace($expVal, 'setBpGroupStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\', \'' . $expStyleGet[3][0] . '\');', $fullExpression);
                            }
                        } else {
                            if ($expStyleGet[3][0] === 'reset') {
                                if ($getMetaRow['parentId'] != '') {
                                    $valRowExp = str_replace($expVal, 'setBpRowParamLabelRemoveStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\');', $valRowExp);
                                } else {
                                    $valRowExp = str_replace($expVal, '$("label[data-label-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').removeAttr("style");', $valRowExp);
                                }
                            } else {
                                if ($getMetaRow['parentId'] != '') {
                                    $valRowExp = str_replace($expVal, 'setBpRowParamLabelStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\', \'' . $expStyleGet[3][0] . '\');', $valRowExp);
                                } else {
                                    $valRowExp = str_replace($expVal, '$("label[data-label-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').attr("style", "' . $expStyleGet[3][0] . '");', $valRowExp);
                                }
                            }
                        }
                    }

                    if (strpos($expVal, '.label(') === false && strpos($expVal, '.control(') !== false) {
                        preg_match_all('/\[(.*)\](.control\((.*)\))/', $expVal, $expStyleGet);
                        $expVal = $expVal . ";";
                        $getMetaRow = $this->model->getMetaTypeCode($processId, $expStyleGet[1][0]);
                        $typeCode = $getMetaRow['type'];

                        if ($expStyleGet[3][0] === 'reset') {
                            if ($getMetaRow['parentId'] != '') {
                                if ($getMetaRow['LOOKUP_TYPE'] == 'popup' && $getMetaRow['LOOKUP_META_DATA_ID'] != '') {
                                    $valRowExp = str_replace($expVal, 'setBpRowPopupParamRemoveStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\');', $valRowExp);
                                } else {
                                    $valRowExp = str_replace($expVal, 'setBpRowParamRemoveStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\');', $valRowExp);
                                }
                            } else {
                                $valRowExp = str_replace($expVal, '$("[data-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').removeAttr("style");', $valRowExp);
                            }
                        } else {
                            $styles = ltrim($expStyleGet[3][0], "'");
                            $styles = rtrim($styles, "'");
                            if ($getMetaRow['parentId'] != '') {
                                if ($getMetaRow['LOOKUP_TYPE'] == 'popup' && $getMetaRow['LOOKUP_META_DATA_ID'] != '') {
                                    $valRowExp = str_replace($expVal, 'setBpRowPopupParamStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\', \'' . $styles . '\');', $valRowExp);
                                } else {
                                    $valRowExp = str_replace($expVal, 'setBpRowParamStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\', \'' . $styles . '\');', $valRowExp);
                                }
                            } else {
                                $valRowExp = str_replace($expVal, '$("[data-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').attr("style", "' . $styles . '");', $valRowExp);
                            }
                        }
                    }
                }
            }

            if (!empty($parseExpressionTrigger[0])) {
                foreach ($parseExpressionTrigger[0] as $kkk => $expVal) {

                    preg_match('/\[(.*?)\]/', $expVal, $expSet);
                    $getMetaRow = $this->model->getMetaTypeCode($processId, $expSet[1]);

                    if ($processActionType == 'view') {
                        $valRowExp = preg_replace('/\[' . $expSet[1] . '\].trigger\(/', '$("span[data-view-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').trigger(', $valRowExp);
                    } elseif (($getMetaRow['LOOKUP_TYPE'] == 'combo' || $getMetaRow['LOOKUP_TYPE'] == 'combo_with_popup') && $getMetaRow['LOOKUP_META_DATA_ID'] != '') {
                        $valRowExp = preg_replace('/\[' . $expSet[1] . '\].trigger\(/', '$("select[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').trigger(', $valRowExp);
                    } else {
                        $valRowExp = preg_replace('/\[' . $expSet[1] . '\].trigger\(/', '$("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').trigger(', $valRowExp);
                    }
                }
            }

            if (!empty($parseExpressionTriggerRow[0])) {
                foreach ($parseExpressionTriggerRow[0] as $kkk => $expVal) {

                    preg_match('/\[(.*?)\]/', $expVal, $expSet);
                    $getMetaRow = $this->model->getMetaTypeCode($processId, $expSet[1]);

                    if (($getMetaRow['LOOKUP_TYPE'] == 'combo' || $getMetaRow['LOOKUP_TYPE'] == 'combo_with_popup') && $getMetaRow['LOOKUP_META_DATA_ID'] != '') {
                        $valRowExp = preg_replace('/\[' . $expSet[1] . '\].rowTrigger\(/', '$("select[data-path=\'' . $expSet[1] . '\']", $(this).closest("tr")).trigger(', $valRowExp);
                    } else {
                        $valRowExp = preg_replace('/\[' . $expSet[1] . '\].rowTrigger\(/', '$("input[data-path=\'' . $expSet[1] . '\']", $(this).closest("tr")).trigger(', $valRowExp);
                    }
                }
            }

            if (!empty($parseExpressionFocus[0])) {
                foreach ($parseExpressionFocus[0] as $fk => $fcVal) {
                    preg_match('/\[(.*?)\]/', $fcVal, $expSet);
                    $getMetaRow = $this->model->getMetaTypeCode($processId, $expSet[1]);
                    $typeCode = $getMetaRow['type'];

                    if ($getMetaRow['parentId'] != '') {
                        $valRowExp = preg_replace('/\[' . $expSet[1] . '\].focus\(\);/', 'setBpRowParamFocus(' . $mainSelector . ', $(this), \'' . $expSet[1] . '\');', $valRowExp);
                    } else {
                        if ($typeCode == 'description' || $typeCode == 'description_auto' || $typeCode == 'text_editor') {
                            $valRowExp = preg_replace('/\[' . $expSet[1] . '\].focus\(\);/', 'setTimeout(function(){ $("textarea[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').focus(); }, 0);', $valRowExp);
                        } else {
                            if ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'popup') {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\].focus\(\);/', 'setTimeout(function(){ $("input#' . $expSet[1] . '_displayField", ' . $mainSelector . ').focus(); }, 0);', $valRowExp);
                            } else {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\].focus\(\);/', 'setTimeout(function(){ $("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').focus().select(); }, 0);', $valRowExp);
                            }
                        }
                    }
                }
            }
            // </editor-fold>

            if (!empty($expEventCatch)) {

                $eventFieldMetaRow = $this->model->getMetaTypeCode($processId, $expEventCatch[1]);

                if (strpos($valRowExp, '].keyup()') !== false) {

                    if ($eventFieldMetaRow['type'] == 'description' || $eventFieldMetaRow['type'] == 'description_auto') {
                        $valRowExp = str_replace($expEventCatch[0] . ".keyup()", $mainSelector . '.on("keyup", "textarea[data-path=\'' . $expEventCatch[1] . '\']", function(e){ eventDelay(function()', $valRowExp);
                    } else {
                        $valRowExp = str_replace($expEventCatch[0] . ".keyup()", $mainSelector . '.on("keyup", "input[data-path=\'' . $expEventCatch[1] . '\']", function(e){ eventDelay(function()', $valRowExp);
                    }

                    $valRowExp .= '}, 250);';
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, '].keydown()') !== false) {
                    $valRowExp = str_replace($expEventCatch[0] . ".keydown()", $mainSelector . '.on("keydown", "input[data-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, '].change()') !== false) {
                    if ($processActionType == 'view') {
                        $valRowExp = str_replace($expEventCatch[0] . ".change()", $mainSelector . '.on("change", "span[data-view-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    } elseif (($eventFieldMetaRow['LOOKUP_TYPE'] == 'combo' || $eventFieldMetaRow['LOOKUP_TYPE'] == 'combo_with_popup') && $eventFieldMetaRow['LOOKUP_META_DATA_ID'] != '') {
                        $valRowExp = str_replace($expEventCatch[0] . ".change()", $mainSelector . '.on("change", "select[data-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    } elseif ($eventFieldMetaRow['LOOKUP_TYPE'] == 'radio' && $eventFieldMetaRow['LOOKUP_META_DATA_ID'] != '') {
                        $valRowExp = str_replace($expEventCatch[0] . ".change()", $mainSelector . '.on("change", "input[data-path=\'' . $expEventCatch[1] . '\']:not([data-isdisabled])", function(e)', $valRowExp);
                    } elseif ($eventFieldMetaRow['LOOKUP_TYPE'] == 'range_slider' && $eventFieldMetaRow['LOOKUP_META_DATA_ID'] != '') {
                        $valRowExp = str_replace($expEventCatch[0] . ".change()", $mainSelector . '.on("change", "input[data-path=\'' . $expEventCatch[1] . '\'].irs-hidden-input:not([data-isdisabled])", function(e)', $valRowExp);
                    } elseif ($eventFieldMetaRow['type'] == 'date' || $eventFieldMetaRow['type'] == 'datetime') {
                        $valRowExp = str_replace($expEventCatch[0] . ".change()", $mainSelector . '.on("changeDate", "input[data-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    } elseif ($eventFieldMetaRow['type'] == 'description' || $eventFieldMetaRow['type'] == 'description_auto' || $eventFieldMetaRow['type'] == 'text_editor' || $eventFieldMetaRow['type'] == 'rule_expression') {
                        $valRowExp = str_replace($expEventCatch[0] . ".change()", $mainSelector . '.on("change", "textarea[data-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    } elseif ($eventFieldMetaRow['type'] == 'bigdecimal') {
                        $valRowExp = str_replace($expEventCatch[0] . ".change(){", $mainSelector . '.on("change", "input[data-path=\'' . $expEventCatch[1] . '\']", function(e){ if(typeof $jthis.attr("data-prevent-change") !== "undefined"){return;} eventDelay(function(){', $valRowExp);
                        $valRowExp = str_replace($expEventCatch[0] . ".change() {", $mainSelector . '.on("change", "input[data-path=\'' . $expEventCatch[1] . '\']", function(e){ if(typeof $jthis.attr("data-prevent-change") !== "undefined"){return;} eventDelay(function(){', $valRowExp);
                        $valRowExp .= '}, 1);';
                    } elseif ($eventFieldMetaRow['type'] == 'file') {
                        $valRowExp = str_replace($expEventCatch[0] . ".change()", $mainSelector . '.on("change", "input[data-path=\'' . $expEventCatch[1] . '\']", function(e){ eventDelay(function()', $valRowExp);
                        $valRowExp .= '}, 150);';
                    } else {
                        $valRowExp = str_replace($expEventCatch[0] . ".change()", $mainSelector . '.on("change", "input[data-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    }
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, '].click()') !== false) {

                    if (isset($eventFieldMetaRow['JSON_CONFIG']['isRightButton']) && $eventFieldMetaRow['JSON_CONFIG']['isRightButton']) {

                        $valRowExp = str_replace($expEventCatch[0] . '.click()', $mainSelector . '.on("click", "button[data-rightbutton-path=\'' . $expEventCatch[1] . '\']:not([disabled])", function(e)', $valRowExp);
                    } elseif (($eventFieldMetaRow['LOOKUP_TYPE'] == 'combo' || $eventFieldMetaRow['LOOKUP_TYPE'] == 'combo_with_popup') && $eventFieldMetaRow['LOOKUP_META_DATA_ID'] != '') {
                        $valRowExp = str_replace($expEventCatch[0] . '.click()', $mainSelector . '.on("click", "select[data-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    } elseif ($eventFieldMetaRow['LOOKUP_TYPE'] == 'radio' && $eventFieldMetaRow['LOOKUP_META_DATA_ID'] != '') {
                        $valRowExp = str_replace($expEventCatch[0] . '.click()', $mainSelector . '.on("click", "input[data-path=\'' . $expEventCatch[1] . '\']:not([data-isdisabled])", function(e){ eventDelay(function()', $valRowExp);
                        $valRowExp .= '}, 150);';
                    } elseif ($eventFieldMetaRow['LOOKUP_TYPE'] == 'icon' && $eventFieldMetaRow['LOOKUP_META_DATA_ID'] != '') {
                        $valRowExp = str_replace($expEventCatch[0] . '.click()', $mainSelector . '.on("click", "div[data-section-path=\'' . $expEventCatch[1] . '\'] ul > li[data-id]", function(e){ eventDelay(function()', $valRowExp);
                        $valRowExp .= '}, 150);';
                    } elseif ($eventFieldMetaRow['type'] == 'boolean') {
                        $valRowExp = str_replace($expEventCatch[0] . '.click()', $mainSelector . '.on("click", "input[data-path=\'' . $expEventCatch[1] . '\']:not([data-isdisabled])", function(e)', $valRowExp);
                    } elseif ($eventFieldMetaRow['type'] == 'button') {
                        $valRowExp = str_replace($expEventCatch[0] . '.click()', $mainSelector . '.on("click", "button[data-path=\'' . $expEventCatch[1] . '\']:not([disabled])", function(e)', $valRowExp);
                    } elseif ($eventFieldMetaRow['type'] == 'description' || $eventFieldMetaRow['type'] == 'description_auto') {
                        $valRowExp = str_replace($expEventCatch[0] . '.click()', $mainSelector . '.on("click", "textarea[data-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    } elseif ($eventFieldMetaRow['type'] == 'url') {
                        $valRowExp = str_replace($expEventCatch[0] . '.click()', $mainSelector . '.on("click", "a[data-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    } else {
                        $valRowExp = str_replace($expEventCatch[0] . '.click()', $mainSelector . '.on("click", "input[data-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    }
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, '].remove()') !== false) {
                    $valRowExp = str_replace($expEventCatch[0] . '.remove()', $mainSelector . '.on("click", "table[data-table-path=\'' . $expEventCatch[1] . '\'] > tbody > tr > td > .bp-remove-row", function(e){ eventDelay(function()', $valRowExp);
                    $valRowExp .= '}, 200);';
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, '].delete()') !== false) {
                    $valRowExp = str_replace($expEventCatch[0] . '.delete()', $mainSelector . '.on("click", "table[data-table-path=\'' . $expEventCatch[1] . '\'] > tbody > tr > td > .bp-remove-row", function(e){ eventDelay(function()', $valRowExp);
                    $valRowExp .= '}, 200);';
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, '].removeSuccess()') !== false) {
                    $valRowExp = str_replace($expEventCatch[0] . '.removeSuccess()', $mainSelector . '.on("change", "table[data-table-path=\'' . $expEventCatch[1] . '\'] > tbody > tr > td > .bp-remove-row", function(e){ eventDelay(function()', $valRowExp);
                    $valRowExp .= '}, 200);';
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, '].focus()') !== false) {
                    $valRowExp = str_replace($expEventCatch[0] . '.focus()', $mainSelector . '.on("focus", "input[data-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, '].dblclick()') !== false) {
                    $valRowExp = str_replace($expEventCatch[0] . '.dblclick()', $mainSelector . '.on("dblclick", "input[data-path=\'' . $expEventCatch[1] . '\']:not([readonly])", function(e)', $valRowExp);
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, '].sidebarchange()') !== false) {

                    if (($eventFieldMetaRow['LOOKUP_TYPE'] == 'combo' || $eventFieldMetaRow['LOOKUP_TYPE'] == 'combo_with_popup') && $eventFieldMetaRow['LOOKUP_META_DATA_ID'] != '') {
                        $valRowExp = str_replace($expEventCatch[0] . ".sidebarchange()", $mainSelector . '.on("change", "select[data-c-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    } elseif ($eventFieldMetaRow['LOOKUP_TYPE'] == 'radio' && $eventFieldMetaRow['LOOKUP_META_DATA_ID'] != '') {
                        $valRowExp = str_replace($expEventCatch[0] . ".sidebarchange()", $mainSelector . '.on("change", "input[data-c-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    } elseif ($eventFieldMetaRow['type'] == 'date' || $eventFieldMetaRow['type'] == 'datetime') {
                        $valRowExp = str_replace($expEventCatch[0] . ".sidebarchange()", $mainSelector . '.on("changeDate", "input[data-c-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    } elseif ($eventFieldMetaRow['type'] == 'description' || $eventFieldMetaRow['type'] == 'description_auto') {
                        $valRowExp = str_replace($expEventCatch[0] . ".sidebarchange()", $mainSelector . '.on("change", "textarea[data-c-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    } elseif ($eventFieldMetaRow['type'] == 'bigdecimal') {
                        $valRowExp = str_replace($expEventCatch[0] . ".sidebarchange(){", $mainSelector . '.on("change", "input[data-c-path=\'' . $expEventCatch[1] . '\']", function(e){ if(typeof $jthis.attr("data-prevent-change") !== "undefined"){return;}', $valRowExp);
                        $valRowExp = str_replace($expEventCatch[0] . ".sidebarchange() {", $mainSelector . '.on("change", "input[data-c-path=\'' . $expEventCatch[1] . '\']", function(e){ if(typeof $jthis.attr("data-prevent-change") !== "undefined"){return;}', $valRowExp);
                    } else {
                        $valRowExp = str_replace($expEventCatch[0] . ".sidebarchange()", $mainSelector . '.on("change", "input[data-c-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    }
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, '].searchNoResult()') !== false) {
                    $valRowExp = str_replace($expEventCatch[0] . '.searchNoResult()', $mainSelector . '.on("searchNoResult", "select[data-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, '].kpikeyup()') !== false) {
                    $kpiPathArr = explode('.', $expEventCatch[1]);

                    if (count($kpiPathArr) == 2) {
                        $valRowExp = str_replace($expEventCatch[0] . '.kpikeyup()', $mainSelector . '.on("keyup", "tr[data-dtl-code=\'' .  strtolower($kpiPathArr[0]) . '\'] [data-path=\'kpiDmDtl.' . $kpiPathArr[1] . '\']", function(e){ eventDelay(function()', $valRowExp);
                    } else {
                        $valRowExp = str_replace($expEventCatch[0] . '.kpikeyup()', $mainSelector . '.on("keyup", "[data-path=\'kpiDmDtl.' . $kpiPathArr[0] . '\']", function(e){ eventDelay(function()', $valRowExp);
                    }

                    $valRowExp .= '}, 250);';
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, '].kpichange()') !== false) {

                    $kpiPrefix = '';

                    if (Mdexpression::$kpiExpresssionPrefix) {
                        $kpiPrefix = Mdexpression::$kpiExpresssionPrefix;
                    }

                    $kpiPathArr = explode('.', $expEventCatch[1]);

                    if (count($kpiPathArr) == 2) {
                        $valRowExp = str_replace($expEventCatch[0] . '.kpichange()', $mainSelector . '.on("change", "tr[data-dtl-code=\'' .  strtolower($kpiPathArr[0]) . '\'] [data-path=\'' . $kpiPrefix . 'kpiDmDtl.' . $kpiPathArr[1] . '\']", function(e){ eventDelay(function()', $valRowExp);
                    } else {
                        $valRowExp = str_replace($expEventCatch[0] . '.kpichange()', $mainSelector . '.on("change", "[data-path=\'kpiDmDtl.' . $kpiPathArr[0] . '\']", function(e){ eventDelay(function()', $valRowExp);
                    }

                    $valRowExp .= '}, 5);';
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, '].kpiclick()') !== false) {

                    $kpiPrefix = '';

                    if (Mdexpression::$kpiExpresssionPrefix) {
                        $kpiPrefix = Mdexpression::$kpiExpresssionPrefix;
                    }

                    $kpiPathArr = explode('.', $expEventCatch[1]);

                    if (count($kpiPathArr) == 2) {
                        $valRowExp = str_replace($expEventCatch[0] . '.kpiclick()', $mainSelector . '.on("click", "tr[data-dtl-code=\'' .  strtolower($kpiPathArr[0]) . '\'] [data-path=\'' . $kpiPrefix . 'kpiDmDtl.' . $kpiPathArr[1] . '\']", function(e)', $valRowExp);
                    } else {
                        $valRowExp = str_replace($expEventCatch[0] . '.kpiclick()', $mainSelector . '.on("click", "[data-path=\'kpiDmDtl.' . $kpiPathArr[0] . '\']", function(e)', $valRowExp);
                    }
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, '].kpiColumnChange()') !== false) {

                    $valRowExp = str_replace($expEventCatch[0] . ".kpiColumnChange(){", $mainSelector . '.on("change", "[data-col-path=\'' . $expEventCatch[1] . '\']", function(e){ if(typeof $jthis.attr("data-prevent-change") !== "undefined"){return;}', $valRowExp);
                    $valRowExp = str_replace($expEventCatch[0] . ".kpiColumnChange() {", $mainSelector . '.on("change", "[data-col-path=\'' . $expEventCatch[1] . '\']", function(e){ if(typeof $jthis.attr("data-prevent-change") !== "undefined"){return;}', $valRowExp);

                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, '].kpiRowChange()') !== false) {

                    $valRowExp = str_replace($expEventCatch[0] . ".kpiRowChange(){", $mainSelector . '.on("change", "tr[data-dtl-code=\'' .  strtolower($expEventCatch[1]) . '\'] input[data-path]", function(e){ if(typeof $jthis.attr("data-prevent-change") !== "undefined"){return;}', $valRowExp);
                    $valRowExp = str_replace($expEventCatch[0] . ".kpiRowChange() {", $mainSelector . '.on("change", "tr[data-dtl-code=\'' .  strtolower($expEventCatch[1]) . '\'] input[data-path]", function(e){ if(typeof $jthis.attr("data-prevent-change") !== "undefined"){return;}', $valRowExp);

                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, '].kpiCellChange()') !== false) {

                    $valRowExp = str_replace($expEventCatch[0] . ".kpiCellChange(){", $mainSelector . '.on("change", "[data-path-cell=\'' . $expEventCatch[1] . '\']", function(e){ if(typeof $jthis.attr("data-prevent-change") !== "undefined"){return;}', $valRowExp);
                    $valRowExp = str_replace($expEventCatch[0] . ".kpiCellChange() {", $mainSelector . '.on("change", "[data-path-cell=\'' . $expEventCatch[1] . '\']", function(e){ if(typeof $jthis.attr("data-prevent-change") !== "undefined"){return;}', $valRowExp);

                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, '].kpiWizardNext()') !== false) {

                    $valRowExp = str_replace($expEventCatch[0] . '.kpiWizardNext(){', $mainSelector . ".on('click', '.wizard[data-step=\"" . ($expEventCatch[1] - 1) . "\"] .actions a[href=\"#next\"]', function(e){ ", $valRowExp);

                    $valRowExp .= 'e.preventDefault();';
                    $valRowExp .= 'e.stopPropagation();';
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, '].kpiWizardPrev()') !== false) {

                    $valRowExp = str_replace($expEventCatch[0] . '.kpiWizardPrev(){', $mainSelector . ".on('click', '.wizard[data-step=\"" . ($expEventCatch[1] - 1) . "\"] .actions a[href=\"#previous\"]', function(e){ ", $valRowExp);

                    $valRowExp .= 'e.preventDefault();';
                    $valRowExp .= 'e.stopPropagation();';
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, '].rowsButtonClick()') !== false) {
                    $valRowExp = str_replace($expEventCatch[0] . '.rowsButtonClick()', $mainSelector . '.on("click", "a[data-b-path*=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    $valRowExp .= '});';
                }
            }

            if (strpos($valRowExp, 'saveadd.click()') !== false) { /* Хадгалаад нэмэх */
                $valRowExp = str_replace('saveadd.click()', $mainSelector . '.on("change", "input#saveAddEventInput", function(e)', $valRowExp);
                $valRowExp .= "});";
            }

            if (strpos($valRowExp, 'allcontrol.change()') !== false) { /* Нээгдэж байгаа процессын бүх контролын change */
                $valRowExp = str_replace('allcontrol.change()', $mainSelector . '.on("change", "input, select, textarea", function(e)', $valRowExp);
                $valRowExp .= "});";
            }

            if (strpos($valRowExp, 'template.change()') !== false) { /* BP Template change */
                $valRowExp = str_replace('template.change()', $mainSelector . '.on("change", "select#bpTemplateId_' . $processId . '", function(e)', $valRowExp);
                $valRowExp .= "});";
            }

            if (strpos($valRowExp, 'subdtl.save()') !== false) { /* Level 2 rows ийг хадгалах товчны event */
                $valRowExp = str_replace('subdtl.save()', $mainSelector . '.on("change", ".bp-btn-subdtl", function(e)', $valRowExp);
                $valRowExp .= "});";
            }

            if (strpos($valRowExp, 'process.afterCloseNotSave()') !== false) { /* Процессийн хаах товчны event */
                $valRowExp = str_replace('process.afterCloseNotSave(){', $mainSelector . '.closest(".ui-dialog-content").on("dialogclose", function(e){ if(typeof e.originalEvent != "undefined" && typeof (e.originalEvent.type) != "undefined" && e.originalEvent.type == \'click\'){ ', $valRowExp);
                $valRowExp .= '} ';
                $valRowExp .= "});";
            }

            if (strpos($valRowExp, 'subkpi.save()') !== false) { /* Rows доторхи subKpi формын хадгалах товчны event */
                $valRowExp = str_replace('subkpi.save()', $mainSelector . '.on("change", ".bp-btn-subkpi", function(e)', $valRowExp);
                $valRowExp .= "});";
            }

            $expressionToJs .= $valRowExp;
        }

        preg_match_all('/\[([^\]]*)\].val\(\)/', $expressionToJs, $getValPath);

        if (!empty($getValPath[0])) {
            foreach ($getValPath[0] as $vgk => $valGetPathLast) {
                if (!empty($valGetPathLast)) {
                    $getMetaRowL = $this->model->getMetaTypeCode($processId, $getValPath[1][$vgk]);
                    $expressionToJs = self::fullExpressionConvertGet($getMetaRowL['type'], $getMetaRowL, $mainSelector, $getMetaRowL['sidebarName'], $valGetPathLast, $getValPath[1][$vgk], $expressionToJs, $processActionType);
                }
            }
        }

        preg_match_all('/sum\(\[(.*?)\]\)/i', $expressionToJs, $sumAggregate); // aggregate (sum)
        preg_match_all('/avg\(\[(.*?)\]\)/i', $expressionToJs, $avgAggregate); // aggregate (avg)
        preg_match_all('/min\(\[(.*?)\]\)/i', $expressionToJs, $minAggregate); // aggregate (min)
        preg_match_all('/max\(\[(.*?)\]\)/i', $expressionToJs, $maxAggregate); // aggregate (max)

        if (count($sumAggregate[1]) > 0) {
            foreach ($sumAggregate[1] as $s => $sv) {
                $getMetaRowAggr = $this->model->getMetaTypeCode($processId, $sv);

                if (empty($getMetaRowAggr['sidebarName'])) {
                    if ($getMetaRowAggr['type'] == 'bigdecimal') {
                        if (count(explode('.', $sv)) >= 3) {
                            $expressionToJs = str_replace($sumAggregate[0][$s], 'getBpBigDecimalFieldSum(\'' . $sv . '\', checkElement, ' . $mainSelector . ')', $expressionToJs);
                        } else {
                            $expressionToJs = str_replace($sumAggregate[0][$s], 'setNumberToFixed($("input[data-path=\'' . $sv . '_bigdecimal\']:not([data-not-aggregate])", ' . $mainSelector . ').sum())', $expressionToJs);
                        }
                    } elseif ($getMetaRowAggr['type'] == 'integer' || $getMetaRowAggr['type'] == 'long') {
                        if (count(explode('.', $sv)) >= 3) {
                            $expressionToJs = str_replace($sumAggregate[0][$s], 'getBpIntegerFieldSum(\'' . $sv . '\', checkElement, ' . $mainSelector . ')', $expressionToJs);
                        } else {
                            $expressionToJs = str_replace($sumAggregate[0][$s], 'setNumberToFixed($("input[data-path=\'' . $sv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').sum())', $expressionToJs);
                        }
                    } else {
                        $expressionToJs = str_replace($sumAggregate[0][$s], 'Number($("input[data-path=\'' . $sv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').sum())', $expressionToJs);
                    }
                } else {
                    if ($getMetaRowAggr['type'] == 'bigdecimal') {
                        $expressionToJs = str_replace($sumAggregate[0][$s], '$("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $sv . '_bigdecimal\']:not([data-not-aggregate])", ' . $mainSelector . ').length > 0 ? setNumberToFixed($("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $sv . '_bigdecimal\']:not([data-not-aggregate])", ' . $mainSelector . ').sum()) : setNumberToFixed($("input[data-path=\'' . $sv . '_bigdecimal\']:not([data-not-aggregate])", ' . $mainSelector . ').sum())', $expressionToJs);
                    } else {
                        $expressionToJs = str_replace($sumAggregate[0][$s], '$("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $sv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').length > 0 ? Number($("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $sv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').sum()) : Number($("input[data-path=\'' . $sv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').sum())', $expressionToJs);
                    }
                }
            }
        }
        if (count($avgAggregate[1]) > 0) {
            foreach ($avgAggregate[1] as $a => $av) {

                $getMetaRowAggr = $this->model->getMetaTypeCode($processId, $av);

                if (empty($getMetaRowAggr['sidebarName']))
                    $expressionToJs = str_replace($avgAggregate[0][$a], 'Number($("input[data-path=\'' . $av . '\']:not([data-not-aggregate])", ' . $mainSelector . ').avg())', $expressionToJs);
                else
                    $expressionToJs = str_replace($avgAggregate[0][$a], '$("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $av . '\']:not([data-not-aggregate])", ' . $mainSelector . ').length > 0 ? Number($("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $av . '\']:not([data-not-aggregate])", ' . $mainSelector . ').avg()) : Number($("input[data-path=\'' . $av . '\']:not([data-not-aggregate])", ' . $mainSelector . ').avg())', $expressionToJs);
            }
        }
        if (count($minAggregate[1]) > 0) {
            foreach ($minAggregate[1] as $m => $mv) {

                $getMetaRowAggr = $this->model->getMetaTypeCode($processId, $mv);

                if (empty($getMetaRowAggr['sidebarName']))
                    $expressionToJs = str_replace($minAggregate[0][$m], 'Number($("input[data-path=\'' . $mv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').min())', $expressionToJs);
                else
                    $expressionToJs = str_replace($minAggregate[0][$m], '$("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $mv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').length > 0 ? Number($("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $mv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').min()) : Number($("input[data-path=\'' . $mv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').min())', $expressionToJs);
            }
        }
        if (count($maxAggregate[1]) > 0) {
            foreach ($maxAggregate[1] as $ma => $mav) {

                $getMetaRowAggr = $this->model->getMetaTypeCode($processId, $mav);

                if (empty($getMetaRowAggr['sidebarName']))
                    $expressionToJs = str_replace($maxAggregate[0][$ma], 'Number($("input[data-path=\'' . $mav . '\']:not([data-not-aggregate])", ' . $mainSelector . ').max())', $expressionToJs);
                else
                    $expressionToJs = str_replace($maxAggregate[0][$ma], '$("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $mav . '\']:not([data-not-aggregate])", ' . $mainSelector . ').length > 0 ? Number($("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $mav . '\']:not([data-not-aggregate])", ' . $mainSelector . ').max()) : Number($("input[data-path=\'' . $mav . '\']:not([data-not-aggregate])", ' . $mainSelector . ').max())', $expressionToJs);
            }
        }

        $expressionToJs = str_ireplace(array('function(e){', 'function(e) {'), 'function(e){ var _this = this; var $jthis = $(_this); ', $expressionToJs);
        $expressionToJs = str_ireplace('$(this)', '$jthis', $expressionToJs);
        $expressionToJs = str_ireplace(array('(this)', '( this)', '(this )', '( this )'), '(_this)', $expressionToJs);
        $expressionToJs = str_replace('checkElement', '$jthis', $expressionToJs);
        $expressionToJs = str_replace(', this)', ', _this)', $expressionToJs);

        $expressionToJs = self::endReplaceResolver($expressionToJs);

        return $expressionToJs;
    }

    public function fullExpressionConvertWithoutEvent($fullExpression = '', $processId = '', $processActionType = '', $isFindFunction = false, $expression_type = '') {

        if (empty($fullExpression)) {
            return '';
        }

        if (self::$isMultiPathConfig == false) {
            self::setMultiPathConfig($processId);
        } else {
            $this->load->model('mdexpression', 'middleware/models/');
        }
        
        if (($expression_type == 'var_fnc' || $expression_type == 'load') && Mdexpression::$enableDisable) {
            
            if ($expression_type == 'var_fnc') {
                
                if (isset(Mdexpression::$enableDisable['enable'])) {
                
                    $enables = Mdexpression::$enableDisable['enable'];
                    $enablesPath = [];

                    foreach ($enables as $enableRows) {
                        foreach ($enableRows as $enableRow) {
                            $enablesPath[] = $enableRow['fullPath'];
                        }
                    }

                    $bracketsEnablePaths = '[' . implode(', ', $enablesPath) . '].disable();';
                    $fullExpression = $fullExpression . "\n" . $bracketsEnablePaths;
                }          

                if (isset(Mdexpression::$enableDisable['disable'])) {

                    $disables = Mdexpression::$enableDisable['disable'];
                    $disablesPath = [];

                    foreach ($disables as $disableRows) {
                        foreach ($disableRows as $disableRow) {
                            $disablesPath[] = $disableRow['fullPath'];
                        }
                    }

                    $bracketsDisablePaths = '[' . implode(', ', $disablesPath) . '].disable();';
                    $fullExpression = $fullExpression . "\n" . $bracketsDisablePaths;
                }            
                
            } else {
                
                if (isset(Mdexpression::$enableDisable['enable']['detail'])) {
                    
                    $enableDetails = Mdexpression::$enableDisable['enable']['detail'];
                    $enableDetailsGrouped = Arr::groupByArray($enableDetails, 'groupPath');
                    $enableExpression = null;
                    
                    foreach ($enableDetailsGrouped as $enableGroupPath => $enableGroupRow) {
                        $enableExpression .= "if (groupPath == '$enableGroupPath') {" . "\n";
                            $enableExpression .= '['.Arr::implode_key(',', $enableGroupRow['rows'], 'fullPath', true).'].enable();' . "\n";
                        $enableExpression .= "}" . "\n";
                    }
                    
                    $fullExpression = $fullExpression . "\n" . $enableExpression;
                }
                
                if (isset(Mdexpression::$enableDisable['disable']['detail'])) {
                    
                    $disableDetails = Mdexpression::$enableDisable['disable']['detail'];
                    $disableDetailsGrouped = Arr::groupByArray($disableDetails, 'groupPath');
                    $disableExpression = null;
                    
                    foreach ($disableDetailsGrouped as $disableGroupPath => $disableGroupRow) {
                        $disableExpression .= "if (groupPath == '$disableGroupPath') {" . "\n";
                            $disableExpression .= '['.Arr::implode_key(',', $disableGroupRow['rows'], 'fullPath', true).'].disable();' . "\n";
                        $disableExpression .= "}" . "\n";
                    }
                    
                    $fullExpression = $fullExpression . "\n" . $disableExpression;
                }
            }
        }

        $mainSelector = self::$setMainSelector ? self::$setMainSelector : self::$mainSelector . $processId;

        $fullExpression = self::startReplaceResolver($fullExpression);

        if ($isFindFunction) {

            preg_match_all('/function[\s\n]+(\S+)[\s\n]*\(/', $fullExpression, $parseFunctionNames);

            if (!empty($parseFunctionNames[0])) {

                $fncNamesArray = array();

                foreach ($parseFunctionNames[0] as $fk => $fn) {
                    $fncName = $parseFunctionNames[1][$fk];
                    $fullExpression = str_replace($fn, 'function ' . $fncName . '_' . $processId . '(', $fullExpression);
                    $fncNamesArray[$fncName] = $fncName . '_' . $processId;
                }

                $keys = array_map('strlen', array_keys($fncNamesArray));
                array_multisort($keys, SORT_DESC, $fncNamesArray);

                Mdexpression::$detectedFunctionNames = $fncNamesArray;
                $isFindFunction = false;
            }
        }

        if (!$isFindFunction && Mdexpression::$detectedFunctionNames) {
            $fullExpression = self::detectedFunctionNamesReplacer($fullExpression);
        }

        $fullExpression = self::fullExpressionReplaceFncNames($processId, $mainSelector, $fullExpression);

        preg_match('/\[(.*?)\]/', $fullExpression, $expEventCatch);
        preg_match_all('/(?<![a-zA-Z0-9\[+])\[[\w.]+\]\s*=\s*[\[|\(\'\w.]+(.*)/', $fullExpression, $parseExpressionEqual);
        preg_match_all('/\[(.*?)\].hide\(\)|\[(.*?)\].softhide\(\)|\[(.*?)\].show\(\)|\[(.*?)\].disable\(\)|\[(.*?)\].enable\(\)|\[(.*?)\].required\(\)|\[(.*?)\].nonrequired\(\)|\[(.*?)\].hideAll\(\)|\[(.*?)\].showAll\(\)|\[(.*?)\].disableAll\(\)|\[(.*?)\].enableAll\(\)|\[(.*?)\].requiredAll\(\)|\[(.*?)\].nonrequiredAll\(\)|\[(.*?)\].empty\(\)/', $fullExpression, $parseExpressionlAttr);
        preg_match_all('/\[[\w.]+\](.*)\s*(==|===|!=|!==|>|<|>=|<=)\s*[\[\w]*(.*)/', $fullExpression, $parseExpressionEqualEqual);
        preg_match_all('/message\((.*)+\)/', $fullExpression, $parseExpressionMessage);
        preg_match_all('/fiscalPeriodMessage\((.*)+\)/', $fullExpression, $parseExpressionFiscalPeriodMessage);
        preg_match_all('/saveConfirm\((.*)+\)/', $fullExpression, $parseExpressionSaveConfirm);
        preg_match_all('/\[[\w.]+\](.label|.control)\((.*)\)/', $fullExpression, $parseExpressionStyle);
        preg_match_all('/\[[\w.]+\].trigger\((.*)\)/', $fullExpression, $parseExpressionTrigger);
        preg_match_all('/\[[\w.]+\].rowTrigger\((.*)\)/', $fullExpression, $parseExpressionTriggerRow);
        preg_match_all('/\[[\w.]+\].focus\(\);/', $fullExpression, $parseExpressionFocus);

        // <editor-fold defaultstate="collapsed" desc="CONVERT JS WITHOUT EVENT">
        if (is_array($parseExpressionEqual) && isset($parseExpressionEqual[0]) && !empty($parseExpressionEqual[0])) {
            foreach ($parseExpressionEqual[0] as $expVal) {

                $expExplode = explode('=', $expVal);
                preg_match('/\[(.*?)\]/', $expExplode[0], $expSet);
                preg_match_all('/\[(.*?)\]/', $expExplode[1], $expGet);
                $exp = trim(str_replace(';', '', $expExplode[1]));

                $getMetaRow = $this->model->getMetaTypeCode($processId, $expSet[1]);
                $typeCode = $getMetaRow['type'];
                $isAcceptTranslate = false;
                
                if ($getMetaRow['IS_TRANSLATE'] == '1' && !empty($expGet[1]) && count($expGet[1]) == 1) {
                    $getMetaRowGet = $this->model->getMetaTypeCode($processId, $expGet[1][0]);
                    if ($getMetaRowGet['IS_TRANSLATE'] == '1') {
                        $getFieldValRemove = str_replace($expGet[0][0] . '.val()', '', $exp);
                        if (!$getFieldValRemove) {
                            $isAcceptTranslate = true;
                        }
                    }
                }

                $getReplace = $exp;

                if (!empty($expGet[1]) && !$isAcceptTranslate) {
                    foreach ($expGet[1] as $key => $valGetPath) {
                        if (strpos($expExplode[1], '.val()') !== false) {
                            $getMetaRow2 = $this->model->getMetaTypeCode($processId, $valGetPath);
                            $exp = self::fullExpressionWithoutEventConvertGet($getMetaRow2['type'], $getMetaRow2, $mainSelector, $getMetaRow2['sidebarName'], $expGet[0][$key] . '.val()', $valGetPath, $exp, $processActionType);
                        }
                    }
                }

                $getReplaced = strtr($getReplace, array("(" => "\(", ")" => "\)", "|" => "\|", "'" => "\'", "*" => "\*", "[" => "\[", "]" => "\]", "/" => "\/", "+" => "\+", "=" => "\=", ":" => "\:"));

                if ($isAcceptTranslate) {

                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpTranslateFieldVal(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSet[1] . '\', getBpElement(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expGet[1][0] . '\'));', $fullExpression);

                    continue;
                }

                if ($getMetaRow['parentId'] != '') {

                    if ($processActionType === 'view') {

                        if ($typeCode == 'boolean') {
                            $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'checkboxCheckerUpdate(getBpElement(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSet[1] . '\'), ' . $getReplace . ')', $fullExpression);
                        } else {
                            if ($getMetaRow['sidebarName'] != '') {
                                if ($typeCode == 'bigdecimal' && $getMetaRow['isShow'] == '1') {
                                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpRowParamBigdecimalSidebar(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSet[1] . '\', (' . $exp . '));', $fullExpression);
                                } else {
                                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpRowParamNumSidebar(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSet[1] . '\', (' . $exp . '));', $fullExpression);
                                }
                            } else {
                                if ($typeCode == 'bigdecimal' && $getMetaRow['isShow'] == '1') {
                                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpRowParamBigdecimal(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSet[1] . '\', (' . $exp . '));', $fullExpression);
                                } else {
                                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpRowParamView(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSet[1] . '\', (' . $exp . '));', $fullExpression);
                                }
                            }
                        }
                    } else {

                        if ($typeCode == 'boolean') {
                            $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'checkboxCheckerUpdate(getBpElement(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSet[1] . '\'), ' . $getReplace . ')', $fullExpression);
                        } elseif ($typeCode == 'label' && $getMetaRow['isShow'] == '1') {
                            $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpRowParamLabel(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSet[1] . '\', (' . $exp . '));', $fullExpression);
                        } else {
                            if ($getMetaRow['sidebarName'] != '') {
                                if ($typeCode == 'bigdecimal' && $getMetaRow['isShow'] == '1') {
                                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpRowParamBigdecimalSidebar(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSet[1] . '\', (' . $exp . '));', $fullExpression);
                                } else {
                                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpRowParamNumSidebar(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSet[1] . '\', (' . $exp . '));', $fullExpression);
                                }
                            } else {
                                if ($typeCode == 'bigdecimal' && $getMetaRow['isShow'] == '1') {
                                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpRowParamBigdecimal(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSet[1] . '\', (' . $exp . '));', $fullExpression);
                                } elseif ($typeCode == 'text_editor' && $getMetaRow['isShow'] == '1') {
                                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setBpHdrParamTextEditor(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '))', $fullExpression);
                                } else {
                                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpRowParamNum(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSet[1] . '\', (' . $exp . '));', $fullExpression);
                                }
                            }
                        }
                    }
                } else {

                    if ($processActionType === 'view') {
                        if ($getMetaRow['LOOKUP_TYPE'] != '' && $getMetaRow['LOOKUP_META_DATA_ID'] != '') {
                            $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpRowParamNum(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSet[1] . '\', (' . $exp . '));', $fullExpression);
                        } elseif ($getMetaRow['isShow'] != '1' || $typeCode == 'qrcode') {
                            $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', '$("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').val(' . $exp . ')', $fullExpression);
                        } else {
                            $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', '$("span[data-view-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').text(' . $exp . ')', $fullExpression);
                        }
                    } else {
                        if ($typeCode == 'boolean' && $getMetaRow['isShow'] == '1') {
                            $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'checkboxCheckerUpdate($("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . '), ' . $getReplace . ')', $fullExpression);
                        } elseif ($typeCode == 'date' && $getMetaRow['isShow'] == '1') {
                            $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setBpHdrParamDate(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '))', $fullExpression);
                        } elseif ($typeCode == 'datetime' && $getMetaRow['isShow'] == '1') {
                            $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setBpHdrParamDateTime(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '))', $fullExpression);
                        } elseif ($typeCode == 'label' && $getMetaRow['isShow'] == '1') {
                            $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setBpHdrParamLabel(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '))', $fullExpression);
                        } elseif ($typeCode == 'text_editor' && $getMetaRow['isShow'] == '1') {
                            $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setBpHdrParamTextEditor(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '))', $fullExpression);
                        } elseif ($typeCode == 'rule_expression' && $getMetaRow['isShow'] == '1') {
                            $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setBpHdrParamRuleExpression(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '))', $fullExpression);
                        } else {

                            if ($getMetaRow['LOOKUP_TYPE'] != '' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['isShow'] == '1') {

                                if ($getMetaRow['LOOKUP_TYPE'] == 'radio') {

                                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'radioButtonCheckerUpdate($("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . '), ' . $getReplace . ')', $fullExpression);
                                } elseif (($getMetaRow['LOOKUP_TYPE'] == 'combo' || $getMetaRow['LOOKUP_TYPE'] == 'combo_with_popup') && ($getMetaRow['CHOOSE_TYPE'] == 'multi' || $getMetaRow['CHOOSE_TYPE'] == 'multicomma')) {

                                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpHdrMultipleCombo(' . $mainSelector . ', \'open\', \'' . $expSet[1] . '\', (' . $exp . '));', $fullExpression);
                                } elseif ($getMetaRow['LOOKUP_TYPE'] == 'popup') {

                                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setLookupPopupValue($("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . '), ' . $exp . ')', $fullExpression);
                                } elseif ($getMetaRow['LOOKUP_TYPE'] == 'combogrid') {

                                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setLookupComboGridValue($("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . '), ' . $exp . ')', $fullExpression);
                                } elseif ($getMetaRow['LOOKUP_TYPE'] == 'range_slider') {

                                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setBpHdrRangeSlider(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '))', $fullExpression);
                                } else {
                                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpRowParamNum(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSet[1] . '\', (' . $exp . '));', $fullExpression);
                                }
                            } elseif (($typeCode == 'description' || $typeCode == 'description_auto' || $typeCode == 'expression_editor' || $typeCode == 'base64_to_file' || $typeCode == 'clob') && $getMetaRow['isShow'] == '1') {
                                $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', '$("textarea[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').val(' . $exp . ')', $fullExpression);
                            } elseif (($typeCode == 'decimal' || $typeCode == 'number' || $typeCode == 'integer') && $getMetaRow['isShow'] == '1') {
                                $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpHdrParamInteger(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '));', $fullExpression);
                            } elseif ($typeCode == 'bigdecimal' && $getMetaRow['isShow'] == '1') {
                                $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpHdrParamNum(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '));', $fullExpression);
                            } else {
                                $fullExpression = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpHdrParamString(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '));', $fullExpression);
                            }
                        }
                    }
                }
            }
        }

        if (!empty($parseExpressionEqualEqual[0])) {
            foreach ($parseExpressionEqualEqual[0] as $expVal) {
                preg_match_all('/\[(.*?)\]/', $expVal, $expGet);
                $expValVar = $expVal;

                if (!empty($expGet[1])) {
                    foreach ($expGet[1] as $key => $valGetPath) {
                        $getMetaRow = $this->model->getMetaTypeCode($processId, $valGetPath);
                        $expVal = self::fullExpressionWithoutEventConvertGet($getMetaRow['type'], $getMetaRow, $mainSelector, $getMetaRow['sidebarName'], $expGet[0][$key] . '.val()', $valGetPath, $expVal, $processActionType);
                    }
                }
                $fullExpression = str_replace($expValVar, $expVal, $fullExpression);
            }
        }

        if (!empty($parseExpressionlAttr[0])) {
            foreach ($parseExpressionlAttr[0] as $expVal) {
                preg_match('/\[(.*?)\]/', $expVal, $expSetAttr);

                if (strpos($expSetAttr[1], ',') !== false) {

                    $expSetAttrExpression = '';
                    $expSetAttrSplitArr = explode(',', $expSetAttr[1]);

                    if (strpos($expVal, '.hide()') !== false) {

                        foreach ($expSetAttrSplitArr as $expSetAttrSplit) {
                            $expSetAttrSplit = trim($expSetAttrSplit);
                            $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttrSplit);

                            if ($getMetaRow['parentId'] != '') {
                                $expSetAttrExpression .= 'setBpRowParamHide(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttrSplit . '\');';
                            } elseif ($getMetaRow['type'] == 'text_editor') {
                                $expSetAttrExpression .= '$("[data-cell-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').css({\'display\': \'none\'});';
                            } else {
                                $expSetAttrExpression .= '$("[data-path=\'' . $expSetAttrSplit . '\'], th[data-cell-path=\'' . $expSetAttrSplit . '\'], tr[data-cell-path=\'' . $expSetAttrSplit . '\'], td[data-cell-path=\'' . $expSetAttrSplit . '\'], label[data-label-path=\'' . $expSetAttrSplit . '\'], div[data-section-path=\'' . $expSetAttrSplit . '\'], div[data-cell-path=\'' . $expSetAttrSplit . '\'], li[data-li-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').css({\'display\': \'none\'});';
                            }
                        }

                        $fullExpression = str_replace($expSetAttr[0] . ".hide();", $expSetAttrExpression, $fullExpression);
                    } elseif (strpos($expVal, '.softhide()') !== false) {

                        foreach ($expSetAttrSplitArr as $expSetAttrSplit) {
                            $expSetAttrSplit = trim($expSetAttrSplit);
                            $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttrSplit);

                            if ($getMetaRow['parentId'] != '') {
                                $expSetAttrExpression .= 'setBpRowParamSoftHide(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttrSplit . '\');';
                            } else {
                                $expSetAttrExpression .= '$("label[data-label-path=\'' . $expSetAttrSplit . '\'], div[data-section-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').css({\'visibility\': \'hidden\'});';
                            }
                        }

                        $fullExpression = str_replace($expSetAttr[0] . ".softhide();", $expSetAttrExpression, $fullExpression);
                    } elseif (strpos($expVal, '.show()') !== false) {

                        foreach ($expSetAttrSplitArr as $expSetAttrSplit) {
                            $expSetAttrSplit = trim($expSetAttrSplit);
                            $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttrSplit);

                            if ($getMetaRow['parentId'] != '') {
                                $expSetAttrExpression .= 'setBpRowParamShow(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttrSplit . '\');';
                            } elseif ($getMetaRow['type'] == 'text_editor') {
                                $expSetAttrExpression .= '$("[data-cell-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').css({\'display\': \'\'});';
                            } else {
                                $expSetAttrExpression .= '$("[data-path=\'' . $expSetAttrSplit . '\'], th[data-cell-path=\'' . $expSetAttrSplit . '\'], tr[data-cell-path=\'' . $expSetAttrSplit . '\'], td[data-cell-path=\'' . $expSetAttrSplit . '\'], label[data-label-path=\'' . $expSetAttrSplit . '\'], div[data-section-path=\'' . $expSetAttrSplit . '\'], div[data-cell-path=\'' . $expSetAttrSplit . '\'], li[data-li-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').css({\'display\': \'\', \'visibility\': \'\'});';
                            }
                        }

                        $fullExpression = str_replace($expSetAttr[0] . '.show();', $expSetAttrExpression, $fullExpression);
                    } elseif (strpos($expVal, '.disable()') !== false) {

                        foreach ($expSetAttrSplitArr as $expSetAttrSplit) {

                            $expSetAttrSplit = trim($expSetAttrSplit);
                            $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttrSplit);

                            if ($getMetaRow['ABILITY_TOGGLE'] == 'enable') {

                                $expSetAttrExpression .= '';
                            } else {

                                if ($getMetaRow['type'] == 'group') {
                                    $expSetAttrExpression .= 'setBpRowGroupDisable(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                } else {
                                    if ($getMetaRow['parentId'] != '') {
                                        if ($getMetaRow['type'] == 'boolean') {
                                            $expSetAttrExpression .= 'setBpRowCheckboxDisable(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttrSplit . '\');';
                                        } elseif ($getMetaRow['type'] == 'button') {
                                            $expSetAttrExpression .= 'setBpRowButtonDisable(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttrSplit . '\');';
                                        } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'radio') {

                                            $expSetAttrExpression .= 'setBpRowRadioDisable(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttrSplit . '\');';
                                        } else {
                                            $expSetAttrExpression .= 'setBpRowParamDisable(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttrSplit . '\');';
                                        }
                                    } else {

                                        if ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && ($getMetaRow['LOOKUP_TYPE'] == 'combo' || $getMetaRow['LOOKUP_TYPE'] == 'combo_with_popup' || $getMetaRow['LOOKUP_TYPE'] == 'combogrid')) {

                                            $expSetAttrExpression .= 'setBpHeaderParamDisable(' . $mainSelector . ', \'' . $expSetAttrSplit . '\');';
                                        } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'radio') {

                                            $expSetAttrExpression .= 'setBpHeaderRadioDisable(' . $mainSelector . ', \'' . $expSetAttrSplit . '\');';
                                        } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && ($getMetaRow['LOOKUP_TYPE'] == 'popup' || $getMetaRow['LOOKUP_TYPE'] == 'combogrid')) {
                                            $expSetAttrExpression .= '
                                                $("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("input[type=\'text\']").attr({\'readonly\': \'readonly\', \'tabindex\': \'-1\'});
                                                $("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find(".input-group-btn > button:not([data-more-metaid])").attr(\'style\', \'pointer-events: none; background-color: #eeeeee !important\').prop(\'disabled\', true);';
                                        } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['type'] == 'boolean') {
                                            $expSetAttrExpression .= 'checkboxDisableUpdate($("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . '));';
                                        } elseif ($getMetaRow['type'] == 'button' && $getMetaRow['isShow'] == '1') {
                                            $expSetAttrExpression .= 'bpButtonDisable(\'' . $expSetAttrSplit . '\', ' . $mainSelector . ');';
                                        } elseif ($getMetaRow['type'] == 'text_editor' && $getMetaRow['isShow'] == '1') {
                                            if (Mdform::$isIndicatorRendering == false) {
                                                $expSetAttrExpression .= 'setTimeout(function(){ tinymce.get(\'param[' . $expSetAttrSplit . ']\').setMode(\'readonly\'); }, 1500);';
                                            } else {
                                                $expSetAttrExpression .= 'setTimeout(function(){ tinymce.get(\'mvParam[' . $expSetAttrSplit . ']\').setMode(\'readonly\'); }, 1500);';
                                            }
                                        } elseif ($getMetaRow['type'] == 'file' && $getMetaRow['isShow'] == '1') {
                                            $expSetAttrExpression .= 'setBpHeaderFileFieldDisable(' . $mainSelector . ', \'' . $expSetAttrSplit . '\');';
                                        } else {
                                            $expSetAttrExpression .= '$("[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').prop("readonly", true).attr("tabindex", "-1");';
                                        }
                                    }
                                }
                            }
                        }

                        $fullExpression = str_replace($expSetAttr[0] . '.disable();', $expSetAttrExpression, $fullExpression);
                    } elseif (strpos($expVal, '.enable()') !== false) {

                        foreach ($expSetAttrSplitArr as $expSetAttrSplit) {
                            $expSetAttrSplit = trim($expSetAttrSplit);
                            $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttrSplit);

                            if ($getMetaRow['ABILITY_TOGGLE'] == 'disable') {
                                $expSetAttrExpression .= '';
                            } else {

                                if ($getMetaRow['type'] == 'group') {
                                    $expSetAttrExpression .= 'setBpRowGroupEnable(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                } else {

                                    if ($getMetaRow['parentId'] != '') {
                                        if ($getMetaRow['type'] == 'boolean') {
                                            $expSetAttrExpression .= 'setBpRowCheckboxEnable(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttrSplit . '\');';
                                        } elseif ($getMetaRow['type'] == 'button') {
                                            $expSetAttrExpression .= 'setBpRowButtonEnable(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttrSplit . '\');';
                                        } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'radio') {

                                            $expSetAttrExpression .= 'setBpRowRadioEnable(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttrSplit . '\');';
                                        } else {
                                            $expSetAttrExpression .= 'setBpRowParamEnable(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttrSplit . '\');';
                                        }
                                    } elseif ($getMetaRow['type'] == 'boolean' && $getMetaRow['isShow'] == '1') {
                                        $expSetAttrExpression .= 'checkboxEnableUpdate($("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . '));';
                                    } elseif ($getMetaRow['type'] == 'button' && $getMetaRow['isShow'] == '1') {
                                        $expSetAttrExpression .= 'bpButtonEnable(\'' . $expSetAttrSplit . '\', ' . $mainSelector . ');';
                                    } elseif ($getMetaRow['type'] == 'text_editor' && $getMetaRow['isShow'] == '1') {
                                        if (Mdform::$isIndicatorRendering == false) {
                                            $expSetAttrExpression .= 'setTimeout(function(){ tinymce.get(\'param[' . $expSetAttrSplit . ']\').setMode(\'design\'); }, 1500);';
                                        } else {
                                            $expSetAttrExpression .= 'setTimeout(function(){ tinymce.get(\'mvParam[' . $expSetAttrSplit . ']\').setMode(\'design\'); }, 1500);';
                                        }
                                    } elseif ($getMetaRow['type'] == 'file' && $getMetaRow['isShow'] == '1') {

                                        $expSetAttrExpression .= 'setBpHeaderFileFieldEnable(' . $mainSelector . ', \'' . $expSetAttrSplit . '\');';
                                    } else {

                                        if ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && ($getMetaRow['LOOKUP_TYPE'] == 'combo' || $getMetaRow['LOOKUP_TYPE'] == 'combo_with_popup' || $getMetaRow['LOOKUP_TYPE'] == 'combogrid')) {

                                            $expSetAttrExpression .= 'setBpHeaderParamEnable(' . $mainSelector . ', \'' . $expSetAttrSplit . '\');';
                                        } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'radio') {

                                            $expSetAttrExpression .= 'setBpHeaderRadioEnable(' . $mainSelector . ', \'' . $expSetAttrSplit . '\');';
                                        } else {

                                            $expSetAttrExpression .= '$("input[data-path=\'' . $expSetAttrSplit . '\'], textarea[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').removeAttr("readonly tabindex");';
                                            $expSetAttrExpression .= 'if($("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").length > 0) {
                                                            $("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("input[type=\'text\']").removeAttr(\'readonly tabindex\');
                                                            $("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("button").removeAttr(\'style\').prop(\'disabled\', false);                                
                                                            }';
                                        }
                                    }
                                }
                            }
                        }

                        $fullExpression = str_replace($expSetAttr[0] . '.enable();', $expSetAttrExpression, $fullExpression);
                    } elseif (strpos($expVal, '.required()') !== false) {

                        foreach ($expSetAttrSplitArr as $expSetAttrSplit) {
                            $expSetAttrSplit = trim($expSetAttrSplit);
                            $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttrSplit);

                            if ($getMetaRow['parentId'] != '') {
                                $expSetAttrExpression .= 'setBpRowParamRequired(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttrSplit . '\');';
                            } else {
                                $expSetAttrExpression .= 'bpSetHeaderParamRequired(' . $mainSelector . ', \'' . $expSetAttrSplit . '\');';
                            }
                        }

                        $fullExpression = str_replace($expSetAttr[0] . '.required();', $expSetAttrExpression, $fullExpression);
                    } elseif (strpos($expVal, '.nonrequired()') !== false) {

                        foreach ($expSetAttrSplitArr as $expSetAttrSplit) {
                            $expSetAttrSplit = trim($expSetAttrSplit);
                            $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttrSplit);

                            if ($getMetaRow['parentId'] != '') {
                                $expSetAttrExpression .= 'setBpRowParamNonRequired(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttrSplit . '\');';
                            } else {
                                $expSetAttrExpression .= 'bpSetHeaderParamNonRequired(' . $mainSelector . ', \'' . $expSetAttrSplit . '\');';
                            }
                        }

                        $fullExpression = str_replace($expSetAttr[0] . ".nonrequired();", $expSetAttrExpression, $fullExpression);
                    } elseif (strpos($expVal, '.hideAll()') !== false) {

                        $fullExpression = str_replace($expSetAttr[0] . ".hideAll();", 'bpDetailHideAll(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');', $fullExpression);
                    } elseif (strpos($expVal, '.showAll()') !== false) {

                        $fullExpression = str_replace($expSetAttr[0] . ".showAll();", 'bpDetailShowAll(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');', $fullExpression);
                    } elseif (strpos($expVal, '.disableAll()') !== false) {

                        $fullExpression = str_replace($expSetAttr[0] . ".disableAll();", 'bpDetailDisableAll(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');', $fullExpression);
                    } elseif (strpos($expVal, '.enableAll()') !== false) {

                        $fullExpression = str_replace($expSetAttr[0] . ".enableAll();", 'bpDetailEnableAll(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');', $fullExpression);
                    }
                } else {
                    $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttr[1]);
                    $typeCode = $getMetaRow['type'];

                    if (strpos($expVal, '.hide()') !== false) {

                        if ($getMetaRow['parentId'] != '') {
                            $fullExpression = str_replace($expSetAttr[0] . ".hide();", 'setBpRowParamHide(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttr[1] . '\');', $fullExpression);
                        } elseif ($getMetaRow['type'] == 'text_editor') {
                            $fullExpression = str_replace($expSetAttr[0] . ".hide();", '$("[data-cell-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').css({\'display\': \'none\'});', $fullExpression);
                        } else {
                            $fullExpression = str_replace($expSetAttr[0] . ".hide();", '$("[data-path=\'' . $expSetAttr[1] . '\'], th[data-cell-path=\'' . $expSetAttr[1] . '\'], tr[data-cell-path=\'' . $expSetAttr[1] . '\'], td[data-cell-path=\'' . $expSetAttr[1] . '\'], label[data-label-path=\'' . $expSetAttr[1] . '\'], div[data-cell-path=\'' . $expSetAttr[1] . '\'], div[data-section-path=\'' . $expSetAttr[1] . '\'], li[data-li-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').css({\'display\': \'none\'});', $fullExpression);
                        }
                    } elseif (strpos($expVal, '.softhide()') !== false) {

                        if ($getMetaRow['parentId'] != '') {
                            $fullExpression = str_replace($expSetAttr[0] . ".softhide();", 'setBpRowParamSoftHide(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttr[1] . '\');', $fullExpression);
                        } else {
                            $fullExpression = str_replace($expSetAttr[0] . ".softhide();", '$("label[data-label-path=\'' . $expSetAttr[1] . '\'], div[data-section-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').css({\'visibility\': \'hidden\'});', $fullExpression);
                        }
                    } elseif (strpos($expVal, '.show()') !== false) {

                        if ($getMetaRow['parentId'] != '') {
                            $fullExpression = str_replace($expSetAttr[0] . ".show();", 'setBpRowParamShow(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttr[1] . '\');', $fullExpression);
                        } elseif ($getMetaRow['type'] == 'text_editor') {
                            $fullExpression = str_replace($expSetAttr[0] . ".show();", '$("[data-cell-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').css({\'display\': \'\'});', $fullExpression);
                        } else {
                            $fullExpression = str_replace($expSetAttr[0] . ".show();", '$("[data-path=\'' . $expSetAttr[1] . '\'], th[data-cell-path=\'' . $expSetAttr[1] . '\'], tr[data-cell-path=\'' . $expSetAttr[1] . '\'], td[data-cell-path=\'' . $expSetAttr[1] . '\'], label[data-label-path=\'' . $expSetAttr[1] . '\'], div[data-cell-path=\'' . $expSetAttr[1] . '\'], div[data-section-path=\'' . $expSetAttr[1] . '\'], li[data-li-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').css({\'display\': \'\', \'visibility\': \'\'});', $fullExpression);
                        }
                    } elseif (strpos($expVal, '.hideAll()') !== false) {

                        $fullExpression = str_replace($expSetAttr[0] . ".hideAll();", '$("th[data-cell-path=\'' . $expSetAttr[1] . '\'], td[data-cell-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').hide();', $fullExpression);
                    } elseif (strpos($expVal, '.showAll()') !== false) {

                        $fullExpression = str_replace($expSetAttr[0] . ".showAll();", '$("th[data-cell-path=\'' . $expSetAttr[1] . '\'], td[data-cell-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').show();', $fullExpression);
                    } elseif (strpos($expVal, '.disable()') !== false) {

                        if ($getMetaRow['parentId'] != '') {

                            if ($getMetaRow['ABILITY_TOGGLE'] == 'enable') {
                                $fullExpression = str_replace($expSetAttr[0] . ".disable();", '', $fullExpression);
                            } else {

                                if ($typeCode == 'boolean' && $getMetaRow['isShow'] == '1') {
                                    $fullExpression = str_replace($expSetAttr[0] . ".disable();", 'setBpRowCheckboxDisable(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttr[1] . '\');', $fullExpression);
                                } elseif ($typeCode == 'button' && $getMetaRow['isShow'] == '1') {
                                    $fullExpression = str_replace($expSetAttr[0] . ".disable();", 'setBpRowButtonDisable(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttr[1] . '\');', $fullExpression);
                                } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'radio') {

                                    $fullExpression = str_replace($expSetAttr[0] . ".disable();", 'setBpRowRadioDisable(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttr[1] . '\');', $fullExpression);
                                } else {
                                    $fullExpression = str_replace($expSetAttr[0] . ".disable();", 'setBpRowParamDisable(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttr[1] . '\');', $fullExpression);
                                }
                            }
                        } else {

                            if ($getMetaRow['ABILITY_TOGGLE'] == 'enable') {
                                $setDisable = '';
                            } else {

                                if ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && ($getMetaRow['LOOKUP_TYPE'] == 'combo' || $getMetaRow['LOOKUP_TYPE'] == 'combogrid')) {

                                    $setDisable = 'setBpHeaderParamDisable(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');';
                                } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'combo_with_popup') {

                                    $setDisable = 'setBpHeaderComboWithPopupDisable(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');';
                                } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'radio') {

                                    $setDisable = 'setBpHeaderRadioDisable(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');';
                                } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'popup') {
                                    $setDisable = '
                                        $("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("input[type=\'text\']").attr(\'readonly\', \'readonly\');
                                        $("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find(".input-group-btn > button:not([data-more-metaid])").attr(\'style\', \'pointer-events: none; background-color: #eeeeee !important\').prop(\'disabled\', true);';
                                } elseif ($getMetaRow['isShow'] == '1' && $typeCode == 'boolean') {
                                    $setDisable = 'checkboxDisableUpdate($("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . '));';
                                } elseif ($typeCode == 'button' && $getMetaRow['isShow'] == '1') {
                                    $setDisable = 'bpButtonDisable(\'' . $expSetAttr[1] . '\', ' . $mainSelector . ');';
                                } elseif ($typeCode == 'text_editor' && $getMetaRow['isShow'] == '1') {
                                    
                                    if (Mdform::$isIndicatorRendering == false) {
                                        $setDisable = 'setTimeout(function(){ tinymce.get(\'param[' . $expSetAttr[1] . ']\').setMode(\'readonly\'); }, 1500);';
                                    } else {
                                        $setDisable = 'setTimeout(function(){ tinymce.get(\'mvParam[' . $expSetAttr[1] . ']\').setMode(\'readonly\'); }, 1500);';
                                    }
                                } elseif ($typeCode == 'file' && $getMetaRow['isShow'] == '1') {
                                    $setDisable = 'setBpHeaderFileFieldDisable(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');';
                                } else {
                                    $setDisable = '$("[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').prop("readonly", true);';
                                }
                            }

                            $fullExpression = str_replace($expSetAttr[0] . '.disable();', $setDisable, $fullExpression);
                        }
                    } elseif (strpos($expVal, '.enable()') !== false) {

                        if ($getMetaRow['parentId'] != '') {

                            if ($getMetaRow['ABILITY_TOGGLE'] == 'disable') {
                                $fullExpression = str_replace($expSetAttr[0] . '.enable();', '', $fullExpression);
                            } else {

                                if ($getMetaRow['isShow'] == '1' && $typeCode == 'button') {

                                    $fullExpression = str_replace($expSetAttr[0] . ".enable();", 'setBpRowButtonEnable(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttr[1] . '\');', $fullExpression);
                                } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'radio') {

                                    $fullExpression = str_replace($expSetAttr[0] . ".enable();", 'setBpRowRadioEnable(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttr[1] . '\');', $fullExpression);
                                } else {
                                    $fullExpression = str_replace($expSetAttr[0] . ".enable();", 'setBpRowParamEnable(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttr[1] . '\');', $fullExpression);
                                }
                            }
                        } else {

                            if ($getMetaRow['ABILITY_TOGGLE'] == 'disable') {
                                $setEnable = '';
                            } else {

                                if ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && ($getMetaRow['LOOKUP_TYPE'] == 'combo' || $getMetaRow['LOOKUP_TYPE'] == 'combogrid')) {
                                    $setEnable = 'setBpHeaderParamEnable(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');';
                                } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'combo_with_popup') {

                                    $setEnable = 'setBpHeaderComboWithPopupEnable(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');';
                                } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'radio') {

                                    $setEnable = 'setBpHeaderRadioEnable(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');';
                                } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'popup') {
                                    $setEnable = '
                                        $("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("input[type=\'text\']").removeAttr(\'readonly\');
                                        $("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("button").removeAttr(\'style\').prop(\'disabled\', false);';
                                } elseif ($getMetaRow['isShow'] == '1' && $typeCode == 'boolean') {
                                    $setEnable = 'checkboxEnableUpdate($("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . '));';
                                } elseif ($typeCode == 'button' && $getMetaRow['isShow'] == '1') {
                                    $setEnable = 'bpButtonEnable(\'' . $expSetAttr[1] . '\', ' . $mainSelector . ');';
                                } elseif ($typeCode == 'file' && $getMetaRow['isShow'] == '1') {

                                    $setEnable = 'setBpHeaderFileFieldEnable(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');';
                                } else {
                                    $setEnable = '$("[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').removeAttr("readonly");';
                                }
                            }

                            $fullExpression = str_replace($expSetAttr[0] . '.enable();', $setEnable, $fullExpression);
                        }
                    } elseif (strpos($expVal, '.disableAll()') !== false) {

                        if ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && ($getMetaRow['LOOKUP_TYPE'] == 'combo' || $getMetaRow['LOOKUP_TYPE'] == 'combo_with_popup')) {
                            $setDisable = 'if($("select[data-path=\'' . $expSetAttr[1] . '\']:eq(0)", ' . $mainSelector . ').hasClass("select2")) {
                                    $("select[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').select2(\'readonly\', true);
                                } else {
                                    $("select[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').attr(\'style\', \'pointer-events: none; background-color: #eeeeee !important;\');
                                }';
                        } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'popup') {
                            $setDisable = '
                                $("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("input[type=\'text\']").attr(\'readonly\', \'readonly\');
                                $("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find(".input-group-btn > button:not([data-more-metaid])").attr(\'style\', \'pointer-events: none; background-color: #eeeeee !important\').prop(\'disabled\', true);';
                        } elseif ($getMetaRow['isShow'] == '1' && $typeCode == 'boolean') {
                            $setDisable = 'checkboxDisableUpdate($("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . '));';
                        } else {
                            $setDisable = '$("[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').prop("readonly", true);';
                        }

                        $fullExpression = str_replace($expSetAttr[0] . '.disableAll();', $setDisable, $fullExpression);
                    } elseif (strpos($expVal, '.enableAll()') !== false) {

                        if ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && ($getMetaRow['LOOKUP_TYPE'] == 'combo' || $getMetaRow['LOOKUP_TYPE'] == 'combo_with_popup')) {
                            $setEnable = 'if($("select[data-path=\'' . $expSetAttr[1] . '\']:eq(0)", ' . $mainSelector . ').hasClass("select2")) {
                                    $("select[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').select2(\'readonly\', false).select2(\'enable\');
                                } else {
                                    $("select[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').removeAttr("style");
                                }';
                        } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'popup') {
                            $setEnable = '
                                $("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("input[type=\'text\']").removeAttr(\'readonly\');
                                $("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("button").removeAttr(\'style\').prop(\'disabled\', false);';
                        } elseif ($getMetaRow['isShow'] == '1' && $typeCode == 'boolean') {
                            $setEnable = 'checkboxEnableUpdate($("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . '));';
                        } else {
                            $setEnable = '$("[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').removeAttr("readonly");';
                        }

                        $fullExpression = str_replace($expSetAttr[0] . '.enableAll();', $setEnable, $fullExpression);
                    } elseif (strpos($expVal, '.required()') !== false) {
                        if ($getMetaRow['parentId'] != '') {
                            $fullExpression = str_replace($expSetAttr[0] . ".required();", 'setBpRowParamRequired(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttr[1] . '\');', $fullExpression);
                        } else {
                            $fullExpression = str_replace($expSetAttr[0] . ".required();", 'bpSetHeaderParamRequired(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');', $fullExpression);
                        }
                    } elseif (strpos($expVal, '.nonrequired()') !== false) {
                        if ($getMetaRow['parentId'] != '') {
                            $fullExpression = str_replace($expSetAttr[0] . ".nonrequired();", 'setBpRowParamNonRequired(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttr[1] . '\');', $fullExpression);
                        } else {
                            $fullExpression = str_replace($expSetAttr[0] . ".nonrequired();", 'bpSetHeaderParamNonRequired(' . $mainSelector . ', \'' . $expSetAttr[1] . '\');', $fullExpression);
                        }
                    } elseif (strpos($expVal, '.empty()') !== false) {
                        if ($getMetaRow['parentId'] != '') {
                            $fullExpression = str_replace($expSetAttr[0] . '.empty();', 'setBpHeaderParamEmpty(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSetAttr[1] . '\');', $fullExpression);
                        } else {
                            $fullExpression = str_replace($expSetAttr[0] . '.empty();', 'setBpHeaderParamEmpty(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $fullExpression);
                        }
                    }
                }
            }
        }

        if (!empty($parseExpressionMessage[0])) {

            foreach ($parseExpressionMessage[0] as $expVal) {
                preg_match_all('/message(\((.*)+\))/', $expVal, $mesgGet);
                $mesgGet = explode(',', $mesgGet[1][0]);
                $seconds = '';
                $message = trim(rtrim($mesgGet[1], ')'));
                $messageType = ltrim($mesgGet[0], '(');
                $delay = 0;

                if (strpos($message, "'") === false) {
                    $message = "'" . Lang::line($message) . "'";
                }

                if (isset($mesgGet[2]) && $mesgGet[2]) {
                    $delay = (int) $mesgGet[2] * 1000;
                    $seconds = 'delay: ' . $delay . ', ';
                }

                if ($expression_type == 'before_save') {
                    $replaceMsgExp = 'if (typeof bpMessageByExp === \'function\') { bpMessageByExp({status: \'' . $messageType . '\', message: ' . $message . ', isconsole: true, delay: ' . $delay . '}); } else { PNotify.removeAll(); new PNotify({title: \'' . $messageType . '\', text: ' . $message . ', type: \'' . $messageType . '\', sticker: false, ' . $seconds . 'addclass: pnotifyPosition}); }';
                    $fullExpression = str_replace($expVal . ';', $replaceMsgExp, $fullExpression);
                } else {
                    $fullExpression = str_replace($expVal . ';', 'PNotify.removeAll(); new PNotify({title: \'' . $messageType . '\', text: ' . $message . ', type: \'' . $messageType . '\', sticker: false, ' . $seconds . 'addclass: pnotifyPosition});', $fullExpression);
                }
            }
        }

        if (!empty($parseExpressionFiscalPeriodMessage[0])) {
            foreach ($parseExpressionFiscalPeriodMessage[0] as $expFpVal) {
                preg_match_all('/fiscalPeriodMessage(\((.*)+\))/', $expFpVal, $mesgGet);
                $mesgGet = explode(",", $mesgGet[1][0]);

                $message = trim(rtrim($mesgGet[1], ')'));

                if (strpos($message, "'") === false) {
                    $message = "'" . Lang::line($message) . "'";
                }

                $fullExpression = str_replace($expFpVal . ';', 'showFiscalPeriodMessage(\'' . ltrim($mesgGet[0], "(") . '\', ' . $message . ');', $fullExpression);
            }
        }

        if (!empty($parseExpressionSaveConfirm[0])) {
            foreach ($parseExpressionSaveConfirm[0] as $expValSc) {
                preg_match_all('/saveConfirm(\((.*)+\))/', $expValSc, $scGet);
                $scGet = trim(ltrim(rtrim($scGet[1][0], ')'), '('));
                $msg = str_replace("'", '', $scGet);
                $msg = Lang::line($msg);

                $msg = ' 
                if (isSaveConfirm_' . $processId . ' === false) { 
                (new PNotify({
                    title: \'Confirmation\',
                    text: \'' . $msg . '\',
                    icon: \'icon-info22\',
                    width: \'330px\',
                    hide: false,
                    confirm: {
                        confirm: true, 
                        buttons: [{
                            text: \'OK\', 
                            addClass: \'btn btn-primary\', 
                            click: function(notice) {
                                isSaveConfirm_' . $processId . ' = true;
                                PNotify.removeAll();
                                thisButton.click();
                            }
                        }, 
                        {
                            text: plang.get(\'close_btn\'), 
                            addClass: \'btn btn-light\', 
                            click: function(notice) {
                                PNotify.removeAll(); 
                            }
                        }]
                    },
                    buttons: {
                        closer: false,
                        sticker: false
                    },
                    history: {
                        history: false
                    },
                    addclass: \'stack-modal\',
                    stack: {
                        \'dir1\': \'down\',
                        \'dir2\': \'right\',
                        \'modal\': true
                    }
                })); return false; }';

                $fullExpression = str_replace($expValSc . ';', $msg, $fullExpression);
            }
        }

        if (!empty($parseExpressionStyle[0])) {
            foreach ($parseExpressionStyle[0] as $expVal) {

                if (strpos($expVal, '.label(') !== false && strpos($expVal, '.control(') !== false) {
                    preg_match_all('/\[(.*)\](.label\((.*)\))(.control\((.*)\))/', $expVal, $expStyleGet);
                    $expVal = $expVal . ";";
                    $getMetaRow = $this->model->getMetaTypeCode($processId, $expStyleGet[1][0]);
                    $typeCode = $getMetaRow['type'];

                    $styles = ltrim($expStyleGet[3][0], "'");
                    $styles = rtrim($styles, "'");

                    if ($typeCode == 'group') {

                        if ($expStyleGet[3][0] === 'reset') {
                            $fullExpression = str_replace($expVal, 'setBpGroupRemoveStyle(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expStyleGet[1][0] . '\');', $fullExpression);
                        } else {
                            $fullExpression = str_replace($expVal, 'setBpGroupStyle(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expStyleGet[1][0] . '\', \'' . $styles . '\');', $fullExpression);
                        }
                    } else {
                        if ($expStyleGet[3][0] === 'reset') {
                            if ($getMetaRow['parentId'] != '') {
                                $fullExpression = str_replace($expVal, 'setBpRowParamLabelRemoveStyle(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expStyleGet[1][0] . '\'); ##CONTROL##', $fullExpression);
                            } else {
                                $fullExpression = str_replace($expVal, '$("label[data-label-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').removeAttr("style"); ##CONTROL##', $fullExpression);
                            }
                        } else {
                            if ($getMetaRow['parentId'] != '') {
                                $fullExpression = str_replace($expVal, 'setBpRowParamLabelStyle(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expStyleGet[1][0] . '\', \'' . $styles . '\'); ##CONTROL##', $fullExpression);
                            } else {
                                $fullExpression = str_replace($expVal, '$("label[data-label-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').attr("style", "' . $styles . '"); ##CONTROL##', $fullExpression);
                            }
                        }
                        if ($expStyleGet[5][0] === 'reset') {
                            if ($getMetaRow['parentId'] != '') {

                                if ($getMetaRow['LOOKUP_TYPE'] == 'popup' && $getMetaRow['LOOKUP_META_DATA_ID'] != '') {
                                    $fullExpression = str_replace('##CONTROL##', 'setBpRowPopupParamRemoveStyle(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expStyleGet[1][0] . '\');', $fullExpression);
                                } else {
                                    $fullExpression = str_replace('##CONTROL##', 'setBpRowParamRemoveStyle(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expStyleGet[1][0] . '\');', $fullExpression);
                                }
                            } else {
                                $fullExpression = str_replace('##CONTROL##', '$("input[data-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').removeAttr("style");', $fullExpression);
                            }
                        } else {
                            $styles = ltrim($expStyleGet[5][0], "'");
                            $styles = rtrim($styles, "'");
                            if ($getMetaRow['parentId'] != '') {
                                if ($getMetaRow['LOOKUP_TYPE'] == 'popup' && $getMetaRow['LOOKUP_META_DATA_ID'] != '') {
                                    $fullExpression = str_replace('##CONTROL##', 'setBpRowPopupParamStyle(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expStyleGet[1][0] . '\', \'' . $styles . '\');', $fullExpression);
                                } else {
                                    $fullExpression = str_replace('##CONTROL##', 'setBpRowParamStyle(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expStyleGet[1][0] . '\', \'' . $styles . '\');', $fullExpression);
                                }
                            } else {

                                if ($typeCode == 'description' || $typeCode == 'description_auto' || $typeCode == 'text_editor') {
                                    $fullExpression = str_replace('##CONTROL##', '$("textarea[data-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').attr("style", "' . $styles . '");', $fullExpression);
                                } else {
                                    $fullExpression = str_replace('##CONTROL##', '$("input[data-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').attr("style", "' . $styles . '");', $fullExpression);
                                }
                            }
                        }
                    }
                }

                if (strpos($expVal, '.label(') !== false && strpos($expVal, '.control(') === false) {
                    preg_match_all('/\[(.*)\](.label\((.*)\))/', $expVal, $expStyleGet);
                    $expVal = $expVal . ';';
                    $getMetaRow = $this->model->getMetaTypeCode($processId, $expStyleGet[1][0]);
                    $typeCode = $getMetaRow['type'];

                    $styles = ltrim($expStyleGet[3][0], "'");
                    $styles = rtrim($styles, "'");

                    if ($typeCode == 'group') {

                        if ($expStyleGet[3][0] === 'reset') {
                            $fullExpression = str_replace($expVal, 'setBpGroupRemoveStyle(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expStyleGet[1][0] . '\');', $fullExpression);
                        } else {
                            $fullExpression = str_replace($expVal, 'setBpGroupStyle(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expStyleGet[1][0] . '\', \'' . $styles . '\');', $fullExpression);
                        }
                    } else {

                        if ($expStyleGet[3][0] === 'reset') {
                            if ($getMetaRow['parentId'] != '') {
                                $fullExpression = str_replace($expVal, 'setBpRowParamLabelRemoveStyle(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expStyleGet[1][0] . '\');', $fullExpression);
                            } else {
                                $fullExpression = str_replace($expVal, '$("label[data-label-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').removeAttr("style");', $fullExpression);
                            }
                        } else {
                            if ($getMetaRow['parentId'] != '') {
                                $fullExpression = str_replace($expVal, 'setBpRowParamLabelStyle(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expStyleGet[1][0] . '\', \'' . $styles . '\');', $fullExpression);
                            } else {
                                $fullExpression = str_replace($expVal, '$("label[data-label-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').attr("style", "' . $styles . '");', $fullExpression);
                            }
                        }
                    }
                }

                if (strpos($expVal, '.label(') === false && strpos($expVal, '.control(') !== false) {
                    preg_match_all('/\[(.*)\](.control\((.*)\))/', $expVal, $expStyleGet);
                    $expVal = $expVal . ';';
                    $getMetaRow = $this->model->getMetaTypeCode($processId, $expStyleGet[1][0]);
                    $typeCode = $getMetaRow['type'];

                    if ($expStyleGet[3][0] === 'reset') {

                        if ($processActionType == 'view') {

                            $fullExpression = str_replace($expVal, '$("[data-view-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').removeAttr("style");', $fullExpression);
                        } else {
                            if ($getMetaRow['parentId'] != '') {
                                if ($getMetaRow['LOOKUP_TYPE'] == 'popup' && $getMetaRow['LOOKUP_META_DATA_ID'] != '') {
                                    $fullExpression = str_replace($expVal, 'setBpRowPopupParamRemoveStyle(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expStyleGet[1][0] . '\');', $fullExpression);
                                } else {
                                    $fullExpression = str_replace($expVal, 'setBpRowParamRemoveStyle(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expStyleGet[1][0] . '\');', $fullExpression);
                                }
                            } else {
                                $fullExpression = str_replace($expVal, '$("[data-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').removeAttr("style");', $fullExpression);
                            }
                        }
                    } else {

                        $styles = ltrim($expStyleGet[3][0], "'");
                        $styles = rtrim($styles, "'");

                        if ($processActionType == 'view') {

                            if ($getMetaRow['parentId'] != '') {
                                $fullExpression = str_replace($expVal, 'setBpRowViewStyle(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expStyleGet[1][0] . '\', \'' . $styles . '\');', $fullExpression);
                            } else {
                                if ($getMetaRow['LOOKUP_TYPE'] == 'icon' && $getMetaRow['LOOKUP_META_DATA_ID'] != '') {
                                    $fullExpression = str_replace($expVal, $mainSelector . '.find("[data-section-path=\'' . $expStyleGet[1][0] . '\'] div.item-icon-selection").attr("style", "' . $styles . '");', $fullExpression);
                                } else {
                                    $fullExpression = str_replace($expVal, '$("[data-view-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').attr("style", "' . $styles . '");', $fullExpression);
                                }
                            }
                        } else {
                            if ($getMetaRow['parentId'] != '') {
                                if ($getMetaRow['LOOKUP_TYPE'] == 'popup' && $getMetaRow['LOOKUP_META_DATA_ID'] != '') {
                                    $fullExpression = str_replace($expVal, 'setBpRowPopupParamStyle(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expStyleGet[1][0] . '\', \'' . $styles . '\');', $fullExpression);
                                } else {
                                    $fullExpression = str_replace($expVal, 'setBpRowParamStyle(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expStyleGet[1][0] . '\', \'' . $styles . '\');', $fullExpression);
                                }
                            } else {
                                if ($getMetaRow['LOOKUP_TYPE'] == 'icon' && $getMetaRow['LOOKUP_META_DATA_ID'] != '') {
                                    $fullExpression = str_replace($expVal, $mainSelector . '.find("[data-section-path=\'' . $expStyleGet[1][0] . '\'] div.item-icon-selection").attr("style", "' . $styles . '");', $fullExpression);
                                } else {
                                    $fullExpression = str_replace($expVal, '$("[data-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').attr("style", "' . $styles . '");', $fullExpression);
                                }
                            }
                        }
                    }
                }
            }
        }

        if (!empty($parseExpressionTrigger[0])) {
            foreach ($parseExpressionTrigger[0] as $kkk => $expVal) {
                preg_match('/\[(.*?)\]/', $expVal, $expSet);
                $getMetaRow = $this->model->getMetaTypeCode($processId, $expSet[1]);

                if (($getMetaRow['LOOKUP_TYPE'] == 'combo' || $getMetaRow['LOOKUP_TYPE'] == 'combo_with_popup') && $getMetaRow['LOOKUP_META_DATA_ID'] != '') {
                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\].trigger\(/', '$("select[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').trigger(', $fullExpression);
                } else {
                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\].trigger\(/', '$("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').trigger(', $fullExpression);
                }
            }
        }

        if (!empty($parseExpressionTriggerRow[0])) {
            foreach ($parseExpressionTriggerRow[0] as $kkk => $expVal) {

                preg_match('/\[(.*?)\]/', $expVal, $expSet);
                $getMetaRow = $this->model->getMetaTypeCode($processId, $expSet[1]);

                if (($getMetaRow['LOOKUP_TYPE'] == 'combo' || $getMetaRow['LOOKUP_TYPE'] == 'combo_with_popup') && $getMetaRow['LOOKUP_META_DATA_ID'] != '') {
                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\].rowTrigger\(/', '$("select[data-path=\'' . $expSet[1] . '\']", (typeof element === \'undefined\' ? ' . $mainSelector . ' : element)).trigger(', $fullExpression);
                } else {
                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\].rowTrigger\(/', '$("input[data-path=\'' . $expSet[1] . '\']", (typeof element === \'undefined\' ? ' . $mainSelector . ' : element)).trigger(', $fullExpression);
                }
            }
        }

        if (!empty($parseExpressionFocus[0])) {
            foreach ($parseExpressionFocus[0] as $fk => $fcVal) {
                preg_match('/\[(.*?)\]/', $fcVal, $expSet);
                $getMetaRow = $this->model->getMetaTypeCode($processId, $expSet[1]);
                $typeCode = $getMetaRow['type'];

                if ($getMetaRow['parentId'] != '') {
                    $fullExpression = preg_replace('/\[' . $expSet[1] . '\].focus\(\);/', 'setBpRowParamFocus(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSet[1] . '\');', $fullExpression);
                } else {
                    if ($typeCode == 'description' || $typeCode == 'description_auto' || $typeCode == 'text_editor') {
                        $fullExpression = preg_replace('/\[' . $expSet[1] . '\].focus\(\);/', 'setTimeout(function(){ $("textarea[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').focus(); }, 1);', $fullExpression);
                    } else {
                        if ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] != '') {
                            if ($getMetaRow['LOOKUP_TYPE'] == 'popup') {
                                $fullExpression = preg_replace('/\[' . $expSet[1] . '\].focus\(\);/', 'setTimeout(function(){ $("input#' . $expSet[1] . '_displayField", ' . $mainSelector . ').focus(); }, 1);', $fullExpression);
                            } elseif ($getMetaRow['LOOKUP_TYPE'] == 'combo') {
                                $fullExpression = preg_replace('/\[' . $expSet[1] . '\].focus\(\);/', 'setTimeout(function(){ $("select[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').focus(); }, 1);', $fullExpression);
                            } else {
                                $fullExpression = preg_replace('/\[' . $expSet[1] . '\].focus\(\);/', 'setTimeout(function(){ $("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').focus().select(); }, 1);', $fullExpression);
                            }
                        } else {
                            $fullExpression = preg_replace('/\[' . $expSet[1] . '\].focus\(\);/', 'setTimeout(function(){ $("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').focus().select(); }, 1);', $fullExpression);
                        }
                    }
                }
            }
        }

        preg_match_all('/\[([^\]]*)\].val\(\)/', $fullExpression, $getValPath);

        if (!empty($getValPath[0])) {
            foreach ($getValPath[0] as $vgk => $valGetPathLast) {
                if (!empty($valGetPathLast)) {
                    $getMetaRowL = $this->model->getMetaTypeCode($processId, $getValPath[1][$vgk]);
                    $fullExpression = self::fullExpressionWithoutEventConvertGet($getMetaRowL['type'], $getMetaRowL, $mainSelector, $getMetaRowL['sidebarName'], $valGetPathLast, $getValPath[1][$vgk], $fullExpression, $processActionType);
                }
            }
        }

        preg_match_all('/sum\(\[(.*?)\]\)/i', $fullExpression, $sumAggregate); // aggregate (sum)
        preg_match_all('/avg\(\[(.*?)\]\)/i', $fullExpression, $avgAggregate); // aggregate (avg)
        preg_match_all('/min\(\[(.*?)\]\)/i', $fullExpression, $minAggregate); // aggregate (min)
        preg_match_all('/max\(\[(.*?)\]\)/i', $fullExpression, $maxAggregate); // aggregate (max)

        if (count($sumAggregate[1]) > 0) {
            foreach ($sumAggregate[1] as $s => $sv) {

                $getMetaRowAggr = $this->model->getMetaTypeCode($processId, $sv);

                if (empty($getMetaRowAggr['sidebarName'])) {
                    if ($getMetaRowAggr['type'] == 'bigdecimal') {
                        if (count(explode('.', $sv)) >= 3) {
                            if ($processActionType == 'view') {
                                $fullExpression = str_replace($sumAggregate[0][$s], 'getBpViewFieldSum(\'' . $sv . '\', checkElement, ' . $mainSelector . ')', $fullExpression);
                            } else {
                                $fullExpression = str_replace($sumAggregate[0][$s], 'getBpBigDecimalFieldSum(\'' . $sv . '\', checkElement, ' . $mainSelector . ')', $fullExpression);
                            }
                        } else {
                            $fullExpression = str_replace($sumAggregate[0][$s], 'setNumberToFixed($("input[data-path=\'' . $sv . '_bigdecimal\']:not([data-not-aggregate])", ' . $mainSelector . ').sum())', $fullExpression);
                        }
                    } elseif ($getMetaRowAggr['type'] == 'integer' || $getMetaRowAggr['type'] == 'long') {
                        if (count(explode('.', $sv)) >= 3) {
                            $fullExpression = str_replace($sumAggregate[0][$s], 'getBpIntegerFieldSum(\'' . $sv . '\', checkElement, ' . $mainSelector . ')', $fullExpression);
                        } else {
                            $fullExpression = str_replace($sumAggregate[0][$s], 'setNumberToFixed($("input[data-path=\'' . $sv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').sum())', $fullExpression);
                        }
                    } else {
                        $fullExpression = str_replace($sumAggregate[0][$s], 'Number($("input[data-path=\'' . $sv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').sum())', $fullExpression);
                    }
                } else {
                    if ($getMetaRowAggr['type'] == 'bigdecimal') {
                        $fullExpression = str_replace($sumAggregate[0][$s], '$("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $sv . '_bigdecimal\']:not([data-not-aggregate])", ' . $mainSelector . ').length > 0 ? setNumberToFixed($("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $sv . '_bigdecimal\']:not([data-not-aggregate])", ' . $mainSelector . ').sum()) : setNumberToFixed($("input[data-path=\'' . $sv . '_bigdecimal\']:not([data-not-aggregate])", ' . $mainSelector . ').sum())', $fullExpression);
                    } else {
                        $fullExpression = str_replace($sumAggregate[0][$s], '$("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $sv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').length > 0 ? Number($("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $sv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').sum()) : Number($("input[data-path=\'' . $sv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').sum())', $fullExpression);
                    }
                }
            }
        }
        if (count($avgAggregate[1]) > 0) {
            foreach ($avgAggregate[1] as $a => $av) {

                $getMetaRowAggr = $this->model->getMetaTypeCode($processId, $av);

                if (empty($getMetaRowAggr['sidebarName']))
                    $fullExpression = str_replace($avgAggregate[0][$a], 'Number($("input[data-path=\'' . $av . '\']:not([data-not-aggregate])", ' . $mainSelector . ').avg())', $fullExpression);
                else
                    $fullExpression = str_replace($avgAggregate[0][$a], '$("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $av . '\']:not([data-not-aggregate])", ' . $mainSelector . ').length > 0 ? Number($("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $av . '\']:not([data-not-aggregate])", ' . $mainSelector . ').avg()) : Number($("input[data-path=\'' . $av . '\']:not([data-not-aggregate])", ' . $mainSelector . ').avg())', $fullExpression);
            }
        }
        if (count($minAggregate[1]) > 0) {
            foreach ($minAggregate[1] as $m => $mv) {

                $getMetaRowAggr = $this->model->getMetaTypeCode($processId, $mv);

                if (empty($getMetaRowAggr['sidebarName'])) {
                    $fullExpression = str_replace($minAggregate[0][$m], 'Number($("input[data-path=\'' . $mv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').min())', $fullExpression);
                } else {
                    $fullExpression = str_replace($minAggregate[0][$m], '$("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $mv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').length > 0 ? Number($("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $mv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').min()) : Number($("input[data-path=\'' . $mv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').min())', $fullExpression);
                }
            }
        }
        if (count($maxAggregate[1]) > 0) {
            foreach ($maxAggregate[1] as $ma => $mav) {

                $getMetaRowAggr = $this->model->getMetaTypeCode($processId, $mav);

                if (empty($getMetaRowAggr['sidebarName']))
                    $fullExpression = str_replace($maxAggregate[0][$ma], 'Number($("input[data-path=\'' . $mav . '\']:not([data-not-aggregate])", ' . $mainSelector . ').max())', $fullExpression);
                else
                    $fullExpression = str_replace($maxAggregate[0][$ma], '$("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $mav . '\']:not([data-not-aggregate])", ' . $mainSelector . ').length > 0 ? Number($("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $mav . '\']:not([data-not-aggregate])", ' . $mainSelector . ').max()) : Number($("input[data-path=\'' . $mav . '\']:not([data-not-aggregate])", ' . $mainSelector . ').max())', $fullExpression);
            }
        }

        $fullExpression = str_replace('checkElement', "(typeof element === 'undefined' ? 'open' : element)", $fullExpression);
        // </editor-fold>

        $fullExpression = self::endReplaceResolver($fullExpression);

        return $fullExpression;
    }

    public function fullExpressionReplaceFncNames($processId, $mainSelector, $fullExpressionStr)
    {

        $fullExpression = html_entity_decode($fullExpressionStr, ENT_QUOTES);

        $fullExpression = str_replace('getLookupFieldValue(', 'bpGetLookupFieldValue(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getLookupFieldMultiValue(', 'bpGetLookupFieldMultiValue(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getLookupFieldValueCheckVal(', 'bpGetLookupFieldValueCheckVal(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getProcessParam(', 'bpGetProcessParam(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getDetailRowCount(', 'getDetailRowCount(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getDate(', 'bpGetDate(', $fullExpression);
        $fullExpression = str_replace('dateFormat(', 'bpDateFormat(', $fullExpression);
        $fullExpression = str_replace('getSessionInfo(', 'bpGetSessionInfo(', $fullExpression);
        $fullExpression = str_replace('getOpenParam(', 'bpGetOpenParam(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('getCallerType(', 'bpGetCallerType(' . $mainSelector, $fullExpression);
        $fullExpression = str_replace('getProcessTabItemCount(', 'bpGetProcessTabItemCount(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('getTemplateValue(', 'getTemplateValue(' . $mainSelector, $fullExpression);
        $fullExpression = str_replace('getComboText(', 'getBpComboText(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getComboValue(', 'getBpComboValue(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getLookupValue(', 'getBpLookupValue(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getFieldValue(', 'getBpRowParamNum(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getDataViewParam(', 'bpGetDataViewParam(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getLookupRowIndex(', 'getLookupRowIndex(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_ireplace('getKpiVal(', 'bpGetKpiRowVal(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_ireplace('getKpiControlCode(', 'bpGetKpiControlCode(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getKpiRowField(', 'bpGetKpiRowField(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_ireplace('getKpiCellVal(', 'bpGetKpiCellVal(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_ireplace('getKpiData(', 'bpGetKpiData(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_ireplace('getKpiDataSum(', 'bpGetKpiDataSum(', $fullExpression);
        $fullExpression = str_ireplace('getKpiDataMax(', 'bpGetKpiDataMax(', $fullExpression);
        $fullExpression = str_ireplace('getKpiDataMin(', 'bpGetKpiDataMin(', $fullExpression);
        $fullExpression = str_ireplace('getKpiDataAvg(', 'bpGetKpiDataAvg(', $fullExpression);
        $fullExpression = str_ireplace('getKpiDataCellVal(', 'bpGetKpiDataCellVal(', $fullExpression);
        $fullExpression = str_replace('getKpiColField(', 'bpGetKpiColField(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getKpiColFieldNull(', 'bpGetKpiColFieldNull(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getKpiColSum(', 'bpGetKpiColSum(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getKpiSubColSum(', 'bpGetKpiSubColSum(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getKpiColCount(', 'bpGetKpiColCount(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getKpiDtlCode(', 'bpGetKpiDtlCode(' . $mainSelector . ', checkElement', $fullExpression);
        $fullExpression = str_replace('getKpiIndicatorId(', 'bpGetKpiIndicatorId(' . $mainSelector . ', checkElement', $fullExpression);
        $fullExpression = str_replace('getKpiObjectDtlId(', 'bpGetKpiObjectDtlId(' . $mainSelector . ', checkElement', $fullExpression);
        $fullExpression = str_replace('getKpiAddonColVal(', 'bpGetKpiAddonColVal(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getKpiColLookupFieldValue(', 'bpGetKpiColLookupFieldValue(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getBufferValue(', 'bpGetJsonParamVal(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('getFileSize(', 'bpGetFileFieldSize(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getGLRowField(', 'bpGetGLRowField(checkElement, ', $fullExpression);
        $fullExpression = str_replace('getActiveGroupPath(', 'bpGetActiveGroupPath(' . $mainSelector, $fullExpression);
        $fullExpression = str_replace('getEditSidebarValue(', 'bpGetEditSidebarValue(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('getRowIndex(', 'bpGetRowIndex(checkElement', $fullExpression);
        $fullExpression = str_replace('getWhat3words(', 'bpGetWhat3words(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getOpenCageData(', 'bpGetOpenCageData(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getGoogleGeoData(', 'bpGetGoogleGeoData(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getCivilInfo(', 'bpGetCivilInfo(', $fullExpression);
        $fullExpression = str_replace('getBankAccountBalance(', 'bpGetBankAccountBalance(', $fullExpression);
        $fullExpression = str_replace('getBankAccountInfo(', 'bpGetBankAccountInfo(', $fullExpression);
        $fullExpression = str_replace('getBankTransactionStatement(', 'bpGetBankTransactionStatement(', $fullExpression);
        $fullExpression = str_replace('getColCellVal(', 'bpGetColCellVal(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getColCellBigdecimal(', 'bpGetColCellBigdecimal(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getSearchNoResultText(', 'bpGetSearchNoResultText(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getPagerDetailSum(', 'bpGetPagerDetailSum(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getPagerDetailAllRowsAggr(', 'bpGetPagerDetailAllRowsAggr(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getPagerTotalPageNum(', 'bpGetPagerTotalPageNum(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('getDetailValueByIndex(', 'bpGetDetailValueByIndex(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getVisiblePanelSelectedRowVal(', 'bpGetVisiblePanelSelectedRowVal(', $fullExpression);
        $fullExpression = str_replace('getBpMetaDataId(', 'bpGetBpMetaDataId(' . $mainSelector, $fullExpression);
        $fullExpression = str_replace('getBase64FromFile(', 'bpGetBase64FromFile(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getFromListByKeyVal(', 'bpGetFromListByKeyVal(', $fullExpression);
        $fullExpression = str_replace('getLifeCycleColumnVal(', 'bpGetLifeCycleColumnVal(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getBasketColumnVal(', 'bpGetBasketColumnVal(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('getValueEncryption(', 'bpGetValueEncryption(', $fullExpression);
        $fullExpression = str_replace('getExternalIpAddress(', 'bpGetExternalIpAddress(', $fullExpression);
        $fullExpression = str_replace('getMetaVerseMethodAction(', 'bpGetMetaVerseMethodAction(' . $mainSelector, $fullExpression);
        $fullExpression = str_replace('getIndicatorParam(', 'bpGetIndicatorParam(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('generatePassword(', 'bpGeneratePassword(', $fullExpression);
        $fullExpression = str_replace('getUniqueId(', 'bpGetUid(', $fullExpression);
        $fullExpression = str_replace('getAddonTabCount(', 'bpGetAddonTabCount(' . $mainSelector . ', ', $fullExpression);

        $fullExpression = str_replace('unsetLookupCriteria(', 'bpUnSetLookupCriteria(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setLookupCriteria(', 'bpSetLookupCriteria(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setKpiLookupCriteria(', 'setKpiLookupCriteria(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setLookupFieldCode(', 'setLookupFieldCode(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setLookupFieldName(', 'setLookupFieldName(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setLookupFieldCodeEnter(', 'bpSetLookupFieldCodeEnter(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setTab(', 'bpSetTab(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setWidgetFieldValue(', 'setBpWGFieldValue(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setDetailValueByIndex(', 'bpSetDetailValueByIndex(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setBigDecimalNull(', 'bpSetBigDecimalNull(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setValueComboByIndex(', 'bpSetValueComboByIndex(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_ireplace('setKpiVal(', 'bpSetKpiRowVal(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setKpiRowField(', 'bpSetKpiRowField(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setKpiColField(', 'bpSetKpiColField(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_ireplace('setKpiCellVal(', 'bpSetKpiCellVal(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_ireplace('setKpiCellStyle(', 'bpSetKpiCellStyle(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_ireplace('unsetKpiCellStyle(', 'bpUnsetKpiCellStyle(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setKpiColStyle(', 'bpSetKpiColStyle(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('unsetKpiColStyle(', 'bpUnsetKpiColStyle(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setKpiRowStyle(', 'bpSetKpiRowStyle(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('unsetKpiRowStyle(', 'bpUnsetKpiRowStyle(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setKpiRowColStyle(', 'bpSetKpiRowColStyle(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setKpiRowColRequired(', 'bpSetKpiRowColRequired(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setKpiRowColNonRequired(', 'bpSetKpiRowColNonRequired(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('kpiWizardGotoStep(', 'bpKpiWizardGotoStep(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setTabOrder(', 'bpSetTabOrder(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setDetailBySameValue(', 'bpSetDetailBySameValue(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setListToNewline(', 'bpSetListToNewline(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setFieldValueAll(', 'bpSetFieldValueAll(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setPVScroll(', 'bpSetPVScroll(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setPHScroll(', 'bpSetPHScroll(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setAddControlChooseType(', 'bpSetAddControlChooseType(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setAutoCompleteFilterType(', 'bpSetAutoCompleteFilterType(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('addAutoCompleteFilterType(', 'bpAddAutoCompleteFilterType(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setScaleFooter(', 'bpSetScaleFooter(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setScaleBigDecimal(', 'bpSetScaleBigDecimal(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('unsetMaxlength(', 'bpSetUnMaxlength(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setMaxlength(', 'bpSetMaxlength(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setMinDate(', 'bpSetMinDate(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setMaxDate(', 'bpSetMaxDate(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setGLRowField(', 'bpSetGLRowField(checkElement, ', $fullExpression);
        $fullExpression = str_replace('setRowIndexing(', 'bpSetRowIndexingByGroup(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setColCellVal(', 'bpSetColCellVal(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setEditSidebarValue(', 'bpSetEditSidebarValue(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setDateNoTrigger(', 'bpSetDateNoTrigger(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setComboSelectedValue(', 'bpSetComboSelectedValue(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setAddonTabFileExtension(', 'bpSetAddonTabFileExtension(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setAddonTabMessage(', 'bpSetAddonTabMessage(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setAddonActionControl(', 'bpSetAddonActionControl(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setAddonTabFileSize(', 'bpSetAddonTabFileSize(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setMetaPopupField(', 'bpSetMetaPopupField(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setDetailMergeCount(', 'bpSetDetailMergeCount(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setDetailMergeVisibler(', 'bpSetDetailMergeVisibler(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setFieldValueByVisibleRows(', 'bpSetFieldValueByVisibleRows(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setFlashingField(', 'bpSetFlashingField(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setSectionSize(', 'bpSetSectionSize(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setLayoutSidebarWidth(', 'bpSetLayoutSidebarWidth(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setVisiblePanelClickRowId(', 'bpSetVisiblePanelClickRowId(', $fullExpression);
        $fullExpression = str_replace('setTabLabelWidth(', 'bpSetTabLabelWidth(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setEqualValMultiLookup(', 'bpSetEqualValMultiLookup(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setWordEditorFilePath(', 'bpSetWordEditorFilePath(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setWordEditorReadOnly(', 'bpSetWordEditorReadOnly(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setPreviewReportTemplateId(', 'bpSetPreviewReportTemplateId(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setDetailHeight(', 'bpSetDetailHeight(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setFieldPrecisionScale(', 'bpSetFieldPrecisionScale(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setMetaVerseFieldValue(', 'bpSetMetaVerseFieldValue(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setHeaderFieldStyle(', 'bpSetHeaderFieldStyle(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setReportTemplateFieldValue(', 'bpSetReportTemplateFieldValue(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('setFileExtension(', 'bpSetFileExtension(' . $mainSelector . ', checkElement, ', $fullExpression);
        
        $fullExpression = str_replace('unsetMask(', 'bpUnSetMask(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setMask(', 'bpSetMask(' . $mainSelector . ', checkElement, ', $fullExpression);

        $fullExpression = str_replace('unsetLookupCodeMask(', 'bpUnSetLookupCodeMask(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('unsetLookupNameMask(', 'bpUnSetLookupNameMask(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setLookupCodeMask(', 'bpSetLookupCodeMask(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('setLookupNameMask(', 'bpSetLookupNameMask(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('dateTimeModify(', 'bpDateTimeModify(', $fullExpression);
        $fullExpression = str_replace('fileFieldPreviewImage(', 'bpFileFieldPreviewImage(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('addFullscreenButton(', 'bpAddFullscreenButton(' . $mainSelector, $fullExpression);
        $fullExpression = str_replace('filePathPreview(', 'bpFilePathPreview(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('filePreviewByUrl(', 'bpFilePreviewByUrl(' . $mainSelector . ', ', $fullExpression);

        $fullExpression = str_replace('runProcessValue(', 'bpRunProcessValue(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('randomFieldValue(', 'bpRandomFieldValue(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('runKpiProcessValue(', 'bpRunKpiProcessValue(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('execProcess(', 'execProcess(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('detailRowContains(', 'detailRowContains(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('transferSplitValueToDtlFunction(', 'transferSplitValueToDtlFunction(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('transferSplitValueToHdrFunction(', 'transferSplitValueToHdrFunction(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('selectorTooltipFunction(', 'selectorTooltipFunction(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('removeTagName(', 'removeTagName(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('addBtnPositionBottom(', 'bpAddBtnPositionBottom(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('rowBtnText(', 'bpRowBtnText(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('runFunctionOtherProcess(', 'runFunctionOtherProcess(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('showGL(', "showGL($mainSelector, ", $fullExpression);
        $fullExpression = str_replace("showGL($mainSelector, 0", "showGL($mainSelector, 0, checkElement", $fullExpression);
        $fullExpression = str_replace("showGL($mainSelector, 1", "showGL($mainSelector, 1, checkElement", $fullExpression);
        $fullExpression = str_replace('pasteDetailRow(', 'pasteDetailRow(' . $mainSelector . ', groupPath, checkElement, ', $fullExpression);
        $fullExpression = str_replace('openPopup(', 'openLookupPopup(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('openCombo(', 'bpOpenLookupCombo(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('detailActionCriteria(', 'detailActionCriteria(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('activeTab(', 'bpActiveTab(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('hideTab(', 'bpHideTab(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('showTab(', 'bpShowTab(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('activeProcessTab(', 'bpActiveProcessTab(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('hideProcessTab(', 'bpHideProcessTab(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('hideSidebar(', 'bpHideSidebar(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('showSidebar(', 'bpShowSidebar(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('hideTreeview(', 'bpHideTreeview(' . $mainSelector, $fullExpression);
        $fullExpression = str_replace('showTreeview(', 'bpShowTreeview(' . $mainSelector, $fullExpression);
        $fullExpression = str_replace('hideDetailFilter(', 'hideDetailFilter(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('showDetailFilter(', 'showDetailFilter(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('hideDetailHeader(', 'bpHideDetailHeader(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('showDetailHeader(', 'bpShowDetailHeader(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('addRow(', 'bpAddRow(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('detailRemoveHide(', 'bpDetailRemoveHide(checkElement, ', $fullExpression);
        $fullExpression = str_replace('detailRemoveShow(', 'bpDetailRemoveShow(checkElement, ', $fullExpression);
        $fullExpression = str_replace('isAddRowRun(', 'bpIsAddRowRun(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('detailRowHide(', 'bpDetailRowHide(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('detailRowShow(', 'bpDetailRowShow(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('detailRowRemove(', 'bpDetailRowRemove(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('pagingDetailRowRemove(', 'bpPagingDetailRowRemove(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('detailRowNumberReset(', 'bpDetailRowsNumberReset(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('detailRowsRemove(', 'bpDetailRowsRemove(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('detailRowHighlight(', 'bpDetailRowHighlight(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('comboFillData(', 'bpComboFillData(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('groupFieldImploder(', 'bpGroupFieldImploder(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('fillGroupByDv(', 'bpFillGroupByDv(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('fillGroupByData(', 'bpFillGroupByData(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('fillGroupAndDtlByData(', 'bpFillGroupAndDtlByData(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('fillGroupByGroup(', 'bpFillGroupByGroup(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('fillGroupByIndicator(', 'bpFillGroupByIndicator(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('detailGroupBtn(', 'bpDetailGroupBtn(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('clearCacheMeta(', 'bpClearCacheMetaFullExp(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('clearCacheFiscalPeriod(', 'bpClearCacheFiscalPeriod(', $fullExpression);
        $fullExpression = str_replace('showKpi(', 'showKpiForm(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('showIndicator(', 'showIndicatorForm(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('showIndicatorTemplate(', 'bpShowIndicatorTemplate(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_ireplace('kpiEnable(', 'bpKpiEnable(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_ireplace('kpiDisable(', 'bpKpiDisable(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_ireplace('kpiShow(', 'bpKpiShow(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_ireplace('kpiHide(', 'bpKpiHide(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_ireplace('kpiRequired(', 'bpKpiRequired(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_ireplace('kpiNonRequired(', 'bpKpiNonRequired(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_ireplace('kpiDetailButton(', 'bpKpiDetailButton(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('kpiColDisable(', 'bpKpiColDisable(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('kpiColHide(', 'bpKpiColHide(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('kpiColShow(', 'bpKpiColShow(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('kpiRowHide(', 'bpKpiRowHide(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('kpiRowShow(', 'bpKpiRowShow(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('kpiRowDisable(', 'bpKpiRowDisable(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('kpiRowColEnable(', 'bpKpiRowColEnable(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('kpiRowColDisable(', 'bpKpiRowColDisable(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('kpiRowColRequired(', 'bpSetKpiRowColRequired(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('kpiRowColNonRequired(', 'bpSetKpiRowColNonRequired(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('kpiCellDisable(', 'bpKpiCellDisable(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('kpiColEnable(', 'bpKpiColEnable(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('kpiRowEnable(', 'bpKpiRowEnable(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('kpiCellEnable(', 'bpKpiCellEnable(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('kpiColRequired(', 'bpKpiColRequired(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('kpiColNonRequired(', 'bpKpiColNonRequired(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('kpiCheckGroupSum(', 'bpKpiCheckGroupSum(' . $mainSelector, $fullExpression);
        $fullExpression = str_replace('kpiCheckFactSum(', 'bpKpiCheckFactSum(' . $mainSelector, $fullExpression);
        $fullExpression = str_replace('kpiObjectHideButton(', 'bpKpiObjectHideButton(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('kpiChangeColumnName(', 'bpKpiChangeColumnName(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('kpiIndicatorCellMerge(', 'bpKpiIndicatorCellMerge(' . $mainSelector, $fullExpression);
        $fullExpression = str_replace('kpiIndicatorCellMergeByColName(', 'bpKpiIndicatorCellMergeByColName(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_ireplace('kpiPathChangeEvent(', 'bpKpiPathChangeEvent(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_ireplace('clearCacheKpiTemplate(', 'bpClearCacheKpiTemplate(' . $mainSelector . ', checkElement, ', $fullExpression);

        $fullExpression = str_replace('ignoreGroup(', 'bpIgnoreGroup(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('acceptGroup(', 'bpAcceptGroup(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('disableEnterAddRow(', 'bpDisableEnterAddRow(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('enableEnterAddRow(', 'bpEnableEnterAddRow(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('validate(', 'bpFormValidateByExp(' . $mainSelector, $fullExpression);
        $fullExpression = str_replace('copyFileFieldByIndex(', 'bpCopyFileFieldByIndex(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('copyAllSubDetail(', 'bpCopyAllSubDetail(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('saveProcess(', 'bpSaveProcess(' . $mainSelector, $fullExpression);
        $fullExpression = str_ireplace('getParamAuthenticationUrl(', 'bpGetParamAuthenticationUrl(' . $mainSelector . ', ', $fullExpression);

        $fullExpression = str_replace('checkBufferParam(', 'bpCheckJsonParam(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('saveEdit(', 'bpSaveEdit(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_ireplace('isclosedfiscalperiod(', 'isClosedFiscalPeriod(', $fullExpression);
        $fullExpression = str_replace('showButton(', 'bpShowButton(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('hideButton(', 'bpHideButton(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('renameButton(', 'bpRenameButton(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('packageProcessResolver(', 'bpPackageProcessResolver(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('clickRowsBtn(', 'bpClickRowsBtn(' . $mainSelector . ', checkElement', $fullExpression);
        $fullExpression = str_replace('clickSideBarBtn(', 'bpClickSideBarBtn(' . $mainSelector . ', checkElement', $fullExpression);
        $fullExpression = str_replace('isDisabled(', 'bpIsDisabled(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('hideProcessDialog(', 'bpHideProcessDialog(' . $mainSelector, $fullExpression);
        $fullExpression = str_replace('thisClickButton(', 'bpThisClickButton(' . $mainSelector . ', ', $fullExpression);

        $fullExpression = str_replace('calcFooter(', 'bpCalcFooter(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('round(', 'bpRound(', $fullExpression);
        $fullExpression = str_replace('Math.bpRound(', 'Math.round(', $fullExpression);
        $fullExpression = str_replace('n2w(', 'bpNumberToWords(', $fullExpression);
        $fullExpression = str_replace('checkDataPermission(', 'bpCheckDataPermission(', $fullExpression);
        $fullExpression = str_replace('runWidget(', "bpRunWidget(" . $mainSelector . ", checkElement, '_" . $processId . "', ", $fullExpression);
        $fullExpression = str_replace('popupIgnoreSaveButton(', 'bpPopupIgnoreSaveButton(' . $mainSelector . ', checkElement, ', $fullExpression);

        $fullExpression = str_replace('timeAddMinute(', 'bpTimeAddMinute(', $fullExpression);
        $fullExpression = str_replace('removeDuplicateRows(', 'bpRemoveDuplicateRows(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('hideDuplicateRows(', 'bpHideDuplicateRows(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('styleDuplicateRows(', 'bpStyleDuplicateRows(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('ignoreDuplicateRows(', 'bpIgnoreDuplicateRows(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('countDuplicateRows(', 'bpCountDuplicateRows(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('detailRowsShow(', 'bpDetailRowsShow(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('removeCriteriaRows(', 'bpRemoveCriteriaRows(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('ignoreHdrDtlAutoChange(', 'bpIgnoreHdrDtlAutoChange(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('callHdrDtlRelation(', 'bpCallHdrDtlRelation(' . $mainSelector . ', ', $fullExpression);

        $fullExpression = str_replace('blockMessageStart(', 'bpBlockMessageStart(', $fullExpression);
        $fullExpression = str_replace('blockMessageStop(', 'bpBlockMessageStop(', $fullExpression);
        $fullExpression = str_replace('fullMessage(', 'bpFullMessage(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('messageChooseMeta(', 'bpMessageChooseMeta(' . $mainSelector . ', \'' . $processId . '\', responseData, ', $fullExpression);
        $fullExpression = str_replace('showConfirmDialog(', 'showConfirmDialog(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('messageClose(', 'bpMessageClose(', $fullExpression);
        $fullExpression = str_replace('unlimitTimeMessage(', 'bpUnlimitTimeMessage(', $fullExpression);
        $fullExpression = str_replace('centerMessage(', 'bpCenterMessage(', $fullExpression);

        $fullExpression = str_replace('runPHP(', 'bpRunPHP(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('callPhpService(', 'bpCallPhpService(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('soundPlay(', 'bpSoundPlay(', $fullExpression);
        $fullExpression = str_replace('lookupFieldReload(', 'bpLookupFieldReload(' . $mainSelector . ', \'' . $processId . '\', \'_' . $processId . '\', ', $fullExpression);
        $fullExpression = str_replace('hideEditSidebar(', 'bpHideEditSidebar(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('showEditSidebar(', 'bpShowEditSidebar(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('defaultEditSidebar(', 'bpDefaultEditSidebar(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('activePopupNameTabIndex(', 'bpActivePopupNameTabIndex(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('numberExponent(', 'bpNumberExponent(', $fullExpression);
        $fullExpression = str_replace('runJSFunction(', 'bpRunJSFunction(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('callSolidWindow(', 'bpCallSolidWindow(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('googleDMStoDD(', 'bpGoogleDMStoDD(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('googleDDtoUTM(', 'bpGoogleDDtoUTM(', $fullExpression);
        $fullExpression = str_replace('googleLatLngtoDMS(', 'bpGoogleCoordinatetoDMS(', $fullExpression);
        $fullExpression = str_replace('googleCoordinateDistanceBetween(', 'bpGoogleCoordinateDistanceBetween(', $fullExpression);
        $fullExpression = str_replace('passwordStrength(', 'bpPasswordStrength(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('validateUserPassword(', 'bpValidateUserPassword(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('n2time(', 'bpNumberToTime(', $fullExpression);
        $fullExpression = str_replace('t2number(', 'bpTimeToNumber(', $fullExpression);
        $fullExpression = str_replace('qrcode(', 'bpGenerateQRcode(', $fullExpression);
        $fullExpression = str_replace('previewQRCode(', 'bpPreviewQRCode(', $fullExpression);
        $fullExpression = str_replace('contentViewerById(', 'bpContentViewerById(checkElement, ', $fullExpression);
        $fullExpression = str_replace('colsSetOneCheck(', 'bpColsSetOneCheck(' . $mainSelector . ', checkElement', $fullExpression);
        $fullExpression = str_replace('colsSetOneVal(', 'bpColsSetOneVal(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('colsSetOneStyle(', 'bpColsSetOneStyle(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('getWorkspaceParam(', 'bpGetWorkspaceParam(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('detailColumnMerge(', 'bpDetailColumnMerge(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('detailColumnSort(', 'bpDetailColumnSort(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('workSpaceClose(', 'bpWorkSpaceClose(' . $mainSelector, $fullExpression);
        $fullExpression = str_replace('multiTabClose(', 'bpMultiTabClose(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('buttonTimer(', 'bpButtonTimer(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('buttonTimerAction(', 'bpButtonTimerAction(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('printPosByInvoiceId(', 'bpPrintPosByInvoiceId(', $fullExpression);
        $fullExpression = str_replace('printTemplatePosByInvoiceId(', 'bpPrintTemplatePosByInvoiceId(', $fullExpression);
        $fullExpression = str_replace('printTemplateByProcess(', 'bpPrintTemplateByProcess(' . $mainSelector . ', checkElement, \'' . $processId . '\',', $fullExpression);
        $fullExpression = str_replace('printTemplateByResponse(', 'bpPrintTemplateByResponse(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('procPrintPreview(', 'bpProcessPrintPreview(' . $mainSelector . ', checkElement, \'' . $processId . '\',', $fullExpression);
        $fullExpression = str_replace('processPrintPreview(', 'bpProcessPrintPreview(' . $mainSelector . ', checkElement, \'' . $processId . '\',', $fullExpression);
        $fullExpression = str_replace('visiblePanelDataViewReload(', 'bpVisiblePanelDataViewReload(', $fullExpression);
        $fullExpression = str_replace('closeProcessIsOpenedBp(', 'bpCloseProcessIsOpenedBp(' . $mainSelector, $fullExpression);
        $fullExpression = str_replace('saveBtnPositionFixed(', 'saveBtnPositionFixed(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('saveReportTemplateToFile(', 'bpSaveReportTemplateToFile(' . $mainSelector . ', ', $fullExpression);

        $fullExpression = str_replace('changeColumnName(', 'bpChangeColumnName(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('changeLabelName(', 'bpChangeLabelName(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('changeLookupPlaceHolder(', 'bpChangeLookupPlaceHolder(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('changeLookup(', 'bpChangeLookupByExp(' . $mainSelector . ', checkElement, \'' . $processId . '\', ', $fullExpression);
        $fullExpression = str_replace('changeGroupName(', 'bpChangeGroupName(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('changeTabName(', 'bpChangeTabName(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('changeDialogPosition(', 'bpChangeDialogPosition(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('changeTitleName(', 'bpChangeTitleName(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('changeButtonName(', 'bpChangeButtonName(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('changePlaceHolder(', 'bpChangePlaceHolder(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('changeSectionTitle(', 'bpChangeSectionTitle(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('changeProcessName(', 'bpChangeProcessName(' . $mainSelector . ', ', $fullExpression);

        $fullExpression = str_replace('statusBtnChangePosition(', 'bpStatusBtnChangePosition(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('hideWfmStatusButton(', 'bpHideWfmStatusButton(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('showWfmStatusButton(', 'bpShowWfmStatusButton(' . $mainSelector . ', ', $fullExpression);

        $fullExpression = str_replace('pagerDetailReload(', 'bpPagerDetailReload(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_ireplace('addonRequired(', 'bpAddonRequired(' . $mainSelector . ',', $fullExpression);
        $fullExpression = str_ireplace('addonNonRequired(', 'bpAddonNonRequired(' . $mainSelector . ',', $fullExpression);
        $fullExpression = str_ireplace('addonCountRequired(', 'bpAddonCountRequired(' . $mainSelector . ',', $fullExpression);
        $fullExpression = str_ireplace('addonNonCountRequired(', 'bpAddonNonCountRequired(' . $mainSelector . ',', $fullExpression);
        $fullExpression = str_replace('moveBetweenCell(', 'bpMoveBetweenCell(\'_' . $processId . '\',', $fullExpression);
        $fullExpression = str_replace('cyrillicToLatin(', 'bpCyrillicToLatin(', $fullExpression);
        $fullExpression = str_replace('numberToWords(', 'bpNumberToWords(', $fullExpression);
        $fullExpression = str_replace('getSaveButtonCode(', 'bpGetSaveButtonCode(thisButton', $fullExpression);
        $fullExpression = str_replace('isBpOpened(', 'bpIsBpOpened(' . $mainSelector, $fullExpression);
        $fullExpression = str_replace('closeActiveSysTab(', 'bpCloseActiveSysTab(' . $mainSelector, $fullExpression);
        $fullExpression = str_replace('multiPathVisibler(', 'bpMultiPathVisibler(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('hideSection(', 'bpHideSection(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('showSection(', 'bpShowSection(' . $mainSelector . ', ', $fullExpression);
        $fullExpression = str_replace('callProcessFromAnotherServer(', 'bpCallProcessFromAnotherServer(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('fileRowsToProcessTab(', 'bpFileRowsToProcessTab(' . $mainSelector . ', checkElement, \'_' . $processId . '\', ', $fullExpression);
        $fullExpression = str_replace('callAddMeta(', 'bpCallAddMeta(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('fieldToDetailToolbar(', 'bpFieldToDetailToolbar(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('workspaceMenuReload(', 'bpWorkspaceMenuReload(' . $mainSelector . ', checkElement', $fullExpression);
        $fullExpression = str_replace('checkEqualValMultiLookup(', 'bpCheckEqualValMultiLookup(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('clearBasket(', 'bpClearBasket(' . $mainSelector, $fullExpression);
        $fullExpression = str_replace('panelSelectedRowRemoveBoldStyle(', 'bpPanelSelectedRowRemoveBoldStyle(' . $mainSelector, $fullExpression);
        $fullExpression = str_replace('sendToMetaFromDetail(', 'bpSendToMetaFromDetail(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('docToPdfByDotNet(', 'bpDocToPdfByDotNet(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('runKpiIndicatorDataMart(', 'bpRunKpiIndicatorDataMart(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('execIndicator(', 'bpExecIndicator(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('findText(', 'bpFindText(', $fullExpression);
        $fullExpression = str_replace('callKpiIndicatorForm(', 'bpCallKpiIndicatorForm(' . $mainSelector . ', checkElement, ', $fullExpression);
        $fullExpression = str_replace('reportTemplatePreview(', 'bpReportTemplatePreview(' . $mainSelector . ', ', $fullExpression);
        
        if (strpos($fullExpression, 'columnRepeatFunction(') !== false) {
            preg_match_all('/columnRepeatFunction\((.*?)\)/i', $fullExpression, $columnRepeatFunctions);

            if (count($columnRepeatFunctions[0]) > 0) {
                foreach ($columnRepeatFunctions[1] as $ek => $ev) {

                    $arguments = trim(str_replace("'", '', $ev));
                    $argumentsArr = explode(',', $arguments);

                    if (count($argumentsArr) == 2) {

                        $groupPath = trim($argumentsArr[0]);
                        $fncName = trim($argumentsArr[1]);

                        if (strpos($fncName, '|') !== false) {
                            $rpFunctions = explode('|', $fncName);
                            $fncName = '';
                            foreach ($rpFunctions as $rpFunction) {
                                if ($rpFunction != '') {
                                    $fncName .= trim($rpFunction) . '_' . $processId . ',';
                                }
                            }
                            $fncName = rtrim($fncName, ',');
                        } else {
                            $fncName = $fncName . '_' . $processId;
                        }

                        $fullExpression = str_replace($columnRepeatFunctions[0][$ek], "bpRepeatColumnFunction($mainSelector, '$groupPath', '$fncName')", $fullExpression);
                    } elseif (count($argumentsArr) == 3) {

                        $groupPath = trim($argumentsArr[0]);
                        $fncName = trim($argumentsArr[1]);
                        $elem = trim($argumentsArr[2]);

                        if (strpos($fncName, '|') !== false) {
                            $rpFunctions = explode('|', $fncName);
                            $fncName = '';
                            foreach ($rpFunctions as $rpFunction) {
                                if ($rpFunction != '') {
                                    $fncName .= trim($rpFunction) . '_' . $processId . ',';
                                }
                            }
                            $fncName = rtrim($fncName, ',');
                        } else {
                            $fncName = $fncName . '_' . $processId;
                        }

                        $fullExpression = str_replace($columnRepeatFunctions[0][$ek], "bpRepeatColumnFunction($mainSelector, '$groupPath', '$fncName', $elem)", $fullExpression);
                    }
                }
            }
        }

        if (strpos($fullExpression, 'repeatFunction(') !== false) {
            preg_match_all('/repeatFunction\((.*?)\)/i', $fullExpression, $repeatFunctions);

            if (count($repeatFunctions[0]) > 0) {
                foreach ($repeatFunctions[1] as $ek => $ev) {

                    $arguments = trim(str_replace("'", '', $ev));
                    $argumentsArr = explode(',', $arguments);

                    if (count($argumentsArr) == 2) {

                        $groupPath = trim($argumentsArr[0]);
                        $fncName = trim($argumentsArr[1]);

                        if (strpos($fncName, '|') !== false) {
                            $rpFunctions = explode('|', $fncName);
                            $fncName = '';
                            foreach ($rpFunctions as $rpFunction) {
                                if ($rpFunction != '') {
                                    $fncName .= trim($rpFunction) . '_' . $processId . ',';
                                }
                            }
                            $fncName = rtrim($fncName, ',');
                        } else {
                            $fncName = $fncName . '_' . $processId;
                        }

                        $fullExpression = str_replace($repeatFunctions[0][$ek], "repeatFunction($mainSelector, '$groupPath', '$fncName')", $fullExpression);
                    } elseif (count($argumentsArr) == 3) {

                        $groupPath = trim($argumentsArr[0]);
                        $fncName = trim($argumentsArr[1]);
                        $elem = trim($argumentsArr[2]);

                        if (strpos($fncName, '|') !== false) {
                            $rpFunctions = explode('|', $fncName);
                            $fncName = '';
                            foreach ($rpFunctions as $rpFunction) {
                                if ($rpFunction != '') {
                                    $fncName .= trim($rpFunction) . '_' . $processId . ',';
                                }
                            }
                            $fncName = rtrim($fncName, ',');
                        } else {
                            $fncName = $fncName . '_' . $processId;
                        }

                        $fullExpression = str_replace($repeatFunctions[0][$ek], "repeatFunction($mainSelector, '$groupPath', '$fncName', $elem)", $fullExpression);
                    }
                }
            }
        }

        if (strpos($fullExpression, 'kpiRepeatFunction(') !== false) {
            preg_match_all('/kpiRepeatFunction\((.*?)\)/i', $fullExpression, $repeatFunctions);

            if (count($repeatFunctions[0]) > 0) {
                foreach ($repeatFunctions[1] as $ek => $ev) {

                    $arguments = trim(str_replace("'", '', $ev));
                    $argumentsArr = explode(',', $arguments);

                    $fncName = trim($argumentsArr[0]);

                    if (strpos($fncName, '|') !== false) {
                        $rpFunctions = explode('|', $fncName);
                        $fncName = '';
                        foreach ($rpFunctions as $rpFunction) {
                            if ($rpFunction != '') {
                                $fncName .= trim($rpFunction) . '_' . $processId . ',';
                            }
                        }
                        $fncName = rtrim($fncName, ',');
                    } else {
                        $fncName = $fncName . '_' . $processId;
                    }

                    $fullExpression = str_replace($repeatFunctions[0][$ek], "bpKpiRepeatFunction($mainSelector, checkElement, '$fncName')", $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'cacheCallExpression(') !== false) {
            preg_match_all('/cacheCallExpression\((.*?)\)/i', $fullExpression, $cacheCallExpressions);

            if (count($cacheCallExpressions[0]) > 0) {
                foreach ($cacheCallExpressions[1] as $ek => $ev) {

                    $cacheExpCode = strtolower(str_replace("'", '', $ev));
                    $cacheGroupPath = $this->model->getCacheGroupPathByExpCodeModel($processId, $cacheExpCode);

                    $fullExpression = str_replace($cacheCallExpressions[0][$ek], "bpCacheCallExpression($mainSelector, '_$processId', '$processId', '$cacheExpCode', '$cacheGroupPath')", $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'getVarCacheCallExpression(') !== false) {
            preg_match_all('/getVarCacheCallExpression\((.*?)\)/i', $fullExpression, $cacheCallExpressions);

            if (count($cacheCallExpressions[0]) > 0) {
                foreach ($cacheCallExpressions[1] as $ek => $ev) {

                    $evArr          = explode(',', $ev);
                    $cacheExpCode   = strtolower(str_replace("'", '', $evArr[0]));
                    $cacheGroupPath = $this->model->getCacheGroupPathByExpCodeModel($processId, $cacheExpCode);
                    $varNames       = str_replace("'", '', $evArr[1]);

                    $fullExpression = str_replace($cacheCallExpressions[0][$ek], "bpCacheCallExpressionVar($mainSelector, '_$processId', '$processId', '$cacheExpCode', '$cacheGroupPath', '$varNames')", $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'callProcess(') !== false) {
            preg_match_all('/callProcess\((.*?)\)/i', $fullExpression, $callProcess);

            if (count($callProcess[0]) > 0) {
                foreach ($callProcess[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $processCode = trim(str_replace("'", '', $evArr[0]));
                    $processCodeLower = strtolower($processCode);
                    $getProcessId = $this->model->getProcessIdByCodeModel($processCodeLower);
                    $processIdBySelect = $getProcessId ? "'" . $getProcessId . "'" : $processCode;

                    $fullExpression = str_replace($callProcess[0][$ek], 'bpCallProcessByExp(' . $mainSelector . ', checkElement, \'' . Mdwebservice::$processCode . '\', ' . $processIdBySelect . ',' . $evArr[1] . (isset($evArr[2]) ? ',' . $evArr[2] : '') . ')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'callProcessBpOpen(') !== false) {
            preg_match_all('/callProcessBpOpen\((.*?)\)/i', $fullExpression, $callProcess);

            if (count($callProcess[0]) > 0) {
                foreach ($callProcess[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $processCode = strtolower(trim(str_replace("'", '', $evArr[0])));
                    $processIdBySelect = $this->model->getProcessIdByCodeModel($processCode);

                    $evArrParam = '';

                    foreach ($evArr as $evAkey => $evArow) {
                        if (0 < $evAkey) {
                            $evArrParam .= ', ' . $evArow;
                        }
                    }

                    $fullExpression = str_replace($callProcess[0][$ek], 'bpCallProcessBpOpenByExp(' . $mainSelector . ', checkElement, \'' . Mdwebservice::$processCode . '\', \'' . $processIdBySelect . '\'' . $evArrParam . ')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'callProcessDefaultGet(') !== false) {
            preg_match_all('/callProcessDefaultGet\((.*?)\)/i', $fullExpression, $callProcessDefaultGet);

            if (count($callProcessDefaultGet[0]) > 0) {
                foreach ($callProcessDefaultGet[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $processCode = strtolower(trim(str_replace("'", '', $evArr[0])));
                    $processIdBySelect = $this->model->getProcessIdByCodeModel($processCode);

                    $fullExpression = str_replace($callProcessDefaultGet[0][$ek], 'bpCallProcessDefaultGetByExp(' . $mainSelector . ', checkElement, \'' . Mdwebservice::$processCode . '\', \'' . $processIdBySelect . '\',' . $evArr[1] . ')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'callDataView(') !== false) {
            preg_match_all('/callDataView\((.*?)\)/i', $fullExpression, $callDataView);

            if (count($callDataView[0]) > 0) {
                foreach ($callDataView[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $dvCode = trim(str_replace("'", '', $evArr[0]));
                    $dvCodeLower = strtolower($dvCode);
                    $dvIdBySelect = $this->model->getMetaGroupIdByCodeModel($dvCodeLower);
                    $dvIdBySelect = $dvIdBySelect ? "'" . $dvIdBySelect . "'" : $dvCode;

                    if (isset($evArr[2])) {
                        $attr = ', ' . $evArr[2];
                    } else {
                        $attr = '';
                    }

                    $fullExpression = str_replace($callDataView[0][$ek], 'bpCallDataViewByExp(' . $mainSelector . ', checkElement, ' . $dvIdBySelect . ',' . (isset($evArr[1]) ? $evArr[1] : "''") . $attr . ')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'callStatement(') !== false) {
            preg_match_all('/callStatement\((.*?)\)/i', $fullExpression, $callStatement);

            if (count($callStatement[0]) > 0) {
                foreach ($callStatement[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $metaCode = trim(str_replace("'", '', $evArr[0]));
                    $metaCodeLower = strtolower($metaCode);
                    $metaIdBySelect = $this->model->getStatementIdByCodeModel($metaCodeLower);
                    $metaIdBySelect = $metaIdBySelect ? "'" . $metaIdBySelect . "'" : $metaCode;

                    if (isset($evArr[2])) {
                        $attr = ', ' . $evArr[2];
                    } else {
                        $attr = '';
                    }

                    $fullExpression = str_replace($callStatement[0][$ek], 'bpCallStatementByExp(' . $mainSelector . ', checkElement, ' . $metaIdBySelect . ',' . $evArr[1] . $attr . ')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'callWorkspace(') !== false) {
            preg_match_all('/callWorkspace\((.*?)\)/i', $fullExpression, $callStatement);

            if (count($callStatement[0]) > 0) {
                foreach ($callStatement[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $metaCode = trim(str_replace("'", '', $evArr[0]));
                    $metaCodeLower = strtolower($metaCode);
                    $metaIdBySelectRow = $this->model->getWorkspaceIdByCodeModel($metaCodeLower);
                    $metaIdBySelect = $metaIdBySelectRow ? "'" . $metaIdBySelectRow['META_DATA_ID'] . "'" : $metaCode;
                    $metaNameBySelect = $metaIdBySelectRow ? "'" . $metaIdBySelectRow['META_DATA_NAME'] . "'" : $metaCode;

                    $fullExpression = str_replace($callStatement[0][$ek], 'bpCallWorkspaceByExp(' . $mainSelector . ', checkElement, ' . $metaIdBySelect . ', ' . $metaNameBySelect . ')', $fullExpression);
                }
            }
        }
        
        if (strpos($fullExpression, 'callIndicatorDataView(') !== false) {
            preg_match_all('/callIndicatorDataView\((.*?)\)/i', $fullExpression, $callDataView);

            if (count($callDataView[0]) > 0) {
                foreach ($callDataView[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $dvCode = trim(str_replace("'", '', $evArr[0]));
                    $dvCodeLower = strtolower($dvCode);
                    $dvIdBySelect = $this->model->getKpiIndicatorIdByCodeModel($dvCodeLower);
                    $dvIdBySelect = $dvIdBySelect ? "'" . $dvIdBySelect . "'" : $dvCode;

                    if (isset($evArr[2])) {
                        $attr = ', ' . $evArr[2];
                    } else {
                        $attr = '';
                    }

                    $fullExpression = str_replace($callDataView[0][$ek], 'bpCallIndicatorDataViewByExp(' . $mainSelector . ', checkElement, ' . $dvIdBySelect . ',' . (isset($evArr[1]) ? $evArr[1] : "''") . $attr . ')', $fullExpression);
                }
            }
        }
        
        if (strpos($fullExpression, 'callIndicatorProcess(') !== false) {
            preg_match_all('/callIndicatorProcess\((.*?)\)/i', $fullExpression, $callProcess);

            if (count($callProcess[0]) > 0) {
                foreach ($callProcess[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $processCode = trim(str_replace("'", '', $evArr[0]));
                    $processCodeLower = strtolower($processCode);
                    $getProcessId = $this->model->getKpiIndicatorIdByCodeModel($processCodeLower);
                    $processIdBySelect = $getProcessId ? "'" . $getProcessId . "'" : $processCode;

                    $fullExpression = str_replace($callProcess[0][$ek], 'bpCallIndicatorProcessByExp(' . $mainSelector . ', checkElement, \'' . Mdwebservice::$processCode . '\', ' . $processIdBySelect . ',' . $evArr[1] . (isset($evArr[2]) ? ',' . $evArr[2] : '') . ')', $fullExpression);
                }
            }
        }
        
        if (strpos($fullExpression, 'getDataPointInPolygon(') !== false) {
            preg_match_all('/getDataPointInPolygon\((.*?)\)/i', $fullExpression, $callProcess);

            if (count($callProcess[0]) > 0) {
                foreach ($callProcess[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    
                    if (count($evArr) >= 3) {
                        $processCode = trim(str_replace("'", '', $evArr[0]));
                        $processCodeLower = strtolower($processCode);
                        $getProcessId = $this->model->getKpiIndicatorIdByCodeModel($processCodeLower);
                        $processIdBySelect = $getProcessId ? "'" . $getProcessId . "'" : $processCode;

                        $fullExpression = str_replace($callProcess[0][$ek], 'bpGetDataPointInPolygon('.$mainSelector.', checkElement, '.$processIdBySelect.', '.$evArr[1].', '.$evArr[2].(isset($evArr[3]) ? ', '.$evArr[3] : '').')', $fullExpression);
                    }
                }
            }
        }
        
        if (strpos($fullExpression, 'close(') !== false) {
            preg_match_all('/close\((.*?)\)/i', $fullExpression, $closeProcess);

            if (count($closeProcess[0]) > 0) {
                foreach ($closeProcess[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $processCode = strtolower(trim(str_replace("'", '', $evArr[0])));

                    if ($processCode == 'this') {
                        $processIdBySelect = 'this';
                    } else {
                        $processIdBySelect = $this->model->getProcessIdByCodeModel($processCode);
                    }

                    $fullExpression = str_replace($closeProcess[0][$ek], 'bpCloseProcessByExp(' . $mainSelector . ', \'' . $processIdBySelect . '\')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'clickButton(') !== false) {
            preg_match_all('/clickButton\((.*?)\)/i', $fullExpression, $clickButton);

            if (count($clickButton[0]) > 0) {
                foreach ($clickButton[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $processCode = strtolower(trim(str_replace("'", '', $evArr[0])));

                    if ($processCode == 'this') {
                        $processIdBySelect = 'this';
                    } else {
                        $processIdBySelect = $this->model->getProcessIdByCodeModel($processCode);
                    }

                    $fullExpression = str_replace($clickButton[0][$ek], 'bpClickButtonByExp(' . $mainSelector . ', \'' . $processIdBySelect . '\',' . $evArr[1] . ')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'getFieldValueOtherProcess(') !== false) {
            preg_match_all('/getFieldValueOtherProcess\((.*?)\)/i', $fullExpression, $getFieldValueOtherProcess);

            if (count($getFieldValueOtherProcess[0]) > 0) {
                foreach ($getFieldValueOtherProcess[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $processCode = strtolower(trim(str_replace("'", '', $evArr[0])));

                    if ($processCode == 'this') {
                        $processIdBySelect = 'this';
                    } else {
                        $processIdBySelect = $this->model->getProcessIdByCodeModel($processCode);
                    }

                    $fullExpression = str_replace($getFieldValueOtherProcess[0][$ek], 'bpGetFieldValueOtherProcessByExp(' . $mainSelector . ', \'' . $processIdBySelect . '\',' . $evArr[1] . ')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'setFieldValueOtherProcess(') !== false) {
            preg_match_all('/setFieldValueOtherProcess\((.*?)\)/i', $fullExpression, $setFieldValueOtherProcess);

            if (count($setFieldValueOtherProcess[0]) > 0) {
                foreach ($setFieldValueOtherProcess[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $processCode = strtolower(trim(str_replace("'", '', $evArr[0])));

                    if ($processCode == 'this') {
                        $processIdBySelect = 'this';
                    } else {
                        $processIdBySelect = $this->model->getProcessIdByCodeModel($processCode);
                    }

                    $fullExpression = str_replace($setFieldValueOtherProcess[0][$ek], 'bpSetFieldValueOtherProcessByExp(' . $mainSelector . ', \'' . $processIdBySelect . '\',' . $evArr[1] . ',' . $evArr[2] . ')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'setComboTextOtherProcess(') !== false) {
            preg_match_all('/setComboTextOtherProcess\((.*?)\)/i', $fullExpression, $setComboTextOtherProcess);

            if (count($setComboTextOtherProcess[0]) > 0) {
                foreach ($setComboTextOtherProcess[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $processCode = strtolower(trim(str_replace("'", '', $evArr[0])));

                    if ($processCode == 'this') {
                        $processIdBySelect = 'this';
                    } else {
                        $processIdBySelect = $this->model->getProcessIdByCodeModel($processCode);
                    }

                    $fullExpression = str_replace($setComboTextOtherProcess[0][$ek], 'bpSetComboTextOtherProcessByExp(' . $mainSelector . ', \'' . $processIdBySelect . '\',' . $evArr[1] . ',' . $evArr[2] . ')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'setComboValueOtherProcess(') !== false) {
            preg_match_all('/setComboValueOtherProcess\((.*?)\)/i', $fullExpression, $setComboValueOtherProcess);

            if (count($setComboValueOtherProcess[0]) > 0) {
                foreach ($setComboValueOtherProcess[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $processCode = strtolower(trim(str_replace("'", '', $evArr[0])));

                    if ($processCode == 'this') {
                        $processIdBySelect = 'this';
                    } else {
                        $processIdBySelect = $this->model->getProcessIdByCodeModel($processCode);
                    }

                    $fullExpression = str_replace($setComboValueOtherProcess[0][$ek], 'bpSetComboValueOtherProcessByExp(' . $mainSelector . ', \'' . $processIdBySelect . '\',' . $evArr[1] . ',' . $evArr[2] . ')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'setDataViewFilter(') !== false) {
            preg_match_all('/setDataViewFilter\((.*?)\)/i', $fullExpression, $setDataViewFilter);

            if (count($setDataViewFilter[0]) > 0) {
                foreach ($setDataViewFilter[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $dvCode = strtolower(trim(str_replace("'", '', $evArr[0])));

                    $divIdBySelect = $this->model->getMetaGroupIdByCodeModel($dvCode);
                    $divIdBySelect = $divIdBySelect ? "'" . $divIdBySelect . "'" : $divIdBySelect;

                    if (strpos($divIdBySelect, "'") === false) {
                        $divIdBySelect = "'" . $divIdBySelect . "'";
                    }

                    $fullExpression = str_replace($setDataViewFilter[0][$ek], 'bpSetDataViewFilter(' . $mainSelector . ', checkElement, ' . $divIdBySelect . ',' . $evArr[1] . ')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'dataViewRefresh(') !== false) {
            preg_match_all('/dataViewRefresh\((.*?)\)/i', $fullExpression, $dataViewRefresh);

            if (count($dataViewRefresh[0]) > 0) {
                foreach ($dataViewRefresh[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $dvCode = strtolower(trim(str_replace("'", '', $evArr[0])));

                    $divIdBySelect = $this->model->getMetaGroupIdByCodeModel($dvCode);
                    $divIdBySelect = $divIdBySelect ? "'" . $divIdBySelect . "'" : $dvCode;

                    if (strpos($divIdBySelect, "'") === false) {
                        $divIdBySelect = "'" . $divIdBySelect . "'";
                    }

                    if (isset($evArr[1])) {
                        $fullExpression = str_replace($dataViewRefresh[0][$ek], 'bpDataViewRefresh(' . $mainSelector . ', checkElement, ' . $divIdBySelect . ',' . $evArr[1] . ')', $fullExpression);
                    } else {
                        $fullExpression = str_replace($dataViewRefresh[0][$ek], 'bpDataViewRefresh(' . $mainSelector . ', checkElement, ' . $divIdBySelect . ')', $fullExpression);
                    }
                }
            }
        }

        if (strpos($fullExpression, 'getDataViewColumnVal(') !== false) {
            preg_match_all('/getDataViewColumnVal\((.*?)\)/i', $fullExpression, $getDataViewColumnVal);

            if (count($getDataViewColumnVal[0]) > 0) {
                foreach ($getDataViewColumnVal[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $dvCode = strtolower(trim(str_replace("'", '', $evArr[0])));

                    $divIdBySelect = $this->model->getMetaGroupIdByCodeModel($dvCode);
                    $divIdBySelect = $divIdBySelect ? $divIdBySelect : $dvCode;

                    $fullExpression = str_replace($getDataViewColumnVal[0][$ek], 'bpGetDataViewColumnVal(\'' . $divIdBySelect . '\',' . $evArr[1] . ',' . $evArr[2] . ')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'getVisibleDataViewColumnVal(') !== false) {
            preg_match_all('/getVisibleDataViewColumnVal\((.*?)\)/i', $fullExpression, $getDataViewColumnVal);

            if (count($getDataViewColumnVal[0]) > 0) {
                foreach ($getDataViewColumnVal[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $dvCode = strtolower(trim(str_replace("'", '', $evArr[0])));

                    $divIdBySelect = $this->model->getMetaGroupIdByCodeModel($dvCode);
                    $divIdBySelect = $divIdBySelect ? $divIdBySelect : $dvCode;

                    $fullExpression = str_replace($getDataViewColumnVal[0][$ek], 'bpGetVisibleDataViewColumnVal(\'' . $divIdBySelect . '\',' . $evArr[1] . ',' . $evArr[2] . ')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'getDataViewFilterVal(') !== false) {
            preg_match_all('/getDataViewFilterVal\((.*?)\)/i', $fullExpression, $getDataViewFilterVal);

            if (count($getDataViewFilterVal[0]) > 0) {
                foreach ($getDataViewFilterVal[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $dvCode = strtolower(trim(str_replace("'", '', $evArr[0])));

                    $divIdBySelect = $this->model->getMetaGroupIdByCodeModel($dvCode);

                    $fullExpression = str_replace($getDataViewFilterVal[0][$ek], 'bpGetDataViewFilterVal(\'' . $divIdBySelect . '\',' . $evArr[1] . ')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'getPanelSelectedRowVal(') !== false) {
            preg_match_all('/getPanelSelectedRowVal\((.*?)\)/i', $fullExpression, $getPanelSelectedRowVal);

            if (count($getPanelSelectedRowVal[0]) > 0) {
                foreach ($getPanelSelectedRowVal[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $dvCode = strtolower(trim(str_replace("'", '', $evArr[0])));

                    $dvIdBySelect = $this->model->getMetaGroupIdByCodeModel($dvCode);

                    $fullExpression = str_replace($getPanelSelectedRowVal[0][$ek], 'bpGetPanelSelectedRowVal(\'' . $dvIdBySelect . '\',' . $evArr[1] . ', ' . $evArr[2] . ')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'getWfmNextStatusList(') !== false) {
            preg_match_all('/getWfmNextStatusList\((.*?)\)/i', $fullExpression, $getDataViewColumnVal);

            if (count($getDataViewColumnVal[0]) > 0) {
                foreach ($getDataViewColumnVal[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $dvCode = strtolower(trim(str_replace("'", '', $evArr[0])));

                    $dvIdBySelect = $this->model->getMetaGroupIdByCodeModel($dvCode);

                    $fullExpression = str_replace($getDataViewColumnVal[0][$ek], 'bpGetWfmNextStatusBySelectedRow(\'' . $dvIdBySelect . '\')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'getWfmNextStatusListByRowData(') !== false) {
            preg_match_all('/getWfmNextStatusListByRowData\((.*?)\)/i', $fullExpression, $getDataViewColumnVal);

            if (count($getDataViewColumnVal[0]) > 0) {
                foreach ($getDataViewColumnVal[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $dvCode = strtolower(trim(str_replace("'", '', $evArr[0])));
                    $rowDataQryStr = trim($evArr[1]);

                    $dvIdBySelect = $this->model->getMetaGroupIdByCodeModel($dvCode);

                    $fullExpression = str_replace($getDataViewColumnVal[0][$ek], 'bpGetWfmNextStatusByRowDataQryStr(\'' . $dvIdBySelect . '\', ' . $rowDataQryStr . ')', $fullExpression);
                }
            }
        }
        
        if (strpos($fullExpression, 'changeWfmStatusByRowDataQryStr(') !== false) {
            preg_match_all('/changeWfmStatusByRowDataQryStr\((.*?)\)/i', $fullExpression, $getDataViewColumnVal);

            if (count($getDataViewColumnVal[0]) > 0) {
                foreach ($getDataViewColumnVal[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $dvCode = strtolower(trim(str_replace("'", '', $evArr[0])));
                    $dvIdBySelect = $this->model->getMetaGroupIdByCodeModel($dvCode);
                    $rowDataQryStr = trim($evArr[1]);
                    $thirdArgument = '';
                    
                    if (isset($evArr[2])) {
                        $thirdArgument = ', '.$evArr[2];
                    }

                    $fullExpression = str_replace($getDataViewColumnVal[0][$ek], 'bpChangeWfmStatusByRowDataQryStr(' . $mainSelector . ', checkElement, \'' . $dvIdBySelect . '\', ' . $rowDataQryStr . $thirdArgument . ')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'dataViewExpandAll(') !== false) {
            preg_match_all('/dataViewExpandAll\((.*?)\)/i', $fullExpression, $dataViewRefresh);

            if (count($dataViewRefresh[0]) > 0) {
                foreach ($dataViewRefresh[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $dvCode = strtolower(trim(str_replace("'", '', $evArr[0])));

                    $divIdBySelect = $this->model->getMetaGroupIdByCodeModel($dvCode);
                    $divIdBySelect = $divIdBySelect ? "'" . $divIdBySelect . "'" : $divIdBySelect;

                    if (isset($evArr[1])) {
                        $fullExpression = str_replace($dataViewRefresh[0][$ek], 'bpDataViewExpandAll(' . $mainSelector . ', checkElement, ' . $divIdBySelect . ',' . $evArr[1] . ')', $fullExpression);
                    } else {
                        $fullExpression = str_replace($dataViewRefresh[0][$ek], 'bpDataViewExpandAll(' . $mainSelector . ', checkElement, ' . $divIdBySelect . ')', $fullExpression);
                    }
                }
            }
        }

        if (strpos($fullExpression, 'dataViewCollapseAll(') !== false) {
            preg_match_all('/dataViewExpandAll\((.*?)\)/i', $fullExpression, $dataViewRefresh);

            if (count($dataViewRefresh[0]) > 0) {
                foreach ($dataViewRefresh[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $dvCode = strtolower(trim(str_replace("'", '', $evArr[0])));

                    $divIdBySelect = $this->model->getMetaGroupIdByCodeModel($dvCode);
                    $divIdBySelect = $divIdBySelect ? "'" . $divIdBySelect . "'" : $divIdBySelect;

                    if (isset($evArr[1])) {
                        $fullExpression = str_replace($dataViewRefresh[0][$ek], 'bpDataViewCollapseAll(' . $mainSelector . ', checkElement, ' . $divIdBySelect . ',' . $evArr[1] . ')', $fullExpression);
                    } else {
                        $fullExpression = str_replace($dataViewRefresh[0][$ek], 'bpDataViewCollapseAll(' . $mainSelector . ', checkElement, ' . $divIdBySelect . ')', $fullExpression);
                    }
                }
            }
        }

        if (strpos($fullExpression, 'evaluator(') !== false) {
            preg_match_all('/evaluator\((.*?)\)/i', $fullExpression, $evaluator);

            if (count($evaluator[0]) > 0) {
                foreach ($evaluator[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);

                    $fullExpression = str_replace($evaluator[0][$ek], 'eval(bpExpEvaluator(' . $mainSelector . ', checkElement, \'' . $processId . '\', ' . $evArr[0] . ',' . $evArr[1] . '))', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'getKpiTemplateCellVal(') !== false) {
            preg_match_all('/getKpiTemplateCellVal\((.*?)\)/i', $fullExpression, $getKpiTemplateCellVal);

            if (count($getKpiTemplateCellVal[0]) > 0) {
                foreach ($getKpiTemplateCellVal[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $kpiTempCode = strtolower(trim(str_replace("'", '', $evArr[0])));

                    $kpiTempIdBySelect = $this->model->getKpiTempIdByCodeModel($kpiTempCode);

                    $fullExpression = str_replace($getKpiTemplateCellVal[0][$ek], 'bpGetKpiTemplateCellVal(\'' . $kpiTempIdBySelect . '\', ' . $evArr[1] . ')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'setKpiTemplateCellVal(') !== false) {
            preg_match_all('/setKpiTemplateCellVal\((.*?)\)/i', $fullExpression, $setKpiTemplateCellVal);

            if (count($setKpiTemplateCellVal[0]) > 0) {
                foreach ($setKpiTemplateCellVal[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $kpiTempCode = strtolower(trim(str_replace("'", '', $evArr[0])));

                    $kpiTempIdBySelect = $this->model->getKpiTempIdByCodeModel($kpiTempCode);

                    $fullExpression = str_replace($setKpiTemplateCellVal[0][$ek], 'bpSetKpiTemplateCellVal(\'' . $kpiTempIdBySelect . '\', ' . $evArr[1] . ', ' . $evArr[2] . ')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'kpiTemplateVisibler(') !== false) {
            preg_match_all('/kpiTemplateVisibler\((.*?)\)/i', $fullExpression, $kpiTemplateVisibler);

            if (count($kpiTemplateVisibler[0]) > 0) {
                foreach ($kpiTemplateVisibler[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $kpiTempCode = strtolower(trim(str_replace("'", '', $evArr[0])));

                    $kpiTempIdBySelect = $this->model->getKpiTempIdByCodeModel($kpiTempCode);

                    $fullExpression = str_replace($kpiTemplateVisibler[0][$ek], 'bpKpiTemplateVisibler(' . $mainSelector . ', checkElement, \'' . $kpiTempIdBySelect . '\', ' . $evArr[1] . ')', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'workSpaceReload(') !== false) {
            preg_match_all('/workSpaceReload\((.*?)\)/i', $fullExpression, $workSpaceReload);

            if (count($workSpaceReload[0]) > 0) {
                foreach ($workSpaceReload[1] as $ek => $ev) {

                    $evArr = explode(',', $ev);
                    $wsCode = trim(str_replace("'", '', $evArr[0]));
                    $wsCodeLower = strtolower($wsCode);
                    $wsIdBySelectRow = $this->model->getWorkspaceIdByCodeModel($wsCodeLower);
                    $wsIdBySelect = $wsIdBySelectRow ? "'" . $wsIdBySelectRow['META_DATA_ID'] . "'" : $wsCode;

                    $fullExpression = str_replace($workSpaceReload[0][$ek], 'bpWorkSpaceReload(' . $wsIdBySelect . ', responseData)', $fullExpression);
                }
            }
        }

        if (strpos($fullExpression, 'bankIpTerminalTransfer(') !== false) {
            preg_match_all('/bankIpTerminalTransfer\((.*?)\);/i', $fullExpression, $bankIpTerminalTransfers);

            if (count($bankIpTerminalTransfers[0]) > 0) {
                foreach ($bankIpTerminalTransfers[1] as $ek => $ev) {

                    $arguments = trim($ev);
                    $argumentsArr = explode(',', $arguments);

                    $fncName = str_replace("'", '', trim($argumentsArr[3]));
                    $fncName = $fncName . '_' . $processId;

                    $fullExpression = str_replace($bankIpTerminalTransfers[0][$ek], "bpBankIpTerminalTransfer($mainSelector, checkElement, " . $argumentsArr[0] . ", " . $argumentsArr[1] . ", " . $argumentsArr[2] . ", '$fncName');", $fullExpression);
                }
            }
        }

        return $fullExpression;
    }

    public function detectedFunctionNamesReplacer($fullExpression) {
        $data = Mdexpression::$detectedFunctionNames;

        foreach ($data as $k => $v) {
            $fullExpression = str_replace($k . '(', $v . '(', $fullExpression);
            $fullExpression = str_replace($k . ' (', $v . '(', $fullExpression);
        }

        return $fullExpression;
    }

    public function statementExpression($getRow) {
        
        $expressionSuperExp = html_entity_decode($getRow['SUPER_GLOBAL_EXPRESSION']);
        $expressionRowExp = html_entity_decode($getRow['ROW_EXPRESSION']);
        $expressionGroupExp = html_entity_decode($getRow['GLOBAL_EXPRESSION']);
        $expressionGloExp = $rowFields = $typeRowFields = '';

        preg_match_all('/\[[\w.]+\]+(.*)\s*=\s*[\[\'\w.]+(.*)/', $expressionRowExp, $parseExpressionEqual);
        preg_match_all('/\[[\w.]+\]+(.*)\s*=\s*[\[\'\w.]+(.*)/', $expressionGroupExp, $parseExpressionGroupEqual);
        preg_match_all('/\[[\w.]+\]+(.*)\s*=\s*[\[\'\w.]+(.*)/', $expressionSuperExp, $parseExpressionGlobalEqual);

        $expressionSuperGloExp = preg_replace('/var (.*?) \=/', 'self::$data[\'_EXPRESSION_GLOBAL\'][\'$1\'] =', $expressionSuperExp, -1, $replacedCount);
        $expressionSuperGloExp = preg_replace('/global (.*?) \=/', 'self::$data[\'_EXPRESSION_GLOBAL\'][\'$1\'] =', $expressionSuperGloExp);
        $expressionGloExp = preg_replace('/var (.*?) \=/', 'self::$data[\'_EXPRESSION_GLOBAL\'][\'$1\'] =', $expressionGroupExp);

        $typeFields = array();

        if (strpos($expressionSuperGloExp, 'setType(') !== false) {
            preg_match_all('/setType\((.*?)\)/i', $expressionSuperGloExp, $setTypes);

            if (count($setTypes[0]) > 0) {
                foreach ($setTypes[1] as $ek => $ev) {

                    if (strpos($ev, ',') !== false) {

                        $evArr = explode(',', $ev);
                        $typeLowerCode = strtolower(trim($evArr[1]));
                        $fieldCode = trim($evArr[0]);

                        $typeFields[$fieldCode] = $typeLowerCode;
                        $typeRowFields .= strtolower($fieldCode) . '=' . $typeLowerCode . ',';
                    }

                    $expressionSuperGloExp = str_replace($setTypes[0][$ek], '', $expressionSuperGloExp);
                }
            }
        }

        if (strpos($expressionSuperGloExp, 'setScale(') !== false) {
            preg_match_all('/setScale\((.*?)\)/i', $expressionSuperGloExp, $setScales);

            if (count($setScales[0]) > 0) {
                foreach ($setScales[1] as $ek => $ev) {

                    if (strpos($ev, ',') !== false) {

                        $evArr = explode(',', $ev);
                        $fieldCode = strtolower(trim($evArr[0]));
                        $scale = strtolower(trim($evArr[1]));

                        Mdstatement::$dataViewColumnsSetScale[$fieldCode] = $scale;
                        $typeRowFields .= $fieldCode . '=setscale,';
                    }

                    $expressionSuperGloExp = str_replace($setScales[0][$ek], '', $expressionSuperGloExp);
                }
            }
        }

        if (strpos($expressionSuperGloExp, 'setScaleFloatEmpty(') !== false) {
            preg_match_all('/setScaleFloatEmpty\((.*?)\)/i', $expressionSuperGloExp, $setScaleFloatEmptys);

            if (count($setScaleFloatEmptys[0]) > 0) {
                foreach ($setScaleFloatEmptys[1] as $ek => $ev) {

                    if (strpos($ev, ',') !== false) {

                        $evArr = explode(',', $ev);
                        $fieldCode = strtolower(trim($evArr[0]));
                        $scale = strtolower(trim($evArr[1]));

                        Mdstatement::$dataViewColumnsSetScale[$fieldCode] = $scale;
                        $typeRowFields .= $fieldCode . '=floatemptyscale,';
                    }

                    $expressionSuperGloExp = str_replace($setScaleFloatEmptys[0][$ek], '', $expressionSuperGloExp);
                }
            }
        }

        if (strpos($expressionSuperGloExp, 'setTrunc(') !== false) {
            preg_match_all('/setTrunc\((.*?)\)/i', $expressionSuperGloExp, $setTruncs);

            if (count($setTruncs[0]) > 0) {
                foreach ($setTruncs[1] as $ek => $ev) {

                    if (strpos($ev, ',') !== false) {

                        $evArr = explode(',', $ev);
                        $scale = trim($evArr[1]);
                        $fieldCode = trim($evArr[0]);

                        Mdstatement::$truncAmountFields[$fieldCode] = $scale;
                    }

                    $expressionSuperGloExp = str_replace($setTruncs[0][$ek], '', $expressionSuperGloExp);
                }
            }
        }

        if ($replacedCount) {

            preg_match_all('/var (.*?) \=/', $expressionSuperExp, $parseExpressionSuperGlobalEqual);

            foreach ($parseExpressionSuperGlobalEqual[1] as $expSVal) {

                if (!empty($expSVal)) {
                    $expSVal = strtolower(trim($expSVal));

                    $expressionSuperGloExp .= 'if (!array_key_exists(\'' . $expSVal . '\', $result[\'rows\'][0])) {
                        array_walk($result[\'rows\'], function(&$value) {          
                            $value[\'' . $expSVal . '\'] = null;
                        }); 
                    } ';
                }
            }
        }

        if (!empty($parseExpressionGlobalEqual[0])) {

            foreach ($parseExpressionGlobalEqual[0] as $globalExpVal) {

                $expExplode = explode('=', $globalExpVal);
                preg_match('/\[(.*?)\]/', $expExplode[0], $expSet);

                $expressionSuperGloExp = str_replace('[' . $expSet[1] . ']', 'self::$data[\'_EXPRESSION_GLOBAL\'][\'' . $expSet[1] . '\']', $expressionSuperGloExp);
            }
        }

        if (!empty($parseExpressionGroupEqual[0])) {

            foreach ($parseExpressionGroupEqual[0] as $groupExpVal) {

                $expExplode = explode('=', $groupExpVal);
                preg_match('/\[(.*?)\]/', $expExplode[0], $expSet);
                preg_match_all('/\[(.*?)\]/', $expExplode[1], $expGet);

                if (!empty($expGet[1])) {
                    foreach ($expGet[1] as $key => $valGetPath) {
                        if (strpos($expressionGloExp, '[' . $valGetPath . '].global()') !== false) {
                            $expressionGloExp = str_replace('[' . $valGetPath . '].global()', 'self::$data[\'_EXPRESSION_GLOBAL\'][\'' . $valGetPath . '\']', $expressionGloExp);
                        }

                        if (isset($typeFields[$valGetPath])) {
                            $rowFields .= $valGetPath . '=' . $typeFields[$valGetPath] . ',';
                            unset($typeFields[$valGetPath]);
                        } else {
                            $rowFields .= $valGetPath . '=bigdecimal,';
                        }
                    }
                }

                $expressionGloExp = str_replace('[' . $expSet[1] . '].global()', 'self::$data[\'_EXPRESSION_GLOBAL\'][\'' . $expSet[1] . '\']', $expressionGloExp);
                $expressionGloExp = str_replace('[' . $expSet[1] . ']', '$row[\'' . strtolower($expSet[1]) . '\']', $expressionGloExp);
            }
        }

        if (!empty($parseExpressionEqual[0])) {

            foreach ($parseExpressionEqual[0] as $expVal) {

                $expExplode = explode('=', $expVal);
                preg_match('/\[(.*?)\]/', $expExplode[0], $expSet);
                preg_match_all('/\[(.*?)\]/', $expExplode[1], $expGet);

                if (!empty($expGet[1])) {
                    foreach ($expGet[1] as $key => $valGetPath) {
                        if (strpos($expressionRowExp, '[' . $valGetPath . '].val()') !== false) {
                            $expressionRowExp = str_replace('[' . $valGetPath . '].val()', '$row[\'' . strtolower($valGetPath) . '\']', $expressionRowExp);
                        } elseif (strpos($expressionRowExp, '[' . $valGetPath . '].global()') !== false) {
                            $expressionRowExp = str_replace('[' . $valGetPath . '].global()', 'self::$data[\'_EXPRESSION_GLOBAL\'][\'' . $valGetPath . '\']', $expressionRowExp);
                        }
                    }
                }

                $expressionRowExp = str_replace('[' . $expSet[1] . '].global()', 'self::$data[\'_EXPRESSION_GLOBAL\'][\'' . $expSet[1] . '\']', $expressionRowExp);
                $expressionRowExp = str_replace('[' . $expSet[1] . ']', '$row[\'' . strtolower($expSet[1]) . '\']', $expressionRowExp);

                $rowFields .= $expSet[1] . '=' . (isset($typeFields[$expSet[1]]) ? $typeFields[$expSet[1]] : 'bigdecimal') . ',';
            }
        }

        $expressionGloExp = str_replace('runProcessValue(', 'self::runProcessValue(', $expressionGloExp);
        $expressionGloExp = str_replace('getOneDataView(', 'self::getOneDataView(', $expressionGloExp);

        $expressionSuperGloExp = str_replace('runProcessValue(', 'self::runProcessValue(', $expressionSuperGloExp);
        $expressionSuperGloExp = str_replace('getOneDataView(', 'self::getOneDataView(', $expressionSuperGloExp);

        preg_match_all('/\[([^\]]*)\].val\(\)/', $expressionGloExp, $groupValPaths);

        if (!empty($groupValPaths[0])) {
            foreach ($groupValPaths[1] as $vgk => $groupValPath) {
                if (!empty($groupValPath)) {

                    $groupValPath = strtolower($groupValPath);

                    $expressionGloExp = str_replace('$row' . $groupValPaths[0][$vgk], 'issetParam(self::$data[\'_EXPRESSION_GLOBAL\'][' . $groupValPath . '])', $expressionGloExp);
                }
            }
        }

        $expressionRowExp = str_replace('.val()', '', $expressionRowExp);
        $expressionGloExp = str_replace('.val()', '', $expressionGloExp);
        $expressionSuperGloExp = str_replace('.val()', '', $expressionSuperGloExp);

        preg_match_all('/\[([^\]]*)\].filter\(\)/', $expressionSuperGloExp, $filterValPath);

        if (!empty($filterValPath[0])) {
            foreach ($filterValPath[1] as $vgk => $valGetPathLast) {
                if (!empty($valGetPathLast)) {

                    $valGetPath = strtolower($valGetPathLast);
                    $valGetPath = 'issetParam(Mdstatement::$filterParams' . "[" . $valGetPath . "])";

                    $expressionSuperGloExp = str_replace('self::$data[\'_EXPRESSION_GLOBAL\']' . $filterValPath[0][$vgk], $valGetPath, $expressionSuperGloExp);
                }
            }
        }

        preg_match_all('/\[([^\]]*)\].filter\(\)/', $expressionRowExp, $filterValPath);

        if (!empty($filterValPath[0])) {
            foreach ($filterValPath[1] as $vgk => $valGetPathLast) {
                if (!empty($valGetPathLast)) {

                    $valGetPath = strtolower($valGetPathLast);
                    $valGetPath = 'issetParam(Mdstatement::$filterParams' . "[" . $valGetPath . "])";

                    $expressionRowExp = str_replace('$row' . $filterValPath[0][$vgk], $valGetPath, $expressionRowExp);
                }
            }
        }

        if ($rowFields == '' && $typeRowFields != '') {
            $rowFields = $typeRowFields;
        }

        if ($typeFields) {
            foreach ($typeFields as $typeKey => $typeVal) {
                $rowFields .= $typeKey . '=' . $typeVal . ',';
            }
        }

        $expressionRowExp = str_replace('formatAmount(', 'Number::fractionRange(', $expressionRowExp);

        return array(
            'rowExp' => $expressionRowExp,
            'gloExp' => $expressionGloExp,
            'superGloExp' => Str::removeNL($expressionSuperGloExp),
            'rowFields' => rtrim(str_replace(',,', ',', $rowFields), ',')
        );
    }

    public function strToScript() {
        $processId = Input::post('processId');
        $uniqId = Input::post('uniqId');
        $expStr = Input::post('expStr');
        $eventType = Input::post('eventType');

        $mdExp = new Mdexpression();

        if ($eventType == 'event') {
            $result = $mdExp->fullExpressionConvertEvent($expStr, $processId);
        } else {
            $result = $mdExp->fullExpressionConvertWithoutEvent($expStr, $processId);
        }

        $result = str_replace('_' . $processId, '_' . $uniqId, $result);

        echo json_encode($result, JSON_UNESCAPED_UNICODE); exit;
    }

    public function fullExpressionConvertEventTaxonamy($fullExpression = '', $processId = '', $tag, $dtl = false, $dtlCombo = false, $mainRow = array(), $editNtrMode = false)
    {
        if (empty($fullExpression)) {
            return '';
        }

        if (self::$isMultiPathConfig == false) {
            self::setMultiPathConfig($processId);
        } else {
            $this->load->model('mdexpression', 'middleware/models/');
        }

        $mainSelector = self::$setMainSelector ? self::$setMainSelector : self::$mainSelector . $processId;

        $fullExpression = self::fullExpressionReplaceFncNames($processId, $mainSelector, $fullExpression);

        $result = array_filter(explode("};", $fullExpression));
        $expressionToJs = '';

        foreach ($result as $valRowExp) {

            preg_match('/\[(.*?)\]/', $valRowExp, $expEventCatch);
            preg_match_all('/(?<![a-zA-Z0-9\[+])\[[\w.]+\]\s*=\s*[\[|\(\'\w.]+(.*)/', $valRowExp, $parseExpressionEqual);
            preg_match_all('/\[(.*?)\].hide\(\)|\[(.*?)\].show\(\)|\[(.*?)\].disable\(\)|\[(.*?)\].enable\(\)|\[(.*?)\].required\(\)|\[(.*?)\].nonrequired\(\)/', $fullExpression, $parseExpressionlAttr);
            preg_match_all('/\[[\w.]+\](.*)\s*(==|===|!=|!==|>|<|>=|<=)\s*[\[\w]*(.*)/', $fullExpression, $parseExpressionEqualEqual);
            preg_match_all('/message\((.*)+\)/', $valRowExp, $parseExpressionMessage);
            preg_match_all('/fiscalPeriodMessage\((.*)+\)/', $valRowExp, $parseExpressionFiscalPeriodMessage);
            preg_match_all('/\[[\w.]+\](.label|.control)\((.*)\)/', $valRowExp, $parseExpressionStyle);
            preg_match_all('/\[[\w.]+\].trigger\((.*)\)/', $valRowExp, $parseExpressionTrigger);
            preg_match_all('/\[[\w.]+\].rowTrigger\((.*)\)/', $valRowExp, $parseExpressionTriggerRow);

            // <editor-fold defaultstate="collapsed" desc="CONVERT JS EVENT">
            if (!empty($parseExpressionEqual[0])) {
                foreach ($parseExpressionEqual[0] as $kkk => $expVal) {
                    $expExplode = explode("=", $expVal);
                    preg_match('/\[(.*?)\]/', $expExplode[0], $expSet);
                    preg_match_all('/\[(.*?)\]/', $expExplode[1], $expGet);
                    $exp = trim(str_replace(';', '', $expExplode[1]));

                    $getMetaRow = $this->model->getMetaTypeCode($processId, $expSet[1]);
                    $typeCode = $getMetaRow['type'];

                    $getReplace = $exp;
                    if (!empty($expGet[1])) {
                        foreach ($expGet[1] as $key => $valGetPath) {
                            if (strpos($expExplode[1], ".val()") !== false) {
                                $getMetaRow2 = $this->model->getMetaTypeCode($processId, $valGetPath);
                                $exp = self::fullExpressionConvertGet($getMetaRow2['type'], $getMetaRow2, $mainSelector, $getMetaRow2['sidebarName'], $expGet[0][$key] . '.val()', $valGetPath, $exp);
                            }
                        }
                    }

                    $getReplaced = strtr($getReplace, array("(" => "\(", ")" => "\)", "|" => "\|", "'" => "\'", "*" => "\*", "[" => "\[", "]" => "\]", "/" => "\/", "+" => "\+", "=" => "\=", ":" => "\:"));

                    if ($getMetaRow['parentId'] != '') {
                        if ($typeCode == 'boolean') {
                            $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'checkboxCheckerUpdate(getBpElement(' . $mainSelector . ', (typeof element === \'undefined\' ? \'open\' : element), \'' . $expSet[1] . '\'), ' . $getReplace . ')', $valRowExp);
                        } else {
                            if ($getMetaRow['sidebarName'] != '') {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setBpRowParamNumSidebar(' . $mainSelector . ', $(this), \'' . $expSet[1] . '\', (' . $exp . '))', $valRowExp);
                            } else {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpRowParamNum(' . $mainSelector . ', $(this), \'' . $expSet[1] . '\', (' . $exp . '));', $valRowExp);
                            }
                        }
                    } else {
                        if ($typeCode == 'boolean' && $getMetaRow['isShow'] == '1') {
                            $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'checkboxCheckerUpdate($("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . '), ' . $getReplace . ')', $valRowExp);
                        } elseif ($typeCode == 'date' && $getMetaRow['isShow'] == '1') {
                            $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setBpHdrParamDate(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '))', $valRowExp);
                        } elseif ($typeCode == 'datetime' && $getMetaRow['isShow'] == '1') {
                            $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setBpHdrParamDateTime(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '))', $valRowExp);
                        } elseif ($typeCode == 'bigdecimal' || $typeCode == 'long' || $typeCode == 'number' || $typeCode == 'integer' || $typeCode == 'decimal') {
                            if ($getMetaRow['LOOKUP_TYPE'] == 'popup' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['isShow'] == '1') {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setLookupPopupValue($("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . '), ' . $exp . ')', $valRowExp);
                            } elseif ($getMetaRow['LOOKUP_TYPE'] == 'combo' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['isShow'] == '1') {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpRowParamNum(' . $mainSelector . ', $(this), \'' . $expSet[1] . '\', (' . $exp . '));', $valRowExp);
                            } elseif (($typeCode == 'number' || $typeCode == 'integer' || $typeCode == 'decimal') && $getMetaRow['isShow'] == '1') {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', '$("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').autoNumeric("set", ' . $exp . ');', $valRowExp);
                            } elseif ($typeCode == 'bigdecimal' && $getMetaRow['isShow'] == '1') {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpHdrParamNum(' . $mainSelector . ', \'' . $expSet[1] . '\', (' . $exp . '));', $valRowExp);
                            } else {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', '$("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').val(' . $exp . ')', $valRowExp);
                            }
                        } else {
                            if ($getMetaRow['LOOKUP_TYPE'] == 'combo' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['isShow'] == '1') {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'setBpRowParamNum(' . $mainSelector . ', $(this), \'' . $expSet[1] . '\', (' . $exp . '));', $valRowExp);
                            } elseif ($getMetaRow['LOOKUP_TYPE'] == 'popup' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['isShow'] == '1') {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', 'setLookupPopupValue($("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . '), ' . $exp . ')', $valRowExp);
                            } else if (($typeCode == 'description' || $typeCode == 'description_auto') && $getMetaRow['isShow'] == '1') {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', '$("textarea[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').val(' . $exp . ')', $valRowExp);
                            } else {
                                $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . '/', '$("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').val(' . $exp . ')', $valRowExp);
                            }
                        }
                    }
                }
            }

            if (!empty($parseExpressionEqualEqual[0])) {
                foreach ($parseExpressionEqualEqual[0] as $expVal) {
                    preg_match_all('/\[(.*?)\]/', $expVal, $expGet);
                    $expValVar = $expVal;

                    if (!empty($expGet[1])) {
                        foreach ($expGet[1] as $key => $valGetPath) {
                            $getMetaRow = $this->model->getMetaTypeCode($processId, $valGetPath);
                            $expVal = self::fullExpressionConvertGet($getMetaRow['type'], $getMetaRow, $mainSelector, "", $expGet[0][$key] . '.val()', $valGetPath, $expVal);
                        }
                    }
                    $valRowExp = str_replace($expValVar, $expVal, $valRowExp);
                }
            }

            if (!empty($parseExpressionlAttr[0])) {
                foreach ($parseExpressionlAttr[0] as $expVal) {
                    preg_match('/\[(.*?)\]/', $expVal, $expSetAttr);

                    if (strpos($expSetAttr[1], ",") !== false) {

                        $expSetAttrExpression = '';
                        $expSetAttrSplitArr = explode(",", $expSetAttr[1]);

                        if (strpos($expVal, ".hide()") !== false) {

                            foreach ($expSetAttrSplitArr as $expSetAttrSplit) {
                                $expSetAttrSplit = trim($expSetAttrSplit);
                                $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttrSplit);

                                if ($getMetaRow['parentId'] != '')
                                    $expSetAttrExpression .= 'setBpRowParamHide(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                else
                                    $expSetAttrExpression .= '$("input[data-path=\'' . $expSetAttrSplit . '\'], textarea[data-path=\'' . $expSetAttrSplit . '\'], th[data-cell-path=\'' . $expSetAttrSplit . '\'], tr[data-cell-path=\'' . $expSetAttrSplit . '\'], td[data-cell-path=\'' . $expSetAttrSplit . '\'], label[data-label-path=\'' . $expSetAttrSplit . '\'], div[data-section-path=\'' . $expSetAttrSplit . '\'], li[data-li-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').hide();';
                            }

                            $valRowExp = str_replace($expSetAttr[0] . ".hide();", $expSetAttrExpression, $valRowExp);
                        } elseif (strpos($expVal, ".show()") !== false) {

                            foreach ($expSetAttrSplitArr as $expSetAttrSplit) {
                                $expSetAttrSplit = trim($expSetAttrSplit);
                                $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttrSplit);

                                if ($getMetaRow['parentId'] != '')
                                    $expSetAttrExpression .= 'setBpRowParamShow(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                else
                                    $expSetAttrExpression .= '$("input[data-path=\'' . $expSetAttrSplit . '\'], textarea[data-path=\'' . $expSetAttrSplit . '\'], th[data-cell-path=\'' . $expSetAttrSplit . '\'], tr[data-cell-path=\'' . $expSetAttrSplit . '\'], td[data-cell-path=\'' . $expSetAttrSplit . '\'], label[data-label-path=\'' . $expSetAttrSplit . '\'], div[data-section-path=\'' . $expSetAttrSplit . '\'], li[data-li-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').show();';
                            }

                            $valRowExp = str_replace($expSetAttr[0] . ".show();", $expSetAttrExpression, $valRowExp);
                        } elseif (strpos($expVal, ".disable()") !== false) {

                            foreach ($expSetAttrSplitArr as $expSetAttrSplit) {
                                $expSetAttrSplit = trim($expSetAttrSplit);
                                $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttrSplit);

                                if ($getMetaRow['type'] == 'group') {
                                    $expSetAttrExpression .= 'setBpRowGroupDisable(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                } else {
                                    if ($getMetaRow['parentId'] != '') {
                                        $expSetAttrExpression .= 'setBpRowParamDisable(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                    } else {
                                        if ($getMetaRow['type'] == 'boolean' && $getMetaRow['isShow'] == '1') {
                                            $expSetAttrExpression .= 'checkboxDisableUpdate( $("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . '));';
                                        } else {
                                            $expSetAttrExpression .= 'if ($("[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').hasClass("descriptionInit")) {
                                                        $("textarea[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').prop("readonly", true);                        
                                                    } else {
                                                        $("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').prop("readonly", true);                        
                                                    }';
                                            $expSetAttrExpression .= 'if($("select[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').hasClass("select2")) {
                                                                $("select[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').select2(\'readonly\', true);
                                                            } else {
                                                                $("select[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').attr(\'style\', \'pointer-events: none; background-color: #eeeeee !important;\');
                                                            }';
                                            $expSetAttrExpression .= 'if($("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").length > 0) {
                                                            $("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("input[type=\'text\']").attr(\'readonly\', \'readonly\');
                                                            $("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find(".input-group-btn > button:not([data-more-metaid])").attr(\'style\', \'pointer-events: none; background-color: #eeeeee !important\').prop(\'disabled\', true);                                
                                                            }';
                                        }
                                    }
                                }
                            }

                            $valRowExp = str_replace($expSetAttr[0] . ".disable();", $expSetAttrExpression, $valRowExp);
                        } elseif (strpos($expVal, ".enable()") !== false) {

                            foreach ($expSetAttrSplitArr as $expSetAttrSplit) {
                                $expSetAttrSplit = trim($expSetAttrSplit);
                                $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttrSplit);

                                if ($getMetaRow['type'] == 'group') {
                                    $expSetAttrExpression .= 'setBpRowGroupEnable(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                } else {
                                    if ($getMetaRow['parentId'] != '') {
                                        $expSetAttrExpression .= 'setBpRowParamEnable(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                    } elseif ($getMetaRow['type'] == 'boolean' && $getMetaRow['isShow'] == '1') {
                                        $expSetAttrExpression .= 'checkboxEnableUpdate($("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . '));';
                                    } else {

                                        if ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'combo') {
                                            $expSetAttrExpression .= 'if($("select[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').hasClass("select2")) {
                                                    $("select[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').select2(\'readonly\', false).select2(\'enable\');
                                                } else {
                                                    $("select[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').removeAttr("style");
                                                }';
                                        } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'popup') {
                                            $expSetAttrExpression .= '
                                                $("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("input[type=\'text\']").removeAttr(\'readonly\');
                                                $("input[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("button").removeAttr(\'style\').prop(\'disabled\', false);';
                                        } else {
                                            $expSetAttrExpression .= '$("input[data-path=\'' . $expSetAttrSplit . '\'], textarea[data-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').removeAttr("readonly");';
                                        }
                                    }
                                }
                            }

                            $valRowExp = str_replace($expSetAttr[0] . ".enable();", $expSetAttrExpression, $valRowExp);
                        } elseif (strpos($expVal, ".required()") !== false) {

                            foreach ($expSetAttrSplitArr as $expSetAttrSplit) {
                                $expSetAttrSplit = trim($expSetAttrSplit);
                                $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttrSplit);

                                if ($getMetaRow['parentId'] != '') {
                                    $expSetAttrExpression .= 'setBpRowParamRequired(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                } else {
                                    $expSetAttrExpression .= 'bpSetHeaderParamRequired(' . $mainSelector . ', \'' . $expSetAttrSplit . '\');';
                                }
                            }

                            $valRowExp = str_replace($expSetAttr[0] . ".required();", $expSetAttrExpression, $valRowExp);
                        } elseif (strpos($expVal, ".nonrequired()") !== false) {

                            foreach ($expSetAttrSplitArr as $expSetAttrSplit) {
                                $expSetAttrSplit = trim($expSetAttrSplit);
                                $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttrSplit);

                                if ($getMetaRow['parentId'] != '') {
                                    $expSetAttrExpression .= 'setBpRowParamNonRequired(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                } else {
                                    $expSetAttrExpression .= 'setBpHeaderParamNonRequired(' . $mainSelector . ', $(this), \'' . $expSetAttrSplit . '\');';
                                }
                            }

                            $valRowExp = str_replace($expSetAttr[0] . ".nonrequired();", $expSetAttrExpression, $valRowExp);
                        }
                    } else {
                        $getMetaRow = $this->model->getMetaTypeCode($processId, $expSetAttr[1]);

                        if (strpos($expVal, ".hide()") !== false) {
                            if ($getMetaRow['parentId'] != '')
                                $valRowExp = str_replace($expSetAttr[0] . ".hide();", 'setBpRowParamHide(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $valRowExp);
                            else
                                $valRowExp = str_replace($expSetAttr[0] . ".hide();", '$("input[data-path=\'' . $expSetAttr[1] . '\'], th[data-cell-path=\'' . $expSetAttr[1] . '\'], tr[data-cell-path=\'' . $expSetAttr[1] . '\'], td[data-cell-path=\'' . $expSetAttr[1] . '\'], label[data-label-path=\'' . $expSetAttr[1] . '\'], div[data-section-path=\'' . $expSetAttr[1] . '\'], li[data-li-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').hide();', $valRowExp);
                        } elseif (strpos($expVal, ".show()") !== false) {
                            if ($getMetaRow['parentId'] != '')
                                $valRowExp = str_replace($expSetAttr[0] . ".show();", 'setBpRowParamShow(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $valRowExp);
                            else
                                $valRowExp = str_replace($expSetAttr[0] . ".show();", '$("input[data-path=\'' . $expSetAttr[1] . '\'], th[data-cell-path=\'' . $expSetAttr[1] . '\'], tr[data-cell-path=\'' . $expSetAttr[1] . '\'], td[data-cell-path=\'' . $expSetAttr[1] . '\'], label[data-label-path=\'' . $expSetAttr[1] . '\'], div[data-section-path=\'' . $expSetAttr[1] . '\'], li[data-li-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').show();', $valRowExp);
                        } elseif (strpos($expVal, ".disable()") !== false) {
                            if ($getMetaRow['parentId'] != '') {
                                $valRowExp = str_replace($expSetAttr[0] . ".disable();", 'setBpRowParamDisable(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $valRowExp);
                            } else {
                                if ($getMetaRow['type'] == 'boolean' && $getMetaRow['isShow'] == '1') {
                                    $setDisable = 'checkboxDisableUpdate( $("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . '));';
                                } else {
                                    $setDisable = '$("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').prop("readonly", true);';
                                    $setDisable .= 'if($("select[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').hasClass("select2")) {
                                                        $("select[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').select2(\'readonly\', true);
                                                    } else {
                                                        $("select[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').attr(\'style\', \'pointer-events: none; background-color: #eeeeee !important;\');
                                                    }';
                                    $setDisable .= 'if($("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").length > 0) {
                                                    $("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("input[type=\'text\']").attr(\'readonly\', \'readonly\');
                                                    $("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find(".input-group-btn > button:not([data-more-metaid])").attr(\'style\', \'pointer-events: none; background-color: #eeeeee !important\');                                
                                                    }';
                                }
                                $valRowExp = str_replace($expSetAttr[0] . ".disable();", $setDisable, $valRowExp);
                            }
                        } elseif (strpos($expVal, ".enable()") !== false) {

                            if ($getMetaRow['parentId'] != '')
                                $valRowExp = str_replace($expSetAttr[0] . ".enable();", 'setBpRowParamEnable(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $valRowExp);
                            else {

                                if ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'combo') {
                                    $setEnable = 'if($("select[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').hasClass("select2")) {
                                            $("select[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').select2(\'readonly\', false);
                                        } else {
                                            $("select[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').removeAttr("style");
                                        }';
                                } elseif ($getMetaRow['isShow'] == '1' && $getMetaRow['LOOKUP_META_DATA_ID'] != '' && $getMetaRow['LOOKUP_TYPE'] == 'popup') {
                                    $setEnable = '
                                        $("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("input[type=\'text\']").removeAttr(\'readonly\');
                                        $("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').closest("div.meta-autocomplete-wrap").find("button").removeAttr(\'style\').prop(\'disabled\', false);';
                                } elseif ($getMetaRow['type'] == 'boolean' && $getMetaRow['isShow'] == '1') {
                                    $setEnable = 'checkboxEnableUpdate( $("input[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . '));';
                                } else {
                                    $setEnable = '$("[data-path=\'' . $expSetAttr[1] . '\']", ' . $mainSelector . ').removeAttr("readonly");';
                                }
                                $valRowExp = str_replace($expSetAttr[0] . ".enable();", $setEnable, $valRowExp);
                            }
                        } elseif (strpos($expVal, ".required()") !== false) {

                            if ($getMetaRow['parentId'] != '') {
                                $valRowExp = str_replace($expSetAttr[0] . ".required();", 'setBpRowParamRequired(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $valRowExp);
                            } else {
                                $valRowExp = str_replace($expSetAttr[0] . ".required();", 'setBpHeaderParamRequired(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $valRowExp);
                            }
                        } elseif (strpos($expVal, ".nonrequired()") !== false) {
                            if ($getMetaRow['parentId'] != '') {
                                $valRowExp = str_replace($expSetAttr[0] . ".nonrequired();", 'setBpRowParamNonRequired(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $valRowExp);
                            } else {
                                $valRowExp = str_replace($expSetAttr[0] . ".nonrequired();", 'setBpHeaderParamNonRequired(' . $mainSelector . ', $(this), \'' . $expSetAttr[1] . '\');', $valRowExp);
                            }
                        }
                    }
                }
            }

            if (!empty($parseExpressionMessage[0])) {
                foreach ($parseExpressionMessage[0] as $expVal) {
                    preg_match_all('/message(\((.*)+\))/', $expVal, $mesgGet);
                    $mesgGet = explode(',', $mesgGet[1][0]);

                    $message = trim(rtrim($mesgGet[1], ')'));

                    if (strpos($message, "'") === false) {
                        $message = "'" . Lang::line($message) . "'";
                    }

                    $valRowExp = str_replace($expVal . ';', 'PNotify.removeAll(); new PNotify({title: \'' . ltrim($mesgGet[0], "(") . '\', text: ' . $message . ', type: \'' . ltrim($mesgGet[0], "(") . '\', sticker: false, addclass: pnotifyPosition});', $valRowExp);
                }
            }

            if (!empty($parseExpressionFiscalPeriodMessage[0])) {
                foreach ($parseExpressionFiscalPeriodMessage[0] as $expFpVal) {
                    preg_match_all('/fiscalPeriodMessage(\((.*)+\))/', $expFpVal, $mesgGet);
                    $mesgGet = explode(',', $mesgGet[1][0]);

                    $message = trim(rtrim($mesgGet[1], ')'));

                    if (strpos($message, "'") === false) {
                        $message = "'" . Lang::line($message) . "'";
                    }

                    $valRowExp = str_replace($expFpVal . ';', 'showFiscalPeriodMessage(\'' . ltrim($mesgGet[0], "(") . '\', ' . $message . ');', $valRowExp);
                }
            }

            if (!empty($parseExpressionStyle[0])) {
                foreach ($parseExpressionStyle[0] as $expVal) {
                    if (strpos($expVal, ".label(") !== false && strpos($expVal, ".control(") !== false) {
                        preg_match_all('/\[(.*)\](.label\((.*)\))(.control\((.*)\))/', $expVal, $expStyleGet);
                        $expVal = $expVal . ";";
                        $getMetaRow = $this->model->getMetaTypeCode($processId, $expStyleGet[1][0]);

                        if ($expStyleGet[3][0] === 'reset') {
                            if ($getMetaRow['parentId'] != '') {
                                $valRowExp = str_replace($expVal, 'setBpRowParamLabelRemoveStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\'); ##CONTROL##', $valRowExp);
                            } else {
                                $valRowExp = str_replace($expVal, '$("label[data-label-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').removeAttr("style"); ##CONTROL##', $valRowExp);
                            }
                        } else {
                            if ($getMetaRow['parentId'] != '') {
                                $valRowExp = str_replace($expVal, 'setBpRowParamLabelStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\', \'' . $expStyleGet[3][0] . '\'); ##CONTROL##', $valRowExp);
                            } else {
                                $valRowExp = str_replace($expVal, '$("label[data-label-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').attr("style", "' . $expStyleGet[3][0] . '"); ##CONTROL##', $valRowExp);
                            }
                        }
                        if ($expStyleGet[5][0] === 'reset') {
                            if ($getMetaRow['parentId'] != '') {
                                $valRowExp = str_replace('##CONTROL##', 'setBpRowParamRemoveStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\');', $valRowExp);
                            } else {
                                $valRowExp = str_replace('##CONTROL##', '$("[data-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').removeAttr("style");', $valRowExp);
                            }
                        } else {
                            if ($getMetaRow['parentId'] != '') {
                                $valRowExp = str_replace('##CONTROL##', 'setBpRowParamStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\', \'' . $expStyleGet[5][0] . '\');', $valRowExp);
                            } else {
                                $valRowExp = str_replace('##CONTROL##', '$("[data-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').attr("style", "' . $expStyleGet[5][0] . '");', $valRowExp);
                            }
                        }
                    }
                    if (strpos($expVal, ".label(") !== false && strpos($expVal, ".control(") === false) {
                        preg_match_all('/\[(.*)\](.label\((.*)\))/', $expVal, $expStyleGet);
                        $expVal = $expVal . ";";
                        $getMetaRow = $this->model->getMetaTypeCode($processId, $expStyleGet[1][0]);

                        if ($expStyleGet[3][0] === 'reset') {
                            if ($getMetaRow['parentId'] != '') {
                                $valRowExp = str_replace($expVal, 'setBpRowParamLabelRemoveStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\');', $valRowExp);
                            } else {
                                $valRowExp = str_replace($expVal, '$("label[data-label-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').removeAttr("style");', $valRowExp);
                            }
                        } else {
                            if ($getMetaRow['parentId'] != '') {
                                $valRowExp = str_replace($expVal, 'setBpRowParamLabelStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\', \'' . $expStyleGet[3][0] . '\');', $valRowExp);
                            } else {
                                $valRowExp = str_replace($expVal, '$("label[data-label-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').attr("style", "' . $expStyleGet[3][0] . '");', $valRowExp);
                            }
                        }
                    }
                    if (strpos($expVal, ".label(") === false && strpos($expVal, ".control(") !== false) {
                        preg_match_all('/\[(.*)\](.control\((.*)\))/', $expVal, $expStyleGet);
                        $expVal = $expVal . ";";
                        $getMetaRow = $this->model->getMetaTypeCode($processId, $expStyleGet[1][0]);

                        if ($expStyleGet[3][0] === 'reset') {
                            if ($getMetaRow['parentId'] != '') {
                                $valRowExp = str_replace($expVal, 'setBpRowParamRemoveStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\');', $valRowExp);
                            } else {
                                $valRowExp = str_replace($expVal, '$("[data-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').removeAttr("style");', $valRowExp);
                            }
                        } else {
                            if ($getMetaRow['parentId'] != '') {
                                $valRowExp = str_replace($expVal, 'setBpRowParamStyle(' . $mainSelector . ', $(this), \'' . $expStyleGet[1][0] . '\', \'' . $expStyleGet[3][0] . '\');', $valRowExp);
                            } else {
                                $valRowExp = str_replace($expVal, '$("[data-path=\'' . $expStyleGet[1][0] . '\']", ' . $mainSelector . ').attr("style", "' . $expStyleGet[3][0] . '");', $valRowExp);
                            }
                        }
                    }
                }
            }

            if (!empty($parseExpressionTrigger[0])) {
                foreach ($parseExpressionTrigger[0] as $kkk => $expVal) {

                    preg_match('/\[(.*?)\]/', $expVal, $expSet);
                    $getMetaRow = $this->model->getMetaTypeCode($processId, $expSet[1]);

                    if ($getMetaRow['LOOKUP_TYPE'] == 'combo' && $getMetaRow['LOOKUP_META_DATA_ID'] != '') {
                        $valRowExp = preg_replace('/\[' . $expSet[1] . '\].trigger\(/', '$("select[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').trigger(', $valRowExp);
                    } else {
                        $valRowExp = preg_replace('/\[' . $expSet[1] . '\].trigger\(/', '$("input[data-path=\'' . $expSet[1] . '\']", ' . $mainSelector . ').trigger(', $valRowExp);
                    }
                }
            }

            if (!empty($parseExpressionTriggerRow[0])) {
                foreach ($parseExpressionTriggerRow[0] as $kkk => $expVal) {

                    preg_match('/\[(.*?)\]/', $expVal, $expSet);
                    $getMetaRow = $this->model->getMetaTypeCode($processId, $expSet[1]);

                    if ($getMetaRow['LOOKUP_TYPE'] == 'combo' && $getMetaRow['LOOKUP_META_DATA_ID'] != '') {
                        $valRowExp = preg_replace('/\[' . $expSet[1] . '\].rowTrigger\(/', '$("select[data-path=\'' . $expSet[1] . '\']", $(this).closest("tr")).trigger(', $valRowExp);
                    } else {
                        $valRowExp = preg_replace('/\[' . $expSet[1] . '\].rowTrigger\(/', '$("input[data-path=\'' . $expSet[1] . '\']", $(this).closest("tr")).trigger(', $valRowExp);
                    }
                }
            }
            // </editor-fold>


            if (!empty($expEventCatch)) {

                $eventFieldMetaRow = $this->model->getMetaTypeCode($processId, $expEventCatch[1]);

                if (strpos($valRowExp, "].keyup()") !== false) {

                    if ($eventFieldMetaRow['type'] == 'description' || $eventFieldMetaRow['type'] == 'description_auto') {
                        $valRowExp = str_replace($expEventCatch[0] . ".keyup()", $mainSelector . '.on("keyup", "textarea[data-path=\'' . $expEventCatch[1] . '\']", function(e){ eventDelay(function()', $valRowExp);
                    } else {
                        $valRowExp = str_replace($expEventCatch[0] . ".keyup()", $mainSelector . '.on("keyup", "input[data-path=\'' . $expEventCatch[1] . '\']", function(e){ eventDelay(function()', $valRowExp);
                    }

                    $valRowExp .= '}, 250);';
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, '].kpikeyup()') !== false) {
                    $kpiPathArr = explode('.', $expEventCatch[1]);

                    if (count($kpiPathArr) == 2) {
                        $valRowExp = str_replace($expEventCatch[0] . '.kpikeyup()', $mainSelector . '.on("keyup", "tr[data-dtl-code=\'' .  strtolower($kpiPathArr[0]) . '\'] [data-path=\'kpiDmDtl.' . $kpiPathArr[1] . '\']", function(e){ eventDelay(function()', $valRowExp);
                    } else {
                        $valRowExp = str_replace($expEventCatch[0] . '.kpikeyup()', $mainSelector . '.on("keyup", "[data-path=\'kpiDmDtl.' . $kpiPathArr[0] . '\']", function(e){ eventDelay(function()', $valRowExp);
                    }

                    $valRowExp .= '}, 250);';
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, "].keydown()") !== false) {
                    $valRowExp = str_replace($expEventCatch[0] . ".keydown()", $mainSelector . '.on("keydown", "input[data-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, "].change()") !== false) {

                    if ($eventFieldMetaRow['LOOKUP_TYPE'] == 'combo' && $eventFieldMetaRow['LOOKUP_META_DATA_ID'] != '') {
                        $valRowExp = str_replace($expEventCatch[0] . ".change()", $mainSelector . '.on("change", "select[data-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    } elseif ($eventFieldMetaRow['type'] == 'date' || $eventFieldMetaRow['type'] == 'datetime') {
                        $valRowExp = str_replace($expEventCatch[0] . ".change()", $mainSelector . '.on("changeDate", "input[data-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    } elseif ($eventFieldMetaRow['type'] == 'description' || $eventFieldMetaRow['type'] == 'description_auto') {
                        $valRowExp = str_replace($expEventCatch[0] . ".change()", $mainSelector . '.on("change", "textarea[data-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    } else {
                        $valRowExp = str_replace($expEventCatch[0] . ".change()", $mainSelector . '.on("change", "input[data-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    }
                    $valRowExp .= '});';

                    if ($mainRow && $mainRow['IS_MULTI'] === '1') {
                        $valRowExp .= ' var fSel2 = $("select[data-path=\'' . $expEventCatch[1] . '\']", ' . $mainSelector . ').first(); ';
                        $valRowExp .= ' fSel2.select2("destroy"); '
                            . ' multiSelectionRender_' . $processId . '(\'' . $eventFieldMetaRow['LOOKUP_META_DATA_ID'] . '\', \'' . $eventFieldMetaRow['parentId'] . '\', \'' . $tag . '\', $("span.' . $tag . '", ' . $mainSelector . ').children(), fSel2); ';
                    } elseif (!$dtl) {
                        $valRowExp .= ' var fSel2 = $("select[data-path=\'' . $expEventCatch[1] . '\']", ' . $mainSelector . ').first(); ';
                        $valRowExp .= 'fSel2.select2("destroy"); var fSel2Clone = fSel2.clone(); ';
                        $valRowExp .= '$("span.' . $tag . '", ' . $mainSelector . ').children().append(fSel2Clone); $("span.' . $tag . '", ' . $mainSelector . ').find("select").select2(); ';
                        $valRowExp .= ' $("span.' . $tag . '", ' . $mainSelector . ').find("select").attr("data-autowidth", "1"); ';

                        if (!$editNtrMode) {
                            $valRowExp .= ' setTimeout(function() {
                                                    $("span.' . $tag . '", ' . $mainSelector . ').find("select").select2({
                                                        dropdownAutoWidth: true, 
                                                        escapeMarkup: function(markup) {
                                                            return markup;
                                                        }
                                                    });
                                                    $("span.' . $tag . '", ' . $mainSelector . ').find("select").trigger("change");
                                                }, 1000); ';
                        }
                    } elseif ($dtlCombo) {
                        return $expEventCatch[1];
                    }
                }
                if (strpos($valRowExp, "].kpichange()") !== false) {

                    $kpiPathArr = explode('.', $expEventCatch[1]);

                    if (count($kpiPathArr) == 2) {
                        $valRowExp = str_replace($expEventCatch[0] . '.kpichange()', $mainSelector . '.on("change", "tr[data-dtl-code=\'' .  strtolower($kpiPathArr[0]) . '\'] [data-path=\'kpiDmDtl.' . $kpiPathArr[1] . '\']", function(e)', $valRowExp);
                    } else {
                        $valRowExp = str_replace($expEventCatch[0] . '.kpichange()', $mainSelector . '.on("change", "[data-path=\'kpiDmDtl.' . $kpiPathArr[0] . '\']", function(e)', $valRowExp);
                    }

                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, "].click()") !== false) {
                    if ($eventFieldMetaRow['LOOKUP_TYPE'] == 'combo' && $eventFieldMetaRow['LOOKUP_META_DATA_ID'] != '') {
                        $valRowExp = str_replace($expEventCatch[0] . ".click()", $mainSelector . '.on("click", "select[data-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    } elseif ($eventFieldMetaRow['type'] == 'boolean') {
                        $valRowExp = str_replace($expEventCatch[0] . ".click()", $mainSelector . '.on("click", "input[data-path=\'' . $expEventCatch[1] . '\']:not([data-isdisabled])", function(e)', $valRowExp);
                    } elseif ($eventFieldMetaRow['type'] == 'description' || $eventFieldMetaRow['type'] == 'description_auto') {
                        $valRowExp = str_replace($expEventCatch[0] . ".click()", $mainSelector . '.on("click", "textarea[data-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    } else {
                        $valRowExp = str_replace($expEventCatch[0] . ".click()", $mainSelector . '.on("click", "input[data-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                    }
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, "].kpiclick()") !== false) {

                    $kpiPathArr = explode('.', $expEventCatch[1]);

                    if (count($kpiPathArr) == 2) {
                        $valRowExp = str_replace($expEventCatch[0] . '.kpiclick()', $mainSelector . '.on("click", "tr[data-dtl-code=\'' .  strtolower($kpiPathArr[0]) . '\'] [data-path=\'kpiDmDtl.' . $kpiPathArr[1] . '\']", function(e)', $valRowExp);
                    } else {
                        $valRowExp = str_replace($expEventCatch[0] . '.kpiclick()', $mainSelector . '.on("click", "[data-path=\'kpiDmDtl.' . $kpiPathArr[0] . '\']", function(e)', $valRowExp);
                    }
                    $valRowExp .= '});';
                }
                if (strpos($valRowExp, "].remove()") !== false) {
                    $valRowExp = str_replace($expEventCatch[0] . ".remove()", $mainSelector . '.on("click", "table[data-table-path=\'' . $expEventCatch[1] . '\'] > tbody > tr > td > a.bp-remove-row", function(e)', $valRowExp);
                    $valRowExp .= '});';
                }
            }

            if (strpos($valRowExp, 'saveadd.click()') !== false) { /* Хадгалаад нэмэх */
                $valRowExp = str_replace('saveadd.click()', $mainSelector . '.on("change", "input#saveAddEventInput", function(e)', $valRowExp);
                $valRowExp .= "});";
            }

            if (strpos($valRowExp, 'allcontrol.change()') !== false) { /* Нээгдэж байгаа процессын бүх контролын change */
                $valRowExp = str_replace('allcontrol.change()', $mainSelector . '.on("change", "input, select, textarea", function(e)', $valRowExp);
                $valRowExp .= "});";
            }

            if (strpos($valRowExp, 'template.change()') !== false) { /* BP Template change */
                $valRowExp = str_replace('template.change()', $mainSelector . '.on("change", "select#bpTemplateId_' . $processId . '", function(e)', $valRowExp);
                $valRowExp .= "});";
            }

            if (strpos($valRowExp, 'subdtl.save()') !== false) { /* Level 2 rows ийг хадгалах товчны event */
                $valRowExp = str_replace('subdtl.save()', $mainSelector . '.on("change", ".bp-btn-subdtl", function(e)', $valRowExp);
                $valRowExp .= "});";
            }

            $expressionToJs .= $valRowExp;
        }

        preg_match_all('/\[([^\]]*)\].val\(\)/', $expressionToJs, $getValPath);

        if (!empty($getValPath[0])) {
            foreach ($getValPath[0] as $vgk => $valGetPathLast) {
                if (!empty($valGetPathLast)) {
                    $getMetaRowL = $this->model->getMetaTypeCode($processId, $getValPath[1][$vgk]);
                    $expressionToJs = self::fullExpressionConvertGet($getMetaRowL['type'], $getMetaRowL, $mainSelector, $getMetaRowL['sidebarName'], $valGetPathLast, $getValPath[1][$vgk], $expressionToJs);
                }
            }
        }

        preg_match_all('/sum\(\[(.*?)\]\)/i', $expressionToJs, $sumAggregate); // aggregate (sum)
        preg_match_all('/avg\(\[(.*?)\]\)/i', $expressionToJs, $avgAggregate); // aggregate (avg)
        preg_match_all('/min\(\[(.*?)\]\)/i', $expressionToJs, $minAggregate); // aggregate (min)
        preg_match_all('/max\(\[(.*?)\]\)/i', $expressionToJs, $maxAggregate); // aggregate (max)

        if (count($sumAggregate[1]) > 0) {
            foreach ($sumAggregate[1] as $s => $sv) {
                $getMetaRowAggr = $this->model->getMetaTypeCode($processId, $sv);

                if (empty($getMetaRowAggr['sidebarName'])) {
                    if ($getMetaRowAggr['type'] == 'bigdecimal') {
                        if (count(explode('.', $sv)) >= 3) {
                            $expressionToJs = str_replace($sumAggregate[0][$s], 'getBpBigDecimalFieldSum(\'' . $sv . '\', checkElement, ' . $mainSelector . ')', $expressionToJs);
                        } else {
                            $expressionToJs = str_replace($sumAggregate[0][$s], 'setNumberToFixed($("input[data-path=\'' . $sv . '_bigdecimal\']:not([data-not-aggregate])", ' . $mainSelector . ').sum())', $expressionToJs);
                        }
                    } else {
                        $expressionToJs = str_replace($sumAggregate[0][$s], 'Number($("input[data-path=\'' . $sv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').sum())', $expressionToJs);
                    }
                } else {
                    if ($getMetaRowAggr['type'] == 'bigdecimal') {
                        $expressionToJs = str_replace($sumAggregate[0][$s], '$("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $sv . '_bigdecimal\']:not([data-not-aggregate])", ' . $mainSelector . ').length > 0 ? setNumberToFixed($("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $sv . '_bigdecimal\']:not([data-not-aggregate])", ' . $mainSelector . ').sum()) : setNumberToFixed($("input[data-path=\'' . $sv . '_bigdecimal\']:not([data-not-aggregate])", ' . $mainSelector . ').sum())', $expressionToJs);
                    } else {
                        $expressionToJs = str_replace($sumAggregate[0][$s], '$("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $sv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').length > 0 ? Number($("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $sv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').sum()) : Number($("input[data-path=\'' . $sv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').sum())', $expressionToJs);
                    }
                }
            }
        }
        if (count($avgAggregate[1]) > 0) {
            foreach ($avgAggregate[1] as $a => $av) {

                $getMetaRowAggr = $this->model->getMetaTypeCode($processId, $av);

                if (empty($getMetaRowAggr['sidebarName']))
                    $expressionToJs = str_replace($avgAggregate[0][$a], 'Number($("input[data-path=\'' . $av . '\']:not([data-not-aggregate])", ' . $mainSelector . ').avg())', $expressionToJs);
                else
                    $expressionToJs = str_replace($avgAggregate[0][$a], '$("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $av . '\']:not([data-not-aggregate])", ' . $mainSelector . ').length > 0 ? Number($("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $av . '\']:not([data-not-aggregate])", ' . $mainSelector . ').avg()) : Number($("input[data-path=\'' . $av . '\']:not([data-not-aggregate])", ' . $mainSelector . ').avg())', $expressionToJs);
            }
        }
        if (count($minAggregate[1]) > 0) {
            foreach ($minAggregate[1] as $m => $mv) {

                $getMetaRowAggr = $this->model->getMetaTypeCode($processId, $mv);

                if (empty($getMetaRowAggr['sidebarName']))
                    $expressionToJs = str_replace($minAggregate[0][$m], 'Number($("input[data-path=\'' . $mv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').min())', $expressionToJs);
                else
                    $expressionToJs = str_replace($minAggregate[0][$m], '$("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $mv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').length > 0 ? Number($("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $mv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').min()) : Number($("input[data-path=\'' . $mv . '\']:not([data-not-aggregate])", ' . $mainSelector . ').min())', $expressionToJs);
            }
        }
        if (count($maxAggregate[1]) > 0) {
            foreach ($maxAggregate[1] as $ma => $mav) {

                $getMetaRowAggr = $this->model->getMetaTypeCode($processId, $mav);

                if (empty($getMetaRowAggr['sidebarName']))
                    $expressionToJs = str_replace($maxAggregate[0][$ma], 'Number($("input[data-path=\'' . $mav . '\']:not([data-not-aggregate])", ' . $mainSelector . ').max())', $expressionToJs);
                else
                    $expressionToJs = str_replace($maxAggregate[0][$ma], '$("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $mav . '\']:not([data-not-aggregate])", ' . $mainSelector . ').length > 0 ? Number($("table.bprocess-table-dtl tbody").find("td:last-child").find(".input_html").find("input[data-path=\'' . $mav . '\']:not([data-not-aggregate])", ' . $mainSelector . ').max()) : Number($("input[data-path=\'' . $mav . '\']:not([data-not-aggregate])", ' . $mainSelector . ').max())', $expressionToJs);
            }
        }

        $expressionToJs = str_ireplace(array('function(e){', 'function(e) {'), 'function(e){ var _this = this; var $jthis = $(_this); ', $expressionToJs);
        $expressionToJs = str_ireplace('$(this)', '$jthis', $expressionToJs);
        $expressionToJs = str_ireplace(array('(this)', '( this)', '(this )', '( this )'), '(_this)', $expressionToJs);
        $expressionToJs = str_replace('checkElement', '$jthis', $expressionToJs);

        return $expressionToJs;
    }

    public function getCacheExpression($processId) {
        $this->load->model('mdexpression', 'middleware/models/');
        $result = $this->model->getCacheExpressionModel($processId);
        return $result;
    }

    public function processCacheExpression($expressionStr, $processId, $groupPath, $runMode) {
        
        if (empty($expressionStr)) {
            return '';
        }

        if ($runMode == 'before_save') {
            return self::processCacheExpressionNotLoop($expressionStr, $processId, $groupPath, $runMode);
        }

        if (Mdexpression::$isMultiPathConfig == false) {
            (new Mdexpression())->setMultiPathConfig($processId);
        }

        $prefix = Mdexpression::$cachePrefix;

        preg_match_all('/sum\(\[(.*?)\]\)/i', $expressionStr, $sumAggregate);
        preg_match_all('/(?<![a-zA-Z0-9\[+])\[[\w.]+\]\s*=\s*[\[|\(\'\w.]+(.*)/', $expressionStr, $parseExpressionEqual);

        if (count($sumAggregate[1]) > 0) {
            foreach ($sumAggregate[1] as $s => $sv) {

                $svArr = explode('.', strtolower($sv));
                $rowsPath = $svArr[0];
                $fieldPath = $svArr[1];

                $expressionStr = str_replace($sumAggregate[0][$s], 'helperSumFieldBp($cacheArray[\'' . $rowsPath . '\'], \'' . $fieldPath . '\')', $expressionStr);
            }
        }

        if (!empty($parseExpressionEqual[0])) {
            foreach ($parseExpressionEqual[0] as $expVal) {

                $expExplode = explode('=', $expVal);
                preg_match('/\[(.*?)\]/', $expExplode[0], $expSet);
                preg_match_all('/\[(.*?)\]/', $expExplode[1], $expGet);
                $exp = trim(str_replace(';', '', $expExplode[1]));

                $getReplace = $exp;

                if (!empty($expGet[1])) {
                    foreach ($expGet[1] as $key => $valGetPath) {

                        if (strpos($expExplode[1], '.val()') !== false) {

                            $valGetPath = strtolower($valGetPath);

                            if (strpos($valGetPath, '.') === false) {

                                $valGetPath = "['" . $valGetPath . "']";
                                $exp = str_replace($expGet[0][$key] . '.val()', 'returnNull('.Mdexpression::$cachePrefixHeader . $valGetPath.')', $exp);
                            } else {
                                $valGetPath = str_replace($groupPath, '', $valGetPath);

                                if (strpos($valGetPath, '.') !== false) {
                                    $valGetPath = "['" . implode("']['", explode('.', $valGetPath)) . "']";
                                } else {
                                    $valGetPath = "['" . $valGetPath . "']";
                                }

                                $exp = str_replace($expGet[0][$key] . '.val()', 'returnNull('.$prefix . $valGetPath.')', $exp);
                            }
                        }
                    }
                }

                $getReplaced = strtr($getReplace, array("(" => "\(", ")" => "\)", "|" => "\|", "'" => "\'", "*" => "\*", "[" => "\[", "]" => "\]", "/" => "\/", "+" => "\+", "=" => "\=", ":" => "\:"));

                $setPath = strtolower($expSet[1]);
                $setPath = str_replace($groupPath, '', $setPath);

                if (strpos($setPath, '.') !== false) {
                    $setPath = "['" . implode("']['", explode('.', $setPath)) . "']";
                } else {
                    $setPath = "['" . $setPath . "']";
                }

                if (is_numeric($exp) && $exp == 0) {
                    $exp = "'0'";
                }

                $expressionStr = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', $prefix . $setPath . ' = ' . $exp . ';', $expressionStr);
            }
        }

        $expressionStr = self::cacheExpressionReplaceFncNames($expressionStr, $processId, $groupPath);

        preg_match_all('/\[([^\]]*)\].val\(\)/', $expressionStr, $getValPath);

        if (!empty($getValPath[0])) {
            foreach ($getValPath[1] as $vgk => $valGetPathLast) {
                if (!empty($valGetPathLast)) {

                    $valGetPath = strtolower($valGetPathLast);

                    if (strpos($valGetPath, '.') === false) {

                        $typeCode = '';

                        if (isset(Mdexpression::$multiPathConfig[$valGetPath])) {

                            $configRow = Mdexpression::$multiPathConfig[$valGetPath];
                            $typeCode = $configRow['META_TYPE_CODE'];
                        }

                        if ($typeCode == 'boolean') {

                            $valGetPath = "['" . $valGetPath . "']";
                            $expressionStr = str_replace($getValPath[0][$vgk], 'issetParam(' . Mdexpression::$cachePrefixHeader . $valGetPath . ')', $expressionStr);
                        } else {

                            $valGetPath = "['" . $valGetPath . "']";
                            $expressionStr = str_replace($getValPath[0][$vgk], 'returnNull(' . Mdexpression::$cachePrefixHeader . $valGetPath . ')', $expressionStr);
                        }
                    } else {

                        $valGetPath = str_replace($groupPath, '', $valGetPath);

                        if (strpos($valGetPath, '.') !== false) {
                            $valGetPath = "['" . implode("']['", explode('.', $valGetPath)) . "']";
                        } else {
                            $valGetPath = "['" . $valGetPath . "']";
                        }

                        $valGetPath = 'returnNull(' . $prefix . $valGetPath . ')';

                        $expressionStr = str_replace($getValPath[0][$vgk], $valGetPath, $expressionStr);
                    }
                }
            }
        }

        return $expressionStr;
    }

    public function processCacheExpressionNotLoop($expressionStr, $processId, $groupPath, $runMode) {
        
        if (Mdexpression::$isMultiPathConfig == false) {
            (new Mdexpression())->setMultiPathConfig($processId);
        }

        preg_match_all('/\[([^\]]*)\].val\(\)/', $expressionStr, $getValPath);
        preg_match_all('/sum\(\[(.*?)\]\)/i', $expressionStr, $sumAggregate);

        if (!empty($getValPath[0])) {
            $prefix = '$paramData';

            foreach ($getValPath[1] as $vgk => $valGetPathLast) {
                if (!empty($valGetPathLast)) {

                    $valGetPath = 'returnNull(issetParam(' . $prefix . "['" . $valGetPathLast . "']))";

                    $expressionStr = str_replace($getValPath[0][$vgk], $valGetPath, $expressionStr);
                }
            }
        }

        if (count($sumAggregate[1]) > 0) {
            foreach ($sumAggregate[1] as $s => $sv) {

                $svArr = explode('.', strtolower($sv));
                $rowsPath = $svArr[0];
                $fieldPath = $svArr[1];

                $expressionStr = str_replace($sumAggregate[0][$s], 'helperSumFieldBp($cacheArray[\'' . $rowsPath . '\'], \'' . $fieldPath . '\')', $expressionStr);
            }
        }

        preg_match_all('/message\((.*)+\)/', $expressionStr, $parseExpressionMessage);

        foreach ($parseExpressionMessage[0] as $expVal) {

            preg_match_all('/message(\((.*)+\))/', $expVal, $mesgGet);
            $mesgGet = explode(',', $mesgGet[1][0]);

            $message = trim(rtrim($mesgGet[1], ')'));

            if (isset($mesgGet[2])) {
                $message .= ' ' . trim(rtrim($mesgGet[2], ')'));
            }

            if (strpos($message, "'") === false) {
                $message = "'" . Lang::line($message) . "'";
            }

            $expressionStr = str_replace($expVal . ';', 'Mdwebservice::$cacheExpressionMessage = array(\'status\' => \'' . ltrim($mesgGet[0], "(") . '\', \'message\' => ' . $message . ');', $expressionStr);
        }

        $expressionStr = str_replace('return false;', '', $expressionStr);
        $expressionStr = str_replace('return true;', '', $expressionStr);

        return $expressionStr;
    }

    public function cacheExpressionReplaceFncNames($expressionStr, $processId, $groupPath) {
        
        $prefix = Mdexpression::$cachePrefix;
        $groupPath = strtolower($groupPath);

        if (strpos($expressionStr, 'getLookupFieldValue(') !== false) {
            preg_match_all('/getLookupFieldValue\((.*?)\)/i', $expressionStr, $glfs);

            if (count($glfs[0]) > 0) {
                foreach ($glfs[1] as $ek => $ev) {

                    $evs = explode(',', $ev);
                    $path = str_replace("'", '', $evs[0]);
                    $path = strtolower($path);
                    $path = str_replace($groupPath, '', $path);

                    $expressionStr = str_replace($glfs[0][$ek], "getLookupFieldValue('$path'," . strtolower($evs[1]) . ")", $expressionStr);
                }
            }
        }

        $search = array(
            'getLookupFieldValue(',
            'checkEmptyRowGroup(',
            'getDate(',
            'detailRowRemove(element',
            'round('
        );
        $replace = array(
            'helperGetLookupFieldValBp(' . $prefix . ', ',
            'helperCheckEmptyRowGroup(' . $prefix . ', ',
            'Date::getPrevDate(',
            'unset(' . $prefix,
            'Number::numberFormat(',
        );

        $expressionStr = str_replace($search, $replace, $expressionStr);

        return $expressionStr;
    }

    public static function statementUIExpression($row, $params = array()) {
        
        $result = array();

        if (!empty($row['UI_EXPRESSION'])) {
            $result['headerFooter'] = self::statementUIExpressionParse(html_entity_decode($row['UI_EXPRESSION']));
        }

        if (!empty($row['UI_GROUP_EXPRESSION'])) {
            $result['group'] = self::statementUIExpressionParse(html_entity_decode($row['UI_GROUP_EXPRESSION']));
        }

        if (!empty($row['UI_DETAIL_EXPRESSION'])) {
            $result['detail'] = self::statementUIExpressionParse(html_entity_decode($row['UI_DETAIL_EXPRESSION']));
        }

        if ($result) {
            Mdstatement::$filterParams = Arr::changeKeyLower($params);
        }

        return $result;
    }

    public static function statementUIExpressionParse($expressionStr) 
    {
        preg_match_all('/\[([^\]]*)\].val\(\)/', $expressionStr, $getValPath);
        preg_match_all('/\[([^\]]*)\].filter\(\)/', $expressionStr, $getValPathFilter);
        preg_match_all('/\[(.*?)\].hide\(\)|\[(.*?)\].show\(\)/', $expressionStr, $parseExpressionlAttr);

        if (!empty($getValPath[0])) {
            $prefix = '$rowParams';

            foreach ($getValPath[1] as $vgk => $valGetPathLast) {
                if (!empty($valGetPathLast)) {

                    $valGetPath = strtolower($valGetPathLast);
                    $valGetPath = 'issetParam(' . $prefix . "['" . $valGetPath . "'])";

                    $expressionStr = str_replace($getValPath[0][$vgk], $valGetPath, $expressionStr);
                }
            }
        }

        if (!empty($getValPathFilter[0])) {
            $prefix = 'Mdstatement::$filterParams';

            foreach ($getValPathFilter[1] as $vgk => $valGetPathLast) {
                if (!empty($valGetPathLast)) {

                    $valGetPath = strtolower($valGetPathLast);
                    $valGetPath = 'issetParam(' . $prefix . "['" . $valGetPath . "'])";

                    $expressionStr = str_replace($getValPathFilter[0][$vgk], $valGetPath, $expressionStr);
                }
            }
        }

        if (!empty($parseExpressionlAttr[0])) {

            $prefix = '$objHtmlReplace';

            foreach ($parseExpressionlAttr[0] as $expVal) {
                preg_match('/\[(.*?)\]/', $expVal, $expSetAttr);

                if (strpos($expSetAttr[1], ',') !== false) {

                    $expSetAttrExpression = '';
                    $expSetAttrSplitArr = explode(',', $expSetAttr[1]);

                    if (strpos($expVal, '.hide()') !== false) {

                        foreach ($expSetAttrSplitArr as $expSetAttrSplit) {
                            $expSetAttrSplit = trim($expSetAttrSplit);
                            $expSetAttrExpression .= '$("input[data-path=\'' . $expSetAttrSplit . '\'], textarea[data-path=\'' . $expSetAttrSplit . '\'], th[data-cell-path=\'' . $expSetAttrSplit . '\'], tr[data-cell-path=\'' . $expSetAttrSplit . '\'], td[data-cell-path=\'' . $expSetAttrSplit . '\'], label[data-label-path=\'' . $expSetAttrSplit . '\'], div[data-section-path=\'' . $expSetAttrSplit . '\'], li[data-li-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').hide();';
                        }

                        $expressionStr = str_replace($expSetAttr[0] . '.hide();', $expSetAttrExpression, $expressionStr);
                    } elseif (strpos($expVal, '.show()') !== false) {

                        foreach ($expSetAttrSplitArr as $expSetAttrSplit) {
                            $expSetAttrSplit = trim($expSetAttrSplit);
                            $expSetAttrExpression .= '$("input[data-path=\'' . $expSetAttrSplit . '\'], textarea[data-path=\'' . $expSetAttrSplit . '\'], th[data-cell-path=\'' . $expSetAttrSplit . '\'], tr[data-cell-path=\'' . $expSetAttrSplit . '\'], td[data-cell-path=\'' . $expSetAttrSplit . '\'], label[data-label-path=\'' . $expSetAttrSplit . '\'], div[data-section-path=\'' . $expSetAttrSplit . '\'], li[data-li-path=\'' . $expSetAttrSplit . '\']", ' . $mainSelector . ').show();';
                        }

                        $expressionStr = str_replace($expSetAttr[0] . '.show();', $expSetAttrExpression, $expressionStr);
                    }
                } else {

                    if (strpos($expVal, '.hide()') !== false) {
                        $expressionStr = str_replace($expSetAttr[0] . '.hide();', $prefix . '->find(\'[data-path="' . $expSetAttr[1] . '"]\')->remove();', $expressionStr);
                    } elseif (strpos($expVal, '.show()') !== false) {
                        $expressionStr = str_replace($expSetAttr[0] . '.show();', $prefix . '->find(\'[data-path="' . $expSetAttr[1] . '"]\')->css(\'display\', \'\');', $expressionStr);
                    }
                }
            }
        }

        preg_match_all('/sum\(\[(.*?)\]\)/i', $expressionStr, $sumAggregate); // aggregate (sum)
        preg_match_all('/min\(\#(.*?)\#\)/i', $expressionStr, $minAggregate); // aggregate (min)

        if (count($sumAggregate[1]) > 0) {
            foreach ($sumAggregate[1] as $s => $sv) {

                $sv = strtolower($sv);
                Mdstatement::$data[$sv . '_sum'] = 0;

                $expressionStr = str_replace($sumAggregate[0][$s], 'Mdstatement::$data[\'' . $sv . '_sum\']', $expressionStr);
            }
        }

        if (count($minAggregate[1]) > 0) {
            foreach ($minAggregate[1] as $m => $mv) {

                $mv = strtolower($mv);
                Mdstatement::$data[$mv . '_min'] = 0;

                $expressionStr = str_replace($minAggregate[0][$m], 'Mdstatement::$data[\'' . $mv . '_min\']', $expressionStr);
            }
        }

        return $expressionStr;
    }

    public function glTemplateExpression($templateId, $uniqId) {

        $expression     = null;
        $expressionDb   = $this->db->GetOne("SELECT EXPRESSION FROM FIN_GENERAL_LEDGER_TMP WHERE ID = $templateId");
        $expressionTrim = trim($expressionDb);

        if ($expressionTrim != '') {

            $mainSelector = '$glLoadWindow_' . $uniqId;
            $expressionTrim = html_entity_decode($expressionTrim, ENT_QUOTES);
            $expressionTrim = str_replace('getGlDtlRowCode(', 'bpGetGlDtlRowCode(' . $mainSelector . ', $this', $expressionTrim);
            $result = array_filter(explode("};", $expressionTrim));

            foreach ($result as $valRowExp) {

                preg_match('/\[(.*?)\]/', $valRowExp, $expEventCatch);
                preg_match_all('/(?<![a-zA-Z0-9\[+])\[[\w.]+\]\s*=\s*[\[|\(\'\w.]+(.*)/', $valRowExp, $parseExpressionEqual);
                preg_match_all('/\[(.*?)\].hide\(\)|\[(.*?)\].show\(\)|\[(.*?)\].disable\(\)|\[(.*?)\].enable\(\)/', $valRowExp, $parseExpressionlAttr);
                preg_match_all('/\[[\w.]+\](.*)\s*(==|===|!=|!==|>|<|>=|<=)\s*[\[\w]*(.*)/', $valRowExp, $parseExpressionEqualEqual);
                preg_match_all('/message\((.*)+\)/', $valRowExp, $parseExpressionMessage);
                preg_match_all('/\[[\w.]+\](.label|.control)\((.*)\)/', $valRowExp, $parseExpressionStyle);
                preg_match_all('/\[[\w.]+\].trigger\((.*)\)/', $valRowExp, $parseExpressionTrigger);

                if (!empty($parseExpressionEqual[0])) {
                    foreach ($parseExpressionEqual[0] as $k => $expVal) {

                        $expExplode = explode('=', $expVal);
                        preg_match('/\[(.*?)\]/', $expExplode[0], $expSet);
                        preg_match_all('/\[(.*?)\]/', $expExplode[1], $expGet);
                        $exp = trim(str_replace(';', '', $expExplode[1]));

                        $getReplace = $exp;

                        if (!empty($expGet[1])) {
                            foreach ($expGet[1] as $key => $valGetPath) {
                                if (strpos($expExplode[1], '.val()') !== false) {
                                    $exp = self::glDtlGet($mainSelector, $expGet[0][$key] . '.val()', $valGetPath, $exp);
                                }
                            }
                        }

                        $setSplit = explode('.', $expSet[1]);
                        $subId = $setSplit[0];
                        $rowId = $setSplit[1];
                        $setFieldPath = $setSplit[2];

                        $getReplaced = strtr($getReplace, array("(" => "\(", ")" => "\)", "|" => "\|", "'" => "\'", "*" => "\*", "[" => "\[", "]" => "\]", "/" => "\/", "+" => "\+", "=" => "\=", ":" => "\:", '$' => '\$', '?' => '\?'));

                        if ($setFieldPath == 'debitAmount') {
                            $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'bpSetGlDtlDebitAmount(' . $mainSelector . ', ' . $subId . ', ' . $rowId . ', (' . $exp . '));', $valRowExp);
                        } elseif ($setFieldPath == 'creditAmount') {
                            $valRowExp = preg_replace('/\[' . $expSet[1] . '\]\s*=\s*' . $getReplaced . ';/', 'bpSetGlDtlCreditAmount(' . $mainSelector . ', ' . $subId . ', ' . $rowId . ', (' . $exp . '));', $valRowExp);
                        }
                    }
                }

                if (!empty($parseExpressionEqualEqual[0])) {
                    foreach ($parseExpressionEqualEqual[0] as $expVal) {
                        preg_match_all('/\[(.*?)\]/', $expVal, $expGet);
                        $expValVar = $expVal;

                        if (!empty($expGet[1])) {
                            foreach ($expGet[1] as $key => $valGetPath) {
                                $getMetaRow = $this->model->getMetaTypeCode($processId, $valGetPath);
                                $expVal = self::fullExpressionConvertGet($getMetaRow['type'], $getMetaRow, $mainSelector, "", $expGet[0][$key] . '.val()', $valGetPath, $expVal);
                            }
                        }
                        $valRowExp = str_replace($expValVar, $expVal, $valRowExp);
                    }
                }

                if (!empty($parseExpressionMessage[0])) {
                    foreach ($parseExpressionMessage[0] as $expVal) {
                        preg_match_all('/message(\((.*)+\))/', $expVal, $mesgGet);
                        $mesgGet = explode(',', $mesgGet[1][0]);

                        $message = trim(rtrim($mesgGet[1], ')'));

                        if (strpos($message, "'") === false) {
                            $message = "'" . Lang::line($message) . "'";
                        }

                        $valRowExp = str_replace($expVal . ';', 'PNotify.removeAll(); new PNotify({title: \'' . ltrim($mesgGet[0], "(") . '\', text: ' . $message . ', type: \'' . ltrim($mesgGet[0], "(") . '\', sticker: false, addclass: pnotifyPosition});', $valRowExp);
                    }
                }


                if (!empty($expEventCatch)) {

                    $eventFieldPath = $expEventCatch[1];

                    /*if (strpos($valRowExp, "].keyup()") !== false) {

                        if ($eventFieldMetaRow['type'] == 'description' || $eventFieldMetaRow['type'] == 'description_auto') {
                            $valRowExp = str_replace($expEventCatch[0] . ".keyup()", $mainSelector.'.on("keyup", "textarea[data-path=\'' . $expEventCatch[1] . '\']", function(e){ eventDelay(function()', $valRowExp);
                        } else {
                            $valRowExp = str_replace($expEventCatch[0] . ".keyup()", $mainSelector.'.on("keyup", "input[data-path=\'' . $expEventCatch[1] . '\']", function(e){ eventDelay(function()', $valRowExp);
                        }

                        $valRowExp .= '}, 250);';
                        $valRowExp .= '});';
                    }
                    if (strpos($valRowExp, "].keydown()") !== false) {
                        $valRowExp = str_replace($expEventCatch[0] . ".keydown()", $mainSelector.'.on("keydown", "input[data-path=\'' . $expEventCatch[1] . '\']", function(e)', $valRowExp);
                        $valRowExp .= '});';
                    }*/
                    if (strpos($valRowExp, '].change()') !== false) {
                        $valRowExp = str_replace($expEventCatch[0] . '.change(){', $mainSelector . '.on(\'change\', "input[data-input-name=\'' . $eventFieldPath . '\']", function(e){ var $this = $(this); if(typeof $this.attr("data-prevent-change") !== "undefined"){return;}', $valRowExp);
                        $valRowExp = str_replace($expEventCatch[0] . '.change() {', $mainSelector . '.on(\'change\', "input[data-input-name=\'' . $eventFieldPath . '\']", function(e){ var $this = $(this); if(typeof $this.attr("data-prevent-change") !== "undefined"){return;}', $valRowExp);

                        if ($eventFieldPath == 'debitAmount' || $eventFieldPath == 'creditAmount') {
                            $valRowExp .= '$this.next("input[type=hidden]").val($this.autoNumeric(\'get\'));';
                        }

                        $valRowExp .= ' });';
                    }

                    if (strpos($valRowExp, '].focus()') !== false) {
                        $valRowExp = str_replace($expEventCatch[0] . ".focus()", $mainSelector . '.on("focus", "input[data-input-name=\'' . $eventFieldPath . '\']", function(e)', $valRowExp);
                        $valRowExp .= ' });';
                    }
                }

                $expression .= $valRowExp;
            }

            preg_match_all('/\[([^\]]*)\].val\(\)/', $expression, $getValPath);

            if (!empty($getValPath[0])) {
                foreach ($getValPath[0] as $vgk => $valGetPathLast) {
                    if (!empty($valGetPathLast)) {
                        $expression = self::glDtlGet($mainSelector, $valGetPathLast, $getValPath[1][$vgk], $expression);
                    }
                }
            }
        }

        return $expression;
    }

    public function glDtlGet($mainSelector, $search, $replace, $exp)
    {

        $replaceSplit = explode('.', $replace); //1.2.debitAmount
        $subId = $replaceSplit[0];
        $rowId = $replaceSplit[1];
        $fieldPath = $replaceSplit[2];

        if ($fieldPath == 'debitAmount') {
            $exp = str_replace($search, 'bpGetGlDtlDebitAmount(' . $mainSelector . ', ' . $subId . ', ' . $rowId . ')', $exp);
        } elseif ($fieldPath == 'creditAmount') {
            $exp = str_replace($search, 'bpGetGlDtlCreditAmount(' . $mainSelector . ', ' . $subId . ', ' . $rowId . ')', $exp);
        }

        return $exp;
    }

    public static function reportTemplateUIExpression($expressionStr)
    {
        $expressionStr = html_entity_decode($expressionStr);

        preg_match_all('/\[([^\]]*)\].val\(\)/', $expressionStr, $getValPath);
        preg_match_all('/\[(.*?)\].hide\(\)|\[(.*?)\].show\(\)/', $expressionStr, $parseExpressionlAttr);

        if (!empty($getValPath[0])) {
            $prefix = '$dataElement';

            foreach ($getValPath[1] as $vgk => $valGetPathLast) {
                if (!empty($valGetPathLast)) {

                    $valGetPath = strtolower($valGetPathLast);
                    $valGetPath = 'issetParam(' . $prefix . "['" . $valGetPath . "'])";

                    $expressionStr = str_replace($getValPath[0][$vgk], $valGetPath, $expressionStr);
                }
            }
        }

        if (!empty($parseExpressionlAttr[0])) {

            $prefix = '$domHtml';

            foreach ($parseExpressionlAttr[0] as $expVal) {
                preg_match('/\[(.*?)\]/', $expVal, $expSetAttr);

                if (strpos($expVal, '.hide()') !== false) {
                    $expressionStr = str_replace($expSetAttr[0] . '.hide();', $prefix . '->find(\'[data-path="' . $expSetAttr[1] . '"], [data-colcode="' . $expSetAttr[1] . '"], [data-col-path="' . $expSetAttr[1] . '"], [data-col-name="' . $expSetAttr[1] . '"]\')->remove();', $expressionStr);
                } elseif (strpos($expVal, '.show()') !== false) {
                    $expressionStr = str_replace($expSetAttr[0] . '.show();', $prefix . '->find(\'[data-path="' . $expSetAttr[1] . '"], [data-colcode="' . $expSetAttr[1] . '"], [data-col-path="' . $expSetAttr[1] . '"], [data-col-name="' . $expSetAttr[1] . '"]\')->css(\'display\', \'\');', $expressionStr);
                }
            }
        }

        if (strpos($expressionStr, 'kpiChangeColumnName(') !== false) {
            preg_match_all('/kpiChangeColumnName\((.*?)\)/i', $expressionStr, $htmlKpiForms);

            if (count($htmlKpiForms[0]) > 0) {

                foreach ($htmlKpiForms[1] as $ek => $ev) {

                    if (strpos($ev, ',') !== false) {
                        $evArr = explode(',', $ev);

                        if (count($evArr) == 2) {

                            $colName = str_replace("'", '', trim(strip_tags($evArr[0])));
                            $changeName = str_replace("'", '', trim(strip_tags($evArr[1])));

                            $expressionStr = str_replace($htmlKpiForms[0][$ek], $prefix . '->find(\'th[data-col-name="' . $colName . '"\')->text(\'' . Lang::line($changeName) . '\');', $expressionStr);
                        }
                    }
                }
            }
        }

        return $expressionStr;
    }

    public function convertIndicatorColExpression($uniqId, $indicatorId, $expressionArr)
    {

        $arr = array();
        $selector = Mdexpression::$setMainSelector;

        foreach ($expressionArr as $colCode => $row) {

            if ($expression = issetParam($row['expression'])) {

                preg_match_all('/\[(.*?)\]/', $expression, $expEventCatch);

                if (isset($expEventCatch[0][0])) {

                    $selectorPath = '';

                    foreach ($expEventCatch[1] as $eventPath) {
                        $selectorPath .= '[data-path="' . $eventPath . '"],';

                        $expression = str_replace("[$eventPath]", 'getIndColVal(' . $selector . ', $this, \'' . $eventPath . '\')', $expression);
                    }

                    $selectorPath = rtrim($selectorPath, ',');

                    $arr[] = $selector . '.on(\'change input propertychange\', \'' . $selectorPath . '\', function(){ ' . "\n";

                    $arr[] = 'var $this = $(this); ' . "\n";
                    $arr[] = 'setIndColVal(' . $selector . ', $this, \'' . $colCode . '\', (' . $expression . ')); ' . "\n";

                    $arr[] = '}); ' . "\n";
                }
            }
        }

        return implode('', $arr);
    }

    public function convertIndicatorCellExpression($uniqId, $indicatorId, $expressionArr)
    {

        $arr = array();
        $selector = Mdexpression::$setMainSelector;

        foreach ($expressionArr as $cellCode => $row) {

            if ($expression = issetParam($row['expression'])) {

                $expression = str_replace(array('[[', ']]'), array('[', ']'), $expression);
                $expression = str_ireplace('datediff(', 'dateDiff(', $expression);

                preg_match_all('/\[(.*?)\]/', $expression, $expEventCatch);

                if (isset($expEventCatch[0][0])) {

                    $parentColumnName = issetParam($row['parentColumnName']);
                    $parentColumnName = $parentColumnName ? $parentColumnName . '.' : '';
                    $selectorPath = '';

                    foreach ($expEventCatch[1] as $eventPath) {
                        $selectorPath .= '[data-field-name="' . $parentColumnName . $eventPath . '"],';

                        $expression = str_replace("[$eventPath]", "getIndCellVal($selector, '$parentColumnName$eventPath')", $expression);
                    }

                    $selectorPath = rtrim($selectorPath, ',');

                    $arr[] = $selector . '.on(\'change input propertychange\', \'' . $selectorPath . '\', function(){ ' . "\n";

                    $arr[] = "setIndCellVal($selector, '$cellCode', ($expression)); " . "\n";

                    $arr[] = '}); ' . "\n";
                } else {

                    $expression = strtolower(str_replace(array('[', ']'), array('', ''), $expression));

                    if ($expression == 'hide') {
                        $arr[] = "hideIndCellVal($selector, '$cellCode'); " . "\n";
                    }
                }
            }
        }

        return implode('', $arr);
    }

    public function convertIndicatorHdrExpression($uniqId, $indicatorId, $expressionArr) {

        $arr = array();
        $selector = Mdexpression::$setMainSelector;

        foreach ($expressionArr as $colCode => $expression) {

            /*preg_match_all('/sum\(\[(.*?)\]\)/i', $expression, $sumAggregate); 
            preg_match_all('/avg\(\[(.*?)\]\)/i', $expression, $avgAggregate); 
            preg_match_all('/min\(\[(.*?)\]\)/i', $expression, $minAggregate); 
            preg_match_all('/max\(\[(.*?)\]\)/i', $expression, $maxAggregate);

            if (count($sumAggregate[1]) > 0) {
                foreach ($sumAggregate[1] as $s => $sv) {
                    $expressionToJs = str_replace($sumAggregate[0][$s], 'Number($("input[data-path=\''.$sv.'\']:not([data-not-aggregate])", '.$mainSelector.').sum())', $expressionToJs);
                }
            }*/

            $expression = self::fullExpressionReplaceFncNames($uniqId, $selector, $expression);

            preg_match_all('/\[(.*?)\]/', $expression, $expEventCatch);

            if (isset($expEventCatch[0][0])) {

                $selectorPath = '';

                foreach ($expEventCatch[1] as $eventPath) {

                    if (strpos($eventPath, '.') !== false) {
                        $eventPathArr = explode('.', $eventPath);
                        $parentPath = $eventPathArr[0];
                        $fieldPath = $eventPathArr[1];

                        $selectorPath .= 'table[data-col-name="' . $parentPath . '"] > tbody [data-col-path="' . $fieldPath . '"],';
                        $expression = str_replace("sum([$eventPath])", 'getIndDtlSum(' . $selector . ', \'' . $parentPath . '\', \'' . $fieldPath . '\')', $expression);
                    } else {
                        $selectorPath .= '[data-col-path="' . $eventPath . '"],';
                        $expression = str_replace("[$eventPath]", 'getIndHdrVal(' . $selector . ', \'' . $eventPath . '\')', $expression);
                    }
                }

                $selectorPath = rtrim($selectorPath, ',');

                $arr[] = $selector . '.on(\'change input propertychange\', \'' . $selectorPath . '\', function(){ ' . "\n";
                //$arr[] = 'eventDelay(function(){'."\n";

                $arr[] = 'var $this = $(this); ' . "\n";
                $arr[] = 'setIndHdrVal(' . $selector . ', \'' . $colCode . '\', (' . $expression . ')); ' . "\n";

                //$arr[] = '}, 200);'."\n";
                $arr[] = '}); ' . "\n";
            }
        }

        return implode('', $arr);
    }

    public function flowchartStart()
    {
        return self::flowchartToExpression(Mdexpression::$flowData, Mdexpression::$flowData[0]['id']);
    }

    public function flowchartToExpression($flowData, $id)
    {
        foreach ($flowData as $flow) {
            if ($flow['id'] == $id) {
                switch ($flow['type']) {
                    case 'standard.Rectangle':
                        Mdexpression::$flowCodeString .= self::flowchartToExpressionExecute($flow['attrs']['label']['text']);
                        self::getNextBlockFlowchart($flow['id']);
                        if (self::isCloseBracket($flow['id'])) {
                            Mdexpression::$flowCodeString .= '}';
                        }
                        break;
                    case 'standard.Polygon':
                        Mdexpression::$flowCodeString .= self::flowchartToExpressionCondition($flow['attrs']['label']['text']);
                        self::getNextBlockFlowchart($flow['id']);
                        Mdexpression::$sensorBracket .= $flow['id'] . ',';
                        break;
                    default:
                        break;
                }
            }
        }
    }

    public function getNextBlockFlowchart($id)
    {
        foreach (Mdexpression::$flowData as $flow) {
            if ($flow['type'] == 'app.Link' && $flow['source']['id'] == $id) {
                self::flowchartToExpression(Mdexpression::$flowData, $flow['target']['id']);
            }
        }
    }

    public function isCloseBracket($id)
    {
        $isCloseBracket = true;
        foreach (Mdexpression::$flowData as $flow) {
            if ($flow['type'] == 'app.Link' && $flow['source']['id'] == $id) {
                $isCloseBracket = false;
            }
        }
        return $isCloseBracket;
    }

    public function flowchartToExpressionExecute($blockString = '')
    {
        return $blockString . ';';
    }

    public function flowchartToExpressionCondition($blockString = '')
    {
        return 'if (' . $blockString . ') {';
    }

    public function microCreateObject($obj, $formData)
    {
        $rowExp = $obj;
        preg_match_all('/\$[\w\d]+\s*=+(.*)/', $rowExp, $parseExpressionEqual);
        preg_match_all('/(\$[\w\d]*)+\s*=/', $rowExp, $parseExpressionEqual2);

        $instanceExp = &getInstance();
        $instanceExp->load->model('mdform', 'middleware/models/');

        if (!isset($parseExpressionEqual[1][0])) {
            return null;
        }
        $getObjectCodeName = explode('new', $parseExpressionEqual[1][0]);

        $getObject = $instanceExp->model->getKpiIndicatorByCodeModel(trim($getObjectCodeName[1]));
        
        if (!$getObject) {
            return null;
        }
        
        $getObjectProps = $instanceExp->model->getKpiIOIndicatorColumnsModel($getObject['ID'], null);

        $props = '';
        $props .= 'private $data = [];' . PHP_EOL;

        // Class defination
        $objecPrefix = getUID();
        $classString = 'class Obj' . $objecPrefix . '_' . $getObject['CLASS_NAME'] . ' {' . PHP_EOL;

        // Properties
        $classString .= $props;

        // Magic methods
        $classString .= 'public function __set($name, $value) {' . PHP_EOL;
        $classString .= '$this->data[$name] = $value;' . PHP_EOL;
        $classString .= '}' . PHP_EOL;
        $classString .= 'public function __get($name) {' . PHP_EOL;
        $classString .= 'if (array_key_exists($name, $this->data)) {' . PHP_EOL;
        $classString .= 'return $this->data[$name];' . PHP_EOL;
        $classString .= '}' . PHP_EOL;
        $classString .= '}' . PHP_EOL;

        // Methods
        $classString .= 'public function getNameOfObjectName() {' . PHP_EOL;
        $classString .= 'return "' . $getObject['CLASS_NAME'] . '";' . PHP_EOL;
        $classString .= '}' . PHP_EOL;

        // Class end
        $classString .= '}' . PHP_EOL;

        $classString .= $parseExpressionEqual2[1][0] . ' = new Obj' . $objecPrefix . '_' . $getObject['CLASS_NAME'];

        return $classString;
    }

    public function microCreateClientObject($obj, $formData)
    {
        $rowExp = $obj;
        preg_match_all('/\$[\w\d]+\s*=+(.*)/', $rowExp, $parseExpressionEqual);
        preg_match_all('/((.*)\$[\w\d]*)+\s*=/', $rowExp, $parseExpressionEqual2);

        $instanceExp = &getInstance();
        $instanceExp->load->model('mdform', 'middleware/models/');

        if (!isset($parseExpressionEqual[1][0])) {
            return '';
        }
        $getObjectCodeName = explode('new', $parseExpressionEqual[1][0]);

        $getObject = $instanceExp->model->getKpiIndicatorByCodeModel(trim($getObjectCodeName[1]));
        $getObjectProps = $instanceExp->model->getKpiIOIndicatorColumnsModel($getObject['ID'], null);

        //        $props = 'getNameOfObjectName = \''.$getObject['CLASS_NAME'].'\';'.PHP_EOL;
        //        $getSet = '';

        //        foreach ($getObjectProps as $prow) {
        //            $props .= $prow['COLUMN_NAME'].' = null;'.PHP_EOL;
        //            $getSet .= 'get '.$prow['COLUMN_NAME'].'() {'.PHP_EOL;
        //            $getSet .= 'return this.'.$prow['COLUMN_NAME'].';'.PHP_EOL;
        //            $getSet .= '}'.PHP_EOL;
        //            $getSet .= 'set '.$prow['COLUMN_NAME'].'(p) {'.PHP_EOL;
        //            $getSet .= 'this.'.$prow['COLUMN_NAME'].' = p;'.PHP_EOL;
        //            $getSet .= '}'.PHP_EOL;
        //        }

        // Class defination
        //        $objecPrefix = getUID();
        //        $classString = 'class Obj'.$objecPrefix.'_'.$getObject['CLASS_NAME'].' {'.PHP_EOL;
        //
        //            // Properties
        //            $classString .= $props;
        //            
        //            // Methods
        //            $classString .= $getSet.PHP_EOL;
        //            $classString .= 'get getNameOfObjectName() {'.PHP_EOL;
        //            $classString .= 'return this.getNameOfObjectName;'.PHP_EOL;      
        //            $classString .= '}'.PHP_EOL;            
        //
        //        // Class end
        //        $classString .= '}'.PHP_EOL;

        $classString = '{getNameOfObjectName:\'' . $getObject['CLASS_NAME'] . '\'}';

        //$classString .= $parseExpressionEqual2[1][0].' = new Obj'.$objecPrefix.'_'.$getObject['CLASS_NAME'];        
        $classString = $parseExpressionEqual2[1][0] . ' = ' . $classString;

        return $classString;
    }

    public function microSaveObject($obj)
    {
        $instanceExp = &getInstance();
        $instanceExp->load->model('mdform', 'middleware/models/');

        $getObject = $instanceExp->model->getKpiIndicatorByCodeModel($obj->getNameOfObjectName());
        $getObjectProps = $instanceExp->model->getKpiIOIndicatorColumnsModel($getObject['ID'], null);
        $objectFillData = [];

        foreach ($getObjectProps as $row) {
            if ($obj->{$row['COLUMN_NAME']}) {
                $objectFillData[$row['COLUMN_NAME']] = $obj->{$row['COLUMN_NAME']};
            }
        }

        $postArrData['kpiMainIndicatorId'] = $getObject['ID'];
        $postArrData['kpiDataTblName'] = $getObject['TABLE_NAME'];
//        $postArrData['kpiTbl'] = $objectFillData;
        Mdform::$mvSaveParams = $objectFillData;
        $postArrData['isMicroFlow'] = true;
        $resultSaveObject = $instanceExp->model->saveMetaVerseDataModel(null, $postArrData);

        return $resultSaveObject;
    }

    public function microUpdateObject($obj, $id, $customColumnName = '')
    {
        $instanceExp = &getInstance();
        $instanceExp->load->model('mdform', 'middleware/models/');

        $getObject = $instanceExp->model->getKpiIndicatorByCodeModel($obj->getNameOfObjectName());
        $getObjectProps = $instanceExp->model->getKpiIOIndicatorColumnsModel($getObject['ID'], null);
        $objectFillData = [];

        foreach ($getObjectProps as $row) {
            if ($obj->{$row['COLUMN_NAME']}) {
                $objectFillData[$row['COLUMN_NAME']] = $obj->{$row['COLUMN_NAME']};
            }
        }

        $postArrData['kpiMainIndicatorId'] = $getObject['ID'];
        $postArrData['kpiDataTblName'] = $getObject['TABLE_NAME'];
        //$postArrData['kpiTbl'] = $objectFillData;
        $postArrData['kpiTblId'] = $id;
        $postArrData['isMicroFlow'] = true;
        $postArrData['customIdField'] = $customColumnName;
        Mdform::$mvSaveParams = $objectFillData;
        $instanceExp->model->saveMetaVerseDataModel(null, $postArrData);
    }

    public function microCallFunction()
    {
        $instanceExp = &getInstance();
        $instanceExp->load->model('mdform', 'middleware/models/');

        $cache = phpFastCache();

        $arg_list = func_get_args();
        $functionName = $arg_list[0];

        $getCacheData = $cache->get('microCallFunctionTmpData_' . $functionName);
        if ($getCacheData == null) {
            $getObject = $instanceExp->model->getKpiIndicatorByCodeModel($functionName);
            if ($getObject) {
                $getFunctionProps = $instanceExp->model->getKpiIOIndicatorColumnsModel($getObject['ID'], null);
                $cacheData = [
                    'getObject' => $getObject,
                    'getFunctionProps' => $getFunctionProps
                ];
                $cache->set('microCallFunctionTmpData_' . $functionName, $cacheData, Mdwebservice::$expressionCacheTime);
            }
        } else {
            $getObject = $getCacheData['getObject'];
            $getFunctionProps = $getCacheData['getFunctionProps'];
        }

        $functionResult = '';
        $argIndex = 1;
        $pstring = '';
        $ostring = '';
        $outArray = [];

        if ($getFunctionProps) {
            foreach ($getFunctionProps as $prow) {
                if ($prow['IS_INPUT'] == '1') {
                    ${Str::lower($prow['COLUMN_NAME'])} = $arg_list[$argIndex];
                    $argIndex++;
                } else {
                    $ostring .= '$outArray[\'' . Str::lower($prow['COLUMN_NAME']) . '\'] = $' . Str::lower($prow['COLUMN_NAME']);
                }
            }
            $pstring = rtrim($pstring, ',');
        }

        $fString = '';
        //$fString = $pstring.';'.PHP_EOL;
        $functionScript = self::microFlowExpression($getObject['ID']);
        $fString .= $functionScript . PHP_EOL;
        $fString .= $ostring . ';' . PHP_EOL;
        // pa($fString);
        @eval($fString);
        return $outArray;
    }

    public function microForLoop($rowExp, $formData)
    {
        $getLoop = explode(" to ", $rowExp); //str_replace('{promotion}', $promotionContent, $templateContent)
        $getLoop2 = str_ireplace('for ', '', $getLoop[0]);
        preg_match('/\$[\w\d]*/', $getLoop2, $parseExpressionEqual);
        $resultLoop = 'for (' . $getLoop2 . '; ' . $parseExpressionEqual[0] . ' < ' . $getLoop[1] . '; ' . $parseExpressionEqual[0] . '++)';
        return $resultLoop;
    }

    public function microCurrentReplace($obj, $formData)
    {
        $rowExp = $obj;
        $path = '';

        foreach ($formData as $fkey => $frow) {
            if (!is_array($frow)) {
                $value = (is_numeric($frow) ? $frow : '"' . $frow . '"');
                preg_match('/\[([^\]]*)\].val\(\)/', $rowExp, $getValPath);
                if ($getValPath) {
                    if ($getValPath[1] === $fkey) {
                        $path = $fkey;
                        $rowExp = str_replace($getValPath[0], $value, $rowExp);
                    }
                }
            }
        }
        // $rowExp = preg_replace('/\$(.*?)\./', '\$$1->', $rowExp);

        return ['rowExp' => $rowExp, 'path' => $path];
    }

    public function microCurrentParamReplace($obj, $formData)
    {
        if (empty($obj)) {
            return ['rowExp' => ''];
        }
        
        $rowExp = json_decode($obj, true);
        $path = 'array(';

        if ($rowExp) {
            foreach ($rowExp as $rowKey => $rowVal) {
                preg_match('/\[([^\]]*)\].val\(\)/', $rowVal['value'], $getValPath);
                if ($getValPath) {
                    foreach ($formData as $fkey => $frow) {
                        if (!is_array($frow)) {
                            $value = (is_numeric($frow) ? $frow : '"' . $frow . '"');
                            if ($getValPath[1] === $fkey) {
                                $rowExp[$rowKey]['value'] = $value;
                                $path .= '"'.$rowVal['srcPath'].'"=>'.$value.',';
                            }
                        }
                    }
                } elseif (substr_count($rowVal['value'], '$') > 0) {
                    $path .= '"'.$rowVal['srcPath'].'"=>'.str_replace("'", "\'", $rowVal['value']).',';
                } else {
                    $frow = $rowVal['value'];
                    $value = (is_numeric($frow) ? $frow : '"' . $frow . '"');
                    $path .= '"'.$rowVal['srcPath'].'"=>'.$value.',';
                }
            }
        }
        $path = rtrim($path, ',');
        $path .= ')';
        // $rowExp = preg_replace('/\$(.*?)\./', '\$$1->', $rowExp);

        return ['rowExp' => $path];
    }

    public function microCurrentCleintReplace($obj, $formData)
    {
        $rowExp = $obj;
        $path = '';

        foreach ($formData as $fkey => $frow) {
            if (!is_array($frow)) {
                $value = (is_numeric($frow) ? $frow : '"' . $frow . '"');
                preg_match('/\[([^\]]*)\].val\(\)/', $rowExp, $getValPath);
                if ($getValPath) {
                    if ($getValPath[1] === $fkey) {
                        $path = $fkey;
                    }
                    $rowExp = str_replace($getValPath[0], $value, $rowExp);
                }
            }
        }
        // $rowExp = preg_replace('/\$(.*?)\./', '\$$1->', $rowExp);

        return ['rowExp' => $rowExp, 'path' => $path];
    }

    public function microRunMeta($metaCode, $parameter, $isPreview = null)
    {
        unset(WebService::$addonHeaderParam['windowSessionId']);

        $getParams = explode('|', $parameter);
        $prm = [];
        
        foreach ($getParams as $row) {
            $rowValue = explode('@@', $row);
            $prm[$rowValue[0]] = $rowValue[1];
        }

        $returnData = [];
        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, $metaCode, $prm);

        if ($result['status'] == 'success') {
            $returnData = $result['result'];
        }
        if ($isPreview) {
            print_r($returnData);
            exit;
        }
        
        return $returnData;
    }

    public function microRunWebservice($indicatorId, $parameter, $isPreview = null)
    {
        $parameter = str_replace('@@@', '♠', $parameter);
        $getParams = explode('|', $parameter);
        $prm = [];
        foreach ($getParams as $row) {
            $rowValue = explode('@', $row);
            $prm[$rowValue[0]] = $rowValue[1];
            $paramDatas[] = [
                'fieldPath' => 'Parameters.' . $rowValue[0],
                'inputPath' => 'Parameters.' . $rowValue[0],
                'value' => str_replace('♠', '@', $rowValue[1])
            ];
        }

        $postData['processCode'] = 'KPI_CALL_WEBSERVICE';
        $paramDatas[] = [
            'fieldPath' => 'indicatorId',
            'inputPath' => 'indicatorId',
            'value' => $indicatorId
        ];
        $postData['paramData'] = $paramDatas;

        $returnData = [];
        $instanceExpService = &getInstance();
        $instanceExpService->load->model('mdwebservice', 'middleware/models/');
        $result = $instanceExpService->model->execProcessModel($postData);

        if ($result['status'] == 'success') {
            $returnData = $result['result'];
        }
        return $returnData;
    }

    public function microRunDbQuery($functionName, $parameter)
    {
        if ($functionName && $parameter) {

            $paramValues = json_encode($parameter, JSON_UNESCAPED_UNICODE);

            $instanceExpService = &getInstance();
            $instanceExpService->load->model('mdexpression', 'middleware/models/');            
            $returnData = $instanceExpService->model->microRunDbQueryModel($functionName, $paramValues);
            $returnData = json_decode($returnData, true);

            return $returnData;
        }
        return [];
    }

    public function microRunDbProcedure($functionName, $parameter)
    {
        if ($functionName && $parameter) {
            
            $paramValues = json_encode($parameter, JSON_UNESCAPED_UNICODE);

            $instanceExpService = &getInstance();
            $instanceExpService->load->model('mdexpression', 'middleware/models/');            
            $returnData = $instanceExpService->model->microRunDbProcedureModel($functionName, $paramValues);

            return $returnData;
        }
        return [];
    }

    public function microRunProcedure($indicatorId, $parameter, $isPreview = null)
    {
        if (!$indicatorId || !is_numeric($indicatorId)) {
            return array('status' => 'error', 'message' => 'Invalid id!');
        }
        
        $instanceExp = &getInstance();
        $instanceExp->load->model('mdform', 'middleware/models/');

        $getParams = explode('|', $parameter);
        $prm = [];
        foreach ($getParams as $row) {
            $rowValue = explode('@', $row);
            $prm[$rowValue[0]] = $rowValue[1];
        }

        $returnData = [];
        $postArrData['kpiMainIndicatorId'] = $indicatorId;
        $postArrData['kpiDataTblName'] = '';
        $postArrData['kpiTbl'] = $prm;
        $postArrData['isMicroFlow'] = true;
        $result = $instanceExp->model->saveKpiDynamicDataModel(null, $postArrData);

        if ($result['status'] == 'success') {
            $returnData = $result['response'];
        } else {
            $returnData = $result;
        }
        if ($isPreview == 1) {
            print_r($returnData);
            exit;
        }
        return $returnData;
    }

    public function microRunGet($indicatorId, $parameter)
    {
        $instanceExp = &getInstance();
        $instanceExp->load->model('mdform', 'middleware/models/');

        $getParams = explode('|', $parameter);
        $prm = [];
        foreach ($getParams as $key => $row) {
            $rowValue = explode('@', $row);
            $prm[$key]['column'] = $rowValue[0];
            $prm[$key]['value'] = $rowValue[1];
        }

        $getIndicator = $instanceExp->model->getIndicatorModel($indicatorId);
        $getData = $instanceExp->model->getKpiDynamicActiveDataRowsModel($getIndicator['TABLE_NAME'], trim($prm[0]['column']), trim($prm[0]['value']));

        return $getData;
    }

    public function microFindObject($obj, $formData)
    {
        try {
            //            $isCurrentReplace = false;
            // $rowExp = $obj['text'];
            $rowExp = isset($obj['expressionvalue']) ? $obj['expressionvalue'] : $obj['text'];
            $rowExpParam = issetParam($obj['expressionmappingparameter']);
            if (substr_count($rowExp, '.val()') > 0) {
                $currentReplace = self::microCurrentReplace($rowExp, $formData);
                $rowExp = $currentReplace['rowExp'];
                //$rowExp = str_replace('$current', '$formData', $rowExp);    
                //                $isCurrentReplace = true;
            }
            $currentReplace = self::microCurrentParamReplace($rowExpParam, $formData);
            $rowExpParam = $currentReplace['rowExp'];

            preg_match('/=+\s*new(.*)/', $rowExp, $checkNewObject);
            preg_match_all('/\$[\w\d]+\s*=+(.*)/', $rowExp, $parseExpressionEqual);
            preg_match_all('/(\$[\w\d]*)+\s*=/', $rowExp, $parseExpressionEqual2);
            // preg_match_all('/\<(.*?)\>/i', $rowExp, $getObjectFromExp);
            // preg_match_all('/read\((.*?)\)/i', $rowExp, $getRecordId);
            preg_match_all('/runGet:(.*?)\((.*?)\)/i', $rowExp, $getRecordId);
            preg_match_all('/runMeta:(.*?)\((.*?)\)/i', $rowExp, $getMetaParameter);
            preg_match_all('/runWebservice:(.*?)\((.*?)\)/i', $rowExp, $getMetaParameter2);
            preg_match_all('/runDbQuery:(.*)/i', $rowExp, $getDbQuery);
            preg_match_all('/runDbProcudure:(.*)/i', $rowExp, $getDbProcedure);
            preg_match_all('/runProcedure:(.*?)\((.*?)\)/i', $rowExp, $getProcedureParameter);
            preg_match_all('/runFunction:(.*?)\((.*?)\)/i', $rowExp, $getFunctionParameter);
            preg_match('/.save/i', $rowExp, $getSave);
            preg_match('/.update/i', $rowExp, $getUpdate);
            preg_match('/for /i', $rowExp, $getFor);
            $getParamValue = '';
            $getMetaValue = $getMetaParameter && isset($getMetaParameter[2]) ? isset($getMetaParameter[2][0]) ? $getMetaParameter[2][0] : '' : '';
            $getMetaCode = $getMetaParameter && isset($getMetaParameter[1]) ? isset($getMetaParameter[1][0]) ? $getMetaParameter[1][0] : '' : '';
            $getMetaValue2 = $getMetaParameter2 && isset($getMetaParameter2[2]) ? isset($getMetaParameter2[2][0]) ? $getMetaParameter2[2][0] : '' : '';
            $getMetaCode2 = $getMetaParameter2 && isset($getMetaParameter2[1]) ? isset($getMetaParameter2[1][0]) ? $getMetaParameter2[1][0] : '' : '';
            $getDbQueryValue = $getDbQuery && isset($getDbQuery[1]) ? isset($getDbQuery[1][0]) ? $getDbQuery[1][0] : '' : '';
            $getDbProcedureValue = $getDbProcedure && isset($getDbProcedure[1]) ? isset($getDbProcedure[1][0]) ? $getDbProcedure[1][0] : '' : '';
            $getRecordValue = $getRecordId && isset($getRecordId[2]) ? isset($getRecordId[2][0]) ? $getRecordId[2][0] : '' : '';
            $getRecordCode = $getRecordId && isset($getRecordId[1]) ? isset($getRecordId[1][0]) ? $getRecordId[1][0] : '' : '';
            $getProcValue = $getProcedureParameter && isset($getProcedureParameter[2]) ? isset($getProcedureParameter[2][0]) ? $getProcedureParameter[2][0] : '' : '';
            $getProcCode = $getProcedureParameter && isset($getProcedureParameter[1]) ? isset($getProcedureParameter[1][0]) ? $getProcedureParameter[1][0] : '' : '';
            $getFunctionValue = $getFunctionParameter && isset($getFunctionParameter[2]) ? isset($getFunctionParameter[2][0]) ? $getFunctionParameter[2][0] : '' : '';
            $getFunctionName = $getFunctionParameter && isset($getFunctionParameter[1]) ? isset($getFunctionParameter[1][0]) ? $getFunctionParameter[1][0] : '' : '';

            if ($getMetaCode) {
                //$rowExp = str_replace('runMeta:'.$getMetaCode.'('.$getMetaValue.')', 'self::microRunMeta("'.$getMetaCode.'", '.json_decode($obj['expressionmappingparameter'], true).')', $rowExp);
                //pa($rowExp);
                $rowExp = str_replace('runMeta:' . $getMetaCode . '(' . $getMetaValue . ')', 'self::microRunMeta("' . $getMetaCode . '", "' . $getMetaValue . '")', $rowExp);
                return $rowExp;
            }

            if ($getMetaCode2) {
                $rowExp = str_replace('runWebservice:' . $getMetaCode2 . '(' . $getMetaValue2 . ')', 'self::microRunWebservice("' . $getMetaCode2 . '", "' . $getMetaValue2 . '")', $rowExp);
                return $rowExp;
            }

            if ($getDbQueryValue) {
                $rowExp = '$funPrm = eval(\'return '.$rowExpParam.';\');' . $rowExp;
                $rowExp = str_replace('runDbQuery:' . $getDbQueryValue, 'self::microRunDbQuery("' . $getDbQueryValue . '", $funPrm)', $rowExp);
                return $rowExp;
            }

            if ($getDbProcedureValue) {
                $rowExp = '$funPrm = eval(\'return '.$rowExpParam.';\');' . $rowExp;
                $rowExp = str_replace('runDbProcudure:' . $getDbProcedureValue, 'self::microRunDbProcedure("' . $getDbProcedureValue . '", $funPrm)', $rowExp);
                return $rowExp;
            }

            if ($getProcCode) {
                $rowExp = str_replace('runProcedure:' . $getProcCode . '(' . $getProcValue . ')', 'self::microRunProcedure("' . $getProcCode . '", "' . $getProcValue . '")', $rowExp);
                return $rowExp;
            }

            if ($getRecordValue) {
                $rowExp = str_replace('runGet:' . $getRecordCode . '(' . $getRecordValue . ')', 'self::microRunGet("' . $getRecordCode . '", "' . $getRecordValue . '")', $rowExp);
                return $rowExp;
            }

            if ($getFunctionName) {
                $getFunctionValue2 = "'" . $getFunctionName . '\',' . $getFunctionValue;
                //                $graph=[[0,0,1,2,0,0,0],[0,0,2,0,0,3,0],[1,2,0,1,3,0,0],[2,0,1,0,0,0,1],[0,0,3,0,0,2,0],[0,3,0,0,2,0,1],[0,0,0,1,0,1,0]];
                //                pa(self::microCallFunction('dijkstra',$graph, 0));
                $rowExp = str_replace('runFunction:' . $getFunctionName . '(' . $getFunctionValue . ')', 'self::microCallFunction(' . $getFunctionValue2 . ')', $rowExp);
                return $rowExp;
            }

            $instanceExp = &getInstance();
            $instanceExp->load->model('mdform', 'middleware/models/');

            if (!empty($getFor)) {
                $newInstance = self::microForLoop($rowExp, $formData);
                return $newInstance;
            }

            if (!empty($checkNewObject)) {
                $newInstance = self::microCreateObject($rowExp, $formData);
                return $newInstance;
            }

            if (!empty($getSave)) {
                //$_POST['isMicroFlowSelfSave'] = true;
                $getSaveVariable1 = explode('=', $rowExp);
                if (count($getSaveVariable1) === 2) {
                    $getSaveVariable = explode('.', trim($getSaveVariable1[1]));
                    return trim($getSaveVariable1[0]) . ' = self::microSaveObject(' . $getSaveVariable[0] . ')';
                } else {
                    $getSaveVariable = explode('.', trim($getSaveVariable1[0]));
                    return 'self::microSaveObject(' . $getSaveVariable[0] . ')';
                }
                // $saveInstance = self::microSaveObject($getSaveVariable[0], $formData);                
            }

            if (!empty($getUpdate)) {
                // $_POST['isMicroFlowSelfSave'] = true;
                preg_match_all('/update\((.*?)\)/i', $rowExp, $rowExpUpdate);
                $objParams = explode(',', $rowExpUpdate[1][0]);
                $recordId = str_replace('#selectedId#', Input::post('kpiTblId'), $objParams[0]);
                $getSaveVariable = explode('.', $rowExp);
                return 'self::microUpdateObject(' . $getSaveVariable[0] . ',' . $recordId . ',\'' . trim($objParams[1]) . '\')';
            }

            //            if (!$isCurrentReplace) {
            //                $rowExp = preg_replace('/\$(.*?)\./', '\$$1->', $rowExp);
            //            }
            // if (substr_count($rowExp, '$') > 1) {
            //     return $rowExp;
            // }

            // if (substr_count($getParamValue, '$current') > 0) {
            //     $getParamValue = self::microCurrentReplace($getParamValue, $formData);
            // }

            $getObject = [];
            if (isset($parseExpressionEqual[1][0])) {
                $getObjectCodeName = explode('.', $parseExpressionEqual[1][0]);
                $getObject = $instanceExp->model->getKpiIndicatorByCodeModel(trim($getObjectCodeName[0]));
            }

            if (empty($getObject)) {
                return $rowExp;
            }

            $classString = '$getObjectRecordRow = [];' . PHP_EOL;
            $classString .= '$instanceExp = &getInstance();' . PHP_EOL;
            $classString .= '$instanceExp->load->model(\'mdform\', \'middleware/models/\');' . PHP_EOL;
            $classString .= '$getObjectProps = $instanceExp->model->getKpiIOIndicatorColumnsModel(' . $getObject['ID'] . ', null);' . PHP_EOL;
            //$whereColumn = isset($currentReplace) && isset($currentReplace['path']) ? $currentReplace['path'] : 'ID';
            if ($getParamValue) {
                $getParamValue = explode(',', $getParamValue);
                if (!isset($getParamValue[1])) {
                    $getParamValue[1] = 'ID';
                }
                $classString .= '$getObjectRecordRow = $instanceExp->model->getKpiDynamicActiveDataRowModel(\'' . $getObject['TABLE_NAME'] . '\', \'' . trim($getParamValue[1]) . '\', ' . trim($getParamValue[0]) . ');' . PHP_EOL;
            }
            $getObjectMethods = $instanceExp->model->objectMethodModel($getObject['ID']);

            // $parseExpressionEqual2 = explode('=', $getObjectMethod[0][0]);
            // $getObjectMethod = $instanceExp->model->getKpiIndicatorByCodeModel(explode('.', $parseExpressionEqual2[1])[1]);
            // $getObjectMethodExp = str_replace('$', '$this->', $getObjectMethod['VAR_FNC_EXPRESSION_STRING']);

            // pa($getObjectRecordRow);

            // $filterData = $getObjectRecordRow = array();

            // foreach ($obj['criteria'] as $inputParam => $inputParamVal) {
            //     $filterData[$inputParamVal['objectAttr']][] = issetParam($formData[$inputParamVal['criteriaValue']]);
            // }        

            // $_POST['indicatorId'] = $getObject['ID'];
            // $_POST['filterData']  = $filterData;

            // $result = $this->model->indicatorDataGridModel();

            // if (isset($result['status']) && $result['status'] == 'success') {

            //     $rows     = $result['rows'];
            //     $getObjectRecordRow = $rows[0];
            //     $getObjectRecordRow['COUNT'] = $result['total'];

            // }   

            $props = '';
            // $props .= 'public static $COUNT = "'.$getObjectRecordRow['COUNT'].'";'.PHP_EOL;
            $props .= 'private $data = [];' . PHP_EOL;
            // foreach ($getObjectProps as $objRow) {
            // ${$dynamicVariable}[$objRow['COLUMN_NAME']] = $getObjectRecordRow[$objRow['COLUMN_NAME']];
            // $props .= 'public $'.$objRow['COLUMN_NAME'].';'.PHP_EOL;
            // }     

            // Class defination
            $objecPrefix = getUID();
            $classString .= 'class Obj' . $objecPrefix . '_' . $getObject['CLASS_NAME'] . ' {' . PHP_EOL;

            // Properties
            $classString .= $props;

            // Magic methods
            $classString .= 'public function __set($name, $value) {' . PHP_EOL;
            $classString .= '$this->data[$name] = $value;' . PHP_EOL;
            $classString .= '}' . PHP_EOL;
            $classString .= 'public function __get($name) {' . PHP_EOL;
            $classString .= 'if (array_key_exists($name, $this->data)) {' . PHP_EOL;
            $classString .= 'return $this->data[$name];' . PHP_EOL;
            $classString .= '}' . PHP_EOL;
            $classString .= '}' . PHP_EOL;

            // Class end
            $classString .= '}' . PHP_EOL;

            $classString .= $parseExpressionEqual2[1][0] . ' = new Obj' . $objecPrefix . '_' . $getObject['CLASS_NAME'] . ';' . PHP_EOL;

            $classString .= $parseExpressionEqual2[1][0] . '->isempty = (empty($getObjectRecordRow) ? 1 : 0);';
            $classString .= 'foreach ($getObjectProps as $objRow) {';
            // ${$dynamicVariable}[$objRow['COLUMN_NAME']] = $getObjectRecordRow[$objRow['COLUMN_NAME']];
            // $props .= 'public $'.$objRow['COLUMN_NAME'].';'.PHP_EOL;
            $classString .= $parseExpressionEqual2[1][0] . '->$objRow[\'COLUMN_NAME\'] = (isset($getObjectRecordRow[$objRow[\'COLUMN_NAME\']]) ? (is_numeric($getObjectRecordRow[$objRow[\'COLUMN_NAME\']]) ? $getObjectRecordRow[$objRow[\'COLUMN_NAME\']] : \'"\'.$getObjectRecordRow[$objRow[\'COLUMN_NAME\']].\'"\') : \'""\');' . PHP_EOL;
            $classString .= '}' . PHP_EOL;

            // public function '.$getObjectMethod['CODE'].'() {
            // '.$getObjectMethodExp.'
            // }
            $classString = rtrim($classString, ';');

            return $classString;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function microFindClientObject($obj, $formData)
    {
        try {
            //            $isCurrentReplace = false;
            $rowExp = $obj['text'];
            //            if (substr_count($rowExp, '.val()') > 0) {
            //                $currentReplace = self::microCurrentCleintReplace($rowExp, $formData);
            //                $rowExp = $currentReplace['rowExp'];
            //$rowExp = str_replace('$current', '$formData', $rowExp);    
            //                $isCurrentReplace = true;
            //            }        
            $rowExp = preg_replace('/\$(.*?)->/', '\$$1.', $rowExp);
            //            $rowExp = preg_replace('/\$(.*?)\./', '\$$1->', $rowExp);
            preg_match('/=+\s*new(.*)/', $rowExp, $checkNewObject);
            preg_match_all('/\$[\w\d]+\s*=+(.*)/', $rowExp, $parseExpressionEqual);
            preg_match_all('/(\$[\w\d]*)+\s*=/', $rowExp, $parseExpressionEqual2);
            // preg_match_all('/\<(.*?)\>/i', $rowExp, $getObjectFromExp);
            // preg_match_all('/read\((.*?)\)/i', $rowExp, $getRecordId);
            preg_match_all('/runGet:(.*?)\((.*?)\)/i', $rowExp, $getRecordId);
            preg_match_all('/runMeta:(.*?)\((.*?)\)/i', $rowExp, $getMetaParameter);
            preg_match_all('/runWebservice:(.*?)\((.*?)\)/i', $rowExp, $getMetaParameter2);
            preg_match_all('/runProcedure:(.*?)\((.*?)\)/i', $rowExp, $getProcedureParameter);
            preg_match_all('/runFunction:(.*?)\((.*?)\)/i', $rowExp, $getFunctionParameter);
            preg_match('/.save/i', $rowExp, $getSave);
            preg_match('/.update/i', $rowExp, $getUpdate);
            preg_match('/for /i', $rowExp, $getFor);
            $getParamValue = '';
            $getMetaValue = $getMetaParameter && isset($getMetaParameter[2]) ? isset($getMetaParameter[2][0]) ? $getMetaParameter[2][0] : '' : '';
            $getMetaCode = $getMetaParameter && isset($getMetaParameter[1]) ? isset($getMetaParameter[1][0]) ? $getMetaParameter[1][0] : '' : '';
            $getMetaValue2 = $getMetaParameter2 && isset($getMetaParameter2[2]) ? isset($getMetaParameter2[2][0]) ? $getMetaParameter2[2][0] : '' : '';
            $getMetaCode2 = $getMetaParameter2 && isset($getMetaParameter2[1]) ? isset($getMetaParameter2[1][0]) ? $getMetaParameter2[1][0] : '' : '';
            $getRecordValue = $getRecordId && isset($getRecordId[2]) ? isset($getRecordId[2][0]) ? $getRecordId[2][0] : '' : '';
            $getRecordCode = $getRecordId && isset($getRecordId[1]) ? isset($getRecordId[1][0]) ? $getRecordId[1][0] : '' : '';
            $getProcValue = $getProcedureParameter && isset($getProcedureParameter[2]) ? isset($getProcedureParameter[2][0]) ? $getProcedureParameter[2][0] : '' : '';
            $getProcCode = $getProcedureParameter && isset($getProcedureParameter[1]) ? isset($getProcedureParameter[1][0]) ? $getProcedureParameter[1][0] : '' : '';
            $getFunctionValue = $getFunctionParameter && isset($getFunctionParameter[2]) ? isset($getFunctionParameter[2][0]) ? $getFunctionParameter[2][0] : '' : '';
            $getFunctionName = $getFunctionParameter && isset($getFunctionParameter[1]) ? isset($getFunctionParameter[1][0]) ? $getFunctionParameter[1][0] : '' : '';

            if ($getMetaCode) {
                $rowExp = str_replace('runMeta:' . $getMetaCode . '(' . $getMetaValue . ')', '$.ajax({
                type: "post",
                url: "mdexpression/microRunMeta/' . $getMetaCode . '/' . $getMetaValue . '",
                dataType: "json",
                async: false,
              }).responseJSON', $rowExp);
                return $rowExp;
            }

            if ($getMetaCode2) {
                $rowExp = str_replace('runWebservice:' . $getMetaCode2 . '(' . $getMetaValue2 . ')', 'self::microRunWebservice("' . $getMetaCode2 . '", "' . $getMetaValue2 . '")', $rowExp);
                return $rowExp;
            }

            if ($getProcCode) {
                $rowExp = str_replace('runProcedure:' . $getProcCode . '(' . $getProcValue . ')', 'self::microRunProcedure("' . $getProcCode . '", "' . $getProcValue . '")', $rowExp);
                return $rowExp;
            }

            if ($getRecordValue) {
                $rowExp = str_replace('runGet:' . $getRecordCode . '(' . $getRecordValue . ')', 'self::microRunGet("' . $getRecordCode . '", "' . $getRecordValue . '")', $rowExp);
                return $rowExp;
            }

            if ($getFunctionName) {
                $getFunctionValue2 = "'" . $getFunctionName . '\',' . $getFunctionValue;
                //                $graph=[[0,0,1,2,0,0,0],[0,0,2,0,0,3,0],[1,2,0,1,3,0,0],[2,0,1,0,0,0,1],[0,0,3,0,0,2,0],[0,3,0,0,2,0,1],[0,0,0,1,0,1,0]];
                //                pa(self::microCallFunction('dijkstra',$graph, 0));
                $rowExp = str_replace('runFunction:' . $getFunctionName . '(' . $getFunctionValue . ')', 'self::microCallFunction(' . $getFunctionValue2 . ')', $rowExp);
                return $rowExp;
            }

            $instanceExp = &getInstance();
            $instanceExp->load->model('mdform', 'middleware/models/');

            if (!empty($getFor)) {
                $newInstance = self::microForLoop($rowExp, $formData);
                return $newInstance;
            }

            if (!empty($checkNewObject)) {
                $newInstance = self::microCreateClientObject($rowExp, $formData);
                return $newInstance;
            }

            if (!empty($getSave)) {
                $_POST['isMicroFlowSelfSave'] = true;
                $getSaveVariable = explode('.', $rowExp);
                // $saveInstance = self::microSaveObject($getSaveVariable[0], $formData);
                return 'self::microSaveObject(' . $getSaveVariable[0] . ')';
            }

            if (!empty($getUpdate)) {
                $_POST['isMicroFlowSelfSave'] = true;
                preg_match_all('/update\((.*?)\)/i', $rowExp, $rowExpUpdate);
                $recordId = str_replace('#selectedId#', Input::post('kpiTblId'), $rowExpUpdate[1][0]);
                $getSaveVariable = explode('.', $rowExp);
                return 'microFlowUpdateObject(' . $getSaveVariable[0] . ',\'' . $recordId . '\')';
            }

            //            if (!$isCurrentReplace) {
            //                $rowExp = preg_replace('/\$(.*?)\./', '\$$1->', $rowExp);
            //            }
            // if (substr_count($rowExp, '$') > 1) {
            //     return $rowExp;
            // }

            // if (substr_count($getParamValue, '$current') > 0) {
            //     $getParamValue = self::microCurrentReplace($getParamValue, $formData);
            // }

            $getObject = [];
            if (isset($parseExpressionEqual[1][0])) {
                $getObjectCodeName = explode('.', $parseExpressionEqual[1][0]);
                $getObject = $instanceExp->model->getKpiIndicatorByCodeModel(trim($getObjectCodeName[0]));
            }

            if (empty($getObject)) {
                return $rowExp;
            }

            $classString = '$getObjectRecordRow = [];' . PHP_EOL;
            $classString .= '$instanceExp = &getInstance();' . PHP_EOL;
            $classString .= '$instanceExp->load->model(\'mdform\', \'middleware/models/\');' . PHP_EOL;
            $classString .= '$getObjectProps = $instanceExp->model->getKpiIOIndicatorColumnsModel(' . $getObject['ID'] . ', null);' . PHP_EOL;
            //$whereColumn = isset($currentReplace) && isset($currentReplace['path']) ? $currentReplace['path'] : 'ID';
            if ($getParamValue) {
                $getParamValue = explode(',', $getParamValue);
                if (!isset($getParamValue[1])) {
                    $getParamValue[1] = 'ID';
                }
                $classString .= '$getObjectRecordRow = $instanceExp->model->getKpiDynamicActiveDataRowModel(\'' . $getObject['TABLE_NAME'] . '\', \'' . trim($getParamValue[1]) . '\', ' . trim($getParamValue[0]) . ');' . PHP_EOL;
            }
            $getObjectMethods = $instanceExp->model->objectMethodModel($getObject['ID']);

            // $parseExpressionEqual2 = explode('=', $getObjectMethod[0][0]);
            // $getObjectMethod = $instanceExp->model->getKpiIndicatorByCodeModel(explode('.', $parseExpressionEqual2[1])[1]);
            // $getObjectMethodExp = str_replace('$', '$this->', $getObjectMethod['VAR_FNC_EXPRESSION_STRING']);

            // pa($getObjectRecordRow);

            // $filterData = $getObjectRecordRow = array();

            // foreach ($obj['criteria'] as $inputParam => $inputParamVal) {
            //     $filterData[$inputParamVal['objectAttr']][] = issetParam($formData[$inputParamVal['criteriaValue']]);
            // }        

            // $_POST['indicatorId'] = $getObject['ID'];
            // $_POST['filterData']  = $filterData;

            // $result = $this->model->indicatorDataGridModel();

            // if (isset($result['status']) && $result['status'] == 'success') {

            //     $rows     = $result['rows'];
            //     $getObjectRecordRow = $rows[0];
            //     $getObjectRecordRow['COUNT'] = $result['total'];

            // }   

            $props = '';
            // $props .= 'public static $COUNT = "'.$getObjectRecordRow['COUNT'].'";'.PHP_EOL;
            $props .= 'private $data = [];' . PHP_EOL;
            // foreach ($getObjectProps as $objRow) {
            // ${$dynamicVariable}[$objRow['COLUMN_NAME']] = $getObjectRecordRow[$objRow['COLUMN_NAME']];
            // $props .= 'public $'.$objRow['COLUMN_NAME'].';'.PHP_EOL;
            // }     

            // Class defination
            $objecPrefix = getUID();
            $classString .= 'class Obj' . $objecPrefix . '_' . $getObject['CLASS_NAME'] . ' {' . PHP_EOL;

            // Properties
            $classString .= $props;

            // Magic methods
            $classString .= 'public function __set($name, $value) {' . PHP_EOL;
            $classString .= '$this->data[$name] = $value;' . PHP_EOL;
            $classString .= '}' . PHP_EOL;
            $classString .= 'public function __get($name) {' . PHP_EOL;
            $classString .= 'if (array_key_exists($name, $this->data)) {' . PHP_EOL;
            $classString .= 'return $this->data[$name];' . PHP_EOL;
            $classString .= '}' . PHP_EOL;
            $classString .= '}' . PHP_EOL;

            // Class end
            $classString .= '}' . PHP_EOL;

            $classString .= $parseExpressionEqual2[1][0] . ' = new Obj' . $objecPrefix . '_' . $getObject['CLASS_NAME'] . ';' . PHP_EOL;

            $classString .= $parseExpressionEqual2[1][0] . '->isempty = (empty($getObjectRecordRow) ? 1 : 0);';
            $classString .= 'foreach ($getObjectProps as $objRow) {';
            // ${$dynamicVariable}[$objRow['COLUMN_NAME']] = $getObjectRecordRow[$objRow['COLUMN_NAME']];
            // $props .= 'public $'.$objRow['COLUMN_NAME'].';'.PHP_EOL;
            $classString .= $parseExpressionEqual2[1][0] . '->$objRow[\'COLUMN_NAME\'] = (isset($getObjectRecordRow[$objRow[\'COLUMN_NAME\']]) ? (is_numeric($getObjectRecordRow[$objRow[\'COLUMN_NAME\']]) ? $getObjectRecordRow[$objRow[\'COLUMN_NAME\']] : \'"\'.$getObjectRecordRow[$objRow[\'COLUMN_NAME\']].\'"\') : \'""\');' . PHP_EOL;
            $classString .= '}' . PHP_EOL;

            // public function '.$getObjectMethod['CODE'].'() {
            // '.$getObjectMethodExp.'
            // }
            $classString = rtrim($classString, ';');

            return $classString;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function microMessageObject($obj, $formData = [])
    {
        $rowExp = $obj['text'];
        if (substr_count($rowExp, '.val()') > 0) {
            $currentReplace = self::microCurrentReplace($rowExp, $formData);
            $rowExp = $currentReplace['rowExp'];
        }
        $rowExp = str_replace('"', '', $rowExp);
        preg_match('/\$(.*?)\[(.*?)\]/', $rowExp, $expArrayCatch);

        $mrowexp = $rowExp;
        $action = '';
        if (issetParam($obj['expressionindicator'])) {
            $action = json_decode($obj['expressionindicator'], true);
            $action = $action['id'];
        }
        if ($expArrayCatch) {
            $mrowexp = $rowExp . '".' . $expArrayCatch[0];
        }
        return 'array_push($pushMicroFlowMessage, ["message"=>"' . $mrowexp . '", "type"=>"' . issetParam($obj['font-family']) . '", "action"=>"' . $action . '"])';
    }

    public function microClientMessageObject($obj, $formData = [])
    {
        $rowExp = $obj['text'];
        if (substr_count($rowExp, '.val()') > 0) {
            $currentReplace = self::microCurrentReplace($rowExp, $formData);
            $rowExp = $currentReplace['rowExp'];
        }
        $rowExp = str_replace('"', '', $rowExp);
        preg_match('/\$(.*?)\[(.*?)\]/', $rowExp, $expArrayCatch);

        $mrowexp = $rowExp;
        $action = '';
        if (issetParam($obj['expressionindicator'])) {
            $action = json_decode($obj['expressionindicator'], true);
            $action = $action['id'];
        }
        if ($expArrayCatch) {
            $mrowexp = $rowExp . '".' . $expArrayCatch[0];
        }
        return 'new PNotify({title: "Анхааруулга", text: "' . $mrowexp . '", type: "' . issetParam($obj['font-family']) . '", sticker: false})';
    }

    /*public function microMessageObject($obj, $formData = []) {
        $rowExp = $obj['text'];
        if (substr_count($rowExp, '.val()') > 0) {
            $currentReplace = self::microCurrentReplace($rowExp, $formData);
            $rowExp = $currentReplace['rowExp'];
        }                
        $rowExp = str_replace('"', '', $rowExp);
        preg_match('/\$(.*?)\[(.*?)\]/', $rowExp, $expArrayCatch);
        
        if ($expArrayCatch) {
            $rowExp = str_replace($expArrayCatch[0], '', $rowExp);
            return 'return "'.$rowExp.'".'.$expArrayCatch[0];
        } else {
            return 'return "'.$rowExp.'"';
        }
    }*/

    public function microConditionObject($obj, $formData = [])
    {
        $rowExp = $obj['condition'];
        //        if (substr_count($rowExp, '$current') > 0) {
        //            $currentReplace = self::microCurrentReplace($rowExp, $formData);
        //            $rowExp = $currentReplace['rowExp'];
        //        }                
        if (substr_count($rowExp, '.val()') > 0) {
            $currentReplace = self::microCurrentReplace($rowExp, $formData);
            $rowExp = $currentReplace['rowExp'];
        }
        $expCondition = preg_replace('/\$(.*?)\./', '\$$1->', $rowExp);
        return $expCondition;
    }

    public function microFlowExpression($indicatorId = '', $formData = [])
    {
        $instanceExp = &getInstance();
        $instanceExp->load->model('mdform', 'middleware/models/');
        Mdform::$isIndicatorRendering = true;
        $getExp = $instanceExp->model->getKpiIndicatorExpressionModel($indicatorId);
        $rowExp = issetParam($getExp['VAR_FNC_EXPRESSION_STRING']);
        
        if (empty($rowExp) || empty($getExp['VAR_FNC_EXPRESSION_STRING_JSON'])) {
            return '';
        }
        
        $rowExpJson = html_entity_decode($getExp['VAR_FNC_EXPRESSION_STRING_JSON']);
        
        preg_match_all('/var (.*?)/', $rowExpJson, $parseExpressionEqual);
        foreach ($parseExpressionEqual[0] as $key => $row) {
            $rowExpJson = str_replace($row, $parseExpressionEqual[1][$key], $rowExpJson);
        }

        /**
         * Replace session values
         */
        $rowExpJson = str_ireplace(':sessiondepartmentid', Mdmetadata::getDefaultValue('sessiondepartmentid'), $rowExpJson);
        $rowExpJson = str_ireplace(':sessionuserkeydepartmentid', Mdmetadata::getDefaultValue('sessionuserkeydepartmentid'), $rowExpJson);
        $rowExpJson = str_ireplace(':sessioncompanydepartmentid', Mdmetadata::getDefaultValue('sessioncompanydepartmentid'), $rowExpJson);
        $rowExpJson = str_ireplace(':sysdatetime', "'".Date::currentDate()."'", $rowExpJson);
        $rowExpJson = str_ireplace(':sysdate', "'".Date::currentDate('Y-m-d')."'", $rowExpJson);

        //Mdmetadata::getDefaultValue($value)
        $rowExpJson = json_decode($rowExpJson, true);

        if ($rowExp) {
            $rowExp = rtrim($rowExp, ';');
            $rowExp .= ';';
            // Mdform::$fillFromExpression = true;                      

            foreach ($rowExpJson['cells'] as $row) {
                switch ($row['type']) {
                    case 'fsa.StartState':
                        $rowExp = str_replace($row['id'] . ';', '', $rowExp);
                        break;
                    case 'standard.Rectangle':
                        if (issetParam($row['attrs']['label']['code']) == 'find-object') {
                            $mfindObj = self::microFindObject($row['attrs']['label'], $formData);
                            if (is_null($mfindObj)) return '';
//                            if (is_null($mfindObj)) return $row['attrs']['label'];
                            $rowExp = str_replace($row['id'], $mfindObj, $rowExp);
                        }
                        break;
                    case 'standard.Polygon':
                        $rowExp = str_replace($row['id'], self::microConditionObject($row['attrs']['label'], $formData), $rowExp);
                        break;
                    case 'erd.Entity':
                        if (issetParam($row['attrs']['label']['code']) == 'confirmation') {
                            $rowExp = str_replace($row['id'], $row['id'] . '♥microFlowConfirmationDialog♥' . $row['attrs']['text']['text'] . '♥' . $row['id'], $rowExp);
                        } else {
                            $rowExp = str_replace($row['id'], self::microMessageObject($row['attrs']['text'], $formData), $rowExp);
                        }
                        break;
                    default:
                        break;
                }
            }

            return $rowExp;
            // dd($getResponse);

            // $classInstance = new $getObject['CODE']();
            // $getCallMethod = $getObjectMethod['CODE'];

            // Mdform::$kpiDmMart = [
            //     trim($parseExpressionEqual2[0]) => $classInstance->$getCallMethod()
            // ];

        }

        return '';
    }

    public function microFlowClientExpression($indicatorId = '', $formData = [], $rowExpJson = null, $rowExp = '')
    {
        $instanceExp = &getInstance();
        $instanceExp->load->model('mdform', 'middleware/models/');
        Mdform::$isIndicatorRendering = true;

        if (!isset($rowExpJson)) {
            $rowExp = $instanceExp->model->getKpiTemplateExpressionModel($indicatorId, 'VAR_FNC_EXPRESSION_STRING');
            $rowExpJson = $instanceExp->model->getKpiTemplateExpressionModel($indicatorId, 'VAR_FNC_EXPRESSION_STRING_JSON');


            preg_match_all('/var (.*?)/', $rowExpJson, $parseExpressionEqual);

            foreach ($parseExpressionEqual[0] as $key => $row) {
                $rowExpJson = str_replace($row, $parseExpressionEqual[1][$key], $rowExpJson);
            }

            /**
             * Replace session values
             */
            $rowExpJson = str_replace(':sessiondepartmentid', Mdmetadata::getDefaultValue('sessiondepartmentid'), $rowExpJson);

            //        $rowExp = Input::post('expressionString');        
            //        $rowExpJson = $_POST['expressionStringJson'];
            $rowExpJson = json_decode($rowExpJson, true);
        }

        if ($rowExp) {
            $rowExp = rtrim($rowExp, ';');
            $rowExp .= ';';

            foreach ($rowExpJson['cells'] as $key => $row) {
                if (isset($row['isconvert'])) continue;

                switch ($row['type']) {
                    case 'fsa.StartState':
                        $rowExpJson['cells'][$key]['isconvert'] = true;
                        $rowExp = str_replace($row['id'] . ';', '', $rowExp);
                        break;
                    case 'standard.Rectangle':
                        if (issetParam($row['attrs']['label']['code']) == 'find-object') {
                            $rowExpJson['cells'][$key]['isconvert'] = true;
                            $rowExp = str_replace($row['id'], self::microFindClientObject($row['attrs']['label'], $formData), $rowExp);
                        }
                        break;
                    case 'standard.Polygon':
                        $rowExpJson['cells'][$key]['isconvert'] = true;
                        $rowExp = str_replace($row['id'], self::microConditionObject($row['attrs']['label'], $formData), $rowExp);
                        break;
                    case 'erd.Entity':
                        $rowExp = str_replace($row['id'], self::microClientMessageObject($row['attrs']['text'], $formData), $rowExp);
                        break;
                    default:
                        break;
                }
            }

            return $rowExp;
        }

        return '';
    }

    public function microFlowClientExpressionOld($indicatorId = '', $formData = [])
    {
        $instanceExp = &getInstance();
        $instanceExp->load->model('mdform', 'middleware/models/');
        $allFieldExp = $instanceExp->model->getFlowClientExpressionModel($indicatorId);
        $resultExp = '';

        if ($allFieldExp) {
            foreach ($allFieldExp as $frow) {
                if ($frow['CHANGE_EXPRESSION_STRING']) {
                    $rowExp = '[' . $frow['COLUMN_NAME_PATH'] . '].change(){';
                    $rowExp .= $frow['CHANGE_EXPRESSION_STRING'];
                    $rowExpJson = json_decode($frow['CHANGE_JSON_CONFIG'], true);
                    $rowExp .= ';};';

                    foreach ($rowExpJson['cells'] as $row) {
                        switch ($row['type']) {
                            case 'fsa.StartState':
                                $rowExp = str_replace($row['id'] . ';', '', $rowExp);
                                break;
                            case 'standard.Rectangle':
                            case 'standard.Polygon':
                                $rowExp = str_replace($row['id'], $row['attrs']['label']['text'], $rowExp);
                                break;
                            case 'erd.Entity':
                                $rowExp = str_replace($row['id'], self::microMessageObject($row['attrs']['text'], $formData), $rowExp);
                                break;
                            default:
                                break;
                        }
                    }
                }

                $resultExp .= $rowExp;
            }
            return $resultExp;
        }

        return '';
    }

    public function executeMicroFlowExpression($indicatorId = '', $formData = []) {
        try {

            $executeScript = '$instanceExp = &getInstance();'.PHP_EOL;
            $executeScript .= '$instanceExp->load->model(\'mdform\', \'middleware/models/\');'.PHP_EOL;                  
            $executeScript .= '$pushMicroFlowMessage = [];'.PHP_EOL;
            //pa(self::microFlowExpression($indicatorId, $formData));
            $executeScript .= self::microFlowExpression($indicatorId, $formData);
//            pa($executeScript);
            
            @eval($executeScript);
            
            if ($pushMicroFlowMessage) {
                return [
                    'status' => 'success',
                    'data' => [
                        'type' => 'message',
                        'result' => $pushMicroFlowMessage
                    ]
                ];
            }
            
            return '_microflow_success';

            self::clearCacheFlowchart();

        } catch (ParseError $p) {
            return ['status' => 'error', 'message' => $p->getMessage(), 'type' => 'ParseError'];
        } catch (Error $p) {
            return ['status' => 'error', 'message' => $p->getMessage(), 'type' => 'Error'];
        } catch (Throwable $p) {
            return ['status' => 'error', 'message' => $p->getMessage(), 'type' => 'Throwable'];
        } catch (Exception $p) {
            return ['status' => 'error', 'message' => $p->getMessage(), 'type' => 'Exception'];
        }       
    }    

    public function executeMicroFlowExpressionNew($indicatorId = '', $formData = [])
    {
        try {
            $cache = phpFastCache();
            $indicatorId = Input::postCheck('microIndicatorId') ? Input::post('microIndicatorId') : $indicatorId;
            $sessionUserKeyId = Ue::sessionUserKeyId();
            // self::clientMicroFlowExpression($indicatorId); return;
            // $executeClientScript = '';
            $executeScript = '$instanceExp = &getInstance();' . PHP_EOL;
            $executeScript .= '$instanceExp->load->model(\'mdform\', \'middleware/models/\');' . PHP_EOL;
            $executeScript .= '$pushMicroFlowMessage = [];' . PHP_EOL;
            $generateScript = $cache->get('microflow_expression_data_' . $indicatorId . '_'.$sessionUserKeyId);
            $generateScript2 = $generateScript;

            if ($generateScript == null) {
                $generateScript = self::microFlowExpression($indicatorId, $formData);
            } elseif (Input::postCheck('flowId')) {
                $flowId = Input::post('flowId');
                $generateScript = preg_replace('/' . $flowId . '(.*?)' . $flowId . '/', '$isConfirmation=' . Input::post('isConfirmation'), $generateScript);
            }            

            $cache->set('microflow_expression_data_' . $indicatorId . '_'.$sessionUserKeyId, $generateScript, Mdwebservice::$expressionCacheTime);
            $rowExpSplite = explode(';', $generateScript);
            $microRuntimeArr = [];

            if (isset($flowId) || $generateScript2 == null) {
                foreach ($rowExpSplite as $key => $row) {

                    if (strpos($row, '♥microFlowConfirmationDialog') !== false) {
                        return [
                            'status' => 'microflowConfirmation',
                            'indicator' => [
                                'id' => $indicatorId
                            ],
                            'data' => $row
                        ];
                        exit;
                    }
                    preg_match('/\$[\w\d]+\s*=+(.[\S\s]*)/', $row, $parseExpressionEqualRegex);
                    if ($parseExpressionEqualRegex) {
                        preg_match('/\$[\w\d]+\s*=/', $parseExpressionEqualRegex[0], $parseExpressionEqual);
                        $parseExpressionEqual[0] = rtrim($parseExpressionEqual[0], '=');
                        // if ($key === 5) {
                        //     pa($parseExpressionEqualRegex);
                        // }                    
                        $replaceVariableName = $parseExpressionEqualRegex[1];
                        if ($microRuntimeArr) {
                            foreach ($microRuntimeArr as $varKey => $varVal) {
                                if (!is_array($varVal))
                                    $replaceVariableName = str_replace($varKey,  $varVal, $replaceVariableName);
                            }
                        }
                        $microRuntimeArr[$parseExpressionEqual[0]] = eval('return ' . $replaceVariableName . ';');
                        $generateScript = str_replace($parseExpressionEqualRegex[0], $parseExpressionEqual[0] . '=' . var_export($microRuntimeArr[$parseExpressionEqual[0]], true), $generateScript);
                        //$generateScript = str_replace($parseExpressionEqual[0], $microRuntimeArr[$parseExpressionEqual[0]], $generateScript);
                        $cache->set('microflow_expression_data_' . $indicatorId . '_'.$sessionUserKeyId, $generateScript, Mdwebservice::$expressionCacheTime);
                    }
                }
                            
                $cache->set('microflow_expression_data_' . $indicatorId . '_'.$sessionUserKeyId, $generateScript, Mdwebservice::$expressionCacheTime);
                echo json_encode(['status' => 'success'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $executeScript .= $generateScript;
            // pa($executeScript);
            eval($executeScript);

            /**
             * Result return type
             */
            //self::clearCacheFlowchart();
            if ($pushMicroFlowMessage) {
                return [
                    'status' => 'success',
                    'data' => [
                        'type' => 'message',
                        'result' => $pushMicroFlowMessage
                    ]
                ];
            }

            return '_microflow_success';            
        } catch (ParseError $p) {
            pa($p->getMessage());
            return [
                'status' => 'error',
                'message' => $p->getMessage()
            ];
        }
    }

    public function clientMicroFlowExpression($indicatorId)
    {
        $executeClientScript = '';
        $executeClientScript .= self::microFlowClientExpression($indicatorId);
        echo $executeClientScript;
    }

    public function uxFlowExpression($indicatorId, $attr = array())
    {

        Mdform::$isIndicatorRendering = true;

        $instanceExp = &getInstance();
        $instanceExp->load->model('mdform', 'middleware/models/');

        if ($expJson = issetParam($attr['expJson'])) {
            $rowExpJson = $expJson;
        } else {
            $rowExpJson = $instanceExp->model->getKpiTemplateExpressionModel($indicatorId, 'VAR_FNC_EXPRESSION_STRING_JSON');
        }

        $rowExpJson = json_decode($rowExpJson, true);
        $result = [];

        if ($rowExpJson) {

            foreach ($rowExpJson['cells'] as $row) {
                switch ($row['type']) {
                    case 'standard.Rectangle':
                        if (issetParam($row['attrs']['label']['code']) == 'find-object') {
                            $result = json_decode($row['attrs']['label']['expressionindicator'], true);
                        }
                        if (issetParam($row['attrs']['label']['code']) == 'button') {

                            $result['buttons'] = [];
                            $buttonInfo = json_decode($row['attrs']['label']['expressionindicator'], true);
                            $buttonInfo['buttonname'] = $row['attrs']['label']['text'];
                            $buttonInfo['buttoncolor'] = isset($row['attrs']['label']['expressionbuttoncolor']) ? $row['attrs']['label']['expressionbuttoncolor'] : 'btn-primary';
                            $buttonInfo['buttonicon'] = issetParam($row['attrs']['label']['expressionbuttonicon']);

                            if ($instanceExp->model->checkuxFlowUserPermissionModel($buttonInfo['id'])) {
                                $result['buttons'][] = $buttonInfo;
                            }
                        }

                        if (issetParam($row['attrs']['label']['code']) == 'indicator') {

                            $indicatorInfo = json_decode($row['attrs']['label']['expressionindicator'], true);
                            $result[] = $indicatorInfo;
                        }
                        break;
                    default:
                        break;
                }
            }
        }

        return $result;
    }

    public function uxFlowExpressionMapping($indicatorId, $buttonId)
    {
        $instanceExp = &getInstance();
        $instanceExp->load->model('mdform', 'middleware/models/');
        Mdform::$isIndicatorRendering = true;
        $rowExp = $instanceExp->model->getKpiTemplateExpressionModel($indicatorId, 'VAR_FNC_EXPRESSION_STRING');
        $rowExpJson = $instanceExp->model->getKpiTemplateExpressionModel($indicatorId, 'VAR_FNC_EXPRESSION_STRING_JSON');
        $rowExpJson = json_decode($rowExpJson, true);
        $result = [];

        if ($rowExpJson) {

            foreach ($rowExpJson['cells'] as $row) {
                switch ($row['type']) {
                    case 'standard.Rectangle':
                        if (issetParam($row['attrs']['label']['code']) == 'button') {
                            $buttonInfo = json_decode($row['attrs']['label']['expressionindicator'], true);
                            if ($buttonInfo['id'] == $buttonId) {
                                $result = json_decode($row['attrs']['label']['expressionmappingparameter'], true);
                            }
                        }
                        break;
                    default:
                        break;
                }
            }
        }

        return $result;
    }

    public function clearCacheFlowchart()
    {
        $tmp_dir = Mdcommon::getCacheDirectory();

        // $flowchartCacheFiles = glob($tmp_dir . "/*/mi/microCallFunctionTmpData_*.txt");
        $flowchartCacheFiles = glob($tmp_dir . "/*/mi/microflow_expression_data_*.txt");
        foreach ($flowchartCacheFiles as $flowchartCacheFile) {
            @unlink($flowchartCacheFile);
        }
    }

    public static function viewLogExpression($metaDataId) {
        
        $exp = 'bpRenderViewLog(bp_window_' . $metaDataId . '); ';
        $exp .= 'bp_window_' . $metaDataId . '.on(\'remove\', function(){ 
                    bpRenderViewLog(bp_window_' . $metaDataId . '); 
                }); ';

        return $exp;
    }
    
    public static function viewLogBeforeUnloadExpression($metaDataId) { 
        
        $exp = 'window.onbeforeunload = function(e) {  
                    bpRenderViewLog(bp_window_' . $metaDataId . '); 
                }; ';

        return $exp;
    }
    
}
