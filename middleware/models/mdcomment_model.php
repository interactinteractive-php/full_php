<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
    
class Mdcomment_Model extends Model {

    public function __construct() {
        parent::__construct();
    }        

    public function getCommentMetaProcessRowsModel($metaDataId, $metaValueId, $commentStructureId = null, $listMetaDataId = null)
    {
        $structureIdPh = $this->db->Param(0);
        $recordIdPh = $this->db->Param(1);
        $userIdPh = $this->db->Param(2);
        
        $selectColumn = $join = $where = '';
        
        if ($commentStructureId) {
            
            $selectColumn = 'WFMS.WFM_STATUS_NAME, WFMS.WFM_STATUS_COLOR, ';
            $join = 'LEFT JOIN META_WFM_STATUS WFMS ON WFMS.ID = CM.WFM_STATUS_ID';
            
        } elseif (!$commentStructureId && $listMetaDataId) {
            
            $where = " AND OBJ.LIST_META_DATA_ID = $listMetaDataId"; 
        }
        
        $data = $this->db->GetAll("
            SELECT 
                CM.*, 
                $selectColumn 
                EC.COMMENT_TEXT 
            FROM (
                    SELECT 
                        OBJ.ID AS COMMENT_ID, 
                        OBJ.CREATED_DATE, 
                        OBJ.CREATED_USER_ID, 
                        OBJ.PARENT_ID, 
                        US.USERNAME, 
                        UM.USER_ID, 
                        EMP.LAST_NAME, 
                        EMP.FIRST_NAME, 
                        EMP.PICTURE, 
                        CASE WHEN OBJ.CREATED_USER_ID = $userIdPh THEN 1 
                        ELSE 0 END AS IS_OWN, 
                        EL.ID AS OWN_REACTION_ID, 
                        OBJ.WFM_STATUS_ID 
                    FROM ECM_COMMENT OBJ 
                        INNER JOIN UM_USER UM ON UM.USER_ID = OBJ.CREATED_USER_ID 
                        LEFT JOIN UM_SYSTEM_USER US ON US.USER_ID = UM.SYSTEM_USER_ID 
                        LEFT JOIN VW_EMPLOYEE EMP ON EMP.PERSON_ID = US.PERSON_ID 
                        LEFT JOIN ECM_REACTION_LOG EL ON EL.COMMENT_ID = OBJ.ID 
                            AND EL.CREATED_USER_ID = $userIdPh           
                    WHERE OBJ.REF_STRUCTURE_ID = $structureIdPh 
                        AND OBJ.RECORD_ID = $recordIdPh 
                        AND OBJ.IS_DELETED = 0 
                        $where
                    GROUP BY 
                        OBJ.ID, 
                        OBJ.CREATED_DATE, 
                        OBJ.CREATED_USER_ID, 
                        OBJ.PARENT_ID, 
                        US.USERNAME, 
                        EMP.LAST_NAME, 
                        EMP.FIRST_NAME, 
                        EMP.PICTURE, 
                        UM.USER_ID,
                        EL.ID, 
                        OBJ.WFM_STATUS_ID 
                ) CM 
                INNER JOIN ECM_COMMENT EC ON EC.ID = CM.COMMENT_ID 
                $join 
            ORDER BY CM.CREATED_DATE ASC", array($metaDataId, $metaValueId, Ue::sessionUserKeyId()));

        return $data;
    }
    
    public function getCommentReactionTypeModel($structureId) {
        $row = $this->db->GetRow("SELECT ID, CODE, NAME FROM ECM_REACTION_TYPE WHERE REF_STRUCTURE_ID = ".$this->db->Param(0), array($structureId));
        return $row;
    }

    public function saveCommentProcessModel()
    {   
        try {
            
            $metaDataId = Input::numeric('metaDataId');
            $metaValueId = Input::numeric('metaValueId');            

            if ($metaDataId && $metaValueId) {
                $commentTxt = Input::post('commentText');
                $base64File = Input::post('commentBase64File');
                
                if ($base64File) {
                    $getFilePathInfo = self::uploadFileComment($base64File);
                    $commentTxt = '<img style="width:410px" src="'.$getFilePathInfo.'"/>';
                }
                
                $commentStructureId = Input::numeric('commentStructureId');
                $processMetaDataId = Input::numeric('processMetaDataId');
                $listMetaDataId = Input::numeric('listMetaDataId');
                $moduleMetaDataId = Input::numeric('moduleMetaDataId');
                
                $data = array(
                    'ID'                  => getUID(),
                    'REF_STRUCTURE_ID'    => $metaDataId, 
                    'RECORD_ID'           => $metaValueId,
                    'COMMENT_TEXT'        => $commentTxt, 
                    'CREATED_COMMAND_ID'  => $processMetaDataId, 
                    'LIST_META_DATA_ID'   => $listMetaDataId, 
                    'MODULE_META_DATA_ID' => $moduleMetaDataId, 
                    'CREATED_DATE'        => Date::currentDate(), 
                    'CREATED_USER_ID'     => Ue::sessionUserKeyId()
                );
                
                if ($parentId = Input::numeric('parentId')) {
                    $data['IS_REPLY'] = 1;
                    $data['PARENT_ID'] = $parentId;
                }
                
                if ($commentStructureId) {

                    $this->load->model('mdobject', 'middleware/models/');

                    $dataRow = $data;
                    $dataRow['commenttypeid'] = 1;
                    $dataRow['refStructureId'] = $commentStructureId;
                    
                    $startWfmStatusId = $this->model->getStartWfmStatusModel($commentStructureId, $dataRow);

                    if ($startWfmStatusId) {
                        $data['WFM_STATUS_ID'] = $startWfmStatusId;
                    } 
                }

                $result = $this->db->AutoExecute('ECM_COMMENT', $data);

                if ($result) {
                    
                    if ($base64File) {
                        $attachFileId = getUID();
                        $dataAttachFile = array(
                            'CONTENT_ID' => $attachFileId,
                            'FILE_NAME' => $getFilePathInfo,
                            'PHYSICAL_PATH' => $getFilePathInfo,
                            'FILE_EXTENSION' => '',
                            'FILE_SIZE' => '',
                            'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                            'IS_PHOTO' => '1'
                        );

                        $attachFile = $this->db->AutoExecute('ECM_CONTENT', $dataAttachFile);
                        if ($attachFile) {
                            $dataMetaValue = array(
                                'ID' => getUID(),
                                'REF_STRUCTURE_ID' => $commentStructureId,
                                'RECORD_ID' => $data['ID'],
                                'CONTENT_ID' => Input::param($attachFileId),
                                'ORDER_NUM' => 1
                            );
                            $this->db->AutoExecute('ECM_CONTENT_MAP', $dataMetaValue);
                        }                    
                    }
                    
                    $mentionData = Input::post('mentionData');

                    if ($parentId) {
                        $paramsMentions = array(
                            'refStructureId' => $metaDataId,
                            'id' => $metaValueId,
                            'userIds' => array(
                                array('userId' => Input::numeric('replyUserId'))
                            )
                        );
                        $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'send_mention_notification', $paramsMentions);                    
                    }
                    
                    if ($mentionData) {
                        
                        $mentionsUserIds = array();
                        $mentions = json_decode(html_entity_decode($mentionData, ENT_QUOTES), true);
                        
                        foreach ($mentions as $menrow) {
                            
                            $dataMention = array(
                                'ID'               => getUID(),
                                'COMMENT_ID'       => $data['ID'],
                                'RECORD_ID'        => $menrow['id'],
                                'NAME'             => $menrow['name'],
                                'TABLE_NAME'       => issetVar($menrow['tablename']),
                                'CREATED_USER_ID'      => $data['CREATED_USER_ID'],
                                'CREATED_DATE'         => $data['CREATED_DATE']
                            );
                            array_push($mentionsUserIds, array('userId' => $menrow['id']));
                            
                            $this->db->AutoExecute('ECM_COMMENT_MENTION', $dataMention);
                        }

                        $paramsMentions = array(
                            'refStructureId' => $metaDataId,
                            'id' => $metaValueId,
                            'userIds' => $mentionsUserIds
                        );
                        $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'send_mention_notification', $paramsMentions);                                            
                    }
                    
                    $response = array('status' => 'success');
                }
                
            } else {
                $response = array('status' => 'error', 'message' => 'Invalid id!');
            }
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        return $response;
    }
    
