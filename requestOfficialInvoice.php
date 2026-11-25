<?php
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function requestOfficialInvoice_config() {
    return [
        "name" => "requestOfficialInvoice",
        "description" => "ماژولی برای ثبت درخواست صدور فاکتور رسمی و ایجاد تیکت پشتیبانی",
        "version" => "1.0",
        "author" => "AfaghHosting",
        "language" => "farsi",
        "fields" => [
            "department_id" => [
                "FriendlyName" => "ID دپارتمان پشتیبانی",
                "Type" => "text",
                "Size" => "5",
                "Description" => "شماره ID دپارتمانی که تیکت‌ها در آن ایجاد می‌شوند",
                "Default" => "1",
            ],
        ],
    ];
}

function requestOfficialInvoice_activate() {
    // ایجاد جدول درخواست‌ها
    $query = "CREATE TABLE IF NOT EXISTS `mod_request_official_invoice` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `invoiceid` int(11) NOT NULL,
        `userid` int(11) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    try {
        \WHMCS\Database\Capsule::statement($query);
    } catch (\Exception $e) {
        return ["status" => "error", "description" => $e->getMessage()];
    }

    return ["status" => "success", "description" => "ماژول فعال شد و جدول ایجاد شد"];
}

function requestOfficialInvoice_deactivate() {
    return ["status" => "success", "description" => "ماژول غیرفعال شد"];
}

function requestOfficialInvoice_upgrade($vars) {
    // در صورت نیاز به آپگرید جدول یا فیلدها
}
