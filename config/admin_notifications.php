<?php

/**
 * Notifications e-mail destinées aux comptes admin / super_admin.
 * Clés utilisées pour les préférences (colonne users.admin_mail_notification_keys).
 */
return [
    'types' => [
        [
            'key' => 'sourcing_request_created',
            'label' => 'New sourcing request (client)',
            'notification_classes' => [
                \App\Notifications\SourcingRequestCreated::class,
            ],
        ],
        [
            'key' => 'client_registered',
            'label' => 'New client self-registration',
            'notification_classes' => [
                \App\Notifications\ClientRegisteredForAdmins::class,
            ],
        ],
        [
            'key' => 'sourcing_request_assigned',
            'label' => 'Sourcing request assigned / reassignment',
            'notification_classes' => [
                \App\Notifications\SourcingRequestAssigned::class,
            ],
        ],
        [
            'key' => 'dossier_assignment_removed',
            'label' => 'Assignment removed from admin',
            'notification_classes' => [
                \App\Notifications\DossierAssignmentRemoved::class,
            ],
        ],
        [
            'key' => 'quotation_accepted_admin',
            'label' => 'Quotation accepted (admin mail)',
            'notification_classes' => [
                \App\Notifications\QuotationAccepted::class,
            ],
        ],
        [
            'key' => 'quotation_rejected_admin',
            'label' => 'Quotation rejected (admin mail)',
            'notification_classes' => [
                \App\Notifications\QuotationRejected::class,
            ],
        ],
        [
            'key' => 'refund_request_created',
            'label' => 'Refund request created',
            'notification_classes' => [
                \App\Notifications\RefundRequestCreated::class,
            ],
        ],
        [
            'key' => 'proof_of_payment_uploaded',
            'label' => 'Proof of payment uploaded',
            'notification_classes' => [
                \App\Notifications\ProofOfPaymentUploaded::class,
            ],
        ],
        [
            'key' => 'admin_sourcing_request_status',
            'label' => 'Sourcing status change by client (assigned admin)',
            'notification_classes' => [
                \App\Notifications\AdminSourcingRequestStatusUpdated::class,
            ],
        ],
        [
            'key' => 'quotation_negotiation_requested',
            'label' => 'Quotation negotiation requested',
            'notification_classes' => [
                \App\Notifications\QuotationNegotiationRequested::class,
            ],
        ],
    ],

    /**
     * Super admin : par défaut uniquement ces clés en e-mail.
     * Admin : toutes les clés (voir AdminNotificationMailGate::defaultEnabledKeysForRole).
     */
    'super_admin_default_keys' => [
        'sourcing_request_created',
        'client_registered',
    ],
];
