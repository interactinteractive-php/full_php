<?php
$_POST['metaDataId'] = $this->methodId;
$_POST['dataRow'] = [$this->paramData];
$_POST['isProcess'] = 'false';
$_POST['responseType'] = 'outputArray';

$templateMetaId = $this->methodRow['PREVIEW_REPORT_TEMPLATE_ID'];

if ($previewReportTemplateId = issetParam($this->paramData['previewreporttemplateid'])) {
    $templateMetaId = $previewReportTemplateId;
}

$_POST['print_options'] = [
    'numberOfCopies' => 1, 
    'isPrintNewPage' => 1,
    'isSettingsDialog' => 0,
    'isShowPreview' => 1,
    'isPrintPageBottom' => 0,
    'isPrintPageRight' => 0,
    'pageOrientation' => 'portrait',
    'isPrintSaveTemplate' => 0,
    'paperInput' => 'portrait',
    'pageSize' => strtolower(issetDefaultVal($this->paramData['printpapersize'], 'a4')),
    'printType' => '1col', 
    'templateMetaId' => $templateMetaId
];

$reportTemplate = (new Mdtemplate())->printOption();

echo $reportTemplate['Html'];