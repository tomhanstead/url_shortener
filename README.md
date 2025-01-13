# Url Mapping Service

This Laravel application provides a service for encoding and mapping URLs. The main functionality includes generating short keys for long URLs and returning appropriate responses using HTTP standards.

## Features

- **URL Encoding**: Converts a given long URL into a short key.
- **Error Handling**: Handles exceptions and returns appropriate HTTP status codes.
- **RESTful API**: Designed with REST principles for scalable and maintainable services.

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/tomhanstead/url_shortener.git
   cd url_shortener
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Set up environment variables:
   ```bash
   cp .env.example .env
   ```
   Update the `.env` file with your database credentials and ensure the following settings for SQLite:
   ```env
   DB_CONNECTION=sqlite
   ```

4. Run migrations:
   ```bash
   php artisan migrate
   ```

5. Start the development server:
   ```bash
   php artisan serve
   ```

## Endpoints

### Encode URL
**POST** `/api/encode`

Request Body:
```json
{
    "url": "https://example.com/long-url?foo=bar"
}
```

Response:
- Success (HTTP 200):
  ```json
  {
    "original_url": "https://example.com/long-url?foo=bar",
    "short_url": "https://shortern_url.test/s7zctw"
  }
  ```
- Error (HTTP 500):
  ```json
  {
    "error": "An error occurred while mapping the URL."
  }
  ```


### Decode URL
**POST** `/api/decode`

Request Body:
```json
{
    "url": "https://shortern_url.test/s7zctw?foo=bar"
}
```

Response:
- Success (HTTP 200):
  ```json
  {
    "original_url": "https://example.com/long-url?foo=bar",
    "short_url": "https://shortern_url.test/s7zctw"
  }
  ```
- Error (HTTP 500):
  ```json
  {
    "error": "An error occurred while mapping the URL."
  }
  ```

## Code Structure

- **Controller**: `UrlMappingController` handles requests and responses.
- **Service**: `UrlMappingService` contains the business logic for URL encoding.
- **Resource**: `UrlMappingResource` ensures transforming models into structured JSON responses. It also ensures consistency in API responses.
- **Request Validation**: `UrlEncodingRequest` ensures input validation.
- **Model**: `UrlMapping` represents the database table for storing URLs and their mappings.
## Unit Test
To test the api please run:
```
php artisan test
```

## Technologies Used

- **Framework**: Laravel 11
- **Language**: PHP 8.3+
- **Database**: SQLite
- **Testing**: PHPUnit
