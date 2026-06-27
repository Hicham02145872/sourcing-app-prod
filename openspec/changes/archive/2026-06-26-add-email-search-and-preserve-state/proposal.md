## Why

Users searching across admin and client lists cannot find records by client email, and returning to a list after viewing a detail page loses the active search/filter state — forcing users to re-enter their search query.

## What Changes

- Add email search to all admin list controllers that already have a search bar but don't search by email
- Add email search to client-side lists (handling, dashboard, orders)
- Add `->withQueryString()` to paginators missing it so search persists across pages
- Ensure "back" links on show/detail views retain the previous list search state via `url()->previous()` or session storage
- Add text search (including email) to RefundRequest and other list views missing it entirely

## Capabilities

### New Capabilities
- `email-search`: Add email field to search queries across all list controllers that support text search
- `search-state-preservation`: Preserve search/filter state when navigating from a list to a detail page and back

### Modified Capabilities
- (none — no existing specs in openspec/specs/)

## Impact

- Controllers: ~15 controller index/handling methods across Admin and Client namespaces
- Views: Back/return links in show.blade.php files (admin and client)
- No new dependencies required
- No database migration needed
