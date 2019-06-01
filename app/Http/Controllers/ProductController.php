<?php

namespace App\Http\Controllers;

/* Models */
use App\Http\Controllers\FileController;
use App\Products;
use App\ProductsGallery;
use App\User;
use App\UsersVideos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/* Views support*/
// use Illuminate\Support\Facades\View;

/* Mail support*/
// use \Swift_Mailer;
// use \Swift_SmtpTransport;
// use \Swift_Message;

class ProductController extends Controller
{
	public function create(Request $request)
	{
		$product = Products::create([
			'product_name' => $request->input('product_name'),
			'price' 	=> $request->input('price'),
			'enable'    => $request->input('enable'),
			'company_profile_id' => $request->input('company_profile_id'),
			'tax' => $request->input('tax')
		]);

		foreach ($request->file('photos') as $key => $photo) 
		{
			$upload = ImageController::upload($photo);

			ProductsGallery::create([
				'products_id' => $product->id,
				'image' => $upload
			]);
		}

		return response()->json(['status'=>'Added product'],201);
		//return $request->input('tax');
	}

    public function create2(Request $request)
    {
        $data = $request->json()->all();
        $file = $request->file('file');

		$upload = FileController::upload($file);

		return $upload;
    }

    public function productsCompany(Request $request, $id)
    {
 	  $Products = Products::select('id','product_name','price','enable','tax')
 	  ->where('company_profile_id',$id)
 	  ->with('products_gallery:id,products_id,image')
 	  ->get();

      return $Products;
    }
}
