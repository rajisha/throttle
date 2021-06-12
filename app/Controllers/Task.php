<?php

namespace App\Controllers;

use App\Models\TaskModel;
use Config\Services;
use CodeIgniter\API\ResponseTrait;

class Task extends BaseController
{
	use ResponseTrait;

	public function index()
	{
		$tasks = (new TaskModel())->findAll();
		return $this->setResponseFormat('json')->respond(['tasks' => $tasks]);
	}

	public function saveTask()
	{
		try {
			if (!$this->request->getPost() || $this->validateTask())
				return $this->setResponseFormat('json')->respond(['error' => "Title is required."]);
			else {
				$throttler = Services::throttler();
				$allowed = $throttler->check('add-task', 2, MINUTE);
				if ($allowed) {
					$result = (new TaskModel())->create($this->request->getPost());
					return $this->setResponseFormat('json')->respond(['message' => 'Successfully saved.', 'result' => $result]);
				} else {
					return $this->setResponseFormat('json')->respond(['message' => "Too many attempts."]);
				}
			}
		} catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	public function validateTask()
	{
		$validation =  \Config\Services::validation();
		$validation->setRules([
			'title' => 'required'
		]);
		if (!$validation->withRequest($this->request)->run()) {
			return true;
		}
	}
}
