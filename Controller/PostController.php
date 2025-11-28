<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Post and Comments/config.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/Post and Comments/Model/Post.php';
    class postcontroller{
        public function addpost($post) {
            $sql = "INSERT INTO posts (id, subject, content, number_messages, user_name, category, view_count, like_count, created_at) 
                    VALUES ( :id, :subjects, :content, :number_messages, :user_name, :category, :view_count, :like_count, :created_at)";
            $db = config::getConnexion();
            
            try {
                $query = $db->prepare($sql);
                $query->execute([
                    'id' => $post->getid(),
                    'subjects' => $post->getsubjects(),
                    'content' => $post->getcontent(),
                    'number_messages' => $post->getnumber_messages(),
                    'user_name' => $post->getuser_name(),
                    'category' => $post->getcategory(),
                    'view_count' => $post->getview_count(),
                    'like_count' => $post->getlike_count(),
                    'created_at' => $post->getcreated_at()
                ]);
                return true;
            } catch (Exception $e) {
                echo 'Error: '. $e->getMessage();
                return false;
            }
        }
        public function readposts(){
            $sql="SELECT * FROM posts";
            $db=config::getConnexion();
            try{
                $query=$db->prepare($sql);
                $query->execute();
                return $query->fetchAll();
            } catch (Exception $e){
                echo 'Error' . $e->getMessage();
            }
        }
        public function updatepost($post, $id) {
            try {
                $db = config::getConnexion();
                $query = $db->prepare(
                    'UPDATE posts SET
                    subject = :subject,
                    content = :content,
                    number_messages = :number_messages,
                    user_name = :user_name,
                    category = :category,
                    view_count = :view_count,
                    like_count = :like_count,
                    created_at = :created_at
                    WHERE id = :id'
                );
                $query->execute([
                    'id' => $id,
                    'subject' => $post->getsubjects(),
                    'content' => $post->getcontent(),
                    'number_messages' => $post->getnumber_messages(),
                    'user_name' => $post->getuser_name(),
                    'category' => $post->getcategory(),
                    'view_count' => $post->getview_count(),
                    'like_count' => $post->getlike_count(),
                    'created_at' => $post->getcreated_at()
                ]);
                echo $query->rowCount() . " record(s) UPDATED successfully <br>";
            } catch (PDOException $e) {
                echo 'Error: '. $e->getMessage();
            }
        }
        public function deletepost($id){
            try{
                $db=config::getConnexion();
                $query=$db->prepare('DELETE FROM posts WHERE id= :id');
                $query->execute(['id' => $id]);
                echo $query->rowCount() . "record DELETED successfully <br>";
            } catch (PDOException $e){
                echo 'Error: ' . $e->getMessage();
            }
        }
    
    }
?>