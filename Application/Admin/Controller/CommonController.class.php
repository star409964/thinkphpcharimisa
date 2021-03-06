<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\Rbac;
class CommonController extends Controller {

	function _initialize() {
		
		// 用户权限检查
		if (C ( 'USER_AUTH_ON' ) && !in_array(MODULE_NAME,explode(',',C('NOT_AUTH_MODULE')))) {
			import ( 'ORG.Util.RBAC' );
			if (! RBAC::AccessDecision ()) {
				//检查认证识别号
				if (! $_SESSION [C ( 'USER_AUTH_KEY' )]) {
					//跳转到认证网关
					if ($this->isAjax()){ // zhanghuihua@msn.com
						$this->ajaxReturn(true, "", 301);
					} else {
						//跳转到认证网关
						redirect ( PHP_FILE . C ( 'USER_AUTH_GATEWAY' ) );
					}
				}
				// 没有权限 抛出错误
				if (C ( 'RBAC_ERROR_PAGE' )) {
					// 定义权限错误页面
					redirect ( C ( 'RBAC_ERROR_PAGE' ) );
				} else {
					$this->ajaxerror('没有权限');
				}
			}
		}
		$this->assign('menu',$this->getControllerName());
		$this->assign('dtime',date("Y-m-d h:i:s "));
	}

	
	public function index() {
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		//  $map[C('VAR_PAGE')]=$_REQUEST(C('VAR_PAGE'));
		$name=$this->getControllerName();
	    //dump($map);
		$mode = D ($name);
		if (! empty ( $mode )) {
			$this->_list ( $mode, $map );
		}
		//echo $mode->_sql();
		$this->display ();
		return;
	}
	/**
	 +----------------------------------------------------------
	 * 取得操作成功后要返回的URL地址
	 * 默认返回当前模块的默认操作
	 * 可以在action控制器中重载
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 * @throws ThinkExecption
	 +----------------------------------------------------------
	 */
	function getReturnUrl() {
		return __URL__ . '?' . C ( 'VAR_MODULE' ) . '=' . MODULE_NAME . '&' . C ( 'VAR_ACTION' ) . '=' . C ( 'DEFAULT_ACTION' );
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据表单生成查询条件
	 * 进行列表过滤
	 +----------------------------------------------------------
	 * @access protected
	 +----------------------------------------------------------
	 * @param string $name 数据对象名称
	 +----------------------------------------------------------
	 * @return HashMap
	 +----------------------------------------------------------
	 * @throws ThinkExecption
	 +----------------------------------------------------------
	 */
	protected function _search($name = '') {
		//生成查询条件
		if (empty ( $name )) {
			$name = $this->getControllerName();
		}
		//$name=$this->getControllerName();
		$model = D ( $name );
		$map = array ();
		foreach ( $model->getDbFields () as $key => $val ) {
			if (isset ( $_REQUEST [$val] ) && $_REQUEST [$val] != '') {
				$map [$val] = array('like',$_REQUEST [$val].'%');
			}
		}
	
		return $map;
	
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据表单生成查询条件
	 * 进行列表过滤
	 +----------------------------------------------------------
	 * @access protected
	 +----------------------------------------------------------
	 * @param Model $model 数据对象
	 * @param HashMap $map 过滤条件
	 * @param string $sortBy 排序
	 * @param boolean $asc 是否正序
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 * @throws ThinkExecption
	 +----------------------------------------------------------
	 */
	protected function _list($model, $map, $sortBy = 'sort', $asc = true) {
		//排序字段 默认为主键名
		if (isset ( $_REQUEST ['_order'] )) {
			$order = $_REQUEST ['_order'];
		} else {
			$order = ! empty ( $sortBy ) ? $sortBy : $model->getPk ();
		}
		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if (isset ( $_REQUEST ['_sort'] )) {
			$sort = $_REQUEST ['_sort'] ? 'asc' : 'desc';
		} else {
			$sort = $asc ? 'asc' : 'desc';
		}
		//取得满足条件的记录数
		$count = $model->where ( $map )->count ( 'id' );
		if ($count > 0) {
				
			//创建分页对象
			if (! empty ( $_REQUEST ['listRows'] )) {
				$listRows = $_REQUEST ['listRows'];
			} else {
				$listRows = '10';
			}
			$p = new \Think\Page ( $count, $listRows );
			//分页查询数据
	
			$voList = $model->where($map)->order( "`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->select( );
			//分页跳转的时候保证查询条件
			/* foreach ( $map as $key => $val ) {
			 if (! is_array ( $val )) {
			 $p->parameter .= "$key=" . urlencode ( $val ) . "&";
			 }
			} */
			//分页css样式表
			$this->pagecss($p);
			//分页显示
			$page = $p->show ();
			//列表排序显示
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			//模板赋值显示
			trace(json_encode($voList),'公共的列表信息');
			$this->assign ( 'list', $voList );
			$this->assign ( 'sort', $sort );
			$this->assign ( 'order', $order );
			$this->assign ( 'sortImg', $sortImg );
			$this->assign ( 'sortType', $sortAlt );
			$this->assign ( "page", $page );
		}
		$this->assign ( 'totalCount', $count );
		$this->assign ( 'numPerPage', $p->listRows );
		$this->assign ( 'totalPage', $p->totalPages );
		$this->assign ( 'listRows', $listRows );
		$this->assign ( 'currentPage', !empty($_REQUEST[C('VAR_PAGE')])?$_REQUEST[C('VAR_PAGE')]:1);
			
		//Cookie::set ( '_currentUrl_', __SELF__ );
	
		return;
	}
	
	public function pagecss($page){
		$page->setCss('prev','previous paginate_button paginate_button_disabled');
		$page->setCss('next','next paginate_button');
		$page->setCss('first','first paginate_button paginate_button_disabled');
		$page->setCss('end','last paginate_button');
		$page->setCss('num','paginate_button');
		$page->setCss('current','active');
	
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
	
	}
	function insert() {
		//B('FilterString');
		$name=$this->getControllerName();
		$model = D ($name);
		if (false === $model->create ()) {
			$this->ajaxerror($model->getError (), 0);
		}
		$model->wxid = getWxid();
		//保存当前数据对象
		$list=$model->add ();
		if ($list!==false) { //保存成功
			//$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			$this->ajaxsuccess('新增成功！');
		} else {
			//失败提示
			$this->ajaxerror('sory-添加失败');
		}
	}
	
	public function add() {
		$this->display ();
	}
	
	function read() {
		$this->edit ();
	}
	
	function edit() {
		$name=$this->getControllerName();
		$model = D ( $name );
		$id = $_REQUEST [$model->getPk ()];
		$vo = $model->getById ( $id );
		$this->assign ( 'vo', $vo );  
		$this->display ();
	}
	
	function update() {
		//B('FilterString');
		$name=$this->getControllerName();
		$model = D ( $name );
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		// 更新数据
		$list=$model->save ();
		if (false !== $list) {
			//成功提示
			$this->ajaxsuccess('编辑成功！');
		} else {
			//失败提示
			$this->ajaxerror('sory-编辑失败');
		}
	}
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-9-9
	 * 修改时间：
	 +----------------------------------------------------------
	 * 控制器说明：删除对应图片
	 +----------------------------------------------------------
	 * 继承：公共控制器 
	 +----------------------------------------------------------
	 * 备注：获取对应的id， $imag 传递对应的图片字段
	 +----------------------------------------------------------
	 */
	public function delimg($img='pic_src'){
		if(I('id')){
			$id = I('id');
			$name=$this->getControllerName();
			$src =D($name)->where('id='.$id)->setField($img,'');
			if($src){
				$this->ajaxReturn(array('msg'=>'删除成功','state'=>'1'));
			}else{
				$this->ajaxReturn(array('msg'=>'删除失败','state'=>'2'));
			};
		}
	
	}
	/**
	 +----------------------------------------------------------
	 * 默认删除操作
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 * @throws ThinkExecption
	 +----------------------------------------------------------
	 */
	public function foreverdelete() {
		//删除指定记录
		$name=$this->getControllerName();
		$model = M ($name);
		if (! empty ( $model )) {
			$pk = $model->getPk ();
			$id = I($pk);
			if (isset ( $id )) {
				$condition = array ($pk => array ('in', explode ( ',', $id ) ) );
				$list=$model->where ( $condition )->setField ( 'status', - 1 );
				if ($list!==false) {
					jsonReturn(1,'删除成功');
				} else {
					jsonReturn(110,'删除失败');
				}
			} else {
				$this->ajaxReturn('非法操作','json');
			}
		}
	}
	
	public function delete() {
		//删除指定记录
		$name=$this->getControllerName();
		$model = D ($name);
		if (! empty ( $model )) {
			$pk = $model->getPk ();
			$id = I($pk);
			if (isset ( $id )) {
				$condition = array ($pk => array ('in', explode ( ',', $id ) ) );
				if (false !== $model->where ( $condition )->delete ()) {
					jsonReturn(1,'删除成功'.$name);
				} else {
					jsonReturn(110,'删除失败');
				}
			} else {
				jsonReturn(120,'非法操作');
			}
		}
		$this->forward ();
	}
	public function del() {
		//删除指定记录
		$name=$this->getControllerName();
		$model = D ($name);
		if (! empty ( $model )) {
			$pk = $model->getPk ();
			$id = I('check_box');
			if (isset ( $id )) {
				$condition = array ($pk => array ('in',$id) );
				if (false !== $model->where ( $condition )->delete ()) {
					//echo $model->getlastsql();
					$this->success('删除成功');
				} else {
					$this->error('删除失败');
				}
			} else {
				$this->error('非法操作');
			}
		}
		//$this->forward ();
	}
	
	public function clear() {
		//删除指定记录
		$name=$this->getControllerName();
		$model = D ($name);
		if (! empty ( $model )) {
			if (false !== $model->where ( 'status=-1' )->delete ()) { // zhanghuihua@msn.com change status=1 to status=-1
				$this->assign ( "jumpUrl", $this->getReturnUrl () );
				$this->success ( L ( '_DELETE_SUCCESS_' ) );
			} else {
				$this->error ( L ( '_DELETE_FAIL_' ) );
			}
		}
		$this->forward ();
	}
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-8-7
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：分离启用和禁用函数
	 +----------------------------------------------------------
	 * 函数属性：重写forbid 可以传入想改变的参数
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */
	public function forbid(){
		$this->gforbid();
	}
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-9-30
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：设置状态为0
	 +----------------------------------------------------------
	 * 函数属性：$state 要对哪个字段进行设置
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */
	protected  function gforbid($state='state') {
		$name=$this->getControllerName();
		$model = D ($name);
		$pk = $model->getPk ();
		$id = $_REQUEST [$pk];
		$condition = array ($pk => array ('in', $id ) );
		$list=$model->forbid ( $condition ,$state);
		if ($list!==false) {
			$this->ajaxsuccess('禁用成功',1,showStatus(0,$id));
		} else {
			$this->ajaxerror('禁用失败', 0);
		}
	}
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-8-7
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：分离启用和禁用函数
	 +----------------------------------------------------------
	 * 函数属性：重写resume 可以传入想改变的参数
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */	
	public function resume(){
		$this->gresume();
	}
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-9-30
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：设置状态为1
	 +----------------------------------------------------------
	 * 函数属性：$state 要对哪个字段进行设置
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */
	protected function  gresume($state='state') {
		//恢复指定记录
		$name=$this->getControllerName();
		$model = D ($name);
		$pk = $model->getPk ();
		$id = $_GET [$pk];
		$condition = array ($pk => array ('in', $id ) );
		if (false !== $model->resume ( $condition,$state )) {
			$this->ajaxsuccess('恢复成功',1,showStatus(1,$id));
		} else {
			$this->ajaxerror('恢复失败', 0);
		}
	}
	
	public function checkPass() {
		$name=$this->getControllerName();
		$model = D ($name);
		$pk = $model->getPk ();
		$id = $_GET [$pk];
		$condition = array ($pk => array ('in', $id ) );
		if (false !== $model->checkPass( $condition )) {
			$this->ajaxsuccess('状态批准成功', 1);
		} else {
			$this->ajaxerror('状态批准失败', 0);
		}
	}
	
	public function recycle() {
		$name=$this->getControllerName();
		$model = D ($name);
		$pk = $model->getPk ();
		$id = $_GET [$pk];
		$condition = array ($pk => array ('in', $id ) );
		if (false !== $model->recycle ( $condition )) {
	
			$this->ajaxsuccess('恢复成功',1,showStatus(1,$id));
		} else {
			$this->ajaxerror('恢复失败', 0);
		}
	}
	
	
	
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-9-1
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：状态设定函数 
	 +----------------------------------------------------------
	 * 函数属性：$field要设定的字段名称（默认status）,
 	 * 	      $num设置的值（默认0）,
	 * 		  $status设置显示状态
	 *        $imgShow ture显示图片，false显示文字
	 +----------------------------------------------------------
	 * 备注：需要传递表的主键值才能设定 对应字段的状态
	 *     用到commonModel类的setStatusNum函数，用到公共函数function的getStatus显示函数
	 +----------------------------------------------------------
	 */
	public function setStatusNum($field='status',$num=0,$status,$imgShow=true) {
		//恢复指定记录
		$name=$this->getControllerName();
		$model = D ($name);
		$pk = $model->getPk ();
		$id = I($pk);
		$condition = array ($pk => array ('in', $id ) );
		if (false !== $model->setStatusNum( $condition,$field,$num )) {
			$this->ajaxsuccess('^-^成功',1,getStatus($status,$id,$imgShow));
		} else {
			$this->ajaxerror('@_@失败', 0);
		}
	}
    

	
	
	
	/*
	 * 返回带 样式参数的 list
	 * $model  $model	=	D("Category");
	 * $date   查找条件
	 *
	 *
	 */
	
	function ret_sytle_list($model,$date){
	
		$list	=	$model->field("id,title,pid,path,concat(path,'-',id) as bpath")->order("sort desc")->where($date)->select();
		foreach($list as $key=>$value){
			$list[$key]['signnum']= count(explode('-',$value['bpath']))-1;
			$list[$key]['marginnum']= (count(explode('-',$value['bpath']))-1)*20;
		}
		return $list;
	}
	
	function saveSort() {
		$seqNoList = $_POST ['seqNoList'];
		if (! empty ( $seqNoList )) {
			//更新数据对象
			$name=$this->getControllerName();
			$model = D ($name);
			$col = explode ( ',', $seqNoList );
			//启动事务
			$model->startTrans ();
			foreach ( $col as $val ) {
				$val = explode ( ':', $val );
				$model->id = $val [0];
				$model->sort = $val [1];
				$result = $model->save ();
				if (! $result) {
					break;
				}
			}
			//提交事务
			$model->commit ();
			if ($result!==false) {
				//采用普通方式跳转刷新页面
				$this->success ( '更新成功' );
			} else {
				$this->error ( $model->getError () );
			}
		}
	}
	/**
	 +----------------------------------------------------------
	 * 成功后ajax返回，status默认1，content默认是：新增成功
	 *
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @return ajaxReturn
	 +----------------------------------------------------------
	 * @throws FcsException
	 +----------------------------------------------------------
	 */
	public function ajaxsuccess($content='成功',$status='1',$other=''){
		$data['url'] = C('WLCMS_URL').__CONTROLLER__.'/index';
		$data['content'] = $content;
		$data['status'] = $status;
		$data['other']=$other;
		$this->ajaxReturn($data,'json');
	}
	/**
	 +----------------------------------------------------------
	 * 失败后ajax返回，status默认0，content默认是：新增失败
	 *
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @return ajaxReturn
	 +----------------------------------------------------------
	 * @throws FcsException
	 +----------------------------------------------------------
	 */
	public function ajaxerror($content='新增失败',$status='0',$other=''){
	
		$data['url'] = C('WLCMS_URL').__CONTROLLER__.'/index';
		$data['content'] = $content;
		$data['status'] = $status;
		$data['other']=$other;
		$this->ajaxReturn($data,'json');
	}
	
	/**
	 * mike2015-7-8
	 * 获取当前Action名称
	 * @access protected
	 */
	protected function getControllerName() {
	
		return CONTROLLER_NAME ;
	}
	 
}