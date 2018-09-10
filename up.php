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
                    $result = move_uploaded_file($_FILES['file']['tmp_name'], "update/$direction/$name.pdf");

                    if ($result == 1) { 
                        $introduction = htmlspecialchars($_POST['intro']);
                        $stmt = $conn->prepare("SELECT * FROM update_data WHERE file_name = ?");
                        $params = $name;
                        $stmt->bind_param('s', $params);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $stmt->close();
                        $rows = mysqli_num_rows($result);

                        if ($rows == 0) {
                            $time = date("Y-m-d h:i:sa");
                            	$stmt = $conn->prepare("INSERT INTO `update_data`(direction,file_name,intro,team,time,image) VALUES (?,?,?,?,?,?)");
                        		$stmt->bind_param('ssssss',$direction ,$params, $introduction, $_SESSION['team'], $time,$_SESSION['id']);
                        		$stmt->execute();
                        		$stmt->close();
                            // $update = $conn->prepare("insert into update_data(direction,file_name,intro,team,time,image) values(?,?,?,?,?,?)");
                            // $params = $name;
                            // $update->bind_param('ssssss',$direction ,$params, $introduction, $_SESSION['team'], $time,$_SESSION['id']);
                            // $update->execute();
                            // $update->close();
                            
                            $check = $conn->prepare("UPDATE member SET update_check = ? WHERE team = ?");
                            $success = 1;
                            $check->bind_param('is',$success,$_SESSION['team']);
                            $check->execute();
                            $check->close();
                            
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
?>
			<script type="text/javascript">
			    setTimeout(() => {
			        window.location ="./connect/error.php";
			    }, 0);
            </script>
<?php
                    }
                }
            }
        }
    }
}
?>
