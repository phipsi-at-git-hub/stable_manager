
# StableManager – Product Concept 1.1

**Version:** 1.1 
**Status:** Approved Product Direction  
**Product Type:** Central SaaS Platform  
**Architecture Direction:** Modular Monolith with Multi-Tenant Architecture

---

# 1. Executive Summary

StableManager is a central SaaS platform designed to connect riding stables, horses, and the people involved in their care and management.

The product combines two complementary capabilities:

1. A comprehensive management environment for individual riding stables.
2. A cross-stable platform that connects people and their relationships with horses and different stable environments.

Each stable receives its own logically isolated workspace in which it can manage horses, people, health records, appointments, tasks, documents, and other operational information.

At the same time, StableManager provides a shared identity layer. People can use one central account to participate in multiple stable environments and maintain different roles and relationships in each of them.

The long-term objective is to create a connected ecosystem that provides meaningful value not only to stable operators but also to horse owners, employees, trainers, riding partners, and other people involved with horses.

The initial rollout will focus on one selected pilot stable and its associated community. The architecture will support additional stables as the product evolves.

The creation of new stable tenants will initially remain under the exclusive control of the Platform Owner.

---

# 2. Product Vision

## 2.1 Vision Statement

Create a connected digital platform that simplifies stable operations and brings together the people, horses, and relationships that make up the equestrian environment.

StableManager should become more than a traditional stable management application.

It should provide a shared digital foundation for the different people and organizations involved with horses, while respecting the boundaries, responsibilities, and data ownership of individual stables.

## 2.2 The Problem

Stable operations involve many different people, responsibilities, and information sources.

Examples include:

- Stable owners managing daily operations.
- Employees coordinating feeding, care, and other duties.
- Trainers organizing lessons and training activities.
- Horse owners tracking health information and important events.
- Riding partners coordinating their responsibilities.
- Veterinarians and other external contacts contributing to horse care.

Information is often distributed across paper documents, spreadsheets, messaging applications, calendars, and individual conversations.

This can lead to:

- Fragmented information.
- Unclear responsibilities.
- Missed appointments and follow-ups.
- Duplicated data.
- Limited transparency.
- Difficult coordination between different stakeholders.

Traditional stable management applications often focus primarily on the individual stable.

StableManager aims to address both the operational needs of individual stables and the relationships that extend beyond a single stable environment.

## 2.3 The Opportunity

The opportunity is to combine stable-specific management with a shared platform for people and their relationships with horses.

This creates two complementary value propositions:

**For stables:** A structured and reliable system for organizing daily operations.

**For people connected to horses:** One consistent platform for managing their involvement across different stable environments.

---

# 3. Product Strategy

## 3.1 Combined Product Concept

StableManager follows a combined product model.

It consists of two conceptual layers.

### Layer A: Stable Management

Each stable has its own dedicated workspace.

This workspace provides the tools needed to organize and manage the stable's daily operations.

The stable controls its operational data and grants access to authorized users.

Core capabilities include:

- Stable information and configuration.
- Horse management.
- People and contact management.
- Health and care records.
- Appointments and calendar.
- Tasks and assignments.
- Document management.
- Operational dashboards.
- Notifications.
- Activity and audit history.

### Layer B: Cross-Stable Platform

The platform provides shared identities and supports relationships across multiple stables.

A person has one central user identity and can be associated with multiple stables.

The same person may have different roles in different environments.

For example:

- Employee at Stable A.
- Horse owner at Stable B.
- Trainer at Stable C.
- Riding partner for a horse at Stable A.

The platform provides the identity and relationship foundation needed to support these scenarios.

Cross-stable functionality must not bypass stable-specific permissions or expose private stable data without explicit authorization.

---

# 4. Target Audience

## 4.1 Initial Target Audience

The initial target audience is one selected riding stable and its associated community.

The first implementation will focus on real operational needs within this environment.

Relevant user groups include:

### Stable Owners

People responsible for the overall operation and organization of a stable.

### Stable Managers

