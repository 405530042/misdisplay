<?php
require('./connect/connect.php');
session_start();
define("filesplace", "./");
require('timer.php');
if (is_uploaded_file($_FILES['file']['tmp_name'])) {
    if ($_FILES['file']['type'] != "application/pdf") {
        $_SESSION['error']=6;
        // header("location: ./connect/error.php");
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        window.location ="./connect/error.php";
			    }, 0);
            </script>
<?php
    }
    else {
        if (!isset($_FILES['file']['tmp_name']) || trim($_FILES['file']['tmp_name']) == '') {
            $_SESSION['error']=6;
            // header("location: ./connect/error.php");
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        window.location ="./connect/error.php";
			    }, 0);
            </script>
<?php
        }

        else {
            $direction = htmlspecialchars($_POST['direction']);
            if( trim($direction) == ''||trim($direction) == NULL){
                $_SESSION['error'] = 11; 
                // header("location: ./connect/error.php");
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        window.location ="./connect/error.php";
			    }, 0);
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
                if (mysqli_num_rows($result) != 1) {
                    $_SESSION['error']=7;
                    // header("location: ./connect/error.php");
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        window.location ="./connect/error.php";
			    }, 0);
            </script>
<?php
                }
                else {
                    $name = htmlspecialchars($_POST['name']);
                    $result2 = move_uploaded_file($_FILES['file']['tmp_name'], iconv("utf-8", "big5","update/".$direction."/".$name.".pdf"));
		//move_uploaded_file($_FILES['file']['tmp_name'],"update/".$direction."/".$name.".pdf")
                    if (result2) { 
                        $introduction = htmlspecialchars($_POST['intro']);
                        $stmt = $conn->prepare("SELECT * FROM update_data WHERE file_name = ?");
                        $params = $name;
                        $stmt->bind_param('s', $params);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $stmt->close();
                        $rows = mysqli_num_rows($result);

                        if ($rows == 0) {
                            	$time = date("Y-m-d H:i:s");
                            		$stmt = $conn->prepare("INSERT INTO update_data (direction,file_name,intro,team,time,image) VALUES (?,?,?,?,?,?)");
                        		$stmt->bind_param('ssssss',$direction , $params, $introduction, $_SESSION['team'], $time, $_SESSION['id']);
                        		$stmt->execute();

                            		$check = $conn->prepare("UPDATE member SET update_check = ? WHERE team = ?");
                            		$success = 1;
                            		$check->bind_param('is',$success,$_SESSION['team']);
                            		$check->execute();
					
                            		$check->close();
                            		echo "123:". $stmt->error;
					$stmt->close();
                            		$_SESSION['error'] = 8;
  
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        window.location ="./connect/error.php";
			    }, 0);
            </script>
<?php
                        }
                        else {
                            $_SESSION['error'] = 9;
                            // header("location: ./connect/error.php");
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        window.location ="./connect/error.php";
			    }, 0);
            </script>
<?php
                        }
                    }
                    else {
                        $_SESSION['error']=10;
                        // header("location: ./connect/error.php");
			echo $result2;
?>
			<!-- <script type="text/javascript">
			    setTimeout(() => {
			        window.location ="./connect/error.php";
			    }, 0);
            </script> -->
<?php
                    }
                }
            }
        }
    }
}
?>
