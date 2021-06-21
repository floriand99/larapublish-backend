<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Http\Requests\StoreArticleRequest;
use App\Services\ReadingTimeService;

class ArticleController extends Controller
{

    public function __construct(){
        $this->middleware('auth:api')->only('store', 'update', 'destroy', 'toggleLike');
    }

    private function getByFilter($filter, $baseQuery){
        switch($filter){
            case 'popular':
                $articles = $baseQuery->popular()->with(['user', 'tags'])->withCount('likes')->paginate(15);
                break;
            default:
                $articles = $baseQuery->newest()->with(['user', 'tags'])->withCount('likes')->paginate(15);
                break;

        }
        return $articles;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $articles = $this->getByFilter($request->query('orderBy'), new Article());
        return $articles;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArticleRequest $request)
    {
        $data = $request->validated();
        $article = Article::make([
            'title' => $data['title'],
            'content' => $data['content'],
        ]);
        $article->user_id = auth()->user()->id;
        if($request->has('image'))
            $article->image = $request->image;
        $numOfWords = str_word_count($request->content);
        $article->reading_time = ReadingTimeService::calculate($numOfWords);
        $article->save();
        foreach($request->tags as $tag){
            $tag = \App\Models\Tag::firstOrCreate(['content' => $tag]);
            $article->tags()->attach($tag);
        }
        return $article;
    }

    /**;
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::with(['user', 'tags'])->withCount('likes')->findOrFail($id);
        $liked = $article->likes()->where('user_id', auth()->user()->id)->first();
        $article->liked = $liked ? true : false;
        return $article;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreArticleRequest $request, Article $article)
    {
        $article->fill($request->validated());
        $article->save();
        return $article;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();
    }

    public function search(Request $request, $query){
        $articles = $this->getByFilter($request->query('orderBy'), Article::where('title', 'LIKE', "%{$query}%"));
        return $articles;
    }

    public function toggleLike($id){
        $article = Article::findOrFail($id);
        $liked = $article->likes()->where('user_id', auth()->user()->id)->first();
        if($liked){
            $article->likes()->detach(auth()->user());
            $liked = false;
        }else{
            $article->likes()->attach(auth()->user());
            $liked = true;
        }
        return [
            'likes_count' => \App\Models\Like::where('article_id', $article->id)->count(),
            'liked' => $liked
        ];
    }

    public function getLikes($id){
        $article = Article::findOrFail($id);
        $liked = $article->likes()->where('user_id', auth()->user()->id)->first();
        return [
            'likes_count' => $article->likes()->count(),
            'liked' => $liked ? true : false,
        ];
    }

    public function getUserArticles(Request $request, $userId){
        $baseQuery = Article::where('user_id', $userId);
        $articles = $this->getByFilter($request->query('orderBy'), $baseQuery);
        return $articles;
    }

    public function getByTag(Request $request, $tagId){
        $baseQuery = \App\Models\Tag::findOrFail($tagId)->articles();
        $articles = $this->getByFilter($request->query('orderBy'), $baseQuery);
        return $articles;
    }
}