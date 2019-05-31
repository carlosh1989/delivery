<?php

namespace App\Http\Controllers;
use App\User;
use App\FeaturesCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    
    function index(Request $request){
        
        if ($request->isJson()) {

            $featuresCategories = DB::table('features_categories')->paginate(15);
            return response()->json( $featuresCategories, 200 );
        }
         
        return response()->json(['error','Unauthorized'] , 401 , []);
    }

    function getCategoryByTalent(Request $request, $talent){
    
    
        // if ($request->isJson()) {
            
            $featuresCategories = DB::table('features_categories')
                ->where('featureGroup', $talent )
                ->paginate(15);

            return response()->json( ($featuresCategories->count()) ? $featuresCategories : [] , 200 );
        // }
         
        // return response()->json(['error','Unauthorized beibe'] , 401 , []);
    }

    function createCategory(Request $request){
        
        // Params : array[string,string,string]

        if ($request->isJson()) {
            // TODO: Create the category in the DB
            $data = $request->json()->all();
            
            $category = FeaturesCategories::create([
                'featureCategoryName'           => $data['featureCategoryName'],
                'featureCategoryDescription'    => $data['featureCategoryDescription'],
                'featureGroup'                  => $data['featureGroup']
            ]);
            
            return response()->json( $category , 201 );
        }
         
        return response()->json(['error','Unauthorized'] , 401 , []);
    }

    function updateCategory(Request $request, $id){
          
          if ($request->isJson()) {
            
            $data = $request->json()->all();
            // return $data;
            $category = FeaturesCategories::find($id);            
            
            $category->featureCategoryName = $data['featureCategoryName'];
            $category->featureCategoryDescription = $data['featureCategoryDescription'];
            $category->featureGroup = $data['featureGroup'];

            $category->save();

            return response()->json( $category , 201 );
        }
         
        return response()->json(['error','Unauthorized'] , 401 , []);
    }


}
