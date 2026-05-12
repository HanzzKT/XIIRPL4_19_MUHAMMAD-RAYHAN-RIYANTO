<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComplaintCategory;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
    /**
     * Menampilkan halaman utama (beranda/index) untuk akses publik dan Customer.
     * Secara otomatis mengumpulkan data formulir dan statistik riwayat komplain jika pengunjung sudah login sebagai Customer.
     */
    public function index(Request $request)
    {
        // Redirect staff (cs, manager, admin) to their respective dashboards
        if (Auth::check()) {
            $userRole = Auth::user()->role;
            
            if ($userRole === 'cs') {
                return redirect()->route('cs.dashboard');
            } elseif ($userRole === 'manager') {
                return redirect()->route('manager.dashboard');
            } elseif ($userRole === 'admin') {
                return redirect()->route('admin.dashboard');
            }
        }
        
        $categories = ComplaintCategory::where('is_active', true)->get();

        $complaints = collect();
        $hasActiveComplaint = false;
        $activeComplaint = null;
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

                // Check if customer has an active (not yet completed) complaint
                $activeComplaint = Complaint::with(['category'])
                    ->where('customer_id', $customer->id)
                    ->whereIn('status', ['baru', 'diproses'])
                    ->latest()
                    ->first();
                $hasActiveComplaint = $activeComplaint !== null;
            }
        }

        // Customer completion stats
        $customerStats = ['total' => 0, 'done' => 0, 'active' => 0, 'rate' => 0];
        if (Auth::check() && Auth::user()->role === 'customer' && isset($customer) && $customer) {
            $cTotal  = Complaint::where('customer_id', $customer->id)->count();
            $cDone   = Complaint::where('customer_id', $customer->id)->where('status', 'selesai')->count();
            $cActive = Complaint::where('customer_id', $customer->id)->whereIn('status', ['baru','diproses'])->count();
            $customerStats = [
                'total'  => $cTotal,
                'done'   => $cDone,
                'active' => $cActive,
                'rate'   => $cTotal > 0 ? round(($cDone / $cTotal) * 100, 1) : 0,
            ];
        }

        return view('public-pages.index', compact('categories', 'complaints', 'hasActiveComplaint', 'activeComplaint', 'customerStats'));
    }

    /**
     * Menampilkan halaman Frequently Asked Questions (FAQ) atau kumpulan pertanyaan yang sering ditanyakan pelanggan.
     */
    public function faq()
    {
        // Redirect staff (cs, manager, admin) to their respective dashboards
        if (Auth::check()) {
            $userRole = Auth::user()->role;
            
            if ($userRole === 'cs') {
                return redirect()->route('cs.dashboard');
            } elseif ($userRole === 'manager') {
                return redirect()->route('manager.dashboard');
            } elseif ($userRole === 'admin') {
                return redirect()->route('admin.dashboard');
            }
        }
        
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

        return view('public-pages.faq', compact('faqs'));
    }

    /**
     * Menampilkan halaman informasi Hubungi Kami berisi kontak penting Perusahaan (WhatsApp, Email, Jam Operasional).
     */
    public function contact()
    {
        // Redirect staff (cs, manager, admin) to their respective dashboards
        if (Auth::check()) {
            $userRole = Auth::user()->role;
            
            if ($userRole === 'cs') {
                return redirect()->route('cs.dashboard');
            } elseif ($userRole === 'manager') {
                return redirect()->route('manager.dashboard');
            } elseif ($userRole === 'admin') {
                return redirect()->route('admin.dashboard');
            }
        }
        
        $contacts = [
            'whatsapp' => '0813-8855-6335',
            'email' => 'cs@karunialaris.com',
            'address' => 'Jl. Raya Pekayon No.50, RT.004/RW.001, Jaka Setia, Kec. Bekasi Sel., Kota Bks, Jawa Barat',
            'hours' => [
                'senin_jumat' => '08:00 - 17:00',
                'sabtu' => '08:00 - 15:00',
                'minggu' => 'Tutup'
            ]
        ];

        return view('public-pages.contact', compact('contacts'));
    }

    /**
     * Menampilkan halaman edukasi edukasi tentang panduan Langkah-langkah dan Alur Cara Membuat Komplain di sistem.
     */
    public function complaintFlow()
    {
        // Redirect staff (cs, manager, admin) to their respective dashboards
        if (Auth::check()) {
            $userRole = Auth::user()->role;
            
            if ($userRole === 'cs') {
                return redirect()->route('cs.dashboard');
            } elseif ($userRole === 'manager') {
                return redirect()->route('manager.dashboard');
            } elseif ($userRole === 'admin') {
                return redirect()->route('admin.dashboard');
            }
        }
        
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

        return view('public-pages.complaint-flow', compact('steps'));
    }
}