People responsible for coordinating daily activities, employees, horses, and operational processes.

### Employees

People involved in daily stable work and horse care.

### Trainers

People responsible for training activities, lessons, and related coordination.

### Horse Owners

People who own horses located at a stable and need access to relevant information about them.

### Riding Partners

People who regularly ride or care for horses without necessarily owning them.

### Other Authorized People

Additional stakeholders who may need limited access to selected information or functions.

## 4.2 Long-Term Target Audience

In the longer term, StableManager should support additional riding stables and their associated communities.

The platform should be suitable for different stable sizes and organizational structures.

The initial focus remains on practical usability for small and medium-sized stable environments.

---

# 5. Core Product Principles

## 5.1 Central SaaS Platform

StableManager will initially be developed and operated as a centrally hosted SaaS application.

All participating stables use the same application platform.

Each stable receives its own logically isolated tenant environment.

The application is centrally maintained and deployed.

The initial product strategy does not include independent physical installations for individual stables.

## 5.2 Global User Identity

Each person has one central user identity.

A user can participate in multiple stable environments without needing separate accounts.

The user's identity is independent of their membership in a particular stable.

Memberships, roles, and permissions are managed separately for each stable.

## 5.3 Stable-Specific Data Ownership

Each stable is responsible for its own operational data.

Examples include:

- Stable-specific horse records.
- Health and care information.
- Appointments.
- Tasks.
- Documents.
- Internal notes.
- Operational settings.

A user must not gain access to another stable's information merely because they belong to that stable as well.

Access must be explicitly authorized.

## 5.4 Controlled Platform Growth

StableManager will support multiple stable tenants from the beginning.

However, the creation of new stable tenants will initially be restricted to the Platform Owner.

There will be no unrestricted self-service stable creation in the initial product phase.

This allows controlled growth and avoids introducing unnecessary operational complexity during early development.

## 5.5 Modular Architecture

The application will be developed as a modular monolith.

Functional domains are separated into modules with clear responsibilities and interfaces.

The architecture should remain extensible without introducing distributed system complexity prematurely.

## 5.6 Security and Privacy by Design

Security and privacy are foundational requirements.

The application must provide:

- Strict tenant isolation.
- Server-side authorization.
- Explicit access control.
- Secure authentication.
- Protected document storage.
- Auditable administrative actions.
- Appropriate handling of personal and potentially sensitive information.

Frontend restrictions alone are never sufficient to enforce security.

---

# 6. Platform Roles and Stable Roles

StableManager distinguishes between platform-level roles and stable-level roles.

These roles serve different purposes and must remain separate.

## 6.1 Platform-Level Roles

Platform roles control access to platform-wide functionality.

### Platform Owner

The Platform Owner has the highest level of platform administration privileges.

Initially, this role is assigned exclusively to the project owner.

Responsibilities and capabilities include:

- Creating new stable tenants.
- Managing platform-level configuration.
- Managing platform-wide administration.
- Monitoring platform health.
- Managing platform-level access and operational settings.

The Platform Owner role must not be assignable through ordinary stable administration.

Its assignment and use must be protected and auditable.

### Future Platform Roles

Additional platform roles may be introduced later, for example:

- Platform Admin.
- Platform Support.

These roles are not required for the initial implementation.

Their permissions must be explicitly defined before introduction.

## 6.2 Stable-Level Roles

Stable roles define what a user can do within an individual stable.

Initial role concepts include:

| Role | Description |
|---|---|
| Stable Owner | Responsible for the stable and its configuration |
| Stable Manager | Manages daily operations |
| Employee | Performs assigned operational tasks |
| Trainer | Manages permitted training-related activities |
| Horse Owner | Accesses explicitly authorized information about owned horses |
| Riding Partner | Accesses explicitly authorized information related to riding and horse care |

Stable roles are assigned independently for each stable.

A person may have different roles in different stable environments.

## 6.3 Permission Principles

Roles provide a baseline for authorization.

Access to individual resources and actions must be controlled by explicit permissions.

