<?php
	$content=$_POST['code'];
	$filename="mytest.java";
	$fp=fopen($filename,"w");
	if(!$fp){
		echo " open file failed";
	}
	echo '<br/>';
	if(!fwrite($fp,$content)){
		echo "write file failed";
	}else{
		exec("sh mv.sh",$arr,$sta);
		exec("sh javac.sh ",$arr,$s);
		if($s==1){
			exec("sh javac.sh 2> ./error.txt");
			echo file_get_contents("./error.txt");
			fclose($ef);
		}else{
			echo "compile succeed!";
		}
	}
	fclose($fp);
?>