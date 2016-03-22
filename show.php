<?php
  defined('ACCINDEX')||exit('ACCINDEX DENIED');
?>
<!DOCTYPE HTML>
<html>

<head>
<title>Code Online</title>
<meta charset="utf-8"/>
<link rel=stylesheet href="codemirror-5.12/doc/docs.css">
<link rel="stylesheet" href="bootstrap-3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="codemirror-5.12/lib/codemirror.css">
<link rel="stylesheet" href="codemirror-5.12/theme/eclipse.css">
<link rel="stylesheet" href="codemirror-5.12/theme/seti.css">
<link rel="stylesheet" href="codemirror-5.12/theme/dracula.css">
<link rel="stylesheet" href="codemirror-5.12/addon/display/fullscreen.css">
<script src="codemirror-5.12/lib/codemirror.js"></script>
<script src="codemirror-5.12/clike.js"></script>
<script src="codemirror-5.12/addon/selection/active-line.js"></script>
<script src="codemirror-5.12/addon/edit/matchbrackets.js"></script>
<script src="codemirror-5.12/addon/display/fullscreen.js"></script>
<script src="bootstrap-3.3.6/js/bootstrap.min.js"></script>

<style type="text/css">
      .CodeMirror {border: 1px solid black; font-size:13px;}
</style>

<style>
  #center{
  margin:0px auto;  
  text-align:center; 
  }

  #leftInputSize{
  	float:left;
  	width:50%;	
  }

  #rightInputSize{
  	float:right;
  	width:50%;
  }

  #right{
  	float:right;
  }

  #left{
    float: left;
  }

  #namesize{
    width: 14%;
    float: left;
  }

  #fill{
    width: 86%;
    float: left;
  }

  #fillline{
    width: 100%;
    float: left;
  }
</style>
</head>

<body>
  <div class="navbar navbar-inverse" role="navigation">
  <div class="navbar-header">
     <a class="navbar-brand" >Code Online 0.2</a>
  </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="#">code</a></li>
      <li><a href="help.html">帮助</a></li>
      <li><a href="about.html">关于我们</a></li>
    </ul>
  </div>




<form  method="post" name="form_code" action="index.php">
  <p>
    选择语言: <select  id="selectLanguage">
      <option selected>java</option>  
    </select>
    &nbsp&nbsp&nbsp&nbsp
    选择主题: <select onchange="selectTheme()" id="selectBackground" name="selectBackground">
    <option value="day">白天</option>
    <option value="night">夜间</option>
    <option value="dracula">经典</option>
    </select>
  </p>

  <div id="namesize">
    <input type="text" class="form-control" id="className" placeholder="请输入类名" name="className">
  </div>

  <div id="left">
    <button type="button" class="btn btn-default" id="generate" onclick="generateCode()">生成</button>
  </div>

  <div id="fillline"><em>光标停留在输入框内，按F11(或Fn+F11)进入全屏模式，如排版混乱请使用chrome浏览器访问。</em></div>



  <div id="fillline">  
    <textarea id="code" name="code"> </textarea>
  </div>


  <br/>
  <div id="center">
    <button type="button" class="btn btn-success" onclick="compile()">Go!</button>
  </div>
  <br/>

  <div id="leftInputSize">
    <h5>输入</h5>
    <textarea   class="form-control" rows="6" name="input" id="input"></textarea>
  </div>
</form>



<div id="rightInputSize">
  <h5>输出</h5>
  <textarea  readonly="readonly" class="form-control" rows="6" id="output"></textarea>
</div>

<script>
  var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
    lineNumbers: true,
    styleActiveLine: true,
    matchBrackets: true,
	  mode:"text/x-java",
	  extraKeys: {
	   "F11": function(cm) {
	     cm.setOption("fullScreen", !cm.getOption("fullScreen"));
	    },
	   "Esc": function(cm) {
	     if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
	    }
    }
   });

  editor.setOption("theme","eclipse");

  var input = document.getElementById("selectBackground");
  function selectTheme() {
    var theme = input.options[input.selectedIndex].textContent;
	  if(theme=="白天"){
		 editor.setOption("theme","eclipse");
	  }else if(theme=="夜间"){
		 editor.setOption("theme", "seti");
	 }else if(theme=="经典"){
		editor.setOption("theme", "dracula");
	 }
  }


var code_content;
var name_content;
var input_content;


  function generateCode(){
    var className=document.getElementById("className").value;
    if(className==""){
      alert("请输入类名");
      return;
    }
    var textareaValue="public class "+className+"{"+"\r\n"+"\t"+"public static void main(String[] args){"+"\r\n\t\t"+"\r\n"+"\t"+"}"+"\r\n"+"}";
    editor.setOption("value",textareaValue);
  }

  function compile(){
      form_code.submit();
  }

  window.onload=function(){
   var temp1=new XMLHttpRequest();
      temp1.onreadystatechange=function()
        {
        if (temp1.readyState==4 && temp1.status==200)
          {  
            alert("OK");
            var xmlDom=temp1.responseXML;

            var xmlRoot=xmlDom.documentElement; 
            if(xmlRoot.getElementsByTagName("theme")[0].firstChild.data=='day'){
              editor.setOption("theme","eclipse");
              document.getElementById("selectBackground").selectedIndex=0;
            }else if(xmlRoot.getElementsByTagName("theme")[0].firstChild.data=='night'){
              editor.setOption("theme","seti");
              document.getElementById("selectBackground").selectedIndex=1;
            }else if(xmlRoot.getElementsByTagName("theme")[0].firstChild.data=='dracula'){
              editor.setOption("theme","dracula");
              document.getElementById("selectBackground").selectedIndex=2;
            }
            document.getElementById("className").value=xmlRoot.getElementsByTagName("className")[0].firstChild.data;
            editor.setOption("value",xmlRoot.getElementsByTagName("code")[0].firstChild.data);
            document.getElementById("input").value=xmlRoot.getElementsByTagName("input")[0].firstChild.data;
          }
        }
      temp1.open("GET","info.xml",true);
      temp1.send();
      showOutput();
   }


  function showOutput(){
    var xmr=new XMLHttpRequest();
    var tag;

    xmr.onreadystatechange=function()
          {
          if (xmr.readyState==4 && xmr.status==200)
            {
             tag=xmr.responseText;
              if(tag==""){
              return;
            }else if(tag=="0"){
                var temp=new XMLHttpRequest();
                temp.onreadystatechange=function()
                  {
                  if (temp.readyState==4 && temp.status==200)
                    {
                    document.getElementById("output").innerHTML=temp.responseText;
                    }
                  }
                temp.open("GET","error.txt",true);
                temp.send();
            }else if(tag=="1"){
                var temp=new XMLHttpRequest();
                temp.onreadystatechange=function()
                  {
                  if (temp.readyState==4 && temp.status==200)
                    {
                    document.getElementById("output").innerHTML=temp.responseText;
                    }
                  }
                temp.open("GET","exception.txt",true);
                temp.send();
            }else if(tag=="2"){
                var temp=new XMLHttpRequest();
                temp.onreadystatechange=function()
                  {
                  if (temp.readyState==4 && temp.status==200)
                    {
                    document.getElementById("output").innerHTML=temp.responseText;
                    }
                  }
                temp.open("GET","output.txt",true);
                temp.send();
            }
            }
          }
    xmr.open("GET","tag.txt",true);
    xmr.send();    
  }

</script>

</body>
</html>