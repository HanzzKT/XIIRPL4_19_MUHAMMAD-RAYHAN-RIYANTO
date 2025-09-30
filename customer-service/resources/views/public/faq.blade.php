@extends('public.layout')

@section('title', 'FAQ - Pertanyaan yang Sering Diajukan')

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">FAQ</h1>
            <p class="text-xl text-blue-100">Pertanyaan yang Sering Diajukan</p>
        </div>
    </div>
</section>

<!-- FAQ Content -->
<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Temukan Jawaban untuk Pertanyaan Anda</h2>
                
                <div class="space-y-6">
                    @foreach($faqs as $index => $faq)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <button class="faq-button w-full px-6 py-4 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 focus:outline-none focus:bg-gray-100" 
                                data-target="faq-{{ $index }}">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $faq['question'] }}</h3>
                                <svg class="faq-icon w-5 h-5 text-gray-500 transform transition-transform duration-200" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </button>
                        <div id="faq-{{ $index }}" class="faq-content hidden px-6 py-4 bg-white border-t border-gray-200">
                            <p class="text-gray-700 leading-relaxed">{{ $faq['answer'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact CTA -->
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-8 text-white">
            <h2 class="text-2xl font-bold mb-4">Tidak Menemukan Jawaban?</h2>
            <p class="text-blue-100 mb-6">Hubungi customer service kami untuk mendapatkan bantuan langsung</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="https://wa.me/081234567890" target="_blank" 
                   class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                    📱 WhatsApp
                </a>
                <a href="tel:021-1234-5678" 
                   class="bg-white text-blue-600 hover:bg-gray-100 px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                    📞 Telepon
                </a>
                <a href="{{ route('contact') }}" 
                   class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                    📧 Info Kontak
                </a>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqButtons = document.querySelectorAll('.faq-button');
    
    faqButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const content = document.getElementById(targetId);
            const icon = this.querySelector('.faq-icon');
            
            // Close all other FAQ items
            faqButtons.forEach(otherButton => {
                if (otherButton !== this) {
                    const otherTargetId = otherButton.getAttribute('data-target');
                    const otherContent = document.getElementById(otherTargetId);
                    const otherIcon = otherButton.querySelector('.faq-icon');
                    
                    otherContent.classList.add('hidden');
                    otherIcon.classList.remove('rotate-180');
                }
            });
            
            // Toggle current FAQ item
            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        });
    });
});
</script>
@endsection
