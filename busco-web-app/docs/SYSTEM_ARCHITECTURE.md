# SYSTEM ARCHITECTURE
# BUSCO Sugar Milling Co., Inc. Website System

---

## 1. System Overview

The BUSCO Sugar Milling Co., Inc. Website is a corporate informational web-based system designed to:

- Present company background and corporate profile
- Showcase services and sugar milling operations
- Explain the sugar production process
- Publish company news and achievements
- Display weekly Quedan price updates
- Highlight corporate mission, vision, and CSR programs
- Provide career opportunities information
- Allow content management through a single Administrator account

The system is primarily informational with limited administrative CRUD functionality.

---

## 2. System Objectives

The main objectives of the system are:

1. To provide an official digital presence for BUSCO.
2. To display updated weekly Quedan prices.
3. To manage news and announcements efficiently.
4. To showcase company services and community involvement.
5. To ensure structured, secure, and scalable deployment.

---

## 3. System Type

- Web-Based Corporate Information System
- Single Administrator Role
- Public informational access
- Server-side rendered architecture
- Database-driven content management

---

## 4. Overall System Architecture

The system follows a Three-Layer Architecture:

### 4.1 Presentation Layer (Frontend)
Handles:
- Public website interface
- Admin dashboard interface
- Responsive design layout
- Blade templating structure

Color Palette Implementation:

Primary Green: #1B5E20  
Accent Green: #2E7D32  
Highlight Yellow: #F9A825  
White Background: #FFFFFF  
Alternate Section Background: #F7F9F5  

---

### 4.2 Application Layer (Backend)
Powered by Laravel.

Handles:
- Authentication
- Business logic
- CRUD operations
- Quedan price difference calculation
- Data validation
- Route protection
- Session management

---

### 4.3 Data Layer (Database)
Powered by PostgreSQL.

Stores:
- Administrator account
- News records
- Quedan price records
- Historical price data

---

## 5. Public Website Structure

### 5.1 Home Page
- Hero Section
- Latest News Preview
- Current Active Quedan Price Highlight
- Community Highlight
- Quick Navigation Links

---

### 5.2 About Page
- Company Overview
- History
- Vision & Mission
- Core Values

---

### 5.3 Services / Operations
- Sugar Milling
- Cane Procurement
- Quality Assurance
- Distribution Support

---

### 5.4 Sugar Milling Process
Step-by-step explanation:

1. Cane Delivery & Weighing
2. Crushing
3. Juice Extraction
4. Clarification
5. Evaporation
6. Crystallization
7. Centrifugation
8. Drying & Packaging

---

### 5.5 News & Achievements
Dynamic content module.

Displays:
- Title
- Description
- Image
- Category
- Date
- Status (Published/Draft)

---

### 5.6 Quedan Price Announcement Page

Displays:

QUEDAN PRICE UPDATE  
Week: March 22, 2026  
Price: ₱ 2,500.00 per LKG  
Effective Date: March 27, 2026  
Status: Official  

Change from Last Week:
+ ₱50.00 (if increase)
- ₱100.00 (if decrease)
No Change (if same price)

Includes:
- Previous price reference
- Historical table of past weeks

---

### 5.7 Community & Training
- CSR Programs
- Farmer Trainings
- Workshops
- Outreach Activities

---

### 5.8 Careers
- Job Openings
- Internship Opportunities
- Application Instructions

---

### 5.9 Contact Page
- Address
- Email
- Phone
- Contact Form

---

## 6. Administrator Module

### 6.1 Authentication
Single Administrator Account:

Fields:
- Email
- Password (Hashed)
- Role (Admin)

Security:
- Middleware protection
- Session authentication
- Encrypted credentials

---

### 6.2 Admin Dashboard
Displays:
- Total News Count
- Active Quedan Price
- Last Update Date
- Quick Action Buttons

---

### 6.3 News Management (CRUD)

Admin can:
- Create
- Edit
- Delete
- Publish / Unpublish

Fields:
- Title
- Content
- Image
- Category
- Status
- Created Date

---

### 6.4 Quedan Price Module

Admin inputs:
- Week Label
- Price
- Effective Date
- Notes (optional)

System automatically:
- Retrieves previous active price
- Calculates price difference
- Determines trend (UP / DOWN / NO CHANGE)
- Archives previous record
- Sets new record as Active

---

## 7. Business Logic

Quedan Price Computation:

difference = new_price - previous_price

If difference > 0:
Trend = UP

If difference < 0:
Trend = DOWN

If difference = 0:
Trend = NO CHANGE

Only one active Quedan record is allowed at a time.

When a new price is inserted:
- Previous active record becomes archived.

---

## 8. Database Structure

### 8.1 users Table
- id
- name
- email
- password
- role
- created_at
- updated_at

---

### 8.2 news Table
- id
- title
- content
- image
- category
- status
- created_at
- updated_at

---

### 8.3 quedan_prices Table
- id
- week_label
- price
- effective_date
- difference
- trend
- status (active / archived)
- created_at
- updated_at

---

## 9. Security Considerations

- Password hashing (bcrypt)
- CSRF protection
- Route middleware protection
- Input validation
- File upload validation
- HTTPS enforced
- APP_DEBUG disabled in production

---

## 10. Deployment Architecture (Option A)

### 10.1 Technology Stack

Backend Framework:
- Laravel (PHP)
- MVC Architecture

Database:
- PostgreSQL

Frontend:
- Blade Templating Engine
- Central Layout Structure (app.blade.php)

Blade Architecture:

resources/views/

- layouts/
  - app.blade.php
- partials/
  - navbar.blade.php
  - footer.blade.php
- pages/
  - home.blade.php
  - about.blade.php
  - services.blade.php
  - process.blade.php
  - news.blade.php
  - announcement.blade.php
  - community.blade.php
  - careers.blade.php
  - contact.blade.php
- admin/
  - dashboard.blade.php
  - news/
  - quedan/

Blade Directives:
- @extends
- @section
- @yield
- @include

---

### 10.2 Hosting Environment

Platform:
Railway (Pro Plan)

Infrastructure:
- Laravel App hosted on Railway
- PostgreSQL Database via Railway service
- GitHub Continuous Deployment
- SSL Enabled

Deployment Process:

1. Push Laravel project to GitHub
2. Connect repository to Railway
3. Create Laravel service
4. Add PostgreSQL plugin
5. Configure environment variables
6. Run migrations
7. Deploy production

---

### 10.3 Production Configuration

APP_ENV=production  
APP_DEBUG=false  
DB_CONNECTION=pgsql  

Security:
- Encrypted environment variables
- HTTPS enabled
- Protected admin routes
- Hashed passwords

---

## 11. Scalability & Future Enhancements

Future upgrades may include:

- Email notifications for Quedan updates
- Downloadable PDF of weekly prices
- Multi-admin role management
- Mobile API integration
- Analytics dashboard
- News filtering & search

---

## 12. System Summary

The BUSCO Website System is a structured corporate informational platform with:

- Public informational pages
- Administrator-controlled content management
- News CRUD module
- Weekly Quedan price announcement system
- Automatic price change computation
- Laravel MVC architecture
- PostgreSQL database
- Blade templating engine
- Railway Pro Plan deployment

The system ensures reliability, security, scalability, and professional-grade implementation.