    public function updateCommentProcessModel()
    {
        try {
            
            $commentId = Input::numeric('commentId');
            
            if ($commentId) {
                
                $sessionUserKeyId = Ue::sessionUserKeyId();
                $id1Ph = $this->db->Param(0);
                $id2Ph = $this->db->Param(1);
                
                $checkId = $this->db->GetOne("
                    SELECT 
                        ID 
                    FROM ECM_COMMENT 
                    WHERE ID = $id1Ph 
                        AND CREATED_USER_ID = $id2Ph", 
                    array($commentId, $sessionUserKeyId)
                );
                
                if ($checkId) {
                    
                    $data = array(
                        'IS_MODIFIED'      => 1, 
                        'COMMENT_TEXT'     => Input::post('commentText'), 
                        'MODIFIED_DATE'    => Date::currentDate(), 
                        'MODIFIED_USER_ID' => $sessionUserKeyId
                    );

                    $this->db->AutoExecute('ECM_COMMENT', $data, 'UPDATE', 'ID = '.$commentId);
                    
                    $response = array('status' => 'success');
                    
                } else {
                    $response = array('status' => 'error', 'message' => 'Access denied!');
                }
                
            } else {
                $response = array('status' => 'error', 'message' => 'Invalid id!');
            }
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function saveCommentReactionModel()
    {
        try {
            
            $structureId    = Input::numeric('structureId');
            $recordId       = Input::numeric('recordId');
            $commentId      = Input::numeric('commentId');
            $reactionTypeId = Input::numeric('reactionTypeId');

            if ($structureId && $recordId && $commentId && $reactionTypeId) {
                
                $sessionUserKeyId = Ue::sessionUserKeyId();
                $id1Ph = $this->db->Param(0);
                $id2Ph = $this->db->Param(1);
                
                $checkId = $this->db->GetOne("
                    SELECT 
                        ID 
                    FROM ECM_REACTION_LOG 
                    WHERE COMMENT_ID = $id1Ph
                        AND CREATED_USER_ID = $id2Ph", 
                    array($commentId, $sessionUserKeyId)
                );
                
                if ($checkId) {
                    
                    $this->db->Execute("DELETE FROM ECM_REACTION_LOG WHERE ID = $id1Ph", array($checkId));
                    
                } else {
                    
                    $data = array(
                        'ID'               => getUID(), 
                        'REF_STRUCTURE_ID' => $structureId,
                        'RECORD_ID'        => $recordId, 
                        'REACTION_TYPE_ID' => $reactionTypeId, 
                        'IS_ACTIVE'        => 1, 
                        'CREATED_USER_ID'  => $sessionUserKeyId, 
                        'CREATED_DATE'     => Date::currentDate(), 
                        'COMMENT_ID'       => $commentId
                    );
                    
                    $this->db->AutoExecute('ECM_REACTION_LOG', $data);
                }
                
                $response = array('status' => 'success', 'message' => 'success');
                
            } else {
                $response = array('status' => 'error', 'message' => 'Invalid id!');
            }
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function removeCommentProcessModel()
    {
        try {
            
            $commentId = Input::numeric('commentId');
            
            if ($commentId) {
                
                $sessionUserKeyId = Ue::sessionUserKeyId();
                $id1Ph = $this->db->Param(0);
                $id2Ph = $this->db->Param(1);
                
                $checkId = $this->db->GetOne("
                    SELECT 
                        ID 
                    FROM ECM_COMMENT 
                    WHERE ID = $id1Ph 
                        AND CREATED_USER_ID = $id2Ph", 
                    array($commentId, $sessionUserKeyId)
                );
                
                if ($checkId) {
                    
                    if (DB_DRIVER == 'oci8') {
                
                        $subSql = "
                            SELECT 
                                ID  
                            FROM ECM_COMMENT 
                                CONNECT BY 
                                NOCYCLE 
                                PRIOR ID = PARENT_ID  
                                START WITH ID = $id1Ph";

                    } elseif (DB_DRIVER == 'postgres9') {

                        $subSql = "
                            WITH RECURSIVE TMP_ECM_COMMENT AS 
                            (
                                SELECT 
                                    U1.ID,
                                    U1.PARENT_ID 
                                FROM ECM_COMMENT U1 
                                WHERE ID = $id1Ph 

                                UNION ALL 

                                SELECT 
                                    U2.ID, 
                                    U2.PARENT_ID 
                                FROM ECM_COMMENT U2 
                                    JOIN TMP_ECM_COMMENT ON TMP_ECM_COMMENT.PARENT_ID = U2.ID 
                            ) SELECT * FROM TMP_ECM_COMMENT";
                    }
                    
                    $this->db->Execute("UPDATE ECM_COMMENT SET IS_DELETED = 1 WHERE ID IN ($subSql)", array($commentId));
                    
                    $response = array('status' => 'success');
                    
                } else {
                    $response = array('status' => 'error', 'message' => 'Access denied!');
                }
                
            } else {
                $response = array('status' => 'error', 'message' => 'Invalid id!');
            }
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function buildCommentList($reaction, $rows, $replyLabel, $editLabel, $deleteLabel, $commentStructureId = '', $depth = 0, $parent = 0)
    {
        $comment = array();
        
        if ($depth == 0) {
            $comment[] = '<ul class="media-list">';
        }
        
        foreach ($rows as $k => $row) {
            
            if (!array_find_val($rows, 'COMMENT_ID', $row['PARENT_ID'])) {
                $row['PARENT_ID'] = 0;
            }
            
            if ($row['PARENT_ID'] == $parent) { 
                
                $isChild = array_find_val($rows, 'PARENT_ID', $row['COMMENT_ID']);
                    
                if ($depth == 0) {
                    $comment[] = '<li class="media flex-column flex-md-row border-top-1 mt0 border-gray pt-2 pb-1" data-comment-id="'.$row['COMMENT_ID'].'" data-user-id="'.$row['USER_ID'].'">';
                } else {
                    $comment[] = '<div class="media flex-column flex-md-row border-top-1 mt0 border-gray pt-2 pb-1" data-comment-id="'.$row['COMMENT_ID'].'" data-user-id="'.$row['USER_ID'].'">';
                }

                    $comment[] = '<div class="mr-md-1 mb-2 mb-md-0">';
                        $comment[] = Ue::getFullUrlPhoto($row['PICTURE'], 'class="rounded-circle avatar" width="36" height="36"');
                    $comment[] = '</div>';

                    $comment[] = '<div class="media-body">';

                        $comment[] = '<div class="p-2 pb3" style="background-color: #F3F4F6;border-radius: 0.5rem;">';
                        
                        $comment[] = '<div class="media-title">';
                            $comment[] = '<a href="javascript:;" class="font-weight-semibold" tabindex="-1">'.($row['FIRST_NAME'] ? $row['FIRST_NAME'] : $row['USERNAME']).'</a>';
                            $comment[] = '<span class="text-muted ml-3 font-size-sm">'.Date::formatter($row['CREATED_DATE'], 'Y/m/d H:i').'</span>';
                            
                            if ($commentStructureId) {
                                
                                $comment[] = '<div class="dropdown bp-comment-workflow-btn d-inline-block ml-3">
                                    <a href="#" data-current-status-id="'.$row['WFM_STATUS_ID'].'" class="badge badge-primary badge-pill dropdown-toggle" data-toggle="dropdown" style="padding-top: 3px;padding-bottom: 3px;font-size: 11px; background-color: '.$row['WFM_STATUS_COLOR'].'">'.$row['WFM_STATUS_NAME'].'</a>
                                    <div class="dropdown-menu dropdown-menu-right"></div>
                                </div>';
                            }
                            
                        $comment[] = '</div>';

                        $comment[] = '<p class="mb-2 line-height-normal">'.html_entity_decode($row['COMMENT_TEXT'], ENT_QUOTES, 'UTF-8').'</p>';
                        
                        $comment[] = '</div>';

                        $comment[] = '<ul class="list-inline list-inline-dotted font-size-sm">';

                            if ($reaction) {

                                $reactionId = $reaction['ID'];
                                $reactionName = $reaction['NAME'];

                                $comment[] = '<li class="list-inline-item">';

                                    if (!$row['OWN_REACTION_ID']) {
                                        $comment[] = '<a href="javascript:;" class="text-secondary" tabindex="-1" data-bp-comment-rc="0" data-rtid="'.$reactionId.'"><i class="fa fa-thumbs-o-up font-size-14"></i> '.$reactionName.'</a>';
                                    } else {
                                        $comment[] = '<a href="javascript:;" class="text-primary" tabindex="-1" data-bp-comment-rc="1" data-rtid="'.$reactionId.'"><i class="fa fa-thumbs-up font-size-14"></i> '.$reactionName.'</a>';
                                    }

                                $comment[] = '</li>';
                            }

                            $comment[] = '<li class="list-inline-item"><a href="javascript:;" tabindex="-1" class="text-secondary" data-bp-comment-reply="1">'.$replyLabel.'</a></li>';

                            if ($row['IS_OWN'] == '1') {
                                $comment[] = '<li class="list-inline-item"><a href="javascript:;" tabindex="-1" class="text-secondary" data-bp-comment-edit="1">'.$editLabel.'</a></li>';
                                $comment[] = '<li class="list-inline-item"><a href="javascript:;" tabindex="-1" class="text-secondary" data-bp-comment-remove="1">'.$deleteLabel.'</a></li>';
                            }

                        $comment[] = '</ul>';
                        
                        if ($isChild) {
                            $comment[] = $this->buildCommentList($reaction, $rows, $replyLabel, $editLabel, $deleteLabel, $commentStructureId, $depth + 1, $row['COMMENT_ID']);
                        }
                        
                    $comment[] = '</div>';

                if ($depth == 0) {    
                    $comment[] = '</li>';
                } else {
                    $comment[] = '</div>';
                }
            }
        }
        
        if ($depth == 0) {
            $comment[] = '</ul>';
        }
        
        return implode('', $comment);
    }
    
    public static function uploadFileComment($value) {
        $value = explode(',', $value);
        $fileData = @base64_decode($value[1]);

        if ($fileData) {
            $f = finfo_open();
            $mimeType = finfo_buffer($f, $fileData, FILEINFO_MIME_TYPE);

            if ($mimeType == 'text/plain') {
                return array('status' => 'error', 'message' => 'Wrong content type!');
            } else {
                $fileExtension = mimeToExt($mimeType);

                $filePath = Mdwebservice::bpUploadGetPath();
                $fileUrl = $filePath.getUID().'.'.$fileExtension;

                file_put_contents($fileUrl, $fileData);

                return $fileUrl;
            }

        }
        return '';
    }        

}