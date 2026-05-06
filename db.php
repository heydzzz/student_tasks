<?php
// JSON File Storage System (replaces MySQL)
$data_file = __DIR__ . '/tasks.json';

class TaskManager
{
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
        if (!file_exists($file)) {
            file_put_contents($file, json_encode([]));
        }
    }

    private function read()
    {
        $content = file_get_contents($this->file);
        return json_decode($content, true) ?: [];
    }

    private function write($data)
    {
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function query($sql)
    {
        // Simple query parser for SELECT, INSERT, UPDATE, DELETE
        $sql = trim($sql);

        if (stripos($sql, 'SELECT') === 0) {
            return $this->handleSelect($sql);
        } elseif (stripos($sql, 'INSERT') === 0) {
            return $this->handleInsert($sql);
        } elseif (stripos($sql, 'UPDATE') === 0) {
            return $this->handleUpdate($sql);
        } elseif (stripos($sql, 'DELETE') === 0) {
            return $this->handleDelete($sql);
        }
        return false;
    }

    public function prepare($sql)
    {
        return new PreparedStatement($this->file, $sql);
    }

    private function handleSelect($sql)
    {
        $tasks = $this->read();

        // Handle ORDER BY
        if (preg_match('/ORDER\s+BY\s+(\w+)\s+(DESC|ASC)?/i', $sql, $matches)) {
            $orderBy = $matches[1];
            $order = isset($matches[2]) && strtoupper($matches[2]) === 'DESC' ? SORT_DESC : SORT_ASC;

            if ($orderBy === 'id') {
                usort($tasks, function ($a, $b) use ($order) {
                    return $order === SORT_DESC ? $b['id'] - $a['id'] : $a['id'] - $b['id'];
                });
            }
        }

        return new QueryResult($tasks);
    }

    private function handleInsert($sql)
    {
        preg_match('/INSERT\s+INTO\s+tasks\s*\((.*?)\)\s*VALUES\s*\((.*?)\)/i', $sql, $matches);
        if (!$matches) return false;

        $fields = array_map('trim', explode(',', $matches[1]));
        $values = array_map('trim', explode(',', $matches[2]));

        $tasks = $this->read();
        $new_id = count($tasks) > 0 ? max(array_column($tasks, 'id')) + 1 : 1;

        $new_task = ['id' => $new_id];
        foreach ($fields as $i => $field) {
            $value = trim($values[$i], "'\"");
            $new_task[$field] = $value;
        }

        $tasks[] = $new_task;
        $this->write($tasks);
        return true;
    }

    private function handleUpdate($sql)
    {
        preg_match('/UPDATE\s+tasks\s+SET\s+(.*?)\s+WHERE\s+id\s*=\s*(\d+)/i', $sql, $matches);
        if (!$matches) return false;

        $updates = $matches[1];
        $id = intval($matches[2]);

        $tasks = $this->read();
        foreach ($tasks as &$task) {
            if ($task['id'] == $id) {
                preg_match_all('/(\w+)\s*=\s*[\'"]?([^\',]+)[\'"]?(?:,|$)/i', $updates, $pairs);
                foreach ($pairs[1] as $i => $field) {
                    $task[$field] = trim($pairs[2][$i], "'\"");
                }
                break;
            }
        }
        $this->write($tasks);
        return true;
    }

    private function handleDelete($sql)
    {
        preg_match('/DELETE\s+FROM\s+tasks\s+WHERE\s+id\s*=\s*(\d+)/i', $sql, $matches);
        if (!$matches) return false;

        $id = intval($matches[1]);
        $tasks = $this->read();
        $tasks = array_filter($tasks, function ($task) use ($id) {
            return $task['id'] != $id;
        });
        $this->write(array_values($tasks));
        return true;
    }
}

class QueryResult
{
    public $data;
    public $num_rows;
    public $error = false;

    public function __construct($data)
    {
        $this->data = $data;
        $this->num_rows = count($data);
    }

    public function fetch_assoc()
    {
        if (empty($this->data)) return null;
        return array_shift($this->data);
    }
}

class PreparedStatement
{
    private $file;
    private $sql;
    private $params = [];

    public function __construct($file, $sql)
    {
        $this->file = $file;
        $this->sql = $sql;
    }

    public function bind_param($types, &...$params)
    {
        $this->params = $params;
        return true;
    }

    public function execute()
    {
        $sql = $this->sql;
        foreach ($this->params as $param) {
            $sql = preg_replace('/\?/', "'" . addslashes($param) . "'", $sql, 1);
        }
        $manager = new TaskManager($this->file);
        return $manager->query($sql);
    }

    public function get_result()
    {
        $sql = $this->sql;
        foreach ($this->params as $param) {
            $sql = preg_replace('/\?/', "'" . addslashes($param) . "'", $sql, 1);
        }
        $manager = new TaskManager($this->file);
        return $manager->query($sql);
    }
}

// Initialize task manager
$conn = new TaskManager($data_file);
