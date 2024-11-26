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

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['start_quiz'])) {
            $_SESSION['settings']['levevl'] = (int)$_POST['level'];
            $_SESSION['settings']['operator'] = $_POST['operator'];
            $_SESSION['settings']['num_items'] = (int)$_POST['num_items'];
            $_SESSION['settings']['max_diff'] = (int)$_POST['max_diff'];
            $_SESSION['quiz'][
                'problems' => [],
                'score' => 0,
                'correct' => 0,
                'wrong' => 0,
            ];

            for($i = 0; $i < $_SESSION['settings']['num_items']; $i++) {
                $_SESSION['quiz']['problems'][] = generateProblem(
                    $_SESSION['settings']['level'],
                    $_SESSION['settings']['operator']
                );
            }
        } elseif(isset($_POST['answer'])) {
            $current = array_shift($_SESSION['quiz']['problems']);
            $userAnswer = (int)$_POST['answer'];

            if ($userAnswer === $current[3]) {
                $_SESSION['quiz']['correct']++;
                $_SESSION['quiz']['score'] += 10;
        } else {
            $_SESSION['quiz']['wrong']++;
        }
    }
}
    ?>
</body>
</html>