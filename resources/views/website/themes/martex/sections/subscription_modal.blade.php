<div id="subscription-form" class="modal fade auto-off" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- CLOSE BUTTON -->
            <button type="button" class="btn-close ico-10 white-color" data-bs-dismiss="modal" aria-label="Close">
                <span class="flaticon-cancel"></span>
            </button>
            <!-- MODAL CONTENT -->
            <div class="modal-body text-center">
                <!-- IMAGE -->
                <div class="modal-body-img">
                    <img class="img-fluid" src="{{ static_asset('website/themes/martex/images/modal-newsletter-blue.jpg') }}" alt="content-image">
                </div> 
                <!-- NEWSLETTER FORM -->
                <div class="modal-body-content">
                    <!-- Title -->
                    <h5 class="s-24 w-700">{{ __('stay_up_to_date_with_our_news_ideas_and_updates') }}</h5>
                    <!-- Form -->
                    <form action="{{ route('subscribe.store') }}"  method="post" class="newsletter-form form"  novalidate="true"> 
                        @csrf
                        <div class="mb-2">
                            <input type="name"  class="form-control"
                            placeholder="{{ __('your_name') }}" required="" id="name" name="name" required="">
                            <div class="invalid-feedback text-danger"></div>
                        </div>
                        <div class="input-group">
                            <input type="email" autocomplete="off" class="form-control"
                                placeholder="{{ __('your_email_address') }}" required="" id="email" name="email">
                                <div class="invalid-feedback text-danger"></div>
                                <span class="input-group-btn">
                                <button type="submit" class="btn btn--theme hover--theme save">{{ __('subscribe_now') }}</button>
                            </span>
                            <button id="preloader" class="btn btn-primary d-none" type="button"
                                                        disabled>
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...
                                                    </button>
                        </div>
                        <!-- Newsletter Form Notification -->
                        <label for="s-email" class="form-notification"></label>
                    </form>
                </div> <!-- END NEWSLETTER FORM -->
            </div> <!-- END MODAL CONTENT -->
        </div>
    </div>
</div>
