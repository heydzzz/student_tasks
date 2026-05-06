<?php
// JSON-based data storage

class TaskData
{
    private $file = 'tasks.json';

    public function __construct()
    {
        if (!file_exists($this->file)) {
            file_put_contents($this->file, json_encode([]));
        }
    }

    public function getAll()
    {
        $data = file_get_contents($this->file);
        return json_decode($data, true) ?: [];
    }

    public function getById($id)
    {
        $tasks = $this->getAll();
        foreach ($tasks as $task) {
            if ($task['id'] == $id) {
                return $task;
            }
        }
        return null;
    }

    public function add($title, $description, $due_date, $status)
    {
        $tasks = $this->getAll();
        $new_id = count($tasks) > 0 ? max(array_column($tasks, 'id')) + 1 : 1;

        $tasks[] = [
            'id' => $new_id,
            'title' => $title,
            'description' => $description,
            'due_date' => $due_date,
            'status' => $status
        ];

        $this->save($tasks);
    }

    public function update($id, $title, $description, $due_date, $status)
    {
        $tasks = $this->getAll();
        foreach ($tasks as &$task) {
            if ($task['id'] == $id) {
                $task['title'] = $title;
                $task['description'] = $description;
                $task['due_date'] = $due_date;
                $task['status'] = $status;
                break;
            }
        }
        $this->save($tasks);
    }

    public function delete($id)
    {
        $tasks = $this->getAll();
        $tasks = array_filter($tasks, function ($task) use ($id) {
            return $task['id'] != $id;
        });
        $this->save(array_values($tasks));
    }

    private function save($data)
    {
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}

$db = new TaskData();
