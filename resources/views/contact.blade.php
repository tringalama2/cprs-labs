@extends('layouts.guest')

@push('head')
    <meta name="description" content="Contact EasyCPRSLabs - Get support, report issues, or provide feedback for our VA CPRS lab formatting tool. We're here to help healthcare providers optimize their workflow.">
    <meta name="keywords" content="contact EasyCPRSLabs, CPRS support, VA lab tool support, healthcare tool feedback, medical software support">
    <meta property="og:title" content="Contact EasyCPRSLabs - Support for Healthcare Providers">
    <meta property="og:description" content="Get in touch with the EasyCPRSLabs team for support, feedback, or questions about our VA CPRS lab formatting tool.">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Contact EasyCPRSLabs - We're Here to Help">
    <meta name="twitter:description" content="Reach out to our team for support with EasyCPRSLabs, the essential tool for formatting VA CPRS lab results.">
    <title>Contact Us - EasyCPRSLabs Support</title>
@endpush

@section('content')
    <div class="p-4 md:p-6 mx-auto max-w-5xl lg:max-w-7xl">
        <x-logo-and-title
            title="Contact"
            subtitle="We'd love to hear from you. Send us a message and we'll respond as soon as possible."
            :title_after="false"
        />

        <!-- Contact Form -->
        <div class="max-w-4xl mx-auto">
            <livewire:contact/>
        </div>

        <!-- Quick Links Section -->
        <div class="max-w-4xl mx-auto mt-16">
            <div class="bg-white rounded shadow-2xl shadow-black/10 ring-1 ring-white/5 p-8 md:p-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-8 text-center">Need Quick Answers?</h2>

                <div class="text-center">
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Before contacting us, you might find the answer you're looking for in our comprehensive FAQ
                        section.
                    </p>

                    <a href="{{ route('faq') }}" class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        View Frequently Asked Questions
                    </a>

                    <p class="text-sm text-gray-500 mt-4">
                        Common questions about privacy, lab types, troubleshooting, and more
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
