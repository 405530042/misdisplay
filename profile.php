<?php 
include('./connect/connect.php');
require('./template/header.php');
require('./template/nav.php');
include('./connect/function.php');
if ($_SESSION['user_id'] != 3 && $_SESSION['user_id']!= 2) {
    echo '權限不足';
    // header("refresh:2; url=./index.php");
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        window.location ="./index.php";
			    }, 2000);
            </script>
<?php
}
else {
    $query_teamname = $conn->prepare("SELECT * FROM member WHERE team = ?");
    $params = $_SESSION['team'];
    $query_teamname->bind_param('s', $params);
    $query_teamname->execute();
    $result = $query_teamname->get_result();
    $query_teamname->close();

    $query = $conn->prepare("SELECT * FROM update_data WHERE team = ?");
    $params = $_SESSION['team'];
    $query->bind_param('s',$params);
    $query->execute();
    $result2 = $query->get_result();
    $query->close();

    $team = mysqli_fetch_array($result);
    $rows = mysqli_num_rows($result2);
    $has_data = false;
    $update_time = '無';

    if ($rows == 0) {
    $query = $conn->prepare("UPDATE member SET update_check = 0  WHERE team = ?");
    $params = $_SESSION['team'];
    $query->bind_param('s',$params);
    $query->execute();
    $query->close();
        $has_data = false;
        $update_time = '無';
    }
    else {
        $query = $conn->prepare("UPDATE member SET update_check = 1 WHERE team = ?");
        $params = $_SESSION['team'];
        $query->bind_param('s',$params);
        $query->execute();
        $query->close();
        $has_data = true;
        $profile = mysqli_fetch_array($result2);
        $update_time = $profile['time'];
        $direction = $profile['direction'];  
        $_SESSION['article_id'] = $profile['id'];
    }
?>
<div class="pre-container">
    <header> 黏仔雲端 </header>
    <div class="page-hint">
        <div> 首頁 > 隊伍檔案 </div>
        <div>
            <a href="./index.php">
                回上一頁
                <img src="./img/back.png" alt="back">
            </a>
        </div>
    </div>
    <div class="hr"></div>
<?php
    if (!$has_data) {
        echo '<h1><center> 尚無檔案上傳。 </center></h1>';
    }
    else {
?>
    <div class="container">
        <div class="info-box">
            <div class="info-title"> 隊伍檔案 </div>

            <div class="info-content">
                <div class="info-content-form">
                    
                    <div class="profile-header">
                        <h1 class="team-name">
    
                           組別：<?php echo $team['team']; ?>
                        </h1>
                        <h5 class="update-date">
                            <?php echo '#最後修改日期：' . $update_time; ?>
                        </h5>
                    </div>
    
                    <div class="hr"></div>
                    
                    <form action="" method="POST">
                        <div class="form-group">
                            <label> 專題名稱 </label>
                            <label class="detail">
                                <?php echo $profile['file_name']; ?>
                            </label>
                        </div>

                        <div class="form-group">
                            <label> 專題簡介 </label>
                            <label class="detail">
                                <?php echo $profile['intro']; ?>
                            </label>
                        </div>
                        <div class="form-group">
                            <label > 專題瀏覽 </label>
                            <label class="detail pdf-file">
                                <iframe style="pointer-events: none; user-select: none;" type="application/pdf" name="myiframe" id="myiframe" src="update/<?php echo $direction.'/'.$profile['file_name'] . '.pdf' ;?>"></iframe>
                            </label>
                        </div>
                        
<?php if ($_SESSION['user_id'] == 3) { ?> 
                        <div class="form-group submit-area">
                            <button type="submit" name="edit" value="<?php echo $profile['id'] ?>">
                                更改簡介
                            </button>
                            <button type="submit" name="image" value="<?php echo $profile['id'] ?>">
                                修改封面圖片
                            </button>
                    		<button type="submit" name="modify_file" value="<?php echo $profile['id'] ?>">
                    		    重新上傳檔案
                    	    </button>
                        </div>
<?php } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div>

<?php
}

require('./template/footer.php');
?>
