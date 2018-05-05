<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title>sese</title>
	</head>
	<body>
		<div style="margin-top:20px"></div>
		<form method="post" action="<?php echo base_url('presensi/tampil'); ?>" enctype="multipart/form-data">
    	<input type="file" name="file"/>
    	<input type="submit" value="Upload file"/>
		</form>
	</body>
</html>
