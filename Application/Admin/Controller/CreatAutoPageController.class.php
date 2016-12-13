<?php
namespace Admin\Controller;
use Think\Controller;
define ( 'HT', '.html' );
class CreatAutoPageController extends Controller {
   	
   protected $viewUrl = '' ;
   static protected $controller = '' ;
   static protected $model = '' ;
	
	
	//static protected $controller
	public function __construct()
    {
    		header("Content-Type: text/html; charset=utf-8");
		$this->viewUrl =  MODULE_PATH.'View/'.CONTROLLER_NAME;
		 self::$controller = $this->getFilesStr('Controller.php');
		 self::$model = $this->getFilesStr('Model.php');
    }
   public function index(){
			//self::buildController(MODULE_NAME,'mm', '--'); //ok
			$this->createDefaultIndex();
			$this->createDefaultAdd();
			$this->createDefaultEdit();
   }
   //获取 模版文件
    public function getFilesStr($name){
   		$file_path = $this->viewUrl.'/'.$name;
		trace('模版文件路径',$file_path);
   		if(file_exists($file_path)){
			$str = file_get_contents($file_path);//将整个文件内容读入到一个字符串中
			return $str;
	   }else{
	   	echo 'File does not exist';
		exit;
	   }
   }
   
   // 检测应用目录是否需要自动创建
    static public function checkDir($module){
        if(!is_dir(APP_PATH.$module)) {
            // 创建模块的目录结构
            self::buildAppDir($module);
        }elseif(!is_dir(LOG_PATH)){
            // 检查缓存目录
            self::buildRuntime();
        }
    }

	
	 // 创建控制器类
    static public function buildController($module,$controller='Index',$fun) {
        $file   =   APP_PATH.$module.'/Controller/'.$controller.'Controller'.EXT;
        if(!is_file($file)){
        		
            $content = str_replace(array('[MODULE]','[CONTROLLER]','[Function]'),array($module,$controller,$fun),self::$controller);
            dump($content);
            if(!C('APP_USE_NAMESPACE')){
                $content    =   preg_replace('/namespace\s(.*?);/','',$content,1);
            }
            $dir = dirname($file);
            if(!is_dir($dir)){
                mkdir($dir, 0755, true);
            }
            file_put_contents($file,$content);
        }
    }

    // 创建模型类
    static public function buildModel($module,$model) {
        $file   =   APP_PATH.$module.'/Model/'.$model.'Model'.EXT;
        if(!is_file($file)){
            $content = str_replace(array('[MODULE]','[MODEL]'),array($module,$model),self::$model);
            if(!C('APP_USE_NAMESPACE')){
                $content    =   preg_replace('/namespace\s(.*?);/','',$content,1);
            }
            $dir = dirname($file);
            if(!is_dir($dir)){
                mkdir($dir, 0755, true);
            }
            file_put_contents($file,$content);
        }
    }

	/**
     * 函数说明：创建模板的所有页面
     * @deprecated 创建时间：2016-12-9
     * @deprecated 备注：
     * @author mike<stardandan@126.com>
     * @param string $view 控制器名
     * @param string $content 写入的html代码
     * @param string $tem 模板名：默认空
     * @param string $name 模板名：文件名add，index，edit，img
     * @return 创建文件
     */
    static public function buildViewAll($view='Index',$content,$name,$tem=NULL){
    	if($tem==null){
    		$file   =   APP_PATH.MODULE_NAME.'/View/'.$view.'/'.$name.HT;
    	}else{
    		$file   =   APP_PATH.MODULE_NAME.'/View/'.$tem.'/'.$view.'/'.$name.HT;
    	}
		
    	if(!is_file($file)){
    		$dir = dirname($file);
    		if(!is_dir($dir)){
    			mkdir($dir, 0755, true);
    		}
    		file_put_contents($file,$content);
    	}
    }
   
    /**
     * 函数说明：创建index模板页面
     * @deprecated 创建时间：2015-5-6
     * @deprecated 备注：
     * @author mike<stardandan@126.com>
     * @param string $module 分组名（模块名）Tourism 下的admin模块
     * @param string $view 控制器名
     * @param string $tem 模板名：默认default
     * @return 创建文件
     */
    public function createDefaultIndex(){
    $htmls = $this->getFilesStr('index.html');
    $htmls1 = $this->getFilesStr('index_1.html');
    $htmls2 = $this->getFilesStr('index_2.html');
    $htmls3 = $this->getFilesStr('index_3.html');
    	//replayLable-表格头，replayList-循环显示内容，Indexscript-附加的js
    	$content = str_replace(array('[FIRST]','[SECOND]','[THIRD]'),array($htmls1,$htmls2,$htmls3),$htmls);
    	$this->buildViewAll('MM',$content,'index');
    }
	
	/**
     * 函数说明：创建add模板页面
     * @deprecated 创建时间：2015-5-6
     * @deprecated 备注：
     * @author mike<stardandan@126.com>
     * @param string $module 分组名（模块名）Tourism 下的admin模块
     * @param string $view 控制器名
     * @param string $tem 模板名：默认default
     * @return 创建文件
     */
    public function createDefaultAdd(){
    $htmls = $this->getFilesStr('add.html');
    $htmls1 = $this->getFilesStr('add_1.html');
    $htmls2 = $this->getFilesStr('add_2.html');
    $htmls3 = $this->getFilesStr('add_3.html');
    	//replayLable-表格头，replayList-循环显示内容，Indexscript-附加的js
    	$content = str_replace(array('[FIRST]','[SECOND]','[THIRD]'),array($htmls1,$htmls2,$htmls3),$htmls);
    	$this->buildViewAll('MM',$content,'add');
    }
	
	/**
     * 函数说明：创建edit模板页面
     * @deprecated 创建时间：2015-5-6
     * @deprecated 备注：
     * @author mike<stardandan@126.com>
     * @param string $module 分组名（模块名）Tourism 下的admin模块
     * @param string $view 控制器名
     * @param string $tem 模板名：默认default
     * @return 创建文件
     */
    public function createDefaultEdit(){
    $htmls = $this->getFilesStr('edit.html');
    $htmls1 = $this->getFilesStr('edit_1.html');
    $htmls2 = $this->getFilesStr('edit_2.html');
    $htmls3 = $this->getFilesStr('edit_3.html');
    	//replayLable-表格头，replayList-循环显示内容，Indexscript-附加的js
    	$content = str_replace(array('[FIRST]','[SECOND]','[THIRD]'),array($htmls1,$htmls2,$htmls3),$htmls);
    	$this->buildViewAll('MM',$content,'edit');
    }
   
   	
}//class end
