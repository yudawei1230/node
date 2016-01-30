<?php
namespace Home\Controller;
class MainController extends FrontController {
    protected function _init() {
        $this->assign('user',$this->user);
        if (!$this->checkConsoleLogin()){
            $this->redirect('Login/login');
            exit;
        }
    }

    public function index(){
    	//if($_GET['report'])
    		//$this->display('main/changereport');
    	if($this->user['type'] == 1){
        	$this->display('Main/admin');
        }else{
        	$map['year'] = $_GET['year'];
        	$map['month'] = $_GET['month'];

        	$report_db = D('Report');
        	$re = $report_db->getList($map,$this->user['id']);

        	$curr_year = intval(date('Y'));
			$curr_month = date('m');
			for($i=5;$i>0;$i--){
			    if($i<0){break;}
			    $year[]= $curr_year+$i;
			}

			$year[] = $curr_year;
			for ($i=1; $i<=5; $i++) { 
				$year[]= $curr_year-$i;
			}
			if($map['year'] == ''){
				$this->assign('curr_year',$curr_year);				
			}else{
				$this->assign('curr_year',$map['year']);				
			}

			if($map['month'] == ''){
				$this->assign('curr_month',$curr_month);				
			}else{
				$this->assign('curr_month',$map['month']);				
			}
			for($i=0;$i<count($re['data_list']);$i++)
			switch($re['data_list'][$i]['reportname'])
			{
				case "JGZB": $re['data_list'][$i]['reportname'] = "监管指标表"; break;
				case "ZXTJ": $re['data_list'][$i]['reportname'] = "专项统计表"; break;
				case "LR": $re['data_list'][$i]['reportname'] = "利润表"; break;
				case "ZCFZ": $re['data_list'][$i]['reportname'] = "资产负债表"; break;
			}
			//$this->assign('reportName',$re['data_list'][0]['reportname']);
			$this->assign('year',$year);
        	$this->assign('report_list',$re['data_list']);
        	$this->assign('page',$re['page_list']);
        	$this->display('main/user');
        }
    }



    public function reportSet(){
    	if($this->user['type'] == 2){
    		$set = M('User_set')->where(array('u_id'=>$this->user['id']))->find();
     		$this->assign('set',$set);
    		$this->display('main/reportset');
    	}
    }

    public function setPost(){
    	if($this->post){
				$data["organizationcode"] = $_POST['organizationcode'];
				$data["areascode"] = $_POST['areascode'];
				$data["institutioncode"] = $_POST['institutioncode'];
				if(empty($data["organizationcode"]) || empty($data["areascode"]) || empty($data["institutioncode"])){
					alert('机构类代码,地区代码,标准化机构编码不能为空','Main/reportSet');
					exit;
				}
				if(strlen($data["organizationcode"]) != 4){
					alert('机构类代码为4位字符','Main/reportSet');
					exit;
				}
				if(strlen($data["areascode"]) != 7){
					alert('地区代码为7位字符','Main/reportSet');
					exit;
				}
				if(strlen($data["institutioncode"]) != 14){
					alert('标准化机构编码为14位字符','Main/reportSet');
					exit;
				}
				$user_set_db = M('User_set');
				$set_val = $user_set_db->where(array('u_id'=>$this->user['id']))->find();
				if($set_val){
					$user_set_db->data($data)->where(array('u_id'=>$this->user['id']))->save();
				}else{
					$data['u_id'] = $this->user['id'];
					$user_set_db->data($data)->add();
				}
				alert('设置成功','Main/index');
    	}
    }

