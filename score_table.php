<?php 
include('./connect/connect.php');
session_start();
$categories = $_POST['id'];
$stmt = $conn->prepare("SELECT * FROM update_data WHERE direction =?");
$stmt->bind_param('s',$categories);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// echo mysqli_num_rows($result);

// echo "<table>
// <tr>
// <th>專題名稱</th>
// <th>組別</th>
// </tr>";

while($row = mysqli_fetch_array($result)) {
	echo "<form>";
    echo "<tr>";
    echo "<td>" . $row['file_name'] . "</td>";
   	echo "<td class='td-center'>" . $row['team'] . "</td>";
   	echo "<td class='td-center'><input type=checkbox></td>";
   	echo "<button type=submit>評分成績</button> ";
    echo "</tr>";
    echo "</form>";
}
echo "</table>";


$sql = "SELECT * FROM update_data WHERE direction = '$categories'";
$result = mysqli_query($conn, $sql) or die("取出資料失敗！".mysqli_error($conn));
$res = "";//把準備回傳的變數res準備好
while($data=mysqli_fetch_assoc($result)){
   $res .="<tr>";
    "<td>" . $row['file_name'] . "</td>";
   	"<td class='td-center'>" . $row['team'] . "</td>";
    "</tr>";;//將對應的型號項目遞迴列出
};
echo $res;//將型號項目丟回給ajax
?>