<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    session_start();

    if(!isset($_SESSION['settings'])){
        $_SESSION['setting'] = [
            'level' => 1,
            'operator' => 'addition',
            'num_items' => 5,
            'max_diff' => 10
        ];
    }
    ?>
</body>
</html>