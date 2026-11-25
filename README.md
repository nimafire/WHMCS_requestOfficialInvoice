# WHMCS Official Invoice Request

A lightweight WHMCS addon module that allows clients to request an official invoice directly from their paid invoices.

The module adds an official invoice request button to eligible invoices in the WHMCS client area. When a client submits a request, a support ticket is automatically created to notify the support team that the invoice has been paid and an official invoice needs to be issued.

## Features

- Adds an official invoice request button to paid invoices.
- Allows clients to request an official invoice directly from the invoice page.
- Automatically creates a support ticket when a request is submitted.
- Includes the related invoice information in the support ticket.
- Sends requests to a configurable support department.
- Stores request records in a dedicated database table.
- Prevents the need for clients to manually contact support.
- Uses WHMCS Capsule database functionality.
- Does not modify WHMCS core files.

## Requirements

- WHMCS 8.x
- PHP 7.4 or newer
- MySQL / MariaDB
- Administrator access to WHMCS

## Installation

1. Download or clone this repository.
2. Upload the module directory to your WHMCS installation:

```text
/modules/addons/requestOfficialInvoice/
```

3. Log in to the WHMCS Admin Area.
4. Navigate to **System Settings → Addon Modules**.
5. Find **Request Official Invoice** and click **Activate**.
6. Open the module configuration and set the support department ID.

## Configuration

The module provides the following configuration option:

| Setting | Description | Default |
|---|---|---|
| Department ID | Support department where official invoice requests are created | 1 |

Make sure the configured department exists in WHMCS and is available for ticket creation.

## How It Works

1. A client pays an invoice.
2. The official invoice request button becomes available on the invoice page.
3. The client clicks the request button.
4. The module creates a support ticket in the configured department.
5. The ticket contains information about the paid invoice and the client's request.
6. The support team processes the request and issues the official invoice.

## Database

The module automatically creates the following table during activation:

```text
mod_request_official_invoice
```

The table stores:

- Request ID
- WHMCS Invoice ID
- Client User ID
- Request creation timestamp

The table is not deleted when the module is deactivated.

## Deactivation

The module can be deactivated from the WHMCS Addon Modules page.

Deactivation does not remove the database table or previously stored request records.

## License

MIT License
