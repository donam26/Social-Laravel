<?php

namespace App\Http\Controllers\Admin;

use App\Events\NotificationViolate;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Violate as ModelsViolate;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class Violate extends Controller
{
    public function index(Request $request, $page = 1)
    {
        // validate
        $request->validate([
            'title' => 'nullable|max:255',
        ]);

        $breadcrumbs = 'violates';
        $page = intval($page);
        $perpage = intval($request->query('perpage') ?? 10);
        $filters = [
            'title' => $request->query('title') ?? '',
        ];

        // filters
        $violates = ModelsViolate::when($filters['title'], function (Builder $query, $value) {
                return $query->where('name', 'like', '%' . $value . '%');
            })->with('feel.user')->with('user');

        // pagination
        $total = $violates->count();
        $pagination = [
            'perpage' => $perpage,
            'curr_page' => intval($page),
            'total' => $total,
            'total_page' => intval(ceil($total / $perpage)),
            'base_url' => route('admin.violates'),
            'params' => http_build_query(array_merge($filters, ['perpage' => $perpage])),
        ];
        // get data
        $violates = $violates->limit($perpage)->offset(($page - 1) * $perpage)->get();
        return view('pages.violates.index', compact('violates', 'filters', 'pagination'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'sex' => 'required|max:255',
            'address' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $customer = ModelsViolate::find($id);

        // check if user not found
        if (!$customer) {
            return redirect()->route('admin.feels')->with('error', 'Không tìm thấy khách hàng');
        }

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->sex = $request->sex;
        $customer->address = $request->address;
        $customer->save();

        // redirect back with success message
        return response()->json(['status' => 'success', 'message' => 'Cập nhật thông tin thành công'], 200);
    }

    public function delete($id)
    {
        $feel = ModelsViolate::findOrFail($id);
        $feel->delete();
        return redirect()->back()->with('success', 'Feel đã được xóa thành công');
    }

    public function confirm($id)
    {
        $user = auth()->user(); 
        $violate = ModelsViolate::findOrFail($id);
        $feel = Post::findOrFail($violate->feel_id);
        $notification = Notification::create([
            'user_id' => $violate->feel->user_id,
            'member_name' =>  'Admin',
            'content' => 'Bài viết của bạn đã vi phạm chính sách, vui lòng không tái phạm để không bị khóa tài khoản',
            'type' => '10',
            'status' => '0',
            'member_image' => 'man.jpg',
            // link của bài viết
            'link' => '/feel/' . $violate->feel_id,
        ]);
        broadcast(new NotificationViolate ($violate, $feel));
        $feel->update(['status' => 0]);
        $violate->delete();
        return redirect()->back()->with('success', 'Feel đã được xóa thành công');
    }
}
