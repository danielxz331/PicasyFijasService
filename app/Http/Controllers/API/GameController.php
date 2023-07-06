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
            'number' => 'required|size:4'
        ]);

        $player = Player::find($request->player_id);

        // Valida si es el turno del jugador.
        if ($player->turn !== $request->turn) {
            return response()->json(['error' => 'No es tu turno.'], 400);
        }

        // Aquí va la lógica para calcular las picas y fijas.

        // Por simplicidad, se asume que las picas y fijas son 0.
        $picas = 0;
        $fijas = 0;

        $game = Game::create([
            'player_id' => $request->player_id,
            'picas' => $picas,
            'fijas' => $fijas
        ]);

        return response()->json($game, 201);
    }

    public function status(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
        ]);

        $game = Game::find($request->game_id);
        $player = $game->player;

        // Aquí va la lógica para comprobar si el juego ha terminado.
        
        return response()->json(['game' => $game, 'player' => $player], 200);
    }
}
