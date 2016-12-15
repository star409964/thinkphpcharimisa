<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
class MenusController extends CommonController {
	
	public function _filter(&$map)
	{
	    $map['wxchat_account'] = getWxid();
		$search = I('search',110119120);
		if($search!='110119120'){
			$map['title'] = array('like','%'.$search.'%');
		}
	}
	
	/*
	 * 菜单详情
	 */
	public function info($mid){
		$map['menusid'] = $mid;
		$map['pid'] = 0;
		$mo = M('MenuInfo');
		$list = $mo->where($map)->field('id,name,sort,pid,path')-> select();
		if($list!=FALSE){
			foreach ($list as $key => $value) {
				$list[$key]['children'] = $mo->where('pid='.$value['id'])->field('id,name,sort')->select();
			}
		}
		$title = M('Menus')->where('id='.$mid)->getField('title');
		
		$this->assign('title',$title);
		$this->assign('list',$list);
		$this->display();
	}
	/*
	 * ajax 添加菜单
	 */
	 public function ajaxAdd(){
	 	$id = I('id');
		$menusid = I('menusid');
			if($id=='top'){
				$data['name'] = '一级菜单名字';
				$data['pid'] = 0;
				$data['type'] = 'view';
				$data['menusid'] = $menusid;
				$ret = M("MenuInfo")->add($data);
			}else{
				$data['name'] = '二级菜单名字';
				$data['pid'] = $id;
				$data['type'] = 'view';
				$data['menusid'] = $menusid;
				$ret = M("MenuInfo")->add($data);
			}
			if($ret!=FALSE){
				$list = M('MenuInfo')->find($ret);
				jsonReturn(1,'新增默认菜单成功',$list);
			}else{
				jsonReturn(110,'新增菜单失败');
			}
	 }
	 /*
	  * ajax-编辑菜单
	  */
	public function ajaxEdit(){
		$id = I('id');
		$info = M("MenuInfo")->find($id);
		if($info!=false){
			jsonReturn(1,'存在此菜单',$info);
		}else{
			jsonReturn(110,'sory不存在此菜单');
		}
	}
	
	/*
	 * 更新菜单
	 */
	public function ajaxUpdate(){
		C('TOKEN_ON',false);
		$model = D ('MenuInfo');
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		$list=$model->save ();
		if (false !== $list) {
			jsonReturn(1,'编辑成功');
		} else {
			jsonReturn(110,'编辑失败');
		}
		
	}
}//class end	