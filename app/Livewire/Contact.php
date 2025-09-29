<?php

namespace App\Livewire;

use App\Mail\ContactMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Contact extends Component
{
    public $name = '';

    public $email = '';

    public $subject = '';

    public $message = '';

    public $showSuccess = false;

    public $isSubmitting = false;

    protected $rules = [
        'name' => 'required|min:2|max:100',
        'email' => 'required|email|max:255',
        'subject' => 'required|min:5|max:200',
        'message' => 'required|min:10|max:1000',
    ];

    protected $messages = [
        'name.required' => 'Name is required.',
        'name.min' => 'Name must be at least 2 characters.',
        'email.required' => 'Email is required.',
        'email.email' => 'Please enter a valid email address.',
        'subject.required' => 'Subject is required.',
        'subject.min' => 'Subject must be at least 5 characters.',
        'message.required' => 'Message is required.',
        'message.min' => 'Message must be at least 10 characters.',
        'message.max' => 'Message must not exceed 1000 characters.',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submit()
    {
        $this->isSubmitting = true;

        $this->validate();

        try {
            $contactEmail = config('mail.contact_email', config('mail.from.address'));

            Mail::to($contactEmail)->send(new ContactMessage([
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->subject,
                'message' => $this->message,
            ]));

            $this->reset(['name', 'email', 'subject', 'message']);
            $this->resetErrorBag();
            $this->showSuccess = true;

            $this->dispatch('contact-sent');
        } catch (\Exception $e) {
            $this->addError('general', 'Sorry, there was an error sending your message. Please try again later.');
        }

        $this->isSubmitting = false;
    }

    public function render(): View
    {
        return view('livewire.contact');
    }
}
