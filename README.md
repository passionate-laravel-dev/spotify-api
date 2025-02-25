# Laravel Spotify REST API

This project is built to showcase my skills in Laravel by implementing a Spotify REST API.
There might still be areas for improvement! :)

### Packages

-   `laravel/framework`: 11.44.0
-   `laravel/sanctum`: 4.0 (for API authentication)
-   `dedoc/scramble`: 0.12.10 (for API documentation)

### Installation Instructions

1. Clone the repository:

    ```bash
    git clone git@github.com:passionate-laravel-dev/spotify-api.git
    cd spotify-api
    ```

2. Install dependencies:

    ```bash
    composer install

    ```

3. Generate the Laravel key:

    ```bash
    php artisan key:generate

    ```

4. Configure the database access in the `.env` file.

5. Generate the database and seed it

    ```bash
    php artisan migrate --seed

    ```

6. Run the project

    ```bash
    php artisan serve

    ```

### Endpoints

**Authentication:**
For all API calls (except signin and signup), include a Bearer token in the header for authentication.

Example header:

```
Authorization: Bearer {your_token_here}
```

**POST** `api/auth/signin` - User sign-in
Test user info

```json
{
    "email": "test@example.com",
    "password": "123456"
}
```

**POST** `api/auth/signup` - User sign-up

**GET** `api/spotify/search-items` - Search items from Spotify (e.g., _artist_, _album_, _track_, ...)

Example

```json
{
    "type": "artist",
    "query": "Drake"
}
```

**GET** `api/spotify/artists` - Get artist

**GET** `api/spotify/artists/several` - Get several artists

**GET** `api/spotify/artists/{id}/albums` - Get albums for a specific artist

### To Visit Documentation

1. Open your browser.

2. Go to: http://localhost:8000/docs/api

**Note:**

> I used Laravel Sail for Docker setup, and I can share with you a Postman workspace that I used to test the endpoints.
