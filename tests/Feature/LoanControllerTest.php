<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Loan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and set it as the currently authenticated user
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    /**
     * Test retrieving all loans (index).
     */
    public function test_index_success()
    {
        // Create multiple loans
        Loan::factory()->count(3)->create(['lender_id' => $this->user->id]);

        $response = $this->getJson('/api/loans');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'amount',
                        'interest_rate',
                        'duration',
                        'lender' => ['id', 'name'],
                        'borrower' => ['id', 'name'],
                    ]
                ]
            ]);
    }

    /**
     * Test successful loan creation (store).
     */
    public function test_store_success()
    {
        $borrower = User::factory()->create();

        $data = [
            'amount' => 5000,
            'interest_rate' => 5.5,
            'duration' => 24,
            'borrower_id' => $borrower->id,
        ];

        $response = $this->postJson('/api/loans', $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Loan created successfully.',
                'data' => ['loan_id' => true], // Checking presence of loan_id
            ]);
    }

    /**
     * Test loan creation failure due to lender and borrower being the same (store).
     */
    public function test_store_failure_same_lender_and_borrower()
    {
        $data = [
            'amount' => 5000,
            'interest_rate' => 5.5,
            'duration' => 24,
            'borrower_id' => $this->user->id, // Same as lender
        ];

        $response = $this->postJson('/api/loans', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['borrower_id']);
    }

    /**
     * Test retrieving a specific loan (show).
     */
    public function test_show_success()
    {
        $loan = Loan::factory()->create(['lender_id' => $this->user->id]);

        $response = $this->getJson("/api/loans/{$loan->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Loan retrieved successfully.',
                'data' => [
                    'id' => $loan->id,
                    'amount' => $loan->amount,
                    'interest_rate' => $loan->interest_rate,
                    'duration' => $loan->duration,
                    'lender' => ['id' => $this->user->id, 'name' => $this->user->name],
                ],
            ]);
    }

    /**
     * Test updating a loan successfully (update).
     */
    public function test_update_success()
    {
        $loan = Loan::factory()->create(['lender_id' => $this->user->id]);
        $data = [
            'amount' => 6000,
            'interest_rate' => 6.0,
            'duration' => 36,
        ];

        $response = $this->putJson("/api/loans/{$loan->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Loan updated successfully.',
            ]);
    }

    /**
     * Test loan update failure with non-existent loan (update).
     */
    public function test_update_failure_non_existent_loan()
    {
        $data = [
            'amount' => 6000,
            'interest_rate' => 6.0,
            'duration' => 36,
        ];

        $response = $this->putJson('/api/loans/999', $data);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Failed to update loan',
            ]);
    }

    /**
     * Test deleting a loan successfully (destroy).
     */
    public function test_destroy_success()
    {
        $loan = Loan::factory()->create(['lender_id' => $this->user->id]);

        $response = $this->deleteJson("/api/loans/{$loan->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Loan deleted successfully.',
            ]);
    }

    /**
     * Test loan deletion failure with non-existent loan (destroy).
     */
    public function test_destroy_failure_non_existent_loan()
    {
        $response = $this->deleteJson('/api/loans/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Failed to delete loan',
            ]);
    }
}
