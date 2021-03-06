<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<title>登录 - 报表</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="Application/Home/View/Main/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="Application/Home/View/Main/css/admin.css">
		<script type="text/javascript" src="/Application/Home/View/js/jquery-1.10.2.min.js"></script>
	</head>

	<body class="login">
		<div class="middle-container">
			<div class="middle-row">
				<div class="middle-wrapper">
					<div class="login-container-header">
						<div class="container">
							<div class="row">
								<div class="col-sm-12">
									<div class="text-center">报表</div>
								</div>
							</div>
						</div>
					</div>
					<div class="login-container">
						<div class="container">
							<div class="row">
								<div class="col-sm-4 col-sm-offset-4">
									<h1 class="text-center title">登录</h1>
									<form action="<?php echo U('Login/loginpost');?>" class="validate-form" method="post">
										<div class="form-group">
											<div class="controls with-icon-over-input">
												<input type="text" name="username" data-rule-required="true" class="form-control" placeholder="用户名" value="">
												<i class="glyphicon glyphicon-user text-muted"></i>
											</div>
										</div>
										<div class="form-group">
											<div class="controls with-icon-over-input">
												<input type="password" name="password" data-rule-required="true" class="form-control" placeholder="密码" value="">
												<i class="glyphicon glyphicon-lock text-muted"></i>
											</div>
										</div>
										<div class="form-group">
											<div class="controls with-icon-over-input">
												<input type="text" name="verify" data-rule-required="true" class="form-control" placeholder="验证码" value="">
												<i class="glyphicon glyphicon-lock text-muted"></i>
											</div>
											<img  id="verify" src="<?php echo U('Login/verify');?>" title="验证码" />
										</div>
										<button class="btn btn-block">登录</button>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="login-container-footer">
						<div class="container">
							<div class="row">
								<div class="col-sm-12">
									<!-- <div class="text-center">&copy; 302医院爱肝中心 | <a href="http://bosixing.com" target="_blank">博思行传播</a> 技术支持</div> -->
								</div>
							</div>
						</div>
					</div>
				</div><!--/middle-wrapper-->
			</div><!--/middle-row-->
		</div><!--/middle-container-->
	</body>
</html>
<script type="text/javascript">
$(function(){
	$('#verify').click(function(){
		var timenow = new Date().getTime();
		$(this).attr("src","<?php echo U('Login/verify',"+timenow");?>");
	})
})
</script>