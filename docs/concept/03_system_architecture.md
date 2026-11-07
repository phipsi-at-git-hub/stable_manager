
# StableManager — System Architecture

**Document status:** Working baseline  
**Version:** 0.2  
**Architecture style:** Modular monolith

## 1. Architectural Goals

The architecture should support a maintainable product built by one developer, while retaining clear boundaries between business domains. It should avoid premature distribution into microservices and avoid building infrastructure for features that are not yet required.

Primary goals:

- Strong tenant isolation.
- Clear module boundaries.
- Straightforward local development and deployment.
- Testable business logic.
- Secure file and document handling.
- A stable API for the frontend.
- Room for future modules without speculative complexity.

## 2. Recommended Technology Stack

| Layer | Recommended technology | Purpose |
|---|---|---|
| Backend | PHP 8.4+ and Laravel | Application logic, API, authorization, jobs |
| Frontend | Vue 3 and TypeScript | Interactive web interface |
| Build tooling | Vite | Frontend build and development server |
| Database | PostgreSQL | Relational data and integrity |
| Authentication | Laravel Sanctum | First-party SPA/API authentication |
| Cache and queue | Redis | Cache, queue, and short-lived coordination |
| File storage | S3-compatible object storage | Documents and images |
| Web server | Nginx | TLS termination and HTTP serving |
| Runtime | PHP-FPM | PHP application execution |
| Local environment | Docker Compose | Reproducible development services |
| Testing | Pest or PHPUnit, Vitest, Playwright | Backend, frontend, and end-to-end tests |
| Code quality | PHPStan, Laravel Pint, ESLint | Static analysis and consistency |

Versions should be checked against current Laravel and PHP compatibility before the project is initialized. Prefer supported stable releases over adopting a version solely because it is newest.

## 3. High-Level Architecture

```text
Browser
  |
  | HTTPS
  v
Nginx
  |
  +------------------------------+
  |                              |
  v                              v
Vue 3 SPA                    Laravel API
                                 |
             +-------------------+-------------------+
             |                   |                   |
          Modules             Core Services       Queue Workers
             |                   |                   |
             +-------------------+-------------------+
                                 |
                   +-------------+-------------+
                   |             |             |
                PostgreSQL     Redis      Object Storage
```

The frontend communicates with the backend through a versioned JSON API. The backend is the authority for identity, tenant context, authorization, validation, business rules, and data access.

## 4. Modular Monolith

The application is deployed as one backend application, but its code is organized into domain modules.

Suggested structure:

```text
app/
  Core/
    Authentication/
    Authorization/
    Tenancy/
    Audit/
    Notifications/
    Storage/
  Modules/
    Dashboard/
    Stable/
    Horses/
    People/
    Health/
    Calendar/
    Tasks/
    Documents/
  Shared/
    Exceptions/
    Support/
    ValueObjects/

resources/
  js/
    app/
    components/
    modules/
      dashboard/
      horses/
      people/
      health/
      calendar/
      tasks/
      documents/

routes/
  api.php
```

A module may contain its own controllers, requests, actions/services, policies, models, events, and tests. The exact folder layout can follow Laravel conventions where that improves clarity; the goal is a comprehensible domain boundary, not a custom framework.

### Module responsibilities

- **Stable:** workspace settings and membership lifecycle.
- **Horses:** horse records, status, identification, and horse profiles.
- **People:** person records and contact details.
- **Health:** health/care records and follow-up information.
- **Calendar:** appointments and calendar queries.
- **Tasks:** task lifecycle, assignments, and comments.
- **Documents:** metadata, storage operations, and authorized downloads.
- **Dashboard:** read-oriented summaries assembled from authorized module data.

## 5. Core Services

### Authentication

Authentication establishes the identity of the user. It does not, by itself, grant access to any Stable.

### Authorization

Policies and permission services determine whether the authenticated user may perform an action on a specific resource in the active Stable. Authorization must be enforced in the backend, not inferred from hidden frontend controls.

### Tenancy

Tenancy resolves the active Stable context and scopes tenant-owned queries. The active Stable identifier must be validated against the user's memberships on every request where tenant data is accessed.

### Audit

Audit services record selected high-value actions such as membership changes, permission changes, sensitive record updates, and document access events where justified.

### Storage

Storage services validate files, generate safe object keys, store metadata, and authorize downloads. Application code should not expose private object storage directly.

### Notifications

Notifications handle in-app events and, when implemented, outbound channels such as email. Notifications should be queued where appropriate.

## 6. Multi-Tenancy Model

