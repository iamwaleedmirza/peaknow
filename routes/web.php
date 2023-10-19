<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;
use App\Http\Controllers\Utils\FileUploadController;

/**
 * Static Routes / Public Routes
 **/
// Route::get('/', ['App\Http\Controllers\StaticPageController', 'getHomeView'])->name('home');
// Route::get('/about-us', ['App\Http\Controllers\StaticPageController', 'getAboutUsView'])->name('about-us');
// Route::get('/plans', ['App\Http\Controllers\StaticPageController', 'getPlansView'])->name('plans');
// Route::get('/faq', ['App\Http\Controllers\StaticPageController', 'getFaqView'])->name('faq');
// Route::get('/blogs', ['App\Http\Controllers\StaticPageController', 'getBlogsView'])->name('blogs');
// Route::get('/contact-us', ['App\Http\Controllers\StaticPageController', 'contactUs'])->name('contact-us');
// Route::post('/contact-us', ['App\Http\Controllers\StaticPageController', 'submitContactUs'])->name('contact-us-post')->middleware(ProtectAgainstSpam::class);
// Route::get('/save-popup-cookie', ['App\Http\Controllers\StaticPageController', 'saveCookie'])->name('save.popup-cookie');
// Route::get('/save-cookie-agreed', ['App\Http\Controllers\StaticPageController', 'saveCookieAgreed'])->name('save.cookie-agreed');
// Route::post('/home-subscription', ['App\Http\Controllers\StaticPageController', 'homeEmailSubscription'])->name('home.email.subscription')->middleware(ProtectAgainstSpam::class);
// Route::get('/terms-conditions', ['App\Http\Controllers\StaticPageController', 'termsConditions'])->name('terms-conditions');
// Route::get('/telehealth-consent', ['App\Http\Controllers\StaticPageController', 'telehealthConsent'])->name('telehealth-consent');
// Route::get('/privacy-policy', ['App\Http\Controllers\StaticPageController', 'privacyPolicy'])->name('privacy-policy');
// Route::get('/refund-policy', ['App\Http\Controllers\StaticPageController', 'refundPolicy'])->name('refund-policy');
// Route::get('/cookie-policy', ['App\Http\Controllers\StaticPageController', 'cookiePolicy'])->name('cookie-policy');

Route::get('/', ['App\Http\Controllers\Auth\LoginController', 'showLoginForm'])->name('home');

Route::get('/state-not-live', ['App\Http\Controllers\StaticPageController', 'stateNotLive'])->name('state-not-live');
Route::get('/continue-with-state', ['App\Http\Controllers\StaticPageController', 'continueWithState'])->name('continue-state-not-live');

//Route::get('/emailTemplate', ['App\Http\Controllers\StaticPageController', 'getEmailView']);


/**
 * Webhook Routes
 **/

//Route::post('/webhook/call', ['App\Http\Controllers\Utils\WebhookController', 'handle'])->name('webhook.get');


/**
 * User Routes
 **/
