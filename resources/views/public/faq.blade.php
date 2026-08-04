@extends('layouts.public')
@section('title', 'Frequently Asked Questions')

@section('content')
<header class="page-header">
  <div class="container">
    <span class="eyebrow">Need Help</span>
    <h1>Frequently Asked Questions</h1>
    <nav class="breadcrumb-sumas small mt-2"><a href="{{ route('home') }}">Home</a> <i class="fa-solid fa-angle-right mx-2" style="color:rgba(255,255,255,0.4)"></i> <span class="active">FAQs</span></nav>
  </div>
</header>

<section class="section-pad">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="accordion accordion-sumas" id="faqAccordion">
          @php
            $faqs = [
              ['q' => 'Who is eligible to apply for a hostel room?', 'a' => 'Any registered SUMAS student with an active matriculation number can apply. Priority is given to first-year students and those with documented medical or distance-related need.'],
              ['q' => 'How do I apply for hostel accommodation?', 'a' => 'Create a student account, log in to your dashboard, and submit a Hostel Application selecting your preferred block. You can track the status from your dashboard at any time.'],
              ['q' => 'How long does allocation take?', 'a' => 'Most applications are reviewed within 3-5 working days. You will receive a notification the moment your status changes to Approved or Rejected.'],
              ['q' => 'Can I choose my roommates?', 'a' => 'Roommate preference is not guaranteed but you may indicate a preferred roommate in the application notes.'],
              ['q' => 'What happens if my preferred hostel is full?', 'a' => 'You will be offered the next available block that matches your gender and level, or you can choose to remain on the waiting list.'],
              ['q' => 'How do I get my allocation slip?', 'a' => 'Once approved, visit "My Allocation" on your dashboard to view your room details and generate a printable allocation slip.'],
              ['q' => 'Is hostel accommodation renewed automatically each session?', 'a' => 'No. A fresh application is required at the start of every academic session.'],
              ['q' => 'Who do I contact for hostel issues?', 'a' => 'Reach the Hostel Office through the Contact Us page, or speak with your block\'s resident hall officer.'],
            ];
          @endphp
          @foreach($faqs as $i => $faq)
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button {{ $i === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}">{{ $faq['q'] }}</button></h2>
            <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion"><div class="accordion-body">{{ $faq['a'] }}</div></div>
          </div>
          @endforeach
        </div>

        <div class="glass-card text-center p-4 mt-5" style="background:var(--sumas-cream);">
          <h5 class="mb-2">Still have a question?</h5>
          <p class="text-muted small mb-3">Our hostel office team is happy to help with anything not covered here.</p>
          <a href="{{ route('contact') }}" class="btn btn-sumas-primary">Contact Hostel Office</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
