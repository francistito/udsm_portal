@extends('layouts.main', ['title' => 'Home', 'header' => 'Home' ])


@push('after-styles')
    <style>

    </style>
@endpush

@section('content')

    <section
        class="page-header page-header-modern page-header-background page-header-background-sm overlay overlay-color-primary overlay-show overlay-op-8 mb-5"
        style="background-image: url(img/page-header/page-header-elements.jpg);">
        <div class="container">
            <div class="row">
                <div class="col-md-12 align-self-center p-static order-2 text-center">
                    <h1>Forms</h1>

                </div>
                <div class="col-md-12 align-self-center order-1">
                    <ul class="breadcrumb breadcrumb-light d-block text-center">
                        <li><a href="#">Home</a></li>
                        <li class="active">Elements</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-2">

        <div class="row">
            <div class="col-lg-9 order-1 order-lg-2">

                <div class="card">
                    <div class="card-body">
                        <div class="offset-anchor" id="contact-sent"></div>

                        <div class="overflow-hidden mb-1">
                            <h2 class="font-weight-normal text-7 mb-0"><strong
                                    class="font-weight-extra-bold">Contact</strong> Us</h2>
                        </div>
                        <div class="overflow-hidden mb-4 pb-3">
                            <p class="mb-0">Feel free to ask for details, don't save any questions!</p>
                        </div>

                        <form id="contactFormAdvanced"
                              action="https://preview.oklerthemes.com/porto/8.0.0/elements-forms-advanced-contact.php#contact-sent"
                              method="POST" enctype="multipart/form-data">
                            <input type="hidden" value="true" name="emailSent" id="emailSent">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="required font-weight-bold text-dark text-2">Full Name</label>
                                    <input type="text" value="" data-msg-required="Please enter your name." maxlength="100"
                                           class="form-control" name="name" id="name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="required font-weight-bold text-dark text-2">Email Address</label>
                                    <input type="email" value="" data-msg-required="Please enter your email address."
                                           data-msg-email="Please enter a valid email address." maxlength="100"
                                           class="form-control" name="email" id="email" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label class="font-weight-bold text-dark text-2">Subject</label>
                                    <select data-msg-required="Please enter the subject." class="form-control"
                                            name="subject" id="subject" required>
                                        <option value="">...</option>
                                        <option value="Option 1">Option 1</option>
                                        <option value="Option 2">Option 2</option>
                                        <option value="Option 3">Option 3</option>
                                        <option value="Option 4">Option 4</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <p class="mb-2">Checkboxes</p>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input" name="checkboxes[]" type="checkbox"
                                                   data-msg-required="Please select at least one option."
                                                   id="inlineCheckbox1" value="option1"> 1
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input" name="checkboxes[]" type="checkbox"
                                                   data-msg-required="Please select at least one option."
                                                   id="inlineCheckbox1" value="option2"> 2
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input" name="checkboxes[]" type="checkbox"
                                                   data-msg-required="Please select at least one option."
                                                   id="inlineCheckbox1" value="option3"> 3
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <p class="mb-2">Radios</p>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="radios"
                                                   data-msg-required="Please select at least one option." id="inlineRadio1"
                                                   value="option1"> 1
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="radios"
                                                   data-msg-required="Please select at least one option." id="inlineRadio2"
                                                   value="option2"> 2
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="radios"
                                                   data-msg-required="Please select at least one option." id="inlineRadio3"
                                                   value="option3"> 3
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label class="font-weight-bold text-dark text-2">Attachment</label>
                                    <input class="d-block" type="file" name="attachment" id="attachment">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12 mb-4">
                                    <label class="required font-weight-bold text-dark text-2">Message</label>
                                    <textarea maxlength="5000" data-msg-required="Please enter your message." rows="6"
                                              class="form-control" name="message" id="message" required></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12 mb-0">
                                    <label class="font-weight-bold text-dark text-2">Human Verification</label>
                                </div>
                            </div>
                            <div class="form-row pt-2">
                                <div class="form-group col-md-4">
                                    <div class="captcha form-control">
                                        <div class="captcha-image">
                                            <img id="captcha-image"
                                                 src="php/simple-php-captcha/simple-php-captcha.php/porto/8.0.0/php/simple-php-captcha/simple-php-captchac815.html?_CAPTCHA&amp;t=0.22035000+1592322728"
                                                 alt="CAPTCHA code"></div>
                                        <div class="captcha-refresh">
                                            <a href="#" id="refreshCaptcha"><i class="fas fa-sync"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-8">
                                    <input type="text" value="" maxlength="6" data-msg-captcha="Wrong verification code."
                                           data-msg-required="Please enter the verification code."
                                           placeholder="Type the verification code."
                                           class="form-control form-control-lg captcha-input" name="captcha" id="captcha"
                                           required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <hr>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12 mb-5">
                                    <input type="submit" id="contactFormSubmit" value="Send Message"
                                           class="btn btn-primary btn-modern pull-right" data-loading-text="Loading...">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
        </div>




    </div>

@endsection
