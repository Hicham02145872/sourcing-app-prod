## Why

The quotation management workflow has several friction points and missing features that degrade the admin experience and limit functionality. File uploads lack video support and delete capability, quotation list ordering is counterintuitive, the edit form diverges from the create form, and a bad gateway error blocks quotation updates. Additionally, clients need visibility into shipping fees during sourcing request creation, controllable via a feature flag.

## What Changes

1. **Feature flag for shipping fees pop-up**: When a client creates a sourcing request, a pop-up displays shipping fee information. Controlled via the existing Dev Dashboard feature flag system.
2. **Quotation file uploads support video + delete**: File upload fields in quotation creation accept video files (mp4, mov, avi) and allow deleting selected files before form submission.
3. **Quotation list ordering**: Latest quotations appear first in the admin list (fix existing ordering if broken).
4. **Edit quotation unified with create**: The edit quotation form matches the create quotation form in layout, fields, and UX.
5. **Fix bad gateway on quotation update**: Resolve the 502 Bad Gateway error when clicking "Update Quotation" in the edit form.

## Capabilities

### New Capabilities
- `shipping-fees-popup`: Feature-flagged pop-up showing shipping fees to clients during sourcing request creation, toggleable via the Dev Dashboard.
- `quotation-media-uploads`: Video file support and pre-submission delete capability for quotation file uploads.
- `quotation-list-ordering`: Chronological ordering with newest quotations first in admin list.

### Modified Capabilities
- `sourcing-platform-updates-v3` (implied): The quotation edit form is updated to match the create form; the update endpoint is fixed to resolve the 502 error.

## Impact

- **Controllers**: `QuotationController@update` needs debugging and fix for 502 error.
- **Views**: `admin/quotations/edit.blade.php` rewritten to match `create.blade.php`; `create.blade.php` updated for video uploads and file deletion UI.
- **Feature Flag**: New `shipping_fees_popup` flag added via Dev Dashboard.
- **Client Views**: `client/sourcing-requests/create.blade.php` updated with shipping fees pop-up gated by feature flag.
- **Routes**: Minor additions for shipping fees pop-up data endpoint if needed.
