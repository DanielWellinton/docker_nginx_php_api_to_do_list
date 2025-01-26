<?php
require_once 'api.php';

$task = new Task();

$data = json_decode(file_get_contents("php://input"));

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($page - 1) * 5;
        echo json_encode($task->getTasks($page, $offset));
        break;
    case 'POST':
        $title = $data->title;
        $description = $data->description;
        echo json_encode($task->createTask($title, $description));
        break;
    case 'PUT':
        $id = $data->id;
        $title = $data->title;
        $description = $data->description;
        $completed = $data->completed;
        echo json_encode($task->updateTask($id, $title, $description, $completed));
        break;
    case 'DELETE':
        $id = $data->id;
        echo json_encode($task->deleteTask($id));
        break;
}