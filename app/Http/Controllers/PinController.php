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
        'locations' => 'required|array',
        'locations.*.latitude' => 'required|string',
        'locations.*.longitude' => 'required|string',
        'locations.*.name' => 'required|string',
    ]);

    $pin = new Pin();
    $pin->locations = $validatedData['locations'];
    $pin->save();

    return response()->json([
        'message' => 'Pin created successfully!',
        'pin' => $pin,
    ], 201);
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pin = Resource::find($id);
        return response()->json([
            'message' => 'Pin find successfully!',
            'pin' => $pin,
        ], 201);
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
    public function update(Request $request)
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
