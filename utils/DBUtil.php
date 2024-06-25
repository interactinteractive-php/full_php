<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class DBUtil extends DBSql {
    
    public static function toShortName($driverName) 
    {
        $result = strtr($driverName, 
            [
                'oracle'      => 'oci8', 
                'oci'         => 'oci8', 
                'mysql'       => 'mysqli', 
                'mssql'       => 'mssql', 
                'mysqli'      => 'mysqli', 
                'postgresql'  => 'postgres9', 
                'postgre'     => 'postgres9', 
                'postgres'    => 'postgres9', 
                'postgre_sql' => 'postgres9'
            ]
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
        $array = [];
        
        foreach ($data as $row) {
            
            $array[] = [
                'dbId'   => Crypt::encrypt($row['ID'], 'db00x'), 
                'dbName' => $row['DB_NAME']
            ];
        }
        
        return $array;
    }
    
    public static function getConnectionByDbId($dbId) {
        
        global $db;
        
        $row = $db->GetRow("SELECT * FROM MDM_CONNECTIONS WHERE ID = " . $db->Param(0), [$dbId]);
        
        return $row;
    }
    
    public static function getConnectionByCustomerId($dbId) {
        
        global $db;
        
        $row = $db->GetRow("SELECT * FROM MDM_CONNECTIONS WHERE CUSTOMER_ID = " . $db->Param(0), [$dbId]);
        
        return $row;
    }
    
}