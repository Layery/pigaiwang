<?php
/**
 * 分页类
 *
 * Class Page
 * @Example: $pageLink = new Page( "p#p.html" ); //page/#p  为分页样式
 *           $pageLink->set( "limit" , 20 );
 *           $pageLink->set( "total" , 100 );
 *           $allPage = $pageLink->getTotalPage(); //总页数
 *           $pageLink->set( "uri" , "/aa/bb/cc/" );
 *           $pageLink->set( "page" , 2 );
 *           $limit = $pageLink->getLimit();
 *           $uriPath = $pageLink->getPageUrl();        //获取当前页面的url路径
 *           $this->assign( "page" , $pageLink->getPages() ); //分页列表
 *           $this->assign( "mPage" , $pageLink->getMPages() );
 *
 *
 */
namespace app\common;
class Page {
    /**
     * @var 当前url链接
     */
    public $pageUri;                 //当前url链接
    private $uri;                    //获取url链接
    private $query;                 //参数
    private $baseUri='page/#p';    //分页样式
    private $total;                 //总数
    private $totalPage;             //总页数
    private $page=1;                //当前页数
    private $limit=10;              //每页条数
    private $showList=5;            //显示的分页数
    private $info=array( "first"=>"首页",  "prev"=>"上一页","pageList"=>"分页列表","next"=>"下一页", "last"=>"末页","prevClass"=>"prevPage","nextClass"=>"nextPage","pageClass"=>"pageNum");
    private $showTotalPage = 1;
    private $url_type = 0; //page形式 1 ?page=2  0 /page/1
    private $first_url='';//单独设置首页链接地址

    /**
     * @param string $baseUri 分页样式
     */
    public function __construct($baseUri='page/#p/'){
        $this->baseUri=$baseUri;
        $url = $_SERVER['REQUEST_URI'];
        $uri = parse_url($url);
        $this->uri = $uri['path'];
        if(isset($uri['query'])){
            $this->query='?'.$uri['query'];
        }
    }

    /**
     * 设置参数
     *
     * @Author : whoSafe
     *
     * @Example: $model->set("info",array("first"=>"1",  "prev"=>"1","pageList"=>"1","next"=>"1", "last"=>"1"))
     *
     * @param string $key       指定设置的key
     * @param string|array $val 值
     *
     * @return $this
     *$is_clear_query 是否清除query true 是 false 否
     */
    public function set($key,$val,$is_clear_query=true){
        $base=array("info","uri","showList",'total','limit','page', 'showTotalPage','first_url','url_type');
        if(in_array($key,$base)){
            $this->{$key}=$val;
            if($is_clear_query===true and $key=='uri'){
                $this->query='';
            }
        }
        return $this;
    }

    /**
     * 获取分页数据
     *
     * @Author : whoSafe
     *
     * @Example:$model->getPages()
     *
     * @return string
     */
    public function getPages(){
        $this->getTotalPage();
        $this->getUri();
        $str="";
        if(is_array($this->info) && $this->info){
            foreach($this->info as $key=>$val){
                if(method_exists($this,$key)){
                    $str.=$this->$key();
                }
            }
            if($this->showTotalPage && $this->totalPage) {
                $str .= "<i>共" . $this->total ."条数据</i><i>" .$this->page.'/'. $this->totalPage . "页</i>";
            }
        }else{
            $str=$this->pageList();
        }
        return $str ? "<div class='navpage'>".$str."</div>" : $str;

    }

    /**
     * 获取数据开始和偏远量
     *
     * @Author : whoSafe
     *
     * @Example:$model->getLimit()
     *
     * @return array
     */
    public function getLimit(){
        return array(
            "skip"=>max(($this->page-1)*$this->limit,0),
            "limit"=>$this->limit,
        );
    }

    /**
     * 获取url路径
     *
     * @Author : whoSafe
     *
     * @Example:$model->getPageUrl()
     *
     * @return string
     */
    public function getPageUrl(){
        return $this->replace($this->page);
    }

    /**
     * 获取总分页数
     *
     * @Author : whoSafe
     *
     * @Example:$model->getTotalPage()
     *
     * @return float
     */
    public function getTotalPage(){
        $this->totalPage  =  ceil(($this->total)/($this->limit));
        return $this->totalPage;
    }

