<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\LibraryBook;
use App\Models\LibraryBookAccess;
use App\Models\Setting;
use Illuminate\Http\Request;
class LibraryController extends Controller
{
    /**
     * Bütün aktiv kitabların siyahısı.
     * my=1 parametri ilə yalnız access verilmiş kitablar qaytarılır.
     */
    public function index(Request $request)
    {
        $guest = $request->user();

        if ($request->my == 1) {
            $bookIds = LibraryBookAccess::where('guest_id', $guest->id)
                ->pluck('library_book_id');

            $books = LibraryBook::whereIn('id', $bookIds)
                ->where('status', 1)
                ->orderByDesc('id')
                ->get();

            return response(['status' => 'success', 'data' => $books]);
        }

        $books    = LibraryBook::where('status', 1)
            ->orderByDesc('id')
            ->get();
        $featured = $books->where('is_featured', 1)->values();
        $all      = $books->values();

        return response(['status' => 'success', 'data' => [
            'featured' => $featured,
            'all'      => $all,
        ]]);
    }

    /**
     * Kitab detalları.
     * Əgər user bu kitaba access-i varsa full_pdf_url qaytarılır,
     * yoxsa null qaytarılır.
     */
    public function show(Request $request, int $id)
    {
        $book = LibraryBook::where('status', 1)->findOrFail($id);
        $guestId   = $request->user()->id;
        $hasAccess = LibraryBookAccess::where([
            'library_book_id' => $id,
            'guest_id'        => $guestId,
        ])->exists();

        $data = $book->toArray();
        $data['has_access']   = $hasAccess;
        $data['full_pdf_url'] = $hasAccess ? $book->full_pdf_url : null;

        return response(['status' => 'success', 'data' => $data]);
    }

    /**
     * WhatsApp sifariş mesajını qaytarır.
     * App bu məlumatı alıb WhatsApp-ı açacaq.
     */
    public function orderWhatsapp(Request $request, int $id)
    {
        $book    = LibraryBook::where('status', 1)->findOrFail($id);
        $guest   = $request->user();
        $setting = Setting::first();

        $whatsappNumber = $setting->whatsapp ?? null;
        $message = "Salam! Mən {$guest->name} sistemdəki \"{$book->title}\" adlı kitabı almaq istəyirəm.";

        return response(['status' => 'success', 'data' => [
            'whatsapp_number'      => $whatsappNumber,
            'message'              => $message,
            'library_purchase_info'=> $setting->library_purchase_info ?? null,
            'book'                 => [
                'id'           => $book->id,
                'title'        => $book->title,
                'price'        => $book->price,
                'has_full_pdf' => !is_null($book->full_pdf_url),
            ],
        ]]);
    }
}
