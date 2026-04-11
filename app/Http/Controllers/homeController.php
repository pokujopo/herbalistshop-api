<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Banner_image;
use App\Models\User;
use App\Models\Post;
use App\Models\Cart;
use App\Models\Test;
use App\Models\Comments;
use Dom\Comment;

use Illuminate\Http\Request;

class homeController extends Controller
{
       public function home(){
           $posts = Post::orderBy('created_at', 'desc')->take(20)->get();
            $posts_asc = Post::orderBy('created_at', 'asc')->take(20)->get();
            $banner_images = Banner_image::first();
            return view('customer.home', compact('posts','posts_asc',  'banner_images'));
           
       }
       public function index(){
           
            //return view('customer.home', compact('posts','posts_asc',  'banner_images'));
                    
        if (Auth::id()){
            $posts = Post::orderBy('created_at', 'desc')->take(20)->get();
            $posts_asc = Post::orderBy('created_at', 'asc')->take(20)->get();
            $banner_images = Banner_image::first();
            $posts = Post::all();

            
            //return response()->json([$banner_images]);
            $usertype =  Auth::user()->usertype;

            if ($usertype == 'admin'){
                return view('customer_care.show_product', compact('posts'));
            }
            elseif ($usertype == 'user'){    
                return view('customer.home', compact('posts','posts_asc',  'banner_images'));

              }
            else{
                return redirect()->back();
            }} 
        else{
                return redirect()->back();
            }
        }


        public function delete_cart($id){
            $delete_cart = Cart::find($id);
            $delete_cart->delete();
            return redirect()->back();
        }

        public function delete_grouped_cart(){

           $delete_cart = Cart::all();
           $delete_cart->remove();
           return redirect()->back();

        }

        public function see_cart_product(){
            #$users = User::find($id);
            //$posts = Post::find($id);
            //$carts = Cart::all();
            $userId = Auth::id();
            $cartItems = Cart::where('cart.user_id', $userId)
            ->join('posts', 'cart.product_id', '=', 'posts.id')
            ->select('cart.*', 'posts.price', 'posts.image', 'posts.product')->get();

            $groupedItems = $cartItems-> groupBy('product_id')->map(function ($group){
                $items = $group->first();
                $items->quantity = $group->count();
                return $items;

            });

            $cartCount = $groupedItems->count();

          /*  return response()-> json([
                'massege' => 'tumia hizi taarifa kuonyesha cart in shopping cart page',
                'groupedItems'=> $groupedItems,
                'cartCount' => $cartCount

            ]);
            */ 

            return view('customer.see_cart_product', compact('groupedItems', 'cartCount'));
        }

        public function search(Request $request){
            $name = $request->query('query');
            $product = Post::where('product', 'like', "%{$name}%")->get();
            return response()->json(['product' => $product,'name'=> $name]);
        }

        public function shop(){
            $products = Post::orderBy('created_at', 'desc')->take(5)->get();
            return view('customer.shop', compact('products'));
        }

        public function shop_category($category){
            $products = Post::where('category', $category)->get();
            return view('customer.shop_category', compact('products'));
        }

        public function virutubisho_category(Request $request){
            $virutubisho_request = $request-> virutubisho;
            $virutubisho = Post::where('category', 'virutubisho')->get();
            return response()->json([$virutubisho_request]);
            return view('customer.shop_change', compact('virutubisho'));
            
            #return redirect()->back();
        }

        public function get_post(Request $request){

                $limits = request('limit', 5);
                return Post::latest()->take($limits)->get();

        }

        public function get_product_price(Request $request){
            $price = $request -> price;
            $limit = $request -> limit ?? 2;

            $posts = Post::where('price', '<=', $price)->get();
            return response()->json(['posts'=>$posts]);
           // if ($)

        }

        public function get_post_rate(Request $request){
            $rate = $request -> rate;
            $limit = $request -> limit ?? 2;
            if ($rate == 'newPost'){

                $see_new_posts = Post::orderBy('created_at', 'desc')->take($limit)->get();
                return response()->json(['posts' => $see_new_posts]);
            } elseif ($rate == 'olderProduct'){
               // $see_commented_posts = Comment::where('product_id');
                $see_older_posts = Post::orderBy('created_at', 'asc')->take($limit)->get();
                return response()->json(['posts' => $see_older_posts]);


            }elseif ($rate == 'popular'){
                
                $see_commented_posts = Comments::join('posts', 'comments.product_id', '=', 'posts.id')
                ->select('comments.*', 'posts.product', 'posts.image', 'posts.price', 'posts.description', 'posts.product', 'posts.id')->get();

                $groupedProduct = $see_commented_posts->groupBy('product_id')->map(function ($group){ 
                    $products = $group->first(); return $products;
            })->values();

                //Post::has('comments')->get();
                return response()->json(['posts'=>$groupedProduct]);
            }else{
                return response()-> json(['message'=> 'notFound...404']);
            }
        }
       
