<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profolio;
use App\Models\View;
use App\Models\Like;


class ProfolioController extends Controller
{
    public function profolio_page(){

        return view('profolio.post_project');
    }

    public function post_project(Request $request){
        $sitename = $request -> name;
        $sitelink = $request -> link;
        $sitelanguage = $request -> language;
        $siteimage = $request -> image;

        $project_db = new Profolio;
        $project_db -> name = $sitename;
        $project_db -> link = $sitelink;
        $project_db -> language = $sitelanguage;
        

        if($siteimage){
            $image_name = time().'.'.$siteimage->getClientOriginalExtension();
            $request->image->move('project_image', $image_name);

            $project_db->image = $image_name;
        }

        $project_db ->save();
        return redirect('http://localhost:5173/post_project');
        /*return response()->json([
            "status" => 200,
            "message"=> "taarifa zime ifadhiawa kwa db",
        ]);*/
    }

    public function get_project(){
        $projects = Profolio::withCount(['like','view'])->limit(8)->get();
        return response()->json([
            "status" => 200,
            "all_project" => $projects,
        ]);
    }

    public function detect_view (Request $request){
            $view = "view";
            $post_id = $request->project_id;

            $view_db = new View;
            $view_db->view = $view;
            $view_db->project_id = $post_id;
            $view_db->save();
            return response()->json("ime tambua kuwa kuna mtu ka view site");
    }
    public function count_view($id){
        $viewer = View::where('project_id', $id)->count();
        return response()->json($viewer);
    }

    public function detect_like(Request $request){
            $post_id = $request->project_id;
            $like = "like";
            $like_db = new Like;
            $like_db->like = $like;
            $like_db->project_id = $post_id;
            $like_db->save();
            return response()->json("ime tambua kuwa kuna mtu ka like project");
    }

    public function count_like($id){

        $liker = Like::where('project_id', $id)->count();
        return response()->json($liker);

    }
    public function search(Request $request){
            $name = $request->query('query');
            $product = Profolio::withCount(['like','view'])->where('name', 'like', "%{$name}%")->get();
            return response()->json(['project' => $product,'name'=> $name]);
        }
    
}



