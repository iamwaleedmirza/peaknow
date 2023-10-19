@extends('user.base.main')

@section('title') Medical Questions @endsection

@section('css')
    <link href="{{ asset('/css/peaks/auth.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet"
          type="text/css"/>
    <style>
        #questionnaire-form {
            display: none;
        }

        .tab {
            display: none;
        }

        .invalid {
            border: 1px solid #ea5f5f;
        }

        p {
            text-align: justify;
        }
    </style>
@endsection

@section('content')
    <section class="text-center">
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-10 col-lg-8">

                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block mt-3">
                        <button type="button" class="close" id="close_click" data-dismiss="alert" aria-label="Close">×
                        </button>
                        <span>{{ $message }}</span>
                    </div>
                @endif

                <div id="questionnaire-intro">
                    <div class="mb-4">
                        <h4>
                            We need to ask you a few questions, so that our doctors can help you.
                        </h4>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="card-questions-info text-start">
                            <p>
                                These questions are necessary for our doctors to properly prescribe you medications.
                            </p>
                            <p><strong>It will be quick and easy !</strong></p>
                        </div>
                    </div>

                    <div class="form-group mb-5 mt-5">
                        <button id="btn-lets-go" type="button" class="btn btn-peaks">Let’s start!</button>
                    </div>
                </div>

                <form id="questionnaire-form" class="mb-5" method="POST" action="{{ route('medical-answers') }}">
                    @csrf

                    <!-- Question Tabs start -->
                    <div class="row justify-content-center px-0">
                        <div class="col-12 col-md-8">

                            <!-- Tab 1 -->
                            <div  @if (Auth::check() && Auth::user()->gender !==null) class="" style="display:none;" @else  class="tab" @endif>
                                <!-- Question 1 -->
                                <div>
                                    <p class="h5 mb-5 text-center">What is your gender?</p>
                                    <input type="hidden" name="que_1" value="{{ que_1 }}">
                                    <div class="row justify-content-center px-0">
                                        <div class="col-12 col-md-8 col-lg-6">

                                            @if (Auth::check() && Auth::user()->gender !==null)
                                            <div class="form-check text-start mb-3">
                                                <input class="form-check-input" id="option_others" name="ans_1"
                                                       type="radio" value="{{Auth::user()->gender}}" checked required>
                                                <label class="form-check-label" for="option_others">{{Auth::user()->gender}}</label>
                                            </div>
                                            @else
                                            <div class="form-check text-start mb-3">
                                                <input class="form-check-input" id="option_male" name="ans_1"
                                                       type="radio" value="Male" checked required>
                                                <label class="form-check-label" for="option_male">Male</label>
                                            </div>
                                            <div class="form-check text-start mb-3">
                                                <input class="form-check-input" id="option_female" name="ans_1"
                                                       type="radio" value="Female" required>
                                                <label class="form-check-label" for="option_female">Female</label>
                                            </div>
                                            <div class="form-check text-start mb-3">
                                                <input class="form-check-input" id="option_others" name="ans_1"
                                                       type="radio" value="Others" required>
                                                <label class="form-check-label" for="option_others">Others</label>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 1 -->
                            <div  @if (Auth::check() && Auth::user()->dob !==null) class="" style="display:none;" @else  class="tab"  @endif>
                                <!-- Question 2 -->
                                <div>
                                    <p class="h5 text-center">Date of Birth</p>
                                    <p class="mb-5 text-center">{MM/DD/YYYY}</p>

                                    <input type="hidden" name="que_2" value="{{ que_2 }}">

                                    @if (Auth::check() && Auth::user()->dob !==null)
                                    <div class="row justify-content-center px-0">
                                    <div class="col-12 col-md-8 col-lg-8">
                                        <input value="{{Auth::user()->dob}}"
                                        id="ans_2" class="input-field input-primary birth_date"
                                               type="text" name="ans_2" placeholder="MM/DD/YYYY" required>
                                    </div>
                                    </div>
                                    @else
                                    <div class="row justify-content-center px-0">
                                        <div class="col-12 col-md-8 col-lg-8">
                                            <input id="ans_2" class="input-field input-primary birth_date"
                                                   type="text" name="ans_2" placeholder="MM/DD/YYYY" required>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Tab 2 -->
                            <div class="tab">
                                <!-- Question 3 -->
                                <p class="h5 mb-5">
                                    Certain symptoms can be a sign of a more serious medical problem and you should see
                                    a doctor in person instead. Have you recently experienced, or have you ever
                                    experienced any of the following symptoms? Select all that apply.
                                </p>
                                <input type="hidden" name="que_3__1" value="{{ que_3__1 }}">
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q3_op1" name="ans_3__1[]" type="checkbox"
                                           value="Chest pain or shortness of breath when climbing 2 flights of stairs or walking 4 blocks"
                                           required>
                                    <label class="form-check-label" for="q3_op1">
                                        Chest pain or shortness of breath when climbing 2 flights of stairs or walking 4
                                        blocks
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q3_op2" name="ans_3__1[]" type="checkbox"
                                           value="Chest pain or shortness of breath with sexual activity" required>
                                    <label class="form-check-label" for="q3_op2">
                                        Chest pain or shortness of breath with sexual activity
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q3_op3" name="ans_3__1[]" type="checkbox"
                                           value="Any episodes of unexplained fainting, lightheadedness, or dizziness"
                                           required>
                                    <label class="form-check-label" for="q3_op3">
                                        Any episodes of unexplained fainting, lightheadedness, or dizziness
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q3_op4" name="ans_3__1[]" type="checkbox"
                                           value="Cramping of the legs with exercise" required>
                                    <label class="form-check-label" for="q3_op4">
                                        Cramping of the legs with exercise
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q3_op5" name="ans_3__1[]" type="checkbox"
                                           value="Abnormal heartbeats or rhythms" required>
                                    <label class="form-check-label" for="q3_op5">
                                        Abnormal heartbeats or rhythms
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q3_op6" name="ans_3__1[]" type="checkbox"
                                           value="I've never experienced any of the above symptoms." required>
                                    <label class="form-check-label" for="q3_op6">
                                        I've never experienced any of the above symptoms.
                                    </label>
                                </div>

                                <div id="div_que_3__2" class="mt-5" style="display: none">
                                    <p class="mb-2 t-small t-color-red">
                                        These symptoms can be a sign of a more serious medical problem and the doctor
                                        will need more information when reviewing your profile. Please explain your
                                        symptoms in detail and when you last experienced them.
                                    </p>
                                    <input type="hidden" name="que_3__2" value="{{ que_3__2 }}">
                                    <textarea class="input-primary" id="ans_3__2" name="ans_3__2" rows="3"></textarea>
                                </div>
                            </div>

                            <!-- Tab 3 -->
                            <div class="tab">
                                <!-- Question 4 -->
                                <p class="h5 mb-5">
                                    Do you have any of the following conditions that could make your ED too complex for
                                    us to manage online? Select all that apply.
                                </p>
                                <input type="hidden" name="que_4__1" value="{{ que_4__1 }}">
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q4_op1" name="ans_4__1[]" type="checkbox"
                                           value="Any condition where sex is not advised" required>
                                    <label class="form-check-label" for="q4_op1">
                                        Any condition where sex is not advised
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q4_op2" name="ans_4__1[]" type="checkbox"
                                           value="Kidney problems including having/had a kidney transplant" required>
                                    <label class="form-check-label" for="q4_op2">
                                        Kidney problems including having/had a kidney transplant
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q4_op3" name="ans_4__1[]" type="checkbox"
                                           value="Liver problems" required>
                                    <label class="form-check-label" for="q4_op3">
                                        Liver problems
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q4_op4" name="ans_4__1[]" type="checkbox"
                                           value="Neurological problems like Parkinson's, Multiple Sclerosis, or Motor Neuron Disease"
                                           required>
                                    <label class="form-check-label" for="q4_op4">
                                        Neurological problems like Parkinson's, Multiple Sclerosis, or Motor Neuron Disease
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q4_op5" name="ans_4__1[]" type="checkbox"
                                           value="HIV / AIDS" required>
                                    <label class="form-check-label" for="q4_op5">
                                        HIV / AIDS
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q4_op6" name="ans_4__1[]" type="checkbox"
                                           value="Spinal Injury Or Paralysis" required>
                                    <label class="form-check-label" for="q4_op6">
                                        Spinal Injury Or Paralysis
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q4_op7" name="ans_4__1[]" type="checkbox"
                                           value="Previous surgery on your prostate or pelvis" required>
                                    <label class="form-check-label" for="q4_op7">
                                        Previous surgery on your prostate or pelvis
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q4_op8" name="ans_4__1[]" type="checkbox"
                                           value="Radiation therapy to your pelvis" required>
                                    <label class="form-check-label" for="q4_op8">
                                        Radiation therapy to your pelvis
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q4_op9" name="ans_4__1[]" type="checkbox"
                                           value="Low testosterone" required>
                                    <label class="form-check-label" for="q4_op9">
                                        Low testosterone
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q4_op10" name="ans_4__1[]" type="checkbox"
                                           value="No, I don't have any of the above conditions." required>
                                    <label class="form-check-label" for="q4_op10">
                                        No, I don't have any of the above conditions.
                                    </label>
                                </div>

                                <div id="div_que_4__2" class="mt-5" style="display: none">
                                    <p class="mb-2 t-small t-color-red">
                                        This condition(s) could make your ED too complex to be managed through
                                        telemedicine and the doctor will need more information when reviewing your
                                        profile. Please explain your condition(s) in detail.
                                    </p>
                                    <input type="hidden" name="que_4__2" value="{{ que_4__2 }}">
                                    <textarea class="input-primary" id="ans_4__2" name="ans_4__2" rows="3"></textarea>
                                </div>
                            </div>

                            <!-- Tab 4 -->
                            <div class="tab">
                                <!-- Question 5 -->
                                <p class="h5 mb-5">
                                    Do you have any of these genital conditions that can make having sex difficult?
                                    Select all that apply.
                                </p>
                                <input type="hidden" name="que_5" value="{{ que_5 }}">
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input ans_5" id="q5_op1" name="ans_5[]" type="checkbox"
                                           value="Physical abnormalities of the penis / curving or bending of the penis making it hard for sex including Peyronie's disease"
                                           required>
                                    <label class="form-check-label" for="q5_op1">
                                        Physical abnormalities of the penis / curving or bending of the penis making it
                                        hard for sex including Peyronie's disease
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input ans_5" id="q5_op2" name="ans_5[]" type="checkbox"
                                           value="Scarring of the penis (feels like lumps or hard tissue under the skin)"
                                           required>
                                    <label class="form-check-label" for="q5_op2">
                                        Scarring of the penis (feels like lumps or hard tissue under the skin)
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input ans_5" id="q5_op3" name="ans_5[]" type="checkbox"
                                           value="Pain with erections" required>
                                    <label class="form-check-label" for="q5_op3">
                                        Pain with erections
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input ans_5" id="q5_op4" name="ans_5[]" type="checkbox"
                                           value="Tight foreskin" required>
                                    <label class="form-check-label" for="q5_op4">
                                        Tight foreskin
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input ans_5" id="q5_op5" name="ans_5[]" type="checkbox"
                                           value="No, I don't have any of the above genital conditions." required>
                                    <label class="form-check-label" for="q5_op5">
                                        No, I don't have any of the above genital conditions.
                                    </label>
                                </div>
                            </div>

                            <!-- Tab 5 -->
                            <div class="tab">
                                <!-- Question 6 -->
                                <p class="h5 mb-5">
                                    Do you currently have, or have you ever had any of the following
                                    heart/cardiovascular conditions? Select all that apply.
                                </p>
                                <input type="hidden" name="que_6__1" value="{{ que_6__1 }}">
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q6_op1" name="ans_6__1[]" type="checkbox"
                                           value="Low blood pressure" required>
                                    <label class="form-check-label" for="q6_op1">
                                        Low blood pressure
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q6_op2" name="ans_6__1[]" type="checkbox"
                                           value="Angina (chest pain or shortness of breath)" required>
                                    <label class="form-check-label" for="q6_op2">
                                        Angina (chest pain or shortness of breath)
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q6_op3" name="ans_6__1[]" type="checkbox"
                                           value="Heart disease or heart attack" required>
                                    <label class="form-check-label" for="q6_op3">
                                        Heart disease or heart attack
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q6_op4" name="ans_6__1[]" type="checkbox"
                                           value="Heart stent or open-heart bypass surgery"
                                           required>
                                    <label class="form-check-label" for="q6_op4">
                                        Heart stent or open-heart bypass surgery
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q6_op5" name="ans_6__1[]" type="checkbox"
                                           value="Heart failure" required>
                                    <label class="form-check-label" for="q6_op5">
                                        Heart failure
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q6_op6" name="ans_6__1[]" type="checkbox"
                                           value="Stroke" required>
                                    <label class="form-check-label" for="q6_op6">
                                        Stroke
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q6_op7" name="ans_6__1[]" type="checkbox"
                                           value="Have history or family history of QT prolongation" required>
                                    <label class="form-check-label" for="q6_op7">
                                        Have history or family history of QT prolongation
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q6_op8" name="ans_6__1[]" type="checkbox"
                                           value="Abnormal heartbeat/rhythm—rapid, irregular, unusually slow (fewer than 60 beats/minute)"
                                           required>
                                    <label class="form-check-label" for="q6_op8">
                                        Abnormal heartbeat/rhythm—rapid, irregular, unusually slow (fewer than 60
                                        beats/minute)
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q6_op9" name="ans_6__1[]" type="checkbox"
                                           value="Heart valve problems" required>
                                    <label class="form-check-label" for="q6_op9">
                                        Heart valve problems
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q6_op10" name="ans_6__1[]" type="checkbox"
                                           value="Hypertrophic obstructive cardiomyopathy (HCM)" required>
                                    <label class="form-check-label" for="q6_op10">
                                        Hypertrophic obstructive cardiomyopathy (HCM)
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q6_op11" name="ans_6__1[]" type="checkbox"
                                           value="Peripheral vascular disease or claudication (other than regular muscle soreness with exercise, cramping or pain in the calves or thighs)"
                                           required>
                                    <label class="form-check-label" for="q6_op11">
                                        Peripheral vascular disease or claudication (other than regular muscle soreness
                                        with exercise, cramping or pain in the calves or thighs)
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q6_op12" name="ans_6__1[]" type="checkbox"
                                           value="None Apply" required>
                                    <label class="form-check-label" for="q6_op12">
                                        None Apply
                                    </label>
                                </div>

                                <div id="div_que_6__2" class="mt-5" style="display: none">
                                    <p class="mb-2 t-small t-color-red">
                                        Having certain heart/cardiovascular conditions may require special consideration
                                        and the doctor will need more information when reviewing your profile. Please
                                        explain your heart/cardiovascular condition(s) in detail.
                                    </p>
                                    <input type="hidden" name="que_6__2" value="{{ que_6__2 }}">
                                    <textarea class="input-primary" id="ans_6__2" name="ans_6__2" rows="3"></textarea>

                                </div>
                            </div>

                            <!-- Tab 6 -->
                            <div class="tab">
                                <!-- Question 7 -->
                                <p class="h5 mb-5">
                                    It can be life-threatening to take ED medications if you have certain medical
                                    conditions. Do you currently have, or have you ever had any of the following medical
                                    conditions? Select all that apply.
                                </p>
                                <input type="hidden" name="que_7__1" value="{{ que_7__1 }}">
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q7_op1" name="ans_7__1[]" type="checkbox"
                                           value="Priapism (erection lasting longer than 4 hours)" required>
                                    <label class="form-check-label" for="q7_op1">
                                        Priapism (erection lasting longer than 4 hours)
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q7_op2" name="ans_7__1[]" type="checkbox"
                                           value="Retinitis pigmentosa" required>
                                    <label class="form-check-label" for="q7_op2">
                                        Retinitis pigmentosa
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q7_op3" name="ans_7__1[]" type="checkbox"
                                           value="Anterior ischemic optic neuropathy (AION)" required>
                                    <label class="form-check-label" for="q7_op3">
                                        Anterior ischemic optic neuropathy (AION)
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q7_op4" name="ans_7__1[]" type="checkbox"
                                           value="Sickle cell disease"
                                           required>
                                    <label class="form-check-label" for="q7_op4">
                                        Sickle cell disease
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q7_op5" name="ans_7__1[]" type="checkbox"
                                           value="Blood clotting disorder / abnormal bleeding or bruising / or coagulopathy"
                                           required>
                                    <label class="form-check-label" for="q7_op5">
                                        Blood clotting disorder / abnormal bleeding or bruising / or coagulopathy
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q7_op6" name="ans_7__1[]" type="checkbox"
                                           value="Myeloma or leukemia" required>
                                    <label class="form-check-label" for="q7_op6">
                                        Myeloma or leukemia
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q7_op7" name="ans_7__1[]" type="checkbox"
                                           value="Stomach or intestinal ulcer" required>
                                    <label class="form-check-label" for="q7_op7">
                                        Stomach or intestinal ulcer
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q7_op8" name="ans_7__1[]" type="checkbox"
                                           value="No, I've never had any of these medical conditions."
                                           required>
                                    <label class="form-check-label" for="q7_op8">
                                        No, I've never had any of these medical conditions.
                                    </label>
                                </div>

                                <div id="div_que_7__2" class="mt-5" style="display: none">
                                    <p class="mb-2 t-small t-color-red">
                                        Taking ED medications while having this condition(s) can be life-threatening and
                                        the doctor will need more information when reviewing your profile. Please
                                        explain your condition(s) in detail.
                                    </p>
                                    <input type="hidden" name="que_7__2" value="{{ que_7__2 }}">
                                    <textarea class="input-primary" id="ans_7__2" name="ans_7__2" rows="3"></textarea>
                                </div>
                            </div>

                            <!-- Tab 7 -->
                            <div class="tab">
                                <!-- Question 8 -->
                                <p class="h5 mb-5">
                                    Have you ever been diagnosed with any of the following? Select all that apply.
                                </p>
                                <input type="hidden" name="que_8" value="{{ que_8 }}">
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q8_op1" name="ans_8[]" type="checkbox"
                                           value="Diabetes" required>
                                    <label class="form-check-label" for="q8_op1">
                                        Diabetes
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q8_op2" name="ans_8[]" type="checkbox"
                                           value="High Blood Pressure" required>
                                    <label class="form-check-label" for="q8_op2">
                                        High Blood Pressure
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q8_op3" name="ans_8[]" type="checkbox"
                                           value="High Cholesterol" required>
                                    <label class="form-check-label" for="q8_op3">
                                        High Cholesterol
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q8_op4" name="ans_8[]" type="checkbox"
                                           value="My father had a heart attack or heart disease at 55 years or younger"
                                           required>
                                    <label class="form-check-label" for="q8_op4">
                                        My father had a heart attack or heart disease at 55 years or younger
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q8_op5" name="ans_8[]" type="checkbox"
                                           value="My mother had a heart attack or heart disease at 65 years or younger"
                                           required>
                                    <label class="form-check-label" for="q8_op5">
                                        My mother had a heart attack or heart disease at 65 years or younger
                                    </label>
                                </div>
                                <div class="form-check text-start mb-3">
                                    <input class="form-check-input" id="q8_op6" name="ans_8[]" type="checkbox"
                                           value="No, I've never received these diagnoses." required>
                                    <label class="form-check-label" for="q8_op6">
                                        No, I've never received these diagnoses.
                                    </label>
                                </div>
                            </div>

                            <!-- Tab 8 -->
                            <div class="tab">
                                <!-- Question 9 -->
                                <div class="mb-4">
                                    <p class="h5 mb-5">
                                        Have you seen a doctor in the last 36 months?
                                    </p>
                                    <input type="hidden" name="que_9" value="{{ que_9 }}">
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q9_op1" name="ans_9" type="radio"
                                               value="Yes" required>
                                        <label class="form-check-label" for="q9_op1">Yes</label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q9_op2" name="ans_9" type="radio"
                                               value="No" required>
                                        <label class="form-check-label" for="q9_op2">No</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 10 -->
                            <div class="tab">
                                <div class="mb-4">
                                    <p class="h5 mb-5">
                                        Was your blood pressure taken in the past year?
                                    </p>
                                    <input type="hidden" name="que_10__1" value="{{ que_10__1 }}">
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q10_1_op1" name="ans_10__1" type="radio"
                                               value="Yes" required>
                                        <label class="form-check-label" for="q10_1_op1">Yes</label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q10_1_op2" name="ans_10__1" type="radio"
                                               value="No, I'll get a new blood pressure measurement" required>
                                        <label class="form-check-label" for="q10_1_op2">No</label>
                                    </div>
                                </div>

                                <!-- Question 10 part 2 -->
                                <div id="div_que_10__2" class="mb-5" style="display: none">
                                    <p class="mb-2 t-small t-color-red">
                                        What's the top number (Systolic Pressure)?
                                    </p>
                                    <input type="hidden" name="que_10__2" value="{{ que_10__2 }}">
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q10_2_op1" name="ans_10__2"
                                               type="radio"
                                               value="Less than 95 (LOW)">
                                        <label class="form-check-label" for="q10_2_op1">Less than 95 (LOW)</label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q10_2_op2" name="ans_10__2"
                                               type="radio"
                                               value="95-119 (NORMAL)">
                                        <label class="form-check-label" for="q10_2_op2">95-119 (NORMAL)</label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q10_2_op3" name="ans_10__2"
                                               type="radio"
                                               value="120-139 (ELEVATED)">
                                        <label class="form-check-label" for="q10_2_op3">120-139 (ELEVATED)</label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q10_2_op4" name="ans_10__2"
                                               type="radio"
                                               value="140-159 (HIGH S1)">
                                        <label class="form-check-label" for="q10_2_op4">140-159 (HIGH S1)</label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q10_2_op5" name="ans_10__2"
                                               type="radio"
                                               value="Over 160 (HIGH S2)">
                                        <label class="form-check-label" for="q10_2_op5">Over 160 (HIGH S2)</label>
                                    </div>
                                </div>

                                <!-- Question 10 part 3 -->
                                <div id="div_que_10__3" class="mb-5" style="display: none">
                                    <p class="mb-2 t-small t-color-red">
                                        What's the bottom number (Diastolic Pressure)?
                                    </p>
                                    <input type="hidden" name="que_10__3" value="{{ que_10__3 }}">
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q10_3_op1" name="ans_10__3"
                                               type="radio" value="Less than 65 (LOW)">
                                        <label class="form-check-label" for="q10_3_op1">Less than 65 (LOW)</label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q10_3_op2" name="ans_10__3"
                                               type="radio" value="65-80 (NORMAL)">
                                        <label class="form-check-label" for="q10_3_op2">65-80 (NORMAL)</label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q10_3_op3" name="ans_10__3"
                                               type="radio" value="80-90 (ELEVATED)">
                                        <label class="form-check-label" for="q10_3_op3">80-90 (ELEVATED)</label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q10_3_op4" name="ans_10__3"
                                               type="radio" value="90-100 (HIGH S1)">
                                        <label class="form-check-label" for="q10_3_op4">90-100 (HIGH S1)</label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q10_3_op5" name="ans_10__3"
                                               type="radio" value="Over 100 (HIGH S2)">
                                        <label class="form-check-label" for="q10_3_op5">Over 100 (HIGH S2)</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 11 -->
                            <div class="tab">
                                <div class="mb-4">
                                    <p class="h5 mb-5">
                                        Are you allergic to any medication(s)?
                                    </p>
                                    <input type="hidden" name="que_11__1" value="{{ que_11__1 }}">
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q11_op1" name="ans_11__1" type="radio"
                                               value="Yes" required>
                                        <label class="form-check-label" for="q11_op1">Yes</label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q11_op2" name="ans_11__1" type="radio"
                                               value="No" required>
                                        <label class="form-check-label" for="q11_op2">No</label>
                                    </div>

                                    <div id="div_que_11__2" class="mt-5" style="display: none">
                                        <p class="mb-2 t-small t-color-red">
                                            Please list any medications for which you have an allergy
                                        </p>
                                        <input type="hidden" name="que_11__2" value="{{ que_11__2 }}">
                                        <textarea class="input-primary" id="ans_11__2" name="ans_11__2"
                                                  rows="3"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 12 -->
                            <div class="tab">
                                <!-- Question 12 -->
                                <div class="mb-5">
                                    <p class="h5 mb-5">
                                        Do you have any prior medical conditions or serious problems?
                                    </p>
                                    <input type="hidden" name="que_12__1" value="{{ que_12__1 }}">
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q12_op1" name="ans_12__1" type="radio"
                                               value="Yes" required>
                                        <label class="form-check-label" for="q12_op1">Yes</label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q12_op2" name="ans_12__1" type="radio"
                                               value="No" required>
                                        <label class="form-check-label" for="q12_op2">No</label>
                                    </div>

                                    <div id="div_que_12__2" class="mt-5" style="display: none">
                                        <p class="mb-2 t-small t-color-red">Please list all medical conditions or problems from which you suffer or have suffered</p>
                                        <input type="hidden" name="que_12__2" value="{{ que_12__2 }}">
                                        <textarea id="ans_12__2" class="input-primary"
                                                  name="ans_12__2" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="tab">
                                <!-- Question 13 -->
                                <div class="mb-5">
                                    <p class="h5 mb-5">
                                        Are you taking any prescription or nonprescription medications?
                                    </p>
                                    <input type="hidden" name="que_13__1" value="{{ que_13__1 }}">
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q13_op1" name="ans_13__1" type="radio"
                                               value="Yes" required>
                                        <label class="form-check-label" for="q13_op1">Yes</label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q13_op2" name="ans_13__1" type="radio"
                                               value="No" required>
                                        <label class="form-check-label" for="q13_op2">No</label>
                                    </div>

                                    <div id="div_que_13__2" class="mt-5" style="display: none">
                                        <p class="mb-2 t-small t-color-red">
                                            Please provide a full list of all current medications including any vitamins,
                                            herbs, supplements, or over-the-counter products such as pain relievers and
                                            sleep aids both prescription and nonprescription.
                                        </p>
                                        <input type="hidden" name="que_13__2" value="{{ que_13__2 }}">
                                        <textarea id="ans_13__2" class="input-primary"
                                                  rows="3" name="ans_13__2"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 10 -->
                            <div class="tab">
                                <!-- Question 14 -->
                                <div class="mb-5">
                                    <p class="h5 mb-5">
                                        It can be life-threatening to take ED medications in conjunction with certain
                                        other medications. Please be accurate. Do you currently use or have ever used
                                        any of the following? Select all that apply.
                                    </p>
                                    <input type="hidden" name="que_14__1" value="{{ que_14__1 }}">
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q14_op1" name="ans_14__1[]" type="checkbox"
                                               value="Nitroglycerin in any form including spray, ointment, patches, or tablets (Common names include: Nitro-Dur, Nitrolingual, Nitrostat, Nitromist, Nitrolingual, Nitro-Bid, Transderm- Nitro, Nitro-Time, Deponit, Minitran, Nitrek, Nitrodisc, Nitrogard, Nitroglyn, Nitrol ointment, Nitrong, Nitro-Par)."
                                               required>
                                        <label class="form-check-label" for="q14_op1">
                                            Nitroglycerin in any form including spray, ointment, patches, or tablets
                                            (Common names include: Nitro-Dur, Nitrolingual, Nitrostat, Nitromist,
                                            Nitrolingual, Nitro-Bid, Transderm- Nitro, Nitro-Time, Deponit, Minitran,
                                            Nitrek, Nitrodisc, Nitrogard, Nitroglyn, Nitrol ointment, Nitrong,
                                            Nitro-Par).
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q14_op2" name="ans_14__1[]" type="checkbox"
                                               value="Isosorbide mononitrate or isosorbide dinitrate (Common names include: Isordil, Dilatrate, Sorbitrate, Imdur, Ismo, Monoket)"
                                               required>
                                        <label class="form-check-label" for="q14_op2">
                                            Isosorbide mononitrate or isosorbide dinitrate (Common names include:
                                            Isordil, Dilatrate, Sorbitrate, Imdur, Ismo, Monoket)
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q14_op3" name="ans_14__1[]" type="checkbox"
                                               value="I've never used any of the above medications"
                                               required>
                                        <label class="form-check-label" for="q14_op3">
                                            I've never used any of the above medications
                                        </label>
                                    </div>
                                </div>

                                <!-- Question 14 part 2 -->
                                <div id="div_que_14__2" class="mb-5" style="display: none">
                                    <p class="mb-2 t-small t-color-red">
                                        It can be dangerous to take ED medications in conjunction with certain other
                                        medications. Please be accurate. Do you currently take any of the following
                                        medications? Select all that apply.
                                    </p>
                                    <input type="hidden" name="que_14__2" value="{{ que_14__2 }}">
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q14_2_op1" name="ans_14__2[]"
                                               type="checkbox"
                                               value="Any medication called an alpha blocker (Common names include: Carder (doxazosin), Coreg (carvedilol), Flomax (tamsulosin), Hytrin (terazosin), Minipress (prazosin) Rapaflo (silodosin) Regitine or Traverse (phentolamine), Trandate (labetalol), Uraxatral (alfuzosin))"
                                        >
                                        <label class="form-check-label" for="q14_2_op1">
                                            Any medication called an alpha blocker (Common names include: Carder
                                            (doxazosin), Coreg (carvedilol), Flomax (tamsulosin), Hytrin (terazosin),
                                            Minipress (prazosin) Rapaflo (silodosin) Regitine or Traverse
                                            (phentolamine), Trandate (labetalol), Uraxatral (alfuzosin))
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q14_2_op2" name="ans_14__2[]"
                                               type="checkbox"
                                               value="Sildenafil (Revatio) because I have been diagnosed /I have pulmonary hypertension"
                                        >
                                        <label class="form-check-label" for="q14_2_op2">
                                            Sildenafil (Revatio) because I have been diagnosed /I have pulmonary
                                            hypertension
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q14_2_op3" name="ans_14__2[]"
                                               type="checkbox"
                                               value="Riociguat (Adempas) because I have been diagnosed /I have pulmonary hypertension"
                                        >
                                        <label class="form-check-label" for="q14_2_op3">
                                            Riociguat (Adempas) because I have been diagnosed /I have pulmonary
                                            hypertension
                                        </label>
                                    </div>
                                </div>

                                <!-- Question 14 part 3 -->
                                <div id="div_que_14__3" class="mb-5" style="display: none">
                                    <p class="mb-2 t-small t-color-red">
                                        Taking this medication(s) in conjunction with ED medications may require special
                                        consideration and the doctor will need more information when reviewing your
                                        profile. Please let us know if you are currently taking this medication(s), how
                                        often you take it, or when you stopped taking the medication(s) and why?
                                    </p>
                                    <input type="hidden" name="que_14__3" value="{{ que_14__3 }}">
                                    <textarea id="ans_14__3" class="input-primary" rows="3" name="ans_14__3"></textarea>
                                </div>
                            </div>

                            <!-- Tab 11 -->
                            <div class="tab">
                                <!-- Question 15 -->
                                <div class="mb-5">
                                    <p class="h5 mb-5">
                                        Have you ever been treated for ed? This includes medications either prescribed
                                        or purchased over the counter as well as any other treatments. Select all that
                                        apply.
                                    </p>
                                    <input type="hidden" name="que_15__1" value="{{ que_15__1 }}">
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q15_op1" name="ans_15__1[]" type="checkbox"
                                               value="Viagra (sildenafil)"
                                               required>
                                        <label class="form-check-label" for="q15_op1">
                                            Viagra (sildenafil)
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q15_op2" name="ans_15__1[]" type="checkbox"
                                               value="Cialis (tadalafil)"
                                               required>
                                        <label class="form-check-label" for="q15_op2">
                                            Cialis (tadalafil)
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q15_op3" name="ans_15__1[]" type="checkbox"
                                               value="Levitra (vardenafil)"
                                               required>
                                        <label class="form-check-label" for="q15_op3">
                                            Levitra (vardenafil)
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q15_op4" name="ans_15__1[]" type="checkbox"
                                               value="Other (pumps, penile injections, penile implants, supplements)"
                                               required>
                                        <label class="form-check-label" for="q15_op4">
                                            Other (pumps, penile injections, penile implants, supplements)
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q15_op5" name="ans_15__1[]" type="checkbox"
                                               value="I've never been treated for ED."
                                               required>
                                        <label class="form-check-label" for="q15_op5">
                                            I've never been treated for ED.
                                        </label>
                                    </div>
                                </div>

                                <!-- Question 15 part 2 -->
                                <div id="div_que_15__2" class="mb-5" style="display: none">
                                    <p class="mb-2 t-small t-color-red">
                                        Did you experience any side effects from your previous ED medicines that would
                                        stop you from using them again? If yes, please list all side effects (example:
                                        chest pain, lightheadedness, dizziness, blurry vision, etc.)
                                    </p>
                                    <input type="hidden" name="que_15__2" value="{{ que_15__2 }}">
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q15_2_op1" name="ans_15__2" type="radio"
                                               value="Yes, I had side effects"
                                        >
                                        <label class="form-check-label" for="q15_2_op1">
                                            Yes, I had side effects
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q15_2_op2" name="ans_15__2" type="radio"
                                               value="No, I did not have any side effects"
                                        >
                                        <label class="form-check-label" for="q15_2_op2">
                                            No, I did not have any side effects
                                        </label>
                                    </div>
                                </div>

                                <!-- Question 15 part 3 -->
                                <div id="div_que_15__3" class="mb-5" style="display: none">
                                    <p class="mb-2 t-small t-color-red">
                                        Please list the your previous ED medicines that bothered you
                                    </p>
                                    <input type="hidden" name="que_15__3" value="{{ que_15__3 }}">
                                    <textarea id="ans_15__3" class="input-primary" rows="3" name="ans_15__3"></textarea>
                                </div>
                            </div>

                            <!-- Tab 12 -->
                            <div class="tab">
                                <!-- Question 16 -->
                                <div class="mb-5">
                                    <p class="h5 mb-5">
                                        Do you get erections when asleep or first thing in the morning?
                                    </p>
                                    <input type="hidden" name="que_16" value="{{ que_16 }}">
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q16_op1" name="ans_16" type="radio"
                                               value="Yes" required>
                                        <label class="form-check-label" for="q16_op1">Yes</label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q16_op2" name="ans_16" type="radio"
                                               value="Sometimes" required>
                                        <label class="form-check-label" for="q16_op2">Sometimes</label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q16_op3" name="ans_16" type="radio"
                                               value="Hardly ever" required>
                                        <label class="form-check-label" for="q16_op3">Hardly ever</label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q16_op4" name="ans_16" type="radio"
                                               value="No, never" required>
                                        <label class="form-check-label" for="q16_op4">No, never</label>
                                    </div>
                                </div>
                            </div>

                            <div class="tab">
                                <!-- Question 17 -->
                                <div class="mb-5">
                                    <p class="h5 mb-5">
                                        Do you ever have a problem getting or maintaining an erection that is satisfying
                                        enough for sex?
                                    </p>
                                    <input type="hidden" name="que_17" value="{{ que_17 }}">
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q17_op1" name="ans_17" type="radio"
                                               value="Yes, every time"
                                               required>
                                        <label class="form-check-label" for="q17_op1">
                                            Yes, every time
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q17_op2" name="ans_17" type="radio"
                                               value="Yes, more than half the time"
                                               required>
                                        <label class="form-check-label" for="q17_op2">
                                            Yes, more than half the time
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q17_op3" name="ans_17" type="radio"
                                               value="Yes, on occasion"
                                               required>
                                        <label class="form-check-label" for="q17_op3">
                                            Yes, on occasion
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q17_op4" name="ans_17" type="radio"
                                               value="Yes, but rarely"
                                               required>
                                        <label class="form-check-label" for="q17_op4">
                                            Yes, but rarely
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q17_op5" name="ans_17" type="radio"
                                               value="I NEVER have a problem getting or maintaining an erection for as long as I want"
                                               required>
                                        <label class="form-check-label" for="q17_op5">
                                            I NEVER have a problem getting or maintaining an erection for as long as I
                                            want
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 13 -->
                            <div class="tab">
                                <!-- Question 18 -->
                                <div class="mb-5">
                                    <p class="h5 mb-5">
                                        When masturbating, does your erection remain hard until orgasm or last as long
                                        as you would like?
                                    </p>
                                    <input type="hidden" name="que_18" value="{{ que_18 }}">
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q18_op1" name="ans_18" type="radio"
                                               value="No, it does not remain hard" required>
                                        <label class="form-check-label" for="q18_op1">
                                            No, it does not remain hard
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q18_op2" name="ans_18" type="radio"
                                               value="Yes, it almost always remains hard" required>
                                        <label class="form-check-label" for="q18_op2">
                                            Yes, it almost always remains hard
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q18_op3" name="ans_18" type="radio"
                                               value="Yes, but only rarely does it remain hard" required>
                                        <label class="form-check-label" for="q18_op3">
                                            Yes, but only rarely does it remain hard
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q18_op4" name="ans_18" type="radio"
                                               value="Not sure" required>
                                        <label class="form-check-label" for="q18_op4">Not sure</label>
                                    </div>
                                </div>
                            </div>

                            <div class="tab">
                                <!-- Question 19 -->
                                <div class="mb-5">
                                    <p class="h5 mb-5">
                                        Do you have premature ejaculation? Premature ejaculation is when you ejaculate
                                        within a short time of having sex.
                                    </p>
                                    <input type="hidden" name="que_19" value="{{ que_19 }}">
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q19_op1" name="ans_19" type="radio"
                                               value="Yes, I have premature ejaculation"
                                               required>
                                        <label class="form-check-label" for="q19_op1">
                                            Yes, I have premature ejaculation
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q19_op2" name="ans_19" type="radio"
                                               value="No" required>
                                        <label class="form-check-label" for="q19_op2">No</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 14 -->
                            <div class="tab">
                                <!-- Question 20 -->
                                <div class="mb-5">
                                    <p class="h5 mb-5">
                                        How did your ED begin? Select the one that best describes how your ED began.
                                    </p>
                                    <input type="hidden" name="que_20" value="{{ que_20 }}">
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q20_op1" name="ans_20" type="radio"
                                               value="Suddenly with a new partner" required>
                                        <label class="form-check-label" for="q20_op1">
                                            Suddenly with a new partner
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q20_op2" name="ans_20" type="radio"
                                               value="Suddenly but not with a new partner" required>
                                        <label class="form-check-label" for="q20_op2">
                                            Suddenly but not with a new partner
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q20_op3" name="ans_20" type="radio"
                                               value="Gradually but has worsened over time" required>
                                        <label class="form-check-label" for="q20_op3">
                                            Gradually but has worsened over time
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q20_op4" name="ans_20" type="radio"
                                               value="I do not recall how it began" required>
                                        <label class="form-check-label" for="q20_op4">
                                            I do not recall how it began
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="tab">
                                <!-- Question 21 -->
                                <div class="mb-5">
                                    <p class="h5 mb-5">
                                        Sometimes ED can be the result of our lifestyle habits. Do you have any of the
                                        following lifestyle habits? Select all that apply.
                                    </p>
                                    <input type="hidden" name="que_21" value="{{ que_21 }}">
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q21_op1" name="ans_21[]" type="checkbox"
                                               value="I do not get as much exercise as I should"
                                               required>
                                        <label class="form-check-label" for="q21_op1">
                                            I do not get as much exercise as I should
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q21_op2" name="ans_21[]" type="checkbox"
                                               value="I do not eat as healthy as I should" required>
                                        <label class="form-check-label" for="q21_op2">
                                            I do not eat as healthy as I should
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q21_op3" name="ans_21[]" type="checkbox"
                                               value="I smoke tobacco products" required>
                                        <label class="form-check-label" for="q21_op3">
                                            I smoke tobacco products
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q21_op4" name="ans_21[]" type="checkbox"
                                               value="I drink more than I should (greater than 2 drinks/day)" required>
                                        <label class="form-check-label" for="q21_op4">
                                            I drink more than I should (greater than 2 drinks/day)
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q21_op5" name="ans_21[]" type="checkbox"
                                               value="I do not sleep as much as I should" required>
                                        <label class="form-check-label" for="q21_op5">
                                            I do not sleep as much as I should
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q21_op6" name="ans_21[]" type="checkbox"
                                               value="I'm 20+ lbs overweight" required>
                                        <label class="form-check-label" for="q21_op6">
                                            I'm 20+ lbs overweight
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q21_op7" name="ans_21[]" type="checkbox"
                                               value="None of the above lifestyle habits describe me." required>
                                        <label class="form-check-label" for="q21_op7">
                                            None of the above lifestyle habits describe me.
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 15 -->
                            <div class="tab">
                                <!-- Question 22 -->
                                <div class="mb-5">
                                    <p class="h5 mb-5">
                                        Have you, or are you currently using any of the following recreational drugs?
                                        Select all that apply.
                                    </p>
                                    <input type="hidden" name="que_22" value="{{ que_22 }}">
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q22_op1" name="ans_22[]" type="checkbox"
                                               value="Amyl Nitrate or Butyl Nitrate"
                                               required>
                                        <label class="form-check-label" for="q22_op1">
                                            Amyl Nitrate or Butyl Nitrate
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q22_op2" name="ans_22[]" type="checkbox"
                                               value="Cocaine" required>
                                        <label class="form-check-label" for="q22_op2">
                                            Cocaine
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q22_op3" name="ans_22[]" type="checkbox"
                                               value="Molly (MDMA, ecstasy)" required>
                                        <label class="form-check-label" for="q22_op3">
                                            Molly (MDMA, ecstasy)
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q22_op4" name="ans_22[]" type="checkbox"
                                               value="Other" required>
                                        <label class="form-check-label" for="q22_op4">
                                            Other
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q22_op5" name="ans_22[]" type="checkbox"
                                               value="I don't take any recreational drugs." required>
                                        <label class="form-check-label" for="q22_op5">
                                            I don't take any recreational drugs.
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="tab">
                                <!-- Question 23 -->
                                <div class="mb-5">
                                    <p class="h5 mb-5">
                                        Over the past 2 weeks, how often have you had symptoms of depression (feeling
                                        down, little interest in doing things) or anxiety (feeling nervous or worrying
                                        too much about different things)?
                                    </p>
                                    <input type="hidden" name="que_23" value="{{ que_23 }}">
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q23_op1" name="ans_23" type="radio"
                                               value="Not at all" required>
                                        <label class="form-check-label" for="q23_op1">
                                            Not at all
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q23_op2" name="ans_23" type="radio"
                                               value="Several Days" required>
                                        <label class="form-check-label" for="q23_op2">
                                            Several Days
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q23_op3" name="ans_23" type="radio"
                                               value="More than half of the days" required>
                                        <label class="form-check-label" for="q23_op3">
                                            More than half of the days
                                        </label>
                                    </div>
                                    <div class="form-check text-start mb-3">
                                        <input class="form-check-input" id="q23_op4" name="ans_23" type="radio"
                                               value="Nearly every day" required>
                                        <label class="form-check-label" for="q23_op4">
                                            Nearly every day
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 16 -->
                            <div class="tab">
                                <!-- Question 24 -->
                                <div class="mb-5">
                                    <p class="h5 mb-5">
                                        Is there anything else You would like the doctor to know? (OPTIONAL)
                                    </p>
                                    <input type="hidden" name="que_24" value="{{ que_24 }}">
                                    <textarea class="input-primary" id="ans_24" name="ans_24" rows="3"></textarea>

                                </div>
                                <div class="form-check text-start mb-3 ">
                                    <input class="form-check-input" id="q25_op1" name="ans_25" type="checkbox"
                                           value="I certify that I have provided accurate information and that all important medical information has been submitted to the best of my knowledge." required>
                                    <label class="form-check-label agreement" for="q25_op1">
                                        I certify that I have provided accurate information and that all important medical information has been submitted to the best of my knowledge.
                                    </label>
                                </div>
                            </div>

                        </div>
                        <!-- Question Tabs End -->

                        <div id="div-btn-wrapper" class="d-flex align-items-center justify-content-around gap-3 gap-md-0 mt-5">
                            <button type="button" class="btn btn-peaks-outline mb-4" id="prevBtn" onclick="nextPrev(-1)">
                                Previous
                            </button>
                            <button type="button" class="btn btn-peaks mb-4" id="nextBtn" onclick="nextPrev(1)">
                                Next
                            </button>
                            <button type="button" class="btn btn-peaks mb-4" id="submitBtn" style="display: none;">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <div id="snackbar" class="snackbar">Fill this field to continue.</div>

