# StableManager — API Design

**Document status:** Working baseline  
**Version:** 0.2  
**API style:** REST over HTTPS, JSON

## 1. Purpose

This document defines conventions and an initial endpoint outline for the StableManager API. The API is the trusted boundary between the frontend and backend. It owns validation, authorization, tenant scoping, and business rules.

## 2. General Conventions

- Base path: `/api/v1`
- Transport: HTTPS in deployed environments.
- Payload format: JSON.
- Resource identifiers: opaque identifiers, preferably UUIDs.
- Timestamps: ISO 8601.
- Date-only fields: `YYYY-MM-DD`.
- Pagination: consistent page-based or cursor-based strategy.
- Validation: server-side for every write operation.
- Authorization: evaluated on every protected request.
- Tenant context: explicit and validated for every tenant-scoped operation.

Use plural resource names and predictable HTTP methods.

## 3. Authentication

Example endpoints, subject to the selected Laravel Sanctum SPA configuration:

| Method | Endpoint | Purpose |
|---|---|---|
| GET | `/auth/user` | Return the authenticated user |
| POST | `/auth/logout` | End the current session |
| POST | `/auth/forgot-password` | Request password reset |
| POST | `/auth/reset-password` | Complete password reset |

Registration and invitation acceptance flows should follow the chosen account policy. Do not expose authentication implementation details unnecessarily.

## 4. Stable Context and Memberships

| Method | Endpoint | Purpose |
|---|---|---|
| GET | `/stables` | List Stables available to the user |
| POST | `/stables` | Create a Stable, if permitted |
| GET | `/stables/{stable}` | View Stable details |
| PATCH | `/stables/{stable}` | Update Stable details |
| GET | `/stables/{stable}/memberships` | List memberships |
| POST | `/stables/{stable}/memberships/invitations` | Invite a member |
| PATCH | `/stables/{stable}/memberships/{membership}` | Update role or status |
| DELETE | `/stables/{stable}/memberships/{membership}` | Revoke membership |

The Stable identifier in the route must be checked against the authenticated user's membership. A route parameter is not proof of access.

## 5. Horses

| Method | Endpoint | Purpose |
|---|---|---|
| GET | `/stables/{stable}/horses` | List/search horses |
| POST | `/stables/{stable}/horses` | Create horse |
| GET | `/stables/{stable}/horses/{horse}` | View horse |
| PATCH | `/stables/{stable}/horses/{horse}` | Update horse |
| DELETE | `/stables/{stable}/horses/{horse}` | Archive horse |
| GET | `/stables/{stable}/horses/{horse}/contacts` | List horse contacts |
| POST | `/stables/{stable}/horses/{horse}/contacts` | Add horse contact |
| PATCH | `/stables/{stable}/horses/{horse}/contacts/{contact}` | Update relationship |
| DELETE | `/stables/{stable}/horses/{horse}/contacts/{contact}` | End/remove relationship |

Horse list endpoints should support controlled filters such as `search`, `status`, and pagination. Do not accept arbitrary database column names as sort or filter expressions.

## 6. People

| Method | Endpoint | Purpose |
|---|---|---|
| GET | `/stables/{stable}/people` | List/search people |
| POST | `/stables/{stable}/people` | Create person |
| GET | `/stables/{stable}/people/{person}` | View person |
| PATCH | `/stables/{stable}/people/{person}` | Update person |
| DELETE | `/stables/{stable}/people/{person}` | Archive person |

Person endpoints must distinguish business contact data from authentication account data. Linking a user account requires a separate authorization and validation flow.

## 7. Health Records

| Method | Endpoint | Purpose |
|---|---|---|
| GET | `/stables/{stable}/horses/{horse}/health-records` | List a horse's records |
| POST | `/stables/{stable}/horses/{horse}/health-records` | Create record |
| GET | `/stables/{stable}/health-records/{record}` | View record |
| PATCH | `/stables/{stable}/health-records/{record}` | Update record |
| DELETE | `/stables/{stable}/health-records/{record}` | Archive record |
| GET | `/stables/{stable}/health-record-types` | List categories |

A health record must be proven to belong to the route's Stable and horse. Do not trust a `horse_id` supplied in a request without validating its tenant ownership.

## 8. Calendar and Appointments

| Method | Endpoint | Purpose |
|---|---|---|
| GET | `/stables/{stable}/appointments` | List appointments |
| POST | `/stables/{stable}/appointments` | Create appointment |
| GET | `/stables/{stable}/appointments/{appointment}` | View appointment |
| PATCH | `/stables/{stable}/appointments/{appointment}` | Update appointment |
| DELETE | `/stables/{stable}/appointments/{appointment}` | Cancel/archive appointment |

