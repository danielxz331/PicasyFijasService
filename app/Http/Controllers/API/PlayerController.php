<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'number' => 'required|size:4'
        ]);
    
        $player = new Player();
    
        $player->name = $request->name;
        $player->number = $request->number;
        $player->availability = 'on';
        $player->position = 0;
    
        // Obtenemos todos los turnos ya usados
        $usedTurns = Player::pluck('turn')->toArray();
    
        // Creamos un array con los números del 1 al 4
        $allTurns = range(1, 4);
    
        // Obtenemos los turnos aún disponibles
        $availableTurns = array_diff($allTurns, $usedTurns);
    
        if(empty($availableTurns)) {
            return response()->json(['error' => 'Ya hay 4 jugadores en el juego.'], 400);
        }
    
        // Seleccionamos aleatoriamente uno de los turnos disponibles
        $player->turn = $availableTurns[array_rand($availableTurns)];
    
        $player->save();
    
        return response()->json($player, 201);
    }
}