StableManager uses a shared-database, shared-application multi-tenant model.

- A Stable is a tenant.
- A user may be a member of several Stables.
- Tenant-owned business records carry a `stable_id`.
- The current Stable is selected by the user and validated by the backend.
- Every query and mutation must be scoped to an authorized Stable.
- A tenant identifier supplied by the browser is never trusted without membership validation.

### Isolation requirements

1. Use centralized tenant-context resolution.
2. Use policies and query scopes consistently.
3. Add database constraints that prevent relationships across Stables.
4. Test direct object access, nested resources, search, exports, and file downloads.
5. Avoid relying only on frontend filtering.
6. Ensure background jobs carry and validate the correct Stable context.
7. Ensure cache keys include tenant context when cached data is tenant-specific.

For high-risk relationships, use composite constraints where practical. For example, a health record's `stable_id` and `horse_id` should reference a horse belonging to that same Stable. This can be enforced with a unique composite key on the horse table and a matching composite foreign key, subject to the chosen migration design.

## 7. Person and User Separation

A **User** represents an authentication identity. A **Person** represents a real-world contact in a Stable's business domain.

A person may have no login. A user may be associated with a person record. Avoid using the users table as the universal contact directory, because not every owner, veterinarian, farrier, or emergency contact needs application access.

The association should be explicit and protected against accidental cross-tenant identity disclosure.

## 8. API Design Principles

- Use `/api/v1` as the versioned API prefix.
- Use JSON request and response bodies.
- Use consistent validation error structures.
- Use HTTP status codes consistently.
- Paginate list endpoints.
- Avoid returning internal database fields unnecessarily.
- Enforce tenant context and policies on every protected endpoint.
- Use request validation classes and resource/serializer layers.
- Keep business logic out of controllers.

Example response shape:

```json
{
  "data": {
    "id": "resource-id",
    "name": "Example"
  },
  "meta": {}
}
```

Validation errors should return a consistent structure with field-level messages. The frontend should not need to parse arbitrary exception text.

## 9. Domain Events and Background Jobs

Use events when they create useful decoupling, for example:

- A task is assigned.
- A health follow-up is approaching.
- A membership is invited or revoked.
- A document upload has completed processing.

Do not introduce an event bus for every internal method call. Direct service calls are acceptable when the dependency is simple and local.

Queue jobs for work that is slow, retryable, or not required to complete the HTTP request. Jobs must be idempotent where possible and must carry sufficient tenant context.

## 10. Frontend Architecture

Use Vue 3 with TypeScript and a feature-oriented structure. Keep API access in a defined client/service layer rather than scattering raw HTTP calls across components.

Recommended frontend areas:

- Application shell and navigation.
- Authentication and active Stable selection.
- Shared UI components and form controls.
- Feature modules for horses, people, health, calendar, tasks, and documents.
- Route guards for user experience only; backend authorization remains authoritative.
- Centralized handling of API errors and session expiry.

Avoid over engineering state management before the application demonstrates a need for it.

## 11. Security Architecture

- Use HTTPS in all deployed environments.
- Use secure session and cookie settings appropriate to the SPA deployment model.
- Apply CSRF protection where applicable.
- Rate-limit authentication and sensitive endpoints.
- Validate and authorize every request.
- Validate uploads by size, extension, and actual content where feasible.
- Store private documents outside the public web root or in private object storage.
- Use short-lived authorized download links only when the authorization flow supports them.
- Keep secrets in environment or secret-management configuration.
- Log security events without logging passwords, tokens, or unnecessary personal data.
- Keep dependencies patched and review security advisories.

## 12. Observability and Operations

The production system should provide:

- Structured application logs.
- Error reporting.
- Health checks for application and dependencies.
- Queue failure visibility and retry procedures.
- Database backup monitoring.
- Storage usage visibility.
- A documented restore procedure.
- Deployment and rollback notes.

## 13. Deployment Environments

Maintain separate environments for:

- Local development.
- Testing/CI.
- Staging.
- Production.

Staging should resemble production sufficiently to validate migrations, storage, queues, and deployment procedures. Production data must not be copied into development casually; use sanitized fixtures where possible.

## 14. Architectural Decisions to Revisit

Revisit these choices only when there is a concrete product or operational reason:

- Whether PostGIS is needed for real geospatial workflows.
- Whether a separate search service is justified.
- Whether a dedicated mobile application is needed.
- Whether any module needs independent scaling.
- Whether the application requires regional hosting or data residency options.

The default is to retain the modular monolith until measurable constraints justify a more complex architecture.
