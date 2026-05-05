# LarkSuite (Feishu) Integration Plan

This document outlines the plan to integrate LarkSuite (Base/Bitable) to automatically sync shipping information for each shipping company.

## 1. Prerequisites (API Credentials)

To integrate LarkSuite, you need to obtain the following information from the [Lark Open Platform](https://open.larksuite.com/):

### Step 1: Create a Lark App
1. Go to the [Lark Open Platform](https://open.larksuite.com/app).
2. Click **Create Custom App**.
3. Go to **Basic Information** -> **Credentials** to find:
    - **App ID**
    - **App Secret**

### Step 2: Enable Permissions
In the App management console, go to **Permission Administration** and enable:
- `bitable:app` (View, edit, and manage Base)
- `bitable:app:readonly` (View Base)

### Step 3: Create the Sheet (Base)
1. In Lark, click on the **+** (Create) button or go to **Base**.
2. Select **New Base** (or choose a template).
3. Name your sheet (e.g., "Shipping Tracking").
4. Add columns that match our data (Order ID, Product, Quantity, etc.).

### Step 4: Get Base & Table IDs
1. Open your Lark Base (Bitable) in the browser.
2. Look at the URL in your browser: `https://your-company.larksuite.com/base/BASE_TOKEN?table=TABLE_ID`
    - **BASE_TOKEN**: The long alphanumeric string after `/base/` and before `?`.
    - **TABLE_ID**: The alphanumeric string after `table=`.

> [!IMPORTANT]
> **Collaborator Access**: You must invite your app to the base. 
> 1. In your Base, click **Share** (top right).
> 2. Search for your **App Name** (the one you created in Step 1).
> 3. Grant it **Can Edit** permissions. Without this, the API will return "No permission".

## 2. Integration Workflow

1. **Status Trigger**: When a `SourcingOrder` status changes to `shipment_preparing`.
2. **Company Config**: Retrieve `lark_app_id`, `lark_secret`, `lark_base_token`, and `lark_table_id` from the linked `ShippingCompany`.
3. **Data Sync**: Append a new row to the Lark Bitable with order details (ID, product, quantity, client info, etc.).

## 3. Proposed Implementation

- **Service**: `App\Services\LarkSheetService` to handle API communication.
- **Listener**: `App\Listeners\SyncOrderToLarkSheet` to trigger the sync.
- **Migration**: Add Lark fields to the `shipping_companies` table.

App id : cli_a9d1affbbe38de1a    app secrect : 6eUjWpgdf1Pdxkz8y0xyFgTHIqT4xAwh