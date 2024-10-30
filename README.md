# Loan Management System
A Loan Management System built with Laravel, offering APIs for user registration, authentication, and loan management. This project is Dockerized for easy setup and includes integration tests with SQLite for isolated test environments.

## Prerequisites
- **Docker**
- **Docker Compose**

## Setup Instructions
### 1. Clone the Repository
   ```bash
   git clone git@github.com:VishnuJampalaUB/loan-management-system.git
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

- Access the Postman collection - https://www.postman.com/altimetry-explorer-59925640/bda43e84-877d-4850-b3ec-4b09cfae9033/collection/53iwr0h/loan-management-system

- **Automated Token Handling**: Login saves the token globally, auto-attaching it to all authorized requests.


### Running Tests
Integration tests are configured to use an SQLite in-memory database.

```bash
docker-compose exec app php artisan test
```

