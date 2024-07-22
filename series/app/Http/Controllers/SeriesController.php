<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeriesFormRequest;
use App\Models\Episode;
use App\Models\Season;
use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Http\View;
use Illuminate\Support\Facades\DB;

class SeriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        // $series = Serie::query()->orderBy('nome')->get();
        $series = Series::all();
        $mensagemSucesso = session('mensagem.sucesso'); 
        // $request->session()->forget('mensagem.sucesso');


        return view('series.index')->with(['series' => $series])->with('mensagemSucesso' , $mensagemSucesso);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('series.create');    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeriesFormRequest $request)
    {
        $nomeSerie = $request->nome;
        
        // $request->validate([
        //     'nome' => ['required', 'min:3']
        // ])
        //dd($request->all());
        $serie = Series::create($request->all()); 
        // $request->session()->flash('mensagem.sucesso', "Série '{$nomeSerie}' adicionada com sucesso");
        $seasons = [];
        for($i=1; $i <= $request->seasonsQty; $i++){
            //esse é o relacionamento
            $seasons[] = [
                'series_id' => $serie->id,
                'number' => $i
            ];
        }
        
        Season::insert($seasons);
            
        $episodes = [];
        foreach($serie->seasons as $season){
            for($j=1; $j <= $request->episodesPerSeason; $j++){
                $episodes[] = [
                    'season_id' => $season->id,
                    'number' => $j
                ];
            }
        }
            
        Episode::insert($episodes);
        
        return redirect('series')->with('mensagem.sucesso', "Série '{$nomeSerie}' adicionada com sucesso");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Series $series)
    {

        return view('series.edit')->with('serie', $series);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Series $series, SeriesFormRequest $request)
    {
        $series->fill($request->all());
        $series->save();
        $nomeSerie = $series->nome;
        // $request->session()->flash('mensagem.sucesso', "Série '{$nomeSerie}' atualizada com sucesso");


        return redirect('series')->with('mensagem.sucesso', "Série '{$nomeSerie}' atualizada com sucesso");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Series $series)
    {

        $nomeSerie = $series->nome;
        Series::destroy($series->id);
        // $request->session()->flash('mensagem.sucesso', "Série '{$nomeSerie}' removida com sucesso");

        return redirect('series')->with('mensagem.sucesso', "Série '{$nomeSerie}' removida com sucesso");
    }
}
