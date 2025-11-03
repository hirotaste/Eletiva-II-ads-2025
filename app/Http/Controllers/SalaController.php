<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use Illuminate\Http\Request;

class SalaController extends Controller
{
    /**
     * Display a listing of rooms (WEB).
     */
    public function webIndex()
    {
        $salas = Sala::orderBy('codigo')->get();
        return view('salas.index', compact('salas'));
    }

    /**
     * Show the form for creating a new room.
     */
    public function create()
    {
        return view('salas.create');
    }

    /**
     * Store a newly created room (WEB).
     */
    public function webStore(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:20|unique:salas,codigo',
            'nome' => 'required|string|max:100',
            'capacidade' => 'required|integer|min:1',
            'tipo' => 'nullable|in:sala_aula,laboratorio,auditorio',
            'possui_projetor' => 'boolean',
            'possui_ar_condicionado' => 'boolean',
            'possui_computadores' => 'boolean',
        ]);

        Sala::create($validated);
        
        return redirect()->route('salas.index')
            ->with('success', 'Sala cadastrada com sucesso!');
    }

    /**
     * Show the form for editing the specified room.
     */
    public function edit($id)
    {
        $sala = Sala::findOrFail($id);
        return view('salas.edit', compact('sala'));
    }

    /**
     * Update the specified room (WEB).
     */
    public function webUpdate(Request $request, $id)
    {
        $sala = Sala::findOrFail($id);
        
        $validated = $request->validate([
            'codigo' => 'required|string|max:20|unique:salas,codigo,' . $id,
            'nome' => 'required|string|max:100',
            'capacidade' => 'required|integer|min:1',
            'tipo' => 'nullable|in:sala_aula,laboratorio,auditorio',
            'possui_projetor' => 'boolean',
            'possui_ar_condicionado' => 'boolean',
            'possui_computadores' => 'boolean',
        ]);

        $sala->update($validated);
        
        return redirect()->route('salas.index')
            ->with('success', 'Sala atualizada com sucesso!');
    }

    /**
     * Remove the specified room (WEB).
     */
    public function webDestroy($id)
    {
        $sala = Sala::findOrFail($id);
        $sala->delete();
        
        return redirect()->route('salas.index')
            ->with('success', 'Sala removida com sucesso!');
    }
}
