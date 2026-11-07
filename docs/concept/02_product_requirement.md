# StableManager — Product Requirements

**Document status:** Working baseline  
**Version:** 0.2

## 1. Purpose

This document defines the functional and non-functional requirements for the initial StableManager product. Requirements are written to support implementation, testing, and later product decisions. They are not a commitment to implement every deferred capability.

## 2. Scope

The initial release covers authentication, stable workspaces, memberships and permissions, horses, people, health records, appointments, tasks, documents, dashboard information, and basic notifications.

## 3. Requirement Conventions

- **Must:** required for the initial release.
- **Should:** important, but may be deferred if necessary.
- **Could:** optional improvement.
- **Deferred:** explicitly outside the initial release.

Each requirement has an identifier to make it traceable in implementation tasks and tests.

## 4. User and Workspace Requirements

### 4.1 Accounts and Authentication

| ID | Priority | Requirement |
|---|---|---|
| AUTH-001 | Must | A user can register or be invited according to the configured account policy. |
| AUTH-002 | Must | A user can sign in and sign out securely. |
| AUTH-003 | Must | A user can request a password reset using a verified channel. |
| AUTH-004 | Must | Authentication endpoints are protected against common abuse, including rate limiting. |
| AUTH-005 | Should | A user can update basic account settings. |
| AUTH-006 | Should | A user can review and revoke active sessions where supported. |

### 4.2 Stable Workspaces and Memberships

| ID | Priority | Requirement |
|---|---|---|
| TEN-001 | Must | An authorized user can create a Stable workspace. |
| TEN-002 | Must | A user can belong to multiple Stable workspaces. |
| TEN-003 | Must | A user can select an active Stable workspace. |
| TEN-004 | Must | Every tenant-owned record is associated with exactly one Stable. |
| TEN-005 | Must | A user can only access a Stable through a valid membership and permission. |
| TEN-006 | Must | A Stable owner or manager can invite users, subject to permission rules. |
| TEN-007 | Should | A Stable owner can deactivate a membership without deleting the user's account. |
| TEN-008 | Should | A Stable can maintain its name, contact details, and basic settings. |

### 4.3 People and Identity

| ID | Priority | Requirement |
|---|---|---|
| PPL-001 | Must | Authorized users can create, view, update, and archive person records. |
| PPL-002 | Must | A person record can exist without a login account. |
| PPL-003 | Must | A person can optionally be linked to a user account. |
| PPL-004 | Must | A person can have contact details and an address where required. |
| PPL-005 | Must | A person can be associated with one or more horses using a relationship type. |
| PPL-006 | Should | Relationships can have effective start and end dates. |
| PPL-007 | Should | Duplicate person records can be identified and resolved by an authorized user. |

## 5. Horse Management Requirements

| ID | Priority | Requirement |
|---|---|---|
| HOR-001 | Must | Authorized users can create, view, update, and archive horse records. |
| HOR-002 | Must | A horse belongs to one Stable workspace. |
| HOR-003 | Must | A horse record supports name, date of birth, sex, breed, color, and descriptive notes. |
| HOR-004 | Should | A horse record supports UELN or another relevant identification number. |
| HOR-005 | Should | A horse record supports height and additional identification details. |
| HOR-006 | Must | A horse has a lifecycle status, such as active, inactive, sold, or deceased. |
| HOR-007 | Must | Authorized users can associate people with a horse using a defined relationship type. |
| HOR-008 | Should | A horse can have one or more profile images. |
| HOR-009 | Must | A horse profile presents its key details and links to related health records, appointments, tasks, people, and documents. |
| HOR-010 | Must | Horse lists support search and practical filtering. |
| HOR-011 | Must | Users cannot view or modify horses belonging to another Stable. |

## 6. Health and Care Records

| ID | Priority | Requirement |
|---|---|---|
| HLT-001 | Must | Authorized users can create, view, update, and archive health/care records. |
| HLT-002 | Must | A health record is associated with a horse and a Stable. |
| HLT-003 | Must | A record supports category, event date, title or summary, and notes. |
| HLT-004 | Should | A record supports provider/practitioner details. |
| HLT-005 | Should | A record supports a next-due or follow-up date. |
| HLT-006 | Must | A record can have associated documents. |
| HLT-007 | Should | Users can filter records by horse, category, and date range. |
| HLT-008 | Must | The system can display upcoming follow-up dates. |
| HLT-009 | Must | Health records are presented as user-entered documentation, not as a diagnosis or substitute for veterinary advice. |
| HLT-010 | Must | Health records are protected by stable membership and record-level permissions. |

Suggested initial categories include veterinary visit, vaccination, dental care, farrier, physiotherapy, medication record, injury, treatment, and general care. Categories should be configurable or extensible rather than hard-coded into every part of the application.

## 7. Calendar and Appointment Requirements

| ID | Priority | Requirement |
|---|---|---|
| CAL-001 | Must | Authorized users can create, view, update, and cancel appointments. |
| CAL-002 | Must | An appointment belongs to a Stable. |
| CAL-003 | Must | An appointment supports title, start, end, and optional description. |
| CAL-004 | Should | An appointment can be associated with one or more horses. |
| CAL-005 | Should | An appointment can be associated with people or service providers. |
| CAL-006 | Must | Users can view appointments in a calendar and list view. |
| CAL-007 | Must | Calendar views respect the active Stable and the user's permissions. |
| CAL-008 | Should | Appointments can have reminders. |
| CAL-009 | Could | Recurring appointments can be configured with clear exception handling. |
| CAL-010 | Must | Date/time storage and display handle time zones consistently. |

