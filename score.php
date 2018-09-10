<?php 

include('./connect/connect.php');

require('./template/header.php');

require('./template/nav.php');

require('./connect/function.php');

if ($_SESSION['user_id']!= 1) {

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
?>



<div class="pre-container">
    <div class="msg-container">
        <header> 黏仔雲端 </header>

        <div class="page-hint">
            <div> 首頁 > 評分成績 </div>
            <div></div>
        </div>
            
        <div class="hr"></div>

        <div class="profile-area">
            <header>
                <span>
                    資料夾
                </span>
            </header>

            <ul>
                <!--<li class="profile-box">-->
                <!--    <a href="index.php?article_id=1">-->
                <!--        <div class="cover" style="background-image: url('./update/img/0822測試2/61.jpg');"></div>-->
                <!--    </a>                    -->
                <!--    <div class="title">-->
                <!--        <span> lanbo30678 </span>-->
                <!--    </div>-->
                <!--</li>-->
        <!--    </ul>-->
        <!--</div>-->
            
	<!--<div id="direction">-->
	<!--	<h1>選擇項目評分:</h1>-->
<?php 
    $stmt = $conn->prepare("SELECT * FROM direction WHERE status = 0");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
	for ($i = 0; $i < mysqli_num_rows($result); $i++) {
   		$row = mysqli_fetch_assoc($result);
?>
            
                <li class="profile-box">
                    <a onclick="li2(`<?php echo $row['dir_name'] ?>`)">
                        <div class="cover" style="background-image: url('./img/FILE.png');"></div>
                    </a>                    
                    <div class="title">
                        <span> <?php echo $row['dir_name'] ?> </span>
                    </div>
                </li>
                
   			<!--<button onclick="li2(`<?php echo $row['dir_name'] ?>`)"><?php echo $row['dir_name'] ?> </button>-->
<?php
    }
?>
            </ul>
        </div>
    </div>
    
    <div class="hr"></div>
    
    <div class="container">
        <div class="info-box">
            <div class="info-title"> 檔案列表 </div>

            <div class="info-content">
                <div class="info-content-table">
                    <table>
                        <thead>
                            <tr>
                                <th> 專題名稱 </th>
                                <th> 組別 </th>
                                <th></th>
                            </tr>
                        </thead>
                        
                        <tbody id="data">
                            <tr>
                                <td class="td-center">
                                    尚未選擇檔案
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.js"
 integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous">
  </script>
  <script>
  	// 'use strict';
 li2 = function(id) {
   $.ajax({
      url:"score_table.php",
      method:"POST",
      data:{
         id: id
      },
      success:function(res) {
          $('#data').html(res);
      }
   })//end ajax
}

 function aaaa() {
 	alert();
 }
  </script>

<!--    </div>-->
<!--</div>-->
<?php
}
require('./template/footer.php');
?>