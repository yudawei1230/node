<?php
namespace Home\Model;
use Think\Model;
class ReportModel extends Model {
    public function getList($map,$u_id){
        $db_pre = C('db_prefix');
        if($map['year']){
            $map_k['year'] = $map['year'];
        }
        if($map['month']){
            $map_k['month'] = $map['month'];   
        }
        if($map['reportname'])
        {   
            $map_k['reportname'] = $map['reportname'];
        }
        $map_k['u_id'] = $u_id;
        $count = (int) $this->where($map_k)->count();
        $page = new \Think\Page($count,10);
        $page->setconfig('header','条数据');
        $page->setconfig('prev','上一页');
        $page->setconfig('next','下一页');        
        $list = $this->where($map_k)->order('month')->limit($page->firstRow.','.$page->listRows)->select();
        


        $data['data_list'] = $list;
        $data['page_list'] = $page->show();
        return $data;
    }
}