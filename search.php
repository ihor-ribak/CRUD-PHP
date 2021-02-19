<?php
include 'functions.php';
template_header('Search');
$pdo = pdo_connect_mysql();
?>
    <div class="content search">
        <h2>Search Contacts</h2>
        <form name="search" method="get" action="search.php">
            <input type="text" name="que" placeholder="Search for a contact">
            <input type="submit" value="Search">
        </form>
    </div>
<?php
if (isset($_GET['que'])){
    $que = $_GET['que'];
    $query = "SELECT * FROM contacts INNER JOIN phones ON contacts.id=phones.id WHERE name LIKE :que OR title LIKE :que";
    $que = "%".$que."%";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':que', $que);
    $stmt->execute();
    $jml = $stmt->rowCount();

    if ($jml>0){
        echo "<table style='width: 90%; border-collapse: collapse; margin-left:45px;'>
        <thead style='background-color: #ebeef1; border-bottom: 1px solid #d3dae0;'>
        <tr>
            <td style='padding: 10px; font-weight: bold; color: #767779; font-size: 14px;'>#</td>
            <td style='padding: 10px; font-weight: bold; color: #767779; font-size: 14px;'>Name</td>
            <td style='padding: 10px; font-weight: bold; color: #767779; font-size: 14px;'>Email</td>
            <td style='padding: 10px; font-weight: bold; color: #767779; font-size: 14px;'>Title</td>
            <td style='padding: 10px; font-weight: bold; color: #767779; font-size: 14px;'>Created</td>
            <td style='padding: 10px; font-weight: bold; color: #767779; font-size: 14px;'>Phone1</td>
            <td style='padding: 10px; font-weight: bold; color: #767779; font-size: 14px;'>Phone2</td>
            
            
            <td></td>
        </tr>
        </thead>";
        $no=1;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            echo "<tbody>
                  <tr style='border-bottom: 1px solid #d3dae0; padding: 10px;'>
	 			  <td style='padding: 10px;'>{$id}</td>
	 			  <td style='padding: 10px;'>{$name}</td>
	 			  <td style='padding: 10px;'>{$email}</td>
	 			  <td style='padding: 10px;'>{$title}</td>
	 			  <td style='padding: 10px;'>{$created}</td>
	 			  <td style='padding: 10px;'>{$phone1}</td>
	 			  <td style='padding: 10px;'>{$phone2}</td>
	 			</tr>";
            $no++;
        }
        echo "</tbody></table>";
    } else {
        echo "<p style=' margin-left:47px;'>For this query <b>$_GET[que]</b> no results</p>";
    }
}
?>
<?=template_footer()?>
