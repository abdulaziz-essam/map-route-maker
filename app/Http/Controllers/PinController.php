<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pin;
class PinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pins = Pin::all();

        return response()->json($pins);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
/**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
    $validatedData = $request->validate([
        'latitude' => 'required|max:255',
        'longitude' => 'required|max:255',
        'name' => 'required|max:255',
    ]);

    $pin = new Pin;

    $pin->latitude = $validatedData['latitude'];
    $pin->longitude = $validatedData['longitude'];
    $pin->name = $validatedData['name'];

    try {
        $pin->save();
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error creating pin',
            'error' => $e->getMessage()
        ], 500);
    }

    return response()->json([
        'message' => 'Pin created successfully!',
        'data' => $pin
    ], 201);
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->route('id');
        $pin = Pin::findOrFail($id);
        $pin->delete();

        return "delete success";
    }
}
