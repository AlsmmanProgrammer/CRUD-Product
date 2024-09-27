<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\Product;

class ProductsController extends Controller
{

    public function index(){
        $products=Product::orderBy('created_at','DESC')->get();
        return view ('products.index',[
            'products' => $products

        ]);

    }

    public function create(){
        return view ('products.create');



    }

    public function store(Request $request){
        $rules=[
            'name'  =>  'required|min:5',
            'sku'   =>  'required|min:3',
            'price' =>  'required|numeric:3'
        ];
        if($request->image != "") {

            $rules['image'] ='image';

        }

            $validator = Validator::make($request->all(),$rules);

        if ( $validator->fails()) {
            return redirect()->route('products.create')->withInput()->withErrors( $validator);
        }



        // //  plan1 for store
        //  Product::create([
        //  'name'        => $request->input('name'),
        // 'sku'         => $request->input('sku'),
        //  'price'       =>$request->input('price'),
        // 'description' =>$request->input('description'),

        //  ]);


        //  plan2 for store
        $product  =  new Product();
        $product->name = $request->name;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->save();

        if($request->image != "") {

        //here for store image name or path
        $image=$request->image;
        $ext=$image->getCLientOriginalExtension();
        $imageName=time().'.'.$ext;//unique image name

        //Save image in  products directory
        $image->move(public_path('images'), $imageName);


        //Save image name in database
        $product->image =  $imageName;
        $product->save();

        }
            return redirect()->route('products.index')->with('success', 'Products added Successfully.' );

    }

    public function edit($id){
        $product=Product::findOrFail($id);
        return view ('products.edit',[
            'products' => $product
        ]);

    }

    public function update($id,Request $request){
        $product=Product::findOrFail($id);

        $rules=[
            'name'  =>  'required|min:5',
            'sku'   =>  'required|min:3',
            'price' =>  'required|numeric:3'
        ];
        if($request->image != "") {

            $rules['image'] ='image';

        }

            $validator = Validator::make($request->all(),$rules);
                if ( $validator->fails()) {
                    return redirect()->route('products.edit',$product->$id)->withInput()->withErrors( $validator);
            }



        //  plan1 for update
        //  Product::update([
        //  'name'        => $request->input('name'),
        // 'sku'         => $request->input('sku'),
        //  'price'       =>$request->input('price'),
        // 'description' =>$request->input('description'),
        //  ]);

        //  plan2 for update
        $product->name = $request->name;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->save();


        if($request->image != "") {
            //delete old image
            File::delete(public_path('images/'. $request->image));

        //here for update image name or path
        $image=$request->image;
        $ext=$image->getCLientOriginalExtension();
        $imageName=time().'.'.$ext;//unique image name

        //Save image in  products directory
        $image->move(public_path('images'), $imageName);


        //Save image name in database
        $product->image =  $imageName;
        $product->save();
        }

        return redirect()->route('products.index')->with('success', 'Products updated Successfully.' );
    }

    public function destroy($id){

        $product=Product::findOrFail($id);

           //delete old image
            File::delete(public_path('images/'. $product->image));

           //delete product from database
            $product->delete();

            return redirect()->route('products.index')->with('success', 'Products deleted Successfully.' );


    }

}
