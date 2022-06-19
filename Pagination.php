<?php

    class Pagination{
        public int $count_pages = 1;
        public int $current_page = 1;
        public string $uri = '';
        public int $mid_size = 3;
        public int $all_pages = 3;
        public int $page;
        public int $per_page;
        public int $total;

        public function __construct(
            $page = 1,
            $per_page = 1,
            $total = 1
        )
        {
            $this->page = $page;
            $this->per_page = $per_page;
            $this->total = $total;
            $this->count_pages = $this->get_count_pages();
            $this->current_page = $this->get_current_page();
            $this->uri = $this->get_params();
        }

        public function get_count_pages():int{
            return ceil($this->total/$this->per_page)?:1;
        }

        public function get_start(){
            return ($this->current_page -1)*$this->per_page;
        }
        public function get_current_page():int{
            if($this->page<1){
                $this->page = 1;
            }
            if($this->page>$this->count_pages){
                $this->page=$this->count_pages;
            }
            return $this->page;
        }

        public function get_params(): string
        {
            $url = $_SERVER['REQUEST_URI'];
            $url = explode('?',$url);
            $uri = $url[0];
            if(isset($url[1]) && $url[1]!=''){
                $uri .= '?';
                $params = explode('&', $url[1]);
                foreach($params as $param){
                    if(!preg_match("#page=#",$param)) $uri .= "{$param}&";
                }
            }
            return $uri;
        }
        public function get_html():string{
            $back = '';
            $forward = '';
            $start_page = '';
            $end_page = '';
            $pages_left = '';
            $pages_right = '';

            if($this->current_page>1){
                $back = "<li><a href='".$this->get_link($this->current_page -1)."'>назад</a></li>";
            }
            if($this->current_page<$this->count_pages){
                $forward = "<li><a href='".$this->get_link($this->current_page +1)."'>вперед</a></li>";
            }
            if($this->current_page>$this->mid_size+1){
                $start_page = "<li><a href='".$this->get_link(1)."'>перша</a></li>";
            }
            if($this->current_page<($this->count_pages - $this->mid_size)){
                $end_page = "<li><a href='".$this->get_link($this->count_pages)."'>остання</a></li>";
            }

            for($i=$this->mid_size;$i>0;$i--){
                if($this->current_page-$i>0){
                    $pages_left .= "<li><a href='".$this->get_link($this->current_page-$i)."'>".($this->current_page-$i)."</a></li>";
                }
            }
            for($i=1;$i<=$this->mid_size;$i++){
                if($this->current_page+$i<=$this->count_pages){
                    $pages_right .= "<li><a href='".$this->get_link($this->current_page+$i)."'>".($this->current_page+$i)."</a></li>";
                }
            }

            return '<nav><ul>'.$start_page.$back.$pages_left.'<li><a>'. $this->current_page.'</a></li>'.$pages_right. $forward. $end_page.'</ul></nav>';

        }
        public function get_link($page):string{
            if($page == 1){
                return rtrim($this->uri, '?&');
            }
            if(str_contains($this->uri, '&')){
                return "{$this->uri}page={$page}";
            }
            else{
                if(str_contains($this->uri, '?')){
                    return "{$this->uri}page={$page}";
                }
                else{
                    return "{$this->uri}?page={$page}";
                }
            }
        }
    }