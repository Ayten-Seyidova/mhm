<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\LibraryBook;
use App\Models\LibraryBookAccess;
use App\Services\BunnyService;
use Illuminate\Http\Request;

class LibraryBookController extends Controller
{
    protected BunnyService $bunny;

    public function __construct(BunnyService $bunny)
    {
        $this->bunny = $bunny;
    }

    public function index(Request $request)
    {
        $posts = LibraryBook::when($request->search, function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('author', 'like', "%{$request->search}%");
            })
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.pages.library-book', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'author'   => 'nullable|string|max:255',
            'price'    => 'required|numeric|min:0',
            'demo_pdf' => 'nullable|file|mimes:pdf|max:51200',
            'full_pdf' => 'nullable|file|mimes:pdf|max:102400',
        ]);

        $data = [
            'title'       => $request->title,
            'author'      => $request->author,
            'publisher'   => $request->publisher,
            'description' => $request->description,
            'language'    => $request->language,
            'page_count'  => $request->page_count,
            'year'        => $request->year,
            'price'       => $request->price,
            'is_featured' => isset($request->is_featured) ? 1 : 0,
            'status'      => isset($request->status) ? 1 : 0,
        ];

        // Cover image — local (existing helper)
        if ($request->hasFile('image')) {
            $data['cover'] = uploadImg($request->file('image'));
        }

        // Demo PDF — Bunny CDN
        if ($request->hasFile('demo_pdf')) {
            $data['demo_pdf_url'] = $this->bunny->uploadPdf($request->file('demo_pdf'), 'pdf/demo');
        }

        // Full PDF — Bunny CDN
        if ($request->hasFile('full_pdf')) {
            $data['full_pdf_url'] = $this->bunny->uploadPdf($request->file('full_pdf'), 'pdf/full');
        }

        LibraryBook::create($data);

        alert()->success('Uğurlu', 'Əlavə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->route('library-book.index');
    }

    public function edit(string $id)
    {
        $post = LibraryBook::find($id);
        return response()->json(['post' => $post], 200);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'price'    => 'required|numeric|min:0',
            'demo_pdf' => 'nullable|file|mimes:pdf|max:51200',
            'full_pdf' => 'nullable|file|mimes:pdf|max:102400',
        ]);

        $post = LibraryBook::findOrFail($id);

        $data = [
            'title'       => $request->title,
            'author'      => $request->author,
            'publisher'   => $request->publisher,
            'description' => $request->description,
            'language'    => $request->language,
            'page_count'  => $request->page_count,
            'year'        => $request->year,
            'price'       => $request->price,
            'is_featured' => isset($request->is_featured) ? 1 : 0,
            'status'      => isset($request->status) ? 1 : 0,
        ];

        // Cover image
        if (isset($_POST['hidden'])) {
            if ($_POST['hidden'] == "0") {
                $data['cover'] = null;
            } elseif ($request->hasFile('image') && $_POST['hidden'] == "1") {
                $data['cover'] = uploadImg($request->file('image'));
            }
        }

        // Demo PDF — yeni yüklənirsə köhnəni sil, yenisini yüklə
        if ($request->hasFile('demo_pdf')) {
            if ($post->demo_pdf_url) {
                $this->bunny->deletePdf($post->demo_pdf_url);
            }
            $data['demo_pdf_url'] = $this->bunny->uploadPdf($request->file('demo_pdf'), 'pdf/demo');
        }

        // Full PDF — yeni yüklənirsə köhnəni sil, yenisini yüklə
        if ($request->hasFile('full_pdf')) {
            if ($post->full_pdf_url) {
                $this->bunny->deletePdf($post->full_pdf_url);
            }
            $data['full_pdf_url'] = $this->bunny->uploadPdf($request->file('full_pdf'), 'pdf/full');
        }

        $post->update($data);

        alert()->success('Uğurlu', 'Redaktə olundu')
            ->showConfirmButton('Tamam', '#163A76');

        return redirect()->back();
    }

    public function destroy(string $id)
    {
        $post = LibraryBook::findOrFail($id);

        if ($post->demo_pdf_url) $this->bunny->deletePdf($post->demo_pdf_url);
        if ($post->full_pdf_url) $this->bunny->deletePdf($post->full_pdf_url);

        $post->delete();

        return response()->json(['message' => 'Uğurlu']);
    }

    public function changeStatus(Request $request)
    {
        try {
            $post = LibraryBook::findOrFail($request->id);
            $post->status = $post->status ? 0 : 1;
            $post->save();

            return response()->json(['message' => 'Uğurlu', 'status' => $post->status], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Xəta'], 500);
        }
    }

    public function checked(Request $request)
    {
        foreach ($request->arr as $id) {
            $post = LibraryBook::find($id);
            if (!$post) continue;

            if ($request->val == 0) {
                $post->status = 0;
                $post->save();
            } elseif ($request->val == 1) {
                $post->status = 1;
                $post->save();
            } elseif ($request->val == 2) {
                if ($post->demo_pdf_url) $this->bunny->deletePdf($post->demo_pdf_url);
                if ($post->full_pdf_url) $this->bunny->deletePdf($post->full_pdf_url);
                $post->delete();
            }
        }

        return response()->json(['message' => 'Uğurlu']);
    }

    // -------------------------------------------------------
    // Access management — admin bir usere kitab açır
    // -------------------------------------------------------

    public function grantAccess(Request $request)
    {
        $request->validate([
            'library_book_id' => 'required|exists:library_books,id',
            'guest_id'        => 'required|exists:guests,id',
        ]);

        LibraryBookAccess::firstOrCreate([
            'library_book_id' => $request->library_book_id,
            'guest_id'        => $request->guest_id,
        ]);

        return response()->json(['message' => 'Access verildi']);
    }

    public function revokeAccess(Request $request)
    {
        LibraryBookAccess::where([
            'library_book_id' => $request->library_book_id,
            'guest_id'        => $request->guest_id,
        ])->delete();

        return response()->json(['message' => 'Access silindi']);
    }

    public function bookAccesses(string $id)
    {
        $book = LibraryBook::with(['accesses.guest'])->findOrFail($id);
        $guests = Guest::where('status', 1)->orderBy('name')->get(['id', 'name', 'phone']);

        return response()->json([
            'book'    => $book,
            'accesses'=> $book->accesses,
            'guests'  => $guests,
        ]);
    }
}
