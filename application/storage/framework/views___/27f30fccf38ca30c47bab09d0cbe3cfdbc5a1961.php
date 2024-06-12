<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Suppor Ticket Status</title>
</head>
<body>
    <p>
        Hello <?php echo e(ucfirst($ticketOwner->name)); ?>,
    </p>
    <p>
        Your support ticket with ID #<?php echo e($ticket->ticket_id); ?> has been marked has resolved and closed.
    </p>
</body>
</html>