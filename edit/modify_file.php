<?php 
require('../connect/connect.php');
require('../template/header-in-file.php');
require('../template/nav-in-file.php');
session_start();
if ($_SESSION['user_id'] != 3) {
	echo '權限不足';
// 	header("refresh:2; url=./view.php");
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        window.location ="../index.php";
			    }, 2000);
            </script>
<?php
}
else {
?>

<div class="pre-container">
    <header> 專題系統 </header>
    <div class="page-hint">
        <div> 首頁 > 隊伍檔案 > 重新上傳檔案 </div>
        <div>
            <a href="../profile.php">
                回上一頁
                <img src="../img/back.png" alt="back">
            </a>
        </div>
    </div>
    <div class="hr"></div>
            
    <div class="container">
<?php
	$query = $conn->prepare("SELECT * FROM update_data WHERE team = ?");
    $params = $_SESSION['team'];
    $query->bind_param('s', $params);
    $query->execute();
    $result2 = $query->get_result();
    $query->close();
    $profile = mysqli_fetch_array($result2);
    $file_name = $profile['file_name'];
    $direction = $profile['direction'];
?>
        <div class="info-box">
            <div class="info-title"> 重新上傳檔案 </div>

            <div class="info-content">
                <div class="info-content-form">
                	<form action= "" enctype="multipart/form-data" method="post">
                	    <div class="form-group">
                	        <label> 修改檔名 </label>
                		    <input type="text" name="name" value="<?php echo $file_name ?>" required />
                		</div>
                		
                	    <div class="form-group">
                	        <label> 上傳檔案 </label>
                		    <input type="file" id="file" name="file" accept=".pdf" value="" required />
                		</div>
                		
                		<div class="form-group submit-area">
                    		<button type="submit" name="modify_file2"> 送出 </button>
                    	</div>
                	</form>
                </div>
            </div>
        </div>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['modify_file2'])) {
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            if ($_FILES['file']['type'] != "application/pdf") {
                echo "<p>請上傳PDF格式.</p>";
                // header("refresh:1.5");
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        location.reload();
			    }, 1500);
            </script>
<?php
            }
            else {
                if (!isset($_FILES['file']['tmp_name']) || trim($_FILES['file']['tmp_name']) == '') {
                    echo "<p>請上傳PDF格式.</p>";
                    // header("refresh:1.5");
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        location.reload();
			    }, 1500);
            </script>
<?php
                }
                else {
                    $file_url="../update/$direction/$file_name.pdf";
                    if (file_exists($file_url)) {
                        $new_name = htmlspecialchars($_POST['name']);
                        $result = move_uploaded_file($_FILES['file']['tmp_name'], "../update/$direction/$new_name.pdf");
                        if ($result == 1) {
                            if ($file_name === $new_name) {
                                $time = date("Y-m-d h:i:sa");
                                $team = $profile['team'];
                                $stmt = $conn->prepare("UPDATE update_data SET file_name =? ,time =? WHERE team =?");
                                $params = $name;
                                $stmt->bind_param('sss', $new_name, $time ,$team);
                                $stmt->execute();
                                $stmt->close();
                                $_SESSION['error'] = 5;
                                // header("Location: ../connect/error.php");
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        window.location ="../connect/error.php";
			    }, 0);
            </script>
<?php
                            }
                            else {
                                unlink($file_url);
                                $time = date("Y-m-d h:i:sa");
                                $team=$profile['team'];
                                $stmt = $conn->prepare("UPDATE update_data SET file_name =? ,time =? WHERE team =?");
                                $params = $name;
                                $stmt->bind_param('sss', $new_name, $time ,$team);
                                $stmt->execute();
                                $stmt->close();
                                $_SESSION['error'] = 5;
                                // header("Location: ../connect/error.php");
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        window.location ="../connect/error.php";
			    }, 0);
            </script>
<?php
                            }
                        }
        	            else {
                            echo "上傳失敗請再試一次";
                        }
                    }
                    else {
                        echo "找不到路徑";
                        echo $file_url;
                    }
                }
            }
        }
    }
}
?>
    </div>
</div>
<?php
}
require('../template/footer.php');
?>
