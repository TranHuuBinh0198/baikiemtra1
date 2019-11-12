<?php
    class User{
        var $id;
        var $email;
        var $password;
        var $fullname;

        function __construct($id,$email,$password=null,$fullname) {
            $this->id = $id;
            $this->email = $email;
            $this->password = $password;
            $this->fullname = $fullname;
        }

        static function connect(){
            $host = "localhost";
            $username = "root";
            $password = "";
            $dbname = "db_contact";
            $conn = new mysqli($host,$username,$password,$dbname);
            $conn->set_charset("utf8");
            if($conn->connect_error)
                die("Kết nối thất bại. /".$conn->connect_error);
            return $conn;
        }
    
        /**
         * xac thuc taii khoan nguoi dung
         * @* @param $userName String ten dang nhap
         * * @param $password String mat khau
         * @return User hoac null neu khong ton tai
         */
        static function authentication($email,$password){
            $con = User::connect();
            $sql = "SELECT * FROM users WHERE email='".$email."' and password='".$password."'";
            $result =$con->query($sql);
            $contact = array();
            while($row = $result->fetch_assoc()){
                if($row != ""){
                    return new User($row["id"],$row["email"],null,$row["fullname"]);
                }
            
            }
            return null;
        }


    }
?>