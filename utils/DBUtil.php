<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class DBUtil extends DBSql {
    
    public static function toShortName($driverName) 
    {
        $result = str_replace(
            array('oracle', 'mysql', 'mssql', 'mysqli', 'postgresql'), 
            array('oci8', 'mysql', 'mssql', 'mysqli', 'postgres9'), 
            $driverName
        );
        
        return $result;
    }
    
    public static function getCountConnection() {
        
        global $db;
        
        $count = $db->GetOne("SELECT COUNT(ID) FROM MDM_CONNECTIONS WHERE IS_ACTIVE = 1 AND (IS_EXTERNAL IS NULL OR IS_EXTERNAL = 0)");
        
        return $count;
    }
    
    public static function getConnections() {
        
        global $db;
        
        $data = $db->GetAll("SELECT ID, DB_NAME, USER_NAME FROM MDM_CONNECTIONS WHERE IS_ACTIVE = 1 AND (IS_EXTERNAL IS NULL OR IS_EXTERNAL = 0) ORDER BY ID ASC");
        $array = array();
        
        foreach ($data as $row) {
            
            $array[] = array(
                'dbId'   => Crypt::encrypt($row['ID'], 'db00x'), 
                'dbName' => $row['DB_NAME']
            );
        }
        
        return $array;
    }
    
    public static function getConnectionByDbId($dbId) {
        
        global $db;
        
        $row = $db->GetRow("SELECT * FROM MDM_CONNECTIONS WHERE ID = " . $db->Param(0), array($dbId));
        
        return $row;
    }
    
}