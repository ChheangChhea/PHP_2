<?php
date_default_timezone_set("Asia/Phnom_Penh");
$cn= new mysqli("localhost","root","","php25_2");
    $cn->set_charset("utf8");
    $editId =$_POST['txt-edit-id'];
    $name = trim($_POST['txt-name']);
    $name = $cn->real_escape_string($name);
    $lang = $_POST['txt-lang'];
    $des = trim($_POST['txt-des']);
    $des=$cn->real_escape_string($des);
    // $date = date("Y-m-d h:i:s A");
    $img =$_POST['txt-img'];
    $status =$_POST['txt-status'];
    //ckeck daulicat name
    $sql = "SELECT name FROM tbl_category WHERE name='$name'&& id !=$editId";
        $rs=$cn->query($sql);
        if($rs->num_rows > 0){
            $msg['dpl']=true;
        }else{
            if($editId==0){
                $sql = "INSERT INTO tbl_category 
                values(null,'$name',$img,'$des','$lang',$status)";
                $cn->query($sql);
                 $msg['id']=$cn->insert_id;
                 $msg['edit']=false;
            }else{
                $sql ="UPDATE tbl_category SET name='$name'
                ,lang='$lang',img ='$img',des='$des',status='$status' WHERE id= $editId";
                $cn->query($sql);
                $msg['edit']=true;
            }
           
             $msg['dpl']=false;
        }
     echo json_encode($msg);
?>