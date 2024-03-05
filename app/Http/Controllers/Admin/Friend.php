<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Friend as ModelsFriend;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
class Friend extends Controller
{
    public function index(Request $request, $page = 1)
    {
        // validate
        $request->validate([
            'name' => 'nullable|max:255',
        ]);

        $breadcrumbs = 'friends';
        $page = intval($page);
        $perpage = intval($request->query('perpage') ?? 10);
        $filters = [
            'name' => $request->query('name') ?? '',
        ];

        // filters
        $friends = ModelsFriend::when($filters['name'], function (Builder $query, $value) {
                return $query->where('name', 'like', '%' . $value . '%');
            });

        // pagination
        $total = $friends->count();
        $pagination = [
            'perpage' => $perpage,
            'curr_page' => intval($page),
            'total' => $total,
            'total_page' => intval(ceil($total / $perpage)),
            'base_url' => route('admin.friends'),
            'params' => http_build_query(array_merge($filters, ['perpage' => $perpage])),
        ];

        // get data
        $friends = $friends->limit($perpage)->offset(($page - 1) * $perpage)->get();
        return view('pages.friends.index', compact('friends', 'filters', 'pagination'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'sex' => 'required|max:255',
            'address' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $customer = ModelsFriend::find($id);

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
    }}
