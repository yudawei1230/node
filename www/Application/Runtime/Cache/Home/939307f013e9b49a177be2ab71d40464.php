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
					    	<a href="#home" aria-controls="home" role="tab" data-toggle="tab" id="ZCFZ">资产负载表</a>
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
			<h3 class="panel-title"></h3>
		</div>
		<div class="panel-body">
		<button type="button" class="btn btn-primary" action="save" style="margin-right: 25px" isDel=<?php echo ($id); ?>>提交</button>
		<button type="button" class="btn btn-primary" id="back" path="<?php echo ($backpath); ?>">返回</button>

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
										</label>								<label class="checkbox-inline">
											频度
											<select name="frequent" id="frequent">
												<?php if(is_array($frequent)): $i = 0; $__LIST__ = $frequent;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option <?php if($vo[1] == $frequentSelected){echo 'selected';} ?> value="<?php echo ($vo[1]); ?>"><?php echo ($vo[0]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
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
												<tbody id="path" path='<?php echo ($path); ?>'>
													<!-- <?php if(is_array($table_val)): $i = 0; $__LIST__ = $table_val;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>-->
													<tr>
														<td><?php echo ($vo[0]); ?></td>
														<td><?php echo ($vo[1]); ?></td>
														<td>
															<input type="text" value="<?php echo ($vo[2]); ?>" onkeyup="javascript:if(this.value=='')this.value=''"/>
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
														<button type="button" class="btn btn-primary" action="save" isDel=<?php echo ($id); ?>>提交</button>
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
	var data = {};
	var isDel = 0;
	$('#back').click(function(){
		window.location.href=$('#back').attr('path');
	});
	$("button[action='save']").click(function(){
		if($("button[action='save']").attr('isDel')!="")
		{
			isDel = 1;
			$.get('<?php echo U('main/checkDel');?>',{id:$("button[action='save']").attr('isDel')},function(data){
			});
		}
		var tr_obj = $('#table_list').find('tr');
		tr_obj.each(function(i){
			var code = tr_obj.eq(i).find('td').eq(1).html();
			var prices = tr_obj.eq(i).find('input').val();


			if(typeof(code) != 'undefined' && typeof(prices) != 'undefined' && prices != ''){
				data[i] = code+'*'+prices;
			}
		})
		data['isDel'] = isDel;
		data['year'] = $('#year1').val();
		data['reportName'] = $('#reportName').text();
		data['frequent'] = $('#frequent').val();
		data['path'] = $('#path').attr('path');
		if($('#frequent').val()==0)
		{
			if($('#reportName').text()!="人行利润表")
			{
				data['month'] = "01";
				data['day'] = "01";
			}
			else
			{
				data['month'] = "12";	
			
				data['day'] = new Date($('#year1').val(),12,0).getDate();
			}
		}
		else
		{
			if($('#reportName').text()!="人行资产负债表")
			{
				switch($('#frequent').val())
				{
					case '1': data['month'] = "03"; data['day'] = new Date($('#year1').val(),3,0).getDate(); break;
					case '2': data['month'] = "06"; data['day'] = new Date($('#year1').val(),6,0).getDate(); break;
					case '3': data['month'] = "09"; data['day'] = new Date($('#year1').val(),9,0).getDate(); break;
					case '4': data['month'] = "12"; data['day'] = new Date($('#year1').val(),12,0).getDate(); break;
				}
			}
			else
			{
				if($('#frequent').val()<10)
					data['month'] = "0"+$('#frequent').val();
				else
					data['month'] = $('#frequent').val();
				data['day'] = new Date($('#year1').val(),$('#frequent').val(),0).getDate();
			}
		}
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