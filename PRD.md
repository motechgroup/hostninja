You are essentially building a **mini WHMCS + GoDaddy-style hosting marketplace**. Since you already work with Laravel/MySQL and Tailwind, this stack is very suitable.

Below is a detailed **Product Requirements Document (PRD)** you can give to your coding agent, followed by a **Google Stitch UI generation prompt**.

---

# PRODUCT REQUIREMENTS DOCUMENT (PRD)

# Project Name

**HostNinja Cloud — Domain Registration & Web Hosting Platform**

## 1. Product Overview

HostNinja Cloud is a modern web hosting marketplace that allows customers to:

* Search and register domain names
* Purchase web hosting packages
* Manage their domains and hosting accounts
* Manage billing and invoices
* Submit support tickets
* Access hosting tools through a customer dashboard
* Receive automated notifications

The platform should provide an experience similar to:

* GoDaddy
* Namecheap
* Hostinger
* Cloudways
* WHMCS

The system will have:

1. Public marketing website
2. Customer portal
3. Admin dashboard
4. Hosting automation engine
5. Billing system
6. Domain management system

---

# 2. Technology Stack

## Backend

Framework:

* Laravel 12

Language:

* PHP 8.3+

Database:

* PostgreSQL (Primary application database)
* MySQL support where required

Authentication:

* Laravel Breeze/Fortify
* Email verification
* Two-factor authentication

Queue:

* Laravel Horizon

Cache:

* Redis

Storage:

* S3 compatible storage

API:

* REST API

---

## Frontend

Framework:

* Laravel Blade + Livewire 3

Styling:

* TailwindCSS

JavaScript:

* Alpine.js

Charts:

* Chart.js

---

# 3. User Roles

## Customer

Can:

* Register account
* Search domains
* Purchase hosting
* Manage services
* View invoices
* Make payments
* Open tickets
* Manage profile

---

## Reseller

Can:

* Sell hosting packages
* Manage customers
* View commissions
* Create custom pricing

---

## Support Agent

Can:

* Handle tickets
* View customer services
* Reply to customers

---

## Administrator

Full control:

* Manage users
* Manage domains
* Manage servers
* Manage hosting plans
* Manage payments
* Manage invoices
* Manage support
* View analytics

---

# 4. Public Website Features

## Homepage

Sections:

Hero:

"Launch Your Website Today"

Domain search bar:

Example:

```
yourdomain.com

[Search Domain]
```

Features:

* Affordable hosting
* Free SSL
* Fast servers
* 99.9% uptime
* Daily backups

Pricing section:

Hosting plans:

Starter

Business

Professional

Enterprise

---

## Domain Search System

User enters:

example.com

System checks:

Availability

Pricing

Extensions:

.com
.net
.org
.co.ke
.africa
.io

Results:

Available:

example.com

KES 1,200/year

Button:

Register Now

Unavailable:

Suggest alternatives:

example.net

example.co.ke

---

# 5. Domain Management Module

Features:

* Domain registration
* Domain renewal
* Domain transfer
* WHOIS information
* DNS management
* Nameserver management
* Domain locking
* Auto renewal

Database:

domains table

Fields:

id

user_id

domain_name

extension

registration_date

expiry_date

status

registrar

price

---

# 6. Hosting Product System

Admin can create:

Hosting Packages

Example:

## Starter Plan

Storage:
10GB SSD

Bandwidth:
100GB

Email Accounts:
10

Databases:
5

SSL:
Free

Price:

KES 299/month

---

Hosting Features:

* Create plans
* Modify pricing
* Enable/disable plans
* Assign server

Database:

hosting_plans

---

# 7. Customer Dashboard

Dashboard homepage:

Cards:

Active Services

Domains

Invoices

Tickets

Balance

Menu:

Dashboard

My Services

Domains

Billing

Support

Profile

---

## My Services

Display:

Website Hosting

example.com

Status:

Active

Actions:

Manage

Renew

Upgrade

Cancel

---

# 8. Hosting Control Panel Integration

System should support:

Option A:

Integrate with:

* cPanel API
* WHM API

Functions:

Create hosting account automatically

Suspend account

Unsuspend

Terminate

Change package

---

Option B:

Custom hosting manager

Features:

* Server monitoring
* Disk usage
* Bandwidth tracking

---

# 9. Billing System

Features:

Invoices

Recurring billing

Payment history

Subscriptions

Invoice statuses:

Paid

Pending

Cancelled

Overdue

---

Payment gateways:

Kenya:

* M-Pesa STK Push
* Airtel Money

International:

* Stripe
* PayPal

---

# 10. Support Ticket System

Customer:

Create ticket

Categories:

Technical

Billing

Domain

Account

Features:

* Attach files
* Email notifications
* Ticket priority
* Agent assignment

---

# 11. Admin Dashboard

Dashboard metrics:

Revenue

Customers

Active domains

Hosting accounts

Tickets

Server status

Charts:

Monthly revenue

New customers

Domain registrations

---

# 12. Database Structure

Main tables:

users

roles

domains

domain_orders

hosting_plans

hosting_services

servers

invoices

payments

tickets

ticket_messages

transactions

coupons

notifications

settings

---

# 13. Security Requirements

Implement:

* CSRF protection
* Rate limiting
* Password hashing
* Two factor authentication
* Activity logs
* API authentication
* SQL injection protection
* File upload validation

---

# 14. Admin Features

Settings:

Company information

Logo

Currency

Tax

Email templates

Payment settings

SMTP

---

# 15. Email Automation

Send emails:

Welcome email

Domain registration confirmation

Invoice created

Payment received

Renewal reminders

Password reset

---

# 16. Future Features

Phase 2:

* Website builder
* VPS hosting
* Dedicated servers
* SSL marketplace
* Affiliate system
* AI website generator
* Mobile app

---

# Development Approach

Build in phases:

## Phase 1

Foundation:

Authentication

Landing page

Plans

Domain search UI

Customer dashboard

## Phase 2

Billing

Payments

Invoices

## Phase 3

Hosting automation

cPanel integration

## Phase 4

Advanced marketplace features

---

