# Blog Application with Livewire and Filament

A modern blog application built with Laravel, Livewire for the frontend, and Filament for the admin panel.

## Features

- User authentication (login/registration)
- Profile management (cover/profile photos, bio)
- Post creation with images/videos
- Like and comment functionality
- Follow system for users
- Admin panel with Filament
- Responsive design

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/your-repo.git
   cd your-repo
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install && npm run dev
   ```

3. Copy and configure the environment file:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Set up the database:
   - Configure your database settings in `.env`
   - Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

5. Install Filament and Livewire (if not already included):
   ```bash
   composer require filament/filament
   composer require livewire/livewire
   ```

6. Serve the application:
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` in your browser.

## Admin Access

- Visit `/admin` to access the Filament admin panel.
- Use seeded admin credentials or create one manually in the database.

## Contributing

Feel free to fork this repository and submit pull requests. For major changes, please open an issue first.

## License

[MIT](LICENSE)

