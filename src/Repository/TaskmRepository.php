<?php

namespace App\Repository;

use App\Entity\Taskm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
/**
 * @extends ServiceEntityRepository<Taskm>
 *
 * @method Taskm|null find($id, $lockMode = null, $lockVersion = null)
 * @method Taskm|null findOneBy(array $criteria, array $orderBy = null)
 * @method Taskm[]    findAll()
 * @method Taskm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Taskm::class);
    }

    public function insertTask($title, $description, $dueDate)
    {
        $task = new Taskm();
        $task->setTitle($title);
        $task->setDescription($description);
        $task->setDueDate(new \DateTime($dueDate));
        
        $entityManager = $this->getEntityManager();
        $entityManager->persist($task);
        $entityManager->flush();

        return $task;
    }

      public function updateTask($taskId, $title, $description, $dueDate)
    {
        $task = $this->find($taskId);

        if (!$task) {
            return null; // Task not found
        }

        $task->setTitle($title);
        $task->setDescription($description);
        $task->setDueDate(new \DateTime($dueDate));

        $entityManager = $this->getEntityManager();
        $entityManager->flush();

        return $task;
    }

    public function deleteTask($taskId)
    {
        $task = $this->find($taskId);

        if (!$task) {
            return false; // Task not found
        }

        $entityManager = $this->getEntityManager();
        $entityManager->remove($task);
        $entityManager->flush();

        return true; // Task deleted successfully
    }

 

    private function formatDueDate(Taskm $task): array
    {
        return [
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'dueDate' => $task->getDueDate()->format('d/m/Y'), // Format the date as dd/mm/yyyy
        ];
    }

    public function getAllTasks()
    {
        $tasks = $this->findAll();

        // Format due dates before returning
        return array_map([$this, 'formatDueDate'], $tasks);
    }
}