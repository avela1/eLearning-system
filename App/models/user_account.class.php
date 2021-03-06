<?php

class User_account {

    private $error = "";
    
    public function login($POST) {

        $data = array();
        $db = Database::getInstance();
        $_SESSION['error'] ="";
        $data['username'] = trim($POST['username']);
        $data['password'] = md5(trim($POST['password']));


        if(empty($data['username']) || empty($data['password'])) {
            $this -> error .= "Please fill the form correctly. <br>";
        }

        if($this -> error == "") {

            $sql = "SELECT * FROM useraccount where Username=:username  && Pass=:password limit 1";
            $result = $db -> read($sql, $data);

            
            if(is_array($result)) {
               
                $_SESSION['username'] = $result[0]->Username;
                $_SESSION['userrole'] = $result[0]->UserType;

                if($_SESSION['userrole'] == "Administrator") {
                    $arry = array();
                    $arry['username'] = $_SESSION['username'];

                    $sql1 = "SELECT `ID` FROM instructorinfo where Username=:username limit 1";
                    $result = $db -> read($sql1, $arry);
                    $_SESSION['ID'] = $result[0]->ID;

                    header("Location: ". ROOT . "admin/");
                    die;
                } else if($_SESSION['userrole'] == "Student") {

                    $arry = array();
                    $arry['username'] = $_SESSION['username'];

                    $sql1 = "SELECT `ID` FROM instructorinfo where Username=:username limit 1";
                    $result = $db -> read($sql1, $arry);
                    $_SESSION['ID'] = $result[0]->ID;

                    header("Location: ". ROOT . "student/");
                    die;
                } else if($_SESSION['userrole'] == "Teacher") {

                    $arry = array();
                    $arry['username'] = $_SESSION['username'];
                    $sql1 = "SELECT `ID` FROM instructorinfo where Username=:username limit 1";
                    $result = $db -> read($sql1, $arry);
                    $_SESSION['ID'] = $result[0]->ID;

                    header("Location: ". ROOT . "teacher/");
                    die;
                } else {
                    $_SESSION['error'] ="incorrect username or password";
                }
              
            }
            $_SESSION['error'] ="incorrect username or password";
        } else {
            $_SESSION['error'] = "Please fill the form correctly.";
        }
    }

    public function check_login() {
        

        if(isset($_SESSION['username']) && isset($_SESSION['userrole'])) {
            
            $db = Database::getInstance();
            $arr['username'] = $_SESSION['username'];

            if($_SESSION['userrole'] == "Administrator") {
                $sql = "select * from admininfo where Username = :username limit 1";
            } elseif($_SESSION['userrole'] == "Student") {
                $sql = "select * from studentinfo where Username = :username limit 1";
            } else {
                $sql = "select * from instructorinfo where Username = :username limit 1";
            }
            
            $check = $db->read($sql, $arr);
            if($_SESSION['userrole'] == "Student"){
                $_SESSION['Batch'] = $check[0]->Batch;
            }

            if(is_array($check)) {
                return $check;
            }
        }
        return false;
    }

    public function logout() { 
        if(isset($_SESSION['username']) && isset($_SESSION['userrole'])) {
            unset($_SESSION['username']);
            unset($_SESSION['userrole']);
            unset($_SESSION['ID']);
            unset($_SESSION['Batch']);
            unset($_SESSION['crs_id']);
            header("Location: ". ROOT . "login");
            die;
        }
    }

}