<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Services\DataService;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function __construct()
    {
        DataService::initializeData();
    }

    /**
     * Display a listing of classrooms (API).
     */
    public function index()
    {
        $classrooms = DataService::getAll('classrooms');
        return response()->json($classrooms);
    }

    /**
     * Display a listing of classrooms (WEB).
     */
    public function webIndex()
    {
        $classrooms = DataService::getAll('classrooms');
        return view('classrooms.index', compact('classrooms'));
    }

    /**
     * Show the form for creating a new classroom.
     */
    public function create()
    {
        return view('classrooms.create');
    }

    /**
     * Store a newly created classroom (WEB).
     */
    public function webStore(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'building' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'type' => 'required|in:aula teorica,laboratorio,auditorio,seminario',
            'has_accessibility' => 'boolean',
        ]);

        // Converter checkbox para boolean
        $validated['has_accessibility'] = $request->has('has_accessibility');

        DataService::add('classrooms', $validated);
        
        return redirect()->route('classrooms.index')
            ->with('success', 'Sala cadastrada com sucesso!');
    }

    /**
     * Show the form for editing the specified classroom.
     */
    public function edit($id)
    {
        $classroom = DataService::find('classrooms', $id);
        
        if (!$classroom) {
            return redirect()->route('classrooms.index')
                ->with('error', 'Sala não encontrada!');
        }

        return view('classrooms.edit', compact('classroom'));
    }

    /**
     * Update the specified classroom (WEB).
     */
    public function webUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'building' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'type' => 'required|in:aula teorica,laboratorio,auditorio,seminario',
            'has_accessibility' => 'boolean',
        ]);

        // Converter checkbox para boolean
        $validated['has_accessibility'] = $request->has('has_accessibility');

        $classroom = DataService::update('classrooms', $id, $validated);
        
        if (!$classroom) {
            return redirect()->route('classrooms.index')
                ->with('error', 'Sala não encontrada!');
        }
        
        return redirect()->route('classrooms.index')
            ->with('success', 'Sala atualizada com sucesso!');
    }

    /**
     * Remove the specified classroom (WEB).
     */
    public function webDestroy($id)
    {
        $deleted = DataService::delete('classrooms', $id);
        
        if ($deleted) {
            return redirect()->route('classrooms.index')
                ->with('success', 'Sala removida com sucesso!');
        }
        
        return redirect()->route('classrooms.index')
            ->with('error', 'Sala não encontrada!');
    }
}
