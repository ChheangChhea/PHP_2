<?php
$photo = $_FILES['txt-file'];
$photoName =  $photo['name'];
$photoSize = $photo['size'];
$imgName = mt_rand(100000, 999999);
$tmp = $photo['tmp_name'];
// echo $photoName;
// alert("123");
$ext = pathinfo($photoName, PATHINFO_EXTENSION);
// echo '<br>';
// echo $ext;
// echo '<br>';
// echo time();
// move_uploaded_file($tmp,'img/'.time().$imgName.
// '.'.$ext);

$newName = time() . $imgName . '.' . $ext;
move_uploaded_file($tmp, '../img/' . $newName);
$msg['imgName'] = $newName;
echo json_encode($msg);
