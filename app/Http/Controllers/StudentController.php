<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAvatarRequest;
use App\Models\Student;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\StudentResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Requests\DestroyUserRequest;
use App\Services\Student\StudentService;

class StudentController extends Controller
{
    /**
     * The service instance
     * @var StudentService
     */
    private StudentService $studentService;

    /**
     * Constructor
     */
    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Request $request)
    {
        return $this->studentService->index($request->all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateStudentRequest $request
     * @param Student $student
     *
     * @return JsonResponse
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $data = $request->validated();
        if ($this->studentService->update($student, $data)) {
            return $this->responseUpdateSuccess(['record' => $student->fresh()]);
        } else {
            return $this->responseUpdateFail();
        }
    }

    /**
     * Render properties
     * @return array
     */
    public function properties()
    {
        return [];
    }
}