Special care is required for:

- Health records.
- Personal information.
- Financial information.
- Private documents.
- Internal stable notes.
- Horse ownership information.

A user's role in one stable must never automatically grant permissions in another stable.

---

# 7. Identity, People, and Relationships

## 7.1 Separation of People and User Accounts

StableManager distinguishes between a person record and a user account.

A person may exist in the system without having a login account.

For example, a veterinarian or external contact may be recorded as a person without requiring access to StableManager.

A person record can optionally be linked to a central user account.

This allows stables to manage contacts independently of platform access.

## 7.2 Global User Identity

A user account represents a person at platform level.

The same account can be associated with multiple stables.

Stable-specific memberships determine the user's participation and permissions within each environment.

## 7.3 Stable Memberships

Memberships represent the relationship between a user and a stable.

Each membership contains the relevant stable-specific role and status.

The membership model must support multiple simultaneous stable relationships.

## 7.4 Cross-Stable Relationships

The platform should be able to represent relationships between people, horses, and stables.

Examples include:

- A person owning a horse.
- A person being a riding partner for a horse.
- A person working at a stable.
- A person training horses at multiple stables.
- A horse being associated with a particular stable.

These relationships must be explicitly modeled and authorized.

The exact distinction between global relationships and stable-specific relationships will be refined during database design.

---

# 8. Horse Data Concept

Horses are a central part of StableManager.

The initial implementation will manage horses within the context of an individual stable.

A stable-specific horse record may contain:

- Name.
- Date of birth.
- Breed.
- Gender.
- Color.
- Identification information.
- Current status.
- Images.
- Relevant contacts.
- Health records.
- Appointments.
- Documents.

## 8.1 Future Cross-Stable Horse Identity

The long-term product vision may require a horse identity that persists when a horse moves between stables.

For example, a horse could move from Stable A to Stable B while retaining a continuous identity and selected relationships.

However, this must not imply that Stable B automatically gains access to Stable A's historical health records, internal notes, or documents.

The distinction between a global horse identity and stable-specific horse records remains an open architectural decision.

This topic must be resolved before implementing cross-stable horse identity features.

---

# 9. Initial Functional Scope

The first development phase focuses on establishing a reliable foundation and delivering a useful product for the pilot stable.

## 9.1 Platform Foundation

- Authentication.
- User accounts.
- Platform roles.
- Platform administration.
- Stable tenant management.
- Stable memberships.
- Active stable context.
- Tenant-aware authorization.

## 9.2 Stable Management

- Stable profile and configuration.
- Stable member management.
- Stable-specific roles and permissions.

## 9.3 Horse Management

- Horse creation and editing.
- Horse profiles.
- Horse identification information.
- Horse status management.
- Horse images.
- Horse search and filtering.

## 9.4 People Management

- Person records.
- Contact information.
- Horse contact relationships.
- Horse owner relationships.
- Optional linking of person records to user accounts.

## 9.5 Health Management

- Health record categories.
- Health record creation and editing.
- Horse health history.
- Follow-up dates and reminders.

## 9.6 Calendar and Appointments

- Appointment creation and management.
- Calendar views.
- Horse-related appointments.
- Relevant operational events.

## 9.7 Task Management

- Task creation and editing.
- Task assignments.
- Task status management.
- Open and overdue task overview.

## 9.8 Document Management

- Secure document upload.
- Authorized document downloads.
- Associations with horses, people, and other records.
- Tenant-aware document access.

## 9.9 Dashboard and Notifications

- Stable dashboard.
- Upcoming appointments.
- Open and overdue tasks.
- In-app notifications.

## 9.10 Audit and Security

- Audit logging.
- Tenant isolation tests.
- Authorization tests.
- Security review.
- Backup and restoration procedures.

---

# 10. Future Product Capabilities

The following capabilities are potential future extensions and are not part of the initial implementation commitment.

## 10.1 Feeding and Care Planning

- Feeding plans.
- Feeding schedules.
- Care routines.
- Task automation.
- Individual horse requirements.

