<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = array(
          array('name' => 'admin.customers.list','module_name' => 'Customer Management','title' => 'Can view the customers list table','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:29:45'),
          array('name' => 'admin.customers.view','module_name' => 'Customer Management','title' => 'Can view the customers','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:29:02'),
          array('name' => 'admin.customers.data','module_name' => 'Customer Management','title' => 'Can view the customers details','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:30:08'),
          array('name' => 'admin.customers.change.password','module_name' => 'Customer Management','title' => "Can change the customers account\'s password",'permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:30:51'),
          array('name' => 'admin.daily.orders','module_name' => 'Order Management','title' => 'Can view the list of daily orders','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-21 06:27:24'),
          array('name' => 'admin.pending.orders','module_name' => 'Order Management','title' => 'Can view Pending orders','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-21 06:28:47'),
          array('name' => 'admin.prescribed.orders','module_name' => 'Order Management','title' => 'Can view the list of prescribed orders','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-21 06:28:00'),
          array('name' => 'admin.view.orders','module_name' => 'Order Management','title' => 'Can view the order details  - 1','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-21 06:29:40'),
          array('name' => 'admin.orders.data','module_name' => 'Order Management','title' => 'Can view the order details  - 2','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-21 06:29:52'),
          array('name' => 'admin.order.refill.data','module_name' => 'Order Refill Management','title' => 'Can view the refill details','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-21 06:20:28'),
          array('name' => 'admin.order.refill.tracking.data','module_name' => 'Order Shipping Management','title' => 'Can view and update the refill tracking number - 2','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:44:25'),
          array('name' => 'admin.order.refill.tracking.data.post','module_name' => 'Order Shipping Management','title' => 'Can view and update the refill tracking number - 1','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:43:41'),
          array('name' => 'order-invoice','module_name' => 'Invoice Management','title' => 'Can view the invoices - 1','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-21 06:19:39'),
          array('name' => 'subscribed.order.invoice','module_name' => 'Invoice Management','title' => 'Can view the invoices - 2','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-21 06:19:13'),
          array('name' => 'admin.declined.orders','module_name' => 'Order Management','title' => 'Can view declined orders','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-21 06:25:44'),
          array('name' => 'admin.declined.orders-refund','module_name' => 'Manage Refund','title' => 'Can refund the amount of declined orders (if auto refund failed)','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-21 06:22:25'),
          array('name' => 'admin.cancelled.orders','module_name' => 'Order Management','title' => 'Can view the list of Cancelled orders','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-21 06:21:37'),
          array('name' => 'admin.expired.orders','module_name' => 'Order Management','title' => 'Can view the expired orders','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-21 06:21:12'),
          array('name' => 'admin.refund.history','module_name' => 'Manage Refund','title' => 'Can view the Refund history - 1','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:04:29'),
          array('name' => 'admin.peaks.errors','module_name' => 'General','title' => 'Can view the Peaks Error Codes menu','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:03:55'),
          array('name' => 'admin.setting.view','module_name' => 'General','title' => 'Can view General Settings','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:15:07'),
          array('name' => 'general-setting.post','module_name' => 'General','title' => 'Can update the General Settings','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:28:10'),
          array('name' => 'admin.subscriptions.active','module_name' => 'Subscription Management','title' => 'Can view the active subscriptions','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 08:58:35'),
          array('name' => 'admin.subscriptions.paused','module_name' => 'Subscription Management','title' => 'Can view the paused subscriptions','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 08:58:01'),
          array('name' => 'admin.subscriptions.expired','module_name' => 'Subscription Management','title' => 'Can view the expired subscriptions','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 08:57:41'),
          array('name' => 'admin.refund.order','module_name' => 'Manage Refund','title' => 'Can refund the order amount','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:00:29'),
          array('name' => 'admin.dash.order.sales','module_name' => 'Order Management','title' => 'Can view the order sales data on the dashboard','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:24:34'),
          array('name' => 'admin.promo.code','module_name' => 'Promo Code Management','title' => 'Can view the list of promo codes','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:36:28'),
          array('name' => 'admin.get.promo.code-update-form','module_name' => 'Promo Code Management','title' => 'Can view the update promo code form','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:40:13'),
          array('name' => 'admin.post.promo.code-update-form','module_name' => 'Promo Code Management','title' => 'Can update the Promo code - 2','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:41:50'),
          
          array('name' => 'admin.promo.code.post','module_name' => 'Promo Code Management','title' => 'Can update the promo code - 1','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:42:01'),
          array('name' => 'admin.delete.promo.code.post','module_name' => 'Promo Code Management','title' => 'Can delete promo code','permission_for' => '3','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:41:00'),
          array('name' => 'admin.request.for.refill','module_name' => 'Order Refill Management','title' => 'Can generate the refill request','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:41:00'),
          array('name' => 'admin.subscription.resume','module_name' => 'Subscription Management','title' => 'Can resume the subscription','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:41:00'),
          array('name' => 'admin.subscription.pause','module_name' => 'Subscription Management','title' => 'Can pause the subscription','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:41:00'),
          array('name' => 'admin.unship.orders','module_name' => 'Order Shipping Management','title' => 'Can view the list of unshipped orders','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:41:00'),
          array('name' => 'admin.update.refill.ship.detail','module_name' => 'Order Shipping Management','title' => 'Can update the shipping status','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2022-12-20 09:41:00'),
          array('name' => 'admin.force.payment.for.refill','module_name' => 'Order/Refill Payment Management','title' => 'Can force payment of failed refill','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin-pending-order-cancel','module_name' => 'Order Management','title' => 'Can cancel pending orders of customers','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.failed.refill.transaction.details','module_name' => 'Order/Refill Payment Management','title' => 'Can view the failed Refill transactions','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.subscriptions.cancelled','module_name' => 'Subscription Management','title' => 'Can view the Cancelled Subscriptions which are cancelled by Admin - 1','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.subscription.cancel.by.admin','module_name' => 'Subscription Management','title' => 'Can cancel the subscription ','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.view.cancelled.subscription.details','module_name' => 'Subscription Management','title' => 'Can view the Cancelled Subscriptions which are cancelled by Admin - 2','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.view.refund.history.details','module_name' => 'Manage Refund','title' => 'Can view the Refund history - 2','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.products.list','module_name' => 'Plan Management','title' => 'Can view the list of Products','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.products.edit','module_name' => 'Plan Management','title' => 'Can edit the Products - 1','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.products.store','module_name' => 'Plan Management','title' => 'Can edit the Products - 2','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.plan.type.list','module_name' => 'Plan Management','title' => 'Can view the plan type list','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.plan.type.edit','module_name' => 'Plan Management','title' => 'Can edit the plan type - 1','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.plan.type.store','module_name' => 'Plan Management','title' => 'Can edit the plan type - 2','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.medicine.list','module_name' => 'Plan Management','title' => 'Can view the Medicine variants list','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.medicine.variants.edit','module_name' => 'Plan Management','title' => 'Can edit the Medicine variant - 1','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.medicine.variants.store','module_name' => 'Plan Management','title' => 'Can edit the Medicine variant - 2','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.plan.list','module_name' => 'Plan Management','title' => 'Can view the plans list','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.plan.edit','module_name' => 'Plan Management','title' => 'Can edit the plans -1 ','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.plan.store','module_name' => 'Plan Management','title' => 'Can edit the plans -2','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.delete.plan','module_name' => 'Plan Management','title' => 'Can delete the plan','permission_for' => '3','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'send.to.beluga','module_name' => 'Order Management','title' => 'Can send order to Beluga forcefully (if failed)','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.beluga.pending.orders','module_name' => 'Order Management','title' => 'Can view the list of orders which are pending for Beluga','permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.beluga.setting','module_name' => 'General','title' => "Can view the Beluga's consultation fee",'permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.update.consultation.fee','module_name' => 'General','title' => "Can change the Beluga's consultation fee",'permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.update.liberty.script.number','module_name' => 'Order Management','title' => 'Can update Liberty script number','permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.customers.account.status.update','module_name' => 'Customer Management','title' => "Can update the user's account status (Enable/Disable)",'permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.customers.medical.question.list','module_name' => 'Order Management','title' => "Can view medical questions and answers",'permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.beluga.cancellation.pending.orders','module_name' => 'Order Management','title' => "Can view the list of Cancelled orders which are pending for confimration on Beluga",'permission_for' => '1','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),
          array('name' => 'admin.mark.as.cancelled.order','module_name' => 'Order Management','title' => "Can mark the order status cancelled if Beluga sends Rx event on cancelled order",'permission_for' => '2','guard_name' => 'web','created_at' => NULL,'updated_at' => '2023-01-12 09:41:00'),

        );
    
        Schema::disableForeignKeyConstraints();
        Permission::truncate();
        Schema::enableForeignKeyConstraints();

        foreach ($permissions as $permission) {
            DB::table('permissions')->insert($permission);
        }
    }
}
