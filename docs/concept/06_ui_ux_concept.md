# StableManager — UI/UX Concept

**Document status:** Working baseline  
**Version:** 0.2

## 1. UX Goals

StableManager should feel like a dependable daily work tool for a horse business. It must help users find information and complete common actions without requiring extensive training.

The interface should be:

- Clear and calm rather than visually busy.
- Practical for frequent use.
- Consistent across modules.
- Responsive on desktop, tablet, and mobile.
- Accessible and understandable.
- Efficient for users who may be working outdoors or switching between tasks.

## 2. Design Principles

1. **Show the next useful action.** Pages should make primary actions obvious.
2. **Prioritize scanning.** Use clear hierarchy, concise labels, and useful summaries.
3. **Avoid unnecessary data entry.** Reuse existing records and provide sensible defaults.
4. **Make context visible.** Users should always know which Stable they are working in.
5. **Protect against mistakes.** Use validation, confirmations for destructive actions, and clear feedback.
6. **Use progressive disclosure.** Keep routine views concise and place less common details in secondary sections.
7. **Support touch interaction.** Controls must be comfortably usable on tablets and phones.

## 3. Visual Direction

Suggested visual language:

- Deep forest green as a primary brand color.
- Warm off-white, sand, and neutral surfaces.
- Dark charcoal text for readability.
- Restrained accent colous for status and emphasis.
- Natural, professional imagery used selectively.

Avoid a rustic or overly decorative aesthetic. The product should look like modern business software with an equestrian identity.

Colour must not be the sole indicator of status. Pair color with text, icons, or other visual cues.

## 4. Application Navigation

Suggested primary navigation:

```text
Dashboard
Horses
People
Health & Care
Calendar
Tasks
Documents
Stable Settings
```

The exact navigation may vary by role. Users should not see navigation items that they cannot use, but backend authorization must remain authoritative.

### Application shell

The shell should include:

- Stable selector.
- Primary navigation.
- Page title and optional breadcrumbs.
- Contextual primary action.
- Notifications entry point.
- User/account menu.
- Responsive navigation behavior.

The active Stable should remain visible and easy to change.

## 5. Dashboard

The dashboard should answer practical questions:

- What needs attention today?
- What appointments are coming up?
- Which tasks are due or overdue?
- Which health follow-ups are approaching?
- Are there important recent updates?

Suggested widgets:

- Today's appointments.
- Open and overdue tasks.
- Upcoming health follow-ups.
- Recently updated horses or records.
- Quick actions.

Widgets must be permission-aware. Do not show counts or summaries that reveal the existence of information the user cannot access.

## 6. Horse List and Horse Profile

### Horse list

Useful elements:

- Search by horse name and supported identifiers.
- Filters for status, breed, and other practical attributes.
- Clear active/inactive status.
- Compact summary information.
- Obvious create-horse action.
- Pagination or incremental loading for larger stables.

### Horse profile

Suggested information architecture:

- Overview.
- People and ownership.
- Health and care.
- Appointments.
- Tasks.
- Documents.
- Activity/history.

The overview should show the most useful facts without becoming a dense data sheet. Related records should be accessible through clearly named sections or tabs.

## 7. People

The people area should distinguish between a business contact and a login account. A person profile may include:

- Name and contact details.
- Relationship to the Stable.
- Linked horses.
- Optional linked user account.
- Relevant notes.
- Status or archived state.

Forms should make it clear whether the user is creating a contact, inviting a user, or linking an existing account.

## 8. Health and Care

The health area should support chronological record keeping.

Recommended layout:

- Horse context.
- Timeline or list of health/care events.
- Category and date filters.
- Follow-up date visibility.
- Related documents.
- Clear distinction between recorded facts and user-entered notes.

Do not use language or interface patterns that imply the application diagnoses conditions or prescribes treatment.

## 9. Calendar

The calendar should support:

- Month, week, and agenda/list views.
- Clear start and end times.
- Stable timezone handling.
- Filters by horse, appointment type, or participant where useful.
- Quick appointment creation.
- Links from an appointment to related horses and people.

Mobile layouts should favor an agenda/list view when a full calendar grid becomes difficult to use.

## 10. Tasks

Task views should help users understand what is open, who is responsible, and what is due.

Suggested views:

- My tasks.
- All permitted tasks.
- Due soon.
- Overdue.
- Completed.

A task card or row should show title, status, due date, assignee, and linked horse where applicable. Keep task status and priority separate.

## 11. Documents

Documents should be discoverable through both a central document area and the records to which they are attached.

Show useful metadata:

- Title.
- File type.
- Upload date.
- Uploader, where appropriate.
- Category.
- Related horse or record.

Downloads must use an authorized backend route. Never assume that obscuring a storage URL is sufficient protection.

## 12. Forms and Feedback

Forms should:

- Group related fields.
- Mark required fields clearly.
- Validate both client-side and server-side.
- Preserve entered values when validation fails.
- Explain errors in plain language.
- Use appropriate controls for dates, times, and selections.
- Avoid asking for information that is not needed for the current task.

After an action, provide clear feedback. For destructive actions, explain what will happen and whether it can be reversed.

## 13. Responsive Behavior

### Desktop

- Persistent primary navigation where space permits.
- Tables and multi-column layouts for information-dense views.
- Contextual side panels where they improve workflows.

### Tablet

- Collapsible navigation.
- Touch-friendly actions.
- Tables that can adapt to cards or horizontally scroll when appropriate.

### Mobile

- Compact navigation.
- Prioritized summary information.
- Single-column forms.
- Large touch targets.
- Agenda/list calendar view.
- Avoid hover-only interactions.

## 14. Accessibility

Target WCAG 2.2 AA as a design objective where practical.

- Semantic headings and landmarks.
- Keyboard-accessible navigation and controls.
- Visible focus states.
- Labels for all form controls.
- Sufficient text and interface contrast.
- Status information that does not rely on color alone.
- Accessible validation and notification messages.
- Alternative text for meaningful images.

## 15. Initial Screen Inventory

| Screen | Primary purpose |
|---|---|
| Sign in / password reset | Secure account access |
| Stable selection | Choose active workspace |
| Dashboard | Daily operational overview |
| Horse list | Find and manage horses |
| Horse detail | View a horse's complete workspace |
| Create/edit horse | Maintain horse information |
| People list/detail | Manage business contacts |
| Health record list/detail | Review care history |
| Create/edit health record | Record an event and follow-up |
| Calendar | Review appointments |
| Appointment detail/form | Manage an appointment |
| Task list/detail | Track work |
| Document library | Find and manage files |
| Stable settings | Configure workspace and memberships |
| User settings | Manage personal account preferences |

## 16. UX Validation

Validate the interface through task-based walkthroughs. Example tasks:

- Add a new horse.
- Find a horse and identify its primary contact.
- Record a veterinary visit and attach a document.
- Find the next upcoming appointment.
- Create and complete a task.
- Invite a staff member.
- Switch between two Stables.
- Attempt to open a resource without permission.

Record friction points and simplify workflows before adding more features.
