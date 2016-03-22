Compiler Online 0.2
=========================
##一、待解决的问题
	1.当输入的代码含有循环时，执行代码后会导致代码不见；
	2.当多人用时肯定会出现错误。
	3.输出的错误、excepttion和正常输出最好可以用不同的颜色标识出来；
	4.在某些浏览器中会出现排版错误的问题；
	5.执行代码中含有死循环的导致刷新页面后代码消失的问题（或与AJAX的XMLHttpRequest异步的工作方式有关）；
	6.并发问题（考虑引入账号系统，为每个注册账号的用户在服务器上添加一个新用户）；
	7.安全问题（每个用户的权限设置）。
	8.优化响应，利用AJAX。
	9.考虑将一些信息存储在本地浏览器的Cache中，而不是全部上传到服务器。
##二、本版本概述
	本版本最终含有两个主要页面，index.php和show.php。其中index.php主要任务是执行编译运行的动作，
	show.php是画出页面。浏览器端提交代码，将触发index.php执行，会产生下列动作：
###1.index.php的动作：
	1.1将error.txt、input.txt、output.txt、exception.txt、info.xml、tag.txt中的内容置空；
	1.2调用delete.sh脚本删除./code文件夹下的所有*.class和*.java文件；
	1.3将浏览器端的theme、className、code、input存储到info.xml文件中，以供后面AJAX读取（如果theme、code那几项内容为空则停止执行下面的动作并输出show.php）；
	1.4执行javac.sh、java.sh进行编译运行，其中程序的输入输出是利用输入输出重定向完成的，根据编译运行的
	结果将输出重定向到error.txt（或output.txt或exception.txt）中，输入重定向到input.txt中，并且根据编译运行的结果，将标志tag写入到tag.txt文件中去（有三种结果：编译失败则tag为0，编译成功但运行抛出exception则tag为1，编译成功且运行成功则tag为2）；
	1.5调用show.php文件将结果输出出来。
####2.show.php的动作如下：
	2.1在window.onload中调用ajax的XMLHttpRequest读取服务器上的info.xml中的信息，将类名、代码、输入重新显示到浏览器端（如果不这样做，按下按钮Go！的时候会提交表单并调用index.php会导致也面刷新，从而导致写的代码被刷新掉，用户体验很差。）
	2.2ajax的XMLHttpRequest读取服务器上的output.txt文件的内容并输出到浏览器端。
	
####三、额外说明：
	之所以将负责进行编译的文件叫做index.php，是因为我们最先要访问的是它，由于第一次访问时所有需要输入的内都为空，所以index.	php只会执行到上面的第3步，将所有内容置空，下面show.php读取服务器端内容时也为空，从而浏览器端不显示任何输入内容，而在经过编译后导致服务器端内容不为空，后面show.php就能够显示出我们所写的代码，即使页面刷新也不会消失。此外，前端代码编辑页面是使用CodeMirror（一个js插件）插件完成的。
