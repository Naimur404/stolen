<?php

namespace App\Http\Controllers;


class DashBoardController extends Controller
{
    public function index(){

        $data= 0;
        $card =0;
        $service = 0;
        $tran = 0;


        $totaldata= 0;
        $totalcard = 0;
        $totalservice = 0;
        $totaltran = 0;
        return view('admin.color-version.index',compact('data','card','service','tran','totaldata','totalcard','totalservice','totaltran'));
    }
}
