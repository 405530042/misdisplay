<?php
require("connect.php");
require('prevent.php');

date_default_timezone_set("Asia/Taipei");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['edit'])) {
        $_ARTICLE_ID = htmlspecialchars($_POST['edit']);
?> 
<script type="text/javascript">
    window.location =" ./edit/edit_article.php";
</script>
<?php
    }
    else if (isset($_POST['image'])) {
        $_ARTICLE_ID = htmlspecialchars($_POST['edit']);
?> 
<script type="text/javascript"> 
   window.location ="../edit/edit_image.php";
</script>
<?php
}
// 編輯成功
    else if (isset($_POST['modify_intro'])) {
        $teamNo = htmlspecialchars($_POST['modify_intro']);
        $intro = htmlspecialchars($_POST['edited']);
        $stmt = $conn->prepare("UPDATE update_data SET intro = ? WHERE team = ?");
        $stmt->bind_param('si', $intro, $teamNo);
        $stmt->execute();
        $stmt->close();
        $edit_success = 1;
        echo '<p>修改成功' . $teamNo . '</p>';
        // header("refresh:0.75;url=../profile.php");
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        window.location ="../profile.php";
			    }, 750);
            </script>
<?php
    }
    else if (isset($_POST['modify_file'])) {
        // header("location: ../edit/modify_file.php");
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        window.location ="../edit/modify_file.php";
			    }, 0);
            </script>
<?php
    }
    else if(isset($_POST['change_password'])){
        if(empty($_POST['old'])||empty($_POST['new'])||empty($_POST['check'])){
            $errorchange="欄位為空";
        }
        else if(htmlspecialchars($_POST['new'])==htmlspecialchars($_POST['check'])){
            if (strlen(trim(htmlspecialchars($_POST['new']))) > 12) {
            $_SESSION['error']=12;
            // header("Location: ./connect/error.php");
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        window.location ="./error.php";
			    }, 0);
            </script>
<?php
            }
            else {
                $pd ='a'. htmlspecialchars($_POST['old']);
                $password ='a'. htmlspecialchars($_POST['new']);
                $change_old = 'a'. hash('md5',hash('sha256',$pd));
                $_SESSION['pd']=$change_old ;
                $encrypt ='a'. hash('md5',hash('sha256',$password));
                $user_id = $row['id'];
                $old_pd = $row['password'];
                $_SESSION['old_pd'] = $row['password'];
                if ($old_pd == $change_old) {
                    $stmt = $conn->prepare("UPDATE member SET password = ? WHERE id = ?");
                    $stmt->bind_param('si', $encrypt, $user_id);
                    $stmt->execute();
                    $stmt->close();
                    $_SESSION['error']=2;
                    // header("Location: ./connect/error.php");
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        window.location ="./error.php";
			    }, 0);
            </script>
<?php
                }
                else {
                    $_SESSION['error']=3;
                    //  header("Location: ./connect/error.php");
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        window.location ="./error.php";
			    }, 0);
            </script>
<?php
                }
            }
        }
        else {
            $_SESSION['error']=4;
            // header("Location: ./connect/error.php");
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        window.location ="./error.php";
			    }, 0);
            </script>
<?php
        }
    }
}
?>
