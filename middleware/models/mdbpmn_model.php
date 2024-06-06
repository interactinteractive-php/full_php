<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdbpmn_Model extends Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getGraphRowModel() {
        
        $selectedRow = Input::post('selectedRow');
        $dataRow     = Arr::decode($selectedRow);

        if (isset($dataRow['dataRow']['id'])) {
            $val = $this->db->GetOne("SELECT GRAPH_XML FROM EIS_BPM_PROCESS WHERE ID = ".$this->db->Param(0), array($dataRow['dataRow']['id']));
            return $val;
        }
        
        return null;
    }
    
    public function getBpmLinkModel($metaDataId) {
        $row = $this->db->GetRow("SELECT * FROM META_BPM_LINK WHERE META_DATA_ID = ".$this->db->Param(0), array($metaDataId));
        return $row;
    }
    
    public function getBpmGraphXmlById($id) {
        $val = $this->db->GetOne("SELECT GRAPH_XML FROM META_BPM_LINK WHERE META_DATA_ID = ".$this->db->Param(0), array($id));
        return $val;
    }
    
    public function saveBpmGraphXmlModel() {
        
        $graphXml   = Input::postNonTags('graphXml');
        $metaDataId = Input::post('id');
        
        $bpmLinkData = array(
            'ID'           => getUID(),
            'META_DATA_ID' => $metaDataId
        );

        if (self::isExistsMetaLink('META_BPM_LINK', 'META_DATA_ID', $metaDataId)) {

            unset($bpmLinkData['ID']);

            $this->db->AutoExecute('META_BPM_LINK', $bpmLinkData, 'UPDATE', 'META_DATA_ID = '.$metaDataId);
            $this->db->UpdateClob('META_BPM_LINK', 'GRAPH_XML', $graphXml, 'META_DATA_ID = '.$metaDataId);

        } else {

            $this->db->AutoExecute('META_BPM_LINK', $bpmLinkData);

            if ($graphXml) {
                $this->db->UpdateClob('META_BPM_LINK', 'GRAPH_XML', $graphXml, 'META_DATA_ID = '.$metaDataId);
            }
        }
        
        return array('status' => 'success', 'message' => $this->lang->line('msg_save_success'));
    }
    
    public function getBpmGraphXmlByConfigId() {
        
        $id         = Input::post('id');
        $tableName  = Input::post('tableName');
        $columnName = Input::post('columnName');
        
        $val = $this->db->GetOne("SELECT $columnName FROM $tableName WHERE ID = ".$this->db->Param(0), array($id));
        
        return $val;
    }
    
    public function saveBpmGraphXmlByConfigModel() {
        
        try {
            
            $id         = Input::post('id');
            $tableName  = Input::post('tableName');
            $columnName = Input::post('columnName');
            $graphXml   = Input::postNonTags('graphXml');
            $idPh       = $this->db->Param(0);
            
            $result = $this->db->UpdateClob($tableName, $columnName, $graphXml, 'ID = '.$id);
            
            $this->db->Execute("DELETE FROM EIS_BPM_PROCESS WHERE ID IN (SELECT TRG_PROCESS_ID FROM EIS_BPM_TRANSITION WHERE MAIN_PROCESS_ID = $idPh)", array($id));
            $this->db->Execute("DELETE FROM EIS_BPM_TRANSITION WHERE MAIN_PROCESS_ID = $idPh", array($id));
            
            if ($result) {
                
                $xmlToArr = Xml::createArray($graphXml);
        
                $cells = $xmlToArr['mxGraphModel']['root']['mxCell'];
                $newCells = $saveArr = array();

                foreach ($cells as $k => $cell) {
            
                    if (!isset($cell['mxGeometry']) || (!isset($cell['@attributes']) && !isset($cell['@attributes']['style']))) {
                        unset($cells[$k]);
                        continue;
                    }

                    $style = $cell['@attributes']['style'];
                    $textShape = substr($style, 0, 5);

                    if ($textShape == 'text;' && !isset($cell['@attributes']['connectable'])) {

                        unset($cells[$k]);
                        continue;
                    } 

                    $newCells[$cell['@attributes']['id']] = $cell;
                }

                foreach ($newCells as $c => $cell) {

                    $connectorStyle = $cell['@attributes']['style'];
                    $connectorShape = substr($connectorStyle, 0, 17);
                    $connectorShapeBlock = substr($connectorStyle, 0, 15);

                    if (isset($cell['@attributes']['connectable']) || $connectorShape == 'endArrow=classic;' || $connectorShapeBlock == 'endArrow=block;') {

                        $parent = $cell['@attributes']['parent'];

                        if (($connectorShape != 'endArrow=classic;' && $connectorShapeBlock != 'endArrow=block;') && !isset($newCells[$parent])) {
                            continue;
                        }

                        if (!isset($newCells[$parent])) {
                            $parentCell = $cell;
                        } else {
                            $parentCell = $newCells[$parent];
                        }

                        $swimlaneShape = substr($parentCell['@attributes']['style'], 0, 9);

                        if ($swimlaneShape == 'swimlane;') {
                            $parentCell = $cell;
                        }

                        $source = issetParam($parentCell['@attributes']['source']);
                        $target = issetParam($parentCell['@attributes']['target']);

                        if ($source && $target && isset($newCells[$source]) && isset($newCells[$target])) {

                            $sourceCell = $newCells[$source];
                            $targetCell = $newCells[$target];

                            /*$saveArr[] = array(
                                'source' => array(
                                    'id'    => $sourceCell['@attributes']['id'], 
                                    'value' => $sourceCell['@attributes']['value'], 
                                ), 
                                'target' => array(
                                    'id'    => $targetCell['@attributes']['id'], 
                                    'value' => $targetCell['@attributes']['value'], 
                                )
                            );*/
                            
                            $saveArr[] = array(
                                'id'    => $sourceCell['@attributes']['id'], 
                                'value' => $sourceCell['@attributes']['value'], 
                            );
                            
                            $saveArr[] = array(
                                'id'    => $targetCell['@attributes']['id'], 
                                'value' => $targetCell['@attributes']['value'], 
                            );

                            unset($newCells[$source]);
                            unset($newCells[$target]);
                        } 
                    }
                }
                
                if ($saveArr) {
                    
                    $currentDate = Date::currentDate();
                    $userId = Ue::sessionUserKeyId();
                    
                    $i = 0;
                    
                    foreach ($saveArr as $save) {
                        
                        if ($save['value'] != '') {
                            
                            $dtlId = getUID();
                            $sourceData = array(
                                'ID'              => $dtlId, 
                                'NAME'            => $save['value'], 
                                'GRAPH_OBJECT_ID' => $save['id'], 
                                'CREATED_DATE'    => $currentDate, 
                                'CREATED_USER_ID' => $userId
                            );
                            $this->db->AutoExecute('EIS_BPM_PROCESS', $sourceData);
                            
                            if ($i == 0) {
                                
                                $transData = array(
                                    'ID'              => getUID(), 
                                    'MAIN_PROCESS_ID' => $id, 
                                    'TRG_PROCESS_ID'  => $dtlId
                                );
                                $this->db->AutoExecute('EIS_BPM_TRANSITION', $transData);
                                
                            } else {
                                
                                $transData = array(
                                    'ID'              => getUID(), 
                                    'MAIN_PROCESS_ID' => $id, 
                                    'SRC_PROCESS_ID'  => $dtlIdSecond, 
                                    'TRG_PROCESS_ID'  => $dtlId
                                );
                                $this->db->AutoExecute('EIS_BPM_TRANSITION', $transData);
                            }
                            
                            $dtlIdSecond = $dtlId;
                            
                            $i++;
                        }
                    }
                }
                
                return array('status' => 'success', 'message' => $this->lang->line('msg_save_success'));
            } else {
                return array('status' => 'error', 'message' => $this->lang->line('msg_error_success'));
            }
            
        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }

    public function getMetaDataModel($metaDataId) {
        
        $metaDataIdPh = $this->db->Param(0);
        $bindVars = array($this->db->addQ($metaDataId));
        
        $row = $this->db->GetRow("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME, 
                MD.META_TYPE_ID, 
                MT.META_TYPE_CODE, 
                MD.DESCRIPTION, 
                MD.ADDON_XML_DATA, 
                MD.COPY_COUNT 
            FROM META_DATA MD 
                LEFT JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
            WHERE MD.META_DATA_ID = $metaDataIdPh", $bindVars);

        return $row;
    }        

}