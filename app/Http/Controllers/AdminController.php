<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{   
    //product
    public function addProduct(Request $request){
        //validate request
        $this->validate($request,[
            'productName' => 'required',
            'productImage' => 'required',
            'productDescription' => 'required',
            'productPrice' => 'required',
            'productQuantity' => 'required',
        ]);
        return Product::create([
            'productName'=>$request->productName,
            'productImage'=>$request->productImage,
            'productDescription' => $request->productDescription,
            'productPrice' => $request->productPrice,
            'productQuantity' => $request->productQuantity,
        ]);
        
    }
    public function editProduct(Request $request){
        //validate request
        $this->validate($request,[
            'productName' => 'required',
            'productImage' => 'required',
            'productDescription' => 'required',
            'productPrice' => 'required',
            'productQuantity' => 'required',
        ]);
        return Product::where('id', $request->id)->update([
            'productName'=>$request->productName,
            'productImage'=>$request->productImage,
            'productDescription' => $request->productDescription,
            'productPrice' => $request->productPrice,
            'productQuantity' => $request->productQuantity,

        ]);
        
    }
    public function deleteProduct(Request $request){
        //validate request
        $this->validate($request,[
            'id'=>'required',
        ]);
        return Product::where('id', $request->id)->delete();
        
    }
    public function activeProduct(Request $request){
        //validate request
        $this->validate($request,[
            'id'=>'required',
            'productActivated'=>'required',
        ]);
        return Product::where('id', $request->id)->update([
            'productActivated' => $request->productActivated,
        ]);
        
    }
    public function getProducts(){
        return Product::orderBy('id', 'desc')->get();
    }
    
    //category
    public function addCategory(Request $request){
        //validate request
        $this->validate($request,[
            'categoryName' => 'required|max:255',
            'iconImage' => 'required',
        ]);
        return Category::create([
            'categoryName'=>$request->categoryName,
            'iconImage'=>$request->iconImage,
        ]);
        
    }
    public function editCategory(Request $request){
        //validate request
        $this->validate($request,[
            'categoryName' => 'required|max:255',
            'iconImage' =>'required',
        ]);
        return Category::where('id', $request->id)->update([
            'categoryName'=>$request->categoryName,
            'iconImage'=>$request->iconImage,

        ]);
        
    }
    public function deleteCategory(Request $request){
        //validate request
        $this->validate($request,[
            'id'=>'required',
        ]);
        return Category::where('id', $request->id)->delete();
        
    }
    public function getCategories(){
        return Category::orderBy('id', 'desc')->get();
    }
    public function upload(Request $request){
        //validate
        $this->validate($request,[
            'file' => 'required|mimes:jpg,jpeg,png'
        ]);
        $imageName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads'), $imageName);
        return $imageName;
    }
    public function deleteImage(Request $request){
        $fileName   = $request->imageName;
        $this->deleteFileFromServer($fileName);
        return 'done';
    }
    public function deleteFileFromServer($fileName){
        $filePath = public_path().'/uploads/'.$fileName; 
        if(file_exists($filePath)){
            @unlink($filePath);
        }
        return;
    }
}
