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
		$list = $mo->where($map)->field('id,name,sort,pid,path')->order('sort')-> select();
		if($list!=FALSE){
			foreach ($list as $key => $value) {
				$list[$key]['children'] = $mo->where('pid='.$value['id'])->field('id,name,sort')->order('sort')->select();
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
	 	$id = I('pid');
		$menusid = I('menusid');
			if($id=='top'){
				$data['name'] = '一级菜单名字';
				$data['pid'] = 0;
				$data['path'] = 0;
				$data['type'] = 'view';
				$data['menusid'] = $menusid;
				$ret = D("MenuInfo")->add($data);
			}else{
				$data['name'] = '二级菜单名字';
				$data['pid'] = $id;
				$data['type'] = 'view';
				$data['menusid'] = $menusid;
				$data['path'] = '0-'.$id;
				$ret = D("MenuInfo")->add($data);
			}
			if($ret!=FALSE){
				$list = D('MenuInfo')->find($ret);
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
			jsonReturn(110,$model->getError ());
		}
		$list=$model->save ();
		if (false !== $list) {
			jsonReturn(1,'编辑成功');
		} else {
			jsonReturn(110,'编辑失败');
		}
		
	}
	/*
	 *   菜单排序
	 */
	public function ajaxSort(){
		$id1 = I('id1');
		$id2 = I('id2');
		$sort1 = I('sort1');
		$sort2 = I('sort2');
		$mo = M('MenuInfo');
		$mo->where('id='.$id1)->setField('sort',$sort1);
		$ret =  $mo->where('id='.$id2)->setField('sort',$sort2);
		if($ret!=FALSE){
			jsonReturn(1,'排序修改成功');
		}
	}
	
	/*
	 * 删除一个菜单 
	 */
	 
	public function deleteMenu(){
		$id = I('id');
		if(empty(id)) jsonReturn(110,'没有传递id');
		$find = M('MenuInfo')->where('pid='.$id)->find();
		if($find!=FALSE){
			jsonReturn(110,'请先删除子菜单！！');
		}else{
			$ret = M('MenuInfo')->delete($id);
			if($ret!=FALSE){
				jsonReturn(1,'删除成功');
			}else{
				jsonReturn(110,'删除失败');
			}
		}
	}
	
	
	
	/*
	 * 设置菜单
	 */
	public function setWxMenu(){
		$mid = I('menuid');
		if(empty($mid)) jsonReturn('110','参数错误');
		$button = $this->addWxMenu($mid);
//		dump($button);
		$wxid = getWxid();
		$wechatObj = A("Api/WxChat",'Logic');
		$list = $wechatObj->setMenu($button,$wxid);
	}
	
	private function addWxMenu($mid){
	$model = D('MenuInfo');	
	$date['menusid'] = $mid;
	$len = '';
	$list_all = array();
	$data1['menusid'] = $mid;
	$data1['pid'] = 0;
	//重新组装数据
	$one_list = $model->field("id,name as title,pid,path,key,type,url,media_id,concat(path,'-',id) as bpath,sort")->order('sort')->where($data1)->limit(3)->select();
	//dump($one_list);
	foreach($one_list as $keys1=>$values1){
		$data2['menusid'] = $mid;
		$data2['pid'] = $values1['id'];
		$two_list = $model->field("id,name as title,pid,path,key,type,url,media_id,concat(path,'-',id) as bpath,sort")->order('sort')->where($data2)->limit(6)->select();
		//dump($two_list);
		if($two_list!=FALSE){
			$list_all = array_merge($list_all,array(0=>$values1),$two_list);
		}else{
			$list_all = array_merge($list_all,array(0=>$values1));
		}
	}
	//重新组装数据
//		dump($list_all);
		$list = $list_all;
		//$list	=	$model->field("id,title,pid,path,key,type,url,media_id,concat(path,'-',id) as bpath,sort")->order("bpath,sort desc")->where($date)->select();
		foreach($list as $keys=>$value){
			$butte = null;
			if($list[$keys]['id'] == $list[$keys+1]['pid']){
				//有子菜单
				$top = $list[$keys]['title'];
				$butt['button'][]['name'] = $top;
				$len = count($butt['button']);
			}else{
				if($list[$keys]['path'] =='0'){//一级菜单
					$butts['type'] = $list[$keys]['type'];
					$butts['name'] = $list[$keys]['title'];
					$k = $this->get_type($list[$keys]['type']);
						if($k!=FALSE){
							$butts[$k] = $list[$keys][$k]; 
						}
					$butt['button'][] = $butts;
				}else{//二级菜单
					$butte['type'] = $list[$keys]['type'];
					$butte['name'] = $list[$keys]['title'];
					$k1 = $this->get_type($list[$keys]['type']); 
					$butte[$k1] = $list[$keys][$k1];
					$butt['button'][$len-1]['sub_button'][] = $butte;
				}
			}
		}
		return json_encode($butt,JSON_UNESCAPED_UNICODE);
	}
	
	
	private function get_type($type){ 
		switch ($type) {
			case 'click': $res = 'key';
				break;
			case 'scancode_waitmsg': $res = 'key';
				break;
			case 'scancode_push': $res = 'key';
				break;
			case 'pic_sysphoto': $res = 'key';
				break;
			case 'pic_photo_or_album': $res = 'key';
				break;
			case 'pic_weixin': $res = 'key';
				break;
			case 'location_select': $res = 'key';
				break;
			case 'media_id': $res = 'media_id';
				break;
			case 'view_limited': $res = 'media_id';
				break;
			case 'view': $res = 'url';
				break;
			default:  $res = FALSE;
				break;
		}
		return $res;
	}
	/*
	 * 选择素材
	 */
	public function selectMedias(){
		$this->display();
	}
	
	public function images(){
		$map['wxid'] = getWxid();
		//$list = M('MediaImages')->where($map)->select();
		$model = M('MediaImages');
		$this->_list($model, $map,'id');
		$this->display();
	}
	
	public function news() {
		$map['wxid'] = getWxid();
		$mode = M("MediaNews");
		if (! empty ( $mode )) {
			$media_list = $mode->where($map)->group('media_id')->getField('media_id',true);
			trace('sql=',M()->getLastSql());
			$count = count($media_list);
			$p = new \Think\Page ( $count, 10 );
			for ($i=$p->firstRow; $i <$p->listRows ; $i++) { 
				$map['media_id'] = $media_list[$i];
				if(!empty($map['media_id'])){
					$list = $mode->where($map)->select();
					$datas[] = $list;
					trace('执行循环内部if的次数');
				}
			}
			$this->assign('list',$datas);
		}
		$this->display ();
	}
	
}//class end	