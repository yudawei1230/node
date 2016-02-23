<?php if (!defined('THINK_PATH')) exit();?><!-- 首页 -->
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>报表工具</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="/Application/Home/View/Main/css/bootstrap.min.css">
		<link  href="/Application/Home/View/Main/css/admin.css" type="text/css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="/Application/Home/View/Main/css/admin.css">
		<script type="text/javascript" src="/Application/Home/View/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="/Application/Home/View/js/bootstrap.min.js"></script>
		<?php echo ($other_script); ?>
	</head>
	<body>
		<div class="navbar navbar-inverse">
			<div class="container">
					<ul class="nav nav-tabs" role="tablist" style="float:left">
					    <li role="presentation" class="active">
					    	<a href="#home" aria-controls="home" role="tab" data-toggle="tab" id="ALL">全部</a>
					    </li>
					    <li role="presentation">
					    	<a href="#home" aria-controls="home" role="tab" data-toggle="tab" id="ZCFZ">资产负债表</a>
					    </li>
					    <li role="presentation">
					    	<a href="#profile" aria-controls="profile" role="tab" data-toggle="tab" id="LR">利润表</a>
					    </li>
					    <li role="presentation">
					    	<a href="#messages" aria-controls="messages" role="tab" data-toggle="tab" id="ZXTJ">专项统计表</a>
						</li>
					    <li role="presentation">
					    	<a href="#settings" aria-controls="settings" role="tab" data-toggle="tab" id="JGZB">监管指标表</a>
					    </li>
				  	</ul>
				<div class="navbar-collapse collapse" style="float:right">
					<span class="pull-right">当前登录用户：<?php echo ($user['username']); ?> [<a href="<?php echo U('login/logout');?>" >[退出]</a>]</span>
					<span class="pull-right">[<a href="<?php echo U('main/editpassword');?>" >修改密码]&nbsp;&nbsp;</a></span>
					<span class="pull-right">[<a href="<?php echo U('main/reportset');?>" >基本设置]&nbsp;&nbsp;</a></span>
					
				</div><!--/.navbar-collapse -->
			</div>
		</div>
		<div id="container">
			<div id="main-nav-bg"></div>

<div id="main-nav">
	<div class="panel panel-default">
		<div class="panel-heading">
		</div>
		<div class="panel-body">
			<ul class="list-group">
				<li class="list-group-item">
					<form class="form-inline">
						<div class="form-group">
							<select id="year" name="year">
								<?php if(is_array($year)): $i = 0; $__LIST__ = $year;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option <?php if($vo == $curr_year){echo 'selected';} ?> value="<?php echo ($vo); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					    	</select>
						</div>
						<div class="form-group">
							<select id="season" name="season">
								<?php if(is_array($season)): $i = 0; $__LIST__ = $season;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option <?php if($vo[1] == $curr_season){echo 'selected';} ?> value="<?php echo ($vo[1]); ?>"><?php echo ($vo[0]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					    	</select>
						</div>
					</form>
			  	</li>
				<?php if(is_array($report_list)): $i = 0; $__LIST__ = $report_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="list-group-item">
						<a href="<?php echo U('main/download',array('id'=>$vo['id']));?>" class="badge" target="_blank">下载</a>
				    	<a href="<?php echo U('main/reportdel',array('id'=>$vo['id']));?>" class="badge">删除</a>
				    	<a  class="badge" id="check" path="<?php echo ($vo['id']); ?>">查看</a>
				    		<?php echo ($vo["year"]); ?>年<?php echo ($vo["month"]); ?>月<?php echo ($vo["reportname"]); ?>(<?php echo ($vo["frequentness"]); ?>)
				  	</li><?php endforeach; endif; else: echo "" ;endif; ?>				
				<ul class="pagination">
					<?php echo ($page); ?>
				</ul>
			</ul>
		</div>
	</div>
</div><!--/main-nav-->

<div id="content">
	<div class="container">
		<div id="contentWrap" class="row">
			<div class="col-xs-12">
				<div class="page-header">
					<!-- <h1>资产负债表</h1> -->

				</div>
				<div class="boxx">
					<div class="boxx-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="tabbable">
									<form class="form-horizontal" role="form" action="<?php echo U('main/uploadReport');?>" method="post" enctype="multipart/form-data" id="form">
										<div class="form-group">
											<label for="organizationcode" class="col-lg-2 control-label">上传EXCEL表：</label>
											<div class="col-lg-10">
												<input type="file" name="table" />
												<input type="text" name="backurl" id="backurl" style="display:none"  />
												<p class="help-block">请上传97-2003 Excel格式的xls文件</p>
											</div>
										</div>

										<div class="form-actions form-actions-padding-sm">
											<div class="row">
												<div class="col-md-10 col-md-offset-2">
													<button type="botton" class="btn btn-success" id="submit"><i class="glyphicon glyphicon-ok"></i> 上传</button>
												</div>
											</div>
										</div>
									</form>
									</div><!--/tab-content-->
								</div><!--/tabbable-->

							</div><!--/col-sm-12-->
						</div><!--/row-->
					</div><!--/boxx-body-->
				</div><!--/boxx-->
			</div><!--/col-xs-12-->
		</div><!--/contentWrap-->
	</div><!--/container-->



<script type="text/javascript">
$(function(){
	$('a[path]').click(function(){
		if(window.location.href.indexOf('change')>0)
			window.location.href = window.location.href.split('?')[0]+'?s=/home/main/checkexcel&id='+$(this).attr('path')+'&backpath='+window.location.href.split('.html')[0]+'&report='+$('.active').children()[0].id+'&year='+$('#year').val()+'&month='+$('#season').val();
		else
			window.location.href = window.location.href.split('?')[0]+'?s=/home/main/checkexcel&id='+$(this).attr('path')+'&backpath='+window.location.href.split('.html')[0];
	});
	$('#backurl').val(window.location.href);
	$('a[role]').click(function (e) {
	 	window.location.href = window.location.href.split("?")[0]+'?s=/home/main/changereport.html&report='+e.currentTarget.id+'&year='+$('#year').val()+'&month='+$('#season').val();
	});
	if(window.location.href.indexOf('&')>-1&&window.location.href.indexOf('report=')>-1)
	{
		$('#ALL').parent().removeClass("active");
		$('#'+window.location.href.split('=')[2].split('&')[0]).parent().addClass("active");
	}
	$('#season').change(function(){
		window.location.href = window.location.href.split("?")[0]+'?s=/home/main/changereport.html&report='+$('.active').children()[0].id+'&year='+$('#year').val()+'&month='+$('#season').val();
	});
	$('#year').change(function(){
		window.location.href = window.location.href.split("?")[0]+'?s=/home/main/changereport.html&report='+$('.active').children()[0].id+'&year='+$('#year').val()+'&month='+$('#season').val();
	});
});
</script>