<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

// Check if the contact id exists, for example update.php?id=1 will get the contact with the id of 1
if (isset($_GET['id'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, but instead we update a record and not insert
        $id      = isset($_POST['id']) ? $_POST['id'] : NULL;
        $name    = isset($_POST['name']) ? $_POST['name'] : '';
        $email   = isset($_POST['email']) ? $_POST['email'] : '';
//        $phone   = isset($_POST['phone']) ? $_POST['phone'] : '';
        $title   = isset($_POST['title']) ? $_POST['title'] : '';
        $created = isset($_POST['created']) ? $_POST['created'] : date('Y-m-d H:i:s');
        $phone1   = isset($_POST['phone1']) ? $_POST['phone1'] : '';
        $phone2   = isset($_POST['phone2']) ? $_POST['phone2'] : '';

        // Update the record
        $stmt = $pdo->prepare('UPDATE contacts SET id = ?, name = ?, email = ?,  title = ?, created = ? WHERE id = ?');
        $stmt->execute([$id, $name, $email, $title, $created, $_GET['id']]);
        $phn = $pdo->prepare('UPDATE phones SET id = ?, phone1 = ?, phone2 = ? WHERE id = ?');
        $phn->execute([$id, $phone1, $phone2, $_GET['id']]);
        $msg = 'Updated Successfully!';
    }
    // Get the contact from the contacts and phones tables
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
<?=template_header('Read')?>

<div class="content update">
    <h2>Update Contact #<?=$contact['id']?></h2>
    <form action="update.php?id=<?=$contact['id']?>" method="post">
        <label for="id">ID</label>
        <label for="name">Name</label>
        <input type="text" name="id" placeholder="1" value="<?=$contact['id']?>" id="id">
        <input type="text" name="name" placeholder="First name Last name" value="<?=$contact['name']?>" id="name">
        <label for="email">Email</label>
        <label for="phone">Phone1</label>
        <input type="text" name="email" placeholder="example@example.com" value="<?=$contact['email']?>" id="email">
        <input type="text" name="phone1" placeholder="**********" value="<?=$contact['phone1']?>" id="phone1">
        <label for="title">Title</label>
        <label for="created">Created</label>
        <input type="text" name="title" placeholder="Who are you ?" value="<?=$contact['title']?>" id="title">
        <input type="datetime-local" name="created" value="<?=date('Y-m-d\TH:i', strtotime($contact['created']))?>" id="created">
        <label for="phone2">Phone2</label>
        <input type="text" name="phone2" placeholder="**********" value="<?=$contact['phone2']?>" id="phone2">
        <input type="submit" value="Update">
    </form>
    <?php if ($msg): ?>
        <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
