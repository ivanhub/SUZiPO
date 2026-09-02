<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return view('bookings.index');
    }

    public function create()
    {
        return view('bookings.create');
    }

    public function store(Request $request)
    {
        // Логика сохранения
    }

    public function show($id)
    {
        return view('bookings.show');
    }

    public function edit($id)
    {
        return view('bookings.edit');
    }

    public function update(Request $request, $id)
    {
        // Логика обновления
    }

    public function destroy($id)
    {
        // Логика удаления
    }
}