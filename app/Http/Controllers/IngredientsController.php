<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\{
    Ingredient,
    AllIngredient
};
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class IngredientsController extends Controller
{

    /**
     * Store ingredients.
     * 
     * @return json
     */
    public function store(Request $request)
    {
        try {
            if(!$request->has('name')) {
                return response()->json(['error' => 'The name field is required'], 422);
            }

            $ingredients = $request->only('name')['name'];

            foreach($ingredients as $ingredient) {
                $allIngredients = new AllIngredient();
                $allIngredients->name = $ingredient;
                $allIngredients->save();
            }

        } catch(\Exception $e) {
            return ['error' => $e->getMessage(), 500];
        }

        return response()->json(['success' => 'Ingredients have been saved successfully.'], 200);
    }

    /**
     * Get all the ingredients.
     * 
     * @return json
     */
    public function index()
    {
        try {
            $ingredients = AllIngredient::all();

            if(!count($ingredients)) {
                return response()->json(['success' => 'No ingredients found.'], 200);
            }
        } catch(\Exception $e) {
            return ['error' => $e->getMessage(), 500];
        }

        return response()->json(['success' => $ingredients], 200);
    }

    public function show($id)
    {
        try {
            if(!is_numeric($id)) {
                return response()->json(['error' => 'The value provided is not an integer.']);
            }

            $ingredient = AllIngredient::findOrFail($id);

        } catch(ModelNotFoundException $e) {
            return ['error' => $e->getMessage(), 403];
        }

        return response()->json(['success' => $ingredient], 200);
    }
}