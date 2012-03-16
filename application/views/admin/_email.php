<?php
/*
Available variables:
$voter['first_name'] - first name of the voter
$voter['last_name'] - last name of the voter
$voter['username'] - username or email of the voter
$password - password of the voter
$pin - pin of the voter
$admin - name of the admin
*/
?>

Hello <?php echo $voter['first_name'] . ' ' . $voter['last_name']; ?>,

The following are your login credentials:
Email: <?php echo $voter['username'] . "\n"; ?>
<?php if ( ! empty($password)): ?>
Password: <?php echo $password . "\n"; ?>
<?php endif; ?>
<?php if ( ! empty($pin)): ?>
PIN: <?php echo $pin . "\n"; ?>
<?php endif; ?>

<?php echo $admin . "\n"; ?>
Halalan Administrator