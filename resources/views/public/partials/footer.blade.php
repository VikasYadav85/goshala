<footer class="bg-saffron-900 text-saffron-50 mt-20">
    <div class="container mx-auto px-4 py-14 grid md:grid-cols-4 gap-10">
        <div class="md:col-span-2">
            <div class="bg-white/10 inline-block rounded-2xl p-3 mb-4">
                <img src="{{ asset('img/logo.png') }}" alt="Gopal Seva Samarpan Trust" class="h-20 w-auto">
            </div>
            <div class="text-xs uppercase tracking-widest text-saffron-300 mb-3">Goshala &amp; Cow Rescue</div>
            <p class="text-saffron-100/90 leading-relaxed mb-4 max-w-md">
                {{ $publicSettings['footer_about'] }}
            </p>

            <form method="POST" action="{{ route('subscribe') }}" class="flex max-w-md gap-2">
                @csrf
                <input type="email" name="email" required placeholder="Subscribe to seva updates"
                       class="flex-1 px-4 py-2 rounded-full bg-saffron-800 border border-saffron-700 text-saffron-50 placeholder-saffron-300 focus:outline-none focus:ring-2 focus:ring-saffron-400">
                <button class="btn btn-primary !bg-saffron-500 hover:!bg-saffron-400">Subscribe</button>
            </form>

            <div class="mt-6 flex items-center gap-3">
                <a href="{{ $publicSettings['social']['instagram'] }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-saffron-800 hover:bg-saffron-700 flex items-center justify-center" aria-label="Instagram">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 01-1.38-.9 3.7 3.7 0 01-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.17 15.58 2.16 15.2 2.16 12s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.42 2.17 8.8 2.16 12 2.16zm0 1.62c-3.15 0-3.52.01-4.76.07-.99.04-1.53.21-1.89.35-.48.18-.82.4-1.18.76-.36.36-.58.7-.76 1.18-.14.36-.31.9-.35 1.89-.06 1.24-.07 1.61-.07 4.76s.01 3.52.07 4.76c.04.99.21 1.53.35 1.89.18.48.4.82.76 1.18.36.36.7.58 1.18.76.36.14.9.31 1.89.35 1.24.06 1.61.07 4.76.07s3.52-.01 4.76-.07c.99-.04 1.53-.21 1.89-.35.48-.18.82-.4 1.18-.76.36-.36.58-.7.76-1.18.14-.36.31-.9.35-1.89.06-1.24.07-1.61.07-4.76s-.01-3.52-.07-4.76c-.04-.99-.21-1.53-.35-1.89a3.18 3.18 0 00-.76-1.18 3.18 3.18 0 00-1.18-.76c-.36-.14-.9-.31-1.89-.35-1.24-.06-1.61-.07-4.76-.07zm0 2.76a5.46 5.46 0 110 10.92 5.46 5.46 0 010-10.92zm0 1.62a3.84 3.84 0 100 7.68 3.84 3.84 0 000-7.68zm5.65-2.91a1.27 1.27 0 110 2.54 1.27 1.27 0 010-2.54z"/></svg>
                </a>
                <a href="{{ $publicSettings['social']['facebook'] }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-saffron-800 hover:bg-saffron-700 flex items-center justify-center" aria-label="Facebook">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.99 3.66 9.13 8.44 9.88v-6.99H7.9V12h2.54V9.8c0-2.51 1.49-3.89 3.77-3.89 1.1 0 2.24.2 2.24.2v2.46H15.2c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.45 2.89h-2.34v6.99C18.34 21.13 22 16.99 22 12z"/></svg>
                </a>
                <a href="{{ $publicSettings['social']['youtube'] }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-saffron-800 hover:bg-saffron-700 flex items-center justify-center" aria-label="YouTube">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.5 6.2a3 3 0 00-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 00.5 6.2C0 8.1 0 12 0 12s0 3.9.5 5.8a3 3 0 002.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 002.1-2.1c.5-1.9.5-5.8.5-5.8s0-3.9-.5-5.8zM9.6 15.6V8.4l6.3 3.6-6.3 3.6z"/></svg>
                </a>
                <a href="https://wa.me/{{ ltrim($publicSettings['whatsapp'] ?? '', '+') }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-saffron-800 hover:bg-saffron-700 flex items-center justify-center" aria-label="WhatsApp">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.5 3.5A11.6 11.6 0 0012 0C5.4 0 .1 5.4.1 12c0 2.1.6 4.1 1.6 5.9L0 24l6.3-1.7a11.9 11.9 0 005.7 1.5h.01c6.6 0 11.9-5.4 11.9-12 0-3.2-1.2-6.2-3.4-8.3zM12 21.8c-1.8 0-3.5-.5-5-1.4l-.4-.2-3.7 1 1-3.6-.2-.4A9.8 9.8 0 012.2 12C2.2 6.6 6.6 2.2 12 2.2c2.6 0 5.1 1 6.9 2.9 1.8 1.8 2.9 4.3 2.9 6.9 0 5.4-4.4 9.8-9.8 9.8zm5.4-7.4c-.3-.1-1.7-.8-2-.9-.3-.1-.5-.1-.7.1-.2.3-.8.9-1 1.1-.2.2-.4.2-.6.1-.3-.1-1.2-.5-2.3-1.4-.9-.8-1.5-1.7-1.7-2-.2-.3 0-.5.1-.6.1-.1.3-.4.5-.5.1-.2.2-.3.3-.5.1-.2 0-.4 0-.5-.1-.1-.7-1.7-.9-2.3-.2-.6-.4-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.4s1.1 2.8 1.2 3c.1.2 2.1 3.3 5.2 4.6 1.7.7 2.4.8 3.3.7.5-.1 1.7-.7 1.9-1.4.2-.7.2-1.2.2-1.4-.1-.1-.3-.2-.6-.4z"/></svg>
                </a>
            </div>
        </div>

        <div>
            <h3 class="font-display text-lg font-semibold mb-4">Quick Links</h3>
            <ul class="space-y-2 text-saffron-100/90">
                <li><a href="{{ route('about') }}" class="hover:text-white">About Us</a></li>
                <li><a href="{{ route('goshala') }}" class="hover:text-white">Our Goshala</a></li>
                <li><a href="{{ route('donations.index') }}" class="hover:text-white">Seva &amp; Donation</a></li>
                <li><a href="{{ route('campaigns.index') }}" class="hover:text-white">Campaigns</a></li>
                <li><a href="{{ route('events.index') }}" class="hover:text-white">Events &amp; Festivals</a></li>
                <li><a href="{{ route('blog.index') }}" class="hover:text-white">Blog / गौ सेवा ज्ञान</a></li>
                <li><a href="{{ route('volunteer.index') }}" class="hover:text-white">Become a Volunteer</a></li>
                <li><a href="{{ route('transparency') }}" class="hover:text-white">Transparency</a></li>
                <li><a href="{{ route('faqs') }}" class="hover:text-white">FAQs</a></li>
            </ul>
        </div>

        <div>
            <h3 class="font-display text-lg font-semibold mb-4">Reach Us</h3>
            <address class="not-italic text-saffron-100/90 leading-relaxed">
                {{ $publicSettings['address'] }}<br>
                <a href="tel:{{ $publicSettings['phone'] }}" class="hover:text-white">{{ $publicSettings['phone'] }}</a><br>
                <a href="mailto:{{ $publicSettings['email'] }}" class="hover:text-white">{{ $publicSettings['email'] }}</a>
            </address>

            <div class="mt-6 grid grid-cols-3 gap-2 text-center text-xs">
                <div class="bg-saffron-800 rounded-lg py-2">80G<br><span class="text-saffron-300">Tax exempt</span></div>
                <div class="bg-saffron-800 rounded-lg py-2">12A<br><span class="text-saffron-300">Certified</span></div>
                <div class="bg-saffron-800 rounded-lg py-2">NGO<br><span class="text-saffron-300">Registered</span></div>
            </div>
        </div>
    </div>

    <div class="border-t border-saffron-800">
        <div class="container mx-auto px-4 py-5 flex flex-col md:flex-row items-center justify-between gap-2 text-xs text-saffron-200">
            <div>© {{ date('Y') }} Gopal Seva Samarpan Trust. All rights reserved.</div>
            <div class="flex items-center gap-4">
                <a href="{{ route('faqs') }}" class="hover:text-white">FAQs</a>
                <a href="{{ route('contact.index') }}" class="hover:text-white">Contact</a>
                <a href="{{ route('admin.login') }}" class="hover:text-white">Admin</a>
            </div>
        </div>
    </div>
</footer>
