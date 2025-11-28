<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Post and Comments/config.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/Post and Comments/Model/messages.php';
    class messagescontroller{
        public function addmessages($messages) {
            $sql = "INSERT INTO messages (id, post_id, content, number_replies, user_name, like_count, created_at) 
                    VALUES ( :id, :post_id, :content, :number_replies, :user_name, :like_count, :created_at)";
            $db = config::getConnexion();
            
            try {
                $query = $db->prepare($sql);
                $query->execute([
                    'id' => $messages->getid(),
                    'post_id' => $messages->getpost_id(),
                    'content' => $messages->getcontent(),
                    'number_replies' => $messages->getnumber_replies(),
                    'user_name' => $messages->getuser_name(),
                    'like_count' => $messages->getlike_count(),
                    'created_at' => $messages->getcreated_at()
                ]);
                return true;
            } catch (Exception $e) {
                echo 'Error: '. $e->getMessage();
                return false;
            }
        }
        public function readmessages(){
            $sql="SELECT * FROM messages";
            $db=config::getConnexion();
            try{
                $query=$db->prepare($sql);
                $query->execute();
                return $query->fetchAll();
            } catch (Exception $e){
                echo 'Error' . $e->getMessage();
            }
        }
        public function updatemessages($messages, $id) {
            try {
                $db = config::getConnexion();
                $query = $db->prepare(
                    'UPDATE messages SET
                    post_id = :post_id,
                    content = :content,
                    number_replies = :number_replies,
                    user_name = :user_name,
                    like_count = :like_count,
                    created_at = :created_at
                    WHERE id = :id'
                );
                $query->execute([
                    'id' => $id,
                    'post_id' => $messages->getpost_id(),
                    'content' => $messages->getcontent(),
                    'number_replies' => $messages->getnumber_replies(),
                    'user_name' => $messages->getuser_name(),
                    'like_count' => $messages->getlike_count(),
                    'created_at' => $messages->getcreated_at()
                ]);
                echo $query->rowCount() . " record(s) UPDATED successfully <br>";
            } catch (PDOException $e) {
                echo 'Error: '. $e->getMessage();
            }
        }
        public function deletemessages($id){
            try{
                $db=config::getConnexion();
                $query=$db->prepare('DELETE FROM messages WHERE id= :id');
                $query->execute(['id' => $id]);
                echo $query->rowCount() . "record DELETED successfully <br>";
            } catch (PDOException $e){
                echo 'Error: ' . $e->getMessage();
            }
        }
    
    }
?>