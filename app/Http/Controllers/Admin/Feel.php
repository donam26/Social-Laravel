<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
class Feel extends Controller
{
    public function index(Request $request, $page = 1)
    {
        // validate
        $request->validate([
            'title' => 'nullable|max:255',
            'user_id' => 'nullable',
        ]);

        $breadcrumbs = 'feels';
        $page = intval($page);
        $perpage = intval($request->query('perpage') ?? 10);
        $filters = [
            'title' => $request->query('title') ?? '',
            'user_id' => $request->query('user_id') ?? '',
        ];

        // filters
        $feels = Post::with('user:id,name')
        ->when($filters['title'], function (Builder $query, $value) {
                return $query->where('title', 'like', '%' . $value . '%');
            })
            ->when($filters['user_id'], function (Builder $query, $value) {
                return $query->where('user_id', 'like', '%' . $value . '%');
            });

        // pagination
        $total = $feels->count();
        $pagination = [
            'perpage' => $perpage,
            'curr_page' => intval($page),
            'total' => $total,
            'total_page' => intval(ceil($total / $perpage)),
            'base_url' => route('admin.feels'),
            'params' => http_build_query(array_merge($filters, ['perpage' => $perpage])),
        ];

        // get data
        $feels = $feels->limit($perpage)->offset(($page - 1) * $perpage)->get();
        return view('pages.feels.index', compact('feels', 'filters', 'pagination'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'sex' => 'required|max:255',
            'address' => 'required|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
        ]);

        $customer = Post::find($id);

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

    public function delete(Request $request, $id)
    {
        // validate id
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.feels')
                ->withErrors($validator)
                ->withInput();
        }

        // get data
        $customer = Post::find($id);

        // delete
        $customer->delete();

        return redirect()->route('admin.feels')->with('success', 'Xóa đơn hàng thành công');
    }}