## 8. Task Management Requirements

| ID | Priority | Requirement |
|---|---|---|
| TSK-001 | Must | Authorized users can create, view, update, and complete tasks. |
| TSK-002 | Must | A task belongs to a Stable. |
| TSK-003 | Must | A task supports title, description, status, and optional due date. |
| TSK-004 | Should | A task can be assigned to one or more members. |
| TSK-005 | Should | A task can be associated with horses, people, or appointments where relevant. |
| TSK-006 | Should | Users can add comments or updates to a task. |
| TSK-007 | Must | Users can filter tasks by status, assignee, due date, and related horse. |
| TSK-008 | Must | The system distinguishes open, in-progress, completed, and cancelled tasks. |
| TSK-009 | Must | Task visibility and actions are permission-controlled. |
| TSK-010 | Should | Overdue and due-soon tasks are clearly identifiable. |

## 9. Document Management Requirements

| ID | Priority | Requirement |
|---|---|---|
| DOC-001 | Must | Authorized users can upload documents. |
| DOC-002 | Must | Documents are associated with a Stable and, where applicable, a related record. |
| DOC-003 | Must | Users can view document metadata and download authorized files. |
| DOC-004 | Must | File access is mediated by server-side authorization; storage URLs must not expose tenant data publicly. |
| DOC-005 | Must | The system validates file size and allowed file types. |
| DOC-006 | Should | Documents support title, description, category, and upload date. |
| DOC-007 | Should | Users can search or filter documents by relevant metadata. |
| DOC-008 | Must | Deletion and retention behavior is explicit and auditable. |

## 10. Dashboard and Notifications

| ID | Priority | Requirement |
|---|---|---|
| DASH-001 | Must | The dashboard shows a useful overview for the active Stable. |
| DASH-002 | Must | The dashboard can show upcoming appointments and tasks. |
| DASH-003 | Should | The dashboard can show upcoming health follow-ups. |
| DASH-004 | Must | Dashboard widgets only include information the current user is authorized to see. |
| NOT-001 | Should | Users receive in-app notifications for relevant assignments and reminders. |
| NOT-002 | Should | Notifications can be marked as read. |
| NOT-003 | Could | Email notifications can be enabled for selected events. |

## 11. Audit and Administration

| ID | Priority | Requirement |
|---|---|---|
| AUD-001 | Must | Important security-sensitive actions are recorded in an audit log. |
| AUD-002 | Must | Audit entries identify the actor, action, target, Stable, and timestamp where available. |
| AUD-003 | Must | Ordinary users cannot alter audit history. |
| ADM-001 | Must | A platform-level administrative capability is separated from Stable-level roles. |
| ADM-002 | Must | Platform administration is restricted and auditable. |

## 12. Non-Functional Requirements

### Security and Privacy

- All application traffic must use HTTPS in production.
- Passwords must be stored using a modern password hashing algorithm.
- Authorization must be enforced server-side on every protected operation.
- Tenant isolation must be tested, including negative access tests.
- Secrets must not be committed to source control.
- Uploaded files must be validated and served through authorized access.
- Sensitive actions must be logged without unnecessarily copying sensitive content into logs.
- Data processing, retention, export, and deletion must be designed to meet applicable privacy obligations.

### Reliability and Data Integrity

- Database constraints must protect critical relationships and uniqueness rules.
- Destructive operations should use archive/soft-delete behavior where business history must be retained.
- Backups must be automated and restoration must be tested.
- Errors must be logged with enough context to diagnose problems without exposing secrets.

### Performance and Scalability

- Common list and detail pages should feel responsive under the expected initial workload.
- Lists must use pagination for potentially large datasets.
- Database queries should be indexed based on actual access patterns.
- Background jobs should be used for tasks that should not block web requests, such as large file processing and outbound notifications.
- The initial architecture should support growth without requiring microservices.

### Usability and Accessibility

- Core workflows must work on desktop, tablet, and mobile screen sizes.
- Forms must provide clear validation and actionable error messages.
- Important actions must have clear confirmation or undo behavior where appropriate.
- Interfaces should use semantic structure and accessible labels.
- Color must not be the only way to communicate status.

### Maintainability

- Code should be organized by domain module.
- Automated tests should cover critical business rules and security boundaries.
- Code style and static analysis should be applied consistently.
- API behavior and important architectural decisions should be documented.

## 13. Deferred Requirements

AI features, GIS, feeding plans, lesson booking, invoicing, payments, payroll, and native mobile apps are deferred. They should receive their own requirements and threat/privacy review before implementation.

## 14. Acceptance Baseline for the Initial Release

The initial release is acceptable when:
- A user can sign in and access an authorized Stable.
- Stable memberships and permissions work as specified.
- Horses and people can be managed and linked.
- Health records can be recorded and retrieved.
- Appointments and tasks can be created and managed.
- Documents can be uploaded and securely retrieved.
- Dashboard information is permission-aware.
- Cross-tenant access attempts are rejected.
- Critical workflows have automated tests and operational backups.
