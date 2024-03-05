<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Customer extends Controller
{
    public function index(Request $request, $page = 1)
    {
        // validate
        $request->validate([
            'name' => 'nullable|max:255',
            'email' => 'nullable|email',
        ]);

        $breadcrumbs = 'customers';
        $page = intval($page);
        $perpage = intval($request->query('perpage') ?? 10);
        $filters = [
            'name' => $request->query('name') ?? '',
            'email' => $request->query('email') ?? '',
        ];

        // filters
        $customers = User::when($filters['name'], function (Builder $query, $value) {
                return $query->where('name', 'like', '%' . $value . '%');
            })
            ->when($filters['email'], function (Builder $query, $value) {
                return $query->where('email', 'like', '%' . $value . '%');
            });

        // pagination
        $total = $customers->count();
        $pagination = [
            'perpage' => $perpage,
            'curr_page' => intval($page),
            'total' => $total,
            'total_page' => intval(ceil($total / $perpage)),
            'base_url' => route('admin.customers'),
            'params' => http_build_query(array_merge($filters, ['perpage' => $perpage])),
        ];

        // get data
        $customers = $customers->limit($perpage)->offset(($page - 1) * $perpage)->get();

        return view('pages.customers.index', compact('customers', 'filters', 'pagination'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'sex' => 'required|max:255',
            'address' => 'required|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
        ]);

        $customer = User::find($id);

        // check if user not found
        if (!$customer) {
            return redirect()->route('admin.customers')->with('error', 'Không tìm thấy khách hàng');
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
            return redirect()->route('admin.customers')
                ->withErrors($validator)
                ->withInput();
        }

        // get data
        $customer = User::find($id);

        // delete
        $customer->delete();

        return redirect()->route('admin.customers')->with('success', 'Xóa đơn hàng thành công');
    }
}
