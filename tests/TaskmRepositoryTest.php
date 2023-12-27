<?php

namespace App\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskmRepositoryTest extends KernelTestCase
{
    private $entityManager;
    private $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $this->repository = $this->entityManager->getRepository('App\Entity\Taskm');
    }

    public function testInsertTask()
    {
        $task = $this->repository->insertTask('Test Title', 'Test Description', '2023-12-31');
        $this->assertInstanceOf(\App\Entity\Taskm::class, $task);

        // Assert that the task has been saved to the database
        $savedTask = $this->repository->find($task->getId());
        $this->assertInstanceOf(\App\Entity\Taskm::class, $savedTask);
        $this->assertEquals('Test Title', $savedTask->getTitle());
    }

    public function testUpdateTask()
    {
        // Create a task first
        $task = $this->repository->insertTask('Test Title', 'Test Description', '2023-12-31');

        // Update the task
        $updatedTask = $this->repository->updateTask($task->getId(), 'Updated Title', 'Updated Description', '2024-01-01');
        $this->assertInstanceOf(\App\Entity\Taskm::class, $updatedTask);

        // Assert that the task has been updated in the database
        $savedTask = $this->repository->find($task->getId());
        $this->assertInstanceOf(\App\Entity\Taskm::class, $savedTask);
        $this->assertEquals('Updated Title', $savedTask->getTitle());

    }

    public function testDeleteTask()
    {
        // Create a task first
        $task = $this->repository->insertTask('Test Title', 'Test Description', '02.02.2025');

        // Delete the task
        $result = $this->repository->deleteTask($task->getId());
        $this->assertTrue($result);

        // Assert that the task has been deleted from the database
        $deletedTask = $this->repository->find($task->getId());
        $this->assertNull($deletedTask);

    }

    public function testGetAllTasks()
    {
        // Create multiple tasks
        $this->repository->insertTask('Task 1', 'Description 1', '02.02.2025');
        $this->repository->insertTask('Task 2', 'Description 2', '29.03.2024');

        // Retrieve all tasks
        $tasks = $this->repository->getAllTasks();

        // Assert that tasks are retrieved and in the expected format
        $this->assertIsArray($tasks);
        $this->assertCount(2, $tasks);

        foreach ($tasks as $task) {
            $this->assertArrayHasKey('id', $task);
            $this->assertArrayHasKey('title', $task);
            $this->assertArrayHasKey('description', $task);
            $this->assertArrayHasKey('dueDate', $task);
        }
    }
}
