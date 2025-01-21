<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FirebaseHelper;
use App\Http\Helpers\SmsHelper;
use App\Http\Requests\CustomerRequest;
use App\Models\Action;
use App\Models\Customer;
use App\Models\CustomerGroupDate;
use App\Models\Group;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $groups = Group::where('is_deleted', 0)->get();
        $subjects = Subject::where('is_deleted', 0)->get();
        $isDeleted = $request->is_deleted;
        $order = 'id';
        if (!isset($isDeleted)) {
            $isDeleted = 0;
        }

        if (isset($isDeleted) && $isDeleted == 1) {
            $order = 'updated_at';
        }

        $posts = Customer::where('is_deleted', $isDeleted)->where(function ($query) use ($request) {
            return $request->search ?
                $query->from('search')->where('name', 'like', "%$request->search%")->orWhere('username', 'like', "%$request->search%")->orWhere('email', 'like', "%$request->search%") : '';
        })->where(function ($query) use ($request) {
            return $request->status ?
                $query->from('status')->where('status', $request->status) : '';
        })->where(function ($query) use ($request) {
            if ($request->group_id) {
                $categoryId = $request->group_id;
                $query->whereRaw("JSON_CONTAINS(group_ids, '\"$categoryId\"')");
            }
        })->where(function ($query) use ($request) {
            if ($request->blocked_subject_id) {
                $categoryId = $request->blocked_subject_id;
                $query->whereRaw("JSON_CONTAINS(blocked_subject_ids, '\"$categoryId\"')");
            }
        });

        $countActive = (clone $posts)->where('status', 1)->get();
        $countDeactive = (clone $posts)->where('status', 0)->get();

        $posts = $posts->orderBy('status', 'desc')->orderBy($order, 'desc')->paginate(50);

        return view('admin.pages.customer', compact('posts', 'groups', 'subjects', 'countActive', 'countDeactive'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        for ($i = 0; $i < $request->count; $i++) {
            $customer = Customer::create([
                'status' => isset($request->status) ? 1 : 0,
                'group_ids' => json_encode($request->group_ids),
                'blocked_subject_ids' => json_encode($request->blocked_subject_ids),
            ]);

            $groupIds = $request->group_ids;
            $dates = $request->date;
            $endDates = $request->end_date;
            if (!empty($groupIds)) {
                foreach ($groupIds as $index => $groupId) {
                    CustomerGroupDate::create([
                        'group_id' => $groupId,
                        'customer_id' => $customer->id,
                        'date' => $dates[$index] ? $dates[$index] : Carbon::now(),
                        'end_date' => $endDates[$index] ? $endDates[$index] : null,
                    ]);
                }
            }
        }

        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            Action::create([
                'title' => $user->name . ' ' . $request->count . ' yeni tələbə yaratdı.'
            ]);
        }

        alert()->success('Uğurlu', 'Əlavə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->route('customer.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Customer::find($id);
        $dates = CustomerGroupDate::where('customer_id', $id)
            ->with('group')
            ->get()
            ->map(function ($date) {
                return [
                    'group_name' => $date->group->name ?? null,
                    'date' => $date->date,
                    'end_date' => $date->end_date,
                ];
            });
        return response()->json(['post' => $post, 'dates' => $dates], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, string $id)
    {
        $postUpdate = Customer::find($id);

        $postUpdate->name = $request->name;
        $postUpdate->group_ids = json_encode($request->group_ids);
        $postUpdate->blocked_subject_ids = json_encode($request->blocked_subject_ids);
        $postUpdate->email = $request->email;
        $postUpdate->class = $request->class;
        $postUpdate->status = isset($request->status) ? 1 : 0;

        if ($request->password) {
            $request->validate(
                [
                    "password" => "required|min:8|max:15",
                ],
                [
                    "password.required" => "Şifrə qeyd olunmalıdır",
                    "password.min" => "Şifrə 8-15 simvoldan ibarət olmalıdır",
                    "password.max" => "Şifrə 8-15 simvoldan ibarət olmalıdır",
                ]
            );
            $postUpdate->password = bcrypt($request->password);
            $postUpdate->password_text = $request->password;
        }

        $postUpdate->save();

        CustomerGroupDate::where('customer_id', $id)->delete();

        $groupIds = $request->group_ids;
        $dates = $request->date;
        $endDates = $request->end_date;
        if (!empty($groupIds)) {
            foreach ($groupIds as $index => $groupId) {
                CustomerGroupDate::create([
                    'group_id' => $groupId,
                    'customer_id' => $id,
                    'date' => $dates[$index] ? $dates[$index] : Carbon::now(),
                    'end_date' => $endDates[$index] ? $endDates[$index] : null,
                ]);
            }
        }

        alert()->success('Uğurlu', 'Redaktə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);
        if ($customer->is_deleted == 0) {
            $customer->is_deleted = 1;
            if (Auth::guard('admin')->check()) {
                $user = Auth::guard('admin')->user();
                Action::create([
                    'title' => $user->name . ' "' . $customer->username . '" adlı tələbəni sildi.'
                ]);
            }
        } else {
            $customer->is_deleted = 0;
            if (Auth::guard('admin')->check()) {
                $user = Auth::guard('admin')->user();
                Action::create([
                    'title' => $user->name . ' "' . $customer->username . '" adlı silinmiş tələbəni bərpa etdi.'
                ]);
            }
        }
        $customer->save();
        return response()->json(['message' => 'Uğurlu']);
    }

    public function changeStatus(Request $request)
    {
        try {
            $postID = $request->id;
            $post = Customer::find($postID);
            $status = $post->status;

            if ($status == 0) {
                if (Auth::guard('admin')->check()) {
                    $user = Auth::guard('admin')->user();
                    Action::create([
                        'title' => $user->name . ' "' . $post->username . '" adlı tələbəni aktiv etdi.'
                    ]);
                }
            } else {
                if (Auth::guard('admin')->check()) {
                    $user = Auth::guard('admin')->user();
                    Action::create([
                        'title' => $user->name . ' "' . $post->username . '" adlı tələbəni deaktiv etdi.'
                    ]);
                }
            }

            $post->status = $status ? 0 : 1;

            $post->save();

            return response()->json(['message' => 'Uğurlu', 'status' => $post->status], 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Xəta', 'status' => $post->status], 500);
        }
    }

    public function checked(Request $request)
    {
        $arr = $request->arr;

        if ($request->val == 0) {
            foreach ($arr as $id) {
                $post = Customer::find($id);
                $post->status = 0;
                $post->save();
                if (Auth::guard('admin')->check()) {
                    $user = Auth::guard('admin')->user();
                    Action::create([
                        'title' => $user->name . ' "' . $post->username . '" adlı tələbəni deaktiv etdi.'
                    ]);
                }
            }
        } else if ($request->val == 1) {
            foreach ($arr as $id) {
                $post = Customer::find($id);
                $post->status = 1;
                $post->save();
                if (Auth::guard('admin')->check()) {
                    $user = Auth::guard('admin')->user();
                    Action::create([
                        'title' => $user->name . ' "' . $post->username . '" adlı tələbəni aktiv etdi.'
                    ]);
                }
            }
        } else if ($request->val == 2) {
            foreach ($arr as $id) {
                $customer = Customer::find($id);
                if ($customer->is_deleted == 0) {
                    $customer->is_deleted = 1;
                    if (Auth::guard('admin')->check()) {
                        $user = Auth::guard('admin')->user();
                        Action::create([
                            'title' => $user->name . ' "' . $customer->username . '" adlı tələbəni sildi.'
                        ]);
                    }
                } else {
                    $customer->is_deleted = 0;
                    if (Auth::guard('admin')->check()) {
                        $user = Auth::guard('admin')->user();
                        Action::create([
                            'title' => $user->name . ' "' . $customer->username . '" adlı silinmiş tələbəni bərpa etdi.'
                        ]);
                    }
                }
                $customer->save();
            }
        } else if ($request->val == 3) {
            foreach ($arr as $id) {
                $post = Customer::find($id);
                $message = 'Ödənişinizin vaxtı bitir';
                FirebaseHelper::sendUser("Ödəmə bildirişi", $message, $id);
                // Firebase notification
            }
        }

        return response()->json(['message' => 'Uğurlu']);
    }

    public function device(Request $request)
    {
        DB::table('device_log')->where('customer_id', $request->id)->delete();
        return response()->json(['message' => 'Uğurlu']);
    }

    public function payment()
    {
        $customers = \App\Models\Customer::where('status', 1)->where('is_deleted', 0)->get();
        $today = Carbon::now()->startOfDay();

        foreach ($customers as $customer) {
            $paymentDate = Carbon::parse($customer->date);
            $paymentDate->month = $today->month;
            $paymentDate->year = $today->year;

            if ($paymentDate->lessThan($today)) {
                $paymentDate->addMonth();
            }

            $daysLeft = $today->diffInDays($paymentDate, false);
            if ($daysLeft == 0) {
                $message = 'Bu gün ərzində ödənişi etməlisiniz.';
            } elseif ($daysLeft == 1) {
                $message = 'Ödəniş üçün 1 gününüz qalır.';
            } elseif ($daysLeft == 2) {
                $message = 'Ödəniş üçün 2 gününüz qalır.';
            } elseif ($daysLeft == 3) {
                $message = 'Ödəniş üçün 3 gününüz qalır.';
            } else {
                continue;
            }
            FirebaseHelper::sendUser("Ödəmə bildirişi", $message, $customer->id);
            //bura sms göndərmək üçün funksiya çağırılmalıdır. Parametr ($customer->id, $message)
        }
        return 'Successfully sended';
    }

}
