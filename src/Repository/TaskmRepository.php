<?php

namespace App\Repository;

use App\Entity\Taskm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @extends ServiceEntityRepository<Taskm>
 *
 * @method Taskm|null insertTask(String:$title, String:$description, Date:$dueDate)
 * @method Taskm|null updateTask(Integer:$taskId, String:$title, String:$description, Date:$dueDate)
 * @method Taskm|null deleteTask(Integer:$taskId)
 * @method Taskm|nul  getAllTasks()
 */
class TaskmRepository extends ServiceEntityRepository
{

    private $validator;

    /**
     * Undocumented function
     *
     * @param ManagerRegistry $registry
     * @param ValidatorInterface $validator
     */
    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator)
    {
        parent::__construct($registry, Taskm::class);
        $this->validator = $validator;
    }

    /**
     * Undocumented function
     *
     * @param [type] $title
     * @param [type] $description
     * @param [type] $dueDate
     * @return void
     */
    public function insertTask($title, $description, $dueDate)
    {
        $task = new Taskm();
        $task->setTitle($title);
        $task->setDescription($description);
        $task->setDueDate(new \DateTime($dueDate));

        $violations = $this->validator->validate($task);

        if ($violations->count() > 0) {

            throw new \Exception((string) $violations);
        }

        $entityManager = $this->getEntityManager();
        $entityManager->persist($task);
        $entityManager->flush();

        return $task;
    }

    /**
     * Undocumented function
     *
     * @param [type] $taskId
     * @param [type] $title
     * @param [type] $description
     * @param [type] $dueDate
     * @return void
     */
    public function updateTask($taskId, $title, $description, $dueDate)
    {
        $task = $this->find($taskId);

        if (!$task) {
            return null;
        }

        $task->setTitle($title);
        $task->setDescription($description);
        $task->setDueDate(new \DateTime($dueDate));

        $violations = $this->validator->validate($task);

        if ($violations->count() > 0) {

            throw new \Exception((string) $violations);
        }

        $entityManager = $this->getEntityManager();
        $entityManager->flush();

        return $task;
    }

    /**
     * Undocumented function
     *
     * @param [type] $taskId
     * @return void
     */
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

    /**
     * Undocumented function
     *
     * @param Taskm $task
     * @return array
     */
    private function formatDueDate(Taskm $task): array
    {
        return [
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'dueDate' => $task->getDueDate()->format('d.m.y'), // Format the date as dd/mm/yyyy
        ];
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getAllTasks()
    {
        $tasks = $this->findAll();

        return array_map([$this, 'formatDueDate'], $tasks);
    }
}
