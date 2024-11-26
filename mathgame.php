
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
            $_SESSION['settings']['level'] = (int)$_POST['level'];
            $_SESSION['settings']['operator'] = $_POST['operator'];
            $_SESSION['settings']['num_items'] = (int)$_POST['num_items'];
            $_SESSION['settings']['max_diff'] = (int)$_POST['max_diff'];
            $_SESSION['quiz']= [
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
        } elseif (isset($_POST['answer'])) {
            if (!empty($_SESSION['quiz']['problems'])) {
                $current = array_shift($_SESSION['quiz']['problems']);
                $userAnswer = (int)$_POST['answer'];
    
                if ($userAnswer === $current[3]) {
                    $_SESSION['quiz']['correct']++;
                    $_SESSION['quiz']['score'] += 10;
                } else {
                    $_SESSION['quiz']['wrong']++;
                }
            }
        } elseif (isset($_POST['restart'])) {
            unset($_SESSION['quiz']);
        }
    }
    
$gameOver = isset($_SESSION['quiz']['problems']) && empty($_SESSION['quiz']['problems']);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Math Game</title>
</head>
<body>
    <h1>Simple Math Quiz PHP</h1>

    <?php if ($gameOver): ?>
    <h2>Game Over</h2>
    <p>Score: <?php echo $_SESSION['quiz']['score']; ?></p>
    <p>Correct: <?php echo $_SESSION['quiz']['correct']; ?></p>
    <p>Wrong: <?php echo $_SESSION['quiz']['wrong']; ?></p>
    <form method="post">
        <button type="submit" name="restart">Restart Quiz</button>
    </form>
<?php else: ?>

    <?php if (!isset($_SESSION['quiz'])): ?>
        <form method="post">
            <h2>Settings</h2>
            <label>Level:
                <select name="level">
                    <option value="1">Level 1 (1-10)</option>
                    <option value="2">Level 2 (11-100)</option>
                </select>
            </label><br>

            <label>Operator:
                <select name="operator">
                    <option value="addition">Addition</option>
                    <option value="subtraction">Subtraction</option>
                    <option value="multiplication">Multiplication</option>
                </select>
            </label><br>

            <label>Number of Items: <input type="number" name="num_items" value="5" min="1" max="20"></label><br>
            <label>Max Difference of Choices: <input type="number" name="max_diff" value="10" min="1" max="50"></label><br>

            <button type="submit" name="start_quiz">Start Quiz</button>
        </form>
    <?php else: ?>
        <h2>Question</h2>
        <?php $current = $_SESSION['quiz']['problems'][0]; ?>
        <p><?php echo "{$current[0]} {$current[1]} {$current[2]} = ?"; ?></p>

        <form method="post">
            <input type="number" name="answer" required>
            <button type="submit">Submit</button>
        </form>

        <p>Score: <?php echo $_SESSION['quiz']['score']; ?></p>
        <p>Correct: <?php echo $_SESSION['quiz']['correct']; ?></p>
        <p>Wrong: <?php echo $_SESSION['quiz']['wrong']; ?></p>
    <?php endif; ?>
<?php endif; ?>
</body>
</html>