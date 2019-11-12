<?php
    class TagContact{
        var $id;
        var $name;
        var $num;

        function __construct($id,$name,$num) {
            $this->id = $id;
            $this->name = $name;
            $this->num = $num;
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
         * get danh sách tag theo id user và số lượng liên hệ của tag
         * @* @param $idUser String id user
         * @return listTag hoac null neu khong tìm thấy
         */
        static function getListTag($idUser){
            $con = TagContact::connect();
            $sql = "SELECT tag.id,tag.name,COUNT(tag_danhba.id_tag) as 'num' FROM tag_danhba RIGHT JOIN tag ON tag_danhba.id_tag=tag.id LEFT JOIN danhba on danhba.id = tag_danhba.id_danhba WHERE tag.id_user =$idUser and danhba.id_user = $idUser GROUP BY tag_danhba.id_tag";
            $result =$con->query($sql);
            $listTag = array();
            while($row = $result->fetch_assoc()){
                array_push($listTag,new TagContact($row["id"],$row["name"],$row["num"]));
            }
            return $listTag;
        }
          /**
         * Thêm mới một item trong table tag_danhba
         * @* @param idContact,ArrayTag
         * @return true thêm thành công
         * @return false thêm thất bại
         */
        static function addTagContact($idContact,$arrTag){
            $con = TagContact::connect();
            foreach ($arrTag as $key => $value) {
                $sql = "INSERT INTO tag_danhba(id_danhba,id_tag) VALUES($idContact,$value)";
                if($con->query($sql) == false){
                    echo "lOI : ".$con->error;
                    return false;
                }
            }
            return true;
        }
        /**
         * Xóa một item trong table tag_danhba
         * @* @param idContact
         * @return true Xóa thành công
         * @return false Xóa thất bại
         */
        static function deleteTagContag($idContact){
            $con = TagContact::connect();
            $sql = "DELETE  FROM tag_danhba WHERE id_danhba=".$idContact;
            $result =$con->query($sql);
            return $result;
        }
        /**
         * Xóa một item trong table tag_danhba
         * @* @param idTag
         * @return true Xóa thành công
         * @return false Xóa thất bại
         */
        static function deleteTag($idTag){
            $con = TagContact::connect();
            $sql = "DELETE  FROM tag_danhba WHERE id_tag=".$idTag;
            $result =$con->query($sql);
            return $result;
        }
        
    }
?>