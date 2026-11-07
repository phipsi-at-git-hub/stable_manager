
# StableManager — Product Concept

**Document status:** Working baseline  
**Version:** 0.2  
**Product type:** Modular web application for equestrian businesses

## 1. Executive Summary

StableManager is a web-based management platform for riding stables, boarding facilities, and other horse businesses. It brings essential operational information into one structured workspace: horses, people, health records, appointments, tasks, and documents.

The product is intended to reduce fragmented communication, duplicated data entry, and reliance on paper records or disconnected spreadsheets. It should help a stable understand what is happening, what needs attention, and where the relevant information can be found.

StableManager will be developed as a modular application. The initial product should prioritize dependable core workflows, clear data ownership, security, and ease of use. Advanced capabilities such as AI assistance, geospatial features, feeding optimization, lesson booking, and financial management can be considered after the core product has proven useful.

## 2. Product Vision

**Make the day-to-day management of a horse business clearer, safer, and easier by providing one reliable place for operational information.**

StableManager should become a practical working tool rather than an administrative system that creates more work. Common actions must be easy to find, quick to complete, and usable on desktop, tablet, and mobile devices.

## 3. Problem Statement

Horse businesses often manage information across several disconnected tools, including paper folders, spreadsheets, messaging applications, calendars, and individual staff members' knowledge. This can lead to:

- Out-of-date or duplicated horse and contact information.
- Important health events and follow-up dates being overlooked.
- Unclear responsibility for routine or one-off tasks.
- Appointments being missed or communicated inconsistently.
- Documents being difficult to locate.
- Limited visibility for owners and managers.
- Operational knowledge being lost when a staff member is absent or leaves.

StableManager aims to provide a consistent, searchable, permission-controlled source of operational information.

## 4. Target Users and Customers

### Primary customer groups

- Boarding and livery stables.
- Riding schools.
- Mixed businesses that combine boarding, training, and riding lessons.
- Small and medium-sized equestrian businesses.

### User groups

- Stable owners and operators.
- Stable managers.
- Employees and stable staff.
- Trainers and instructors.
- Horse owners and authorized representatives.
- Other people who need limited access to relevant horse information.

The initial product should be designed for both boarding stables and riding schools, while keeping the domain model flexible enough for other horse businesses.

## 5. Product Principles

1. **Operational usefulness first:** prioritize workflows that solve frequent, real problems.
2. **One reliable record:** avoid unnecessary duplication of horses, people, and documents.
3. **Secure by default:** tenant boundaries and permissions must be enforced on the server.
4. **Clear responsibility:** tasks and appointments should have understandable ownership.
5. **Progressive complexity:** keep everyday screens simple; expose advanced detail when needed.
6. **Mobile-aware:** common stable activities may happen away from a desk.
7. **Modular evolution:** add capabilities without turning the application into a collection of tightly coupled features.
8. **Data portability:** provide a path to export customer data in a usable format.

## 6. Product Scope

### Initial product capabilities

- User accounts and authentication.
- Stable/business workspace management.
- Memberships and role-based permissions.
- Horse records and horse profiles.
- People records and relationships to horses.
- Health and care records.
- Calendar and appointments.
- Tasks and task assignments.
- Document storage and association.
- Dashboard and notifications.
- Audit history for important actions.

### Explicitly deferred capabilities

The following are not part of the initial release unless later prioritized:

- AI assistant and automated recommendations.
- GIS, mapping, and geospatial analysis.
- Feeding plans and ration calculations.
- Riding lesson scheduling and customer booking.
- Invoicing, payments, and full accounting.
- Payroll and staff time tracking.
- Marketplace or public horse listings.
- Native mobile applications.

The architecture should not prevent these capabilities from being added later, but the initial implementation should not build speculative infrastructure for them.

## 7. Product Boundaries and Assumptions

- A **Stable** is the primary customer workspace and tenant boundary.
- A user may belong to more than one Stable.
- A person may exist as a contact without having a login account.
- A login account may be linked to a person record where appropriate.
- Horse owners may receive access to selected information, but access is controlled by the stable and is not automatic.
- Each stable controls its own users, horses, records, and documents.
- StableManager is initially a cloud-hosted, multi-tenant SaaS application.
- German is the initial product interface language. The technical design should avoid assumptions that prevent future localization.

## 8. Success Measures

The initial product should be evaluated using practical measures:

- A stable can create and maintain horse records without assistance.
- Authorized users can find a horse's key information quickly.
- Health records and follow-up dates can be recorded consistently.
- Tasks can be assigned and their status understood.
- Appointments can be created and viewed by authorized users.
- Users cannot access another stable's data.
- Documents can be uploaded, found, and downloaded securely.
- Core workflows work reliably on desktop and mobile-sized screens.

Potential quantitative measures after launch include active stables, weekly active users per stable, completion rate of task workflows, time required to find key records, and support requests per active stable.

## 9. Product Risks

| Risk | Potential impact | Mitigation |
|---|---|---|
| Scope grows too quickly | Core product remains unfinished | Keep deferred capabilities outside the initial scope |
| Tenant isolation is incomplete | Severe data exposure | Design tenant boundaries into schema, authorization, and tests |
| Too much data entry | Low adoption | Use sensible defaults, concise forms, and reusable records |
| Unclear person/account model | Duplicate identities and confusing access | Keep person records distinct from login accounts |
| Mobile workflows are awkward | Poor use in daily stable operations | Test common tasks at narrow screen widths and on touch devices |
| Health information is treated as medical advice | User safety and liability concerns | Present records as documentation, not diagnosis or veterinary advice |

## 10. Product Decisions to Validate

These decisions should be confirmed before the corresponding functionality is implemented:

- Exact customer subscription and pricing model.
- Whether horse-owner access is included in the first public release or introduced shortly afterward.
- Detailed access rules for horse owners and external trainers.
- Required horse identification fields for target markets.
- Document retention and deletion policies.
- Notification channels for the first release.
- Data import and export formats.
- Legal and privacy requirements for the markets in which the product will operate.

## 11. Definition of Product Success

The first release is successful when a real stable can use StableManager for its everyday horse, people, health, appointment, task, and document workflows without relying on the developer to operate the system. Reliability, security, and clarity are more important than the number of features.
