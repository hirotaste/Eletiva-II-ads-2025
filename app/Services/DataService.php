<?php

namespace App\Services;

class DataService
{
    public static function initializeData()
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

    public static function getNextId($entity)
    {
        $items = session($entity, []);
        return count($items) > 0 ? max(array_column($items, 'id')) + 1 : 1;
    }

    public static function add($entity, $data)
    {
        $items = session($entity, []);
        $data['id'] = self::getNextId($entity);
        $items[] = $data;
        session([$entity => $items]);
        return $data;
    }

    public static function getAll($entity)
    {
        return session($entity, []);
    }

    public static function find($entity, $id)
    {
        $items = session($entity, []);
        foreach ($items as $item) {
            if ($item['id'] == $id) {
                return $item;
            }
        }
        return null;
    }

    public static function update($entity, $id, $data)
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

    public static function delete($entity, $id)
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