Route::group(['middleware' => ['XssSanitizer'], 'prefix' => 'user'], function () {
    Route::get('/login', ['App\Http\Controllers\Auth\LoginController', 'showLoginForm'])->name('login.user');
    Route::post('/login', ['App\Http\Controllers\Auth\UserLoginController', 'login'])->name('user.login');

    Route::get('/register', ['App\Http\Controllers\Auth\RegisterController', 'getRegistrationView'])->name('register.user');
    Route::post('/register', ['App\Http\Controllers\User\RegistrationController', 'registerFirstStep'])->name('user.register');
    Route::get('/registration/successful', ['App\Http\Controllers\User\RegistrationController', 'registrationSuccessful'])->name('user.register_successful')->middleware('auth');
    Route::post('/registration/successful', ['App\Http\Controllers\User\RegistrationController', 'postRegistrationSuccessful'])->name('user.register_successful.post')->middleware('auth');
    Route::get('/register/details/', ['App\Http\Controllers\User\RegistrationController', 'getRegistrationDetailsView'])->name('register-details');
    Route::post('/register-second-step', ['App\Http\Controllers\User\RegistrationController', 'registerSecondStep'])->name('user.register.second-step');
    //Route::post('/register', ['App\Http\Controllers\User\RegistrationController', 'registerAjaxStep'])->name('user.register');
    Route::post('/verifyOtp', ['App\Http\Controllers\User\RegistrationController', 'VerifyOtpByType'])->name('user.verifyOtpByType');
    // Social Logins
    // Route::get('/login/{provider}/redirect', ['App\Http\Controllers\Auth\SocialLoginController', 'redirectToProvider'])->name('social-redirect');
    // Route::get('/login/{provider}/callback', ['App\Http\Controllers\Auth\SocialLoginController', 'providerCallback'])->name('social-callback');

    // Route::get('/register/confirmation', ['App\Http\Controllers\Auth\SocialLoginController', 'confirmSocialInfo'])->name('social-confirmation');
    // Route::post('/register/social/user', ['App\Http\Controllers\Auth\SocialLoginController', 'registerSocialLogin'])->name('register-social-user');
    Route::get('/password/reset', ['App\Http\Controllers\StaticPageController', 'getPasswordResetView'])->name('password-reset');
    // Route::post('/social/email', ['App\Http\Controllers\Auth\SocialLoginController', 'SetUserEmail'])->name('set.social.email');
});

Route::get('/iframecommunicator', function () {
    return view('payment.IFrameCommunicator');
})->name('authorize.iframe');

// Route::get('/select-plan', ['App\Http\Controllers\StaticPageController', 'getPlans'])
//     ->name('get.plans')
//     ->middleware(['auth', 'user', 'XssSanitizer', 'UserStateAllowed']);

// Route::get('subscribe/plan/{id}', ['App\Http\Controllers\User\PlansController', 'selectPlan'])->name('select-plan');
Route::post('unsubscribe/plan', ['App\Http\Controllers\User\PaymentController', 'unsubscribePlan'])->name('unsubscribe.plan');

Route::post('cancel/plan', ['App\Http\Controllers\User\OrderController', 'requestOrderCancellation'])->name('cancel.plan');

// New Plan
Route::get('wp-plan/{id}/{qty}', ['App\Http\Controllers\User\PlansController', 'checkPlan']);
Route::get('wp-change-plan/{id}/{qty}', ['App\Http\Controllers\User\PlansController', 'ChangePlan']);

// Route::get('subscribe/plan/{id}', ['App\Http\Controllers\User\PlansController', 'selectPlan'])->name('select-plan');

