<?php

namespace App\Http\Controllers;

use App\Models\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('scategories')->get()->toArray();
        $res = array_reverse($articles);
        return response()->json($res);
  
    }
    
        public function store(Request $request)
        {
            $article = new Article();
            $article->designation= $request->input('designation');
            $article->marque= $request->input('marque');
            $article->reference= $request->input('reference');
            $article->qtestock= $request->input('qtestock');
            $article->prix= $request->input('prix');
            $article->imageart= $request->input('imageart');
            $article->scategorieID= $request->input('scategorieID');
            
            $article->save();
    
            return response()->json($article);
        }
    
         
        public function show($id)
        {
            $article= Article::find($id);
            return response()->json($article);
        }
    
        public function update($id, Request $request)
        {
            $article = Article::find($id);
            $article->update($request->all());
    
            return response()->json($article);
    
        }
    
        public function destroy($id)
        {
            $article = Article::find($id);
            $article->delete();
    
            return response()->json(['message' => 'Article deleted successfully']);
    
        }

}
