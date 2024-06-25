<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdupgrade_Model extends Model {
    
    private static $exportIgnoreColumns = ['CREATED_USER_ID', 'MODIFIED_USER_ID', 'EXPORT_SCRIPT', 'COPY_COUNT', 'COMPANY_DEPARTMENT_ID'];
    private static $exportIgnoreTableColumns = ['META_PROCESS_RULE' => ['IS_ACTIVE' => 1]];
    private static $ignoreDeleteScriptTables = ['UM_SYSTEM', 'META_PROCESS_RULE'];
    private static $executedTables = [];
    private static $executedTablesPrimaryColumn = [];
    private static $exportedMetaIds = [];
    private static $executedFolderIds = [];
    private static $scriptFolderIds = [];
    private static $deleteIds = [];
    private static $clobColumns = [];
    private static $blobColumns = [];
    private static $fileColumn = [];
    private static $fileColumns = [];
    private static $previousTranslateList = [];
    private static $replaceIds = [];
    private static $updateMetaIds = [];
    private static $exportedRecordIds = [];
    private static $childCreateTable = [];
    private static $exportCreateTables = [];
    private static $tablePrimaryField = [];
    private static $metaFolderId = null;
    private static $insertDataFilter = null;
    private static $deleteScript = null;
    private static $exportRecordId = null;
    private static $isIgnoreMetaFolder = false;
    private static $isIgnoreTranslate = false;
    private static $isIdReplace = false;
    private static $isPreviewUpdateMeta = false;
    private static $ignoreDeleteScript = false;
    private static $ignoreDbCommitTrans = false;
    private static $isMetaImportCopy = false;
    private static $isCreateTable = false;
    private static $isInsertData = true;
    private static $isCreateRollback = false;
    private static $isKpiDbSchemaNameReplace = false;
    private static $isGenerateLanguageFile = false;
    private static $isGlobeDictionaryPersist = false;

    public function __construct() {
        parent::__construct();
    }
    
    private function metaTableRelation() {
        
        return array(
            
            Mdmetadata::$reportTemplateMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_REPORT_TEMPLATE_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        ), 
                        'file' => array(
                            'HTML_FILE_PATH' => 'contentFile'
                        )
                    ), 
                    array(
                        'table' => 'META_SRC_TRG_PARAM', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'TRG_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_REPORT_TEMPLATE_GROUP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_REPORT_TEMPLATE_QRCODE', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'RT_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_PROCESS_TEMPLATE', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'TEMPLATE_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DM_TEMPLATE_DTL', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'TEMPLATE_META_DATA_ID'
                            )
                        )
                    )
                )
            ), 
            
            Mdmetadata::$menuMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_MENU_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        ), 
                        'file' => array(
                            'PHOTO_NAME' => 'imageFile'
                        )
                    ), 
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    )
                )
            ), 
            
            Mdmetadata::$statementMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_STATEMENT_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_STATEMENT_LINK_GROUP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_STATEMENT_TEMPLATE', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DM_DRILLDOWN_DTL', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'STATEMENT_META_DATA_ID'
                            )
                        ), 
                        'child' => array(
                            array(
                                'table' => 'META_DM_DRILLDOWN_PARAM', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'DM_DRILLDOWN_DTL_ID'
                                    )
                                )
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_TAG_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        ), 
                        'child' => array(
                            array(
                                'table' => 'META_TAG', 
                                'link' => array(
                                    array(
                                        'src' => 'TAG_ID', 
                                        'trg' => 'ID'
                                    )
                                )
                            )
                        )
                    ), 
                    array(
                        'table' => 'RP_REPORT_LAYOUT', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'REPORT_LAYOUT_ID'
                            )
                        )
                    )
                )
            ), 
            
            Mdmetadata::$metaGroupMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_GROUP_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        ), 
                        'child' => array(
                            array(
                                'table' => 'META_GROUP_SUB_QUERY', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'META_GROUP_LINK_ID'
                                    )
                                )
                            ), 
                            array(
                                'table' => 'META_DM_STATEMENT_DTL', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'META_GROUP_LINK_ID'
                                    )
                                )
                            ), 
                            array(
                                'table' => 'META_DM_DRILLDOWN_DTL', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'MAIN_GROUP_LINK_ID'
                                    )
                                ), 
                                'child' => array(
                                    array(
                                        'table' => 'META_DM_DRILLDOWN_PARAM', 
                                        'link' => array(
                                            array(
                                                'src' => 'ID', 
                                                'trg' => 'DM_DRILLDOWN_DTL_ID'
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_GROUP_CONFIG', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_GROUP_GRID_OPTIONS', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_GROUP_GRID_LAYOUT', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_META_DATA_ID'
                            )
                        ), 
                        'child' => array(
                            array(
                                'table' => 'META_GROUP_GRID_LAYOUT_DTL', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'HEADER_ID'
                                    )
                                )
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_GROUP_RELATION', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_GROUP_PARAM_CONFIG', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'GROUP_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_PARAM_VALUES', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DM_PROCESS_DTL', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DM_TRANSFER_PROCESS', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DM_PROCESS_BATCH', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_PROCESS_LOOKUP_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DM_ROW_PROCESS_PARAM', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DM_DM_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID' 
                            )
                        )
                    )
                )
            ), 
            
            Mdmetadata::$businessProcessMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_BUSINESS_PROCESS_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        ), 
                        'child' => array(
                            array(
                                'table' => 'META_BP_EXPRESSION_DTL', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'BP_LINK_ID'
                                    )
                                ), 
                                'child' => array(
                                    array(
                                        'table' => 'META_BP_EXP_CACHE_VERSION', 
                                        'link' => array(
                                            array(
                                                'src' => 'ID', 
                                                'trg' => 'VERSION_ID'
                                            )
                                        )
                                    )
                                )
                            ), 
                            array(
                                'table' => 'META_BP_EXP_CACHE', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'BP_LINK_ID'
                                    )
                                )
                            ), 
                            array(
                                'table' => 'META_PROCESS_TEMPLATE', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'PROCESS_LINK_ID'
                                    )
                                )
                            ), 
                            /*array(
                                'table' => 'META_PROCESS_NTF', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'BUSINESS_PROCESS_LINK_ID'
                                    )
                                ),
                                'child' => array(
                                    array(
                                        'table' => 'META_PROCESS_NTF_PARAM', 
                                        'link' => array(
                                            array(
                                                'src' => 'ID', 
                                                'trg' => 'PROCESS_NTF_ID'
                                            )
                                        )
                                    )
                                )
                            )*/
                        )
                    ), 
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_PROCESS_DEFAULT_GET', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'PROCESS_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_BP_EXPRESSION_PROCESS', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'PROCESS_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_PARAM_VALUES', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_GROUP_PARAM_CONFIG', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_PROCESS_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_PROCESS_LOOKUP_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'PROCESS_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_PROCESS_PARAM_ATTR_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'PROCESS_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_BUSINESS_PROCESS_TEMPLATE', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        ), 
                        'file' => array(
                            'HTML_FILE_PATH' => 'contentFile'
                        )
                    ), 
                    array(
                        'table' => 'META_PROCESS_WORKFLOW', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_BP_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_PROCESS_WF_BEHAVIOUR', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_BP_ID'
                            )
                        )
                    ),
                    array(
                        'table' => 'META_PROCESS_PARAM_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_BP_ID'
                            )
                        )
                    ),
                    array(
                        'table' => 'META_PROCESS_RULE', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_PROCESS_ID'
                            )
                        ), 
                        'child' => array(
                            array(
                                'table' => 'META_PROCESS_PARAM_LINK', 
                                'link' => array(
                                    array(
                                        'src' => 'MAIN_PROCESS_ID', 
                                        'trg' => 'DONE_BP_ID'
                                    ), 
                                    array(
                                        'src' => 'RULE_PROCESS_ID', 
                                        'trg' => 'DO_BP_ID'
                                    )
                                )
                            )
                        )
                    ),
                    array(
                        'table' => 'META_BP_LAYOUT_HDR', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        ), 
                        'child' => array(
                            array(
                                'table' => 'META_BP_LAYOUT_SECTION', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'HEADER_ID'
                                    )
                                )
                            ), 
                            array(
                                'table' => 'META_BP_LAYOUT_PARAM', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'HEADER_ID'
                                    )
                                )
                            )
                        )
                    ),
                    array(
                        'table' => 'META_DATA_SEQUENCE_CONFIG', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    )
                )
            ), 
            
            Mdmetadata::$taskFlowMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_BUSINESS_PROCESS_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_PROCESS_PARAM_ATTR_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'PROCESS_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_PROCESS_WORKFLOW', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_BP_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_PROCESS_WF_BEHAVIOUR', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_BP_ID'
                            )
                        )
                    ),
                    array(
                        'table' => 'META_PROCESS_PARAM_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_BP_ID'
                            )
                        )
                    ),
                    array(
                        'table' => 'META_PROCESS_RULE', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_PROCESS_ID'
                            )
                        ), 
                        'child' => array(
                            array(
                                'table' => 'META_PROCESS_PARAM_LINK', 
                                'link' => array(
                                    array(
                                        'src' => 'MAIN_PROCESS_ID', 
                                        'trg' => 'DONE_BP_ID'
                                    ), 
                                    array(
                                        'src' => 'RULE_PROCESS_ID', 
                                        'trg' => 'DO_BP_ID'
                                    )
                                )
                            )
                        )
                    )
                )
            ), 
            
            Mdmetadata::$bookmarkMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_BOOKMARK_LINKS', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    )
                )
            ), 
            
            Mdmetadata::$dashboardMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_DASHBOARD_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    )
                )
            ), 
            
            Mdmetadata::$contentMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_CONTENT_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_CONTENT_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    )
                )
            ), 
            
            Mdmetadata::$googleMapMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_GOOGLE_MAP_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        ), 
                        'child' => array(
                            array(
                                'table' => 'META_GOOGLE_MAP_PARAM', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'GOOGLE_MAP_LINK_ID'
                                    )
                                )
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    )
                )
            ), 
            
            Mdmetadata::$calendarMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_CALENDAR_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    )
                )
            ), 
            
            Mdmetadata::$donutMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_DONUT', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    )
                )
            ), 
            
            Mdmetadata::$cardMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_CARD', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    )
                )
            ), 
            
            Mdmetadata::$diagramMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_DASHBOARD_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    )
                )
            ), 
            
            Mdmetadata::$packageMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_PACKAGE_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    )
                )
            ), 
            
            Mdmetadata::$workSpaceMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_WORKSPACE_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        ), 
                        'child' => array(
                            array(
                                'table' => 'META_WORKSPACE_MENU_CRITERIA', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'META_WORKSPACE_LINK_ID'
                                    )
                                )
                            ), 
                            array(
                                'table' => 'META_WORKSPACE_PARAM_MAP', 
                                'link' => array(
                                    array(
                                        'src' => 'META_DATA_ID', 
                                        'trg' => 'WORKSPACE_META_ID'
                                    )
                                )
                            ), 
                            array(
                                'table' => 'META_WORKSPACE_WIDGET_MAP', 
                                'link' => array(
                                    array(
                                        'src' => 'META_DATA_ID', 
                                        'trg' => 'WORKSPACE_META_ID'
                                    )
                                )
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    )
                )
            ), 
            
            Mdmetadata::$layoutMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_LAYOUT_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        ), 
                        'child' => array(
                            array(
                                'table' => 'META_LAYOUT_PARAM_MAP', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'META_LAYOUT_LINK_ID'
                                    )
                                ), 
                                'child' => array(
                                    array(
                                        'table' => 'META_LAYOUT_PARAM_CONFIG', 
                                        'link' => array(
                                            array(
                                                'src' => 'ID', 
                                                'trg' => 'LAYOUT_PARAM_MAP_ID'
                                            )
                                        )
                                    ) 
                                )
                            ) 
                        )
                    ), 
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    )
                )
            ), 
            
            Mdmetadata::$widgetMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_WIDGET_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        ), 
                        'child' => array(
                            array(
                                'table' => 'META_WIDGET_PARAM', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'WIDGET_LINK_ID'
                                    )
                                )
                            ) 
                        )
                    ), 
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    )
                )
            ), 
            
            Mdmetadata::$proxyMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_PROXY_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    )
                )
            ), 
            
            Mdmetadata::$fieldMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_FIELD_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    )
                )
            ),

            Mdmetadata::$dmMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_DATAMART_LINK', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        ), 
                        'child' => array(
                            array(
                                'table' => 'META_DATAMART_COLUMN', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'META_DATAMART_LINK_ID'
                                    )
                                ),
                                'child' => array(
                                    array(
                                        'table' => 'META_DATAMART_COLUMN_CRITERIA', 
                                        'link' => array(
                                            array(
                                                'src' => 'ID', 
                                                'trg' => 'META_DATAMART_COLUMN_ID'
                                            )
                                        )
                                    )
                                )                                
                            ) 
                        )
                    ), 
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    ),
                    array(
                        'table' => 'META_GROUP_RELATION', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'MAIN_META_DATA_ID'
                            )
                        )
                    )
                )
            ), 
            Mdmetadata::$pageMetaTypeId => array(
                'META_DATA' => array(
                    array(
                        'table' => 'META_DATA_FOLDER_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_META_MAP', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'SRC_META_DATA_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_BP_LAYOUT_HDR', 
                        'link' => array(
                            array(
                                'src' => 'META_DATA_ID', 
                                'trg' => 'META_DATA_ID'
                            )
                        ), 
                        'child' => array(
                            array(
                                'table' => 'META_BP_LAYOUT_SECTION', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'HEADER_ID'
                                    )
                                ), 
                                'child' => array(
                                    array(
                                        'table' => 'META_BP_LAYOUT_SECTION_DTL', 
                                        'link' => array(
                                            array(
                                                'src' => 'ID', 
                                                'trg' => 'SECTION_ID'
                                            )
                                        )
                                    )
                                )
                            ), 
                            array(
                                'table' => 'META_BP_LAYOUT_PARAM', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'HEADER_ID'
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );
    }
    
    private function objectTableRelation() {
        
        $kpiDbSchemaName = Config::getFromCache('kpiDbSchemaName');
        $kpiDbSchemaName = $kpiDbSchemaName ? $kpiDbSchemaName.'.' : '';
        
        return array(

            'kpi' => array(
                'KPI_TEMPLATE' => array(
                    array(
                        'table' => 'KPI_TEMPLATE_DTL', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'TEMPLATE_ID'
                            )
                        ), 
                        'child' => array(
                            array(
                                'table' => 'KPI_TEMPLATE_DTL_FACT', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'TEMPLATE_DTL_ID'
                                    )
                                )
                            ), 
                            array(
                                'table' => 'KPI_INDICATOR', 
                                'link' => array(
                                    array(
                                        'src' => 'INDICATOR_ID', 
                                        'trg' => 'ID'
                                    )
                                ), 
                                'child' => array(
                                    array(
                                        'table' => 'KPI_DIMENSION', 
                                        'link' => array(
                                            array(
                                                'src' => 'DIMENSION_ID', 
                                                'trg' => 'ID'
                                            )
                                        )
                                    )
                                )
                            ) 
                        )
                    ), 
                    array(
                        'table' => 'KPI_TEMPLATE_FACT', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'TEMPLATE_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'KPI_TEMPLATE_MAP', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'SRC_TEMPLATE_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'KPI_TEMPLATE_CRITERIA', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'TEMPLATE_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'KPI_TEMPLATE_DIMENSION', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'TEMPLATE_ID'
                            )
                        )
                    )
                )
            ), 
            
            'ntf' => array(
                'NTF_NOTIFICATION' => array(
                    array(
                        'table' => 'NTF_NOTIFICATION_ACTION', 
                        'link' => array(
                            array(
                                'src' => 'NOTIFICATION_ID', 
                                'trg' => 'NOTIFICATION_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'NTF_NOTIFICATION_TEMPLATE_MAP', 
                        'link' => array(
                            array(
                                'src' => 'NOTIFICATION_ID', 
                                'trg' => 'NOTIFICATION_ID'
                            )
                        )
                    )
                )
            ), 
            
            'testcase' => array(
                'TEST_CASE' => array(
                    array(
                        'table' => 'TEST_CASE_QUALITY', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'TEST_CASE_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'TEST_CASE_SCENARIO_MAP', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'TEST_CASE_ID'
                            )
                        ), 
                        'child' => array(
                            array(
                                'table' => 'TEST_CASE_SCENARIO', 
                                'link' => array(
                                    array(
                                        'src' => 'SCENARIO_ID', 
                                        'trg' => 'ID'
                                    )
                                )
                            )
                        )
                    ), 
                    array(
                        'table' => 'TEST_CASE_SYSTEM_MAP', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'TEST_CASE_ID'
                            )
                        ), 
                        'child' => array(
                            array(
                                'table' => 'UM_SYSTEM', 
                                'link' => array(
                                    array(
                                        'src' => 'SYSTEM_ID', 
                                        'trg' => 'SYSTEM_ID'
                                    )
                                ), 
                                'child' => array(
                                    array(
                                        'table' => 'TEST_CASE_SYSTEM_USER_MAP', 
                                        'link' => array(
                                            array(
                                                'src' => 'SYSTEM_ID', 
                                                'trg' => 'SYSTEM_ID'
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            ), 
            
            'umobject' => array(
                'UM_OBJECT' => array(
                    array(
                        'table' => 'UM_OBJECT_CODE', 
                        'link' => array(
                            array(
                                'src' => 'OBJECT_ID', 
                                'trg' => 'OBJECT_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'UM_OBJECT_CODE_PARAM', 
                        'link' => array(
                            array(
                                'src' => 'OBJECT_ID', 
                                'trg' => 'OBJECT_ID'
                            )
                        )
                    )
                )
            ), 
            
            'bugfix' => array(
                'META_BUG_FIXING' => array(
                    array(
                        'table' => 'META_BUG_FIXING_DTL', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'META_BUG_FIXING_ID'
                            )
                        )
                    )
                )
            ), 
            
            'wfmfield' => array(
                'META_WFM_FIELD' => array(
                    array(
                        'table' => 'META_WFM_FIELD_DTL', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'WFM_FIELD_ID'
                            )
                        )
                    )
                )
            ), 
            
            'config' => array(
                'CONFIG' => array(
                    array(
                        'table' => 'CONFIG_VALUE', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'CONFIG_ID'
                            )
                        )
                    )
                )
            ), 
            
            'booktype' => array(
                'BOOK_TYPE' => array()
            ), 
            
            'kpiindicator' => array(
                'KPI_INDICATOR' => array(
                    array(
                        'table' => 'KPI_INDICATOR_CATEGORY', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'INDICATOR_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'KPI_INDICATOR_INDICATOR_MAP', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'MAIN_INDICATOR_ID'
                            )
                        ), 
                        'childCreateTable' => array(
                            array(
                                'isCreateTable'   => true, 
                                'isInsertData'    => self::$isInsertData, 
                                'tableColumnName' => 'TEMPLATE_TABLE_NAME'
                            )
                        ), 
                        'child' => array(
                            array(
                                'table' => 'KPI_INDICATOR_INDICATOR_MAP', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'SRC_INDICATOR_MAP_ID'
                                    )
                                ), 
                                'child' => array(
                                    array(
                                        'table' => 'KPI_INDICATOR_INDICATOR_MAP', 
                                        'link' => array(
                                            array(
                                                'src' => 'ID', 
                                                'trg' => 'SRC_INDICATOR_MAP_ID'
                                            )
                                        )
                                    )
                                )
                            ), 
                            array(
                                'table' => 'KPI_INDICATOR_MAP_CRITERIA', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'SRC_INDICATOR_MAP_ID'
                                    )
                                )
                            ), 
                            array(
                                'table' => 'KPI_INDICATOR_MAP_CRITERIA', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'INDICATOR_MAP_ID'
                                    )
                                )
                            )
                        )
                    ), 
                    array(
                        'table' => 'KPI_INDICATOR_INDICATOR_MAP', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'SRC_INDICATOR_ID'
                            )
                        ),
                        'child' => array(
                            array(
                                'table' => 'KPI_INDICATOR_INDICATOR_MAP', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'SRC_INDICATOR_MAP_ID'
                                    )
                                )
                            )
                        )
                    ), 
                    array(
                        'table' => 'KPI_INDICATOR_TYPE_MAP', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'INDICATOR_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'KPI_DATAMODEL_MAP', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'SRC_INDICATOR_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'KPI_DATAMODEL_MAP_KEY', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'MAIN_INDICATOR_ID'
                            )
                        ), 
                        'child' => array(
                            array(
                                'table' => 'KPI_DATAMODEL_MAP_KEY_DTL', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'DATAMODEL_MAP_KEY_ID'
                                    )
                                )
                            )
                        )
                    ), 
                    array(
                        'table' => 'KPI_DATAMODEL_CRITERIA', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'MAIN_INDICATOR_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'KPI_INDICATOR_REF_VALUE', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'INDICATOR_ID'
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_STATEMENT_LINK', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'MAIN_INDICATOR_ID' 
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_STATEMENT_LINK_GROUP', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'MAIN_INDICATOR_ID' 
                            )
                        )
                    ),
                    array(
                        'table' => 'META_REPORT_TEMPLATE_LINK', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'MAIN_INDICATOR_ID' 
                            )
                        )
                    ), 
                    array(
                        'table' => 'META_REPORT_TEMPLATE_GROUP', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'MAIN_INDICATOR_ID' 
                            )
                        )
                    ),
                    array(
                        'table' => 'META_DM_DRILLDOWN_DTL', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'MAIN_INDICATOR_ID'
                            )
                        ), 
                        'child' => array(
                            array(
                                'table' => 'META_DM_DRILLDOWN_PARAM', 
                                'link' => array(
                                    array(
                                        'src' => 'ID', 
                                        'trg' => 'DM_DRILLDOWN_DTL_ID'
                                    )
                                )
                            )
                        )
                    )
                )
            ), 
            
            'kpitype' => array(
                'KPI_TYPE' => array()
            ), 
            
            'kpiindicatorrelation' => array(
                $kpiDbSchemaName . 'V_16754202632369' => array()
            ), 
            
            'impexcel' => array(
                'IMP_EXCEL_TEMPLATE' => array(
                    array(
                        'table' => 'IMP_EXCEL_TEMPLATE_OPTIONS', 
                        'link' => array(
                            array(
                                'src' => 'ID', 
                                'trg' => 'EXCEL_TEMPLATE_ID'
                            )
                        )
                    )
                )
            ), 
            
            'metawidget' => array(
                'META_WIDGET' => array()
            ),
            
            'processrule' => array(
                'META_PROCESS_RULE' => array()
            ),
            
            'globedictionary' => array(
                'GLOBE_DICTIONARY' => array()
            ),
            
        );
    }
    
    public function tablePrimaryField($tableName) {
        
        if (!self::$tablePrimaryField) {
            $kpiDbSchemaName = Config::getFromCache('kpiDbSchemaName');
            $kpiDbSchemaName = $kpiDbSchemaName ? $kpiDbSchemaName.'.' : '';

            self::$tablePrimaryField = [
                'NTF_NOTIFICATION' => 'NOTIFICATION_ID', 
                'UM_OBJECT_CODE' => 'OBJECT_CODE_ID', 
                'UM_OBJECT' => 'OBJECT_ID', 
                'BOOK_TYPE' => 'BOOK_TYPE_ID', 
                $kpiDbSchemaName . 'V_16754202632369' => 'SRC_RECORD_ID'
            ];
        } 
        
        return isset(self::$tablePrimaryField[$tableName]) ? self::$tablePrimaryField[$tableName] : 'ID';
    }
    
    public function tableFileColumns($tableName) {
        
        $arr = array(
            'KPI_INDICATOR' => array(
                'PROFILE_PICTURE' => 'imageFile', 
                'ICON' => 'imageFile'
            ), 
            'KPI_INDICATOR_INDICATOR_MAP' => array(
                'DEFAULT_FILE' => 'contentFile'
            )
        );
        return issetParamArray($arr[$tableName]);
    }
    
    public function dynamicCreateTables($tableName) {
        
        $arr = array(
            'KPI_INDICATOR' => array(
                array(
                    'isCreateTable'   => self::$isCreateTable, 
                    'tableColumnName' => 'TABLE_NAME'
                )
            )
        );
        
        return issetParamArray($arr[$tableName]);
    }
    
    public function bugfixDatagridModel() {
        
        $page = Input::post('page', 1);
        $rows = Input::post('rows', 10);
        
        $param = array(
            'systemMetaGroupId' => '1498128719613',
            'showQuery' => 0,
            'paging' => array(
                'offset' => $page,
                'pageSize' => $rows
            )
        );
        
        if (Input::postCheck('sort') && Input::postCheck('order')) {
            
            $sortField = Input::post('sort');
            $sortOrder = Input::post('order');

            if (strpos($sortField, ',') === false) {
                
                $param['paging']['sortColumnNames'] = array(
                    $sortField => array(
                        'sortType' => $sortOrder
                    )
                );
                
            } else {
                
                $sortFieldArr = explode(',', $sortField);
                $sortOrderArr = explode(',', $sortOrder);
                
                foreach ($sortFieldArr as $sortK => $sortF) {
                    $sortColumnNames[$sortF] = array('sortType' => $sortOrderArr[$sortK]);
                }
                
                $param['paging']['sortColumnNames'] = $sortColumnNames;
            }
        }
        
        if (Input::postCheck('sortFields')) {
            
            parse_str(Input::post('sortFields'), $sortFields);
            
            if (count($sortFields) > 0) {
                
                foreach ($sortFields as $sortKey => $sortType) {
                    
                    $param['paging']['sortColumnNames'] = array(
                        $sortKey => array(
                            'sortType' => $sortType
                        )
                    );
                }
            }
        }
        
        if (Input::postCheck('filterRules')) {
            
            $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);

            if (count($filterRules) > 0) {

                foreach ($filterRules as $rule) {

                    $field = $rule['field'];
                    $value = Input::param($rule['value']);

                    $operatorFilter = 'LIKE';

                    $paramFilter[$field][] = array(
                        'operator' => $operatorFilter,
                        'operand'  => $operatorFilter === 'LIKE' ? '%'.$value.'%' : $value
                    );
                }

                if (isset($param['criteria'])) {
                    $param['criteria'] = array_merge($param['criteria'], $paramFilter);
                } else {
                    $param['criteria'] = $paramFilter;
                }
            }
        }
        
        if (Input::postCheck('defaultCriteriaData')) {

            parse_str(Input::post('defaultCriteriaData'), $defaultCriteriaData);
            
            $defaultCriteriaCondition = $defaultCriteriaData['criteriaCondition'];
            $defaultCriteriaParam     = $defaultCriteriaData['param'];
            
            foreach ($defaultCriteriaParam as $defParam => $defParamVal) {
                
                $defParamVal = Input::param($defParamVal);
                
                if ($defParamVal) {
                    
                    if (isset($defaultCriteriaCondition[$defParam])) {
                        $operator = $defaultCriteriaCondition[$defParam];
                    } else {
                        $operator = 'between';
                    }

                    if ($defParam == 'createddate' && !empty($defParamVal)) {
                        $paramDefaultCriteria['createddate'][] = array(
                            'operator' => $operator,
                            'operand'  => $defParamVal[0].' AND '.$defParamVal[1]
                        );
                    } else {
                        $paramDefaultCriteria[$defParam][] = array(
                            'operator' => $operator,
                            'operand'  => ($operator == 'like') ? '%'.$defParamVal.'%' : $defParamVal 
                        );
                    }
                }
            }
            
            if (isset($param['criteria'])) {
                $param['criteria'] = array_merge($param['criteria'], $paramDefaultCriteria);
            } else {
                $param['criteria'] = $paramDefaultCriteria;
            }
        }
        
        ini_set('max_execution_time', 15);
        ini_set('default_socket_timeout', 15);
        
        $data = Mdupgrade::getBugfixDataByCommand('list', $param);
        
        $result = array();
        
        if ($data['status'] == 'success' && isset($data['result'])) {
            
            $result['total'] = (isset($data['result']['paging']) ? $data['result']['paging']['totalcount'] : 0);

            unset($data['result']['paging']);

            if (isset($data['result']['aggregatecolumns']) && $data['result']['aggregatecolumns']) {
                $result['footer'] = array($data['result']['aggregatecolumns']);
            }
            unset($data['result']['aggregatecolumns']);
            
            $customerBugFixed = self::getCustomerBugFixed();
            
            array_walk($data['result'], function(&$value) use ($customerBugFixed) {          
                $value['fixed'] = isset($customerBugFixed[$value['id']]) ? '1' : '0';
            }); 
                            
            $result['rows'] = $data['result'];
            $result['status'] = 'success';
            
        } else {
            
            $message = isset($data['message']) ? $data['message'] : $this->ws->getResponseMessage($data);
            
            $result = array('status' => 'error', 'message' => $message, 'rows' => array(), 'total' => 0);
        }
        
        return $result;
    }
    
    public function getCustomerBugFixed() {
        
        $data = $this->db->GetAll("SELECT META_BUG_FIXING_ID FROM CUSTOMER_BUG_FIXED GROUP BY META_BUG_FIXING_ID");
        
        if ($data) {
            foreach ($data as $row) {
                $data[$row['META_BUG_FIXING_ID']] = 1;
            }
        }
        
        return $data;
    }
    
    public function updatingBugFixingModel() {
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('default_socket_timeout', 30000);
        
        $bugFixId = Input::numeric('bugFixId');
        
        if ($bugFixId) {
            
            $exportData = Mdupgrade::getBugfixDataByCommand('download', array('ids' => $bugFixId));

            if ($exportData['status'] == 'success' && isset($exportData['result'])) {
                
                includeLib('Compress/Compression');
                
                $fileContent = Compression::gzinflate($exportData['result']);
                
                if ($fileContent && strpos($fileContent, '<meta id="') === false) {
                    return array('status' => 'error', 'message' => 'PHP export хийсэн файл уншуулна уу!', 'logs' => '');
                } 
                
                $response = self::executeUpgradeScript(array($fileContent));
                
                if ($response['status'] == 'success') {
                    
                    $currentDate = Date::currentDate('Y-m-d H:i:s');

                    $fixedData = array(
                        'ID'                 => getUID(), 
                        'META_BUG_FIXING_ID' => $bugFixId, 
                        'CREATED_USER_ID'    => Ue::sessionUserKeyId(), 
                        'CREATED_DATE'       => $currentDate, 
                        'STATUS_ID'          => 1, 
                        'MODIFIED_DATE'      => $currentDate
                    );

                    $this->db->AutoExecute('CUSTOMER_BUG_FIXED', $fixedData);
                }

                return array('status' => 'success', 'logs' => issetParam($response['logs']));
            }
            
        } else {
            return array('status' => 'error', 'message' => 'Invalid ids!');
        }
    }
    
    public function getMetaTypeByMultiIds($ids) {
        
        $ids = str_replace(',,', ',', $ids);
        $ids = rtrim($ids, ',');
        
        if ($ids) {
            
            $data = $this->db->GetAll("
                SELECT 
                    META_TYPE_ID, 
                    META_DATA_ID, 
                    META_DATA_CODE 
                FROM META_DATA 
                WHERE META_TYPE_ID IN (".Mdmetadata::$businessProcessMetaTypeId.", ".Mdmetadata::$metaGroupMetaTypeId.") 
                    AND META_DATA_ID IN ($ids)"); 
            
        } else {
            $data = null;
        }
        
        return $data;
    }
    
    public function downloadBugFixingModel($ids) {
        
        if ($ids) {
            
            includeLib('Compress/Compression');
            
            $scripts = $this->db->GetAll(" 
                SELECT 
                    SCRIPT 
                FROM META_BUG_FIXING 
                WHERE ID IN ($ids) 
                    AND SCRIPT IS NOT NULL 
                ORDER BY CREATED_DATE ASC");

            $metas = $this->db->GetAll("
                SELECT 
                    TMP.*
                FROM ( 
                
                    SELECT 
                        TO_CHAR(MD.META_TYPE_ID) AS META_TYPE_ID, 
                        MD.META_DATA_ID, 
                        MD.META_DATA_CODE, 
                        ".$this->db->IfNull('UD.USERNAME', 'UM.USERNAME')." AS USER_NAME, 
                        ".$this->db->IfNull('MD.MODIFIED_DATE', 'MD.CREATED_DATE')." AS MODIFIED_DATE, 
                        HDR.CREATED_DATE, 
                        NULL AS SRC_RECORD_ID 
                    FROM META_BUG_FIXING HDR 
                        INNER JOIN META_BUG_FIXING_DTL DTL ON DTL.META_BUG_FIXING_ID = HDR.ID 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = DTL.META_DATA_ID 
                        LEFT JOIN UM_USER US ON US.USER_ID = MD.CREATED_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = US.SYSTEM_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UD ON UD.USER_ID = MD.MODIFIED_USER_ID 
                    WHERE HDR.ID IN ($ids) 
                    
                    UNION 

                    SELECT 
                        'wfmfield' AS META_TYPE_ID, 
                        MD.ID AS META_DATA_ID, 
                        TO_CHAR(MD.ID) AS META_DATA_CODE, 
                        ".$this->db->IfNull('UD.USERNAME', 'UM.USERNAME')." AS USER_NAME, 
                        ".$this->db->IfNull('MD.MODIFIED_DATE', 'MD.CREATED_DATE')." AS MODIFIED_DATE, 
                        HDR.CREATED_DATE, 
                        NULL AS SRC_RECORD_ID
                    FROM META_BUG_FIXING HDR 
                        INNER JOIN META_BUG_FIXING_DTL DTL ON DTL.META_BUG_FIXING_ID = HDR.ID 
                        INNER JOIN META_WFM_FIELD MD ON MD.REF_STRUCTURE_ID = DTL.REF_STRUCTURE_ID 
                        LEFT JOIN UM_USER US ON US.USER_ID = MD.CREATED_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = US.SYSTEM_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UD ON UD.USER_ID = MD.MODIFIED_USER_ID  
                    WHERE HDR.ID IN ($ids)  

                    UNION 

                    SELECT 
                        'booktype' AS META_TYPE_ID, 
                        MD.BOOK_TYPE_ID AS META_DATA_ID, 
                        MD.BOOK_TYPE_CODE AS META_DATA_CODE, 
                        ".$this->db->IfNull('UD.USERNAME', 'UM.USERNAME')." AS USER_NAME, 
                        ".$this->db->IfNull('MD.MODIFIED_DATE', 'MD.CREATED_DATE')." AS MODIFIED_DATE, 
                        HDR.CREATED_DATE, 
                        NULL AS SRC_RECORD_ID 
                    FROM META_BUG_FIXING HDR 
                        INNER JOIN META_BUG_FIXING_DTL DTL ON DTL.META_BUG_FIXING_ID = HDR.ID 
                        INNER JOIN BOOK_TYPE MD ON MD.BOOK_TYPE_ID = DTL.BOOK_TYPE_ID 
                        LEFT JOIN UM_USER US ON US.USER_ID = MD.CREATED_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = US.SYSTEM_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UD ON UD.USER_ID = MD.MODIFIED_USER_ID  
                    WHERE HDR.ID IN ($ids)  
                        
                    UNION 

                    SELECT 
                        'config' AS META_TYPE_ID, 
                        MD.ID AS META_DATA_ID, 
                        MD.CODE AS META_DATA_CODE, 
                        NULL AS USER_NAME, 
                        NULL AS MODIFIED_DATE, 
                        HDR.CREATED_DATE, 
                        NULL AS SRC_RECORD_ID
                    FROM META_BUG_FIXING HDR 
                        INNER JOIN META_BUG_FIXING_DTL DTL ON DTL.META_BUG_FIXING_ID = HDR.ID 
                        INNER JOIN CONFIG MD ON MD.ID = DTL.CONFIG_ID 
                    WHERE HDR.ID IN ($ids) 
                    
                    UNION 

                    SELECT 
                        'ntf' AS META_TYPE_ID, 
                        MD.NOTIFICATION_ID AS META_DATA_ID, 
                        TO_CHAR(MD.NOTIFICATION_ID) AS META_DATA_CODE, 
                        ".$this->db->IfNull('UD.USERNAME', 'UM.USERNAME')." AS USER_NAME, 
                        ".$this->db->IfNull('MD.MODIFIED_DATE', 'MD.CREATED_DATE')." AS MODIFIED_DATE, 
                        HDR.CREATED_DATE, 
                        NULL AS SRC_RECORD_ID
                    FROM META_BUG_FIXING HDR 
                        INNER JOIN META_BUG_FIXING_DTL DTL ON DTL.META_BUG_FIXING_ID = HDR.ID 
                        INNER JOIN NTF_NOTIFICATION MD ON MD.NOTIFICATION_ID = DTL.NOTIFICATION_ID 
                        LEFT JOIN UM_USER US ON US.USER_ID = MD.CREATED_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = US.SYSTEM_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UD ON UD.USER_ID = MD.MODIFIED_USER_ID 
                    WHERE HDR.ID IN ($ids) 
                    
                    UNION 

                    SELECT 
                        'umobject' AS META_TYPE_ID, 
                        MD.OBJECT_ID AS META_DATA_ID, 
                        MD.CODE AS META_DATA_CODE, 
                        ".$this->db->IfNull('UD.USERNAME', 'UM.USERNAME')." AS USER_NAME, 
                        ".$this->db->IfNull('MD.MODIFIED_DATE', 'MD.CREATED_DATE')." AS MODIFIED_DATE, 
                        HDR.CREATED_DATE, 
                        NULL AS SRC_RECORD_ID
                    FROM META_BUG_FIXING HDR 
                        INNER JOIN META_BUG_FIXING_DTL DTL ON DTL.META_BUG_FIXING_ID = HDR.ID 
                        INNER JOIN UM_OBJECT MD ON MD.OBJECT_ID = DTL.OBJECT_ID 
                        LEFT JOIN UM_USER US ON US.USER_ID = MD.CREATED_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = US.SYSTEM_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UD ON UD.USER_ID = MD.MODIFIED_USER_ID 
                    WHERE HDR.ID IN ($ids) 
                        
                    UNION 

                    SELECT 
                        'impexcel' AS META_TYPE_ID, 
                        MD.ID AS META_DATA_ID, 
                        MD.CODE AS META_DATA_CODE, 
                        ".$this->db->IfNull('UD.USERNAME', 'UM.USERNAME')." AS USER_NAME, 
                        ".$this->db->IfNull('MD.MODIFIED_DATE', 'MD.CREATED_DATE')." AS MODIFIED_DATE, 
                        HDR.CREATED_DATE, 
                        NULL AS SRC_RECORD_ID
                    FROM META_BUG_FIXING HDR 
                        INNER JOIN META_BUG_FIXING_DTL DTL ON DTL.META_BUG_FIXING_ID = HDR.ID 
                        INNER JOIN IMP_EXCEL_TEMPLATE MD ON MD.ID = DTL.EXCEL_TEMPLATE_ID 
                        LEFT JOIN UM_USER US ON US.USER_ID = MD.CREATED_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = US.SYSTEM_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UD ON UD.USER_ID = MD.MODIFIED_USER_ID 
                    WHERE HDR.ID IN ($ids) 
                        
                    UNION 

                    SELECT 
                        'kpi' AS META_TYPE_ID, 
                        MD.ID AS META_DATA_ID, 
                        MD.CODE AS META_DATA_CODE, 
                        ".$this->db->IfNull('UD.USERNAME', 'UM.USERNAME')." AS USER_NAME, 
                        ".$this->db->IfNull('MD.MODIFIED_DATE', 'MD.CREATED_DATE')." AS MODIFIED_DATE, 
                        HDR.CREATED_DATE, 
                        NULL AS SRC_RECORD_ID
                    FROM META_BUG_FIXING HDR 
                        INNER JOIN META_BUG_FIXING_DTL DTL ON DTL.META_BUG_FIXING_ID = HDR.ID 
                        INNER JOIN KPI_TEMPLATE MD ON MD.ID = DTL.KPI_TEMPLATE_ID 
                        LEFT JOIN UM_USER US ON US.USER_ID = MD.CREATED_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = US.SYSTEM_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UD ON UD.USER_ID = MD.MODIFIED_USER_ID 
                    WHERE HDR.ID IN ($ids)  
                        
                    UNION 

                    SELECT 
                        'kpi' AS META_TYPE_ID, 
                        MD.ID AS META_DATA_ID, 
                        MD.CODE AS META_DATA_CODE, 
                        ".$this->db->IfNull('UD.USERNAME', 'UM.USERNAME')." AS USER_NAME, 
                        ".$this->db->IfNull('MD.MODIFIED_DATE', 'MD.CREATED_DATE')." AS MODIFIED_DATE, 
                        MD.CREATED_DATE, 
                        NULL AS SRC_RECORD_ID
                    FROM 
                        (
                            SELECT 
                                TRG_TEMPLATE_ID 
                            FROM KPI_TEMPLATE_MAP 
                            START WITH SRC_TEMPLATE_ID IN (
                                SELECT 
                                    KPI_TEMPLATE_ID 
                                FROM META_BUG_FIXING_DTL 
                                WHERE META_BUG_FIXING_ID IN ($ids) 
                                    AND KPI_TEMPLATE_ID IS NOT NULL 
                            ) 
                            CONNECT BY NOCYCLE SRC_TEMPLATE_ID = PRIOR TRG_TEMPLATE_ID 
                        ) KM 
                        INNER JOIN KPI_TEMPLATE MD ON MD.ID = KM.TRG_TEMPLATE_ID 
                        LEFT JOIN UM_USER US ON US.USER_ID = MD.CREATED_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = US.SYSTEM_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UD ON UD.USER_ID = MD.MODIFIED_USER_ID 
                        
                    UNION 
                    
                    SELECT 
                        TMP.META_TYPE_ID, 
                        TMP.META_DATA_ID, 
                        TMP.META_DATA_CODE, 
                        ".$this->db->IfNull('UD.USERNAME', 'UM.USERNAME')." AS USER_NAME, 
                        ".$this->db->IfNull('MD.MODIFIED_DATE', 'MD.CREATED_DATE')." AS MODIFIED_DATE, 
                        MD.CREATED_DATE, 
                        TMP.SRC_RECORD_ID 
                    FROM (
                            SELECT
                                ID AS META_DATA_ID, 
                                'kpiindicator' AS META_TYPE_ID, 
                                CODE AS META_DATA_CODE, 
                                NULL AS SRC_RECORD_ID 
                            FROM KPI_INDICATOR
                            START WITH 
                                ID IN (
                                    SELECT 
                                        INDICATOR_ID 
                                    FROM META_BUG_FIXING_DTL 
                                    WHERE META_BUG_FIXING_ID IN ($ids) 
                                        AND INDICATOR_ID IS NOT NULL
                                ) 
                            CONNECT BY NOCYCLE PRIOR PARENT_ID = ID 

                            UNION 

                            SELECT
                                ID AS META_DATA_ID, 
                                'kpiindicator' AS META_TYPE_ID, 
                                CODE AS META_DATA_CODE, 
                                NULL AS SRC_RECORD_ID 
                            FROM KPI_INDICATOR
                                START WITH ID IN (
                                    SELECT 
                                        CATEGORY_ID 
                                    FROM KPI_INDICATOR_CATEGORY
                                    WHERE INDICATOR_ID IN (
                                        SELECT 
                                            INDICATOR_ID 
                                        FROM META_BUG_FIXING_DTL 
                                        WHERE META_BUG_FIXING_ID IN ($ids) 
                                            AND INDICATOR_ID IS NOT NULL
                                    )  
                                )
                                CONNECT BY NOCYCLE PRIOR PARENT_ID = ID 

                            UNION 

                            SELECT 
                                KI.ID AS META_DATA_ID, 
                                'kpiindicator' AS META_TYPE_ID, 
                                KI.CODE AS META_DATA_CODE, 
                                NULL AS SRC_RECORD_ID 
                            FROM KPI_INDICATOR_INDICATOR_MAP M 
                                INNER JOIN KPI_INDICATOR KI ON M.TRG_INDICATOR_ID = KI.ID 
                            WHERE M.SEMANTIC_TYPE_ID = 10000009 
                                AND M.SRC_INDICATOR_ID IN ( 
                                    SELECT 
                                        INDICATOR_ID 
                                    FROM META_BUG_FIXING_DTL 
                                    WHERE META_BUG_FIXING_ID IN ($ids) 
                                        AND INDICATOR_ID IS NOT NULL
                                )  
                            
                            UNION 
                            
                            SELECT 
                                T2.ID AS META_DATA_ID, 
                                'kpiindicatorbydata' AS META_TYPE_ID, 
                                T2.CODE AS META_DATA_CODE, 
                                ".$this->db->listAgg('T0.ID', ',', 'T0.ID')." AS SRC_RECORD_ID 
                            FROM KPI_INDICATOR T0 
                                INNER JOIN KPI_TYPE T1 ON T1.ID = T0.KPI_TYPE_ID 
                                INNER JOIN KPI_INDICATOR T2 ON T2.ID = T1.RELATED_INDICATOR_ID 
                            WHERE T0.ID IN ( 
                                SELECT 
                                    INDICATOR_ID 
                                FROM META_BUG_FIXING_DTL 
                                WHERE META_BUG_FIXING_ID IN ($ids) 
                                    AND INDICATOR_ID IS NOT NULL
                            )   
                            GROUP BY 
                                T2.ID, 
                                T2.CODE 
                        ) TMP 
                        INNER JOIN KPI_INDICATOR MD ON MD.ID = TMP.META_DATA_ID 
                        LEFT JOIN UM_USER US ON US.USER_ID = MD.CREATED_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = US.SYSTEM_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UD ON UD.USER_ID = MD.MODIFIED_USER_ID 
                    
                    UNION 
                    
                    SELECT 
                        TMP.META_TYPE_ID, 
                        TMP.META_DATA_ID, 
                        TMP.META_DATA_CODE, 
                        ".$this->db->IfNull('UD.USERNAME', 'UM.USERNAME')." AS USER_NAME, 
                        ".$this->db->IfNull('MD.MODIFIED_DATE', 'MD.CREATED_DATE')." AS MODIFIED_DATE, 
                        MD.CREATED_DATE, 
                        TMP.SRC_RECORD_ID 
                    FROM (
                            SELECT 
                                T0.ID AS META_DATA_ID, 
                                'kpiindicatorrelation' AS META_TYPE_ID, 
                                TO_CHAR(T0.ID) AS META_DATA_CODE, 
                                NULL AS SRC_RECORD_ID 
                            FROM KPI_INDICATOR_INDICATOR_MAP T0 
                                INNER JOIN KPI_INDICATOR T1 ON T1.ID = T0.SRC_INDICATOR_ID  
                            WHERE T0.SRC_INDICATOR_ID IN ( 
                                SELECT 
                                    INDICATOR_ID 
                                FROM META_BUG_FIXING_DTL 
                                WHERE META_BUG_FIXING_ID IN ($ids) 
                                    AND INDICATOR_ID IS NOT NULL
                            ) AND T0.SEMANTIC_TYPE_ID = 10000015 
                            GROUP BY 
                                T0.ID  
                        ) TMP 
                        INNER JOIN KPI_INDICATOR_INDICATOR_MAP MD ON MD.ID = TMP.META_DATA_ID 
                        LEFT JOIN UM_USER US ON US.USER_ID = MD.CREATED_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = US.SYSTEM_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UD ON UD.USER_ID = MD.MODIFIED_USER_ID 
                    
                    UNION 
                    
                    SELECT 
                        
                        TMP.META_TYPE_ID, 
                        TMP.META_DATA_ID, 
                        TMP.META_DATA_CODE, 
                        ".$this->db->IfNull('UD.USERNAME', 'UM.USERNAME')." AS USER_NAME, 
                        ".$this->db->IfNull('MD.MODIFIED_DATE', 'MD.CREATED_DATE')." AS MODIFIED_DATE, 
                        MD.CREATED_DATE, 
                        NULL AS SRC_RECORD_ID 
                        
                    FROM (
                            SELECT 
                                KT.ID AS META_DATA_ID, 
                                'kpitype' AS META_TYPE_ID, 
                                KT.CODE AS META_DATA_CODE 
                            FROM 
                                (
                                    SELECT 
                                        ID 
                                    FROM KPI_TYPE 
                                    START WITH ID IN ( 
                                        SELECT 
                                            KPI_TYPE_ID 
                                        FROM META_BUG_FIXING_DTL 
                                        WHERE META_BUG_FIXING_ID IN ($ids) 
                                            AND KPI_TYPE_ID IS NOT NULL 
                                    )   
                                    CONNECT BY NOCYCLE ID = PRIOR PARENT_ID
                                ) KM 
                                INNER JOIN KPI_TYPE KT ON KT.ID = KM.ID 

                            UNION 

                            SELECT 
                                KI.ID AS META_DATA_ID, 
                                'kpiindicator' AS META_TYPE_ID, 
                                KI.CODE AS META_DATA_CODE 
                            FROM (
                                SELECT 
                                    RELATED_INDICATOR_ID 
                                FROM KPI_TYPE 
                                START WITH ID IN ( 
                                    SELECT 
                                        KPI_TYPE_ID 
                                    FROM META_BUG_FIXING_DTL 
                                    WHERE META_BUG_FIXING_ID IN ($ids) 
                                        AND KPI_TYPE_ID IS NOT NULL 
                                )    
                                CONNECT BY NOCYCLE ID = PRIOR PARENT_ID 
                            ) KT 
                            INNER JOIN KPI_INDICATOR KI ON KI.ID = KT.RELATED_INDICATOR_ID 
                        ) TMP 
                        LEFT JOIN KPI_INDICATOR MD ON MD.ID = TMP.META_DATA_ID 
                        LEFT JOIN UM_USER US ON US.USER_ID = MD.CREATED_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = US.SYSTEM_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UD ON UD.USER_ID = MD.MODIFIED_USER_ID 
                    
                    UNION 
                    
                    SELECT 
                        'metawidget' AS META_TYPE_ID, 
                        MD.ID AS META_DATA_ID, 
                        MD.CODE AS META_DATA_CODE, 
                        NULL AS USER_NAME, 
                        NULL AS MODIFIED_DATE, 
                        HDR.CREATED_DATE, 
                        NULL AS SRC_RECORD_ID 
                    FROM META_BUG_FIXING HDR 
                        INNER JOIN META_BUG_FIXING_DTL DTL ON DTL.META_BUG_FIXING_ID = HDR.ID 
                        INNER JOIN META_WIDGET MD ON MD.ID = DTL.WIDGET_ID 
                    WHERE HDR.ID IN ($ids) 
                    
                    UNION 
                    
                    SELECT 
                        'processrule' AS META_TYPE_ID, 
                        MD.ID AS META_DATA_ID, 
                        TO_CHAR(MD.ID) AS META_DATA_CODE, 
                        ".$this->db->IfNull('UD.USERNAME', 'UM.USERNAME')." AS USER_NAME, 
                        ".$this->db->IfNull('MD.MODIFIED_DATE', 'MD.CREATED_DATE')." AS MODIFIED_DATE, 
                        HDR.CREATED_DATE, 
                        NULL AS SRC_RECORD_ID 
                    FROM META_BUG_FIXING HDR 
                        INNER JOIN META_BUG_FIXING_DTL DTL ON DTL.META_BUG_FIXING_ID = HDR.ID 
                        INNER JOIN META_PROCESS_RULE MD ON MD.ID = DTL.PROCESS_RULE_ID  
                        LEFT JOIN UM_USER US ON US.USER_ID = MD.CREATED_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = US.SYSTEM_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UD ON UD.USER_ID = MD.MODIFIED_USER_ID 
                    WHERE HDR.ID IN ($ids)  
                    
                    UNION 
                    
                    SELECT 
                        'globedictionary' AS META_TYPE_ID, 
                        MD.ID AS META_DATA_ID, 
                        MD.CODE AS META_DATA_CODE, 
                        ".$this->db->IfNull('UD.USERNAME', 'UM.USERNAME')." AS USER_NAME, 
                        ".$this->db->IfNull('MD.MODIFIED_DATE', 'MD.CREATED_DATE')." AS MODIFIED_DATE, 
                        HDR.CREATED_DATE, 
                        NULL AS SRC_RECORD_ID 
                    FROM META_BUG_FIXING HDR 
                        INNER JOIN META_BUG_FIXING_DTL DTL ON DTL.META_BUG_FIXING_ID = HDR.ID 
                        INNER JOIN GLOBE_DICTIONARY MD ON MD.ID = DTL.GLOBE_ID   
                        LEFT JOIN UM_USER US ON US.USER_ID = MD.CREATED_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = US.SYSTEM_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UD ON UD.USER_ID = MD.MODIFIED_USER_ID 
                    WHERE HDR.ID IN ($ids)  
                        
                ) TMP 
                GROUP BY 
                    TMP.META_TYPE_ID, 
                    TMP.META_DATA_ID, 
                    TMP.META_DATA_CODE, 
                    TMP.USER_NAME, 
                    TMP.MODIFIED_DATE, 
                    TMP.CREATED_DATE, 
                    TMP.SRC_RECORD_ID 
                ORDER BY 
                    TMP.META_TYPE_ID DESC, 
                    TMP.CREATED_DATE ASC");
            
            $scriptXml = $metaXml = null;
            
            if ($scripts) {

                foreach ($scripts as $scrpt) {

                    $scriptXml .= $scrpt['SCRIPT'] . Mdcommon::$separator . "\n";
                }
            }
            
            if ($metas) {
                
                $isUseMetaUserId = Config::getFromCache('IS_USE_META_CREATED_MODIFIED_USER_ID');
            
                if ($isUseMetaUserId == '1') {
                    self::$exportIgnoreColumns = ['EXPORT_SCRIPT', 'COPY_COUNT', 'COMPANY_DEPARTMENT_ID'];
                }

                foreach ($metas as $meta) {
                    
                    if (is_numeric($meta['META_TYPE_ID'])) {
                        
                        self::$ignoreDeleteScript = false;
                        $metaResult = self::oneMetaModel($meta);

                        if ($metaResult['status'] == 'success') {
                            $metaXml .= $metaResult['result'];
                        }
                    
                    } else {
                        
                        if ($meta['META_TYPE_ID'] == 'impexcel' 
                                || $meta['META_TYPE_ID'] == 'kpi' 
                                || $meta['META_TYPE_ID'] == 'kpiindicator' 
                                || $meta['META_TYPE_ID'] == 'kpiindicatorbydata' 
                                || $meta['META_TYPE_ID'] == 'kpiindicatorrelation' 
                                || $meta['META_TYPE_ID'] == 'kpitype' 
                                || $meta['META_TYPE_ID'] == 'metawidget' 
                                || $meta['META_TYPE_ID'] == 'processrule' 
                                || $meta['META_TYPE_ID'] == 'globedictionary') {
                            
                            self::$ignoreDeleteScript = false;
                        } else {
                            self::$ignoreDeleteScript = true;
                        }
                        
                        self::$isKpiDbSchemaNameReplace = false;
                        self::$isCreateTable = false;
                        self::$isInsertData = true;
                        self::$insertDataFilter = null;

                        if ($meta['META_TYPE_ID'] == 'kpiindicatorbydata') { 

                            self::$isCreateTable = true;
                            self::$isInsertData = false;
                            self::$insertDataFilter = 'SRC_RECORD_ID='.$meta['SRC_RECORD_ID'];

                            $meta['META_TYPE_ID'] = 'kpiindicator';
                        } 
                        
                        if ($meta['META_TYPE_ID'] == 'kpiindicatorrelation') { 
                            self::$isKpiDbSchemaNameReplace = true;
                        }
                        
                        $objectResult = self::oneObjectModel($meta['META_DATA_ID'], $meta['META_TYPE_ID'], $meta['META_DATA_CODE']);

                        if ($objectResult['status'] == 'success') {
                            
                            $metaXml .= $objectResult['result'];
                        }
                    }
                }
            }
            
            if ($metaXml || $scriptXml) {

                $xml = self::upgradeXmlHeader();
                
                if ($scriptXml) {
                    $xml .= '<scripts>' . "\n";
                        $xml .= '<![CDATA[' . "\n";
                            $xml .= $scriptXml;
                        $xml .= ']]>' . "\n";
                    $xml .= '</scripts>' . "\n";
                }
                
                if ($metaXml) {
                    $xml .= '<metas>' . "\n";
                        $xml .= $metaXml;
                    $xml .= '</metas>' . "\n";
                }
                
                $xml .= self::upgradeXmlFooter();

                $script = Compression::gzdeflate($xml);

                $result = ['status' => 'success', 'result' => $script];
                
            } else {
                $result = ['status' => 'error', 'message' => 'Татах өгөгдөл олдсонгүй!'];
            }
            
        } else {
            $result = ['status' => 'error', 'message' => 'Invalid ids!'];
        }
        
        return $result;
    }
    
    public function downloadObjectModel($objectCode, $ids) {
        
        $selectedRows = array();
        $idsArr = explode(',', $ids);
        
        foreach ($idsArr as $id) {
            $selectedRows[] = array('id' => $id);
        }
        
        $_POST['id'] = 'id';
        $_POST['objectCode'] = $objectCode;
        $_POST['selectedRows'] = $selectedRows;
        
        self::$ignoreDeleteScript = false;
        $export = self::exportObjectModel();

        if ($export['status'] == 'success') {
            $result = array('status' => 'success', 'result' => $export['result']);
        } else {
            $result = array('status' => 'error', 'message' => $export['message']);
        }
        
        return $result;
    }
    
    public function exportMetaModel() {
        
        includeLib('Compress/Compression');
        $this->load->model('mdmetadata', 'middleware/models/');
        
        $metaId = Input::post('metaId');
        
        if (is_array($metaId)) { /* Олон үзүүлэлт */
            
            $metaIds = Arr::implode_r(',', $metaId, true);
            
            if (count($metaId) == 1) {
                $isOneMeta = true;
                $metaId = $metaId[0];
            }
            
        } else { /* Нэг үзүүлэлт */
            
            $metaIds = $metaId;
            $isOneMeta = true;
        }
        
        $metas = $this->db->GetAll("
            SELECT 
                TMP.*
            FROM (    
                SELECT 
                    TT.META_TYPE_ID, 
                    TT.META_DATA_ID, 
                    TT.META_DATA_CODE, 
                    ".$this->db->IfNull('UD.USERNAME', 'UM.USERNAME')." AS USER_NAME, 
                    ".$this->db->IfNull('TT.MODIFIED_DATE', 'TT.CREATED_DATE')." AS MODIFIED_DATE 
                FROM (
                        SELECT 
                            META_DATA_ID, 
                            META_TYPE_ID, 
                            META_DATA_CODE, 
                            CREATED_USER_ID, 
                            MODIFIED_USER_ID, 
                            CREATED_DATE, 
                            MODIFIED_DATE 
                        FROM META_DATA 
                        WHERE META_DATA_ID IN ($metaIds) 

                        UNION  

                        SELECT 
                            MD.META_DATA_ID, 
                            MD.META_TYPE_ID, 
                            MD.META_DATA_CODE, 
                            MD.CREATED_USER_ID, 
                            MD.MODIFIED_USER_ID, 
                            MD.CREATED_DATE, 
                            MD.MODIFIED_DATE 
                        FROM META_PROCESS_RULE PR 
                            INNER JOIN META_BUSINESS_PROCESS_LINK BP ON BP.META_DATA_ID = PR.RULE_PROCESS_ID 
                            INNER JOIN META_DATA MD ON MD.META_DATA_ID = BP.META_DATA_ID 
                        WHERE PR.MAIN_PROCESS_ID IN ($metaIds) 
                    ) TT 
                    LEFT JOIN UM_USER US ON US.USER_ID = TT.CREATED_USER_ID 
                    LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = US.SYSTEM_USER_ID 
                    LEFT JOIN UM_SYSTEM_USER UD ON UD.USER_ID = TT.MODIFIED_USER_ID 
            ) TMP 
            GROUP BY 
                TMP.META_TYPE_ID, 
                TMP.META_DATA_ID, 
                TMP.META_DATA_CODE, 
                TMP.USER_NAME, 
                TMP.MODIFIED_DATE" 
        );
        
        if ($metas) {
            
            $isUseMetaUserId = Config::getFromCache('IS_USE_META_CREATED_MODIFIED_USER_ID');
            
            if ($isUseMetaUserId == '1') {
                self::$exportIgnoreColumns = ['EXPORT_SCRIPT', 'COPY_COUNT', 'COMPANY_DEPARTMENT_ID'];
            }
            
            $metaXml = null;
            
            foreach ($metas as $meta) {

                $metaResult = self::oneMetaModel($meta);

                if ($metaResult['status'] == 'success') {
                    $metaXml .= $metaResult['result'];
                }
            }

            if ($metaXml) {

                $xml = self::upgradeXmlHeader();

                $xml .= '<metas>' . "\n";
                    $xml .= $metaXml;
                $xml .= '</metas>' . "\n";

                $xml .= self::upgradeXmlFooter();

                $script = Compression::gzdeflate($xml);

                $result = array('status' => 'success', 'result' => $script);
                
                if (isset($isOneMeta)) {
                    $result['metaId'] = $metaId;
                }
            }
        }
        
        return isset($result) ? $result : ['status' => 'error', 'message' => 'Татах өгөгдөл олдсонгүй!'];
    }
    
    public function upgradeXmlHeader() {
        
        $domainUrl    = URL;
        $unitName     = Session::unitName();
        $dbUserName   = $unitName ? $unitName : DB_USER;
        $currentDate  = Date::currentDate();
        $userName     = Ue::getSessionUserName();
            
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<documents domainUrl="'.$domainUrl.'" dbDriver="'.DB_DRIVER.'" dbUserName="'.$dbUserName.'" exportedDate="'.$currentDate.'" sessionUserName="'.$userName.'">' . "\n";
        
        return $xml;
    }
    
    public function upgradeXmlFooter() {
        
        $xml = '</documents>';
        
        return $xml;
    }
    
    public function oneMetaModel($metaRow) {
        
        $metaTypeId = $metaRow['META_TYPE_ID']; 
        $metaId     = $metaRow['META_DATA_ID']; 
        
        if (!isset(self::$exportedMetaIds[$metaTypeId][$metaId])) {
            
            self::$clobColumns = array();
            self::$blobColumns = array();
            self::$fileColumns = array();
            self::$deleteIds = array();
            self::$exportedRecordIds = array();
            self::$exportCreateTables = array();
            self::$childCreateTable = array();
            self::$exportedMetaIds[$metaTypeId][$metaId] = 1;
            
            $metaCode     = $metaRow['META_DATA_CODE'];
            $userName     = $metaRow['USER_NAME'];
            $modifiedDate = $metaRow['MODIFIED_DATE'];
            
            $separator         = Mdcommon::$separator;
            $metaTableRelation = self::metaTableRelation();

            try {
                
                $xmlScript = '';
                
                if (isset($metaTableRelation[$metaTypeId])) {
                    
                    $script    = '';
                    $relations = $metaTableRelation[$metaTypeId];

                    foreach ($relations as $tblName => $rel) {

                        $script .= self::generateDeleteInsertQuery($tblName, 'META_DATA_ID='.$metaId, $separator);

                        foreach ($rel as $relRow) {

                            $relTableName = $relRow['table'];
                            $relTrg       = $relRow['link'][0]['trg'];
                            $child        = issetParamArray($relRow['child']);

                            self::$fileColumn = issetParamArray($relRow['file']);

                            $script .= self::generateDeleteInsertQuery($relTableName, $relTrg.'='.$metaId, $separator, $child);
                        } 
                    } 

                    $deleteScript = self::generateDeleteScript($separator);
                    
                    if (self::$isMetaImportCopy) {
                        self::$deleteScript = $deleteScript;
                    } else {
                        $script = $deleteScript . $script;
                    }
                    
                    $xmlScript = '<meta id="'.$metaId.'" typeId="'.$metaTypeId.'" code="'.$metaCode.'" userName="'.$userName.'" modifiedDate="'.$modifiedDate.'">' . "\n";
                    
                    $xmlScript .= '<scripts>' . "\n";
                            $xmlScript .= '<![CDATA[' . "\n";
                                $xmlScript .= $script;
                            $xmlScript .= ']]>' . "\n";
                        $xmlScript .= '</scripts>' . "\n";
                        
                    $xmlScript .= self::clobBlobAppendXml();
                    $xmlScript .= self::fileAppendXml();
                    $xmlScript .= self::translateAppendXml($metaId, $metaTypeId);
                    
                    $xmlScript .= '</meta>' . "\n";
                }

                $result = array('status' => 'success', 'result' => $xmlScript);

            } catch (Exception $ex) {
                $result = array('status' => 'error', 'message' => $ex->getMessage());
            }
            
        } else {
            $result = array('status' => 'success', 'result' => '');
        }
        
        return $result;
    }
    
    public function oneObjectModel($metaId, $metaTypeId, $metaCode) {
        
        if (!isset(self::$exportedMetaIds[$metaTypeId][$metaId])) {
            
            self::$clobColumns = array();
            self::$blobColumns = array();
            self::$fileColumns = array();
            self::$deleteIds = array();
            self::$exportedRecordIds = array();
            self::$exportCreateTables = array();
            self::$childCreateTable = array();
            self::$exportedMetaIds[$metaTypeId][$metaId] = 1;
            
            $separator           = Mdcommon::$separator;
            $objectTableRelation = self::objectTableRelation();

            try {
                
                $xmlScript = '';
                
                if (isset($objectTableRelation[$metaTypeId])) {
                    
                    $script    = '';
                    $relations = $objectTableRelation[$metaTypeId];
                    
                    if (Input::isEmpty('dataTableName') == false) {
                        $dataTableNames = Input::post('dataTableName');
                    }

                    foreach ($relations as $tblName => $rel) {
                        
                        self::$fileColumn = self::tableFileColumns($tblName);
                        
                        if (isset($dataTableNames) && is_array($dataTableNames)) {
                                
                            foreach ($dataTableNames as $dataTableName) {

                                if ($dataTableName == $tblName && $tblName == 'KPI_INDICATOR' && self::$exportRecordId == $metaId) {

                                    self::$childCreateTable = array(
                                        array(
                                            'isCreateTable'   => true, 
                                            'isInsertData'    => true, 
                                            'isDataCheck'     => true,
                                            'tableColumnName' => 'TABLE_NAME'
                                        )
                                    );
                                }
                            }
                            
                        } elseif ($dynamicCreateTable = self::dynamicCreateTables($tblName)) {
                            
                            self::$childCreateTable = $dynamicCreateTable;
                        }
                        
                        $script .= self::generateDeleteInsertQuery($tblName, self::tablePrimaryField($tblName).'='.$metaId, $separator);
                    
                        foreach ($rel as $relRow) {

                            $relTableName = $relRow['table'];
                            $relTrg       = $relRow['link'][0]['trg'];
                            $child        = issetParamArray($relRow['child']);

                            self::$fileColumn = issetParamArray($relRow['file']);
                            
                            if ($childCreateTable = issetParamArray($relRow['childCreateTable'])) {
                                self::$childCreateTable = array_merge(self::$childCreateTable, $childCreateTable);
                            }
                            
                            $script .= self::generateDeleteInsertQuery($relTableName, $relTrg.'='.$metaId, $separator, $child);
                        } 
                    } 

                    $deleteScript      = self::generateObjectDeleteScript($separator); 
                    $createTableScript = self::exportCreateTableAppendXml();
                    $clobBlobScript    = self::clobBlobAppendXml();
                    
                    if (self::$exportCreateTables || self::$isKpiDbSchemaNameReplace) {
                            
                        $kpiDbSchemaName   = Config::getFromCache('kpiDbSchemaName');
                        
                        $script            = str_replace(", '$kpiDbSchemaName.", ", '[kpiDbSchemaName].", $script);
                        $script            = str_replace("INSERT INTO $kpiDbSchemaName.", 'INSERT INTO [kpiDbSchemaName].', $script);
                        $deleteScript      = str_replace("DELETE FROM $kpiDbSchemaName.", 'DELETE FROM [kpiDbSchemaName].', $deleteScript);
                        $createTableScript = str_replace('tblName="'.$kpiDbSchemaName.'.', 'tblName="[kpiDbSchemaName].', $createTableScript);
                        $clobBlobScript    = str_replace('tblName="'.$kpiDbSchemaName.'.', 'tblName="[kpiDbSchemaName].', $clobBlobScript);
                    }
                        
                    $script = $deleteScript . $script;
                    
                    $xmlScript = '<meta id="'.$metaId.'" typeId="'.$metaTypeId.'" code="'.$metaCode.'" skipError="'.(self::$ignoreDeleteScript ? 1 : 0).'">' . "\n";
                        
                        $xmlScript .= '<scripts>' . "\n";
                            $xmlScript .= '<![CDATA[' . "\n";
                                $xmlScript .= $script;
                            $xmlScript .= ']]>' . "\n";
                        $xmlScript .= '</scripts>' . "\n";
                        
                        $xmlScript .= $createTableScript;
        
                        $xmlScript .= $clobBlobScript;
                        $xmlScript .= self::fileAppendXml();
                        
                    
                    $xmlScript .= '</meta>' . "\n";
                }

                $result = array('status' => 'success', 'result' => $xmlScript);

            } catch (Exception $ex) {
                $result = array('status' => 'error', 'message' => $ex->getMessage());
            }
            
        } else {
            $result = array('status' => 'success', 'result' => '');
        }
        
        return $result;
    }
    
    public function exportCreateTableAppendXml() {
        
        $xml = null;
        
        if (self::$isCreateRollback == false && self::$exportCreateTables) {
            
            $kpiDbSchemaName = Config::getFromCache('kpiDbSchemaName');
            $srcRecordIdPh = $this->db->Param(0);
            $rowXml = null;
            
            foreach (self::$exportCreateTables as $row) {
                
                $createTableName = $row['tableName'];
                
                if (self::$isInsertData && !$row['isDataCheck'] && $row['mainTableName'] == 'KPI_INDICATOR' && $row['columnName'] == 'TABLE_NAME' && $row['recordId']) {
                    
                    $isDefaultDataset = $this->db->GetOne("SELECT IS_DEFAULT_DATASET FROM V_DATA_SET WHERE SRC_RECORD_ID = $srcRecordIdPh", array($row['recordId']));
                    
                    if (!$isDefaultDataset) {
                        continue;
                    }
                }
                
                try {
                    
                    if ($columns = $this->db->MetaColumns($createTableName)) {
                        
                        $tmpCreateTableName = $createTableName;
                        $createTableName    = str_replace($kpiDbSchemaName . '.', '', $createTableName);
                        $dbTableName        = "[kpiDbSchemaName].$createTableName";
                        
                        $fields = $createColumns = '';

                        foreach ($columns as $column) {

                            $name          = strtoupper($column->name);
                            $max_length    = $column->max_length;
                            $type          = $column->type;
                            $scale         = $column->scale;
                            $not_null      = $column->not_null ? ' NOT NULL ENABLE' : '';
                            $default_value = $column->default_value;
                            
                            if ($default_value != '') {
                                $default_value = " DEFAULT '$default_value'";
                            }

                            if ($type == 'INT' || $type == 'NUMBER') {
                                
                                $fields .= "$name NUMBER($max_length, $scale)".$default_value."".$not_null.", ";
                                $createColumns .= "ALTER TABLE $dbTableName ADD ($name NUMBER($max_length, $scale))". Mdcommon::$separator . "\n";
                                
                            } elseif ($type == 'VARCHAR2') {
                                
                                $fields .= "$name VARCHAR2($max_length CHAR)$not_null, ";
                                $createColumns .= "ALTER TABLE $dbTableName ADD ($name VARCHAR2($max_length CHAR))". Mdcommon::$separator . "\n";
                                
                            } elseif ($type == 'DATE') {
                                
                                $fields .= "$name DATE$not_null, ";
                                $createColumns .= "ALTER TABLE $dbTableName ADD ($name DATE)". Mdcommon::$separator . "\n";
                                
                            } elseif ($type == 'CLOB') {
                                
                                $fields .= "$name CLOB$not_null, ";
                                $createColumns .= "ALTER TABLE $dbTableName ADD ($name CLOB)". Mdcommon::$separator . "\n";
                            }
                        }

                        $fields             = rtrim(trim($fields), ',');
                        $createTableScripts = "CREATE TABLE $dbTableName ($fields)". Mdcommon::$separator . "\n";

                        $tableOwner = $deleteScripts = $indexScripts = '';

                        if (strpos($tmpCreateTableName, '.') !== false) {
                            $tmpCreateTableNameArr = explode('.', $tmpCreateTableName);
                            $tableOwner = $tmpCreateTableNameArr[0];
                        }

                        $indexes = $this->db->MetaIndexes($createTableName, true, $tableOwner);

                        if ($indexes) {
                            
                            if (DB_DRIVER == 'oci8') {
                                
                                $primaryKeys = $this->db->MetaPrimaryKeys($createTableName, $tableOwner);
                                
                            } elseif (DB_DRIVER == 'postgres9') {
                                
                                $primaryKeys = [];
                                $rs = $this->db->MetaColumns('public.' . $createTableName);

                                if (is_array($rs)) {
                                    $fieldObjs = self::postgreArrayColumnsConvert($rs);
                                } else {
                                    $fieldObjs = self::postgreSqlColumnsConvert($rs->sql);
                                }

                                $keyRow = $this->db->GetRow(sprintf($this->db->metaKeySQL1, strtolower($createTableName)));

                                if (isset($keyRow['COLUMN_NAME'])) {
                                    $primaryKeys[0] = $keyRow['COLUMN_NAME'];
                                }
                            }

                            global $ADODB_FETCH_MODE;

                            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

                            foreach ($indexes as $indexName => $indexRow) {

                                if ($primaryKeys && isset($indexRow['columns'][0]) == $primaryKeys[0]) {

                                    $indexScripts .= "ALTER TABLE $dbTableName ADD CONSTRAINT $indexName PRIMARY KEY (ID) ENABLE" . Mdcommon::$separator . "\n";

                                } else {

                                    $unique  = $indexRow['unique'] ? 'UNIQUE ' : '';
                                    $columns = Arr::implode_r(',', $indexRow['columns'], true);

                                    $indexScripts .= "CREATE ".$unique."INDEX [kpiDbSchemaName].$indexName ON $dbTableName ($columns)" . Mdcommon::$separator . "\n";
                                }
                            }
                        }
                        
                        if (self::$isInsertData) {
                            $insertScripts = self::generateInsertQuery($tmpCreateTableName, '1=1', Mdcommon::$separator);
                        } elseif (self::$insertDataFilter) {
                            $insertScripts = self::generateInsertQuery($tmpCreateTableName, self::$insertDataFilter, Mdcommon::$separator);
                        }
                        
                        $insertScripts = str_replace('INTO '.$kpiDbSchemaName.'.', 'INTO [kpiDbSchemaName].', $insertScripts);
                        $insertScripts = str_replace('INTO '.$createTableName.' (', 'INTO [kpiDbSchemaName].'.$createTableName.' (', $insertScripts);
                        $columnName    = $row['columnName'];

                        if ($columnName == 'TEMPLATE_TABLE_NAME') {
                            $deleteScripts = "DELETE FROM $dbTableName" . Mdcommon::$separator . "\n";
                        }
                        
                        if (self::$clobColumns) {
                            foreach (self::$clobColumns as $c => $clobTbl) {
                                $clobTblName = $clobTbl['tblName'];
                                
                                if ($clobTblName == $createTableName) {
                                    self::$clobColumns[$c]['tblName'] = '[kpiDbSchemaName].'.$clobTblName;
                                }
                            }
                        }

                        $rowXml .= '<createTable tblName="'.$createTableName.'" skipError="1">' . "\n";
                            $rowXml .= '<![CDATA[' . "\n";
                                $rowXml .= $createTableScripts;
                                $rowXml .= $deleteScripts;
                                $rowXml .= $createColumns;
                                $rowXml .= $indexScripts;
                                $rowXml .= $insertScripts;
                            $rowXml .= ']]>' . "\n";
                        $rowXml .= '</createTable>' . "\n";
                    }
                    
                } catch (Exception $ex) { }
            }
            
            if ($rowXml) {
                $xml = '<createTables>' . "\n";
                    $xml .= $rowXml;
                $xml .= '</createTables>' . "\n";
            }
        }
        
        return $xml;
    }
    
    public function generateCreateInsertQuery($createTableName, $separator) {
        
        try {
            
            $fields = '';
            $columns = $this->db->MetaColumns($createTableName);
            
            foreach ($columns as $column) {
                
                $name          = strtoupper($column->name);
                $max_length    = $column->max_length;
                $type          = $column->type;
                $scale         = $column->scale;
                $not_null      = $column->not_null;
                $default_value = $column->default_value;
                
                $not_null      = $not_null ? ' NOT NULL ENABLE' : '';
                
                if ($type == 'INT' || $type == 'NUMBER') {
                    $fields .= "$name NUMBER($max_length, $scale)$not_null, ";
                } elseif ($type == 'VARCHAR2') {
                    $fields .= "$name VARCHAR2($max_length CHAR)$not_null, ";
                } elseif ($type == 'DATE') {
                    $fields .= "$name DATE$not_null, ";
                } 
            }
            
            $scripts = "CREATE TABLE $createTableName ()". $separator;
            
            $result = array('status' => 'success');
            
        } catch (Exception $ex) {
            $result = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $result;
    }
    
    public function generateObjectDeleteScript($separator) {
        
        $deleteScript = null;
        
        if (self::$ignoreDeleteScript) {
            return $deleteScript;
        }
        
        if (self::$deleteIds) {
            
            $deleteIds = array_reverse(self::$deleteIds);
            
            foreach ($deleteIds as $tableName => $columns) {
                
                if (in_array($tableName, self::$ignoreDeleteScriptTables)) {
                    continue;
                }
                
                foreach ($columns as $columnName => $ids) {
                    
                    $where = '';
                    
                    if (is_array($ids)) {
                        
                        $idsSplit = array_chunk($ids, 500); 
                            
                        foreach ($idsSplit as $idsArr) {
                            $where .= " $columnName IN (" . implode(',', $idsArr) . ") OR";
                        }

                        $where = rtrim($where, ' OR');
                        
                    } else {
                        
                        parse_str($ids, $idsArr);
                        
                        foreach ($idsArr as $columnName => $val) {
                            if ($val != '') {
                                $where .= " $columnName = $val AND";
                            } else {
                                $where .= " $columnName IS NULL AND";
                            }
                        }
                        
                        $where = mb_substr($where, 0, -4);
                    }
                    
                    $deleteScript .= "DELETE FROM $tableName WHERE$where" . $separator . "\n";
                }
            }
        }
        
        return $deleteScript;
    }
    
    public function generateDeleteScript($separator) {
        
        $deleteScript = null;
        
        if (self::$deleteIds) {
            
            foreach (self::$deleteIds as $tableName => $columns) {
                
                foreach ($columns as $columnName => $ids) {
                    
                    $where = '';
                    
                    if (is_array($ids)) {
                        
                        $idsSplit = array_chunk($ids, 500); 
                            
                        foreach ($idsSplit as $idsArr) {
                            $where .= " $columnName IN (" . implode(',', $idsArr) . ") OR";
                        }

                        $where = rtrim($where, ' OR');
                        
                    } else {
                        
                        parse_str($ids, $idsArr);
                        
                        foreach ($idsArr as $columnName => $val) {
                            if ($val != '') {
                                $where .= " $columnName = $val AND";
                            } else {
                                $where .= " $columnName IS NULL AND";
                            }
                        }
                        
                        $where = mb_substr($where, 0, -4);
                    }
                    
                    $deleteScript .= "DELETE FROM $tableName WHERE$where" . $separator . "\n";
                }
            }
        }
        
        return $deleteScript;
    }
    
    public function generateDeleteInsertQuery($tblName, $recordId, $separator, $child = array()) {
        
        $script = null;
        
        if (self::$isIgnoreMetaFolder && $tblName == 'META_DATA_FOLDER_MAP') {    
            return $script;
        }
        
        if (!isset(self::$deleteIds[$tblName][$recordId]) && $recordId) {
            
            if (!in_array($tblName, self::$ignoreDeleteScriptTables)) {
                self::$deleteIds[$tblName][$recordId] = $recordId;
            }
        
            $insert = self::generateInsertQuery($tblName, $recordId, $separator, array(), $child);

            if ($insert) {

                $script .= $insert;

                if ($tblName == 'META_DATA_FOLDER_MAP') {
                    $script .= self::generateDeleteInsertFolderQuery(self::$metaFolderId);
                }
            }
        }
        
        return $script;
    }
    
    public function generateInsertQuery($tblName, $recordId, $separator, $data = array(), $childs = array()) {
         
        $sql = null;
        
        if (!$data && $recordId) {
            
            parse_str($recordId, $recordIdArr);
            $where = '';
            $bindParams = array();
            
            foreach ($recordIdArr as $fieldName => $fieldVal) {
                
                if (strpos($fieldVal, ',') !== false) {
                    $where .= "$fieldName IN ($fieldVal) AND ";
                } else {
                    $where .= "$fieldName = ".$this->db->Param($fieldName).' AND ';
                    $bindParams = array($fieldName => $fieldVal) + $bindParams;
                }
            }

            $where = mb_substr($where, 0, -5);
            
            $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
            
            if ($bindParams) {
                $data = $this->db->GetAll("SELECT * FROM $tblName WHERE $where", $bindParams);
            } else {
                $data = $this->db->GetAll("SELECT * FROM $tblName WHERE $where");
            }
        }
        
        if ($data) {
            
            $fields        = self::getObjectFields($tblName);
            $primaryColumn = self::$executedTablesPrimaryColumn[$tblName];
            
            $kpiDbSchemaName = Config::getFromCache('kpiDbSchemaName');
            
            foreach ($data as $row) {
                
                $isExportRow = true;
                
                if ($tblName == 'META_PROCESS_PARAM_LINK' || $tblName == 'KPI_INDICATOR_MAP_CRITERIA' || $tblName == 'KPI_INDICATOR_INDICATOR_MAP') {
                    
                    if (isset(self::$exportedRecordIds[$tblName][$row[$primaryColumn]])) {
                        $isExportRow = false;
                    } else {
                        self::$exportedRecordIds[$tblName][$row[$primaryColumn]] = 1;
                    }
                }
                
                if ($isExportRow) {
                    
                    if (self::$childCreateTable) {

                        foreach (self::$childCreateTable as $tblRow) {

                            $tableColumnName = $tblRow['tableColumnName'];
                            $dataTableName   = issetParam($row[$tableColumnName]);

                            if ($dataTableName) {

                                if ($tblRow['isCreateTable']) {

                                    self::$exportCreateTables[strtolower($dataTableName)] = array(
                                        'mainTableName' => $tblName, 
                                        'columnName'    => $tableColumnName, 
                                        'tableName'     => $dataTableName, 
                                        'recordId'      => $row[$primaryColumn], 
                                        'isDataCheck'   => isset($tblRow['isDataCheck']) ? $tblRow['isDataCheck'] : false
                                    );
                                }

                                $row[$tableColumnName] = '[kpiDbSchemaName].' . str_replace($kpiDbSchemaName.'.', '', $dataTableName);
                            }
                        }
                    }

                    $columns = $values = '';
                    $sql     .= 'INSERT INTO ' . $tblName . ' (';

                    foreach ($fields as $col) {

                        if (!in_array($col['name'], self::$exportIgnoreColumns) && !isset(self::$exportIgnoreTableColumns[$tblName][$col['name']])) {

                            if ($col['type'] == 'INT' || $col['type'] == 'NUMBER') {

                                if ($row[$col['name']] != '') {

                                    $sId = $row[$col['name']];

                                    $columns .= $col['name'] . ', ';

                                    $values .= $sId . ', ';

                                    if (self::$isIdReplace && $primaryColumn == $col['name']) {
                                        array_push(self::$replaceIds, $sId);
                                    }
                                }

                            } elseif ($col['type'] == 'DATE') {

                                if ($row[$col['name']] != '') {

                                    $columns .= $col['name'] . ', ';

                                    $values .= $this->db->SQLDate('Y-m-d H:i:s', "'" . $row[$col['name']] . "'", 'TO_DATE') . ', ';
                                } 

                            } elseif ($col['type'] == 'CLOB') {

                                if ($row[$col['name']] != '') {

                                    self::$clobColumns[] = array(
                                        'tblName'    => $tblName, 
                                        'colName'    => $col['name'], 
                                        'equalField' => $primaryColumn, 
                                        'recordId'   => $row[$primaryColumn], 
                                        'content'    => $row[$col['name']]
                                    );  
                                }

                            } elseif ($col['type'] == 'BLOB') {

                                if ($row[$col['name']] != '') {

                                    self::$blobColumns[] = array(
                                        'tblName'    => $tblName, 
                                        'colName'    => $col['name'], 
                                        'equalField' => $primaryColumn, 
                                        'recordId'   => $row[$primaryColumn], 
                                        'content'    => $row[$col['name']]
                                    );  
                                } 

                            } else {

                                if (isset(self::$fileColumn[$col['name']])) {

                                    self::$fileColumns[] = array(
                                        'tblName'    => $tblName, 
                                        'colName'    => $col['name'], 
                                        'equalField' => $primaryColumn, 
                                        'recordId'   => $row[$primaryColumn], 
                                        'content'    => $row[$col['name']], 
                                        'fileType'   => self::$fileColumn[$col['name']]
                                    );
                                } 

                                if ($row[$col['name']] != '') {

                                    $columns .= $col['name'] . ', ';

                                    $values .= "'" . str_replace("'", "''", $row[$col['name']]) . "', ";
                                } 
                            }
                        }
                    }

                    $sql .= rtrim($columns, ', ') . ') VALUES (' . rtrim($values, ', ') . ')' . $separator . "\n";

                    if ($tblName == 'META_DATA_FOLDER_MAP' && $row['FOLDER_ID']) {

                        self::$metaFolderId = $row['FOLDER_ID'];
                    }
                }
                
                if ($childs) {
                    
                    foreach ($childs as $child) {
                        
                        $qryStr = '';
                        
                        foreach ($child['link'] as $link) { 
                            $qryStr .= $link['trg'].'='.$row[$link['src']].'&';
                        }
                        
                        $qryStr = rtrim($qryStr, '&');
                        
                        $sql .= self::generateDeleteInsertQuery($child['table'], $qryStr, $separator, issetParamArray($child['child']));
                    }
                }
            }
        }
        
        return $sql;
    }
    
    public function generateDeleteInsertFolderQuery($metaFolderId) {
        
        $script = '';
        
        if (!isset(self::$executedFolderIds[$metaFolderId])) {
            
            if (DB_DRIVER == 'oci8') {
                
                $data = $this->db->GetAll("
                    SELECT 
                        * 
                    FROM FVM_FOLDER 
                        CONNECT BY 
                        NOCYCLE 
                        PRIOR PARENT_FOLDER_ID = FOLDER_ID 
                        START WITH FOLDER_ID = ".$this->db->Param(0)."  
                    ORDER BY LEVEL DESC", 
                    array($metaFolderId)
                );
                
            } elseif (DB_DRIVER == 'postgres9') {
                
                $data = $this->db->GetAll("
                    WITH RECURSIVE TMP_FOLDER AS 
                    (
                        SELECT 
                            U1.*, 
                            1 AS LEVEL 
                        FROM FVM_FOLDER U1 
                        WHERE FOLDER_ID = ".$this->db->Param(0)."  

                        UNION ALL 

                        SELECT 
                            U2.*, 
                            TMP_FOLDER.LEVEL + 1 
                        FROM FVM_FOLDER U2 
                            JOIN TMP_FOLDER ON TMP_FOLDER.PARENT_FOLDER_ID = U2.FOLDER_ID 
                    ) SELECT * FROM TMP_FOLDER ORDER BY LEVEL DESC", 
                    array($metaFolderId)
                );
            }

            if ($data) {

                $separator = Mdcommon::$separator;

                foreach ($data as $row) {

                    $folderId = $row['FOLDER_ID'];
                    
                    if (!isset(self::$scriptFolderIds[$folderId])) {
                        
                        self::$deleteIds['FVM_FOLDER']['FOLDER_ID'][] = $folderId;

                        $script .= self::generateInsertQuery('FVM_FOLDER', 'FOLDER_ID='.$folderId, $separator, array($row));
                        
                        self::$scriptFolderIds[$folderId] = 1;
                    }
                }
            }
            
            self::$executedFolderIds[$metaFolderId] = 1;
        }
        
        return $script;
    }
    
    public function postgreSqlColumnsConvert($sql) {

        $data = $this->db->GetAll($sql);
        
        if ($data) {
            
            $arr = array();
            
            foreach ($data as $row) {
                
                $typeName = 'varchar';
                
                if ($row['TYPNAME'] == 'numeric') {
                    $typeName = 'NUMBER';
                } elseif ($row['TYPNAME'] == 'text' || $row['TYPNAME'] == 'clob') {
                    $typeName = 'CLOB';
                } elseif ($row['TYPNAME'] == 'timestamp') {
                    $typeName = 'DATE'; 
                }
                
                $arr[] = array(
                    'name'       => strtoupper($row['ATTNAME']), 
                    'max_length' => 4000, 
                    'type'       => $typeName, 
                    'scale'      => 1
                );
            }
            
            return $arr;
        }
        
        return null;
    }
    
    public function postgreArrayColumnsConvert($data) {

        $arr = [];
            
        foreach ($data as $row) {

            $typeName = 'varchar';

            if ($row->type == 'numeric') {
                $typeName = 'NUMBER';
            } elseif ($row->type == 'text' || $row->type == 'clob') {
                $typeName = 'CLOB';
            } elseif ($row->type == 'timestamp') {
                $typeName = 'DATE'; 
            }

            $arr[] = array(
                'name'       => strtoupper($row->name), 
                'max_length' => 4000, 
                'type'       => $typeName, 
                'scale'      => 1
            );
        }

        return $arr;
    }
    
    public function getObjectFields($objectName) {
        
        if (isset(self::$executedTables[$objectName])) {
            
            $fieldObjs = self::$executedTables[$objectName];
            
        } else {
            
            try {
                
                if (DB_DRIVER == 'oci8') {
                
                    $rs = $this->db->Execute("SELECT * FROM $objectName WHERE 1 = 0");
                    $fieldObjs = Arr::objectToArray($rs->_fieldobjs);

                    $getPrimaryColumn = $this->db->MetaPrimaryKeys($objectName);

                } elseif (DB_DRIVER == 'postgres9') {

                    $rs = $this->db->MetaColumns('public.' . $objectName);
                    
                    if (is_array($rs)) {
                        $fieldObjs = self::postgreArrayColumnsConvert($rs);
                    } else {
                        $fieldObjs = self::postgreSqlColumnsConvert($rs->sql);
                    }

                    $keyRow = $this->db->GetRow(sprintf($this->db->metaKeySQL1, strtolower($objectName)));

                    if (isset($keyRow['COLUMN_NAME'])) {
                        $getPrimaryColumn[0] = $keyRow['COLUMN_NAME'];
                    }
                }

                self::$executedTables[$objectName] = $fieldObjs;

                if (isset($getPrimaryColumn[0])) {

                    self::$executedTablesPrimaryColumn[$objectName] = strtoupper($getPrimaryColumn[0]);

                } else {

                    if ($objectName == 'META_DATA') {
                        self::$executedTablesPrimaryColumn[$objectName] = 'META_DATA_ID';
                    } else {
                        self::$executedTablesPrimaryColumn[$objectName] = 'ID';
                    }
                }
            
            } catch (Exception $ex) {
                
                $fieldObjs = array();
            }
        }
        
        return $fieldObjs;
    }
    
    public function clobBlobAppendXml() {
        
        $xml = null;
        
        if (!empty(self::$clobColumns) || !empty(self::$blobColumns)) {
            
            $xml .= '<cloblobs>' . "\n";
            
            if (!empty(self::$clobColumns)) {
                
                foreach (self::$clobColumns as $clobCol) {
                    $xml .= '<cloblob type="clob" tblName="'.$clobCol['tblName'].'" colName="'.$clobCol['colName'].'" equalField="'.$clobCol['equalField'].'" recordId="'.$clobCol['recordId'].'">' . "\n";
                        $xml .= '<![CDATA[' . "\n";
                            $xml .= $clobCol['content'] . "\n";
                        $xml .= ']]>' . "\n";
                    $xml .= '</cloblob>' . "\n";
                }
            }
            
            if (!empty(self::$blobColumns)) {
                
                foreach (self::$blobColumns as $blobCol) {
                    $xml .= '<cloblob type="blob" tblName="'.$blobCol['tblName'].'" colName="'.$blobCol['colName'].'" equalField="'.$blobCol['equalField'].'" recordId="'.$blobCol['recordId'].'">' . "\n";
                        $xml .= '<![CDATA[' . "\n";
                            $xml .= $blobCol['content'] . "\n";
                        $xml .= ']]>' . "\n";
                    $xml .= '</cloblob>' . "\n";
                }
            }
            
            $xml .= '</cloblobs>' . "\n";
        }
        
        return $xml;
    }
    
    public function fileAppendXml() {
        
        $xml = null;
        
        if (!empty(self::$fileColumns)) {
            
            $xml .= '<filecontents>' . "\n";
            
            foreach (self::$fileColumns as $fileCol) {
                
                if (file_exists($fileCol['content'])) {
                    
                    $fileType = $fileCol['fileType'];
                    
                    if ($fileType == 'imageFile') {
                        
                        $fileContent = base64_encode(file_get_contents($fileCol['content']));
                        
                    } elseif ($fileType == 'contentFile') {
                        
                        $fileContent = file_get_contents($fileCol['content']);
                    }
                    
                    $xml .= '<filecontent fileType="'.$fileType.'" fileUrl="'.$fileCol['content'].'">' . "\n";
                        $xml .= '<![CDATA[' . "\n";
                            $xml .= $fileContent . "\n";
                        $xml .= ']]>' . "\n";
                    $xml .= '</filecontent>' . "\n";
                }
            }
            
            $xml .= '</filecontents>' . "\n";
        }
        
        return $xml;
    }
    
    public function translateAppendXml($metaId, $metaTypeId) {
        
        $xml = null;
        
        if (self::$isIgnoreTranslate) {
            return $xml;
        }
        
        $data = array();
        $metaDataIdPh = $this->db->Param(0);
        
        if ($metaTypeId == Mdmetadata::$businessProcessMetaTypeId) {
            
            $sql = "
                SELECT 
                    GD.* 
                FROM ( 
                    SELECT 
                        GL.CODE, 
                        GL.TYPE_ID, 
                        GL.GROUP_ID, 
                        GL.MONGOLIAN, 
                        GL.ENGLISH, 
                        GL.RUSSIAN, 
                        GL.KOREAN, 
                        GL.CHINESE, 
                        GL.JAPANESE, 
                        GL.TURKISH, 
                        GL.GERMAN, 
                        GL.ID, 
                        GL.ARABIC, 
                        GL.META_DATA_ID    
                    FROM META_BUSINESS_PROCESS_LINK PL 
                        INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.PROCESS_NAME) 
                    WHERE PL.META_DATA_ID = $metaDataIdPh 
                    
                    UNION ALL 
                    
                    SELECT 
                        GL.CODE, 
                        GL.TYPE_ID, 
                        GL.GROUP_ID, 
                        GL.MONGOLIAN, 
                        GL.ENGLISH, 
                        GL.RUSSIAN, 
                        GL.KOREAN, 
                        GL.CHINESE, 
                        GL.JAPANESE, 
                        GL.TURKISH, 
                        GL.GERMAN, 
                        GL.ID, 
                        GL.ARABIC, 
                        GL.META_DATA_ID    
                    FROM META_PROCESS_PARAM_ATTR_LINK PL
                        INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.TAB_NAME) 
                    WHERE PL.PROCESS_META_DATA_ID = $metaDataIdPh 
                        AND PL.IS_INPUT = 1 
                        AND PL.IS_SHOW = 1 
                        AND PL.TAB_NAME IS NOT NULL    
                    
                    UNION ALL 
                    
                    SELECT 
                        GL.CODE, 
                        GL.TYPE_ID, 
                        GL.GROUP_ID, 
                        GL.MONGOLIAN, 
                        GL.ENGLISH, 
                        GL.RUSSIAN, 
                        GL.KOREAN, 
                        GL.CHINESE, 
                        GL.JAPANESE, 
                        GL.TURKISH, 
                        GL.GERMAN, 
                        GL.ID, 
                        GL.ARABIC, 
                        GL.META_DATA_ID   
                    FROM META_PROCESS_PARAM_ATTR_LINK PL 
                        INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.SIDEBAR_NAME) 
                    WHERE PL.PROCESS_META_DATA_ID = $metaDataIdPh 
                        AND PL.IS_INPUT = 1 
                        AND PL.IS_SHOW = 1 
                        AND PL.SIDEBAR_NAME IS NOT NULL    
                    
                    UNION ALL 

                    SELECT 
                        GL.CODE, 
                        GL.TYPE_ID, 
                        GL.GROUP_ID, 
                        GL.MONGOLIAN, 
                        GL.ENGLISH, 
                        GL.RUSSIAN, 
                        GL.KOREAN, 
                        GL.CHINESE, 
                        GL.JAPANESE, 
                        GL.TURKISH, 
                        GL.GERMAN, 
                        GL.ID, 
                        GL.ARABIC, 
                        GL.META_DATA_ID  
                    FROM META_PROCESS_PARAM_ATTR_LINK PL 
                        INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.LABEL_NAME) 
                    WHERE PL.PROCESS_META_DATA_ID = $metaDataIdPh 
                        AND PL.IS_INPUT = 1 
                        AND PL.IS_SHOW = 1 
                        AND PL.LABEL_NAME IS NOT NULL 
                    
                    UNION ALL 

                    SELECT 
                        GL.CODE, 
                        GL.TYPE_ID, 
                        GL.GROUP_ID, 
                        GL.MONGOLIAN, 
                        GL.ENGLISH, 
                        GL.RUSSIAN, 
                        GL.KOREAN, 
                        GL.CHINESE, 
                        GL.JAPANESE, 
                        GL.TURKISH, 
                        GL.GERMAN, 
                        GL.ID, 
                        GL.ARABIC, 
                        GL.META_DATA_ID  
                    FROM META_PROCESS_RULE PR 
                        INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PR.ERROR_MESSAGE) 
                    WHERE PR.MAIN_PROCESS_ID = $metaDataIdPh 
                ) GD 
                GROUP BY 
                    GD.CODE, 
                    GD.TYPE_ID, 
                    GD.GROUP_ID, 
                    GD.MONGOLIAN, 
                    GD.ENGLISH, 
                    GD.RUSSIAN, 
                    GD.KOREAN, 
                    GD.CHINESE, 
                    GD.JAPANESE, 
                    GD.TURKISH, 
                    GD.GERMAN, 
                    GD.ID, 
                    GD.ARABIC, 
                    GD.META_DATA_ID"; 
            
        } elseif ($metaTypeId == Mdmetadata::$metaGroupMetaTypeId) {
            
            $sql = "
                SELECT 
                    GD.* 
                FROM ( 
                    SELECT 
                        GL.CODE, 
                        GL.TYPE_ID, 
                        GL.GROUP_ID, 
                        GL.MONGOLIAN, 
                        GL.ENGLISH, 
                        GL.RUSSIAN, 
                        GL.KOREAN, 
                        GL.CHINESE, 
                        GL.JAPANESE, 
                        GL.TURKISH, 
                        GL.GERMAN, 
                        GL.ID, 
                        GL.ARABIC, 
                        GL.META_DATA_ID    
                    FROM META_GROUP_LINK PL 
                        INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.LIST_NAME) 
                    WHERE PL.META_DATA_ID = $metaDataIdPh 
                    
                    UNION ALL 
                    
                    SELECT 
                        GL.CODE, 
                        GL.TYPE_ID, 
                        GL.GROUP_ID, 
                        GL.MONGOLIAN, 
                        GL.ENGLISH, 
                        GL.RUSSIAN, 
                        GL.KOREAN, 
                        GL.CHINESE, 
                        GL.JAPANESE, 
                        GL.TURKISH, 
                        GL.GERMAN, 
                        GL.ID, 
                        GL.ARABIC, 
                        GL.META_DATA_ID    
                    FROM META_GROUP_LINK PL 
                        INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.LIST_MENU_NAME) 
                    WHERE PL.META_DATA_ID = $metaDataIdPh 
                    
                    UNION ALL
                    
                    SELECT 
                        GL.CODE, 
                        GL.TYPE_ID, 
                        GL.GROUP_ID, 
                        GL.MONGOLIAN, 
                        GL.ENGLISH, 
                        GL.RUSSIAN, 
                        GL.KOREAN, 
                        GL.CHINESE, 
                        GL.JAPANESE, 
                        GL.TURKISH, 
                        GL.GERMAN, 
                        GL.ID, 
                        GL.ARABIC, 
                        GL.META_DATA_ID    
                    FROM META_GROUP_CONFIG PL
                        INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.SIDEBAR_NAME) 
                    WHERE PL.MAIN_META_DATA_ID = $metaDataIdPh 
                        AND PL.IS_SELECT = 1 
                        AND (PL.IS_SHOW = 1 OR PL.IS_SHOW_BASKET = 1)  
                        AND PL.PARENT_ID IS NULL  
                        AND PL.SIDEBAR_NAME IS NOT NULL    
                    
                    UNION ALL 
                    
                    SELECT 
                        GL.CODE, 
                        GL.TYPE_ID, 
                        GL.GROUP_ID, 
                        GL.MONGOLIAN, 
                        GL.ENGLISH, 
                        GL.RUSSIAN, 
                        GL.KOREAN, 
                        GL.CHINESE, 
                        GL.JAPANESE, 
                        GL.TURKISH, 
                        GL.GERMAN, 
                        GL.ID, 
                        GL.ARABIC, 
                        GL.META_DATA_ID   
                    FROM META_GROUP_CONFIG PL 
                        INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.SEARCH_GROUPING_NAME) 
                    WHERE PL.MAIN_META_DATA_ID = $metaDataIdPh 
                        AND (PL.IS_CRITERIA = 1 OR PL.IS_UM_CRITERIA = 1) 
                        AND PL.PARENT_ID IS NULL 
                        AND PL.SEARCH_GROUPING_NAME IS NOT NULL 
                    
                    UNION ALL
                    
                    SELECT 
                        GL.CODE, 
                        GL.TYPE_ID, 
                        GL.GROUP_ID, 
                        GL.MONGOLIAN, 
                        GL.ENGLISH, 
                        GL.RUSSIAN, 
                        GL.KOREAN, 
                        GL.CHINESE, 
                        GL.JAPANESE, 
                        GL.TURKISH, 
                        GL.GERMAN, 
                        GL.ID, 
                        GL.ARABIC, 
                        GL.META_DATA_ID   
                    FROM META_DM_PROCESS_BATCH PL 
                        INNER JOIN META_DM_PROCESS_DTL DTL ON DTL.MAIN_META_DATA_ID = PL.MAIN_META_DATA_ID 
                            AND DTL.BATCH_NUMBER = PL.BATCH_NUMBER  
                        INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.BATCH_NAME) 
                    WHERE PL.MAIN_META_DATA_ID = $metaDataIdPh 
                        AND PL.BATCH_NAME IS NOT NULL
                        
                    UNION ALL
                    
                    SELECT 
                        GL.CODE, 
                        GL.TYPE_ID, 
                        GL.GROUP_ID, 
                        GL.MONGOLIAN, 
                        GL.ENGLISH, 
                        GL.RUSSIAN, 
                        GL.KOREAN, 
                        GL.CHINESE, 
                        GL.JAPANESE, 
                        GL.TURKISH, 
                        GL.GERMAN, 
                        GL.ID, 
                        GL.ARABIC, 
                        GL.META_DATA_ID   
                    FROM META_DM_PROCESS_DTL PL 
                        INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.PROCESS_NAME) 
                    WHERE PL.MAIN_META_DATA_ID = $metaDataIdPh 
                        AND PL.PROCESS_NAME IS NOT NULL 
                    
                    UNION ALL 

                    SELECT 
                        GL.CODE, 
                        GL.TYPE_ID, 
                        GL.GROUP_ID, 
                        GL.MONGOLIAN, 
                        GL.ENGLISH, 
                        GL.RUSSIAN, 
                        GL.KOREAN, 
                        GL.CHINESE, 
                        GL.JAPANESE, 
                        GL.TURKISH, 
                        GL.GERMAN, 
                        GL.ID, 
                        GL.ARABIC, 
                        GL.META_DATA_ID  
                    FROM META_GROUP_CONFIG PL 
                        INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.LABEL_NAME) 
                    WHERE PL.MAIN_META_DATA_ID = $metaDataIdPh 
                        AND PL.IS_SELECT = 1 
                        AND (PL.IS_SHOW = 1 OR PL.IS_SHOW_BASKET = 1)  
                        AND PL.PARENT_ID IS NULL 
                        AND PL.LABEL_NAME IS NOT NULL 
                ) GD 
                GROUP BY 
                    GD.CODE, 
                    GD.TYPE_ID, 
                    GD.GROUP_ID, 
                    GD.MONGOLIAN, 
                    GD.ENGLISH, 
                    GD.RUSSIAN, 
                    GD.KOREAN, 
                    GD.CHINESE, 
                    GD.JAPANESE, 
                    GD.TURKISH, 
                    GD.GERMAN, 
                    GD.ID, 
                    GD.ARABIC, 
                    GD.META_DATA_ID"; 
            
        } elseif ($metaTypeId == Mdmetadata::$menuMetaTypeId) {
            
            $sql = "
                SELECT 
                    GD.* 
                FROM ( 
                    SELECT 
                        GL.CODE, 
                        GL.TYPE_ID, 
                        GL.GROUP_ID, 
                        GL.MONGOLIAN, 
                        GL.ENGLISH, 
                        GL.RUSSIAN, 
                        GL.KOREAN, 
                        GL.CHINESE, 
                        GL.JAPANESE, 
                        GL.TURKISH, 
                        GL.GERMAN, 
                        GL.ID, 
                        GL.ARABIC, 
                        GL.META_DATA_ID    
                    FROM META_MENU_LINK PL 
                        INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.GLOBE_CODE) 
                    WHERE PL.META_DATA_ID = $metaDataIdPh 
                ) GD 
                GROUP BY 
                    GD.CODE, 
                    GD.TYPE_ID, 
                    GD.GROUP_ID, 
                    GD.MONGOLIAN, 
                    GD.ENGLISH, 
                    GD.RUSSIAN, 
                    GD.KOREAN, 
                    GD.CHINESE, 
                    GD.JAPANESE, 
                    GD.TURKISH, 
                    GD.GERMAN, 
                    GD.ID, 
                    GD.ARABIC, 
                    GD.META_DATA_ID"; 
            
        } elseif ($metaTypeId == Mdmetadata::$statementMetaTypeId) {
            
            $sql = "
                SELECT 
                    GD.* 
                FROM ( 
                    SELECT 
                        GL.CODE, 
                        GL.TYPE_ID, 
                        GL.GROUP_ID, 
                        GL.MONGOLIAN, 
                        GL.ENGLISH, 
                        GL.RUSSIAN, 
                        GL.KOREAN, 
                        GL.CHINESE, 
                        GL.JAPANESE, 
                        GL.TURKISH, 
                        GL.GERMAN, 
                        GL.ID, 
                        GL.ARABIC, 
                        GL.META_DATA_ID 
                    FROM META_TAG PL 
                        INNER JOIN META_TAG_MAP TM ON TM.TAG_ID = PL.ID 
                        INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.NAME) 
                    WHERE TM.META_DATA_ID = $metaDataIdPh 
                ) GD 
                GROUP BY 
                    GD.CODE, 
                    GD.TYPE_ID, 
                    GD.GROUP_ID, 
                    GD.MONGOLIAN, 
                    GD.ENGLISH, 
                    GD.RUSSIAN, 
                    GD.KOREAN, 
                    GD.CHINESE, 
                    GD.JAPANESE, 
                    GD.TURKISH, 
                    GD.GERMAN, 
                    GD.ID, 
                    GD.ARABIC, 
                    GD.META_DATA_ID"; 
        }
        
        if (isset($sql)) {
            $data = $this->db->GetAll($sql, array($metaId));
        }
        
        if ($data) {
            
            $xml .= '<translatelist>' . "\n";
                $xml .= '<![CDATA[' . "\n";
                    $xml .= Str::remove_doublewhitespace(Str::removeNL(var_export($data, true))) . "\n"; 
                $xml .= ']]>' . "\n";
            $xml .= '</translatelist>' . "\n";
        }
        
        return $xml;
    }
    
    public function exportObjectModel() {
        
        includeLib('Compress/Compression');
        $this->load->model('mdmetadata', 'middleware/models/');
        
        $id           = strtolower(Input::post('id'));
        $objectCode   = strtolower(Input::post('objectCode'));
        $selectedRows = Input::post('selectedRows');
        
        if (is_array($selectedRows) && array_key_exists($id, $selectedRows[0])) {
            
            $ids = Arr::implode_key(',', $selectedRows, $id, true);
            
            try {
                
                if ($objectCode == 'kpi') {
                
                    $objects = $this->db->GetAll("
                        SELECT 
                            ID AS META_DATA_ID, 
                            'kpi' AS META_TYPE_ID, 
                            CODE AS META_DATA_CODE 
                        FROM KPI_TEMPLATE 
                        WHERE ID IN ($ids) 

                        UNION 

                        SELECT 
                            KP.ID AS META_DATA_ID, 
                            'kpi' AS META_TYPE_ID, 
                            KP.CODE AS META_DATA_CODE 
                        FROM 
                            (
                                SELECT 
                                    TRG_TEMPLATE_ID 
                                FROM KPI_TEMPLATE_MAP 
                                START WITH SRC_TEMPLATE_ID IN ($ids) 
                                CONNECT BY NOCYCLE SRC_TEMPLATE_ID = PRIOR TRG_TEMPLATE_ID 
                            ) KM 
                            INNER JOIN KPI_TEMPLATE KP ON KP.ID = KM.TRG_TEMPLATE_ID"
                    );

                } elseif ($objectCode == 'ntf') {

                    $objects = $this->db->GetAll("
                        SELECT 
                            NOTIFICATION_ID AS META_DATA_ID, 
                            'ntf' AS META_TYPE_ID, 
                            NOTIFICATION_TYPE_ID AS META_DATA_CODE 
                        FROM NTF_NOTIFICATION 
                        WHERE NOTIFICATION_ID IN ($ids)"
                    );

                } elseif ($objectCode == 'testcase') {

                    $objects = $this->db->GetAll("
                        SELECT 
                            TC.ID AS META_DATA_ID, 
                            'testcase' AS META_TYPE_ID, 
                            TC.TEST_MODE AS META_DATA_CODE, 
                            MD.META_DATA_ID AS PROCESS_META_DATA_ID, 
                            MD.META_TYPE_ID AS PROCESS_META_TYPE_ID, 
                            MD.META_DATA_CODE AS PROCESS_META_DATA_CODE 
                        FROM TEST_CASE TC
                            INNER JOIN META_DATA MD ON MD.META_DATA_ID = TC.PROCESS_META_DATA_ID  
                        WHERE TC.ID IN ($ids)" 
                    );

                } elseif ($objectCode == 'umobject') {

                    $objects = $this->db->GetAll("
                        SELECT 
                            OBJECT_ID AS META_DATA_ID, 
                            'umobject' AS META_TYPE_ID, 
                            OBJECT_CODE_ID AS META_DATA_CODE 
                        FROM UM_OBJECT_CODE 
                        WHERE OBJECT_CODE_ID IN ($ids)"
                    );

                } elseif ($objectCode == 'bugfix') {

                    $objects = $this->db->GetAll("
                        SELECT 
                            ID AS META_DATA_ID, 
                            'bugfix' AS META_TYPE_ID, 
                            ID AS META_DATA_CODE 
                        FROM META_BUG_FIXING 
                        WHERE ID IN ($ids)"
                    );

                } elseif ($objectCode == 'kpiindicator') {

                    $objects = $this->db->GetAll("
                        SELECT 
                            TMP.* 
                        FROM (
                            SELECT
                                ID AS META_DATA_ID, 
                                'kpiindicator' AS META_TYPE_ID, 
                                CODE AS META_DATA_CODE, 
                                NULL AS SRC_RECORD_ID 
                            FROM KPI_INDICATOR
                            START WITH 
                                ID IN ($ids) 
                            CONNECT BY NOCYCLE PRIOR PARENT_ID = ID 

                            UNION 

                            SELECT
                                ID AS META_DATA_ID, 
                                'kpiindicator' AS META_TYPE_ID, 
                                CODE AS META_DATA_CODE, 
                                NULL AS SRC_RECORD_ID 
                            FROM KPI_INDICATOR
                                START WITH ID IN (
                                    SELECT 
                                        CATEGORY_ID 
                                    FROM KPI_INDICATOR_CATEGORY
                                    WHERE INDICATOR_ID IN ($ids) 
                                )
                                CONNECT BY NOCYCLE PRIOR PARENT_ID = ID 

                            UNION 

                            SELECT 
                                KI.ID AS META_DATA_ID, 
                                'kpiindicator' AS META_TYPE_ID, 
                                KI.CODE AS META_DATA_CODE, 
                                NULL AS SRC_RECORD_ID 
                            FROM KPI_INDICATOR_INDICATOR_MAP M 
                                INNER JOIN KPI_INDICATOR KI ON M.TRG_INDICATOR_ID = KI.ID 
                            WHERE M.SEMANTIC_TYPE_ID = 10000009 
                                AND M.SRC_INDICATOR_ID IN ($ids) 
                            
                            UNION 
    
                            SELECT 
                                T2.ID AS META_DATA_ID, 
                                'kpiindicatorbydata' AS META_TYPE_ID, 
                                T2.CODE AS META_DATA_CODE, 
                                T0.ID AS SRC_RECORD_ID 
                            FROM KPI_INDICATOR T0 
                                INNER JOIN KPI_TYPE T1 ON T1.ID = T0.KPI_TYPE_ID 
                                INNER JOIN KPI_INDICATOR T2 ON T2.ID = T1.RELATED_INDICATOR_ID 
                            WHERE T0.ID IN ($ids) 
                        ) TMP 
                        GROUP BY TMP.META_DATA_ID, TMP.META_TYPE_ID, TMP.META_DATA_CODE, TMP.SRC_RECORD_ID  
                        ORDER BY TMP.META_TYPE_ID DESC"
                    );

                } elseif ($objectCode == 'kpiindicatorall') {

                    $objects = $this->db->GetAll("
                        SELECT 
                            TMP.* 
                        FROM (
                            WITH TMP_IND AS (

                                SELECT
                                    ID AS META_DATA_ID, 
                                    'kpiindicator' AS META_TYPE_ID, 
                                    CODE AS META_DATA_CODE 
                                FROM KPI_INDICATOR
                                START WITH 
                                    ID IN ($ids) 
                                CONNECT BY NOCYCLE PRIOR PARENT_ID = ID 

                                UNION 

                                SELECT
                                    ID AS META_DATA_ID, 
                                    'kpiindicator' AS META_TYPE_ID, 
                                    CODE AS META_DATA_CODE 
                                FROM KPI_INDICATOR
                                    START WITH ID IN (
                                        SELECT 
                                            CATEGORY_ID 
                                        FROM KPI_INDICATOR_CATEGORY
                                        WHERE INDICATOR_ID IN ($ids) 
                                    )
                                    CONNECT BY NOCYCLE PRIOR PARENT_ID = ID 

                                UNION 

                                SELECT
                                    ID AS META_DATA_ID, 
                                    'kpiindicator' AS META_TYPE_ID, 
                                    CODE AS META_DATA_CODE 
                                FROM KPI_INDICATOR
                                START WITH 
                                    ID IN ($ids) 
                                CONNECT BY NOCYCLE PRIOR ID = PARENT_ID  

                                UNION 

                                SELECT
                                    ID AS META_DATA_ID, 
                                    'kpiindicator' AS META_TYPE_ID, 
                                    CODE AS META_DATA_CODE 
                                FROM KPI_INDICATOR
                                    START WITH ID IN (
                                        SELECT 
                                            CATEGORY_ID 
                                        FROM KPI_INDICATOR_CATEGORY
                                        WHERE INDICATOR_ID IN ($ids) 
                                    )
                                    CONNECT BY NOCYCLE PRIOR ID = PARENT_ID
                            ) 

                            SELECT 
                                KI.ID AS META_DATA_ID, 
                                'kpiindicator' AS META_TYPE_ID, 
                                KI.CODE AS META_DATA_CODE 
                            FROM KPI_INDICATOR_INDICATOR_MAP M 
                                INNER JOIN KPI_INDICATOR KI ON M.TRG_INDICATOR_ID = KI.ID 
                                INNER JOIN TMP_IND TI ON M.SRC_INDICATOR_ID = TI.META_DATA_ID 
                            WHERE M.SEMANTIC_TYPE_ID = 10000009 

                            UNION 

                            SELECT 
                                META_DATA_ID, 
                                META_TYPE_ID, 
                                META_DATA_CODE
                            FROM TMP_IND 
                        ) TMP 
                        GROUP BY TMP.META_DATA_ID, TMP.META_TYPE_ID, TMP.META_DATA_CODE"
                    );

                } elseif ($objectCode == 'kpiindicatorbycategory') { 
                    
                    $objects = $this->db->GetAll("
                        SELECT 
                            TMP.* 
                        FROM ( 
                            SELECT 
                                KI.ID AS META_DATA_ID, 
                                'kpiindicatorbycategory' AS META_TYPE_ID, 
                                KI.CODE AS META_DATA_CODE 
                            FROM KPI_INDICATOR_INDICATOR_MAP M 
                                INNER JOIN KPI_INDICATOR KI ON M.TRG_INDICATOR_ID = KI.ID 
                            WHERE M.SEMANTIC_TYPE_ID = 10000009 
                                AND M.SRC_INDICATOR_ID IN ( 
                                    SELECT 
                                        INDICATOR_ID 
                                    FROM KPI_INDICATOR_CATEGORY 
                                    WHERE CATEGORY_ID IN ($ids) 
                                )
                                
                            UNION  
                            
                            SELECT 
                                T1.ID AS META_DATA_ID, 
                                'kpiindicatorbycategory' AS META_TYPE_ID, 
                                T1.CODE AS META_DATA_CODE 
                            FROM KPI_INDICATOR_CATEGORY T0 
                                INNER JOIN KPI_INDICATOR T1 ON T1.ID = T0.INDICATOR_ID 
                            WHERE CATEGORY_ID IN ($ids) 
                        ) TMP 
                        GROUP BY TMP.META_DATA_ID, TMP.META_TYPE_ID, TMP.META_DATA_CODE");
                    
                } elseif ($objectCode == 'kpitype') {

                    $objects = $this->db->GetAll("
                        SELECT 
                            T0.* 
                        FROM (   
                            SELECT 
                                KT.ID AS META_DATA_ID, 
                                'kpitype' AS META_TYPE_ID, 
                                KT.CODE AS META_DATA_CODE 
                            FROM 
                                (
                                    SELECT 
                                        ID 
                                    FROM KPI_TYPE 
                                    START WITH ID IN ($ids) 
                                    CONNECT BY NOCYCLE ID = PRIOR PARENT_ID
                                ) KM 
                                INNER JOIN KPI_TYPE KT ON KT.ID = KM.ID 

                            UNION 

                            SELECT 
                                KI.ID AS META_DATA_ID, 
                                'kpiindicator' AS META_TYPE_ID, 
                                KI.CODE AS META_DATA_CODE 
                            FROM (
                                SELECT 
                                    RELATED_INDICATOR_ID 
                                FROM KPI_TYPE 
                                START WITH ID IN ($ids) 
                                CONNECT BY NOCYCLE ID = PRIOR PARENT_ID 
                            ) KT 
                            INNER JOIN KPI_INDICATOR KI ON KI.ID = KT.RELATED_INDICATOR_ID 
                        ) T0 
                        ORDER BY T0.META_TYPE_ID DESC"
                    );

                } elseif ($objectCode == 'impexcel') {

                    $objects = $this->db->GetAll("
                        SELECT 
                            ID AS META_DATA_ID, 
                            'impexcel' AS META_TYPE_ID, 
                            CODE AS META_DATA_CODE 
                        FROM IMP_EXCEL_TEMPLATE 
                        WHERE ID IN ($ids)"
                    );

                } elseif ($objectCode == 'metawidget') {

                    $objects = $this->db->GetAll("
                        SELECT 
                            ID AS META_DATA_ID, 
                            'metawidget' AS META_TYPE_ID, 
                            CODE AS META_DATA_CODE 
                        FROM META_WIDGET 
                        WHERE ID IN ($ids)"
                    );

                } elseif ($objectCode == 'processrule') {

                    $objects = $this->db->GetAll("
                        SELECT 
                            ID AS META_DATA_ID, 
                            'processrule' AS META_TYPE_ID, 
                            ID AS META_DATA_CODE 
                        FROM META_PROCESS_RULE 
                        WHERE ID IN ($ids)"
                    );

                } elseif ($objectCode == 'globedictionary') {

                    $objects = $this->db->GetAll("
                        SELECT 
                            ID AS META_DATA_ID, 
                            'globedictionary' AS META_TYPE_ID, 
                            CODE AS META_DATA_CODE 
                        FROM GLOBE_DICTIONARY 
                        WHERE ID IN ($ids)"
                    );

                }
            
            } catch (Exception $ex) { 
                return array('status' => 'error', 'message' => $ex->getMessage());
            }
        }
        
        $objectXml = null;
        
        if (isset($objects) && $objects) {
            
            self::$exportRecordId = $ids;
            
            foreach ($objects as $object) {
                
                self::$isCreateTable = false;
                self::$isInsertData = true;
                self::$insertDataFilter = null;
                
                if ($object['META_TYPE_ID'] == 'kpiindicatorbydata') { 
                    
                    self::$isCreateTable = true;
                    self::$isInsertData = false;
                    self::$insertDataFilter = 'SRC_RECORD_ID='.$object['SRC_RECORD_ID'];
                    
                    $object['META_TYPE_ID'] = 'kpiindicator';
                    
                } elseif ($object['META_TYPE_ID'] == 'kpiindicatorbycategory') {
                    
                    self::$isCreateTable = false;
                    self::$isInsertData = false;
                    
                    $object['META_TYPE_ID'] = 'kpiindicator';
                }

                $objectResult = self::oneObjectModel($object['META_DATA_ID'], $object['META_TYPE_ID'], $object['META_DATA_CODE']);

                if ($objectResult['status'] == 'success') {
                    
                    $objectXml .= $objectResult['result'];
                    
                    if (isset($object['PROCESS_META_DATA_ID']) && $object['PROCESS_META_DATA_ID']) {
                        
                        $meta = array(
                            'META_TYPE_ID'   => $object['PROCESS_META_TYPE_ID'], 
                            'META_DATA_ID'   => $object['PROCESS_META_DATA_ID'], 
                            'META_DATA_CODE' => $object['PROCESS_META_DATA_CODE'], 
                            'USER_NAME'      => '', 
                            'MODIFIED_DATE'  => ''
                        );
                        
                        $metaResult = self::oneMetaModel($meta);

                        if ($metaResult['status'] == 'success') {
                            $objectXml .= $metaResult['result'];
                        }
                    }
                    
                } else {
                    return $objectResult;
                }
            }
            
        } else {
            return array('status' => 'error', 'message' => 'Татах өгөгдөл олдсонгүй!');
        }

        if ($objectXml) {

            $xml = self::upgradeXmlHeader();

            $xml .= '<metas>' . "\n";
                $xml .= $objectXml;
            $xml .= '</metas>' . "\n";

            $xml .= self::upgradeXmlFooter();

            $script = Compression::gzdeflate($xml);

            $result = array('status' => 'success', 'typeId' => $object['META_TYPE_ID'], 'result' => $script);
            
            if (count($selectedRows) == 1) {
                $result['objectId'] = $ids;
            }
        } 
        
        return isset($result) ? $result : array('status' => 'error', 'message' => 'Татах өгөгдөл олдсонгүй!');
    }
    
    public function importMetaFileModel() {
        
        $isAccessMetaImport = Mdmeta::isAccessMetaImport();
        $objectCode = Input::post('objectCode');
        
        if (!$isAccessMetaImport && $objectCode != 'dbupdate') {
            return array('status' => 'error', 'message' => 'Импорт хийх боломжгүй байна.');
        }
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        includeLib('Compress/Compression');
        
        $totalFile   = count($_FILES['import_file']['name']);
        $fileSources = array();
        
        for ($i = 0; $i < $totalFile; $i++) {
            
            if ($_FILES['import_file']['error'][$i] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['import_file']['tmp_name'][$i])) {
                
                $fileContent = Compression::gzinflate(file_get_contents($_FILES['import_file']['tmp_name'][$i]));
                
                if ($fileContent && strpos($fileContent, '<meta id="') !== false) {
                    
                    if ($objectCode == 'dbupdate') {
                        
                        if (strpos($fileContent, '<meta id="9999999999999" typeId="222222222222" code="databaseCompareScripts"') !== false) {
                            $fileSources[] = $fileContent;
                        } else {
                            return array('status' => 'error', 'message' => 'DBSyncTool-р гарган авсан файл уншуулна уу!');
                        }
                        
                    } else {
                        $fileSources[] = $fileContent;
                    }
                    
                } else {
                    return array('status' => 'error', 'message' => 'PHP export хийсэн файл уншуулна уу!');
                }
            }
        }
        
        if ($fileSources) {
            
            if (Input::postCheck('isPreviewUpdateMeta')) {
                
                if (Input::postCheck('updateMeta')) {
                    
                    $updateMetas = Input::post('updateMeta');
                
                    foreach ($updateMetas as $updateMetaId) {
                        self::$updateMetaIds[$updateMetaId] = 1;
                    }

                    self::$isPreviewUpdateMeta = true;
                    
                } else {
                    return array('status' => 'error', 'message' => 'Шинэчлэлтийн үзүүлэлт олдсонгүй!');
                }
            }
            
            $response = self::executeUpgradeScript($fileSources);
            
        } else {
            $response = array('status' => 'error', 'message' => 'Файлаас шинэчлэлтийн дата олдсонгүй!');
        }
        
        return $response;
    }
    
    public function executeUpgradeScript($fileSources) {
        
        $isCustomerServer = Config::getFromCache('isCustomerServer');
        $isIgnoreCheckLock = Config::getFromCache('CONFIG_IGNORE_CHECK_LOCK');
        $isIgnorePatchScript = Config::getFromCache('PF_IS_IGNORE_PATCH_SCRIPT');
        $isIgnoreCreatedAlready = Config::getFromCache('PF_IS_IGNORE_IMPORT_CREATED_ALREADY');
        $kpiDbSchemaName = Config::getFromCache('kpiDbSchemaName');
        
        $kpiDbSchemaName = $kpiDbSchemaName ? $kpiDbSchemaName . '.' : '';
        $sessionUserId = Ue::sessionUserId();
        $sessionUserId = $sessionUserId ? $sessionUserId : 1;
        $metaCount = $metaLockedCount = 0;
        $lockedMetaMessage = $logs = '';
        $successMetas = $translateList = [];
        
        if ($isIgnoreCreatedAlready) {
            $isIgnoreCheckLock = 1;
        }

        $this->db->BeginTrans(); 

        foreach ($fileSources as $fileSource) {
            
            $fileSource = str_replace('[kpiDbSchemaName].', $kpiDbSchemaName, $fileSource);
            
            if (strpos($fileSource, '_alteredUserId') !== false) {
                
                preg_match('/VALUES\(([0-9]*?)\, _alteredUserId/i', $fileSource, $dbLogIdMatch);
                $dbLogId = $dbLogIdMatch[1];
                
                if (self::checkEisArcScriptLog($dbLogId)) {
                    $this->db->RollbackTrans();
                    return ['status' => 'error', 'message' => 'Уг файлыг өмнө нь уншуулсан байна!', 'logs' => ''];
                }
                
                $fileSource = str_replace('_alteredUserId', $sessionUserId, $fileSource);
            }
            
            $decryptedArray = Xml::createArray($fileSource);
            
            if (isset($decryptedArray['documents']['scripts']['@cdata']) && $decryptedArray['documents']['scripts']['@cdata'] && $isIgnorePatchScript != '1') {
                
                $scripts = trim($decryptedArray['documents']['scripts']['@cdata']);
                
                if ($scripts) {
                    
                    $scripts = str_replace(Mdcommon::$separator . Mdcommon::$separator, Mdcommon::$separator, $scripts);
                    $scripts = str_replace(';' . Mdcommon::$separator, Mdcommon::$separator, $scripts);

                    $scriptsArr = explode(Mdcommon::$separator, $scripts);
                    $isScriptRun = false;
                    
                    foreach ($scriptsArr as $scrpt) {
                        
                        $scrpt = trim($scrpt);
                        
                        if ($scrpt) {
                            
                            $isScriptRun = true;
                            
                            if (DB_DRIVER == 'postgres9') {
                                
                                $lastChar = substr($scrpt, -1);
                                
                                if ($lastChar == ';') {
                                    $scrpt = 'DO $$ BEGIN '.$scrpt.' EXCEPTION WHEN others THEN END; $$;';
                                } else {
                                    $scrpt = 'DO $$ BEGIN '.$scrpt.'; EXCEPTION WHEN others THEN END; $$;';
                                }
                            }
                            
                            if (self::$isGenerateLanguageFile && !self::$isGlobeDictionaryPersist && stripos($scrpt, 'globe_dictionary') !== false) {
                                
                                self::$isGlobeDictionaryPersist = true;
                            }
                            
                            try {
                                
                                $this->db->Execute($scrpt);
                                
                            } catch (Exception $ex) {
                                
                                $logs .= 'SQL:<br />';
                                $logs .= $scrpt.'<br />';
                                $logs .= 'Error:<br />';
                                $logs .= $ex->msg. '<br />';
                                $logs .= '=====================================================================<br />';
                            }
                        }
                    }
                    
                    if ($isScriptRun) {
                        $this->db->CommitTrans();
                        $this->db->BeginTrans(); 
                    }
                }
            }

            if (isset($decryptedArray['documents']['metas']['meta'])) {

                $metas = $decryptedArray['documents']['metas']['meta'];

                if (!array_key_exists(0, $metas)) {
                    $metas = array($metas);
                }

                foreach ($metas as $meta) {

                    $metaAttributes = $meta['@attributes'];

                    $metaDataId = $metaAttributes['id'];
                    $metaTypeId = $metaAttributes['typeId'];
                    
                    if (self::$isPreviewUpdateMeta && !isset(self::$updateMetaIds[$metaDataId])) {
                        continue;
                    }
                    
                    if ($isIgnoreCreatedAlready && self::checkCreatedAlreadyModel($metaTypeId, $metaDataId)) {
                        continue;
                    }
                    
                    if (isset($successMetas[$metaTypeId . '_' . $metaDataId])) {
                        continue;
                    }
                    
                    $metaCode = $metaAttributes['code'];
                    
                    ++$metaCount;

                    if (!$isIgnoreCheckLock && is_numeric($metaTypeId)) {

                        $response = $this->ws->getJsonByCurl(Mdmeta::getLockServerAddr().'checkLock/'.$metaDataId.'/'.$sessionUserId);

                        $json = json_decode($response, true);

                        if ($json && ($json['isLocked'] == true || $json['isLocked'] == 'true')) {

                            ++$metaLockedCount;
                            $lockedMetaMessage .= $metaCode . ', ';

                            continue;
                        }
                    }

                    self::previousTranslateList($isCustomerServer, $metaDataId, $metaTypeId);

                    $skipError    = issetParam($metaAttributes['skipError']);
                    $skipErrorTbl = [];
                    
                    $scripts     = $meta['scripts']['@cdata'];
                    $scriptsList = explode(Mdcommon::$separator, $scripts);
                    
                    foreach ($scriptsList as $script) {

                        $script = trim($script);

                        if ($script) {
                            
                            if (DB_DRIVER == 'postgres9') {
                                
                                preg_match_all('/INSERT INTO(.*?)\(/', $script, $parseTblName);
                                
                                if ($skipError == '1' || (isset($parseTblName[1][0]) && $parseTblName[1][0] && in_array(trim($parseTblName[1][0]), self::$ignoreDeleteScriptTables))) {
                                    $script = 'DO $$ BEGIN '.$script.'; EXCEPTION WHEN others THEN END; $$;';
                                }
                            }
                            
                            if (self::$isGenerateLanguageFile && !self::$isGlobeDictionaryPersist && stripos($script, 'globe_dictionary') !== false) {
                                
                                self::$isGlobeDictionaryPersist = true;
                            }

                            try {
                                
                                $this->db->Execute($script);

                            } catch (Exception $ex) {
                                
                                preg_match_all('/INSERT INTO(.*?)\(/', $script, $parseTblName);
                                
                                if ($skipError == '1') {
                                    
                                    $skipErrorTbl[trim($parseTblName[1][0])] = 1;
                                    
                                } else {
                                
                                    $message = $ex->getMessage();

                                    $logs = 'META SQL:<br />';
                                    $logs .= $script.'<br />';
                                    $logs .= 'Error:<br />';
                                    $logs .= $message. '<br />';
                                    $logs .= '=====================================================================<br />';

                                    if (!isset($parseTblName[1][0]) || (isset($parseTblName[1][0]) && $parseTblName[1][0] && !in_array(trim($parseTblName[1][0]), self::$ignoreDeleteScriptTables))) {

                                        $this->db->RollbackTrans();
                                        return ['status' => 'error', 'message' => $message, 'logs' => $logs];
                                    }
                                }
                                
                            }
                        }
                    }
                    
                    if (isset($meta['createTables']['createTable'])) {
                        
                        $createTables = $meta['createTables']['createTable'];
                        
                        if (!array_key_exists(0, $createTables)) {
                            $createTables = array($createTables);
                        }
                        
                        foreach ($createTables as $createTable) {
                            
                            $content = $createTable['@cdata'];
                            $createTableScripts = explode(Mdcommon::$separator, $content);
                            
                            foreach ($createTableScripts as $createTableScript) {
                                
                                $createTableScript = trim($createTableScript);

                                if ($createTableScript) {
                                    
                                    $createTableScript = str_replace('NUMBER(, )', 'NUMBER', $createTableScript);
                                    
                                    if (DB_DRIVER == 'postgres9') {
                                        
                                        $createTableScript = str_replace(' CHAR)', ')', $createTableScript);
                                        $createTableScript = str_replace("''", "'", $createTableScript);
                                        
                                        if (strpos($createTableScript, 'ALTER TABLE') !== false) {
                                            
                                            $createTableScript = str_replace(' ADD (', ' ADD COLUMN ', $createTableScript);
                                            $createTableScript = str_replace('))', ')', $createTableScript);
                                            $createTableScript = str_replace('CLOB)', 'CLOB', $createTableScript);
                                            $createTableScript = str_replace('DATE)', 'DATE', $createTableScript);
                                        }
                                        
                                        $createTableScript = 'DO $$ BEGIN '.$createTableScript.'; EXCEPTION WHEN others THEN END; $$;';
                                    }
                                    
                                    try {

                                        $this->db->Execute($createTableScript);

                                    } catch (Exception $ex) { }
                                }
                            }
                        }
                    }

                    if (isset($meta['cloblobs']['cloblob'])) {

                        $cloblobs = $meta['cloblobs']['cloblob'];

                        if (!array_key_exists(0, $cloblobs)) {
                            $cloblobs = array($cloblobs);
                        }

                        foreach ($cloblobs as $cloblob) {

                            $content    = $cloblob['@cdata'];
                            $attributes = $cloblob['@attributes'];

                            $type       = $attributes['type'];
                            $tblName    = $attributes['tblName'];
                            $colName    = $attributes['colName'];
                            $equalField = $attributes['equalField'];
                            $recordId   = $attributes['recordId'];
                            
                            if ($skipError == '1' && isset($skipErrorTbl[$tblName])) {
                                continue;
                            }

                            try {

                                if ($type == 'clob') {
                                    $this->db->UpdateClob($tblName, $colName, $content, $equalField . ' = '.$recordId);
                                } else {
                                    $this->db->UpdateBlob($tblName, $colName, $content, $equalField . ' = '.$recordId);
                                }

                            } catch (Exception $ex) {
                                       
                                if ($skipError != '1') {
                                    
                                    $message = $ex->getMessage();
                                    $this->db->RollbackTrans();

                                    $logs .= 'META CLOB:<br />';
                                    $logs .= $tblName.'<br />';
                                    $logs .= 'Error:<br />';
                                    $logs .= $message. '<br />';
                                    $logs .= '=====================================================================<br />';

                                    return ['status' => 'error', 'message' => $message, 'logs' => $logs];
                                }
                            }
                        }
                    }

                    if (isset($meta['filecontents']['filecontent'])) {

                        $fileContents = $meta['filecontents']['filecontent'];

                        if (!array_key_exists(0, $fileContents)) {
                            $fileContents = array($fileContents);
                        }

                        foreach ($fileContents as $fileContent) {

                            $content    = $fileContent['@cdata'];
                            $attributes = $fileContent['@attributes'];

                            $fileType   = $attributes['fileType'];
                            $fileUrl    = $attributes['fileUrl'];

                            try {

                                if ($fileType == 'imageFile') {

                                    self::file_force_contents($fileUrl, base64_decode($content));

                                } elseif ($fileType == 'contentFile') {

                                    self::file_force_contents($fileUrl, $content);
                                }

                            } catch (Exception $ex) {

                                $message = $ex->getMessage();
                                $this->db->RollbackTrans();
                                
                                $logs .= 'META FILE:<br />';
                                $logs .= $fileUrl.'<br />';
                                $logs .= 'Error:<br />';
                                $logs .= $message. '<br />';
                                $logs .= '=====================================================================<br />';

                                return ['status' => 'error', 'message' => $message, 'logs' => $logs];
                            }
                        }
                    }

                    if (isset($meta['translatelist']['@cdata'])) {
                        $translateList[$metaDataId] = $meta['translatelist']['@cdata'];
                    }
                    
                    $successMetas[$metaTypeId . '_' . $metaDataId] = [
                        'metaTypeId' => $metaTypeId, 
                        'metaDataId' => $metaDataId, 
                        'metaCode'   => $metaCode
                    ];
                }
            } 
        }
        
        if (self::$ignoreDbCommitTrans == false) {
            
            $this->db->CommitTrans();
            
            if ($successMetas) {
                
                self::upgradeGlobeDictionary($translateList);
                
                $metaClass = new Mdmeta();
                
                foreach ($successMetas as $keyMeta) {

                    if ($keyMeta['metaTypeId'] == Mdmetadata::$businessProcessMetaTypeId) {

                        $metaClass->bpParamsClearCache($keyMeta['metaDataId'], $keyMeta['metaCode'], true);

                    } elseif ($keyMeta['metaTypeId'] == Mdmetadata::$metaGroupMetaTypeId) {

                        $metaClass->dvCacheClearByMetaId($keyMeta['metaDataId'], true);

                    } elseif ($keyMeta['metaTypeId'] == 'kpi' || $keyMeta['metaTypeId'] == 'kpiindicator') {

                        $metaClass->clearCacheKpiTemplateById($keyMeta['metaDataId']);
                    }
                }
            }
            
            if (self::$isGenerateLanguageFile && self::$isGlobeDictionaryPersist) {
                    
                $this->load->model('mdlanguage', 'middleware/models/');
                $this->model->generateLanguageFileModel();
            }

            if ($metaCount == $metaLockedCount) {

                if ($metaCount == 1) {
                    $response = ['status' => 'error', 'message' => rtrim($lockedMetaMessage, ', ') . ' кодтой уг үзүүлэлт түгжээтэй байгаа учир шинэчлэх боломжгүй байна!', 'logs' => $logs];
                } else {
                    $response = ['status' => 'error', 'message' => "Нийт $metaCount үзүүлэлтээс түгжигдсэн үзүүлэлт: " . rtrim($lockedMetaMessage, ', '), 'logs' => $logs];
                }

            } else {

                if ($metaLockedCount > 0) {
                    $doneMetaCount = $metaCount - $metaLockedCount;
                    $response = ['status' => 'success', 'message' => "Нийт $metaCount үзүүлэлтээс $doneMetaCount шинэчлэгдлээ. Түгжигдсэн үзүүлэлт: " . rtrim($lockedMetaMessage, ', '), 'logs' => $logs];
                } else {
                    $response = ['status' => 'success', 'message' => 'Амжилттай', 'logs' => $logs];
                }
            }
            
        } else {
            $response = ['status' => 'success', 'metaDataId' => $metaDataId];
        }

        return $response;
    }
    
    public function upgradeGlobeDictionary($translateList) {
        
        if ($translateList) {
            
            self::$isGlobeDictionaryPersist = true;
            
            foreach ($translateList as $metaId => $arrayStr) {
                
                eval('$array = '.$arrayStr.';'); 
                
                foreach ($array as $row) {
                    
                    $globeCode   = Str::lower($row['CODE']);
                    $updateArray = $row;

                    unset($updateArray['CODE']);

                    $cols = [];

                    foreach ($updateArray as $key => $val) {
                        if ($val != '') { 
                            $cols[] = "$key = '$val'"; 
                        } else {
                            $cols[] = "$key = null";
                        }
                    }

                    $affectedCount = self::executeQueryStr("UPDATE GLOBE_DICTIONARY SET ".implode(', ', $cols)." WHERE LOWER(CODE) = '$globeCode' AND IS_CUSTOM = 0");

                    if (!$affectedCount) {
                        self::insertExecute($row);
                    }
                }
            }
        }
        
        if (self::$previousTranslateList) {
            
            $this->db->BeginTrans(); 
            
            foreach (self::$previousTranslateList as $metaId => $row) {
                
                $list = $row['list'];
                
                foreach ($list as $val) {
                    
                    try {
                        
                        if ($val['UPDATE_TBL'] == 'META_BUSINESS_PROCESS_LINK') {
                            
                            $updateQry = "UPDATE META_BUSINESS_PROCESS_LINK SET PROCESS_NAME = '".$val['CODE']."' WHERE META_DATA_ID = $metaId";
                            
                        } elseif ($val['UPDATE_COL'] == 'TAB_NAME' || $val['UPDATE_COL'] == 'SIDEBAR_NAME' || $val['UPDATE_COL'] == 'LABEL_NAME') {
                            
                            $updateQry = "UPDATE META_PROCESS_PARAM_ATTR_LINK SET ".$val['UPDATE_COL']." = '".$val['CODE']."' WHERE PROCESS_META_DATA_ID = $metaId AND IS_INPUT = 1 AND IS_SHOW = 1 AND PARAM_REAL_PATH = '".$val['REAL_PATH']."'";
                            
                        } elseif ($val['UPDATE_TBL'] == 'META_MENU_LINK') {
                            
                            $updateQry = "UPDATE META_MENU_LINK SET GLOBE_CODE = '".$val['CODE']."' WHERE META_DATA_ID = $metaId";
                            
                        } elseif ($val['UPDATE_COL'] == 'LIST_NAME') {
                            
                            $updateQry = "UPDATE META_GROUP_LINK SET LIST_NAME = '".$val['CODE']."' WHERE META_DATA_ID = $metaId";
                            
                        } elseif ($val['UPDATE_COL'] == 'LIST_MENU_NAME') {
                            
                            $updateQry = "UPDATE META_GROUP_LINK SET LIST_MENU_NAME = '".$val['CODE']."' WHERE META_DATA_ID = $metaId";
                            
                        } elseif ($val['UPDATE_COL'] == 'DV_SIDEBAR_NAME') {
                            
                            $updateQry = "UPDATE META_GROUP_CONFIG SET SIDEBAR_NAME = '".$val['CODE']."' WHERE MAIN_META_DATA_ID = $metaId AND FIELD_PATH = '".$val['REAL_PATH']."'";
                            
                        } elseif ($val['UPDATE_COL'] == 'SEARCH_GROUPING_NAME') {
                            
                            $updateQry = "UPDATE META_GROUP_CONFIG SET SEARCH_GROUPING_NAME = '".$val['CODE']."' WHERE MAIN_META_DATA_ID = $metaId AND FIELD_PATH = '".$val['REAL_PATH']."'";
                            
                        } elseif ($val['UPDATE_COL'] == 'BATCH_NAME') {
                            
                            $updateQry = "UPDATE META_DM_PROCESS_BATCH SET BATCH_NAME = '".$val['CODE']."' WHERE ID = ".$val['REAL_PATH'];
                            
                        } elseif ($val['UPDATE_COL'] == 'PROCESS_NAME') {
                            
                            $updateQry = "UPDATE META_DM_PROCESS_DTL SET PROCESS_NAME = '".$val['CODE']."' WHERE ID = ".$val['REAL_PATH'];
                            
                        } elseif ($val['UPDATE_COL'] == 'DV_LABEL_NAME') {
                            
                            $updateQry = "UPDATE META_GROUP_CONFIG SET LABEL_NAME = '".$val['CODE']."' WHERE MAIN_META_DATA_ID = $metaId AND FIELD_PATH = '".$val['REAL_PATH']."'";
                        }
                        
                        $this->db->Execute($updateQry);

                    } catch (Exception $ex) {
                        
                        $message = $ex->getMessage();
                    }
                }
            }
            
            $this->db->CommitTrans();
        }
        
        return true;
    }
    
    public function previousTranslateList($isCustomerServer, $metaDataId, $metaTypeId) {
        
        if ($isCustomerServer == '1') {
            
            if (isset(self::$previousTranslateList[$metaDataId])) {
                return null;
            }

            $row = $this->db->GetRow("SELECT ID FROM CUSTOMER_META_TRANSLATE WHERE META_DATA_ID = " . $this->db->Param(0), array($metaDataId));

            if (isset($row['ID'])) {
                
                $metaDataIdPh = $this->db->Param(0);
                
                if ($metaTypeId == Mdmetadata::$businessProcessMetaTypeId) {
            
                    $sql = "
                        SELECT 
                            GD.* 
                        FROM ( 
                            SELECT 
                                GL.CODE, 
                                null AS REAL_PATH, 
                                'PROCESS_NAME' AS UPDATE_COL, 
                                'META_BUSINESS_PROCESS_LINK' AS UPDATE_TBL   
                            FROM META_BUSINESS_PROCESS_LINK PL 
                                INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.PROCESS_NAME) 
                            WHERE PL.META_DATA_ID = $metaDataIdPh 

                            UNION ALL 

                            SELECT 
                                GL.CODE, 
                                PL.PARAM_REAL_PATH AS REAL_PATH, 
                                'TAB_NAME' AS UPDATE_COL, 
                                'META_PROCESS_PARAM_ATTR_LINK' AS UPDATE_TBL       
                            FROM META_PROCESS_PARAM_ATTR_LINK PL
                                INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.TAB_NAME) 
                            WHERE PL.PROCESS_META_DATA_ID = $metaDataIdPh 
                                AND PL.IS_INPUT = 1 
                                AND PL.IS_SHOW = 1 
                                AND PL.TAB_NAME IS NOT NULL    

                            UNION ALL 

                            SELECT 
                                GL.CODE, 
                                PL.PARAM_REAL_PATH AS REAL_PATH, 
                                'SIDEBAR_NAME' AS UPDATE_COL, 
                                'META_PROCESS_PARAM_ATTR_LINK' AS UPDATE_TBL          
                            FROM META_PROCESS_PARAM_ATTR_LINK PL 
                                INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.SIDEBAR_NAME) 
                            WHERE PL.PROCESS_META_DATA_ID = $metaDataIdPh 
                                AND PL.IS_INPUT = 1 
                                AND PL.IS_SHOW = 1 
                                AND PL.SIDEBAR_NAME IS NOT NULL    

                            UNION ALL 

                            SELECT 
                                GL.CODE, 
                                PL.PARAM_REAL_PATH AS REAL_PATH, 
                                'LABEL_NAME' AS UPDATE_COL, 
                                'META_PROCESS_PARAM_ATTR_LINK' AS UPDATE_TBL           
                            FROM META_PROCESS_PARAM_ATTR_LINK PL 
                                INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.LABEL_NAME) 
                            WHERE PL.PROCESS_META_DATA_ID = $metaDataIdPh 
                                AND PL.IS_INPUT = 1 
                                AND PL.IS_SHOW = 1 
                                AND PL.LABEL_NAME IS NOT NULL 
                        ) GD 
                        GROUP BY 
                            GD.CODE, 
                            GD.REAL_PATH, 
                            GD.UPDATE_COL, 
                            GD.UPDATE_TBL"; 
                    
                } elseif ($metaTypeId == Mdmetadata::$metaGroupMetaTypeId) {
                    
                    $sql = "
                        SELECT 
                            GD.* 
                        FROM ( 
                            SELECT 
                                GL.CODE, 
                                null AS REAL_PATH, 
                                'LIST_NAME' AS UPDATE_COL, 
                                'META_GROUP_LINK' AS UPDATE_TBL   
                            FROM META_GROUP_LINK PL 
                                INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.LIST_NAME) 
                            WHERE PL.META_DATA_ID = $metaDataIdPh 

                            UNION ALL 
                            
                            SELECT 
                                GL.CODE, 
                                null AS REAL_PATH, 
                                'LIST_MENU_NAME' AS UPDATE_COL, 
                                'META_GROUP_LINK' AS UPDATE_TBL   
                            FROM META_GROUP_LINK PL 
                                INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.LIST_MENU_NAME) 
                            WHERE PL.META_DATA_ID = $metaDataIdPh 

                            UNION ALL 

                            SELECT 
                                GL.CODE, 
                                PL.FIELD_PATH AS REAL_PATH, 
                                'DV_SIDEBAR_NAME' AS UPDATE_COL, 
                                'META_GROUP_CONFIG' AS UPDATE_TBL       
                            FROM META_GROUP_CONFIG PL
                                INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.SIDEBAR_NAME) 
                            WHERE PL.MAIN_META_DATA_ID = $metaDataIdPh 
                                AND PL.IS_SELECT = 1 
                                AND (PL.IS_SHOW = 1 OR PL.IS_SHOW_BASKET = 1)  
                                AND PL.PARENT_ID IS NULL  
                                AND PL.SIDEBAR_NAME IS NOT NULL    

                            UNION ALL 

                            SELECT 
                                GL.CODE, 
                                PL.FIELD_PATH AS REAL_PATH, 
                                'SEARCH_GROUPING_NAME' AS UPDATE_COL, 
                                'META_GROUP_CONFIG' AS UPDATE_TBL          
                            FROM META_GROUP_CONFIG PL 
                                INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.SEARCH_GROUPING_NAME) 
                            WHERE PL.MAIN_META_DATA_ID = $metaDataIdPh 
                                AND (PL.IS_CRITERIA = 1 OR PL.IS_UM_CRITERIA = 1) 
                                AND PL.PARENT_ID IS NULL 
                                AND PL.SEARCH_GROUPING_NAME IS NOT NULL 
                            
                            UNION ALL 

                            SELECT 
                                GL.CODE, 
                                TO_CHAR(PL.ID) AS REAL_PATH, 
                                'BATCH_NAME' AS UPDATE_COL, 
                                'META_DM_PROCESS_BATCH' AS UPDATE_TBL          
                            FROM META_DM_PROCESS_BATCH PL 
                                INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.BATCH_NAME) 
                            WHERE PL.MAIN_META_DATA_ID = $metaDataIdPh 
                                AND PL.BATCH_NAME IS NOT NULL 
                            
                            UNION ALL 

                            SELECT 
                                GL.CODE, 
                                TO_CHAR(PL.ID) AS REAL_PATH, 
                                'PROCESS_NAME' AS UPDATE_COL, 
                                'META_DM_PROCESS_DTL' AS UPDATE_TBL          
                            FROM META_DM_PROCESS_DTL PL 
                                INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.PROCESS_NAME) 
                            WHERE PL.MAIN_META_DATA_ID = $metaDataIdPh 
                                AND PL.PROCESS_NAME IS NOT NULL 

                            UNION ALL 

                            SELECT 
                                GL.CODE, 
                                PL.FIELD_PATH AS REAL_PATH, 
                                'DV_LABEL_NAME' AS UPDATE_COL, 
                                'META_GROUP_CONFIG' AS UPDATE_TBL           
                            FROM META_GROUP_CONFIG PL 
                                INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.LABEL_NAME) 
                            WHERE PL.MAIN_META_DATA_ID = $metaDataIdPh 
                                AND PL.IS_SELECT = 1 
                                AND (PL.IS_SHOW = 1 OR PL.IS_SHOW_BASKET = 1)  
                                AND PL.PARENT_ID IS NULL 
                                AND PL.LABEL_NAME IS NOT NULL 
                        ) GD 
                        GROUP BY 
                            GD.CODE, 
                            GD.REAL_PATH, 
                            GD.UPDATE_COL, 
                            GD.UPDATE_TBL"; 
                    
                } elseif ($metaTypeId == Mdmetadata::$menuMetaTypeId) {
                    
                    $sql = "
                        SELECT 
                            GD.* 
                        FROM ( 
                            SELECT 
                                GL.CODE, 
                                null AS REAL_PATH, 
                                'GLOBE_CODE' AS UPDATE_COL, 
                                'META_MENU_LINK' AS UPDATE_TBL   
                            FROM META_MENU_LINK PL 
                                INNER JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.GLOBE_CODE) 
                            WHERE PL.META_DATA_ID = $metaDataIdPh 
                        ) GD 
                        GROUP BY 
                            GD.CODE, 
                            GD.REAL_PATH, 
                            GD.UPDATE_COL, 
                            GD.UPDATE_TBL"; 
                }
                
                if (isset($sql)) {
                    
                    $data = $this->db->GetAll($sql, array($metaDataId));
                    
                    if ($data) {
                        self::$previousTranslateList[$metaDataId] = array(
                            'metaTypeId' => $metaTypeId, 
                            'list'       => $data
                        );
                    }
                    
                    return true;
                }

            } else {
                return null;
            }
        }
        
        return null;
    }
    
    public function executeQueryStr($str) {
        
        try {
            
            $this->db->Execute($str);
            
            return $this->db->affected_rows();
            
        } catch (Exception $ex) {
            return 0;
        }
    }
    
    public function insertExecute($row) {
        
        try {
            
            $this->db->AutoExecute('GLOBE_DICTIONARY', $row);
            return true;
            
        } catch (Exception $ex) {
            return false;
        }
    }
    
    public function importAnotherServerModel() {
        
        $url = Config::getFromCache('PRODUCTION_HTTP_URL');
        $metaId = Input::post('metaId');
        
        if ($url) {
            
            set_time_limit(0);
            ini_set('memory_limit', '-1');
        
            $result = self::getOneMetaToMultiMetaModel($metaId);

            if ($result['status'] == 'success') {

                try {

                    $params = array('encryptedSource' => $result['result']); 

                    $curl_handle = curl_init();

                    curl_setopt($curl_handle, CURLOPT_URL, $url . 'mdupgrade/encryptedFileImport');
                    curl_setopt($curl_handle, CURLOPT_TIMEOUT, 30000);
                    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl_handle, CURLOPT_POST, true);
                    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, json_encode($params));
                    curl_setopt($curl_handle, CURLOPT_HEADER, false);
                    curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
                        'User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36',
                        'Cache-Control: no-cache', 
                        'Content-Type: application/json'
                    ));

                    $buffer = curl_exec($curl_handle);
                    curl_close($curl_handle);

                    $response = json_decode(remove_utf8_bom($buffer), true);

                } catch (Exception $ex) {
                    $response = array('status' => 'error', 'message' => $ex->getMessage());
                }

            } else {
                $response = array('status' => 'error', 'message' => 'Амжилтгүй!');
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Тохиргоо дутуу байна!');
        }
        
        if ($response['status'] == 'success') {
            
            $logParam = array(
                'ID'              => getUID(), 
                'META_DATA_ID'    => $metaId, 
                'CREATED_USER_ID' => Ue::sessionUserId(), 
                'CREATED_DATE'    => Date::currentDate()
            );
            
            $this->db->AutoExecute('UM_META_UPGRADE_LOG', $logParam);
        }
        
        return $response;
    }
    
    public function encryptedFileImportModel($param = array()) {
        
        try {
            
            if (!$param) {
                $jsonBody = file_get_contents('php://input');
                $param = json_decode($jsonBody, true);
            }
            
            if (isset($param['bugFixEncryptedSource'])) {
                
                $bugFixEncryptedSource = $param['bugFixEncryptedSource'];
                unset($param['bugFixEncryptedSource']);
                
                $bugfixParam = $param;
                $bugfixParam['encryptedSource'] = $bugFixEncryptedSource;
                
                $result = $this->model->encryptedFileImportModel($bugfixParam);
                
                if ($result['status'] != 'success') {
                    
                    return $result;
                    
                } elseif ($bugFixId = issetParam($param['bugFixId'])) { 
                    
                    $result = self::downloadBugFixingModel($bugFixId);

                    if ($result['status'] == 'success') {
                        $param['bugfixOldPatch'] = $result['result'];
                    }
                }
            }
            
            if (isset($param['encryptedSource'])) {

                includeLib('Compress/Compression');

                $encryptedXml = $param['encryptedSource'];
                $decryptedXml = Compression::gzinflate($encryptedXml);

                $decryptedArray = Xml::createArray($decryptedXml);

                if (isset($decryptedArray['documents']['metas']['meta'])) {

                    $metas = $decryptedArray['documents']['metas']['meta'];

                    if (!array_key_exists(0, $metas)) {
                        $metas = array($metas);
                    }
                    
                    $isCustomerServer = Config::getFromCache('isCustomerServer');
                    $successMetas = $translateList = array();
                    
                    $this->db->BeginTrans(); 

                    foreach ($metas as $meta) {

                        $metaAttributes = $meta['@attributes'];

                        $metaDataId = $metaAttributes['id'];
                        $metaTypeId = $metaAttributes['typeId'];
                        $metaCode   = $metaAttributes['code'];
                        
                        self::previousTranslateList($isCustomerServer, $metaDataId, $metaTypeId);
                        
                        $skipError    = issetParam($metaAttributes['skipError']);
                        $skipErrorTbl = array();
                    
                        $scripts     = $meta['scripts']['@cdata'];
                        $scriptsList = explode(Mdcommon::$separator, $scripts);

                        foreach ($scriptsList as $script) {
                            
                            $script = trim($script);
                            
                            if ($script) {
                                
                                try {

                                    $this->db->Execute($script);

                                } catch (ADODB_Exception $ex) { 
                                    
                                    preg_match_all('/INSERT INTO(.*?)\(/', $script, $parseTblName);
                                
                                    if ($skipError == '1') {

                                        $skipErrorTbl[trim($parseTblName[1][0])] = 1;

                                    } else {

                                        $message = $ex->getMessage();

                                        if (!isset($parseTblName[1][0]) || (isset($parseTblName[1][0]) && $parseTblName[1][0] && !in_array(trim($parseTblName[1][0]), self::$ignoreDeleteScriptTables))) {

                                            $this->db->RollbackTrans();
                                            return array('status' => 'error', 'message' => $message);
                                        }
                                    }
                                }
                            }
                        } 

                        if (isset($meta['cloblobs']['cloblob'])) {

                            $cloblobs = $meta['cloblobs']['cloblob'];

                            if (!array_key_exists(0, $cloblobs)) {
                                $cloblobs = array($cloblobs);
                            }

                            foreach ($cloblobs as $cloblob) {

                                $content    = $cloblob['@cdata'];
                                $attributes = $cloblob['@attributes'];

                                $type       = $attributes['type'];
                                $tblName    = $attributes['tblName'];
                                $colName    = $attributes['colName'];
                                $equalField = $attributes['equalField'];
                                $recordId   = $attributes['recordId'];
                                
                                if ($skipError == '1' && isset($skipErrorTbl[$tblName])) {
                                    continue;
                                }

                                try {

                                    if ($type == 'clob') {
                                        $this->db->UpdateClob($tblName, $colName, $content, $equalField . ' = '.$recordId);
                                    } else {
                                        $this->db->UpdateBlob($tblName, $colName, $content, $equalField . ' = '.$recordId);
                                    }

                                } catch (ADODB_Exception $ex) {
                                    
                                    if ($skipError != '1') {
                                        
                                        $message = $ex->getMessage();
                                        $this->db->RollbackTrans();

                                        return array('status' => 'error', 'message' => $message);
                                    }
                                }
                            }
                        }

                        if (isset($meta['filecontents']['filecontent'])) {

                            $fileContents = $meta['filecontents']['filecontent'];

                            if (!array_key_exists(0, $fileContents)) {
                                $fileContents = array($fileContents);
                            }

                            foreach ($fileContents as $fileContent) {

                                $content    = $fileContent['@cdata'];
                                $attributes = $fileContent['@attributes'];

                                $fileType   = $attributes['fileType'];
                                $fileUrl    = $attributes['fileUrl'];

                                try {

                                    if ($fileType == 'imageFile') {

                                        self::file_force_contents($fileUrl, base64_decode($content));

                                    } elseif ($fileType == 'contentFile') {

                                        self::file_force_contents($fileUrl, $content);
                                    }

                                } catch (Exception $ex) {

                                    $message = $ex->getMessage();
                                    $this->db->RollbackTrans();

                                    return array('status' => 'error', 'message' => $message);
                                }
                            }
                        }
                        
                        if (isset($meta['translatelist']['@cdata'])) {
                            $translateList[$metaDataId] = $meta['translatelist']['@cdata'];
                        }
                        
                        $successMetas[$metaDataId] = array('metaTypeId' => $metaTypeId, 'metaCode' => $metaCode);
                    }

                    $this->db->CommitTrans();
                    
                    if ($successMetas) {
                        
                        Session::init();
                        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');

                        if ($logged == false) {
                            Session::set(SESSION_PREFIX . 'LoggedIn', true);
                            Session::set(SESSION_PREFIX . 'lastTime', time());
                        }

                        $_POST['nult'] = true;
                        
                        self::upgradeGlobeDictionary($translateList);
                        
                        foreach ($successMetas as $keyMetaId => $keyMeta) {

                            if ($keyMeta['metaTypeId'] == Mdmetadata::$businessProcessMetaTypeId) {
                                
                                (new Mdmeta())->bpParamsClearCache($keyMetaId, $keyMeta['metaCode'], true);
                                
                            } elseif ($keyMeta['metaTypeId'] == Mdmetadata::$metaGroupMetaTypeId) {
                                
                                (new Mdmeta())->dvCacheClearByMetaId($keyMetaId, true);
                                
                            } elseif ($keyMeta['metaTypeId'] == 'kpi' || $keyMeta['metaTypeId'] == 'kpiindicator') {

                                (new Mdmeta())->clearCacheKpiTemplateById($keyMetaId);
                            }
                        }
                    }
                    
                    if ($bugFixId = issetParam($param['bugFixId'])) {
                        
                        $currentDate = Date::currentDate('Y-m-d H:i:s');

                        $fixedData = array(
                            'ID'                 => getUID(), 
                            'META_BUG_FIXING_ID' => $bugFixId, 
                            'CREATED_USER_ID'    => Ue::sessionUserKeyId(), 
                            'CREATED_DATE'       => $currentDate, 
                            'STATUS_ID'          => 1, 
                            'MODIFIED_DATE'      => $currentDate
                        );

                        $customerBugFixedResult = $this->db->AutoExecute('CUSTOMER_BUG_FIXED', $fixedData);
                        
                        if ($customerBugFixedResult && isset($param['bugfixOldPatch'])) {
                            $this->db->UpdateClob('CUSTOMER_BUG_FIXED', 'OLD_PATCH', $param['bugfixOldPatch'], 'ID = '.$fixedData['ID']);
                        }
                    }

                    return array('status' => 'success', 'message' => 'Амжилттай');
                }    
            } 

            return array('status' => 'error', 'message' => 'Амжилтгүй!');
            
        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }
    
    public function getOneMetaToMultiMetaModel($metaId) {
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        $result     = array('status' => 'error', 'message' => 'Үзүүлэлт олдсонгүй!');
        $metaTypeId = $this->model->getMetaTypeById($metaId);
        
        $metaIdPh   = $this->db->Param(0);
        
        if ($metaTypeId == Mdmetadata::$businessProcessMetaTypeId) {
            
            $sql = "
                SELECT 
                    TT.META_DATA_ID, TT.META_TYPE_ID, TT.META_DATA_CODE  
                FROM (
                    SELECT 
                        META_DATA_ID, META_TYPE_ID, META_DATA_CODE  
                    FROM META_DATA 
                    WHERE META_DATA_ID = $metaIdPh 
                    
                    UNION  
                    
                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE  
                    FROM META_BUSINESS_PROCESS_LINK BP 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = BP.GETDATA_PROCESS_ID 
                    WHERE BP.META_DATA_ID = $metaIdPh 
                    
                    UNION  
                    
                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_BUSINESS_PROCESS_LINK BP 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = BP.SYSTEM_META_GROUP_ID  
                    WHERE BP.META_DATA_ID = $metaIdPh 
                    
                    UNION  
                    
                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_BUSINESS_PROCESS_LINK BP 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = BP.REF_META_GROUP_ID   
                    WHERE BP.META_DATA_ID = $metaIdPh 
                    
                    UNION  
                    
                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_PROCESS_PARAM_ATTR_LINK BP 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = BP.GET_PROCESS_META_DATA_ID    
                    WHERE BP.PROCESS_META_DATA_ID = $metaIdPh 
                        
                    UNION  
                    
                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_PROCESS_PARAM_ATTR_LINK BP 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = BP.LOOKUP_META_DATA_ID     
                    WHERE BP.PROCESS_META_DATA_ID = $metaIdPh 
                        
                    UNION  
                    
                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_BP_EXPRESSION_PROCESS BP 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = BP.USE_META_DATA_ID      
                    WHERE BP.PROCESS_META_DATA_ID = $metaIdPh 
                        
                    UNION  
                    
                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_BP_EXPRESSION_PROCESS BP 
                        INNER JOIN META_BUSINESS_PROCESS_LINK PL ON PL.META_DATA_ID = BP.USE_META_DATA_ID   
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID       
                    WHERE BP.PROCESS_META_DATA_ID = $metaIdPh 
                        
                    UNION  
                    
                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_BP_EXPRESSION_PROCESS BP 
                        INNER JOIN META_BUSINESS_PROCESS_LINK PL ON PL.META_DATA_ID = BP.USE_META_DATA_ID   
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.SYSTEM_META_GROUP_ID        
                    WHERE BP.PROCESS_META_DATA_ID = $metaIdPh 
                        
                    UNION  
                    
                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_BP_EXPRESSION_PROCESS BP 
                        INNER JOIN META_BUSINESS_PROCESS_LINK PL ON PL.META_DATA_ID = BP.USE_META_DATA_ID   
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.REF_META_GROUP_ID         
                    WHERE BP.PROCESS_META_DATA_ID = $metaIdPh 
                        
                    UNION  
                    
                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_PROCESS_PARAM_ATTR_LINK BP 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = BP.LOOKUP_KEY_META_DATA_ID      
                    WHERE BP.PROCESS_META_DATA_ID = $metaIdPh 
                        
                    UNION  
                    
                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_PROCESS_WORKFLOW BP 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = BP.DO_BP_ID       
                    WHERE BP.MAIN_BP_ID = $metaIdPh 
                        
                    UNION  
                
                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_PROCESS_WORKFLOW BP 
                        INNER JOIN META_BUSINESS_PROCESS_LINK PL ON PL.META_DATA_ID = BP.DO_BP_ID  
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.SYSTEM_META_GROUP_ID  
                    WHERE BP.MAIN_BP_ID = $metaIdPh 
                        
                    UNION  
                    
                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_PROCESS_WORKFLOW BP 
                        INNER JOIN META_BUSINESS_PROCESS_LINK PL ON PL.META_DATA_ID = BP.DO_BP_ID  
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.REF_META_GROUP_ID   
                    WHERE BP.MAIN_BP_ID = $metaIdPh 
                ) TT 
                GROUP BY TT.META_DATA_ID, TT.META_TYPE_ID, TT.META_DATA_CODE";
            
        } elseif ($metaTypeId == Mdmetadata::$metaGroupMetaTypeId) {
            
            $sql = "
                SELECT 
                    TT.META_DATA_ID, TT.META_TYPE_ID, TT.META_DATA_CODE 
                FROM (
                    SELECT 
                        META_DATA_ID, META_TYPE_ID, META_DATA_CODE 
                    FROM META_DATA 
                    WHERE META_DATA_ID = $metaIdPh 

                    UNION  

                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_GROUP_LINK GL 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = GL.REF_META_GROUP_ID 
                    WHERE GL.META_DATA_ID = $metaIdPh 

                    UNION  

                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_GROUP_LINK GL 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = GL.REF_STRUCTURE_ID  
                    WHERE GL.META_DATA_ID = $metaIdPh 

                    UNION  

                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_GROUP_LINK GL 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = GL.LAYOUT_META_DATA_ID   
                    WHERE GL.META_DATA_ID = $metaIdPh 

                    UNION  

                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_GROUP_LINK GL 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = GL.CALCULATE_PROCESS_ID   
                    WHERE GL.META_DATA_ID = $metaIdPh 

                    UNION  

                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_GROUP_LINK GL 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = GL.QS_META_DATA_ID    
                    WHERE GL.META_DATA_ID = $metaIdPh 

                    UNION  

                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_GROUP_LINK GL 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = GL.QS_META_DATA_ID    
                    WHERE GL.META_DATA_ID = $metaIdPh 

                    UNION  

                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_GROUP_CONFIG GL 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = GL.LOOKUP_META_DATA_ID     
                    WHERE GL.MAIN_META_DATA_ID = $metaIdPh 

                    UNION  

                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_GROUP_CONFIG GL 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = GL.LOOKUP_KEY_META_DATA_ID     
                    WHERE GL.MAIN_META_DATA_ID = $metaIdPh 

                    UNION  

                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_GROUP_CONFIG GL 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = GL.PROCESS_META_DATA_ID      
                    WHERE GL.MAIN_META_DATA_ID = $metaIdPh 

                    UNION  

                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_GROUP_CONFIG GL 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = GL.REF_STRUCTURE_ID       
                    WHERE GL.MAIN_META_DATA_ID = $metaIdPh 

                    UNION  

                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_GROUP_CONFIG GL 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = GL.REF_STRUCTURE_ID 
                    WHERE GL.MAIN_META_DATA_ID = $metaIdPh 
                ) TT 
                GROUP BY TT.META_DATA_ID, TT.META_TYPE_ID, TT.META_DATA_CODE";
            
        } elseif ($metaTypeId && Mdmetadata::$statementMetaTypeId) {
            
            $sql = "
                SELECT 
                    TT.META_DATA_ID, TT.META_TYPE_ID, TT.META_DATA_CODE 
                FROM ( 
                    SELECT 
                        META_DATA_ID, META_TYPE_ID, META_DATA_CODE 
                    FROM META_DATA 
                    WHERE META_DATA_ID = $metaIdPh 

                    UNION  

                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_STATEMENT_LINK SL 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = SL.DATA_VIEW_ID  
                    WHERE SL.META_DATA_ID = $metaIdPh 

                    UNION  

                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_STATEMENT_LINK SL 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = SL.DASHBOARD_META_ID   
                    WHERE SL.META_DATA_ID = $metaIdPh 

                    UNION  

                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_STATEMENT_LINK SL 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = SL.GROUP_DATA_VIEW_ID    
                    WHERE SL.META_DATA_ID = $metaIdPh 

                    UNION  

                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_STATEMENT_LINK SL 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = SL.PROCESS_META_DATA_ID     
                    WHERE SL.META_DATA_ID = $metaIdPh 

                    UNION  

                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_STATEMENT_LINK SL 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = SL.ROW_DATA_VIEW_ID      
                    WHERE SL.META_DATA_ID = $metaIdPh 

                    UNION  

                    SELECT 
                        MD.META_DATA_ID, MD.META_TYPE_ID, MD.META_DATA_CODE 
                    FROM META_STATEMENT_TEMPLATE SL 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = SL.TRG_META_DATA_ID       
                    WHERE SL.SRC_META_DATA_ID = $metaIdPh 
                ) TT 
                GROUP BY TT.META_DATA_ID, TT.META_TYPE_ID, TT.META_DATA_CODE";
        }
        
        if (isset($sql)) {
            
            $metas = $this->db->GetAll(
                "SELECT 
                    TMP.*
                FROM ( 
                    SELECT 
                        MD.META_TYPE_ID, 
                        MD.META_DATA_ID, 
                        MD.META_DATA_CODE, 
                        ".$this->db->IfNull('UD.USERNAME', 'UM.USERNAME')." AS USER_NAME, 
                        ".$this->db->IfNull('MD.MODIFIED_DATE', 'MD.CREATED_DATE')." AS MODIFIED_DATE 
                    FROM ($sql) SMD 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = SMD.META_DATA_ID 
                        LEFT JOIN UM_USER US ON US.USER_ID = MD.CREATED_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = US.SYSTEM_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER UD ON UD.USER_ID = MD.MODIFIED_USER_ID 
                ) TMP 
                GROUP BY 
                    TMP.META_TYPE_ID, 
                    TMP.META_DATA_ID, 
                    TMP.META_DATA_CODE, 
                    TMP.USER_NAME, 
                    TMP.MODIFIED_DATE 
                ORDER BY TMP.META_TYPE_ID ASC", 
                array($metaId)
            );
        }
        
        if (isset($metas) && $metas) {
            
            includeLib('Compress/Compression');
            
            $isUseMetaUserId = Config::getFromCache('IS_USE_META_CREATED_MODIFIED_USER_ID');
            
            if ($isUseMetaUserId == '1') {
                self::$exportIgnoreColumns = ['EXPORT_SCRIPT', 'COPY_COUNT'];
            }
            
            $metaXml = null;
            
            foreach ($metas as $meta) {
                
                $metaResult = self::oneMetaModel($meta);
                
                if ($metaResult['status'] == 'success') {
                    $metaXml .= $metaResult['result'];
                }
            }
            
            if ($metaXml) {
                
                $xml = self::upgradeXmlHeader();

                $xml .= '<metas>' . "\n";
                    $xml .= $metaXml;
                $xml .= '</metas>' . "\n";

                $xml .= self::upgradeXmlFooter();

                $script = Compression::gzdeflate($xml);

                $result = array('status' => 'success', 'result' => $script);
            }
        } 
        
        return isset($result) ? $result : ['status' => 'error', 'message' => 'Татах өгөгдөл олдсонгүй!'];
    }
    
    public function getMetaUpgradeInfoModel($metaId) {
        
        $metaIdPh = $this->db->Param(0);
        
        $row = $this->db->GetRow("
            SELECT 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME, 
                UM.USERNAME, 
                UP.CREATED_DATE AS LAST_UPGRADE_DATE 
            FROM META_DATA MD 
                LEFT JOIN (
                    SELECT 
                        MAX(CREATED_DATE) AS CREATED_DATE, 
                        CREATED_USER_ID, 
                        META_DATA_ID 
                    FROM UM_META_UPGRADE_LOG 
                    WHERE META_DATA_ID = $metaIdPh 
                    GROUP BY CREATED_USER_ID, META_DATA_ID  
                ) UP ON UP.META_DATA_ID = MD.META_DATA_ID 
                LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = UP.CREATED_USER_ID 
            WHERE MD.META_DATA_ID = $metaIdPh", 
            array($metaId) 
        );
        
        return $row;
    }
    
    public function sysUpdateAccessByPassModel() {
        
        $password = Input::post('ps');
        
        if (Hash::createMD5reverse($password) == '67a4ae7ee4b7f12fd2c9a4a8f4bc0ee1') {
            return true;
        }
        
        return false;
    }
    
    public function sysUpdateModel() {
        
        $codeNames    = Input::post('codeNames');
        $codeNamesArr = explode(',', $codeNames);
        
        if (is_countable($codeNamesArr) && count($codeNamesArr) > 0) {
            
            try {
                
                $isPhpUpdate = false;
                
                foreach ($codeNamesArr as $codeName) {
                
                    if ($codeName == 'frontend-full') {
                        
                        $isPhpUpdate = true;
                        shell_exec('sh /home/update/php.sh full');
                        
                    } elseif ($codeName == 'frontend-assets') {
                        
                        $isPhpUpdate = true;
                        shell_exec('sh /home/update/php.sh assets');
                        
                    } elseif ($codeName == 'frontend-helper') {
                        
                        $isPhpUpdate = true;
                        shell_exec('sh /home/update/php.sh helper');
                        
                    } elseif ($codeName == 'frontend-libs') {
                        
                        $isPhpUpdate = true;
                        shell_exec('sh /home/update/php.sh libs');
                        
                    } elseif ($codeName == 'frontend-middleware') {
                        
                        $isPhpUpdate = true;
                        shell_exec('sh /home/update/php.sh middleware');
                        
                    } elseif ($codeName == 'backend') {
                        
                        shell_exec('sh /home/update/java.sh');
                    }
                }
                
                if ($isPhpUpdate && function_exists('opcache_reset')) {
                    opcache_reset();
                }
                
                $response = array('status' => 'success', 'message' => 'Амжилттай шинэчлэлээ');
            
            } catch (Exception $ex) {
                $response = array('status' => 'error', 'message' => $ex->getMessage());
            } 
            
        } else {
            $response = array('status' => 'error', 'message' => 'Empty');
        }
        
        return $response;
    }
    
    public function metaConfigReplaceModel() {
        
        $sourceId = Input::numeric('sourceId');
        $targetId = Input::numeric('targetId');
        
        if ($sourceId && $targetId) {
            
            $metaIdPh = $this->db->Param(0);
            
            $meta = $this->db->GetRow("
                SELECT 
                    MD.META_DATA_ID, 
                    MD.META_TYPE_ID, 
                    MD.META_DATA_CODE, 
                    ".$this->db->IfNull('UD.USERNAME', 'UM.USERNAME')." AS USER_NAME, 
                    ".$this->db->IfNull('MD.MODIFIED_DATE', 'MD.CREATED_DATE')." AS MODIFIED_DATE 
                FROM META_DATA MD 
                    LEFT JOIN UM_USER US ON US.USER_ID = MD.CREATED_USER_ID 
                    LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = US.SYSTEM_USER_ID 
                    LEFT JOIN UM_SYSTEM_USER UD ON UD.USER_ID = MD.MODIFIED_USER_ID 
                WHERE MD.META_DATA_ID = $metaIdPh", 
                array($targetId) 
            );
            
            if ($meta) {
                
                $isUseMetaUserId = Config::getFromCache('IS_USE_META_CREATED_MODIFIED_USER_ID');
            
                if ($isUseMetaUserId == '1') {
                    self::$exportIgnoreColumns = ['EXPORT_SCRIPT', 'COPY_COUNT'];
                }
            
                self::$isIgnoreMetaFolder = true;
                self::$isIgnoreTranslate = true;
                self::$isIdReplace = true;
                
                $metaResult = self::oneMetaModel($meta);

                if ($metaResult['status'] == 'success') {
                    
                    $result = $metaResult['result'];
                    
                    if ($sourceCode = Input::post('sourceCode')) {
                        $sourceMetaRow = array('META_DATA_CODE' => $sourceCode, 'META_DATA_NAME' => Input::post('sourceName'), 'META_GROUP_LINK_ID' => null);
                    } else {
                        $sourceMetaRow = self::getMetaColumnDatas($sourceId);
                    }
                    
                    $targetMetaRow = self::getMetaColumnDatas($targetId);
                    
                    $sourceMetaCode = $sourceMetaRow['META_DATA_CODE'];
                    $targetMetaCode = $targetMetaRow['META_DATA_CODE'];
                    $sourceMetaName = $sourceMetaRow['META_DATA_NAME'];
                    $targetMetaName = $targetMetaRow['META_DATA_NAME'];
                    
                    $targetMetaGroupLinkId = $sourceMetaGroupLinkId = '';
                    
                    $result = str_replace(
                        array(
                            ' '. $targetId . ')', '('. $targetId . ',', ' '. $targetId . ',', '('. $targetId . ')',
                            ' = '. $targetId . Mdcommon::$separator, '"'.$targetId . '"', '"'.$targetMetaCode.'"', "'".$targetMetaCode."'", 
                            '"'.$targetMetaName.'"', "'".$targetMetaName."'"
                        ), 
                        array(
                            ' '. $sourceId . ')', '('. $sourceId . ',', ' '. $sourceId . ',', '('. $sourceId . ')', 
                            ' = '. $sourceId . Mdcommon::$separator, '"'.$sourceId . '"', '"'.$sourceMetaCode.'"', "'".$sourceMetaCode."'", 
                            '"'.$sourceMetaName.'"', "'".$sourceMetaName."'"
                        ),
                        $result
                    );
                    
                    if ($meta['META_TYPE_ID'] == Mdmetadata::$metaGroupMetaTypeId) {
                        
                        $sourceMetaGroupLinkId = $sourceMetaRow['META_GROUP_LINK_ID'] ? $sourceMetaRow['META_GROUP_LINK_ID'] : getUID();
                        $targetMetaGroupLinkId = $targetMetaRow['META_GROUP_LINK_ID'] ? $targetMetaRow['META_GROUP_LINK_ID'] : getUID();
                    
                        $result = str_replace(
                            array(
                                '"'.$targetMetaGroupLinkId.'"', 
                                '('. $targetMetaGroupLinkId . ',', 
                                ' '. $targetMetaGroupLinkId . ')', 
                                ' '. $targetMetaGroupLinkId . ',', 
                                ' = '. $targetMetaGroupLinkId . Mdcommon::$separator
                            ), 
                            array(
                                '"'.$sourceMetaGroupLinkId.'"', 
                                '('. $sourceMetaGroupLinkId . ',', 
                                ' '. $sourceMetaGroupLinkId . ')', 
                                ' '. $sourceMetaGroupLinkId . ',', 
                                ' = '. $sourceMetaGroupLinkId . Mdcommon::$separator
                            ),
                            $result
                        );
                        
                    } elseif ($meta['META_TYPE_ID'] == Mdmetadata::$reportTemplateMetaTypeId) {
                        
                        $result = str_replace('/'.$targetId.'.html', '/'.$sourceId.'.html', $result);
                    }
                    
                    if (self::$replaceIds) {
                        
                        $sourceReplaceIds = array();
                        $targetReplaceIds = array();
                        
                        foreach (self::$replaceIds as $k => $oldId) {
                            
                            $newId = getUIDAdd($k);
                            
                            $targetReplaceIds[] = ' '. $oldId . ')';
                            $targetReplaceIds[] = '('. $oldId . ')';
                            $targetReplaceIds[] = ' '. $oldId . ',';
                            $targetReplaceIds[] = '('. $oldId . ',';
                            $targetReplaceIds[] = '"'. $oldId . '"';
                            $targetReplaceIds[] = ' = '. $oldId . Mdcommon::$separator;
                            
                            $sourceReplaceIds[] = ' '. $newId . ')';
                            $sourceReplaceIds[] = '('. $newId . ')';
                            $sourceReplaceIds[] = ' '. $newId . ',';
                            $sourceReplaceIds[] = '('. $newId . ',';
                            $sourceReplaceIds[] = '"'. $newId . '"';
                            $sourceReplaceIds[] = ' = '. $newId . Mdcommon::$separator;
                        }
                        
                        $result = str_replace($targetReplaceIds, $sourceReplaceIds, $result);
                    }
                    
                    $xml = self::upgradeXmlHeader();

                        $xml .= '<metas>' . "\n";
                            $xml .= $result;
                        $xml .= '</metas>' . "\n";

                    $xml .= self::upgradeXmlFooter();
                    
                    if (self::$isMetaImportCopy == false) {
                        
                        $response = self::executeUpgradeScript(array($xml));
                        
                    } else {
                        $response = array(
                            'status' => 'success', 
                            'metaTypeId' => $meta['META_TYPE_ID'], 
                            'metaCode' => $meta['META_DATA_CODE'], 
                            'oldMetaGroupLinkId' => $targetMetaGroupLinkId,
                            'newMetaGroupLinkId' => $sourceMetaGroupLinkId,
                            'xmlData' => $xml
                        );
                    }
                    
                } else {
                    $response = array('status' => 'error', 'message' => 'Unsuccessfully');
                }
                
            } else {
                $response = array('status' => 'error', 'message' => 'Not found: '.$targetId);
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid id!');
        }
        
        return $response;
    }
    
    public function getMetaColumnDatas($metaId) {
        
        $row = $this->db->GetRow("
            SELECT 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME, 
                GL.ID AS META_GROUP_LINK_ID 
            FROM META_DATA MD 
                LEFT JOIN META_GROUP_LINK GL ON GL.META_DATA_ID = MD.META_DATA_ID
            WHERE MD.META_DATA_ID = ".$this->db->Param(0), 
            array($metaId)
        );
        
        return $row;
    }
    
    public function knowMetasInFileModel() {
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        includeLib('Compress/Compression');
        
        $totalFile   = count($_FILES['import_file']['name']);
        $fileSources = array();
        $isMetaImportCopy = Input::numeric('isMetaImportCopy');
        
        for ($i = 0; $i < $totalFile; $i++) {
            
            if ($_FILES['import_file']['error'][$i] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['import_file']['tmp_name'][$i])) {
                
                $fileContent = Compression::gzinflate(file_get_contents($_FILES['import_file']['tmp_name'][$i]));
                
                if ($fileContent && strpos($fileContent, '<meta id="') !== false) {
                    
                    if ($isMetaImportCopy) {
                        
                        if (strpos($fileContent, 'typeId="'.Mdmetadata::$businessProcessMetaTypeId.'"') !== false 
                            || strpos($fileContent, 'typeId="'.Mdmetadata::$metaGroupMetaTypeId.'"') !== false 
                            || strpos($fileContent, 'typeId="'.Mdmetadata::$statementMetaTypeId.'"') !== false) {

                            $fileSources[$_FILES['import_file']['name'][$i]] = $fileContent;
                        }
                    
                    } else {
                        $fileSources[$_FILES['import_file']['name'][$i]] = $fileContent;
                    }
                } 
            }
        }
        
        if ($fileSources) {
            
            $metaTypes = array();
            $dbMetaTypes = $this->db->GetAll("SELECT META_TYPE_ID, META_TYPE_NAME FROM META_TYPE");
            
            foreach ($dbMetaTypes as $metaType) {
                $metaTypes[$metaType['META_TYPE_ID']] = $metaType['META_TYPE_NAME'];
            }
            
            $idPh = $this->db->Param(0);
            $data = array();
            
            foreach ($fileSources as $fileName => $fileSource) {

                $decryptedArray = Xml::createArray($fileSource);

                if (isset($decryptedArray['documents']['metas']['meta'])) {

                    $metas = $decryptedArray['documents']['metas']['meta'];

                    if (!array_key_exists(0, $metas)) {
                        $metas = array($metas);
                    }
                    
                    if ($isMetaImportCopy && count($metas) > 1) {
                        return array('status' => 'error', 'message' => 'Зөвхөн нэг метаны хувьд ажиллана!');
                    }

                    foreach ($metas as $meta) {

                        $metaAttributes = $meta['@attributes'];

                        $metaDataId   = $metaAttributes['id'];
                        $metaTypeId   = $metaAttributes['typeId'];
                        $metaCode     = $metaAttributes['code'];
                        $userName     = issetParam($metaAttributes['userName']);
                        $modifiedDate = issetParam($metaAttributes['modifiedDate']);
                        $metaName = $folderId = $folderCode = $folderName = '';
                        
                        if ($isMetaImportCopy) {
                            
                            $metaRow = $this->db->GetRow("
                                SELECT 
                                    MD.META_DATA_ID, 
                                    MD.META_DATA_NAME, 
                                    FF.FOLDER_ID, 
                                    FF.FOLDER_CODE, 
                                    FF.FOLDER_NAME 
                                FROM META_DATA MD 
                                    LEFT JOIN META_DATA_FOLDER_MAP FM ON FM.META_DATA_ID = MD.META_DATA_ID 
                                    LEFT JOIN FVM_FOLDER FF ON FF.FOLDER_ID = FM.FOLDER_ID 
                                WHERE MD.META_DATA_ID = ".$idPh, array($metaDataId));
                            
                            if ($metaRow) {
                                $isMetaCreated = true;
                                $metaName = $metaRow['META_DATA_NAME'];
                                $folderId = $metaRow['FOLDER_ID'];
                                $folderCode = $metaRow['FOLDER_NAME'];
                                $folderName = $metaRow['FOLDER_NAME'];
                            } else {
                                $isMetaCreated = false;
                            }
                            
                        } else {
                            $isMetaCreated = $this->db->GetOne("SELECT META_DATA_ID FROM META_DATA WHERE META_DATA_ID = ".$idPh, array($metaDataId));
                        }
                        
                        $data[] = array(
                            'fileName'      => $fileName, 
                            'metaId'        => $metaDataId, 
                            'metaCode'      => $metaCode, 
                            'metaName'      => $metaName, 
                            'metaType'      => issetDefaultVal($metaTypes[$metaTypeId], $metaTypeId), 
                            'folderId'      => $folderId, 
                            'folderCode'    => $folderCode, 
                            'folderName'    => $folderName, 
                            'userName'      => $userName, 
                            'modifiedDate'  => $modifiedDate, 
                            'isMetaCreated' => $isMetaCreated ? true : false
                        );
                    }
                }
            }
            
            $response = array('status' => 'success', 'metaList' => $data);
            
        } else {
            $response = array('status' => 'error', 'message' => 'Файлаас шинэчлэлтийн дата олдсонгүй!');
        }
        
        return $response;
    }
    
    public function updatePatchMetaBugFixModel($rows) {
        
        if (isset($rows[0]['id'])) {
            
            $alreadyBugFix = $selectedRows = array();
            
            foreach ($rows as $row) {
                
                $bugFixId = $row['id'];
                
                if (!isset($alreadyBugFix[$bugFixId])) {
                    
                    $result = self::downloadBugFixingModel($bugFixId);

                    if ($result['status'] == 'success') {
                        $this->db->UpdateClob('META_BUG_FIXING', 'PATCH', $result['result'], 'ID = '.$bugFixId);
                    }
                    
                    $alreadyBugFix[$bugFixId] = 1;
                    $selectedRows[] = array('id' => $bugFixId);
                }
            }
            
            if ($alreadyBugFix) {
                
                $_POST['id'] = 'id';
                $_POST['objectCode'] = 'bugfix';
                $_POST['selectedRows'] = $selectedRows;
                
                self::$ignoreDeleteScript = false;
                $bugFixExport = self::exportObjectModel();
                
                if ($bugFixExport['status'] == 'success') {
                    
                    $url = 'https://qa.veritech.mn/mdupgrade/encryptedFileImport';
                    $response = $this->ws->curlRequest($url, array('encryptedSource' => $bugFixExport['result']));
                    
                } else {
                    $response = $bugFixExport;
                }
                
            } else {
                $response = array('status' => 'error', 'message' => 'Шинэчлэх өгөгдөл олдсонгүй! /005/');
            }
        }
        
        return $response;
    }
    
    public function importPatchMetaBugFixModel($rows) {
        
        $isDev = Config::getFromCache('is_dev');
        
        if (!$isDev && isset($rows[0]['id'])) {
            
            set_time_limit(0);
            ini_set('memory_limit', '-1');
        
            $n = 1;
            $alreadyBugFix = $customerBugFixedIds = array();
            
            foreach ($rows as $row) {
                
                $bugFixId = $row['id'];
                
                if (!isset($alreadyBugFix[$bugFixId])) {
                    
                    $result = self::downloadBugFixingModel($bugFixId);

                    if ($result['status'] == 'success') {
                        
                        $currentDate = Date::currentDate('Y-m-d H:i:s');

                        $fixedData = array(
                            'ID'                 => getUIDAdd($n), 
                            'META_BUG_FIXING_ID' => $bugFixId, 
                            'CREATED_USER_ID'    => Ue::sessionUserKeyId(), 
                            'CREATED_DATE'       => $currentDate, 
                            'MODIFIED_DATE'      => $currentDate, 
                            'STATUS_ID'          => 1, 
                            'DESCRIPTION'        => issetParam($row['newWfmDescription'])
                        );

                        $this->db->AutoExecute('CUSTOMER_BUG_FIXED', $fixedData);
                        $this->db->UpdateClob('CUSTOMER_BUG_FIXED', 'OLD_PATCH', $result['result'], 'ID = '.$fixedData['ID']);
                        
                        $customerBugFixedIds[] = $fixedData['ID'];
                    }
                    
                    $alreadyBugFix[$bugFixId] = $bugFixId;
                    
                    $n ++;
                }
            }
            
            if ($alreadyBugFix) {
                
                includeLib('Compress/Compression');
                
                $updateSources = array();
                $ids = Arr::implode_r(',', $alreadyBugFix, true);
                
                $patchData = $this->db->GetAll("SELECT PATCH FROM META_BUG_FIXING WHERE ID IN ($ids) AND PATCH IS NOT NULL");
                
                foreach ($patchData as $patchRow) {
                    
                    $updateContent = Compression::gzinflate($patchRow['PATCH']);
                    
                    if ($updateContent && strpos($updateContent, '<meta id="') !== false) {
                        $updateSources[] = $updateContent;
                    }
                }
                
                if ($updateSources) {
                    $response = self::executeUpgradeScript($updateSources);
                } else {
                    $response = array('status' => 'error', 'message' => 'Шинэчлэх өгөгдөл олдсонгүй! /006/');
                }
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Шинэчлэх тохиргоо олдсонгүй! /007/');
        }
        
        if ($customerBugFixedIds && $response['status'] != 'success') {
            $this->db->Execute("DELETE FROM CUSTOMER_BUG_FIXED WHERE ID IN (".Arr::implode_r(',', $customerBugFixedIds, true).")");
        }
        
        return $response;
    }
    
    public function metaPatchRollbackModel() {
        
        try {
            
            $id = Input::numeric('id');
            
            if (!$id) {
                
                throw new Exception('Invalid id!');
                
            } else {
                
                $row = $this->db->GetRow("SELECT OLD_PATCH FROM CUSTOMER_BUG_FIXED WHERE ID = ".$this->db->Param(0), array($id));
                $oldPatch = issetParam($row['OLD_PATCH']);
                
                if ($oldPatch) {
                    
                    includeLib('Compress/Compression');
                    
                    $updateContent = Compression::gzinflate($oldPatch);
                    
                    if ($updateContent && strpos($updateContent, '<meta id="') !== false) {
                        
                        $response = self::executeUpgradeScript(array($updateContent));
                        
                    } else {
                        throw new Exception('Зөв Old patch олдсонгүй!');
                    }
                
                } else {
                    throw new Exception('Old patch олдсонгүй!');
                }
            }
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function sendPatchMetaBugFixModel($rows) {
        
        $isDev = Config::getFromCache('is_dev');
        
        if (!$isDev && isset($rows[0]['id'])) {
            
            $domains = Config::getFromCache('metaPatchSendToDomains');
            
            if ($domains) {
                
                set_time_limit(0);
                ini_set('memory_limit', '-1');
                
                $domains = explode(',', $domains);
                $alreadyBugFix = array();

                foreach ($rows as $row) {

                    $bugFixId = $row['id'];

                    if (!isset($alreadyBugFix[$bugFixId])) {

                        $row = $this->db->GetRow("SELECT PATCH FROM META_BUG_FIXING WHERE ID = ".$this->db->Param(0), array($bugFixId));
                        $patch = issetParam($row['PATCH']);

                        if ($patch) {
                            
                            $_POST['id'] = 'id';
                            $_POST['objectCode'] = 'bugfix';
                            $_POST['selectedRows'] = array(array('id' => $bugFixId));

                            self::$ignoreDeleteScript = false;
                            $bugFixExport = self::exportObjectModel();

                            if ($bugFixExport['status'] == 'success') {
                                
                                foreach ($domains as $domain) {
                                
                                    $domain = rtrim($domain, '/');
                                    $url = $domain. '/mdupgrade/encryptedFileImport';

                                    $this->ws->curlQueue($url, array(
                                            'bugFixId' => $bugFixId, 
                                            'encryptedSource' => $patch, 
                                            'bugFixEncryptedSource' => $bugFixExport['result']
                                        )
                                    );
                                }
                                
                                $response = array('status' => 'success');
                            } else {
                                $response = $bugFixExport;
                            }
                        }

                        $alreadyBugFix[$bugFixId] = $bugFixId;
                    }
                }
                
                if (!$alreadyBugFix) {
                    $response = array('status' => 'error', 'message' => 'Шинэчлэх өгөгдөл олдсонгүй! /002/');
                }
                
            } else {
                $response = array('status' => 'error', 'message' => 'Шинэчлэх тохиргоо олдсонгүй! /003/');
            }
        } else {
            $response = array('status' => 'error', 'message' => 'Шинэчлэх тохиргоо олдсонгүй! /004/');
        }
        
        return true;
    }
    
    public function metaPatchImportModel() {
        
        try {
            
            $bugFixId = Input::numeric('id');
        
            $exportData = Mdupgrade::getBugfixDataByCommand('downloadObject', ['objectCode' => 'bugfix', 'ids' => $bugFixId]);

            if ($exportData['status'] == 'success' && isset($exportData['result'])) {

                includeLib('Compress/Compression');

                $fileContent = Compression::gzinflate($exportData['result']);

                if ($fileContent && strpos($fileContent, '<meta id="') === false) {
                    return ['status' => 'error', 'message' => 'PHP export хийсэн файл уншуулна уу!', 'logs' => ''];
                } 

                $response = self::executeUpgradeScript([$fileContent]);

                if ($response['status'] == 'success') {
                    
                    $isOnlyLastLoadLog = Config::getFromCache('PF_IS_PATCH_LOAD_ONLY_LAST_LOG');
                    $currentDate       = Date::currentDate('Y-m-d H:i:s');

                    $fixedData = [
                        'ID'                 => getUID(), 
                        'META_BUG_FIXING_ID' => $bugFixId, 
                        'CREATED_USER_ID'    => Ue::sessionUserKeyId(), 
                        'STATUS_ID'          => 1, 
                        'CREATED_DATE'       => $currentDate, 
                        'MODIFIED_DATE'      => $currentDate
                    ];

                    $dbResult = $this->db->AutoExecute('CUSTOMER_BUG_FIXED', $fixedData);

                    if ($dbResult) {
                        
                        self::$isCreateRollback = true;
                        $clobResult = true;
                        
                        if ($isOnlyLastLoadLog) {
                            
                            $this->db->Execute("DELETE FROM CUSTOMER_BUG_FIXED WHERE META_BUG_FIXING_ID = $bugFixId AND ID <> ".$fixedData['ID']);
                            
                        } else {
                        
                            $result = self::downloadBugFixingModel($bugFixId);
                            $oldPatch = isset($result['result']) ? $result['result'] : null;

                            if ($oldPatch) {
                                $clobResult = $this->db->UpdateClob('CUSTOMER_BUG_FIXED', 'OLD_PATCH', $oldPatch, 'ID = '.$fixedData['ID']);
                            } 
                        }

                        if ($clobResult) {

                            $exportData = Mdupgrade::getBugfixDataByCommand('download', ['ids' => $bugFixId]);

                            if ($exportData['status'] == 'success' && isset($exportData['result'])) {

                                $fileContent = Compression::gzinflate($exportData['result']);

                                if ($fileContent && strpos($fileContent, '<meta id="') === false) {
                                    return ['status' => 'error', 'message' => 'PHP export хийсэн файл уншуулна уу!', 'logs' => ''];
                                } 

                                $response = self::executeUpgradeScript([$fileContent]);
                                
                                $status = issetDefaultVal($response['status'], 'success');
                                $message = issetDefaultVal($response['message'], 'Амжилттай');

                                return ['status' => $status, 'message' => $message, 'logs' => issetParam($response['logs'])];
                            } else {
                                return $exportData;
                            }
                        }
                    }
                    
                } else {
                    return ['status' => 'error', 'message' => $response['message']];
                }
                
            } else {
                return ['status' => 'error', 'message' => $exportData['message']];
            }
            
            return ['status' => 'error', 'message' => 'Error!'];
        
        } catch (Exception $ex) {
            return ['status' => 'error', 'message' => $ex->getMessage()];
        }
    }
    
    public function metaImportCopyFileModel() {
        
        if (!Mdmeta::isAccessMetaImport()) {
            return array('status' => 'error', 'message' => 'Импорт хийх боломжгүй байна!');
        }
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        includeLib('Compress/Compression');
        
        $totalFile   = count($_FILES['import_file']['name']);
        $fileSources = array();
        
        for ($i = 0; $i < $totalFile; $i++) {
            
            if ($_FILES['import_file']['error'][$i] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['import_file']['tmp_name'][$i])) {
                
                $fileContent = Compression::gzinflate(file_get_contents($_FILES['import_file']['tmp_name'][$i]));
                
                if ($fileContent && strpos($fileContent, '<meta id="') !== false) {
                    
                    if (strpos($fileContent, 'typeId="'.Mdmetadata::$businessProcessMetaTypeId.'"') !== false 
                        || strpos($fileContent, 'typeId="'.Mdmetadata::$metaGroupMetaTypeId.'"') !== false 
                        || strpos($fileContent, 'typeId="'.Mdmetadata::$statementMetaTypeId.'"') !== false) {
                        
                        $fileSources[] = $fileContent;
                    }
                    
                } else {
                    return array('status' => 'error', 'message' => 'PHP export хийсэн файл уншуулна уу!');
                }
            }
        }
        
        if ($fileSources) {
            
            self::$ignoreDbCommitTrans = true;
            self::$isMetaImportCopy = true;
            
            $response = self::executeUpgradeScript($fileSources);
            
            if ($response['status'] == 'success') {
                
                $copyMetaDataId = $response['metaDataId'];
                
                $newMetaId      = getUID();
                $newMetaCode    = Input::post('newMetaCode');
                $newMetaName    = Input::post('newMetaName');
                
                $this->db->Execute("INSERT INTO META_DATA (META_DATA_ID, META_DATA_CODE, META_DATA_NAME) VALUES ($newMetaId, '$newMetaCode', '$newMetaName')");
                
                $_POST['sourceId'] = $newMetaId;
                $_POST['targetId'] = $copyMetaDataId;
                
                $copyResult = self::metaConfigReplaceModel();
                
                if ($copyResult['status'] == 'success') {
                    $xmlData = $copyResult['xmlData'];
                } else {
                    $response = $copyResult;
                }
                
                $this->db->RollbackTrans();
                
                if (isset($xmlData)) {
                    
                    self::$ignoreDbCommitTrans = false;
                    self::$isMetaImportCopy = false;
            
                    $response = self::executeUpgradeScript(array($xmlData));
                    
                    if ($response['status'] != 'success') {
                        
                        $response['message'] = 'new copy: ' . $response['message'];
                        
                    } else {
                        
                        $folderId = Input::numeric('folderId');
                        
                        if ($folderId) {
                            
                            $metaDataFolderMap = array(
                                'ID'           => getUID(), 
                                'FOLDER_ID'    => $folderId, 
                                'META_DATA_ID' => $newMetaId,
                                'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s')
                            );
                            $this->db->AutoExecute('META_DATA_FOLDER_MAP', $metaDataFolderMap);

                            $response['folderId'] = $folderId;
                        }
                    }
                }
            } 
            
        } else {
            $response = array('status' => 'error', 'message' => 'Файлаас шинэчлэлтийн дата олдсонгүй! /Only Process, Metagroup, Statement/');
        }
        
        return $response;
    }
    
    public function metaCopyReplaceModel() {
        
        if (!Mdmeta::isAccessMetaImport()) {
            return array('status' => 'error', 'message' => 'Энэ үйлдэл боломжгүй байна!');
        }
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        $newMetaCode = Input::post('newMetaCode');
        
        if ($this->model->checkMetaDataCodeModel($newMetaCode)) {
            return array('status' => 'error', 'message' => 'Шинэ код давхардаж байна!');
        }
        
        includeLib('Compress/Compression');
        $this->load->model('mdupgrade', 'middleware/models/');
        
        $copyMetaDataId = Input::numeric('copyMetaDataId');
        $newMetaId      = Input::numeric('newMetaId');
        $newMetaName    = Input::post('newMetaName');
        
        $_POST['sourceId']   = $newMetaId;
        $_POST['sourceCode'] = $newMetaCode;
        $_POST['sourceName'] = $newMetaName;
        $_POST['targetId']   = $copyMetaDataId;
        
        self::$isMetaImportCopy = true;
        
        $copyResult = self::metaConfigReplaceModel();

        if ($copyResult['status'] == 'success') {
            $xmlData = $copyResult['xmlData'];
        } else {
            $response = $copyResult;
        }

        if (isset($xmlData)) {

            self::$ignoreDbCommitTrans = true;
            self::$isMetaImportCopy = false;

            $response = self::executeUpgradeScript(array($xmlData));

            if ($response['status'] != 'success') {
                
                $this->db->RollbackTrans();
                $response['message'] = 'new copy: ' . $response['message'];
                
            } else {
                
                $newMetaType = $copyResult['metaTypeId'];
                $folderId    = Input::numeric('folderId');
                $isMetaCopyReplace = true;
                
                try {
                        
                    if ($folderId) {

                        $metaDataFolderMap = array(
                            'ID'           => getUID(), 
                            'FOLDER_ID'    => $folderId, 
                            'META_DATA_ID' => $newMetaId, 
                            'CREATED_DATE' => Date::currentDate()
                        );
                        $this->db->AutoExecute('META_DATA_FOLDER_MAP', $metaDataFolderMap);
                    }
                    
                    if ($isMetaCopyReplace) {
                        
                        $replaceResult = self::oldMetaNewMetaReplace(array(
                            'metaTypeId'  => $newMetaType, 
                            'oldMetaId'   => $copyMetaDataId, 
                            'newMetaId'   => $newMetaId, 
                            'oldMetaCode' => $copyResult['metaCode'], 
                            'newMetaCode' => $newMetaCode, 
                            'oldMetaGroupLinkId' => $copyResult['oldMetaGroupLinkId'], 
                            'newMetaGroupLinkId' => $copyResult['newMetaGroupLinkId']
                        ));

                        if ($replaceResult['status'] != 'success') {
                            throw new Exception($replaceResult['message']);
                        }

                        if (self::$deleteScript) {

                            $deleteScriptArr = explode(Mdcommon::$separator, self::$deleteScript);

                            foreach ($deleteScriptArr as $deleteScript) {

                                $deleteScript = trim($deleteScript);

                                if ($deleteScript) {

                                    if (DB_DRIVER == 'postgres9') {
                                        $deleteScript = 'DO $$ BEGIN '.$deleteScript.'; EXCEPTION WHEN others THEN END; $$;';
                                    }

                                    $this->db->Execute($deleteScript);
                                }
                            }
                        }
                    }
                    
                    $logData = array(
                        'ID'               => getUID(),
                        'META_DATA_ID'     => $copyMetaDataId, 
                        'NEW_META_DATA_ID' => $newMetaId,
                        'CREATED_USER_ID'  => Ue::sessionUserId(),
                        'CREATED_DATE'     => Date::currentDate(),
                        'IS_IMPORT'        => 2
                    );
                    $this->db->AutoExecute('CUSTOMER_META_COPY_LOG', $logData);
                    
                    /*$this->db->Execute("DELETE FROM META_DATA_FOLDER_MAP WHERE META_DATA_ID = $copyMetaDataId");
                    $metaDataFolderMap = array(
                        'ID'           => getUID(), 
                        'FOLDER_ID'    => 999, 
                        'META_DATA_ID' => $copyMetaDataId, 
                        'CREATED_DATE' => Date::currentDate()
                    );
                    $this->db->AutoExecute('META_DATA_FOLDER_MAP', $metaDataFolderMap);*/

                    $this->db->CommitTrans();
                    
                    if ($newMetaType == Mdmetadata::$businessProcessMetaTypeId) {

                        (new Mdmeta())->bpParamsClearCache($copyMetaDataId, $copyResult['metaCode'], true);

                    } elseif ($newMetaType == Mdmetadata::$metaGroupMetaTypeId) {

                        (new Mdmeta())->dvCacheClearByMetaId($copyMetaDataId, true);
                    }
                    
                    $response = array('status' => 'success', 'message' => 'Амжилттай!', 'folderId' => $folderId);
                
                } catch (Exception $ex) {
                    
                    $this->db->RollbackTrans();
                    $response = array('status' => 'error', 'message' => $ex->getMessage());
                }
            }
        }
        
        return $response;
    }
    
    public function oldMetaNewMetaReplace($param) {
        
        try {
            
            $metaTypeId  = $param['metaTypeId'];
            $oldMetaId   = $param['oldMetaId'];
            $newMetaId   = $param['newMetaId'];
            $oldMetaCode = $param['oldMetaCode'];
            $newMetaCode = $param['newMetaCode'];
            
            $oldMetaCodeLower = Str::lower($oldMetaCode);
            
            $idPh1 = $this->db->Param(0);
            $idPh2 = $this->db->Param(1);
            
            $bindIds = [$newMetaId, $oldMetaId];
            
            if (Mdmetadata::$metaGroupMetaTypeId == $metaTypeId) { 
                
                $oldMetaGroupLinkId = $param['oldMetaGroupLinkId'];
                $newMetaGroupLinkId = $param['newMetaGroupLinkId'];
                
                $this->db->Execute("UPDATE CUSTOMER_DV_FILTER SET DV_META_DATA_ID = $idPh1 WHERE DV_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE CUSTOMER_DV_HDR_FTR SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE CUSTOMER_DV_IGNORE_LOAD SET DV_META_DATA_ID = $idPh1 WHERE DV_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE CUSTOMER_TEMPLATE SET DATA_VIEW_ID = $idPh1 WHERE DATA_VIEW_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE CUSTOMER_TEMPLATE_MAP SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_BUSINESS_PROCESS_LINK SET REF_META_GROUP_ID = $idPh1 WHERE REF_META_GROUP_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_BUSINESS_PROCESS_LINK SET SYSTEM_META_GROUP_ID = $idPh1 WHERE SYSTEM_META_GROUP_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_BUSINESS_PROCESS_LINK SET COMMENT_STRUCTURE_ID = $idPh1 WHERE COMMENT_STRUCTURE_ID = $idPh2", $bindIds);

                $this->db->Execute("UPDATE META_CARD SET DATA_VIEW_ID = $idPh1 WHERE DATA_VIEW_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_CARD SET CHART_DATA_VIEW_ID = $idPh1 WHERE CHART_DATA_VIEW_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_DATAMART_COLUMN SET META_GROUP_ID = $idPh1 WHERE META_GROUP_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_DM_PROCESS_DTL SET AUTO_MAP_DATAVIEW_ID = $idPh1 WHERE AUTO_MAP_DATAVIEW_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_GROUP_CONFIG_USER SET MAIN_META_DATA_ID = $idPh1 WHERE MAIN_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_GROUP_CRITERIA_TEMPLATE SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_GROUP_LINK SET REF_META_GROUP_ID = $idPh1 WHERE REF_META_GROUP_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_GROUP_LINK SET DATA_LEGEND_DV_ID = $idPh1 WHERE DATA_LEGEND_DV_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_GROUP_LINK SET QS_META_DATA_ID = $idPh1 WHERE QS_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_GROUP_LINK SET EXTERNAL_META_DATA_ID = $idPh1 WHERE EXTERNAL_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_GROUP_LINK SET BANNER_META_DATA_ID = $idPh1 WHERE BANNER_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_GROUP_CONFIG SET LOOKUP_KEY_META_DATA_ID = $idPh1 WHERE LOOKUP_KEY_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_GROUP_CONFIG SET REF_STRUCTURE_ID = $idPh1 WHERE REF_STRUCTURE_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_GROUP_CONFIG SET LOOKUP_META_DATA_ID = $idPh1 WHERE LOOKUP_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_GROUP_PARAM_CONFIG SET LOOKUP_META_DATA_ID = $idPh1 WHERE LOOKUP_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_GROUP_PARAM_CONFIG SET PARAM_META_DATA_ID = $idPh1 WHERE PARAM_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_GROUP_PRINT_USER SET DV_META_DATA_ID = $idPh1 WHERE DV_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_GROUP_RELATION SET SRC_META_GROUP_ID = $idPh1 WHERE SRC_META_GROUP_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_GROUP_RELATION SET TRG_META_GROUP_ID = $idPh1 WHERE TRG_META_GROUP_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_LAYOUT_LINK SET CRITERIA_DATA_VIEW_ID = $idPh1 WHERE CRITERIA_DATA_VIEW_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_PARAM_VALUES SET LOOKUP_META_DATA_ID = $idPh1 WHERE LOOKUP_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_PROCESS_LOOKUP_MAP SET LOOKUP_META_ID = $idPh1 WHERE LOOKUP_META_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_PROCESS_LOOKUP_MAP SET GROUP_META_DATA_ID = $idPh1 WHERE GROUP_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_PROCESS_NTF SET SYSTEM_META_GROUP_ID = $idPh1 WHERE SYSTEM_META_GROUP_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_PROCESS_PARAM_ATTR_LINK SET LOOKUP_KEY_META_DATA_ID = $idPh1 WHERE LOOKUP_KEY_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_PROCESS_PARAM_ATTR_LINK SET LOOKUP_META_DATA_ID = $idPh1 WHERE LOOKUP_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_PROCESS_SCHEDULE SET DATAVIEW_ID = $idPh1 WHERE DATAVIEW_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_REPORT_TEMPLATE_LINK SET DATA_MODEL_ID = $idPh1 WHERE DATA_MODEL_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_STATEMENT_LINK SET GROUP_DATA_VIEW_ID = $idPh1 WHERE GROUP_DATA_VIEW_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_STATEMENT_LINK SET ROW_DATA_VIEW_ID = $idPh1 WHERE ROW_DATA_VIEW_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_STATEMENT_LINK SET DATA_VIEW_ID = $idPh1 WHERE DATA_VIEW_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_WFM_ASSIGNMENT SET SYSTEM_META_GROUP_ID = $idPh1 WHERE SYSTEM_META_GROUP_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WFM_FIELD SET SELECT_META_DATA_ID = $idPh1 WHERE SELECT_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WFM_FIELD SET LOOKUP_META_DATA_ID = $idPh1 WHERE LOOKUP_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WFM_STATUS SET ASSIGN_DATAVIEW_ID = $idPh1 WHERE ASSIGN_DATAVIEW_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WORKSPACE_LINK SET ROW_DATAVIEW_ID = $idPh1 WHERE ROW_DATAVIEW_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WORKSPACE_LINK SET GROUP_META_DATA_ID = $idPh1 WHERE GROUP_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_DM_TEMPLATE_DTL SET META_GROUP_LINK_ID = $idPh1 WHERE META_GROUP_LINK_ID = $idPh2", [$newMetaGroupLinkId, $oldMetaGroupLinkId]);
                
                $this->db->Execute("UPDATE KPI_TEMPLATE SET LIST_META_DATA_ID = $idPh1 WHERE LIST_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE KPI_TEMPLATE_FACT SET LOOKUP_META_DATA_ID = $idPh1 WHERE LOOKUP_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE KPI_TEMPLATE_FACT SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE KPI_TEMPLATE_DTL_FACT SET LOOKUP_META_DATA_ID = $idPh1 WHERE LOOKUP_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE KPI_INDICATOR_FACT SET LOOKUP_META_DATA_ID = $idPh1 WHERE LOOKUP_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE KPI_INDICATOR_FACT SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE KPI_INDICATOR_INDICATOR_FACT SET LOOKUP_META_DATA_ID = $idPh1 WHERE LOOKUP_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE KPI_INDICATOR SET LIST_META_DATA_ID = $idPh1 WHERE LIST_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE KPI_INDICATOR SET LOOKUP_META_DATA_ID = $idPh1 WHERE LOOKUP_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE FIN_ACCOUNT_TYPE_BP_CONFIG SET DATAVIEW_ID = $idPh1 WHERE DATAVIEW_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE FIN_ACCOUNT_TYPE_BP_CONFIG SET CHECK_DATAVIEW_ID = $idPh1 WHERE CHECK_DATAVIEW_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE REP_FIN_GENERAL_LEDGER_MAP SET DATA_VIEW_ID = $idPh1 WHERE DATA_VIEW_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE FIN_GENERAL_LEDGER_TMP SET CRITERIA = REPLACE(CRITERIA, '$oldMetaId', '$newMetaId') WHERE CRITERIA LIKE '%$oldMetaId%'");
                
                /*
                $this->db->Execute("UPDATE UM_USER_ALIAS SET REF_STRUCTURE_ID = $idPh1 WHERE REF_STRUCTURE_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WFM_LOG SET REF_STRUCTURE_ID = $idPh1 WHERE REF_STRUCTURE_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_DATAMART_LINK SET REF_STRUCTURE_ID = $idPh1 WHERE REF_STRUCTURE_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_GROUP_LINK SET REF_STRUCTURE_ID = $idPh1 WHERE REF_STRUCTURE_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_SEMANTIC_CONFIG SET REF_STRUCTURE_ID = $idPh1 WHERE REF_STRUCTURE_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WFM_ALIAS_LOG SET REF_STRUCTURE_ID = $idPh1 WHERE REF_STRUCTURE_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WFM_ASSIGNMENT SET REF_STRUCTURE_ID = $idPh1 WHERE REF_STRUCTURE_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WFM_ASSIGNMENT SET REF_STRUCTURE_ID = $idPh1 WHERE REF_STRUCTURE_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WFM_AUTO_TRANSITION SET STRUCTURE_ID = $idPh1 WHERE STRUCTURE_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WFM_CRITERIA SET REF_STRUCTURE_ID = $idPh1 WHERE REF_STRUCTURE_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WFM_FIELD SET REF_STRUCTURE_ID = $idPh1 WHERE REF_STRUCTURE_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WFM_INHERITANCE SET SRC_REF_STRUCTURE_ID = $idPh1 WHERE SRC_REF_STRUCTURE_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WFM_INHERITANCE SET TRG_REF_STRUCTURE_ID = $idPh1 WHERE TRG_REF_STRUCTURE_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WFM_PENDING_LOG SET REF_STRUCTURE_ID = $idPh1 WHERE REF_STRUCTURE_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WFM_STATUS_LINK SET REF_STRUCTURE_ID = $idPh1 WHERE REF_STRUCTURE_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WFM_WORKFLOW SET REF_STRUCTURE_ID = $idPh1 WHERE REF_STRUCTURE_ID = $idPh2", $bindIds);
                */
                
            } elseif (Mdmetadata::$businessProcessMetaTypeId == $metaTypeId) {
                
                $this->db->Execute("UPDATE META_BUSINESS_PROCESS_LINK SET GETDATA_PROCESS_ID = $idPh1 WHERE GETDATA_PROCESS_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_BUSINESS_PROCESS_LINK SET RULE_META_DATA_ID = $idPh1 WHERE RULE_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_CARD SET PROCESS_META_DATA_ID = $idPh1 WHERE PROCESS_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_DATAMART_SCHEDULE SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_DM_PROCESS_DTL SET AUTO_MAP_DELETE_PROCESS_ID = $idPh1 WHERE AUTO_MAP_DELETE_PROCESS_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_DM_ROW_PROCESS_PARAM SET PROCESS_META_DATA_ID = $idPh1 WHERE PROCESS_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_GROUP_CONFIG SET INLINE_PROCESS_ID = $idPh1 WHERE INLINE_PROCESS_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_GROUP_CONFIG SET PROCESS_META_DATA_ID = $idPh1 WHERE PROCESS_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_GROUP_PARAM_CONFIG SET PROCESS_META_DATA_ID = $idPh1 WHERE PROCESS_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_GROUP_LINK SET RULE_PROCESS_ID = $idPh1 WHERE RULE_PROCESS_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_GROUP_LINK SET CALCULATE_PROCESS_ID = $idPh1 WHERE CALCULATE_PROCESS_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_PROCESS_PARAM_LINK SET DEFAULT_VALUE = $idPh1 WHERE DEFAULT_VALUE = $idPh2", [$newMetaCode, $oldMetaCode]);
                $this->db->Execute("UPDATE META_PROCESS_PARAM_LINK SET DONE_BP_ID = $idPh1 WHERE DONE_BP_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_PROCESS_PARAM_LINK SET DO_BP_ID = $idPh1 WHERE DO_BP_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_PROCESS_REPEATER SET PROCESS_ID = $idPh1 WHERE PROCESS_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_PROCESS_RULE SET RULE_PROCESS_ID = $idPh1 WHERE RULE_PROCESS_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_PROCESS_SCHEDULE SET PROCESS_ID = $idPh1 WHERE PROCESS_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_PROCESS_UNIQUE_MESSAGE SET MAIN_META_DATA_ID = $idPh1 WHERE MAIN_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_PROCESS_WORKFLOW SET DO_BP_ID = $idPh1 WHERE DO_BP_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_STATEMENT_LINK SET PROCESS_META_DATA_ID = $idPh1 WHERE PROCESS_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_VALIDATION_RULE SET CHECK_PROCESS_META_DATA_ID = $idPh1 WHERE CHECK_PROCESS_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_VALIDATION_RULE SET RUN_PROCESS_META_DATA_ID = $idPh1 WHERE RUN_PROCESS_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_VALIDATION_RULE SET SKIP_PROCESS_META_DATA_ID = $idPh1 WHERE SKIP_PROCESS_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_WFM_STATUS SET MOBILE_PROCESS_META_DATA_ID = $idPh1 WHERE MOBILE_PROCESS_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WFM_STATUS SET PROCESS_META_DATA_ID = $idPh1 WHERE PROCESS_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_PROCESS_PARAM_ATTR_LINK SET GET_PROCESS_META_DATA_ID = $idPh1 WHERE GET_PROCESS_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_PROCESS_PARAM_ATTR_LINK SET EDIT_PROCESS_META_DATA_ID = $idPh1 WHERE EDIT_PROCESS_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_PROCESS_DEFAULT_GET SET GETDATA_PROCESS_ID = $idPh1 WHERE GETDATA_PROCESS_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_PROCESS_WF_BEHAVIOUR SET CRITERIA = REGEXP_REPLACE(CRITERIA, '$oldMetaCode.', '$newMetaCode.', 1, 0, 'i') WHERE CRITERIA IS NOT NULL AND LOWER(CRITERIA) LIKE '$oldMetaCodeLower.%'");
                $this->db->Execute("UPDATE META_PROCESS_WF_BEHAVIOUR SET CRITERIA = REGEXP_REPLACE(CRITERIA, 'done.$oldMetaCode.', 'done.$newMetaCode.', 1, 0, 'i') WHERE CRITERIA IS NOT NULL AND LOWER(CRITERIA) LIKE '%done.$oldMetaCodeLower.%'");
                $this->db->Execute("UPDATE META_PROCESS_WF_BEHAVIOUR SET CRITERIA = REGEXP_REPLACE(CRITERIA, ' $oldMetaCode.', ' $newMetaCode.', 1, 0, 'i') WHERE CRITERIA IS NOT NULL AND LOWER(CRITERIA) LIKE '% $oldMetaCodeLower.%'");
                $this->db->Execute("UPDATE META_PROCESS_WF_BEHAVIOUR SET CRITERIA = REGEXP_REPLACE(CRITERIA, '($oldMetaCode.', '($newMetaCode.', 1, 0, 'i') WHERE CRITERIA IS NOT NULL AND LOWER(CRITERIA) LIKE '%($oldMetaCodeLower.%'");
                
                $this->db->Execute("UPDATE META_PROCESS_NTF SET SYSTEM_META_GROUP_ID = $idPh1 WHERE SYSTEM_META_GROUP_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE NTF_NOTIFICATION_ACTION SET PROCESS_META_DATA_ID = $idPh1 WHERE PROCESS_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE KPI_TEMPLATE SET PROCESS_META_DATA_ID = $idPh1 WHERE PROCESS_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE KPI_TEMPLATE_DTL SET GET_PROCESS_ID = $idPh1 WHERE GET_PROCESS_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE KPI_TEMPLATE_DTL SET HELP_PROCESS_META_ID = $idPh1 WHERE HELP_PROCESS_META_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE KPI_TEMPLATE_FACT SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE KPI_TEMPLATE_DTL_FACT SET GET_PROCESS_ID = $idPh1 WHERE GET_PROCESS_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE KPI_INDICATOR_FACT SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE KPI_INDICATOR_INDICATOR_FACT SET GET_PROCESS_ID = $idPh1 WHERE GET_PROCESS_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE FIN_ACCOUNT_TYPE_BP_CONFIG SET DEBIT_PROCESS_ID = $idPh1 WHERE DEBIT_PROCESS_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE FIN_ACCOUNT_TYPE_BP_CONFIG SET CREDIT_PROCESS_ID = $idPh1 WHERE CREDIT_PROCESS_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE FIN_ACCOUNT_TYPE_BP_CONFIG SET DEBIT_EDIT_PROCESS_ID = $idPh1 WHERE DEBIT_EDIT_PROCESS_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE FIN_ACCOUNT_TYPE_BP_CONFIG SET CREDIT_EDIT_PROCESS_ID = $idPh1 WHERE CREDIT_EDIT_PROCESS_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE FIN_ACCOUNT_TYPE_BP_CONFIG SET DELETE_PROCESS_ID = $idPh1 WHERE DELETE_PROCESS_ID = $idPh2", $bindIds);
                
            } elseif (Mdmetadata::$statementMetaTypeId == $metaTypeId) {
                
                $this->db->Execute("UPDATE CUSTOMER_DEFAULT_META SET SRC_META_DATA_ID = $idPh1 WHERE SRC_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE CUSTOMER_DEFAULT_META SET ACTION_META_DATA_ID = $idPh1 WHERE ACTION_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE CUSTOMER_ST_GROUPING_CONFIG SET STATEMENT_META_DATA_ID = $idPh1 WHERE STATEMENT_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_STATEMENT_TEMPLATE SET TRG_META_DATA_ID = $idPh1 WHERE TRG_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_DM_STATEMENT_DTL SET STATEMENT_META_DATA_ID = $idPh1 WHERE STATEMENT_META_DATA_ID = $idPh2", $bindIds);
                
            } elseif (Mdmetadata::$reportTemplateMetaTypeId == $metaTypeId) {
                
                $this->db->Execute("UPDATE CUSTOMER_TEMPLATE_MAP SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_DM_TEMPLATE_DTL SET TEMPLATE_META_DATA_ID = $idPh1 WHERE TEMPLATE_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_PROCESS_TEMPLATE SET TEMPLATE_META_DATA_ID = $idPh1 WHERE TEMPLATE_META_DATA_ID = $idPh2", $bindIds);
                
            } elseif (Mdmetadata::$menuMetaTypeId == $metaTypeId) {
                
                $this->db->Execute("UPDATE META_WORKSPACE_LINK SET MENU_META_DATA_ID = $idPh1 WHERE MENU_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WORKSPACE_LINK SET SUBMENU_META_DATA_ID = $idPh1 WHERE SUBMENU_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WORKSPACE_LINK SET DEFAULT_MENU_ID = $idPh1 WHERE DEFAULT_MENU_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE UM_USER SET CLICK_MENU_ID = $idPh1 WHERE CLICK_MENU_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE UM_USER SET DEFAULT_MENU_ID = $idPh1 WHERE DEFAULT_MENU_ID = $idPh2", $bindIds);
            }
            
            if (Mdmetadata::$metaGroupMetaTypeId == $metaTypeId || Mdmetadata::$businessProcessMetaTypeId == $metaTypeId) {
                
                $this->db->Execute("UPDATE CUSTOMER_META_USER_CONFIG SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE CUSTOMER_META_WS SET SRC_META_DATA_ID = $idPh1 WHERE SRC_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE CUSTOMER_META_WS SET TRG_META_DATA_ID = $idPh1 WHERE TRG_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE CUSTOMER_DV_FIELD SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE CUSTOMER_DV_OFFLINE_MOBILE SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE CUSTOMER_META_OFFLINE SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_MENU_LINK SET COUNT_META_DATA_ID = $idPh1 WHERE COUNT_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_GROUP_RELATION SET SRC_META_DATA_ID = $idPh1 WHERE SRC_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_GROUP_RELATION SET TRG_META_DATA_ID = $idPh1 WHERE TRG_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_GROUP_CONFIG SET TRG_META_DATA_ID = $idPh1 WHERE TRG_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_SEMANTIC_TYPE SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_SRC_TRG_PARAM SET SRC_META_DATA_ID = $idPh1 WHERE SRC_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_TAG_MAP SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_VALIDATION_RULE SET LINK_META_DATA_ID = $idPh1 WHERE LINK_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_BP_LAYOUT_SECTION SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_DM_PROCESS_DTL SET PROCESS_META_DATA_ID = $idPh1 WHERE PROCESS_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_DM_TRANSFER_PROCESS SET GET_META_DATA_ID = $idPh1 WHERE GET_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_DM_TRANSFER_PROCESS SET PROCESS_META_DATA_ID = $idPh1 WHERE PROCESS_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_DM_TRANSFER_PROCESS SET DEFAULT_VALUE = $idPh1 WHERE DEFAULT_VALUE = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_DASHBOARD_LINK SET PROCESS_META_DATA_ID = $idPh1 WHERE PROCESS_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_DASHBOARD_LINK SET PROCESS_META_DATA_ID4 = $idPh1 WHERE PROCESS_META_DATA_ID4 = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_DASHBOARD_LINK SET PROCESS_META_DATA_ID3 = $idPh1 WHERE PROCESS_META_DATA_ID3 = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_DASHBOARD_LINK SET PROCESS_META_DATA_ID2 = $idPh1 WHERE PROCESS_META_DATA_ID2 = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_LAYOUT_PARAM_MAP SET BP_META_DATA_ID = $idPh1 WHERE BP_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_PACKAGE_LINK SET COUNT_META_DATA_ID = $idPh1 WHERE COUNT_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_PARAM_VALUES SET PARAM_META_DATA_ID = $idPh1 WHERE PARAM_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_PROCESS_PARAM_ATTR_LINK SET MORE_META_DATA_ID = $idPh1 WHERE MORE_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_PROCESS_PARAM_LINK SET DEFAULT_VALUE = $idPh1 WHERE DEFAULT_VALUE = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_DATA_ATTACH SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_DATA_SEQUENCE_CONFIG SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
            }
            
            if (Mdmetadata::$metaGroupMetaTypeId == $metaTypeId 
                || Mdmetadata::$businessProcessMetaTypeId == $metaTypeId 
                || Mdmetadata::$statementMetaTypeId == $metaTypeId 
                || Mdmetadata::$reportTemplateMetaTypeId == $metaTypeId) {
                
                $this->db->Execute("UPDATE CUSTOMER_PROXY_CONFIG SET ACTION_META_DATA_ID = $idPh1 WHERE ACTION_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE CUSTOMER_USE_CHILD SET TRG_META_DATA_ID = $idPh1 WHERE TRG_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE CUSTOMER_USE_CHILD SET SRC_META_DATA_ID = $idPh1 WHERE SRC_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_MENU_LINK SET ACTION_META_DATA_ID = $idPh1 WHERE ACTION_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_DM_DM_MAP SET SRC_META_DATA_ID = $idPh1 WHERE SRC_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_DM_DM_MAP SET TRG_META_DATA_ID = $idPh1 WHERE TRG_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_DM_DRILLDOWN_DTL SET LINK_META_DATA_ID = $idPh1 WHERE LINK_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_DM_PROCESS_IGNORE SET MAIN_META_DATA_ID = $idPh1 WHERE MAIN_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_DM_PROCESS_IGNORE SET TRG_META_DATA_ID = $idPh1 WHERE TRG_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_DM_PROCESS_IGNORE SET PROCESS_META_DATA_ID = $idPh1 WHERE PROCESS_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_DM_TEMPLATE_DTL SET SRC_META_DATA_ID = $idPh1 WHERE SRC_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_PACKAGE_LINK SET DEFAULT_META_ID = $idPh1 WHERE DEFAULT_META_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_BUG_FIXING_DTL SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_BP_EXPRESSION_PROCESS SET USE_META_DATA_ID = $idPh1 WHERE USE_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_WORKSPACE_PARAM_MAP SET TARGET_META_ID = $idPh1 WHERE TARGET_META_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE META_WORKSPACE_PARAM_MAP SET LINK_META_DATA_ID = $idPh1 WHERE LINK_META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE META_BUSINESS_PROCESS_LINK SET LOAD_EXPRESSION_STRING = REPLACE(LOAD_EXPRESSION_STRING, '$oldMetaId', '$newMetaId') WHERE LOAD_EXPRESSION_STRING LIKE '%$oldMetaId%'");
                $this->db->Execute("UPDATE META_BUSINESS_PROCESS_LINK SET EVENT_EXPRESSION_STRING = REPLACE(EVENT_EXPRESSION_STRING, '$oldMetaId', '$newMetaId') WHERE EVENT_EXPRESSION_STRING LIKE '%$oldMetaId%'");
                $this->db->Execute("UPDATE META_BUSINESS_PROCESS_LINK SET VAR_FNC_EXPRESSION_STRING = REPLACE(VAR_FNC_EXPRESSION_STRING, '$oldMetaId', '$newMetaId') WHERE VAR_FNC_EXPRESSION_STRING LIKE '%$oldMetaId%'");
                $this->db->Execute("UPDATE META_BUSINESS_PROCESS_LINK SET SAVE_EXPRESSION_STRING = REPLACE(SAVE_EXPRESSION_STRING, '$oldMetaId', '$newMetaId') WHERE SAVE_EXPRESSION_STRING LIKE '%$oldMetaId%'");
                
                $this->db->Execute("UPDATE META_BUSINESS_PROCESS_LINK SET LOAD_EXPRESSION_STRING = REPLACE(LOAD_EXPRESSION_STRING, '''$oldMetaCode''', '''$newMetaCode''') WHERE LOAD_EXPRESSION_STRING LIKE '%''$oldMetaCode''%'");
                $this->db->Execute("UPDATE META_BUSINESS_PROCESS_LINK SET EVENT_EXPRESSION_STRING = REPLACE(EVENT_EXPRESSION_STRING, '''$oldMetaCode''', '''$newMetaCode''') WHERE EVENT_EXPRESSION_STRING LIKE '%''$oldMetaCode''%'");
                $this->db->Execute("UPDATE META_BUSINESS_PROCESS_LINK SET VAR_FNC_EXPRESSION_STRING = REPLACE(VAR_FNC_EXPRESSION_STRING, '''$oldMetaCode''', '''$newMetaCode''') WHERE VAR_FNC_EXPRESSION_STRING LIKE '%''$oldMetaCode''%'");
                $this->db->Execute("UPDATE META_BUSINESS_PROCESS_LINK SET SAVE_EXPRESSION_STRING = REPLACE(SAVE_EXPRESSION_STRING, '''$oldMetaCode''', '''$newMetaCode''') WHERE SAVE_EXPRESSION_STRING LIKE '%''$oldMetaCode''%'");
                
                $this->db->Execute("UPDATE META_BP_EXPRESSION_DTL SET EVENT_EXPRESSION_STRING = REPLACE(EVENT_EXPRESSION_STRING, '$oldMetaId', '$newMetaId') WHERE EVENT_EXPRESSION_STRING LIKE '%$oldMetaId%'");
                $this->db->Execute("UPDATE META_BP_EXPRESSION_DTL SET LOAD_EXPRESSION_STRING = REPLACE(LOAD_EXPRESSION_STRING, '$oldMetaId', '$newMetaId') WHERE LOAD_EXPRESSION_STRING LIKE '%$oldMetaId%'");
                $this->db->Execute("UPDATE META_BP_EXPRESSION_DTL SET VAR_FNC_EXPRESSION_STRING = REPLACE(VAR_FNC_EXPRESSION_STRING, '$oldMetaId', '$newMetaId') WHERE VAR_FNC_EXPRESSION_STRING LIKE '%$oldMetaId%'");
                $this->db->Execute("UPDATE META_BP_EXPRESSION_DTL SET SAVE_EXPRESSION_STRING = REPLACE(SAVE_EXPRESSION_STRING, '$oldMetaId', '$newMetaId') WHERE SAVE_EXPRESSION_STRING LIKE '%$oldMetaId%'");
                
                $this->db->Execute("UPDATE META_BP_EXPRESSION_DTL SET EVENT_EXPRESSION_STRING = REPLACE(EVENT_EXPRESSION_STRING, '''$oldMetaCode''', '''$newMetaCode''') WHERE EVENT_EXPRESSION_STRING LIKE '%''$oldMetaCode''%'");
                $this->db->Execute("UPDATE META_BP_EXPRESSION_DTL SET LOAD_EXPRESSION_STRING = REPLACE(LOAD_EXPRESSION_STRING, '''$oldMetaCode''', '''$newMetaCode''') WHERE LOAD_EXPRESSION_STRING LIKE '%''$oldMetaCode''%'");
                $this->db->Execute("UPDATE META_BP_EXPRESSION_DTL SET VAR_FNC_EXPRESSION_STRING = REPLACE(VAR_FNC_EXPRESSION_STRING, '''$oldMetaCode''', '''$newMetaCode''') WHERE VAR_FNC_EXPRESSION_STRING LIKE '%''$oldMetaCode''%'");
                $this->db->Execute("UPDATE META_BP_EXPRESSION_DTL SET SAVE_EXPRESSION_STRING = REPLACE(SAVE_EXPRESSION_STRING, '''$oldMetaCode''', '''$newMetaCode''') WHERE SAVE_EXPRESSION_STRING LIKE '%''$oldMetaCode''%'");
                
                $this->db->Execute("UPDATE KPI_TEMPLATE SET EVENT_EXPRESSION_STRING = REPLACE(EVENT_EXPRESSION_STRING, '$oldMetaId', '$newMetaId') WHERE EVENT_EXPRESSION_STRING LIKE '%$oldMetaId%'");
                $this->db->Execute("UPDATE KPI_TEMPLATE SET SAVE_EXPRESSION_STRING = REPLACE(SAVE_EXPRESSION_STRING, '$oldMetaId', '$newMetaId') WHERE SAVE_EXPRESSION_STRING LIKE '%$oldMetaId%'");
                $this->db->Execute("UPDATE KPI_TEMPLATE SET VAR_FNC_EXPRESSION_STRING = REPLACE(VAR_FNC_EXPRESSION_STRING, '$oldMetaId', '$newMetaId') WHERE VAR_FNC_EXPRESSION_STRING LIKE '%$oldMetaId%'");
                
                $this->db->Execute("UPDATE KPI_TEMPLATE SET EVENT_EXPRESSION_STRING = REPLACE(EVENT_EXPRESSION_STRING, '''$oldMetaCode''', '''$newMetaCode''') WHERE EVENT_EXPRESSION_STRING LIKE '%''$oldMetaCode''%'");
                $this->db->Execute("UPDATE KPI_TEMPLATE SET SAVE_EXPRESSION_STRING = REPLACE(SAVE_EXPRESSION_STRING, '''$oldMetaCode''', '''$newMetaCode''') WHERE SAVE_EXPRESSION_STRING LIKE '%''$oldMetaCode''%'");
                $this->db->Execute("UPDATE KPI_TEMPLATE SET VAR_FNC_EXPRESSION_STRING = REPLACE(VAR_FNC_EXPRESSION_STRING, '''$oldMetaCode''', '''$newMetaCode''') WHERE VAR_FNC_EXPRESSION_STRING LIKE '%''$oldMetaCode''%'");
                
                $this->db->Execute("UPDATE KPI_INDICATOR SET EVENT_EXPRESSION_STRING = REPLACE(EVENT_EXPRESSION_STRING, '$oldMetaId', '$newMetaId') WHERE EVENT_EXPRESSION_STRING LIKE '%$oldMetaId%'");
                $this->db->Execute("UPDATE KPI_INDICATOR SET LOAD_EXPRESSION_STRING = REPLACE(LOAD_EXPRESSION_STRING, '$oldMetaId', '$newMetaId') WHERE LOAD_EXPRESSION_STRING LIKE '%$oldMetaId%'");
                $this->db->Execute("UPDATE KPI_INDICATOR SET VAR_FNC_EXPRESSION_STRING = REPLACE(VAR_FNC_EXPRESSION_STRING, '$oldMetaId', '$newMetaId') WHERE VAR_FNC_EXPRESSION_STRING LIKE '%$oldMetaId%'");
                $this->db->Execute("UPDATE KPI_INDICATOR SET SAVE_EXPRESSION_STRING = REPLACE(SAVE_EXPRESSION_STRING, '$oldMetaId', '$newMetaId') WHERE SAVE_EXPRESSION_STRING LIKE '%$oldMetaId%'");
                $this->db->Execute("UPDATE KPI_INDICATOR SET AFTER_SAVE_EXPRESSION_STRING = REPLACE(AFTER_SAVE_EXPRESSION_STRING, '$oldMetaId', '$newMetaId') WHERE AFTER_SAVE_EXPRESSION_STRING LIKE '%$oldMetaId%'");
                
                $this->db->Execute("UPDATE KPI_INDICATOR SET EVENT_EXPRESSION_STRING = REPLACE(EVENT_EXPRESSION_STRING, '''$oldMetaCode''', '''$newMetaCode''') WHERE EVENT_EXPRESSION_STRING LIKE '%''$oldMetaCode''%'");
                $this->db->Execute("UPDATE KPI_INDICATOR SET LOAD_EXPRESSION_STRING = REPLACE(LOAD_EXPRESSION_STRING, '''$oldMetaCode''', '''$newMetaCode''') WHERE LOAD_EXPRESSION_STRING LIKE '%''$oldMetaCode''%'");
                $this->db->Execute("UPDATE KPI_INDICATOR SET VAR_FNC_EXPRESSION_STRING = REPLACE(VAR_FNC_EXPRESSION_STRING, '''$oldMetaCode''', '''$newMetaCode''') WHERE VAR_FNC_EXPRESSION_STRING LIKE '%''$oldMetaCode''%'");
                $this->db->Execute("UPDATE KPI_INDICATOR SET SAVE_EXPRESSION_STRING = REPLACE(SAVE_EXPRESSION_STRING, '''$oldMetaCode''', '''$newMetaCode''') WHERE SAVE_EXPRESSION_STRING LIKE '%''$oldMetaCode''%'");
                $this->db->Execute("UPDATE KPI_INDICATOR SET AFTER_SAVE_EXPRESSION_STRING = REPLACE(AFTER_SAVE_EXPRESSION_STRING, '''$oldMetaCode''', '''$newMetaCode''') WHERE AFTER_SAVE_EXPRESSION_STRING LIKE '%''$oldMetaCode''%'");
                
                $this->db->Execute("UPDATE UM_META_BLOCK SET ACTION_META_DATA_ID = $idPh1 WHERE ACTION_META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE UM_META_LOCK SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE UM_CRITERIA SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                $this->db->Execute("UPDATE UM_OBJECT SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
                
                $this->db->Execute("UPDATE CONFIG_VALUE SET CONFIG_VALUE = $idPh1 WHERE CONFIG_VALUE = $idPh2", $bindIds);
                $this->db->Execute("UPDATE CONFIG_VALUE SET CONFIG_VALUE = $idPh1 WHERE LOWER(CONFIG_VALUE) = $idPh2", [$newMetaCode, Str::lower($oldMetaCode)]);
            }
            
            $this->db->Execute("UPDATE META_META_MAP SET TRG_META_DATA_ID = $idPh1 WHERE TRG_META_DATA_ID = $idPh2", $bindIds);
            $this->db->Execute("UPDATE META_PROXY_MAP SET TRG_META_DATA_ID = $idPh1 WHERE TRG_META_DATA_ID = $idPh2", $bindIds);
            
            $this->db->Execute("UPDATE KPI_INDICATOR_INDICATOR_MAP SET LOOKUP_META_DATA_ID = $idPh1 WHERE LOOKUP_META_DATA_ID = $idPh2", $bindIds);
            
            $this->db->Execute("UPDATE UM_QUICK_MENU SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
            $this->db->Execute("UPDATE UM_META_PERMISSION SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
            $this->db->Execute("UPDATE UM_META_PERMISSION_CACHE SET META_DATA_ID = $idPh1 WHERE META_DATA_ID = $idPh2", $bindIds);
            $this->db->Execute("UPDATE UM_META_PERMISSION_CACHE SET PARENT_META_DATA_ID = $idPh1 WHERE PARENT_META_DATA_ID = $idPh2", $bindIds);
            
            $result = ['status' => 'success'];
            
        } catch (Exception $ex) {
            $result = ['status' => 'error', 'message' => $ex->getMessage()];
        }
        
        return $result;
    }
    
    public function metaReplaceModel() {
        
        try {
            
            if (!Mdmeta::isAccessMetaImport()) {
                throw new Exception('Энэ үйлдэл боломжгүй байна!');
            }

            set_time_limit(0);
            ini_set('memory_limit', '-1');

            $oldMetaDataId = Input::numeric('oldMetaDataId');
            $replaceMetaId = Input::numeric('replaceMetaId');

            $this->load->model('mdmetadata', 'middleware/models/');
            
            $oldMetaRow = $this->model->getMetaDataModel($oldMetaDataId);
            $newMetaRow = $this->model->getMetaDataModel($replaceMetaId);
            
            $this->load->model('mdupgrade', 'middleware/models/');
            
            $oldGroupLink = self::getMetaColumnDatas($oldMetaDataId);
            $newGroupLink = self::getMetaColumnDatas($replaceMetaId);
            
            $oldMetaGroupLinkId = $oldGroupLink['META_GROUP_LINK_ID'];
            $newMetaGroupLinkId = $newGroupLink['META_GROUP_LINK_ID'];
            
            $newMetaType = $newMetaRow['META_TYPE_ID'];
            $newMetaCode = $newMetaRow['META_DATA_CODE'];
            $oldMetaCode = $oldMetaRow['META_DATA_CODE'];
            
            $this->db->BeginTrans(); 

            $replaceResult = self::oldMetaNewMetaReplace(array(
                'metaTypeId'  => $newMetaType, 
                'oldMetaId'   => $oldMetaDataId, 
                'newMetaId'   => $replaceMetaId, 
                'oldMetaCode' => $oldMetaCode, 
                'newMetaCode' => $newMetaCode, 
                'oldMetaGroupLinkId' => $oldMetaGroupLinkId, 
                'newMetaGroupLinkId' => $newMetaGroupLinkId
            ));

            if ($replaceResult['status'] != 'success') {
                throw new Exception($replaceResult['message']);
            }

            $logData = array(
                'ID'               => getUID(),
                'META_DATA_ID'     => $oldMetaDataId, 
                'NEW_META_DATA_ID' => $replaceMetaId,
                'CREATED_USER_ID'  => Ue::sessionUserId(),
                'CREATED_DATE'     => Date::currentDate(),
                'IS_IMPORT'        => 3
            );
            $this->db->AutoExecute('CUSTOMER_META_COPY_LOG', $logData);
            
            $this->db->CommitTrans();

            if ($newMetaType == Mdmetadata::$businessProcessMetaTypeId) {

                (new Mdmeta())->bpParamsClearCache($oldMetaDataId, $oldMetaCode, true);

            } elseif ($newMetaType == Mdmetadata::$metaGroupMetaTypeId) {

                (new Mdmeta())->dvCacheClearByMetaId($oldMetaDataId, true);
            }
            
            $response = array('status' => 'success', 'message' => 'Амжилттай!');
        
        } catch (Exception $ex) {
            
            $this->db->RollbackTrans();
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function getBugFixingScriptModel($id) {
        
        try {
            
            $row = $this->db->GetRow("SELECT SCRIPT FROM META_BUG_FIXING WHERE ID = ".$this->db->Param(0), array($id));
            
            return array('status' => 'success', 'script' => issetParam($row['SCRIPT']));
            
        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }        
    }
    
    public function getBugFixingKnowledgeModel($id) {
        
        try {
            
            $row = $this->db->GetRow("SELECT KNOWLEDGE FROM META_BUG_FIXING WHERE ID = ".$this->db->Param(0), array($id));
            
            return array('status' => 'success', 'knowledge' => issetParam($row['KNOWLEDGE']));
            
        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }        
    }
    
    public function file_force_contents($filename, $data, $flags = 0) {
        if (!is_dir(dirname($filename))) {
            mkdir(dirname($filename).'/', 0777, true);
        }
        return file_put_contents($filename, $data, $flags);
    }
    
    public function isTableDataKpiIndicatorModel() {
        
        $id           = strtolower(Input::post('id'));
        $selectedRows = Input::post('selectedRows');
        
        if (is_array($selectedRows) && array_key_exists($id, $selectedRows[0])) {
            
            $ids = Arr::implode_key(',', $selectedRows, $id, true);
            
            $row = $this->db->GetRow("
                SELECT 
                    TABLE_NAME  
                FROM KPI_INDICATOR 
                WHERE ID IN ($ids) 
                    AND TABLE_NAME IS NOT NULL");
            
            if (isset($row['TABLE_NAME'])) {
                
                $tableName = substr($row['TABLE_NAME'], 0, 2);
                
                if ($tableName == 'V_' || strpos($row['TABLE_NAME'], '.V_') !== false) {
                    
                    try {
                        
                        $rs = $this->db->SelectLimit("
                            SELECT 
                                ID  
                            FROM ".$row['TABLE_NAME']." 
                            WHERE DELETED_USER_ID IS NULL 
                            ORDER BY ID ASC", 100, 0);
                        
                        if (isset($rs->_array)) {
                            return array('status' => 'success', 'count' => count($rs->_array));
                        } 
                        
                    } catch (Exception $ex) {
                        return null;
                    }
                }
            } 
            
            return null;
            
        } else {
            return array('status' => 'error', 'message' => 'Татах өгөгдөл олдсонгүй!');
        }
    }
    
    public function checkCreatedAlreadyModel($metaTypeId, $metaDataId) {
        try {
            
            if ($metaTypeId == '222222222222' || $metaTypeId == 'bugfix') {
                return false;
            }
            
            $idPh = $this->db->Param(0);
            
            if (is_numeric($metaTypeId)) {
                
                $checkData = $this->db->GetOne("SELECT META_DATA_ID FROM META_DATA WHERE META_DATA_ID = $idPh", [$metaDataId]);
                
                if ($checkData) {
                    return true;
                }
                
            } else {
                
                $objectTableRelation = self::objectTableRelation();
                
                if (isset($objectTableRelation[$metaTypeId])) {
                    
                    $relations = $objectTableRelation[$metaTypeId];
                    $tblName = array_key_first($relations);
                    
                    if (isset($relations[$tblName][0]['link'][0]['src'])) {
                        $primaryField = $relations[$tblName][0]['link'][0]['src'];
                    } else {
                        $primaryField = self::tablePrimaryField($tblName);
                    }
                    
                    $checkData = $this->db->GetOne("SELECT $primaryField FROM $tblName WHERE $primaryField = $idPh", [$metaDataId]);
                
                    if ($checkData) {
                        return true;
                    }
                }
            }
            
            return false;
            
        } catch (Exception $ex) {
            return true;
        }
    }
    
    public function checkEisArcScriptLog($logId) {
        try {
            $checkId = $this->db->GetOne("SELECT ID FROM EIS_ARC_SCRIPT_LOG WHERE ID = ".$this->db->Param(0), [$logId]);
            if ($checkId) {
                return true;
            } 
        } catch (Exception $ex) {}
        
        return false;
    }
    
    public function getPatchListModel($criteria = []) {
        try {
            $where = null;
            if ($criteria) {
                foreach ($criteria as $filterCol => $filterColVals) {
                    $orCriteria = null;
                    
                    foreach ($filterColVals as $filterColVal) {
                        $operator = $filterColVal['operator'];
                        $filterVal = $filterColVal['operand'];
                        $orCriteria .= "LOWER($filterCol) $operator '".Str::lower($filterVal)."' OR ";
                    }
                    
                    $orCriteria = rtrim(trim($orCriteria), ' OR');
                    $where .= " AND ($orCriteria)";
                }
            }
            
            $data = $this->db->GetAll("SELECT ID, DESCRIPTION FROM META_BUG_FIXING WHERE DESCRIPTION IS NOT NULL $where ORDER BY CREATED_DATE DESC");
            
            return ['status' => 'success', 'data' => $data];
        } catch (Exception $ex) {
            return ['status' => 'error', 'message' => $ex->getMessage()];
        }     
    }
    
    public function installCloudPatchDownloadModel() {
        
        $url = Mdupgrade::getCloudInstallUrl();
        $patchId = Input::numeric('patchId');
        
        $response = (new WebService())->curlRequest($url, ['commandName' => 'download', 'param' => ['ids' => $patchId]]);
        
        if ($response['status'] == 'success') {
            
            $fileContent = $response['result'];
            $fileId = getUID();
        
            $cacheTmpDir = Mdcommon::getCacheDirectory();
            $cacheDir    = $cacheTmpDir . '/cloud_patch';
            $cachePath   = $cacheDir . '/' . $fileId . '.txt';

            if (!is_dir($cacheDir)) {

                mkdir($cacheDir, 0777);

            } else {

                $currentHour = (int) Date::currentDate('H');

                /* Оройны 14 цагаас 19 цагийн хооронд шалгаж өмнө нь үүссэн файлуудыг устгана */
                if ($currentHour >= 14 && $currentHour <= 19) { 

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

            file_put_contents($cachePath, $fileContent);
            
            $result = ['status' => 'success', 'fileId' => $fileId];
            
        } else {
            $result = $response;
        }
        
        return $result;
    }
    
    public function installCloudPatchImportModel() {
        
        $domain = strtolower(Input::post('domain'));
        
        if (strpos($domain, 'https:') !== false || strpos($domain, 'http:') !== false) {
            $domainFix = rtrim($domain, '/');
        } else {
            $domainFix = 'https://' . rtrim($domain, '/');
        }
        
        $domainUrl  = $domainFix . '/mdupgrade/externalCloudPatchImport';
        $fileId     = Input::numeric('fileId');
        $patchId    = Input::numeric('patchId');
        $customerId = Input::numeric('customerId');
        $patchName  = Input::post('patchName');
        
        $cacheTmpDir = Mdcommon::getCacheDirectory();
        $cacheDir    = $cacheTmpDir . '/cloud_patch';
        $cachePath   = $cacheDir . '/' . $fileId . '.txt';
        
        $fileContent = file_get_contents($cachePath);
        
        $ch = curl_init($domainUrl);
            
        curl_setopt($ch, CURLOPT_URL, $domainUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContent);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36',
            'Cache-Control: no-cache', 
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {

            $msg = curl_error($ch);
            curl_close($ch);

            $result = ['status' => 'error', 'message' => $msg];
            
        } else {
            curl_close($ch); 
            $result = json_decode(remove_utf8_bom($response), true);
        }
        
        self::createCustomerBugFixedWithDomain([
            'patchResult' => $result, 
            'domainAttr' => [
                'domain'     => $domain, 
                'customerId' => $customerId, 
                'patchId'    => $patchId, 
                'patchName'  => $patchName 
            ]
        ]);
        
        return $result;
    }
    
    public function externalCloudPatchImportModel() {
        
        includeLib('Compress/Compression');
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $inputContent = file_get_contents('php://input');
        $fileContent  = Compression::gzinflate($inputContent);

        if ($fileContent && strpos($fileContent, '<meta id="') === false) {
            return ['status' => 'error', 'message' => 'PHP export хийсэн файл уншуулна уу!', 'logs' => ''];
        } 
        
        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');

        if ($logged == false) {
            Session::set(SESSION_PREFIX . 'LoggedIn', true);
            Session::set(SESSION_PREFIX . 'lastTime', time());
        }

        $_POST['nult'] = true;
        
        self::$isGenerateLanguageFile = true;
        $response = self::executeUpgradeScript([$fileContent]);
        
        return $response;
    }
    
    public function installCloudPatchDbImportModel() {
        
        $customerId = Input::numeric('customerId');
        $connectionInfo = DBUtil::getConnectionByCustomerId($customerId);
        
        if (!$connectionInfo) {
            return ['status' => 'error', 'message' => 'Mdm connection not found!'];
        }
        
        global $db;
        
        $dbDriver = DBUtil::toShortName($connectionInfo['DB_TYPE']);
        $dbHost   = $connectionInfo['HOST_NAME'] . ':' . $connectionInfo['PORT'];
        $dbUser   = strtolower($connectionInfo['USER_NAME']);
        $dbPass   = $connectionInfo['USER_PASSWORD'];
        $dbSID    = $connectionInfo['SID'];

        if ($dbSID) {
            $connectSID = true;
        } else {
            $dbSID = $connectionInfo['SERVICE_NAME'];
            $connectSID = false;
        }

        $db = ADONewConnection($dbDriver);
        $db->debug = DB_DEBUG;
        $db->connectSID = $connectSID;
        $db->autoRollback = true;
        $db->datetime = true;

        try {
            $db->Connect($dbHost, $dbUser, $dbPass, $dbSID);
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->msg];
        }
        
        $db->SetCharSet(DB_CHATSET);
                
        $this->db = $db;
        
        includeLib('Compress/Compression');
        
        $fileId      = Input::numeric('fileId');
        $patchId     = Input::numeric('patchId');
        $patchName   = Input::post('patchName');
        
        $cacheTmpDir = Mdcommon::getCacheDirectory();
        $cacheDir    = $cacheTmpDir . '/cloud_patch';
        $cachePath   = $cacheDir . '/' . $fileId . '.txt';
        
        $fileContent = file_get_contents($cachePath);
        $fileContent = Compression::gzinflate($fileContent);
        
        $response = self::executeUpgradeScript([$fileContent]);
                                
        $status  = issetDefaultVal($response['status'], 'success');
        $message = issetDefaultVal($response['message'], 'Амжилттай');
        //$logs    = issetParam($response['logs']);

        $result = ['status' => $status, 'message' => $message];
        
        global $db;
        
        $db = ADONewConnection(DB_DRIVER);
        $db->debug = DB_DEBUG;
        $db->connectSID = defined('DB_SID') ? DB_SID : true;
        $db->autoRollback = true;
        $db->datetime = true;

        try {
            $db->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, false, true);
        } catch (Exception $e) { } 

        $db->SetCharSet(DB_CHATSET);
        $this->db = $db;
        
        self::createCustomerBugFixedWithDomain([
            'patchResult' => $result, 
            'domainAttr' => [
                'domain'     => null, 
                'customerId' => $customerId, 
                'patchId'    => $patchId, 
                'patchName'  => $patchName 
            ]
        ]);
        
        return $result;
    }
    
    public function createCustomerBugFixedWithDomain($input) {
        
        try {
            
            $data = [
                'ID'                 => getUID(), 
                'CREATED_USER_ID'    => Ue::sessionUserKeyId(), 
                'CREATED_DATE'       => Date::currentDate(), 
                'META_BUG_FIXING_ID' => $input['domainAttr']['patchId'], 
                'DESCRIPTION'        => $input['domainAttr']['patchName'], 
                'CUSTOMER_ID'        => $input['domainAttr']['customerId'], 
                'DOMAIN_NAME'        => $input['domainAttr']['domain'], 
                'STATUS'             => $input['patchResult']['status']
            ];
            
            if ($data['STATUS'] != 'success' && isset($input['patchResult']['message'])) {
                $data['ERROR_MSG'] = mb_substr($input['patchResult']['message'], 0, 1024);
            }
            
            $this->db->AutoExecute('CUSTOMER_BUG_FIXED', $data);
            return true;
            
        } catch (Exception $ex) {
            return false;
        }
    }
    
}