Auth::routes();
Route::group(['middleware' => ['auth', 'user', 'XssSanitizer', 'UserStateAllowed'], 'prefix' => 'user'], function () {
    Route::get('/change/password', ['App\Http\Controllers\Auth\ChangePasswordController', 'getView'])->name('user.change-password');
    Route::post('/change/password', ['App\Http\Controllers\Auth\ChangePasswordController', 'changePassword'])->name('user.change-password.post');

    Route::get('/otp-verification', ['App\Http\Controllers\StaticPageController', 'getOtpVerifyView'])->name('otp-verify');
    Route::get('/email-verification', ['App\Http\Controllers\StaticPageController', 'getemailVerifyView'])->name('email-verify');
    Route::get('/resend-email-verification', ['App\Http\Controllers\User\RegistrationController', 'reSentEmailVerify'])->name('email-resend-verify');
    Route::post('/resend-email--ajax', ['App\Http\Controllers\User\RegistrationController', 'reSentEmailVerifyAjax'])->name('email-resend-verify-ajax');
    Route::post('/otp-verification', ['App\Http\Controllers\User\RegistrationController', 'verifyOtp'])->name('otp-verify.check');
    Route::get('/continue-otp-verification', ['App\Http\Controllers\User\RegistrationController', 'continueOtpVerification'])->name('continue-otp-verification');
    Route::get('/resend-otp', ['App\Http\Controllers\User\RegistrationController', 'resendOtp'])->name('resend.otp');
    Route::post('/resend-otp-ajax', ['App\Http\Controllers\User\RegistrationController', 'resendOtpAjax'])->name('resend.otp.ajax');
    Route::post('/change-mobile-no', ['App\Http\Controllers\User\RegistrationController', 'changeMobileNo'])->name('change-mobile-no');
    Route::post('/change-email', ['App\Http\Controllers\User\RegistrationController', 'changeEmail'])->name('change-email');
    Route::get('/rx-user-agreement', ['App\Http\Controllers\User\RegistrationController', 'rxUserAgreement'])->name('rx-user-agreement');
    Route::post('/rx-user-agreement', ['App\Http\Controllers\User\RegistrationController', 'submitRxUserAgreement'])->name('submit-rx-user-agreement');

    Route::get('/medical/questions', ['App\Http\Controllers\User\RegistrationController', 'getQuestionnaire'])->middleware(['active-order'])->name('medical-questions');
    Route::post('/medical/questions', ['App\Http\Controllers\User\RegistrationController', 'submitAnswers'])->name('medical-answers');

    Route::get('/documents/upload', ['App\Http\Controllers\StaticPageController', 'getDocumentUploadView'])->middleware(['active-order','check-order-ques'])->name('upload-documents');
    Route::post('/checkout', ['App\Http\Controllers\User\RegistrationController', 'addToCart'])->name('add-to-cart');
    Route::get('/account/verification', ['App\Http\Controllers\HomeController', 'verifyAccountForOrder'])->name('account-verify-check');
    Route::get('/account/home', ['App\Http\Controllers\HomeController', 'accountHome'])->middleware('UserVerified')->name('account-home')->middleware('onboarding');
    Route::get('/account/info', ['App\Http\Controllers\HomeController', 'accountInfo'])->middleware('UserVerified')->name('account-info');
    Route::post('/account/info', ['App\Http\Controllers\HomeController', 'updateInfo'])->middleware('UserVerified')->name('account-info-post');
    Route::post('/account/upload/documents', ['App\Http\Controllers\HomeController', 'uploadDocuments'])->name('upload-documents-post');
    Route::get('is-documents-uploaded', ['App\Http\Controllers\HomeController', 'isDocumentsUploaded'])->name('user.is-documents-uploaded');
    Route::get('/account/orders', ['App\Http\Controllers\User\OrderController', 'accountOrders'])->name('account-orders');
    Route::get('/order/detail/{orderId}', ['App\Http\Controllers\User\OrderController', 'orderDetails'])->name('order-details');
    Route::get('/order/{orderId}/refill/{refillNo}', ['App\Http\Controllers\User\OrderController', 'refillDetails'])->name('order.refill-details');
    // Route::get('/account/payment', ['App\Http\Controllers\HomeController', 'accountPaymentMethod'])->name('account-payment');
    Route::get('/account/addresses', ['App\Http\Controllers\HomeController', 'accountAddresses'])->middleware('UserVerified')->name('account-addresses');

    Route::get('/plan/renew/{planId}/{qty}', ['App\Http\Controllers\HomeController', 'renewPlan'])->name('plan.renew');

    Route::get('/order/summary', ['App\Http\Controllers\User\RegistrationController', 'orderSummary'])->name('order-summary');
    Route::get('/shipping/info', ['App\Http\Controllers\User\RegistrationController', 'shippingInfo'])->name('shipping-info');
    Route::post('/add/shipping-address', ['App\Http\Controllers\User\RegistrationController', 'addNewAddress'])->name('add-shipping-address');
    Route::post('/shipping-address/delete', ['App\Http\Controllers\User\RegistrationController', 'deleteAddress'])->name('delete-shipping-address');
    Route::post('/shipping-address/edit', ['App\Http\Controllers\User\RegistrationController', 'editAddress'])->name('edit-shipping-address');

    Route::post('/order/save-address', ['App\Http\Controllers\User\RegistrationController', 'completeOrder'])->name('order-address-submit');
    Route::get('/make/{order}/unpaid/payment', ['App\Http\Controllers\User\PaymentController', 'makeUnPaidPayment'])->name('make-unpaid-payment');
    Route::get('/make/payment', ['App\Http\Controllers\User\PaymentController', 'getCustomerProfileToken'])->name('make-payment');
    Route::get('/payment/failed', ['App\Http\Controllers\User\PaymentController', 'oneTimeOrderTransactionFailed'])->name('payment.failed');
    Route::get('/create-subscription', ['App\Http\Controllers\User\PaymentController', 'getCustomerProfileToken'])->name('create.subscription');

    Route::get('/payment/success', ['App\Http\Controllers\Payment\PaymentSuccessController', '__invoke'])->name('payment-success');
    Route::get('/order/{orderNo}/refill/payment/success', ['App\Http\Controllers\User\OrderController', 'refillOrderSuccessView'])->name('refill.request-success');
    Route::get('/order/{order}/payment/success', ['App\Http\Controllers\User\OrderController', 'getOrderSuccessView'])->name('order-success');

    Route::post('/cancel-order', ['App\Http\Controllers\User\OrderController', 'requestOrderCancellation'])->name('cancel.order');

    // new pause/resume routes
    Route::post('subscription/{order_no}/pause', ['App\Http\Controllers\Subscription\PauseResumeController', 'pause'])->name('subscription.pause');
    Route::post('subscription/{order_no}/resume', ['App\Http\Controllers\Subscription\PauseResumeController', 'resume'])->name('subscription.resume');

    Route::get('/plan', ['App\Http\Controllers\User\SubscriptionController', 'myPlan'])->name('user.plan.index');

    Route::get('/change-plan', ['App\Http\Controllers\StaticPageController', 'getPlansExceptCurrent'])->name('user.change.plan');

    Route::post('/update-order/shipping-address', ['App\Http\Controllers\User\ChangeShippingController', 'updateOrderShippingAddress'])->name('user.order.shipping.address');

    Route::get('/update-payment-profile', ['App\Http\Controllers\User\PaymentController', 'getPaymentChangePageToken'])->name('change.payment_method');
    Route::post('/payment-updated/log', ['App\Http\Controllers\StaticPageController', 'logPaymentUpdated'])->name('log.payment-updated');

    Route::get('/create-customerPayment-profile/{order_id}/{customer_profile_id}', ['App\Http\Controllers\User\PaymentController', 'createCustomerPaymentProfile'])->name('create-customerPayment-profile');

    Route::get('/request-for-refill', ['App\Http\Controllers\User\OrderController', 'requestForRefill'])->name('order.request-refill');

    Route::get('/email/verification/', ['App\Http\Controllers\Auth\UserLoginController', 'emailVerification'])->name('user.email.verification');
    Route::post('/email/verification/', ['App\Http\Controllers\HomeController', 'resendEmailVerification'])->name('user.email.verification.post');

    Route::post('/validate-promo-code', ['App\Http\Controllers\User\DiscountController', 'validatePromoCode'])->name('user.validate-promo-code');
    Route::post('/remove-promo-code', ['App\Http\Controllers\User\DiscountController', 'removePromo'])->name('user.remove-promo-code');
});