        public function get_post_category(Request $request){

                $limit = $request-> limit ?? 6;
                $category = $request-> category;
                
                $query = Post::query();
                if ($category && $category != 'all'){
                    $query->where('category', $category);
                    $posts = $query->limit($limit)->get();
                    return response()->json(['posts' => $posts]);

                }else{
                   $posts = Post::all();
                   return response()->json(['posts' => $posts]);

                }
               
        }

    

        public function product_info($id){
            $products = Post::all();
            $posts = Post::find($id);

            //$comments = Comments::where('product_id', $id)->get();
            return view('customer.product_info', compact('posts', 'products'));
        }

        public function get_comments($id){
            //$posts = Post::find($id);
            $comments = Comments::where('product_id', $id)->latest()->get();
            $count_com = Comments::where('product_id', $id)->count();
            return response()->json(['comments'=> $comments, 'count'=> $count_com]);
        }

        public function post_comment(Request $request){
               # $post = Post::product();
                $user = Auth::user();
                $user_id = $user->id;
                $name = $user->name;
                $usertype = $user->usertype;

                $comments = new Comments;
                $comments->comment = $request->comment;
                $comments->product_id = $request->product_id;
                $comments->product_name = $request->product_name;
                $comments->username = $name;
                $comments->user_id = $user_id;
                $comments->usertype = $usertype;

                $comments->save();
                return response()->json(['status'=> 'successfully']);

        }

        public function add_cart_btn(Request $request){

            //$product_id = $request->product_id;

            $user = Auth::user();
            $user_id = $user->id;
            //$email = $user->email;
            $name = $user->name;
            $usertype = $user->usertype;

            $cart = new Cart;
            $cart->product_id = $request->product_id;
            $cart->user_id = $user_id;
            $cart->username = $name;
          //  $cart->useremail = $email;
            $cart->usertype = $usertype;

            $cart->save();



            return response()-> json([
                'message' => 'your cart zime ifadhiwa kwenye database na kuonyesha page ya shopping cart',
                'product_cart_id' => $cart
            ]);

        }

        public function show_cart(){
            $carts = Cart::all();
            return response()-> json([
                'massege' => 'tumia hizi taarifa kuonyesha cart in shopping cart page',
                'all_cart'=> $carts
            ]);
        }

        public function checkout_page(){
             $userId = Auth::id();
            $cartItems = Cart::where('cart.user_id', $userId)
            ->join('posts', 'cart.product_id', '=', 'posts.id')
            ->select('cart.*', 'posts.price', 'posts.image', 'posts.product')->get();

            $groupedItems = $cartItems-> groupBy('product_id')->map(function ($group){
                $items = $group->first();
                $items->quantity = $group->count();
                return $items;

            });

            $cartCount = $groupedItems->count();

          /*  return response()-> json([
                'massege' => 'tumia hizi taarifa kuonyesha cart in shopping cart page',
                'groupedItems'=> $groupedItems,
                'cartCount' => $cartCount

            ]);
            */ 

            return view('customer.checkout', compact('groupedItems', 'cartCount'));
           
        }

        public function posts_api(){
            $posts  = Post::all();
            return response()->json([
                "status"=> 200,
                "results"=> $posts,
            ]);
        }

        public function search_api(Request $request){
            $name = $request->query('query');
            $product = Post::where('product', 'like', "%{$name}%")->get();
            return response()->json(['product' => $product,'name'=> $name]);
        }


        public function test_post(Request $request){
                $username = $request-> name;
                $email = $request-> email;
                $password = $request-> password;

                $test_db =  new Test;
                $test_db -> username = $username;
                $test_db -> email = $email;
                $test_db -> password = $password;
                $test_db->save();

                return redirect()->back();

               /* return  response()->json([
                    "status"=> 200,
                    "message"=> "taarifa zime ifadhiwa kwa db",
                    "username"=> $username,
                    "email"=> $email,
                    "password"=> $password,
                ]);*/
        }

    };


