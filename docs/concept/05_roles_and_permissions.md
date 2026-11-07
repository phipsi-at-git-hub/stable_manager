# StableManager — Roles and Permissions

**Document status:** Working baseline  
**Version:** 0.2

## 1. Purpose

This document defines the initial authorization model for StableManager. It distinguishes platform-level administration from permissions within an individual Stable and establishes rules for users who belong to multiple Stables.

## 2. Authorization Principles

1. **Authentication is not authorization.** A signed-in user does not automatically have access to a Stable.
2. **Tenant membership is required.** A user must have an active membership in the Stable being accessed.
3. **Least privilege.** Users receive only the permissions required for their responsibilities.
4. **Server-side enforcement.** Hiding a button in the frontend is not an authorization control.
5. **Consistent checks.** API endpoints, downloads, background jobs, exports, and search must apply the same tenant and permission rules.
6. **Auditable administration.** Membership and permission changes should be recorded.
7. **Explicit external access.** Horse owners and external professionals receive only deliberately granted access.

## 3. Role Model

StableManager has two authorization levels:

- **Platform level:** administration of the StableManager service itself.
- **Stable level:** access to data and operations inside a specific Stable workspace.

A platform administrator is not an ordinary Stable role. Platform access should be tightly restricted and separately audited.

## 4. Proposed Roles

### 4.1 Platform Administrator

Responsible for platform operations, support, and service-level administration.

Typical capabilities:

- Manage platform configuration.
- Review service health and operational status.
- Manage customer workspace lifecycle where required.
- Provide support through controlled, auditable procedures.

Platform administrators should not routinely browse customer business data. Any exceptional support access should be purpose-limited, logged, and governed by policy.

### 4.2 Stable Owner

The primary business owner of a Stable workspace.

Typical capabilities:

- Manage Stable settings.
- Invite, suspend, and revoke memberships.
- Assign Stable-level roles.
- Manage horses, people, health records, appointments, tasks, and documents.
- View operational dashboards.
- Access and manage relevant audit information.

Ownership transfer and removal of the last owner require special safeguards.

### 4.3 Stable Manager

Responsible for day-to-day administration.

Typical capabilities:

- Manage operational records.
- Manage horses and people.
- Create and manage appointments and tasks.
- Assign tasks to permitted members.
- Manage documents.
- View operational dashboards.
- Manage memberships only if explicitly granted.

A manager should not automatically be able to transfer ownership or change platform-level settings.

### 4.4 Employee

Performs operational work.

Typical capabilities:

- View permitted horse and stable information.
- View assigned or relevant tasks.
- Update task progress and completion.
- View permitted appointments.
- Add permitted operational notes or records.

Access to sensitive health information, membership administration, and bulk exports should be limited unless the role requires it.

### 4.5 Trainer / Instructor

Works with horses and training-related appointments.

Typical capabilities:

- View horses explicitly made available to the trainer.
- View relevant appointments.
- Add or update training-related information if that feature is implemented.
- Access only the people and documents needed for the assigned work.

This role should not imply broad access to all Stable data.

### 4.6 Horse Owner

A person who owns or co-owns one or more horses.

Typical capabilities, when external access is enabled:

- View explicitly linked horses.
- View selected horse information.
- View selected health/care records and documents if permitted.
- View relevant appointments or updates if enabled.
- Manage personal account details.

A horse owner must not automatically gain access to all Stable records, internal notes, staff tasks, other owners, or business administration.

### 4.7 Riding Partner / Limited External User

An optional future role for a person who needs narrow access to a horse or selected operational information. This role should be introduced only when a concrete use case and permission model are defined.

## 5. Permission Matrix — Initial Proposal

Legend: **Full** = create/read/update/archive within policy; **Limited** = selected records/actions; **Own** = own or assigned items; **No** = no default access.

