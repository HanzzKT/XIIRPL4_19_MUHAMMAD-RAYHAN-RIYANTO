<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComplaintCategory;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        $categories = ComplaintCategory::where('is_active', true)->get();

        $complaints = collect();
        if (Auth::check() && Auth::user()->role === 'customer') {
            $customer = Auth::user()->customer;
            if ($customer) {
                $query = Complaint::with(['category'])
                    ->where('customer_id', $customer->id)
                    ->latest();

                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }
                if ($request->filled('category')) {
                    $query->where('complaint_category_id', $request->category);
                }
                if ($request->filled('search')) {
                    $query->where('description', 'like', "%{$request->search}%");
                }

                $complaints = $query->paginate(10)->withQueryString();
            }
        }

        return view('public.index', compact('categories', 'complaints'));
    }

    public function faq()
    {
        $faqs = [
            [
                'question' => 'Kenapa gas berbau tidak sedap?',
                'answer' => 'Gas LPG memiliki bau khas yang ditambahkan untuk keamanan. Jika bau terlalu menyengat atau berbeda, segera hubungi CS kami.'
            ],
            [
                'question' => 'Galon air kotor, apa solusinya?',
                'answer' => 'Segera hubungi CS kami untuk penggantian galon. Kami akan mengganti dengan galon yang bersih dan berkualitas.'
            ],
            [
                'question' => 'Bagaimana cara melaporkan tabung bocor?',
                'answer' => 'Hubungi CS kami melalui telepon atau WhatsApp. Tim teknisi akan segera datang untuk penggantian.'
            ],
            [
                'question' => 'Berapa lama proses penyelesaian komplain?',
                'answer' => 'Kami berkomitmen menyelesaikan komplain dalam 24 jam untuk kasus darurat dan 3x24 jam untuk kasus non-darurat.'
            ],
            [
                'question' => 'Apakah ada biaya untuk penggantian produk rusak?',
                'answer' => 'Tidak ada biaya tambahan untuk penggantian produk yang rusak akibat kesalahan dari pihak kami.'
            ]
        ];

        return view('public.faq', compact('faqs'));
    }

    public function contact()
    {
        $contacts = [
            'whatsapp' => '081234567890',
            'email' => 'cs@karunialaris.com',
            'address' => 'Jl. Raya Industri No. 123, Jakarta Timur',
            'hours' => [
                'senin_jumat' => '08:00 - 17:00',
                'sabtu' => '08:00 - 15:00',
                'minggu' => 'Tutup'
            ]
        ];

        return view('public.contact', compact('contacts'));
    }

    public function complaintFlow()
    {
        $steps = [
            [
                'step' => 1,
                'title' => 'Daftar/Login Akun',
                'description' => 'Buat akun customer atau login ke sistem kami',
                'icon' => 'user'
            ],
            [
                'step' => 2,
                'title' => 'Buat Komplain Online',
                'description' => 'Isi form komplain dengan detail masalah Anda',
                'icon' => 'edit'
            ],
            [
                'step' => 3,
                'title' => 'CS Menangani Komplain',
                'description' => 'Tim CS akan merespon dan menangani komplain Anda',
                'icon' => 'chat'
            ],
            [
                'step' => 4,
                'title' => 'Proses Penyelesaian',
                'description' => 'Tim teknis menyelesaikan masalah sesuai jenis komplain',
                'icon' => 'cog'
            ],
            [
                'step' => 5,
                'title' => 'Komplain Selesai',
                'description' => 'CS memberikan feedback dan menyelesaikan komplain',
                'icon' => 'check'
            ]
        ];

        return view('public.complaint-flow', compact('steps'));
    }
}
