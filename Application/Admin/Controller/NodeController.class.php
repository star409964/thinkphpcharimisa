<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
/**
 +----------------------------------------------------------
 * 创建人：mike
 * 创建时间：2015-8-8
 * 修改时间：
 +----------------------------------------------------------
 * 控制器说明：节点-控制器
 +----------------------------------------------------------
 * 继承：公共控制器
 +----------------------------------------------------------
 * 备注：
 +----------------------------------------------------------
 */

class NodeController extends CommonController {
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-8-7
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：过滤器
	 +----------------------------------------------------------
	 * 函数属性：对map进行过滤和设置   &代表   对实参 可以修改
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */
	public function _filter(&$map)
	{   //查看菜单分组group 有没有传递值
		$grp = I('group_id');
		$sear = I('title');//查询变量
		
        if(!empty($grp)) {
            $map['group_id'] =  I('group_id');
            $this->assign('nodeName','分组');
        }elseif(empty($sear) && !isset($map['pid']) ) {
			$map['pid']	=	0;
		}
		if(I('pid')!=''){
			$map['pid']=I('pid');
		}
		$_SESSION['currentNodeId']	=	$map['pid'];
		//获取上级节点
		$node  = M("Node");
        if(isset($map['pid'])) {
            if($node->getById($map['pid'])) {
                $this->assign('level',$node->level+1);
                $this->assign('nodeName',$node->name);
            }else {
                $this->assign('level',1);
            }
        }
	}
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-8-7
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：删除之前 确认有没有  子集分类
	 +----------------------------------------------------------
	 * 函数属性：
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */	
    public function _before_foreverdelete(){
    	$id = I('id');
    	$re = D('Node')->where('pid='.$id)->select();
    	if(!empty($re)){
    		$data['status']  = 4;
			$data['content'] = '请先删除子类';
			$this->ajaxReturn($data,'json'); 
			
    	}
    }
    /**
     +----------------------------------------------------------
     * 创建人：mike
     * 创建时间：2015-8-7
     * 修改时间：
     * 修改内容：
     +----------------------------------------------------------
     * 函数说明：多条记录删除之前 确认有没有  子集分类
     +----------------------------------------------------------
     * 函数属性：
     +----------------------------------------------------------
     * 备注：
     +----------------------------------------------------------
     */
    public function _before_del(){
    	$id = I('check_box');
    	if (isset ( $id )) {
    		$condition = array ('pid' => array ('in',$id) );
    		$re = D('Node')->where($condition)->select();
    		if(!empty($re)){
    			$data['status']  = 4;
    			$data['content'] = '请先删除子类';
    			$this->ajaxReturn($data,'json');
    				
    		}
    	}
    	
    }
	public function _before_index() {
		$model	=	M("Group");
		$list	=	$model->where('status=1')->getField('id,title');
		$this->assign('groupList',$list);
	}

	// 获取配置类型
	public function _before_add() {
		$model	=	M("Group");
		$list	=	$model->where('status=1')->select();
		$this->assign('list',$list);
		$node	=	M("Node");
		$node->getById($_SESSION['currentNodeId']);
        $this->assign('pid',$node->id);
		$this->assign('level',$node->level+1);
	}

    public function _before_patch() {
		$model	=	M("Group");
		$list	=	$model->where('status=1')->select();
		$this->assign('list',$list);
		$node	=	M("Node");
		$node->getById($_SESSION['currentNodeId']);
        $this->assign('pid',$node->id);
		$this->assign('level',$node->level+1);
    }
	public function _before_edit() {
		$model	=	M("Group");
		$list	=	$model->where('status=1')->select();
		$this->assign('list',$list);
	}

    /**
     +----------------------------------------------------------
     * 默认排序操作
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function sort()
    {
		$node = M('Node');
        if(!empty($_GET['sortId'])) {
            $map = array();
            $map['status'] = 1;
            $map['id']   = array('in',$_GET['sortId']);
            $sortList   =   $node->where($map)->order('sort asc')->select();
        }else{
            if(!empty($_GET['pid'])) {
                $pid  = $_GET['pid'];
            }else {
                $pid  = $_SESSION['currentNodeId'];
            }
            if($node->getById($pid)) {
                $level   =  $node->level+1;
            }else {
                $level   =  1;
            }
            $this->assign('level',$level);
            $sortList   =   $node->where('status=1 and pid='.$pid.' and level='.$level)->order('sort asc')->select();
        }
        $this->assign("sortList",$sortList);
        $this->display();
        return ;
    }
	
	public function forbid(){
		$this->gforbid('status');
	}
	public function resume(){
		$this->gresume('status');
	}
	public function creatauth(){
			$pid = I('pid');
			$mo = D('Node');
			$list = $mo->where('id='.$pid)->getField('level');
			$listall = $mo->where('pid='.$pid)->select();
			$art = array('index'=>'列表页面','add'=>'添加页面','edit'=>'编辑页面' ,'insert'=>'添加权限','update'=>'更新权限','delete'=>'删除权限','del'=>'批量删除权限','resume'=>'启用权限','forbid'=>'禁用权限');
			if(!empty($pid)&&$list==2){
			if($listall!=false){
			$res['state']=0;
			$res['msg']='已经存在权限列表，要想生成基础权限，请先删除';
			}else{
			foreach ($art as $key => $value) {
				$data['name'] = $key;
				$data['title'] = $value;
				$data['pid'] = $pid;
				$data['status'] = 1;
				$data['level'] = 3;
				$mo->add($data);
			}
				$res['state']=1;
				$res['msg']='基本权限生成成功';
			}
			}else{
				$res['state']=0;
				$res['msg']='权限添加失败，请到二级目录生成';
			}
				$this->ajaxReturn($res);
			}
}