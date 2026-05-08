

CRUD Enclosure modellre

Read: 
GET /enclosures     //mindegyik lekérdezése
GET /enclosures/:id //egy adott id 

Create:
POST /enclosures    //uj letrehozasa

Update:
PUT /enclosures/:id     //modositas - teljes objektum
PATCH /enclosures/:id   //modositas - mezok egy reszet

Delete:
DELETE /enclosures/:id  //torles



---------------------------- php artisan install:api --------------------------------------------------------


 ** ** ** ** ** ** authentikacios ** ** ** ** ** **

Route::post('/register', [ApiController::class, 'register'])->name('api.register');
Route::post('/login', [ApiController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiController::class, 'logout'])->name('api.logout');
    Route::get('/user',[ApiController::class, 'user'])->name('api.user');
});

 ** ** ** ** ** ** muveletek ** ** ** ** ** **

 
Route::get('enclosures/{id?}', [ApiController::class, 'getEnclosures'] )->name('api.enclosures.get')->where('id', '[0-9]+');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('enclosures/', [ApiController::class, 'store'] )->name('api.enclosures.store');
    Route::put('enclosures/{id}', [ApiController::class, 'update'] )->name('api.enclosures.update');
    Route::delete('enclosures/{id}', [ApiController::class, 'destroy'] )->name('api.enclosures.destroy');
    
});



 ---------------------------- php artisan make:controller ApiController -----------------------------------------------------------

 ** ** ** ** ** ** nullable ** ** ** ** ** ** **

public function getEnclosures(Request $request, string|null $id = null)...

** ** ** ** ** ** ** validalas ** ** ** ** ** ** **

 $validated = $request->validate([
    "name" => 'required|string|max:255|unique:enclosures,name',
    ...
]);
$enclosure->update($validated);

** ** ** ** ** ** ha csak admin teheti meg ** ** ** ** ** ** 

if(!$request->user()->tokenCan('admin')){
    return response()->json(['error' => 'not authorized'], 403);
}
    $enclosure->delete();
    return response()->json(status: 204);


 ** ** ** ** ** specifikus request el validálunk: ** ** ** ** **

public function store(StoreEnclosureRequest $request)
{
    $enclosure = Enclosure::create($request->validated());
    return response()->json($enclosure, 201);
}


** ** ** ** ** ** validálás Validator al ** ** ** ** ** ** **

$validator = Validator::make($request->all(), [
    'name' => 'required|string|max:255',
    'email' => 'required|string|email|max:255|unique:users',
    'password' => 'required|string|min:5|confirmed',
]);
if($validator->fails()){
    return response()->json(['error' => $validator->messages(), 400]);
}

 ** ** ** ** ** ** elmentes regisztracional ** ** ** ** ** ** **

$validated = $validator->validate();
$user = User::create($validated);
return response()->json($user);

** ** ** ** ** ** bejelentkezes login nal: ** ** ** ** ** ** **

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




** ** ** ** ** ** logout ** ** ** ** ** ** ** ** ** ** ** ** **

 $request->user()->currentAccessToken()->delete();
 return response()->json([], 204);



 ---------------------------- php artisan make:middleware ValidateParams --------------------------------------------------------

public function handle(Request $request, Closure $next): Response
{
    $number = $request->route()->parameters['number'];
    ...
    $errors = [];
        if(!filter_var($number, FILTER_VALIDATE_INT)){
            $errors[] = 'number is not int';
        }
        ...
        if(!empty($errors)){
            return response()->json(['errors' => $errors], 418);
        }
        return $next($request);

}

 ** ** ** ** ** ** api.php: ** ** ** ** ** **

Route::get('uri-params2/{number}/{string}/{optional?}', function($number, $string, $optional = null) {
    return response()->json([
        'number' => $number,
        'string' => $string,
        'optional' => $optional
        ]);
})->middleware(ValidateParams::class);
ehelyett :
->where('number', '[0-9]+')->where('string', '[a-zA-Z0-9]+');



 ---------------------------- php artisan make:request StoreEnclosureRequest --------------------------------------------------------


public function authorize(): bool
{
    $post = Post::findOrFail($this->route('id'));
    return $this->user()->tokenCan('admin') ||
     ($post->author != null && 
     $post->author->id == $this->user()->id);
}

public function rules(): array
{
    return [
        'title' => 'required|min:5',
        ...
    ]
}



 ---------------------------- php artisan make:resource PostResource --------------------------------------------------------


 public function toArray(Request $request): array 
 {
    return [
        'id' => $this->id,
        'title' => $this->title,
        'categories' => CategoryResource::collection($this->whenLoaded('categories')),
    ];
 }


 ** ** ** ** ** ** ApiController.php ben: ** ** ** ** ** **
...
return new PostResource(Post::findOrFail($id));
** ** ** ** ** ** vagy
return PostResource::collection(Post::all());
** ** ** ** ** ** vagy (ha odavissza): 
return PostResource::collection(Post::with('categories')->findOrFail($id));
return PostResource::collection(Post::with('categories')->get());



