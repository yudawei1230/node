<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
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
			<h3 class="panel-title">Panel title</h3>
		</div>
		<div class="panel-body">
			<ul class="list-group">
				<li class="list-group-item">
					<form class="form-inline" action="<?php echo U('main/index');?>">
						<div class="form-group">
							<select id="year" name="year">
								<?php if(is_array($year)): $i = 0; $__LIST__ = $year;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option <?php if($vo == $curr_year){echo 'selected';} ?> value="<?php echo ($vo); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					    	</select>
						</div>
						<div class="form-group">
							<select id="month" name="month">
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
						<button type="submit" class="btn btn-default btn-xs" id="search">搜索</button>
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
		    			<h1 id="reportName"><?php echo ($reportName); ?></h1>
				</div>
				<div class="boxx">
					<div class="boxx-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="tabbable">

									<div class="box">
										<div class="box-header blue-background mb">
											<div class="title"><i class="glyphicon glyphicon-edit"></i> 基础设置</div>
										</div><!--/box-header-->
										<div class="boxx-body pt">
											<form action="/index.php?s=/home/main/editreport"  method="post" id="table_post" >
												<label class="checkbox-inline">
													年度
													<select name="year" id="year1">
														<?php if(is_array($year)): $i = 0; $__LIST__ = $year;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option <?php if($vo == $curr_year){echo 'selected';} ?> value="<?php echo ($vo); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
													</select>
												</label>
												<label class="checkbox-inline">
													月度
													<select name="month" id="month1">
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
												</label>
												<label class="checkbox-inline">
													频度
													<select name="frequentness" id="frequentness">
														<option value="4" selected>4</option>

													</select>
												</label>



										</div><!--/boxx-body-->
									</div><!--/box-->

									<div class="tab-content">
										
											<table class="table table-bordered table-striped" id="table_list">
												<thead>
													<th>指标名称</th>
													<th>指标代码</th>
													<th>余额</th>
												</thead>
												<tbody>
													<!-- <?php if(is_array($table_val)): $i = 0; $__LIST__ = $table_val;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>-->
													<tr>
														<td><?php echo ($vo[0]); ?></td>
														<td><?php echo ($vo[1]); ?></td>
														<td>
															<input type="text" value="<?php echo ($vo[2]); ?>" />
														</td>
													</tr>
													<!--<?php endforeach; endif; else: echo "" ;endif; ?> -->
												</tbody>
												<thead>
													<th>指标名称</th>
													<th>指标代码</th>
													<th>余额</th>
												</thead>
											</table>
											<div class="boxx">
												<div class="pull-left">
													<form class="form-inline mb" role="form">
														<button type="button" class="btn btn-primary" id="save">提交</button>
													</form>
												</div>
												<div class="clearfix"></div>
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
	// $('#save').click(function(){

	// 	$('#table_post').submit();
	// })
	var data = {};
	$('#save').click(function(){
		var tr_obj = $('#table_list').find('tr');
		tr_obj.each(function(i){
			var code = tr_obj.eq(i).find('td').eq(1).html();
			var prices = tr_obj.eq(i).find('input').val();


			if(typeof(code) != 'undefined' && typeof(prices) != 'undefined' && prices != ''){
				data[i] = code+'*'+prices;
			}
		})
		data['year'] = $('#year1').val();
		data['month'] = $('#month1').val();
		data['frequentness'] = $('#frequentness').val();
		data['reportName'] = $('#reportName').text();
		data['day'] = new Date($('#year1').val(),$('#month1').val(),0).getDate();
		$.post('<?php echo U('main/editreport');?>',data,function(e){
			if(e.err == 0){
				if(e.exsitPf)
					alert("此月报表已存在，压缩包生成成功，覆盖成功");
				else
					alert('转换成功，压缩包生成成功');
				window.location.href='<?php echo U("main/index");?>';
			}else{
				window.location.href='<?php echo U("main/index");?>';
				alert(e.msg);
			}
		},'json');
	})
})
</script>