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

        // La lógica para asignar el turno aleatoriamente se deja a tu consideración.

        $player = Player::create($request->all());

        return response()->json($player, 201);
    }
}
