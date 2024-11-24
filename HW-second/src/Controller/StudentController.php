<?php
namespace App\Controller;

use App\Entity\Student;
use App\Service\StudentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    private StudentService $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    #[Route('/students', name: 'students_list')]
    public function list(): Response
    {
        $students = $this->studentService->getAllStudents();

        return $this->render('students/list.html.twig', [
            'students' => $students,
        ]);
    }

    #[Route('/students/add', name: 'add_student', methods: ['POST'])]
    public function addStudent(Request $request): Response
    {
        $name = $request->request->get('name');
        if ($name) {
            $this->studentService->addStudent($name);
        }

        return $this->redirectToRoute('students_list');
    }

    #[Route('/students/{id}/add-grade', name: 'add_grade', methods: ['POST'])]
    public function addGrade(Student $student, Request $request): Response
    {
        $gradeValue = (int) $request->request->get('grade');
        if ($gradeValue) {
            $this->studentService->addGradeToStudent($student, $gradeValue);
        }

        return $this->redirectToRoute('students_list');
    }

    #[Route('/students/{id}/delete', name: 'delete_student', methods: ['POST'])]
    public function deleteStudent(Student $student): Response
    {
        $this->studentService->deleteStudent($student);
        return $this->redirectToRoute('students_list');
    }
}
