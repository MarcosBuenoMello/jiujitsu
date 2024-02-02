<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aluno;
class BoletinController extends Controller
{
    public function index(){
        $user = session('user_logged');
        $aluno = Aluno::findOrFail($user['aluno']->id);
        return view('boletins.index', compact('aluno'));
    }
}
