<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table = 'task';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['title', 'task_summary'];

    public function create($task)
    {
        try {
            if (isset($task)) {
                $id = $this->insert($task);
                return $this->find($id);
            }
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}
