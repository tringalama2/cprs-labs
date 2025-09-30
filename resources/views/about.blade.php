@extends('layouts.guest')

@push('head')
    <meta name="description" content="EasyCPRSLabs - The essential tool for medical residents, fellows, students, and healthcare providers to instantly beautify and organize VA CPRS lab results. Save time with clean, tabulated lab displays.">
    <meta name="keywords" content="CPRS labs, VA EHR, medical residents, lab results formatter, healthcare tools, medical students, clinical tools, VA hospital, lab organizer">
    <meta property="og:title" content="About EasyCPRSLabs - Essential Tool for Healthcare Providers">
    <meta property="og:description" content="Transform messy CPRS lab results into clean, organized tables. Perfect for medical residents, fellows, and students working in VA healthcare systems.">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="EasyCPRSLabs - Streamline Your Lab Review Process">
    <meta name="twitter:description" content="The go-to tool for healthcare professionals to beautify CPRS lab results and save valuable time during patient care.">
    <title>About EasyCPRSLabs - Essential Lab Formatting Tool for Healthcare Providers</title>
@endpush

@section('content')
    <div class="p-4 md:p-6 mx-auto max-w-5xl lg:max-w-7xl">
        <x-logo-and-title
            title="About"
            subtitle="The essential tool for healthcare providers working with VA CPRS systems"
            :title_after="false"
        />

        <!-- Main Content -->
        <div class="bg-white rounded shadow-2xl shadow-black/10 ring-1 ring-white/5 p-8 md:p-12 mb-8">
            <!-- Problem Statement -->
            <section class="mb-10">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Built for Healthcare Professionals Like You</h2>
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold text-sky-600 mb-4">The Challenge Every Medical Resident
                                                                            Faces</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            If you're a <strong>medical resident</strong>, <strong>fellow</strong>, or <strong>medical
                                                                                                               student</strong>
                            working in VA hospitals, you know the frustration: clicking back to previous days labs to
                            see if the autoimmune workup ordered on admission has resulted. CPRS lab results are hard to
                            read and even harder to analyze quickly.
                        </p>
                        <p class="text-gray-600 leading-relaxed">
                            When you're managing multiple patients and need to review lab results fast, every second
                            counts. Scrolling through disorganized lab data wastes precious time that should be spent on
                            patient care.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-sky-600 mb-4">The EasyCPRSLabs Solution</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            <strong>EasyCPRSLabs</strong> instantly transforms chaotic CPRS lab results into beautiful,
                                                          organized tables with intelligent highlighting and logical
                                                          grouping. What used to take minutes of mental parsing now
                                                          takes seconds.
                        </p>
                        <p class="text-gray-600 leading-relaxed">
                            See all your patient's labs at once, organized by panels (CBC, BMP, LFTs, etc.) with
                            abnormal values clearly highlighted.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Features -->
            <section class="mb-10">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Why Healthcare Providers Choose EasyCPRSLabs</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 p-6 rounded border border-gray-200">
                        <div class="text-sky-600 text-2xl mb-3">⚡</div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Instant Results</h3>
                        <p class="text-gray-600 text-sm">Copy from CPRS, paste, and get beautifully formatted labs in
                                                         under 10 seconds</p>
                    </div>
                    <div class="bg-gray-50 p-6 rounded border border-gray-200">
                        <div class="text-sky-600 text-2xl mb-3">📊</div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Smart Organization</h3>
                        <p class="text-gray-600 text-sm">Automatically groups labs into standard panels (CBC, BMP,
                                                         Lipids, etc.)</p>
                    </div>
                    <div class="bg-gray-50 p-6 rounded border border-gray-200">
                        <div class="text-sky-600 text-2xl mb-3">🎯</div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Abnormal Value Highlighting</h3>
                        <p class="text-gray-600 text-sm">Critical and abnormal values stand out with color-coded
                                                         highlighting</p>
                    </div>
                </div>
            </section>

            <!-- Target Audience -->
            <section class="mb-10">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Perfect For</h2>
                <div class="bg-gray-900 rounded p-8 text-white">
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="text-3xl mb-3">👨‍⚕️</div>
                            <h3 class="font-semibold mb-2 text-sky-400">Medical Residents</h3>
                            <p class="text-sm text-gray-400">Streamline your daily lab reviews during rounds</p>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl mb-3">🩺</div>
                            <h3 class="font-semibold mb-2 text-sky-400">Fellows</h3>
                            <p class="text-sm text-gray-400">Quickly analyze complex lab panels for subspecialty
                                                             care</p>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl mb-3">📚</div>
                            <h3 class="font-semibold mb-2 text-sky-400">Medical Students</h3>
                            <p class="text-sm text-gray-400">Learn lab interpretation with clearly organized results</p>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl mb-3">⚕️</div>
                            <h3 class="font-semibold mb-2 text-sky-400">Healthcare Providers</h3>
                            <p class="text-sm text-gray-400">Enhance patient care with efficient lab review</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- How It Works -->
            <section class="mb-10">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">How It Works</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="bg-sky-800 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 ring-4 ring-sky-600">
                            <span class="text-white font-light text-lg">1</span>
                        </div>
                        <h3 class="text-lg font-semibold mb-2 text-gray-600">Copy from CPRS</h3>
                        <p class="text-gray-600 text-sm">Select "All Tests By Date" in CPRS and copy your lab
                                                         results</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-sky-800 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 ring-4 ring-sky-600">
                            <span class="text-white font-light text-lg">2</span>
                        </div>
                        <h3 class="text-lg font-semibold mb-2 text-gray-600">Paste & Submit</h3>
                        <p class="text-gray-600 text-sm">Paste into our secure formatter and click submit</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-sky-800 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 ring-4 ring-sky-600">
                            <span class="text-white font-light text-lg">3</span>
                        </div>
                        <h3 class="text-lg font-semibold mb-2 text-gray-600">Beautiful Results</h3>
                        <p class="text-gray-600 text-sm">Get organized, highlighted lab results instantly</p>
                    </div>
                </div>
            </section>

            <!-- Privacy & Security -->
            <section class="mb-10">
                <div class="bg-gray-50 rounded p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Your Privacy Matters</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-sky-600 mb-2">🔒 HIPAA Conscious Design</h3>
                            <p class="text-gray-600 text-sm">Remove all patient identifiers before using. Lab data is
                                                             processed securely and never stored on our servers.</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-sky-600 mb-2">⚡ Real-time Processing</h3>
                            <p class="text-gray-600 text-sm">Data is formatted in real-time and immediately returned to
                                                             you. No databases, no storage, no tracking.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Call to Action -->
            <section class="text-center">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Ready to Transform Your Lab Review Process?</h2>
                <p class="text-lg text-gray-600 mb-6">Join thousands of healthcare providers who save time daily with
                                                      EasyCPRSLabs</p>
                <a href="{{ route('home') }}" class="inline-block bg-sky-600 hover:bg-sky-700 text-white font-medium py-3 px-6 rounded text-base transition-colors duration-200">
                    Try EasyCPRSLabs Now - It's Free!
                </a>
            </section>
        </div>

    </div>
@endsection
