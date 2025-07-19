# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

GoalLensFC is a Laravel 12 web application for football/soccer fans that integrates with API Football to display league information, team data, and statistics. The app includes user authentication, favorite team management, and real-time football data.

## Development Commands

### Starting the Development Environment
```bash
# Start all development services (server, queue, logs, vite)
composer dev

# Alternative: Start individual services
php artisan serve          # Laravel development server
npm run dev                # Vite development server for assets
php artisan queue:listen    # Queue worker
php artisan pail            # Real-time log viewer
```

### Testing
```bash
# Run all tests
composer test
# Or alternatively:
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

### Asset Building
```bash
npm run build    # Build production assets
npm run dev      # Development build with hot reload
```

### Database Operations
```bash
php artisan migrate              # Run migrations
php artisan migrate:fresh --seed # Fresh database with seeders
php artisan db:seed             # Run seeders only
```

### API Football Data Management
```bash
php artisan fetch:leagues    # Fetch league data from API Football
php artisan fetch:teams      # Fetch team data from API Football  
php artisan download:logos   # Download logos for teams and leagues
```

## Architecture Overview

### Key Models and Relationships
- **User**: Authentication with Laravel Breeze, has many favorite teams
- **Team**: Football teams with logos, belongs to many leagues and users (favorites)
- **League**: Football leagues with logos, has many teams and favorite team relationships
- **FavoriteTeamUser**: Pivot model connecting users to their favorite teams with league context

### Core Services
- **ApiFootballService** (`app/Services/ApiFootballService.php`): Handles all API Football integration for team statistics and match data
- Uses RapidAPI with configurable credentials via environment variables

### Frontend Stack
- **Laravel Breeze**: Authentication scaffolding
- **Tailwind CSS**: Utility-first CSS framework
- **Alpine.js**: Lightweight JavaScript framework
- **Vite**: Asset bundling and hot reload

### Database
- SQLite database (`database/database.sqlite`) for development
- Migrations handle teams, leagues, users, and favorite relationships
- Logo storage in `public/logos/` with separate subdirectories for teams and leagues

### Route Structure
- `/` - Main match dashboard (public)
- `/favorites` - User's favorite teams management (authenticated)
- `/results` - Results for user's favorite teams (authenticated)
- `/leagues` - League data endpoint
- `/teams` - Team data endpoint
- `/dashboard` - User dashboard (authenticated)
- `/league-games` - League games view (authenticated)

### Logo Management
- Team logos: `public/logos/teams/{team_id}.png`
- League logos: `public/logos/leagues/{league_id}.png`
- Logo URLs are dynamically generated in model accessors
- Current implementation uses hardcoded localhost URLs (needs environment-aware configuration)

## Configuration Notes

### API Football Setup
Required environment variables:
- `FOOTBALL_API_KEY`: RapidAPI key for API Football
- `FOOTBALL_API_HOST`: API host (usually api-football-v1.p.rapidapi.com)
- `FOOTBALL_API_URL`: API base URL

### Development Database
The app uses SQLite for development with the database file at `database/database.sqlite`. The database is automatically created during setup.

## Testing Strategy
- Feature tests cover authentication flows and main application features
- Unit tests for individual components
- Test database uses in-memory SQLite for speed
- Laravel's built-in testing tools with PHPUnit

## Common Development Patterns
- Controllers follow Laravel conventions with resource-style naming
- Blade templates in `resources/views/` with component-based structure
- Models use Eloquent relationships and accessors for computed properties
- API responses cached and handled through service layer
- Authentication middleware protects user-specific routes