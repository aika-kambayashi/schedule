<?php

class UserRepository extends DbRepository {

    public function insert($mail, $password, $name, $department_id) {
        $password = $this->hashPassword($password);
        $now = new DateTime();
        $sql = '
            insert into user
                (mail,password,name,department_id,modified_at,created_at) 
            values
                (:mail,:password,:name,:department_id,:modified_at,:created_at)
        ';
        $stmt = $this->execute($sql, [
            ':mail'         => $mail,
            ':password'     => $password,
            ':name'         => $name,
            ':department_id' => $department_id,
            ':modified_at'  => $now->format('Y-m-d H:i:s'),
            ':created_at'   => $now->format('Y-m-d H:i:s')
        ]);
    }

    public function hashPassword($password) {
        return sha1($password . 'SecretKey');
    }
    
    public function fetchByMail($mail) {
        $sql = 'select * from user where mail = :mail';
        return $this->fetch($sql, [':mail' => $mail]);
    }

    public function fetchDept($department_id) {
        $sql = 'select * from department where department_id = :department_id';
        return $this->fetch($sql, [':department_id' => $department_id]);
    }

    public function fetchDeptByMail($mail) {
        $sql = 'select d.department_id,depart_name
            from department d
            left join user u
            on u.department_id = d.department_id
            where mail = :mail
        ';
        return $this->fetch($sql, [':mail' => $mail]);
    }

    public function isUniqueMail($mail) {
        $sql = 'select count(user_id) as count from user where mail = :mail';
        $row = $this->fetch($sql, [':mail' => $mail]);
        if ($row['count'] === '0') {
            return true;
        }
        return false;
    }

    public function fetchByUserId($user_id) {
        $sql = 'select * from user where user_id = :user_id';
        return $this->fetch($sql, [':user_id' => $user_id]);
    }

    public function getDepartments() {
        $sql = 'select * from department';
        return $this->execute($sql);
    }
}