<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

if (isset($_GET['id'])) {
    if (!empty($_POST)) {
        $id      = isset($_POST['id']) ? $_POST['id'] : NULL;
        $phone1   = isset($_POST['phone1']) ? $_POST['phone1'] : '';
        $phone2   = isset($_POST['phone2']) ? $_POST['phone2'] : '';

        $phn = $pdo->prepare('UPDATE phones SET id = ?, phone1 = ?, phone2 = ? WHERE id = ?');
        $phn->execute([$id, $phone1, $phone2, $_GET['id']]);
        $msg = 'Updated Successfully!';
    }

    $stmt = $pdo->prepare('SELECT * FROM contacts INNER JOIN phones ON contacts.id=phones.id WHERE contacts.id = ?');
    $stmt->execute([$_GET['id']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Contact doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}
?>
<?=template_header('Add phone')?>

    <div class="content update">
        <h2>Update Phone #<?=$contact['id']?></h2>
        <form action="add_phone.php?id=<?=$contact['id']?>" method="post">
            <label for="id">ID</label>
            <input type="text" name="id" placeholder="1" value="<?=$contact['id']?>" id="id">
            <label for="phone">Phone1</label>
            <label for="phone2">Phone2</label>
            <input type="text" name="phone1" placeholder="**********" value="<?=$contact['phone1']?>" id="phone1">
            <input type="text" name="phone2" placeholder="**********" value="<?=$contact['phone2']?>" id="phone2">
            <input type="submit" value="Update">
        </form>
        <?php if ($msg): ?>
            <p><?=$msg?></p>
        <?php endif; ?>
    </div>

<?=template_footer()?>