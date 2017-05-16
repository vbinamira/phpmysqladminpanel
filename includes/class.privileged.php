<?php
require ($_SERVER['DOCUMENT_ROOT']."/includes/class.user.php");

class PrivilegedUser extends USER {
    private $roles = array();

    public function __construct() {
        parent::__construct();
    }

    // override User method
    public static function getByUsername($username) {
        $user_role = new USER();
        $stmt = $user_role->runQuery("SELECT id, email, created_at  FROM users WHERE name = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($id, $email, $created);
        $result = $stmt->fetch();

        if (!empty($result)) {
            $privUser = new PrivilegedUser();
            $privUser->user_id = $id;
            $privUser->username = $username;
            $privUser->email= $email;
            $privUser->created = $created;
            $privUser->initRoles();
            return $privUser;
        } else {
            return false;
        }
    }

    // populate roles with their associated permissions
    protected function initRoles() {
        $this->roles = array();
        $user_role = new USER();
        $id = $_SESSION['userSession'];
        $stmt = $user_role->runQuery("SELECT t1.role_id, t2.name FROM role_user as t1 JOIN roles as t2 ON t1.role_id = t2.id
            WHERE t1.user_id = ? ");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($roleID, $name);

        while($row = $stmt->fetch()) {
            $this->roles[$name] = Role::getRolePerms($roleID);
        }
    }

    // check if user has a specific privilege
    public function hasPrivilege($perm) {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($perm)) {
                return true;
            }
        }
        return false;
    }

}