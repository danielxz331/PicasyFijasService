<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    
    public function play(Request $request)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
            'turn' => 'required|integer',
            'number' => 'required'
        ]);

        $player = Player::find($request->player_id);
        // Valida si es el turno del jugador.

        if ($player->turn !== $request->turn) {
            return response()->json(['error' => 'No es tu turno.'], 400);
        }

        if( strlen($request->number) < 4 || strlen($request->number) > 4 ){
            return response()->json(['error' => 'Cantidad de dígitos inválida'], 400);
        }
        //traigo a todos los demás jugadores
        $otherPlayers = Player::whereNotIn('id', [$request->player_id])->get();
        
        $gameData = [];

        if($player->availability == 'on'){
            foreach ($otherPlayers as $otherPlayer) {
                $picas = 0;
                $fijas = 0;
    
                for ($i = 0; $i < 4; $i++) {
                    if ($otherPlayer->number[$i] == $request->number[$i]) {
                        $fijas++;
                    }
                }
    
                for ($i = 0; $i < 4; $i++) {
                    if ($otherPlayer->number[$i] != $request->number[$i] && strpos($request->number, $otherPlayer->number[$i]) !== false) {
                        $picas++;
                    }
                }
                $otherPlayer->availability = 'on';
                if ($otherPlayer->number == $request->number) {
                    $fijas = 4;
                    // Elimina al jugador si se adivinó su número.

                    $otherPlayer->availability = 'off';
                    $jugador = Player::find($otherPlayer->id);
                    $jugador->availability = $otherPlayer->availability;

                    $jugadores_eliminados = Player::where('availability','off')->get();
                    if (count($jugadores_eliminados) == 0){
                        $jugador->position = 4;
                    }
                    if (count($jugadores_eliminados) == 1){
                        $jugador->position = 3;
                    }
                    if (count($jugadores_eliminados) == 2){
                        $jugador->position = 2;
                        $jugador_ganador = Player::where('id', '!=', $jugador->id)->where('availability', '!=', 'off')->first();
                        $jugador_ganador->availability = 'off';
                        $jugador_ganador->position = 1;
                        $jugador_ganador->save();
                    }
                    
                    $jugador->save();

                }
                
                $juego = new Game();
                $juego->player_id=$otherPlayer->id;
                $juego->picas = $picas;
                $juego->fijas = $fijas;
    
                $juego->save();
                $gameData[] = $juego;
            }
        }else{
            return response()->json(['error' => 'El jugador ha sido eliminado'], 400);
        }
        //itero sobre todos los jugadores
        return response()->json($gameData, 201);  
        
        
    }



    
    public function status()
    {
        $pocisiones = Player::where('availability', '==', 'off')->orderBy('position', 'asc')->get();
        return response()->json($pocisiones, 200);
    }
}
