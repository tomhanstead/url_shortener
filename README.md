# URL Mapping Service

This Laravel application provides a service for encoding and decoding URLs. The main functionality includes generating short keys for long URLs and mapping them to their original counterparts using RESTful APIs and robust architecture.


---


## Features

- **URL Encoding** : Converts a long URL into a short, unique key.

- **URL Decoding** : Retrieves the original URL from a short key.

- **Error Handling** : Handles exceptions gracefully and returns proper HTTP status codes.

- **RESTful API** : Built with REST principles for scalability and maintainability.

- **Validation** : Ensures the validity of URLs using request validation.


---


## Table of Contents

- [Installation](#installation)

- [Getting Started](#getting-started)

- [Backend Design and Architecture](#backend-design-and-architecture)
    - [SOLID Principles](#solid-principles)
    - [Dependency Injection](#dependency-injection)
    - [Service Layer](#service-layer)
    - [PSR-12](#psr-12)
    - [Rate Limiting](#rate-limiting)

- [Code Structure](#code-structure)
    - [Controllers](#controllers)
    - [Services](#services)
    - [Resources](#resources)
    - [Requests](#requests)
    - [Models](#models)

- [API Endpoints](#api-endpoints)
    - [Encode URL](#encode-url)
    - [Decode URL](#decode-url)

- [Testing](#testing)

- [Technologies Used](#technologies-used)


---


## Installation

1. **Clone the Repository:**

```bash
git clone https://github.com/tomhanstead/url_shortener.git
cd url_shortener
```

2. **Install Dependencies:**

```bash
composer install
```

3. **Set Up Environment Variables:**

```bash
cp .env.example .env
```
Update the `.env` file with your database credentials. Example for SQLite:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

4. **Generate Application Key:**

```bash
php artisan key:generate
```

5. **Run Migrations:**

```bash
php artisan migrate
```

6. **Start the Development Server:**

```bash
php artisan serve
```


---


## Getting Started

### Backend Design and Architecture

#### SOLID Principles

The application adheres to SOLID principles:

- **Single Responsibility Principle** : Each class focuses on a specific functionality (e.g., `UrlMappingService` handles mapping logic).

- **Open/Closed Principle** : Services and handlers are extendable without modifying existing code.

- **Dependency Inversion Principle** : Classes like `UrlMappingController` rely on abstractions instead of concrete implementations.

#### Dependency Injection
`UrlMappingServiceContract` is injected into the `UrlMappingController` to decouple service logic and enable testability.
#### Service Layer

Encapsulates business logic for URL encoding and decoding, ensuring a clean separation of concerns.

#### PSR-12
This assignment follows the PSR-12 coding style, ensuring consistent code styling. I used Laravel Pint to check and fix code styles.
```bash
  ./vendor/bin/pint // you can also use --fix to automatically fix any issues.
```
### Rate Limiting
To safeguard the server from excessive API requests and ensure fair usage, rate limiting has been implemented. This restricts each user or IP address to 60 requests per minute.


---


## Code Structure

### Controllers

- **`UrlMappingController`** :
    - Handles incoming HTTP requests for encoding and decoding URLs.

    - Interacts with the service layer for business logic.

    - Returns consistent, structured JSON responses.

### Services

- **`UrlMappingService`** :
    - Implements `UrlMappingServiceContract`.

    - Contains the core logic for mapping URLs and generating short keys.

### Resources

- **`UrlMappingResource`** :
    - Transforms `UrlMapping` models into a consistent API response structure.

### Requests

- **`UrlEncodeDecodeRequest`** :
    - Validates incoming requests for encoding and decoding.

### Models

- **`UrlMapping`** :
    - Represents the database table for storing URLs and their short keys.


---


## API Endpoints

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


---


## Testing

Run the test suite:


```bash
php artisan test
```


---


## Technologies Used

- **Framework** : Laravel 10

- **Language** : PHP 8.3+

- **Database** : SQLite

- **Testing** : PHPUnit

- **Caching** : Database


---
