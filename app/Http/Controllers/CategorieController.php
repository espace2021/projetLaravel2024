<?php

namespace App\Http\Controllers;
use App\Models\Categorie;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::all()->toArray();
        return array_reverse($categories);  
    }

    public function store(Request $request)
    {
        $categorie = new Categorie([
            'nomcategorie' => $request->input('nomcategorie'),
            'imagecategorie' => $request->input('imagecategorie')
        ]);
        $categorie->save();

        return response()->json('Catégorie créée !');
    }

    public function show($id)
    {
        $categorie = Categorie::find($id);
        return response()->json($categorie);
    }

    public function update(Request $request, $id)
    {
        $categorie = Categorie::find($id);
        $categorie->update($request->all());

        return response()->json('Catégorie MAJ !');
    }

    
    public function destroy($id)
    {
        $categorie = Categorie::find($id);
        $categorie->delete();
        return response()->json('Catégorie supprimée !');
    }

}
