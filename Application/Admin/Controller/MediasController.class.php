<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
class MediasController extends CommonController {
	
	public function _filter(&$map)
	{
	    $map['wxid'] = getWxid();
		$search = I('search',110119120);
		if($search!='110119120'){
			$map['title'] = array('like','%'.$search.'%');
		}
	}
	
	public function mediaNews() {
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
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
	
	public function mediaImages() {
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$mode = M("MediaImages");
		if (! empty ( $mode )) {
			$this->_list ( $mode, $map ,'id');
		}
		$this->display ();
	}
	
	
	//:  1-news，2-image，3-video，4-voice，5-music
	public function getMedias($id){
		$wxChat = A("Api/Media","Event");
		$wxid = getWxid();
		$wxChat->getmedia($wxid,$id);//微信号，类型
	}
	
	public function index(){
		$this->display();
	}
	
	public function tt($id){
		$wxChat = A("Api/WxChat","Logic");
		$wxChat->getMediasImage('fvVr-DwM6S11H_a-sEnr6pnYKTRTGA5GcObGlZlNwrg',1);//微信号，类型
	}
	
}//class end	