<?php

namespace App\Http\Controllers;
use App\User;
use App\FeaturesCategories;
use App\FeaturesLabels;
use App\FeaturesValues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FeaturesController extends Controller
{
    
    function index(Request $request){
        
        if ($request->isJson()) {

            $featuresCategories = DB::table('features_categories')->paginate(15);
            return response()->json( $featuresCategories, 200 );
        }
         
        return response()->json(['error','Unauthorized'] , 401 , []);
    }


     function getLabelsBycategory(Request $request, $category_id){
    
            $features = new FeaturesLabels;
           
                $features = FeaturesLabels::
                select('id','features_categories_id', 'featureLabel')
                ->where( 'features_categories_id','=', $category_id )
                ->get();

            return response()->json( ($features->count()) ? $features : [] , 200 );
    }


     function getValuesByLabel(Request $request, $features_labels_id){

                $values = FeaturesValues::
                select('id', 'featureValue')
                ->where( 'features_labels_id','=', $features_labels_id )
                ->get();

            return response()->json( ($values->count()) ? $values : [] , 200 );
    }



    function getFeaturesByCategory(Request $request, $category_id){
    
        if ($request->isJson()) {
            $features = new FeaturesLabels;
           
                $features = FeaturesLabels::
                select('id','features_categories_id', 'featureLabel')
                ->with(array('features_values' => function($query){
                    $query->select('id','features_labels_id','featureValue','created_at');
                }))
                ->where( 'features_categories_id','=', $category_id )
                ->get();

            return response()->json( ($features->count()) ? $features : [] , 200 );
        }
         
        return response()->json(['error','Unauthorized'] , 401 , []);
    }


    function createFeatures(Request $request){
        
        if ($request->isJson()) {
            
            $data = $request->json()->all();
            $featuresList = $data['features'];

            foreach($featuresList as $f )
            {
                $featureLabelId = FeaturesLabels::create([
                    'features_categories_id' => $data['features_categories_id'],
                    'featureLabel'           => $f['featureLabel']
                ]);

                foreach ($f['collection']['values'] as $value) {

                    FeaturesValues::create([
                        'features_labels_id' => $featureLabelId->id,
                        'featureValue'           => $value,
                    ]);
                }
            }

            return response()->json([],201);
        }
         
        return response()->json(['error','Unauthorized'] , 401 , []);
    }

            
    function updateFeatureLabel(Request $request, $id){
          
          if ($request->isJson()) {
            
            $data = $request->json()->all();
            // return $data;
            $feature = FeaturesLabels::find($id);            
            $feature->featureLabel = $data['featureLabel'];
            $feature->save();
            return response()->json( $feature , 201 );
        }
         
        return response()->json(['error','Unauthorized'] , 401 , []);
    }

    function updateFeatureValue(Request $request, $id){
          
          if ($request->isJson()) {
            
            $data = $request->json()->all();
            // return $data;
            $feature = FeaturesValues::find($id);            
            $feature->featureValue = $data['featureValue'];
            $feature->save();
            return response()->json( $feature , 201 );
        }
         
        return response()->json(['error','Unauthorized'] , 401 , []);
    }
}
