@extends('public-pages.layout')

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
                   class="flex items-center justify-center bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                    </svg>
                    WhatsApp
                </a>
                <a href="tel:021-1234-5678" 
                   class="flex items-center justify-center bg-white text-blue-600 hover:bg-gray-100 px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                    </svg>
                    Telepon
                </a>
                <a href="{{ route('contact') }}" 
                   class="flex items-center justify-center bg-blue-700 hover:bg-blue-800 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Info Kontak
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
