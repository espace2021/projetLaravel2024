<?php

namespace App\Http\Controllers;
use App\Models\Scategorie;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScategorieController extends Controller
{
    public function index()
    {
        $scategories = Scategorie::with('categories')->get()->toArray();
        return array_reverse($scategories);  
    }

    
    public function store(Request $request)
    {
        $scategorie = new Scategorie([
            'nomscategorie' => $request->input('nomscategorie'),
            'imagescategorie' => $request->input('imagescategorie'),
            'categorieID' => $request->input('categorieID'),
        ]);
        $scategorie->save();

        return response()->json('S/Categorie créée !');
    }

    public function show($id)
    {
        $scategorie = Scategorie::find($id);
        return response()->json($scategorie);
    }

    

    public function update(Request $request, $id)
    {
        $scategorie = Scategorie::find($id);
        $scategorie->update($request->all());

        return response()->json('S/Catégorie MAJ !');
    }

    public function destroy($id)
    {
        $scategorie = Scategorie::find($id);
        $scategorie->delete();

        return response()->json('Scategorie supprimée !');
    }

    public function showSCategorieByCAT($idcat)
    {
     $Scategorie= Scategorie::where('categorieID', $idcat)->with('categories')->get()->toArray();
        return response()->json($Scategorie);
    }

}
