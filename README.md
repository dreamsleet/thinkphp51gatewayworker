# thinkphp51gatewayworker
thinkphp 5.1.23 整合gatewaywoker 实现TCP长连接应用

使用：
方法一、
	git clone https://github.com/dreamsleet/thinkphp51gatewayworker.git	
	
方法二、

1，安装thinkphp

	composer create-project topthink/think
	

2，安装workerman及gatewayworker

	composer require workerman/gateway-worker
3，复制public/server.php 复制application/server目录

=================================================================================================

启动
	php /home/www/thinkphp51gatewayworker/public/server.php start -d 	#修改为自己的实际项目路径
	
备注：
	application/server/controller/Run  		#服务配置，修改启动进程数和端口等	
	application/server/controller/Events  	#项目逻辑处理[收到的信息以及发送信息的处理等]
