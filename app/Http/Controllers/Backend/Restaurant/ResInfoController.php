<?php

namespace App\Http\Controllers\backend\restaurant;

use App\Http\Controllers\Controller;
use App\Models\RestaurantInfo;
use Illuminate\Http\Request;

class ResInfoController extends Controller
{
    public function edit($id)
    {
        $edit = RestaurantInfo::where("id", $id)->first();
        return view("backend.restaurant.info.edit", compact("edit"));
    }

    public function update(Request $request, $id)
    {
        $data = [
            'name' => $request->name,
            'hotline_1'=> $request->hotline_1,
            'hotline_2'=> $request->hotline_2,
            'email'=> $request->email,
            'opening_day'=> $request->opening_day,
            'open_time'=> $request->open_time,
            'close_time'=> $request->close_time,
            'sort_description'=> $request->sort_description,
            'log_description'=> $request->log_description
        ];
        $edit = RestaurantInfo::where('id', $id)->first();
        $edit->update($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Cập nhật thông tin nhà hàng thành công"]);
        return redirect()->route("restaurant.index");
    }
}
