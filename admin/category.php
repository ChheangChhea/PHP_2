<?php
$cn = new mysqli("localhost", "root", "", "php25_2");
$cn->set_charset("utf8");
$id = 1;
$sql = "SELECT MAX(id) FROM tbl_category";
$rs = $cn->query($sql);
if ($rs->num_rows > 0) {
    $row = $rs->fetch_array();
    $id = $row[0] + 1;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

    <link rel="stylesheet" href="style/style.css">
    <script src="js/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="style/bootstrap.min.css">
</head>
<style>
    .box{
        width: 49%;
        float: left;
    }
</style>
<body>
    <form class="upl">
        <div class="frm" style="width:90%">
            <div class="box" style="width:30%">
            <input type="hidden" name="txt-edit-id" id="txt-edit-id" class="frm-control" value='0'>
            <label for="">ID</label>
            <input type="text" name="txt-id" id="txt-id" readonly
                value="<?php echo $id; ?>" class="frm-control">
                <label for="">Languse</label>
           <select name="txt-lang" id="txt-lang" class="frm-control">
             <option value="1">English</option>
             <option value="2">Khmer</option>

           </select>
            <label for="">Name</label>
            <input type="text" name="txt-name" id="txt-name" class="frm-control">

           <label for="">Status</label>
           <select name="txt-status" id="txt-status" class="frm-control">
             <option value="1">1</option>
             <option value="2">2</option>

           </select>

            <label for="">Photo</label>
            <div class="img-box">
                 <input type="file" name="txt-file" id="txt-file"
                    class="txt-file">
                <input type="text" name="txt-img" id="txt-img"
                    class="txt-img">
            </div>

            </div>
          <div class="box"  style="width:68%; margin-left:2%">
          <label for="">Description</label>
           <textarea  style="height:300px" name="txt-des" id="txt-des" class="frm-control"></textarea>

          </div>
            <div class='btnSave'>
                Save
            </div>
    </form>

    </div>
    <h1></h1>
    <table class="table" id="tblData">
        <tr>
            <th width="100">ID</th>
            <th width="100">languse</th>
            <th>Name</th>
            <th>Description</th>
            <th width="100">photo</th>
            <th width="100">Status</th>
            <th width="100">Action</th>
        </tr>
        <?php
        $sql = "SELECT *FROM tbl_category order by id desc";
        $rs = $cn->query($sql);
        while ($row = $rs->fetch_array()) {
        ?>
            <tr>
                <td><?php echo $row[0]; ?> </td>
                <td><?php echo $row[4]; ?> </td>
                <td><?php echo $row[1]; ?> </td>
                <td><?php echo $row[3]; ?> </td>
                <td> <img src="img/<?php echo $row[2]; ?>" alt="<?php echo $row[2]; ?>"></td>
                <td><?php echo $row[5]; ?> </td>
                <td> <i class="fas fa-edit btnEdit"></i> </td>
                <!-- <td> <input type="button" value="Edit" class="btnEdit"></td> -->

            </tr>
        <?php
        }
        // if($rs->num_rows >0){

        // }
        ?>

    </table>
</body>
<script>

    $(document).ready(function(){
        var tbl= $('#tblData');
        var btnEdit ='<i class="fas fa-edit btnEdit"></i>';
        var loading= "<div class='img-loading'></div>";
        var ind=0;
        // Upload img
        $('.txt-file').change(function() {
            var eThis = $(this);
            var imgBox = $('.img-box');
            var frm = eThis.closest('form.upl');
            var frm_data = new FormData(frm[0]);
            $.ajax({
                url: 'action/upl-img.php',
                type: 'POST',
                data: frm_data,
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                beforeSend: function() {
                    imgBox.append(loading);
                },
                success: function(data) {
                    imgBox.css({
                        "background-image": "url(img/" + data['imgName'] + ")"
                    });
                    imgBox.find('.img-loading').remove();
                    imgBox.find('.txt-img').val(data['imgName']);
                }
            });
        });
        $('.btnSave').click(function(){
        var eThis = $(this);
        var id= $('#txt-id');
        var name= $('#txt-name');
        var lang= $('#txt-lang');
        var des= $('#txt-des');
        var imgName= $('#txt-img');
        var imgBox = $('.img-box');

        var status = $('#txt-status');
        if(name.val()==''){
            alert("please input name");
            name.focus();
            return;
        }
        // else if(price.val()==''){
        //     alert("please input price");
        //     return;
        // }
        var frm = eThis.closest('form.upl');
       var frm_data = new FormData(frm[0]);
$.ajax({
	url:'action/save-category.php',
	type:'POST',
	data:frm_data,
	contentType:false,
	cache:false,
	processData:false,
	dataType:"json",
	beforeSend:function(){
           eThis.html("waiting...")
	},
	success:function(data){
    if(data['dpl'] == true){
        alert("Duplicate name");
    }else if(data['edit']== true){
       tbl.find('tr:eq('+ind+') td:eq(1)').text(name.val());
        tbl.find('tr:eq('+ind+') td:eq(2)').text(des.val());
       tbl.find('tr:eq('+ind+') td:eq(3) img').attr("src","img/"+imgName.val()+"");
       tbl.find('tr:eq('+ind+') td:eq(3) img').attr("alt",""+imgName.val()+"");
    }else{
        var tr = `

           <tr>
                <td>${id.val()}</td>
                <td>${name.val()}</td>
                <td>${des.val()}</td>
                <td> <img src='img/${imgName.val()}'alt="${imgName.val()}"</td>
                <td>${btnEdit}</td>
           </tr>
           `;
                        tbl.find('tr:eq(0)').after(tr);
                        //    tbl.prepend(tr);
                        name.val("");
                        des.val("");
                        imgBox.css({
                            "background-image": "url(style/photo.png)"
                        });
                        imgBox.find("input").val('');
                        name.focus();
                        id.val(data['id'] + 1);
                    }
                    eThis.html("Save");
                }
            });
        });
        //get edit data

        tbl.on('click',"tr td .btnEdit",function(){
          var Parent =$(this).parents('tr');
          var id = Parent.find('td:eq(0)').text();
          var name = Parent.find('td:eq(1)').text();
          var price = Parent.find('td:eq(2)').text();
          var img = Parent.find('td:eq(3) img').attr("alt");
          ind=Parent.index();
          $('#txt-id').val(id);
          $('#txt-name').val(name);
          $('#txt-price').val(price);
          $('#txt-img').val(img);
          $('.img-box').css({"background-image":"url(img/"+img+")"});
          $("#txt-edit-id").val(id);

        });

    });
</script>

</html>