


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
   <form action="addAttachment.php" method="post" enctype="multipart/form-data">
       <input type="file" id="file" name="file">
       <input type="hidden"
              name="<?php echo ini_get("session.upload_progress.name"); ?>" value="<? md5(time().$_FILES['file']['name'])?>" />
       <input type="submit">
   </form>


</body>

</html>

