<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\AddressBook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class AddressBookTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_address_books_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasTable('address_books'));

        $this->assertTrue(Schema::hasColumns('address_books', [
            'id', 'user_id', 'name', 'phone', 'email', 'address', 'created_at', 'updated_at'
        ]));
    }

    public function test_address_book_model_can_be_created()
    {
        $addressBook = AddressBook::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->assertInstanceOf(AddressBook::class, $addressBook);
        $this->assertDatabaseHas('address_books', ['id' => $addressBook->id]);
    }

    public function test_address_book_belongs_to_user()
    {
        $addressBook = AddressBook::factory()->create(['user_id' => $this->user->id]);

        $this->assertInstanceOf(User::class, $addressBook->user);
        $this->assertEquals($this->user->id, $addressBook->user->id);
    }

    public function test_user_has_many_address_books()
    {
        $this->user = User::factory()->create();
        AddressBook::factory()->count(3)->create(['user_id' => $this->user->id]);

        $this->assertCount(3, $this->user->addressBooks);
    }

    public function test_index_method_returns_only_user_address_books()
    {
        $otherUser = User::factory()->create();
        $userAddressBook = AddressBook::factory()->create(['user_id' => $this->user->id]);
        AddressBook::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->get(route('addressbooks.index'));

        $response->assertStatus(200);
        $response->assertViewIs('addressbooks.index');
        $response->assertViewHas('addressbooks', function ($addressbooks) use ($userAddressBook) {
            return $addressbooks->contains($userAddressBook) && $addressbooks->count() === 1;
        });
    }

    public function test_store_method_creates_new_address_book()
    {
        $addressBookData = [
            'name' => 'John Doe',
            'phone' => '1234567890',
            'email' => 'john@example.com',
            'address' => '123 Main St',
        ];

        $response = $this->actingAs($this->user)->post(route('addressbooks.store'), $addressBookData);

        $response->assertRedirect(route('addressbooks.index'));
        $this->assertDatabaseHas('address_books', array_merge($addressBookData, ['user_id' => $this->user->id]));
    }

    public function test_update_method_updates_address_book()
    {
        $addressBook = AddressBook::factory()->create(['user_id' => $this->user->id]);
        $updatedData = [
            'name' => 'Jane Doe',
            'phone' => '9876543210',
            'email' => 'jane@example.com',
            'address' => '456 Elm St',
        ];

        $response = $this->actingAs($this->user)->put(route('addressbooks.update', $addressBook), $updatedData);

        $response->assertRedirect(route('addressbooks.index'));
        $this->assertDatabaseHas('address_books', array_merge($updatedData, ['id' => $addressBook->id, 'user_id' => $this->user->id]));
    }

    public function test_destroy_method_deletes_address_book()
    {
        $addressBook = AddressBook::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->delete(route('addressbooks.destroy', $addressBook));

        $response->assertRedirect(route('addressbooks.index'));
        $this->assertDatabaseMissing('address_books', ['id' => $addressBook->id]);
    }

    public function test_user_cannot_access_other_users_address_book()
    {
        $otherUser = User::factory()->create();
        $otherAddressBook = AddressBook::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->get(route('addressbooks.show', $otherAddressBook));

        $response->assertStatus(403);
    }

    public function test_store_method_validates_input()
    {
        $invalidData = [
            'name' => '', // name is required
            'phone' => '', // phone is required
            'email' => 'invalid-email',
        ];

        $response = $this->actingAs($this->user)->post(route('addressbooks.store'), $invalidData);

        $response->assertSessionHasErrors(['name', 'phone', 'email']);
    }

    public function test_update_method_validates_input()
    {
        $addressBook = AddressBook::factory()->create(['user_id' => $this->user->id]);
        $invalidData = [
            'name' => '', // name is required
            'phone' => '', // phone is required
            'email' => 'invalid-email',
        ];

        $response = $this->actingAs($this->user)->put(route('addressbooks.update', $addressBook), $invalidData);

        $response->assertSessionHasErrors(['name', 'phone', 'email']);
    }

    public function test_routes_are_protected_by_auth_middleware()
    {
        $addressBook = AddressBook::factory()->create();

        $this->get(route('addressbooks.index'))->assertRedirect('/login');
        $this->get(route('addressbooks.create'))->assertRedirect('/login');
        $this->post(route('addressbooks.store'), [])->assertRedirect('/login');
        $this->get(route('addressbooks.show', $addressBook))->assertRedirect('/login');
        $this->get(route('addressbooks.edit', $addressBook))->assertRedirect('/login');
        $this->put(route('addressbooks.update', $addressBook), [])->assertRedirect('/login');
        $this->delete(route('addressbooks.destroy', $addressBook))->assertRedirect('/login');
    }
}
