<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group as ModelsGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
class Group extends Controller
{
    public function index(Request $request, $page = 1)
    {
        // validate
        $request->validate([
            'name' => 'nullable|max:255',
        ]);

        $breadcrumbs = 'groups';
        $page = intval($page);
        $perpage = intval($request->query('perpage') ?? 10);
        $filters = [
            'name' => $request->query('name') ?? '',
        ];

        // filters
        $groups = ModelsGroup::when($filters['name'], function (Builder $query, $value) {
                return $query->where('name', 'like', '%' . $value . '%');
            })
            ->with('createdUser');

        // pagination
        $total = $groups->count();
        $pagination = [
            'perpage' => $perpage,
            'curr_page' => intval($page),
            'total' => $total,
            'total_page' => intval(ceil($total / $perpage)),
            'base_url' => route('admin.groups'),
            'params' => http_build_query(array_merge($filters, ['perpage' => $perpage])),
        ];

        // get data
        $groups = $groups->limit($perpage)->offset(($page - 1) * $perpage)->get();
        return view('pages.groups.index', compact('groups', 'filters', 'pagination'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'sex' => 'required|max:255',
            'address' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $customer = ModelsGroup::find($id);

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
}
