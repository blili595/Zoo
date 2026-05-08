<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Http\Requests\StoreEnclosureRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Enclosure;


class ApiController extends Controller
{
    //

    public function getEnclosures(Request $request, string|null $id = null) {
    
        if($id){
            return Enclosure::findOrFail($id);
        }
        return Enclosure::all();
    }

    
    public function store(StoreEnclosureRequest $request) {
        $enclosure = Enclosure::create($request->validated());
        return response()->json($enclosure, 201);
        
    }
    public function update(Request $request, $id) {
        $validated = $request->validate([
            "name" => 'required|string|max:255|unique:enclosures,name',
            "limit" => 'required|integer',
            "feeding_at" => 'required|date_format:H:i',
            "is_predator" => 'required|boolean'
        ]);
        $enclosure = Enclosure::findOrFail($id);
        $enclosure->update($validated);
        return response()->json($enclosure, 201);
        
    }
    public function destroy(Request $request, $id) {
        $enclosure = Enclosure::findOrFail($id);
        //ellenorzes, hogy a felhasznalo admin-e
        if(!$request->user()->tokenCan('admin')){
            return response()->json(['error' => 'You are not authorized to delete this enclosure'], 403);
        }
        $enclosure->delete();
        return response()->json(status: 204);
        
    }




    public function register(Request $request){
        //validalas
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5|confirmed',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->messages(), 400]);
        }
        //elmentes
        $validated = $validator->validate();
        $user = User::create($validated);
        return response()->json($user);
    }

    public function login(Request $request){
        //validalas
        $validator = Validator::make($request->all(), [
            
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|confirmed',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->messages(), 400]);
        }
        //elmentes
        
        $validated = $validator->validate();

        $user = User::where('email', $validated['email'])->first();
        if(!$user){
            return response()->json(['error' => 'User not found'], 404);
        }
        if(Auth::attempt($validated)){
            $token = $user->createToken($user->email, $user->isAdmin() ? ['admin'] : ['*']);
           return response()->json(['token' => $token->plainTextToken]);
        } else{
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        return response()->json($user);
    }

    
    public function logout(Request $request){
        
        $request->user()->currentAccessToken()->delete();
        return response()->json([], 204);
    }

    
    public function user(Request $request){

       return $request->user();
    }
    public function relatedPosts(Request $request, $id){
        $post = Post::findOrFail($id);
        $categories = $post->categories;
        $relatedPosts = collect([]);
        foreach($categories as $category){
            $relatedPosts = $relatedPosts->concat($category->posts);
        }
        $relatedPosts = $relatedPosts->unique('id')->sortBy('id')->values()->all();
        return response()->json($relatedPosts);
    }

}
