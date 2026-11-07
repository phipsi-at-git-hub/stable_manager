
# StableManager — Database Design

**Document status:** Conceptual baseline; validate during implementation  
**Version:** 0.2  
**Database:** PostgreSQL

## 1. Design Objectives

The database must represent the core StableManager domain while enforcing tenant boundaries and preserving useful operational history.

Key objectives:

- Clear separation between authentication users and business contacts.
- Stable ownership of tenant data.
- Referential integrity across related records.
- Support for users belonging to multiple Stables.
- Traceable relationships between horses, people, records, appointments, tasks, and documents.
- Practical indexes for common lists and filters.
- Avoidance of premature schema complexity.

This is a logical design, not a complete migration specification. Field types, nullability, deletion behavior, and constraints must be finalized alongside implementation.

## 2. Core Entity Overview

```text
users
  |
  +----< stable_memberships >---- stables
  |                                  |
  |                                  +----< horses
  |                                  |       |
  |                                  |       +----< horse_contacts >---- people
  |                                  |       |
  |                                  |       +----< health_records
  |                                  |       |
  |                                  |       +----< appointment_horses >---- appointments
  |                                  |       |
  |                                  |       +----< tasks
  |                                  |
  |                                  +----< people
  |                                  +----< appointments
  |                                  +----< tasks
  |                                  +----< documents
  |                                  +----< notifications
  |                                  +----< audit_logs
```

## 3. Main Tables

### 3.1 `users`

Authentication identities.

Suggested fields:

- `id`
- `name`
- `email`
- `email_verified_at`
- `password`
- `remember_token`
- `created_at`
- `updated_at`
- `deleted_at` where account lifecycle requires it

Email uniqueness should be defined according to the chosen account policy. Do not store business contact information here merely because a person may eventually log in.

### 3.2 `stables`

Tenant workspaces.

Suggested fields:

- `id`
- `name`
- `slug`
- `email`
- `phone`
- `address` fields or a structured address representation
- `timezone`
- `default_locale`
- `settings` (JSON for limited, non-core preferences)
- `created_at`
- `updated_at`
- `deleted_at`

A Stable should have a defined timezone for calendar display and date-sensitive workflows.

### 3.3 `stable_memberships`

Membership and stable-specific role assignment.

Suggested fields:

- `id`
- `stable_id`
- `user_id`
- `role_id` or `role_key`
- `status` (invited, active, suspended, revoked)
- `invited_by_user_id`
- `joined_at`
- `created_at`
- `updated_at`

Constraints:

- Unique active membership per `(stable_id, user_id)` according to the chosen lifecycle model.
- Foreign keys to `stables` and `users`.
- Membership status must be checked before granting access.

### 3.4 `roles` and `permissions`

If roles and permissions are database-configurable, use explicit tables such as:

- `roles`: `id`, `stable_id` nullable for system roles, `key`, `name`.
- `permissions`: `id`, `key`, `description`.
- `role_permissions`: `role_id`, `permission_id`.

For a simpler first version, stable-scoped role keys plus centralized policy logic may be sufficient. Avoid building a fully dynamic permission editor unless customers need it.

### 3.5 `people`

Business contacts within a Stable.

Suggested fields:

- `id`
- `stable_id`
- `linked_user_id` nullable
- `first_name`
- `last_name`
- `email`
- `phone`
- `address` fields
- `notes`
- `status`
- `created_at`
- `updated_at`
- `deleted_at`

A person may be linked to a login account, but the person record remains a tenant-owned business record.

### 3.6 `horses`

Suggested fields:

- `id`
- `stable_id`
- `name`
- `ueln` nullable
- `other_identifier` nullable
- `date_of_birth` nullable
- `sex` nullable
- `breed` nullable
- `colour` nullable
- `height_cm` nullable
- `description` nullable
- `status`
- `created_at`
- `updated_at`
- `deleted_at`

Potential constraints:

- Unique `(stable_id, ueln)` when UELN is present, if the business rules require it.
- Unique composite key `(stable_id, id)` to support tenant-consistent composite foreign keys.

Do not assume all horses have a UELN or that every identifier is globally unique across all source systems.

### 3.7 `horse_contacts`

Associates people with horses.

Suggested fields:

- `id`
- `stable_id`
- `horse_id`
- `person_id`
- `relationship_type` (owner, co-owner, emergency_contact, veterinarian, farrier, trainer, other)
- `is_primary`
- `valid_from`
- `valid_until`
- `notes`
- `created_at`
- `updated_at`

The table should support multiple contacts per horse and multiple horses per person. Relationship dates preserve historical context.

### 3.8 `health_record_types`

Optional configurable category table.

Suggested fields:

- `id`
- `stable_id` nullable if system defaults are supported
- `key`
- `name`
- `is_active`
- `sort_order`

### 3.9 `health_records`

Suggested fields:

- `id`
- `stable_id`
- `horse_id`
- `health_record_type_id`
- `title`
- `event_date`
- `provider_person_id` nullable
- `notes`
- `next_due_date` nullable
- `created_by_user_id`
- `created_at`
- `updated_at`
- `deleted_at`

Health records are documentation entries. Avoid implying that a recorded treatment or medication is a system-generated veterinary recommendation.

### 3.10 `appointments`

Suggested fields:

- `id`
- `stable_id`
- `title`
- `description`
- `starts_at`
- `ends_at`
- `timezone` or stable timezone reference
- `location` nullable
- `status`
- `created_by_user_id`
- `created_at`
- `updated_at`
- `deleted_at`

Store timestamps consistently, typically in UTC, and convert for display using the Stable's timezone or an explicitly selected appointment timezone.

