
    <?php
    session_start();

    if(!isset($_SESSION['settings'])){
        $_SESSION['settings'] = [
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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f3f4f6;
        }

        .container {
            max-width: 600px;
            width: 100%;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 24px;
            color: #333333;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 20px;
            color: #555555;
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin: 10px 0;
            text-align: left;
            font-weight: bold;
        }

        select,
        input[type="number"],
        button {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 5px 0 15px 0;
            font-size: 16px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            display: block;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            font-size: 16px;
            color: #666666;
            margin: 10px 0;
        }

        .score-board {
            margin: 20px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
        }

        .question {
            font-size: 18px;
            color: #333333;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .stats {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin-top: 20px;
        }

        .stats span {
            font-weight: bold;
            color: #444444;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Simple Math Quiz PHP</h1>

        <?php if ($gameOver): ?>
            <h2>Game Over</h2>
            <div class="score-board">
                <p>Score: <?php echo $_SESSION['quiz']['score']; ?></p>
                <p>Correct: <?php echo $_SESSION['quiz']['correct']; ?></p>
                <p>Wrong: <?php echo $_SESSION['quiz']['wrong']; ?></p>
            </div>
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
                    </label>

                    <label>Operator:
                        <select name="operator">
                            <option value="addition">Addition</option>
                            <option value="subtraction">Subtraction</option>
                            <option value="multiplication">Multiplication</option>
                        </select>
                    </label>

                    <label>Number of Items:</label>
                    <input type="number" name="num_items" value="5" min="1" max="20">

                    <label>Max Difference of Choices:</label>
                    <input type="number" name="max_diff" value="10" min="1" max="50">

                    <button type="submit" name="start_quiz">Start Quiz</button>
                </form>
            <?php else: ?>
                <h2>Question</h2>
                <div class="question">
                    <?php $current = $_SESSION['quiz']['problems'][0]; ?>
                    <?php echo "{$current[0]} {$current[1]} {$current[2]} = ?"; ?>
                </div>
                <form method="post">
                    <input type="number" name="answer" placeholder="Enter your answer" required>
                    <button type="submit">Submit</button>
                </form>
                <div class="stats">
                    <span>Score: <?php echo $_SESSION['quiz']['score']; ?></span>
                    <span>Correct: <?php echo $_SESSION['quiz']['correct']; ?></span>
                    <span>Wrong: <?php echo $_SESSION['quiz']['wrong']; ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
