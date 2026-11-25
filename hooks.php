<?php
use WHMCS\Database\Capsule;

if (!defined('WHMCS')) die('This file cannot be accessed directly');

// نمایش دکمه سبز و پیام
add_hook('ClientAreaPageViewInvoice', 1, function ($vars) {

    $invoiceId = $vars['invoiceid'];
    $userId = $_SESSION['uid'] ?? 0;

    if (strtolower($vars['status']) !== 'paid') return;
    if (!Capsule::schema()->hasTable('mod_request_official_invoice')) return;

    $hasRequested = Capsule::table('mod_request_official_invoice')
        ->where('invoiceid', $invoiceId)
        ->where('userid', $userId)
        ->exists();

    $buttonHTML = $hasRequested
        ? '<div class="alert alert-info" style="margin-top:10px">درخواست شما ثبت شده است.</div>'
        : '<form method="post" style="margin-top:10px">
               <input type="hidden" name="request_official_invoice" value="1">
               <button type="submit" class="btn btn-success">درخواست فاکتور رسمی</button>
           </form>';

    echo <<<SCRIPT
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const header = document.querySelector('.invoice-title');
        if (header) {
            header.insertAdjacentHTML('afterend', `$buttonHTML`);
        }
    });
    </script>
    SCRIPT;
});

// ثبت درخواست و ایجاد تیکت با API داخلی
add_hook('ClientAreaPage', 1, function ($vars) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_official_invoice']) && isset($vars['invoiceid'])) {

        $invoiceId = (int)$vars['invoiceid'];
        $userId = $_SESSION['uid'] ?? 0;
        if (!$userId) return;
        if (!Capsule::schema()->hasTable('mod_request_official_invoice')) return;

        $exists = Capsule::table('mod_request_official_invoice')
            ->where('invoiceid', $invoiceId)
            ->where('userid', $userId)
            ->exists();

        if (!$exists) {
            Capsule::table('mod_request_official_invoice')->insert([
                'invoiceid' => $invoiceId,
                'userid' => $userId,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // خواندن ID دپارتمان از تنظیمات ماژول
            $deptIdRow = Capsule::table('tbladdonmodules')
                ->where('module', 'requestOfficialInvoice')
                ->where('setting', 'department_id')
                ->first();
            $deptId = $deptIdRow ? (int)$deptIdRow->value : 1;

            // ایجاد تیکت با localAPI
            $command = 'OpenTicket';
            $postData = [
                'deptid' => $deptId,
                'clientid' => $userId,
                'subject' => "درخواست فاکتور رسمی {$invoiceId}",
                'message' => "سلام. لطفا نسبت به صدور فاکتور رسمی برای #{$invoiceId} اقدام فرمایید",
                'priority' => 'Medium',
            ];
            $adminUsername = ''; // نام کاربری ادمین یا خالی
            try {
                localAPI($command, $postData, $adminUsername);
            } catch (Exception $e) {
                // در صورت نیاز می‌توان لاگ خطا اضافه کرد
            }
        }
    }
});
