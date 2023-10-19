<?php
    include "config.php";

    if(isset($_POST['submit'])){

        if(isset($_FILES['fileToUpload'])){
            $errors = array();

            $file_name = $_FILES['fileToUpload']['name'];
            $file_size = $_FILES['fileToUpload']['size'];
            $file_tmp = $_FILES['fileToUpload']['tmp_name'];
            $file_type = $_FILES['fileToUpload']['type'];

            $file_ext = strtolower(end(explode('.',$file_name)));

            $extensions = array("jpeg","jpg","png");

            if(in_array($file_ext,$extensions) == false){
                $errors[] = "please check this file";
            }

            if($file_size > 2097152){
                $errors[] = "please check file size";
            }

            $new_name = time() . "-" . basename($file_name);
            $target = "upload/" . $new_name;
            $images_name = $new_name;

            if(empty($errors) == true){
                move_uploaded_file($file_tmp,$target);
            }
            else{
                print_r($errors);
                die();
            }
        }

        session_start();

        $title = $_POST['post_title'];
        $description = $_POST['postdesc'];
        $category = $_POST['category'];
        $date = date("d M,Y");
        $auter = $_SESSION['user_id'];

        $sql = "INSERT INTO post (title,description,category,post_date,author,post_img) VALUES ('{$title}','{$description}','{$category}','{$date}','{$auter}','{$images_name}');";
        $sql .= "UPDATE category SET post = post + 1 WHERE category_id = {$category}";

        // echo $sql;
        // die();
        if(mysqli_multi_query($conn,$sql)){
            header("location: {$headername}/admin/post.php");
        }else{
            echo "<div> failed </div>";
        }
    }
?>