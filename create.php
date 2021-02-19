<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $id       = isset($_POST['id']) && !empty($_POST['id'])  ? $_POST['id'] : NULL;
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $name     = isset($_POST['name'])     ? $_POST['name']    : '';
    $email    = isset($_POST['email'])    ? $_POST['email']   : '';
    $title    = isset($_POST['title'])    ? $_POST['title']   : '';
    $created  = isset($_POST['created'])  ? $_POST['created'] : date('Y-m-d H:i:s');
    $phone1   = isset($_POST['phone1'])   ? $_POST['phone1']  : '';
    $phone2   = isset($_POST['phone2'])   ? $_POST['phone2']  : '';

    $id_phone = $_POST['id'];

    // Insert new record into the contacts table
    $stmt = $pdo->prepare('INSERT INTO contacts VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$id, $name, $email,  $title, $created]);

    // Insert new record into the phones table
    $phn = $pdo->prepare('INSERT INTO phones (`id`, `phone1`, `phone2`) VALUES (?, ?, ?)');
    $phn->execute([$id_phone, $phone1, $phone2]);

    // Output message
    $msg = 'Created Successfully!';
}
?>
<?=template_header('Create')?>

<div class="content update">
    <h2>Create Contact</h2>
    <form action="create.php" method="POST">
        <label for="id">ID</label>
        <label for="name">Name</label>
        <input type="text" name="id" placeholder="enter id " value="" id="id">
        <input type="text" name="name" placeholder="first name last name" id="name">
        <label for="email">Email</label>
        <label for="phone1">Phone1</label>
        <input type="text" name="email" placeholder="example@example.com" id="email">
        <input type="text" name="phone1" placeholder="**********" id="phone1">
        <label for="title">Title</label>
        <label for="created">Created</label>
        <input type="text" name="title" placeholder="who are you ?" id="title">
        <input type="datetime-local" name="created" value="<?=date('Y-m-d\TH:i')?>" id="created">
        <label for="phone2">Phone2</label>
        <input type="text" name="phone2" placeholder="**********" id="phone2">
        <input type="submit" value="Create">
    </form>
    <?php if ($msg): ?>
        <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