## 10.2 Riding and Lesson Management

- Riding schedules.
- Lesson planning.
- Horse availability.
- Trainer coordination.
- Riding partner coordination.

## 10.3 Cross-Stable Platform Features

- Improved cross-stable person relationships.
- Horse identity across stable environments.
- Authorized information sharing.
- Cross-stable coordination.
- Additional services for horse owners and other stakeholders.

## 10.4 Financial Management

- Stable-related financial records.
- Invoices.
- Payments.
- Cost tracking.
- Financial reporting.

## 10.5 AI and Intelligent Data Analysis

Potential AI capabilities include:

- Intelligent search.
- Information extraction from documents.
- Automated summaries.
- Task and appointment assistance.
- Data analysis.
- Pattern recognition.
- Decision support.

AI functionality must operate through controlled application services.

AI components must not receive unrestricted database access or bypass the application's authorization model.

## 10.6 GIS and Location-Based Features

Potential future functionality includes:

- Stable and facility maps.
- Location-based information.
- Route planning.
- Geographic visualization.
- Relevant regional services.

---

# 11. Technical and Architectural Direction

## 11.1 Application Architecture

StableManager will use a modular monolith architecture.

The application is deployed as one centrally managed system, with clear separation between functional modules.

Microservices are explicitly not required for the initial product architecture.

## 11.2 Proposed Technology Stack

| Component | Technology |
|---|---|
| Backend | PHP 8.4+ |
| Backend Framework | Laravel |
| Frontend | Vue 3 |
| Frontend Language | TypeScript |
| Database | PostgreSQL |
| Authentication | Laravel Sanctum |
| Background Jobs | Redis / Laravel Queues |
| File Storage | S3-compatible object storage |
| Web Server | Nginx |
| Development Environment | Docker |

## 11.3 Multi-Tenant Architecture

A stable represents one tenant.

Tenant-owned records must be associated with the corresponding stable.

The active stable context is validated on the server.

All tenant-specific operations must verify that the authenticated user is authorized to access the requested stable and resource.

Where practical, database constraints should support tenant consistency.

Cross-tenant access must be prevented by design and covered by automated tests.

## 11.4 Central Identity and Authorization

User identity is managed centrally.

Stable memberships and permissions are evaluated in the context of the selected stable.

Platform-level authorization is separate from stable-level authorization.

A platform role must not be confused with a stable membership role.

## 11.5 Document Storage

Documents must be stored privately.

Downloads require server-side authorization.

A document's storage location or identifier must not provide public access.

Tenant ownership and resource associations must be validated before upload, retrieval, or download.

---

# 12. Privacy, Security, and Data Protection

StableManager will handle personal data and may contain sensitive information about people, horses, and their care.

Privacy and security must therefore be considered throughout product development.

Core requirements include:

- Data minimization.
- Purpose limitation.
- Explicit authorization.
- Tenant isolation.
- Secure authentication.
- Protected file storage.
- Auditability.
- Secure backups.
- Defined retention and deletion procedures.
- Appropriate access to personal and health-related information.

The central SaaS model means that the platform operator is responsible for the security and availability of the shared infrastructure.

The legal roles and obligations of the platform operator and participating stables must be evaluated before production use.

The architecture should support appropriate data protection measures from the beginning.

---

# 13. Deployment and Operating Model

## 13.1 Initial Deployment Model

StableManager will initially operate as one centrally hosted SaaS application.

All participating stables use the same application deployment.

Stable data remains logically separated through the multi-tenant architecture.

## 13.2 Controlled Stable Creation

The creation of new stable tenants is initially restricted to the Platform Owner.

The product must support the technical creation and administration of multiple stable tenants.

However, public self-service tenant creation is not part of the initial product scope.

## 13.3 Initial Rollout

The first rollout will focus on:

1. One selected pilot stable.
2. The people associated with that stable.
3. Real operational workflows.
4. Practical validation and feedback.
5. Incremental improvement of the application.

Additional stables may be introduced later when the platform is sufficiently mature.

