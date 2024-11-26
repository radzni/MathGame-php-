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

    function generateProblem($level, $operator) {
        $min = $level === 1 ? 1 : 11;
        $max = $level === 1 ? 10 : 100;
    
        $num1 = rand($min, $max);
        $num2 = rand($min, $max);
    
        switch ($operator) {
            case 'subtraction':
                $answer = $num1 - $num2;
                $symbol = '-';
                break;
            case 'multiplication':
                $answer = $num1 * $num2;
                $symbol = '×';
                break;
            case 'addition':
            default:
                $answer = $num1 + $num2;
                $symbol = '+';
                break;
        }
    
        return [$num1, $symbol, $num2, $answer];
    }
    ?>
</body>
</html>