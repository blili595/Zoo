<?php

namespace App\Http\Controllers;

use App\Models\Encosure;
use App\Http\Requests\StoreEnclosureRequest;
use App\Http\Requests\UpdateEnclosureRequest;
use App\Models\Animal;
use App\Models\Enclosure;
use App\Helper\Helper;
use Illuminate\Http\Request;
use App\Models\User;

class EnclosureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //

        /*$user = $request->user();

        $enclosures = $user->enclosures()->withCount('animals')->get();*/
        if (auth()->user()->admin) {
            $enclosures = Enclosure::paginate(5);
        } else {
            $enclosures = auth()->user()->enclosures()->paginate(5);; 
        }
        return view('enclosures.index', compact('enclosures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $users = User::all(); 

        return view('enclosures.form', compact('users'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEnclosureRequest $request)
    {
        //
        $enclosure = Enclosure::create($request->validated());
        return redirect()->route('enclosures.show', $enclosure->id)->with('success', 'Enclosure created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Enclosure $enclosure)
    {
        //
        $enclosure = Enclosure::find($enclosure->id);
        return view('enclosures.show', compact('enclosure'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enclosure $enclosure)
    {
        //
        $enclosure = Enclosure::find($enclosure->id);
        $users = User::all(); 
        return view('enclosures.form', compact('enclosure', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEnclosureRequest $request, Enclosure $enclosure)
    {
        
        $enclosure = Enclosure::find($enclosure->id);
        $enclosure->update($request->validated());
        $enclosure->users()->sync($request->input('users', [])); 

        return redirect()->route('enclosures.show', $enclosure->id)->with('success', 'Enclosure updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enclosure $enclosure)
{
    
    if ($enclosure->animals()->exists()) {
        return redirect()->route('enclosures.index')->with('error', 'Enclosure cannot be deleted because it has animals.');
    }

    
    $enclosure->delete();

    return redirect()->route('enclosures.index')->with('success', 'Enclosure deleted successfully.');
}
}
