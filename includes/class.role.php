<?php
include ($_SERVER['DOCUMENT_ROOT'].'/config/class.dbconnect.php');
class Role
{
    private $permissions;
    
    protected function __construct() { 
        $this->permissions = array();
    }
    // return a role object with associated permissions
    public static function getRolePerms($role_id) {
        $role = new Role();
        $database = new database();
        $db = $database->dbconnect();
        $stmt = $db->prepare("SELECT t2.name FROM permission_role as t1 JOIN permissions as t2 ON t1.permission_id = t2.id WHERE t1.role_id = ? ");
        $stmt->bind_param('i', $role_id);
        $stmt->execute();
        $stmt->bind_result($name);
        while($stmt->fetch()) {
            $role->permissions[$name] = true;
        }
        return $role;
        
    }

    // check if a permission is set
    public function hasPermission($permission) {
        return isset($this->permissions[$permission]);
    }

}