<?php
	define('ACCINDEX',true);
	$content=$_POST['code'];
	$exe=$_POST['className'];
	$filename=$exe.".java";
	$input=$_POST['input'];
	$theme=$_POST['selectBackground'];

	exec("sh delete.sh");

	$tp=fopen("tag.txt", "w");
	$fp=fopen($filename,"w");
	$op=fopen("output.txt","w");
	$ep=fopen("exception.txt", "w");
	$ip=fopen("input.txt","w");
	$eep=fopen("error.txt", "w");
	$xmlp=fopen("info.xml", "w");


	fwrite($tp, "");
	fwrite($fp, "");
	fwrite($op, "");
	fwrite($ep, "");
	fwrite($eep, "");
	fwrite($xmlp, "");

	
	if($exe==""&&$content==""){
		fclose($op);
		fclose($ep);
		fclose($tp);
		fclose($fp);
		fclose($ip);
		fclose($eep);
		fclose($xmlp);
		include 'show.php';
		exit(0);
	}
	$xmlinfo='<?xml version="1.0" encoding="utf-8" ?>'."\r\n".'<info>'."\r\n".
	'<theme>'.$theme.'</theme>'."\r\n".
	'<className>'.$exe.'</className>'."\r\n".
	'<code>'.$content.'</code>'."\r\n".
	'<input>'.$input.'</input>'."\r\n".
	'</info>';

	fwrite($xmlp, $xmlinfo);
	fclose($xmlp);
	fwrite($ip, $input);
	fclose($ip);



	if(!$fp){
		echo " open file failed";
	}
	if(!fwrite($fp,$content)){
		echo "write file failed";
	}else{
		exec("sh mv.sh ".$filename,$arr,$sta);
		exec("sh javac.sh ".$filename,$arr,$s1);
		if($s1!=0){//编译失败
			exec("sh javac.sh ".$filename." 2> ./error.txt");
			fwrite($tp, "0");//tag存的为0，表示编译失败
		}else{//编译成功
			exec("sh java.sh ".$exe." <input.txt"." 1> ./output.txt",$arr,$s2);
			if($s2!=0){//抛出exception
				exec("sh java.sh ".$exe." < input.txt "."2> ./exception.txt",$arr,$s2);
				fwrite($tp, "1");//tag存的为1，表示编译成功但是抛出exception
			}else{
				fwrite($tp, "2");//tag存的为2，表示编译成功且运行成功
			}
		}
	}
	fclose($op);
	fclose($ep);
	fclose($tp);
	fclose($fp);
	sleep(3);
	
	include 'show.php';
	
	
?>