### 3.11 `appointment_horses`

Many-to-many association:

- `stable_id`
- `appointment_id`
- `horse_id`

Use a unique constraint on the relevant pair. Ensure the appointment and horse belong to the same Stable.

### 3.12 `appointment_people`

Optional association between appointments and people:

- `stable_id`
- `appointment_id`
- `person_id`
- `participation_type`

This supports practitioners, owners, trainers, and other participants without overloading a single appointment field.

### 3.13 `tasks`

Suggested fields:

- `id`
- `stable_id`
- `title`
- `description`
- `status`
- `priority` nullable
- `due_at` nullable
- `completed_at` nullable
- `created_by_user_id`
- `created_at`
- `updated_at`
- `deleted_at`

Task status should be represented by a controlled set of values. Keep task priority separate from task status.

### 3.14 `task_assignments`

- `id`
- `stable_id`
- `task_id`
- `user_id`
- `assigned_by_user_id`
- `assigned_at`

Use uniqueness constraints to prevent duplicate assignments.

### 3.15 `task_comments`

- `id`
- `stable_id`
- `task_id`
- `user_id`
- `body`
- `created_at`
- `updated_at`

### 3.16 `documents`

Suggested fields:

- `id`
- `stable_id`
- `uploaded_by_user_id`
- `original_filename`
- `storage_disk`
- `storage_key`
- `mime_type`
- `file_size`
- `checksum` nullable
- `title`
- `description`
- `category`
- `created_at`
- `deleted_at`

### 3.17 Document associations

Avoid an unrestricted polymorphic association unless every access path is centrally secured and tested.

Safer initial options include explicit association tables, for example:

- `horse_documents` (`stable_id`, `horse_id`, `document_id`)
- `health_record_documents` (`stable_id`, `health_record_id`, `document_id`)
- `appointment_documents` (`stable_id`, `appointment_id`, `document_id`)
- `task_documents` (`stable_id`, `task_id`, `document_id`)

This is more explicit and makes tenant-consistent foreign keys easier to reason about.

### 3.18 `notifications`

Suggested fields:

- `id`
- `stable_id` nullable for account-level notifications
- `user_id`
- `type`
- `data` (limited JSON payload)
- `read_at`
- `created_at`

Do not put sensitive medical or personal details into notification payloads unnecessarily.

### 3.19 `audit_logs`

Suggested fields:

- `id`
- `stable_id` nullable for platform-level events
- `actor_user_id` nullable for system events
- `action`
- `subject_type`
- `subject_id`
- `metadata` (limited JSON)
- `ip_address` where justified and lawful
- `created_at`

Audit logs should be append-only from the application's perspective. Avoid recording passwords, tokens, or full sensitive document contents.

## 4. Tenant Integrity

A `stable_id` column and an application query scope are necessary but may not be sufficient.

For critical relationships, enforce tenant consistency at the database level where practical. A common PostgreSQL pattern is:

1. Add a unique constraint on `(stable_id, id)` in the parent table.
2. Store both `stable_id` and the parent ID in the child table.
3. Add a composite foreign key from `(stable_id, parent_id)` to `(stable_id, id)`.

This helps prevent a record in Stable A from referencing a horse in Stable B, even if application code contains a defect.

Apply the same principle to horse contacts, health records, appointment associations, task associations, and document associations.

## 5. Indexing Guidance

Add indexes based on expected query patterns, including:

- `stable_memberships (user_id, status)`
- `stable_memberships (stable_id, status)`
- `horses (stable_id, status)`
- `horses (stable_id, name)`
- `people (stable_id, last_name, first_name)`
- `health_records (stable_id, horse_id, event_date)`
- `health_records (stable_id, next_due_date)`
- `appointments (stable_id, starts_at)`
- `tasks (stable_id, status, due_at)`
- `task_assignments (stable_id, user_id, task_id)`
- `documents (stable_id, created_at)`

Use PostgreSQL full-text search or a dedicated search service only when simple indexed search is no longer sufficient.

## 6. Deletion and Retention

Use soft deletion or archival for records whose history matters, such as horses, people, and operational records. Define what happens to associated documents and audit history before implementing destructive deletion.

Account deletion, Stable closure, data export, and retention require explicit product and legal policies. Do not cascade-delete important business history accidentally.

## 7. Data Types and Conventions

- Use UUIDs or another consistent non-guessable identifier strategy for externally exposed resource IDs.
- Use `date` for date-only values and timezone-aware timestamps for moments in time.
- Store money as integer minor units or an appropriate fixed-precision decimal if financial functionality is later introduced.
- Store measurements with explicit units or normalized units.
- Use constrained enums or reference tables for controlled statuses.
- Keep JSON for genuinely flexible metadata, not as a substitute for relational modeling.

## 8. Future Tables — Not for Initial Implementation

Potential later entities include:

- `feeding_plans`, `feeding_plan_items`
- `riding_lessons`, `lesson_participants`
- `invoices`, `invoice_items`, `payments`
- `facilities`, `horse_locations`
- `ai_conversations`, `ai_messages`
- geospatial locations and PostGIS geometry columns

Do not create these tables until a concrete feature requires them.

## 9. Migration and Data Quality Practices

- Make schema changes through version-controlled migrations.
- Use foreign keys and unique constraints deliberately.
- Avoid irreversible migrations unless there is a documented recovery plan.
- Seed only stable reference data and development fixtures.
- Test migrations on a copy or staging dataset before production.
- Add automated tests for tenant-consistent relationships.
- Document any denormalization and how it is kept consistent.
