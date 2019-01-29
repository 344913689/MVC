<?php

echo "<pre>";
$page = new Page(6, 60);
print_r($page->allUlr());

class Page{

    //每页显示多少条数据
    protected $number;
    //一共多少数据
    protected $totalCount;
    //一共多少页
    protected $totalPage;
    //当前页
    protected $page;
    //url
    protected $url;

    public function __construct($number = 6, $totalCount){
        //每页默认显示5条数据
        $this->number = $number;
        $this->totalCount = $totalCount;
        //得到总页数
        $this->totalPage = $this->getTotalPage();
        //得到当前页数
        $this->page = $this->getPage();
        //得到url
        $this->url = $this->getUrl();

    }

    protected function getTotalPage(){
        return ceil($this->totalCount / $this->number);
    }

    protected function getPage(){
        $p = $_GET['page'];
        $page = 1;
        if (empty($p) or $p < 1){
            $page = 1;
        } else if ($p > $this->totalPage){
            $page = $this->totalPage;
        } else {
            $page = $p;
        }

        return $page;
    }

    protected function getUrl(){
        $scheme = $_SERVER['REQUEST_SCHEME'];
        $host = $_SERVER['SERVER_NAME'];
        $port = $_SERVER['SERVER_PORT'];
        $uri = $_SERVER['REQUEST_URI'];
        //中间做处理
        $uriArray = parse_url($uri);
        $path = $uriArray['path'];
        if (!empty($uriArray['query'])) {
            parse_str($uriArray['query'], $array);
            unset($array['page']);
            $query = http_build_query($array);
            if ($query != '') {
                $path = $path.'?'.$query;
            }        
        }
        return $scheme.'://'.$host.':'.$port.$path;
    }

    protected function setUrl($str){
        $strurl = preg_replace('# #','',$str);
        if (strpos($this->url, '?')) {
            $url = $this->url.'&'.$strurl;
        } else {
            $url = $this->url.'?'.$strurl;
        }
        return $url;
    }

    public function allUlr(){
        return [
            'first' => $this->first(),
            'next' => $this->next(),
            'prev' => $this->prev(),
            'end' => $this->end(),
        ];
    }

    public function first(){
        return $this->setUrl('page = 1');
    }

    public function next(){
        if ($this->page + 1 > $this->totalPage) {
            $page = $this->totalPage;
        } else {
            $page = $this->page + 1;
        }

        return $this->setUrl('page = '. $page);
    }

    public function prev(){
        if ($this->page - 1 <= 0) {
            $page = 1;
        } else {
            $page = $this->page - 1;
        }
        return $this->setUrl('page = '. $page);
    }

    public function end(){
        return $this->setUrl('page = '. $this->totalPage);
    }

    public function limit(){
        //limit 0，5 limit 3，8
        $offset = ($this->page - 1) * $this->number;
        return $offset.','.$this->number;
    }
}