    public function uploadReport(){
    	$set = M('User_set')->where(array('u_id'=>$this->user['id']))->find();
    	if(!$set){
    		alert('请先进行基本设置','main/reportset');
    		exit;
    	}

	    $upload = new \Think\Upload();// 实例化上传类
	    $upload->maxSize   =     3145728 ;// 设置附件上传大小
	    $upload->exts      =     array('xls');// 设置附件上传类型
	    $upload->rootPath  =     './upload/'; // 设置附件上传根目录
	    $upload->savePath  =     ''; // 设置附件上传（子）目录
	    // 上传文件 
	    $info   =   $upload->upload();
	    if(!$info) {// 上传错误提示错误信息
	        alert($upload->getError(),'Main/index');
	    }else{// 上传成功
	    	session('upload_file',$info);
	    }
	    alert('上传成功','main/report');
    }
    public function report(){
    	$map['year'] = $_GET['year'];
    	$map['month'] = $_GET['month'];
    	$report_db = D('Report');
    	$re = $report_db->getList($map,$this->user['id']);
    	$set = M('User_set')->where(array('u_id'=>$this->user['id']))->find();
    	$curr_year = intval(date('Y'));
		$curr_month = date('m');
		for($i=5;$i>0;$i--){
		    if($i<0){break;}
		    $year[]= $curr_year+$i;
		}

		$year[] = $curr_year;
		for ($i=1; $i<=5; $i++) { 
			$year[]= $curr_year-$i;
		}
		if($map['year'] == ''){
			$this->assign('curr_year',$curr_year);				
		}else{
			$this->assign('curr_year',$map['year']);				
		}

		if($map['month'] == ''){
			$this->assign('curr_month',$curr_month);				
		}else{
			$this->assign('curr_month',$map['month']);				
		}
		for($i=0;$i<count($re['data_list']);$i++)
			switch($re['data_list'][$i]['reportname'])
			{
				case "JGZB": $re['data_list'][$i]['reportname'] = "监管指标表"; break;
				case "ZXTJ": $re['data_list'][$i]['reportname'] = "专项统计表"; break;
				case "LR": $re['data_list'][$i]['reportname'] = "利润表"; break;
				case "ZCFZ": $re['data_list'][$i]['reportname'] = "资产负债表"; break;
			}
		$this->assign('year',$year);
    	$this->assign('report_list',$re['data_list']);
    	$this->assign('page',$re['page_list']);

		/** PHPExcel_IOFactory */
		vendor('PHPExcel.PHPExcel.IOFactory');
		// Check prerequisites
		$info = session('upload_file');
		$file_path = './upload/'.$info['table']['savepath'].$info['table']['savename'];
		//$file_path = './upload/2016-01-19/569e1ada73718.xls';
		$file_org_name = $info['table']['name'];
		if(!file_exists($file_path) || empty($info)) {
			alert("没有找到xls文件.",'main/index');
		}
		 
		$reader = \PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
		//$PHPExcel = $PHPReader->load($dir.$templateName);
		$PHPExcel = $reader->load($file_path); // 载入excel文件
		$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
		$highestRow = $sheet->getHighestRow(); // 取得总行数
		$highestColumm = $sheet->getHighestColumn(); // 取得总列数
		
		
		//dat
		$dat_con = '';
		/** 循环读取每个单元格的数据 */
		$reportName = $sheet->getCell('A'.'1')->getValue();
		$datacol1 = 'B';
		$datacol2 = 'A';
		$row = 4;
		if($reportName =="")
		{
			$reportName = "监管指标表";
			$datacol1 = 'C';
			$datacol2 = 'B';
			$datacol3 = 'D';
			$row = 6;
		}
		else if($reportName !="人行资产负债表")
			$datacol3 = 'C';
		else
			$datacol3 = 'D';
		$this->assign('reportName',$reportName);
		for (; $row <= $highestRow; $row++){//行数是以第1行开始
			$table_val[$row]['0'] = $sheet->getCell($datacol1.$row)->getValue();
			$table_val[$row]['1'] = $sheet->getCell($datacol2.$row)->getValue();
			$table_val[$row]['2'] = trim($sheet->getCell($datacol3.$row)->getValue());
		}
		//@unlink(realpath($file_path));
		$this->assign('table_val',$table_val);	
		$curr_year = intval(date('Y'));
		$curr_month = date('m');
		for($i=5;$i>0;$i--){
		    if($i<0){break;}
		    $year[]= $curr_year+$i;
		}

		$year[] = $curr_year;
		for ($i=1; $i<=5; $i++) { 
			$year[]= $curr_year-$i;
		} 
		$this->assign('curr_year',$curr_year);
		$this->assign('curr_month',$curr_month);
		$this->assign('year',$year);
		$count = 0;
		if (!file_exists('./upload/'.$info['table']['savepath'].$set['institutioncode']))
			mkdir('./upload/'.$info['table']['savepath'].$set['institutioncode']);
		if($reportName == "监管指标表")
		{
			$rename = './upload/'.$info['table']['savepath'].$set['institutioncode'].'/'.$curr_year."-".$curr_month."-".$set['institutioncode']."-"."JGZB".$count.".xls";
			while(file_exists($rename))
				{
					$count++;
					$rename = './upload/'.$info['table']['savepath'].$set['institutioncode'].'/'.$curr_year."-".$curr_month."-".$set['institutioncode']."-"."JGZB".$count.".xls";
				}
		}
		if($reportName == "人行资产负债表")
		{
			$rename = './upload/'.$info['table']['savepath'].$set['institutioncode'].'/'.$curr_year."-".$curr_month."-".$set['institutioncode']."-"."ZCFZ".$count.".xls";
			while(file_exists($rename))
				{
					$count++;
					$rename = './upload/'.$info['table']['savepath'].$set['institutioncode'].'/'.$curr_year."-".$curr_month."-".$set['institutioncode']."-"."ZCFZ".$count.".xls";
				}
		}
				if($reportName == "人行专项统计表")
		{
			$rename = './upload/'.$info['table']['savepath'].$set['institutioncode'].'/'.$curr_year."-".$curr_month."-".$set['institutioncode']."-"."ZXTJ".$count.".xls";
			while(file_exists($rename))
				{
					$count++;
					$rename = './upload/'.$info['table']['savepath'].$set['institutioncode'].'/'.$curr_year."-".$curr_month."-".$set['institutioncode']."-"."ZXTJ".$count.".xls";
				}
		}
				if($reportName == "人行利润表")
		{
			$rename = './upload/'.$info['table']['savepath'].$set['institutioncode'].'/'.$curr_year."-".$curr_month."-".$set['institutioncode']."-"."LR".$count.".xls";
			while(file_exists($rename))
				{
					$count++;
					$rename = './upload/'.$info['table']['savepath'].$set['institutioncode'].'/'.$curr_year."-".$curr_month."-".$set['institutioncode']."-"."LR".$count.".xls";
				}
		}
		rename($file_path,$rename);
		session('upload_file',null);
		$this->display('main/report');
    }
    public function editreport(){
    	$year = $_POST['year'];
    	unset($_POST['year']);
    	$month = $_POST['month'];
    	unset($_POST['month']);
    	$zip_month = $month;
    	$reportName = $_POST['reportName'];
    	switch($reportName)
    	{
    		case "人行资产负债表": $reportName = 'ZCFZ'; break;
    		case "人行利润表": $reportName = 'LR'; break;
    		case "人行专项统计表": $reportName = 'ZXTJ'; break;
    		case "监管指标表": $reportName = 'JGZB'; break;
    	}
    	if($month == '01'){
    		$days = '01';
    	}else{
    		$month = $month -1;
    		if($monty < 10){
    			$month = '0'.$month;

    		}
    		$days = date('t', strtotime('-1 month'));
    	}
    	$frequentness = $_POST['frequentness'];
    	unset($_POST['frequentness']);
    	foreach($_POST as $val){
    		$data[] = explode('*', $val);
    	}

    	$set = M('User_set')->where(array('u_id'=>$this->user['id']))->find();
		$user_set[0] = 'I00001';//关键字代码
		$user_set[1] = 'A1411';//表单代码
		$user_set[2] = $set['organizationcode'];//机构类代码
		$user_set[3] = $set['areascode'];//地区代码
		$user_set[4] = '1';//数据属性
		$user_set[5] = 'CNY0001';//币种
		$user_set[6] = '1';//单位
		$user_set[7] = '1';//业务数据标志
		$user_set[8] = '1';//数值型类型
		$user_set[9] = $set['institutioncode'];//标准化机构编码
		
		//idx
		$idx_con = implode('|', $user_set);
		//dat
		$dat_con = '';
		foreach($data as $val){
			$dat_con .=$user_set[0].'|'.$val[0].'|'.$val[1]."\r\n";
		}
		$dat_con = trim($dat_con);

		/*filename;
		1         代号（金融统计监测管理信息系统-数值型统计指标数据－B）
		2		  标志位（头文件：I；数据文件：J；数据说明文件：D）
		3—6	  机构类代码
		7—13	  地区代码
		14—21	  年（4位），月（2位，01、02…11、12月），日（2位）
		22	  频度 报送银行的次数，没做报文前让用户选。
		23	  批次 固定1
		24    顺序号（文件名的顺序码没有特别的含义，主要为区分多次报送而设置，也可以在数据修改阶段，用于对不同时间报送的数据进行区分）
		*/
		//头文件
		$idx_filename = 'B'.'I'.$user_set[2].$user_set[3].$year.$month.$days.$frequentness.'1'.'1'.'.idx';
		//数据文件
		$dat_filename = 'B'.'J'.$user_set[2].$user_set[3].$year.$month.$days.$frequentness.'1'.'1'.'.dat';
		//说明文件
		$txt_filename = 'B'.'D'.$user_set[2].$user_set[3].$year.$month.$days.$frequentness.'1'.'1'.'.txt';



		file_put_contents(TEMP_PATH.$idx_filename, $idx_con);
		file_put_contents(TEMP_PATH.$dat_filename, $dat_con);
		file_put_contents(TEMP_PATH.$txt_filename, '');
     	vendor('PclZip');

     	$data['path'] = date('Y-m-d').'/'.$this->user['id'].'-'.$year.'-'.$month.'-'.$frequentness.$reportName.'.zip';
     	$data['u_id'] = $this->user['id'];
     	$data['year'] = $year;
     	$data['month'] = $zip_month;
     	$data['frequentness'] = $frequentness;
     	$data['reportname'] = $reportName;
     	$data['updatetime'] = $this->timestamp;

     	$zipfile = './upload/'.$data['path'];
		$archive = new \PclZip($zipfile);
		$file_path = TEMP_PATH.$idx_filename.','.TEMP_PATH.$dat_filename.','.TEMP_PATH.$txt_filename;
        $v_list = $archive->create($file_path,PCLZIP_OPT_REMOVE_PATH, 'Application/Runtime/Temp/');
		 
		if ($v_list == 0) {
			echo json_encode(array(
				'err'=>1,
				'msg'=>$archive->errorInfo(true)
			),true);
		}else{
			$report_db = M('Report');
			$tmp = $report_db ->where(array('u_id'=>$this->user['id'],'year'=>$year,'month'=>$zip_month,'frequentness'=>$frequentness,'reportname'=>$reportName))->find();
			if($tmp){
				$a = $report_db ->where(array('u_id'=>$this->user['id'],'year'=>$year,'month'=>$zip_month,'frequentness'=>$frequentness,'reportname'=>$reportName))->data($data)->save();
				$exsitPf = 1;
			}else{
				$a = M('Report')->data($data)->add();	
				$exsitPf = 0;
			}
		}
		if($a){
			unset($_POST['reportName']);
			@unlink(realpath(TEMP_PATH.$idx_filename));
			@unlink(realpath(TEMP_PATH.$dat_filename));
			@unlink(realpath(TEMP_PATH.$txt_filename));
			echo json_encode(array(
				'err'=>0,
				'exsitPf' =>$exsitPf
			),true);
		}

    }
    public function changereport(){
    	$year = $_GET['year'];
    	$report = $_GET['report'];
		$report_db = M('Report');
		$tmp = $report_db ->where(array('u_id'=>$this->user['id'],'year'=>$year,'reportname'=>"ZXTJ"))->find();
		$this->assign('year',$year);
    	$this->assign('report_list',$re['data_list']);
    	$this->assign('page',$re['page_list']);
    	$this->display('main/user');
    	//echo $report;
    	//$this->display('');
    }
    public function download(){
    	$id = abs($_GET['id']);
    	if($id == 0){
    		alert('下载文件不存在','main/index');
    	}
    	$report = M('report')->find($id);
    	$tmp_path = $report['path'];
    	$path = explode('/', $tmp_path);
    	$dir = './upload/'.$path[0].'/';
    	$filename = $path[1];
         
        if (!file_exists($dir.$filename)){
            header("Content-type: text/html; charset=utf-8");
            echo "File not found!";
            exit; 
        } else {
            $file = fopen($dir.$filename,"r"); 
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: ".filesize($dir . $filename));
            Header("Content-Disposition: attachment; filename=".$filename);
            echo fread($file, filesize($dir.$filename));
            fclose($file);
        }
    }
    public function reportDel(){
    	$id = abs($_GET['id']);
    	if($id == 0){
    		alert('请选择要删除的报表','main/index');
    	}
    	$report = M('report')->find($id);
    	if($this->user['id'] != $report['u_id']){
    		alert('删除失败','main/index');
    	}
    	@unlink(realpath('./upload/'.$report['path']));
    	$report = M('report')->delete($id);
    	alert('删除成功','main/index');
    }
    public function editPassword(){
    	if($this->post){
    		$old_pwd = trim($_POST['old_password']);
    		$new_pwd1 = trim($_POST['new_password1']);
    		$new_pwd2 = trim($_POST['new_password2']);
    		if($new_pwd1 != $new_pwd2){
    			alert('两次输入不一致','main/editPassword');
    			exit;
    		}

    		if(empty($old_pwd)){
    			alert('请填写旧密码','main/editPassword');
    			exit;
    		}
    		if(empty($new_pwd1)|| empty($new_pwd2)){
    			alert('请填写新密码','main/editPassword');
    			exit;
    		}
    		$old_pwd = md5($_POST['old_password']);
    		$new_pwd = md5($_POST['new_password1']);
    		if($old_pwd == $old_pwd){
    			M('User')->data(array('password'=>$new_pwd))->where(array('id'=>$this->user['id']))->save();
    			$this->disAuthCookie();
    			$this->redirect('login/login');
    		}
    	}else{
    		$this->display('main/editpassword');
    	}
    }

    public function admin(){
    	if($this->user['type'] == 1){
    		$db = D('User');
    		$data = $db->getList();
    		$this->assign('data',$data['data_list']);
    		$this->assign('page',$data['page_list']);
    		$this->display('main/admin');
    	}else{
    		$this->disAuthCookie();
    		$this->redirect('login/login');
    	}
    }

    public function adduser(){
    	if($this->user['type'] == 1){
    		$user_name = trim($_POST['username']);
    		if(empty($user_name)){
    			alert('用户名不能为空','main/admin');
    			exit;
    		}
    		if(M('User')->where(array('username'=>$user_name))->find()){
    			alert('用户名已存在','main/admin');
    			exit;	
    		}
    		$data['username'] = $user_name;
    		$data['password'] = md5('888888');
    		$data['type'] = 2;
    		$data['updatetime'] = $this->timestamp;
    		M('User')->data($data)->add();
    		alert('新增成功','main/admin');
    	}else{
    		$this->disAuthCookie();
    		$this->redirect('login/login');
    	}
    }
}