Potential query parameters:

- `from`
- `to`
- `horse_id`
- `status`
- `page`
- `per_page`

Validate date ranges and maximum page sizes. Recurrence support should be designed separately if it is introduced.

## 9. Tasks

| Method | Endpoint | Purpose |
|---|---|---|
| GET | `/stables/{stable}/tasks` | List tasks |
| POST | `/stables/{stable}/tasks` | Create task |
| GET | `/stables/{stable}/tasks/{task}` | View task |
| PATCH | `/stables/{stable}/tasks/{task}` | Update task |
| DELETE | `/stables/{stable}/tasks/{task}` | Archive task |
| POST | `/stables/{stable}/tasks/{task}/assignments` | Assign member |
| DELETE | `/stables/{stable}/tasks/{task}/assignments/{assignment}` | Remove assignment |
| GET | `/stables/{stable}/tasks/{task}/comments` | List comments |
| POST | `/stables/{stable}/tasks/{task}/comments` | Add comment |

Task status transitions should be validated by the backend. A client must not be able to set arbitrary status values.

## 10. Documents

| Method | Endpoint | Purpose |
|---|---|---|
| GET | `/stables/{stable}/documents` | List permitted documents |
| POST | `/stables/{stable}/documents` | Upload document |
| GET | `/stables/{stable}/documents/{document}` | View metadata |
| GET | `/stables/{stable}/documents/{document}/download` | Authorized download |
| DELETE | `/stables/{stable}/documents/{document}` | Delete/archive document |

Uploads must enforce file size and content rules. The download endpoint must authorize the user against both the document and its Stable/related record. Never treat an unguessable document ID as authorization.

## 11. Dashboard and Notifications

| Method | Endpoint | Purpose |
|---|---|---|
| GET | `/stables/{stable}/dashboard` | Retrieve dashboard summaries |
| GET | `/notifications` | List current user's notifications |
| POST | `/notifications/{notification}/read` | Mark notification as read |
| POST | `/notifications/read-all` | Mark permitted notifications as read |

Dashboard data should be assembled from permission-aware queries. Avoid returning hidden data in aggregate counts.

## 12. Response Conventions

Successful single-resource response:

```json
{
  "data": {
    "id": "018f-example",
    "name": "Example Horse"
  }
}
```

Paginated response:

```json
{
  "data": [],
  "meta": {
    "current_page": 1,
    "per_page": 25,
    "total": 0
  },
  "links": {
    "next": null,
    "prev": null
  }
}
```

Validation error response:

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "name": [
      "The name field is required."
    ]
  }
}
```

The exact response envelope may follow Laravel conventions, but it must be consistent and documented.

## 13. HTTP Status Codes

Use status codes consistently:

- `200 OK` — successful read or update.
- `201 Created` — resource created.
- `204 No Content` — successful operation with no response body.
- `400 Bad Request` — malformed request where appropriate.
- `401 Unauthorized` — authentication required or invalid.
- `403 Forbidden` — authenticated but not permitted.
- `404 Not Found` — resource does not exist or is intentionally concealed by policy.
- `409 Conflict` — conflicting state or uniqueness condition.
- `422 Unprocessable Entity` — validation failure.
- `429 Too Many Requests` — rate limit exceeded.
- `500 Internal Server Error` — unexpected server failure.

For sensitive tenant resources, returning `404` instead of `403` may reduce resource enumeration. Choose a consistent policy and test it.

## 14. API Security Requirements

- Validate all request payloads.
- Authorize every resource operation.
- Scope all tenant-owned queries.
- Prevent mass assignment of protected fields such as `stable_id`, role, and ownership flags.
- Apply rate limits to authentication, invitation, and sensitive operations.
- Validate uploaded file content and size.
- Protect downloads with authorization checks.
- Avoid leaking stack traces or internal exception details.
- Use CSRF protection for cookie-authenticated SPA requests as required.
- Keep API tokens and secrets out of logs and frontend bundles.
- Test nested routes and cross-tenant object references.

## 15. API Documentation and Testing

Maintain an OpenAPI specification once endpoint contracts stabilize. Tests should cover:

- Expected success responses.
- Validation errors.
- Authentication failures.
- Permission failures.
- Cross-tenant access attempts.
- Invalid state transitions.
- Pagination and filtering boundaries.
- Upload and download authorization.
- Timezone and date-range behavior.

The API should evolve through deliberate, documented changes. Breaking changes should be avoided within `/api/v1`; if a breaking change becomes unavoidable, define a migration strategy before implementation.
