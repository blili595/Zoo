# Zoo

## Setup

composer install
npm install

php artisan key:generate

php artisan migrate

npm run dev
php artisan serve

# Zoo Management System

A full-stack zoo management web application built with Laravel.  
The system allows administrators and caretakers to manage enclosures, animals, feeding schedules, and archived animals through role-based access control.

---

## Features

### Authentication & Authorization
- Authentication required for all pages
- Role-based access control
- Separate permissions for administrators and caretakers
- Backend authorization checks for protected operations

---

## Dashboard

The homepage provides an overview of the zoo’s current state, including:
- total number of enclosures
- total number of animals
- upcoming feeding tasks for the logged-in caretaker

### Feeding Schedule
- Displays only future feedings based on server time
- Feeding list sorted by feeding time
- Time handling implemented with Carbon

---

## Enclosure Management

### Features
- Paginated enclosure list
- Different visibility rules for caretakers and admins
- Display of:
  - enclosure name
  - animal capacity
  - current animal count

### Admin Functionality
- Create new enclosures
- Edit enclosure details
- Assign caretakers to enclosures
- Delete empty enclosures

### Validation Rules
- Enclosures can only be deleted when empty
- Proper backend validation for all operations

---

## Animal Management

### Features
- Create and edit animals
- Upload and replace animal images
- Display animal details:
  - name
  - species
  - birth date
  - image

### Enclosure Rules
- Predators and non-predators cannot share the same enclosure
- Animal count cannot exceed enclosure capacity

### Sorting
Animals inside enclosures are sorted by:
1. species
2. birth date

### Image Handling
- Uploaded images stored locally
- Old images automatically removed when replaced
- Placeholder image displayed when no image exists

---

## Archived Animals

### Soft Delete System
- Animals are archived instead of permanently deleted
- Archived animals listed separately
- Restore functionality for archived animals
- Restoration requires selecting a valid enclosure

### Archive List
Displays:
- animal name
- species
- archive date

Sorted by archive date descending.

---

## Seeder & Database

### Database Structure
Implemented relational database models:
- `User`
- `Enclosure`
- `Animal`

### Relationships
- Many-to-many: caretakers ↔ enclosures
- One-to-many: enclosures → animals

### Seeder
- Faker-generated test data
- Consistent relationship generation
- Handles enclosure validation rules
- Generates realistic testing data

---

## Technical Highlights

- Laravel MVC architecture
- Eloquent ORM relationships
- Form request validation
- Soft Deletes
- File upload and storage handling
- Pagination
- Authentication & authorization
- Role-based access control
- Responsive UI
- Persistent form handling with validation feedback

---

## Technologies Used

- Laravel
- PHP
- Blade
- MySQL
- Eloquent ORM
- Carbon
- Bootstrap / CSS

---

## Project Goal

The goal of the project was to develop a role-based management system with complex relational data handling and business validation rules.

The application demonstrates:
- backend-focused web development
- database design and relationships
- secure authentication and authorization
- server-side validation
- file handling
- CRUD operations
- clean MVC architecture
- real-world business logic implementation
