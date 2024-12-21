<?php

define("TASKS_FILE", "tasks.json");


function loadTasks(): array
{
    if (!file_exists(TASKS_FILE)) {
        return [];
    }

    $data = file_get_contents(TASKS_FILE);

    return $data ? json_decode($data, true) : [];
}

$tasks = loadTasks();
// print_r($tasks);

function saveTask(array $tasks): void
{
    file_put_contents(TASKS_FILE, json_encode($tasks, JSON_PRETTY_PRINT));
}

// print_r($_SERVER);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task']) && !empty(trim($_POST['task']))) {
        $tasks[] = [
            'task' => htmlspecialchars(trim($_POST['task'])),
            'done' => false
        ];
        saveTask($tasks);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } elseif (isset($_POST['delete'])) {
        unset($tasks[$_POST['delete']]);
        $tasks = array_values($tasks);
        saveTask($tasks);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } elseif (isset($_POST['toggle'])) {
        $tasks[$_POST['toggle']]['done'] = !$tasks[$_POST['toggle']]['done'];
        saveTask($tasks);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="w-75 mx-auto my-4 shadow p-4 mb-5 bg-white rounded">
            <h1 class="text-success-emphasis">To Do App</h1>
            <form method="POST">
                <div class="input-group mb-3">
                    <input type="text" name="task" class="form-control" placeholder="Entry a new task" required aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <button type="submit" class="input-group-text btn btn-primary" id="basic-addon2">Add Task</button>
                </div>
            </form>
            <div class="mt-4">
                <h2>Task List</h2>
                <ul>
                    <?php if (empty($tasks)): ?>
                        <li class="list-group-item">No tasks yet. Add one above! </li>
                    <?php else: ?>
                        <?php foreach ($tasks as $index => $task): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2">
                                <form method="POST">
                                    <input type="hidden" name="toggle" value="<?= $index ?>">
                                    <button type="submit" class="btn">
                                        <span class="task <?= $task['done'] ? 'text-decoration-line-through' : '' ?>">
                                            <?= htmlspecialchars($task['task']) ?>
                                        </span>
                                    </button>
                                </form>
                                <form method="POST">
                                    <input type="hidden" name="delete" value="<?= $index ?>">
                                    <button class="btn btn btn-outline-danger">Delete</button>
                                </form>
                            </li>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>