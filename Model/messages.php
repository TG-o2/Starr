<?php
    class messages{
        private $id;
        private $post_id;
        private $content;
        private $number_replies;
        private $user_name;
        private $like_count;
        private $created_at;
        public function __construct($id,$post_id,$con,$nb_mes,$user,$lc,$ca){
            $this->id=$id;
            $this->post_id=$post_id;
            $this->content=$con;
            $this->number_replies=$nb_mes;
            $this->user_name=$user;
            $this->like_count=$lc;
            $this->created_at=$ca;
        }
        public function getid(){
            return $this->id;
        }
        public function setid($aa){
            $this->id=$aa;
        }
        public function getpost_id(){
            return $this->post_id;
        }
        public function setpost_id($aa){
            $this->post_id=$aa;
        }
        public function getcontent(){
             return $this->content;
        }
        public function setcontent($aa){
            $this->content=$aa;
        }
        public function getnumber_replies(){
             return $this->number_replies;
        }
        public function setnumber_replies($aa){
            $this->number_replies=$aa;
        }
        public function getuser_name(){
            return $this->user_name;
        }
        public function setuser_name($aa){
            $this->user_name=$aa;
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