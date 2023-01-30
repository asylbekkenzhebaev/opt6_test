<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    protected $user;
    protected $company;


    /**
     * @group company_test
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->company = Company::factory()->create();

        $this->employee = Employee::factory()->create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'company_id' => $this->company->id,
            'user_id' => $this->user->id,
        ]);

    }

    /**
     * for an authorized user
     * @group employee_test
     * @return void
     */
    public function testCreateFormForAuthUser()
    {
        $this->actingAs($this->user);
        $response = $this->get(route('employees.create'));
        $response->assertOK()
            ->assertSeeText('Create a new employee');
    }

    /**
     * for an authorized user
     * @group employee_test
     * @return void
     */
    public function testStoreForAuthUser()
    {
        $user = $this->user;
        $company = $this->company;
        $this->actingAs($user);

        $employeeData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'company_id' => $company->id,
            'user_id' => $user->id,
        ];

        $response = $this->post(route('employees.store'), $employeeData);
        $response->assertSessionDoesntHaveErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('employees', $employeeData);
    }


    /**
     * for an authorized user
     * @group employee_test
     * @return void
     */
    public function testEditFormForAuthUser()
    {
        $user = $this->user;
        $employee = $this->employee;
        $this->actingAs($user);

        $response = $this->get(route('employees.edit', $employee));
        $response->assertOk()
            ->assertSeeText('Edit a employee');
    }

    /**
     * for an authorized user
     * @group employee_test
     * @return void
     */
    public function testUpdateForAuthUser()
    {
        $employee = $this->employee;
        $company = $this->company;
        $user = $this->user;
        $this->actingAs($this->user);

        $employeeData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'company_id' => $company->id,
            'user_id' => $user->id,
        ];

        $response = $this->put(
            route('employees.update', ['employee' => $employee]),
            $employeeData
        );

        $response->assertSessionDoesntHaveErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('employees', array_merge($employeeData, ['id' => $employee->id]));
    }

    /**
     * for an authorized user
     * @group employee_test
     * @return void
     */
    public function testDestroyForAuthUser()
    {
        $user = $this->user;
        $employee = $this->employee;

        $this->actingAs($user);

        $this->delete(route('employees.destroy', $employee))
            ->assertRedirect()
            ->assertSessionDoesntHaveErrors();
        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }

    /**
     * for a guest
     * @group employee_test
     * @return void
     */
    public function testCreateForGuest()
    {
        $response = $this->get(route('employees.create'));
        $response->assertRedirect('/login');
    }

    /**
     * for a guest
     * @group employee_test
     * @return void
     */
    public function testStoreForGuest()
    {
        $company = $this->company;
        $user = $this->user;

        $employeeData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'company_id' => $company->id,
            'user_id' => $user->id,
        ];
        $response = $this->post(route('employees.store'), $employeeData);
        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('employees', $employeeData);
    }


    /**
     * for a guest
     * @group employee_test
     * @return void
     */
    public function testEditForGuest()
    {
        $employee = $this->employee;
        $response = $this->get(route('employees.edit', $employee));
        $response->assertRedirect('/login');
        $this->assertDatabaseHas('employees', ['id' => $employee->id]);
    }

    /**
     * for a guest
     * @group employee_test
     * @return void
     */
    public function testUpdateForGuest()
    {
        $employee = $this->employee;
        $user = $this->user;

        $employeeData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'company_id' => $employee->id,
            'user_id' => $user->id,
        ];

        $response = $this->put(route('employees.update', $employee), $employeeData);
        $response->assertRedirect('/login');
        $this->assertDatabaseHas('employees', ['id' => $employee->id]);
    }

    /**
     * for a guest
     * @group employee_test
     * @return void
     */
    public function testDestroyForGuest()
    {
        $employee = $this->employee;
        $response = $this->delete(route('employees.destroy', ['employee' => $employee]));
        $response->assertRedirect('/login');
        $this->assertDatabaseHas('employees', ['id' => $employee->id]);
    }
}
