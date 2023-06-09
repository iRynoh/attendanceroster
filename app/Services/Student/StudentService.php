<?php

namespace App\Services\Student;

use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class StudentService
{
    /**
     * Get a single resource from the database
     *
     * @param  Student  $student
     *
     * @return StudentResource
     */
    public function get(Student $student)
    {
        return new StudentResource($student);
    }

    /**
     * Get resource index from the database
     * @param $query
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index($data)
    {
        $query = Student::query();
        if (!empty($data['search'])) {
            $query = $query->search($data['search']);
        }
        if (!empty($data['filters'])) {
            $this->filter($query, $data['filters']);
        }
        if (!empty($data['sort_by']) && !empty($data['sort'])) {
            $query = $query->orderBy($data['sort_by'], $data['sort']);
        }

        return StudentResource::collection($query->paginate(10));
    }

    /**
     * Updates resource in the database
     * @param  Student|Model  $user
     * @param  array  $data
     * @return bool
     */
    public function update(Student $user, array $data)
    {
        $data = $this->clean($data);

        return $user->update($data);
    }

    /**
     * Clean the data
     * @param  array  $data
     * @return array
     */
    private function clean(array $data)
    {
        foreach ($data as $i => $row) {
            if ('null' === $row) {
                $data[$i] = null;
            }
        }
        return $data;
    }

    /**
     * Filter resources
     * @return void
     */
    private function filter(Builder &$query, $filters)
    {
        $query->filter($filters);
    }
}
