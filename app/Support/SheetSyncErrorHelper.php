<?php

namespace App\Support;

/**
 * Convertit les erreurs techniques de synchronisation sheet en messages utilisateur (FR).
 */
class SheetSyncErrorHelper
{
    public static function toUserMessage(\Throwable $e): string
    {
        $message = $e->getMessage();
        $code = $e->getCode();

        // Erreurs d'authentification / accès
        if (str_contains($message, 'credentials') || str_contains($message, 'Credentials')) {
            return __('The sheet connection credentials are invalid or expired. Please check the configuration in Shipping Companies.');
        }
        if (str_contains($message, 'token') || str_contains($message, 'Token') || str_contains($message, '401') || str_contains($message, '403')) {
            return __('The sheet access token is invalid or expired. Please update it in the shipping company settings.');
        }
        if (str_contains($message, 'Permission') || str_contains($message, 'permission') || str_contains($message, '403')) {
            return __('Access to the sheet was denied. Please check the permissions of the linked account.');
        }

        // Google Sheet
        if (str_contains($message, 'Google') || str_contains($message, 'spreadsheet')) {
            if (str_contains($message, 'not found') || str_contains($message, '404')) {
                return __('The Google Sheet was not found. Please check the Sheet ID in the shipping company settings.');
            }
            if (str_contains($message, 'quota') || str_contains($message, 'rate limit')) {
                return __('Too many requests to Google Sheet. Please try again in a few minutes.');
            }
        }

        // Lark
        if (str_contains($message, 'Lark') || str_contains($message, 'lark') || str_contains($message, 'sheet') && str_contains($message, 'token')) {
            if (str_contains($message, 'not found') || str_contains($message, 'Sheet')) {
                return __('The Lark sheet was not found. Please check the spreadsheet token and sheet name.');
            }
        }

        // Réseau / timeout
        if (str_contains($message, 'timeout') || str_contains($message, 'Timeout') || str_contains($message, 'Connection')) {
            return __('The connection to the sheet service timed out. Please try again later.');
        }
        if (str_contains($message, 'Network') || str_contains($message, 'network') || $code === 0) {
            return __('A network error occurred. Please check your connection and try again.');
        }

        // Config manquante
        if (str_contains($message, 'Config missing') || str_contains($message, 'not configured') || str_contains($message, 'not have a Google Sheet ID')) {
            return __('The shipping company sheet is not configured. Please set up Google Sheet or Lark in Shipping Companies.');
        }

        // Message par défaut (court, sans jargon)
        return __('Synchronization to the sheet failed. Please try again or check the shipping company configuration.');
    }
}
