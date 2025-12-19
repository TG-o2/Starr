<?php
    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . '/../Model/messages.php';
    class messagescontroller{
        public function addmessages($messages) {
            // Insert with user_id into user_name column (despite confusing naming)
            $sql = "INSERT INTO messages (post_id, content, number_replies, user_name, like_count, created_at) 
                    VALUES ( :post_id, :content, :number_replies, :user_name, :like_count, :created_at)";
            $db = config::getConnexion();
            
            try {
                $query = $db->prepare($sql);
                $query->execute([
                    'post_id' => $messages->getpost_id(),
                    'content' => $messages->getcontent(),
                    'number_replies' => $messages->getnumber_replies(),
                    'user_name' => $messages->getuser_id(),  // Store user_id in user_name column
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
        
        // New method to fetch messages with user details
        public function readmessagesWithUserInfo(){
            $sql="SELECT 
                    m.id,
                    m.post_id,
                    m.content,
                    m.number_replies,
                    m.user_name,
                    m.like_count,
                    m.created_at,
                    u.user_id,
                    u.username,
                    u.fname,
                    u.lname,
                    COALESCE(sp.total_points, 0) as starPoints,
                    u.avatar
                FROM messages m
                LEFT JOIN user u ON m.user_name = u.user_id
                LEFT JOIN STARR_POINTS sp ON u.user_id = sp.starr_id
                ORDER BY m.created_at ASC";
            $db=config::getConnexion();
            try{
                $query=$db->prepare($sql);
                $query->execute();
                return $query->fetchAll();
            } catch (Exception $e){
                echo 'Error: ' . $e->getMessage();
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
                    'user_name' => $messages->getuser_id(),  // Store user_id in user_name column
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