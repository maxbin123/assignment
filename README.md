### Getting started

- Clone the repo
- copy `env.example` to `.env`
- set the `DB_` environment variables in `.env` to your liking
- create a database with the name specified in `DB_DATABASE`
- `composer install`
- migrate and seed products form json with `php artisan migrate:fresh --seed`
- `php artisan l5-swagger:generate` to get Swagger on `/api/documentation/`

### About
As in description we need to have to track products removed from cart for sales. 
It will be two variants, if order was completed or cart was abandoned. 
In first case we have customer's info for sales, in second not, but decided to track them also for "most removed products" report.

Because of nature of problem I decided to go with Event Sourcing pattern & apply well-known [spatie/laravel-event-sourcing](https://github.com/spatie/laravel-event-sourcing) package on top of Laravel.

So whole cart became an AggregateRoot, and on when order placed the Projector stores it to DB. 
Reports are based on EventQueries.

### Testing
- `php artisan test`
