<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FirebaseHelper;
use App\Http\Requests\PostRequest;
use App\Jobs\SendGuestNotification;
use App\Models\Action;
use App\Models\Direction;
use App\Models\Guest;
use App\Models\Post;
use App\Models\SubDirection;
use App\Models\TeacherSubDirection;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = null;

        $teachers = User::where('type', 'teacher')->get();

        $directions = Direction::where('is_deleted', 0)->get();
        $allSubDirections = SubDirection::where('is_deleted', 0)->get();

        if ($request->direction_id) {
            $subDirections = SubDirection::where('direction_id', $request->direction_id)->where('is_deleted', 0)->get();
        } else {
            $subDirections = $allSubDirections;
        }

        $isDeleted = $request->is_deleted ?? 0;

        $posts = Post::with('teacher')->with('subDirection')->where('is_deleted', $isDeleted)
            ->where(function ($query) use ($request) {
                if ($request->search) {
                    $query->where('content', 'like', "%{$request->search}%");
                }
            })
            ->where(function ($query) use ($request) {
                return $request->user_id ?
                    $query->from('user_id')->where('user_id', $request->user_id) : '';
            })
            ->where(function ($query) use ($request) {
                return $request->sub_direction_id ?
                    $query->from('sub_direction_id')->where('sub_direction_id', $request->sub_direction_id) : '';
            })
            ->when($request->direction_id, function ($query) use ($request) {
                return $query->whereHas('subDirection', function ($q) use ($request) {
                    $q->where('direction_id', $request->direction_id);
                });
            })->where(function ($query) use ($request) {
                return $request->status ?
                    $query->from('status')->where('status', $request->status) : '';
            });

        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();

            $posts = $posts->where('user_id', $user->id);

            $teaherDirectionIds = TeacherSubDirection::where('user_id', $user->id)->pluck('sub_direction_id');
            $subDirections = SubDirection::whereIn('id', $teaherDirectionIds)->get();
        }

        $posts = $posts->orderBy('id', 'desc')->paginate(20);
        return view('admin.pages.post', compact('posts', 'directions', 'subDirections', 'allSubDirections', 'teachers'));
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
    public function store(PostRequest $request)
    {

        $image = $request->file('image');
        $uploadedImg = $image ? uploadImg($image) : 'postImage/noPhoto.png';

        $userId = null;
        if (Auth::guard('teacher')->check()) {
            $user = Auth::guard('teacher')->user();
            $userId = $user->id;
        }

        $url = $request->video;
        $position = strpos($url, '=');

        if ($position !== false) {
            $parsedUrl = parse_url($url);

            parse_str($parsedUrl['query'] ?? '', $queryParams);

            $videoId = $queryParams['v'] ?? null;

            if ($videoId) {
                $url = $videoId;
            } else {
                $url = '';
            }
        }

        $subDirectionIds = $request->sub_direction_id;
        if (!empty($subDirectionIds)) {
            foreach ($subDirectionIds as $subDirectionId) {
                $post = Post::create([
                    'sub_direction_id' => $subDirectionId,
                    'image' => $uploadedImg,
                    'type' => $request->type,
                    'content' => $request->content,
                    'video' => $url,
                    'user_id' => $userId,
                    'correct' => $request->correct,
                    'status' => isset($request->status) ? 1 : 0,
                ]);

                if ($request->type == 'question') {
                    Variant::create([
                        'post_id' => $post->id,
                        'A' => $request->A,
                        'B' => $request->B,
                        'C' => $request->C,
                        'D' => $request->D,
                        'E' => $request->E,
                    ]);
                }
            }
        }

//        if (Auth::check()) {
        if (isset($request->notification)) {
            //       FirebaseHelper::sendAll('Paylaşım edildi', 'Admin tərəfindən '.$request->content.' başlıqlı paylaşım edildi');
            SendGuestNotification::dispatch(
                'Paylaşım edildi',
                'Admin tərəfindən ' . $request->content . ' başlıqlı paylaşım edildi',
                $subDirectionIds
            );
//            $guests = Guest::whereIn('sub_direction_id', $subDirectionIds)->where('is_deleted', 0)->where('status', 1)->get();
//
//            foreach ($guests as $guest) {
//                SendGuestNotification::dispatch(
//                    'Paylaşım edildi',
//                    'Admin tərəfindən '.$request->content.' başlıqlı paylaşım edildi',
//                    $guest->id
//                );
//            }
        }
//            }

        alert()->success('Uğurlu', 'Əlavə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->route('post.index');
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
        $post = Post::with('subDirection')->find($id);
        $variant = Variant::where('post_id', $post->id)->first();
        return response()->json(['post' => $post, 'variant' => $variant], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, string $id)
    {
        $postUpdate = Post::find($id);

        $image = $request->file('image');

        if ($_POST['hidden'] == "0") {
            $postUpdate->image = 'postImage/noPhoto.png';
        } else if ($image && $_POST['hidden'] == "1") {
            $postUpdate->image = uploadImg($image);
        }

        $url = $request->video;
        $position = strpos($url, '=');

        if ($position !== false) {
            $parsedUrl = parse_url($url);

            parse_str($parsedUrl['query'] ?? '', $queryParams);

            $videoId = $queryParams['v'] ?? null;

            if ($videoId) {
                $url = $videoId;
            }
        }

        $postUpdate->video = $url;
        $postUpdate->content = $request->content;
        $postUpdate->correct = $request->correct;
        $postUpdate->type = $request->type;
        $postUpdate->sub_direction_id = $request->sub_direction_id;
        $postUpdate->status = isset($request->status) ? 1 : 0;

        $postUpdate->save();

        $variant = Variant::where('post_id', $postUpdate->id)->first();
        if ($request->type == 'question') {
            if (empty($variant)) {
                Variant::create([
                    'post_id' => $postUpdate->id,
                    'A' => $request->A,
                    'B' => $request->B,
                    'C' => $request->C,
                    'D' => $request->D,
                    'E' => $request->E,
                ]);
            } else {
                $variant->A = $request->A;
                $variant->B = $request->B;
                $variant->C = $request->C;
                $variant->D = $request->D;
                $variant->E = $request->E;
            }
        } else {
            Variant::where('post_id', $postUpdate->id)->delete();
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
        $customer = Post::find($id);
        if ($customer->is_deleted == 0) {
            $customer->is_deleted = 1;
        } else {
            $customer->is_deleted = 0;
        }
        $customer->save();
        return response()->json(['message' => 'Uğurlu']);
    }

    public function changeStatus(Request $request)
    {
        try {
            $postID = $request->id;
            $post = Post::find($postID);
            $status = $post->status;
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
                $post = Post::find($id);
                $post->status = 0;
                $post->save();
            }
        } else if ($request->val == 1) {
            foreach ($arr as $id) {
                $post = Post::find($id);
                $post->status = 1;
                $post->save();
            }
        } else if ($request->val == 2) {
            foreach ($arr as $id) {
                $customer = Post::find($id);
                if ($customer->is_deleted == 0) {
                    $customer->is_deleted = 1;
                } else {
                    $customer->is_deleted = 0;
                }
                $customer->save();
            }
        }

        return response()->json(['message' => 'Uğurlu']);
    }
}