## 13.4 Future Operating Considerations

The following topics remain open for later evaluation:

- Public registration.
- Stable onboarding processes.
- Subscription and pricing models.
- Customer support processes.
- Service availability commitments.
- Commercial operation.
- Alternative deployment models.

These decisions must not unnecessarily complicate the initial development phase.

---

# 14. Development Strategy

StableManager is developed as a long-term project with an emphasis on quality and practical value.

The development process follows continuous Kanban:

**Backlog → To Do → Doing → Done**

There are no mandatory sprint cycles or fixed weekly delivery commitments.

The project prioritizes:

- Small, verifiable implementation steps.
- Maintainable code.
- Automated testing.
- Clear documentation.
- Security and privacy.
- Real-world validation.
- Incremental delivery.

The first objective is a complete and useful foundation for the pilot stable.

The long-term product vision should guide architectural decisions without forcing every future feature into the initial implementation.

---

# 15. Product Success Criteria

The initial product phase will be considered successful when:

- The pilot stable can use StableManager for relevant daily operations.
- Users can access the system through a central account.
- Stable-specific memberships and permissions work reliably.
- Tenant data is strictly isolated.
- Horses and their relevant information can be managed.
- People and their relationships with horses can be represented.
- Health records, appointments, tasks, and documents can be managed.
- Users can access only information they are authorized to see.
- Important actions are auditable.
- The system is maintainable and ready for further development.

Long-term success will be measured by the platform's ability to support additional stables and increasingly complex relationships between people, horses, and stable environments.

---

# 16. Open Product and Architecture Decisions

The following decisions are intentionally left open for future refinement.

| Topic | Open Question |
|---|---|
| Global Horse Identity | Should horses have a persistent platform-wide identity? |
| Horse Transfers | How should horse data and relationships behave when a horse changes stables? |
| Cross-Stable Sharing | Which information can be shared between different stable environments? |
| Person Relationships | Which relationships belong globally to a person and which are stable-specific? |
| External Access | How should veterinarians and other external contacts receive limited access? |
| Notifications | Which notifications are global and which are stable-specific? |
| Data Retention | How should archived or deleted stable data be handled? |
| Onboarding | How should users and additional stables be onboarded? |
| Commercial Model | Should StableManager eventually become a commercial SaaS product? |

These decisions should be addressed when they become relevant to implementation.

They should not delay the development of the initial stable management foundation unless they affect core data integrity or security.

---

# 17. Product Decisions – Version 1.0

The following decisions define the approved product direction for StableManager 1.0.

| ID | Decision | Status |
|---|---|---|
| PD-001 | StableManager combines stable management and a cross-stable platform | Approved |
| PD-002 | StableManager initially uses a central SaaS deployment model | Approved |
| PD-003 | Each stable represents a logically isolated tenant | Approved |
| PD-004 | Users have one central platform identity | Approved |
| PD-005 | Users can belong to multiple stables | Approved |
| PD-006 | Stable memberships and permissions are managed independently | Approved |
| PD-007 | Platform roles and stable roles are separated | Approved |
| PD-008 | New stable creation is initially restricted to the Platform Owner | Approved |
| PD-009 | The initial rollout targets one pilot stable and its associated community | Approved |
| PD-010 | The application is developed as a modular monolith | Approved |
| PD-011 | Strict tenant isolation is a fundamental security requirement | Approved |
| PD-012 | Public self-service stable registration is not part of the initial scope | Approved |
| PD-013 | A global horse identity remains an open architectural decision | Open |

---

# 18. Final Product Statement

StableManager is a central SaaS platform that combines dedicated management environments for individual riding stables with a shared identity and relationship layer connecting people, horses, and stable environments.

Its purpose is to simplify daily stable operations while creating a connected digital ecosystem for everyone involved with horses.

The product starts with one pilot stable and its community, but its architecture and long-term vision are designed to support multiple stables and increasingly interconnected equestrian relationships.

**StableManager – One platform. Connected stables. Better horse management.**
