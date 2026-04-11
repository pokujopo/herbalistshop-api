<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;
use App\Models\Banner_image;

use Illuminate\Http\Request;

class adminController extends Controller
{
    public function admin_page(){
        $posts = Post::all();

        return view('customer_care.show_product', compact('posts'));
    }

    public function post_product_page(){
        $banner_id = 5;
        $show_banner_img = Banner_image::find($banner_id);
        //return response()->json($show_banner_img);
        return view('customer_care.post_product', compact('show_banner_img'));
    }

    public function post_product(Request $request){
        
        $user = Auth::user();
        $user_id = $user->id;
       //$name = $user->name;
        $usertype = $user->usertype;

        $posts = new Post;
        $posts->product = $request->product;
        $posts->price = $request->price;
        $posts->category = $request->category;
        $posts->description = $request->description;
        $posts->user_id = $user_id;
        //$posts->name = $name;
        $posts->usertype = $usertype;
        $image = $request->image;
        
        if($image){
            $image_name = time().'.'.$image->getClientOriginalExtension();
            $request->image->move('product_image', $image_name);

            $posts->image = $image_name;
        }

        $posts->save();
        return redirect()->back()->with('message', 'post data successfully');

    }

    public function show_product(){
        $posts = Post::all();
        
        return view('customer_care.show_product', compact('posts'));
 
    }

    public function delete_product($id){
        $posts = Post::find($id);
        $posts->delete();
        return redirect()->back();
    }

    public function update_page($id){

        $posts = Post::find($id);
        return view('customer_care.update_product_page', compact('posts'));


    }

    public function update_product(Request $request, $id){
        $posts = Post::find($id);

        $posts->product = $request->product;
        $posts->price = $request->price;
        $posts->category = $request->category;
        $posts->description = $request->description;

        $image = $request->image;
        
        if($image){
            $image_name = time().'.'.$image->getClientOriginalExtension();
            $request->image->move('product_image', $image_name);

            $posts->image = $image_name;
        }

        $posts->save();
        return redirect()->back()->with('message', 'update product successfully');

    }

    public function change_banner(Request $request){
        $user = Auth::user();
        $user_id = $user -> id;
        $usertype = $user -> usertype;
        $banner_id = 3;

        $banner_image = Banner_image::find($banner_id);
        $image_one = $request -> image_one;
        if($image_one){
            $image_name = time().'.'.$image_one->getClientOriginalExtension();
            $request->image_one->move('banner_image', $image_name);

            $banner_image->image_one = $image_name;
        }
        $image_two = $request -> image_two;
        if($image_two){
            $image_name = time().'.'.$image_two->getClientOriginalExtension();
            $request->image_two->move('banner_image', $image_name);

            $banner_image->image_two = $image_name;
        }
        $image_three = $request -> image_three;
        if($image_three){
            $image_name = time().'.'.$image_three->getClientOriginalExtension();
            $request->image_three->move('banner_image', $image_name);

            $banner_image->image_three = $image_name;
        }
        $image_offer = $request -> image_offer;
        if($image_offer){
            $image_name = time().'.'.$image_offer->getClientOriginalExtension();
            $request->image_offer->move('banner_image', $image_name);

            $banner_image->image_offer = $image_name;
        }
        $banner_image-> user_id = $user_id;
        $banner_image-> usertype = $usertype;

        $banner_image->save();
        return redirect()->back();

    }


    
}