/**
 * Admin Routes
 **/
Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', ['App\Http\Controllers\StaticPageController', 'getAdminLoginView']);
    Route::post('/login', ['App\Http\Controllers\Auth\UserLoginController', 'adminLogin'])->name('admin.login');
});
Route::group(['middleware' => ['auth', 'admin','permission'], 'prefix' => 'admin'], function () {
    Route::get('/dashboard', ['App\Http\Controllers\Admin\AdminHomeController', 'index'])->name('admin.home');

    Route::get('/permission', ['App\Http\Controllers\Admin\PermissionController', 'index'])->name('admin.permission.list');
    Route::post('permission-store', ['App\Http\Controllers\Admin\PermissionController', 'store'])->name('admin.permission.store');
    Route::post('show-permission-detail', ['App\Http\Controllers\Admin\PermissionController', 'show'])->name('admin.permission.show');
    Route::post('remove-permission', ['App\Http\Controllers\Admin\PermissionController', 'DeletePermission'])->name('admin.permission.delete');

    Route::get('/role', ['App\Http\Controllers\Admin\RoleController', 'index'])->name('admin.role.list');
    Route::get('/role/add-role', ['App\Http\Controllers\Admin\RoleController', 'create'])->name('admin.role.add');
    Route::get('/role/edit/{id?}', ['App\Http\Controllers\Admin\RoleController', 'show'])->name('admin.role.show');
    Route::post('role-store', ['App\Http\Controllers\Admin\RoleController', 'store'])->name('admin.role.store');
    Route::post('remove-role', ['App\Http\Controllers\Admin\RoleController', 'DeleteRole'])->name('admin.role.delete');

    Route::get('/admin-user-list', ['App\Http\Controllers\Admin\AdminUserList', 'index'])->name('admin.admin-user.list');
    Route::post('add-user-store', ['App\Http\Controllers\Admin\AdminUserList', 'store'])->name('admin.admin-user.store');
    Route::post('show-user-detail', ['App\Http\Controllers\Admin\AdminUserList', 'show'])->name('admin.admin-user.show');
    Route::post('remove-user', ['App\Http\Controllers\Admin\AdminUserList', 'DeletePermission'])->name('admin.admin-user.delete');
    Route::post('admin-user-change-password', ['App\Http\Controllers\Admin\AdminUserList', 'adminChangeCustomerPassword'])->name('admin.admin-user.change.password');


    Route::get('/customers-list', ['App\Http\Controllers\Admin\UserController', 'getCustomers'])->name('admin.customers.list');
    
    Route::get('/{customer}/customers-view', ['App\Http\Controllers\Admin\UserController', 'getCustomersDetails'])->name('admin.customers.view');
    Route::get('/{customer}/customers-data', ['App\Http\Controllers\Admin\UserController', 'getCustomersData'])->name('admin.customers.data');
    Route::post('/customer/change-password', ['App\Http\Controllers\Admin\UserController', 'changeCustomerPassword'])->name('admin.customers.change.password');
    Route::post('/update-user-account-status', ['App\Http\Controllers\Admin\UserController', 'changeCustomerAccountStatus'])->name('admin.customers.account.status.update');
    Route::get('/view-medical-question/{order_no}', ['App\Http\Controllers\Admin\UserController', 'viewMedicalQuestionList'])->name('admin.customers.medical.question.list');


    Route::get('/daily-orders', ['App\Http\Controllers\Admin\OrdersController', 'dailyOrders'])->name('admin.daily.orders');
    Route::get('/pending-orders', ['App\Http\Controllers\Admin\OrdersController', 'pendingOrders'])->name('admin.pending.orders');
    Route::get('/prescribed-orders', ['App\Http\Controllers\Admin\OrdersController', 'prescribedOrders'])->name('admin.prescribed.orders');
    Route::get('/unshipped-orders', ['App\Http\Controllers\Admin\OrdersController', 'unshippedOrders'])->name('admin.unship.orders');
    Route::get('/{order}/order-detail', ['App\Http\Controllers\Admin\OrdersController', 'viewOrderDetail'])->name('admin.view.orders');
    Route::get('/{order}/order-data', ['App\Http\Controllers\Admin\OrdersController', 'viewOrderData'])->name('admin.orders.data');
    // Route::get('/{order}/order-refill-details/{refill_number}', ['App\Http\Controllers\Admin\OrdersController', 'viewOrderRefillDetail'])->name('admin.view.order.refill');
    Route::get('/{order}/order-refill-data/{refill_number}', ['App\Http\Controllers\Admin\OrdersController', 'viewOrderRefillData'])->name('admin.order.refill.data');
    Route::get('/{order}/order-refill-tracking-data/{refill_number}', ['App\Http\Controllers\Admin\OrdersController', 'viewOrderRefillTrackingData'])->name('admin.order.refill.tracking.data');
    Route::post('/order-refill-tracking-data', ['App\Http\Controllers\Admin\OrdersController', 'OrderRefillTrackingDataPost'])->name('admin.order.refill.tracking.data.post');
    Route::get('/order/invoice/{order}', ['App\Http\Controllers\Admin\OrdersController', 'generateInvoice'])->name('order-invoice');
    Route::get('/subscribed/order/invoice/{order}', ['App\Http\Controllers\Admin\OrdersController', 'generateSubscriptionInvoice'])->name('subscribed.order.invoice');
    Route::get('/declined-orders', ['App\Http\Controllers\Admin\OrdersController', 'declinedOrders'])->name('admin.declined.orders');
    // Route::post('/declined-orders', ['App\Http\Controllers\Admin\OrdersController', 'declinedOrders'])->name('admin.declined.orders-refund');
    
    Route::get('/cancelled-orders', ['App\Http\Controllers\Admin\OrdersController', 'cancelledOrders'])->name('admin.cancelled.orders');
    Route::get('/expired-orders', ['App\Http\Controllers\Admin\OrdersController', 'expiredOrders'])->name('admin.expired.orders');
    
    Route::get('/refund-history', ['App\Http\Controllers\Admin\FinanceController', 'refundHistory'])->name('admin.refund.history');
    Route::post('/view-refund-history-details', ['App\Http\Controllers\Admin\FinanceController', 'refundHistoryDetails'])->name('admin.view.refund.history.details');

    // Route::get('/refund-failed', ['App\Http\Controllers\Admin\FinanceController', 'refundFailed'])->name('admin.refund.failed');

    Route::get('/peaks-error-codes', ['App\Http\Controllers\Admin\PeaksErrorDetailController', 'getPeaksErrorDetailView'])->name('admin.peaks.errors');

    Route::get('/general-setting', ['App\Http\Controllers\Admin\SettingsController', 'settingView'])->name('admin.setting.view');
    Route::post('/general-setting', ['App\Http\Controllers\Admin\SettingsController', 'settingUpdate'])->name('general-setting.post');
    Route::get('/shipping-setting', ['App\Http\Controllers\Admin\SettingsController', 'shippingSettingView'])->name('admin.shipping.view');
    Route::post('/shipping-setting', ['App\Http\Controllers\Admin\SettingsController', 'shippingSettingUpdate'])->name('shipping-setting.post');
    Route::get('/plans-setting', ['App\Http\Controllers\Admin\SettingsController', 'plansView'])->name('admin.setting.plans');
    Route::get('/plans-setting/{plan_id}', ['App\Http\Controllers\Admin\SettingsController', 'editPlan'])->name('admin.edit.plan');
    Route::post('/plans-setting/update', ['App\Http\Controllers\Admin\SettingsController', 'planUpdate'])->name('admin.update.plan');
    Route::get('/page-content/{slug}', ['App\Http\Controllers\Admin\PagesContentController', 'pageView'])->name('pages.view');
    Route::post('/page_content/{slug}', ['App\Http\Controllers\Admin\PagesContentController', 'contentUpdate'])->name('pages-content.post');
    Route::get('/change-password', ['App\Http\Controllers\Admin\PasswordChangeController', 'passwordView'])->name('admin.change-password');
    Route::post('/change-password/submit', ['App\Http\Controllers\Admin\PasswordChangeController', 'updatePassword'])->name('admin.change-password-post');
    Route::get('/all-subscribers', ['App\Http\Controllers\Admin\SettingsController', 'allSubscribers'])->name('admin.subscribers');
    Route::post('/delete/subscribers', ['App\Http\Controllers\Admin\SubscriberController', 'deletePopUpUser'])->name('admin.delete.subscribers');
    Route::get('/subscriptions/active', ['App\Http\Controllers\Admin\SubscriptionController', 'activeSubscriptions'])->name('admin.subscriptions.active');
    Route::get('/subscriptions/paused', ['App\Http\Controllers\Admin\SubscriptionController', 'pausedSubscriptions'])->name('admin.subscriptions.paused');
    Route::get('/subscriptions/expired', ['App\Http\Controllers\Admin\SubscriptionController', 'expiredSubscriptions'])->name('admin.subscriptions.expired');
    Route::get('/subscriptions/cancelled', ['App\Http\Controllers\Admin\SubscriptionController', 'cancelledSubscriptions'])->name('admin.subscriptions.cancelled');


    Route::get('/contact-us', ['App\Http\Controllers\Admin\SettingsController', 'contactUsData'])->name('admin.contact-us-data');
    Route::post('/delete/contact', ['App\Http\Controllers\Admin\SubscriberController', 'deleteContactUsUser'])->name('admin.delete.contact');
    Route::post('/refund-order', ['App\Http\Controllers\Admin\FinanceController', 'refundOrder'])->name('admin.refund.order');
    

    Route::get('/getOrderSales/{type}', ['App\Http\Controllers\Admin\AdminHomeController', 'getOrderSales'])->name('admin.dash.order.sales');
    //Promo Routes
    Route::get('/promo-code', ['App\Http\Controllers\Admin\PromeCodeController', 'getPromoCode'])->name('admin.promo.code');
    Route::get('/{promoCode}/get-promo-code', ['App\Http\Controllers\Admin\PromeCodeController', 'getUpdatePromoCodeModal'])->name('admin.get.promo.code-update-form');
    Route::post('/add-promo-code', ['App\Http\Controllers\Admin\PromeCodeController', 'addPromoCode'])->name('admin.promo.code.post');
    Route::post('/update-promo-code', ['App\Http\Controllers\Admin\PromeCodeController', 'updatePromoCode'])->name('admin.post.promo.code-update-form');
    
    Route::post('delete-promo-code', ['App\Http\Controllers\Admin\PromeCodeController', 'deletePromoCode'])->name('admin.delete.promo.code.post');

    // Failed refill transaction module
    Route::get('/failed-refill-transactions', ['App\Http\Controllers\Admin\FailedRefillTransaction', 'FailedRefillTransactions'])->name('admin.failed.refill.transaction');
    Route::get('/view-failed-refill-details/{id}', ['App\Http\Controllers\Admin\FailedRefillTransaction', 'ViewOrderDetails'])->name('admin.failed.refill.transaction.details');

    // New Route added after perimission add
    // Request for refill
    Route::post('/admin-request-for-refill', ['App\Http\Controllers\Admin\OrdersController', 'RequestRefill'])->name('admin.request.for.refill');

    // Force payment
    Route::post('/admin-force-payment-for-refill', ['App\Http\Controllers\Admin\FailedRefillTransaction', 'ForcePaymentForRefill'])->name('admin.force.payment.for.refill');    

    // Subscription resume & pause
    Route::post('resume-subscription-admin', ['App\Http\Controllers\Admin\SubscriptionController', 'PauseSubscriptionResume'])->name('admin.subscription.resume');
    Route::post('pause-subscription-admin', ['App\Http\Controllers\Admin\SubscriptionController', 'PauseSubscription'])->name('admin.subscription.pause');

    // View contact us details for popup box
    Route::post('/view-contact-us', ['App\Http\Controllers\Admin\SettingsController', 'viewcontactUsData'])->name('admin.view.contact-us');

    // View contact us details for popup box
    Route::post('/update-refill-ship-details', ['App\Http\Controllers\Admin\OrdersController', 'UpdateShipmentDetail'])->name('admin.update.refill.ship.detail');

    // Pending order cancel on pending order list 
    Route::post('/admin-pending-order-cancel', ['App\Http\Controllers\Admin\OrdersController', 'PendingOrderCancelled'])->name('admin.pending.order.cancel');

    // Cancel Subscription By Admin
    Route::post('/cancel-subscription-by-admin', ['App\Http\Controllers\Admin\SubscriptionController', 'cancelSubscription'])->name('admin.subscription.cancel.by.admin');

    Route::post('/view-cancellled-subscription-details', ['App\Http\Controllers\Admin\SubscriptionController', 'viewcancelSubscription'])->name('admin.view.cancelled.subscription.details');

    
    Route::controller(App\Http\Controllers\Admin\ProductController::class)->group(function () {
        Route::get('products', 'index')->name('admin.products.list');
        Route::post('edit-product', 'EditProduct')->name('admin.products.edit');
        Route::post('store-product', 'store')->name('admin.products.store');
    });

    Route::controller(App\Http\Controllers\Admin\PlanTypeController::class)->group(function () {
        Route::get('plan-types', 'index')->name('admin.plan.type.list');
        Route::post('edit-plan-type', 'EditPlan')->name('admin.plan.type.edit');
        Route::post('store-plan-type', 'store')->name('admin.plan.type.store');
    });

    Route::controller(App\Http\Controllers\Admin\MedicineVarientController::class)->group(function () {
        Route::get('medicine-varients', 'index')->name('admin.medicine.list');
        Route::post('edit-medicine', 'EditMedicineVarient')->name('admin.medicine.variants.edit');
        Route::post('store-medicine', 'store')->name('admin.medicine.variants.store');
        Route::post('get-medicine', 'getMedicine')->name('admin.get.medicine.variants.list');
    });

    Route::controller(App\Http\Controllers\Admin\PlanController::class)->group(function () {
        Route::get('plan', 'index')->name('admin.plan.list');
        Route::get('plan/add', 'create')->name('admin.add.plan');
        Route::get('plan/edit/{id}', 'editPlan')->name('admin.plan.edit');
        Route::post('store-plan-data', 'store')->name('admin.plan.store');
        Route::post('delete-plan', 'DeletePlan')->name('admin.delete.plan');
    });

    Route::controller(App\Http\Controllers\Admin\SettingsController::class)->group(function () {
        Route::get('beluga-setting', 'BelugaSetting')->name('admin.beluga.setting');
        Route::post('consultation-fees', 'UpdateConsultationFee')->name('admin.update.consultation.fee');
    });

    // send to beluga
    Route::post('send-to-beluga', [App\Http\Controllers\Admin\OrdersController::class,'sendToBeluga'])->name('send.to.beluga');

    Route::get('beluga-pending-order', ['App\Http\Controllers\Admin\OrdersController', 'BelugaPendingOrders'])->name('admin.beluga.pending.orders');

    Route::post('update-liberty-script-number', [App\Http\Controllers\Admin\OrdersController::class,'UpdateLibertyScriptNumber'])->name('admin.update.liberty.script.number');

    Route::get('beluga-cancellation-pending-order', ['App\Http\Controllers\Admin\OrdersController', 'CancellationOrderPending'])->name('admin.beluga.cancellation.pending.orders');
    Route::post('admin-mark-as-cancelled-order', ['App\Http\Controllers\Admin\OrdersController', 'MarkAsCancelledOrder'])->name('admin.mark.as.cancelled.order');
});


/**
 * Other Routes
 **/
Route::get('/user/logout', ['App\Http\Controllers\Auth\UserLoginController', 'logout'])->name('user.logout');
Route::get('/admin/logout', ['App\Http\Controllers\Auth\UserLoginController', 'AdminLogout'])->name('admin.logout');

// Storage route
Route::get('storage/{folder}/{filename}', ['App\Http\Controllers\StaticPageController', 'storageRoute'])->name('images_route');