| Capability | Owner | Manager | Employee | Trainer | Horse Owner |
|---|---:|---:|---:|---:|---:|
| Manage Stable settings | Full | Limited | No | No | No |
| Manage memberships | Full | Optional | No | No | No |
| View horse records | Full | Full | Limited | Limited | Own/linked |
| Create/update horse records | Full | Full | Limited | Limited | No by default |
| Manage people records | Full | Full | Limited | No by default | Own profile |
| View health records | Full | Full | Limited | Limited | Own/linked, selected |
| Create health records | Full | Full | Limited | Limited | No by default |
| Manage appointments | Full | Full | Limited | Limited | View selected |
| Manage tasks | Full | Full | Own/assigned | Limited | No |
| Manage documents | Full | Full | Limited | Limited | View selected |
| View dashboard | Full | Full | Limited | Limited | Limited/none |
| View audit log | Full | Limited | No | No | No |
| Export Stable data | Full | Optional | No | No | No |

This matrix is a starting point. Each permission must be implemented as an explicit policy and refined against actual workflows.

## 6. Permission Naming

Prefer capability-based permission keys rather than checking role names throughout the application.

Example keys:

```text
stable.view
stable.update
memberships.view
memberships.invite
memberships.update
memberships.revoke
horses.view
horses.create
horses.update
horses.archive
people.view
people.create
people.update
health_records.view
health_records.create
health_records.update
appointments.view
appointments.create
appointments.update
tasks.view
tasks.create
tasks.assign
tasks.update
tasks.complete
documents.upload
documents.view
documents.delete
audit_logs.view
exports.create
```

Role names can map to these permissions. A role should not be used as a shortcut for access when a capability check is clearer.

## 7. Tenant Context and Access Evaluation

For every tenant-owned resource request, the backend should establish:

1. The authenticated user.
2. The requested or active Stable.
3. Whether the user has an active membership in that Stable.
4. Whether the user has the required capability.
5. Whether the specific resource belongs to that Stable.
6. Whether any record-level restrictions apply.

The request must fail safely if any part of this evaluation is missing or inconsistent.

## 8. Horse Owner Access Model

Horse-owner access requires particular care because a horse may have multiple owners and ownership can change over time.

Recommended approach:

- Link the owner through `horse_contacts` or a dedicated ownership relationship.
- Track effective dates where ownership history matters.
- Require an explicit Stable decision to enable portal access.
- Grant access only to the horse(s) linked to the person.
- Separate internal notes from information visible to external users.
- Treat each document as independently permission-controlled.
- Revoke access when the relationship ends, subject to any agreed retention or legal requirements.
- Test direct URL and API access after revocation.

Do not assume that being listed as an owner in a contact record automatically creates a login or portal entitlement.

## 9. Sensitive Actions and Audit

Record at least:

- Membership invitations, role changes, suspension, and revocation.
- Stable ownership changes.
- Changes to permission configuration.
- Important changes to horse identification and status.
- Creation, update, and archival of health records where appropriate.
- Document upload and deletion.
- Bulk exports.
- Exceptional platform support access.

Audit records should identify the actor, action, target, Stable, and timestamp where possible. Keep metadata limited and avoid copying sensitive content into the audit log.

## 10. Authorization Test Requirements

Automated tests must cover:

- User with no membership cannot access a Stable.
- User in Stable A cannot access Stable B resources.
- Revoked or suspended membership cannot access tenant data.
- Employee cannot perform manager-only actions.
- Horse owner cannot access an unlinked horse.
- Horse owner cannot access internal-only notes or documents.
- A document download checks authorization, not merely possession of an identifier.
- Nested routes cannot be used to bypass tenant scoping.
- Background jobs do not operate under an unintended tenant context.
- Search, notifications, and dashboard results do not leak records across Stables.

## 11. Implementation Guidance

Start with a small, understandable role set and centralized Laravel policies. Avoid a complex customer-configurable role editor until real customer needs justify it. Keep permission keys stable and testable so that roles can evolve without rewriting business logic.
