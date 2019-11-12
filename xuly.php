<?php 
    session_start();
    require_once("models/user.php");
    require_once("models/contact.php");
    $user = unserialize($_SESSION["user"]);
    $con = mysqli_connect("localhost","root","","db_contact");
    $output = '';
    if($_POST["search"] != ''){
        
        $sql = "select * from danhba where id_user = ".$_POST["iduser"]." and (name like '%".$_POST["search"]."%' or email like '%".$_POST["search"]."%' or phone like '%".$_POST["search"]."%')";
        $result = mysqli_query($con,$sql);
        if(mysqli_num_rows($result)>0)
        {
            while($row = mysqli_fetch_array($result)){
                $output = '<tr>';
                $output .= '
                            <td>'.$row["name"].'</td>
                            <td>'.$row["email"].'</td>
                            <td>'.$row["phone"].'</td>
                            <td>
                                <a href="" onclick="func(this)" eName="'.$row["name"].'" eEmail="'.$row["email"].'"   eId="'.$row["id"].'" ePhone="'.$row["phone"].'" class="btn btn-success" data-toggle="modal" data-target="#editContact" ><i class="fas fa-edit"></i>Sửa</a> 
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#myModalDelete'.$row["id"].'"><i class="fas fa-trash-alt"></i>Xóa</button>
                                    <div id="myModalDelete'.$row["id"].'" class="modal fade" role="dialog">
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
                                                <a href="index.php?action=xoa&id='.$row["id"].'"  class="btn btn-danger"><i class="fas fa-trash-alt"></i>Xóa</a> 
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                            </div>

                                        </div>
                                    </div>
                                </td>
                            ';
                $output .= '</tr>';

            }
            echo $output;
        }
        else
        {
            echo "<p>Không tìm thấy !!!</p>";
        }
    }
    else{
        $contact = Contact::getList($user->id);
        foreach ($contact as $key => $value) {
            $output .= '<tr>';
            $output .= '
                        <td>'.$value->name.'</td>
                        <td>'.$value->email.'</td>
                        <td>'.$value->phoneNumber.'</td>
                        <td>
                            <a href="" onclick="func(this)" eName="'.$value->name.'" eEmail="'.$value->email.'"   eId="'.$value->id.'" ePhone="'.$value->phoneNumber.'" class="btn btn-success" data-toggle="modal" data-target="#editContact" ><i class="fas fa-edit"></i>Sửa</a> 
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#myModalDelete'.$value->id.'"><i class="fas fa-trash-alt"></i>Xóa</button>
                                <div id="myModalDelete'.$value->id.'" class="modal fade" role="dialog">
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
                                            <a href="index.php?action=xoa&id='.$value->id.'"  class="btn btn-danger"><i class="fas fa-trash-alt"></i>Xóa</a> 
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                        </div>

                                    </div>
                                </div>
                            </td>
                        ';
            $output .= '</tr>';
        }
        echo $output;
    }
    

?>