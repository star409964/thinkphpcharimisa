<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	$mo = M('RoleUser');
	$list = $mo->join('RIGHT JOIN __USER__ ON __ROLE_USER__.user_id = __USER__.id')->field('user_id,account,nickname')->select();	
	trace($list,'用户信息');
	trace(M()->_sql(),'最后sql语句');
    	$this->display();
    }
	public function ajaxindex(){
    		$list = M("Node")->select();
			$recordsTotal = $recordsFiltered = M("Node")->count();
		//获取Datatables发送的参数 必要
		$draw = I('draw');//这个值作者会直接返回给前台	
		//搜索
$searchs = I('search');//获取前台传过来的过滤条件
 $search = $searchs['value'];
//分页
$start = $_GET['start'];//从多少开始
$length = $_GET['length'];//数据长度

echo json_encode(array(
    "draw" => intval($draw),
    "recordsTotal" => intval($recordsTotal),
    "recordsFiltered" => intval($recordsFiltered),
    "data" => $list
),JSON_UNESCAPED_UNICODE);

    }
}