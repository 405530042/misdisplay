<?php 
require('../connect/connect.php');
require('../connect/function.php');
require('../template/header-in-file.php');
require('../template/nav-in-file.php');
header("Content-Type:text/html; charset=utf-8");
session_start();
if ($_SESSION['user_id'] != 3) {
	echo '權限不足';
// 	header("refresh:0.75; url=../index.php");
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        window.location ="../index.php";
			    }, 750);
            </script>
<?php
}
else {
?>
<div class="pre-container">
    <header> 黏仔雲端 </header>
    <div class="page-hint">
        <div> 首頁 > 隊伍檔案 > 更新封面 </div>
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
	$stmt = $conn->prepare("SELECT * FROM direction WHERE status = 1");
	$stmt->execute();
	$result = $stmt->get_result();
	
	$stmt->close();
?>
        <div class="info-box">
            <div class="info-title"> 更新封面（第 <?php echo $_SESSION['team']; ?> 組） </div>

            <div class="info-content">
                <div class="info-content-form">
                	<form action="" enctype="multipart/form-data" method="post">
                	    <div class="form-group">
                	        <label> 選擇資料夾 </label>
                			<select name="direction" id="">
                    			<?php
                    			    if (mysqli_num_rows($result) === 0) {
                    			        echo "<option value='無'> 無 </option>";
                    			    }
                    			    else {
                            		    for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                            				$rows = mysqli_fetch_assoc($result);
                            				$items = $rows['dir_name'];
                            				echo "<option value=$items> $items </option>";
                        			    }
                    			    }
                    			?>
                		    </select>
                		</div>
                		
                	    <div class="form-group">
                	        <label> 上傳檔案 </label>
		                    <input type="file" id="file" name="file" accept="image/*" value="" required />
                		</div>
                		
                		<div class="form-group submit-area">
		                    <!--<input type="submit" name="image" value="送出" />-->
		                    <button type="submit" name="image"> 送出 </button>
                    	</div>
                	</form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if(isset($_POST['image'])){
		if (is_uploaded_file($_FILES['file']['tmp_name'])) {
				  if ($_FILES['file']['type'] != "image/jpeg" && $_FILES['file']['type'] != "image/jpge" && $_FILES['file']['type'] != "image/JPG" && $_FILES['file']['type'] != "image/png") {
				  		  $_SESSION['error']=6;
				  		  $_SESSION['type']= $_FILES['file']['type'];
				  		  ?> 
                <script type="text/javascript"> 
                   window.location ="../connect/error.php";
                </script>
<?php
		}
		else{
			if (!isset($_FILES['file']['tmp_name']) || trim($_FILES['file']['tmp_name']) == '') {
            $_SESSION['error']=6;
            // header("location: ../connect/error.php");
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        window.location ="../connect/error.php";
			    }, 0);
            </script>
<?php
        }
        	else{
        		 $direction = htmlspecialchars($_POST['direction']);
            if( trim($direction) == ''||trim($direction) == NULL){
                $_SESSION['error'] = 11; 
                			  		  ?> 
                <script type="text/javascript"> 
                   window.location ="../connect/error.php";
                </script>
<?php
            }
            else{
            	 $stmt = $conn->prepare("SELECT * FROM direction WHERE dir_name =? AND status = 1");
                $params = $direction;
                $stmt->bind_param('s', $direction);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
                echo $_POST['direction'];
                echo $name = htmlspecialchars($_POST['name']);
                if (mysqli_num_rows($result) != 1) {
                    $_SESSION['error']=7;
                           			  		  ?> 
                <script type="text/javascript"> 
                   window.location ="../connect/error.php";
                </script>
<?php
                }
                  else {
                    $name = $_SESSION['id'];
                    $result = move_uploaded_file($_FILES['file']['tmp_name'], "../update/img/$direction/$name.jpg");

                    if ($result == 1) { 
                    	$stmt = $conn->prepare("UPDATE update_data SET image = ? where id =? ");
                    	$article_id=$_SESSION['article_id'];
		                $params =$name+".jpg";
		                $stmt->bind_param('si', $params,$article_id);
		                $stmt->execute();
		                $result = $stmt->get_result();
		                $stmt->close();
                        $_SESSION['error']=8;
                         			  		  ?> 
                <script type="text/javascript"> 
                   window.location ="../connect/error.php";
                </script>
<?php
                    }
                    else {
                        $_SESSION['error']=20;
?> 
                <script type="text/javascript"> 
                   window.location ="../connect/error.php";
                </script>
<?php
                    }
                }
            }
        	}
		}
	}
}
}
require('../template/footer.php');
?>