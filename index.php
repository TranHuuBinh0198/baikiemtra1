<?php 
    session_start();
    require_once("models/user.php");
    require_once("models/contact.php");
    require_once("models/tagcontact.php");
    require_once("models/tag.php");
    if(!isset($_SESSION["user"])){
        header("location:login.php");
    }
    $user = unserialize($_SESSION["user"]);
   
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if($_POST["submit"] == "add"){
            $name = $_POST["name"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            Contact::insert($name,$phone,$email,$user->id);
            if(!empty($_POST["tag"])){
                $id = Contact::getMaxId($user->id);
                TagContact::addTagContact($id,$_POST["tag"]);
            }
        }
        if($_POST["submit"] == "update"){
            $id = $_POST["e_id"];
            $name = $_POST["e_name"];
            $email = $_POST["e_email"];
            $phone = $_POST["e_phone"];
            Contact::update($id,$name,$phone,$email);
            if(!empty($_POST["tag"])){
                TagContact::deleteTagContag($id);
                TagContact::addTagContact($id,$_POST["tag"]);
            }else{
                TagContact::deleteTagContag($id);
            }
        }
        if($_POST["submit"] == "addTag"){
            Tag::insertTag($_POST["nameTag"],$user->id);
        }
        if($_POST["submit"] == "editTag"){
            $id = $_POST["e_idtag"];
            $name = $_POST["e_tagname"];
            Tag::updateTag($id,$name);
        }
    }
    if(isset($_GET["action"])){
        switch ($_GET["action"]) {
            case 'xoa':
                $id = $_GET["id"];
                TagContact::deleteTagContag($id);
                Contact::delete($id);
                break;
            case 'xoaTag':
                $id = $_GET["id"];
                TagContact::deleteTag($id);
                Tag::deleteTag($id);
            default:
                # code...
                break;
        }
    }
    $listContact = Contact::getList($user->id);
    $listTag = TagContact::getListTag($user->id);
    $arrTag = Tag::getListTag($user->id);


     


    if(isset($_GET["tag"])){
        if($_GET["tag"] == "all"){
            $listContactByTag = $listContact;
        }else{
            $listContactByTag = Contact::getListContactByTag($_GET["tag"]);
        }
        
    }
    if(!empty($listContactByTag)){
        $list = $listContactByTag;
    }
    else {
        $list = $listContact;
    }
    //--> Pagination
    $size = 5;
    $page = 1;
    if(isset($_REQUEST['page'])){
        $page = $_REQUEST['page'];
    }
    
    $from = $size*($page-1);// 0 ,3, 6
    $to = ($size*$page) -1 ; // 2,5,8
    $arrPagi = array();
    for($i=$from;$i<=$to;$i++){
        if($i > count($list)-1)
            break;
        array_push($arrPagi,$list[$i]);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/all.css">
    <title>Danh ba</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <h2 style="text-align:center">CONTACT</h2>
            <div class="col-md-3">
                <div id="addtag">
                    <button type="button" class="btn btn-warning " data-toggle="modal" data-target="#addTag">Thêm nhãn</button>
                    <div id="addTag" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <form action="index.php" method="POST">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Thêm nhãn</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="usr">Tên:</label>
                                            <input type="text" class="form-control" id="nameTag" name="nameTag">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                    <button class="btn btn-success" type="submit" name="submit" value="addTag">Thêm nhãn</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="tag">
                    <div class="list-group">
                        <p href="#" class="list-group-item active">nhãn</p>
                        <a href="index.php?tag=all" class="list-group-item">Tất cả<span class="badge"><?php echo count($listContact) ?></span></a>
                        <?php 
                            foreach ($listTag as $key => $value) {
                        ?>
                            <div id="tag-m" class="list-group-item">
                                <a href="index.php?tag=<?php echo $value->id ?>" ><?php echo $value->name ?></a>
                                <span class="badge"><?php echo $value->num ?></span>
                                <div class="control">
                                    <div class="item">
                                        <p><i onclick="funcTag(this)" class="fas fa-pen"  data-toggle="modal" data-target="#editTag" tId="<?php echo $value->id ?>" tName="<?php echo $value->name ?>"></i></p>
                                        <div id="modal-tag">
                                            <i class="fas fa-trash-alt" data-toggle="modal" data-target="#deleteTag<?php echo $value->id ?>"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="deleteTag<?php echo $value->id ?>" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Xác nhận</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>Bạn có chắc muốn xóa nhãn không?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="index.php?action=xoaTag&id=<?php echo $value->id ?>"  class="btn btn-danger"><i class="fas fa-trash-alt"></i>Xóa</a> 
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                    </div>

                                </div>
                            </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6">
                        <div id="searchform">
                            <form action="" method="get">
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" name="search" id="search-form" class="form-control" placeholder="Search">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6" style="text-align:right">
                        <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#addContact"><i class="fas fa-plus"></i>Tạo liên hệ</button>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary">Xin chào : <?php echo $user->fullname ?></button>
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
                            <button type="button" class="btn btn-primary" style="display:none" id="iduser" value="<?php echo $user->id ?>"></button>
                        </div>
                    </div>
                </div>
                <div class="list-contact">
                    <h2 class="list-group-item active">Danh sách liên lạc</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th colspan="2">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="result">
                            <?php
                                foreach ($arrPagi as $key => $value) {
                                ?>
                                    <tr>
                                        <td><?php echo $value->name ?></td>
                                        <td><?php echo $value->email ?></td>
                                        <td><?php echo $value->phoneNumber ?></td>
                                        <td>
                                            <a href="" onclick="func(this)" eTag='<?php echo json_encode(Tag::getTagOfContact($value->id)) ?>' eName="<?php echo $value->name ?>" eEmail="<?php echo $value->email ?>"   eId="<?php echo $value->id ?>" ePhone="<?php echo $value->phoneNumber ?>" class="btn btn-success" data-toggle="modal" data-target="#editContact" ><i class="fas fa-edit"></i>Sửa</a> 
                                        </td>
                                        <td>
                                        <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#myModalDelete<?php echo $value->id ?>"><i class="fas fa-trash-alt"></i>Xóa</button>
                                            <div id="myModalDelete<?php echo $value->id ?>" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Xác nhận</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Bạn có chắc muốn xóa liên hệ không?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="index.php?action=xoa&id=<?php echo $value->id ?>"  class="btn btn-danger"><i class="fas fa-trash-alt"></i>Xóa</a> 
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                            <?php 
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-12" style="text-align:center">
                        <ul class="pagination">
                        <?php 
                            $numPage =  ceil(count($list)/$size);
                            for($i=1;$i<=$numPage;$i++){
                        ?>
                            <li><a href="?page=<?php echo $i ?>"><?php echo $i; ?></a></li>
                        <?php } ?>
                        </ul>
                    </div>
                   
                </div>
            </div>
            <div class="m-modal">
                <div id="addContact" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <form action="index.php" method="post">           
                        <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Thêm liên hệ</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="usr">Tên:</label>
                                        <input type="text" class="form-control" id="usr" name="name">
                                    </div>
                                    <div class="form-group">
                                        <label for="pwd">Email:</label>
                                        <input type="text" class="form-control" id="pwd" name="email">
                                    </div>
                                    <div class="form-group">
                                        <label for="pwd">Số điện thoại:</label>
                                        <input type="text" class="form-control" id="pwd" name="phone">
                                    </div>
                                    <div class="form-group">
                                        <label for="pwd">Gán nhãn:</label>
                                        <?php 
                                            foreach ($arrTag as $key => $value) {
                                        ?>
                                            <div class="checkbox">
                                                <label><input type="checkbox" name="tag[]" value="<?php echo $value->id ?>"><?php echo $value->name ?></label>
                                            </div>
                                        <?php
                                            }
                                        ?>
                                    </div> 
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" type="submit" value="add" name="submit">Thêm</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="editContact" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <form action="index.php" method="post">           
                        <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Chỉnh sửa liên hệ</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" id="eId" name="e_id" value="">
                                    </div>       
                                    <div class="form-group">
                                        <label for="usr">Tên:</label>
                                        <input type="text" class="form-control" id="e_name" name="e_name">
                                    </div>
                                    <div class="form-group">
                                        <label for="pwd">Email:</label>
                                        <input type="text" class="form-control" id="e_email" name="e_email">
                                    </div>
                                    <div class="form-group">
                                        <label for="pwd">Số điện thoại:</label>
                                        <input type="text" class="form-control" id="e_phone" name="e_phone">
                                    </div>
                                    <div class="form-group">
                                        <label for="pwd">Thay đổi nhãn:</label>
                                        <?php 
                                            foreach ($arrTag as $key => $value) {
                                        ?>
                                            <div class="checkbox">
                                                <label><input type="checkbox" class="tagP" name="tag[]" value="<?php echo $value->id ?>"><?php echo $value->name ?></label>
                                            </div>
                                        <?php
                                            }
                                        ?>
                                    </div> 
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-success" type="submit" name="submit" value="update">Cập nhật</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="editTag" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <form action="index.php" method="post">           
                        <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Chỉnh sửa nhãn</h4>
                                </div>
                                <div class="modal-body">    
                                    <input type="hidden" id="e_idtag" name="e_idtag" value=""> 
                                    <div class="form-group">
                                        <label for="usr">Tên:</label>
                                        <input type="text" class="form-control" id="e_tagname" name="e_tagname">
                                    </div>   
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-success" type="submit" name="submit" value="editTag">Cập nhật</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/app.js"></script>
    <script>
        $(document).ready(function(){
            var id = $('#iduser').val();
            $('#search-form').keyup(function(){
                var key = $(this).val();
                if(key != ''){
                    $.ajax({
                        url : "xuly.php",
                        method : "POST",
                        data : {search : key,iduser : id},
                        dataType : "text",
                        success: function(data)
                        {
                            $('#result').html(data);
                        }
                    })
                }
                else{
                    $('#result').html('');
                    $.ajax({
                        url : "xuly.php",
                        method : "POST",
                        data : {search : key},
                        dataType : "text",
                        success: function(data)
                        {
                            $('#result').html(data);
                        }
                    })
                }
            })
        })
    </script>
</body>
</html>