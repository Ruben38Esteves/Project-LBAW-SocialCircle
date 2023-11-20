<?php
class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('/pages/home', ['user' => $user]);
    }
}