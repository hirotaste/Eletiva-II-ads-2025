<?php

namespace App\Http\Controllers;

use App\Models\PeriodoLetivo;
use Illuminate\Http\Request;

class PeriodoLetivoController extends Controller
{
    /**
     * Display a listing of academic periods (WEB).
     */
    public function webIndex()
    {
        $periodos = PeriodoLetivo::orderBy('ano', 'desc')
            ->orderBy('semestre', 'desc')
            ->get();
        return view('periodos-letivos.index', compact('periodos'));
    }

    /**
     * Show the form for creating a new academic period.
     */
    public function create()
    {
        return view('periodos-letivos.create');
    }

    /**
     * Store a newly created academic period (WEB).
     */
    public function webStore(Request $request)
    {
        $validated = $request->validate([
            'ano' => 'required|integer|min:2000|max:2100',
            'semestre' => 'required|in:1,2',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after:data_inicio',
            'status' => 'nullable|in:planejamento,ativo,finalizado',
        ]);

        PeriodoLetivo::create($validated);
        
        return redirect()->route('periodos-letivos.index')
            ->with('success', 'Período letivo cadastrado com sucesso!');
    }

    /**
     * Show the form for editing the specified academic period.
     */
    public function edit($id)
    {
        $periodo = PeriodoLetivo::findOrFail($id);
        return view('periodos-letivos.edit', compact('periodo'));
    }

    /**
     * Update the specified academic period (WEB).
     */
    public function webUpdate(Request $request, $id)
    {
        $periodo = PeriodoLetivo::findOrFail($id);
        
        $validated = $request->validate([
            'ano' => 'required|integer|min:2000|max:2100',
            'semestre' => 'required|in:1,2',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after:data_inicio',
            'status' => 'nullable|in:planejamento,ativo,finalizado',
        ]);

        $periodo->update($validated);
        
        return redirect()->route('periodos-letivos.index')
            ->with('success', 'Período letivo atualizado com sucesso!');
    }

    /**
     * Remove the specified academic period (WEB).
     */
    public function webDestroy($id)
    {
        $periodo = PeriodoLetivo::findOrFail($id);
        $periodo->delete();
        
        return redirect()->route('periodos-letivos.index')
            ->with('success', 'Período letivo removido com sucesso!');
    }
}
