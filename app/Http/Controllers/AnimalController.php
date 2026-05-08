<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Http\Requests\StoreAnimalRequest;
use App\Http\Requests\UpdateAnimalRequest;
use Illuminate\Http\Request;
use App\Models\Enclosure;
use App\Helper\Helper;

class AnimalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function archived()
    {
        //
        $animals = Animal::withTrashed()->whereNotNull('deleted_at')->with('enclosure')->orderBy('deleted_at', 'desc')->get();
        $enclosures = Enclosure::all();
        return view('animals.index', compact('animals', 'enclosures'));
    }

    public function restore(Request $request, $id)
{
    $request->validate([
        'enclosure_id' => 'required|exists:enclosures,id',
    ]);
    $animal = Animal::withTrashed()->findOrFail($id); 
    $enclosure = Enclosure::findOrFail($request->input('enclosure_id'));

    if ($enclosure->animals->count() >= $enclosure->limit) {
        return redirect()->route('animals.archived')->with('error', 'The selected enclosure has reached its animal limit.');
    }

    $animal->enclosure_id = $enclosure->id;
    $animal->restore();

    return redirect()->route('animals.archived')->with('success', 'Animal restored successfully.');
}
    /*public function index()
    {
        //
        if (auth()->user()->admin) {
            // Admin sees all animals
            $animals = Animal::all();
        } else {
            // Non-admin users see only assigned animals
            $animals = auth()->user()->animals; // Assuming a relationship exists
        }
        return view('animals.index', compact('animals'));
    }*/

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $enclosures = Enclosure::all();
        $species = Helper::all();
        return view('animals.form', compact('enclosures', 'species'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnimalRequest $request)
    {
        //
        $validated = $request->validated();
        if ($request->hasFile('picture')) {
            $imagePath = $request->file('picture')->store('images', 'public'); 
            $validated['picture'] = $imagePath;
        }
        
        $isPredator = Helper::isPredator($validated['species']);
        $enclosure = Enclosure::find($validated['enclosure_id']);
        if ($isPredator && !$enclosure->is_predator || !$isPredator && $enclosure->is_predator) {
            return back()->with('error', 'Predators/not predators can only be placed in predator/not predator enclosures.');
        }

        $animal = Animal::create($request->validated());

        return redirect()->route('animals.show', $animal->id)->with('success', 'Animal created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Animal $animal)
    {
        //
        
        $animal = Animal::find($animal->id);

        return view('animals.show', compact('animal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Animal $animal)
    {
        //
        if (request()->user()->admin === false) {
            return redirect()->route('home')->with('error', 'You are not authorized to edit an animal.');
        }
        $animal = Animal::find($animal->id);
        $enclosures = Enclosure::all();
        $species = Helper::all();
        return view('animals.form', compact('animal', 'enclosures', 'species'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnimalRequest $request, Animal $animal)
    {
        //
        $validated = $request->validated();
        
        $animal = Animal::find($animal->id);
        if ($request->hasFile('picture')) {
            if ($animal->picture) {
                \Storage::disk('public')->delete($animal->picture);
            }
            $imagePath = $request->file('picture')->store('images',  'public'); 
            $validated['picture'] = $imagePath;
        } else {
            $validated['picture'] = $animal->picture;
        }
        $animal->update($validated);
        return redirect()->route('animals.show', $animal->id)->with('success', 'Animal updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Animal $animal)
    {
        //

        $animal->delete();
        return redirect()->route('home')->with('success', 'Animal deleted successfully.');
    }
}
