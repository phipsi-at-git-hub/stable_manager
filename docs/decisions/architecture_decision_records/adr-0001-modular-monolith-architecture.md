# ADR-0001: Modular Monolith Architecture

## Status
Accepted

## Context
StableManager is designed as a modular web application for
multiple horse businesses.

The application must support clear domain boundaries,
independent module development and long-term maintainability.

## Decision
StableManager uses a modular monolith architecture.

All business modules are part of one Laravel application
and share a common deployment unit.

## Consequences

- Clear separation of business domains.
- Shared authentication and infrastructure.
- Simplified deployment and operations.
- No distributed service infrastructure required.
- Module boundaries must be enforced through architecture rules.
