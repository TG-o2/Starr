<?php
    class post{
        private $id;
        private $subjects;
        private $content;
        private $number_messages;
        private $user_name;
        private $category;
        private $view_count;
        private $like_count;
        private $created_at;
        public function __construct($id,$sub,$con,$nb_mes,$user,$cate,$vc,$lc,$ca){
            $this->id=$id;
            $this->subjects=$sub;
            $this->content=$con;
            $this->number_messages=$nb_mes;
            $this->user_name=$user;
            $this->category=$cate;
            $this->view_count=$vc;
            $this->like_count=$lc;
            $this->created_at=$ca;
        }
        public function getid(){
            return $this->id;
        }
        public function setid($aa){
            $this->id=$aa;
        }
        public function getsubjects(){
            return $this->subjects;
        }
        public function setsubjects($aa){
            $this->subjects=$aa;
        }
        public function getcontent(){
             return $this->content;
        }
        public function setcontent($aa){
            $this->content=$aa;
        }
        public function getnumber_messages(){
             return $this->number_messages;
        }
        public function setnumber_messages($aa){
            $this->number_messages=$aa;
        }
        public function getuser_name(){
            return $this->user_name;
        }
        public function setuser_name($aa){
            $this->user_name=$aa;
        }
        public function getcategory(){
            return $this->category;
        }
        public function setcategory($aa){
            $this->category=$aa;
        }
        public function getview_count(){
            return $this->view_count;
        }
        public function setview_count($aa){
            $this->view_count=$aa;
        }
        public function getlike_count(){
            return $this->like_count;
        }
        public function setlike_count($aa){
            $this->like_count=$aa;
        }
        public function getcreated_at(){
            return $this->created_at;
        }
        public function setcreated_at($aa){
            $this->created_at=$aa;
        }
    
        
    }
?>