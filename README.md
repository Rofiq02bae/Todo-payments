# ✅ Todo Application

A clean, maintainable **Todo List** application built with **Laravel 13** and **React 19** using **Inertia.js**. Features include task CRUD, user authentication, Midtrans payment integration, PDF export, and email notifications.

---

## 📋 Table of Contents

- [Overview](#-overview)
- [Tech Stack](#-tech-stack)
- [Features](#-features)
- [Architecture](#-architecture)
- [Getting Started](#-getting-started)
- [Folder Structure](#-folder-structure)
- [Key Endpoints](#-key-endpoints)
- [Payment Flow](#-payment-flow)
- [PDF Export Flow](#-pdf-export-flow)
- [Email Notifications](#-email-notifications)
- [Testing](#-testing)
- [Contributing](#-contributing)
- [License](#-license)

---

## 🔭 Overview

This project is a **fullstack Todo application** designed as a learning platform for a junior fullstack developer. It combines a Laravel backend with a React frontend, integrated seamlessly via Inertia.js. The app goes beyond simple task tracking by incorporating:

- **User authentication** (register, login, logout)
- **Per-user task isolation** — users only see and manage their own todos
- **Midtrans payment gateway** integration for premium todo access
- **PDF export** of paid todo details using DomPDF
- **Email notifications** for task creation, status changes, and payment invoices
- **Webhook signature verification** for secure Midtrans callbacks

---

## 🛠 Tech Stack

### Backend

| Technology | Version | Purpose |
|-----------|---------|---------|
| PHP | 8.3+ | Server-side language |
| Laravel | 13.7 | Backend framework |
| SQLite | — | Default database (MySQL-ready) |
| Laravel DomPDF | — | PDF generation from Blade templates |
| Midtrans PHP SDK | 2.6+ | Payment gateway integration |
| Laravel Wayfinder | 0.1 | Type-safe route generation |

### Frontend

| Technology | Version | Purpose |
|-----------|---------|---------|
| React | 19.2 | UI framework |
| TypeScript | 5.7 | Type safety |
| Inertia.js | 3.0 | SPA-like navigation without API boilerplate |
| Tailwind CSS | 4.1 | Utility-first styling |
| Radix UI | latest | Accessible UI primitives |
| Vite | 8.0 | Build tool & dev server |
| Lucide React | 1.17 | Icon library |
| Sonner | — | Toast notifications |

### DevOps & Testing

| Tool | Purpose |
|------|---------|
| PHP Pint | Code style fixer |
| PHPUnit 12 | Unit & Feature testing |
| ESLint | JavaScript/TypeScript linting |
| Prettier | Code formatting |
| Laravel Sail | Docker-based local development |

---

## ✨ Features

### 🔐 Authentication

- User registration (name, email, password)
- Login with email & password
- Logout with session invalidation
- Route protection via `auth` middleware
- Ownership checks — users can only access their own resources

### ✅ Todo Management (CRUD)

- Create new todos (title, description)
- Mark todos as completed (`is_completed` toggle)
- Edit existing todos
- Delete todos
- Dashboard scoped to current user's todos

### 💳 Midtrans Payment Integration

- Create Snap payment transactions for each todo
- Popup-based Snap payment UI
- Webhook endpoint (`POST /midtrans/webhook`) with **SHA-512 signature verification**
- Supports payment status: `capture`, `settlement`, `success`
- Stores `order_id`, `transaction_id`, and `payment_type`
- Already-paid detection — skips new Snap transaction if todo is already paid

### 📄 PDF Export

- Generate PDF from todo details using **DomPDF** + Blade template
- PDF includes todo metadata and comment section
- Download modal with one-click download
- PDF file stored in Laravel storage
- Auto-generated on successful webhook payment
- Automatic download prompt after successful payment

### 📧 Email Notifications

- **TodoCreatedMail** — sent when a new todo is created
- **TodoStatusChangedMail** — sent when `is_completed` is toggled
- **PaymentInvoiceMail** — sent with payment details upon successful transaction
- Graceful error handling — logs warnings if email fails to send

### 🔒 Security

- CSRF protection (with exclusions for webhook & PDF generate routes)
- Signed webhook signature verification
- Ownership-based authorization via `abort_unless`
- Password hashing via Laravel's default bcrypt
- Production-ready password validation (min 12 chars, mixed, symbols)
- Prohibited destructive DB commands in production

---

## 🏗 Architecture

The application follows a **simple MVC + Service Layer** pattern:

```
React (Inertia Pages)
       ↓
Laravel Controllers
       ↓
Services (MidtransService, PdfExportService, MidtransWebhookService)
       ↓
Eloquent Models (User, Todo, Payment, PdfExport)
       ↓
SQLite / MySQL
```

Design decisions:

- **No repository pattern** — keeps things beginner-friendly
- **Service layer only where needed** — payment, PDF, and webhook logic are extracted
- **Inertia.js over API+SPA** — simpler deployment, shared validation, no CORS issues
- **Blade templates for PDF** — server-side rendering to HTML → PDF
- **Mail sending is synchronous** — no queue worker required for simplicity

---

## 🚀 Getting Started

### Prerequisites

- PHP 8.3+
- Composer
- Node.js 18+ & npm
- SQLite (default) or MySQL

### Installation

```bash
# Clone the repository
git clone <repository-url>
cd Todo

# Run the setup script (installs deps, generates key, runs migrations)
composer run setup
```

This runs:
1. `composer install`
2. Copy `.env.example` → `.env`
3. `php artisan key:generate`
4. `php artisan migrate --force`
5. `npm install`
6. `npm run build`

### Environment Variables

Configure your `.env` file:

```env
APP_NAME=Todo
APP_URL=http://localhost

DB_CONNECTION=sqlite
# DB_DATABASE=/absolute/path/to/database.sqlite

MIDTRANS_SERVER_KEY=your-server-key
MIDTRANS_CLIENT_KEY=your-client-key
MIDTRANS_IS_PRODUCTION=false

MAIL_MAILER=log   # Use Mailpit locally for testing
```

### Run Development Server

```bash
composer run dev
```

This starts:
- Laravel dev server (`php artisan serve`)
- Queue listener (`php artisan queue:listen`)
- Log monitor (`php artisan pail`)
- Vite dev server (`npm run dev`)

Visit **http://localhost** — register an account and start managing todos!

---

## 📁 Folder Structure

```
├── app/
│   ├── Http/Controllers/     # Auth, Todo, Payment, PDF, Webhook controllers
│   ├── Http/Middleware/       # HandleInertiaRequests
│   ├── Mail/                  # Mailable classes
│   ├── Models/                 # User, Todo, Payment, PdfExport
│   └── Services/              # Midtrans, PDF export, Webhook services
├── config/                    # App config (midtrans, inertia, etc.)
├── database/
│   ├── factories/             # Model factories
│   ├── migrations/            # Schema migrations
│   └── seeders/               # Database seeders
├── docs/                      # Project documentation (roadmap, architecture, decisions)
├── resources/
│   ├── css/                   # Tailwind stylesheets
│   ├── js/                    # React pages, components, hooks, actions
│   └── views/                 # Blade templates (PDF layout, emails)
├── routes/
│   ├── web.php                # Web routes (auth, todos, payments, PDF, webhook)
│   └── console.php            # Artisan commands
└── tests/
    ├── Feature/               # Feature tests
    └── Unit/                   # Unit tests
```

---

## 🔗 Key Endpoints

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/login` | Show login page |
| POST | `/login` | Authenticate user |
| GET | `/register` | Show registration page |
| POST | `/register` | Create user account |
| POST | `/logout` | Logout user |

### Todos

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/todos` | List all todos |
| GET | `/todos/create` | Show create form |
| POST | `/todos` | Store new todo |
| GET | `/todos/{id}/edit` | Show edit form |
| PUT | `/todos/{id}` | Update todo |
| DELETE | `/todos/{id}` | Delete todo |

### Payments & PDF

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/payments/create/{todo}` | Initiate Midtrans Snap |
| POST | `/pdf/generate/{todo}` | Generate PDF export |
| GET | `/pdf/download/{pdfExport}` | Download generated PDF |
| POST | `/midtrans/webhook` | Midtrans webhook handler |

---

## 💳 Payment Flow

```
User clicks "Bayar"
       ↓
GET /payments/create/{todo} → Midtrans Snap popup
       ↓
User completes payment
       ↓
Midtrans POST /midtrans/webhook → Signature verified
       ↓
Payment status updated (capture/settlement/success)
       ↓
Invoice email sent
       ↓
(Optional) PDF auto-generated
```

---

## 📄 PDF Export Flow

```
User clicks "Export PDF"
       ↓
POST /pdf/generate/{todo}
       ↓
If already paid → Generate PDF directly → Show download modal
       ↓
If not paid → Return payment URL → Snap popup
       ↓
On payment success → POST again with order_id + transaction_id
       ↓
Update payment status → Generate PDF → Show download modal
```

---

## 📧 Email Notifications

| Event | Mailable | Template |
|-------|---------|----------|
| Todo created | `TodoCreatedMail` | `emails/todo-created.blade.php` |
| Todo status changed | `TodoStatusChangedMail` | `emails/todo-status-changed.blade.php` |
| Payment successful | `PaymentInvoiceMail` | `emails/payment-invoice.blade.php` |

> 💡 Use **Mailpit** or set `MAIL_MAILER=log` during development to prevent actual emails.

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run via composer
composer run test
```

Tests use in-memory SQLite for fast execution. Both **Unit** and **Feature** test suites are configured.

---

## 🤝 Contributing

This project is a learning project. Contributions that improve simplicity, readability, or educational value are welcome. Please:

1. Fork the repository
2. Create a feature branch
3. Run tests before submitting
4. Submit a pull request with a clear description

---

## 📝 License

This project is open-sourced under the **MIT License**. See Laravel's license for framework terms.
