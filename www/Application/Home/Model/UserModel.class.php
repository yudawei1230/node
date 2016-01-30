<?php
namespace Home\Model;
use Think\Model;
class UserModel extends Model {
    public function getList(){
        $count = (int) $this->where(array('type'=>2))->count();
        $page = new \Think\Page($count,10);
        $page->setconfig('header','条数据');
        $page->setconfig('prev','上一页');
        $page->setconfig('next','下一页');        
        $list = $this->where(array('type'=>2))->order('updatetime DESC')->limit($page->firstRow.','.$page->listRows)->select();
        $data['data_list'] = $list;
        $data['page_list'] = $page->show();
        return $data;
    }
}