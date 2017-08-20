<html>
<body>
<?php echo $data['msg']; ?>
<form method="post" enctype="multipart/form-data">
画像ファイル
<input type="file" name="upload">
<br>
分割数：<input type="number" name="divide" value="4"><br>
<button type="submit" name="submit" value="submit">Submit</button>
</form>
</body>
</html>

