<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminPasswordUpdateRequest;
use App\Http\Requests\Admin\ProfileStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Đăng xuất tài khoản thành công.');
    }

    public function AdminProfile()
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        return view('admin.profile.admin_profile', compact('data'));
    }
    public function AdminProfileStore(ProfileStoreRequest $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);

        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $file = $request->file('photo');
            if ($data->photo && file_exists(public_path('uploads/admin_images'))) {
                @unlink(public_path('uploads/admin_images/' . $data->photo));
            }

            $filename = now()
                ->format('YmdHis') . '_' . $file
                    ->getClientOriginalName();

            $file->move(public_path('uploads/admin_images/'), $filename);
            $data->photo = $filename;
        }
        $data->save();
        return redirect()->back()->with('message', 'Cập nhập hồ sơ thành công');

    }

    public function AdminChangePassword()
    {
        // lay id 
        $id = Auth::user()->id;
        $data = User::find($id);

        return view('admin.profile.change_pass', compact('data'));
    }
    public function AdminPasswordUpdate(AdminPasswordUpdateRequest $request)
    {
        if (!Hash::check($request->old_password, Auth::user()->password)) {
            return back()->with([
                'message' => 'Mật khẩu cũ điền không đúng.',
                'alert-type' => 'error'
            ]);
        }

        $user = User::find(Auth::id());
        $user->password = Hash::make($request->new_password);
        $user->save();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with([
            'message' => 'Đổi mật khẩu thành công. Vui lòng đăng nhập lại!',
            'alert-type' => 'success'
        ]);
    }

}
