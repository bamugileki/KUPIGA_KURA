<p align="center">
    <h1 align="center">Tume Huru ya Taifa ya Uchaguzi Tanzania</h1>
    <p align="center">Comprehensive Electronic Voting Platform for Tanzania's National Electoral Commission</p>
</p>

---

## Overview

**Tume ya Uchaguzi** is a secure, bilingual (English/Swahili) electronic voting platform built for the National Electoral Commission of Tanzania. It provides end-to-end election management — from voter registration and identity verification through NIDA (National ID), to live vote casting, real-time results tracking, fraud detection, and full audit trails.

## Features

### Voter Features
- **Registration & Identity Verification** — Register using NIDA number, driving licence, or NHIF number with automatic age validation
- **Vote Casting** — Cast votes in active elections with one-vote-per-election enforcement
- **Vote Receipts** — View confirmation receipts after voting
- **Voting History** — Access personal voting history
- **Election Results** — View live results once elections close
- **Objections** — Submit objections to elections or candidates

### Candidate Features
- **Candidate Registration** — Apply with detailed profiles (photo, party, biography, experience, running mate)
- **Campaign Dashboard** — View real-time vote rankings and performance
- **Nomination Support** — Track nomination endorsements

### Admin Features
- **Election Lifecycle Management** — Full control: draft → nomination_open → published → campaign_period → active → closed → objection_period → returned
- **Candidate Approval** — Review and approve/reject candidate applications
- **User Management** — Manage voters, officers, observers, and administrators
- **Assisted Voting** — Election officers can assist voters with consent tracking
- **Fraud Detection** — Automatic detection of duplicate IDs, underage registration, and suspicious activity
- **Audit Logs** — Complete audit trail with IP and device information
- **Suspicious Activity Logs** — Flag and review suspicious behavior
- **Objections & Violations** — Manage election objections and code-of-conduct violations
- **Announcements** — Create and publish bilingual announcements with priority levels
- **Results Export** — Export election results
- **System Settings** — Configure platform settings
- **Constituency Management** — Manage constituencies for parliamentary and councillor elections

### Security Features
- NIDA identity validation and age extraction
- Account locking after failed login attempts
- Duplicate registration detection
- Fraud detection event system
- Full audit trail for all critical actions
- IP and device tracking

## Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | PHP 8.1+, Laravel 10 |
| **Database** | SQLite (configurable) |
| **Auth** | Laravel Sanctum |
| **Frontend** | Blade Templates, Tailwind CSS |
| **Icons** | SVG inline |
| **Localization** | Custom English/Swahili translation system |
| **Identity** | NIDA National ID validation |

## Requirements

- PHP ^8.1
- Composer
- SQLite (or other Laravel-supported database)

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/bamugileki/KUPIGA_KURA.git
   cd KUPIGA_KURA
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Set up database**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Start the server**
   ```bash
   php artisan serve
   ```

   Visit `http://localhost:8000` in your browser.

## Default Roles

The system supports multiple user roles with distinct permissions:

| Role | Description |
|------|-------------|
| **Voter** | Register, verify identity, cast votes, view results |
| **Candidate** | Register candidacy, view campaign performance |
| **Election Officer** | Manage elections, assist voters |
| **Observer** | Read-only election monitoring |
| **Admin** | Full platform management |
| **Super Admin** | Complete system control |

## Language Support

The platform is fully bilingual:
- **English**
- **Swahili** (Kiswahili)

Toggle language from the navigation bar at any time.

## License

This project is developed for the National Electoral Commission of Tanzania.

---

<p align="center">Developed by <a href="https://github.com/bamugileki">Francis Bamugileki</a></p>
