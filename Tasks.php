<?php

/**
 * Defining the Task Storing File
 */
define("TASKS_FILE", "tasks.json");

/**
 * Task Management App Model
 */


/**
 * Function for Loading All Task
 *
 * @return array
 */
function loadTasks(): array
{
    if(!file_exists(TASKS_FILE)){
        return [];
    }

    $data = file_get_contents(TASKS_FILE);

    return $data ? json_decode($data, true) : [];
}

/**
 * Function for Saving Tasks
 *
 * @param array $tasks
 * @return void
 */
function saveTasks(array $tasks) : void
{
    file_put_contents(TASKS_FILE, json_encode($tasks, JSON_PRETTY_PRINT));
}

/**
 * Functions for Reloading Page
 *
 * @return void
 */
function loadPage() : void
{
	header("Location: {$_SERVER['PHP_SELF']}");
	exit;
}

// Loading All Tasks to Fetch
$tasks = loadTasks();

// App Controller
if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

    if( isset($_POST['task'] ) && !empty( trim($_POST['task'])) ) { 
        $tasks[] = [
            'task' => htmlspecialchars(trim($_POST['task'])),
            'done' => false
        ];

        saveTasks($tasks);
        loadPage();

    } else if ( isset($_POST['delete']) ) {

        unset($tasks[$_POST['delete']]);
        $tasks = array_values($tasks); 
        saveTasks($tasks);
        loadPage();

    } else if ( isset($_POST['toggle']) ) {

        $tasks[$_POST['toggle']]['done'] = !$tasks[$_POST['toggle']]['done'];
        saveTasks($tasks);
        loadPage();

    }
}

?>

<!-- ./ Loading App View -->
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Simple To Do Task Manager App</title>
    <link rel="stylesheet" href="assets/milligram.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

	<header class="app-header">
	    <div class="container">
        	<h1 class="app-heading">To - Do Task Manager</h1>
        </div>
	</header> <!-- ./ app-header -->

	<main class="app-body">
		<div class="container">
			<div class="task-card">
				<div class="task-heading">
					<h2>Task Manager</h2>
				</div>

				<div class="task-form">
					<form method="post">
						<div class="row">
							<div class="column column-75 task-input">
								<input type="text" name="task" placeholder="Enter a new Task..." required>
							</div> <!-- ./ task-input -->

							<div class="column column-25 task-submit">
								<button type="submit" name="addTask" class="button button-primary">
									Add New Task
								</button>
							</div> <!-- ./ task-submit -->
						</div>
					</form>
				</div> <!-- ./ task-form -->

				<div class="task-list">
					<div class="list-heading">
						<h3>Task List</h3>
					</div> <!-- ./ list-heading -->

					<ul class="task-items">
						<?php if ( empty($tasks) ) : ?>
							<li>No Task yet! Add one Above.</li>
						<?php
							else:
								foreach ( $tasks as $key => $task ) : 
						?>
							<li class="task-item">
								<form class="task-item-form" method="post">
									<input type="hidden" name="toggle" value="<?= $key ?>">

									<button type="submit" class="task-item-task">
										<span class="task <?= $task['done'] ? 'task-done' : '' ?>">
											<?= htmlspecialchars($task['task']); ?>
										</span>
									</button>
								</form>

								<form method="post" class="task-delete">
									<input type="hidden" name="delete" value="<?= $key ?>">
									<button class="delete-btn button button-outline" type="submit">Delete</button>
								</form>
							</li>
						<?php
								endforeach;
							endif;
						?>
					</ul> <!-- ./ .list-items (Tasks) -->
				</div> <!-- ./task-list -->

            </div> <!-- ./ task-card -->
		</div> <!-- ./ container -->
	</main>

	<footer class="app-footer">
		<div class="container">
			<p>&copy;<?= date('Y') ?>. Designed and Developed by Jahangir Hossain</p>
		</div>
	</footer>
</body>
</html>