<?php

namespace App\Services;

/**
 * Data Service for session-based data storage.
 * Provides temporary data storage for development and testing purposes.
 * 
 * @deprecated This service is for temporary storage only. Use proper database models in production.
 */
class DataService
{
    /**
     * Initialize session data with sample records.
     * Creates default data for teachers, disciplines, students, and classrooms if not present.
     *
     * @return void
     */
    public static function initializeData(): void
    {
        if (!session()->has('teachers')) {
            session([
                'teachers' => [
                    [
                        'id' => 1,
                        'name' => 'Dr. João Silva',
                        'email' => 'joao.silva@universidade.edu.br',
                        'specialization' => 'Engenharia de Software',
                        'employment_type' => 'integral'
                    ],
                    [
                        'id' => 2,
                        'name' => 'Dra. Maria Santos',
                        'email' => 'maria.santos@universidade.edu.br',
                        'specialization' => 'Banco de Dados',
                        'employment_type' => 'meio periodo'
                    ]
                ],
                'disciplines' => [
                    [
                        'id' => 1,
                        'code' => 'ENG001',
                        'name' => 'Engenharia de Software I',
                        'workload_hours' => 80,
                        'weekly_hours' => 4,
                        'credits' => 4,
                        'type' => 'obrigatória'
                    ],
                    [
                        'id' => 2,
                        'code' => 'BD001',
                        'name' => 'Banco de Dados I',
                        'workload_hours' => 60,
                        'weekly_hours' => 3,
                        'credits' => 3,
                        'type' => 'obrigatória'
                    ]
                ],
                'students' => [
                    [
                        'id' => 1,
                        'registration_number' => '2023001001',
                        'name' => 'Pedro Henrique',
                        'email' => 'pedro.henrique@estudante.edu.br',
                        'status' => 'ativo',
                        'gpa' => 8.5
                    ],
                    [
                        'id' => 2,
                        'registration_number' => '2023001002',
                        'name' => 'Ana Carolina',
                        'email' => 'ana.carolina@estudante.edu.br',
                        'status' => 'ativo',
                        'gpa' => 9.2
                    ]
                ],
                'classrooms' => [
                    [
                        'id' => 1,
                        'code' => 'A101',
                        'building' => 'Bloco A',
                        'capacity' => 40,
                        'type' => 'aula teorica',
                        'has_accessibility' => true
                    ],
                    [
                        'id' => 2,
                        'code' => 'L201',
                        'building' => 'Bloco L',
                        'capacity' => 20,
                        'type' => 'laboratorio',
                        'has_accessibility' => true
                    ]
                ]
            ]);
        }
    }

    /**
     * Get the next available ID for an entity.
     *
     * @param string $entity The entity type
     * @return int The next ID
     */
    public static function getNextId(string $entity): int
    {
        $items = session($entity, []);
        return count($items) > 0 ? max(array_column($items, 'id')) + 1 : 1;
    }

    /**
     * Add a new item to session storage.
     *
     * @param string $entity The entity type
     * @param array<string, mixed> $data The data to store
     * @return array<string, mixed> The stored data with ID
     */
    public static function add(string $entity, array $data): array
    {
        $items = session($entity, []);
        $data['id'] = self::getNextId($entity);
        $items[] = $data;
        session([$entity => $items]);
        return $data;
    }

    /**
     * Get all items of a specific entity type.
     *
     * @param string $entity The entity type
     * @return array<int, array<string, mixed>> Array of items
     */
    public static function getAll(string $entity): array
    {
        return session($entity, []);
    }

    /**
     * Find a specific item by ID.
     *
     * @param string $entity The entity type
     * @param int $id The item ID
     * @return array<string, mixed>|null The found item or null
     */
    public static function find(string $entity, int $id): ?array
    {
        $items = session($entity, []);
        foreach ($items as $item) {
            if ($item['id'] == $id) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Update an existing item.
     *
     * @param string $entity The entity type
     * @param int $id The item ID
     * @param array<string, mixed> $data The updated data
     * @return array<string, mixed>|null The updated item or null if not found
     */
    public static function update(string $entity, int $id, array $data): ?array
    {
        $items = session($entity, []);
        foreach ($items as $key => $item) {
            if ($item['id'] == $id) {
                $data['id'] = $id;
                $items[$key] = $data;
                session([$entity => $items]);
                return $data;
            }
        }
        return null;
    }

    /**
     * Delete an item by ID.
     *
     * @param string $entity The entity type
     * @param int $id The item ID
     * @return bool True if deleted, false if not found
     */
    public static function delete(string $entity, int $id): bool
    {
        $items = session($entity, []);
        foreach ($items as $key => $item) {
            if ($item['id'] == $id) {
                unset($items[$key]);
                session([$entity => array_values($items)]);
                return true;
            }
        }
        return false;
    }
}