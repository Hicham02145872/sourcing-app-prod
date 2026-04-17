<?php

return [
    'common' => [
        'legal' => 'Legal',
        'last_updated' => 'Last updated: April 2026',
        'copyright' => '© 2026 Fast Sourcing Brothers LLC. Registered in Wyoming, USA.',
    ],

    'privacy' => [
        'title' => 'Privacy Policy',
        'sections' => [
            'collection' => [
                'title' => '1. Data Collection',
                'body' => 'We collect information necessary to process your sourcing requests and provide support. This includes your name, email, company details, and shipping address.',
            ],
            'usage' => [
                'title' => '2. Use of Data',
                'body' => 'Your data is used solely for service fulfillment, communication regarding your orders, and improving our platform\'s user experience.',
            ],
            'security' => [
                'title' => '3. Data Security',
                'body' => 'We implement enterprise-grade encryption and security protocols to protect your sensitive business information. We never sell your data to third parties.',
            ],
        ],
    ],

    'terms' => [
        'title' => 'Terms of Service',
        'intro' => 'Welcome to Fast Sourcing Brothers. By using our platform, you agree to the following terms and conditions.',
        'sections' => [
            'acceptance' => [
                'title' => '1. Acceptance of Terms',
                'body' => 'The services provided by Fast Sourcing Brothers are subject to these Terms of Service. By accessing or using our website, you agree to be bound by these terms.',
            ],
            'services' => [
                'title' => '2. Services Provided',
                'body' => 'Fast Sourcing Brothers provides product sourcing, quality inspection, and logistics management services. Each service is subject to specific agreements signed during the order process.',
            ],
            'obligations' => [
                'title' => '3. User Obligations',
                'body' => 'Users must provide accurate information for all sourcing requests and comply with international trade regulations.',
            ],
        ],
    ],

    'refund' => [
        'title' => 'Refund Policy',
        'intro' => 'At Fast Sourcing Brothers, we guarantee your investment. You are eligible for a 100% full refund in the following cases:',
        'points' => [
            'shipping_delay' => 'If the shipping exceeds the promised timeframe for your specific country.',
            'damage_or_loss' => 'If the inventory is damaged or lost during transit.',
            'spec_mismatch' => 'If the sourced products do not match the agreed-upon specifications.',
        ],
        'processing' => [
            'title' => 'Processing Time',
            'body_before' => 'Refunds will be processed back to the original payment method within',
            'days' => '7-10 business days',
            'body_after' => 'following the approval of your request.',
        ],
    ],

    'shipping' => [
        'title' => 'Shipping Policy',
        'intro' => 'Fast Sourcing Brothers provides global logistics solutions tailored to each destination. Our shipping timeframe depends on the target country and the complexity of the sourcing.',
        'cards' => [
            'partners' => [
                'title' => 'Carrier Partners',
                'body' => 'We work exclusively with premium carriers including DHL, FedEx, UPS, and Aramex to ensure safe and trackable delivery.',
            ],
            'tracking' => [
                'title' => 'Tracking',
                'body' => 'All shipments include comprehensive tracking. Live updates are available via your client dashboard.',
            ],
        ],
        'commitments' => [
            'title' => 'Delivery Commitments',
            'body_before' => 'We provide a specific delivery timeframe for each order based on the destination. If we exceed this timeframe, you are eligible for a partial or full refund as per our ',
            'body_after' => '.',
        ],
    ],

    'support' => [
        'title' => 'Support',
        'contact_title' => 'Contact Support',
        'intro' => 'Need assistance or have questions about a sourcing request? Our dedicated team is here to help.',
        'labels' => [
            'email_us' => 'Email us',
            'our_hq' => 'Our HQ',
        ],
        'fastest_response' => [
            'title' => 'Fastest Response',
            'quote' => '"We typically respond to support inquiries within 2-4 hours during business days."',
            'role' => 'Managing Director',
        ],
    ],
];
