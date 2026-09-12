<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Workflow rules
    |--------------------------------------------------------------------------
    | Feature flags are driven by the FeatureFlagService (feature_flags table).
    | Numeric/tunable values live here.
    */
    'workflow' => [
        // Maximum number of requests in 'in_review' per admin (feature flag:
        // 'workflow_in_review_limit' must be enabled for the rule to apply).
        'in_review_limit' => env('FSB_WORKFLOW_IN_REVIEW_LIMIT', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | Action deadlines (hours per status)
    |--------------------------------------------------------------------------
    | Command workflow:check-deadlines flags requests and orders that stay
    | longer than the configured duration in an actionable status (feature
    | flag 'sla_deadlines_autolock' must be enabled for the rule to apply).
    |
    | requests: statuses of sourcing_requests.
    | orders:   statuses of sourcing_orders. A 'paid' deadline is also
    |           escalated (reported) to super admins.
    */
    'sla' => [
        'requests' => [
            'in_review' => 24,     // price the request and move it to Quoted, otherwise block new work
            'negotiating' => 24,   // update the price for the client or close the negotiation
        ],
        'orders' => [
            'paid' => 24,              // buy the goods and move the order to shipping
            'in_transit_china' => 48,  // upload parcel photo + label + China tracking number
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Shipping label PNG rendering (GD)
    |--------------------------------------------------------------------------
    | The shipping label can be output as a 300 DPI PNG via GD (feature flag
    | 'label_image_output'). The PDF rendering stays the default.
    */
    'label' => [
        'dpi' => env('FSB_LABEL_DPI', 300),
        'font_path' => base_path('vendor/dompdf/dompdf/lib/fonts/DejaVuSans.ttf'),
        'font_bold_path' => base_path('vendor/dompdf/dompdf/lib/fonts/DejaVuSans-Bold.ttf'),
        'logo_path' => public_path('images/logo.png'),
    ],
];
