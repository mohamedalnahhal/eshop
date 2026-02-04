# eShop Platform
Multi-Tenant eCommerce Platform - Web Development Course Project

## Introduction
Building an eCommerce website for a business can be one of the best ways that helps expanding the customer base and increasing business’ reach. However it comes with technical & logistical challenges, technical challenges can be summed up by the cost and the ability of developing and maintaining such a system with its database, servers, payments, UI, etc…, especially for small business.

Multi-tenant eCommerce platforms are SaaS that offer to solve this issue by providing vendors the ability to create a customized and isolated online shop as easily as possible hosted on a unique subdomain with every aspect of it handled by the platform.

This project aims to build a multi-tenant eCommerce platform that manages databases, hosting, backend, payments and other aspects for every vendor subscribed, providing him the ability to manage everything via a dedicated dashboard, hence the challenge of this project.

## Architecture
The platform will utilize modern architecture designs to ensure scalability and data integrity

- Tenancy mode : Single database with tenant isolation
- Routing : Subdomain routing for every store
- Tech stack : 
  - Laravel
  - FilamentPHP (for dashboards)
  - MySQL

These architectural decisions help build the project before the deadline while keeping the quality and integrity of the project.

## Key features
- Super Admin Dashboard: A centralized panel to manage all registered tenants (stores) 
- Tenant Dashboard
  - Authentication
  - Manage Products
  - Manage Orders
  - Store Settings & Branding
  - Generate Reports
- Flexible Store Builder: Each store can have its own branding
- Main Marketstore
