<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
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
    }

    /**
     * for all
     * @group company_test
     * @return void
     */
    public function testIndex()
    {
        $company = $this->company;

        $this->get(route('companies.index'))
            ->assertOk()
            ->assertSee($company->name);
    }

    /**
     * for all
     * @group company_test
     * @return void
     */
    public function testShowPage()
    {
        $company = $this->company;
        $response = $this->get(route('companies.show', $company));
        $response->assertOk();
    }


    /**
     * for an authorized user
     * @group company_test
     * @return void
     */
    public function testCreateFormForAuthUser()
    {
        $user = $this->user;
        $this->actingAs($user);

        $response = $this->get(route('companies.create'));
        $response->assertOK()
            ->assertSeeText('Create a new company');
    }

    /**
     * for an authorized user
     * @group company_test
     * @return void
     */
    public function testStoreForAuthUser()
    {
        $user = $this->user;
        $this->actingAs($user);

        $companyData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'address' => $this->faker->address,
            'logo' => null,
            'user_id' => $user->id,
        ];
        $response = $this->post(route('companies.store'), $companyData);
        $response->assertSessionDoesntHaveErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('companies', $companyData);
    }


    /**
     * for an authorized user
     * @group company_test
     * @return void
     */
    public function testEditFormForAuthUser()
    {
        $user = $this->user;
        $this->actingAs($user);
        $company = $this->company;
        $response = $this->get(route('companies.edit', $company));
        $response->assertOk()
            ->assertSeeText('Edit a company');
    }

    /**
     * for an authorized user
     * @group company_test
     * @return void
     */
    public function testUpdateForAuthUser()
    {
        $user = $this->user;
        $this->actingAs($user);
        $company = $this->company;

        $companyData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'address' => $this->faker->address,
            'logo' => null,
            'user_id' => $user->id,
        ];

        $response = $this->put(
            route('companies.update', ['company' => $company]),
            $companyData
        );

        $response->assertSessionDoesntHaveErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('companies', array_merge($companyData, ['id' => $company->id]));
    }

    /**
     * for an authorized user
     * @group company_test
     * @return void
     */
    public function testDestroyForAuthUser()
    {
        $user = $this->user;
        $this->actingAs($user);
        $company = $this->company;

        $this->delete(route('companies.destroy', $company))
            ->assertRedirect()
            ->assertSessionDoesntHaveErrors();
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }

    /**
     * for a guest
     * @group company_test
     * @return void
     */
    public function testCreateForGuest()
    {
        $response = $this->get(route('companies.create'));
        $response->assertRedirect('/login');
    }

    /**
     * for a guest
     * @group company_test
     * @return void
     */
    public function testStoreForGuest()
    {
        $user = $this->user;
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'address' => $this->faker->address,
            'user_id' => $user->id,
        ];
        $response = $this->post(route('companies.store'), $data);
        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('companies', $data);
    }


    /**
     * for a guest
     * @group company_test
     * @return void
     */
    public function testEditFormForGuest()
    {
        $company = $this->company;
        $response = $this->get(route('companies.edit', $company));
        $response->assertRedirect('/login');
        $this->assertDatabaseHas('companies', ['id' => $company->id]);

    }

    /**
     * for a guest
     * @group company_test
     * @return void
     */
    public function testUpdateForGuest()
    {
        $user = $this->user;
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'address' => $this->faker->address,
            'user_id' => $user->id,
        ];
        $company = $this->company;
        $response = $this->put(route('companies.update', $company), $data);
        $response->assertRedirect('/login');
        $this->assertDatabaseHas('companies', ['id' => $company->id]);
    }

    /**
     * for a guest
     * @group company_test
     * @return void
     */
    public function testDestroyForGuest()
    {
        $company = $this->company;
        $response = $this->delete(route('companies.destroy', ['company' => $company]));
        $response->assertRedirect('/login');
        $this->assertDatabaseHas('companies', ['id' => $company->id]);
    }
}
