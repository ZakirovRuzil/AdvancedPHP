<?php
namespace App\Service;

use App\Entity\Student;
use App\Entity\Grade;
use Doctrine\ORM\EntityManagerInterface;

class StudentService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllStudents(): array
    {
        $studentRepository = $this->entityManager->getRepository(Student::class);
        return $studentRepository->findAll();
    }

    public function addGradeToStudent(Student $student, int $gradeValue): Grade
    {
        $grade = new Grade();
        $grade->setValue($gradeValue);
        $grade->setStudent($student);

        $this->entityManager->persist($grade);
        $this->entityManager->flush();

        return $grade;
    }

    public function addStudent(string $name): Student
    {
        $student = new Student();
        $student->setName($name);

        $this->entityManager->persist($student);
        $this->entityManager->flush();

        return $student;
    }

    public function deleteStudent(Student $student): void
    {
        $this->entityManager->remove($student);
        $this->entityManager->flush();
    }
}
