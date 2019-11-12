<?php
class Contact{
    #--> Properties
    var $id;
    var $name;
    var $email;
    var $phoneNumber;
    var $idUser;
    #end properties
    #Contruct function
    function __construct($id,$name,$email,$phoneNumber,$idUser) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->idUser = $idUser;
    }
    /**
     * Kết nối với cơ sở dữ liệu mysql
     */
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
     * Lấy danh sách danh bạ theo id tag
     * @param idTag
     * @return ListContact
     */
    static function getListContactByTag($idTag){
        $con = Contact::connect();
        $sql = "SELECT * from danhba WHERE id in (SELECT id_danhba FROM tag_danhba WHERE tag_danhba.id_tag = $idTag)";
        $result =$con->query($sql);
        $contact = array();
        while($row = $result->fetch_assoc()){
            array_push($contact,new Contact($row["id"],$row["name"],$row["email"],$row["phone"],$row["id_user"]));
        }
        return $contact;
    }
    /**
     * Lấy danh sách danh bạ theo id user
     * @param idUser
     * @return ListContact
     */
    static function getList($idUser){
        $con = Contact::connect();
        $sql = "SELECT * FROM danhba WHERE id_user=".$idUser;
        $result =$con->query($sql);
        $contact = array();
        while($row = $result->fetch_assoc()){
            array_push($contact,new Contact($row["id"],$row["name"],$row["email"],$row["phone"],$row["id_user"]));
        }
        return $contact;
    }
    /**
     * Thêm mới một thông tin danh bạ
     * @param name,phone,email,iduser
     * @return true : thêm thành công
     *         false : thêm thất bại
     */
    static function insert($name,$sdt,$email,$idUser){
        $con = Contact::connect();
        $sql = "INSERT INTO  danhba(name,phone,email,id_user) VALUES('$name','$sdt','$email','$idUser')";
        $result =$con->query($sql);
        return $result;
    }
     /**
     * Cập nhật một thông tin danh bạ
     * @param idContact,name,phone,email
     * @return true : thêm thành công
     *         false : thêm thất bại
     */
    static function update($idContact,$name,$phone,$email){
        $con = Contact::connect();
        $sql = "UPDATE danhba SET name ='$name',phone='$phone',email='$email' where id=".$idContact;
        $result =$con->query($sql);
        return $result;
    }
     /**
     * Lấy id lớn nhất trong table danhba
     * @param iduser
     * @return idmax
     */
    static function getMaxId($idUser){
        $con = Contact::connect();
        $sql = "SELECT MAX(id) as 'id' FROM danhba WHERE id_user=".$idUser;
        $result =$con->query($sql);
        while($row = $result->fetch_assoc()){
            if(!empty($row))
                return $row["id"];
        }
        return null;
    }
     /**
     * Xóa một thông tin danh bạ
     * @param idContact
     * @return true : xóa thành công
     *         false : xóa thất bại
     */
    static function delete($id){
        $con = Contact::connect();
        $sql = "DELETE  FROM danhba WHERE id=".$id;
        $result =$con->query($sql);
        return $result;
    }
}
?>