    /**
     * 首页
     * @return string
     */
    private function first(){
        if(empty($this->info['first']) || $this->totalPage<2){
            return "";
        }
        $str="";
        $url=$this->replace("");
        if($this->page >1 ){
            $str.=$this->html($url,$this->info['first']);
        }
        return $str;
    }

    /**
     * 末尾页
     *
     * @return string
     */
    private function last(){
        if(empty($this->info['last']) || $this->totalPage<2 ||$this->page >$this->totalPage){
            return "";
        }
        $str="";
        $url=$this->replace($this->totalPage);
        if($this->page !=$this->totalPage ){
            $str.=$this->html($url,$this->info['last']);
        }
        return $str;
    }

    /**
     * 上一页
     * @return string
     */
    private function prev(){
        if(empty($this->info['prev']) || $this->totalPage<2){
            return "";
        }
        $str="";
        $url=$this->replace($this->page-1);
        $prevClass = !empty($this->info['prevClass']) ? $this->info['prevClass'] : '';
        if($this->page>1){
            $str.=$this->html($url,$this->info['prev'],$prevClass);
        }
        return $str;
    }

    /**
     * 下一页
     * @return string
     */
    private function next(){
        if(empty($this->info['next']) || $this->totalPage<2){
            return "";
        }
        $str="";
        $url=$this->replace($this->page+1);
        $prevClass = !empty($this->info['nextClass']) ? $this->info['nextClass'] : '';
        if($this->page<$this->totalPage){
            $str.=$this->html($url,$this->info['next'],$prevClass);
        }
        return $str;
    }

    /**
     * 分页数字列表
     * @return string
     */
    private function pageList(){
        if($this->totalPage<2){
            return "";
        }
        $str="";
        $middle=ceil($this->showList/2);

        $startPage=max(0,$this->page-$middle);

        if(($endPage=$startPage+$this->showList-1)>=$this->totalPage)
        {
            $endPage=$this->totalPage-1;
            $startPage=max(0,$endPage-$this->showList+1);
        }
        $pageClass = !empty($this->info['pageClass']) ? $this->info['pageClass'] : '';
        for(;$startPage<=$endPage;++$startPage){
            if($this->page ==$startPage+1){
                $str.=$this->html('',$this->page,$pageClass);
            }else{
                $url=$this->replace($startPage+1);
                $str.=$this->html($url,$startPage+1,$pageClass);
            }
        }
        return $str;
    }

    /**
     * 生成html代码
     *
     * @param $uri
     * @param $name
     *
     * @return string
     */
    private function html($uri,$name,$class=''){
        $this->pageUri=$uri;
        if ($class) $class = ' class="'.$class.'"';
        if($uri){
            if($this->url_type==1){
                return "<a{$class} href='".($uri)."'>".$name."</a>";
            }
            return "<a{$class} href='".($uri.$this->query)."'>".$name."</a>";
        }else{
            return "<span{$class}>{$name}</span>";
        }
    }

    /**
     * 分页替换
     *
     * @param $page
     *
     * @return string
     */
    private function replace($page){
        if($this->url_type==1){
            $reg="#page=\d#";
            if(preg_match($reg,$this->query)){
                $this->query=ltrim(str_replace("?","",preg_replace($reg,"",$this->query)),"&");
            }
            $query= '';
            if(is_numeric($page) && $page>1 ){
                if($this->query){
                    $query="?page={$page}".'&'.ltrim($this->query,"?");
                }else{
                    $query="?page={$page}";
                }
            }else{
                if($this->query){
                    $query='?'.ltrim($this->query,"?");
                }
            }
            return $this->uri.$query;
        }
        if(is_numeric($page) && $page>1 ){
            return str_replace("#p",$page,$this->uri);
        }else{
            return $this->first_url?$this->first_url:rtrim(str_replace($this->baseUri,'',$this->uri),'/').'/';
        }
    }

    /**
     * 创建基础url
     */
    public function getUri(){
        if($this->url_type==1){
            $this->uri = $this->uri;
        }else{
            $reg="#".str_replace("#p",'\d+',$this->baseUri)."?#";
            if(preg_match($reg,$this->uri)){
                $this->uri=preg_replace($reg,$this->baseUri,$this->uri);
            }else{
                $this->uri=rtrim($this->uri,'/').'/'.$this->baseUri;
            }
        }

    }
} 