@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <script src="{{ asset('mask/src/jquery.mask.js') }}"></script>
    <script>

        @if (session('mismatchError'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('mismatchError') }}',
                showConfirmButton: true,
            });
        @endif

        $('.birth_date').mask('00/00/0000');
        // $(".birth_date").datepicker({
        //     startDate: new Date('1900-01-01'),
        //     endDate: new Date(),
        //     autoclose: true,
        //     todayHighlight: true
        // }).on('changeDate', function(ev) {

        // });
        $(".birth_date").on('change',function() {
            if(verifyDate($('.birth_date').val()) == false){
                $('.birth_date').val('');
            }
        });
        $(function () {
            $('input[name="ans_3__1[]"]').on('click', function () {
                if ($(this).val() !== "I've never experienced any of the above symptoms.") {
                    let value = "I've never experienced any of the above symptoms.";
                    $(':checkbox[value="' + value + '"]').prop("checked", false);
                    $('#div_que_3__2').show();
                    $('#ans_3__2').attr('required', '');
                    if ($('input[name="ans_3__1[]"]:checked').length === 0) {
                        $('#div_que_3__2').hide();
                        $('#ans_3__2').removeAttr('required');
                        $('#ans_3__2').val('');
                    }
                } else {
                    $('input[name="ans_3__1[]"]').prop('checked', false);
                    $(this).prop('checked', true);
                    $('#div_que_3__2').hide();
                    $('#ans_3__2').removeAttr('required');
                    $('#ans_3__2').val('');
                }
            });
            $('.ans_5').on('click', function () {
                if($(this).attr('id')=='q5_op5'){
                    $('.ans_5').prop("checked", false);
                    $('#q5_op5').prop("checked", true);
                }else{
                    $('#q5_op5').prop("checked", false);
                }
            });
            $('input[name="ans_8[]"]').on('click', function () {
                if($(this).attr('id')=='q8_op6'){
                    $('input[name="ans_8[]"]').prop("checked", false);
                    $('#q8_op6').prop("checked", true);
                }else{
                    $('#q8_op6').prop("checked", false);
                }
            });
             $('input[name="ans_21[]"]').on('click', function () {
                if($(this).attr('id')=='q21_op7'){
                    $('input[name="ans_21[]"]').prop("checked", false);
                    $('#q21_op7').prop("checked", true);
                }else{
                    $('#q21_op7').prop("checked", false);
                }
            });
            $('input[name="ans_22[]"]').on('click', function () {
                if($(this).attr('id')=='q22_op5'){
                    $('input[name="ans_22[]"]').prop("checked", false);
                    $('#q22_op5').prop("checked", true);
                }else{
                    $('#q22_op5').prop("checked", false);
                }
            });
            $('input[name="ans_4__1[]"]').on('click', function () {
                if ($(this).val() !== "No, I don't have any of the above conditions.") {
                    let value = "No, I don't have any of the above conditions.";
                    $(':checkbox[value="' + value + '"]').prop("checked", false);
                    $('#div_que_4__2').show();
                    $('#ans_4__2').attr('required', '');
                    if ($('input[name="ans_4__1[]"]:checked').length === 0) {
                        $('#div_que_4__2').hide();
                        $('#ans_4__2').removeAttr('required');
                        $('#ans_4__2').val('');
                    }
                } else {
                    $('input[name="ans_4__1[]"]').prop('checked', false);
                    $(this).prop('checked', true);
                    $('#div_que_4__2').hide();
                    $('#ans_4__2').removeAttr('required');
                    $('#ans_4__2').val('');
                }
            });

            $('input[name="ans_6__1[]"]').on('click', function () {
                if ($(this).val() !== "None Apply") {
                    let value = "None Apply";
                    $(':checkbox[value="' + value + '"]').prop("checked", false);
                    $('#div_que_6__2').show();
                    $('#ans_6__2').attr('required', '');
                    if ($('input[name="ans_6__1[]"]:checked').length === 0) {
                        $('#div_que_6__2').hide();
                        $('#ans_6__2').removeAttr('required');
                        $('#ans_6__2').val('');
                    }
                } else {
                    $('input[name="ans_6__1[]"]').prop('checked', false);
                    $(this).prop('checked', true);
                    $('#div_que_6__2').hide();
                    $('#ans_6__2').removeAttr('required');
                    $('#ans_6__2').val('');
                }
            });

            $('input[name="ans_7__1[]"]').on('click', function () {
                if ($(this).val() !== "No, I've never had any of these medical conditions.") {
                    let value = "No, I've never had any of these medical conditions.";
                    $(':checkbox[value="' + value + '"]').prop("checked", false);
                    $('#div_que_7__2').show();
                    $('#ans_7__2').attr('required', '');
                    if ($('input[name="ans_7__1[]"]:checked').length === 0) {
                        $('#div_que_7__2').hide();
                        $('#ans_7__2').removeAttr('required');
                        $('#ans_7__2').val('');
                    }
                } else {
                    $('input[name="ans_7__1[]"]').prop('checked', false);
                    $(this).prop('checked', true);
                    $('#div_que_7__2').hide();
                    $('#ans_7__2').removeAttr('required');
                    $('#ans_7__2').val('');
                }
            });

            let nextPrevButtons = `
                <button type="button" class="btn btn-peaks-outline mb-4" id="prevBtn" onclick="nextPrev(-1)">
                    Previous
                </button>
                <button type="button" class="btn btn-peaks mb-4" id="nextBtn" onclick="nextPrev(1)">
                    Next
                </button>
                <button type="button" class="btn btn-peaks mb-4" id="submitBtn" style="display: none;">
                    Submit
                </button>
            `;

            const divBtnWrapper = $('#div-btn-wrapper');

            $('input[name="ans_10__1"]').on('click', function () {
                if ($(this).val() === "Yes") {
                    $('#div_que_10__2').show();
                    $('#div_que_10__3').show();
                    $('#ans_10__2').attr('required', '');
                    $('#ans_10__3').attr('required', '');

                    if (document.getElementById('nextBtn') == null) {
                        divBtnWrapper.html(nextPrevButtons);
                        initSubmitButton();
                    }

                } else {
                    $('#div_que_10__2').hide();
                    $('#div_que_10__3').hide();
                    $('#ans_10__2').removeAttr('required');
                    $('#ans_10__3').removeAttr('required');
                    $('#ans_10__2').val('');
                    $('#ans_10__3').val('');

                    let bloodWarningText = 'Your Blood Pressure is needed in order to prescribe any PD5 inhibitors. Please visit your primary doctor, local drug store or supermarket with a Blood Pressure Monitor or purchase a Blood Pressure Monitor unit to test at home.';
                    let bloodWarningElement = `
                        <div class="card p-3">
                            <p class="t-color-red text-center">${bloodWarningText}</p>
                            <a href="{{ route('account-home') }}" class="t-link text-center">Go to Dashboard</a>
                        </div>
                        `;

                    divBtnWrapper.html(bloodWarningElement);
                }
            });

            $('input[name="ans_11__1"]').on('click', function () {
                if ($(this).val() !== "No") {
                    $('#div_que_11__2').show();
                    $('#ans_11__2').attr('required', '');
                } else {
                    $('#div_que_11__2').hide();
                    $('#ans_11__2').removeAttr('required');
                    $('#ans_11__2').val('');
                }
            });

            $('input[name="ans_12__1"]').on('click', function () {
                if ($(this).val() !== "No") {
                    $('#div_que_12__2').show();
                    $('#ans_12__2').attr('required', '');
                } else {
                    $('#div_que_12__2').hide();
                    $('#ans_12__2').removeAttr('required');
                    $('#ans_12__2').val('');
                }
            });

            $('input[name="ans_13__1"]').on('click', function () {
                if ($(this).val() !== "No") {
                    $('#div_que_13__2').show();
                    $('#ans_13__2').attr('required', '');
                } else {
                    $('#div_que_13__2').hide();
                    $('#ans_13__2').removeAttr('required');
                    $('#ans_13__2').val('');
                }
            });

            $('input[name="ans_14__1[]"]').on('click', function () {
                if ($(this).val() !== "I've never used any of the above medications") {
                    let value = "I've never used any of the above medications";
                    $(':checkbox[value="' + value + '"]').prop("checked", false);
                    $('#div_que_14__2').show();
                    $('#q14_2_op1').attr('required', '');
                    $('#q14_2_op2').attr('required', '');
                    $('#q14_2_op3').attr('required', '');
                    $('#q14_2_op4').attr('required', '');
                    if ($('input[name="ans_14__1[]"]:checked').length === 0) {
                        $('input[name="ans_14__2[]"]').prop('checked', false);
                        $('#div_que_14__2').hide();
                        $('#div_que_14__3').hide();
                        $('#ans_14__3').removeAttr('required');
                        $('#ans_14__3').val('');
                    }
                } else {
                    $('input[name="ans_14__1[]"]').prop('checked', false);
                    $('input[name="ans_14__2[]"]').prop('checked', false);
                    $(this).prop('checked', true);
                    $('#div_que_14__2').hide();
                    $('#q14_2_op1').removeAttr('required');
                    $('#q14_2_op2').removeAttr('required');
                    $('#q14_2_op3').removeAttr('required');
                    $('#q14_2_op4').removeAttr('required');
                    $('#div_que_14__3').hide();
                    $('#ans_14__3').removeAttr('required');
                    $('#ans_14__3').val('');
                }
            });
            $('input[name="ans_14__2[]"]').on('click', function () {
                $('#div_que_14__3').show();
                $('#ans_14__3').attr('required', '');
                if ($('input[name="ans_14__2[]"]:checked').length === 0) {
                    $('#div_que_14__3').hide();
                    $('#ans_14__3').removeAttr('required');
                    $('#ans_14__3').val('');
                }
            });

            $('input[name="ans_15__1[]"]').on('click', function () {
                if ($(this).val() !== "I've never been treated for ED.") {
                    let value = "I've never been treated for ED.";
                    $(':checkbox[value="' + value + '"]').prop("checked", false);
                    $('#div_que_15__2').show();
                    $('#q15_2_op1').attr('required', '');
                    $('#q15_2_op2').attr('required', '');
                    $('#q15_2_op3').attr('required', '');
                    if ($('input[name="ans_15__1[]"]:checked').length === 0) {
                        $('input[name="ans_15__2"]').prop('checked', false);
                        $('#div_que_15__2').hide();
                        $('#div_que_15__3').hide();
                        $('#ans_15__3').removeAttr('required');
                        $('#ans_15__3').val('');
                    }
                } else {
                    $('input[name="ans_15__1[]"]').prop('checked', false);
                    $('input[name="ans_15__2"]').prop('checked', false);
                    $(this).prop('checked', true);
                    $('#div_que_15__2').hide();
                    $('#q15_2_op1').removeAttr('required');
                    $('#q15_2_op2').removeAttr('required');
                    $('#q15_2_op3').removeAttr('required');
                    $('#div_que_15__3').hide();
                    $('#ans_15__3').removeAttr('required');
                    $('#ans_15__3').val('');
                }
            });
            $('input[name="ans_15__2"]').on('click', function () {
                if ($(this).val() === "Yes, I had side effects") {
                    $('#div_que_15__3').show();
                    $('#ans_15__3').attr('required', '');
                } else {
                    $('#div_que_15__3').hide();
                    $('#ans_15__3').removeAttr('required');
                    $('#ans_15__3').val('');
                }
            });
        });

        const questionnaireIntro = document.getElementById('questionnaire-intro');
        const questionnaireForm = document.getElementById('questionnaire-form');

        $('#btn-lets-go').click(function () {
            questionnaireIntro.style.display = 'none';
            questionnaireForm.style.display = 'block';
            $("#close_click").click();
        });

        let currentTab = 0;

        showTab(currentTab);

        function showTab(n) {
            const x = document.getElementsByClassName("tab");
            x[n].style.display = "block";
            if (n === 0) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }
            if (n === (x.length - 1)) {
                $('#nextBtn').hide();
                $('#submitBtn').show();
                // document.getElementById("nextBtn").innerHTML = "Submit";
            } else {
                $('#submitBtn').hide();
                $('#nextBtn').show();
                // document.getElementById("nextBtn").innerHTML = "Next";
            }
        }

        function initSubmitButton() {
            $('#submitBtn').on('click', function () {
                if (!$('#q25_op1').is(":checked")) {
                    $('.text-error').remove();
                    $('<div class="mb-2 t-color-red t-small text-start text-error">Please check this box if you want to proceed.</div>').insertAfter($('.agreement'));
                    return false;
                }
                $('.text-error').remove();
                $('.loaderElement').show();
                questionnaireForm.submit();
            });
        }

        initSubmitButton();

        function nextPrev(n) {
            const x = document.getElementsByClassName("tab");
            if (n === 1 && !validateForm())
                return false;
            x[currentTab].style.display = "none";
            currentTab = currentTab + n;
            if (currentTab >= x.length) {
                // $('.loaderElement').show();
                // questionnaireForm.submit();
                return false;
            }
            showTab(currentTab);
        }

        function validateForm() {
            let tabs, inputItem, textareaItem, valid = true;
            tabs = document.getElementsByClassName("tab");
            inputItem = tabs[currentTab].getElementsByTagName("input");
            textareaItem = tabs[currentTab].getElementsByTagName("textarea");
            for (let i = 0; i < inputItem.length; i++) {
                if (inputItem[i].getAttribute("type") === "date" || inputItem[i].getAttribute("type") === "text") {

                    if (inputItem[i].value == "" && inputItem[i].getAttribute("required") !== null) {
                        if (!inputItem[i].classList.contains('invalid')) {
                            inputItem[i].className += " invalid";
                            $('<div class="mb-2 t-color-red t-small text-start text-error">Date of birth is required</div>').insertAfter(inputItem[i]);
                        }
                        valid = false;
                    }else if(inputItem[i].value == ""){
                        if (!inputItem[i].classList.contains('invalid')) {
                            inputItem[i].className += " invalid";
                            $('<div class="mb-2 t-color-red t-small text-start text-error">Date of birth is required</div>').insertAfter(inputItem[i]);
                        }
                        valid = false;
                    }else if(verifyDate(inputItem[i].value) == false){
                        if (!inputItem[i].classList.contains('invalid')) {
                            inputItem[i].className += " invalid";
                            $('<div class="mb-2 t-color-red t-small text-start text-error">Date of birth is required</div>').insertAfter(inputItem[i]);
                        }
                        valid = false;
                    } else {
                        inputItem[i].classList.remove('invalid');
                        $('.text-error').remove();
                    }
                } else if (inputItem[i].getAttribute("type") === "checkbox") {
                    let boxes = $('input[name="' + inputItem[i].getAttribute("name") + '"]:checked');
                    let boxesVisible = $('input[name="' + inputItem[i].getAttribute("name") + '"]').is(':visible');
                    if (inputItem[i].getAttribute("required") !== null && boxes.length === 0) {
                        valid = false;
                        // showSnackbar();
                        showToast('error', 'Please select an answer to proceed.');
                    }else if(boxes.length === 0 && boxesVisible){
                        valid = false;
                        showToast('error', 'Please select an answer to proceed.');
                    }
                } else if (inputItem[i].getAttribute("type") === "radio") {
                    let radio_boxes = $('input[name="' + inputItem[i].getAttribute("name") + '"]:checked');
                    let radioBoxesVisible = $('input[name="' + inputItem[i].getAttribute("name") + '"]').is(':visible');
                    if (inputItem[i].getAttribute("required") !== null && radio_boxes.length === 0) {
                        valid = false;
                        // showSnackbar();
                        showToast('error', 'Please select an answer to proceed.');
                    }else if(radio_boxes.length === 0 && radioBoxesVisible){
                        valid = false;
                        showToast('error', 'Please select an answer to proceed.');
                    }
                }
            }
            for (let j = 0; j < textareaItem.length; j++) {
                if (textareaItem[j].value === "" && textareaItem[j].getAttribute("required") !== null) {
                    if (!textareaItem[j].classList.contains('invalid')) {
                        textareaItem[j].className += " invalid";
                        $('<div class="mb-2 t-color-red t-small text-start text-error">This field is required</div>').insertAfter(textareaItem[j]);
                    }
                    valid = false;
                }else if(textareaItem[j].value === "" && $(textareaItem[j]).parent().is(":visible")){
                    if (!textareaItem[j].classList.contains('invalid')) {
                        textareaItem[j].className += " invalid";
                        $('<div class="mb-2 t-color-red t-small text-start text-error">This field is required</div>').insertAfter(textareaItem[j]);
                    }
                    valid = false;
                }
                else {
                    textareaItem[j].classList.remove('invalid');
                    $('.text-error').remove();
                }
            }
            return valid;
        }

        function showSnackbar() {
            let x = document.getElementById("snackbar");
            x.className = "show";
            setTimeout(function () {
                x.className = x.className.replace("show", "");
            }, 3000);
        }
    </script>
@endsection
