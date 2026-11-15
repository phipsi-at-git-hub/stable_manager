
# StableManager

**A connected management platform for riding stables, horses, and the people involved in their care.**

StableManager is a modular web application designed to simplify the organization and daily operations of riding stables while connecting the people involved with horses across different stable environments.

The project combines two complementary ideas:

- **Stable Management:** A dedicated digital workspace for each stable to manage horses, people, health records, appointments, tasks, documents, and daily operations.
- **Cross-Stable Platform:** A shared platform that connects people and their relationships with horses and stables, even when they are involved in multiple stable environments.

StableManager is designed as a central SaaS platform with strict tenant isolation and a shared identity layer.

---

## Project Vision

The long-term vision is to create a connected ecosystem around horses and the people involved in their lives.

A person might, for example:

- Work as an employee at one stable.
- Own a horse at another stable.
- Be a riding partner for a horse at a third stable.
- Work as a trainer across multiple stable environments.

StableManager aims to represent these relationships in one coherent platform without compromising the privacy and data ownership of individual stables.

The goal is not simply to digitize stable administration. It is to create meaningful value for both stable operators and the wider community of people connected to horses.

---

## Core Product Principles

### 1. Central SaaS Platform

StableManager is developed as a centrally hosted SaaS application.

All customers use the same application platform, while each stable receives its own logically isolated workspace.

The platform is designed to support multiple stables from the beginning, even though the initial rollout will focus on one pilot stable.

### 2. Global User Identity

Each person has one central user identity.

A user can be associated with multiple stables and can have different roles and permissions in each of them.

This avoids the need for separate accounts when a person is involved with multiple stables.

### 3. Strict Tenant Isolation

Each stable manages its own operational data.

Access to horses, health records, appointments, documents, and other stable-specific information is controlled by tenant membership and explicit permissions.

Being associated with one stable does not automatically grant access to another stable's data.

### 4. Controlled Stable Creation

The creation of new stable tenants is initially restricted to the Platform Owner.

There will be no unrestricted self-service stable creation in the initial product phase.

This allows the platform to grow in a controlled manner and keeps the initial deployment manageable.

### 5. Modular Architecture

StableManager is developed as a modular monolith.

Business capabilities are separated into clearly defined modules while remaining part of one application.

This keeps the architecture maintainable without introducing the operational complexity of microservices.

### 6. Security and Privacy by Design

Tenant isolation, authorization, secure document handling, auditability, and privacy are fundamental architectural requirements.

Security must be enforced on the server side and must never depend solely on frontend restrictions.

---

## Initial Target Audience

The first target audience is one selected riding stable and the people associated with it.

This includes, for example:

- Stable owners and managers
- Employees
- Trainers
- Horse owners
- Riding partners
- Other authorized people involved in stable operations or horse care

The initial implementation will be validated in this real-world environment.

In the longer term, StableManager should support additional stables and their respective communities.

---

## Main Functional Areas

The platform is designed to grow through independent functional modules.

| Module | Purpose |
|---|---|
| Authentication | User authentication and account management |
| Platform Administration | Platform-level administration and stable management |
| Stable Management | Stable information and operational configuration |
| Memberships | Relationships between users and stables |
| Horses | Horse profiles and stable-specific horse information |
| People | Contacts and people involved in stable operations |
| Health | Health records, treatments, and follow-up dates |
| Calendar | Appointments and relevant events |
| Tasks | Tasks, assignments, and status tracking |
| Documents | Secure document storage and associations |
| Dashboard | Operational overview and relevant information |
| Notifications | User notifications and reminders |
| Audit | Traceability of important actions |

Potential future modules include:

- Feeding and care planning
- Riding and lesson management
- Booking and availability management
- Financial administration
- Advanced analytics
- AI-assisted workflows
- Cross-stable services and features

---

## Architectural Direction

StableManager follows a central SaaS architecture.

The main architectural concepts are:

- One central application platform
- One central user identity system
- Multiple logically isolated stable tenants
- Stable-specific memberships and permissions
- Platform-level roles separated from stable-level roles
- Shared application services with tenant-aware authorization
- Centralized infrastructure and deployment

A stable is represented as a tenant.

Users can belong to multiple stable tenants. Their permissions are evaluated in the context of the currently accessed stable.

Stable-specific data must always be associated with the correct tenant.

The initial architecture does not require separate physical installations for individual stables.

---

## Technology Stack

The current proposed technology stack is:

| Area | Technology |
|---|---|
| Backend | PHP 8.4+ / Laravel |
| Frontend | Vue 3 |
| Frontend Language | TypeScript |
| Database | PostgreSQL |
| Authentication | Laravel Sanctum |
| Background Processing | Redis / Laravel Queues |
| File Storage | S3-compatible object storage |
| Web Server | Nginx |
| Development Environment | Docker |
| Architecture | Modular Monolith |

Technology choices may be refined during implementation, but architectural consistency and maintainability take priority.

---

## Development Approach

StableManager is developed as a long-term personal software project.

The project prioritizes:

- Real-world usefulness
- High software quality
- Maintainable architecture
- Security and data protection
- Incremental development
- Practical validation with real users

Development follows a continuous Kanban workflow:

**Backlog → To Do → Doing → Done**

There are no mandatory sprint cycles or fixed weekly delivery commitments.

The initial development focus is a functional and reliable product for the pilot stable rather than implementing every planned feature at once.

---

## Documentation

The `docs/` directory contains the central product and technical documentation.

The documentation is the source of truth for product decisions, requirements, architecture, data models, permissions, user experience, and API design.

Implementation tasks are tracked separately.

---

## Project Status

StableManager is currently in the product definition and architectural planning phase.

The following fundamental decisions have been made:

- StableManager will be a central SaaS platform.
- The product combines stable management with cross-stable relationships.
- Each stable is represented as an isolated tenant.
- Users have a central identity and can belong to multiple stables.
- Platform-level and stable-level permissions are separated.
- Creation of new stables is initially restricted to the Platform Owner.
- The first rollout targets one pilot stable and its associated people.
- The architecture should support additional stables in the future.

Detailed onboarding, registration, subscription, and commercial operating models have not yet been finalized.

---

## Long-Term Goal

Build a reliable and extensible platform that creates real value for riding stables and everyone connected to horses.

StableManager should grow from a practical management solution for one stable into a connected platform that supports relationships, collaboration, and information sharing across multiple stable environments.

---

## License 

Copyright (c) 2026 Philipp Krummenauer. All rights reserved.

StableManager is proprietary software.

No license or permission is granted to use, copy, modify,
distribute, sublicense, or commercially exploit this software
without prior written permission from the copyright holder.

Third-party dependencies remain subject to their respective
licenses.
