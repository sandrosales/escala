<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use App\Models\Plantao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PlantaoController extends Controller
{
    public function listByMonth()
    {
        $plantoes = DB::table('plantoes')
            ->select(
                DB::raw("to_char(data, 'YYYY-MM') AS mes"),
                'funcionarios.nome',
                DB::raw("string_agg(DATE_PART('day', data)::text, ', ') AS dias"),
                'tipo'
            )
            ->join('funcionarios', 'plantoes.funcionario_id', '=', 'funcionarios.id')
            ->groupBy('mes', 'funcionarios.nome', 'tipo')
            ->orderBy('mes', 'asc')
            ->orderBy('funcionarios.nome', 'asc')
            ->get();

        return view('portal.escala.plantoes-mes', ['plantoes' => $plantoes]);
    }

    public function create()
    {
        $funcionarios = Funcionario::all();
        return view('portal.escala.plantao-form', compact('funcionarios'));
    }



    //  Store
    public function store(Request $request)
    {
        $funcionario = Funcionario::findOrFail($request->funcionario_id);
        $tipoPlantao = $request->tipo;
        $dataPlantao = $request->data;

        $validatedData = $request->validate([
            'funcionario_id' => 'required|exists:funcionarios,id',
            'tipo' => 'required|in:normal,extra',
            'data' => 'required|date',
        ]);

        $tipoPlantao = $validatedData['tipo'];
        $funcionarioId = $validatedData['funcionario_id'];
        $mes = intval(Carbon::parse($request->data)->format('m'));

        $qtdPlantoesNormais = Plantao::where('funcionario_id', $funcionarioId)
            ->where('tipo', 'normal')
            ->whereMonth('data', $mes)
            ->count();

        $qtdPlantoesExtras = Plantao::where('funcionario_id', $funcionarioId)
            ->where('tipo', 'extra')
            ->whereMonth('data', $mes)
            ->count();

        if (($tipoPlantao == 'normal' && $qtdPlantoesNormais >= 8) ||
            ($tipoPlantao == 'extra' && $qtdPlantoesExtras >= 5)
        ) {
            return redirect()->back()
                ->with('info', "O funcionário já tem o número máximo de plantões.");
        }

        // Verifica se o novo plantão é do mesmo tipo de um plantão já existente no mesmo dia

        $existePlantaoDoMesmoTipo = $funcionario->plantoes()
            ->where('data', $dataPlantao)
            ->where('tipo', $tipoPlantao)
            ->exists();

        $existePlantaoExtra = $funcionario->plantoes()
            ->where('data', $dataPlantao)
            ->where('tipo', 'extra')
            ->exists();

        $existePlantaoNormalExtra = $funcionario->plantoes()
            ->where('data', $dataPlantao)
            ->where('tipo', 'normal')
            ->exists();

        if ($existePlantaoDoMesmoTipo) {
            return redirect()->back()
                ->with('info', "Já existe um plantão $tipoPlantao marcado para esse dia.");
        }

        if ($existePlantaoExtra) {
            return redirect()->back()
                ->with('info', "Já existe um plantão EXTRA marcado para esse dia.");
        }

        if ($existePlantaoNormalExtra) {
            return redirect()->back()
                ->with('info', "Funcionário já tem plantão NORMAL lançado para esse dia, não é possivel lançar plantão extra.");
        }

        $plantao = new Plantao();
        $plantao->fill($validatedData);
        $plantao->save();

        return redirect()->route('plantoes.mes')
            ->with('success', 'Plantão criado com sucesso.');
    }
}
