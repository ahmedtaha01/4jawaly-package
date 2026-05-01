# FourJawaly (4jawaly) Laravel package

Send SMS through [4jawaly](https://4jawaly.com/) from a Laravel application. This package wraps the JSON API using Laravel’s HTTP client and your account credentials.

## Requirements

- PHP 8.1+ (same as supported Laravel versions)
- Laravel 10, 11, or 12
- A 4jawaly account with API key, API secret, and an approved sender name

## Installation

Install with Composer (adjust the constraint to match your setup):

```bash
composer require ahmedtaha/fourjawaly-package
```

If you are developing the package locally, add a path repository in your app’s `composer.json`, then require it as usual.

## Register the service provider

The package does not rely on Composer auto-discovery yet. Register the provider manually.

**Laravel 11+** — add to `bootstrap/providers.php`:

```php
AhmedTaha\FourjawalyPackage\FourJawalyServiceProvider::class,
```

**Laravel 10** — add to the `providers` array in `config/app.php`:

```php
AhmedTaha\FourjawalyPackage\FourJawalyServiceProvider::class,
```

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=fourjawaly-config
```

Set these variables in `.env` (names match `config/fourjawaly.php`):

```env
FOURJAWALY_API_KEY=your_api_key
FOURJAWALY_API_SECRET=your_api_secret
FOURJAWALY_SENDER_NAME=your_registered_sender
```

`FOURJAWALY_API_KEY` and `FOURJAWALY_API_SECRET` are sent as HTTP Basic credentials (`base64(key:secret)`) to the 4jawaly API, consistent with their documentation.

## Usage

### Send a message (facade)

The package includes `FourJawalyFacade`, which proxies to `FourJawalyService`:

```php
use AhmedTaha\FourjawalyPackage\Facades\FourJawalyFacade;

$result = FourJawalyFacade::sendMessage(
    ['9665XXXXXXXX', '9665YYYYYYYY'],
    'Your message text here.'
);
```

`sendMessage` returns the decoded JSON array from the API on success.

On failure it throws `AhmedTaha\FourjawalyPackage\Exceptions\FourJawalyException` (non-2xx responses and transport errors are wrapped).

```php
use AhmedTaha\FourjawalyPackage\Exceptions\FourJawalyException;
use AhmedTaha\FourjawalyPackage\Facades\FourJawalyFacade;

try {
    FourJawalyFacade::sendMessage(['9665XXXXXXXX'], 'Hello');
} catch (FourJawalyException $e) {
    // Log or handle $e->getMessage()
}
```

### Send a message (dependency injection)

You can inject or resolve `FourJawalyService` instead of the facade:

```php
use AhmedTaha\FourjawalyPackage\FourJawalyService;

$fourJawaly = app(FourJawalyService::class);

$result = $fourJawaly->sendMessage(
    ['9665XXXXXXXX', '9665YYYYYYYY'],
    'Your message text here.'
);
```

### Optional: validate input with the DTO

`FourJawalyDTO` checks that numbers are non-empty strings, use the `966` country prefix, and do not start with `+`. It also ensures the message is not blank. Use it before calling the API if you want consistent validation:

```php
use AhmedTaha\FourjawalyPackage\DTO\FourJawalyDTO;
use AhmedTaha\FourjawalyPackage\Facades\FourJawalyFacade;

$dto = new FourJawalyDTO(['9665XXXXXXXX'], 'Hello from Laravel');
FourJawalyFacade::sendMessage($dto->phones, $dto->message);
```

## API endpoint

The client posts to:

`https://api-sms.4jawaly.com/api/v1/account/area/sms/send`

Payload shape is built inside `FourJawalyService` (`messages` array with `text`, `numbers`, and `sender`).

## License

Specify your license in `composer.json` or this file when you publish the package.
