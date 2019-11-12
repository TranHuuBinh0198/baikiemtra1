<?php
    class Tag{
        var $id;
        var $name;
        var $slug;
        var $isUser;

        function __construct($id,$name,$slug,$idUser) {
            $this->id     = $id;
            $this->name   = $name;
            $this->slug   = $slug;
            $this->idUser = $idUser;
        }

        static function connect(){
            $host     = "localhost";
            $username = "root";
            $password = "";
            $dbname   = "db_contact";
            $conn     = new mysqli($host,$username,$password,$dbname);
            $conn->set_charset("utf8");
            if($conn->connect_error)
                die("Kết nối thất bại. /".$conn->connect_error);
            return $conn;
        }
    
        /**
         * lấy danh sách Tag theo id user
         * @* @param idUser
         * 
         * @return ListTag
         */
        static function getListTag($idUser){
            $con = Tag::connect();
            $sql = "SELECT * FROM tag WHERE id_user=".$idUser;
            $result =$con->query($sql);
            $tag = array();
            while($row = $result->fetch_assoc()){
                array_push($tag,new Tag($row["id"],$row["name"],$row["slug"],$row["id_user"]));
            }
            return $tag;
        }
        /**
         * Lấy danh sách Tag
         * @* @param idUser
         * @return ListTag
         */
        static function getList($idUser){
            $con = Tag::connect();
            $sql = "SELECT tag.id FROM tag,tag_danhba where tag_danhba.id_tag=tag.id and tag.id_user = $idUser GROUP BY tag_danhba.id_tag";
            $result =$con->query($sql);
            $tag = array();
            while($row = $result->fetch_assoc()){
                array_push($tag,$row["id"]);
            }
            return $tag;
        }
         /**
         * Lấy danh sách Tag của một thông tin liên hệ
         * @* @param idContact
         * @return ListTag
         */
        static function getTagOfContact($idContact){
            $con = Tag::connect();
            $sql = "SELECT tag.id FROM tag WHERE id in ( SELECT DISTINCT tag_danhba.id_tag from danhba,tag_danhba WHERE danhba.id = tag_danhba.id_danhba and danhba.id = $idContact)";
            $result =$con->query($sql);
            $contact = array();
            while($row = $result->fetch_assoc()){
                array_push($contact,$row["id"]);
            }
            return $contact;
        }
         /**
         * Thêm mới một Tag
         * @* @param name
         * 
         */
        static function insertTag($name,$idUser){
            $con = Tag::connect();
            $sql = "INSERT INTO  tag(name,id_user) VALUES('$name',$idUser)";
            $result =$con->query($sql);
            return $result;
        }
        static function updateTag($id,$name){
            $con = Tag::connect();
            $sql = "UPDATE tag set name='$name' WHERE id=".$id;
            $result =$con->query($sql);
            return $result;
        }
        static function deleteTag($idTag){
            $con = Tag::connect();
            $sql = "DELETE  FROM tag WHERE id=".$idTag;
            $result =$con->query($sql);
            return $result;
        }
    }
?>