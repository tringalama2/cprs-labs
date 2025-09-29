@extends('layouts.guest')

@push('head')
    <meta name="description" content="Frequently Asked Questions about EasyCPRSLabs - Get answers about patient data safety, lab formatting, CPRS compatibility, and more for our VA lab results tool.">
    <meta name="keywords" content="EasyCPRSLabs FAQ, CPRS lab questions, VA lab tool help, medical software FAQ, healthcare tool questions">
    <meta property="og:title" content="FAQ - EasyCPRSLabs Frequently Asked Questions">
    <meta property="og:description" content="Find answers to common questions about EasyCPRSLabs, the essential tool for formatting VA CPRS lab results for healthcare providers.">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="EasyCPRSLabs FAQ - Common Questions Answered">
    <meta name="twitter:description" content="Get answers to frequently asked questions about using EasyCPRSLabs for VA CPRS lab result formatting.">
    <title>FAQ - Frequently Asked Questions | EasyCPRSLabs</title>
@endpush

@section('content')
<div class="p-4 md:p-6 mx-auto max-w-5xl lg:max-w-7xl">
    <!-- Logo and Title Section -->
    <div class="flex justify-center mb-6 md:mb-12">
        <div class="flex flex-col items-center gap-4">
            <div class="flex-shrink-0">
                <x-application-logo class="w-12 h-12 block fill-current text-sky-600"/>
            </div>
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-2">
                    <span class="text-sky-600">Easy</span><span class="text-gray-600">CPRSLabs</span> FAQ
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Frequently asked questions about using EasyCPRSLabs
                </p>
            </div>
        </div>
    </div>

    <!-- FAQ Content -->
    <div class="bg-white rounded shadow-2xl shadow-black/10 ring-1 ring-white/5 p-8 md:p-12">
        <div class="space-y-8">
            <!-- Security & Privacy -->
            <div class="border-b border-gray-200 pb-8">
                <h3 class="text-xl font-semibold text-sky-600 mb-4 flex items-center">
                    🔒 Is my patient data safe?
                </h3>
                <p class="text-gray-600 leading-relaxed mb-4">
                    <strong>Yes, absolutely!</strong> We take patient privacy very seriously and have designed EasyCPRSLabs with HIPAA-conscious principles:
                </p>
                <ul class="list-disc list-inside text-gray-600 leading-relaxed space-y-2 ml-4">
                    <li><strong>No data storage:</strong> We don't store any patient data on our servers</li>
                    <li><strong>Real-time processing:</strong> Lab results are processed in real-time and immediately returned to you</li>
                    <li><strong>No tracking:</strong> We don't track or log patient information</li>
                    <li><strong>Remove identifiers:</strong> Always remove patient identifiers before using our tool</li>
                    <li><strong>Secure transmission:</strong> All data is transmitted securely via HTTPS</li>
                </ul>
            </div>

            <!-- Lab Types -->
            <div class="border-b border-gray-200 pb-8">
                <h3 class="text-xl font-semibold text-sky-600 mb-4 flex items-center">
                    🩺 What types of labs can I format?
                </h3>
                <p class="text-gray-600 leading-relaxed mb-4">
                    EasyCPRSLabs can format most standard VA CPRS lab results, including:
                </p>
                <div class="grid md:grid-cols-2 gap-4">
                    <ul class="list-disc list-inside text-gray-600 leading-relaxed space-y-1">
                        <li>Complete Blood Count (CBC)</li>
                        <li>Basic Metabolic Panel (BMP)</li>
                        <li>Comprehensive Metabolic Panel (CMP)</li>
                        <li>Lipid panels</li>
                        <li>Liver function tests (LFTs)</li>
                        <li>Thyroid function tests</li>
                    </ul>
                    <ul class="list-disc list-inside text-gray-600 leading-relaxed space-y-1">
                        <li>Coagulation studies (PT/INR, PTT)</li>
                        <li>Cardiac markers</li>
                        <li>Inflammatory markers (ESR, CRP)</li>
                        <li>Urinalysis</li>
                        <li>Microbiology results</li>
                        <li>Many other standard panels</li>
                    </ul>
                </div>
                <p class="text-gray-600 leading-relaxed mt-4">
                    The tool automatically organizes results into standard panels with intelligent highlighting for abnormal values.
                </p>
            </div>

            <!-- Parsing Issues -->
            <div class="border-b border-gray-200 pb-8">
                <h3 class="text-xl font-semibold text-sky-600 mb-4 flex items-center">
                    ⚡ Why isn't my lab parsing correctly?
                </h3>
                <p class="text-gray-600 leading-relaxed mb-4">
                    If labs aren't parsing correctly, here are the most common solutions:
                </p>
                <div class="bg-gray-50 rounded-lg p-6 mb-4">
                    <h4 class="font-semibold text-gray-800 mb-3">Step-by-step copying from CPRS:</h4>
                    <ol class="list-decimal list-inside text-gray-600 leading-relaxed space-y-2">
                        <li>Open the <strong>"Labs"</strong> tab in CPRS</li>
                        <li>Select <strong>"All Tests By Date"</strong> from the dropdown</li>
                        <li>Use <code class="bg-gray-200 px-2 py-1 rounded text-sm">Ctrl+A</code> (or <code class="bg-gray-200 px-2 py-1 rounded text-sm">Cmd+A</code> on Mac) to select all</li>
                        <li>Use <code class="bg-gray-200 px-2 py-1 rounded text-sm">Ctrl+C</code> (or <code class="bg-gray-200 px-2 py-1 rounded text-sm">Cmd+C</code> on Mac) to copy</li>
                        <li>Paste directly into EasyCPRSLabs</li>
                    </ol>
                </div>
                <p class="text-gray-600 leading-relaxed">
                    Some custom or rare lab formats may not be recognized yet. If you encounter persistent issues, please contact us with examples (with patient info removed) to help us improve the tool.
                </p>
            </div>

            <!-- Cost -->
            <div class="border-b border-gray-200 pb-8">
                <h3 class="text-xl font-semibold text-sky-600 mb-4 flex items-center">
                    💰 Is EasyCPRSLabs free?
                </h3>
                <p class="text-gray-600 leading-relaxed">
                    <strong>Yes, EasyCPRSLabs is completely free</strong> for all healthcare providers. We built this tool to help improve patient care and reduce the time spent on administrative tasks. There are no hidden fees, subscriptions, or usage limits.
                </p>
                <p class="text-gray-600 leading-relaxed mt-3">
                    If you find the tool helpful, you can support us through our <a href="https://www.buymeacoffee.com/tringali" target="_blank" class="text-sky-600 hover:text-sky-700 underline">sponsor link</a> to help with hosting costs and continued development.
                </p>
            </div>

            <!-- Feature Requests -->
            <div class="border-b border-gray-200 pb-8">
                <h3 class="text-xl font-semibold text-sky-600 mb-4 flex items-center">
                    🚀 Can I request new features?
                </h3>
                <p class="text-gray-600 leading-relaxed mb-4">
                    <strong>Absolutely!</strong> We're always looking to improve EasyCPRSLabs based on feedback from healthcare providers like you.
                </p>
                <div class="bg-sky-50 rounded-lg p-6">
                    <h4 class="font-semibold text-sky-800 mb-3">Ways to suggest improvements:</h4>
                    <ul class="list-disc list-inside text-sky-700 leading-relaxed space-y-2">
                        <li>Use our <a href="{{ route('contact') }}" class="text-sky-600 hover:text-sky-800 underline">contact form</a> to suggest new features</li>
                        <li>Report parsing issues with specific lab types</li>
                        <li>Request support for new lab formats</li>
                        <li>Suggest UI/UX improvements</li>
                    </ul>
                </div>
            </div>

            <!-- Troubleshooting -->
            <div class="border-b border-gray-200 pb-8">
                <h3 class="text-xl font-semibold text-sky-600 mb-4 flex items-center">
                    🔧 Common troubleshooting tips
                </h3>
                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Labs not showing up at all:</h4>
                        <ul class="list-disc list-inside text-gray-600 leading-relaxed space-y-1 ml-4">
                            <li>Make sure you copied from the "All Tests By Date" view</li>
                            <li>Check that you actually have lab results in the date range</li>
                            <li>Try copying a smaller date range first</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Some labs missing or in "Unparsable" section:</h4>
                        <ul class="list-disc list-inside text-gray-600 leading-relaxed space-y-1 ml-4">
                            <li>These may be non-standard or custom lab formats</li>
                            <li>Check the original CPRS output to verify the format</li>
                            <li>Contact us with examples to help improve parsing</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Wrong reference ranges or highlighting:</h4>
                        <ul class="list-disc list-inside text-gray-600 leading-relaxed space-y-1 ml-4">
                            <li>Our tool uses standard reference ranges</li>
                            <li>Some labs may have facility-specific ranges</li>
                            <li>Always verify critical values against the original CPRS output</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Browser Compatibility -->
            <div class="border-b border-gray-200 pb-8">
                <h3 class="text-xl font-semibold text-sky-600 mb-4 flex items-center">
                    🌐 Which browsers work best?
                </h3>
                <p class="text-gray-600 leading-relaxed mb-4">
                    EasyCPRSLabs works on all modern browsers. For the best experience, we recommend:
                </p>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Recommended:</h4>
                        <ul class="list-disc list-inside text-gray-600 leading-relaxed space-y-1">
                            <li>Chrome (latest version)</li>
                            <li>Firefox (latest version)</li>
                            <li>Safari (latest version)</li>
                            <li>Edge (latest version)</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Mobile devices:</h4>
                        <ul class="list-disc list-inside text-gray-600 leading-relaxed space-y-1">
                            <li>iOS Safari</li>
                            <li>Android Chrome</li>
                            <li>Mobile-optimized design</li>
                            <li>Touch-friendly interface</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Still Need Help -->
            <div>
                <h3 class="text-xl font-semibold text-sky-600 mb-4 flex items-center">
                    💬 Still need help?
                </h3>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Can't find the answer you're looking for? We're here to help healthcare providers get the most out of EasyCPRSLabs.
                </p>
                <div class="text-center">
                    <a href="{{ route('contact') }}" class="inline-block bg-sky-600 hover:bg-sky-700 text-white font-medium py-3 px-6 rounded text-base transition-colors duration-200">
                        Contact Our Support Team
                    </a>
                </div>
                <p class="text-center text-sm text-gray-500 mt-4">
                    We typically respond within 24 hours
                </p>
            </div>
        </div>
    </div>
</div>
@endsection