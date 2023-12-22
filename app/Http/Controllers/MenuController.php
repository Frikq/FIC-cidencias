<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    
    public function index(){
        return view('menu');
    }/*

    public function create($username){
        return view('menu', compact('username'));
    }

    public function show($dos, $variables){
        return "Bienvenido $dos, a esta pagina de $variables";
    }*/
}
