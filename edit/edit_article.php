<?php 
include('../connect/connect.php');
include('../connect/function.php');
require('../template/header-in-file.php');
require('../template/nav-in-file.php');
session_start();
if ($_SESSION['user_id'] != 3) {
	echo '權限不足';
// 	header("refresh:2; url=../index.php");
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
    <header> 黏仔雲端 </header>
    <div class="page-hint">
        <div> 首頁 > 隊伍檔案 > 更改簡介 </div>
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
	$teamNo = $_SESSION['team'];
	$stmt = $conn->prepare("SELECT * FROM update_data WHERE team = ?");
	$params = $teamNo;
	$stmt->bind_param('i', $params);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$rows = mysqli_num_rows($result);

	if ($rows === 0) {
		echo "<h3>尚無簡介</h3>";
	}
	else {
		$content = mysqli_fetch_assoc($result);
		$intro = $content['intro'];
?>
        <div class="info-box">
            <div class="info-title"> 更改簡介 </div>

            <div class="info-content">
                <div class="info-content-form">
                	<form action="" method="post">
                	    <div class="form-group">
                	        <label> 簡介 </label>
                		    <textarea name="edited" rows="4"><?php echo $intro ?></textarea>
                		</div>
                		
                		<div class="form-group submit-area">
                    		<button type="submit" name="modify_intro" value="<?php echo $teamNo ?>"> 送出 </button>
                    	</div>
                	</form>
                </div>
            </div>
        </div>
<?php
	}
?>
    </div>
</div>
<?php
}
require('../template/footer.php');
?>
