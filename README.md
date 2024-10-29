# Loan Management System
A Loan Management System built with Laravel, offering APIs for user registration, authentication, and loan management. This project is Dockerized for easy setup and includes integration tests with SQLite for isolated test environments.

## Prerequisites
- **Docker**
- **Docker Compose**

## Setup Instructions
### 1. Clone the Repository
   ```bash
   git clone https://github.com/VishnuJampalaUB/loan-management-system.git
   cd loan-management-system
   ```
### 2. Configure Environment Files
- **Create `.env` file**: Copy `.env.example` to `.env`, then update values as needed (e.g., database credentials).
- **Create `.env.testing` file** for integration tests (recommended config: SQLite in-memory database).

```bash
cp .env.example .env
cp .env.example .env.testing
```

### 3. Build and Run with Docker
To build and start the application along with MySQL in Docker:

```bash
docker-compose up --build
```

### 4. Run Migrations
After containers are up, run migrations to create tables in the MySQL database:

```bash
docker-compose exec app php artisan migrate
```

### 5. Access the Application
- **App URL:** [http://localhost:8000](http://localhost:8000)

### Running Tests
Integration tests are configured to use an SQLite in-memory database.

```bash
docker-compose exec app php artisan test
```

### API Endpoints
- `POST /api/register` - Register a new user.
- `POST /api/login` - Log in a user.
- `POST /api/logout` - Log out the authenticated user.
- `GET /api/loans` - Retrieve all loans (authentication not required).
- `GET /api/loans/{loan_id}` - Retrieve a single loan by id (authentication not required).
- `POST /api/loans` - Create a new loan (authentication required).
- `PUT /api/loans` - Update an existing loan (authentication required, only lender can update).
- `DELETE /api/loans` - Delete an existing loan (authentication required, only lender can delete).

### Postman Collection

