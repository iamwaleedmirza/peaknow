@extends('static-pages.newBase.main')

@section('title') FAQ @endsection

@section('meta_description')
    What is Tadalafil? What is ED (Erectile Dysfunction)? Is Tadalafil safe? Is my information safe? Read our FAQs and feel safe with a US Licensed Medical Provider
@endsection

@section('css')
    <style>
        .faq-tab-list .active {
            background-color: #906596;
            color: var(--btn-text-color);
        }
    </style>
@endsection

@section('header')
    <header class="peaks pages-abt-us">
        <div class="overlay">
            @include('static-pages.newBase.components.navbar')
        </div>
    </header>
@endsection

@section('content')
    <div id="faq-page" class="section-peaks">

        <section class="section-faq pb-5">
            <div class="pt-4">
                <div class="mb-5">
                    <h4 class="text-center h4 h-color">Do you have any questions?</h4>
                    <p class="text-center t-normal t-color">
                        This list was created for you to get the full information about us and what we do
                    </p>
                </div>

                <div class="container">
                    <div class="row justify-content-center mx-0">
                        <div class="col-12 col-md-10 col-lg-8">

                            <div class="d-flex justify-content-center align-items-center">
                                <ul class="faq-tab-list nav nav-pills mb-3 d-flex flex-column flex-sm-row justify-content-center align-items-center gap-sm-3"
                                    id="pills-tab" role="tablist">
                                    <li class="nav-item mb-2 mb-sm-0" role="presentation">
                                        <button class="newBtn btn-peaks-outline active" id="pills-common-ques-tab"
                                                data-bs-toggle="pill" data-bs-target="#pills-common-ques" type="button"
                                                role="tab" aria-controls="pills-common-ques" aria-selected="true">
                                            COMMON QUESTIONS
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="newBtn btn-peaks-outline text-uppercase"
                                                id="pills-shipping-ques-tabs"
                                                data-bs-toggle="pill" data-bs-target="#pills-shipping-ques"
                                                type="button"
                                                role="tab" aria-controls="pills-shipping-ques" aria-selected="false">
                                            SHIPPING & DELIVERY
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-content" id="pills-tabContent">

                                <div class="tab-pane fade show active" id="pills-common-ques" role="tabpanel"
                                     aria-labelledby="pills-common-ques-tab">
                                    <div class="accordion accordion-flush" id="accordionCommonQues">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseOne" aria-expanded="true"
                                                        aria-controls="collapseOne">
                                                    What is Peaks Curative?
                                                </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse show"
                                                 aria-labelledby="headingOne"
                                                 data-bs-parent="#accordionCommonQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        Peaks Curative is an online medical referral service that helps
                                                        connect patients with doctors for the purpose of treating
                                                        Erectile
                                                        Dysfunction in men.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingTwo">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapseTwo" aria-expanded="false"
                                                        aria-controls="collapseTwo">
                                                    What is Erectile Dysfunction (ED)?
                                                </button>
                                            </h2>
                                            <div id="collapseTwo" class="accordion-collapse collapse"
                                                 aria-labelledby="headingTwo"
                                                 data-bs-parent="#accordionCommonQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        Erectile dysfunction (impotence) is the inability to get and
                                                        keep an
                                                        erection firm enough for sex. Having erection trouble from time
                                                        to
                                                        time isn't necessarily a cause for concern. If erectile
                                                        dysfunction
                                                        is an ongoing issue, however, it can cause stress, affect your
                                                        self-confidence and contribute to relationship problems.
                                                        Problems
                                                        getting or keeping an erection can also be a sign of an
                                                        underlying
                                                        health condition that needs treatment and a risk factor for
                                                        heart
                                                        disease.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingThree">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapseThree" aria-expanded="false"
                                                        aria-controls="collapseThree">
                                                    Is my information safe?
                                                </button>
                                            </h2>
                                            <div id="collapseThree" class="accordion-collapse collapse"
                                                 aria-labelledby="headingThree"
                                                 data-bs-parent="#accordionCommonQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        Yes. Your information will be sent in an encrypted format to our
                                                        doctors and pharmacy.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingFour">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapseFour" aria-expanded="false"
                                                        aria-controls="collapseFour">
                                                    Are the treatments offered on PeaksCurative.com FDA approved?
                                                </button>
                                            </h2>
                                            <div id="collapseFour" class="accordion-collapse collapse"
                                                 aria-labelledby="headingFour"
                                                 data-bs-parent="#accordionCommonQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        The compounded products offered on PeaksCurative.com use FDA
                                                        approved active pharmaceutical ingredients (API). Compounded
                                                        products are not FDA approved.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingFive">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapseFive" aria-expanded="false"
                                                        aria-controls="collapseFive">
                                                    Where is Peaks Curative available?
                                                </button>
                                            </h2>
                                            <div id="collapseFive" class="accordion-collapse collapse"
                                                 aria-labelledby="headingFive"
                                                 data-bs-parent="#accordionCommonQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        Peaks Curative is currently available for delivery in the state
                                                        of
                                                        Florida (FL) in the United States. More states are coming soon.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingSix">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapseSex" aria-expanded="false"
                                                        aria-controls="collapseSex">
                                                    Do I need RX from Peaks?
                                                </button>
                                            </h2>
                                            <div id="collapseSex" class="accordion-collapse collapse"
                                                 aria-labelledby="headingSix"
                                                 data-bs-parent="#accordionCommonQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        Yes. In order to buy or subscribe to Peaks Curative services you
                                                        need a RX from a doctor. Our products contain active ingredients
                                                        that require an RX. You can provide an RX or use the Peaks
                                                        Curative
                                                        service to get matched to a medical provider that reviews your
                                                        information and would send you a prescription if it is
                                                        considered
                                                        suitable or required for your treatment.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingSeven">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapseSeven" aria-expanded="false"
                                                        aria-controls="collapseSeven">
                                                    How much does it cost?
                                                </button>
                                            </h2>
                                            <div id="collapseSeven" class="accordion-collapse collapse"
                                                 aria-labelledby="headingSeven"
                                                 data-bs-parent="#accordionCommonQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        Peaks offers different packages depending on your needs and/or
                                                        RX.
                                                        We offer recurring monthly subscriptions where the product will
                                                        be
                                                        mailed to you and we also offer concierge service where the
                                                        patient
                                                        can customize their order and frequency of delivery. We offer
                                                        single
                                                        order plans starting at $49 and monthly subscriptions starting
                                                        at
                                                        $69.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingEight">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapseEight" aria-expanded="false"
                                                        aria-controls="collapseEight">
                                                    Is it safe and effective to receive an online RX?
                                                </button>
                                            </h2>
                                            <div id="collapseEight" class="accordion-collapse collapse"
                                                 aria-labelledby="headingEight"
                                                 data-bs-parent="#accordionCommonQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        Yes. Our doctors and pharmacy are licensed in Florida and New
                                                        York
                                                        and follow federal regulations on Rx and medication. It is also
                                                        very
                                                        effective because it can save a considerable amount of time and
                                                        reduce contact by not having to go in person.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingNinth">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapseNinth" aria-expanded="false"
                                                        aria-controls="collapseNinth">
                                                    Do I need to see a healthcare provider in person to use Peaks
                                                    Curative?
                                                </button>
                                            </h2>
                                            <div id="collapseNinth" class="accordion-collapse collapse"
                                                 aria-labelledby="headingNinth"
                                                 data-bs-parent="#accordionCommonQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        No. First, you will complete an online questionnaire to gather
                                                        information for your healthcare provider about your symptoms and
                                                        medical history. We will then match you with a licensed provider
                                                        who
                                                        will review the information and evaluate your treatment options.
                                                        Your provider might follow up with questions which you can
                                                        respond
                                                        to using our secure online message portal. If your treatment
                                                        plan
                                                        includes a prescription, it will be delivered to the shipping
                                                        address you provided in discreet packaging in a few days once
                                                        your
                                                        provider completes your evaluation.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingTen">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapseTen" aria-expanded="false"
                                                        aria-controls="collapseTen">
                                                    What is Tadalafil?
                                                </button>
                                            </h2>
                                            <div id="collapseTen" class="accordion-collapse collapse"
                                                 aria-labelledby="headingTen"
                                                 data-bs-parent="#accordionCommonQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        Tadalafil is used to treat male sexual function problems
                                                        (impotence
                                                        or erectile dysfunction-ED). In combination with sexual
                                                        stimulation,
                                                        tadalafil works by increasing blood flow to the penis to help a
                                                        man
                                                        get and keep an erection.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingEleven">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapseEleven" aria-expanded="false"
                                                        aria-controls="collapseEleven">
                                                    Does Tadalafil have side effects?
                                                </button>
                                            </h2>
                                            <div id="collapseEleven" class="accordion-collapse collapse"
                                                 aria-labelledby="headingEleven"
                                                 data-bs-parent="#accordionCommonQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        Yes. Even though they are uncommon, Tadalafil may cause side
                                                        effects. Tell your doctor if any of these symptoms are severe or
                                                        do
                                                        not go away: headache, indigestion or heartburn, nausea,
                                                        diarrhea,
                                                        flushing, pain in the stomach, back, muscles, arms or legs,
                                                        cough.
                                                        Some side effects can be serious. If you experience any of these
                                                        symptoms, call your doctor immediately or get emergency medical
                                                        treatment: sudden decrease or loss of vision (see below for more
                                                        information) blurred vision changes in color vision (seeing a
                                                        blue
                                                        tinge on objects or having difficulty telling the difference
                                                        between
                                                        blue and green) sudden decrease or loss of hearing (see below
                                                        for
                                                        more information) ringing in ears, erection that lasts longer
                                                        than 4
                                                        hours, dizziness, chest pain, hives, rash, difficulty breathing
                                                        or
                                                        swallowing, swelling of the face, throat, tongue, lips, eyes,
                                                        hands,
                                                        feet, ankles, or lower legs, blistering or peeling of skin.

                                                        Everyone responds differently to medications and treatments, so
                                                        If
                                                        you have any symptoms or concerns please contact us at
                                                        <a href="mailto:support@peakscurative.com">support@peakscurative.com</a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading12">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse12" aria-expanded="false"
                                                        aria-controls="collapse12">
                                                    What is Sildenafil?
                                                </button>
                                            </h2>
                                            <div id="collapse12" class="accordion-collapse collapse"
                                                 aria-labelledby="heading12"
                                                 data-bs-parent="#accordionCommonQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        Sildenafil relaxes muscles of the blood vessels and increases
                                                        blood
                                                        flow to particular areas of the body. Sildenafil under the name
                                                        Viagra is used to treat erectile dysfunction (impotence) in men.
                                                        Sildenafil can also be used to treat pulmonary arterial
                                                        hypertension
                                                        and improve exercise capacity in men and women.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading13">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse13" aria-expanded="false"
                                                        aria-controls="collapse13">
                                                    Does Sildenafil have side effects?
                                                </button>
                                            </h2>
                                            <div id="collapse13" class="accordion-collapse collapse"
                                                 aria-labelledby="heading13"
                                                 data-bs-parent="#accordionCommonQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        Dizziness, headache, flushing, or stomach upset may occur.
                                                        Vision
                                                        changes such as increased sensitivity to light, blurred vision,
                                                        or
                                                        trouble telling blue and green colors apart may also occur. If
                                                        any
                                                        of these effects persist or worsen, tell your doctor or
                                                        pharmacist
                                                        promptly. To reduce the risk of dizziness and lightheadedness,
                                                        get
                                                        up slowly when rising from a sitting or lying position.

                                                        Everyone responds differently to medications and treatments, so
                                                        If
                                                        you have any symptoms or concerns please contact us at
                                                        <a href="mailto:support@peakscurative.com">support@peakscurative.com</a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading14">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse14" aria-expanded="false"
                                                        aria-controls="collapse14">
                                                    What is the difference between Sildenafil vs Tadalafil?
                                                </button>
                                            </h2>
                                            <div id="collapse14" class="accordion-collapse collapse"
                                                 aria-labelledby="heading14"
                                                 data-bs-parent="#accordionCommonQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        Sildenafil works within an hour of taking the medication and for
                                                        its
                                                        full effect, should be taken on an empty stomach. The effects of
                                                        Sildenafil generally last four to five hours. Our Tadalafil has
                                                        two
                                                        formulations – one that can be taken 'as required', and one that
                                                        can
                                                        be taken daily.The 'as required' Tadalafil (generic Cialis) has
                                                        an
                                                        advantage over Sildenafil, it lasts a lot longer – up to 36
                                                        hours
                                                        (compared with 4-5 hours for Sildenafil). Some men prefer this
                                                        as it
                                                        allows for more spontaneity. Tadalafil also works faster than
                                                        Sildenafil – it generally works within 30 minutes, though for
                                                        some
                                                        men it can take up to an hour.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading15">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse15" aria-expanded="false"
                                                        aria-controls="collapse15">
                                                    What is compounding?
                                                </button>
                                            </h2>
                                            <div id="collapse15" class="accordion-collapse collapse"
                                                 aria-labelledby="heading15"
                                                 data-bs-parent="#accordionCommonQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        Drug compounding is often regarded as the process of combining,
                                                        mixing, or altering ingredients to create a medication tailored
                                                        to
                                                        the needs of an individual patient. Compounding includes the
                                                        combining of two or more drugs. Compounded drugs are not
                                                        FDA-approved.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading16">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse16" aria-expanded="false"
                                                        aria-controls="collapse16">
                                                    What is the return & refund policy?
                                                </button>
                                            </h2>
                                            <div id="collapse16" class="accordion-collapse collapse"
                                                 aria-labelledby="heading16"
                                                 data-bs-parent="#accordionCommonQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        Prescription treatments can not be refunded or returned. If an
                                                        error
                                                        occurred please contact us at
                                                        <a href="mailto:support@peakscurative.com">support@peakscurative.com</a>
                                                        or by our website.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading17">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse17" aria-expanded="false"
                                                        aria-controls="collapse17">
                                                    Why is Peaks Curative unable to ship to my state?
                                                </button>
                                            </h2>
                                            <div id="collapse17" class="accordion-collapse collapse"
                                                 aria-labelledby="heading17"
                                                 data-bs-parent="#accordionCommonQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        Peaks Curative is currently available only in Florida. Our team
                                                        is
                                                        working hard to be available in the whole United States. If you
                                                        are
                                                        in one state we currently don’t ship to, please register on the
                                                        “Contact Us” page and you will get 30% off on your subscription
                                                        when
                                                        we are available in your state.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading18">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse18" aria-expanded="false"
                                                        aria-controls="collapse18">
                                                    Do you ship outside the United States?
                                                </button>
                                            </h2>
                                            <div id="collapse18" class="accordion-collapse collapse"
                                                 aria-labelledby="heading18"
                                                 data-bs-parent="#accordionCommonQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        No. We currently do not ship outside the United States.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading19">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse19" aria-expanded="false"
                                                        aria-controls="collapse19">
                                                    What if I have a problem with my shipment?
                                                </button>
                                            </h2>
                                            <div id="collapse19" class="accordion-collapse collapse"
                                                 aria-labelledby="heading19"
                                                 data-bs-parent="#accordionCommonQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        We are sorry if you had an inconvenience with your order. Please
                                                        contact us at
                                                        <a href="mailto:support@peakscurative.com">support@peakscurative.com</a>
                                                        or through our website and our team will get in touch with you
                                                        as
                                                        soon as possible.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading20">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse20" aria-expanded="false"
                                                        aria-controls="collapse20">
                                                    Can I get coverage from my health insurance for Peaks Curative?
                                                </button>
                                            </h2>
                                            <div id="collapse20" class="accordion-collapse collapse"
                                                 aria-labelledby="heading20"
                                                 data-bs-parent="#accordionCommonQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        No (usually). Peaks Curative services and treatments are not
                                                        (normally) covered under health insurance. You may contact your
                                                        insurance company to see if they will reimburse you for the
                                                        prescription. However, you can easily compare our prices to the
                                                        other brands. Our team works really hard to provide a service
                                                        that
                                                        is accessible, making it way cheaper for most patients compared
                                                        to
                                                        going to a care physician and using their insurance.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="tab-pane fade" id="pills-shipping-ques" role="tabpanel"
                                     aria-labelledby="pills-shipping-ques-tabs">
                                    <div class="accordion accordion-flush" id="accordionShippingQues">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseOne" aria-expanded="true"
                                                        aria-controls="collapseOne">
                                                    Why is PEAKS CURATIVE unable to ship to my state?
                                                </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse show"
                                                 aria-labelledby="headingOne"
                                                 data-bs-parent="#accordionShippingQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        Each state has different telemedicine laws and PEAKS CURATIVE
                                                        staff
                                                        is working diligently to be available in all 50 states. If you
                                                        are
                                                        in one of the states where we currently do not ship to, please
                                                        share
                                                        your email address on the "GET STARTED" page and we will notify
                                                        you
                                                        when we are available in your state!
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingTwo">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapseTwo" aria-expanded="false"
                                                        aria-controls="collapseTwo">
                                                    Do you ship outside the United States?
                                                </button>
                                            </h2>
                                            <div id="collapseTwo" class="accordion-collapse collapse"
                                                 aria-labelledby="headingTwo"
                                                 data-bs-parent="#accordionShippingQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        At this time, PEAKS CURATIVE does not deliver outside the United
                                                        States. International shipping will be available in the near
                                                        future.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingThree">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapseThree" aria-expanded="false"
                                                        aria-controls="collapseThree">
                                                    Can I return my shipment?
                                                </button>
                                            </h2>
                                            <div id="collapseThree" class="accordion-collapse collapse"
                                                 aria-labelledby="headingThree"
                                                 data-bs-parent="#accordionShippingQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        We do not accept returned shipments. Our service is prescription
                                                        based and our medications cannot be returned. If you feel your
                                                        shipment was delivered by mistake. Please let our 24/7 customer
                                                        support agents help you via chat, or email at
                                                        <a href="mailto:support@peakscurative.com">support@peakscurative.com</a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingFour">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapseFour" aria-expanded="false"
                                                        aria-controls="collapseFour">
                                                    I did not receive my shipment, what do I do?
                                                </button>
                                            </h2>
                                            <div id="collapseFour" class="accordion-collapse collapse"
                                                 aria-labelledby="headingFour"
                                                 data-bs-parent="#accordionShippingQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        We are here to help. If the order is lost in the mail, we are
                                                        able
                                                        to reship free of charge one time to the same address. Reasons
                                                        why
                                                        you may not have received your package yet:
                                                    </p>
                                                    <ol>
                                                        <li>
                                                            Refer to your USPS tracking number sent to your email to
                                                            check
                                                            the status of your package. There may be a delay in
                                                            shipment.
                                                        </li>
                                                        <li>
                                                            The shipping address is invalid. Please log back into your
                                                            account and check that the shipping address provided is
                                                            correct.
                                                            Please notify us that the shipping address is incorrect and
                                                            we
                                                            will be happy to assist.
                                                        </li>
                                                        <li>
                                                            Your package is returning due to invalid address, refusal,
                                                            or
                                                            other reasons. Please contact support to resolve any issues
                                                            regarding your return to sender package. Email to
                                                            <a href="mailto:support@peakscurative.com">support@peakscurative.com</a>
                                                            or reach 24/7 Live Chat on our website.
                                                        </li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingFive">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapseFive" aria-expanded="false"
                                                        aria-controls="collapseFive">
                                                    Do you offer overnight shipping?
                                                </button>
                                            </h2>
                                            <div id="collapseFive" class="accordion-collapse collapse"
                                                 aria-labelledby="headingFive"
                                                 data-bs-parent="#accordionShippingQues">
                                                <div class="accordion-body">
                                                    <p>
                                                        Yes we offer overnight shipping! Overnight shipping is available
                                                        Monday-Thursday for guaranteed delivery if placed before 12PM
                                                        EST.
                                                        Orders placed after this time will be sent the next business
                                                        day.
                                                        Please note that PEAKS CURATIVE treatment includes prescription
                                                        medication, so each order or change in subscription must be
                                                        reviewed
                                                        by our provider team. This process can take 1-2 business days.
                                                        For
                                                        this reason, please ensure that any future requests for changes
                                                        are
                                                        made 3-5 business days before your next renewal so that the
                                                        correct
                                                        shipment will be sent out. At PEAKS CURATIVE, patient safety is
                                                        our
                                                        number one priority. All customer profiles must go through a
                                                        medical
                                                        review that usually takes 24-48 hours. Once your medication has
                                                        been
                                                        approved it will be processed and will ship your medication
                                                        shortly
                                                        thereafter. You will receive an email that contains tracking
                                                        information as soon as it ships. If your treatment is being
                                                        shipped
                                                        to a military base and/or a P.O. Box, we cannot offer overnight
                                                        shipping.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection

