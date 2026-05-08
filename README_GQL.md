composer require nuwave/lighthouse

php artisan vendor:publish --tag=lighthouse-schema

composer require mll-lab/laravel-graphiql

composer require laravel/telescope

php artisan telescope:install
php artisan migrate

--- rules ---
php artisan lighthouse:query RelatedPosts

-- login mutation -- 
php artisan lighthouse:mutation login






type Post{
    id: ID!
    title: String!
    description:String!
    text:String!
    author: User @belongsTo ---vagy---  user: User @belongsTo (relation:"author")
    categories: [Category] @belongsToMany
}

type Category{
  id: ID!
  name: String!
  style: Style!
  posts: [Post] @belongsToMany
}

** ** és a query-ben: ** ** 

posts: [Post!]! @all
post(id: Int! @eq): Post @find

categories: [Category!]! @all
category(id: Int! @eq) Category @find



** type User-ben: ** kiadja a user postjait ha van 

posts: [Post!]! @hasMany 


---------------- Mutations ----------------

type Mutation{
    createCategory(name: String, style: String!): Category @create
    ** * ha nem biztos hogy jo a parameter: * **
    createCategory1(name: String, style: Style!): Category @create
    createCategory2(input: createCategoryInput @spread): Category @create
    updateCategory(id:ID!, name: String!, style:Style!): Category! @update
    deleteCategory(id:ID!) @whereKey: Category @delete
    upsertCategory(id:ID!, name: String!, style:Style!): Category! @upsert
    ** ** ** @rules ** ** ** 
    createUser(
        name: String! @rules(apply: ["unique:users,name"]), 
        email: String! @rules(apply: ["email", "max:20", "unique:users,email"]), 
        password: String!, ): User @create
    login(
        email:String! @rules(apply: ["email", "max:20", "unique:users,email"]),  
        password:String!) :String!



}

input createCategoryInput {
    name: String!
    style: Style!
}

** atirjuk a Category type ban is a style-t String ről Style ra ** 

enum Style{
    primary
    secondary
    success
    danger
    warning
    info
    dark
}



-------------- sajat rules --------------
** adott kategoriaba beleeso posztok ** 
graphql ben: 
** type query ben: **
relatedPosts(id:Int): [Post]

** RelatedPosts.php ban: **
public function __invoke(null $_, array $args){
    **
    $post = Post::findOrFail($args['id']);
    $categories = $post->categories;
    $relatedPosts = collect([]);
    foreach($categories as $category){
        $relatedPosts = $relatedPosts->concat($category->posts);
    }
    $relatedPosts = $relatedPosts->unique('id')->sortBy('id')->values()->all();
    return $relatedPosts;
}



------- login mutation ----------

** ** ** Login.php ban ** ** **
public function __invoke(null $_, array $args){
    $user = User::where('email', $args['email'])->first();
    if(!$user){
        return "nem letezo email";
    }
    if(Auth::attempt($args)){
        $token = $user->createToken($user->email, $user->isAdmin() ? ['admin'] : ['*']);
        return $token->plainTextToken;
    } else{
        return 'error' => 'Invalid credentials';
    }
    
}