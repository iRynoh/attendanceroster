<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_lists_students()
    {
        $this->seed();

        $response = $this->get('/api/students');

        $response->assertOk();
        $response->assertJsonCount(10, 'data'); // 10 students per call, as it's a pagination
    }

    public function test_it_paginate_students()
    {
        $this->seed();

        $response = $this->get('api/students?page=1');
        $firstPage = $response->json('data');

        $response = $this->get('api/students?page=2');
        $secondPage = $response->json('data');

        $this->assertNotEquals($firstPage, $secondPage); // Not the same data
        $this->assertSameSize($firstPage, $secondPage); // But same size of 10
    }

    public function test_it_updates_student_status()
    {
        $student = Student::factory()->create();
        $status = 'present';

        $this->assertNull($student->status);

        $response = $this->patch("api/students/{$student->id}", [
            'status' => $status
        ]);

        $response->assertOk();
        $this->assertEquals($status, $student->fresh()->status);

        $status = 'absent';
        $response = $this->patch("api/students/{$student->id}", [
            'status' => $status
        ]);

        $response->assertOk();
        $this->assertEquals($status, $student->fresh()->status);
    }
}
