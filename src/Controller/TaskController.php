<?php

namespace App\Controller;

use App\Repository\TaskmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class TaskController extends AbstractController
{

    #[Route('/taskm/insert', name: 'taskm_insert', methods: ['POST'])]
    public function create(Request $request, TaskmRepository $taskRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $task = $taskRepository->insertTask($data['title'], $data['description'], $data['dueDate']);

        return $this->json($task, Response::HTTP_CREATED);
    }

    #[Route('/taskm/update/{id}', name: 'taskm_update', methods: ['PUT'])]
    public function update($id, Request $request, TaskmRepository $taskRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $task = $taskRepository->updateTask($id, $data['title'], $data['description'], $data['dueDate']);

        if (!$task) {
            return $this->json(['error' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($task);
    }

    #[Route('/taskm/delete/{id}', name: 'taskm_delete', methods: ['POST'])]
    public function delete($id, TaskmRepository $taskRepository): Response
    {
        $result = $taskRepository->deleteTask($id);

        if (!$result) {
            return $this->json(['error' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/taskm/list', name: 'taskm_list', methods: ['GET'])]
    public function listAll(TaskmRepository $taskRepository): Response
    {
        $tasks = $taskRepository->getAllTasks();

        return $this->json($tasks);
    }
}
