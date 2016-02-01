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
		<?php echo ($other_script); ?>
	</head>
	<body>
		<div class="navbar navbar-inverse">
			<div class="container">
				<div class="navbar-header">
					<a href="<?php echo U('main/index');?>" class="navbar-brand">人行指标填报</a>
					<a href="<?php echo U('main/index');?>" class="navbar-brand">人行报表导出</a>
				</div>
				<div class="navbar-collapse collapse">
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
			<h3 class="panel-title">压缩文件列表</h3>
		<form id="reportform" action="/index.php?s=/home/main/changereport"  method="get">
			<select class="form-control" id="report" name="report">
				<option value="ALL">全部</option>
				<option value="ZCFZ">资产负债表</option>
				<option value="JGZB">监管指标表</option>
				<option value="LR">利润表</option>
				<option value="ZXTJ">专项统计表</option>
			</select>
		</form>
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
							<select id="month" name="month">
					    		<option <?php if('ALL' == $curr_month){echo 'selected';} ?> value="ALL" >全部</option>
					    		<option <?php if(1 == $curr_month){echo 'selected';} ?> value="01" >01</option>
								<option <?php if(2 == $curr_month){echo 'selected';} ?> value="02">02</option>
								<option <?php if(3 == $curr_month){echo 'selected';} ?> value="03">03</option>
								<option <?php if(4 == $curr_month){echo 'selected';} ?> value="04">04</option>
								<option <?php if(5 == $curr_month){echo 'selected';} ?> value="05">05</option>
								<option <?php if(6 == $curr_month){echo 'selected';} ?> value="06">06</option>
								<option <?php if(7 == $curr_month){echo 'selected';} ?> value="07">07</option>
								<option <?php if(8 == $curr_month){echo 'selected';} ?> value="08">08</option>
								<option <?php if(9 == $curr_month){echo 'selected';} ?> value="09">09</option>
								<option <?php if(10 == $curr_month){echo 'selected';} ?> value="10">10</option>
								<option <?php if(11 == $curr_month){echo 'selected';} ?> value="11">11</option>
								<option <?php if(12 == $curr_month){echo 'selected';} ?> value="12">12</option>
					    	</select>
						</div>
<!-- 						<button type="submit" class="btn btn-default btn-xs" id="search">搜索</button> -->
					</form>
			  	</li>
				<?php if(is_array($report_list)): $i = 0; $__LIST__ = $report_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="list-group-item">
						<a href="<?php echo U('main/download',array('id'=>$vo['id']));?>" class="badge" target="_blank">下载</a>
				    	<a href="<?php echo U('main/reportdel',array('id'=>$vo['id']));?>" class="badge">删除</a>
				    		<?php echo ($vo["year"]); ?>年<?php echo ($vo["month"]); ?>月<?php echo ($vo["frequentness"]); ?>频次<?php echo ($vo["reportname"]); ?>
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
	// ${'#search'}.click(function(){
	// 	var month = $('#month').val();
	// 	var year = $('#year').val();
	// 	var url = '<?php echo U("main/index");?>'+'?y='+year+'&m='+month;
	// 	window.location.href=url;
	// })


	if(window.location.href.indexOf('&')>-1&&window.location.href.indexOf('report=')>-1)
	{
		$('#report').val(window.location.href.split('=')[2].split('&')[0]);
		$('#month').val(window.location.href.split('=')[4]);
	}
	else
	{
		$('#report').val('ALL');
		$('month').val('ALL')
	}
	$('#report').change(function(){
		window.location.href='http://10.2.2.14:900/index.php?s=/home/main/changereport.html&report='+$('#report').val()+'&year='+$('#year').val()+'&month='+$('#month').val();
	});
	$('#month').change(function(){
		window.location.href='http://10.2.2.14:900/index.php?s=/home/main/changereport.html&report='+$('#report').val()+'&year='+$('#year').val()+'&month='+$('#month').val();
	});
	$('#year').change(function(){
		window.location.href='http://10.2.2.14:900/index.php?s=/home/main/changereport.html&report='+$('#report').val()+'&year='+$('#year').val()+'&month='+$('#month').val();
	});
});
</script>