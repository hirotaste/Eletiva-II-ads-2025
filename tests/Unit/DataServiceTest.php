<?php

namespace Tests\Unit;

use App\Services\DataService;
use Tests\TestCase;

/**
 * Unit tests for DataService.
 * Tests session-based data storage operations.
 */
class DataServiceTest extends TestCase
{
    /**
     * Set up test environment before each test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear session before each test
        session()->flush();
    }

    /**
     * Test initializing data.
     *
     * @return void
     */
    public function test_can_initialize_data(): void
    {
        DataService::initializeData();

        $this->assertTrue(session()->has('teachers'));
        $this->assertTrue(session()->has('disciplines'));
        $this->assertTrue(session()->has('students'));
        $this->assertTrue(session()->has('classrooms'));

        $teachers = session('teachers');
        $this->assertIsArray($teachers);
        $this->assertNotEmpty($teachers);
    }

    /**
     * Test getting next ID for empty collection.
     *
     * @return void
     */
    public function test_get_next_id_returns_one_for_empty_collection(): void
    {
        $nextId = DataService::getNextId('test_entity');

        $this->assertEquals(1, $nextId);
    }

    /**
     * Test getting next ID for existing collection.
     *
     * @return void
     */
    public function test_get_next_id_returns_correct_value(): void
    {
        session(['test_entity' => [
            ['id' => 1, 'name' => 'Item 1'],
            ['id' => 2, 'name' => 'Item 2'],
            ['id' => 5, 'name' => 'Item 5'],
        ]]);

        $nextId = DataService::getNextId('test_entity');

        $this->assertEquals(6, $nextId);
    }

    /**
     * Test adding an item.
     *
     * @return void
     */
    public function test_can_add_item(): void
    {
        $data = ['name' => 'Test Item', 'description' => 'Test Description'];

        $result = DataService::add('items', $data);

        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('Test Item', $result['name']);

        $items = session('items');
        $this->assertCount(1, $items);
    }

    /**
     * Test adding multiple items.
     *
     * @return void
     */
    public function test_can_add_multiple_items(): void
    {
        DataService::add('items', ['name' => 'Item 1']);
        DataService::add('items', ['name' => 'Item 2']);
        DataService::add('items', ['name' => 'Item 3']);

        $items = session('items');
        $this->assertCount(3, $items);
        $this->assertEquals(1, $items[0]['id']);
        $this->assertEquals(2, $items[1]['id']);
        $this->assertEquals(3, $items[2]['id']);
    }

    /**
     * Test getting all items.
     *
     * @return void
     */
    public function test_can_get_all_items(): void
    {
        session(['items' => [
            ['id' => 1, 'name' => 'Item 1'],
            ['id' => 2, 'name' => 'Item 2'],
        ]]);

        $items = DataService::getAll('items');

        $this->assertIsArray($items);
        $this->assertCount(2, $items);
    }

    /**
     * Test getting all items returns empty array when no items exist.
     *
     * @return void
     */
    public function test_get_all_returns_empty_array_when_no_items(): void
    {
        $items = DataService::getAll('nonexistent');

        $this->assertIsArray($items);
        $this->assertEmpty($items);
    }

    /**
     * Test finding an item by ID.
     *
     * @return void
     */
    public function test_can_find_item_by_id(): void
    {
        session(['items' => [
            ['id' => 1, 'name' => 'Item 1'],
            ['id' => 2, 'name' => 'Item 2'],
            ['id' => 3, 'name' => 'Item 3'],
        ]]);

        $item = DataService::find('items', 2);

        $this->assertIsArray($item);
        $this->assertEquals(2, $item['id']);
        $this->assertEquals('Item 2', $item['name']);
    }

    /**
     * Test finding non-existent item returns null.
     *
     * @return void
     */
    public function test_find_returns_null_when_item_not_found(): void
    {
        session(['items' => [
            ['id' => 1, 'name' => 'Item 1'],
        ]]);

        $item = DataService::find('items', 999);

        $this->assertNull($item);
    }

    /**
     * Test updating an item.
     *
     * @return void
     */
    public function test_can_update_item(): void
    {
        session(['items' => [
            ['id' => 1, 'name' => 'Original Name'],
            ['id' => 2, 'name' => 'Item 2'],
        ]]);

        $result = DataService::update('items', 1, [
            'name' => 'Updated Name',
            'description' => 'New Description'
        ]);

        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('Updated Name', $result['name']);
        $this->assertEquals('New Description', $result['description']);

        $items = session('items');
        $this->assertEquals('Updated Name', $items[0]['name']);
    }

    /**
     * Test updating non-existent item returns null.
     *
     * @return void
     */
    public function test_update_returns_null_when_item_not_found(): void
    {
        session(['items' => [
            ['id' => 1, 'name' => 'Item 1'],
        ]]);

        $result = DataService::update('items', 999, ['name' => 'Updated']);

        $this->assertNull($result);
    }

    /**
     * Test deleting an item.
     *
     * @return void
     */
    public function test_can_delete_item(): void
    {
        session(['items' => [
            ['id' => 1, 'name' => 'Item 1'],
            ['id' => 2, 'name' => 'Item 2'],
            ['id' => 3, 'name' => 'Item 3'],
        ]]);

        $result = DataService::delete('items', 2);

        $this->assertTrue($result);

        $items = session('items');
        $this->assertCount(2, $items);
        
        // Verify item 2 is gone
        $found = false;
        foreach ($items as $item) {
            if ($item['id'] == 2) {
                $found = true;
                break;
            }
        }
        $this->assertFalse($found);
    }

    /**
     * Test deleting non-existent item returns false.
     *
     * @return void
     */
    public function test_delete_returns_false_when_item_not_found(): void
    {
        session(['items' => [
            ['id' => 1, 'name' => 'Item 1'],
        ]]);

        $result = DataService::delete('items', 999);

        $this->assertFalse($result);
    }

    /**
     * Test that delete reindexes the array.
     *
     * @return void
     */
    public function test_delete_reindexes_array(): void
    {
        session(['items' => [
            ['id' => 1, 'name' => 'Item 1'],
            ['id' => 2, 'name' => 'Item 2'],
            ['id' => 3, 'name' => 'Item 3'],
        ]]);

        DataService::delete('items', 2);

        $items = session('items');
        
        // Check that array is reindexed (keys are 0, 1, not 0, 2)
        $this->assertArrayHasKey(0, $items);
        $this->assertArrayHasKey(1, $items);
        $this->assertArrayNotHasKey(2, $items);
    }
}
