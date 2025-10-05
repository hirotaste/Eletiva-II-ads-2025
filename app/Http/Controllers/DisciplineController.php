<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Services\DataService;
use Illuminate\Http\Request;

class DisciplineController extends Controller
{
    public function __construct()
    {
        DataService::initializeData();
    }

    /**
     * Display a listing of disciplines (API).
     */
    public function index()
    {
        $disciplines = DataService::getAll('disciplines');
        return response()->json($disciplines);
    }

    /**
     * Display a listing of disciplines (WEB).
     */
    public function webIndex()
    {
        $disciplines = DataService::getAll('disciplines');
        return view('disciplines.index', compact('disciplines'));
    }

    /**
     * Show the form for creating a new discipline.
     */
    public function create()
    {
        return view('disciplines.create');
    }

    /**
     * Store a newly created discipline (WEB).
     */
    public function webStore(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'name' => 'required|string|max:255',
            'workload_hours' => 'required|integer|min:1',
            'weekly_hours' => 'required|integer|min:1',
            'credits' => 'required|integer|min:1',
            'type' => 'required|in:obrigatória,eletiva,optativa',
        ]);

        DataService::add('disciplines', $validated);
        
        return redirect()->route('disciplines.index')
            ->with('success', 'Disciplina cadastrada com sucesso!');
    }

    /**
     * Show the form for editing the specified discipline.
     */
    public function edit($id)
    {
        $discipline = DataService::find('disciplines', $id);
        
        if (!$discipline) {
            return redirect()->route('disciplines.index')
                ->with('error', 'Disciplina não encontrada!');
        }

        return view('disciplines.edit', compact('discipline'));
    }

    /**
     * Update the specified discipline (WEB).
     */
    public function webUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'name' => 'required|string|max:255',
            'workload_hours' => 'required|integer|min:1',
            'weekly_hours' => 'required|integer|min:1',
            'credits' => 'required|integer|min:1',
            'type' => 'required|in:obrigatória,eletiva,optativa',
        ]);

        $discipline = DataService::update('disciplines', $id, $validated);
        
        if (!$discipline) {
            return redirect()->route('disciplines.index')
                ->with('error', 'Disciplina não encontrada!');
        }
        
        return redirect()->route('disciplines.index')
            ->with('success', 'Disciplina atualizada com sucesso!');
    }

    /**
     * Remove the specified discipline (WEB).
     */
    public function webDestroy($id)
    {
        $deleted = DataService::delete('disciplines', $id);
        
        if ($deleted) {
            return redirect()->route('disciplines.index')
                ->with('success', 'Disciplina removida com sucesso!');
        }
        
        return redirect()->route('disciplines.index')
            ->with('error', 'Disciplina não encontrada!');
    }

    /**
     * Display the specified discipline.
     */
    public function show(Discipline $discipline)
    {
        return response()->json($discipline->load('classes', 'curriculumMatrices'));
    }

    /**
     * Update the specified discipline.
     */
    public function update(Request $request, Discipline $discipline)
    {
        $validated = $request->validate([
            'code' => 'sometimes|string|unique:disciplines,code,' . $discipline->id,
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'workload_hours' => 'sometimes|integer|min:1',
            'weekly_hours' => 'sometimes|integer|min:1',
            'credits' => 'sometimes|integer|min:1',
            'prerequisites' => 'nullable|array',
            'type' => 'sometimes|in:mandatory,elective,optional',
            'is_active' => 'sometimes|boolean',
        ]);

        $discipline->update($validated);
        return response()->json($discipline);
    }

    /**
     * Remove the specified discipline.
     */
    public function destroy(Discipline $discipline)
    {
        $discipline->update(['is_active' => false]);
        return response()->json(['message' => 'Discipline deactivated successfully']);
    }
}
