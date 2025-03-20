<?php

namespace App\Http\Controllers\backend\system;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SlideController extends Controller
{
    public function index()
    {
        $items = Slide::orderBy("id","desc")->get();
        return view("backend.system.slide.index", compact("items"));
    }

    public function create()
    {
        return view("backend.system.slide.create");
    }

    public function store(Request $request)
    {
        $data = [
            'title' => $request->title,
            'sub_title'=> $request->sub_title,
            'alias'=> $request->alias,
            'image'=> $request->image,
            'order'=> $request->order,
            'description'=> $request->description,
        ];
        $create = new Slide();
        $create->create($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Thêm mới slide thành công"]);
        return redirect()->route("slide.index");
    }

    public function edit(string $id)
    {
        $edit = Slide::findOrFail($id);
        return view("backend.system.slide.edit", compact("edit"));
    }

    public function update(Request $request, string $id)
    {
        $data = [
            'title' => $request->title,
            'sub_title'=> $request->sub_title,
            'alias'=> $request->alias,
            'image'=> $request->image,
            'order'=> $request->order,
            'description'=> $request->description,
        ];
        $edit = Slide::where('id', $id)->first();
        $edit->update($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Cập nhật slide thành công"]);
        return redirect()->route("slide.index");
    }

    public function destroy($id)
    {
        $destroy = Slide::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa Slide thành công']);
        } else {
            return response()->json(['success' => false, 'message' => 'Slide không tồn tại'], 404);
        }
    }

    public function changeActive($id){
        $change = Slide::find($id);    
        if ($change) {
            $change->is_active = !$change->is_active;
            $change->save();
            return response()->json(['success' => true, 'message' => 'Thay đổi trạng thái thành công']);
        }
        else {
            return response()->json(['success' => false, 'message' => 'Thay đổi trạng thái không thành công'], 404);
        }
    }
}
